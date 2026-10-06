<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OutPassDepartment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'institute_id',
        'head_of_department',
        'employee_count',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'employee_count' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the institute that owns the department.
     */
    public function institute(): BelongsTo
    {
        return $this->belongsTo(InstituteAdmin::class, 'institute_id');
    }

    /**
     * Get the head of department.
     */
    public function hod(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'head_of_department');
    }

    /**
     * Get all employees in this department.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'department', 'name');
    }

    /**
     * Get department full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->name} ({$this->code})";
    }

    /**
     * Update employee count.
     */
    public function updateEmployeeCount(): void
    {
        $this->update([
            'employee_count' => $this->employees()->where('is_active', true)->count()
        ]);
    }

    /**
     * Scope a query to only include active departments.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}