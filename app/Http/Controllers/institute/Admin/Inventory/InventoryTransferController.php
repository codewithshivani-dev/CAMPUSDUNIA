<?php

namespace App\Http\Controllers\institute\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\InventoryWarehouseTransfer;
use App\Models\Inventory\InventoryItem;
use App\Models\Inventory\InventoryItemBatch;
use App\Models\Inventory\InventoryAssetInstance;
use App\Models\Inventory\InventoryWarehouse;
use App\Models\Inventory\InventoryStore;
use App\Models\Inventory\InventoryReceiptOut;
use App\Models\Inventory\InventoryReceiptIn;
use App\Models\Inventory\InventoryStockMovement;
use App\Services\Inventory\InventoryLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class InventoryTransferController extends Controller
{
    private function instituteId()
    {
        return auth()->user()->institute_id;
    }

    private function generateTransferCode()
    {
        $prefix = 'TRF';
        $date = now()->format('Ymd');
        $last = InventoryWarehouseTransfer::where('institute_id', $this->instituteId())
            ->whereDate('created_at', now()->toDateString())
            ->count();

        return $prefix . '-' . $date . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }

    public function index()
    {
        $transfers = InventoryWarehouseTransfer::where('institute_id', $this->instituteId())
            ->with(['item', 'fromWarehouse', 'toWarehouse', 'fromStore', 'toStore', 'creator', 'approver', 'receiver', 'receiptOut', 'receiptIn'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => InventoryWarehouseTransfer::where('institute_id', $this->instituteId())->count(),
            'pending' => InventoryWarehouseTransfer::where('institute_id', $this->instituteId())->where('status', 'PENDING')->count(),
            'approved' => InventoryWarehouseTransfer::where('institute_id', $this->instituteId())->where('status', 'APPROVED')->count(),
            'in_transit' => InventoryWarehouseTransfer::where('institute_id', $this->instituteId())->where('status', 'IN_TRANSIT')->count(),
            'completed' => InventoryWarehouseTransfer::where('institute_id', $this->instituteId())->where('status', 'COMPLETED')->count(),
            'cancelled' => InventoryWarehouseTransfer::where('institute_id', $this->instituteId())->where('status', 'CANCELLED')->count(),
        ];

        return view('instituteAdmin.inventory.transfer.index', compact('transfers', 'stats'));
    }

    public function create()
    {
        $items = InventoryItem::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->where('current_stock', '>', 0)
            ->with(['warehouse', 'store', 'category'])
            ->get();

        $warehouses = InventoryWarehouse::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->get();

        $stores = InventoryStore::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->with('warehouse')
            ->get();

        return view('instituteAdmin.inventory.transfer.create', compact('items', 'warehouses', 'stores'));
    }

    public function store(Request $request)
    {
        try {
            Log::info('=== TRANSFER STORE START ===');
            Log::info('Request data:', $request->all());

            // Validate request - with store support
            $validated = $request->validate([
                'item_id' => 'required|exists:inventory_items,id',
                'transfer_type' => 'required|in:warehouse_to_warehouse,store_to_store,warehouse_to_store,store_to_warehouse',
                'from_warehouse_id' => 'nullable|exists:inventory_warehouses,id',
                'from_store_id' => 'nullable|exists:inventory_stores,id',
                'to_warehouse_id' => 'nullable|exists:inventory_warehouses,id',
                'to_store_id' => 'nullable|exists:inventory_stores,id',
                'quantity' => 'required|numeric|min:0.01',
                'expected_arrival_date' => 'nullable|date|after_or_equal:today',
                'reason' => 'nullable|string|max:500',
                'notes' => 'nullable|string|max:1000',
            ]);

            // Validate source and destination based on transfer type (with hierarchy validation)
            $this->validateTransferLocations($request);

            Log::info('Validation passed');

            $transfer = null;

            DB::transaction(function() use ($request, &$transfer) {
                // Get the item
                $item = InventoryItem::where('institute_id', $this->instituteId())
                    ->findOrFail($request->item_id);

                Log::info('Item found:', [
                    'id' => $item->id, 
                    'stock' => $item->available_stock,
                    'warehouse_id' => $item->warehouse_id,
                    'store_id' => $item->store_id
                ]);

                // Check if item is in source location
                $this->validateSourceLocation($item, $request);

                // Check stock availability
                if ($item->available_stock < $request->quantity) {
                    throw new \Exception("Insufficient stock. Available: {$item->available_stock}, Requested: {$request->quantity}");
                }

                // Check destination capacity
                $this->checkDestinationCapacity($request);

                // Get table columns dynamically
                $columns = Schema::getColumnListing('inventory_warehouse_transfers');
                Log::info('Table columns:', $columns);

                // Build data array with store support
                $data = [
                    'institute_id' => $this->instituteId(),
                    'transfer_code' => $this->generateTransferCode(),
                    'item_id' => $request->item_id,
                    'from_warehouse_id' => $request->from_warehouse_id,
                    'from_store_id' => $request->from_store_id,
                    'to_warehouse_id' => $request->to_warehouse_id,
                    'to_store_id' => $request->to_store_id,
                    'quantity' => $request->quantity,
                    'status' => 'PENDING',
                    'expected_arrival_date' => $request->expected_arrival_date,
                    'reason' => $request->reason,
                    'notes' => $request->notes,
                    'transfer_type' => $request->transfer_type,
                ];

                // Only add columns that exist
                if (in_array('transfer_date', $columns)) {
                    $data['transfer_date'] = now();
                }

                if (in_array('created_by', $columns)) {
                    $data['created_by'] = auth()->id();
                }

                Log::info('Data to insert:', $data);

                // Create the transfer
                $transfer = InventoryWarehouseTransfer::create($data);

                // Update location paths
                $transfer->updateLocationPaths();

                Log::info('Transfer created with ID: ' . $transfer->id);

                // Log the action
                if (class_exists(InventoryLogger::class)) {
                    InventoryLogger::log([
                        'module' => 'TRANSFER',
                        'action' => 'CREATE',
                        'record_id' => $transfer->id,
                        'new_data' => $transfer->toArray(),
                        'remarks' => 'Transfer created: ' . $transfer->transfer_code
                    ]);
                }
            });

            Log::info('=== TRANSFER STORE END ===');

            return redirect()
                ->route('inventory.transfers.show', $transfer->id)
                ->with('success', 'Transfer Created Successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', $e->errors());
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Transfer creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Validate transfer locations based on transfer type with HIERARCHY VALIDATION
     */
    private function validateTransferLocations($request)
    {
        $type = $request->transfer_type;

        switch ($type) {
            case 'warehouse_to_warehouse':
                if (!$request->from_warehouse_id || !$request->to_warehouse_id) {
                    throw new \Exception('Both source and destination warehouses are required for warehouse-to-warehouse transfer.');
                }
                if ($request->from_warehouse_id == $request->to_warehouse_id) {
                    throw new \Exception('Source and destination warehouses cannot be the same.');
                }
                // Validate both warehouses exist
                $fromWarehouse = InventoryWarehouse::where('institute_id', $this->instituteId())
                    ->find($request->from_warehouse_id);
                $toWarehouse = InventoryWarehouse::where('institute_id', $this->instituteId())
                    ->find($request->to_warehouse_id);
                if (!$fromWarehouse || !$toWarehouse) {
                    throw new \Exception('One or both warehouses not found.');
                }
                break;

            case 'store_to_store':
                if (!$request->from_store_id || !$request->to_store_id) {
                    throw new \Exception('Both source and destination stores are required for store-to-store transfer.');
                }
                if ($request->from_store_id == $request->to_store_id) {
                    throw new \Exception('Source and destination stores cannot be the same.');
                }
                // ✅ VALIDATE: Both stores must belong to the same warehouse
                $fromStore = InventoryStore::where('institute_id', $this->instituteId())
                    ->where('id', $request->from_store_id)
                    ->first();
                $toStore = InventoryStore::where('institute_id', $this->instituteId())
                    ->where('id', $request->to_store_id)
                    ->first();
                
                if (!$fromStore || !$toStore) {
                    throw new \Exception('One or both stores not found.');
                }
                
                if ($fromStore->warehouse_id != $toStore->warehouse_id) {
                    throw new \Exception(
                        'Store-to-store transfers require both stores to belong to the same warehouse. ' .
                        "Source store belongs to warehouse ID: {$fromStore->warehouse_id}, " .
                        "Destination store belongs to warehouse ID: {$toStore->warehouse_id}"
                    );
                }
                break;

            case 'warehouse_to_store':
                if (!$request->from_warehouse_id || !$request->to_store_id) {
                    throw new \Exception('Source warehouse and destination store are required.');
                }
                // ✅ VALIDATE: Destination store must belong to the source warehouse
                $toStore = InventoryStore::where('institute_id', $this->instituteId())
                    ->where('id', $request->to_store_id)
                    ->first();
                
                if (!$toStore) {
                    throw new \Exception('Destination store not found.');
                }
                
                if ($toStore->warehouse_id != $request->from_warehouse_id) {
                    throw new \Exception(
                        'Destination store must belong to the source warehouse. ' .
                        "Store belongs to warehouse ID: {$toStore->warehouse_id}, " .
                        "Source warehouse ID: {$request->from_warehouse_id}"
                    );
                }
                break;

            case 'store_to_warehouse':
                if (!$request->from_store_id || !$request->to_warehouse_id) {
                    throw new \Exception('Source store and destination warehouse are required.');
                }
                // ✅ VALIDATE: Source store must belong to the destination warehouse
                $fromStore = InventoryStore::where('institute_id', $this->instituteId())
                    ->where('id', $request->from_store_id)
                    ->first();
                
                if (!$fromStore) {
                    throw new \Exception('Source store not found.');
                }
                
                if ($fromStore->warehouse_id != $request->to_warehouse_id) {
                    throw new \Exception(
                        'Source store must belong to the destination warehouse. ' .
                        "Store belongs to warehouse ID: {$fromStore->warehouse_id}, " .
                        "Destination warehouse ID: {$request->to_warehouse_id}"
                    );
                }
                break;

            default:
                throw new \Exception('Invalid transfer type.');
        }
    }

    /**
     * Validate source location matches item's location
     */
    private function validateSourceLocation($item, $request)
    {
        $type = $request->transfer_type;

        switch ($type) {
            case 'warehouse_to_warehouse':
            case 'warehouse_to_store':
                if ($item->warehouse_id != $request->from_warehouse_id) {
                    throw new \Exception('Item is not available in the selected source warehouse.');
                }
                break;

            case 'store_to_store':
            case 'store_to_warehouse':
                if ($item->store_id != $request->from_store_id) {
                    throw new \Exception('Item is not available in the selected source store.');
                }
                break;
        }
    }

    /**
     * Check destination capacity
     */
    private function checkDestinationCapacity($request)
    {
        $type = $request->transfer_type;
        $quantity = $request->quantity;

        if (in_array($type, ['warehouse_to_warehouse', 'store_to_warehouse'])) {
            $warehouse = InventoryWarehouse::where('institute_id', $this->instituteId())
                ->find($request->to_warehouse_id);
            if ($warehouse && $warehouse->capacity) {
                $currentUtilization = InventoryItem::where('institute_id', $this->instituteId())
                    ->where('warehouse_id', $request->to_warehouse_id)
                    ->sum('current_stock') ?? 0;
                if (($currentUtilization + $quantity) > $warehouse->capacity) {
                    throw new \Exception(
                        "Destination warehouse capacity exceeded. " .
                        "Current: {$currentUtilization}, Required: {$quantity}, Capacity: {$warehouse->capacity}"
                    );
                }
            }
        }

        if (in_array($type, ['store_to_store', 'warehouse_to_store'])) {
            $store = InventoryStore::where('institute_id', $this->instituteId())
                ->find($request->to_store_id);
            if ($store && $store->capacity) {
                $currentUtilization = InventoryItem::where('institute_id', $this->instituteId())
                    ->where('store_id', $request->to_store_id)
                    ->sum('current_stock') ?? 0;
                if (($currentUtilization + $quantity) > $store->capacity) {
                    throw new \Exception(
                        "Destination store capacity exceeded. " .
                        "Current: {$currentUtilization}, Required: {$quantity}, Capacity: {$store->capacity}"
                    );
                }
            }
        }
    }

    public function show($id)
    {
        $transfer = InventoryWarehouseTransfer::where('institute_id', $this->instituteId())
            ->with([
                'item' => function($query) {
                    $query->with(['category', 'subcategory', 'warehouse', 'store']);
                },
                'fromWarehouse',
                'toWarehouse',
                'fromStore',
                'toStore',
                'creator',
                'approver',
                'receiver',
                'receiptOut' => function($query) {
                    $query->with(['item', 'warehouse', 'creator', 'approver']);
                },
                'receiptIn' => function($query) {
                    $query->with(['item', 'warehouse', 'creator', 'approver']);
                }
            ])
            ->findOrFail($id);

        return view('instituteAdmin.inventory.transfer.show', compact('transfer'));
    }

    public function edit($id)
    {
        $transfer = InventoryWarehouseTransfer::where('institute_id', $this->instituteId())
            ->where('status', 'PENDING')
            ->findOrFail($id);

        $items = InventoryItem::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->where('current_stock', '>', 0)
            ->with(['warehouse', 'store', 'category'])
            ->get();

        $warehouses = InventoryWarehouse::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->get();

        $stores = InventoryStore::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->with('warehouse')
            ->get();

        return view('instituteAdmin.inventory.transfer.edit', compact('transfer', 'items', 'warehouses', 'stores'));
    }

    public function update(Request $request, $id)
    {
        try {
            $transfer = InventoryWarehouseTransfer::where('institute_id', $this->instituteId())
                ->where('status', 'PENDING')
                ->findOrFail($id);

            $request->validate([
                'item_id' => 'required|exists:inventory_items,id',
                'transfer_type' => 'required|in:warehouse_to_warehouse,store_to_store,warehouse_to_store,store_to_warehouse',
                'from_warehouse_id' => 'nullable|exists:inventory_warehouses,id',
                'from_store_id' => 'nullable|exists:inventory_stores,id',
                'to_warehouse_id' => 'nullable|exists:inventory_warehouses,id',
                'to_store_id' => 'nullable|exists:inventory_stores,id',
                'quantity' => 'required|numeric|min:0.01',
                'expected_arrival_date' => 'nullable|date|after_or_equal:today',
                'reason' => 'nullable|string|max:500',
                'notes' => 'nullable|string|max:1000',
            ]);

            // Validate transfer locations with hierarchy validation
            $this->validateTransferLocations($request);

            DB::transaction(function() use ($request, $transfer) {
                $item = InventoryItem::where('institute_id', $this->instituteId())->findOrFail($request->item_id);

                // Validate source location
                $this->validateSourceLocation($item, $request);

                if ($item->available_stock < $request->quantity) {
                    throw new \Exception("Insufficient stock. Available: {$item->available_stock}, Requested: {$request->quantity}");
                }

                // Check destination capacity
                $this->checkDestinationCapacity($request);

                $oldData = $transfer->toArray();

                $transfer->update([
                    'item_id' => $request->item_id,
                    'from_warehouse_id' => $request->from_warehouse_id,
                    'from_store_id' => $request->from_store_id,
                    'to_warehouse_id' => $request->to_warehouse_id,
                    'to_store_id' => $request->to_store_id,
                    'quantity' => $request->quantity,
                    'expected_arrival_date' => $request->expected_arrival_date,
                    'reason' => $request->reason,
                    'notes' => $request->notes,
                    'transfer_type' => $request->transfer_type,
                ]);

                // Update location paths
                $transfer->updateLocationPaths();

                if (class_exists(InventoryLogger::class)) {
                    InventoryLogger::log([
                        'module' => 'TRANSFER',
                        'action' => 'UPDATE',
                        'record_id' => $transfer->id,
                        'old_data' => $oldData,
                        'new_data' => $transfer->fresh()->toArray(),
                        'remarks' => 'Transfer updated: ' . $transfer->transfer_code
                    ]);
                }
            });

            return redirect()
                ->route('inventory.transfers.show', $transfer->id)
                ->with('success', 'Transfer Updated Successfully');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function approve($id)
    {
        try {
            Log::info('=== APPROVE TRANSFER ===');
            
            $transfer = InventoryWarehouseTransfer::where('institute_id', $this->instituteId())
                ->where('status', 'PENDING')
                ->findOrFail($id);

            $item = InventoryItem::where('institute_id', $this->instituteId())->findOrFail($transfer->item_id);

            // Check stock based on source location
            $availableStock = $this->getAvailableStock($item, $transfer);
            if ($availableStock < $transfer->quantity) {
                return back()->with('error', "Insufficient stock. Available: {$availableStock}, Required: {$transfer->quantity}");
            }

            // ✅ Check destination capacity with full validation
            $this->checkTransferDestinationCapacity($transfer);

            $transfer->update([
                'status' => 'APPROVED',
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]);

            // Update location paths
            $transfer->updateLocationPaths();

            if (class_exists(InventoryLogger::class)) {
                InventoryLogger::log([
                    'module' => 'TRANSFER',
                    'action' => 'APPROVE',
                    'record_id' => $transfer->id,
                    'new_data' => [
                        'transfer_code' => $transfer->transfer_code,
                        'from_location' => $transfer->source_location_path,
                        'to_location' => $transfer->destination_location_path,
                        'quantity' => $transfer->quantity,
                    ],
                    'remarks' => 'Transfer approved: ' . $transfer->transfer_code
                ]);
            }

            Log::info('Transfer approved successfully');

            return redirect()
                ->route('inventory.transfers.show', $transfer->id)
                ->with('success', 'Transfer approved successfully.');

        } catch (\Exception $e) {
            Log::error('Approve failed:', ['error' => $e->getMessage()]);
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Get available stock based on source location
     */
    private function getAvailableStock($item, $transfer)
    {
        // If source is a store, check store stock
        if ($transfer->from_store_id) {
            $storeItem = InventoryItem::where('institute_id', $this->instituteId())
                ->where('item_code', $item->item_code)
                ->where('store_id', $transfer->from_store_id)
                ->first();
            return $storeItem ? $storeItem->available_stock : 0;
        }

        // If source is a warehouse, check warehouse stock
        if ($transfer->from_warehouse_id) {
            $warehouseItem = InventoryItem::where('institute_id', $this->instituteId())
                ->where('item_code', $item->item_code)
                ->where('warehouse_id', $transfer->from_warehouse_id)
                ->first();
            return $warehouseItem ? $warehouseItem->available_stock : 0;
        }

        return $item->available_stock;
    }

    /**
     * Check destination capacity for transfer
     */
    private function checkTransferDestinationCapacity($transfer)
    {
        $quantity = $transfer->quantity;

        // Check warehouse destination
        if ($transfer->to_warehouse_id) {
            $warehouse = InventoryWarehouse::where('institute_id', $this->instituteId())
                ->find($transfer->to_warehouse_id);
            if ($warehouse && $warehouse->capacity) {
                $currentUtilization = InventoryItem::where('institute_id', $this->instituteId())
                    ->where('warehouse_id', $transfer->to_warehouse_id)
                    ->sum('current_stock') ?? 0;
                if (($currentUtilization + $quantity) > $warehouse->capacity) {
                    throw new \Exception(
                        "Destination warehouse capacity exceeded. " .
                        "Current: {$currentUtilization}, Required: {$quantity}, Capacity: {$warehouse->capacity}"
                    );
                }
            }
        }

        // Check store destination
        if ($transfer->to_store_id) {
            $store = InventoryStore::where('institute_id', $this->instituteId())
                ->find($transfer->to_store_id);
            if ($store && $store->capacity) {
                $currentUtilization = InventoryItem::where('institute_id', $this->instituteId())
                    ->where('store_id', $transfer->to_store_id)
                    ->sum('current_stock') ?? 0;
                if (($currentUtilization + $quantity) > $store->capacity) {
                    throw new \Exception(
                        "Destination store capacity exceeded. " .
                        "Current: {$currentUtilization}, Required: {$quantity}, Capacity: {$store->capacity}"
                    );
                }
            }
        }
    }

    /**
     * start() method - With BATCH and ASSET transfer support
     */
    public function start($id)
    {
        try {
            Log::info('=== START TRANSFER PROCESS ===');
            
            $transfer = InventoryWarehouseTransfer::where('institute_id', $this->instituteId())
                ->where('status', 'APPROVED')
                ->findOrFail($id);
                
            Log::info('Transfer found:', [
                'id' => $transfer->id,
                'status' => $transfer->status,
                'code' => $transfer->transfer_code,
                'quantity' => $transfer->quantity
            ]);

            DB::transaction(function() use ($transfer) {
                // Get the item from source location
                $item = $this->getSourceItem($transfer);
                    
                if (!$item) {
                    throw new \Exception('Source item not found');
                }
                
                Log::info('Source item found:', [
                    'id' => $item->id,
                    'current_stock' => $item->current_stock,
                    'available_stock' => $item->available_stock,
                    'warehouse_id' => $item->warehouse_id,
                    'store_id' => $item->store_id,
                    'item_code' => $item->item_code,
                    'sku' => $item->sku,
                    'track_batch' => $item->track_batch,
                    'item_type' => $item->item_type
                ]);

                // Check if there's enough stock
                if ($item->current_stock < $transfer->quantity) {
                    throw new \Exception("Insufficient stock in source location. Available: {$item->current_stock}, Required: {$transfer->quantity}");
                }

                // ✅ 1. HANDLE BATCH TRANSFER (if batch tracking enabled)
                $batchData = null;
                if ($item->track_batch) {
                    $batchData = $this->transferBatches($item, $transfer);
                }

                // ✅ 2. HANDLE ASSET INSTANCE TRANSFER (if item type is ASSET)
                $assetData = null;
                if ($item->item_type === 'ASSET') {
                    $assetData = $this->transferAssetInstances($item, $transfer);
                }

                // 3. DECREASE stock from source location
                $oldStock = $item->current_stock;
                $item->decrement('current_stock', $transfer->quantity);
                $item->decrement('available_stock', $transfer->quantity);
                
                Log::info('Source stock decreased');

                // 4. Create stock movement for OUT
                $this->createStockMovement($item, $transfer, 'OUT', $oldStock);

                // 5. Find or create item in destination location
                $destItem = $this->findOrCreateDestinationItem($item, $transfer);

                // 6. Create stock movement for IN
                $this->createStockMovement($destItem, $transfer, 'IN', $destItem->current_stock - $transfer->quantity);

                // 7. Update warehouse/store utilization
                $this->updateLocationUtilization($transfer);

                // 8. Save batch and asset data to transfer record
                $updateData = [
                    'status' => 'COMPLETED',
                    'received_date' => now(),
                    'from_location_path' => $transfer->source_location_path,
                    'to_location_path' => $transfer->destination_location_path,
                ];
                
                if ($batchData) {
                    $updateData['batch_details'] = $batchData;
                }
                if ($assetData) {
                    $updateData['asset_details'] = $assetData;
                }
                
                $columns = Schema::getColumnListing('inventory_warehouse_transfers');
                if (in_array('received_by', $columns)) {
                    $updateData['received_by'] = auth()->id();
                }
                
                $transfer->update($updateData);
                
                Log::info('Transfer status updated to COMPLETED');

                // 9. Log the action
                if (class_exists(InventoryLogger::class)) {
                    InventoryLogger::log([
                        'module' => 'TRANSFER',
                        'action' => 'COMPLETE',
                        'record_id' => $transfer->id,
                        'new_data' => [
                            'transfer_code' => $transfer->transfer_code,
                            'from_location' => $transfer->source_location_path,
                            'to_location' => $transfer->destination_location_path,
                            'quantity' => $transfer->quantity,
                            'item_name' => $item->item_name ?? 'N/A',
                            'batch_transferred' => !empty($batchData),
                            'assets_transferred' => !empty($assetData),
                        ],
                        'remarks' => 'Transfer completed: ' . $transfer->transfer_code
                    ]);
                }
            });

            Log::info('=== TRANSFER COMPLETED SUCCESSFULLY ===');
            
            return redirect()
                ->route('inventory.transfers.show', $transfer->id)
                ->with('success', 'Transfer completed successfully. Stock has been moved.');

        } catch (\Exception $e) {
            Log::error('Transfer start failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->route('inventory.transfers.show', $id)
                ->with('error', 'Failed to start transfer: ' . $e->getMessage());
        }
    }

    /**
     * Transfer batches from source to destination
     */
    private function transferBatches($sourceItem, $transfer)
    {
        Log::info('Transferring batches for item:', [
            'source_item_id' => $sourceItem->id,
            'quantity' => $transfer->quantity,
            'source_item_code' => $sourceItem->item_code
        ]);

        // Get batches using FEFO (First Expiry First Out)
        $batches = $sourceItem->batches()
            ->where('remaining_quantity', '>', 0)
            ->orderBy('expiry_date', 'asc')
            ->get();

        $remainingQty = $transfer->quantity;
        $transferredBatches = [];

        foreach ($batches as $batch) {
            if ($remainingQty <= 0) break;

            $takeQty = min($batch->remaining_quantity, $remainingQty);

            // Store batch data before modification
            $batchData = [
                'id' => $batch->id,
                'batch_number' => $batch->batch_number,
                'quantity' => $takeQty,
                'remaining_before' => $batch->remaining_quantity,
                'expiry_date' => $batch->expiry_date,
                'manufacturing_date' => $batch->manufacturing_date,
                'purchase_price' => $batch->purchase_price,
            ];

            // Deduct from source batch
            $batch->deductStock($takeQty, $transfer, 'Transferred to destination');

            // Create stock movement for batch transfer OUT
            $this->createStockMovementForBatch($batch, $takeQty, $transfer, 'OUT');

            // Find or create destination batch
            $destBatch = $this->findOrCreateDestinationBatch($batch, $sourceItem, $transfer, $takeQty);

            if ($destBatch) {
                // Create stock movement for batch transfer IN
                $this->createStockMovementForBatch($destBatch, $takeQty, $transfer, 'IN');
            }

            $transferredBatches[] = array_merge($batchData, [
                'destination_batch_id' => $destBatch ? $destBatch->id : null,
            ]);

            $remainingQty -= $takeQty;
        }

        // If there's remaining quantity that couldn't be fulfilled by batches,
        // create a new batch for the remaining quantity
        if ($remainingQty > 0) {
            Log::warning('Not enough batches to cover quantity, creating new batch for remainder', [
                'remaining' => $remainingQty,
                'item_id' => $sourceItem->id
            ]);

            $newBatch = InventoryItemBatch::create([
                'institute_id' => $this->instituteId(),
                'item_id' => $sourceItem->id,
                'warehouse_id' => $transfer->to_warehouse_id,
                'store_id' => $transfer->to_store_id,
                'batch_number' => 'BATCH-' . strtoupper(uniqid()),
                'quantity' => $remainingQty,
                'remaining_quantity' => $remainingQty,
                'purchase_price' => $sourceItem->buying_price ?? 0,
                'created_by' => auth()->id(),
            ]);

            // Create stock movement for new batch
            $this->createStockMovementForBatch($newBatch, $remainingQty, $transfer, 'IN');

            $transferredBatches[] = [
                'is_new' => true,
                'batch_number' => $newBatch->batch_number,
                'quantity' => $remainingQty,
                'destination_batch_id' => $newBatch->id,
            ];
        }

        Log::info('Batch transfer completed', [
            'transferred_batches' => count($transferredBatches),
            'total_quantity' => $transfer->quantity
        ]);

        return $transferredBatches;
    }

    /**
     * Find or create destination batch
     */
    private function findOrCreateDestinationBatch($sourceBatch, $sourceItem, $transfer, $quantity)
    {
        // Check if destination already has this batch number
        $existingBatch = InventoryItemBatch::where('institute_id', $this->instituteId())
            ->where('item_id', $sourceItem->id)  // The item code is the same, so item_id will be found
            ->where('batch_number', $sourceBatch->batch_number)
            ->where('warehouse_id', $transfer->to_warehouse_id)
            ->where('store_id', $transfer->to_store_id)
            ->first();

        if ($existingBatch) {
            // Batch already exists, just add to it
            $existingBatch->quantity += $quantity;
            $existingBatch->remaining_quantity += $quantity;
            $existingBatch->save();

            Log::info('Updated existing batch at destination', [
                'batch_number' => $sourceBatch->batch_number,
                'added_quantity' => $quantity
            ]);

            return $existingBatch;
        }

        // Create new batch at destination
        $newBatch = $sourceBatch->replicate();
        $newBatch->item_id = $sourceItem->id;
        $newBatch->warehouse_id = $transfer->to_warehouse_id;
        $newBatch->store_id = $transfer->to_store_id;
        $newBatch->quantity = $quantity;
        $newBatch->remaining_quantity = $quantity;
        $newBatch->created_by = auth()->id();
        $newBatch->save();

        Log::info('Created new batch at destination', [
            'batch_number' => $newBatch->batch_number,
            'quantity' => $quantity
        ]);

        return $newBatch;
    }

    /**
     * Transfer asset instances from source to destination
     */
    private function transferAssetInstances($sourceItem, $transfer)
    {
        Log::info('Transferring asset instances for item:', [
            'source_item_id' => $sourceItem->id,
            'quantity' => $transfer->quantity,
            'source_item_code' => $sourceItem->item_code
        ]);

        // Get active asset instances from source
        $assets = $sourceItem->assetInstances()
            ->where('status', 'ACTIVE')
            ->limit($transfer->quantity)
            ->get();

        $transferredAssets = [];
        $transferredCount = 0;

        foreach ($assets as $asset) {
            // Mark source asset as TRANSFERRED
            $asset->update([
                'status' => 'TRANSFERRED',
                'transfer_id' => $transfer->id,
                'updated_by' => auth()->id()
            ]);

            // Create asset at destination
            $newAsset = $asset->replicate();
            $newAsset->item_id = $sourceItem->id;
            $newAsset->warehouse_id = $transfer->to_warehouse_id;
            $newAsset->store_id = $transfer->to_store_id;
            $newAsset->asset_code = $asset->asset_code;
            $newAsset->created_by = auth()->id();
            $newAsset->save();

            // Create stock movement for asset transfer
            $this->createStockMovementForAsset($asset, $transfer, 'OUT');
            $this->createStockMovementForAsset($newAsset, $transfer, 'IN');

            $transferredAssets[] = [
                'source_asset_id' => $asset->id,
                'destination_asset_id' => $newAsset->id,
                'asset_code' => $asset->asset_code,
                'serial_number' => $asset->serial_number,
            ];

            $transferredCount++;
        }

        // If not enough assets, log warning
        if ($transferredCount < $transfer->quantity) {
            Log::warning('Not enough active assets for transfer', [
                'requested' => $transfer->quantity,
                'transferred' => $transferredCount,
                'item_id' => $sourceItem->id
            ]);

            InventoryLogger::log([
                'module' => 'ASSET',
                'action' => 'TRANSFER_WARNING',
                'record_id' => $sourceItem->id,
                'new_data' => [
                    'source_item_id' => $sourceItem->id,
                    'dest_item_id' => $sourceItem->id,
                    'requested' => $transfer->quantity,
                    'transferred' => $transferredCount,
                    'transfer_id' => $transfer->id
                ],
                'remarks' => "Not enough active assets for transfer. Requested: {$transfer->quantity}, Transferred: {$transferredCount}"
            ]);
        }

        Log::info('Asset transfer completed', [
            'transferred_assets' => $transferredCount,
            'total_quantity' => $transfer->quantity
        ]);

        return $transferredAssets;
    }

    /**
     * Create stock movement for batch
     */
    private function createStockMovementForBatch($batch, $quantity, $transfer, $movementType)
    {
        $movement = InventoryStockMovement::create([
            'institute_id' => $this->instituteId(),
            'item_id' => $batch->item_id,
            'warehouse_id' => $movementType === 'OUT' ? $transfer->from_warehouse_id : $transfer->to_warehouse_id,
            'store_id' => $movementType === 'OUT' ? $transfer->from_store_id : $transfer->to_store_id,
            'batch_id' => $batch->id,
            'movement_type' => $movementType,
            'quantity' => $quantity,
            'previous_stock' => $batch->remaining_quantity + $quantity,
            'new_stock' => $batch->remaining_quantity,
            'unit_cost' => $batch->purchase_price ?? 0,
            'total_cost' => ($batch->purchase_price ?? 0) * $quantity,
            'reference_type' => InventoryWarehouseTransfer::class,
            'reference_id' => $transfer->id,
            'notes' => "Batch transfer: {$batch->batch_number}",
            'created_by' => auth()->id(),
        ]);

        Log::info('Batch stock movement created', [
            'movement_id' => $movement->id,
            'batch_id' => $batch->id,
            'batch_number' => $batch->batch_number,
            'quantity' => $quantity,
            'type' => $movementType
        ]);

        return $movement;
    }

    /**
     * Create stock movement for asset
     */
    private function createStockMovementForAsset($asset, $transfer, $movementType)
    {
        $movement = InventoryStockMovement::create([
            'institute_id' => $this->instituteId(),
            'item_id' => $asset->item_id,
            'warehouse_id' => $movementType === 'OUT' ? $transfer->from_warehouse_id : $transfer->to_warehouse_id,
            'store_id' => $movementType === 'OUT' ? $transfer->from_store_id : $transfer->to_store_id,
            'asset_instance_id' => $asset->id,
            'movement_type' => $movementType,
            'quantity' => 1,
            'previous_stock' => 1,
            'new_stock' => 1,
            'unit_cost' => $asset->buying_price ?? 0,
            'total_cost' => $asset->buying_price ?? 0,
            'reference_type' => InventoryWarehouseTransfer::class,
            'reference_id' => $transfer->id,
            'notes' => "Asset transfer: {$asset->asset_code}",
            'created_by' => auth()->id(),
        ]);

        Log::info('Asset stock movement created', [
            'movement_id' => $movement->id,
            'asset_id' => $asset->id,
            'asset_code' => $asset->asset_code,
            'type' => $movementType
        ]);

        return $movement;
    }

    /**
     * Create stock movement for item
     */
    private function createStockMovement($item, $transfer, $movementType, $previousStock)
    {
        $movement = InventoryStockMovement::create([
            'institute_id' => $this->instituteId(),
            'item_id' => $item->id,
            'warehouse_id' => $movementType === 'OUT' ? $transfer->from_warehouse_id : $transfer->to_warehouse_id,
            'store_id' => $movementType === 'OUT' ? $transfer->from_store_id : $transfer->to_store_id,
            'movement_type' => $movementType,
            'quantity' => $transfer->quantity,
            'previous_stock' => $previousStock,
            'new_stock' => $item->current_stock,
            'unit_cost' => $item->buying_price ?? 0,
            'total_cost' => ($item->buying_price ?? 0) * $transfer->quantity,
            'reference_type' => InventoryWarehouseTransfer::class,
            'reference_id' => $transfer->id,
            'notes' => "Transfer: {$transfer->transfer_code}",
            'created_by' => auth()->id(),
        ]);

        Log::info('Item stock movement created', [
            'movement_id' => $movement->id,
            'item_id' => $item->id,
            'item_name' => $item->item_name,
            'quantity' => $transfer->quantity,
            'type' => $movementType
        ]);

        return $movement;
    }

    /**
     * Get source item from the correct location
     */
    private function getSourceItem($transfer)
    {
        $query = InventoryItem::where('institute_id', $this->instituteId())
            ->where('id', $transfer->item_id);

        if ($transfer->from_store_id) {
            $query->where('store_id', $transfer->from_store_id);
        } elseif ($transfer->from_warehouse_id) {
            $query->where('warehouse_id', $transfer->from_warehouse_id);
        }

        return $query->first();
    }

    /**
     * Find or create destination item - KEEPS SAME CODES
     */
    private function findOrCreateDestinationItem($sourceItem, $transfer)
    {
        $query = InventoryItem::where('institute_id', $this->instituteId())
            ->where('item_code', $sourceItem->item_code);

        if ($transfer->to_store_id) {
            $query->where('store_id', $transfer->to_store_id);
        } elseif ($transfer->to_warehouse_id) {
            $query->where('warehouse_id', $transfer->to_warehouse_id);
        }

        $destItem = $query->first();

        if ($destItem) {
            // Update existing item
            $destItem->increment('current_stock', $transfer->quantity);
            $destItem->increment('available_stock', $transfer->quantity);
            Log::info('Destination item updated:', ['id' => $destItem->id]);
            return $destItem;
        }

        // Create new item - KEEP SAME CODES
        $newItem = $sourceItem->replicate();
        
        // KEEP the SAME item_code, SKU, and barcode
        $newItem->item_code = $sourceItem->item_code;
        $newItem->sku = $sourceItem->sku;
        $newItem->barcode = $sourceItem->barcode;
        
        // Set location
        $newItem->warehouse_id = $transfer->to_warehouse_id;
        $newItem->store_id = $transfer->to_store_id;
        
        // Reset stock values for new location
        $newItem->current_stock = $transfer->quantity;
        $newItem->available_stock = $transfer->quantity;
        $newItem->reserved_stock = 0;
        $newItem->opening_stock = 0;
        $newItem->opening_stock_value = 0;
        $newItem->created_by = auth()->id();
        $newItem->updated_by = auth()->id();
        $newItem->save();
        
        Log::info('New destination item created (same codes):', [
            'id' => $newItem->id,
            'item_code' => $newItem->item_code,
            'sku' => $newItem->sku
        ]);

        return $newItem;
    }

    /**
     * Update utilization for both source and destination locations
     */
    private function updateLocationUtilization($transfer)
    {
        // Update source warehouse
        if ($transfer->from_warehouse_id) {
            $warehouse = InventoryWarehouse::find($transfer->from_warehouse_id);
            if ($warehouse) {
                $warehouse->updateUtilization();
            }
        }

        // Update source store
        if ($transfer->from_store_id) {
            $store = InventoryStore::find($transfer->from_store_id);
            if ($store) {
                $store->updateUtilization();
            }
        }

        // Update destination warehouse
        if ($transfer->to_warehouse_id) {
            $warehouse = InventoryWarehouse::find($transfer->to_warehouse_id);
            if ($warehouse) {
                $warehouse->updateUtilization();
            }
        }

        // Update destination store
        if ($transfer->to_store_id) {
            $store = InventoryStore::find($transfer->to_store_id);
            if ($store) {
                $store->updateUtilization();
            }
        }
    }

    public function cancel($id)
    {
        try {
            Log::info('=== CANCEL TRANSFER ===');
            
            $transfer = InventoryWarehouseTransfer::where('institute_id', $this->instituteId())
                ->whereIn('status', ['PENDING', 'APPROVED'])
                ->findOrFail($id);

            $transfer->update([
                'status' => 'CANCELLED'
            ]);

            if (class_exists(InventoryLogger::class)) {
                InventoryLogger::log([
                    'module' => 'TRANSFER',
                    'action' => 'CANCEL',
                    'record_id' => $transfer->id,
                    'remarks' => 'Transfer cancelled: ' . $transfer->transfer_code
                ]);
            }

            Log::info('Transfer cancelled successfully');

            return redirect()
                ->route('inventory.transfers.index')
                ->with('success', 'Transfer cancelled successfully.');

        } catch (\Exception $e) {
            Log::error('Cancel failed:', ['error' => $e->getMessage()]);
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $transfer = InventoryWarehouseTransfer::where('institute_id', $this->instituteId())
                ->where('status', 'PENDING')
                ->findOrFail($id);

            $oldData = $transfer->toArray();

            if (class_exists(InventoryLogger::class)) {
                InventoryLogger::log([
                    'module' => 'TRANSFER',
                    'action' => 'DELETE',
                    'record_id' => $transfer->id,
                    'old_data' => $oldData,
                    'remarks' => 'Transfer deleted: ' . $transfer->transfer_code
                ]);
            }

            $transfer->delete();

            return redirect()
                ->route('inventory.transfers.index')
                ->with('success', 'Transfer Deleted Successfully');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function print($id)
    {
        $transfer = InventoryWarehouseTransfer::where('institute_id', $this->instituteId())
            ->with([
                'item' => function($query) {
                    $query->with(['category', 'subcategory', 'warehouse', 'store']);
                },
                'fromWarehouse',
                'toWarehouse',
                'fromStore',
                'toStore',
                'creator',
                'approver',
                'receiver',
                'receiptOut' => function($query) {
                    $query->with(['item', 'warehouse', 'creator', 'approver']);
                },
                'receiptIn' => function($query) {
                    $query->with(['item', 'warehouse', 'creator', 'approver']);
                }
            ])
            ->findOrFail($id);

        // Increment print count
        $transfer->incrementPrintCount();

        if (class_exists(InventoryLogger::class)) {
            InventoryLogger::log([
                'module' => 'TRANSFER',
                'action' => 'PRINT',
                'record_id' => $transfer->id,
                'remarks' => 'Transfer printed: ' . $transfer->transfer_code
            ]);
        }

        return view('instituteAdmin.inventory.transfer.print', compact('transfer'));
    }

    public function getItemDetails($id)
    {
        $item = InventoryItem::where('institute_id', $this->instituteId())
            ->with(['warehouse', 'store', 'category'])
            ->findOrFail($id);

        return response()->json([
            'id' => $item->id,
            'name' => $item->item_name,
            'code' => $item->item_code,
            'current_stock' => $item->current_stock,
            'available_stock' => $item->available_stock,
            'warehouse_id' => $item->warehouse_id,
            'warehouse_name' => $item->warehouse->warehouse_name ?? null,
            'store_id' => $item->store_id,
            'store_name' => $item->store->store_name ?? null,
        ]);
    }
}