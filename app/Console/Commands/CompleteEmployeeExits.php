<?php
// app/Console/Commands/CompleteEmployeeExits.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmployeeExit;
use App\Services\EmployeeExitService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CompleteEmployeeExits extends Command
{
    protected $signature = 'employee:complete-exits';
    protected $description = 'Complete employee exits when notice period ends';

    protected $exitService;

    public function __construct(EmployeeExitService $exitService)
    {
        parent::__construct();
        $this->exitService = $exitService;
    }

    public function handle()
    {
        $this->info('Checking for employee exits to complete...');

        // Find exits where notice period has ended
        $exits = EmployeeExit::where('exit_status', 'notice_period')
            ->where('notice_end_date', '<=', Carbon::now())
            ->get();

        if ($exits->isEmpty()) {
            $this->info('No exits to complete.');
            return 0;
        }

        $completed = 0;
        $errors = 0;

        foreach ($exits as $exit) {
            try {
                $this->exitService->completeExit($exit->id, 1); // System user
                $completed++;
                
                $this->info("Exit completed for employee: {$exit->employee->employee_code}");
                
            } catch (\Exception $e) {
                $errors++;
                
                Log::error('Failed to auto-complete exit', [
                    'exit_id' => $exit->id,
                    'employee_id' => $exit->employee_id,
                    'error' => $e->getMessage(),
                ]);
                
                $this->error("Failed to complete exit ID {$exit->id}: " . $e->getMessage());
            }
        }

        $this->info("Completed: {$completed}, Errors: {$errors}");
        
        return 0;
    }
}