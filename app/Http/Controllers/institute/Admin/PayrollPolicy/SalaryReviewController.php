<?php

namespace App\Http\Controllers\institute\Admin\PayrollPolicy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeDetails;
use App\Models\Departments;
use App\Models\SalaryReview;
use App\Models\SalarySlip;
use App\Models\AttendanceReview;
use App\Models\EmployeeLeave;
use App\Models\PayrollExecution;
use App\Models\EmployeeLeaveBalance;
use App\Models\User;
use App\Models\EmployeeSalaryStructure;
use App\Models\PayrollPolicyOtherDeduction;
use App\Models\SalaryPreview;
use App\Traits\InstituteBranchAccess;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\InstituteNotificationSetting;
use Illuminate\Support\Facades\Mail;

class SalaryReviewController extends Controller
{
    use InstituteBranchAccess;

    /**
     * Display salary review list
     */
    public function index(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }
        
        // Get selected month/year or default to current
        $selectedYear = $request->get('year', now()->year);
        $selectedMonth = $request->get('month', now()->month);
        
        // Get available years for filter
        $availableYears = range(now()->subYears(2)->year, now()->addYear()->year);
        
        $departmentId = $request->get('department_id');
        $employeeId = $request->get('employee_id');
        $reviewStatus = $request->get('review_status', 'all');
        
        // Get departments for filter
        $departments = Departments::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->orderBy('department')
            ->get();
        
        // Get all employees for dropdown
        $allEmployees = EmployeeDetails::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
        
        // Get employees query with department
        $employeesQuery = EmployeeDetails::where('employee_details.institute_id', $context['institute_id'])
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
        
        // Get salary reviews
        $salaryReviews = [];
        $hasPendingReviews = false;
        $salaryMonth = Carbon::create($selectedYear, $selectedMonth, 1)->format('Y-m');
        
        foreach ($employees as $employee) {
            // Check if salary review exists
            $existingReview = SalaryReview::where('institute_id', $context['institute_id'])
                ->where('branch_id', $context['branch_id'])
                ->where('year', $selectedYear)
                ->where('month', $selectedMonth)
                ->where('employee_id', $employee->employee_id)
                ->first();
            
            // If no review exists, try to get from SalarySlip
            if (!$existingReview) {
                $salarySlip = SalarySlip::where('employee_id', $employee->employee_id)
                    ->where('salary_month', $salaryMonth)
                    ->where('institute_id', $context['institute_id'])
                    ->first();
                
                if ($salarySlip) {
                    $details = json_decode($salarySlip->salary_details_json, true);
                    
                    // Get all attendance deduction components from salary slip
                    $absentDeduction = $salarySlip->absent_deduction ?? 0;
                    $unpaidLeaveDeduction = $salarySlip->unpaid_leave_deduction ?? 0;
                    $unapprovedLeaveDeduction = $salarySlip->unapproved_leave_deduction ?? 0;
                    $shortAttendanceDeduction = $salarySlip->short_attendance_deduction ?? 0;

                    // Get quota-based deductions (these are the actual deduction amounts)
                    $shortLeavesWithinQuotaDeduction = $salarySlip->short_leaves_within_quota_deduction ?? 0;
                    $shortLeavesExceededDeduction = $salarySlip->short_leaves_exceeded_deduction ?? 0;
                    $halfDaysWithinQuotaDeduction = $salarySlip->half_days_within_quota_deduction ?? 0;
                    $halfDaysExceededDeduction = $salarySlip->half_days_exceeded_deduction ?? 0;

                    // Calculate total attendance deductions by summing ALL individual columns
                    $attendanceDeductionsTotal = $absentDeduction + 
                                                $unpaidLeaveDeduction + 
                                                $unapprovedLeaveDeduction + 
                                                $shortAttendanceDeduction + 
                                                $shortLeavesWithinQuotaDeduction + 
                                                $shortLeavesExceededDeduction + 
                                                $halfDaysWithinQuotaDeduction + 
                                                $halfDaysExceededDeduction;

                    $salaryData = [
                        'basic_salary' => $salarySlip->basic_salary,
                        'gross_salary' => $salarySlip->gross_salary,
                        'monthly_net_salary' => $salarySlip->monthly_net_salary,
                        'payable_salary' => $salarySlip->payable_salary,
                        'net_salary' => $salarySlip->net_salary,
                        'total_deductions' => $salarySlip->gross_salary - $salarySlip->net_salary,
                        'attendance_summary' => $details['attendance_summary'] ?? [],
                        'earnings_breakdown' => $details['earnings'] ?? [],
                        'deductions_breakdown' => $details['deductions'] ?? [],
                        'leave_breakdown' => $details['leave_details']['leave_breakdown'] ?? [],
                        'leave_deduction' => $salarySlip->leave_deduction ?? 0,
                        'absent_deduction' => $absentDeduction,
                        'unpaid_leave_deduction' => $unpaidLeaveDeduction,
                        'unapproved_leave_deduction' => $unapprovedLeaveDeduction,
                        'short_attendance_deduction' => $shortAttendanceDeduction,
                        'short_leaves_within_quota_deduction' => $shortLeavesWithinQuotaDeduction,
                        'short_leaves_exceeded_deduction' => $shortLeavesExceededDeduction,
                        'half_days_within_quota_deduction' => $halfDaysWithinQuotaDeduction,
                        'half_days_exceeded_deduction' => $halfDaysExceededDeduction,
                        'attendance_deductions_total' => $attendanceDeductionsTotal,
                        // Add quota counts for display in card
                        'short_leaves_used' => $details['attendance_summary']['short_leaves_used'] ?? 0,
                        'short_leaves_allocated' => $details['attendance_summary']['short_leaves_allocated'] ?? 0,
                        'half_days_used' => $details['attendance_summary']['half_days_used'] ?? 0,
                        'half_days_allocated' => $details['attendance_summary']['half_days_allocated'] ?? 0,
                    ];
                    
                    $reviewStatusValue = 'pending';
                } else {
                    continue; // Skip if no salary data
                }
         } else {
                // REVIEWED or FINALIZED case - get from salary review
                $shortAttendanceDeduction = $existingReview->short_attendance_deduction ?? 0;
                $exceededShortLeavesDeduction = $existingReview->exceeded_short_leaves_deduction ?? 0;
                $exceededHalfDaysDeduction = $existingReview->exceeded_half_days_deduction ?? 0;
                
                // Use the stored values from salary review
                $attendanceDeductionsTotal = (
                    ($existingReview->leave_deduction ?? 0) +
                    ($existingReview->absent_deduction ?? 0) +
                    ($existingReview->unpaid_leave_deduction ?? 0) +
                    ($existingReview->unapproved_leave_deduction ?? 0) +
                    $shortAttendanceDeduction +
                    $exceededShortLeavesDeduction +
                    $exceededHalfDaysDeduction
                );
                
                // Use final_payable_salary for finalized, otherwise use net_salary
                $payableSalary = ($existingReview->review_status === 'finalized') 
                    ? ($existingReview->final_payable_salary ?? $existingReview->net_salary ?? 0)
                    : ($existingReview->net_salary ?? $existingReview->payable_salary ?? 0);
                
                // Use monthly_net_salary from review or calculate from gross - standard
                $monthlyNetSalary = $existingReview->monthly_net_salary ?? 
                    (($existingReview->gross_salary ?? 0) - ($existingReview->total_standard_deductions ?? 0));

                $salaryData = [
                    'basic_salary' => $existingReview->basic_salary,
                    'gross_salary' => $existingReview->gross_salary,
                    'net_salary' => $existingReview->net_salary,
                    'monthly_net_salary' => $monthlyNetSalary,  // FIXED
                    'payable_salary' => $payableSalary,  // FIXED - use final_payable_salary for finalized
                    'total_deductions' => $existingReview->total_deductions,
                    'attendance_summary' => $existingReview->attendance_summary ?? [],
                    'earnings_breakdown' => $existingReview->earnings_breakdown ?? [],
                    'deductions_breakdown' => $existingReview->deductions_breakdown ?? [],
                    'leave_breakdown' => $existingReview->leave_breakdown ?? [],
                    'leave_deduction' => $existingReview->leave_deduction ?? 0,
                    'absent_deduction' => $existingReview->absent_deduction ?? 0,
                    'unpaid_leave_deduction' => $existingReview->unpaid_leave_deduction ?? 0,
                    'unapproved_leave_deduction' => $existingReview->unapproved_leave_deduction ?? 0,
                    'short_attendance_deduction' => $shortAttendanceDeduction,
                    'exceeded_short_leaves_deduction' => $exceededShortLeavesDeduction,
                    'exceeded_half_days_deduction' => $exceededHalfDaysDeduction,
                    'attendance_deductions_total' => $attendanceDeductionsTotal,
                ];
                $reviewStatusValue = $existingReview->review_status;
            }
                        
            // Apply status filter
            if ($reviewStatus != 'all' && $reviewStatusValue != $reviewStatus) {
                continue;
            }
            
            if ($reviewStatusValue != 'finalized') {
                $hasPendingReviews = true;
            }
            
            // Get finalizer/reviewer name
            if ($existingReview && $existingReview->finalized_by) {
                $finalizer = User::where('id', $existingReview->finalized_by)
                    ->orWhere('id', $existingReview->finalized_by)
                    ->first();
                $existingReview->finalized_by_name = $finalizer ? $finalizer->name : 'System';
            }
            
            if ($existingReview && $existingReview->reviewed_by) {
                $reviewer = EmployeeDetails::where('employee_id', $existingReview->reviewed_by)
                    ->orWhere('id', $existingReview->reviewed_by)
                    ->first();
                $existingReview->reviewed_by_name = $reviewer ? $reviewer->name : 'System';
            }
            
            $salaryReviews[] = [
                'employee' => $employee,
                'salary' => $salaryData,
                'review' => $existingReview,
                'review_status' => $reviewStatusValue
            ];
        }
        
