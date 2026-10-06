<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSubjectLecture extends Model
{
    protected $table = 'employee_subject_lectures';
    protected $guarded = [
    ];

    protected $casts = [
        'valid_from'   => 'date',
        'valid_to'     => 'date',
        'days_of_week' => 'array',
    ];

    public function assignment()
    {
        return $this->belongsTo(AssignSubjectsToEmployee::class, 'emp_assign_subject_id');
    }

    public function building()
    {
        return $this->belongsTo(AddBuilding::class, 'building_id');
    }

    public function block()
    {
        return $this->belongsTo(AddBlock::class, 'block_id');
    }

    public function floor()
    {
        return $this->belongsTo(AddFloor::class, 'floor_id');
    }

    public function room()
    {
        return $this->belongsTo(AddRooms::class, 'room_id');
    }

    /**
     * Generate combined location name from location IDs
     */
    public function getFullLocationAttribute()
    {
        $parts = [];
        
        if ($this->building) {
            $parts[] = $this->building->name;
        }
        
        if ($this->floor) {
            $parts[] = $this->floor->floor_name;
        }
        
        if ($this->room) {
            $parts[] = $this->room->room_name;
        }
        
        return implode(' - ', $parts);
    }
}
