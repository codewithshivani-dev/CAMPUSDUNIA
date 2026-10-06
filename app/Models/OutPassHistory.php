<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutPassHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'out_pass_id',
        'user_id',
        'action',
        'previous_status',
        'new_status',
        'remarks',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the out pass that owns the history.
     */
    public function outPass(): BelongsTo
    {
        return $this->belongsTo(OutPass::class);
    }

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get action icon class.
     */
    public function getActionIconAttribute(): string
    {
        return match($this->action) {
            'created' => 'fa-plus-circle text-success',
            'submitted' => 'fa-paper-plane text-primary',
            'viewed' => 'fa-eye text-info',
            'approved' => 'fa-check-circle text-success',
            'rejected' => 'fa-times-circle text-danger',
            'cancelled' => 'fa-ban text-warning',
            'printed' => 'fa-print text-secondary',
            'downloaded' => 'fa-download text-primary',
            'returned' => 'fa-undo text-info',
            'expired' => 'fa-clock text-dark',
            default => 'fa-history text-secondary'
        };
    }

    /**
     * Get formatted action description.
     */
    public function getDescriptionAttribute(): string
    {
        $userName = $this->user->name ?? 'System';
        
        $description = "{$userName} {$this->action} this pass";
        
        if ($this->previous_status && $this->new_status && $this->previous_status !== $this->new_status) {
            $description .= " (changed from {$this->previous_status} to {$this->new_status})";
        }
        
        if ($this->remarks) {
            $description .= ": {$this->remarks}";
        }
        
        return $description;
    }
}