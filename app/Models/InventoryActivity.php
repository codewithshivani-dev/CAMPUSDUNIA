<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryActivity extends Model
{
    use HasFactory;
    protected $table = 'inventory_activity_logs';

    protected $fillable = [
        'institute_id',
        'item_id',
        'item_name',
        'item_code', 
        'category_id',
        'category_name',
        'log_type',
        'changes',
        'quantity_change',
        'previous_quantity',
        'new_quantity',
        'notes',
        'performed_by',
        'user_id'
    ];

    protected $casts = [
        'changes' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function itemTransaction()
    {
        return $this->belongsTo(Transaction::class, 'item_id');
    }

    // Scopes for filtering
    public function scopeByType($query, $type)
    {
        if ($type && $type !== 'all') {
            return $query->where('log_type', $type);
        }
        return $query;
    }

    public function scopeByCategory($query, $categoryId)
    {
        if ($categoryId && $categoryId !== 'all') {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }
 
    public function scopeByItem($query, $itemId)
    {
        if ($itemId) {
            return $query->where('item_id', $itemId);
        }
        return $query;
    }

    public function scopeByDate($query, $date)
    {
        if ($date) {
            return $query->whereDate('created_at', $date);
        }
        return $query;
    }

    // Helper method to log activity
    public static function logActivity($data)
    {
        return self::create([
            'institute_id' => $data['institute_id'],
            'item_id' => $data['item_id'] ?? null,
            'item_name' => $data['item_name'] ?? 'Unknown',
            'item_code' => $data['item_code'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'category_name' => $data['category_name'] ?? null,
            'log_type' => $data['log_type'],
            'changes' => $data['changes'] ?? null,
            'quantity_change' => $data['quantity_change'] ?? null,
            'previous_quantity' => $data['previous_quantity'] ?? null,
            'new_quantity' => $data['new_quantity'] ?? null,
            'notes' => $data['notes'] ?? null,
            'performed_by' => $data['performed_by'] ?? auth()->user()?->name ?? 'System',
            'user_id' => $data['user_id'] ?? auth()->id()
        ]);
    }

    // Format log type for display
    public function getFormattedLogTypeAttribute()
    {
        return match($this->log_type) {
            'in' => 'Stock In',
            'out' => 'Stock Out',
            'edit' => 'Edit',
            'create' => 'Create',
            'delete' => 'Delete',
            default => ucfirst($this->log_type)
        };
    }

    // Get formatted changes for display
    public function getFormattedChangesAttribute()
    {
        if (!$this->changes || empty($this->changes)) {
            return null;
        }

        $formatted = [];
        foreach ($this->changes as $field => $change) {
            $fieldName = ucfirst(str_replace('_', ' ', $field));
            $formatted[] = [
                'field' => $fieldName,
                'from' => $change['from'] ?? null,
                'to' => $change['to'] ?? null
            ];
        }

        return $formatted;
    }
}