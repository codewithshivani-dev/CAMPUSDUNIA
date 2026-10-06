<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use App\Services\Inventory\InventoryLogger;

class InventoryAssetInstance extends Model
{

    protected $table = 'inventory_asset_instances';

    protected $guarded = [];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
        'assigned_date' => 'date',
        'return_date' => 'date',
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'buying_price' => 'decimal:2',
        'current_value' => 'decimal:2',
        'depreciation_rate' => 'decimal:2',
        'status' => 'string',
        'is_available' => 'boolean',
        'sold_at' => 'datetime',
        'transferred_at' => 'datetime',
    ];

    // ==================== ASSET STATUS CONSTANTS ====================
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_UNDER_REPAIR = 'UNDER_REPAIR';
    const STATUS_DAMAGED = 'DAMAGED';
    const STATUS_ASSIGNED = 'ASSIGNED';
    const STATUS_DISPOSED = 'DISPOSED';
    const STATUS_LOST = 'LOST';
    const STATUS_STOLEN = 'STOLEN';
    const STATUS_RETURNED = 'RETURNED';
    const STATUS_MAINTENANCE = 'MAINTENANCE';
    const STATUS_SOLD = 'SOLD';
    const STATUS_TRANSFERRED = 'TRANSFERRED';

    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_UNDER_REPAIR => 'Under Repair',
            self::STATUS_DAMAGED => 'Damaged',
            self::STATUS_ASSIGNED => 'Assigned',
            self::STATUS_DISPOSED => 'Disposed',
            self::STATUS_LOST => 'Lost',
            self::STATUS_STOLEN => 'Stolen',
            self::STATUS_RETURNED => 'Returned',
            self::STATUS_MAINTENANCE => 'Maintenance',
            self::STATUS_SOLD => 'Sold',
            self::STATUS_TRANSFERRED => 'Transferred',
        ];
    }

    public static function getStatusBadges()
    {
        return [
            self::STATUS_ACTIVE => 'bg-success',
            self::STATUS_UNDER_REPAIR => 'bg-warning text-dark',
            self::STATUS_DAMAGED => 'bg-danger',
            self::STATUS_ASSIGNED => 'bg-primary',
            self::STATUS_DISPOSED => 'bg-secondary',
            self::STATUS_LOST => 'bg-dark',
            self::STATUS_STOLEN => 'bg-dark',
            self::STATUS_RETURNED => 'bg-info',
            self::STATUS_MAINTENANCE => 'bg-warning text-dark',
            self::STATUS_SOLD => 'bg-danger text-white',
            self::STATUS_TRANSFERRED => 'bg-secondary text-white',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the parent inventory item (product/master item)
     */
    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    /**
     * Get the warehouse where this asset is stored
     */
    public function warehouse()
    {
        return $this->belongsTo(InventoryWarehouse::class, 'warehouse_id');
    }

    /**
     * Get the store where this asset is stored
     */
    public function store()
    {
        return $this->belongsTo(InventoryStore::class, 'store_id');
    }

    /**
     * Get the department where this asset is assigned
     */
    public function department()
    {
        return $this->belongsTo(InventoryDepartment::class, 'department_id');
    }

    /**
     * Get the user who is assigned this asset
     */
    public function assignedTo()
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to');
    }

    /**
     * Get the user who created this asset instance
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Get the user who last updated this asset instance
     */
    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    /**
     * Get the user who deleted this asset instance
     */
    public function deleter()
    {
        return $this->belongsTo(\App\Models\User::class, 'deleted_by');
    }

    /**
     * Get the receipt in that created this asset
     */
    public function receiptIn()
    {
        return $this->belongsTo(InventoryReceiptIn::class, 'receipt_in_id');
    }

    /**
     * Get the receipt out that removed this asset
     */
    public function receiptOut()
    {
        return $this->belongsTo(InventoryReceiptOut::class, 'receipt_out_id');
    }

    /**
     * Get the stock out that sold this asset
     */
    public function stockOut()
    {
        return $this->belongsTo(InventoryStockOut::class, 'stock_out_id');
    }

    /**
     * Get the stock movements for this asset
     */
    public function stockMovements()
    {
        return $this->hasMany(InventoryStockMovement::class, 'asset_instance_id');
    }

    /**
     * Get the maintenance history for this asset
     */
    public function maintenanceHistory()
    {
        return $this->hasMany(InventoryAssetMaintenance::class, 'asset_instance_id');
    }

    /**
     * Get the assignment history for this asset
     */
    public function assignmentHistory()
    {
        return $this->hasMany(InventoryAssetAssignment::class, 'asset_instance_id');
    }

    // ==================== STATUS MANAGEMENT ====================

    /**
     * Check if asset is available
     */
    public function isAvailable()
    {
        return $this->status === self::STATUS_ACTIVE && !$this->assigned_to;
    }

    /**
     * Check if asset is assigned
     */
    public function isAssigned()
    {
        return $this->status === self::STATUS_ASSIGNED && $this->assigned_to;
    }

    /**
     * Check if asset is damaged
     */
    public function isDamaged()
    {
        return $this->status === self::STATUS_DAMAGED;
    }

    /**
     * Check if asset is under repair
     */
    public function isUnderRepair()
    {
        return $this->status === self::STATUS_UNDER_REPAIR;
    }

    /**
     * Check if asset is disposed
     */
    public function isDisposed()
    {
        return $this->status === self::STATUS_DISPOSED;
    }

    /**
     * Check if asset is lost or stolen
     */
    public function isLostOrStolen()
    {
        return in_array($this->status, [self::STATUS_LOST, self::STATUS_STOLEN]);
    }

    /**
     * Check if asset is sold
     */
    public function isSold()
    {
        return $this->status === self::STATUS_SOLD;
    }

    /**
     * Check if asset is transferred
     */
    public function isTransferred()
    {
        return $this->status === self::STATUS_TRANSFERRED;
    }

    /**
     * Check if asset can be sold (active and not assigned)
     */
    public function canBeSold()
    {
        return $this->status === self::STATUS_ACTIVE && !$this->assigned_to;
    }

    /**
     * Check if asset can be transferred (active and not assigned)
     */
    public function canBeTransferred()
    {
        return $this->status === self::STATUS_ACTIVE && !$this->assigned_to;
    }

    /**
     * Update asset status with logging
     */
    public function updateStatus($newStatus, $reason = null, $notes = null)
    {
        $oldStatus = $this->status;

        if ($oldStatus === $newStatus) {
            return $this;
        }

        $updateData = [
            'status' => $newStatus,
            'status_reason' => $reason,
            'notes' => $notes ?? $this->notes,
        ];

        // Set additional timestamps based on status
        if ($newStatus === self::STATUS_SOLD) {
            $updateData['sold_at'] = now();
        } elseif ($newStatus === self::STATUS_TRANSFERRED) {
            $updateData['transferred_at'] = now();
        }

        $this->update($updateData);

        InventoryLogger::log([
            'module' => 'ASSET',
            'action' => 'STATUS_CHANGE',
            'record_id' => $this->id,
            'old_data' => ['status' => $oldStatus],
            'new_data' => ['status' => $newStatus, 'reason' => $reason],
            'remarks' => "Asset {$this->asset_code} status changed from {$oldStatus} to {$newStatus}. Reason: {$reason}"
        ]);

        return $this;
    }

    /**
     * Mark asset as sold
     */
    public function markAsSold($stockOutId = null, $notes = null)
    {
        $this->updateStatus(self::STATUS_SOLD, 'Sold', $notes);
        
        if ($stockOutId) {
            $this->update(['stock_out_id' => $stockOutId]);
        }

        InventoryLogger::log([
            'module' => 'ASSET',
            'action' => 'SOLD',
            'record_id' => $this->id,
            'new_data' => [
                'asset_code' => $this->asset_code,
                'serial_number' => $this->serial_number,
                'stock_out_id' => $stockOutId,
            ],
            'remarks' => "Asset {$this->asset_code} marked as sold"
        ]);

        return $this;
    }

    /**
     * Mark asset as transferred
     */
    public function markAsTransferred($transferId = null, $notes = null)
    {
        $this->updateStatus(self::STATUS_TRANSFERRED, 'Transferred', $notes);
        
        if ($transferId) {
            $this->update(['transfer_id' => $transferId]);
        }

        InventoryLogger::log([
            'module' => 'ASSET',
            'action' => 'TRANSFERRED',
            'record_id' => $this->id,
            'new_data' => [
                'asset_code' => $this->asset_code,
                'serial_number' => $this->serial_number,
                'transfer_id' => $transferId,
            ],
            'remarks' => "Asset {$this->asset_code} marked as transferred"
        ]);

        return $this;
    }

    // ==================== ASSIGNMENT METHODS ====================

    /**
     * Assign asset to a user
     */
    public function assignTo($userId, $notes = null)
    {
        if (!$this->isAvailable()) {
            throw new \Exception("Asset is not available for assignment. Current status: {$this->status}");
        }

        $this->update([
            'status' => self::STATUS_ASSIGNED,
            'assigned_to' => $userId,
            'assigned_date' => now(),
            'notes' => $notes ?? $this->notes,
        ]);

        // Create assignment history record
        InventoryAssetAssignment::create([
            'institute_id' => $this->institute_id,
            'asset_instance_id' => $this->id,
            'assigned_to' => $userId,
            'assigned_by' => auth()->id(),
            'assigned_date' => now(),
            'notes' => $notes,
            'created_by' => auth()->id(),
        ]);

        InventoryLogger::log([
            'module' => 'ASSET',
            'action' => 'ASSIGN',
            'record_id' => $this->id,
            'new_data' => [
                'asset_code' => $this->asset_code,
                'assigned_to' => $userId,
                'assigned_by' => auth()->id(),
            ],
            'remarks' => "Asset {$this->asset_code} assigned to user ID: {$userId}"
        ]);

        return $this;
    }

    /**
     * Return asset from assignment
     */
    public function returnAsset($notes = null)
    {
        if (!$this->isAssigned()) {
            throw new \Exception("Asset is not currently assigned. Current status: {$this->status}");
        }

        $this->update([
            'status' => self::STATUS_RETURNED,
            'assigned_to' => null,
            'return_date' => now(),
            'notes' => $notes ?? $this->notes,
        ]);

        // Update assignment history
        $assignment = $this->assignmentHistory()->whereNull('return_date')->latest()->first();
        if ($assignment) {
            $assignment->update([
                'return_date' => now(),
                'returned_by' => auth()->id(),
                'return_notes' => $notes,
            ]);
        }

        InventoryLogger::log([
            'module' => 'ASSET',
            'action' => 'RETURN',
            'record_id' => $this->id,
            'new_data' => [
                'asset_code' => $this->asset_code,
                'returned_by' => auth()->id(),
            ],
            'remarks' => "Asset {$this->asset_code} returned"
        ]);

        // Check if asset should be active or maintenance
        $this->updateStatus(self::STATUS_ACTIVE, 'Returned from assignment', $notes);

        return $this;
    }

    // ==================== MAINTENANCE METHODS ====================

    /**
     * Mark asset for maintenance
     */
    public function markForMaintenance($notes = null)
    {
        $this->updateStatus(self::STATUS_MAINTENANCE, 'Scheduled maintenance', $notes);

        InventoryLogger::log([
            'module' => 'ASSET',
            'action' => 'MAINTENANCE',
            'record_id' => $this->id,
            'new_data' => [
                'asset_code' => $this->asset_code,
                'notes' => $notes,
            ],
            'remarks' => "Asset {$this->asset_code} marked for maintenance"
        ]);

        return $this;
    }

    /**
     * Record maintenance completion
     */
    public function completeMaintenance($notes = null)
    {
        $this->update([
            'last_maintenance_date' => now(),
            'notes' => $notes ?? $this->notes,
        ]);

        $this->updateStatus(self::STATUS_ACTIVE, 'Maintenance completed', $notes);

        // Create maintenance record
        InventoryAssetMaintenance::create([
            'institute_id' => $this->institute_id,
            'asset_instance_id' => $this->id,
            'maintenance_date' => now(),
            'maintenance_type' => 'SCHEDULED',
            'description' => $notes,
            'cost' => 0,
            'performed_by' => auth()->id(),
            'created_by' => auth()->id(),
        ]);

        InventoryLogger::log([
            'module' => 'ASSET',
            'action' => 'MAINTENANCE_COMPLETE',
            'record_id' => $this->id,
            'new_data' => [
                'asset_code' => $this->asset_code,
                'maintenance_date' => now(),
            ],
            'remarks' => "Maintenance completed for asset {$this->asset_code}"
        ]);

        return $this;
    }

    /**
     * Record damage
     */
    public function markDamaged($reason = null, $notes = null)
    {
        $this->updateStatus(self::STATUS_DAMAGED, $reason, $notes);

        InventoryLogger::log([
            'module' => 'ASSET',
            'action' => 'DAMAGE',
            'record_id' => $this->id,
            'new_data' => [
                'asset_code' => $this->asset_code,
                'reason' => $reason,
                'notes' => $notes,
            ],
            'remarks' => "Asset {$this->asset_code} marked as damaged. Reason: {$reason}"
        ]);

        return $this;
    }

    /**
     * Mark for repair
     */
    public function markForRepair($reason = null, $notes = null)
    {
        $this->updateStatus(self::STATUS_UNDER_REPAIR, $reason, $notes);

        InventoryLogger::log([
            'module' => 'ASSET',
            'action' => 'REPAIR',
            'record_id' => $this->id,
            'new_data' => [
                'asset_code' => $this->asset_code,
                'reason' => $reason,
                'notes' => $notes,
            ],
            'remarks' => "Asset {$this->asset_code} sent for repair. Reason: {$reason}"
        ]);

        return $this;
    }

    /**
     * Mark as repaired
     */
    public function markAsRepaired($notes = null)
    {
        $this->updateStatus(self::STATUS_ACTIVE, 'Repaired successfully', $notes);

        // Create maintenance record
        InventoryAssetMaintenance::create([
            'institute_id' => $this->institute_id,
            'asset_instance_id' => $this->id,
            'maintenance_date' => now(),
            'maintenance_type' => 'REPAIR',
            'description' => $notes,
            'cost' => 0,
            'performed_by' => auth()->id(),
            'created_by' => auth()->id(),
        ]);

        InventoryLogger::log([
            'module' => 'ASSET',
            'action' => 'REPAIRED',
            'record_id' => $this->id,
            'new_data' => [
                'asset_code' => $this->asset_code,
                'notes' => $notes,
            ],
            'remarks' => "Asset {$this->asset_code} repaired successfully"
        ]);

        return $this;
    }

    /**
     * Dispose asset
     */
    public function dispose($reason = null, $notes = null)
    {
        $this->updateStatus(self::STATUS_DISPOSED, $reason, $notes);

        // Update parent item stock
        if ($this->item) {
            $this->item->decrement('current_stock');
            $this->item->decrement('available_stock');
        }

        InventoryLogger::log([
            'module' => 'ASSET',
            'action' => 'DISPOSE',
            'record_id' => $this->id,
            'new_data' => [
                'asset_code' => $this->asset_code,
                'reason' => $reason,
                'notes' => $notes,
            ],
            'remarks' => "Asset {$this->asset_code} disposed. Reason: {$reason}"
        ]);

        return $this;
    }

    // ==================== DEPRECIATION METHODS ====================

    /**
     * Calculate depreciation for this asset
     */
    public function calculateDepreciation($months = null)
    {
        if (!$this->buying_price || $this->buying_price <= 0) {
            return 0;
        }

        $lifeMonths = $months ?? $this->item->asset_life_months ?? 36;
        if ($lifeMonths <= 0) {
            return 0;
        }

        $monthlyDepreciation = $this->buying_price / $lifeMonths;
        $ageInMonths = $this->purchase_date ? $this->purchase_date->diffInMonths(now()) : 0;
        $accumulatedDepreciation = $monthlyDepreciation * min($ageInMonths, $lifeMonths);

        return round(max($accumulatedDepreciation, 0), 2);
    }

    /**
     * Get current value of asset
     */
    public function getCurrentValueAttribute()
    {
        if ($this->status === self::STATUS_DISPOSED || $this->status === self::STATUS_SOLD) {
            return 0;
        }
        $depreciation = $this->calculateDepreciation();
        return round(max($this->buying_price - $depreciation, 0), 2);
    }

    /**
     * Get formatted current value
     */
    public function getFormattedCurrentValueAttribute()
    {
        return '₹' . number_format($this->current_value, 2);
    }

    /**
     * Get depreciation percentage
     */
    public function getDepreciationPercentageAttribute()
    {
        if ($this->buying_price <= 0) {
            return 0;
        }
        $depreciation = $this->calculateDepreciation();
        return round(($depreciation / $this->buying_price) * 100, 2);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get the full display name with code and serial
     */
    public function getDisplayNameAttribute()
    {
        $name = $this->asset_code;
        if ($this->serial_number) {
            $name .= ' (SN: ' . $this->serial_number . ')';
        }
        if ($this->status === self::STATUS_SOLD) {
            $name .= ' [SOLD]';
        } elseif ($this->status === self::STATUS_TRANSFERRED) {
            $name .= ' [TRANSFERRED]';
        }
        return $name;
    }

    /**
     * Get the status badge class
     */
    public function getStatusBadgeAttribute()
    {
        $badges = self::getStatusBadges();
        return $badges[$this->status] ?? 'bg-secondary';
    }

    /**
     * Get the status text
     */
    public function getStatusTextAttribute()
    {
        $statuses = self::getStatuses();
        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Get warranty status
     */
    public function getWarrantyStatusAttribute()
    {
        if (!$this->warranty_expiry) {
            return 'Not Set';
        }
        if ($this->warranty_expiry < now()) {
            return 'Expired';
        }
        $daysLeft = $this->warranty_expiry->diffInDays(now());
        if ($daysLeft <= 30) {
            return 'Expiring Soon (' . $daysLeft . ' days)';
        }
        return 'Active (' . $daysLeft . ' days left)';
    }

    /**
     * Get warranty badge class
     */
    public function getWarrantyBadgeAttribute()
    {
        if (!$this->warranty_expiry) {
            return 'bg-secondary';
        }
        if ($this->warranty_expiry < now()) {
            return 'bg-danger';
        }
        if ($this->warranty_expiry <= now()->addDays(30)) {
            return 'bg-warning text-dark';
        }
        return 'bg-success';
    }

    /**
     * Check if asset is in warranty
     */
    public function isInWarranty()
    {
        return $this->warranty_expiry && $this->warranty_expiry > now();
    }

    /**
     * Get days left in warranty
     */
    public function getWarrantyDaysLeftAttribute()
    {
        if (!$this->warranty_expiry) {
            return null;
        }
        if ($this->warranty_expiry < now()) {
            return 0;
        }
        return $this->warranty_expiry->diffInDays(now());
    }

    /**
     * Check if asset needs maintenance
     */
    public function needsMaintenance()
    {
        if (!$this->last_maintenance_date) {
            return true;
        }
        if (!$this->next_maintenance_date) {
            return false;
        }
        return $this->next_maintenance_date <= now();
    }

    /**
     * Get the location string for this asset
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

    // ==================== SCOPES ====================

    /**
     * Scope for available assets
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                     ->whereNull('assigned_to');
    }

    /**
     * Scope for assigned assets
     */
    public function scopeAssigned($query)
    {
        return $query->where('status', self::STATUS_ASSIGNED)
                     ->whereNotNull('assigned_to');
    }

    /**
     * Scope for damaged assets
     */
    public function scopeDamaged($query)
    {
        return $query->where('status', self::STATUS_DAMAGED);
    }

    /**
     * Scope for under repair assets
     */
    public function scopeUnderRepair($query)
    {
        return $query->where('status', self::STATUS_UNDER_REPAIR);
    }

    /**
     * Scope for disposed assets
     */
    public function scopeDisposed($query)
    {
        return $query->where('status', self::STATUS_DISPOSED);
    }

    /**
     * Scope for sold assets
     */
    public function scopeSold($query)
    {
        return $query->where('status', self::STATUS_SOLD);
    }

    /**
     * Scope for transferred assets
     */
    public function scopeTransferred($query)
    {
        return $query->where('status', self::STATUS_TRANSFERRED);
    }

    /**
     * Scope for active assets (not disposed, lost, stolen, sold, or transferred)
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [
            self::STATUS_DISPOSED,
            self::STATUS_LOST,
            self::STATUS_STOLEN,
            self::STATUS_SOLD,
            self::STATUS_TRANSFERRED,
        ]);
    }

    /**
     * Scope for usable assets (active and not assigned)
     */
    public function scopeUsable($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                     ->whereNull('assigned_to');
    }

    /**
     * Scope by item
     */
    public function scopeByItem($query, $itemId)
    {
        return $query->where('item_id', $itemId);
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
     * Scope by department
     */
    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    /**
     * Scope by assigned user
     */
    public function scopeByAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Scope for assets in warranty
     */
    public function scopeInWarranty($query)
    {
        return $query->where('warranty_expiry', '>', now())
                     ->whereNotNull('warranty_expiry');
    }

    /**
     * Scope for warranty expiring soon
     */
    public function scopeWarrantyExpiringSoon($query, $days = 30)
    {
        return $query->where('warranty_expiry', '<=', now()->addDays($days))
                     ->where('warranty_expiry', '>', now());
    }

    /**
     * Scope for assets needing maintenance
     */
    public function scopeNeedsMaintenance($query)
    {
        return $query->where(function($q) {
            $q->whereNull('last_maintenance_date')
              ->orWhere('next_maintenance_date', '<=', now());
        })->whereNotIn('status', [
            self::STATUS_DISPOSED, 
            self::STATUS_LOST, 
            self::STATUS_STOLEN,
            self::STATUS_SOLD,
            self::STATUS_TRANSFERRED,
        ]);
    }

    /**
     * Scope by asset code (search)
     */
    public function scopeByAssetCode($query, $code)
    {
        return $query->where('asset_code', 'LIKE', "%{$code}%");
    }

    /**
     * Scope by serial number (search)
     */
    public function scopeBySerialNumber($query, $serial)
    {
        return $query->where('serial_number', 'LIKE', "%{$serial}%");
    }

    /**
     * Scope FIFO for serial numbers (oldest first)
     */
    public function scopeFIFO($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    /**
     * Scope LIFO for serial numbers (newest first)
     */
    public function scopeLIFO($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // ==================== DELETION METHODS ====================

    /**
     * Check if asset can be deleted
     */
    public function canBeDeleted()
    {
        if ($this->stockMovements()->exists()) {
            return false;
        }
        if ($this->maintenanceHistory()->exists()) {
            return false;
        }
        if ($this->assignmentHistory()->exists()) {
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
                'message' => "This asset has {$this->stockMovements()->count()} stock movement(s)"
            ];
        }

        if ($this->maintenanceHistory()->exists()) {
            $blockers[] = [
                'type' => 'maintenance_history',
                'count' => $this->maintenanceHistory()->count(),
                'message' => "This asset has {$this->maintenanceHistory()->count()} maintenance record(s)"
            ];
        }

        if ($this->assignmentHistory()->exists()) {
            $blockers[] = [
                'type' => 'assignment_history',
                'count' => $this->assignmentHistory()->count(),
                'message' => "This asset has {$this->assignmentHistory()->count()} assignment record(s)"
            ];
        }

        return $blockers;
    }

    // ==================== BOOT METHOD ====================

    protected static function boot()
    {
        parent::boot();

        static::created(function ($asset) {
            InventoryLogger::log([
                'module' => 'ASSET',
                'action' => 'CREATED',
                'record_id' => $asset->id,
                'new_data' => $asset->toArray(),
                'remarks' => "Asset created: {$asset->asset_code}"
            ]);
        });

        static::updated(function ($asset) {
            if ($asset->wasChanged('status')) {
                InventoryLogger::log([
                    'module' => 'ASSET',
                    'action' => 'STATUS_CHANGED',
                    'record_id' => $asset->id,
                    'old_data' => ['status' => $asset->getOriginal('status')],
                    'new_data' => ['status' => $asset->status],
                    'remarks' => "Asset {$asset->asset_code} status updated"
                ]);
            }
        });

        static::deleted(function ($asset) {
            InventoryLogger::log([
                'module' => 'ASSET',
                'action' => 'DELETED',
                'record_id' => $asset->id,
                'old_data' => $asset->toArray(),
                'remarks' => "Asset deleted: {$asset->asset_code}"
            ]);
        });
    }
}