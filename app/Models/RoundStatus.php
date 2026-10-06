<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoundStatus extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'round_status';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [


        'institute_id',
        'branch_id',
        'reference_id',
        'lead_id',
        'name',
        'round_status',
        'desc',
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
        'lifecycle_status',
        'started_at',
        'completed_at',
        'rescheduled_from',
        'rescheduled_at',
        'marks',
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
        ,
        'started_at' => 'datetime'
        ,
        'completed_at' => 'datetime'
        ,
        'rescheduled_at' => 'datetime'
    ];

    public function lead()
    {
        return $this->belongsTo(InterviewLead::class, 'lead_id');
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}