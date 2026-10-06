<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    
    protected $employee;
    protected $leaveDetails;
    protected $assignmentMethod;
    protected $sessionYear;
    
    public function __construct($employee, $leaveDetails, $assignmentMethod, $sessionYear)
    {
        $this->employee = $employee;
        $this->leaveDetails = $leaveDetails;
        $this->assignmentMethod = $assignmentMethod;
        $this->sessionYear = $sessionYear;
    }
    
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }
    
    public function toMail($notifiable)
    {
        $totalDays = collect($this->leaveDetails)->sum('raw_days');
        
        return (new MailMessage)
            ->subject('Leave Quota Assigned')
            ->greeting('Dear ' . $this->employee->name . ',')
            ->line('Your leave quota for the session ' . $this->sessionYear . ' has been assigned.')
            ->line('Total allocated: ' . $totalDays . ' days')
            ->action('View Leave Balance', url('/employee/leave-summary'))
            ->line('Thank you for using our application!');
    }
    
    public function toArray($notifiable)
    {
        return [
            'institute_id' => $this->employee->institute_id ?? null,
            'branch_id' => $this->employee->branch_id ?? null,
            'employee_id' => $this->employee->employee_id,
            'employee_name' => $this->employee->name,
            'leave_details' => $this->leaveDetails,
            'total_days' => collect($this->leaveDetails)->sum('raw_days'),
            'session_year' => $this->sessionYear,
            'assignment_method' => $this->assignmentMethod,
            'message' => 'Leave quota assigned for ' . $this->sessionYear
        ];
    }
}