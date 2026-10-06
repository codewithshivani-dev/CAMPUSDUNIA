<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AddFloor extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'add_floors';

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
        // Boolean casts
        'has_ac' => 'boolean',
        'is_central_ac' => 'boolean',
        'has_water_facility' => 'boolean',
        'has_water_cooler' => 'boolean',
        'has_drinking_water' => 'boolean',
        'has_fire_extinguisher' => 'boolean',
        'has_fire_alarm' => 'boolean',
        'has_emergency_exit' => 'boolean',
        'has_lift' => 'boolean',
        'has_washroom' => 'boolean',
        'has_wifi' => 'boolean',
        'has_projector_room' => 'boolean',
        'has_conference_room' => 'boolean',
        'has_library' => 'boolean',
        'has_staff_room' => 'boolean',
        'has_common_room' => 'boolean',
        'has_disabled_access' => 'boolean',
        
        // Integer casts
        'ac_units' => 'integer',
        'water_cooler_count' => 'integer',
        'fire_extinguisher_count' => 'integer',
        'lift_capacity' => 'integer',
        'lift_weight_limit' => 'integer',
        'lift_count' => 'integer',
        'washroom_capacity' => 'integer',
        'washroom_count' => 'integer',
        'total_rooms' => 'integer',
        'occupied_rooms' => 'integer',
        'available_rooms' => 'integer',
        'total_capacity' => 'integer',
        'floor_level' => 'integer',
        
        // Decimal casts
        'total_area' => 'decimal:2',
        'floor_height' => 'decimal:2',
        
        // Enum casts
        'lift_type' => 'string',
        'washroom_type' => 'string',
        'washroom_gender' => 'string',
        'status' => 'string',
        
        // Timestamps
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',

           // JSON casts
        'additional_areas' => 'array',
        'gates' => 'array',
        'custom_amenities' => 'array',
        'allocated_amenities' => 'array',
        'allocated_facility_entries' => 'array',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'active',
        'has_ac' => false,
        'is_central_ac' => false,
        'ac_units' => 0,
        'has_water_facility' => false,
        'has_water_cooler' => false,
        'has_drinking_water' => false,
        'water_cooler_count' => 0,
        'has_fire_extinguisher' => false,
        'has_fire_alarm' => false,
        'has_emergency_exit' => false,
        'fire_extinguisher_count' => 0,
        'has_lift' => false,
        'lift_count' => 0,
        'has_washroom' => false,
        'washroom_count' => 0,
        'has_wifi' => false,
        'has_projector_room' => false,
        'has_conference_room' => false,
        'has_library' => false,
        'has_staff_room' => false,
        'has_common_room' => false,
        'has_disabled_access' => false,
        'total_rooms' => 0,
        'occupied_rooms' => 0,
        'available_rooms' => 0,
        'total_capacity' => 0,
        'total_area' => 0,
    ];

    /**
     * Get the institute that owns the floor.
     */
    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }

    /**
     * Get the branch that owns the floor.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get the building that owns the floor.
     */
    public function building()
    {
        return $this->belongsTo(AddBuilding::class, 'building_id');
    }

    /**
     * Get the block that owns the floor.
     */
    public function block()
    {
        return $this->belongsTo(AddBlock::class, 'block_id');
    }


    public function rooms(): HasMany
    {
        return $this->hasMany(AddRooms::class, 'floor_id');
    }
    /**
     * Scope a query to only include active floors.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive floors.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope a query to only include floors under maintenance.
     */
    public function scopeUnderMaintenance($query)
    {
        return $query->where('status', 'under_maintenance');
    }

    /**
     * Scope a query to only include floors with AC.
     */
    public function scopeWithAc($query)
    {
        return $query->where('has_ac', true);
    }

    /**
     * Scope a query to only include floors with WiFi.
     */
    public function scopeWithWifi($query)
    {
        return $query->where('has_wifi', true);
    }

    /**
     * Scope a query to only include floors with disabled access.
     */
    public function scopeWithDisabledAccess($query)
    {
        return $query->where('has_disabled_access', true);
    }

    /**
     * Scope a query to only include floors with water facility.
     */
    public function scopeWithWaterFacility($query)
    {
        return $query->where('has_water_facility', true);
    }

    /**
     * Scope a query to only include floors with fire safety.
     */
    public function scopeWithFireSafety($query)
    {
        return $query->where(function ($query) {
            $query->where('has_fire_extinguisher', true)
                  ->orWhere('has_fire_alarm', true)
                  ->orWhere('has_emergency_exit', true);
        });
    }

    /**
     * Scope a query to only include ground floors.
     */
    public function scopeGroundFloor($query)
    {
        return $query->where('floor_level', 0);
    }

    /**
     * Scope a query to only include basement floors.
     */
    public function scopeBasement($query)
    {
        return $query->where('floor_level', '<', 0);
    }

    /**
     * Scope a query to only include upper floors.
     */
    public function scopeUpperFloors($query)
    {
        return $query->where('floor_level', '>', 0);
    }

    /**
     * Check if the floor is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if the floor is inactive.
     */
    public function isInactive()
    {
        return $this->status === 'inactive';
    }

    /**
     * Check if the floor is under maintenance.
     */
    public function isUnderMaintenance()
    {
        return $this->status === 'under_maintenance';
    }

    /**
     * Check if the floor is ground floor.
     */
    public function isGroundFloor()
    {
        return $this->floor_level === 0;
    }

    /**
     * Check if the floor is basement.
     */
    public function isBasement()
    {
        return $this->floor_level < 0;
    }

    /**
     * Check if the floor is upper floor.
     */
    public function isUpperFloor()
    {
        return $this->floor_level > 0;
    }

    /**
     * Get floor display name.
     */
    public function getDisplayNameAttribute()
    {
        if ($this->floor_name) {
            return $this->floor_name . " (Floor {$this->floor_number})";
        }
        
        // Convert floor number to display format
        if ($this->floor_level === 0) {
            return "Ground Floor";
        } elseif ($this->floor_level < 0) {
            $level = abs($this->floor_level);
            return "Basement {$level}";
        } else {
            $suffix = $this->getOrdinalSuffix($this->floor_level);
            return $this->floor_level . $suffix . " Floor";
        }
    }

    /**
     * Get ordinal suffix for floor number.
     */
    private function getOrdinalSuffix($number)
    {
        if ($number % 100 >= 11 && $number % 100 <= 13) {
            return 'th';
        }
        
        switch ($number % 10) {
            case 1: return 'st';
            case 2: return 'nd';
            case 3: return 'rd';
            default: return 'th';
        }
    }

    /**
     * Activate the floor.
     */
    public function activate()
    {
        $this->update(['status' => 'active']);
    }

    /**
     * Deactivate the floor.
     */
    public function deactivate()
    {
        $this->update(['status' => 'inactive']);
    }

    /**
     * Set floor as under maintenance.
     */
    public function setUnderMaintenance()
    {
        $this->update(['status' => 'under_maintenance']);
    }

    /**
     * Get occupancy percentage.
     */
    public function getOccupancyPercentageAttribute()
    {
        if ($this->total_rooms > 0) {
            return round(($this->occupied_rooms / $this->total_rooms) * 100, 2);
        }
        return 0;
    }

    /**
     * Get availability percentage.
     */
    public function getAvailabilityPercentageAttribute()
    {
        if ($this->total_rooms > 0) {
            return round(($this->available_rooms / $this->total_rooms) * 100, 2);
        }
        return 0;
    }

    /**
     * Check if floor has any safety features.
     */
    public function hasSafetyFeatures()
    {
        return $this->has_fire_extinguisher || 
               $this->has_fire_alarm || 
               $this->has_emergency_exit;
    }

    /**
     * Get all safety features as array.
     */
    public function getSafetyFeaturesAttribute()
    {
        $features = [];
        
        if ($this->has_fire_extinguisher) {
            $features[] = 'Fire Extinguisher';
        }
        
        if ($this->has_fire_alarm) {
            $features[] = 'Fire Alarm';
        }
        
        if ($this->has_emergency_exit) {
            $features[] = 'Emergency Exit';
        }
        
        return $features;
    }

    /**
     * Get all water facilities as array.
     */
    public function getWaterFacilitiesAttribute()
    {
        $facilities = [];
        
        if ($this->has_water_facility) {
            $facilities[] = 'Water Facility';
        }
        
        if ($this->has_water_cooler) {
            $facilities[] = 'Water Cooler';
        }
        
        if ($this->has_drinking_water) {
            $facilities[] = 'Drinking Water';
        }
        
        return $facilities;
    }

    /**
     * Get all amenities as array.
     */
    public function getAmenitiesAttribute()
    {
        $amenities = [];
        
        if ($this->has_wifi) {
            $amenities[] = 'WiFi';
        }
        
        if ($this->has_projector_room) {
            $amenities[] = 'Projector Room';
        }
        
        if ($this->has_conference_room) {
            $amenities[] = 'Conference Room';
        }
        
        if ($this->has_library) {
            $amenities[] = 'Library';
        }
        
        if ($this->has_staff_room) {
            $amenities[] = 'Staff Room';
        }
        
        if ($this->has_common_room) {
            $amenities[] = 'Common Room';
        }
        
        return $amenities;
    }

    /**
     * Get area per room (average).
     */
    public function getAreaPerRoomAttribute()
    {
        if ($this->total_rooms > 0) {
            return round($this->total_area / $this->total_rooms, 2);
        }
        return 0;
    }

    /**
     * Update available rooms count.
     */
    public function updateAvailableRooms()
    {
        $this->available_rooms = $this->total_rooms - $this->occupied_rooms;
        $this->save();
    }

    /**
     * Increment occupied rooms.
     */
    public function incrementOccupiedRooms($count = 1)
    {
        $this->increment('occupied_rooms', $count);
        $this->updateAvailableRooms();
    }

    /**
     * Decrement occupied rooms.
     */
    public function decrementOccupiedRooms($count = 1)
    {
        $this->decrement('occupied_rooms', $count);
        $this->updateAvailableRooms();
    }

    /**
     * Check if floor has available rooms.
     */
    public function hasAvailableRooms()
    {
        return $this->available_rooms > 0;
    }

    /**
     * Get floor level description.
     */
    public function getFloorLevelDescriptionAttribute()
    {
        if ($this->floor_level === null) {
            return 'Not specified';
        }
        
        if ($this->floor_level === 0) {
            return 'Ground Floor';
        } elseif ($this->floor_level < 0) {
            $level = abs($this->floor_level);
            $suffix = $this->getOrdinalSuffix($level);
            return "Basement {$level}{$suffix}";
        } else {
            $suffix = $this->getOrdinalSuffix($this->floor_level);
            return $this->floor_level . $suffix . ' Floor';
        }
    }
}