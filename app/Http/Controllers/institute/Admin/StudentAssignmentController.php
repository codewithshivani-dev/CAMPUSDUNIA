<?php
namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignmentStudents;
use App\Models\AssignmentSubmission;
use App\Models\StudentParentDetails;
use App\Models\Assignment;
use App\Models\SubjectsCoursewise;
use App\Models\ProductDetails;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StudentAssignmentController extends Controller
{
    // Show student's assignments
    public function index()
    {
        $user = Auth::user();
        $student = StudentParentDetails::where('user_id', $user->id)->firstOrFail();
        $studentId = $student->student_hash_id;
        
        $assignments = AssignmentStudents::with(['assignment', 'assignment.files', 'submissions'])
            ->where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Transform files to include proper URLs
        $assignments->each(function ($assignmentStudent) {
            if ($assignmentStudent->assignment && $assignmentStudent->assignment->files) {
                $assignmentStudent->assignment->files->each(function ($file) {
                    $file->url = $this->getFileUrl($file->file_path);
                    $file->icon = $this->getFileIcon($file->file_type);
                    $file->previewable = in_array(strtolower($file->file_type), ['jpg', 'jpeg', 'png', 'gif', 'pdf']);
                });
            }
            
            // Transform submission files
            if ($assignmentStudent->submissions) {
                $assignmentStudent->submissions->each(function ($submission) {
                    $submission->url = $this->getFileUrl($submission->file_path);
                });
            }
        });

        return view('instituteAdmin.AssignmentsFiles.AssignmentsList', compact('assignments'));
    }

    // Show submit form
    public function submitForm(AssignmentStudents $assignmentStudents)
    {
        // Verify the student is authorized to view this assignment
        $user = Auth::user();
        $student = StudentParentDetails::where('user_id', $user->id)->firstOrFail();
        
        if ($assignmentStudents->student_id != $student->student_hash_id) {
            abort(403, 'You are not authorized to view this assignment.');
        }

        // Get assignment files with proper URLs
        $assignment = $assignmentStudents->assignment;
        $files = $assignment->files ?? collect();
        
        // Transform files to include URLs
        $files = $files->map(function($file) {
            $file->url = $this->getFileUrl($file->file_path);
            $file->icon = $this->getFileIcon($file->file_type);
            $file->previewable = in_array(strtolower($file->file_type), ['jpg', 'jpeg', 'png', 'gif', 'pdf']);
            return $file;
        });

        // Transform submission files
        $assignmentStudents->submissions->each(function ($submission) {
            $submission->url = $this->getFileUrl($submission->file_path);
        });

        return view('instituteAdmin.AssignmentsFiles.AssignmentsSubmit', compact('assignmentStudents', 'files'));
    }

    // Upload submission
    public function upload(Request $request, AssignmentStudents $assignmentStudents)
    {
        // Verify the student is authorized to submit this assignment
        $user = Auth::user();
        $student = StudentParentDetails::where('user_id', $user->id)->firstOrFail();
        
        if ($assignmentStudents->student_id != $student->student_hash_id) {
            abort(403, 'You are not authorized to submit this assignment.');
        }

        $request->validate([
            'submission' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png,zip|max:10240',
            'notes' => 'nullable|string|max:500',
        ]);

        // Store the file
        $path = $request->file('submission')->store('student_submissions', 'public');
        
        // Create submission with institute_id
        AssignmentSubmission::create([
            'institute_id' => $user->institute_id,
            'student_id' => $student->student_hash_id,
            'assignment_student_id' => $assignmentStudents->id,
            'file_path' => $path,
            'original_name' => $request->file('submission')->getClientOriginalName(),
            'file_type' => $request->file('submission')->getClientOriginalExtension(),
            'notes' => $request->notes,
            'submitted_at' => now(),
        ]);

        // Update status
        $assignmentStudents->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()->route('student.assignments.index')
            ->with('success', 'Assignment submitted successfully.');
    }

    // Get assignment details for students
    public function getAssignmentDetailsforstudents(Assignment $assignment)
    {
        // Verify the student is assigned to this assignment
        $user = Auth::user();
        $student = StudentParentDetails::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        // Check if the student is assigned to this assignment
        $isAssigned = AssignmentStudents::where('assignment_id', $assignment->id)
            ->where('student_id', $student->student_hash_id)
            ->exists();
            
        if (!$isAssigned) {
            return response()->json(['error' => 'You are not authorized to view this assignment'], 403);
        }

        // Get course type name
        $courseType = ProductDetails::where('product_id', $assignment->course_type_id)
            ->first();
        
        // Get subject name
        $subject = SubjectsCoursewise::where('subject_id', $assignment->subject_id)
            ->first();

        // Transform files to include URLs
        $files = [];
        if ($assignment->files) {
            $files = $assignment->files->map(function($file) {
                return [
                    'file_name' => $file->original_name,
                    'file_type' => $file->file_type,
                    'download_url' => $this->getFileUrl($file->file_path),
                    'icon' => $this->getFileIcon($file->file_type)
                ];
            })->toArray();
        }

        $assignmentDetails = [
            'assignment' => [
                'id' => $assignment->id,
                'title' => $assignment->title,
                'description' => $assignment->description,
                'course_type' => $courseType->course_type ?? 'N/A',
                'branch' => $courseType->sub_type ?? 'N/A',
                'subject' => $subject->subject_name ?? 'N/A',
                'semester_id' => $assignment->semester_id,
                'due_date' => $assignment->due_date,
                'created_at' => $assignment->created_at,
                'status' => 'active',
                'files' => $files
            ]
        ];

        return response()->json($assignmentDetails);
    }

    // Get file URL using the image route
    private function getFileUrl($filePath)
    {
        // Remove 'storage/' prefix if it exists
        $path = str_replace('storage/', '', $filePath);
        
        // Generate URL using the image route
        return route('image', ['path' => $path]);
    }

    // Helper function to get file icons
    private function getFileIcon($extension)
    {
        $extension = strtolower($extension);
        
        $icons = [
            'pdf' => 'fa-file-pdf',
            'doc' => 'fa-file-word',
            'docx' => 'fa-file-word',
            'ppt' => 'fa-file-powerpoint',
            'pptx' => 'fa-file-powerpoint',
            'xls' => 'fa-file-excel',
            'xlsx' => 'fa-file-excel',
            'jpg' => 'fa-file-image',
            'jpeg' => 'fa-file-image',
            'png' => 'fa-file-image',
            'gif' => 'fa-file-image',
            'zip' => 'fa-file-archive',
            'rar' => 'fa-file-archive',
        ];

        return $icons[$extension] ?? 'fa-file';
    }

    /**
 * Get detailed grade information for a student
 */
public function getGradeDetails(AssignmentStudents $assignmentStudent)
{
    // Verify the student is authorized to view this grade
    $user = Auth::user();
    $student = StudentParentDetails::where('user_id', $user->id)->first();
    
    if ($assignmentStudent->student_id != $student->student_hash_id) {
        return response()->json(['error' => 'You are not authorized to view this grade.'], 403);
    }

    // Load all related data
    $assignmentStudent->load([
        'assignment',
        'assignment.files',
        'submissions',
        'assignment.employee'
    ]);

    // Get student info
    $studentInfo = StudentParentDetails::where('student_hash_id', $assignmentStudent->student_id)->first();

    // Transform submissions to include URLs
    $submissions = $assignmentStudent->submissions->map(function($submission) {
        return [
            'id' => $submission->id,
            'original_name' => $submission->original_name,
            'file_type' => $submission->file_type,
            'submitted_at' => $submission->submitted_at,
            'notes' => $submission->notes,
            'download_url' => $this->getFileUrl($submission->file_path)
        ];
    });

    // Get course type info
    $courseType = ProductDetails::where('product_id', $assignmentStudent->assignment->course_type_id)->first();
    
    // Get subject info
    $subject = SubjectsCoursewise::where('subject_id', $assignmentStudent->assignment->subject_id)->first();

    $gradeDetails = [
        'grade_details' => [
            'id' => $assignmentStudent->id,
            'status' => $assignmentStudent->status,
            'marks' => $assignmentStudent->marks,
            'feedback' => $assignmentStudent->feedback,
            'submitted_at' => $assignmentStudent->submitted_at,
            'graded_at' => $assignmentStudent->graded_at,
            'assignment' => [
                'id' => $assignmentStudent->assignment->id,
                'title' => $assignmentStudent->assignment->title,
                'description' => $assignmentStudent->assignment->description,
                'course_type' => $courseType->course_type ?? 'N/A',
                'subject' => $subject->subject_name ?? 'N/A',
                'due_date' => $assignmentStudent->assignment->due_date,
                'created_at' => $assignmentStudent->assignment->created_at,
            ],
            'student' => [
                'first_name' => $studentInfo->first_name ?? 'Student',
                'last_name' => $studentInfo->last_name ?? '',
                'registration_number' => $studentInfo->registration_number ?? 'N/A',
                'email' => $studentInfo->email ?? 'N/A',
            ],
            'submissions' => $submissions,
            'teacher' => $assignmentStudent->assignment->employee->name ?? 'N/A',
            // Optional: Add grade breakdown if you have it
            'grade_breakdown' => $this->getGradeBreakdown($assignmentStudent->marks)
        ]
    ];

    return response()->json($gradeDetails);
}

/**
 * Helper method to generate grade breakdown (optional)
 */
private function getGradeBreakdown($marks)
{
    // This is a sample grade breakdown. You can customize this based on your grading system
    return [
        [
            'criteria' => 'Content Knowledge',
            'weight' => 40,
            'your_score' => round($marks * 0.4, 1),
            'max_score' => 40,
            'percentage' => round(($marks * 0.4 / 40) * 100, 1)
        ],
        [
            'criteria' => 'Organization',
            'weight' => 20,
            'your_score' => round($marks * 0.2, 1),
            'max_score' => 20,
            'percentage' => round(($marks * 0.2 / 20) * 100, 1)
        ],
        [
            'criteria' => 'Presentation',
            'weight' => 20,
            'your_score' => round($marks * 0.2, 1),
            'max_score' => 20,
            'percentage' => round(($marks * 0.2 / 20) * 100, 1)
        ],
        [
            'criteria' => 'Timeliness',
            'weight' => 20,
            'your_score' => round($marks * 0.2, 1),
            'max_score' => 20,
            'percentage' => round(($marks * 0.2 / 20) * 100, 1)
        ]
    ];
}
}