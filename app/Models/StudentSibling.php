<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentSibling extends Model
{
    use HasFactory;

    protected $table = 'student_siblings';

    protected $guarded = [];
    
    public function studentDetails()
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_hash_id', 'student_hash_id');
    }
    
}