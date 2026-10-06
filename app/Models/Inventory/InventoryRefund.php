<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class InventoryRefund extends Model
{
    protected $table = 'inventory_refunds';

    protected $guarded = [];

    protected $casts = [
        'refund_amount' => 'decimal:2',
        'refund_items' => 'array',
        'processed_at' => 'datetime',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
        'metadata' => 'array',
    ];

    // ==================== STATUSES ====================
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_APPROVED = 'approved';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_FAILED = 'failed';

    // ==================== REFUND METHODS ====================
    const METHOD_CASH = 'cash';
    const METHOD_BANK_TRANSFER = 'bank_transfer';
    const METHOD_CHEQUE = 'cheque';
    const METHOD_UPI = 'upi';
    const METHOD_ONLINE = 'online';
    const METHOD_CARD = 'card';
    const METHOD_WALLET = 'wallet';

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_FAILED => 'Failed',
        ];
    }

    public static function getMethods()
    {
        return [
            self::METHOD_CASH => 'Cash',
            self::METHOD_BANK_TRANSFER => 'Bank Transfer',
            self::METHOD_CHEQUE => 'Cheque',
            self::METHOD_UPI => 'UPI',
            self::METHOD_ONLINE => 'Online Transfer',
            self::METHOD_CARD => 'Card',
            self::METHOD_WALLET => 'Wallet',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function transaction()
    {
        return $this->belongsTo(InventoryStockOut::class, 'transaction_id');
    }

    /**
     * Get the receipt that this refund belongs to
     */
    public function receiptOut()
    {
        return $this->belongsTo(InventoryReceiptOut::class, 'receipt_out_id');
    }

    /**
     * Get the stock movements for this refund
     */
    public function stockMovements()
    {
        return $this->morphMany(InventoryStockMovement::class, 'reference');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function canceller()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    // ==================== ACTION METHODS ====================

    /**
     * Process the refund
     */
    public function process()
    {
        if ($this->status !== self::STATUS_PENDING) {
            throw new \Exception('Only pending refunds can be processed.');
        }

        $this->update([
            'status' => self::STATUS_PROCESSING,
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        // Log the action
        \App\Services\Inventory\InventoryLogger::log([
            'module' => 'REFUND',
            'action' => 'PROCESS',
            'record_id' => $this->id,
            'new_data' => ['status' => self::STATUS_PROCESSING],
            'remarks' => "Refund #{$this->refund_code} is being processed"
        ]);

        return $this;
    }

    /**
     * Approve the refund
     */
    public function approve()
    {
        if (!in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROCESSING])) {
            throw new \Exception('Only pending or processing refunds can be approved.');
        }

        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        \App\Services\Inventory\InventoryLogger::log([
            'module' => 'REFUND',
            'action' => 'APPROVE',
            'record_id' => $this->id,
            'new_data' => ['status' => self::STATUS_APPROVED],
            'remarks' => "Refund #{$this->refund_code} approved"
        ]);

        return $this;
    }

    /**
     * Complete the refund
     */
    public function complete()
    {
        if ($this->status !== self::STATUS_APPROVED) {
            throw new \Exception('Only approved refunds can be completed.');
        }

        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        // Reverse stock if not already reversed
        if ($this->refund_items && !$this->stock_reversed) {
            $this->reverseStock();
        }

        \App\Services\Inventory\InventoryLogger::log([
            'module' => 'REFUND',
            'action' => 'COMPLETE',
            'record_id' => $this->id,
            'new_data' => ['status' => self::STATUS_COMPLETED],
            'remarks' => "Refund #{$this->refund_code} completed"
        ]);

        return $this;
    }

    /**
     * Reverse stock for the refund
     */
    public function reverseStock()
    {
        if ($this->stock_reversed) {
            throw new \Exception('Stock has already been reversed for this refund.');
        }

        if (!$this->refund_items) {
            throw new \Exception('No items specified for stock reversal.');
        }

        $transaction = $this->transaction;
        if (!$transaction) {
            throw new \Exception('Transaction not found for stock reversal.');
        }

        $items = is_array($this->refund_items) ? $this->refund_items : json_decode($this->refund_items, true);

        foreach ($items as $itemData) {
            $item = InventoryItem::where('institute_id', $this->institute_id)
                ->where('id', $itemData['id'])
                ->first();

            if ($item) {
                $oldStock = $item->current_stock;
                $quantity = $itemData['quantity'] ?? 0;

                // Determine location
                $warehouseId = $transaction->from_warehouse_id ?? $transaction->to_warehouse_id;
                $storeId = $transaction->from_store_id ?? $transaction->to_store_id;

                // Update stock
                $item->current_stock += $quantity;
                $item->available_stock += $quantity;
                $item->save();

                // Create stock movement
                InventoryStockMovement::create([
                    'institute_id' => $this->institute_id,
                    'item_id' => $item->id,
                    'warehouse_id' => $warehouseId,
                    'store_id' => $storeId,
                    'movement_type' => 'IN',
                    'quantity' => $quantity,
                    'previous_stock' => $oldStock,
                    'new_stock' => $item->current_stock,
                    'unit_cost' => $item->buying_price ?? 0,
                    'total_cost' => ($item->buying_price ?? 0) * $quantity,
                    'reference_type' => self::class,
                    'reference_id' => $this->id,
                    'notes' => 'Stock reversal from refund #' . $this->refund_code,
                    'created_by' => auth()->id(),
                ]);

                // Update location utilization
                if ($warehouseId) {
                    $warehouse = InventoryWarehouse::find($warehouseId);
                    if ($warehouse) {
                        $warehouse->updateUtilization();
                    }
                }
                if ($storeId) {
                    $store = InventoryStore::find($storeId);
                    if ($store) {
                        $store->updateUtilization();
                    }
                }
            }
        }

        $this->update(['stock_reversed' => true]);

        \App\Services\Inventory\InventoryLogger::log([
            'module' => 'REFUND',
            'action' => 'STOCK_REVERSED',
            'record_id' => $this->id,
            'new_data' => ['stock_reversed' => true],
            'remarks' => "Stock reversed for refund #{$this->refund_code}"
        ]);

        return $this;
    }

    /**
     * Reject the refund
     */
    public function reject($reason = null)
    {
        if (!in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROCESSING])) {
            throw new \Exception('Only pending or processing refunds can be rejected.');
        }

        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejection_reason' => $reason,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        \App\Services\Inventory\InventoryLogger::log([
            'module' => 'REFUND',
            'action' => 'REJECT',
            'record_id' => $this->id,
            'new_data' => ['status' => self::STATUS_REJECTED, 'reason' => $reason],
            'remarks' => "Refund #{$this->refund_code} rejected: {$reason}"
        ]);

        return $this;
    }

    /**
     * Cancel the refund
     */
    public function cancel($reason = null)
    {
        if ($this->status === self::STATUS_COMPLETED) {
            throw new \Exception('Completed refunds cannot be cancelled.');
        }

        $this->update([
            'status' => self::STATUS_CANCELLED,
            'cancellation_reason' => $reason,
            'cancelled_by' => auth()->id(),
        ]);

        \App\Services\Inventory\InventoryLogger::log([
            'module' => 'REFUND',
            'action' => 'CANCEL',
            'record_id' => $this->id,
            'new_data' => ['status' => self::STATUS_CANCELLED, 'reason' => $reason],
            'remarks' => "Refund #{$this->refund_code} cancelled: {$reason}"
        ]);

        return $this;
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if refund is fully completed
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if refund is pending
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if refund can be processed
     */
    public function canBeProcessed()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if refund can be approved
     */
    public function canBeApproved()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROCESSING]);
    }

    /**
     * Check if refund can be completed
     */
    public function canBeCompleted()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if refund can be cancelled
     */
    public function canBeCancelled()
    {
        return $this->status !== self::STATUS_COMPLETED && $this->status !== self::STATUS_CANCELLED;
    }

    /**
     * Get total refundable amount for the transaction
     */
    public function getRefundableAmount()
    {
        $transaction = $this->transaction;
        if (!$transaction) {
            return 0;
        }

        $totalPaid = $transaction->amount_received ?? $transaction->total_amount ?? 0;
        $refundedSoFar = self::where('transaction_id', $transaction->id)
            ->where('status', self::STATUS_COMPLETED)
            ->where('id', '!=', $this->id)
            ->sum('refund_amount');

        return $totalPaid - $refundedSoFar;
    }

    /**
     * Check if refund has been reversed
     */
    public function hasStockReversed()
    {
        return (bool) $this->stock_reversed;
    }

    // ==================== SCOPES ====================

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeByTransaction($query, $transactionId)
    {
        return $query->where('transaction_id', $transactionId);
    }

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('refund_method', $method);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('refund_code', 'LIKE', "%{$search}%")
                ->orWhereHas('transaction', function($tq) use ($search) {
                    $tq->where('stock_out_code', 'LIKE', "%{$search}%")
                        ->orWhere('customer_name', 'LIKE', "%{$search}%");
                });
        });
    }

    // ==================== ACCESSORS ====================

    public function getStatusTextAttribute()
    {
        $statuses = self::getStatuses();
        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PENDING => 'bg-warning text-dark',
            self::STATUS_PROCESSING => 'bg-info',
            self::STATUS_APPROVED => 'bg-primary',
            self::STATUS_COMPLETED => 'bg-success',
            self::STATUS_REJECTED => 'bg-danger',
            self::STATUS_CANCELLED => 'bg-secondary',
            self::STATUS_FAILED => 'bg-danger',
        ];
        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getFormattedRefundAmountAttribute()
    {
        return '₹' . number_format($this->refund_amount, 2);
    }

    public function getMethodTextAttribute()
    {
        $methods = self::getMethods();
        return $methods[$this->refund_method] ?? $this->refund_method;
    }

    public function getItemsSummaryAttribute()
    {
        if (!$this->refund_items) {
            return null;
        }

        $items = is_array($this->refund_items) ? $this->refund_items : json_decode($this->refund_items, true);
        return [
            'total_items' => count($items),
            'total_quantity' => array_sum(array_column($items, 'quantity')),
            'items' => $items,
        ];
    }

    public function getRefundableAmountAttribute()
    {
        return $this->getRefundableAmount();
    }

    // ==================== EVENTS ====================

    protected static function booted()
    {
        static::creating(function ($refund) {
            if (empty($refund->refund_code)) {
                $refund->refund_code = $refund->generateRefundCode();
            }
        });
    }

    /**
     * Generate refund code
     */
    private function generateRefundCode()
    {
        $prefix = 'REF';
        $date = now()->format('Ymd');
        $last = self::where('institute_id', $this->institute_id ?? auth()->user()->institute_id)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        return $prefix . '-' . $date . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }
}