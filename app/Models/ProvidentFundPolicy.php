<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvidentFundPolicy extends Model
{
    use HasFactory;
    
    protected $table = 'provident_fund_policies';
    protected $guarded = [];


    public function allowances()
    {
        return $this->hasOne(PayrollpolicyAllowance::class, 'payroll_policy_id', 'payroll_policy_id');
    }

    public function taxDeductions()
    {
        return $this->hasOne(PayrollPolicyTaxDeduction::class, 'payroll_policy_id', 'payroll_policy_id');
    }

    public function otherDeductions()
    {
        return $this->hasOne(PayrollPolicyOtherDeduction::class, 'payroll_policy_id', 'payroll_policy_id');
    }

    /**
     * Get the department relationship
     */
    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'department_id');
    }

    /**
     * Get the employee relationship
     */
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }

    /**
     * Scope to filter by institute
     */
    public function scopeForInstitute($query, $instituteId)
    {
        return $query->where('institute_id', $instituteId);
    }

    /**
     * Scope to filter by branch
     */
    public function scopeForBranch($query, $branchId)
    {
        if ($branchId) {
            return $query->where('branch_id', $branchId);
        }
        return $query;
    }
}