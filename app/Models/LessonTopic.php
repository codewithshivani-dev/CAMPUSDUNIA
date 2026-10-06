<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonTopic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lesson_plan_id', 'topic_number', 'title', 'topics', 'description',
        'video_url', 'resources', 'files',
        'scheduled_date', 'weekday', 'time_slot', 'week_info',
        'covered', 'coverage_status', 'covered_date', 'distribution_data'
    ];

    protected $casts = [
        'resources' => 'array',
        'files' => 'array',
        'distribution_data' => 'array',
        'scheduled_date' => 'date',
        'covered_date' => 'date',
        'covered' => 'boolean',
    ];

    // Relationships
    public function lessonPlan()
    {
        return $this->belongsTo(LessonPlan::class);
    }

    // Accessors
    public function getResourceListAttribute()
    {
        return is_array($this->resources) ? implode(', ', $this->resources) : '';
    }

    public function getFileListAttribute()
    {
        return is_array($this->files) ? $this->files : [];
    }
}