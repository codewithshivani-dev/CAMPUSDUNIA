<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReimbursementTravelPolicy extends Model
{
    use HasFactory;

    protected $fillable = [
        'reimbursement_policy_id',
        'institute_id',
        'branch_id',
        'policy_name',
        'policy_category',
        'description',
        'policy_data',
        'two_wheeler_min',
        'two_wheeler_max',
        'two_wheeler_rate_km',
        'two_wheeler_bill_required',
        'two_wheeler_photo_required',
        'car_min',
        'car_max',
        'car_rate_km',
        'car_bill_required',
        'car_photo_required',
        'auto_min',
        'auto_max',
        'auto_rate_km',
        'auto_bill_required',
        'auto_photo_required',
        'bus_categories',
        'train_categories',
        'flight_categories',
        'frequency_type',
        'frequency_value',
        'submission_within',
        'submission_type',
        'financial_year',
        'calculation_type',
        'effective_from',
        'effective_to',
        'allow_actual_amount',
        'allow_multi_bills',
        'max_bills',
        'allow_same_bill',
        'allow_weekend',
        'allow_holiday',
        'remarks_mandatory',
        'bill_mandatory',
        'vendor_mandatory',
        'settlement_timeline',
        'settlement_mode',
        'auto_settlement',
        'allow_partial_settlement',
        'status'
    ];

    protected $casts = [
        'policy_data' => 'array',
        'two_wheeler_min' => 'decimal:2',
        'two_wheeler_max' => 'decimal:2',
        'two_wheeler_rate_km' => 'decimal:2',
        'car_min' => 'decimal:2',
        'car_max' => 'decimal:2',
        'car_rate_km' => 'decimal:2',
        'auto_min' => 'decimal:2',
        'auto_max' => 'decimal:2',
        'auto_rate_km' => 'decimal:2',
        'bus_min' => 'decimal:2',
        'bus_max' => 'decimal:2',
        'train_min' => 'decimal:2',
        'train_max' => 'decimal:2',
        'flight_min' => 'decimal:2',
        'flight_max' => 'decimal:2',
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}