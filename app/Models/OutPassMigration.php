<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutPassMigration extends Model
{
    use HasFactory;

    protected $table = 'out_pass_migrations';

    protected $fillable = [
        'institute_id',
        'branch_id',
        'pass_code',
        'pass_type',
        'status',
        'out_date',
        'out_time',
        'purpose',
        'destination',
        'remarks',

        // requester (polymorphic)
        'requester_type',
        'requester_id',

        // vehicle
        'vehicle_number',
        'vehicle_type',
        'vehicle_model',
        'driver_name',
        'driver_contact',
        'driver_address',
        'driver_license',

        // visitor
        'visitor_name',
        'visitor_contact',
        'visitor_address',
        'visitor_id_proof',
        'visitor_company',
        'person_to_meet',

        // approval
        'approved_by',
        'approved_at',
        'approved_ip',
        'rejection_reason',

        // return tracking
        'actual_return_time',

        // meta
        'generated_ip',
        'created_by',
    ];

    protected $casts = [
        'out_date' => 'date',
        'approved_at' => 'datetime',
        'actual_return_time' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // Polymorphic requester (student / employee)
    public function requester()
    {
        return $this->morphTo();
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    public function accompanyPersons()
    {
        return $this->hasMany(OutPassAccompanyPerson::class, 'out_pass_id');
    }

    public function passengers()
    {
        return $this->hasMany(OutPassPassenger::class, 'out_pass_id');
    }

    public function approvals()
    {
        return $this->hasMany(OutPassApproval::class, 'out_pass_id');
    }

    public function history()
    {
        return $this->hasMany(OutPassHistory::class, 'out_pass_id');
    }

    public function pdfGenerations()
    {
        return $this->hasMany(OutPassPdfGeneration::class, 'out_pass_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('out_date', now());
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getStatusBadgeAttribute()
    {
        return [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'cancelled' => 'secondary',
            'used' => 'info',
            'expired' => 'dark',
        ][$this->status] ?? 'light';
    }
}