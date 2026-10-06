<?php
// app/Models/StudentRollNumber.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentRollNumber extends Model
{
    protected $fillable = [
        'student_hash_id',
        'registration_number',
        'institute_id',
        'branch_id',
        'department_id',
        'department_category_id',
        'course_subtype_id',
        'batch_id',
        'academic_year_id',
        'section_id',
        'roll_number',
        'roll_number_sequence',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_hash_id', 'student_hash_id');
    }

    public function academicDetails(): BelongsTo
    {
        return $this->belongsTo(StudentAcademicTransportDetails::class, 'student_hash_id', 'student_hash_id');
    }
}