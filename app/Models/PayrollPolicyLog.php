<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollPolicyLog extends Model
{
    use HasFactory;

    protected $table = 'payroll_policy_logs';

    protected $fillable = [
        'payroll_policy_id',
        'institute_id',
        'branch_id',
        'payroll_type',
        'department_id',
        'employee_id',
        'financial_year',
        'action',
        'change_type',
        'previous_data',
        'current_data',
        'changed_by'
    ];

    protected $casts = [
        'previous_data' => 'array',
        'current_data' => 'array'
    ];

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'department_id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }
}
