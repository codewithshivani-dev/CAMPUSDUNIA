<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExitDetail extends Model
{
    use HasFactory;

    protected $table = 'student_exit_details';

    protected $guarded = [];

    protected $casts = [
        'exit_date' => 'date',
        'dues_cleared_at' => 'datetime',
        'no_due_certificate_generated_at' => 'datetime',
        'exited_at' => 'datetime',
        'metadata' => 'json',
        'dues_cleared' => 'boolean',
        'no_due_certificate_generated' => 'boolean',
        'notify_student' => 'boolean',
        'notify_parent' => 'boolean',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function exitedBy()
    {
        return $this->belongsTo(User::class, 'exited_by');
    }

    public function duesClearedBy()
    {
        return $this->belongsTo(User::class, 'dues_cleared_by');
    }

    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'fincap_merchant_id', 'institute_id');
    }

  
    // Scopes
    public function scopeByExitType($query, $type)
    {
        return $query->where('exit_type', $type);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('exit_date', [$startDate, $endDate]);
    }

    public function scopeByInstitute($query, $instituteId)
    {
        return $query->where('institute_id', $instituteId);
    }
}