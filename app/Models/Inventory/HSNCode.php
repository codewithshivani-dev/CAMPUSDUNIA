<?php
// app/Models/Inventory/HSNCode.php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HSNCode extends Model
{
    use SoftDeletes;

    protected $table = 'hsn_codes';

    protected $fillable = [
        'hsn_code',
        'description',
        'section',
        'chapter',
        'heading',
        'gst_rate',
        'is_compounded',
        'status'
    ];

    protected $casts = [
        'is_compounded' => 'boolean'
    ];

    // Relationships
    public function categories()
    {
        return $this->hasMany(InventoryCategory::class, 'hsn_code_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByGSTRate($query, $rate)
    {
        return $query->where('gst_rate', $rate);
    }

    // Accessors
    public function getDisplayNameAttribute()
    {
        return $this->hsn_code . ' - ' . $this->description;
    }

    public function getGSTLabelAttribute()
    {
        return $this->gst_rate . '% GST';
    }
}