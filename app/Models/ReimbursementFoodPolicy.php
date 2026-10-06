<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReimbursementFoodPolicy extends Model
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
        'breakfast_min',
        'breakfast_max',
        'lunch_min',
        'lunch_max',
        'dinner_min',
        'dinner_max',
        'individual_bill_required',
        'individual_photo_required',
        'two_meals_min',
        'two_meals_max',
        'two_meals_bill_required',
        'two_meals_photo_required',
        'three_meals_min',
        'three_meals_max',
        'three_meals_bill_required',
        'three_meals_photo_required',
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
        'breakfast_min' => 'decimal:2',
        'breakfast_max' => 'decimal:2',
        'lunch_min' => 'decimal:2',
        'lunch_max' => 'decimal:2',
        'dinner_min' => 'decimal:2',
        'dinner_max' => 'decimal:2',
        'two_meals_min' => 'decimal:2',
        'two_meals_max' => 'decimal:2',
        'three_meals_min' => 'decimal:2',
        'three_meals_max' => 'decimal:2',
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