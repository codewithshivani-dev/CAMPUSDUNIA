<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use App\Services\Inventory\InventoryLogger;

class InventoryItemBatch extends Model
{

    protected $table = 'inventory_item_batches';

    protected $guarded = [];

    protected $casts = [
        'quantity' => 'decimal:2',
        'remaining_quantity' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'manufacturing_date' => 'date',
        'expiry_date' => 'date',
        'status' => 'string',
        'blocked_reason' => 'string',
    ];

    // ==================== BATCH STATUS CONSTANTS ====================
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_PARTIALLY_USED = 'PARTIALLY_USED';
    const STATUS_EXHAUSTED = 'EXHAUSTED';
    const STATUS_EXPIRED = 'EXPIRED';
    const STATUS_BLOCKED = 'BLOCKED';

    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_PARTIALLY_USED => 'Partially Used',
            self::STATUS_EXHAUSTED => 'Exhausted',
            self::STATUS_EXPIRED => 'Expired',
            self::STATUS_BLOCKED => 'Blocked',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the parent inventory item
     */
    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    /**
     * Get the warehouse where this batch is stored
     */
    public function warehouse()
    {
        return $this->belongsTo(InventoryWarehouse::class, 'warehouse_id');
    }

    /**
     * Get the store where this batch is stored
     */
    public function store()
    {
        return $this->belongsTo(InventoryStore::class, 'store_id');
    }

    /**
     * Get the stock movements for this batch
     */
    public function stockMovements()
    {
        return $this->hasMany(InventoryStockMovement::class, 'batch_id');
    }

    /**
     * Get the receipts in that added this batch
     */
    public function receiptsIn()
    {
        return $this->morphToMany(InventoryReceiptIn::class, 'batchable');
    }

    /**
     * Get the user who created this batch
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Get the user who last updated this batch
     */
    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    /**
     * Get the user who deleted this batch
     */
    public function deleter()
    {
        return $this->belongsTo(\App\Models\User::class, 'deleted_by');
    }

    // ==================== STOCK MANAGEMENT METHODS ====================

    /**
     * Add stock to this batch
     */
    public function addStock($quantity, $reference = null, $notes = null)
    {
        if ($quantity <= 0) {
            throw new \Exception("Quantity must be greater than 0.");
        }

        $previousQuantity = $this->remaining_quantity;
        $newQuantity = $previousQuantity + $quantity;

        $this->update([
            'remaining_quantity' => $newQuantity,
            'quantity' => $this->quantity + $quantity,
        ]);

        // Update batch status
        $this->updateStatus();

        // Create stock movement for adding stock
        $this->createStockMovement($quantity, $previousQuantity, $newQuantity, 'IN', $reference, $notes);

        InventoryLogger::log([
            'module' => 'BATCH',
            'action' => 'STOCK_IN',
            'record_id' => $this->id,
            'new_data' => [
                'batch_number' => $this->batch_number,
                'item_name' => $this->item->item_name ?? 'N/A',
                'quantity_added' => $quantity,
                'previous_quantity' => $previousQuantity,
                'new_quantity' => $newQuantity,
                'notes' => $notes
            ],
            'remarks' => "Stock added to batch: {$this->batch_number}"
        ]);

        return $this;
    }

    /**
     * Deduct stock from this batch (FEFO - First Expiry First Out)
     * 
     * @param float $quantity Quantity to deduct
     * @param mixed $reference Reference object (e.g., StockOut, ReceiptOut)
     * @param string|null $notes Additional notes
     * @return $this
     * @throws \Exception
     */
    public function deductStock($quantity, $reference = null, $notes = null)
    {
        // Validate quantity
        if ($quantity <= 0) {
            throw new \Exception("Quantity must be greater than 0.");
        }

        // Check if batch is blocked
        if ($this->status === self::STATUS_BLOCKED) {
            throw new \Exception("Batch {$this->batch_number} is blocked and cannot be used.");
        }

        // Check if batch is expired
        if ($this->isExpired()) {
            throw new \Exception("Batch {$this->batch_number} has expired on {$this->expiry_date->format('Y-m-d')} and cannot be used.");
        }

        // Check sufficient stock
        if ($this->remaining_quantity < $quantity) {
            throw new \Exception("Insufficient stock in batch {$this->batch_number}. Available: {$this->remaining_quantity}, Requested: {$quantity}");
        }

        $previousQuantity = $this->remaining_quantity;
        $newQuantity = $previousQuantity - $quantity;

        // Update the batch
        $this->update([
            'remaining_quantity' => $newQuantity,
        ]);

        // Update batch status
        $this->updateStatus();

        // Create stock movement for deduction
        $this->createStockMovement($quantity, $previousQuantity, $newQuantity, 'OUT', $reference, $notes);

        // Check if batch is exhausted
        if ($newQuantity <= 0) {
            InventoryLogger::log([
                'module' => 'BATCH',
                'action' => 'EXHAUSTED',
                'record_id' => $this->id,
                'new_data' => [
                    'batch_number' => $this->batch_number,
                    'item_name' => $this->item->item_name ?? 'N/A',
                    'quantity_used' => $quantity,
                    'reference_type' => $reference ? get_class($reference) : null,
                    'reference_id' => $reference ? $reference->id : null,
                ],
                'remarks' => "Batch {$this->batch_number} is now exhausted"
            ]);
        }

        InventoryLogger::log([
            'module' => 'BATCH',
            'action' => 'STOCK_OUT',
            'record_id' => $this->id,
            'new_data' => [
                'batch_number' => $this->batch_number,
                'item_name' => $this->item->item_name ?? 'N/A',
                'quantity_deducted' => $quantity,
                'previous_quantity' => $previousQuantity,
                'new_quantity' => $newQuantity,
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference ? $reference->id : null,
                'notes' => $notes
            ],
            'remarks' => "Stock deducted from batch: {$this->batch_number}"
        ]);

        return $this;
    }

