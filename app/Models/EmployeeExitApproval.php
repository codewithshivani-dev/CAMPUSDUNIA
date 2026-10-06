<?php
// app/Models/EmployeeExitApproval.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeExitApproval extends Model
{
    

    protected $table = 'employee_exit_approvals';

    protected $fillable = [
        'institute_id',
        'branch_id',
        'exit_id',
        'approval_step_id',
        'approver_id',
        'approver_name',
        'approver_role',
        'status',
        'step_number',
        'total_steps',
        'comments',
        'approved_date'
    ];

    protected $casts = [
        'approved_date' => 'datetime'
    ];

    // Relationships
    public function exit()
    {
        return $this->belongsTo(EmployeeExit::class, 'exit_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'Rejected');
    }

    // Accessor for display status
    public function getDisplayStatusAttribute()
    {
        if ($this->status === 'Pending' && $this->exit && $this->exit->exit_status === 'approved') {
            return 'Already Approved';
        }
        return $this->status;
    }

    public function getDisplayStatusClassAttribute()
    {
        $status = $this->display_status;
        
        $classes = [
            'Pending' => 'pending',
            'Approved' => 'approved',
            'Rejected' => 'rejected',
            'Already Approved' => 'already-approved'
        ];
        
        return $classes[$status] ?? strtolower($status);
    }
}