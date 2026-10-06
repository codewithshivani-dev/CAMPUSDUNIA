<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryReview extends Model
{
    protected $table = 'salary_reviews';
    
    protected $fillable = [
        'institute_id',
        'branch_id',
        'employee_id',
        'department_id',
        'attendance_review_id',
        'year',
        'month',
        
        // Base Salary
        'basic_salary',
        'gross_salary',
        'monthly_net_salary',
        'final_payable_salary',  // New field for final payable after all deductions
        
        // Standard Deductions (Statutory)
        'pt_deduction',
        'lst_deduction',
        'tds_deduction',
        'insurance_premium',
        'advance_salary_deduction',
        'pf_employee_deduction',
        'esi_employee_deduction',
        'nps_employee_deduction',
        'total_standard_deductions',
        
        // Attendance Deductions
        'absent_deduction',
        'leave_deduction',
        'unpaid_leave_deduction',
        'unapproved_leave_deduction',
        'short_attendance_deduction',
        'exceeded_short_leaves_deduction',
        'exceeded_half_days_deduction',
        'total_attendance_deductions',
        
        // Other Deductions (Loan, Advance, Custom)
        'loan_deduction',
        'advance_deduction',
        'custom_deductions_total',
        'other_deductions_total',
        'other_deductions_details', // JSON field
        
        // Summary
        'total_deductions',
        'net_salary', // Legacy, will be deprecated
        'payable_salary', // Legacy
        
        // Status
        'review_status',
        'review_notes',
        'reviewed_by',
        'reviewed_at',
        'finalized_by',
        'finalized_at',
        'finalize_notes',
        'finalization_details', // JSON field for complete breakdown
        
        // Earnings Breakdown
        'earnings_breakdown', // JSON field
        'deductions_breakdown', // JSON field
        'attendance_summary', // JSON field
        'leave_breakdown', // JSON field
    ];
    
    protected $casts = [
        'other_deductions_details' => 'array',
        'finalization_details' => 'array',
        'earnings_breakdown' => 'array',
        'deductions_breakdown' => 'array',
        'attendance_summary' => 'array',
        'leave_breakdown' => 'array',
        'reviewed_at' => 'datetime',
        'finalized_at' => 'datetime',
    ];
    
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }
    
    public function attendanceReview()
    {
        return $this->belongsTo(AttendanceReview::class, 'attendance_review_id');
    }
}