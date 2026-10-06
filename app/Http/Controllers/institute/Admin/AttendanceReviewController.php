<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeLeave;
use App\Models\EmployeeDetails;
use App\Models\Departments;
use App\Models\AttendanceReview;
use App\Models\SalarySlip;
use App\Models\SalaryPreview;
use App\Models\EmployeeAttendanceLogs;
use App\Models\User;
use App\Models\EmployeeSalaryStructure;
use App\Models\EmployeeLeaveBalance;
use App\Models\PayrollExecution;
use App\Traits\InstituteBranchAccess;
use Carbon\Carbon;
use App\Models\LeaveDeduction;
use Illuminate\Support\Facades\DB;
use App\Models\InstituteNotificationSetting;
use Illuminate\Support\Facades\Mail;

class AttendanceReviewController extends Controller
{
    use InstituteBranchAccess;

    public function index(Request $request)
    {
        $merchantId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();
        
        $selectedYear = $request->get('year', now()->year);
        $selectedMonth = $request->get('month', now()->month);
        $availableYears = range(now()->subYears(2)->year, now()->addYear()->year);
        $departmentId = $request->get('department_id');
        $employeeId = $request->get('employee_id');
        $reviewStatus = $request->get('review_status', 'all');
        
        $departments = Departments::where('institute_id', $merchantId)->get();
        
        $allEmployees = EmployeeDetails::where('institute_id', $merchantId)
            ->where('status', 'active')
            ->get();
        
        $employeesQuery = EmployeeDetails::where('employee_details.institute_id', $merchantId)
            ->where('employee_details.status', 'active')
            ->leftJoin('departments', 'departments.department_id', '=', 'employee_details.department_id')
            ->select('employee_details.*', 'departments.department as department_name');
        
        if ($departmentId) {
            $employeesQuery->where('employee_details.department_id', $departmentId);
        }
        
        if ($employeeId) {
            $employeesQuery->where('employee_details.employee_id', $employeeId);
        }
        
        $employees = $employeesQuery->get();
        
        foreach ($employees as $employee) {
            $date = Carbon::create($selectedYear, $selectedMonth, 1);
            $shiftData = $employee->resolveEffectiveShift($date);
            $employee->resolved_shift = $shiftData['shift'] ?? null;
            
            if ($employee->resolved_shift && isset($employee->resolved_shift->weekly_off_days)) {
                $weeklyOffData = $employee->resolved_shift->weekly_off_days;
                if (is_string($weeklyOffData)) {
                    $weeklyOffData = json_decode($weeklyOffData, true);
                }
                $employee->weekly_off_days = $this->parseWeeklyOffDays($weeklyOffData);
            } else {
                $employee->weekly_off_days = [7];
            }
        }
        
        $reviewSummaries = [];
        $dateRange = $this->getDateRange($selectedYear, $selectedMonth);
        $hasPendingEmployees = false;
        
        foreach ($employees as $employee) {
            $attendanceData = $this->calculateEmployeeAttendance(
                $employee,
                $dateRange['start'],
                $dateRange['end']
            );
            
            $existingFinalized = AttendanceReview::where('institute_id', $merchantId)
                ->where('year', $selectedYear)
                ->where('month', $selectedMonth)
                ->where('employee_id', $employee->employee_id)
                ->first();
            
            $reviewStatusValue = $existingFinalized ? $existingFinalized->review_status : 'pending';
            
            if ($reviewStatusValue === 'finalized') {
                $attendanceDetails = $existingFinalized->attendance_details;
                if (is_string($attendanceDetails)) {
                    $attendanceDetails = json_decode($attendanceDetails, true) ?? [];
                }
                
                $leaveCounts = [];
                foreach ($attendanceDetails as $details) {
                    $status = $details['status'] ?? '';
                    $leaveType = $details['leave_type'] ?? '';
                    
                    if ($status === 'leave' && !empty($leaveType)) {
                        if (!isset($leaveCounts[$leaveType])) {
                            $leaveCounts[$leaveType] = 0;
                        }
                        $leaveCounts[$leaveType]++;
                    }
                }
                
                $attendanceData = [
                    'employee_name' => $employee->name,
                    'employee_code' => $employee->employee_code,
                    'department' => $employee->department_name ?? 'No Department',
                    'department_id' => $employee->department_id,
                    'shift_info' => $employee->resolved_shift ? [
                        'shift_name' => $employee->resolved_shift->shift_name,
                        'shift_timing' => $employee->resolved_shift->start_time . ' - ' . $employee->resolved_shift->end_time,
                        'weekly_offs' => $this->getWeeklyOffNames($employee->weekly_off_days)
                    ] : null,
                    'present_days' => $existingFinalized->present_days,
                    'absent_days' => $existingFinalized->absent_days,
                    'leave_days' => $existingFinalized->leave_days,
                    'leave_instances_count' => array_sum($leaveCounts),
                    'weekend_days' => $existingFinalized->weekend_days,
                    'working_days' => $existingFinalized->working_days,
                    'attendance_percentage' => $existingFinalized->attendance_percentage,
                    'short_attendance_days' => $existingFinalized->short_attendance_days ?? 0,
                    'unapproved_leave_days' => $existingFinalized->unapproved_leave_days ?? 0,
                    'leave_counts' => $leaveCounts,
                    'attendance_details' => $attendanceDetails
                ];
            }
            
            if ($reviewStatus != 'all' && $reviewStatusValue != $reviewStatus) {
                continue;
            }
            
            if ($reviewStatusValue === 'pending') {
                $hasPendingEmployees = true;
            }
            
            $reviewSummaries[] = [
                'employee' => $employee,
                'attendance' => $attendanceData,
                'review' => $existingFinalized,
                'review_status' => $reviewStatusValue
            ];
        }
        
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = Carbon::create()->month($i)->format('F');
        }
        
