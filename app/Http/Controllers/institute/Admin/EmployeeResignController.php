<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeDetails;
use App\Models\EmployeeExit;
use App\Models\EmployeeExitApproval;
use App\Models\EmployeeExitPolicy;
use App\Models\PolicyAssignment;
use App\Models\EmployeeExitTaskAssignment;
use App\Models\User;
use App\Services\EmployeeExitService;
use App\Traits\InstituteBranchAccess;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EmployeeResignController extends Controller
{
    use InstituteBranchAccess;

    protected $exitService;

    public function __construct(EmployeeExitService $exitService)
    {
        $this->exitService = $exitService;
    }

    /**
     * Get the applicable policy for an employee based on exit type
     * Priority: Individual > Department > All Departments
     */
    private function getEmployeePolicy($employee, $exitType = 'Resignation')
    {
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
        $allAssignment = PolicyAssignment::where('assignment_type', 'all')
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
     * Get notice period for employee from policy
     * Checks employment type overrides first, then default
     */
    private function getEmployeeNoticePeriod($employee, $policy)
    {
        if (!$policy) {
            return 30; // Default fallback
        }

        $employmentType = $employee->employment_type;
        
        // Decode employment_notice_periods JSON
        $employmentNoticePeriods = $this->decodeJson($policy->employment_notice_periods);
        $employmentCustomDays = $this->decodeJson($policy->employment_custom_days);

        // Check if there's an override for this employment type
        if ($employmentType && isset($employmentNoticePeriods[$employmentType])) {
            $period = $employmentNoticePeriods[$employmentType];
            
            // Check if it's custom days
            if ($period === 'custom' && isset($employmentCustomDays[$employmentType])) {
                return (int) $employmentCustomDays[$employmentType];
            }
            
            // Return the specific period (30, 45, 60, 90)
            return (int) $period;
        }

        // Return default notice period from policy
        return (int) $policy->default_notice_period;
    }

    /**
     * Get policy details for display
     */
    private function getPolicyDetails($policy, $employee)
    {
        if (!$policy) {
            return (object) [
                'policy_id' => null,
                'policy_name' => 'Default Policy',
                'policy_code' => 'DEFAULT',
                'exit_type' => 'Resignation',
                'notice_period_days' => 45,
                'default_notice_period' => 45,
                'exit_interview_required' => true,
                'interview_days' => 5,
                'fnf_required' => true,
                'fnf_processing_days' => 30,
                'fnf_settlement_type' => 'standard',
                'fnf_items' => [],
                'kt_required' => true,
                'kt_days' => 5,
                'kt_requirements' => [],
                'clearance_workflow' => 'sequential',
                'clearance_days' => 7,
                'description' => 'Default exit policy',
            ];
        }

        $noticePeriod = $this->getEmployeeNoticePeriod($employee, $policy);
        
        return (object) [
            'policy_id' => $policy->id,
            'policy_name' => $policy->policy_name ?? 'Default Policy',
            'policy_code' => $policy->policy_code ?? 'DEFAULT',
            'exit_type' => $policy->exit_type ?? 'Resignation',
            'notice_period_days' => $noticePeriod ?? 45,
            'default_notice_period' => $policy->default_notice_period ?? 45,
            'employment_notice_periods' => $this->decodeJson($policy->employment_notice_periods),
            'employment_custom_days' => $this->decodeJson($policy->employment_custom_days),
            'exit_interview_required' => $policy->exit_interview_required ?? true,
            'interview_days' => $policy->interview_days ?? 5,
            'fnf_required' => $policy->fnf_required ?? true,
            'fnf_processing_days' => $policy->fnf_processing_days ?? 30,
            'fnf_settlement_type' => $policy->fnf_settlement_type ?? 'standard',
            'fnf_items' => $this->decodeJson($policy->fnf_items) ?? [],
            'kt_required' => $policy->kt_required ?? true,
            'kt_days' => $policy->kt_days ?? 5,
            'kt_requirements' => $this->decodeJson($policy->kt_requirements) ?? [],
            'additional_requirements' => $this->decodeJson($policy->additional_requirements) ?? [],
            'clearance_workflow' => $policy->clearance_workflow ?? 'sequential',
            'clearance_days' => $policy->clearance_days ?? 7,
            'description' => $policy->description ?? '',
            'terms_conditions' => $policy->terms_conditions ?? '',
            'is_active' => $policy->is_active ?? true,
            'created_at' => $policy->created_at,
            'updated_at' => $policy->updated_at,
        ];
    }

    /**
     * Get task assignments for an exit with additional user details
     */
    private function getTaskAssignments($exitId)
    {
        if (!$exitId) {
            return collect();
        }
        
        $tasks = EmployeeExitTaskAssignment::where('exit_id', $exitId)->get();
        
        // Enrich with additional user details
        foreach ($tasks as $task) {
            if ($task->assigned_to_user_id) {
                $user = User::find($task->assigned_to_user_id);
                if ($user) {
                    $task->assigned_user_email = $user->email;
                    $task->assigned_user_phone = $user->phone ?? null;
                }
                
                // Get employee details for the assigned user
                $assignedEmployee = EmployeeDetails::where('user_id', $task->assigned_to_user_id)->first();
                if ($assignedEmployee) {
                    $task->assigned_employee_designation = $assignedEmployee->designation;
                    $task->assigned_employee_code = $assignedEmployee->employee_code;
                    $task->assigned_employee_profile_photo = $assignedEmployee->profile_photo ?? null;
                }
            }
        }
        
        return $tasks->keyBy('task_type');
    }

    /**
     * Show employee exit dashboard
     */
    public function index()
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
        
        // Get exit policy for this employee (Resignation type)
        $policy = $this->getEmployeePolicy($employee, 'Resignation');
        $exitPolicy = $this->getPolicyDetails($policy, $employee);
        
        // Get active exit process
        $activeExit = EmployeeExit::where('employee_id', $employee->employee_id)
            ->whereIn('exit_status', ['pending_approval', 'notice_period', 'approved'])
            ->with(['approvals' => function($q) {
                $q->orderBy('step_number', 'asc');
            }])
            ->first();
        
        // If no active exit, check for exited
        if (!$activeExit) {
            $activeExit = EmployeeExit::where('employee_id', $employee->employee_id)
                ->where('exit_status', 'exited')
                ->whereNull('actual_exit_date')
                ->first();
        }
        
        // Get task assignments if there's an active exit
        $taskAssignments = $activeExit ? $this->getTaskAssignments($activeExit->id) : collect();
        
        // Get exit history
        $exitHistory = EmployeeExit::where('employee_id', $employee->employee_id)
            ->whereIn('exit_status', ['exited', 'cancelled', 'rejected'])
            ->where(function($query) {
                $query->where('exit_status', '!=', 'exited')
                    ->orWhereNotNull('actual_exit_date');
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate notice period details
        $noticeDetails = null;
        if ($activeExit && $activeExit->exit_status === 'notice_period') {
            $noticeDetails = $this->exitService->calculateNoticePeriodDetails($activeExit);
        }
        
        // Build exit journey stages
        $exitJourney = $this->buildExitJourney($activeExit, $exitPolicy, $noticeDetails, $taskAssignments);
        
        $hasActiveExit = $activeExit !== null;
        $isAdminInitiated = $activeExit && $activeExit->initiation_source === 'admin';
        $isOnHold = $activeExit && $activeExit->exit_status === 'pending_approval';
        $isNoticePeriod = $activeExit && $activeExit->exit_status === 'notice_period';
        $isExited = $activeExit && $activeExit->exit_status === 'exited';
        $isApproved = $activeExit && $activeExit->exit_status === 'approved';
        $hasPolicy = $exitPolicy !== null;

        // Add this to the compact or view data
        $isAdminInitiated = $activeExit && $activeExit->initiation_source === 'admin';
        $exitType = $activeExit ? $activeExit->exit_type : 'Resignation';
        
        return view('instituteAdmin.EmployeeExit.EmployeeExitDashboard', compact(
            'employee',
            'exitPolicy',
            'activeExit',
            'exitHistory',
            'noticeDetails',
            'hasActiveExit',
            'isAdminInitiated',
            'isOnHold',
            'isAdminInitiated',  
            'exitType',          
            'isNoticePeriod',
            'isExited',
            'isApproved',
            'exitJourney',
            'hasPolicy',
            'taskAssignments'
        ));
    }


    /**
     * Build the exit journey stages with assignment details
     */
    private function buildExitJourney($activeExit, $exitPolicy, $noticeDetails, $taskAssignments)
    {
        $journey = [];
        $hasActiveExit = $activeExit !== null;
        $hasPolicy = $exitPolicy !== null;
        $currentStatus = $activeExit ? $activeExit->exit_status : null;
        $isExited = $currentStatus === 'exited';
        $isNoticePeriod = $currentStatus === 'notice_period';
        $isPendingApproval = $currentStatus === 'pending_approval';
        $isApproved = $currentStatus === 'approved';
        
        // Get initiation source and exit type
        $initiationSource = $activeExit ? ($activeExit->initiation_source ?? 'employee') : 'employee';
        $exitType = $activeExit ? ($activeExit->exit_type ?? 'Resignation') : 'Resignation';
        $isAdminInitiated = $initiationSource === 'admin';
        
        // Get the approval date or resignation date as base
        $baseDate = null;
        if ($activeExit) {
            if ($activeExit->approved_at) {
                $baseDate = Carbon::parse($activeExit->approved_at);
            } elseif ($activeExit->created_at) {
                $baseDate = Carbon::parse($activeExit->created_at);
            }
        }
        
        // Calculate expected exit date
        $expectedExitDate = null;
        if ($baseDate && $exitPolicy) {
            $noticeDuration = $exitPolicy->notice_period_days ?? 45;
            $expectedExitDate = $baseDate->copy()->addDays($noticeDuration);
        }
        
        // ============================================================
        // STEP 1: EXIT SUBMISSION (was: RESIGNATION SUBMISSION)
        // ============================================================
        $submissionDate = $activeExit ? Carbon::parse($activeExit->created_at) : null;
        $status1 = $hasActiveExit ? 'completed' : 'pending';
        $isLocked = !$hasPolicy;

        // Determine title, icon, description based on who initiated
        if ($isAdminInitiated) {
            $title = 'Exit Initiated by Admin';
            $icon = 'fas fa-user-cog';
            $description = $hasActiveExit 
                ? "Admin has initiated your exit process. Type: " . ucwords($exitType)
                : "Admin will initiate your exit process.";
            $statusLabel = $status1 === 'completed' ? 'Completed' : 'Action Required';
            $dataSubmittedAt = $submissionDate ? $submissionDate->format('d M Y g:i A') : 'Not initiated';
            $dataExitType = ucwords(str_replace('_', ' ', $exitType));
            $dataInitiationSource = 'Admin';
            $color = 'secondary';
        } else {
            $title = 'Submit Resignation';
            $icon = 'fas fa-pen';
            $description = $hasActiveExit 
                ? 'Your resignation has been submitted successfully.' 
                : 'Submit your resignation to start the exit process.';
            $statusLabel = $status1 === 'completed' ? 'Completed' : 'Action Required';
            $dataSubmittedAt = $submissionDate ? $submissionDate->format('d M Y g:i A') : 'Not submitted';
            $dataExitType = $activeExit ? ucwords(str_replace('_', ' ', $activeExit->exit_type ?? 'N/A')) : 'N/A';
            $dataInitiationSource = ucfirst($initiationSource ?? 'N/A');
            $color = 'primary';
        }

        $journey[] = [
            'key' => 'submission',
            'title' => $title,
            'icon' => $icon,
            'color' => $color,
            'status' => $status1,
            'status_label' => $statusLabel,
            'date' => $submissionDate ? $submissionDate->format('d M, Y') : null,
            'description' => $description,
            'is_enabled' => true,
            'is_first' => true,
            'is_locked' => $isLocked,
            'is_admin_initiated' => $isAdminInitiated,
            'exit_type' => $exitType,
            'initiation_source' => $initiationSource,
            'assigned_to' => null,
            'data' => [
                'submitted_at' => $dataSubmittedAt,
                'exit_type' => $dataExitType,
                'initiation_source' => $dataInitiationSource,
                'exit_reason' => $activeExit ? ucwords(str_replace('_', ' ', $activeExit->exit_reason ?? 'N/A')) : 'N/A'
            ]
        ];

        // ============================================================
        // STEP 2: APPROVAL
        // ============================================================
        if ($hasPolicy) {
            $approvalStatus = 'pending';
            $approvalDate = null;
            $approvers = [];
            
            if ($hasActiveExit) {
                if ($isPendingApproval) {
                    $approvalStatus = 'in-progress';
                } elseif ($isNoticePeriod || $isApproved || $isExited) {
                    $approvalStatus = 'completed';
                    $approvalDate = $activeExit->approved_at ? Carbon::parse($activeExit->approved_at) : $submissionDate;
                    $approvers = $activeExit->approvals ?? [];
                }
            }

            // For admin-initiated exits, show that no approval is needed
            $isApprovalLocked = !$hasActiveExit || $isPendingApproval;
            
            // If admin initiated and already in notice period, show as auto-approved
            if ($isAdminInitiated && ($isNoticePeriod || $isApproved || $isExited)) {
                $approvalStatus = 'completed';
                $approvalDate = $activeExit->created_at ? Carbon::parse($activeExit->created_at) : null;
            }

            $approvalDescription = $isAdminInitiated 
                ? ($approvalStatus === 'completed' ? 'Exit was auto-approved by admin.' : 'Waiting for admin approval.')
                : ($approvalStatus === 'completed' ? 'Your resignation has been approved.' : 'Waiting for admin approval.');

            $journey[] = [
                'key' => 'approval',
                'title' => 'Request Status',
                'icon' => 'fas fa-user-check',
                'color' => 'info',
                'status' => $approvalStatus,
                'status_label' => $approvalStatus === 'completed' ? 'Approved' : ($approvalStatus === 'in-progress' ? 'Pending' : 'Pending'),
                'date' => $approvalDate ? $approvalDate->format('d M, Y') : null,
                'description' => $approvalDescription,
                'is_enabled' => true,
                'is_locked' => $isApprovalLocked,
                'is_admin_initiated' => $isAdminInitiated,
                'assigned_to' => null,
                'data' => [
                    'approvers' => $approvers,
                    'approved_at' => $approvalDate ? $approvalDate->format('d M Y g:i A') : ($hasActiveExit ? 'Pending' : 'Will start after submission'),
                    'status' => $hasActiveExit ? ucwords(str_replace('_', ' ', $currentStatus)) : 'Not initiated'
                ]
            ];
        }

        // ============================================================
        // STEP 3: NOTICE PERIOD
        // ============================================================
        if ($hasPolicy) {
            $noticeStatus = 'pending';
            $noticeStart = null;
            $noticeEnd = null;
            $daysRemaining = 0;
            $noticeDuration = $exitPolicy->notice_period_days ?? $exitPolicy->default_notice_period ?? 45;
            
            if ($hasActiveExit) {
                if ($isNoticePeriod) {
                    $noticeStatus = 'in-progress';
                    $noticeStart = $noticeDetails['start_date'] ?? ($activeExit->approved_at ? Carbon::parse($activeExit->approved_at) : null);
                    $noticeEnd = $noticeDetails['end_date'] ?? $expectedExitDate;
                    $daysRemaining = $noticeDetails['days_remaining'] ?? 0;
                } elseif ($isApproved || $isExited) {
                    $computedNoticeEnd = $noticeDetails['end_date']
                        ?? ($expectedExitDate ? $expectedExitDate->format('d M, Y') : null);
                    $computedNoticeStart = $noticeDetails['start_date']
                        ?? ($activeExit->approved_at ? Carbon::parse($activeExit->approved_at)->format('d M, Y') : null);

                    $isNoticeOver = $isExited
                        || ($computedNoticeEnd && Carbon::parse($computedNoticeEnd)->isPast());

                    $noticeStatus = $isNoticeOver ? 'completed' : 'in-progress';
                    $noticeStart = $computedNoticeStart;
                    $noticeEnd = $computedNoticeEnd;

                    if ($noticeStart && $noticeEnd) {
                        $start = Carbon::parse($noticeStart);
                        $end = Carbon::parse($noticeEnd);
                        if ($isNoticeOver) {
                            $daysRemaining = $end->diffInDays($start);
                        } else {
                            $daysRemaining = max(0, now()->diffInDays($end, false));
                        }
                    }
                } elseif ($isPendingApproval) {
                    $noticeStatus = 'pending';
                    $noticeStart = $submissionDate ? $submissionDate->format('d M, Y') : null;
                    if ($noticeStart) {
                        $startDate = Carbon::parse($submissionDate);
                        $noticeEnd = $startDate->copy()->addDays($noticeDuration)->format('d M, Y');
                    }
                }
            }

            $isNoticeLocked = !$hasActiveExit || $isPendingApproval;

            $noticeDisplay = '';
            if ($noticeStatus === 'in-progress') {
                $noticeDisplay = $daysRemaining > 0 ? "{$daysRemaining} days remaining" : 'Notice period ending soon';
            } elseif ($noticeStatus === 'completed') {
                $noticeDisplay = 'Completed';
            } else {
                $noticeDisplay = $noticeDuration . ' Days';
            }

            // For admin-initiated, show different description
            $noticeDescription = $isAdminInitiated
                ? ($noticeStatus === 'in-progress' 
                    ? "{$daysRemaining} days remaining until exit. (Admin initiated)" 
                    : ($noticeStatus === 'completed' ? 'Notice period completed.' : 'Notice period will start after admin approval.'))
                : ($noticeStatus === 'in-progress' 
                    ? "{$daysRemaining} days remaining until exit." 
                    : ($noticeStatus === 'completed' ? 'Notice period completed.' : 'Notice period will start after approval.'));

            $journey[] = [
                'key' => 'notice_period',
                'title' => 'Notice Period',
                'icon' => 'fas fa-hourglass-half',
                'color' => 'warning',
                'status' => $noticeStatus,
                'status_label' => $noticeStatus === 'completed' ? 'Completed' : ($noticeStatus === 'in-progress' ? 'In Progress' : 'Pending'),
                'date' => $noticeDisplay,
                'notice_days' => $noticeDuration . ' Days',
                'description' => $noticeDescription,
                'is_enabled' => true,
                'is_locked' => $isNoticeLocked,
                'is_admin_initiated' => $isAdminInitiated,
                'assigned_to' => null,
                'data' => [
                    'notice_days' => $noticeDuration,
                    'start_date' => $noticeStart ?? 'N/A',
                    'end_date' => $noticeEnd ?? 'N/A',
                    'days_remaining' => $daysRemaining,
                    'is_overdue' => $noticeDetails['is_overdue'] ?? false
                ],
                'notice_details' => $noticeDetails
            ];
        }

        // ============================================================
        // STEP 4: KNOWLEDGE TRANSFER
        // ============================================================
        if ($hasPolicy && $exitPolicy->kt_required) {
            $ktStatus = 'pending';
            $ktDuration = $exitPolicy->kt_days ?? 5;
            
            // Get KT task assignment
            $ktTask = $taskAssignments->get('kt');
            $ktAssignedTo = null;
            $ktAssignedName = null;
            $ktAssignedRole = null;
            $ktAssignedDesignation = null;
            $ktAssignedEmployeeCode = null;
            $ktAssignedEmail = null;
            
            if ($ktTask) {
                $ktAssignedTo = $ktTask->assigned_to_user_id;
                $ktAssignedName = $ktTask->assigned_to_name;
                $ktAssignedRole = $ktTask->assigned_to_role;
                $ktAssignedDesignation = $ktTask->assigned_employee_designation ?? null;
                $ktAssignedEmployeeCode = $ktTask->assigned_employee_code ?? null;
                $ktAssignedEmail = $ktTask->assigned_user_email ?? null;
                $ktStatus = $ktTask->status;
            }
            
            if ($hasActiveExit) {
                if ($isExited) {
                    $ktStatus = 'completed';
                } elseif ($isNoticePeriod || $isApproved) {
                    if ($ktStatus === 'pending' || !$ktTask) {
                        $ktStatus = 'Pending';
                    }
                } elseif ($isPendingApproval) {
                    $ktStatus = 'pending';
                }
            }

            $isKTLocked = !$hasActiveExit || $isPendingApproval;
            
            // Calculate KT deadline
            $ktDeadline = null;
            $ktDeadlineDate = null;
            if ($expectedExitDate) {
                $ktDeadlineDate = $expectedExitDate->copy()->subDays($ktDuration);
                $ktDeadline = $ktDeadlineDate->format('d M, Y');
            } elseif ($activeExit && $activeExit->approved_at) {
                $ktDeadlineDate = Carbon::parse($activeExit->approved_at)->addDays($ktDuration);
                $ktDeadline = $ktDeadlineDate->format('d M, Y');
            }

            // Get KT requirements labels
            $ktRequirements = $exitPolicy->kt_requirements ?? [];
            $ktLabels = [
                'documentation' => 'Documentation Handover',
                'project_handover' => 'Project/Work Handover',
                'code_handover' => 'Code/System Handover',
                'client_handover' => 'Client/Stakeholder Handover',
                'process_handover' => 'Process Handover',
                'training' => 'Training & Support',
                'knowledge_docs' => 'Knowledge Base Documentation'
            ];
            
            $ktRequirementLabels = [];
            foreach ($ktRequirements as $req) {
                $ktRequirementLabels[] = $ktLabels[$req] ?? ucwords(str_replace('_', ' ', $req));
            }

            $ktDescription = $expectedExitDate 
                ? "Complete KT by " . $ktDeadline . " (" . $ktDuration . " day" . ($ktDuration == 1 ? '' : 's') . " before your last working day, " . $expectedExitDate->format('d M, Y') . ")"
                : 'Complete knowledge transfer and handover activities before your last working day.';

            $journey[] = [
                'key' => 'knowledge_transfer',
                'title' => 'Knowledge Transfer',
                'icon' => 'fas fa-chalkboard-teacher',
                'color' => 'success',
                'status' => $ktStatus,
                'status_label' => $ktStatus === 'completed' ? 'Completed' : ($ktStatus === 'Pending' ? 'Pending' : 'Pending'),
                'date' => null,
                'description' => $ktDescription,
                'is_enabled' => true,
                'is_locked' => $isKTLocked,
                'is_admin_initiated' => $isAdminInitiated,
                'assigned_to' => $ktAssignedTo,
                'assigned_to_name' => $ktAssignedName,
                'assigned_to_role' => $ktAssignedRole,
                'assigned_to_designation' => $ktAssignedDesignation,
                'assigned_to_employee_code' => $ktAssignedEmployeeCode,
                'assigned_to_email' => $ktAssignedEmail,
                'task_id' => $ktTask ? $ktTask->id : null,
                'task_deadline' => $ktTask ? $ktTask->deadline : null,
                'task_instructions' => $ktTask ? $ktTask->instructions : null,
                'task_created_at' => $ktTask ? $ktTask->created_at : null,
                'task_updated_at' => $ktTask ? $ktTask->updated_at : null,
                'data' => [
                    'kt_duration' => $ktDuration,
                    'kt_deadline' => $ktDeadline ?? 'To be scheduled',
                    'requirements' => $ktRequirementLabels,
                    'status' => $ktStatus,
                    'expected_exit_date' => $expectedExitDate ? $expectedExitDate->format('d M, Y') : 'N/A'
                ]
            ];
        }

        // ============================================================
        // STEP 5: ASSETS & CLEARANCE - No assigned person shown
        // ============================================================
        if ($hasPolicy && !empty($exitPolicy->clearance_workflow) && $exitPolicy->clearance_workflow !== 'none') {
            $clearanceStatus = 'pending';
            $clearanceDuration = $exitPolicy->clearance_days ?? 7;
            
            // Get Asset Clearance task assignment
            $assetTask = $taskAssignments->get('asset_clearance');
            
            if ($assetTask) {
                $clearanceStatus = $assetTask->status;
            }
            
            if ($hasActiveExit) {
                if ($isExited) {
                    $clearanceStatus = 'completed';
                } elseif ($isNoticePeriod || $isApproved) {
                    if ($clearanceStatus === 'pending' || !$assetTask) {
                        $clearanceStatus = 'pending';
                    }
                } elseif ($isPendingApproval) {
                    $clearanceStatus = 'pending';
                }
            }

            $isClearanceLocked = !$hasActiveExit || $isPendingApproval;
            
            // Calculate clearance deadline
            $clearanceDeadline = null;
            $clearanceDeadlineDate = null;
            if ($expectedExitDate) {
                $clearanceDeadlineDate = $expectedExitDate->copy()->subDays($clearanceDuration);
                $clearanceDeadline = $clearanceDeadlineDate->format('d M, Y');
            } elseif ($activeExit && $activeExit->approved_at) {
                $clearanceDeadlineDate = Carbon::parse($activeExit->approved_at)->addDays($clearanceDuration);
                $clearanceDeadline = $clearanceDeadlineDate->format('d M, Y');
            }

            $workflowLabels = [
                'sequential' => 'Step-by-Step',
                'parallel' => 'All at Once',
                'hybrid' => 'Mixed'
            ];

            $clearanceDescription = $expectedExitDate 
                ? "Complete clearance by " . $clearanceDeadline . " (" . $clearanceDuration . " day" . ($clearanceDuration == 1 ? '' : 's') . " before your last working day, " . $expectedExitDate->format('d M, Y') . ")"
                : 'Return assets and complete clearance process before your last working day.';

            $journey[] = [
                'key' => 'clearance',
                'title' => 'Assets & Clearance',
                'icon' => 'fas fa-clipboard-check',
                'color' => 'info',
                'status' => $clearanceStatus,
                'status_label' => $clearanceStatus === 'completed' ? 'Completed' : ($clearanceStatus === 'in-progress' ? 'In Progress' : 'Pending'),
                'date' => null,
                'description' => $clearanceDescription,
                'is_enabled' => true,
                'is_locked' => $isClearanceLocked,
                'is_admin_initiated' => $isAdminInitiated,
                // No assignment fields for clearance
                'assigned_to' => null,
                'assigned_to_name' => null,
                'assigned_to_role' => null,
                'assigned_to_designation' => null,
                'assigned_to_employee_code' => null,
                'assigned_to_email' => null,
                'task_id' => $assetTask ? $assetTask->id : null,
                'task_deadline' => $assetTask ? $assetTask->deadline : null,
                'task_instructions' => $assetTask ? $assetTask->instructions : null,
                'task_created_at' => $assetTask ? $assetTask->created_at : null,
                'task_updated_at' => $assetTask ? $assetTask->updated_at : null,
                'data' => [
                    'clearance_duration' => $clearanceDuration,
                    'clearance_deadline' => $clearanceDeadline ?? 'To be scheduled',
                    'workflow' => $workflowLabels[$exitPolicy->clearance_workflow] ?? ucfirst($exitPolicy->clearance_workflow ?? 'N/A'),
                    'status' => $clearanceStatus,
                    'expected_exit_date' => $expectedExitDate ? $expectedExitDate->format('d M, Y') : 'N/A'
                ]
            ];
        }

        // ============================================================
        // STEP 6: EXIT INTERVIEW - Show Interviewer
        // ============================================================
        if ($hasPolicy && $exitPolicy->exit_interview_required) {
            $interviewStatus = 'pending';
            $interviewDuration = $exitPolicy->interview_days ?? 5;
            
            // Get Exit Interview task assignment
            $interviewTask = $taskAssignments->get('exit_interview');
            $interviewerName = null;
            $interviewerRole = null;
            $interviewerDesignation = null;
            $interviewerEmployeeCode = null;
            $interviewerEmail = null;
            
            if ($interviewTask) {
                $interviewerName = $interviewTask->assigned_to_name;
                $interviewerRole = $interviewTask->assigned_to_role;
                $interviewerDesignation = $interviewTask->assigned_employee_designation ?? null;
                $interviewerEmployeeCode = $interviewTask->assigned_employee_code ?? null;
                $interviewerEmail = $interviewTask->assigned_user_email ?? null;
                $interviewStatus = $interviewTask->status;
            }
            
            if ($hasActiveExit) {
                if ($isExited) {
                    $interviewStatus = 'completed';
                } elseif ($isNoticePeriod || $isApproved) {
                    if ($interviewStatus === 'pending' || !$interviewTask) {
                        $interviewStatus = 'pending';
                    }
                } elseif ($isPendingApproval) {
                    $interviewStatus = 'pending';
                }
            }

            $isInterviewLocked = !$hasActiveExit || $isPendingApproval;
            
            // Calculate interview deadline
            $interviewDeadline = null;
            $interviewDeadlineDate = null;
            if ($expectedExitDate) {
                $interviewDeadlineDate = $expectedExitDate->copy()->subDays($interviewDuration);
                $interviewDeadline = $interviewDeadlineDate->format('d M, Y');
            } elseif ($activeExit && $activeExit->approved_at) {
                $interviewDeadlineDate = Carbon::parse($activeExit->approved_at)->addDays($interviewDuration);
                $interviewDeadline = $interviewDeadlineDate->format('d M, Y');
            }

            $interviewDescription = $expectedExitDate 
                ? "Complete exit interview by " . $interviewDeadline . " (" . $interviewDuration . " day" . ($interviewDuration == 1 ? '' : 's') . " before your last working day, " . $expectedExitDate->format('d M, Y') . ")"
                : 'Schedule and complete your exit interview before your last working day.';

            $journey[] = [
                'key' => 'exit_interview',
                'title' => 'Exit Interview',
                'icon' => 'fas fa-comments',
                'color' => 'warning',
                'status' => $interviewStatus,
                'status_label' => $interviewStatus === 'completed' ? 'Completed' : ($interviewStatus === 'in-progress' ? 'In Progress' : 'Pending'),
                'date' => null,
                'description' => $interviewDescription,
                'is_enabled' => true,
                'is_locked' => $isInterviewLocked,
                'is_admin_initiated' => $isAdminInitiated,
                // Interviewer fields (separate from assigned person)
                'interviewer_name' => $interviewerName,
                'interviewer_role' => $interviewerRole,
                'interviewer_designation' => $interviewerDesignation,
                'interviewer_employee_code' => $interviewerEmployeeCode,
                'interviewer_email' => $interviewerEmail,
                // Also keep assignment fields for compatibility
                'assigned_to' => $interviewTask ? $interviewTask->assigned_to_user_id : null,
                'assigned_to_name' => $interviewerName,
                'assigned_to_role' => $interviewerRole,
                'assigned_to_designation' => $interviewerDesignation,
                'assigned_to_employee_code' => $interviewerEmployeeCode,
                'assigned_to_email' => $interviewerEmail,
                'task_id' => $interviewTask ? $interviewTask->id : null,
                'task_deadline' => $interviewTask ? $interviewTask->deadline : null,
                'task_instructions' => $interviewTask ? $interviewTask->instructions : null,
                'task_created_at' => $interviewTask ? $interviewTask->created_at : null,
                'task_updated_at' => $interviewTask ? $interviewTask->updated_at : null,
                'data' => [
                    'interview_duration' => $interviewDuration,
                    'interview_deadline' => $interviewDeadline ?? 'To be scheduled',
                    'status' => $interviewStatus,
                    'expected_exit_date' => $expectedExitDate ? $expectedExitDate->format('d M, Y') : 'N/A'
                ]
            ];
        }

        // ============================================================
        // STEP 7: FNF SETTLEMENT
        // ============================================================
        if ($hasPolicy && $exitPolicy->fnf_required) {
            $fnfStatus = 'pending';
            $fnfDuration = $exitPolicy->fnf_processing_days ?? 30;
            
            // Get FNF task assignment
            $fnfTask = $taskAssignments->get('fnf');
            $fnfAssignedTo = null;
            $fnfAssignedName = null;
            $fnfAssignedRole = null;
            $fnfAssignedDesignation = null;
            $fnfAssignedEmployeeCode = null;
            $fnfAssignedEmail = null;
            
            if ($fnfTask) {
                $fnfAssignedTo = $fnfTask->assigned_to_user_id;
                $fnfAssignedName = $fnfTask->assigned_to_name;
                $fnfAssignedRole = $fnfTask->assigned_to_role;
                $fnfAssignedDesignation = $fnfTask->assigned_employee_designation ?? null;
                $fnfAssignedEmployeeCode = $fnfTask->assigned_employee_code ?? null;
                $fnfAssignedEmail = $fnfTask->assigned_user_email ?? null;
                $fnfStatus = $fnfTask->status;
            }
            
            if ($hasActiveExit) {
                if ($isExited) {
                    $fnfStatus = 'completed';
                } elseif ($isNoticePeriod || $isApproved) {
                    if ($fnfStatus === 'pending' || !$fnfTask) {
                        $fnfStatus = 'Pending';
                    }
                } elseif ($isPendingApproval) {
                    $fnfStatus = 'pending';
                }
            }

            $isFNFLocked = !$hasActiveExit || $isPendingApproval;
            
            // Calculate FNF deadline
            $fnfDeadline = null;
            $fnfDeadlineDate = null;
            if ($expectedExitDate) {
                $fnfDeadlineDate = $expectedExitDate->copy()->addDays($fnfDuration);
                $fnfDeadline = $fnfDeadlineDate->format('d M, Y');
            } elseif ($activeExit && $activeExit->approved_at) {
                $fnfDeadlineDate = Carbon::parse($activeExit->approved_at)->addDays($fnfDuration + 45);
                $fnfDeadline = $fnfDeadlineDate->format('d M, Y');
            }

            $settlementLabels = [
                'standard' => 'Standard',
                'expedited' => 'Expedited'
            ];

            // Get FNF items labels
            $fnfItems = $exitPolicy->fnf_items ?? [];
            $fnfLabels = [
                'salary_settlement' => 'Salary Settlement',
                'leave_encashment' => 'Leave Encashment',
                'bonus_settlement' => 'Bonus/Incentive',
                'reimbursement' => 'Reimbursement',
                'pf_settlement' => 'PF Settlement',
                'esi_settlement' => 'ESI Settlement',
                'gratuity' => 'Gratuity',
                'other_dues' => 'Other Dues'
            ];
            
            $fnfItemLabels = [];
            foreach ($fnfItems as $item) {
                $fnfItemLabels[] = $fnfLabels[$item] ?? ucwords(str_replace('_', ' ', $item));
            }

            $fnfDescription = $expectedExitDate 
                ? "FNF settlement will be completed by " . $fnfDeadline . " (" . $fnfDuration . " day" . ($fnfDuration == 1 ? '' : 's') . " after your last working day, " . $expectedExitDate->format('d M, Y') . ")"
                : 'Final settlement of all dues and payments after your last working day.';

            $journey[] = [
                'key' => 'fnf',
                'title' => 'FNF Settlement',
                'icon' => 'fas fa-file-invoice-dollar',
                'color' => 'danger',
                'status' => $fnfStatus,
                'status_label' => $fnfStatus === 'completed' ? 'Completed' : ($fnfStatus === 'in-progress' ? 'In Progress' : 'Pending'),
                'date' => null,
                'description' => $fnfDescription,
                'is_enabled' => true,
                'is_locked' => $isFNFLocked,
                'is_admin_initiated' => $isAdminInitiated,
                'assigned_to' => $fnfAssignedTo,
                'assigned_to_name' => $fnfAssignedName,
                'assigned_to_role' => $fnfAssignedRole,
                'assigned_to_designation' => $fnfAssignedDesignation,
                'assigned_to_employee_code' => $fnfAssignedEmployeeCode,
                'assigned_to_email' => $fnfAssignedEmail,
                'task_id' => $fnfTask ? $fnfTask->id : null,
                'task_deadline' => $fnfTask ? $fnfTask->deadline : null,
                'task_instructions' => $fnfTask ? $fnfTask->instructions : null,
                'task_created_at' => $fnfTask ? $fnfTask->created_at : null,
                'task_updated_at' => $fnfTask ? $fnfTask->updated_at : null,
                'data' => [
                    'fnf_duration' => $fnfDuration,
                    'fnf_deadline' => $fnfDeadline ?? 'To be scheduled',
                    'settlement_type' => $settlementLabels[$exitPolicy->fnf_settlement_type] ?? ucfirst($exitPolicy->fnf_settlement_type ?? 'Standard'),
                    'items' => $fnfItemLabels,
                    'status' => $fnfStatus,
                    'expected_exit_date' => $expectedExitDate ? $expectedExitDate->format('d M, Y') : 'N/A'
                ]
            ];
        }

        return $journey;
    }

    /**
     * Show resignation form
     */
    public function showResignationForm()
    {
        $context = $this->getInstituteBranchContext();
        $userId = auth()->id();
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }
        
        $employee = EmployeeDetails::where('user_id', $userId)
            ->where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->first();
        
        if (!$employee) {
            return redirect()->back()->with('error', 'Employee record not found.');
        }
        
        // Check if already has active exit
        $activeExit = EmployeeExit::where('employee_id', $employee->employee_id)
            ->whereIn('exit_status', ['pending_approval', 'notice_period'])
            ->first();
        
        if ($activeExit) {
            return redirect()->route('employee.exit.dashboard')
                ->with('error', 'You already have an active exit process.');
        }
        
        // Get policy for this employee
        $policy = $this->getEmployeePolicy($employee, 'Resignation');
        $exitPolicy = $this->getPolicyDetails($policy, $employee);
        
        return view('instituteAdmin.EmployeeExit.resignation-form', compact('employee', 'exitPolicy'));
    }

    /**
     * Submit resignation request
     */
    public function submitResignation(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        $userId = auth()->id();
        
        $validator = Validator::make($request->all(), [
            'exit_reason' => 'required|string|max:500',
            'exit_notes' => 'nullable|string|max:500',
            'exit_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $employee = EmployeeDetails::where('user_id', $userId)
            ->where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->first();
        
        if (!$employee) {
            return redirect()->back()->with('error', 'Employee record not found.');
        }
        
        // Check for active exit
        $activeExit = EmployeeExit::where('employee_id', $employee->employee_id)
            ->whereIn('exit_status', ['pending_approval', 'notice_period'])
            ->first();
        
        if ($activeExit) {
            return redirect()->back()->with('error', 'You already have an active exit process.');
        }
        
        // Get policy and notice period
        $policy = $this->getEmployeePolicy($employee, 'Resignation');
        $noticeDays = $this->getEmployeeNoticePeriod($employee, $policy);
        
        // Create exit using service
        $exit = $this->exitService->createExit($employee, [
            'resignation_date' => Carbon::now(),
            'exit_reason' => $request->exit_reason,
            'exit_notes' => $request->exit_notes,
            'exit_document' => $request->file('exit_document'),
            'notice_period_days' => $noticeDays,
            'initiation_source' => 'employee',
            'initiated_by' => $userId,
        ], $context);
        
        return redirect()->route('employee.exit.dashboard')
            ->with('success', 'Resignation submitted successfully! Your request is ON HOLD pending admin approval.');
    }

    private function decodeJson($data)
    {
        if (is_string($data)) {
            return json_decode($data, true);
        }
        return $data;
    }

    /**
     * Show employee's exit policy details
     */
    public function showPolicy()
    {
        $context = $this->getInstituteBranchContext();
        $userId = auth()->id();

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }
        
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
        
        $policy = $this->getEmployeePolicy($employee, 'Resignation');
        $exitPolicy = $this->getPolicyDetails($policy, $employee);
        
        return view('instituteAdmin.EmployeeExit.employee-policy', compact('employee', 'exitPolicy'));
    }
}
