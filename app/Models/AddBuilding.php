<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AddBuilding extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'buildings_page';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded= [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'year_established' => 'integer',
        'total_floors' => 'integer',
        'total_blocks' => 'integer',
        'total_rooms' => 'integer',
        'total_washrooms' => 'integer',
        'area_value' => 'decimal:2',
        'gates' => 'array',
        'additional_areas' => 'array',
        'amenities' => 'array',
        'custom_amenities' => 'array',
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
        'status' => 'active',
        'total_floors' => 0,
        'total_blocks' => 0,
        'total_rooms' => 0,
        'total_washrooms' => 0,
        'area_value' => null,
        'area_unit' => null,
        'gates' => '[]',
        'additional_areas' => '[]',
        'amenities' => '{}',
        'custom_amenities' => '[]',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'total_capacity',
        'age',
    ];

    /**
     * Scope a query to only include active buildings.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive buildings.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Check if the building is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if the building is inactive.
     *
     * @return bool
     */
    public function isInactive()
    {
        return $this->status === 'inactive';
    }

    /**
     * Activate the building.
     *
     * @return void
     */
    public function activate()
    {
        $this->update(['status' => 'active']);
    }

    /**
     * Deactivate the building.
     *
     * @return void
     */
    public function deactivate()
    {
        $this->update(['status' => 'inactive']);
    }

    /**
     * Get the institute that owns the building.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }

    /**
     * Get the branch that owns the building.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

      /**
     * Get the blocks associated with this building.
     */
    public function blocks(): HasMany
    {
        return $this->hasMany(AddBlock::class, 'building_id');
    }

      /**
     * Get the rooms associated with this building through blocks.
     */
    public function rooms(): HasMany
    {
        return $this->hasManyThrough(AddRooms::class, AddBlock::class, 'building_id', 'block_id');
    }

    /**
     * Get the building's total capacity (rooms + washrooms).
     *
     * @return int
     */
    public function getTotalCapacityAttribute()
    {
        return $this->total_rooms + $this->total_washrooms;
    }

    /**
     * Get the building's age based on year established.
     *
     * @return int|null
     */
    public function getAgeAttribute()
    {
        if ($this->year_established) {
            return date('Y') - $this->year_established;
        }
        return null;
    }

    /**
     * Get the formatted total area with unit.
     *
     * @return string|null
     */
    public function getFormattedAreaAttribute()
    {
        if ($this->area_value) {
            $unit = $this->formatUnit($this->area_unit);
            return $this->area_value . ' ' . $unit;
        }
        return null;
    }

    /**
     * Get the number of gates.
     *
     * @return int
     */
    public function getGatesCountAttribute()
    {
        $gates = $this->gates;
        if (is_array($gates)) {
            return count(array_filter($gates, function($gate) {
                return !empty($gate['name']) || !empty($gate['number']);
            }));
        }
        return 0;
    }

    /**
     * Get the number of additional areas.
     *
     * @return int
     */
    public function getAdditionalAreasCountAttribute()
    {
        $areas = $this->additional_areas;
        if (is_array($areas)) {
            return count(array_filter($areas, function($area) {
                return !empty($area['name']);
            }));
        }
        return 0;
    }

    /**
     * Get the number of enabled predefined amenities.
     *
     * @return int
     */
    public function getEnabledAmenitiesCountAttribute()
    {
        $amenities = $this->amenities;
        if (is_array($amenities)) {
            return count(array_filter($amenities, function($amenity) {
                if (is_array($amenity)) {
                    return !empty($amenity['enabled']);
                }
                return !empty($amenity);
            }));
        }
        return 0;
    }

    /**
     * Get the number of custom amenities.
     *
     * @return int
     */
    public function getCustomAmenitiesCountAttribute()
    {
        $customAmenities = $this->custom_amenities;
        if (is_array($customAmenities)) {
            return count(array_filter($customAmenities, function($amenity) {
                return !empty($amenity['name']);
            }));
        }
        return 0;
    }

    /**
     * Get the total amenities count (predefined + custom).
     *
     * @return int
     */
    public function getTotalAmenitiesCountAttribute()
    {
        return $this->enabled_amenities_count + $this->custom_amenities_count;
    }

    /**
     * Set the gates attribute.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setGatesAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['gates'] = $value;
        } else {
            $this->attributes['gates'] = json_encode($value);
        }
    }

    /**
     * Set the additional_areas attribute.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setAdditionalAreasAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['additional_areas'] = $value;
        } else {
            $this->attributes['additional_areas'] = json_encode($value);
        }
    }

    /**
     * Set the amenities attribute.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setAmenitiesAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['amenities'] = $value;
        } else {
            $this->attributes['amenities'] = json_encode($value);
        }
    }

    /**
     * Set the custom_amenities attribute.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setCustomAmenitiesAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['custom_amenities'] = $value;
        } else {
            $this->attributes['custom_amenities'] = json_encode($value);
        }
    }

    /**
     * Format unit code to readable string.
     *
     * @param  string|null  $unit
     * @return string
     */
    private function formatUnit($unit)
    {
        $units = [
            'sq_ft' => 'Sq. Ft.',
            'sq_m' => 'Sq. M.',
            'sq_yd' => 'Sq. Yd.',
            'gaj' => 'Gaj',
            'marla' => 'Marla',
            'kanal' => 'Kanal',
            'acre' => 'Acre',
            'hectare' => 'Hectare',
            'bigha' => 'Bigha',
            'biswa' => 'Biswa',
        ];
        return $units[$unit] ?? $unit ?? '';
    }
}