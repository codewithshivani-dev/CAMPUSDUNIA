<?php
namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Departments;
use App\Models\StudentLeave;
use App\Models\StudentParentDetails;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\EmployeeDetails;
use App\Models\StudentAcademicTransportDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StudentLeaveController extends Controller
{
    
    public function applyLeave(Request $request)
{
    $request->validate([
        'leave_type' => 'required|string|max:255',
        'leave_duration_type' => 'required|in:Full Day,Half Day,Short Leave',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'reason' => 'nullable|string',
        'leave_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    $user = Auth::user();
    $student = $user->studentDetail; // relationship to student_parent_details
    
    if (!$student) {
        return back()->with('error', 'Student record not found for your account.');
    }
  
    // 🟢 Fetch academic details using student_hash_id
    $academic = StudentAcademicTransportDetails::where('student_hash_id', $student->student_hash_id)->first();
    
    if (!$academic) {
        return back()->with('error', 'Academic details not found for this student.');
    }
    
    // 🟢 File upload
    $filePath = $request->hasFile('leave_document')
        ? $request->file('leave_document')->store('student_leaves', 'public')
        : null;

    // 🟢 Calculate leave duration
    $start = Carbon::parse($request->start_date);
    $end = Carbon::parse($request->end_date ?? $request->start_date);
    $totalDays = $start->diffInDays($end) + 1;

    // 🟢 Find approver (supervisor/manager from same department)
    $approver = $this->getApproverForStudent($student->institute_id, $academic->department);
    
    // 🟢 Create leave request
    StudentLeave::create([
        'institute_id'        => $student->institute_id,
        'student_hash_id'     => $student->student_hash_id,
        'leave_type'          => $request->leave_type,
        'leave_duration_type' => $request->leave_duration_type,
        'start_date'          => $request->start_date,
        'end_date'            => $request->end_date ?? $request->start_date,
        'total_days'          => $totalDays,
        'reason'              => $request->reason,
        'leave_document'      => $filePath,
        'approver_id' => $approver->user_id,
        'status'              => 'Pending',
    ]);

    return back()->with('success', 'Leave request submitted successfully!');
}


    // 🟡 View leave requests (for manager/supervisor)
    public function viewLeaveRequests(Request $request)
    {
        $user = Auth::user();
    
    // Get employee details of logged-in user (for manager/supervisor)
    $employee = EmployeeDetails::where('id', $user->id)->first();
    
    if (! $employee) {
        return back()->with('error', 'Employee record not found.');
    }

    $employeeId = $employee->id;
    
    // 🟢 Removed with('student')
    $query = StudentLeave::where('approver_id', $employeeId);
   
        if ($request->search) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%");
            });
        }

        if ($request->leave_type) {
            $query->where('leave_type', $request->leave_type);
        }

        $pending = (clone $query)->where('status','Pending')->latest()->get();
        $approved = (clone $query)->where('status','Approved')->latest()->get();
        $rejected = (clone $query)->where('status','Rejected')->latest()->get();

        return view('instituteAdmin.StudentLeave.ViewRequests', compact('pending','approved','rejected'));
    }

    // 🔵 Approve / Reject leave
    public function updateStatus(Request $request, $id)
    {
        $leave = StudentLeave::findOrFail($id);
        $employee = Auth::user();

        // ✅ Only Supervisor/Manager can approve
        if (!in_array($employee->role, ['Supervisor', 'Manager'])) {
            abort(403, "You are not authorized to approve leave requests.");
        }

        // ✅ Department must match
        if ($employee->department != $leave->student->department) {
            abort(403, "You can only approve leaves for students in your department.");
        }

        // ✅ Assigned approver must match logged-in employee
        if ($leave->approver_id != $employee->id) {
            abort(403, "You are not assigned to approve this leave request.");
        }

        $leave->update([
            'status' => $request->status,
            'approved_by' => $employee->id,
        ]);

        return back()->with('success', "Leave status updated to {$request->status}");
    }

    // 🔹 Get Supervisor/Manager for the student
   private function getApproverForStudent($instituteId, $department)
{
    return EmployeeDetails::join('model_has_roles', 'employee_details.id', '=', 'model_has_roles.model_id')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->join('users', 'users.id', '=', 'employee_details.id') // both share same id
        ->where('employee_details.institute_id', $instituteId)
        ->where('employee_details.department', $department)
        ->whereIn('roles.name', ['Supervisor', 'Manager'])
        ->select('users.id as user_id', 'employee_details.*')
        ->first();
}

