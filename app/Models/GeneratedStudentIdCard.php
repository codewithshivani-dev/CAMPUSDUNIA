<?php
// app/Models/GeneratedStudentIdCard.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneratedStudentIdCard extends Model
{
    protected $table = 'generated_student_id_cards';

    protected $fillable = [
        'student_hash_id',
        'institute_id',
        'branch_id',
        'template_id',
        'card_number',
        'pdf_path',
        'qr_code',
        'generated_at',
        'expiry_date',
        'is_active',
        'academic_year',
        'batch',
        'course_type',
        'semester',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'expiry_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_hash_id', 'student_hash_id');
    }

    public function template()
    {
        return $this->belongsTo(StudentIdCardTemplate::class, 'template_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForStudent($query, $studentHashId)
    {
        return $query->where('student_hash_id', $studentHashId);
    }
}