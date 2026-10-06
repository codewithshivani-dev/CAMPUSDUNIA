<?php

namespace App\Notifications;

use App\Models\EmployeeDetails;
use Illuminate\Notifications\Notification;

class EmployeeOnboardedNotification extends BaseNotification
{
    protected $employee;
    protected $action;

    public function __construct(EmployeeDetails $employee, $action = 'added')
    {
        $this->employee = $employee;
        $this->action = $action;
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'employee_onboarding',
            'title' => 'New Employee Onboarded',
            'message' => "New employee {$this->employee->name} (Code: {$this->employee->employee_code}) has been {$this->action} as {$this->employee->assigned_role}",
            'employee_id' => $this->employee->employee_id,
            'employee_code' => $this->employee->employee_code,
            'employee_name' => $this->employee->name,
            'department_id' => $this->employee->department_id,
            'department_category_id' => $this->employee->department_category_id,
            'role' => $this->employee->assigned_role,
            'institute_id' => $this->employee->institute_id,
            'branch_id' => $this->employee->branch_id,
            'time' => now()->toDateTimeString(),
            'action_url' => route('employees.index', $this->employee->employee_id),
            'icon' => 'user-plus',
            'color' => 'green'
        ];
    }
}