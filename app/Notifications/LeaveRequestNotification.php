<?php
namespace App\Notifications;

use App\Models\EmployeeLeave;
use Illuminate\Notifications\Notification;

class LeaveRequestNotification extends BaseNotification
{
    protected $leave;
    protected $action;

    public function __construct(EmployeeLeave $leave, $action = 'applied')
    {
        $this->leave = $leave;
        $this->action = $action;
    }

    public function toDatabase($notifiable)
    {
        $employee = $this->leave->employee;
        $actionText = $this->action === 'applied' ? 'applied for' : 'updated';
        
        return [
            'type' => 'leave_request',
            'title' => 'Leave Request ' . ucfirst($this->action),
            'message' => "{$employee->name} has {$actionText} {$this->leave->leave_type} leave from " . 
                         date('d M Y', strtotime($this->leave->start_date)) . 
                         ($this->leave->end_date ? " to " . date('d M Y', strtotime($this->leave->end_date)) : ""),
            'employee_id' => $employee->employee_id,
            'employee_name' => $employee->name,
            'leave_id' => $this->leave->id,
            'leave_type' => $this->leave->leave_type,
            'start_date' => $this->leave->start_date,
            'end_date' => $this->leave->end_date,
            'total_days' => $this->leave->total_days,
            'department_id' => $employee->department_id,
            'department_name' => $employee->department->department ?? null,
            'institute_id' => $this->leave->institute_id,
            'branch_id' => $this->leave->branch_id,
            'time' => now()->toDateTimeString(),
            'action_url' => route('leaves.approvals', ['tab' => 'pending']),
            'icon' => 'calendar-check',
            'color' => 'warning'
        ];
    }
}