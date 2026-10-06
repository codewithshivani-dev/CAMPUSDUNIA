<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmitCard extends Model
{
    protected $table = 'admit_cards';

    protected $guarded = [];

    protected $casts = [
        'generated_at'  => 'datetime',
        'downloaded_at' => 'datetime',
        'printed_at'    => 'datetime',
        'published_at'  => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Student
    |--------------------------------------------------------------------------
    */

    public function student()
    {
        return $this->belongsTo(
            StudentParentDetails::class,
            'student_hash_id',
            'student_hash_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Exam Name
    |--------------------------------------------------------------------------
    */

    public function examName()
    {
        return $this->belongsTo(
            ExamName::class,
            'exam_name_id',
            'exam_name_id'
        );
    }
}