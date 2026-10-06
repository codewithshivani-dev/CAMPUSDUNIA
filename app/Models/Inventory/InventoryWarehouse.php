<?php
// app/Models/Inventory/InventoryWarehouse.php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class InventoryWarehouse extends Model
{
    use SoftDeletes;

    protected $table = 'inventory_warehouses';

    protected $guarded = [];

    protected $casts = [
        'is_default' => 'boolean',
        'status' => 'boolean',
        'capacity' => 'integer',
        'current_utilization' => 'integer',
        'branch_id' => 'string',
    ];

    // ==================== REMOVED BOOT METHOD - NO MODEL EVENTS ====================
    // Model events removed to prevent infinite recursion

    // ==================== RELATIONSHIPS ====================

    public function stores()
    {
        return $this->hasMany(InventoryStore::class, 'warehouse_id');
    }

    public function items()
    {
        return $this->hasMany(InventoryItem::class, 'warehouse_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(InventoryStockMovement::class, 'warehouse_id');
    }

    public function transferOut()
    {
        return $this->hasMany(InventoryWarehouseTransfer::class, 'from_warehouse_id');
    }

    public function transferIn()
    {
        return $this->hasMany(InventoryWarehouseTransfer::class, 'to_warehouse_id');
    }

    public function receiptsOut()
    {
        return $this->hasMany(InventoryReceiptOut::class, 'warehouse_id');
    }

    public function receiptsIn()
    {
        return $this->hasMany(InventoryReceiptIn::class, 'warehouse_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(\App\Models\User::class, 'deleted_by');
    }

    // Add relationship: belongsTo(Branch::class, 'branch_id')
    public function branch()
    {
        return $this->belongsTo(\App\Models\Branch::class, 'branch_id');
    }

    // ==================== STORE VALIDATION METHODS ====================

    /**
     * Check if this warehouse has a specific store
     */
    public function hasStore($storeId): bool
    {
        return $this->stores()
            ->where('id', (string) $storeId)
            ->exists();
    }

    /**
     * Validate that a store belongs to this warehouse
     * 
     * @param string|int $storeId
     * @throws \Exception
     */
    public function validateStoreBelongsToWarehouse($storeId): void
    {
        if (!$this->hasStore($storeId)) {
            $store = InventoryStore::find($storeId);
            $storeName = $store ? $store->store_name : 'Unknown';
            throw new \Exception(
                "Store '{$storeName}' (ID: {$storeId}) does not belong to warehouse '{$this->warehouse_name}' (ID: {$this->id})."
            );
        }
    }

    /**
     * Get count of stores in this warehouse
     */
    public function getStoresCount(): int
    {
        $query = $this->stores();
        
        if ($this->institute_id) {
            $query->where('institute_id', (string) $this->institute_id);
        }
        if ($this->branch_id) {
            $query->where('branch_id', (string) $this->branch_id);
        }
        
        return $query->count();
    }

    /**
     * Get active stores count in this warehouse
     */
    public function getActiveStoresCount(): int
    {
        $query = $this->stores()->where('status', 1);
        
        if ($this->institute_id) {
            $query->where('institute_id', (string) $this->institute_id);
        }
        if ($this->branch_id) {
            $query->where('branch_id', (string) $this->branch_id);
        }
        
        return $query->count();
    }

    /**
     * Get stores with their details
     */
    public function getStoresWithDetails()
    {
        $query = $this->stores()
            ->withCount(['items'])
            ->with(['items' => function($q) {
                $q->select('id', 'store_id', 'current_stock', 'available_stock');
            }]);
        
        if ($this->institute_id) {
            $query->where('institute_id', (string) $this->institute_id);
        }
        if ($this->branch_id) {
            $query->where('branch_id', (string) $this->branch_id);
        }
        
        return $query->get();
    }

    // ==================== UTILIZATION METHODS ====================

    /**
     * Update warehouse utilization ONLY - DOES NOT update stores to prevent recursion
     * Update to only update records for current institute/branch
     */
    public function updateUtilization()
    {
        $query = $this->items();
        
        // Filter by institute if set
        if ($this->institute_id) {
            $query->where('institute_id', $this->institute_id);
        }
        
        // Filter by branch if set
        if ($this->branch_id) {
            $query->where('branch_id', $this->branch_id);
        }
        
        $this->current_utilization = $query->sum('current_stock') ?? 0;
        $this->save();
        return $this;
    }

    /**
     * Update warehouse AND all its stores utilization (use carefully)
     * This is the only method that should update both
     * Updated to respect institute/branch filters
     */
    public function updateUtilizationWithStores()
    {
        // Update warehouse first
        $this->updateUtilization();
        
        // Then update each store individually (avoid recursion by using direct query)
        $storeQuery = $this->stores();
        
        // Filter stores by institute if set
        if ($this->institute_id) {
            $storeQuery->where('institute_id', $this->institute_id);
        }
        
        // Filter stores by branch if set
        if ($this->branch_id) {
            $storeQuery->where('branch_id', $this->branch_id);
        }
        
        $storeIds = $storeQuery->pluck('id')->toArray();
        if (!empty($storeIds)) {
            foreach ($storeIds as $storeId) {
                $store = InventoryStore::find($storeId);
                if ($store) {
                    // Direct update without calling back to warehouse
                    $itemQuery = $store->items();
                    
                    // Filter items by institute if set
                    if ($this->institute_id) {
                        $itemQuery->where('institute_id', $this->institute_id);
                    }
                    
                    // Filter items by branch if set
                    if ($this->branch_id) {
                        $itemQuery->where('branch_id', $this->branch_id);
                    }
                    
                    $store->current_utilization = $itemQuery->sum('current_stock') ?? 0;
                    $store->save();
                }
            }
        }
        
        return $this;
    }

    /**
     * Get utilization percentage
     */
    public function getUtilizationPercentageAttribute()
    {
        if ($this->capacity > 0) {
            return round(($this->current_utilization / $this->capacity) * 100, 2);
        }
        return 0;
    }

    /**
     * Get utilization status (low, medium, high, full)
     */
    public function getUtilizationStatusAttribute()
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
    public function getUtilizationStatusBadgeAttribute()
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
    public function getUtilizationStatusTextAttribute()
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
     * Check if warehouse is full
     */
    public function isFull()
    {
        if ($this->capacity > 0) {
            return $this->current_utilization >= $this->capacity;
        }
        return false;
    }

    /**
     * Check if warehouse can accept items
     * Now checks total capacity across all stores
     */
    public function canAcceptItems($quantity = 1)
    {
        // Check warehouse capacity
        if ($this->capacity > 0) {
            if (($this->current_utilization + $quantity) > $this->capacity) {
                Log::info('Warehouse cannot accept items: Warehouse at capacity', [
                    'warehouse_id' => $this->id,
                    'warehouse_name' => $this->warehouse_name,
                    'capacity' => $this->capacity,
                    'current_utilization' => $this->current_utilization,
                    'requested' => $quantity,
                    'available' => $this->capacity - $this->current_utilization,
                ]);
                return false;
            }
        }

        // Check total store capacity
        $stores = $this->stores()->where('status', 1)->get();
        $totalStoreCapacity = 0;
        $totalStoreUtilization = 0;

        foreach ($stores as $store) {
            if ($store->capacity > 0) {
                $totalStoreCapacity += $store->capacity;
                $totalStoreUtilization += $store->current_utilization ?? 0;
            }
        }

        // If stores have capacity limits, check against total
        if ($totalStoreCapacity > 0) {
            if (($totalStoreUtilization + $quantity) > $totalStoreCapacity) {
                Log::info('Warehouse cannot accept items: All stores at capacity', [
                    'warehouse_id' => $this->id,
                    'warehouse_name' => $this->warehouse_name,
                    'total_store_capacity' => $totalStoreCapacity,
                    'total_store_utilization' => $totalStoreUtilization,
                    'requested' => $quantity,
                    'available' => $totalStoreCapacity - $totalStoreUtilization,
                ]);
                return false;
            }
        }

        return true;
    }

    /**
     * Check if a specific store in this warehouse can accept items
     */
    public function canStoreAcceptItems($storeId, $quantity = 1): bool
    {
        $store = $this->stores()->find($storeId);
        if (!$store) {
            return false;
        }
        return $store->canAcceptItems($quantity);
    }

    /**
     * Get available capacity including store capacities
     */
    public function getAvailableCapacityWithStoresAttribute()
    {
        $warehouseAvailable = $this->available_capacity;
        $storeAvailable = 0;

        $stores = $this->stores()->where('status', 1)->get();
        foreach ($stores as $store) {
            if ($store->capacity > 0) {
                $storeAvailable += max(0, $store->capacity - ($store->current_utilization ?? 0));
            }
        }

        // Return the smaller of warehouse capacity and total store capacity
        return min($warehouseAvailable, $storeAvailable);
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
    public function getRecommendedReorderThresholdAttribute()
    {
        if ($this->capacity > 0) {
            return round($this->capacity * 0.2);
        }
        return 0;
    }

    // ==================== HELPER METHODS ====================

    public function getTotalStoresCountAttribute()
    {
        return $this->getStoresCount();
    }

    public function getTotalItemsCountAttribute()
    {
        $query = $this->items();
        
        if ($this->institute_id) {
            $query->where('institute_id', $this->institute_id);
        }
        if ($this->branch_id) {
            $query->where('branch_id', $this->branch_id);
        }
        
        return $query->sum('current_stock');
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->pincode
        ]);
        return implode(', ', $parts);
    }

    public function getStatusTextAttribute()
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    public function getStatusBadgeAttribute()
    {
        return $this->status ? 'bg-success' : 'bg-danger';
    }

    public function getLocationPathAttribute()
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
     * Get warehouse summary statistics
     */
    public function getSummaryAttribute()
    {
        $itemQuery = $this->items();
        $storeQuery = $this->stores();
        
        if ($this->institute_id) {
            $itemQuery->where('institute_id', $this->institute_id);
            $storeQuery->where('institute_id', $this->institute_id);
        }
        if ($this->branch_id) {
            $itemQuery->where('branch_id', $this->branch_id);
            $storeQuery->where('branch_id', $this->branch_id);
        }
        
        $items = $itemQuery->get();
        $stores = $storeQuery->get();
        
        return [
            'total_items' => $items->count(),
            'total_stock' => $items->sum('current_stock'),
            'total_stores' => $stores->count(),
            'active_stores' => $stores->where('status', 1)->count(),
            'full_stores' => $stores->filter(function ($store) {
                return $store->isFull();
            })->count(),
            'utilization_percentage' => $this->getUtilizationPercentageAttribute(),
            'available_capacity' => $this->available_capacity_with_stores,
            'low_stock_items' => $this->getLowStockItems()->count(),
            'out_of_stock_items' => $this->getOutOfStockItems()->count(),
            'expired_items' => $this->getExpiredItems()->count(),
        ];
    }

    // ==================== ITEM MANAGEMENT METHODS ====================

    public function getLowStockItems()
    {
        $query = $this->items()
            ->whereColumn('available_stock', '<=', 'reorder_level')
            ->where('available_stock', '>', 0);
        
        if ($this->institute_id) {
            $query->where('institute_id', $this->institute_id);
        }
        if ($this->branch_id) {
            $query->where('branch_id', $this->branch_id);
        }
        
        return $query->get();
    }

    public function getOutOfStockItems()
    {
        $query = $this->items()
            ->where('available_stock', '<=', 0);
        
        if ($this->institute_id) {
            $query->where('institute_id', $this->institute_id);
        }
        if ($this->branch_id) {
            $query->where('branch_id', $this->branch_id);
        }
        
        return $query->get();
    }

    public function getExpiredItems()
    {
        $query = $this->items()
            ->where('expiry_date', '<', now());
        
        if ($this->institute_id) {
            $query->where('institute_id', $this->institute_id);
        }
        if ($this->branch_id) {
            $query->where('branch_id', $this->branch_id);
        }
        
        return $query->get();
    }

    // ==================== SCOPES ====================

    public function scopeDefault($query)
    {
        return $query->where('is_default', 1);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Update scopeByInstitute() to also filter by branch_id when applicable
    public function scopeByInstitute($query, $instituteId, $branchId = null)
    {
        $query->where('institute_id', $instituteId);
        
        // Also filter by branch if provided
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }
        
        return $query;
    }

    // Add scopeByBranch() scope method
    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeHasAvailableCapacity($query)
    {
        return $query->whereRaw('capacity > current_utilization OR capacity IS NULL OR capacity = 0');
    }

    public function scopeIsFull($query)
    {
        return $query->whereRaw('capacity > 0 AND current_utilization >= capacity');
    }

    public function scopeWithLowStock($query)
    {
        return $query->whereHas('items', function ($q) {
            $q->whereColumn('available_stock', '<=', 'reorder_level')
              ->where('available_stock', '>', 0);
        });
    }

    /**
     * Scope to get warehouses that have stores with available capacity
     */
    public function scopeWithStoreCapacity($query, $quantity = 1)
    {
        return $query->whereHas('stores', function($q) use ($quantity) {
            $q->where('status', 1)
              ->where(function($sub) use ($quantity) {
                  $sub->where('capacity', 0)
                      ->orWhereNull('capacity')
                      ->orWhereRaw('current_utilization + ? <= capacity', [$quantity]);
              });
        });
    }

    // ==================== BULK OPERATIONS ====================

    public static function batchUpdateUtilization(array $warehouseIds)
    {
        $warehouses = self::whereIn('id', $warehouseIds)->get();
        foreach ($warehouses as $warehouse) {
            $warehouse->updateUtilization();
        }
        return $warehouses;
    }

    public static function updateAllUtilization()
    {
        $warehouses = self::all();
        foreach ($warehouses as $warehouse) {
            $warehouse->updateUtilization();
        }
        return $warehouses;
    }

    public function getItemsSummary()
    {
        $query = $this->items();
        
        if ($this->institute_id) {
            $query->where('institute_id', $this->institute_id);
        }
        if ($this->branch_id) {
            $query->where('branch_id', $this->branch_id);
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

    public function getStockValueSummary()
    {
        $query = $this->items();
        
        if ($this->institute_id) {
            $query->where('institute_id', $this->institute_id);
        }
        if ($this->branch_id) {
            $query->where('branch_id', $this->branch_id);
        }
        
        $items = $query->get();
        
        return [
            'total_value' => $items->sum(function ($item) {
                return $item->current_stock * $item->buying_price;
            }),
            'by_category' => $items->groupBy('category_id')->map(function ($group) {
                return [
                    'category' => $group->first()->category?->category_name ?? 'Uncategorized',
                    'total_stock' => $group->sum('current_stock'),
                    'total_value' => $group->sum(function ($item) {
                        return $item->current_stock * $item->buying_price;
                    })
                ];
            })->values()
        ];
    }

    public function canReceiveTransfer($items)
    {
        $totalQuantity = 0;
        foreach ($items as $item) {
            $totalQuantity += $item['quantity'] ?? 0;
        }
        
        return $this->canAcceptItems($totalQuantity);
    }

    public function getTransferRecommendations()
    {
        $recommendations = [];
        
        if ($this->getUtilizationPercentageAttribute() > 80) {
            $recommendations[] = [
                'type' => 'warning',
                'message' => "Warehouse is at {$this->getUtilizationPercentageAttribute()}% capacity. Consider transferring items to other warehouses.",
                'available_capacity' => $this->available_capacity_with_stores,
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
        
        return $recommendations;
    }
}