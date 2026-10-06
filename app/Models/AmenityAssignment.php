<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AmenityAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'amenity_assignments';

    /*
    |--------------------------------------------------------------------------
    | Primary Key
    |--------------------------------------------------------------------------
    | Keep assignment_id because the existing Building Management code
    | already uses it as the application's assignment identifier.
    |--------------------------------------------------------------------------
    */

    protected $primaryKey = 'assignment_id';

    public $incrementing = false;

    protected $keyType = 'string';

    /*
    |--------------------------------------------------------------------------
    | UPDATED FOR ASSET MANAGEMENT
    |--------------------------------------------------------------------------
    | Soft deletes are used because allocation/assignment records are
    | historical records and should not be permanently removed.
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | LOCATION ALLOCATION
        |--------------------------------------------------------------------------
        | Used by Building Management / Asset Management
        |--------------------------------------------------------------------------
        */

        'assignment_id',
        'unit_id',
        'amenity_id',

        'assigned_to_type',
        'assigned_to_id',

        'building_id',
        'block_id',
        'floor_id',
        'room_id',

        'assigned_by',
        'assigned_at',

        'status',

        'notes',

        'specifications_snapshot',

        /*
        |--------------------------------------------------------------------------
        | ASSET ASSIGNMENT
        |--------------------------------------------------------------------------
        | Used by Asset Management
        |--------------------------------------------------------------------------
        */

        'assign_to_type',

        /*
         * JSON array.
         *
         * Example:
         *
         * ["EMP-001"]
         *
         * or
         *
         * ["EMP-001", "EMP-002"]
         */

        'assign_to_ids',

        'assign_by',
        'assign_at',

        'assign_status',

        'assign_notes',

        'unassigned_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        /*
        |--------------------------------------------------------------------------
        | LOCATION ALLOCATION
        |--------------------------------------------------------------------------
        */

        'assigned_at' => 'datetime',

        /*
        |--------------------------------------------------------------------------
        | UPDATED FOR ASSET MANAGEMENT
        |--------------------------------------------------------------------------
        | Automatically convert specification snapshots from JSON to array.
        |--------------------------------------------------------------------------
        */

        'specifications_snapshot' => 'array',

        /*
        |--------------------------------------------------------------------------
        | ASSET ASSIGNMENT
        |--------------------------------------------------------------------------
        */


        'assign_at' => 'datetime',

        'unassigned_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Physical asset unit.
     *
     * IMPORTANT:
     * amenity_assignments.unit_id stores amenity_units.unit_id,
     * not amenity_units.id.
     *
     * Existing relationship preserved.
     */
    public function unit()
    {
        return $this->belongsTo(
            AmenityUnit::class,
            'unit_id',
            'unit_id'
        );
    }

    /**
     * Institute asset / amenity.
     *
     * IMPORTANT:
     * amenity_assignments.amenity_id stores amenity_id,
     * not the numeric amenities.id.
     *
     * Existing relationship preserved.
     */
    public function amenity()
    {
        return $this->belongsTo(
            Amenity::class,
            'amenity_id',
            'amenity_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOCATION RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function building()
    {
        return $this->belongsTo(
            AddBuilding::class,
            'building_id',
            'id'
        );
    }

    public function block()
    {
        return $this->belongsTo(
            AddBlock::class,
            'block_id',
            'id'
        );
    }

    public function floor()
    {
        return $this->belongsTo(
            AddFloor::class,
            'floor_id',
            'id'
        );
    }

    public function room()
    {
        return $this->belongsTo(
            AddRooms::class,
            'room_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    */

    /**
     * User who performed the LOCATION ALLOCATION.
     */
    public function assignedBy()
    {
        return $this->belongsTo(
            \App\Models\User::class,
            'assigned_by',
            'id'
        );
    }

    /**
     * User who performed the ASSET ASSIGNMENT.
     */
    public function assignedToUser()
    {
        return $this->belongsTo(
            \App\Models\User::class,
            'assign_by',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOCATION DISPLAY
    |--------------------------------------------------------------------------
    */

    public function getAssignedToDisplayAttribute()
    {
        $type = $this->assigned_to_type;
        $id   = $this->assigned_to_id;

        switch ($type) {

            case 'building':

                return $this->building
                    ? $this->building->name
                    : 'Building #' . $id;

            case 'block':

                return $this->block
                    ? $this->block->name
                    : 'Block #' . $id;

            case 'floor':

                return $this->floor
                    ? $this->floor->floor_number
                    : 'Floor #' . $id;

            case 'room':

                return $this->room
                    ? $this->room->room_number
                    : 'Room #' . $id;

            default:

                return 'Not Allocated';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | COMPLETE LOCATION DISPLAY
    |--------------------------------------------------------------------------
    |
    | Useful for Asset Management.
    |
    */

    public function getLocationDisplayAttribute()
    {
        $parts = [];

        if ($this->building) {
            $parts[] = 'Building: ' . $this->building->name;
        }

        if ($this->block) {
            $parts[] = 'Block: ' . $this->block->name;
        }

        if ($this->floor) {
            $parts[] = 'Floor: ' . $this->floor->floor_number;
        }

        if ($this->room) {
            $parts[] = 'Room: ' . $this->room->room_number;
        }

        return !empty($parts)
            ? implode(' → ', $parts)
            : 'Not Allocated';
    }

    /*
    |--------------------------------------------------------------------------
    | ASSET ASSIGNMENT HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Get assignment type in human-readable format.
     */
    public function getAssignToTypeDisplayAttribute()
    {
        return match ($this->assign_to_type) {

            'department' => 'Department',

            'employee' => 'Employee',

            'student' => 'Student',

            default => 'Not Assigned',
        };
    }

    /**
     * Get assigned ID as a string.
     *
     * assign_to_ids is now a VARCHAR column and stores one ID only.
     * The accessor name is kept for backward compatibility.
     */
    public function getAssignToIdsArrayAttribute()
    {
        return $this->assign_to_ids !== null
            ? (string) $this->assign_to_ids
            : null;
    }

    /**
     * Check whether this unit has an active person assignment.
     */
    public function getIsAssignedAttribute()
    {
        return $this->assign_status === 'assigned'
            && !empty($this->assign_to_ids);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATED FOR ASSET MANAGEMENT
    |--------------------------------------------------------------------------
    | Scope for currently active location allocations.
    |
    | This allows controller queries such as:
    |
    | AmenityAssignment::activeAllocation()->get();
    |--------------------------------------------------------------------------
    */

    public function scopeActiveAllocation($query)
    {
        return $query
            ->where('status', 'active');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATED FOR ASSET MANAGEMENT
    |--------------------------------------------------------------------------
    | Scope for currently assigned units.
    |--------------------------------------------------------------------------
    */

    public function scopeAssignedAssets($query)
    {
        return $query
            ->where('status', 'active')
            ->where('assign_status', 'assigned');
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE ASSIGNMENT ID
    |--------------------------------------------------------------------------
    */

    public static function generateAssignmentId()
    {
        $prefix = 'ASN';

        $timestamp = date('Ymd');

        $random = strtoupper(
            substr(uniqid(), -6)
        );

        $candidate =
            $prefix
            . '-'
            . $timestamp
            . '-'
            . $random;

        while (
            self::where(
                'assignment_id',
                $candidate
            )->exists()
        ) {

            $random = strtoupper(
                substr(uniqid(), -6)
            );

            $candidate =
                $prefix
                . '-'
                . $timestamp
                . '-'
                . $random;
        }

        return $candidate;
    }
}