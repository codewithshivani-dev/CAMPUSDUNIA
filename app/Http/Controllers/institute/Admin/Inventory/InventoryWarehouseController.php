<?php
// app/Http/Controllers/institute/Admin/Inventory/InventoryWarehouseController.php

namespace App\Http\Controllers\institute\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\InventoryWarehouse;
use App\Models\Inventory\InventoryStore;
use App\Models\Inventory\InventoryItem;
use App\Models\Inventory\InventoryActivityLog;
use App\Services\Inventory\InventoryLogger;
use App\Models\AddBlock;
use App\Models\AddFloor;
use App\Models\AddRooms;
use Illuminate\Support\Facades\DB;

class InventoryWarehouseController extends Controller
{
    /**
     * Update instituteId() method to return both institute_id and branch_id context
     */
    private function getInstituteContext()
    {
        $user = auth()->user();
        return [
            'institute_id' => $user->institute_id,
            'branch_id' => $user->branch_id // Will be null for parent institute users
        ];
    }

    /**
     * Helper method to get institute_id (for backward compatibility)
     */
    private function instituteId()
    {
        return auth()->user()->institute_id;
    }

    /**
     * Helper method to get branch_id
     */
    private function branchId()
    {
        return auth()->user()->branch_id;
    }

    /**
     * Apply institute and branch filters to a query
     */
    private function applyInstituteBranchFilter($query, $branchId = null)
    {
        $context = $this->getInstituteContext();
        $query->where('institute_id', $context['institute_id']);
        
        // If branch context exists, filter by branch
        if ($branchId !== null) {
            $query->where('branch_id', $branchId);
        } elseif ($context['branch_id'] !== null) {
            $query->where('branch_id', $context['branch_id']);
        }
        
        return $query;
    }

    public function index()
    {
        $context = $this->getInstituteContext();
        $branchId = $context['branch_id'];
        
        // Show parent institute's all warehouses (if parent) or branch-specific (if branch user)
        $warehouses = InventoryWarehouse::where('institute_id', $this->instituteId());
        
        // If user has branch context, only show branch-specific warehouses
        if ($branchId !== null) {
            $warehouses->where('branch_id', $branchId);
        }
        // If user is parent institute (no branch), show all warehouses (both with and without branch)
        // This means we don't add branch filter for parent users
        
        $warehouses = $warehouses->withCount(['items', 'stores'])
            ->latest()
            ->get();

        $blocks = AddBlock::where('status', 'active')
            ->where('institute_id', $this->instituteId())
            ->with('building')
            ->orderBy('name')
            ->get();

        foreach ($warehouses as $warehouse) {
            $warehouse->updateUtilization();
        }

        return view('instituteAdmin.inventory.warehouse.index', compact('warehouses', 'blocks'));
    }

    public function create()
    {
        $blocks = AddBlock::where('status', 'active')
            ->where('institute_id', $this->instituteId())
            ->with('building')
            ->orderBy('name')
            ->get();

        $floors = AddFloor::where('status', 'active')
            ->where('institute_id', $this->instituteId())
            ->with('block')
            ->orderBy('floor_name')
            ->get();

        $rooms = AddRooms::where('status', 'active')
            ->where('institute_id', $this->instituteId())
            ->with('floor')
            ->orderBy('room_name')
            ->get();

        // ============================================
        // FIX: Get actual warehouse records for the current context
        // ============================================
        $warehouseQuery = InventoryWarehouse::where('institute_id', $this->instituteId());
        
        // If user has branch context, filter by branch
        if ($this->branchId() !== null) {
            $warehouseQuery->where('branch_id', $this->branchId());
        }
        
        // Get actual warehouse records for default detection
        $existingWarehouses = $warehouseQuery->get();
        
        // Also keep the floor-based warehouse mapping for auto-fill
        $floorWarehouses = $floors->filter(function($floor) {
            return !is_null($floor->warehouse_name) && !is_null($floor->warehouse_id);
        })->map(function($floor) {
            return (object) [
                'id' => $floor->id,
                'floor_id' => $floor->id,
                'warehouse_name' => $floor->warehouse_name,
                'warehouse_code' => $floor->warehouse_id,
                'block_id' => $floor->block_id
            ];
        })->values();

        return view('instituteAdmin.inventory.warehouse.create', compact(
            'blocks', 
            'floors', 
            'rooms', 
            'floorWarehouses',
            'existingWarehouses'
        ));
    }

