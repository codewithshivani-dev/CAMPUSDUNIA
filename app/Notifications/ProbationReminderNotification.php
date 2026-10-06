<?php

namespace App\Notifications;

use App\Models\EmployeeDetails;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class ProbationReminderNotification extends BaseNotification
{
    protected $employee;
    protected $status;
    protected $daysRemaining;
    protected $endDate;

    public function __construct(EmployeeDetails $employee, $status, $daysRemaining = null)
    {
        $this->employee = $employee;
        $this->status = $status; // 'one_day_remaining', 'completes_today', 'overdue'
        $this->daysRemaining = $daysRemaining;
        $this->endDate = $employee->getProbationEndDateAttribute();
    }

    public function toDatabase($notifiable)
    {
        $message = '';
        $title = '';
        $type = 'probation_reminder';

        switch ($this->status) {
            case 'one_day_remaining':
                $title = 'Probation Ending Tomorrow';
                $message = "Employee {$this->employee->name}'s probation period ends tomorrow. Please take necessary action.";
                $type = 'probation_one_day_remaining';
                break;
            case 'completes_today':
                $title = 'Probation Completes Today';
                $message = "Employee {$this->employee->name}'s probation period completes today. Please process the promotion.";
                $type = 'probation_completes_today';
                break;
            case 'overdue':
                $title = '⚠️ Probation Overdue';
                $message = "Employee {$this->employee->name}'s probation period is overdue by {$this->daysRemaining} day(s). Please promote them to full-time.";
                $type = 'probation_overdue';
                break;
        }

        return [
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'employee_id' => $this->employee->employee_id,
            'employee_code' => $this->employee->employee_code,
            'employee_name' => $this->employee->name,
            'probation_end_date' => $this->endDate ? $this->endDate->format('Y-m-d') : null,
            'days_remaining' => $this->daysRemaining,
            'institute_id' => $this->employee->institute_id,
            'branch_id' => $this->employee->branch_id,
            'time' => now()->toDateTimeString(),
            'action_url' => route('employees.index'),
            'icon' => $this->status === 'overdue' ? 'exclamation-triangle' : 'calendar-check',
            'color' => $this->status === 'overdue' ? 'red' : 'yellow'
        ];
    }

    public function toMail($notifiable)
    {
        $subject = '';
        $view = 'emails.probation-reminder';

        switch ($this->status) {
            case 'one_day_remaining':
                $subject = 'Probation Period Ending Tomorrow';
                break;
            case 'completes_today':
                $subject = 'Probation Period Completes Today';
                break;
            case 'overdue':
                $subject = '⚠️ Probation Period Overdue';
                break;
        }

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject($subject)
            ->markdown($view, [
                'employee' => $this->employee,
                'status' => $this->status,
                'daysRemaining' => $this->daysRemaining,
                'endDate' => $this->endDate,
            ]);
    }

    public function toSms($notifiable)
    {
        $message = '';

        switch ($this->status) {
            case 'one_day_remaining':
                $message = "Dear Admin, {$this->employee->name}'s probation ends tomorrow. Please take action.";
                break;
            case 'completes_today':
                $message = "Dear Admin, {$this->employee->name}'s probation completes today. Please promote.";
                break;
            case 'overdue':
                $message = "⚠️ Alert: {$this->employee->name}'s probation is overdue by {$this->daysRemaining} day(s).";
                break;
        }

        return $message;
    }
}