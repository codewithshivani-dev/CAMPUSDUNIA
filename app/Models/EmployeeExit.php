<?php
// app/Models/EmployeeExit.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class EmployeeExit extends Model
{
    protected $table = 'employee_exits';

    protected $guarded = [];

    protected $casts = [
        'notice_start_date' => 'date',
        'notice_end_date' => 'date',
        'actual_exit_date' => 'date',
        'clearance_done' => 'boolean',
        'settlement_done' => 'boolean',
        'exit_interview_done' => 'boolean',
        'handover_completed' => 'boolean',
        'assets_returned' => 'boolean',
        'user_account_deactivated' => 'boolean',
        'rehire_eligible' => 'boolean',
        'attachments' => 'array',
        'settlement_amount' => 'decimal:2',
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }

    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id', 'fincap_merchant_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function exitPolicy()
    {
        return $this->belongsTo(EmployeeExitPolicy::class, 'exit_policy_id');
    }

    public function initiatedBy()
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function approvals()
    {
        return $this->hasMany(EmployeeExitApproval::class, 'exit_id');
    }

    // Scopes
    public function scopePendingApproval($query)
    {
        return $query->where('exit_status', 'pending_approval');
    }

    public function scopeApproved($query)
    {
        return $query->where('exit_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('exit_status', 'rejected');
    }

    public function scopeNoticePeriod($query)
    {
        return $query->where('exit_status', 'notice_period');
    }

    public function scopeExited($query)
    {
        return $query->where('exit_status', 'exited');
    }

    public function scopeCancelled($query)
    {
        return $query->where('exit_status', 'cancelled');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('exit_status', ['pending_approval', 'approved', 'notice_period']);
    }

    public function scopeEmployeeInitiated($query)
    {
        return $query->where('initiation_source', 'employee');
    }

    public function scopeAdminInitiated($query)
    {
        return $query->where('initiation_source', 'admin');
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending_approval' => 'Pending Approval',
            'approved' => 'Approved',
            'notice_period' => 'Notice Period',
            'exited' => 'Exited',
            'cancelled' => 'Cancelled',
            'rejected' => 'Rejected'
        ];
        return $labels[$this->exit_status] ?? ucfirst(str_replace('_', ' ', $this->exit_status));
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending_approval' => 'warning',
            'approved' => 'success',
            'notice_period' => 'info',
            'exited' => 'danger',
            'cancelled' => 'secondary',
            'rejected' => 'danger'
        ];
        return $colors[$this->exit_status] ?? 'secondary';
    }
    
    public function getIsEmployeeInitiatedAttribute()
    {
        return $this->initiation_source === 'employee';
    }

    public function getIsAdminInitiatedAttribute()
    {
        return $this->initiation_source === 'admin';
    }

    public function getDaysRemainingAttribute()
    {
        if (!$this->notice_end_date) {
            return null;
        }
        $now = now();
        $end = $this->notice_end_date;
        $days = $now->diffInDays($end, false);
        return max(0, $days);
    }

    public function getIsOverdueAttribute()
    {
        if (!$this->notice_end_date) {
            return false;
        }
        return now()->greaterThan($this->notice_end_date) && $this->exit_status === 'notice_period';
    }

    public function getNoticePeriodProgressAttribute()
    {
        if (!$this->notice_start_date || !$this->notice_end_date) {
            return 0;
        }
        $total = $this->notice_start_date->diffInDays($this->notice_end_date);
        $elapsed = $this->notice_start_date->diffInDays(now());
        return $total > 0 ? min(100, ($elapsed / $total) * 100) : 0;
    }

    // Helper methods
    public function canBeApproved()
    {
        return $this->exit_status === 'pending_approval';
    }

    public function canBeRejected()
    {
        return in_array($this->exit_status, ['pending_approval', 'approved']);
    }

    public function canBeCancelled()
    {
        return in_array($this->exit_status, ['pending_approval', 'approved', 'notice_period']);
    }

    public function canBeCompleted()
    {
        return in_array($this->exit_status, ['approved', 'notice_period']);
    }

    public function startNoticePeriod()
    {
        if ($this->exit_status !== 'approved') {
            throw new \Exception('Cannot start notice period. Exit must be approved first.');
        }

        $this->update([
            'exit_status' => 'notice_period',
            'notice_start_date' => now(),
            'notice_end_date' => now()->addDays($this->notice_period_days)
        ]);
    }

    public function completeExit()
    {
        if (!in_array($this->exit_status, ['approved', 'notice_period'])) {
            throw new \Exception('Cannot complete exit. Exit must be in notice period.');
        }

        $this->update([
            'exit_status' => 'exited',
            'actual_exit_date' => now(),
            'user_account_deactivated' => true,
            'user_account_deactivation_date' => now()
        ]);
    }

    public function cancelExit($reason = null)
    {
        if (!in_array($this->exit_status, ['pending_approval', 'approved', 'notice_period'])) {
            throw new \Exception('Cannot cancel exit. Exit is already completed or cancelled.');
        }

        $this->update([
            'exit_status' => 'cancelled',
            'cancellation_reason' => $reason
        ]);
    }

    public function approveExit($userId)
    {
        if ($this->exit_status !== 'pending_approval') {
            throw new \Exception('Cannot approve. Exit is not pending approval.');
        }

        $this->update([
            'exit_status' => 'approved',
            'approved_by' => $userId,
            'approved_at' => now()
        ]);
    }

    public function rejectExit($userId, $reason = null)
    {
        if ($this->exit_status !== 'pending_approval') {
            throw new \Exception('Cannot reject. Exit is not pending approval.');
        }

        $this->update([
            'exit_status' => 'rejected',
            'rejected_by' => $userId,
            'rejected_at' => now(),
            'rejection_reason' => $reason
        ]);
    }
}