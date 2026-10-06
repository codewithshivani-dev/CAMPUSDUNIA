<?php
// app/Models/AcademicYear.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $table = 'academic_years';
    
    protected $primaryKey = 'academic_year_id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'academic_year_id',
        'institute_id',
        'branch_id',
        'year_name',
        'start_date',
        'end_date',
        'is_active',
        'current_year'
    ];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];
}