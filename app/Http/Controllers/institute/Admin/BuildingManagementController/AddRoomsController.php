<?php

namespace App\Http\Controllers\institute\Admin\BuildingManagementController;

use App\Http\Controllers\Controller;
use App\Models\AddRooms;
use App\Models\AddFloor;
use App\Models\AddBlock;
use App\Models\RoomType;
use App\Models\AddBuilding;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AddRoomsController extends Controller
{
    /**
     * Display the rooms management page.
     */
    public function showRoomsPage(Request $request)
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
            
        return view('instituteAdmin.CreateBuildings.buildingManagementRooms', compact('buildings', 'blocks', 'floors'));
    }

   /**
 * Get all buildings (for dropdowns and filters).
 */
public function getAllBuildings(): JsonResponse
{
    
    try {
        Log::info('Fetching all buildings from AddRoomsController');
        
        // Get all buildings without status filter
        $buildings = AddBuilding::orderBy('name')
            ->get(['id', 'name', 'code', 'status']);

        Log::info('Buildings fetched from AddRoomsController:', [
            'count' => $buildings->count(),
            'buildings' => $buildings->toArray()
        ]);
        
        // If no buildings found, try to get from rooms
        if ($buildings->isEmpty()) {
            Log::warning('No buildings found in buildings table, trying to get from rooms');
            
            // Get unique building IDs from rooms
            $buildingIds = AddRooms::whereNotNull('building_id')
                ->distinct()
                ->pluck('building_id');
            
            if ($buildingIds->isNotEmpty()) {
                $buildings = AddBuilding::whereIn('id', $buildingIds)
                    ->orderBy('name')
                    ->get(['id', 'name', 'code', 'status']);
                Log::info('Buildings from rooms:', ['count' => $buildings->count()]);
            }
        }
        
        // If still no buildings, try to get from campus
        if ($buildings->isEmpty()) {
            Log::warning('No buildings found, checking campus data');
            
            $merchantId = auth()->user()->institute_id ?? null;
            if ($merchantId) {
                $campus = InstituteBasicDetails::where('fincap_merchant_id', $merchantId)->first();
                if ($campus) {
                    $building = AddBuilding::firstOrCreate(
                        ['institute_id' => $merchantId],
                        [
                            'name' => $campus->name ?? 'Main Campus',
                            'code' => $campus->fincap_merchant_id ?? 'CAMPUS',
                            'status' => 'active',
                            'area_value' => 0.00
                        ]
                    );
                    $buildings = collect([$building]);
                    Log::info('Created building from campus data:', $building->toArray());
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => $buildings,
            'message' => 'Buildings retrieved successfully.'
        ]);
    } catch (\Exception $e) {
        Log::error('Error fetching buildings from AddRoomsController:', [
            'message' => $e->getMessage(),
            'line' => $e->getLine()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve buildings.',
            'error' => $e->getMessage()
        ], 500);
    }
}
    /**
     * Get all blocks (for dropdowns and filters).
     */
    public function getAllBlocks(): JsonResponse
    {
        try {
            $blocks = AddBlock::where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name', 'building_id']);

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
     * Get all floors (for dropdowns and filters).
     */
    public function getAllFloors(): JsonResponse
    {
        try {
            $floors = AddFloor::where('status', 'active')
                ->orderBy('floor_number')
                ->get(['id', 'floor_number', 'block_id', 'building_id']);

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
     * Get blocks for dropdown.
     */
    public function getBlocks(string $buildingId): JsonResponse
    {
        try {
            $blocks = AddBlock::where('building_id', $buildingId)
                ->where('status', 'Active')
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
     * Get floors by block ID for dropdown.
     */
    public function getFloorsByBlock(string $blockId): JsonResponse
    {
        try {
            $floors = AddFloor::where('block_id', $blockId)
                ->where('status', 'active')
                ->orderBy('floor_number')
                ->get(['id', 'floor_number']);

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
     * Get floor details by ID.
     */
    public function getFloorDetails(string $id): JsonResponse
    {
        try {
            $floor = AddFloor::with(['building', 'block'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $floor,
                'message' => 'Floor details retrieved successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Floor not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve floor details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get room statistics.
     */
    public function getRoomStatistics(): JsonResponse
    {
        try {
            $totalRooms = AddRooms::count();
            $roomsWithAC = AddRooms::where('has_ac', 'yes')->count();
            $roomsWithWashroom = AddRooms::where('has_washroom', true)->count();
            $totalCapacity = AddRooms::sum('capacity') ?? 0;
            
            // Room type distribution
            $classrooms = AddRooms::where('room_type', 'classroom')->count();
            $labs = AddRooms::where('room_type', 'lab')->count();
            $offices = AddRooms::where('room_type', 'office')->count();
            $conferenceRooms = AddRooms::where('room_type', 'conference')->count();
            $libraries = AddRooms::where('room_type', 'library')->count();
            $otherRooms = AddRooms::where('room_type', 'other')->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_rooms' => $totalRooms,
                    'rooms_with_ac' => $roomsWithAC,
                    'rooms_with_washroom' => $roomsWithWashroom,
                    'total_capacity' => $totalCapacity,
                    'room_type_distribution' => [
                        'classrooms' => $classrooms,
                        'labs' => $labs,
                        'offices' => $offices,
                        'conference_rooms' => $conferenceRooms,
                        'libraries' => $libraries,
                        'other_rooms' => $otherRooms
                    ]
                ],
                'message' => 'Room statistics retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve room statistics.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search rooms by various criteria.
     */
    public function searchRooms(Request $request): JsonResponse
    {
        try {
            $query = AddRooms::query();
            
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('room_number', 'like', '%' . $searchTerm . '%')
                      ->orWhere('room_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%')
                      ->orWhere('room_type', 'like', '%' . $searchTerm . '%')
                      ->orWhereHas('building', function($q) use ($searchTerm) {
                          $q->where('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('code', 'like', '%' . $searchTerm . '%');
                      })
                      ->orWhereHas('block', function($q) use ($searchTerm) {
                          $q->where('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('code', 'like', '%' . $searchTerm . '%');
                      })
                      ->orWhereHas('floor', function($q) use ($searchTerm) {
                          $q->where('floor_number', 'like', '%' . $searchTerm . '%');
                      });
                });
            }
            
            // Filter by block
            if ($request->filled('block_id')) {
                $query->where('block_id', $request->block_id);
            }
            
            // Filter by floor
            if ($request->filled('floor_id')) {
                $query->where('floor_id', $request->floor_id);
            }
            
            // Filter by amenities
            if ($request->filled('amenity')) {
                switch($request->amenity) {
                    case 'ac':
                        $query->where('has_ac', 'yes');
                        break;
                    case 'washroom':
                        $query->where('has_washroom', true);
                        break;
                    case 'fire':
                        $query->where(function($q) {
                            $q->where('has_fire_extinguisher', true)
                              ->orWhere('has_fire_alarm', true)
                              ->orWhere('has_smoke_detector', true);
                        });
                        break;
                    case 'projector':
                        $query->where('has_projector', true);
                        break;
                }
            }

            $rooms = $query->with(['building', 'block', 'floor'])
                ->orderBy('block_id')
                ->orderBy('floor_id')
                ->orderBy('room_number')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $rooms,
                'message' => 'Rooms search completed successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to search rooms.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =========================== ROOMS CRUD METHODS ===========================

   /**
     * Display a listing of rooms.
     */
    public function index(Request $request): JsonResponse
    {
        
        // try {
            $instituteId = auth()->user()->institute_id;
           
            // Build query with eager loading
            $query = AddRooms::with([
                'block.building', 
                'floor.block.building'
            ])
            ->whereHas('block.building', function($q) use ($instituteId) {
                $q->where('institute_id', $instituteId);
            });

            // Apply filters
            if ($request->has('block_id') && $request->block_id) {
                $query->where('block_id', $request->block_id);
            }

            if ($request->has('floor_id') && $request->floor_id) {
                $query->where('floor_id', $request->floor_id);
            }

            if ($request->has('room_type') && $request->room_type) {
                $query->where('room_type', $request->room_type);
            }

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('room_number', 'like', "%{$search}%")
                      ->orWhere('room_name', 'like', "%{$search}%")
                      ->orWhereHas('block', function($b) use ($search) {
                          $b->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('block.building', function($b) use ($search) {
                          $b->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('floor', function($f) use ($search) {
                          $f->where('floor_number', 'like', "%{$search}%");
                      });
                });
            }

            $rooms = $query->orderBy('created_at', 'desc')
                ->paginate($request->per_page ?? 15);
           
            // Transform data to include full relationships
            $rooms->getCollection()->transform(function($room) {
                // Ensure building is loaded through block
                $building = null;
                if ($room->block && $room->block->building) {
                    $building = [
                        'id' => $room->block->building->id,
                        'name' => $room->block->building->name,
                        'code' => $room->block->building->code,
                    ];
                }

                // Ensure floor is loaded
                $floor = null;
                if ($room->floor) {
                    $floor = [
                        'id' => $room->floor->id,
                        'floor_number' => $room->floor->floor_number,
                    ];
                }

                // Ensure block is loaded
                $block = null;
                if ($room->block) {
                    $block = [
                        'id' => $room->block->id,
                        'name' => $room->block->name,
                        'code' => $room->block->code,
                    ];
                }

                // Decode room specifications if stored as JSON
                $specifications = [];
                if ($room->room_specifications) {
                    if (is_array($room->room_specifications)) {
                        $specifications = $room->room_specifications;
                    } else {
                        $specifications = json_decode($room->room_specifications, true) ?? [];
                    }
                }

                return [
                    'id' => $room->id,
                    'room_number' => $room->room_number,
                    'room_name' => $room->room_name,
                    'room_type' => $room->room_type,
                    'custom_room_type' => $room->custom_room_type,
                    'status' => $room->status ?? 'active',
                    'occupancy_status' => $room->occupancy_status ?? 'vacant',
                    'capacity' => $specifications['capacity'] ?? $room->capacity ?? null,
                    'max_capacity' => $specifications['max_capacity'] ?? $room->max_capacity ?? null,
                    'has_ac' => $specifications['has_ac'] ?? $room->has_ac ?? false,
                    'has_washroom' => $specifications['has_washroom'] ?? $room->has_washroom ?? false,
                    'has_wifi' => $specifications['has_wifi'] ?? $room->has_wifi ?? false,
                    'has_projector' => $specifications['has_projector'] ?? $room->has_projector ?? false,
                    'has_cctv' => $specifications['has_cctv'] ?? $room->has_cctv ?? false,
                    'has_whiteboard' => $specifications['has_whiteboard'] ?? $room->has_whiteboard ?? false,
                    'has_smart_board' => $specifications['has_smart_board'] ?? $room->has_smart_board ?? false,
                    'has_fire_extinguisher' => $specifications['has_fire_extinguisher'] ?? $room->has_fire_extinguisher ?? false,
                    'has_water_cooler' => $specifications['has_water_cooler'] ?? $room->has_water_cooler ?? false,
                    'has_lan' => $specifications['has_lan'] ?? $room->has_lan ?? false,
                    'has_telephone' => $specifications['has_telephone'] ?? $room->has_telephone ?? false,
                    'has_desk' => $specifications['has_desk'] ?? $room->has_desk ?? false,
                    'has_chair' => $specifications['has_chair'] ?? $room->has_chair ?? false,
                    'has_cabinets' => $specifications['has_cabinets'] ?? $room->has_cabinets ?? false,
                    'has_bed' => $specifications['has_bed'] ?? $room->has_bed ?? false,
                    'has_tv' => $specifications['has_tv'] ?? $room->has_tv ?? false,
                    'has_kitchenette' => $specifications['has_kitchenette'] ?? $room->has_kitchenette ?? false,
                    'has_fridge' => $specifications['has_fridge'] ?? $room->has_fridge ?? false,
                    'has_microwave' => $specifications['has_microwave'] ?? $room->has_microwave ?? false,
                    'has_fire_alarm' => $specifications['has_fire_alarm'] ?? $room->has_fire_alarm ?? false,
                    'has_smoke_detector' => $specifications['has_smoke_detector'] ?? $room->has_smoke_detector ?? false,
                    'has_sprinkler' => $specifications['has_sprinkler'] ?? $room->has_sprinkler ?? false,
                    'has_emergency_exit' => $specifications['has_emergency_exit'] ?? $room->has_emergency_exit ?? false,
                    'has_emergency_light' => $specifications['has_emergency_light'] ?? $room->has_emergency_light ?? false,
                    'has_door_lock' => $specifications['has_door_lock'] ?? $room->has_door_lock ?? false,
                    'has_smart_lock' => $specifications['has_smart_lock'] ?? $room->has_smart_lock ?? false,
                    'has_intercom' => $specifications['has_intercom'] ?? $room->has_intercom ?? false,
                    'has_wheelchair_access' => $specifications['has_wheelchair_access'] ?? $room->has_wheelchair_access ?? false,
                    'has_grab_bars' => $specifications['has_grab_bars'] ?? $room->has_grab_bars ?? false,
                    'has_visual_alerts' => $specifications['has_visual_alerts'] ?? $room->has_visual_alerts ?? false,
                    'block_id' => $room->block_id,
                    'floor_id' => $room->floor_id,
                    'block' => $block,
                    'floor' => $floor,
                    'building' => $building,
                    'created_at' => $room->created_at,
                    'updated_at' => $room->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $rooms,
                'message' => 'Rooms retrieved successfully.'
            ]);
        // } catch (\Exception $e) {
        //     Log::error('Error fetching rooms: ' . $e->getMessage());
        //     Log::error('Trace: ' . $e->getTraceAsString());
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to retrieve rooms.',
        //         'error' => $e->getMessage()
        //     ], 500);
        // }
    }


    /**
     * Store multiple rooms (bulk).
     */
    public function bulkStore(Request $request): JsonResponse
    {
        Log::info('Bulk Room Store Request:', $request->all());
       
        // Get floor_id from request
        $floorId = $request->input('floor_id');
        
        // Get rooms data
        $roomsData = $request->input('rooms');
        if (is_string($roomsData)) {
            $roomsData = json_decode($roomsData, true);
        }

        if (!is_array($roomsData) || empty($roomsData)) {
            return response()->json([
                'success' => false,
                'message' => 'No rooms data provided.'
            ], 422);
        }

        if (!$floorId) {
            return response()->json([
                'success' => false,
                'message' => 'Floor ID is required.'
            ], 422);
        }

        // Validate floor exists
        $floor = AddFloor::find($floorId);
        if (!$floor) {
            return response()->json([
                'success' => false,
                'message' => 'Floor not found.'
            ], 404);
        }

        $errors = [];
        $createdRooms = [];
        $roomNumbers = [];

        DB::beginTransaction();

        // try {
            foreach ($roomsData as $index => $roomData) {
                // Skip if room_number is missing
                if (empty($roomData['room_number'])) {
                    $errors[] = "Room #" . ($index + 1) . " is missing room number.";
                    continue;
                }

                // Check for duplicate room numbers in this batch
                $roomNumber = $roomData['room_number'];
                if (in_array($roomNumber, $roomNumbers)) {
                    $errors[] = "Duplicate room number '{$roomNumber}' found in batch.";
                    continue;
                }
                $roomNumbers[] = $roomNumber;

                // Check if room with same number exists on this floor
                $existingRoom = AddRooms::where('floor_id', $floorId)
                    ->where('room_number', $roomNumber)
                    ->first();

                if ($existingRoom) {
                    $errors[] = "Room '{$roomNumber}' already exists on this floor.";
                    continue;
                }

                // Remove the 'id' field if it exists (it's only for frontend tracking)
                unset($roomData['id']);

                // Set default values for JSON fields
                $roomData['room_specifications'] = $roomData['room_specifications'] ?? [];
                $roomData['allocated_amenities'] = $roomData['allocated_amenities'] ?? [];
                $roomData['selected_floor_amenities'] = $roomData['selected_floor_amenities'] ?? [];
                $roomData['selected_floor_facilities'] = $roomData['selected_floor_facilities'] ?? [];
                $roomData['additional_areas'] = $roomData['additional_areas'] ?? [];
                $roomData['gates'] = $roomData['gates'] ?? [];
                $roomData['custom_amenities'] = $roomData['custom_amenities'] ?? [];
                
                // Set floor_id, building_id, block_id
                $roomData['floor_id'] = $floorId;
                $roomData['building_id'] = $floor->building_id;
                $roomData['block_id'] = $floor->block_id;
                $roomData['institute_id'] = auth()->user()->institute_id ?? null;
                $roomData['branch_id'] = auth()->user()->branch_id ?? null;

                // Handle custom_room_type
                if (isset($roomData['room_type']) && $roomData['room_type'] === 'other' && !empty($roomData['custom_room_type'])) {
                    $roomData['custom_room_type'] = $roomData['custom_room_type'];
                } else {
                    $roomData['custom_room_type'] = null;
                }

                // Handle has_ac - ensure it's set to 'yes' or 'no'
                if (array_key_exists('has_ac', $roomData)) {
                    $acValue = $roomData['has_ac'];
                    if (is_bool($acValue)) {
                        $roomData['has_ac'] = $acValue ? 'yes' : 'no';
                    } elseif (is_string($acValue)) {
                        $roomData['has_ac'] = in_array(strtolower($acValue), ['yes', 'true', '1', 'on']) ? 'yes' : 'no';
                    } else {
                        $roomData['has_ac'] = $acValue ? 'yes' : 'no';
                    }
                } else {
                    $roomData['has_ac'] = 'no';
                }

                // Handle default values for enum fields
                $enumFields = [
                    'occupancy_type' => 'single',
                    'category' => 'standard',
                    'usage_type' => 'permanent',
                    'booking_required' => 'no',
                    'door_type' => 'single',
                    'window_type' => 'casement',
                    'lighting_type' => 'led',
                    'ac_type' => 'none',
                    'network_type' => 'both',
                    'security_level' => 'medium',
                    'washroom_type' => 'attached',
                    'accessibility_level' => 'none',
                    'status' => 'active',
                    'occupancy_status' => 'vacant'
                ];

                foreach ($enumFields as $field => $default) {
                    if (empty($roomData[$field])) {
                        $roomData[$field] = $default;
                    }
                }

             
                 
                $room = AddRooms::create($roomData);
                $createdRooms[] = $room;
            }

            if (empty($createdRooms)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No rooms were created. Errors: ' . implode('; ', $errors),
                    'errors' => $errors
                ], 422);
            }

            // Update floor room statistics
            $this->updateFloorStatistics($floorId);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $createdRooms,
                'errors' => $errors,
                'message' => count($createdRooms) . ' room(s) created successfully.' . (!empty($errors) ? ' Note: ' . implode('; ', $errors) : '')
            ]);

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     Log::error('Bulk room creation error:', [
        //         'message' => $e->getMessage(),
        //         'line' => $e->getLine(),
        //         'trace' => $e->getTraceAsString()
        //     ]);
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to create rooms: ' . $e->getMessage(),
        //         'error' => $e->getMessage(),
        //         'line' => $e->getLine()
        //     ], 500);
        // }
    }

    /**
     * Update floor statistics.
     */
    private function updateFloorStatistics(string $floorId): void
    {
        $floor = AddFloor::find($floorId);
        if (!$floor) return;

        $totalRooms = AddRooms::where('floor_id', $floorId)->count();
        $occupiedRooms = AddRooms::where('floor_id', $floorId)
            ->where('occupancy_status', 'occupied')
            ->count();
        $totalCapacity = AddRooms::where('floor_id', $floorId)->sum('capacity') ?? 0;

        $floor->update([
            'total_rooms' => $totalRooms,
            'occupied_rooms' => $occupiedRooms,
            'available_rooms' => $totalRooms - $occupiedRooms,
            'total_capacity' => $totalCapacity,
        ]);
    }

    /**
     * Display the specified room.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $room = AddRooms::with(['building', 'block', 'floor'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $room,
                'message' => 'Room retrieved successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room not found.'
            ], 404);
        }
    }

           /**
     * Display the room edit page.
     */
    public function edit(string $id)
    {
        try {
            $room = AddRooms::with([
                'building',
                'block',
                'floor'
            ])->findOrFail($id);

            // ----------------------------------------------------------
            // Load room's existing JSON fields
            // ----------------------------------------------------------
            $getData = function($field) use ($room) {
                $value = $room->$field;
                if (is_array($value) || is_object($value)) return (array) $value;
                if (is_string($value)) {
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE) return $decoded ?? [];
                }
                return [];
            };

            $roomSpecifications       = $getData('room_specifications');
            $selectedFloorAmenities   = $getData('selected_floor_amenities');
            $selectedFloorFacilities  = $getData('selected_floor_facilities');
            $roomAdditionalAreas      = $getData('additional_areas');
            $roomGates                = $getData('gates');
            $roomCustomAmenities      = $getData('custom_amenities');

            // ----------------------------------------------------------
            // Facilities + Amenities from the parent FLOOR
            // ----------------------------------------------------------
            $floorAllocatedAmenities  = $room->floor->allocated_amenities ?? [];
            $floorAllocatedFacilities = $room->floor->allocated_facility_entries
                ?? ($room->floor->allocated_amenities['facility_entries'] ?? []);

            if (is_string($floorAllocatedAmenities)) {
                $floorAllocatedAmenities = json_decode($floorAllocatedAmenities, true) ?: [];
            }
            if (is_string($floorAllocatedFacilities)) {
                $floorAllocatedFacilities = json_decode($floorAllocatedFacilities, true) ?: [];
            }
            if (!is_array($floorAllocatedAmenities)) $floorAllocatedAmenities = [];
            if (!is_array($floorAllocatedFacilities)) $floorAllocatedFacilities = [];

            // ----------------------------------------------------------
            // Campus facilities JSON (for resolving facility IDs → names)
            // ----------------------------------------------------------
            $campusFacilities = [];

            if ($room->building && !empty($room->building->facilities)) {
                $campusFacilities = $room->building->facilities;
            }
            if (empty($campusFacilities) && $room->building_id) {
                $campusRecord = AddBuilding::find($room->building_id);
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

            // Build the lookup map (ID → { name, type_label, icon })
            $facilityLookup = [];
            $icons = [
                'parking' => 'fa-parking', 'playground' => 'fa-futbol',
                'swimming_pool' => 'fa-swimming-pool', 'clubhouse' => 'fa-home',
                'warehouse' => 'fa-warehouse', 'store_room' => 'fa-boxes',
                'auditorium' => 'fa-theater-masks', 'washrooms' => 'fa-restroom',
            ];
            $labels = [
                'parking' => 'Parking', 'playground' => 'Playground',
                'swimming_pool' => 'Swimming Pool', 'clubhouse' => 'Clubhouse',
                'warehouse' => 'Warehouse', 'store_room' => 'Store Room',
                'auditorium' => 'Auditorium', 'washrooms' => 'Washroom',
            ];

            foreach ($campusFacilities as $type => $typeData) {
                if (!is_array($typeData)) continue;
                $icon = $icons[$type] ?? 'fa-building';
                $label = $labels[$type] ?? ucfirst(str_replace('_', ' ', $type));

                if ($type === 'clubhouse' && !empty($typeData['id'])) {
                    $facilityLookup[$typeData['id']] = [
                        'name' => $typeData['name'] ?? $label,
                        'type_label' => $label,
                        'icon' => $icon,
                    ];
                    continue;
                }

                if ($type === 'washrooms') {
                    $entries = $typeData['detailed_washrooms']
                        ?? $typeData['entries']
                        ?? (isset($typeData[0]) ? $typeData : []);
                    foreach ($entries as $e) {
                        if (!empty($e['id'])) {
                            $facilityLookup[$e['id']] = [
                                'name' => $e['name'] ?? $label,
                                'type_label' => $label,
                                'icon' => $icon,
                            ];
                        }
                    }
                    continue;
                }

                foreach (['basement', 'open', 'indoor', 'outdoor', 'entries'] as $nk) {
                    if (!empty($typeData[$nk]) && is_array($typeData[$nk])) {
                        foreach ($typeData[$nk] as $e) {
                            if (is_array($e) && !empty($e['id'])) {
                                $facilityLookup[$e['id']] = [
                                    'name' => $e['name'] ?? $label,
                                    'type_label' => $label,
                                    'icon' => $icon,
                                ];
                            }
                        }
                    }
                }
            }

            // ----------------------------------------------------------
            // Resolve floor's allocated facility IDs → names (for checkboxes)
            // ----------------------------------------------------------
            $floorFacilityOptions = [];
            foreach ($floorAllocatedFacilities as $id) {
                if (isset($facilityLookup[$id])) {
                    $floorFacilityOptions[] = array_merge(
                        ['id' => $id],
                        $facilityLookup[$id]
                    );
                } else {
                    $floorFacilityOptions[] = [
                        'id' => $id,
                        'name' => 'Unknown Facility',
                        'type_label' => 'Unknown',
                        'icon' => 'fa-question-circle',
                    ];
                }
            }

            return view('instituteAdmin.CreateBuildings.roomsEdit', compact(
                'room',
                'roomSpecifications',
                'selectedFloorAmenities',
                'selectedFloorFacilities',
                'roomAdditionalAreas',
                'roomGates',
                'roomCustomAmenities',
                'floorAllocatedAmenities',
                'floorFacilityOptions',
                'campusFacilities'
            ));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Room not found');
        } catch (\Exception $e) {
            Log::error('Error loading room edit page: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            abort(500, 'Error loading room edit page: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified room.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $room = AddRooms::findOrFail($id);

            $validator = Validator::make($request->all(), $this->getValidationRules(true));

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed.'
                ], 422);
            }

            // Check relationships if changing building/block/floor
            if ($request->hasAny(['building_id', 'block_id', 'floor_id'])) {
                $buildingId = $request->building_id ?? $room->building_id;
                $blockId = $request->block_id ?? $room->block_id;
                $floorId = $request->floor_id ?? $room->floor_id;

                // Check if block belongs to building
                if ($buildingId && $blockId) {
                    $block = AddBlock::find($blockId);
                    if ($block && $block->building_id != $buildingId) {
                        return response()->json([
                            'success' => false,
                            'message' => 'The selected block does not belong to the specified building.'
                        ], 422);
                    }
                }

                // Check if floor belongs to block and building
                if ($floorId) {
                    $floor = AddFloor::find($floorId);
                    if ($floor) {
                        if ($blockId && $floor->block_id != $blockId) {
                            return response()->json([
                                'success' => false,
                                'message' => 'The selected floor does not belong to the specified block.'
                            ], 422);
                        }
                        if ($buildingId && $floor->building_id != $buildingId) {
                            return response()->json([
                                'success' => false,
                                'message' => 'The selected floor does not belong to the specified building.'
                            ], 422);
                        }
                    }
                }
            }

            // Check for unique room number within floor
            if ($request->has('room_number') || $request->has('floor_id')) {
                $floorId = $request->floor_id ?? $room->floor_id;
                $roomNumber = $request->room_number ?? $room->room_number;

                if ($floorId) {
                    $existingRoom = AddRooms::where('floor_id', $floorId)
                        ->where('room_number', $roomNumber)
                        ->where('id', '!=', $id)
                        ->first();

                    if ($existingRoom) {
                        return response()->json([
                            'success' => false,
                            'message' => 'A room with this number already exists on the selected floor.'
                        ], 422);
                    }
                }
            }

            // Validate maintenance dates
            if ($request->filled('last_maintenance_date') && $request->filled('next_maintenance_date')) {
                $lastMaintenance = Carbon::parse($request->last_maintenance_date);
                $nextMaintenance = Carbon::parse($request->next_maintenance_date);
                
                if ($nextMaintenance->lte($lastMaintenance)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Next maintenance date must be after last maintenance date.'
                    ], 422);
                }
            }

            $data = $request->all();
            
            // Set updated_by if user is authenticated
            if (auth()->check()) {
                $data['updated_by'] = auth()->id();
            }

            // Handle custom_room_type
            if ($request->room_type === 'other' && $request->filled('custom_room_type')) {
                $data['custom_room_type'] = $request->custom_room_type;
            } elseif ($request->room_type !== 'other') {
                $data['custom_room_type'] = null;
            }

            // Handle JSON fields
            $jsonFields = [
                'room_specifications' => [],
                'selected_floor_amenities' => [],
                'selected_floor_facilities' => [],
                'additional_areas' => [],
                'gates' => [],
                'custom_amenities' => [],
                'allocated_amenities' => [],
            ];

            foreach ($jsonFields as $field => $default) {
                if ($request->has($field)) {
                    $value = $request->$field;
                    if (is_string($value)) {
                        $value = json_decode($value, true);
                    }
                    $data[$field] = $value ?? $default;
                }
            }

            // Ensure boolean fields are properly cast
            $booleanFields = [
                'has_wifi', 'has_lan', 'has_telephone', 'has_cctv',
                'has_projector', 'has_whiteboard', 'has_smart_board',
                'has_desk', 'has_chair', 'has_cabinets', 'has_bed', 'has_tv',
                'has_kitchenette', 'has_fridge', 'has_microwave', 'has_water_cooler',
                'has_fire_extinguisher', 'has_fire_alarm', 'has_smoke_detector',
                'has_sprinkler', 'has_emergency_exit', 'has_emergency_light',
                'has_door_lock', 'has_smart_lock', 'has_intercom',
                'has_washroom', 'has_hot_water', 'has_shower', 'has_bathtub',
                'has_wheelchair_access', 'has_grab_bars', 'has_visual_alerts'
            ];

            foreach ($booleanFields as $field) {
                if ($request->has($field)) {
                    $data[$field] = filter_var($request->$field, FILTER_VALIDATE_BOOLEAN);
                }
            }

            // Handle has_ac
            if ($request->has('has_ac')) {
                $acValue = $request->has_ac;
                if (is_bool($acValue)) {
                    $data['has_ac'] = $acValue ? 'yes' : 'no';
                } elseif (is_string($acValue)) {
                    $data['has_ac'] = in_array(strtolower($acValue), ['yes', 'true', '1', 'on']) ? 'yes' : 'no';
                } else {
                    $data['has_ac'] = $acValue ? 'yes' : 'no';
                }
            }

            $room->update($data);

            // Update floor statistics if floor changed
            if ($request->has('floor_id') && $request->floor_id != $room->floor_id) {
                $this->updateFloorStatistics($room->floor_id);
                $this->updateFloorStatistics($request->floor_id);
            }

            return response()->json([
                'success' => true,
                'data' => $room->load(['building', 'block', 'floor']),
                'message' => 'Room updated successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update room.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified room (soft delete).
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $room = AddRooms::findOrFail($id);
            $floorId = $room->floor_id;
            $room->delete();

            // Update floor statistics
            if ($floorId) {
                $this->updateFloorStatistics($floorId);
            }

            return response()->json([
                'success' => true,
                'message' => 'Room deleted successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete room.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore a soft-deleted room.
     */
    public function restore(string $id): JsonResponse
    {
        try {
            $room = AddRooms::withTrashed()->findOrFail($id);
            
            if (!$room->trashed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Room is not deleted.'
                ], 400);
            }

            $room->restore();

            // Update floor statistics
            if ($room->floor_id) {
                $this->updateFloorStatistics($room->floor_id);
            }

            return response()->json([
                'success' => true,
                'data' => $room,
                'message' => 'Room restored successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore room.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Permanently delete a room.
     */
    public function forceDelete(string $id): JsonResponse
    {
        try {
            $room = AddRooms::withTrashed()->findOrFail($id);
            $floorId = $room->floor_id;
            $room->forceDelete();

            // Update floor statistics
            if ($floorId) {
                $this->updateFloorStatistics($floorId);
            }

            return response()->json([
                'success' => true,
                'message' => 'Room permanently deleted.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to permanently delete room.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get room statistics (detailed).
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = AddRooms::query();
        $this->applyFilters($query, $request);

        $totalRooms = $query->count();
        $activeRooms = $query->clone()->where('status', 'active')->count();
        $inactiveRooms = $query->clone()->where('status', 'inactive')->count();
        $underMaintenanceRooms = $query->clone()->where('status', 'under_maintenance')->count();
        $renovationRooms = $query->clone()->where('status', 'renovation')->count();
        
        $vacantRooms = $query->clone()->where('occupancy_status', 'vacant')->count();
        $occupiedRooms = $query->clone()->where('occupancy_status', 'occupied')->count();
        $partiallyOccupiedRooms = $query->clone()->where('occupancy_status', 'partially_occupied')->count();
        
        // Room type statistics
        $classrooms = $query->clone()->where('room_type', 'classroom')->count();
        $labs = $query->clone()->where('room_type', 'lab')->count();
        $offices = $query->clone()->where('room_type', 'office')->count();
        $conferenceRooms = $query->clone()->where('room_type', 'conference')->count();
        $libraries = $query->clone()->where('room_type', 'library')->count();
        $otherRooms = $query->clone()->where('room_type', 'other')->count();
        
        // Amenities statistics
        $roomsWithAC = $query->clone()->where('has_ac', 'yes')->count();
        $roomsWithWifi = $query->clone()->where('has_wifi', true)->count();
        $roomsWithProjector = $query->clone()->where('has_projector', true)->count();
        $roomsWithWhiteboard = $query->clone()->where('has_whiteboard', true)->count();
        $roomsWithSmartBoard = $query->clone()->where('has_smart_board', true)->count();
        $roomsWithWashroom = $query->clone()->where('has_washroom', true)->count();
        $roomsWithCCTV = $query->clone()->where('has_cctv', true)->count();
        $roomsWithFireExtinguisher = $query->clone()->where('has_fire_extinguisher', true)->count();
        
        // Capacity and area statistics
        $totalCapacity = $query->clone()->sum('capacity');
        $totalArea = $query->clone()->sum('area');
        $avgCapacity = $totalRooms > 0 ? round($totalCapacity / $totalRooms, 2) : 0;
        $avgArea = $totalRooms > 0 ? round($totalArea / $totalRooms, 2) : 0;
        
        // Electrical items totals
        $totalLights = $query->clone()->sum('lights_count');
        $totalFans = $query->clone()->sum('fans_count');
        $totalACUnits = $query->clone()->sum('ac_count');
        $totalSockets = $query->clone()->sum('sockets_count');

        return response()->json([
            'success' => true,
            'data' => [
                'total_rooms' => $totalRooms,
                
                'status_distribution' => [
                    'active' => $activeRooms,
                    'inactive' => $inactiveRooms,
                    'under_maintenance' => $underMaintenanceRooms,
                    'renovation' => $renovationRooms,
                ],
                
                'occupancy_distribution' => [
                    'vacant' => $vacantRooms,
                    'occupied' => $occupiedRooms,
                    'partially_occupied' => $partiallyOccupiedRooms,
                    'occupancy_rate' => $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0,
                ],
                
                'room_type_distribution' => [
                    'classrooms' => $classrooms,
                    'labs' => $labs,
                    'offices' => $offices,
                    'conference_rooms' => $conferenceRooms,
                    'libraries' => $libraries,
                    'other_rooms' => $otherRooms,
                ],
                
                'amenities_coverage' => [
                    'ac_coverage' => $totalRooms > 0 ? round(($roomsWithAC / $totalRooms) * 100, 2) : 0,
                    'wifi_coverage' => $totalRooms > 0 ? round(($roomsWithWifi / $totalRooms) * 100, 2) : 0,
                    'projector_coverage' => $totalRooms > 0 ? round(($roomsWithProjector / $totalRooms) * 100, 2) : 0,
                    'whiteboard_coverage' => $totalRooms > 0 ? round(($roomsWithWhiteboard / $totalRooms) * 100, 2) : 0,
                    'smart_board_coverage' => $totalRooms > 0 ? round(($roomsWithSmartBoard / $totalRooms) * 100, 2) : 0,
                    'washroom_coverage' => $totalRooms > 0 ? round(($roomsWithWashroom / $totalRooms) * 100, 2) : 0,
                    'cctv_coverage' => $totalRooms > 0 ? round(($roomsWithCCTV / $totalRooms) * 100, 2) : 0,
                    'fire_safety_coverage' => $totalRooms > 0 ? round(($roomsWithFireExtinguisher / $totalRooms) * 100, 2) : 0,
                ],
                
                'capacity_statistics' => [
                    'total_capacity' => $totalCapacity,
                    'average_capacity' => $avgCapacity,
                ],
                
                'area_statistics' => [
                    'total_area' => $totalArea,
                    'average_area' => $avgArea,
                ],
                
                'electrical_items' => [
                    'total_lights' => $totalLights,
                    'total_fans' => $totalFans,
                    'total_ac_units' => $totalACUnits,
                    'total_sockets' => $totalSockets,
                    'avg_lights_per_room' => $totalRooms > 0 ? round($totalLights / $totalRooms, 2) : 0,
                    'avg_sockets_per_room' => $totalRooms > 0 ? round($totalSockets / $totalRooms, 2) : 0,
                ],
            ],
            'message' => 'Room statistics retrieved successfully.'
        ]);
    }

    /**
     * Get rooms by building ID.
     */
    public function getByBuilding(string $buildingId): JsonResponse
    {
        try {
            $rooms = AddRooms::where('building_id', $buildingId)
                ->with(['block', 'floor'])
                ->orderBy('room_number')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $rooms,
                'message' => 'Rooms retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve rooms.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get rooms by block ID.
     */
    public function getByBlock(string $blockId): JsonResponse
    {
        try {
            $rooms = AddRooms::where('block_id', $blockId)
                ->with(['building', 'floor'])
                ->orderBy('room_number')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $rooms,
                'message' => 'Rooms retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve rooms.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get rooms by floor ID.
     */
    public function getByFloor(string $floorId): JsonResponse
    {
        try {
            // Find the floor with room details
            $floor = AddFloor::where('id', $floorId)
                ->with(['building', 'block'])
                ->first();

            if (!$floor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Floor not found.'
                ], 404);
            }

            // Get actual rooms from rooms table
            $rooms = AddRooms::where('floor_id', $floorId)
                ->with(['building', 'block', 'floor'])
                ->orderBy('room_number')
                ->get();

            // Prepare room data from floor table
            $roomData = [
                'floor_id' => $floor->id,
                'floor_number' => $floor->floor_number,
                'floor_name' => $floor->floor_name,
                'building' => $floor->building ? [
                    'id' => $floor->building->id,
                    'name' => $floor->building->name ?? 'N/A',
                ] : null,
                'block' => $floor->block ? [
                    'id' => $floor->block->id,
                    'name' => $floor->block->name ?? 'N/A',
                ] : null,
                
                // Room statistics from floor table
                'room_stats' => [
                    'total_rooms' => (int)($floor->total_rooms ?? 0),
                    'available_rooms' => (int)($floor->available_rooms ?? 0),
                    'occupied_rooms' => (int)($floor->occupied_rooms ?? 0),
                    'total_capacity' => (int)($floor->total_capacity ?? 0),
                    'occupancy_rate' => $floor->total_rooms > 0 
                        ? round(($floor->occupied_rooms / $floor->total_rooms) * 100, 2) 
                        : 0,
                    'availability_rate' => $floor->total_rooms > 0 
                        ? round(($floor->available_rooms / $floor->total_rooms) * 100, 2) 
                        : 0,
                ],
                
                // Area details
                'area' => [
                    'total_area' => $floor->total_area,
                    'area_unit' => $floor->area_unit ?? 'sq_ft',
                ],
                
                // Amenities
                'amenities' => [
                    'has_ac' => (bool)$floor->has_ac,
                    'has_lift' => (bool)$floor->has_lift,
                    'has_washroom' => (bool)$floor->has_washroom,
                    'has_wifi' => (bool)$floor->has_wifi,
                    'has_library' => (bool)$floor->has_library,
                    'has_staff_room' => (bool)$floor->has_staff_room,
                    'has_conference_room' => (bool)$floor->has_conference_room,
                    'has_fire_alarm' => (bool)$floor->has_fire_alarm,
                    'has_fire_extinguisher' => (bool)$floor->has_fire_extinguisher,
                    'has_disabled_access' => (bool)$floor->has_disabled_access,
                ],
                
                'allocated_amenities' => $floor->allocated_amenities ?? [],
                'allocated_facilities' => $floor->allocated_facility_entries ?? [],
                'gates' => $floor->gates ?? [],
                'additional_areas' => $floor->additional_areas ?? [],
                
                'status' => $floor->status,
                'floor_level' => $floor->floor_level,
                'created_at' => $floor->created_at,
                'updated_at' => $floor->updated_at,
                
                // Actual rooms list
                'rooms' => $rooms,
            ];
           
            return response()->json([
                'success' => true,
                'data' => $roomData,
                'message' => 'Room data retrieved successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching rooms by floor:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve room data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update room status.
     */
    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,inactive,under_maintenance,renovation'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }

        try {
            $room = AddRooms::findOrFail($id);
            $room->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'data' => $room,
                'message' => 'Room status updated successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update room status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update occupancy status.
     */
    public function updateOccupancyStatus(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'occupancy_status' => 'required|in:vacant,occupied,partially_occupied'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }

        try {
            $room = AddRooms::findOrFail($id);
            $room->update(['occupancy_status' => $request->occupancy_status]);

            // Update floor statistics
            if ($room->floor_id) {
                $this->updateFloorStatistics($room->floor_id);
            }

            return response()->json([
                'success' => true,
                'data' => $room,
                'message' => 'Room occupancy status updated successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update room occupancy status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get rooms requiring maintenance.
     */
    public function getMaintenanceDue(Request $request): JsonResponse
    {
        $days = $request->days ?? 30;
        $date = now()->addDays($days);

        $rooms = AddRooms::where('status', '!=', 'under_maintenance')
            ->whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '<=', $date)
            ->with(['building', 'block', 'floor'])
            ->orderBy('next_maintenance_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rooms,
            'message' => 'Rooms due for maintenance retrieved successfully.'
        ]);
    }

    /**
     * Search rooms by various criteria (legacy method).
     */
    public function search(Request $request): JsonResponse
    {
        $query = AddRooms::query();

        $query->when($request->filled('room_number'), function ($q) use ($request) {
            return $q->where('room_number', 'like', '%' . $request->room_number . '%');
        })
        ->when($request->filled('room_name'), function ($q) use ($request) {
            return $q->where('room_name', 'like', '%' . $request->room_name . '%');
        })
        ->when($request->filled('department'), function ($q) use ($request) {
            return $q->where('department', 'like', '%' . $request->department . '%');
        })
        ->when($request->filled('in_charge_name'), function ($q) use ($request) {
            return $q->where('in_charge_name', 'like', '%' . $request->in_charge_name . '%');
        });

        $rooms = $query->with(['building', 'block', 'floor'])
            ->orderBy('room_number')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $rooms,
            'message' => 'Rooms search completed successfully.'
        ]);
    }

    /**
     * Apply filters to query.
     */
    private function applyFilters($query, Request $request): void
    {
        $query->when($request->filled('building_id'), function ($q) use ($request) {
            return $q->where('building_id', $request->building_id);
        })
        ->when($request->filled('block_id'), function ($q) use ($request) {
            return $q->where('block_id', $request->block_id);
        })
        ->when($request->filled('floor_id'), function ($q) use ($request) {
            return $q->where('floor_id', $request->floor_id);
        })
        ->when($request->filled('institute_id'), function ($q) use ($request) {
            return $q->where('institute_id', $request->institute_id);
        })
        ->when($request->filled('branch_id'), function ($q) use ($request) {
            return $q->where('branch_id', $request->branch_id);
        })
        ->when($request->filled('room_type'), function ($q) use ($request) {
            return $q->where('room_type', $request->room_type);
        })
        ->when($request->filled('status'), function ($q) use ($request) {
            return $q->where('status', $request->status);
        })
        ->when($request->filled('occupancy_status'), function ($q) use ($request) {
            return $q->where('occupancy_status', $request->occupancy_status);
        })
        ->when($request->filled('has_ac'), function ($q) use ($request) {
            return $q->where('has_ac', $request->has_ac);
        })
        ->when($request->filled('has_wifi'), function ($q) use ($request) {
            return $q->where('has_wifi', $request->boolean('has_wifi'));
        })
        ->when($request->filled('has_projector'), function ($q) use ($request) {
            return $q->where('has_projector', $request->boolean('has_projector'));
        })
        ->when($request->filled('has_washroom'), function ($q) use ($request) {
            return $q->where('has_washroom', $request->boolean('has_washroom'));
        })
        ->when($request->filled('min_capacity'), function ($q) use ($request) {
            return $q->where('capacity', '>=', $request->min_capacity);
        })
        ->when($request->filled('max_capacity'), function ($q) use ($request) {
            return $q->where('capacity', '<=', $request->max_capacity);
        })
        ->when($request->filled('department'), function ($q) use ($request) {
            return $q->where('department', $request->department);
        });
    }

    /**
     * Get validation rules.
     */
    private function getValidationRules(bool $forUpdate = false): array
    {
        $rules = [
            'building_id' => 'nullable|string|max:100',
            'block_id' => 'nullable|string|max:100',
            'floor_id' => 'nullable|string|max:100',
            'room_number' => 'required|string|max:50',
            'room_name' => 'nullable|string|max:255',
            'room_type' => 'required|in:classroom,lab,office,conference,library,store_room,other',
            'custom_room_type' => 'nullable|string|max:100',
            'institute_id' => 'nullable|string|max:100',
            'branch_id' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            
            // Room Specifications
            'area' => 'nullable|numeric|min:0',
            'area_type' => 'nullable|in:super,carpet',
            'capacity' => 'nullable|integer|min:0',
            'min_capacity' => 'nullable|integer|min:0',
            'occupancy_type' => 'nullable|in:single,shared,multiple,flexible',
            'category' => 'nullable|in:standard,premium,deluxe,executive,economy',
            'usage_type' => 'nullable|in:permanent,temporary,flexible,event_based',
            'available_from' => 'nullable|string|max:10',
            'available_to' => 'nullable|string|max:10',
            'booking_required' => 'nullable|in:yes,no',
            
            // Dimensions
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'doors' => 'nullable|integer|min:0',
            'windows' => 'nullable|integer|min:0',
            'door_type' => 'nullable|in:single,double,sliding,french',
            'window_type' => 'nullable|in:casement,sliding,fixed,bay',
            
            // Electrical Items
            'lights_count' => 'nullable|integer|min:0',
            'fans_count' => 'nullable|integer|min:0',
            'ac_count' => 'nullable|integer|min:0',
            'sockets_count' => 'nullable|integer|min:0',
            'lighting_type' => 'nullable|in:led,fluorescent,incandescent,natural,mixed',
            'ac_type' => 'nullable|in:central,split,window,none',
            
            // Connectivity
            'has_wifi' => 'nullable|boolean',
            'has_lan' => 'nullable|boolean',
            'has_telephone' => 'nullable|boolean',
            'has_cctv' => 'nullable|boolean',
            'internet_speed' => 'nullable|integer|min:0',
            'network_type' => 'nullable|in:wired,wireless,both',
            
            // Fire Safety
            'has_fire_extinguisher' => 'nullable|boolean',
            'has_fire_alarm' => 'nullable|boolean',
            'has_smoke_detector' => 'nullable|boolean',
            'has_sprinkler' => 'nullable|boolean',
            'has_emergency_exit' => 'nullable|boolean',
            'has_emergency_light' => 'nullable|boolean',
            
            // Security
            'has_door_lock' => 'nullable|boolean',
            'has_smart_lock' => 'nullable|boolean',
            'has_intercom' => 'nullable|boolean',
            'security_level' => 'nullable|in:low,medium,high',
            
            // Washroom Details
            'has_washroom' => 'nullable|boolean',
            'washroom_capacity' => 'nullable|integer|min:0',
            'washroom_type' => 'nullable|in:attached,shared,common',
            'has_hot_water' => 'nullable|boolean',
            'has_shower' => 'nullable|boolean',
            'has_bathtub' => 'nullable|boolean',
            
            // Accessibility
            'has_wheelchair_access' => 'nullable|boolean',
            'has_grab_bars' => 'nullable|boolean',
            'has_visual_alerts' => 'nullable|boolean',
            'accessibility_level' => 'nullable|in:none,basic,full',
            
            // Other Amenities
            'has_projector' => 'nullable|boolean',
            'has_whiteboard' => 'nullable|boolean',
            'has_smart_board' => 'nullable|boolean',
            'has_desk' => 'nullable|boolean',
            'has_chair' => 'nullable|boolean',
            'has_cabinets' => 'nullable|boolean',
            'has_bed' => 'nullable|boolean',
            'has_tv' => 'nullable|boolean',
            'has_kitchenette' => 'nullable|boolean',
            'has_fridge' => 'nullable|boolean',
            'has_microwave' => 'nullable|boolean',
            'has_water_cooler' => 'nullable|boolean',
            'has_ac' => 'nullable|string|in:yes,no',
            
            // Room Status
            'status' => 'nullable|in:active,inactive,under_maintenance,renovation',
            'occupancy_status' => 'nullable|in:vacant,occupied,partially_occupied,maintenance,reserved',
            'floor_level' => 'nullable|string|max:50',
            
            // Usage Information
            'department' => 'nullable|string|max:255',
            'in_charge_name' => 'nullable|string|max:255',
            'in_charge_contact' => 'nullable|string|max:50',
            'special_notes' => 'nullable|string',
            
            // Maintenance Information
            'last_maintenance_date' => 'nullable|date',
            'next_maintenance_date' => 'nullable|date|after_or_equal:last_maintenance_date',
            'maintenance_notes' => 'nullable|string',
            
            // Floor Amenities Selection
            'selected_floor_amenities' => 'nullable|array',
            'selected_floor_facilities' => 'nullable|array',
            
            // Audit Information
            'created_by' => 'nullable|string|max:100',
            'updated_by' => 'nullable|string|max:100',
        ];

        return $rules;
    }

    // =========================== FLOORS METHODS ===========================

    /**
     * Get blocks by building ID for dropdown.
     */
    public function getBlocksByBuilding(string $buildingId): JsonResponse
    {
        try {
            $blocks = AddBlock::where('building_id', $buildingId)
                ->where('status', 'active')
                ->orderBy('name')
                ->get();

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
     * Get floors by building ID.
     */
    public function getFloorsByBuilding(string $buildingId): JsonResponse
    {
        try {
            $floors = AddFloor::where('building_id', $buildingId)
                ->with(['building', 'block'])
                ->orderBy('block_id')
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
     * Get floor statistics.
     */
    public function getFloorStatistics(): JsonResponse
    {
        try {
            $totalFloors = AddFloor::count();
            $activeFloors = AddFloor::where('status', 'active')->count();
            $inactiveFloors = AddFloor::where('status', 'inactive')->count();
            $maintenanceFloors = AddFloor::where('status', 'under_maintenance')->count();
            
            $floorsWithAC = AddFloor::where('has_ac', 1)->count();
            $floorsWithWifi = AddFloor::where('has_wifi', 1)->count();
            $floorsWithLift = AddFloor::where('has_lift', 1)->count();
            $floorsWithWashroom = AddFloor::where('has_washroom', 1)->count();
            
            // Calculate percentages
            $acCoverage = $totalFloors > 0 ? round(($floorsWithAC / $totalFloors) * 100, 2) : 0;
            $wifiCoverage = $totalFloors > 0 ? round(($floorsWithWifi / $totalFloors) * 100, 2) : 0;
            $liftCoverage = $totalFloors > 0 ? round(($floorsWithLift / $totalFloors) * 100, 2) : 0;
            $washroomCoverage = $totalFloors > 0 ? round(($floorsWithWashroom / $totalFloors) * 100, 2) : 0;
            
            // Get total rooms from all floors
            $totalRooms = AddFloor::sum('total_rooms') ?? 0;
            $occupiedRooms = AddFloor::sum('occupied_rooms') ?? 0;
            $availableRooms = $totalRooms - $occupiedRooms;

            return response()->json([
                'success' => true,
                'data' => [
                    'total_floors' => $totalFloors,
                    'active_floors' => $activeFloors,
                    'inactive_floors' => $inactiveFloors,
                    'maintenance_floors' => $maintenanceFloors,
                    
                    'facility_coverage' => [
                        'ac_coverage' => $acCoverage,
                        'wifi_coverage' => $wifiCoverage,
                        'lift_coverage' => $liftCoverage,
                        'washroom_coverage' => $washroomCoverage,
                    ],
                    
                    'room_statistics' => [
                        'total_rooms' => $totalRooms,
                        'occupied_rooms' => $occupiedRooms,
                        'available_rooms' => $availableRooms,
                    ]
                ],
                'message' => 'Floor statistics retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve floor statistics.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search floors by various criteria.
     */
    public function searchFloors(Request $request): JsonResponse
    {
        try {
            $query = AddFloor::query();
            
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('floor_number', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%')
                      ->orWhereHas('building', function($q) use ($searchTerm) {
                          $q->where('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('code', 'like', '%' . $searchTerm . '%');
                      })
                      ->orWhereHas('block', function($q) use ($searchTerm) {
                          $q->where('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('code', 'like', '%' . $searchTerm . '%');
                      });
                });
            }
            
            $floors = $query->with(['building', 'block'])
                ->orderBy('building_id')
                ->orderBy('block_id')
                ->orderBy('floor_number')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $floors,
                'message' => 'Floors search completed successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to search floors.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of floors.
     */
    public function indexFloors(Request $request): JsonResponse
    {
        $query = AddFloor::with(['building', 'block']);

        // Apply filters
        $this->applyFloorFilters($query, $request);

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
    public function storeFloor(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'building_id' => 'required|exists:add_buildings,id',
            'block_id' => 'required|exists:add_blocks,id',
            'floor_number' => 'required|string|max:50',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,under_maintenance',
            
            // Air Conditioning
            'has_ac' => 'nullable|boolean',
            'is_central_ac' => 'nullable|boolean',
            'ac_units' => 'nullable|integer|min:0',
            
            // Water Facility
            'has_water_facility' => 'nullable|boolean',
            'has_water_cooler' => 'nullable|boolean',
            'has_drinking_water' => 'nullable|boolean',
            'water_cooler_count' => 'nullable|integer|min:0',
            
            // Fire Safety
            'has_fire_extinguisher' => 'nullable|boolean',
            'has_fire_alarm' => 'nullable|boolean',
            'has_emergency_exit' => 'nullable|boolean',
            'fire_extinguisher_count' => 'nullable|integer|min:0',
            
            // Lifts Configuration
            'has_lift' => 'nullable|boolean',
            'lift_type' => 'nullable|in:passenger,service',
            'lift_capacity' => 'nullable|integer|min:1',
            'lift_weight_limit' => 'nullable|integer|min:100',
            'lift_count' => 'nullable|integer|min:0',
            
            // Washrooms
            'has_washroom' => 'nullable|boolean',
            'washroom_type' => 'nullable|in:common,private',
            'washroom_gender' => 'nullable|in:male,female,unisex',
            'washroom_capacity' => 'nullable|integer|min:1',
            'washroom_count' => 'nullable|integer|min:0',
            
            // Other Facilities
            'has_wifi' => 'nullable|boolean',
            'has_projector_room' => 'nullable|boolean',
            'has_conference_room' => 'nullable|boolean',
            'has_library' => 'nullable|boolean',
            'has_staff_room' => 'nullable|boolean',
            'has_common_room' => 'nullable|boolean',
            'has_disabled_access' => 'nullable|boolean',
            
            // Room Statistics
            'total_rooms' => 'nullable|integer|min:0',
            'occupied_rooms' => 'nullable|integer|min:0',
            'total_capacity' => 'nullable|integer|min:0',
            
            // Area Information
            'total_area' => 'nullable|numeric|min:0',
            'floor_level' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }

        try {
            // Check if floor number already exists in the same block
            $existingFloor = AddFloor::where('block_id', $request->block_id)
                ->where('floor_number', $request->floor_number)
                ->first();

            if ($existingFloor) {
                return response()->json([
                    'success' => false,
                    'message' => 'A floor with this number already exists in the selected block.'
                ], 422);
            }

            // Check if block belongs to building
            $block = AddBlock::find($request->block_id);
            if ($block->building_id != $request->building_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected block does not belong to the specified building.'
                ], 422);
            }

            $data = $request->all();
            
            // Set created_by if user is authenticated
            if (auth()->check()) {
                $data['created_by'] = auth()->id();
            }

            $floor = AddFloor::create($data);

            return response()->json([
                'success' => true,
                'data' => $floor->load(['building', 'block']),
                'message' => 'Floor created successfully.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create floor.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified floor.
     */
    public function showFloor(string $id): JsonResponse
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
    public function updateFloor(Request $request, string $id): JsonResponse
    {
        try {
            $floor = AddFloor::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'building_id' => 'required|exists:add_buildings,id',
                'block_id' => 'required|exists:add_blocks,id',
                'floor_number' => 'required|string|max:50',
                'description' => 'nullable|string',
                'status' => 'required|in:active,inactive,under_maintenance',
                
                // Air Conditioning
                'has_ac' => 'nullable|boolean',
                'is_central_ac' => 'nullable|boolean',
                'ac_units' => 'nullable|integer|min:0',
                
                // Water Facility
                'has_water_facility' => 'nullable|boolean',
                'has_water_cooler' => 'nullable|boolean',
                'has_drinking_water' => 'nullable|boolean',
                'water_cooler_count' => 'nullable|integer|min:0',
                
                // Fire Safety
                'has_fire_extinguisher' => 'nullable|boolean',
                'has_fire_alarm' => 'nullable|boolean',
                'has_emergency_exit' => 'nullable|boolean',
                'fire_extinguisher_count' => 'nullable|integer|min:0',
                
                // Lifts Configuration
                'has_lift' => 'nullable|boolean',
                'lift_type' => 'nullable|in:passenger,service',
                'lift_capacity' => 'nullable|integer|min:1',
                'lift_weight_limit' => 'nullable|integer|min:100',
                'lift_count' => 'nullable|integer|min:0',
                
                // Washrooms
                'has_washroom' => 'nullable|boolean',
                'washroom_type' => 'nullable|in:common,private',
                'washroom_gender' => 'nullable|in:male,female,unisex',
                'washroom_capacity' => 'nullable|integer|min:1',
                'washroom_count' => 'nullable|integer|min:0',
                
                // Other Facilities
                'has_wifi' => 'nullable|boolean',
                'has_projector_room' => 'nullable|boolean',
                'has_conference_room' => 'nullable|boolean',
                'has_library' => 'nullable|boolean',
                'has_staff_room' => 'nullable|boolean',
                'has_common_room' => 'nullable|boolean',
                'has_disabled_access' => 'nullable|boolean',
                
                // Room Statistics
                'total_rooms' => 'nullable|integer|min:0',
                'occupied_rooms' => 'nullable|integer|min:0',
                'total_capacity' => 'nullable|integer|min:0',
                
                // Area Information
                'total_area' => 'nullable|numeric|min:0',
                'floor_level' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed.'
                ], 422);
            }

            // Check if floor number already exists in the same block (excluding current floor)
            if ($request->floor_number != $floor->floor_number || $request->block_id != $floor->block_id) {
                $existingFloor = AddFloor::where('block_id', $request->block_id)
                    ->where('floor_number', $request->floor_number)
                    ->where('id', '!=', $id)
                    ->first();

                if ($existingFloor) {
                    return response()->json([
                        'success' => false,
                        'message' => 'A floor with this number already exists in the selected block.'
                    ], 422);
                }
            }

            // Check if block belongs to building
            $block = AddBlock::find($request->block_id);
            if ($block->building_id != $request->building_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected block does not belong to the specified building.'
                ], 422);
            }

            $data = $request->all();
            
            // Set updated_by if user is authenticated
            if (auth()->check()) {
                $data['updated_by'] = auth()->id();
            }

            $floor->update($data);

            return response()->json([
                'success' => true,
                'data' => $floor->load(['building', 'block']),
                'message' => 'Floor updated successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Floor not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update floor.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified floor.
     */
    public function destroyFloor(string $id): JsonResponse
    {
        try {
            $floor = AddFloor::findOrFail($id);
            
            // Check if floor has rooms
            $roomCount = AddRooms::where('floor_id', $id)->count();
            if ($roomCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete floor. It has ' . $roomCount . ' room(s) assigned. Please delete or reassign the rooms first.'
                ], 400);
            }

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
     * Apply filters to floor query.
     */
    private function applyFloorFilters($query, Request $request): void
    {
        $query->when($request->filled('building_id'), function ($q) use ($request) {
            return $q->where('building_id', $request->building_id);
        })
        ->when($request->filled('block_id'), function ($q) use ($request) {
            return $q->where('block_id', $request->block_id);
        })
        ->when($request->filled('status'), function ($q) use ($request) {
            return $q->where('status', $request->status);
        })
        ->when($request->filled('has_ac'), function ($q) use ($request) {
            return $q->where('has_ac', $request->has_ac);
        })
        ->when($request->filled('has_wifi'), function ($q) use ($request) {
            return $q->where('has_wifi', $request->boolean('has_wifi'));
        })
        ->when($request->filled('has_lift'), function ($q) use ($request) {
            return $q->where('has_lift', $request->boolean('has_lift'));
        })
        ->when($request->filled('has_washroom'), function ($q) use ($request) {
            return $q->where('has_washroom', $request->boolean('has_washroom'));
        });
    }
    /**
     * Display the room details page.
     */
    public function showDetails(string $id)
    {
        try {
            $room = AddRooms::with(['building', 'block', 'floor'])->findOrFail($id);

            // Helper function to safely get data (handles both string JSON and array)
            $getData = function($field) use ($room) {
                $value = $room->$field;
                
                // If it's already an array or object, return as is
                if (is_array($value) || is_object($value)) {
                    return (array) $value;
                }
                
                // If it's a string, try to decode JSON
                if (is_string($value)) {
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return $decoded ?? [];
                    }
                    return [];
                }
                
                return [];
            };

            // Get all data using the helper function
            $roomSpecifications = $getData('room_specifications');
            $selectedFloorAmenities = $getData('selected_floor_amenities');
            $selectedFloorFacilities = $getData('selected_floor_facilities');

            // Count amenities
            $amenityCount = 0;
            $amenityList = [
                'has_ac', 'has_wifi', 'has_projector', 'has_whiteboard', 
                'has_smart_board', 'has_washroom', 'has_cctv', 'has_fire_extinguisher'
            ];
            foreach ($amenityList as $key) {
                if ($room->$key) $amenityCount++;
            }

            // Also count amenities from specifications if they exist
            if (isset($roomSpecifications['has_wifi']) && $roomSpecifications['has_wifi']) $amenityCount++;
            if (isset($roomSpecifications['has_projector']) && $roomSpecifications['has_projector']) $amenityCount++;
            if (isset($roomSpecifications['has_whiteboard']) && $roomSpecifications['has_whiteboard']) $amenityCount++;
            if (isset($roomSpecifications['has_smart_board']) && $roomSpecifications['has_smart_board']) $amenityCount++;
            if (isset($roomSpecifications['has_cctv']) && $roomSpecifications['has_cctv']) $amenityCount++;
            if (isset($roomSpecifications['has_fire_extinguisher']) && $roomSpecifications['has_fire_extinguisher']) $amenityCount++;
            if (isset($roomSpecifications['has_washroom']) && $roomSpecifications['has_washroom']) $amenityCount++;

            return view('instituteAdmin.CreateBuildings.roomsView', compact(
                'room',
                'roomSpecifications',
                'selectedFloorAmenities',
                'selectedFloorFacilities',
                'amenityCount'
            ));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Room not found');
        } catch (\Exception $e) {
            Log::error('Error loading room details: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            abort(500, 'Error loading room details');
        }
    }
    public function getRoomTypes(): JsonResponse
    {
        // try {
            $types = RoomType::where('status', true)
                ->orderBy('name')
                ->get(['id', 'name']);
            return response()->json([
                'success' => true,
                'data' => $types,
                'message' => 'Room types retrieved successfully.'
            ]);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => $e->getMessage()
        //     ], 500);
        // }
    }
}