<?php

namespace App\Http\Controllers\institute\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\InventoryReceiptIn;
use App\Models\Inventory\InventoryItem;
use App\Models\Inventory\InventoryItemBatch;
use App\Models\Inventory\InventoryAssetInstance;
use App\Models\Inventory\InventoryWarehouse;
use App\Models\Inventory\InventoryStore;
use App\Models\Inventory\InventoryStockOut;
use App\Models\Inventory\InventoryStockMovement;
use App\Services\Inventory\InventoryLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryReceiptInController extends Controller
{
    private function instituteId()
    {
        return auth()->user()->institute_id;
    }

    private function generateReceiptNumber()
    {
        $prefix = 'IN';
        $date = now()->format('Ymd');
        $last = InventoryReceiptIn::where('institute_id', $this->instituteId())
            ->whereDate('created_at', now()->toDateString())
            ->count();

        return $prefix . '-' . $date . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }

    public function index()
    {
        $receipts = InventoryReceiptIn::where('institute_id', $this->instituteId())
            ->with(['item', 'warehouse', 'store', 'creator', 'approver', 'receiver'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total' => InventoryReceiptIn::where('institute_id', $this->instituteId())->count(),
            'draft' => InventoryReceiptIn::where('institute_id', $this->instituteId())->where('status', 'DRAFT')->count(),
            'approved' => InventoryReceiptIn::where('institute_id', $this->instituteId())->where('status', 'APPROVED')->count(),
            'completed' => InventoryReceiptIn::where('institute_id', $this->instituteId())->where('status', 'COMPLETED')->count(),
            'cancelled' => InventoryReceiptIn::where('institute_id', $this->instituteId())->where('status', 'CANCELLED')->count(),
        ];

        return view('instituteAdmin.inventory.receipt.in.index', compact('receipts', 'stats'));
    }

    public function create()
    {
        $items = InventoryItem::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->with(['warehouse', 'store', 'category'])
            ->get();

        $warehouses = InventoryWarehouse::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->get();

        $stores = InventoryStore::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->with('warehouse')
            ->get();

        return view('instituteAdmin.inventory.receipt.in.create', compact('items', 'warehouses', 'stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'warehouse_id' => 'nullable|exists:inventory_warehouses,id',
            'store_id' => 'nullable|exists:inventory_stores,id',
            'quantity' => 'required|numeric|min:0.01',
            'receipt_type' => 'required|in:PURCHASE,TRANSFER,RETURN',
            'supplier_name' => 'nullable|string|max:255',
            'supplier_invoice_number' => 'nullable|string|max:100',
            'batch_data' => 'nullable|array',
            'batch_data.*.batch_number' => 'nullable|string|max:100',
            'batch_data.*.quantity' => 'nullable|numeric|min:0',
            'batch_data.*.manufacturing_date' => 'nullable|date',
            'batch_data.*.expiry_date' => 'nullable|date|after:manufacturing_date',
            'serial_numbers' => 'nullable|array',
            'serial_numbers.*.serial_number' => 'nullable|string|max:100',
            'serial_numbers.*.asset_code' => 'nullable|string|max:100',
            'serial_numbers.*.purchase_date' => 'nullable|date',
            'serial_numbers.*.warranty_expiry' => 'nullable|date|after:purchase_date',
        ]);

        // Validate that either warehouse or store is selected
        if (!$request->warehouse_id && !$request->store_id) {
            return back()->withErrors([
                'location' => 'Please select either a warehouse or a store as the destination.'
            ])->withInput();
        }

        $item = InventoryItem::where('institute_id', $this->instituteId())->findOrFail($request->item_id);

        // Check destination capacity
        $this->validateDestinationCapacity($request);

        // Validate batch data if batch tracking is enabled
        if ($item->track_batch && $request->has('batch_data')) {
            $this->validateBatchData($request->batch_data, $request->quantity);
        }

        // Validate serial numbers if serial tracking is enabled
        if ($item->track_serial && $request->has('serial_numbers')) {
            $this->validateSerialData($request->serial_numbers, $request->quantity);
        }

        $receipt = InventoryReceiptIn::create([
            'institute_id' => $this->instituteId(),
            'receipt_number' => $this->generateReceiptNumber(),
            'item_id' => $request->item_id,
            'warehouse_id' => $request->warehouse_id,
            'store_id' => $request->store_id,
            'quantity' => $request->quantity,
            'unit_price' => $item->buying_price,
            'total_price' => $item->buying_price * $request->quantity,
            'receipt_type' => $request->receipt_type,
            'supplier_name' => $request->supplier_name,
            'supplier_invoice_number' => $request->supplier_invoice_number,
            'notes' => $request->notes,
            'status' => 'DRAFT',
            'created_by' => auth()->id()
        ]);

        // ✅ Handle batch data for receipt
        if ($item->track_batch && $request->has('batch_data') && !empty($request->batch_data)) {
            $this->handleBatchDataForReceipt($receipt, $item, $request->batch_data);
        }

        // ✅ Handle serial numbers for receipt
        if ($item->track_serial && $request->has('serial_numbers') && !empty($request->serial_numbers)) {
            $this->handleSerialDataForReceipt($receipt, $item, $request->serial_numbers);
        }

        // Check if this is a transfer-based receipt
        if ($request->receipt_type === 'TRANSFER' && $request->filled('reference_id')) {
            $this->linkTransferReceipt($receipt, $request->reference_id);
        }

        InventoryLogger::log([
            'module' => 'RECEIPT_IN',
            'action' => 'CREATE',
            'record_id' => $receipt->id,
            'new_data' => $receipt->toArray(),
            'remarks' => 'In receipt created: ' . $receipt->receipt_number
        ]);

        $message = 'In Receipt Created Successfully. Please approve to complete.';
        
        if ($receipt->store_id) {
            $message .= ' Destination: Store - ' . ($receipt->store->store_name ?? 'N/A');
        } else {
            $message .= ' Destination: Warehouse - ' . ($receipt->warehouse->warehouse_name ?? 'N/A');
        }

        return redirect()
            ->route('inventory.receipts.in.show', $receipt->id)
            ->with('success', $message);
    }

    /**
     * Validate batch data
     */
    private function validateBatchData($batchData, $totalQuantity)
    {
        $totalBatchQty = 0;
        foreach ($batchData as $batch) {
            if (!empty($batch['quantity'])) {
                $totalBatchQty += $batch['quantity'];
            }
        }

        if ($totalBatchQty != $totalQuantity) {
            throw new \Exception("Total batch quantity ({$totalBatchQty}) does not match receipt quantity ({$totalQuantity})");
        }

        // Validate expiry dates
        foreach ($batchData as $index => $batch) {
            if (!empty($batch['expiry_date']) && !empty($batch['manufacturing_date'])) {
                $mfgDate = \Carbon\Carbon::parse($batch['manufacturing_date']);
                $expDate = \Carbon\Carbon::parse($batch['expiry_date']);
                if ($expDate <= $mfgDate) {
                    throw new \Exception("Batch #" . ($index + 1) . ": Expiry date must be after manufacturing date.");
                }
            }
        }

        return true;
    }

    /**
     * Validate serial data
     */
    private function validateSerialData($serialData, $totalQuantity)
    {
        if (count($serialData) != $totalQuantity) {
            throw new \Exception("Number of serial numbers (" . count($serialData) . ") does not match receipt quantity ({$totalQuantity})");
        }

        // Check for duplicate serial numbers
        $serials = array_column($serialData, 'serial_number');
        if (count($serials) !== count(array_unique($serials))) {
            throw new \Exception("Duplicate serial numbers found.");
        }

        return true;
    }

    /**
     * Handle batch data for receipt
     */
    private function handleBatchDataForReceipt($receipt, $item, $batchData)
    {
        foreach ($batchData as $batch) {
            if (empty($batch['quantity']) || $batch['quantity'] <= 0) {
                continue;
            }

            $batchNumber = $batch['batch_number'] ?? 'BATCH-' . strtoupper(uniqid());
            $manufacturingDate = $batch['manufacturing_date'] ?? null;
            $expiryDate = $batch['expiry_date'] ?? null;

            // Check if batch already exists
            $existingBatch = InventoryItemBatch::where('item_id', $item->id)
                ->where('batch_number', $batchNumber)
                ->where('warehouse_id', $receipt->warehouse_id)
                ->where('store_id', $receipt->store_id)
                ->first();

            if ($existingBatch) {
                // Add to existing batch
                $existingBatch->quantity += $batch['quantity'];
                $existingBatch->remaining_quantity += $batch['quantity'];
                $existingBatch->save();

                Log::info('Updated existing batch from receipt', [
                    'batch_number' => $batchNumber,
                    'added_quantity' => $batch['quantity']
                ]);
            } else {
                // Create new batch
                InventoryItemBatch::create([
                    'institute_id' => $this->instituteId(),
                    'item_id' => $item->id,
                    'warehouse_id' => $receipt->warehouse_id,
                    'store_id' => $receipt->store_id,
                    'batch_number' => $batchNumber,
                    'quantity' => $batch['quantity'],
                    'remaining_quantity' => $batch['quantity'],
                    'manufacturing_date' => $manufacturingDate,
                    'expiry_date' => $expiryDate,
                    'purchase_price' => $item->buying_price,
                    'created_by' => auth()->id(),
                ]);

                Log::info('Created new batch from receipt', [
                    'batch_number' => $batchNumber,
                    'quantity' => $batch['quantity']
                ]);
            }
        }
    }

    /**
     * Handle serial data for receipt
     */
    private function handleSerialDataForReceipt($receipt, $item, $serialData)
    {
        foreach ($serialData as $serial) {
            if (empty($serial['serial_number'])) {
                continue;
            }

            $assetCode = $serial['asset_code'] ?? $item->generateAssetCode();
            $purchaseDate = $serial['purchase_date'] ?? now();
            $warrantyExpiry = $serial['warranty_expiry'] ?? null;

            // Check if serial already exists
            $existingAsset = InventoryAssetInstance::where('item_id', $item->id)
                ->where('serial_number', $serial['serial_number'])
                ->first();

            if ($existingAsset) {
                // Update existing asset
                $existingAsset->update([
                    'status' => 'ACTIVE',
                    'warehouse_id' => $receipt->warehouse_id,
                    'store_id' => $receipt->store_id,
                    'purchase_date' => $purchaseDate,
                    'warranty_expiry' => $warrantyExpiry,
                    'buying_price' => $item->buying_price,
                    'updated_by' => auth()->id(),
                ]);

                Log::info('Updated existing asset from receipt', [
                    'serial_number' => $serial['serial_number']
                ]);
            } else {
                // Create new asset
                InventoryAssetInstance::create([
                    'institute_id' => $this->instituteId(),
                    'item_id' => $item->id,
                    'warehouse_id' => $receipt->warehouse_id,
                    'store_id' => $receipt->store_id,
                    'asset_code' => $assetCode,
                    'serial_number' => $serial['serial_number'],
                    'status' => 'ACTIVE',
                    'purchase_date' => $purchaseDate,
                    'warranty_expiry' => $warrantyExpiry,
                    'buying_price' => $item->buying_price,
                    'created_by' => auth()->id(),
                ]);

                Log::info('Created new asset from receipt', [
                    'serial_number' => $serial['serial_number'],
                    'asset_code' => $assetCode
                ]);
            }
        }
    }

    /**
     * Link a transfer-based receipt to its source transfer
     */
    private function linkTransferReceipt($receipt, $referenceId)
    {
        $transfer = InventoryStockOut::where('institute_id', $this->instituteId())
            ->where('id', $referenceId)
            ->where('type', 'transfer')
            ->first();

        if ($transfer) {
            // Update receipt with reference
            $receipt->update([
                'reference_type' => InventoryStockOut::class,
                'reference_id' => $transfer->id,
                'supplier_name' => 'Transfer from ' . ($transfer->fromWarehouse->warehouse_name ?? 'N/A'),
                'notes' => ($receipt->notes ?? '') . ' | Transfer: ' . $transfer->stock_out_code,
            ]);

            InventoryLogger::log([
                'module' => 'RECEIPT_IN',
                'action' => 'LINK_TRANSFER',
                'record_id' => $receipt->id,
                'new_data' => [
                    'receipt_number' => $receipt->receipt_number,
                    'transfer_id' => $transfer->id,
                    'transfer_code' => $transfer->stock_out_code,
                ],
                'remarks' => 'Receipt linked to transfer: ' . $transfer->stock_out_code
            ]);
        }
    }

    /**
     * Validate destination capacity (warehouse or store)
     */
    private function validateDestinationCapacity($request)
    {
        if ($request->warehouse_id) {
            $warehouse = InventoryWarehouse::where('institute_id', $this->instituteId())
                ->findOrFail($request->warehouse_id);
            
            if (!$warehouse->canAcceptItems($request->quantity)) {
                throw new \Exception(
                    "Warehouse is at capacity. " .
                    "Current: {$warehouse->current_utilization}, " .
                    "Required: {$request->quantity}, " .
                    "Capacity: {$warehouse->capacity}"
                );
            }
        }

        if ($request->store_id) {
            $store = InventoryStore::where('institute_id', $this->instituteId())
                ->findOrFail($request->store_id);
            
            if (!$store->canAcceptItems($request->quantity)) {
                throw new \Exception(
                    "Store is at capacity. " .
                    "Current: {$store->current_utilization}, " .
                    "Required: {$request->quantity}, " .
                    "Capacity: {$store->capacity}"
                );
            }
        }
    }

    public function show($id)
    {
        $receipt = InventoryReceiptIn::where('institute_id', $this->instituteId())
            ->with([
                'item' => function($query) {
                    $query->with(['category', 'subcategory', 'warehouse', 'store']);
                },
                'warehouse',
                'store',
                'creator',
                'approver',
                'receiver'
            ])
            ->findOrFail($id);

        $otherLocations = collect();
        if ($receipt->item) {
            // Find same item in other warehouses
            $otherWarehouses = InventoryItem::where('institute_id', $this->instituteId())
                ->where('item_code', $receipt->item->item_code)
                ->where('id', '!=', $receipt->item_id)
                ->whereNotNull('warehouse_id')
                ->with('warehouse')
                ->get(['id', 'warehouse_id', 'current_stock', 'available_stock']);
            
            // Find same item in other stores
            $otherStores = InventoryItem::where('institute_id', $this->instituteId())
                ->where('item_code', $receipt->item->item_code)
                ->where('id', '!=', $receipt->item_id)
                ->whereNotNull('store_id')
                ->with('store')
                ->get(['id', 'store_id', 'current_stock', 'available_stock']);
            
            $otherLocations = $otherWarehouses->merge($otherStores);
        }

        $receiptHistory = InventoryReceiptIn::where('institute_id', $this->instituteId())
            ->where('item_id', $receipt->item_id)
            ->where('id', '!=', $receipt->id)
            ->where('status', 'COMPLETED')
            ->latest()
            ->limit(5)
            ->get();

        return view('instituteAdmin.inventory.receipt.in.show', compact(
            'receipt',
            'otherLocations',
            'receiptHistory'
        ));
    }

    public function edit($id)
    {
        $receipt = InventoryReceiptIn::where('institute_id', $this->instituteId())
            ->where('status', 'DRAFT')
            ->findOrFail($id);

        $items = InventoryItem::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->with(['warehouse', 'store', 'category'])
            ->get();

        $warehouses = InventoryWarehouse::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->get();

        $stores = InventoryStore::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->with('warehouse')
            ->get();

        return view('instituteAdmin.inventory.receipt.in.edit', compact('receipt', 'items', 'warehouses', 'stores'));
    }

    public function update(Request $request, $id)
    {
        $receipt = InventoryReceiptIn::where('institute_id', $this->instituteId())
            ->where('status', 'DRAFT')
            ->findOrFail($id);

        $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'warehouse_id' => 'nullable|exists:inventory_warehouses,id',
            'store_id' => 'nullable|exists:inventory_stores,id',
            'quantity' => 'required|numeric|min:0.01',
            'receipt_type' => 'required|in:PURCHASE,TRANSFER,RETURN',
            'supplier_name' => 'nullable|string|max:255',
            'supplier_invoice_number' => 'nullable|string|max:100',
        ]);

        // Validate that either warehouse or store is selected
        if (!$request->warehouse_id && !$request->store_id) {
            return back()->withErrors([
                'location' => 'Please select either a warehouse or a store as the destination.'
            ])->withInput();
        }

        DB::transaction(function() use ($request, $receipt) {
            $item = InventoryItem::where('institute_id', $this->instituteId())->findOrFail($request->item_id);

            $oldData = $receipt->toArray();

            // Check capacity if location or quantity changed
            if ($receipt->warehouse_id != $request->warehouse_id || 
                $receipt->store_id != $request->store_id || 
                $receipt->quantity != $request->quantity) {
                
                $this->validateDestinationCapacity($request);
            }

            $receipt->update([
                'item_id' => $request->item_id,
                'warehouse_id' => $request->warehouse_id,
                'store_id' => $request->store_id,
                'quantity' => $request->quantity,
                'unit_price' => $item->buying_price,
                'total_price' => $item->buying_price * $request->quantity,
                'receipt_type' => $request->receipt_type,
                'supplier_name' => $request->supplier_name,
                'supplier_invoice_number' => $request->supplier_invoice_number,
                'notes' => $request->notes,
            ]);

            InventoryLogger::log([
                'module' => 'RECEIPT_IN',
                'action' => 'UPDATE',
                'record_id' => $receipt->id,
                'old_data' => $oldData,
                'new_data' => $receipt->fresh()->toArray(),
                'remarks' => 'In receipt updated: ' . $receipt->receipt_number
            ]);
        });

        return redirect()
            ->route('inventory.receipts.in.show', $receipt->id)
            ->with('success', 'In Receipt Updated Successfully');
    }

    public function approve($id)
    {
        $receipt = InventoryReceiptIn::where('institute_id', $this->instituteId())
            ->where('status', 'DRAFT')
            ->findOrFail($id);

        try {
            // Check destination capacity
            $this->validateReceiptCapacity($receipt);

            $receipt->approve();

            return redirect()
                ->route('inventory.receipts.in.show', $receipt->id)
                ->with('success', 'Receipt approved. You can now complete it to update stock.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Validate receipt capacity before approval/complete
     */
    private function validateReceiptCapacity($receipt)
    {
        if ($receipt->warehouse_id) {
            $warehouse = InventoryWarehouse::where('institute_id', $this->instituteId())
                ->findOrFail($receipt->warehouse_id);
            
            if (!$warehouse->canAcceptItems($receipt->quantity)) {
                throw new \Exception(
                    "Warehouse is at capacity. " .
                    "Current: {$warehouse->current_utilization}, " .
                    "Required: {$receipt->quantity}, " .
                    "Capacity: {$warehouse->capacity}"
                );
            }
        }

        if ($receipt->store_id) {
            $store = InventoryStore::where('institute_id', $this->instituteId())
                ->findOrFail($receipt->store_id);
            
            if (!$store->canAcceptItems($receipt->quantity)) {
                throw new \Exception(
                    "Store is at capacity. " .
                    "Current: {$store->current_utilization}, " .
                    "Required: {$receipt->quantity}, " .
                    "Capacity: {$store->capacity}"
                );
            }
        }
    }

    /**
     * COMPLETE - With location path saving and stock movement for transfer receipts
     */
    public function complete($id)
    {
        $receipt = InventoryReceiptIn::where('institute_id', $this->instituteId())
            ->where('status', 'APPROVED')
            ->findOrFail($id);

        try {
            // Check capacity before completing
            $this->validateReceiptCapacity($receipt);

            // ✅ Check if this is a transfer-based receipt
            $isTransferReceipt = $receipt->receipt_type === 'TRANSFER' && 
                $receipt->reference_type === InventoryStockOut::class &&
                $receipt->reference_id;

            if ($isTransferReceipt) {
                // This is a transfer-based receipt - link to the transfer
                $transfer = InventoryStockOut::where('institute_id', $this->instituteId())
                    ->find($receipt->reference_id);

                if ($transfer && $transfer->type === 'transfer') {
                    // Build location paths
                    $fromLocationPath = $this->buildLocationPath(
                        $transfer->from_warehouse_id,
                        $transfer->from_store_id
                    );
                    $toLocationPath = $this->buildLocationPath(
                        $transfer->to_warehouse_id,
                        $transfer->to_store_id
                    );

                    // Update transfer status with location paths
                    $transfer->update([
                        'status' => 'completed',
                        'received_by' => auth()->id(),
                        'received_at' => now(),
                        'from_location_path' => $fromLocationPath,
                        'to_location_path' => $toLocationPath,
                    ]);

                    // ✅ Create stock movement for transfer receipt
                    $this->createStockMovementForTransferReceipt($receipt, $transfer);

                    InventoryLogger::log([
                        'module' => 'RECEIPT_IN',
                        'action' => 'TRANSFER_COMPLETED',
                        'record_id' => $receipt->id,
                        'new_data' => [
                            'receipt_number' => $receipt->receipt_number,
                            'transfer_id' => $transfer->id,
                            'transfer_code' => $transfer->stock_out_code,
                            'quantity' => $receipt->quantity,
                            'from_location_path' => $fromLocationPath,
                            'to_location_path' => $toLocationPath,
                        ],
                        'remarks' => 'Transfer completed via receipt: ' . $receipt->receipt_number
                    ]);
                }
            }

            // ✅ Save location path for receipt
            $locationPath = $this->buildLocationPath(
                $receipt->warehouse_id,
                $receipt->store_id
            );
            $receipt->update([
                'location_path' => $locationPath,
            ]);

            // Complete the receipt (this will update stock)
            $receipt->complete();

            // ✅ Create stock movement for receipt completion
            $this->createStockMovementForReceipt($receipt);

            return redirect()
                ->route('inventory.receipts.in.show', $receipt->id)
                ->with('success', 'Receipt completed successfully. Stock has been updated.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Build location path from warehouse and store IDs
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

    /**
     * Create stock movement for transfer receipt
     */
    private function createStockMovementForTransferReceipt($receipt, $transfer)
    {
        $item = InventoryItem::where('institute_id', $this->instituteId())
            ->find($receipt->item_id);

        if (!$item) {
            return;
        }

        // Create IN stock movement
        InventoryStockMovement::create([
            'institute_id' => $this->instituteId(),
            'item_id' => $receipt->item_id,
            'warehouse_id' => $receipt->warehouse_id,
            'store_id' => $receipt->store_id,
            'movement_type' => 'TRANSFER_IN',
            'quantity' => $receipt->quantity,
            'previous_stock' => $item->current_stock - $receipt->quantity,
            'new_stock' => $item->current_stock,
            'unit_cost' => $receipt->unit_price ?? $item->buying_price ?? 0,
            'total_cost' => ($receipt->unit_price ?? $item->buying_price ?? 0) * $receipt->quantity,
            'reference_type' => InventoryStockOut::class,
            'reference_id' => $transfer->id,
            'notes' => "Transfer receipt: {$receipt->receipt_number} - Transfer: {$transfer->stock_out_code}",
            'created_by' => auth()->id(),
        ]);

        Log::info('Stock movement created for transfer receipt', [
            'receipt_id' => $receipt->id,
            'transfer_id' => $transfer->id,
            'quantity' => $receipt->quantity
        ]);
    }

    /**
     * Create stock movement for receipt completion
     */
    private function createStockMovementForReceipt($receipt)
    {
        $item = InventoryItem::where('institute_id', $this->instituteId())
            ->find($receipt->item_id);

        if (!$item) {
            return;
        }

        // Create IN stock movement
        InventoryStockMovement::create([
            'institute_id' => $this->instituteId(),
            'item_id' => $receipt->item_id,
            'warehouse_id' => $receipt->warehouse_id,
            'store_id' => $receipt->store_id,
            'movement_type' => 'IN',
            'quantity' => $receipt->quantity,
            'previous_stock' => $item->current_stock - $receipt->quantity,
            'new_stock' => $item->current_stock,
            'unit_cost' => $receipt->unit_price ?? $item->buying_price ?? 0,
            'total_cost' => ($receipt->unit_price ?? $item->buying_price ?? 0) * $receipt->quantity,
            'reference_type' => InventoryReceiptIn::class,
            'reference_id' => $receipt->id,
            'notes' => "Receipt IN: {$receipt->receipt_number}",
            'created_by' => auth()->id(),
        ]);

        Log::info('Stock movement created for receipt completion', [
            'receipt_id' => $receipt->id,
            'quantity' => $receipt->quantity
        ]);
    }

    /**
     * Get location path for transfer (legacy method)
     */
    private function getLocationPath($transfer, $direction)
    {
        $parts = [];
        
        if ($direction === 'from') {
            if ($transfer->fromWarehouse) {
                $parts[] = $transfer->fromWarehouse->warehouse_name;
            }
            if ($transfer->fromStore) {
                $parts[] = $transfer->fromStore->store_name;
            }
        } else {
            if ($transfer->toWarehouse) {
                $parts[] = $transfer->toWarehouse->warehouse_name;
            }
            if ($transfer->toStore) {
                $parts[] = $transfer->toStore->store_name;
            }
        }
        
        return implode(' → ', $parts) ?: 'N/A';
    }

    public function cancel($id)
    {
        $receipt = InventoryReceiptIn::where('institute_id', $this->instituteId())
            ->whereIn('status', ['DRAFT', 'APPROVED'])
            ->findOrFail($id);

        try {
            $receipt->cancel();

            return redirect()
                ->route('inventory.receipts.in.index')
                ->with('success', 'Receipt cancelled successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $receipt = InventoryReceiptIn::where('institute_id', $this->instituteId())
            ->where('status', 'DRAFT')
            ->findOrFail($id);

        $oldData = $receipt->toArray();

        InventoryLogger::log([
            'module' => 'RECEIPT_IN',
            'action' => 'DELETE',
            'record_id' => $receipt->id,
            'old_data' => $oldData,
            'remarks' => 'In receipt deleted: ' . $receipt->receipt_number
        ]);

        $receipt->delete();

        return redirect()
            ->route('inventory.receipts.in.index')
            ->with('success', 'In Receipt Deleted Successfully');
    }

    public function print($id)
    {
        $receipt = InventoryReceiptIn::where('institute_id', $this->instituteId())
            ->with([
                'item' => function($query) {
                    $query->with(['category', 'subcategory', 'warehouse', 'store']);
                },
                'warehouse',
                'store',
                'creator',
                'approver',
                'receiver'
            ])
            ->findOrFail($id);

        // Increment print count and log
        $receipt->incrementPrintCount();

        return view('instituteAdmin.inventory.receipt.in.print', compact('receipt'));
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
            'buying_price' => $item->buying_price,
            'selling_price' => $item->selling_price,
            'current_stock' => $item->current_stock,
            'available_stock' => $item->available_stock,
            'warehouse_id' => $item->warehouse_id,
            'warehouse_name' => $item->warehouse->warehouse_name ?? null,
            'store_id' => $item->store_id,
            'store_name' => $item->store->store_name ?? null,
            'category_name' => $item->category->category_name ?? null,
        ]);
    }

    public function getStatusCounts()
    {
        $counts = [
            'total' => InventoryReceiptIn::where('institute_id', $this->instituteId())->count(),
            'draft' => InventoryReceiptIn::where('institute_id', $this->instituteId())->where('status', 'DRAFT')->count(),
            'approved' => InventoryReceiptIn::where('institute_id', $this->instituteId())->where('status', 'APPROVED')->count(),
            'completed' => InventoryReceiptIn::where('institute_id', $this->instituteId())->where('status', 'COMPLETED')->count(),
            'cancelled' => InventoryReceiptIn::where('institute_id', $this->instituteId())->where('status', 'CANCELLED')->count(),
        ];

        return response()->json($counts);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        $receipts = InventoryReceiptIn::where('institute_id', $this->instituteId())
            ->where(function($q) use ($query) {
                $q->where('receipt_number', 'LIKE', "%{$query}%")
                    ->orWhereHas('item', function($itemQuery) use ($query) {
                        $itemQuery->where('item_name', 'LIKE', "%{$query}%")
                            ->orWhere('item_code', 'LIKE', "%{$query}%");
                    });
            })
            ->with(['item', 'warehouse', 'store'])
            ->limit(10)
            ->get();

        return response()->json($receipts);
    }

    /**
     * Get transfer details for linking
     */
    public function getTransferDetails($transferId)
    {
        try {
            $transfer = InventoryStockOut::where('institute_id', $this->instituteId())
                ->where('type', 'transfer')
                ->whereIn('status', ['approved', 'in-transit', 'completed'])
                ->with(['fromWarehouse', 'toWarehouse', 'fromStore', 'toStore'])
                ->findOrFail($transferId);

            // Check if transfer already has a receipt
            $existingReceipt = InventoryReceiptIn::where('institute_id', $this->instituteId())
                ->where('reference_type', InventoryStockOut::class)
                ->where('reference_id', $transfer->id)
                ->exists();

            return response()->json([
                'success' => true,
                'transfer' => [
                    'id' => $transfer->id,
                    'code' => $transfer->stock_out_code,
                    'from_location' => $transfer->fromWarehouse->warehouse_name ?? $transfer->fromStore->store_name ?? 'N/A',
                    'to_location' => $transfer->toWarehouse->warehouse_name ?? $transfer->toStore->store_name ?? 'N/A',
                    'quantity' => is_array($transfer->items) ? array_sum(array_column($transfer->items, 'quantity')) : 0,
                    'status' => $transfer->status,
                    'has_receipt' => $existingReceipt,
                ],
                'items' => $transfer->items,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Transfer not found: ' . $e->getMessage()
            ], 404);
        }
    }
}