public function assignLeaveForm()
    {
        $departments = Departments::all();
        return view('instituteAdmin.StudentLeave.assignLeavestostudent', compact('departments'));
    }
  public function getStudentsByDepartment($department_id)
{
    try {
        // Get students by joining academic_transport_details with student_parent_details
        $students = DB::table('academic_transport_details as atd')
            ->join('student_parent_details as spd', 'atd.student_hash_id', '=', 'spd.student_hash_id')
            ->where('atd.department_id', $department_id)
            ->select(
                'spd.student_hash_id',
                'spd.first_name as name',
                'spd.registration_number',
                DB::raw("CONCAT(spd.first_name, ' (', spd.registration_number, ')') as display_name")
            )
            ->distinct()
            ->orderBy('spd.first_name')
            ->get();
        return response()->json($students);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to fetch students'
        ], 500);
    }
}
private function getInstituteBranchContext()
{
    $user = Auth::user();

    return [
        'institute_id' => $user->institute_id ?? null,
        'branch_id' => $user->branch_id ?? null,
    ];
}
public function assignStudentLeaveStore(Request $request)
{
    $request->validate([
        'department_id' => 'required|string',
        'student_ids' => 'required|array|min:1',
        'student_ids.*' => 'required|string',
        'session_year' => 'required|string',
        'leave_types' => 'required|array|min:1',
        'leave_types.*.type' => 'required|string',
        'leave_types.*.days' => 'required|integer|min:0',
        'custom_leave.type' => 'nullable|string',
        'custom_leave.days' => 'nullable|integer|min:0',
    ]);
    $leaveTypes = $request->leave_types;
    // :white_check_mark: Add custom leave if filled
    if (!empty($request->custom_leave['type']) && !empty($request->custom_leave['days'])) {
        $leaveTypes[] = [
            'type' => $request->custom_leave['type'],
            'days' => $request->custom_leave['days'],
        ];
    }
    $context = $this->getInstituteBranchContext();
    // Check if user has institute access
    if (!$context['institute_id']) {
        return redirect()->back()->with('error', 'You are not associated with any institute.');
    }
    DB::beginTransaction();
    try {
        foreach ($request->student_ids as $studentHashId) {
            // Get student details to get registration number
            $student = StudentParentDetails::where('student_hash_id', $studentHashId)->first();
            foreach ($leaveTypes as $leaveData) {
                StudentLeave::updateOrCreate(
                    [
                        'institute_id' => $context['institute_id'],
                        'branch_id' => $context['branch_id'],
                        'student_hash_id' => $studentHashId,
                        'department_id' => $request->department_id,
                        'leave_type' => $leaveData['type'],
                        'session_year' => $request->session_year,
                    ],
                    [
                        'registration_number' => $student->registration_number ?? null,
                        'total_allocated' => $leaveData['days'],
                        'used' => 0,
                        'remaining' => $leaveData['days'],
                        'status' => 'active',
                        'remarks' => 'Assigned by admin'
                    ]
                );
            }
        }
        DB::commit();
        return redirect()->back()->with('success', 'Leave quotas assigned successfully to ' . count($request->student_ids) . ' student(s)!');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error assigning student leave: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Failed to assign leave quotas: ' . $e->getMessage())
            ->withInput();
    }
}

}