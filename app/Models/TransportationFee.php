<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportationFee extends Model
{
    use HasFactory;

    protected $table = 'transport_fees';

    protected $guarded = [];

    protected $casts = [
        'fee_breakdown' => 'array',
        'stop_fees' => 'array',
        'monthly_fee' => 'float',
        'annual_fee' => 'float',
        'late_fee_value' => 'float',
        'partially_fee_value' => 'float'
    ];

    /**
     * Get the route for this fee
     */
    public function route()
    {
        return $this->belongsTo(TransportRoute::class, 'route_id');
    }

    /**
     * Get the bus for this fee
     */
    public function transport()
    {
        return $this->belongsTo(TransportDetails::class, 'transport_id');
    }

    /**
     * Get the institute
     */
    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    /**
     * Get the user who created this fee
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to only include active fees
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Calculate annual fee from monthly fee
     */
    public function setMonthlyFeeAttribute($value)
    {
        $this->attributes['monthly_fee'] = $value;
        $this->attributes['annual_fee'] = $value * 12;
    }
}