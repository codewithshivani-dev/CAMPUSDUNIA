<?php
// app/Models/Inventory/InventoryCategory.php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryCategory extends Model
{
    use SoftDeletes;

    protected $table = 'inventory_categories';

    protected $fillable = [
        'institute_id',
        'configuration_id',
        'category_name',
        'category_code',
        'icon',
        'icon_color',
        'icon_image',
        'description',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'tax_slab_id',
        'hsn_code_id',
        'gst_rate',
        'is_gst_applicable',
        'is_hsn_mandatory',
        'tax_composition'
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_gst_applicable' => 'boolean',
        'is_hsn_mandatory' => 'boolean',
        'tax_composition' => 'array'
    ];

    // ==================== RELATIONSHIPS ====================

    public function configuration()
    {
        return $this->belongsTo(InventoryConfiguration::class, 'configuration_id');
    }

    public function subCategories()
    {
        return $this->hasMany(InventorySubCategory::class, 'category_id');
    }

    public function items()
    {
        return $this->hasMany(InventoryItem::class, 'category_id');
    }

    public function subCategoryItems()
    {
        return $this->hasManyThrough(
            InventoryItem::class,
            InventorySubCategory::class,
            'category_id',
            'subcategory_id',
            'id',
            'id'
        );
    }

    public function taxSlab()
    {
        return $this->belongsTo(TaxSlab::class, 'tax_slab_id');
    }

    public function hsnCode()
    {
        return $this->belongsTo(HSNCode::class, 'hsn_code_id');
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

    // ==================== PREDEFINED CATEGORIES WITH FULL TAX DATA ====================
    
    public static function getPredefinedCategories()
    {
        return [
            // ===== STATIONERY & OFFICE =====
            'stationery' => [
                'name' => 'Stationery',
                'prefix' => 'STA',
                'icon' => 'fa-pencil',
                'color' => '#0d6efd',
                'gst_rate' => '5',
                'hsn_code' => '4820',
                'hsn_description' => 'Paper stationery and office supplies',
                'cgst' => 2.5,
                'sgst' => 2.5,
                'description' => 'Office stationery, writing materials, paper products',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 2.5], ['type' => 'sgst', 'rate' => 2.5]]
            ],

            // ===== FURNITURE =====
            'furniture' => [
                'name' => 'Furniture',
                'prefix' => 'FUR',
                'icon' => 'fa-couch',
                'color' => '#8b5cf6',
                'gst_rate' => '18',
                'hsn_code' => '9401',
                'hsn_description' => 'Seats and furniture',
                'cgst' => 9,
                'sgst' => 9,
                'description' => 'Office and household furniture, seating, tables',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 9], ['type' => 'sgst', 'rate' => 9]]
            ],

            // ===== ELECTRONICS =====
            'electronics' => [
                'name' => 'Electronics',
                'prefix' => 'ELE',
                'icon' => 'fa-laptop',
                'color' => '#0ea5e9',
                'gst_rate' => '18',
                'hsn_code' => '8471',
                'hsn_description' => 'Automatic data processing machines',
                'cgst' => 9,
                'sgst' => 9,
                'description' => 'Electronic devices, computers, peripherals',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 9], ['type' => 'sgst', 'rate' => 9]]
            ],

            // ===== IT ASSETS =====
            'it_assets' => [
                'name' => 'IT Assets',
                'prefix' => 'ITA',
                'icon' => 'fa-computer',
                'color' => '#6366f1',
                'gst_rate' => '18',
                'hsn_code' => '8471',
                'hsn_description' => 'Automatic data processing machines',
                'cgst' => 9,
                'sgst' => 9,
                'description' => 'IT hardware, servers, networking equipment',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 9], ['type' => 'sgst', 'rate' => 9]]
            ],

            // ===== GROCERY =====
            'grocery' => [
                'name' => 'Grocery',
                'prefix' => 'GRO',
                'icon' => 'fa-shopping-cart',
                'color' => '#f59e0b',
                'gst_rate' => '5',
                'hsn_code' => '2106',
                'hsn_description' => 'Food preparations not elsewhere specified',
                'cgst' => 2.5,
                'sgst' => 2.5,
                'description' => 'Grocery items, packaged food, staples',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 2.5], ['type' => 'sgst', 'rate' => 2.5]]
            ],

            // ===== VEGETABLES (EXEMPT) =====
            'vegetables' => [
                'name' => 'Vegetables',
                'prefix' => 'VEG',
                'icon' => 'fa-carrot',
                'color' => '#22c55e',
                'gst_rate' => '0',
                'hsn_code' => '0709',
                'hsn_description' => 'Other vegetables, fresh or chilled',
                'cgst' => 0,
                'sgst' => 0,
                'description' => 'Fresh vegetables (GST exempt)',
                'tax_slabs' => []
            ],

            // ===== FRUITS (EXEMPT) =====
            'fruits' => [
                'name' => 'Fruits',
                'prefix' => 'FRU',
                'icon' => 'fa-apple-alt',
                'color' => '#ef4444',
                'gst_rate' => '0',
                'hsn_code' => '0810',
                'hsn_description' => 'Other fruit, fresh',
                'cgst' => 0,
                'sgst' => 0,
                'description' => 'Fresh fruits (GST exempt)',
                'tax_slabs' => []
            ],

            // ===== DAIRY =====
            'dairy' => [
                'name' => 'Dairy',
                'prefix' => 'DAI',
                'icon' => 'fa-cheese',
                'color' => '#fbbf24',
                'gst_rate' => '5',
                'hsn_code' => '0401',
                'hsn_description' => 'Milk and cream',
                'cgst' => 2.5,
                'sgst' => 2.5,
                'description' => 'Dairy products, milk, cheese, butter',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 2.5], ['type' => 'sgst', 'rate' => 2.5]]
            ],

            // ===== KITCHEN ITEMS =====
            'kitchen_items' => [
                'name' => 'Kitchen Items',
                'prefix' => 'KIT',
                'icon' => 'fa-utensils',
                'color' => '#f97316',
                'gst_rate' => '18',
                'hsn_code' => '8215',
                'hsn_description' => 'Spoons, forks, ladles, kitchen utensils',
                'cgst' => 9,
                'sgst' => 9,
                'description' => 'Kitchen utensils, cookware, cutlery',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 9], ['type' => 'sgst', 'rate' => 9]]
            ],

            // ===== CLEANING MATERIAL =====
            'cleaning_material' => [
                'name' => 'Cleaning Material',
                'prefix' => 'CLE',
                'icon' => 'fa-broom',
                'color' => '#06b6d4',
                'gst_rate' => '18',
                'hsn_code' => '3402',
                'hsn_description' => 'Organic surface-active agents',
                'cgst' => 9,
                'sgst' => 9,
                'description' => 'Cleaning supplies, detergents, disinfectants',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 9], ['type' => 'sgst', 'rate' => 9]]
            ],

            // ===== UNIFORM =====
            'uniform' => [
                'name' => 'Uniform',
                'prefix' => 'UNI',
                'icon' => 'fa-shirt',
                'color' => '#8b5cf6',
                'gst_rate' => '5',
                'hsn_code' => '6203',
                'hsn_description' => 'Men\'s suits, jackets, trousers',
                'cgst' => 2.5,
                'sgst' => 2.5,
                'description' => 'Uniforms, workwear, protective clothing',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 2.5], ['type' => 'sgst', 'rate' => 2.5]]
            ],

            // ===== BOOKS =====
            'books' => [
                'name' => 'Books',
                'prefix' => 'BOO',
                'icon' => 'fa-book',
                'color' => '#6366f1',
                'gst_rate' => '5',
                'hsn_code' => '4901',
                'hsn_description' => 'Printed books, brochures, leaflets',
                'cgst' => 2.5,
                'sgst' => 2.5,
                'description' => 'Books, publications, educational materials',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 2.5], ['type' => 'sgst', 'rate' => 2.5]]
            ],

            // ===== MEDICINES =====
            'medicines' => [
                'name' => 'Medicines',
                'prefix' => 'MED',
                'icon' => 'fa-pills',
                'color' => '#ef4444',
                'gst_rate' => '5',
                'hsn_code' => '3004',
                'hsn_description' => 'Medicaments for therapeutic use',
                'cgst' => 2.5,
                'sgst' => 2.5,
                'description' => 'Pharmaceuticals, medicines, healthcare products',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 2.5], ['type' => 'sgst', 'rate' => 2.5]]
            ],

            // ===== SPORTS ITEMS =====
            'sports_items' => [
                'name' => 'Sports Items',
                'prefix' => 'SPO',
                'icon' => 'fa-futbol',
                'color' => '#f59e0b',
                'gst_rate' => '18',
                'hsn_code' => '9506',
                'hsn_description' => 'Articles for sports, outdoor games',
                'cgst' => 9,
                'sgst' => 9,
                'description' => 'Sports equipment, gear, fitness items',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 9], ['type' => 'sgst', 'rate' => 9]]
            ],

            // ===== CONSUMABLES =====
            'consumables' => [
                'name' => 'Consumables',
                'prefix' => 'CON',
                'icon' => 'fa-box',
                'color' => '#14b8a6',
                'gst_rate' => '18',
                'hsn_code' => '3926',
                'hsn_description' => 'Other articles of plastics',
                'cgst' => 9,
                'sgst' => 9,
                'description' => 'General consumable supplies, disposable items',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 9], ['type' => 'sgst', 'rate' => 9]]
            ],

            // ===== NON-CONSUMABLES =====
            'non_consumables' => [
                'name' => 'Non Consumables',
                'prefix' => 'NON',
                'icon' => 'fa-tag',
                'color' => '#8b5cf6',
                'gst_rate' => '18',
                'hsn_code' => '3926',
                'hsn_description' => 'Other articles of plastics',
                'cgst' => 9,
                'sgst' => 9,
                'description' => 'Non-consumable durable items, equipment',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 9], ['type' => 'sgst', 'rate' => 9]]
            ],

            // ===== SPICES =====
            'spices' => [
                'name' => 'Spices',
                'prefix' => 'SPC',
                'icon' => 'fa-pepper-hot',
                'color' => '#dc2626',
                'gst_rate' => '5',
                'hsn_code' => '0910',
                'hsn_description' => 'Ginger, saffron, turmeric, thyme, bay leaves',
                'cgst' => 2.5,
                'sgst' => 2.5,
                'description' => 'Spices, herbs, condiments',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 2.5], ['type' => 'sgst', 'rate' => 2.5]]
            ],

            // ===== PULSES (EXEMPT) =====
            'pulses' => [
                'name' => 'Pulses',
                'prefix' => 'PUL',
                'icon' => 'fa-seedling',
                'color' => '#65a30d',
                'gst_rate' => '0',
                'hsn_code' => '0713',
                'hsn_description' => 'Dried leguminous vegetables',
                'cgst' => 0,
                'sgst' => 0,
                'description' => 'Pulses, lentils, legumes (GST exempt)',
                'tax_slabs' => []
            ],

            // ===== GRAINS (EXEMPT) =====
            'grains' => [
                'name' => 'Grains',
                'prefix' => 'GRN',
                'icon' => 'fa-wheat-awn',
                'color' => '#b45309',
                'gst_rate' => '0',
                'hsn_code' => '1001',
                'hsn_description' => 'Wheat and meslin',
                'cgst' => 0,
                'sgst' => 0,
                'description' => 'Cereals, grains, rice, wheat (GST exempt)',
                'tax_slabs' => []
            ],

            // ===== OILS =====
            'oils' => [
                'name' => 'Oils',
                'prefix' => 'OIL',
                'icon' => 'fa-flask',
                'color' => '#d97706',
                'gst_rate' => '5',
                'hsn_code' => '1512',
                'hsn_description' => 'Sunflower, safflower or cotton-seed oil',
                'cgst' => 2.5,
                'sgst' => 2.5,
                'description' => 'Cooking oils, edible oils, fats',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 2.5], ['type' => 'sgst', 'rate' => 2.5]]
            ],

            // ===== PACKAGING =====
            'packaging' => [
                'name' => 'Packaging Materials',
                'prefix' => 'PKG',
                'icon' => 'fa-box-open',
                'color' => '#64748b',
                'gst_rate' => '18',
                'hsn_code' => '3923',
                'hsn_description' => 'Articles for conveyance of goods, of plastics',
                'cgst' => 9,
                'sgst' => 9,
                'description' => 'Packaging materials, boxes, wraps',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 9], ['type' => 'sgst', 'rate' => 9]]
            ],

            // ===== LABORATORY =====
            'laboratory' => [
                'name' => 'Laboratory Equipment',
                'prefix' => 'LAB',
                'icon' => 'fa-flask',
                'color' => '#7c3aed',
                'gst_rate' => '18',
                'hsn_code' => '7017',
                'hsn_description' => 'Laboratory, hygienic or pharmaceutical glassware',
                'cgst' => 9,
                'sgst' => 9,
                'description' => 'Lab equipment, glassware, scientific instruments',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 9], ['type' => 'sgst', 'rate' => 9]]
            ],

            // ===== SAFETY =====
            'safety' => [
                'name' => 'Safety Equipment',
                'prefix' => 'SAF',
                'icon' => 'fa-hard-hat',
                'color' => '#eab308',
                'gst_rate' => '18',
                'hsn_code' => '9004',
                'hsn_description' => 'Safety glasses and goggles',
                'cgst' => 9,
                'sgst' => 9,
                'description' => 'Safety equipment, PPE, protective gear',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 9], ['type' => 'sgst', 'rate' => 9]]
            ],

            // ===== HVAC =====
            'hvac' => [
                'name' => 'HVAC & Cooling',
                'prefix' => 'HVC',
                'icon' => 'fa-snowflake',
                'color' => '#0284c7',
                'gst_rate' => '28',
                'hsn_code' => '8415',
                'hsn_description' => 'Air conditioning machines',
                'cgst' => 14,
                'sgst' => 14,
                'description' => 'HVAC systems, ACs, cooling equipment',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 14], ['type' => 'sgst', 'rate' => 14]]
            ],

            // ===== PLUMBING =====
            'plumbing' => [
                'name' => 'Plumbing Materials',
                'prefix' => 'PLB',
                'icon' => 'fa-faucet',
                'color' => '#0891b2',
                'gst_rate' => '18',
                'hsn_code' => '7412',
                'hsn_description' => 'Copper tube and pipe fittings',
                'cgst' => 9,
                'sgst' => 9,
                'description' => 'Plumbing supplies, pipes, fittings',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 9], ['type' => 'sgst', 'rate' => 9]]
            ],

            // ===== ELECTRICAL =====
            'electrical' => [
                'name' => 'Electrical Items',
                'prefix' => 'ELC',
                'icon' => 'fa-bolt',
                'color' => '#f59e0b',
                'gst_rate' => '18',
                'hsn_code' => '8536',
                'hsn_description' => 'Electrical switching apparatus',
                'cgst' => 9,
                'sgst' => 9,
                'description' => 'Electrical supplies, switches, wiring',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 9], ['type' => 'sgst', 'rate' => 9]]
            ],

            // ===== HARDWARE =====
            'hardware' => [
                'name' => 'Hardware',
                'prefix' => 'HRD',
                'icon' => 'fa-tools',
                'color' => '#475569',
                'gst_rate' => '18',
                'hsn_code' => '8302',
                'hsn_description' => 'Base metal mountings and fittings',
                'cgst' => 9,
                'sgst' => 9,
                'description' => 'Hardware, fasteners, tools, fittings',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 9], ['type' => 'sgst', 'rate' => 9]]
            ],

            // ===== PAINTS =====
            'paints' => [
                'name' => 'Paints & Coatings',
                'prefix' => 'PNT',
                'icon' => 'fa-paint-roller',
                'color' => '#dc2626',
                'gst_rate' => '18',
                'hsn_code' => '3209',
                'hsn_description' => 'Paints and varnishes based on polymers',
                'cgst' => 9,
                'sgst' => 9,
                'description' => 'Paints, coatings, varnishes, colors',
                'tax_slabs' => [['type' => 'cgst', 'rate' => 9], ['type' => 'sgst', 'rate' => 9]]
            ],
        ];
    }

    // ==================== PREDEFINED CATEGORY CHECK ====================
    
    /**
     * Check if this category is a predefined category
     */
    public function isPredefined()
    {
        $predefined = self::getPredefinedCategories();
        $predefinedNames = array_column($predefined, 'name');
        return in_array($this->category_name, $predefinedNames);
    }

    /**
     * Get the predefined category key for this category
     */
    public function getPredefinedKey()
    {
        $predefined = self::getPredefinedCategories();
        foreach ($predefined as $key => $data) {
            if ($data['name'] === $this->category_name) {
                return $key;
            }
        }
        return null;
    }

    /**
     * Get predefined category data for this category
     */
    public function getPredefinedData()
    {
        $key = $this->getPredefinedKey();
        if ($key) {
            $predefined = self::getPredefinedCategories();
            return $predefined[$key] ?? null;
        }
        return null;
    }

    // ==================== ITEMS COUNT HELPER ====================
    
    /**
     * Get total items count (including subcategory items)
     */
    public function itemsCount()
    {
        // Count items directly in this category
        $directItems = $this->items()->count();
        
        // Count items in subcategories
        $subCategoryItems = $this->subCategoryItems()->count();
        
        return $directItems + $subCategoryItems;
    }

    /**
     * Check if category can be deleted (no items or subcategories)
     */
    public function canBeDeleted()
    {
        return $this->itemsCount() === 0 && $this->subCategories()->count() === 0;
    }

    /**
     * Get deletion blockers
     */
    public function getDeletionBlockers()
    {
        $blockers = [];
        
        $itemCount = $this->itemsCount();
        if ($itemCount > 0) {
            $blockers[] = [
                'type' => 'items',
                'count' => $itemCount,
                'message' => "This category has {$itemCount} item(s) associated with it."
            ];
        }
        
        $subCount = $this->subCategories()->count();
        if ($subCount > 0) {
            $blockers[] = [
                'type' => 'sub_categories',
                'count' => $subCount,
                'message' => "This category has {$subCount} subcategory(ies) associated with it."
            ];
        }
        
        return $blockers;
    }

    /**
     * Get structure tree (category with subcategories)
     */
    public function getStructureTree()
    {
        return [
            'id' => $this->id,
            'name' => $this->category_name,
            'code' => $this->category_code,
            'subcategories' => $this->subCategories->map(function($sub) {
                return [
                    'id' => $sub->id,
                    'name' => $sub->subcategory_name,
                    'code' => $sub->subcategory_code
                ];
            })
        ];
    }

    // ==================== TAX HELPER METHODS ====================

    public function getApplicableTaxRate()
    {
        return $this->gst_rate ?? $this->taxSlab?->tax_rate ?? null;
    }

    public function getTaxComposition()
    {
        if ($this->tax_composition) {
            return $this->tax_composition;
        }

        $rate = $this->getApplicableTaxRate();
        if ($rate && $rate > 0) {
            return [
                ['type' => 'cgst', 'rate' => $rate / 2],
                ['type' => 'sgst', 'rate' => $rate / 2]
            ];
        }

        return [];
    }

    public function calculateTax($amount)
    {
        $taxBreakdown = [];
        $totalTax = 0;

        foreach ($this->getTaxComposition() as $tax) {
            $taxAmount = ($amount * $tax['rate']) / 100;
            $taxBreakdown[] = [
                'type' => $tax['type'],
                'rate' => $tax['rate'],
                'amount' => round($taxAmount, 2)
            ];
            $totalTax += $taxAmount;
        }

        return [
            'base_amount' => $amount,
            'total_tax' => round($totalTax, 2),
            'total_with_tax' => round($amount + $totalTax, 2),
            'tax_breakdown' => $taxBreakdown
        ];
    }

    public function getHSNCodeDisplay()
    {
        return $this->hsnCode?->hsn_code ?? null;
    }

    public function getCGSTRate()
    {
        $rate = $this->getApplicableTaxRate();
        return $rate ? $rate / 2 : 0;
    }

    public function getSGSTRate()
    {
        return $this->getCGSTRate();
    }

    // ==================== VALIDATION RULES ====================

    public static function getValidationRules($categoryId = null, $instituteId = null)
    {
        $uniqueRule = 'unique:inventory_categories,category_name';
        $uniqueCodeRule = 'unique:inventory_categories,category_code';
        
        if ($categoryId) {
            $uniqueRule .= ',' . $categoryId . ',id,institute_id,' . $instituteId;
            $uniqueCodeRule .= ',' . $categoryId . ',id,institute_id,' . $instituteId;
        } elseif ($instituteId) {
            $uniqueRule .= ',NULL,id,institute_id,' . $instituteId;
            $uniqueCodeRule .= ',NULL,id,institute_id,' . $instituteId;
        }

        return [
            'category_name' => ['required', 'max:255', $uniqueRule],
            'configuration_id' => ['required', 'exists:inventory_configurations,id'],
            'category_code' => ['required', 'max:50', $uniqueCodeRule],
            'icon_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:1024'],
            'status' => ['nullable', 'in:0,1'],
            'gst_rate' => ['nullable', 'in:0,3,5,12,18,28'],
            'tax_slab_id' => ['nullable', 'exists:tax_slabs,id'],
            'hsn_code_id' => ['nullable', 'exists:hsn_codes,id'],
            'is_gst_applicable' => ['boolean'],
            'is_hsn_mandatory' => ['boolean']
        ];
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeWithGST($query)
    {
        return $query->where('is_gst_applicable', true);
    }

    public function scopeByGSTRate($query, $rate)
    {
        return $query->where('gst_rate', $rate);
    }

    public function scopeByTaxSlab($query, $taxSlabId)
    {
        return $query->where('tax_slab_id', $taxSlabId);
    }

    public function scopeByHSNCode($query, $hsnCodeId)
    {
        return $query->where('hsn_code_id', $hsnCodeId);
    }
}