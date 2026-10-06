<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class InventoryDepreciationLog extends Model
{

    protected $table = 'inventory_depreciation_logs';

    protected $guarded = [];

    protected $casts = [
        'depreciation_date' => 'datetime',
        'book_value_before' => 'decimal:2',
        'book_value_after' => 'decimal:2',
        'depreciation_amount' => 'decimal:2',
        'remaining_life_months' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $dates = [
        'depreciation_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relationships
    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    public function assetInstance()
    {
        return $this->belongsTo(InventoryAssetInstance::class, 'asset_instance_id');
    }

    // Scopes
    public function scopeByItem($query, $itemId)
    {
        return $query->where('item_id', $itemId);
    }

    public function scopeByInstitute($query, $instituteId)
    {
        return $query->where('institute_id', $instituteId);
    }

    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('depreciation_date', [$startDate, $endDate]);
    }

    // Accessors
    public function getFormattedDepreciationAmountAttribute()
    {
        return '₹' . number_format($this->depreciation_amount, 2);
    }

    public function getFormattedBookValueBeforeAttribute()
    {
        return '₹' . number_format($this->book_value_before, 2);
    }

    public function getFormattedBookValueAfterAttribute()
    {
        return '₹' . number_format($this->book_value_after, 2);
    }

    public function getDepreciationMethodTextAttribute()
    {
        $methods = [
            'straight_line' => 'Straight Line',
            'declining_balance' => 'Declining Balance',
            'sum_of_years' => 'Sum of Years',
        ];
        return $methods[$this->depreciation_method] ?? $this->depreciation_method;
    }

    // Helper Methods
    public static function getDepreciationMethods()
    {
        return [
            'straight_line' => 'Straight Line',
            'declining_balance' => 'Declining Balance',
            'sum_of_years' => 'Sum of Years',
        ];
    }
}