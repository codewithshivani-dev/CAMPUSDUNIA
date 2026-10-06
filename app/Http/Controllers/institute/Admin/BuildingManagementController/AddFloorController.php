<?php

namespace App\Http\Controllers\institute\Admin\BuildingManagementController;
use App\Http\Controllers\Controller;

use App\Models\AddFloor;
use App\Models\AddBuilding;
use App\Models\AddBlock;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AddFloorController extends Controller
{
    /**
     * Display the floors management page.
     */
    public function page()
    {
        // Get all buildings for dropdown
        $buildings = AddBuilding::where('status', 'active')
            ->orderBy('name')
            ->get();
        
        // Get all blocks for the list
        $blocks = AddBlock::where('status', 'active')
            ->with('building')
            ->orderBy('name')
            ->get();

        // Get all floors for filter
        $floors = AddFloor::with(['building', 'block'])
            ->orderBy('building_id')
            ->orderBy('block_id')
            ->orderBy('floor_number')
            ->get();


        return view('instituteAdmin.CreateBuildings.buildingManagementFloors', compact('buildings', 'blocks', 'floors'));
    }

    /**
     * Display a listing of floors.
     */
    public function index(Request $request): JsonResponse
    {
        $query = AddFloor::with(['building' => function($query) {
                $query->select('id', 'name', 'code');
            }, 'block' => function($query) {
                $query->select('id', 'name', 'code');
            }]);

        // Apply filters
        $query->when($request->filled('building_id'), function ($q) use ($request) {
            return $q->where('building_id', $request->building_id);
        })
        ->when($request->filled('block_id'), function ($q) use ($request) {
            return $q->where('block_id', $request->block_id);
        })
        ->when($request->filled('institute_id'), function ($q) use ($request) {
            return $q->where('institute_id', $request->institute_id);
        })
        ->when($request->filled('branch_id'), function ($q) use ($request) {
            return $q->where('branch_id', $request->branch_id);
        })
        ->when($request->filled('status'), function ($q) use ($request) {
            return $q->where('status', $request->status);
        })
        ->when($request->filled('has_ac'), function ($q) use ($request) {
            return $q->where('has_ac', $request->boolean('has_ac'));
        })
        ->when($request->filled('has_wifi'), function ($q) use ($request) {
            return $q->where('has_wifi', $request->boolean('has_wifi'));
        })
        ->when($request->filled('has_washroom'), function ($q) use ($request) {
            return $q->where('has_washroom', $request->boolean('has_washroom'));
        })
        ->when($request->filled('has_disabled_access'), function ($q) use ($request) {
            return $q->where('has_disabled_access', $request->boolean('has_disabled_access'));
        })
        ->when($request->filled('floor_level'), function ($q) use ($request) {
            return $q->where('floor_level', $request->floor_level);
        })
        ->when($request->filled('min_available_rooms'), function ($q) use ($request) {
            return $q->where('available_rooms', '>=', $request->min_available_rooms);
        });

        // Sorting
        $sortBy = $request->sort_by ?? 'created_at';
        $sortOrder = $request->sort_order ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $floors = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $floors,
            'message' => 'Floors retrieved successfully.'
        ]);
    }

   
        /**
     * Store a newly created floor.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Parse floors from JSON string
            $floorsData = $request->input('floors');
            
            if (is_string($floorsData)) {
                $floorsData = json_decode($floorsData, true);
            }

            // If it's still a string, try to decode it again (nested JSON)
            if (is_string($floorsData)) {
                $floorsData = json_decode($floorsData, true);
            }

            if (!is_array($floorsData) || empty($floorsData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid floors data. Expected array of floors.'
                ], 422);
            }

            $buildingId = $request->input('building_id');
            $blockId = $request->input('block_id');

            // Validate building and block existence
            if (!$buildingId || !$blockId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Building ID and Block ID are required.'
                ], 422);
            }

            // Check if block belongs to building
            $block = AddBlock::find($blockId);
            if ($block && $block->building_id != $buildingId) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected block does not belong to the specified building.'
                ], 422);
            }

            DB::beginTransaction();

            $createdFloors = [];

            foreach ($floorsData as $floorData) {
                // Validate required fields for each floor
                if (empty($floorData['floor_number'])) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Each floor must have a floor_number.'
                    ], 422);
                }

                // Check for duplicate floor number within the same block
                $existingFloor = AddFloor::where('block_id', $blockId)
                    ->where('floor_number', $floorData['floor_number'])
                    ->first();

                if ($existingFloor) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Floor '{$floorData['floor_number']}' already exists in this block."
                    ], 422);
                }

                // Prepare data for creation
                $createData = [
                    'institute_id' => auth()->user()->institute_id,
                    'branch_id' => auth()->user()->branch_id,
                    'building_id' => $buildingId,
                    'block_id' => $blockId,
                    
                    // Basic fields
                    'floor_number' => $floorData['floor_number'] ?? null,
                    'description' => $floorData['description'] ?? null,
                    
                    // Area fields - set to 0 if empty or null
                    'total_area' => isset($floorData['area_value']) && $floorData['area_value'] !== '' && $floorData['area_value'] !== null
                        ? (float)$floorData['area_value']
                        : 0,  // Default to 0 instead of null
                    'area_unit' => $floorData['area_unit'] ?? 'sq_ft',
                    
                    // JSON fields
                    'additional_areas' => $floorData['additional_areas'] ?? [],
                    'gates' => $floorData['gates'] ?? [],
                    'custom_amenities' => $floorData['custom_amenities'] ?? [],
                    'allocated_amenities' => $floorData['allocated_amenities'] ?? [],
                    'allocated_facility_entries' => $floorData['allocated_facility_entries'] ?? [],
                ];

                // Map amenities to boolean fields
                $amenities = $floorData['allocated_amenities'] ?? [];
                
                // Map boolean flags from allocated_amenities
                $createData['has_ac'] = isset($amenities['ac']) ? (bool)$amenities['ac'] : false;
                $createData['has_wifi'] = isset($amenities['wifi']) ? (bool)$amenities['wifi'] : false;
                $createData['has_lift'] = isset($amenities['elevator']) ? (bool)$amenities['elevator'] : false;
                $createData['has_washroom'] = isset($amenities['washroom']) ? (bool)$amenities['washroom'] : false;
                
                // Map other amenities if your table has these columns
                $createData['has_fire_alarm'] = isset($amenities['fire_alarm']) ? (bool)$amenities['fire_alarm'] : false;
                $createData['has_fire_extinguisher'] = isset($amenities['fire_extinguisher']) ? (bool)$amenities['fire_extinguisher'] : false;
                $createData['has_library'] = isset($amenities['library']) ? (bool)$amenities['library'] : false;
                $createData['has_staff_room'] = isset($amenities['staff_room']) ? (bool)$amenities['staff_room'] : false;
                $createData['has_conference_room'] = isset($amenities['conference_room']) ? (bool)$amenities['conference_room'] : false;
                $createData['has_common_room'] = isset($amenities['common_room']) ? (bool)$amenities['common_room'] : false;
                $createData['has_projector_room'] = isset($amenities['projector_room']) ? (bool)$amenities['projector_room'] : false;
                $createData['has_disabled_access'] = isset($amenities['disabled_access']) ? (bool)$amenities['disabled_access'] : false;
                
                // Map room statistics - ensure numeric values
                $createData['total_rooms'] = isset($floorData['rooms']) && is_numeric($floorData['rooms']) 
                    ? (int)$floorData['rooms'] 
                    : 0;
                
                // Set occupied rooms (default 0)
                $createData['occupied_rooms'] = 0;
                
                // Available rooms = total rooms
                $createData['available_rooms'] = $createData['total_rooms'];
                
                // Total capacity (default to total rooms if not set)
                $createData['total_capacity'] = $createData['total_rooms'];
                
                // Lift count - default 0 if not set
                $createData['lift_count'] = isset($amenities['elevator_count']) ? (int)$amenities['elevator_count'] : 0;
                
                // Washroom count - default 0 if not set
                $createData['washroom_count'] = isset($amenities['washroom_count']) ? (int)$amenities['washroom_count'] : 0;
                
                // Status
                $createData['status'] = 'active';

                // Handle floor level (optional)
                if (isset($floorData['floor_level'])) {
                    $createData['floor_level'] = (int)$floorData['floor_level'];
                }

                // Create floor
                $floor = AddFloor::create($createData);
                $floor->load(['building', 'block']);
                $createdFloors[] = $floor;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($createdFloors) . ' floor(s) created successfully.',
                'data' => $createdFloors
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create floors.',
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Display the specified floor.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $floor = AddFloor::with(['building', 'block'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $floor,
                'message' => 'Floor retrieved successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Floor not found.'
            ], 404);
        }
    }

    /**
 * Update the specified floor.
 */
