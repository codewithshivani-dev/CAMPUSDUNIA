<?php
// app/Models/AcademicEvent.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicEvent extends Model
{
    protected $table = 'academic_events';
    
    protected $primaryKey = 'academic_event_id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'academic_event_id',
        'institute_id',
        'branch_id',
        'title',
        'event_type',
        'event_date',
        'end_date',
        'description',
        'color',
        'created_by'
    ];
    
    protected $casts = [
        'event_date' => 'date',
        'end_date' => 'date'
    ];
}