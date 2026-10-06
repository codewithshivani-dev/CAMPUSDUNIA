<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OutPassStudents extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'student_id',
        'full_name',
        'contact_number',
        'address',
        'class',
        'section',
        'roll_number',
        'email',
        'parent_name',
        'parent_contact',
        'parent_email',
        'date_of_birth',
        'blood_group',
        'medical_conditions',
        'admission_year',
        'is_hosteler',
        'hostel_name',
        'room_number',
        'institute_id',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'is_hosteler' => 'boolean',
        'is_active' => 'boolean',
        'admission_year' => 'integer',
    ];

    /**
     * Get the user associated with the student.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the institute that owns the student.
     */
    public function institute(): BelongsTo
    {
        return $this->belongsTo(InstituteAdmin::class, 'institute_id');
    }

    /**
     * Get all out passes for the student (as requester).
     */
    public function outPasses(): MorphMany
    {
        return $this->morphMany(OutPass::class, 'requester');
    }

    /**
     * Get all class assignments for the student.
     */
    public function classAssignments(): HasMany
    {
        return $this->hasMany(StudentClassAssignment::class);
    }

    /**
     * Get the current class of the student.
     */
    public function currentClass()
    {
        return $this->classAssignments()
            ->where('status', 'active')
            ->with('class')
            ->first();
    }

    /**
     * Get full address with hostel info.
     */
    public function getFullAddressAttribute(): string
    {
        if ($this->is_hosteler) {
            return "Hostel: {$this->hostel_name}, Room: {$this->room_number}";
        }
        return $this->address;
    }

    /**
     * Get student's age.
     */
    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth?->age;
    }

    /**
     * Scope a query to only include active students.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include hostelers.
     */
    public function scopeHostelers($query)
    {
        return $query->where('is_hosteler', true);
    }
}