<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeExit;
use App\Models\EmployeeExitTaskAssignment;
use App\Models\EmployeeDetails;
use App\Models\User;
use App\Models\EmployeeExitPolicy;
use App\Models\PolicyAssignment;
use App\Models\TaskRequirement;
use App\Traits\InstituteBranchAccess;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ExitTaskManagementController extends Controller
{
    use InstituteBranchAccess;
    
    public function index(Request $request)
    {
    $context = $this->getInstituteBranchContext();
    $userId = auth()->id();

    if (!$userId) {
        return redirect()->route('login')->with('error', 'Please login first.');
    }

    // Get employee details
    $employee = EmployeeDetails::where('user_id', $userId)
        ->where('institute_id', $context['institute_id'])
        ->when($context['branch_id'], function($query) use ($context) {
            return $query->where('branch_id', $context['branch_id']);
        })
        ->with('department')
        ->first();

    if (!$employee) {
        return redirect()->back()->with('error', 'Employee record not found.');
    }

    // Get tasks assigned to this user
    $query = EmployeeExitTaskAssignment::with([
        'exit.employee.department',
        'exit.exitPolicy',
        'assignedBy'
    ])
    ->where('assigned_to_user_id', $userId)
    ->whereHas('exit', function($q) use ($context) {
        $q->where('institute_id', $context['institute_id']);
        if ($context['branch_id']) {
            $q->where('branch_id', $context['branch_id']);
        }
    });

    // Apply filters
    if ($request->filled('status')) {
        if ($request->status === 'pending') {
            $query->whereIn('status', ['pending', 'in-progress']);
        } elseif ($request->status === 'completed') {
            $query->where('status', 'completed');
        } elseif ($request->status === 'overdue') {
            $query->where('status', '!=', 'completed')
                ->where('deadline', '<', Carbon::now());
        }
    }

    if ($request->filled('task_type')) {
        $query->where('task_type', $request->task_type);
    }

    $tasks = $query->orderBy('created_at', 'desc')->paginate(10);

    // Task types with full details
    $taskTypes = $this->getTaskTypes();

    // Assignable users (for filter dropdown)
    $assignableUsers = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['admin', 'superadmin', 'institute_admin', 'hr']);
        })
        ->where('institute_id', $context['institute_id'])
        ->when($context['branch_id'], function($query) use ($context) {
            return $query->where('branch_id', $context['branch_id']);
        })
        ->select('id', 'name')
        ->get();

    // Process tasks with ALL required details
    $processedTasks = [];
    foreach ($tasks as $task) {
        // Get requirements
        $requirements = $task->requirements ?? collect();
        $completedCount = $requirements->where('status', 'completed')->count();
        $totalCount = $requirements->count();

        // Get policy for this task
        $exit = $task->exit;
        $policy = null;
        $policyDate = null;

        if ($exit) {
            // Get employee details
            $employeeDetails = $exit->employee_id 
                ? EmployeeDetails::where('employee_id', $exit->employee_id)->first() 
                : null;
            
            if ($employeeDetails) {
                $exitType = $exit->exit_type ?? 'Resignation';
                $policy = $this->getEmployeePolicy($employeeDetails, $exitType);
            }
            
            // Fallback: get policy from task/exit
            if (!$policy) {
                $policy = $this->getPolicyForTask($task);
            }
            
            // Calculate policy-based due date
            if ($policy) {
                $policyDate = $this->getPolicyBasedDate($task, $policy);
            }
        }

        $isOverdue = $task->deadline && Carbon::parse($task->deadline)->isPast() && $task->status !== 'completed';

        $processedTasks[] = [
            'task' => $task,
            'requirements' => $requirements,
            'completed_count' => $completedCount,
            'total_count' => $totalCount,
            'completion_percentage' => $totalCount > 0 
                ? round(($completedCount / $totalCount) * 100) 
                : 0,
            'is_overdue' => $isOverdue,
            'can_complete' => $task->status !== 'completed',
            // ✅ ADD THESE MISSING KEYS
            'policy_details' => $policy,      // <-- Policy object
            'policy_date' => $policyDate,     // <-- Policy date array
            'has_policy' => $policy !== null, // <-- Boolean flag
        ];
    }

    // Statistics
    $statistics = [
        'total' => EmployeeExitTaskAssignment::where('assigned_to_user_id', $userId)
            ->whereHas('exit', function($q) use ($context) {
                $q->where('institute_id', $context['institute_id']);
                if ($context['branch_id']) {
                    $q->where('branch_id', $context['branch_id']);
                }
            })
            ->count(),
        'pending' => EmployeeExitTaskAssignment::where('assigned_to_user_id', $userId)
            ->whereIn('status', ['pending', 'in-progress'])
            ->whereHas('exit', function($q) use ($context) {
                $q->where('institute_id', $context['institute_id']);
                if ($context['branch_id']) {
                    $q->where('branch_id', $context['branch_id']);
                }
            })
            ->count(),
        'in_progress' => EmployeeExitTaskAssignment::where('assigned_to_user_id', $userId)
            ->where('status', 'in-progress')
            ->whereHas('exit', function($q) use ($context) {
                $q->where('institute_id', $context['institute_id']);
                if ($context['branch_id']) {
                    $q->where('branch_id', $context['branch_id']);
                }
            })
            ->count(),
        'completed' => EmployeeExitTaskAssignment::where('assigned_to_user_id', $userId)
            ->where('status', 'completed')
            ->whereHas('exit', function($q) use ($context) {
                $q->where('institute_id', $context['institute_id']);
                if ($context['branch_id']) {
                    $q->where('branch_id', $context['branch_id']);
                }
            })
            ->count(),
        'overdue' => EmployeeExitTaskAssignment::where('assigned_to_user_id', $userId)
            ->where('status', '!=', 'completed')
            ->where('deadline', '<', Carbon::now())
            ->whereHas('exit', function($q) use ($context) {
                $q->where('institute_id', $context['institute_id']);
                if ($context['branch_id']) {
                    $q->where('branch_id', $context['branch_id']);
                }
            })
            ->count(),
    ];

    return view('instituteAdmin.EmployeeExit.task-management', compact(
        'employee',
        'tasks',
        'processedTasks',
        'statistics',
        'taskTypes',
        'assignableUsers'
    ));
}

    /**
     * Get the applicable policy for an employee based on exit type
     * Priority: Individual > Department > All Departments
     * This matches the logic in EmployeeResignController
     */
    private function getEmployeePolicy($employee, $exitType = 'Resignation')
    {
        if (!$employee) {
            return null;
        }

        $employeeId = $employee->employee_id;
        $departmentId = $employee->department_id;

        // 1. Check for Individual Assignment (Highest Priority)
        $individualAssignment = PolicyAssignment::where('employee_id', $employeeId)
            ->where('exit_type', $exitType)
            ->where(function($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now());
            })
            ->with('policy')
            ->first();
     
        if ($individualAssignment && $individualAssignment->policy) {
            return $individualAssignment->policy;
        }

        // 2. Check for Department Assignment
        if ($departmentId) {
            $departmentAssignment = PolicyAssignment::where('department_id', $departmentId)
                ->where('exit_type', $exitType)
                ->where(function($query) {
                    $query->whereNull('expiry_date')
                        ->orWhere('expiry_date', '>=', now());
                })
                ->with('policy')
                ->first();

            if ($departmentAssignment && $departmentAssignment->policy) {
                return $departmentAssignment->policy;
            }
        }
   
        // 3. Check for All Departments Assignment (Lowest Priority)
        $allAssignment = PolicyAssignment::where('assignment_type', 'all_department')
            ->where('exit_type', $exitType)
            ->where(function($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now());
            })
            ->with('policy')
            ->first();

        if ($allAssignment && $allAssignment->policy) {
            return $allAssignment->policy;
        }

        return null;
    }

    /**
     * Calculate the policy-driven due date for a task based on the employee's
     * expected last working day and the number of days configured on the policy
     * for that task type
     */
    private function getPolicyBasedDate($task, $policy)
    {
        if (!$policy) {
            return null;
        }

        $exit = $task->exit;
        if (!$exit) {
            return null;
        }

        // Base date: when the exit was approved, falling back to when it was created
        $baseDate = null;
        if (!empty($exit->approved_at)) {
            $baseDate = Carbon::parse($exit->approved_at);
        } elseif (!empty($exit->created_at)) {
            $baseDate = Carbon::parse($exit->created_at);
        }

        if (!$baseDate) {
            return null;
        }

        // Notice period: prefer whatever was stored on the exit itself,
        // fall back to the policy's default notice period
        $noticeDuration = $exit->notice_period_days
            ?? $policy->default_notice_period
            ?? 45;

        $expectedExitDate = $baseDate->copy()->addDays((int) $noticeDuration);

        $days = null;
        $direction = 'before';

        switch ($task->task_type) {
            case 'kt':
                $days = $policy->kt_days ?? 5;
                $direction = 'before';
                break;
            case 'asset_clearance':
                $days = $policy->clearance_days ?? 7;
                $direction = 'before';
                break;
            case 'exit_interview':
                $days = $policy->interview_days ?? 5;
                $direction = 'before';
                break;
            case 'fnf':
                $days = $policy->fnf_processing_days ?? 30;
                $direction = 'after';
                break;
            default:
                return null;
        }

        $days = (int) $days;
        $dueDate = $direction === 'before'
            ? $expectedExitDate->copy()->subDays($days)
            : $expectedExitDate->copy()->addDays($days);

        return [
            'due_date' => $dueDate->format('d M, Y'),
            'due_date_iso' => $dueDate->toDateString(),
            'days' => $days,
            'direction' => $direction,
            'label' => $days . ' day' . ($days == 1 ? '' : 's') . ' ' . $direction . ' last working day',
            'expected_exit_date' => $expectedExitDate->format('d M, Y'),
            'is_overdue' => $dueDate->isPast() && $task->status !== 'completed'
        ];
    }

    /**
     * Get or create requirements for a task based on its type
     */
    private function getOrCreateRequirements($task, $policy)
    {
        // Return existing requirements if already generated
        $existingRequirements = TaskRequirement::where('task_id', $task->id)->get();
        if ($existingRequirements->count() > 0) {
            return $existingRequirements;
        }

        // Exit interview has no requirements - just return empty collection
        if ($task->task_type === 'exit_interview') {
            return collect();
        }

        // Get exit and employee
        $exit = $task->exit;
        if (!$exit || !$exit->employee_id) {
            return collect();
        }

        $employee = EmployeeDetails::where('employee_id', $exit->employee_id)->first();
        if (!$employee) {
            return collect();
        }

        // Get applicable policy for this employee
        $exitType = $exit->exit_type ?? 'Resignation';
        $applicablePolicy = $this->getEmployeePolicy($employee, $exitType);

        // Fallback to the policy passed from index()
        if (!$applicablePolicy) {
            $applicablePolicy = $policy;
        }

        // No policy assigned - create defaults
        if (!$applicablePolicy) {
            return $this->createDefaultRequirements($task);
        }

        // Decode policy JSON fields
        $ktRequirements = $this->decodeJson($applicablePolicy->kt_requirements) ?? [];
        $fnfItems = $this->decodeJson($applicablePolicy->fnf_items) ?? [];
        $additionalRequirements = $this->decodeJson($applicablePolicy->additional_requirements) ?? [];

        // Get requirements based on task type
        $requirementKeys = $this->getRequirementKeysFromPolicy(
            $task,
            $ktRequirements,
            $fnfItems,
            $additionalRequirements
        );

        // If no requirements, return empty
        if (empty($requirementKeys)) {
            return collect();
        }

        $requirementLabels = $this->getRequirementLabels();
        $additionalLabels = $this->getAdditionalRequirementLabels();

        $createdRequirements = [];

        foreach ($requirementKeys as $index => $key) {
            // Skip if key is empty or null
            if (empty($key)) {
                continue;
            }

            $label = $requirementLabels[$key]
                ?? $additionalLabels[$key]
                ?? ucwords(str_replace('_', ' ', $key));

            $createdRequirements[] = TaskRequirement::create([
                'task_id'           => $task->id,
                'requirement_key'   => $key,
                'requirement_label' => $label,
                'status'            => 'pending',
                'order'             => $index + 1,
            ]);
        }

        return collect($createdRequirements);
    }

    /**
     * Get requirement keys from policy based on task type
     */
    private function getRequirementKeysFromPolicy(
        $task,
        $ktRequirements = [],
        $fnfItems = [],
        $additionalRequirements = []
    )
    {
        $requirementKeys = [];

        switch ($task->task_type) {
            case 'kt':
                // KT uses kt_requirements
                $requirementKeys = is_array($ktRequirements) ? $ktRequirements : [];
                break;
                
            case 'fnf':
                // FNF uses fnf_items
                $requirementKeys = is_array($fnfItems) ? $fnfItems : [];
                break;
                
            case 'asset_clearance':
                // Asset clearance uses additional_requirements (which contains asset items)
                $requirementKeys = is_array($additionalRequirements) ? $additionalRequirements : [];
                break;
                
            case 'exit_interview':
                // Exit interview has no requirements
                $requirementKeys = [];
                break;
                
            default:
                $requirementKeys = [];
        }

        return array_values(array_unique(array_filter($requirementKeys)));
    }

    /**
     * Create default requirements when no policy is found
     */
    private function createDefaultRequirements($task)
    {
        // Exit interview has no requirements
        if ($task->task_type === 'exit_interview') {
            return collect();
        }

        $requirementKeys = [];
        
        if ($task->task_type === 'kt') {
            $requirementKeys = ['documentation', 'project_handover', 'code_handover', 'process_handover', 'training'];
        } elseif ($task->task_type === 'asset_clearance') {
            $requirementKeys = ['laptop', 'desktop', 'access_card', 'phone', 'charger'];
        } elseif ($task->task_type === 'fnf') {
            $requirementKeys = ['salary_settlement', 'leave_encashment', 'reimbursement', 'pf_settlement', 'gratuity'];
        }
        
        $requirementLabels = $this->getRequirementLabels();
        $createdRequirements = [];
        
        foreach ($requirementKeys as $key) {
            $label = $requirementLabels[$key] ?? ucwords(str_replace('_', ' ', $key));
            $createdRequirements[] = TaskRequirement::create([
                'task_id' => $task->id,
                'requirement_key' => $key,
                'requirement_label' => $label,
                'status' => 'pending',
                'order' => count($createdRequirements) + 1
            ]);
        }
        
        return collect($createdRequirements);
    }

    /**
     * Get the policy for a task (fallback method)
     */
    private function getPolicyForTask($task)
    {
        $exit = $task->exit;
        if (!$exit) {
            return null;
        }
        
        // Try from exit_policy_id
        if ($exit->exit_policy_id) {
            $policy = EmployeeExitPolicy::find($exit->exit_policy_id);
            if ($policy) {
                return $policy;
            }
        }
        
        // Try from exitPolicy relationship
        if (method_exists($exit, 'exitPolicy') && $exit->exitPolicy) {
            return $exit->exitPolicy;
        }
        
        // Try from employee
        if ($exit->employee_id) {
            $employee = EmployeeDetails::with('department')->where('employee_id', $exit->employee_id)->first();
            if ($employee) {
                $exitType = $exit->exit_type ?? 'Resignation';
                $policy = $this->getEmployeePolicy($employee, $exitType);
                if ($policy) {
                    return $policy;
                }
            }
        }
        
        // Fallback: any active policy
        if ($exit->institute_id) {
            $anyPolicy = EmployeeExitPolicy::where('institute_id', $exit->institute_id)
                ->where('is_active', true)
                ->first();
            
            if ($anyPolicy) {
                return $anyPolicy;
            }
        }
        
        return null;
    }

    /**
     * Decode JSON data
     */
    private function decodeJson($data)
    {
        if (is_string($data)) {
            return json_decode($data, true);
        }
        return $data;
    }

    /**
     * Get requirement labels mapping
     */
    private function getRequirementLabels()
    {
        return [
            // KT Requirements
            'documentation' => 'Documentation Handover',
            'project_handover' => 'Project/Work Handover',
            'code_handover' => 'Code/System Handover',
            'client_handover' => 'Client/Stakeholder Handover',
            'process_handover' => 'Process Handover',
            'training' => 'Training & Support',
            'knowledge_docs' => 'Knowledge Base Documentation',
            
            // Asset Clearance (from additional_requirements)
            'asset_return' => 'Asset Return',
            'id_card_return' => 'ID Card Return',
            'laptop' => 'Laptop Return',
            'desktop' => 'Desktop Return',
            'access_card' => 'Access Card Return',
            'uniform' => 'Uniform Return',
            'keys' => 'Keys Return',
            'phone' => 'Phone/Device Return',
            'charger' => 'Charger Return',
            'stationery' => 'Stationery Return',
            'parking_pass' => 'Parking Pass Return',
            'company_assets' => 'Other Company Assets',
            
            // FNF Items
            'salary_settlement' => 'Salary Settlement',
            'leave_encashment' => 'Leave Encashment',
            'bonus_settlement' => 'Bonus/Incentive',
            'reimbursement' => 'Reimbursement',
            'pf_settlement' => 'PF Settlement',
            'esi_settlement' => 'ESI Settlement',
            'gratuity' => 'Gratuity',
            'other_dues' => 'Other Dues',
            
            // Exit Interview (these won't be used since exit interview has no requirements)
            'interview_scheduled' => 'Interview Scheduled',
            'feedback_form' => 'Feedback Form Submission',
            'hr_meeting' => 'HR Meeting Completed',
            'manager_meeting' => 'Manager Meeting Completed'
        ];
    }

    /**
     * Get additional requirement labels mapping
     */
    private function getAdditionalRequirementLabels()
    {
        return [
            'certificate_handover' => 'Certificate Handover',
            'exit_form' => 'Exit Form Submission',
            'final_settlement' => 'Final Settlement Agreement',
            'nda_signing' => 'NDA Signing',
            'separation_letter' => 'Separation Letter',
            'exit_interview_form' => 'Exit Interview Form',
            'clearance_form' => 'Clearance Form',
            'handover_report' => 'Handover Report',
            'exit_checklist' => 'Exit Checklist Completion',
            'notice_period_compliance' => 'Notice Period Compliance',
            'asset_acknowledgment' => 'Asset Acknowledgment'
        ];
    }

    /**
     * Get task statistics
     */
    private function getTaskStatistics($instituteId, $branchId)
    {
        $baseQuery = EmployeeExitTaskAssignment::whereHas('exit', function($q) use ($instituteId, $branchId) {
            $q->where('institute_id', $instituteId);
            if ($branchId) {
                $q->where('branch_id', $branchId);
            }
        });

        return [
            'total' => $baseQuery->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'in_progress' => (clone $baseQuery)->where('status', 'in-progress')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'overdue' => (clone $baseQuery)->where('status', '!=', 'completed')
                ->where('deadline', '<', Carbon::now())
                ->count(),
        ];
    }

    /**
     * Get task types
     */
    private function getTaskTypes()
    {
        return [
            'kt' => [
                'label' => 'Knowledge Transfer',
                'icon' => 'fa-chalkboard-teacher',
                'color' => 'primary',
                'icon_color' => '#4f46e5',
                'bg_color' => '#eef2ff'
            ],
            'exit_interview' => [
                'label' => 'Exit Interview',
                'icon' => 'fa-comments',
                'color' => 'warning',
                'icon_color' => '#d97706',
                'bg_color' => '#fef3c7'
            ],
            'asset_clearance' => [
                'label' => 'Asset Clearance',
                'icon' => 'fa-laptop',
                'color' => 'info',
                'icon_color' => '#0891b2',
                'bg_color' => '#cffafe'
            ],
            'fnf' => [
                'label' => 'FNF Settlement',
                'icon' => 'fa-file-invoice-dollar',
                'color' => 'danger',
                'icon_color' => '#dc2626',
                'bg_color' => '#fee2e2'
            ]
        ];
    }

    /**
     * Toggle a requirement status
     */
    public function toggleRequirement(Request $request)
    {
        try {
            $request->validate([
                'requirement_id' => 'required|exists:task_requirements,id',
                'status' => 'required|in:pending,in_progress,completed'
            ]);

            $requirement = TaskRequirement::findOrFail($request->requirement_id);
            $task = EmployeeExitTaskAssignment::findOrFail($requirement->task_id);
            
            // Update requirement status
            $requirement->status = $request->status;
            if ($request->status === 'completed') {
                $requirement->completed_at = Carbon::now();
                $requirement->completed_by = auth()->id();
                $requirement->notes = $request->notes ?? null;
            }
            $requirement->save();

            // Check if all requirements are completed
            $allRequirements = TaskRequirement::where('task_id', $task->id)->get();
            $completedCount = $allRequirements->where('status', 'completed')->count();
            $totalCount = $allRequirements->count();

            // Update task status if all requirements are completed
            if ($completedCount === $totalCount && $totalCount > 0) {
                if ($task->status !== 'completed') {
                    $task->status = 'completed';
                    $task->completed_at = Carbon::now();
                    $task->completed_by = auth()->id();
                    $task->save();
                }
            } elseif ($task->status === 'completed' && $completedCount < $totalCount) {
                // If task was completed but some requirements got uncompleted
                $task->status = 'in-progress';
                $task->completed_at = null;
                $task->completed_by = null;
                $task->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Requirement status updated successfully!',
                'requirement' => $requirement,
                'task_status' => $task->status,
                'completed_count' => $completedCount,
                'total_count' => $totalCount,
                'completion_percentage' => $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0
            ]);

        } catch (\Exception $e) {
            Log::error('Error toggling requirement: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update requirement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get task details with requirements
     */
    public function getTaskDetails($taskId)
    {
        try {
            $task = EmployeeExitTaskAssignment::with([
                'exit.employee.department',
                'exit.exitPolicy',
                'assignedTo',
                'assignedBy',
                'requirements'
            ])->findOrFail($taskId);

            $exit = $task->exit;
            $employee = $exit && $exit->employee_id ? EmployeeDetails::where('employee_id', $exit->employee_id)->first() : null;
            
            // Get the applicable policy
            $policy = null;
            if ($employee) {
                $exitType = $exit->exit_type ?? 'Resignation';
                $policy = $this->getEmployeePolicy($employee, $exitType);
            }
            
            if (!$policy) {
                $policy = $this->getPolicyForTask($task);
            }
            
            $taskTypes = $this->getTaskTypes();
            $policyDate = $this->getPolicyBasedDate($task, $policy);

            return response()->json([
                'success' => true,
                'task' => $task,
                'employee' => $task->exit->employee ?? null,
                'assigned_to' => $task->assignedTo,
                'assigned_by' => $task->assignedBy,
                'requirements' => $task->requirements,
                'policy' => $policy,
                'has_policy' => $policy !== null,
                'task_type_info' => $taskTypes[$task->task_type] ?? null,
                'policy_date' => $policyDate,
                'completed_count' => $task->requirements->where('status', 'completed')->count(),
                'total_count' => $task->requirements->count(),
                'completion_percentage' => $task->requirements->count() > 0 
                    ? round(($task->requirements->where('status', 'completed')->count() / $task->requirements->count()) * 100) 
                    : 0
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting task details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Task not found: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update task status
     */
    public function updateTaskStatus(Request $request)
    {
        try {
            $request->validate([
                'task_id' => 'required|exists:employee_exit_task_assignments,id',
                'status' => 'required|in:pending,in-progress,completed',
                'completion_notes' => 'nullable|string|max:500'
            ]);

            $task = EmployeeExitTaskAssignment::findOrFail($request->task_id);
            $userId = auth()->id();

            $task->status = $request->status;
            $task->updated_by = $userId;
            
            if ($request->status === 'completed') {
                // Check if all requirements are completed
                $requirements = TaskRequirement::where('task_id', $task->id)->get();
                $completedCount = $requirements->where('status', 'completed')->count();
                $totalCount = $requirements->count();
                
                if ($totalCount > 0 && $completedCount < $totalCount) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot mark task as completed. Please complete all requirements first. (' . $completedCount . '/' . $totalCount . ' completed)'
                    ], 400);
                }
                
                $task->completed_at = Carbon::now();
                $task->completed_by = $userId;
                $task->completion_notes = $request->completion_notes;
            } else {
                $task->completed_at = null;
                $task->completed_by = null;
                $task->completion_notes = null;
            }

            $task->save();

            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully!',
                'task' => $task,
                'requirements' => TaskRequirement::where('task_id', $task->id)->get()
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating task status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update task: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update task statuses
     */
    public function bulkUpdateStatus(Request $request)
    {
        try {
            $request->validate([
                'task_ids' => 'required|string',
                'status' => 'required|in:pending,in-progress,completed'
            ]);

            $taskIds = json_decode($request->task_ids, true);
            
            if (!is_array($taskIds) || empty($taskIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid task IDs provided'
                ], 400);
            }

            $status = $request->status;
            $userId = auth()->id();

            DB::beginTransaction();

            $updatedCount = 0;
            $failedCount = 0;

            foreach ($taskIds as $taskId) {
                $task = EmployeeExitTaskAssignment::find($taskId);
                if ($task) {
                    if ($status === 'completed') {
                        // Check if all requirements are completed
                        $requirements = TaskRequirement::where('task_id', $task->id)->get();
                        $completedCount = $requirements->where('status', 'completed')->count();
                        $totalCount = $requirements->count();
                        
                        if ($totalCount > 0 && $completedCount < $totalCount) {
                            $failedCount++;
                            continue;
                        }
                    }
                    
                    $task->status = $status;
                    $task->updated_by = $userId;
                    
                    if ($status === 'completed') {
                        $task->completed_at = Carbon::now();
                        $task->completed_by = $userId;
                    } else {
                        $task->completed_at = null;
                        $task->completed_by = null;
                        $task->completion_notes = null;
                    }
                    
                    $task->save();
                    $updatedCount++;
                } else {
                    $failedCount++;
                }
            }

            DB::commit();

            $message = "{$updatedCount} tasks updated successfully!";
            if ($failedCount > 0) {
                $message .= " {$failedCount} tasks failed to update (requirements not completed).";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'updated_count' => $updatedCount,
                'failed_count' => $failedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error bulk updating tasks: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update tasks: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add note to requirement
     */
    public function addRequirementNote(Request $request)
    {
        try {
            $request->validate([
                'requirement_id' => 'required|exists:task_requirements,id',
                'notes' => 'required|string|max:500'
            ]);

            $requirement = TaskRequirement::findOrFail($request->requirement_id);
            $requirement->notes = $request->notes;
            $requirement->save();

            return response()->json([
                'success' => true,
                'message' => 'Note added successfully!',
                'requirement' => $requirement
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add note: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get employee policy API
     */
    public function getEmployeePolicyApi($employeeId)
    {
        try {
            $employee = EmployeeDetails::find($employeeId);
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found'
                ], 404);
            }
            
            $exitType = request('exit_type', 'Resignation');
            $policy = $this->getEmployeePolicy($employee, $exitType);
            
            if ($policy) {
                return response()->json([
                    'success' => true,
                    'policy' => $policy,
                    'kt_requirements' => $this->decodeJson($policy->kt_requirements) ?? [],
                    'fnf_items' => $this->decodeJson($policy->fnf_items) ?? [],
                    'additional_requirements' => $this->decodeJson($policy->additional_requirements) ?? [],
                    'notice_period' => $policy->default_notice_period
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No policy found for this employee'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}