<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReimbursementOtherPolicies extends Model
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
        'min_amount',
        'max_amount',
        'remarks',
        'bill_required',
        'photo_required',
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
        'policy_data',
        'status'
    ];

    protected $casts = [
        'policy_data' => 'array',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
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