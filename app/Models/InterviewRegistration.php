<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewRegistration extends Model
{
    use HasFactory;
    protected $table = "interview_registrations";
    protected $guarded = [];
    
    public function interviewConfiguration()
    {
        return $this->belongsTo(
            InterviewConfiguration::class,
            'interview_config_id',
            'interview_config_id'
        );
    }
}
