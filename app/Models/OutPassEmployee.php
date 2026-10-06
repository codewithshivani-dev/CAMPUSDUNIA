<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OutPassEmployee extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'employee_id',
        'full_name',
        'contact_number',
        'address',
        'department',
        'designation',
        'email',
        'reporting_manager',
        'emergency_contact',
        'emergency_contact_name',
        'joining_date',
        'salary',
        'employment_type',
        'work_shift',
        'institute_id',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'joining_date' => 'date',
        'salary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user associated with the employee.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the institute that owns the employee.
     */
    public function institute(): BelongsTo
    {
        return $this->belongsTo(InstituteAdmin::class, 'institute_id');
    }

    /**
     * Get all out passes for the employee (as requester).
     */
    public function outPasses(): MorphMany
    {
        return $this->morphMany(OutPass::class, 'requester');
    }

    /**
     * Get the department this employee belongs to.
     */
    public function departmentModel(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department', 'name');
    }

    /**
     * Get employees managed by this employee.
     */
    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'reporting_manager', 'full_name');
    }

    /**
     * Get the manager of this employee.
     */
    public function manager()
    {
        return $this->belongsTo(Employee::class, 'reporting_manager', 'full_name');
    }

    /**
     * Get employee's experience in years.
     */
    public function getExperienceYearsAttribute(): ?int
    {
        return $this->joining_date?->diffInYears(now());
    }

    /**
     * Scope a query to only include active employees.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by department.
     */
    public function scopeInDepartment($query, string $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope a query to filter by employment type.
     */
    public function scopeOfEmploymentType($query, string $type)
    {
        return $query->where('employment_type', $type);
    }
}