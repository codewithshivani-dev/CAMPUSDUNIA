<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class AddBlock extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'add_blocks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'total_floors' => 'integer',
        'total_rooms' => 'integer',
        'total_capacity' => 'integer',
        'total_washrooms' => 'integer',
        'has_lift' => 'boolean',
        'lift_count' => 'integer',
        'has_fire_safety' => 'boolean',
        'has_disabled_access' => 'boolean',
        'has_security_system' => 'boolean',
        'total_area' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'additional_areas' => 'array',
        'gates' => 'array',
        'custom_amenities' => 'array',
        'allocated_amenities' => 'array',
        'allocated_facilities' => 'array',
    ];


    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'active',
        'total_floors' => 0,
        'total_rooms' => 0,
        'total_capacity' => 0,
        'total_washrooms' => 0,
        'has_lift' => false,
        'lift_count' => 0,
        'has_fire_safety' => false,
        'has_disabled_access' => false,
        'has_security_system' => false,
    ];
    public function floors(): HasMany
    {
        return $this->hasMany(AddFloor::class, 'block_id');
    }
    /**
     * Get the institute that owns the block.
     * Assuming there's an Institute model
     */
    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }

    /**
     * Get the branch that owns the block.
     * Assuming there's a Branch model
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get the building that owns the block.
     * Assuming there's a Building model
     */
    public function building()
    {
        return $this->belongsTo(AddBuilding::class, 'building_id');
    }

    /**
     * Scope a query to only include active blocks.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive blocks.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope a query to only include blocks under maintenance.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnderMaintenance($query)
    {
        return $query->where('status', 'under_maintenance');
    }

    /**
     * Scope a query to only include blocks with lifts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithLift($query)
    {
        return $query->where('has_lift', true);
    }

    /**
     * Scope a query to only include blocks with fire safety.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithFireSafety($query)
    {
        return $query->where('has_fire_safety', true);
    }

    /**
     * Scope a query to only include blocks with disabled access.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithDisabledAccess($query)
    {
        return $query->where('has_disabled_access', true);
    }

    /**
     * Scope a query to only include blocks with security system.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithSecuritySystem($query)
    {
        return $query->where('has_security_system', true);
    }

    /**
     * Check if the block is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if the block is inactive.
     *
     * @return bool
     */
    public function isInactive()
    {
        return $this->status === 'inactive';
    }

    /**
     * Check if the block is under maintenance.
     *
     * @return bool
     */
    public function isUnderMaintenance()
    {
        return $this->status === 'under_maintenance';
    }

    /**
     * Activate the block.
     *
     * @return void
     */
    public function activate()
    {
        $this->update(['status' => 'active']);
    }

    /**
     * Deactivate the block.
     *
     * @return void
     */
    public function deactivate()
    {
        $this->update(['status' => 'inactive']);
    }

    /**
     * Set block as under maintenance.
     *
     * @return void
     */
    public function setUnderMaintenance()
    {
        $this->update(['status' => 'under_maintenance']);
    }

    /**
     * Check if block has any accessibility features.
     *
     * @return bool
     */
    public function hasAccessibilityFeatures()
    {
        return $this->has_lift || $this->has_disabled_access;
    }

    /**
     * Check if block has safety features.
     *
     * @return bool
     */
    public function hasSafetyFeatures()
    {
        return $this->has_fire_safety || $this->has_security_system;
    }

    /**
     * Get all safety features as array.
     *
     * @return array
     */
    public function getSafetyFeaturesAttribute()
    {
        $features = [];
        
        if ($this->has_fire_safety) {
            $features[] = 'Fire Safety';
        }
        
        if ($this->has_security_system) {
            $features[] = 'Security System';
        }
        
        return $features;
    }

    /**
     * Get all accessibility features as array.
     *
     * @return array
     */
    public function getAccessibilityFeaturesAttribute()
    {
        $features = [];
        
        if ($this->has_lift) {
            $features[] = 'Lift';
        }
        
        if ($this->has_disabled_access) {
            $features[] = 'Disabled Access';
        }
        
        return $features;
    }

    /**
     * Calculate rooms per floor (average).
     *
     * @return float|null
     */
    public function getRoomsPerFloorAttribute()
    {
        if ($this->total_floors > 0) {
            return round($this->total_rooms / $this->total_floors, 2);
        }
        return null;
    }

    /**
     * Calculate capacity per room (average).
     *
     * @return float|null
     */
    public function getCapacityPerRoomAttribute()
    {
        if ($this->total_rooms > 0) {
            return round($this->total_capacity / $this->total_rooms, 2);
        }
        return null;
    }
}