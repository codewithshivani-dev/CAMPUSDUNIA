<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendanceLogs  extends Model
{
    protected $fillable = ['institute_id','branch_id','attendance_id', 'check_type', 'check_time', 'ip_address', 'device_info', 'location'];

    public function attendance()
    {
        return $this->belongsTo(EmployeeAttendance::class, 'attendance_id');
    }
}


