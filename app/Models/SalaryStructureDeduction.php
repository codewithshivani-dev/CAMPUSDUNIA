<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStructureDeduction extends Model
{
    use HasFactory;

    protected $table = 'salary_structure_deductions';

    protected $guarded = [];

    protected $casts = [
        // Booleans
        'pt_selected' => 'boolean',
        'lst_selected' => 'boolean',
        'tds_selected' => 'boolean',
        'insurance_selected' => 'boolean',
        'advance_selected' => 'boolean',

        // Arrays / JSON
        'pt_slabs' => 'array',
        'lst_slabs' => 'array',
        'tds_slabs' => 'array',
        'custom_deductions' => 'array',

        // Decimals
        'pt_value' => 'decimal:2',
        'pt_value_monthly' => 'decimal:2',
        'pt_value_annual' => 'decimal:2',

        'lst_value' => 'decimal:2',
        'lst_value_monthly' => 'decimal:2',
        'lst_value_annual' => 'decimal:2',

        'tds_value' => 'decimal:2',
        'tds_value_monthly' => 'decimal:2',
        'tds_value_annual' => 'decimal:2',

        'insurance_value' => 'decimal:2',
        'insurance_value_monthly' => 'decimal:2',
        'insurance_value_annual' => 'decimal:2',

        'advance_value' => 'decimal:2',
        'advance_value_monthly' => 'decimal:2',
        'advance_value_annual' => 'decimal:2',
    ];
}
