<?php

namespace App\Http\Controllers;

use App\Models\AddRoomsAmeneties;
use App\Models\AddRooms;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AddRoomAmenetiesController extends Controller
{
    /**
     * Display a listing of room amenities.
     */
    public function index(Request $request): JsonResponse
    {
        $query = RoomAmenity::query();

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
        ->when($request->filled('has_speakers'), function ($q) use ($request) {
            return $q->where('has_speakers', $request->boolean('has_speakers'));
        })
        ->when($request->filled('has_computers'), function ($q) use ($request) {
            return $q->where('computers_count', '>', 0);
        })
        ->when($request->filled('has_lab_equipment'), function ($q) use ($request) {
            return $q->where('has_lab_equipment', $request->boolean('has_lab_equipment'));
        })
        ->when($request->filled('has_wheelchair_access'), function ($q) use ($request) {
            return $q->where('has_wheelchair_access', $request->boolean('has_wheelchair_access'));
        })
        ->when($request->filled('min_computers'), function ($q) use ($request) {
            return $q->where('computers_count', '>=', $request->min_computers);
        });

        $amenities = $query->with('room')->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $amenities,
            'message' => 'Room amenities retrieved successfully.'
        ]);
    }

    /**
     * Store a newly created room amenity.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->getValidationRules());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }

        // Check if room exists
        if (!AddRoom::find($request->room_id)) {
            return response()->json([
                'success' => false,
                'message' => 'The specified room does not exist.'
            ], 422);
        }

        // Check if amenity already exists for this room
        $existingAmenity = RoomAmenity::where('room_id', $request->room_id)->first();
        if ($existingAmenity) {
            return response()->json([
                'success' => false,
                'message' => 'Amenities already exist for this room. Use update instead.'
            ], 409);
        }

        try {
            $amenity = RoomAmenity::create($request->all());

            return response()->json([
                'success' => true,
                'data' => $amenity,
                'message' => 'Room amenities created successfully.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create room amenities.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified room amenity.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $amenity = RoomAmenity::with('room')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $amenity,
                'message' => 'Room amenities retrieved successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room amenities not found.'
            ], 404);
        }
    }

    /**
     * Update the specified room amenity.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $amenity = RoomAmenity::findOrFail($id);

            $validator = Validator::make($request->all(), $this->getValidationRules(true));

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed.'
                ], 422);
            }

            // Check if room exists if changing room_id
            if ($request->has('room_id') && $request->room_id != $amenity->room_id) {
                if (!AddRoom::find($request->room_id)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'The specified room does not exist.'
                    ], 422);
                }

                // Check if new room already has amenities
                $existingAmenity = RoomAmenity::where('room_id', $request->room_id)
                    ->where('id', '!=', $id)
                    ->first();
                
                if ($existingAmenity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'The selected room already has amenities.'
                    ], 422);
                }
            }

            $amenity->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $amenity,
                'message' => 'Room amenities updated successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room amenities not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update room amenities.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified room amenity.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $amenity = RoomAmenity::findOrFail($id);
            $amenity->delete();

            return response()->json([
                'success' => true,
                'message' => 'Room amenities deleted successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room amenities not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete room amenities.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get amenities by room ID.
     */
    public function getByRoom(string $roomId): JsonResponse
    {
        try {
            $amenity = RoomAmenity::where('room_id', $roomId)
                ->with('room')
                ->first();

            if (!$amenity) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'No amenities found for this room.'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $amenity,
                'message' => 'Room amenities retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve room amenities.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create or update amenities for a room.
     */
    public function upsert(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->getValidationRules());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }

        // Check if room exists
        if (!AddRoom::find($request->room_id)) {
            return response()->json([
                'success' => false,
                'message' => 'The specified room does not exist.'
            ], 422);
        }

        try {
            // Check if amenity already exists
            $amenity = RoomAmenity::where('room_id', $request->room_id)->first();
            
            if ($amenity) {
                // Update existing
                $amenity->update($request->all());
                $message = 'Room amenities updated successfully.';
            } else {
                // Create new
                $amenity = RoomAmenity::create($request->all());
                $message = 'Room amenities created successfully.';
            }

            return response()->json([
                'success' => true,
                'data' => $amenity,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upsert room amenities.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get room amenities statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = RoomAmenity::query();

        if ($request->filled('institute_id')) {
            $query->where('institute_id', $request->institute_id);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        $totalAmenities = $query->count();
        
        // Furniture Statistics
        $totalChairs = $query->clone()->sum('chairs_count');
        $totalTables = $query->clone()->sum('tables_count');
        $totalDesks = $query->clone()->sum('desks_count');
        $totalBookshelves = $query->clone()->sum('bookshelves_count');
        $totalCabinets = $query->clone()->sum('cabinets_count');
        $totalSofas = $query->clone()->sum('sofas_count');
        
        // Audio Visual Statistics
        $withSpeakers = $query->clone()->where('has_speakers', true)->count();
        $withMicrophone = $query->clone()->where('has_microphone', true)->count();
        $withSoundSystem = $query->clone()->where('has_sound_system', true)->count();
        $withTV = $query->clone()->where('has_tv', true)->count();
        $withDVDPlayer = $query->clone()->where('has_dvd_player', true)->count();
        $withVideoConferencing = $query->clone()->where('has_video_conferencing', true)->count();
        
        // Computer Equipment Statistics
        $withComputers = $query->clone()->where('computers_count', '>', 0)->count();
        $totalComputers = $query->clone()->sum('computers_count');
        $totalPrinters = $query->clone()->sum('printers_count');
        $totalScanners = $query->clone()->sum('scanners_count');
        $withServerRack = $query->clone()->where('has_server_rack', true)->count();
        $withNetworkSwitch = $query->clone()->where('has_network_switch', true)->count();
        $totalNetworkPorts = $query->clone()->sum('network_ports_count');
        
        // Laboratory Equipment Statistics
        $withLabEquipment = $query->clone()->where('has_lab_equipment', true)->count();
        $withFumeHood = $query->clone()->where('has_fume_hood', true)->count();
        $withSafetyShower = $query->clone()->where('has_safety_shower', true)->count();
        $withEyeWashStation = $query->clone()->where('has_eye_wash_station', true)->count();
        $withGasSupply = $query->clone()->where('has_gas_supply', true)->count();
        $withWaterSupply = $query->clone()->where('has_water_supply', true)->count();
        
        // Special Features Statistics
        $withWheelchairAccess = $query->clone()->where('has_wheelchair_access', true)->count();
        $withBrailleSignage = $query->clone()->where('has_braille_signage', true)->count();
        $withEmergencyLighting = $query->clone()->where('has_emergency_lighting', true)->count();
        $withBackupPower = $query->clone()->where('has_backup_power', true)->count();
        $withIntercom = $query->clone()->where('has_intercom', true)->count();
        $withTelephone = $query->clone()->where('has_telephone', true)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_amenities_records' => $totalAmenities,
                
                'furniture_statistics' => [
                    'total_chairs' => $totalChairs,
                    'total_tables' => $totalTables,
                    'total_desks' => $totalDesks,
                    'total_bookshelves' => $totalBookshelves,
                    'total_cabinets' => $totalCabinets,
                    'total_sofas' => $totalSofas,
                    'avg_chairs_per_room' => $totalAmenities > 0 ? round($totalChairs / $totalAmenities, 2) : 0,
                    'avg_computers_per_room' => $totalAmenities > 0 ? round($totalComputers / $totalAmenities, 2) : 0,
                ],
                
                'audio_visual_statistics' => [
                    'rooms_with_speakers' => $withSpeakers,
                    'rooms_with_microphone' => $withMicrophone,
                    'rooms_with_sound_system' => $withSoundSystem,
                    'rooms_with_tv' => $withTV,
                    'rooms_with_dvd_player' => $withDVDPlayer,
                    'rooms_with_video_conferencing' => $withVideoConferencing,
                    'speakers_coverage' => $totalAmenities > 0 ? round(($withSpeakers / $totalAmenities) * 100, 2) : 0,
                    'video_conferencing_coverage' => $totalAmenities > 0 ? round(($withVideoConferencing / $totalAmenities) * 100, 2) : 0,
                ],
                
                'computer_equipment_statistics' => [
                    'rooms_with_computers' => $withComputers,
                    'total_computers' => $totalComputers,
                    'total_printers' => $totalPrinters,
                    'total_scanners' => $totalScanners,
                    'rooms_with_server_rack' => $withServerRack,
                    'rooms_with_network_switch' => $withNetworkSwitch,
                    'total_network_ports' => $totalNetworkPorts,
                    'computer_coverage' => $totalAmenities > 0 ? round(($withComputers / $totalAmenities) * 100, 2) : 0,
                ],
                
                'lab_equipment_statistics' => [
                    'rooms_with_lab_equipment' => $withLabEquipment,
                    'rooms_with_fume_hood' => $withFumeHood,
                    'rooms_with_safety_shower' => $withSafetyShower,
                    'rooms_with_eye_wash_station' => $withEyeWashStation,
                    'rooms_with_gas_supply' => $withGasSupply,
                    'rooms_with_water_supply' => $withWaterSupply,
                    'lab_equipment_coverage' => $totalAmenities > 0 ? round(($withLabEquipment / $totalAmenities) * 100, 2) : 0,
                ],
                
                'special_features_statistics' => [
                    'rooms_with_wheelchair_access' => $withWheelchairAccess,
                    'rooms_with_braille_signage' => $withBrailleSignage,
                    'rooms_with_emergency_lighting' => $withEmergencyLighting,
                    'rooms_with_backup_power' => $withBackupPower,
                    'rooms_with_intercom' => $withIntercom,
                    'rooms_with_telephone' => $withTelephone,
                    'wheelchair_access_coverage' => $totalAmenities > 0 ? round(($withWheelchairAccess / $totalAmenities) * 100, 2) : 0,
                    'emergency_lighting_coverage' => $totalAmenities > 0 ? round(($withEmergencyLighting / $totalAmenities) * 100, 2) : 0,
                ],
            ],
            'message' => 'Room amenities statistics retrieved successfully.'
        ]);
    }

    /**
     * Get summary of amenities for a specific room.
     */
    public function getRoomSummary(string $roomId): JsonResponse
    {
        try {
            $amenity = RoomAmenity::where('room_id', $roomId)
                ->with('room')
                ->first();
            
            if (!$amenity) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'has_amenities' => false,
                        'message' => 'No amenities recorded for this room.'
                    ],
                    'message' => 'No amenities found for this room.'
                ]);
            }

            // Create a summary object
            $summary = [
                'has_amenities' => true,
                'room_info' => [
                    'room_number' => $amenity->room->room_number ?? null,
                    'room_name' => $amenity->room->room_name ?? null,
                    'room_type' => $amenity->room->room_type ?? null,
                ],
                'furniture' => [
                    'chairs_count' => $amenity->chairs_count,
                    'tables_count' => $amenity->tables_count,
                    'desks_count' => $amenity->desks_count,
                    'bookshelves_count' => $amenity->bookshelves_count,
                    'cabinets_count' => $amenity->cabinets_count,
                    'sofas_count' => $amenity->sofas_count,
                ],
                'audio_visual' => [
                    'has_speakers' => $amenity->has_speakers,
                    'has_microphone' => $amenity->has_microphone,
                    'has_sound_system' => $amenity->has_sound_system,
                    'has_tv' => $amenity->has_tv,
                    'has_video_conferencing' => $amenity->has_video_conferencing,
                ],
                'computer_equipment' => [
                    'computers_count' => $amenity->computers_count,
                    'printers_count' => $amenity->printers_count,
                    'scanners_count' => $amenity->scanners_count,
                    'has_server_rack' => $amenity->has_server_rack,
                    'has_network_switch' => $amenity->has_network_switch,
                    'network_ports_count' => $amenity->network_ports_count,
                ],
                'lab_equipment' => [
                    'has_lab_equipment' => $amenity->has_lab_equipment,
                    'has_fume_hood' => $amenity->has_fume_hood,
                    'has_safety_shower' => $amenity->has_safety_shower,
                    'has_eye_wash_station' => $amenity->has_eye_wash_station,
                ],
                'special_features' => [
                    'has_wheelchair_access' => $amenity->has_wheelchair_access,
                    'has_braille_signage' => $amenity->has_braille_signage,
                    'has_emergency_lighting' => $amenity->has_emergency_lighting,
                    'has_backup_power' => $amenity->has_backup_power,
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $summary,
                'message' => 'Room amenities summary retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve room amenities summary.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get rooms with specific amenities.
     */
    public function getRoomsWithAmenities(Request $request): JsonResponse
    {
        $query = RoomAmenity::query();

        // Apply amenity filters
        if ($request->filled('has_computers')) {
            $query->where('computers_count', '>', 0);
        }
        if ($request->filled('has_projector')) {
            // Assuming projector is in the room table, not amenities
            $query->whereHas('room', function ($q) {
                $q->where('has_projector', true);
            });
        }
        if ($request->filled('has_wheelchair_access')) {
            $query->where('has_wheelchair_access', true);
        }
        if ($request->filled('min_computers')) {
            $query->where('computers_count', '>=', $request->min_computers);
        }
        if ($request->filled('has_lab_equipment')) {
            $query->where('has_lab_equipment', true);
        }

        $amenities = $query->with('room')
            ->orderBy('room_id')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $amenities,
            'message' => 'Rooms with specified amenities retrieved successfully.'
        ]);
    }

    /**
     * Update specific furniture counts.
     */
    public function updateFurniture(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'chairs_count' => 'nullable|integer|min:0',
            'tables_count' => 'nullable|integer|min:0',
            'desks_count' => 'nullable|integer|min:0',
            'bookshelves_count' => 'nullable|integer|min:0',
            'cabinets_count' => 'nullable|integer|min:0',
            'sofas_count' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }

        try {
            $amenity = RoomAmenity::findOrFail($id);
            $amenity->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $amenity,
                'message' => 'Furniture counts updated successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room amenities not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update furniture counts.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update computer equipment.
     */
    public function updateComputerEquipment(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'computers_count' => 'nullable|integer|min:0',
            'printers_count' => 'nullable|integer|min:0',
            'scanners_count' => 'nullable|integer|min:0',
            'has_server_rack' => 'nullable|boolean',
            'has_network_switch' => 'nullable|boolean',
            'network_ports_count' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }

        try {
            $amenity = RoomAmenity::findOrFail($id);
            $amenity->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $amenity,
                'message' => 'Computer equipment updated successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room amenities not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update computer equipment.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get validation rules.
     */
    private function getValidationRules(bool $forUpdate = false): array
    {
        $rules = [
            'room_id' => 'required|string|max:100',
            'institute_id' => 'nullable|string|max:100',
            'branch_id' => 'nullable|string|max:100',
            
            // Furniture
            'chairs_count' => 'nullable|integer|min:0',
            'tables_count' => 'nullable|integer|min:0',
            'desks_count' => 'nullable|integer|min:0',
            'bookshelves_count' => 'nullable|integer|min:0',
            'cabinets_count' => 'nullable|integer|min:0',
            'sofas_count' => 'nullable|integer|min:0',
            
            // Audio Visual Equipment
            'has_speakers' => 'nullable|boolean',
            'has_microphone' => 'nullable|boolean',
            'has_sound_system' => 'nullable|boolean',
            'has_tv' => 'nullable|boolean',
            'has_dvd_player' => 'nullable|boolean',
            'has_video_conferencing' => 'nullable|boolean',
            
            // Computer Equipment
            'computers_count' => 'nullable|integer|min:0',
            'printers_count' => 'nullable|integer|min:0',
            'scanners_count' => 'nullable|integer|min:0',
            'has_server_rack' => 'nullable|boolean',
            'has_network_switch' => 'nullable|boolean',
            'network_ports_count' => 'nullable|integer|min:0',
            
            // Laboratory Equipment
            'has_lab_equipment' => 'nullable|boolean',
            'has_fume_hood' => 'nullable|boolean',
            'has_safety_shower' => 'nullable|boolean',
            'has_eye_wash_station' => 'nullable|boolean',
            'has_gas_supply' => 'nullable|boolean',
            'has_water_supply' => 'nullable|boolean',
            
            // Special Features
            'has_wheelchair_access' => 'nullable|boolean',
            'has_braille_signage' => 'nullable|boolean',
            'has_emergency_lighting' => 'nullable|boolean',
            'has_backup_power' => 'nullable|boolean',
            'has_intercom' => 'nullable|boolean',
            'has_telephone' => 'nullable|boolean',
        ];

        return $rules;
    }
}