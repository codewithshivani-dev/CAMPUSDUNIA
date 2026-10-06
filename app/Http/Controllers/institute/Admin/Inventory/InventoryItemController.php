<?php

namespace App\Http\Controllers\institute\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Inventory\InventoryItem;
use App\Models\Inventory\InventoryItemBatch;
use App\Models\Inventory\InventoryCategory;
use App\Models\Inventory\InventorySubCategory;
use App\Models\Inventory\InventoryWarehouse;
use App\Models\Inventory\InventoryStore;
use App\Models\Inventory\InventoryStockOut;
use App\Models\Inventory\InventoryReceiptIn;
use App\Models\Inventory\InventoryReceiptOut;
use App\Models\Inventory\InventoryStockMovement;
use App\Models\Inventory\InventoryAssetInstance;
use App\Models\Inventory\InventoryItemUnit;
use App\Models\Inventory\TaxSlab;
use App\Models\Inventory\InventoryDepreciationLog;
use App\Models\Inventory\InventoryVendor;
use App\Services\Inventory\InventoryLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryItemController extends Controller
{
    private function instituteId()
    {
        return auth()->user()->institute_id;
    }

    private function branchId()
    {
        return auth()->user()->branch_id ?? session('current_branch_id');
    }

    // ============================================================
    // UNIT OPTIONS (Predefined)
    // ============================================================
    private function getUnitOptions()
    {
        return [
            ['id' => 'PC', 'code' => 'PC', 'name' => 'Piece'],
            ['id' => 'KG', 'code' => 'KG', 'name' => 'Kilogram'],
            ['id' => 'GM', 'code' => 'GM', 'name' => 'Gram'],
            ['id' => 'LTR', 'code' => 'LTR', 'name' => 'Litre'],
            ['id' => 'ML', 'code' => 'ML', 'name' => 'Millilitre'],
            ['id' => 'BOX', 'code' => 'BOX', 'name' => 'Box'],
            ['id' => 'PACK', 'code' => 'PACK', 'name' => 'Pack'],
            ['id' => 'CARTON', 'code' => 'CARTON', 'name' => 'Carton'],
            ['id' => 'ROLL', 'code' => 'ROLL', 'name' => 'Roll'],
            ['id' => 'MTR', 'code' => 'MTR', 'name' => 'Metre'],
            ['id' => 'FT', 'code' => 'FT', 'name' => 'Feet'],
            ['id' => 'DOZ', 'code' => 'DOZ', 'name' => 'Dozen'],
            ['id' => 'PAIR', 'code' => 'PAIR', 'name' => 'Pair'],
            ['id' => 'BUNDLE', 'code' => 'BUNDLE', 'name' => 'Bundle'],
            ['id' => 'SET', 'code' => 'SET', 'name' => 'Set'],
        ];
    }

    // ============================================================
    // INDEX - List all items
    // ============================================================
    public function index()
    {
        $items = InventoryItem::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->with([
                'category',
                'subcategory',
                'vendor',
                'store' => function ($query) {
                    $query->with('warehouse');
                },
                'warehouse',
            ])
            ->latest()
            ->paginate(20);

        $categories = InventoryCategory::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->get();
        $warehouses = InventoryWarehouse::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->get();

        return view('instituteAdmin.inventory.item.index', compact('items', 'categories', 'warehouses'));
    }

    // ============================================================
    // CREATE - Show creation form
    // ============================================================
    public function create()
    {
        $categories = InventoryCategory::with(['hsnCode', 'taxSlab'])
            ->where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->where('status', 1)
            ->get();
    
        $categoriesWithData = $categories->map(function($category) {
            return [
                'id' => $category->id,
                'category_name' => $category->category_name,
                'category_code' => $category->category_code,
                'hsn_code' => $category->hsnCode ? $category->hsnCode->hsn_code : null,
                'hsn_description' => $category->hsnCode ? $category->hsnCode->description : null,
                'tax_rate' => $category->taxSlab ? $category->taxSlab->tax_rate : ($category->gst_rate ?? 0),
                'tax_name' => $category->taxSlab ? $category->taxSlab->tax_name : null,
                'tax_code' => $category->taxSlab ? $category->taxSlab->tax_code : null,
                'is_gst_applicable' => $category->is_gst_applicable ?? 0,
                'gst_rate' => $category->gst_rate ?? 0,
            ];
        });
    
        $warehouses = InventoryWarehouse::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->where('status', 1)
            ->get();
    
        $stores = InventoryStore::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->where('status', 1)
            ->get();
    
        $unitOptions = $this->getUnitOptions();
    
        $vendors = InventoryVendor::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->where('status', 1)
            ->orderBy('vendor_name', 'asc')
            ->get();
    
        return view('instituteAdmin.inventory.item.create', compact(
            'categoriesWithData',
            'warehouses',
            'stores',
            'unitOptions',
            'vendors'
        ));
    }

    // ============================================================
    // MAIN STORE METHOD - Handles both modes
    // ============================================================
    public function store(Request $request)
    {
        try {
            if ($request->has('mode') && $request->mode === 'transfer') {
                return $this->storeTransferStockIn($request);
            }
            
            if ($request->has('mode') && $request->mode === 'bulk') {
                return $this->storeBulkItems($request);
            }
            
            return $this->storeSingleItem($request);
            
        } catch (\Exception $e) {
            Log::error('InventoryItemController@store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function storeSingleItem(Request $request)
    {
        return $this->storeStep1($request);
    }

    // ============================================================
    // STEP 1: CLASSIFICATION
    // ============================================================
    public function storeStep1(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:inventory_categories,id',
            'subcategory_id' => 'nullable|exists:inventory_sub_categories,id',
            'warehouse_id' => 'required|exists:inventory_warehouses,id',
            'store_id' => 'nullable|exists:inventory_stores,id',
            'rack_number' => 'nullable|max:50',
            'shelf_number' => 'nullable|max:50',
            'bin_number' => 'nullable|max:50',
            'unit_id' => 'required|string|max:50',
            'unit_name' => 'required|string|max:100',
            'unit_code' => 'required|string|max:20',
            'hsn_code' => 'nullable|string|max:50',
        ]);

        $category = InventoryCategory::with(['hsnCode', 'taxSlab'])->findOrFail($request->category_id);
        $subcategory = $request->subcategory_id ? InventorySubCategory::find($request->subcategory_id) : null;

        $hsnCode = $request->hsn_code ?: ($category->hsnCode ? $category->hsnCode->hsn_code : null);
        $taxSlab = $category->taxSlab;
        $taxPercentage = $taxSlab ? $taxSlab->tax_rate : ($category->gst_rate ?? 0);

        $itemCode = $this->generateItemCode($category, $subcategory);
        $sku = $this->generateSku($request->item_name ?? 'ITEM');

        $item = InventoryItem::create([
            'institute_id' => $this->instituteId(),
            'branch_id' => $this->branchId(),
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'warehouse_id' => $request->warehouse_id,
            'store_id' => $request->store_id,
            'unit_id' => $request->unit_id,
            'unit_name' => $request->unit_name,
            'unit_code' => $request->unit_code,
            'rack_number' => $request->rack_number,
            'shelf_number' => $request->shelf_number,
            'bin_number' => $request->bin_number,
            'item_code' => $itemCode,
            'sku' => $sku,
            'hsn_code' => $hsnCode,
            'tax_percentage' => $taxPercentage,
            'status' => 1,
            'created_by' => auth()->id(),
        ]);

        // Save additional units
        if ($request->has('additional_units')) {
            $additionalUnits = is_string($request->additional_units) 
                ? json_decode($request->additional_units, true) 
                : $request->additional_units;
            
            if (is_array($additionalUnits)) {
                foreach ($additionalUnits as $unitData) {
                    if (!empty($unitData['unit_id']) && !empty($unitData['conversion_rate'])) {
                        InventoryItemUnit::create([
                            'institute_id' => $this->instituteId(),
                            'branch_id' => $this->branchId(),
                            'item_id' => $item->id,
                            'unit_id' => $unitData['unit_id'],
                            'unit_name' => $unitData['unit_name'] ?? $unitData['unit_id'],
                            'unit_code' => $unitData['unit_code'] ?? $unitData['unit_id'],
                            'conversion_rate' => $unitData['conversion_rate'],
                            'is_base' => false,
                            'created_by' => auth()->id(),
                        ]);
                    }
                }
            }
        }

        InventoryLogger::log([
            'module' => 'ITEM',
            'action' => 'CREATE_STEP1',
            'record_id' => $item->id,
            'new_data' => $item->toArray(),
            'remarks' => 'Step 1: Classification completed'
        ]);

        return response()->json([
            'success' => true,
            'item_id' => $item->id,
            'tax_percentage' => $taxPercentage,
            'hsn_code' => $hsnCode,
            'tax_slab' => $taxSlab ? $taxSlab->toArray() : null,
            'message' => 'Step 1 completed successfully'
        ]);
    }

    // ============================================================
    // STEP 2: ITEM DETAILS & PRICING
    // ============================================================
    public function storeStep2(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'item_name' => 'required|string|max:255',
            'item_type' => 'required|in:CONSUMABLE,NON_CONSUMABLE,ASSET,SERVICE,RAW_MATERIAL,FINISHED_GOOD',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'manufacturer_part_number' => 'nullable|string|max:255',
            'hsn_code' => 'nullable|string|max:50',
            'barcode' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'vendor_id' => 'nullable|string|max:50',
            'mrp' => 'nullable|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'landing_cost' => 'nullable|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'opening_stock' => 'nullable|numeric|min:0',
            'custom_fields' => 'nullable|string',
        ]);
    
        $item = InventoryItem::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->findOrFail($request->item_id);
    
        $oldData = $item->toArray();
    
        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('inventory/items', 'public');
        }
    
        // Generate QR code if not provided
        $qrCode = $request->qr_code;
        if (empty($qrCode)) {
            $qrCode = $this->generateQrCode($item);
        }
    
        // Handle custom fields
        $customFields = [];
        if ($request->has('custom_fields')) {
            $customFields = is_string($request->custom_fields) 
                ? json_decode($request->custom_fields, true) 
                : $request->custom_fields;
        }
    
        // Calculate opening stock value
        $openingStock = (float)($request->opening_stock ?? 0);
        $openingStockValue = $openingStock * ($request->buying_price ?? 0);
    
        // Calculate selling price with discount if needed
        $sellingPrice = (float)$request->selling_price;
        $discountPercent = (float)($request->discount_percent ?? 0);
        if ($discountPercent > 0) {
            $discountAmount = ($sellingPrice * $discountPercent) / 100;
            $sellingPrice = $sellingPrice - $discountAmount;
        }
    
        $item->update([
            'item_name' => $request->item_name,
            'item_type' => $request->item_type,
            'brand' => $request->brand,
            'model' => $request->model,
            'manufacturer' => $request->manufacturer,
            'manufacturer_part_number' => $request->manufacturer_part_number,
            'hsn_code' => $request->hsn_code ?? $item->hsn_code,
            'barcode' => $request->barcode,
            'qr_code' => $qrCode,
            'description' => $request->description,
            'image' => $imagePath ?? $item->image,
            'vendor_id' => $request->vendor_id,
            'buying_price' => (float)$request->buying_price,
            'selling_price' => (float)$sellingPrice,
            'discount_percent' => $discountPercent,
            'mrp' => (float)($request->mrp ?? 0),
            'wholesale_price' => (float)($request->wholesale_price ?? 0),
            'landing_cost' => (float)($request->landing_cost ?? 0),
            'min_stock' => (float)($request->min_stock ?? 0),
            'opening_stock' => $openingStock,
            'opening_stock_value' => $openingStockValue,
            'current_stock' => $openingStock,
            'available_stock' => $openingStock,
            'reserved_stock' => 0,
            'custom_fields' => $customFields,
            'updated_by' => auth()->id(),
        ]);
    
        InventoryLogger::log([
            'module' => 'ITEM',
            'action' => 'CREATE_STEP2',
            'record_id' => $item->id,
            'old_data' => $oldData,
            'new_data' => $item->fresh()->toArray(),
            'remarks' => 'Step 2: Item details, pricing & vendor completed'
        ]);
    
        return response()->json([
            'success' => true,
            'item_id' => $item->id,
            'qr_code' => $qrCode,
            'message' => 'Step 2 completed successfully'
        ]);
    }

    // ============================================================
    // STEP 3: TRACKING (Batch, Serial, Expiry, Asset Depreciation)
    // ============================================================
    public function storeStep3(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'track_batch' => 'nullable|boolean',
            'track_serial' => 'nullable|boolean',
            'track_expiry' => 'nullable|boolean',
            'shelf_life_days' => 'nullable|integer|min:0',
            'manufacturing_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:manufacturing_date',
            'batch_number' => 'nullable|string|max:100',
            'batch_manufacturing_date' => 'nullable|date',
            'batch_expiry_date' => 'nullable|date|after:batch_manufacturing_date',
            'batch_remarks' => 'nullable|string|max:500',
            'serial_numbers' => 'nullable|string',
            'depreciation_applicable' => 'nullable|boolean',
            'asset_life_months' => 'nullable|integer|min:0',
            'depreciation_method' => 'nullable|in:straight_line,declining_balance,sum_of_years',
            'salvage_value' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'weight_unit' => 'nullable|string|max:10',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'dimension_unit' => 'nullable|string|max:10',
            'reorder_level' => 'nullable|numeric|min:0',
            'reorder_quantity' => 'nullable|numeric|min:0',
            'max_stock' => 'nullable|numeric|min:0',
            'status' => 'nullable|boolean',
        ]);

        $item = InventoryItem::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->findOrFail($request->item_id);

        $oldData = $item->toArray();
        $openingStock = (float)$item->opening_stock;

        // If expiry is not tracked, clear expiry fields
        $expiryDate = $request->track_expiry ? $request->expiry_date : null;
        $manufacturingDate = $request->track_expiry ? $request->manufacturing_date : null;
        $shelfLifeDays = $request->track_expiry ? $request->shelf_life_days : null;

        // If batch is not tracked, clear batch fields
        $batchNumber = $request->track_batch ? $request->batch_number : null;

        // If serial is not tracked, clear serial fields
        $serialNumber = $request->track_serial ? $request->serial_number : null;

        // If item is not ASSET, clear depreciation fields
        $depreciationApplicable = ($item->item_type === 'ASSET' && $request->depreciation_applicable) ? 1 : 0;
        $assetLifeMonths = $depreciationApplicable ? $request->asset_life_months : null;
        $depreciationMethod = $depreciationApplicable ? ($request->depreciation_method ?? 'straight_line') : null;
        $salvageValue = $depreciationApplicable ? ($request->salvage_value ?? 0) : null;

        $item->update([
            'track_batch' => $request->track_batch ?? 0,
            'track_serial' => $request->track_serial ?? 0,
            'track_expiry' => $request->track_expiry ?? 0,
            'shelf_life_days' => $shelfLifeDays,
            'manufacturing_date' => $manufacturingDate,
            'expiry_date' => $expiryDate,
            'batch_number' => $batchNumber,
            'serial_number' => $serialNumber,
            'depreciation_applicable' => $depreciationApplicable,
            'asset_life_months' => $assetLifeMonths,
            'depreciation_method' => $depreciationMethod,
            'salvage_value' => $salvageValue,
            'weight' => $request->weight,
            'weight_unit' => $request->weight_unit,
            'length' => $request->length,
            'width' => $request->width,
            'height' => $request->height,
            'dimension_unit' => $request->dimension_unit,
            'reorder_level' => (float)($request->reorder_level ?? 0),
            'reorder_quantity' => (float)($request->reorder_quantity ?? 0),
            'max_stock' => (float)($request->max_stock ?? 0),
            'status' => $request->status ?? 1,
            'updated_by' => auth()->id(),
        ]);

        // === HANDLE BATCH TRACKING WITH OPENING STOCK ===
        if ($request->track_batch && $openingStock > 0) {
            $this->createBatchesForOpeningStock($item, $openingStock, $request);
        }

        // === HANDLE SERIAL TRACKING WITH OPENING STOCK ===
        if ($request->track_serial && $openingStock > 0) {
            $this->createSerialsForOpeningStock($item, $openingStock, $request);
        }

        // If asset depreciation is applicable, create initial depreciation log
        if ($depreciationApplicable && $assetLifeMonths > 0) {
            $this->createInitialDepreciationLog($item);
        }

        InventoryLogger::log([
            'module' => 'ITEM',
            'action' => 'CREATE_STEP3',
            'record_id' => $item->id,
            'old_data' => $oldData,
            'new_data' => $item->fresh()->toArray(),
            'remarks' => 'Step 3: Tracking completed'
        ]);

        return response()->json([
            'success' => true,
            'item_id' => $item->id,
            'message' => 'Step 3 completed successfully'
        ]);
    }

    /**
     * Create batches for opening stock
     */
    private function createBatchesForOpeningStock($item, $openingStock, $request)
    {
        $batchNumber = $request->batch_number ?? 'BATCH-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        $manufacturingDate = $request->batch_manufacturing_date ?? $request->manufacturing_date ?? now();
        $expiryDate = $request->batch_expiry_date ?? $request->expiry_date ?? null;
        $batchRemarks = $request->batch_remarks ?? 'Initial stock';

        // Create a single batch for the opening stock
        InventoryItemBatch::create([
            'institute_id' => $this->instituteId(),
            'branch_id' => $this->branchId(),
            'item_id' => $item->id,
            'batch_number' => $batchNumber,
            'quantity' => $openingStock,
            'remaining_quantity' => $openingStock,
            'manufacturing_date' => $manufacturingDate,
            'expiry_date' => $expiryDate,
            'purchase_price' => $item->buying_price,
            'remarks' => $batchRemarks,
            'created_by' => auth()->id(),
        ]);

        InventoryLogger::log([
            'module' => 'BATCH',
            'action' => 'CREATE_FROM_OPENING_STOCK',
            'record_id' => $item->id,
            'new_data' => [
                'batch_number' => $batchNumber,
                'quantity' => $openingStock,
                'manufacturing_date' => $manufacturingDate,
                'expiry_date' => $expiryDate,
            ],
            'remarks' => "Batch created for opening stock: {$openingStock} units"
        ]);
    }

    /**
     * Create serial numbers for opening stock
     */
    private function createSerialsForOpeningStock($item, $openingStock, $request)
    {
        // Check if serial numbers were provided in the form
        $serialNumbers = [];
        if ($request->has('serial_numbers')) {
            $serialNumbers = is_string($request->serial_numbers) 
                ? json_decode($request->serial_numbers, true) 
                : $request->serial_numbers;
        }

        if (!empty($serialNumbers) && is_array($serialNumbers)) {
            // Use provided serial numbers
            foreach ($serialNumbers as $serialData) {
                InventoryAssetInstance::create([
                    'institute_id' => $this->instituteId(),
                    'branch_id' => $this->branchId(),
                    'item_id' => $item->id,
                    'serial_number' => $serialData['serial_number'] ?? $this->generateSerialNumber($item, 0),
                    'asset_code' => $serialData['asset_code'] ?? $this->generateAssetCode($item),
                    'status' => 'ACTIVE',
                    'purchase_date' => $serialData['purchase_date'] ?? now(),
                    'warranty_expiry' => $serialData['warranty_expiry'] ?? null,
                    'warehouse_id' => $item->warehouse_id,
                    'store_id' => $item->store_id,
                    'buying_price' => $item->buying_price,
                    'created_by' => auth()->id(),
                ]);
            }
        } else {
            // Auto-generate serial numbers for opening stock
            for ($i = 1; $i <= $openingStock; $i++) {
                InventoryAssetInstance::create([
                    'institute_id' => $this->instituteId(),
                    'branch_id' => $this->branchId(),
                    'item_id' => $item->id,
                    'serial_number' => $this->generateSerialNumber($item, $i),
                    'asset_code' => $this->generateAssetCode($item),
                    'status' => 'ACTIVE',
                    'purchase_date' => now(),
                    'warehouse_id' => $item->warehouse_id,
                    'store_id' => $item->store_id,
                    'buying_price' => $item->buying_price,
                    'created_by' => auth()->id(),
                ]);
            }

            InventoryLogger::log([
                'module' => 'SERIAL',
                'action' => 'GENERATE_FROM_OPENING_STOCK',
                'record_id' => $item->id,
                'new_data' => [
                    'count' => $openingStock,
                    'start_serial' => $this->generateSerialNumber($item, 1),
                    'end_serial' => $this->generateSerialNumber($item, $openingStock),
                ],
                'remarks' => "Generated {$openingStock} serial numbers for opening stock"
            ]);
        }
    }

    // ============================================================
    // STEP 4: FINALIZE
    // ============================================================
    public function storeStep4(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'finalize' => 'nullable|boolean',
        ]);

        $item = InventoryItem::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->findOrFail($request->item_id);

        // Final validation
        $errors = [];
        if (empty($item->item_name)) {
            $errors[] = 'Item Name is required';
        }
        if (empty($item->category_id)) {
            $errors[] = 'Category is required';
        }
        if (empty($item->warehouse_id)) {
            $errors[] = 'Warehouse is required';
        }
        if ($item->buying_price <= 0) {
            $errors[] = 'Buying Price is required';
        }
        if ($item->selling_price <= 0) {
            $errors[] = 'Selling Price is required';
        }

        // If batch tracking is enabled, ensure batches exist
        if ($item->track_batch && $item->opening_stock > 0) {
            $batchCount = $item->batches()->count();
            if ($batchCount == 0) {
                $errors[] = 'Batch tracking is enabled but no batches created for opening stock';
            }
        }

        // If serial tracking is enabled, ensure serials exist
        if ($item->track_serial && $item->opening_stock > 0) {
            $assetCount = $item->assetInstances()->count();
            if ($assetCount == 0) {
                $errors[] = 'Serial tracking is enabled but no serial numbers created for opening stock';
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Please complete all required fields: ' . implode(', ', $errors)
            ], 422);
        }

        if ($request->has('finalize') && $request->finalize) {
            $item->update([
                'status' => $item->status ?? 1,
                'updated_by' => auth()->id(),
            ]);
        }

        InventoryLogger::log([
            'module' => 'ITEM',
            'action' => 'CREATE_FINALIZE',
            'record_id' => $item->id,
            'new_data' => $item->fresh()->toArray(),
            'remarks' => 'Item creation finalized'
        ]);

        return response()->json([
            'success' => true,
            'item_id' => $item->id,
            'message' => 'Item created successfully!',
            'redirect' => route('inventory.items.index')
        ]);
    }

    public function storeFinalize(Request $request)
    {
        return $this->storeStep4($request);
    }

    // ============================================================
    // BULK ITEMS CREATION
    // ============================================================
    public function storeBulkItems(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.category_id' => 'required|exists:inventory_categories,id',
            'items.*.warehouse_id' => 'required|exists:inventory_warehouses,id',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.unit_id' => 'required|string',
            'items.*.buying_price' => 'required|numeric|min:0',
            'items.*.selling_price' => 'required|numeric|min:0',
            'items.*.vendor_id' => 'nullable|exists:inventory_vendors,id',
        ]);

        $createdItems = [];
        $errors = [];

        DB::transaction(function() use ($request, &$createdItems, &$errors) {
            foreach ($request->items as $index => $itemData) {
                try {
                    $category = InventoryCategory::with(['hsnCode', 'taxSlab'])->find($itemData['category_id']);
                    $taxSlab = $category ? $category->taxSlab : null;
                    $taxPercentage = $taxSlab ? $taxSlab->tax_rate : ($category->gst_rate ?? 0);
                    $hsnCode = $category && $category->hsnCode ? $category->hsnCode->hsn_code : null;

                    $itemCode = $this->generateItemCode($category, null);
                    $sku = $this->generateSku($itemData['item_name']);

                    $openingStock = (float)($itemData['opening_stock'] ?? 0);
                    $openingStockValue = $openingStock * ((float)($itemData['buying_price'] ?? 0));

                    $item = InventoryItem::create([
                        'institute_id' => $this->instituteId(),
                        'branch_id' => $this->branchId(),
                        'category_id' => $itemData['category_id'],
                        'subcategory_id' => $itemData['subcategory_id'] ?? null,
                        'vendor_id' => $itemData['vendor_id'] ?? null,
                        'warehouse_id' => $itemData['warehouse_id'],
                        'store_id' => $itemData['store_id'] ?? null,
                        'unit_id' => $itemData['unit_id'],
                        'unit_name' => $itemData['unit_name'] ?? $itemData['unit_id'],
                        'unit_code' => $itemData['unit_code'] ?? $itemData['unit_id'],
                        'item_name' => $itemData['item_name'],
                        'item_code' => $itemCode,
                        'sku' => $sku,
                        'hsn_code' => $hsnCode,
                        'item_type' => $itemData['item_type'] ?? 'CONSUMABLE',
                        'buying_price' => (float)$itemData['buying_price'],
                        'selling_price' => (float)$itemData['selling_price'],
                        'mrp' => (float)($itemData['mrp'] ?? 0),
                        'wholesale_price' => (float)($itemData['wholesale_price'] ?? 0),
                        'landing_cost' => (float)($itemData['landing_cost'] ?? 0),
                        'min_stock' => (float)($itemData['min_stock'] ?? 0),
                        'opening_stock' => $openingStock,
                        'opening_stock_value' => $openingStockValue,
                        'current_stock' => $openingStock,
                        'available_stock' => $openingStock,
                        'reserved_stock' => 0,
                        'tax_percentage' => $taxPercentage,
                        'rack_number' => $itemData['rack_number'] ?? null,
                        'shelf_number' => $itemData['shelf_number'] ?? null,
                        'bin_number' => $itemData['bin_number'] ?? null,
                        'reorder_level' => (float)($itemData['reorder_level'] ?? 0),
                        'reorder_quantity' => (float)($itemData['reorder_quantity'] ?? 0),
                        'max_stock' => (float)($itemData['max_stock'] ?? 0),
                        'status' => 1,
                        'created_by' => auth()->id(),
                    ]);

                    $createdItems[] = [
                        'id' => $item->id,
                        'item_code' => $item->item_code,
                        'item_name' => $item->item_name,
                    ];

                    InventoryLogger::log([
                        'module' => 'ITEM',
                        'action' => 'BULK_CREATE',
                        'record_id' => $item->id,
                        'new_data' => $item->toArray(),
                        'remarks' => 'Bulk item created'
                    ]);

                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
                }
            }
        });

        return response()->json([
            'success' => empty($errors),
            'created' => $createdItems,
            'errors' => $errors,
            'message' => count($createdItems) . ' items created successfully' . (count($errors) > 0 ? ', with ' . count($errors) . ' errors.' : '')
        ]);
    }

    // ============================================================
    // TRANSFER-BASED STOCK IN
    // ============================================================
    private function storeTransferStockIn(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:transfer',
            'transfer_id' => 'required|exists:inventory_stock_outs,id',
            'items_data' => 'required|json',
            'destination_warehouse_id' => 'required|exists:inventory_warehouses,id',
            'destination_store_id' => 'nullable|exists:inventory_stores,id',
        ]);
        
        $items = json_decode($request->items_data, true);
        
        if (empty($items)) {
            return response()->json([
                'success' => false,
                'message' => 'No items to receive.'
            ], 400);
        }
        
        $transfer = InventoryStockOut::findOrFail($request->transfer_id);
        $destinationWarehouseId = $request->destination_warehouse_id;
        $destinationStoreId = $request->destination_store_id;
        
        $this->validateTransferReceivable($transfer);
        
        DB::transaction(function() use ($items, $transfer, $destinationWarehouseId, $destinationStoreId) {
            $receivedItems = [];
            
            foreach ($items as $itemData) {
                $sourceItem = InventoryItem::where('institute_id', $this->instituteId())
                    ->where('branch_id', $this->branchId())
                    ->where('id', $itemData['id'])
                    ->first();
                
                if (!$sourceItem) {
                    throw new \Exception("Source item not found: " . ($itemData['name'] ?? 'Unknown'));
                }
                
                $this->validateSourceStock($sourceItem, $itemData['quantity']);
                
                $destItem = $this->findOrCreateDestinationItem(
                    $sourceItem,
                    $destinationWarehouseId,
                    $destinationStoreId
                );
                
                $previousStock = $destItem->current_stock;
                $this->updateDestinationStock($destItem, $itemData['quantity']);
                
                $receiptIn = $this->createReceiptInRecord(
                    $destItem,
                    $itemData['quantity'],
                    $transfer,
                    $destinationWarehouseId,
                    $destinationStoreId
                );
                
                $this->createStockMovement(
                    $destItem,
                    $itemData['quantity'],
                    $previousStock,
                    $destinationWarehouseId,
                    $destinationStoreId,
                    $receiptIn->id,
                    'IN'
                );
                
                if ($sourceItem->track_batch) {
                    $this->transferBatches(
                        $sourceItem,
                        $destItem,
                        $itemData['quantity'],
                        $transfer,
                        $destinationWarehouseId,
                        $destinationStoreId
                    );
                }
                
                if ($sourceItem->item_type === 'ASSET') {
                    $this->transferAssetInstances(
                        $sourceItem,
                        $destItem,
                        $itemData['quantity'],
                        $transfer,
                        $destinationWarehouseId,
                        $destinationStoreId
                    );
                }
                
                $this->verifySourceStockReduced($sourceItem, $itemData['quantity']);
                
                $receivedItems[] = [
                    'item_id' => $destItem->id,
                    'quantity' => $itemData['quantity'],
                    'name' => $destItem->item_name,
                    'receipt_id' => $receiptIn->id
                ];
            }
            
            $this->updateLocationUtilization($destinationWarehouseId, $destinationStoreId);
            
            if ($transfer->from_warehouse_id) {
                $this->updateLocationUtilization($transfer->from_warehouse_id, null);
            }
            if ($transfer->from_store_id) {
                $this->updateLocationUtilization(null, $transfer->from_store_id);
            }
            
            $transfer->update([
                'status' => 'completed',
                'received_by' => auth()->id(),
                'received_at' => now()
            ]);
            
            InventoryLogger::log([
                'module' => 'STOCK_IN',
                'action' => 'CREATE_FROM_TRANSFER',
                'record_id' => $transfer->id,
                'new_data' => [
                    'transfer_code' => $transfer->stock_out_code,
                    'destination_warehouse_id' => $destinationWarehouseId,
                    'destination_store_id' => $destinationStoreId,
                    'items_received' => $receivedItems,
                    'total_items' => count($receivedItems)
                ],
                'remarks' => 'Stock In from transfer: ' . $transfer->stock_out_code
            ]);
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Stock received successfully!',
            'redirect' => route('inventory.items.index')
        ]);
    }

    private function validateTransferReceivable($transfer)
    {
        if ($transfer->type !== 'transfer') {
            throw new \Exception('This is not a transfer type stock out.');
        }
        
        if ($transfer->status === 'pending') {
            throw new \Exception('This transfer is pending approval.');
        }
        
        if ($transfer->status === 'cancelled') {
            throw new \Exception('This transfer has been cancelled.');
        }
        
        if ($transfer->status === 'completed' && $transfer->received_at) {
            $existingReceipt = InventoryReceiptIn::where('institute_id', $this->instituteId())
                ->where('branch_id', $this->branchId())
                ->where('reference_type', InventoryStockOut::class)
                ->where('reference_id', $transfer->id)
                ->exists();
            
            if ($existingReceipt) {
                throw new \Exception('This transfer has already been received.');
            }
        }
        
        return true;
    }

    private function validateSourceStock($sourceItem, $quantity)
    {
        if ($sourceItem->current_stock < $quantity) {
            throw new \Exception(
                "Insufficient stock for {$sourceItem->item_name}. " .
                "Available: {$sourceItem->current_stock}, Required: {$quantity}"
            );
        }
        return true;
    }

    private function verifySourceStockReduced($sourceItem, $quantity)
    {
        $currentStock = $sourceItem->fresh()->current_stock;
        
        if ($currentStock < $quantity) {
            return;
        }
        
        InventoryLogger::log([
            'module' => 'STOCK_IN',
            'action' => 'MANUAL_STOCK_REDUCTION',
            'record_id' => $sourceItem->id,
            'new_data' => [
                'item_name' => $sourceItem->item_name,
                'quantity' => $quantity,
                'current_stock' => $currentStock,
                'new_stock' => $currentStock - $quantity
            ],
            'remarks' => 'Manual stock reduction during transfer receipt'
        ]);
        
        $sourceItem->current_stock -= $quantity;
        $sourceItem->available_stock -= $quantity;
        $sourceItem->save();
        
        $this->createStockMovement(
            $sourceItem,
            $quantity,
            $currentStock,
            $sourceItem->warehouse_id,
            $sourceItem->store_id,
            null,
            'OUT'
        );
    }

    private function findOrCreateDestinationItem($sourceItem, $warehouseId, $storeId = null)
    {
        $existingItem = InventoryItem::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->where('item_code', $sourceItem->item_code)
            ->where(function($query) use ($warehouseId, $storeId) {
                if ($warehouseId) {
                    $query->where('warehouse_id', $warehouseId);
                }
                if ($storeId) {
                    $query->where('store_id', $storeId);
                }
            })
            ->first();
        
        if ($existingItem) {
            return $existingItem;
        }
        
        $newItem = $sourceItem->replicate();
        $newItem->branch_id = $this->branchId();
        $newItem->item_code = $sourceItem->item_code;
        $newItem->sku = $sourceItem->sku;
        $newItem->barcode = $sourceItem->barcode;
        $newItem->qr_code = $sourceItem->qr_code;
        $newItem->warehouse_id = $warehouseId;
        $newItem->store_id = $storeId;
        $newItem->current_stock = 0;
        $newItem->available_stock = 0;
        $newItem->reserved_stock = 0;
        $newItem->opening_stock = 0;
        $newItem->opening_stock_value = 0;
        $newItem->created_by = auth()->id();
        $newItem->save();
        
        InventoryLogger::log([
            'module' => 'ITEM',
            'action' => 'CREATE_FROM_TRANSFER',
            'record_id' => $newItem->id,
            'new_data' => [
                'item_name' => $newItem->item_name,
                'item_code' => $newItem->item_code,
                'source_item_id' => $sourceItem->id,
                'warehouse_id' => $warehouseId,
                'store_id' => $storeId
            ],
            'remarks' => 'Item created at destination from transfer'
        ]);
        
        return $newItem;
    }

    private function transferBatches($sourceItem, $destItem, $quantity, $transfer, $warehouseId, $storeId = null)
    {
        if (!$sourceItem->track_batch) {
            return;
        }
        
        $batches = $sourceItem->batches()
            ->where('remaining_quantity', '>', 0)
            ->orderBy('expiry_date', 'asc')
            ->get();
        
        $remainingQty = $quantity;
        
        foreach ($batches as $batch) {
            if ($remainingQty <= 0) break;
            
            $takeQty = min($batch->remaining_quantity, $remainingQty);
            
            $batch->deductStock($takeQty, $transfer, 'Transferred to destination');
            
            $existingBatch = InventoryItemBatch::where('institute_id', $this->instituteId())
                ->where('branch_id', $this->branchId())
                ->where('item_id', $destItem->id)
                ->where('batch_number', $batch->batch_number)
                ->where('warehouse_id', $warehouseId)
                ->where('store_id', $storeId)
                ->first();
            
            if ($existingBatch) {
                $existingBatch->quantity += $takeQty;
                $existingBatch->remaining_quantity += $takeQty;
                $existingBatch->save();
            } else {
                $newBatch = $batch->replicate();
                $newBatch->branch_id = $this->branchId();
                $newBatch->item_id = $destItem->id;
                $newBatch->warehouse_id = $warehouseId;
                $newBatch->store_id = $storeId;
                $newBatch->quantity = $takeQty;
                $newBatch->remaining_quantity = $takeQty;
                $newBatch->created_by = auth()->id();
                $newBatch->save();
            }
            
            $remainingQty -= $takeQty;
        }
        
        if ($remainingQty > 0) {
            $newBatch = InventoryItemBatch::create([
                'institute_id' => $this->instituteId(),
                'branch_id' => $this->branchId(),
                'item_id' => $destItem->id,
                'warehouse_id' => $warehouseId,
                'store_id' => $storeId,
                'batch_number' => 'BATCH-' . strtoupper(uniqid()),
                'quantity' => $remainingQty,
                'remaining_quantity' => $remainingQty,
                'purchase_price' => $sourceItem->buying_price ?? 0,
                'created_by' => auth()->id(),
            ]);
        }
    }

    private function transferAssetInstances($sourceItem, $destItem, $quantity, $transfer, $warehouseId, $storeId = null)
    {
        if ($sourceItem->item_type !== 'ASSET') {
            return;
        }
        
        $assets = $sourceItem->assetInstances()
            ->where('status', 'ACTIVE')
            ->limit($quantity)
            ->get();
        
        foreach ($assets as $asset) {
            $existingAsset = InventoryAssetInstance::where('institute_id', $this->instituteId())
                ->where('branch_id', $this->branchId())
                ->where('serial_number', $asset->serial_number)
                ->where('item_id', $destItem->id)
                ->where(function($q) use ($warehouseId, $storeId) {
                    if ($warehouseId) {
                        $q->where('warehouse_id', $warehouseId);
                    }
                    if ($storeId) {
                        $q->where('store_id', $storeId);
                    }
                })
                ->first();
            
            if ($existingAsset) {
                $existingAsset->status = 'ACTIVE';
                $existingAsset->save();
            } else {
                $newAsset = $asset->replicate();
                $newAsset->branch_id = $this->branchId();
                $newAsset->item_id = $destItem->id;
                $newAsset->warehouse_id = $warehouseId;
                $newAsset->store_id = $storeId;
                $newAsset->asset_code = $asset->asset_code;
                $newAsset->created_by = auth()->id();
                $newAsset->save();
            }
            
            $asset->update([
                'status' => 'TRANSFERRED',
                'transfer_id' => $transfer->id
            ]);
        }
    }

    private function createReceiptInRecord($destItem, $quantity, $transfer, $warehouseId, $storeId = null)
    {
        $receiptNumber = $this->generateReceiptInNumber();
        
        return InventoryReceiptIn::create([
            'institute_id' => $this->instituteId(),
            'branch_id' => $this->branchId(),
            'receipt_number' => $receiptNumber,
            'item_id' => $destItem->id,
            'warehouse_id' => $warehouseId,
            'store_id' => $storeId,
            'quantity' => $quantity,
            'unit_price' => $destItem->buying_price ?? 0,
            'total_price' => ($destItem->buying_price ?? 0) * $quantity,
            'receipt_type' => 'TRANSFER',
            'reference_type' => InventoryStockOut::class,
            'reference_id' => $transfer->id,
            'supplier_name' => 'Transfer from ' . ($transfer->fromWarehouse->warehouse_name ?? 'N/A'),
            'notes' => 'Stock In from transfer: ' . $transfer->stock_out_code,
            'status' => 'COMPLETED',
            'created_by' => auth()->id(),
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
    }

    private function updateDestinationStock($destItem, $quantity)
    {
        $oldStock = $destItem->current_stock;
        $newStock = $oldStock + $quantity;
        
        $destItem->current_stock = $newStock;
        $destItem->available_stock = $destItem->available_stock + $quantity;
        $destItem->save();
        
        InventoryLogger::log([
            'module' => 'STOCK',
            'action' => 'DESTINATION_STOCK_UPDATE',
            'record_id' => $destItem->id,
            'new_data' => [
                'item_name' => $destItem->item_name,
                'old_stock' => $oldStock,
                'added' => $quantity,
                'new_stock' => $newStock
            ],
            'remarks' => "Destination stock updated"
        ]);
    }

    private function createStockMovement($item, $quantity, $previousStock, $warehouseId, $storeId, $referenceId, $movementType)
    {
        return InventoryStockMovement::create([
            'institute_id' => $this->instituteId(),
            'branch_id' => $this->branchId(),
            'item_id' => $item->id,
            'warehouse_id' => $warehouseId,
            'store_id' => $storeId,
            'movement_type' => $movementType,
            'quantity' => $quantity,
            'previous_stock' => $previousStock,
            'new_stock' => $item->current_stock,
            'unit_cost' => $item->buying_price ?? 0,
            'total_cost' => ($item->buying_price ?? 0) * $quantity,
            'reference_type' => InventoryReceiptIn::class,
            'reference_id' => $referenceId,
            'notes' => 'Stock In from transfer',
            'created_by' => auth()->id(),
        ]);
    }

    private function generateReceiptInNumber()
    {
        $prefix = 'RCV';
        $date = now()->format('Ymd');
        $last = InventoryReceiptIn::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->whereDate('created_at', now()->toDateString())
            ->count();
        
        return $prefix . '-' . $date . '-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
    }

    private function updateLocationUtilization($warehouseId = null, $storeId = null)
    {
        if ($warehouseId) {
            $warehouse = InventoryWarehouse::where('institute_id', $this->instituteId())
                ->where('branch_id', $this->branchId())
                ->find($warehouseId);
            if ($warehouse) {
                $totalStock = InventoryItem::where('institute_id', $this->instituteId())
                    ->where('branch_id', $this->branchId())
                    ->where('warehouse_id', $warehouseId)
                    ->sum('current_stock');
                $warehouse->current_utilization = $totalStock ?? 0;
                $warehouse->save();
            }
        }
        
        if ($storeId) {
            $store = InventoryStore::where('institute_id', $this->instituteId())
                ->where('branch_id', $this->branchId())
                ->find($storeId);
            if ($store) {
                $totalStock = InventoryItem::where('institute_id', $this->instituteId())
                    ->where('branch_id', $this->branchId())
                    ->where('store_id', $storeId)
                    ->sum('current_stock');
                $store->current_utilization = $totalStock ?? 0;
                $store->save();
            }
        }
    }

    // ============================================================
    // FETCH TRANSFER DETAILS
    // ============================================================
    public function fetchTransfer($transferId)
    {
        try {
            $transfer = $this->findTransferForReceiving($transferId);
            
            if (!$transfer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transfer not found.'
                ], 404);
            }
            
            try {
                $this->validateTransferReceivable($transfer);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
            
            $items = $this->getTransferItemsWithDetails($transfer);
            $transfer->load(['fromWarehouse', 'toWarehouse', 'fromStore', 'toStore']);
            
            $isReadyForReceiving = in_array($transfer->status, ['approved', 'in-transit', 'completed']);
            $totalQuantity = array_sum(array_column($items, 'quantity'));
            $itemCount = count($items);
            
            $preselected = $this->getPreselectedDestinations($transfer);
            
            return response()->json([
                'success' => true,
                'transfer' => $transfer,
                'items' => $items,
                'is_ready_for_receiving' => $isReadyForReceiving,
                'status_message' => $this->getTransferStatusMessage($transfer->status),
                'source_location_path' => $this->getLocationPath($transfer),
                'total_quantity' => $totalQuantity,
                'item_count' => $itemCount,
                'has_batch_items' => $this->hasBatchItems($items),
                'has_asset_items' => $this->hasAssetItems($items),
                'preselected_destinations' => $preselected
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching transfer: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getPreselectedDestinations($transfer)
    {
        $result = [
            'warehouse_id' => null,
            'store_id' => null,
            'is_locked' => false,
            'lock_reason' => null
        ];

        if ($transfer->to_warehouse_id && !$transfer->to_store_id) {
            $result['warehouse_id'] = $transfer->to_warehouse_id;
            $result['is_locked'] = true;
            $result['lock_reason'] = 'Warehouse-to-Warehouse transfer - destination warehouse is fixed';
        }
        elseif ($transfer->to_warehouse_id && $transfer->to_store_id) {
            $result['warehouse_id'] = $transfer->to_warehouse_id;
            $result['store_id'] = $transfer->to_store_id;
            $result['is_locked'] = true;
            $result['lock_reason'] = 'Warehouse-to-Store transfer - destination is fixed';
        }
        elseif (!$transfer->to_warehouse_id && $transfer->to_store_id) {
            $result['store_id'] = $transfer->to_store_id;
            $result['is_locked'] = true;
            $result['lock_reason'] = 'Store-to-Store transfer - destination store is fixed';
        }

        return $result;
    }

    private function findTransferForReceiving($transferId)
    {
        $transfer = InventoryStockOut::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->where(function($query) use ($transferId) {
                $query->where('stock_out_code', $transferId)
                    ->orWhere('id', $transferId);
            })
            ->where(function($query) {
                $query->where('status', 'approved')
                    ->orWhere('status', 'in-transit')
                    ->orWhere(function($q) {
                        $q->where('status', 'completed')
                          ->whereNull('received_at');
                    });
            })
            ->where('type', 'transfer')
            ->first();
        
        if ($transfer) {
            return $transfer;
        }
        
        return InventoryStockOut::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->where(function($query) use ($transferId) {
                $query->where('stock_out_code', $transferId)
                    ->orWhere('id', $transferId);
            })
            ->where('type', 'transfer')
            ->whereIn('status', ['pending', 'approved', 'in-transit', 'completed'])
            ->first();
    }

    private function getTransferItemsWithDetails($transfer)
    {
        $items = is_array($transfer->items) ? $transfer->items : json_decode($transfer->items, true);
        
        if (empty($items)) {
            return [];
        }
        
        foreach ($items as &$item) {
            $inventoryItem = InventoryItem::where('institute_id', $this->instituteId())
                ->where('branch_id', $this->branchId())
                ->where('id', $item['id'])
                ->first();
            
            if ($inventoryItem) {
                $item['current_stock'] = $inventoryItem->current_stock;
                $item['available_stock'] = $inventoryItem->available_stock;
                $item['track_batch'] = $inventoryItem->track_batch ?? false;
                $item['track_serial'] = $inventoryItem->track_serial ?? false;
                $item['item_type'] = $inventoryItem->item_type ?? 'CONSUMABLE';
                $item['unit_name'] = $inventoryItem->unit_name ?? 'Unit';
                
                if ($inventoryItem->track_batch) {
                    $item['has_batches'] = $inventoryItem->batches()
                        ->where('remaining_quantity', '>', 0)
                        ->exists();
                }
                
                if ($inventoryItem->item_type === 'ASSET') {
                    $item['has_assets'] = $inventoryItem->assetInstances()
                        ->where('status', 'ACTIVE')
                        ->exists();
                }
            }
        }
        
        return $items;
    }

    private function hasBatchItems($items)
    {
        foreach ($items as $item) {
            if (isset($item['track_batch']) && $item['track_batch']) {
                return true;
            }
        }
        return false;
    }

    private function hasAssetItems($items)
    {
        foreach ($items as $item) {
            if (isset($item['item_type']) && $item['item_type'] === 'ASSET') {
                return true;
            }
        }
        return false;
    }

    private function getLocationPath($transfer)
    {
        $parts = [];
        
        if ($transfer->fromWarehouse) {
            $parts[] = 'Warehouse: ' . $transfer->fromWarehouse->warehouse_name;
        }
        
        if ($transfer->fromStore) {
            $parts[] = 'Store: ' . $transfer->fromStore->store_name;
        }
        
        return implode(' → ', $parts);
    }

    private function getTransferStatusMessage($status)
    {
        $messages = [
            'pending' => 'This transfer is pending approval.',
            'approved' => 'This transfer is approved and ready for receiving.',
            'in-transit' => 'This transfer is in transit. You can receive the stock.',
            'completed' => 'This transfer has been dispatched. You can receive the stock.',
            'cancelled' => 'This transfer has been cancelled.'
        ];
        
        return $messages[$status] ?? 'Status: ' . ucfirst($status);
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================

    private function generateItemCode($category, $subcategory = null)
    {
        $lastItem = InventoryItem::latest('id')->first();
        $nextId = $lastItem ? $lastItem->id + 1 : 1;

        return strtoupper(
            $category->category_code .
            '-' .
            ($subcategory ? $subcategory->subcategory_code : 'GEN') .
            '-' .
            str_pad($nextId, 6, '0', STR_PAD_LEFT)
        );
    }

    private function generateSku($itemName)
    {
        $prefix = strtoupper(Str::slug(substr($itemName, 0, 15), ''));
        $random = strtoupper(Str::random(5));
        return $prefix . '-' . $random;
    }

    private function generateQrCode($item)
    {
        return 'QR-' . $item->item_code . '-' . Str::random(6);
    }

    private function generateSerialNumber($item, $index)
    {
        $prefix = strtoupper(substr($item->item_code, 0, 4));
        return $prefix . '-' . date('Ymd') . '-' . str_pad($index, 4, '0', STR_PAD_LEFT);
    }

    private function generateAssetCode($item)
    {
        $prefix = strtoupper(substr($item->item_code, 0, 6));
        $lastAsset = $item->assetInstances()->orderBy('id', 'desc')->first();
        $nextId = $lastAsset ? intval(substr($lastAsset->asset_code, -4)) + 1 : 1;
        return $prefix . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    private function createInitialDepreciationLog($item)
    {
        if (!$item->depreciation_applicable || !$item->asset_life_months) {
            return;
        }

        $monthlyDepreciation = $item->buying_price / $item->asset_life_months;

        InventoryDepreciationLog::create([
            'institute_id' => $this->instituteId(),
            'branch_id' => $this->branchId(),
            'item_id' => $item->id,
            'asset_instance_id' => null,
            'depreciation_date' => now(),
            'book_value_before' => $item->buying_price,
            'book_value_after' => $item->buying_price - $monthlyDepreciation,
            'depreciation_amount' => $monthlyDepreciation,
            'depreciation_method' => $item->depreciation_method ?? 'straight_line',
            'remaining_life_months' => $item->asset_life_months - 1,
            'created_by' => auth()->id(),
        ]);
    }

    // ============================================================
    // VIEW, EDIT, UPDATE
    // ============================================================

    public function view($id)
    {
        $item = InventoryItem::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->with([
                'category',
                'subcategory',
                'vendor',
                'warehouse',
                'store',
                'stockMovements' => function ($query) {
                    $query->latest()->limit(10);
                },
                'batches' => function ($query) {
                    $query->where('remaining_quantity', '>', 0);
                },
                'assetInstances' => function ($query) {
                    $query->where('status', 'ACTIVE');
                }
            ])
            ->findOrFail($id);

        $depreciationLogs = [];
        if ($item->depreciation_applicable) {
            $depreciationLogs = InventoryDepreciationLog::where('item_id', $item->id)
                ->where('branch_id', $this->branchId())
                ->latest()
                ->limit(10)
                ->get();
        }

        $otherWarehouses = InventoryItem::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->where('id', '!=', $id)
            ->where('item_code', $item->item_code)
            ->with('warehouse', 'store')
            ->get(['id', 'warehouse_id', 'store_id', 'current_stock', 'available_stock']);

        return view('instituteAdmin.inventory.item.view', compact(
            'item',
            'otherWarehouses',
            'depreciationLogs'
        ));
    }

    public function edit($id)
    {
        $item = InventoryItem::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->with(['category', 'subcategory', 'vendor'])
            ->findOrFail($id);
    
        $categories = InventoryCategory::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->where('status', 1)
            ->get(); // Removed ->with('taxRate')
    
        $subcategories = InventorySubCategory::where('category_id', $item->category_id)
            ->where('branch_id', $this->branchId())
            ->get();
            
        $warehouses = InventoryWarehouse::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->where('status', 1)
            ->get();
            
        $stores = InventoryStore::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->where('status', 1)
            ->get();
            
        $unitOptions = $this->getUnitOptions();
        
        $taxRates = TaxSlab::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->where('status', 1)
            ->get();
            
        $vendors = InventoryVendor::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->where('status', 1)
            ->orderBy('vendor_name')
            ->get();
    
        return view('instituteAdmin.inventory.item.edit', compact(
            'item',
            'categories',
            'subcategories',
            'warehouses',
            'stores',
            'unitOptions',
            'taxRates',
            'vendors'
        ));
    }

    public function update(Request $request, $id)
    {
        $item = InventoryItem::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->findOrFail($id);

        $oldData = $item->toArray();

        $item->update([
            'warehouse_id' => $request->warehouse_id,
            'store_id' => $request->store_id,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'vendor_id' => $request->vendor_id,
            'unit_id' => $request->unit_id,
            'unit_name' => $request->unit_name,
            'unit_code' => $request->unit_code,
            'item_name' => $request->item_name,
            'item_type' => $request->item_type,
            'brand' => $request->brand,
            'model' => $request->model,
            'manufacturer' => $request->manufacturer,
            'manufacturer_part_number' => $request->manufacturer_part_number,
            'hsn_code' => $request->hsn_code,
            'buying_price' => $request->buying_price,
            'selling_price' => $request->selling_price,
            'mrp' => $request->mrp ?? 0,
            'wholesale_price' => $request->wholesale_price ?? 0,
            'landing_cost' => $request->landing_cost ?? 0,
            'min_stock' => $request->min_stock ?? 0,
            'reorder_level' => $request->reorder_level,
            'reorder_quantity' => $request->reorder_quantity,
            'max_stock' => $request->max_stock,
            'rack_number' => $request->rack_number,
            'shelf_number' => $request->shelf_number,
            'bin_number' => $request->bin_number,
            'track_batch' => $request->track_batch ?? 0,
            'track_serial' => $request->track_serial ?? 0,
            'track_expiry' => $request->track_expiry ?? 0,
            'depreciation_applicable' => $request->depreciation_applicable ?? 0,
            'asset_life_months' => $request->asset_life_months,
            'depreciation_method' => $request->depreciation_method ?? 'straight_line',
            'salvage_value' => $request->salvage_value ?? 0,
            'description' => $request->description,
            'status' => $request->status ?? 1,
            'updated_by' => auth()->id()
        ]);

        InventoryLogger::log([
            'module' => 'ITEM',
            'action' => 'UPDATE',
            'record_id' => $item->id,
            'old_data' => $oldData,
            'new_data' => $item->fresh()->toArray()
        ]);

        return redirect()
            ->route('inventory.items.index')
            ->with('success', 'Item Updated Successfully');
    }

    // ============================================================
    // STATUS TOGGLE & DELETE
    // ============================================================

    public function toggleStatus($id)
    {
        $item = InventoryItem::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->findOrFail($id);
        
        $oldStatus = $item->status;
        $newStatus = !$oldStatus;
        
        $item->update([
            'status' => $newStatus,
            'updated_by' => auth()->id()
        ]);

        InventoryLogger::log([
            'module' => 'ITEM',
            'action' => 'STATUS_TOGGLE',
            'record_id' => $item->id,
            'old_data' => ['status' => $oldStatus],
            'new_data' => ['status' => $newStatus],
            'remarks' => "Item status changed from " . ($oldStatus ? 'Active' : 'Inactive') . " to " . ($newStatus ? 'Active' : 'Inactive')
        ]);

        return redirect()
            ->route('inventory.items.index')
            ->with('success', 'Item status updated successfully.');
    }

    public function destroy($id)
    {
        $item = InventoryItem::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->findOrFail($id);

        if (!$item->canBeDeleted()) {
            $blockers = $item->getDeletionBlockers();
            $messages = [];

            foreach ($blockers as $blocker) {
                $messages[] = $blocker['message'];
            }

            return response()->json([
                'success' => false,
                'message' => 'Cannot delete this item: ' . implode(', ', $messages),
                'blockers' => $blockers
            ], 422);
        }

        InventoryLogger::log([
            'module' => 'ITEM',
            'action' => 'DELETE',
            'record_id' => $item->id,
            'old_data' => $item->toArray()
        ]);

        $item->update(['deleted_by' => auth()->id()]);
        $item->delete();

        return response()->json([
            'success' => true
        ]);
    }

    // ============================================================
    // API ENDPOINTS
    // ============================================================

    public function subCategoryByCategory($categoryId)
    {
        return response()->json(
            InventorySubCategory::where('category_id', $categoryId)
                ->where('branch_id', $this->branchId())
                ->get()
        );
    }

    public function getTaxRate($categoryId)
    {
        $category = InventoryCategory::with(['hsnCode', 'taxSlab'])
            ->where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->find($categoryId);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'tax_slab' => $category->taxSlab,
            'tax_percentage' => $category->taxSlab ? $category->taxSlab->tax_rate : ($category->gst_rate ?? 0),
            'hsn_code' => $category->hsnCode ? $category->hsnCode->hsn_code : null,
            'hsn_description' => $category->hsnCode ? $category->hsnCode->description : null,
            'is_gst_applicable' => $category->is_gst_applicable ?? 0,
        ]);
    }

    public function getStoreByWarehouse($warehouseId)
    {
        return response()->json(
            InventoryStore::where('warehouse_id', $warehouseId)
                ->where('institute_id', $this->instituteId())
                ->where('branch_id', $this->branchId())
                ->where('status', 1)
                ->get()
        );
    }

    public function getItemUnits($itemId)
    {
        $item = InventoryItem::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->find($itemId);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'base_unit' => [
                'unit_id' => $item->unit_id,
                'unit_name' => $item->unit_name,
                'unit_code' => $item->unit_code
            ],
            'additional_units' => $item->units
        ]);
    }

    /**
     * Get item batches for POS
     */
    public function getItemBatches($id)
    {
        $item = InventoryItem::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->with(['batches' => function($query) {
                $query->where('remaining_quantity', '>', 0)
                    ->orderBy('expiry_date', 'asc'); // FIFO - oldest first
            }])
            ->find($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        $batches = $item->batches->map(function($batch) {
            return [
                'id' => $batch->id,
                'batch_number' => $batch->batch_number,
                'remaining_quantity' => $batch->remaining_quantity,
                'expiry_date' => $batch->expiry_date ? $batch->expiry_date->format('Y-m-d') : null,
                'manufacturing_date' => $batch->manufacturing_date ? $batch->manufacturing_date->format('Y-m-d') : null,
                'purchase_price' => $batch->purchase_price,
            ];
        });

        return response()->json([
            'success' => true,
            'batches' => $item->batches,
            'total_quantity' => $item->batches->sum('remaining_quantity')
        ]);
    }

    /**
     * Get item assets (serial numbers) for POS with FIFO
     */
    public function getItemAssets($id)
    {
        $item = InventoryItem::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->with(['assetInstances' => function($query) {
                $query->where('status', 'ACTIVE')
                    ->orderBy('created_at', 'asc');
            }])
            ->find($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        $assets = $item->assetInstances->map(function($asset) {
            return [
                'id' => $asset->id,
                'serial_number' => $asset->serial_number,
                'asset_code' => $asset->asset_code,
                'status' => $asset->status,
                'purchase_date' => $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : null,
                'warranty_expiry' => $asset->warranty_expiry ? $asset->warranty_expiry->format('Y-m-d') : null,
            ];
        });

        return response()->json([
            'success' => true,
            'assets' => $item->assetInstances,
            'total_count' => $item->assetInstances->count()
        ]);
    }

    /**
     * Get tax details for an item
     */
    public function getTaxDetails($id)
    {
        $item = InventoryItem::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->with(['category'])
            ->find($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        $taxRate = $item->tax_percentage ?? 0;
        $hsnCode = $item->hsn_code ?? '';

        // Get category tax details
        $categoryTax = 0;
        if ($item->category) {
            $categoryTax = $item->category->gst_rate ?? 0;
        }

        return response()->json([
            'success' => true,
            'tax_rate' => $taxRate > 0 ? $taxRate : $categoryTax,
            'hsn_code' => $hsnCode,
            'tax_percentage' => $taxRate > 0 ? $taxRate : $categoryTax,
            'item_name' => $item->item_name,
            'item_code' => $item->item_code,
        ]);
    }

    public function calculateDepreciation($itemId)
    {
        $item = InventoryItem::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->findOrFail($itemId);

        if (!$item->depreciation_applicable || !$item->asset_life_months) {
            return response()->json([
                'success' => false,
                'message' => 'Depreciation not applicable for this item'
            ]);
        }

        $monthlyDepreciation = $item->buying_price / $item->asset_life_months;
        $ageInMonths = $item->created_at ? $item->created_at->diffInMonths(now()) : 0;
        $accumulatedDepreciation = $monthlyDepreciation * min($ageInMonths, $item->asset_life_months);
        $bookValue = max($item->buying_price - $accumulatedDepreciation, 0);
        $remainingLife = max($item->asset_life_months - $ageInMonths, 0);

        return response()->json([
            'success' => true,
            'item_id' => $item->id,
            'item_name' => $item->item_name,
            'original_cost' => $item->buying_price,
            'asset_life_months' => $item->asset_life_months,
            'depreciation_method' => $item->depreciation_method ?? 'straight_line',
            'monthly_depreciation' => round($monthlyDepreciation, 2),
            'age_in_months' => $ageInMonths,
            'accumulated_depreciation' => round($accumulatedDepreciation, 2),
            'book_value' => round($bookValue, 2),
            'remaining_life_months' => $remainingLife,
            'depreciation_logs' => InventoryDepreciationLog::where('item_id', $item->id)
                ->where('branch_id', $this->branchId())
                ->latest()
                ->limit(10)
                ->get()
        ]);
    }
}