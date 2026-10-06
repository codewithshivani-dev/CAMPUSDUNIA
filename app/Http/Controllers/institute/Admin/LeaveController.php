<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeLeaveBalance;
use App\Models\EmployeeLeave;
use App\Models\LeaveApproval;
use App\Models\EmployeeDetails;
use App\Models\Departments;
use App\Models\LeaveDeduction;
use App\Models\User;
use App\Models\LeavePolicy;
use App\Models\InstituteBasicDetails;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use App\Models\EmployeeApprovalChain;
use App\Models\DepartmentCategory;
use App\Models\LeavePolicyAssignment;
use App\Notifications\LeaveRequestNotification;
use App\Notifications\LeaveApprovalNotification;
use App\Notifications\LeaveAssignedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use App\Models\InstituteNotificationSetting;
use Illuminate\Support\Facades\Mail;

class LeaveController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;

    // Show apply leave form with context
    public function showApplyLeaveForm()
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
            return redirect()->back()->with('error', 'Employee record not found for this institute/branch.');
        }
        
        // Get employee's leave balances
        $individualBalances = $this->getEmployeeLeaveBalances($employee, $context);
        
        return view('instituteAdmin.EmployeeFiles.applyleaves', compact(
            'individualBalances', 
            'employee'
        ));
    }
    
    private function getEmployeeLeaveBalances($employee, $context)
    {
        $currentSession = $this->getCurrentAcademicSession();
        $mergedBalances = collect();
        
        // 1. Get individual assignments for this employee
        $individualBalances = EmployeeLeaveBalance::where('employee_id', $employee->employee_id)
            ->where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->where('session_year', $currentSession)
            ->get();
        
        // 2. Get department-level assignments for this employee's department
        $departmentBalances = collect();
        if ($employee->department_id) {
            $departmentBalances = EmployeeLeaveBalance::whereNull('employee_id')
                ->where('department_id', $employee->department_id)
                ->where('institute_id', $context['institute_id'])
                ->when($context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                })
                ->where('assignment_type', 'department')
                ->where('session_year', $currentSession)
                ->get();
        }
        
        // 3. Merge balances: Individual takes precedence over Department
        // First, add all department balances
        foreach ($departmentBalances as $deptBalance) {
            $mergedBalances->put($deptBalance->leave_type, (object)[
                'employee_id' => $employee->employee_id,
                'leave_type' => $deptBalance->leave_type,
                'total_allocated' => $deptBalance->total_allocated,
                'remaining' => $deptBalance->remaining,
                'is_from_department' => true,
                'assignment_source' => 'department'
            ]);
        }
        
        // Then override with individual balances (individual takes precedence)
        foreach ($individualBalances as $indBalance) {
            $mergedBalances->put($indBalance->leave_type, (object)[
                'employee_id' => $employee->employee_id,
                'leave_type' => $indBalance->leave_type,
                'total_allocated' => $indBalance->total_allocated,
                'remaining' => $indBalance->remaining,
                'is_from_department' => false,
                'assignment_source' => 'individual'
            ]);
        }
        
        // Filter out balances with zero or negative remaining
        $mergedBalances = $mergedBalances->filter(function($balance) {
            return $balance->remaining > 0;
        });
        
        return $mergedBalances->values();
    }

    public function applyLeave(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }
        
        $userId = auth()->id();
        $employee = EmployeeDetails::where('user_id', $userId)
            ->where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->first();
            
        if (!$employee) {
            return back()->with('error', 'Employee record not found. Please contact HR.');
        }
        
        // Get employee's shift to determine weekly off days
        $date = Carbon::parse($request->start_date);
        $shiftData = $employee->resolveEffectiveShift($date);
        $shift = $shiftData['shift'] ?? null;
        
        // Get weekly off days from shift
        $weeklyOffDays = $this->getWeeklyOffDaysFromShift($shift);
        
        // Validate request
        $validated = $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'reason' => 'nullable',
            'leave_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);
        
        $startDate = Carbon::parse($request->start_date);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : $startDate;
        
        // Check if selected dates are weekly off - WARNING ONLY, NOT BLOCKING
        $weeklyOffWarning = $this->checkWeeklyOffDates($startDate, $endDate, $weeklyOffDays);
        
        // Check for overlapping leaves
        if ($this->hasOverlappingLeaves($employee->employee_id, $startDate, $endDate, $context)) {
            return back()->with('error', 'You already have a leave application for the selected date(s).');
        }
        
        // Calculate leave days (excluding weekly off days based on shift)
        $calculatedDays = $this->calculateLeaveDaysWithShift($startDate, $endDate, $weeklyOffDays);
        
        // Get the leave balance
        $balance = $this->getEmployeeLeaveBalance($employee->employee_id, $request->leave_type, $context);
        
        if (!$balance) {
            return back()->with('error', 'No leave quota assigned for ' . $request->leave_type);
        }
        
        // Check if enough balance (only for working days)
        if ($calculatedDays['total_days'] > $balance->remaining) {
            return back()->with('error', 
                'Insufficient leave balance. Required: ' . number_format($calculatedDays['total_days'], 2) . 
                ' working days, Available: ' . number_format($balance->remaining, 2) . ' days.');
        }
        
        // Handle file upload
        $documentPath = null;
        if ($request->hasFile('leave_document')) {
            $documentPath = $this->handleDocumentUpload($request);
        }
        
        try {
            DB::beginTransaction();
            
            // Create leave record
            $leave = EmployeeLeave::create([
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['branch_id'],
                'employee_id' => $employee->employee_id,
                'employee_name' => $employee->name,
                'department_id' => $employee->department_id,
                'leave_type' => $request->leave_type,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'leave_duration_type' => 'Full Day',
                'total_days' => $calculatedDays['total_days'],
                'total_hours' => $calculatedDays['total_hours'],
                'days_to_deduct' => $calculatedDays['total_days'],
                'reason' => $request->reason,
                'leave_document' => $documentPath,
                'final_status' => 'Pending',
                'applied_by' => $userId,
                'applied_date' => now(),
                'session_year' => $this->getCurrentAcademicSession(),
            ]);
            
            // Get approvers
            $approvers = $this->getLeaveApprovers($employee);
            
            if (empty($approvers)) {
                DB::commit();
                $message = 'Leave applied successfully! However, no approvers are configured. Please contact HR.';
                if ($weeklyOffWarning) {
                    $message .= ' ' . $weeklyOffWarning;
                }
                return redirect()->back()->with('warning', $message);
            }
            
            // Create approval records with institute_id and branch_id
            foreach ($approvers as $index => $approver) {
                LeaveApproval::create([
                    'institute_id' => $context['institute_id'],      // ADD THIS
                    'branch_id' => $context['branch_id'],            // ADD THIS
                    'leave_id' => $leave->id,
                    'approval_step_id' => $index + 1,
                    'approver_id' => $approver['user_id'],
                    'approver_name' => $approver['name'],
                    'approver_role' => $approver['role'],
                    'status' => 'Pending',
                    'step_number' => $index + 1,
                    'total_steps' => count($approvers),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            DB::commit();
            
            // Send notifications to approvers
            if (!empty($approvers)) {
                $approverUserIds = array_column($approvers, 'user_id');
                $approverUsers = User::whereIn('id', $approverUserIds)->get();
                
                foreach ($approverUsers as $approverUser) {
                    try {
                        // Send database notification
                        $approverUser->notify(new LeaveRequestNotification($leave, 'applied'));
                        
                        // ✅ Send EMAIL notification to approver
                        $approverEmailData = [
                            'user_id' => $approverUser->id,
                            'name' => $approverUser->name,
                            'email' => $approverUser->email,
                            'role' => 'Approver'
                        ];
                        $this->sendLeaveRequestToApproverEmail($leave, $approverEmailData, $context);
                        
                    } catch (\Exception $e) {
                        \Log::warning('Failed to send notification to approver: ' . $e->getMessage());
                    }
                }
            }
            
            $successMessage = '✅ Leave applied successfully! Pending approval from ' . count($approvers) . ' approver(s).';
            if ($weeklyOffWarning) {
                $successMessage .= ' ' . $weeklyOffWarning;
            }
            
            return redirect()->back()->with('success', $successMessage);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to apply leave. Please try again. Error: ' . $e->getMessage());
        }
    }

     /**
     * Get weekly off days from employee's shift
     */
    private function getWeeklyOffDaysFromShift($shift)
    {
        if (!$shift) {
            return [7]; // Default Sunday as weekly off
        }
        
        $weeklyOffDays = $shift->weekly_off_days;
        if (is_string($weeklyOffDays)) {
            $weeklyOffDays = json_decode($weeklyOffDays, true);
        }
        
        if (!is_array($weeklyOffDays) || empty($weeklyOffDays)) {
            return [7];
        }
        
        // Convert day names to numbers (Monday=1, Sunday=7)
        $dayMap = [
            'Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4,
            'Friday' => 5, 'Saturday' => 6, 'Sunday' => 7
        ];
        
        $numericDays = [];
        foreach ($weeklyOffDays as $day) {
            if (is_numeric($day)) {
                $numericDays[] = (int)$day;
            } elseif (isset($dayMap[$day])) {
                $numericDays[] = $dayMap[$day];
            } elseif (isset($dayMap[ucfirst($day)])) {
                $numericDays[] = $dayMap[ucfirst($day)];
            }
        }
        
        return array_unique($numericDays) ?: [7];
    }

    /**
     * Check if selected dates include weekly off days - NON-BLOCKING warning only
     */
    private function checkWeeklyOffDates($startDate, $endDate, $weeklyOffDays)
    {
        $currentDate = $startDate->copy();
        $weeklyOffDates = [];
        
        while ($currentDate->lte($endDate)) {
            $dayOfWeek = $currentDate->dayOfWeekIso;
            if (in_array($dayOfWeek, $weeklyOffDays)) {
                $weeklyOffDates[] = $currentDate->format('d M Y');
            }
            $currentDate->addDay();
        }
        
        if (!empty($weeklyOffDates)) {
            // This is just a warning - NOT blocking the leave application
            return 'ℹ️ Note: The following dates are weekly offs: ' . implode(', ', $weeklyOffDates) . 
                '. These dates will not be counted as leave days.';
        }
        
        return null;
    }

    /**
     * Calculate leave days excluding weekly off days based on employee's shift
     */
    private function calculateLeaveDaysWithShift($startDate, $endDate, $weeklyOffDays)
    {
        $days = 0;
        $currentDate = $startDate->copy();
        $weekendDates = [];
        
        while ($currentDate->lte($endDate)) {
            $dayOfWeek = $currentDate->dayOfWeekIso; // Monday=1, Sunday=7
            
            // Only count if it's NOT a weekly off day
            if (!in_array($dayOfWeek, $weeklyOffDays)) {
                $days++;
            } else {
                $weekendDates[] = $currentDate->format('d M Y');
            }
            $currentDate->addDay();
        }
        
        $hours = $days * 8;
        
        return [
            'total_days' => $days,
            'total_hours' => $hours,
            'weekend_dates' => $weekendDates
        ];
    }

    private function getLeaveApprovers($employee)
    {
        $approvers = [];
        $instituteId = $employee->institute_id;
        $departmentId = $employee->department_id;
        $branchId = $employee->branch_id ?? null;
        $context = $this->getInstituteBranchContext();
        
        // 1. Find Department HOD/Manager (first priority)
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
        
        // 2. Find Institute Admin - SIMPLIFIED - NO employee() relation
        $adminUsers = User::whereHas('roles', function($query) {
                $query->whereIn('name', ['admin', 'superadmin', 'institute_admin']);
            })
            ->where(function($query) use ($instituteId, $branchId) {
                // Check if user has institute_id in users table
                $query->where('institute_id', $instituteId);
                if ($branchId) {
                    $query->where('branch_id', $branchId);
                }
            })
            ->where('id', '!=', $employee->user_id)
            ->get();
        
        foreach ($adminUsers as $adminUser) {
            $approvers[] = [
                'user_id' => $adminUser->id,
                'name' => $adminUser->name,
                'role' => 'Institute Admin',
                'employee_id' => null,
                'type' => 'admin'
            ];
        }
        
        // 3. Fallback: If no approvers found, find any admin user
        if (empty($approvers)) {
            $fallbackAdmin = User::whereHas('roles', function($query) {
                    $query->whereIn('name', ['admin', 'superadmin', 'institute_admin']);
                })
                ->where('id', '!=', $employee->user_id)
                ->first();
            
            if ($fallbackAdmin) {
                $approvers[] = [
                    'user_id' => $fallbackAdmin->id,
                    'name' => $fallbackAdmin->name,
                    'role' => 'Institute Admin',
                    'employee_id' => null,
                    'type' => 'admin'
                ];
            }
        }
        
        // Remove duplicate approvers
        $approvers = collect($approvers)->unique('user_id')->values()->toArray();
        
        return $approvers;
    }

    // Helper method to get employee leave balance (check individual first, then department)
    private function getEmployeeLeaveBalance($employeeId, $leaveType, $context)
    {
        $currentSession = $this->getCurrentAcademicSession();
        
        // First check for individual assignment
        $individualBalance = EmployeeLeaveBalance::where('employee_id', $employeeId)
            ->where('leave_type', $leaveType)
            ->where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->where('session_year', $currentSession)
            ->first();
        
        if ($individualBalance && $individualBalance->remaining > 0) {
            return $individualBalance;
        }
        
        // If no individual balance, get employee details to check department
        $employee = EmployeeDetails::where('employee_id', $employeeId)->first();
        if (!$employee || !$employee->department_id) {
            return null;
        }
        
        // Check for department assignment
        $departmentBalance = EmployeeLeaveBalance::whereNull('employee_id')
            ->where('department_id', $employee->department_id)
            ->where('leave_type', $leaveType)
            ->where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->where('assignment_type', 'department')
            ->where('session_year', $currentSession)
            ->first();
        
        if ($departmentBalance && $departmentBalance->remaining > 0) {
            // Return department balance (will be used for deduction later)
            // We don't create personal copy here to avoid duplication
            return $departmentBalance;
        }
        
        return null;
    }


    // Simple leave calculation (no policy conversions)
    private function calculateSimpleLeaveDays($startDate, $endDate, $durationType)
    {
        switch ($durationType) {
            case 'Half Day':
                $days = 0.5;
                $hours = 4;
                break;
                
            case 'Short Leave':
                $days = 0.25;
                $hours = 2;
                break;
                
            default: // Full Day
                // Calculate actual working days (excluding weekends)
                $days = 0;
                $currentDate = $startDate->copy();
                
                while ($currentDate->lte($endDate)) {
                    if (!$currentDate->isWeekend()) {
                        $days++;
                    }
                    $currentDate->addDay();
                }
                
                $hours = $days * 8;
        }
        
        return [
            'total_days' => $days,
            'total_hours' => $hours
        ];
    }

  

    private function processSimpleLeaveApproval($leave, $employee, $balance, $daysToDeduct)
    {
        $context = $this->getInstituteBranchContext();
        // Find department HOD/Manager
        $departmentHod = EmployeeDetails::where('department_id', $employee->department_id)
            ->where('institute_id', $employee->institute_id)
            ->whereIn('assigned_role', ['manager', 'hod', 'head'])
            ->where('user_id', '!=', $employee->user_id)
            ->first();
        
        // Find Admin as fallback
        $adminUser = User::whereHas('roles', function($query) {
                $query->whereIn('name', ['admin']);
            })
            ->first();
        
        $approvers = [];
        
        // Add HOD as first approver if exists
        if ($departmentHod) {
            $approvers[] = [
                'user_id' => $departmentHod->user_id,
                'name' => $departmentHod->name,
                'role' => 'Department ' . ucfirst($departmentHod->assigned_role),
                'employee_id' => $departmentHod->employee_id
            ];
        }
        
        // Add Admin as approver (either primary if no HOD, or secondary)
        if ($adminUser) {
            $approvers[] = [
                'user_id' => $adminUser->id,
                'name' => $adminUser->name,
                'role' => 'Institute Admin',
                'employee_id' => null
            ];
        }
        
        if (empty($approvers)) {
            // Auto-approve if no approvers configured
            return $this->autoApproveLeave($leave, $balance, $daysToDeduct);
        }
        
        // Create approval records with institute_id and branch_id
        foreach ($approvers as $index => $approver) {
            LeaveApproval::create([
                'institute_id' => $context['institute_id'],      // ADD THIS
                'branch_id' => $context['branch_id'],            // ADD THIS
                'leave_id' => $leave->id,
                'approval_step_id' => $index + 1,
                'approver_id' => $approver['user_id'],
                'approver_name' => $approver['name'],
                'approver_role' => $approver['role'],
                'status' => 'Pending',
                'step_number' => $index + 1,
                'total_steps' => count($approvers),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $firstApprover = $approvers[0];
        return redirect()->back()->with('success', 
            '✅ Leave applied successfully! Pending approval from ' . 
            $firstApprover['name'] . ' (' . $firstApprover['role'] . ')');
    }

    private function hasOverlappingLeaves($employeeId, $startDate, $endDate, $context)
    {
        return EmployeeLeave::where('employee_id', $employeeId)
            ->where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->whereIn('final_status', ['Pending', 'Approved'])
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();
    }

    private function getCurrentAcademicSession()
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        
        // Academic session: April to March
        if ($currentMonth >= 4) {
            return $currentYear . '-' . ($currentYear + 1);
        } else {
            return ($currentYear - 1) . '-' . $currentYear;
        }
    }
     
    private function handleDocumentUpload(Request $request)
    {
        if (!$request->hasFile('leave_document')) {
            return null;
        }
        
        $file = $request->file('leave_document');
        $filename = 'leave_doc_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        $path = $file->storeAs('public/uploads/leave_documents', $filename);
        
        return 'uploads/leave_documents/' . $filename;
    }

    private function processLeaveApproval($leave, $employee, $balance, $daysToDeduct)
    {
        $context = $this->getInstituteBranchContext();
        $approvers = $this->getDepartmentApprovers($employee);
        
        if (empty($approvers)) {
            // Auto-approve and deduct balance
            return $this->autoApproveLeave($leave, $balance, $daysToDeduct);
        }
        
        // Create approval records
        foreach ($approvers as $index => $approver) {
            LeaveApproval::create([
                'institute_id'=>$context['institute_id'],
                'leave_id' => $leave->id,
                'approval_step_id' => $index + 1,
                'approver_id' => $approver['user_id'],
                'approver_name' => $approver['name'],
                'approver_role' => $approver['role'],
                'status' => 'Pending',
                'step_number' => $index + 1,
                'total_steps' => count($approvers),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $firstApprover = $approvers[0];
        return redirect()->back()->with('success', 
            '✅ Leave applied successfully! Pending approval from ' . 
            $firstApprover['name'] . ' (' . $firstApprover['role'] . ')');
    }
    
    // Calculate leave days based on policy
    private function calculateLeaveDays($startDate, $endDate)
    {
        // Calculate actual working days (excluding weekends - Sunday only as fallback)
        $days = 0;
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            if (!$currentDate->isSunday()) { // Fallback to Sunday check
                $days++;
            }
            $currentDate->addDay();
        }
        
        $hours = $days * 8;
        
        return [
            'total_days' => $days,
            'total_hours' => $hours
        ];
    }

    private function getDepartmentApprovers($employee)
    {
        $approvers = [];
        $instituteId = $employee->institute_id;
        $departmentId = $employee->department_id;
        
        // 1. Find Department Manager in the same department
        $manager = EmployeeDetails::where('institute_id', $instituteId)
            ->where('department_id', $departmentId)
            ->where('user_id', '!=', $employee->user_id) // Don't assign to self
            ->where('assigned_role', 'manager') // Using assigned_role column
            ->first();
        
        if ($manager) {
            $approvers[] = [
                'user_id' => $manager->user_id,
                'name' => $manager->first_name . ' ' . $manager->last_name,
                'role' => 'Department Manager',
                'employee_id' => $manager->employee_id
            ];
        }
        
        // 2. Find Institute Admin - look in Users table with 'admin' role
        $adminUser = User::whereHas('roles', function($query) {
                $query->whereIn('name', ['admin', 'superadmin', 'administrator', 'institute_admin']);
            })
            ->where('id', '!=', $employee->user_id) // Don't assign to self
            ->first();
       
        if ($adminUser) {
            $approvers[] = [
                'user_id' => $adminUser->id,
                'name' => $adminUser->name,
                'role' => 'Institute Admin',
                'employee_id' => null 
            ];
        }
        
        // 3. Fallback: If no manager or admin found, find any supervisor/lead
        if (empty($approvers)) {
            $supervisor = EmployeeDetails::where('institute_id', $instituteId)
                ->where('department_id', $departmentId)
                ->where('user_id', '!=', $employee->user_id)
                ->whereIn('assigned_role', ['supervisor', 'lead', 'head'])
                ->first();
            
            if ($supervisor) {
                $approvers[] = [
                    'user_id' => $supervisor->user_id,
                    'name' => $supervisor->first_name . ' ' . $supervisor->last_name,
                    'role' => 'Department ' . ucfirst($supervisor->assigned_role),
                    'employee_id' => $supervisor->employee_id
                ];
            }
        }
        
        return $approvers;
    }
    
    // Get default policy rules
    private function getDefaultPolicyRules()
    {
        return [
            'full_day_hours' => 8,
            'half_day_hours' => 4,
            'short_leave_hours' => 2,
            'conversion' => [
                'half_day_to_full_day' => 2,
                'short_leave_to_half_day' => 2,
                'short_leave_to_full_day' => 4
            ]
        ];
    }
    
    

    private function autoApproveLeave($leave, $daysToDeduct, $balance)
    {
        $context = $this->getInstituteBranchContext();
        try {
            // Update leave balance
            $balance->used += $daysToDeduct;
            $balance->remaining -= $daysToDeduct;
            
            // Also update detailed counts if columns exist
            if (isset($balance->used_full_days) && $leave->leave_duration_type === 'Full Day') {
                $balance->used_full_days += $leave->total_days;
            } elseif (isset($balance->used_half_days) && $leave->leave_duration_type === 'Half Day') {
                $balance->used_half_days += 1; // Each half day counts as 1
            } elseif (isset($balance->used_short_leaves) && $leave->leave_duration_type === 'Short Leave') {
                $balance->used_short_leaves += 1; // Each short leave counts as 1
            }
            
            $balance->save();
            
            // Update leave status
            $leave->final_status = 'Approved';
            $leave->approved_by = auth()->id();
            $leave->approved_date = now();
            $leave->save();
            
            // Create approval record with institute_id and branch_id
            LeaveApproval::create([
                'institute_id' => $context['institute_id'],      // ADD THIS
                'branch_id' => $context['branch_id'],            // ADD THIS
                'leave_id' => $leave->id,
                'approval_step_id' => 1,
                'approver_id' => auth()->id(),
                'approver_name' => 'System',
                'status' => 'Approved',
                'approved_date' => now(),
                'comments' => 'Auto-approved (No approvers configured)',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            return redirect()->back()->with('success', '✅ Leave applied and auto-approved successfully!');
            
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function assignLeaveForm()
    {
        $context = $this->getInstituteBranchContext();
        
        // Get ALL leave deductions (both default and custom) - excluding Absent
        $leaveDeductions = LeaveDeduction::forInstitute($context['institute_id'], $context['branch_id'])
            ->where('leave_type', '!=', 'Absent')
            ->where('is_active', true)
            ->orderBy('is_custom', 'asc') // Default first, then custom
            ->orderBy('leave_type', 'asc')
            ->get();
        
        // Check if all required deduction rules are configured
        $requiredTypes = ['Sick Leave', 'Casual Leave', 'Earned Leave', 'Unpaid Leave'];
        $configuredTypes = $leaveDeductions->pluck('leave_type')->toArray();
        $missingTypes = array_diff($requiredTypes, $configuredTypes);
        $hasMissingDeductions = !empty($missingTypes);
        $hasAnyDeductions = $leaveDeductions->count() > 0;
        
        // Get department categories
        $departmentCategories = DepartmentCategory::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->get();
        
        return view('instituteAdmin.EmployeeFiles.assignLeaves', compact(
            'leaveDeductions',
            'departmentCategories',
            'hasMissingDeductions',
            'missingTypes',
            'hasAnyDeductions'
        ));
    }
    
    public function assignLeaveStore(Request $request)
    {
        try {
            $request->validate([
                'department_id' => 'required',
                'department_category_id' => 'required',
                'session_year' => 'required',
                'leave_types' => 'required|array|min:1',
                'assignment_method' => 'required|in:department,multiple,single'
            ]);
            
            $context = $this->getInstituteBranchContext();
            
            // Validate each leave type has required fields
            foreach ($request->leave_types as $index => $leaveData) {
                if (empty($leaveData['type'])) {
                    return redirect()->back()->with('error', "Leave type #" . ($index + 1) . " has no type selected.");
                }
                if (!isset($leaveData['full_days']) || $leaveData['full_days'] === '') {
                    return redirect()->back()->with('error', "Leave type #" . ($index + 1) . " has no days entered.");
                }
                if (floatval($leaveData['full_days']) <= 0) {
                    return redirect()->back()->with('error', "Leave type #" . ($index + 1) . " has zero or negative days. Please enter a valid number.");
                }
            }
            
            // Get department name
            $department = Departments::where('department_id', $request->department_id)->first();
            if (!$department) {
                return redirect()->back()->with('error', 'Department not found.');
            }
            
            DB::beginTransaction();
            
            $successCount = 0;
            $failedCount = 0;
            
            // Initialize arrays for notifications
            $assignedEmployees = [];
            $employeeLeaveData = [];
            
            if ($request->assignment_method === 'department') {
                
                foreach ($request->leave_types as $leaveData) {
                    if (empty($leaveData['type'])) {
                        continue;
                    }
                    
                    $leaveType = $leaveData['type'];
                    $leaveTypeId = $leaveData['leave_type_id'] ?? null; 
                    $isCustom = $leaveData['is_custom'] ?? 0;
                    $rawFullDays = floatval($leaveData['full_days']);
                    
                    if ($rawFullDays <= 0) {
                        continue;
                    }
                    
                    // Calculate effective full days based on leave type
                    $effectiveFullDays = $this->calculateEffectiveFullDays($leaveType, $rawFullDays);
                    
                    // Check if department assignment already exists
                    $existingDepartmentAssignment = EmployeeLeaveBalance::whereNull('employee_id')
                        ->where('department_id', $request->department_id)
                        ->where('leave_type', $leaveType)
                        ->where('institute_id', $context['institute_id'])
                        ->when($context['branch_id'], function($query) use ($context) {
                            return $query->where('branch_id', $context['branch_id']);
                        })
                        ->where('session_year', $request->session_year)
                        ->where('assignment_type', 'department')
                        ->first();
                    
                    try {
                        $departmentData = [
                            'institute_id' => $context['institute_id'],
                            'branch_id' => $context['branch_id'],
                            'employee_id' => null,
                            'department_id' => $request->department_id,
                            'department_name' => $department->department,
                            'department_category_id' => $request->department_category_id,
                            'assignment_type' => 'department',
                            'leave_type' => $leaveType,
                            'leave_type_id' => $leaveTypeId, 
                            'is_custom' => $isCustom, 
                            'session_year' => $request->session_year,
                            'total_allocated' => $effectiveFullDays,
                            'remaining' => $effectiveFullDays,
                            'is_department_assignment' => true,
                            'updated_at' => now(),
                        ];
                        
                        if ($existingDepartmentAssignment) {
                            $existingDepartmentAssignment->update($departmentData);
                        } else {
                            $departmentData['created_at'] = now();
                            EmployeeLeaveBalance::create($departmentData);
                        }
                        $successCount++;
                        
                    } catch (\Exception $e) {
                        $failedCount++;
                    }
                }
                
                // Get all employees in the department for notifications
                $employees = EmployeeDetails::where('department_id', $request->department_id)
                    ->where('institute_id', $context['institute_id'])
                    ->when($context['branch_id'], function($query) use ($context) {
                        return $query->where('branch_id', $context['branch_id']);
                    })
                    ->where('status', 'active')
                    ->get();
                
                foreach ($employees as $emp) {
                    $assignedEmployees[$emp->employee_id] = $emp;
                    $employeeLeaveData[$emp->employee_id] = [];
                    
                    foreach ($request->leave_types as $leaveData) {
                        if (empty($leaveData['type'])) continue;
                        
                        $leaveType = $leaveData['type'];
                        $rawFullDays = floatval($leaveData['full_days']);
                        
                        if ($rawFullDays <= 0) continue;
                        
                        $effectiveFullDays = $this->calculateEffectiveFullDays($leaveType, $rawFullDays);
                        
                        $employeeLeaveData[$emp->employee_id][] = [
                            'type' => $leaveType,
                            'raw_days' => $rawFullDays,
                            'effective_days' => $effectiveFullDays,
                            'category' => $this->getLeaveCategory($leaveType)
                        ];
                    }
                }
                
            } else {
                // MULTIPLE or SINGLE ASSIGNMENT
               
                $employeeIds = [];
                
                if ($request->assignment_method === 'multiple') {
                    $employeeIds = $request->employee_ids ?? [];
                    
                    if (empty($employeeIds)) {
                        DB::rollBack();
                        return redirect()->back()->with('error', 'Please select at least one employee.');
                    }
                    
                } elseif ($request->assignment_method === 'single') {
                    $employeeIds = $request->employee_ids ?? [];
                    
                    if (empty($employeeIds)) {
                        DB::rollBack();
                        return redirect()->back()->with('error', 'Please select an employee.');
                    }
                }
                
                foreach ($employeeIds as $empId) {
                    $employee = EmployeeDetails::where('employee_id', $empId)->first();
                    
                    if ($employee) {
                        $assignedEmployees[$empId] = $employee;
                        $employeeLeaveData[$empId] = [];
                    }
                    
                    foreach ($request->leave_types as $leaveData) {
                        if (empty($leaveData['type'])) {
                            continue;
                        }
                        
                        $leaveType = $leaveData['type'];
                        $leaveTypeId = $leaveData['leave_type_id'] ?? null;
                        $isCustom = $leaveData['is_custom'] ?? 0;
                        $rawFullDays = floatval($leaveData['full_days']);
                        
                        if ($rawFullDays <= 0) {
                            continue;
                        }
                        
                        $effectiveFullDays = $this->calculateEffectiveFullDays($leaveType, $rawFullDays);
                        
                        if ($employee) {
                            $employeeLeaveData[$empId][] = [
                                'type' => $leaveType,
                                'raw_days' => $rawFullDays,
                                'effective_days' => $effectiveFullDays,
                                'category' => $this->getLeaveCategory($leaveType)
                            ];
                        }
                        
                        $existingAllocation = EmployeeLeaveBalance::where('employee_id', $empId)
                            ->where('leave_type', $leaveType)
                            ->where('institute_id', $context['institute_id'])
                            ->when($context['branch_id'], function($query) use ($context) {
                                return $query->where('branch_id', $context['branch_id']);
                            })
                            ->where('session_year', $request->session_year)
                            ->first();
                        
                        try {
                            $employeeData = [
                                'institute_id' => $context['institute_id'],
                                'branch_id' => $context['branch_id'],
                                'employee_id' => $empId,
                                'department_id' => $request->department_id,
                                'department_name' => $department->department,
                                'department_category_id' => $request->department_category_id,
                                'assignment_type' => $request->assignment_method,
                                'leave_type' => $leaveType,
                                'leave_type_id' => $leaveTypeId, 
                                'is_custom' => $isCustom,
                                'session_year' => $request->session_year,
                                'total_allocated' => $effectiveFullDays,
                                'remaining' => $effectiveFullDays,
                                'is_department_assignment' => false,
                                'updated_at' => now(),
                            ];
                            
                            if ($existingAllocation) {
                                $existingAllocation->update($employeeData);
                            } else {
                                $employeeData['created_at'] = now();
                                EmployeeLeaveBalance::create($employeeData);
                            }
                            $successCount++;
                            
                        } catch (\Exception $e) {
                            $failedCount++;
                        }
                    }
                }
            }
            DB::commit();
            // Send notifications
            $notificationCount = 0;
            foreach ($assignedEmployees as $empId => $employee) {
                if (isset($employeeLeaveData[$empId]) && !empty($employeeLeaveData[$empId])) {
                    $user = User::find($employee->user_id);
                    
                    if ($user) {
                        try {
                            // Send database notification
                            $user->notify(new LeaveAssignedNotification(
                                $employee, 
                                $employeeLeaveData[$empId], 
                                $request->assignment_method,
                                $request->session_year
                            ));
                            
                            // ✅ Send EMAIL notification
                            $this->sendLeaveQuotaEmail(
                                $employee, 
                                $employeeLeaveData[$empId], 
                                $request->assignment_method, 
                                $request->session_year, 
                                $context
                            );
                            
                            $notificationCount++;
                        } catch (\Exception $e) {
                            \Log::warning('Failed to send notification to employee: ' . $empId, ['error' => $e->getMessage()]);
                        }
                    }
                }
            }
            
            if ($successCount > 0) {
                $message = "Leave Quotas Assigned Successfully! ";
                $message .= "{$successCount} records processed. ";
                $message .= "Notifications sent to {$notificationCount} employees.";
                
                return redirect()->back()->with('success', $message);
            } else {
                return redirect()->back()->with('error', 'Failed to assign leave quotas. No records were saved.');
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            DB::rollBack();  
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    // Helper method to calculate effective full days
    private function calculateEffectiveFullDays($leaveType, $rawCount)
    {
        $leaveType = strtolower($leaveType);
        
        switch ($leaveType) {
            case 'half_days':
                // 2 half days = 1 full day
                return $rawCount / 2;
                
            case 'short_leave':
                // 4 short leaves = 1 full day
                return $rawCount / 4;
                
            default:
                // Regular leave types (Sick, Casual, etc.)
                return $rawCount;
        }
    }

    // Helper method to get leave category
    private function getLeaveCategory($leaveType)
    {
        $leaveType = strtolower($leaveType);
        
        if (in_array($leaveType, ['sick', 'casual', 'earned', 'unpaid', 'maternity'])) {
            return 'full_day';
        } elseif ($leaveType === 'half_days') {
            return 'half_day';
        } elseif ($leaveType === 'short_leave') {
            return 'short_leave';
        } else {
            return 'custom';
        }
    }

    private function validateLeaveAllocation($employeeId, $leaveType, $allocationType, $sessionYear, $context)
    {
        $currentYear = date('Y');
        $currentMonth = date('m');
        
        // Check monthly allocation limits
        if ($allocationType === 'monthly') {
            // Check if already allocated for current month
            $existingAllocation = EmployeeLeaveBalance::where('employee_id', $employeeId)
                ->where('leave_type', $leaveType)
                ->where('institute_id', $context['institute_id'])
                ->when($context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                })
                ->when(Schema::hasColumn('employee_leave_balances', 'allocation_period'), function($query) {
                    return $query->where('allocation_period', 'monthly');
                })
                ->when(Schema::hasColumn('employee_leave_balances', 'last_allocated_date'), function($query) use ($currentMonth, $currentYear) {
                    return $query->whereMonth('last_allocated_date', $currentMonth)
                        ->whereYear('last_allocated_date', $currentYear);
                }, function($query) {
                    // If no last_allocated_date column, use created_at
                    return $query->whereMonth('created_at', date('m'))
                        ->whereYear('created_at', date('Y'));
                })
                ->first();
                
            if ($existingAllocation) {
                return [
                    'success' => false,
                    'message' => "Monthly quota for {$leaveType} already assigned for current month."
                ];
            }
        }
        
        // Check quarterly allocation limits
        if ($allocationType === 'quarterly') {
            $currentQuarter = ceil($currentMonth / 3);
            
            $existingAllocation = EmployeeLeaveBalance::where('employee_id', $employeeId)
                ->where('leave_type', $leaveType)
                ->where('institute_id', $context['institute_id'])
                ->when($context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                })
                ->when(Schema::hasColumn('employee_leave_balances', 'allocation_period'), function($query) {
                    return $query->where('allocation_period', 'quarterly');
                })
                ->when(Schema::hasColumn('employee_leave_balances', 'last_allocated_date'), function($query) use ($currentQuarter, $currentYear) {
                    return $query->whereRaw('QUARTER(last_allocated_date) = ?', [$currentQuarter])
                        ->whereYear('last_allocated_date', $currentYear);
                }, function($query) use ($currentQuarter) {
                    // If no last_allocated_date column, use created_at
                    return $query->whereRaw('QUARTER(created_at) = ?', [$currentQuarter])
                        ->whereYear('created_at', date('Y'));
                })
                ->first();
                
            if ($existingAllocation) {
                return [
                    'success' => false,
                    'message' => "Quarterly quota for {$leaveType} already assigned for current quarter."
                ];
            }
        }
        
        // Check yearly allocation limits
        if ($allocationType === 'yearly') {
            $existingAllocation = EmployeeLeaveBalance::where('employee_id', $employeeId)
                ->where('leave_type', $leaveType)
                ->where('institute_id', $context['institute_id'])
                ->when($context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                })
                ->when(Schema::hasColumn('employee_leave_balances', 'allocation_period'), function($query) {
                    return $query->where('allocation_period', 'yearly');
                })
                ->where('session_year', $sessionYear)
                ->first();
                
            if ($existingAllocation) {
                return [
                    'success' => false,
                    'message' => "Yearly quota for {$leaveType} already assigned for session {$sessionYear}."
                ];
            }
        }
        
        return ['success' => true];
    }

    // Calculate total leave days with policy conversions
    private function calculateTotalLeaveDays($fullDays, $halfDays, $shortLeaves, $policyId = null)
    {
        // Get policy rules
        $policyRules = $this->getPolicyRules($policyId);
        
        // Convert half days to full days based on policy
        $halfToFull = $policyRules['conversion']['half_day_to_full_day'] ?? 2;
        $fullDaysFromHalf = $halfDays / $halfToFull;
        
        // Convert short leaves to full days based on policy
        $shortToFull = $policyRules['conversion']['short_leave_to_full_day'] ?? 4;
        $fullDaysFromShort = $shortLeaves / $shortToFull;
        
        // Total days (including conversions)
        return $fullDays + $fullDaysFromHalf + $fullDaysFromShort;
    }

    // Calculate total leave hours
    private function calculateTotalLeaveHours($fullDays, $halfDays, $shortLeaves, $policyId = null)
    {
        // Get policy rules
        $policyRules = $this->getPolicyRules($policyId);
        
        $fullDayHours = $policyRules['full_day_hours'] ?? 8;
        $halfDayHours = $policyRules['half_day_hours'] ?? 4;
        $shortLeaveHours = $policyRules['short_leave_hours'] ?? 2;
        
        return ($fullDays * $fullDayHours) + 
            ($halfDays * $halfDayHours) + 
            ($shortLeaves * $shortLeaveHours);
    }

    private function getApprovalChain($employeeId)
    {
        return EmployeeApprovalChain::where('employee_id', $employeeId)
            ->orderBy('approval_step_id', 'asc')
            ->get();
    }
    
    public function getLeaveApproval(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        $currentUserId = auth()->id();

        // Get employees for datalist with employee code
        $employees = EmployeeDetails::select('employee_id', 'employee_code', 'name')
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->orderBy('name')
            ->get();

        // Get departments for filter using direct query
        $departments = DB::table('departments')
            ->where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->select('department_id', 'department')
            ->get();

        // Get leave types for filter from LeaveDeduction table
        $leaveTypes = LeaveDeduction::forInstitute($context['institute_id'], $context['branch_id'])
            ->where('is_active', true)
            ->where('leave_type', '!=', 'Absent')
            ->pluck('leave_type')
            ->unique()
            ->values()
            ->toArray();
        
        // If no leave types found in LeaveDeduction, get from EmployeeLeave table
        if (empty($leaveTypes)) {
            $leaveTypes = EmployeeLeave::where('institute_id', $context['institute_id'])
                ->when($context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                })
                ->distinct()
                ->pluck('leave_type')
                ->toArray();
        }
        
        // Also add default leave types if still empty
        if (empty($leaveTypes)) {
            $leaveTypes = ['Sick', 'Casual', 'Earned', 'Unpaid', 'Maternity', 'half_days', 'short_leave'];
        }

        // Query approvals with joins instead of relationships
        $query = DB::table('leave_approvals as la')
            ->join('employee_leaves as el', 'la.leave_id', '=', 'el.id')
            ->join('employee_details as ed', 'el.employee_id', '=', 'ed.employee_id')
            ->leftJoin('departments as d', 'ed.department_id', '=', 'd.department_id')
            ->select(
                'la.id',
                'la.approver_id',
                'la.status',
                'la.approver_name',
                'la.approved_date',
                'la.comments',
                'el.employee_name',
                'el.employee_id as leave_employee_id',
                'el.leave_type',
                'el.start_date',
                'el.end_date',
                'el.total_days',
                'el.reason',
                'el.leave_document',
                'ed.employee_code',
                'ed.name as employee_full_name',
                'd.department as department_name'
            )
            ->where('la.approver_id', $currentUserId);

        // Filter by employee (search by name or employee code)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ed.employee_id', $search)
                ->orWhere('ed.employee_code', 'like', "%{$search}%")
                ->orWhere('ed.name', 'like', "%{$search}%");
            });
        }

        // Filter by department
        if ($request->filled('department_id')) {
            $query->where('ed.department_id', $request->department_id);
        }

        // Filter by leave type
        if ($request->filled('leave_type')) {
            $query->where('el.leave_type', $request->leave_type);
        }

        // Filter by date range
        if ($request->filled('date_filter')) {
            $today = Carbon::today();
            
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('el.start_date', $today->toDateString());
                    break;
                    
                case 'tomorrow':
                    $query->whereDate('el.start_date', $today->copy()->addDay()->toDateString());
                    break;
                    
                case 'week':
                    $startOfWeek = $today->copy()->startOfWeek(Carbon::MONDAY);
                    $endOfWeek = $today->copy()->endOfWeek(Carbon::SUNDAY);
                    $query->whereBetween('el.start_date', [$startOfWeek, $endOfWeek]);
                    break;
                    
                case 'next_week':
                    $startOfNextWeek = $today->copy()->addWeek()->startOfWeek(Carbon::MONDAY);
                    $endOfNextWeek = $today->copy()->addWeek()->endOfWeek(Carbon::SUNDAY);
                    $query->whereBetween('el.start_date', [$startOfNextWeek, $endOfNextWeek]);
                    break;
                    
                case 'month':
                    $query->whereMonth('el.start_date', $today->month)
                        ->whereYear('el.start_date', $today->year);
                    break;
                    
                case 'next_month':
                    $nextMonth = $today->copy()->addMonth();
                    $query->whereMonth('el.start_date', $nextMonth->month)
                        ->whereYear('el.start_date', $nextMonth->year);
                    break;
                    
                case 'year':
                    $query->whereYear('el.start_date', $today->year);
                    break;
                    
                case 'custom':
                    if ($request->filled('from_date') && $request->filled('to_date')) {
                        $fromDate = Carbon::parse($request->from_date)->startOfDay();
                        $toDate = Carbon::parse($request->to_date)->endOfDay();
                        $query->whereBetween('el.start_date', [$fromDate, $toDate]);
                    }
                    break;
            }
        }

        // Get results and convert to collection
        $allApprovals = $query->get();

        // Filter by status
        $pending = $allApprovals->where('status', 'Pending');
        $approved = $allApprovals->where('status', 'Approved');
        $rejected = $allApprovals->where('status', 'Rejected');
        
        // Get active tab from request
        $activeTab = $request->get('tab', 'pending');

        return view(
            'instituteAdmin.EmployeeFiles.approvalsleaves',
            compact('pending', 'approved', 'rejected', 'employees', 'departments', 'leaveTypes', 'activeTab')
        );
    }

    public function updateLeaveApproval(Request $request, $id)
    {
        $approval = LeaveApproval::where('id', $id)->first();
        
        if (!$approval) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Approval record not found.'
                ], 404);
            }
            return redirect()->back()->with('error', 'Approval record not found.');
        }
        
        $currentUserId = auth()->id();

        // Validate the current user is the approver and it's pending
        if ($approval->approver_id != $currentUserId || $approval->status != 'Pending') {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to update this approval or it has already been processed.'
                ], 403);
            }
            return redirect()->back()->with('error', 'You are not authorized to update this approval or it has already been processed.');
        }

        DB::beginTransaction();
        try {
            $action = $request->action;
            
            if ($action === 'approve') {
                $approval->status = 'Approved';
                $approval->approved_date = now();
                $approval->comments = $request->comments;
            } elseif ($action === 'reject') {
                $approval->status = 'Rejected';
                $approval->approved_date = now();
                $approval->comments = $request->comments;
            } else {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid action.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Invalid action.');
            }
            
            $approval->save();

            $leave = $approval->leave;
            if ($leave) {
                // Store institute_id and branch_id in leave_approvals
                $approval->institute_id = $leave->institute_id;
                $approval->branch_id = $leave->branch_id;
                $approval->save();
                
                // Get current session year
                $currentSession = $this->getCurrentAcademicSession();
                
                if ($action === 'approve') {
                    // ✅ ANY approver approves = Leave is APPROVED immediately
                    $leave->final_status = 'Approved';
                    $leave->approved_by = $currentUserId;
                    $leave->approved_date = now();
                    $leave->save();
                    
                    // ✅ DEDUCT BALANCE immediately
                    $balance = EmployeeLeaveBalance::where('employee_id', $leave->employee_id)
                        ->where('leave_type', $leave->leave_type)
                        ->where('institute_id', $leave->institute_id)
                        ->when($leave->branch_id, function($query) use ($leave) {
                            return $query->where('branch_id', $leave->branch_id);
                        })
                        ->where('session_year', $currentSession)
                        ->first();
                    
                    // If not found by session_year, try without it
                    if (!$balance) {
                        $balance = EmployeeLeaveBalance::where('employee_id', $leave->employee_id)
                            ->where('leave_type', $leave->leave_type)
                            ->where('institute_id', $leave->institute_id)
                            ->when($leave->branch_id, function($query) use ($leave) {
                                return $query->where('branch_id', $leave->branch_id);
                            })
                            ->first();
                    }
                    
                    if ($balance) {
                        // Log current balance before deduction
                        $oldBalance = $balance->remaining;
                        $oldUsed = $balance->used ?? 0;
                        
                        // Update remaining balance
                        $balance->remaining -= $leave->days_to_deduct;
                        
                        // Update used column - simply add the days to deduct
                        $balance->used = $oldUsed + $leave->days_to_deduct;
                        
                        $balance->save();
                        
                    } else {
                        
                        // Optional: Throw an exception if balance is required
                        // throw new \Exception('Leave balance not found for employee');
                    }
                    
                    // Mark all other pending approvals as "Rejected"
                    $leave->approvals()
                        ->where('status', 'Pending')
                        ->where('id', '!=', $approval->id)
                        ->update([
                            'status' => 'Already Approved',
                            'comments' => 'Leave already approved by ' . $approval->approver_name,
                            'updated_at' => now()
                        ]);
                        
                } elseif ($action === 'reject') {
                    // ✅ ANY approver rejects = Leave is REJECTED immediately
                    $leave->final_status = 'Rejected';
                    $leave->save();
                    
                    // Mark all other pending approvals as "Rejected"
                    $leave->approvals()
                        ->where('status', 'Pending')
                        ->where('id', '!=', $approval->id)
                        ->update([
                            'status' => 'Rejected',
                            'comments' => 'Leave rejected by ' . $approval->approver_name,
                            'updated_at' => now()
                        ]);
                }
            }
            $context = $this->getInstituteBranchContext();
            // ✅ SEND NOTIFICATION TO EMPLOYEE about approval/rejection
            $employeeUser = User::find($leave->employee->user_id);

            if ($employeeUser) {
                $approvedByName = auth()->user()->name;
                
                // Send database notification
                $employeeUser->notify(new LeaveApprovalNotification($leave, $action, $approvedByName));
                
                // ✅ Send EMAIL notification
                $this->sendLeaveStatusEmail($leave, $action, $approvedByName, $request->comments, $context);
            }

            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Leave request ' . $action . 'd successfully!'
                ]);
            }
            
            return redirect()->route('leaves.approvals', ['tab' => 'pending'])
                ->with('success', 'Leave request ' . $action . 'd successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update leave approval: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to update leave approval: ' . $e->getMessage());
        }
    }

    public function viewEmployeeLeaveSummary(Request $request, $employeeId = null)
    {
        $context = $this->getInstituteBranchContext();

        // Check if user has institute access
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        $userId = auth()->id();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // Determine if user is admin/HR
        $isAdmin = auth()->user()->hasRole('admin') || auth()->user()->hasRole('hr');

        // If employeeId is provided AND user is admin
        if ($employeeId && $isAdmin) {
            // Admin viewing specific employee's summary
            $employee = $this->getCommonQuery(EmployeeDetails::class)
                ->where('employee_id', $employeeId)
                ->first();
                
            if (!$employee) {
                return redirect()->back()->with('error', 'Employee not found or you don\'t have permission.');
            }
        } else {
            // Regular employee viewing their own summary
            $employee = $this->getCommonQuery(EmployeeDetails::class)
                ->where('user_id', $userId)
                ->first();
                
            if (!$employee) {
                return redirect()->back()->with('error', 'Employee record not found.');
            }
            
            $employeeId = $employee->employee_id;
        }
        
        // Get session year from request or use current
        $sessionYear = $request->input('session_year', $this->getCurrentAcademicSession());
        
        // Get leave balances for the session
        $leaveBalances = EmployeeLeaveBalance::where('employee_id', $employeeId)
            ->where('session_year', $sessionYear)
            ->get();
        
        // Get all available sessions for dropdown
        $availableSessions = EmployeeLeaveBalance::where('employee_id', $employeeId)
            ->distinct('session_year')
            ->pluck('session_year')
            ->sortDesc()
            ->values();
        
        // Calculate summary statistics
        $totalAllocatedLeaves = $leaveBalances->sum('total_allocated');
        $totalUsedLeaves = $leaveBalances->sum('used');
        $totalRemainingLeaves = $leaveBalances->sum('remaining');
        $usagePercentage = $totalAllocatedLeaves > 0 
            ? round(($totalUsedLeaves / $totalAllocatedLeaves) * 100, 1) 
            : 0;
        
        // Get recent leave applications (for the current session year)
        // $recentLeaves = LeaveApplication::where('employee_id', $employeeId)
        //     ->whereYear('created_at', '>=', date('Y') - 1) // Last year's applications
        //     ->orderBy('created_at', 'desc')
        //     ->limit(5)
        //     ->get();
        
        // If no leave balances for current session, check if there are any at all
        if ($leaveBalances->isEmpty() && $availableSessions->isNotEmpty()) {
            // Auto-select the latest session
            $sessionYear = $availableSessions->first();
            $leaveBalances = EmployeeLeaveBalance::where('employee_id', $employeeId)
                ->where('session_year', $sessionYear)
                ->get();
                
            // Recalculate with new session data
            $totalAllocatedLeaves = $leaveBalances->sum('total_allocated');
            $totalUsedLeaves = $leaveBalances->sum('used');
            $totalRemainingLeaves = $leaveBalances->sum('remaining');
            $usagePercentage = $totalAllocatedLeaves > 0 
                ? round(($totalUsedLeaves / $totalAllocatedLeaves) * 100, 1) 
                : 0;
        }

        // Add descriptions for each leave type
        $leaveDescriptions = [
            'Sick' => 'Medical leave with doctor certificate',
            'Casual' => 'Personal or emergency leave',
            'Earned' => 'Annual earned leave based on service',
            'Unpaid' => 'Leave with salary deduction',
            'Maternity' => 'Maternity and childcare leave',
            'half_days' => 'Half day leave applications',
            'short_leave' => 'Short duration leave (few hours)'
        ];
        
        // Enhance leave balances with descriptions
        $leaveBalances = $leaveBalances->map(function ($balance) use ($leaveDescriptions) {
            $balance->description = $leaveDescriptions[$balance->leave_type] ?? 'Annual leave allocation';
            return $balance;
        });
        
        return view('instituteAdmin.EmployeeFiles.leaveSummary', 
            compact(
                'leaveBalances', 
                'sessionYear', 
                'employee', 
                'availableSessions', 
                'isAdmin',
                'totalAllocatedLeaves',
                'totalUsedLeaves',
                'totalRemainingLeaves',
                'usagePercentage',

            ));
    }

   
    /**
     * Display employee's own leave applications with status
     */
    public function myLeaveStatus(Request $request)
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
            ->first();
        
        if (!$employee) {
            return redirect()->back()->with('error', 'Employee record not found.');
        }
        
        // Query leave applications with approvals ordered by step
        $query = EmployeeLeave::where('employee_id', $employee->employee_id)
            ->where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->with(['approvals' => function($q) {
                $q->orderBy('step_number', 'asc');
            }]);
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('final_status', $request->status);
        }
        
        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }
        
        if ($request->filled('from_date')) {
            $query->whereDate('start_date', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('end_date', '<=', $request->to_date);
        }
        
        if ($request->filled('year')) {
            $query->whereYear('start_date', $request->year);
        }
        
        // Order by latest first
        $leaveApplications = $query->orderBy('applied_date', 'desc')->paginate(10);
        
        // Transform approvals to show "Already Approved" status
        foreach ($leaveApplications as $leave) {
            // Check if any approval is already approved
            $hasApproved = $leave->approvals->contains(function($approval) {
                return $approval->status === 'Approved';
            });
            
            // If leave is approved, mark all pending approvals as "Already Approved" for display
            if ($hasApproved && $leave->final_status === 'Approved') {
                foreach ($leave->approvals as $approval) {
                    if ($approval->status === 'Pending') {
                        $approval->display_status = 'Already Approved';
                        $approval->display_status_class = 'already-approved';
                    } else {
                        $approval->display_status = $approval->status;
                        $approval->display_status_class = strtolower($approval->status);
                    }
                }
            } else {
                // If not approved yet, show actual status
                foreach ($leave->approvals as $approval) {
                    $approval->display_status = $approval->status;
                    $approval->display_status_class = strtolower($approval->status);
                }
            }
        }
        
        // Get all leave types for filter dropdown
        $leaveTypes = LeaveDeduction::forInstitute($context['institute_id'], $context['branch_id'])
            ->where('leave_type', '!=', 'Absent')
            ->where('is_active', true)
            ->pluck('leave_type')
            ->unique()
            ->values();
        
        // Get available years for filter
        $availableYears = EmployeeLeave::where('employee_id', $employee->employee_id)
            ->where('institute_id', $context['institute_id'])
            ->selectRaw('DISTINCT YEAR(start_date) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        // Get summary statistics
        $summary = [
            'total' => EmployeeLeave::where('employee_id', $employee->employee_id)
                ->where('institute_id', $context['institute_id'])
                ->count(),
            'pending' => EmployeeLeave::where('employee_id', $employee->employee_id)
                ->where('institute_id', $context['institute_id'])
                ->where('final_status', 'Pending')
                ->count(),
            'approved' => EmployeeLeave::where('employee_id', $employee->employee_id)
                ->where('institute_id', $context['institute_id'])
                ->where('final_status', 'Approved')
                ->count(),
            'rejected' => EmployeeLeave::where('employee_id', $employee->employee_id)
                ->where('institute_id', $context['institute_id'])
                ->where('final_status', 'Rejected')
                ->count(),
        ];
        
        return view('instituteAdmin.EmployeeFiles.myleavestatus', compact(
            'leaveApplications',
            'leaveTypes',
            'availableYears',
            'summary',
            'employee'
        ));
    }

    /**
     * Leave Quota Management - Get both individual and department-level assignments
     */
    public function quotaManagement(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }
        
        $currentSession = $request->session_year ?? $this->getCurrentAcademicSession();
        
        // Get all employees with department join
        $employeesQuery = EmployeeDetails::select(
            'employee_details.*',
            'departments.department as department_name'
        )
        ->leftJoin('departments', 'employee_details.department_id', '=', 'departments.department_id')
        ->where('employee_details.institute_id', $context['institute_id'])
        ->when($context['branch_id'], function($query) use ($context) {
            return $query->where('employee_details.branch_id', $context['branch_id']);
        })
        ->where('employee_details.status', 'active');
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $employeesQuery->where(function($q) use ($search) {
                $q->where('employee_details.name', 'like', "%{$search}%")
                ->orWhere('employee_details.employee_id', 'like', "%{$search}%")
                ->orWhere('employee_details.employee_code', 'like', "%{$search}%");
            });
        }
        
        // Apply department filter
        if ($request->filled('department_id')) {
            $employeesQuery->where('employee_details.department_id', $request->department_id);
        }
        
        $employees = $employeesQuery->paginate(20);
        
        // Manually load leave balances for each employee (including department-level assignments)
        foreach ($employees as $employee) {
            // Get individual assignments for this employee
            $individualBalances = EmployeeLeaveBalance::where('employee_id', $employee->employee_id)
                ->where('session_year', $currentSession);
            
            // Get department-level assignments for this employee's department
            $departmentBalances = EmployeeLeaveBalance::whereNull('employee_id')
                ->where('department_id', $employee->department_id)
                ->where('assignment_type', 'department')
                ->where('session_year', $currentSession);
            
            // Filter by leave type if specified
            if ($request->filled('leave_type')) {
                $individualBalances->where('leave_type', $request->leave_type);
                $departmentBalances->where('leave_type', $request->leave_type);
            }
            
            $individualBalances = $individualBalances->orderBy('leave_type')->get();
            $departmentBalances = $departmentBalances->orderBy('leave_type')->get();
            
            // Merge balances: individual takes precedence over department
            $mergedBalances = collect();
            
            // First, add all department balances
            foreach ($departmentBalances as $deptBalance) {
                $mergedBalances->put($deptBalance->leave_type, (object)[
                    'leave_type' => $deptBalance->leave_type,
                    'total_allocated' => $deptBalance->total_allocated,
                    'used' => 0,
                    'remaining' => $deptBalance->remaining,
                    'session_year' => $deptBalance->session_year,
                    'assignment_source' => 'department'
                ]);
            }
            
            // Then override with individual balances (individual takes precedence)
            foreach ($individualBalances as $indBalance) {
                $mergedBalances->put($indBalance->leave_type, (object)[
                    'leave_type' => $indBalance->leave_type,
                    'total_allocated' => $indBalance->total_allocated,
                    'used' => $indBalance->used ?? 0,
                    'remaining' => $indBalance->remaining,
                    'session_year' => $indBalance->session_year,
                    'assignment_source' => 'individual'
                ]);
            }
            
            $employee->leaveBalances = $mergedBalances->values();
        }
        
        // Filter by utilization if needed
        if ($request->filled('utilization_filter')) {
            $filteredEmployees = collect();
            
            foreach ($employees as $employee) {
                $hasMatchingBalance = false;
                
                foreach ($employee->leaveBalances as $balance) {
                    $percentage = $balance->total_allocated > 0 
                        ? (($balance->used ?? 0) / $balance->total_allocated) * 100 
                        : 0;
                    
                    $matches = false;
                    switch ($request->utilization_filter) {
                        case 'critical': $matches = $percentage >= 90; break;
                        case 'high': $matches = $percentage >= 70 && $percentage < 90; break;
                        case 'medium': $matches = $percentage >= 30 && $percentage < 70; break;
                        case 'low': $matches = $percentage > 0 && $percentage < 30; break;
                        case 'zero': $matches = $percentage == 0 && $balance->total_allocated > 0; break;
                        default: $matches = true;
                    }
                    
                    if ($matches) {
                        $hasMatchingBalance = true;
                        break;
                    }
                }
                
                if ($hasMatchingBalance || $employee->leaveBalances->isNotEmpty()) {
                    $filteredEmployees->push($employee);
                }
            }
            
            $employees = new \Illuminate\Pagination\LengthAwarePaginator(
                $filteredEmployees,
                $filteredEmployees->count(),
                20,
                $request->page ?? 1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }
        
        // Get departments for filter dropdown
        $departments = Departments::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->get();
        
        // Get available sessions
        $availableSessions = EmployeeLeaveBalance::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->distinct('session_year')
            ->pluck('session_year')
            ->sortDesc()
            ->values();
        
        if ($availableSessions->isEmpty()) {
            $availableSessions = collect([$this->getCurrentAcademicSession()]);
        }
        
        // Get leave types for filter
        $leaveTypes = LeaveDeduction::forInstitute($context['institute_id'], $context['branch_id'])
            ->where('is_active', true)
            ->pluck('leave_type')
            ->unique()
            ->values();
        
        // Calculate statistics
        $totalEmployees = EmployeeDetails::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->where('status', 'active')
            ->count();
        
        $individualLeavesAssigned = EmployeeLeaveBalance::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->whereNotNull('employee_id')
            ->where('session_year', $currentSession)
            ->sum('total_allocated');
        
        $departmentLeavesAssigned = EmployeeLeaveBalance::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->whereNull('employee_id')
            ->where('assignment_type', 'department')
            ->where('session_year', $currentSession)
            ->sum('total_allocated');
        
        $totalLeavesAssigned = $individualLeavesAssigned + $departmentLeavesAssigned;
        $totalUsedLeaves = EmployeeLeaveBalance::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->whereNotNull('employee_id')
            ->where('session_year', $currentSession)
            ->sum('used');
        
        $avgUtilization = $totalLeavesAssigned > 0 ? ($totalUsedLeaves / $totalLeavesAssigned) * 100 : 0;
        
        $totalDepartments = Departments::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->count();
        
        return view('instituteAdmin.EmployeeFiles.leaveQuotaManagement', compact(
            'employees', 'departments', 'availableSessions', 'currentSession', 
            'leaveTypes', 'totalEmployees', 'totalLeavesAssigned', 'avgUtilization', 
            'totalDepartments'
        ));
    }

    /**
     * AJAX endpoint for employee leave details (including department assignments)
     */
    public function ajaxEmployeeLeaveDetails($employeeId)
    {
        try {
            $context = $this->getInstituteBranchContext();
            $currentSession = request('session_year', $this->getCurrentAcademicSession());
            
            $employee = EmployeeDetails::with('department')
                ->where('employee_id', $employeeId)
                ->where('institute_id', $context['institute_id'])
                ->when($context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                })
                ->first();
            
            if (!$employee) {
                return response()->json(['success' => false, 'message' => 'Employee not found']);
            }
            
            // Get individual assignments
            $individualBalances = EmployeeLeaveBalance::where('employee_id', $employeeId)
                ->orderBy('session_year', 'desc')
                ->orderBy('leave_type')
                ->get();
            
            // Get department assignments for this employee's department
            $departmentBalances = EmployeeLeaveBalance::whereNull('employee_id')
                ->where('department_id', $employee->department_id)
                ->where('assignment_type', 'department')
                ->orderBy('session_year', 'desc')
                ->orderBy('leave_type')
                ->get();
            
            $html = view('instituteAdmin.EmployeeFiles.partials.employeeLeaveDetailsModal', compact('employee', 'individualBalances', 'departmentBalances'))->render();
            
            return response()->json(['success' => true, 'html' => $html]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Check if a specific notification channel is enabled for a module
     */
    private function isNotificationEnabled($instituteId, $moduleName, $channel)
    {
        try {
            $setting = InstituteNotificationSetting::where('institute_id', $instituteId)
                ->where('module_name', $moduleName)
                ->first();
            
            if (!$setting) {
                return $channel === 'email';
            }
            
            switch ($channel) {
                case 'email': return $setting->email_enabled;
                case 'whatsapp': return $setting->whatsapp_enabled;
                case 'sms': return $setting->sms_enabled;
                default: return false;
            }
        } catch (\Exception $e) {
            \Log::error('Error checking notification status: ' . $e->getMessage());
            return $channel === 'email';
        }
    }

    /**
     * Send email notification for leave quota assignment
     */
    private function sendLeaveQuotaEmail($employee, $leaveDetails, $assignmentMethod, $sessionYear, $context)
    {
        try {
            $emailEnabled = $this->isNotificationEnabled($context['institute_id'], 'leave_quota_assigned', 'email');
            
            if (!$emailEnabled || empty($employee->email)) {
                return;
            }
            
            $totalRawDays = collect($leaveDetails)->sum('raw_days');
            
            Mail::send('emails.leave-quota-assigned', [
                'employeeName' => $employee->name,
                'employeeCode' => $employee->employee_code,
                'leaveDetails' => $leaveDetails,
                'totalRawDays' => $totalRawDays,
                'sessionYear' => $sessionYear,
                'assignmentMethod' => $assignmentMethod
            ], function ($message) use ($employee) {
                $message->to($employee->email)
                        ->subject('Leave Quota Assigned for ' . date('Y') . ' - ' . config('app.name'));
            });
            
            \Log::info('Leave quota email sent to: ' . $employee->email);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send leave quota email: ' . $e->getMessage());
        }
    }

    /**
     * Send email notification for leave approval/rejection
     */
    private function sendLeaveStatusEmail($leave, $status, $approvedByName, $comments = null, $context)
    {
        try {
            $moduleName = ($status === 'approved') ? 'leave_approved' : 'leave_rejected';
            $emailEnabled = $this->isNotificationEnabled($context['institute_id'], $moduleName, 'email');
            
            if (!$emailEnabled || empty($leave->employee->email)) {
                return;
            }
            
            Mail::send('emails.leave-status-update', [
                'employeeName' => $leave->employee->name,
                'leaveType' => $leave->leave_type,
                'startDate' => Carbon::parse($leave->start_date)->format('d-m-Y'),
                'endDate' => Carbon::parse($leave->end_date)->format('d-m-Y'),
                'totalDays' => $leave->total_days,
                'reason' => $leave->reason,
                'status' => $status,
                'approvedByName' => $approvedByName,
                'comments' => $comments ?? ($status === 'rejected' ? 'No specific reason provided' : null)
            ], function ($message) use ($leave, $status) {
                $subject = $status === 'approved' 
                    ? 'Your Leave Request Has Been Approved' 
                    : 'Update on Your Leave Request';
                $message->to($leave->employee->email)
                        ->subject($subject . ' - ' . config('app.name'));
            });
            
            \Log::info('Leave status email sent to: ' . $leave->employee->email);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send leave status email: ' . $e->getMessage());
        }
    }

    /**
     * Send email notification to approvers for new leave request
     */
    private function sendLeaveRequestToApproverEmail($leave, $approver, $context)
    {
        try {
            $emailEnabled = $this->isNotificationEnabled($context['institute_id'], 'leave_applied', 'email');
            
            if (!$emailEnabled || empty($approver['email'])) {
                return;
            }
            
            Mail::send('emails.leave-request-notification', [
                'approverName' => $approver['name'],
                'employeeName' => $leave->employee->name,
                'employeeCode' => $leave->employee->employee_code,
                'departmentName' => $leave->employee->department->department ?? 'N/A',
                'leaveType' => $leave->leave_type,
                'startDate' => Carbon::parse($leave->start_date)->format('d-m-Y'),
                'endDate' => Carbon::parse($leave->end_date)->format('d-m-Y'),
                'totalDays' => $leave->total_days,
                'reason' => $leave->reason,
                'appliedDate' => Carbon::parse($leave->applied_date)->format('d-m-Y H:i')
            ], function ($message) use ($approver) {
                $message->to($approver['email'])
                        ->subject('New Leave Request Awaiting Approval - ' . config('app.name'));
            });
            
            \Log::info('Leave request email sent to approver: ' . $approver['email']);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send leave request email to approver: ' . $e->getMessage());
        }
    }

}