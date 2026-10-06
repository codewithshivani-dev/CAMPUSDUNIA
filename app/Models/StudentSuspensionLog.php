<?php
// app/Models/StudentSuspensionLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentSuspensionLog extends Model
{
    protected $table = 'student_suspension_logs';
    
    protected $fillable = [
        'student_hash_id',
        'student_id',
        'user_id',
        'action',
        'reason',
        'performed_by',
        'metadata'
    ];
    
    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Get the student associated with this log
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_hash_id', 'student_hash_id');
    }
    
    /**
     * Get the admin who performed the action
     */
    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
    
    /**
     * Scope to get suspension records only
     */
    public function scopeSuspendActions($query)
    {
        return $query->where('action', 'suspend');
    }
    
    /**
     * Scope to get unsuspension records only
     */
    public function scopeUnsuspendActions($query)
    {
        return $query->where('action', 'unsuspend');
    }
    
    /**
     * Scope to get active suspensions (last action is suspend without unsuspend)
     */
    public function scopeCurrentlySuspended($query)
    {
        return $query->where('action', 'suspend')
            ->whereRaw('created_at > COALESCE((
                SELECT MAX(created_at) FROM student_suspension_logs AS l2 
                WHERE l2.student_hash_id = student_suspension_logs.student_hash_id 
                AND l2.action = "unsuspend"
            ), "1900-01-01")');
    }
}