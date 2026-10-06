<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'department_categories';

    // Relationship with departments
    public function departments()
    {
        return $this->hasMany(Departments::class, 'department_category_id', 'department_category_id');
    }

     public function employees()
    {
        return $this->hasManyThrough(
            EmployeeDetails::class,
            Departments::class,
            'department_category_id', // Foreign key on Departments table
            'department_id', // Foreign key on EmployeeDetails table
            'department_category_id', // Local key on DepartmentCategory table
            'id' // Local key on Departments table
        );
    }
    // Relationship with Students
    // Relationship with Students through AcademicTransportDetails
    public function students()
    {
        return $this->hasManyThrough(
            StudentParentDetails::class,
            StudentAcademicTransportDetails::class,
            'department_category_id', // Foreign key on AcademicTransportDetails
            'student_hash_id', // Foreign key on StudentParentDetails
            'department_category_id', // Local key on DepartmentCategory
            'student_hash_id' // Local key on AcademicTransportDetails
        );
    }
     // Count of employees
    public function getEmployeesCountAttribute()
    {
        return $this->employees()->count();
    }

    // Count of students
    public function getStudentsCountAttribute()
    {
        return $this->students()->count();
    }

    // Count of departments
    public function getDepartmentsCountAttribute()
    {
        return $this->departments()->count();
    }
}