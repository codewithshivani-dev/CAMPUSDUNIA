<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmployeeDetails;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CheckProbationStatus extends Command
{
    protected $signature = 'probation:check {--debug : Show debug information}';
    protected $description = 'Check probation status and send notifications';

    public function handle()
{
    $isDebug = $this->option('debug');
    $today = Carbon::now()->startOfDay();
    
    $employees = EmployeeDetails::where('employment_type', 'Probation-Period')
        ->whereNotNull('doj')
        ->whereNotNull('probation_days')
        ->get();
    
    $this->info("Checking probation status for " . $employees->count() . " employees");
    
    $adminNotificationsSent = 0;
    $employeeNotificationsSent = 0;
    $errors = [];
    
    foreach ($employees as $employee) {
        $endDate = $employee->getProbationEndDateAttribute();
        if (!$endDate) {
            if ($isDebug) {
                $this->warn("No end date for: {$employee->name}");
            }
            continue;
        }
        
        $diffInDays = $today->diffInDays($endDate, false);
        
        $status = null;
        $daysDelta = abs($diffInDays);
        
        if ($diffInDays == 1) {
            $status = 'one_day_remaining';
        } elseif ($diffInDays == 0) {
            $status = 'completes_today';
        } elseif ($diffInDays < 0) {
            $status = 'overdue';
        }
        
        if ($status) {
            if ($isDebug) {
                $this->info("Processing: {$employee->name} - Status: {$status}");
            }
            
            // ✅ Send to admins (ALL STATUSES)
            try {
                $employee->sendProbationReminderToAdmins();
                $adminNotificationsSent++;
                if ($isDebug) {
                    $this->info("✓ Admin notification sent for: {$employee->name}");
                }
            } catch (\Exception $e) {
                $errors[] = "Admin notification failed for {$employee->name}: " . $e->getMessage();
            }
            
            // ✅ Send to employees (ALL STATUSES - INCLUDING OVERDUE)
            try {
                $result = $employee->sendProbationReminderToEmployee();
                if ($result) {
                    $employeeNotificationsSent++;
                    if ($isDebug) {
                        $this->info("✓ Employee notification sent for: {$employee->name}");
                    }
                } else {
                    if ($isDebug) {
                        $this->warn("✗ Employee notification FAILED for: {$employee->name}");
                    }
                    $errors[] = "Employee notification failed for {$employee->name} - No user linked or email not found";
                }
            } catch (\Exception $e) {
                $errors[] = "Employee notification failed for {$employee->name}: " . $e->getMessage();
            }
        } else {
            if ($isDebug) {
                $this->info("No action needed for: {$employee->name} (Status: active)");
            }
        }
    }
    
    $this->info("=== SUMMARY ===");
    $this->info("Total admin notifications sent: {$adminNotificationsSent}");
    $this->info("Total employee notifications sent: {$employeeNotificationsSent}");
    
    if (!empty($errors)) {
        $this->warn("Errors encountered:");
        foreach ($errors as $error) {
            $this->warn("- {$error}");
        }
    }
    
    Log::info("Probation check completed", [
        'total_employees' => $employees->count(),
        'admin_notifications' => $adminNotificationsSent,
        'employee_notifications' => $employeeNotificationsSent,
        'errors' => $errors
    ]);
    
    return Command::SUCCESS;
}
}