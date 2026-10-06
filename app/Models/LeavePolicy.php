<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeavePolicy extends Model
{
    protected $fillable = [
        'institute_id',
        'branch_id',
        'policy_id',
        'policy_name',
        'conversion_rules',
        'is_default',
        'active'
    ];

    protected $casts = [
        'conversion_rules' => 'array'
    ];

    // Sample conversion_rules structure:
    // {
    //     "full_day_hours": 8,
    //     "half_day_hours": 4,
    //     "short_leave_hours": 2,
    //     "conversion": {
    //         "half_day_to_full_day": 2,
    //         "short_leave_to_half_day": 2,
    //         "short_leave_to_full_day": 4
    //     },
    //     "carry_forward": {
    //         "enabled": true,
    //         "max_days": 15,
    //         "valid_until": "2024-03-31"
    //     }
    // }

    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id', 'fincap_merchant_id');
    }


    // public function branch()
    // {
    //     return $this->belongsTo(Branch::class);
    // }

    public function leaveBalances()
    {
        return $this->hasMany(EmployeeLeaveBalance::class, 'policy_id', 'policy_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}