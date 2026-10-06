<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\FincapMerchantSubCategories;
use App\Models\FincapMerchant;
use Illuminate\Support\Facades\Auth;
use App\Models\Allsubcategories;
use App\Models\ProductDetails;
use App\Models\StudentCourseFeeStructure;
use App\Models\StudentCustomFeestructure;
use App\Models\StudentHostelFeeStructure;
use App\Models\StudentMiscellaneousFeeStructure;
use App\Models\StudentRegistrationFeeStructure;
use App\Models\StudentTransportFeeStructure;
use App\Models\SetPaymentGatewayCharges;
use App\Models\StudentParentDetails;
use App\Models\CourseFeeStructure;
use App\Models\StudentAcademicDetailsLog;
use App\Models\StudentacademicTransportDetails;
use App\Models\TransportationFee;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
class StudentFeeController extends Controller
{
    
    public function studentCourseFeeStructure(Request $request, $auto = false)
    {
        try {
            // ------------------------------------
            // 1. Get logged-in student
            // ------------------------------------
            $student = Auth::user();

            $studentDetail = StudentParentDetails::with('academicTransportDetails')
                ->where([
                    'institute_id' => $student->institute_id,
                    'user_id'      => $student->id,
                ])
                ->first();
            if (!$studentDetail || !$studentDetail->academicTransportDetails) {
                return back()->with('error', 'Transport / student details not found.');
            }

            // IMPORTANT: Get the product_id from academic details
            $productId = $studentDetail->academicTransportDetails->course_subtype_id ?? null;

            // ------------------------------------
            // 2. Get Course Fee Structure
            // ------------------------------------
            $getcoursefee = CourseFeeStructure::where(
                'batch_id',
                $studentDetail->academicTransportDetails['batch_id']
            )->get();
            if ($getcoursefee->isEmpty()) {
                return back()->with('error', 'Course fee structure not found.');
            }

            // ------------------------------------
            // 3. Extract All Academic Years
            // ------------------------------------
            $academicYears = $getcoursefee->pluck('academic_year_id')
                            ->unique()
                            ->values()
                            ->toArray();

            $totalAcademicYear = count($academicYears);

            // ------------------------------------
            // 4. Decode JSON Fee Structure
            // ------------------------------------
            $courseFee = json_decode($getcoursefee[0]->course_fee, true);

            $duration       = $courseFee['duration'];
            $payments       = $courseFee['payments'];
            $lateFee        = $courseFee['late_fee'];
            $partialPayment = $courseFee['partial_payment'];

            $totalPayments     = count($payments);
            $paymentsPerYear   = ceil($totalPayments / $totalAcademicYear);

            // ------------------------------------
            // 5. Clear existing records for this student AND this product
            // ------------------------------------
            StudentCourseFeeStructure::where('student_hash_id', $studentDetail->student_hash_id)
                ->where('product_id', $productId)  // Only clear current product's records
                ->delete();

            // ------------------------------------
            // 6. Insert Student Fee Structure with product_id
            // ------------------------------------
            foreach ($payments as $index => $payment) {

                // Determine academic year dynamically
                $yearIndex    = floor($index / $paymentsPerYear);
                $yearIndex    = min($yearIndex, $totalAcademicYear - 1);
                $academicYear = $academicYears[$yearIndex];

                StudentCourseFeeStructure::create([
                    'institute_id'        => $student->institute_id,
                    'branch_id'           => $student->branch_id,
                    'student_hash_id'     => $studentDetail->student_hash_id,
                    'product_id'          => $productId,  // CRITICAL: Store product_id
                    'fee_duration_type'   => $duration,
                    'batch_id'            => $getcoursefee[0]->batch_id,
                    'academic_year_id'    => $academicYear,
                    'course_fee'          => $payment['amount'],
                    'due_date'            => $payment['end_date'],
                    'late_fee_type'       => $lateFee['type']  ?? null,
                    'late_fee_value'      => $lateFee['amount'] ?? 0,
                    'partially_fee_type'  => $partialPayment['type']  ?? null,
                    'partially_fee_value' => $partialPayment['value'] ?? 0,
                ]);
            }

            return back()->with('success', 'Course fee structure saved successfully.');

        } catch (\Exception $e) {
            return back()->with(
                'error',
                'Error fetching your fee structure: ' . $e->getMessage()
            );
        }
    }

