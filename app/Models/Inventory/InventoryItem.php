<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\Inventory\InventoryLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryItem extends Model
{
    use SoftDeletes;

    protected $table = 'inventory_items';

    protected $guarded = [];

    protected $casts = [
        // Boolean fields
        'track_batch' => 'boolean',
        'track_serial' => 'boolean',
        'track_expiry' => 'boolean',
        'depreciation_applicable' => 'boolean',
        'status' => 'boolean',
        'is_default' => 'boolean',
        
        // Decimal fields
        'buying_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'cess' => 'decimal:2',
        'salvage_value' => 'decimal:2',
        'current_stock' => 'decimal:2',
        'available_stock' => 'decimal:2',
        'reserved_stock' => 'decimal:2',
        'opening_stock' => 'decimal:2',
        'opening_stock_value' => 'decimal:2',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        
        // Date fields
        'manufacturing_date' => 'date',
        'expiry_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        
        // Array fields
        'custom_fields' => 'array',
        'meta_data' => 'array',
        'attributes' => 'array',
        'units' => 'array',
    ];

    protected $dates = [
        'manufacturing_date',
        'expiry_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [
        'display_name',
        'stock_status',
        'stock_status_badge',
        'stock_status_text',
        'formatted_buying_price',
        'formatted_selling_price',
        'formatted_stock_value',
        'profit_per_unit',
        'margin',
        'stock_value',
        'item_type_text',
        'item_type_badge',
        'unit_display',
        'location_full',
        'location_name',
        'location_type',
        'location_hierarchy',
        'book_value',
        'remaining_life',
        'days_until_expiry',
        'shelf_life_status',
        'shelf_life_badge',
        'transfer_readiness',
        'transfer_status_badge',
        'transfer_status_text',
        'asset_instance_counts',
        'total_batches_quantity',
        'total_assets_count',
        'is_transferable',
        'can_be_transferred',
        'reserved_quantity',
        'available_for_transfer',
    ];

    // ============================================================
    // ==================== CONSTANTS ====================
    // ============================================================
    
    const TYPE_CONSUMABLE = 'CONSUMABLE';
    const TYPE_NON_CONSUMABLE = 'NON_CONSUMABLE';
    const TYPE_ASSET = 'ASSET';
    const TYPE_SERVICE = 'SERVICE';
    const TYPE_RAW_MATERIAL = 'RAW_MATERIAL';
    const TYPE_FINISHED_GOOD = 'FINISHED_GOOD';

    const DEPRECIATION_METHOD_STRAIGHT_LINE = 'straight_line';
    const DEPRECIATION_METHOD_DECLINING_BALANCE = 'declining_balance';
    const DEPRECIATION_METHOD_SUM_OF_YEARS = 'sum_of_years';

    const STOCK_STATUS_IN_STOCK = 'IN_STOCK';
    const STOCK_STATUS_LOW_STOCK = 'LOW_STOCK';
    const STOCK_STATUS_OUT_OF_STOCK = 'OUT_OF_STOCK';
    const STOCK_STATUS_EXPIRED = 'EXPIRED';
    const STOCK_STATUS_DISCONTINUED = 'DISCONTINUED';

    // ============================================================
    // ==================== RELATIONSHIPS ====================
    // ============================================================

    /**
     * Warehouse relationship
     */
    public function warehouse()
    {
        return $this->belongsTo(InventoryWarehouse::class, 'warehouse_id');
    }

    /**
     * Store relationship
     */
    public function store()
    {
        return $this->belongsTo(InventoryStore::class, 'store_id');
    }

    /**
     * Category relationship
     */
    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'category_id');
    }

    /**
     * Subcategory relationship
     */
    public function subcategory()
    {
        return $this->belongsTo(InventorySubCategory::class, 'subcategory_id');
    }

    /**
     * Tax rate relationship
     */
    public function taxRate()
    {
        return $this->belongsTo(InventoryTaxRate::class, 'tax_rate_id');
    }

    /**
     * Vendor relationship
     */
    public function vendor()
    {
        return $this->belongsTo(InventoryVendor::class, 'vendor_id');
    }

    /**
     * Stock movements relationship
     */
    public function stockMovements()
    {
        return $this->hasMany(InventoryStockMovement::class, 'item_id');
    }

    /**
     * Warehouse transfers relationship
     */
    public function warehouseTransfers()
    {
        return $this->hasMany(InventoryWarehouseTransfer::class, 'item_id');
    }

    /**
     * Receipts out relationship
     */
    public function receiptsOut()
    {
        return $this->hasMany(InventoryReceiptOut::class, 'item_id');
    }

    /**
     * Receipts in relationship
     */
    public function receiptsIn()
    {
        return $this->hasMany(InventoryReceiptIn::class, 'item_id');
    }

    /**
     * Batches relationship
     */
    public function batches()
    {
        return $this->hasMany(InventoryItemBatch::class, 'item_id');
    }

    /**
     * Asset instances relationship
     */
    public function assetInstances()
    {
        return $this->hasMany(InventoryAssetInstance::class, 'item_id');
    }

    /**
     * Depreciation logs relationship
     */
    public function depreciationLogs()
    {
        return $this->hasMany(InventoryDepreciationLog::class, 'item_id');
    }

    // ============================================================
    // ==================== SCOPES ====================
    // ============================================================

    /**
     * Scope to filter by branch
     */
    public function scopeByBranch($query, $branchId = null)
    {
        if ($branchId) {
            return $query->where('branch_id', $branchId);
        }
        return $query;
    }

    /**
     * Scope to search across multiple fields
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('item_name', 'LIKE', "%{$search}%")
              ->orWhere('item_code', 'LIKE', "%{$search}%")
              ->orWhere('sku', 'LIKE', "%{$search}%")
              ->orWhere('barcode', 'LIKE', "%{$search}%")
              ->orWhere('qr_code', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%")
              ->orWhere('brand', 'LIKE', "%{$search}%")
              ->orWhere('model', 'LIKE', "%{$search}%")
              ->orWhere('manufacturer', 'LIKE', "%{$search}%")
              ->orWhere('manufacturer_part_number', 'LIKE', "%{$search}%")
              ->orWhere('hsn_code', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Scope to filter by institute
     */
    public function scopeByInstitute($query, $instituteId)
    {
        return $query->where('institute_id', $instituteId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))
                     ->where('expiry_date', '>', now());
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('available_stock', '<=', 'reorder_level')
                     ->where('available_stock', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('available_stock', '<=', 0);
    }

    public function scopeInStock($query)
    {
        return $query->where('available_stock', '>', 0)
                     ->whereColumn('available_stock', '>', 'reorder_level');
    }

    /**
     * Scope for items with reserved stock
     */
    public function scopeHasReservedStock($query)
    {
        return $query->where('reserved_stock', '>', 0);
    }

    /**
     * Scope for items with no reserved stock
     */
    public function scopeNoReservedStock($query)
    {
        return $query->where('reserved_stock', '<=', 0);
    }

    /**
     * Scope to get items with available stock (excluding reserved)
     */
    public function scopeWithAvailableStock($query, $minimum = 1)
    {
        return $query->whereRaw('(available_stock - reserved_stock) >= ?', [$minimum]);
    }

    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    public function scopeByStore($query, $storeId)
    {
        return $query->where('store_id', $storeId);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeBySubcategory($query, $subcategoryId)
    {
        return $query->where('subcategory_id', $subcategoryId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('item_type', $type);
    }

    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    public function scopeByBatchTracked($query)
    {
        return $query->where('track_batch', 1);
    }

    public function scopeBySerialTracked($query)
    {
        return $query->where('track_serial', 1);
    }

    public function scopeByExpiryTracked($query)
    {
        return $query->where('track_expiry', 1);
    }

    public function scopeWithDepreciation($query)
    {
        return $query->where('depreciation_applicable', 1);
    }

    public function scopeByStockStatus($query, $status)
    {
        switch ($status) {
            case self::STOCK_STATUS_IN_STOCK:
                return $query->inStock();
            case self::STOCK_STATUS_LOW_STOCK:
                return $query->lowStock();
            case self::STOCK_STATUS_OUT_OF_STOCK:
                return $query->outOfStock();
            case self::STOCK_STATUS_EXPIRED:
                return $query->expired();
            default:
                return $query;
        }
    }

    // ============================================================
    // ==================== ACCESSORS ====================
    // ============================================================

    /**
     * Get display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->item_name . ' (' . $this->item_code . ')';
    }

    /**
     * Get stock status
     */
    public function getStockStatusAttribute()
    {
        if (!$this->status) {
            return self::STOCK_STATUS_DISCONTINUED;
        }
        
        if ($this->isExpired()) {
            return self::STOCK_STATUS_EXPIRED;
        }
        
        if ($this->available_stock <= 0) {
            return self::STOCK_STATUS_OUT_OF_STOCK;
        }
        
        if ($this->needsReorder()) {
            return self::STOCK_STATUS_LOW_STOCK;
        }
        
        return self::STOCK_STATUS_IN_STOCK;
    }

    /**
     * Get stock status badge
     */
    public function getStockStatusBadgeAttribute()
    {
        $statuses = [
            self::STOCK_STATUS_IN_STOCK => 'bg-success',
            self::STOCK_STATUS_LOW_STOCK => 'bg-warning text-dark',
            self::STOCK_STATUS_OUT_OF_STOCK => 'bg-danger',
            self::STOCK_STATUS_EXPIRED => 'bg-dark',
            self::STOCK_STATUS_DISCONTINUED => 'bg-secondary',
        ];
        
        return $statuses[$this->stock_status] ?? 'bg-secondary';
    }

    /**
     * Get stock status text
     */
    public function getStockStatusTextAttribute()
    {
        $statuses = [
            self::STOCK_STATUS_IN_STOCK => 'In Stock',
            self::STOCK_STATUS_LOW_STOCK => 'Low Stock',
            self::STOCK_STATUS_OUT_OF_STOCK => 'Out of Stock',
            self::STOCK_STATUS_EXPIRED => 'Expired',
            self::STOCK_STATUS_DISCONTINUED => 'Discontinued',
        ];
        
        return $statuses[$this->stock_status] ?? 'Unknown';
    }

    /**
     * Get reserved quantity
     */
    public function getReservedQuantityAttribute()
    {
        return $this->reserved_stock ?? 0;
    }

    /**
     * Get available for transfer (available_stock - reserved_stock)
     */
    public function getAvailableForTransferAttribute()
    {
        return max(0, $this->available_stock - ($this->reserved_stock ?? 0));
    }

    /**
     * Get formatted buying price
     */
    public function getFormattedBuyingPriceAttribute()
    {
        return '₹' . number_format($this->buying_price, 2);
    }

    /**
     * Get formatted selling price
     */
    public function getFormattedSellingPriceAttribute()
    {
        return '₹' . number_format($this->selling_price, 2);
    }

    /**
     * Get formatted stock value
     */
    public function getFormattedStockValueAttribute()
    {
        return '₹' . number_format($this->stock_value, 2);
    }

    /**
     * Get profit per unit
     */
    public function getProfitPerUnitAttribute()
    {
        return $this->selling_price - $this->buying_price;
    }

    /**
     * Get margin percentage
     */
    public function getMarginAttribute()
    {
        if ($this->buying_price > 0) {
            return round((($this->selling_price - $this->buying_price) / $this->buying_price) * 100, 2);
        }
        return 0;
    }

    /**
     * Get stock value
     */
    public function getStockValueAttribute()
    {
        return $this->current_stock * $this->buying_price;
    }

    /**
     * Get item type text
     */
    public function getItemTypeTextAttribute()
    {
        $types = self::getItemTypes();
        return $types[$this->item_type] ?? $this->item_type;
    }

    /**
     * Get item type badge
     */
    public function getItemTypeBadgeAttribute()
    {
        $badges = [
            self::TYPE_CONSUMABLE => 'bg-primary',
            self::TYPE_NON_CONSUMABLE => 'bg-info',
            self::TYPE_ASSET => 'bg-warning text-dark',
            self::TYPE_SERVICE => 'bg-success',
            self::TYPE_RAW_MATERIAL => 'bg-secondary',
            self::TYPE_FINISHED_GOOD => 'bg-dark',
        ];
        return $badges[$this->item_type] ?? 'bg-secondary';
    }

    /**
     * Get unit display
     */
    public function getUnitDisplayAttribute()
    {
        return $this->unit_name . ' (' . $this->unit_code . ')';
    }

    /**
     * Get full location
     */
    public function getLocationFullAttribute()
    {
        $parts = [];
        if ($this->rack_number) $parts[] = "Rack: {$this->rack_number}";
        if ($this->shelf_number) $parts[] = "Shelf: {$this->shelf_number}";
        if ($this->bin_number) $parts[] = "Bin: {$this->bin_number}";
        return implode(' | ', $parts) ?: 'Not specified';
    }

    /**
     * Get location name
     */
    public function getLocationNameAttribute()
    {
        if ($this->warehouse_id && $this->warehouse) {
            return $this->warehouse->warehouse_name;
        } elseif ($this->store_id && $this->store) {
            return $this->store->store_name;
        }
        return 'N/A';
    }

    /**
     * Get location type
     */
    public function getLocationTypeAttribute()
    {
        if ($this->warehouse_id) {
            return 'Warehouse';
        } elseif ($this->store_id) {
            return 'Store';
        }
        return 'Unknown';
    }

    /**
     * Get location hierarchy
     */
    public function getLocationHierarchyAttribute()
    {
        $parts = [];
        
        if ($this->warehouse) {
            $parts[] = "🏢 " . $this->warehouse->warehouse_name;
        }
        
        if ($this->store) {
            $parts[] = "🏪 " . $this->store->store_name;
        }
        
        if ($this->rack_number) $parts[] = "Rack: {$this->rack_number}";
        if ($this->shelf_number) $parts[] = "Shelf: {$this->shelf_number}";
        if ($this->bin_number) $parts[] = "Bin: {$this->bin_number}";
        
        return implode(' → ', $parts);
    }

    /**
     * Get book value
     */
    public function getBookValueAttribute()
    {
        if (!$this->isDepreciationApplicable()) {
            return $this->buying_price;
        }
        
        $bookValue = $this->buying_price - $this->calculateDepreciation();
        return round(max($bookValue, 0), 2);
    }

    /**
     * Get remaining life
     */
    public function getRemainingLifeAttribute()
    {
        if (!$this->isDepreciationApplicable() || !$this->asset_life_months) {
            return null;
        }
        
        if ($this->created_at) {
            $ageInMonths = $this->created_at->diffInMonths(now());
            return max(0, $this->asset_life_months - $ageInMonths);
        }
        
        return $this->asset_life_months;
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

    /**
     * Get shelf life status
     */
    public function getShelfLifeStatusAttribute()
    {
        if (!$this->expiry_date) {
            return 'Not Set';
        }
        if ($this->isExpired()) {
            return 'Expired';
        }
        if ($this->days_until_expiry <= 7) {
            return 'Expiring Soon';
        }
        return 'Valid';
    }

    /**
     * Get shelf life badge
     */
    public function getShelfLifeBadgeAttribute()
    {
        if (!$this->expiry_date) {
            return 'bg-secondary';
        }
        if ($this->isExpired()) {
            return 'bg-danger';
        }
        if ($this->days_until_expiry <= 7) {
            return 'bg-warning text-dark';
        }
        return 'bg-success';
    }

    /**
     * Get transfer readiness
     */
    public function getTransferReadinessAttribute()
    {
        $availableForTransfer = $this->available_for_transfer;
        
        $readiness = [
            'can_transfer' => $availableForTransfer > 0,
            'has_batches' => $this->track_batch && $this->hasAvailableBatches(),
            'has_assets' => $this->item_type === self::TYPE_ASSET && $this->hasAvailableAssets(),
            'is_active' => (bool) $this->status,
            'stock_available' => $this->available_stock,
            'reserved_stock' => $this->reserved_stock ?? 0,
            'available_for_transfer' => $availableForTransfer,
        ];
        
        $issues = [];
        if (!$readiness['is_active']) {
            $issues[] = 'Item is inactive';
        }
        if ($readiness['stock_available'] <= 0) {
            $issues[] = 'No stock available';
        }
        if ($readiness['reserved_stock'] > 0 && $readiness['available_for_transfer'] <= 0) {
            $issues[] = 'All stock is reserved';
        }
        if ($this->track_batch && !$readiness['has_batches']) {
            $issues[] = 'No batches available for batch-tracked item';
        }
        if ($this->item_type === self::TYPE_ASSET && !$readiness['has_assets'] && $readiness['stock_available'] > 0) {
            $issues[] = 'No active asset instances available';
        }
        
        $readiness['issues'] = $issues;
        $readiness['is_ready'] = empty($issues) && $readiness['available_for_transfer'] > 0;
        
        return $readiness;
    }

    /**
     * Get transfer status badge
     */
    public function getTransferStatusBadgeAttribute()
    {
        $readiness = $this->transfer_readiness;
        
        if ($readiness['is_ready']) {
            return 'bg-success';
        }
        if (count($readiness['issues']) > 0) {
            return 'bg-warning text-dark';
        }
        return 'bg-secondary';
    }

    /**
     * Get transfer status text
     */
    public function getTransferStatusTextAttribute()
    {
        $readiness = $this->transfer_readiness;
        
        if ($readiness['is_ready']) {
            return 'Ready for Transfer';
        }
        if (count($readiness['issues']) > 0) {
            return 'Issues: ' . implode(', ', $readiness['issues']);
        }
        return 'Not Available';
    }

    /**
     * Get asset instance counts
     */
    public function getAssetInstanceCountsAttribute()
    {
        return [
            'total' => $this->assetInstances()->count(),
            'active' => $this->assetInstances()->where('status', 'ACTIVE')->count(),
            'damaged' => $this->assetInstances()->where('status', 'DAMAGED')->count(),
            'assigned' => $this->assetInstances()->where('status', 'ASSIGNED')->count(),
            'under_repair' => $this->assetInstances()->where('status', 'UNDER_REPAIR')->count(),
            'disposed' => $this->assetInstances()->where('status', 'DISPOSED')->count(),
            'sold' => $this->assetInstances()->where('status', 'SOLD')->count(),
            'transferred' => $this->assetInstances()->where('status', 'TRANSFERRED')->count(),
        ];
    }

    /**
     * Get total batches quantity
     */
    public function getTotalBatchesQuantityAttribute()
    {
        return $this->getTotalBatchesQuantity();
    }

    /**
     * Get total assets count
     */
    public function getTotalAssetsCountAttribute()
    {
        return $this->getTotalAssetsCount();
    }

    /**
     * Check if item is transferable
     */
    public function getIsTransferableAttribute()
    {
        return $this->available_stock > 0 && $this->status;
    }

    /**
     * Check if item can be transferred (with reserved stock check)
     */
    public function getCanBeTransferredAttribute()
    {
        return $this->canBeTransferred();
    }

    // ============================================================
    // ==================== VALIDATION RULES ====================
    // ============================================================

    /**
     * Get validation rules as static method
     */
    public static function getValidationRules($isUpdate = false, $itemId = null)
    {
        $rules = [
            'category_id' => 'required|exists:inventory_categories,id',
            'subcategory_id' => 'nullable|exists:inventory_sub_categories,id',
            'warehouse_id' => 'required|exists:inventory_warehouses,id',
            'store_id' => 'nullable|exists:inventory_stores,id',
            'unit_id' => 'required|string|max:50',
            'unit_name' => 'required|string|max:100',
            'unit_code' => 'required|string|max:20',
            'item_name' => 'required|string|max:255',
            'item_type' => 'required|in:' . implode(',', array_keys(self::getItemTypes())),
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'manufacturer_part_number' => 'nullable|string|max:255',
            'hsn_code' => 'nullable|string|max:50',
            'barcode' => 'nullable|string|max:100|unique:inventory_items,barcode,' . ($itemId ? $itemId : 'NULL'),
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'tax_percentage' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0',
            'cess' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|numeric|min:0',
            'reorder_quantity' => 'nullable|numeric|min:0',
            'max_stock' => 'nullable|numeric|min:0',
            'rack_number' => 'nullable|string|max:50',
            'shelf_number' => 'nullable|string|max:50',
            'bin_number' => 'nullable|string|max:50',
            'track_batch' => 'nullable|boolean',
            'track_serial' => 'nullable|boolean',
            'track_expiry' => 'nullable|boolean',
            'depreciation_applicable' => 'nullable|boolean',
            'asset_life_months' => 'nullable|integer|min:0',
            'depreciation_method' => 'nullable|in:' . implode(',', array_keys(self::getDepreciationMethods())),
            'salvage_value' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ];

        if ($isUpdate && $itemId) {
            $rules['barcode'] = 'nullable|string|max:100|unique:inventory_items,barcode,' . $itemId;
        }

        return $rules;
    }

    // ============================================================
    // ==================== HELPER METHODS ====================
    // ============================================================

    /**
     * Get item types
     */
    public static function getItemTypes()
    {
        return [
            self::TYPE_CONSUMABLE => 'Consumable',
            self::TYPE_NON_CONSUMABLE => 'Non-Consumable',
            self::TYPE_ASSET => 'Asset',
            self::TYPE_SERVICE => 'Service',
            self::TYPE_RAW_MATERIAL => 'Raw Material',
            self::TYPE_FINISHED_GOOD => 'Finished Product',
        ];
    }

    /**
     * Get depreciation methods
     */
    public static function getDepreciationMethods()
    {
        return [
            self::DEPRECIATION_METHOD_STRAIGHT_LINE => 'Straight Line',
            self::DEPRECIATION_METHOD_DECLINING_BALANCE => 'Declining Balance',
            self::DEPRECIATION_METHOD_SUM_OF_YEARS => 'Sum of Years',
        ];
    }

    /**
     * Get actual warehouse (from store if exists)
     */
    public function getActualWarehouseAttribute()
    {
        if ($this->store) {
            return $this->store->warehouse;
        }
        return $this->warehouse;
    }

    /**
     * Check if item is predefined category
     */
    public function getIsPredefinedCategoryAttribute()
    {
        $predefined = [
            'Stationery', 'Furniture', 'Electronics', 'IT Assets',
            'Grocery', 'Vegetables', 'Fruits', 'Dairy',
            'Kitchen Items', 'Cleaning Material', 'Uniform',
            'Books', 'Medicines', 'Sports Items',
            'Consumables', 'Non Consumables'
        ];
        return in_array($this->category?->category_name, $predefined);
    }

    /**
     * Check if item has custom fields
     */
    public function hasCustomFields()
    {
        return !empty($this->custom_fields);
    }

    /**
     * Check if item is expired
     */
    public function isExpired()
    {
        if (!$this->expiry_date) {
            return false;
        }
        return $this->expiry_date < now();
    }

    /**
     * Check if item needs reorder
     */
    public function needsReorder()
    {
        return $this->available_stock <= $this->reorder_level && $this->available_stock > 0;
    }

    /**
     * Check if depreciation is applicable
     */
    public function isDepreciationApplicable()
    {
        return (bool) $this->depreciation_applicable;
    }

    /**
     * Calculate depreciation
     */
    public function calculateDepreciation($months = null)
    {
        if (!$this->isDepreciationApplicable()) {
            return 0;
        }
        
        $lifeMonths = $months ?? $this->asset_life_months;
        if (!$lifeMonths || $lifeMonths <= 0) {
            return 0;
        }
        
        $monthlyDepreciation = $this->buying_price / $lifeMonths;
        
        if ($this->created_at) {
            $ageInMonths = $this->created_at->diffInMonths(now());
            $accumulatedDepreciation = $monthlyDepreciation * min($ageInMonths, $lifeMonths);
            return round($accumulatedDepreciation, 2);
        }
        
        return 0;
    }

    /**
     * Get active batches
     */
    public function getActiveBatches()
    {
        return $this->batches()
            ->where('remaining_quantity', '>', 0)
            ->where(function($query) {
                $query->whereNull('expiry_date')
                      ->orWhere('expiry_date', '>=', now());
            })
            ->get();
    }

    /**
     * Get FEFO batches (First Expiry First Out)
     */
    public function getFEFOBatches()
    {
        return $this->batches()
            ->where('remaining_quantity', '>', 0)
            ->orderBy('expiry_date', 'asc')
            ->get();
    }

    /**
     * Get batch by number
     */
    public function getBatchByNumber($batchNumber)
    {
        return $this->batches()
            ->where('batch_number', $batchNumber)
            ->first();
    }

    /**
     * Get batches for transfer
     */
    public function getBatchesForTransfer($quantity = null)
    {
        $query = $this->batches()
            ->where('remaining_quantity', '>', 0)
            ->orderBy('expiry_date', 'asc');
        
        if ($quantity) {
            $query->limit(10);
        }
        
        return $query->get();
    }

    /**
     * Get assets for transfer
     */
    public function getAssetsForTransfer($quantity = null)
    {
        $query = $this->assetInstances()
            ->where('status', 'ACTIVE');
        
        if ($quantity) {
            $query->limit($quantity);
        }
        
        return $query->get();
    }

    /**
     * Check if item has available batches
     */
    public function hasAvailableBatches($quantity = null)
    {
        $availableQty = $this->batches()
            ->where('remaining_quantity', '>', 0)
            ->sum('remaining_quantity');
        
        if ($quantity) {
            return $availableQty >= $quantity;
        }
        
        return $availableQty > 0;
    }

    /**
     * Check if item has available assets
     */
    public function hasAvailableAssets($quantity = null)
    {
        $availableCount = $this->assetInstances()
            ->where('status', 'ACTIVE')
            ->count();
        
        if ($quantity) {
            return $availableCount >= $quantity;
        }
        
        return $availableCount > 0;
    }

    /**
     * Get total batches quantity
     */
    public function getTotalBatchesQuantity()
    {
        return $this->batches()
            ->where('remaining_quantity', '>', 0)
            ->sum('remaining_quantity');
    }

    /**
     * Get total assets count
     */
    public function getTotalAssetsCount()
    {
        return $this->assetInstances()
            ->where('status', 'ACTIVE')
            ->count();
    }

    /**
     * Get batch transfer summary
     */
    public function getBatchTransferSummary()
    {
        if (!$this->track_batch) {
            return null;
        }
        
        $batches = $this->batches()
            ->where('remaining_quantity', '>', 0)
            ->orderBy('expiry_date', 'asc')
            ->get();
        
        return [
            'total_batches' => $batches->count(),
            'total_quantity' => $batches->sum('remaining_quantity'),
            'oldest_expiry' => $batches->first()?->expiry_date,
            'newest_expiry' => $batches->last()?->expiry_date,
            'batches' => $batches->map(function($batch) {
                return [
                    'id' => $batch->id,
                    'batch_number' => $batch->batch_number,
                    'remaining_quantity' => $batch->remaining_quantity,
                    'expiry_date' => $batch->expiry_date,
                    'is_expiring_soon' => $batch->isExpiringSoon(30),
                    'is_expired' => $batch->isExpired()
                ];
            })
        ];
    }

    /**
     * Get asset transfer summary
     */
    public function getAssetTransferSummary()
    {
        if ($this->item_type !== self::TYPE_ASSET) {
            return null;
        }
        
        $assets = $this->assetInstances()
            ->where('status', 'ACTIVE')
            ->get();
        
        return [
            'total_assets' => $assets->count(),
            'assets' => $assets->map(function($asset) {
                return [
                    'id' => $asset->id,
                    'asset_code' => $asset->asset_code,
                    'serial_number' => $asset->serial_number,
                    'purchase_date' => $asset->purchase_date,
                    'current_value' => $asset->current_value,
                    'is_in_warranty' => $asset->isInWarranty()
                ];
            })
        ];
    }

    /**
     * Get location path for transfer
     */
    public function getLocationPathForTransfer()
    {
        if ($this->warehouse) {
            return $this->warehouse->warehouse_name . 
                   ($this->warehouse->warehouse_code ? " ({$this->warehouse->warehouse_code})" : "");
        }
        
        if ($this->store) {
            return $this->store->store_name . 
                   ($this->store->store_code ? " ({$this->store->store_code})" : "");
        }
        
        return 'Unknown Location';
    }

    /**
     * Get transfer preview details
     */
    public function getTransferPreviewDetails()
    {
        return [
            'id' => $this->id,
            'item_name' => $this->item_name,
            'item_code' => $this->item_code,
            'sku' => $this->sku,
            'current_stock' => $this->current_stock,
            'available_stock' => $this->available_stock,
            'reserved_stock' => $this->reserved_stock ?? 0,
            'available_for_transfer' => $this->available_for_transfer,
            'unit_name' => $this->unit_name,
            'track_batch' => $this->track_batch,
            'track_serial' => $this->track_serial,
            'item_type' => $this->item_type,
            'item_type_text' => $this->item_type_text,
            'has_batches' => $this->hasAvailableBatches(),
            'has_assets' => $this->item_type === self::TYPE_ASSET && $this->hasAvailableAssets(),
            'total_batches_qty' => $this->getTotalBatchesQuantity(),
            'total_assets_count' => $this->getTotalAssetsCount(),
            'location' => $this->getLocationPathForTransfer(),
            'is_active' => (bool) $this->status
        ];
    }

    /**
     * Validate if item can be transferred
     */
    public function validateTransfer($quantity, $destinationWarehouseId = null, $destinationStoreId = null)
    {
        $errors = [];
        $warnings = [];
        
        // Check available for transfer (including reserved)
        $availableForTransfer = $this->available_for_transfer;
        if ($availableForTransfer < $quantity) {
            $errors[] = "Insufficient available stock. Available: {$availableForTransfer}, Required: {$quantity} (Reserved: {$this->reserved_stock})";
        }
        
        if ($this->track_batch) {
            $availableBatchQty = $this->getTotalBatchesQuantity();
            if ($availableBatchQty < $quantity) {
                $warnings[] = "Batch quantity available: {$availableBatchQty}, Required: {$quantity}.";
            }
        }
        
        if ($this->item_type === self::TYPE_ASSET) {
            $availableAssets = $this->getTotalAssetsCount();
            if ($availableAssets < $quantity) {
                $warnings[] = "Active assets available: {$availableAssets}, Required: {$quantity}.";
            }
        }
        
        if ($destinationWarehouseId) {
            $warehouse = InventoryWarehouse::find($destinationWarehouseId);
            if ($warehouse && !$warehouse->canAcceptItems($quantity)) {
                $errors[] = "Destination warehouse is at capacity.";
            }
        }
        
        if ($destinationStoreId) {
            $store = InventoryStore::find($destinationStoreId);
            if ($store && !$store->canAcceptItems($quantity)) {
                $errors[] = "Destination store is at capacity.";
            }
        }
        
        if (!$this->status) {
            $errors[] = "Item is inactive and cannot be transferred.";
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
            'can_transfer' => empty($errors) && empty($warnings),
            'can_transfer_with_warnings' => empty($errors),
            'available_quantity' => $availableForTransfer,
            'reserved_quantity' => $this->reserved_stock ?? 0,
        ];
    }

    /**
     * Check if item can be transferred (with reserved stock check)
     */
    public function canBeTransferred()
    {
        return $this->available_for_transfer > 0 && $this->status;
    }

    /**
     * Reserve stock for pending transactions
     * 
     * @param float $quantity Quantity to reserve
     * @param string $referenceType Type of reference (e.g., 'stock_out', 'transfer')
     * @param int $referenceId ID of the reference
     * @return $this
     * @throws \Exception
     */
    public function reserveStock($quantity, $referenceType = null, $referenceId = null)
    {
        if ($quantity <= 0) {
            throw new \Exception("Quantity must be greater than 0.");
        }

        $availableForReserve = $this->available_stock - ($this->reserved_stock ?? 0);
        if ($availableForReserve < $quantity) {
            throw new \Exception("Insufficient stock available for reservation. Available: {$availableForReserve}, Requested: {$quantity}");
        }

        $oldReserved = $this->reserved_stock ?? 0;
        $newReserved = $oldReserved + $quantity;

        $this->update([
            'reserved_stock' => $newReserved,
        ]);

        InventoryLogger::log([
            'module' => 'STOCK_RESERVATION',
            'action' => 'RESERVE',
            'record_id' => $this->id,
            'new_data' => [
                'item_name' => $this->item_name,
                'item_code' => $this->item_code,
                'quantity_reserved' => $quantity,
                'previous_reserved' => $oldReserved,
                'new_reserved' => $newReserved,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
            ],
            'remarks' => "Reserved {$quantity} units of {$this->item_name}"
        ]);

        return $this;
    }

    /**
     * Release reserved stock
     * 
     * @param float $quantity Quantity to release
     * @param string $reason Reason for release
     * @return $this
     * @throws \Exception
     */
    public function releaseReservedStock($quantity, $reason = null)
    {
        if ($quantity <= 0) {
            throw new \Exception("Quantity must be greater than 0.");
        }

        $currentReserved = $this->reserved_stock ?? 0;
        if ($currentReserved < $quantity) {
            throw new \Exception("Cannot release more than reserved. Reserved: {$currentReserved}, Requested: {$quantity}");
        }

        $newReserved = $currentReserved - $quantity;

        $this->update([
            'reserved_stock' => $newReserved,
        ]);

        InventoryLogger::log([
            'module' => 'STOCK_RESERVATION',
            'action' => 'RELEASE',
            'record_id' => $this->id,
            'new_data' => [
                'item_name' => $this->item_name,
                'item_code' => $this->item_code,
                'quantity_released' => $quantity,
                'previous_reserved' => $currentReserved,
                'new_reserved' => $newReserved,
                'reason' => $reason,
            ],
            'remarks' => "Released {$quantity} reserved units of {$this->item_name}" . ($reason ? " Reason: {$reason}" : "")
        ]);

        return $this;
    }

    /**
     * Check if item has pending reservations
     */
    public function hasPendingReservations()
    {
        return ($this->reserved_stock ?? 0) > 0;
    }

    /**
     * Get pending reservations count
     */
    public function getPendingReservationsCount()
    {
        return $this->reserved_stock ?? 0;
    }

    /**
     * Get transfer recommendation
     */
    public function getTransferRecommendation($quantity, $destinationWarehouseId = null, $destinationStoreId = null)
    {
        $validation = $this->validateTransfer($quantity, $destinationWarehouseId, $destinationStoreId);
        
        $recommendations = [];
        
        if ($this->track_batch) {
            $batchSummary = $this->getBatchTransferSummary();
            if ($batchSummary && $batchSummary['total_quantity'] > 0) {
                $recommendations[] = [
                    'type' => 'batch',
                    'message' => "Transfer using FEFO - {$batchSummary['total_batches']} batches available"
                ];
                
                $expiringBatches = $batchSummary['batches']->filter(function($batch) {
                    return $batch['is_expiring_soon'] ?? false;
                });
                if ($expiringBatches->count() > 0) {
                    $recommendations[] = [
                        'type' => 'warning',
                        'message' => "⚠️ {$expiringBatches->count()} batch(es) are expiring soon."
                    ];
                }
            } else {
                $recommendations[] = [
                    'type' => 'warning',
                    'message' => "⚠️ No batches available. New batches will be created."
                ];
            }
        }
        
        if ($this->item_type === self::TYPE_ASSET) {
            $assetSummary = $this->getAssetTransferSummary();
            if ($assetSummary && $assetSummary['total_assets'] > 0) {
                $recommendations[] = [
                    'type' => 'asset',
                    'message' => "Transferring {$assetSummary['total_assets']} active asset instance(s)"
                ];
            } else {
                $recommendations[] = [
                    'type' => 'warning',
                    'message' => "⚠️ No active assets available."
                ];
            }
        }
        
        return [
            'validation' => $validation,
            'recommendations' => $recommendations,
            'can_transfer' => $validation['can_transfer'] || $validation['can_transfer_with_warnings'],
            'has_warnings' => count($validation['warnings']) > 0 || count($recommendations) > 0,
            'available_quantity' => $validation['available_quantity'] ?? $this->available_for_transfer,
        ];
    }

    /**
     * Check if item has transferable stock
     */
    public function hasTransferableStock($minimumQuantity = 1)
    {
        return $this->available_for_transfer >= $minimumQuantity;
    }

    /**
     * Get transferable quantity
     */
    public function getTransferableQuantity()
    {
        return $this->available_for_transfer;
    }

    /**
     * Get item across all warehouses
     */
    public function getItemAcrossAllWarehouses()
    {
        return self::where('item_code', $this->item_code)
            ->with(['warehouse', 'store'])
            ->get();
    }

    /**
     * Check if item is available in other warehouse
     */
    public function isAvailableInOtherWarehouse($excludeWarehouseId = null)
    {
        $query = self::where('item_code', $this->item_code)
            ->where('available_stock', '>', 0);
        
        if ($excludeWarehouseId) {
            $query->where('warehouse_id', '!=', $excludeWarehouseId);
        }
        
        return $query->exists();
    }

    /**
     * Get total stock across all warehouses
     */
    public function getTotalStockAcrossWarehouses()
    {
        return self::where('item_code', $this->item_code)->sum('available_stock');
    }

    /**
     * Generate asset code
     */
    public function generateAssetCode()
    {
        $prefix = strtoupper(substr($this->item_code, 0, 6));
        $lastAsset = $this->assetInstances()->orderBy('id', 'desc')->first();
        $nextId = $lastAsset ? intval(substr($lastAsset->asset_code, -4)) + 1 : 1;
        return $prefix . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Update location utilization
     */
    public function updateLocationUtilization()
    {
        if ($this->warehouse_id) {
            $this->updateWarehouseUtilization($this->warehouse_id);
        }

        if ($this->store_id) {
            $this->updateStoreUtilization($this->store_id);
        }

        return $this;
    }

    /**
     * Update warehouse utilization
     */
    public function updateWarehouseUtilization($warehouseId)
    {
        $warehouse = InventoryWarehouse::find($warehouseId);
        if ($warehouse) {
            $warehouse->updateUtilization();
        }
        return $this;
    }

    /**
     * Update store utilization
     */
    public function updateStoreUtilization($storeId)
    {
        $store = InventoryStore::find($storeId);
        if ($store) {
            $store->updateUtilization();
        }
        return $this;
    }

    /**
     * Update location utilization on move
     */
    public function updateLocationUtilizationOnMove($oldWarehouseId = null, $oldStoreId = null)
    {
        if ($oldWarehouseId && $oldWarehouseId != $this->warehouse_id) {
            $this->updateWarehouseUtilization($oldWarehouseId);
        }

        if ($this->warehouse_id) {
            $this->updateWarehouseUtilization($this->warehouse_id);
        }

        if ($oldStoreId && $oldStoreId != $this->store_id) {
            $this->updateStoreUtilization($oldStoreId);
        }

        if ($this->store_id) {
            $this->updateStoreUtilization($this->store_id);
        }

        return $this;
    }

    /**
     * Add stock with stock adjustment history tracking
     */
    public function addStock($quantity, $warehouseId = null, $storeId = null, $reference = null, $notes = null, $batchData = null, $serialNumbers = null)
    {
        $previousStock = $this->current_stock;
        $newStock = $previousStock + $quantity;

        DB::transaction(function() use ($quantity, $warehouseId, $storeId, $reference, $notes, $batchData, $serialNumbers, $previousStock, $newStock) {
            $this->update([
                'current_stock' => $newStock,
                'available_stock' => $this->available_stock + $quantity,
                // Do not modify reserved_stock here - it should be managed separately
            ]);

            $this->createStockMovement('IN', $quantity, $previousStock, $newStock, $warehouseId, $storeId, $reference, $notes);
            $this->updateLocationUtilization();

            if ($batchData && $this->track_batch) {
                $this->handleBatchCreation($batchData);
            }

            if ($serialNumbers && $this->item_type === self::TYPE_ASSET) {
                $this->handleAssetCreation($serialNumbers, $warehouseId, $storeId);
            }

            // Track stock adjustment history
            $this->recordStockAdjustment($quantity, 'IN', $previousStock, $newStock, $notes);
        });

        InventoryLogger::log([
            'module' => 'STOCK',
            'action' => 'STOCK_IN',
            'record_id' => $this->id,
            'new_data' => [
                'item_name' => $this->item_name,
                'item_code' => $this->item_code,
                'quantity' => $quantity,
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'reserved_stock' => $this->reserved_stock ?? 0,
                'warehouse_id' => $warehouseId,
                'store_id' => $storeId,
                'notes' => $notes
            ],
            'remarks' => "Stock IN: {$quantity} units added to {$this->item_name}"
        ]);

        return $this;
    }

    /**
     * Remove stock with stock adjustment history tracking
     * Now checks reserved stock before allowing removal
     */
    public function removeStock($quantity, $warehouseId = null, $storeId = null, $reference = null, $notes = null, $batchNumber = null, $assetInstanceId = null)
    {
        // Check available stock (excluding reserved)
        $availableForRemoval = $this->available_stock - ($this->reserved_stock ?? 0);
        if ($availableForRemoval < $quantity) {
            throw new \Exception("Insufficient available stock. Available: {$availableForRemoval}, Requested: {$quantity} (Reserved: {$this->reserved_stock})");
        }

        $previousStock = $this->current_stock;
        $newStock = $previousStock - $quantity;

        DB::transaction(function() use ($quantity, $warehouseId, $storeId, $reference, $notes, $batchNumber, $assetInstanceId, $previousStock, $newStock) {
            $this->update([
                'current_stock' => $newStock,
                'available_stock' => $this->available_stock - $quantity,
                // reserved_stock remains unchanged - will be released separately
            ]);

            $this->createStockMovement('OUT', $quantity, $previousStock, $newStock, $warehouseId, $storeId, $reference, $notes);
            $this->updateLocationUtilization();

            if ($this->track_batch && $batchNumber) {
                $this->deductFromBatch($batchNumber, $quantity);
            }

            if ($assetInstanceId && $this->item_type === self::TYPE_ASSET) {
                $this->removeAssetInstance($assetInstanceId);
            }

            // Track stock adjustment history
            $this->recordStockAdjustment($quantity, 'OUT', $previousStock, $newStock, $notes);
        });

        InventoryLogger::log([
            'module' => 'STOCK',
            'action' => 'STOCK_OUT',
            'record_id' => $this->id,
            'new_data' => [
                'item_name' => $this->item_name,
                'item_code' => $this->item_code,
                'quantity' => $quantity,
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'reserved_stock' => $this->reserved_stock ?? 0,
                'warehouse_id' => $warehouseId,
                'store_id' => $storeId,
                'batch_number' => $batchNumber,
                'asset_instance_id' => $assetInstanceId,
                'notes' => $notes
            ],
            'remarks' => "Stock OUT: {$quantity} units removed from {$this->item_name}"
        ]);

        return $this;
    }

    /**
     * Record stock adjustment for history tracking
     */
    protected function recordStockAdjustment($quantity, $type, $previousStock, $newStock, $notes = null)
    {
        return $this;
    }

    /**
     * Create stock movement
     */
    private function createStockMovement($type, $quantity, $previousStock, $newStock, $warehouseId, $storeId, $reference, $notes)
    {
        InventoryStockMovement::create([
            'institute_id' => $this->institute_id,
            'branch_id' => $this->branch_id ?? null,
            'item_id' => $this->id,
            'warehouse_id' => $warehouseId ?? $this->warehouse_id,
            'store_id' => $storeId ?? $this->store_id,
            'movement_type' => $type,
            'quantity' => $quantity,
            'previous_stock' => $previousStock,
            'new_stock' => $newStock,
            'unit_cost' => $this->buying_price,
            'total_cost' => $this->buying_price * $quantity,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference ? $reference->id : null,
            'notes' => $notes,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Handle batch creation
     */
    private function handleBatchCreation($batchData)
    {
        foreach ($batchData as $batch) {
            $this->batches()->create([
                'institute_id' => $this->institute_id,
                'branch_id' => $this->branch_id ?? null,
                'batch_number' => $batch['batch_number'] ?? 'BATCH-' . time(),
                'quantity' => $batch['quantity'] ?? 0,
                'remaining_quantity' => $batch['quantity'] ?? 0,
                'manufacturing_date' => $batch['manufacturing_date'] ?? null,
                'expiry_date' => $batch['expiry_date'] ?? null,
                'purchase_price' => $batch['purchase_price'] ?? $this->buying_price,
                'created_by' => auth()->id(),
            ]);
        }
    }

    /**
     * Handle asset creation
     */
    private function handleAssetCreation($serialNumbers, $warehouseId, $storeId)
    {
        foreach ($serialNumbers as $serial) {
            $this->assetInstances()->create([
                'institute_id' => $this->institute_id,
                'branch_id' => $this->branch_id ?? null,
                'asset_code' => $serial['asset_code'] ?? $this->generateAssetCode(),
                'serial_number' => $serial['serial_number'] ?? null,
                'status' => 'ACTIVE',
                'purchase_date' => $serial['purchase_date'] ?? now(),
                'warranty_expiry' => $serial['warranty_expiry'] ?? null,
                'warehouse_id' => $warehouseId,
                'store_id' => $storeId,
                'buying_price' => $serial['buying_price'] ?? $this->buying_price,
                'notes' => $serial['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);
        }
    }

    /**
     * Deduct from batch
     */
    private function deductFromBatch($batchNumber, $quantity)
    {
        $batch = $this->batches()->where('batch_number', $batchNumber)->first();
        if ($batch) {
            $batch->deductStock($quantity);
        }
    }

    /**
     * Remove asset instance
     */
    private function removeAssetInstance($assetInstanceId)
    {
        $asset = $this->assetInstances()->where('id', $assetInstanceId)->first();
        if ($asset) {
            $asset->update(['status' => 'REMOVED']);
        }
    }

    /**
     * Get custom fields
     */
    public function getCustomFields()
    {
        return $this->custom_fields ?? [];
    }

    /**
     * Get custom field by label
     */
    public function getCustomField($label)
    {
        $fields = $this->getCustomFields();
        foreach ($fields as $field) {
            if ($field['label'] === $label) {
                return $field['value'] ?? null;
            }
        }
        return null;
    }

    /**
     * Set custom fields
     */
    public function setCustomFields(array $fields)
    {
        $this->custom_fields = $fields;
        $this->save();
        return $this;
    }

    /**
     * Add custom field
     */
    public function addCustomField($label, $value)
    {
        $fields = $this->getCustomFields();
        foreach ($fields as &$field) {
            if ($field['label'] === $label) {
                $field['value'] = $value;
                $this->custom_fields = $fields;
                $this->save();
                return $this;
            }
        }
        $fields[] = ['label' => $label, 'value' => $value];
        $this->custom_fields = $fields;
        $this->save();
        return $this;
    }

    /**
     * Remove custom field
     */
    public function removeCustomField($label)
    {
        $fields = $this->getCustomFields();
        $fields = array_filter($fields, function($field) use ($label) {
            return $field['label'] !== $label;
        });
        $this->custom_fields = array_values($fields);
        $this->save();
        return $this;
    }

    /**
     * Check if item can be deleted
     */
    public function canBeDeleted()
    {
        if ($this->hasPendingReservations()) {
            return false;
        }
        
        if ($this->stockMovements()->exists()) {
            return false;
        }
        
        if ($this->receiptsIn()->exists() || $this->receiptsOut()->exists()) {
            return false;
        }
        
        if ($this->warehouseTransfers()->exists()) {
            return false;
        }
        
        if ($this->batches()->exists()) {
            return false;
        }
        
        if ($this->assetInstances()->exists()) {
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
        
        if ($this->hasPendingReservations()) {
            $blockers[] = [
                'type' => 'reserved_stock',
                'count' => $this->reserved_stock ?? 0,
                'message' => "This item has {$this->reserved_stock} unit(s) reserved"
            ];
        }
        
        if ($this->stockMovements()->exists()) {
            $blockers[] = [
                'type' => 'stock_movements',
                'count' => $this->stockMovements()->count(),
                'message' => "This item has {$this->stockMovements()->count()} stock movement(s)"
            ];
        }
        
        if ($this->receiptsIn()->exists()) {
            $blockers[] = [
                'type' => 'receipts_in',
                'count' => $this->receiptsIn()->count(),
                'message' => "This item has {$this->receiptsIn()->count()} in receipt(s)"
            ];
        }
        
        if ($this->receiptsOut()->exists()) {
            $blockers[] = [
                'type' => 'receipts_out',
                'count' => $this->receiptsOut()->count(),
                'message' => "This item has {$this->receiptsOut()->count()} out receipt(s)"
            ];
        }
        
        if ($this->warehouseTransfers()->exists()) {
            $blockers[] = [
                'type' => 'warehouse_transfers',
                'count' => $this->warehouseTransfers()->count(),
                'message' => "This item has {$this->warehouseTransfers()->count()} warehouse transfer(s)"
            ];
        }
        
        if ($this->batches()->exists()) {
            $blockers[] = [
                'type' => 'batches',
                'count' => $this->batches()->count(),
                'message' => "This item has {$this->batches()->count()} batch(es)"
            ];
        }
        
        if ($this->assetInstances()->exists()) {
            $blockers[] = [
                'type' => 'asset_instances',
                'count' => $this->assetInstances()->count(),
                'message' => "This item has {$this->assetInstances()->count()} asset instance(s)"
            ];
        }
        
        return $blockers;
    }

    // ============================================================
    // ==================== BOOT METHOD ====================
    // ============================================================

    protected static function boot()
    {
        parent::boot();

        static::created(function ($item) {
            // Update location utilization
            $item->updateLocationUtilization();

            // Log creation
            InventoryLogger::log([
                'module' => 'ITEM',
                'action' => 'CREATED',
                'record_id' => $item->id,
                'new_data' => $item->toArray(),
                'remarks' => 'Item created: ' . $item->item_name
            ]);
        });

        static::updated(function ($item) {
            // Check if location changed
            if ($item->wasChanged('warehouse_id') || $item->wasChanged('store_id')) {
                $item->updateLocationUtilizationOnMove(
                    $item->getOriginal('warehouse_id'),
                    $item->getOriginal('store_id')
                );
            }

            // If stock changed
            if ($item->wasChanged('current_stock') || $item->wasChanged('available_stock') || $item->wasChanged('reserved_stock')) {
                $item->updateLocationUtilization();
            }

            // Log update
            InventoryLogger::log([
                'module' => 'ITEM',
                'action' => 'UPDATED',
                'record_id' => $item->id,
                'old_data' => $item->getOriginal(),
                'new_data' => $item->getChanges(),
                'remarks' => 'Item updated: ' . $item->item_name
            ]);
        });

        static::deleted(function ($item) {
            // Log deletion
            InventoryLogger::log([
                'module' => 'ITEM',
                'action' => 'DELETED',
                'record_id' => $item->id,
                'old_data' => $item->toArray(),
                'remarks' => 'Item deleted: ' . $item->item_name
            ]);

            // Update location utilization
            $item->updateLocationUtilization();
        });
    }
}