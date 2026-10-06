<?php
// app/Models/Inventory/TaxSlab.php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxSlab extends Model
{
    use SoftDeletes;

    protected $table = 'tax_slabs';

    protected $fillable = [
        'institute_id',
        'tax_name',
        'tax_code',
        'tax_type',
        'tax_rate',
        'is_compound',
        'description',
        'status'
    ];

    protected $casts = [
        'tax_rate' => 'decimal:2',
        'is_compound' => 'boolean'
    ];

    // Relationships
    public function institute()
    {
        return $this->belongsTo(\App\Models\Institute::class);
    }

    public function categories()
    {
        return $this->hasMany(InventoryCategory::class, 'tax_slab_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('tax_type', $type);
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return $this->tax_name . ' (' . $this->tax_rate . '%)';
    }

    public function getTaxTypeLabelAttribute()
    {
        $labels = [
            'cgst' => 'CGST',
            'sgst' => 'SGST',
            'igst' => 'IGST',
            'cess' => 'CESS',
            'custom' => 'Custom'
        ];
        return $labels[$this->tax_type] ?? ucfirst($this->tax_type);
    }

    // Helper Methods
    public function getTaxAmount($baseAmount)
    {
        return ($baseAmount * $this->tax_rate) / 100;
    }
}