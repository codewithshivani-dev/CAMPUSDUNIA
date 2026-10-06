<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Services\Inventory\InventoryLogger;

class InventoryReceiptOut extends Model
{
    use SoftDeletes;

    protected $table = 'inventory_receipts_out';

    protected $guarded = [];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'approved_at' => 'datetime',
        'print_count' => 'integer',
        'last_printed_at' => 'datetime',
    ];

    // ==================== RECEIPT OUT TYPES ====================
    const TYPE_SALE = 'SALE';
    const TYPE_TRANSFER = 'TRANSFER';
    const TYPE_RETURN = 'RETURN';
    const TYPE_DAMAGE = 'DAMAGE';
    const TYPE_WASTE = 'WASTE';
    const TYPE_ISSUE = 'ISSUE';           // NEW: Department issue
    const TYPE_CONSUMPTION = 'CONSUMPTION'; // NEW: Consumption (for recipes/kitchen)

    public static function getReceiptTypes()
    {
        return [
            self::TYPE_SALE => 'Sale',
            self::TYPE_TRANSFER => 'Transfer',
            self::TYPE_RETURN => 'Return',
            self::TYPE_DAMAGE => 'Damage',
            self::TYPE_WASTE => 'Waste',
            self::TYPE_ISSUE => 'Department Issue',
            self::TYPE_CONSUMPTION => 'Consumption',
        ];
    }

    // ==================== CONSUMPTION TYPES ====================
    const CONSUMPTION_KITCHEN = 'KITCHEN';
    const CONSUMPTION_PRODUCTION = 'PRODUCTION';
    const CONSUMPTION_PROJECT = 'PROJECT';
    const CONSUMPTION_OTHER = 'OTHER';

    public static function getConsumptionTypes()
    {
        return [
            self::CONSUMPTION_KITCHEN => 'Kitchen / Food Preparation',
            self::CONSUMPTION_PRODUCTION => 'Production / Manufacturing',
            self::CONSUMPTION_PROJECT => 'Project Work',
            self::CONSUMPTION_OTHER => 'Other',
        ];
    }

    // ==================== WASTE REASONS ====================
    const WASTE_EXPIRED = 'EXPIRED';
    const WASTE_DAMAGED = 'DAMAGED';
    const WASTE_BROKEN = 'BROKEN';
    const WASTE_SPOILED = 'SPOILED';
    const WASTE_OTHER = 'OTHER';

    public static function getWasteReasons()
    {
        return [
            self::WASTE_EXPIRED => 'Expired',
            self::WASTE_DAMAGED => 'Damaged',
            self::WASTE_BROKEN => 'Broken',
            self::WASTE_SPOILED => 'Spoiled / Rotten',
            self::WASTE_OTHER => 'Other',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(InventoryWarehouse::class, 'warehouse_id');
    }

    public function department()
    {
        return $this->belongsTo(InventoryDepartment::class, 'department_id');
    }

    public function assetInstance()
    {
        return $this->belongsTo(InventoryAssetInstance::class, 'asset_instance_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function reference()
    {
        return $this->morphTo();
    }

    // ==================== PRINT TRACKING ====================

    public function incrementPrintCount()
    {
        $this->increment('print_count');
        $this->update(['last_printed_at' => now()]);

        InventoryLogger::log([
            'module' => 'RECEIPT_OUT',
            'action' => 'PRINT',
            'record_id' => $this->id,
            'new_data' => [
                'receipt_number' => $this->receipt_number,
                'print_count' => $this->print_count + 1,
                'item_name' => $this->item->item_name ?? 'N/A',
                'printed_by' => auth()->user()->name ?? 'System'
            ],
            'remarks' => "Receipt #{$this->receipt_number} printed (Count: " . ($this->print_count + 1) . ")"
        ]);

        return $this;
    }

    public function getPrintCountAttribute($value)
    {
        return $value ?? 0;
    }

    // ==================== STATUS METHODS ====================

    public function isDraft()
    {
        return $this->status === 'DRAFT';
    }

    public function isApproved()
    {
        return $this->status === 'APPROVED';
    }

    public function isCompleted()
    {
        return $this->status === 'COMPLETED';
    }

    public function isCancelled()
    {
        return $this->status === 'CANCELLED';
    }

    // ==================== ACTION METHODS ====================

    public function approve()
    {
        if (!$this->isDraft()) {
            throw new \Exception('Only draft receipts can be approved.');
        }

        $this->update([
            'status' => 'APPROVED',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        InventoryLogger::log([
            'module' => 'RECEIPT_OUT',
            'action' => 'APPROVE',
            'record_id' => $this->id,
            'remarks' => 'Receipt approved'
        ]);

        return $this;
    }

    public function complete()
    {
        if (!$this->isApproved()) {
            throw new \Exception('Only approved receipts can be completed.');
        }

        \DB::transaction(function () {
            $item = $this->item;
            
            // If it's a department issue, we don't reduce stock from warehouse
            // but we track it differently
            if ($this->receipt_type === self::TYPE_ISSUE) {
                // Track department issue without removing from stock
                // The item is issued to department but still in system
                $this->update([
                    'status' => 'COMPLETED'
                ]);
                
                InventoryLogger::log([
                    'module' => 'RECEIPT_OUT',
                    'action' => 'DEPARTMENT_ISSUE',
                    'record_id' => $this->id,
                    'new_data' => [
                        'quantity' => $this->quantity,
                        'item' => $this->item->item_name,
                        'department' => $this->department->name ?? 'N/A',
                        'issued_to' => $this->issued_to
                    ]
                ]);
                return;
            }

            // For consumption, we remove stock
            if ($this->receipt_type === self::TYPE_CONSUMPTION) {
                $item->removeStock(
                    $this->quantity,
                    $this->warehouse_id,
                    $this,
                    'Consumption: ' . ($this->consumption_type ?? 'General')
                );
            } else {
                // Regular stock removal for SALE, TRANSFER, RETURN, DAMAGE, WASTE
                $item->removeStock(
                    $this->quantity,
                    $this->warehouse_id,
                    $this,
                    'Receipt Out #' . $this->receipt_number . ' - ' . $this->receipt_type_text,
                    null,
                    $this->asset_instance_id
                );
            }

            $this->update([
                'status' => 'COMPLETED'
            ]);

            InventoryLogger::log([
                'module' => 'RECEIPT_OUT',
                'action' => 'COMPLETE',
                'record_id' => $this->id,
                'new_data' => [
                    'quantity' => $this->quantity,
                    'item' => $this->item->item_name,
                    'warehouse' => $this->warehouse->warehouse_name,
                    'receipt_type' => $this->receipt_type,
                    'department' => $this->department->name ?? null,
                    'consumption_type' => $this->consumption_type,
                    'waste_reason' => $this->waste_reason
                ]
            ]);
        });

        return $this;
    }

    public function cancel()
    {
        $this->update([
            'status' => 'CANCELLED'
        ]);

        InventoryLogger::log([
            'module' => 'RECEIPT_OUT',
            'action' => 'CANCEL',
            'record_id' => $this->id,
            'remarks' => 'Receipt cancelled'
        ]);

        return $this;
    }

    // ==================== SCOPES ====================

    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    public function scopeByItem($query, $itemId)
    {
        return $query->where('item_id', $itemId);
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeByReceiptType($query, $type)
    {
        return $query->where('receipt_type', $type);
    }

    public function scopeByConsumptionType($query, $type)
    {
        return $query->where('consumption_type', $type);
    }

    public function scopeByWasteReason($query, $reason)
    {
        return $query->where('waste_reason', $reason);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'DRAFT');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'APPROVED');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'COMPLETED');
    }

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    // ==================== ACCESSORS ====================

    public function getStatusTextAttribute()
    {
        $statuses = [
            'DRAFT' => 'Draft',
            'APPROVED' => 'Approved',
            'COMPLETED' => 'Completed',
            'CANCELLED' => 'Cancelled'
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'DRAFT' => 'bg-secondary',
            'APPROVED' => 'bg-warning',
            'COMPLETED' => 'bg-success',
            'CANCELLED' => 'bg-danger'
        ];
        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getReceiptTypeTextAttribute()
    {
        $types = self::getReceiptTypes();
        return $types[$this->receipt_type] ?? $this->receipt_type;
    }

    public function getReceiptTypeBadgeAttribute()
    {
        $badges = [
            'SALE' => 'bg-primary',
            'TRANSFER' => 'bg-info',
            'RETURN' => 'bg-warning',
            'DAMAGE' => 'bg-danger',
            'WASTE' => 'bg-danger',
            'ISSUE' => 'bg-secondary',
            'CONSUMPTION' => 'bg-success',
        ];
        return $badges[$this->receipt_type] ?? 'bg-secondary';
    }

    public function getConsumptionTypeTextAttribute()
    {
        $types = self::getConsumptionTypes();
        return $types[$this->consumption_type] ?? $this->consumption_type;
    }

    public function getWasteReasonTextAttribute()
    {
        $reasons = self::getWasteReasons();
        return $reasons[$this->waste_reason] ?? $this->waste_reason;
    }
}