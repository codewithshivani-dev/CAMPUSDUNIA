<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Departments  extends Model
{
    use HasFactory;
    protected $table="departments";
    
    protected $guarded = [];
    
    public function category()
    {
        return $this->belongsTo(DepartmentCategory::class, 'department_category_id', 'department_category_id');
    }

    // Relationship with Employees
    public function employees()
    {
        return $this->hasMany(EmployeeDetails::class, 'department_id');
    }

    // Count of employees
    public function getEmployeesCountAttribute()
    {
        return $this->employees()->count();
    }

    // Add this relationship
    public function shift()
    {
        return $this->belongsTo(Shifts::class, 'shift_id', 'id');
    }
    public function departmentShifts()
    {
        return $this->hasMany(DepartmentShift::class, 'department_id', 'department_id');
    }
    
    public function payrollExecution()
    {
        return $this->hasOne(PayrollExecution::class, 'department_id', 'department_id');
    }
}


