<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\EmployeeShift;
use App\Models\DepartmentShift;
use App\Models\EmployeeDetails;
use App\Models\WFHRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmployeeShiftController extends Controller
{
    /**
     * Display employee shift view with date-wise filtering
     */
    public function showemployeeshift(Request $request)
    {
        $user = Auth::user();
        $employee = EmployeeDetails::where('user_id', $user->id)->firstOrFail();
       
        // Get employee's effective shift with priority logic
        $shiftData = $this->getEmployeeEffectiveShift($employee);
        
        // Check if employee is on WFH today (any status)
        $wfhStatus = $this->getWFHStatusForToday($employee->employee_id);
        $wfhDetails = $this->getWFHDetailsForToday($employee->employee_id);
        
        // Get assignment type
        $assignmentType = $this->getAssignmentType($shiftData);
        
        // Get shift details for view
        $shiftDetails = $this->getShiftDetails($shiftData);
        
        // Add WFH information to shift details
        if ($wfhStatus && $wfhDetails) {
            $shiftDetails['is_wfh'] = true;
            $shiftDetails['wfh_details'] = $wfhDetails;
            $shiftDetails['wfh_status'] = $wfhDetails['request_status'] ?? 'unknown';
        } else {
            $shiftDetails['is_wfh'] = false;
            $shiftDetails['wfh_details'] = null;
            $shiftDetails['wfh_status'] = null;
        }
        
        // Set default date range (today only)
        $today = Carbon::now()->format('Y-m-d');
        $startDate = $request->input('start_date', $today);
        $endDate = $request->input('end_date', $today);
        
        return view('instituteAdmin.Shifts.employeeshift', compact(
            'assignmentType',
            'shiftDetails',
            'wfhStatus',
            'wfhDetails',
            'startDate',
            'endDate',
            'today'
        ));
    }
    
    /**
     * Get shifts for a date range (AJAX endpoint)
     */
    public function getShiftsByDateRange(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date'
            ]);

            $user = Auth::user();
            $employee = EmployeeDetails::where('user_id', $user->id)->firstOrFail();
            
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            
            // Get all shifts for the date range
            $shifts = $this->getShiftsForDateRange($employee, $startDate, $endDate);
            
            return response()->json([
                'success' => true,
                'shifts' => $shifts,
                'employee' => [
                    'name' => $employee->name,
                    'employee_code' => $employee->employee_code,
                    'department' => $employee->department->department_name ?? 'No Department'
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching shifts by date range: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching shifts: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get shifts for a date range - INCLUDING WFH days
     */
    private function getShiftsForDateRange($employee, $startDate, $endDate)
    {
        $shifts = [];
        $currentDate = clone $startDate;
        $instituteId = $employee->institute_id;
        $branchId = $employee->branch_id;
        
        // Get all active shifts for the employee
        $allShifts = $this->getAllEmployeeShifts($employee);
        
        // Get ALL WFH requests for the date range (not just approved)
        $wfhRequests = $this->getAllWFHRequestsForRange($employee->employee_id, $startDate, $endDate);
        
        // If no shifts and no WFH found, return empty array
        if ($allShifts->isEmpty() && $wfhRequests->isEmpty()) {
            return [];
        }
        
        // Process each day in the range
        $groups = [];
        $currentGroup = null;
        
        while ($currentDate <= $endDate) {
            $dateString = $currentDate->format('Y-m-d');
            $dayOfWeek = $currentDate->format('l');
            
            // Check if this date has a WFH request
            $wfhForDate = $this->getWFHForDate($wfhRequests, $dateString);
            $isWFHDate = $wfhForDate !== null;
            
            // Find applicable shift for this date
            $applicableShift = null;
            foreach ($allShifts as $shift) {
                $shiftStart = Carbon::parse($shift->shift->start_date);
                $shiftEnd = $shift->shift->end_date ? Carbon::parse($shift->shift->end_date) : null;
                
                if ($shiftStart <= $currentDate && ($shiftEnd === null || $shiftEnd >= $currentDate)) {
                    $applicableShift = $shift;
                    break;
                }
            }
            
            // Check if it's a weekly off
            $isWeeklyOff = false;
            $shiftName = 'No Shift Assigned';
            $startTime = null;
            $endTime = null;
            $priority = 'low';
            $breakMinutes = 0;
            $graceMinutes = 0;
            $weeklyOffs = [];
            $flexibleWorkingHours = false;
            $flexibilityLabel = 'Fixed';
            $isActive = false;
            $isUpcoming = false;
            $isCompleted = false;
            $isWFH = false;
            $wfhReason = null;
            $wfhRequestId = null;
            $wfhStatus = null;
            $wfhApprovedAt = null;
            $wfhStartDate = null;
            $wfhEndDate = null;
            
            // If this is a WFH date, use WFH details
            if ($isWFHDate) {
                $isWFH = true;
                $wfhReason = $wfhForDate['reason'] ?? 'Work From Home';
                $wfhRequestId = $wfhForDate['request_id'] ?? null;
                $wfhStatus = $wfhForDate['request_status'] ?? 'pending';
                $wfhApprovedAt = $wfhForDate['approved_at'] ?? null;
                $wfhStartDate = $wfhForDate['start_date'] ?? null;
                $wfhEndDate = $wfhForDate['end_date'] ?? null;
                
                // Use the shift details from the applicable shift if available
                if ($applicableShift && $applicableShift->shift) {
                    $shift = $applicableShift->shift;
                    $shiftName = $shift->shift_name . ' (WFH)';
                    $startTime = $shift->start_time;
                    $endTime = $shift->end_time;
                    $priority = $shift->priority;
                    $breakMinutes = $shift->break_minutes ?? 0;
                    $graceMinutes = $shift->grace_minutes ?? 0;
                    $flexibleWorkingHours = (bool) ($shift->flexible_working_hours ?? false);
                    $flexibilityLabel = $shift->flexibility_label ?? ($flexibleWorkingHours ? 'Flexible Working Hours' : 'Fixed Working Hours');
                    $flexibilityMessage = $flexibleWorkingHours
                        ? 'Complete your required working hours.'
                        : 'Shift timing will be fixed.';
                    
                    // Check weekly off
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
                    $isWeeklyOff = in_array($dayOfWeek, $weeklyOffs);
                    
                    // Determine status
                    $today = Carbon::now()->toDateString();
                    if ($shift->start_date <= $today && ($shift->end_date === null || $shift->end_date >= $today)) {
                        $isActive = true;
                    } elseif ($shift->start_date > $today) {
                        $isUpcoming = true;
                    } elseif ($shift->end_date && $shift->end_date < $today) {
                        $isCompleted = true;
                    }
                } else {
                    // No shift assigned, use default WFH details
                    $shiftName = 'Work From Home';
                    $startTime = '09:00:00';
                    $endTime = '18:00:00';
                    $priority = 'medium';
                    $breakMinutes = 60;
                    $graceMinutes = 15;
                    $isActive = true;
                }
            } elseif ($applicableShift && $applicableShift->shift) {
                // Regular shift (non-WFH)
                $shift = $applicableShift->shift;
                $shiftName = $shift->shift_name;
                $startTime = $shift->start_time;
                $endTime = $shift->end_time;
                $priority = $shift->priority;
                $breakMinutes = $shift->break_minutes ?? 0;
                $graceMinutes = $shift->grace_minutes ?? 0;
                $flexibleWorkingHours = (bool) ($shift->flexible_working_hours ?? false);
                $flexibilityLabel = $shift->flexibility_label ?? ($flexibleWorkingHours ? 'Flexible Working Hours' : 'Fixed Working Hours');
                $flexibilityMessage = $flexibleWorkingHours
                    ? 'Complete your required working hours.'
                    : 'Shift timing will be fixed.';
                
                // Check weekly off
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
                $isWeeklyOff = in_array($dayOfWeek, $weeklyOffs);
                
                // Determine status
                $today = Carbon::now()->toDateString();
                if ($shift->start_date <= $today && ($shift->end_date === null || $shift->end_date >= $today)) {
                    $isActive = true;
                } elseif ($shift->start_date > $today) {
                    $isUpcoming = true;
                } elseif ($shift->end_date && $shift->end_date < $today) {
                    $isCompleted = true;
                }
            }
            
            // Skip weekly off days and days with no shift assigned (unless it's WFH)
            if (!$isWeeklyOff && ($shiftName !== 'No Shift Assigned' || $isWFH)) {
                // Group consecutive days with same shift
                $groupKey = $shiftName . '|' . ($startTime ?? '') . '|' . ($endTime ?? '') . '|' . ($isWFH ? 'wfh' : 'regular') . '|' . ($wfhStatus ?? 'none');
                
                if ($currentGroup === null || $currentGroup['key'] !== $groupKey) {
                    if ($currentGroup !== null) {
                        $groups[] = $currentGroup;
                    }
                    $currentGroup = [
                        'key' => $groupKey,
                        'shift_name' => $shiftName,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'break_minutes' => $breakMinutes,
                        'grace_minutes' => $graceMinutes,
                        'priority' => $priority,
                        'flexible_working_hours' => $flexibleWorkingHours,
                        'flexibility_label' => $flexibilityLabel,
                        'weekly_off_days' => $weeklyOffs,
                        'assignment_type' => $applicableShift ? $applicableShift->type : 'individual',
                        'shift_start_date' => $dateString,
                        'shift_end_date' => $dateString,
                        'days_count' => 1,
                        'dates' => [$dateString],
                        'is_active' => $isActive,
                        'is_upcoming' => $isUpcoming,
                        'is_completed' => $isCompleted,
                        'duration' => $this->calculateShiftDuration($startTime, $endTime),
                        'is_wfh' => $isWFH,
                        'wfh_reason' => $wfhReason,
                        'wfh_request_id' => $wfhRequestId,
                        'wfh_status' => $wfhStatus,
                        'wfh_approved_at' => $wfhApprovedAt,
                        'wfh_start_date' => $wfhStartDate,
                        'wfh_end_date' => $wfhEndDate
                    ];
                } else {
                    // Extend the current group
                    $currentGroup['shift_end_date'] = $dateString;
                    $currentGroup['days_count']++;
                    $currentGroup['dates'][] = $dateString;
                    // Update status if any day is active
                    if ($isActive) {
                        $currentGroup['is_active'] = true;
                    }
                    if ($isUpcoming) {
                        $currentGroup['is_upcoming'] = true;
                    }
                    if ($isCompleted) {
                        $currentGroup['is_completed'] = true;
                    }
                }
            }
            
            $currentDate->addDay();
        }
        
        // Add the last group
        if ($currentGroup !== null) {
            $groups[] = $currentGroup;
        }
        
        // Remove the 'key' and 'dates' fields before returning
        foreach ($groups as &$group) {
            unset($group['key']);
            unset($group['dates']);
        }
        
        return $groups;
    }

    /**
     * Get ALL WFH requests for a date range (including pending, rejected, approved)
     */
    private function getAllWFHRequestsForRange($employeeId, $startDate, $endDate)
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
     * Get WFH details for a specific date
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
                    'created_at' => $wfh->created_at,
                    'rejected_at' => $wfh->rejected_at,
                    'rejection_reason' => $wfh->rejection_reason,
                ];
            }
        }
        return null;
    }
    
    /**
     * Get WFH status for today (any status)
     */
    private function getWFHStatusForToday($employeeId)
    {
        $today = Carbon::now()->toDateString();
        
        return WFHRequest::where('employee_id', $employeeId)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->exists();
    }
    
    /**
     * Get WFH details for today (all statuses)
     */
    private function getWFHDetailsForToday($employeeId)
    {
        $today = Carbon::now()->toDateString();
        
        $wfhRequest = WFHRequest::where('employee_id', $employeeId)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->orderBy('created_at', 'desc')
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
            'rejected_at' => $wfhRequest->rejected_at,
            'rejection_reason' => $wfhRequest->rejection_reason,
            'request_status' => $wfhRequest->request_status,
            'created_at' => $wfhRequest->created_at,
        ];
    }
    
    /**
     * Get WFH dates for an employee within a date range
     */
    private function getWFHDatesForRange($employeeId, $startDate, $endDate)
    {
        $wfhDates = [];
        
        $wfhRequests = WFHRequest::where('employee_id', $employeeId)
            ->where('is_active', true)
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                ->orWhereBetween('end_date', [$startDate, $endDate])
                ->orWhere(function($q2) use ($startDate, $endDate) {
                    $q2->where('start_date', '<=', $startDate)
                        ->where('end_date', '>=', $endDate);
                });
            })
            ->get();
        
        foreach ($wfhRequests as $wfh) {
            $wfhStart = Carbon::parse($wfh->start_date);
            $wfhEnd = Carbon::parse($wfh->end_date);
            
            // If WFH request covers the entire range or part of it
            $current = $wfhStart->copy();
            while ($current <= $wfhEnd && $current <= $endDate) {
                if ($current >= $startDate) {
                    $wfhDates[$current->format('Y-m-d')] = [
                        'date' => $current->format('Y-m-d'),
                        'status' => $wfh->request_status,
                        'reason' => $wfh->reason,
                        'request_id' => $wfh->request_id,
                        'approved_at' => $wfh->approved_at,
                    ];
                }
                $current->addDay();
            }
        }
        
        return $wfhDates;
    }
    
    /**
     * Calculate shift duration
     */
    private function calculateShiftDuration($startTime, $endTime)
    {
        if (!$startTime || !$endTime) return 'N/A';
        
        try {
            $start = Carbon::parse($startTime);
            $end = Carbon::parse($endTime);
            $hours = $start->diffInHours($end);
            $minutes = $start->diffInMinutes($end) % 60;
            $duration = $hours . 'h';
            if ($minutes > 0) $duration .= " {$minutes}m";
            return $duration;
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    /**
     * Get all employee shifts (both individual and department)
     */
    private function getAllEmployeeShifts($employee)
    {
        $instituteId = $employee->institute_id;
        $branchId = $employee->branch_id;

        // Individual shifts
        $individualShifts = EmployeeShift::where('institute_id', $instituteId)
            ->where('employee_id', $employee->employee_id)
            ->where('status', 'active')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId),
                            fn($q) => $q->whereNull('branch_id'))
            ->with('shift')
            ->get()
            ->filter(fn($s) => $s->shift !== null)
            ->each(function ($shift) {
                $shift->type = 'individual';
                $shift->priority_value = $this->getPriorityValue($shift->shift->priority);
            });

        // Department shifts
        $departmentShifts = collect([]);
        if ($employee->department_id) {
            $departmentShifts = DepartmentShift::where('institute_id', $instituteId)
                ->where('department_id', $employee->department_id)
                ->where('status', 'active')
                ->where('shift_type', 'employee')
                ->when($branchId, fn($q) => $q->where('branch_id', $branchId),
                                fn($q) => $q->whereNull('branch_id'))
                ->with('shift')
                ->get()
                ->filter(fn($s) => $s->shift !== null)
                ->each(function ($shift) {
                    $shift->type = 'department';
                    $shift->priority_value = $this->getPriorityValue($shift->shift->priority);
                });
        }

        // Merge and sort by priority
        $allShifts = $individualShifts->merge($departmentShifts);
        
        // Sort by priority (high to low) and then by created_at
        $sorted = $allShifts->sortByDesc(function ($s) {
            return [$s->priority_value, $s->created_at->timestamp];
        });

        return $sorted;
    }

    /**
     * Check if employee is on WFH today (only approved)
     */
    private function checkWFHStatus($employeeId)
    {
        $today = Carbon::now()->toDateString();
        
        return WFHRequest::where('employee_id', $employeeId)
            ->where('request_status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->where('is_active', true)
            ->exists();
    }
    
    /**
     * Get WFH details for the employee (only approved)
     */
    private function getWFHDetails($employeeId)
    {
        $today = Carbon::now()->toDateString();
        
        $wfhRequest = WFHRequest::where('employee_id', $employeeId)
            ->where('request_status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
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
            'status' => $wfhRequest->request_status,
        ];
    }
        
    private function getEmployeeEffectiveShift($employee)
    {
        $currentDate = Carbon::now()->toDateString();
        $instituteId = $employee->institute_id;
        $branchId = $employee->branch_id;

        // 1. INDIVIDUAL SHIFTS - ONLY get active shifts within date range
        $individualShifts = EmployeeShift::where('institute_id', $instituteId)
            ->where('employee_id', $employee->employee_id)
            ->where('status', 'active')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId),
                            fn($q) => $q->whereNull('branch_id'))
            ->with(['shift' => function($q) use ($currentDate) {
                $q->where('start_date', '<=', $currentDate)  // Shift has started
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

        // 2. DEPARTMENT SHIFTS - ONLY get active shifts within date range
        $departmentShifts = collect([]);

        if ($employee->department_id) {
            $departmentShifts = DepartmentShift::where('institute_id', $instituteId)
                ->where('department_id', $employee->department_id)
                ->where('status', 'active')
                ->where('shift_type', 'employee')
                ->when($branchId, fn($q) => $q->where('branch_id', $branchId),
                                fn($q) => $q->whereNull('branch_id'))
                ->with(['shift' => function($q) use ($currentDate) {
                    $q->where('start_date', '<=', $currentDate)  // Shift has started
                    ->where(function($q2) use ($currentDate) {
                        $q2->where('end_date', '>=', $currentDate)  // Shift hasn't ended
                            ->orWhereNull('end_date');  // Or has no end date
                    });
                }])
                ->get()
                ->filter(fn($s) => $s->shift !== null)
                ->each(function ($shift) {
                    $shift->type = 'department';
                    $shift->priority_value = $this->getPriorityValue($shift->shift->priority);
                });
        }

        // 3. MERGE BOTH
        $allShifts = $individualShifts->merge($departmentShifts);

        if ($allShifts->isEmpty()) return null;

        // 4. SORT: priority DESC → created_at DESC
        $sorted = $allShifts->sortByDesc(function ($s) {
            return [$s->priority_value, $s->created_at->timestamp];
        });

        return $sorted->first();
    }

    /**
     * Convert priority string to numeric value
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
     * Determine assignment type for display
     */
    private function getAssignmentType($shiftData)
    {
        if (!$shiftData) {
            return 'holiday'; // No shift assigned
        }
        
        return $shiftData['type']; // 'individual' or 'department'
    }
    
    /**
     * Get shift details with conflict information
     */
    private function getShiftDetails($shiftData)
    {
        if (!$shiftData) {
            return [
                'message' => 'No shift assigned',
                'is_holiday' => true,
                'conflicts' => [],
                'is_wfh' => false,
                'wfh_details' => null
            ];
        }

        $shift = $shiftData->shift;
        $currentDate = Carbon::now()->toDateString();
        $flexibleWorkingHours = (bool) ($shift->flexible_working_hours ?? false);
        $flexibilityMessage = $flexibleWorkingHours
            ? 'Complete your required working hours.'
            : 'Shift timing will be fixed.';

        // Active check
        $isActive = true;

        if ($shift->start_date && Carbon::parse($shift->start_date)->gt($currentDate)) {
            $isActive = false;
        }

        if ($shift->end_date && Carbon::parse($shift->end_date)->lt($currentDate)) {
            $isActive = false;
        }

        // Duration
        $duration = 'N/A';
        if ($shift->start_time && $shift->end_time) {
            $start = Carbon::parse($shift->start_time);
            $end   = Carbon::parse($shift->end_time);

            $hours = $start->diffInHours($end);
            $minutes = $start->diffInMinutes($end) % 60;

            $duration = $hours . 'h';
            if ($minutes > 0) $duration .= " {$minutes}m";
        }

        // Weekly offs
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
            'shift_id'       => $shift->id,
            'shift_name'     => $shift->shift_name,
            'start_time'     => $shift->start_time,
            'end_time'       => $shift->end_time,
            'break_minutes'  => $shift->break_minutes,
            'grace_minutes'  => $shift->grace_minutes,
            'weekly_off_days'=> $weeklyOffs,
            'priority'       => $shift->priority,
            'start_date'     => $shift->start_date,
            'end_date'       => $shift->end_date,
            'flexible_working_hours' => (bool) ($shift->flexible_working_hours ?? false),
            'flexibility_label' => $shift->flexibility_label ?? ((bool) ($shift->flexible_working_hours ?? false) ? 'Flexible Working Hours' : 'Fixed Working Hours'),
            'flexibility_message' => $flexibilityMessage,
            'is_active'      => $isActive,
            'is_holiday'     => false,
            'assignment_type'=> $shiftData->type,
            'duration'       => $duration,
            'assignment_date'=> $shiftData->created_at
        ];
    }

    /**
     * Get employee's shift timeline/history
     */
    public function getShiftHistory()
    {
        try {
            $user = Auth::user();
            $employee = EmployeeDetails::where('user_id', $user->id)->firstOrFail();
            $instituteId = $employee->institute_id;
            $currentDate = Carbon::now()->toDateString();

            // Individual shift history - get ALL shifts (active, inactive, future)
            $individualHistory = EmployeeShift::where('institute_id', $instituteId)
                ->where('employee_id', $employee->employee_id)
                ->with('shift')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($employeeShift) use ($currentDate) {
                    $shift = $employeeShift->shift;
                    
                    // Determine shift status
                    $status = $employeeShift->status; // 'active' or 'inactive'
                    
                    // Check if shift is expired based on date
                    if ($shift) {
                        if ($shift->end_date && Carbon::parse($shift->end_date)->lt($currentDate)) {
                            $status = 'completed';
                        } elseif ($shift->start_date && Carbon::parse($shift->start_date)->gt($currentDate)) {
                            $status = 'upcoming';
                        }
                    }
                    
                    return [
                        'type'           => 'individual',
                        'shift'          => $shift ? $this->formatShiftForHistory($shift) : null,
                        'shift_id'       => $employeeShift->shift_id,
                        'status'         => $status,
                        'assignment_type'=> $employeeShift->assignment_type,
                        'assigned_at'    => $employeeShift->created_at,
                        'assigned_ts'    => $employeeShift->created_at ? $employeeShift->created_at->timestamp : null,
                        'ended_at'       => $employeeShift->deleted_at,
                        'ended_ts'       => $employeeShift->deleted_at ? $employeeShift->deleted_at->timestamp : null,
                    ];
                })
                ->toBase();

            // Department shift history - get ALL shifts
            $departmentHistory = collect([]);
            if ($employee->department_id) {
                $departmentHistory = DepartmentShift::where('institute_id', $instituteId)
                    ->where('department_id', $employee->department_id)
                    ->where('shift_type', 'employee')
                    ->with('shift')
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function($departmentShift) use ($currentDate) {
                        $shift = $departmentShift->shift;
                        
                        // Determine shift status
                        $status = $departmentShift->status;
                        
                        if ($shift) {
                            if ($shift->end_date && Carbon::parse($shift->end_date)->lt($currentDate)) {
                                $status = 'completed';
                            } elseif ($shift->start_date && Carbon::parse($shift->start_date)->gt($currentDate)) {
                                $status = 'upcoming';
                            }
                        }
                        
                        return [
                            'type'        => 'department',
                            'shift'       => $shift ? $this->formatShiftForHistory($shift) : null,
                            'shift_id'    => $departmentShift->shift_id,
                            'status'      => $status,
                            'assigned_at' => $departmentShift->created_at,
                            'assigned_ts' => $departmentShift->created_at ? $departmentShift->created_at->timestamp : null,
                            'ended_at'    => $departmentShift->deleted_at,
                            'ended_ts'    => $departmentShift->deleted_at ? $departmentShift->deleted_at->timestamp : null,
                        ];
                    })
                    ->toBase();
            }

            // Merge and sort
            $history = $individualHistory
                ->merge($departmentHistory)
                ->sortByDesc(function ($item) {
                    return $item['assigned_ts'] ?? ($item['assigned_at'] ? $item['assigned_at']->timestamp : 0);
                })
                ->values()
                ->all();

            return response()->json([
                'success' => true,
                'history' => $history,
                'employee' => [
                    'name' => $employee->name,
                    'employee_code' => $employee->employee_code,
                    'department' => $employee->department->department_name ?? 'No Department'
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getShiftHistory: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading shift history'
            ]);
        }
    }

    /**
     * Format shift data for history display
     */
    private function formatShiftForHistory($shift)
    {
        if (!$shift) return null;
        
        $flexibleWorkingHours = (bool) ($shift->flexible_working_hours ?? false);
        $flexibilityMessage = $flexibleWorkingHours
            ? 'Complete your working hours as required.'
            : 'Shift timing will be fixed.';

        return [
            'id' => $shift->id,
            'shift_name' => $shift->shift_name,
            'start_time' => $shift->start_time,
            'end_time' => $shift->end_time,
            'start_date' => $shift->start_date,
            'end_date' => $shift->end_date,
            'break_minutes' => $shift->break_minutes,
            'grace_minutes' => $shift->grace_minutes,
            'priority' => $shift->priority,
            'weekly_off_days' => $shift->weekly_off_days,
            'flexible_working_hours' => $flexibleWorkingHours,
            'flexibility_label' => $flexibleWorkingHours ? 'Flexible Working Hours' : 'Fixed Working Hours',
            'flexibility_message' => $flexibilityMessage,
            'created_at' => $shift->created_at,
            'updated_at' => $shift->updated_at,
        ];
    }
    
    /**
     * Get today's shift for the employee
     */
    public function getTodayShift()
    {
        try {
            $user = Auth::user();
            $employee = EmployeeDetails::where('user_id', $user->id)->firstOrFail();
            
            $shiftData = $this->getEmployeeEffectiveShift($employee);
            $isWFH = $this->checkWFHStatus($employee->employee_id);
            $wfhDetails = $this->getWFHDetails($employee->employee_id);
            
            if (!$shiftData) {
                return response()->json([
                    'success' => true,
                    'has_shift' => false,
                    'message' => 'No shift assigned for today',
                    'is_wfh' => $isWFH,
                    'wfh_details' => $wfhDetails
                ]);
            }
            
            $shiftDetails = $this->getShiftDetails($shiftData);
            
            // Add WFH information
            if ($isWFH && $wfhDetails) {
                $shiftDetails['is_wfh'] = true;
                $shiftDetails['wfh_details'] = $wfhDetails;
            } else {
                $shiftDetails['is_wfh'] = false;
                $shiftDetails['wfh_details'] = null;
            }
            
            return response()->json([
                'success' => true,
                'has_shift' => true,
                'shift' => $shiftDetails,
                'assignment_type' => $shiftData['type'],
                'is_wfh' => $isWFH,
                'wfh_details' => $wfhDetails
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getTodayShift: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching shift information'
            ]);
        }
    }

    /**
     * Get employee's upcoming shifts
     */
    public function getUpcomingShifts()
    {
        try {
            $user = Auth::user();
            $employee = EmployeeDetails::where('user_id', $user->id)->firstOrFail();
            $instituteId = $employee->institute_id;
            $currentDate = Carbon::now()->toDateString();

            // Individual upcoming shifts
            $individualUpcoming = EmployeeShift::where('institute_id', $instituteId)
                ->where('employee_id', $employee->employee_id)
                ->where('status', 'active')
                ->with(['shift' => function($q) use ($currentDate) {
                    $q->where('start_date', '>', $currentDate)  // Future shifts only
                    ->orderBy('start_date', 'asc');
                }])
                ->get()
                ->filter(fn($s) => $s->shift !== null)
                ->map(function($employeeShift) use ($currentDate) {
                    $shift = $employeeShift->shift;
                    $daysUntil = Carbon::parse($shift->start_date)->diffInDays($currentDate);
                    
                    return [
                        'type' => 'individual',
                        'shift' => $this->formatShiftForHistory($shift),
                        'shift_id' => $employeeShift->shift_id,
                        'status' => 'upcoming',
                        'assigned_at' => $employeeShift->created_at,
                        'days_until' => $daysUntil . ' days',
                    ];
                })
                ->toBase();

            // Department upcoming shifts
            $departmentUpcoming = collect([]);
            if ($employee->department_id) {
                $departmentUpcoming = DepartmentShift::where('institute_id', $instituteId)
                    ->where('department_id', $employee->department_id)
                    ->where('shift_type', 'employee')
                    ->where('status', 'active')
                    ->with(['shift' => function($q) use ($currentDate) {
                        $q->where('start_date', '>', $currentDate)  // Future shifts only
                        ->orderBy('start_date', 'asc');
                    }])
                    ->get()
                    ->filter(fn($s) => $s->shift !== null)
                    ->map(function($departmentShift) use ($currentDate) {
                        $shift = $departmentShift->shift;
                        $daysUntil = Carbon::parse($shift->start_date)->diffInDays($currentDate);
                        
                        return [
                            'type' => 'department',
                            'shift' => $this->formatShiftForHistory($shift),
                            'shift_id' => $departmentShift->shift_id,
                            'status' => 'upcoming',
                            'assigned_at' => $departmentShift->created_at,
                            'days_until' => $daysUntil . ' days',
                        ];
                    })
                    ->toBase();
            }

            // Merge and sort by start date
            $upcoming = $individualUpcoming
                ->merge($departmentUpcoming)
                ->sortBy(function ($item) {
                    return $item['shift']['start_date'] ?? 0;
                })
                ->values()
                ->all();

            return response()->json([
                'success' => true,
                'upcoming' => $upcoming,
                'employee' => [
                    'name' => $employee->name,
                    'employee_code' => $employee->employee_code,
                    'department' => $employee->department->department_name ?? 'No Department'
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getUpcomingShifts: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading upcoming shifts'
            ]);
        }
    }
}