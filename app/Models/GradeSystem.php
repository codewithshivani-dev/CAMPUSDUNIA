<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeSystem extends Model
{
  use HasFactory;
    protected $table = "grade_systems";

     protected $guarded = [];

    protected $casts = [
        'grade_ranges' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id', 'institute_id');
    }
}