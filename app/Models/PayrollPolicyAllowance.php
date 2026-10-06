<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollPolicyAllowance extends Model
{
    use HasFactory;

    protected $table = 'payrollpolicy_allowances';

    // Allow all fields (simple & flexible)
    protected $guarded = [];

    protected $casts = [
        'hra_selected' => 'boolean',
        'conveyance_selected' => 'boolean',
        'medical_selected' => 'boolean',
        'special_selected' => 'boolean',
        'lta_selected' => 'boolean',
        'education_selected' => 'boolean',
        'custom_allowances' => 'array', // JSON -> array
    ];
}