    public function store(Request $request)
    {
        $context = $this->getInstituteContext();
        $branchId = $this->branchId();
        
        // Update validation rules to include branch_id context for uniqueness
        $request->validate([
            'warehouse_name' => [
                'required',
                'max:255',
                'unique:inventory_warehouses,warehouse_name,NULL,id,institute_id,' . $this->instituteId() . ',branch_id,' . ($branchId ?? 'NULL')
            ],
            'warehouse_code' => [
                'required',
                'max:50',
                'unique:inventory_warehouses,warehouse_code,NULL,id,institute_id,' . $this->instituteId() . ',branch_id,' . ($branchId ?? 'NULL')
            ],
            'capacity' => 'nullable|integer|min:0',
            'block_id' => 'nullable|integer|min:0',
            'floor_id' => 'nullable|integer|min:0',
            'room_id' => 'nullable|integer|min:0',
        ]);

        $warehouse = null;

        \DB::transaction(function () use ($request, &$warehouse, $branchId) {
            // Only update default for same institute and branch
            if ($request->boolean('is_default')) {
                $query = InventoryWarehouse::where('institute_id', $this->instituteId());
                if ($branchId !== null) {
                    $query->where('branch_id', $branchId);
                }
                $query->update(['is_default' => 0]);
            }

            $warehouse = InventoryWarehouse::create([
                'institute_id' => $this->instituteId(),
                'branch_id' => $branchId, // Add branch_id from authenticated user's branch
                'warehouse_name' => $request->warehouse_name,
                'warehouse_code' => strtoupper($request->warehouse_code),
                'contact_person' => $request->contact_person,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $request->pincode,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'capacity' => $request->capacity,
                'current_utilization' => 0,
                'is_default' => $request->boolean('is_default'),
                'status' => $request->boolean('status', 1),
                'block_id' => $request->block_id,
                'floor_id' => $request->floor_id,
                'room_id' => $request->room_id,
                'created_by' => auth()->id()
            ]);

            InventoryLogger::log([
                'module' => 'WAREHOUSE',
                'action' => 'CREATE',
                'record_id' => $warehouse->id,
                'new_data' => $warehouse->toArray(),
                'remarks' => 'Warehouse created: ' . $warehouse->warehouse_name
            ]);
        });

        if ($request->has('step') && $request->step == '1') {
            return redirect()
                ->route('inventory.warehouses.add-store', ['warehouse' => $warehouse->id])
                ->with('success', 'Warehouse created successfully! Now add a store.');
        }

        return redirect()
            ->route('inventory.warehouses.index')
            ->with('success', 'Warehouse Created Successfully');
    }

    public function addStore($warehouseId)
    {
        $warehouse = $this->applyInstituteBranchFilter(
            InventoryWarehouse::where('id', $warehouseId)
        )->firstOrFail();

        $warehouses = $this->applyInstituteBranchFilter(
            InventoryWarehouse::where('status', 1)
        )->get();

        $floors = AddFloor::where('status', 'active')
            ->where('institute_id', $this->instituteId())
            ->with('block')
            ->orderBy('floor_name')
            ->get();

        $rooms = AddRooms::where('status', 'active')
            ->where('institute_id', $this->instituteId())
            ->where('room_type', 'store')
            ->with('floor')
            ->orderBy('room_name')
            ->get();

        return view('instituteAdmin.inventory.warehouse.add-store', compact(
            'warehouse',
            'warehouses',
            'floors',
            'rooms'
        ));
    }

