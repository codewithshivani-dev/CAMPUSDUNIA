<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanSchemaConfiguration extends Model
{
    use HasFactory;

    protected $table = 'loan_schema_configuration';

    protected $guarded = [];

    protected $casts = [
        'tenure_date' => 'date',
        'subvention_rate' => 'decimal:4',
        'roi_rate' => 'decimal:4',
        'processing_fee_percent' => 'decimal:4',
        'processing_fee_amount' => 'decimal:2'
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
}