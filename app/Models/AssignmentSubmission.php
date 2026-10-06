<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $table = "assignment_submissions";
    protected $guarded = [];

    // Belongs to student assignment
    public function assignmentStudent()
    {
        return $this->belongsTo(AssignmentStudents::class, 'assignment_student_id');
    }
}
