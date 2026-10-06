<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $table = 'amenities';

    protected $fillable = [
        'amenity_id',
        'asset_id',
        'institute_id',
        'name',
        'category_id',
        'category_name',
        'specifications',
        'is_active',
        'count',
    ];

    protected $casts = [
        'specifications' => 'array',
        'is_active' => 'boolean',
        'count' => 'integer',
    ];

    /**
     * Get the asset associated with this amenity.
     *
     * Existing relationship preserved.
     */
    public function asset()
    {
        return $this->belongsTo(
            Asset::class,
            'asset_id',
            'asset_id'
        );
    }

    /**
     * Get the category associated with this amenity.
     *
     * Existing relationship preserved.
     */
    public function category()
    {
        return $this->belongsTo(
            AssetCategory::class,
            'category_id',
            'category_id'
        );
    }

    /**
     * Get all units for this amenity.
     *
     * Existing relationship preserved.
     */
    public function units()
    {
        return $this->hasMany(
            AmenityUnit::class,
            'amenity_id'
        );
    }

    /**
     * Get available units for this amenity.
     *
     * Existing relationship preserved.
     */
    public function availableUnits()
    {
        return $this->hasMany(
            AmenityUnit::class,
            'amenity_id'
        )->where('status', 'available');
    }

    /**
     * Scope for active amenities.
     *
     * Existing relationship preserved.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for a specific institute.
     *
     * Existing relationship preserved.
     */
    public function scopeForInstitute($query, $instituteId)
    {
        return $query->where(
            'institute_id',
            $instituteId
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATED FOR ASSET MANAGEMENT
    |--------------------------------------------------------------------------
    | Get all allocation/assignment records belonging to this amenity.
    |
    | IMPORTANT:
    | amenity_assignments.amenity_id stores the business amenity_id,
    | not the numeric amenities.id.
    |--------------------------------------------------------------------------
    */

    public function assignments()
    {
        return $this->hasMany(
            AmenityAssignment::class,
            'amenity_id',
            'amenity_id'
        );
    }
}