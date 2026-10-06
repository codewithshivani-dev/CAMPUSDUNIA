<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class InventoryReceipt extends Model
{
    protected $table = 'inventory_receipts';

    protected $guarded = [];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(InventoryWarehouse::class, 'warehouse_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reference()
    {
        return $this->morphTo();
    }

    public function scopeIn($query)
    {
        return $query->where('receipt_type', 'IN');
    }

    public function scopeOut($query)
    {
        return $query->where('receipt_type', 'OUT');
    }

    public function scopeTransfer($query)
    {
        return $query->where('receipt_type', 'TRANSFER');
    }
}