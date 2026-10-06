<?php

namespace App\Notifications;

use App\Models\EmployeeDetails;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class EmployeeProbationCompleteNotification extends BaseNotification
{
    protected $employee;
    protected $status;
    protected $daysRemaining;
    protected $endDate;

    public function __construct(EmployeeDetails $employee, $status, $daysRemaining = null)
    {
        $this->employee = $employee;
        $this->status = $status;
        $this->daysRemaining = $daysRemaining;
        $this->endDate = $employee->getProbationEndDateAttribute();
    }

    public function toDatabase($notifiable)
    {
        $title = '';
        $message = '';

        switch ($this->status) {
            case 'one_day_remaining':
                $title = '📅 Probation Ending Tomorrow';
                $message = "Dear {$this->employee->name}, your probation period ends tomorrow. Please prepare for the transition.";
                break;
            case 'completes_today':
                $title = '🎉 Probation Completes Today';
                $message = "Dear {$this->employee->name}, congratulations! Your probation period completes today. Your manager will process your promotion shortly.";
                break;
            case 'overdue':
                $title = '⚠️ Probation Period Overdue';
                $message = "Dear {$this->employee->name}, your probation period was completed on {$this->endDate->format('d-m-Y')} but is yet to be confirmed. Please follow up with your manager/HR for the promotion process.";
                break;
            default:
                $title = 'Probation Status Update';
                $message = "Dear {$this->employee->name}, there is an update regarding your probation status. Please contact HR for more details.";
        }

        return [
            'type' => 'employee_probation_reminder',
            'institute_id' => $this->employee->institute_id,
            'branch_id'    => $this->employee->branch_id,
            'title' => $title,
            'message' => $message,
            'employee_id' => $this->employee->employee_id,
            'employee_code' => $this->employee->employee_code,
            'employee_name' => $this->employee->name,
            'probation_end_date' => $this->endDate ? $this->endDate->format('Y-m-d') : null,
            'days_remaining' => $this->daysRemaining,
            'status' => $this->status,
            'time' => now()->toDateTimeString(),
           'icon' => $this->status === 'overdue'
                ? 'exclamation-triangle'
                : 'calendar-check',

            'color' => $this->status === 'overdue'
                ? 'danger'
                : 'success',
        ];
    }

    public function toMail($notifiable)
{
    $subject = '';
    $message = '';
    $actionText = 'View Your Profile';
    
    // Define variables first
    $departmentName = $this->employee->department_name ?? 'N/A';
    $designation = $this->employee->designation ?? 'N/A';
    $doj = $this->employee->doj ? \Carbon\Carbon::parse($this->employee->doj)->format('d-m-Y') : 'N/A';
    $endDate = $this->endDate ? $this->endDate->format('d-m-Y') : 'N/A';

    switch ($this->status) {
        case 'one_day_remaining':
            $subject = '📅 Your Probation Period Ends Tomorrow';
            $message = "Your probation period ends tomorrow. Please ensure all your tasks are completed and prepare for the transition to a full-time employee.";
            break;
        case 'completes_today':
            $subject = '🎉 Congratulations! Your Probation Completes Today';
            $message = "Congratulations! Your probation period completes today. Your manager has been notified and will process your promotion shortly.";
            break;
        case 'overdue':
            $subject = '⚠️ Probation Period Overdue - Action Required';
            $message = "Your probation period was completed on {$endDate} but is yet to be confirmed. Please contact your manager or HR to follow up on the promotion process.";
            $actionText = 'Contact HR';
            break;
        default:
            $subject = 'Probation Status Update';
            $message = "There is an update regarding your probation status. Please contact HR for more details.";
    }

    return (new \Illuminate\Notifications\Messages\MailMessage)
        ->subject($subject)
        ->greeting("Dear {$this->employee->name},")
        ->line($message)
        ->line("")
        ->line("**Your Details:**")
        ->line("- Employee Code: {$this->employee->employee_code}")
        ->line("- Department: {$departmentName}")  // ✅ Use variable
        ->line("- Designation: {$designation}")     // ✅ Use variable
        ->line("- Date of Joining: {$doj}")          // ✅ Use variable
        ->line("- Probation End Date: {$endDate}")   // ✅ Use variable
        ->line("")
        ->action($actionText, url('/employee-details/' . $this->employee->id))
        ->line('Thank you for your patience!');
}

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}