<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStructureAllowances extends Model
{
    use HasFactory;

    protected $table = 'salary_structure_allowances';

    protected $guarded = [];

    protected $casts = [
        'custom_allowances' => 'array',
    ];
}
