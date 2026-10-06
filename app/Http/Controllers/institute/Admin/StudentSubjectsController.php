<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Traits\InstituteBranchAccess;
use App\Models\StudentSubjectAssignment;
use App\Models\SubjectsCoursewise;
use App\Models\SubSubject; // Add this
use App\Models\StudentParentDetails;
use App\Models\StudentAcademicTransportDetails;
use App\Models\ProductDetails;
use App\Models\Departments;
use App\Models\InstituteBasicDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentSubjectsController extends Controller
{
    use InstituteBranchAccess;

    public function getStudentSubjectsWithSyllabus()
{
    $user = auth()->user();
    $student = StudentParentDetails::where('user_id', $user->id)->firstOrFail();
    
    $studentHashId = $student->student_hash_id;
    $context = $this->getInstituteBranchContext();
    
    // Get student's academic details
    $academicDetails = \App\Models\StudentAcademicTransportDetails::where('student_hash_id', $studentHashId)
        ->where('institute_id', $context['institute_id'])
        ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            return $query->where('branch_id', $context['branch_id']);
        }, function($query) {
            return $query->whereNull('branch_id');
        })
        ->first();

    if (!$academicDetails) {
        return response()->json([
            'success' => false,
            'message' => 'Student academic details not found.'
        ], 404);
    }

    // Get product_id
    $productId = $this->getProductIdFromAcademicDetails($academicDetails);
    
    if (!$productId) {
        return response()->json([
            'success' => false,
            'message' => 'Course details not found for student.'
        ], 404);
    }

    $course = ProductDetails::where('product_id', $productId)->first();
    
    // Get student's basic details
    $basicDetails = StudentParentDetails::where('student_hash_id', $studentHashId)
        ->where('institute_id', $context['institute_id'])
        ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            return $query->where('branch_id', $context['branch_id']);
        }, function($query) {
            return $query->whereNull('branch_id');
        })
        ->first();

    // Get individually assigned subjects
    $assignedSubjects = StudentSubjectAssignment::where('student_hash_id', $studentHashId)
        ->where('institute_id', $context['institute_id'])
        ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            return $query->where('branch_id', $context['branch_id']);
        }, function($query) {
            return $query->whereNull('branch_id');
        })
        ->with(['subject' => function($query) {
            $query->select('id', 'subject_id', 'subject_name', 'semester_id');
        }])
        ->get();
  
    $mainSubjects = [];
    $subSubjects = [];

    // Get institute type to determine syllabus fetching logic
    $institute = InstituteBasicDetails::where('fincap_merchant_id', $context['institute_id'])->first();
    $instituteType = $institute->type ?? 'Institute';

    foreach ($assignedSubjects as $assignment) {
        if ($assignment->subject_type === 'sub_subject' && $assignment->sub_subject_id) {
            // Get sub-subject details
            $subSub = SubSubject::where('sub_subject_id', $assignment->sub_subject_id)
                ->where('subject_id', $assignment->subject_id)
                ->first();
                
            if ($subSub) {
                // Get syllabus for sub-subject
                $syllabus = $this->getSubjectSyllabus(
                    $assignment->subject_id,
                    $productId,
                    $context,
                    $assignment->semester_id ?? null
                );
                
                $subSubjects[] = [
                    'subject_id' => $assignment->subject_id,
                    'subject_name' => $assignment->subject->subject_name ?? 'N/A',
                    'sub_subject_id' => $subSub->sub_subject_id,
                    'sub_subject_name' => $subSub->sub_subject_name,
                    'assigned_date' => $assignment->assigned_date,
                    'assigned_individually' => true,
                    'syllabus' => $syllabus
                ];
            }
        } else {
            // Main subject
            $subject = $assignment->subject;
            if ($subject) {
                // Get syllabus for subject
                $syllabus = $this->getSubjectSyllabus(
                    $subject->subject_id,
                    $productId,
                    $context,
                    $subject->semester_id
                );
                
                $mainSubjects[] = [
                    'subject_id' => $subject->subject_id,
                    'subject_name' => $subject->subject_name,
                    'semester_id' => $subject->semester_id,
                    'assigned_date' => $assignment->assigned_date,
                    'assigned_individually' => true,
                    'syllabus' => $syllabus
                ];
            }
        }
    }

    // If NO individually assigned subjects, get ALL subjects from the course
    if (empty($mainSubjects) && empty($subSubjects) && $productId) {
        $courseSubjects = $this->getCourseSubjectsByAcademicDetails($academicDetails, $context, $productId);
        
        // Add syllabus to course subjects
        foreach ($courseSubjects['main_subjects'] as &$subject) {
            $subject['syllabus'] = $this->getSubjectSyllabus(
                $subject['subject_id'],
                $productId,
                $context,
                $subject['semester_id']
            );
        }
        
        foreach ($courseSubjects['sub_subjects'] as &$subSubject) {
            $subSubject['syllabus'] = $this->getSubjectSyllabus(
                $subSubject['subject_id'],
                $productId,
                $context,
                null // You might need to adjust this for sub-subjects
            );
        }
        
        $mainSubjects = $courseSubjects['main_subjects'];
        $subSubjects = $courseSubjects['sub_subjects'];
    }

    return response()->json([
        'success' => true,
        'data' => [
            'student' => [
                'name' => $basicDetails->first_name . ' ' . $basicDetails->last_name ?? 
                           $student->first_name . ' ' . $student->last_name,
                'student_hash_id' => $studentHashId,
                'registration_number' => $academicDetails->registration_number ?? $student->registration_number,
                'course' => $course ? $course->course_type . ' - ' . $course->sub_type : 'N/A',
                'course_id' => $productId
            ],
            'main_subjects' => $mainSubjects,
            'sub_subjects' => $subSubjects,
            'has_individual_assignments' => !empty($assignedSubjects),
            'total_subjects' => count($mainSubjects) + count($subSubjects),
            'source' => !empty($assignedSubjects) ? 'individual_assignments' : 'course_default',
            'institute_type' => $instituteType
        ]
    ]);
}


