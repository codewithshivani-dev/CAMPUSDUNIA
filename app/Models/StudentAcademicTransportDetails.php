<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAcademicTransportDetails extends Model
{
    protected $table = "academic_transport_details";

    protected $guarded = [];


    // Relationship with Student
    public function student()
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_hash_id', 'student_hash_id');
    }
     // Relationship with Department Category
    public function departmentCategory()
    {
        return $this->belongsTo(DepartmentCategory::class, 'department_category_id', 'department_category_id');
    }
}
