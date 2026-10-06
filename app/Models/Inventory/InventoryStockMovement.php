<?php
// app/Models/Inventory/InventoryStockMovement.php

namespace App\Models\Inventory;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryStockMovement extends Model
{
    use SoftDeletes;

    protected $table = 'inventory_stock_movements';

    protected $guarded = [];

    // In InventoryStockMovement.php - Add store_id to casts if not present

    protected $casts = [
        'quantity' => 'decimal:2',
        'previous_stock' => 'decimal:2',
        'new_stock' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== MOVEMENT TYPES ====================
    const TYPE_IN = 'IN';
    const TYPE_OUT = 'OUT';
    const TYPE_TRANSFER_IN = 'TRANSFER_IN';
    const TYPE_TRANSFER_OUT = 'TRANSFER_OUT';
    const TYPE_ADJUSTMENT = 'ADJUSTMENT';

    public static function getMovementTypes()
    {
        return [
            self::TYPE_IN => 'Stock In',
            self::TYPE_OUT => 'Stock Out',
            self::TYPE_TRANSFER_IN => 'Transfer In',
            self::TYPE_TRANSFER_OUT => 'Transfer Out',
            self::TYPE_ADJUSTMENT => 'Adjustment',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    public function store()
    {
        return $this->belongsTo(InventoryStore::class, 'store_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(InventoryWarehouse::class, 'warehouse_id');
    }

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

    // ==================== SCOPES ====================

    public function scopeIn($query)
    {
        return $query->where('movement_type', self::TYPE_IN);
    }

    public function scopeOut($query)
    {
        return $query->where('movement_type', self::TYPE_OUT);
    }

    public function scopeTransferIn($query)
    {
        return $query->where('movement_type', self::TYPE_TRANSFER_IN);
    }

    public function scopeTransferOut($query)
    {
        return $query->where('movement_type', self::TYPE_TRANSFER_OUT);
    }

    public function scopeAdjustment($query)
    {
        return $query->where('movement_type', self::TYPE_ADJUSTMENT);
    }

    public function scopeByItem($query, $itemId)
    {
        return $query->where('item_id', $itemId);
    }

    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // ==================== ACCESSORS ====================

    public function getMovementTypeTextAttribute()
    {
        $types = self::getMovementTypes();
        return $types[$this->movement_type] ?? $this->movement_type;
    }

    public function getMovementTypeBadgeAttribute()
    {
        $badges = [
            self::TYPE_IN => 'badge-success',
            self::TYPE_OUT => 'badge-danger',
            self::TYPE_TRANSFER_IN => 'badge-info',
            self::TYPE_TRANSFER_OUT => 'badge-warning',
            self::TYPE_ADJUSTMENT => 'badge-secondary',
        ];
        return $badges[$this->movement_type] ?? 'badge-secondary';
    }

    public function getFormattedQuantityAttribute()
    {
        return number_format($this->quantity, 2);
    }

    public function getFormattedUnitCostAttribute()
    {
        return '₹' . number_format($this->unit_cost, 2);
    }

    public function getFormattedTotalCostAttribute()
    {
        return '₹' . number_format($this->total_cost, 2);
    }

    public function getDirectionAttribute()
    {
        $inTypes = [self::TYPE_IN, self::TYPE_TRANSFER_IN];
        return in_array($this->movement_type, $inTypes) ? 'IN' : 'OUT';
    }

    public function getDirectionSignAttribute()
    {
        return $this->direction === 'IN' ? '+' : '-';
    }

    // ==================== HELPER METHODS ====================

    /**
     * Create a stock movement record
     */
    public static function createMovement($data)
    {
        $movement = self::create([
            'institute_id' => $data['institute_id'],
            'item_id' => $data['item_id'],
            'warehouse_id' => $data['warehouse_id'] ?? null,
            'store_id' => $data['store_id'] ?? null,
            'movement_type' => $data['movement_type'],
            'reference_type' => $data['reference_type'] ?? null,
            'reference_id' => $data['reference_id'] ?? null,
            'quantity' => $data['quantity'],
            'previous_stock' => $data['previous_stock'] ?? 0,
            'new_stock' => $data['new_stock'] ?? 0,
            'unit_cost' => $data['unit_cost'] ?? 0,
            'total_cost' => $data['total_cost'] ?? 0,
            'reason' => $data['reason'] ?? null,
            'remarks' => $data['remarks'] ?? null,
            'created_by' => $data['created_by'] ?? auth()->id(),
        ]);

        return $movement;
    }

    /**
     * Get stock movement summary for an item
     */
    public static function getItemSummary($itemId)
    {
        return self::where('item_id', $itemId)
            ->selectRaw('
                movement_type,
                SUM(quantity) as total_quantity,
                COUNT(*) as movement_count,
                MIN(created_at) as first_movement,
                MAX(created_at) as last_movement
            ')
            ->groupBy('movement_type')
            ->get();
    }

    /**
     * Get total stock in/out for a period
     */
    public static function getPeriodSummary($itemId, $startDate, $endDate)
    {
        return self::where('item_id', $itemId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                SUM(CASE WHEN movement_type IN (?, ?) THEN quantity ELSE 0 END) as total_in,
                SUM(CASE WHEN movement_type IN (?, ?) THEN quantity ELSE 0 END) as total_out
            ', [
                self::TYPE_IN, self::TYPE_TRANSFER_IN,
                self::TYPE_OUT, self::TYPE_TRANSFER_OUT
            ])
            ->first();
    }

    /**
     * Get recent movements for an item
     */
    public static function getRecentMovements($itemId, $limit = 10)
    {
        return self::where('item_id', $itemId)
            ->with(['warehouse', 'creator'])
            ->latest()
            ->limit($limit)
            ->get();
    }
}