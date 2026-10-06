<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryVendor extends Model
{
    use SoftDeletes;

    protected $table = 'inventory_vendors';

    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
        'is_preferred' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    /**
     * Items from this vendor
     */
    public function items()
    {
        return $this->hasMany(InventoryItem::class, 'vendor_id');
    }

    /**
     * Receipts from this vendor
     */
    public function receiptsIn()
    {
        return $this->hasMany(InventoryReceiptIn::class, 'vendor_id');
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopePreferred($query)
    {
        return $query->where('is_preferred', 1);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('vendor_name', 'LIKE', "%{$search}%")
              ->orWhere('vendor_code', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('phone', 'LIKE', "%{$search}%")
              ->orWhere('contact_person', 'LIKE', "%{$search}%")
              ->orWhere('gst_number', 'LIKE', "%{$search}%");
        });
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    public function getDisplayNameAttribute()
    {
        return $this->vendor_name . ($this->vendor_code ? ' (' . $this->vendor_code . ')' : '');
    }

    public function getStatusBadgeAttribute()
    {
        return $this->status ? 'bg-success' : 'bg-danger';
    }

    public function getStatusTextAttribute()
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    /**
     * Check if vendor can be deleted
     */
    public function canBeDeleted()
    {
        // Check if vendor has any items
        if ($this->items()->count() > 0) {
            return false;
        }

        // Check if vendor has any receipts
        if ($this->receiptsIn()->count() > 0) {
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

        if ($this->items()->count() > 0) {
            $blockers[] = [
                'type' => 'items',
                'count' => $this->items()->count(),
                'message' => "This vendor has {$this->items()->count()} item(s) associated"
            ];
        }

        if ($this->receiptsIn()->count() > 0) {
            $blockers[] = [
                'type' => 'receipts',
                'count' => $this->receiptsIn()->count(),
                'message' => "This vendor has {$this->receiptsIn()->count()} receipt(s) associated"
            ];
        }

        return $blockers;
    }
}