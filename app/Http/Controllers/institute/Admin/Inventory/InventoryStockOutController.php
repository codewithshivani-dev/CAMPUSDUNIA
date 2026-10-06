<?php
// app/Http/Controllers/institute/Admin/Inventory/InventoryStockOutController.php

namespace App\Http\Controllers\institute\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\InventoryStockOut;
use App\Models\Inventory\InventoryItem;
use App\Models\Inventory\InventoryItemBatch;
use App\Models\Inventory\InventoryAssetInstance;
use App\Models\Inventory\InventoryWarehouse;
use App\Models\Inventory\InventoryStore;
use App\Models\Inventory\InventoryCategory;
use App\Models\Inventory\InventoryStockMovement;
use App\Models\Inventory\InventoryReceiptIn;
use App\Models\Inventory\InventoryReceiptOut;
use App\Models\Inventory\InventoryConfiguration;
use App\Services\Inventory\InventoryLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class InventoryStockOutController extends Controller
{
    private function instituteId()
    {
        return (string) auth()->user()->institute_id;
    }

    private function generateStockOutCode($type)
    {
        $prefix = $type === 'sell' ? 'SALE' : 'TRF';
        $date = now()->format('Ymd');
        $last = InventoryStockOut::where('institute_id', $this->instituteId())
            ->where('type', $type)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        return $prefix . '-' . $date . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get active inventory configuration
     */
    private function getActiveConfiguration()
    {
        return InventoryConfiguration::where('institute_id', $this->instituteId())
            ->where('is_configured', 1)
            ->where('status', 'active')
            ->first();
    }

    /**
     * Validate transfer hierarchy
     */
    private function validateTransferHierarchy($subType, $fromWarehouseId, $fromStoreId, $toWarehouseId, $toStoreId)
    {
        switch ($subType) {
            case 'warehouse_to_warehouse':
                if (!$fromWarehouseId || !$toWarehouseId) {
                    throw new \Exception('Both source and destination warehouses are required.');
                }
                if ($fromWarehouseId == $toWarehouseId) {
                    throw new \Exception('Source and destination warehouses cannot be the same.');
                }
                break;

            case 'store_to_store':
                if (!$fromStoreId || !$toStoreId) {
                    throw new \Exception('Both source and destination stores are required.');
                }
                if ($fromStoreId == $toStoreId) {
                    throw new \Exception('Source and destination stores cannot be the same.');
                }
                // Validate both stores belong to the same warehouse
                $fromStore = InventoryStore::where('institute_id', $this->instituteId())
                    ->where('id', $fromStoreId)
                    ->first();
                $toStore = InventoryStore::where('institute_id', $this->instituteId())
                    ->where('id', $toStoreId)
                    ->first();

                if (!$fromStore || !$toStore) {
                    throw new \Exception('One or both stores not found.');
                }

                if ($fromStore->warehouse_id != $toStore->warehouse_id) {
                    throw new \Exception('Store-to-store transfers require both stores to belong to the same warehouse.');
                }
                break;

            case 'warehouse_to_store':
                if (!$fromWarehouseId || !$toStoreId) {
                    throw new \Exception('Source warehouse and destination store are required.');
                }
                // Validate that the destination store belongs to the source warehouse
                $toStore = InventoryStore::where('institute_id', $this->instituteId())
                    ->where('id', $toStoreId)
                    ->first();

                if (!$toStore) {
                    throw new \Exception('Destination store not found.');
                }

                if ($toStore->warehouse_id != $fromWarehouseId) {
                    throw new \Exception('Destination store must belong to the source warehouse.');
                }
                break;

            default:
                throw new \Exception('Invalid transfer sub-type: ' . $subType);
        }

        return true;
    }

    /**
     * Auto-select FIFO batches for an item
     */
    private function autoSelectFIFOBatches($itemId, $quantity)
    {
        $batches = InventoryItemBatch::where('item_id', $itemId)
            ->where('remaining_quantity', '>', 0)
            ->where(function($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>', now());
            })
            ->orderBy('expiry_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $selected = [];
        $remaining = $quantity;

        foreach ($batches as $batch) {
            if ($remaining <= 0) break;

            $takeQty = min($batch->remaining_quantity, $remaining);
            $selected[] = [
                'batch_id' => $batch->id,
                'batch_number' => $batch->batch_number,
                'quantity' => $takeQty,
                'expiry_date' => $batch->expiry_date ? $batch->expiry_date->format('Y-m-d') : null,
            ];
            $remaining -= $takeQty;
        }

        if ($remaining > 0) {
            throw new \Exception("Insufficient batch stock. Need {$remaining} more units.");
        }

        return $selected;
    }

    /**
     * Auto-select FIFO serial numbers for an item
     */
    private function autoSelectFIFOSerials($itemId, $quantity)
    {
        $assets = InventoryAssetInstance::where('item_id', $itemId)
            ->where('status', 'ACTIVE')
            ->orderBy('created_at', 'asc')
            ->limit($quantity)
            ->get();

        if ($assets->count() < $quantity) {
            throw new \Exception("Insufficient active serial numbers. Need {$quantity}, available: {$assets->count()}");
        }

        return $assets->map(function($asset) {
            return [
                'asset_instance_id' => $asset->id,
                'serial_number' => $asset->serial_number,
                'asset_code' => $asset->asset_code,
            ];
        })->toArray();
    }

    public function index(Request $request)
    {
        $query = InventoryStockOut::where('institute_id', $this->instituteId())
            ->with(['item', 'fromWarehouse', 'toWarehouse', 'fromStore', 'toStore', 'creator', 'approver']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('stock_out_code', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_phone', 'LIKE', "%{$search}%")
                  ->orWhere('transaction_id', 'LIKE', "%{$search}%");
            });
        }

        $stockOuts = $query->latest()->paginate(20);

        $stats = [
            'total' => InventoryStockOut::where('institute_id', $this->instituteId())->count(),
            'pending' => InventoryStockOut::where('institute_id', $this->instituteId())->where('status', 'pending')->count(),
            'approved' => InventoryStockOut::where('institute_id', $this->instituteId())->where('status', 'approved')->count(),
            'completed' => InventoryStockOut::where('institute_id', $this->instituteId())->where('status', 'completed')->count(),
            'cancelled' => InventoryStockOut::where('institute_id', $this->instituteId())->where('status', 'cancelled')->count(),
        ];

        return view('instituteAdmin.inventory.stock-out.index', compact('stockOuts', 'stats'));
    }

    public function create()
    {
        $configuration = $this->getActiveConfiguration();

        $items = InventoryItem::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->where('current_stock', '>', 0)
            ->with(['warehouse', 'store', 'category', 'subcategory'])
            ->get();

        $warehouses = InventoryWarehouse::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->get();

        $stores = InventoryStore::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->with('warehouse')
            ->get();

        $categories = InventoryCategory::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->get();

        return view('instituteAdmin.inventory.stock-out.create', compact(
            'items', 'warehouses', 'stores', 'categories', 'configuration'
        ));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:transfer,sell',
                'sub_type' => 'nullable|string',
                'items_data' => 'required|json',
                'batch_data' => 'nullable|json',
                'serial_data' => 'nullable|json',
                'customer_type' => 'nullable|in:individual,business',
                'customer_name' => 'nullable|string|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'customer_email' => 'nullable|email|max:255',
                'customer_address' => 'nullable|string|max:500',
                'gst_number' => 'nullable|string|max:50',
                'pan_number' => 'nullable|string|max:20',
                'payment_method' => 'nullable|string|max:50',
                'sell_from_warehouse_id' => 'nullable|exists:inventory_warehouses,id',
                'sell_from_store_id' => 'nullable|exists:inventory_stores,id',
                'from_warehouse_id' => 'nullable|exists:inventory_warehouses,id',
                'from_store_id' => 'nullable|exists:inventory_stores,id',
                'to_warehouse_id' => 'nullable|exists:inventory_warehouses,id',
                'to_store_id' => 'nullable|exists:inventory_stores,id',
                'subtotal' => 'nullable|numeric|min:0',
                'discount_amount' => 'nullable|numeric|min:0',
                'tax_amount' => 'nullable|numeric|min:0',
                'total_amount' => 'nullable|numeric|min:0',
                'amount_received' => 'nullable|numeric|min:0',
                'change_amount' => 'nullable|numeric|min:0',
                'transfer_payment_method' => 'nullable|string|max:50',
                'transfer_total_amount' => 'nullable|numeric|min:0',
                'transfer_amount_received' => 'nullable|numeric|min:0',
                'logistics_data' => 'nullable|json',
            ]);

            $stockOut = null;
            $itemsData = json_decode($request->items_data, true);
            $batchData = json_decode($request->batch_data, true) ?? [];
            $serialData = json_decode($request->serial_data, true) ?? [];
            $logisticsData = json_decode($request->logistics_data, true) ?? [];

            if (empty($itemsData)) {
                throw new \Exception('No items provided');
            }

            // Validate transfer hierarchy if transfer type
            if ($request->type === 'transfer') {
                $this->validateTransferHierarchy(
                    $request->sub_type,
                    $request->from_warehouse_id,
                    $request->from_store_id,
                    $request->to_warehouse_id,
                    $request->to_store_id
                );

                // Check destination capacity
                $subType = $request->sub_type;
                try {
                    if ($subType === 'warehouse_to_warehouse') {
                        $this->checkDestinationCapacity('warehouse', $request->to_warehouse_id, $itemsData);
                    } elseif ($subType === 'store_to_store') {
                        $this->checkDestinationCapacity('store', $request->to_store_id, $itemsData);
                    } elseif ($subType === 'warehouse_to_store') {
                        $this->checkDestinationCapacity('store', $request->to_store_id, $itemsData);
                    }
                } catch (\Exception $e) {
                    return back()->with('error', $e->getMessage())->withInput();
                }
            }

            DB::transaction(function() use ($request, $itemsData, $batchData, $serialData, $logisticsData, &$stockOut) {
                $stockOut = $this->createStockOut($request, $itemsData, $batchData, $serialData, $logisticsData);
                $this->validateItemsStock($itemsData, $stockOut, $batchData, $serialData);
            });

            return redirect()
                ->route('inventory.stock-out.show', $stockOut->id)
                ->with('success', 'Stock Out Created Successfully.');

        } catch (\Exception $e) {
            Log::error('Stock Out creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    private function validateItemsStock($itemsData, $stockOut, $batchData = [], $serialData = [])
    {
        foreach ($itemsData as $itemData) {
            $item = $this->findSourceItem($itemData['id'], $stockOut);
            
            if (!$item) {
                throw new \Exception("Item not found in source location: " . ($itemData['name'] ?? 'Unknown'));
            }

            // Check overall stock
            if ($item->current_stock < $itemData['quantity']) {
                throw new \Exception("Insufficient stock for {$item->item_name}. Available: {$item->current_stock}, Requested: {$itemData['quantity']}");
            }

            // If batch tracking is enabled
            if ($item->track_batch) {
                // If no batch data provided, auto-select using FIFO
                if (!isset($batchData[$itemData['id']]) || empty($batchData[$itemData['id']])) {
                    $batchData[$itemData['id']] = $this->autoSelectFIFOBatches($item->id, $itemData['quantity']);
                    
                    // Update stockOut with auto-selected batches
                    $stockOut->batch_data = $batchData;
                    $stockOut->save();
                    
                    Log::info('Auto-selected FIFO batches for item', [
                        'item_id' => $item->id,
                        'item_name' => $item->item_name,
                        'quantity' => $itemData['quantity'],
                        'batches' => $batchData[$itemData['id']]
                    ]);
                } else {
                    // Validate provided batch selection
                    $selectedBatches = $batchData[$itemData['id']];
                    $totalBatchQty = 0;
                    
                    foreach ($selectedBatches as $batchSelection) {
                        $batch = InventoryItemBatch::where('item_id', $item->id)
                            ->where('id', $batchSelection['batch_id'])
                            ->first();
                        
                        if (!$batch) {
                            throw new \Exception("Batch not found for item: {$item->item_name}");
                        }
                        
                        if ($batch->remaining_quantity < $batchSelection['quantity']) {
                            throw new \Exception("Insufficient quantity in batch {$batch->batch_number}. Available: {$batch->remaining_quantity}, Requested: {$batchSelection['quantity']}");
                        }
                        
                        // Check if batch is expired
                        if ($batch->expiry_date && $batch->expiry_date < now()) {
                            throw new \Exception("Batch {$batch->batch_number} has expired on {$batch->expiry_date->format('Y-m-d')}");
                        }
                        
                        $totalBatchQty += $batchSelection['quantity'];
                    }
                    
                    if ($totalBatchQty != $itemData['quantity']) {
                        throw new \Exception("Batch quantity total ({$totalBatchQty}) does not match requested quantity ({$itemData['quantity']}) for {$item->item_name}");
                    }
                }
            }

            // If serial tracking is enabled
            if ($item->track_serial) {
                // If no serial data provided, auto-select using FIFO
                if (!isset($serialData[$itemData['id']]) || empty($serialData[$itemData['id']])) {
                    $serialData[$itemData['id']] = $this->autoSelectFIFOSerials($item->id, $itemData['quantity']);
                    
                    // Update stockOut with auto-selected serials
                    $stockOut->serial_data = $serialData;
                    $stockOut->save();
                    
                    Log::info('Auto-selected FIFO serials for item', [
                        'item_id' => $item->id,
                        'item_name' => $item->item_name,
                        'quantity' => $itemData['quantity'],
                        'serials' => $serialData[$itemData['id']]
                    ]);
                } else {
                    // Validate provided serial selection
                    $selectedSerials = $serialData[$itemData['id']];
                    
                    if (count($selectedSerials) != $itemData['quantity']) {
                        throw new \Exception("Serial count ({$selectedSerials}) does not match requested quantity ({$itemData['quantity']}) for {$item->item_name}");
                    }
                    
                    foreach ($selectedSerials as $serialSelection) {
                        $asset = InventoryAssetInstance::where('item_id', $item->id)
                            ->where('id', $serialSelection['asset_instance_id'])
                            ->first();
                        
                        if (!$asset) {
                            throw new \Exception("Serial number not found for item: {$item->item_name}");
                        }
                        
                        if ($asset->status !== 'ACTIVE') {
                            throw new \Exception("Serial number {$asset->serial_number} is not active (status: {$asset->status})");
                        }
                    }
                }
            }
        }
    }

    private function createStockOut($request, $itemsData, $batchData, $serialData, $logisticsData)
    {
        Log::info('Creating Stock Out with data:', [
            'type' => $request->type,
            'sub_type' => $request->sub_type,
            'items_count' => count($itemsData),
            'has_batch_data' => !empty($batchData),
            'has_serial_data' => !empty($serialData),
            'has_logistics' => !empty($logisticsData)
        ]);

        // Build location paths
        $fromLocationPath = $this->buildLocationPath(
            $request->from_warehouse_id,
            $request->from_store_id
        );
        $toLocationPath = $this->buildLocationPath(
            $request->to_warehouse_id,
            $request->to_store_id
        );

        $data = [
            'institute_id' => $this->instituteId(),
            'stock_out_code' => $this->generateStockOutCode($request->type),
            'type' => $request->type,
            'sub_type' => $request->sub_type,
            'items' => $itemsData,
            'batch_data' => $batchData,
            'serial_data' => $serialData,
            'logistics' => $logisticsData,
            'from_location_path' => $fromLocationPath,
            'to_location_path' => $toLocationPath,
            'created_by' => auth()->id(),
            'status' => 'pending',
            'payment_status' => 'pending'
        ];

        if ($request->type === 'sell') {
            $data['from_warehouse_id'] = $request->sell_from_warehouse_id;
            $data['from_store_id'] = $request->sell_from_store_id;
            
            $data['customer_type'] = $request->customer_type;
            $data['customer_name'] = $request->customer_name;
            $data['customer_phone'] = $request->customer_phone;
            $data['customer_email'] = $request->customer_email;
            $data['customer_address'] = $request->customer_address;
            $data['gst_number'] = $request->gst_number;
            $data['pan_number'] = $request->pan_number;
            $data['payment_method'] = $request->payment_method;
            
            $subtotal = 0;
            foreach ($itemsData as $item) {
                $subtotal += $item['quantity'] * ($item['price'] ?? 0);
            }
            
            $data['subtotal'] = $request->subtotal ?? $subtotal;
            $data['tax_amount'] = $request->tax_amount ?? 0;
            $data['discount_amount'] = $request->discount_amount ?? 0;
            $data['total_amount'] = $request->total_amount ?? $subtotal;
            $data['amount_received'] = $request->amount_received ?? 0;
            $data['change_amount'] = $request->change_amount ?? 0;
            
        } else {
            $data['from_warehouse_id'] = $request->from_warehouse_id;
            $data['from_store_id'] = $request->from_store_id;
            $data['to_warehouse_id'] = $request->to_warehouse_id;
            $data['to_store_id'] = $request->to_store_id;
            
            if ($request->sub_type === 'warehouse_to_warehouse') {
                $data['payment_method'] = $request->transfer_payment_method;
                $data['total_amount'] = $request->transfer_total_amount ?? 0;
                $data['amount_received'] = $request->transfer_amount_received ?? 0;
                $data['change_amount'] = ($request->transfer_amount_received ?? 0) - ($request->transfer_total_amount ?? 0);
            }
            
            $data['subtotal'] = $request->subtotal ?? 0;
            $data['tax_amount'] = 0;
            $data['discount_amount'] = 0;
        }

        $stockOut = InventoryStockOut::create($data);
        
        Log::info('Stock Out created:', ['id' => $stockOut->id, 'code' => $stockOut->stock_out_code]);
        
        return $stockOut;
    }

    /**
     * Build location path string from warehouse and store IDs
     */
    private function buildLocationPath($warehouseId, $storeId)
    {
        $parts = [];

        if ($warehouseId) {
            $warehouse = InventoryWarehouse::where('institute_id', $this->instituteId())
                ->where('id', $warehouseId)
                ->first();
            if ($warehouse) {
                $parts[] = '🏢 ' . $warehouse->warehouse_name;
                if ($warehouse->warehouse_code) {
                    $parts[] = '(' . $warehouse->warehouse_code . ')';
                }
            }
        }

        if ($storeId) {
            $store = InventoryStore::where('institute_id', $this->instituteId())
                ->where('id', $storeId)
                ->first();
            if ($store) {
                $parts[] = '🏪 ' . $store->store_name;
                if ($store->store_code) {
                    $parts[] = '(' . $store->store_code . ')';
                }
            }
        }

        return implode(' ', $parts) ?: 'N/A';
    }

    private function findSourceItem($itemId, $stockOut)
    {
        $query = InventoryItem::where('institute_id', $this->instituteId())
            ->where('id', $itemId);

        if ($stockOut->from_warehouse_id) {
            $query->where('warehouse_id', $stockOut->from_warehouse_id);
        } elseif ($stockOut->from_store_id) {
            $query->where('store_id', $stockOut->from_store_id);
        }

        return $query->first();
    }

    public function show($id)
    {
        $stockOut = InventoryStockOut::where('institute_id', $this->instituteId())
            ->with([
                'fromWarehouse',
                'toWarehouse',
                'fromStore',
                'toStore',
                'creator',
                'approver',
                'receiver'
            ])
            ->findOrFail($id);

        return view('instituteAdmin.inventory.stock-out.show', compact('stockOut'));
    }

    public function edit($id)
    {
        $stockOut = InventoryStockOut::where('institute_id', $this->instituteId())
            ->where('status', 'pending')
            ->findOrFail($id);

        $items = InventoryItem::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->where('current_stock', '>', 0)
            ->with(['warehouse', 'category'])
            ->get();

        $warehouses = InventoryWarehouse::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->get();

        $stores = InventoryStore::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->with('warehouse')
            ->get();

        return view('instituteAdmin.inventory.stock-out.edit', compact('stockOut', 'items', 'warehouses', 'stores'));
    }

    public function update(Request $request, $id)
    {
        try {
            $stockOut = InventoryStockOut::where('institute_id', $this->instituteId())
                ->where('status', 'pending')
                ->findOrFail($id);

            $request->validate([
                'item_id' => 'required|exists:inventory_items,id',
                'quantity' => 'required|numeric|min:0.01',
                'expected_arrival_date' => 'nullable|date|after_or_equal:today',
                'reason' => 'nullable|string|max:500',
                'notes' => 'nullable|string|max:1000',
            ]);

            DB::transaction(function() use ($request, $stockOut) {
                $oldData = $stockOut->toArray();

                $updateData = [
                    'item_id' => $request->item_id,
                    'quantity' => $request->quantity,
                    'expected_arrival_date' => $request->expected_arrival_date,
                    'reason' => $request->reason,
                    'notes' => $request->notes,
                    'updated_by' => auth()->id()
                ];

                if ($stockOut->type === 'sell') {
                    $updateData['unit_price'] = $request->unit_price;
                    $updateData['total_amount'] = $request->quantity * $request->unit_price;
                    $updateData['customer_name'] = $request->customer_name;
                    $updateData['customer_phone'] = $request->customer_phone;
                    $updateData['customer_email'] = $request->customer_email;
                    $updateData['customer_address'] = $request->customer_address;
                }

                $stockOut->update($updateData);

                InventoryLogger::log([
                    'module' => 'STOCK_OUT',
                    'action' => 'UPDATE',
                    'record_id' => $stockOut->id,
                    'old_data' => $oldData,
                    'new_data' => $stockOut->fresh()->toArray(),
                    'remarks' => 'Stock Out updated: ' . $stockOut->stock_out_code
                ]);
            });

            return redirect()
                ->route('inventory.stock-out.show', $stockOut->id)
                ->with('success', 'Stock Out Updated Successfully');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * APPROVE - For SELL: Reduces stock immediately with batch/serial tracking
     * For TRANSFER: Just approves, stock moves on complete
     */
    public function approve($id)
    {
        try {
            Log::info('=== APPROVE STOCK OUT ===', ['id' => $id]);
            
            $stockOut = InventoryStockOut::where('institute_id', $this->instituteId())
                ->where('status', 'pending')
                ->findOrFail($id);

            DB::transaction(function() use ($stockOut) {
                $items = is_array($stockOut->items) ? $stockOut->items : json_decode($stockOut->items, true);
                $batchData = $stockOut->batch_data ?? [];
                $serialData = $stockOut->serial_data ?? [];
                
                // Validate all items first
                foreach ($items as $itemData) {
                    $item = $this->findSourceItem($itemData['id'], $stockOut);
                    
                    if (!$item) {
                        throw new \Exception("Item not found in source location: " . ($itemData['name'] ?? 'Unknown'));
                    }

                    if ($item->current_stock < $itemData['quantity']) {
                        throw new \Exception("Insufficient stock for {$item->item_name}. Available: {$item->current_stock}, Required: {$itemData['quantity']}");
                    }

                    // Validate expiry for batch items
                    if ($item->track_batch && isset($batchData[$itemData['id']])) {
                        foreach ($batchData[$itemData['id']] as $batchSelection) {
                            $batch = InventoryItemBatch::where('item_id', $item->id)
                                ->where('id', $batchSelection['batch_id'])
                                ->first();
                            
                            if ($batch && $batch->expiry_date && $batch->expiry_date < now()) {
                                throw new \Exception("Batch {$batch->batch_number} has expired. Cannot use expired stock.");
                            }
                        }
                    }
                }

                if ($stockOut->type === 'sell') {
                    // SALE: Reduce stock immediately with batch/serial tracking
                    foreach ($items as $itemData) {
                        $item = $this->findSourceItem($itemData['id'], $stockOut);
                        $oldStock = $item->current_stock;
                        
                        // Handle batch tracking
                        if ($item->track_batch && isset($batchData[$itemData['id']])) {
                            foreach ($batchData[$itemData['id']] as $batchSelection) {
                                $batch = InventoryItemBatch::where('item_id', $item->id)
                                    ->where('id', $batchSelection['batch_id'])
                                    ->first();
                                
                                if ($batch) {
                                    $batch->deductStock($batchSelection['quantity'], $stockOut, 'Sold');
                                    
                                    // Create stock movement for batch
                                    $this->createStockMovementForBatch($batch, $batchSelection['quantity'], $stockOut, 'OUT');
                                }
                            }
                            
                            Log::info('Batch stock deducted for sale', [
                                'item_id' => $item->id,
                                'item_name' => $item->item_name,
                                'quantity' => $itemData['quantity'],
                                'batches' => $batchData[$itemData['id']]
                            ]);
                        }
                        
                        // Handle serial tracking
                        if ($item->track_serial && isset($serialData[$itemData['id']])) {
                            foreach ($serialData[$itemData['id']] as $serialSelection) {
                                $asset = InventoryAssetInstance::where('item_id', $item->id)
                                    ->where('id', $serialSelection['asset_instance_id'])
                                    ->first();
                                
                                if ($asset) {
                                    $asset->update([
                                        'status' => 'SOLD',
                                        'sold_at' => now(),
                                        'sold_by' => auth()->id(),
                                        'stock_out_id' => $stockOut->id
                                    ]);
                                }
                            }
                            
                            Log::info('Serial assets marked as SOLD', [
                                'item_id' => $item->id,
                                'item_name' => $item->item_name,
                                'serials' => count($serialData[$itemData['id']])
                            ]);
                        }
                        
                        // Reduce overall stock
                        $item->current_stock -= $itemData['quantity'];
                        $item->available_stock -= $itemData['quantity'];
                        $item->save();
                        
                        // Create stock movement
                        $this->createStockMovement($item, $itemData, $oldStock, $stockOut, 'OUT');
                    }
                    
                    // Update utilization for source location
                    $this->updateLocationUtilization($stockOut->from_warehouse_id, $stockOut->from_store_id);
                    
                    $stockOut->update([
                        'status' => 'completed',
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                        'received_by' => auth()->id(),
                        'received_at' => now()
                    ]);
                } else {
                    // TRANSFER: Just approve, stock moves on complete
                    $stockOut->update([
                        'status' => 'approved',
                        'approved_by' => auth()->id(),
                        'approved_at' => now()
                    ]);
                }

                InventoryLogger::log([
                    'module' => 'STOCK_OUT',
                    'action' => 'APPROVE',
                    'record_id' => $stockOut->id,
                    'new_data' => [
                        'stock_out_code' => $stockOut->stock_out_code,
                        'type' => $stockOut->type,
                        'status' => $stockOut->status,
                        'has_batch_data' => !empty($batchData),
                        'has_serial_data' => !empty($serialData),
                        'items_count' => count($items),
                        'total_quantity' => array_sum(array_column($items, 'quantity'))
                    ],
                    'remarks' => 'Stock Out approved: ' . $stockOut->stock_out_code
                ]);
            });

            Log::info('Stock Out approved successfully', ['id' => $stockOut->id]);

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Stock Out approved successfully.'
                ]);
            }

            return redirect()
                ->route('inventory.stock-out.show', $stockOut->id)
                ->with('success', 'Stock Out approved successfully.');

        } catch (\Exception $e) {
            Log::error('Approve stock out failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->route('inventory.stock-out.show', $id)
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Create stock movement for batch deduction
     */
    private function createStockMovementForBatch($batch, $quantity, $stockOut, $movementType)
    {
        $movement = InventoryStockMovement::create([
            'institute_id' => $this->instituteId(),
            'item_id' => $batch->item_id,
            'warehouse_id' => $batch->warehouse_id ?? $stockOut->from_warehouse_id,
            'store_id' => $batch->store_id ?? $stockOut->from_store_id,
            'batch_id' => $batch->id,
            'movement_type' => $movementType,
            'quantity' => $quantity,
            'previous_stock' => $batch->remaining_quantity + $quantity,
            'new_stock' => $batch->remaining_quantity,
            'unit_cost' => $batch->purchase_price ?? 0,
            'total_cost' => ($batch->purchase_price ?? 0) * $quantity,
            'reference_type' => InventoryStockOut::class,
            'reference_id' => $stockOut->id,
            'notes' => "Batch deduction: {$batch->batch_number} - " . ($stockOut->customer_name ?? 'Transfer'),
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
     * COMPLETE - For TRANSFER: Moves stock from source to destination
     * Includes batch and asset instance transfer
     */
    public function complete($id)
    {
        try {
            Log::info('=== COMPLETE STOCK OUT (TRANSFER) ===', ['id' => $id]);
            
            $stockOut = InventoryStockOut::where('institute_id', $this->instituteId())
                ->where('status', 'approved')
                ->where('type', 'transfer')
                ->findOrFail($id);

            $items = is_array($stockOut->items) ? $stockOut->items : json_decode($stockOut->items, true);
            
            // Validate transfer hierarchy again before completing
            $this->validateTransferHierarchy(
                $stockOut->sub_type,
                $stockOut->from_warehouse_id,
                $stockOut->from_store_id,
                $stockOut->to_warehouse_id,
                $stockOut->to_store_id
            );
            
            // Check destination capacity
            $subType = $stockOut->sub_type;
            try {
                if ($subType === 'warehouse_to_warehouse') {
                    $this->checkDestinationCapacity('warehouse', $stockOut->to_warehouse_id, $items);
                } elseif ($subType === 'store_to_store') {
                    $this->checkDestinationCapacity('store', $stockOut->to_store_id, $items);
                } elseif ($subType === 'warehouse_to_store') {
                    $this->checkDestinationCapacity('store', $stockOut->to_store_id, $items);
                }
            } catch (\Exception $e) {
                $errorMessage = 'Cannot complete transfer: ' . $e->getMessage();
                
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage
                    ], 400);
                }
                
                return redirect()
                    ->route('inventory.stock-out.show', $id)
                    ->with('error', $errorMessage);
            }

            DB::transaction(function() use ($stockOut, $items) {
                $destinationItem = null;
                $batchData = $stockOut->batch_data ?? [];
                $serialData = $stockOut->serial_data ?? [];
                
                foreach ($items as $itemData) {
                    // Find source item
                    $sourceItem = $this->findSourceItem($itemData['id'], $stockOut);

                    if (!$sourceItem) {
                        throw new \Exception('Item not found in source location: ' . ($itemData['name'] ?? 'Unknown'));
                    }

                    if ($sourceItem->current_stock < $itemData['quantity']) {
                        throw new \Exception("Insufficient stock for {$sourceItem->item_name}. Available: {$sourceItem->current_stock}, Required: {$itemData['quantity']}");
                    }

                    // 1. DECREASE from source
                    $oldStock = $sourceItem->current_stock;
                    $sourceItem->current_stock -= $itemData['quantity'];
                    $sourceItem->available_stock -= $itemData['quantity'];
                    $sourceItem->save();

                    $this->createStockMovement($sourceItem, $itemData, $oldStock, $stockOut, 'OUT');

                    // 2. ADD to destination
                    $destItem = $this->addToDestination($sourceItem, $stockOut, $itemData['quantity']);
                    $destinationItem = $destItem;

                    // 3. TRANSFER BATCHES if batch tracking is enabled
                    if ($sourceItem->track_batch) {
                        $this->transferBatches($sourceItem, $destItem, $itemData['quantity'], $stockOut);
                    }

                    // 4. TRANSFER ASSET INSTANCES if item type is ASSET
                    if ($sourceItem->item_type === 'ASSET') {
                        $this->transferAssetInstances($sourceItem, $destItem, $itemData['quantity'], $stockOut);
                    }
                }

                // Update utilization for both locations
                $this->updateLocationUtilization($stockOut->from_warehouse_id, $stockOut->from_store_id);
                $this->updateLocationUtilization($stockOut->to_warehouse_id, $stockOut->to_store_id);

                // Create receipt in for destination
                $this->createReceiptIn($stockOut, $destinationItem, $items);

                // Build location paths
                $fromLocationPath = $this->buildLocationPath(
                    $stockOut->from_warehouse_id,
                    $stockOut->from_store_id
                );
                $toLocationPath = $this->buildLocationPath(
                    $stockOut->to_warehouse_id,
                    $stockOut->to_store_id
                );

                $stockOut->update([
                    'status' => 'completed',
                    'received_by' => auth()->id(),
                    'received_at' => now(),
                    'from_location_path' => $fromLocationPath,
                    'to_location_path' => $toLocationPath,
                    'source_location_details' => json_encode($this->getLocationDetails($stockOut, 'from')),
                    'destination_location_details' => json_encode($this->getLocationDetails($stockOut, 'to')),
                ]);

                Log::info('Transfer completed successfully', [
                    'id' => $stockOut->id,
                    'from_location_path' => $fromLocationPath,
                    'to_location_path' => $toLocationPath
                ]);

                InventoryLogger::log([
                    'module' => 'STOCK_OUT',
                    'action' => 'COMPLETE',
                    'record_id' => $stockOut->id,
                    'new_data' => [
                        'transfer_code' => $stockOut->stock_out_code,
                        'from_warehouse' => $stockOut->fromWarehouse->warehouse_name ?? 'N/A',
                        'to_warehouse' => $stockOut->toWarehouse->warehouse_name ?? 'N/A',
                        'from_location_path' => $fromLocationPath,
                        'to_location_path' => $toLocationPath,
                        'items_count' => count($items),
                        'total_quantity' => array_sum(array_column($items, 'quantity'))
                    ],
                    'remarks' => 'Transfer completed: ' . $stockOut->stock_out_code
                ]);
            });

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transfer completed successfully. Stock has been moved.',
                    'data' => [
                        'transfer_code' => $stockOut->stock_out_code,
                        'from_warehouse' => $stockOut->fromWarehouse->warehouse_name ?? 'N/A',
                        'to_warehouse' => $stockOut->toWarehouse->warehouse_name ?? 'N/A',
                        'from_location_path' => $stockOut->from_location_path,
                        'to_location_path' => $stockOut->to_location_path,
                        'items_count' => count($items)
                    ]
                ]);
            }

            return redirect()
                ->route('inventory.stock-out.show', $stockOut->id)
                ->with('success', 'Transfer completed successfully. Stock has been moved.');

        } catch (\Exception $e) {
            Log::error('Complete stock out failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Try to rollback the transfer to approved status
            try {
                $stockOut = InventoryStockOut::where('institute_id', $this->instituteId())
                    ->where('id', $id)
                    ->first();
                if ($stockOut && $stockOut->status === 'completed') {
                    // This is a partial failure - log it
                    InventoryLogger::log([
                        'module' => 'STOCK_OUT',
                        'action' => 'COMPLETE_FAILED',
                        'record_id' => $stockOut->id,
                        'remarks' => 'Transfer completion failed after partial updates: ' . $e->getMessage()
                    ]);
                }
            } catch (\Exception $inner) {
                // Ignore rollback errors
            }

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to complete transfer: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()
                ->route('inventory.stock-out.show', $id)
                ->with('error', 'Failed to complete transfer: ' . $e->getMessage());
        }
    }

    /**
     * Get location details as array
     */
    private function getLocationDetails($stockOut, $direction)
    {
        $details = [
            'type' => null,
            'id' => null,
            'name' => null,
            'code' => null,
            'path' => null,
            'hierarchy' => [],
            'contact' => [],
        ];

        $warehouse = $stockOut->{$direction . 'Warehouse'};
        $store = $stockOut->{$direction . 'Store'};

        if ($warehouse) {
            $details['type'] = 'warehouse';
            $details['id'] = $warehouse->id;
            $details['name'] = $warehouse->warehouse_name;
            $details['code'] = $warehouse->warehouse_code;
            $details['path'] = $this->buildLocationPath($warehouse->id, null);
            $details['hierarchy'] = [
                'block_id' => $warehouse->block_id,
                'floor_id' => $warehouse->floor_id,
                'room_id' => $warehouse->room_id,
            ];
            $details['contact'] = [
                'person' => $warehouse->contact_person,
                'phone' => $warehouse->phone,
                'email' => $warehouse->email,
            ];
            $details['address'] = $warehouse->address;
            $details['capacity'] = $warehouse->capacity;
            $details['utilization'] = $warehouse->current_utilization;
        }

        if ($store) {
            $details['type'] = 'store';
            $details['id'] = $store->id;
            $details['name'] = $store->store_name;
            $details['code'] = $store->store_code;
            $details['path'] = $this->buildLocationPath(null, $store->id);
            $details['hierarchy'] = [
                'floor_id' => $store->floor_id,
                'room_id' => $store->room_id,
            ];
            $details['contact'] = [
                'person' => $store->contact_person,
                'phone' => $store->phone,
                'email' => $store->email,
            ];
            $details['address'] = $store->address;
            $details['capacity'] = $store->capacity;
            $details['utilization'] = $store->current_utilization;
            
            if ($store->warehouse) {
                $details['parent_warehouse'] = [
                    'id' => $store->warehouse->id,
                    'name' => $store->warehouse->warehouse_name,
                    'code' => $store->warehouse->warehouse_code,
                ];
            }
        }

        return $details;
    }

    /**
     * Create receipt in record for destination
     */
    private function createReceiptIn($stockOut, $destItem, $items)
    {
        $totalQuantity = array_sum(array_column($items, 'quantity'));
        $totalPrice = $destItem ? ($destItem->buying_price ?? 0) * $totalQuantity : 0;
        
        $receiptNumber = $this->generateReceiptInNumber();
        
        $receiptIn = InventoryReceiptIn::create([
            'institute_id' => $this->instituteId(),
            'receipt_number' => $receiptNumber,
            'item_id' => $destItem ? $destItem->id : null,
            'warehouse_id' => $stockOut->to_warehouse_id,
            'store_id' => $stockOut->to_store_id,
            'quantity' => $totalQuantity,
            'unit_price' => $destItem ? $destItem->buying_price : 0,
            'total_price' => $totalPrice,
            'receipt_type' => 'TRANSFER',
            'reference_type' => InventoryStockOut::class,
            'reference_id' => $stockOut->id,
            'supplier_name' => 'Transfer from ' . ($stockOut->fromWarehouse->warehouse_name ?? 'N/A'),
            'notes' => 'Transfer receipt: ' . $stockOut->stock_out_code,
            'status' => 'COMPLETED',
            'created_by' => auth()->id(),
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        
        Log::info('Receipt IN created for transfer', [
            'receipt_number' => $receiptNumber,
            'transfer_id' => $stockOut->id
        ]);
        
        return $receiptIn;
    }

    /**
     * Generate receipt in number
     */
    private function generateReceiptInNumber()
    {
        $prefix = 'RCV';
        $date = now()->format('Ymd');
        $last = InventoryReceiptIn::where('institute_id', $this->instituteId())
            ->whereDate('created_at', now()->toDateString())
            ->count();
        
        return $prefix . '-' . $date . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }

    /**
     * TRANSFER BATCHES - Properly handles batch transfer with stock movements
     */
    private function transferBatches($sourceItem, $destItem, $quantity, $stockOut)
    {
        if (!$sourceItem->track_batch) {
            return;
        }
        
        Log::info('Transferring batches for item:', [
            'source_item_id' => $sourceItem->id,
            'dest_item_id' => $destItem->id,
            'quantity' => $quantity,
            'source_item_code' => $sourceItem->item_code,
            'dest_item_code' => $destItem->item_code
        ]);
        
        // Get batches using FEFO (First Expiry First Out)
        $batches = $sourceItem->batches()
            ->where('remaining_quantity', '>', 0)
            ->orderBy('expiry_date', 'asc')
            ->get();
        
        $remainingQty = $quantity;
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
            ];
            
            // Deduct from source batch
            $batch->deductStock($takeQty, $stockOut, 'Transferred to ' . ($destItem->location_name ?? 'Destination'));
            
            // Create stock movement for batch transfer OUT
            $this->createStockMovementForBatch($batch, $takeQty, $stockOut, 'OUT');
            
            // Check if destination already has this batch number
            $existingBatch = InventoryItemBatch::where('institute_id', $this->instituteId())
                ->where('item_id', $destItem->id)
                ->where('batch_number', $batch->batch_number)
                ->where('warehouse_id', $stockOut->to_warehouse_id)
                ->where('store_id', $stockOut->to_store_id)
                ->first();
            
            if ($existingBatch) {
                // Batch already exists, just add to it
                $existingBatch->quantity += $takeQty;
                $existingBatch->remaining_quantity += $takeQty;
                $existingBatch->save();
                
                // Create stock movement for batch transfer IN
                $this->createStockMovementForBatch($existingBatch, $takeQty, $stockOut, 'IN');
                
                Log::info('Updated existing batch at destination', [
                    'batch_number' => $batch->batch_number,
                    'added_quantity' => $takeQty
                ]);
            } else {
                // Create new batch at destination
                $newBatch = $batch->replicate();
                $newBatch->item_id = $destItem->id;
                $newBatch->warehouse_id = $stockOut->to_warehouse_id;
                $newBatch->store_id = $stockOut->to_store_id;
                $newBatch->quantity = $takeQty;
                $newBatch->remaining_quantity = $takeQty;
                $newBatch->created_by = auth()->id();
                $newBatch->save();
                
                // Create stock movement for batch transfer IN
                $this->createStockMovementForBatch($newBatch, $takeQty, $stockOut, 'IN');
                
                Log::info('Created new batch at destination', [
                    'batch_number' => $newBatch->batch_number,
                    'quantity' => $takeQty
                ]);
            }
            
            $transferredBatches[] = [
                'source_batch_id' => $batch->id,
                'batch_number' => $batch->batch_number,
                'quantity' => $takeQty,
                'destination_batch_id' => $existingBatch ? $existingBatch->id : ($newBatch->id ?? null),
            ];
            
            InventoryLogger::log([
                'module' => 'BATCH',
                'action' => 'TRANSFER',
                'record_id' => $destItem->id,
                'new_data' => [
                    'batch_number' => $batch->batch_number,
                    'quantity' => $takeQty,
                    'source_item_id' => $sourceItem->id,
                    'dest_item_id' => $destItem->id,
                    'transfer_id' => $stockOut->id,
                    'source_batch_id' => $batch->id,
                ],
                'remarks' => "Batch {$batch->batch_number} transferred to destination"
            ]);
            
            $remainingQty -= $takeQty;
        }
        
        // If there's remaining quantity that couldn't be fulfilled by batches,
        // create a new batch for the remaining quantity
        if ($remainingQty > 0) {
            Log::warning('Not enough batches to cover quantity, creating new batch for remainder', [
                'remaining' => $remainingQty,
                'item_id' => $destItem->id
            ]);
            
            $newBatch = InventoryItemBatch::create([
                'institute_id' => $this->instituteId(),
                'item_id' => $destItem->id,
                'warehouse_id' => $stockOut->to_warehouse_id,
                'store_id' => $stockOut->to_store_id,
                'batch_number' => 'BATCH-' . strtoupper(uniqid()),
                'quantity' => $remainingQty,
                'remaining_quantity' => $remainingQty,
                'purchase_price' => $sourceItem->buying_price ?? 0,
                'created_by' => auth()->id(),
            ]);
            
            // Create stock movement for new batch
            $this->createStockMovementForBatch($newBatch, $remainingQty, $stockOut, 'IN');
            
            InventoryLogger::log([
                'module' => 'BATCH',
                'action' => 'CREATE_FROM_TRANSFER',
                'record_id' => $newBatch->id,
                'new_data' => [
                    'batch_number' => $newBatch->batch_number,
                    'quantity' => $remainingQty,
                    'source_item_id' => $sourceItem->id,
                    'dest_item_id' => $destItem->id,
                    'transfer_id' => $stockOut->id
                ],
                'remarks' => "New batch created for remaining quantity from transfer"
            ]);
        }
        
        Log::info('Batch transfer completed', [
            'transferred_batches' => count($transferredBatches),
            'total_quantity' => $quantity
        ]);
        
        return $transferredBatches;
    }

    /**
     * TRANSFER ASSET INSTANCES - Handles asset transfer properly
     */
    private function transferAssetInstances($sourceItem, $destItem, $quantity, $stockOut)
    {
        if ($sourceItem->item_type !== 'ASSET') {
            return;
        }
        
        Log::info('Transferring asset instances for item:', [
            'source_item_id' => $sourceItem->id,
            'dest_item_id' => $destItem->id,
            'quantity' => $quantity,
            'source_item_code' => $sourceItem->item_code
        ]);
        
        // Get active asset instances from source
        $assets = $sourceItem->assetInstances()
            ->where('status', 'ACTIVE')
            ->limit($quantity)
            ->get();
        
        $transferredAssets = 0;
        $assetDetails = [];
        
        foreach ($assets as $asset) {
            // Check if asset already exists at destination with same serial number
            $existingAsset = InventoryAssetInstance::where('institute_id', $this->instituteId())
                ->where('serial_number', $asset->serial_number)
                ->where('item_id', $destItem->id)
                ->where(function($q) use ($stockOut) {
                    if ($stockOut->to_warehouse_id) {
                        $q->where('warehouse_id', $stockOut->to_warehouse_id);
                    }
                    if ($stockOut->to_store_id) {
                        $q->where('store_id', $stockOut->to_store_id);
                    }
                })
                ->first();
            
            if ($existingAsset) {
                // Asset already exists at destination - update status
                $existingAsset->status = 'ACTIVE';
                $existingAsset->save();
                
                Log::info('Updated existing asset at destination', [
                    'asset_code' => $asset->asset_code,
                    'serial_number' => $asset->serial_number
                ]);
            } else {
                // Transfer asset to destination
                $newAsset = $asset->replicate();
                $newAsset->item_id = $destItem->id;
                $newAsset->warehouse_id = $stockOut->to_warehouse_id;
                $newAsset->store_id = $stockOut->to_store_id;
                $newAsset->asset_code = $asset->asset_code;
                $newAsset->created_by = auth()->id();
                $newAsset->save();
            }
            
            // Mark original as transferred
            $asset->update([
                'status' => 'TRANSFERRED',
                'transfer_id' => $stockOut->id,
                'updated_by' => auth()->id()
            ]);
            
            // Create stock movement for asset transfer
            InventoryStockMovement::create([
                'institute_id' => $this->instituteId(),
                'item_id' => $sourceItem->id,
                'warehouse_id' => $stockOut->from_warehouse_id,
                'store_id' => $stockOut->from_store_id,
                'asset_instance_id' => $asset->id,
                'movement_type' => 'OUT',
                'quantity' => 1,
                'previous_stock' => $sourceItem->current_stock + 1,
                'new_stock' => $sourceItem->current_stock,
                'unit_cost' => $asset->buying_price ?? 0,
                'total_cost' => $asset->buying_price ?? 0,
                'reference_type' => InventoryStockOut::class,
                'reference_id' => $stockOut->id,
                'notes' => "Asset transferred: {$asset->asset_code}",
                'created_by' => auth()->id(),
            ]);
            
            $transferredAssets++;
            $assetDetails[] = [
                'source_asset_id' => $asset->id,
                'asset_code' => $asset->asset_code,
                'serial_number' => $asset->serial_number
            ];
            
            InventoryLogger::log([
                'module' => 'ASSET',
                'action' => 'TRANSFER',
                'record_id' => $destItem->id,
                'new_data' => [
                    'asset_code' => $asset->asset_code,
                    'serial_number' => $asset->serial_number,
                    'source_item_id' => $sourceItem->id,
                    'dest_item_id' => $destItem->id,
                    'transfer_id' => $stockOut->id
                ],
                'remarks' => "Asset {$asset->asset_code} transferred to destination"
            ]);
        }
        
        // If not enough assets, log warning
        if ($transferredAssets < $quantity) {
            Log::warning('Not enough active assets for transfer', [
                'requested' => $quantity,
                'transferred' => $transferredAssets,
                'item_id' => $sourceItem->id
            ]);
            
            InventoryLogger::log([
                'module' => 'ASSET',
                'action' => 'TRANSFER_WARNING',
                'record_id' => $sourceItem->id,
                'new_data' => [
                    'source_item_id' => $sourceItem->id,
                    'dest_item_id' => $destItem->id,
                    'requested' => $quantity,
                    'transferred' => $transferredAssets,
                    'transfer_id' => $stockOut->id
                ],
                'remarks' => "Not enough active assets for transfer. Requested: {$quantity}, Transferred: {$transferredAssets}"
            ]);
        }
        
        Log::info('Asset transfer completed', [
            'transferred_assets' => $transferredAssets,
            'total_quantity' => $quantity
        ]);
        
        return $assetDetails;
    }

    /**
     * Create stock movement record
     */
    private function createStockMovement($item, $itemData, $oldStock, $stockOut, $movementType)
    {
        $movement = InventoryStockMovement::create([
            'institute_id' => $this->instituteId(),
            'item_id' => $item->id,
            'warehouse_id' => $item->warehouse_id,
            'store_id'     => $item->store_id,
            'movement_type' => $movementType,
            'quantity' => $itemData['quantity'],
            'previous_stock' => $oldStock,
            'new_stock' => $item->current_stock,
            'unit_cost' => $item->buying_price ?? 0,
            'total_cost' => ($item->buying_price ?? 0) * $itemData['quantity'],
            'reference_type' => InventoryStockOut::class,
            'reference_id' => $stockOut->id,
            'notes' => "Stock Out: {$stockOut->stock_out_code} - " . ($stockOut->customer_name ?? 'Transfer'),
            'created_by' => auth()->id(),
        ]);
        
        Log::info('Stock movement created', [
            'movement_id' => $movement->id,
            'item_id' => $item->id,
            'type' => $movementType,
            'quantity' => $itemData['quantity']
        ]);
        
        return $movement;
    }

    /**
     * Update utilization for specific location
     */
    private function updateLocationUtilization($warehouseId = null, $storeId = null)
    {
        if ($warehouseId) {
            $warehouse = InventoryWarehouse::find($warehouseId);
            if ($warehouse) {
                $totalStock = InventoryItem::where('institute_id', $this->instituteId())
                    ->where('warehouse_id', $warehouseId)
                    ->sum('current_stock') ?? 0;
                
                $warehouse->current_utilization = $totalStock;
                $warehouse->save();
                
                Log::info('Warehouse utilization updated:', [
                    'warehouse_id' => $warehouseId,
                    'current_utilization' => $warehouse->current_utilization,
                    'capacity' => $warehouse->capacity,
                ]);
            }
        }

        if ($storeId) {
            $store = InventoryStore::find($storeId);
            if ($store) {
                $totalStock = InventoryItem::where('institute_id', $this->instituteId())
                    ->where('store_id', $storeId)
                    ->sum('current_stock') ?? 0;
                
                $store->current_utilization = $totalStock;
                $store->save();
                
                Log::info('Store utilization updated:', [
                    'store_id' => $storeId,
                    'current_utilization' => $store->current_utilization,
                    'capacity' => $store->capacity,
                ]);
            }
        }
    }

    /**
     * Add items to destination location
     */
    private function addToDestination($sourceItem, $stockOut, $quantity)
    {
        if ($stockOut->to_store_id) {
            return $this->createItemInStore(
                $sourceItem,
                $stockOut->to_store_id,
                $quantity,
                $stockOut
            );
        }
    
        if ($stockOut->to_warehouse_id) {
            return $this->createItemInWarehouse(
                $sourceItem,
                $stockOut->to_warehouse_id,
                $quantity,
                $stockOut
            );
        }
    
        throw new \Exception('No destination specified for transfer.');
    }

    /**
     * Create item in store
     */
    private function createItemInStore($sourceItem, $storeId, $quantity, $stockOut)
    {
        $existingItem = InventoryItem::where('institute_id', $this->instituteId())
            ->where('item_code', $sourceItem->item_code)
            ->where('store_id', $storeId)
            ->first();

        if ($existingItem) {
            $oldStock = $existingItem->current_stock;

            $existingItem->current_stock += $quantity;
            $existingItem->available_stock += $quantity;
            $existingItem->save();

            $this->createStockMovement(
                $existingItem,
                ['quantity' => $quantity, 'name' => $existingItem->item_name],
                $oldStock,
                $stockOut,
                'IN'
            );

            Log::info('Destination store item updated', [
                'id' => $existingItem->id,
                'item_code' => $existingItem->item_code,
                'store_id' => $storeId
            ]);

            return $existingItem;
        }

        $newItem = $sourceItem->replicate();
        $newItem->item_code = $sourceItem->item_code;
        $newItem->sku = $sourceItem->sku;
        $newItem->barcode = $sourceItem->barcode;
        $newItem->warehouse_id = null;
        $newItem->store_id = $storeId;
        $newItem->current_stock = $quantity;
        $newItem->available_stock = $quantity;
        $newItem->reserved_stock = 0;
        $newItem->opening_stock = 0;
        $newItem->opening_stock_value = 0;
        $newItem->created_by = auth()->id();
        $newItem->save();

        $this->createStockMovement(
            $newItem,
            ['quantity' => $quantity, 'name' => $newItem->item_name],
            0,
            $stockOut,
            'IN'
        );

        Log::info('New destination store item created', [
            'id' => $newItem->id,
            'item_code' => $newItem->item_code,
            'store_id' => $storeId
        ]);

        return $newItem;
    }

    /**
     * Create item in warehouse
     */
    private function createItemInWarehouse($sourceItem, $warehouseId, $quantity, $stockOut)
    {
        $existingItem = InventoryItem::where('institute_id', $this->instituteId())
            ->where('item_code', $sourceItem->item_code)
            ->where('warehouse_id', $warehouseId)
            ->first();

        if ($existingItem) {
            $oldStock = $existingItem->current_stock;

            $existingItem->current_stock += $quantity;
            $existingItem->available_stock += $quantity;
            $existingItem->save();

            $this->createStockMovement(
                $existingItem,
                [
                    'quantity' => $quantity,
                    'name' => $existingItem->item_name
                ],
                $oldStock,
                $stockOut,
                'IN'
            );

            Log::info('Destination warehouse item updated', [
                'id' => $existingItem->id,
                'item_code' => $existingItem->item_code,
                'warehouse_id' => $warehouseId
            ]);

            return $existingItem;
        }

        $newItem = $sourceItem->replicate();
        $newItem->item_code = $sourceItem->item_code;
        $newItem->sku = $sourceItem->sku;
        $newItem->barcode = $sourceItem->barcode;
        $newItem->warehouse_id = $warehouseId;
        $newItem->store_id = null;
        $newItem->current_stock = $quantity;
        $newItem->available_stock = $quantity;
        $newItem->reserved_stock = 0;
        $newItem->opening_stock = 0;
        $newItem->opening_stock_value = 0;
        $newItem->created_by = auth()->id();
        $newItem->save();

        $this->createStockMovement(
            $newItem,
            [
                'quantity' => $quantity,
                'name' => $newItem->item_name
            ],
            0,
            $stockOut,
            'IN'
        );

        Log::info('New destination warehouse item created', [
            'id' => $newItem->id,
            'item_code' => $newItem->item_code,
            'warehouse_id' => $warehouseId
        ]);

        return $newItem;
    }

    private function checkDestinationCapacity($destinationType, $destinationId, $items)
    {
        $totalQuantity = 0;
        foreach ($items as $itemData) {
            $totalQuantity += $itemData['quantity'];
        }

        if ($destinationType === 'warehouse') {
            $destination = InventoryWarehouse::find($destinationId);
            if (!$destination) {
                throw new \Exception('Destination warehouse not found.');
            }
            
            $currentUtilization = InventoryItem::where('institute_id', $this->instituteId())
                ->where('warehouse_id', $destinationId)
                ->sum('current_stock') ?? 0;
            
            $capacity = $destination->capacity ?? 0;
            
            if ($capacity > 0) {
                $availableSpace = $capacity - $currentUtilization;
                if ($totalQuantity > $availableSpace) {
                    throw new \Exception(
                        "Destination warehouse '{$destination->warehouse_name}' does not have enough capacity. " .
                        "Available: {$availableSpace}, Required: {$totalQuantity}, Capacity: {$capacity}"
                    );
                }
            }
            
            return true;
            
        } elseif ($destinationType === 'store') {
            $destination = InventoryStore::find($destinationId);
            if (!$destination) {
                throw new \Exception('Destination store not found.');
            }
            
            $currentUtilization = InventoryItem::where('institute_id', $this->instituteId())
                ->where('store_id', $destinationId)
                ->sum('current_stock') ?? 0;
            
            $capacity = $destination->capacity ?? 0;
            
            if ($capacity > 0) {
                $availableSpace = $capacity - $currentUtilization;
                if ($totalQuantity > $availableSpace) {
                    throw new \Exception(
                        "Destination store '{$destination->store_name}' does not have enough capacity. " .
                        "Available: {$availableSpace}, Required: {$totalQuantity}, Capacity: {$capacity}"
                    );
                }
            }
            
            return true;
        }
        
        throw new \Exception('Invalid destination type.');
    }

    public function cancel($id)
    {
        try {
            $stockOut = InventoryStockOut::where('institute_id', $this->instituteId())
                ->whereIn('status', ['pending', 'approved'])
                ->findOrFail($id);

            $stockOut->update([
                'status' => 'cancelled'
            ]);

            InventoryLogger::log([
                'module' => 'STOCK_OUT',
                'action' => 'CANCEL',
                'record_id' => $stockOut->id,
                'remarks' => 'Stock Out cancelled: ' . $stockOut->stock_out_code
            ]);

            return redirect()
                ->route('inventory.stock-out.index')
                ->with('success', 'Stock Out cancelled successfully.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $stockOut = InventoryStockOut::where('institute_id', $this->instituteId())
                ->where('status', 'pending')
                ->findOrFail($id);

            $oldData = $stockOut->toArray();

            InventoryLogger::log([
                'module' => 'STOCK_OUT',
                'action' => 'DELETE',
                'record_id' => $stockOut->id,
                'old_data' => $oldData,
                'remarks' => 'Stock Out deleted: ' . $stockOut->stock_out_code
            ]);

            $stockOut->delete();

            return redirect()
                ->route('inventory.stock-out.index')
                ->with('success', 'Stock Out Deleted Successfully');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function print($id)
    {
        $stockOut = InventoryStockOut::where('institute_id', $this->instituteId())
            ->with([
                'fromWarehouse',
                'toWarehouse',
                'fromStore',
                'toStore',
                'creator',
                'approver',
                'receiver'
            ])
            ->findOrFail($id);
    
        if ($stockOut->type === 'sell') {
            return view(
                'instituteAdmin.inventory.stock-out.print-invoice',
                ['stockOut' => $stockOut]
            );
        } else {
            return view(
                'instituteAdmin.inventory.stock-out.print-transfer',
                ['stockOut' => $stockOut]
            );
        }
    }

    public function getItemDetails($id)
    {
        try {
            $item = InventoryItem::where('institute_id', $this->instituteId())
                ->with(['warehouse', 'store', 'category'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'id' => $item->id,
                'name' => $item->item_name,
                'code' => $item->item_code,
                'barcode' => $item->barcode,
                'current_stock' => $item->current_stock,
                'available_stock' => $item->available_stock,
                'selling_price' => $item->selling_price,
                'warehouse_id' => $item->warehouse_id,
                'warehouse_name' => $item->warehouse->warehouse_name ?? null,
                'store_id' => $item->store_id,
                'store_name' => $item->store->store_name ?? null,
                'track_batch' => $item->track_batch ?? false,
                'item_type' => $item->item_type ?? 'CONSUMABLE',
                'has_batches' => $item->batches()->where('remaining_quantity', '>', 0)->exists(),
                'batch_count' => $item->batches()->where('remaining_quantity', '>', 0)->count(),
                'has_assets' => $item->assetInstances()->where('status', 'ACTIVE')->exists(),
                'asset_count' => $item->assetInstances()->where('status', 'ACTIVE')->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get batches for a specific item
     */
    public function getItemBatches($id)
    {
        try {
            $item = InventoryItem::where('institute_id', $this->instituteId())
                ->findOrFail($id);

            $batches = $item->batches()
                ->where('remaining_quantity', '>', 0)
                ->orderBy('expiry_date', 'asc')
                ->get()
                ->map(function($batch) {
                    return [
                        'id' => $batch->id,
                        'batch_number' => $batch->batch_number,
                        'remaining_quantity' => $batch->remaining_quantity,
                        'expiry_date' => $batch->expiry_date ? $batch->expiry_date->format('Y-m-d') : null,
                        'manufacturing_date' => $batch->manufacturing_date ? $batch->manufacturing_date->format('Y-m-d') : null,
                        'is_expired' => $batch->expiry_date && $batch->expiry_date < now(),
                    ];
                });

            return response()->json([
                'success' => true,
                'batches' => $batches
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get serial numbers (asset instances) for a specific item
     */
    public function getItemAssets($id)
    {
        try {
            $item = InventoryItem::where('institute_id', $this->instituteId())
                ->findOrFail($id);

            $assets = $item->assetInstances()
                ->where('status', 'ACTIVE')
                ->get()
                ->map(function($asset) {
                    return [
                        'id' => $asset->id,
                        'asset_code' => $asset->asset_code,
                        'serial_number' => $asset->serial_number,
                        'status' => $asset->status,
                        'purchase_date' => $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : null,
                    ];
                });

            return response()->json([
                'success' => true,
                'assets' => $assets,
                'total_count' => $assets->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }
}