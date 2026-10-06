<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollPolicyOtherDeduction extends Model
{
    use HasFactory;

    protected $table = 'payrollpolicy_other_deductions';

    // Allow all fields (simple & flexible)
    protected $guarded = [];

    protected $casts = [
        'insurance_selected' => 'boolean',
        'loan_selected' => 'boolean',
        'advance_selected' => 'boolean',
        'custom_deductions' => 'array',
    ];

    public function payrollPolicy()
    {
        return $this->belongsTo(ProvidentFundPolicy::class);
    }
}
