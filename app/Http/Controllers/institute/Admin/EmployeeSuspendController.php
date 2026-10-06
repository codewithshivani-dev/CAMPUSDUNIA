<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\ProductDetails;
use App\Models\FincapMerchantSubCategories; 
use App\Models\Departments;
use App\Models\InstituteBasicDetails;
use App\Models\EmployeeDetails;
use App\Models\Designations;
use App\Models\DepartmentCategory;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\EmployeeProbationLog;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use App\Notifications\ActivityNotification;
use App\Notifications\EmployeeOnboardedNotification;
use App\Traits\SendsInstituteNotifications;
use App\Models\User; 
use App\Models\EmployeeSuspensionLog;
use App\Exports\EmployeesExport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class EmployeeSuspendController extends Controller
{
    use InstituteBranchAccess,DepartmentRelationships; 
    use SendsInstituteNotifications;

    /**
     * Suspend an employee (temporary - blocks access)
     */
    public function suspendEmployee(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            // Get institute/branch context
            $context = $this->getInstituteBranchContext();

            // Find the employee
            $employee = EmployeeDetails::where('id', $id)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found or you do not have access.'
                ], 404);
            }

            // Apply branch restriction if branch admin
            if ($context['is_branch_admin'] && $context['branch_id']) {
                if ($employee->branch_id != $context['branch_id']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have access to this employee.'
                    ], 403);
                }
            }

            // Check if employee is already suspended
            if ($employee->suspend_status === 'suspended') {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee is already suspended.'
                ], 400);
            }

            // Check if employee is exited (can't suspend an exited employee)
            if ($employee->status === 'inactive') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot suspend an exited employee.'
                ], 400);
            }

            // Validate suspension reason
            $validator = Validator::make($request->all(), [
                'suspension_reason' => 'required|string|min:3|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please provide a valid suspension reason.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update employee suspend status
            $employee->update([
                'suspend_status' => 'suspended',
                'suspended_at' => now(),
                'suspension_reason' => $request->suspension_reason,
                'unsuspended_at' => null,
                'suspended_by' => auth()->id()
            ]);

            // Update user table status to suspended
            if ($employee->user_id) {
                User::where('id', $employee->user_id)->update([
                    'status' => 'suspended'
                ]);
            }

            // Create suspension log
            EmployeeSuspensionLog::create([
                'employee_id' => $employee->id,
                'employee_code' => $employee->employee_code,
                'user_id' => $employee->user_id,
                'action' => 'suspend',
                'reason' => $request->suspension_reason,
                'performed_by' => auth()->id(),
                'metadata' => json_encode([
                    'suspension_number' => $employee->suspensionLogs()->where('action', 'suspend')->count() + 1,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'previous_status' => 'active'
                ])
            ]);

            // Log the suspension action
            \Log::info('Employee suspended temporarily', [
                'employee_id' => $employee->id,
                'employee_code' => $employee->employee_code,
                'employee_name' => $employee->name,
                'reason' => $request->suspension_reason,
                'suspended_by' => auth()->id(),
                'suspended_at' => now()
            ]);

            // Send suspension notification if enabled
            $this->sendEmployeeSuspensionNotification($employee, $context, 'suspended', $request->suspension_reason);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Employee has been suspended temporarily. Access is now blocked.',
                    'suspension_count' => $employee->suspensionLogs()->where('action', 'suspend')->count()
                ]);
            }

            return redirect()->back()->with('success', 'Employee has been suspended temporarily. Access is blocked.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error suspending employee: ' . $e->getMessage(), [
                'employee_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error suspending employee: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error suspending employee: ' . $e->getMessage());
        }
    }

    /**
     * Unsuspend an employee (restore access)
     */
    public function unsuspendEmployee(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            // Get institute/branch context
            $context = $this->getInstituteBranchContext();

            // Find the employee
            $employee = EmployeeDetails::where('id', $id)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found or you do not have access.'
                ], 404);
            }

            // Apply branch restriction if branch admin
            if ($context['is_branch_admin'] && $context['branch_id']) {
                if ($employee->branch_id != $context['branch_id']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have access to this employee.'
                    ], 403);
                }
            }

            // Check if employee is not suspended
            if ($employee->suspend_status !== 'suspended') {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee is not currently suspended.'
                ], 400);
            }

            // Check if employee is exited (can't unsuspend an exited employee)
            if ($employee->status === 'inactive') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot unsuspend an exited employee.'
                ], 400);
            }

            // Get suspension reason before clearing (for audit/notification)
            $suspensionReason = $employee->suspension_reason;
            $unsuspensionReason = $request->unsuspension_reason ?? null;

            // Restore employee access - Clear suspension data
            $employee->update([
                'suspend_status' => 'active',
                'suspended_at' => null,
                'suspension_reason' => null,
                'unsuspended_at' => now(),
                'unsuspended_by' => auth()->id()
            ]);

            // Update user table status back to active
            if ($employee->user_id) {
                User::where('id', $employee->user_id)->update([
                    'status' => 'active'
                ]);
            }

            // Create unsuspension log
            EmployeeSuspensionLog::create([
                'employee_id' => $employee->id,
                'employee_code' => $employee->employee_code,
                'user_id' => $employee->user_id,
                'action' => 'unsuspend',
                'reason' => $unsuspensionReason,
                'performed_by' => auth()->id(),
                'metadata' => json_encode([
                    'previous_suspension_reason' => $suspensionReason,
                    'suspension_duration_hours' => $employee->suspended_at ? now()->diffInHours($employee->suspended_at) : null,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'total_suspensions' => $employee->suspensionLogs()->where('action', 'suspend')->count()
                ])
            ]);

            // Log the unsuspension action
            \Log::info('Employee unsuspended', [
                'employee_id' => $employee->id,
                'employee_code' => $employee->employee_code,
                'employee_name' => $employee->name,
                'previous_suspension_reason' => $suspensionReason,
                'unsuspension_reason' => $unsuspensionReason,
                'unsuspended_by' => auth()->id(),
                'unsuspended_at' => now()
            ]);

            // Send unsuspension notification if enabled
            $this->sendEmployeeSuspensionNotification($employee, $context, 'unsuspended', $suspensionReason, $unsuspensionReason);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Employee has been unsuspended. Access is restored.'
                ]);
            }

            return redirect()->back()->with('success', 'Employee has been unsuspended. Access is restored.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error unsuspending employee: ' . $e->getMessage(), [
                'employee_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error unsuspending employee: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error unsuspending employee: ' . $e->getMessage());
        }
    }

    /**
     * Get suspension history for an employee
     */
    public function getEmployeeSuspensionHistory($id)
    {
        try {
            $context = $this->getInstituteBranchContext();
            
            $employee = EmployeeDetails::where('id', $id)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found'
                ], 404);
            }
            
            $suspensionLogs = EmployeeSuspensionLog::where('employee_id', $id)
                ->with('performedBy')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $statistics = [
                'total_suspensions' => $suspensionLogs->where('action', 'suspend')->count(),
                'total_unsuspensions' => $suspensionLogs->where('action', 'unsuspend')->count(),
                'currently_suspended' => $employee->suspend_status === 'suspended',
                'last_suspension' => $suspensionLogs->where('action', 'suspend')->first(),
                'last_unsuspension' => $suspensionLogs->where('action', 'unsuspend')->first(),
            ];
            
            return response()->json([
                'success' => true,
                'logs' => $suspensionLogs,
                'statistics' => $statistics
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching suspension history: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send suspension/unsuspension notification
     */
    private function sendEmployeeSuspensionNotification($employee, $context, $action, $reason = null, $unsuspensionReason = null)
    {
        try {
            // Check if suspension notification is enabled
            $moduleName = $action === 'suspended' ? 'employee_suspension' : 'employee_unsuspension';
            $emailEnabled = $this->isNotificationEnabled(
                $context['institute_id'],
                $moduleName,
                'email'
            );
            
            if (!$emailEnabled || empty($employee->email)) {
                \Log::info("{$moduleName} email notifications disabled or no email for employee: " . $employee->id);
                return;
            }

            // Get institute details
            $institute = InstituteBasicDetails::where('fincap_merchant_id', $context['institute_id'])->first();
            $instituteName = $institute ? $institute->name ?? 'Institute' : 'Institute';
            
            // Get department name
            $departmentName = 'N/A';
            if ($employee->department_id) {
                $department = Departments::where('department_id', $employee->department_id)->first();
                $departmentName = $department ? $department->department : 'N/A';
            }
            
            // Get suspension statistics
            $totalSuspensions = $employee->suspensionLogs()->where('action', 'suspend')->count();
            $lastSuspension = $employee->suspensionLogs()->where('action', 'suspend')->latest()->first();
            
            // Prepare email data
            $emailData = [
                'employeeName' => $employee->name,
                'employeeCode' => $employee->employee_code,
                'instituteName' => $instituteName,
                'action' => $action,
                'reason' => $reason,
                'unsuspensionReason' => $unsuspensionReason,
                'date' => now()->format('d-m-Y H:i:s'),
                'departmentName' => $departmentName,
                'designation' => $employee->designation ?? 'N/A',
                'totalSuspensions' => $totalSuspensions,
                'suspensionNumber' => $totalSuspensions,
                'previousSuspensionDate' => $lastSuspension ? $lastSuspension->created_at->format('d-m-Y') : null,
            ];

            // Send email to employee
            $view = $action === 'suspended' 
                ? 'emails.employee-suspended' 
                : 'emails.employee-unsuspended';
            
            Mail::send($view, $emailData, function ($message) use ($employee, $instituteName, $action) {
                $subject = $action === 'suspended' 
                    ? "Account Suspension Notice - {$instituteName} (#{$employee->employee_code})"
                    : "Account Access Restored - {$instituteName} (#{$employee->employee_code})";
                $message->to($employee->email)->subject($subject);
            });
            
            \Log::info("Employee {$action} email sent to: " . $employee->email);

        } catch (\Exception $e) {
            \Log::error("Failed to send {$action} notification: " . $e->getMessage());
        }
    }

    /**
     * Check if a specific notification channel is enabled for a module
     */
    private function isNotificationEnabled($instituteId, $moduleName, $channel)
    {
        // try {
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
        //     } catch (\Exception $e) {
        //         \Log::error('Error checking notification status: ' . $e->getMessage());
        //         return $channel === 'email'; // Default to email only on error
        //     }
    }


}