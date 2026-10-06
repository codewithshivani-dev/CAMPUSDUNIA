<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\SalaryReview;
use App\Models\FinalSalarySlip;
use App\Models\EmployeeDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GenerateFinalSalarySlips extends Command
{
    protected $signature = 'payroll:generate-final-slips 
                            {--month= : Month (1-12)}
                            {--year= : Year}
                            {--employee= : Specific employee ID}
                            {--force : Force regenerate even if slip exists}
                            {--dry-run : Preview without saving}';

    protected $description = 'Generate final salary slips from finalized salary reviews';

    public function handle()
    {
        $this->info('==========================================');
        $this->info('Final Salary Slip Generation Started');
        $this->info('==========================================');
        
        $year = $this->option('year') ?? Carbon::now()->year;
        $month = $this->option('month') ?? Carbon::now()->month;
        $employeeId = $this->option('employee');
        $forceRegenerate = $this->option('force');
        $isDryRun = $this->option('dry-run');
        
        $monthName = Carbon::create($year, $month, 1)->format('F Y');
        $salaryMonth = Carbon::create($year, $month, 1)->format('Y-m');
        
        $this->info("Processing for: {$monthName}");
        
        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No data will be saved');
        }
        if ($forceRegenerate) {
            $this->warn('FORCE MODE - Will regenerate existing slips');
        }
        
        // Get finalized salary reviews
        $query = SalaryReview::with('employee')
            ->where('year', $year)
            ->where('month', $month)
            ->where('review_status', 'finalized');
        
        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }
        
        $salaryReviews = $query->get();
        
        if ($salaryReviews->isEmpty()) {
            $this->warn("No finalized salary reviews found for {$monthName}");
            return 0;
        }
        
        $this->info("Found " . $salaryReviews->count() . " finalized salary reviews");
        $this->newLine();
        
        $results = [
            'total' => $salaryReviews->count(),
            'created' => 0,
            'regenerated' => 0,
            'skipped' => 0,
            'failed' => 0,
        ];
        
        foreach ($salaryReviews as $review) {
            $result = $this->processFinalSalarySlip($review, $salaryMonth, $forceRegenerate, $isDryRun);
            
            if ($result['status'] == 'created') {
                $results['created']++;
            } elseif ($result['status'] == 'regenerated') {
                $results['regenerated']++;
            } elseif ($result['status'] == 'skipped') {
                $results['skipped']++;
            } elseif ($result['status'] == 'failed') {
                $results['failed']++;
            }
            
            $icon = $this->getStatusIcon($result['status']);
            $this->line("  {$icon} {$result['employee_name']}: {$result['message']}");
            if (isset($result['amount'])) {
                $this->line("      Amount: ₹" . number_format($result['amount'], 2));
            }
        }
        
        // Summary
        $this->newLine();
        $this->info('==========================================');
        $this->info('Generation Summary');
        $this->info('==========================================');
        $this->info("Total Processed     : {$results['total']}");
        $this->info("Newly Created       : {$results['created']}");
        $this->info("Regenerated         : {$results['regenerated']}");
        $this->info("Skipped (Exists)    : {$results['skipped']}");
        $this->info("Failed              : {$results['failed']}");
        $this->info('==========================================');
        
        Log::info('Final Salary Slip Generation', [
            'month' => $salaryMonth,
            'results' => $results
        ]);
        
        return 0;
    }
    
    private function getStatusIcon($status)
    {
        switch ($status) {
            case 'created': return '✓';
            case 'regenerated': return '⟳';
            case 'skipped': return '○';
            case 'failed': return '✗';
            default: return '•';
        }
    }
    
    private function processFinalSalarySlip($salaryReview, $salaryMonth, $forceRegenerate, $isDryRun)
    {
        $employee = $salaryReview->employee;
        
        if (!$employee) {
            return [
                'status' => 'failed',
                'employee_name' => 'Unknown',
                'message' => 'Employee not found'
            ];
        }
        
        // Check if final salary slip already exists
        $existingSlip = FinalSalarySlip::where('employee_id', $employee->employee_id)
            ->where('salary_month', $salaryMonth)
            ->first();
        
        if ($existingSlip && !$forceRegenerate) {
            return [
                'status' => 'skipped',
                'employee_name' => $employee->name,
                'amount' => $existingSlip->final_payable,
                'message' => 'Final slip already exists (use --force to regenerate)'
            ];
        }
        
        // Generate unique slip ID
        $slipId = $this->generateSlipId($salaryReview, $salaryMonth);
        
        // Prepare final salary slip data
        $slipData = $this->prepareFinalSlipData($salaryReview, $employee, $salaryMonth, $slipId);
        
        if ($isDryRun) {
            return [
                'status' => 'created',
                'employee_name' => $employee->name,
                'amount' => $salaryReview->final_payable_salary,
                'message' => 'DRY RUN: Would create final salary slip'
            ];
        }
        
        try {
            if ($existingSlip && $forceRegenerate) {
                $existingSlip->update($slipData);
                $status = 'regenerated';
                $message = 'Final salary slip regenerated';
            } else {
                FinalSalarySlip::create($slipData);
                $status = 'created';
                $message = 'Final salary slip created';
            }
            
            return [
                'status' => $status,
                'employee_name' => $employee->name,
                'amount' => $salaryReview->final_payable_salary,
                'message' => $message
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'employee_name' => $employee->name,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    private function generateSlipId($salaryReview, $salaryMonth)
    {
        $date = Carbon::parse($salaryMonth);
        $yearMonth = $date->format('Ym');
        $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return "FINAL-{$yearMonth}-{$random}";
    }
    
    private function prepareFinalSlipData($salaryReview, $employee, $salaryMonth, $slipId)
    {
        // Get details from salary review
        $finalizationDetails = $salaryReview->finalization_details ?? [];
        
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
            
            // Generation Info
            'generation_type' => 'cron',
            'generated_at' => now(),
            'generated_by' => 'System Cron',
            
            // Institute/Branch
            'institute_id' => $salaryReview->institute_id,
            'branch_id' => $salaryReview->branch_id,
            
            // Additional Details
            'additional_details' => [
                'salary_review_finalized_at' => $salaryReview->finalized_at,
                'salary_review_finalized_by' => $salaryReview->finalized_by,
                'calculation_note' => $finalizationDetails['calculation_formula'] ?? null,
            ]
        ];
    }
}