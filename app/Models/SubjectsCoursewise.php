<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectsCoursewise extends Model
{
    protected $table = "subjects_coursewise";

    protected $guarded = [];


    public function subSubjects()
    {
        return $this->hasMany(\App\Models\SubSubject::class, 'subject_id', 'subject_id');
    }
}
