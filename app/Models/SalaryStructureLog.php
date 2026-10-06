<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStructureLog extends Model
{
    use HasFactory;

    protected $table = 'salary_structure_logs';

    protected $fillable = [
        'salary_structure_id',
        'institute_id',
        'branch_id',
        'structure_type',
        'department_id',
        'employee_id',
        'financial_year',
        'action',
        'change_type',
        'previous_data',
        'current_data',
        'changed_by'
    ];

    protected $casts = [
        'previous_data' => 'array',
        'current_data' => 'array'
    ];

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'department_id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }
    
    // public function currentStructure()
    // {
    //     return $this->belongsTo(EmployeeSalaryStructure::class, 'salary_structure_id', 'salary_structure_id');
    // }

    // In your SalaryStructureLog model, update the currentStructure relationship:

    public function currentStructure()
    {
        return $this->belongsTo(EmployeeSalaryStructure::class, 'salary_structure_id', 'salary_structure_id')
            ->with(['allowances', 'deductions', 'preview']);
    }

}
