<?php

namespace App\Services;

use App\Models\EmployeeDetails;
use App\Models\EmployeeExit;
use App\Models\EmployeeExitApproval;
use App\Models\EmployeeExitPolicy;
use App\Models\User;
use App\Notifications\EmployeeExitNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class EmployeeExitService
{
    /**
     * Get employee exit policy
     */
    public function getEmployeeExitPolicy($employee, $instituteId)
    {
        // Employee-wise policy
        $policy = EmployeeExitPolicy::where('institute_id', $instituteId)
            ->where('policy_type', 'employee_wise')
            ->where('employment_type', $employee->employment_type)
            ->where('employee_id', $employee->employee_id)
            ->where('is_active', true)
            ->first();
        
        // Department-wise policy
        if (!$policy && $employee->department_id) {
            $policy = EmployeeExitPolicy::where('institute_id', $instituteId)
                ->where('policy_type', 'department_wise')
                ->where('employment_type', $employee->employment_type)
                ->where('department_id', $employee->department_id)
                ->where('is_active', true)
                ->first();
        }
       
        return $policy;
    }

    /**
     * Get exit approvers
     */
    public function getExitApprovers($employee)
    {
        $approvers = [];
        $instituteId = $employee->institute_id;
        $departmentId = $employee->department_id;
        $branchId = $employee->branch_id;
        
        // 1. Find Department HOD/Manager
        $departmentHod = EmployeeDetails::where('institute_id', $instituteId)
            ->when($branchId, function($query) use ($branchId) {
                return $query->where('branch_id', $branchId);
            })
            ->where('department_id', $departmentId)
            ->where('user_id', '!=', $employee->user_id)
            ->whereIn('assigned_role', ['manager', 'hod', 'head', 'supervisor'])
            ->where('status', 'active')
            ->first();
        
        if ($departmentHod) {
            $approvers[] = [
                'user_id' => $departmentHod->user_id,
                'name' => $departmentHod->name,
                'role' => 'Department ' . ucfirst($departmentHod->assigned_role),
                'employee_id' => $departmentHod->employee_id,
                'type' => 'department_hod'
            ];
        }
        
        // 2. Find HR/Admin
        $adminUsers = User::whereHas('roles', function($query) {
                $query->whereIn('name', ['admin', 'superadmin', 'institute_admin', 'hr']);
            })
            ->where('institute_id', $instituteId)
            ->when($branchId, function($query) use ($branchId) {
                return $query->where('branch_id', $branchId);
            })
            ->where('id', '!=', $employee->user_id)
            ->get();
        
        foreach ($adminUsers as $adminUser) {
            $approvers[] = [
                'user_id' => $adminUser->id,
                'name' => $adminUser->name,
                'role' => 'Admin',
                'employee_id' => null,
                'type' => 'admin'
            ];
        }
        
        // 3. Fallback: Any admin
        if (empty($approvers)) {
            $fallbackAdmin = User::whereHas('roles', function($query) {
                    $query->whereIn('name', ['admin', 'superadmin']);
                })
                ->where('id', '!=', $employee->user_id)
                ->first();
            
            if ($fallbackAdmin) {
                $approvers[] = [
                    'user_id' => $fallbackAdmin->id,
                    'name' => $fallbackAdmin->name,
                    'role' => 'System Admin',
                    'employee_id' => null,
                    'type' => 'admin'
                ];
            }
        }
        
        // Remove duplicates
        $approvers = collect($approvers)->unique('user_id')->values()->toArray();
        
        return $approvers;
    }

    /**
     * Create exit record
     */
    public function createExit($employee, $data, $context)
    {
        $exitPolicy = $this->getEmployeeExitPolicy($employee, $context['institute_id']);
        $noticeDays = $data['notice_period_days'] ?? ($exitPolicy ? $exitPolicy->notice_period_days : 30);
        
        // Determine if approval is required
        $approvalRequired = true; // Default for employee
        
        // For admin-initiated exits, NO approval required
        if (isset($data['initiation_source']) && $data['initiation_source'] === 'admin') {
            $approvalRequired = false; // ✅ Admin initiated = auto-approved
        }
        
        // Determine exit status
        $exitStatus = $approvalRequired ? 'pending_approval' : 'notice_period';
        
        // Handle document upload
        $documentPath = null;
        if (isset($data['exit_document']) && $data['exit_document']) {
            $file = $data['exit_document'];
            $filename = 'exit_doc_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/uploads/exit_documents', $filename);
            $documentPath = 'uploads/exit_documents/' . $filename;
        }
        
        // Determine dates
        $resignationDate = $data['resignation_date'] ?? Carbon::now();
        $proposedLastWorkingDate = $data['proposed_last_working_date'] ?? null;
        $noticeStartDate = $data['notice_start_date'] ?? null;
        $noticeEndDate = $data['notice_end_date'] ?? null;
        
        // Get initiation_source from data, default to 'employee'
        $initiationSource = $data['initiation_source'] ?? 'employee';
        
        // ✅ FIX: Get exit_type from data, default to 'Resignation'
        $exitType = $data['exit_type'] ?? 'Resignation';
        
        // Log the data for debugging
        Log::info('Creating exit with data:', [
            'initiation_source' => $initiationSource,
            'exit_type' => $exitType,
            'approval_required' => $approvalRequired,
            'exit_status' => $exitStatus
        ]);
        
        // Create exit record with ALL fields including exit_type
        $exit = EmployeeExit::create([
            'institute_id' => $context['institute_id'],
            'branch_id' => $context['branch_id'],
            'employee_id' => $employee->employee_id,
            'exit_policy_id' => $exitPolicy ? $exitPolicy->id : null,
            'resignation_date' => $resignationDate,
            'proposed_last_working_date' => $proposedLastWorkingDate,
            'notice_start_date' => $noticeStartDate,
            'notice_end_date' => $noticeEndDate,
            'notice_period_days' => $noticeDays,
            'exit_status' => $exitStatus,
            'exit_reason' => $data['exit_reason'] ?? null,
            'exit_notes' => $data['exit_notes'] ?? null,
            'exit_document' => $documentPath,
            'initiation_source' => $initiationSource,
            'initiated_by' => $data['initiated_by'] ?? auth()->id(),
            'initiated_at' => Carbon::now(),
            // ✅ FIX: ADD exit_type here
            'exit_type' => $exitType,
        ]);
        
        // ✅ If admin initiated, auto-approve immediately (skip approval workflow)
        if ($initiationSource === 'admin' && $exitStatus === 'notice_period') {
            // Admin initiated exits are already in notice_period status
            // Just log it
            Log::info('Admin initiated exit auto-approved: ' . $exit->id);
        }
        
        // Create approval records if required (only for employee-initiated)
        if ($approvalRequired && $exitStatus === 'pending_approval') {
            $approvers = $this->getExitApprovers($employee);
            
            if (empty($approvers)) {
                // Auto-approve if no approvers (should rarely happen)
                $exit->update([
                    'exit_status' => 'notice_period',
                    'approved_by' => 1,
                    'approved_at' => Carbon::now(),
                    'notice_start_date' => Carbon::now(),
                    'notice_end_date' => Carbon::now()->addDays($noticeDays),
                    'proposed_last_working_date' => Carbon::now()->addDays($noticeDays),
                ]);
            } else {
                foreach ($approvers as $index => $approver) {
                    EmployeeExitApproval::create([
                        'institute_id' => $context['institute_id'],
                        'branch_id' => $context['branch_id'],
                        'exit_id' => $exit->id,
                        'approval_step_id' => $index + 1,
                        'approver_id' => $approver['user_id'],
                        'approver_name' => $approver['name'],
                        'approver_role' => $approver['role'],
                        'status' => 'Pending',
                        'step_number' => $index + 1,
                        'total_steps' => count($approvers),
                    ]);
                }
            }
        }
        
        // Send notifications (both email and in-app)
        $this->sendExitInitiationNotifications($employee, $exit);
        
        return $exit;
    }
    
    /**
     * Approve exit - Start notice period
     */
    public function approveExit($exitId, $userId)
    {
        $exit = EmployeeExit::findOrFail($exitId);
        
        if ($exit->exit_status !== 'pending_approval') {
            throw new \Exception('Exit is not pending approval.');
        }
        
        DB::beginTransaction();
        
        try {
            // Calculate notice period from approval date
            $noticeDays = $exit->notice_period_days ?? 30;
            $approvalDate = Carbon::now();
            $noticeEndDate = $approvalDate->copy()->addDays($noticeDays);
            
            $exit->update([
                'exit_status' => 'notice_period',
                'approved_by' => $userId,
                'approved_at' => $approvalDate,
                'notice_start_date' => $approvalDate,
                'notice_end_date' => $noticeEndDate,
                'proposed_last_working_date' => $noticeEndDate,
            ]);
            
            // Mark all approvals as approved
            EmployeeExitApproval::where('exit_id', $exit->id)
                ->where('status', 'Pending')
                ->update([
                    'status' => 'Approved',
                    'approved_date' => Carbon::now(),
                ]);
            
            DB::commit();
            
            // Send notifications (both email and in-app)
            $this->sendExitApprovalNotifications($exit, 'approved');
            
            return $exit;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reject exit
     */
    public function rejectExit($exitId, $userId, $reason = null)
    {
        $exit = EmployeeExit::findOrFail($exitId);
        
        if ($exit->exit_status !== 'pending_approval') {
            throw new \Exception('Exit is not pending approval.');
        }
        
        DB::beginTransaction();
        
        try {
            $exit->update([
                'exit_status' => 'rejected',
                'rejected_by' => $userId,
                'rejected_at' => Carbon::now(),
                'rejection_reason' => $reason,
            ]);
            
            // Mark all approvals as rejected
            EmployeeExitApproval::where('exit_id', $exit->id)
                ->where('status', 'Pending')
                ->update([
                    'status' => 'Rejected',
                    'approved_date' => Carbon::now(),
                    'comments' => $reason ?? 'Exit request rejected',
                ]);
            
            DB::commit();
            
            // Send notifications (both email and in-app)
            $this->sendExitApprovalNotifications($exit, 'rejected', $reason);
            
            return $exit;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Complete exit
     */
    public function completeExit($exitId, $userId = null)
    {
        $exit = EmployeeExit::findOrFail($exitId);
        
        if (!in_array($exit->exit_status, ['notice_period', 'approved'])) {
            throw new \Exception('Exit cannot be completed. Current status: ' . $exit->exit_status);
        }
        
        DB::beginTransaction();
        
        try {
            $exit->update([
                'exit_status' => 'exited',
                'actual_exit_date' => Carbon::now(),
                'completed_by' => $userId ?? 1,
                'completed_at' => Carbon::now(),
                'user_account_deactivated' => true,
                'user_account_deactivation_date' => Carbon::now(),
            ]);
            
            // Update employee status
            $exit->employee->update([
                'status' => 'inactive',
                'exit_date' => Carbon::now(),
            ]);
            
            // Deactivate user account
            if ($exit->employee->user_id) {
                $user = User::find($exit->employee->user_id);
                if ($user && $user->status !== 'inactive') {
                    $user->update([
                        'status' => 'inactive',
                        'deactivated_at' => Carbon::now(),
                    ]);
                }
            }
            
            DB::commit();
            
            // Send notifications (both email and in-app)
            $this->sendExitCompletionNotifications($exit);
            
            return $exit;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Cancel exit
     */
    public function cancelExit($exitId, $userId, $reason = null)
    {
        $exit = EmployeeExit::findOrFail($exitId);
        
        if (!in_array($exit->exit_status, ['pending_approval', 'approved', 'notice_period'])) {
            throw new \Exception('Exit cannot be cancelled. Current status: ' . $exit->exit_status);
        }
        
        DB::beginTransaction();
        
        try {
            $exit->update([
                'exit_status' => 'cancelled',
                'cancelled_by' => $userId,
                'cancelled_at' => Carbon::now(),
                'cancellation_reason' => $reason,
            ]);
            
            // Reactivate employee if they were deactivated
            if ($exit->employee->status !== 'active') {
                $exit->employee->update([
                    'status' => 'active',
                    'exit_date' => null,
                ]);
            }
            
            // Reactivate user account
            if ($exit->employee->user_id) {
                $user = User::find($exit->employee->user_id);
                if ($user && $user->status !== 'active') {
                    $user->update([
                        'status' => 'active',
                        'deactivated_at' => null,
                    ]);
                }
            }
            
            DB::commit();
            
            // Send notifications (both email and in-app)
            $this->sendExitCancellationNotifications($exit);
            
            return $exit;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // =====================================================
    // ========== NOTIFICATION METHODS =====================
    // =====================================================

    /**
     * Check if a specific notification channel is enabled for a module
     */
    private function isNotificationEnabled($instituteId, $moduleName, $channel)
    {
        try {
            $setting = \App\Models\InstituteNotificationSetting::where('institute_id', $instituteId)
                ->where('module_name', $moduleName)
                ->first();
            
            if (!$setting) {
                // If no setting found, use default (email enabled by default)
                return $channel === 'email';
            }
            
            switch ($channel) {
                case 'email':
                    return $setting->email_enabled;
                case 'whatsapp':
                    return $setting->whatsapp_enabled;
                case 'sms':
                    return $setting->sms_enabled;
                default:
                    return false;
            }
        } catch (\Exception $e) {
            \Log::error('Error checking notification status: ' . $e->getMessage());
            return $channel === 'email'; // Default to email only on error
        }
    }

    /**
     * Send exit initiation notifications (Email + In-App)
     */
    public function sendExitInitiationNotifications($employee, $exit)
    {
        try {
            $instituteId = $employee->institute_id;
            $initiationSource = $exit->initiation_source ?? 'employee';
            $moduleName = 'employee_exit_initiated';
            
            // 1. Send Email to Employee
            if ($this->isNotificationEnabled($instituteId, $moduleName, 'email') && !empty($employee->email)) {
                $this->sendExitInitiationEmail($employee, $exit);
            }

            // 2. Send In-App Notification to Employee
            $this->sendInAppNotification(
                $employee->user_id,
                'employee_exit_initiated',
                $initiationSource === 'admin' ? 'Exit Process Initiated by Admin' : 'Resignation Request Submitted',
                $initiationSource === 'admin' 
                    ? "Admin has initiated your exit process. Status: {$exit->exit_status}"
                    : "Your resignation request has been submitted. Status: {$exit->exit_status}",
                $employee->institute_id,
                [
                    'exit_id' => $exit->id,
                    'status' => $exit->exit_status,
                    'initiation_source' => $initiationSource,
                    'action_url' => route('employee.exit.dashboard'),
                ]
            );

            // 3. Send Notifications to Approvers
            $approvals = EmployeeExitApproval::where('exit_id', $exit->id)
                ->where('status', 'Pending')
                ->get();
            
            foreach ($approvals as $approval) {
                $approver = User::find($approval->approver_id);
                if ($approver) {
                    if ($this->isNotificationEnabled($instituteId, 'exit_approval_request', 'email') && !empty($approver->email)) {
                        $this->sendExitApprovalRequestEmail($approver, $employee, $exit);
                    }

                    $this->sendInAppNotification(
                        $approver->id,
                        'exit_approval_request',
                        'Exit Request Approval Required',
                        "{$employee->name} ({$employee->employee_code}) has submitted an exit request. Please review and approve.",
                        $employee->institute_id,
                        [
                            'exit_id' => $exit->id,
                            'employee_id' => $employee->employee_id,
                            'action_url' => route('exit.approvals'),
                        ]
                    );
                }
            }

            // ✅ 4. NEW: Send notification to ALL admins/HR
            $this->sendNotificationToAllAdmins($employee, $exit, 'initiated');

            Log::info('Exit initiation notifications sent for exit: ' . $exit->id);

        } catch (\Exception $e) {
            Log::error('Failed to send exit initiation notifications: ' . $e->getMessage(), [
                'exit_id' => $exit->id ?? null,
                'employee_id' => $employee->id ?? null,
            ]);
        }
    }

    /**
     * Send exit initiation email
     */
    private function sendExitInitiationEmail($employee, $exit)
    {
        try {
            $instituteName = $this->getInstituteName($employee->institute_id);
            $initiationSource = $exit->initiation_source ?? 'employee';
            
            $emailData = [
                'employeeName' => $employee->name,
                'employeeCode' => $employee->employee_code,
                'instituteName' => $instituteName,
                'exitStatus' => $exit->exit_status,
                'exitReason' => $exit->exit_reason,
                'initiationSource' => $initiationSource,
                'status' => $exit->exit_status,
                'noticeStartDate' => $exit->notice_start_date ? Carbon::parse($exit->notice_start_date)->format('d M Y') : 'N/A',
                'noticeEndDate' => $exit->notice_end_date ? Carbon::parse($exit->notice_end_date)->format('d M Y') : 'N/A',
                'proposedLastWorkingDate' => $exit->proposed_last_working_date ? Carbon::parse($exit->proposed_last_working_date)->format('d M Y') : 'N/A',
                'noticeDays' => $exit->notice_period_days ?? 30,
            ];

            Mail::send('emails.employee-exit-initiated', $emailData, function ($message) use ($employee, $instituteName, $initiationSource) {
                $subject = $initiationSource === 'admin' 
                    ? "Exit Process Initiated by Admin - {$instituteName} (#{$employee->employee_code})"
                    : "Resignation Request Submitted - {$instituteName} (#{$employee->employee_code})";
                $message->to($employee->email)
                    ->subject($subject);
            });
            
        } catch (\Exception $e) {
            Log::error('Failed to send exit initiation email: ' . $e->getMessage());
        }
    }

    /**
     * Send exit approval request email to approver
     */
    private function sendExitApprovalRequestEmail($approver, $employee, $exit)
    {
        // try {
            $instituteName = $this->getInstituteName($employee->institute_id);
            
            Mail::send('emails.exit-approval-request', [
                'approverName' => $approver->name,
                'employeeName' => $employee->name,
                'employeeCode' => $employee->employee_code,
                'instituteName' => $instituteName,
                'exitReason' => $exit->exit_reason,
                'exitId' => $exit->id,
                'initiationSource' => $exit->initiation_source ?? 'employee',
            ], function ($message) use ($approver, $instituteName) {
                $message->to($approver->email)
                    ->subject("Exit Request Approval Required - {$instituteName}");
            });
            
        // } catch (\Exception $e) {
        //     Log::error('Failed to send exit approval request email: ' . $e->getMessage());
        // }
    }

    /**
     * Send exit approval notifications (Email + In-App)
     */
    public function sendExitApprovalNotifications($exit, $status, $reason = null, $performedBy = null)
    {
        try {
            $employee = $exit->employee;
            $instituteId = $employee->institute_id;
            
            // ✅ Normalize status
            $normalizedStatus = $status;
            if ($status === 'approve') {
                $normalizedStatus = 'approved';
            } elseif ($status === 'reject') {
                $normalizedStatus = 'rejected';
            }
            
            $moduleName = $normalizedStatus === 'approved' ? 'employee_exit_approved' : 'employee_exit_rejected';
            
            // 1. Send Email to Employee ✅ WITH performedBy
            if ($this->isNotificationEnabled($instituteId, $moduleName, 'email') && !empty($employee->email)) {
                $this->sendExitStatusUpdateEmail($employee, $exit, $normalizedStatus, $reason, $performedBy);
            }

            // 2. Send In-App Notification to Employee
            $notificationTitle = $normalizedStatus === 'approved' 
                ? 'Exit Request Approved - Notice Period Started' 
                : 'Exit Request Rejected';
            $notificationMessage = $normalizedStatus === 'approved'
                ? "Your exit request has been approved. Notice period started from " . ($exit->notice_start_date ? Carbon::parse($exit->notice_start_date)->format('d M Y') : 'today') . "."
                : "Your exit request has been rejected." . ($reason ? " Reason: {$reason}" : "");

            $this->sendInAppNotification(
                $employee->user_id,
                $normalizedStatus === 'approved' ? 'employee_exit_approved' : 'employee_exit_rejected',
                $notificationTitle,
                $notificationMessage,
                $employee->institute_id,
                [
                    'exit_id' => $exit->id,
                    'status' => $normalizedStatus,
                    'action_url' => route('employee.exit.dashboard'),
                ]
            );

            // 3. Send Notification to Admin who approved/rejected
            if ($performedBy) {
                $this->sendInAppNotification(
                    $performedBy->id,
                    'exit_approval_action',
                    $normalizedStatus === 'approved' ? '✅ Exit Request Approved' : '❌ Exit Request Rejected',
                    "You have {$normalizedStatus} the exit request for {$employee->name} ({$employee->employee_code})." . ($reason ? " Reason: {$reason}" : ""),
                    $employee->institute_id,
                    [
                        'exit_id' => $exit->id,
                        'action_url' => route('exit.approvals.index'),
                    ]
                );
            }

            // ✅ 4. Send notification to ALL admins/HR
            $this->sendNotificationToAllAdmins($employee, $exit, $normalizedStatus, $reason, $performedBy);

            Log::info('Exit approval notifications sent for exit: ' . $exit->id);

        } catch (\Exception $e) {
            Log::error('Failed to send exit approval notifications: ' . $e->getMessage(), [
                'exit_id' => $exit->id ?? null,
            ]);
        }
    }

    /**
     * Send exit status update email to employee
     */
    private function sendExitStatusUpdateEmail($employee, $exit, $status, $reason = null, $performedBy = null)
    {
        try {
            $instituteName = $this->getInstituteName($employee->institute_id);
            
            // ✅ Normalize status
            $normalizedStatus = $status;
            if ($status === 'approve') {
                $normalizedStatus = 'approved';
            } elseif ($status === 'reject') {
                $normalizedStatus = 'rejected';
            }
            
            $emailData = [
                'employeeName' => $employee->name,
                'employeeCode' => $employee->employee_code,
                'instituteName' => $instituteName,
                'status' => $normalizedStatus,
                'reason' => $reason,
                'performedBy' => $performedBy ? $performedBy->name : null,
                'noticeStartDate' => $exit->notice_start_date ? Carbon::parse($exit->notice_start_date)->format('d M Y') : 'N/A',
                'noticeEndDate' => $exit->notice_end_date ? Carbon::parse($exit->notice_end_date)->format('d M Y') : 'N/A',
                'proposedLastWorkingDate' => $exit->proposed_last_working_date ? Carbon::parse($exit->proposed_last_working_date)->format('d M Y') : 'N/A',
                'noticeDays' => $exit->notice_period_days ?? 30,
            ];

            Mail::send('emails.employee-exit-status-update', $emailData, function ($message) use ($employee, $instituteName, $normalizedStatus) {
                $subject = $normalizedStatus === 'approved' 
                    ? "✅ Exit Request Approved - Notice Period Started - {$instituteName}"
                    : "❌ Exit Request Rejected - {$instituteName}";
                $message->to($employee->email)->subject($subject);
            });

            Log::info('Exit status update email sent to employee: ' . $employee->email . ' with status: ' . $normalizedStatus);
            
        } catch (\Exception $e) {
            Log::error('Failed to send exit status update email: ' . $e->getMessage());
        }
    }

    /**
     * Send exit completion notifications (Email + In-App)
     */
    public function sendExitCompletionNotifications($exit)
    {
        try {
            $employee = $exit->employee;
            $instituteId = $employee->institute_id;
            $moduleName = 'employee_exit_completed';
            
            // 1. Send Email to Employee (check if enabled)
            if ($this->isNotificationEnabled($instituteId, $moduleName, 'email') && !empty($employee->email)) {
                try {
                    $instituteName = $this->getInstituteName($employee->institute_id);
                    
                    Mail::send('emails.employee-exit-completed', [
                        'employeeName' => $employee->name,
                        'employeeCode' => $employee->employee_code,
                        'instituteName' => $instituteName,
                        'exitDate' => Carbon::parse($exit->actual_exit_date)->format('d M Y'),
                        'noticeEndDate' => $exit->notice_end_date ? Carbon::parse($exit->notice_end_date)->format('d M Y') : 'N/A',
                    ], function ($message) use ($employee, $instituteName) {
                        $message->to($employee->email)
                            ->subject("Exit Process Completed - {$instituteName}");
                    });
                } catch (\Exception $e) {
                    Log::error('Failed to send exit completion email: ' . $e->getMessage());
                }
            }

            // 2. Send In-App Notification to Employee
            $this->sendInAppNotification(
                $employee->user_id,
                'employee_exit_completed',
                'Exit Process Completed',
                "Your exit process has been completed. Last working day: " . Carbon::parse($exit->actual_exit_date)->format('d M Y'),
                $employee->institute_id,
                [
                    'exit_id' => $exit->id,
                    'action_url' => route('employee.exit.dashboard'),
                ]
            );

            Log::info('Exit completion notifications sent for exit: ' . $exit->id);

        } catch (\Exception $e) {
            Log::error('Failed to send exit completion notifications: ' . $e->getMessage(), [
                'exit_id' => $exit->id ?? null,
            ]);
        }
    }

    /**
     * Send exit cancellation notifications (Email + In-App)
     */
    public function sendExitCancellationNotifications($exit)
    {
        try {
            $employee = $exit->employee;
            $instituteId = $employee->institute_id;
            $moduleName = 'employee_exit_cancelled';
            $initiationSource = $exit->initiation_source ?? 'employee';
            
            // 1. Send Email to Employee (check if enabled)
            if ($this->isNotificationEnabled($instituteId, $moduleName, 'email') && !empty($employee->email)) {
                try {
                    $instituteName = $this->getInstituteName($employee->institute_id);
                    
                    Mail::send('emails.employee-exit-cancelled', [
                        'employeeName' => $employee->name,
                        'employeeCode' => $employee->employee_code,
                        'instituteName' => $instituteName,
                        'initiationSource' => $initiationSource,
                        'reason' => $exit->cancellation_reason ?? 'No specific reason provided',
                        'cancelledAt' => Carbon::parse($exit->cancelled_at)->format('d M Y H:i:s'),
                    ], function ($message) use ($employee, $instituteName, $initiationSource) {
                        $subject = $initiationSource === 'admin'
                            ? "Exit Process Cancelled by Admin - {$instituteName}"
                            : "Your Exit Request Has Been Cancelled - {$instituteName}";
                        $message->to($employee->email)->subject($subject);
                    });
                } catch (\Exception $e) {
                    Log::error('Failed to send exit cancellation email: ' . $e->getMessage());
                }
            }

            // 2. Send In-App Notification to Employee
            $this->sendInAppNotification(
                $employee->user_id,
                'employee_exit_cancelled',
                $initiationSource === 'admin' ? 'Exit Process Cancelled by Admin' : 'Exit Request Cancelled',
                $initiationSource === 'admin'
                    ? "Admin has cancelled your exit process. Your account remains active."
                    : "Your exit request has been cancelled. You can continue working.",
                $employee->institute_id,
                [
                    'exit_id' => $exit->id,
                    'initiation_source' => $initiationSource,
                    'action_url' => route('employee.exit.dashboard'),
                ]
            );

            Log::info('Exit cancellation notifications sent for exit: ' . $exit->id);

        } catch (\Exception $e) {
            Log::error('Failed to send exit cancellation notifications: ' . $e->getMessage(), [
                'exit_id' => $exit->id ?? null,
            ]);
        }
    }

    /**
     * Generic method to send in-app notification
     */
    public function sendInAppNotification($userId, $type, $title, $message, $instituteId, $extraData = [])
    {
        try {
            if (!$userId) {
                Log::warning('Cannot send in-app notification: User ID is null', [
                    'type' => $type,
                    'title' => $title,
                ]);
                return;
            }

            $user = User::find($userId);
            if (!$user) {
                Log::warning('Cannot send in-app notification: User not found', [
                    'user_id' => $userId,
                    'type' => $type,
                ]);
                return;
            }

            $notificationData = array_merge([
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'institute_id' => $instituteId,
                'module' => 'employee_exit',
                'timestamp' => Carbon::now()->toIso8601String(),
                'icon' => $this->getNotificationIcon($type),
                'color' => $this->getNotificationColor($type),
            ], $extraData);

            // Send via Laravel Notification system
            $user->notify(new EmployeeExitNotification($notificationData));

        } catch (\Exception $e) {
            Log::error('Failed to send in-app notification: ' . $e->getMessage(), [
                'user_id' => $userId,
                'type' => $type,
            ]);
        }
    }

    /**
     * Get notification icon based on type
     */
    public function getNotificationIcon($type)
    {
        $icons = [
            'employee_exit_initiated' => 'user-minus',
            'employee_exit_approved' => 'check-circle',
            'employee_exit_rejected' => 'times-circle',
            'employee_exit_completed' => 'check-double',
            'employee_exit_cancelled' => 'undo-alt',
            'exit_approval_request' => 'user-clock',
            'exit_approval_action' => 'clipboard-check',
            'employee_exit' => 'door-open',
        ];

        return $icons[$type] ?? 'bell';
    }

    /**
     * Get notification color based on type
     */
    public function getNotificationColor($type)
    {
        $colors = [
            'employee_exit_initiated' => 'warning',
            'employee_exit_approved' => 'success',
            'employee_exit_rejected' => 'danger',
            'employee_exit_completed' => 'info',
            'employee_exit_cancelled' => 'secondary',
            'exit_approval_request' => 'primary',
            'exit_approval_action' => 'info',
            'employee_exit' => 'danger',
        ];

        return $colors[$type] ?? 'secondary';
    }

    /**
     * Get institute name
     */
    public function getInstituteName($instituteId)
    {
        try {
            $institute = \App\Models\InstituteBasicDetails::where('fincap_merchant_id', $instituteId)->first();
            return $institute ? $institute->name ?? $institute->fincap_merchant_name ?? 'Institute' : 'Institute';
        } catch (\Exception $e) {
            return 'Institute';
        }
    }

    /**
     * Calculate notice period details
     */
    public function calculateNoticePeriodDetails($exit)
    {
        if (!$exit->notice_start_date || !$exit->notice_end_date) {
            return null;
        }
        
        $startDate = Carbon::parse($exit->notice_start_date);
        $endDate = Carbon::parse($exit->notice_end_date);
        $now = Carbon::now();
        
        $daysRemaining = $now->diffInDays($endDate, false);
        $isOverdue = $daysRemaining < 0;
        $daysWorked = $startDate->diffInDays($now);
        $totalDays = $startDate->diffInDays($endDate);
        $progress = $totalDays > 0 ? ($daysWorked / $totalDays) * 100 : 0;
        
        return [
            'start_date' => $startDate->format('d M Y'),
            'end_date' => $endDate->format('d M Y'),
            'days_remaining' => max(0, $daysRemaining),
            'is_overdue' => $isOverdue,
            'days_worked' => $daysWorked,
            'total_days' => $totalDays,
            'progress' => min(100, $progress),
            'status' => $exit->exit_status
        ];
    }


    private function sendNotificationToAllAdmins($employee, $exit, $action, $reason = null, $performedBy = null)
{
    try {
        $instituteId = $employee->institute_id;
        $branchId = $employee->branch_id;
        $instituteName = $this->getInstituteName($instituteId);
        
        // ✅ FIX: Normalize the action
        $normalizedAction = $action;
        if ($action === 'approve') {
            $normalizedAction = 'approved';
        } elseif ($action === 'reject') {
            $normalizedAction = 'rejected';
        }
        
        // ✅ Get ALL users with admin or HR roles
        $adminUsers = User::whereHas('roles', function($query) {
                $query->whereIn('name', ['admin', 'superadmin', 'institute_admin', 'hr']);
            })
            ->where('institute_id', $instituteId)
            ->when($branchId, function($query) use ($branchId) {
                return $query->where('branch_id', $branchId);
            })
            ->where('id', '!=', $employee->user_id) // Exclude the employee
            ->get();

        // ✅ Log how many admins we're sending to
        Log::info('Sending ' . $normalizedAction . ' notification to ' . $adminUsers->count() . ' admins for exit: ' . $exit->id);

        foreach ($adminUsers as $admin) {
            // ✅ Skip the person who performed the action (they already got notification)
            if ($performedBy && $admin->id == $performedBy->id) {
                continue;
            }

            // ✅ Send EMAIL to Admin
            if (!empty($admin->email)) {
                try {
                    if ($normalizedAction === 'initiated') {
                        // Use exit-approval-request.blade.php for new requests
                        Mail::send('emails.exit-approval-request', [
                            'approverName' => $admin->name,
                            'employeeName' => $employee->name,
                            'employeeCode' => $employee->employee_code,
                            'instituteName' => $instituteName,
                            'exitReason' => $exit->exit_reason,
                            'exitId' => $exit->id,
                            'initiationSource' => $exit->initiation_source ?? 'employee',
                        ], function ($message) use ($admin, $instituteName) {
                            $message->to($admin->email)
                                ->subject("📋 New Exit Request - {$instituteName}");
                        });
                    } else {
                        // ✅ FIX: Use the normalized action for status
                        $statusText = $normalizedAction; // 'approved' or 'rejected'
                        $isApproved = $statusText === 'approved';
                        $emoji = $isApproved ? '✅' : '❌';
                        $subjectText = $isApproved ? 'Approved' : 'Rejected';
                        
                        // ✅ Send email with proper status
                        Mail::send('emails.employee-exit-status-update', [
                            'employeeName' => $employee->name,
                            'employeeCode' => $employee->employee_code,
                            'instituteName' => $instituteName,
                            'status' => $statusText, // 'approved' or 'rejected'
                            'reason' => $reason,
                            'noticeStartDate' => $exit->notice_start_date ? Carbon::parse($exit->notice_start_date)->format('d M Y') : 'N/A',
                            'noticeEndDate' => $exit->notice_end_date ? Carbon::parse($exit->notice_end_date)->format('d M Y') : 'N/A',
                            'proposedLastWorkingDate' => $exit->proposed_last_working_date ? Carbon::parse($exit->proposed_last_working_date)->format('d M Y') : 'N/A',
                            'noticeDays' => $exit->notice_period_days ?? 30,
                            'performedBy' => $performedBy ? $performedBy->name : 'System',
                        ], function ($message) use ($admin, $instituteName, $subjectText, $emoji) {
                            $message->to($admin->email)
                                ->subject("{$emoji} Exit Request {$subjectText} - {$instituteName}");
                        });
                    }

                    Log::info('Email sent to admin: ' . $admin->email . ' for action: ' . $normalizedAction);

                } catch (\Exception $e) {
                    Log::error('Failed to send email to admin: ' . $admin->email . ' - ' . $e->getMessage());
                }
            }

            // ✅ Send IN-APP Notification to Admin
            $isApproved = $normalizedAction === 'approved';
            $title = $normalizedAction === 'initiated' 
                ? '📋 New Exit Request' 
                : ($isApproved ? '✅ Exit Request Approved' : '❌ Exit Request Rejected');
            
            $messageText = $normalizedAction === 'initiated'
                ? "{$employee->name} ({$employee->employee_code}) has submitted an exit request."
                : "{$employee->name} ({$employee->employee_code}) exit request has been {$normalizedAction}." . ($reason ? " Reason: {$reason}" : "");

            if ($performedBy) {
                $messageText .= " Action by: " . $performedBy->name;
            }

            $this->sendInAppNotification(
                $admin->id,
                'exit_admin_notification',
                $title,
                $messageText,
                $employee->institute_id,
                [
                    'exit_id' => $exit->id,
                    'employee_id' => $employee->employee_id,
                    'action' => $normalizedAction,
                    'action_url' => route('exit.approvals.index'),
                ]
            );
        }

        Log::info('Admin notifications sent successfully for exit: ' . $exit->id . ' action: ' . $normalizedAction);

    } catch (\Exception $e) {
        Log::error('Failed to send notifications to admins: ' . $e->getMessage(), [
            'exit_id' => $exit->id ?? null,
            'action' => $action ?? null,
        ]);
    }
    }
}