<?php
// app/Models/EmployeeSuspensionLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSuspensionLog extends Model
{
    protected $fillable = [
        'employee_id',
        'employee_code',
        'user_id',
        'action',
        'reason',
        'performed_by',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}