<?php
namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentParentDetails;
use App\Models\StudentAcademicTransportDetails;
use App\Models\StudentAcademicDetailsLog;
use App\Models\CourseFeeStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Traits\InstituteBranchAccess;
use App\Models\StudentCourseFeeStructure;
use App\Models\StudentRegistrationFeeStructure;

class StudentPromotionController extends Controller
{
    use InstituteBranchAccess;

    /**
     * Show the promotion form
    */
    public function showPromotionForm()
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return back()->with('error', 'You are not associated with any institute.');
        }

        // Get all departments
        $departments = DB::table('departments')
            ->select('department_id', 'department')
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($q) use ($context) {
                $q->where('branch_id', $context['branch_id']);
            })
            ->orderBy('department')
            ->get();

        // Generate academic years for dropdown
        $academicYears = $this->generateAcademicYears();

        return view('instituteAdmin.Promotion.PromoteStudents', compact('departments', 'academicYears'));
    }

    /**
     * Generate academic years for dropdown
     */
    private function generateAcademicYears()
    {
        $currentYear = date('Y');
        
        $academicYears = [];
        
        // Generate from current year only
        for ($i = 0; $i <= 5; $i++) {
            $yearStart = $currentYear + $i;
            $yearEnd = $yearStart + 1;
            $yearLabel = $yearStart . '-' . $yearEnd;
            
            // Determine which year should be selected by default
            $selected = false;
            $nextSelected = false;
            
            // For "from" academic year, default to current year
            if ($i == 0) {
                $selected = true;
            }
            
            // For "to" academic year, default to next year
            if ($i == 1) {
                $nextSelected = true;
            }
            
            $academicYears[] = [
                'id' => $yearStart,
                'label' => $yearLabel,
                'selected' => $selected,
                'next_selected' => $nextSelected
            ];
        }
        
        return $academicYears;
    }

    /**
     * Helper method to get the next class level (e.g., Class 1 -> Class 2, Nursery -> LKG)
     */
    private function getNextClassLevel($currentClass)
    {
        // Define standard class progression order
        $classProgression = [
            'Nursery' => 'LKG',
            'LKG' => 'UKG',
            'UKG' => 'Class 1',
            'Class 1' => 'Class 2',
            'Class 2' => 'Class 3',
            'Class 3' => 'Class 4',
            'Class 4' => 'Class 5',
            'Class 5' => 'Class 6',
            'Class 6' => 'Class 7',
            'Class 7' => 'Class 8',
            'Class 8' => 'Class 9',
            'Class 9' => 'Class 10',
            'Class 10' => 'Class 11',
            'Class 11' => 'Class 12',
        ];
        
        // Check if class exists in progression mapping
        if (isset($classProgression[$currentClass])) {
            return $classProgression[$currentClass];
        }
        
        // Extract numeric value from class name (e.g., "Class 1" -> 1, "Class 2" -> 2)
        if (preg_match('/(\d+)/', $currentClass, $matches)) {
            $nextLevel = $matches[1] + 1;
            return preg_replace('/\d+/', $nextLevel, $currentClass, 1);
        }
        
        // Fallback for unknown class names
        return 'the next class';
    }

    /**
     * Get classes (course subtypes) for selected department
     */
    public function getClassesByDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Department ID required']);
        }

        $context = $this->getInstituteBranchContext();

        $classes = DB::table('product_details')
            ->select('product_id', 'sub_type', 'course_type', 'department_id', 'course_length', 'course_duration')
            ->where('department_id', $request->department_id)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($q) use ($context) {
                $q->where('branch_id', $context['branch_id']);
            }, function($q) {
                $q->whereNull('branch_id');
            })
            ->orderBy('sub_type')
            ->get();

        // Format class names with course type/branch
        $formattedClasses = $classes->map(function($class) {
            // Display format: "BBA (Human Resources)" or if no course_type, just "BBA"
            $displayName = $class->sub_type;
            if (!empty($class->course_type)) {
                $displayName = $class->sub_type . ' (' . $class->course_type . ')';
            }
            
            return [
                'product_id' => $class->product_id,
                'sub_type' => $class->sub_type,
                'course_type' => $class->course_type,
                'department_id' => $class->department_id,
                'course_length' => $class->course_length,
                'course_duration' => $class->course_duration,
                'display_name' => $displayName
            ];
        });

        return response()->json([
            'success' => true,
            'classes' => $formattedClasses
        ]);
    }

    /**
     * Get sections for selected class
     */
    public function getSectionsByClass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Class ID required']);
        }

        $context = $this->getInstituteBranchContext();

        // Get sections from course_fee_structures
        $courseFeeStructure = DB::table('course_fee_structures')
            ->where('product_id', $request->class_id)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($q) use ($context) {
                $q->where('branch_id', $context['branch_id']);
            }, function($q) {
                $q->whereNull('branch_id');
            })
            ->orderBy('batch_id', 'desc')
            ->first();

        $sections = [];
        if ($courseFeeStructure && $courseFeeStructure->sections) {
            $sections = json_decode($courseFeeStructure->sections, true) ?? [];
        }

        return response()->json([
            'success' => true,
            'sections' => $sections
        ]);
    }

    /**
     * Get students list for selected class, section and academic year
     */
    public function getStudentsForPromotion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required',
            'academic_year' => 'required',
            'section_id' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Class ID and Academic Year required']);
        }

        $context = $this->getInstituteBranchContext();
        
        // Get the selected academic year label
        $selectedAcademicYear = $request->academic_year;
        
        // Convert year start to full year label (e.g., 2025 to 2025-2026)
        $academicYearLabel = $selectedAcademicYear . '-' . ($selectedAcademicYear + 1);

        // Get source class details from product_details
        $sourceClass = DB::table('product_details')
            ->where('product_id', $request->class_id)
            ->first();

        if (!$sourceClass) {
            return response()->json([
                'success' => false,
                'message' => 'Source class not found'
            ], 404);
        }

        // Get course end date directly from course_fee_structures using academic_year
        $courseFeeStructure = DB::table('course_fee_structures')
            ->where('product_id', $request->class_id)
            ->where('academic_year', $academicYearLabel)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($q) use ($context) {
                $q->where('branch_id', $context['branch_id']);
            }, function($q) {
                $q->whereNull('branch_id');
            })
            ->first();

        // If no fee structure found for this academic year, show error
        if (!$courseFeeStructure) {
            return response()->json([
                'success' => false,
                'message' => 'No fee structure found for class ' . $sourceClass->sub_type . ' in academic year ' . $academicYearLabel,
                'course_not_completed' => true,
                'academic_year_label' => $academicYearLabel
            ], 422);
        }

        // Check if course end date is completed
        $courseEndDate = $courseFeeStructure->course_end_date ?? null;
        $currentDate = date('Y-m-d');
        $isCourseCompleted = false;
        $courseNotCompletedMessage = null;

        if ($courseEndDate && $courseEndDate < $currentDate) {
            $isCourseCompleted = true;
        } elseif ($courseEndDate && $courseEndDate >= $currentDate) {
            $courseNotCompletedMessage = "This course is not completed yet. Course end date: " . date('d-m-Y', strtotime($courseEndDate));
        }

        $query = StudentParentDetails::with('academicTransportDetails')
            ->where('institute_id', $context['institute_id'])
            ->where('status', 'active')
            ->whereHas('academicTransportDetails', function($q) use ($request, $context, $academicYearLabel) {
                $q->where('course_subtype_id', $request->class_id)
                ->where('academic_year', $academicYearLabel);
                
                if ($context['is_branch_admin'] && $context['branch_id']) {
                    $q->where('branch_id', $context['branch_id']);
                }
                
                if ($request->filled('section_id')) {
                    $q->where('section_id', $request->section_id);
                }
            })
            ->select(
                'student_hash_id',
                'registration_number',
                'first_name',
                'middle_name',
                'last_name',
                'email',
                'mobile',
                'status'
            )
            ->get();

        // If course is not completed, return error message
        if (!$isCourseCompleted && $courseNotCompletedMessage) {
            return response()->json([
                'success' => false,
                'message' => $courseNotCompletedMessage,
                'course_not_completed' => true,
                'end_date' => $courseEndDate,
                'academic_year_label' => $academicYearLabel,
                'academic_year_id' => $courseFeeStructure->academic_year_id
            ], 422);
        }

        // Get section display names
        $sections = [];
        if ($courseFeeStructure && $courseFeeStructure->sections) {
            $sections = json_decode($courseFeeStructure->sections, true) ?? [];
        }

        $sectionMap = [];
        foreach ($sections as $section) {
            if (isset($section['id'], $section['name'])) {
                $sectionMap[$section['id']] = $section['name'];
            }
        }

        // Get department name
        $department = DB::table('departments')
            ->where('department_id', $sourceClass->department_id ?? null)
            ->first();

        // Format students data
        $students = $query->map(function($student) use ($sectionMap, $sourceClass, $department, $academicYearLabel) {
            $academic = $student->academicTransportDetails;
            
            return [
                'student_hash_id' => $student->student_hash_id,
                'registration_number' => $student->registration_number,
                'full_name' => trim($student->first_name . ' ' . 
                    ($student->middle_name ? $student->middle_name . ' ' : '') . 
                    $student->last_name),
                'status' => $student->status,
                'current_department' => $department->department ?? null,
                'current_department_id' => $sourceClass->department_id ?? null,
                'current_class' => $academic->course_subtype ?? $sourceClass->sub_type ?? null,
                'current_class_id' => $academic->course_subtype_id ?? null,
                'current_section' => $academic->section_id,
                'current_section_name' => $sectionMap[$academic->section_id] ?? $academic->section_id,
                'current_batch' => $academic->batch ?? null,
                'current_batch_id' => $academic->batch_id ?? null,
                'current_academic_year' => $academic->academic_year ?? $academicYearLabel,
                'academic_year_id' => $academic->academic_year_id
            ];
        });

        return response()->json([
            'success' => true,
            'students' => $students,
            'total_count' => $students->count(),
            'source_department_id' => $sourceClass->department_id ?? null,
            'source_department_name' => $department->department ?? null,
            'academic_year' => $academicYearLabel,
            'academic_year_id' => $courseFeeStructure->academic_year_id,
            'course_end_date' => $courseEndDate,
            'course_end_date_formatted' => $courseEndDate ? date('d-m-Y', strtotime($courseEndDate)) : null,
            'message' => 'Showing active students from academic year ' . $academicYearLabel
        ]);
    }

    /**
     * Process student promotion
     */
    public function promoteStudents(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'from_class_id' => 'required',
                'to_class_id' => 'required',
                'from_section_id' => 'nullable|string',
                'to_section_id' => 'nullable|string',
                'from_academic_year' => 'required',
                'to_academic_year' => 'required',
                'student_ids' => 'required|array|min:1',
                'student_ids.*' => 'exists:student_parent_details,student_hash_id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $context = $this->getInstituteBranchContext();
            $currentUser = Auth::user();

            // Convert academic year IDs to labels
            $fromAcademicYearLabel = $request->from_academic_year . '-' . ($request->from_academic_year + 1);
            $toAcademicYearLabel = $request->to_academic_year . '-' . ($request->to_academic_year + 1);

            // Get source class details
            $sourceClass = DB::table('product_details')
                ->where('product_id', $request->from_class_id)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if (!$sourceClass) {
                throw new \Exception('Source class not found');
            }

            // Get target class details
            $targetClass = DB::table('product_details')
                ->where('product_id', $request->to_class_id)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if (!$targetClass) {
                throw new \Exception('Target class not found');
            }

            // VALIDATION: Check if trying to promote to the same product for single-year courses
            if ($sourceClass->product_id == $targetClass->product_id) {
                $isMultiYearCourse = ($sourceClass->course_length > 1);
                
                if (!$isMultiYearCourse && $fromAcademicYearLabel != $toAcademicYearLabel) {
                    // Single-year course trying to promote to same class but different year - BLOCK
                    throw new \Exception('Students cannot be promoted to the same class. Please select the next class (e.g., ' . $this->getNextClassLevel($sourceClass->sub_type) . ') as the target class.');
                }
                
                if ($fromAcademicYearLabel == $toAcademicYearLabel) {
                    throw new \Exception('Cannot promote students to the same class in the same academic year.');
                }
            }

            // Get target department details
            $targetDepartment = null;
            if ($targetClass->department_id) {
                $targetDepartment = DB::table('departments')
                    ->where('department_id', $targetClass->department_id)
                    ->first();
            }

            $promotedCount = 0;
            $failedStudents = [];
            $feePendingStudents = [];

            foreach ($request->student_ids as $studentHashId) {
                try {
                    // Get student with current academic record
                    $student = StudentParentDetails::where('student_hash_id', $studentHashId)
                        ->where('status', 'active')
                        ->first();

                    if (!$student) {
                        $failedStudents[] = [
                            'id' => $studentHashId,
                            'name' => $studentHashId,
                            'reason' => 'Student not found or inactive'
                        ];
                        continue;
                    }

                    $studentFullName = trim(
                        ($student->first_name ?? '') . ' ' .
                        ($student->middle_name ? $student->middle_name . ' ' : '') .
                        ($student->last_name ?? '')
                    );
                    $studentLabel = $studentFullName ?: $studentHashId;
                    if ($student->registration_number) {
                        $studentLabel .= ' (' . $student->registration_number . ')';
                    }

                    $academicRecord = StudentAcademicTransportDetails::where('student_hash_id', $studentHashId)
                        ->where('academic_year', $fromAcademicYearLabel)
                        ->first();
                    
                    if (!$academicRecord) {
                        $failedStudents[] = [
                            'id' => $studentHashId,
                            'name' => $studentLabel,
                            'reason' => 'No academic record found for year ' . $fromAcademicYearLabel
                        ];
                        continue;
                    }

                    // Get source course fee structure using student's actual academic_year_id and batch_id
                    $sourceCourseFee = DB::table('course_fee_structures')
                        ->where('product_id', $academicRecord->course_subtype_id)
                        ->where('academic_year_id', $academicRecord->academic_year_id)
                        ->where('batch_id', $academicRecord->batch_id)
                        ->where('institute_id', $context['institute_id'])
                        ->first();

                    // If not found with batch_id, try without batch_id
                    if (!$sourceCourseFee) {
                        $sourceCourseFee = DB::table('course_fee_structures')
                            ->where('product_id', $academicRecord->course_subtype_id)
                            ->where('academic_year_id', $academicRecord->academic_year_id)
                            ->where('institute_id', $context['institute_id'])
                            ->first();
                    }

                    // Check if source course is completed using the correct fee structure
                    $courseEndDate = $sourceCourseFee->course_end_date ?? null;
                    $currentDate = date('Y-m-d');
                    if ($courseEndDate && $courseEndDate >= $currentDate) {
                        $failedStudents[] = [
                            'id' => $studentHashId,
                            'name' => $studentLabel,
                            'reason' => 'Course not completed yet. End date: ' . date('d-m-Y', strtotime($courseEndDate))
                        ];
                        continue;
                    }
        
                    // Check pending fees for current course installments and registration fee
                    $pendingCourse = StudentCourseFeeStructure::where('student_hash_id', $studentHashId)
                        ->where('institute_id', $context['institute_id'])
                        ->where('product_id', $academicRecord->course_subtype_id)
                        ->where('batch_id', $academicRecord->batch_id)
                        ->where('academic_year_id', $academicRecord->academic_year_id)
                        ->where('payment_status', '!=', 'paid')
                        ->exists();

                    $pendingRegistration = StudentRegistrationFeeStructure::where('student_hash_id', $studentHashId)
                        ->where('institute_id', $context['institute_id'])
                        ->where('payment_status', '!=', 'paid')
                        ->exists();

                    if ($pendingCourse || $pendingRegistration) {
                        $feeTypes = [];
                        if ($pendingCourse) $feeTypes[] = 'Course Fee';
                        if ($pendingRegistration) $feeTypes[] = 'Registration Fee';
                        
                        $feePendingStudents[] = [
                            'id' => $studentHashId,
                            'registration_number' => $student->registration_number,
                            'name' => $studentFullName,
                            'pending_fees' => implode(' + ', $feeTypes)
                        ];
                        continue;
                    }

                    // Get target batch details - MUST match the selected academic year
                    $targetCourseFee = DB::table('course_fee_structures')
                        ->where('product_id', $request->to_class_id)
                        ->where('academic_year', $toAcademicYearLabel)
                        ->where('institute_id', $context['institute_id'])
                        ->when($context['is_branch_admin'] && $context['branch_id'], function($q) use ($context) {
                            $q->where('branch_id', $context['branch_id']);
                        }, function($q) {
                            $q->whereNull('branch_id');
                        })
                        ->first();

                    // NO FALLBACK - if not found, throw error explicitly
                    if (!$targetCourseFee) {
                        $failedStudents[] = [
                            'id' => $studentHashId,
                            'name' => $studentLabel,
                            'reason' => 'Fee structure not found for target class in academic year ' . $toAcademicYearLabel
                        ];
                        continue;
                    }

                    // Determine promotion type
                    $promotionType = ($sourceClass->department_id != $targetClass->department_id) 
                        ? 'cross_department' 
                        : 'same_department';

                    // For same product but different year, mark as vertical progression (only for multi-year courses)
                    if ($sourceClass->product_id == $targetClass->product_id && $sourceClass->course_length > 1) {
                        $promotionType = 'vertical_progression';
                    }

                    // Save to log table
                    StudentAcademicDetailsLog::create([
                        'student_hash_id' => $studentHashId,
                        'user_id' => $student->user_id,
                        'institute_id' => $context['institute_id'],
                        'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                        
                        // Previous academic details
                        'previous_department_id' => $academicRecord->department_id,
                        'previous_department' => $academicRecord->department,
                        'previous_department_category_id' => $academicRecord->department_category_id,
                        'previous_course_type_id' => $academicRecord->course_type_id,
                        'previous_course_type' => $academicRecord->course_type,
                        'previous_course_subtype_id' => $academicRecord->course_subtype_id,
                        'previous_course_subtype' => $academicRecord->course_subtype,
                        'previous_session_id' => $academicRecord->session_id,
                        'previous_semester_id' => $academicRecord->semester_id,
                        'previous_section_id' => $academicRecord->section_id,
                        'previous_batch_id' => $academicRecord->batch_id,
                        'previous_batch' => $academicRecord->batch,
                        'previous_academic_year_id' => $academicRecord->academic_year_id,
                        'previous_academic_year' => $academicRecord->academic_year,
                        'previous_mode_of_course' => $academicRecord->mode_of_course,
                        'previous_mode_type' => $academicRecord->mode_type,
                        
                        // New academic details
                        'new_department_id' => $targetClass->department_id,
                        'new_department' => $targetDepartment->department ?? null,
                        'new_department_category_id' => $targetClass->department_category_id ?? null,
                        'new_course_type_id' => $targetClass->finacp_merchant_sub_category_id ?? $academicRecord->course_type_id,
                        'new_course_type' => $targetClass->course_type ?? $academicRecord->course_type,
                        'new_course_subtype_id' => $request->to_class_id,
                        'new_course_subtype' => $targetClass->sub_type,
                        'new_session_id' => $targetCourseFee->session_id ?? null,
                        'new_semester_id' => null,
                        'new_section_id' => $request->to_section_id,
                        'new_batch_id' => $targetCourseFee->batch_id,
                        'new_batch' => $targetCourseFee->batch,
                        'new_academic_year_id' => $targetCourseFee->academic_year_id,
                        'new_academic_year' => $toAcademicYearLabel,
                        'new_mode_of_course' => $targetClass->mode_of_course ?? $academicRecord->mode_of_course,
                        'new_mode_type' => $targetClass->mode_type ?? $academicRecord->mode_type,
                        
                        'promotion_type' => $promotionType,
                        'promoted_by' => $currentUser->id,
                        'promoted_at' => now()
                    ]);

                    // Update academic record
                    $academicUpdate = [
                        'course_subtype_id' => $request->to_class_id,
                        'course_subtype' => $targetClass->sub_type,
                        'department_id' => $targetClass->department_id,
                        'department' => $targetDepartment->department ?? null,
                        'academic_year' => $toAcademicYearLabel,
                        'academic_year_id' => $targetCourseFee->academic_year_id,
                        'batch_id' => $targetCourseFee->batch_id,
                        'batch' => $targetCourseFee->batch,
                    ];

                    if ($request->filled('to_section_id')) {
                        $academicUpdate['section_id'] = $request->to_section_id;
                    }

                    if ($targetClass->finacp_merchant_sub_category_id) {
                        $academicUpdate['course_type_id'] = $targetClass->finacp_merchant_sub_category_id;
                        $academicUpdate['course_type'] = $targetClass->course_type;
                    }

                    if ($targetClass->mode_of_course) {
                        $academicUpdate['mode_of_course'] = $targetClass->mode_of_course;
                    }
                    if ($targetClass->mode_type) {
                        $academicUpdate['mode_type'] = $targetClass->mode_type;
                    }

                    if ($targetClass->department_category_id) {
                        $academicUpdate['department_category_id'] = $targetClass->department_category_id;
                    }

                    $academicRecord->update($academicUpdate);

                    // UPDATE STUDENT STATUS FROM 'new' TO 'old'
                    if ($student->student_status === 'new') {
                        $student->update(['student_status' => 'old']);
                    }
                    
                    $promotedCount++;

                } catch (\Exception $e) {
                    $failedStudents[] = [
                        'id' => $studentHashId,
                        'name' => $studentLabel ?? $studentHashId,
                        'reason' => 'Error: ' . $e->getMessage()
                    ];
                }
            }

            DB::commit();

            $message = "Successfully promoted {$promotedCount} students from academic year {$fromAcademicYearLabel} to {$toAcademicYearLabel}";
            
            $responseData = [
                'success' => true,
                'message' => $message,
                'promoted_count' => $promotedCount,
                'fee_pending_count' => count($feePendingStudents),
                'failed_count' => count($failedStudents),
                'fee_pending_students' => $feePendingStudents,
                'failed_students' => $failedStudents,
                'from_academic_year' => $fromAcademicYearLabel,
                'to_academic_year' => $toAcademicYearLabel,
                'department_changed' => ($sourceClass->department_id != $targetClass->department_id),
                'product_changed' => ($sourceClass->product_id != $targetClass->product_id)
            ];

            return response()->json($responseData);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Promotion failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Promote all students from a class
     */
    public function promoteAllStudents(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'from_class_id' => 'required',
                'to_class_id' => 'required',
                'from_section_id' => 'nullable|string',
                'to_section_id' => 'nullable|string',
                'from_academic_year' => 'required',
                'to_academic_year' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $context = $this->getInstituteBranchContext();
            $currentUser = Auth::user();
            
            // Convert academic year IDs to labels
            $fromAcademicYearLabel = $request->from_academic_year . '-' . ($request->from_academic_year + 1);
            $toAcademicYearLabel = $request->to_academic_year . '-' . ($request->to_academic_year + 1);
            
            // Get source class details
            $sourceClass = DB::table('product_details')
                ->where('product_id', $request->from_class_id)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if (!$sourceClass) {
                throw new \Exception('Source class not found');
            }

            // Get target class details
            $targetClass = DB::table('product_details')
                ->where('product_id', $request->to_class_id)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if (!$targetClass) {
                throw new \Exception('Target class not found');
            }

            // VALIDATION: Check if trying to promote to the same product for single-year courses
            if ($sourceClass->product_id == $targetClass->product_id) {
                $isMultiYearCourse = ($sourceClass->course_length > 1);
                
                if (!$isMultiYearCourse && $fromAcademicYearLabel != $toAcademicYearLabel) {
                    throw new \Exception('Students cannot be promoted to the same class. Please select the next class (e.g., ' . $this->getNextClassLevel($sourceClass->sub_type) . ') as the target class.');
                }
                
                if ($fromAcademicYearLabel == $toAcademicYearLabel) {
                    throw new \Exception('Cannot promote students to the same class in the same academic year.');
                }
            }
            
            // Get students from source class for the selected academic year
            $studentQuery = StudentAcademicTransportDetails::where('course_subtype_id', $request->from_class_id)
                ->where('institute_id', $context['institute_id'])
                ->where('academic_year', $fromAcademicYearLabel);

            if ($context['is_branch_admin'] && $context['branch_id']) {
                $studentQuery->where('branch_id', $context['branch_id']);
            }

            if ($request->filled('from_section_id')) {
                $studentQuery->where('section_id', $request->from_section_id);
            }

            $academicRecords = $studentQuery->get();

            if ($academicRecords->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No students found from academic year ' . $fromAcademicYearLabel
                ], 404);
            }

            // Get target department details
            $targetDepartment = null;
            if ($targetClass->department_id) {
                $targetDepartment = DB::table('departments')
                    ->where('department_id', $targetClass->department_id)
                    ->first();
            }

            $promotedCount = 0;
            $failedStudents = [];
            $feePendingStudents = [];

            foreach ($academicRecords as $academicRecord) {
                try {
                    // Verify student is active
                    $student = StudentParentDetails::where('student_hash_id', $academicRecord->student_hash_id)
                        ->where('status', 'active')
                        ->first();

                    if (!$student) {
                        $failedStudents[] = [
                            'id' => $academicRecord->student_hash_id,
                            'name' => $academicRecord->student_hash_id,
                            'reason' => 'Student inactive'
                        ];
                        continue;
                    }

                    $studentFullName = trim(
                        ($student->first_name ?? '') . ' ' .
                        ($student->middle_name ? $student->middle_name . ' ' : '') .
                        ($student->last_name ?? '')
                    );
                    $studentLabel = $studentFullName ?: $academicRecord->student_hash_id;
                    if ($student->registration_number) {
                        $studentLabel .= ' (' . $student->registration_number . ')';
                    }

                    // Get source course fee structure using student's actual academic_year_id and batch_id
                    $sourceCourseFee = DB::table('course_fee_structures')
                        ->where('product_id', $academicRecord->course_subtype_id)
                        ->where('academic_year_id', $academicRecord->academic_year_id)
                        ->where('batch_id', $academicRecord->batch_id)
                        ->where('institute_id', $context['institute_id'])
                        ->first();

                    // If not found with batch_id, try without batch_id
                    if (!$sourceCourseFee) {
                        $sourceCourseFee = DB::table('course_fee_structures')
                            ->where('product_id', $academicRecord->course_subtype_id)
                            ->where('academic_year_id', $academicRecord->academic_year_id)
                            ->where('institute_id', $context['institute_id'])
                            ->first();
                    }

                    // Check if source course is completed using the correct fee structure
                    $courseEndDate = $sourceCourseFee->course_end_date ?? null;
                    $currentDate = date('Y-m-d');
                    if ($courseEndDate && $courseEndDate >= $currentDate) {
                        $failedStudents[] = [
                            'id' => $academicRecord->student_hash_id,
                            'name' => $studentLabel,
                            'reason' => 'Course not completed yet. End date: ' . date('d-m-Y', strtotime($courseEndDate))
                        ];
                        continue;
                    }

                    // Check pending fees for current course installments and registration fee
                    $pendingCourse = StudentCourseFeeStructure::where('student_hash_id', $academicRecord->student_hash_id)
                        ->where('institute_id', $context['institute_id'])
                        ->where('product_id', $academicRecord->course_subtype_id)
                        ->where('batch_id', $academicRecord->batch_id)
                        ->where('academic_year_id', $academicRecord->academic_year_id)
                        ->where('payment_status', '!=', 'paid')
                        ->exists();

                    $pendingRegistration = StudentRegistrationFeeStructure::where('student_hash_id', $academicRecord->student_hash_id)
                        ->where('institute_id', $context['institute_id'])
                        ->where('payment_status', '!=', 'paid')
                        ->exists();

                    if ($pendingCourse || $pendingRegistration) {
                        $feeTypes = [];
                        if ($pendingCourse) $feeTypes[] = 'Course Fee';
                        if ($pendingRegistration) $feeTypes[] = 'Registration Fee';
                        
                        $feePendingStudents[] = [
                            'id' => $academicRecord->student_hash_id,
                            'registration_number' => $student->registration_number,
                            'name' => $studentFullName,
                            'pending_fees' => implode(' + ', $feeTypes)
                        ];
                        continue;
                    }

                    // Get target batch details - MUST match the selected academic year
                    $targetCourseFee = DB::table('course_fee_structures')
                        ->where('product_id', $request->to_class_id)
                        ->where('academic_year', $toAcademicYearLabel)
                        ->where('institute_id', $context['institute_id'])
                        ->when($context['is_branch_admin'] && $context['branch_id'], function($q) use ($context) {
                            $q->where('branch_id', $context['branch_id']);
                        }, function($q) {
                            $q->whereNull('branch_id');
                        })
                        ->first();

                    // NO FALLBACK - if not found, throw error explicitly
                    if (!$targetCourseFee) {
                        $failedStudents[] = [
                            'id' => $academicRecord->student_hash_id,
                            'name' => $studentLabel,
                            'reason' => 'Fee structure not found for target class in academic year ' . $toAcademicYearLabel
                        ];
                        continue;
                    }

                    // Determine promotion type
                    $promotionType = ($sourceClass->department_id != $targetClass->department_id) 
                        ? 'cross_department' 
                        : 'same_department';

                    // For same product but different year, mark as vertical progression (only for multi-year courses)
                    if ($sourceClass->product_id == $targetClass->product_id && $sourceClass->course_length > 1) {
                        $promotionType = 'vertical_progression';
                    }

                    // Save to log table
                    StudentAcademicDetailsLog::create([
                        'student_hash_id' => $academicRecord->student_hash_id,
                        'user_id' => $student->user_id,
                        'institute_id' => $context['institute_id'],
                        'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                        
                        // Previous academic details
                        'previous_department_id' => $academicRecord->department_id,
                        'previous_department' => $academicRecord->department,
                        'previous_department_category_id' => $academicRecord->department_category_id,
                        'previous_course_type_id' => $academicRecord->course_type_id,
                        'previous_course_type' => $academicRecord->course_type,
                        'previous_course_subtype_id' => $academicRecord->course_subtype_id,
                        'previous_course_subtype' => $academicRecord->course_subtype,
                        'previous_session_id' => $academicRecord->session_id,
                        'previous_semester_id' => $academicRecord->semester_id,
                        'previous_section_id' => $academicRecord->section_id,
                        'previous_batch_id' => $academicRecord->batch_id,
                        'previous_batch' => $academicRecord->batch,
                        'previous_academic_year_id' => $academicRecord->academic_year_id,
                        'previous_academic_year' => $academicRecord->academic_year,
                        'previous_mode_of_course' => $academicRecord->mode_of_course,
                        'previous_mode_type' => $academicRecord->mode_type,
                        
                        // New academic details
                        'new_department_id' => $targetClass->department_id,
                        'new_department' => $targetDepartment->department ?? null,
                        'new_department_category_id' => $targetClass->department_category_id ?? null,
                        'new_course_type_id' => $targetClass->finacp_merchant_sub_category_id ?? $academicRecord->course_type_id,
                        'new_course_type' => $targetClass->course_type ?? $academicRecord->course_type,
                        'new_course_subtype_id' => $request->to_class_id,
                        'new_course_subtype' => $targetClass->sub_type,
                        'new_session_id' => $targetCourseFee->session_id ?? null,
                        'new_semester_id' => null,
                        'new_section_id' => $request->to_section_id,
                        'new_batch_id' => $targetCourseFee->batch_id,
                        'new_batch' => $targetCourseFee->batch,
                        'new_academic_year_id' => $targetCourseFee->academic_year_id,
                        'new_academic_year' => $toAcademicYearLabel,
                        'new_mode_of_course' => $targetClass->mode_of_course ?? $academicRecord->mode_of_course,
                        'new_mode_type' => $targetClass->mode_type ?? $academicRecord->mode_type,
                        
                        'promotion_type' => $promotionType,
                        'promoted_by' => $currentUser->id,
                        'promoted_at' => now()
                    ]);

                    // Update academic record
                    $academicUpdate = [
                        'course_subtype_id' => $request->to_class_id,
                        'course_subtype' => $targetClass->sub_type,
                        'department_id' => $targetClass->department_id,
                        'department' => $targetDepartment->department ?? null,
                        'academic_year' => $toAcademicYearLabel,
                        'academic_year_id' => $targetCourseFee->academic_year_id,
                        'batch_id' => $targetCourseFee->batch_id,
                        'batch' => $targetCourseFee->batch,
                    ];

                    if ($request->filled('to_section_id')) {
                        $academicUpdate['section_id'] = $request->to_section_id;
                    }

                    if ($targetClass->finacp_merchant_sub_category_id) {
                        $academicUpdate['course_type_id'] = $targetClass->finacp_merchant_sub_category_id;
                        $academicUpdate['course_type'] = $targetClass->course_type;
                    }

                    if ($targetClass->mode_of_course) {
                        $academicUpdate['mode_of_course'] = $targetClass->mode_of_course;
                    }
                    if ($targetClass->mode_type) {
                        $academicUpdate['mode_type'] = $targetClass->mode_type;
                    }

                    if ($targetClass->department_category_id) {
                        $academicUpdate['department_category_id'] = $targetClass->department_category_id;
                    }

                    $academicRecord->update($academicUpdate);
                    
                    // UPDATE STUDENT STATUS FROM 'new' TO 'old'
                    if ($student->student_status === 'new') {
                        $student->update(['student_status' => 'old']);
                    }
                    
                    $promotedCount++;

                } catch (\Exception $e) {
                    $failedStudents[] = [
                        'id' => $academicRecord->student_hash_id,
                        'name' => $studentLabel ?? $academicRecord->student_hash_id,
                        'reason' => 'Error: ' . $e->getMessage()
                    ];
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully promoted {$promotedCount} students from academic year {$fromAcademicYearLabel} to {$toAcademicYearLabel}",
                'promoted_count' => $promotedCount,
                'fee_pending_count' => count($feePendingStudents),
                'failed_count' => count($failedStudents),
                'fee_pending_students' => $feePendingStudents,
                'failed_students' => $failedStudents,
                'department_changed' => ($sourceClass->department_id != $targetClass->department_id),
                'product_changed' => ($sourceClass->product_id != $targetClass->product_id),
                'from_academic_year' => $fromAcademicYearLabel,
                'to_academic_year' => $toAcademicYearLabel
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Promotion failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate if promotion is allowed between classes
     */
    public function validatePromotion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_class_id' => 'required',
            'to_class_id' => 'required',
            'from_academic_year' => 'required',
            'to_academic_year' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid request - academic years required']);
        }

        $context = $this->getInstituteBranchContext();

        $sourceClass = DB::table('product_details')
            ->where('product_id', $request->from_class_id)
            ->where('institute_id', $context['institute_id'])
            ->first();

        $targetClass = DB::table('product_details')
            ->where('product_id', $request->to_class_id)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$sourceClass || !$targetClass) {
            return response()->json(['success' => false, 'message' => 'Classes not found']);
        }

        $fromAcademicYearLabel = $request->from_academic_year . '-' . ($request->from_academic_year + 1);
        $toAcademicYearLabel = $request->to_academic_year . '-' . ($request->to_academic_year + 1);

        // Check if it's the same academic year
        $isSameAcademicYear = ($fromAcademicYearLabel == $toAcademicYearLabel);
        
        // Check if it's the same product (course)
        $isSameProduct = ($sourceClass->product_id == $targetClass->product_id);
        
        // Check if department changed
        $departmentChange = ($sourceClass->department_id != $targetClass->department_id);
        
        // Check course length for same product promotion
        $isMultiYearCourse = false;
        if ($isSameProduct && $sourceClass) {
            // Multi-year course: course_length > 1 (e.g., BCA has 3 years)
            // Single-year course: course_length = 1 (e.g., Class 1, Class 2 are separate products)
            $isMultiYearCourse = ($sourceClass->course_length > 1);
        }
        
        // Determine promotion type and validation status
        $isValid = true;
        $warningMessage = null;
        $promotionType = null;
        
        if ($isSameProduct && $isSameAcademicYear) {
            // Same course, same year - NEVER allowed
            $isValid = false;
            $warningMessage = 'Cannot promote students to the same class in the same academic year.';
        } elseif ($isSameProduct && !$isSameAcademicYear && $isMultiYearCourse) {
            // Same course, different year, MULTI-YEAR course - ALLOWED (BCA 1st -> BCA 2nd)
            $promotionType = 'vertical_progression';
            $warningMessage = '✓ Students will move to the next year of the same course.';
        } elseif ($isSameProduct && !$isSameAcademicYear && !$isMultiYearCourse) {
            // Same course, different year, SINGLE-YEAR course - NOT ALLOWED
            $isValid = false;
            $warningMessage = '⚠ Students cannot be promoted to the same class. Please select the next class (e.g., ' . $this->getNextClassLevel($sourceClass->sub_type) . ') as the target class.';
        } elseif (!$isSameProduct && $departmentChange) {
            // Different course, different department - Cross Department
            $promotionType = 'cross_department';
            $warningMessage = '⚠ Warning: This will move students to a different department.';
        } elseif (!$isSameProduct && !$departmentChange) {
            // Different course, same department - Horizontal Movement
            $promotionType = 'horizontal_movement';
            $warningMessage = 'ℹ Students will change course but remain in the same department.';
        }

        return response()->json([
            'success' => $isValid,
            'is_valid' => $isValid,
            'department_change' => $departmentChange,
            'is_same_product' => $isSameProduct,
            'is_same_academic_year' => $isSameAcademicYear,
            'is_multi_year_course' => $isMultiYearCourse,
            'course_length' => $sourceClass->course_length ?? 1,
            'source_department_id' => $sourceClass->department_id,
            'target_department_id' => $targetClass->department_id,
            'source_product_id' => $sourceClass->product_id,
            'target_product_id' => $targetClass->product_id,
            'from_academic_year' => $fromAcademicYearLabel,
            'to_academic_year' => $toAcademicYearLabel,
            'promotion_type' => $promotionType,
            'message' => $warningMessage ?: 'Promotion is valid.'
        ]);
    }

    /**
     * Get promotion history for a student
     */
    public function getStudentPromotionHistory($studentHashId)
    {
        $logs = StudentAcademicDetailsLog::where('student_hash_id', $studentHashId)
            ->with('promoter')
            ->orderBy('promoted_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'history' => $logs
        ]);
    }
}