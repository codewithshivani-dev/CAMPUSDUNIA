<?php
// app/Models/Inventory/InventorySubCategory.php (Updated)

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventorySubCategory extends Model
{
    use SoftDeletes;

    protected $table = 'inventory_sub_categories';

    protected $fillable = [
        'institute_id',
        'category_id',
        'subcategory_name',
        'subcategory_code',
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
    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'category_id');
    }

    public function items()
    {
        return $this->hasMany(InventoryItem::class, 'subcategory_id');
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

    // ==================== TAX HELPERS ====================
    public function getGSTRate()
    {
        return $this->category?->gst_rate ?? null;
    }

    public function isGSTApplicable()
    {
        return $this->category?->is_gst_applicable ?? true;
    }

    public function getHSNCode()
    {
        return $this->category?->getHSNCodeDisplay() ?? null;
    }

    public function calculateTax($amount)
    {
        return $this->category?->calculateTax($amount) ?? [
            'base_amount' => $amount,
            'total_tax' => 0,
            'total_with_tax' => $amount,
            'tax_breakdown' => []
        ];
    }

    // ==================== HELPER METHODS ====================
    public function hasItems()
    {
        return $this->items()->exists();
    }

    public function itemsCount()
    {
        return $this->items()->count();
    }

    public function canBeDeleted()
    {
        return !$this->items()->exists();
    }

    public function getDeletionBlockers()
    {
        $blockers = [];

        if ($this->items()->exists()) {
            $blockers[] = [
                'type' => 'items',
                'count' => $this->items()->count(),
                'message' => "This subcategory has {$this->items()->count()} item(s) associated with it."
            ];
        }

        return $blockers;
    }

    public function getStatusTextAttribute()
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    public function getStatusBadgeAttribute()
    {
        return $this->status ? 'bg-success' : 'bg-danger';
    }

    public function getDisplayNameAttribute()
    {
        return $this->subcategory_name . ' (' . $this->subcategory_code . ')';
    }

    // ==================== SCOPES ====================
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}