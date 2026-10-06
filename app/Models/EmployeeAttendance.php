<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance  extends Model
{
    protected $fillable = ['institute_id','branch_id','employee_id', 'date', 'status', 'total_hours'];

    public function logs()
    {
        return $this->hasMany(EmployeeAttendanceLogs::class, 'attendance_id');
    }
}


