<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PayrollExecution;
use App\Models\SalaryReview;
use App\Models\FinalSalarySlip;
use App\Models\EmployeeDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessPayrollExecutions extends Command
{
    protected $signature = 'payroll:process-executions 
                            {--date= : Process for specific date (Y-m-d)}
                            {--department= : Process only specific department}
                            {--dry-run : Preview without saving}';

    protected $description = 'Process payroll executions based on department execution dates';

    public function handle()
    {
        $this->info('==========================================');
        $this->info('Payroll Execution Processor Started');
        $this->info('==========================================');
        
        $processDate = $this->option('date') 
            ? Carbon::parse($this->option('date')) 
            : Carbon::now();
        
        $specificDepartment = $this->option('department');
        $isDryRun = $this->option('dry-run');
        
        $this->info("Processing for date: " . $processDate->format('Y-m-d'));
        
        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No data will be saved');
        }
        
        // Get all active payroll executions that should run on this date
        $payrollExecutions = $this->getPayrollExecutionsForDate($processDate, $specificDepartment);
        
        if ($payrollExecutions->isEmpty()) {
            $this->warn('No payroll executions found for this date.');
            return 0;
        }
        
        $this->info("Found " . $payrollExecutions->count() . " departments to process");
        $this->newLine();
        
        $results = [
            'total_departments' => $payrollExecutions->count(),
            'total_employees' => 0,
            'slips_created' => 0,
            'slips_already_exist' => 0,
            'failed' => 0,
            'departments_processed' => []
        ];
        
        foreach ($payrollExecutions as $payrollConfig) {
            $departmentResult = $this->processDepartmentPayroll($payrollConfig, $processDate, $isDryRun);
            $results['departments_processed'][] = $departmentResult;
            $results['total_employees'] += $departmentResult['total_employees'];
            $results['slips_created'] += $departmentResult['slips_created'];
            $results['slips_already_exist'] += $departmentResult['slips_already_exist'];
            $results['failed'] += $departmentResult['failed'];
            
            $this->displayDepartmentResult($departmentResult);
        }
        
        // Summary
        $this->newLine();
        $this->info('==========================================');
        $this->info('PROCESSING SUMMARY');
        $this->info('==========================================');
        $this->info("Total Departments Processed: {$results['total_departments']}");
        $this->info("Total Employees Processed   : {$results['total_employees']}");
        $this->info("Salary Slips Created        : {$results['slips_created']}");
        $this->info("Already Exists              : {$results['slips_already_exist']}");
        $this->info("Failed                      : {$results['failed']}");
        $this->info('==========================================');
        
        // Log results
        Log::info('Payroll Execution Processed', [
            'date' => $processDate->format('Y-m-d'),
            'results' => $results
        ]);
        
        return 0;
    }
    
    /**
     * Get payroll executions that should run on the given date
     */
    private function getPayrollExecutionsForDate($processDate, $specificDepartment = null)
    {
        $currentDay = $processDate->day;
        $daysInMonth = $processDate->daysInMonth;
        
        $query = PayrollExecution::with('department')
            ->where('status', 'active')
            ->where(function($q) use ($currentDay, $daysInMonth) {
                // For departments with execution_day greater than month days, use last day
                $q->where('execution_day', $currentDay)
                  ->orWhere(function($q2) use ($currentDay, $daysInMonth) {
                      $q2->where('execution_day', '>', $daysInMonth)
                         ->whereRaw("? = ?", [$currentDay, $daysInMonth]);
                  });
            });
        
        if ($specificDepartment) {
            $query->where('department_id', $specificDepartment);
        }
        
        return $query->get();
    }
    
    /**
     * Process payroll for a single department
     */
    private function processDepartmentPayroll($payrollConfig, $processDate, $isDryRun)
    {
        $departmentName = $payrollConfig->department->department ?? 'Unknown Department';
        $executionDay = $payrollConfig->execution_day;
        $cycleDays = $payrollConfig->cycle_days;
        $payrollCycle = $payrollConfig->payroll_cycle;
        
        $this->info("Processing Department: {$departmentName}");
        $this->line("   Payroll Cycle: " . ($payrollCycle == 'days' ? "{$cycleDays} days" : "Monthly"));
        $this->line("   Execution Day: {$executionDay}");
        
        // Get all employees in this department with finalized salary reviews
        $employees = $this->getEmployeesForPayroll($payrollConfig->department_id, $processDate);
        
        if ($employees->isEmpty()) {
            $this->line("   No finalized salary reviews found for this department");
            return [
                'department_name' => $departmentName,
                'total_employees' => 0,
                'slips_created' => 0,
                'slips_already_exist' => 0,
                'failed' => 0,
                'details' => []
            ];
        }
        
        $this->line("   Found " . $employees->count() . " employees with finalized salaries");
        
        $departmentResult = [
            'department_name' => $departmentName,
            'total_employees' => $employees->count(),
            'slips_created' => 0,
            'slips_already_exist' => 0,
            'failed' => 0,
            'details' => []
        ];
        
        foreach ($employees as $employee) {
            $result = $this->createFinalSalarySlip($employee, $payrollConfig, $processDate, $isDryRun);
            
            if ($result['status'] == 'created') {
                $departmentResult['slips_created']++;
            } elseif ($result['status'] == 'exists') {
                $departmentResult['slips_already_exist']++;
            } elseif ($result['status'] == 'failed') {
                $departmentResult['failed']++;
            }
            
            $departmentResult['details'][] = $result;
            
            $icon = $this->getResultIcon($result['status']);
            $this->line("      {$icon} {$result['employee_name']}: {$result['message']}");
            if (isset($result['amount'])) {
                $this->line("          Amount: ₹" . number_format($result['amount'], 2));
            }
        }
        
        return $departmentResult;
    }
    
    /**
     * Get employees with finalized salary reviews for the month
     */
    private function getEmployeesForPayroll($departmentId, $processDate)
    {
        $year = $processDate->year;
        $month = $processDate->month;
        
        // Get all salary reviews that are finalized for this month and department
        $salaryReviews = SalaryReview::with('employee')
            ->where('year', $year)
            ->where('month', $month)
            ->where('review_status', 'finalized')
            ->whereHas('employee', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            })
            ->get();
        
        return $salaryReviews;
    }
    
    /**
     * Create final salary slip for an employee
     */
    private function createFinalSalarySlip($salaryReview, $payrollConfig, $processDate, $isDryRun)
    {
        $employee = $salaryReview->employee;
        
        if (!$employee) {
            return [
                'status' => 'failed',
                'employee_name' => 'Unknown',
                'message' => 'Employee not found'
            ];
        }
        
        $salaryMonth = $processDate->format('Y-m');
        
        // Check if final slip already exists
        $existingSlip = FinalSalarySlip::where('employee_id', $employee->employee_id)
            ->where('salary_month', $salaryMonth)
            ->first();
        
        if ($existingSlip) {
            return [
                'status' => 'exists',
                'employee_name' => $employee->name,
                'amount' => $existingSlip->final_payable,
                'message' => 'Final salary slip already exists'
            ];
        }
        
        // Generate unique slip ID
        $slipId = $this->generateSlipId($salaryReview, $salaryMonth);
        
        // Prepare slip data
        $slipData = $this->prepareFinalSlipData($salaryReview, $employee, $payrollConfig, $salaryMonth, $slipId);
        
        if ($isDryRun) {
            return [
                'status' => 'created',
                'employee_name' => $employee->name,
                'amount' => $salaryReview->final_payable_salary,
                'message' => 'DRY RUN: Would create salary slip'
            ];
        }
        
        try {
            FinalSalarySlip::create($slipData);
            
            // Update payroll execution last run
            $payrollConfig->update(['last_run_at' => now()]);
            
            return [
                'status' => 'created',
                'employee_name' => $employee->name,
                'amount' => $salaryReview->final_payable_salary,
                'message' => 'Salary slip created successfully'
            ];
        } catch (\Exception $e) {
            Log::error('Failed to create final salary slip', [
                'employee_id' => $employee->employee_id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'status' => 'failed',
                'employee_name' => $employee->name,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate unique slip ID
     */
    private function generateSlipId($salaryReview, $salaryMonth)
    {
        $date = Carbon::parse($salaryMonth);
        $yearMonth = $date->format('Ym');
        $employeeCode = substr($salaryReview->employee_id, -6);
        $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return "FINAL-{$yearMonth}-{$employeeCode}-{$random}";
    }
    
    /**
     * Prepare final salary slip data
     */
    private function prepareFinalSlipData($salaryReview, $employee, $payrollConfig, $salaryMonth, $slipId)
    {
        $finalizationDetails = $salaryReview->finalization_details ?? [];
        
        // Calculate pay date based on execution day
        $payDate = $this->calculatePayDate($salaryReview->year, $salaryReview->month, $payrollConfig);
        
        return [
            'slip_id' => $slipId,
            'employee_id' => $employee->employee_id,
            'employee_name' => $employee->name,
            'employee_code' => $employee->employee_code,
            'salary_review_id' => $salaryReview->id,
            'year' => $salaryReview->year,
            'month' => $salaryReview->month,
            'salary_month' => $salaryMonth,
            
            // Salary Amounts
            'basic_salary' => $salaryReview->basic_salary ?? 0,
            'gross_salary' => $salaryReview->gross_salary ?? 0,
            'net_salary' => $salaryReview->monthly_net_salary ?? 0,
            'final_payable' => $salaryReview->final_payable_salary ?? 0,
            
            // Deductions
            'standard_deductions' => $finalizationDetails['standard_deductions_breakdown'] ?? [],
            'attendance_deductions' => $finalizationDetails['attendance_deductions_breakdown'] ?? [],
            'other_deductions' => $salaryReview->other_deductions_details ?? [],
            'total_deductions' => $salaryReview->total_deductions ?? 0,
            
            // Earnings
            'earnings_breakdown' => $salaryReview->earnings_breakdown ?? [],
            
            // Attendance Summary
            'attendance_summary' => $salaryReview->attendance_summary ?? [],
            
            // Leave Breakdown
            'leave_breakdown' => $salaryReview->leave_breakdown ?? [],
            
            // Bank Details
            'bank_name' => $employee->bank_name ?? null,
            'account_number' => $employee->bank_account_number ? 'XXXX' . substr($employee->bank_account_number, -4) : null,
            'ifsc_code' => $employee->ifsc_code ?? null,
            'pan_number' => $employee->pan_number ?? null,
            
            // Payroll Execution Info
            'additional_details' => [
                'payroll_cycle' => $payrollConfig->payroll_cycle,
                'cycle_days' => $payrollConfig->cycle_days,
                'execution_day' => $payrollConfig->execution_day,
                'pay_date' => $payDate,
                'financial_year' => $payrollConfig->financial_year,
                'salary_review_finalized_at' => $salaryReview->finalized_at,
                'salary_review_finalized_by' => $salaryReview->finalized_by,
                'calculation_note' => $finalizationDetails['calculation_formula'] ?? null,
            ],
            
            // Generation Info
            'generation_type' => 'cron',
            'generated_at' => now(),
            'generated_by' => 'System Cron',
            
            // Institute/Branch
            'institute_id' => $salaryReview->institute_id,
            'branch_id' => $salaryReview->branch_id,
        ];
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
        
        if ($executionDay > $payDate->daysInMonth) {
            $payDate = Carbon::create($year, $month, $payDate->daysInMonth);
        }
        
        return $payDate->format('d M Y');
    }
    
    /**
     * Display department result
     */
    private function displayDepartmentResult($result)
    {
        $this->newLine();
        $this->line("   ┌─────────────────────────────────────────");
        $this->line("   │ Department: {$result['department_name']}");
        $this->line("   │ Employees: {$result['total_employees']}");
        $this->line("   │ Created: {$result['slips_created']}");
        $this->line("   │ Already Exists: {$result['slips_already_exist']}");
        $this->line("   │ Failed: {$result['failed']}");
        $this->line("   └─────────────────────────────────────────");
    }
    
    private function getResultIcon($status)
    {
        switch ($status) {
            case 'created': return '✓';
            case 'exists': return '○';
            case 'failed': return '✗';
            default: return '•';
        }
    }
}