        // Get month names for dropdown
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = Carbon::create()->month($i)->format('F');
        }
        
        return view('instituteAdmin.Payroll.ReviewSalary', compact(
            'departments',
            'allEmployees',
            'salaryReviews',
            'selectedYear',
            'selectedMonth',
            'availableYears',
            'months',
            'departmentId',
            'employeeId',
            'reviewStatus',
            'hasPendingReviews'
        ));
    }
    

    /**
     * Get salary review details for an employee
     */
    public function getSalaryDetails(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'year' => 'required|integer',
            'month' => 'required|integer',
        ]);
        
        $context = $this->getInstituteBranchContext();
        $salaryMonth = Carbon::create($request->year, $request->month, 1)->format('Y-m');
        
        // Get payroll execution configuration for employee's department
        $employee = EmployeeDetails::where('employee_id', $request->employee_id)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        $payrollConfig = null;
        $payrollExecutionDetails = [];
        
        if ($employee && $employee->department_id) {
            $payrollConfig = PayrollExecution::where('department_id', $employee->department_id)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            if ($payrollConfig) {
                $payDate = $this->calculatePayDate($request->year, $request->month, $payrollConfig);
                
                $payrollExecutionDetails = [
                    'payroll_cycle' => $payrollConfig->payroll_cycle ?? 'monthly',
                    'cycle_days' => $payrollConfig->cycle_days ?? null,
                    'execution_day' => $payrollConfig->execution_day ?? null,
                    'pay_date' => $payDate,
                    'calculation_basis' => $payrollConfig->payroll_cycle === 'days' 
                        ? "Salary calculated based on {$payrollConfig->cycle_days}-day payroll cycle" 
                        : "Salary calculated based on calendar days of the month",
                    'working_days_in_cycle' => $this->calculateWorkingDaysInCycle($request->year, $request->month, $payrollConfig)
                ];
            }
        }
        
        // Get attendance review for unpaid leave days
        $attendanceReview = AttendanceReview::where('institute_id', $context['institute_id'])
            ->where('employee_id', $request->employee_id)
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->first();
        
        // Calculate unpaid leave days from attendance details
        $unpaidLeaveDays = 0;
        $unapprovedLeaveDays = 0;
        if ($attendanceReview && isset($attendanceReview->attendance_details)) {
            $attnDetails = $attendanceReview->attendance_details;
            if (is_string($attnDetails)) {
                $attnDetails = json_decode($attnDetails, true);
            }
            if (is_array($attnDetails)) {
                foreach ($attnDetails as $date => $attn) {
                    if (isset($attn['status']) && $attn['status'] === 'leave' && isset($attn['leave_type'])) {
                        $leaveType = strtolower($attn['leave_type']);
                        if (strpos($leaveType, 'unpaid') !== false) {
                            $unpaidLeaveDays += 1;
                        }
                    }
                    if (isset($attn['status']) && $attn['status'] === 'unapproved_leave') {
                        $unapprovedLeaveDays += 1;
                    }
                }
            }
        }
        
        // Check if salary review exists
        $salaryReview = SalaryReview::where('institute_id', $context['institute_id'])
            ->where('branch_id', $context['branch_id'])
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->where('employee_id', $request->employee_id)
            ->first();

        if ($salaryReview) {
            $salarySlip = SalarySlip::where('employee_id', $request->employee_id)
                ->where('salary_month', $salaryMonth)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if (!$salarySlip) {
                return response()->json([
                    'success' => false,
                    'message' => 'Salary slip data not found for reviewed salary.'
                ]);
            }

            $details = json_decode($salarySlip->salary_details_json, true);
            
            $calculationNote = $details['calculation_note'] ?? null;
            
            // Get earnings breakdown
            $earningsBreakdown = $salaryReview->earnings_breakdown ?? ($details['earnings'] ?? []);
            $grossSalary = array_sum($earningsBreakdown);
            
            // Get standard deductions (PF, ESI, PT, TDS, etc.)
            $standardDeductions = $details['standard_deductions'] ?? [];
            $totalStandardDeductions = array_sum($standardDeductions);
            
            // Calculate Monthly Net Salary = stored monthly net salary or gross minus standard deductions
            $monthlyNetSalary = $salarySlip->monthly_net_salary ?? ($grossSalary - $totalStandardDeductions);
            
            // Get attendance deductions
            $attendanceDeductions = [
                'leave_deduction' => $salaryReview->leave_deduction ?? 0,
                'absent_deduction' => $salaryReview->absent_deduction ?? 0,
                'unpaid_leave_deduction' => $salaryReview->unpaid_leave_deduction ?? 0,
                'unapproved_leave_deduction' => $salaryReview->unapproved_leave_deduction ?? 0,
            ];
            $totalAttendanceDeductions = array_sum($attendanceDeductions);
            
            // Get other deductions
            $otherDeductions = $salaryReview->other_deductions ?? 0;
            $otherDeductionsDetails = $salaryReview->other_deductions_details ?? [];
            
            // Calculate Payable Salary = Monthly Net Salary - Attendance Deductions - Other Deductions
            $payableSalary = $monthlyNetSalary - $totalAttendanceDeductions - $otherDeductions;
            
            // Total deductions for display
            $totalDeductions = $totalStandardDeductions + $totalAttendanceDeductions + $otherDeductions;

            return response()->json([
                'success' => true,
                'data' => [
                    'employee_name' => $salarySlip->name,
                    'salary_month' => Carbon::create($request->year, $request->month, 1)->format('F Y'),
                    'basic_salary' => $salarySlip->basic_salary,
                    'gross_salary' => $salarySlip->gross_salary,
                    'monthly_net_salary' => $monthlyNetSalary,
                    'payable_salary' => max(0, $payableSalary),
                    'total_standard_deductions' => $totalStandardDeductions,
                    'total_attendance_deductions' => $totalAttendanceDeductions,
                    'total_other_deductions' => $otherDeductions,
                    'total_deductions' => $totalDeductions,
                    'leave_deduction' => $salaryReview->leave_deduction ?? 0,
                    'absent_deduction' => $salaryReview->absent_deduction ?? 0,
                    'unpaid_leave_deduction' => $salaryReview->unpaid_leave_deduction ?? 0,
                    'unpaid_leave_days' => $unpaidLeaveDays,
                    'unapproved_leave_deduction' => $salaryReview->unapproved_leave_deduction ?? 0,
                    'unapproved_leave_days' => $unapprovedLeaveDays,
                    'other_deductions' => $otherDeductions,
                    'other_deductions_details' => $otherDeductionsDetails,
                    'attendance_summary' => $salaryReview->attendance_summary ?? ($details['attendance_summary'] ?? []),
                    'earnings_breakdown' => $earningsBreakdown,
                    'standard_deductions_breakdown' => $standardDeductions,
                    'attendance_deductions_breakdown' => $attendanceDeductions,
                    'leave_breakdown' => $salaryReview->leave_breakdown ?? ($details['leave_details']['leave_breakdown'] ?? []),
                    'review_status' => $salaryReview->review_status,
                    'review_notes' => $salaryReview->review_notes,
                    'reviewed_at' => $salaryReview->reviewed_at,
                    'reviewed_by' => $salaryReview->reviewed_by,
                    'payroll_execution' => $payrollExecutionDetails,
                    'calculation_note' => $calculationNote ?? $this->generateCalculationNote($payrollConfig, $request->year, $request->month),
                    'daily_rate' => $monthlyNetSalary > 0 ? ($monthlyNetSalary / ($payrollConfig && $payrollConfig->payroll_cycle === 'days' ? $payrollConfig->cycle_days : Carbon::create($request->year, $request->month, 1)->daysInMonth)) : 0,
                    'days_in_period' => $payrollConfig && $payrollConfig->payroll_cycle === 'days' ? ($payrollConfig->cycle_days ?? 30) : Carbon::create($request->year, $request->month, 1)->daysInMonth,
                ]
            ]);
        }
        
        // Get from SalarySlip (no review exists yet)
        $salarySlip = SalarySlip::where('employee_id', $request->employee_id)
            ->where('salary_month', $salaryMonth)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        if (!$salarySlip) {
            return response()->json([
                'success' => false,
                'message' => 'Salary data not found. Please finalize attendance first.'
            ]);
        }
        
        $details = json_decode($salarySlip->salary_details_json, true);
        
        $calculationNote = $details['calculation_note'] ?? null;
        
        // Get earnings breakdown
        $earningsBreakdown = $details['earnings'] ?? [];
        $grossSalary = array_sum($earningsBreakdown);
        
        // Get standard deductions (PF, ESI, PT, TDS, etc.)
        $standardDeductions = $details['standard_deductions'] ?? [];
        $totalStandardDeductions = array_sum($standardDeductions);
        
        // Calculate Monthly Net Salary = stored monthly net salary or gross minus standard deductions
        $monthlyNetSalary = $salarySlip->monthly_net_salary ?? ($grossSalary - $totalStandardDeductions);
        
        // Get attendance deductions
        $attendanceDeductions = [
            'leave_deduction' => $salarySlip->leave_deduction ?? 0,
            'absent_deduction' => $salarySlip->absent_deduction ?? 0,
            'unpaid_leave_deduction' => $salarySlip->unpaid_leave_deduction ?? 0,
            'unapproved_leave_deduction' => $salarySlip->unapproved_leave_deduction ?? 0,
        ];
        $totalAttendanceDeductions = array_sum($attendanceDeductions);
        
        // Calculate Payable Salary = stored payable salary or Monthly Net Salary - Attendance Deductions
        $payableSalary = $salarySlip->payable_salary ?? max(0, $monthlyNetSalary - $totalAttendanceDeductions);
        
        return response()->json([
            'success' => true,
            'data' => [
                'employee_name' => $salarySlip->name,
                'salary_month' => Carbon::create($request->year, $request->month, 1)->format('F Y'),
                'basic_salary' => $salarySlip->basic_salary,
                'gross_salary' => $salarySlip->gross_salary,
                'monthly_net_salary' => $monthlyNetSalary,
                'payable_salary' => max(0, $payableSalary),
                'total_standard_deductions' => $totalStandardDeductions,
                'total_attendance_deductions' => $totalAttendanceDeductions,
                'total_other_deductions' => 0,
                'total_deductions' => $totalStandardDeductions + $totalAttendanceDeductions,
                'leave_deduction' => $salarySlip->leave_deduction ?? 0,
                'absent_deduction' => $salarySlip->absent_deduction ?? 0,
                'unpaid_leave_deduction' => $salarySlip->unpaid_leave_deduction ?? 0,
                'unpaid_leave_days' => $unpaidLeaveDays,
                'unapproved_leave_deduction' => $salarySlip->unapproved_leave_deduction ?? 0,
                'unapproved_leave_days' => $unapprovedLeaveDays,
                'other_deductions' => 0,
                'other_deductions_details' => [],
                'attendance_summary' => $details['attendance_summary'] ?? [],
                'earnings_breakdown' => $earningsBreakdown,
                'standard_deductions_breakdown' => $standardDeductions,
                'attendance_deductions_breakdown' => $attendanceDeductions,
                'leave_breakdown' => $details['leave_details']['leave_breakdown'] ?? [],
                'review_status' => 'pending',
                'review_notes' => null,
                'payroll_execution' => $payrollExecutionDetails,
                'calculation_note' => $calculationNote ?? $this->generateCalculationNote($payrollConfig, $request->year, $request->month),
                'daily_rate' => $monthlyNetSalary > 0 ? ($monthlyNetSalary / ($payrollConfig && $payrollConfig->payroll_cycle === 'days' ? $payrollConfig->cycle_days : Carbon::create($request->year, $request->month, 1)->daysInMonth)) : 0,
                'days_in_period' => $payrollConfig && $payrollConfig->payroll_cycle === 'days' ? ($payrollConfig->cycle_days ?? 30) : Carbon::create($request->year, $request->month, 1)->daysInMonth,
            ]
        ]);
    }

    /**
     * Calculate pay date based on execution day
     */
    private function calculatePayDate($year, $month, $payrollConfig)
    {
        if (!$payrollConfig || !$payrollConfig->execution_day) {
            return Carbon::create($year, $month, 1)->endOfMonth()->format('d M Y');
        }
        
        $executionDay = $payrollConfig->execution_day;
        $payDate = Carbon::create($year, $month, $executionDay);
        
        // If execution day is greater than month days, use last day of month
        if ($executionDay > $payDate->daysInMonth) {
            $payDate = Carbon::create($year, $month, $payDate->daysInMonth);
        }
        
        return $payDate->format('d M Y');
    }

    /**
     * Calculate working days in payroll cycle
     */
    private function calculateWorkingDaysInCycle($year, $month, $payrollConfig)
    {
        if (!$payrollConfig) {
            return Carbon::create($year, $month, 1)->daysInMonth;
        }
        
        if ($payrollConfig->payroll_cycle === 'days' && $payrollConfig->cycle_days) {
            return $payrollConfig->cycle_days;
        }
        
        return Carbon::create($year, $month, 1)->daysInMonth;
    }

    /**
     * Generate calculation note for salary
     */
    private function generateCalculationNote($payrollConfig, $year, $month)
    {
        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
        
        if ($payrollConfig && $payrollConfig->payroll_cycle === 'days') {
            return "Salary calculated for {$payrollConfig->cycle_days}-day payroll cycle. Execution Day: {$payrollConfig->execution_day} of the month. Pay Date: " . $this->calculatePayDate($year, $month, $payrollConfig);
        }
        
        return "Salary calculated based on {$daysInMonth} calendar days of the month.";
    }
    
    /**
     * Save salary review
     */
     public function saveSalaryReview(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'year' => 'required|integer',
            'month' => 'required|integer',
            'final_payable_salary' => 'required|numeric|min:0',
            'review_notes' => 'nullable|string',
            'other_deductions' => 'nullable|numeric|min:0',
            'other_deductions_details' => 'nullable|array',
            'loan_deduction' => 'nullable|numeric|min:0',
            'advance_deduction' => 'nullable|numeric|min:0',
        ]);
        
        $context = $this->getInstituteBranchContext();
        
        // Get employee details
        $employee = EmployeeDetails::where('employee_id', $request->employee_id)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ]);
        }
        
        // Get salary slip data
        $salaryMonth = Carbon::create($request->year, $request->month, 1)->format('Y-m');
        $salarySlip = SalarySlip::where('employee_id', $request->employee_id)
            ->where('salary_month', $salaryMonth)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        if (!$salarySlip) {
            return response()->json([
                'success' => false,
                'message' => 'Salary data not found'
            ]);
        }
        
        $details = json_decode($salarySlip->salary_details_json, true);
        
        // Calculate earnings
        $earnings = $details['earnings'] ?? [];
        $grossSalary = array_sum($earnings);
        
        // Calculate standard deductions from SalaryPreview
        $salaryStructure = EmployeeSalaryStructure::where('employee_id', $request->employee_id)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        $salaryPreview = null;
        if ($salaryStructure) {
            $salaryPreview = SalaryPreview::where('salary_structure_id', $salaryStructure->salary_structure_id)
                ->where('institute_id', $context['institute_id'])
                ->first();
        }
        
        // Standard Deductions breakdown
        $ptDeduction = $salaryPreview->pt_monthly ?? 0;
        $lstDeduction = $salaryPreview->lst_monthly ?? 0;
        $tdsDeduction = $salaryPreview->tds_monthly ?? 0;
        $insurancePremium = $salaryPreview->insurance_premium_monthly ?? 0;
        $advanceSalaryDeduction = $salaryPreview->advance_salary_monthly ?? 0;
        $pfEmployeeDeduction = $salaryPreview->pf_employee_monthly ?? 0;
        $esiEmployeeDeduction = $salaryPreview->esi_employee_monthly ?? 0;
        $npsEmployeeDeduction = $salaryPreview->nps_employee_monthly ?? 0;
        
        $totalStandardDeductions = $ptDeduction + $lstDeduction + $tdsDeduction + $insurancePremium + 
                                $advanceSalaryDeduction + $pfEmployeeDeduction + $esiEmployeeDeduction + 
                                $npsEmployeeDeduction;
        
        // Attendance deductions from salary slip
        $absentDeduction = $salarySlip->absent_deduction ?? 0;
        $leaveDeduction = $salarySlip->leave_deduction ?? 0;
        $unpaidLeaveDeduction = $salarySlip->unpaid_leave_deduction ?? 0;
        $unapprovedLeaveDeduction = $salarySlip->unapproved_leave_deduction ?? 0;
        $shortAttendanceDeduction = $salarySlip->short_attendance_deduction ?? 0;
        $exceededShortLeavesDeduction = $salarySlip->exceeded_short_leaves_deduction ?? 0;
        $exceededHalfDaysDeduction = $salarySlip->exceeded_half_days_deduction ?? 0;
        
        $totalAttendanceDeductions = $absentDeduction + $leaveDeduction + $unpaidLeaveDeduction + 
                                    $unapprovedLeaveDeduction + $shortAttendanceDeduction + 
                                    $exceededShortLeavesDeduction + $exceededHalfDaysDeduction;
        
        // Other deductions from request
        $loanDeduction = $request->loan_deduction ?? 0;
        $advanceDeduction = $request->advance_deduction ?? 0;
        $customDeductionsTotal = 0;
        
        $otherDeductionsDetails = $request->other_deductions_details ?? [];
        foreach ($otherDeductionsDetails as $ded) {
            if (isset($ded['type']) && $ded['type'] === 'custom') {
                $customDeductionsTotal += $ded['amount'] ?? 0;
            }
        }
        
        $otherDeductionsTotal = $loanDeduction + $advanceDeduction + $customDeductionsTotal;
        
        // Calculate monthly net salary (after standard deductions)
        $monthlyNetSalary = $grossSalary - $totalStandardDeductions;
        
        // Final payable salary
        $finalPayableSalary = $monthlyNetSalary - $totalAttendanceDeductions - $otherDeductionsTotal;
        
        // Total deductions
        $totalDeductions = $totalStandardDeductions + $totalAttendanceDeductions + $otherDeductionsTotal;
        
        // Create or update salary review with all fields
        $salaryReview = SalaryReview::updateOrCreate(
            [
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['branch_id'],
                'employee_id' => $request->employee_id,
                'year' => $request->year,
                'month' => $request->month,
            ],
            [
                'department_id' => $employee->department_id,
                'attendance_review_id' => $details['attendance_review_id'] ?? null,
                
                // Base Salary
                'basic_salary' => $salarySlip->basic_salary,
                'gross_salary' => $grossSalary,
                'monthly_net_salary' => $monthlyNetSalary,
                'final_payable_salary' => $finalPayableSalary,
                
                // Standard Deductions
                'pt_deduction' => $ptDeduction,
                'lst_deduction' => $lstDeduction,
                'tds_deduction' => $tdsDeduction,
                'insurance_premium' => $insurancePremium,
                'advance_salary_deduction' => $advanceSalaryDeduction,
                'pf_employee_deduction' => $pfEmployeeDeduction,
                'esi_employee_deduction' => $esiEmployeeDeduction,
                'nps_employee_deduction' => $npsEmployeeDeduction,
                'total_standard_deductions' => $totalStandardDeductions,
                
                // Attendance Deductions
                'absent_deduction' => $absentDeduction,
                'leave_deduction' => $leaveDeduction,
                'unpaid_leave_deduction' => $unpaidLeaveDeduction,
                'unapproved_leave_deduction' => $unapprovedLeaveDeduction,
                'short_attendance_deduction' => $shortAttendanceDeduction,
                'exceeded_short_leaves_deduction' => $exceededShortLeavesDeduction,
                'exceeded_half_days_deduction' => $exceededHalfDaysDeduction,
                'total_attendance_deductions' => $totalAttendanceDeductions,
                
                // Other Deductions
                'loan_deduction' => $loanDeduction,
                'advance_deduction' => $advanceDeduction,
                'custom_deductions_total' => $customDeductionsTotal,
                'other_deductions_total' => $otherDeductionsTotal,
                'other_deductions_details' => $otherDeductionsDetails,
                
                // Summary
                'total_deductions' => $totalDeductions,
                'net_salary' => $finalPayableSalary,
                'payable_salary' => $finalPayableSalary,
                
                // JSON fields for detailed breakdown
                'earnings_breakdown' => $earnings,
                'deductions_breakdown' => $details['deductions'] ?? [],
                'attendance_summary' => $details['attendance_summary'] ?? [],
                'leave_breakdown' => $details['leave_details']['leave_breakdown'] ?? [],
                
                // Status
                'review_status' => 'reviewed',
                'review_notes' => $request->review_notes,
                'reviewed_by' => auth()->user()->employee_id ?? auth()->user()->id,
                'reviewed_at' => now(),
            ]
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Salary reviewed successfully',
            'data' => $salaryReview
        ]);
    }
    
    // /**
    //  * Finalize salary
    //  */
    // public function finalizeSalary(Request $request)
    // {
    //     $request->validate([
    //         'employee_id' => 'required|string',
    //         'year' => 'required|integer',
    //         'month' => 'required|integer',
    //         'finalize_notes' => 'nullable|string'
    //     ]);
        
    //     $context = $this->getInstituteBranchContext();
        
    //     DB::beginTransaction();
        
    //     try {
    //         $salaryReview = SalaryReview::where('institute_id', $context['institute_id'])
    //             ->where('branch_id', $context['branch_id'])
    //             ->where('employee_id', $request->employee_id)
    //             ->where('year', $request->year)
    //             ->where('month', $request->month)
    //             ->first();
            
    //         if (!$salaryReview) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Salary not reviewed yet. Please review before finalizing.'
    //             ], 422);
    //         }
            
    //         if ($salaryReview->review_status === 'finalized') {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Salary already finalized'
    //             ], 422);
    //         }
            
    //         // Get employee for department info
    //         $employee = EmployeeDetails::where('employee_id', $request->employee_id)
    //             ->where('institute_id', $context['institute_id'])
    //             ->first();
            
    //         // Get payroll execution details
    //         $payrollConfig = null;
    //         $payrollExecutionDetails = [];
    //         if ($employee && $employee->department_id) {
    //             $payrollConfig = PayrollExecution::where('department_id', $employee->department_id)
    //                 ->where('institute_id', $context['institute_id'])
    //                 ->first();
                
    //             if ($payrollConfig) {
    //                 $payDate = $this->calculatePayDate($request->year, $request->month, $payrollConfig);
    //                 $payrollExecutionDetails = [
    //                     'payroll_cycle' => $payrollConfig->payroll_cycle ?? 'monthly',
    //                     'cycle_days' => $payrollConfig->cycle_days ?? null,
    //                     'execution_day' => $payrollConfig->execution_day ?? null,
    //                     'pay_date' => $payDate,
    //                     'financial_year' => $payrollConfig->financial_year ?? date('Y'),
    //                     'calculation_basis' => $payrollConfig->payroll_cycle === 'days' 
    //                         ? "Salary calculated based on {$payrollConfig->cycle_days}-day payroll cycle" 
    //                         : "Salary calculated based on calendar days of the month",
    //                 ];
    //             }
    //         }
            
    //         // Get comprehensive finalization details
    //         $finalizationDetails = [
    //             'finalized_at' => now()->toDateTimeString(),
    //             'finalized_by' => auth()->user()->name ?? 'System',
    //             'final_payable_salary' => $salaryReview->final_payable_salary,
    //             'total_deductions' => $salaryReview->total_deductions,
    //             'payroll_execution' => $payrollExecutionDetails,
    //             'standard_deductions_breakdown' => [
    //                 'Professional Tax (PT)' => $salaryReview->pt_deduction,
    //                 'Labour Welfare (LST)' => $salaryReview->lst_deduction,
    //                 'TDS' => $salaryReview->tds_deduction,
    //                 'Insurance Premium' => $salaryReview->insurance_premium,
    //                 'Advance Salary' => $salaryReview->advance_salary_deduction,
    //                 'Employee PF' => $salaryReview->pf_employee_deduction,
    //                 'Employee ESI' => $salaryReview->esi_employee_deduction,
    //                 'Employee NPS' => $salaryReview->nps_employee_deduction,
    //             ],
    //             'attendance_deductions_breakdown' => [
    //                 'Absent Deduction' => $salaryReview->absent_deduction,
    //                 'Leave Deduction' => $salaryReview->leave_deduction,
    //                 'Unpaid Leave Deduction' => $salaryReview->unpaid_leave_deduction,
    //                 'Unapproved Leave Deduction' => $salaryReview->unapproved_leave_deduction,
    //                 'Short Attendance Deduction' => $salaryReview->short_attendance_deduction,
    //                 'Exceeded Short Leaves Deduction' => $salaryReview->exceeded_short_leaves_deduction,
    //                 'Exceeded Half Days Deduction' => $salaryReview->exceeded_half_days_deduction,
    //             ],
    //             'other_deductions_breakdown' => $salaryReview->other_deductions_details,
    //             'calculation_formula' => 'Final Payable = (Gross Salary - Standard Deductions) - Attendance Deductions - Other Deductions'
    //         ];
            
    //         // Update salary review as finalized
    //         $salaryReview->update([
    //             'review_status' => 'finalized',
    //             'finalize_notes' => $request->finalize_notes,
    //             'finalized_by' => auth()->user()->employee_id ?? auth()->user()->id,
    //             'finalized_at' => now(),
    //             'finalization_details' => $finalizationDetails
    //         ]);
            
    //         // Update SalarySlip status
    //         $salaryMonth = Carbon::create($request->year, $request->month, 1)->format('Y-m');
    //         SalarySlip::where('employee_id', $request->employee_id)
    //             ->where('salary_month', $salaryMonth)
    //             ->where('institute_id', $context['institute_id'])
    //             ->update([
    //                 'status' => 'approved',
    //                 'finalized_at' => now(),
    //                 'finalized_by' => auth()->user()->employee_id ?? auth()->user()->id,
    //             ]);
            
    //         DB::commit();
            
    //         // Return success with redirect URL
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Salary finalized successfully for payroll',
    //             'redirect_url' => route('salary.review.viewFinalized', [
    //                 'employee_id' => $request->employee_id,
    //                 'year' => $request->year,
    //                 'month' => $request->month
    //             ]),
    //             'data' => $salaryReview
    //         ]);
            
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         \Log::error('Salary finalization error: ' . $e->getMessage());
            
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error finalizing salary: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function finalizeSalary(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'year' => 'required|integer',
            'month' => 'required|integer',
            'finalize_notes' => 'nullable|string'
        ]);
        
        $context = $this->getInstituteBranchContext();
        
        DB::beginTransaction();
        
        try {
            $salaryReview = SalaryReview::where('institute_id', $context['institute_id'])
                ->where('branch_id', $context['branch_id'])
                ->where('employee_id', $request->employee_id)
                ->where('year', $request->year)
                ->where('month', $request->month)
                ->first();
            
            if (!$salaryReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'Salary not reviewed yet. Please review before finalizing.'
                ], 422);
            }
            
            if ($salaryReview->review_status === 'finalized') {
                return response()->json([
                    'success' => false,
                    'message' => 'Salary already finalized'
                ], 422);
            }
            
            // Get employee for department info
            $employee = EmployeeDetails::where('employee_id', $request->employee_id)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            // Get payroll execution details
            $payrollConfig = null;
            $payrollExecutionDetails = [];
            if ($employee && $employee->department_id) {
                $payrollConfig = PayrollExecution::where('department_id', $employee->department_id)
                    ->where('institute_id', $context['institute_id'])
                    ->first();
                
                if ($payrollConfig) {
                    $payDate = $this->calculatePayDate($request->year, $request->month, $payrollConfig);
                    $payrollExecutionDetails = [
                        'payroll_cycle' => $payrollConfig->payroll_cycle ?? 'monthly',
                        'cycle_days' => $payrollConfig->cycle_days ?? null,
                        'execution_day' => $payrollConfig->execution_day ?? null,
                        'pay_date' => $payDate,
                        'financial_year' => $payrollConfig->financial_year ?? date('Y'),
                        'calculation_basis' => $payrollConfig->payroll_cycle === 'days' 
                            ? "Salary calculated based on {$payrollConfig->cycle_days}-day payroll cycle" 
                            : "Salary calculated based on calendar days of the month",
                    ];
                }
            }
            
            // Get comprehensive finalization details
            $finalizationDetails = [
                'finalized_at' => now()->toDateTimeString(),
                'finalized_by' => auth()->user()->name ?? 'System',
                'final_payable_salary' => $salaryReview->final_payable_salary,
                'total_deductions' => $salaryReview->total_deductions,
                'payroll_execution' => $payrollExecutionDetails,
                'standard_deductions_breakdown' => [
                    'Professional Tax (PT)' => $salaryReview->pt_deduction,
                    'Labour Welfare (LST)' => $salaryReview->lst_deduction,
                    'TDS' => $salaryReview->tds_deduction,
                    'Insurance Premium' => $salaryReview->insurance_premium,
                    'Advance Salary' => $salaryReview->advance_salary_deduction,
                    'Employee PF' => $salaryReview->pf_employee_deduction,
                    'Employee ESI' => $salaryReview->esi_employee_deduction,
                    'Employee NPS' => $salaryReview->nps_employee_deduction,
                ],
                'attendance_deductions_breakdown' => [
                    'Absent Deduction' => $salaryReview->absent_deduction,
                    'Leave Deduction' => $salaryReview->leave_deduction,
                    'Unpaid Leave Deduction' => $salaryReview->unpaid_leave_deduction,
                    'Unapproved Leave Deduction' => $salaryReview->unapproved_leave_deduction,
                    'Short Attendance Deduction' => $salaryReview->short_attendance_deduction,
                    'Exceeded Short Leaves Deduction' => $salaryReview->exceeded_short_leaves_deduction,
                    'Exceeded Half Days Deduction' => $salaryReview->exceeded_half_days_deduction,
                ],
                'other_deductions_breakdown' => $salaryReview->other_deductions_details,
                'calculation_formula' => 'Final Payable = (Gross Salary - Standard Deductions) - Attendance Deductions - Other Deductions'
            ];
            
            // Update salary review as finalized
            $salaryReview->update([
                'review_status' => 'finalized',
                'finalize_notes' => $request->finalize_notes,
                'finalized_by' => auth()->user()->employee_id ?? auth()->user()->id,
                'finalized_at' => now(),
                'finalization_details' => $finalizationDetails
            ]);
            
            // Update SalarySlip status
            $salaryMonth = Carbon::create($request->year, $request->month, 1)->format('Y-m');
            SalarySlip::where('employee_id', $request->employee_id)
                ->where('salary_month', $salaryMonth)
                ->where('institute_id', $context['institute_id'])
                ->update([
                    'status' => 'approved',
                    'finalized_at' => now(),
                    'finalized_by' => auth()->user()->employee_id ?? auth()->user()->id,
                ]);
            
            // ✅ Send email notification to employee
            $this->sendSalaryFinalizedEmail($request->employee_id, $request->year, $request->month, $context, $salaryReview);
            
            DB::commit();
            
            // Return success with redirect URL
            return response()->json([
                'success' => true,
                'message' => 'Salary finalized successfully for payroll',
                'redirect_url' => route('salary.review.viewFinalized', [
                    'employee_id' => $request->employee_id,
                    'year' => $request->year,
                    'month' => $request->month
                ]),
                'data' => $salaryReview
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Salary finalization error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error finalizing salary: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * View finalized salary details page
     */
    public function viewFinalizedSalaryPage(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'year' => 'required|integer',
            'month' => 'required|integer',
        ]);
        
        $context = $this->getInstituteBranchContext();
        
        $salaryReview = SalaryReview::where('institute_id', $context['institute_id'])
            ->where('branch_id', $context['branch_id'])
            ->where('employee_id', $request->employee_id)
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->where('review_status', 'finalized')
            ->first();
        
        if (!$salaryReview) {
            return redirect()->route('salary.review.index')->with('error', 'Finalized salary not found');
        }
        
        $employee = EmployeeDetails::where('employee_id', $request->employee_id)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        $attendanceReview = AttendanceReview::where('employee_id', $request->employee_id)
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        // Get payroll execution details
        $payrollConfig = null;
        $payrollExecutionDetails = [];
        if ($employee && $employee->department_id) {
            $payrollConfig = PayrollExecution::where('department_id', $employee->department_id)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            if ($payrollConfig) {
                $payDate = $this->calculatePayDate($request->year, $request->month, $payrollConfig);
                $payrollExecutionDetails = [
                    'payroll_cycle' => $payrollConfig->payroll_cycle ?? 'monthly',
                    'cycle_days' => $payrollConfig->cycle_days ?? null,
                    'execution_day' => $payrollConfig->execution_day ?? null,
                    'pay_date' => $payDate,
                    'financial_year' => $payrollConfig->financial_year ?? date('Y'),
                    'calculation_basis' => $payrollConfig->payroll_cycle === 'days' 
                        ? "Salary calculated based on {$payrollConfig->cycle_days}-day payroll cycle" 
                        : "Salary calculated based on calendar days of the month",
                ];
            }
        }
        
        $monthName = Carbon::create($request->year, $request->month)->format('F Y');
        // Get finalizer name
        $finalizedByName = null;
        if ($salaryReview->finalized_by) {
            // $finalizer = EmployeeDetails::where('employee_id', $salaryReview->finalized_by)
            //     ->orWhere('id', $salaryReview->finalized_by)
            //     ->first();
            $finalizer = User::where('id', $salaryReview->finalized_by)
                ->orWhere('id', $salaryReview->finalized_by)
                ->first();

            $finalizedByName = $finalizer ? $finalizer->name : 'System';
        }
        return view('instituteAdmin.Payroll.ViewFinalizedSalary', compact(
            'salaryReview',
            'employee',
            'attendanceReview',
            'monthName',
            'request',
            'payrollExecutionDetails',
            'finalizedByName'  
        ));
    }

     /**
     * Get comprehensive salary details for finalization
     */
    private function getComprehensiveSalaryDetails($employeeId, $year, $month, $context)
    {
        $employee = EmployeeDetails::where('employee_id', $employeeId)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        if (!$employee) {
            throw new \Exception('Employee not found');
        }
        
        $salaryMonth = Carbon::create($year, $month, 1);
        $salaryMonthFormatted = $salaryMonth->format('Y-m');
        
        // Get salary slip
        $salarySlip = SalarySlip::where('employee_id', $employeeId)
            ->where('salary_month', $salaryMonthFormatted)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        if (!$salarySlip) {
            throw new \Exception('Salary slip not found');
        }
        
        $details = json_decode($salarySlip->salary_details_json, true);
        
        // Get attendance review
        $attendanceReview = AttendanceReview::where('employee_id', $employeeId)
            ->where('year', $year)
            ->where('month', $month)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        // Get salary structure
        $salaryStructure = EmployeeSalaryStructure::where('employee_id', $employeeId)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        // Get payroll configuration
        $payrollConfig = PayrollExecution::where('department_id', $employee->department_id)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        // Calculate payroll execution summary
        $payrollExecutionSummary = $this->getPayrollExecutionSummary($payrollConfig, $year, $month);
        
        // Calculate leave deductions breakdown
        $leaveBreakdown = $this->getDetailedLeaveBreakdown($employeeId, $year, $month, $context);
        
        // Calculate earnings breakdown
        $earningsBreakdown = $details['earnings'] ?? [];
        
        // Calculate deduction breakdowns
        $standardDeductions = $details['standard_deductions'] ?? [];
        $attendanceDeductions = [
            'leave_deduction' => $salarySlip->leave_deduction ?? 0,
            'absent_deduction' => $salarySlip->absent_deduction ?? 0,
            'unpaid_leave_deduction' => $salarySlip->unpaid_leave_deduction ?? 0,
            'unapproved_leave_deduction' => $salarySlip->unapproved_leave_deduction ?? 0,
            'short_attendance_deduction' => $salarySlip->short_attendance_deduction ?? 0,
            'exceeded_short_leaves_deduction' => $salarySlip->exceeded_short_leaves_deduction ?? 0,
            'exceeded_half_days_deduction' => $salarySlip->exceeded_half_days_deduction ?? 0,
        ];
        
        // Get other deductions from review if exists
        $salaryReview = SalaryReview::where('employee_id', $employeeId)
            ->where('year', $year)
            ->where('month', $month)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        $otherDeductions = $salaryReview ? ($salaryReview->other_deductions ?? 0) : 0;
        $otherDeductionsDetails = $salaryReview ? ($salaryReview->other_deductions_details ?? []) : [];
        
        // Calculate totals
        $grossSalary = array_sum($earningsBreakdown);
        $totalStandardDeductions = array_sum($standardDeductions);
        $totalAttendanceDeductions = array_sum($attendanceDeductions);
        $monthlyNetSalary = $salarySlip->monthly_net_salary ?? ($grossSalary - $totalStandardDeductions);
        $payableSalary = $monthlyNetSalary - $totalAttendanceDeductions - $otherDeductions;
        
        // Calculate employer contributions
        $employerContributions = $this->calculateEmployerContributions($salaryStructure, $context);
        
        // Calculate total cost to company
        $totalCostToCompany = $payableSalary + array_sum($employerContributions);
        
        return [
            // Basic Information
            'employee' => [
                'id' => $employee->employee_id,
                'name' => $employee->name,
                'code' => $employee->employee_code,
                'department' => $employee->department_name ?? 'N/A',
                'designation' => $employee->designation ?? 'N/A',
                'joining_date' => $employee->joining_date,
            ],
            'salary_month' => $salaryMonth->format('F Y'),
            'finalized_at' => now()->format('Y-m-d H:i:s'),
            'finalized_by' => auth()->user()->name ?? 'System',
            
            // Salary Summary
            'salary_summary' => [
                'basic_salary' => $salarySlip->basic_salary,
                'gross_salary' => $salarySlip->gross_salary,
                'monthly_net_salary' => $monthlyNetSalary,
                'total_standard_deductions' => $totalStandardDeductions,
                'total_attendance_deductions' => $totalAttendanceDeductions,
                'total_other_deductions' => $otherDeductions,
                'total_deductions' => $totalStandardDeductions + $totalAttendanceDeductions + $otherDeductions,
                'payable_salary' => max(0, $payableSalary),
                'total_cost_to_company' => $totalCostToCompany,
            ],
            
            // Earnings Breakdown
            'earnings_breakdown' => $earningsBreakdown,
            
            // Deduction Breakdowns
            'deductions_breakdown' => [
                'standard_deductions' => $standardDeductions,
                'attendance_deductions' => $attendanceDeductions,
                'other_deductions' => [
                    'total' => $otherDeductions,
                    'details' => $otherDeductionsDetails
                ]
            ],
            
            // Attendance Summary
            'attendance_summary' => $attendanceReview ? [
                'present_days' => $attendanceReview->present_days,
                'absent_days' => $attendanceReview->absent_days,
                'leave_days' => $attendanceReview->leave_days,
                'weekend_days' => $attendanceReview->weekend_days,
                'working_days' => $attendanceReview->working_days,
                'short_attendance_days' => $attendanceReview->short_attendance_days ?? 0,
                'unapproved_leave_days' => $attendanceReview->unapproved_leave_days ?? 0,
                'attendance_percentage' => $attendanceReview->attendance_percentage,
            ] : null,
            
            // Leave Details
            'leave_details' => $leaveBreakdown,
            
            // Payroll Execution Summary
            'payroll_execution_summary' => $payrollExecutionSummary,
            
            // Employer Contributions
            'employer_contributions' => $employerContributions,
            
            // Calculation Note
            'calculation_note' => $this->generateFinalizationNote(
                $payrollConfig, 
                $year, 
                $month, 
                $totalAttendanceDeductions,
                $payableSalary
            ),
            
            // Payment Details
            'payment_details' => [
                'payment_mode' => $this->getEmployeePaymentMode($employee),
                'bank_account' => $employee->bank_account_number ?? 'N/A',
                'bank_name' => $employee->bank_name ?? 'N/A',
                'ifsc_code' => $employee->ifsc_code ?? 'N/A',
                'pan_number' => $employee->pan_number ?? 'N/A',
                'uan_number' => $employee->uan_number ?? 'N/A',
            ]
        ];
    }

    /**
     * Get payroll execution summary
     */
    private function getPayrollExecutionSummary($payrollConfig, $year, $month)
    {
        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
        
        if (!$payrollConfig) {
            return [
                'payroll_cycle' => 'monthly',
                'cycle_days' => $daysInMonth,
                'execution_day' => null,
                'pay_date' => Carbon::create($year, $month, 1)->endOfMonth()->format('d M Y'),
                'calculation_basis' => "Salary calculated based on {$daysInMonth} calendar days of the month"
            ];
        }
        
        $executionDay = $payrollConfig->execution_day;
        $payDate = Carbon::create($year, $month, $executionDay);
        
        if ($executionDay > $payDate->daysInMonth) {
            $payDate = Carbon::create($year, $month, $payDate->daysInMonth);
        }
        
        return [
            'payroll_cycle' => $payrollConfig->payroll_cycle ?? 'monthly',
            'cycle_days' => $payrollConfig->cycle_days ?? $daysInMonth,
            'execution_day' => $executionDay,
            'pay_date' => $payDate->format('d M Y'),
            'calculation_basis' => $payrollConfig->payroll_cycle === 'days' 
                ? "Salary calculated based on {$payrollConfig->cycle_days}-day payroll cycle" 
                : "Salary calculated based on {$daysInMonth} calendar days of the month"
        ];
    }
    
    /**
     * Calculate employer contributions
     */
    private function calculateEmployerContributions($salaryStructure, $context)
    {
        $contributions = [
            'employer_pf' => 0,
            'employer_esi' => 0,
            'gratuity' => 0,
            'bonus' => 0
        ];
        
        if (!$salaryStructure) {
            return $contributions;
        }
        
        // Get salary preview for employer contributions
        $salaryPreview = SalaryPreview::where('salary_structure_id', $salaryStructure->salary_structure_id)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        if ($salaryPreview) {
            $contributions['employer_pf'] = $salaryPreview->employer_pf_monthly ?? 0;
            $contributions['employer_esi'] = $salaryPreview->employer_esi_monthly ?? 0;
        }
        
        // Calculate gratuity (4.81% of basic + DA)
        $basicSalary = $salaryStructure->basic_salary_monthly ?? 0;
        $contributions['gratuity'] = round($basicSalary * 0.0481, 2);
        
        return $contributions;
    }
    
    /**
     * Get employee payment mode
     */
    private function getEmployeePaymentMode($employee)
    {
        if ($employee->bank_account_number && $employee->ifsc_code) {
            return 'Bank Transfer';
        }
        return 'Cheque/Cash';
    }
    
    /**
     * Generate finalization note
     */
    private function generateFinalizationNote($payrollConfig, $year, $month, $totalAttendanceDeductions, $payableSalary)
    {
        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
        
        $note = "Salary for the month of " . Carbon::create($year, $month, 1)->format('F Y') . " has been finalized.\n";
        
        if ($payrollConfig && $payrollConfig->payroll_cycle === 'days') {
            $note .= "Payroll Cycle: {$payrollConfig->cycle_days}-day cycle with execution on day {$payrollConfig->execution_day} of the month.\n";
        } else {
            $note .= "Payroll Cycle: Monthly cycle based on {$daysInMonth} calendar days.\n";
        }
        
        if ($totalAttendanceDeductions > 0) {
            $note .= "Attendance deductions applied: ₹" . number_format($totalAttendanceDeductions, 2) . "\n";
        }
        
        $note .= "Final payable salary: ₹" . number_format($payableSalary, 2);
        
        return $note;
    }
    
    /**
     * Create salary review from salary slip
     */
    private function createReviewFromSalarySlip($salarySlip, $context)
    {
        $details = json_decode($salarySlip->salary_details_json, true);
        $earnings = $details['earnings'] ?? [];
        $deductions = $details['deductions'] ?? [];
        $attendanceSummary = $details['attendance_summary'] ?? [];
        $leaveBreakdown = $details['leave_details']['leave_breakdown'] ?? [];
        
        $grossSalary = array_sum($earnings);
        $totalStandardDeductions = array_sum($deductions);
        $totalAttendanceDeductions = ($salarySlip->leave_deduction ?? 0) + 
                                     ($salarySlip->absent_deduction ?? 0) +
                                     ($salarySlip->unpaid_leave_deduction ?? 0) +
                                     ($salarySlip->unapproved_leave_deduction ?? 0);
        
        $monthlyNetSalary = $salarySlip->monthly_net_salary ?? ($grossSalary - $totalStandardDeductions);
        $payableSalary = $salarySlip->payable_salary ?? max(0, $monthlyNetSalary - $totalAttendanceDeductions);
        $totalDeductions = $totalStandardDeductions + $totalAttendanceDeductions;
        
        $salaryMonth = Carbon::parse($salarySlip->salary_month);
        
        return SalaryReview::create([
            'institute_id' => $context['institute_id'],
            'branch_id' => $context['branch_id'],
            'employee_id' => $salarySlip->employee_id,
            'department_id' => $details['department_id'] ?? null,
            'attendance_review_id' => $details['attendance_review_id'] ?? null,
            'year' => $salaryMonth->year,
            'month' => $salaryMonth->month,
            'basic_salary' => $salarySlip->basic_salary,
            'gross_salary' =>$salarySlip->gross_salary,
            'net_salary' => $payableSalary,
            'total_deductions' => $totalDeductions,
            'leave_deduction' => $salarySlip->leave_deduction ?? 0,
            'absent_deduction' => $salarySlip->absent_deduction ?? 0,
            'unpaid_leave_deduction' => $salarySlip->unpaid_leave_deduction ?? 0,
            'unapproved_leave_deduction' => $salarySlip->unapproved_leave_deduction ?? 0,
            'earnings_breakdown' => $earnings,
            'deductions_breakdown' => $deductions,
            'leave_breakdown' => $leaveBreakdown,
            'attendance_summary' => $attendanceSummary,
            'review_status' => 'reviewed',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->user()->employee_id ?? auth()->user()->id,
        ]);
    }
    
    /**
     * Get finalized salary with comprehensive details (API endpoint)
     */
    public function getFinalizedSalaryDetails(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'year' => 'required|integer',
            'month' => 'required|integer',
        ]);
        
        $context = $this->getInstituteBranchContext();
        
        $salaryReview = SalaryReview::where('institute_id', $context['institute_id'])
            ->where('branch_id', $context['branch_id'])
            ->where('employee_id', $request->employee_id)
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->where('review_status', 'finalized')
            ->first();
        
        if (!$salaryReview) {
            return response()->json([
                'success' => false,
                'message' => 'Finalized salary not found for this employee'
            ], 404);
        }
        
        // Get comprehensive details
        $details = $this->getComprehensiveSalaryDetails(
            $request->employee_id,
            $request->year,
            $request->month,
            $context
        );
        
        return response()->json([
            'success' => true,
            'data' => $details
        ]);
    }

    /**
     * Get detailed leave breakdown
     */
    private function getDetailedLeaveBreakdown($employeeId, $year, $month, $context)
    {
        $monthStart = Carbon::create($year, $month, 1)->startOfMonth();
        $monthEnd = Carbon::create($year, $month, 1)->endOfMonth();
        
        $leaves = EmployeeLeave::where('employee_id', $employeeId)
            ->where('institute_id', $context['institute_id'])
            ->where('final_status', 'Approved')
            ->where(function($query) use ($monthStart, $monthEnd) {
                $query->whereBetween('start_date', [$monthStart, $monthEnd])
                    ->orWhereBetween('end_date', [$monthStart, $monthEnd])
                    ->orWhere(function($q) use ($monthStart, $monthEnd) {
                        $q->where('start_date', '<=', $monthStart)
                            ->where('end_date', '>=', $monthEnd);
                    });
            })
            ->get();
        
        $leaveBreakdown = [];
        $totalDays = 0;
        
        foreach ($leaves as $leave) {
            $startDate = Carbon::parse($leave->start_date);
            $endDate = Carbon::parse($leave->end_date);
            
            $periodStart = $startDate < $monthStart ? $monthStart : $startDate;
            $periodEnd = $endDate > $monthEnd ? $monthEnd : $endDate;
            $daysInMonth = $periodEnd->diffInDays($periodStart) + 1;
            
            // Adjust for half days and short leaves
            $days = $daysInMonth;
            $durationType = $leave->leave_duration_type ?? 'Full Day';
            
            if (in_array($durationType, ['Half Day', 'half_day', 'half_days'])) {
                $days = 0.5;
            } elseif (in_array($durationType, ['Short Leave', 'short_leave'])) {
                $days = 0.25;
            }
            
            $totalDays += $days;
            
            $leaveBreakdown[] = [
                'leave_type' => $leave->leave_type,
                'duration_type' => $durationType,
                'start_date' => $startDate->format('d M Y'),
                'end_date' => $endDate->format('d M Y'),
                'days' => $days,
                'reason' => $leave->reason,
                'status' => $leave->final_status
            ];
        }
        
        return [
            'total_leave_days' => round($totalDays, 2),
            'leaves' => $leaveBreakdown
        ];
    }
    
    
    /**
     * Get finalized salary details
     */
    public function getFinalizedSalary(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'year' => 'required|integer',
            'month' => 'required|integer',
        ]);
        
        $context = $this->getInstituteBranchContext();
        
        $salaryReview = SalaryReview::where('institute_id', $context['institute_id'])
            ->where('branch_id', $context['branch_id'])
            ->where('employee_id', $request->employee_id)
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->where('review_status', 'finalized')
            ->first();
        
        if (!$salaryReview) {
            return response()->json([
                'success' => false,
                'message' => 'Finalized salary not found'
            ]);
        }
        
        // Get finalizer name
        $finalizedByName = null;
        if ($salaryReview->finalized_by) {
            $finalizer = EmployeeDetails::where('employee_id', $salaryReview->finalized_by)
                ->orWhere('id', $salaryReview->finalized_by)
                ->first();
            $finalizedByName = $finalizer ? $finalizer->name : 'System';
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'employee_name' => $salaryReview->employee->name ?? 'Unknown',
                'salary_month' => Carbon::create($request->year, $request->month, 1)->format('F Y'),
                'basic_salary' => $salaryReview->basic_salary,
                'gross_salary' => $salaryReview->gross_salary,
                'net_salary' => $salaryReview->net_salary,
                'total_deductions' => $salaryReview->total_deductions,
                'attendance_summary' => $salaryReview->attendance_summary,
                'earnings_breakdown' => $salaryReview->earnings_breakdown,
                'deductions_breakdown' => $salaryReview->deductions_breakdown,
                'leave_breakdown' => $salaryReview->leave_breakdown,
                'finalized_at' => $salaryReview->finalized_at ? Carbon::parse($salaryReview->finalized_at)->format('d M Y h:i A') : null,
                'finalized_by' => $finalizedByName,
                'finalize_notes' => $salaryReview->finalize_notes,
            ]
        ]);
    }
    
  
    /**
     * Show detailed salary review page
     */
    public function show(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'year' => 'required|integer',
            'month' => 'required|integer',
        ]);
        
        $context = $this->getInstituteBranchContext();
        $salaryMonth = Carbon::create($request->year, $request->month, 1)->format('Y-m');
        
        // Get employee details
        // $employee = EmployeeDetails::where('employee_id', $request->employee_id)
        //     ->where('institute_id', $context['institute_id'])
        //     ->first();
        $employee = EmployeeDetails::query()
            ->join('departments', 'employee_details.department_id', '=', 'departments.department_id')
            ->where('employee_id', $request->employee_id)
            ->where('employee_details.institute_id', $context['institute_id'])
            ->select(
                'employee_details.*',
                'employee_details.institute_id',
                'departments.department as department_name'
            )
            ->firstOrFail();
        if (!$employee) {
            return redirect()->route('salary.review.index')->with('error', 'Employee not found');
        }
        
        // Get payroll execution details
        $payrollConfig = PayrollExecution::where('department_id', $employee->department_id)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        $payrollExecutionDetails = [];
        if ($payrollConfig) {
            $payDate = $this->calculatePayDate($request->year, $request->month, $payrollConfig);
            $payrollExecutionDetails = [
                'payroll_cycle' => $payrollConfig->payroll_cycle ?? 'monthly',
                'cycle_days' => $payrollConfig->cycle_days ?? null,
                'execution_day' => $payrollConfig->execution_day ?? null,
                'pay_date' => $payDate,
                'financial_year' => $payrollConfig->financial_year ?? date('Y'),
                'calculation_basis' => $payrollConfig->payroll_cycle === 'days' 
                    ? "Salary calculated based on {$payrollConfig->cycle_days}-day payroll cycle" 
                    : "Salary calculated based on calendar days of the month",
            ];
        }
        
        // Get salary structure
        $salaryStructure = EmployeeSalaryStructure::where('employee_id', $request->employee_id)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        // Get Salary Preview
        $salaryPreview = null;
        if ($salaryStructure) {
            $salaryPreview = SalaryPreview::where('salary_structure_id', $salaryStructure->salary_structure_id)
                ->where('institute_id', $context['institute_id'])
                ->first();
        }
        
        // Get attendance review
        $attendanceReview = AttendanceReview::where('institute_id', $context['institute_id'])
            ->where('employee_id', $request->employee_id)
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->first();
        
        // Calculate unpaid leave days
        $unpaidLeaveDays = 0;
        $unapprovedLeaveDays = 0;
        if ($attendanceReview && isset($attendanceReview->attendance_details)) {
            $attnDetails = $attendanceReview->attendance_details;
            if (is_string($attnDetails)) {
                $attnDetails = json_decode($attnDetails, true);
            }
            if (is_array($attnDetails)) {
                foreach ($attnDetails as $date => $attn) {
                    if (isset($attn['status']) && $attn['status'] === 'leave' && isset($attn['leave_type'])) {
                        $leaveType = strtolower($attn['leave_type']);
                        if (strpos($leaveType, 'unpaid') !== false) {
                            $unpaidLeaveDays += 1;
                        }
                    }
                    if (isset($attn['status']) && $attn['status'] === 'unapproved_leave') {
                        $unapprovedLeaveDays += 1;
                    }
                }
            }
        }
        
        // Build earnings breakdown from SalaryPreview
        $earningsBreakdown = [];
        if ($salaryPreview) {
            $earningsBreakdown = [
                'Basic Salary' => $salaryPreview->basic_salary_monthly ?? 0,
                'HRA' => $salaryPreview->hra_monthly ?? 0,
                'Conveyance' => $salaryPreview->conveyance_monthly ?? 0,
                'Medical Allowance' => $salaryPreview->medical_monthly ?? 0,
                'Special Allowance' => $salaryPreview->special_allowance_monthly ?? 0,
                'LTA' => $salaryPreview->lta_monthly ?? 0,
                'Education Allowance' => $salaryPreview->education_allowance_monthly ?? 0,
                'Bonus' => $salaryPreview->bonus_monthly ?? 0,
                'Overtime' => $salaryPreview->overtime_monthly ?? 0,
            ];
            $earningsBreakdown = array_filter($earningsBreakdown, function($value) { return $value > 0; });
        }
        
        // Build standard deductions breakdown
        $standardDeductionsBreakdown = [];
        if ($salaryPreview) {
            $standardDeductionsBreakdown = [
                'Professional Tax (PT)' => $salaryPreview->pt_monthly ?? 0,
                'Labour Welfare (LST)' => $salaryPreview->lst_monthly ?? 0,
                'TDS' => $salaryPreview->tds_monthly ?? 0,
                'Insurance Premium' => $salaryPreview->insurance_premium_monthly ?? 0,
                'Advance Salary' => $salaryPreview->advance_salary_monthly ?? 0,
                'Employee PF' => $salaryPreview->pf_employee_monthly ?? 0,
                'Employee ESI' => $salaryPreview->esi_employee_monthly ?? 0,
                'Employee NPS' => $salaryPreview->nps_employee_monthly ?? 0,
            ];
            $standardDeductionsBreakdown = array_filter($standardDeductionsBreakdown, function($value) { return $value > 0; });
        }
        
        // Get enabled other deductions
        $enabledOtherDeductions = $this->getEnabledOtherDeductions($request->employee_id, $context);
        
        // Get salary review
        $salaryReview = SalaryReview::where('institute_id', $context['institute_id'])
            ->where('branch_id', $context['branch_id'])
            ->where('employee_id', $request->employee_id)
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->first();
        
        $salarySlip = SalarySlip::where('employee_id', $request->employee_id)
            ->where('salary_month', $salaryMonth)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        $details = $salarySlip ? json_decode($salarySlip->salary_details_json ?? '{}', true) : [];
        $attendanceSummary = $details['attendance_summary'] ?? [];
        
        // Common variables
        $grossSalary = 0;
        $monthlyNetSalary = 0;
        $totalStandardDeductions = 0;
        $attendanceDeductionsTotal = 0;
        $otherDeductions = 0;
        $payableSalary = 0;
        $reviewStatus = 'pending';
        
        // Short Leave and Half Day variables (from attendance_summary)
        $shortLeavesWithinQuota = $attendanceSummary['short_leaves_within_quota'] ?? 0;
        $shortLeavesExceeded = $attendanceSummary['short_leaves_exceeded'] ?? 0;
        $halfDaysWithinQuota = $attendanceSummary['half_days_within_quota'] ?? 0;
        $halfDaysExceeded = $attendanceSummary['half_days_exceeded'] ?? 0;
        
        // Deduction amounts from attendance_deductions
        $shortLeavesWithinQuotaDeduction = $details['attendance_deductions']['short_leaves_within_quota_deduction'] ?? 0;
        $shortLeavesExceededDeduction = $details['attendance_deductions']['short_leaves_exceeded_deduction'] ?? 0;
        $halfDaysWithinQuotaDeduction = $details['attendance_deductions']['half_days_within_quota_deduction'] ?? 0;
        $halfDaysExceededDeduction = $details['attendance_deductions']['half_days_exceeded_deduction'] ?? 0;
        
        if (!$salaryReview) {
            // PENDING case - get from salary slip
            if (!$salarySlip) {
                return redirect()->route('salary.review.index')->with('error', 'Salary data not found');
            }
            
            $attendanceDeductionsBreakdown = [
                'leave_deduction' => $salarySlip->leave_deduction ?? 0,
                'absent_deduction' => $salarySlip->absent_deduction ?? 0,
                'unpaid_leave_deduction' => $salarySlip->unpaid_leave_deduction ?? 0,
                'unapproved_leave_deduction' => $salarySlip->unapproved_leave_deduction ?? 0,
                'short_attendance_deduction' => $salarySlip->short_attendance_deduction ?? 0,
                'exceeded_short_leaves_deduction' => $salarySlip->exceeded_short_leaves_deduction ?? 0,
                'exceeded_half_days_deduction' => $salarySlip->exceeded_half_days_deduction ?? 0,
            ];
            
            $attendanceDeductionsTotal = array_sum($attendanceDeductionsBreakdown);
            $totalStandardDeductions = array_sum($standardDeductionsBreakdown);
            $grossSalary = array_sum($earningsBreakdown);
            $monthlyNetSalary = $salarySlip->monthly_net_salary ?? ($grossSalary - $totalStandardDeductions);
            $otherDeductions = 0;
            $payableSalary = $salarySlip->payable_salary ?? max(0, $monthlyNetSalary - $attendanceDeductionsTotal);
            
            $salaryData = [
                'basic_salary' => $salarySlip->basic_salary,
                'gross_salary' => $grossSalary,
                'net_salary' => $salarySlip->net_salary,
                'monthly_net_salary' => $monthlyNetSalary,
                'payable_salary' => $payableSalary,
                'total_deductions' => $totalStandardDeductions + $attendanceDeductionsTotal,
                'attendance_deductions_total' => $attendanceDeductionsTotal,
                'earnings_breakdown' => $earningsBreakdown,
                'standard_deductions_breakdown' => $standardDeductionsBreakdown,
                'attendance_deductions_breakdown' => $attendanceDeductionsBreakdown,
                'leave_deduction' => $salarySlip->leave_deduction ?? 0,
                'absent_deduction' => $salarySlip->absent_deduction ?? 0,
                'unpaid_leave_deduction' => $salarySlip->unpaid_leave_deduction ?? 0,
                'unpaid_leave_days' => $unpaidLeaveDays,
                'unapproved_leave_deduction' => $salarySlip->unapproved_leave_deduction ?? 0,
                'unapproved_leave_days' => $unapprovedLeaveDays,
                'short_attendance_deduction' => $salarySlip->short_attendance_deduction ?? 0,
                'exceeded_short_leaves_deduction' => $salarySlip->exceeded_short_leaves_deduction ?? 0,
                'exceeded_half_days_deduction' => $salarySlip->exceeded_half_days_deduction ?? 0,
                // Short Leave and Half Day breakdown
                'short_leaves_within_quota' => $shortLeavesWithinQuota,
                'short_leaves_exceeded' => $shortLeavesExceeded,
                'short_leaves_within_quota_deduction' => $shortLeavesWithinQuotaDeduction,
                'short_leaves_exceeded_deduction' => $shortLeavesExceededDeduction,
                'half_days_within_quota' => $halfDaysWithinQuota,
                'half_days_exceeded' => $halfDaysExceeded,
                'half_days_within_quota_deduction' => $halfDaysWithinQuotaDeduction,
                'half_days_exceeded_deduction' => $halfDaysExceededDeduction,
                'attendance_summary' => $attendanceSummary,
                'leave_breakdown' => $details['leave_details']['leave_breakdown'] ?? [],
                'other_deductions' => 0,
                'other_deductions_details' => [],
                'daily_rate' => $details['daily_rate'] ?? 0,
                'days_in_period' => $details['days_in_period'] ?? Carbon::create($request->year, $request->month, 1)->daysInMonth,
            ];
            $reviewStatus = 'pending';
            
        } else {
            // REVIEWED or FINALIZED case - get from salary review
            $attendanceDeductionsBreakdown = [
                'leave_deduction' => $salaryReview->leave_deduction ?? 0,
                'absent_deduction' => $salaryReview->absent_deduction ?? 0,
                'unpaid_leave_deduction' => $salaryReview->unpaid_leave_deduction ?? 0,
                'unapproved_leave_deduction' => $salaryReview->unapproved_leave_deduction ?? 0,
                'short_attendance_deduction' => $salarySlip ? ($salarySlip->short_attendance_deduction ?? 0) : 0,
                'exceeded_short_leaves_deduction' => $salarySlip ? ($salarySlip->exceeded_short_leaves_deduction ?? 0) : 0,
                'exceeded_half_days_deduction' => $salarySlip ? ($salarySlip->exceeded_half_days_deduction ?? 0) : 0,
            ];
            
            // Get Short Leave and Half Day values from salary review if available, else from attendance_summary
            $shortLeavesWithinQuota = $salaryReview->short_leaves_within_quota ?? $shortLeavesWithinQuota;
            $shortLeavesExceeded = $salaryReview->short_leaves_exceeded ?? $shortLeavesExceeded;
            $halfDaysWithinQuota = $salaryReview->half_days_within_quota ?? $halfDaysWithinQuota;
            $halfDaysExceeded = $salaryReview->half_days_exceeded ?? $halfDaysExceeded;
            $shortLeavesWithinQuotaDeduction = $salaryReview->short_leaves_within_quota_deduction ?? $shortLeavesWithinQuotaDeduction;
            $shortLeavesExceededDeduction = $salaryReview->short_leaves_exceeded_deduction ?? $shortLeavesExceededDeduction;
            $halfDaysWithinQuotaDeduction = $salaryReview->half_days_within_quota_deduction ?? $halfDaysWithinQuotaDeduction;
            $halfDaysExceededDeduction = $salaryReview->half_days_exceeded_deduction ?? $halfDaysExceededDeduction;
            
            $attendanceDeductionsTotal = $salaryReview->total_attendance_deductions ?? array_sum($attendanceDeductionsBreakdown);
            $totalStandardDeductions = $salaryReview->total_standard_deductions ?? array_sum($standardDeductionsBreakdown);
            $grossSalary = $salaryReview->gross_salary ?? array_sum($earningsBreakdown);
            $monthlyNetSalary = $salaryReview->monthly_net_salary ?? ($grossSalary - $totalStandardDeductions);
            $otherDeductions = $salaryReview->other_deductions ?? 0;
            $payableSalary = $salaryReview->final_payable_salary ?? $salaryReview->payable_salary ?? max(0, $monthlyNetSalary - $attendanceDeductionsTotal - $otherDeductions);
            
            $salaryData = [
                'basic_salary' => $salaryReview->basic_salary ?? ($salarySlip->basic_salary ?? 0),
                'gross_salary' => $grossSalary,
                'net_salary' => $salaryReview->net_salary ?? ($salarySlip->net_salary ?? 0),
                'monthly_net_salary' => $monthlyNetSalary,
                'payable_salary' => $payableSalary,
                'total_deductions' => $salaryReview->total_deductions ?? ($totalStandardDeductions + $attendanceDeductionsTotal + $otherDeductions),
                'attendance_deductions_total' => $attendanceDeductionsTotal,
                'earnings_breakdown' => !empty($salaryReview->earnings_breakdown) ? $salaryReview->earnings_breakdown : $earningsBreakdown,
                'standard_deductions_breakdown' => !empty($salaryReview->deductions_breakdown) ? $salaryReview->deductions_breakdown : $standardDeductionsBreakdown,
                'attendance_deductions_breakdown' => $attendanceDeductionsBreakdown,
                'leave_deduction' => $salaryReview->leave_deduction ?? 0,
                'absent_deduction' => $salaryReview->absent_deduction ?? 0,
                'unpaid_leave_deduction' => $salaryReview->unpaid_leave_deduction ?? 0,
                'unpaid_leave_days' => $unpaidLeaveDays,
                'unapproved_leave_deduction' => $salaryReview->unapproved_leave_deduction ?? 0,
                'unapproved_leave_days' => $unapprovedLeaveDays,
                'short_attendance_deduction' => $salarySlip ? ($salarySlip->short_attendance_deduction ?? 0) : 0,
                'exceeded_short_leaves_deduction' => $salarySlip ? ($salarySlip->exceeded_short_leaves_deduction ?? 0) : 0,
                'exceeded_half_days_deduction' => $salarySlip ? ($salarySlip->exceeded_half_days_deduction ?? 0) : 0,
                // Short Leave and Half Day breakdown
                'short_leaves_within_quota' => $shortLeavesWithinQuota,
                'short_leaves_exceeded' => $shortLeavesExceeded,
                'short_leaves_within_quota_deduction' => $shortLeavesWithinQuotaDeduction,
                'short_leaves_exceeded_deduction' => $shortLeavesExceededDeduction,
                'half_days_within_quota' => $halfDaysWithinQuota,
                'half_days_exceeded' => $halfDaysExceeded,
                'half_days_within_quota_deduction' => $halfDaysWithinQuotaDeduction,
                'half_days_exceeded_deduction' => $halfDaysExceededDeduction,
                'attendance_summary' => $salaryReview->attendance_summary ?? ($attendanceSummary ?? []),
                'leave_breakdown' => $salaryReview->leave_breakdown ?? ($details['leave_details']['leave_breakdown'] ?? []),
                'other_deductions' => $otherDeductions,
                'other_deductions_details' => $salaryReview->other_deductions_details ?? [],
                'daily_rate' => $details['daily_rate'] ?? 0,
                'days_in_period' => $details['days_in_period'] ?? Carbon::create($request->year, $request->month, 1)->daysInMonth,
            ];
            $reviewStatus = $salaryReview->review_status;
        }
        
        $calculationNote = $this->generateCalculationNote($payrollConfig, $request->year, $request->month);
        
        // Get leaves taken this month
        $monthStart = Carbon::create($request->year, $request->month, 1)->startOfMonth();
        $monthEnd = Carbon::create($request->year, $request->month, 1)->endOfMonth();
        
        $leavesThisMonth = EmployeeLeave::where('employee_id', $request->employee_id)
            ->where('institute_id', $context['institute_id'])
            ->where('final_status', 'Approved')
            ->where(function($query) use ($monthStart, $monthEnd) {
                $query->whereBetween('start_date', [$monthStart, $monthEnd])
                    ->orWhereBetween('end_date', [$monthStart, $monthEnd])
                    ->orWhere(function($q) use ($monthStart, $monthEnd) {
                        $q->where('start_date', '<=', $monthStart)
                            ->where('end_date', '>=', $monthEnd);
                    });
            })
            ->get();
        
        $leaveBreakdown = [];
        $totalLeavesTakenThisMonth = 0;
        
        foreach ($leavesThisMonth as $leave) {
            $startDate = Carbon::parse($leave->start_date);
            $endDate = Carbon::parse($leave->end_date);
            
            $periodStart = $startDate < $monthStart ? $monthStart : $startDate;
            $periodEnd = $endDate > $monthEnd ? $monthEnd : $endDate;
            $daysInMonth = $periodEnd->diffInDays($periodStart) + 1;
            
            $days = $daysInMonth;
            $durationType = $leave->leave_duration_type ?? 'Full Day';
            
            if (in_array($durationType, ['Half Day', 'half_day', 'half_days','Half Day Leave', 'Half Day', 'Half Day Leave (Half Day)'])) {
                $days = 0.5;
            } elseif (in_array($durationType, ['Short Leave', 'short_leave','Short Day Leave', 'Short Leave', 'Short Leave (Day)'])) {
                $days = 0.25;
            }
            
            $leaveType = $leave->leave_type ?? 'General Leave';
            if (!isset($leaveBreakdown[$leaveType])) {
                $leaveBreakdown[$leaveType] = [
                    'type' => $leaveType,
                    'days' => 0,
                    'leaves' => []
                ];
            }
            
            $leaveBreakdown[$leaveType]['days'] += $days;
            $leaveBreakdown[$leaveType]['leaves'][] = [
                'start' => $startDate->format('d M Y'),
                'end' => $endDate->format('d M Y'),
                'days' => $days,
                'duration_type' => $durationType
            ];
            
            $totalLeavesTakenThisMonth += $days;
        }
        
        // Build timeline
        $finalizationTimeline = [];
        
        if ($attendanceReview) {
            $finalizationTimeline[] = [
                'title' => 'Attendance ' . ($attendanceReview->review_status === 'finalized' ? 'Finalized' : 'Status: ' . ucfirst($attendanceReview->review_status)),
                'status' => $attendanceReview->review_status,
                'date' => $attendanceReview->finalized_at ? Carbon::parse($attendanceReview->finalized_at)->format('d M Y h:i A') : 'Pending',
                'by' => $attendanceReview->finalized_by ? ($this->getEmployeeName($attendanceReview->finalized_by) ?? 'System') : null,
                'icon' => 'fa-calendar-check'
            ];
        }
        
        if ($salaryReview) {
            $finalizationTimeline[] = [
                'title' => 'Salary ' . ucfirst($reviewStatus),
                'status' => $reviewStatus,
                'date' => $salaryReview->finalized_at ? Carbon::parse($salaryReview->finalized_at)->format('d M Y h:i A') : ($salaryReview->reviewed_at ? Carbon::parse($salaryReview->reviewed_at)->format('d M Y h:i A') : 'Pending'),
                'by' => $salaryReview->finalized_by ? ($this->getEmployeeName($salaryReview->finalized_by) ?? 'System') : ($salaryReview->reviewed_by ? ($this->getEmployeeName($salaryReview->reviewed_by) ?? 'System') : null),
                'icon' => 'fa-rupee-sign'
            ];
        }
        
        $monthName = Carbon::create($request->year, $request->month)->format('F Y');
        
        // Pass data to view
        return view('instituteAdmin.Payroll.DetailedSalaryReview', compact(
            'employee',
            'salaryData',
            'reviewStatus',
            'salaryReview',
            'attendanceReview',
            'leaveBreakdown',
            'totalLeavesTakenThisMonth',
            'finalizationTimeline',
            'monthName',
            'request',
            'payrollExecutionDetails',
            'calculationNote',
            'enabledOtherDeductions'
        ));
    }
    
    /**
     * Helper method to get employee name
     */
    private function getEmployeeName($employeeId)
    {
        $emp = EmployeeDetails::where('employee_id', $employeeId)
            ->orWhere('id', $employeeId)
            ->first();
        return $emp ? $emp->name : null;
    }
    
    
    /**
     * Format leave type label (consistent with AttendanceReviewController)
     */
    private function formatLeaveTypeLabel($type)
    {
        $type = strtolower(trim($type));
        $type = str_replace([' ', '-'], '_', $type);

        if (in_array($type, ['half_day', 'half_days'])) {
            return 'Half Day';
        }
        if (in_array($type, ['short_leave', 'short_leaves'])) {
            return 'Short Leave';
        }
        if ($type === 'unpaid' || $type === 'unpaid_leave' || $type === 'unpaid_leaves') {
            return 'Unpaid';
        }
        if ($type === 'casual' || $type === 'casual_leave' || $type === 'casual_leaves') {
            return 'Casual';
        }

        return ucwords(str_replace(['_', '-'], ' ', $type));
    }
    
    // /**
    //  * Bulk finalize salaries
    //  */
    // public function bulkFinalizeSalary(Request $request)
    // {
    //     $request->validate([
    //         'employee_ids' => 'required|array',
    //         'year' => 'required|integer',
    //         'month' => 'required|integer',
    //     ]);
        
    //     $context = $this->getInstituteBranchContext();
        
    //     $finalizedCount = 0;
    //     $errors = [];
        
    //     foreach ($request->employee_ids as $employeeId) {
    //         try {
    //             $salaryReview = SalaryReview::where('institute_id', $context['institute_id'])
    //                 ->where('branch_id', $context['branch_id'])
    //                 ->where('employee_id', $employeeId)
    //                 ->where('year', $request->year)
    //                 ->where('month', $request->month)
    //                 ->first();
                
    //             if (!$salaryReview) {
    //                 $errors[] = "Salary not reviewed for employee ID {$employeeId}";
    //                 continue;
    //             }
                
    //             if ($salaryReview->review_status === 'finalized') {
    //                 $errors[] = "Salary already finalized for employee ID {$employeeId}";
    //                 continue;
    //             }
                
    //             $salaryReview->update([
    //                 'review_status' => 'finalized',
    //                 'finalize_notes' => 'Bulk finalized',
    //                 'finalized_by' => auth()->user()->employee_id ?? auth()->user()->id,
    //                 'finalized_at' => now()
    //             ]);
                
    //             // Update SalarySlip
    //             $salaryMonth = Carbon::create($request->year, $request->month, 1)->format('Y-m');
    //             SalarySlip::where('employee_id', $employeeId)
    //                 ->where('salary_month', $salaryMonth)
    //                 ->where('institute_id', $context['institute_id'])
    //                 ->update(['status' => 'approved']);
                
    //             $finalizedCount++;
    //         } catch (\Exception $e) {
    //             $errors[] = "Error processing employee ID {$employeeId}: " . $e->getMessage();
    //         }
    //     }
        
    //     return response()->json([
    //         'success' => true,
    //         'message' => "Successfully finalized {$finalizedCount} employee(s)" . (count($errors) > 0 ? " with " . count($errors) . " errors" : ""),
    //         'finalized_count' => $finalizedCount,
    //         'errors' => $errors
    //     ]);
    // }

    public function bulkFinalizeSalary(Request $request)
    {
        $request->validate([
            'employee_ids' => 'required|array',
            'year' => 'required|integer',
            'month' => 'required|integer',
        ]);
        
        $context = $this->getInstituteBranchContext();
        
        $finalizedCount = 0;
        $emailSentCount = 0;
        $errors = [];
        
        foreach ($request->employee_ids as $employeeId) {
            try {
                $salaryReview = SalaryReview::where('institute_id', $context['institute_id'])
                    ->where('branch_id', $context['branch_id'])
                    ->where('employee_id', $employeeId)
                    ->where('year', $request->year)
                    ->where('month', $request->month)
                    ->first();
                
                if (!$salaryReview) {
                    $errors[] = "Salary not reviewed for employee ID {$employeeId}";
                    continue;
                }
                
                if ($salaryReview->review_status === 'finalized') {
                    $errors[] = "Salary already finalized for employee ID {$employeeId}";
                    continue;
                }
                
                $salaryReview->update([
                    'review_status' => 'finalized',
                    'finalize_notes' => 'Bulk finalized',
                    'finalized_by' => auth()->user()->employee_id ?? auth()->user()->id,
                    'finalized_at' => now()
                ]);
                
                // Update SalarySlip
                $salaryMonth = Carbon::create($request->year, $request->month, 1)->format('Y-m');
                SalarySlip::where('employee_id', $employeeId)
                    ->where('salary_month', $salaryMonth)
                    ->where('institute_id', $context['institute_id'])
                    ->update(['status' => 'approved']);
                
                // ✅ Send email notification
                $this->sendSalaryFinalizedEmail($employeeId, $request->year, $request->month, $context, $salaryReview);
                
                $finalizedCount++;
                $emailSentCount++;
                
            } catch (\Exception $e) {
                $errors[] = "Error processing employee ID {$employeeId}: " . $e->getMessage();
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "Successfully finalized {$finalizedCount} employee(s)" . 
                        ($emailSentCount > 0 ? " and sent {$emailSentCount} email(s)" : "") . 
                        (count($errors) > 0 ? " with " . count($errors) . " errors" : ""),
            'finalized_count' => $finalizedCount,
            'email_sent_count' => $emailSentCount,
            'errors' => $errors
        ]);
    }

    /**
     * Get payroll execution details for API response
     */
    public function getPayrollExecutionDetails(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'year' => 'required|integer',
            'month' => 'required|integer',
        ]);
        
        $context = $this->getInstituteBranchContext();
        
        $employee = EmployeeDetails::where('employee_id', $request->employee_id)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        if (!$employee || !$employee->department_id) {
            return response()->json([
                'success' => false,
                'message' => 'Employee or department not found'
            ]);
        }
        
        $payrollConfig = PayrollExecution::where('department_id', $employee->department_id)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        if (!$payrollConfig) {
            return response()->json([
                'success' => false,
                'message' => 'No payroll configuration found for this department'
            ]);
        }
        
        $payDate = $this->calculatePayDate($request->year, $request->month, $payrollConfig);
        $workingDaysInCycle = $this->calculateWorkingDaysInCycle($request->year, $request->month, $payrollConfig);
        
        return response()->json([
            'success' => true,
            'data' => [
                'payroll_cycle' => $payrollConfig->payroll_cycle,
                'cycle_days' => $payrollConfig->cycle_days,
                'execution_day' => $payrollConfig->execution_day,
                'pay_date' => $payDate,
                'working_days_in_cycle' => $workingDaysInCycle,
                'calculation_basis' => $payrollConfig->payroll_cycle === 'days' 
                    ? "Salary calculated based on {$payrollConfig->cycle_days}-day payroll cycle" 
                    : "Salary calculated based on calendar days of the month",
            ]
        ]);
    }

    /**
     * Get enabled other deduction types from payroll policy
     */
    private function getEnabledOtherDeductions($employeeId, $context)
    {
        // Get employee's salary structure to find payroll_policy_id
        $salaryStructure = EmployeeSalaryStructure::where('employee_id', $employeeId)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        if (!$salaryStructure || !$salaryStructure->payroll_policy_id) {
            return [
                'loan_enabled' => false,
                'advance_enabled' => false,
                'custom_deductions' => []
            ];
        }
        
        // Get other deductions from payroll policy
        $otherDeductions = PayrollPolicyOtherDeduction::where('payroll_policy_id', $salaryStructure->payroll_policy_id)
            ->where('institute_id', $context['institute_id'])
            ->first();
        // dd($otherDeductions);
        if (!$otherDeductions) {
            return [
                'loan_enabled' => false,
                'advance_enabled' => false,
                'custom_deductions' => []
            ];
        }
        
        $customDeductions = [];
        if (!empty($otherDeductions->custom_deductions)) {
            $customDeductionsRaw = $otherDeductions->custom_deductions;
            if (is_string($customDeductionsRaw)) {
                $customDeductionsRaw = json_decode($customDeductionsRaw, true);
            }
            
            if (is_array($customDeductionsRaw)) {
                foreach ($customDeductionsRaw as $custom) {
                    if (isset($custom['selected']) && $custom['selected'] == true) {
                        $customDeductions[] = [
                            'name' => $custom['name'] ?? 'Custom Deduction',
                            'type' => $custom['type'] ?? 'fixed',
                            'description' => $custom['description'] ?? '',
                            'selected' => true
                        ];
                    }
                }
            }
        }
        
        return [
            'loan_enabled' => $otherDeductions->loan_selected ?? false,
            'advance_enabled' => $otherDeductions->advance_selected ?? false,
            'custom_deductions' => $customDeductions
        ];
    }

    
    /**
     * Get leave deduction configuration from database (mapped from leave type)
     */
    private function getLeaveDeductionConfigForType($leaveType)
    {
        $merchantId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();
        
        // Map the normalized leave types to database values (matching AttendanceReviewController)
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
            'short_leave' => ['approved' => 25, 'unapproved' => 50],
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

    /**
     * Normalize leave type (consistent with AttendanceReviewController)
     */
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
     * Get leave deduction percentage using the proper mapping
     */
    private function getLeaveDeductionPercentage($leaveType, $isApproved = true)
    {
        $config = $this->getLeaveDeductionConfigForType($leaveType);
        return $isApproved ? $config['approved_percentage'] : $config['unapproved_percentage'];
    }

    /**
     * Check if email notification is enabled for a module
     */
    private function isEmailNotificationEnabled($moduleName)
    {
        try {
            $merchantId = auth()->user()->institute_id;
            
            $setting = InstituteNotificationSetting::where('institute_id', $merchantId)
                ->where('module_name', $moduleName)
                ->first();
            
            // If no setting found, email is enabled by default
            if (!$setting) {
                return true;
            }
            
            return $setting->email_enabled;
            
        } catch (\Exception $e) {
            \Log::error('Error checking email notification status: ' . $e->getMessage());
            return true;
        }
    }

    /**
     * Send salary finalized email to employee
     */
    private function sendSalaryFinalizedEmail($employeeId, $year, $month, $context, $salaryReview = null)
    {
        // try {
            // Get employee details
            $employee = EmployeeDetails::where('employee_id', $employeeId)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            if (!$employee || empty($employee->email)) {
                return;
            }
            
            $emailEnabled = $this->isEmailNotificationEnabled('salary_finalized');
            
            if (!$emailEnabled) {
                return;
            }
            
            // Get salary review data
            if (!$salaryReview) {
                $salaryReview = SalaryReview::where('institute_id', $context['institute_id'])
                    ->where('branch_id', $context['branch_id'])
                    ->where('employee_id', $employeeId)
                    ->where('year', $year)
                    ->where('month', $month)
                    ->first();
            }
            
            if (!$salaryReview) {
                \Log::warning('Salary review not found for email', ['employee_id' => $employeeId]);
                return;
            }
            
            // Get payroll execution details for pay date
            $payrollConfig = null;
            $payDate = null;
            if ($employee->department_id) {
                $payrollConfig = PayrollExecution::where('department_id', $employee->department_id)
                    ->where('institute_id', $context['institute_id'])
                    ->first();
                
                if ($payrollConfig) {
                    $payDate = $this->calculatePayDate($year, $month, $payrollConfig);
                }
            }
            
            $monthName = Carbon::create($year, $month, 1)->format('F');
            
            // Build deductions breakdown for email
            $deductionsBreakdown = [];
            
            // Standard deductions
            if ($salaryReview->pt_deduction > 0) $deductionsBreakdown['Professional Tax (PT)'] = $salaryReview->pt_deduction;
            if ($salaryReview->lst_deduction > 0) $deductionsBreakdown['Labour Welfare (LST)'] = $salaryReview->lst_deduction;
            if ($salaryReview->tds_deduction > 0) $deductionsBreakdown['TDS'] = $salaryReview->tds_deduction;
            if ($salaryReview->insurance_premium > 0) $deductionsBreakdown['Insurance Premium'] = $salaryReview->insurance_premium;
            if ($salaryReview->advance_salary_deduction > 0) $deductionsBreakdown['Advance Salary'] = $salaryReview->advance_salary_deduction;
            if ($salaryReview->pf_employee_deduction > 0) $deductionsBreakdown['Employee PF'] = $salaryReview->pf_employee_deduction;
            if ($salaryReview->esi_employee_deduction > 0) $deductionsBreakdown['Employee ESI'] = $salaryReview->esi_employee_deduction;
            if ($salaryReview->nps_employee_deduction > 0) $deductionsBreakdown['Employee NPS'] = $salaryReview->nps_employee_deduction;
            
            // Attendance deductions
            if (($salaryReview->absent_deduction ?? 0) > 0) $deductionsBreakdown['Absent Deduction'] = $salaryReview->absent_deduction;
            if (($salaryReview->leave_deduction ?? 0) > 0) $deductionsBreakdown['Leave Deduction'] = $salaryReview->leave_deduction;
            if (($salaryReview->unpaid_leave_deduction ?? 0) > 0) $deductionsBreakdown['Unpaid Leave'] = $salaryReview->unpaid_leave_deduction;
            if (($salaryReview->unapproved_leave_deduction ?? 0) > 0) $deductionsBreakdown['Unapproved Leave'] = $salaryReview->unapproved_leave_deduction;
            
            // Other deductions
            if ($salaryReview->loan_deduction > 0) $deductionsBreakdown['Loan Deduction'] = $salaryReview->loan_deduction;
            if ($salaryReview->advance_deduction > 0) $deductionsBreakdown['Advance Deduction'] = $salaryReview->advance_deduction;
            if ($salaryReview->custom_deductions_total > 0) $deductionsBreakdown['Custom Deductions'] = $salaryReview->custom_deductions_total;
            
            Mail::send('emails.salary-finalized', [
                'employeeName' => $employee->name,
                'employeeCode' => $employee->employee_code,
                'year' => $year,
                'month' => $month,
                'monthName' => $monthName,
                'basicSalary' => $salaryReview->basic_salary ?? 0,
                'grossSalary' => $salaryReview->gross_salary ?? 0,
                'netSalary' => $salaryReview->final_payable_salary ?? $salaryReview->net_salary ?? 0,
                'totalDeductions' => $salaryReview->total_deductions ?? 0,
                'deductionsBreakdown' => $deductionsBreakdown,
                'attendanceSummary' => $salaryReview->attendance_summary ?? [],
                'payDate' => $payDate,
            ], function ($message) use ($employee, $monthName, $year) {
                $message->to($employee->email)
                        ->subject('Salary Finalized - ' . $monthName . ' ' . $year . ' - ' . config('app.name'));
            });
            
            \Log::info('Salary finalized email sent to: ' . $employee->email);
            
        // } catch (\Exception $e) {
        //     \Log::error('Failed to send salary finalized email: ' . $e->getMessage(), [
        //         'employee_id' => $employeeId,
        //         'year' => $year,
        //         'month' => $month
        //     ]);
        // }
    }
}