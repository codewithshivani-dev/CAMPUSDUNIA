<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $table = 'leave_types';

    // protected $fillable = [
    //     'institute_id',
    //     'branch_id',
    //     'leave_type',
    //     'is_custom',
    //     'is_active',
    //     'created_by',
    //     'updated_by'
    // ];

    protected $guarded = [];

    protected $casts = [
        'is_custom' => 'boolean',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function leaveDeduction()
    {
        return $this->hasOne(LeaveDeduction::class, 'leave_type_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scope for institute/branch
    public function scopeForInstitute($query, $instituteId, $branchId = null)
    {
        return $query->where('institute_id', $instituteId)
            ->when($branchId, function ($q) use ($branchId) {
                return $q->where('branch_id', $branchId);
            });
    }

    // Scope for active only
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for custom only
    public function scopeCustom($query)
    {
        return $query->where('is_custom', true);
    }

    // Scope for default only
    public function scopeDefault($query)
    {
        return $query->where('is_custom', false);
    }
}