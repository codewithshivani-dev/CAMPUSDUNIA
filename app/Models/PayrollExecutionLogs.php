<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollExecutionLogs extends Model
{
    use HasFactory;
    
    protected $table = 'payroll_execution_logs';
    
    protected $fillable = [
        'payroll_execution_id',
        'department_id',
        'old_financial_year',
        'new_financial_year',
        'old_cycle',
        'new_cycle',
        'old_cycle_days',
        'new_cycle_days',
        'old_execution_day',
        'new_execution_day',
        'changed_by_name',
        'remark'
    ];
    
    public function payrollExecution()
    {
        return $this->belongsTo(PayrollExecution::class, 'payroll_execution_id', 'id');
    }
}
