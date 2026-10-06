<?php
// app/Http/Controllers/institute/Admin/EmployeeExitController.php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDetails;
use App\Models\EmployeeExit;
use App\Models\EmployeeExitPolicy;
use App\Models\PolicyAssignment;
use App\Models\User;
use App\Services\EmployeeExitService;
use App\Traits\InstituteBranchAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EmployeeExitController extends Controller
{
    use InstituteBranchAccess;

    protected $exitService;

    public function __construct(EmployeeExitService $exitService)
    {
        $this->exitService = $exitService;
    }

    /**
     * Get all policies assigned to an employee (excluding Resignation for admin)
     */
    private function getEmployeeAssignedPolicies($employee, $instituteId)
    {
        $policies = [];
        $employeeId = $employee->employee_id;
        $departmentId = $employee->department_id;

        // Exit types that admin can initiate (exclude Resignation)
        $adminAllowedExitTypes = ['Termination', 'End of Contract', 'Mutual Agreement', 'Retirement', 'Other'];

        // 1. Get Individual Assignments (Highest Priority)
        $individualAssignments = PolicyAssignment::where('employee_id', $employeeId)
            ->where(function($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now());
            })
            ->with('policy')
            ->get();

        foreach ($individualAssignments as $assignment) {
            if ($assignment->policy && $assignment->policy->is_active) {
                $exitType = $assignment->exit_type ?? 'Resignation';
                // Skip Resignation for admin
                if (!in_array($exitType, $adminAllowedExitTypes)) {
                    continue;
                }
                if (!isset($policies[$exitType]) || $policies[$exitType]['priority'] < 1) {
                    $policies[$exitType] = [
                        'policy' => $assignment->policy,
                        'assignment' => $assignment,
                        'priority' => 1,
                        'assignment_type' => 'individual',
                        'exit_type' => $exitType,
                    ];
                }
            }
        }

        // 2. Get Department Assignments
        if ($departmentId) {
            $departmentAssignments = PolicyAssignment::where('department_id', $departmentId)
                ->where(function($query) {
                    $query->whereNull('expiry_date')
                        ->orWhere('expiry_date', '>=', now());
                })
                ->with('policy')
                ->get();

            foreach ($departmentAssignments as $assignment) {
                if ($assignment->policy && $assignment->policy->is_active) {
                    $exitType = $assignment->exit_type ?? 'Resignation';
                    // Skip Resignation for admin
                    if (!in_array($exitType, $adminAllowedExitTypes)) {
                        continue;
                    }
                    if (!isset($policies[$exitType]) || $policies[$exitType]['priority'] < 2) {
                        $policies[$exitType] = [
                            'policy' => $assignment->policy,
                            'assignment' => $assignment,
                            'priority' => 2,
                            'assignment_type' => 'department',
                            'exit_type' => $exitType,
                        ];
                    }
                }
            }
        }

        // 3. Get "All Departments" Assignments (Lowest Priority)
        $allAssignments = PolicyAssignment::where('assignment_type', 'all_department')
            ->where(function($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now());
            })
            ->with('policy')
            ->get();

        foreach ($allAssignments as $assignment) {
            if ($assignment->policy && $assignment->policy->is_active) {
                $exitType = $assignment->exit_type ?? 'Resignation';
                // Skip Resignation for admin
                if (!in_array($exitType, $adminAllowedExitTypes)) {
                    continue;
                }
                if (!isset($policies[$exitType]) || $policies[$exitType]['priority'] < 3) {
                    $policies[$exitType] = [
                        'policy' => $assignment->policy,
                        'assignment' => $assignment,
                        'priority' => 3,
                        'assignment_type' => 'all_department',
                        'exit_type' => $exitType,
                    ];
                }
            }
        }

        // Sort by exit type and return
        ksort($policies);
        return $policies;
    }

    public function initiateExit($employeeId)
    {
        $context = $this->getInstituteBranchContext();
        $instituteId = $context['institute_id'];

        $employee = EmployeeDetails::select(
            'employee_details.*',
            'departments.department as department_name'
        )
        ->leftJoin('departments', 'employee_details.department_id', '=', 'departments.department_id')
        ->where('employee_details.employee_id', $employeeId)
        ->where('employee_details.institute_id', $instituteId)
        ->with(['activeExit' => function($query) {
            $query->whereIn('exit_status', ['pending_approval', 'notice_period', 'exited', 'approved']);
        }])
        ->firstOrFail();

        // ✅ NEW: Get pending self-initiated resignation
        $pendingResignation = EmployeeExit::where('employee_id', $employee->employee_id)
            ->where('exit_status', 'pending_approval')
            ->where('initiation_source', 'employee')
            ->where('exit_type', 'Resignation')
            ->latest()
            ->first();

        // ✅ NEW: Flag to show admin override option
        $hasPendingResignation = $pendingResignation !== null;

        // Get ALL assigned policies for this employee (excluding Resignation for admin)
        $assignedPolicies = $this->getEmployeeAssignedPolicies($employee, $instituteId);
        
        // Get active exit if any (excluding pending resignation for override)
        $activeExit = $employee->activeExit;
        
        // ✅ NEW: If there's a pending resignation, don't treat it as blocking
        if ($hasPendingResignation && $activeExit && $activeExit->id == $pendingResignation->id) {
            $activeExit = null; // Allow admin to override
            $hasActiveExit = false;
        } else {
            $hasActiveExit = $activeExit !== null;
        }
        
        // Get exit history
        $exitHistory = EmployeeExit::where('employee_id', $employee->employee_id)
            ->where('institute_id', $instituteId)
            ->whereIn('exit_status', ['exited', 'cancelled', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Check if employee has a pending/approved exit that admin should see
        $hasPendingOrApprovedExit = EmployeeExit::where('employee_id', $employee->employee_id)
            ->whereIn('exit_status', ['pending_approval', 'notice_period', 'approved'])
            ->exists();

        $activeExitDetails = null;
        if ($activeExit) {
            $activeExitDetails = [
                'exit' => $activeExit,
                'policy' => $activeExit->exitPolicy,
                'status_label' => $activeExit->status_label,
                'initiation_source' => $activeExit->initiation_source ?? 'admin',
                'is_admin_initiated' => ($activeExit->initiation_source ?? 'admin') === 'admin',
                'is_employee_initiated' => ($activeExit->initiation_source ?? 'admin') === 'employee',
                'notice_details' => $this->exitService->calculateNoticePeriodDetails($activeExit),
                'exit_type_label' => $this->getExitTypeLabel($activeExit->exit_type ?? 'Resignation'),
                'reason_label' => $this->getReasonLabel($activeExit->exit_reason ?? ''),
            ];
        }

        // ✅ NEW: Add pending resignation details for override
        $pendingResignationDetails = null;
        if ($hasPendingResignation) {
            $pendingResignationDetails = [
                'exit' => $pendingResignation,
                'submitted_at' => $pendingResignation->created_at->format('d M Y h:i A'),
                'exit_reason' => ucwords(str_replace('_', ' ', $pendingResignation->exit_reason ?? 'N/A')),
                'exit_notes' => $pendingResignation->exit_notes,
            ];
        }

        // Map exit types to reasons
        $exitTypeReasonMap = [
            'Resignation' => 'resignation',
            'Termination' => 'termination',
            'End of Contract' => 'end_of_contract',
            'Mutual Agreement' => 'mutual_separation',
            'Retirement' => 'retirement',
            'Other' => 'other'
        ];

        return view('instituteAdmin.EmployeeExit.initiate-exit', compact(
            'employee',
            'assignedPolicies',
            'hasActiveExit',
            'activeExit',
            'activeExitDetails',
            'exitHistory',
            'context',
            'hasPendingOrApprovedExit',
            'exitTypeReasonMap',
            'hasPendingResignation',
            'pendingResignationDetails',
            'pendingResignation'
        ));
    }

     /**
     * Get label for exit type
     */
    private function getExitTypeLabel($exitType)
    {
        $labels = [
            'Resignation' => 'Resignation',
            'Termination' => 'Termination',
            'End of Contract' => 'End of Contract',
            'Mutual Agreement' => 'Mutual Agreement',
            'Retirement' => 'Retirement',
            'Other' => 'Other'
        ];
        return $labels[$exitType] ?? $exitType;
    }
    
    /**
     * Get label for reason
     */
    private function getReasonLabel($reason)
    {
        $labels = [
            'resignation' => 'Resignation',
            'termination' => 'Termination',
            'end_of_contract' => 'End of Contract',
            'mutual_separation' => 'Mutual Separation',
            'retirement' => 'Retirement',
            'other' => 'Other'
        ];
        return $labels[$reason] ?? ucwords(str_replace('_', ' ', $reason));
    }


    public function processExit(Request $request, $employeeId)
{
    $context = $this->getInstituteBranchContext();
    $instituteId = $context['institute_id'];

    $validator = Validator::make($request->all(), [
        'policy_id' => 'required|exists:employee_exit_policies,id',
        'exit_notes' => 'nullable|string|max:500',
        'custom_start_date' => 'nullable|date|after_or_equal:today',
        'require_approval' => 'nullable|boolean',
        'exit_type' => 'required|string',
        // ✅ NEW: Override confirmation
        'override_pending' => 'nullable|boolean',
        'pending_exit_id' => 'nullable|exists:employee_exits,id',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $employee = EmployeeDetails::where('employee_id', $employeeId)
        ->where('institute_id', $instituteId)
        ->firstOrFail();

    // ✅ NEW: Check for pending self-initiated resignation
    $pendingResignation = EmployeeExit::where('employee_id', $employee->employee_id)
        ->where('exit_status', 'pending_approval')
        ->where('initiation_source', 'employee')
        ->where('exit_type', 'Resignation')
        ->latest()
        ->first();

    // ✅ NEW: If there's a pending resignation and override is confirmed
    if ($pendingResignation && ($request->override_pending ?? false)) {
        // Cancel the pending resignation
        $pendingResignation->update([
            'exit_status' => 'cancelled',
            'cancelled_by' => auth()->id(),
            'cancelled_at' => now(),
            'cancellation_reason' => 'Overridden by admin for termination/other exit',
        ]);
    }

    // Check if already has active exit (excluding cancelled)
    $activeExit = EmployeeExit::where('employee_id', $employee->employee_id)
        ->whereIn('exit_status', ['pending_approval', 'notice_period', 'approved'])
        ->where('id', '!=', $pendingResignation ? $pendingResignation->id : 0)
        ->first();

    if ($activeExit) {
        return redirect()->back()
            ->with('error', 'This employee already has an active exit process.')
            ->withInput();
    }

    try {
        // Get the selected policy
        $policy = EmployeeExitPolicy::findOrFail($request->policy_id);
        
        $defaultNoticeDays = $policy->default_notice_period ?? 30;
        
        // Check for employment type override
        if ($employee->employment_type) {
            $employmentNoticePeriods = json_decode($policy->employment_notice_periods, true) ?? [];
            if (isset($employmentNoticePeriods[$employee->employment_type])) {
                $period = $employmentNoticePeriods[$employee->employment_type];
                if ($period === 'custom') {
                    $employmentCustomDays = json_decode($policy->employment_custom_days, true) ?? [];
                    if (isset($employmentCustomDays[$employee->employment_type])) {
                        $defaultNoticeDays = (int) $employmentCustomDays[$employee->employment_type];
                    }
                } else {
                    $defaultNoticeDays = (int) $period;
                }
            }
        }

        $noticeDays = $request->notice_period_days ?? $defaultNoticeDays;

        // Determine start date
        if ($request->has('custom_start_date') && $request->custom_start_date) {
            $startDate = Carbon::parse($request->custom_start_date);
        } else {
            $startDate = Carbon::now();
        }

        $endDate = $startDate->copy()->addDays($noticeDays);
        $requireApproval = $request->has('require_approval') && $request->require_approval;

        // Auto-determine exit reason from exit type
        $exitTypeReasonMap = [
            'Resignation' => 'resignation',
            'Termination' => 'termination',
            'End of Contract' => 'end_of_contract',
            'Mutual Agreement' => 'mutual_separation',
            'Retirement' => 'retirement',
            'Other' => 'other'
        ];
        $exitReason = $exitTypeReasonMap[$request->exit_type] ?? 'other';

        // Create exit record
        $exitData = [
            'resignation_date' => Carbon::now(),
            'proposed_last_working_date' => $endDate,
            'notice_start_date' => $startDate,
            'notice_end_date' => $endDate,
            'notice_period_days' => $noticeDays,
            'exit_reason' => $exitReason,
            'exit_notes' => $request->exit_notes,
            'initiation_source' => 'admin',
            'initiated_by' => auth()->id(),
            'exit_type' => $request->exit_type,
            'exit_policy_id' => $policy->id,
        ];

        // Create exit using service
        $exit = $this->exitService->createExit($employee, $exitData, $context);

        // If require_approval is false, auto-approve
        if (!$requireApproval && $exit->exit_status === 'pending_approval') {
            $this->exitService->approveExit($exit->id, auth()->id());
        }

        $message = $requireApproval 
            ? 'Exit request submitted for approval. Employee will be notified once approved.'
            : "Exit initiated successfully! Employee will be marked as exited on {$endDate->format('d-m-Y')}.";

        // ✅ NEW: Add override message
        if ($pendingResignation && ($request->override_pending ?? false)) {
            $message = "✅ Admin override completed. The pending resignation has been cancelled. " . $message;
        }

        return redirect()->route('employees.index')
            ->with('success', $message);

    } catch (\Exception $e) {
        \Log::error('Exit initiation error: ' . $e->getMessage(), [
            'employee_id' => $employeeId,
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()
            ->with('error', 'Failed to initiate exit: ' . $e->getMessage())
            ->withInput();
    }
    }

    /**
     * Approve exit request
     */
    public function approveExit($exitId)
    {
        try {
            $this->exitService->approveExit($exitId, auth()->id());
            
            return redirect()->back()
                ->with('success', 'Exit request approved successfully. Employee will be auto-exited when the notice period ends.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to approve exit: ' . $e->getMessage());
        }
    }

    /**
     * Reject exit request
     */
    public function rejectExit(Request $request, $exitId)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->exitService->rejectExit($exitId, auth()->id(), $request->rejection_reason);
            
            return redirect()->back()
                ->with('success', 'Exit request rejected successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to reject exit: ' . $e->getMessage());
        }
    }

    /**
     * Complete exit (Manual override)
     */
    public function completeExit($exitId)
    {
        try {
            $this->exitService->completeExit($exitId, auth()->id());
            
            return redirect()->route('employees.index')
                ->with('success', 'Exit completed successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to complete exit: ' . $e->getMessage());
        }
    }

    /**
     * Cancel exit
     */
    public function cancelExit(Request $request, $exitId)
    {
        $validator = Validator::make($request->all(), [
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->exitService->cancelExit($exitId, auth()->id(), $request->cancellation_reason);
            
            return redirect()->route('employees.index')
                ->with('success', 'Exit process cancelled successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to cancel exit: ' . $e->getMessage());
        }
    }

    /**
     * Update exit clearance
     */
    public function updateClearance(Request $request, $exitId)
    {
        $context = $this->getInstituteBranchContext();
        $instituteId = $context['institute_id'];

        $exit = EmployeeExit::where('id', $exitId)
            ->where('institute_id', $instituteId)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'clearance_done' => 'boolean',
            'clearance_notes' => 'nullable|string|max:500',
            'assets_returned' => 'boolean',
            'assets_notes' => 'nullable|string|max:500',
            'handover_completed' => 'boolean',
            'handover_notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $exit->update([
                'clearance_done' => $request->clearance_done ?? $exit->clearance_done,
                'clearance_notes' => $request->clearance_notes ?? $exit->clearance_notes,
                'clearance_date' => $request->clearance_done ? Carbon::now() : null,
                'assets_returned' => $request->assets_returned ?? $exit->assets_returned,
                'assets_notes' => $request->assets_notes ?? $exit->assets_notes,
                'assets_return_date' => $request->assets_returned ? Carbon::now() : null,
                'handover_completed' => $request->handover_completed ?? $exit->handover_completed,
                'handover_notes' => $request->handover_notes ?? $exit->handover_notes,
                'handover_date' => $request->handover_completed ? Carbon::now() : null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Clearance updated successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update clearance: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get exit details for AJAX
     */
    public function getExitDetails($exitId)
    {
        try {
            $context = $this->getInstituteBranchContext();
            $instituteId = $context['institute_id'];

            $exit = EmployeeExit::where('id', $exitId)
                ->where('institute_id', $instituteId)
                ->with(['employee', 'exitPolicy', 'initiatedBy', 'approvedBy', 'completedBy'])
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $exit->id,
                    'employee' => [
                        'id' => $exit->employee->id,
                        'name' => $exit->employee->name,
                        'employee_code' => $exit->employee->employee_code,
                        'department' => $exit->employee->department ? $exit->employee->department->department : 'N/A',
                        'designation' => $exit->employee->designation ?? 'N/A',
                    ],
                    'notice_period' => [
                        'start_date' => $exit->notice_start_date->format('d-m-Y'),
                        'end_date' => $exit->notice_end_date->format('d-m-Y'),
                        'days' => $exit->notice_period_days,
                        'days_remaining' => $exit->days_remaining,
                        'is_overdue' => $exit->is_overdue,
                        'progress' => $exit->notice_period_progress,
                    ],
                    'status' => [
                        'value' => $exit->exit_status,
                        'label' => $exit->status_label,
                        'color' => $exit->status_color,
                    ],
                    'details' => [
                        'exit_reason' => $exit->exit_reason,
                        'exit_notes' => $exit->exit_notes,
                        'actual_exit_date' => $exit->actual_exit_date ? $exit->actual_exit_date->format('d-m-Y') : null,
                    ],
                    'clearance' => [
                        'clearance_done' => $exit->clearance_done,
                        'clearance_date' => $exit->clearance_date ? $exit->clearance_date->format('d-m-Y') : null,
                        'assets_returned' => $exit->assets_returned,
                        'handover_completed' => $exit->handover_completed,
                        'settlement_done' => $exit->settlement_done,
                    ],
                    'audit' => [
                        'initiated_by' => $exit->initiatedBy ? $exit->initiatedBy->name : 'System',
                        'initiated_at' => $exit->created_at->format('d-m-Y H:i:s'),
                        'approved_by' => $exit->approvedBy ? $exit->approvedBy->name : null,
                        'approved_at' => $exit->approved_at ? $exit->approved_at->format('d-m-Y H:i:s') : null,
                        'completed_by' => $exit->completedBy ? $exit->completedBy->name : null,
                        'completed_at' => $exit->completed_at ? $exit->completed_at->format('d-m-Y H:i:s') : null,
                    ],
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching exit details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get exit history for employee
     */
    public function getExitHistory($employeeId)
    {
        try {
            $context = $this->getInstituteBranchContext();
            $instituteId = $context['institute_id'];

            $exits = EmployeeExit::where('employee_id', $employeeId)
                ->where('institute_id', $instituteId)
                ->orderBy('created_at', 'desc')
                ->with(['initiatedBy', 'approvedBy', 'completedBy'])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $exits->map(function($exit) {
                    return [
                        'id' => $exit->id,
                        'status' => $exit->status_label,
                        'status_color' => $exit->status_color,
                        'notice_period' => "{$exit->notice_period_days} days",
                        'start_date' => $exit->notice_start_date->format('d-m-Y'),
                        'end_date' => $exit->notice_end_date->format('d-m-Y'),
                        'exit_reason' => $exit->exit_reason,
                        'initiated_by' => $exit->initiatedBy ? $exit->initiatedBy->name : 'System',
                        'initiated_at' => $exit->created_at->format('d-m-Y H:i:s'),
                        'completed_at' => $exit->completed_at ? $exit->completed_at->format('d-m-Y H:i:s') : null,
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching exit history: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send exit initiation notification
     */
    private function sendExitInitiationNotification($employee, $startDate, $endDate, $noticeDays, $status)
    {
        try {
            $instituteName = $this->getInstituteName($employee->institute_id);

            $emailData = [
                'employeeName' => $employee->name,
                'instituteName' => $instituteName,
                'startDate' => $startDate->format('d-m-Y'),
                'endDate' => $endDate->format('d-m-Y'),
                'noticeDays' => $noticeDays,
                'employeeCode' => $employee->employee_code,
                'departmentName' => $employee->department ? $employee->department->department : 'N/A',
                'status' => $status,
                'isPendingApproval' => $status === 'pending_approval',
            ];

            if (!empty($employee->email)) {
                \Mail::send('emails.employee-exit-initiated', $emailData, function ($message) use ($employee, $instituteName) {
                    $message->to($employee->email)
                        ->subject("Exit Process Initiated - {$instituteName} (#{$employee->employee_code})");
                });
            }

            \Log::info('Exit initiation email sent to: ' . $employee->email);

        } catch (\Exception $e) {
            \Log::error('Failed to send exit initiation email: ' . $e->getMessage());
        }
    }

    /**
     * Get institute name
     */
    private function getInstituteName($instituteId)
    {
        try {
            $institute = \App\Models\InstituteBasicDetails::where('fincap_merchant_id', $instituteId)->first();
            return $institute ? $institute->name ?? $institute->fincap_merchant_name ?? 'Institute' : 'Institute';
        } catch (\Exception $e) {
            return 'Institute';
        }
    }
}