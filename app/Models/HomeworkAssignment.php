<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeworkAssignment extends Model
{
    use HasFactory;
    protected $table = 'homework_assignments';
    protected $guarded = [];

    protected $casts = [
        'submitted_at' => 'datetime',
        'assigned_at' => 'datetime',
        'grade' => 'string',
    ];

    /**
     * Get the homework that owns the assignment.
     */
    public function homework(): BelongsTo
    {
        return $this->belongsTo(Homework::class);
    }

    /**
     * Get the student that owns the assignment.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_hash_id', 'student_hash_id');
    }

    /**
     * Get the student user that owns the assignment.
     */
    public function studentUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_user_id');
    }

    /**
     * Check if assignment is submitted.
     */
    public function isSubmitted(): bool
    {
        return !is_null($this->submitted_at);
    }

    /**
     * Check if assignment is graded.
     */
    public function isGraded(): bool
    {
        return !is_null($this->graded_at);
    }

    /**
     * Check if assignment is late.
     */
    public function isLate(): bool
    {
        return $this->submitted_at && $this->submitted_at > $this->homework->due_date;
    }

    /**
     * Get submission status class for UI.
     */
    public function getStatusClass(): string
    {
        return match($this->status) {
            'assigned' => 'status-created',
            'submitted' => 'status-submitted',
            'graded' => 'status-graded',
            'late' => 'status-late',
            default => 'status-created',
        };
    }

    /**
     * Get grade letter if applicable.
     */
    public function getGradeLetter(): ?string
    {
        if (!$this->grade) return null;

        if ($this->grade >= 90) return 'A+';
        if ($this->grade >= 80) return 'A';
        if ($this->grade >= 70) return 'B';
        if ($this->grade >= 60) return 'C';
        if ($this->grade >= 50) return 'D';
        return 'F';
    }
}