<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use App\Models\FincapMerchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentParentAddress;
use App\Models\StudentParentBankAccount;
use App\Models\StudentParentDetails;
use App\Models\StudentParentDocuments;
use App\Models\StudentAcademicTransportDetails;
use App\Models\DepartmentCategory;
use App\Models\TransportDetails;
use App\Models\InstituteBasicDetails;
use App\Models\StudentSibling;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\StudentsExport;
use App\Notifications\StudentOnboardedNotification;
use App\Traits\SendsInstituteNotifications;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\CourseFeeStructure;
use App\Http\Controllers\institute\Admin\StudentFeeController;

class BulkFeeAssignmentController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;
    use SendsInstituteNotifications;

    /**
     * Show the bulk fee assignment page with class selection
     */
    public function bulkFeeAssignment(Request $request)
    {
        // Get institute/branch context
        $context = $this->getInstituteBranchContext();

        // Check if user has institute access
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        // Get all courses/products for this institute with their fee structures
        $coursesQuery = DB::table('product_details as pd')
            ->join('course_fee_structures as cfs', 'pd.product_id', '=', 'cfs.product_id')
            ->where('pd.institute_id', $context['institute_id'])
            ->select(
                'pd.product_id',
                'pd.sub_type as product_name',
                'pd.branch_id',
                'cfs.batch_id',
                'cfs.batch',
                'cfs.academic_year_id',
                'cfs.academic_year',
                'cfs.sections',
                'cfs.course_fee',
                'cfs.registration_fee'
            )
            ->distinct();

        // Apply branch filter if branch admin
        if ($context['is_branch_admin'] && $context['branch_id']) {
            $coursesQuery->where('pd.branch_id', $context['branch_id']);
        }

        $courses = $coursesQuery->get();

        // Group courses by product for easier display
        $groupedCourses = [];
        foreach ($courses as $course) {
            if (!isset($groupedCourses[$course->product_id])) {
                $groupedCourses[$course->product_id] = [
                    'product_id' => $course->product_id,
                    'product_name' => $course->product_name,
                    'batches' => []
                ];
            }
            
            // Parse sections JSON
            $sections = json_decode($course->sections, true) ?? [];
            
            $groupedCourses[$course->product_id]['batches'][] = [
                'batch_id' => $course->batch_id,
                'batch_name' => $course->batch,
                'academic_year_id' => $course->academic_year_id,
                'academic_year' => $course->academic_year,
                'sections' => $sections,
                'has_course_fee' => !empty($course->course_fee) && $course->course_fee !== 'null' && $course->course_fee !== '[]',
                'has_registration_fee' => !empty($course->registration_fee) && $course->registration_fee !== 'null' && $course->registration_fee !== '[]'
            ];
        }

        return view('instituteAdmin.StudentFiles.BulkFeeAssignment', [
            'groupedCourses' => $groupedCourses,
            'institute_context' => $context
        ]);
    }

    /**
     * Get students by class/batch/section for bulk fee assignment
     */
    public function getStudentsForBulkFee(Request $request)
    {
        // try {
            $request->validate([
                'product_id' => 'required|string',
                'batch_id' => 'required|string',
                'section_id' => 'nullable|string'
            ]);

            $context = $this->getInstituteBranchContext();

            // Build query to get students in this class
            $query = StudentParentDetails::where('institute_id', $context['institute_id'])
                ->whereHas('academicTransportDetails', function($q) use ($request, $context) {
                    $q->where('course_subtype_id', $request->product_id)
                      ->where('batch_id', $request->batch_id);
                    
                    if ($request->filled('section_id') && $request->section_id !== 'undefined' && $request->section_id !== '') {
                        $q->where('section_id', $request->section_id);
                    }

                    // Apply branch filter
                    if ($context['is_branch_admin'] && $context['branch_id']) {
                        $q->where('branch_id', $context['branch_id']);
                    }
                })
                ->with('academicTransportDetails')
                ->select(
                    'student_hash_id',
                    'registration_number',
                    'first_name',
                    'middle_name',
                    'last_name',
                    'email',
                    'mobile',
                    'student_status' // Add student_status to selection
                );

            $students = $query->get();
            // Check which students already have course fee assigned
            $studentHashIds = $students->pluck('student_hash_id')->toArray();
            
            $studentsWithCourseFee = DB::table('student_course_fee_structures')
                ->whereIn('student_hash_id', $studentHashIds)
                ->where('batch_id', $request->batch_id)
                ->distinct('student_hash_id')
                ->pluck('student_hash_id')
                ->toArray();

            // Check which students already have registration fee assigned
            $studentsWithRegFee = DB::table('student_registration_fees')
                ->whereIn('student_hash_id', $studentHashIds)
                ->where('batch_id', $request->batch_id)
                ->distinct('student_hash_id')
                ->pluck('student_hash_id')
                ->toArray();

            // Add flags to each student
            foreach ($students as $student) {
                $student->course_fee_assigned = in_array($student->student_hash_id, $studentsWithCourseFee);
                $student->reg_fee_assigned = in_array($student->student_hash_id, $studentsWithRegFee);
                
                // Determine if registration fee can be assigned (only for new students)
                $student->can_assign_reg_fee = ($student->student_status === 'new' && !$student->reg_fee_assigned);
                
                // Determine overall fee assignment status
                $student->fee_assigned = $student->course_fee_assigned || $student->reg_fee_assigned;
                
                // Determine if student can be selected for fee assignment
                // New students can have both, existing students only course fee
                $student->can_be_selected = !$student->course_fee_assigned || 
                    ($student->student_status === 'new' && !$student->reg_fee_assigned);
            }

            return response()->json([
                'success' => true,
                'students' => $students,
                'total_count' => $students->count(),
                'assigned_count' => count(array_unique(array_merge($studentsWithCourseFee, $studentsWithRegFee))),
                'new_students_count' => $students->where('student_status', 'new')->count(),
                'existing_students_count' => $students->where('student_status', '!=', 'new')->count()
            ]);

        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Error fetching students: ' . $e->getMessage()
        //     ], 500);
        // }
    }

    /**
     * Process bulk fee assignment for selected students
     */
    public function assignBulkFee(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $request->validate([
                'product_id' => 'required|string',
                'batch_id' => 'required|string',
                'section_id' => 'nullable|string',
                'student_ids' => 'required|array',
                'student_ids.*' => 'string'
            ]);

            $context = $this->getInstituteBranchContext();

            // Get the course fee structure for this batch
            $courseFeeStructure = CourseFeeStructure::where('product_id', $request->product_id)
                ->where('batch_id', $request->batch_id)
                ->first();

            if (!$courseFeeStructure) {
                throw new \Exception('Course fee structure not found for this batch.');
            }

            $successCount = 0;
            $failedCount = 0;
            $alreadyAssignedCount = 0;
            $skippedDueToStatusCount = 0;

            // Process each student
            foreach ($request->student_ids as $studentHashId) {
                try {
                    // Get student details
                    $studentDetail = StudentParentDetails::where('student_hash_id', $studentHashId)
                        ->select('student_hash_id', 'student_status', 'first_name', 'last_name')
                        ->first();
                    
                    if (!$studentDetail) {
                        $failedCount++;
                        continue;
                    }

                    $studentAssigned = false;

                    // Assign Course Fee if not already assigned
                    if (!empty($courseFeeStructure->course_fee) && 
                        $courseFeeStructure->course_fee !== 'null' && 
                        $courseFeeStructure->course_fee !== '[]') {
                        
                        $alreadyHasCourseFee = DB::table('student_course_fee_structures')
                            ->where('student_hash_id', $studentHashId)
                            ->where('batch_id', $request->batch_id)
                            ->exists();

                        if (!$alreadyHasCourseFee) {
                            $this->assignCourseFee(
                                $courseFeeStructure,
                                $studentDetail,
                                $context
                            );
                            $studentAssigned = true;
                        }
                    }

                    // Assign Registration Fee ONLY for new students
                    if (!empty($courseFeeStructure->registration_fee) && 
                        $courseFeeStructure->registration_fee !== 'null' && 
                        $courseFeeStructure->registration_fee !== '[]') {
                        
                        // Check if student is new
                        if ($studentDetail->student_status === 'new') {
                            $alreadyHasRegFee = DB::table('student_registration_fees')
                                ->where('student_hash_id', $studentHashId)
                                ->where('batch_id', $request->batch_id)
                                ->exists();

                            if (!$alreadyHasRegFee) {
                                $this->assignRegistrationFee(
                                    $courseFeeStructure,
                                    $studentDetail,
                                    $context
                                );
                                $studentAssigned = true;
                            } else {
                                // Registration fee already assigned
                                if (!$studentAssigned) {
                                    $alreadyAssignedCount++;
                                }
                            }
                        } else {
                            // Skip registration fee for non-new students
                            \Log::info('Skipped registration fee assignment for non-new student', [
                                'student_hash_id' => $studentHashId,
                                'student_status' => $studentDetail->student_status
                            ]);
                            $skippedDueToStatusCount++;
                            
                            // If only registration fee would have been assigned, count as skipped
                            if (empty($courseFeeStructure->course_fee) || 
                                $courseFeeStructure->course_fee === 'null' || 
                                $courseFeeStructure->course_fee === '[]') {
                                $skippedDueToStatusCount++;
                            }
                        }
                    }

                    if ($studentAssigned) {
                        $successCount++;
                    } elseif (!$studentAssigned && $studentDetail->student_status !== 'new' && 
                              (empty($courseFeeStructure->course_fee) || 
                               $courseFeeStructure->course_fee === 'null' || 
                               $courseFeeStructure->course_fee === '[]')) {
                        // This student only had registration fee option but is not new
                        $skippedDueToStatusCount++;
                    } elseif (!$studentAssigned) {
                        $alreadyAssignedCount++;
                    }

                } catch (\Exception $e) {
                    \Log::error('Failed to assign fee to student', [
                        'student_hash_id' => $studentHashId,
                        'error' => $e->getMessage()
                    ]);
                    $failedCount++;
                }
            }

            DB::commit();

            $message = "Fee assignment completed. Success: $successCount";
            if ($alreadyAssignedCount > 0) {
                $message .= ", Already assigned: $alreadyAssignedCount";
            }
            if ($skippedDueToStatusCount > 0) {
                $message .= ", Skipped (non-new students): $skippedDueToStatusCount";
            }
            if ($failedCount > 0) {
                $message .= ", Failed: $failedCount";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'success_count' => $successCount,
                'already_assigned_count' => $alreadyAssignedCount,
                'skipped_due_to_status_count' => $skippedDueToStatusCount,
                'failed_count' => $failedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error assigning bulk fee: ' . $e->getMessage()
            ], 500);
        }
    }

    private function assignCourseFee($courseFeeStructure, $studentDetail, $context)
    {
        $feeData = json_decode($courseFeeStructure->course_fee, true);

        if (is_string($feeData)) {
            $feeData = json_decode($feeData, true);
        }

        if (!is_array($feeData) || empty($feeData['payments'])) {
            return false;
        }

        $duration       = $feeData['duration'];
        $payments       = $feeData['payments'];
        $lateFee        = $feeData['late_fee'] ?? [];
        $partialPayment = $feeData['partial_payment'] ?? [];

        // Get academic years for this batch
        $academicYears = CourseFeeStructure::where('batch_id', $courseFeeStructure->batch_id)
            ->pluck('academic_year_id')
            ->unique()
            ->values()
            ->toArray();

        $totalAcademicYear = count($academicYears);

        $totalPayments   = count($payments);
        $paymentsPerYear = ceil($totalPayments / $totalAcademicYear);

        foreach ($payments as $index => $payment) {

            $yearIndex = floor($index / $paymentsPerYear);
            $yearIndex = min($yearIndex, $totalAcademicYear - 1);

            $academicYear = $academicYears[$yearIndex];

            DB::table('student_course_fee_structures')->insert([
                'institute_id'        => $context['institute_id'],
                'branch_id'           => $context['is_branch_admin'] ? $context['branch_id'] : null,
                'student_hash_id'     => $studentDetail->student_hash_id,

                'fee_duration_type'   => $duration,
                'product_id'          => $courseFeeStructure->product_id,
                'batch_id'            => $courseFeeStructure->batch_id,
                'academic_year_id'    => $academicYear,

                'course_fee'          => $payment['amount'],

                'start_date'          => $payment['start_date'] ?? null,
                'due_date'            => $payment['end_date'] ?? null,

                'late_fee_type'       => $lateFee['type'] ?? null,
                'late_fee_value'      => $lateFee['amount'] ?? 0,

                'partially_fee_type'  => $partialPayment['type'] ?? null,
                'partially_fee_value' => $partialPayment['value'] ?? 0,

                'payment_status'      => 'pending',
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
        }

        return true;
    }

    private function assignRegistrationFee($courseFeeStructure, $studentDetail, $context)
    {
        $feeData = json_decode($courseFeeStructure->registration_fee, true);

        if (is_string($feeData)) {
            $feeData = json_decode($feeData, true);
        }

        if (!is_array($feeData) || empty($feeData['payments'])) {
            return false;
        }

        $duration = $feeData['duration'];
        $payments = $feeData['payments'];
        $lateFee  = $feeData['late_fee'] ?? [];

        $academicYears = CourseFeeStructure::where('batch_id', $courseFeeStructure->batch_id)
            ->pluck('academic_year_id')
            ->unique()
            ->values()
            ->toArray();

        $totalAcademicYear = count($academicYears);

        $totalPayments   = count($payments);
        $paymentsPerYear = ceil($totalPayments / $totalAcademicYear);

        foreach ($payments as $index => $payment) {

            $yearIndex = floor($index / $paymentsPerYear);
            $yearIndex = min($yearIndex, $totalAcademicYear - 1);

            $academicYear = $academicYears[$yearIndex];

            DB::table('student_registration_fees')->insert([
                'institute_id'        => $context['institute_id'],
                'branch_id'           => $context['is_branch_admin'] ? $context['branch_id'] : null,
                'product_id'          => $courseFeeStructure->product_id,
                'student_hash_id'     => $studentDetail->student_hash_id,
                'batch_id'            => $courseFeeStructure->batch_id,
                'academic_year_id'    => $academicYear,

                'fee_duration_type'   => $duration,

                'registration_fee'    => $payment['amount'],

                'start_date'          => $payment['start_date'] ?? null,
                'due_date'            => $payment['end_date'] ?? null,

                'late_fee_type'       => $lateFee['type'] ?? null,
                'late_fee_value'      => $lateFee['amount'] ?? 0,

                'payment_status'      => 'pending',
                'start_date'          => now(),

                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
        }

        return true;
    }

    /**
     * Get fee structure details for preview
     */
    public function getFeeStructurePreview(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|string',
                'batch_id' => 'required|string'
            ]);

            $courseFeeStructure = CourseFeeStructure::where('product_id', $request->product_id)
                ->where('batch_id', $request->batch_id)
                ->first();

            if (!$courseFeeStructure) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fee structure not found'
                ]);
            }

            $preview = [];

            // Parse course fee
            if (!empty($courseFeeStructure->course_fee) && 
                $courseFeeStructure->course_fee !== 'null' && 
                $courseFeeStructure->course_fee !== '[]') {
                
                $feeData = json_decode($courseFeeStructure->course_fee, true);
                
                if (is_string($feeData)) {
                    $feeData = json_decode($feeData, true);
                }

                if (is_array($feeData) && !empty($feeData['payments'])) {
                    $totalAmount = 0;
                    foreach ($feeData['payments'] as $payment) {
                        $totalAmount += is_array($payment) ? ($payment['amount'] ?? 0) : $payment;
                    }

                    $preview['course_fee'] = [
                        'total_payments' => count($feeData['payments']),
                        'total_amount' => $totalAmount,
                        'duration' => $feeData['duration'] ?? 'N/A',
                        'has_late_fee' => !empty($feeData['late_fee']),
                        'has_partial_payment' => !empty($feeData['partial_payment'])
                    ];
                }
            }

            // Parse registration fee
            if (!empty($courseFeeStructure->registration_fee) && 
                $courseFeeStructure->registration_fee !== 'null' && 
                $courseFeeStructure->registration_fee !== '[]') {
                
                $feeData = json_decode($courseFeeStructure->registration_fee, true);
                
                if (is_string($feeData)) {
                    $feeData = json_decode($feeData, true);
                }

                if (is_array($feeData) && !empty($feeData['payments'])) {
                    $totalAmount = 0;
                    foreach ($feeData['payments'] as $payment) {
                        $totalAmount += is_array($payment) ? ($payment['amount'] ?? 0) : $payment;
                    }

                    $preview['registration_fee'] = [
                        'total_payments' => count($feeData['payments']),
                        'total_amount' => $totalAmount,
                        'duration' => $feeData['duration'] ?? 'N/A',
                        'has_late_fee' => !empty($feeData['late_fee']),
                        'has_partial_payment' => !empty($feeData['partial_payment'])
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'fee_preview' => $preview,
                'batch_name' => $courseFeeStructure->batch,
                'academic_year' => $courseFeeStructure->academic_year
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching fee preview: ' . $e->getMessage()
            ], 500);
        }
    }
}