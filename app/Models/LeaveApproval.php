<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApproval extends Model
{
    protected $table = "leave_approvals";

    protected $guarded = [];

    public function leave()
    {
        return $this->belongsTo(EmployeeLeave::class,'leave_id');
    }

    public function approver()
    {
        return $this->belongsTo(EmployeeDetails::class, 'approver_id', 'employee_id');
    }

    public function step()
    {
        return $this->belongsTo(ApprovalStep::class, 'approval_step_id');
    }
}

