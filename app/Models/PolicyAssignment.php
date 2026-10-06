<?php
// app/Models/PolicyAssignment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolicyAssignment extends Model

{
    protected $table = 'policy_assignments';
    protected $fillable = [
        'policy_id',
        'department_id',
        'employee_id',
        'exit_type',
        'assignment_type',
        'effective_date',
        'expiry_date',
        'notes',
        'assigned_by',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function policy()
    {
        return $this->belongsTo(EmployeeExitPolicy::class, 'policy_id');
    }

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'department_id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(EmployeeDetails::class, 'assigned_by');
    }
}