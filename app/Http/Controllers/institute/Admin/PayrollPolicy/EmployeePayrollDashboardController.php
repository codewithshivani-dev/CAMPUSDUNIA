<?php

namespace App\Http\Controllers\institute\Admin\PayrollPolicy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeDetails;
use App\Models\AttendanceReview;
use App\Models\SalaryReview;
use App\Models\SalarySlip;
use App\Models\FinalSalarySlip;
use App\Models\EmployeeSalaryStructure;
use App\Models\EmployeeLeave;
use App\Models\ProvidentFundPolicy;
use App\Models\PayrollPolicyAllowance;
use App\Models\SalaryStructureAllowances;
use App\Models\SalaryPreview;
use App\Models\PayrollPolicyTaxDeduction;
use App\Models\PayrollPolicyOtherDeduction;
use App\Models\SalaryStructureDeduction;
use App\Models\SalaryStructureBonus;
use App\Models\SalaryStructureOvertime;
use App\Models\EmployeeLeaveBalance;
use App\Traits\InstituteBranchAccess;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\LeaveDeduction;
use App\Models\Departments;
use App\Models\PayrollExecution;

class EmployeePayrollDashboardController extends Controller
{
    use InstituteBranchAccess;

    public function index(Request $request)
    {
        $employee = $this->getLoggedInEmployee();
       
        $instituteId = auth()->user()->institute_id;
        
        $department = Departments::where('institute_id', $instituteId)
            ->where('department_id', $employee->department_id)
            ->first();
       
        if (!$employee) {
            return redirect()->route('login')->with('error', 'Employee record not found.');
        }
        
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', Carbon::now()->month);
        $context = $this->getInstituteBranchContext();
        
        // Get dynamic data
        $deductionRules = $this->getLeaveDeductionRules($context);
        $payrollRules = $this->getPayrollExecutionRules($employee, $context);
        
        // Get attendance status for the month
        $attendanceReview = AttendanceReview::where('employee_id', $employee->employee_id)
            ->where('year', $year)
            ->where('month', $month)
            ->first();
        
        // Get salary status for the month
        $salaryReview = SalaryReview::where('employee_id', $employee->employee_id)
            ->where('year', $year)
            ->where('month', $month)
            ->first();
        
        // Get salary slip for the month
        $salarySlip = SalarySlip::where('employee_id', $employee->employee_id)
            ->where('salary_month', Carbon::create($year, $month, 1)->format('Y-m'))
            ->first();
        
        // Get final salary slip
        $finalSalarySlip = FinalSalarySlip::where('employee_id', $employee->employee_id)
            ->where('year', $year)
            ->where('month', $month)
            ->first();
        
        // Get current month journey status
        $currentMonthStatus = $this->getCurrentMonthJourneyStatus($employee, $year, $month);
        
        // Get recent salary history (last 6 months)
        $salaryHistory = $this->getSalaryHistory($employee->employee_id, 6);
        
        // Get leave balances
        $leaveBalances = $this->getEmployeeLeaveBalances($employee->employee_id);
        
        // Get current year summary
        $yearlySummary = $this->getYearlySummary($employee->employee_id, $year);
        
        // Get available years for filter
        $availableYears = range(Carbon::now()->subYears(2)->year, Carbon::now()->year);
       
        // Get months for dropdown
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = Carbon::create()->month($i)->format('F');
        }
        
