<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusTopic extends Model
{
    use HasFactory;

    protected $table = 'syllabus_topics';

    // Allow all fields for mass assignment
    protected $guarded = [];
}
