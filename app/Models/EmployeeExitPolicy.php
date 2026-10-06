<?php
// app/Models/EmployeeExitPolicy.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeExitPolicy extends Model
{
    protected $table = 'employee_exit_policies';

    protected $fillable = [
        'institute_id',
        'branch_id',
        'policy_name',
        'policy_code',
        'exit_type', 
        'description',
        'terms_conditions',
        'is_active',
        'exit_interview_required',
        'interview_days',
        'fnf_required',
        'fnf_processing_days',
        'fnf_settlement_type',
        'fnf_items',
        'kt_required',
        'kt_days',
        'kt_requirements',
        'additional_requirements',
        'clearance_workflow',
        'clearance_days',
        'default_notice_period',
        'employment_notice_periods',
        'employment_custom_days',
    ];

    protected $casts = [
        'fnf_items' => 'array',
        'kt_requirements' => 'array',
        'additional_requirements' => 'array',
        'employment_notice_periods' => 'array',
        'employment_custom_days' => 'array',
        'is_active' => 'boolean',
        'exit_interview_required' => 'boolean',
        'fnf_required' => 'boolean',
        'kt_required' => 'boolean',
    ];

    public function assignments()
    {
        return $this->hasMany(PolicyAssignment::class, 'policy_id');
    }

    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id' , 'institute_id');
    }
    
    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'department_id');
    }
    
     public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }
    
    /**
     * Get assignments for a specific exit type
     */
    public function assignmentsForExitType($exitType)
    {
        return $this->assignments()->where('exit_type', $exitType);
    }
}