        return view('instituteAdmin.AttendanceReview.index', compact(
            'departments',
            'allEmployees',
            'reviewSummaries',
            'selectedYear',
            'selectedMonth',
            'availableYears',
            'months',
            'departmentId',
            'employeeId',
            'reviewStatus',
            'hasPendingEmployees'
        ));
    }
    
    /**
     * Show the review page for a specific employee
     */
    public function review(Request $request, $employeeId)
    {
        $merchantId = auth()->user()->institute_id;
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        
        $employee = EmployeeDetails::leftJoin('departments', 'departments.department_id', '=', 'employee_details.department_id')
            ->where('employee_details.employee_id', $employeeId)
            ->where('employee_details.institute_id', $merchantId)
            ->select('employee_details.*', 'departments.department as department_name')
            ->first();
        
        if (!$employee) {
            abort(404, 'Employee not found');
        }
        
        $date = Carbon::create($year, $month, 1);
        $shiftData = $employee->resolveEffectiveShift($date);
        $employee->resolved_shift = $shiftData['shift'] ?? null;
        
        if ($employee->resolved_shift && isset($employee->resolved_shift->weekly_off_days)) {
            $weeklyOffData = $employee->resolved_shift->weekly_off_days;
            if (is_string($weeklyOffData)) {
                $weeklyOffData = json_decode($weeklyOffData, true) ?? [];
            }
            $employee->weekly_off_days = $this->parseWeeklyOffDays($weeklyOffData);
        } else {
            $employee->weekly_off_days = [7];
        }
        
        $dateRange = $this->getDateRange($year, $month);
        $attendanceData = $this->calculateEmployeeAttendance($employee, $dateRange['start'], $dateRange['end']);
        
        // Get leave summary data
        $assignedLeaveTypes = $this->getAssignedLeaveTypes($employee->employee_id, $year, $merchantId);
        $assignedLeaveTypes = array_unique(array_map([$this, 'normalizeLeaveType'], $assignedLeaveTypes));
        
        $leaveBalances = $this->getDetailedLeaveBalances($employee->employee_id, $employee->department_id, $year, $merchantId);
        
        $leaveSummary = [];
        foreach ($assignedLeaveTypes as $normalizedLeaveType) {
            $balance = $leaveBalances[$normalizedLeaveType] ?? [
                'quota' => 0,
                'is_unpaid' => false,
                'is_under_quota' => true
            ];
            
            if ($normalizedLeaveType === 'short_leave') {
                $taken = $this->getShortLeavesUsedThisMonth($employee->employee_id, $year, $month);
            } elseif ($normalizedLeaveType === 'half_day') {
                $taken = $this->getHalfDaysUsedThisMonth($employee->employee_id, $year, $month);
            } else {
                $leaveApps = $this->getLeaveApplicationsUsedThisMonth($employee->employee_id, $year, $month, $merchantId);
                $taken = $leaveApps[$normalizedLeaveType] ?? 0;
            }
            
            $deductionConfig = $this->getLeaveDeductionConfigForType($normalizedLeaveType);
            
            $leaveSummary[$this->formatLeaveTypeLabel($normalizedLeaveType)] = [
                'type' => $normalizedLeaveType,
                'taken' => $taken,
                'quota' => $balance['quota'],
                'remaining' => max(0, $balance['quota'] - $taken),
                'is_under_quota' => $taken <= $balance['quota'],
                'is_unpaid' => false,
                'approved_deduction_percentage' => $deductionConfig['approved_percentage'],
                'unapproved_deduction_percentage' => $deductionConfig['unapproved_percentage']
            ];
        }
        
        $attendanceData['leave_summary'] = $leaveSummary;
        
        return view('instituteAdmin.AttendanceReview.review', compact(
            'employee',
            'attendanceData',
            'year',
            'month'
        ));
    }

    public function finalizeAttendance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'year' => 'required|integer',
            'month' => 'required|integer',
            'present_days' => 'required|integer',
            'absent_days' => 'required|integer',
            'leave_days' => 'required|integer',
            'working_days' => 'required|integer',
            'finalize_notes' => 'nullable|string',
            'attendance_details' => 'nullable|array'
        ]);
        
        $merchantId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();
        
        $employee = EmployeeDetails::where('employee_id', $request->employee_id)
            ->where('institute_id', $merchantId)
            ->first();
        
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found']);
        }
        
        $salaryStructure = EmployeeSalaryStructure::where('employee_id', $request->employee_id)
            ->where('institute_id', $merchantId)
            ->where(function($query) use ($context) {
                $query->where('branch_id', $context['branch_id'])->orWhereNull('branch_id');
            })
            ->first();
        
        if (!$salaryStructure) {
            return response()->json([
                'success' => false,
                'message' => 'Salary structure not found. Please create salary structure first.',
                'error_type' => 'missing_salary_structure'
            ], 422);
        }
        
        $existing = AttendanceReview::where('institute_id', $merchantId)
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->where('employee_id', $request->employee_id)
            ->first();
        
        if ($existing && $existing->review_status === 'finalized') {
            return response()->json(['success' => false, 'message' => 'Attendance already finalized']);
        }
        
        DB::beginTransaction();
        
        try {
            $review = AttendanceReview::updateOrCreate(
                [
                    'institute_id' => $merchantId,
                    'branch_id' => $context['branch_id'],
                    'year' => $request->year,
                    'month' => $request->month,
                    'employee_id' => $request->employee_id,
                ],
                [
                    'department_id' => $employee->department_id ?? null,
                    'present_days' => $request->present_days,
                    'absent_days' => $request->absent_days,
                    'leave_days' => $request->leave_days,
                    'weekend_days' => $request->weekend_days ?? 0,
                    'working_days' => $request->working_days,
                    'short_attendance_days' => $request->short_attendance_days ?? 0,
                    'unapproved_leave_days' => $request->unapproved_leave_days ?? 0,
                    'attendance_percentage' => $request->working_days > 0 ? round(($request->present_days / $request->working_days) * 100, 2) : 0,
                    'review_status' => 'finalized',
                    'finalize_notes' => $request->finalize_notes,
                    'finalized_by' => auth()->user()->employee_id ?? auth()->user()->id,
                    'finalized_at' => now(),
                    'attendance_details' => $request->attendance_details
                ]
            );
            
            // Update leave balances
            $this->updateLeaveBalancesFromAttendance($request->employee_id, $request->year, $request->attendance_details);
            
            // CRITICAL: Create salary slip entry immediately
            $salarySlipResult = $this->createSalarySlipEntry($employee, $request->year, $request->month, $context);
            // ✅ ADD THIS: Send email notification to employee
            $attendanceData = [
                'present_days' => $request->present_days,
                'absent_days' => $request->absent_days,
                'leave_days' => $request->leave_days,
                'weekend_days' => $request->weekend_days ?? 0,
                'working_days' => $request->working_days,
                'attendance_percentage' => $request->working_days > 0 ? round(($request->present_days / $request->working_days) * 100, 2) : 0,
                'attendance_details' => $request->attendance_details
            ];
            
            $this->sendAttendanceFinalizedEmail($employee, $attendanceData, $request->year, $request->month);
            
            DB::commit();
            
            return response()->json([
                'success' => true, 
                'message' => 'Attendance finalized and salary slip created successfully',
                'salary_slip' => $salarySlipResult
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function getAttendanceDetails(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'year' => 'required|integer',
            'month' => 'required|integer',
        ]);
        
        $merchantId = auth()->user()->institute_id;
        
        $employee = EmployeeDetails::leftJoin('departments', 'departments.department_id', '=', 'employee_details.department_id')
            ->where('employee_details.employee_id', $request->employee_id)
            ->where('employee_details.institute_id', $merchantId)
            ->select('employee_details.*', 'departments.department as department_name')
            ->first();
        
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found']);
        }
        
        $date = Carbon::create($request->year, $request->month, 1);
        $shiftData = $employee->resolveEffectiveShift($date);
        $employee->resolved_shift = $shiftData['shift'] ?? null;
        
        if ($employee->resolved_shift && isset($employee->resolved_shift->weekly_off_days)) {
            $weeklyOffData = $employee->resolved_shift->weekly_off_days;
            if (is_string($weeklyOffData)) {
                $weeklyOffData = json_decode($weeklyOffData, true) ?? [];
            }
            $employee->weekly_off_days = $this->parseWeeklyOffDays($weeklyOffData);
        } else {
            $employee->weekly_off_days = [7];
        }
        
        $dateRange = $this->getDateRange($request->year, $request->month);
        $attendanceData = $this->calculateEmployeeAttendance($employee, $dateRange['start'], $dateRange['end']);
        
        // ========== ADD LEAVE SUMMARY DATA ==========
        // Get assigned leave types and balances for this employee
        $assignedLeaveTypes = $this->getAssignedLeaveTypes(
            $employee->employee_id, $request->year, $merchantId
        );
        
        // Get how many of each leave type taken this month
        $takenThisMonth = $this->getLeavesTakenThisMonth(
            $employee->employee_id, $request->year, $request->month, $merchantId
        );
        
        // Get detailed leave balances with quotas
        $leaveBalances = $this->getDetailedLeaveBalances(
            $employee->employee_id, $employee->department_id, $request->year, $merchantId
        );
        
        // Build leave summary
        $leaveSummary = [];
        $assignedLeaveTypes = array_unique(array_map([$this, 'normalizeLeaveType'], $assignedLeaveTypes));

        foreach ($assignedLeaveTypes as $normalizedLeaveType) {
            $balance = $leaveBalances[$normalizedLeaveType] ?? [
                'quota' => 0,
                'is_unpaid' => false,
                'is_under_quota' => true
            ];
            
            // For Short Leave and Half Day, use the attendance detection count
            if ($normalizedLeaveType === 'short_leave') {
                $taken = $this->getShortLeavesUsedThisMonth($employee->employee_id, $request->year, $request->month);
            } elseif ($normalizedLeaveType === 'half_day') {
                $taken = $this->getHalfDaysUsedThisMonth($employee->employee_id, $request->year, $request->month);
            } else {
                // For other leave types, get from leave applications
                $leaveApps = $this->getLeaveApplicationsUsedThisMonth($employee->employee_id, $request->year, $request->month, $merchantId);
                $taken = $leaveApps[$normalizedLeaveType] ?? 0;
            }
            
            $salary_deducted = ($taken > $balance['quota']) ? true : false;
            
            $deductionConfig = $this->getLeaveDeductionConfigForType($normalizedLeaveType);
            
            $leaveSummary[$this->formatLeaveTypeLabel($normalizedLeaveType)] = [
                'type' => $normalizedLeaveType,
                'taken' => $taken,
                'quota' => $balance['quota'],
                'remaining' => max(0, $balance['quota'] - $taken),
                'is_under_quota' => $taken <= $balance['quota'],
                'is_unpaid' => false,
                'salary_deducted' => $salary_deducted,
                'approved_deduction_percentage' => $deductionConfig['approved_percentage'],
                'unapproved_deduction_percentage' => $deductionConfig['unapproved_percentage']
            ];
        }
        
        // Add leave summary to attendance data
        $attendanceData['leave_summary'] = $leaveSummary;
        
        // Add salary info
        $context = $this->getInstituteBranchContext();
        $salaryStructure = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
            ->where('institute_id', $merchantId)
            ->where(function($query) use ($context) {
                $query->where('branch_id', $context['branch_id'])->orWhereNull('branch_id');
            })
            ->first();
        
        if ($salaryStructure) {
            $salaryPreview = SalaryPreview::where('salary_structure_id', $salaryStructure->salary_structure_id)
                ->where('institute_id', $merchantId)
                ->first();
            
            if ($salaryPreview) {
                $attendanceData['salary_info'] = [
                    'basic_salary' => $salaryPreview->basic_salary_monthly ?? 0,
                    'total_earnings' => $salaryPreview->ctc_monthly ?? 0,
                    'net_salary' => $salaryPreview->net_salary_monthly ?? 0
                ];
            }
        }
        // ========== END ADD LEAVE SUMMARY DATA ==========
        
        return response()->json(['success' => true, 'data' => $attendanceData]);
    }

    private function getLeaveDeductionConfigForType($leaveType)
    {
        $merchantId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();
        
        // Map the normalized leave types to database values
        $dbLeaveTypeMap = [
            'short_leave' => ['Short Day Leave', 'Short Leave', 'Short Leave (Day)'],
            'half_day' => ['Half Day Leave', 'Half Day', 'Half Day Leave (Half Day)'],
            'casual_leave' => ['Casual Leave'],
            'sick_leave' => ['Sick Leave'],
            'earned_leave' => ['Earned Leave'],
            'unpaid_leave' => ['Unpaid Leave'],
            'maternity_leave' => ['Maternity Leave'],
            'study_leave' => ['Study Leave'],
            'absent' => ['Absent']
        ];
        
        // Determine the normalized type
        $normalizedType = $this->normalizeLeaveType($leaveType);
        
        // Get the possible database leave type names for this normalized type
        $possibleTypes = $dbLeaveTypeMap[$normalizedType] ?? [$leaveType];
        
        $deduction = null;
        
        // Try to find deduction by matching against possible database leave types
        foreach ($possibleTypes as $type) {
            $deduction = LeaveDeduction::forInstitute($merchantId, $context['branch_id'])
                ->where('leave_type', $type)
                ->where('is_active', true)
                ->first();
            
            if ($deduction) {
                break;
            }
        }
        
        // If still not found, try case-insensitive partial match
        if (!$deduction) {
            $deduction = LeaveDeduction::forInstitute($merchantId, $context['branch_id'])
                ->whereRaw('LOWER(leave_type) LIKE ?', ['%' . strtolower($normalizedType) . '%'])
                ->where('is_active', true)
                ->first();
        }
        
        if ($deduction) {
            return [
                'approved_percentage' => floatval($deduction->approved_deduction_percentage),
                'unapproved_percentage' => floatval($deduction->unapproved_deduction_percentage)
            ];
        }
        
        // Default values if no configuration found
        $defaults = [
            'short_leave' => ['approved' => 25, 'unapproved' => 55],
            'half_day' => ['approved' => 50, 'unapproved' => 100],
            'casual_leave' => ['approved' => 0, 'unapproved' => 100],
            'sick_leave' => ['approved' => 0, 'unapproved' => 100],
            'earned_leave' => ['approved' => 0, 'unapproved' => 100],
            'unpaid_leave' => ['approved' => 100, 'unapproved' => 100],
        ];
        
        $default = $defaults[$normalizedType] ?? ['approved' => 0, 'unapproved' => 100];
        
        return [
            'approved_percentage' => $default['approved'],
            'unapproved_percentage' => $default['unapproved']
        ];
    }

    public function getFinalizedAttendance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'year' => 'required|integer',
            'month' => 'required|integer',
        ]);
        
        $merchantId = auth()->user()->institute_id;
        
        $finalized = AttendanceReview::where('institute_id', $merchantId)
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->where('employee_id', $request->employee_id)
            ->where('review_status', 'finalized')
            ->first();
        
        if (!$finalized) {
            return response()->json(['success' => false, 'message' => 'Finalized attendance not found']);
        }
        
        $employee = EmployeeDetails::where('employee_id', $request->employee_id)
            ->where('institute_id', $merchantId)
            ->first();
        
        $attendanceDetails = $finalized->attendance_details ?? [];
        if (is_string($attendanceDetails)) {
            $attendanceDetails = json_decode($attendanceDetails, true) ?? [];
        }
        
        $dateRange = $this->getDateRange($request->year, $request->month);
        $originalAttendanceRecords = EmployeeAttendance::with('logs')
            ->where('employee_id', $request->employee_id)
            ->whereBetween('date', [$dateRange['start'], $dateRange['end']])
            ->where('institute_id', $merchantId)
            ->get()
            ->keyBy('date');
        
        foreach ($attendanceDetails as $date => &$details) {
            if (isset($originalAttendanceRecords[$date])) {
                $record = $originalAttendanceRecords[$date];
                
                if (empty($details['check_logs']) && !empty($record->logs)) {
                    $recordLogs = $record->logs->sortBy('check_time')->map(function ($log) {
                        return [
                            'type' => strtoupper($log->check_type),
                            'time' => Carbon::parse($log->check_time, 'UTC')->setTimezone('Asia/Kolkata')->format('h:i A'),
                            'full_time' => $log->check_time
                        ];
                    })->values()->all();
                    $details['check_logs'] = $recordLogs;
                }
                
                if (!isset($details['check_in']) && !empty($details['check_logs'])) {
                    $details['check_in'] = $details['check_logs'][0]['time'] ?? null;
                }
                if (!isset($details['check_out']) && !empty($details['check_logs'])) {
                    $details['check_out'] = end($details['check_logs'])['time'] ?? null;
                }
                if (!isset($details['total_hours']) && $record->total_hours) {
                    $details['total_hours'] = $record->total_hours;
                }
            }
        }
        
        return response()->json(['success' => true, 'data' => [
            'employee_name' => $employee->name ?? 'Unknown',
            'employee_code' => $employee->employee_code ?? 'N/A',
            'present_days' => $finalized->present_days,
            'absent_days' => $finalized->absent_days,
            'leave_days' => $finalized->leave_days,
            'weekend_days' => $finalized->weekend_days,
            'working_days' => $finalized->working_days,
            'attendance_percentage' => $finalized->attendance_percentage,
            'attendance_details' => $attendanceDetails,
            'finalized_at' => Carbon::parse($finalized->finalized_at)->format('d M Y h:i A'),
            'finalize_notes' => $finalized->finalize_notes
        ]]);
    }

    public function bulkFinalize(Request $request)
    {
        $request->validate([
            'employee_ids' => 'required|array',
            'year' => 'required|integer',
            'month' => 'required|integer',
            'action' => 'required|in:finalize_all'
        ]);
        
        $merchantId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();
        
        $finalizedCount = 0;
        $salarySlipCount = 0;
        $errors = [];
        
        foreach ($request->employee_ids as $employeeId) {
            try {
                DB::beginTransaction();
                
                $employee = EmployeeDetails::where('employee_id', $employeeId)
                    ->where('institute_id', $merchantId)
                    ->first();
                
                if (!$employee) {
                    $errors[] = "Employee ID {$employeeId} not found";
                    DB::rollBack();
                    continue;
                }
                
                $salaryStructure = EmployeeSalaryStructure::where('employee_id', $employeeId)
                    ->where('institute_id', $merchantId)
                    ->where(function($query) use ($context) {
                        $query->where('branch_id', $context['branch_id'])->orWhereNull('branch_id');
                    })
                    ->first();
                
                if (!$salaryStructure) {
                    $errors[] = "Salary structure not found for employee: {$employee->name}";
                    DB::rollBack();
                    continue;
                }
                
                $existing = AttendanceReview::where('institute_id', $merchantId)
                    ->where('year', $request->year)
                    ->where('month', $request->month)
                    ->where('employee_id', $employeeId)
                    ->first();
                
                if ($existing && $existing->review_status === 'finalized') {
                    $errors[] = "Employee {$employee->name} already finalized";
                    DB::rollBack();
                    continue;
                }
                
                $dateRange = $this->getDateRange($request->year, $request->month);
                
                $date = Carbon::create($request->year, $request->month, 1);
                $shiftData = $employee->resolveEffectiveShift($date);
                $employee->resolved_shift = $shiftData['shift'] ?? null;
                
                if ($employee->resolved_shift && isset($employee->resolved_shift->weekly_off_days)) {
                    $weeklyOffData = $employee->resolved_shift->weekly_off_days;
                    if (is_string($weeklyOffData)) {
                        $weeklyOffData = json_decode($weeklyOffData, true) ?? [];
                    }
                    $employee->weekly_off_days = $this->parseWeeklyOffDays($weeklyOffData);
                } else {
                    $employee->weekly_off_days = [7];
                }
                
                $attendanceData = $this->calculateEmployeeAttendance($employee, $dateRange['start'], $dateRange['end']);
                
                AttendanceReview::updateOrCreate(
                    [
                        'institute_id' => $merchantId,
                        'branch_id' => $context['branch_id'],
                        'year' => $request->year,
                        'month' => $request->month,
                        'employee_id' => $employeeId,
                    ],
                    [
                        'department_id' => $employee->department_id ?? null,
                        'present_days' => $attendanceData['present_days'],
                        'absent_days' => $attendanceData['absent_days'],
                        'leave_days' => $attendanceData['leave_days'],
                        'weekend_days' => $attendanceData['weekend_days'],
                        'working_days' => $attendanceData['working_days'],
                        'short_attendance_days' => $attendanceData['short_attendance_days'] ?? 0,
                        'unapproved_leave_days' => $attendanceData['unapproved_leave_days'] ?? 0,
                        'attendance_percentage' => $attendanceData['attendance_percentage'],
                        'review_status' => 'finalized',
                        'finalize_notes' => 'Bulk finalized',
                        'finalized_by' => auth()->user()->employee_id ?? auth()->user()->id,
                        'finalized_at' => now(),
                        'attendance_details' => $attendanceData['attendance_details']
                    ]
                );
                
                // Create salary slip for bulk finalize
                try {
                    $salarySlipResult = $this->createSalarySlipEntry($employee, $request->year, $request->month, $context);
                    $salarySlipCount++;
                } catch (\Exception $e) {
                    $errors[] = "Salary slip creation failed for {$employee->name}: " . $e->getMessage();
                }
                // ✅ ADD THIS: Send email notification to employee
                $attendanceData = [
                    'present_days' => $attendanceData['present_days'],
                    'absent_days' => $attendanceData['absent_days'],
                    'leave_days' => $attendanceData['leave_days'],
                    'weekend_days' => $attendanceData['weekend_days'],
                    'working_days' => $attendanceData['working_days'],
                    'attendance_percentage' => $attendanceData['attendance_percentage'],
                    'attendance_details' => $attendanceData['attendance_details']
                ];
                
                $this->sendAttendanceFinalizedEmail($employee, $attendanceData, $request->year, $request->month);
                
                DB::commit();
                $finalizedCount++;
                
            } catch (\Exception $e) {
                DB::rollBack();
                $errors[] = "Error processing employee ID {$employeeId}: " . $e->getMessage();
            }
        }
        
        $message = "Successfully finalized {$finalizedCount} employee(s)";
        if ($salarySlipCount > 0) {
            $message .= " with {$salarySlipCount} salary slip(s) created";
        }
        if (!empty($errors)) {
            $message .= ". Errors: " . implode(", ", $errors);
        }
        
        return response()->json(['success' => $finalizedCount > 0, 'message' => $message, 'finalized_count' => $finalizedCount, 'salary_slip_count' => $salarySlipCount, 'errors' => $errors]);
    }

    private function calculateEmployeeAttendance($employee, $startDate, $endDate)
    {
        $merchantId = auth()->user()->institute_id;
        
        // Convert to date strings for proper comparison
        $startDateStr = $startDate->toDateString();
        $endDateStr = $endDate->toDateString();
        
        $attendanceRecords = EmployeeAttendance::with('logs')
            ->where('employee_id', $employee->employee_id)
            ->whereBetween('date', [$startDateStr, $endDateStr])
            ->where('institute_id', $merchantId)
            ->get()
            ->keyBy('date');
        
        // Debug: Log all attendance record dates
        \Log::info('Attendance Records Found', [
            'employee_id' => $employee->employee_id,
            'dates' => $attendanceRecords->keys()->toArray()
        ]);

        // Fixed: Query leaves using date strings
        $allApprovedLeaves = EmployeeLeave::where('employee_id', $employee->employee_id)
            ->where('final_status', 'Approved')
            ->where(function($query) use ($startDateStr, $endDateStr) {
                // Leave starts within the month
                $query->whereDate('start_date', '>=', $startDateStr)
                    ->whereDate('start_date', '<=', $endDateStr)
                    // OR leave ends within the month
                    ->orWhere(function($q) use ($startDateStr, $endDateStr) {
                        $q->whereDate('end_date', '>=', $startDateStr)
                        ->whereDate('end_date', '<=', $endDateStr);
                    })
                    // OR leave spans across the entire month
                    ->orWhere(function($q) use ($startDateStr, $endDateStr) {
                        $q->whereDate('start_date', '<=', $startDateStr)
                        ->whereDate('end_date', '>=', $endDateStr);
                    });
            })
            ->get();
        
        // Debug: Log found leaves
        \Log::info('Fetched Leaves Count: ' . $allApprovedLeaves->count(), [
            'employee_id' => $employee->employee_id,
            'date_range' => $startDateStr . ' to ' . $endDateStr
        ]);
        
        $fullDayLeaveDates = [];
        $shortDayLeaveDates = [];
        
        foreach ($allApprovedLeaves as $leave) {
            $leaveStart = Carbon::parse($leave->start_date);
            $leaveEnd = Carbon::parse($leave->end_date);
            $durationType = strtolower($leave->leave_duration_type ?? 'full day');
            
            \Log::info('Processing Leave: ' . $leave->id, [
                'type' => $leave->leave_type,
                'duration' => $durationType,
                'start' => $leave->start_date,
                'end' => $leave->end_date
            ]);
            
            $current = $leaveStart->copy();
            while ($current <= $leaveEnd) {
                $dateKey = $current->toDateString();
                
                // Only process dates within our range
                if ($dateKey >= $startDateStr && $dateKey <= $endDateStr) {
                    if (in_array($durationType, ['short leave', 'short_leave', 'half day', 'half_day', 'half_days'])) {
                        if (!isset($shortDayLeaveDates[$dateKey])) {
                            $shortDayLeaveDates[$dateKey] = $leave;
                            \Log::info('Added to shortDayLeaveDates', ['date' => $dateKey, 'type' => $durationType]);
                        }
                    } else {
                        $fullDayLeaveDates[$dateKey] = $leave;
                        \Log::info('Added to fullDayLeaveDates', ['date' => $dateKey, 'type' => $durationType]);
                    }
                }
                $current->addDay();
            }
        }
        
        // Debug: Log the populated arrays
        \Log::info('Full Day Leave Dates', ['dates' => array_keys($fullDayLeaveDates)]);
        \Log::info('Short/Half Day Leave Dates', ['dates' => array_keys($shortDayLeaveDates)]);
        
        $presentDays = 0;
        $absentDays = 0;
        $leaveDays = 0;
        $weekendDays = 0;
        $workingDays = 0;
        $unapprovedLeaveDays = 0;
        $leaveCounts = [];
        $attendanceDetails = [];
        
        $currentDate = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        while ($currentDate->lte($end)) {
            $dateString = $currentDate->toDateString();
            
            // Debug for specific dates
            if (in_array($dateString, ['2026-03-10', '2026-03-12'])) {
                \Log::info('Checking date: ' . $dateString, [
                    'has_attendance' => isset($attendanceRecords[$dateString]),
                    'has_full_day_leave' => isset($fullDayLeaveDates[$dateString]),
                    'has_short_day_leave' => isset($shortDayLeaveDates[$dateString])
                ]);
            }
            
            $dayOfWeek = $currentDate->dayOfWeekIso;
            $isWeeklyOff = in_array($dayOfWeek, $employee->weekly_off_days);
            
            if ($isWeeklyOff) {
                $weekendDays++;
                $attendanceDetails[$dateString] = [
                    'status' => 'weekend',
                    'status_text' => 'Weekly Off',
                    'is_working_day' => false,
                    'day_name' => $currentDate->format('l')
                ];
            } else {
                $workingDays++;
                
                // ✅ NEW ORDER: Check attendance records FIRST
                if (isset($attendanceRecords[$dateString])) {
                    $record = $attendanceRecords[$dateString];

                    // Prepare approved leaves for this date
                    $approvedLeavesForDate = [];
                    if (isset($fullDayLeaveDates[$dateString])) {
                        $approvedLeavesForDate['full_day'] = $fullDayLeaveDates[$dateString];
                    }
                    if (isset($shortDayLeaveDates[$dateString])) {
                        $leave = $shortDayLeaveDates[$dateString];
                        $durationType = strtolower($leave->leave_duration_type ?? '');
                        if (in_array($durationType, ['short leave', 'short_leave'])) {
                            $approvedLeavesForDate['short_leave'] = $leave;
                        } elseif (in_array($durationType, ['half day', 'half_day', 'half_days'])) {
                            $approvedLeavesForDate['half_day'] = $leave;
                        }
                    }
                    
                    // Debug log
                    \Log::info('Approved Leaves for Date: ' . $dateString, [
                        'short_leave' => isset($approvedLeavesForDate['short_leave']) ? $approvedLeavesForDate['short_leave']->id : null,
                        'half_day' => isset($approvedLeavesForDate['half_day']) ? $approvedLeavesForDate['half_day']->id : null,
                        'full_day' => isset($approvedLeavesForDate['full_day']) ? $approvedLeavesForDate['full_day']->id : null
                    ]);
                    
                    // Auto-detect attendance status
                    $attendanceResult = $this->calculateAttendanceStatusWithAutoDetection(
                        $record, 
                        $employee->resolved_shift, 
                        $employee->employee_id,
                        $approvedLeavesForDate
                    );
                    
                    // Get logs for display
                    $logs = $record->logs->sortBy('check_time');
                    $checkInLog = $logs->where('check_type', 'IN')->first();
                    $checkOutLog = $logs->where('check_type', 'OUT')->last();
                    
                    $checkLogs = $logs->map(function($log) {
                        return [
                            'type' => $log->check_type,
                            'time' => Carbon::parse($log->check_time, 'UTC')->setTimezone('Asia/Kolkata')->format('h:i A')
                        ];
                    })->values()->toArray();
                    
                    $workedHours = floatval($record->total_hours ?? 0);
                    $requiredHours = $employee->resolved_shift->working_hours ?? 9;
                    
                    // Update counts based on result
                    if ($attendanceResult['status'] === 'present') {
                        $presentDays++;
                        if (isset($attendanceResult['leave_type'])) {
                            $leaveCounts[$attendanceResult['leave_type']] = ($leaveCounts[$attendanceResult['leave_type']] ?? 0) + 1;
                        }
                    } elseif ($attendanceResult['status'] === 'absent') {
                        $absentDays++;
                    } elseif ($attendanceResult['status'] === 'leave') {
                        $leaveDays++;
                        $leaveCounts[$attendanceResult['leave_type']] = ($leaveCounts[$attendanceResult['leave_type']] ?? 0) + 1;
                    }
                    
                    // Build attendance details
                    $attendanceDetails[$dateString] = [
                        'status' => $attendanceResult['status'],
                        'status_text' => $attendanceResult['status_text'],
                        'is_working_day' => true,
                        'day_name' => $currentDate->format('l'),
                        'check_in' => $checkInLog ? Carbon::parse($checkInLog->check_time, 'UTC')->setTimezone('Asia/Kolkata')->format('h:i A') : null,
                        'check_out' => $checkOutLog ? Carbon::parse($checkOutLog->check_time, 'UTC')->setTimezone('Asia/Kolkata')->format('h:i A') : null,
                        'check_logs' => $checkLogs,
                        'total_hours' => round($workedHours, 2),
                        'required_hours' => $requiredHours,
                        'short_hours' => max(0, $requiredHours - $workedHours),
                        'leave_type' => $attendanceResult['leave_type'] ?? null,
                        'is_approved' => $attendanceResult['is_approved'] ?? false,
                        'is_auto_detected' => $attendanceResult['is_auto_detected'] ?? false,
                        'within_quota' => $attendanceResult['within_quota'] ?? false,
                        'deduction_percentage' => $attendanceResult['deduction_percentage'] ?? 0,
                        'can_toggle_approval' => isset($attendanceResult['leave_type']) && !isset($approvedLeavesForDate['short_leave']) && !isset($approvedLeavesForDate['half_day'])
                    ];
                }
                // THEN check for full day leave (no attendance record)
                elseif (isset($fullDayLeaveDates[$dateString])) {
                    $leave = $fullDayLeaveDates[$dateString];
                    $leaveDays++;
                    $leaveTypeKey = $leave->leave_type ?: 'Other';
                    if (!isset($leaveCounts[$leaveTypeKey])) $leaveCounts[$leaveTypeKey] = 0;
                    $leaveCounts[$leaveTypeKey]++;
                    $attendanceDetails[$dateString] = [
                        'status' => 'leave',
                        'status_text' => ucfirst($leave->leave_type),
                        'is_working_day' => true,
                        'day_name' => $currentDate->format('l'),
                        'approved_leave' => true,
                        'leave_type' => $leave->leave_type
                    ];
                }
                // THEN check for short/half day leave (no attendance record)
                elseif (isset($shortDayLeaveDates[$dateString])) {
                    $shortDayLeave = $shortDayLeaveDates[$dateString];
                    $presentDays++;
                    
                    $leaveType = ucfirst($shortDayLeave->leave_duration_type);
                    if (!isset($leaveCounts[$leaveType])) $leaveCounts[$leaveType] = 0;
                    $leaveCounts[$leaveType]++;
                    
                    $deductionConfig = $this->getLeaveDeductionConfig($shortDayLeave->leave_duration_type);
                    $leaveCategory = $deductionConfig['leave_category'];
                    
                    $daysToDeduct = 0;
                    if ($leaveCategory === 'short_leave') {
                        $daysToDeduct = 0.25;
                    } elseif ($leaveCategory === 'half_day') {
                        $daysToDeduct = 0.5;
                    }
                    
                    $attendanceDetails[$dateString] = [
                        'status' => 'present',
                        'status_text' => 'Present + ' . ucfirst($shortDayLeave->leave_duration_type),
                        'is_working_day' => true,
                        'day_name' => $currentDate->format('l'),
                        'approved_leave' => true,
                        'leave_type' => $shortDayLeave->leave_duration_type,
                        'leave_category' => $leaveCategory,
                        'days_to_deduct' => $daysToDeduct
                    ];
                }
                else {
                    $absentDays++;
                    $attendanceDetails[$dateString] = [
                        'status' => 'absent',
                        'status_text' => 'Absent',
                        'is_working_day' => true,
                        'day_name' => $currentDate->format('l')
                    ];
                }
            }
            
            $currentDate->addDay();
        }
        
        return [
            'employee_name' => $employee->name,
            'employee_code' => $employee->employee_code,
            'department' => $employee->department_name ?? 'No Department',
            'department_id' => $employee->department_id,
            'shift_info' => $employee->resolved_shift ? [
                'shift_name' => $employee->resolved_shift->shift_name,
                'shift_timing' => $employee->resolved_shift->start_time . ' - ' . $employee->resolved_shift->end_time,
                'weekly_offs' => $this->getWeeklyOffNames($employee->weekly_off_days)
            ] : null,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'leave_days' => $leaveDays,
            'weekend_days' => $weekendDays,
            'working_days' => $workingDays,
            'unapproved_leave_days' => $unapprovedLeaveDays,
            'leave_counts' => $leaveCounts,
            'attendance_percentage' => $workingDays > 0 ? round(($presentDays / $workingDays) * 100, 2) : 0,
            'attendance_details' => $attendanceDetails
        ];
    }
    
    private function determineAttendanceStatus($workedHours, $requiredHours)
    {
        if ($workedHours >= $requiredHours) {
            return ['status' => 'present', 'status_text' => 'Present'];
        } elseif ($workedHours >= ($requiredHours / 2)) {
            return ['status' => 'present', 'status_text' => 'Present (Partial)'];
        } elseif ($workedHours > 0) {
            return ['status' => 'short_attendance', 'status_text' => 'Short Attendance'];
        } else {
            return ['status' => 'absent', 'status_text' => 'Absent'];
        }
    }

    private function updateLeaveBalancesFromAttendance($employeeId, $year, $attendanceDetails)
    {
        $merchantId = auth()->user()->institute_id;
        $sessionYear = $this->getCurrentAcademicSession();
        
        $shortLeavesUsed = 0;
        $halfDaysUsed = 0;
        
        foreach ($attendanceDetails as $details) {
            $leaveType = $details['leave_type'] ?? '';
            $status = $details['status'] ?? '';
            
            if (($status === 'present' || $status === 'leave') && !empty($leaveType)) {
                $normalizedType = strtolower(str_replace(' ', '_', $leaveType));
                if (in_array($normalizedType, ['short_leave', 'short leave'])) {
                    $shortLeavesUsed++;
                } elseif (in_array($normalizedType, ['half_day', 'half_days', 'half day'])) {
                    $halfDaysUsed++;
                }
            }
        }
        
        $sessionFormats = [$sessionYear, $year . '-' . ($year + 1), ($year - 1) . '-' . $year, $year];
        $sessionFormats = array_unique($sessionFormats);
        
        if ($shortLeavesUsed > 0) {
            $shortLeaveBalance = EmployeeLeaveBalance::where('employee_id', $employeeId)
                ->where('institute_id', $merchantId)
                ->where(function($query) {
                    $query->where('leave_type', 'Short Leave')->orWhere('leave_type', 'short_leave');
                })
                ->whereIn('session_year', $sessionFormats)
                ->first();
            
            if ($shortLeaveBalance) {
                $currentRemaining = floatval($shortLeaveBalance->remaining ?? 0);
                $totalAllocated = floatval($shortLeaveBalance->total_allocated ?? 0);
                $newRemaining = max(0, $currentRemaining - $shortLeavesUsed);
                $shortLeaveBalance->update(['used' => $totalAllocated - $newRemaining, 'remaining' => $newRemaining]);
            }
        }
        
        if ($halfDaysUsed > 0) {
            $halfDayBalance = EmployeeLeaveBalance::where('employee_id', $employeeId)
                ->where('institute_id', $merchantId)
                ->where(function($query) {
                    $query->where('leave_type', 'Half Day')->orWhere('leave_type', 'half_day');
                })
                ->whereIn('session_year', $sessionFormats)
                ->first();
            
            if ($halfDayBalance) {
                $currentRemaining = floatval($halfDayBalance->remaining ?? 0);
                $totalAllocated = floatval($halfDayBalance->total_allocated ?? 0);
                $newRemaining = max(0, $currentRemaining - $halfDaysUsed);
                $halfDayBalance->update(['used' => $totalAllocated - $newRemaining, 'remaining' => $newRemaining]);
            }
        }
    }

    // ========== SALARY SLIP CREATION FUNCTIONS (PRESERVED) ==========

    private function createSalarySlipEntry($employee, $year, $month, $context)
    {
        $salaryMonth = Carbon::create($year, $month, 1);
        $salaryMonthFormatted = $salaryMonth->format('Y-m');
        
        $payrollConfig = $this->getPayrollConfiguration($employee->department_id, $context['institute_id']);
        
        $date = Carbon::create($year, $month, 1);
        $shiftData = $employee->resolveEffectiveShift($date);
        $employee->resolved_shift = $shiftData['shift'] ?? null;
        
        $salaryStructure = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
            ->where('institute_id', $context['institute_id'])
            ->where(function($query) use ($context) {
                $query->where('branch_id', $context['branch_id'])->orWhereNull('branch_id');
            })
            ->first();

        if (!$salaryStructure) {
            throw new \Exception('No salary structure found for employee: ' . $employee->name);
        }
        
        $salaryPreview = SalaryPreview::where('salary_structure_id', $salaryStructure->salary_structure_id)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        if (!$salaryPreview) {
            throw new \Exception('No salary preview found for salary structure');
        }
        
        $attendanceReview = AttendanceReview::where('employee_id', $employee->employee_id)
            ->where('year', $year)
            ->where('month', $month)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        $leaveData = $this->calculateLeaveDeductionsForSalarySlip(
            $employee->employee_id,
            $salaryMonth->copy()->startOfMonth(),
            $salaryMonth->copy()->endOfMonth(),
            $context,
            $payrollConfig
        );
        
        if ($payrollConfig && $payrollConfig->payroll_cycle === 'days') {
            $salaryCalculation = $this->calculateDailyCycleSalary(
                $employee,
                $attendanceReview,
                $salaryPreview,
                $salaryMonth,
                $payrollConfig,
                $leaveData
            );
        } else {
            $salaryCalculation = $this->calculateMonthlyCycleSalary(
                $employee,
                $attendanceReview,
                $salaryPreview,
                $salaryMonth,
                $leaveData
            );
        }
        
        $dailyRate = $salaryCalculation['daily_rate'];
        $absentDeduction = $salaryCalculation['absent_deduction'];
        $leaveDeductionAmount = $salaryCalculation['leave_deduction_amount'];
        $shortAttendanceDeduction = $salaryCalculation['short_attendance_deduction'];
        
        // New: Get quota-based deductions (within quota vs exceeded)
        $shortLeavesWithinQuotaDeduction = $salaryCalculation['short_leaves_within_quota_deduction'] ?? 0;
        $shortLeavesExceededDeduction = $salaryCalculation['short_leaves_exceeded_deduction'] ?? 0;
        $halfDaysWithinQuotaDeduction = $salaryCalculation['half_days_within_quota_deduction'] ?? 0;
        $halfDaysExceededDeduction = $salaryCalculation['half_days_exceeded_deduction'] ?? 0;
        
        $unpaidLeaveDeduction = $salaryCalculation['unpaid_leave_deduction'];
        $unapprovedLeaveDeduction = 0;
        
        $leaveDeductionComponent = max(0, $leaveDeductionAmount - $unpaidLeaveDeduction - $unapprovedLeaveDeduction);
        $monthlyNetSalary = $salaryPreview->net_salary_monthly;
        $totalAttendanceDeductions = $absentDeduction + $leaveDeductionAmount;
        $payableSalary = max(0, $monthlyNetSalary - $totalAttendanceDeductions);
        
        $calendarDaysInMonth = $salaryCalculation['days_in_period'];
        $absentDays = $salaryCalculation['absent_days'];
        $unpaidLeaveDays = $salaryCalculation['unpaid_leave_days'];
        $unapprovedLeaveDays = $salaryCalculation['unapproved_leave_days'];
        
        // Get quota usage details
        $shortLeavesWithinQuota = $salaryCalculation['short_leaves_within_quota'] ?? 0;
        $shortLeavesExceeded = $salaryCalculation['short_leaves_exceeded'] ?? 0;
        $shortLeavesAllocated = $salaryCalculation['short_leaves_allocated'] ?? 0;
        $halfDaysWithinQuota = $salaryCalculation['half_days_within_quota'] ?? 0;
        $halfDaysExceeded = $salaryCalculation['half_days_exceeded'] ?? 0;
        $halfDaysAllocated = $salaryCalculation['half_days_allocated'] ?? 0;
        
        $totalUnpaidDays = $salaryCalculation['total_unpaid_days'];
        
        $earnings = [
            'basic_salary' => $salaryPreview->basic_salary_monthly,
            'hra' => $salaryPreview->hra_monthly ?? 0,
            'conveyance' => $salaryPreview->conveyance_monthly ?? 0,
            'medical' => $salaryPreview->medical_monthly ?? 0,
            'special_allowance' => $salaryPreview->special_allowance_monthly ?? 0,
            'lta' => $salaryPreview->lta_monthly ?? 0,
            'education_allowance' => $salaryPreview->education_allowance_monthly ?? 0,
        ];
        
        $standardDeductions = [
            'professional_tax' => $salaryPreview->pt_monthly ?? 0,
            'labour_welfare' => $salaryPreview->lst_monthly ?? 0,
            'tds' => $salaryPreview->tds_monthly ?? 0,
            'insurance_premium' => $salaryPreview->insurance_premium_monthly ?? 0,
            'advance_salary' => $salaryPreview->advance_salary_monthly ?? 0,
            'pf_employee' => $salaryPreview->pf_employee_monthly ?? 0,
            'esi_employee' => $salaryPreview->esi_employee_monthly ?? 0,
        ];
        
        $totalStandardDeductions = array_sum($standardDeductions);
        
        // Calculate total attendance deductions (sum of all components)
        $totalAttendanceDeductionsCalculated = $absentDeduction + 
                                            $unpaidLeaveDeduction + 
                                            $unapprovedLeaveDeduction + 
                                            $shortAttendanceDeduction + 
                                            $shortLeavesWithinQuotaDeduction + 
                                            $shortLeavesExceededDeduction + 
                                            $halfDaysWithinQuotaDeduction + 
                                            $halfDaysExceededDeduction;
        
        $finalNetSalary = max(0, $payableSalary - $totalStandardDeductions);
        
        $salarySlipId = 'SLIP-' . $salaryMonth->format('Ym') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        $salaryDetailsJson = [
            'salaryslip_id' => $salarySlipId,
            'employee_id' => $employee->employee_id,
            'name' => $employee->name,
            'salary_month' => $salaryMonthFormatted,
            'year' => $year,
            'month' => $month,
            'payroll_cycle' => $payrollConfig ? $payrollConfig->payroll_cycle : 'monthly',
            'basic_salary' => $salaryPreview->basic_salary_monthly,
            'gross_salary' => $salaryPreview->gross_salary_monthly,
            'monthly_net_salary' => $monthlyNetSalary,
            'payable_salary' => $payableSalary,
            'final_net_salary' => $payableSalary,
            'daily_rate' => round($dailyRate, 2),
            'attendance_summary' => [
                'present_days' => $attendanceReview ? $attendanceReview->present_days : 0,
                'absent_days' => $absentDays,
                'leave_days' => $attendanceReview ? $attendanceReview->leave_days : 0,
                'weekend_days' => $attendanceReview ? $attendanceReview->weekend_days : 0,
                'working_days' => $attendanceReview ? $attendanceReview->working_days : 0,
                'short_attendance_days' => $attendanceReview ? ($attendanceReview->short_attendance_days ?? 0) : 0,
                'unapproved_leave_days' => $unapprovedLeaveDays,
                'attendance_percentage' => $attendanceReview ? $attendanceReview->attendance_percentage : 0,
                'short_leaves_within_quota' => $shortLeavesWithinQuota,
                'short_leaves_exceeded' => $shortLeavesExceeded,
                'short_leaves_allocated' => $shortLeavesAllocated,
                'half_days_within_quota' => $halfDaysWithinQuota,
                'half_days_exceeded' => $halfDaysExceeded,
                'half_days_allocated' => $halfDaysAllocated,
            ],
            'earnings' => $earnings,
            'standard_deductions' => $standardDeductions,
            'attendance_deductions' => [
                'absent_deduction' => round($absentDeduction, 2),
                'unpaid_leave_deduction' => round($unpaidLeaveDeduction, 2),
                'unapproved_leave_deduction' => round($unapprovedLeaveDeduction, 2),
                'short_attendance_deduction' => round($shortAttendanceDeduction, 2),
                'short_leaves_within_quota_deduction' => round($shortLeavesWithinQuotaDeduction, 2),
                'short_leaves_exceeded_deduction' => round($shortLeavesExceededDeduction, 2),
                'half_days_within_quota_deduction' => round($halfDaysWithinQuotaDeduction, 2),
                'half_days_exceeded_deduction' => round($halfDaysExceededDeduction, 2),
            ],
            'total_earnings' => array_sum($earnings),
            'total_standard_deductions' => $totalStandardDeductions,
            'total_attendance_deductions' => $totalAttendanceDeductionsCalculated,
            'total_deductions' => $totalStandardDeductions + $totalAttendanceDeductionsCalculated,
            'leave_details' => $leaveData,
            'created_by' => auth()->user()->employee_id ?? auth()->user()->id,
            'created_at' => now()->toISOString(),
        ];
        
        $existingSlip = SalarySlip::where('employee_id', $employee->employee_id)
            ->where('salary_month', $salaryMonthFormatted)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        if ($existingSlip) {
            $existingSlip->update([
                'basic_salary' => $salaryPreview->basic_salary_monthly,
                'gross_salary' => $salaryPreview->gross_salary_monthly,
                'monthly_net_salary' => $monthlyNetSalary,
                'payable_salary' => $payableSalary,
                'net_salary' => $payableSalary,
                'leave_deduction' => round($leaveDeductionComponent, 2),
                'absent_deduction' => round($absentDeduction, 2),
                'unpaid_leave_deduction' => round($unpaidLeaveDeduction, 2),
                'unapproved_leave_deduction' => round($unapprovedLeaveDeduction, 2),
                'short_attendance_deduction' => round($shortAttendanceDeduction, 2),
                'short_leaves_within_quota_deduction' => round($shortLeavesWithinQuotaDeduction, 2),
                'short_leaves_exceeded_deduction' => round($shortLeavesExceededDeduction, 2),
                'half_days_within_quota_deduction' => round($halfDaysWithinQuotaDeduction, 2),
                'half_days_exceeded_deduction' => round($halfDaysExceededDeduction, 2),
                'status' => 'pending',
                'generated_date' => now()->toDateString(),
                'salary_details_json' => json_encode($salaryDetailsJson),
            ]);
            $salarySlip = $existingSlip;
        } else {
            $salarySlip = SalarySlip::create([
                'salaryslip_id' => $salarySlipId,
                'employee_id' => $employee->employee_id,
                'name' => $employee->name,
                'salary_month' => $salaryMonthFormatted,
                'basic_salary' => $salaryPreview->basic_salary_monthly,
                'gross_salary' => $salaryPreview->gross_salary_monthly,
                'monthly_net_salary' => $monthlyNetSalary,
                'payable_salary' => $payableSalary,
                'net_salary' => $payableSalary,
                'leave_deduction' => round($leaveDeductionComponent, 2),
                'absent_deduction' => round($absentDeduction, 2),
                'unpaid_leave_deduction' => round($unpaidLeaveDeduction, 2),
                'unapproved_leave_deduction' => round($unapprovedLeaveDeduction, 2),
                'short_attendance_deduction' => round($shortAttendanceDeduction, 2),
                'short_leaves_within_quota_deduction' => round($shortLeavesWithinQuotaDeduction, 2),
                'short_leaves_exceeded_deduction' => round($shortLeavesExceededDeduction, 2),
                'half_days_within_quota_deduction' => round($halfDaysWithinQuotaDeduction, 2),
                'half_days_exceeded_deduction' => round($halfDaysExceededDeduction, 2),
                'status' => 'pending',
                'generated_date' => now()->toDateString(),
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['branch_id'],
                'salary_details_json' => json_encode($salaryDetailsJson),
            ]);
        }
        
        return ['success' => true, 'salaryslip_id' => $salarySlipId, 'employee_name' => $employee->name];
    }

    private function getPayrollConfiguration($departmentId, $instituteId)
    {
        if (!$departmentId) return null;
        return PayrollExecution::where('department_id', $departmentId)->where('institute_id', $instituteId)->first();
    }

    private function calculateMonthlyCycleSalary($employee, $attendanceReview, $salaryPreview, $salaryMonth, $leaveData)
    {
        // Get payroll configuration for this employee's department
        $payrollConfig = null;
        if ($employee && $employee->department_id) {
            $payrollConfig = PayrollExecution::where('department_id', $employee->department_id)
                ->where('institute_id', $employee->institute_id)
                ->first();
        }
        
        // Calculate days in period based on payroll cycle
        if ($payrollConfig && $payrollConfig->payroll_cycle === 'days') {
            $daysInPeriod = $payrollConfig->cycle_days ?? 30;
            $dailyRate = $daysInPeriod > 0 ? $salaryPreview->net_salary_monthly / $daysInPeriod : 0;
        } else {
            $daysInPeriod = $salaryMonth->copy()->endOfMonth()->day;
            $dailyRate = $daysInPeriod > 0 ? $salaryPreview->net_salary_monthly / $daysInPeriod : 0;
        }
        
        // Get basic attendance data
        $absentDays = $attendanceReview ? $attendanceReview->absent_days : 0;
        $unapprovedLeaveDays = $attendanceReview ? ($attendanceReview->unapproved_leave_days ?? 0) : 0;
        $unpaidLeaveDays = $leaveData['unpaid_leave_days'] ?? 0;
        
        // Calculate short attendance deduction (if any)
        $shortAttendanceData = $this->calculateShortAttendanceDeduction($attendanceReview, $employee, $dailyRate);
        
        // Calculate quota data (within quota vs exceeded)
        $quotaData = $this->calculateQuotaData($attendanceReview, $employee, $dailyRate, $salaryMonth);
        
        // Calculate deductions
        $absentDeduction = $dailyRate * $absentDays;
        $unpaidLeaveDeduction = $dailyRate * $unpaidLeaveDays;  // Fixed: was $unpaidLeaveDeductionDays
        $unapprovedFullDayCount = array_sum($quotaData['unapproved_full_day_leaves'] ?? []);
        $unapprovedLeaveDeduction = $dailyRate * $unapprovedFullDayCount;
        
        
        // Total leave deduction amount
        $leaveDeductionAmount = $unpaidLeaveDeduction + $unapprovedLeaveDeduction + 
                            $shortAttendanceData['deduction'] + 
                            $quotaData['total_short_leave_deduction'] + 
                            $quotaData['total_half_day_deduction'];
        
        $baseNetSalary = $salaryPreview->net_salary_monthly;
        $netSalary = $baseNetSalary - ($absentDeduction + $leaveDeductionAmount);
        $totalUnpaidDays = $absentDays + $unpaidLeaveDays + $unapprovedLeaveDays;
        
        return [
            'daily_rate' => $dailyRate,
            'absent_deduction' => $absentDeduction,
            'leave_deduction_amount' => $leaveDeductionAmount,
            'short_attendance_deduction' => $shortAttendanceData['deduction'],
            
            // Short Leave details
            'short_leaves_within_quota' => $quotaData['short_leaves_within_quota'],
            'short_leaves_exceeded' => $quotaData['short_leaves_exceeded'],
            'short_leaves_within_quota_deduction' => $quotaData['short_leaves_within_quota_deduction'],
            'short_leaves_exceeded_deduction' => $quotaData['short_leaves_exceeded_deduction'],
            'short_leaves_allocated' => $quotaData['short_leaves_allocated'],
            'total_short_leave_deduction' => $quotaData['total_short_leave_deduction'],
            
            // Half Day details
            'half_days_within_quota' => $quotaData['half_days_within_quota'],
            'half_days_exceeded' => $quotaData['half_days_exceeded'],
            'half_days_within_quota_deduction' => $quotaData['half_days_within_quota_deduction'],
            'half_days_exceeded_deduction' => $quotaData['half_days_exceeded_deduction'],
            'half_days_allocated' => $quotaData['half_days_allocated'],
            'total_half_day_deduction' => $quotaData['total_half_day_deduction'],
            
            // Full day leaves
            'approved_full_day_leaves' => $quotaData['approved_full_day_leaves'],
            'unapproved_full_day_leaves' => $quotaData['unapproved_full_day_leaves'],
            
            // Other deductions
            'unpaid_leave_deduction' => $unpaidLeaveDeduction,
            'unapproved_leave_deduction' => $unapprovedLeaveDeduction,
            
            'base_net_salary' => $baseNetSalary,
            'net_salary' => max(0, $netSalary),
            'days_in_period' => $daysInPeriod,
            'absent_days' => $absentDays,
            'unpaid_leave_days' => $unpaidLeaveDays,
            'unapproved_leave_days' => $unapprovedLeaveDays,
            'total_unpaid_days' => $totalUnpaidDays
        ];
    }

    /**
     * Calculate quota data using actual deduction percentages from each day
     * 
     * - These are per-instance deductions, NOT multiplied by daily rate again
     */
    private function calculateQuotaData($attendanceReview, $employee, $dailyRate, $salaryMonth)
    {
        $shortLeavesWithinQuota = 0;
        $shortLeavesExceeded = 0;
        $halfDaysWithinQuota = 0;
        $halfDaysExceeded = 0;
        $approvedFullDayLeaves = [];
        $unapprovedFullDayLeaves = [];
        
        // Per-instance deduction accumulators
        $shortLeavesWithinQuotaDeduction = 0;
        $shortLeavesExceededDeduction = 0;
        $halfDaysWithinQuotaDeduction = 0;
        $halfDaysExceededDeduction = 0;
        
        $shortLeavesAllocated = 0;
        $halfDaysAllocated = 0;
        
        if ($attendanceReview && $attendanceReview->attendance_details) {
            $attendanceDetails = is_string($attendanceReview->attendance_details) 
                ? json_decode($attendanceReview->attendance_details, true) 
                : $attendanceReview->attendance_details;
            
            if (is_array($attendanceDetails)) {
                foreach ($attendanceDetails as $date => $details) {
                    $status = $details['status'] ?? '';
                    $leaveType = $details['leave_type'] ?? '';
                    $withinQuota = $details['within_quota'] ?? false;
                    $isApproved = $details['is_approved'] ?? false;
                    $deductionPercentage = $details['deduction_percentage'] ?? 0;
                    
                    // Count Short Leaves and Half Days with per-instance deduction
                    if ($status === 'present' && !empty($leaveType)) {
                        $normalizedType = strtolower($leaveType);
                        
                        if (strpos($normalizedType, 'short') !== false) {
                            // Short Leave: Each instance = (daily_rate * percentage/100)
                            $instanceDeduction = $dailyRate  * ($deductionPercentage / 100);
                            
                            if ($withinQuota) {
                                $shortLeavesWithinQuota++;
                                $shortLeavesWithinQuotaDeduction += $instanceDeduction;
                            } else {
                                $shortLeavesExceeded++;
                                $shortLeavesExceededDeduction += $instanceDeduction;
                            }
                        } elseif (strpos($normalizedType, 'half') !== false) {
                            // Half Day: Each instance = (daily_rate  * percentage/100)
                            $instanceDeduction = $dailyRate  * ($deductionPercentage / 100);
                            
                            if ($withinQuota) {
                                $halfDaysWithinQuota++;
                                $halfDaysWithinQuotaDeduction += $instanceDeduction;
                            } else {
                                $halfDaysExceeded++;
                                $halfDaysExceededDeduction += $instanceDeduction;
                            }
                        }
                    }
                    
                    // Count Full Day Leaves (approved vs unapproved) - These are counted as days
                    if ($status === 'leave' && !empty($leaveType)) {
                        if ($isApproved) {
                            $key = $leaveType;
                            if (!isset($approvedFullDayLeaves[$key])) {
                                $approvedFullDayLeaves[$key] = 0;
                            }
                            $approvedFullDayLeaves[$key]++;
                        } else {
                            $key = $leaveType;
                            if (!isset($unapprovedFullDayLeaves[$key])) {
                                $unapprovedFullDayLeaves[$key] = 0;
                            }
                            $unapprovedFullDayLeaves[$key]++;
                        }
                    }
                }
            }
        }
        
        // Get allocated quotas from leave_balances
        $merchantId = auth()->user()->institute_id;
        $sessionYear = $this->getCurrentAcademicSession();
        $sessionFormats = [$sessionYear, $salaryMonth->format('Y') . '-' . ($salaryMonth->format('Y') + 1), ($salaryMonth->format('Y') - 1) . '-' . $salaryMonth->format('Y'), $salaryMonth->format('Y')];
        $sessionFormats = array_unique($sessionFormats);
        
        // Get Short Leave quota
        $shortLeaveBalance = EmployeeLeaveBalance::where('employee_id', $employee->employee_id)
            ->where('institute_id', $merchantId)
            ->where(function($query) {
                $query->where('leave_type', 'Short Leave')
                    ->orWhere('leave_type', 'short_leave')
                    ->orWhere('leave_type', 'Short Day Leave');
            })
            ->whereIn('session_year', $sessionFormats)
            ->first();
        
        // Get Half Day quota
        $halfDayBalance = EmployeeLeaveBalance::where('employee_id', $employee->employee_id)
            ->where('institute_id', $merchantId)
            ->where(function($query) {
                $query->where('leave_type', 'Half Day')
                    ->orWhere('leave_type', 'half_day')
                    ->orWhere('leave_type', 'Half Day Leave');
            })
            ->whereIn('session_year', $sessionFormats)
            ->first();
        
        $shortLeavesAllocated = $shortLeaveBalance ? floatval($shortLeaveBalance->total_allocated ?? 0) : 0;
        $halfDaysAllocated = $halfDayBalance ? floatval($halfDayBalance->total_allocated ?? 0) : 0;
        
        return [
            // Instance counts
            'short_leaves_within_quota' => $shortLeavesWithinQuota,
            'short_leaves_exceeded' => $shortLeavesExceeded,
            'half_days_within_quota' => $halfDaysWithinQuota,
            'half_days_exceeded' => $halfDaysExceeded,
            
            // Per-instance deductions
            'short_leaves_within_quota_deduction' => $shortLeavesWithinQuotaDeduction,
            'short_leaves_exceeded_deduction' => $shortLeavesExceededDeduction,
            'half_days_within_quota_deduction' => $halfDaysWithinQuotaDeduction,
            'half_days_exceeded_deduction' => $halfDaysExceededDeduction,
            
            // Total deductions for easy access
            'total_short_leave_deduction' => $shortLeavesWithinQuotaDeduction + $shortLeavesExceededDeduction,
            'total_half_day_deduction' => $halfDaysWithinQuotaDeduction + $halfDaysExceededDeduction,
            
            // Quota allocations
            'short_leaves_allocated' => $shortLeavesAllocated,
            'half_days_allocated' => $halfDaysAllocated,
            
            // Full day leaves
            'approved_full_day_leaves' => $approvedFullDayLeaves,
            'unapproved_full_day_leaves' => $unapprovedFullDayLeaves,
        ];
    }

    private function calculateDailyCycleSalary($employee, $attendanceReview, $salaryPreview, $salaryMonth, $payrollConfig, $leaveData)
    {
        $cycleDays = $payrollConfig->cycle_days ?? 30;
        $dailyRate = $cycleDays > 0 ? $salaryPreview->net_salary_monthly / $cycleDays : 0;
        
        $absentDays = $attendanceReview ? $attendanceReview->absent_days : 0;
        $unapprovedLeaveDays = $attendanceReview ? ($attendanceReview->unapproved_leave_days ?? 0) : 0;
        $unpaidLeaveDays = $leaveData['unpaid_leave_days'] ?? 0;
        
        $shortAttendanceData = $this->calculateShortAttendanceDeduction($attendanceReview, $employee, $dailyRate);
        $quotaData = $this->calculateQuotaData($attendanceReview, $employee, $dailyRate, $salaryMonth);
        
        $absentDeduction = $dailyRate * $absentDays;
        $unpaidLeaveDeduction = $dailyRate * $unpaidLeaveDays;
        $unapprovedLeaveDeduction = $dailyRate * $unapprovedLeaveDays;
        
        $leaveDeductionAmount = $unpaidLeaveDeduction + $unapprovedLeaveDeduction + 
                            $shortAttendanceData['deduction'] + 
                            $quotaData['total_short_leave_deduction'] + 
                            $quotaData['total_half_day_deduction'];
        
        $baseNetSalary = $salaryPreview->net_salary_monthly;
        $netSalary = $baseNetSalary - ($absentDeduction + $leaveDeductionAmount);
        $totalUnpaidDays = $absentDays + $unpaidLeaveDays + $unapprovedLeaveDays;
        
        return [
            'daily_rate' => $dailyRate,
            'absent_deduction' => $absentDeduction,
            'leave_deduction_amount' => $leaveDeductionAmount,
            'short_attendance_deduction' => $shortAttendanceData['deduction'],
            
            'short_leaves_within_quota' => $quotaData['short_leaves_within_quota'],
            'short_leaves_exceeded' => $quotaData['short_leaves_exceeded'],
            'short_leaves_within_quota_deduction' => $quotaData['short_leaves_within_quota_deduction'],
            'short_leaves_exceeded_deduction' => $quotaData['short_leaves_exceeded_deduction'],
            'short_leaves_allocated' => $quotaData['short_leaves_allocated'],
            'total_short_leave_deduction' => $quotaData['total_short_leave_deduction'],
            
            'half_days_within_quota' => $quotaData['half_days_within_quota'],
            'half_days_exceeded' => $quotaData['half_days_exceeded'],
            'half_days_within_quota_deduction' => $quotaData['half_days_within_quota_deduction'],
            'half_days_exceeded_deduction' => $quotaData['half_days_exceeded_deduction'],
            'half_days_allocated' => $quotaData['half_days_allocated'],
            'total_half_day_deduction' => $quotaData['total_half_day_deduction'],
            
            'approved_full_day_leaves' => $quotaData['approved_full_day_leaves'],
            'unapproved_full_day_leaves' => $quotaData['unapproved_full_day_leaves'],
            
            'unpaid_leave_deduction' => $unpaidLeaveDeduction,
            'unapproved_leave_deduction' => $unapprovedLeaveDeduction,
            
            'base_net_salary' => $baseNetSalary,
            'net_salary' => max(0, $netSalary),
            'days_in_period' => $cycleDays,
            'absent_days' => $absentDays,
            'unpaid_leave_days' => $unpaidLeaveDays,
            'unapproved_leave_days' => $unapprovedLeaveDays,
            'total_unpaid_days' => $totalUnpaidDays
        ];
    }

    private function calculateShortAttendanceDeduction($attendanceReview, $employee, $dailyRate)
    {
        $deduction = 0;
        $shortAttendanceDays = 0;
        
        if ($attendanceReview && isset($attendanceReview->attendance_details)) {
            $shortAttendanceDays = $attendanceReview->short_attendance_days ?? 0;
            
            if ($shortAttendanceDays > 0) {
                $totalShortHours = 0;
                $shortDayCount = 0;
                
                $attendanceDetails = is_string($attendanceReview->attendance_details) 
                    ? json_decode($attendanceReview->attendance_details, true) 
                    : $attendanceReview->attendance_details;
                
                foreach ($attendanceDetails as $date => $details) {
                    if (isset($details['status']) && $details['status'] === 'short_attendance' && isset($details['short_hours'])) {
                        $totalShortHours += floatval($details['short_hours']);
                        $shortDayCount++;
                    }
                }
                
                $avgShortHours = $shortDayCount > 0 ? $totalShortHours / $shortDayCount : 0;
                $shiftHours = $employee->resolved_shift->working_hours ?? 9;
                
                if ($shiftHours > 0) {
                    $deduction = ($avgShortHours / $shiftHours) * $dailyRate * $shortAttendanceDays;
                }
            }
        }
        
        return ['deduction' => $deduction, 'days' => $shortAttendanceDays];
    }

    private function calculateLeaveDeductionsForSalarySlip($employeeId, $startDate, $endDate, $context, $payrollConfig = null)
    {
        $employee = EmployeeDetails::where('employee_id', $employeeId)->first();
        $shiftOffDays = $this->getEmployeeShiftOffDaysForSalarySlip($employee);
        
        // Calculate daily rate based on payroll configuration
        $dailyRate = $this->calculateDailyRateForEmployee($employeeId, $startDate, $context, $payrollConfig);
        
        $leaves = EmployeeLeave::where('employee_id', $employeeId)
            ->where('institute_id', $context['institute_id'])
            ->where('final_status', 'Approved')
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)->where('end_date', '>=', $endDate);
                    });
            })
            ->get();
        
        $totalLeaveDays = 0;
        $paidLeaves = 0;
        $unpaidLeaves = 0;
        $leaveBreakdown = [];
        
        foreach ($leaves as $leave) {
            $leaveStart = Carbon::parse($leave->start_date);
            $leaveEnd = Carbon::parse($leave->end_date);
            $effectiveStart = $leaveStart->gt($startDate) ? $leaveStart : $startDate;
            $effectiveEnd = $leaveEnd->lt($endDate) ? $leaveEnd : $endDate;
            
            $workingDaysInLeave = 0;
            $currentDate = $effectiveStart->copy();
            while ($currentDate <= $effectiveEnd) {
                if (!in_array($currentDate->dayOfWeek, $shiftOffDays)) {
                    $workingDaysInLeave++;
                }
                $currentDate->addDay();
            }
            
            $leaveType = $leave->leave_type;
            $isApproved = $leave->final_status === 'Approved';
            
            // Get deduction configuration from leave_deductions table
            $deductionConfig = $this->getLeaveDeductionConfig($leaveType);
            $percentage = $isApproved ? $deductionConfig['approved_percentage'] : $deductionConfig['unapproved_percentage'];
            
            // Get days to deduct based on leave category
            $daysToDeductPerDay = $this->getDaysToDeductForLeave($leaveType, $isApproved);
            
            $totalLeaveDuration = $leaveStart->diffInDays($leaveEnd) + 1;
            $daysToDeductThisMonth = $totalLeaveDuration > 0 ? ($workingDaysInLeave / $totalLeaveDuration) * $daysToDeductPerDay : 0;
            
            // Determine if leave is unpaid based on deduction percentage
            $isUnpaidLeave = $percentage >= 100;
            
            // Calculate deduction amount using the payroll-configuration-based daily rate
            $deductionAmount = ($dailyRate * $percentage * $daysToDeductThisMonth) / 100;
            
            if ($isUnpaidLeave) {
                $unpaidLeaves += $daysToDeductThisMonth;
            } else {
                $paidLeaves += $daysToDeductThisMonth;
            }
            
            $totalLeaveDays += $daysToDeductThisMonth;
            
            $leaveBreakdown[] = [
                'leave_id' => $leave->leave_id,
                'leave_type' => $leave->leave_type,
                'start_date' => $leave->start_date,
                'end_date' => $leave->end_date,
                'days_to_deduct' => round($daysToDeductThisMonth, 2),
                'deduction_percentage' => $percentage,
                'deduction_amount' => round($deductionAmount, 2),
                'paid_days' => round($isUnpaidLeave ? 0 : $daysToDeductThisMonth, 2),
                'unpaid_days' => round($isUnpaidLeave ? $daysToDeductThisMonth : 0, 2),
                'leave_category' => $deductionConfig['leave_category'],
                'daily_rate_used' => round($dailyRate, 2)
            ];
        }
        
        return [
            'total_leaves_taken' => round($totalLeaveDays, 2),
            'paid_leaves' => round($paidLeaves, 2),
            'unpaid_leave_days' => round($unpaidLeaves, 2),
            'daily_rate' => round($dailyRate, 2),
            'leave_breakdown' => $leaveBreakdown
        ];
    }

    /**
     * Calculate daily rate for an employee based on payroll configuration
     */
    private function calculateDailyRateForEmployee($employeeId, $date, $context, $payrollConfig = null)
    {
        $salaryStructure = EmployeeSalaryStructure::where('employee_id', $employeeId)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        if (!$salaryStructure) {
            return 0;
        }
        
        $salaryPreview = SalaryPreview::where('salary_structure_id', $salaryStructure->salary_structure_id)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        if (!$salaryPreview) {
            return 0;
        }
        
        $monthlyNetSalary = $salaryPreview->net_salary_monthly;
        
        // Get payroll configuration if not provided
        if (!$payrollConfig) {
            $employee = EmployeeDetails::where('employee_id', $employeeId)->first();
            if ($employee && $employee->department_id) {
                $payrollConfig = PayrollExecution::where('department_id', $employee->department_id)
                    ->where('institute_id', $context['institute_id'])
                    ->first();
            }
        }
        
        // Calculate days in period based on payroll cycle
        $daysInPeriod = 0;
        
        if ($payrollConfig && $payrollConfig->payroll_cycle === 'days') {
            // Daily cycle - use cycle_days
            $daysInPeriod = $payrollConfig->cycle_days ?? 30;
        } else {
            // Monthly cycle - use calendar days in month
            $daysInPeriod = Carbon::parse($date)->daysInMonth;
        }
        
        // Calculate daily rate
        return $daysInPeriod > 0 ? $monthlyNetSalary / $daysInPeriod : 0;
    }

    private function getEmployeeShiftOffDaysForSalarySlip($employee)
    {
        $defaultOffDays = [7];
        if (!$employee || !$employee->resolved_shift) return $defaultOffDays;
        
        $weeklyOffDays = $employee->resolved_shift->weekly_off_days;
        if (is_string($weeklyOffDays)) {
            $weeklyOffDays = json_decode($weeklyOffDays, true);
        }
        
        if (!is_array($weeklyOffDays)) return $defaultOffDays;
        
        $map = ['Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6, 'Sunday' => 7];
        $offDays = [];
        foreach ($weeklyOffDays as $day) {
            if (is_int($day) || ctype_digit(strval($day))) {
                $dayInt = intval($day);
                if ($dayInt >= 1 && $dayInt <= 7) $offDays[] = $dayInt;
            } elseif (isset($map[$day])) {
                $offDays[] = $map[$day];
            }
        }
        return array_unique($offDays) ?: $defaultOffDays;
    }

    private function getCurrentAcademicSession()
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        return $currentMonth >= 4 ? $currentYear . '-' . ($currentYear + 1) : ($currentYear - 1) . '-' . $currentYear;
    }

    private function parseWeeklyOffDays($weeklyOffData)
    {
        $dayMap = ['Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6, 'Sunday' => 7];
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

    private function getWeeklyOffNames($weeklyOffDays)
    {
        $dayNames = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'];
        $names = [];
        foreach ($weeklyOffDays as $day) {
            if (isset($dayNames[$day])) $names[] = $dayNames[$day];
        }
        return implode(', ', $names);
    }

    private function getDateRange($year, $month)
    {
        $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        return ['start' => $start, 'end' => $start->copy()->endOfMonth()];
    }
    
    private function getLeaveDeductionConfig($leaveType)
    {
        $merchantId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();
        
        // Map the normalized leave types to database values
        $dbLeaveTypeMap = [
            'short_leave' => ['Short Day Leave', 'Short Leave', 'Short Leave (Day)'],
            'half_day' => ['Half Day Leave', 'Half Day', 'Half Day Leave (Half Day)'],
            'casual_leave' => ['Casual Leave'],
            'sick_leave' => ['Sick Leave'],
            'earned_leave' => ['Earned Leave'],
            'unpaid_leave' => ['Unpaid Leave'],
            'maternity_leave' => ['Maternity Leave'],
            'study_leave' => ['Study Leave'],
            'absent' => ['Absent']
        ];
        
        // Get possible database leave type names
        $normalizedType = $this->normalizeLeaveType($leaveType);
        $possibleTypes = $dbLeaveTypeMap[$normalizedType] ?? [$leaveType];
        
        $deduction = null;
        
        // Try to find deduction by matching against possible database leave types
        foreach ($possibleTypes as $type) {
            $deduction = LeaveDeduction::forInstitute($merchantId, $context['branch_id'])
                ->where('leave_type', $type)
                ->where('is_active', true)
                ->first();
            
            if ($deduction) {
                break;
            }
        }
        
        // If still not found, try case-insensitive partial match
        if (!$deduction) {
            $deduction = LeaveDeduction::forInstitute($merchantId, $context['branch_id'])
                ->whereRaw('LOWER(leave_type) LIKE ?', ['%' . strtolower($leaveType) . '%'])
                ->where('is_active', true)
                ->first();
        }
        
        if ($deduction) {
            return [
                'approved_percentage' => floatval($deduction->approved_deduction_percentage),
                'unapproved_percentage' => floatval($deduction->unapproved_deduction_percentage),
                'is_custom' => $deduction->is_custom,
                'leave_category' => $deduction->leave_category ?? 'full_day',
                'requires_doctor_certificate' => $deduction->requires_doctor_certificate,
                'max_consecutive_days' => $deduction->max_consecutive_days,
                'max_days_per_year' => $deduction->max_days_per_year
            ];
        }
        
        // Default values if no configuration found
        return [
            'approved_percentage' => 0,
            'unapproved_percentage' => 100,
            'is_custom' => false,
            'leave_category' => 'full_day',
            'requires_doctor_certificate' => false,
            'max_consecutive_days' => null,
            'max_days_per_year' => null
        ];
    }

    private function normalizeLeaveType($leaveType)
    {
        $mappings = [
            'short leave' => 'short_leave',
            'short day leave' => 'short_leave',
            'shortday leave' => 'short_leave',
            'half day' => 'half_day',
            'half day leave' => 'half_day',
            'halfday' => 'half_day',
            'sick leave' => 'sick_leave',
            'casual leave' => 'casual_leave',
            'earned leave' => 'earned_leave',
            'unpaid leave' => 'unpaid_leave',
            'maternity leave' => 'maternity_leave',
            'study leave' => 'study_leave'
        ];
        
        $lower = strtolower(trim($leaveType));
        
        if (isset($mappings[$lower])) {
            return $mappings[$lower];
        }
        
        // Remove common words and convert to underscore
        $cleaned = preg_replace('/\s+(leave|day|type)/i', '', $lower);
        return str_replace([' ', '-'], '_', $cleaned);
    }

    /**
     * Get days to deduct based on leave category from deductions table
     */
    private function getDaysToDeductForLeave($leaveType, $isApproved = true)
    {
        $config = $this->getLeaveDeductionConfig($leaveType);
        $leaveCategory = $config['leave_category'];
        
        // Days to deduct based on leave category
        switch ($leaveCategory) {
            case 'half_day':
                return 0.5;
            case 'short_leave':
                return 0.25;
            case 'full_day':
            default:
                return 1.0;
        }
    }

    /**
     * Calculate deduction amount for a leave day based on configuration
     */
    private function calculateDeductionForLeaveDay($leaveType, $dailyRate, $isApproved = true)
    {
        $config = $this->getLeaveDeductionConfig($leaveType);
        $percentage = $isApproved ? $config['approved_percentage'] : $config['unapproved_percentage'];
        return ($dailyRate * $percentage) / 100;
    }

    private function calculateAttendanceStatusWithAutoDetection($record, $shift, $employeeId, $approvedLeavesForDate = [])
    {
        // Get shift values
        $requiredHours = floatval($shift->working_hours ?? 9);
        $halfDayThreshold = floatval($shift->half_day_hours ?? 4);
        $shortLeaveAllowance = floatval($shift->short_leave_hours ?? 2);
        $graceMinutes = floatval($shift->grace_minutes ?? 15);
        
        $workedHours = floatval($record->total_hours ?? 0);
        
        // ========== CALCULATE EFFECTIVE WORKED HOURS WITH GRACE PERIOD ==========
        // Get check-in time to determine if grace period applies
        $logs = $record->logs->sortBy('check_time');
        $checkInLog = $logs->where('check_type', 'IN')->first();
        $checkOutLog = $logs->where('check_type', 'OUT')->last();
        
        $effectiveWorkedHours = $workedHours;
        $graceApplied = false;
        $lateMinutes = 0;
        $isOnTime = true;
        
        if ($checkInLog && $shift) {
            try {
                $checkInTime = Carbon::parse($checkInLog->check_time, 'UTC')->setTimezone('Asia/Kolkata');
                $shiftStartTime = Carbon::createFromFormat('H:i:s', $shift->start_time, 'Asia/Kolkata');
                $shiftStartTime->setDate($checkInTime->year, $checkInTime->month, $checkInTime->day);
                $graceEndTime = $shiftStartTime->copy()->addMinutes($graceMinutes);
                
                if ($checkInTime->lte($graceEndTime)) {
                    // Within grace period - consider as on time
                    $graceApplied = true;
                    $lateMinutes = 0;
                    $isOnTime = true;
                    
                    // Recalculate effective worked hours from shift start time
                    if ($checkOutLog) {
                        $checkOutTime = Carbon::parse($checkOutLog->check_time, 'UTC')->setTimezone('Asia/Kolkata');
                        $effectiveWorkedHours = $checkOutTime->diffInMinutes($shiftStartTime) / 60;
                    }
                } else {
                    $lateMinutes = $checkInTime->diffInMinutes($shiftStartTime);
                    $isOnTime = false;
                    $effectiveWorkedHours = $workedHours; // Use actual worked hours
                }
            } catch (\Exception $e) {
                // Ignore parsing errors, use original worked hours
                $effectiveWorkedHours = $workedHours;
            }
        }
        
        $shortByHours = max(0, $requiredHours - $effectiveWorkedHours);
        $shortLeaveMinHours = $requiredHours - $shortLeaveAllowance;
        
        // Minimum short duration to qualify for Short Leave (30 minutes = 0.5 hours)
        $minShortLeaveDuration = 0.5;
        
        $currentDate = Carbon::parse($record->date);
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;
        
        // ========== 1. CHECK FOR APPROVED LEAVES FROM LEAVE APPLICATION ==========
        
        // Full Day Leave (e.g., Casual Leave, Sick Leave, Earned Leave)
        if (isset($approvedLeavesForDate['full_day'])) {
            $leave = $approvedLeavesForDate['full_day'];
            return [
                'status' => 'leave',
                'status_text' => 'Approved Leave (' . $leave->leave_type . ')',
                'leave_type' => $leave->leave_type,
                'is_approved' => true,
                'is_auto_detected' => false,
                'within_quota' => true,
                'deduction_percentage' => $this->getLeaveDeductionPercentage($leave->leave_type, true),
                'short_by_hours' => 0,
                'requires_approval' => false,
                'grace_applied' => false,
                'late_minutes' => 0
            ];
        }
        
        // Short Leave via Application (pre-approved)
        if (isset($approvedLeavesForDate['short_leave'])) {
            $leave = $approvedLeavesForDate['short_leave'];
            return [
                'status' => 'present',
                'status_text' => 'Present + Short Leave (Approved via Application)',
                'leave_type' => 'Short Leave',
                'is_approved' => true,
                'is_auto_detected' => false,
                'within_quota' => true,
                'deduction_percentage' => $this->getLeaveDeductionPercentage('Short Leave', true),
                'short_by_hours' => $shortByHours,
                'requires_approval' => false,
                'grace_applied' => $graceApplied,
                'late_minutes' => $lateMinutes,
                'effective_hours' => round($effectiveWorkedHours, 2)
            ];
        }
        
        // Half Day via Application (pre-approved)
        if (isset($approvedLeavesForDate['half_day'])) {
            $leave = $approvedLeavesForDate['half_day'];
            return [
                'status' => 'present',
                'status_text' => 'Present + Half Day (Approved via Application)',
                'leave_type' => 'Half Day',
                'is_approved' => true,
                'is_auto_detected' => false,
                'within_quota' => true,
                'deduction_percentage' => $this->getLeaveDeductionPercentage('Half Day', true),
                'short_by_hours' => $shortByHours,
                'requires_approval' => false,
                'grace_applied' => $graceApplied,
                'late_minutes' => $lateMinutes,
                'effective_hours' => round($effectiveWorkedHours, 2)
            ];
        }
        
        // ========== 2. DETERMINE STATUS BASED ON EFFECTIVE WORKED HOURS ==========
        
        // FULL DAY PRESENT - Worked full required hours or more (grace applied or not)
        if ($effectiveWorkedHours >= $requiredHours) {
            $statusText = 'Present (Full Day)';
            if (!$isOnTime && $lateMinutes > 0) {
                $statusText = 'Present (Full Day) - Late by ' . $lateMinutes . ' min';
            } elseif ($graceApplied) {
                $statusText = 'Present (Full Day)';
            }
            
            return [
                'status' => 'present',
                'status_text' => $statusText,
                'leave_type' => null,
                'is_approved' => true,
                'is_auto_detected' => false,
                'within_quota' => true,
                'deduction_percentage' => 0,
                'short_by_hours' => 0,
                'requires_approval' => false,
                'grace_applied' => $graceApplied,
                'late_minutes' => $lateMinutes,
                'effective_hours' => round($effectiveWorkedHours, 2)
            ];
        }
        
        // If grace was applied but still short, check if short is within acceptable range
        if ($graceApplied && $shortByHours < $minShortLeaveDuration && $effectiveWorkedHours > 0) {
            return [
                'status' => 'present',
                'status_text' => 'Present (Full Day)',
                'leave_type' => null,
                'is_approved' => true,
                'is_auto_detected' => false,
                'within_quota' => true,
                'deduction_percentage' => 0,
                'short_by_hours' => $shortByHours,
                'requires_approval' => false,
                'grace_applied' => true,
                'late_minutes' => $lateMinutes,
                'effective_hours' => round($effectiveWorkedHours, 2),
                'note' => 'Short by less than ' . $minShortLeaveDuration . ' hours with grace applied - counted as full day'
            ];
        }
        
        // If short by LESS than minimum threshold (e.g., 10-20 minutes without grace), treat as FULL DAY
        if ($shortByHours < $minShortLeaveDuration && $effectiveWorkedHours > 0) {
            $statusText = 'Present (Full Day)';
            if (!$isOnTime && $lateMinutes > 0) {
                $statusText = 'Present (Full Day) - Late by ' . $lateMinutes . ' min';
            }
            
            return [
                'status' => 'present',
                'status_text' => $statusText,
                'leave_type' => null,
                'is_approved' => true,
                'is_auto_detected' => false,
                'within_quota' => true,
                'deduction_percentage' => 0,
                'short_by_hours' => $shortByHours,
                'requires_approval' => false,
                'grace_applied' => $graceApplied,
                'late_minutes' => $lateMinutes,
                'effective_hours' => round($effectiveWorkedHours, 2),
                'note' => 'Short by less than ' . $minShortLeaveDuration . ' hours - counted as full day'
            ];
        }
        
        // SHORT LEAVE - Worked enough to qualify (between 7-8.5 hours for a 9-hour day)
        // AND short by at least minimum threshold (30 minutes)
        if ($effectiveWorkedHours >= $shortLeaveMinHours && $shortByHours <= $shortLeaveAllowance && $shortByHours >= $minShortLeaveDuration) {
            // Get current usage count for this month (excluding current date)
            $usedShortLeaves = $this->getShortLeavesUsedThisMonth($employeeId, $currentYear, $currentMonth);
            $shortLeaveQuota = $this->getEmployeeLeaveQuota($employeeId, 'Short Leave');
            
            // Check if within quota (used < quota)
            $isWithinQuota = ($usedShortLeaves < $shortLeaveQuota);
            
            $lateText = (!$isOnTime && $lateMinutes > 0) ? ' - Late by ' . $lateMinutes . ' min' : '';
            
            if ($shortLeaveQuota > 0 && $isWithinQuota) {
                // Within quota - Auto Approve with deduction percentage from config
                $deductionPercentage = $this->getLeaveDeductionPercentage('Short Leave', true);
                return [
                    'status' => 'present',
                    'status_text' => 'Present + Short Leave (Within Quota - Auto Approved)' . $lateText,
                    'leave_type' => 'Short Leave',
                    'is_approved' => true,
                    'is_auto_detected' => true,
                    'within_quota' => true,
                    'deduction_percentage' => $deductionPercentage,
                    'short_by_hours' => $shortByHours,
                    'requires_approval' => false,
                    'grace_applied' => $graceApplied,
                    'late_minutes' => $lateMinutes,
                    'effective_hours' => round($effectiveWorkedHours, 2)
                ];
            } else {
                // Exceeds quota - Unapproved with deduction
                $deductionPercentage = $this->getLeaveDeductionPercentage('Short Leave', false);
                return [
                    'status' => 'present',
                    'status_text' => 'Present + Short Leave (Exceeds Quota - Will be Deducted)' . $lateText,
                    'leave_type' => 'Short Leave',
                    'is_approved' => false,
                    'is_auto_detected' => true,
                    'within_quota' => false,
                    'deduction_percentage' => $deductionPercentage,
                    'short_by_hours' => $shortByHours,
                    'requires_approval' => true,
                    'grace_applied' => $graceApplied,
                    'late_minutes' => $lateMinutes,
                    'effective_hours' => round($effectiveWorkedHours, 2)
                ];
            }
        }
        
        // HALF DAY - Worked between half day threshold and short leave minimum threshold
        if ($effectiveWorkedHours >= $halfDayThreshold && $effectiveWorkedHours < $shortLeaveMinHours) {
            $usedHalfDays = $this->getHalfDaysUsedThisMonth($employeeId, $currentYear, $currentMonth);
            $halfDayQuota = $this->getEmployeeLeaveQuota($employeeId, 'Half Day');
            
            $isWithinQuota = ($usedHalfDays < $halfDayQuota);
            
            $lateText = (!$isOnTime && $lateMinutes > 0) ? ' - Late by ' . $lateMinutes . ' min' : '';
            
            if ($halfDayQuota > 0 && $isWithinQuota) {
                $deductionPercentage = $this->getLeaveDeductionPercentage('Half Day', true);
                return [
                    'status' => 'present',
                    'status_text' => 'Present + Half Day (Within Quota - Auto Approved)' . $lateText,
                    'leave_type' => 'Half Day',
                    'is_approved' => true,
                    'is_auto_detected' => true,
                    'within_quota' => true,
                    'deduction_percentage' => $deductionPercentage,
                    'short_by_hours' => $shortByHours,
                    'requires_approval' => false,
                    'grace_applied' => $graceApplied,
                    'late_minutes' => $lateMinutes,
                    'effective_hours' => round($effectiveWorkedHours, 2)
                ];
            } else {
                $deductionPercentage = $this->getLeaveDeductionPercentage('Half Day', false);
                return [
                    'status' => 'present',
                    'status_text' => 'Present + Half Day (Exceeds Quota - Will be Deducted)' . $lateText,
                    'leave_type' => 'Half Day',
                    'is_approved' => false,
                    'is_auto_detected' => true,
                    'within_quota' => false,
                    'deduction_percentage' => $deductionPercentage,
                    'short_by_hours' => $shortByHours,
                    'requires_approval' => true,
                    'grace_applied' => $graceApplied,
                    'late_minutes' => $lateMinutes,
                    'effective_hours' => round($effectiveWorkedHours, 2)
                ];
            }
        }
        
        // SHORT ATTENDANCE - Worked less than half day threshold but more than 0
        // This indicates very poor attendance - full deduction applies
        if ($effectiveWorkedHours > 0 && $effectiveWorkedHours < $halfDayThreshold) {
            $lateText = (!$isOnTime && $lateMinutes > 0) ? ' - Late by ' . $lateMinutes . ' min' : '';
            return [
                'status' => 'short_attendance',
                'status_text' => 'Short Attendance (Salary Deducted)' . $lateText,
                'leave_type' => null,
                'is_approved' => false,
                'is_auto_detected' => true,
                'within_quota' => false,
                'deduction_percentage' => 100,
                'short_by_hours' => $shortByHours,
                'requires_approval' => false,
                'grace_applied' => $graceApplied,
                'late_minutes' => $lateMinutes,
                'effective_hours' => round($effectiveWorkedHours, 2)
            ];
        }
        
        // ABSENT - No check-in record at all
        return [
            'status' => 'absent',
            'status_text' => 'Absent',
            'leave_type' => null,
            'is_approved' => false,
            'is_auto_detected' => false,
            'within_quota' => false,
            'deduction_percentage' => 100,
            'short_by_hours' => $requiredHours,
            'requires_approval' => false,
            'grace_applied' => false,
            'late_minutes' => 0,
            'effective_hours' => 0
        ];
    }

    private function getEmployeeLeaveQuota($employeeId, $leaveType)
    {
        $merchantId = auth()->user()->institute_id;
        $sessionYear = $this->getCurrentAcademicSession();
        $employee = EmployeeDetails::where('employee_id', $employeeId)->first();
        
        $sessionFormats = [
            $sessionYear, 
            date('Y') . '-' . (date('Y') + 1), 
            (date('Y') - 1) . '-' . date('Y'), 
            date('Y')
        ];
        $sessionFormats = array_unique($sessionFormats);
        
        $normalizedLeaveType = $this->normalizeLeaveType($leaveType);
        
        // First check individual balance - SEARCH WITH MULTIPLE PATTERNS
        $balance = EmployeeLeaveBalance::where('employee_id', $employeeId)
            ->where('institute_id', $merchantId)
            ->where(function($query) use ($leaveType, $normalizedLeaveType) {
                $query->where('leave_type', $leaveType)
                    ->orWhere('leave_type', $normalizedLeaveType)
                    ->orWhere('leave_type', 'like', '%' . $leaveType . '%')
                    ->orWhere('leave_type', 'like', '%Short%')  // Match any Short leave type
                    ->orWhere('leave_type', 'like', '%Short Day%')
                    ->orWhere('leave_type', 'like', '%Day Leave%');
            })
            ->whereIn('session_year', $sessionFormats)
            ->first();
        
        if ($balance) {
            $quota = floatval($balance->remaining ?? 0);
            return $quota;
        }
        
        // If no individual, check department balance
        if ($employee && $employee->department_id) {
            $departmentBalance = EmployeeLeaveBalance::whereNull('employee_id')
                ->where('department_id', $employee->department_id)
                ->where('institute_id', $merchantId)
                ->where('assignment_type', 'department')
                ->where(function($query) use ($leaveType, $normalizedLeaveType) {
                    $query->where('leave_type', $leaveType)
                        ->orWhere('leave_type', $normalizedLeaveType)
                        ->orWhere('leave_type', 'like', '%' . $leaveType . '%')
                        ->orWhere('leave_type', 'like', '%Short%')
                        ->orWhere('leave_type', 'like', '%Short Day%');
                })
                ->whereIn('session_year', $sessionFormats)
                ->first();
            
            if ($departmentBalance) {
                return floatval($departmentBalance->remaining ?? 0);
            }
        }
        
        return 0;
    }

    private function getLeaveDeductionPercentage($leaveType, $isApproved = true)
    {
        $merchantId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();  
        $normalizedType = $this->normalizeLeaveType($leaveType);
        
        // Map the normalized leave types to database values
        $dbLeaveTypeMap = [
            'short_leave' => ['Short Day Leave', 'Short Leave', 'Short Leave (Day)'],
            'half_day' => ['Half Day Leave', 'Half Day', 'Half Day Leave (Half Day)'],
            'casual_leave' => ['Casual Leave'],
            'sick_leave' => ['Sick Leave'],
            'earned_leave' => ['Earned Leave'],
            'unpaid_leave' => ['Unpaid Leave'],
            'maternity_leave' => ['Maternity Leave'],
            'study_leave' => ['Study Leave'],
            'absent' => ['Absent']
        ];
        
        $possibleTypes = $dbLeaveTypeMap[$normalizedType] ?? [$leaveType];
        
        $deduction = null;
        
        // Try to find deduction by matching against possible database leave types
        foreach ($possibleTypes as $type) {
            $deduction = LeaveDeduction::forInstitute($merchantId, $context['branch_id'])
                ->where('leave_type', $type)
                ->where('is_active', true)
                ->first();
            
            if ($deduction) {
                break;
            }
        }
        
        // If still not found, try case-insensitive partial match
        if (!$deduction) {
            $deduction = LeaveDeduction::forInstitute($merchantId, $context['branch_id'])
                ->whereRaw('LOWER(leave_type) LIKE ?', ['%' . strtolower($normalizedType) . '%'])
                ->where('is_active', true)
                ->first();
        }
        
        if ($deduction) {
            $percentage = $isApproved 
                ? floatval($deduction->approved_deduction_percentage) 
                : floatval($deduction->unapproved_deduction_percentage);
            
            return $percentage;
        }
        
        // Default values based on normalized type
        $defaults = [
            'short_leave' => $isApproved ? 25 : 50,
            'half_day' => $isApproved ? 50 : 100,
            'casual_leave' => $isApproved ? 0 : 100,
            'sick_leave' => $isApproved ? 0 : 100,
            'earned_leave' => $isApproved ? 0 : 100,
            'unpaid_leave' => 100,
        ];
        
        return $defaults[$normalizedType] ?? ($isApproved ? 0 : 100);
    }

    private function formatLeaveTypeLabel($type)
    {
        $type = strtolower(trim($type));
        if (in_array($type, ['half_day', 'half_days', 'half day', 'half days','Half Day Leave', 'Half Day'])) {
            return 'Half Day';
        }
        if (in_array($type, ['short_leave', 'short leave','Short Day Leave', 'Short Leave', 'Short Leave (Day)'])) {
            return 'Short Leave';
        }
        return ucwords(str_replace(['_', '-'], ' ', $type));
    }

    private function getDetailedLeaveBalances($employeeId, $departmentId, $year, $merchantId)
    {
        $balances = [];
        
        $sessionFormats = [
            $year,
            $year . '-' . ($year + 1),
            ($year - 1) . '-' . $year,
            (string)$year
        ];
        $sessionFormats = array_unique($sessionFormats);
        
        $employee = EmployeeDetails::where('employee_id', $employeeId)->first();
        
         // FIX: Only count attendance-detected leaves for Short Leave and Half Day
        // DO NOT add leave applications for these types
        $currentMonthUsage = [
            'short_leave' => $this->getShortLeavesUsedThisMonth($employeeId, $year, date('n')),
            'half_day' => $this->getHalfDaysUsedThisMonth($employeeId, $year, date('n'))
        ];
        
         // Get leave applications usage for current month - EXCLUDING Short Leave & Half Day
        $leaveApplicationsUsage = $this->getLeaveApplicationsUsedThisMonth($employeeId, $year, date('n'), $merchantId);
        
        $allUsage = $currentMonthUsage;
        
        // Add other leave types from applications
        foreach ($leaveApplicationsUsage as $type => $count) {
            $allUsage[$type] = ($allUsage[$type] ?? 0) + $count;
        }
        
        // Get employee-specific leave balances (TOTAL quota for session)
        $employeeBalances = EmployeeLeaveBalance::where('employee_id', $employeeId)
            ->where('institute_id', $merchantId)
            ->whereIn('session_year', $sessionFormats)
            ->get();
        
        foreach ($employeeBalances as $balance) {
            $normalizedType = $this->normalizeLeaveType($balance->leave_type);
            
            // Get current month usage for this leave type
            $currentMonthUsed = $allUsage[$normalizedType] ?? 0;
            $quota = floatval($balance->total_allocated ?? 0);
            
            $balances[$normalizedType] = [
                'quota' => $quota,
                'used' => $currentMonthUsed,  // ONLY current month's usage
                'remaining' => max(0, $quota - $currentMonthUsed),  // Calculate remaining based on quota minus current usage
                'is_unpaid' => false,
                'assignment_type' => 'individual',
                'original_type' => $balance->leave_type
            ];
        }
        
        // SECOND: Get department-level allocations
        if ($employee && $employee->department_id) {
            $departmentBalances = EmployeeLeaveBalance::whereNull('employee_id')
                ->where('department_id', $employee->department_id)
                ->where('institute_id', $merchantId)
                ->where('assignment_type', 'department')
                ->whereIn('session_year', $sessionFormats)
                ->get();
            
            foreach ($departmentBalances as $balance) {
                $normalizedType = $this->normalizeLeaveType($balance->leave_type);
                
                if (!isset($balances[$normalizedType])) {
                    $currentMonthUsed = $allUsage[$normalizedType] ?? 0;
                    $quota = floatval($balance->total_allocated ?? 0);
                    
                    $balances[$normalizedType] = [
                        'quota' => $quota,
                        'used' => $currentMonthUsed,
                        'remaining' => max(0, $quota - $currentMonthUsed),
                        'is_unpaid' => $balance->is_custom ?? false,
                        'assignment_type' => 'department',
                        'original_type' => $balance->leave_type
                    ];
                }
            }
        }
        
        return $balances;
    }
    
    private function getLeaveApplicationsUsedThisMonth($employeeId, $year, $month, $merchantId)
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = Carbon::create($year, $month, 1)->endOfMonth();
        
        $taken = [];
        
        $leaves = EmployeeLeave::where('employee_id', $employeeId)
            ->where('institute_id', $merchantId)
            ->where('final_status', 'Approved')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                    });
            })
            ->get();
        
        foreach ($leaves as $leave) {
            $type = $this->normalizeLeaveType($leave->leave_type);
            
            // FIX: Skip Short Leave and Half Day entirely
            // They are counted by attendance detection only
            if ($type === 'short_leave' || $type === 'half_day') {
                continue;
            }
            
            // Also check the original leave_type field for variations
            $originalType = strtolower($leave->leave_type);
            if (strpos($originalType, 'short') !== false || strpos($originalType, 'half day') !== false) {
                continue; // Skip any leave that contains 'short' or 'half day'
            }
            
            if (!isset($taken[$type])) {
                $taken[$type] = 0;
            }
            
            // Calculate working days in leave period
            $leaveStart = Carbon::parse($leave->start_date);
            $leaveEnd = Carbon::parse($leave->end_date);
            $effectiveStart = $leaveStart->gt($start) ? $leaveStart : $start;
            $effectiveEnd = $leaveEnd->lt($end) ? $leaveEnd : $end;
            
            $workingDays = 0;
            $current = $effectiveStart->copy();
            while ($current <= $effectiveEnd) {
                if (!$current->isWeekend()) {
                    $workingDays++;
                }
                $current->addDay();
            }
            
            if (in_array($leave->leave_duration_type, ['Half Day', 'half_days', 'half_day'])) {
                $taken[$type] += 0.5;
            } elseif (in_array($leave->leave_duration_type, ['Short Leave', 'short_leave', 'short leave'])) {
                $taken[$type] += 0.25;
            } else {
                $taken[$type] += $workingDays;
            }
        }
        
        return $taken;
    }

    private function getAssignedLeaveTypes($employeeId, $year, $merchantId)
    {
        $employee = EmployeeDetails::where('employee_id', $employeeId)->first();
        if (!$employee) {
            return [];
        }
        
        $sessionFormats = [
            $year,
            $year . '-' . ($year + 1),
            ($year - 1) . '-' . $year,
            (string)$year
        ];
        $sessionFormats = array_unique($sessionFormats);
        
        // Check individual assignments first
        $leaveTypes = EmployeeLeaveBalance::where('employee_id', $employeeId)
            ->where('institute_id', $merchantId)
            ->whereIn('session_year', $sessionFormats)
            ->pluck('leave_type')
            ->toArray();
        
        // If no individual assignments, check department assignments
        if (empty($leaveTypes) && $employee->department_id) {
            $departmentLeaveTypes = EmployeeLeaveBalance::whereNull('employee_id')
                ->where('department_id', $employee->department_id)
                ->where('institute_id', $merchantId)
                ->where('assignment_type', 'department')
                ->whereIn('session_year', $sessionFormats)
                ->pluck('leave_type')
                ->toArray();
            
            $leaveTypes = array_merge($leaveTypes, $departmentLeaveTypes);
        }
        
        return array_unique($leaveTypes);
    }

    private function getLeavesTakenThisMonth($employeeId, $year, $month, $merchantId)
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = Carbon::create($year, $month, 1)->endOfMonth();
        
        $employee = EmployeeDetails::where('employee_id', $employeeId)
            ->where('institute_id', $merchantId)
            ->first();
        
        // Get dynamic shift thresholds
        $shiftHours = 9;
        $halfDayHours = 4;
        $shortLeaveHours = 6;
        
        if ($employee) {
            $date = Carbon::create($year, $month, 1);
            $shiftData = $employee->resolveEffectiveShift($date);
            $shift = $shiftData['shift'] ?? null;
            if ($shift) {
                $shiftHours = floatval($shift->working_hours ?? 9);
                $halfDayHours = floatval($shift->half_day_hours ?? 4);
                $shortLeaveHours = floatval($shift->short_leave_hours ?? 6);
            }
        }
        
        // Get approved leaves from employee_leaves table
        $leaves = EmployeeLeave::where('employee_id', $employeeId)
            ->where('institute_id', $merchantId)
            ->where('final_status', 'Approved')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                    });
            })
            ->get();
        
        $taken = [];
        
        foreach ($leaves as $leave) {
            $type = $this->normalizeLeaveType($leave->leave_type);
            if (!isset($taken[$type])) {
                $taken[$type] = 0;
            }
            
            if (in_array($leave->leave_duration_type, ['Half Day', 'half_days', 'half_day'])) {
                $taken[$type] += 0.5;
            } elseif (in_array($leave->leave_duration_type, ['Short Leave', 'short_leave', 'short leave'])) {
                $taken[$type] += 0.25;
            } else {
                $taken[$type] += floatval($leave->days_to_deduct ?? 1);
            }
        }
        
        // Count from attendance records based on worked hours
        $attendanceRecords = EmployeeAttendance::where('employee_id', $employeeId)
            ->whereBetween('date', [$start, $end])
            ->where('institute_id', $merchantId)
            ->get();
        
        $shortLeaveCount = 0;
        $halfDayCount = 0;
        
        // Create a lookup of dates that have approved leaves
        $approvedLeaveDates = [];
        foreach ($leaves as $leave) {
            $leaveStart = Carbon::parse($leave->start_date);
            $leaveEnd = Carbon::parse($leave->end_date);
            $current = $leaveStart->copy();
            while ($current <= $leaveEnd) {
                $approvedLeaveDates[$current->toDateString()] = true;
                $current->addDay();
            }
        }
        
        foreach ($attendanceRecords as $record) {
            $dateString = $record->date;
            $workedHours = floatval($record->total_hours ?? 0);
            
            // Skip if this date has an approved full day leave
            if (isset($approvedLeaveDates[$dateString])) {
                continue;
            }
            
            // Count based on worked hours
            if ($workedHours >= $shortLeaveHours && $workedHours < $shiftHours) {
                $shortLeaveCount++;
            } elseif ($workedHours >= $halfDayHours && $workedHours < $shortLeaveHours) {
                $halfDayCount++;
            }
        }
        
        // Add the counts to the taken array
        if ($shortLeaveCount > 0) {
            $taken['short_leave'] = ($taken['short_leave'] ?? 0) + $shortLeaveCount;
        }
        if ($halfDayCount > 0) {
            $taken['half_day'] = ($taken['half_day'] ?? 0) + $halfDayCount;
        }
        
        return $taken;
    }

    private function getShortLeavesUsedThisMonth($employeeId, $year, $month)
    {
        // Get the attendance details that were already calculated
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = Carbon::create($year, $month, 1)->endOfMonth();
        $merchantId = auth()->user()->institute_id;
        
        $employee = EmployeeDetails::where('employee_id', $employeeId)
            ->where('institute_id', $merchantId)
            ->first();
        
        if (!$employee) {
            return 0;
        }
        
        // Get the shift
        $date = Carbon::create($year, $month, 1);
        $shiftData = $employee->resolveEffectiveShift($date);
        $shift = $shiftData['shift'] ?? null;
        
        if (!$shift) {
            return 0;
        }
        
        // Get attendance records
        $attendanceRecords = EmployeeAttendance::with('logs')
            ->where('employee_id', $employeeId)
            ->whereBetween('date', [$start, $end])
            ->where('institute_id', $merchantId)
            ->get();
        
        $shortLeaveCount = 0;
        
        foreach ($attendanceRecords as $record) {
            $workedHours = floatval($record->total_hours ?? 0);
            $requiredHours = floatval($shift->working_hours ?? 9);
            $shortLeaveAllowance = floatval($shift->short_leave_hours ?? 2);
            $shortLeaveMinHours = $requiredHours - $shortLeaveAllowance;
            $shortByHours = $requiredHours - $workedHours;
            
            // ✅ This should match your calculateAttendanceStatusWithAutoDetection logic
            // It should NOT count March 4 (short by 0.17hrs) as Short Leave
            $minShortLeaveDuration = 0.5; // 30 minutes minimum
            
            if ($workedHours >= $shortLeaveMinHours && 
                $shortByHours >= $minShortLeaveDuration && 
                $shortByHours <= $shortLeaveAllowance) {
                $shortLeaveCount++;
            }
        }
        
        return $shortLeaveCount;
    }

    private function getHalfDaysUsedThisMonth($employeeId, $year, $month)
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = Carbon::create($year, $month, 1)->endOfMonth();
        $merchantId = auth()->user()->institute_id;
        
        $employee = EmployeeDetails::where('employee_id', $employeeId)
            ->where('institute_id', $merchantId)
            ->first();
        
        if (!$employee) {
            return 0;
        }
        
        $date = Carbon::create($year, $month, 1);
        $shiftData = $employee->resolveEffectiveShift($date);
        $shift = $shiftData['shift'] ?? null;
        
        if (!$shift) {
            return 0;
        }
        
        $requiredHours = floatval($shift->working_hours ?? 9);
        $shortLeaveAllowance = floatval($shift->short_leave_hours ?? 2);
        $shortLeaveMinHours = $requiredHours - $shortLeaveAllowance;
        $halfDayThreshold = floatval($shift->half_day_hours ?? 4);
        
        $attendanceRecords = EmployeeAttendance::where('employee_id', $employeeId)
            ->whereBetween('date', [$start, $end])
            ->where('institute_id', $merchantId)
            ->get();
        
        $halfDayCount = 0;
        
        foreach ($attendanceRecords as $record) {
            $workedHours = floatval($record->total_hours ?? 0);
            
            // ✅ Should match your calculateAttendanceStatusWithAutoDetection logic
            if ($workedHours >= $halfDayThreshold && $workedHours < $shortLeaveMinHours) {
                $halfDayCount++;
            }
        }
        
        return $halfDayCount;
    }

        /**
    * Check if email notification is enabled for a module
    */
    private function isEmailNotificationEnabled($moduleName)
    {
        // try {
            $merchantId = auth()->user()->institute_id;
            
            $setting = InstituteNotificationSetting::where('institute_id', $merchantId)
                ->where('module_name', $moduleName)
                ->first();
            
            // If no setting found, email is enabled by default
            if (!$setting) {
                return true;
            }
            
            return $setting->email_enabled;
            
        // } catch (\Exception $e) {
        
        //     return true;
        // }
    }

    /**
     * Send attendance finalized email to employee
     */
    private function sendAttendanceFinalizedEmail($employee, $attendanceData, $year, $month)
    {
        // try {
            $emailEnabled = $this->isEmailNotificationEnabled('attendance_finalized');
            
            if (!$emailEnabled || empty($employee->email)) {
                return;
            }
            
            // Prepare leave breakdown from attendance details
            $leaveBreakdown = [];
            $leaveQuotas = [];
            
            if (isset($attendanceData['attendance_details']) && is_array($attendanceData['attendance_details'])) {
                foreach ($attendanceData['attendance_details'] as $details) {
                    if (isset($details['leave_type']) && !empty($details['leave_type'])) {
                        $type = strtolower(str_replace(' ', '_', $details['leave_type']));
                        $leaveBreakdown[$type] = ($leaveBreakdown[$type] ?? 0) + 1;
                    }
                }
            }
            
            // Get leave quotas for this employee
            $merchantId = auth()->user()->institute_id;
            $sessionYear = $this->getCurrentAcademicSession();
            
            $balances = EmployeeLeaveBalance::where('employee_id', $employee->employee_id)
                ->where('institute_id', $merchantId)
                ->where('session_year', $sessionYear)
                ->get();
            
            foreach ($balances as $balance) {
                $type = strtolower(str_replace(' ', '_', $balance->leave_type));
                $leaveQuotas[$type] = floatval($balance->total_allocated ?? 0);
            }
            
            $monthName = Carbon::create($year, $month, 1)->format('F');
            
            Mail::send('emails.attendance-finalized', [
                'employeeName' => $employee->name,
                'employeeCode' => $employee->employee_code,
                'year' => $year,
                'month' => $month,
                'monthName' => $monthName,
                'workingDays' => $attendanceData['working_days'] ?? 0,
                'presentDays' => $attendanceData['present_days'] ?? 0,
                'absentDays' => $attendanceData['absent_days'] ?? 0,
                'leaveDays' => $attendanceData['leave_days'] ?? 0,
                'weekendDays' => $attendanceData['weekend_days'] ?? 0,
                'attendancePercentage' => $attendanceData['attendance_percentage'] ?? 0,
                'leaveBreakdown' => $leaveBreakdown,
                'leaveQuotas' => $leaveQuotas
            ], function ($message) use ($employee, $monthName, $year) {
                $message->to($employee->email)
                        ->subject('Attendance Finalized - ' . $monthName . ' ' . $year . ' - ' . config('app.name'));
            });
            
            
        // } catch (\Exception $e) {
        //     \Log::error('Failed to send attendance finalized email: ' . $e->getMessage());
        // }
    }
}