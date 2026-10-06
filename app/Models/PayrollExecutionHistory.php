<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PayrollExecutionHistory extends Model
{
    use HasFactory;
    
    protected $table = 'payroll_execution_histories';
    protected $guarded = [];
    
    protected $casts = [
        'planned_execution_date' => 'date',
        'actual_execution_date' => 'date',
        'execution_summary' => 'array',
        'failed_employees' => 'array',
    ];
    
    // Relationships
    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id', 'fincap_merchant_id');
    }
    
    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'department_id');
    }
    
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }
    
    public function executedBy()
    {
        return $this->belongsTo(User::class, 'executed_by', 'id');
    }
    
    // Accessors
    public function getFormattedPlannedDateAttribute()
    {
        return $this->planned_execution_date ? Carbon::parse($this->planned_execution_date)->format('d M Y') : 'N/A';
    }
    
    public function getFormattedActualDateAttribute()
    {
        return $this->actual_execution_date ? Carbon::parse($this->actual_execution_date)->format('d M Y') : 'N/A';
    }
    
    public function getExecutionPeriodAttribute()
    {
        return Carbon::create($this->year, $this->month, 1)->format('F Y');
    }
    
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
            'processing' => '<span class="badge bg-info">Processing</span>',
            'completed' => '<span class="badge bg-success">Completed</span>',
            'failed' => '<span class="badge bg-danger">Failed</span>',
            'partial' => '<span class="badge bg-secondary">Partial</span>',
        ];
        
        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
}