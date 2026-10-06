<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentCustomFeestructure extends Model
{
    protected $table = "student_custom_fees";

    protected $guarded = [];

    public function studentDetail()
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_hash_id', 'student_hash_id');
    }
}
