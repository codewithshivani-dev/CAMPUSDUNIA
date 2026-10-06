<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollExecution extends Model
{
    use HasFactory;
    
    protected $table = 'payroll_executions';
    
    protected $fillable = [
        'execution_id',
        'institute_id',
        'branch_id',
        'employee_id',
        'department_id',
        'financial_year',
        'payroll_cycle',
        'execution_day',        
        'cycle_days',
        'month',
        'year',
        'execution_date',
        'attendance_review_id',
        'present_days',
        'absent_days',
        'leave_days',
        'basic_salary',
        'allowances',
        'deductions',
        'gross_salary',
        'net_salary',
        'status',
        'processed_by',
        'notes'
    ];
    
    protected $casts = [
        'allowances' => 'array',
        'deductions' => 'array',
        'execution_date' => 'datetime'
    ];
    
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }
    
    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'department_id');
    }
    
    public function attendanceReview()
    {
        return $this->belongsTo(AttendanceReview::class);
    }
    
    public function logs()
    {
        return $this->hasMany(PayrollExecutionLogs::class, 'payroll_execution_id', 'id');
    }
}