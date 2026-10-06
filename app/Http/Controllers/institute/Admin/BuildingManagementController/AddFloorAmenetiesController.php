<?php

namespace App\Http\Controllers\institute\Admin\BuildingManagementController;
use App\Http\Controllers\Controller;

use App\Models\AddFloorAmeneties;
use App\Models\AddFloor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AddFloorsAmenetiesController extends Controller
{
    /**
     * Display a listing of floor amenities.
     */
    public function index(Request $request): JsonResponse
    {
        $query = AddFloorsAmenity::query();

        // Apply filters
        $query->when($request->filled('floor_id'), function ($q) use ($request) {
            return $q->where('floor_id', $request->floor_id);
        })
        ->when($request->filled('institute_id'), function ($q) use ($request) {
            return $q->where('institute_id', $request->institute_id);
        })
        ->when($request->filled('branch_id'), function ($q) use ($request) {
            return $q->where('branch_id', $request->branch_id);
        })
        ->when($request->filled('has_backup_generator'), function ($q) use ($request) {
            return $q->where('has_backup_generator', $request->boolean('has_backup_generator'));
        })
        ->when($request->filled('has_cctv'), function ($q) use ($request) {
            return $q->where('has_cctv', $request->boolean('has_cctv'));
        })
        ->when($request->filled('has_network_cabling'), function ($q) use ($request) {
            return $q->where('has_network_cabling', $request->boolean('has_network_cabling'));
        })
        ->when($request->filled('has_server_room'), function ($q) use ($request) {
            return $q->where('has_server_room', $request->boolean('has_server_room'));
        });

        $amenities = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $amenities,
            'message' => 'Floor amenities retrieved successfully.'
        ]);
    }

    /**
     * Store a newly created floor amenity.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'floor_id' => 'required|exists:add_floors,id',
            'institute_id' => 'nullable|string|max:100',
            'branch_id' => 'nullable|string|max:100',
            
            // Electrical
            'has_backup_generator' => 'nullable|boolean',
            'has_ups' => 'nullable|boolean',
            'power_sockets_count' => 'nullable|integer|min:0',
            'light_points_count' => 'nullable|integer|min:0',
            'fan_points_count' => 'nullable|integer|min:0',
            
            // Network & Communication
            'has_network_cabling' => 'nullable|boolean',
            'has_intercom' => 'nullable|boolean',
            'has_telephone_lines' => 'nullable|boolean',
            'network_ports_count' => 'nullable|integer|min:0',
            
            // Security
            'has_cctv' => 'nullable|boolean',
            'cctv_cameras_count' => 'nullable|integer|min:0',
            'has_security_desk' => 'nullable|boolean',
            'has_access_control' => 'nullable|boolean',
            
            // Furniture & Fixtures
            'has_chairs' => 'nullable|boolean',
            'has_tables' => 'nullable|boolean',
            'has_whiteboards' => 'nullable|boolean',
            'has_smart_boards' => 'nullable|boolean',
            'has_projectors' => 'nullable|boolean',
            'whiteboard_count' => 'nullable|integer|min:0',
            'smart_board_count' => 'nullable|integer|min:0',
            'projector_count' => 'nullable|integer|min:0',
            
            // Special Rooms
            'has_server_room' => 'nullable|boolean',
            'has_storage_room' => 'nullable|boolean',
            'has_cleaner_room' => 'nullable|boolean',
            'has_electric_room' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }

        // Check if amenity already exists for this floor
        $existingAmenity = AddFloorsAmenity::where('floor_id', $request->floor_id)->first();
        if ($existingAmenity) {
            return response()->json([
                'success' => false,
                'message' => 'Amenities already exist for this floor. Use update instead.'
            ], 409);
        }

        try {
            $amenity = AddFloorsAmenity::create($request->all());

            return response()->json([
                'success' => true,
                'data' => $amenity,
                'message' => 'Floor amenities created successfully.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create floor amenities.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified floor amenity.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $amenity = AddFloorsAmenity::with('floor')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $amenity,
                'message' => 'Floor amenities retrieved successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Floor amenities not found.'
            ], 404);
        }
    }

    /**
     * Update the specified floor amenity.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $amenity = AddFloorsAmenity::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'floor_id' => 'sometimes|required|exists:add_floors,id',
                'institute_id' => 'nullable|string|max:100',
                'branch_id' => 'nullable|string|max:100',
                
                // Electrical
                'has_backup_generator' => 'nullable|boolean',
                'has_ups' => 'nullable|boolean',
                'power_sockets_count' => 'nullable|integer|min:0',
                'light_points_count' => 'nullable|integer|min:0',
                'fan_points_count' => 'nullable|integer|min:0',
                
                // Network & Communication
                'has_network_cabling' => 'nullable|boolean',
                'has_intercom' => 'nullable|boolean',
                'has_telephone_lines' => 'nullable|boolean',
                'network_ports_count' => 'nullable|integer|min:0',
                
                // Security
                'has_cctv' => 'nullable|boolean',
                'cctv_cameras_count' => 'nullable|integer|min:0',
                'has_security_desk' => 'nullable|boolean',
                'has_access_control' => 'nullable|boolean',
                
                // Furniture & Fixtures
                'has_chairs' => 'nullable|boolean',
                'has_tables' => 'nullable|boolean',
                'has_whiteboards' => 'nullable|boolean',
                'has_smart_boards' => 'nullable|boolean',
                'has_projectors' => 'nullable|boolean',
                'whiteboard_count' => 'nullable|integer|min:0',
                'smart_board_count' => 'nullable|integer|min:0',
                'projector_count' => 'nullable|integer|min:0',
                
                // Special Rooms
                'has_server_room' => 'nullable|boolean',
                'has_storage_room' => 'nullable|boolean',
                'has_cleaner_room' => 'nullable|boolean',
                'has_electric_room' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed.'
                ], 422);
            }

            // Check if floor_id is being changed to one that already has amenities
            if ($request->has('floor_id') && $request->floor_id != $amenity->floor_id) {
                $existingAmenity = AddFloorsAmenity::where('floor_id', $request->floor_id)
                    ->where('id', '!=', $id)
                    ->first();
                
                if ($existingAmenity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'The selected floor already has amenities.'
                    ], 422);
                }
            }

            $amenity->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $amenity,
                'message' => 'Floor amenities updated successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Floor amenities not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update floor amenities.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified floor amenity.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $amenity = AddFloorsAmenity::findOrFail($id);
            $amenity->delete();

            return response()->json([
                'success' => true,
                'message' => 'Floor amenities deleted successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Floor amenities not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete floor amenities.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get amenities by floor ID.
     */
    public function getByFloor(string $floorId): JsonResponse
    {
        try {
            $amenity = AddFloorsAmenity::where('floor_id', $floorId)->first();

            if (!$amenity) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'No amenities found for this floor.'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $amenity,
                'message' => 'Floor amenities retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve floor amenities.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create or update amenities for a floor.
     */
    public function upsert(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'floor_id' => 'required|exists:add_floors,id',
            'institute_id' => 'nullable|string|max:100',
            'branch_id' => 'nullable|string|max:100',
            
            // Electrical
            'has_backup_generator' => 'nullable|boolean',
            'has_ups' => 'nullable|boolean',
            'power_sockets_count' => 'nullable|integer|min:0',
            'light_points_count' => 'nullable|integer|min:0',
            'fan_points_count' => 'nullable|integer|min:0',
            
            // Network & Communication
            'has_network_cabling' => 'nullable|boolean',
            'has_intercom' => 'nullable|boolean',
            'has_telephone_lines' => 'nullable|boolean',
            'network_ports_count' => 'nullable|integer|min:0',
            
            // Security
            'has_cctv' => 'nullable|boolean',
            'cctv_cameras_count' => 'nullable|integer|min:0',
            'has_security_desk' => 'nullable|boolean',
            'has_access_control' => 'nullable|boolean',
            
            // Furniture & Fixtures
            'has_chairs' => 'nullable|boolean',
            'has_tables' => 'nullable|boolean',
            'has_whiteboards' => 'nullable|boolean',
            'has_smart_boards' => 'nullable|boolean',
            'has_projectors' => 'nullable|boolean',
            'whiteboard_count' => 'nullable|integer|min:0',
            'smart_board_count' => 'nullable|integer|min:0',
            'projector_count' => 'nullable|integer|min:0',
            
            // Special Rooms
            'has_server_room' => 'nullable|boolean',
            'has_storage_room' => 'nullable|boolean',
            'has_cleaner_room' => 'nullable|boolean',
            'has_electric_room' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }

        try {
            // Check if amenity already exists
            $amenity = AddFloorsAmenity::where('floor_id', $request->floor_id)->first();
            
            if ($amenity) {
                // Update existing
                $amenity->update($request->all());
                $message = 'Floor amenities updated successfully.';
            } else {
                // Create new
                $amenity = AddFloorsAmenity::create($request->all());
                $message = 'Floor amenities created successfully.';
            }

            return response()->json([
                'success' => true,
                'data' => $amenity,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upsert floor amenities.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get amenities statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = AddFloorsAmenity::query();

        if ($request->filled('institute_id')) {
            $query->where('institute_id', $request->institute_id);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        $totalAmenities = $query->count();
        
        // Electrical Statistics
        $withBackupGenerator = $query->clone()->where('has_backup_generator', true)->count();
        $withUPS = $query->clone()->where('has_ups', true)->count();
        $totalPowerSockets = $query->clone()->sum('power_sockets_count');
        $totalLightPoints = $query->clone()->sum('light_points_count');
        $totalFanPoints = $query->clone()->sum('fan_points_count');
        
        // Network Statistics
        $withNetworkCabling = $query->clone()->where('has_network_cabling', true)->count();
        $withIntercom = $query->clone()->where('has_intercom', true)->count();
        $withTelephoneLines = $query->clone()->where('has_telephone_lines', true)->count();
        $totalNetworkPorts = $query->clone()->sum('network_ports_count');
        
        // Security Statistics
        $withCCTV = $query->clone()->where('has_cctv', true)->count();
        $withSecurityDesk = $query->clone()->where('has_security_desk', true)->count();
        $withAccessControl = $query->clone()->where('has_access_control', true)->count();
        $totalCctvCameras = $query->clone()->sum('cctv_cameras_count');
        
        // Furniture Statistics
        $withChairs = $query->clone()->where('has_chairs', true)->count();
        $withTables = $query->clone()->where('has_tables', true)->count();
        $withWhiteboards = $query->clone()->where('has_whiteboards', true)->count();
        $withSmartBoards = $query->clone()->where('has_smart_boards', true)->count();
        $withProjectors = $query->clone()->where('has_projectors', true)->count();
        $totalWhiteboards = $query->clone()->sum('whiteboard_count');
        $totalSmartBoards = $query->clone()->sum('smart_board_count');
        $totalProjectors = $query->clone()->sum('projector_count');
        
        // Special Rooms Statistics
        $withServerRoom = $query->clone()->where('has_server_room', true)->count();
        $withStorageRoom = $query->clone()->where('has_storage_room', true)->count();
        $withCleanerRoom = $query->clone()->where('has_cleaner_room', true)->count();
        $withElectricRoom = $query->clone()->where('has_electric_room', true)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_amenities_records' => $totalAmenities,
                
                'electrical_statistics' => [
                    'floors_with_backup_generator' => $withBackupGenerator,
                    'floors_with_ups' => $withUPS,
                    'total_power_sockets' => $totalPowerSockets,
                    'total_light_points' => $totalLightPoints,
                    'total_fan_points' => $totalFanPoints,
                    'avg_power_sockets_per_floor' => $totalAmenities > 0 ? round($totalPowerSockets / $totalAmenities, 2) : 0,
                    'avg_light_points_per_floor' => $totalAmenities > 0 ? round($totalLightPoints / $totalAmenities, 2) : 0,
                ],
                
                'network_statistics' => [
                    'floors_with_network_cabling' => $withNetworkCabling,
                    'floors_with_intercom' => $withIntercom,
                    'floors_with_telephone_lines' => $withTelephoneLines,
                    'total_network_ports' => $totalNetworkPorts,
                    'avg_network_ports_per_floor' => $totalAmenities > 0 ? round($totalNetworkPorts / $totalAmenities, 2) : 0,
                ],
                
                'security_statistics' => [
                    'floors_with_cctv' => $withCCTV,
                    'floors_with_security_desk' => $withSecurityDesk,
                    'floors_with_access_control' => $withAccessControl,
                    'total_cctv_cameras' => $totalCctvCameras,
                    'avg_cctv_cameras_per_floor' => $totalAmenities > 0 ? round($totalCctvCameras / $totalAmenities, 2) : 0,
                ],
                
                'furniture_statistics' => [
                    'floors_with_chairs' => $withChairs,
                    'floors_with_tables' => $withTables,
                    'floors_with_whiteboards' => $withWhiteboards,
                    'floors_with_smart_boards' => $withSmartBoards,
                    'floors_with_projectors' => $withProjectors,
                    'total_whiteboards' => $totalWhiteboards,
                    'total_smart_boards' => $totalSmartBoards,
                    'total_projectors' => $totalProjectors,
                    'avg_whiteboards_per_floor' => $totalAmenities > 0 ? round($totalWhiteboards / $totalAmenities, 2) : 0,
                    'avg_projectors_per_floor' => $totalAmenities > 0 ? round($totalProjectors / $totalAmenities, 2) : 0,
                ],
                
                'special_rooms_statistics' => [
                    'floors_with_server_room' => $withServerRoom,
                    'floors_with_storage_room' => $withStorageRoom,
                    'floors_with_cleaner_room' => $withCleanerRoom,
                    'floors_with_electric_room' => $withElectricRoom,
                ],
                
                'coverage_percentages' => [
                    'backup_generator_coverage' => $totalAmenities > 0 ? round(($withBackupGenerator / $totalAmenities) * 100, 2) : 0,
                    'cctv_coverage' => $totalAmenities > 0 ? round(($withCCTV / $totalAmenities) * 100, 2) : 0,
                    'network_cabling_coverage' => $totalAmenities > 0 ? round(($withNetworkCabling / $totalAmenities) * 100, 2) : 0,
                    'whiteboard_coverage' => $totalAmenities > 0 ? round(($withWhiteboards / $totalAmenities) * 100, 2) : 0,
                    'projector_coverage' => $totalAmenities > 0 ? round(($withProjectors / $totalAmenities) * 100, 2) : 0,
                ]
            ],
            'message' => 'Amenities statistics retrieved successfully.'
        ]);
    }

    /**
     * Get summary of amenities for a specific floor.
     */
    public function getFloorSummary(string $floorId): JsonResponse
    {
        try {
            $amenity = AddFloorsAmenity::where('floor_id', $floorId)->first();
            
            if (!$amenity) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'has_amenities' => false,
                        'message' => 'No amenities recorded for this floor.'
                    ],
                    'message' => 'No amenities found for this floor.'
                ]);
            }

            // Create a summary object
            $summary = [
                'has_amenities' => true,
                'electrical' => [
                    'has_backup_generator' => $amenity->has_backup_generator,
                    'has_ups' => $amenity->has_ups,
                    'power_sockets_count' => $amenity->power_sockets_count,
                    'light_points_count' => $amenity->light_points_count,
                ],
                'network' => [
                    'has_network_cabling' => $amenity->has_network_cabling,
                    'has_intercom' => $amenity->has_intercom,
                    'network_ports_count' => $amenity->network_ports_count,
                ],
                'security' => [
                    'has_cctv' => $amenity->has_cctv,
                    'cctv_cameras_count' => $amenity->cctv_cameras_count,
                    'has_access_control' => $amenity->has_access_control,
                ],
                'furniture' => [
                    'has_whiteboards' => $amenity->has_whiteboards,
                    'whiteboard_count' => $amenity->whiteboard_count,
                    'has_smart_boards' => $amenity->has_smart_boards,
                    'smart_board_count' => $amenity->smart_board_count,
                    'has_projectors' => $amenity->has_projectors,
                    'projector_count' => $amenity->projector_count,
                ],
                'special_rooms' => [
                    'has_server_room' => $amenity->has_server_room,
                    'has_storage_room' => $amenity->has_storage_room,
                    'has_electric_room' => $amenity->has_electric_room,
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $summary,
                'message' => 'Floor amenities summary retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve floor amenities summary.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}