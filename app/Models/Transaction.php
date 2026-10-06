<?php
// app/Models/Transaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'inventory_transactions';

    protected $fillable = [
        'institute_id',
        'item_id',
        'type',
        'quantity_change',
        'previous_quantity',
        'new_quantity',
        'performed_by',
        'notes',
        'additional_data'
    ]; 

    protected $casts = [
        'additional_data' => 'array',
        'quantity_change' => 'integer',
        'previous_quantity' => 'integer',
        'new_quantity' => 'integer',
        'created_at' => 'datetime'
    ];

    // Append virtual attributes to JSON
    protected $appends = [
        'formatted_date',
        'formatted_time',
        'type_label',
        'item_name',
        'category_name'
    ];

    // Relationships
    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class,'item_id');
    }

    // Accessors
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }

    public function getFormattedTimeAttribute()
    {
        return $this->created_at->format('h:i A');
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            'in' => 'Stock In',
            'out' => 'Stock Out',
            'adjust' => 'Adjustment'
        ];
        
        return $labels[$this->type] ?? ucfirst($this->type);
    }

    public function getItemNameAttribute()
    {
        return $this->item->name ?? 'Unknown Item';
    }

    public function getCategoryNameAttribute()
    {
        return $this->item->category_name ?? 'Uncategorized';
    }

    public function getQuantityChangeFormattedAttribute()
    {
        $sign = $this->type === 'in' ? '+' : '-';
        return $sign . $this->quantity_change;
    }

    // Scopes
    public function scopeStockIn($query)
    {
        return $query->where('type', 'in');
    }

    public function scopeStockOut($query)
    {
        return $query->where('type', 'out');
    }

    public function scopeAdjustments($query)
    {
        return $query->where('type', 'adjust');
    }

    public function scopeForCategory($query, $categoryId)
    {
        return $query->whereHas('item', function ($q) use ($categoryId) {
            $q->where('category_id', $categoryId);
        });
    }

    public function scopeForItem($query, $itemId)
    {
        return $query->where('item_id', $itemId);
    }

    public function scopeDateRange($query, $startDate, $endDate = null)
    {
        $query->whereDate('created_at', '>=', $startDate);
        
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        
        return $query;
    }

    public function scopePerformedBy($query, $name)
    {
        return $query->where('performed_by', 'like', "%{$name}%");
    }

    // Methods
    public function getAdditionalData($key, $default = null)
    {
        $data = $this->additional_data ?? [];
        return $data[$key] ?? $default;
    }

    public function setAdditionalData($key, $value)
    {
        $data = $this->additional_data ?? [];
        $data[$key] = $value;
        $this->additional_data = $data;
        $this->save();
        return $this;
    }
}