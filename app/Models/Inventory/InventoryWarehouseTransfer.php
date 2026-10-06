<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Services\Inventory\InventoryLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InventoryWarehouseTransfer extends Model
{
    use SoftDeletes;

    protected $table = 'inventory_warehouse_transfers';

    protected $guarded = [];

    protected $casts = [
        'quantity' => 'decimal:2',
        'transfer_date' => 'datetime',
        'expected_arrival_date' => 'datetime',
        'received_date' => 'datetime',
        'approved_at' => 'datetime',
        'print_count' => 'integer',
        'last_printed_at' => 'datetime',
        // Location path tracking
        'from_location_path' => 'string',
        'to_location_path' => 'string',
        'source_location_details' => 'array',
        'destination_location_details' => 'array',
        // Transfer metadata
        'metadata' => 'array',
        'items_snapshot' => 'array',
        'batch_details' => 'array',
        'asset_details' => 'array',
    ];

    // ==================== RELATIONSHIPS ====================

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    public function fromWarehouse()
    {
        return $this->belongsTo(InventoryWarehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(InventoryWarehouse::class, 'to_warehouse_id');
    }

    public function fromStore()
    {
        return $this->belongsTo(InventoryStore::class, 'from_store_id');
    }

    public function toStore()
    {
        return $this->belongsTo(InventoryStore::class, 'to_store_id');
    }

    public function receiptOut()
    {
        return $this->belongsTo(InventoryReceiptOut::class, 'receipt_out_id');
    }

    public function receiptIn()
    {
        return $this->belongsTo(InventoryReceiptIn::class, 'receipt_in_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    // ==================== STATUS METHODS ====================

    public function isPending()
    {
        return $this->status === 'PENDING';
    }

    public function isApproved()
    {
        return $this->status === 'APPROVED';
    }

    public function isInTransit()
    {
        return $this->status === 'IN_TRANSIT';
    }

    public function isCompleted()
    {
        return $this->status === 'COMPLETED';
    }

    public function isCancelled()
    {
        return $this->status === 'CANCELLED';
    }

    // ==================== LOCATION PATH METHODS ====================

    /**
     * Get the full source location path
     */
    public function getSourceLocationPathAttribute()
    {
        if ($this->from_location_path) {
            return $this->from_location_path;
        }

        return $this->buildLocationPath('from');
    }

    /**
     * Get the full destination location path
     */
    public function getDestinationLocationPathAttribute()
    {
        if ($this->to_location_path) {
            return $this->to_location_path;
        }

        return $this->buildLocationPath('to');
    }

    /**
     * Build location path from related models
     */
    private function buildLocationPath($direction)
    {
        $parts = [];
        $warehouseKey = $direction . '_warehouse_id';
        $storeKey = $direction . '_store_id';

        if ($this->{$warehouseKey}) {
            $warehouse = $this->{$direction . 'Warehouse'};
            if ($warehouse) {
                $parts[] = '🏢 ' . $warehouse->warehouse_name;
                if ($warehouse->warehouse_code) {
                    $parts[] = '(' . $warehouse->warehouse_code . ')';
                }
                
                // Add location hierarchy if available
                $locationParts = [];
                if ($warehouse->block_id) {
                    $locationParts[] = 'Block: ' . $warehouse->block_id;
                }
                if ($warehouse->floor_id) {
                    $locationParts[] = 'Floor: ' . $warehouse->floor_id;
                }
                if ($warehouse->room_id) {
                    $locationParts[] = 'Room: ' . $warehouse->room_id;
                }
                if (!empty($locationParts)) {
                    $parts[] = '📍 ' . implode(' → ', $locationParts);
                }
            }
        }

        if ($this->{$storeKey}) {
            $store = $this->{$direction . 'Store'};
            if ($store) {
                $parts[] = '🏪 ' . $store->store_name;
                if ($store->store_code) {
                    $parts[] = '(' . $store->store_code . ')';
                }
                
                $locationParts = [];
                if ($store->floor_id) {
                    $locationParts[] = 'Floor: ' . $store->floor_id;
                }
                if ($store->room_id) {
                    $locationParts[] = 'Room: ' . $store->room_id;
                }
                if (!empty($locationParts)) {
                    $parts[] = '📍 ' . implode(' → ', $locationParts);
                }
            }
        }

        return implode(' ', $parts) ?: 'Unknown Location';
    }

    /**
     * Update location paths from related models
     */
    public function updateLocationPaths()
    {
        $this->from_location_path = $this->buildLocationPath('from');
        $this->to_location_path = $this->buildLocationPath('to');
        $this->save();

        return $this;
    }

    /**
     * Get source location details as array
     */
    public function getSourceLocationDetails()
    {
        $details = [
            'type' => null,
            'id' => null,
            'name' => null,
            'code' => null,
            'path' => $this->source_location_path,
        ];

        if ($this->from_warehouse_id && $this->fromWarehouse) {
            $details['type'] = 'warehouse';
            $details['id'] = $this->from_warehouse_id;
            $details['name'] = $this->fromWarehouse->warehouse_name;
            $details['code'] = $this->fromWarehouse->warehouse_code;
            $details['address'] = $this->fromWarehouse->address;
            $details['contact'] = [
                'person' => $this->fromWarehouse->contact_person,
                'phone' => $this->fromWarehouse->phone,
                'email' => $this->fromWarehouse->email,
            ];
            $details['hierarchy'] = [
                'block_id' => $this->fromWarehouse->block_id,
                'floor_id' => $this->fromWarehouse->floor_id,
                'room_id' => $this->fromWarehouse->room_id,
            ];
        } elseif ($this->from_store_id && $this->fromStore) {
            $details['type'] = 'store';
            $details['id'] = $this->from_store_id;
            $details['name'] = $this->fromStore->store_name;
            $details['code'] = $this->fromStore->store_code;
            $details['address'] = $this->fromStore->address;
            $details['contact'] = [
                'person' => $this->fromStore->contact_person,
                'phone' => $this->fromStore->phone,
                'email' => $this->fromStore->email,
            ];
            $details['hierarchy'] = [
                'floor_id' => $this->fromStore->floor_id,
                'room_id' => $this->fromStore->room_id,
            ];
            if ($this->fromStore->warehouse) {
                $details['parent_warehouse'] = [
                    'id' => $this->fromStore->warehouse->id,
                    'name' => $this->fromStore->warehouse->warehouse_name,
                    'code' => $this->fromStore->warehouse->warehouse_code,
                ];
            }
        }

        return $details;
    }

    /**
     * Get destination location details as array
     */
    public function getDestinationLocationDetails()
    {
        $details = [
            'type' => null,
            'id' => null,
            'name' => null,
            'code' => null,
            'path' => $this->destination_location_path,
        ];

        if ($this->to_warehouse_id && $this->toWarehouse) {
            $details['type'] = 'warehouse';
            $details['id'] = $this->to_warehouse_id;
            $details['name'] = $this->toWarehouse->warehouse_name;
            $details['code'] = $this->toWarehouse->warehouse_code;
            $details['address'] = $this->toWarehouse->address;
            $details['contact'] = [
                'person' => $this->toWarehouse->contact_person,
                'phone' => $this->toWarehouse->phone,
                'email' => $this->toWarehouse->email,
            ];
            $details['hierarchy'] = [
                'block_id' => $this->toWarehouse->block_id,
                'floor_id' => $this->toWarehouse->floor_id,
                'room_id' => $this->toWarehouse->room_id,
            ];
        } elseif ($this->to_store_id && $this->toStore) {
            $details['type'] = 'store';
            $details['id'] = $this->to_store_id;
            $details['name'] = $this->toStore->store_name;
            $details['code'] = $this->toStore->store_code;
            $details['address'] = $this->toStore->address;
            $details['contact'] = [
                'person' => $this->toStore->contact_person,
                'phone' => $this->toStore->phone,
                'email' => $this->toStore->email,
            ];
            $details['hierarchy'] = [
                'floor_id' => $this->toStore->floor_id,
                'room_id' => $this->toStore->room_id,
            ];
            if ($this->toStore->warehouse) {
                $details['parent_warehouse'] = [
                    'id' => $this->toStore->warehouse->id,
                    'name' => $this->toStore->warehouse->warehouse_name,
                    'code' => $this->toStore->warehouse->warehouse_code,
                ];
            }
        }

        return $details;
    }

    // ==================== ACTION METHODS ====================

    public function approve()
    {
        if (!$this->isPending()) {
            throw new \Exception('Only pending transfers can be approved.');
        }

        // Update location paths before approval
        $this->updateLocationPaths();

        $updateData = [
            'status' => 'APPROVED',
            'approved_at' => now(),
            'source_location_details' => $this->getSourceLocationDetails(),
            'destination_location_details' => $this->getDestinationLocationDetails(),
        ];

        // Only add approved_by if column exists
        $columns = Schema::getColumnListing('inventory_warehouse_transfers');
        if (in_array('approved_by', $columns)) {
            $updateData['approved_by'] = auth()->id();
        }

        $this->update($updateData);

        InventoryLogger::log([
            'module' => 'TRANSFER',
            'action' => 'APPROVE',
            'record_id' => $this->id,
            'new_data' => [
                'transfer_code' => $this->transfer_code,
                'from_location' => $this->source_location_path,
                'to_location' => $this->destination_location_path,
                'quantity' => $this->quantity,
                'approved_by' => auth()->user()->name ?? 'System'
            ],
            'remarks' => 'Transfer approved: ' . $this->transfer_code
        ]);

        return $this;
    }

    /**
     * Mark transfer as in transit
     */
    public function markInTransit($logisticsData = null)
    {
        if (!$this->isApproved()) {
            throw new \Exception('Only approved transfers can be marked as in transit.');
        }

        $updateData = [
            'status' => 'IN_TRANSIT',
            'transit_started_at' => now(),
        ];

        if ($logisticsData) {
            $updateData['logistics'] = $logisticsData;
        }

        $this->update($updateData);

        InventoryLogger::log([
            'module' => 'TRANSFER',
            'action' => 'IN_TRANSIT',
            'record_id' => $this->id,
            'new_data' => [
                'transfer_code' => $this->transfer_code,
                'from_location' => $this->source_location_path,
                'to_location' => $this->destination_location_path,
                'logistics' => $logisticsData
            ],
            'remarks' => 'Transfer marked as in transit: ' . $this->transfer_code
        ]);

        return $this;
    }

    public function startTransfer()
    {
        if (!$this->isApproved()) {
            throw new \Exception('Only approved transfers can be started.');
        }

        DB::transaction(function () {
            // Update source warehouse stock (OUT)
            $this->item->decrement('current_stock', $this->quantity);
            $this->item->decrement('available_stock', $this->quantity);

            // Create IN receipt for destination
            $receiptIn = InventoryReceiptIn::create([
                'institute_id' => $this->institute_id,
                'receipt_number' => 'IN-' . strtoupper(uniqid()),
                'item_id' => $this->item_id,
                'warehouse_id' => $this->to_warehouse_id,
                'store_id' => $this->to_store_id,
                'quantity' => $this->quantity,
                'unit_price' => $this->item->buying_price ?? 0,
                'total_price' => ($this->item->buying_price ?? 0) * $this->quantity,
                'receipt_type' => 'TRANSFER',
                'reference_type' => get_class($this),
                'reference_id' => $this->id,
                'supplier_name' => $this->fromWarehouse ? $this->fromWarehouse->warehouse_name : 'N/A',
                'notes' => 'Transfer from ' . ($this->fromWarehouse ? $this->fromWarehouse->warehouse_name : 'N/A'),
                'status' => 'COMPLETED',
                'created_by' => auth()->id()
            ]);

            // Update destination warehouse stock (IN)
            $inventoryItem = InventoryItem::where('institute_id', $this->institute_id)
                ->where('item_code', $this->item->item_code)
                ->where(function($query) {
                    if ($this->to_warehouse_id) {
                        $query->where('warehouse_id', $this->to_warehouse_id);
                    }
                    if ($this->to_store_id) {
                        $query->where('store_id', $this->to_store_id);
                    }
                })
                ->first();

            if ($inventoryItem) {
                $inventoryItem->increment('current_stock', $this->quantity);
                $inventoryItem->increment('available_stock', $this->quantity);
            } else {
                // Create new item in destination
                $newItem = $this->item->replicate();
                $newItem->warehouse_id = $this->to_warehouse_id;
                $newItem->store_id = $this->to_store_id;
                $newItem->current_stock = $this->quantity;
                $newItem->available_stock = $this->quantity;
                $newItem->reserved_stock = 0;
                $newItem->save();
            }

            // Update warehouse utilization
            if ($this->fromWarehouse) {
                $this->fromWarehouse->updateUtilization();
            }
            if ($this->toWarehouse) {
                $this->toWarehouse->updateUtilization();
            }

            // Update location paths
            $this->updateLocationPaths();

            $updateData = [
                'status' => 'COMPLETED',
                'receipt_in_id' => $receiptIn->id,
                'received_date' => now(),
                'destination_location_details' => $this->getDestinationLocationDetails(),
            ];

            // Only add received_by if column exists
            $columns = Schema::getColumnListing('inventory_warehouse_transfers');
            if (in_array('received_by', $columns)) {
                $updateData['received_by'] = auth()->id();
            }

            $this->update($updateData);

            InventoryLogger::log([
                'module' => 'TRANSFER',
                'action' => 'COMPLETE',
                'record_id' => $this->id,
                'new_data' => [
                    'transfer_code' => $this->transfer_code,
                    'from_warehouse' => $this->fromWarehouse ? $this->fromWarehouse->warehouse_name : 'N/A',
                    'to_warehouse' => $this->toWarehouse ? $this->toWarehouse->warehouse_name : 'N/A',
                    'quantity' => $this->quantity,
                    'receipt_in_id' => $receiptIn->id,
                    'from_location_path' => $this->source_location_path,
                    'to_location_path' => $this->destination_location_path,
                ],
                'remarks' => 'Transfer completed: ' . $this->transfer_code
            ]);
        });

        return $this;
    }

    public function cancel()
    {
        if ($this->isCompleted()) {
            throw new \Exception('Completed transfers cannot be cancelled.');
        }

        $this->update([
            'status' => 'CANCELLED'
        ]);

        InventoryLogger::log([
            'module' => 'TRANSFER',
            'action' => 'CANCEL',
            'record_id' => $this->id,
            'remarks' => 'Transfer cancelled: ' . $this->transfer_code
        ]);

        return $this;
    }

    public function incrementPrintCount()
    {
        $this->increment('print_count');
        $this->update(['last_printed_at' => now()]);

        InventoryLogger::log([
            'module' => 'TRANSFER',
            'action' => 'PRINT',
            'record_id' => $this->id,
            'new_data' => [
                'transfer_code' => $this->transfer_code,
                'print_count' => ($this->print_count ?? 0) + 1,
                'printed_by' => auth()->user()->name ?? 'System'
            ],
            'remarks' => "Transfer #{$this->transfer_code} printed (Count: " . (($this->print_count ?? 0) + 1) . ")"
        ]);

        return $this;
    }

    // ==================== SNAPSHOT METHODS ====================

    /**
     * Take a snapshot of the transfer items for audit
     */
    public function takeSnapshot()
    {
        $items = is_array($this->items) ? $this->items : json_decode($this->items, true);
        
        if (!empty($items)) {
            $this->items_snapshot = $items;
            
            // Capture batch details if available
            if ($this->item && $this->item->track_batch) {
                $this->batch_details = $this->item->batches()
                    ->where('remaining_quantity', '>', 0)
                    ->get()
                    ->map(function($batch) {
                        return [
                            'id' => $batch->id,
                            'batch_number' => $batch->batch_number,
                            'quantity' => $batch->remaining_quantity,
                            'expiry_date' => $batch->expiry_date?->format('Y-m-d'),
                            'purchase_price' => $batch->purchase_price,
                        ];
                    })
                    ->toArray();
            }
            
            // Capture asset details if available
            if ($this->item && $this->item->item_type === 'ASSET') {
                $this->asset_details = $this->item->assetInstances()
                    ->where('status', 'ACTIVE')
                    ->limit($this->quantity)
                    ->get()
                    ->map(function($asset) {
                        return [
                            'id' => $asset->id,
                            'asset_code' => $asset->asset_code,
                            'serial_number' => $asset->serial_number,
                            'purchase_date' => $asset->purchase_date?->format('Y-m-d'),
                            'current_value' => $asset->current_value,
                        ];
                    })
                    ->toArray();
            }
            
            $this->save();
        }
        
        return $this;
    }

    // ==================== SCOPES ====================

    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'APPROVED');
    }

    public function scopeInTransit($query)
    {
        return $query->where('status', 'IN_TRANSIT');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'COMPLETED');
    }

    public function scopeByFromLocation($query, $locationType, $locationId)
    {
        if ($locationType === 'warehouse') {
            return $query->where('from_warehouse_id', $locationId);
        } elseif ($locationType === 'store') {
            return $query->where('from_store_id', $locationId);
        }
        return $query;
    }

    public function scopeByToLocation($query, $locationType, $locationId)
    {
        if ($locationType === 'warehouse') {
            return $query->where('to_warehouse_id', $locationId);
        } elseif ($locationType === 'store') {
            return $query->where('to_store_id', $locationId);
        }
        return $query;
    }

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('expected_arrival_date', '<', now())
            ->whereIn('status', ['APPROVED', 'IN_TRANSIT']);
    }

    // ==================== ACCESSORS ====================

    public function getStatusTextAttribute()
    {
        $statuses = [
            'PENDING' => 'Pending',
            'APPROVED' => 'Approved',
            'IN_TRANSIT' => 'In Transit',
            'COMPLETED' => 'Completed',
            'CANCELLED' => 'Cancelled'
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'PENDING' => 'badge-warning',
            'APPROVED' => 'badge-info',
            'IN_TRANSIT' => 'badge-primary',
            'COMPLETED' => 'badge-success',
            'CANCELLED' => 'badge-danger'
        ];
        return $badges[$this->status] ?? 'badge-secondary';
    }

    public function getIsOverdueAttribute()
    {
        if ($this->expected_arrival_date && !$this->isCompleted() && !$this->isCancelled()) {
            return now()->greaterThan($this->expected_arrival_date);
        }
        return false;
    }

    /**
     * Get transfer type label
     */
    public function getTransferTypeLabelAttribute()
    {
        if ($this->from_warehouse_id && $this->to_warehouse_id) {
            return 'Warehouse to Warehouse';
        } elseif ($this->from_store_id && $this->to_store_id) {
            return 'Store to Store';
        } elseif ($this->from_warehouse_id && $this->to_store_id) {
            return 'Warehouse to Store';
        } elseif ($this->from_store_id && $this->to_warehouse_id) {
            return 'Store to Warehouse';
        }
        return 'Unknown Transfer';
    }

    /**
     * Get transfer type badge
     */
    public function getTransferTypeBadgeAttribute()
    {
        $badges = [
            'Warehouse to Warehouse' => 'bg-primary',
            'Store to Store' => 'bg-success',
            'Warehouse to Store' => 'bg-info',
            'Store to Warehouse' => 'bg-warning text-dark',
        ];
        return $badges[$this->transfer_type_label] ?? 'bg-secondary';
    }

    /**
     * Get formatted source location
     */
    public function getFormattedSourceLocationAttribute()
    {
        return $this->source_location_path ?: 'Unknown';
    }

    /**
     * Get formatted destination location
     */
    public function getFormattedDestinationLocationAttribute()
    {
        return $this->destination_location_path ?: 'Unknown';
    }

    /**
     * Get transfer duration in days
     */
    public function getTransferDurationAttribute()
    {
        if ($this->created_at && $this->received_date) {
            return $this->created_at->diffInDays($this->received_date);
        }
        return null;
    }

    /**
     * Check if transfer is complete
     */
    public function getIsCompleteAttribute()
    {
        return $this->status === 'COMPLETED';
    }

    /**
     * Get the transfer status with icon
     */
    public function getStatusWithIconAttribute()
    {
        $icons = [
            'PENDING' => '⏳',
            'APPROVED' => '✅',
            'IN_TRANSIT' => '🚚',
            'COMPLETED' => '🎯',
            'CANCELLED' => '❌'
        ];
        return ($icons[$this->status] ?? '') . ' ' . $this->status_text;
    }

    /**
     * Get tracking number (alias for transfer_code)
     */
    public function getTrackingNumberAttribute()
    {
        return $this->transfer_code;
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if transfer can be completed
     */
    public function canBeCompleted()
    {
        return $this->isApproved() || $this->isInTransit();
    }

    /**
     * Check if transfer can be cancelled
     */
    public function canBeCancelled()
    {
        return !$this->isCompleted() && !$this->isCancelled();
    }

    /**
     * Get location chain for transfer
     */
    public function getLocationChain()
    {
        $from = $this->formatted_source_location;
        $to = $this->formatted_destination_location;
        
        if ($from && $to) {
            return $from . ' → ' . $to;
        }
        return 'Unknown Transfer Path';
    }

    /**
     * Get summary for transfer
     */
    public function getSummaryAttribute()
    {
        return [
            'id' => $this->id,
            'code' => $this->transfer_code,
            'status' => $this->status_text,
            'status_badge' => $this->status_badge,
            'type' => $this->transfer_type_label,
            'from' => $this->formatted_source_location,
            'to' => $this->formatted_destination_location,
            'quantity' => $this->quantity,
            'item' => $this->item ? $this->item->item_name : 'N/A',
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'expected_arrival' => $this->expected_arrival_date?->format('Y-m-d'),
            'is_overdue' => $this->is_overdue,
            'is_complete' => $this->is_complete,
            'location_chain' => $this->location_chain,
        ];
    }
}