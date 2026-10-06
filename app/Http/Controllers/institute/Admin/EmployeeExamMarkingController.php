<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Traits\InstituteBranchAccess;
use App\Models\EmployeeDetails;
use App\Models\AssignSubjectsToEmployee;
use App\Models\ExamStructureOfflineExam;
use App\Models\StudentAcademicTransportDetails;
use App\Models\ProductDetails;
use App\Models\SubjectsCoursewise;
use App\Models\StudentParentDetails;
use App\Models\StudentExamMarks;
use App\Models\InstituteBasicDetails;
use App\Models\Departments;
use App\Models\GradeSystem;
use App\Models\DepartmentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeExamMarkingController extends Controller
{
    use InstituteBranchAccess;

    /**
     * Show employee exam marking dashboard
     */
   public function employeeExamMarkingView()
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        $user = Auth::user();
        $employee = EmployeeDetails::where('user_id', $user->id)->firstOrFail();
        
        // Get employee's assigned subjects
        $assignedSubjects = $this->getAssignedSubjects($employee->employee_id, $context);
        
        // Get default grade system (NO examId needed here - this is dashboard)
        $gradeSystemData = $this->getGradeSystemForExam(null); // Pass null for default grade system
        
        // Check for success message in session
        $successMessage = session('exam_mark_success');

        // FIX: Pass correct data for dashboard view
        return view('instituteAdmin.ExamStructure.EmployeeExamMarking', [
            'employee' => $employee,
            'assignedSubjects' => $assignedSubjects,
            'today' => Carbon::today()->format('Y-m-d'),
            'successMessage' => $successMessage,
            'gradeSystemData' => $gradeSystemData  // Add this
        ]);
    }

    /**
     * Get employee's assigned subjects with exams
     */
    public function getEmployeeExams(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 401);
        }

        $user = Auth::user();
        $employee = EmployeeDetails::where('user_id', $user->id)->firstOrFail();
        
        // Get employee's assigned subjects
        $assignedSubjects = AssignSubjectsToEmployee::with(['subject' => function($query) {
            return $query->select('subject_id', 'subject_name');
        }, 'course' => function($query) {
            return $query->select('product_id', 'course_type');
        }, 'department' => function($query) {
            return $query->select('department_id', 'department');
        }])
            ->where('employee_id', $employee->employee_id)
            ->where('institute_id', $context['institute_id'])
            ->where('status', 'active')
            // ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            //     return $query->where('branch_id', $context['branch_id']);
            // }, function($query) {
            //     return $query->whereNull('branch_id');
            // })
            ->get();
     
        if ($assignedSubjects->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No subjects assigned to you.'
            ], 404);
        }

        // Get subject IDs from assignments
        $subjectIds = $assignedSubjects->pluck('subject_id')->unique()->toArray();
        
        // Get exams for these subjects
        $query = ExamStructureOfflineExam::with([
            'department' => function($query) {
                return $query->select('department_id', 'department');
            },
            'course' => function($query) {
                return $query->select('product_id', 'course_type');
            },
            'subject' => function($query) {
                return $query->select('subject_id', 'subject_name');
            },
            'departmentCategory' => function($query) {
                return $query->select('department_category_id', 'category_name');
            }
        ])
        ->where('institute_id', $context['institute_id'])
        ->whereIn('subject_id', $subjectIds)
        ->where('is_published', true)
        ->where('status', '!=', 'cancelled');

        // Apply filters if provided
        if ($request->has('date_from') && $request->date_from != '') {
            $query->where('exam_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->where('exam_date', '<=', $request->date_to);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by employee's sections
        $employeeSectionIds = [];
        foreach ($assignedSubjects as $assignment) {
            if ($assignment->section_id) {
                $employeeSectionIds = array_merge(
                    $employeeSectionIds,
                    explode(',', $assignment->section_id)
                );
            }
        }
        
        if (!empty($employeeSectionIds)) {
            $query->where(function($q) use ($employeeSectionIds) {
                foreach ($employeeSectionIds as $sectionId) {
                    $q->orWhere('section_id', 'like', "%{$sectionId}%");
                }
            });
        }

        // Order by date
        $exams = $query->orderBy('exam_date', 'asc')
                      ->orderBy('start_time', 'asc')
                      ->get();

        // Format exam data
        $formattedExams = $exams->map(function($exam) use ($assignedSubjects, $employeeSectionIds) {
            // Check if this exam is for employee's specific sections
            $examSectionIds = explode(',', $exam->section_id);
            $employeeSections = array_intersect($examSectionIds, $employeeSectionIds);
            
            // Get section names
            $sectionNames = $this->getSectionNamesForExam($exam, $employeeSections);
            
            // Check if marks have been entered
            $marksEntered = StudentExamMarks::where('exam_id', $exam->exam_id)
                ->where('institute_id', $exam->institute_id)
                ->where('marked_by', Auth::id())
                ->exists();
            
            // Count total students for this exam
            $totalStudents = $this->getTotalStudentsForExam($exam, $employeeSections);
            
            // Count students with marks entered
            $markedStudents = StudentExamMarks::where('exam_id', $exam->exam_id)
                ->where('institute_id', $exam->institute_id)
                ->where('marked_by', Auth::id())
                ->count();
            
            return [
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
                'department_name' => $exam->department->department ?? 'N/A',
                'course_name' => $exam->course->course_type ?? 'N/A',
                'sections' => $sectionNames,
                'section_ids' => $employeeSections,
                'is_for_employee' => !empty($employeeSections),
                'remarks' => $exam->remarks,
                'is_published' => $exam->is_published,
                'marks_entered' => $marksEntered,
                'total_students' => $totalStudents,
                'marked_students' => $markedStudents,
                'progress_percentage' => $totalStudents > 0 ? round(($markedStudents / $totalStudents) * 100, 2) : 0,
                'can_mark_attendance' => $this->canMarkAttendance($exam)
            ];
        });

        // Filter exams that are actually for this employee
        $employeeExams = $formattedExams->filter(function($exam) {
            return $exam['is_for_employee'];
        })->values();

        return response()->json([
            'success' => true,
            'employee' => [
                'name' => $employee->name,
                'employee_id' => $employee->employee_id,
                'designation' => $employee->designation ?? 'N/A'
            ],
            'exams' => $employeeExams,
            'total_exams' => $employeeExams->count(),
            'total_subjects' => $assignedSubjects->count(),
            'message' => $employeeExams->count() > 0 
                ? 'Exams loaded successfully' 
                : 'No exams found for your assigned subjects'
        ]);
    }

    /**
     * Get students for exam marking
     */
    public function getStudentsForExamMarking($examId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 401);
        }

        $user = Auth::user();
        $employee = EmployeeDetails::where('user_id', $user->id)->firstOrFail();

        // Get exam details
        $exam = ExamStructureOfflineExam::with([
            'subject' => function($query) {
                return $query->select('subject_id', 'subject_name');
            },
            'department' => function($query) {
                return $query->select('department_id', 'department');
            },
            'course' => function($query) {
                return $query->select('product_id', 'course_type');
            }
        ])
            ->where('exam_id', $examId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found.'
            ], 404);
        }

        // Check if employee is assigned to this subject
        $isAssigned = AssignSubjectsToEmployee::where('employee_id', $employee->employee_id)
            ->where('subject_id', $exam->subject_id)
            ->where('institute_id', $context['institute_id'])
            ->where('status', 'active')
            // ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            //     return $query->where('branch_id', $context['branch_id']);
            // }, function($query) {
            //     return $query->whereNull('branch_id');
            // })
            ->exists();

        if (!$isAssigned) {
            return response()->json([
                'success' => false,
                'message' => 'You are not assigned to this subject.'
            ], 403);
        }

        // Get exam sections
        $examSectionIds = explode(',', $exam->section_id);
        
        // Get employee's assigned sections for this subject
        $assignedSubject = AssignSubjectsToEmployee::where('employee_id', $employee->employee_id)
            ->where('subject_id', $exam->subject_id)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        $employeeSectionIds = $assignedSubject->section_id ? explode(',', $assignedSubject->section_id) : [];
        
        // Intersection of exam sections and employee sections
        $eligibleSections = array_intersect($examSectionIds, $employeeSectionIds);
        
        if (empty($eligibleSections)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not assigned to any sections for this exam.'
            ], 403);
        }

        // Get students for these sections
        $students = $this->getStudentsForExam($exam, $eligibleSections, $context);
        
        // Get existing marks
        $existingMarks = StudentExamMarks::where('exam_id', $examId)
            ->where('institute_id', $context['institute_id'])
            ->whereIn('student_hash_id', $students->pluck('student_hash_id'))
            ->get()
            ->keyBy('student_hash_id');

        // Format student data
        $formattedStudents = $students->map(function($student) use ($exam, $existingMarks) {
            $existingMark = $existingMarks[$student->student_hash_id] ?? null;
            
            return [
                'student_hash_id' => $student->student_hash_id,
                'registration_number' => $student->registration_number,
                'student_name' => $student->first_name . ' ' . $student->last_name,
                'section_id' => $student->section_id,
                'section_name' => $student->section_name ?? 'N/A',
                'roll_number' => $student->roll_number ?? 'N/A',
                'existing_marks' => $existingMark ? [
                    'obtained_marks' => $existingMark->obtained_marks,
                    'grade' => $existingMark->grade,
                    'remarks' => $existingMark->remarks,
                    'marked_at' => $existingMark->created_at->format('Y-m-d H:i:s')
                ] : null,
                'is_marked' => !is_null($existingMark)
            ];
        });
        
        // Get section names
        $sectionNames = $this->getSectionNames($eligibleSections, $exam->subtype_id, $context);

        return response()->json([
            'success' => true,
            'exam' => [
                'exam_id' => $exam->exam_id,
                'exam_name' => $exam->exam_name,
                'subject_name' => $exam->subject->subject_name ?? 'N/A',
                'exam_date' => $exam->exam_date,
                'exam_date_formatted' => date('l, F j, Y', strtotime($exam->exam_date)),
                'total_marks' => $exam->total_marks,
                'passing_marks' => $exam->passing_marks,
                'passing_percentage' => $exam->total_marks > 0 ? 
                    round(($exam->passing_marks / $exam->total_marks) * 100, 2) : 0,
                'status' => $exam->status,
                'department_name' => $exam->department->department ?? 'N/A',
                'course_name' => $exam->course->course_type ?? 'N/A',
                'sections' => $sectionNames,
                'section_ids' => $eligibleSections,
                'remarks' => $exam->remarks
            ],
            'students' => $formattedStudents,
            'total_students' => $students->count(),
            'marked_students' => $existingMarks->count(),
            'employee' => [
                'name' => $employee->name,
                'employee_id' => $employee->employee_id
            ]
        ]);
    }

    /**
     * Save exam marks for students
     */
    public function saveExamMarks(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 401);
        }

        $request->validate([
            'exam_id' => 'required|string',
            'marks' => 'required|array',
            'marks.*.student_hash_id' => 'required|string',
            'marks.*.obtained_marks' => 'required|numeric|min:0',
            'marks.*.remarks' => 'nullable|string|max:500'
        ]);

        $user = Auth::user();
        $employee = EmployeeDetails::where('user_id', $user->id)->firstOrFail();

        // Get exam details
        $exam = ExamStructureOfflineExam::where('exam_id', $request->exam_id)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found.'
            ], 404);
        }

        // Check if employee is assigned to this subject
        $isAssigned = AssignSubjectsToEmployee::where('employee_id', $employee->employee_id)
            ->where('subject_id', $exam->subject_id)
            ->where('institute_id', $context['institute_id'])
            ->where('status', 'active')
            // ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            //     return $query->where('branch_id', $context['branch_id']);
            // }, function($query) {
            //     return $query->whereNull('branch_id');
            // })
            ->exists();

        if (!$isAssigned) {
            return response()->json([
                'success' => false,
                'message' => 'You are not assigned to this subject.'
            ], 403);
        }

        // Check if exam is still open for marking
        if (!$this->canMarkAttendance($exam)) {
            return response()->json([
                'success' => false,
                'message' => 'Exam marking is closed. You can only mark attendance for ongoing or recently completed exams.'
            ], 403);
        }
        
        // Check if exam can be marked (today or any past date, NOT upcoming)
        $examDate = Carbon::parse($exam->exam_date);
        $today = Carbon::today();
        
        if ($examDate->greaterThan($today)) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot mark upcoming exams. This exam is scheduled for ' . 
                            date('F j, Y', strtotime($exam->exam_date))
            ], 403);
        }
        
        $successCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($request->marks as $markData) {
            // Validate marks don't exceed total marks
            if ($markData['obtained_marks'] > $exam->total_marks) {
                $errors[] = "Marks for student {$markData['student_hash_id']} exceed total marks ({$exam->total_marks})";
                $failedCount++;
                continue;
            }

            // Calculate grade
            $grade = $this->calculateGrade(
                $markData['obtained_marks'],
                $exam->total_marks,
                $exam->passing_marks,
                $exam->exam_id  // Pass exam ID to get grade system
            );

            // Check if marks already exist
            $existingMark = StudentExamMarks::where('exam_id', $request->exam_id)
                ->where('student_hash_id', $markData['student_hash_id'])
                ->where('institute_id', $context['institute_id'])
                ->first();

            if ($existingMark) {
                // Update existing marks
                $existingMark->update([
                    'obtained_marks' => $markData['obtained_marks'],
                    'grade' => $grade,
                    'remarks' => $markData['remarks'] ?? null,
                    'updated_by' => $user->id,
                    'updated_at' => now()
                ]);
            } else {
                // Create new marks record
                StudentExamMarks::create([
                    'exam_id' => $request->exam_id,
                    'student_hash_id' => $markData['student_hash_id'],
                    'subject_id' => $exam->subject_id,
                    'institute_id' => $context['institute_id'],
                    'branch_id' => $context['branch_id'] ?? null,
                    'department_id' => $exam->department_id,
                    'course_id' => $exam->course_id,
                    'total_marks' => $exam->total_marks,
                    'obtained_marks' => $markData['obtained_marks'],
                    'passing_marks' => $exam->passing_marks,
                    'grade' => $grade,
                    'status' => 'submitted',
                    'marked_by' => $user->id,
                    'marked_date' => now(),
                    'remarks' => $markData['remarks'] ?? null,
                    'created_by' => $user->id,
                    'updated_by' => $user->id
                ]);
            }

            $successCount++;
        }

        // ADD THIS: Store success message in session
        session()->flash('exam_mark_success', [
            'message' => "Exam marks saved successfully! {$successCount} students marked.",
            'exam_id' => $request->exam_id,
            'success_count' => $successCount
        ]);

        // After saving marks, calculate updated stats
        $totalStudents = $this->getTotalStudentsForExam($exam, $eligibleSections);
        $markedStudents = StudentExamMarks::where('exam_id', $request->exam_id)
            ->where('institute_id', $context['institute_id'])
            ->where('marked_by', $user->id)
            ->count();

        return response()->json([
            'success' => true,
            'message' => "Marks saved successfully. {$successCount} records processed.",
            'data' => [
                'success_count' => $successCount,
                'failed_count' => $failedCount,
                'errors' => $errors,
                'exam_id' => $request->exam_id,
                'total_students' => $totalStudents,
                'marked_students' => $markedStudents,
                'progress_percentage' => $totalStudents > 0 ? round(($markedStudents / $totalStudents) * 100, 2) : 0
            ]
        ]);
    }

    /**
     * Get exam marks summary
     */
    public function getExamMarksSummary($examId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 401);
        }

        $user = Auth::user();
        
        // Get exam details
        $exam = ExamStructureOfflineExam::with([
            'subject' => function($query) {
                return $query->select('subject_id', 'subject_name');
            }
        ])
            ->where('exam_id', $examId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found.'
            ], 404);
        }

        // Get marks statistics
        $marksSummary = StudentExamMarks::where('exam_id', $examId)
            ->where('institute_id', $context['institute_id'])
            ->selectRaw('
                COUNT(*) as total_students,
                SUM(CASE WHEN obtained_marks >= passing_marks THEN 1 ELSE 0 END) as passed,
                SUM(CASE WHEN obtained_marks < passing_marks THEN 1 ELSE 0 END) as failed,
                AVG(obtained_marks) as average_marks,
                MAX(obtained_marks) as highest_marks,
                MIN(obtained_marks) as lowest_marks
            ')
            ->first();

        // Get grade distribution
        $gradeDistribution = StudentExamMarks::where('exam_id', $examId)
            ->where('institute_id', $context['institute_id'])
            ->select('grade', DB::raw('COUNT(*) as count'))
            ->groupBy('grade')
            ->get()
            ->pluck('count', 'grade');

        return response()->json([
            'success' => true,
            'exam' => [
                'exam_name' => $exam->exam_name,
                'subject_name' => $exam->subject->subject_name ?? 'N/A',
                'total_marks' => $exam->total_marks,
                'passing_marks' => $exam->passing_marks
            ],
            'summary' => [
                'total_students' => $marksSummary->total_students ?? 0,
                'passed' => $marksSummary->passed ?? 0,
                'failed' => $marksSummary->failed ?? 0,
                'pass_percentage' => $marksSummary->total_students > 0 ? 
                    round(($marksSummary->passed / $marksSummary->total_students) * 100, 2) : 0,
                'average_marks' => round($marksSummary->average_marks ?? 0, 2),
                'highest_marks' => $marksSummary->highest_marks ?? 0,
                'lowest_marks' => $marksSummary->lowest_marks ?? 0
            ],
            'grade_distribution' => $gradeDistribution
        ]);
    }

    /**
     * Export exam marks to PDF/Excel
     */
    public function exportExamMarks($examId, $format = 'pdf')
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        $user = Auth::user();
        $employee = EmployeeDetails::where('user_id', $user->id)->firstOrFail();

        // Get exam details
        $exam = ExamStructureOfflineExam::with([
            'subject' => function($query) {
                return $query->select('subject_id', 'subject_name');
            },
            'department' => function($query) {
                return $query->select('department_id', 'department');
            },
            'course' => function($query) {
                return $query->select('product_id', 'course_type');
            }
        ])
            ->where('exam_id', $examId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$exam) {
            return redirect()->back()->with('error', 'Exam not found.');
        }

        // Get marks data
        $marks = StudentExamMarks::with(['student' => function($query) {
            return $query->select('student_hash_id', 'first_name', 'last_name', 'registration_number');
        }])
            ->where('exam_id', $examId)
            ->where('institute_id', $context['institute_id'])
            ->orderBy('student_hash_id')
            ->get();

        // You can implement PDF/Excel export here using libraries like DomPDF or Maatwebsite/Excel
        // For now, return a view

        return view('instituteAdmin.EmployeeFiles.ExportExamMarks', [
            'exam' => $exam,
            'marks' => $marks,
            'employee' => $employee,
            'export_format' => $format
        ]);
    }

    /**
     * Helper Methods
     */

    private function getAssignedSubjects($employeeId, $context)
    {
        
        return AssignSubjectsToEmployee::with([
            'subject' => function($query) {
                return $query->select('subject_id', 'subject_name');
            },
           
            'course' => function($query) {
                return $query->select('product_id', 'course_type');
            },
            'department' => function($query) {
                return $query->select('department_id', 'department');
            }
        ])
            ->where('employee_id', $employeeId)
            ->where('institute_id', $context['institute_id'])
            ->where('status', 'active')
            // ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            //     return $query->where('branch_id', $context['branch_id']);
            // }, function($query) {
            //     return $query->whereNull('branch_id');
            // })
            ->get()
            ->map(function($assignment) {
                return [
                    'subject_id' => $assignment->subject_id,
                    'subject_name' => $assignment->subject->subject_name ?? 'N/A',
                    'subject_display_name' => $assignment->subject_display_name,
                    'course_name' => $assignment->course->course_type ?? 'N/A',
                    'department_name' => $assignment->department->department ?? 'N/A',
                    'section_id' => $assignment->section_id,
                    'semester_id' => $assignment->semester_id
                ];
            });
    }

    private function getStudentsForExam($exam, $sectionIds, $context)
    {
        // Get students based on exam criteria
        $query = StudentAcademicTransportDetails::where('institute_id', $context['institute_id'])
            ->where('department_id', $exam->department_id)
            ->where(function($q) use ($exam) {
                $q->where('course_type', $exam->course_id)
                ->orWhere('course_subtype_id', $exam->subtype_id);
            // })
            // ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            //     return $query->where('branch_id', $context['branch_id']);
            // },
            // function($query) {
            //     return $query->whereNull('branch_id');
             });

        // Filter by sections
        if (!empty($sectionIds)) {
            $query->where(function($q) use ($sectionIds) {
                foreach ($sectionIds as $sectionId) {
                    $q->orWhere('section_id', 'like', "%{$sectionId}%");
                }
            });
        }

        $students = $query->get();
        
        // Get section data from course_fee_structures for this exam
        $sectionData = DB::table('course_fee_structures')
            ->where('product_id', $exam->subtype_id)
            ->where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->value('sections');
        
        // Parse section data to create a mapping
        $sectionMap = [];
        if ($sectionData) {
            $sections = json_decode($sectionData, true);
            if (is_array($sections)) {
                foreach ($sections as $section) {
                    if (isset($section['id']) && isset($section['name'])) {
                        $sectionMap[$section['id']] = $section['name'];
                    }
                }
            }
        }
        
        // Join with student parent details for names
        return $students->map(function($academic) use ($context, $sectionMap) {
            $student = StudentParentDetails::where('student_hash_id', $academic->student_hash_id)
                ->where('institute_id', $academic->institute_id)
                ->when($context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                }, function($query) {
                    return $query->whereNull('branch_id');
                })
                ->first();
            
            if ($student) {
                $academic->first_name = $student->first_name;
                $academic->last_name = $student->last_name;
                $academic->registration_number = $student->registration_number;
                $academic->roll_number = $student->roll_number;
            }
            
            // Get section name from mapping
            $academic->section_name = $sectionMap[$academic->section_id] ?? $academic->section_id;
            
            return $academic;
        });
    }

    private function getTotalStudentsForExam($exam, $sectionIds)
    {
        $context = $this->getInstituteBranchContext();
        
        $query = StudentAcademicTransportDetails::where('institute_id', $context['institute_id'])
            ->where('department_id', $exam->department_id)
            ->where(function($q) use ($exam) {
                $q->where('course_subtype_id', $exam->course_id)
                  ->orWhere('course_subtype_id', $exam->course_id);
            });

        // Filter by sections
        if (!empty($sectionIds)) {
            $query->where(function($q) use ($sectionIds) {
                foreach ($sectionIds as $sectionId) {
                    $q->orWhere('section_id', 'like', "%{$sectionId}%");
                }
            });
        }

        return $query->count();
    }

    private function getSectionNamesForExam($exam, $sectionIds)
    {
        if (empty($sectionIds)) {
            return 'All Sections';
        }
        
        // Try to get section data from course_fee_structures
        $sectionData = DB::table('course_fee_structures')
            ->where('product_id', $exam->subtype_id)
            ->where('institute_id', $exam->institute_id)
            ->when($exam->branch_id, function($query) use ($exam) {
                return $query->where('branch_id', $exam->branch_id);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->value('sections');
       
        $sectionNames = [];
        
        if ($sectionData) {
            $sections = json_decode($sectionData, true);
            if (is_array($sections)) {
                foreach ($sectionIds as $sectionId) {
                    foreach ($sections as $section) {
                        if (isset($section['id']) && $section['id'] === $sectionId) {
                            $sectionNames[] = $section['name'] ?? $sectionId;
                            break;
                        }
                    }
                    
                    // If not found in JSON, use the ID
                    if (!in_array($sectionId, array_column($sections, 'id'))) {
                        $sectionNames[] = "Section-{$sectionId}";
                    }
                }
            } else {
                $sectionNames = $sectionIds;
            }
        } else {
            $sectionNames = $sectionIds;
        }

        return implode(', ', $sectionNames);
    }

    private function getSectionNames($sectionIds, $courseId, $context)
    {
        
        if (empty($sectionIds)) {
            return 'All Sections';
        }

        // Get section data from course_fee_structures
        $sectionData = DB::table('course_fee_structures')
            ->where('product_id', $courseId)
            ->where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->value('sections');

        $sectionNames = [];
        
        if ($sectionData) {
            $sections = json_decode($sectionData, true);
            if (is_array($sections)) {
                foreach ($sectionIds as $sectionId) {
                    foreach ($sections as $section) {
                        if (isset($section['id']) && $section['id'] === $sectionId) {
                            $sectionNames[] = $section['name'] ?? $sectionId;
                            break;
                        }
                    }
                    
                    // If not found, use the ID
                    if (!in_array($sectionId, array_column($sections, 'id'))) {
                        $sectionNames[] = "Section-{$sectionId}";
                    }
                }
            } else {
                $sectionNames = $sectionIds;
            }
        } else {
            $sectionNames = $sectionIds;
        }

        return implode(', ', $sectionNames);
    }

    private function calculateGrade($obtainedMarks, $totalMarks, $passingMarks, $examId = null)
    {
        if ($totalMarks == 0) return 'N/A';
        
        $percentage = ($obtainedMarks / $totalMarks) * 100;
        
        // Get grade system for this exam or use default
        $gradeSystem = $this->getGradeSystemForExam($examId);
        
        if ($gradeSystem && isset($gradeSystem['grade_ranges'])) {
            // Decode grade_ranges if it's JSON string
            $gradeRanges = $gradeSystem['grade_ranges'];
            if (is_string($gradeRanges)) {
                $gradeRanges = json_decode($gradeRanges, true);
            }
            
            if (is_array($gradeRanges)) {
                foreach ($gradeRanges as $range) {
                    if ($percentage >= $range['min_percentage'] && $percentage <= $range['max_percentage']) {
                        return $range['grade'];
                    }
                }
            }
        }
        
        // Fallback to default grading
        return $this->getDefaultGrade($percentage, $obtainedMarks, $passingMarks);
    }

    private function getGradeSystemForExam($examId = null)
    {
        $context = $this->getInstituteBranchContext();
        
        if ($examId) {
            // Try to get grade system associated with the exam
            $exam = ExamStructureOfflineExam::find($examId);
            if ($exam && $exam->grade_system_id) {
                $gradeSystem = GradeSystem::where('id', $exam->grade_system_id)
                    ->where('institute_id', $context['institute_id'])
                    ->where('is_active', true)
                    ->first();
                    
                if ($gradeSystem) {
                    return [
                        'id' => $gradeSystem->id,
                        'name' => $gradeSystem->name,
                        'grade_ranges' => $gradeSystem->grade_ranges
                    ];
                }
            }
        }
        
        // Get default grade system for institute/branch
        $query = GradeSystem::where('institute_id', $context['institute_id'])
            ->where('is_active', true);
        
        if ($context['is_branch_admin'] && $context['branch_id']) {
            $query->where(function($q) use ($context) {
                $q->where('branch_id', $context['branch_id'])
                ->orWhereNull('branch_id');
            });
        } else {
            $query->whereNull('branch_id');
        }
        
        $gradeSystem = $query->where('is_default', true)
            ->first();
            
        if ($gradeSystem) {
            return [
                'id' => $gradeSystem->id,
                'name' => $gradeSystem->name,
                'grade_ranges' => $gradeSystem->grade_ranges
            ];
        }
        
        return null;
    }

    private function getDefaultGrade($percentage, $obtainedMarks, $passingMarks)
    {
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($obtainedMarks >= $passingMarks) return 'C';
        if ($percentage >= 40) return 'D';
        return 'F';
    }

    private function canMarkAttendance($exam)
    {
        $examDate = Carbon::parse($exam->exam_date);
        $today = Carbon::today();
        
        // Allow marking for today and all past exams
        return !$examDate->greaterThan($today);
    }

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
     * Show exam marking page
     */
    public function examMarkingPage($examId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        $user = Auth::user();
        $employee = EmployeeDetails::where('user_id', $user->id)->firstOrFail();
        
        // Get exam details
        $exam = ExamStructureOfflineExam::with([
            'subject' => function($query) {
                return $query->select('subject_id', 'subject_name');
            },
            'department' => function($query) {
                return $query->select('department_id', 'department');
            },
            'course' => function($query) {
                return $query->select('product_id', 'course_type');
            }
        ])
            ->where('exam_id', $examId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$exam) {
            return redirect()->route('employee.exam.marking.view')->with('error', 'Exam not found.');
        }

        // Check if employee is assigned to this subject
        $isAssigned = AssignSubjectsToEmployee::where('employee_id', $employee->employee_id)
            ->where('subject_id', $exam->subject_id)
            ->where('institute_id', $context['institute_id'])
            ->where('status', 'active')
            // ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            //     return $query->where('branch_id', $context['branch_id']);
            // }, function($query) {
            //     return $query->whereNull('branch_id');
            // })
            ->exists();

        if (!$isAssigned) {
            return redirect()->route('employee.exam.marking.view')->with('error', 'You are not assigned to this subject.');
        }

        // Get employee's assigned sections for this subject
        $assignedSubject = AssignSubjectsToEmployee::where('employee_id', $employee->employee_id)
            ->where('subject_id', $exam->subject_id)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        $employeeSectionIds = $assignedSubject->section_id ? explode(',', $assignedSubject->section_id) : [];
        
        // Get exam sections
        $examSectionIds = explode(',', $exam->section_id);
        
        // Intersection of exam sections and employee sections
        $eligibleSections = array_intersect($examSectionIds, $employeeSectionIds);
        
        if (empty($eligibleSections)) {
            return redirect()->route('employee.exam.marking.view')->with('error', 'You are not assigned to any sections for this exam.');
        }

        // Get section names
        $sectionNames = $this->getSectionNames($eligibleSections, $exam->subtype_id, $context);
        
        // Get existing marks summary
        $marksSummary = StudentExamMarks::where('exam_id', $examId)
            ->where('institute_id', $context['institute_id'])
            ->selectRaw('
                COUNT(*) as total_marked,
                SUM(CASE WHEN obtained_marks >= passing_marks THEN 1 ELSE 0 END) as passed,
                SUM(CASE WHEN obtained_marks < passing_marks THEN 1 ELSE 0 END) as failed,
                AVG(obtained_marks) as average_marks
            ')
            ->first();
        
        // Get grade system data for this exam - DON'T dd() here, just get the data
        $gradeSystemData = $this->getGradeSystemForExam($examId);
          // Decode grade_ranges if it's a JSON string
        $decodedGradeRanges = [];
        if ($gradeSystemData && isset($gradeSystemData['grade_ranges'])) {
            $gradeRanges = $gradeSystemData['grade_ranges'];
            
            if (is_string($gradeRanges)) {
                $decodedGradeRanges = json_decode($gradeRanges, true);
            } elseif (is_array($gradeRanges)) {
                $decodedGradeRanges = $gradeRanges;
            }
            
            // Sort by min_percentage for display
            usort($decodedGradeRanges, function($a, $b) {
                return $b['min_percentage'] <=> $a['min_percentage']; // Descending
            });
            
            $gradeSystemData['grade_ranges'] = $decodedGradeRanges;
        }

        // Debug: Log the grade system data
        \Log::info('Grade System Data for Exam ' . $examId, ['data' => $gradeSystemData]);
        
        return view('instituteAdmin.ExamStructure.ExamMarkingPage', [
            'exam' => $exam,
            'employee' => $employee,
            'eligibleSections' => $eligibleSections,
            'sectionNames' => $sectionNames,
            'marksSummary' => $marksSummary,
            'examId' => $examId,
            'gradeSystemData' => $gradeSystemData  // Pass to view
        ]);
    }

    /**
     * Get students with marks for exam
     */
    public function getStudentsWithMarks(Request $request, $examId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 401);
        }

        // Get exam details
        $exam = ExamStructureOfflineExam::where('exam_id', $examId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found.'
            ], 404);
        }

        // Get students for this exam
        $students = $this->getStudentsForExam($exam, [], $context);
        
        // Apply filters
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $students = $students->filter(function($student) use ($searchTerm) {
                return stripos($student->first_name . ' ' . $student->last_name, $searchTerm) !== false ||
                    stripos($student->registration_number, $searchTerm) !== false ||
                    stripos($student->roll_number, $searchTerm) !== false;
            });
        }
        
        // Get existing marks
        $existingMarks = StudentExamMarks::where('exam_id', $examId)
            ->where('institute_id', $context['institute_id'])
            ->get()
            ->keyBy('student_hash_id');

        // Format student data
        $formattedStudents = $students->map(function($student) use ($exam, $existingMarks) {
            $existingMark = $existingMarks[$student->student_hash_id] ?? null;
            
            // Calculate grade if marks exist
            $grade = null;
            if ($existingMark) {
                $grade = $this->calculateGrade(
                    $existingMark->obtained_marks,
                    $exam->total_marks,
                    $exam->passing_marks
                );
            }
            
            return [
                'student_hash_id' => $student->student_hash_id,
                'registration_number' => $student->registration_number,
                'student_name' => $student->first_name . ' ' . $student->last_name,
                'section_name' => $student->section_name ?? 'N/A',
                'roll_number' => $student->roll_number ?? 'N/A',
                'existing_marks' => $existingMark ? [
                    'id' => $existingMark->id,
                    'obtained_marks' => $existingMark->obtained_marks,
                    'grade' => $grade,
                    'remarks' => $existingMark->remarks,
                    'marked_at' => $existingMark->created_at->format('Y-m-d H:i:s')
                ] : null,
                'is_marked' => !is_null($existingMark),
                'status' => $existingMark ? ($existingMark->obtained_marks >= $exam->passing_marks ? 'passed' : 'failed') : 'pending'
            ];
        });

        // Apply status filter
        if ($request->has('status') && $request->status != 'all') {
            $formattedStudents = $formattedStudents->filter(function($student) use ($request) {
                return $student['status'] === $request->status;
            });
        }

        return response()->json([
            'success' => true,
            'students' => $formattedStudents->values(),
            'total_students' => $formattedStudents->count(),
            'exam' => [
                'total_marks' => $exam->total_marks,
                'passing_marks' => $exam->passing_marks
            ]
        ]);
    }

    /**
     * Update exam marks for a specific student
     */
    public function updateExamMarks(Request $request, $studentHashId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 401);
        }

        $request->validate([
            'exam_id' => 'required|string',
            'obtained_marks' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:500'
        ]);

        $user = Auth::user();
        $employee = EmployeeDetails::where('user_id', $user->id)->firstOrFail();

        // Get exam details
        $exam = ExamStructureOfflineExam::where('exam_id', $request->exam_id)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found.'
            ], 404);
        }

        // Validate marks don't exceed total marks
        if ($request->obtained_marks > $exam->total_marks) {
            return response()->json([
                'success' => false,
                'message' => "Marks cannot exceed total marks ({$exam->total_marks})"
            ], 422);
        }

        // Calculate grade
         $grade = $this->calculateGrade(
            $request->obtained_marks,
            $exam->total_marks,
            $exam->passing_marks,
            $exam->exam_id  // Pass exam ID
        );

        // Check if marks already exist
        $existingMark = StudentExamMarks::where('exam_id', $request->exam_id)
            ->where('student_hash_id', $studentHashId)
            ->where('institute_id', $context['institute_id'])
            ->first();
    
        if ($existingMark) {
            // Update existing marks
        StudentExamMarks::where('id', $existingMark->id)->update([
                'obtained_marks' => $request->obtained_marks,
                'grade' => $grade,
                'remarks' => $request->remarks,
                'updated_by' => $user->id,
            ]);

            $action = 'updated';
        } else {
        
            // Create new marks record
            StudentExamMarks::create([
                'exam_id' => $request->exam_id,
                'student_hash_id' => $studentHashId,
                'subject_id' => $exam->subject_id,
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['branch_id'] ?? null,
                'department_id' => $exam->department_id,
                'course_id' => $exam->course_id,
                'total_marks' => $exam->total_marks,
                'obtained_marks' => $request->obtained_marks,
                'passing_marks' => $exam->passing_marks,
                'grade' => $grade,
                'status' => 'submitted',
                'marked_by' => $user->id,
                'marked_date' => now(),
                'remarks' => $request->remarks ?? null,
                'created_by' => $user->id,
                'updated_by' => $user->id
            ]);
            
            $action = 'added';
        }

        return response()->json([
            'success' => true,
            'message' => "Marks {$action} successfully",
            'data' => [
                'grade' => $grade,
                'status' => $request->obtained_marks >= $exam->passing_marks ? 'passed' : 'failed'
            ]
        ]);
    }
    /**
     * Bulk update exam marks - FIXED VERSION
     */
    public function bulkUpdateExamMarks(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 401);
        }

        $request->validate([
            'exam_id' => 'required|string',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'required|string|exists:student_parent_details,student_hash_id',
            'marks' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:500'
        ]);

        $user = Auth::user();
        $employee = EmployeeDetails::where('user_id', $user->id)->firstOrFail();

        // Get exam details
        $exam = ExamStructureOfflineExam::where('exam_id', $request->exam_id)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found.'
            ], 404);
        }

        // Validate marks don't exceed total marks
        if ($request->marks > $exam->total_marks) {
            return response()->json([
                'success' => false,
                'message' => "Marks cannot exceed total marks ({$exam->total_marks})"
            ], 422);
        }

        // Calculate grade
          $grade = $this->calculateGrade(
            $request->marks,
            $exam->total_marks,
            $exam->passing_marks,
            $exam->exam_id  // Pass exam ID
        );

        $successCount = 0;
        $failedCount = 0;
        $errors = [];

            foreach ($request->student_ids as $studentHashId) {
                // try {
                    // Verify student exists and is enrolled
                    $studentExists = StudentParentDetails::where('student_hash_id', $studentHashId)
                        ->where('institute_id', $context['institute_id'])
                        ->exists();
                        
                    if (!$studentExists) {
                        $errors[] = "Student {$studentHashId} not found";
                        $failedCount++;
                        continue;
                    }

                    // Check if marks already exist
                    $existingMark = StudentExamMarks::where('exam_id', $request->exam_id)
                        ->where('student_hash_id', $studentHashId)
                        ->where('institute_id', $context['institute_id'])
                        ->first();

                    if ($existingMark) {
                        // Update existing marks
                        $existingMark->update([
                            'obtained_marks' => $request->marks,
                            'grade' => $grade,
                            'remarks' => $request->remarks ?? $existingMark->remarks,
                            'updated_by' => $user->id,
                            'updated_at' => now()
                        ]);
                    } else {
                        // Create new marks record
                        StudentExamMarks::create([
                            'exam_id' => $request->exam_id,
                            'student_hash_id' => $studentHashId,
                            'subject_id' => $exam->subject_id,
                            'institute_id' => $context['institute_id'],
                            'branch_id' => $context['branch_id'] ?? null,
                            'department_id' => $exam->department_id,
                            'course_id' => $exam->course_id,
                            'total_marks' => $exam->total_marks,
                            'obtained_marks' => $request->marks,
                            'passing_marks' => $exam->passing_marks,
                            'grade' => $grade,
                            'status' => 'submitted',
                            'marked_by' => $user->id,
                            'marked_date' => now(),
                            'remarks' => $request->remarks ?? null,
                            'created_by' => $user->id,
                            'updated_by' => $user->id
                        ]);
                    }

                    $successCount++;
            }
            
            return response()->json([
                'success' => true,
                'message' => "Bulk update completed. {$successCount} successful, {$failedCount} failed.",
                'data' => [
                    'success_count' => $successCount,
                    'failed_count' => $failedCount,
                    'grade' => $grade,
                    'status' => $request->marks >= $exam->passing_marks ? 'passed' : 'failed',
                    'errors' => $errors
                ]
            ]);           
    }

    /**
     * Get exam marking progress for dashboard
     */
    public function getExamProgress($examId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 401);
        }

        $user = Auth::user();
        $employee = EmployeeDetails::where('user_id', $user->id)->firstOrFail();
        
        // Get exam details
        $exam = ExamStructureOfflineExam::where('exam_id', $examId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found.'
            ], 404);
        }

        // Check if employee is assigned to this subject
        $isAssigned = AssignSubjectsToEmployee::where('employee_id', $employee->employee_id)
            ->where('subject_id', $exam->subject_id)
            ->where('institute_id', $context['institute_id'])
            ->where('status', 'active')
            ->exists();

        if (!$isAssigned) {
            return response()->json([
                'success' => false,
                'message' => 'You are not assigned to this subject.'
            ], 403);
        }

        // Get employee's assigned sections
        $assignedSubject = AssignSubjectsToEmployee::where('employee_id', $employee->employee_id)
            ->where('subject_id', $exam->subject_id)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        $employeeSectionIds = $assignedSubject->section_id ? explode(',', $assignedSubject->section_id) : [];
        
        // Get exam sections
        $examSectionIds = explode(',', $exam->section_id);
        
        // Intersection of exam sections and employee sections
        $eligibleSections = array_intersect($examSectionIds, $employeeSectionIds);
        
        // Get total students for this exam
        $totalStudents = $this->getTotalStudentsForExam($exam, $eligibleSections);
        
        // Get marked students count
        $markedStudents = StudentExamMarks::where('exam_id', $examId)
            ->where('institute_id', $context['institute_id'])
            ->where('marked_by', $user->id)
            ->count();

        return response()->json([
            'success' => true,
            'exam_id' => $examId,
            'total_students' => $totalStudents,
            'marked_students' => $markedStudents,
            'progress_percentage' => $totalStudents > 0 ? round(($markedStudents / $totalStudents) * 100, 2) : 0,
            'message' => 'Exam progress retrieved successfully'
        ]);
    }
}