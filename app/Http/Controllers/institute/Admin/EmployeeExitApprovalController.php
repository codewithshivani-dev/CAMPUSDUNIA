<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeDetails;
use App\Models\EmployeeExit;
use App\Models\EmployeeExitApproval;
use App\Models\EmployeeExitTaskAssignment;
use App\Models\User;
use App\Services\EmployeeExitService;
use App\Traits\InstituteBranchAccess;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmployeeExitApprovalController extends Controller
{
    use InstituteBranchAccess;

    protected EmployeeExitService $exitService;

    public function __construct(EmployeeExitService $exitService)
    {
        $this->exitService = $exitService;
    }

    /**
     * Show exit approval dashboard
     */
    public function index(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        $currentUserId = auth()->id();
        
        // Get employees for search
        $employees = EmployeeDetails::select('employee_id', 'employee_code', 'name', 'department_id')
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->orderBy('name')
            ->get();
        
        // Get departments for filter
        $departments = DB::table('departments')
            ->where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->select('department_id', 'department')
            ->get();
        
        // Query approvals
        $query = DB::table('employee_exit_approvals as ea')
            ->join('employee_exits as ee', 'ea.exit_id', '=', 'ee.id')
            ->join('employee_details as ed', 'ee.employee_id', '=', 'ed.employee_id')
            ->leftJoin('departments as d', 'ed.department_id', '=', 'd.department_id')
            ->leftJoin('users as u', 'ea.approver_id', '=', 'u.id')
            ->select(
                'ea.id',
                'ea.approver_id',
                'ea.status',
                'ea.approver_name',
                'ea.approver_role',
                'ea.approved_date',
                'ea.comments',
                'ee.id as exit_id',
                'ee.employee_id',
                'ee.exit_status',
                'ee.exit_reason',
                'ee.exit_notes',
                'ee.exit_document',
                'ee.resignation_date',
                'ee.initiated_at',
                'ee.notice_start_date',
                'ee.notice_end_date',
                'ee.proposed_last_working_date',
                'ee.notice_period_days',
                'ee.initiation_source',
                'ee.created_at as exit_created_at',
                'ed.name as employee_name',
                'ed.employee_code',
                'ed.department_id as emp_department_id',
                'd.department as department_name',
                'u.name as approver_user_name'
            )
            ->where('ea.approver_id', $currentUserId);
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ed.employee_id', $search)
                    ->orWhere('ed.employee_code', 'like', "%{$search}%")
                    ->orWhere('ed.name', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('department_id')) {
            $query->where('ed.department_id', $request->department_id);
        }
        
        // Get results
        $allApprovals = $query->get();
        
        // Filter by status
        $pending = $allApprovals->where('status', 'Pending');
        $approved = $allApprovals->where('status', 'Approved');
        $rejected = $allApprovals->where('status', 'Rejected');
        
        $activeTab = $request->get('tab', 'pending');
        
        return view('instituteAdmin.EmployeeExit.Exitapprovals', compact(
            'pending',
            'approved',
            'rejected',
            'employees',
            'departments',
            'activeTab'
        ));
    }

    /**
     * Get employees for assignment
     */
    public function getAssignableEmployees(Request $request)
    {
        try {
            $context = $this->getInstituteBranchContext();
            $exitId = $request->exit_id;
            
            // Get the exit and employee details
            $exit = EmployeeExit::with('employee')->find($exitId);
            if (!$exit) {
                return response()->json(['success' => false, 'message' => 'Exit not found']);
            }
            
            $employeeDepartmentId = $exit->employee->department_id;
            $employeeId = $exit->employee_id;
            $instituteId = $context['institute_id'];
            $branchId = $context['branch_id'];
            
            // Get HR/Admin users
            $hrUsers = User::whereHas('roles', function($query) {
                    $query->whereIn('name', ['admin', 'superadmin', 'institute_admin', 'hr']);
                })
                ->where('institute_id', $instituteId)
                ->when($branchId, function($query) use ($branchId) {
                    return $query->where('branch_id', $branchId);
                })
                ->select('id', 'name', 'email')
                ->get()
                ->map(function($user) {
                    return (object) [
                        'id' => $user->id,
                        'name' => $user->name,
                        'label' => $user->name . ' (HR/Admin)',
                        'type' => 'hr'
                    ];
                });
            
            // Get employees from same department (excluding the exiting employee)
            $departmentEmployees = EmployeeDetails::where('institute_id', $instituteId)
                ->when($branchId, function($query) use ($branchId) {
                    return $query->where('branch_id', $branchId);
                })
                ->where('department_id', $employeeDepartmentId)
                ->where('employee_id', '!=', $employeeId)
                ->where('status', 'active')
                ->with('user')
                ->get()
                ->filter(function($emp) {
                    return $emp->user && $emp->user->status === 'active';
                })
                ->map(function($emp) {
                    return (object) [
                        'id' => $emp->user_id,
                        'name' => $emp->name,
                        'label' => $emp->name . ' (' . $emp->designation . ')',
                        'type' => 'department'
                    ];
                });
            
            // Combine both lists
            $employees = $hrUsers->merge($departmentEmployees)->unique('id')->values();
            
            return response()->json([
                'success' => true,
                'employees' => $employees,
                'department_name' => $exit->employee->department->name ?? 'N/A'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting assignable employees: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get employees: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign tasks for exit (KT, Interview, etc.)
     */
    public function assignTasks(Request $request)
    {
        try {
            $request->validate([
                'exit_id' => 'required|exists:employee_exits,id',
                'task_type' => 'required|in:kt,exit_interview,asset_clearance,fnf',
                'assigned_to' => 'required|exists:users,id',
                'instructions' => 'nullable|string',
                'deadline' => 'nullable|date|after:today',
            ]);

            $context = $this->getInstituteBranchContext();
            $currentUser = auth()->user();
            $exitId = $request->exit_id;
            $taskType = $request->task_type;
            $assignedToUserId = $request->assigned_to;
            $instructions = $request->instructions;
            $deadline = $request->deadline;

            // Get exit and employee details
            $exit = EmployeeExit::with('employee')->find($exitId);
            if (!$exit) {
                return response()->json(['success' => false, 'message' => 'Exit not found'], 404);
            }

            // Get assigned user details
            $assignedUser = User::find($assignedToUserId);
            if (!$assignedUser) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }

            // Get employee details for the assigned user
            $assignedEmployee = EmployeeDetails::where('user_id', $assignedToUserId)->first();

            // Check if task already exists for this exit and type
            $existingTask = EmployeeExitTaskAssignment::where('exit_id', $exitId)
                ->where('task_type', $taskType)
                ->first();

            if ($existingTask) {
                // Update existing task
                $existingTask->update([
                    'assigned_to_user_id' => $assignedToUserId,
                    'assigned_to_name' => $assignedUser->name,
                    'assigned_to_role' => $assignedEmployee ? $assignedEmployee->designation : 'HR/Admin',
                    'assigned_to_employee_id' => $assignedEmployee ? $assignedEmployee->employee_id : null,
                    'instructions' => $instructions,
                    'deadline' => $deadline,
                    'assigned_at' => Carbon::now(),
                    'assigned_by' => $currentUser->id,
                    'status' => 'pending'
                ]);

                $message = "Task assignment updated successfully!";
            } else {
                // Create new task
                EmployeeExitTaskAssignment::create([
                    'exit_id' => $exitId,
                    'task_type' => $taskType,
                    'assigned_to_user_id' => $assignedToUserId,
                    'assigned_to_name' => $assignedUser->name,
                    'assigned_to_role' => $assignedEmployee ? $assignedEmployee->designation : 'HR/Admin',
                    'assigned_to_employee_id' => $assignedEmployee ? $assignedEmployee->employee_id : null,
                    'instructions' => $instructions,
                    'deadline' => $deadline,
                    'assigned_at' => Carbon::now(),
                    'assigned_by' => $currentUser->id,
                    'status' => 'pending'
                ]);

                $message = "Task assigned successfully!";
            }

            // Send notification to assigned person
            $this->sendTaskAssignmentNotification($exit, $taskType, $assignedUser, $currentUser);

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            Log::error('Error assigning task: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign task: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get task assignments for an exit
     */
    public function getTaskAssignments($exitId)
    {
        try {
            $tasks = EmployeeExitTaskAssignment::where('exit_id', $exitId)
                ->orderBy('task_type')
                ->get();

            return response()->json([
                'success' => true,
                'tasks' => $tasks
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get tasks: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send task assignment notification
     */
    private function sendTaskAssignmentNotification($exit, $taskType, $assignedUser, $currentUser)
    {
        try {
            $employee = $exit->employee;
            $taskLabels = [
                'kt' => 'Knowledge Transfer',
                'exit_interview' => 'Exit Interview',
                'asset_clearance' => 'Asset Clearance',
                'fnf' => 'FNF Settlement'
            ];

            $taskLabel = $taskLabels[$taskType] ?? ucfirst($taskType);

            // Email to assigned person
            if (!empty($assignedUser->email)) {
                Mail::send('emails.exit-task-assignment', [
                    'assignedToName' => $assignedUser->name,
                    'employeeName' => $employee->name,
                    'employeeCode' => $employee->employee_code,
                    'taskType' => $taskLabel,
                    'assignedByName' => $currentUser->name,
                    'exitId' => $exit->id,
                ], function ($message) use ($assignedUser, $taskLabel) {
                    $message->to($assignedUser->email)
                        ->subject("Exit Task Assignment: {$taskLabel}");
                });
            }

            // In-app notification
            $service = app(EmployeeExitService::class);
            $service->sendInAppNotification(
                $assignedUser->id,
                'exit_task_assigned',
                "📋 {$taskLabel} Assigned",
                "You have been assigned as the {$taskLabel} person for {$employee->name} ({$employee->employee_code}). Please review and take action.",
                $employee->institute_id,
                [
                    'exit_id' => $exit->id,
                    'task_type' => $taskType,
                    'action_url' => route('exit.approvals.index'),
                ]
            );

        } catch (\Exception $e) {
            Log::error('Failed to send task assignment notification: ' . $e->getMessage());
        }
    }

    public function updateApproval(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $approval = EmployeeExitApproval::where('id', $id)->first();
                
                if (!$approval) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Approval record not found.'
                    ], 404);
                }
                
                $currentUserId = auth()->id();
                $currentUser = auth()->user();
                
                if ($approval->approver_id != $currentUserId || $approval->status != 'Pending') {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not authorized to update this approval.'
                    ], 403);
                }
                
                DB::beginTransaction();
                
                $exit = $approval->exit;
                
                if (!$exit) {
                    throw new \Exception('Exit record not found.');
                }
                
                $employee = EmployeeDetails::where('employee_id', $exit->employee_id)->first();
                
                if (!$employee) {
                    throw new \Exception('Employee record not found.');
                }
                
                $action = $request->action;
                $comments = $request->comments;
                $employeeName = $employee->name ?? 'Employee';
                
                if ($action === 'approve') {
                    $approval->status = 'Approved';
                    $approval->approved_date = Carbon::now();
                    $approval->comments = $comments;
                    $approval->save();
                    
                    if ($exit->exit_status === 'pending_approval') {
                        $noticeDays = $exit->notice_period_days ?? 30;
                        $approvalDate = Carbon::now();
                        $noticeEndDate = $approvalDate->copy()->addDays($noticeDays);
                        
                        $exit->update([
                            'exit_status' => 'approved',
                            'approved_by' => $currentUserId,
                            'approved_at' => $approvalDate,
                            'notice_start_date' => $approvalDate,
                            'notice_end_date' => $noticeEndDate,
                            'proposed_last_working_date' => $noticeEndDate,
                        ]);
                        
                        // Mark other approvals
                        EmployeeExitApproval::where('exit_id', $exit->id)
                            ->where('status', 'Pending')
                            ->where('id', '!=', $approval->id)
                            ->update([
                                'status' => 'Approved',
                                'approved_date' => Carbon::now(),
                                'comments' => 'Already approved by ' . $currentUser->name
                            ]);
                        
                        $message = "✅ Exit request for <strong>{$employeeName}</strong> has been approved!";
                    } else {
                        $message = "✅ Exit request for <strong>{$employeeName}</strong> has been approved!";
                    }
                    
                } elseif ($action === 'reject') {
                    $approval->status = 'Rejected';
                    $approval->approved_date = Carbon::now();
                    $approval->comments = $comments;
                    $approval->save();
                    
                    $exit->update([
                        'exit_status' => 'rejected',
                        'rejected_by' => $currentUserId,
                        'rejected_at' => Carbon::now(),
                        'rejection_reason' => $comments
                    ]);
                    
                    EmployeeExitApproval::where('exit_id', $exit->id)
                        ->where('status', 'Pending')
                        ->where('id', '!=', $approval->id)
                        ->update([
                            'status' => 'Rejected',
                            'approved_date' => Carbon::now(),
                            'comments' => 'Rejected by ' . $currentUser->name
                        ]);
                    
                    $message = "❌ Exit request for <strong>{$employeeName}</strong> has been rejected.";
                } else {
                    throw new \Exception('Invalid action.');
                }
                
                DB::commit();
                
                $this->exitService->sendExitApprovalNotifications(
                    $exit, 
                    $action, 
                    $comments, 
                    $currentUser
                );
                
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'action' => $action,
                    'employeeName' => $employeeName,
                    'exit_id' => $exit->id
                ]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Approval update error: ' . $e->getMessage(), [
                    'approval_id' => $id,
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update approval: ' . $e->getMessage()
                ], 500);
            }
        }
        
        return redirect()->back()->with('error', 'Invalid request.');
    }

        /**
     * Show task assignment page for an exit
     */
    public function showAssignTasks($exitId)
    {
        $context = $this->getInstituteBranchContext();
        $instituteId = $context['institute_id'];
        
        // Get exit with employee details
        $exit = EmployeeExit::with(['employee.department'])->findOrFail($exitId);
        $employee = $exit->employee;
        
        // Get existing tasks
        $tasks = EmployeeExitTaskAssignment::where('exit_id', $exitId)->get();
        
        // Get departments for filters
        $departments = DB::table('departments')
            ->where('institute_id', $instituteId)
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->select('department_id', 'department')
            ->get();
        
        return view('instituteAdmin.EmployeeExit.assign-tasks', compact(
            'exit',
            'employee',
            'tasks',
            'departments'
        ));
    }

        /**
     * Save all task assignments
     */
    public function saveAllTasks(Request $request)
    {
        try {
            $exitId = $request->exit_id;
            $tasksData = $request->tasks;
            
            if (empty($tasksData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tasks to save.'
                ]);
            }
            
            DB::beginTransaction();
            
            foreach ($tasksData as $taskType => $taskData) {
                // Skip if no assigned person
                if (empty($taskData['assigned_to'])) {
                    continue;
                }
                
                if (!empty($taskData['task_id'])) {
                    // Update existing task
                    $task = EmployeeExitTaskAssignment::find($taskData['task_id']);
                    if ($task) {
                        $task->update([
                            'assigned_to_user_id' => $taskData['assigned_to'],
                            'instructions' => $taskData['instructions'] ?? null,
                            'deadline' => $taskData['deadline'] ?? null,
                            'status' => $taskData['status'] ?? 'pending',
                        ]);
                    }
                } else {
                    // Create new task
                    // Get user details
                    $assignedUser = User::find($taskData['assigned_to']);
                    $assignedEmployee = EmployeeDetails::where('user_id', $taskData['assigned_to'])->first();
                    
                    EmployeeExitTaskAssignment::create([
                        'exit_id' => $exitId,
                        'task_type' => $taskType,
                        'assigned_to_user_id' => $taskData['assigned_to'],
                        'assigned_to_name' => $assignedUser ? $assignedUser->name : 'Unknown',
                        'assigned_to_role' => $assignedEmployee ? $assignedEmployee->designation : 'HR/Admin',
                        'assigned_to_employee_id' => $assignedEmployee ? $assignedEmployee->employee_id : null,
                        'instructions' => $taskData['instructions'] ?? null,
                        'deadline' => $taskData['deadline'] ?? null,
                        'assigned_at' => Carbon::now(),
                        'assigned_by' => auth()->id(),
                        'status' => $taskData['status'] ?? 'pending'
                    ]);
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'All tasks saved successfully!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving tasks: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save tasks: ' . $e->getMessage()
            ], 500);
        }
    }

        /**
     * Update single task (for AJAX)
     */
    public function updateTask(Request $request)
    {
        try {
            $request->validate([
                'task_id' => 'required|exists:employee_exit_task_assignments,id',
                'assigned_to' => 'required|exists:users,id',
                'instructions' => 'nullable|string',
                'deadline' => 'nullable|date',
                'status' => 'nullable|in:pending,in-progress,completed',
            ]);
            
            $task = EmployeeExitTaskAssignment::findOrFail($request->task_id);
            
            // Get user details
            $assignedUser = User::find($request->assigned_to);
            $assignedEmployee = EmployeeDetails::where('user_id', $request->assigned_to)->first();
            
            $task->update([
                'assigned_to_user_id' => $request->assigned_to,
                'assigned_to_name' => $assignedUser ? $assignedUser->name : 'Unknown',
                'assigned_to_role' => $assignedEmployee ? $assignedEmployee->designation : 'HR/Admin',
                'assigned_to_employee_id' => $assignedEmployee ? $assignedEmployee->employee_id : null,
                'instructions' => $request->instructions,
                'deadline' => $request->deadline,
                'status' => $request->status ?? 'pending',
                'assigned_at' => Carbon::now(),
                'assigned_by' => auth()->id(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully!',
                'task' => $task
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error updating task: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update task: ' . $e->getMessage()
            ], 500);
        }
    }

}