    public function addRegistrationFeeStructure(Request $request, $auto = false)
    {
        try {
            $student = Auth::user();

            $studentDetail = StudentParentDetails::with('academicTransportDetails')
                ->where([
                    'institute_id' => $student->institute_id,
                    'user_id'      => $student->id,
                ])
                ->first();

            if (!$studentDetail || !$studentDetail->academicTransportDetails) {
                return back()->with('error', 'Transport / student details not found.');
            }

            // Get product_id
            $productId = $studentDetail->academicTransportDetails->course_subtype_id ?? null;

            $getcoursefee = CourseFeeStructure::where(
                'batch_id',
                $studentDetail->academicTransportDetails['batch_id']
            )->get();
            
            if ($getcoursefee->isEmpty()) {
                return back()->with('error', 'Course fee structure not found.');
            }

            $academicYears = $getcoursefee->pluck('academic_year_id')
                            ->unique()
                            ->values()
                            ->toArray();

            $totalAcademicYear = count($academicYears);

            $registrationFee = json_decode($getcoursefee[0]->registration_fee, true);

            $duration = $registrationFee['duration'];
            $payments = $registrationFee['payments'];
            $lateFee = $registrationFee['late_fee'];

            $totalPayments = count($payments);
            $paymentsPerYear = ceil($totalPayments / $totalAcademicYear);

            // Clear existing records for this product only
            StudentRegistrationFeeStructure::where('student_hash_id', $studentDetail->student_hash_id)
                ->where('product_id', $productId)
                ->delete();

            foreach ($payments as $index => $payment) {
                $yearIndex = floor($index / $paymentsPerYear);
                $yearIndex = min($yearIndex, $totalAcademicYear - 1);
                $academicYear = $academicYears[$yearIndex];

                StudentRegistrationFeeStructure::create([
                    'institute_id' => $student->institute_id,
                    'branch_id' => $student->branch_id,
                    'student_hash_id' => $studentDetail->student_hash_id,
                    'product_id' => $productId,  // ADD THIS LINE
                    'fee_duration_type' => $duration,
                    'batch_id' => $getcoursefee[0]->batch_id,
                    'academic_year_id' => $academicYear,
                    'registration_fee' => $payment['amount'],
                    'due_date' => $payment['end_date'],
                    'late_fee_type' => $lateFee['type'] ?? null,
                    'late_fee_value' => $lateFee['amount'] ?? 0,
                ]);
            }

            return back()->with('success', 'Registration fee structure saved successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching your fee structure: ' . $e->getMessage());
        } 
    }

