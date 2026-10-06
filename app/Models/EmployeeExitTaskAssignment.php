<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeExitTaskAssignment extends Model
{
    protected $table = 'employee_exit_task_assignments';

    protected $fillable = [
        'exit_id',
        'task_type',
        'assigned_to_user_id',
        'assigned_to_name',
        'assigned_to_role',
        'assigned_to_employee_id',
        'instructions',
        'deadline',
        'assigned_at',
        'assigned_by',
        'status',
        'completed_at',
        'completed_by',
        'completion_notes',
        'completion_attachment',
        'updated_by'
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function exit(): BelongsTo
    {
        return $this->belongsTo(EmployeeExit::class, 'exit_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Add the requirements relationship
    public function requirements(): HasMany
    {
        return $this->hasMany(TaskRequirement::class, 'task_id');
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'pending' => 'Pending',
            'in-progress' => 'In Progress',
            'completed' => 'Completed'
        ];
        return $labels[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'in-progress' => '<span class="badge bg-info">In Progress</span>',
            'completed' => '<span class="badge bg-success">Completed</span>'
        ];
        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    public function getTaskTypeLabelAttribute(): string
    {
        $labels = [
            'kt' => 'Knowledge Transfer',
            'exit_interview' => 'Exit Interview',
            'asset_clearance' => 'Asset Clearance',
            'fnf' => 'FNF Settlement'
        ];
        return $labels[$this->task_type] ?? ucfirst($this->task_type);
    }

    public function getTaskTypeIconAttribute(): string
    {
        $icons = [
            'kt' => 'fa-chalkboard-teacher',
            'exit_interview' => 'fa-comments',
            'asset_clearance' => 'fa-laptop',
            'fnf' => 'fa-file-invoice-dollar'
        ];
        return $icons[$this->task_type] ?? 'fa-tag';
    }

    public function getTaskTypeColorAttribute(): string
    {
        $colors = [
            'kt' => 'primary',
            'exit_interview' => 'warning',
            'asset_clearance' => 'info',
            'fnf' => 'danger'
        ];
        return $colors[$this->task_type] ?? 'secondary';
    }

    public function getIsOverdueAttribute(): bool
    {
        if ($this->status === 'completed') {
            return false;
        }
        if (!$this->deadline) {
            return false;
        }
        return $this->deadline->isPast();
    }

    public function getProgressAttribute(): array
    {
        $total = $this->requirements()->count();
        $completed = $this->requirements()->where('status', 'completed')->count();
        
        return [
            'total' => $total,
            'completed' => $completed,
            'percentage' => $total > 0 ? round(($completed / $total) * 100) : 0
        ];
    }
}  