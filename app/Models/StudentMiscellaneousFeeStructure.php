<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentMiscellaneousFeeStructure extends Model
{
    protected $table = "student_miscellaneous_fees";

    protected $guarded = [];
    
    public function studentDetail()
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_hash_id', 'student_hash_id');
    }
    public function academic()
    {
        return $this->belongsTo(StudentAcademicTransportDetails::class, 'student_hash_id', 'student_hash_id');
    }
}
