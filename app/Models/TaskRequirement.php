<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskRequirement extends Model
{
    protected $table = 'task_requirements';

    protected $fillable = [
        'task_id',
        'requirement_key',
        'requirement_label',
        'status',
        'notes',
        'completed_by',
        'completed_at',
        'order'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(EmployeeExitTaskAssignment::class, 'task_id');
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'in_progress' => '<span class="badge bg-info">In Progress</span>',
            'completed' => '<span class="badge bg-success">Completed</span>'
        ];
        return $badges[$this->status] ?? $badges['pending'];
    }

    public function getStatusIconAttribute()
    {
        $icons = [
            'pending' => 'fa-circle text-warning',
            'in_progress' => 'fa-spinner fa-spin text-info',
            'completed' => 'fa-check-circle text-success'
        ];
        return $icons[$this->status] ?? $icons['pending'];
    }
}