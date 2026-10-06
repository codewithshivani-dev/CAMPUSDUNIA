<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'plan_level', 'status',
        'category_id', 'department_id', 'course_type',
        'sub_type', 'academic_year', 'subject_id', 'teacher_id',
        'month', 'year', 'start_date', 'end_date',
        'working_days', 'topics_count', 'chapters_count',
        'syllabus_id', 'syllabus_data',
        'working_days_list', 'distributed_topics'
    ];

    protected $casts = [
        'syllabus_data' => 'array',
        'working_days_list' => 'array',
        'distributed_topics' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relationships
    public function topics()
    {
        return $this->hasMany(LessonTopic::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Employee::class, 'teacher_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Accessors
    public function getPlanTypeAttribute()
    {
        return $this->plan_level === 'day' ? 'Day Plan' : 'Week Plan';
    }

    public function getStatusLabelAttribute()
    {
        return [
            'draft' => 'Draft',
            'active' => 'Active',
        ][$this->status] ?? ucfirst((string) $this->status);
    }
}