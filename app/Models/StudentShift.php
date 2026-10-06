<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class StudentShift extends Model
{
    use HasFactory;

    protected $table = 'student_shifts';
    

    protected $guarded = [];

    protected $casts = [
    'weekly_off_days' => 'array',
    'start_date' => 'date',
    'end_date' => 'date',
];


    public function shift()
    {
        return $this->belongsTo(Shifts::class, 'shift_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_hash_id', 'student_hash_id');
    }

    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id');
    }

}