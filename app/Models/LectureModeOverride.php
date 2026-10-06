<?php
// app/Models/LectureModeOverride.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LectureModeOverride extends Model
{
    use HasFactory;

    protected $table = 'lecture_mode_overrides';

    protected $fillable = [
        'lecture_id',
        'assignment_id',
        'override_date',
        'lecture_mode',
        'meeting_link',
        'meeting_password',
        'meeting_instructions',
        'location_override',
        'room_override',
        'is_active',
        'remarks',
        'institute_id',
        'branch_id',
        'created_by'
    ];

    protected $casts = [
        'override_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function lecture()
    {
        return $this->belongsTo(EmployeeSubjectLecture::class, 'lecture_id');
    }

    public function assignment()
    {
        return $this->belongsTo(AssignSubjectsToEmployee::class, 'assignment_id');
    }

    public function creator()
    {
        return $this->belongsTo(EmployeeDetails::class, 'created_by', 'employee_id');
    }
}