<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeProbationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'promotion_id',
        'employee_id',
        'employee_code',
        'employee_name',
        'doj',
        'probation_days',
        'probation_start_date',
        'probation_end_date',
        'employment_type_before',
        'employment_type_after',
        'promotion_date',
        'promoted_by',
        'promoted_by_user_id',
        'promotion_type',
        'department_before',
        'designation_before',
        'additional_data'
    ];

    protected $casts = [
        'additional_data' => 'array',
        'doj' => 'date',
        'probation_start_date' => 'date',
        'probation_end_date' => 'date',
        'promotion_date' => 'date',
    ];

    /**
     * Get the employee that owns the log.
     */
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id');
    }

    /**
     * Get the user who promoted the employee.
     */
    public function promotedByUser()
    {
        return $this->belongsTo(User::class, 'promoted_by_user_id');
    }
}