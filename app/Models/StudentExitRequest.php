<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentExitRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'request_id',
        'student_hash_id',
        'student_id',
        'user_id',
        'institute_id',
        'branch_id',
        'exit_type',
        'requested_exit_date',
        'exit_reason',
        'additional_notes',
        'status',
        'admin_remarks',
        'admin_processed_at',
        'processed_by',
        'student_confirmed',
        'student_confirmed_at',
        'notify_student',
        'notify_parent',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'requested_exit_date' => 'date',
        'admin_processed_at' => 'datetime',
        'student_confirmed_at' => 'datetime',
        'student_confirmed' => 'boolean',
        'notify_student' => 'boolean',
        'notify_parent' => 'boolean',
        'metadata' => 'array',
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

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id', 'fincap_merchant_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByStudent($query, $studentHashId)
    {
        return $query->where('student_hash_id', $studentHashId);
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'cancelled' => 'secondary',
        ];
        return $badges[$this->status] ?? 'secondary';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pending Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
        ];
        return $labels[$this->status] ?? ucfirst($this->status);
    }

    public function getExitTypeLabelAttribute()
    {
        $labels = [
            'course_completion' => 'Course Completion',
            'mid_session' => 'Mid-Session Withdrawal',
            'cancellation' => 'Cancellation',
        ];
        return $labels[$this->exit_type] ?? ucfirst(str_replace('_', ' ', $this->exit_type));
    }
}