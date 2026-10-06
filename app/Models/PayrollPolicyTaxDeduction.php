<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollPolicyTaxDeduction extends Model
{
    use HasFactory;

    protected $table = 'payrollpolicy_tax_deductions';

    // Allow all fields (simple & flexible)
    protected $guarded = [];

    protected $casts = [
        'pt_selected' => 'boolean',
        'lst_selected' => 'boolean',
        'tds_selected' => 'boolean',

        'pt_slabs' => 'array',
        'lst_slabs' => 'array',
        'tds_slabs' => 'array',
    ];

    public function payrollPolicy()
    {
        return $this->belongsTo(ProvidentFundPolicy::class);
    }
}
