<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OutPassVehicle extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vehicle_number',
        'vehicle_type',
        'model',
        'color',
        'chassis_number',
        'engine_number',
        'registration_date',
        'insurance_expiry',
        'fitness_expiry',
        'fuel_type',
        'owner_type',
        'owner_id',
        'driver_name',
        'driver_contact',
        'driver_address',
        'driver_license',
        'license_expiry',
        'institute_id',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'registration_date' => 'date',
        'insurance_expiry' => 'date',
        'fitness_expiry' => 'date',
        'license_expiry' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the owner model (polymorphic).
     */
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the institute that owns the vehicle.
     */
    public function institute(): BelongsTo
    {
        return $this->belongsTo(InstituteAdmin::class, 'institute_id');
    }

    /**
     * Get all out passes for this vehicle.
     */
    public function outPasses(): HasMany
    {
        return $this->hasMany(OutPass::class, 'vehicle_number', 'vehicle_number');
    }

    /**
     * Check if vehicle documents are valid.
     */
    public function getDocumentsValidAttribute(): bool
    {
        $now = now();
        
        if ($this->insurance_expiry && $this->insurance_expiry < $now) {
            return false;
        }
        
        if ($this->fitness_expiry && $this->fitness_expiry < $now) {
            return false;
        }
        
        if ($this->license_expiry && $this->license_expiry < $now) {
            return false;
        }
        
        return true;
    }

    /**
     * Get vehicle full details.
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->vehicle_number} ({$this->model})";
    }

    /**
     * Scope a query to only include active vehicles.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by vehicle type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('vehicle_type', $type);
    }
}