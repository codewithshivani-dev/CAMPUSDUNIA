<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmenityUnit extends Model
{
    use HasFactory;

    protected $table = 'amenity_units';

    protected $fillable = [
        'unit_id',
        'amenity_id',
        'amenity_asset_id',
        'unit_number',
        'name',
        'specifications',
        'status',
        'assigned_to_block',
        'assigned_to_floor',
        'assigned_to_room',
    ];

    protected $casts = [
        'specifications' => 'array',
    ];

    /**
     * Get the amenity that owns this unit.
     *
     * EXISTING CODE - PRESERVED
     *
     * IMPORTANT:
     * amenity_units.amenity_id references amenities.id.
     */
    public function amenity()
    {
        return $this->belongsTo(
            Amenity::class,
            'amenity_id'
        );
    }

    /**
     * Get the asset associated with this unit.
     *
     * EXISTING CODE - PRESERVED
     *
     * amenity_units.amenity_asset_id stores the Asset business ID
     * such as AST-0011.
     */
    public function asset()
    {
        return $this->belongsTo(
            Asset::class,
            'amenity_asset_id',
            'asset_id'
        );
    }

    /**
     * Scope for available units.
     *
     * EXISTING CODE - PRESERVED
     */
    public function scopeAvailable($query)
    {
        return $query->where(
            'status',
            'available'
        );
    }

    /**
     * Scope for assigned units.
     *
     * EXISTING CODE - PRESERVED
     */
    public function scopeAssigned($query)
    {
        return $query->where(
            'status',
            'assigned'
        );
    }

    /**
     * Scope for maintenance units.
     *
     * EXISTING CODE - PRESERVED
     */
    public function scopeMaintenance($query)
    {
        return $query->where(
            'status',
            'maintenance'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATED FOR ASSET MANAGEMENT
    |--------------------------------------------------------------------------
    |
    | Get all allocation and assignment records belonging to this
    | physical asset unit.
    |
    | IMPORTANT:
    |
    | amenity_assignments.unit_id
    |             ↓
    | amenity_units.unit_id
    |
    | NOT:
    |
    | amenity_assignments.unit_id
    |             ↓
    | amenity_units.id
    |
    | Your database uses the business unit_id value such as:
    |
    | UNI-6A8803B13A3AC
    |
    |--------------------------------------------------------------------------
    */

    public function assignments()
    {
        return $this->hasMany(
            AmenityAssignment::class,
            'unit_id',
            'unit_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATED FOR ASSET MANAGEMENT
    |--------------------------------------------------------------------------
    |
    | Get the current active LOCATION ALLOCATION.
    |
    | Example:
    |
    | Building → Block → Floor → Room
    |
    | The amenity_assignments table stores the allocation history.
    | Only the row having status = active represents the current
    | allocation.
    |
    |--------------------------------------------------------------------------
    */

    public function activeAllocation()
    {
        return $this->hasOne(
            AmenityAssignment::class,
            'unit_id',
            'unit_id'
        )->where(
            'status',
            'active'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATED FOR ASSET MANAGEMENT
    |--------------------------------------------------------------------------
    |
    | Get the current active PERSON / DEPARTMENT / STUDENT assignment.
    |
    | We use:
    |
    | status = active
    | AND
    | assign_status = assigned
    |
    | Student can still exist in the backend.
    | Your frontend can simply hide the Student option.
    |
    |--------------------------------------------------------------------------
    */

    public function activeAssignment()
    {
        return $this->hasOne(
            AmenityAssignment::class,
            'unit_id',
            'unit_id'
        )
        ->where('status', 'active')
        ->where('assign_status', 'assigned');
    }
}