public function update(Request $request, string $id): JsonResponse
{
   
        // Get the floor data - try multiple ways to parse it
        $floorData = $request->input('floor');
        
        // Log the incoming data for debugging (remove in production)
        \Log::info('Update floor - raw input:', $request->all());
        \Log::info('Update floor - floor data:', ['floor' => $floorData]);
        
        // Check if data is coming as a JSON string
        if (is_string($floorData)) {
            $decoded = json_decode($floorData, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $floorData = $decoded;
            }
        }
        
        // Check if data is coming as a JSON array directly
        if (is_array($floorData) && isset($floorData[0]) && is_string($floorData[0])) {
            $decoded = json_decode($floorData[0], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $floorData = $decoded;
            }
        }
        
        // If still not parsed, check if the request has all fields directly
        if (!is_array($floorData) || empty($floorData)) {
            if ($request->has('floor_number') || $request->has('description')) {
                $floorData = $request->all();
            } else {
                $content = $request->getContent();
                if (!empty($content)) {
                    $decoded = json_decode($content, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        if (isset($decoded['floor'])) {
                            $floorData = $decoded['floor'];
                        } else {
                            $floorData = $decoded;
                        }
                    }
                }
            }
        }
        
        // If still not valid, return error with debug info
        if (!is_array($floorData) || empty($floorData)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid floor data. Expected array of floor fields.',
                'debug' => [
                    'received_type' => gettype($floorData),
                    'received_data' => $floorData,
                    'raw_input' => $request->all(),
                    'content' => $request->getContent()
                ]
            ], 422);
        }

        // Find the floor
        $floor = AddFloor::findOrFail($id);

        // Normalize JSON fields - convert strings to arrays if needed
        $jsonFields = ['additional_areas', 'gates', 'custom_amenities', 'allocated_amenities', 'allocated_facility_entries'];
        foreach ($jsonFields as $field) {
            if (isset($floorData[$field])) {
                if (is_string($floorData[$field])) {
                    $decoded = json_decode($floorData[$field], true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $floorData[$field] = $decoded;
                    } else {
                        // If it's not valid JSON, treat it as a single value or split by comma
                        if (strpos($floorData[$field], ',') !== false) {
                            $floorData[$field] = array_map('trim', explode(',', $floorData[$field]));
                        } else {
                            $floorData[$field] = [$floorData[$field]];
                        }
                    }
                }
                // Ensure it's an array
                if (!is_array($floorData[$field])) {
                    $floorData[$field] = (array)$floorData[$field];
                }
            }
        }

        // Prepare validation rules - making JSON fields nullable and accepting strings
        $validator = Validator::make($floorData, [
            'building_id' => 'sometimes|required|exists:buildings_page,id',
            'block_id' => 'sometimes|required|exists:add_blocks,id',
            'floor_number' => 'sometimes|required|string|max:50',
            'description' => 'nullable|string',
            
            // Area fields
            'area_value' => 'nullable|numeric|min:0',
            'area_unit' => 'nullable|string|in:sq_ft,sq_m',
            
            // JSON fields - accept array or string
            'additional_areas' => 'nullable',
            'gates' => 'nullable',
            'custom_amenities' => 'nullable',
            'allocated_amenities' => 'nullable|array',
            'allocated_facility_entries' => 'nullable',
            
            // Room statistics
            'rooms' => 'nullable|integer|min:0',
            'occupied_rooms' => 'nullable|integer|min:0',
            
            // Status
            'status' => 'nullable|in:active,inactive,under_maintenance',
            
            // Floor level
            'floor_level' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }

        // Check if block belongs to building
        $buildingId = $floorData['building_id'] ?? $floor->building_id;
        $blockId = $floorData['block_id'] ?? $floor->block_id;
        
        if (isset($floorData['block_id']) || isset($floorData['building_id'])) {
            $block = AddBlock::find($blockId);
            if ($block && $block->building_id != $buildingId) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected block does not belong to the specified building.'
                ], 422);
            }
        }

        // Check for unique floor number within block
        if (isset($floorData['floor_number']) || isset($floorData['block_id'])) {
            $floorNumber = $floorData['floor_number'] ?? $floor->floor_number;
            $blockId = $floorData['block_id'] ?? $floor->block_id;

            $existingFloor = AddFloor::where('block_id', $blockId)
                ->where('floor_number', $floorNumber)
                ->where('id', '!=', $id)
                ->first();

            if ($existingFloor) {
                return response()->json([
                    'success' => false,
                    'message' => 'A floor with this number already exists in the selected block.'
                ], 422);
            }
        }

        // Prepare data for update
        $updateData = [
            'institute_id' => auth()->user()->institute_id,
            'branch_id' => auth()->user()->branch_id,
        ];

        // Only update fields that are present in the request
        if (isset($floorData['building_id'])) {
            $updateData['building_id'] = $floorData['building_id'];
        }
        if (isset($floorData['block_id'])) {
            $updateData['block_id'] = $floorData['block_id'];
        }
        if (isset($floorData['floor_number'])) {
            $updateData['floor_number'] = $floorData['floor_number'];
        }
        if (isset($floorData['description'])) {
            $updateData['description'] = $floorData['description'];
        }
        if (isset($floorData['floor_level'])) {
            $updateData['floor_level'] = (int)$floorData['floor_level'];
        }
        if (isset($floorData['status'])) {
            $updateData['status'] = $floorData['status'];
        }

        // Handle area fields
        if (isset($floorData['area_value'])) {
            $updateData['total_area'] = $floorData['area_value'] !== '' && $floorData['area_value'] !== null
                ? (float)$floorData['area_value']
                : 0;
        }
        if (isset($floorData['area_unit'])) {
            $updateData['area_unit'] = $floorData['area_unit'];
        }

        // Handle JSON fields - ensure they are stored as arrays
        $jsonFields = ['additional_areas', 'gates', 'custom_amenities', 'allocated_amenities', 'allocated_facility_entries'];
        foreach ($jsonFields as $field) {
            if (isset($floorData[$field])) {
                if (is_array($floorData[$field])) {
                    $updateData[$field] = $floorData[$field];
                } elseif (is_string($floorData[$field])) {
                    // Try to decode JSON
                    $decoded = json_decode($floorData[$field], true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $updateData[$field] = $decoded;
                    } else {
                        // Store as array with single value
                        $updateData[$field] = [$floorData[$field]];
                    }
                } else {
                    $updateData[$field] = (array)$floorData[$field];
                }
            }
        }

        // Map amenities to boolean fields from allocated_amenities
        $amenities = $floorData['allocated_amenities'] ?? null;
        if ($amenities !== null && is_array($amenities)) {
            $amenityMapping = [
                'ac' => 'has_ac',
                'wifi' => 'has_wifi',
                'elevator' => 'has_lift',
                'washroom' => 'has_washroom',
                'fire_alarm' => 'has_fire_alarm',
                'fire_extinguisher' => 'has_fire_extinguisher',
                'library' => 'has_library',
                'staff_room' => 'has_staff_room',
                'conference_room' => 'has_conference_room',
                'common_room' => 'has_common_room',
                'projector_room' => 'has_projector_room',
                'disabled_access' => 'has_disabled_access',
            ];

            foreach ($amenityMapping as $amenityKey => $dbField) {
                $updateData[$dbField] = isset($amenities[$amenityKey]) 
                    ? (bool)$amenities[$amenityKey] 
                    : ($floor->{$dbField} ?? false);
            }

            // Handle counts
            if (isset($amenities['elevator_count'])) {
                $updateData['lift_count'] = (int)$amenities['elevator_count'];
            }
            if (isset($amenities['washroom_count'])) {
                $updateData['washroom_count'] = (int)$amenities['washroom_count'];
            }
        }

        // Handle rooms
        if (isset($floorData['rooms'])) {
            $updateData['total_rooms'] = (int)$floorData['rooms'];
            $updateData['total_capacity'] = (int)$floorData['rooms'];
            
            $occupiedRooms = $floorData['occupied_rooms'] ?? $floor->occupied_rooms ?? 0;
            $updateData['available_rooms'] = max(0, $updateData['total_rooms'] - $occupiedRooms);
        }

        // Handle occupied rooms separately
        if (isset($floorData['occupied_rooms'])) {
            $updateData['occupied_rooms'] = (int)$floorData['occupied_rooms'];
            $totalRooms = $updateData['total_rooms'] ?? $floor->total_rooms ?? 0;
            $updateData['available_rooms'] = max(0, $totalRooms - $updateData['occupied_rooms']);
        }

        // If only occupied_rooms is updated without rooms
        if (isset($floorData['occupied_rooms']) && !isset($floorData['rooms'])) {
            $totalRooms = $floor->total_rooms ?? 0;
            $updateData['available_rooms'] = max(0, $totalRooms - $updateData['occupied_rooms']);
        }

        // Update the floor
        $floor->update($updateData);

        // Reload relationships
        $floor->load(['building', 'block']);

        return response()->json([
            'success' => true,
            'data' => $floor,
            'message' => 'Floor updated successfully.'
        ]);

   
}

    /**
     * Remove the specified floor (soft delete).
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $floor = AddFloor::findOrFail($id);
            $floor->delete();

            return response()->json([
                'success' => true,
                'message' => 'Floor deleted successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Floor not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete floor.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore a soft-deleted floor.
     */
    public function restore(string $id): JsonResponse
    {
        try {
            $floor = AddFloor::withTrashed()->findOrFail($id);
            
            if (!$floor->trashed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Floor is not deleted.'
                ], 400);
            }

            $floor->restore();

            return response()->json([
                'success' => true,
                'data' => $floor,
                'message' => 'Floor restored successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Floor not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore floor.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Permanently delete a floor.
     */
    public function forceDelete(string $id): JsonResponse
    {
        try {
            $floor = AddFloor::withTrashed()->findOrFail($id);
            $floor->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Floor permanently deleted.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Floor not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to permanently delete floor.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get floor statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = AddFloor::query();

        if ($request->filled('building_id')) {
            $query->where('building_id', $request->building_id);
        }

        if ($request->filled('block_id')) {
            $query->where('block_id', $request->block_id);
        }

        if ($request->filled('institute_id')) {
            $query->where('institute_id', $request->institute_id);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        $totalFloors = $query->count();
        $activeFloors = $query->clone()->where('status', 'active')->count();
        $inactiveFloors = $query->clone()->where('status', 'inactive')->count();
        $underMaintenanceFloors = $query->clone()->where('status', 'under_maintenance')->count();
        
        $totalRooms = $query->clone()->sum('total_rooms');
        $occupiedRooms = $query->clone()->sum('occupied_rooms');
        $availableRooms = $query->clone()->sum('available_rooms');
        $totalCapacity = $query->clone()->sum('total_capacity');
        $totalArea = $query->clone()->sum('total_area');
        
        // Facility statistics
        $floorsWithAC = $query->clone()->where('has_ac', true)->count();
        $floorsWithWifi = $query->clone()->where('has_wifi', true)->count();
        $floorsWithWater = $query->clone()->where('has_water_facility', true)->count();
        $floorsWithWashroom = $query->clone()->where('has_washroom', true)->count();
        $floorsWithLift = $query->clone()->where('has_lift', true)->count();
        $floorsWithFireSafety = $query->clone()->where('has_fire_extinguisher', true)->count();
        $floorsWithDisabledAccess = $query->clone()->where('has_disabled_access', true)->count();
        
        // Special rooms
        $floorsWithProjectorRoom = $query->clone()->where('has_projector_room', true)->count();
        $floorsWithConferenceRoom = $query->clone()->where('has_conference_room', true)->count();
        $floorsWithLibrary = $query->clone()->where('has_library', true)->count();
        $floorsWithStaffRoom = $query->clone()->where('has_staff_room', true)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_floors' => $totalFloors,
                'active_floors' => $activeFloors,
                'inactive_floors' => $inactiveFloors,
                'under_maintenance_floors' => $underMaintenanceFloors,
                
                'room_statistics' => [
                    'total_rooms' => $totalRooms,
                    'occupied_rooms' => $occupiedRooms,
                    'available_rooms' => $availableRooms,
                    'occupancy_rate' => $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0,
                ],
                
                'capacity_statistics' => [
                    'total_capacity' => $totalCapacity,
                    'average_capacity_per_floor' => $totalFloors > 0 ? round($totalCapacity / $totalFloors, 2) : 0,
                ],
                
                'area_statistics' => [
                    'total_area' => $totalArea,
                    'average_area_per_floor' => $totalFloors > 0 ? round($totalArea / $totalFloors, 2) : 0,
                ],
                
                'facility_coverage' => [
                    'ac_coverage' => $totalFloors > 0 ? round(($floorsWithAC / $totalFloors) * 100, 2) : 0,
                    'wifi_coverage' => $totalFloors > 0 ? round(($floorsWithWifi / $totalFloors) * 100, 2) : 0,
                    'water_facility_coverage' => $totalFloors > 0 ? round(($floorsWithWater / $totalFloors) * 100, 2) : 0,
                    'washroom_coverage' => $totalFloors > 0 ? round(($floorsWithWashroom / $totalFloors) * 100, 2) : 0,
                    'lift_coverage' => $totalFloors > 0 ? round(($floorsWithLift / $totalFloors) * 100, 2) : 0,
                    'fire_safety_coverage' => $totalFloors > 0 ? round(($floorsWithFireSafety / $totalFloors) * 100, 2) : 0,
                    'disabled_access_coverage' => $totalFloors > 0 ? round(($floorsWithDisabledAccess / $totalFloors) * 100, 2) : 0,
                ],
                
                'special_rooms' => [
                    'floors_with_projector_room' => $floorsWithProjectorRoom,
                    'floors_with_conference_room' => $floorsWithConferenceRoom,
                    'floors_with_library' => $floorsWithLibrary,
                    'floors_with_staff_room' => $floorsWithStaffRoom,
                ],
            ],
            'message' => 'Floor statistics retrieved successfully.'
        ]);
    }

    /**
     * Get floors by building ID.
     */
    public function getByBuilding(string $buildingId): JsonResponse
    {
        try {
            $floors = AddFloor::where('building_id', $buildingId)
                ->with('block')
                ->orderBy('floor_level')
                ->orderBy('floor_number')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $floors,
                'message' => 'Floors retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve floors.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get floors by block ID.
     */
    public function getByBlock(string $blockId): JsonResponse
    {
        
        // try {
            $floors = AddFloor::where('block_id', $blockId)
                ->with('building')
                ->orderBy('floor_level')
                ->orderBy('floor_number')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $floors,
                'message' => 'Floors retrieved successfully.'
            ]);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to retrieve floors.',
        //         'error' => $e->getMessage()
        //     ], 500);
        // }
    }

    /**
     * Get blocks by building ID (for dropdown)
     */
    public function getBlocksByBuilding(string $buildingId): JsonResponse
    {
        try {
            $blocks = AddBlock::where('building_id', $buildingId)
                ->where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name', 'code']);
        
            return response()->json([
                'success' => true,
                'data' => $blocks,
                'message' => 'Blocks retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve blocks.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update floor status.
     */
    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,inactive,under_maintenance'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }

        try {
            $floor = AddFloor::findOrFail($id);
            $floor->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'data' => $floor,
                'message' => 'Floor status updated successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Floor not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update floor status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update room statistics.
     */
    public function updateRoomStats(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'total_rooms' => 'required|integer|min:0',
            'occupied_rooms' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }

        if ($request->occupied_rooms > $request->total_rooms) {
            return response()->json([
                'success' => false,
                'message' => 'Occupied rooms cannot exceed total rooms.'
            ], 422);
        }

        try {
            $floor = AddFloor::findOrFail($id);
            
            $data = [
                'total_rooms' => $request->total_rooms,
                'occupied_rooms' => $request->occupied_rooms,
                'available_rooms' => $request->total_rooms - $request->occupied_rooms
            ];

            $floor->update($data);

            return response()->json([
                'success' => true,
                'data' => $floor,
                'message' => 'Room statistics updated successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Floor not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update room statistics.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search floors.
     */
    public function search(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search', '');
        
        $floors = AddFloor::with(['building', 'block'])
            ->where(function($query) use ($searchTerm) {
                $query->where('floor_number', 'like', "%{$searchTerm}%")
                      ->orWhere('floor_name', 'like', "%{$searchTerm}%")
                      ->orWhere('description', 'like', "%{$searchTerm}%")
                      ->orWhereHas('building', function($q) use ($searchTerm) {
                          $q->where('name', 'like', "%{$searchTerm}%")
                            ->orWhere('code', 'like', "%{$searchTerm}%");
                      })
                      ->orWhereHas('block', function($q) use ($searchTerm) {
                          $q->where('name', 'like', "%{$searchTerm}%")
                            ->orWhere('code', 'like', "%{$searchTerm}%");
                      });
            })
            ->orderBy('floor_level')
            ->orderBy('floor_number')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $floors,
            'message' => 'Floors retrieved successfully.'
        ]);
    }

    /**
     * Display the floor details page.
     */
    public function showDetails($id)
    {
        $floor = AddFloor::with([
            'building:id,name,code',
            'block:id,name,code'
        ])->findOrFail($id);
    
        // Arrays (already cast by the model)
        $additionalAreas = $floor->additional_areas ?? [];
        $gates = $floor->gates ?? [];
        $customAmenities = $floor->custom_amenities ?? [];
        $allocatedAmenities = $floor->allocated_amenities ?? [];
    
        // IMPORTANT: Extract facility_entries BEFORE counting amenities
        $allocatedFacilities = $allocatedAmenities['facility_entries'] ?? [];
        
        // Remove facility_entries from amenities so it's not counted as an amenity
        unset($allocatedAmenities['facility_entries']);
    
        // Count only enabled amenities (value == 1)
        $amenitiesCount = collect($allocatedAmenities)
            ->filter(fn($value) => $value == 1)
            ->count();
    
        $facilitiesCount = count($allocatedFacilities);
    
        // Gates count
        $gatesCount = collect($gates)->filter(function ($gate) {
            return !empty($gate['name'] ?? null) || !empty($gate['number'] ?? null);
        })->count();
    
        // Areas count
        $areasCount = collect($additionalAreas)->filter(function ($area) {
            return !empty($area['name'] ?? null);
        })->count();
    
        // Custom amenities count
        $customAmenitiesCount = collect($customAmenities)->filter(function ($item) {
            return !empty($item['name'] ?? null);
        })->count();
    
        $totalAmenities = $amenitiesCount + $customAmenitiesCount;
    
        return view('instituteAdmin.CreateBuildings.floorsView', compact(
            'floor',
            'additionalAreas',
            'gates',
            'customAmenities',
            'allocatedAmenities',
            'allocatedFacilities',
            'gatesCount',
            'areasCount',
            'amenitiesCount',
            'facilitiesCount',
            'customAmenitiesCount',
            'totalAmenities'
        ));
    }
      /**
     * Display the floor edit page.
     */
    public function edit(string $id)
    {
        try {
            $floor = AddFloor::with([
                'building:id,name,code,facilities',
                'block:id,name,code,building_id,allocated_facilities,allocated_amenities'
            ])->findOrFail($id);

            $buildings = AddBuilding::where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name', 'code']);

            // ============================================================
            // Campus facilities — same robust lookup as showDetails
            // ============================================================
            $campusFacilities = [];

            if ($floor->building && !empty($floor->building->facilities)) {
                $campusFacilities = $floor->building->facilities;
            }

            if (empty($campusFacilities) && $floor->building_id) {
                $campusRecord = AddBuilding::find($floor->building_id);
                if ($campusRecord && !empty($campusRecord->facilities)) {
                    $campusFacilities = $campusRecord->facilities;
                }
            }

            if (empty($campusFacilities)) {
                $merchantId = auth()->user()->institute_id ?? null;
                if ($merchantId) {
                    $campusRecord = AddBuilding::where('institute_id', $merchantId)
                        ->whereNotNull('facilities')
                        ->first();
                    if ($campusRecord) {
                        $campusFacilities = $campusRecord->facilities;
                    }
                }
            }

            if (is_string($campusFacilities)) {
                $campusFacilities = json_decode($campusFacilities, true) ?: [];
            }
            if (!is_array($campusFacilities)) {
                $campusFacilities = [];
            }

            // ============================================================
            // Block-allocated facility IDs (floor can only pick from these)
            // ============================================================
            $blockAllocatedFacilityIds = [];

            if ($floor->block && !empty($floor->block->allocated_facilities)) {
                $raw = $floor->block->allocated_facilities;
                if (is_string($raw)) {
                    $decoded = json_decode($raw, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $raw = $decoded;
                    }
                }
                if (is_array($raw)) {
                    $blockAllocatedFacilityIds = array_values(array_filter($raw, function($v) {
                        return is_string($v) || is_numeric($v);
                    }));
                }
            }

            return view('instituteAdmin.CreateBuildings.floorsEdit', compact(
                'floor',
                'buildings',
                'campusFacilities',
                'blockAllocatedFacilityIds'
            ));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Floor not found');
        } catch (\Exception $e) {
            \Log::error('Error loading floor edit page: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            abort(500, 'Error loading floor edit page: ' . $e->getMessage());
        }
    }
}