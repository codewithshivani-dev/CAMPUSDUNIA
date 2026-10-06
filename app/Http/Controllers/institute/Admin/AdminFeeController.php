<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentGatewayLink;
use App\Models\StudentCourseFeeStructure;
use App\Models\StudentCustomFeestructure;
use App\Models\StudentHostelFeeStructure;
use App\Models\StudentMiscellaneousFeeStructure;
use App\Models\StudentRegistrationFeeStructure;
use App\Models\StudentTransportFeeStructure;
use App\Models\InstituteBasicDetails;
use App\Models\StudentParentDetails;
use App\Models\StudentAcademicDetailsLog;
use App\Models\StudentAcademicTransportDetails;
use App\Models\CourseFeeStructure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminFeeController extends Controller
{
  
    public function getCurseFeeStructure()
    {
        $institute_id = auth()->user()->institute_id;
        $perPage = request('per_page', 10);
        if ($perPage === 'all') {
            $perPage = 100;
        } else {
            $perPage = (int) $perPage;
        }

        $academicYearFilter = request('academic_year');
        
        // Set default academic year if not provided
        if (!$academicYearFilter) {
            $academicYearFilter = $this->getCurrentAcademicYear();
        }

        // ============ BUILD STUDENT FILTER MAP ============
        $studentFilterMap = [];
        
        // Get from transport details for students in that year
        $transportRecords = StudentAcademicTransportDetails::where('institute_id', $institute_id)
            ->where('academic_year', $academicYearFilter)
            ->select('academic_year_id', 'student_hash_id', 'course_subtype_id', 'batch_id')
            ->get();

        foreach ($transportRecords as $record) {
            if (!isset($studentFilterMap[$record->student_hash_id])) {
                $studentFilterMap[$record->student_hash_id] = [
                    'product_id' => $record->course_subtype_id,
                    'batch_id' => $record->batch_id,
                    'academic_year_id' => $record->academic_year_id
                ];
            }
        }

        // Get from logs for students who were promoted
        $logRecords = StudentAcademicDetailsLog::where('institute_id', $institute_id)
            ->where(function($q) use ($academicYearFilter) {
                $q->where('previous_academic_year', $academicYearFilter)
                ->orWhere('new_academic_year', $academicYearFilter);
            })
            ->get();

        foreach ($logRecords as $log) {
            if ($log->previous_academic_year == $academicYearFilter) {
                if (!isset($studentFilterMap[$log->student_hash_id])) {
                    $studentFilterMap[$log->student_hash_id] = [
                        'product_id' => $log->previous_course_subtype_id,
                        'batch_id' => $log->previous_batch_id,
                        'academic_year_id' => $log->previous_academic_year_id
                    ];
                }
            }
            
            if ($log->new_academic_year == $academicYearFilter) {
                if (!isset($studentFilterMap[$log->student_hash_id])) {
                    $studentFilterMap[$log->student_hash_id] = [
                        'product_id' => $log->new_course_subtype_id,
                        'batch_id' => $log->new_batch_id,
                        'academic_year_id' => $log->new_academic_year_id
                    ];
                }
            }
        }

        // If no students found via filters, get ALL students with fee records for this academic year
        if (empty($studentFilterMap)) {
            $feeRecordsForYear = StudentCourseFeeStructure::where('institute_id', $institute_id)
                ->where('academic_year_id', 'LIKE', '%' . $academicYearFilter . '%')
                ->orWhere('academic_year_id', $academicYearFilter)
                ->select('student_hash_id', 'product_id', 'batch_id', 'academic_year_id')
                ->distinct()
                ->get();
            
            foreach ($feeRecordsForYear as $record) {
                if (!isset($studentFilterMap[$record->student_hash_id])) {
                    $studentFilterMap[$record->student_hash_id] = [
                        'product_id' => $record->product_id,
                        'batch_id' => $record->batch_id,
                        'academic_year_id' => $record->academic_year_id
                    ];
                }
            }
        }

        $studentHashIds = array_keys($studentFilterMap);

        // If no students found, return empty
        if (empty($studentHashIds)) {
            return $this->getEmptyCourseFeeResponse($institute_id, $perPage, $academicYearFilter);
        }

        // ============ GET FEE RECORDS ============
        $feeQuery = StudentCourseFeeStructure::with(['studentDetail'])
            ->where('student_course_fee_structures.institute_id', $institute_id)
            ->whereIn('student_hash_id', $studentHashIds);
        
        // Apply filters
        if (request('start_date')) {
            $feeQuery->whereDate('due_date', '>=', request('start_date'));
        }
        
        if (request('end_date')) {
            $feeQuery->whereDate('due_date', '<=', request('end_date'));
        }
        
        if (request('payment_type')) {
            $feeQuery->where('payment_type', request('payment_type'));
        }
        
        if (request('payment_status')) {
            if (request('payment_status') === 'paid') {
                $feeQuery->where('payment_status', 'paid');
            } elseif (request('payment_status') === 'pending') {
                $feeQuery->where('payment_status', '!=', 'paid')
                    ->whereDate('due_date', '>=', now());
            } elseif (request('payment_status') === 'overdue') {
                $feeQuery->where('payment_status', '!=', 'paid')
                    ->whereDate('due_date', '<', now());
            }
        }
        
        // Apply product/batch mapping if available
        $hasExactMapping = false;
        foreach ($studentFilterMap as $details) {
            if ($details['product_id'] && $details['batch_id'] && $details['academic_year_id']) {
                $hasExactMapping = true;
                break;
            }
        }
        
        if ($hasExactMapping) {
            $feeQuery->where(function($q) use ($studentFilterMap) {
                foreach ($studentFilterMap as $studentHash => $details) {
                    if ($details['product_id'] && $details['batch_id'] && $details['academic_year_id']) {
                        $q->orWhere(function($sub) use ($studentHash, $details) {
                            $sub->where('student_hash_id', $studentHash)
                                ->where('product_id', $details['product_id'])
                                ->where('batch_id', $details['batch_id'])
                                ->where('academic_year_id', $details['academic_year_id']);
                        });
                    }
                }
            });
        }
        
        $allFeeRecords = $feeQuery->orderBy('due_date')->get();
        
        // Group by student
        $students = [];
        
        foreach ($allFeeRecords as $fee) {
            $student = $fee->studentDetail;
            if (!$student) continue;
            
            $studentHash = $fee->student_hash_id;
            
            // Get academic details for display
            $academic = $this->getStudentAcademicForYear($studentHash, $academicYearFilter, $institute_id);
            
            if (!isset($students[$studentHash])) {
                $sectionName = $this->getSectionDisplayName(
                    $academic->section_id ?? null,
                    $academic->product_id ?? $fee->product_id,
                    $institute_id,
                    $academic->branch_id ?? null
                );
                
                $students[$studentHash] = [
                    'student_hash_id' => $studentHash,
                    'student_name' => trim($student->first_name . ' ' . ($student->middle_name ? $student->middle_name . ' ' : '') . $student->last_name),
                    'student_reg' => $student->registration_number,
                    'department' => $academic->department ?? '',
                    'course' => $academic->course_type ?? '',
                    'batch' => $academic->batch ?? $fee->batch ?? '',
                    'academic_year' => $academic->academic_year ?? $academicYearFilter,
                    'semester' => $academic->semester_id ?? '',
                    'section' => $sectionName,
                    'mode_type' => $academic->mode_type ?? '',
                    'mode_of_course' => $academic->mode_of_course ?? '',
                    'installments' => [],
                    'total_fee' => 0,
                    'total_paid' => 0,
                    'total_pending' => 0,
                    'current_installment' => null
                ];
            }
            
            // Calculate amounts
            $feeAmount = floatval($fee->course_fee ?? 0);
            $lateFee = floatval($fee->late_fee_amount ?? 0);
            $discount = floatval($fee->discount_amount ?? 0);
            $payableAmount = $feeAmount + $lateFee - $discount;
            
            // Add installment
            $students[$studentHash]['installments'][] = [
                'id' => $fee->id,
                'fee_amount' => $feeAmount,
                'payable_amount' => $payableAmount,
                'late_fee' => $lateFee,
                'discount' => $discount,
                'due_date' => $fee->due_date,
                'payment_status' => $fee->payment_status,
                'updated_at' => $fee->updated_at,
            ];
            
            // Update totals
            $students[$studentHash]['total_fee'] += $payableAmount;
            if ($fee->payment_status === 'paid') {
                $students[$studentHash]['total_paid'] += $payableAmount;
            } else {
                $students[$studentHash]['total_pending'] += $payableAmount;
            }
        }
        
        // Sort installments and set current installment
        foreach ($students as &$student) {
            usort($student['installments'], function($a, $b) {
                return strtotime($a['due_date']) - strtotime($b['due_date']);
            });
            
            foreach ($student['installments'] as $inst) {
                if ($inst['payment_status'] !== 'paid') {
                    $student['current_installment'] = $inst;
                    break;
                }
            }
        }
        
        // Apply student search filter
        if (request('student_search')) {
            $search = strtolower(request('student_search'));
            $students = array_filter($students, function($student) use ($search) {
                return strpos(strtolower($student['student_name']), $search) !== false || 
                    strpos(strtolower($student['student_reg']), $search) !== false;
            });
        }
        
        // Paginate the results
        $studentsArray = array_values($students);
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPage = (int) request('per_page', 10);
        if ($perPage === 'all') {
            $perPage = count($studentsArray);
        }
        $currentPageItems = array_slice($studentsArray, ($currentPage - 1) * $perPage, $perPage);
        
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            count($studentsArray),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        // Calculate totals for stats
        $totalPayable = 0;
        $totalPaidCount = 0;
        $totalOverdueCount = 0;
        
        foreach ($studentsArray as $student) {
            $totalPayable += $student['total_fee'];
            foreach ($student['installments'] as $inst) {
                if ($inst['payment_status'] === 'paid') {
                    $totalPaidCount++;
                } elseif ($inst['payment_status'] !== 'paid' && strtotime($inst['due_date']) < strtotime(now())) {
                    $totalOverdueCount++;
                }
            }
        }
        
        // Get filter data for dropdowns
        $departments = \App\Models\Departments::where('institute_id', $institute_id)->get();
        $courses = StudentAcademicTransportDetails::where('institute_id', $institute_id)->distinct()->pluck('course_type');
        $batches = StudentAcademicTransportDetails::where('institute_id', $institute_id)->distinct()->pluck('batch');
        
        $transportYears = StudentAcademicTransportDetails::where('institute_id', $institute_id)
            ->distinct()
            ->pluck('academic_year');
        
        $logYears = StudentAcademicDetailsLog::where('institute_id', $institute_id)
            ->distinct()
            ->pluck('previous_academic_year')
            ->merge(
                StudentAcademicDetailsLog::where('institute_id', $institute_id)
                    ->distinct()
                    ->pluck('new_academic_year')
            )
            ->unique()
            ->filter()
            ->values();
        
        $academicYears = $transportYears->merge($logYears)->unique()->sortDesc()->values();
        
        return view('instituteAdmin.AdminFeeStructureFile.CourseFee', compact(
            'paginator',
            'departments',
            'courses',
            'batches',
            'totalPayable',
            'totalPaidCount',
            'totalOverdueCount',
            'academicYears'
        ));
    }
    
    /**
     * Get student academic details for a specific year
     */
    private function getStudentAcademicForYear($studentHash, $academicYear, $institute_id)
    {
        // Try to get from transport details
        $academic = StudentAcademicTransportDetails::where('student_hash_id', $studentHash)
            ->where('academic_year', $academicYear)
            ->first();
        
        if ($academic) {
            return $academic;
        }
        
        // Try to get from logs
        $log = StudentAcademicDetailsLog::where('student_hash_id', $studentHash)
            ->where(function($q) use ($academicYear) {
                $q->where('previous_academic_year', $academicYear)
                ->orWhere('new_academic_year', $academicYear);
            })
            ->first();
        
        if ($log) {
            $result = new \stdClass();
            
            if ($log->previous_academic_year == $academicYear) {
                $result->department = $log->previous_department;
                $result->department_id = $log->previous_department_id;
                $result->course_type = $log->previous_course_type;
                $result->course_subtype = $log->previous_course_subtype;
                $result->course_subtype_id = $log->previous_course_subtype_id;
                $result->product_id = $log->previous_course_subtype_id;
                $result->batch = $log->previous_batch;
                $result->batch_id = $log->previous_batch_id;
                $result->academic_year = $log->previous_academic_year;
                $result->academic_year_id = $log->previous_academic_year_id;
                $result->section_id = $log->previous_section_id;
                $result->mode_type = $log->previous_mode_type;
                $result->mode_of_course = $log->previous_mode_of_course;
                $result->semester_id = $log->previous_semester_id;  // ADD THIS
                $result->branch_id = $log->previous_branch_id;
            } else {
                $result->department = $log->new_department;
                $result->department_id = $log->new_department_id;
                $result->course_type = $log->new_course_type;
                $result->course_subtype = $log->new_course_subtype;
                $result->course_subtype_id = $log->new_course_subtype_id;
                $result->product_id = $log->new_course_subtype_id;
                $result->batch = $log->new_batch;
                $result->batch_id = $log->new_batch_id;
                $result->academic_year = $log->new_academic_year;
                $result->academic_year_id = $log->new_academic_year_id;
                $result->section_id = $log->new_section_id;
                $result->mode_type = $log->new_mode_type;
                $result->mode_of_course = $log->new_mode_of_course;
                $result->semester_id = $log->new_semester_id;  // ADD THIS
                $result->branch_id = $log->new_branch_id;
            }
            
            return $result;
        }
        
        // Fallback: get any academic record
        $anyAcademic = StudentAcademicTransportDetails::where('student_hash_id', $studentHash)->first();
        
        if ($anyAcademic) {
            return $anyAcademic;
        }
        
        // Return empty object
        return new \stdClass();
    }

    /**
     * Get empty course fee response when no students found
     */
    private function getEmptyCourseFeeResponse($institute_id, $perPage, $academicYearFilter = null)
    {
        $transportYears = StudentAcademicTransportDetails::where('institute_id', $institute_id)
            ->distinct()
            ->pluck('academic_year');

        $logYears = StudentAcademicDetailsLog::where('institute_id', $institute_id)
            ->distinct()
            ->pluck('previous_academic_year')
            ->merge(
                StudentAcademicDetailsLog::where('institute_id', $institute_id)
                    ->distinct()
                    ->pluck('new_academic_year')
            )
            ->unique()
            ->filter()
            ->values();

        $academicYears = $transportYears->merge($logYears)->unique()->sortDesc()->values();
        
        $departments = \App\Models\Departments::where('institute_id', $institute_id)->get();
        $courses = StudentAcademicTransportDetails::where('institute_id', $institute_id)
            ->distinct()->pluck('course_type');
        $batches = StudentAcademicTransportDetails::where('institute_id', $institute_id)
            ->distinct()->pluck('batch');

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            [],
            0,
            $perPage,
            1,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        $totalPayable = 0;
        $totalPaidCount = 0;
        $totalOverdueCount = 0;

        return view('instituteAdmin.AdminFeeStructureFile.CourseFee', compact(
            'paginator',
            'departments',
            'courses',
            'batches',
            'totalPayable',
            'totalPaidCount',
            'totalOverdueCount',
            'academicYears'
        ));
    }


    private function getAcademicRecordForYear($studentHashId, $academicYearFilter, $logs, $productBatchMap = null)
    {
        // First, try to get from the product-batch map (most accurate)
        if ($productBatchMap && isset($productBatchMap['product_id'])) {
            $academic = new \stdClass();
            
            // Try to get detailed info from product_details
            $productDetails = DB::table('product_details')
                ->where('product_id', $productBatchMap['product_id'])
                ->first();
            
            $academic->department = $productDetails->department_name ?? null;
            $academic->department_id = $productDetails->department_id ?? null;
            $academic->course_type = $productDetails->course_type ?? null;
            $academic->course_subtype = $productDetails->sub_type ?? null;
            $academic->course_subtype_id = $productBatchMap['product_id'];
            $academic->product_id = $productBatchMap['product_id'];
            $academic->batch_id = $productBatchMap['batch_id'];
            $academic->batch = null; // Will be fetched if needed
            $academic->academic_year_id = $productBatchMap['academic_year_id'];
            $academic->academic_year = $academicYearFilter;
            $academic->section_id = null;
            $academic->mode_type = $productDetails->mode_type ?? null;
            $academic->mode_of_course = $productDetails->mode_of_course ?? null;
            $academic->branch_id = $productDetails->branch_id ?? null;
            
            // Try to get batch name from course_fee_structures
            if ($productBatchMap['batch_id'] && $productBatchMap['academic_year_id']) {
                $feeStructure = DB::table('course_fee_structures')
                    ->where('product_id', $productBatchMap['product_id'])
                    ->where('batch_id', $productBatchMap['batch_id'])
                    ->where('academic_year_id', $productBatchMap['academic_year_id'])
                    ->first();
                
                if ($feeStructure) {
                    $academic->batch = $feeStructure->batch;
                    $academic->section_id = $feeStructure->section_id ?? null;
                }
            }
            
            return $academic;
        }
        
        // Fallback to logs if no product-batch map
        if ($logs->isEmpty()) {
            return null;
        }

        $matchedLog = $logs->firstWhere('previous_academic_year', $academicYearFilter);
        $fieldPrefix = 'previous';

        if (!$matchedLog) {
            $matchedLog = $logs->firstWhere('new_academic_year', $academicYearFilter);
            $fieldPrefix = 'new';
        }

        if (!$matchedLog) {
            return null;
        }

        $academic = new \stdClass();
        $academic->department = $matchedLog->{$fieldPrefix . '_department'} ?? null;
        $academic->department_id = $matchedLog->{$fieldPrefix . '_department_id'} ?? null;
        $academic->course_type = $matchedLog->{$fieldPrefix . '_course_type'} ?? null;
        $academic->course_subtype = $matchedLog->{$fieldPrefix . '_course_subtype'} ?? null;
        $academic->course_subtype_id = $matchedLog->{$fieldPrefix . '_course_subtype_id'} ?? null;
        $academic->product_id = $matchedLog->{$fieldPrefix . '_course_subtype_id'} ?? null;
        $academic->batch = $matchedLog->{$fieldPrefix . '_batch'} ?? null;
        $academic->batch_id = $matchedLog->{$fieldPrefix . '_batch_id'} ?? null;
        $academic->academic_year = $matchedLog->{$fieldPrefix . '_academic_year'} ?? $academicYearFilter;
        $academic->academic_year_id = $matchedLog->{$fieldPrefix . '_academic_year_id'} ?? null;
        $academic->semester_id = $matchedLog->{$fieldPrefix . '_semester_id'} ?? null;
        $academic->section_id = $matchedLog->{$fieldPrefix . '_section_id'} ?? null;
        $academic->mode_type = $matchedLog->{$fieldPrefix . '_mode_type'} ?? null;
        $academic->mode_of_course = $matchedLog->{$fieldPrefix . '_mode_of_course'} ?? null;
        $academic->branch_id = $matchedLog->{$fieldPrefix . '_branch_id'} ?? null;

        return $academic;
    }


    public function studentCourseInstallments($student_hash)
    {
        $institute_id = auth()->user()->institute_id;
        
        // Get student details
        $studentData = StudentParentDetails::where('student_hash_id', $student_hash)
            ->where('institute_id', $institute_id)
            ->first();
            
        if (!$studentData) {
            abort(404, 'Student not found');
        }
        
        // Get the selected academic year from request, or use current
        $academicYearFilter = request('academic_year');
        
        // If no filter, get current academic year
        if (!$academicYearFilter) {
            $academicYearFilter = $this->getCurrentAcademicYear();
        }
        
        $academic = null;
        $productId = null;
        $batchId = null;
        $academicYearId = null;
        
        // First try to get from logs for the selected year
        $log = StudentAcademicDetailsLog::where('student_hash_id', $student_hash)
            ->where(function ($q) use ($academicYearFilter) {
                $q->where('previous_academic_year', $academicYearFilter)
                    ->orWhere('new_academic_year', $academicYearFilter);
            })
            ->latest('id')
            ->first();

        if ($log) {
            // Determine which set of fields to use
            if ($log->previous_academic_year == $academicYearFilter) {
                $productId = $log->previous_course_subtype_id;
                $batchId = $log->previous_batch_id;
                $academicYearId = $log->previous_academic_year_id;
                
                $academic = new \stdClass();
                $academic->department_id = $log->previous_department_id;
                $academic->department = $log->previous_department;
                $academic->course_subtype_id = $log->previous_course_subtype_id;
                $academic->course_subtype = $log->previous_course_subtype;
                $academic->batch_id = $log->previous_batch_id;
                $academic->academic_year_id = $log->previous_academic_year_id;
                $academic->academic_year = $log->previous_academic_year;
                $academic->section_id = $log->previous_section_id;
                $academic->mode_type = $log->previous_mode_type;
                $academic->mode_of_course = $log->previous_mode_of_course;
                $academic->branch_id = null;
                
                // Get batch name from course_fee_structures
                if ($productId && $batchId && $academicYearId) {
                    $feeStructure = DB::table('course_fee_structures')
                        ->where('product_id', $productId)
                        ->where('batch_id', $batchId)
                        ->where('academic_year_id', $academicYearId)
                        ->first();
                    if ($feeStructure) {
                        $academic->batch = $feeStructure->batch;
                    }
                }
            } else {
                $productId = $log->new_course_subtype_id;
                $batchId = $log->new_batch_id;
                $academicYearId = $log->new_academic_year_id;
                
                $academic = new \stdClass();
                $academic->department_id = $log->new_department_id;
                $academic->department = $log->new_department;
                $academic->course_subtype_id = $log->new_course_subtype_id;
                $academic->course_subtype = $log->new_course_subtype;
                $academic->batch_id = $log->new_batch_id;
                $academic->academic_year_id = $log->new_academic_year_id;
                $academic->academic_year = $log->new_academic_year;
                $academic->section_id = $log->new_section_id;
                $academic->mode_type = $log->new_mode_type;
                $academic->mode_of_course = $log->new_mode_of_course;
                $academic->branch_id = null;
                
                // Get batch name from course_fee_structures
                if ($productId && $batchId && $academicYearId) {
                    $feeStructure = DB::table('course_fee_structures')
                        ->where('product_id', $productId)
                        ->where('batch_id', $batchId)
                        ->where('academic_year_id', $academicYearId)
                        ->first();
                    if ($feeStructure) {
                        $academic->batch = $feeStructure->batch;
                    }
                }
            }
        } else {
            // No log found, try to get from transport details (current academic year)
            $academic = StudentAcademicTransportDetails::where('student_hash_id', $student_hash)
                ->where('institute_id', $institute_id)
                ->latest('id')
                ->first();

            if ($academic) {
                $productId = $academic->course_subtype_id;
                $batchId = $academic->batch_id;
                $academicYearId = $academic->academic_year_id;
            }
        }
        
        // If still no academic found, try to get any transport record
        if (!$academic) {
            $academic = StudentAcademicTransportDetails::where('student_hash_id', $student_hash)
                ->where('institute_id', $institute_id)
                ->first();
                
            if ($academic) {
                $productId = $academic->course_subtype_id;
                $batchId = $academic->batch_id;
                $academicYearId = $academic->academic_year_id;
            }
        }
        
        if (!$academic) {
            abort(404, 'Academic details not found for this student');
        }
        
        // Get installments for the specific product, batch, and academic year
        $installments = StudentCourseFeeStructure::where('student_hash_id', $student_hash)
            ->where('institute_id', $institute_id)
            ->where('product_id', $productId)
            ->where('batch_id', $batchId)
            ->where('academic_year_id', $academicYearId)
            ->orderBy('due_date')
            ->get();
        
        // If no installments found, try to create them from the fee structure
        if ($installments->isEmpty() && $productId && $batchId && $academicYearId) {
            $this->createStudentInstallmentsFromStructure($student_hash, $productId, $batchId, $academicYearId, $institute_id);
            
            // Fetch again after creation
            $installments = StudentCourseFeeStructure::where('student_hash_id', $student_hash)
                ->where('institute_id', $institute_id)
                ->where('product_id', $productId)
                ->where('batch_id', $batchId)
                ->where('academic_year_id', $academicYearId)
                ->orderBy('due_date')
                ->get();
        }
        
        // Also get previous academic year installments (for historical view)
        $previousInstallments = StudentCourseFeeStructure::where('student_hash_id', $student_hash)
            ->where('institute_id', $institute_id)
            ->where(function($q) use ($productId, $batchId, $academicYearId) {
                $q->where('product_id', '!=', $productId)
                ->orWhere('batch_id', '!=', $batchId)
                ->orWhere('academic_year_id', '!=', $academicYearId);
            })
            ->orderBy('due_date')
            ->get();
        
        // Get current course fee structure
        $currentFeeStructure = CourseFeeStructure::where('product_id', $productId)
            ->where('institute_id', $institute_id)
            ->where('batch_id', $batchId)
            ->where('academic_year_id', $academicYearId)
            ->first();
        
        // If not found, get the latest one for this product and batch
        if (!$currentFeeStructure) {
            $currentFeeStructure = CourseFeeStructure::where('product_id', $productId)
                ->where('institute_id', $institute_id)
                ->where('batch_id', $batchId)
                ->orderBy('academic_year_id', 'desc')
                ->first();
        }
        
        // Decode fee structure data
        $courseFeeStructureData = null;
        $totalCourseFee = 0;
        $paymentSchedule = [];
        $durationType = '';
        $lateFeeInfo = null;
        $partialPaymentInfo = null;
        
        if ($currentFeeStructure && $currentFeeStructure->course_fee) {
            $courseFeeStructureData = json_decode($currentFeeStructure->course_fee, true);
            
            if (is_string($courseFeeStructureData)) {
                $courseFeeStructureData = json_decode($courseFeeStructureData, true);
            }
            
            if (is_array($courseFeeStructureData)) {
                if (isset($courseFeeStructureData['payments']) && is_array($courseFeeStructureData['payments'])) {
                    $paymentSchedule = $courseFeeStructureData['payments'];
                    
                    foreach ($paymentSchedule as $payment) {
                        $amount = is_array($payment) ? ($payment['amount'] ?? 0) : $payment;
                        $totalCourseFee += floatval($amount);
                    }
                }
                
                $durationType = $courseFeeStructureData['duration'] ?? 'Not specified';
                $lateFeeInfo = $courseFeeStructureData['late_fee'] ?? null;
                $partialPaymentInfo = $courseFeeStructureData['partial_payment'] ?? null;
            }
        }
        
        // Calculate summary
        $summary = $this->calculateCourseInstallmentSummary($installments);
        $previousSummary = $this->calculateCourseInstallmentSummary($previousInstallments);
        
        // Get institute details
        $serviceInstitutedetails = InstituteBasicDetails::where('fincap_merchant_id', $institute_id)->first();
        
        // Get section display name
        $sectionName = $this->getSectionDisplayName(
            $academic->section_id ?? null,
            $academic->course_subtype_id ?? $productId,
            $institute_id,
            $academic->branch_id ?? null
        );
        
        // Get promotion history
        $promotionHistory = StudentAcademicDetailsLog::where('student_hash_id', $student_hash)
            ->orderBy('promoted_at', 'desc')
            ->get();
        
        // Calculate payment progress
        $totalInstallments = $installments->count();
        $paidInstallments = $installments->where('payment_status', 'paid')->count();
        $pendingInstallments = $installments->where('payment_status', '!=', 'paid')->count();
        $overdueInstallments = $installments->where('payment_status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->count();
        
        $paymentProgressPercentage = $totalInstallments > 0 
            ? ($paidInstallments / $totalInstallments) * 100 
            : 0;
        
        $totalCurrentCourseFee = $installments->sum('course_fee');
        $totalCurrentCoursePaid = $installments->where('payment_status', 'paid')->sum('paid_amount');
        $totalCurrentCoursePending = $totalCurrentCourseFee - $totalCurrentCoursePaid;
        
        // Get registration fee structure
        $registrationFeeStructure = null;
        $totalRegistrationFee = 0;
        
        if ($currentFeeStructure && $currentFeeStructure->registration_fee) {
            $registrationFeeData = json_decode($currentFeeStructure->registration_fee, true);
            if (is_string($registrationFeeData)) {
                $registrationFeeData = json_decode($registrationFeeData, true);
            }
            $registrationFeeStructure = $registrationFeeData;
            
            if (is_array($registrationFeeData) && isset($registrationFeeData['payments'])) {
                foreach ($registrationFeeData['payments'] as $payment) {
                    $amount = is_array($payment) ? ($payment['amount'] ?? 0) : $payment;
                    $totalRegistrationFee += floatval($amount);
                }
            }
        }
        
        return view('instituteAdmin.AdminFeeStructureFile.StudentCourseInstallments', compact(
            'studentData', 
            'academic', 
            'installments',
            'previousInstallments',
            'summary',
            'previousSummary',
            'serviceInstitutedetails',
            'sectionName',
            'promotionHistory',
            'currentFeeStructure',
            'courseFeeStructureData',
            'totalCourseFee',
            'paymentSchedule',
            'durationType',
            'lateFeeInfo',
            'partialPaymentInfo',
            'registrationFeeStructure',
            'totalRegistrationFee',
            'totalInstallments',
            'paidInstallments',
            'pendingInstallments',
            'overdueInstallments',
            'paymentProgressPercentage',
            'totalCurrentCourseFee',
            'totalCurrentCoursePaid',
            'totalCurrentCoursePending'
        ));
    }

    /**
     * Create student installments from fee structure if they don't exist
     */
    private function createStudentInstallmentsFromStructure($student_hash, $productId, $batchId, $academicYearId, $institute_id)
    {
        try {
            // Get fee structure
            $feeStructure = CourseFeeStructure::where('product_id', $productId)
                ->where('batch_id', $batchId)
                ->where('academic_year_id', $academicYearId)
                ->where('institute_id', $institute_id)
                ->first();
            
            if (!$feeStructure) {
                \Log::warning('Fee structure not found for student', [
                    'student_hash' => $student_hash,
                    'product_id' => $productId,
                    'batch_id' => $batchId,
                    'academic_year_id' => $academicYearId
                ]);
                return;
            }
            
            // Decode course fee
            $courseFee = json_decode($feeStructure->course_fee, true);
            if (is_string($courseFee)) {
                $courseFee = json_decode($courseFee, true);
            }
            
            if (!is_array($courseFee) || !isset($courseFee['payments'])) {
                \Log::warning('Invalid course fee structure', ['student_hash' => $student_hash]);
                return;
            }
            
            $payments = $courseFee['payments'];
            $duration = $courseFee['duration'] ?? 'yearly';
            $lateFee = $courseFee['late_fee'] ?? null;
            $partialPayment = $courseFee['partial_payment'] ?? null;
            
            // Create installments
            foreach ($payments as $index => $payment) {
                $amount = is_array($payment) ? ($payment['amount'] ?? 0) : $payment;
                $endDate = is_array($payment) ? ($payment['end_date'] ?? null) : null;
                
                // Calculate due date if not provided
                if (!$endDate) {
                    $baseDate = Carbon::now();
                    $endDate = $baseDate->addMonths($index)->format('Y-m-d');
                }
                
                StudentCourseFeeStructure::create([
                    'institute_id' => $institute_id,
                    'student_hash_id' => $student_hash,
                    'product_id' => $productId,
                    'fee_duration_type' => $duration,
                    'batch_id' => $batchId,
                    'academic_year_id' => $academicYearId,
                    'course_fee' => $amount,
                    'due_date' => $endDate,
                    'late_fee_type' => $lateFee['type'] ?? null,
                    'late_fee_value' => $lateFee['amount'] ?? 0,
                    'partially_fee_type' => $partialPayment['type'] ?? null,
                    'partially_fee_value' => $partialPayment['value'] ?? 0,
                    'payment_status' => 'pending'
                ]);
            }
            
          
            
        } catch (\Exception $e) {
            \Log::error('Failed to create installments for student', [
                'student_hash' => $student_hash,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Calculate summary for course installments (only for current product/course)
     */
    private function calculateCourseInstallmentSummary($installments)
    {
        if ($installments->isEmpty()) {
            return [
                'total_installments' => 0,
                'total_fee' => 0,
                'total_paid' => 0,
                'total_pending' => 0,
                'paid_count' => 0,
                'pending_count' => 0,
                'monthly_emi' => 0,
                'expected_total' => 0,
                'completion_percentage' => 0,
                'total_course_fee' => 0,
                'total_late_fee' => 0,
                'total_discount' => 0,
                'total_paid_amount' => 0,
                'remaining_amount' => 0,
            ];
        }
        
        $totalFee = 0;
        $totalPaid = 0;
        $totalPending = 0;
        $paidCount = 0;
        $pendingCount = 0;
        $totalLateFee = 0;
        $totalDiscount = 0;
        $totalPaidAmount = 0;
        
        foreach ($installments as $inst) {
            $feeAmount = $inst->course_fee ?? 0;
            $lateFee = $inst->late_fee_amount ?? 0;
            $discount = $inst->discount_amount ?? 0;
            $paidAmount = $inst->paid_amount ?? 0;
            $payable = $feeAmount + $lateFee - $discount;
            
            $totalFee += $payable;
            $totalLateFee += $lateFee;
            $totalDiscount += $discount;
            $totalPaidAmount += $paidAmount;
            
            if ($inst->payment_status === 'paid') {
                $totalPaid += $payable;
                $paidCount++;
            } else {
                $totalPending += $payable;
                $pendingCount++;
            }
        }
        
        // Get monthly EMI (first installment amount after discount)
        $monthlyEMIAfterDiscount = 0;
        $totalCourseFee = 0;
        if ($installments->isNotEmpty()) {
            $first = $installments->first();
            $monthlyEMIAfterDiscount = ($first->course_fee ?? 0) - ($first->discount_amount ?? 0);
            $totalCourseFee = $installments->sum('course_fee');
        }
        
        return [
            'total_installments' => $installments->count(),
            'total_fee' => $totalFee,
            'total_paid' => $totalPaid,
            'total_pending' => $totalPending,
            'paid_count' => $paidCount,
            'pending_count' => $pendingCount,
            'monthly_emi' => $monthlyEMIAfterDiscount,
            'expected_total' => $monthlyEMIAfterDiscount * $installments->count(),
            'completion_percentage' => $installments->count() > 0 ? ($paidCount / $installments->count()) * 100 : 0,
            'total_course_fee' => $totalCourseFee,
            'total_late_fee' => $totalLateFee,
            'total_discount' => $totalDiscount,
            'total_paid_amount' => $totalPaidAmount,
            'remaining_amount' => $totalFee - $totalPaidAmount,
        ];
    }

    private function getSectionDisplayName($sectionId, $productId, $instituteId, $branchId = null)
    {
        if (empty($sectionId) || empty($productId)) {
            return $sectionId;
        }

        $query = DB::table('course_fee_structures')
            ->where('product_id', $productId)
            ->where('institute_id', $instituteId);

        if (!empty($branchId)) {
            $query->where('branch_id', $branchId);
        }

        $sectionData = $query->value('sections');

        if (!$sectionData) {
            \Log::warning('No section data found', [
                'product_id' => $productId,
                'section_id' => $sectionId,
                'institute_id' => $instituteId
            ]);
            return $sectionId;
        }

        $sections = json_decode($sectionData, true);

        if (!is_array($sections)) {
            return $sectionId;
        }


        // Try different matching strategies
        $normalizedInput = str_replace('section_', '', $sectionId);
        $originalInput = $sectionId;

        foreach ($sections as $section) {
            // Get the section ID from the array (could be 'id', 'section_id', or numeric index)
            $dbSectionId = null;
            
            if (isset($section['id'])) {
                $dbSectionId = $section['id'];
            } elseif (isset($section['section_id'])) {
                $dbSectionId = $section['section_id'];
            } elseif (is_string($section) && is_numeric($section)) {
                $dbSectionId = $section;
            }
            
            $sectionName = $section['name'] ?? null;
            
            if (!$sectionName) continue;
            
            // Clean the DB section ID for comparison
            $cleanDbId = str_replace('section_', '', (string)$dbSectionId);
            
            // Check multiple variations
            if ($dbSectionId == $originalInput || 
                $dbSectionId == $normalizedInput ||
                $cleanDbId == $normalizedInput ||
                $cleanDbId == $originalInput) {
                return $sectionName;
            }
            
            // Also check if the section name is directly the ID
            if ($sectionName == $originalInput || $sectionName == $normalizedInput) {
                return $sectionName;
            }
        }

        // If still not found, try to get from the student's academic transport details directly
        $academicTransport = StudentAcademicTransportDetails::where('section_id', $sectionId)
            ->where('institute_id', $instituteId)
            ->first();
        
        if ($academicTransport && $academicTransport->section_name) {
            return $academicTransport->section_name;
        }

        return $sectionId;
    }

    public function getHostelFeeStructure()
    {
        $institute_id = auth()->user()->institute_id;
        $FeeStructures = StudentHostelFeeStructure::where('institute_id',$institute_id)->get();
        $GetFeeStructure = [];

        if ($FeeStructures->count() > 0) {

                foreach ($FeeStructures as $FeeStructure) {
                $student_data = StudentParentDetails::where('student_hash_id',$FeeStructure->student_hash_id)->first();
                $fullName = trim(
                    ($student_data->first_name ?? '') . ' ' .
                    ($student_data->middle_name ?? '') . ' ' .
                    ($student_data->last_name ?? '')
                );
                $student_academics = StudentAcademicTransportDetails::where('student_hash_id',$FeeStructure->student_hash_id)->first();
                $GetFeeStructure[] = [
                    'id' => $FeeStructure->id,
                    'student_reg' => $student_data->registration_number,
                    'department' => $student_academics->department,
                    'course' => $student_academics->course_type,
                    'branch' => $student_academics->course_subtype,
                    'batch' => $student_academics->batch,
                    'academic_year' => $student_academics->academic_year,
                    'semester' => $student_academics->semester_id,
                    'Section' => $student_academics->section_id,
                    'mode_type'=> $student_academics->mode_type,
                    'mode_of_course' => $student_academics->mode_of_course,
                    'student_name' => $fullName,
                    'fee_duration_type'       => $FeeStructure->fee_duration_type,
                    'fee_amount'                   => $FeeStructure->hostel_fee,
                    'pay_fee_amount' => $FeeStructure->hostel_total_fee,
                    'late_fee_amount'             => $FeeStructure->late_fee_amount,
                    'discount_amount'                => $FeeStructure->discount_amount,
                    'pay_date'          => $FeeStructure->pay_date,
                    'start_date'                      => $FeeStructure->start_date,
                    'due_date'                    => $FeeStructure->due_date,
                    'payment_type'                => $FeeStructure->payment_type,
                    'payment_status'              => $FeeStructure->payment_status,
                    'created_at'                  => $FeeStructure->created_at,
                    'updated_at'                  => $FeeStructure->updated_at,
                ];
            }
        }
    
        return view('instituteAdmin.AdminFeeStructureFile.HostelFeeStructure', compact('GetFeeStructure'));
    }

    public function getTransportFeeStructure(Request $request)
    {
         $institute_id = auth()->user()->institute_id;
        $perPage = $request->get('per_page', 10);
        
        // Get the selected academic year from request, or set to current academic year
        $academicYearFilter = $request->get('academic_year');
        
        // If no filter is applied, get the current academic year
        if (!$academicYearFilter) {
            // Get current academic year based on date
            $academicYearFilter = $this->getCurrentAcademicYear();
            
            // Optional: Verify if this academic year exists in your data
            // If not, fallback to the latest available academic year
            $existsInTransport = StudentAcademicTransportDetails::where('institute_id', $institute_id)
                ->where('academic_year', $academicYearFilter)
                ->exists();
            
            $existsInLogs = StudentAcademicDetailsLog::where('institute_id', $institute_id)
                ->where(function($q) use ($academicYearFilter) {
                    $q->where('previous_academic_year', $academicYearFilter)
                        ->orWhere('new_academic_year', $academicYearFilter);
                })
                ->exists();
            
            if (!$existsInTransport && !$existsInLogs) {
                // Fallback to latest academic year from database
                $latestAcademicYear = StudentAcademicTransportDetails::where('institute_id', $institute_id)
                    ->max('academic_year');
                
                if ($latestAcademicYear) {
                    $academicYearFilter = $latestAcademicYear;
                }
            }
            
            // Store it in the request so the query uses it
            $request->merge(['academic_year' => $academicYearFilter]);
        }
        
        if ($perPage === 'all') {
            $perPage = 100;
        } else {
            $perPage = (int) $perPage;
        }

        // Get academic years for filter dropdown
        $transportYears = StudentAcademicTransportDetails::where('institute_id', $institute_id)
            ->distinct()
            ->pluck('academic_year');

        $logYears = StudentAcademicDetailsLog::where('institute_id', $institute_id)
            ->distinct()
            ->pluck('previous_academic_year')
            ->merge(
                StudentAcademicDetailsLog::where('institute_id', $institute_id)
                    ->distinct()
                    ->pluck('new_academic_year')
            )
            ->unique()
            ->filter()
            ->values();

        $academicYears = $transportYears->merge($logYears)->unique()->sortDesc()->values();

        // ============ NOW APPLY THE FILTER (always active now) ============
        // ======================================================
        // Get Academic Year IDs for selected academic year
        // ======================================================
        $academicYearIds = [];

        // Current academic records
        $transportRecords = StudentAcademicTransportDetails::where(
                'institute_id',
                $institute_id
            )
            ->where('academic_year', $academicYearFilter)
            ->select('academic_year_id')
            ->distinct()
            ->get();

        foreach ($transportRecords as $record) {
            if (!empty($record->academic_year_id)) {
                $academicYearIds[] = $record->academic_year_id;
            }
        }

        // Promotion logs
        $logRecords = StudentAcademicDetailsLog::where(
                'institute_id',
                $institute_id
            )
            ->where(function ($q) use ($academicYearFilter) {
                $q->where('previous_academic_year', $academicYearFilter)
                ->orWhere('new_academic_year', $academicYearFilter);
            })
            ->get();

        foreach ($logRecords as $log) {

            if (
                $log->previous_academic_year == $academicYearFilter
                && !empty($log->previous_academic_year_id)
            ) {
                $academicYearIds[] = $log->previous_academic_year_id;
            }

            if (
                $log->new_academic_year == $academicYearFilter
                && !empty($log->new_academic_year_id)
            ) {
                $academicYearIds[] = $log->new_academic_year_id;
            }
        }

        $academicYearIds = array_unique(array_filter($academicYearIds));

        // ======================================================
        // Filter Transport Fee by academic_year_id directly
        // ======================================================

        $baseQuery = StudentTransportFeeStructure::where(
                'institute_id',
                $institute_id
            )
            ->with(['transportStopId']);

        if (!empty($academicYearIds)) {
            $baseQuery->whereIn(
                'academic_year_id',
                $academicYearIds
            );
        }
        
        // Apply other filters
        if ($request->filled('payment_type')) {
            $baseQuery->where('payment_type', $request->payment_type);
        }
        
        if ($request->filled('start_date')) {
            $baseQuery->whereDate('due_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $baseQuery->whereDate('due_date', '<=', $request->end_date);
        }
        
        if ($request->filled('payment_status')) {
            if ($request->payment_status === 'paid') {
                $baseQuery->where('payment_status', 'paid');
            } elseif ($request->payment_status === 'pending') {
                $baseQuery->where('payment_status', '!=', 'paid')
                    ->whereDate('due_date', '>=', now());
            } elseif ($request->payment_status === 'overdue') {
                $baseQuery->where('payment_status', '!=', 'paid')
                    ->whereDate('due_date', '<', now());
            }
        }
        
        // Get unique student hashes with pagination
        $studentIdsQuery = (clone $baseQuery)
            ->select('student_hash_id')
            ->distinct()
            ->groupBy('student_hash_id');
        
        $studentIds = clone $studentIdsQuery;
        $paginatedStudentIds = $studentIds->paginate($perPage);
        
        if ($paginatedStudentIds->isEmpty()) {
            $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                [],
                0,
                $perPage,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
            
            $departments = \App\Models\Departments::where('institute_id', $institute_id)->get();
            $serviceInstitutedetails = InstituteBasicDetails::where('fincap_merchant_id', $institute_id)->first();
            
            return view('instituteAdmin.AdminFeeStructureFile.TransportFee', compact(
                'paginator', 'departments', 'academicYears', 'serviceInstitutedetails', 'academicYearFilter'
            ));
        }
        
        // Get all fee records for these students
        $feeRecords = (clone $baseQuery)
            ->whereIn('student_hash_id', $paginatedStudentIds->pluck('student_hash_id')->toArray())
            ->orderBy('due_date')
            ->get();
        
        $students = [];
        
        foreach ($feeRecords as $fee) {
            $studentHash = $fee->student_hash_id;
            
            if (!isset($students[$studentHash])) {
                $studentData = StudentParentDetails::where('student_hash_id', $studentHash)->first();
                
                if (!$studentData) {
                    continue;
                }
                
                $academic = $this->getStudentAcademicForYear(
                    $studentHash,
                    $academicYearFilter,  // Use the filtered academic year
                    $institute_id
                );
                
                 if (!$academic || !isset($academic->academic_year)) {
                        // Try to get from the fee record's transport stop relation
                        $academic = $fee->academic;
                    }
                 if (!$academic) {
                    continue;
                }
            
                $fullName = trim(
                    ($studentData->first_name ?? '') . ' ' .
                    ($studentData->middle_name ?? '') . ' ' .
                    ($studentData->last_name ?? '')
                );
                
                $productId = $academic->product_id ?? $academic->course_subtype_id ?? null;
                $branchId = $academic->branch_id ?? null;
                $sectionId = $academic->section_id ?? '';
                
                $sectionName = $sectionId;
                if (!empty($sectionId) && !empty($productId)) {
                    $sectionName = $this->getSectionDisplayName(
                    $academic->section_id ?? null,
                    $academic->product_id ?? $academic->course_subtype_id ?? null,
                    $institute_id,
                    $academic->branch_id ?? null
                );
                }
                
                $students[$studentHash] = [
                      'student_hash_id' => $studentHash,
                        'student_name' => $fullName,
                        'student_reg' => $studentData->registration_number ?? '',
                        'department' => $academic->department ?? '',
                        'department_id' => $academic->department_id ?? '',
                        'course' => $academic->course_type ?? '',
                        'batch' => $academic->batch ?? '',
                        'academic_year' => $academic->academic_year ?? $academicYearFilter,
                        'semester' => $academic->semester_id ?? '',
                        'section' => $sectionName ?: $sectionId,
                        'mode_type' => $academic->mode_type ?? '',
                        'mode_of_course' => $academic->mode_of_course ?? '',
                        'transport_stop' => null,
                        'installments' => [],
                        'total_original_fee' => 0,
                        'total_discount' => 0,
                        'total_late_fee' => 0,
                        'total_payable' => 0,
                        'total_paid' => 0,
                        'total_pending' => 0,
                        'total_overdue' => 0,
                        'current_installment' => null
                ];
            }
            
            if (!isset($students[$studentHash])) {
                continue;
            }
            
            // Get stop name
            $feeBreakdown = json_decode($fee->transportStopId['fee_breakdown'] ?? '[]', true);
            $selectedStopId = $fee->transport_stop_id;
            $stopName = null;
            
            if (!empty($feeBreakdown)) {
                foreach ($feeBreakdown as $stop) {
                    if ($stop['stop_id'] === $selectedStopId) {
                        $stopName = $stop['stop_name'];
                        break;
                    }
                }
            }
            
            $feeAmount = floatval($fee->transport_fee ?? 0);
            $lateFee = floatval($fee->late_fee_amount ?? 0);
            $discount = floatval($fee->discount_amount ?? 0);
            $payableAmount = $feeAmount + $lateFee - $discount;
            
            $installment = [
                'id' => $fee->id,
                'fee_amount' => $feeAmount,
                'payable_amount' => $payableAmount,
                'late_fee' => $lateFee,
                'discount' => $discount,
                'due_date' => $fee->due_date,
                'pay_date' => $fee->pay_date,
                'payment_type' => $fee->payment_type,
                'payment_status' => $fee->payment_status,
                'fee_duration_type' => $fee->fee_duration_type,
                'transaction_id' => $fee->transaction_id ?? null,
                'transport_stop' => $stopName,
                'transport_stop_id' => $selectedStopId,
                'created_at' => $fee->created_at,
                'updated_at' => $fee->updated_at,
            ];
            
            $students[$studentHash]['installments'][] = $installment;
            
            if ($stopName && !$students[$studentHash]['transport_stop']) {
                $students[$studentHash]['transport_stop'] = $stopName;
            }
            
            $students[$studentHash]['total_original_fee'] += $feeAmount;
            $students[$studentHash]['total_discount'] += $discount;
            $students[$studentHash]['total_late_fee'] += $lateFee;
            $students[$studentHash]['total_payable'] += $payableAmount;
            
            if ($fee->payment_status === 'paid') {
                $students[$studentHash]['total_paid'] += $payableAmount;
            } else {
                $dueDateObj = \Carbon\Carbon::parse($fee->due_date);
                if ($dueDateObj->lt(\Carbon\Carbon::today())) {
                    $students[$studentHash]['total_overdue'] += $payableAmount;
                } else {
                    $students[$studentHash]['total_pending'] += $payableAmount;
                }
            }
        }
        
        // Sort installments and set current installment
        foreach ($students as &$student) {
            usort($student['installments'], function($a, $b) {
                return strtotime($a['due_date']) - strtotime($b['due_date']);
            });
            
            $today = \Carbon\Carbon::today();
            
            foreach ($student['installments'] as &$inst) {
                if ($inst['payment_status'] !== 'paid') {
                    $dueDate = \Carbon\Carbon::parse($inst['due_date']);
                    if ($dueDate->gte($today)) {
                        $student['current_installment'] = $inst;
                        break;
                    }
                }
            }
            
            if (!$student['current_installment']) {
                foreach ($student['installments'] as &$inst) {
                    if ($inst['payment_status'] !== 'paid') {
                        $student['current_installment'] = $inst;
                        break;
                    }
                }
            }
        }
        
        // Apply additional filters
        $filteredStudents = [];
        foreach ($students as $studentHash => $student) {
            $includeStudent = true;
            
            if ($request->filled('department_id')) {
                if (($student['department_id'] ?? '') != $request->department_id) {
                    $includeStudent = false;
                }
            } elseif ($request->filled('department')) {
                if (strcasecmp(trim($student['department']), trim($request->department)) !== 0) {
                    $includeStudent = false;
                }
            }
            
            if ($includeStudent && $request->filled('course') && 
                strcasecmp(trim($student['course']), trim($request->course)) !== 0) {
                $includeStudent = false;
            }
            
            if ($includeStudent && $request->filled('batch') && 
                strcasecmp(trim($student['batch']), trim($request->batch)) !== 0) {
                $includeStudent = false;
            }
            
            if ($includeStudent && $request->filled('mode_type') && 
                strcasecmp(trim($student['mode_type']), trim($request->mode_type)) !== 0) {
                $includeStudent = false;
            }
            
            if ($includeStudent && $request->filled('student_search')) {
                $search = strtolower(trim($request->student_search));
                if (!str_contains(strtolower($student['student_name']), $search) &&
                    !str_contains(strtolower($student['student_reg']), $search)) {
                    $includeStudent = false;
                }
            }
            
            if ($includeStudent && $request->filled('payment_status')) {
                $hasMatchingStatus = false;
                foreach ($student['installments'] as $inst) {
                    $dueDate = \Carbon\Carbon::parse($inst['due_date']);
                    $status = $inst['payment_status'];
                    
                    if ($status !== 'paid') {
                        if ($dueDate->lt(\Carbon\Carbon::today())) {
                            $status = 'overdue';
                        } else {
                            $status = 'pending';
                        }
                    }
                    
                    if ($status === $request->payment_status) {
                        $hasMatchingStatus = true;
                        break;
                    }
                }
                if (!$hasMatchingStatus) {
                    $includeStudent = false;
                }
            }
            
            if ($includeStudent) {
                $filteredStudents[$studentHash] = $student;
            }
        }
        
        // Calculate totals
        $totalPayable = 0;
        $totalPaidCount = 0;
        $totalOverdueCount = 0;
        $totalDiscountAll = 0;
        $totalOriginalAll = 0;
        
        foreach ($filteredStudents as $student) {
            $totalOriginalAll += $student['total_original_fee'];
            $totalDiscountAll += $student['total_discount'];
            $totalPayable += $student['total_payable'];
            
            foreach ($student['installments'] as $inst) {
                if ($inst['payment_status'] === 'paid') {
                    $totalPaidCount++;
                } else {
                    $dueDate = \Carbon\Carbon::parse($inst['due_date']);
                    if ($dueDate->lt(\Carbon\Carbon::today())) {
                        $totalOverdueCount++;
                    }
                }
            }
        }
        
        // Create paginator
        $studentsArray = array_values($filteredStudents);
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = array_slice($studentsArray, ($currentPage - 1) * $perPage, $perPage);
        
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            count($studentsArray),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        $departments = \App\Models\Departments::where('institute_id', $institute_id)->get();
        $courses = array_unique(array_column($studentsArray, 'course'));
        $batches = array_unique(array_column($studentsArray, 'batch'));
        $serviceInstitutedetails = InstituteBasicDetails::where('fincap_merchant_id', $institute_id)->first();
        
        return view('instituteAdmin.AdminFeeStructureFile.TransportFee', compact(
            'paginator', 
            'departments', 
            'courses', 
            'batches',
            'totalPayable', 
            'totalPaidCount', 
            'totalOverdueCount', 
            'totalDiscountAll', 
            'totalOriginalAll',
            'serviceInstitutedetails',
            'academicYears',
            'academicYearFilter' // Pass the current filter to view
        ));
    }

    public function studentTransportFeeInstallments($student_hash)
    {
        $institute_id = auth()->user()->institute_id;
        
        // Get student details
        $studentData = StudentParentDetails::where('student_hash_id', $student_hash)
            ->where('institute_id', $institute_id)
            ->first();
            
        if (!$studentData) {
            abort(404, 'Student not found');
        }
        
        // Get the selected academic year from request
        $academicYearFilter = request('academic_year');
        
        // If no filter, get current academic year
        if (!$academicYearFilter) {
            $academicYearFilter = $this->getCurrentAcademicYear();
        }
        
        // CRITICAL: Get the correct academic_year_id for the selected academic year
        $academicYearId = null;
        $academic = null;
        
        // First, check the promotion logs to see what class the student was in for this year
        $log = StudentAcademicDetailsLog::where('student_hash_id', $student_hash)
            ->where(function($q) use ($academicYearFilter) {
                $q->where('previous_academic_year', $academicYearFilter)
                ->orWhere('new_academic_year', $academicYearFilter);
            })
            ->first();
        
        if ($log) {
            if ($log->previous_academic_year == $academicYearFilter) {
                $academicYearId = $log->previous_academic_year_id;
                $academic = new \stdClass();
                $academic->academic_year_id = $log->previous_academic_year_id;
                $academic->academic_year = $log->previous_academic_year;
                $academic->section_id = $log->previous_section_id;
                $academic->course_subtype_id = $log->previous_course_subtype_id;
                $academic->course_subtype = $log->previous_course_subtype;
                $academic->branch_id = $log->previous_branch_id;
                $academic->course_type = $log->previous_course_type;
                $academic->department = $log->previous_department;
                $academic->batch = $log->previous_batch;
                $academic->batch_id = $log->previous_batch_id;
                $academic->semester_id = $log->previous_semester_id;
            } else {
                $academicYearId = $log->new_academic_year_id;
                $academic = new \stdClass();
                $academic->academic_year_id = $log->new_academic_year_id;
                $academic->academic_year = $log->new_academic_year;
                $academic->section_id = $log->new_section_id;
                $academic->course_subtype_id = $log->new_course_subtype_id;
                $academic->course_subtype = $log->new_course_subtype;
                $academic->branch_id = $log->new_branch_id;
                $academic->course_type = $log->new_course_type;
                $academic->department = $log->new_department;
                $academic->batch = $log->new_batch;
                $academic->batch_id = $log->new_batch_id;
                $academic->semester_id = $log->new_semester_id;
            }
        }
        
        // If not found in logs, check transport details
        if (!$academicYearId) {
            $transportDetail = StudentAcademicTransportDetails::where('student_hash_id', $student_hash)
                ->where('academic_year', $academicYearFilter)
                ->first();
            
            if ($transportDetail) {
                $academicYearId = $transportDetail->academic_year_id;
                $academic = $transportDetail;
            }
        }
        
        // If still not found, try to get the current academic record
        if (!$academicYearId) {
            $currentAcademic = StudentAcademicTransportDetails::where('student_hash_id', $student_hash)
                ->where('institute_id', $institute_id)
                ->first();
            if ($currentAcademic) {
                $academicYearId = $currentAcademic->academic_year_id;
                $academic = $currentAcademic;
            }
        }
        
        
        
        // Get transport fee installments using the CORRECT academic_year_id
        $installments = StudentTransportFeeStructure::where('student_hash_id', $student_hash)
            ->where('institute_id', $institute_id);
        
        if ($academicYearId) {
            // Use the exact academic_year_id we found
            $installments = $installments->where('academic_year_id', $academicYearId);
        } else {
            // Fallback: filter by academic year string
            $installments = $installments->where('academic_year_id', 'LIKE', '%' . $academicYearFilter . '%');
        }
        
        $installments = $installments->with(['transportStopId'])->orderBy('due_date')->get();
        
        // If no installments found and we have a valid academic object, try to create them
        if ($installments->isEmpty() && $academic && isset($academic->batch_id)) {
            \Log::info('No installments found, attempting to create from fee structure');
            $this->createStudentTransportInstallmentsFromStructure($student_hash, $academic, $institute_id);
            
            // Fetch again after creation
            $installments = StudentTransportFeeStructure::where('student_hash_id', $student_hash)
                ->where('institute_id', $institute_id)
                ->where('academic_year_id', $academicYearId)
                ->with(['transportStopId'])
                ->orderBy('due_date')
                ->get();
        }
        
        // Calculate summary statistics
        $summary = $this->calculateTransportInstallmentSummary($installments);
        
        // Get institute details for receipt
        $serviceInstitutedetails = InstituteBasicDetails::where('fincap_merchant_id', $institute_id)->first();
        
        // Get section name
        $productIdForSection = $academic->course_subtype_id ?? null;
        $sectionName = $this->getSectionDisplayName(
            $academic->section_id ?? null,
            $productIdForSection,
            $institute_id,
            $academic->branch_id ?? null
        );
        
        return view('instituteAdmin.AdminFeeStructureFile.StudentTransportFeeInstallments', compact(
            'studentData', 
            'academic', 
            'installments', 
            'summary', 
            'serviceInstitutedetails', 
            'sectionName', 
            'academicYearFilter'
        ));
    }

    /**
    * Create transport fee installments from structure
    */
    private function createStudentTransportInstallmentsFromStructure($student_hash, $academic, $institute_id)
    {
        try {
            // Get the transportation fee structure for this batch and academic year
            $transportFeeStructure = DB::table('transportation_fees')
                ->where('institute_id', $institute_id)
                ->where('batch_id', $academic->batch_id)
                ->where('academic_year_id', $academic->academic_year_id)
                ->first();
            
            if (!$transportFeeStructure) {
                \Log::warning('Transport fee structure not found', [
                    'student_hash' => $student_hash,
                    'batch_id' => $academic->batch_id,
                    'academic_year_id' => $academic->academic_year_id
                ]);
                return;
            }
            
            // Decode the fee structure
            $feeStructure = json_decode($transportFeeStructure->fee_structure, true);
            
            if (!is_array($feeStructure) || empty($feeStructure)) {
                \Log::warning('Invalid transport fee structure', ['student_hash' => $student_hash]);
                return;
            }
            
            // Get the student's transport stop from academic details
            $transportStopId = $academic->transport_stop_id ?? null;
            
            // Create installments for each period
            foreach ($feeStructure as $index => $payment) {
                $amount = $payment['amount'] ?? 0;
                $dueDate = $payment['due_date'] ?? null;
                $stopId = $payment['stop_id'] ?? $transportStopId;
                
                if (!$dueDate) {
                    $baseDate = Carbon::now();
                    $dueDate = $baseDate->addMonths($index)->format('Y-m-d');
                }
                
                // Check if installment already exists for this due date to avoid duplicates
                $existing = StudentTransportFeeStructure::where('student_hash_id', $student_hash)
                    ->where('institute_id', $institute_id)
                    ->where('academic_year_id', $academic->academic_year_id)
                    ->where('due_date', $dueDate)
                    ->first();
                
                if (!$existing) {
                    StudentTransportFeeStructure::create([
                        'institute_id' => $institute_id,
                        'student_hash_id' => $student_hash,
                        'transport_fee' => $amount,
                        'due_date' => $dueDate,
                        'academic_year_id' => $academic->academic_year_id,
                        'transport_stop_id' => $stopId,
                        'fee_duration_type' => $payment['duration_type'] ?? 'quarterly',
                        'payment_status' => 'pending'
                    ]);
                }
            }
            
            \Log::info('Created transport fee installments for student', [
                'student_hash' => $student_hash,
                'academic_year' => $academic->academic_year,
                'installments_created' => count($feeStructure)
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to create transport fee installments', [
                'student_hash' => $student_hash,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Calculate transport fee installment summary
     */
    private function calculateTransportInstallmentSummary($installments)
    {
        $totalFee = 0;
        $totalPaid = 0;
        $totalPending = 0;
        $paidCount = 0;
        $pendingCount = 0;
        $totalLateFee = 0;
        $totalDiscount = 0;
        $totalOriginalFee = 0;
        
        foreach ($installments as $inst) {
            $feeAmount = $inst->transport_fee ?? 0;
            $lateFee = $inst->late_fee_amount ?? 0;
            $discount = $inst->discount_amount ?? 0;
            $payable = $feeAmount + $lateFee - $discount;
            
            $totalOriginalFee += $feeAmount;
            $totalLateFee += $lateFee;
            $totalDiscount += $discount;
            $totalFee += $payable;
            
            // Determine status based on due date if not paid
            $status = $inst->payment_status;
            if ($status !== 'paid') {
                $dueDate = \Carbon\Carbon::parse($inst->due_date);
                if ($dueDate->lt(\Carbon\Carbon::today())) {
                    $status = 'overdue';
                } else {
                    $status = 'pending';
                }
            }
            
            if ($status === 'paid') {
                $totalPaid += $payable;
                $paidCount++;
            } else {
                $totalPending += $payable;
                $pendingCount++;
            }
        }
        
        // Calculate monthly values (assuming all months have same fee structure)
        $monthlyOriginalFee = $installments->first()->transport_fee ?? 0;
        $monthlyDiscount = $installments->first()->discount_amount ?? 0;
        $monthlyPayable = $monthlyOriginalFee - $monthlyDiscount;
        
        return [
            'total_installments' => $installments->count(),
            'total_original_fee' => $totalOriginalFee,
            'total_fee' => $totalFee,
            'total_paid' => $totalPaid,
            'total_pending' => $totalPending,
            'paid_count' => $paidCount,
            'pending_count' => $pendingCount,
            'total_late_fee' => $totalLateFee,
            'total_discount' => $totalDiscount,
            'monthly_original_fee' => $monthlyOriginalFee,
            'monthly_discount' => $monthlyDiscount,
            'monthly_payable' => $monthlyPayable,
            'completion_percentage' => $installments->count() > 0 ? ($paidCount / $installments->count()) * 100 : 0,
        ];
    }
    
    /**
     * Get Registration Fee Structure - FIXED for duplicates
     */
    public function getRegistrationFeeStructure(Request $request)
    {
        $institute_id = auth()->user()->institute_id;
        $perPage = $request->get('per_page', 10);

        // Get the selected academic year from request
        $academicYearFilter = $request->get('academic_year');

        // Set default academic year if not provided
        if (!$academicYearFilter) {
            $academicYearFilter = $this->getCurrentAcademicYear();
        }

        if ($perPage === 'all') {
            $perPage = 100;
        } else {
            $perPage = (int) $perPage;
        }
        // ============ GET ACADEMIC YEAR IDs FOR THE SELECTED YEAR ============
        $academicYearIds = [];

        // Get from transport details
        $transportRecords = StudentAcademicTransportDetails::where('institute_id', $institute_id)
            ->where('academic_year', $academicYearFilter)
            ->select('academic_year_id')
            ->distinct()
            ->get();

        foreach ($transportRecords as $record) {
            if (!empty($record->academic_year_id)) {
                $academicYearIds[] = $record->academic_year_id;
            }
        }

        // Get from promotion logs
        $logRecords = StudentAcademicDetailsLog::where('institute_id', $institute_id)
            ->where(function ($q) use ($academicYearFilter) {
                $q->where('previous_academic_year', $academicYearFilter)
                    ->orWhere('new_academic_year', $academicYearFilter);
            })
            ->get();

        foreach ($logRecords as $log) {
            if ($log->previous_academic_year == $academicYearFilter && !empty($log->previous_academic_year_id)) {
                $academicYearIds[] = $log->previous_academic_year_id;
            }
            if ($log->new_academic_year == $academicYearFilter && !empty($log->new_academic_year_id)) {
                $academicYearIds[] = $log->new_academic_year_id;
            }
        }

        $academicYearIds = array_unique(array_filter($academicYearIds));

        // If no academic year IDs found, return empty
        if (empty($academicYearIds)) {
            return $this->getEmptyRegistrationFeeResponse($institute_id, $perPage, $academicYearFilter);
        }

        // ============ GET REGISTRATION FEE RECORDS ============
        // Use distinct on student_hash_id to prevent duplicates
        $allFeeRecords = StudentRegistrationFeeStructure::where('institute_id', $institute_id)
            ->whereIn('academic_year_id', $academicYearIds)
            ->orderBy('due_date')
            ->get()
            ->unique('student_hash_id'); // THIS PREVENTS DUPLICATE STUDENTS

        // Group by student
        $students = [];

        foreach ($allFeeRecords as $fee) {
            $studentHash = $fee->student_hash_id;

            // Get student details
            $studentData = StudentParentDetails::where('student_hash_id', $studentHash)->first();
            if (!$studentData) continue;

            // Get academic details based on the FEE RECORD'S academic_year_id
            $academicData = $this->getStudentAcademicByAcademicYearId(
                $studentHash,
                $fee->academic_year_id,
                $institute_id
            );

            $fullName = trim(
                ($studentData->first_name ?? '') . ' ' .
                ($studentData->middle_name ?? '') . ' ' .
                ($studentData->last_name ?? '')
            );

            // Get proper section name
            $productId = $academicData->product_id ?? $academicData->course_subtype_id ?? null;
            $branchId = $academicData->branch_id ?? null;
            $sectionId = $academicData->section_id ?? '';

            $sectionName = $sectionId;
            if (!empty($sectionId) && !empty($productId)) {
                $sectionName = $this->getSectionDisplayName($sectionId, $productId, $institute_id, $branchId);
            }

            // Get all installments for this student (not just one)
            $allStudentFees = StudentRegistrationFeeStructure::where('institute_id', $institute_id)
                ->where('student_hash_id', $studentHash)
                ->whereIn('academic_year_id', $academicYearIds)
                ->orderBy('due_date')
                ->get();

            $installments = [];
            $totalPaid = 0;
            $totalPending = 0;
            $totalOverdue = 0;
            $totalDiscount = 0;
            $totalLateFee = 0;
            $totalOriginalFee = 0;
            $totalPayable = 0;

            foreach ($allStudentFees as $instFee) {
                $feeAmount = floatval($instFee->registration_fee ?? 0);
                $lateFee = floatval($instFee->late_fee_amount ?? 0);
                $discount = floatval($instFee->discount_amount ?? 0);
                $payableAmount = $feeAmount + $lateFee - $discount;

                $installments[] = [
                    'id'               => $instFee->id,
                    'fee_amount'       => $feeAmount,
                    'payable_amount'   => $payableAmount,
                    'late_fee'         => $lateFee,
                    'discount'         => $discount,
                    'due_date'         => $instFee->due_date,
                    'pay_date'         => $instFee->pay_date,
                    'payment_type'     => $instFee->payment_type,
                    'payment_status'   => $instFee->payment_status,
                    'fee_duration_type'=> $instFee->fee_duration_type,
                    'transaction_id'   => $instFee->transaction_id ?? null,
                    'fee_reference_id' => $instFee->fee_reference_id ?? null,
                    'created_at'       => $instFee->created_at,
                    'updated_at'       => $instFee->updated_at,
                ];

                $totalOriginalFee += $feeAmount;
                $totalDiscount += $discount;
                $totalLateFee += $lateFee;
                $totalPayable += $payableAmount;

                if ($instFee->payment_status === 'paid') {
                    $totalPaid += $payableAmount;
                } else {
                    $dueDate = \Carbon\Carbon::parse($instFee->due_date);
                    if ($dueDate->lt(\Carbon\Carbon::today())) {
                        $totalOverdue += $payableAmount;
                    } else {
                        $totalPending += $payableAmount;
                    }
                }
            }

            // Sort installments
            usort($installments, function($a, $b) {
                return strtotime($a['due_date']) - strtotime($b['due_date']);
            });

            // Find current installment
            $currentInstallment = null;
            foreach ($installments as &$inst) {
                if ($inst['payment_status'] !== 'paid') {
                    $currentInstallment = $inst;
                    break;
                }
            }

            $students[$studentHash] = [
                'student_hash_id' => $studentHash,
                'student_name'    => $fullName,
                'student_reg'     => $studentData->registration_number ?? '',
                'department'      => $academicData->department ?? '',
                'department_id'   => $academicData->department_id ?? '',
                'course'          => $academicData->course_type ?? '',
                'branch'          => $academicData->course_subtype ?? '',
                'batch'           => $academicData->batch ?? '',
                'academic_year'   => $academicData->academic_year ?? $academicYearFilter,
                'semester'        => $academicData->semester_id ?? '',
                'section'         => $sectionName ?: $sectionId,
                'section_id'      => $sectionId,
                'mode_type'       => $academicData->mode_type ?? '',
                'mode_of_course'  => $academicData->mode_of_course ?? '',
                'installments'    => $installments,
                'total_paid'      => $totalPaid,
                'total_pending'   => $totalPending,
                'total_overdue'   => $totalOverdue,
                'total_discount'  => $totalDiscount,
                'total_late_fee'  => $totalLateFee,
                'total_original_fee' => $totalOriginalFee,
                'total_payable'   => $totalPayable,
                'current_installment' => $currentInstallment
            ];
        }

        // Apply filters
        if ($request->filled('student_search')) {
            $search = strtolower($request->student_search);
            $students = array_filter($students, function($student) use ($search) {
                return strpos(strtolower($student['student_name']), $search) !== false ||
                    strpos(strtolower($student['student_reg']), $search) !== false;
            });
        }

        if ($request->filled('department')) {
            $students = array_filter($students, function($student) use ($request) {
                return strcasecmp($student['department'], $request->department) === 0;
            });
        }

        if ($request->filled('course')) {
            $students = array_filter($students, function($student) use ($request) {
                return strcasecmp($student['course'], $request->course) === 0;
            });
        }

        if ($request->filled('batch')) {
            $students = array_filter($students, function($student) use ($request) {
                return strcasecmp($student['batch'], $request->batch) === 0;
            });
        }

        if ($request->filled('mode_type')) {
            $students = array_filter($students, function($student) use ($request) {
                return strcasecmp($student['mode_type'], $request->mode_type) === 0;
            });
        }

        // Paginate
        $studentsArray = array_values($students);
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPageValue = (int) $request->get('per_page', 10);
        if ($request->get('per_page') === 'all') {
            $perPageValue = count($studentsArray);
        }
        $currentPageItems = array_slice($studentsArray, ($currentPage - 1) * $perPageValue, $perPageValue);

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            count($studentsArray),
            $perPageValue,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Calculate totals
        $totalPayable = 0;
        $totalPaidCount = 0;
        $totalOverdueCount = 0;

        foreach ($studentsArray as $student) {
            $totalPayable += $student['total_payable'];
            foreach ($student['installments'] as $inst) {
                if ($inst['payment_status'] === 'paid') {
                    $totalPaidCount++;
                } elseif ($inst['payment_status'] !== 'paid' && strtotime($inst['due_date']) < strtotime(now())) {
                    $totalOverdueCount++;
                }
            }
        }

        // Get academic years for filter dropdown
        $transportYears = StudentAcademicTransportDetails::where('institute_id', $institute_id)
            ->distinct()
            ->pluck('academic_year');

        $logYears = StudentAcademicDetailsLog::where('institute_id', $institute_id)
            ->distinct()
            ->pluck('previous_academic_year')
            ->merge(
                StudentAcademicDetailsLog::where('institute_id', $institute_id)
                    ->distinct()
                    ->pluck('new_academic_year')
            )
            ->unique()
            ->filter()
            ->values();
        $academicYears = $transportYears->merge($logYears)->unique()->sortDesc()->values();

        // Get filter data
        $departments = \App\Models\Departments::where('institute_id', $institute_id)->get();
        $courses = array_unique(array_column($studentsArray, 'course'));
        $batches = array_unique(array_column($studentsArray, 'batch'));
        $serviceInstitutedetails = InstituteBasicDetails::where('fincap_merchant_id', $institute_id)->first();

        return view('instituteAdmin.AdminFeeStructureFile.RegistrationFeeStructure', compact(
            'paginator',
            'departments',
            'courses',
            'batches',
            'totalPayable',
            'totalPaidCount',
            'totalOverdueCount',
            'academicYears',
            'serviceInstitutedetails',
            'academicYearFilter'
        ));
    }
    
    /**
     * Get student academic details by academic_year_id (NOT by academic year string)
     */
    private function getStudentAcademicByAcademicYearId($studentHash, $academicYearId, $institute_id)
    {
        // First, try to get from transport details using academic_year_id
        $academic = StudentAcademicTransportDetails::where('student_hash_id', $studentHash)
            ->where('academic_year_id', $academicYearId)
            ->first();

        if ($academic) {
            return $academic;
        }

        // If not found, try to get from logs using academic_year_id
        $log = StudentAcademicDetailsLog::where('student_hash_id', $studentHash)
            ->where(function($q) use ($academicYearId) {
                $q->where('previous_academic_year_id', $academicYearId)
                    ->orWhere('new_academic_year_id', $academicYearId);
            })
            ->first();

        if ($log) {
            $result = new \stdClass();

            if ($log->previous_academic_year_id == $academicYearId) {
                $result->department = $log->previous_department;
                $result->department_id = $log->previous_department_id;
                $result->course_type = $log->previous_course_type;
                $result->course_subtype = $log->previous_course_subtype;
                $result->course_subtype_id = $log->previous_course_subtype_id;
                $result->product_id = $log->previous_course_subtype_id;
                $result->batch = $log->previous_batch;
                $result->batch_id = $log->previous_batch_id;
                $result->academic_year = $log->previous_academic_year;
                $result->academic_year_id = $log->previous_academic_year_id;
                $result->section_id = $log->previous_section_id;
                $result->mode_type = $log->previous_mode_type;
                $result->mode_of_course = $log->previous_mode_of_course;
                $result->semester_id = $log->previous_semester_id;
                $result->branch_id = $log->previous_branch_id;
            } else {
                $result->department = $log->new_department;
                $result->department_id = $log->new_department_id;
                $result->course_type = $log->new_course_type;
                $result->course_subtype = $log->new_course_subtype;
                $result->course_subtype_id = $log->new_course_subtype_id;
                $result->product_id = $log->new_course_subtype_id;
                $result->batch = $log->new_batch;
                $result->batch_id = $log->new_batch_id;
                $result->academic_year = $log->new_academic_year;
                $result->academic_year_id = $log->new_academic_year_id;
                $result->section_id = $log->new_section_id;
                $result->mode_type = $log->new_mode_type;
                $result->mode_of_course = $log->new_mode_of_course;
                $result->semester_id = $log->new_semester_id;
                $result->branch_id = $log->new_branch_id;
            }

            return $result;
        }

        // Fallback: try to get from course_fee_structures
        $feeStructure = DB::table('course_fee_structures')
            ->where('academic_year_id', $academicYearId)
            ->where('institute_id', $institute_id)
            ->first();

        if ($feeStructure) {
            $result = new \stdClass();
            $result->batch = $feeStructure->batch;
            $result->batch_id = $feeStructure->batch_id;
            $result->section_id = $feeStructure->section_id;
            $result->semester_id = $feeStructure->semester_id;
            $result->academic_year_id = $academicYearId;

            // Try to get product details
            $productDetails = DB::table('product_details')
                ->where('product_id', $feeStructure->product_id)
                ->first();

            if ($productDetails) {
                $result->department = $productDetails->department_name;
                $result->department_id = $productDetails->department_id;
                $result->course_type = $productDetails->course_type;
                $result->course_subtype = $productDetails->sub_type;
                $result->product_id = $productDetails->product_id;
                $result->mode_type = $productDetails->mode_type;
                $result->mode_of_course = $productDetails->mode_of_course;
            }

            return $result;
        }

        // Ultimate fallback: return empty object
        return new \stdClass();
    }
    
    /**
     * Student Registration Fee Installments Page
     */
    public function studentRegistrationInstallments($student_hash)
    {
        $institute_id = auth()->user()->institute_id;

        // Get student details
        $studentData = StudentParentDetails::where('student_hash_id', $student_hash)
            ->where('institute_id', $institute_id)
            ->first();

        if (!$studentData) {
            abort(404, 'Student not found');
        }

        // Get the selected academic year from request
        $academicYearFilter = request('academic_year');

        // If no filter, get current academic year
        if (!$academicYearFilter) {
            $academicYearFilter = $this->getCurrentAcademicYear();
        }

        // Get academic details for the SELECTED academic year
        $academic = $this->getStudentAcademicForYear($student_hash, $academicYearFilter, $institute_id);

        // If no academic found for selected year, try to get from promotion logs
        if (!$academic || !isset($academic->academic_year_id)) {
            $log = StudentAcademicDetailsLog::where('student_hash_id', $student_hash)
                ->where(function($q) use ($academicYearFilter) {
                    $q->where('previous_academic_year', $academicYearFilter)
                    ->orWhere('new_academic_year', $academicYearFilter);
                })
                ->first();

            if ($log) {
                $academic = new \stdClass();
                if ($log->previous_academic_year == $academicYearFilter) {
                    $academic->academic_year_id = $log->previous_academic_year_id;
                    $academic->academic_year = $log->previous_academic_year;
                    $academic->section_id = $log->previous_section_id;
                    $academic->course_subtype_id = $log->previous_course_subtype_id;
                    $academic->course_subtype = $log->previous_course_subtype;
                    $academic->product_id = $log->previous_course_subtype_id;
                    $academic->branch_id = $log->previous_branch_id;
                    $academic->course_type = $log->previous_course_type;
                    $academic->department = $log->previous_department;
                    $academic->batch = $log->previous_batch;
                    $academic->batch_id = $log->previous_batch_id;
                    $academic->semester_id = $log->previous_semester_id;
                } else {
                    $academic->academic_year_id = $log->new_academic_year_id;
                    $academic->academic_year = $log->new_academic_year;
                    $academic->section_id = $log->new_section_id;
                    $academic->course_subtype_id = $log->new_course_subtype_id;
                    $academic->course_subtype = $log->new_course_subtype;
                    $academic->product_id = $log->new_course_subtype_id;
                    $academic->branch_id = $log->new_branch_id;
                    $academic->course_type = $log->new_course_type;
                    $academic->department = $log->new_department;
                    $academic->batch = $log->new_batch;
                    $academic->batch_id = $log->new_batch_id;
                    $academic->semester_id = $log->new_semester_id;
                }
            }
        }

        // If still no academic found, try to get current record
        if (!$academic || !isset($academic->academic_year_id)) {
            $academic = StudentAcademicTransportDetails::where('student_hash_id', $student_hash)
                ->where('institute_id', $institute_id)
                ->first();
        }

        // Get registration fee installments for the SPECIFIC academic year
        $installments = StudentRegistrationFeeStructure::where('student_hash_id', $student_hash)
            ->where('institute_id', $institute_id);

        if ($academic && isset($academic->academic_year_id) && $academic->academic_year_id) {
            $installments = $installments->where('academic_year_id', $academic->academic_year_id);
        } else {
            // Fallback: filter by academic year string
            $installments = $installments->where('academic_year_id', 'LIKE', '%' . $academicYearFilter . '%');
        }

        $installments = $installments->orderBy('due_date')->get();

        // Calculate summary statistics
        $summary = $this->calculateRegistrationInstallmentSummary($installments);

        // Get previous academic year installments (for historical view)
        $previousInstallments = collect();
        if ($academic && isset($academic->academic_year_id)) {
            $previousInstallments = StudentRegistrationFeeStructure::where('student_hash_id', $student_hash)
                ->where('institute_id', $institute_id)
                ->where('academic_year_id', '!=', $academic->academic_year_id)
                ->orderBy('due_date')
                ->get();
        }

        $previousSummary = $this->calculateRegistrationInstallmentSummary($previousInstallments);

        // Get institute details for receipt
        $serviceInstitutedetails = InstituteBasicDetails::where('fincap_merchant_id', $institute_id)->first();

        // Get section name
        $productIdForSection = $academic->course_subtype_id ?? $academic->product_id ?? null;
        $sectionName = $this->getSectionDisplayName(
            $academic->section_id ?? null,
            $productIdForSection,
            $institute_id,
            $academic->branch_id ?? null
        );

        // Calculate payment progress
        $totalInstallments = $installments->count();
        $paidInstallments = $installments->where('payment_status', 'paid')->count();
        $pendingInstallments = $installments->where('payment_status', '!=', 'paid')->count();
        $overdueInstallments = $installments->where('payment_status', '!=', 'paid')
            ->filter(function($inst) {
                return $inst->due_date && \Carbon\Carbon::parse($inst->due_date)->lt(\Carbon\Carbon::today());
            })->count();

        $paymentProgressPercentage = $totalInstallments > 0
            ? ($paidInstallments / $totalInstallments) * 100
            : 0;

        $totalRegistrationFee = $installments->sum('registration_fee');
        $totalPaidRegistrationFee = $installments->where('payment_status', 'paid')->sum(function($inst) {
            return ($inst->registration_fee ?? 0) + ($inst->late_fee_amount ?? 0) - ($inst->discount_amount ?? 0);
        });
        $totalPendingRegistrationFee = $summary['total_fee'] - $summary['total_paid'];

        // Get promotion history
        $promotionHistory = StudentAcademicDetailsLog::where('student_hash_id', $student_hash)
            ->orderBy('promoted_at', 'desc')
            ->get();

        // Transform installments to include all data needed for receipts
        $installmentsForJS = $installments->map(function($inst) {
            return [
                'id' => $inst->id,
                'registration_fee' => $inst->registration_fee,
                'late_fee_amount' => $inst->late_fee_amount,
                'discount_amount' => $inst->discount_amount,
                'due_date' => $inst->due_date,
                'pay_date' => $inst->pay_date,
                'payment_status' => $inst->payment_status,
                'payment_type' => $inst->payment_type,
                'transaction_id' => $inst->transaction_id,
                'fee_reference_id' => $inst->fee_reference_id,
                'fee_duration_type' => $inst->fee_duration_type,
            ];
        });

        return view('instituteAdmin.AdminFeeStructureFile.StudentRegistrationInstallments', compact(
            'studentData',
            'academic',
            'installments',
            'installmentsForJS',
            'summary',
            'previousInstallments',
            'previousSummary',
            'serviceInstitutedetails',
            'sectionName',
            'academicYearFilter',
            'promotionHistory',
            'totalInstallments',
            'paidInstallments',
            'pendingInstallments',
            'overdueInstallments',
            'paymentProgressPercentage',
            'totalRegistrationFee',
            'totalPaidRegistrationFee',
            'totalPendingRegistrationFee'
        ));
    }
    
    /**
     * Calculate registration fee installment summary
     */
    private function calculateRegistrationInstallmentSummary($installments)
    {
        if ($installments->isEmpty()) {
            return [
                'total_installments' => 0,
                'total_fee' => 0,
                'total_paid' => 0,
                'total_pending' => 0,
                'paid_count' => 0,
                'pending_count' => 0,
                'total_late_fee' => 0,
                'total_discount' => 0,
                'completion_percentage' => 0,
            ];
        }

        $totalFee = 0;
        $totalPaid = 0;
        $totalPending = 0;
        $paidCount = 0;
        $pendingCount = 0;
        $totalLateFee = 0;
        $totalDiscount = 0;

        foreach ($installments as $inst) {
            $feeAmount = $inst->registration_fee ?? 0;
            $lateFee = $inst->late_fee_amount ?? 0;
            $discount = $inst->discount_amount ?? 0;
            $payable = $feeAmount + $lateFee - $discount;

            $totalFee += $payable;
            $totalLateFee += $lateFee;
            $totalDiscount += $discount;

            if ($inst->payment_status === 'paid') {
                $totalPaid += $payable;
                $paidCount++;
            } else {
                $totalPending += $payable;
                $pendingCount++;
            }
        }

        return [
            'total_installments' => $installments->count(),
            'total_fee' => $totalFee,
            'total_paid' => $totalPaid,
            'total_pending' => $totalPending,
            'paid_count' => $paidCount,
            'pending_count' => $pendingCount,
            'total_late_fee' => $totalLateFee,
            'total_discount' => $totalDiscount,
            'completion_percentage' => $installments->count() > 0 ? ($paidCount / $installments->count()) * 100 : 0,
        ];
    }

    public function getMiscellaneousFeeStructure()
    {
        $institute_id = auth()->user()->institute_id;
        $perPage = request('per_page', 10);
        if ($perPage === 'all') {
            $perPage = 100;
        } else {
            $perPage = (int) $perPage;
        }

        $academicYearFilter = request('academic_year');
        
        // Set default academic year if not provided
        if (!$academicYearFilter) {
            $academicYearFilter = $this->getCurrentAcademicYear();
        }

        // ============ FIRST, GET STUDENTS FOR THE SELECTED ACADEMIC YEAR ============
        $studentHashIds = [];
        $studentAcademicMap = []; // Store academic details for each student
        
        // Get students from transport details for this academic year
        $transportStudents = StudentAcademicTransportDetails::where('institute_id', $institute_id)
            ->where('academic_year', $academicYearFilter)
            ->get();
        
        foreach ($transportStudents as $student) {
            $studentHashIds[] = $student->student_hash_id;
            $studentAcademicMap[$student->student_hash_id] = $student;
        }
        
        // Also get students from promotion logs for this academic year
        $logStudents = StudentAcademicDetailsLog::where('institute_id', $institute_id)
            ->where(function($q) use ($academicYearFilter) {
                $q->where('previous_academic_year', $academicYearFilter)
                ->orWhere('new_academic_year', $academicYearFilter);
            })
            ->get();
        
        foreach ($logStudents as $log) {
            if (!in_array($log->student_hash_id, $studentHashIds)) {
                $studentHashIds[] = $log->student_hash_id;
            }
            
            // Store academic details from log
            if (!isset($studentAcademicMap[$log->student_hash_id])) {
                $academicData = new \stdClass();
                if ($log->previous_academic_year == $academicYearFilter) {
                    $academicData->department = $log->previous_department;
                    $academicData->department_id = $log->previous_department_id;
                    $academicData->course_type = $log->previous_course_type;
                    $academicData->course_subtype = $log->previous_course_subtype;
                    $academicData->batch = $log->previous_batch;
                    $academicData->batch_id = $log->previous_batch_id;
                    $academicData->academic_year = $log->previous_academic_year;
                    $academicData->academic_year_id = $log->previous_academic_year_id;
                    $academicData->product_id = $log->previous_course_subtype_id;
                    $academicData->section_id = $log->previous_section_id;
                    $academicData->mode_type = $log->previous_mode_type;
                    $academicData->mode_of_course = $log->previous_mode_of_course;
                    $academicData->semester_id = $log->previous_semester_id;
                    $academicData->branch_id = $log->previous_branch_id;
                } else {
                    $academicData->department = $log->new_department;
                    $academicData->department_id = $log->new_department_id;
                    $academicData->course_type = $log->new_course_type;
                    $academicData->course_subtype = $log->new_course_subtype;
                    $academicData->batch = $log->new_batch;
                    $academicData->batch_id = $log->new_batch_id;
                    $academicData->academic_year = $log->new_academic_year;
                    $academicData->academic_year_id = $log->new_academic_year_id;
                    $academicData->product_id = $log->new_course_subtype_id;
                    $academicData->section_id = $log->new_section_id;
                    $academicData->mode_type = $log->new_mode_type;
                    $academicData->mode_of_course = $log->new_mode_of_course;
                    $academicData->semester_id = $log->new_semester_id;
                    $academicData->branch_id = $log->new_branch_id;
                }
                $studentAcademicMap[$log->student_hash_id] = $academicData;
            }
        }
        
        // ============ GET CUSTOM FEE RECORDS ============
        $feeQuery = StudentCustomFeestructure::with(['studentDetail'])
            ->where('institute_id', $institute_id);
        
        if (!empty($studentHashIds)) {
            $feeQuery->whereIn('student_hash_id', $studentHashIds);
        }
        
        // Apply academic year filter
        $academicYearIds = [];

        // From current academic records
        $transportRecords = StudentAcademicTransportDetails::where('institute_id', $institute_id)
            ->where('academic_year', $academicYearFilter)
            ->select('academic_year_id')
            ->distinct()
            ->get();

        foreach ($transportRecords as $record) {
            if (!empty($record->academic_year_id)) {
                $academicYearIds[] = $record->academic_year_id;
            }
        }

        // From promotion logs
        $logRecords = StudentAcademicDetailsLog::where('institute_id', $institute_id)
            ->where(function ($q) use ($academicYearFilter) {
                $q->where('previous_academic_year', $academicYearFilter)
                ->orWhere('new_academic_year', $academicYearFilter);
            })
            ->get();

        foreach ($logRecords as $log) {
            if ($log->previous_academic_year == $academicYearFilter && !empty($log->previous_academic_year_id)) {
                $academicYearIds[] = $log->previous_academic_year_id;
            }
            if ($log->new_academic_year == $academicYearFilter && !empty($log->new_academic_year_id)) {
                $academicYearIds[] = $log->new_academic_year_id;
            }
        }

        $academicYearIds = array_unique(array_filter($academicYearIds));

        if (!empty($academicYearIds)) {
            $feeQuery->whereIn('academic_year_id', $academicYearIds);
        }
        
        // Apply other filters
        if (request('start_date')) {
            $feeQuery->whereDate('due_date', '>=', request('start_date'));
        }
        if (request('end_date')) {
            $feeQuery->whereDate('due_date', '<=', request('end_date'));
        }
        if (request('payment_type')) {
            $feeQuery->where('payment_type', request('payment_type'));
        }
        if (request('custom_fee_type')) {
            $feeQuery->where('custom_fee_key', 'like', '%' . request('custom_fee_type') . '%');
        }
        if (request('payment_status')) {
            if (request('payment_status') === 'paid') {
                $feeQuery->where('payment_status', 'paid');
            } elseif (request('payment_status') === 'pending') {
                $feeQuery->where('payment_status', '!=', 'paid')->whereDate('due_date', '>=', now());
            } elseif (request('payment_status') === 'overdue') {
                $feeQuery->where('payment_status', '!=', 'paid')->whereDate('due_date', '<', now());
            }
        }
        
        $allFeeRecords = $feeQuery->orderBy('due_date')->get();
        
        // Group by student
        $students = [];
        $processedFeeKeys = [];
        
        foreach ($allFeeRecords as $fee) {
            $student = $fee->studentDetail;
            if (!$student) continue;
            
            $studentHash = $fee->student_hash_id;
            
            // Get academic data from our map (don't overwrite)
            $academicData = $studentAcademicMap[$studentHash] ?? null;
            
            // If no academic data found, try to get from student's current record using helper
            if (!$academicData) {
                $academicData = $this->getStudentAcademicForYear($studentHash, $academicYearFilter, $institute_id);
            }
            
            $feeType = $fee->custom_fee_key ?? 'Other';
            $uniqueKey = $studentHash . '_' . $feeType . '_' . $fee->due_date;
            
            if (in_array($uniqueKey, $processedFeeKeys)) {
                continue;
            }
            $processedFeeKeys[] = $uniqueKey;
            
            $feeAmount = floatval($fee->custom_fee_value ?? 0);
            $lateFee = floatval($fee->late_fee_amount ?? 0);
            $discount = floatval($fee->discount_amount ?? 0);
            $payableAmount = $feeAmount + $lateFee - $discount;
            
            $status = $fee->payment_status;
            if ($status !== 'paid' && strtotime($fee->due_date) < strtotime(now())) {
                $status = 'overdue';
            } elseif ($status !== 'paid') {
                $status = 'pending';
            }
            
            // Calculate section name ONCE when creating student array
            if (!isset($students[$studentHash])) {
                // Try to get product_id from: fee record > academic data > current student transport
                $productIdForSection = $fee->product_id ?? $academicData->product_id ?? null;
                
                // If still no product_id, try to get from student's current academic record
                if (!$productIdForSection) {
                    $currentStudentAcademic = StudentAcademicTransportDetails::where('student_hash_id', $studentHash)
                        ->where('institute_id', $institute_id)
                        ->first();
                    $productIdForSection = $currentStudentAcademic->course_subtype_id ?? null;
                }
                
                $sectionName = $academicData->section_display_name ?? $this->getSectionDisplayName(
                    $academicData->section_id ?? null,
                    $productIdForSection,
                    $institute_id,
                    $academicData->branch_id ?? null
                );
    
                
                $students[$studentHash] = [
                    'student_hash_id' => $studentHash,
                    'student_name' => trim($student->first_name . ' ' . ($student->middle_name ? $student->middle_name . ' ' : '') . $student->last_name),
                    'student_reg' => $student->registration_number,
                    'department' => $academicData->department ?? '',
                    'department_id' => $academicData->department_id ?? '',
                    'course' => $academicData->course_type ?? '',
                    'branch' => $academicData->course_subtype ?? '',
                    'batch' => $academicData->batch ?? '',
                    'academic_year' => $academicData->academic_year ?? $academicYearFilter,
                    'semester' => $academicData->semester_id ?? '',
                    'section' => $sectionName ?: ($academicData->section_id ?? ''),
                    'mode_type' => $academicData->mode_type ?? '',
                    'mode_of_course' => $academicData->mode_of_course ?? '',
                    'installments' => [],
                    'total_fee' => 0,
                    'total_paid' => 0,
                    'total_pending' => 0,
                    'total_original_fee' => 0,
                    'total_discount' => 0,
                    'total_late_fee' => 0,
                    'total_payable' => 0,
                    'current_installment' => null
                ];
            }
            
            $students[$studentHash]['installments'][] = [
                'id' => $fee->id,
                'fee_type' => $feeType,
                'fee_amount' => $feeAmount,
                'payable_amount' => $payableAmount,
                'late_fee' => $lateFee,
                'discount' => $discount,
                'due_date' => $fee->due_date,
                'payment_status' => $status,
                'payment_type' => $fee->payment_type,
                'updated_at' => $fee->updated_at,
            ];
            
            // Update totals
            $students[$studentHash]['total_original_fee'] += $feeAmount;
            $students[$studentHash]['total_discount'] += $discount;
            $students[$studentHash]['total_late_fee'] += $lateFee;
            $students[$studentHash]['total_payable'] += $payableAmount;
            
            if ($status === 'paid') {
                $students[$studentHash]['total_paid'] += $payableAmount;
            } elseif ($status === 'overdue') {
                $students[$studentHash]['total_overdue'] = ($students[$studentHash]['total_overdue'] ?? 0) + $payableAmount;
            } else {
                $students[$studentHash]['total_pending'] += $payableAmount;
            }
        }
        
        // Sort installments and set current installment
        foreach ($students as &$student) {
            usort($student['installments'], function($a, $b) {
                return strtotime($a['due_date']) - strtotime($b['due_date']);
            });
            
            foreach ($student['installments'] as $inst) {
                if ($inst['payment_status'] !== 'paid') {
                    $student['current_installment'] = $inst;
                    break;
                }
            }
        }
        
        // Apply filters on grouped data
        $filteredStudents = [];
        foreach ($students as $student) {
            $include = true;
            
            if (request('department_id') && ($student['department_id'] ?? '') != request('department_id')) {
                $include = false;
            }
            if (request('course') && $student['course'] != request('course')) {
                $include = false;
            }
            if (request('batch') && $student['batch'] != request('batch')) {
                $include = false;
            }
            if (request('mode_type') && $student['mode_type'] != request('mode_type')) {
                $include = false;
            }
            if (request('student_search')) {
                $search = strtolower(request('student_search'));
                if (strpos(strtolower($student['student_name']), $search) === false && 
                    strpos(strtolower($student['student_reg']), $search) === false) {
                    $include = false;
                }
            }
            
            if ($include) {
                $filteredStudents[] = $student;
            }
        }
        
        $studentsArray = array_values($filteredStudents);
        
        // Paginate
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPageValue = (int) request('per_page', 10);
        if (request('per_page') === 'all') {
            $perPageValue = count($studentsArray);
        }
        $currentPageItems = array_slice($studentsArray, ($currentPage - 1) * $perPageValue, $perPageValue);
        
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            count($studentsArray),
            $perPageValue,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        // Calculate totals
        $totalPayable = 0;
        $totalPaidCount = 0;
        $totalOverdueCount = 0;
        $totalPaidAmount = 0;
        
        foreach ($studentsArray as $student) {
            $totalPayable += $student['total_payable'] ?? 0;
            foreach ($student['installments'] as $inst) {
                if ($inst['payment_status'] === 'paid') {
                    $totalPaidCount++;
                    $totalPaidAmount += $inst['payable_amount'];
                } elseif ($inst['payment_status'] === 'overdue') {
                    $totalOverdueCount++;
                }
            }
        }
        
        // Get filter data
        $departments = \App\Models\Departments::where('institute_id', $institute_id)->get();
        $courses = StudentAcademicTransportDetails::where('institute_id', $institute_id)->distinct()->pluck('course_type');
        $batches = StudentAcademicTransportDetails::where('institute_id', $institute_id)->distinct()->pluck('batch');
        $customFeeTypes = StudentCustomFeestructure::where('institute_id', $institute_id)->whereNotNull('custom_fee_key')->distinct()->pluck('custom_fee_key');
        
        $transportYears = StudentAcademicTransportDetails::where('institute_id', $institute_id)->distinct()->pluck('academic_year');
        $logYears = StudentAcademicDetailsLog::where('institute_id', $institute_id)->distinct()->pluck('previous_academic_year')
            ->merge(StudentAcademicDetailsLog::where('institute_id', $institute_id)->distinct()->pluck('new_academic_year'))
            ->unique()->filter()->values();
        $academicYears = $transportYears->merge($logYears)->unique()->sortDesc()->values();
        
        return view('instituteAdmin.AdminFeeStructureFile.MiscellaneousFee', compact(
            'paginator', 
            'students', 
            'departments', 
            'courses', 
            'batches', 
            'customFeeTypes',
            'totalPayable', 
            'totalPaidCount', 
            'totalOverdueCount', 
            'totalPaidAmount',
            'academicYears', 
            'academicYearFilter'
        ));
    }

    /**
     * Get empty miscellaneous fee response when no students found
     */
    private function getEmptyMiscellaneousFeeResponse($institute_id, $perPage, $academicYearFilter = null)
    {
        $transportYears = StudentAcademicTransportDetails::where('institute_id', $institute_id)
            ->distinct()
            ->pluck('academic_year');

        $logYears = StudentAcademicDetailsLog::where('institute_id', $institute_id)
            ->distinct()
            ->pluck('previous_academic_year')
            ->merge(
                StudentAcademicDetailsLog::where('institute_id', $institute_id)
                    ->distinct()
                    ->pluck('new_academic_year')
            )
            ->unique()
            ->filter()
            ->values();

        $academicYears = $transportYears->merge($logYears)->unique()->sortDesc()->values();
        
        $departments = \App\Models\Departments::where('institute_id', $institute_id)->get();
        $courses = StudentAcademicTransportDetails::where('institute_id', $institute_id)
            ->distinct()->pluck('course_type');
        $batches = StudentAcademicTransportDetails::where('institute_id', $institute_id)
            ->distinct()->pluck('batch');
        
        $customFeeTypes = StudentCustomFeestructure::where('institute_id', $institute_id)
            ->distinct()
            ->pluck('custom_fee_key');

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            [],
            0,
            $perPage,
            1,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        $totalPayable = 0;
        $totalPaidCount = 0;
        $totalOverdueCount = 0;

        return view('instituteAdmin.AdminFeeStructureFile.MiscellaneousFee', compact(
            'paginator',
            'departments',
            'courses',
            'batches',
            'customFeeTypes',
            'totalPayable',
            'totalPaidCount',
            'totalOverdueCount',
            'academicYears'
        ));
    }

    public function studentCustomFeeInstallments($student_hash)
    {
        $institute_id = auth()->user()->institute_id;
        
        // Get student details
        $studentData = StudentParentDetails::where('student_hash_id', $student_hash)
            ->where('institute_id', $institute_id)
            ->first();
            
        if (!$studentData) {
            abort(404, 'Student not found');
        }
        
        // Get the selected academic year from request
        $academicYearFilter = request('academic_year');
        
        // If no filter, get current academic year
        if (!$academicYearFilter) {
            $academicYearFilter = $this->getCurrentAcademicYear();
        }
        
        // Get academic details for the SELECTED academic year
        $academic = $this->getStudentAcademicForYear($student_hash, $academicYearFilter, $institute_id);
        
        // If no academic found for selected year, try to get from promotion logs
        if (!$academic || !isset($academic->academic_year_id)) {
            $log = StudentAcademicDetailsLog::where('student_hash_id', $student_hash)
                ->where(function($q) use ($academicYearFilter) {
                    $q->where('previous_academic_year', $academicYearFilter)
                    ->orWhere('new_academic_year', $academicYearFilter);
                })
                ->first();
            
            if ($log) {
                $academic = new \stdClass();
                if ($log->previous_academic_year == $academicYearFilter) {
                    $academic->academic_year_id = $log->previous_academic_year_id;
                    $academic->academic_year = $log->previous_academic_year;
                    $academic->section_id = $log->previous_section_id;
                    $academic->course_subtype_id = $log->previous_course_subtype_id;
                    $academic->course_subtype = $log->previous_course_subtype;
                    $academic->product_id = $log->previous_course_subtype_id;
                    $academic->branch_id = $log->previous_branch_id;
                    $academic->course_type = $log->previous_course_type;
                    $academic->department = $log->previous_department;
                    $academic->batch = $log->previous_batch;
                    $academic->batch_id = $log->previous_batch_id;
                    $academic->semester_id = $log->previous_semester_id;
                } else {
                    $academic->academic_year_id = $log->new_academic_year_id;
                    $academic->academic_year = $log->new_academic_year;
                    $academic->section_id = $log->new_section_id;
                    $academic->course_subtype_id = $log->new_course_subtype_id;
                    $academic->course_subtype = $log->new_course_subtype;
                    $academic->product_id = $log->new_course_subtype_id;
                    $academic->branch_id = $log->new_branch_id;
                    $academic->course_type = $log->new_course_type;
                    $academic->department = $log->new_department;
                    $academic->batch = $log->new_batch;
                    $academic->batch_id = $log->new_batch_id;
                    $academic->semester_id = $log->new_semester_id;
                }
            }
        }
        
        // If still no academic found, try to get current record
        if (!$academic || !isset($academic->academic_year_id)) {
            $academic = StudentAcademicTransportDetails::where('student_hash_id', $student_hash)
                ->where('institute_id', $institute_id)
                ->first();
        }
        
        // Get custom fee installments for the SPECIFIC academic year
        $installments = StudentCustomFeestructure::where('student_hash_id', $student_hash)
            ->where('institute_id', $institute_id);
        
        if ($academic && isset($academic->academic_year_id) && $academic->academic_year_id) {
            $installments = $installments->where('academic_year_id', $academic->academic_year_id);
        } else {
            // Fallback: filter by academic year string
            $installments = $installments->where('academic_year_id', 'LIKE', '%' . $academicYearFilter . '%');
        }
        
        $installments = $installments->orderBy('due_date')->get();
        
        // Log for debugging
        \Log::info('Custom Fee Installments - Filtered by Year', [
            'student_hash' => $student_hash,
            'selected_academic_year' => $academicYearFilter,
            'academic_year_id_used' => $academic->academic_year_id ?? null,
            'installments_found' => $installments->count(),
        ]);
        
        // If no installments found for this academic year, try to get all for display
        if ($installments->isEmpty()) {
            $installments = StudentCustomFeestructure::where('student_hash_id', $student_hash)
                ->where('institute_id', $institute_id)
                ->orderBy('due_date')
                ->get();
        }
        
        // Calculate summary statistics
        $summary = $this->calculateCustomFeeInstallmentSummary($installments);
        
        // Get previous academic year installments (for historical view)
        $previousInstallments = collect();
        if ($academic && isset($academic->academic_year_id)) {
            $previousInstallments = StudentCustomFeestructure::where('student_hash_id', $student_hash)
                ->where('institute_id', $institute_id)
                ->where('academic_year_id', '!=', $academic->academic_year_id)
                ->orderBy('due_date')
                ->get();
        }
        
        $previousSummary = $this->calculateCustomFeeInstallmentSummary($previousInstallments);
        
        // Get institute details for receipt
        $serviceInstitutedetails = InstituteBasicDetails::where('fincap_merchant_id', $institute_id)->first();
        
        // Get section name
        $productIdForSection = $academic->course_subtype_id ?? $academic->product_id ?? null;
        $sectionName = $this->getSectionDisplayName(
            $academic->section_id ?? null,
            $productIdForSection,
            $institute_id,
            $academic->branch_id ?? null
        );
        
        // Calculate payment progress
        $totalInstallments = $installments->count();
        $paidInstallments = $installments->where('payment_status', 'paid')->count();
        $pendingInstallments = $installments->where('payment_status', '!=', 'paid')->count();
        $overdueInstallments = $installments->where('payment_status', '!=', 'paid')
            ->filter(function($inst) {
                return $inst->due_date && \Carbon\Carbon::parse($inst->due_date)->lt(\Carbon\Carbon::today());
            })->count();
        
        $paymentProgressPercentage = $totalInstallments > 0 
            ? ($paidInstallments / $totalInstallments) * 100 
            : 0;
        
        $totalCustomFee = $installments->sum('custom_fee_value');
        $totalPaidCustomFee = $installments->where('payment_status', 'paid')->sum(function($inst) {
            return ($inst->custom_fee_value ?? 0) + ($inst->late_fee_amount ?? 0) - ($inst->discount_amount ?? 0);
        });
        $totalPendingCustomFee = $summary['total_fee'] - $summary['total_paid'];
        
        // Get promotion history
        $promotionHistory = StudentAcademicDetailsLog::where('student_hash_id', $student_hash)
            ->orderBy('promoted_at', 'desc')
            ->get();
        
        // IMPORTANT: Transform installments to include all data needed for receipts
        $installmentsForJS = $installments->map(function($inst) {
            return [
                'id' => $inst->id,
                'custom_fee_key' => $inst->custom_fee_key,
                'custom_fee_value' => $inst->custom_fee_value,
                'late_fee_amount' => $inst->late_fee_amount,
                'discount_amount' => $inst->discount_amount,
                'due_date' => $inst->due_date,
                'pay_date' => $inst->pay_date,
                'payment_status' => $inst->payment_status,
                'payment_type' => $inst->payment_type,
                'transaction_id' => $inst->fee_reference_id ?? $inst->transaction_id,
                'fee_duration_type' => $inst->fee_duration_type,
            ];
        });
        
        return view('instituteAdmin.AdminFeeStructureFile.StudentCustomFeeInstallments', compact(
            'studentData', 
            'academic', 
            'installments',
            'installmentsForJS',  // ADD THIS - the view expects this variable
            'summary',
            'previousInstallments',
            'previousSummary',
            'serviceInstitutedetails', 
            'sectionName',
            'academicYearFilter',
            'promotionHistory',
            'totalInstallments',
            'paidInstallments',
            'pendingInstallments',
            'overdueInstallments',
            'paymentProgressPercentage',
            'totalCustomFee',
            'totalPaidCustomFee',
            'totalPendingCustomFee'
        ));
    }

    private function calculateCustomFeeInstallmentSummary($installments)
    {
        $totalFee = 0;
        $totalPaid = 0;
        $totalPending = 0;
        $paidCount = 0;
        $pendingCount = 0;
        $totalLateFee = 0;
        $totalDiscount = 0;
        
        foreach ($installments as $inst) {
            $feeAmount = $inst->custom_fee_value ?? 0;
            $lateFee = $inst->late_fee_amount ?? 0;
            $discount = $inst->discount_amount ?? 0;
            $payable = $feeAmount + $lateFee - $discount;
            
            $totalFee += $payable;
            $totalLateFee += $lateFee;
            $totalDiscount += $discount;
            
            // Determine status based on due date if not paid
            $status = $inst->payment_status;
            if ($status !== 'paid') {
                $dueDate = \Carbon\Carbon::parse($inst->due_date);
                if ($dueDate->lt(\Carbon\Carbon::today())) {
                    $status = 'overdue';
                } else {
                    $status = 'pending';
                }
            }
            
            if ($status === 'paid') {
                $totalPaid += $payable;
                $paidCount++;
            } else {
                $totalPending += $payable;
                $pendingCount++;
            }
        }
        
        return [
            'total_installments' => $installments->count(),
            'total_fee' => $totalFee,
            'total_paid' => $totalPaid,
            'total_pending' => $totalPending,
            'paid_count' => $paidCount,
            'pending_count' => $pendingCount,
            'total_late_fee' => $totalLateFee,
            'total_discount' => $totalDiscount,
            'completion_percentage' => $installments->count() > 0 ? ($paidCount / $installments->count()) * 100 : 0,
        ];
    }


    public function manualPaymentConfirmationFee(Request $request)
    {
        // Generate reference ID if transaction_id is empty
        $banktransactionId = $request->transaction_id;
        $transactionId = $this->generateUniqueReferenceId();
    
        try {
            $updated = false;
            $feeRecord = null;
            $feeType = $request->type;
            $paymentMethod = $request->paymentMethod;
            $payableAmount = $request->payableAmount;

            switch ($feeType) {
                case 'Course':
                    $feeRecord = StudentCourseFeeStructure::where('id', $request->id)
                        ->whereIn('payment_status', ['pending', 'partial'])
                        ->first();
                        
                    if ($feeRecord) {
                        $updated = $feeRecord->update([
                            'pay_date' => now(),
                            'payment_status' => 'paid',
                            'course_total_fee' => $payableAmount,
                            'course_pay_fee_amount' => $payableAmount,
                            'payment_type' => $paymentMethod,
                            'fee_reference_id' => $transactionId
                        ]);
                    }
                    break;
                    
                case 'Registration':
                    $feeRecord = StudentRegistrationFeeStructure::where('id', $request->id)
                        ->whereIn('payment_status', ['pending', 'partial'])
                        ->first();
                        
                    if ($feeRecord) {
                        $updated = $feeRecord->update([
                            'pay_date' => now(),
                            'payment_status' => 'paid',
                            'registration_total_fee' => $payableAmount,
                            'payment_type' => $paymentMethod,
                            'fee_reference_id' => $transactionId
                        ]);
                    }
                    break;
                    
                case 'Transport':
                    $feeRecord = StudentTransportFeeStructure::where('id', $request->id)
                        ->whereIn('payment_status', ['pending', 'partial'])
                        ->first();
                        
                    if ($feeRecord) {
                        $updated = $feeRecord->update([
                            'pay_date' => now(),
                            'payment_status' => 'paid',
                            'transport_total_fee' => $payableAmount,
                            'payment_type' => $paymentMethod,
                            'fee_reference_id' => $transactionId
                        ]);
                    }
                    break;
                    
                case 'Hostel':
                    $feeRecord = StudentHostelFeeStructure::where('id', $request->id)
                        ->whereIn('payment_status', ['pending', 'partial'])
                        ->first();
                        
                    if ($feeRecord) {
                        $updated = $feeRecord->update([
                            'pay_date' => now(),
                            'payment_status' => 'paid',
                            'hostel_total_fee' => $payableAmount,
                            'payment_type' => $paymentMethod,
                            'fee_reference_id' => $transactionId
                        ]);
                    }
                    break;
                    
                case 'Custom':
                    $feeRecord = StudentCustomFeestructure::where('id', $request->id)
                        ->whereIn('payment_status', ['pending', 'partial'])
                        ->first();
                        
                    if ($feeRecord) {
                        $updated = $feeRecord->update([
                            'pay_date' => now(),
                            'payment_status' => 'paid',
                            'total_fee_amount' => $payableAmount,
                            'payment_type' => $paymentMethod,
                            'fee_reference_id' => $transactionId
                        ]);
                    }
                    break;
                    
                default:
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid fee type'
                    ], 400);
            }

            if (!$updated || !$feeRecord) {
                return response()->json([
                    'status' => false,
                    'message' => 'Fee record not found or already paid'
                ], 404);
            }

            // ==========================================
            // CREATE PAYMENT GATEWAY LINK RECORD FOR MANUAL PAYMENTS
            // ==========================================
            $user = auth()->user();
            $roles = $user->roles->pluck('name')->first() ?? 'user';
            
            // Determine the payment type for the gateway record
            $gatewayPaymentType = $this->getGatewayPaymentType($feeType);
            
            // Create the payment gateway record
            $paymentLink = PaymentGatewayLink::create([
                'user_id' => auth()->id(),
                'institute_id' => $user->institute_id,
                'user_type' => $roles,
                'transaction_reference' => $transactionId,
                'user_transaction_refered_id' =>  $banktransactionId ?? null,
                'payment_type' => $gatewayPaymentType,
                'payment_link_id' => null, 
                'payment_link' => null, 
                'amount' => $payableAmount,
                'service_charges_type' => 'Manual Payment - ' . ucfirst($paymentMethod),
                'service_charges_amount' => 0.00, 
                'gst_charges' => '0%',
                'gst_charges_amount' => 0.00,
                'total_amount' => $payableAmount,
                'status' => 'paid', 
                'pg_type' => $paymentMethod, 
                // 'payment_mode' => $paymentMethod, 
            
            ]);

            return response()->json([
                'status' => true,
                'updated_rows' => $updated,
                'reference_id' => $transactionId,
                'payment_gateway_id' => $paymentLink->id,
                'message' => 'Payment confirmed successfully'
            ]);

        } catch (\Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => 'Failed to confirm payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the payment type string for the gateway record
     */
    private function getGatewayPaymentType($feeType)
    {
        $types = [
            'Course' => 'Course Fee',
            'Registration' => 'Registration Fee',
            'Transport' => 'Transport Fee',
            'Hostel' => 'Hostel Fee',
            'Custom' => 'Custom Fee'
        ];
        
        return $types[$feeType] ?? 'Other Fee';
    }

    /**
     * Generate a unique reference ID
     * Format: TXN-YYYYMMDD-XXXXXXXX (8 random alphanumeric characters)
     */
    private function generateUniqueReferenceId()
    {
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid() . mt_rand()), 0, 8));
        
        // Optional: Check if this reference ID already exists in any fee table
        // You can add a loop here to ensure uniqueness across all fee types
        
        return "TXN-{$date}-{$random}";
    }

    /**
     * Get the current/running academic year based on current date
     * Assumes academic year runs from June to May (India typical)
     * Adjust the months based on your institution's academic calendar
     */
    private function getCurrentAcademicYear()
    {
        $currentDate = Carbon::now();
        $currentMonth = $currentDate->month;
        $currentYear = $currentDate->year;
        
        // If current month is June (4) or later, academic year is currentYear - nextYear
        // If current month is before April, academic year is previousYear - currentYear
        if ($currentMonth >= 4) {
            // June to December: e.g., 2026-2027
            $academicYear = $currentYear . '-' . ($currentYear + 1);
        } else {
            // January to March: e.g., 2025-2026
            $academicYear = ($currentYear - 1) . '-' . $currentYear;
        }
        
        return $academicYear;
    }


}
