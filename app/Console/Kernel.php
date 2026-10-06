<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    
    protected $commands = [
        \App\Console\Commands\CheckProbationStatus::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('exams:update-status')->everyFiveMinutes();
        // Run daily at 9:00 AM
        $schedule->command('probation:check')->dailyAt('09:00');
        // // Run every hour to check for departments with payroll execution today
        // $schedule->command('payroll:process-executions')
        //     ->hourly()
        //     ->appendOutputTo(storage_path('logs/payroll-executions.log'));

        // // Also run daily at midnight to catch any missed executions
        // $schedule->command('payroll:process-executions')
        //     ->dailyAt('00:00')
        //     ->appendOutputTo(storage_path('logs/payroll-executions.log'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
