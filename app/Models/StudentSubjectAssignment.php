<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubjectAssignment extends Model
{
    use HasFactory;

    protected $table = 'student_subject_assignments';
    protected $guarded =[];

    // protected $fillable = [
    //     'institute_id',
    //     'branch_id',
    //     'student_hash_id',
    //     'subject_type',
    //     'subject_id',
    //     'sub_subject_id',
    //     'course_detail_id',
    //     'department_id',
    //     'assigned_date',
    //     'remarks',
    //     'status'
    // ];

    protected $casts = [
        'assigned_date' => 'date',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_hash_id', 'student_hash_id');
    }

    public function subject()
    {
        return $this->belongsTo(SubjectsCoursewise::class, 'subject_id', 'subject_id');
    }

    public function course()
    {
        return $this->belongsTo(ProductDetails::class, 'course_detail_id', 'product_id');
    }

    public function department()
    {
        return $this->belongsTo(Departments::class,'department_id', 'department_id');
    }
}