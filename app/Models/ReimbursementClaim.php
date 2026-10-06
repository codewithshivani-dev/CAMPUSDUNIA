<?php
// app/Models/ReimbursementClaim.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReimbursementClaim extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'new_reimbursement_claims';

    protected $primaryKey = 'id';

    protected $fillable = [
        // Core identifiers
        'master_request_id',
        'reimbursement_request_id',
        'institute_id',
        'branch_id',

        // Employee details
        'employee_id',
        'name',
        'designation',
        'designation_id',
        'department_id',
        'department',

        // Policy details
        'reimbursement_policy_id',
        'policy_name',
        'policy_category',
        'claim_type',
        'selected_range',
        'range_name',
        'calculation_type',

        // Amounts
        'claim_amount',
        'calculated_amount',
        'approved_amount',

        // Dates
        'expense_date',
        'to_date',
        'submission_date',

        // ========== Travel Fields ==========
        'travel_mode',
        'distance_km',
        'rate_per_km',
        'from_location',
        'to_location',
        'travel_date',
        'travel_provider',

        // ========== Accommodation Fields ==========
        'checkin_date',
        'checkin_time',
        'checkout_date',
        'checkout_time',
        'nights',
        'rate_per_night',
        'hotel_name',
        'includes_food',

        // ========== Food Fields ==========
        'meal_type',
        'meal_date',
        'restaurant_name',
        'item_description',

        // ========== Custom Policy Fields ==========
        'custom_rule_name',
        'custom_rule_type',
        'custom_rate',
        'custom_unit',

        // ========== General Fields ==========
        'bill_number',
        'vendor_name',
        'bill_required',
        'photo_required',
        'remarks',
        'custom_data',
        'units_consumed',
        'rate_per_unit',
        'entry_order',

        // Attachments
        'bill_attachment',
        'bill_attachment_original',
        'photo_attachment',
        'photo_attachment_original',
        'remaining_limit_at_submission',

        // Approval flow
        'status',
        'step1_approver_name',
        'step1_approver_id',
        'step1_status',
        'step1_remarks',
        'step1_approved_at',
        'step2_approver_name',
        'step2_approver_id',
        'step2_status',
        'step2_remarks',
        'step2_approved_at',

        // Settlement
        'settlement_mode',
        'settlement_date',
        'settlement_reference',
        'validation_summary',

        'created_by',
    ];

    protected $casts = [
        'custom_data' => 'array',
        'validation_summary' => 'array',
        'includes_food' => 'boolean',
        'bill_required' => 'boolean',
        'photo_required' => 'boolean',
        'distance_km' => 'decimal:2',
        'rate_per_km' => 'decimal:2',
        'rate_per_night' => 'decimal:2',
        'claim_amount' => 'decimal:2',
        'calculated_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'remaining_limit_at_submission' => 'decimal:2',
        'entry_order' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }

    // public function policy()
    // {
    //     return $this->belongsTo(ReimbursementPolicy::class, 'reimbursement_policy_id', 'reimbursement_policy_id');
    // }

    public function subEntries()
    {
        return $this->hasMany(ReimbursementClaimSubEntry::class, 'reimbursement_request_id', 'reimbursement_request_id');
    }

    public function getBillAttachmentsAttribute()
    {
        if (empty($this->bill_attachment)) {
            return [];
        }
        $paths = json_decode($this->bill_attachment, true) ?? [];
        $originals = json_decode($this->bill_attachment_original, true) ?? [];

        return array_map(function ($path, $index) use ($originals) {
            return [
                'path' => $path,
                'original_name' => $originals[$index] ?? 'Unknown',
            ];
        }, $paths, array_keys($paths));
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo_attachment ? asset('storage/' . $this->photo_attachment) : null;
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function getApprovalFlowAttribute()
    {
        if ($this->step2_approver_name) {
            return '2step';
        }
        return '1step';
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}