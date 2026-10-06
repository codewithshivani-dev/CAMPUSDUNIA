<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = [
        'institute_id',
        'title',
        'content',
        'status',
        'department_category_id',
        'department_id',
        'recipient_type',
        'department',
        'notice_type',
        'attachment',
    ];

    protected $casts = [
        'students' => 'boolean',
        'employees' => 'boolean',
    ];
}
