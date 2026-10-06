<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceReview extends Model
{
    protected $fillable = [
        'institute_id',
        'branch_id',
        'year',
        'month',
        'employee_id',
        'department_id',
        'present_days',
        'absent_days',
        'leave_days',
        'weekend_days',
        'working_days',
        'short_attendance_days',
        'unapproved_leave_days',
        'attendance_percentage',
        'review_status',
        'review_notes',
        'finalize_notes',
        'reviewed_by',
        'finalized_by',
        'reviewed_at',
        'finalized_at',
        'attendance_details'
    ];

    protected $casts = [
        'attendance_details' => 'array',
        'reviewed_at' => 'datetime',
        'finalized_at' => 'datetime'
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(EmployeeDetails::class, 'reviewed_by', 'employee_id');
    }
}