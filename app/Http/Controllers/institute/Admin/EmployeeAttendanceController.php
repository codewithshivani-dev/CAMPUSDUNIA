<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeAttendanceLogs;
use App\Models\EmployeeDetails;
use App\Models\EmployeeLeave;
use App\Models\AttendanceReview;
use App\Models\EmployeeLeaveBalance;
use App\Models\Departments;
use App\Models\Shifts;
use Carbon\Carbon;
use DB;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\MonthlyAttendanceExport;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeAttendanceController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;
    public function showAttendance(Request $request)
    {
        $userId = auth()->id();
            if (!$userId) {
                return redirect()->route('login')->with('error', 'Please login first.');
            }
            $employee = EmployeeDetails::where('user_id', $userId)->first();
        if (!$employee) {
            return back()->with('error', 'Employee record not found.');
        }
        // employee_id is the code like EMPB410D1A3
        $employeeId = $employee->employee_id;
        $todayIST = Carbon::today('Asia/Kolkata')->toDateString();
        $startUTC = Carbon::parse($todayIST . ' 00:00:00', 'Asia/Kolkata')->setTimezone('UTC');
        $endUTC = Carbon::parse($todayIST . ' 23:59:59', 'Asia/Kolkata')->setTimezone('UTC');
        $attendance = EmployeeAttendance::where('employee_id', $employeeId)
            ->whereBetween('created_at', [$startUTC, $endUTC])
            ->with(['logs' => function ($q) {
                $q->orderBy('check_time');
            }])
            ->first();
        if ($attendance) {
            $attendance->logs->transform(function ($log) {
                $log->check_time = Carbon::parse($log->check_time, 'UTC')->setTimezone('Asia/Kolkata');
                return $log;
            });
        }
        return view('instituteAdmin.EmployeeFiles.ViewEmpAttendance', compact('attendance', 'employee'));
    }
    
    // Check-in
    public function checkIn(Request $request)
    {
        $employeeId = $request->employee_id;
        $todayUTC = Carbon::today('UTC')->toDateString();
        $nowUTC = Carbon::now('UTC');

        // Get institute and branch context from the trait
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            return response()->json(['error' => 'You are not associated with any institute.'], 422);
        }
        
        $instituteId = $context['institute_id'];
        $branchId = $context['branch_id'] ?? null;

        // Create or get today's attendance with institute/branch context
        $attendance = EmployeeAttendance::firstOrCreate(
            [
                'employee_id' => $employeeId, 
                'date' => $todayUTC,
                'institute_id' => $instituteId,
                'branch_id' => $branchId
            ],
            [
                'status' => 'Present', 
                'total_hours' => 0,
                'institute_id' => $instituteId,
                'branch_id' => $branchId
            ]
        );

        // Prevent duplicate check-in
        $existingInLog = EmployeeAttendanceLogs::where('attendance_id', $attendance->id)
            ->where('check_type', 'IN')
            ->exists();

        if ($existingInLog) {
            return response()->json(['error' => 'Already checked in today'], 422);
        }

        // Store in UTC with institute/branch context
        $log = EmployeeAttendanceLogs::create([
            'attendance_id' => $attendance->id,
            'check_type' => 'IN',
            'check_time' => $nowUTC,
            'ip_address' => $request->getClientIp(),
            'device_info' => $request->header('User-Agent'),
            'location' => $request->location ?? null,
            'institute_id' => $instituteId,
            'branch_id' => $branchId,
        ]);

        return response()->json([
            'success' => 'Check-in recorded',
            'log_time' => Carbon::parse($log->check_time, 'UTC')->setTimezone('Asia/Kolkata')->format('H:i:s'),
            'institute_id' => $instituteId,
            'branch_id' => $branchId,
        ]);
    }

    public function checkOut(Request $request)
    {
        $employeeId = $request->employee_id;
        $todayUTC = Carbon::today('UTC')->toDateString();
        $nowUTC = Carbon::now('UTC');

        // Get institute and branch context from the trait
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            return response()->json(['error' => 'You are not associated with any institute.'], 422);
        }
        
        $instituteId = $context['institute_id'];
        $branchId = $context['branch_id'] ?? null;

        // Find attendance with institute/branch context
        $attendance = EmployeeAttendance::where('employee_id', $employeeId)
            ->where('date', $todayUTC)
            ->where('institute_id', $instituteId)
            ->when($branchId, function($query) use ($branchId) {
                return $query->where('branch_id', $branchId);
            })
            ->first();

        if (!$attendance) {
            return response()->json(['error' => 'No attendance for today'], 422);
        }

        $existingOutLog = EmployeeAttendanceLogs::where('attendance_id', $attendance->id)
            ->where('check_type', 'OUT')
            ->first();

        if ($existingOutLog) {
            return response()->json(['error' => 'Already checked out today'], 422);
        }

        // Save log in UTC with institute/branch context
        $log = EmployeeAttendanceLogs::create([
            'attendance_id' => $attendance->id,
            'check_type' => 'OUT',
            'check_time' => $nowUTC,
            'ip_address' => $request->ip(),
            'device_info' => $request->header('User-Agent'),
            'location' => $request->location ?? null,
            'institute_id' => $instituteId,
            'branch_id' => $branchId,
        ]);

        // Calculate total hours in IST
        $logs = EmployeeAttendanceLogs::where('attendance_id', $attendance->id)
            ->orderBy('check_time')
            ->get();

        $totalMinutes = 0;
        $lastIn = null;
        $lastOutIST = null;

        foreach ($logs as $l) {
            $logTimeIST = Carbon::parse($l->check_time, 'UTC')->setTimezone('Asia/Kolkata');
            if ($l->check_type === 'IN') {
                $lastIn = $logTimeIST;
            } elseif ($l->check_type === 'OUT' && $lastIn) {
                $totalMinutes += $lastIn->diffInMinutes($logTimeIST);
                $lastOutIST = $logTimeIST;
                $lastIn = null;
            }
        }

        $attendance->total_hours = round($totalMinutes / 60, 2);

        // ----------------------------
        // SHIFT STATUS COMPARISON (in IST)
        // ----------------------------
        $status = 'Present';
        
        // Get employee shift with institute context
        $employee = EmployeeDetails::where('employee_id', $employeeId)
            ->where('institute_id', $instituteId)
            ->when($branchId, function($query) use ($branchId) {
                return $query->where('branch_id', $branchId);
            })
            ->with('shift')
            ->first();
            
       $shiftData = $employee->resolveEffectiveShift(Carbon::today('Asia/Kolkata'));
         $shift = $shiftData['shift'] ?? null;

        if ($shift) {
            $shiftStartIST = Carbon::today('Asia/Kolkata')->setTimeFromTimeString($shift->start_time);
            $shiftEndIST   = Carbon::today('Asia/Kolkata')->setTimeFromTimeString($shift->end_time);

            if ($shiftEndIST->lessThanOrEqualTo($shiftStartIST)) {
                $shiftEndIST->addDay(); // for overnight shifts
            }

            $earlyLeaveThreshold = $shiftEndIST->copy()->subMinutes(10);

            // Use last OUT time in IST
            $compareTime = $lastOutIST ?? Carbon::now('Asia/Kolkata');

            if ($attendance->total_hours <= 0) {
                $status = 'Absent';
            } elseif ($compareTime->lt($earlyLeaveThreshold)) {
                $status = 'Early Leave';
            } elseif ($compareTime->gt($shiftEndIST)) {
                $status = 'Overtime';
            } else {
                $status = 'Present';
            }
        }

        $attendance->status = $status;
        $attendance->save();

        return response()->json([
            'success' => 'Check-out recorded successfully',
            'total_hours' => $attendance->total_hours,
            'status' => $attendance->status,
            'institute_id' => $instituteId,
            'branch_id' => $branchId,
            'log' => $log
        ]);
    }

    public function monthlyAttendance(Request $request)
    {
        $merchantId = auth()->user()->institute_id;
    
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $departmentId = $request->get('department_id');
        $employeeId = $request->get('employee_id');
        $shiftId = $request->get('shift_id');
        $statusFilter = $request->get('status');
    
        // ---------- BASE EMPLOYEE QUERY (SINGLE SOURCE OF TRUTH) ----------
        $employeesQuery = EmployeeDetails::query()
            ->where('employee_details.institute_id', operator: $merchantId)
            ->where('employee_details.status', 'active')
            ->leftJoin('departments', 'departments.department_id', '=', 'employee_details.department_id')
            ->select([
                'employee_details.*',
                'departments.department as department_name',
                'departments.department_id'
            ]);
    
        if ($departmentId) {
            $employeesQuery->where('departments.department_id', $departmentId);
        }
    
        if ($shiftId) {
            $employeesQuery->where(function ($query) use ($shiftId) {
                $query->whereHas('employeeShifts', function ($q) use ($shiftId) {
                    $q->where('shift_id', $shiftId)
                    ->where('status', 'active');
                })->orWhereHas('department.departmentShifts', function ($q) use ($shiftId) {
                    $q->where('shift_id', $shiftId)
                    ->where('status', 'active')
                    ->where('shift_type', 'employee');
                });
            });
        }
    
        if ($employeeId) {
            $employeesQuery->where('employee_id', $employeeId);
        }
    
        // FINAL EMPLOYEES (NO DUPLICATES, NO LOST RELATIONS)
        // $employees = $employeesQuery->get();
        $employees = $employeesQuery->paginate(15);
        
        $employees->getCollection()->transform(function ($employee) use ($month, $year) {
        
            $date = Carbon::create($year, $month, 1);
            $shiftData = $employee->resolveEffectiveShift($date);
        
            $employee->resolved_shift = $shiftData['shift'] ?? null;
            $employee->resolved_shift_type = $shiftData['type'] ?? null;
        
            if ($employee->resolved_shift && isset($employee->resolved_shift->weekly_off_days)) {
        
                if (is_string($employee->resolved_shift->weekly_off_days)) {
                    $decoded = json_decode($employee->resolved_shift->weekly_off_days, true);
                    $employee->resolved_shift->weekly_off_days = is_array($decoded) ? $decoded : [];
                }
        
                if (!empty($employee->resolved_shift->weekly_off_days)) {
        
                    $dayMap = [
                        'Monday'=>1,'Tuesday'=>2,'Wednesday'=>3,
                        'Thursday'=>4,'Friday'=>5,'Saturday'=>6,'Sunday'=>7
                    ];
        
                    $numericDays = [];
        
                    foreach ($employee->resolved_shift->weekly_off_days as $dayName) {
                        if (isset($dayMap[$dayName])) {
                            $numericDays[] = $dayMap[$dayName];
                        }
                    }
        
                    $employee->resolved_shift->week_off_days = $numericDays;
        
                } else {
                    $employee->resolved_shift->week_off_days = [7];
                }
        
            } elseif ($employee->resolved_shift) {
                $employee->resolved_shift->week_off_days = [7];
            }
        
            return $employee;
        });
    
        // Also keep all employees for summary & dropdown counts
        $allEmployees = EmployeeDetails::where('institute_id', $merchantId)
            ->where('status', 'active')
            ->get();
    
        // ---------- DATE RANGE ----------
        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $today = now()->toDateString();
    
        $dates = collect();
        $workingDates = collect();
    
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates->push($date->toDateString());
    
            if (!$date->isWeekend()) {
                $workingDates->push($date->toDateString());
            }
        }
    
        // ---------- ATTENDANCE (SINGLE QUERY + GROUP) ----------
        $attendance = EmployeeAttendance::whereBetween('date', [$startDate, $endDate])
            ->where('institute_id', $merchantId)
            ->with(['logs' => function ($q) {
                $q->orderBy('check_time');
            }])
            ->get()
            ->groupBy(fn ($item) =>
                $item->employee_id . '_' . Carbon::parse($item->date)->toDateString()
            );
    
        // ---------- APPROVED LEAVES ----------
        $leaves = EmployeeLeave::whereBetween('start_date', [$startDate, $endDate])
            ->where('institute_id', $merchantId)
            ->where('final_status', 'Approved')
            ->get();
    
        $leaveData = [];
    
        foreach ($leaves as $leave) {
            $currentDate = Carbon::parse($leave->start_date);
            $endDateLeave = Carbon::parse($leave->end_date);
    
            while ($currentDate->lte($endDateLeave)) {
                if (!$currentDate->isWeekend()) {
                    $key = $leave->employee_id . '_' . $currentDate->toDateString();
                    $leaveData[$key] = $leave;
                }
                $currentDate->addDay();
            }
        }
    
        // ---------- TODAY SUMMARY ----------
        $todaySummary = $this->getTodayAttendanceSummary($allEmployees);
    
        // ---------- MONTHLY SUMMARY ----------
        $summaryEmployees = $statusFilter
            ? $this->filterEmployeesByStatus(
                $employees->getCollection(),
                $statusFilter,
                $today,
                $attendance,
                $leaveData
            )
            : $employees->getCollection();
    
        $monthlySummary = $this->getMonthlySummary(
            $summaryEmployees,
            $startDate,
            min($today, $endDate->toDateString()),
            $attendance,
            $leaveData
        );
    
        // Apply status filter on display list
        if ($statusFilter) {
        
            $filtered = $this->filterEmployeesByStatus(
                $employees->getCollection(),
                $statusFilter,
                $today,
                $attendance,
                $leaveData
            );
        
            $employees->setCollection($filtered);
        }
    
        // ---------- FILTER DROPDOWNS ----------
        $departments = Departments::where('institute_id', $merchantId)->get();
    
        $shifts = Shifts::where('institute_id', $merchantId)
            ->where('is_active', 1)
            ->get();
    
        return view('instituteAdmin.EmployeeFiles.viewEmployeeMonthlyAttendance', compact(
            'employees',
            'allEmployees',
            'dates',
            'workingDates',
            'attendance',
            'leaveData',
            'month',
            'year',
            'departments',
            'departmentId',
            'employeeId',
            'statusFilter',
            'todaySummary',
            'monthlySummary',
            'shifts',
            'shiftId'
        ));
    }

    public function getCurrentShiftAttribute()
    {
        $currentDate = Carbon::now()->toDateString();
        
        // Get individual shift
        $individualShift = $this->employeeShifts()
            ->where('status', 'active')
            ->whereHas('shift', function($q) use ($currentDate) {
                $q->where('start_date', '<=', $currentDate)
                ->where(function($q2) use ($currentDate) {
                    $q2->where('end_date', '>=', $currentDate)
                        ->orWhereNull('end_date');
                });
            })
            ->with('shift')
            ->first();

        $shiftData = $employee->resolveEffectiveShift(Carbon::today('Asia/Kolkata'));
        $shift = $shiftData['shift'] ?? null;    
        
        if ($individualShift) {
            return [
                'type' => 'individual',
                'shift' => $individualShift->shift
            ];
        }
        
        // Get department shift
        if ($this->department_id) {
            $departmentShift = DepartmentShift::where('department_id', $this->department_id)
                ->where('status', 'active')
                ->where('shift_type', 'employee')
                ->whereHas('shift', function($q) use ($currentDate) {
                    $q->where('start_date', '<=', $currentDate)
                    ->where(function($q2) use ($currentDate) {
                        $q2->where('end_date', '>=', $currentDate)
                            ->orWhereNull('end_date');
                    });
                })
                ->with('shift')
                ->first();
            
            if ($departmentShift) {
                return [
                    'type' => 'department',
                    'shift' => $departmentShift->shift
                ];
            }
        }
        
        return null;
    }

    private function getTodayAttendanceSummary($employees)
    {
        $today = now()->toDateString();
        $context = $this->getInstituteBranchContext();
        
        $present = 0;
        $absent = 0;
        $onLeave = 0;
        $total = count($employees);

        foreach ($employees as $employee) {
            // Check attendance for today
            $attendance = EmployeeAttendance::where('employee_id', $employee->employee_id)
                ->whereDate('date', $today)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            if ($attendance && $attendance->status === 'Present') {
                $present++;
            } else {
                // Check for approved leaves for today
                $leave = EmployeeLeave::where('employee_id', $employee->employee_id)
                    ->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today)
                    ->where('final_status', 'Approved')
                    ->where('institute_id', $context['institute_id'])
                    ->first();

                if ($leave) {
                    $onLeave++;
                } else {
                    $absent++;
                }
            }
        }

        return [
            'present' => $present,
            'absent' => $absent,
            'on_leave' => $onLeave,
            'total' => $total,
            'date' => Carbon::parse($today)->format('d M Y')
        ];
    }

    private function getMonthlySummary($employees, $startDate, $endDate, $attendance, $leaveData)
    {
        $currentDate = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        $totalPresent = 0;
        $totalAbsent = 0;
        $totalLeave = 0;
        $workingDays = 0;

        while ($currentDate->lte($end)) {
            if (!$currentDate->isWeekend()) {
                $workingDays++;
                
                foreach ($employees as $employee) {
                    $key = $employee->employee_id . '_' . $currentDate->toDateString();
                    
                    if (isset($attendance[$key][0]) && $attendance[$key][0]->status === 'Present') {
                        $totalPresent++;
                    } elseif (isset($leaveData[$key])) {
                        $totalLeave++;
                    } else {
                        $totalAbsent++;
                    }
                }
            }
            $currentDate->addDay();
        }

        $totalEmployeeDays = $workingDays * count($employees);
        
        return [
            'present' => $totalPresent,
            'absent' => $totalAbsent,
            'on_leave' => $totalLeave,
            'working_days' => $workingDays,
            'avg_attendance' => $totalEmployeeDays > 0 ? round(($totalPresent / $totalEmployeeDays) * 100, 1) : 0,
            'total_employee_days' => $totalEmployeeDays
        ];
    }

    private function filterEmployeesByStatus($employees, $status, $today, $attendance, $leaveData)
    {
        return $employees->filter(function ($employee) use ($status, $today, $attendance, $leaveData) {
            $key = $employee->employee_id . '_' . $today;
            
            if ($status === 'present') {
                return isset($attendance[$key][0]) && $attendance[$key][0]->status === 'Present';
            } elseif ($status === 'absent') {
                return (!isset($attendance[$key][0]) || $attendance[$key][0]->status !== 'Present') && 
                    !isset($leaveData[$key]);
            } elseif ($status === 'leave') {
                return isset($leaveData[$key]);
            }
            
            return true;
        });
    }

    public function getTodayAttendanceStatus()
    {
        $context = $this->getInstituteBranchContext();
        $today = now()->toDateString();
        
        // Get all active employees
        $employees = EmployeeDetails::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
              ->with([
                'shift', 
                'department',
                'employeeShifts.shift'  // Make sure this relationship exists
            ])
            ->where('status', 'active')
            ->get();
    
        $present = [];
        $absent = [];
        $onLeave = [];
        
        foreach ($employees as $employee) {
            // Check attendance
            $attendance = EmployeeAttendance::where('employee_id', $employee->employee_id)
                ->where('date', $today)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            if ($attendance && $attendance->status === 'Present') {
                $present[] = $employee;
            } else {
                // Check for approved leaves
                $leave = EmployeeLeave::where('employee_id', $employee->employee_id)
                    ->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today)
                    ->where('final_status', 'Approved')
                    ->where('institute_id', $context['institute_id'])
                    ->first();
                
                if ($leave) {
                    $onLeave[] = $employee;
                } else {
                    $absent[] = $employee;
                }
            }
        }
        
        return [
            'present' => count($present),
            'absent' => count($absent),
            'on_leave' => count($onLeave),
            'total' => count($employees)
        ];
    }

    public function getAttendanceDetails($employeeId, $date)
    {
        try {
            $merchantId = auth()->user()->institute_id;
            
            $attendance = EmployeeAttendance::where('employee_id', $employeeId)
                ->where('date', $date)
                ->where('institute_id', $merchantId)
                ->first();
            
            if (!$attendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'No attendance record found'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'attendance' => [
                    'check_time' => $attendance->check_time ? Carbon::parse($attendance->check_time)->format('h:i A') : null,
                    'check_out_time' => $attendance->check_out_time ? Carbon::parse($attendance->check_out_time)->format('h:i A') : null,
                    'working_hours' => $attendance->working_hours ?? 'N/A',
                    'status' => $attendance->status,
                    'late_minutes' => $attendance->late_minutes ?? 0,
                    'early_minutes' => $attendance->early_minutes ?? 0
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching attendance details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching attendance details'
            ]);
        }
    }

   //particularly for employee to view their own attendance details in a month with shift comparison and leave information
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
        
        // ✅ FIRST: Check if attendance is finalized for this month
        $finalizedAttendance = AttendanceReview::where('employee_id', $employeeId)
            ->where('year', $year)
            ->where('month', $month)
            ->where('institute_id', $merchantId)
            ->where('review_status', 'finalized')
            ->first();
        
        // ✅ If finalized, show the finalized data (what admin approved)
        if ($finalizedAttendance) {
            return $this->showFinalizedAttendance($finalizedAttendance, $employee, $month, $year);
        }
        
        // ✅ If NOT finalized, show real-time calculated data
        return $this->showRealtimeAttendance($employee, $month, $year);
    }

    
    private function showFinalizedAttendance($finalizedAttendance, $employee, $month, $year)
    {
        $attendanceDetails = $finalizedAttendance->attendance_details;
        if (is_string($attendanceDetails)) {
            $attendanceDetails = json_decode($attendanceDetails, true) ?? [];
        }
        
        // Get leave counts from finalized data
        $leaveCounts = $finalizedAttendance->leave_counts ?? [];
        if (is_string($leaveCounts)) {
            $leaveCounts = json_decode($leaveCounts, true) ?? [];
        }
        
        // Calculate totals from finalized data
        $totalPresent = $finalizedAttendance->present_days ?? 0;
        $totalAbsent = $finalizedAttendance->absent_days ?? 0;
        $totalLeave = $finalizedAttendance->leave_days ?? 0;
        $totalWeekend = $finalizedAttendance->weekend_days ?? 0;
        $totalShortAttendance = $finalizedAttendance->short_attendance_days ?? 0;
        $attendancePercentage = $finalizedAttendance->attendance_percentage ?? 0;
        $workingDays = $finalizedAttendance->working_days ?? 0;
        
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
                    'is_working_day' => $details['is_working_day'] ?? true
                ];
            } else {
                // No record for this date - check if weekend
                $dayOfWeek = $dateObj->dayOfWeekIso;
                $isSunday = ($dayOfWeek == 7);
                
                if ($isSunday) {
                    $detailedAttendance[$dateString] = [
                        'status' => 'weekend',
                        'status_text' => 'Weekly Off',
                        'status_color' => 'secondary',
                        'status_icon' => 'bi-calendar-week',
                        'day_name' => $dateObj->format('l'),
                        'is_working_day' => false
                    ];
                } else {
                    $detailedAttendance[$dateString] = [
                        'status' => 'absent',
                        'status_text' => 'Absent',
                        'status_color' => 'danger',
                        'status_icon' => 'bi-x-circle',
                        'day_name' => $dateObj->format('l'),
                        'is_working_day' => true
                    ];
                }
            }
        }
        // dd($detailedAttendance);
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
    
    private function showRealtimeAttendance($employee, $month, $year)
    {
        $employeeId = $employee->employee_id;
        $merchantId = auth()->user()->institute_id;
        
        // Get employee's shift for the selected month
        $date = Carbon::create($year, $month, 1);
        $shiftData = $employee->resolveEffectiveShift($date);
        $employee->resolved_shift = $shiftData['shift'] ?? null;
        
        // Parse weekly off days
        if ($employee->resolved_shift && isset($employee->resolved_shift->weekly_off_days)) {
            $weeklyOffData = $employee->resolved_shift->weekly_off_days;
            if (is_string($weeklyOffData)) {
                $weeklyOffData = json_decode($weeklyOffData, true) ?? [];
            }
            $employee->weekly_off_days = $this->parseWeeklyOffDays($weeklyOffData);
        } else {
            $employee->weekly_off_days = [7]; // Default Sunday off
        }
        
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
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateObj = Carbon::create($year, $month, $day);
            $dateString = $dateObj->toDateString();
            $dayOfWeek = $dateObj->dayOfWeekIso;
            
            // Check if it's a weekly off
            $isWeeklyOff = in_array($dayOfWeek, $employee->weekly_off_days);
            
            if ($isWeeklyOff) {
                $totalWeekend++;
                $detailedAttendance[$dateString] = [
                    'status' => 'weekend',
                    'status_text' => 'Weekly Off',
                    'status_color' => 'secondary',
                    'status_icon' => 'bi-calendar-week',
                    'day_name' => $dateObj->format('l'),
                    'is_working_day' => false
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
                    'is_working_day' => true
                ];
                continue;
            }
            
            // Check for attendance record
            if (isset($attendances[$dateString])) {
                $record = $attendances[$dateString];
                
                // Get check-in/out times
                $logs = $record->logs;
                $checkInLog = $logs->where('check_type', 'IN')->first();
                $checkOutLog = $logs->where('check_type', 'OUT')->last();
                
                $checkInTime = $checkInLog ? $checkInLog->check_time->format('h:i A') : null;
                $checkOutTime = $checkOutLog ? $checkOutLog->check_time->format('h:i A') : null;
                $totalHours = $record->total_hours ? floatval($record->total_hours) : 0;
                
                // Get attendance status with auto-detection
                $attendanceResult = $this->calculateDetailedAttendanceStatus(
                    $record, 
                    $employee->resolved_shift,
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
                
                // Calculate short hours
                $requiredHours = $employee->resolved_shift->working_hours ?? 9;
                $shortHours = max(0, $requiredHours - $totalHours);
                
                // Get grace period info
                $graceInfo = $this->getCheckInStatusWithGrace($record, $employee->resolved_shift);
                
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
                    'is_working_day' => true
                ];
            }
            // Check for short day leave (pre-approved)
            elseif (isset($shortDayLeaveDates[$dateString])) {
                $leave = $shortDayLeaveDates[$dateString];
                $durationType = strtolower($leave->leave_duration_type);
                
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
                        'required_hours' => $employee->resolved_shift->working_hours ?? 9,
                        'short_hours' => 0,
                        'leave_type' => 'Short Leave',
                        'within_quota' => $isWithinQuota,
                        'days_to_deduct' => $isWithinQuota ? 0 : 0.25,
                        'is_approved' => $isWithinQuota,
                        'deduction_percentage' => $isWithinQuota ? 25 : 55,
                        'approved_leave' => true,
                        'is_working_day' => true
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
                        'required_hours' => $employee->resolved_shift->working_hours ?? 9,
                        'short_hours' => 0,
                        'leave_type' => 'Half Day',
                        'within_quota' => $isWithinQuota,
                        'days_to_deduct' => $isWithinQuota ? 0 : 0.5,
                        'is_approved' => $isWithinQuota,
                        'deduction_percentage' => $isWithinQuota ? 50 : 100,
                        'approved_leave' => true,
                        'is_working_day' => true
                    ];
                }
            }
            // No record - Check if it's a future date
            else {
                // Check if this date is in the future
                if ($dateObj->gt($today)) {
                    // Future date - show as upcoming/blank
                    $detailedAttendance[$dateString] = [
                        'status' => 'upcoming',
                        'status_text' => 'Upcoming',
                        'status_color' => 'secondary',
                        'status_icon' => 'bi-calendar',
                        'day_name' => $dateObj->format('l'),
                        'is_working_day' => true,
                        'is_future' => true
                    ];
                } else {
                    // Past date with no record = Absent
                    $totalAbsent++;
                    $detailedAttendance[$dateString] = [
                        'status' => 'absent',
                        'status_text' => 'Absent',
                        'status_color' => 'danger',
                        'status_icon' => 'bi-x-circle',
                        'day_name' => $dateObj->format('l'),
                        'is_working_day' => true,
                        'is_future' => false
                    ];
                }
            }
        }
        
        $workingDays = $daysInMonth - $totalWeekend;
        $attendancePercentage = $workingDays > 0 ? round(($totalPresent / $workingDays) * 100, 2) : 0;
        
        $leaveCounts = [];
        // dd($detailedAttendance);
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
    
    private function getCurrentAcademicSession()
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        return $currentMonth >= 4 ? $currentYear . '-' . ($currentYear + 1) : ($currentYear - 1) . '-' . $currentYear;
    }

    
    private function getCheckInStatusWithGrace($record, $shift)
    {
        $result = [
            'check_in_time' => null,
            'shift_start_time' => null,
            'is_within_grace' => false,
            'is_late' => false,
            'late_minutes' => 0,
            'grace_minutes' => $shift->grace_minutes ?? 15,
            'status_message' => ''
        ];
        
        $logs = $record->logs->sortBy('check_time');
        $checkInLog = $logs->where('check_type', 'IN')->first();
        
        if (!$checkInLog || !$shift) {
            $result['status_message'] = 'No check-in record';
            return $result;
        }
        
        try {
            $checkInTime = Carbon::parse($checkInLog->check_time, 'UTC')->setTimezone('Asia/Kolkata');
            $shiftStartTime = Carbon::createFromFormat('H:i:s', $shift->start_time, 'Asia/Kolkata');
            $shiftStartTime->setDate($checkInTime->year, $checkInTime->month, $checkInTime->day);
            
            $result['check_in_time'] = $checkInTime->format('h:i A');
            $result['shift_start_time'] = $shiftStartTime->format('h:i A');
            
            $graceMinutes = $shift->grace_minutes ?? 15;
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
                $result['status_message'] = '(' . $graceMinutes . 'min grace period)';
            }
        } catch (\Exception $e) {
            $result['status_message'] = 'Error calculating grace period';
        }
        
        return $result;
    }
    
    /**
         * Calculate detailed attendance status with auto-detection for real-time view
    */
    private function calculateDetailedAttendanceStatus($record, $shift, $shortDayLeave, &$shortLeavesUsedThisMonth, &$halfDaysUsedThisMonth, $shortLeaveBalance, $halfDayBalance, $totalHours = null)
    {
        $requiredHours = floatval($shift->working_hours ?? 9);
        
        // ✅ Use passed total hours or get from record
        if ($totalHours !== null) {
            $workedHours = floatval($totalHours);
        } else {
            $workedHours = floatval($record->total_hours ?? 0);
        }
        
        $halfDayHours = floatval($shift->half_day_hours ?? 4);
        $shortLeaveHours = floatval($shift->short_leave_hours ?? 6);
        
        // Get check-in time for late calculation
        $logs = $record->logs->sortBy('check_time');
        $checkInLog = $logs->where('check_type', 'IN')->first();
        
        $isOnTime = true;
        $lateMinutes = 0;
        
        if ($checkInLog && $shift) {
            try {
                $checkInTime = Carbon::parse($checkInLog->check_time, 'UTC')->setTimezone('Asia/Kolkata');
                $shiftStartTime = Carbon::createFromFormat('H:i:s', $shift->start_time, 'Asia/Kolkata');
                $shiftStartTime->setDate($checkInTime->year, $checkInTime->month, $checkInTime->day);
                $graceMinutes = $shift->grace_minutes ?? 15;
                $graceEndTime = $shiftStartTime->copy()->addMinutes($graceMinutes);
                
                if ($checkInTime->gt($graceEndTime)) {
                    $isOnTime = false;
                    $lateMinutes = $checkInTime->diffInMinutes($shiftStartTime);
                }
            } catch (\Exception $e) {
                // Ignore parsing errors
            }
        }
        
        // ✅ Check if there's any check-in record at all
        $hasCheckIn = ($checkInLog !== null);
        
        // ✅ If no check-in record at all, it's absent
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
        
        // Check for pre-approved short leave/half day from application
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
        
        // ✅ Auto-detection based on actual worked hours (only reached if no pre-approved leave)
        
        // Full day present
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
        // Short Leave range (worked between short_leave_hours and required_hours)
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
        // Half Day range (worked between half_day_hours and short_leave_hours)
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
        // Minimal attendance (worked more than 0 but less than half_day_hours)
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
        // No worked hours but has check-in? This shouldn't happen, but just in case
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
     * Convert decimal hours to human readable format
     */
    private function formatHoursToHMS($hours)
    {
        if (!$hours) return null;
        
        $hrs = floor($hours);
        $mins = round(($hours - $hrs) * 60);
        
        // Handle rounding where minutes become 60
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
    
    public function downloadMonthlyAttendance(Request $request)
    {
        // ✅ Validate request
        $validated = $request->validate([
            'type' => 'required|in:excel,csv,pdf',
            'ids' => 'nullable|string',
            'select_all' => 'nullable|boolean',
            'filters' => 'nullable|string',
        ]);
    
        $merchantId = auth()->user()->institute_id;
    
        /*
        |--------------------------------------------------------------------------
        | BASE EMPLOYEE QUERY
        |--------------------------------------------------------------------------
        */
        $employeesQuery = EmployeeDetails::query()
            ->where('employee_details.institute_id', $merchantId)
            ->where('employee_details.status', 'active')
            ->leftJoin('departments', 'departments.department_id', '=', 'employee_details.department_id')
            ->select([
                'employee_details.*',
                'departments.department as department_name',
                'departments.department_id'
            ]);
    
        /*
        |--------------------------------------------------------------------------
        | SELECT ALL → APPLY FILTERS
        |--------------------------------------------------------------------------
        */
        if ($request->has('select_all') && $request->select_all == 1) {
    
            if ($request->has('filters') && $filters = json_decode($request->filters, true)) {
    
                if (!empty($filters['department_id'])) {
                    $employeesQuery->where('departments.department_id', $filters['department_id']);
                }
    
                if (!empty($filters['employee_id'])) {
                    $employeesQuery->where('employee_details.employee_id', $filters['employee_id']);
                }
    
                if (!empty($filters['shift_id'])) {
                    $employeesQuery->whereHas('employeeShifts', function ($q) use ($filters) {
                        $q->where('shift_id', $filters['shift_id'])
                          ->where('status', 'active');
                    });
                }
            }
        }
        /*
        |--------------------------------------------------------------------------
        | SELECTED IDS ONLY
        |--------------------------------------------------------------------------
        */
        elseif ($request->filled('ids')) {
            $ids = array_filter(explode(',', $validated['ids']));
            $employeesQuery->whereIn('employee_details.employee_id', $ids);
        }
        else {
            return back()->with('error', 'No employees selected for export');
        }
    
        $employees = $employeesQuery->get();
    
        if ($employees->isEmpty()) {
            return back()->with('error', 'No employees found for export');
        }
    
        /*
        |--------------------------------------------------------------------------
        | DATE RANGE
        |--------------------------------------------------------------------------
        */
        $month = $request->get('month', now()->month);
        $year  = $request->get('year', now()->year);
    
        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate   = $startDate->copy()->endOfMonth();
    
        $dates = collect();
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates->push($date->toDateString());
        }
    
        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE
        |--------------------------------------------------------------------------
        */
        $attendance = EmployeeAttendance::whereBetween('date', [$startDate, $endDate])
            ->where('institute_id', $merchantId)
            ->get()
            ->groupBy(fn ($item) =>
                $item->employee_id . '_' . Carbon::parse($item->date)->toDateString()
            );
    
        /*
        |--------------------------------------------------------------------------
        | LEAVES
        |--------------------------------------------------------------------------
        */
        $leaves = EmployeeLeave::whereBetween('start_date', [$startDate, $endDate])
            ->where('institute_id', $merchantId)
            ->where('final_status', 'Approved')
            ->get();
    
        $leaveData = [];
    
        foreach ($leaves as $leave) {
            $current = Carbon::parse($leave->start_date);
            $end     = Carbon::parse($leave->end_date);
    
            while ($current->lte($end)) {
                $key = $leave->employee_id . '_' . $current->toDateString();
                $leaveData[$key] = 'Leave';
                $current->addDay();
            }
        }
    
        /*
        |--------------------------------------------------------------------------
        | BUILD EXPORT DATA
        |--------------------------------------------------------------------------
        */
        $exportRows = collect();
    
        foreach ($employees as $employee) {
            foreach ($dates as $date) {
    
                $key = $employee->employee_id . '_' . $date;
    
                if (isset($leaveData[$key])) {
                    $status = 'Leave';
                } elseif (isset($attendance[$key])) {
                    $status = 'Present';
                } else {
                    $status = 'Absent';
                }
    
                $exportRows->push([
                    'Employee ID'   => $employee->employee_id,
                    'Employee Name' => $employee->name ?? '',
                    'Department'    => $employee->department_name ?? '',
                    'Date'          => $date,
                    'Status'        => $status,
                ]);
            }
        }
    
        if ($exportRows->isEmpty()) {
            return back()->with('error', 'No attendance data found');
        }
    
        $fileName = "attendance_{$month}_{$year}_" . now()->format('Ymd_His');
    
        /*
        |--------------------------------------------------------------------------
        | EXPORT SWITCH
        |--------------------------------------------------------------------------
        */
        switch ($validated['type']) {
    
            case 'excel':
                return Excel::download(
                    new MonthlyAttendanceExport($exportRows),
                    $fileName . '.xlsx'
                );
    
            case 'csv':
                return Excel::download(
                    new MonthlyAttendanceExport($exportRows),
                    $fileName . '.csv'
                );
    
            case 'pdf':
                $pdf = Pdf::loadView('pdf.monthly_attendance', [
                    'rows' => $exportRows,
                    'month' => $month,
                    'year' => $year
                ]);
                return $pdf->download($fileName . '.pdf');
    
            default:
                return back()->with('error', 'Invalid export type');
        }
    }
 
}
