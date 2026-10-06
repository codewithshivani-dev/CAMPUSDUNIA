<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Designations;

class ReimbursementPolicyAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reimbursement_policy_assignments';

    protected $fillable = [
        'institute_id',
        'branch_id',
        'department_id',
        'designation_id',
        'department_category_id',
        'reimbursement_policy_id',
        'assignment_type',
        'department_name',
        'designation_name',
        'employee_id',
        'name',
        'policy_name',
        'policy_category',
        'policy_details',
        'allowed_ranges',
        'approval_type',
        'approvers',
        'step1_approver',
        'step2_approver',
        'notes',
        'status',
        'approved_at',
        'approved_by',
        'effective_from',
        'effective_until',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'policy_details' => 'array',
        'allowed_ranges' => 'array',
        'approvers' => 'array',
        'approved_at' => 'datetime',
        'effective_from' => 'datetime',
        'effective_until' => 'datetime',
    ];

    public function Designation()
    {
        return $this->belongsTo(Designations::class, 'department_category_id', 'department_category_id');
    }
}