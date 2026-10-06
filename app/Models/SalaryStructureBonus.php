<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStructureBonus extends Model
{
    use HasFactory;

    protected $table = 'salary_structure_bonuses';

    protected $guarded = [];

    protected $casts = [
        'bonus_value' => 'decimal:2',
        'bonus_value_monthly' => 'decimal:2',
        'bonus_value_annual' => 'decimal:2',
    ];
}
