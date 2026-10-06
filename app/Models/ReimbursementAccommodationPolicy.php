<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReimbursementAccommodationPolicy extends Model
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
        'basic_min',
        'basic_max',
        'basic_includes_food',
        'basic_bill_required',
        'basic_photo_required',
        'deluxe_min',
        'deluxe_max',
        'deluxe_includes_food',
        'deluxe_bill_required',
        'deluxe_photo_required',
        'premium_min',
        'premium_max',
        'premium_includes_food',
        'premium_bill_required',
        'premium_photo_required',
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
        'basic_min' => 'decimal:2',
        'basic_max' => 'decimal:2',
        'deluxe_min' => 'decimal:2',
        'deluxe_max' => 'decimal:2',
        'premium_min' => 'decimal:2',
        'premium_max' => 'decimal:2',
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