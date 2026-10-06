<?php
// app/Models/Inventory/InventoryStore.php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class InventoryStore extends Model
{
    protected $table = 'inventory_stores';

    protected $guarded = [];

    protected $casts = [
        'is_default' => 'boolean',
        'status' => 'boolean',
        'capacity' => 'integer',
        'current_utilization' => 'integer',
        // All IDs are stored as VARCHAR, so cast them to string
        'id' => 'string',
        'branch_id' => 'string',
        'warehouse_id' => 'string',
        'floor_id' => 'string',
        'room_id' => 'string',
        'institute_id' => 'string',
        'building_id' => 'string',
        'block_id' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    /**
     * Get the warehouse that owns the store.
     * Since both IDs are strings, we need to ensure proper comparison
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(InventoryWarehouse::class, 'warehouse_id', 'id')
            ->where('institute_id', (string) $this->institute_id);
    }

    /**
     * Get the warehouse with fallback for missing relationships
     */
    public function getWarehouseAttribute()
    {
        if ($this->relationLoaded('warehouse') && $this->getRelation('warehouse')) {
            return $this->getRelation('warehouse');
        }

        // Try to load warehouse manually if relationship is null
        if ($this->warehouse_id) {
            $warehouse = InventoryWarehouse::where('id', (string) $this->warehouse_id)
                ->where('institute_id', (string) $this->institute_id)
                ->first();
            
            if ($warehouse) {
                $this->setRelation('warehouse', $warehouse);
                return $warehouse;
            }
        }

        return null;
    }

    /**
     * Get warehouse name with fallback
     */
    public function getWarehouseNameAttribute(): string
    {
        if ($this->warehouse) {
            return $this->warehouse->warehouse_name;
        }
        return 'N/A';
    }

    /**
     * Check if warehouse exists
     */
    public function hasValidWarehouse(): bool
    {
        return $this->warehouse !== null;
    }

    /**
     * Get the parent warehouse
     */
    public function getParentWarehouse()
    {
        return $this->warehouse;
    }

    /**
     * Check if this store belongs to a specific warehouse
     */
    public function belongsToWarehouse($warehouseId): bool
    {
        return $this->warehouse_id == (string) $warehouseId;
    }

    /**
     * Validate that this store belongs to the given warehouse
     * 
     * @param string|int $warehouseId
     * @throws \Exception
     */
    public function validateStoreBelongsToWarehouse($warehouseId): void
    {
        if (!$this->belongsToWarehouse($warehouseId)) {
            throw new \Exception(
                "Store '{$this->store_name}' (ID: {$this->id}) does not belong to warehouse ID: {$warehouseId}. " .
                "This store belongs to warehouse ID: {$this->warehouse_id}"
            );
        }
    }

    /**
     * Get the branch that owns the store
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Branch::class, 'branch_id', 'id');
    }

    /**
     * Get all items in this store
     */
    public function items(): HasMany
    {
        return $this->hasMany(InventoryItem::class, 'store_id', 'id');
    }

    /**
     * Get all stock movements for this store
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(InventoryStockMovement::class, 'store_id', 'id');
    }

    /**
     * Get all transfers out of this store
     */
    public function transferOut(): HasMany
    {
        return $this->hasMany(InventoryStoreTransfer::class, 'from_store_id', 'id');
    }

    /**
     * Get all transfers into this store
     */
    public function transferIn(): HasMany
    {
        return $this->hasMany(InventoryStoreTransfer::class, 'to_store_id', 'id');
    }

    /**
     * Get all receipts out of this store
     */
    public function receiptsOut(): HasMany
    {
        return $this->hasMany(InventoryReceiptOut::class, 'store_id', 'id');
    }

    /**
     * Get all receipts into this store
     */
    public function receiptsIn(): HasMany
    {
        return $this->hasMany(InventoryReceiptIn::class, 'store_id', 'id');
    }

    /**
     * Get the user who created this store
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    /**
     * Get the user who last updated this store
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by', 'id');
    }

    /**
     * Get the user who deleted this store
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'deleted_by', 'id');
    }

    // ============================================================
    // UTILIZATION METHODS
    // ============================================================

    /**
     * Update store utilization based on current stock of all items
     * DOES NOT update warehouse to prevent infinite recursion
     * Updated to filter by current institute/branch
     */
    public function updateUtilization(): self
    {
        $query = $this->items();
        
        // Filter by institute if set
        if ($this->institute_id) {
            $query->where('institute_id', (string) $this->institute_id);
        }
        
        // Filter by branch if set
        if ($this->branch_id) {
            $query->where('branch_id', (string) $this->branch_id);
        }
        
        $this->current_utilization = $query->sum('current_stock') ?? 0;
        $this->save();
        return $this;
    }

    /**
     * Update store AND its parent warehouse utilization (use carefully)
     * This is the only method that should update both
     * Updated to respect institute/branch filters
     */
    public function updateUtilizationWithWarehouse(): self
    {
        // Update store first
        $this->updateUtilization();
        
        // Then update parent warehouse (avoid recursion by using direct query)
        if ($this->warehouse_id) {
            $warehouse = InventoryWarehouse::where('id', (string) $this->warehouse_id)
                ->where('institute_id', (string) $this->institute_id)
                ->first();
            
            if ($warehouse) {
                // Direct update without calling back to stores
                $itemQuery = $warehouse->items();
                
                // Filter items by institute if set
                if ($this->institute_id) {
                    $itemQuery->where('institute_id', (string) $this->institute_id);
                }
                
                // Filter items by branch if set
                if ($this->branch_id) {
                    $itemQuery->where('branch_id', (string) $this->branch_id);
                }
                
                $warehouse->current_utilization = $itemQuery->sum('current_stock') ?? 0;
                $warehouse->save();
            }
        }
        
        return $this;
    }

    /**
     * Get utilization percentage
     */
    public function getUtilizationPercentageAttribute(): float
    {
        if ($this->capacity > 0) {
            return round(($this->current_utilization / $this->capacity) * 100, 2);
        }
        return 0;
    }

    /**
     * Get utilization status (empty, low, medium, high, full)
     */
    public function getUtilizationStatusAttribute(): string
    {
        $percentage = $this->getUtilizationPercentageAttribute();
        
        if ($percentage >= 100) {
            return 'full';
        } elseif ($percentage >= 75) {
            return 'high';
        } elseif ($percentage >= 40) {
            return 'medium';
        } elseif ($percentage > 0) {
            return 'low';
        }
        return 'empty';
    }

    /**
     * Get utilization status badge color
     */
    public function getUtilizationStatusBadgeAttribute(): string
    {
        $badges = [
            'full' => 'bg-danger',
            'high' => 'bg-warning text-dark',
            'medium' => 'bg-info',
            'low' => 'bg-success',
            'empty' => 'bg-secondary',
        ];
        return $badges[$this->utilization_status] ?? 'bg-secondary';
    }

    /**
     * Get utilization status text
     */
    public function getUtilizationStatusTextAttribute(): string
    {
        $texts = [
            'full' => 'Full',
            'high' => 'High',
            'medium' => 'Medium',
            'low' => 'Low',
            'empty' => 'Empty',
        ];
        return $texts[$this->utilization_status] ?? 'Unknown';
    }

    /**
     * Check if store is full
     */
    public function isFull(): bool
    {
        if ($this->capacity > 0) {
            return $this->current_utilization >= $this->capacity;
        }
        return false;
    }

    /**
     * Check if store can accept items
     * Now checks parent warehouse capacity as well
     */
    public function canAcceptItems($quantity = 1): bool
    {
        // Check store capacity
        if ($this->capacity > 0) {
            if (($this->current_utilization + $quantity) > $this->capacity) {
                return false;
            }
        }

        // Check parent warehouse capacity
        if ($this->warehouse_id) {
            $warehouse = $this->warehouse;
            if ($warehouse && $warehouse->capacity > 0) {
                // Get total stock in warehouse including this store
                $warehouseStock = InventoryItem::where('institute_id', (string) $this->institute_id)
                    ->where('warehouse_id', (string) $this->warehouse_id)
                    ->sum('current_stock') ?? 0;
                
                if (($warehouseStock + $quantity) > $warehouse->capacity) {
                    Log::info('Store cannot accept items: Parent warehouse at capacity', [
                        'store_id' => $this->id,
                        'store_name' => $this->store_name,
                        'warehouse_id' => $this->warehouse_id,
                        'warehouse_capacity' => $warehouse->capacity,
                        'warehouse_current' => $warehouseStock,
                        'requested' => $quantity,
                        'available' => $warehouse->capacity - $warehouseStock,
                    ]);
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get available capacity including parent warehouse capacity
     */
    public function getAvailableCapacityWithWarehouseAttribute()
    {
        $storeAvailable = $this->available_capacity;
        $warehouseAvailable = PHP_INT_MAX;

        if ($this->warehouse_id) {
            $warehouse = $this->warehouse;
            if ($warehouse && $warehouse->capacity > 0) {
                $warehouseStock = InventoryItem::where('institute_id', (string) $this->institute_id)
                    ->where('warehouse_id', (string) $this->warehouse_id)
                    ->sum('current_stock') ?? 0;
                $warehouseAvailable = max(0, $warehouse->capacity - $warehouseStock);
            }
        }

        return min($storeAvailable, $warehouseAvailable);
    }

    /**
     * Get available capacity
     */
    public function getAvailableCapacityAttribute()
    {
        if ($this->capacity > 0) {
            return max(0, $this->capacity - $this->current_utilization);
        }
        return PHP_INT_MAX;
    }

    /**
     * Get recommended reorder threshold
     */
    public function getRecommendedReorderThresholdAttribute(): int
    {
        if ($this->capacity > 0) {
            return (int) round($this->capacity * 0.2);
        }
        return 0;
    }

    // ============================================================
    // ITEM MANAGEMENT METHODS
    // ============================================================

    /**
     * Get all items with stock details
     */
    public function getItemsWithStock()
    {
        $query = $this->items()
            ->where('current_stock', '>', 0)
            ->with(['category', 'subcategory']);
        
        if ($this->institute_id) {
            $query->where('institute_id', (string) $this->institute_id);
        }
        if ($this->branch_id) {
            $query->where('branch_id', (string) $this->branch_id);
        }
        
        return $query->get();
    }

    /**
     * Get low stock items in this store
     */
    public function getLowStockItems()
    {
        $query = $this->items()
            ->whereColumn('available_stock', '<=', 'reorder_level')
            ->where('available_stock', '>', 0);
        
        if ($this->institute_id) {
            $query->where('institute_id', (string) $this->institute_id);
        }
        if ($this->branch_id) {
            $query->where('branch_id', (string) $this->branch_id);
        }
        
        return $query->get();
    }

    /**
     * Get out of stock items in this store
     */
    public function getOutOfStockItems()
    {
        $query = $this->items()
            ->where('available_stock', '<=', 0);
        
        if ($this->institute_id) {
            $query->where('institute_id', (string) $this->institute_id);
        }
        if ($this->branch_id) {
            $query->where('branch_id', (string) $this->branch_id);
        }
        
        return $query->get();
    }

    /**
     * Get expired items in this store
     */
    public function getExpiredItems()
    {
        $query = $this->items()
            ->where('expiry_date', '<', now());
        
        if ($this->institute_id) {
            $query->where('institute_id', (string) $this->institute_id);
        }
        if ($this->branch_id) {
            $query->where('branch_id', (string) $this->branch_id);
        }
        
        return $query->get();
    }

    /**
     * Get total value of stock in store
     */
    public function getStockValueAttribute(): float
    {
        $query = $this->items();
        
        if ($this->institute_id) {
            $query->where('institute_id', (string) $this->institute_id);
        }
        if ($this->branch_id) {
            $query->where('branch_id', (string) $this->branch_id);
        }
        
        $items = $query->get();
        
        return $items->sum(function ($item) {
            return $item->current_stock * $item->buying_price;
        });
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    public function getTotalItemsCountAttribute(): int
    {
        $query = $this->items();
        
        if ($this->institute_id) {
            $query->where('institute_id', (string) $this->institute_id);
        }
        if ($this->branch_id) {
            $query->where('branch_id', (string) $this->branch_id);
        }
        
        return (int) $query->sum('current_stock');
    }

    public function getUniqueProductsCountAttribute(): int
    {
        $query = $this->items();
        
        if ($this->institute_id) {
            $query->where('institute_id', (string) $this->institute_id);
        }
        if ($this->branch_id) {
            $query->where('branch_id', (string) $this->branch_id);
        }
        
        return $query->count();
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->pincode
        ]);
        return implode(', ', $parts);
    }

    public function getStatusTextAttribute(): string
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    public function getStatusBadgeAttribute(): string
    {
        return $this->status ? 'bg-success' : 'bg-danger';
    }

    /**
     * Get location path as string
     */
    public function getLocationPathAttribute(): string
    {
        $parts = array_filter([
            $this->building_id ? 'Building #' . $this->building_id : null,
            $this->block_id ? 'Block #' . $this->block_id : null,
            $this->floor_id ? 'Floor #' . $this->floor_id : null,
            $this->room_id ? 'Room #' . $this->room_id : null
        ]);
        
        return implode(' → ', $parts) ?: 'N/A';
    }

    /**
     * Get store summary statistics
     */
    public function getSummaryAttribute(): array
    {
        $itemQuery = $this->items();
        
        if ($this->institute_id) {
            $itemQuery->where('institute_id', (string) $this->institute_id);
        }
        if ($this->branch_id) {
            $itemQuery->where('branch_id', (string) $this->branch_id);
        }
        
        $items = $itemQuery->get();
        
        return [
            'total_items' => $items->count(),
            'total_stock' => $items->sum('current_stock'),
            'total_value' => $this->stock_value,
            'utilization_percentage' => $this->getUtilizationPercentageAttribute(),
            'available_capacity' => $this->available_capacity_with_warehouse,
            'low_stock_items' => $this->getLowStockItems()->count(),
            'out_of_stock_items' => $this->getOutOfStockItems()->count(),
            'expired_items' => $this->getExpiredItems()->count(),
            'warehouse_name' => $this->warehouse_name,
        ];
    }

    /**
     * Get stock value by category
     */
    public function getStockValueByCategory()
    {
        $query = $this->items()
            ->join('inventory_categories', 'inventory_items.category_id', '=', 'inventory_categories.id')
            ->select(
                'inventory_categories.id',
                'inventory_categories.category_name',
                \DB::raw('SUM(inventory_items.current_stock) as total_stock'),
                \DB::raw('SUM(inventory_items.current_stock * inventory_items.buying_price) as total_value')
            )
            ->groupBy('inventory_categories.id', 'inventory_categories.category_name');
        
        if ($this->institute_id) {
            $query->where('inventory_items.institute_id', (string) $this->institute_id);
        }
        if ($this->branch_id) {
            $query->where('inventory_items.branch_id', (string) $this->branch_id);
        }
        
        return $query->get();
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeDefault($query)
    {
        return $query->where('is_default', 1);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeByInstitute($query, $instituteId, $branchId = null)
    {
        $query->where('institute_id', (string) $instituteId);
        
        if ($branchId) {
            $query->where('branch_id', (string) $branchId);
        }
        
        return $query;
    }

    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', (string) $branchId);
    }

    /**
     * Scope to filter by warehouse
     */
    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', (string) $warehouseId);
    }

    /**
     * Scope to get stores that belong to warehouses with available capacity
     */
    public function scopeWithAvailableWarehouseCapacity($query, $quantity = 1)
    {
        return $query->whereHas('warehouse', function($q) use ($quantity) {
            $q->where(function($sub) use ($quantity) {
                $sub->where('capacity', 0)
                    ->orWhereNull('capacity')
                    ->orWhereRaw('(SELECT COALESCE(SUM(current_stock), 0) FROM inventory_items WHERE inventory_items.warehouse_id = inventory_warehouses.id) + ? <= inventory_warehouses.capacity', [$quantity]);
            });
        });
    }

    public function scopeHasAvailableCapacity($query)
    {
        return $query->whereRaw('CAST(capacity AS UNSIGNED) > CAST(current_utilization AS UNSIGNED) OR capacity IS NULL OR capacity = 0');
    }

    public function scopeIsFull($query)
    {
        return $query->whereRaw('CAST(capacity AS UNSIGNED) > 0 AND CAST(current_utilization AS UNSIGNED) >= CAST(capacity AS UNSIGNED)');
    }

    public function scopeWithLowStock($query)
    {
        return $query->whereHas('items', function ($q) {
            $q->whereColumn('available_stock', '<=', 'reorder_level')
              ->where('available_stock', '>', 0);
        });
    }

    // ============================================================
    // BULK OPERATIONS
    // ============================================================

    /**
     * Batch update utilization for multiple stores
     */
    public static function batchUpdateUtilization(array $storeIds)
    {
        $stores = self::whereIn('id', $storeIds)->get();
        foreach ($stores as $store) {
            $store->updateUtilization();
        }
        return $stores;
    }

    /**
     * Update utilization for all stores
     */
    public static function updateAllUtilization()
    {
        $stores = self::all();
        foreach ($stores as $store) {
            $store->updateUtilization();
        }
        return $stores;
    }

    /**
     * Update utilization for stores under a specific warehouse
     */
    public static function updateUtilizationByWarehouse($warehouseId)
    {
        $stores = self::where('warehouse_id', (string) $warehouseId)->get();
        foreach ($stores as $store) {
            $store->updateUtilization();
        }
        return $stores;
    }

    // ============================================================
    // ITEM SUMMARY METHODS
    // ============================================================

    /**
     * Get item count with details
     */
    public function getItemsSummary(): array
    {
        $query = $this->items();
        
        if ($this->institute_id) {
            $query->where('institute_id', (string) $this->institute_id);
        }
        if ($this->branch_id) {
            $query->where('branch_id', (string) $this->branch_id);
        }
        
        return [
            'total_items' => $query->count(),
            'total_stock' => $query->sum('current_stock'),
            'categories' => $query
                ->join('inventory_categories', 'inventory_items.category_id', '=', 'inventory_categories.id')
                ->select('inventory_categories.category_name', \DB::raw('SUM(current_stock) as total'))
                ->groupBy('inventory_categories.category_name')
                ->get()
        ];
    }

    /**
     * Get items with stock status breakdown
     */
    public function getItemsWithStatusBreakdown(): array
    {
        $query = $this->items();
        
        if ($this->institute_id) {
            $query->where('institute_id', (string) $this->institute_id);
        }
        if ($this->branch_id) {
            $query->where('branch_id', (string) $this->branch_id);
        }
        
        $items = $query->get();
        
        return [
            'in_stock' => $items->filter(function ($item) {
                return $item->available_stock > 0 && $item->available_stock > $item->reorder_level;
            })->count(),
            'low_stock' => $items->filter(function ($item) {
                return $item->available_stock > 0 && $item->available_stock <= $item->reorder_level;
            })->count(),
            'out_of_stock' => $items->filter(function ($item) {
                return $item->available_stock <= 0;
            })->count(),
            'expired' => $items->filter(function ($item) {
                return $item->expiry_date && $item->expiry_date < now();
            })->count(),
        ];
    }

    // ============================================================
    // TRANSFER HELPER METHODS
    // ============================================================

    /**
     * Check if store can receive a transfer
     * Now checks parent warehouse capacity
     */
    public function canReceiveTransfer(array $items): bool
    {
        $totalQuantity = 0;
        foreach ($items as $item) {
            $totalQuantity += $item['quantity'] ?? 0;
        }
        
        return $this->canAcceptItems($totalQuantity);
    }

    /**
     * Get transfer recommendations
     */
    public function getTransferRecommendations(): array
    {
        $recommendations = [];
        
        if ($this->getUtilizationPercentageAttribute() > 80) {
            $recommendations[] = [
                'type' => 'warning',
                'message' => "Store is at {$this->getUtilizationPercentageAttribute()}% capacity. Consider transferring items to other stores.",
                'available_capacity' => $this->available_capacity_with_warehouse,
            ];
        }
        
        $lowStockItems = $this->getLowStockItems();
        if ($lowStockItems->count() > 0) {
            $recommendations[] = [
                'type' => 'info',
                'message' => "{$lowStockItems->count()} items are at reorder level. Consider restocking.",
                'items' => $lowStockItems->take(5)->pluck('item_name')->toArray(),
            ];
        }
        
        $expiredItems = $this->getExpiredItems();
        if ($expiredItems->count() > 0) {
            $recommendations[] = [
                'type' => 'danger',
                'message' => "{$expiredItems->count()} items have expired. Please remove them from inventory.",
                'items' => $expiredItems->take(5)->pluck('item_name')->toArray(),
            ];
        }
        
        return $recommendations;
    }

    /**
     * Get nearby stores in same warehouse
     */
    public function getNearbyStores()
    {
        if (!$this->warehouse_id) {
            return collect();
        }
        
        $query = self::where('warehouse_id', (string) $this->warehouse_id)
            ->where('id', '!=', (string) $this->id)
            ->where('status', 1);
        
        if ($this->institute_id) {
            $query->where('institute_id', (string) $this->institute_id);
        }
        if ($this->branch_id) {
            $query->where('branch_id', (string) $this->branch_id);
        }
        
        return $query->get();
    }

    // ============================================================
    // NOTIFICATION METHODS
    // ============================================================

    /**
     * Check if store needs attention
     */
    public function needsAttention(): bool
    {
        return $this->isFull() || 
               $this->getLowStockItems()->count() > 0 || 
               $this->getExpiredItems()->count() > 0;
    }

    /**
     * Get attention reasons
     */
    public function getAttentionReasons(): array
    {
        $reasons = [];
        
        if ($this->isFull()) {
            $reasons[] = 'Store is at full capacity';
        }
        
        $lowStockCount = $this->getLowStockItems()->count();
        if ($lowStockCount > 0) {
            $reasons[] = "{$lowStockCount} items are low on stock";
        }
        
        $expiredCount = $this->getExpiredItems()->count();
        if ($expiredCount > 0) {
            $reasons[] = "{$expiredCount} items have expired";
        }
        
        return $reasons;
    }
}