    public function studentFeeStructure(Request $request)
    {
        // try {
            $student = Auth::user();
            $studentDetail = StudentParentDetails::with(['StudentCourseFeeStructure','StudentCustomFeestructure','StudentHostelFeeStructure','StudentMiscellaneousFeeStructure','StudentRegistrationFeeStructure','StudentTransportFeeStructure','academicTransportDetails'])
                ->where([
                    'institute_id' => $student->institute_id,
                    'user_id'      => $student->id,
                ])
                ->first();

            if (!$studentDetail) {
                return redirect()->back()->with('error', 'Student academic details not found.');
            }
            
            // =========================================================
            // Get all available academic years for this student
            // =========================================================
            $transportYears = StudentAcademicTransportDetails::where('student_hash_id', $studentDetail->student_hash_id)
                ->distinct()
                ->pluck('academic_year');

            $logYears = StudentAcademicDetailsLog::where('student_hash_id', $studentDetail->student_hash_id)
                ->distinct()
                ->pluck('previous_academic_year')
                ->merge(
                    StudentAcademicDetailsLog::where('student_hash_id', $studentDetail->student_hash_id)
                        ->distinct()
                        ->pluck('new_academic_year')
                )
                ->unique()
                ->filter()
                ->values();

            $academicYears = $transportYears->merge($logYears)->unique()->sortDesc()->values();
            $filterYear = request('academic_year');
            
            // Get the current academic details
            $currentAcademic = $studentDetail->academicTransportDetails;
            $currentProductId = $currentAcademic->course_subtype_id ?? null;
            $currentBatchId = $currentAcademic->batch_id ?? null;
            $currentAcademicYear = $currentAcademic->academic_year ?? null;
            $currentAcademicYearId = $currentAcademic->academic_year_id ?? null;
            
            // =========================================================
            // Determine which academic record to use (current or filtered)
            // =========================================================
            $academicRecordToUse = $currentAcademic;
            $productIdToUse = $currentProductId;
            $batchIdToUse = $currentBatchId;
            $academicYearToUse = $currentAcademicYear;
            $academicYearIdToUse = $currentAcademicYearId;
            
            if ($filterYear && $filterYear !== $currentAcademicYear) {
                // Query StudentAcademicDetailsLog for the filtered year
                $logRecord = StudentAcademicDetailsLog::where('student_hash_id', $studentDetail->student_hash_id)
                    ->where('institute_id', $student->institute_id)
                    ->where(function($q) use ($filterYear) {
                        $q->where('previous_academic_year', $filterYear)
                          ->orWhere('new_academic_year', $filterYear);
                    })
                    ->first();
                
                if ($logRecord) {
                    // Determine which fields to use (previous_ or new_) — prefer *_id fields
                    if ($logRecord->previous_academic_year === $filterYear) {
                        $productIdToUse = $logRecord->previous_course_subtype_id ?? $logRecord->previous_course_subtype ?? $currentProductId;
                        $batchIdToUse = $logRecord->previous_batch_id ?? $logRecord->previous_batch ?? $currentBatchId;
                        $academicYearToUse = $logRecord->previous_academic_year ?? $academicYearToUse;
                        $academicYearIdToUse = $logRecord->previous_academic_year_id ?? $academicYearIdToUse;
                    } else {
                        $productIdToUse = $logRecord->new_course_subtype_id ?? $logRecord->new_course_subtype ?? $currentProductId;
                        $batchIdToUse = $logRecord->new_batch_id ?? $logRecord->new_batch ?? $currentBatchId;
                        $academicYearToUse = $logRecord->new_academic_year ?? $academicYearToUse;
                        $academicYearIdToUse = $logRecord->new_academic_year_id ?? $academicYearIdToUse;
                    }
                }
            }
            
            // =========================================================
            // Build an academic record object for the selected year
            // This will be used by the view to show historical/current details
            // =========================================================
            $academicRecord = new \stdClass();
            // Default to current academic details
            $academicRecord->department = $currentAcademic->department ?? null;
            $academicRecord->course_type = $currentAcademic->course_type ?? null;
            $academicRecord->course_subtype = $currentAcademic->course_subtype ?? null;
            $academicRecord->batch = $currentAcademic->batch ?? null;
            $academicRecord->academic_year = $currentAcademic->academic_year ?? null;
            $academicRecord->academic_year_id = $currentAcademic->academic_year_id ?? null;
            $academicRecord->mode_of_course = $currentAcademic->mode_of_course ?? null;
            $academicRecord->mode_type = $currentAcademic->mode_type ?? null;
            $academicRecord->semester = $currentAcademic->semester ?? null;
            $academicRecord->section_id = $currentAcademic->section_id ?? null;
            $academicRecord->section_name = $currentAcademic->section_name ?? null;

            // If a historical year is selected and we have a log record, override fields
            if (!empty($filterYear) && isset($logRecord) && $logRecord) {
                if ($logRecord->previous_academic_year == $filterYear) {
                    $academicRecord->department = $logRecord->previous_department ?? $academicRecord->department;
                    $academicRecord->course_type = $logRecord->previous_course_type ?? $academicRecord->course_type;
                    $academicRecord->course_subtype = $logRecord->previous_course_subtype ?? $academicRecord->course_subtype;
                    $academicRecord->batch = $logRecord->previous_batch ?? $academicRecord->batch;
                    $academicRecord->academic_year = $logRecord->previous_academic_year ?? $academicRecord->academic_year;
                    $academicRecord->academic_year_id = $logRecord->previous_academic_year_id ?? $academicRecord->academic_year_id;
                    $academicRecord->mode_of_course = $logRecord->previous_mode_of_course ?? $academicRecord->mode_of_course;
                    $academicRecord->mode_type = $logRecord->previous_mode_type ?? $academicRecord->mode_type;
                    $academicRecord->semester = $logRecord->previous_semester_id ?? $academicRecord->semester;
                    $academicRecord->section_id = $logRecord->previous_section_id ?? $academicRecord->section_id;
                } else {
                    $academicRecord->department = $logRecord->new_department ?? $academicRecord->department;
                    $academicRecord->course_type = $logRecord->new_course_type ?? $academicRecord->course_type;
                    $academicRecord->course_subtype = $logRecord->new_course_subtype ?? $academicRecord->course_subtype;
                    $academicRecord->batch = $logRecord->new_batch ?? $academicRecord->batch;
                    $academicRecord->academic_year = $logRecord->new_academic_year ?? $academicRecord->academic_year;
                    $academicRecord->academic_year_id = $logRecord->new_academic_year_id ?? $academicRecord->academic_year_id;
                    $academicRecord->mode_of_course = $logRecord->new_mode_of_course ?? $academicRecord->mode_of_course;
                    $academicRecord->mode_type = $logRecord->new_mode_type ?? $academicRecord->mode_type;
                    $academicRecord->semester = $logRecord->new_semester_id ?? $academicRecord->semester;
                    $academicRecord->section_id = $logRecord->new_section_id ?? $academicRecord->section_id;
                }
            }

            // Resolve section_name from course_fee_structures for the product in use
            if (!empty($productIdToUse) && !empty($academicRecord->section_id)) {
                $courseFeeStructure = DB::table('course_fee_structures as cfs')
                    ->where('cfs.product_id', $productIdToUse)
                    ->where('cfs.institute_id', $student->institute_id)
                    ->select('sections')
                    ->first();

                if ($courseFeeStructure && !empty($courseFeeStructure->sections)) {
                    $sections = json_decode($courseFeeStructure->sections, true);
                    if (is_array($sections)) {
                        foreach ($sections as $section) {
                            if (isset($section['id']) && $section['id'] == $academicRecord->section_id) {
                                $academicRecord->section_name = $section['name'] ?? $academicRecord->section_id;
                                break;
                            }
                        }
                    }
                }
                if (!isset($academicRecord->section_name)) {
                    $academicRecord->section_name = $academicRecord->section_id;
                }
            }
            
            // ========== Get installments for the selected year ==========
            $currentInstallments = StudentCourseFeeStructure::where(
                'student_hash_id',
                $studentDetail->student_hash_id
                )
                ->where('institute_id', $student->institute_id)
                ->where('product_id', $productIdToUse)
                ->where('batch_id', $batchIdToUse)
                ->where('academic_year_id', $academicYearIdToUse)
                ->orderBy('due_date')
                ->get();
            
            // Also get registration fee installments for the selected year
            $currentRegistrationInstallments = StudentRegistrationFeeStructure::where(
                    'student_hash_id',
                    $studentDetail->student_hash_id
                )
                ->where('institute_id', $student->institute_id)
                ->where('product_id', $productIdToUse)
                ->where('batch_id', $batchIdToUse)
                ->where('academic_year_id', $academicYearIdToUse)
                ->orderBy('due_date')
                ->get();
            
            // Log for debugging
            \Log::info('Student Fee Structure Request', [
                'student_hash_id' => $studentDetail->student_hash_id,
                'filter_year' => $filterYear,
                'product_id_to_use' => $productIdToUse,
                'batch_id_to_use' => $batchIdToUse,
                'academic_year_to_use' => $academicYearToUse,
                'academic_year_id_to_use' => $academicYearIdToUse,
                'installments_count' => $currentInstallments->count(),
                'registration_installments_count' => $currentRegistrationInstallments->count()
            ]);
            
            // Rest of your code remains the same...
            // Build query for course fee structure template
            $query = DB::table('course_fee_structures as cfs')
                ->join('product_details as pd', 'cfs.product_id', '=', 'pd.product_id')
                ->leftJoin('departments as d', 'cfs.department_id', '=', 'd.department_id')
                ->select(
                    'cfs.*',
                    'pd.sub_type',
                    'pd.course_type',
                    'pd.mode_of_course',
                    'pd.mode_type',
                    'pd.course_duration',
                    'pd.course_length',
                    'cfs.course_start_date as branch_start_date',
                    'cfs.course_end_date as branch_end_date',
                    'd.department',
                    'd.department_id'
                );
        
            // STRICT FILTERING - Only selected product and batch
            if ($productIdToUse) {
                $query->where('cfs.product_id', $productIdToUse);
            } else {
                \Log::warning('No product_id found for student', ['student_hash_id' => $studentDetail->student_hash_id]);
                return redirect()->back()->with('error', 'No course assigned to this student.');
            }
            
            if ($batchIdToUse) {
                $query->where('cfs.batch_id', $batchIdToUse);
            }
            
            // Try to get exact academic year match first (use ID)
            if (!empty($academicYearIdToUse)) {
                $query->where('cfs.academic_year_id', $academicYearIdToUse);
            }

            // Only show active fee structures
            $query->where('cfs.is_active', true);

            $studentFeeStructure = $query->first();

            // If no fee structure found with selected academic year, get the latest one
            if (!$studentFeeStructure && $productIdToUse) {
                \Log::info('No fee structure for selected academic year, fetching latest', [
                    'product_id' => $productIdToUse,
                    'batch_id' => $batchIdToUse,
                    'academic_year' => $academicYearToUse
                ]);
                
                $queryWithoutAcademicYear = DB::table('course_fee_structures as cfs')
                    ->join('product_details as pd', 'cfs.product_id', '=', 'pd.product_id')
                    ->leftJoin('departments as d', 'cfs.department_id', '=', 'd.department_id')
                    ->select(
                        'cfs.*',
                        'pd.sub_type',
                        'pd.course_type',
                        'pd.mode_of_course',
                        'pd.mode_type',
                        'pd.course_duration',
                        'pd.course_length',
                        'cfs.course_start_date as branch_start_date',
                        'cfs.course_end_date as branch_end_date',
                        'd.department',
                        'd.department_id'
                    )
                    ->where('cfs.product_id', $productIdToUse)
                    ->where('cfs.batch_id', $batchIdToUse)
                    ->where('cfs.is_active', true)
                    ->orderBy('cfs.academic_year_id', 'desc')
                    ->first();
                
                $studentFeeStructure = $queryWithoutAcademicYear;
            }

            // =====================
            // Filter other fee categories by selected academic year
            // Assign filtered collections back onto $studentDetail so view uses them
            // =====================
            if (!empty($academicYearToUse)) {
                $filteredHostel = StudentHostelFeeStructure::where('student_hash_id', $studentDetail->student_hash_id)
                    ->where('institute_id', $student->institute_id)
                    ->whereHas('academic', function($q) use ($academicYearToUse) {
                        $q->where('academic_year', $academicYearToUse);
                    })->get();

               $filteredTransport = StudentTransportFeeStructure::where(
                    'student_hash_id',
                    $studentDetail->student_hash_id
                )
                ->where('institute_id', $student->institute_id)
                ->where('academic_year_id', $academicYearIdToUse)
                ->where('batch_id', $batchIdToUse)
                ->get();

                $filteredMisc = StudentMiscellaneousFeeStructure::where('student_hash_id', $studentDetail->student_hash_id)
                    ->where('institute_id', $student->institute_id)
                    ->whereHas('academic', function($q) use ($academicYearToUse) {
                        $q->where('academic_year', $academicYearToUse);
                    })->get();

                $filteredCustom = StudentCustomFeestructure::where('student_hash_id', $studentDetail->student_hash_id)
                    ->where('institute_id', $student->institute_id)
                    ->where('academic_year_id', $academicYearIdToUse)
                    ->get();

                // Attach to studentDetail for view compatibility
                $studentDetail->StudentHostelFeeStructure = $filteredHostel;
                $studentDetail->StudentTransportFeeStructure = $filteredTransport;
                $studentDetail->StudentMiscellaneousFeeStructure = $filteredMisc;
                $studentDetail->StudentCustomFeestructure = $filteredCustom;
            }

            // Process fee data
            if ($studentFeeStructure) {
                // Course Fee - Specific to product
                if ($studentFeeStructure->course_fee && $studentFeeStructure->course_fee !== 'null' && $studentFeeStructure->course_fee !== '') {
                    try {
                        $decoded = json_decode($studentFeeStructure->course_fee, true);
                        if (is_string($decoded)) {
                            $decoded = json_decode($decoded, true);
                        }
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $studentFeeStructure->course_fee = $decoded;
                        } else {
                            $studentFeeStructure->course_fee = null;
                        }
                    } catch (\Exception $e) {
                        $studentFeeStructure->course_fee = null;
                    }
                } else {
                    $studentFeeStructure->course_fee = null;
                }
                
                // Registration Fee - Specific to product
                if ($studentFeeStructure->registration_fee && $studentFeeStructure->registration_fee !== 'null' && $studentFeeStructure->registration_fee !== '') {
                    try {
                        $decoded = json_decode($studentFeeStructure->registration_fee, true);
                        if (is_string($decoded)) {
                            $decoded = json_decode($decoded, true);
                        }
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $studentFeeStructure->registration_fee = $decoded;
                        } else {
                            $studentFeeStructure->registration_fee = null;
                        }
                    } catch (\Exception $e) {
                        $studentFeeStructure->registration_fee = null;
                    }
                } else {
                    $studentFeeStructure->registration_fee = null;
                }
                
