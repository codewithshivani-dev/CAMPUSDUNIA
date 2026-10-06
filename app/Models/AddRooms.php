<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddRooms extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'add_rooms';

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
        'has_fire_extinguisher' => 'boolean',
        'has_fire_alarm' => 'boolean',
        'has_smoke_detector' => 'boolean',
        'has_emergency_exit' => 'boolean',
        'has_washroom' => 'boolean',
        'has_projector' => 'boolean',
        'has_whiteboard' => 'boolean',
        'has_smart_board' => 'boolean',
        'has_wifi' => 'boolean',
        'has_water_cooler' => 'boolean',
        'has_cctv' => 'boolean',
        'has_blackboard' => 'boolean',
        'has_chairs' => 'boolean',
        'has_tables' => 'boolean',
        'has_curtains' => 'boolean',
        'has_blinds' => 'boolean',
        'has_air_purifier' => 'boolean',
        'has_heater' => 'boolean',
        
        // Integer casts
        'capacity' => 'integer',
        'lights_count' => 'integer',
        'fans_count' => 'integer',
        'ac_count' => 'integer',
        'sockets_count' => 'integer',
        'washroom_capacity' => 'integer',
        
        // Decimal casts
        'area' => 'decimal:2',
        
        // Date casts
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        
        // Enum casts
        'room_type' => 'string',
        'area_type' => 'string',
        'washroom_gender' => 'string',
        'status' => 'string',
        'occupancy_status' => 'string',

        'store_rooms' => 'array',
        'selected_floor_amenities' => 'array',
        'selected_floor_facilities' => 'array',
        'room_specifications' => 'array',
        'additional_areas' => 'array',
        'gates' => 'array',
        'custom_amenities' => 'array',
        'allocated_amenities' => 'array',
        'balcony_units'              => 'array',
        
        // String casts (for has_ac which is string in migration)
        'has_ac' => 'string',
        
        // Timestamps
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'room_type' => 'classroom',
        'area_type' => 'super',
        'status' => 'active',
        'occupancy_status' => 'vacant',
        'lights_count' => 0,
        'fans_count' => 0,
        'ac_count' => 0,
        'sockets_count' => 0,
        'has_fire_extinguisher' => false,
        'has_fire_alarm' => false,
        'has_smoke_detector' => false,
        'has_emergency_exit' => false,
        'has_washroom' => false,
        'has_projector' => false,
        'has_whiteboard' => false,
        'has_smart_board' => false,
        'has_wifi' => false,
        'has_water_cooler' => false,
        'has_cctv' => false,
        'has_blackboard' => false,
        'has_chairs' => false,
        'has_tables' => false,
        'has_curtains' => false,
        'has_blinds' => false,
        'has_air_purifier' => false,
        'has_heater' => false,
    ];

    /**
     * Get the institute that owns the room.
     */
    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }

    /**
     * Get the branch that owns the room.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get the building that owns the room.
     */
    public function building()
    {
        return $this->belongsTo(AddBuilding::class, 'building_id');
    }

    /**
     * Get the block that owns the room.
     */
    public function block()
    {
        return $this->belongsTo(AddBlock::class, 'block_id');
    }

    /**
     * Get the floor that owns the room.
     */
    public function floor()
    {
        return $this->belongsTo(AddFloor::class, 'floor_id');
    }
   
    /**
     * Scope a query to only include active rooms.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive rooms.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope a query to only include rooms under maintenance.
     */
    public function scopeUnderMaintenance($query)
    {
        return $query->where('status', 'under_maintenance');
    }

    /**
     * Scope a query to only include rooms under renovation.
     */
    public function scopeRenovation($query)
    {
        return $query->where('status', 'renovation');
    }

    /**
     * Scope a query to only include vacant rooms.
     */
    public function scopeVacant($query)
    {
        return $query->where('occupancy_status', 'vacant');
    }

    /**
     * Scope a query to only include occupied rooms.
     */
    public function scopeOccupied($query)
    {
        return $query->where('occupancy_status', 'occupied');
    }

    /**
     * Scope a query to only include partially occupied rooms.
     */
    public function scopePartiallyOccupied($query)
    {
        return $query->where('occupancy_status', 'partially_occupied');
    }

    /**
     * Scope a query to only include classrooms.
     */
    public function scopeClassrooms($query)
    {
        return $query->where('room_type', 'classroom');
    }

    /**
     * Scope a query to only include labs.
     */
    public function scopeLabs($query)
    {
        return $query->where('room_type', 'lab');
    }

    /**
     * Scope a query to only include offices.
     */
    public function scopeOffices($query)
    {
        return $query->where('room_type', 'office');
    }

    /**
     * Scope a query to only include conference rooms.
     */
    public function scopeConferenceRooms($query)
    {
        return $query->where('room_type', 'conference');
    }

    /**
     * Scope a query to only include libraries.
     */
    public function scopeLibraries($query)
    {
        return $query->where('room_type', 'library');
    }

    /**
     * Scope a query to only include rooms with AC.
     */
    public function scopeWithAc($query)
    {
        return $query->where('ac_count', '>', 0)->orWhere('has_ac', 'yes');
    }

    /**
     * Scope a query to only include rooms with WiFi.
     */
    public function scopeWithWifi($query)
    {
        return $query->where('has_wifi', true);
    }

    /**
     * Scope a query to only include rooms with projectors.
     */
    public function scopeWithProjector($query)
    {
        return $query->where('has_projector', true);
    }

    /**
     * Scope a query to only include rooms with smart boards.
     */
    public function scopeWithSmartBoard($query)
    {
        return $query->where('has_smart_board', true);
    }

    /**
     * Scope a query to only include rooms with washrooms.
     */
    public function scopeWithWashroom($query)
    {
        return $query->where('has_washroom', true);
    }

    /**
     * Scope a query to only include rooms with fire safety.
     */
    public function scopeWithFireSafety($query)
    {
        return $query->where(function ($query) {
            $query->where('has_fire_extinguisher', true)
                  ->orWhere('has_fire_alarm', true)
                  ->orWhere('has_smoke_detector', true);
        });
    }

    /**
     * Check if the room is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if the room is inactive.
     */
    public function isInactive()
    {
        return $this->status === 'inactive';
    }

    /**
     * Check if the room is under maintenance.
     */
    public function isUnderMaintenance()
    {
        return $this->status === 'under_maintenance';
    }

    /**
     * Check if the room is under renovation.
     */
    public function isUnderRenovation()
    {
        return $this->status === 'renovation';
    }

    /**
     * Check if the room is vacant.
     */
    public function isVacant()
    {
        return $this->occupancy_status === 'vacant';
    }

    /**
     * Check if the room is occupied.
     */
    public function isOccupied()
    {
        return $this->occupancy_status === 'occupied';
    }

    /**
     * Check if the room is partially occupied.
     */
    public function isPartiallyOccupied()
    {
        return $this->occupancy_status === 'partially_occupied';
    }

    /**
     * Get room display name.
     */
    public function getDisplayNameAttribute()
    {
        if ($this->room_name) {
            return $this->room_name . " ({$this->room_number})";
        }
        return "Room {$this->room_number}";
    }

    /**
     * Get full room location.
     */
    public function getFullLocationAttribute()
    {
        $location = [];
        
        if ($this->building) {
            $location[] = $this->building->name;
        }
        
        if ($this->block) {
            $location[] = $this->block->name;
        }
        
        if ($this->floor) {
            $location[] = $this->floor->display_name;
        }
        
        $location[] = $this->display_name;
        
        return implode(' - ', $location);
    }

    /**
     * Get room type display name.
     */
    public function getRoomTypeDisplayAttribute()
    {
        if ($this->custom_room_type) {
            return ucfirst($this->custom_room_type);
        }
        
        return match($this->room_type) {
            'classroom' => 'Classroom',
            'lab' => 'Laboratory',
            'office' => 'Office',
            'conference' => 'Conference Room',
            'library' => 'Library',
            'other' => 'Other',
            default => ucfirst($this->room_type),
        };
    }

    /**
     * Get area type display name.
     */
    public function getAreaTypeDisplayAttribute()
    {
        return match($this->area_type) {
            'super' => 'Super Area',
            'carpet' => 'Carpet Area',
            default => ucfirst($this->area_type),
        };
    }

    /**
     * Activate the room.
     */
    public function activate()
    {
        $this->update(['status' => 'active']);
    }

    /**
     * Deactivate the room.
     */
    public function deactivate()
    {
        $this->update(['status' => 'inactive']);
    }

    /**
     * Set room as under maintenance.
     */
    public function setUnderMaintenance()
    {
        $this->update(['status' => 'under_maintenance']);
    }

    /**
     * Set room as under renovation.
     */
    public function setUnderRenovation()
    {
        $this->update(['status' => 'renovation']);
    }

    /**
     * Mark room as vacant.
     */
    public function markAsVacant()
    {
        $this->update(['occupancy_status' => 'vacant']);
    }

    /**
     * Mark room as occupied.
     */
    public function markAsOccupied()
    {
        $this->update(['occupancy_status' => 'occupied']);
    }

    /**
     * Mark room as partially occupied.
     */
    public function markAsPartiallyOccupied()
    {
        $this->update(['occupancy_status' => 'partially_occupied']);
    }

    /**
     * Get total electrical points.
     */
    public function getTotalElectricalPointsAttribute()
    {
        return $this->lights_count + 
               $this->fans_count + 
               $this->ac_count + 
               $this->sockets_count;
    }

    /**
     * Check if room has AC.
     */
    public function hasAc()
    {
        return $this->ac_count > 0 || $this->has_ac === 'yes';
    }

    /**
     * Check if room has basic electrical.
     */
    public function hasBasicElectrical()
    {
        return $this->lights_count > 0 && $this->sockets_count > 0;
    }

    /**
     * Check if room has fire safety features.
     */
    public function hasFireSafety()
    {
        return $this->has_fire_extinguisher || 
               $this->has_fire_alarm || 
               $this->has_smoke_detector;
    }

    /**
     * Get all fire safety features as array.
     */
    public function getFireSafetyFeaturesAttribute()
    {
        $features = [];
        
        if ($this->has_fire_extinguisher) {
            $features[] = 'Fire Extinguisher';
        }
        
        if ($this->has_fire_alarm) {
            $features[] = 'Fire Alarm';
        }
        
        if ($this->has_smoke_detector) {
            $features[] = 'Smoke Detector';
        }
        
        if ($this->has_emergency_exit) {
            $features[] = 'Emergency Exit';
        }
        
        return $features;
    }

    /**
     * Get all amenities as array.
     */
    public function getAmenitiesAttribute()
    {
        $amenities = [];
        
        // Teaching amenities
        if ($this->has_projector) {
            $amenities[] = 'Projector';
        }
        
        if ($this->has_whiteboard) {
            $amenities[] = 'Whiteboard';
        }
        
        if ($this->has_smart_board) {
            $amenities[] = 'Smart Board';
        }
        
        if ($this->has_blackboard) {
            $amenities[] = 'Blackboard';
        }
        
        // Comfort amenities
        if ($this->hasAc()) {
            $amenities[] = 'Air Conditioning';
        }
        
        if ($this->has_wifi) {
            $amenities[] = 'WiFi';
        }
        
        if ($this->has_water_cooler) {
            $amenities[] = 'Water Cooler';
        }
        
        if ($this->has_air_purifier) {
            $amenities[] = 'Air Purifier';
        }
        
        if ($this->has_heater) {
            $amenities[] = 'Heater';
        }
        
        // Furniture
        if ($this->has_chairs) {
            $amenities[] = 'Chairs';
        }
        
        if ($this->has_tables) {
            $amenities[] = 'Tables';
        }
        
        // Window treatments
        if ($this->has_curtains) {
            $amenities[] = 'Curtains';
        }
        
        if ($this->has_blinds) {
            $amenities[] = 'Blinds';
        }
        
        // Security
        if ($this->has_cctv) {
            $amenities[] = 'CCTV';
        }
        
        // Washroom
        if ($this->has_washroom) {
            $washroomType = $this->washroom_gender ? 
                ucfirst($this->washroom_gender) . ' Washroom' : 
                'Washroom';
            $amenities[] = $washroomType;
        }
        
        return $amenities;
    }

    /**
     * Get amenities grouped by category.
     */
    public function getAmenitiesByCategoryAttribute()
    {
        return [
            'teaching' => array_filter($this->amenities, function($amenity) {
                return in_array($amenity, ['Projector', 'Whiteboard', 'Smart Board', 'Blackboard']);
            }),
            'comfort' => array_filter($this->amenities, function($amenity) {
                return in_array($amenity, ['Air Conditioning', 'WiFi', 'Water Cooler', 'Air Purifier', 'Heater']);
            }),
            'furniture' => array_filter($this->amenities, function($amenity) {
                return in_array($amenity, ['Chairs', 'Tables', 'Curtains', 'Blinds']);
            }),
            'security' => array_filter($this->amenities, function($amenity) {
                return in_array($amenity, ['CCTV']);
            }),
            'facilities' => array_filter($this->amenities, function($amenity) {
                return in_array($amenity, ['Male Washroom', 'Female Washroom', 'Unisex Washroom', 'Washroom']);
            }),
        ];
    }

    /**
     * Get area per person.
     */
    public function getAreaPerPersonAttribute()
    {
        if ($this->capacity && $this->area && $this->capacity > 0) {
            return round($this->area / $this->capacity, 2);
        }
        return null;
    }

    /**
     * Check if room is suitable for given capacity.
     */
    public function isSuitableForCapacity($requiredCapacity)
    {
        if (!$this->capacity) {
            return false;
        }
        return $this->capacity >= $requiredCapacity;
    }

    /**
     * Check if maintenance is due.
     */
    public function isMaintenanceDue()
    {
        if (!$this->next_maintenance_date) {
            return false;
        }
        return now()->greaterThanOrEqualTo($this->next_maintenance_date);
    }

    /**
     * Get days until next maintenance.
     */
    public function getDaysUntilMaintenanceAttribute()
    {
        if (!$this->next_maintenance_date) {
            return null;
        }
        
        return now()->diffInDays($this->next_maintenance_date, false);
    }

    /**
     * Schedule next maintenance.
     */
    public function scheduleNextMaintenance($days = 30)
    {
        $this->update([
            'last_maintenance_date' => now(),
            'next_maintenance_date' => now()->addDays($days),
        ]);
    }

    /**
     * Complete maintenance.
     */
    public function completeMaintenance($notes = null)
    {
        $this->update([
            'last_maintenance_date' => now(),
            'next_maintenance_date' => now()->addDays(30), // default 30 days
            'maintenance_notes' => $notes,
        ]);
    }

    /**
     * Get room suitability score (0-100).
     */
    public function getSuitabilityScoreAttribute()
    {
        $score = 0;
        $maxScore = 20;
        
        // Electrical (5 points)
        if ($this->hasBasicElectrical()) $score += 2;
        if ($this->lights_count >= 2) $score += 1;
        if ($this->sockets_count >= 2) $score += 1;
        if ($this->hasAc()) $score += 1;
        
        // Safety (5 points)
        if ($this->has_fire_extinguisher) $score += 2;
        if ($this->has_fire_alarm) $score += 1;
        if ($this->has_smoke_detector) $score += 1;
        if ($this->has_emergency_exit) $score += 1;
        
        // Amenities (5 points)
        if ($this->has_wifi) $score += 1;
        if ($this->has_whiteboard || $this->has_blackboard) $score += 1;
        if ($this->has_smart_board) $score += 2;
        if ($this->has_projector) $score += 1;
        
        // Facilities (5 points)
        if ($this->has_washroom) $score += 2;
        if ($this->has_water_cooler) $score += 1;
        if ($this->has_chairs && $this->has_tables) $score += 2;
        
        return round(($score / $maxScore) * 100, 2);
    }

    /**
     * Check if room is suitable for given room type.
     */
    public function isSuitableForRoomType($roomType)
    {
        $requirements = match($roomType) {
            'classroom' => ['has_whiteboard', 'has_chairs', 'has_tables'],
            'lab' => ['has_sockets_count>=4', 'has_washroom'],
            'office' => ['has_wifi', 'has_ac', 'has_cctv'],
            'conference' => ['has_projector', 'has_ac', 'has_wifi'],
            'library' => ['has_wifi', 'has_ac', 'has_chairs'],
            default => [],
        };
        
        foreach ($requirements as $requirement) {
            if (str_contains($requirement, '>=')) {
                [$field, $value] = explode('>=', $requirement);
                if ($this->$field < $value) {
                    return false;
                }
            } elseif (!$this->$requirement) {
                return false;
            }
        }
        
        return true;
    }
}