<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignSubjectsToEmployee extends Model
{
    protected $table = 'assign_subjects_to_employee';

      protected $guarded = [];

    public function lectures()
    {
        return $this->hasMany(EmployeeSubjectLecture::class, 'emp_assign_subject_id');
    }

    public function employee()
    {
        // local key employee_id (string) -> employee_details.employee_id (string)
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }

    public function subject()
    {
        return $this->belongsTo(SubjectsCoursewise::class, 'subject_id', 'subject_id');
    }
     // Relationship with course
    public function course()
    {
        return $this->belongsTo(ProductDetails::class, 'course_detail_id', 'product_id');
    }
    // Relationship with department
    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'department_id');
    }
}