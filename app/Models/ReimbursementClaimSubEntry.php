<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReimbursementClaimSubEntry extends Model
{
    use HasFactory;

    protected $table = 'reimbursement_claim_sub_entries';

    protected $fillable = [
        'master_request_id',
        'reimbursement_request_id',
        'reimbursement_claim_id',
        'institute_id',
        'branch_id',
        'employee_id',
        'reimbursement_policy_id',
        'policy_category',
        'sub_entry_data',
    ];

    protected $casts = [
        'sub_entry_data' => 'array',
    ];

    public function claim()
    {
        return $this->belongsTo(ReimbursementClaim::class, 'reimbursement_claim_id');
    }
}
