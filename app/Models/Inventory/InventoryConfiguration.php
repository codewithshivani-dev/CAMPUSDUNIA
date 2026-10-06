<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Inventory\InventoryItem;

class InventoryConfiguration extends Model
{
    use SoftDeletes;

    protected $table = 'inventory_configurations';

    protected $guarded = [];

    protected $casts = [
        'multi_warehouse' => 'boolean',
        'barcode_enabled' => 'boolean',
        'qr_enabled' => 'boolean',
        'batch_tracking' => 'boolean',
        'serial_tracking' => 'boolean',
        'is_configured' => 'boolean'
    ];

    // ==================== RELATIONSHIPS ====================

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function inventoryCategories()
    {
        return $this->hasMany(InventoryCategory::class, 'configuration_id');
    }

    // ==================== CHECK METHODS ====================

    public function hasInventoryCategories()
    {
        return $this->inventoryCategories()->exists();
    }

    public function inventoryCategoriesCount()
    {
        return $this->inventoryCategories()->count();
    }

    public function hasItems()
    {
        return InventoryItem::whereHas('category', function($query) {
            $query->where('configuration_id', $this->id);
        })->exists();
    }

    public function itemsCount()
    {
        return InventoryItem::whereHas('category', function($query) {
            $query->where('configuration_id', $this->id);
        })->count();
    }

    // ==================== STATUS METHODS ====================

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isInactive()
    {
        return $this->status === 'inactive';
    }

    public function getStatusBadgeAttribute()
    {
        return $this->isActive() ? 'bg-success' : 'bg-danger';
    }

    public function getStatusTextAttribute()
    {
        return $this->isActive() ? 'Active' : 'Inactive';
    }

    // ==================== DELETION METHODS ====================

    public function canBeDeleted()
    {
        if ($this->inventoryCategories()->exists()) {
            return false;
        }

        if ($this->hasItems()) {
            return false;
        }

        return true;
    }

    public function getDeletionBlockers()
    {
        $blockers = [];

        if ($this->inventoryCategories()->exists()) {
            $count = $this->inventoryCategories()->count();
            $blockers[] = [
                'type' => 'categories',
                'count' => $count,
                'message' => "This configuration is being used by {$count} category(ies)."
            ];
        }

        $itemsCount = $this->itemsCount();
        if ($itemsCount > 0) {
            $blockers[] = [
                'type' => 'items',
                'count' => $itemsCount,
                'message' => "This configuration is being used by {$itemsCount} item(s)."
            ];
        }

        return $blockers;
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeByInstitute($query, $instituteId)
    {
        return $query->where('institute_id', $instituteId);
    }

    public function scopeConfigured($query)
    {
        return $query->where('is_configured', 1);
    }

    // ==================== ADDED: Predefined check for consistency ====================

    /**
     * Configuration is always considered "predefined" in terms of system usage
     * This method exists for consistency with other modules
     */
    public function isPredefined()
    {
        // Configurations are system-defined, not user-defined like categories
        return true;
    }
}