<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutPassApproval extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'out_pass_id',
        'approver_id',
        'approval_level',
        'status',
        'comments',
        'action_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'action_at' => 'datetime',
    ];

    /**
     * Get the out pass being approved.
     */
    public function outPass(): BelongsTo
    {
        return $this->belongsTo(OutPass::class);
    }

    /**
     * Get the approver.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    /**
     * Check if approval is pending.
     */
    public function getIsPendingAttribute(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Get status color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'warning'
        };
    }

    /**
     * Get approval level name.
     */
    public function getLevelNameAttribute(): string
    {
        return ucfirst($this->approval_level) . ' Approval';
    }

    /**
     * Scope a query to only include pending approvals.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to filter by approver.
     */
    public function scopeForApprover($query, int $approverId)
    {
        return $query->where('approver_id', $approverId);
    }
}