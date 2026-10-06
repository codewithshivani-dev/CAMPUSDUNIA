<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class AmenityAssignment extends Model
{


    protected $table = 'amenity_assignments';

    protected $primaryKey = 'assignment_id';
    
    public $incrementing = false;
    
    protected $keyType = 'string';

    protected $fillable = [
        'assignment_id',
        'unit_id',
        'amenity_id',
        'assigned_to_type', // 'building', 'block', 'floor', 'room'
        'assigned_to_id',
        'building_id',
        'block_id',
        'floor_id',
        'room_id',
        'assigned_by',
        'assigned_at',
        'status', // 'active', 'inactive', 'pending'
        'notes',
        'specifications_snapshot',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'specifications_snapshot' => 'array',
    ];

    // Relationships
    public function unit()
    {
        return $this->belongsTo(AmenityUnit::class, 'unit_id', 'id');
    }

    public function amenity()
    {
        return $this->belongsTo(Amenity::class, 'amenity_id', 'id');
    }

    public function building()
    {
        return $this->belongsTo(AddBuilding::class, 'building_id', 'id');
    }

    public function block()
    {
        return $this->belongsTo(AddBlock::class, 'block_id', 'id');
    }

    public function floor()
    {
        return $this->belongsTo(AddFloor::class, 'floor_id', 'id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_by', 'id');
    }

    // Helper methods
    public function getAssignedToDisplayAttribute()
    {
        $type = $this->assigned_to_type;
        $id = $this->assigned_to_id;
        
        switch ($type) {
            case 'building':
                return $this->building ? $this->building->name : 'Building #' . $id;
            case 'block':
                return $this->block ? $this->block->name : 'Block #' . $id;
            case 'floor':
                return $this->floor ? $this->floor->floor_number : 'Floor #' . $id;
            case 'room':
                return $this->room ? $this->room->room_number : 'Room #' . $id;
            default:
                return 'Unknown';
        }
    }

    // Generate a unique assignment ID
    public static function generateAssignmentId()
    {
        $prefix = 'ASN';
        $timestamp = date('Ymd');
        $random = strtoupper(substr(uniqid(), -6));
        
        $candidate = $prefix . '-' . $timestamp . '-' . $random;
        
        // Ensure uniqueness
        while (self::where('assignment_id', $candidate)->exists()) {
            $random = strtoupper(substr(uniqid(), -6));
            $candidate = $prefix . '-' . $timestamp . '-' . $random;
        }
        
        return $candidate;
    }
}