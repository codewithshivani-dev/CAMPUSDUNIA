<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\AssignmentStudents;
use App\Models\AssignmentSubmission;
use App\Models\EmployeeDetails;
use App\Models\Departments;
use App\Models\StudentParentDetails;
use App\Models\SubjectsCoursewise;
use App\Models\ProductDetails;
use App\Models\FincapMerchantSubCategories;
use App\Models\DepartmentCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminAssignmentController extends Controller
{
    /**
     * Display all assignments in the institute
     */
    public function index(Request $request)
    {
        $instituteId = auth()->user()->institute_id;
        
        // Start query
        $query = Assignment::with([
            'employee',
            'department',
            'courseType',
            'files',
            'assignedStudents' => function($q) {
                $q->select('assignment_id', DB::raw('count(*) as total'))
                  ->groupBy('assignment_id');
            },
            'assignedStudentsWithStatus' => function($q) {
                $q->select('assignment_id', 'status', DB::raw('count(*) as count'))
                  ->groupBy('assignment_id', 'status');
            }
        ])->where('institute_id', $instituteId);
        
        // Apply filters
        if ($request->has('department_id') && $request->department_id) {
            $query->where('department_id', $request->department_id);
        }
        
        if ($request->has('employee_id') && $request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }
        
        if ($request->has('course_type_id') && $request->course_type_id) {
            $query->where('course_type_id', $request->course_type_id);
        }
        
        if ($request->has('status') && $request->status) {
            $query->whereHas('assignedStudents', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('employee', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Get all assignments
        $assignments = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get filter data
        $departments = Departments::where('institute_id', $instituteId)->get();
        $employees = EmployeeDetails::where('institute_id', $instituteId)->get();
        
        // Get course types
        $courseTypes = FincapMerchantSubCategories::where('institute_id', $instituteId)
            ->select('finacp_merchant_sub_category_type')
            ->distinct()
            ->get();
        
        // Statistics
        $totalAssignments = Assignment::where('institute_id', $instituteId)->count();
        $totalStudentsAssigned = AssignmentStudents::whereHas('assignment', function($q) use ($instituteId) {
            $q->where('institute_id', $instituteId);
        })->count();
        
        $totalSubmissions = AssignmentStudents::whereHas('assignment', function($q) use ($instituteId) {
            $q->where('institute_id', $instituteId);
        })->where('status', 'submitted')->count();
        
        $totalGraded = AssignmentStudents::whereHas('assignment', function($q) use ($instituteId) {
            $q->where('institute_id', $instituteId);
        })->where('status', 'graded')->count();
        
        return view('instituteAdmin.AssignmentsFiles.AdminAssignmentsIndex', compact(
            'assignments',
            'departments',
            'employees',
            'courseTypes',
            'totalAssignments',
            'totalStudentsAssigned',
            'totalSubmissions',
            'totalGraded'
        ));
    }
    
    /**
     * Show assignment details
     */
    public function show(Assignment $assignment)
    {
        $instituteId = auth()->user()->institute_id;
        
        // Verify assignment belongs to institute
        if ($assignment->institute_id != $instituteId) {
            abort(403, 'You are not authorized to view this assignment.');
        }
        
        // Load all related data
        $assignment->load([
            'employee',
            'department',
            'courseType',
            'files',
            'assignedStudents.student',
            'assignedStudents.submissions'
        ]);
        
        // Get statistics for this assignment
        $studentStats = [
            'total' => $assignment->assignedStudents->count(),
            'pending' => $assignment->assignedStudents->where('status', 'pending')->count(),
            'submitted' => $assignment->assignedStudents->where('status', 'submitted')->count(),
            'graded' => $assignment->assignedStudents->where('status', 'graded')->count(),
        ];
        
        // Get average marks
        $averageMarks = $assignment->assignedStudents->where('status', 'graded')->avg('marks');
        
        return view('instituteAdmin.AssignmentsFiles.AdminAssignmentShow', compact(
            'assignment',
            'studentStats',
            'averageMarks'
        ));
    }
    
    /**
     * View all submissions for an assignment
     */
    public function viewSubmissions(Assignment $assignment)
    {
        $instituteId = auth()->user()->institute_id;
        
        // Verify assignment belongs to institute
        if ($assignment->institute_id != $instituteId) {
            abort(403, 'You are not authorized to view this assignment.');
        }
        
        // Get all assigned students with their submissions
        $assignedStudents = AssignmentStudents::with(['student', 'submissions'])
            ->where('assignment_id', $assignment->id)
            ->orderBy('status')
            ->paginate(20);
        
        return view('instituteAdmin.AssignmentsFiles.AdminAssignmentSubmissions', compact(
            'assignment',
            'assignedStudents'
        ));
    }
    
    /**
     * Download assignment file
     */
    public function downloadFile(Assignment $assignment, $fileId)
    {
        $instituteId = auth()->user()->institute_id;
        
        // Verify assignment belongs to institute
        if ($assignment->institute_id != $instituteId) {
            abort(403, 'You are not authorized to download this file.');
        }
        
        $file = $assignment->files()->where('id', $fileId)->first();
        
        if (!$file) {
            abort(404, 'File not found.');
        }
        
        $filePath = storage_path('app/public/' . $file->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found on server.');
        }
        
        return response()->download($filePath, $file->original_name);
    }
    
    /**
     * Delete an assignment (admin only)
     */
    public function destroy(Assignment $assignment)
    {
        $instituteId = auth()->user()->institute_id;
        
        // Verify assignment belongs to institute
        if ($assignment->institute_id != $instituteId) {
            abort(403, 'You are not authorized to delete this assignment.');
        }
        
        // Delete related records
        $assignment->files()->delete();
        
        // Delete assignment students and their submissions
        $assignment->assignedStudents()->each(function($assignedStudent) {
            $assignedStudent->submissions()->delete();
            $assignedStudent->delete();
        });
        
        // Delete the assignment
        $assignment->delete();
        
        return redirect()->route('admin.assignments.index')
            ->with('success', 'Assignment deleted successfully.');
    }
    
    /**
     * Get assignment statistics for dashboard
     */
    public function statistics()
    {
        $instituteId = auth()->user()->institute_id;
        
        // Overall statistics
        $totalAssignments = Assignment::where('institute_id', $instituteId)->count();
        $totalStudents = AssignmentStudents::whereHas('assignment', function($q) use ($instituteId) {
            $q->where('institute_id', $instituteId);
        })->count();
        
        $submittedCount = AssignmentStudents::whereHas('assignment', function($q) use ($instituteId) {
            $q->where('institute_id', $instituteId);
        })->where('status', 'submitted')->count();
        
        $gradedCount = AssignmentStudents::whereHas('assignment', function($q) use ($instituteId) {
            $q->where('institute_id', $instituteId);
        })->where('status', 'graded')->count();
        
        // Assignments by department
        $assignmentsByDepartment = Assignment::where('institute_id', $instituteId)
            ->with('department')
            ->select('department_id', DB::raw('count(*) as count'))
            ->groupBy('department_id')
            ->get();
        
        // Assignments by status (using assigned students status)
        $assignmentsByStatus = AssignmentStudents::whereHas('assignment', function($q) use ($instituteId) {
            $q->where('institute_id', $instituteId);
        })
        ->select('status', DB::raw('count(*) as count'))
        ->groupBy('status')
        ->get();
        
        // Recent assignments
        $recentAssignments = Assignment::where('institute_id', $instituteId)
            ->with(['employee', 'department'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Top teachers by assignments created
        $topTeachers = Assignment::where('institute_id', $instituteId)
            ->with('employee')
            ->select('employee_id', DB::raw('count(*) as assignment_count'))
            ->groupBy('employee_id')
            ->orderBy('assignment_count', 'desc')
            ->take(5)
            ->get();
        
        return view('instituteAdmin.AssignmentsFiles.AdminAssignmentStatistics', compact(
            'totalAssignments',
            'totalStudents',
            'submittedCount',
            'gradedCount',
            'assignmentsByDepartment',
            'assignmentsByStatus',
            'recentAssignments',
            'topTeachers'
        ));
    }
    
    /**
     * Get assignments by teacher
     */
    public function byTeacher(EmployeeDetails $employee)
    {
        $instituteId = auth()->user()->institute_id;
        
        // Verify employee belongs to institute
        if ($employee->institute_id != $instituteId) {
            abort(403, 'Employee not found in your institute.');
        }
        
        $assignments = Assignment::where('institute_id', $instituteId)
            ->where('employee_id', $employee->employee_id)
            ->with(['department', 'courseType', 'files'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Teacher statistics
        $teacherStats = [
            'total_assignments' => $assignments->total(),
            'total_students' => AssignmentStudents::whereHas('assignment', function($q) use ($employee) {
                $q->where('employee_id', $employee->employee_id);
            })->count(),
            'submitted' => AssignmentStudents::whereHas('assignment', function($q) use ($employee) {
                $q->where('employee_id', $employee->employee_id);
            })->where('status', 'submitted')->count(),
            'graded' => AssignmentStudents::whereHas('assignment', function($q) use ($employee) {
                $q->where('employee_id', $employee->employee_id);
            })->where('status', 'graded')->count(),
        ];
        
        return view('instituteAdmin.AssignmentsFiles.AdminAssignmentsByTeacher', compact(
            'assignments',
            'employee',
            'teacherStats'
        ));
    }
}