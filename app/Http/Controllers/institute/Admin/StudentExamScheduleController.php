<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use App\Traits\InstituteBranchAccess;
use App\Models\StudentParentDetails;
use App\Models\StudentAcademicTransportDetails;
use App\Models\ExamStructureOfflineExam;
use App\Models\ProductDetails;
use App\Models\Departments;
use App\Models\DepartmentCategory;
use App\Models\SubjectsCoursewise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentExamScheduleController extends Controller
{
    use InstituteBranchAccess;

    /**
     * Show student exam schedule view
     */
    public function studentExamScheduleView()
    {
        $user = Auth::user();
        $student = StudentParentDetails::where('user_id', $user->id)->firstOrFail();
       
        // Get student details
        $studentDetails = StudentParentDetails::where('student_hash_id', $student->student_hash_id)
            ->first();

        if (!$studentDetails) {
            abort(404, 'Student details not found.');
        }

        return view('instituteAdmin.StudentFiles.StudentExamSchedule', [
            'student' => $studentDetails
        ]);
    }

    /**
     * Get student's exam schedule data
     */
    public function getStudentExamSchedule(Request $request)
    {
        $user = auth()->user();
        $student = StudentParentDetails::where('user_id', $user->id)->firstOrFail();
        
        $studentHashId = $student->student_hash_id;
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 401);
        }
        
        // Get student's academic details
        $academicDetails = StudentAcademicTransportDetails::where('student_hash_id', $studentHashId)
            ->where('institute_id', $context['institute_id'])
            // ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            //     return $query->where('branch_id', $context['branch_id']);
            // }, function($query) {
            //     return $query->whereNull('branch_id');
            // })
            ->first();

        if (!$academicDetails) {
            return response()->json([
                'success' => false,
                'message' => 'Student academic details not found.'
            ], 404);
        }

        // Get product_id (course/branch)
        $productId = $this->getProductIdFromAcademicDetails($academicDetails);

        if (!$productId) {
            return response()->json([
                'success' => false,
                'message' => 'Course details not found for student.'
            ], 404);
        }

        // Get student's department ID
        $departmentId = $academicDetails->department_id;
        
        if (!$departmentId) {
            return response()->json([
                'success' => false,
                'message' => 'Department information not found for student.'
            ], 404);
        }

        // Get student's section IDs from academic details
        $sectionIds = $this->getStudentSectionIds($academicDetails);

        // Get course details
        $course = ProductDetails::where('product_id', $productId)->first();
        
        // Build exam query
        $query = ExamStructureOfflineExam::with([
            'department',
            'course',
            'subject',
            'departmentCategory'
        ])
        ->where('institute_id', $context['institute_id'])
        ->where('status', '!=', 'cancelled') // Exclude cancelled exams
        ->when($context['is_branch_admin'] && $context['branch_id'], function ($query) use ($context) {
            $query->where(function ($q) use ($context) {
                $q->where('branch_id', $context['branch_id'])
                ->orWhereNull('branch_id');
            });
        });

        // Filter by student's course
        $query->where(function($q) use ($productId) {
            $q->where('course_id', $productId)
              ->orWhere('subtype_id', $productId);
        });

        // Filter by student's department
        $query->where('department_id', $departmentId);

        // Filter by student's sections
        if (!empty($sectionIds)) {
            $query->where(function($q) use ($sectionIds) {
                foreach ($sectionIds as $sectionId) {
                    $q->orWhere('section_id', 'like', "%{$sectionId}%");
                }
            });
        }

        // Apply date filters if provided
        if ($request->has('date_from') && $request->date_from != '') {
            $query->where('exam_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->where('exam_date', '<=', $request->date_to);
        }

        // Apply status filter if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Only show published exams for students
        $query->where('is_published', true);

        // Get upcoming exams (from today onwards)
        $today = date('Y-m-d');
        $query->where('exam_date', '>=', $today);

        // Order by date and time
        $exams = $query->orderBy('exam_date', 'asc')->orderBy('start_time', 'asc')->get();
        
          // Get section names
        $sectionNames = $this->getSectionNames($sectionIds);
        // Format exam data for response
        $formattedExams = $exams->map(function($exam) use ($sectionIds) {
            
            // Check if this exam is for student's specific sections
            $examSectionIds = explode(',', $exam->section_id);
            $studentSections = array_intersect($examSectionIds, $sectionIds);
            // Get section names for this student
            $studentSectionNames = $this->getSectionNames($studentSections);
          
            return [
                'exam_structure_id' => $exam->exam_id,
                'exam_id' => $exam->exam_id,
                'exam_name' => $exam->exam_name,
                'subject_id' => $exam->subject_id,
                'subject_name' => $exam->subject->subject_name ?? 'N/A',
                'exam_date' => $exam->exam_date,
                'exam_date_formatted' => date('D, d M Y', strtotime($exam->exam_date)),
                'start_time' => $exam->start_time,
                'start_time_formatted' => $exam->start_time ? date('h:i A', strtotime($exam->start_time)) : 'N/A',
                'end_time' => $exam->end_time,
                'end_time_formatted' => $exam->end_time ? date('h:i A', strtotime($exam->end_time)) : 'N/A',
                'duration_minutes' => $exam->duration_minutes,
                'duration_formatted' => $this->formatDuration($exam->duration_minutes),
                'classroom_id' => $exam->classroom_id,
                'total_marks' => $exam->total_marks,
                'passing_marks' => $exam->passing_marks,
                'status' => $exam->status,
                'status_formatted' => ucfirst($exam->status),
                'department_name' => $exam->department->department,
                'course_name' => $exam->course->course_type,
                'sections' => $exam->section_id,
                'student_sections' => $studentSectionNames, // Use names instead of IDs
                'student_section_ids' => $studentSections, // Keep IDs if needed
                'is_for_student' => !empty($studentSections),
                'remarks' => $exam->remarks,
                'is_published' => $exam->is_published,
                'created_at' => $exam->created_at,
                'updated_at' => $exam->updated_at
            ];
        });
  
        // Filter exams that are actually for this student
        $studentExams = $formattedExams->filter(function($exam) {
            return $exam['is_for_student'];
        })->values();

        // Get student's basic details
        $basicDetails = StudentParentDetails::where('student_hash_id', $studentHashId)
            ->where('institute_id', $context['institute_id'])
            // ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            //     return $query->where('branch_id', $context['branch_id']);
            // }, function($query) {
            //     return $query->whereNull('branch_id');
            // })
            ->first();
       
        return response()->json([
            'success' => true,
            'student' => [
                'name' => $basicDetails->first_name . ' ' . $basicDetails->last_name ?? 
                           $student->first_name . ' ' . $student->last_name,
                'student_hash_id' => $studentHashId,
                'registration_number' => $academicDetails->registration_number ?? $student->registration_number,
                'course' => $course ? $course->course_type . ' - ' . $course->sub_type : 'N/A',
                'course_id' => $productId,
                'department' => $academicDetails->department,
                'sections' => $sectionNames, // Use names here
                'section_ids' => $sectionIds,
                'academic_year' => $academicDetails->academic_year ?? 'N/A'
            ],
            'exams' => $studentExams,
            'total_exams' => $studentExams->count(),
            'upcoming_exams' => $studentExams->where('exam_date', '>=', $today)->count(),
            'filtered_total' => $exams->count(),
            'message' => $studentExams->count() > 0 
                ? 'Exam schedule loaded successfully' 
                : 'No upcoming exams found for your course and sections'
        ]);
    }

    /**
     * Get product_id from academic details
     */
    private function getProductIdFromAcademicDetails($academicDetails)
    {
        // Method 1: If academic_transport_details has product_id directly
        if (isset($academicDetails->product_id) && $academicDetails->product_id) {
            return $academicDetails->product_id;
        }

        // Method 2: If you need to find product_id from other academic fields
        if (isset($academicDetails->course_subtype_id) && $academicDetails->course_subtype_id) {
            $product = ProductDetails::where('product_id', $academicDetails->course_subtype_id)
                ->first();
                
            if ($product) {
                return $product->product_id;
            }
        }

        // Method 3: If academic_transport_details has department_id and course_type
        if (isset($academicDetails->department_id) && isset($academicDetails->course_type)) {
            $product = ProductDetails::where('department_id', $academicDetails->department_id)
                ->where('course_type', $academicDetails->course_type)
                ->when($academicDetails->course_subtype_id, function($query, $subtypeId) {
                    return $query->where('product_id', $subtypeId);
                })
                ->first();
                
            if ($product) {
                return $product->product_id;
            }
        }

        return null;
    }

    /**
     * Get student's section IDs from academic details
     */
    private function getStudentSectionIds($academicDetails)
    {
        $sectionIds = [];
        
        // Check if section_id exists in academic details
        if (isset($academicDetails->section_id) && $academicDetails->section_id) {
            $sectionIds = explode(',', $academicDetails->section_id);
            $sectionIds = array_map('trim', $sectionIds);
            $sectionIds = array_filter($sectionIds);
        }
        
        // If no section_id found, check other possible fields
        if (empty($sectionIds)) {
            // Check for batch/section information in other fields
            $possibleSectionFields = ['batch', 'section', 'class_section', 'student_section'];
            
            foreach ($possibleSectionFields as $field) {
                if (isset($academicDetails->$field) && $academicDetails->$field) {
                    $sectionIds = [$academicDetails->$field];
                    break;
                }
            }
        }
        
        return $sectionIds;
    }

    private function getSectionNames($sectionIds)
{
    if (empty($sectionIds)) {
        return 'All Sections';
    }
    
    // Check if we already have academic details in scope
    if (isset($this->academicDetails) && $this->academicDetails && $this->academicDetails->sections) {
        return $this->getSectionNamesFromJson($this->academicDetails->sections, $sectionIds);
    }
    
    // If not, try to get it from the student
    $user = auth()->user();
    $student = StudentParentDetails::where('user_id', $user->id)->first();
    
    if (!$student) {
        return implode(', ', $sectionIds);
    }
    
    $context = $this->getInstituteBranchContext();
    
   $academicDetails = StudentAcademicTransportDetails::where('student_hash_id', $student->student_hash_id)
    ->where('institute_id', $context['institute_id'])
    ->when($context['is_branch_admin'] && $context['branch_id'], function ($query) use ($context) {
        $query->where(function ($q) use ($context) {
            $q->where('branch_id', $context['branch_id'])
              ->orWhereNull('branch_id');
        });
    })
    ->first();

    if (!$academicDetails || !$academicDetails->sections) {
        return implode(', ', $sectionIds);
    }
    
    return $this->getSectionNamesFromJson($academicDetails->sections, $sectionIds);
}

