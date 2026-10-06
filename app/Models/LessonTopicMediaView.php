<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonTopicMediaView extends Model
{
    protected $fillable = [
        'lesson_topic_id',
        'user_id',
        'student_hash_id',
        'media_type',
        'media_url',
        'media_name',
        'opened_count',
        'first_opened_at',
        'last_opened_at',
    ];

    protected $casts = [
        'first_opened_at' => 'datetime',
        'last_opened_at' => 'datetime',
    ];
}
