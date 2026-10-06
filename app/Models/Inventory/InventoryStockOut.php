<?php
// app/Models/Inventory/InventoryStockOut.php

namespace App\Models\Inventory;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Services\Inventory\InventoryLogger;
use Illuminate\Support\Facades\DB;

class InventoryStockOut extends Model
{
    protected $table = 'inventory_stock_outs';

    protected $guarded = [];

    protected $casts = [
        // JSON fields
        'items' => 'array',
        'logistics' => 'array',
        'batch_data' => 'array',
        'serial_data' => 'array',
        'payment_details' => 'array',
        'source_location_details' => 'array',
        'destination_location_details' => 'array',
        
        // Decimal fields
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'amount_received' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'transfer_total_amount' => 'decimal:2',
        'transfer_amount_received' => 'decimal:2',
        
        // String fields
        'from_location_path' => 'string',
        'to_location_path' => 'string',
        
        // Date/Time fields
        'approved_at' => 'datetime',
        'received_at' => 'datetime',
        'expected_arrival_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    public function fromWarehouse()
    {
        return $this->belongsTo(InventoryWarehouse::class, 'from_warehouse_id');
    }

    public function fromStore()
    {
        return $this->belongsTo(InventoryStore::class, 'from_store_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(InventoryWarehouse::class, 'to_warehouse_id');
    }

    public function toStore()
    {
        return $this->belongsTo(InventoryStore::class, 'to_store_id');
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

    /**
     * Get the receipt out associated with this stock out
     */
    public function receiptOut()
    {
        return $this->belongsTo(InventoryReceiptOut::class, 'receipt_out_id');
    }

    /**
     * Get the receipt in associated with this stock out (for transfers)
     */
    public function receiptIn()
    {
        return $this->belongsTo(InventoryReceiptIn::class, 'receipt_in_id');
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeTransfer($query)
    {
        return $query->where('type', 'transfer');
    }

    public function scopeSell($query)
    {
        return $query->where('type', 'sell');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeHasBatchData($query)
    {
        return $query->whereNotNull('batch_data')->whereRaw('JSON_LENGTH(batch_data) > 0');
    }

    public function scopeHasSerialData($query)
    {
        return $query->whereNotNull('serial_data')->whereRaw('JSON_LENGTH(serial_data) > 0');
    }

    public function scopeByLocation($query, $locationType, $locationId)
    {
        if ($locationType === 'warehouse') {
            return $query->where('from_warehouse_id', $locationId)
                        ->orWhere('to_warehouse_id', $locationId);
        } elseif ($locationType === 'store') {
            return $query->where('from_store_id', $locationId)
                        ->orWhere('to_store_id', $locationId);
        }
        return $query;
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'approved' => 'info',
            'in-transit' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger'
        ];
        return $badges[$this->status] ?? 'secondary';
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            'transfer' => 'Transfer',
            'sell' => 'Sale'
        ];
        return $labels[$this->type] ?? $this->type;
    }

    public function getSubTypeLabelAttribute()
    {
        $labels = [
            'store_to_store' => 'Store to Store',
            'warehouse_to_warehouse' => 'Warehouse to Warehouse',
            'store_to_warehouse' => 'Store to Warehouse',
            'warehouse_to_store' => 'Warehouse to Store'
        ];
        return $labels[$this->sub_type] ?? $this->sub_type;
    }

    public function getFromLocationAttribute()
    {
        if ($this->from_store_id) {
            return $this->fromStore->store_name ?? 'N/A';
        }
        return $this->fromWarehouse->warehouse_name ?? 'N/A';
    }

    public function getToLocationAttribute()
    {
        if ($this->to_store_id) {
            return $this->toStore->store_name ?? 'N/A';
        }
        return $this->toWarehouse->warehouse_name ?? 'N/A';
    }

    /**
     * Get formatted batch data for display
     */
    public function getFormattedBatchDataAttribute()
    {
        if (empty($this->batch_data)) {
            return [];
        }

        $formatted = [];
        foreach ($this->batch_data as $itemId => $batches) {
            $item = InventoryItem::find($itemId);
            $formatted[] = [
                'item_id' => $itemId,
                'item_name' => $item ? $item->item_name : 'Unknown',
                'item_code' => $item ? $item->item_code : 'N/A',
                'batches' => $batches,
                'total_quantity' => array_sum(array_column($batches, 'quantity'))
            ];
        }
        return $formatted;
    }

    /**
     * Get formatted serial data for display
     */
    public function getFormattedSerialDataAttribute()
    {
        if (empty($this->serial_data)) {
            return [];
        }

        $formatted = [];
        foreach ($this->serial_data as $itemId => $serials) {
            $item = InventoryItem::find($itemId);
            $formatted[] = [
                'item_id' => $itemId,
                'item_name' => $item ? $item->item_name : 'Unknown',
                'item_code' => $item ? $item->item_code : 'N/A',
                'serials' => $serials,
                'total_count' => count($serials)
            ];
        }
        return $formatted;
    }

    /**
     * Get tracking status
     */
    public function getTrackingStatusAttribute()
    {
        $hasBatch = !empty($this->batch_data);
        $hasSerial = !empty($this->serial_data);
        
        if ($hasBatch && $hasSerial) {
            return 'Both Batch & Serial';
        } elseif ($hasBatch) {
            return 'Batch Tracked';
        } elseif ($hasSerial) {
            return 'Serial Tracked';
        }
        return 'No Tracking';
    }

    /**
     * Check if this stock out has tracking data
     */
    public function hasTrackingData()
    {
        return !empty($this->batch_data) || !empty($this->serial_data);
    }

    /**
     * Check if this stock out has batch data
     */
    public function hasBatchData()
    {
        return !empty($this->batch_data);
    }

    /**
     * Check if this stock out has serial data
     */
    public function hasSerialData()
    {
        return !empty($this->serial_data);
    }

    /**
     * Get the total reserved stock for this transaction
     */
    public function getReservedStockAttribute()
    {
        $items = is_array($this->items) ? $this->items : json_decode($this->items, true);
        if (empty($items)) {
            return 0;
        }
        return array_sum(array_column($items, 'quantity'));
    }

    /**
     * Get the location path for source
     */
    public function getSourceLocationPathAttribute()
    {
        if ($this->from_location_path) {
            return $this->from_location_path;
        }
        return $this->buildLocationPath('from');
    }

    /**
     * Get the location path for destination
     */
    public function getDestinationLocationPathAttribute()
    {
        if ($this->to_location_path) {
            return $this->to_location_path;
        }
        return $this->buildLocationPath('to');
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    /**
     * Build location path from warehouse and store IDs
     */
    public function buildLocationPath($direction)
    {
        $parts = [];
        
        if ($direction === 'from') {
            $warehouseId = $this->from_warehouse_id;
            $storeId = $this->from_store_id;
        } else {
            $warehouseId = $this->to_warehouse_id;
            $storeId = $this->to_store_id;
        }

        if ($warehouseId) {
            $warehouse = InventoryWarehouse::find($warehouseId);
            if ($warehouse) {
                $parts[] = '🏢 ' . $warehouse->warehouse_name;
                if ($warehouse->warehouse_code) {
                    $parts[] = '(' . $warehouse->warehouse_code . ')';
                }
            }
        }

        if ($storeId) {
            $store = InventoryStore::find($storeId);
            if ($store) {
                $parts[] = '🏪 ' . $store->store_name;
                if ($store->store_code) {
                    $parts[] = '(' . $store->store_code . ')';
                }
            }
        }

        return implode(' ', $parts) ?: 'N/A';
    }

    /**
     * Get the tracking summary for display
     */
    public function getTrackingSummaryAttribute()
    {
        $summary = [];
        
        if ($this->hasBatchData()) {
            $batchCount = 0;
            $totalBatchQty = 0;
            foreach ($this->batch_data as $batches) {
                $batchCount += count($batches);
                $totalBatchQty += array_sum(array_column($batches, 'quantity'));
            }
            $summary['batch'] = [
                'count' => $batchCount,
                'total_quantity' => $totalBatchQty
            ];
        }
        
        if ($this->hasSerialData()) {
            $serialCount = 0;
            foreach ($this->serial_data as $serials) {
                $serialCount += count($serials);
            }
            $summary['serial'] = [
                'count' => $serialCount
            ];
        }
        
        return $summary;
    }

    /**
     * Get all items with their tracking details
     */
    public function getItemsWithTrackingAttribute()
    {
        $items = is_array($this->items) ? $this->items : json_decode($this->items, true);
        if (empty($items)) {
            return [];
        }

        $result = [];
        foreach ($items as $itemData) {
            $item = InventoryItem::find($itemData['id']);
            $tracking = [
                'has_batch' => isset($this->batch_data[$itemData['id']]),
                'has_serial' => isset($this->serial_data[$itemData['id']]),
                'batch_count' => isset($this->batch_data[$itemData['id']]) ? count($this->batch_data[$itemData['id']]) : 0,
                'serial_count' => isset($this->serial_data[$itemData['id']]) ? count($this->serial_data[$itemData['id']]) : 0,
            ];
            
            $result[] = [
                'id' => $itemData['id'],
                'name' => $item ? $item->item_name : ($itemData['name'] ?? 'Unknown'),
                'code' => $item ? $item->item_code : ($itemData['code'] ?? 'N/A'),
                'quantity' => $itemData['quantity'],
                'price' => $itemData['price'] ?? 0,
                'tracking' => $tracking,
                'batches' => $tracking['has_batch'] ? $this->batch_data[$itemData['id']] : [],
                'serials' => $tracking['has_serial'] ? $this->serial_data[$itemData['id']] : [],
            ];
        }
        
        return $result;
    }

    /**
     * Check if this is a transfer between stores in the same warehouse
     */
    public function isSameWarehouseTransfer()
    {
        if ($this->type !== 'transfer') {
            return false;
        }
        
        if ($this->from_store_id && $this->to_store_id) {
            $fromStore = InventoryStore::find($this->from_store_id);
            $toStore = InventoryStore::find($this->to_store_id);
            if ($fromStore && $toStore) {
                return $fromStore->warehouse_id == $toStore->warehouse_id;
            }
        }
        return false;
    }

    // ============================================================
    // PROCESS METHODS
    // ============================================================

    /**
     * Process stock out for SELL type
     * This reduces stock from the source location and creates a receipt out
     * Now uses batch_data and serial_data for tracking
     */
    public function processSell()
    {
        if ($this->type !== 'sell') {
            throw new \Exception('This method is only for sell type stock outs.');
        }

        if ($this->status !== 'approved' && $this->status !== 'pending') {
            throw new \Exception('Stock out must be approved or pending to process.');
        }

        DB::transaction(function () {
            $items = is_array($this->items) ? $this->items : json_decode($this->items, true);
            $batchData = $this->batch_data ?? [];
            $serialData = $this->serial_data ?? [];
            
            foreach ($items as $itemData) {
                // Find the item in the source location
                $sourceItem = $this->findSourceItem($itemData['id']);
                
                if (!$sourceItem) {
                    throw new \Exception("Item not found in source location: {$itemData['name']}");
                }

                // Check stock availability
                if ($sourceItem->available_stock < $itemData['quantity']) {
                    throw new \Exception(
                        "Insufficient stock for {$sourceItem->item_name}. " .
                        "Available: {$sourceItem->available_stock}, Required: {$itemData['quantity']}"
                    );
                }

                // Handle batch tracking
                if ($sourceItem->track_batch && isset($batchData[$itemData['id']])) {
                    foreach ($batchData[$itemData['id']] as $batchSelection) {
                        $batch = InventoryItemBatch::where('item_id', $sourceItem->id)
                            ->where('id', $batchSelection['batch_id'])
                            ->first();
                        
                        if ($batch) {
                            $batch->deductStock($batchSelection['quantity'], $this, 'Sold via ' . $this->stock_out_code);
                        }
                    }
                }

                // Handle serial tracking
                if ($sourceItem->track_serial && isset($serialData[$itemData['id']])) {
                    foreach ($serialData[$itemData['id']] as $serialSelection) {
                        $asset = InventoryAssetInstance::where('item_id', $sourceItem->id)
                            ->where('id', $serialSelection['asset_instance_id'])
                            ->first();
                        
                        if ($asset) {
                            $asset->update([
                                'status' => 'SOLD',
                                'sold_at' => now(),
                                'sold_by' => auth()->id(),
                                'stock_out_id' => $this->id
                            ]);
                        }
                    }
                }

                // Store old values for logging
                $oldStock = $sourceItem->current_stock;
                $oldAvailable = $sourceItem->available_stock;

                // Reduce stock
                $sourceItem->decrement('current_stock', $itemData['quantity']);
                $sourceItem->decrement('available_stock', $itemData['quantity']);

                // Create stock movement record
                $this->createStockMovement($sourceItem, $itemData, $oldStock);

                // Create receipt out
                $this->createReceiptOut($sourceItem, $itemData);
            }

            // Update location utilization
            $this->updateLocationUtilization('from');

            // Update status
            $this->update([
                'status' => 'completed',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'received_by' => auth()->id(),
                'received_at' => now()
            ]);

            // Log the sale completion
            InventoryLogger::log([
                'module' => 'STOCK_OUT',
                'action' => 'SALE_COMPLETE',
                'record_id' => $this->id,
                'new_data' => [
                    'stock_out_code' => $this->stock_out_code,
                    'customer_name' => $this->customer_name,
                    'total_amount' => $this->total_amount,
                    'items' => $this->items,
                    'batch_data' => $this->batch_data,
                    'serial_data' => $this->serial_data,
                    'status' => 'completed'
                ],
                'remarks' => "Sale completed: {$this->stock_out_code}"
            ]);
        });

        return $this;
    }

    /**
     * Process stock out for TRANSFER type
     * Now uses batch_data and serial_data for tracking
     */
    public function processTransfer()
    {
        if ($this->type !== 'transfer') {
            throw new \Exception('This method is only for transfer type stock outs.');
        }

        if ($this->status !== 'approved') {
            throw new \Exception('Stock out must be approved to process transfer.');
        }

        DB::transaction(function () {
            $items = is_array($this->items) ? $this->items : json_decode($this->items, true);
            $batchData = $this->batch_data ?? [];
            $serialData = $this->serial_data ?? [];
            
            foreach ($items as $itemData) {
                $sourceItem = $this->findSourceItem($itemData['id']);
                
                if (!$sourceItem) {
                    throw new \Exception("Item not found in source location: {$itemData['name']}");
                }

                if ($sourceItem->available_stock < $itemData['quantity']) {
                    throw new \Exception(
                        "Insufficient stock for {$sourceItem->item_name}. " .
                        "Available: {$sourceItem->available_stock}, Required: {$itemData['quantity']}"
                    );
                }

                // Handle batch tracking for transfer
                if ($sourceItem->track_batch && isset($batchData[$itemData['id']])) {
                    foreach ($batchData[$itemData['id']] as $batchSelection) {
                        $batch = InventoryItemBatch::where('item_id', $sourceItem->id)
                            ->where('id', $batchSelection['batch_id'])
                            ->first();
                        
                        if ($batch) {
                            $batch->deductStock($batchSelection['quantity'], $this, 'Transferred via ' . $this->stock_out_code);
                        }
                    }
                }

                // Handle serial tracking for transfer
                if ($sourceItem->track_serial && isset($serialData[$itemData['id']])) {
                    foreach ($serialData[$itemData['id']] as $serialSelection) {
                        $asset = InventoryAssetInstance::where('item_id', $sourceItem->id)
                            ->where('id', $serialSelection['asset_instance_id'])
                            ->first();
                        
                        if ($asset) {
                            $asset->update([
                                'status' => 'TRANSFERRED',
                                'transferred_at' => now(),
                                'transfer_id' => $this->id
                            ]);
                        }
                    }
                }

                // Decrease from source
                $sourceItem->decrement('current_stock', $itemData['quantity']);
                $sourceItem->decrement('available_stock', $itemData['quantity']);

                // Add to destination
                $destItem = $this->addToDestination($sourceItem, $itemData);

                // If destination item has batch tracking, transfer batches
                if ($destItem && $destItem->track_batch && isset($batchData[$itemData['id']])) {
                    $this->transferBatchesToDestination($destItem, $batchData[$itemData['id']]);
                }

                // If destination item has serial tracking, transfer serials
                if ($destItem && $destItem->track_serial && isset($serialData[$itemData['id']])) {
                    $this->transferSerialsToDestination($destItem, $serialData[$itemData['id']]);
                }
            }

            // Update utilization for both locations
            $this->updateLocationUtilization('from');
            $this->updateLocationUtilization('to');

            $this->update([
                'status' => 'completed',
                'received_by' => auth()->id(),
                'received_at' => now()
            ]);

            InventoryLogger::log([
                'module' => 'STOCK_OUT',
                'action' => 'TRANSFER_COMPLETE',
                'record_id' => $this->id,
                'new_data' => [
                    'stock_out_code' => $this->stock_out_code,
                    'from_location' => $this->from_location_path,
                    'to_location' => $this->to_location_path,
                    'items' => $this->items,
                    'batch_data' => $this->batch_data,
                    'serial_data' => $this->serial_data,
                ],
                'remarks' => "Transfer completed: {$this->stock_out_code}"
            ]);
        });

        return $this;
    }

    /**
     * Transfer batches to destination item
     */
    private function transferBatchesToDestination($destItem, $batches)
    {
        foreach ($batches as $batchSelection) {
            $batch = InventoryItemBatch::where('item_id', $destItem->id)
                ->where('id', $batchSelection['batch_id'])
                ->first();
            
            if ($batch) {
                $batch->quantity += $batchSelection['quantity'];
                $batch->remaining_quantity += $batchSelection['quantity'];
                $batch->save();
            } else {
                // Create new batch at destination
                InventoryItemBatch::create([
                    'institute_id' => $this->institute_id,
                    'item_id' => $destItem->id,
                    'warehouse_id' => $this->to_warehouse_id,
                    'store_id' => $this->to_store_id,
                    'batch_number' => $batchSelection['batch_number'] ?? 'BATCH-' . strtoupper(uniqid()),
                    'quantity' => $batchSelection['quantity'],
                    'remaining_quantity' => $batchSelection['quantity'],
                    'expiry_date' => $batchSelection['expiry_date'] ?? null,
                    'purchase_price' => $destItem->buying_price ?? 0,
                    'created_by' => auth()->id(),
                ]);
            }
        }
    }

    /**
     * Transfer serials to destination item
     */
    private function transferSerialsToDestination($destItem, $serials)
    {
        foreach ($serials as $serialSelection) {
            InventoryAssetInstance::create([
                'institute_id' => $this->institute_id,
                'item_id' => $destItem->id,
                'warehouse_id' => $this->to_warehouse_id,
                'store_id' => $this->to_store_id,
                'asset_code' => $serialSelection['asset_code'] ?? $destItem->generateAssetCode(),
                'serial_number' => $serialSelection['serial_number'],
                'status' => 'ACTIVE',
                'purchase_date' => now(),
                'created_by' => auth()->id(),
            ]);
        }
    }

    /**
     * Find the source item (either in warehouse or store)
     */
    private function findSourceItem($itemId)
    {
        $query = InventoryItem::where('institute_id', $this->institute_id)
            ->where('id', $itemId);

        if ($this->from_warehouse_id) {
            $query->where('warehouse_id', $this->from_warehouse_id);
        } elseif ($this->from_store_id) {
            $query->where('store_id', $this->from_store_id);
        }

        return $query->first();
    }

    /**
     * Create stock movement record
     */
    private function createStockMovement($sourceItem, $itemData, $oldStock)
    {
        $movement = InventoryStockMovement::create([
            'institute_id' => $this->institute_id,
            'item_id' => $sourceItem->id,
            'warehouse_id' => $this->from_warehouse_id,
            'store_id' => $this->from_store_id,
            'movement_type' => 'OUT',
            'quantity' => $itemData['quantity'],
            'previous_stock' => $oldStock,
            'new_stock' => $sourceItem->current_stock,
            'unit_cost' => $sourceItem->buying_price ?? 0,
            'total_cost' => ($sourceItem->buying_price ?? 0) * $itemData['quantity'],
            'reference_type' => self::class,
            'reference_id' => $this->id,
            'notes' => "Sale: {$this->stock_out_code} - Customer: {$this->customer_name}",
            'created_by' => auth()->id(),
        ]);

        // Log stock movement
        InventoryLogger::log([
            'module' => 'STOCK_MOVEMENT',
            'action' => 'STOCK_OUT',
            'record_id' => $movement->id,
            'new_data' => [
                'item_name' => $sourceItem->item_name,
                'item_code' => $sourceItem->item_code,
                'quantity' => $itemData['quantity'],
                'previous_stock' => $oldStock,
                'new_stock' => $sourceItem->current_stock,
                'reference' => $this->stock_out_code,
                'customer' => $this->customer_name
            ],
            'remarks' => "Stock OUT via sale: {$this->stock_out_code}"
        ]);

        return $movement;
    }

    /**
     * Create receipt out record
     */
    private function createReceiptOut($sourceItem, $itemData)
    {
        $receiptNumber = $this->generateReceiptOutNumber();

        $receiptOut = InventoryReceiptOut::create([
            'institute_id' => $this->institute_id,
            'receipt_number' => $receiptNumber,
            'item_id' => $sourceItem->id,
            'warehouse_id' => $this->from_warehouse_id,
            'store_id' => $this->from_store_id,
            'quantity' => $itemData['quantity'],
            'unit_price' => $itemData['price'] ?? $sourceItem->selling_price ?? 0,
            'total_price' => ($itemData['price'] ?? $sourceItem->selling_price ?? 0) * $itemData['quantity'],
            'receipt_type' => 'SALE',
            'issued_to' => $this->customer_name ?? 'Walk-in Customer',
            'issued_by' => auth()->id(),
            'purpose' => 'Sale',
            'notes' => "Stock Out Reference: {$this->stock_out_code}",
            'status' => 'COMPLETED',
            'created_by' => auth()->id(),
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $this->update(['receipt_out_id' => $receiptOut->id]);

        InventoryLogger::log([
            'module' => 'RECEIPT_OUT',
            'action' => 'CREATE',
            'record_id' => $receiptOut->id,
            'new_data' => $receiptOut->toArray(),
            'remarks' => "Receipt OUT created for sale: {$this->stock_out_code}"
        ]);

        return $receiptOut;
    }

    /**
     * Generate receipt out number
     */
    private function generateReceiptOutNumber()
    {
        $prefix = 'SALE';
        $date = now()->format('Ymd');
        $last = InventoryReceiptOut::where('institute_id', $this->institute_id)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        return $prefix . '-' . $date . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Update location utilization
     */
    private function updateLocationUtilization($type)
    {
        $warehouseId = $type === 'from' ? $this->from_warehouse_id : $this->to_warehouse_id;
        if ($warehouseId) {
            $warehouse = InventoryWarehouse::find($warehouseId);
            if ($warehouse && method_exists($warehouse, 'updateUtilization')) {
                $warehouse->updateUtilization();
            }
        }

        $storeId = $type === 'from' ? $this->from_store_id : $this->to_store_id;
        if ($storeId) {
            $store = InventoryStore::find($storeId);
            if ($store && method_exists($store, 'updateUtilization')) {
                $store->updateUtilization();
            }
        }
    }

    /**
     * Add items to destination location
     */
    private function addToDestination($sourceItem, $itemData)
    {
        if ($this->to_store_id) {
            return $this->createItemInStore($sourceItem, $itemData);
        } elseif ($this->to_warehouse_id) {
            return $this->createItemInWarehouse($sourceItem, $itemData);
        }
        
        throw new \Exception('No destination specified for transfer.');
    }

    /**
     * Create new item in store
     */
    private function createItemInStore($sourceItem, $itemData)
    {
        $destItem = InventoryItem::where('institute_id', $this->institute_id)
            ->where('item_code', $sourceItem->item_code)
            ->where('store_id', $this->to_store_id)
            ->first();

        if ($destItem) {
            $destItem->increment('current_stock', $itemData['quantity']);
            $destItem->increment('available_stock', $itemData['quantity']);
            return $destItem;
        }

        $newItem = $sourceItem->replicate();
        $newItem->item_code = $sourceItem->item_code;
        $newItem->sku = $sourceItem->sku;
        $newItem->store_id = $this->to_store_id;
        $newItem->warehouse_id = null;
        $newItem->current_stock = $itemData['quantity'];
        $newItem->available_stock = $itemData['quantity'];
        $newItem->reserved_stock = 0;
        $newItem->opening_stock = 0;
        $newItem->opening_stock_value = 0;
        $newItem->created_by = auth()->id();
        $newItem->save();

        return $newItem;
    }

    /**
     * Create new item in warehouse
     */
    private function createItemInWarehouse($sourceItem, $itemData)
    {
        $destItem = InventoryItem::where('institute_id', $this->institute_id)
            ->where('item_code', $sourceItem->item_code)
            ->where('warehouse_id', $this->to_warehouse_id)
            ->first();

        if ($destItem) {
            $destItem->increment('current_stock', $itemData['quantity']);
            $destItem->increment('available_stock', $itemData['quantity']);
            return $destItem;
        }

        $newItem = $sourceItem->replicate();
        $newItem->item_code = $sourceItem->item_code;
        $newItem->sku = $sourceItem->sku;
        $newItem->warehouse_id = $this->to_warehouse_id;
        $newItem->store_id = null;
        $newItem->current_stock = $itemData['quantity'];
        $newItem->available_stock = $itemData['quantity'];
        $newItem->reserved_stock = 0;
        $newItem->opening_stock = 0;
        $newItem->opening_stock_value = 0;
        $newItem->created_by = auth()->id();
        $newItem->save();

        return $newItem;
    }
}