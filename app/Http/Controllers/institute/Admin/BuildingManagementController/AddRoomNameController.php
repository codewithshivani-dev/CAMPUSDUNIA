<?php

namespace App\Http\Controllers;

use App\Models\AddRoomName;
use App\Models\AddRooms;
use App\Models\BuildingsPage;
use App\Models\AddBlock;
use App\Models\AddFloor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AddRoomNameController extends Controller
{
    /**
     * Display the room names management page.
     */
    public function page()
    {
        $buildings = BuildingsPage::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'code']);
            
        return view('room_names.page', compact('buildings'));
    }

    /**
     * Display a listing of room names.
     */
    public function index(Request $request): JsonResponse
    {
        $query = AddRoomName::query()->with(['room']);

        // Apply filters
        $query->when($request->filled('room_id'), function ($q) use ($request) {
            return $q->where('room_id', $request->room_id);
        })
        ->when($request->filled('institute_id'), function ($q) use ($request) {
            return $q->where('institute_id', $request->institute_id);
        })
        ->when($request->filled('branch_id'), function ($q) use ($request) {
            return $q->where('branch_id', $request->branch_id);
        })
        ->when($request->filled('department'), function ($q) use ($request) {
            return $q->where('department', 'like', '%' . $request->department . '%');
        })
        ->when($request->filled('purpose'), function ($q) use ($request) {
            return $q->where('purpose', 'like', '%' . $request->purpose . '%');
        })
        ->when($request->filled('in_charge_name'), function ($q) use ($request) {
            return $q->where('in_charge_name', 'like', '%' . $request->in_charge_name . '%');
        })
        ->when($request->filled('official_name'), function ($q) use ($request) {
            return $q->where('official_name', 'like', '%' . $request->official_name . '%');
        });

        // Sorting
        $sortBy = $request->sort_by ?? 'official_name';
        $sortOrder = $request->sort_order ?? 'asc';
        $query->orderBy($sortBy, $sortOrder);

        $roomNames = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $roomNames,
            'message' => 'Room names retrieved successfully.'
        ]);
    }

    /**
     * Store a newly created room name.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:add_rooms,id',
            'official_name' => 'required|string|max:255',
            'alternative_name' => 'nullable|string|max:255',
            'purpose' => 'nullable|string|max:500',
            'department' => 'nullable|string|max:255',
            'institute_id' => 'nullable|string|max:100',
            'branch_id' => 'nullable|string|max:100',
            'in_charge_name' => 'nullable|string|max:255',
            'in_charge_contact' => 'nullable|string|max:50',
            'special_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }

        // Check if room already has a name record (one-to-one relationship)
        $existingRoomName = AddRoomName::where('room_id', $request->room_id)->first();
        if ($existingRoomName) {
            return response()->json([
                'success' => false,
                'message' => 'Room already has a name record. Use update instead.'
            ], 409);
        }

        // Check for duplicate official name (optional, but recommended)
        $duplicateOfficialName = AddRoomName::where('official_name', $request->official_name)->first();
        if ($duplicateOfficialName) {
            return response()->json([
                'success' => false,
                'message' => 'A room with this official name already exists.'
            ], 409);
        }

        try {
            $roomName = AddRoomName::create($request->all());

            return response()->json([
                'success' => true,
                'data' => $roomName->load('room'),
                'message' => 'Room name created successfully.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create room name.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified room name.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $roomName = AddRoomName::with(['room'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $roomName,
                'message' => 'Room name retrieved successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room name not found.'
            ], 404);
        }
    }

    /**
     * Update the specified room name.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $roomName = AddRoomName::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'room_id' => 'sometimes|required|exists:add_rooms,id',
                'official_name' => 'sometimes|required|string|max:255',
                'alternative_name' => 'nullable|string|max:255',
                'purpose' => 'nullable|string|max:500',
                'department' => 'nullable|string|max:255',
                'institute_id' => 'nullable|string|max:100',
                'branch_id' => 'nullable|string|max:100',
                'in_charge_name' => 'nullable|string|max:255',
                'in_charge_contact' => 'nullable|string|max:50',
                'special_notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed.'
                ], 422);
            }

            // Check if room_id is being changed to one that already has a name record
            if ($request->has('room_id') && $request->room_id != $roomName->room_id) {
                $existingRoomName = AddRoomName::where('room_id', $request->room_id)
                    ->where('id', '!=', $id)
                    ->first();
                
                if ($existingRoomName) {
                    return response()->json([
                        'success' => false,
                        'message' => 'The selected room already has a name record.'
                    ], 422);
                }
            }

            // Check for duplicate official name (excluding current record)
            if ($request->has('official_name') && $request->official_name != $roomName->official_name) {
                $duplicateOfficialName = AddRoomName::where('official_name', $request->official_name)
                    ->where('id', '!=', $id)
                    ->first();
                
                if ($duplicateOfficialName) {
                    return response()->json([
                        'success' => false,
                        'message' => 'A room with this official name already exists.'
                    ], 409);
                }
            }

            $roomName->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $roomName->load('room'),
                'message' => 'Room name updated successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room name not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update room name.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified room name.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $roomName = AddRoomName::findOrFail($id);
            $roomName->delete();

            return response()->json([
                'success' => true,
                'message' => 'Room name deleted successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room name not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete room name.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get room name by room ID.
     */
    public function getByRoom(string $roomId): JsonResponse
    {
        try {
            $roomName = AddRoomName::where('room_id', $roomId)
                ->with(['room'])
                ->first();

            if (!$roomName) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'No name record found for this room.'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $roomName,
                'message' => 'Room name retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve room name.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create or update room name for a room.
     */
    public function upsert(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:add_rooms,id',
            'official_name' => 'required|string|max:255',
            'alternative_name' => 'nullable|string|max:255',
            'purpose' => 'nullable|string|max:500',
            'department' => 'nullable|string|max:255',
            'institute_id' => 'nullable|string|max:100',
            'branch_id' => 'nullable|string|max:100',
            'in_charge_name' => 'nullable|string|max:255',
            'in_charge_contact' => 'nullable|string|max:50',
            'special_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }

        try {
            // Check if room name already exists
            $roomName = AddRoomName::where('room_id', $request->room_id)->first();
            
            if ($roomName) {
                // Check for duplicate official name (excluding current record)
                if ($request->official_name != $roomName->official_name) {
                    $duplicateOfficialName = AddRoomName::where('official_name', $request->official_name)
                        ->where('id', '!=', $roomName->id)
                        ->first();
                    
                    if ($duplicateOfficialName) {
                        return response()->json([
                            'success' => false,
                            'message' => 'A room with this official name already exists.'
                        ], 409);
                    }
                }
                
                // Update existing
                $roomName->update($request->all());
                $message = 'Room name updated successfully.';
            } else {
                // Check for duplicate official name
                $duplicateOfficialName = AddRoomName::where('official_name', $request->official_name)->first();
                if ($duplicateOfficialName) {
                    return response()->json([
                        'success' => false,
                        'message' => 'A room with this official name already exists.'
                    ], 409);
                }
                
                // Create new
                $roomName = AddRoomName::create($request->all());
                $message = 'Room name created successfully.';
            }

            return response()->json([
                'success' => true,
                'data' => $roomName->load('room'),
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upsert room name.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search room names by various criteria.
     */
    public function search(Request $request): JsonResponse
    {
        $query = AddRoomName::query();

        $query->when($request->filled('search'), function ($q) use ($request) {
            return $q->where(function ($subQuery) use ($request) {
                $subQuery->where('official_name', 'like', '%' . $request->search . '%')
                    ->orWhere('alternative_name', 'like', '%' . $request->search . '%')
                    ->orWhere('purpose', 'like', '%' . $request->search . '%')
                    ->orWhere('department', 'like', '%' . $request->search . '%')
                    ->orWhere('in_charge_name', 'like', '%' . $request->search . '%')
                    ->orWhere('special_notes', 'like', '%' . $request->search . '%');
            });
        })
        ->when($request->filled('institute_id'), function ($q) use ($request) {
            return $q->where('institute_id', $request->institute_id);
        })
        ->when($request->filled('branch_id'), function ($q) use ($request) {
            return $q->where('branch_id', $request->branch_id);
        });

        $roomNames = $query->with(['room'])
            ->orderBy('official_name')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $roomNames,
            'message' => 'Room names search completed successfully.'
        ]);
    }

    /**
     * Get room names by department.
     */
    public function getByDepartment(string $department): JsonResponse
    {
        try {
            $roomNames = AddRoomName::where('department', 'like', '%' . $department . '%')
                ->with(['room'])
                ->orderBy('official_name')
                ->get();

            if ($roomNames->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No room names found for this department.'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $roomNames,
                'message' => 'Room names retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve room names.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get room names by purpose.
     */
    public function getByPurpose(string $purpose): JsonResponse
    {
        try {
            $roomNames = AddRoomName::where('purpose', 'like', '%' . $purpose . '%')
                ->with(['room'])
                ->orderBy('official_name')
                ->get();

            if ($roomNames->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No room names found for this purpose.'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $roomNames,
                'message' => 'Room names retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve room names.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get room names statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = AddRoomName::query();

        if ($request->filled('institute_id')) {
            $query->where('institute_id', $request->institute_id);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        $totalRecords = $query->count();
        
        // Department statistics
        $departments = AddRoomName::select('department', DB::raw('count(*) as count'))
            ->whereNotNull('department')
            ->groupBy('department')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        
        // Purpose statistics
        $purposes = AddRoomName::select('purpose', DB::raw('count(*) as count'))
            ->whereNotNull('purpose')
            ->groupBy('purpose')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        
        // Rooms with alternative names
        $withAlternativeNames = AddRoomName::whereNotNull('alternative_name')->count();
        $withInCharge = AddRoomName::whereNotNull('in_charge_name')->count();
        $withSpecialNotes = AddRoomName::whereNotNull('special_notes')->count();
        
        // Get top in-charge persons
        $topInCharges = AddRoomName::select('in_charge_name', DB::raw('count(*) as room_count'))
            ->whereNotNull('in_charge_name')
            ->groupBy('in_charge_name')
            ->orderBy('room_count', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_records' => $totalRecords,
                'rooms_with_alternative_names' => $withAlternativeNames,
                'rooms_with_in_charge' => $withInCharge,
                'rooms_with_special_notes' => $withSpecialNotes,
                'alternative_names_percentage' => $totalRecords > 0 ? round(($withAlternativeNames / $totalRecords) * 100, 2) : 0,
                'in_charge_assignment_percentage' => $totalRecords > 0 ? round(($withInCharge / $totalRecords) * 100, 2) : 0,
                
                'department_statistics' => [
                    'total_unique_departments' => $departments->count(),
                    'top_departments' => $departments,
                ],
                
                'purpose_statistics' => [
                    'total_unique_purposes' => $purposes->count(),
                    'top_purposes' => $purposes,
                ],
                
                'in_charge_statistics' => [
                    'top_in_charges' => $topInCharges,
                ],
            ],
            'message' => 'Room names statistics retrieved successfully.'
        ]);
    }

    /**
     * Get room name details with room information.
     */
    public function getRoomWithDetails(string $roomId): JsonResponse
    {
        try {
            // Get the room details
            $room = AddRooms::with(['building', 'block', 'floor'])->find($roomId);
            
            if (!$room) {
                return response()->json([
                    'success' => false,
                    'message' => 'Room not found.'
                ], 404);
            }

            // Get the room name record
            $roomName = AddRoomName::where('room_id', $roomId)->first();

            // Combine the data
            $roomDetails = [
                'room' => $room,
                'room_name' => $roomName,
                'combined_info' => [
                    'room_number' => $room->room_number,
                    'room_name' => $room->room_name,
                    'official_name' => $roomName->official_name ?? null,
                    'alternative_name' => $roomName->alternative_name ?? null,
                    'purpose' => $roomName->purpose ?? null,
                    'department' => $roomName->department ?? $room->department,
                    'in_charge_name' => $roomName->in_charge_name ?? $room->in_charge_name,
                    'in_charge_contact' => $roomName->in_charge_contact ?? $room->in_charge_contact,
                    'building' => $room->building->name ?? null,
                    'block' => $room->block->name ?? null,
                    'floor' => $room->floor->floor_number ?? null,
                    'room_type' => $room->room_type,
                    'status' => $room->room_status,
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $roomDetails,
                'message' => 'Room details retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve room details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update room names from CSV or array.
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'room_names' => 'required|array',
            'room_names.*.room_id' => 'required|exists:add_rooms,id',
            'room_names.*.official_name' => 'required|string|max:255',
            'room_names.*.alternative_name' => 'nullable|string|max:255',
            'room_names.*.purpose' => 'nullable|string|max:500',
            'room_names.*.department' => 'nullable|string|max:255',
            'room_names.*.in_charge_name' => 'nullable|string|max:255',
            'room_names.*.in_charge_contact' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }

        $results = [
            'successful' => 0,
            'failed' => 0,
            'errors' => []
        ];

        DB::beginTransaction();
        try {
            foreach ($request->room_names as $index => $roomNameData) {
                try {
                    $roomName = AddRoomName::where('room_id', $roomNameData['room_id'])->first();
                    
                    if ($roomName) {
                        // Update existing
                        $roomName->update($roomNameData);
                    } else {
                        // Create new
                        AddRoomName::create($roomNameData);
                    }
                    
                    $results['successful']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = [
                        'index' => $index,
                        'room_id' => $roomNameData['room_id'] ?? null,
                        'error' => $e->getMessage()
                    ];
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $results,
                'message' => 'Bulk update completed. ' . $results['successful'] . ' successful, ' . $results['failed'] . ' failed.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Bulk update failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get rooms by building for dropdown.
     */
    public function getRoomsByBuilding(string $buildingId): JsonResponse
    {
        try {
            $rooms = AddRooms::where('building_id', $buildingId)
                ->with(['block', 'floor'])
                ->whereDoesntHave('roomName') // Only rooms without names
                ->orderBy('room_number')
                ->get(['id', 'room_number', 'room_type', 'block_id', 'floor_id']);

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
     * Get rooms by block for dropdown.
     */
    public function getRoomsByBlock(string $blockId): JsonResponse
    {
        try {
            $rooms = AddRooms::where('block_id', $blockId)
                ->with(['building', 'floor'])
                ->whereDoesntHave('roomName') // Only rooms without names
                ->orderBy('room_number')
                ->get(['id', 'room_number', 'room_type', 'building_id', 'floor_id']);

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
     * Get rooms by floor for dropdown.
     */
    public function getRoomsByFloor(string $floorId): JsonResponse
    {
        try {
            $rooms = AddRooms::where('floor_id', $floorId)
                ->with(['building', 'block'])
                ->whereDoesntHave('roomName') // Only rooms without names
                ->orderBy('room_number')
                ->get(['id', 'room_number', 'room_type', 'building_id', 'block_id']);

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
     * Get all named rooms.
     */
    public function getAllNamedRooms(Request $request): JsonResponse
    {
        try {
            $roomNames = AddRoomName::with(['room.building', 'room.block', 'room.floor'])
                ->orderBy('official_name')
                ->paginate($request->per_page ?? 15);

            return response()->json([
                'success' => true,
                'data' => $roomNames,
                'message' => 'Named rooms retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve named rooms.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get naming statistics.
     */
    public function getNamingStatistics(): JsonResponse
    {
        try {
            $totalRooms = AddRooms::count();
            $namedRooms = AddRoomName::count();
            $unnamedRooms = $totalRooms - $namedRooms;
            $namingPercentage = $totalRooms > 0 ? round(($namedRooms / $totalRooms) * 100, 2) : 0;

            // Statistics by room type
            $roomTypes = AddRooms::select('room_type', DB::raw('count(*) as total'))
                ->groupBy('room_type')
                ->get();

            $namedByType = AddRoomName::join('add_rooms', 'add_room_names.room_id', '=', 'add_rooms.id')
                ->select('add_rooms.room_type', DB::raw('count(*) as named'))
                ->groupBy('add_rooms.room_type')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_rooms' => $totalRooms,
                    'named_rooms' => $namedRooms,
                    'unnamed_rooms' => $unnamedRooms,
                    'naming_percentage' => $namingPercentage,
                    'room_types' => $roomTypes,
                    'named_by_type' => $namedByType
                ],
                'message' => 'Naming statistics retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve naming statistics.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}