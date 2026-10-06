<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeDetails;
use App\Models\Assignment;
use App\Models\Departments;
use App\Models\AssignmentFile;
use App\Models\AssignmentStudents;
use App\Models\StudentParentDetails;
use App\Models\StudentAcademicTransportDetails;
use App\Models\FincapMerchantSubCategories;
use App\Models\SubjectsCoursewise;
use App\Models\ProductDetails;
use App\Models\DepartmentCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{

      public function index(Request $request)
    {
        // Get the logged-in employee
        $employee = EmployeeDetails::where('user_id', auth()->id())->first();
        
        if (!$employee) {
            return redirect()->route('assignments.create')
                ->with('error', 'Employee profile not found. Please complete your profile first.');
        }
        
        // Start query - only get assignments created by this employee
        $query = Assignment::with([
            'employee', 
            'files', 
            'assignedStudents.student',
            'department',
            'courseType',
            'branchDetail'
        ])->where('employee_id', $employee->employee_id); 

        // Apply filters
        if ($request->has('course_type_id') && $request->course_type_id) {
            $query->where('course_type_id', $request->course_type_id);
        }

        if ($request->has('branch_id') && $request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->has('status') && $request->status) {
            $query->whereHas('assignedStudents', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $assignments = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get employee's department for display
        $employeeDepartment = Departments::where('department_id', $employee->department_id)->first();
        
        // Get course types for employee's department (for filter dropdown)
        $courseTypes = [];
        if ($employeeDepartment) {
            $courseTypes = \App\Models\FincapMerchantSubCategories::where('department_id', $employeeDepartment->department_id)
                ->where('institute_id', auth()->user()->institute_id)
                ->select('finacp_merchant_sub_category_type')
                ->distinct()
                ->get();
        }

        return view('instituteAdmin.AssignmentsFiles.GetEmployeeAssignments', compact(
            'assignments', 
            'employee',
            'employeeDepartment',
            'courseTypes'
        ));
    }
    
        // In your AssignmentController
     public function getAssignmentFiles(Assignment $assignment)
    {
        $files = $assignment->files->map(function($file) {
            // Generate URL using your image route
            $filePath = str_replace('storage/', '', $file->file_path);
            $downloadUrl = route('image', ['path' => $filePath]);
            
            return [
                'file_name' => basename($file->file_path),
                'file_type' => $file->file_type,
                'download_url' => $downloadUrl
            ];
        });

        return response()->json(['files' => $files]);
    }
    
    // Show create form
   public function create()
    {
        // Get logged-in employee details
        $employee = EmployeeDetails::where('user_id', auth()->id())
            ->select('employee_id', 'name', 'designation', 'department_id')
            ->first();
            
        if (!$employee) {
            return redirect()->route('assignments.index')
                ->with('error', 'Employee profile not found. Please complete your employee profile first.');
        }
        
        // Get department categories for the institute
        $departmentCategories = DepartmentCategory::where('institute_id', auth()->user()->institute_id)->get();
        
        // Get employee's department (if exists)
        $employeeDepartment = null;
        if ($employee->department_id) {
            $employeeDepartment = Departments::where('department_id', $employee->department_id)
                ->first();
        }
        
        // If employee has a department, get its category
        $employeeDepartmentCategory = null;
        if ($employeeDepartment && $employeeDepartment->department_category_id) {
            $employeeDepartmentCategory = DepartmentCategory::where('department_category_id', $employeeDepartment->department_category_id)
                ->first();
        }
        
        return view('instituteAdmin.AssignmentsFiles.AssignmentsCreate', compact(
            'employee', 
            'departmentCategories',
            'employeeDepartment',
            'employeeDepartmentCategory'
        ));
    }

    // Store new assignment
    public function store(Request $request)
    {
      
        // Get logged-in employee
        $employee = EmployeeDetails::where('user_id', auth()->id())->first();
        if (!$employee) {
            return redirect()->back()->with('error', 'Employee profile not found.');
        }

        // Validation rules
        $validationRules = [
            // 'department_category_id' => 'required',
            // 'department_id' => 'required',
            // 'title' => 'required|string|max:255',
            // 'description' => 'nullable|string',
            // 'due_date' => 'required|date',
            // 'course_type_id' => 'required',
            // 'branch_id' => 'required',
            // 'subject_id' => 'required',
            // 'files.*' => 'file|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png,zip|max:10240',
        ];

        // Check if semester is required
        $branch = ProductDetails::where('product_id',$request->branch_id)->first();
      
        if ($branch && $branch->semesters) {
            // This branch has semesters defined
            $decodedSemesters = json_decode($branch->semesters, true);
              
            if (is_array($decodedSemesters) && count($decodedSemesters) > 0) {
                // Semester is required for this branch
                // $validationRules['semester_id'] = 'required';
                
            }
        }
      
        $validated = $request->validate($validationRules);
       

        // Verify the employee belongs to the selected department
        if ($employee->department_id != $request->department_id) {
            return redirect()->back()->with('error', 'You can only create assignments for your own department.');
        }

        // Prepare data for assignment
        $data = [
            'institute_id' => auth()->user()->institute_id,
            'department_id' => $request->department_id,
            'employee_id' => $employee->employee_id,
            'course_type_id' =>$branch->product_id,
            'semester_id' => $request->semester_id ?? null, 
            'subject_id' => $request->subject_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
        ];
            
        // Create assignment
        $assignment = Assignment::create($data);

        // Save attached files
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('assignments', 'public');
                $assignment->files()->create([
                    'institute_id' => auth()->user()->institute_id,
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('assignments.assignStudentsForm', $assignment->id)
                        ->with('success', 'Assignment created successfully.');
    }
   
    public function assignStudentsForm(Assignment $assignment)
    {
        // Verify the assignment belongs to the logged-in employee
        $employee = EmployeeDetails::where('user_id', auth()->id())->first();
        if ($assignment->employee_id != $employee->employee_id) {
            return redirect()->route('assignments.index')->with('error', 'You can only assign students to your own assignments.');
        }

        // Get the course details from the assignment
        $courseType = $assignment->course_type_id;
        
        // Get the course type name from product_details
        $subtypeTypeName = DB::table('product_details')
            ->where('product_id', $courseType)
            ->value('sub_type');
        
        $courseTypeName = DB::table('product_details')
            ->where('product_id', $courseType)
            ->value('course_type');
    
        // Get the sub_type name from product_details
        $subType = ProductDetails::where('product_id', $courseType)->value('sub_type');
    
        // Build the query for fetching students
        $studentsQuery = DB::table('academic_transport_details as atd')
            ->join('student_parent_details as spd', 'atd.student_hash_id', '=', 'spd.student_hash_id')
            ->where('atd.course_subtype_id', $courseType)
            ->select(
                'spd.student_hash_id', 
                'spd.registration_number', 
                'spd.first_name', 
                'spd.last_name',
                'spd.email',
                'atd.section_id',
                'atd.semester_id'
            );

        // Handle semester logic
        $semesterFilterApplied = false;
        
        if ($assignment->semester_id && $assignment->semester_id !== 'all_semesters') {
            // If assignment has specific semester, match exact semester
            $studentsQuery->where('atd.semester_id', $assignment->semester_id);
            $semesterFilterApplied = true;
        } elseif ($assignment->semester_id === 'all_semesters') {
            // If assignment is for all semesters, fetch students from any semester
            // or students with 'all_semesters' semester_id
            $studentsQuery->where(function($query) {
                $query->where('atd.semester_id', 'all_semesters')
                    ->orWhereNull('atd.semester_id');
            });
            $semesterFilterApplied = true;
        } else {
            // If assignment has no semester, fetch all students
            // No semester filter applied
        }

        $students = $studentsQuery->get();
        
        // Get assignment details for display
        $assignmentDetails = [
            'institute_id' => auth()->user()->institute_id,
            'course_type' => $courseTypeName,
            'course_subtype' => $subType,
            'course_type_raw' => $courseType,
            'semester' => $assignment->semester_id,
            'semester_filter_applied' => $semesterFilterApplied
        ];

        return view('instituteAdmin.AssignmentsFiles.AssignStudents', compact('assignment', 'students', 'assignmentDetails', 'subtypeTypeName', 'courseTypeName'));
    }
    // Save student assignment
    public function assignStudents(Request $request, Assignment $assignment)
    {
        // Verify the assignment belongs to the logged-in employee
        $employee = EmployeeDetails::where('user_id', auth()->id())->first();
        if ($assignment->employee_id != $employee->employee_id) {
            return response()->json(['error' => 'You can only assign students to your own assignments.'], 403);
        }

        $request->validate([
            'student_ids' => 'required|array',
        ]);
        
        foreach ($request->student_ids as $studentId) {
            AssignmentStudents::create([
                'institute_id' => auth()->user()->institute_id,
                'assignment_id' => $assignment->id,
                'student_id' => $studentId,
                'assigned_date' => now(),
                'status' => 'pending',
            ]);
        }

        return redirect()->route('assignments.index')
                         ->with('success', 'Assignment assigned to students successfully.');
    }

        public function getAssignmentStudents(Assignment $assignment)
    {
        $students = $assignment->assignedStudents()->with('student')->get()->map(function($assigned) {
            $studentData = $assigned->student;
            
            // If it's a collection, get the first item
            if ($studentData instanceof \Illuminate\Support\Collection) {
                $studentData = $studentData->first();
            }

            return [
                'first_name' => $studentData->first_name ?? 'Unknown',
                'last_name' => $studentData->last_name ?? 'Student',
                'registration_number' => $studentData->registration_number ?? 'N/A',
                'status' => $assigned->status,
                'marks' => $assigned->marks
            ];
        });

        return response()->json(['students' => $students]);
    }

    public function getAssignmentDetails(Assignment $assignment)
    {
        // Verify the assignment belongs to the logged-in employee
        $employee = EmployeeDetails::where('user_id', auth()->id())->first();
        if ($assignment->employee_id != $employee->employee_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $assignmentDetails = [
            'assignment' => [
                'id' => $assignment->id,
                'title' => $assignment->title,
                'description' => $assignment->description,
                'course_type' => $assignment->courseType->course_type ?? 'N/A',
                'branch' => $assignment->branchDetail->sub_type ?? 'N/A',
                'subject' => $assignment->subject ? SubjectsCoursewise::where('subject_id', $assignment->subject_id)->value('subject_name') : 'N/A',
                'semester_id' => $assignment->semester_id,
                'due_date' => $assignment->due_date,
                'created_at' => $assignment->created_at,
                'students_count' => $assignment->assignedStudents->count(),
                'files_count' => $assignment->files->count(),
            ]
        ];

        return response()->json($assignmentDetails);
    }

  

    /**
     * View a specific student's submission
     */
    public function viewSubmissions(Assignment $assignment)
{
    // Verify the assignment belongs to the logged-in employee
    $employee = EmployeeDetails::where('user_id', auth()->id())->first();
    if ($assignment->employee_id != $employee->employee_id) {
        return redirect()->route('assignments.index')->with('error', 'You can only view submissions for your own assignments.');
    }

    // Get all assigned students with their submissions
    $assignedStudents = AssignmentStudents::with(['student', 'submissions'])
        ->where('assignment_id', $assignment->id)
        ->orderBy('status')
        ->get();

    // Transform the data to handle collection->first() for student
    $assignedStudents->transform(function ($assignedStudent) {
        // If student is a collection, get the first item
        if ($assignedStudent->student instanceof \Illuminate\Support\Collection) {
            $assignedStudent->student = $assignedStudent->student->first();
        }
        return $assignedStudent;
    });

    return view('instituteAdmin.AssignmentsFiles.ViewSubmissions', compact('assignment', 'assignedStudents'));
}

    /**
     * Grade student's submission
     */
    public function gradeSubmission(Request $request, AssignmentStudents $assignmentStudent)
    {
        // Verify the assignment belongs to the logged-in employee
        $employee = EmployeeDetails::where('user_id', auth()->id())->first();
        $assignment = $assignmentStudent->assignment;
        
        if ($assignment->employee_id != $employee->employee_id) {
            return response()->json(['error' => 'You are not authorized to grade this submission.'], 403);
        }

        $request->validate([
            'marks' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:1000',
        ]);

        // Update the assignment student record
        $assignmentStudent->update([
            'marks' => $request->marks,
            'feedback' => $request->feedback,
            'status' => 'graded',
            'graded_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Submission graded successfully.');
    }

    /**
     * Download student submission file
     */
    public function downloadSubmissionFile(AssignmentSubmission $submission)
    {
        // Verify the assignment belongs to the logged-in employee
        $employee = EmployeeDetails::where('user_id', auth()->id())->first();
        $assignment = $submission->assignmentStudent->assignment;
        
        if ($assignment->employee_id != $employee->employee_id) {
            abort(403, 'You are not authorized to download this file.');
        }

        $filePath = storage_path('app/public/' . $submission->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download($filePath, $submission->original_name);
    }
   
}