    /**
     * Create stock movement record for this batch
     */
    private function createStockMovement($quantity, $previousQuantity, $newQuantity, $movementType, $reference = null, $notes = null)
    {
        $movementData = [
            'institute_id' => $this->institute_id,
            'item_id' => $this->item_id,
            'warehouse_id' => $this->warehouse_id,
            'store_id' => $this->store_id,
            'batch_id' => $this->id,
            'movement_type' => $movementType,
            'quantity' => $quantity,
            'previous_stock' => $previousQuantity,
            'new_stock' => $newQuantity,
            'unit_cost' => $this->purchase_price ?? 0,
            'total_cost' => ($this->purchase_price ?? 0) * $quantity,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference ? $reference->id : null,
            'notes' => $notes ?? "Batch: {$this->batch_number}",
            'created_by' => auth()->id(),
        ];

        // Create the stock movement
        $movement = InventoryStockMovement::create($movementData);

        // Also update the parent item's stock movement summary if needed
        if ($this->item) {
            // Update the item's stock movements count
            $this->item->touch();
        }

        return $movement;
    }

    /**
     * Check if batch has stock available
     */
    public function hasStock()
    {
        return $this->remaining_quantity > 0;
    }

    /**
     * Check if batch is expired
     */
    public function isExpired()
    {
        if (!$this->expiry_date) {
            return false;
        }
        return $this->expiry_date < now();
    }

    /**
     * Check if batch is about to expire (within X days)
     */
    public function isExpiringSoon($days = 30)
    {
        if (!$this->expiry_date) {
            return false;
        }
        if ($this->isExpired()) {
            return false;
        }
        return $this->expiry_date <= now()->addDays($days);
    }

