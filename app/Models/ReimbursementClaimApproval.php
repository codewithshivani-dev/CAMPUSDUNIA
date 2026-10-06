<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReimbursementClaimApproval extends Model
{
    protected $table = 'reimbursement_claim_approval_details';

    protected $fillable = [
        'master_request_id',
        'reimbursement_request_id',
        'reimbursement_claim_id',
        'institute_id',
        'branch_id',
        'employee_id',
        'status',
        'step1_status',
        'step1_approver_id',
        'step1_approver_name',
        'step1_remarks',
        'step1_approved_at',
        'step2_status',
        'step2_approver_id',
        'step2_approver_name',
        'step2_remarks',
        'step2_approved_at',
        'approved_amounts',
        'total_approved_amount',
        'decline_reason',
        'settlement_mode',
        'payout_amount',
        'payout_reference',
        'payout_date',
        'settlement_status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'approved_amounts' => 'array',
        'step1_approved_at' => 'datetime',
        'step2_approved_at' => 'datetime',
        'payout_date' => 'datetime',
        'total_approved_amount' => 'decimal:2',
        'payout_amount' => 'decimal:2',
    ];

    public function claim()
    {
        return $this->belongsTo(ReimbursementClaim::class, 'reimbursement_claim_id');
    }
}