private function getSubjectSyllabus($subjectId, $courseId, $context, $semesterId = null)
{
    $syllabusQuery = \App\Models\Syllabus::where('subject_id', $subjectId)
        ->where('course_detail_id', $courseId)
        ->where('institute_id', $context['institute_id'])
        ->where('status', 'active')
        ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            return $query->where('branch_id', $context['branch_id']);
        }, function($query) {
            return $query->where(function($q) {
                $q->whereNull('branch_id')->orWhere('branch_id', '');
            });
        });
    
    if ($semesterId && $semesterId !== 'all_semesters') {
        $syllabusQuery->where(function($q) use ($semesterId) {
            $q->where('semester_id', $semesterId)
              ->orWhere('semester_id', 'all_semesters')
              ->orWhereNull('semester_id');
        });
    }
    
    return $syllabusQuery->orderBy('created_at', 'desc')->get()->map(function($syllabus) {
        return [
            'id' => $syllabus->id,
            'syllabus_id' => $syllabus->syllabus_id,
            'title' => $syllabus->title,
            'description' => $syllabus->description,
            'file_name' => $syllabus->file_name,
            'file_path' => $syllabus->file_path,
            'file_url' => $syllabus->file_path ? asset('storage/' . $syllabus->file_path) : null,
            'uploaded_date' => $syllabus->uploaded_date,
            'syllabus_for' => $syllabus->syllabus_for,
            'semester_id' => $syllabus->semester_id,
            'has_file' => !empty($syllabus->file_path)
        ];
    })->toArray(); // Convert to array
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

        // Method 4: Return null if not found
        return null;
    }

    /**
     * Get all subjects from course based on academic details
     */
    private function getCourseSubjectsByAcademicDetails($academicDetails, $context, $productId = null)
    {
        $mainSubjects = [];
        $subSubjects = [];
        
        // If productId is provided, use it directly
        if ($productId) {
            $courseSubjects = SubjectsCoursewise::where('course_detail_id', $productId)
                ->where('institute_id', $context['institute_id'])
                ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                }, function($query) {
                    return $query->whereNull('branch_id');
                })
                ->where('status', 'Active')
                ->with(['subSubjects' => function($query) {
                    $query->where('status', 'active');
                }])
                ->get();
        } else {
            // Alternative: Find subjects by matching academic details
            $courseSubjects = SubjectsCoursewise::where('institute_id', $academicDetails->institute_id)
                ->where(function($query) use ($academicDetails, $context) {
                    // Match branch
                    if ($context['is_branch_admin'] && $context['branch_id']) {
                        $query->where('branch_id', $context['branch_id']);
                    } else {
                        $query->whereNull('branch_id');
                    }
                    
                    // Try to match department if available
                    if (isset($academicDetails->department_id)) {
                        // This assumes you have department_id in subjects_coursewise
                        $query->where('department_id', $academicDetails->department_id);
                    }
                    
                    // Try to match course type if available
                    if (isset($academicDetails->course_type)) {
                        // You might need to join with product_details to get course_type
                        $query->whereHas('courseDetail', function($q) use ($academicDetails) {
                            $q->where('course_type', $academicDetails->course_type);
                        });
                    }
                })
                ->where('status', 'Active')
                ->with(['subSubjects' => function($query) {
                    $query->where('status', 'active');
                }])
                ->get();
        }

        foreach ($courseSubjects as $subject) {
            $mainSubjects[] = [
                'subject_id' => $subject->subject_id,
                'subject_name' => $subject->subject_name,
                'semester_id' => $subject->semester_id,
                'assigned_individually' => false,
                'assigned_date' => null,
                'course_source' => true
            ];

            // Collect sub-subjects
            foreach ($subject->subSubjects as $subSubject) {
                $subSubjects[] = [
                    'subject_id' => $subject->subject_id,
                    'subject_name' => $subject->subject_name,
                    'sub_subject_id' => $subSubject->sub_subject_id,
                    'sub_subject_name' => $subSubject->sub_subject_name,
                    'assigned_individually' => false,
                    'assigned_date' => null,
                    'course_source' => true
                ];
            }
        }

        return [
            'main_subjects' => $mainSubjects,
            'sub_subjects' => $subSubjects
        ];
    }

    public function studentSubjectsView()
    {
        $user = auth()->user();
        $student = StudentParentDetails::where('user_id', $user->id)->firstOrFail();
       
        // Get student details
        $studentDetails = StudentParentDetails::where('student_hash_id', $student->student_hash_id)
            ->first();

        if (!$studentDetails) {
            abort(404, 'Student details not found.');
        }

        return view('instituteAdmin.StudentFiles.StudentSubjects', [
            'student' => $studentDetails
        ]);
    } 
}
 
