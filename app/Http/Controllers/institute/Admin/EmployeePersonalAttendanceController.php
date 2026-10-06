<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\EmployeeDetails;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeLeave;
use App\Models\AttendanceReview;
use App\Models\EmployeeLeaveBalance;
use App\Models\EmployeeShift;
use App\Models\WFHRequest;
use App\Models\DepartmentShift;
use App\Models\Shifts;
use Carbon\Carbon;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;

class EmployeePersonalAttendanceController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;

    /**
     * Display employee's own attendance for a specific month
     */
    public function myAttendance(Request $request)
    {
        $userId = auth()->id();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }
        
        $employee = EmployeeDetails::where('user_id', $userId)->first();
        if (!$employee) {
            return back()->with('error', 'Employee record not found.');
        }
        
        $employeeId = $employee->employee_id;
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');
        $merchantId = auth()->user()->institute_id;
        
        // Check if attendance is finalized for this month
        $finalizedAttendance = AttendanceReview::where('employee_id', $employeeId)
            ->where('year', $year)
            ->where('month', $month)
            ->where('institute_id', $merchantId)
            ->where('review_status', 'finalized')
            ->first();
        
        if ($finalizedAttendance) {
            return $this->showFinalizedAttendance($finalizedAttendance, $employee, $month, $year);
        }
        
        return $this->showRealtimeAttendance($employee, $month, $year);
    }

    /**
     * Show finalized attendance data
     */
    private function showFinalizedAttendance($finalizedAttendance, $employee, $month, $year)
    {
        $attendanceDetails = $finalizedAttendance->attendance_details;
        if (is_string($attendanceDetails)) {
            $attendanceDetails = json_decode($attendanceDetails, true) ?? [];
        }
        
        $leaveCounts = $finalizedAttendance->leave_counts ?? [];
        if (is_string($leaveCounts)) {
            $leaveCounts = json_decode($leaveCounts, true) ?? [];
        }
        
        $totalPresent = $finalizedAttendance->present_days ?? 0;
        $totalAbsent = $finalizedAttendance->absent_days ?? 0;
        $totalLeave = $finalizedAttendance->leave_days ?? 0;
        $totalWeekend = $finalizedAttendance->weekend_days ?? 0;
        $totalShortAttendance = $finalizedAttendance->short_attendance_days ?? 0;
        $attendancePercentage = $finalizedAttendance->attendance_percentage ?? 0;
        $workingDays = $finalizedAttendance->working_days ?? 0;
        
        $detailedAttendance = $this->buildDetailedAttendanceFromFinalized($attendanceDetails, $year, $month);
        
        return view('instituteAdmin.EmployeeFiles.ParticularEmployeeAttendance', compact(
            'detailedAttendance', 
            'month', 
            'year',
            'totalPresent',
            'totalAbsent', 
            'totalLeave',
            'totalWeekend',
            'totalShortAttendance',
            'attendancePercentage',
            'workingDays',
            'leaveCounts'
        ))->with('isFinalized', true)
        ->with('finalizedDate', $finalizedAttendance->finalized_at ? Carbon::parse($finalizedAttendance->finalized_at)->format('d M Y h:i A') : null);
    }

    /**
     * Show real-time attendance data with shift details
     */
    private function showRealtimeAttendance($employee, $month, $year)
    {
        $employeeId = $employee->employee_id;
        $merchantId = auth()->user()->institute_id;
        
        // Get all attendances for this month with logs
        $attendances = EmployeeAttendance::where('employee_id', $employeeId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('institute_id', $merchantId)
            ->with(['logs' => function ($q) {
                $q->orderBy('check_time');
            }])
            ->get()
            ->keyBy('date');
        
        // Convert times to Asia/Kolkata timezone
        $attendances->each(function ($attendance) {
            if ($attendance->logs) {
                $attendance->logs->transform(function ($log) {
                    $log->check_time = Carbon::parse($log->check_time, 'UTC')->setTimezone('Asia/Kolkata');
                    return $log;
                });
            }
        });
        
        // Get approved leaves for the month
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        
        $approvedLeaves = EmployeeLeave::where('employee_id', $employeeId)
            ->where('institute_id', $merchantId)
            ->where('final_status', 'Approved')
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->get();
        
        // Create leave lookup by date
        $fullDayLeaveDates = [];
        $shortDayLeaveDates = [];
        
        foreach ($approvedLeaves as $leave) {
            $leaveStart = Carbon::parse($leave->start_date);
            $leaveEnd = Carbon::parse($leave->end_date);
            $durationType = strtolower($leave->leave_duration_type ?? 'full day');
            
            $current = $leaveStart->copy();
            while ($current <= $leaveEnd) {
                $dateKey = $current->toDateString();
                
                if (in_array($durationType, ['short leave', 'short_leave'])) {
                    if (!isset($shortDayLeaveDates[$dateKey])) {
                        $shortDayLeaveDates[$dateKey] = $leave;
                    }
                } elseif (in_array($durationType, ['half day', 'half_day', 'half_days'])) {
                    if (!isset($shortDayLeaveDates[$dateKey])) {
                        $shortDayLeaveDates[$dateKey] = $leave;
                    }
                } else {
                    $fullDayLeaveDates[$dateKey] = $leave;
                }
                $current->addDay();
            }
        }
        
        // Get leave balances for quota information
        $currentSession = $this->getCurrentAcademicSession();
        $sessionFormats = [
            $currentSession,
            $year . '-' . ($year + 1),
            ($year - 1) . '-' . $year,
            $year
        ];
        $sessionFormats = array_unique($sessionFormats);
        
        $shortLeaveBalance = EmployeeLeaveBalance::where('employee_id', $employeeId)
            ->where('institute_id', $merchantId)
            ->where(function($query) {
                $query->where('leave_type', 'Short Leave')
                    ->orWhere('leave_type', 'short_leave')
                    ->orWhere('leave_type', 'Short Day Leave');
            })
            ->whereIn('session_year', $sessionFormats)
            ->first();
        
        $halfDayBalance = EmployeeLeaveBalance::where('employee_id', $employeeId)
            ->where('institute_id', $merchantId)
            ->where(function($query) {
                $query->where('leave_type', 'Half Day')
                    ->orWhere('leave_type', 'half_days')
                    ->orWhere('leave_type', 'half day')
                    ->orWhere('leave_type', 'Half Day Leave');
            })
            ->whereIn('session_year', $sessionFormats)
            ->first();
        
        // Track usage for quota checking
        $shortLeavesUsedThisMonth = 0;
        $halfDaysUsedThisMonth = 0;
        
        // Calculate totals
        $totalPresent = 0;
        $totalAbsent = 0;
        $totalLeave = 0;
        $totalWeekend = 0;
        $totalShortAttendance = 0;
        $totalUnapprovedLeave = 0;
        $detailedAttendance = [];
        
        $currentDate = Carbon::create($year, $month, 1);
        $daysInMonth = $currentDate->daysInMonth;
        $today = Carbon::today();
        
        // Get ALL WFH requests for the month (all statuses)
        $allWFHRequests = $this->getAllWFHRequestsForMonth($employeeId, $startDate, $endDate);
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateObj = Carbon::create($year, $month, $day);
            $dateString = $dateObj->toDateString();
            $dayOfWeek = $dateObj->dayOfWeekIso;
            
            // ✅ Get shift for this specific date using priority logic
            $shiftDetails = $this->getEffectiveShiftForDateWithPriority($employee, $dateString);
            
            // ✅ Check if it's a weekly off based on shift
            $isWeeklyOff = false;
            if ($shiftDetails && !empty($shiftDetails['weekly_off_days'])) {
                $isWeeklyOff = $this->isWeeklyOff($shiftDetails['weekly_off_days'], $dateString);
            } else {
                $isWeeklyOff = ($dayOfWeek == 7); // Default: Sunday off
            }
            
            // ✅ Check WFH for this date (ALL STATUSES)
            $wfhForDate = $this->getWFHForDate($allWFHRequests, $dateString);
            
            $isWFH = $wfhForDate !== null;
            
            // ✅ Build shift display info
            $shiftDisplay = null;
            if ($shiftDetails) {
                $shiftDisplay = [
                    'shift_id' => $shiftDetails['shift_id'] ?? null,
                    'shift_name' => $shiftDetails['shift_name'] ?? 'N/A',
                    'start_time' => $shiftDetails['start_time_formatted'] ?? null,
                    'end_time' => $shiftDetails['end_time_formatted'] ?? null,
                    'working_hours' => $shiftDetails['working_hours'] ?? 9,
                    'half_day_hours' => $shiftDetails['half_day_hours'] ?? 4,
                    'short_leave_hours' => $shiftDetails['short_leave_hours'] ?? 6,
                    'grace_minutes' => $shiftDetails['grace_minutes'] ?? 15,
                    'assignment_type' => $shiftDetails['assignment_type'] ?? 'none',
                    'start_time_raw' => $shiftDetails['start_time'] ?? null,
                    'end_time_raw' => $shiftDetails['end_time'] ?? null,
                ];
            }
            
            if ($isWeeklyOff) {
                $totalWeekend++;
                $detailedAttendance[$dateString] = [
                    'status' => 'weekend',
                    'status_text' => 'Weekly Off',
                    'status_color' => 'secondary',
                    'status_icon' => 'bi-calendar-week',
                    'day_name' => $dateObj->format('l'),
                    'is_working_day' => false,
                    'shift' => $shiftDisplay,
                    'is_wfh' => $isWFH,
                    'wfh_details' => $wfhForDate,
                    'wfh_status' => $wfhForDate ? ucfirst($wfhForDate['request_status'] ?? 'pending') : null,
                    'wfh_status_badge' => $wfhForDate ? $wfhForDate['request_status'] : null,
                    'wfh_reason' => $wfhForDate ? $wfhForDate['reason'] : null,
                    'wfh_request_id' => $wfhForDate ? ($wfhForDate['request_id'] ?? null) : null,
                    'wfh_approved_at' => $wfhForDate ? $wfhForDate['approved_at'] : null,
                    'wfh_rejected_at' => $wfhForDate ? $wfhForDate['rejected_at'] : null,
                    'wfh_rejection_reason' => $wfhForDate ? $wfhForDate['rejection_reason'] : null,
                ];
                continue;
            }
            
            // Check for approved full day leave
            if (isset($fullDayLeaveDates[$dateString])) {
                $leave = $fullDayLeaveDates[$dateString];
                $totalLeave++;
                $detailedAttendance[$dateString] = [
                    'status' => 'leave',
                    'status_text' => $leave->leave_type ? ucfirst($leave->leave_type) : 'Leave',
                    'status_color' => 'warning',
                    'status_icon' => 'bi-calendar-week',
                    'day_name' => $dateObj->format('l'),
                    'leave_type' => $leave->leave_type,
                    'approved_leave' => true,
                    'is_working_day' => true,
                    'shift' => $shiftDisplay,
                    'is_wfh' => $isWFH,
                    'wfh_details' => $wfhForDate,
                    'wfh_status' => $wfhForDate ? $wfhForDate['request_status'] : null
                ];
                continue;
            }
            
            // Check for attendance record
            if (isset($attendances[$dateString])) {
                $record = $attendances[$dateString];
                
                $logs = $record->logs;
                $checkInLog = $logs->where('check_type', 'IN')->first();
                $checkOutLog = $logs->where('check_type', 'OUT')->last();
                
                $checkInTime = $checkInLog ? $checkInLog->check_time->format('h:i A') : null;
                $checkOutTime = $checkOutLog ? $checkOutLog->check_time->format('h:i A') : null;
                $totalHours = $record->total_hours ? floatval($record->total_hours) : 0;
                
                $attendanceResult = $this->calculateDetailedAttendanceStatus(
                    $record,
                    $shiftDetails,
                    isset($shortDayLeaveDates[$dateString]) ? $shortDayLeaveDates[$dateString] : null,
                    $shortLeavesUsedThisMonth,
                    $halfDaysUsedThisMonth,
                    $shortLeaveBalance,
                    $halfDayBalance,
                    $totalHours
                );
                
                // Update usage counters and totals
                if ($attendanceResult['status'] === 'present') {
                    if ($attendanceResult['leave_type'] === 'Short Leave') {
                        $shortLeavesUsedThisMonth++;
                    } elseif ($attendanceResult['leave_type'] === 'Half Day') {
                        $halfDaysUsedThisMonth++;
                    }
                    $totalPresent++;
                } elseif ($attendanceResult['status'] === 'absent') {
                    $totalAbsent++;
                } elseif ($attendanceResult['status'] === 'leave') {
                    $totalLeave++;
                } elseif ($attendanceResult['status'] === 'short_attendance') {
                    $totalShortAttendance++;
                }
                
                $requiredHours = $shiftDetails['working_hours'] ?? 9;
                $shortHours = max(0, $requiredHours - $totalHours);
                
                $graceInfo = $this->getCheckInStatusWithGrace($record, $shiftDetails);
                
                // Determine WFH status display text
                $wfhStatusText = null;
                $wfhStatusBadge = null;
                if ($wfhForDate) {
                    $wfhStatusText = ucfirst($wfhForDate['request_status'] ?? 'pending');
                    $wfhStatusBadge = $wfhForDate['request_status'] ?? 'pending';
                }
                
                $detailedAttendance[$dateString] = [
                    'status' => $attendanceResult['status'],
                    'status_text' => $attendanceResult['status_text'],
                    'status_color' => $attendanceResult['status_color'],
                    'status_icon' => $attendanceResult['status_icon'],
                    'day_name' => $dateObj->format('l'),
                    'check_in' => $checkInTime,
                    'check_out' => $checkOutTime,
                    'total_hours' => $totalHours,
                    'required_hours' => $requiredHours,
                    'short_hours' => round($shortHours, 2),
                    'grace_status' => $graceInfo['status_message'],
                    'is_within_grace' => $graceInfo['is_within_grace'],
                    'late_minutes' => $attendanceResult['late_minutes'] ?? $graceInfo['late_minutes'],
                    'leave_type' => $attendanceResult['leave_type'] ?? null,
                    'within_quota' => $attendanceResult['within_quota'] ?? true,
                    'days_to_deduct' => $attendanceResult['days_to_deduct'] ?? 0,
                    'is_approved' => $attendanceResult['is_approved'] ?? false,
                    'deduction_percentage' => $attendanceResult['deduction_percentage'] ?? 0,
                    'is_working_day' => true,
                    'shift' => $shiftDisplay,
                    'is_wfh' => $isWFH,
                    'wfh_details' => $wfhForDate,
                    'wfh_status' => $wfhStatusText,
                    'wfh_status_badge' => $wfhStatusBadge,
                    'wfh_reason' => $wfhForDate ? $wfhForDate['reason'] : null,
                    'wfh_request_id' => $wfhForDate ? $wfhForDate['request_id'] : null,
                    'wfh_approved_at' => $wfhForDate ? $wfhForDate['approved_at'] : null,
                    'wfh_rejected_at' => $wfhForDate ? $wfhForDate['rejected_at'] : null,
                    'wfh_rejection_reason' => $wfhForDate ? $wfhForDate['rejection_reason'] : null,
                ];
            }
            // Check for short day leave (pre-approved)
            elseif (isset($shortDayLeaveDates[$dateString])) {
                $leave = $shortDayLeaveDates[$dateString];
                $durationType = strtolower($leave->leave_duration_type);
                $requiredHours = $shiftDetails['working_hours'] ?? 9;
                
                if (in_array($durationType, ['short leave', 'short_leave'])) {
                    $shortLeavesUsedThisMonth++;
                    $quota = $shortLeaveBalance ? floatval($shortLeaveBalance->remaining ?? 0) : 0;
                    $isWithinQuota = $shortLeavesUsedThisMonth <= $quota;
                    $totalPresent++;
                    
                    $detailedAttendance[$dateString] = [
                        'status' => 'present',
                        'status_text' => $isWithinQuota ? 'Present + Short Leave (Within Quota)' : 'Present + Short Leave (Exceeds Quota)',
                        'status_color' => 'success',
                        'status_icon' => 'bi-check-circle',
                        'day_name' => $dateObj->format('l'),
                        'check_in' => null,
                        'check_out' => null,
                        'total_hours' => null,
                        'required_hours' => $requiredHours,
                        'short_hours' => 0,
                        'leave_type' => 'Short Leave',
                        'within_quota' => $isWithinQuota,
                        'days_to_deduct' => $isWithinQuota ? 0 : 0.25,
                        'is_approved' => $isWithinQuota,
                        'deduction_percentage' => $isWithinQuota ? 25 : 55,
                        'approved_leave' => true,
                        'is_working_day' => true,
                        'shift' => $shiftDisplay,
                        'is_wfh' => $isWFH,
                        'wfh_details' => $wfhForDate,
                        'wfh_status' => $wfhForDate ? ucfirst($wfhForDate['request_status'] ?? 'pending') : null,
                        'wfh_status_badge' => $wfhForDate ? $wfhForDate['request_status'] : null,
                        'wfh_reason' => $wfhForDate ? $wfhForDate['reason'] : null,
                        'wfh_request_id' => $wfhForDate ? $wfhForDate['request_id'] : null,
                    ];
                } elseif (in_array($durationType, ['half day', 'half_day', 'half_days'])) {
                    $halfDaysUsedThisMonth++;
                    $quota = $halfDayBalance ? floatval($halfDayBalance->remaining ?? 0) : 0;
                    $isWithinQuota = $halfDaysUsedThisMonth <= $quota;
                    $totalPresent++;
                    
                    $detailedAttendance[$dateString] = [
                        'status' => 'present',
                        'status_text' => $isWithinQuota ? 'Present + Half Day (Within Quota)' : 'Present + Half Day (Exceeds Quota)',
                        'status_color' => 'success',
                        'status_icon' => 'bi-check-circle',
                        'day_name' => $dateObj->format('l'),
                        'check_in' => null,
                        'check_out' => null,
                        'total_hours' => null,
                        'required_hours' => $requiredHours,
                        'short_hours' => 0,
                        'leave_type' => 'Half Day',
                        'within_quota' => $isWithinQuota,
                        'days_to_deduct' => $isWithinQuota ? 0 : 0.5,
                        'is_approved' => $isWithinQuota,
                        'deduction_percentage' => $isWithinQuota ? 50 : 100,
                        'approved_leave' => true,
                        'is_working_day' => true,
                        'shift' => $shiftDisplay,
                        'is_wfh' => $isWFH,
                        'wfh_details' => $wfhForDate,
                        'wfh_status' => $wfhForDate ? ucfirst($wfhForDate['request_status'] ?? 'pending') : null,
                        'wfh_status_badge' => $wfhForDate ? $wfhForDate['request_status'] : null,
                        'wfh_reason' => $wfhForDate ? $wfhForDate['reason'] : null,
                        'wfh_request_id' => $wfhForDate ? $wfhForDate['request_id'] : null,
                    ];
                }
            }
            // No record - Check if it's a future date
            else {
                if ($dateObj->gt($today)) {
                    $detailedAttendance[$dateString] = [
                        'status' => 'upcoming',
                        'status_text' => 'Upcoming',
                        'status_color' => 'secondary',
                        'status_icon' => 'bi-calendar',
                        'day_name' => $dateObj->format('l'),
                        'is_working_day' => true,
                        'is_future' => true,
                        'shift' => $shiftDisplay,
                        'is_wfh' => $isWFH,
                        'wfh_details' => $wfhForDate,
                        'wfh_status' => $wfhForDate ? ucfirst($wfhForDate['request_status'] ?? 'pending') : null,
                        'wfh_status_badge' => $wfhForDate ? $wfhForDate['request_status'] : null,
                        'wfh_reason' => $wfhForDate ? $wfhForDate['reason'] : null,
                        'wfh_request_id' => $wfhForDate ? ($wfhForDate['request_id'] ?? null) : null,
                        'wfh_approved_at' => $wfhForDate ? $wfhForDate['approved_at'] : null,
                        'wfh_rejected_at' => $wfhForDate ? $wfhForDate['rejected_at'] : null,
                        'wfh_rejection_reason' => $wfhForDate ? $wfhForDate['rejection_reason'] : null,
                    ];
                } else {
                    $totalAbsent++;
                    $detailedAttendance[$dateString] = [
                        'status' => 'absent',
                        'status_text' => 'Absent',
                        'status_color' => 'danger',
                        'status_icon' => 'bi-x-circle',
                        'day_name' => $dateObj->format('l'),
                        'is_working_day' => true,
                        'is_future' => false,
                        'shift' => $shiftDisplay,
                        'is_wfh' => $isWFH,
                        'wfh_details' => $wfhForDate,
                        'wfh_status' => $wfhForDate ? ucfirst($wfhForDate['request_status'] ?? 'pending') : null,
                        'wfh_status_badge' => $wfhForDate ? $wfhForDate['request_status'] : null,
                        'wfh_reason' => $wfhForDate ? $wfhForDate['reason'] : null,
                        'wfh_request_id' => $wfhForDate ? ($wfhForDate['request_id'] ?? null) : null,
                        'wfh_approved_at' => $wfhForDate ? $wfhForDate['approved_at'] : null,
                        'wfh_rejected_at' => $wfhForDate ? $wfhForDate['rejected_at'] : null,
                        'wfh_rejection_reason' => $wfhForDate ? $wfhForDate['rejection_reason'] : null,
                    ];
                }
            }
        }
        
        $workingDays = $daysInMonth - $totalWeekend;
        $attendancePercentage = $workingDays > 0 ? round(($totalPresent / $workingDays) * 100, 2) : 0;
        
        $leaveCounts = [];
        
        return view('instituteAdmin.EmployeeFiles.ParticularEmployeeAttendance', compact(
            'detailedAttendance', 
            'month', 
            'year',
            'totalPresent',
            'totalAbsent', 
            'totalLeave',
            'totalWeekend',
            'totalShortAttendance',
            'totalUnapprovedLeave',
            'attendancePercentage',
            'workingDays',
            'shortLeavesUsedThisMonth',
            'halfDaysUsedThisMonth',
            'shortLeaveBalance',
            'halfDayBalance',
            'leaveCounts'
        ))->with('isFinalized', false);
    }

    /**
     * Build detailed attendance array from finalized data
     */
    private function buildDetailedAttendanceFromFinalized($attendanceDetails, $year, $month)
    {
        $detailedAttendance = [];
        $currentDate = Carbon::create($year, $month, 1);
        $daysInMonth = $currentDate->daysInMonth;
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateObj = Carbon::create($year, $month, $day);
            $dateString = $dateObj->toDateString();
            
            if (isset($attendanceDetails[$dateString])) {
                $details = $attendanceDetails[$dateString];
                $status = $details['status'] ?? '';
                
                $detailedAttendance[$dateString] = [
                    'status' => $status,
                    'status_text' => $details['status_text'] ?? ucfirst($status),
                    'status_color' => $this->getStatusColor($status),
                    'status_icon' => $this->getStatusIcon($status),
                    'day_name' => $dateObj->format('l'),
                    'check_in' => $details['check_in'] ?? null,
                    'check_out' => $details['check_out'] ?? null,
                    'total_hours' => $details['total_hours'] ?? null,
                    'required_hours' => $details['required_hours'] ?? 9,
                    'leave_type' => $details['leave_type'] ?? null,
                    'is_approved' => $details['is_approved'] ?? false,
                    'within_quota' => $details['within_quota'] ?? true,
                    'deduction_percentage' => $details['deduction_percentage'] ?? 0,
                    'grace_status' => $details['grace_status'] ?? null,
                    'late_minutes' => $details['late_minutes'] ?? 0,
                    'is_working_day' => $details['is_working_day'] ?? true,
                    'shift' => $details['shift'] ?? null,
                    'is_wfh' => $details['is_wfh'] ?? false,
                    'wfh_details' => $details['wfh_details'] ?? null,
                    'wfh_status' => $details['wfh_status'] ?? null
                ];
            } else {
                $dayOfWeek = $dateObj->dayOfWeekIso;
                $isSunday = ($dayOfWeek == 7);
                
                if ($isSunday) {
                    $detailedAttendance[$dateString] = [
                        'status' => 'weekend',
                        'status_text' => 'Weekly Off',
                        'status_color' => 'secondary',
                        'status_icon' => 'bi-calendar-week',
                        'day_name' => $dateObj->format('l'),
                        'is_working_day' => false,
                        'shift' => null,
                        'is_wfh' => false,
                        'wfh_details' => null,
                        'wfh_status' => null
                    ];
                } else {
                    $detailedAttendance[$dateString] = [
                        'status' => 'absent',
                        'status_text' => 'Absent',
                        'status_color' => 'danger',
                        'status_icon' => 'bi-x-circle',
                        'day_name' => $dateObj->format('l'),
                        'is_working_day' => true,
                        'shift' => null,
                        'is_wfh' => false,
                        'wfh_details' => null,
                        'wfh_status' => null
                    ];
                }
            }
        }
        
        return $detailedAttendance;
    }

    /**
     * ==========================================================
     * FIXED: Get effective shift with PROPER PRIORITY LOGIC
     * ==========================================================
     * Priority: Individual Shift (highest) > Department Shift > Default Shift
     * Within same type: Higher priority (high > medium > low) wins
     * If same priority: Most recently created wins
     */
    private function getEffectiveShiftForDateWithPriority($employee, $date)
    {
        $currentDate = Carbon::parse($date)->toDateString();
        $instituteId = $employee->institute_id;
        $branchId = $employee->branch_id;

        // ==========================================
        // 1. COLLECT INDIVIDUAL SHIFTS
        // ==========================================
        $individualShifts = EmployeeShift::where('institute_id', $instituteId)
            ->where('employee_id', $employee->employee_id)
            ->where('status', 'active')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId),
                            fn($q) => $q->whereNull('branch_id'))
            ->with(['shift' => function($q) use ($currentDate) {
                $q->where('start_date', '<=', $currentDate)
                  ->where(function($q2) use ($currentDate) {
                      $q2->where('end_date', '>=', $currentDate)
                          ->orWhereNull('end_date');
                  });
            }])
            ->get()
            ->filter(fn($s) => $s->shift !== null)
            ->each(function ($shift) {
                $shift->type = 'individual';
                $shift->priority_value = $this->getPriorityValue($shift->shift->priority);
            });

        // ==========================================
        // 2. COLLECT DEPARTMENT SHIFTS
        // ==========================================
        $departmentShifts = collect([]);

        if ($employee->department_id) {
            $departmentShifts = DepartmentShift::where('institute_id', $instituteId)
                ->where('department_id', $employee->department_id)
                ->where('status', 'active')
                ->where('shift_type', 'employee')
                ->when($branchId, fn($q) => $q->where('branch_id', $branchId),
                                fn($q) => $q->whereNull('branch_id'))
                ->with(['shift' => function($q) use ($currentDate) {
                    $q->where('start_date', '<=', $currentDate)
                      ->where(function($q2) use ($currentDate) {
                          $q2->where('end_date', '>=', $currentDate)
                              ->orWhereNull('end_date');
                      });
                }])
                ->get()
                ->filter(fn($s) => $s->shift !== null)
                ->each(function ($shift) {
                    $shift->type = 'department';
                    $shift->priority_value = $this->getPriorityValue($shift->shift->priority);
                });
        }

        // ==========================================
        // 3. COLLECT DEFAULT SHIFT (direct shift_id)
        // ==========================================
        $defaultShift = null;
        if ($employee->shift_id) {
            $shift = Shifts::where('id', $employee->shift_id)
                ->where('institute_id', $instituteId)
                ->first();
            
            if ($shift) {
                $defaultShift = new \stdClass();
                $defaultShift->type = 'default';
                $defaultShift->shift = $shift;
                $defaultShift->priority_value = $this->getPriorityValue($shift->priority);
                $defaultShift->created_at = $shift->created_at ?? Carbon::now();
            }
        }

        // ==========================================
        // 4. MERGE ALL SHIFTS
        // ==========================================
        $allShifts = $individualShifts->merge($departmentShifts);

        // Add default shift to collection if it exists
        if ($defaultShift) {
            $allShifts->push($defaultShift);
        }

        if ($allShifts->isEmpty()) {
            return null;
        }

        // ==========================================
        // 5. SORT: priority DESC → created_at DESC
        // ==========================================
        $sorted = $allShifts->sortByDesc(function ($s) {
            return [$s->priority_value, $s->created_at->timestamp];
        });

        // ==========================================
        // 6. GET THE BEST MATCHING SHIFT
        // ==========================================
        $bestShift = $sorted->first();

        if (!$bestShift) {
            return null;
        }

        // Format and return the shift details
        return $this->formatShiftDetails($bestShift->shift, $bestShift->type);
    }

    /**
     * Convert priority string to numeric value (same as EmployeeShiftController)
     */
    private function getPriorityValue($priority)
    {
        $priorityValues = [
            'high' => 3,
            'medium' => 2,
            'low' => 1
        ];
        
        return $priorityValues[strtolower($priority)] ?? 1;
    }

    /**
     * Format shift details for display
     */
    private function formatShiftDetails($shift, $assignmentType)
    {
        // Get weekly offs
        $weeklyOffs = [];
        if ($shift->weekly_off_days) {
            try {
                $weeklyOffs = is_array($shift->weekly_off_days) 
                    ? $shift->weekly_off_days 
                    : json_decode($shift->weekly_off_days, true);
                if (!is_array($weeklyOffs)) {
                    $weeklyOffs = [];
                }
            } catch (\Exception $e) {
                $weeklyOffs = [];
            }
        }

        return [
            'shift_id' => $shift->id,
            'shift_name' => $shift->shift_name,
            'start_time' => $shift->start_time,
            'end_time' => $shift->end_time,
            'start_time_formatted' => date('h:i A', strtotime($shift->start_time)),
            'end_time_formatted' => date('h:i A', strtotime($shift->end_time)),
            'working_hours' => $shift->working_hours ?? 9,
            'half_day_hours' => $shift->half_day_hours ?? 4,
            'short_leave_hours' => $shift->short_leave_hours ?? 6,
            'grace_minutes' => $shift->grace_minutes ?? 15,
            'weekly_off_days' => $weeklyOffs,
            'priority' => $shift->priority,
            'start_date' => $shift->start_date,
            'end_date' => $shift->end_date,
            'assignment_type' => $assignmentType
        ];
    }

    /**
     * Check if a date is a weekly off
     */
    private function isWeeklyOff($weeklyOffDays, $date)
    {
        if (empty($weeklyOffDays)) {
            return false;
        }

        $dayOfWeek = Carbon::parse($date)->dayOfWeekIso;
        
        $dayMap = [
            'Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3,
            'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6, 'Sunday' => 7
        ];
        
        foreach ($weeklyOffDays as $day) {
            if (is_numeric($day)) {
                if ((int)$day == $dayOfWeek) return true;
            } elseif (isset($dayMap[$day])) {
                if ($dayMap[$day] == $dayOfWeek) return true;
            } elseif (isset($dayMap[ucfirst($day)])) {
                if ($dayMap[ucfirst($day)] == $dayOfWeek) return true;
            }
        }
        
        return false;
    }

    /**
     * Calculate detailed attendance status
     */
    private function calculateDetailedAttendanceStatus($record, $shiftDetails, $shortDayLeave, &$shortLeavesUsedThisMonth, &$halfDaysUsedThisMonth, $shortLeaveBalance, $halfDayBalance, $totalHours = null)
    {
        $requiredHours = floatval($shiftDetails['working_hours'] ?? 9);
        $halfDayHours = floatval($shiftDetails['half_day_hours'] ?? 4);
        $shortLeaveHours = floatval($shiftDetails['short_leave_hours'] ?? 6);
        
        if ($totalHours !== null) {
            $workedHours = floatval($totalHours);
        } else {
            $workedHours = floatval($record->total_hours ?? 0);
        }
        
        $logs = $record->logs->sortBy('check_time');
        $checkInLog = $logs->where('check_type', 'IN')->first();
        
        $isOnTime = true;
        $lateMinutes = 0;
        
        if ($checkInLog && $shiftDetails) {
            try {
                $checkInTime = Carbon::parse($checkInLog->check_time, 'UTC')->setTimezone('Asia/Kolkata');
                $shiftStartTime = Carbon::createFromFormat('H:i:s', $shiftDetails['start_time'], 'Asia/Kolkata');
                $shiftStartTime->setDate($checkInTime->year, $checkInTime->month, $checkInTime->day);
                $graceMinutes = $shiftDetails['grace_minutes'] ?? 15;
                $graceEndTime = $shiftStartTime->copy()->addMinutes($graceMinutes);
                
                if ($checkInTime->gt($graceEndTime)) {
                    $isOnTime = false;
                    $lateMinutes = $checkInTime->diffInMinutes($shiftStartTime);
                }
            } catch (\Exception $e) {
                // Ignore parsing errors
            }
        }
        
        $hasCheckIn = ($checkInLog !== null);
        
        if (!$hasCheckIn && $workedHours == 0) {
            return [
                'status' => 'absent',
                'status_text' => 'Absent',
                'status_color' => 'danger',
                'status_icon' => 'bi-x-circle',
                'leave_type' => null,
                'within_quota' => true,
                'days_to_deduct' => 0,
                'is_approved' => false,
                'deduction_percentage' => 100,
                'late_minutes' => 0
            ];
        }
        
        if ($shortDayLeave) {
            $durationType = strtolower($shortDayLeave->leave_duration_type);
            
            if (in_array($durationType, ['short leave', 'short_leave'])) {
                $quota = $shortLeaveBalance ? floatval($shortLeaveBalance->remaining ?? 0) : 0;
                $isWithinQuota = ($shortLeavesUsedThisMonth + 1) <= $quota;
                return [
                    'status' => 'present',
                    'status_text' => $isWithinQuota 
                        ? ($isOnTime ? 'Present + Short Leave (Within Quota)' : 'Present + Short Leave (Within Quota, Late - ' . $lateMinutes . ' min)')
                        : ($isOnTime ? 'Present + Short Leave (Exceeds Quota)' : 'Present + Short Leave (Exceeds Quota, Late - ' . $lateMinutes . ' min)'),
                    'status_color' => 'success',
                    'status_icon' => 'bi-check-circle',
                    'leave_type' => 'Short Leave',
                    'within_quota' => $isWithinQuota,
                    'days_to_deduct' => $isWithinQuota ? 0 : 0.25,
                    'is_approved' => $isWithinQuota,
                    'deduction_percentage' => $isWithinQuota ? 25 : 55,
                    'late_minutes' => $lateMinutes
                ];
            } elseif (in_array($durationType, ['half day', 'half_day', 'half_days'])) {
                $quota = $halfDayBalance ? floatval($halfDayBalance->remaining ?? 0) : 0;
                $isWithinQuota = ($halfDaysUsedThisMonth + 1) <= $quota;
                return [
                    'status' => 'present',
                    'status_text' => $isWithinQuota 
                        ? ($isOnTime ? 'Present + Half Day (Within Quota)' : 'Present + Half Day (Within Quota, Late - ' . $lateMinutes . ' min)')
                        : ($isOnTime ? 'Present + Half Day (Exceeds Quota)' : 'Present + Half Day (Exceeds Quota, Late - ' . $lateMinutes . ' min)'),
                    'status_color' => 'success',
                    'status_icon' => 'bi-check-circle',
                    'leave_type' => 'Half Day',
                    'within_quota' => $isWithinQuota,
                    'days_to_deduct' => $isWithinQuota ? 0 : 0.5,
                    'is_approved' => $isWithinQuota,
                    'deduction_percentage' => $isWithinQuota ? 50 : 100,
                    'late_minutes' => $lateMinutes
                ];
            }
        }
        
        if ($workedHours >= $requiredHours) {
            return [
                'status' => 'present',
                'status_text' => $isOnTime ? 'Present' : 'Present (Late - ' . $lateMinutes . ' min)',
                'status_color' => 'success',
                'status_icon' => 'bi-check-circle',
                'leave_type' => null,
                'within_quota' => true,
                'days_to_deduct' => 0,
                'is_approved' => true,
                'deduction_percentage' => 0,
                'late_minutes' => $lateMinutes
            ];
        } 
        elseif ($workedHours >= $shortLeaveHours && $workedHours < $requiredHours) {
            return [
                'status' => 'present',
                'status_text' => $isOnTime ? 'Present + Short Leave (Exceeds Quota)' : 'Present + Short Leave (Exceeds Quota, Late - ' . $lateMinutes . ' min)',
                'status_color' => 'success',
                'status_icon' => 'bi-check-circle',
                'leave_type' => 'Short Leave',
                'within_quota' => false,
                'days_to_deduct' => 0.25,
                'is_approved' => false,
                'deduction_percentage' => 55,
                'late_minutes' => $lateMinutes
            ];
        } 
        elseif ($workedHours >= $halfDayHours && $workedHours < $shortLeaveHours) {
            return [
                'status' => 'present',
                'status_text' => $isOnTime ? 'Present + Half Day (Exceeds Quota)' : 'Present + Half Day (Exceeds Quota, Late - ' . $lateMinutes . ' min)',
                'status_color' => 'success',
                'status_icon' => 'bi-check-circle',
                'leave_type' => 'Half Day',
                'within_quota' => false,
                'days_to_deduct' => 0.5,
                'is_approved' => false,
                'deduction_percentage' => 100,
                'late_minutes' => $lateMinutes
            ];
        } 
        elseif ($workedHours > 0 && $workedHours < $halfDayHours) {
            return [
                'status' => 'present',
                'status_text' => $isOnTime ? 'Present + Half Day (Minimal Attendance)' : 'Present + Half Day (Minimal, Late - ' . $lateMinutes . ' min)',
                'status_color' => 'success',
                'status_icon' => 'bi-check-circle',
                'leave_type' => 'Half Day',
                'within_quota' => false,
                'days_to_deduct' => 0.5,
                'is_approved' => false,
                'deduction_percentage' => 100,
                'late_minutes' => $lateMinutes
            ];
        } 
        else {
            return [
                'status' => 'present',
                'status_text' => $isOnTime ? 'Present (No hours recorded)' : 'Present (Late - ' . $lateMinutes . ' min)',
                'status_color' => 'success',
                'status_icon' => 'bi-check-circle',
                'leave_type' => null,
                'within_quota' => true,
                'days_to_deduct' => 0,
                'is_approved' => true,
                'deduction_percentage' => 0,
                'late_minutes' => $lateMinutes
            ];
        }
    }

    /**
     * Get check-in status with grace period
     */
    private function getCheckInStatusWithGrace($record, $shiftDetails)
    {
        $result = [
            'check_in_time' => null,
            'shift_start_time' => null,
            'is_within_grace' => false,
            'is_late' => false,
            'late_minutes' => 0,
            'grace_minutes' => $shiftDetails['grace_minutes'] ?? 15,
            'status_message' => ''
        ];
        
        $logs = $record->logs->sortBy('check_time');
        $checkInLog = $logs->where('check_type', 'IN')->first();
        
        if (!$checkInLog || !$shiftDetails) {
            $result['status_message'] = 'No check-in record';
            return $result;
        }
        
        try {
            $checkInTime = Carbon::parse($checkInLog->check_time, 'UTC')->setTimezone('Asia/Kolkata');
            $shiftStartTime = Carbon::createFromFormat('H:i:s', $shiftDetails['start_time'], 'Asia/Kolkata');
            $shiftStartTime->setDate($checkInTime->year, $checkInTime->month, $checkInTime->day);
            
            $result['check_in_time'] = $checkInTime->format('h:i A');
            $result['shift_start_time'] = $shiftStartTime->format('h:i A');
            
            $graceMinutes = $shiftDetails['grace_minutes'] ?? 15;
            $graceEndTime = $shiftStartTime->copy()->addMinutes($graceMinutes);
            
            if ($checkInTime->lte($graceEndTime)) {
                $result['is_within_grace'] = true;
                $result['is_late'] = false;
                $result['late_minutes'] = 0;
                $result['status_message'] = 'On Time (within ' . $graceMinutes . ' min grace period)';
            } else {
                $result['is_within_grace'] = false;
                $result['is_late'] = true;
                $result['late_minutes'] = $checkInTime->diffInMinutes($shiftStartTime);
                $result['status_message'] = 'Late by ' . $result['late_minutes'] . ' min (' . $graceMinutes . ' min grace period)';
            }
        } catch (\Exception $e) {
            $result['status_message'] = 'Error calculating grace period';
        }
        
        return $result;
    }

    /**
     * Parse weekly off days from various formats
     */
    private function parseWeeklyOffDays($weeklyOffData)
    {
        $dayMap = [
            'Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3,
            'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6, 'Sunday' => 7
        ];
        $numericDays = [];
        
        if (is_array($weeklyOffData)) {
            foreach ($weeklyOffData as $dayName) {
                if (is_numeric($dayName)) {
                    $numericDays[] = (int)$dayName;
                } elseif (isset($dayMap[$dayName])) {
                    $numericDays[] = $dayMap[$dayName];
                } elseif (isset($dayMap[ucfirst($dayName)])) {
                    $numericDays[] = $dayMap[ucfirst($dayName)];
                }
            }
        }
        
        $numericDays = array_unique($numericDays);
        sort($numericDays);
        return !empty($numericDays) ? $numericDays : [7];
    }

    /**
     * Get current academic session
     */
    private function getCurrentAcademicSession()
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        return $currentMonth >= 4 ? $currentYear . '-' . ($currentYear + 1) : ($currentYear - 1) . '-' . $currentYear;
    }

    /**
     * Get status color
     */
    private function getStatusColor($status)
    {
        $colors = [
            'present' => 'success',
            'absent' => 'danger',
            'leave' => 'warning',
            'weekend' => 'secondary',
            'short_attendance' => 'warning',
        ];
        return $colors[$status] ?? 'secondary';
    }

    /**
     * Get status icon
     */
    private function getStatusIcon($status)
    {
        $icons = [
            'present' => 'bi-check-circle',
            'absent' => 'bi-x-circle',
            'leave' => 'bi-calendar-week',
            'weekend' => 'bi-calendar-alt',
            'short_attendance' => 'bi-hourglass-half',
        ];
        return $icons[$status] ?? 'bi-info-circle';
    }

    /**
     * Format hours to human readable format
     */
    private function formatHoursToHMS($hours)
    {
        if (!$hours) return null;
        
        $hrs = floor($hours);
        $mins = round(($hours - $hrs) * 60);
        
        if ($mins == 60) {
            $hrs++;
            $mins = 0;
        }
        
        if ($hrs > 0 && $mins > 0) {
            return $hrs . 'h ' . $mins . 'm';
        } elseif ($hrs > 0) {
            return $hrs . 'h';
        } else {
            return $mins . 'm';
        }
    }

    /**
     * Get ALL WFH requests for a month (all statuses)
     */
    private function getAllWFHRequestsForMonth($employeeId, $startDate, $endDate)
    {
        return WFHRequest::where('employee_id', $employeeId)
            ->where('is_active', true)
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function($q2) use ($startDate, $endDate) {
                      $q2->where('start_date', '<=', $startDate)
                        ->where('end_date', '>=', $endDate);
                  });
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get WFH for a specific date from collection
     */
    private function getWFHForDate($wfhRequests, $dateString)
    {
        foreach ($wfhRequests as $wfh) {
            $startDate = Carbon::parse($wfh->start_date);
            $endDate = Carbon::parse($wfh->end_date);
            $checkDate = Carbon::parse($dateString);
            
            if ($checkDate >= $startDate && $checkDate <= $endDate) {
                return [
                    'request_id' => $wfh->request_id,
                    'reason' => $wfh->reason,
                    'start_date' => $wfh->start_date,
                    'end_date' => $wfh->end_date,
                    'request_status' => $wfh->request_status,
                    'approved_at' => $wfh->approved_at,
                    'rejected_at' => $wfh->rejected_at,
                    'rejection_reason' => $wfh->rejection_reason,
                    'created_at' => $wfh->created_at,
                ];
            }
        }
        return null;
    }

    /**
     * Check if employee is on WFH for a specific date (approved only)
     */
    private function checkWFHForDate($employeeId, $date)
    {
        return WFHRequest::where('employee_id', $employeeId)
            ->where('request_status', 'approved')
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Get WFH details for a specific date (approved only)
     */
    private function getWFHDetailsForDate($employeeId, $date)
    {
        $wfhRequest = WFHRequest::where('employee_id', $employeeId)
            ->where('request_status', 'approved')
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->where('is_active', true)
            ->first();
           
        if (!$wfhRequest) {
            return null;
        }
        
        return [
            'request_id' => $wfhRequest->request_id,
            'start_date' => $wfhRequest->start_date,
            'end_date' => $wfhRequest->end_date,
            'reason' => $wfhRequest->reason,
            'approved_at' => $wfhRequest->approved_at,
        ];
    }
}