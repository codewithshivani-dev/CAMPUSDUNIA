<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStructureOvertime extends Model
{
    use HasFactory;

    protected $table = 'salary_structure_overtimes';

    protected $guarded = [];

    protected $casts = [
        'applicable_days' => 'array',
        'rate_value' => 'decimal:2',
        'rate_value_monthly' => 'decimal:2',
        'rate_value_annual' => 'decimal:2',
    ];
}
