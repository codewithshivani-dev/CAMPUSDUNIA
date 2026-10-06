<?php
// app/Models/InterviewConfiguration.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewConfiguration extends Model
{
    use HasFactory;



    protected $table = "interview_configurations";
    protected $guarded = [];

    protected $casts = [
        'venue_building_id' => 'integer',
        'venue_block_id' => 'integer',
        'venue_floor_id' => 'integer',
        'venue_room_id' => 'integer',
    ];

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'department_id');
    }
    public function interviewRounds()
    {
        return $this->hasMany(
            InterviewRound::class,
            'interview_config_id',
            'interview_config_id'
        );
    }

    public function venueBuilding()
    {
        return $this->belongsTo(AddBuilding::class, 'venue_building_id');
    }

    public function venueBlock()
    {
        return $this->belongsTo(AddBlock::class, 'venue_block_id');
    }

    public function venueFloor()
    {
        return $this->belongsTo(AddFloor::class, 'venue_floor_id');
    }

    public function venueRoom()
    {
        return $this->belongsTo(AddRooms::class, 'venue_room_id');
    }
}