        return view('instituteAdmin.EmployeeFiles.PayrollDashboard', compact(
            'employee',
            'department',
            'attendanceReview',
            'salaryReview',
            'salarySlip',
            'finalSalarySlip',
            'currentMonthStatus',
            'salaryHistory',
            'leaveBalances',
            'yearlySummary',
            'availableYears',
            'months',
            'year',
            'month',
            'deductionRules',
            'payrollRules'
        ));
    }
    
    /**
     * Get current month journey status (5 steps)
     */
    private function getCurrentMonthJourneyStatus($employee, $year, $month)
    {
        $context = $this->getInstituteBranchContext();

        $attendanceReview = AttendanceReview::where('employee_id', $employee->employee_id)
            ->where('year', $year)
            ->where('month', $month)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        $salaryReview = SalaryReview::where('employee_id', $employee->employee_id)
            ->where('year', $year)
            ->where('month', $month)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        $finalSalarySlip = FinalSalarySlip::where('employee_id', $employee->employee_id)
            ->where('year', $year)
            ->where('month', $month)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        $regularSalarySlip = SalarySlip::where('employee_id', $employee->employee_id)
            ->where('salary_month', Carbon::create($year, $month, 1)->format('Y-m'))
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        // Get Payroll Execution config for the department
        $payrollExecuted = false;
        $payrollDate = null;
        if ($employee && $employee->department_id) {
            $payrollConfig = PayrollExecution::where('department_id', $employee->department_id)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            if ($payrollConfig && $payrollConfig->execution_day) {
                $executionDay = $payrollConfig->execution_day;
                $payrollDateObj = Carbon::create($year, $month, $executionDay);
                if ($executionDay > $payrollDateObj->daysInMonth) {
                    $payrollDateObj = Carbon::create($year, $month, $payrollDateObj->daysInMonth);
                }
                
                // Check if payroll date has passed and salary is finalized
                if ($salaryReview && $salaryReview->review_status === 'finalized' && Carbon::now()->gte($payrollDateObj)) {
                    $payrollExecuted = true;
                    $payrollDate = $payrollDateObj;
                }
            } else {
                // Default: month-end payroll
                $payrollDateObj = Carbon::create($year, $month, 1)->endOfMonth();
                if ($salaryReview && $salaryReview->review_status === 'finalized' && Carbon::now()->gte($payrollDateObj)) {
                    $payrollExecuted = true;
                    $payrollDate = $payrollDateObj;
                }
            }
        }
        
        // Define 5-step journey statuses
        $steps = [
            'attendance_status' => ['status' => 'pending', 'completed_at' => null],
            'attendance_finalized_status' => ['status' => 'pending', 'completed_at' => null],
            'salary_finalized_status' => ['status' => 'pending', 'completed_at' => null],
            'payroll_status' => ['status' => 'pending', 'completed_at' => null],
            'slip_status' => ['status' => 'pending', 'completed_at' => null],
        ];
        
        // STEP 1: Attendance exists
        if ($attendanceReview) {
            $steps['attendance_status']['status'] = 'completed';
            $steps['attendance_status']['completed_at'] = $attendanceReview->created_at;
            
            // STEP 2: Attendance finalized
            if ($attendanceReview->review_status === 'finalized') {
                $steps['attendance_finalized_status']['status'] = 'completed';
                $steps['attendance_finalized_status']['completed_at'] = $attendanceReview->finalized_at;
                $steps['attendance_finalized_status']['completed_by'] = $attendanceReview->finalized_by;
            } else {
                $steps['attendance_finalized_status']['status'] = 'in_progress';
            }
        } else {
            $steps['attendance_status']['status'] = 'pending';
            $steps['attendance_finalized_status']['status'] = 'blocked';
            $steps['salary_finalized_status']['status'] = 'blocked';
            $steps['payroll_status']['status'] = 'blocked';
            $steps['slip_status']['status'] = 'blocked';
            
            $completedSteps = 0;
            $totalSteps = 5;
            $progressPercentage = 0;
            
            return [
                'journey_steps' => $steps,
                'completed_steps' => 0,
                'total_steps' => 5,
                'progress_percentage' => 0,
                'attendance_status' => 'pending',
                'attendance_percentage' => 0,
                'present_days' => 0,
                'absent_days' => 0,
                'leave_days' => 0,
                'salary_status' => 'pending',
                'final_payable' => 0,
                'slip_generated' => false,
                'slip_id' => null,
            ];
        }
        
        // STEP 3: Salary finalized (from SalaryReview)
        if ($salaryReview) {
            if ($salaryReview->review_status === 'finalized') {
                $steps['salary_finalized_status']['status'] = 'completed';
                $steps['salary_finalized_status']['completed_at'] = $salaryReview->finalized_at;
                $steps['salary_finalized_status']['completed_by'] = $salaryReview->finalized_by;
            } elseif ($salaryReview->review_status === 'reviewed') {
                $steps['salary_finalized_status']['status'] = 'in_progress';
            } else {
                $steps['salary_finalized_status']['status'] = 'pending';
            }
        } elseif ($attendanceReview && $attendanceReview->review_status === 'finalized') {
            $steps['salary_finalized_status']['status'] = 'pending';
        } else {
            $steps['salary_finalized_status']['status'] = 'blocked';
        }
        
        // STEP 4: Payroll Executed
        if ($payrollExecuted) {
            $steps['payroll_status']['status'] = 'completed';
            $steps['payroll_status']['completed_at'] = $payrollDate ? $payrollDate->toDateTimeString() : now();
        } elseif ($steps['salary_finalized_status']['status'] === 'completed') {
            $steps['payroll_status']['status'] = 'in_progress';
        } elseif ($steps['salary_finalized_status']['status'] === 'blocked') {
            $steps['payroll_status']['status'] = 'blocked';
        } else {
            $steps['payroll_status']['status'] = 'pending';
        }
        
        // STEP 5: Salary Slip Generated (from FinalSalarySlip)
        // if ($finalSalarySlip) {
        //     $steps['slip_status']['status'] = 'completed';
        //     $steps['slip_status']['completed_at'] = $finalSalarySlip->created_at;
        // } elseif ($payrollExecuted) {
        //     $steps['slip_status']['status'] = 'in_progress';
        // } elseif ($steps['salary_finalized_status']['status'] === 'blocked') {
        //     $steps['slip_status']['status'] = 'blocked';
        // } else {
        //     $steps['slip_status']['status'] = 'pending';
        // }
        // STEP 4: Payroll Executed - Check if FinalSalarySlip exists
        if ($finalSalarySlip) {
            $steps['payroll_status']['status'] = 'completed';
            $steps['payroll_status']['completed_at'] = $finalSalarySlip->created_at;
            $steps['slip_status']['status'] = 'completed';
            $steps['slip_status']['completed_at'] = $finalSalarySlip->created_at;
        } elseif ($steps['salary_finalized_status']['status'] === 'completed') {
            $steps['payroll_status']['status'] = 'in_progress';
            $steps['slip_status']['status'] = 'pending';
        } elseif ($steps['salary_finalized_status']['status'] === 'blocked') {
            $steps['payroll_status']['status'] = 'blocked';
            $steps['slip_status']['status'] = 'blocked';
        } else {
            $steps['payroll_status']['status'] = 'pending';
            $steps['slip_status']['status'] = 'pending';
        }
        
        // Calculate completed steps count
        $completedSteps = 0;
        foreach ($steps as $step) {
            if ($step['status'] === 'completed') {
                $completedSteps++;
            }
        }
        $totalSteps = 5;
        $progressPercentage = ($completedSteps / $totalSteps) * 100;
        
        return [
            'journey_steps' => $steps,
            'completed_steps' => $completedSteps,
            'total_steps' => $totalSteps,
            'progress_percentage' => round($progressPercentage, 1),
            'attendance_status' => $attendanceReview ? $attendanceReview->review_status : 'pending',
            'attendance_percentage' => $attendanceReview ? $attendanceReview->attendance_percentage : 0,
            'present_days' => $attendanceReview ? $attendanceReview->present_days : 0,
            'absent_days' => $attendanceReview ? $attendanceReview->absent_days : 0,
            'leave_days' => $attendanceReview ? $attendanceReview->leave_days : 0,
            'salary_status' => $salaryReview ? $salaryReview->review_status : 'pending',
            'final_payable' => $salaryReview ? ($salaryReview->final_payable_salary ?? $salaryReview->payable_salary ?? 0) : 0,
            'slip_generated' => $finalSalarySlip ? true : false,
            'slip_id' => $finalSalarySlip ? $finalSalarySlip->slip_id : null,
            // Additional statuses for blade
            'attendance_recorded' => $attendanceReview ? true : false,
            'attendance_finalized' => $attendanceReview && $attendanceReview->review_status === 'finalized',
            'salary_finalized' => $salaryReview && $salaryReview->review_status === 'finalized',
            'payroll_executed' => $payrollExecuted,
            'slip_ready' => $finalSalarySlip ? true : false,
        ];
    }
 
    
    /**
     * Get salary slip details
     */
    public function getSalarySlip(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer',
        ]);
        
        $employee = $this->getLoggedInEmployee();
        
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found'], 401);
        }
        
        $context = $this->getInstituteBranchContext();
        $salaryMonth = Carbon::create($request->year, $request->month, 1)->format('Y-m');
        
        // Get department name
        $departmentName = Departments::where('department_id', $employee->department_id)
            ->where('institute_id', $context['institute_id'])
            ->value('department');
        
        // FIRST: Try to get from FinalSalarySlip table
        $finalSalarySlip = FinalSalarySlip::where('employee_id', $employee->employee_id)
            ->where('salary_month', $salaryMonth)
            ->first();
        
        if ($finalSalarySlip) {
            $salaryReview = SalaryReview::where('employee_id', $employee->employee_id)
                ->where('year', $request->year)
                ->where('month', $request->month)
                ->first();
            
            $details = [
                'earnings_breakdown' => $finalSalarySlip->earnings_breakdown ?? [],
                'standard_deductions_breakdown' => $finalSalarySlip->standard_deductions ?? [],
                'attendance_deductions_breakdown' => $finalSalarySlip->attendance_deductions ?? [],
                'other_deductions_breakdown' => $finalSalarySlip->other_deductions ?? [],
                'attendance_summary' => $finalSalarySlip->attendance_summary ?? [],
                'leave_breakdown' => $finalSalarySlip->leave_breakdown ?? [],
                'department' => $departmentName ?? 'N/A',  // ✅ Fixed: string not object
                'calculation_note' => $salaryReview ? $salaryReview->calculation_note : null,
            ];
            
            return response()->json([
                'success' => true,
                'data' => [
                    'slip' => $finalSalarySlip,
                    'details' => $details,
                    'is_final' => true
                ]
            ]);
        }
        
        // SECOND: Try to get from regular SalarySlip
        $regularSalarySlip = SalarySlip::where('employee_id', $employee->employee_id)
            ->where('salary_month', $salaryMonth)
            ->first();
        
        if ($regularSalarySlip) {
            $details = json_decode($regularSalarySlip->salary_details_json, true);
            $details['department'] = $departmentName ?? 'N/A';  // ✅ Fixed: add department string
            
            return response()->json([
                'success' => true,
                'data' => [
                    'slip' => $regularSalarySlip,
                    'details' => $details,
                    'is_final' => false
                ]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Salary slip not found for the selected month.'
        ]);
    }
    
    /**
     * Get attendance details for a month
     */
    public function getAttendanceDetails(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer',
        ]);
        
        $employee = $this->getLoggedInEmployee();
        
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found'], 401);
        }
        
        $attendanceReview = AttendanceReview::where('employee_id', $employee->employee_id)
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->first();
        
        if (!$attendanceReview) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance data not found for the selected month'
            ]);
        }
        
        $attendanceDetails = $attendanceReview->attendance_details;
        if (is_string($attendanceDetails)) {
            $attendanceDetails = json_decode($attendanceDetails, true);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'attendance' => $attendanceReview,
                'details' => $attendanceDetails
            ]
        ]);
    }
    
    /**
     * Get logged in employee
     */
    private function getLoggedInEmployee()
    {
        return EmployeeDetails::where('user_id', auth()->id())->first();
    }
    
    /**
     * Get salary history (last 6 months)
     */
    private function getSalaryHistory($employeeId, $months = 6)
    {
        $history = [];
        $processedMonths = []; // Track processed months to avoid duplicates
        
        for ($i = 0; $i < $months; $i++) {
            $date = Carbon::now()->subMonths($i);
            $year = $date->year;
            $month = $date->month;
            $monthKey = $year . '-' . $month;
            
            // Skip if already processed
            if (in_array($monthKey, $processedMonths)) {
                continue;
            }
            
            // PRIORITY 1: Check FinalSalarySlip first (payroll executed)
            $finalSalarySlip = FinalSalarySlip::where('employee_id', $employeeId)
                ->where('year', $year)
                ->where('month', $month)
                ->first();
            
            if ($finalSalarySlip) {
                $history[] = [
                    'year' => $year,
                    'month' => $month,
                    'month_name' => $date->format('F Y'),
                    'final_payable' => $finalSalarySlip->final_payable ?? 0,
                    'status' => 'finalized',
                    'status_label' => 'Payroll Executed',
                    'status_color' => 'success',
                    'slip_id' => $finalSalarySlip->slip_id,
                    'is_payroll_executed' => true,
                    'executed_date' => $finalSalarySlip->created_at
                ];
                $processedMonths[] = $monthKey;
                continue;
            }
            
            // PRIORITY 2: Check SalaryReview (salary finalized but payroll not executed)
            $salaryReview = SalaryReview::where('employee_id', $employeeId)
                ->where('year', $year)
                ->where('month', $month)
                ->where('review_status', 'finalized')
                ->first();
            
            if ($salaryReview) {
                $history[] = [
                    'year' => $year,
                    'month' => $month,
                    'month_name' => $date->format('F Y'),
                    'final_payable' => $salaryReview->final_payable_salary ?? $salaryReview->payable_salary ?? 0,
                    'status' => 'salary_finalized',
                    'status_label' => 'Salary Finalized',
                    'status_color' => 'info',
                    'slip_id' => null,
                    'is_payroll_executed' => false,
                    'executed_date' => null
                ];
                $processedMonths[] = $monthKey;
                continue;
            }
            
            // PRIORITY 3: Check regular SalarySlip (salary calculated but not reviewed)
            $salarySlip = SalarySlip::where('employee_id', $employeeId)
                ->where('salary_month', Carbon::create($year, $month, 1)->format('Y-m'))
                ->first();
            
            if ($salarySlip) {
                $details = json_decode($salarySlip->salary_details_json, true);
                $history[] = [
                    'year' => $year,
                    'month' => $month,
                    'month_name' => $date->format('F Y'),
                    'final_payable' => $salarySlip->payable_salary ?? 0,
                    'status' => 'calculated',
                    'status_label' => 'Salary Calculated',
                    'status_color' => 'warning',
                    'slip_id' => null,
                    'is_payroll_executed' => false,
                    'executed_date' => null
                ];
                $processedMonths[] = $monthKey;
            }
        }
        
        // Sort by year and month descending (most recent first)
        usort($history, function($a, $b) {
            if ($a['year'] != $b['year']) {
                return $b['year'] - $a['year'];
            }
            return $b['month'] - $a['month'];
        });
        
        return $history;
    }
    
    /**
     * Get employee leave balances
     */
    private function getEmployeeLeaveBalances($employeeId)
    {
        $currentSession = $this->getCurrentAcademicSession();
        
        $leaveBalances = EmployeeLeaveBalance::where('employee_id', $employeeId)
            ->where('session_year', $currentSession)
            ->get();
        
        $balances = [];
        foreach ($leaveBalances as $balance) {
            $balances[] = [
                'leave_type' => $balance->leave_type,
                'total_allocated' => $balance->total_allocated,
                'used' => $balance->used ?? 0,
                'remaining' => ($balance->total_allocated - ($balance->used ?? 0)),
                'carry_forward' => $balance->carry_forward ?? 0
            ];
        }
        
        return $balances;
    }
    
    /**
     * Get yearly summary
     */
    private function getYearlySummary($employeeId, $year)
    {
        $salaryReviews = SalaryReview::where('employee_id', $employeeId)
            ->where('year', $year)
            ->where('review_status', 'finalized')
            ->get();
        
        $totalEarned = $salaryReviews->sum('final_payable_salary');
        $monthsFinalized = $salaryReviews->count();
        
        $attendanceReviews = AttendanceReview::where('employee_id', $employeeId)
            ->where('year', $year)
            ->get();
        
        $avgAttendance = $attendanceReviews->avg('attendance_percentage');
        
        return [
            'total_earned' => $totalEarned,
            'months_finalized' => $monthsFinalized,
            'avg_attendance' => round($avgAttendance, 2),
            'total_present' => $attendanceReviews->sum('present_days'),
            'total_absent' => $attendanceReviews->sum('absent_days'),
            'total_leaves' => $attendanceReviews->sum('leave_days'),
        ];
    }
    
    /**
     * Get current academic session
     */
    private function getCurrentAcademicSession()
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        
        if ($currentMonth >= 4) {
            return $currentYear . '-' . ($currentYear + 1);
        } else {
            return ($currentYear - 1) . '-' . $currentYear;
        }
    }

    private function getLeaveDeductionRules($context)
    {
        $deductionRules = [];
        
        $leaveDeductions = LeaveDeduction::forInstitute($context['institute_id'], $context['branch_id'])
            ->where('is_active', true)
            ->get();
        
        foreach ($leaveDeductions as $deduction) {
            $deductionRules[] = [
                'leave_type' => $deduction->leave_type,
                'approved_percentage' => floatval($deduction->approved_deduction_percentage),
                'unapproved_percentage' => floatval($deduction->unapproved_deduction_percentage),
                'leave_category' => $deduction->leave_category ?? 'full_day',
                'requires_doctor_certificate' => $deduction->requires_doctor_certificate ?? false,
                'max_consecutive_days' => $deduction->max_consecutive_days,
                'max_days_per_year' => $deduction->max_days_per_year,
            ];
        }
        
        // Sort by leave category and type
        usort($deductionRules, function($a, $b) {
            $order = ['short_leave' => 1, 'half_day' => 2, 'full_day' => 3];
            $catA = $order[$a['leave_category']] ?? 99;
            $catB = $order[$b['leave_category']] ?? 99;
            if ($catA == $catB) {
                return strcmp($a['leave_type'], $b['leave_type']);
            }
            return $catA - $catB;
        });
        
        return $deductionRules;
    }

    private function getPayrollExecutionRules($employee, $context)
    {
        $payrollRules = [];
        
        // Get payroll configuration for employee's department
        if ($employee && $employee->department_id) {
            $payrollConfig = PayrollExecution::where('department_id', $employee->department_id)
                ->where('institute_id', $context['institute_id'])
                ->when($context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                })
                ->first();
            
            if ($payrollConfig) {
                $payrollRules = [
                    'payroll_cycle' => $payrollConfig->payroll_cycle ?? 'monthly',
                    'cycle_days' => $payrollConfig->cycle_days ?? null,
                    'execution_day' => $payrollConfig->execution_day ?? null,
                    'financial_year' => $payrollConfig->financial_year ?? date('Y'),
                    'calculation_basis' => $payrollConfig->payroll_cycle === 'days' 
                        ? "Salary calculated based on {$payrollConfig->cycle_days}-day payroll cycle" 
                        : "Salary calculated based on calendar days of the month",
                ];
            }
        }
        
        // If no department-specific config, return default rules
        if (empty($payrollRules)) {
            $payrollRules = [
                'payroll_cycle' => 'monthly',
                'cycle_days' => null,
                'execution_day' => 1,
                'financial_year' => date('Y'),
                'calculation_basis' => 'Salary calculated based on calendar days of the month',
            ];
        }
        
        return $payrollRules;
    }

    private function getDeductionSummary($deductionRules)
    {
        $shortLeaves = array_filter($deductionRules, function($rule) {
            return $rule['leave_category'] === 'short_leave';
        });
        $halfDays = array_filter($deductionRules, function($rule) {
            return $rule['leave_category'] === 'half_day';
        });
        
        $summary = [];
        if (!empty($shortLeaves)) {
            $first = reset($shortLeaves);
            $summary[] = "Short Leave: {$first['approved_percentage']}%/{$first['unapproved_percentage']}%";
        }
        if (!empty($halfDays)) {
            $first = reset($halfDays);
            $summary[] = "Half Day: {$first['approved_percentage']}%/{$first['unapproved_percentage']}%";
        }
        
        return !empty($summary) ? implode(', ', $summary) : 'Deductions apply as per policy';
    }

    private function getCurrentMonthStatus($employee, $year, $month)
    {
        $journeyStatus = $this->getCurrentMonthJourneyStatus($employee, $year, $month);
        
        return [
            'attendance_status' => $journeyStatus['attendance_status'],
            'salary_status' => $journeyStatus['salary_status'],
            'slip_generated' => $journeyStatus['slip_generated'],
            'attendance_percentage' => $journeyStatus['attendance_percentage'],
            'present_days' => $journeyStatus['present_days'],
            'absent_days' => $journeyStatus['absent_days'],
            'leave_days' => $journeyStatus['leave_days'],
            'final_payable' => $journeyStatus['final_payable'],
            'slip_id' => $journeyStatus['slip_id'],
        ];
    }

    /**
     * Show employee's payroll policy
     */
    public function myPayrollPolicy()
    {
        $employee = $this->getLoggedInEmployee();
        
        if (!$employee) {
            return redirect()->route('login')->with('error', 'Employee record not found.');
        }
        
        $context = $this->getInstituteBranchContext();
        $financialYear = date('Y') . '-' . (date('Y') + 1);
        if (date('m') < 4) {
            $financialYear = (date('Y') - 1) . '-' . date('Y');
        }
        
        $policy = null;
        $allowances = null;
        $taxDeductions = null;
        $otherDeductions = null;
        $assignedVia = null; // Track how policy was assigned
        
        // ✅ PRIORITY 1: Check for Employee-specific policy (Individual Policy)
        $employeeSpecificPolicy = ProvidentFundPolicy::where('institute_id', $context['institute_id'])
            ->where('financial_year', $financialYear)
            ->where('payroll_type', 'employee')
            ->where('employee_id', $employee->employee_id)
            ->first();
        
        if ($employeeSpecificPolicy) {
            $policy = $employeeSpecificPolicy;
            $assignedVia = 'individual';
            
            // Get allowances, tax deductions, other deductions
            $allowances = PayrollPolicyAllowance::where('payroll_policy_id', $policy->payroll_policy_id)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            $taxDeductions = PayrollPolicyTaxDeduction::where('payroll_policy_id', $policy->payroll_policy_id)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            $otherDeductions = PayrollPolicyOtherDeduction::where('payroll_policy_id', $policy->payroll_policy_id)
                ->where('institute_id', $context['institute_id'])
                ->first();
        }
        
        // ✅ PRIORITY 2: If no individual policy, check Department policy
        if (!$policy && $employee->department_id) {
            $departmentPolicy = ProvidentFundPolicy::where('institute_id', $context['institute_id'])
                ->where('financial_year', $financialYear)
                ->where('payroll_type', 'department')
                ->where('department_id', $employee->department_id)
                ->first();
            
            if ($departmentPolicy) {
                $policy = $departmentPolicy;
                $assignedVia = 'department';
                
                // Get allowances, tax deductions, other deductions
                $allowances = PayrollPolicyAllowance::where('payroll_policy_id', $policy->payroll_policy_id)
                    ->where('institute_id', $context['institute_id'])
                    ->first();
                
                $taxDeductions = PayrollPolicyTaxDeduction::where('payroll_policy_id', $policy->payroll_policy_id)
                    ->where('institute_id', $context['institute_id'])
                    ->first();
                
                $otherDeductions = PayrollPolicyOtherDeduction::where('payroll_policy_id', $policy->payroll_policy_id)
                    ->where('institute_id', $context['institute_id'])
                    ->first();
            }
        }
        
        // Get deduction rules
        $deductionRules = $this->getLeaveDeductionRules($context);
        
        // Pass assigned_via to view to show how policy was assigned
        return view('instituteAdmin.EmployeeFiles.MyPayrollPolicy', compact(
            'policy',
            'allowances',
            'taxDeductions',
            'otherDeductions',
            'deductionRules',
            'financialYear',
            'employee',
            'assignedVia'
        ));
    }

    /**
     * Show employee's salary structure
     */
    public function mySalaryStructure()
    {
        $employee = $this->getLoggedInEmployee();
        
        if (!$employee) {
            return redirect()->route('login')->with('error', 'Employee record not found.');
        }
        
        $context = $this->getInstituteBranchContext();
        $financialYear = date('Y') . '-' . (date('Y') + 1);
        if (date('m') < 4) {
            $financialYear = (date('Y') - 1) . '-' . date('Y');
        }
        $department = Departments::where('department_id', $employee->department_id)
            ->where('institute_id', $context['institute_id'])
            ->where('status', 'active')
            ->first();
      
        // Get employee's active salary structure
        $salaryStructure = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
            ->where('financial_year', $financialYear)
            ->where('institute_id', $context['institute_id'])
            ->where('status', 'active')
            ->first();
        
        // If no active, try to find any
        if (!$salaryStructure) {
            $salaryStructure = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
                ->where('financial_year', $financialYear)
                ->where('institute_id', $context['institute_id'])
                ->first();
        }
        
        $allowances = null;
        $deductions = null;
        $salaryPreview = null;
        $bonuses = collect();
        $overtime = null;
        
        if ($salaryStructure) {
            // Get allowances
            $allowances = SalaryStructureAllowances::where('salary_structure_id', $salaryStructure->salary_structure_id)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            // Get deductions
            $deductions = SalaryStructureDeduction::where('salary_structure_id', $salaryStructure->salary_structure_id)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            // Get bonuses
            $bonuses = SalaryStructureBonus::where('salary_structure_id', $salaryStructure->salary_structure_id)
                ->where('institute_id', $context['institute_id'])
                ->get();
            
            // Get overtime
            $overtime = SalaryStructureOvertime::where('salary_structure_id', $salaryStructure->salary_structure_id)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            // Get salary preview
            $salaryPreview = SalaryPreview::where('salary_structure_id', $salaryStructure->salary_structure_id)
                ->where('institute_id', $context['institute_id'])
                ->first();
        }
        
        return view('instituteAdmin.EmployeeFiles.MySalaryStructure', compact(
            'salaryStructure',
            'department',
            'allowances',
            'deductions',
            'bonuses',
            'overtime',
            'salaryPreview',
            'financialYear',
            'employee'
        ));
    }

    /**
     * Show employee's salary slip archive for the academic year
     */
    public function mySalarySlips(Request $request)
    {
        $employee = $this->getLoggedInEmployee();
        
        if (!$employee) {
            return redirect()->route('login')->with('error', 'Employee record not found.');
        }
        
        $context = $this->getInstituteBranchContext();
        
        // Get academic year (April to March)
        $currentYear = date('Y');
        $currentMonth = date('m');
        
        if ($currentMonth >= 4) {
            $academicYear = $currentYear . '-' . ($currentYear + 1);
            $academicStartYear = $currentYear;
            $academicEndYear = $currentYear + 1;
        } else {
            $academicYear = ($currentYear - 1) . '-' . $currentYear;
            $academicStartYear = $currentYear - 1;
            $academicEndYear = $currentYear;
        }
        
        // Get selected academic year from request or default
        $selectedYear = $request->get('academic_year', $academicYear);
        
        // Parse selected year range
        $yearParts = explode('-', $selectedYear);
        $startYear = (int)$yearParts[0];
        $endYear = (int)$yearParts[1];
        
        // Get all finalized salary slips for the academic year (April to March)
        $salarySlips = FinalSalarySlip::where('employee_id', $employee->employee_id)
            ->where('institute_id', $context['institute_id'])
            ->whereBetween('year', [$startYear, $endYear])
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->groupBy('year');
        // Get available academic years for filter
        $availableYears = [];
        $firstYear = EmployeeDetails::where('employee_id', $employee->employee_id)
            ->value('doj');
        
        $joinYear = $firstYear ? Carbon::parse($firstYear)->year : date('Y') - 2;
        
        for ($year = $endYear; $year >= $joinYear; $year--) {
            $availableYears[] = $year . '-' . ($year + 1);
        }
        
        // Get department
        $department = Departments::where('department_id', $employee->department_id)
            ->where('institute_id', $context['institute_id'])
            ->pluck('department')
            ->first();

        // Calculate year summary
        $yearSummary = [
            'total_slips' => 0,
            'total_earned' => 0,
            'months_with_slips' => [],
            'pending_months' => []
        ];
        
        foreach ($salarySlips as $year => $slips) {
            $yearSummary['total_slips'] += $slips->count();
            foreach ($slips as $slip) {
                $yearSummary['total_earned'] += $slip->final_payable ?? 0;
                $yearSummary['months_with_slips'][] = $slip->year . '-' . $slip->month;
            }
        }
        
        // Get all months for display
        $allMonths = [];
        for ($m = 1; $m <= 12; $m++) {
            $allMonths[$m] = Carbon::create()->month($m)->format('F');
        }
        
        return view('instituteAdmin.EmployeeFiles.MySalarySlips', compact(
            'employee',
            'department',
            'salarySlips',
            'availableYears',
            'selectedYear',
            'startYear',
            'endYear',
            'yearSummary',
            'allMonths'
        ));
    }

    /**
     * Download single salary slip as PDF
     */
    public function downloadSalarySlip(Request $request)
    {
        $request->validate([
            'slip_id' => 'required|string',
        ]);
        
        $employee = $this->getLoggedInEmployee();
        
        if (!$employee) {
            return redirect()->route('login')->with('error', 'Employee not found.');
        }
        
        $context = $this->getInstituteBranchContext();
        
        $salarySlip = FinalSalarySlip::where('slip_id', $request->slip_id)
            ->where('employee_id', $employee->employee_id)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        if (!$salarySlip) {
            return response()->json(['success' => false, 'message' => 'Salary slip not found']);
        }
        
        // Get additional details
        $salaryReview = SalaryReview::where('employee_id', $employee->employee_id)
            ->where('year', $salarySlip->year)
            ->where('month', $salarySlip->month)
            ->first();
        
        // Generate PDF
        $pdf = PDF::loadView('pdf.salary_slip', [
            'slip' => $salarySlip,
            'employee' => $employee,
            'salaryReview' => $salaryReview,
            'monthName' => Carbon::create($salarySlip->year, $salarySlip->month, 1)->format('F Y')
        ]);
        
        $filename = 'Salary_Slip_' . $employee->employee_code . '_' . $salarySlip->year . '_' . str_pad($salarySlip->month, 2, '0', STR_PAD_LEFT) . '.pdf';
        
        return $pdf->download($filename);
    }
}