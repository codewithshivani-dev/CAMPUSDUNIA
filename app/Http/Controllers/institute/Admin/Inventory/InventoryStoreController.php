<?php
// app/Http/Controllers/institute/Admin/Inventory/InventoryStoreController.php

namespace App\Http\Controllers\institute\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\InventoryStore;
use App\Models\Inventory\InventoryWarehouse;
use App\Models\Inventory\InventoryItem;
use App\Models\Inventory\InventoryActivityLog;
use App\Services\Inventory\InventoryLogger;
use App\Models\AddBlock;
use App\Models\AddFloor;
use App\Models\AddRooms;
use Illuminate\Support\Facades\DB;

class InventoryStoreController extends Controller
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

    public function index(Request $request)
    {
        $context = $this->getInstituteContext();
        $branchId = $context['branch_id'];
        
        // Filter based on user's access level (institute vs branch)
        $query = InventoryStore::where('institute_id', $this->instituteId())
            ->with(['warehouse']);
        
        // If user has branch context, only show branch-specific stores
        if ($branchId !== null) {
            $query->where('branch_id', $branchId);
        }
        // If user is parent institute (no branch), show all stores (both with and without branch)

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('store_name', 'LIKE', "%{$search}%")
                  ->orWhere('store_code', 'LIKE', "%{$search}%")
                  ->orWhere('contact_person', 'LIKE', "%{$search}%");
            });
        }

        $stores = $query->withCount(['items'])
            ->latest()
            ->paginate(10);

        // Get warehouses with branch filter
        $warehouseQuery = InventoryWarehouse::where('institute_id', $this->instituteId())
            ->where('status', 1);
        if ($branchId !== null) {
            $warehouseQuery->where('branch_id', $branchId);
        }
        $warehouses = $warehouseQuery->get();

        // Fix for warehouse relationship - eager load with fallback
        foreach ($stores as $store) {
            // Force load warehouse if not loaded
            if (!$store->relationLoaded('warehouse') || !$store->warehouse) {
                $warehouse = InventoryWarehouse::where('id', (string) $store->warehouse_id)
                    ->where('institute_id', $store->institute_id)
                    ->first();
                
                if ($warehouse) {
                    $store->setRelation('warehouse', $warehouse);
                }
            }
            $store->updateUtilization();
        }

        return view('instituteAdmin.inventory.store.index', compact('stores', 'warehouses'));
    }

    public function create()
    {
        $context = $this->getInstituteContext();
        $branchId = $context['branch_id'];
        
        $warehouseQuery = InventoryWarehouse::where('institute_id', $this->instituteId())
            ->where('status', 1);
        if ($branchId !== null) {
            $warehouseQuery->where('branch_id', $branchId);
        }
        $warehouses = $warehouseQuery->get();

        $buildings = $this->getBuildings();
        $blocks = $this->getBlocks();
        $floors = $this->getFloors();
        $rooms = $this->getRooms();

        return view('instituteAdmin.inventory.store.create', compact(
            'warehouses',
            'buildings',
            'blocks',
            'floors',
            'rooms'
        ));
    }

    public function store(Request $request)
    {
        $context = $this->getInstituteContext();
        $branchId = $context['branch_id'];
        
        $roomIds = $request->input('room_ids') ? explode(',', $request->input('room_ids')) : [];
        
        // Update validation rules to include default_store_room_id
        $request->validate([
            'warehouse_id' => 'required|exists:inventory_warehouses,id',
            'floor_id' => 'required|exists:add_floors,id',
            'room_ids' => 'required|string',
            'default_store_room_id' => 'required|exists:add_rooms,id',
            'store_name' => [
                'nullable',
                'max:255',
                'unique:inventory_stores,store_name,NULL,id,institute_id,' . $this->instituteId() . ',branch_id,' . ($branchId ?? 'NULL')
            ],
            'store_code' => [
                'nullable',
                'max:50',
                'unique:inventory_stores,store_code,NULL,id,institute_id,' . $this->instituteId() . ',branch_id,' . ($branchId ?? 'NULL')
            ],
        ]);

        if (!empty($roomIds)) {
            $validRooms = AddRooms::whereIn('id', $roomIds)
                ->where('room_type', 'store')
                ->where('institute_id', $this->instituteId())
                ->count();

            if ($validRooms !== count($roomIds)) {
                return back()->withErrors(['room_ids' => 'One or more selected rooms are invalid or are not store rooms.'])->withInput();
            }
        }

        // Validate that the default store room is in the selected rooms list
        $defaultStoreRoomId = $request->input('default_store_room_id');
        if (!in_array($defaultStoreRoomId, $roomIds)) {
            return back()->withErrors(['default_store_room_id' => 'The default store must be one of the selected rooms.'])->withInput();
        }

        $createdStores = [];
        $errors = [];

        DB::transaction(function () use ($request, $roomIds, &$createdStores, &$errors, $branchId, $defaultStoreRoomId) {
            $warehouse = InventoryWarehouse::where('institute_id', $this->instituteId())
                ->where('id', (string) $request->warehouse_id)
                ->where('status', 1);
            if ($branchId !== null) {
                $warehouse->where('branch_id', $branchId);
            }
            $warehouse = $warehouse->firstOrFail();

            $perRoomContact = $request->input('per_room_contact', []);
            $perRoomPhone = $request->input('per_room_phone', []);
            $perRoomEmail = $request->input('per_room_email', []);
            $perRoomCapacity = $request->input('per_room_capacity', []);

            $commonContact = $request->input('common_contact_person');
            $commonPhone = $request->input('common_phone');
            $commonEmail = $request->input('common_email');
            $commonCapacity = $request->input('common_capacity');

            $rooms = AddRooms::whereIn('id', $roomIds)->get()->keyBy('id');

            foreach ($roomIds as $roomId) {
                $room = $rooms->get($roomId);
                if (!$room) {
                    $errors[] = "Room ID {$roomId} not found.";
                    continue;
                }

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

                $storeName = $room->room_name . ' Store';
                $storeCode = 'ST-' . strtoupper($room->room_code ?? 'RM' . str_pad($roomId, 4, '0', STR_PAD_LEFT));

                // Determine if this store should be the default
                $isDefault = ($defaultStoreRoomId == $roomId);

                $existingStore = InventoryStore::where('institute_id', $this->instituteId())
                    ->where('room_id', (string) $roomId)
                    ->first();

                if ($existingStore) {
                    // Preserve branch_id (cannot change)
                    $existingStore->update([
                        'warehouse_id' => (string) $request->warehouse_id,
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
                        'is_default' => $isDefault,
                        'status' => $request->boolean('status', 1),
                        'floor_id' => (string) $request->floor_id,
                        'room_id' => (string) $roomId,
                        'updated_by' => auth()->id()
                        // branch_id is NOT updated - it's preserved
                    ]);

                    $createdStores[] = $existingStore;
                } else {
                    // Set branch_id from authenticated user
                    $store = InventoryStore::create([
                        'institute_id' => $this->instituteId(),
                        'branch_id' => $branchId, // Set branch_id from authenticated user
                        'warehouse_id' => (string) $request->warehouse_id,
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
                        'is_default' => $isDefault,
                        'status' => $request->boolean('status', 1),
                        'floor_id' => (string) $request->floor_id,
                        'room_id' => (string) $roomId,
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

            // Ensure only one store is marked as default in the warehouse
            // Find the default store from the created stores
            $defaultStoreId = null;
            foreach ($createdStores as $store) {
                if ($store->is_default) {
                    $defaultStoreId = $store->id;
                    break;
                }
            }

            // If no default store was found (shouldn't happen as we set it above), 
            // set the first created store as default
            if ($defaultStoreId === null && count($createdStores) > 0) {
                $defaultStoreId = $createdStores[0]->id;
                $createdStores[0]->update(['is_default' => 1]);
            }

            // Update all other stores in the same warehouse to not be default
            if ($defaultStoreId !== null) {
                $query = InventoryStore::where('institute_id', $this->instituteId())
                    ->where('warehouse_id', (string) $request->warehouse_id)
                    ->where('id', '!=', (string) $defaultStoreId);
                
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
            ->route('inventory.stores.index')
            ->with('success', $message);
    }

    public function view($id)
    {
        $store = $this->applyInstituteBranchFilter(
            InventoryStore::where('id', (string) $id)
        )
        ->with(['warehouse', 'items' => function($query) {
            // Also filter items by branch context
            if ($this->branchId() !== null) {
                $query->where('branch_id', $this->branchId());
            }
            $query->select('id', 'item_name', 'item_code', 'current_stock', 'available_stock', 'reserved_stock', 'reorder_level')
                ->where('current_stock', '>', 0)
                ->limit(20);
        }, 'creator', 'updater'])
        ->withCount(['items'])
        ->firstOrFail();

        // Force load warehouse if not loaded
        if (!$store->relationLoaded('warehouse') || !$store->warehouse) {
            $warehouse = InventoryWarehouse::where('id', (string) $store->warehouse_id)
                ->where('institute_id', $store->institute_id)
                ->first();
            if ($warehouse) {
                $store->setRelation('warehouse', $warehouse);
            }
        }

        $store->updateUtilization();

        $totalStock = $store->items->sum('current_stock');
        $uniqueProducts = $store->items->count();
        $outOfStock = $store->items->where('current_stock', '<=', 0)->count();
        $lowStockItems = $store->items->filter(function ($item) {
            return $item->available_stock <= $item->reorder_level;
        })->count();

        return view('instituteAdmin.inventory.store.view', compact(
            'store',
            'totalStock',
            'uniqueProducts',
            'outOfStock',
            'lowStockItems'
        ));
    }

    public function edit($id)
    {
        $store = $this->applyInstituteBranchFilter(
            InventoryStore::where('id', (string) $id)
        )->firstOrFail();

        // Force load warehouse if not loaded
        if (!$store->relationLoaded('warehouse') || !$store->warehouse) {
            $warehouse = InventoryWarehouse::where('id', (string) $store->warehouse_id)
                ->where('institute_id', $store->institute_id)
                ->first();
            if ($warehouse) {
                $store->setRelation('warehouse', $warehouse);
            }
        }

        $context = $this->getInstituteContext();
        $branchId = $context['branch_id'];
        
        $warehouseQuery = InventoryWarehouse::where('institute_id', $this->instituteId())
            ->where('status', 1);
        if ($branchId !== null) {
            $warehouseQuery->where('branch_id', $branchId);
        }
        $warehouses = $warehouseQuery->get();

        $buildings = $this->getBuildings();
        $blocks = $this->getBlocks();
        $floors = $this->getFloors();
        $rooms = $this->getRooms();

        return view('instituteAdmin.inventory.store.edit', compact(
            'store',
            'warehouses',
            'buildings',
            'blocks',
            'floors',
            'rooms'
        ));
    }

    public function update(Request $request, $id)
    {
        $context = $this->getInstituteContext();
        $branchId = $context['branch_id'];
        
        $store = $this->applyInstituteBranchFilter(
            InventoryStore::where('id', (string) $id)
        )->firstOrFail();

        // Update validation rules to include branch_id context
        $request->validate([
            'warehouse_id' => 'required|exists:inventory_warehouses,id',
            'store_name' => [
                'required',
                'max:255',
                'unique:inventory_stores,store_name,' . $id . ',id,institute_id,' . $this->instituteId() . ',branch_id,' . ($branchId ?? 'NULL')
            ],
            'store_code' => [
                'required',
                'max:50',
                'unique:inventory_stores,store_code,' . $id . ',id,institute_id,' . $this->instituteId() . ',branch_id,' . ($branchId ?? 'NULL')
            ],
            'capacity' => 'nullable|integer|min:0',
            'floor_id' => 'nullable|integer|min:0',
            'room_id' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $store, $branchId) {
            $oldData = $store->toArray();

            $warehouse = InventoryWarehouse::where('institute_id', $this->instituteId())
                ->where('id', (string) $request->warehouse_id)
                ->where('status', 1);
            if ($branchId !== null) {
                $warehouse->where('branch_id', $branchId);
            }
            $warehouse = $warehouse->firstOrFail();

            if ($request->boolean('is_default')) {
                $query = InventoryStore::where('institute_id', $this->instituteId())
                    ->where('warehouse_id', (string) $request->warehouse_id)
                    ->where('id', '!=', (string) $store->id);
                if ($branchId !== null) {
                    $query->where('branch_id', $branchId);
                }
                $query->update(['is_default' => 0]);
            }

            // Preserve branch_id (cannot change)
            $store->update([
                'warehouse_id' => (string) $request->warehouse_id,
                'store_name' => $request->store_name,
                'store_code' => strtoupper($request->store_code),
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
                'floor_id' => (string) $request->floor_id,
                'room_id' => (string) $request->room_id,
                'updated_by' => auth()->id()
                // branch_id is NOT updated - it's preserved
            ]);

            $store->updateUtilization();

            InventoryLogger::log([
                'module' => 'STORE',
                'action' => 'UPDATE',
                'record_id' => $store->id,
                'old_data' => $oldData,
                'new_data' => $store->fresh()->toArray(),
                'remarks' => 'Store updated: ' . $store->store_name
            ]);
        });

        return redirect()
            ->route('inventory.stores.index')
            ->with('success', 'Store Updated Successfully');
    }

    public function toggleStatus($id)
    {
        $store = $this->applyInstituteBranchFilter(
            InventoryStore::where('id', (string) $id)
        )->firstOrFail();

        $oldData = $store->toArray();
        $newStatus = $store->status ? 0 : 1;
        
        $store->update([
            'status' => $newStatus,
            'updated_by' => auth()->id()
        ]);

        InventoryLogger::log([
            'module' => 'STORE',
            'action' => 'status_toggle',
            'record_id' => $store->id,
            'old_data' => $oldData,
            'new_data' => $store->fresh()->toArray(),
            'remarks' => 'Store status changed from ' . ($oldData['status'] ? 'Active' : 'Inactive') . ' to ' . ($newStatus ? 'Active' : 'Inactive')
        ]);

        $message = $newStatus ? 'Store activated successfully.' : 'Store deactivated successfully.';

        return redirect()
            ->route('inventory.stores.index')
            ->with('success', $message);
    }

    public function destroy($id)
    {
        $store = $this->applyInstituteBranchFilter(
            InventoryStore::where('id', (string) $id)
        )->firstOrFail();

        if ($store->items()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete store because it has items. Please transfer or delete the items first.'
            ], 422);
        }

        if ($store->is_default) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete default store. Please set another store as default first.'
            ], 422);
        }

        DB::transaction(function () use ($store) {
            $oldData = $store->toArray();

            InventoryLogger::log([
                'module' => 'STORE',
                'action' => 'DELETE',
                'record_id' => $store->id,
                'old_data' => $oldData,
                'remarks' => 'Store deleted: ' . $store->store_name
            ]);

            $store->update(['deleted_by' => auth()->id()]);
            $store->delete();
        });

        return response()->json([
            'success' => true
        ]);
    }

    public function logs($id)
    {
        $store = $this->applyInstituteBranchFilter(
            InventoryStore::where('id', (string) $id)
        )->firstOrFail();

        $logs = InventoryActivityLog::where('institute_id', $this->instituteId())
            ->where('module', 'STORE')
            ->where('record_id', (string) $store->id)
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('instituteAdmin.inventory.store.logs', compact('store', 'logs'));
    }

    // ==================== LOCATION DATA ====================

    private function getBuildings()
    {
        return collect([]);
    }

    private function getBlocks()
    {
        return AddBlock::where('status', 'active')
            ->where('institute_id', $this->instituteId())
            ->orderBy('name')
            ->get();
    }

    private function getFloors()
    {
        return AddFloor::where('status', 'active')
            ->where('institute_id', $this->instituteId())
            ->orderBy('floor_name')
            ->get();
    }

    private function getRooms()
    {
        return AddRooms::where('status', 'active')
            ->where('institute_id', $this->instituteId())
            ->where('room_type', 'store')
            ->orderBy('room_name')
            ->get();
    }

    /**
     * Show stock summary for a specific store
     */
    public function stockSummary($id)
    {
        $store = $this->applyInstituteBranchFilter(
            InventoryStore::where('id', (string) $id)
        )
        ->with(['items' => function($query) {
            // Filter items by branch context
            if ($this->branchId() !== null) {
                $query->where('branch_id', $this->branchId());
            }
            $query->select('id', 'item_name', 'item_code', 'current_stock', 'available_stock', 'reserved_stock', 'reorder_level', 'expiry_date')
                ->where('current_stock', '>', 0);
        }, 'warehouse'])
        ->withCount(['items'])
        ->firstOrFail();

        // Force load warehouse if not loaded
        if (!$store->relationLoaded('warehouse') || !$store->warehouse) {
            $warehouse = InventoryWarehouse::where('id', (string) $store->warehouse_id)
                ->where('institute_id', $store->institute_id)
                ->first();
            if ($warehouse) {
                $store->setRelation('warehouse', $warehouse);
            }
        }

        // Update utilization
        $store->updateUtilization();

        // Calculate stats
        $totalStock = $store->items->sum('current_stock');
        $uniqueProducts = $store->items->count();
        $outOfStock = $store->items->where('current_stock', '<=', 0)->count();
        $lowStockItems = $store->items->filter(function ($item) {
            return $item->available_stock <= $item->reorder_level && $item->current_stock > 0;
        })->count();

        return view('instituteAdmin.inventory.store.stock-summary', compact(
            'store',
            'totalStock',
            'uniqueProducts',
            'outOfStock',
            'lowStockItems'
        ));
    }
}