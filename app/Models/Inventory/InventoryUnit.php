<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryUnit extends Model
{
    use SoftDeletes;

    protected $table = 'inventory_units';

    protected $fillable = [
        'institute_id',
        'category_id',
        'subcategory_id',  // Add this - nullable
        'unit_name',
        'unit_code',
        'description',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Relationship with Category
     * A unit belongs to a category
     */
    public function category()
    {
        return $this->belongsTo(
            InventoryCategory::class,
            'category_id'
        );
    }

    /**
     * Relationship with SubCategory (optional)
     * A unit belongs to a subcategory if applicable
     */
    public function subcategory()
    {
        return $this->belongsTo(
            InventorySubCategory::class,
            'subcategory_id'
        );
    }

    /**
     * Relationship with Items
     * A unit has many items
     */
    public function items()
    {
        return $this->hasMany(
            InventoryItem::class,
            'unit_id'
        );
    }

    /**
     * Relationship with Creator
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Relationship with Updater
     */
    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if unit has items
     */
    public function hasItems()
    {
        return $this->items()->exists();
    }

    /**
     * Get items count
     */
    public function itemsCount()
    {
        return $this->items()->count();
    }

    /**
     * Check if unit can be deleted
     */
    public function canBeDeleted()
    {
        // Check if there are any items
        if ($this->items()->exists()) {
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

        if ($this->items()->exists()) {
            $blockers[] = [
                'type' => 'items',
                'count' => $this->items()->count(),
                'message' => "This unit has {$this->items()->count()} item(s) associated with it."
            ];
        }

        return $blockers;
    }

    /**
     * Get full path of unit (Category > SubCategory > Unit)
     */
    public function getFullPathAttribute()
    {
        $path = $this->category->category_name;
        if ($this->subcategory) {
            $path .= ' > ' . $this->subcategory->subcategory_name;
        }
        $path .= ' > ' . $this->unit_name;
        return $path;
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    /**
     * Scope for active units
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope for units by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope for units by subcategory
     */
    public function scopeBySubcategory($query, $subcategoryId)
    {
        return $query->where('subcategory_id', $subcategoryId);
    }
}