private function getSectionNamesFromJson($sectionsJson, $sectionIds)
{
    // Parse sections JSON
    $sectionData = json_decode($sectionsJson, true);
    
    if (!is_array($sectionData)) {
        return implode(', ', $sectionIds);
    }
    
    // Create a mapping of id -> name
    $sectionMap = [];
    foreach ($sectionData as $section) {
        if (isset($section['id']) && isset($section['name'])) {
            $sectionMap[$section['id']] = $section['name'];
        }
    }
    
    // Get names in order
    $names = [];
    foreach ($sectionIds as $id) {
        if (isset($sectionMap[$id])) {
            $names[] = $sectionMap[$id];
        } else {
            $names[] = "Section-$id";
        }
    }
    
    return implode(', ', $names);
}
    /**
     * Format time for display
     */
    private function formatTime($timeString)
    {
        if (!$timeString) return 'N/A';
        
        try {
            $time = date_create_from_format('H:i:s', $timeString);
            if (!$time) {
                $time = date_create_from_format('H:i', $timeString);
            }
            
            if ($time) {
                return date_format($time, 'h:i A');
            }
            
            return $timeString;
        } catch (\Exception $e) {
            return $timeString;
        }
    }

    /**
     * Format duration for display
     */
    private function formatDuration($minutes)
    {
        if (!$minutes) return 'N/A';
        
        if ($minutes < 60) {
            return $minutes . ' minutes';
        }
        
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        
        if ($remainingMinutes > 0) {
            return $hours . 'h ' . $remainingMinutes . 'm';
        }
        
        return $hours . ' hour' . ($hours > 1 ? 's' : '');
    }

    /**
     * Get exam details for a specific exam
     */
   /**
 * Get exam details for a specific exam
 */