    public function storeStore(Request $request, $warehouseId)
    {
        $context = $this->getInstituteContext();
        $branchId = $context['branch_id'];
        
        $warehouse = $this->applyInstituteBranchFilter(
            InventoryWarehouse::where('id', $warehouseId)
        )->firstOrFail();

        // Get the room_ids from the request
        $roomIds = $request->input('room_ids') ? explode(',', $request->input('room_ids')) : [];
        
        $request->validate([
            'floor_id' => 'required|exists:add_floors,id',
            'room_ids' => 'required|string',
        ]);

        // Validate that room_ids are valid and are store rooms
        if (!empty($roomIds)) {
            $validRooms = AddRooms::whereIn('id', $roomIds)
                ->where('room_type', 'store')
                ->where('institute_id', $this->instituteId())
                ->count();

            if ($validRooms !== count($roomIds)) {
                return back()->withErrors(['room_ids' => 'One or more selected rooms are invalid or are not store rooms.'])->withInput();
            }
        }

        $createdStores = [];
        $errors = [];

        DB::transaction(function () use ($request, $warehouse, $roomIds, &$createdStores, &$errors, $branchId) {
            // Get per-room details
            $perRoomContact = $request->input('per_room_contact', []);
            $perRoomPhone = $request->input('per_room_phone', []);
            $perRoomEmail = $request->input('per_room_email', []);
            $perRoomCapacity = $request->input('per_room_capacity', []);

            // Common details (fallback)
            $commonContact = $request->input('common_contact_person');
            $commonPhone = $request->input('common_phone');
            $commonEmail = $request->input('common_email');
            $commonCapacity = $request->input('common_capacity');

            // Get room details for all selected rooms
            $rooms = AddRooms::whereIn('id', $roomIds)->get()->keyBy('id');

            foreach ($roomIds as $roomId) {
                $room = $rooms->get($roomId);
                if (!$room) {
                    $errors[] = "Room ID {$roomId} not found.";
                    continue;
                }

                // Use per-room details if available, otherwise use common details
                $contactPerson = isset($perRoomContact[$roomId]) && !empty($perRoomContact[$roomId]) 
                    ? $perRoomContact[$roomId] 
                    : $commonContact;

                $phone = isset($perRoomPhone[$roomId]) && !empty($perRoomPhone[$roomId]) 
                    ? $perRoomPhone[$roomId] 
                    : $commonPhone;

                $email = isset($perRoomEmail[$roomId]) && !empty($perRoomEmail[$roomId]) 
                    ? $perRoomEmail[$roomId] 
                    : $commonEmail;

                $capacity = isset($perRoomCapacity[$roomId]) && !empty($perRoomCapacity[$roomId]) 
                    ? $perRoomCapacity[$roomId] 
                    : $commonCapacity;

                // Generate UNIQUE store name and code for EACH room
                $storeName = $room->room_name;
                $storeCode = 'ST-' . strtoupper($room->room_code ?? 'RM' . str_pad($roomId, 4, '0', STR_PAD_LEFT));

                // Check if a store already exists for this room
                $existingStore = InventoryStore::where('institute_id', $this->instituteId())
                    ->where('room_id', $roomId)
                    ->first();

                if ($existingStore) {
                    // Update existing store
                    $existingStore->update([
                        'warehouse_id' => $warehouse->id,
                        'store_name' => $storeName,
                        'store_code' => strtoupper($storeCode),
                        'contact_person' => $contactPerson,
                        'phone' => $phone,
                        'email' => $email,
                        'address' => $request->address,
                        'city' => $request->city,
                        'state' => $request->state,
                        'pincode' => $request->pincode,
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude,
                        'capacity' => $capacity ?? 0,
                        'is_default' => $request->boolean('is_default'),
                        'status' => $request->boolean('status', 1),
                        'floor_id' => $request->floor_id,
                        'room_id' => $roomId,
                        'updated_by' => auth()->id()
                    ]);

                    $createdStores[] = $existingStore;
                } else {
                    // Create NEW store for EACH room with branch context
                    $store = InventoryStore::create([
                        'institute_id' => $this->instituteId(),
                        'branch_id' => $branchId, // Set branch_id for stores
                        'warehouse_id' => $warehouse->id,
                        'store_name' => $storeName,
                        'store_code' => strtoupper($storeCode),
                        'contact_person' => $contactPerson,
                        'phone' => $phone,
                        'email' => $email,
                        'address' => $request->address,
                        'city' => $request->city,
                        'state' => $request->state,
                        'pincode' => $request->pincode,
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude,
                        'capacity' => $capacity ?? 0,
                        'current_utilization' => 0,
                        'is_default' => $request->boolean('is_default'),
                        'status' => $request->boolean('status', 1),
                        'floor_id' => $request->floor_id,
                        'room_id' => $roomId,
                        'created_by' => auth()->id()
                    ]);

                    $createdStores[] = $store;

                    InventoryLogger::log([
                        'module' => 'STORE',
                        'action' => 'CREATE',
                        'record_id' => $store->id,
                        'new_data' => $store->toArray(),
                        'remarks' => 'Store created: ' . $store->store_name . ' under warehouse: ' . $warehouse->warehouse_name
                    ]);
                }
            }

            // If this is the default store, update other stores in the same warehouse
            if ($request->boolean('is_default') && !empty($createdStores)) {
                $query = InventoryStore::where('institute_id', $this->instituteId())
                    ->where('warehouse_id', $warehouse->id)
                    ->whereNotIn('id', array_column($createdStores, 'id'));
                
                if ($branchId !== null) {
                    $query->where('branch_id', $branchId);
                }
                $query->update(['is_default' => 0]);
            }
        });

        if (!empty($errors)) {
            return back()->withErrors(['room_ids' => implode(' ', $errors)])->withInput();
        }

        $message = count($createdStores) > 1 
            ? count($createdStores) . ' stores created successfully!' 
            : 'Store created successfully!';

        return redirect()
            ->route('inventory.warehouses.index')
            ->with('success', $message);
    }

