<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeave extends Model
{
    protected $table = "employee_leaves";

    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }

    public function approvals()
    {
        return $this->hasMany(LeaveApproval::class, 'leave_id');
    }
}

