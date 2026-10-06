<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewRound extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'interview_rounds';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'desc',
        'institute_id',
        'branch_id',
        'interview_config_id',
        'interview_round_id',
        'type',
        'type_label',
        'panel',
        'round_date',
        'start_time',
        'end_time',
        'duration',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'round_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'panel' => 'integer',
        'duration' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
public function interviewRounds()
{
    return $this->hasMany(
        InterviewRound::class,
        'interview_config_id',
        'interview_config_id'
    );
}
  
}