    public function view($id)
    {
        $warehouse = $this->applyInstituteBranchFilter(
            InventoryWarehouse::where('id', $id)
        )
        ->with(['items' => function($query) {
            // Also filter items by branch context
            if ($this->branchId() !== null) {
                $query->where('branch_id', $this->branchId());
            }
            $query->select('id', 'item_name', 'item_code', 'current_stock', 'available_stock', 'reserved_stock', 'reorder_level')
                ->where('current_stock', '>', 0)
                ->limit(20);
        }, 'stores', 'creator', 'updater'])
        ->withCount(['items', 'stores'])
        ->firstOrFail();

        $warehouse->updateUtilization();

        $totalStock = $warehouse->items->sum('current_stock');
        $uniqueProducts = $warehouse->items->count();
        $outOfStock = $warehouse->items->where('current_stock', '<=', 0)->count();

        $lowStockItems = $warehouse->items->filter(function ($item) {
            return $item->available_stock <= $item->reorder_level;
        })->count();

        return view('instituteAdmin.inventory.warehouse.view', compact(
            'warehouse',
            'totalStock',
            'uniqueProducts',
            'outOfStock',
            'lowStockItems'
        ));
    }

    public function edit($id)
    {
        $warehouse = $this->applyInstituteBranchFilter(
            InventoryWarehouse::where('id', $id)
        )->firstOrFail();

        $blocks = AddBlock::where('status', 'active')
            ->where('institute_id', $this->instituteId())
            ->with('building')
            ->orderBy('name')
            ->get();

        $floors = AddFloor::where('status', 'active')
            ->where('institute_id', $this->instituteId())
            ->with('block')
            ->orderBy('floor_name')
            ->get();

        $rooms = AddRooms::where('status', 'active')
            ->where('institute_id', $this->instituteId())
            ->with('floor')
            ->orderBy('room_name')
            ->get();

        return view('instituteAdmin.inventory.warehouse.edit', compact('warehouse', 'blocks', 'floors', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        $context = $this->getInstituteContext();
        $branchId = $context['branch_id'];
        
        $warehouse = $this->applyInstituteBranchFilter(
            InventoryWarehouse::where('id', $id)
        )->firstOrFail();

        // Update validation rules to include branch_id context for uniqueness
        $request->validate([
            'warehouse_name' => [
                'required',
                'max:255',
                'unique:inventory_warehouses,warehouse_name,' . $id . ',id,institute_id,' . $this->instituteId() . ',branch_id,' . ($branchId ?? 'NULL')
            ],
            'warehouse_code' => [
                'required',
                'max:50',
                'unique:inventory_warehouses,warehouse_code,' . $id . ',id,institute_id,' . $this->instituteId() . ',branch_id,' . ($branchId ?? 'NULL')
            ],
            'capacity' => 'nullable|integer|min:0',
            'block_id' => 'nullable|integer|min:0',
            'floor_id' => 'nullable|integer|min:0',
            'room_id' => 'nullable|integer|min:0',
        ]);

        \DB::transaction(function () use ($request, $warehouse, $branchId) {
            $oldData = $warehouse->toArray();

            if ($request->boolean('is_default')) {
                $query = InventoryWarehouse::where('institute_id', $this->instituteId())
                    ->where('id', '!=', $warehouse->id);
                if ($branchId !== null) {
                    $query->where('branch_id', $branchId);
                }
                $query->update(['is_default' => 0]);
            }

            // Preserve branch_id (cannot change)
            $warehouse->update([
                'warehouse_name' => $request->warehouse_name,
                'warehouse_code' => strtoupper($request->warehouse_code),
                'contact_person' => $request->contact_person,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $request->pincode,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'capacity' => $request->capacity,
                'is_default' => $request->boolean('is_default'),
                'status' => $request->boolean('status', 1),
                'block_id' => $request->block_id,
                'floor_id' => $request->floor_id,
                'room_id' => $request->room_id,
                'updated_by' => auth()->id()
                // branch_id is NOT updated - it's preserved
            ]);

            $warehouse->updateUtilization();

            InventoryLogger::log([
                'module' => 'WAREHOUSE',
                'action' => 'UPDATE',
                'record_id' => $warehouse->id,
                'old_data' => $oldData,
                'new_data' => $warehouse->fresh()->toArray(),
                'remarks' => 'Warehouse updated: ' . $warehouse->warehouse_name
            ]);
        });

        return redirect()
            ->route('inventory.warehouses.index')
            ->with('success', 'Warehouse Updated Successfully');
    }

    public function destroy($id)
    {
        $warehouse = $this->applyInstituteBranchFilter(
            InventoryWarehouse::where('id', $id)
        )->firstOrFail();

        if ($warehouse->items()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete warehouse because it has items. Please transfer or delete the items first.'
            ], 422);
        }

        if ($warehouse->is_default) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete default warehouse. Please set another warehouse as default first.'
            ], 422);
        }

        \DB::transaction(function () use ($warehouse) {
            $oldData = $warehouse->toArray();

            InventoryLogger::log([
                'module' => 'WAREHOUSE',
                'action' => 'DELETE',
                'record_id' => $warehouse->id,
                'old_data' => $oldData,
                'remarks' => 'Warehouse deleted: ' . $warehouse->warehouse_name
            ]);

            $warehouse->update(['deleted_by' => auth()->id()]);
            $warehouse->delete();
        });

        return response()->json([
            'success' => true
        ]);
    }

    public function toggleStatus($id)
    {
        $warehouse = $this->applyInstituteBranchFilter(
            InventoryWarehouse::where('id', $id)
        )->firstOrFail();

        $oldData = $warehouse->toArray();

        $newStatus = $warehouse->status ? 0 : 1;
        $warehouse->update([
            'status' => $newStatus,
            'updated_by' => auth()->id()
        ]);

        InventoryLogger::log([
            'module' => 'WAREHOUSE',
            'action' => 'status_toggle',
            'record_id' => $warehouse->id,
            'old_data' => $oldData,
            'new_data' => $warehouse->fresh()->toArray(),
            'remarks' => 'Warehouse status changed from ' . ($oldData['status'] ? 'Active' : 'Inactive') . ' to ' . ($newStatus ? 'Active' : 'Inactive')
        ]);

        $message = $newStatus ? 'Warehouse activated successfully.' : 'Warehouse deactivated successfully.';

        return redirect()
            ->route('inventory.warehouses.index')
            ->with('success', $message);
    }

    public function logs($id)
    {
        $warehouse = $this->applyInstituteBranchFilter(
            InventoryWarehouse::where('id', $id)
        )->firstOrFail();

        $logs = InventoryActivityLog::where('institute_id', $this->instituteId())
            ->where('module', 'WAREHOUSE')
            ->where('record_id', $warehouse->id)
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('instituteAdmin.inventory.warehouse.logs', compact('warehouse', 'logs'));
    }

    public function stockSummary($id)
    {
        $warehouse = $this->applyInstituteBranchFilter(
            InventoryWarehouse::where('id', $id)
        )
        ->with(['items' => function ($query) {
            // Filter items by branch context
            if ($this->branchId() !== null) {
                $query->where('branch_id', $this->branchId());
            }
            $query->select(
                'id', 
                'item_name', 
                'item_code', 
                'current_stock', 
                'available_stock', 
                'reserved_stock', 
                'reorder_level', 
                'expiry_date',
                'warehouse_id'
            )
            ->where('current_stock', '>', 0);
        }])
        ->firstOrFail();

        $warehouse->updateUtilization();

        $totalStock = $warehouse->items->sum('current_stock');
        $uniqueProducts = $warehouse->items->count();
        
        // Count out of stock items with branch filter
        $outOfStockQuery = InventoryItem::where('warehouse_id', $id)
            ->where('institute_id', $this->instituteId());
        if ($this->branchId() !== null) {
            $outOfStockQuery->where('branch_id', $this->branchId());
        }
        $outOfStock = $outOfStockQuery->where(function($query) {
            $query->where('current_stock', '<=', 0)
                ->orWhere('current_stock', 'is', null);
        })->count();

        $lowStockItems = $warehouse->items->filter(function ($item) {
            return $item->available_stock <= $item->reorder_level && $item->current_stock > 0;
        })->count();

        return view('instituteAdmin.inventory.warehouse.stock-summary', compact(
            'warehouse',
            'totalStock',
            'uniqueProducts',
            'outOfStock',
            'lowStockItems'
        ));
    }

    public function updateUtilization($id)
    {
        $warehouse = $this->applyInstituteBranchFilter(
            InventoryWarehouse::where('id', $id)
        )->firstOrFail();
        
        $warehouse->updateUtilization();

        return response()->json([
            'success' => true,
            'message' => 'Utilization updated successfully',
            'current_utilization' => $warehouse->current_utilization,
            'percentage' => $warehouse->getUtilizationPercentageAttribute()
        ]);
    }

    private function getBlocks()
    {
        return AddBlock::where('status', 'active')
            ->where('institute_id', $this->instituteId())
            ->with('building')
            ->orderBy('name')
            ->get();
    }

    private function getFloors()
    {
        return AddFloor::where('status', 'active')
            ->where('institute_id', $this->instituteId())
            ->with('block')
            ->orderBy('floor_name')
            ->get();
    }

    private function getRooms()
    {
        return AddRooms::where('status', 'active')
            ->where('institute_id', $this->instituteId())
            ->with('floor')
            ->orderBy('room_name')
            ->get();
    }
}