<?php
// app/Models/LibraryFineRule.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryFineRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'institute_id',
        'branch_id',
        'rule_name',
        'fine_type',
        'frequency',
        'amount',
        'amount_type',
        'grace_period_days',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'amount' => 'decimal:2',
        'grace_period_days' => 'integer'
    ];

    public function institute()
    {
        return $this->belongsTo(InstituteList::class, 'institute_id', 'institute_id');
    }

    public function assessments()
    {
        return $this->hasMany(LibraryFineAssessment::class, 'fine_rule_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('fine_type', $type);
    }
}