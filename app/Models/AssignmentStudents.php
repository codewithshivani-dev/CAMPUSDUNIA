<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentStudents extends Model
{
    use HasFactory;

     protected $table = "assignment_students";
     protected $guarded = [];

    // Student assignment belongs to assignment
    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

    // Belongs to one student
    public function student()
    {
        return $this->hasMany(StudentParentDetails::class, 'student_hash_id', 'student_id');
    }

    // Student can make multiple submissions (history)
    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'assignment_student_id');
    }
}