public function getExamDetails($examId = null)
{
    
    if (!$examId) {
        return response()->json([
            'success' => false,
            'message' => 'Exam ID is required.'
        ], 400);
    }
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.'
            ], 401);
        }
        
        $student = StudentParentDetails::where('user_id', $user->id)->first();
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student record not found.'
            ], 404);
        }
        
        $studentHashId = $student->student_hash_id;
        $context = $this->getInstituteBranchContext();
        
        // Try to find the exam by different fields
        $exam = ExamStructureOfflineExam::with([
            'department', 
            'course', 
            'subject', 
            'departmentCategory'
        ])
        ->where(function($query) use ($examId) {
            // Try to find by exam_structure_id first
            $query->where('exam_id', $examId)
                  // Then try by exam_id
                  ->orWhere('exam_id', $examId)
                  // Then try by id (if numeric)
                  ->when(is_numeric($examId), function($q) use ($examId) {
                      $q->orWhere('id', $examId);
                  });
        })
        ->first();
        
        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found.'
            ], 404);
        }
        
        // Check if student has access to this exam
        $academicDetails = StudentAcademicTransportDetails::where('student_hash_id', $studentHashId)
            ->where('institute_id', $context['institute_id'])
            // ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            //     return $query->where('branch_id', $context['branch_id']);
            // }, function($query) {
            //     return $query->whereNull('branch_id');
            // })
            ->first();
        
        if (!$academicDetails) {
            return response()->json([
                'success' => false,
                'message' => 'Student academic details not found.'
            ], 404);
        }
        
        // Check if exam belongs to student's course and department
        $productId = $this->getProductIdFromAcademicDetails($academicDetails);
        $sectionIds = $this->getStudentSectionIds($academicDetails);
        
        $isAccessible = false;
        
        if ($exam->institute_id == $context['institute_id'] &&
            ($exam->course_id == $productId || $exam->subtype_id == $productId) &&
            $exam->department_id == $academicDetails->department_id) {
            
            // Check sections
            $examSectionIds = explode(',', $exam->section_id);
            $studentSections = array_intersect($examSectionIds, $sectionIds);
            
            if (!empty($studentSections)) {
                $isAccessible = true;
            }
        }
        
        if (!$isAccessible) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this exam.'
            ], 403);
        }
         $sectionNames = $this->getSectionNames($sectionIds);
        $studentSectionNames = !empty($studentSections) ? $this->getSectionNames($studentSections) : 'All Sections';
        
        // Format exam details
        $formattedExam = [
            'exam_structure_id' => $exam->exam_id,
            'exam_id' => $exam->exam_id,
            'exam_name' => $exam->exam_name,
            'subject_id' => $exam->subject_id,
            'subject_name' => $exam->subject->subject_name ?? 'N/A',
            'exam_date' => $exam->exam_date,
            'exam_date_formatted' => date('l, F j, Y', strtotime($exam->exam_date)),
            'start_time' => $exam->start_time,
           'start_time_formatted' => $exam->start_time ? date('h:i A', strtotime($exam->start_time)) : 'N/A',
            'end_time' => $exam->end_time,
            'end_time_formatted' => $exam->end_time ? date('h:i A', strtotime($exam->end_time)) : 'N/A',
            'duration_minutes' => $exam->duration_minutes,
            'duration_formatted' => $this->formatDuration($exam->duration_minutes),
            'classroom_id' => $exam->classroom_id,
            'classroom_details' => $exam->classroom_id,
            'total_marks' => $exam->total_marks,
            'passing_marks' => $exam->passing_marks,
            'passing_percentage' => $exam->total_marks > 0 ? 
                round(($exam->passing_marks / $exam->total_marks) * 100, 2) : 0,
            'status' => $exam->status,
            'status_formatted' => ucfirst($exam->status),
            'department_name' => $exam->department->department ?? 'N/A',
            'course_name' => $exam->course->finacp_merchant_sub_category_type ?? 'N/A',
            'department_category' => $exam->departmentCategory->category_name ?? 'N/A',
             'sections' => $exam->section_id,
            'student_sections' => $studentSectionNames,
            'section_names' => $sectionNames, // Add this for debugging
            'remarks' => $exam->remarks,
            'is_published' => $exam->is_published,
            'instructions' => $exam->instructions ?? 'No special instructions',
            'created_at' => $exam->created_at,
            'updated_at' => $exam->updated_at
        ];
        
        return response()->json([
            'success' => true,
            'exam' => $formattedExam,
            'message' => 'Exam details loaded successfully'
        ]);  
}
}