    /**
     * Get days until expiry
     */
    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->expiry_date) {
            return null;
        }
        if ($this->isExpired()) {
            return 0;
        }
        return $this->expiry_date->diffInDays(now());
    }

    // ==================== STATUS MANAGEMENT ====================

    /**
     * Update batch status based on remaining quantity and expiry
     */
    public function updateStatus()
    {
        if ($this->isExpired()) {
            $newStatus = self::STATUS_EXPIRED;
        } elseif ($this->remaining_quantity <= 0) {
            $newStatus = self::STATUS_EXHAUSTED;
        } elseif ($this->remaining_quantity < $this->quantity) {
            $newStatus = self::STATUS_PARTIALLY_USED;
        } else {
            $newStatus = self::STATUS_ACTIVE;
        }

        if ($this->status !== $newStatus) {
            $oldStatus = $this->status;
            $this->update(['status' => $newStatus]);

            InventoryLogger::log([
                'module' => 'BATCH',
                'action' => 'STATUS_UPDATE',
                'record_id' => $this->id,
                'old_data' => ['status' => $oldStatus],
                'new_data' => ['status' => $newStatus],
                'remarks' => "Batch {$this->batch_number} status changed from {$oldStatus} to {$newStatus}"
            ]);
        }

        return $this;
    }

    /**
     * Block a batch (prevent usage)
     */
    public function block($reason = null)
    {
        if ($this->status === self::STATUS_BLOCKED) {
            return $this;
        }

        $oldStatus = $this->status;
        
        $this->update([
            'status' => self::STATUS_BLOCKED,
            'blocked_reason' => $reason,
        ]);

        InventoryLogger::log([
            'module' => 'BATCH',
            'action' => 'BLOCK',
            'record_id' => $this->id,
            'old_data' => ['status' => $oldStatus],
            'new_data' => [
                'batch_number' => $this->batch_number,
                'reason' => $reason,
            ],
            'remarks' => "Batch {$this->batch_number} blocked: {$reason}"
        ]);

        return $this;
    }

    /**
     * Unblock a batch
     */
    public function unblock()
    {
        if ($this->status !== self::STATUS_BLOCKED) {
            return $this;
        }

        $oldStatus = $this->status;
        
        $newStatus = $this->remaining_quantity > 0 
            ? ($this->isExpired() ? self::STATUS_EXPIRED : self::STATUS_ACTIVE)
            : self::STATUS_EXHAUSTED;
        
        $this->update([
            'status' => $newStatus,
            'blocked_reason' => null,
        ]);

        InventoryLogger::log([
            'module' => 'BATCH',
            'action' => 'UNBLOCK',
            'record_id' => $this->id,
            'old_data' => ['status' => $oldStatus],
            'new_data' => [
                'batch_number' => $this->batch_number,
                'new_status' => $newStatus,
            ],
            'remarks' => "Batch {$this->batch_number} unblocked"
        ]);

        return $this;
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get the batch display name
     */
    public function getDisplayNameAttribute()
    {
        $name = $this->batch_number;
        if ($this->item) {
            $name .= ' (' . number_format($this->remaining_quantity, 2) . ' ' . ($this->item->unit_name ?? 'units') . ')';
        }
        if ($this->expiry_date) {
            $name .= ' | Exp: ' . $this->expiry_date->format('Y-m-d');
        }
        return $name;
    }

    /**
     * Get the expiry status badge
     */
    public function getExpiryBadgeAttribute()
    {
        if (!$this->expiry_date) {
            return 'bg-secondary';
        }
        if ($this->isExpired()) {
            return 'bg-danger';
        }
        if ($this->isExpiringSoon(7)) {
            return 'bg-warning text-dark';
        }
        if ($this->isExpiringSoon(30)) {
            return 'bg-info text-white';
        }
        return 'bg-success';
    }

    /**
     * Get the expiry status text
     */
    public function getExpiryStatusAttribute()
    {
        if (!$this->expiry_date) {
            return 'Not Set';
        }
        if ($this->isExpired()) {
            return 'Expired';
        }
        if ($this->isExpiringSoon(7)) {
            return 'Expiring Soon (7 days)';
        }
        if ($this->isExpiringSoon(30)) {
            return 'Expiring Soon (30 days)';
        }
        return 'Valid';
    }

    /**
     * Get the formatted remaining quantity
     */
    public function getFormattedRemainingQuantityAttribute()
    {
        return number_format($this->remaining_quantity, 2) . ' ' . ($this->item->unit_name ?? 'units');
    }

    /**
     * Get the usage percentage
     */
    public function getUsagePercentageAttribute()
    {
        if ($this->quantity <= 0) {
            return 0;
        }
        return round((($this->quantity - $this->remaining_quantity) / $this->quantity) * 100, 2);
    }

    /**
     * Get the batch value (remaining quantity * purchase price)
     */
    public function getBatchValueAttribute()
    {
        return $this->remaining_quantity * $this->purchase_price;
    }

    /**
     * Get formatted batch value
     */
    public function getFormattedBatchValueAttribute()
    {
        return '₹' . number_format($this->batch_value, 2);
    }

    /**
     * Get the full location string
     */
    public function getLocationAttribute()
    {
        $parts = [];
        if ($this->warehouse) {
            $parts[] = $this->warehouse->warehouse_name;
        }
        if ($this->store) {
            $parts[] = $this->store->store_name;
        }
        return implode(' → ', $parts) ?: 'N/A';
    }

    /**
     * Check if batch is usable (has stock, not expired, not blocked)
     */
    public function isUsable()
    {
        return $this->remaining_quantity > 0 
            && !$this->isExpired() 
            && $this->status !== self::STATUS_BLOCKED;
    }

    /**
     * Get available quantity that can be used
     */
    public function getAvailableQuantity()
    {
        if (!$this->isUsable()) {
            return 0;
        }
        return $this->remaining_quantity;
    }

    /**
     * Check if batch can be deleted
     */
    public function canBeDeleted()
    {
        if ($this->stockMovements()->exists()) {
            return false;
        }
        return true;
    }

    /**
     * Get deletion blockers
     */
    public function getDeletionBlockers()
    {
        $blockers = [];

        if ($this->stockMovements()->exists()) {
            $blockers[] = [
                'type' => 'stock_movements',
                'count' => $this->stockMovements()->count(),
                'message' => "This batch has {$this->stockMovements()->count()} stock movement(s)"
            ];
        }

        return $blockers;
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
            self::STATUS_ACTIVE => 'bg-success',
            self::STATUS_PARTIALLY_USED => 'bg-warning text-dark',
            self::STATUS_EXHAUSTED => 'bg-secondary',
            self::STATUS_EXPIRED => 'bg-danger',
            self::STATUS_BLOCKED => 'bg-dark',
        ];
        return $badges[$this->status] ?? 'bg-secondary';
    }

    // ==================== SCOPES ====================

    /**
     * Scope for active batches
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                     ->where('remaining_quantity', '>', 0)
                     ->where(function($q) {
                         $q->whereNull('expiry_date')
                           ->orWhere('expiry_date', '>', now());
                     });
    }

    /**
     * Scope for available batches (has remaining stock)
     */
    public function scopeAvailable($query)
    {
        return $query->where('remaining_quantity', '>', 0)
                     ->whereNotIn('status', [self::STATUS_EXHAUSTED, self::STATUS_EXPIRED, self::STATUS_BLOCKED]);
    }

    /**
     * Scope for expired batches
     */
    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now())
                     ->where('remaining_quantity', '>', 0);
    }

    /**
     * Scope for expiring soon batches
     */
    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))
                     ->where('expiry_date', '>', now())
                     ->where('remaining_quantity', '>', 0);
    }

    /**
     * Scope for FEFO (First Expiry First Out) - Ordered by expiry date
     * This is the recommended scope for batch selection
     */
    public function scopeFEFO($query)
    {
        return $query->where('remaining_quantity', '>', 0)
                     ->orderBy('expiry_date', 'asc')
                     ->orderBy('created_at', 'asc');
    }

    /**
     * Scope for FIFO (First In First Out) - Ordered by created date
     * Use this when expiry dates are not available or not relevant
     */
    public function scopeFIFO($query)
    {
        return $query->where('remaining_quantity', '>', 0)
                     ->orderBy('created_at', 'asc');
    }

    /**
     * Scope for FILO (First In Last Out) - Ordered by created date descending
     */
    public function scopeFILO($query)
    {
        return $query->where('remaining_quantity', '>', 0)
                     ->orderBy('created_at', 'desc');
    }

    /**
     * Scope by warehouse
     */
    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    /**
     * Scope by store
     */
    public function scopeByStore($query, $storeId)
    {
        return $query->where('store_id', $storeId);
    }

    /**
     * Scope by item
     */
    public function scopeByItem($query, $itemId)
    {
        return $query->where('item_id', $itemId);
    }

    /**
     * Scope by batch number (search)
     */
    public function scopeByBatchNumber($query, $batchNumber)
    {
        return $query->where('batch_number', 'LIKE', "%{$batchNumber}%");
    }

    /**
     * Scope for blocked batches
     */
    public function scopeBlocked($query)
    {
        return $query->where('status', self::STATUS_BLOCKED);
    }

    /**
     * Scope for exhausted batches
     */
    public function scopeExhausted($query)
    {
        return $query->where('status', self::STATUS_EXHAUSTED)
                     ->orWhere('remaining_quantity', '<=', 0);
    }

    /**
     * Scope by date range
     */
    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * Scope by expiry date range
     */
    public function scopeByExpiryRange($query, $from, $to)
    {
        return $query->whereBetween('expiry_date', [$from, $to]);
    }

    /**
     * Scope to get batches with available stock only
     */
    public function scopeWithAvailableStock($query)
    {
        return $query->where('remaining_quantity', '>', 0);
    }

    /**
     * Scope to get batches that are not expired
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expiry_date')
              ->orWhere('expiry_date', '>', now());
        });
    }

    /**
     * Scope to get batches by item and location
     */
    public function scopeByItemAndLocation($query, $itemId, $warehouseId = null, $storeId = null)
    {
        $query->where('item_id', $itemId);
        
        if ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        }
        if ($storeId) {
            $query->where('store_id', $storeId);
        }
        
        return $query;
    }

    // ==================== BOOT METHOD ====================

    protected static function boot()
    {
        parent::boot();

        static::created(function ($batch) {
            InventoryLogger::log([
                'module' => 'BATCH',
                'action' => 'CREATED',
                'record_id' => $batch->id,
                'new_data' => $batch->toArray(),
                'remarks' => "Batch created: {$batch->batch_number}"
            ]);
        });

        static::updated(function ($batch) {
            if ($batch->wasChanged('remaining_quantity') || $batch->wasChanged('status')) {
                InventoryLogger::log([
                    'module' => 'BATCH',
                    'action' => 'UPDATED',
                    'record_id' => $batch->id,
                    'old_data' => $batch->getOriginal(),
                    'new_data' => $batch->getChanges(),
                    'remarks' => "Batch updated: {$batch->batch_number}"
                ]);
            }
        });

        static::deleted(function ($batch) {
            InventoryLogger::log([
                'module' => 'BATCH',
                'action' => 'DELETED',
                'record_id' => $batch->id,
                'old_data' => $batch->toArray(),
                'remarks' => "Batch deleted: {$batch->batch_number}"
            ]);
        });
    }
}