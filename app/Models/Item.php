<?php
// app/Models/Item.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory, SoftDeletes;
    protected $table ='inventory_items';

    protected $fillable = [
        'institute_id',
        'category_id',
        'name',
        'code',
        'quantity',
        'price',
        'description',
        'low_stock_threshold',
        'subcategory', 
        'uniform_for',
        'uniform_gender',
        'uniform_size',
        'custom_fields'
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'has_uniform_fields' => 'boolean' // Virtual attribute
    ];

    // Append virtual attributes to JSON
    protected $appends = [
        'has_uniform_fields',
        'is_low_stock',
        'status',
        'category_name',
        'category_color',
        'category_icon'
    ];

    // Relationships
    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->latest();
    }

    // Accessors (Virtual Attributes)
    public function getHasUniformFieldsAttribute()
    {
        return $this->category->has_uniform_fields ?? false;
    }

    public function getIsLowStockAttribute()
    {
        return $this->quantity <= $this->low_stock_threshold;
    }

    public function getStatusAttribute()
    {
        if ($this->quantity == 0) {
            return [
                'label' => 'Out of Stock',
                'class' => 'danger',
                'color' => '#e74c3c'
            ];
        } elseif ($this->is_low_stock) {
            return [
                'label' => 'Low Stock',
                'class' => 'warning',
                'color' => '#f39c12'
            ];
        } else {
            return [
                'label' => 'In Stock',
                'class' => 'success',
                'color' => '#2ecc71'
            ];
        }
    }

    public function getCategoryNameAttribute()
    {
        return $this->category->name ?? 'Uncategorized';
    }

    public function getCategoryColorAttribute()
    {
        return $this->category->color ?? '#3498db';
    }

    public function getCategoryIconAttribute()
    {
        return $this->category->icon ?? 'fa-box';
    }

    public function getUniformDetailsAttribute()
    {
        if (!$this->has_uniform_fields) {
            return null;
        }

        return [
            'for' => $this->uniform_for,
            'gender' => $this->uniform_gender,
            'size' => $this->uniform_size
        ];
    }

    // Scopes
    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantity <= low_stock_threshold');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', 0);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    public function scopeWithUniform($query)
    {
        return $query->whereHas('category', function($q) {
            $q->where('has_uniform_fields', true);
        });
    }

    // Methods
    public function stockIn($quantity, $performedBy, $notes = null)
    {
        $this->increment('quantity', $quantity);
        
        $this->transactions()->create([
            'institute_id' => $this->institute_id,
            'type' => 'in',
            'quantity_change' => $quantity,
            'previous_quantity' => $this->quantity - $quantity,
            'new_quantity' => $this->quantity,
            'performed_by' => $performedBy,
            'notes' => $notes
        ]);

        return $this;
    }

    public function stockOut($quantity, $performedBy, $notes = null)
    {
        if ($quantity > $this->quantity) {
            throw new \Exception('Insufficient stock');
        }

        $previous = $this->quantity;
        $this->decrement('quantity', $quantity);
        
        $this->transactions()->create([
            'institute_id' => $this->institute_id,
            'type' => 'out',
            'quantity_change' => $quantity,
            'previous_quantity' => $previous,
            'new_quantity' => $this->quantity,
            'performed_by' => $performedBy,
            'notes' => $notes
        ]);

        return $this;
    }

    public function adjustStock($newQuantity, $performedBy, $notes = null)
    {
        $previous = $this->quantity;
        $this->quantity = $newQuantity;
        $this->save();
        
        $this->transactions()->create([
            'institute_id' => $this->institute_id,
            'type' => 'adjust',
            'quantity_change' => abs($newQuantity - $previous),
            'previous_quantity' => $previous,
            'new_quantity' => $newQuantity,
            'performed_by' => $performedBy,
            'notes' => $notes
        ]);

        return $this;
    }

    public function updateCustomField($key, $value)
    {
        $customFields = $this->custom_fields ?? [];
        $customFields[$key] = $value;
        $this->custom_fields = $customFields;
        $this->save();
        return $this;
    }

    public function removeCustomField($key)
    {
        $customFields = $this->custom_fields ?? [];
        if (array_key_exists($key, $customFields)) {
            unset($customFields[$key]);
            $this->custom_fields = $customFields;
            $this->save();
        }
        return $this;
    }

    public function getCustomField($key, $default = null)
    {
        $customFields = $this->custom_fields ?? [];
        return $customFields[$key] ?? $default;
    }
}