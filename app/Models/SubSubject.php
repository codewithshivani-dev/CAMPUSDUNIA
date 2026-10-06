<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubSubject extends Model
{
    use HasFactory;

    protected $table = 'sub_subjects';
    
    protected $guarded = [];

    // public function subject()
    // {
    //     return $this->belongsTo(SubjectsCoursewise::class, 'subject_coursewise_id');
    // }

    public function subject()
    {
        return $this->belongsTo(SubjectsCoursewise::class,'subject_id','subject_id');
    }
}