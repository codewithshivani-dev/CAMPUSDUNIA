<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Services\Inventory\InventoryLogger;

class InventoryReceiptIn extends Model
{
    use SoftDeletes;

    protected $table = 'inventory_receipts_in';

    protected $guarded = [];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'approved_at' => 'datetime',
        'print_count' => 'integer',
        'last_printed_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(InventoryWarehouse::class, 'warehouse_id');
    }

    public function store()
    {
        return $this->belongsTo(InventoryStore::class, 'store_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function reference()
    {
        return $this->morphTo();
    }

    // ==================== PRINT TRACKING ====================

    /**
     * Increment print count and log print action
     */
    public function incrementPrintCount()
    {
        $this->increment('print_count');
        $this->update(['last_printed_at' => now()]);

        InventoryLogger::log([
            'module' => 'RECEIPT_IN',
            'action' => 'PRINT',
            'record_id' => $this->id,
            'new_data' => [
                'receipt_number' => $this->receipt_number,
                'print_count' => $this->print_count + 1,
                'item_name' => $this->item->item_name ?? 'N/A',
                'printed_by' => auth()->user()->name ?? 'System'
            ],
            'remarks' => "Receipt #{$this->receipt_number} printed (Count: " . ($this->print_count + 1) . ")"
        ]);

        return $this;
    }

    /**
     * Get print count
     */
    public function getPrintCountAttribute($value)
    {
        return $value ?? 0;
    }

    // ==================== STATUS METHODS ====================

    public function isDraft()
    {
        return $this->status === 'DRAFT';
    }

    public function isApproved()
    {
        return $this->status === 'APPROVED';
    }

    public function isCompleted()
    {
        return $this->status === 'COMPLETED';
    }

    public function isCancelled()
    {
        return $this->status === 'CANCELLED';
    }

    // ==================== ACTION METHODS ====================

    public function approve()
    {
        if (!$this->isDraft()) {
            throw new \Exception('Only draft receipts can be approved.');
        }

        $this->update([
            'status' => 'APPROVED',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        InventoryLogger::log([
            'module' => 'RECEIPT_IN',
            'action' => 'APPROVE',
            'record_id' => $this->id,
            'remarks' => 'Receipt approved'
        ]);

        return $this;
    }

    public function complete()
    {
        if (!$this->isApproved()) {
            throw new \Exception('Only approved receipts can be completed.');
        }

        \DB::transaction(function () {
            $item = $this->item;
            $item->addStock(
                $this->quantity,
                $this->warehouse_id,
                $this,
                'Receipt In #' . $this->receipt_number
            );

            $this->update([
                'status' => 'COMPLETED',
                'received_by' => auth()->id()
            ]);

            InventoryLogger::log([
                'module' => 'RECEIPT_IN',
                'action' => 'COMPLETE',
                'record_id' => $this->id,
                'new_data' => [
                    'quantity' => $this->quantity,
                    'item' => $this->item->item_name,
                    'warehouse' => $this->warehouse->warehouse_name
                ]
            ]);
        });

        return $this;
    }

    public function cancel()
    {
        $this->update([
            'status' => 'CANCELLED'
        ]);

        InventoryLogger::log([
            'module' => 'RECEIPT_IN',
            'action' => 'CANCEL',
            'record_id' => $this->id,
            'remarks' => 'Receipt cancelled'
        ]);

        return $this;
    }

    // ==================== SCOPES ====================

    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    public function scopeByItem($query, $itemId)
    {
        return $query->where('item_id', $itemId);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'DRAFT');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'APPROVED');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'COMPLETED');
    }

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    // ==================== ACCESSORS ====================

    public function getStatusTextAttribute()
    {
        $statuses = [
            'DRAFT' => 'Draft',
            'APPROVED' => 'Approved',
            'COMPLETED' => 'Completed',
            'CANCELLED' => 'Cancelled'
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'DRAFT' => 'bg-secondary',
            'APPROVED' => 'bg-warning',
            'COMPLETED' => 'bg-success',
            'CANCELLED' => 'bg-danger'
        ];
        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getReceiptTypeTextAttribute()
    {
        $types = [
            'PURCHASE' => 'Purchase',
            'TRANSFER' => 'Transfer',
            'RETURN' => 'Return'
        ];
        return $types[$this->receipt_type] ?? $this->receipt_type;
    }
}