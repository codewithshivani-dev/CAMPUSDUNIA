<?php
// app/Models/LeaveDeduction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveDeduction extends Model
{
    use SoftDeletes;
    
    protected $table = 'leave_deductions';
    
    protected $guarded = [];
    
    protected $casts = [
        'approved_deduction_percentage' => 'decimal:2',
        'unapproved_deduction_percentage' => 'decimal:2',
        'is_custom' => 'boolean',
        'is_active' => 'boolean',
        'requires_doctor_certificate' => 'boolean',
        'max_consecutive_days' => 'integer',
        'max_days_per_year' => 'integer'
    ];
    
    // Relationships
    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id');
    }
    
    public function branch()
    {
        return $this->belongsTo(Branches::class, 'branch_id');
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeForInstitute($query, $instituteId, $branchId = null)
    {
        $query->where('institute_id', $instituteId);
        
        if ($branchId) {
            $query->where(function($q) use ($branchId) {
                $q->where('branch_id', $branchId)
                  ->orWhereNull('branch_id');
            });
        } else {
            $query->whereNull('branch_id');
        }
        
        return $query;
    }
    
    // Calculate deduction amount
    public function calculateDeduction($days, $dailySalary, $isApproved = true)
    {
        $percentage = $isApproved ? $this->approved_deduction_percentage : $this->unapproved_deduction_percentage;
        $totalSalary = $dailySalary * $days;
        $deductionAmount = ($totalSalary * $percentage) / 100;
        
        return (object) [
            'amount' => $deductionAmount,
            'percentage' => $percentage,
            'total_salary' => $totalSalary,
            'net_salary' => $totalSalary - $deductionAmount,
            'status' => $isApproved ? 'Approved' : 'Unapproved'
        ];
    }
    
    // Boot method
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->deduction_id)) {
                $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $model->leave_type), 0, 3));
                $model->deduction_id = $prefix . '-' . strtoupper(uniqid());
            }
        });
    }
}