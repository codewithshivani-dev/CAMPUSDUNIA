<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class DepartmentShift extends Model
{
    use HasFactory;

    protected $table = 'department_shifts';
    
   protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function shift()
    {
        return $this->belongsTo(Shifts::class, 'shift_id', 'id');
    }


    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'department_id');
    }

    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id');
    }

    
}