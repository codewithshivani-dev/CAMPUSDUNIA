<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EmployeeSalaryStructure extends Model
{
    protected $table = 'employee_salary_structures';
    protected $fillable = [
        'salary_structure_id',
        'payroll_policy_id',
        'structure_type',
        'institute_id',
        'branch_id',
        'department_category_id',
        'department_id',
        'designation_id',
        'employee_id',
        'financial_year',
        'fixed_ctc_annual',
        'variable_ctc_annual',
        'total_ctc_annual',
        'monthly_fixed',
        'monthly_variable',
        'basic_salary_percentage',
        'basic_salary_monthly',
        'basic_salary_annual',
        'is_active',
        'employment_type',
        'policy_employment_type',
        'status',
    ];
    protected $casts = [
        'fixed_ctc_annual' => 'decimal:2',
        'variable_ctc_annual' => 'decimal:2',
        'total_ctc_annual' => 'decimal:2',
        'monthly_fixed' => 'decimal:2',
        'monthly_variable' => 'decimal:2',
        'basic_salary_percentage' => 'decimal:2',
        'basic_salary_monthly' => 'decimal:2',
        'basic_salary_annual' => 'decimal:2',
        'is_active' => 'boolean',
    ];


    public function allowances()
    {
        return $this->hasOne(SalaryStructureAllowances::class, 'salary_structure_id', 'salary_structure_id');
    }

    /**
     * Get the bonuses for this salary structure
     */
    public function bonuses()
    {
        return $this->hasMany(SalaryStructureBonus::class, 'salary_structure_id', 'salary_structure_id');
    }

    /**
     * Get the overtime for this salary structure
     */
    public function overtime()
    {
        return $this->hasOne(SalaryStructureOvertime::class, 'salary_structure_id', 'salary_structure_id');
    }

    /**
     * Get the deductions for this salary structure
     */
    public function deductions()
    {
        return $this->hasOne(SalaryStructureDeduction::class, 'salary_structure_id', 'salary_structure_id');
    }

    public function preview()
    {
        return $this->hasOne(SalaryPreview::class, 'salary_structure_id', 'salary_structure_id');
    }

    /**
     * Get the employee for this salary structure
     */
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the department for this salary structure
     */
    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'department_id');
    }

}
