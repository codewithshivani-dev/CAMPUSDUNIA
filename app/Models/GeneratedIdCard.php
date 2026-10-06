<?php
// app/Models/GeneratedIdCard.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneratedIdCard extends Model
{
    protected $table = 'generated_id_cards';

    // protected $guarded = [];

    protected $fillable = [
        'employee_id',
        'institute_id',
        'template_id',
        'card_number',
        'pdf_path',
        'qr_code',
        'generated_at',
        'expiry_date',
        'is_active'
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'expiry_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }

    public function template()
    {
        return $this->belongsTo(IdCardTemplate::class);
    }
}