                // Hostel Fee - Global
                if ($studentFeeStructure->hostel_fee && $studentFeeStructure->hostel_fee !== 'null' && $studentFeeStructure->hostel_fee !== '') {
                    try {
                        $decoded = json_decode($studentFeeStructure->hostel_fee, true);
                        if (is_string($decoded)) {
                            $decoded = json_decode($decoded, true);
                        }
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $studentFeeStructure->hostel_fee = $decoded;
                        } else {
                            $studentFeeStructure->hostel_fee = null;
                        }
                    } catch (\Exception $e) {
                        $studentFeeStructure->hostel_fee = null;
                    }
                } else {
                    $studentFeeStructure->hostel_fee = null;
                }
                
                // Transportation Fee - Global
                if ($studentFeeStructure->transportation_fee && $studentFeeStructure->transportation_fee !== 'null' && $studentFeeStructure->transportation_fee !== '') {
                    try {
                        $decoded = json_decode($studentFeeStructure->transportation_fee, true);
                        if (is_string($decoded)) {
                            $decoded = json_decode($decoded, true);
                        }
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $studentFeeStructure->transportation_fee = $decoded;
                        } else {
                            $studentFeeStructure->transportation_fee = null;
                        }
                    } catch (\Exception $e) {
                        $studentFeeStructure->transportation_fee = null;
                    }
                } else {
                    $studentFeeStructure->transportation_fee = null;
                }
                
                // Miscellaneous Fee - Global
                if ($studentFeeStructure->miscellaneous_fee && $studentFeeStructure->miscellaneous_fee !== 'null' && $studentFeeStructure->miscellaneous_fee !== '') {
                    try {
                        $decoded = json_decode($studentFeeStructure->miscellaneous_fee, true);
                        if (is_string($decoded)) {
                            $decoded = json_decode($decoded, true);
                        }
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $studentFeeStructure->miscellaneous_fee = $decoded;
                        } else {
                            $studentFeeStructure->miscellaneous_fee = null;
                        }
                    } catch (\Exception $e) {
                        $studentFeeStructure->miscellaneous_fee = null;
                    }
                } else {
                    $studentFeeStructure->miscellaneous_fee = null;
                }
            }
            
            // Calculate totals from current installments only
            $totalCourseFee = $currentInstallments->sum('course_fee');
            $totalPaidCourseFee = $currentInstallments->where('payment_status', 'paid')->sum('paid_amount');
            $totalPendingCourseFee = $totalCourseFee - $totalPaidCourseFee;
            $totalInstallments = $currentInstallments->count();
            $paidInstallments = $currentInstallments->where('payment_status', 'paid')->count();
            
            $charges = SetPaymentGatewayCharges::where('institute_id',$student->institute_id)
                ->where('status', 'active')
                ->first();
            
            return view('instituteAdmin.StudentFiles.StudentFeeStructure', compact(
                'studentFeeStructure', 
                'student',
                'studentDetail',
                'charges',
                'currentProductId',
                'currentBatchId',
                'currentAcademicYear',
                'currentInstallments',
                'currentRegistrationInstallments',
                'totalCourseFee',
                'totalPaidCourseFee',
                'totalPendingCourseFee',
                'totalInstallments',
                'paidInstallments',
                'academicYears',
                'filterYear',
                'academicRecord'
            ));

        // } catch (\Exception $e) {
        //     \Log::error('Error in studentFeeStructure', [
        //         'error' => $e->getMessage(),
        //         'trace' => $e->getTraceAsString()
        //     ]);
        //     return redirect()->back()->with('error', 'Error fetching your fee structure: ' . $e->getMessage());
        // }
    }

    public function fetchReceipt(Request $request)
    {
        try {
            $installmentId = $request->installment_id;
            $category = $request->category;
            
            // Determine which table to query based on category
            $receiptData = null;
            
            switch($category) {
                case 'course_fee':
                    $receiptData = StudentCourseFeeStructure::find($installmentId);
                    break;
                case 'hostel_fee':
                    $receiptData = StudentHostelFeeStructure::find($installmentId);
                    break;
                case 'transportation_fee':
                    $receiptData = StudentTransportFeeStructure::find($installmentId);
                    break;
                case 'registration_fee':
                    $receiptData = StudentRegistrationFeeStructure::find($installmentId);
                    break;
                case 'miscellaneous_fee':
                    $receiptData = StudentMiscellaneousFeeStructure::find($installmentId);
                    break;
                default:
                    return response()->json(['status' => false, 'message' => 'Invalid category']);
            }
            
            if (!$receiptData || $receiptData->payment_status !== 'paid') {
                return response()->json(['status' => false, 'message' => 'Receipt not found or payment not completed']);
            }
            
            // Prepare receipt data
            $receipt = [
                'fee_type' => ucfirst(str_replace('_', ' ', $category)),
                'fee_amount' => $receiptData->{$this->getFeeField($category)},
                'late_fee_amount' => $receiptData->late_fee_amount ?? 0,
                'discount_amount' => $receiptData->discount_amount ?? 0,
                'payable_amount' => ($receiptData->{$this->getFeeField($category)} + ($receiptData->late_fee_amount ?? 0) - ($receiptData->discount_amount ?? 0)),
                'payment_status' => $receiptData->payment_status,
                'payment_type' => $receiptData->payment_type,
                'pay_date' => $receiptData->pay_date,
                'due_date' => $receiptData->due_date,
                'transaction_id' => $receiptData->transaction_id,
                'reference_id' => $receiptData->fee_reference_id,
            ];
            
            return response()->json(['status' => true, 'data' => $receipt]);
            
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function fetchCustomReceipt(Request $request)
    {
        try {
            $customFeeId = $request->custom_fee_id;
            $receiptData = StudentCustomFeestructure::find($customFeeId);
            
            if (!$receiptData || $receiptData->payment_status !== 'paid') {
                return response()->json(['status' => false, 'message' => 'Receipt not found or payment not completed']);
            }
            
            $receipt = [
                'fee_type' => $receiptData->custom_fee_key ?? 'Custom Fee',
                'fee_amount' => $receiptData->custom_fee_value,
                'late_fee_amount' => $receiptData->late_fee_amount ?? 0,
                'discount_amount' => $receiptData->discount_amount ?? 0,
                'payable_amount' => ($receiptData->custom_fee_value + ($receiptData->late_fee_amount ?? 0) - ($receiptData->discount_amount ?? 0)),
                'payment_status' => $receiptData->payment_status,
                'payment_type' => $receiptData->payment_type,
                'pay_date' => $receiptData->payment_date,
                'due_date' => $receiptData->due_date,
                'transaction_id' => $receiptData->transaction_id,
                'reference_id' => $receiptData->fee_reference_id,
            ];
            
            return response()->json(['status' => true, 'data' => $receipt]);
            
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    private function getFeeField($category)
    {
        $fields = [
            'course_fee' => 'course_fee',
            'hostel_fee' => 'hostel_fee',
            'transportation_fee' => 'transport_fee',
            'registration_fee' => 'registration_fee',
            'miscellaneous_fee' => 'miscellaneous_fee',
        ];
        
        return $fields[$category] ?? 'fee_amount';
    }

    public function show()
    {
        // Get the currently logged-in user (default guard)
        $user = auth()->user();

        // First get student details to know current product and academic year
        $studentData = StudentParentDetails::with('academicTransportDetails')
            ->where('user_id', $user->id)
            ->first();
        
        if (!$studentData) {
            return redirect()->back()->with('error', 'Student details not found.');
        }
        
        $currentProductId = $studentData->academicTransportDetails->course_subtype_id ?? null;
        $currentAcademicYear = $studentData->academicTransportDetails->academic_year ?? null;
        $currentBatchId = $studentData->academicTransportDetails->batch_id ?? null;

        // Fetch the student details with all necessary relationships
        $studentDetail = StudentParentDetails::with([
            'academicTransportDetails',
            
            // Course Fee - Filter by product_id AND academic_year
            'StudentCourseFeeStructure' => function($query) use ($currentProductId, $currentAcademicYear) {
                $query->where('product_id', $currentProductId)
                    ->whereHas('academic', function($q) use ($currentAcademicYear) {
                        $q->where('academic_year', $currentAcademicYear);
                    })
                    ->orderBy('due_date');
            },
            
            // Registration Fee - Filter by product_id AND academic_year
            'StudentRegistrationFeeStructure' => function($query) use ($currentProductId, $currentAcademicYear) {
                $query->where('product_id', $currentProductId)
                    ->whereHas('academic', function($q) use ($currentAcademicYear) {
                        $q->where('academic_year', $currentAcademicYear);
                    });
            },
            
            // Hostel Fee - Filter ONLY by academic_year (no product_id filter)
            'StudentHostelFeeStructure' => function($query) use ($currentAcademicYear) {
                $query->whereHas('academic', function($q) use ($currentAcademicYear) {
                    $q->where('academic_year', $currentAcademicYear);
                });
            },
            
            // Transport Fee - Filter ONLY by academic_year (no product_id filter)
            'StudentTransportFeeStructure' => function($query) use ($currentAcademicYear) {
                $query->whereHas('academic', function($q) use ($currentAcademicYear) {
                    $q->where('academic_year', $currentAcademicYear);
                });
            },
            
            // Miscellaneous Fee - Filter ONLY by academic_year (no product_id filter)
            'StudentMiscellaneousFeeStructure' => function($query) use ($currentAcademicYear) {
                $query->whereHas('academic', function($q) use ($currentAcademicYear) {
                    $q->where('academic_year', $currentAcademicYear);
                });
            },
            
            'StudentCustomFeestructure',
        ])
        ->where('user_id', $user->id)
        ->first();

        if (!$studentDetail) {
            return redirect()->back()->with('error', 'Student details not found.');
        }

        // Calculate totals for current course only
        $totalCourseFees = 0;
        $totalPaidCourseFees = 0;
        $totalPendingCourseFees = 0;
        
        foreach ($studentDetail->StudentCourseFeeStructure as $fee) {
            $feeAmount = $fee->course_fee ?? 0;
            $totalCourseFees += $feeAmount;
            
            if ($fee->payment_status === 'paid') {
                $totalPaidCourseFees += ($fee->paid_amount ?? $feeAmount);
            } else {
                $totalPendingCourseFees += $feeAmount;
            }
        }
        
        // Calculate registration fee totals
        $totalRegistrationFees = 0;
        $totalPaidRegistrationFees = 0;
        $totalPendingRegistrationFees = 0;
        
        foreach ($studentDetail->StudentRegistrationFeeStructure as $fee) {
            $feeAmount = $fee->registration_fee ?? 0;
            $totalRegistrationFees += $feeAmount;
            
            if ($fee->payment_status === 'paid') {
                $totalPaidRegistrationFees += ($fee->paid_amount ?? $feeAmount);
            } else {
                $totalPendingRegistrationFees += $feeAmount;
            }
        }
        
        // Calculate hostel fee totals
        $totalHostelFees = 0;
        $totalPaidHostelFees = 0;
        $totalPendingHostelFees = 0;
        
        foreach ($studentDetail->StudentHostelFeeStructure as $fee) {
            $feeAmount = $fee->hostel_fee ?? 0;
            $totalHostelFees += $feeAmount;
            if ($fee->payment_status === 'paid') {
                $totalPaidHostelFees += ($fee->paid_amount ?? $feeAmount);
            } else {
                $totalPendingHostelFees += $feeAmount;
            }
        }
        
        // Calculate transport fee totals
        $totalTransportFees = 0;
        $totalPaidTransportFees = 0;
        $totalPendingTransportFees = 0;
        
        foreach ($studentDetail->StudentTransportFeeStructure as $fee) {
            $feeAmount = $fee->transport_fee ?? 0;
            $totalTransportFees += $feeAmount;
            if ($fee->payment_status === 'paid') {
                $totalPaidTransportFees += ($fee->paid_amount ?? $feeAmount);
            } else {
                $totalPendingTransportFees += $feeAmount;
            }
        }
        
        // Calculate miscellaneous fee totals
        $totalMiscellaneousFees = 0;
        $totalPaidMiscellaneousFees = 0;
        $totalPendingMiscellaneousFees = 0;
        
        foreach ($studentDetail->StudentMiscellaneousFeeStructure as $fee) {
            $feeAmount = $fee->miscellaneous_fee ?? 0;
            $totalMiscellaneousFees += $feeAmount;
            if ($fee->payment_status === 'paid') {
                $totalPaidMiscellaneousFees += ($fee->paid_amount ?? $feeAmount);
            } else {
                $totalPendingMiscellaneousFees += $feeAmount;
            }
        }
        
        // Calculate payment progress for course fees
        $totalInstallments = $studentDetail->StudentCourseFeeStructure->count();
        $paidInstallments = $studentDetail->StudentCourseFeeStructure->where('payment_status', 'paid')->count();
        $paymentProgressPercentage = $totalInstallments > 0 ? ($paidInstallments / $totalInstallments) * 100 : 0;
        
        // Get next due installment (course fee only)
        $nextInstallment = $studentDetail->StudentCourseFeeStructure
            ->where('payment_status', '!=', 'paid')
            ->where('due_date', '>=', now())
            ->sortBy('due_date')
            ->first();
        
        // Get overdue installments (course fee only)
        $overdueInstallments = $studentDetail->StudentCourseFeeStructure
            ->where('payment_status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->count();
        
        // Calculate total overall fees (all types)
        $totalAllFees = $totalCourseFees + $totalRegistrationFees + $totalHostelFees + $totalTransportFees + $totalMiscellaneousFees;
        $totalPaidAllFees = $totalPaidCourseFees + $totalPaidRegistrationFees + $totalPaidHostelFees + $totalPaidTransportFees + $totalPaidMiscellaneousFees;
        $totalPendingAllFees = $totalAllFees - $totalPaidAllFees;

        // Get section display name using the helper function
        $academic = $studentDetail->academicTransportDetails;
        $sectionDisplayName = null;
        if ($academic && $academic->course_subtype_id && $academic->section_id) {
            $sectionDisplayName = $this->resolveSectionName(
                $academic->course_subtype_id,
                $studentDetail->institute_id,
                $academic->section_id,
                $studentDetail->branch_id
            );
        }

        return view('instituteAdmin.StudentFiles.fee-structure', compact(
            'studentDetail', 
            'sectionDisplayName',
            'totalCourseFees',
            'totalPaidCourseFees',
            'totalPendingCourseFees',
            'totalRegistrationFees',
            'totalPaidRegistrationFees',
            'totalPendingRegistrationFees',
            'totalHostelFees',
            'totalPaidHostelFees',
            'totalPendingHostelFees',
            'totalTransportFees',
            'totalPaidTransportFees',
            'totalPendingTransportFees',
            'totalMiscellaneousFees',
            'totalPaidMiscellaneousFees',
            'totalPendingMiscellaneousFees',
            'totalAllFees',
            'totalPaidAllFees',
            'totalPendingAllFees',
            'totalInstallments',
            'paidInstallments',
            'paymentProgressPercentage',
            'nextInstallment',
            'overdueInstallments',
            'currentProductId',
            'currentAcademicYear',
            'currentBatchId'
        ));
    }

    private function resolveSectionName($productId, $instituteId, $sectionId, $branchId = null)
    {
            if (!$productId || !$sectionId) {
                return null;
            }

            $query = DB::table('course_fee_structures')
                ->where('product_id', $productId)
                ->where('institute_id', $instituteId);

            $sectionData = $query->value('sections');

            if (!$sectionData) {
                return null;
            }

            $sections = json_decode($sectionData, true);

            if (!is_array($sections)) {
                return null;
            }

            foreach ($sections as $section) {
                if (!isset($section['id'], $section['name'])) {
                    continue;
                }

                // Normalize both values
                $dbSection = str_replace('section_', '', $section['id']);
                $studentSection = str_replace('section_', '', $sectionId);

                if ($dbSection == $studentSection) {
                    return $section['name'];
                }
            }

            return null;
    }

}