<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Homework extends Model
{
    use HasFactory;

    protected $table = 'homeworks';
    protected $guarded = [];

    protected $casts = [
        'due_date' => 'datetime',
        'assigned_at' => 'datetime',
    ];

    /**
     * Get the creator of the homework.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all assignments for this homework.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(HomeworkAssignment::class);
    }

    /**
     * Get the students assigned to this homework.
     */
    public function students()
    {
        return $this->belongsToMany(StudentParentDetails::class, 'homework_assignments', 'homework_id', 'student_id')
                    ->withPivot(['status', 'submitted_at', 'grade', 'feedback'])
                    ->withTimestamps();
    }

    /**
     * Scope a query to only include created homeworks.
     */
    public function scopeCreated($query)
    {
        return $query->where('status', 'created');
    }

    /**
     * Scope a query to only include assigned homeworks.
     */
    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    /**
     * Scope a query to only include homeworks due today.
     */
    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', today());
    }

    /**
     * Scope a query to only include upcoming homeworks.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('due_date', '>', now());
    }

    /**
     * Scope a query to only include overdue homeworks.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())->where('status', '!=', 'completed');
    }

    /**
     * Get homework statistics for dashboard.
     */
    public static function getStatistics($instituteId, $departmentId = null)
    {
        $query = self::where('institute_id', $instituteId);
        
        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        return [
            'total' => $query->count(),
            'created' => $query->where('status', 'created')->count(),
            'assigned' => $query->where('status', 'assigned')->count(),
            'due_today' => $query->whereDate('due_date', today())->count(),
            'upcoming' => $query->where('due_date', '>', now())->count(),
            'overdue' => $query->where('due_date', '<', now())->where('status', '!=', 'completed')->count(),
        ];
    }
}