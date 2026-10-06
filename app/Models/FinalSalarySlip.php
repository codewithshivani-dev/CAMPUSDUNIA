<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FinalSalarySlip extends Model
{
    protected $table = 'final_salary_slips';
    
    protected $fillable = [
        'slip_id',
        'employee_id',
        'employee_name',
        'employee_code',
        'salary_review_id',
        'year',
        'month',
        'salary_month',
        'basic_salary',
        'gross_salary',
        'net_salary',
        'final_payable',
        'standard_deductions',
        'attendance_deductions',
        'other_deductions',
        'total_deductions',
        'earnings_breakdown',
        'attendance_summary',
        'leave_breakdown',
        'payment_status',
        'payment_date',
        'payment_reference',
        'payment_mode',
        'bank_transaction_id',
        'bank_name',
        'account_number',
        'ifsc_code',
        'pan_number',
        'generation_type',
        'generated_at',
        'generated_by',
        'notes',
        'additional_details',
        'institute_id',
        'branch_id',
    ];
    
    protected $casts = [
        'standard_deductions' => 'array',
        'attendance_deductions' => 'array',
        'other_deductions' => 'array',
        'earnings_breakdown' => 'array',
        'attendance_summary' => 'array',
        'leave_breakdown' => 'array',
        'additional_details' => 'array',
        'payment_date' => 'datetime',
        'generated_at' => 'datetime',
    ];
    
    // Relationships
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }
    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class,'institute_id' ,'fincap_merchant_id');
    }
    public function salaryReview()
    {
        return $this->belongsTo(SalaryReview::class, 'salary_review_id');
    }
    
    // Accessors
    public function getFormattedFinalPayableAttribute()
    {
        return '₹' . number_format($this->final_payable, 2);
    }
    
    public function getFormattedGrossSalaryAttribute()
    {
        return '₹' . number_format($this->gross_salary, 2);
    }
    
    // Scopes
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }
    
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }
    
    public function scopeForMonth($query, $year, $month)
    {
        return $query->where('year', $year)->where('month', $month);
    }
    
    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }
}