<?php

namespace App\Http\Controllers\institute\Admin\BuildingManagementController;

use App\Http\Controllers\Controller;
use App\Models\AddBuilding;
use App\Models\AddBlock;
use App\Models\AddFloor;
use App\Models\Amenity;
use App\Models\AmenityUnit;
use App\Models\InstituteBasicDetails;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AddBuildingController extends Controller
{
    /**
     * Display the building management page.
     */
    public function Buildingpage()
    {
      
            $merchantId = auth()->user()->institute_id;
            $campus = InstituteBasicDetails::where('fincap_merchant_id', $merchantId)->first();
            
            // Fetch all buildings to populate the dropdown
            AddBuilding::firstOrCreate(
                ['institute_id' => $merchantId],
                [
                    'name' => $campus->name ?? 'Main Campus',
                    'area_value' => 0.00
                ]
            );
            
            $buildings = AddBuilding::where('institute_id', $merchantId)
                ->orderBy('name')
                ->get();
            $number_of_blocks = $buildings[0]['number_of_blocks'] ?? 1;
            
            // Get the first building
            $campus = $buildings[0] ?? null;
            
            // Fetch blocks for the first building
            $blocks = [];
            if ($campus) {
                $blocks = AddBlock::where('building_id', $campus->id)
                    ->where('status', 'active')
                    ->get();
            }
            
            // Fetch amenities from database
            $amenitiesData = [];
            if ($merchantId && class_exists(\App\Models\Amenity::class)) {
               
                    $amenities = Amenity::where('institute_id', $merchantId)
                        ->where('is_active', true)
                        ->where('count', '>', 0)
                        ->withCount('units')
                        ->get();
                    
                    foreach ($amenities as $amenity) {
                        $amenitiesData[$amenity->name] = [
                            'id' => $amenity->id,
                            'amenity_id' => $amenity->amenity_id,
                            'name' => $amenity->name,
                            'category' => $amenity->category ?? 'general',
                            'total_units' => $amenity->units_count ?? 0,
                            'count' => $amenity->count ?? 0,
                        ];
                       
                    }
               
            }
            
            // Prepare campus data with blocks and amenities
            $campusData = null;
            if ($campus) {
                // Get existing amenities from building
                $buildingAmenities = $campus->amenities ?? [];
                if (is_string($buildingAmenities)) {
                    $buildingAmenities = json_decode($buildingAmenities, true) ?? [];
                }
                
                $campusData = [
                    'id' => $campus->id,
                    'name' => $campus->name,
                    'code' => $campus->code ?? $campus->fincap_merchant_id,
                    'area_value' => $campus->area_value ?? 0,
                    'area_unit' => $campus->area_unit ?? 'sq_ft',
                    'number_of_blocks' => $campus->number_of_blocks ?? 1,
                    'blocks' => $blocks->toArray(),
                    'additional_areas' => $campus->additional_areas ?? [],
                    'gates' => $campus->gates ?? [],
                    'amenities' => $amenitiesData,
                    'custom_amenities' => $campus->custom_amenities ?? [],
                    'facilities' => $campus->facilities ?? [],
                    'allocated_amenities' => $buildingAmenities ?? [],
                ];
            }

            return view('instituteAdmin.CreateBuildings.buildingManagementBuildings', 
                compact('campus', 'buildings', 'blocks', 'campusData', 'number_of_blocks')
            );
            
       
    }

    /**
     * Display the building details page.
     */
    public function showData($id)
    {
        try {
            // Eager load blocks and rooms
            $building = AddBuilding::with([
                'blocks.floors.rooms'
            ])->findOrFail($id);
            
            // Get photo URL if exists
            if ($building->photo) {
                $building->photo_url = Storage::disk('public')->url($building->photo);
            }
            
            // Helper function to safely get data
            $getData = function($field) use ($building) {
                $value = $building->$field;
                
                if (is_array($value) || is_object($value)) {
                    return (array) $value;
                }
                
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
            $gates = $getData('gates');
            $additionalAreas = $getData('additional_areas');
            $amenities = $getData('amenities');
            $customAmenities = $getData('custom_amenities');
            $facilities = $getData('facilities');
            
            // ===== GET WASHROOMS FROM FACILITIES =====
            $washrooms = $facilities['washrooms'] ?? [];
            $washroomCount = count($washrooms);
            
            // Get individual washroom counts by type
            $maleWashroomCount = 0;
            $femaleWashroomCount = 0;
            $unisexWashroomCount = 0;
            
            foreach ($washrooms as $washroom) {
                if (isset($washroom['type'])) {
                    if ($washroom['type'] === 'male') $maleWashroomCount++;
                    elseif ($washroom['type'] === 'female') $femaleWashroomCount++;
                    elseif ($washroom['type'] === 'unisex') $unisexWashroomCount++;
                }
            }
            
            // Calculate counts
            $gatesCount = count(array_filter($gates, function($g) { 
                return !empty($g['name']) || !empty($g['number']); 
            }));
            
            $areasCount = count(array_filter($additionalAreas, function($a) { 
                return !empty($a['name']); 
            }));
            
            // Count enabled predefined amenities
            $enabledAmenitiesCount = 0;
            if (is_array($amenities) && !empty($amenities)) {
                foreach ($amenities as $key => $value) {
                    if (is_array($value)) {
                        if (isset($value['enabled']) && ($value['enabled'] === true || $value['enabled'] === 'true' || $value['enabled'] === 1 || $value['enabled'] === '1')) {
                            $enabledAmenitiesCount++;
                        }
                    } else {
                        if ($value === true || $value === 'true' || $value === 1 || $value === '1') {
                            $enabledAmenitiesCount++;
                        }
                    }
                }
            }
            
            // Count enabled facilities
            $facilityTypes = ['parking', 'playground', 'swimming_pool', 'clubhouse', 'warehouse', 'store_room', 'auditorium'];
            $facilitiesCount = 0;
            foreach ($facilityTypes as $type) {
                if (isset($facilities[$type]) && is_array($facilities[$type]) && isset($facilities[$type]['enabled']) && $facilities[$type]['enabled']) {
                    $facilitiesCount++;
                }
            }
            
            $customAmenitiesCount = count(array_filter($customAmenities, function($a) { 
                return !empty($a['name']); 
            }));
            
            $totalAmenities = $enabledAmenitiesCount + $customAmenitiesCount;
            
            // ===== GET BLOCKS AND CALCULATE TOTALS =====
            $blocks = $building->blocks ?? collect();
            
            $totalFloors = 0;
            $totalRooms = 0;
            $totalCapacity = 0;
            $totalBlockCount = $blocks->count();
            
            $allRooms = collect();
            
            foreach ($blocks as $block) {
                $totalFloors += $block->floors->count();
                foreach ($block->floors as $floor) {
                    $totalRooms += $floor->total_rooms ?? 0;
                    $totalCapacity += $floor->total_capacity ?? 0;
                    $allRooms = $allRooms->merge($floor->rooms);
                }
            }
            
            if ($blocks->isEmpty()) {
                $totalFloors = $building->total_floors ?? $building->floors ?? 0;
                $totalRooms = $building->total_rooms ?? $building->rooms ?? 0;
                $totalCapacity = $building->total_capacity ?? $building->capacity ?? 0;
                $totalBlockCount = $building->number_of_blocks ?? $building->total_blocks ?? 0;
            }
            
            $totalRoomCount = $allRooms->count() > 0 ? $allRooms->count() : $totalRooms;
            
            // Get room statistics
            $roomStats = [];
            if ($allRooms->count() > 0) {
                $roomStats = [
                    'total' => $allRooms->count(),
                    'occupied' => $allRooms->where('occupancy_status', 'occupied')->count(),
                    'vacant' => $allRooms->where('occupancy_status', 'vacant')->count(),
                    'maintenance' => $allRooms->where('occupancy_status', 'maintenance')->count(),
                    'reserved' => $allRooms->where('occupancy_status', 'reserved')->count(),
                ];
            }
            
            return view('instituteAdmin.CreateBuildings.buildingsView', compact(
                'building',
                'gates',
                'additionalAreas',
                'amenities',
                'customAmenities',
                'facilities',
                'gatesCount',
                'areasCount',
                'enabledAmenitiesCount',
                'customAmenitiesCount',
                'totalAmenities',
                'facilitiesCount',
                'blocks',
                'allRooms',
                'totalFloors',
                'totalRooms',
                'totalRoomCount',
                'totalCapacity',
                'totalBlockCount',
                'roomStats',
                'washrooms',
                'washroomCount',
                'maleWashroomCount',
                'femaleWashroomCount',
                'unisexWashroomCount'
            ));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Building not found');
        } catch (\Exception $e) {
            Log::error('Error loading building details: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            abort(500, 'Error loading building details');
        }
    }

    /**
     * Show the form for editing the specified building.
     */
    public function edit(string $id)
    {
        try {
            $building = AddBuilding::findOrFail($id);
            
            if ($building->photo) {
                $building->photo_url = Storage::disk('public')->url($building->photo);
            }
            
            $getData = function($field) use ($building) {
                $value = $building->$field;
                if (is_array($value) || is_object($value)) {
                    return (array) $value;
                }
                if (is_string($value)) {
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return $decoded ?? [];
                    }
                    return [];
                }
                return [];
            };

            $gates = $getData('gates');
            $additionalAreas = $getData('additional_areas');
            $amenities = $getData('amenities');
            $customAmenities = $getData('custom_amenities');
            $facilities = $getData('facilities');
            
            $washrooms = $facilities['washrooms'] ?? [];
            $washroomCount = count($washrooms);
            
            $maleWashroomCount = 0;
            $femaleWashroomCount = 0;
            $unisexWashroomCount = 0;
            
            foreach ($washrooms as $washroom) {
                if (isset($washroom['type'])) {
                    if ($washroom['type'] === 'male') $maleWashroomCount++;
                    elseif ($washroom['type'] === 'female') $femaleWashroomCount++;
                    elseif ($washroom['type'] === 'unisex') $unisexWashroomCount++;
                }
            }
            
            $enabledAmenitiesCount = 0;
            if (is_array($amenities)) {
                foreach ($amenities as $key => $value) {
                    if (is_array($value)) {
                        if (isset($value['enabled']) && ($value['enabled'] === true || $value['enabled'] === 'true' || $value['enabled'] === 1 || $value['enabled'] === '1')) {
                            $enabledAmenitiesCount++;
                        }
                    } else {
                        if ($value === true || $value === 'true' || $value === 1 || $value === '1') {
                            $enabledAmenitiesCount++;
                        }
                    }
                }
            }
            
            $customAmenitiesCount = count(array_filter($customAmenities, function($a) { 
                return !empty($a['name']); 
            }));
            
            $totalAmenities = $enabledAmenitiesCount + $customAmenitiesCount;
            $gatesCount = count(array_filter($gates, function($g) { return !empty($g['name']) || !empty($g['number']); }));
            $areasCount = count(array_filter($additionalAreas, function($a) { return !empty($a['name']); }));

            return view('instituteAdmin.CreateBuildings.buildingsEdit', compact(
                'building',
                'gates',
                'additionalAreas',
                'amenities',
                'customAmenities',
                'facilities',
                'enabledAmenitiesCount',
                'customAmenitiesCount',
                'totalAmenities',
                'gatesCount',
                'areasCount',
                'washrooms',
                'washroomCount',
                'maleWashroomCount',
                'femaleWashroomCount',
                'unisexWashroomCount'
            ));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Building not found');
        } catch (\Exception $e) {
            Log::error('Error loading edit page: ' . $e->getMessage());
            abort(500, 'Error loading edit page');
        }
    }

    /**
     * Display a listing of buildings.
     */
    public function index(): JsonResponse
    {
        try {
            $buildings = AddBuilding::query()
                ->when(request('status'), function ($query, $status) {
                    return $query->where('status', $status);
                })
                ->when(request('institute_id'), function ($query, $instituteId) {
                    return $query->where('institute_id', $instituteId);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(request('per_page', 15));

            $buildings->getCollection()->transform(function ($building) {
                if ($building->photo) {
                    $building->photo_url = Storage::disk('public')->url($building->photo);
                }
                return $building;
            });

            return response()->json([
                'success' => true,
                'data' => $buildings,
                'message' => 'Buildings retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching buildings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve buildings.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified building.
     */
    public function show(string $id): JsonResponse
    {
        Log::info('=== Building Show Method Called ===');
        Log::info('Building ID: ' . $id);
        Log::info('User: ' . (auth()->user() ? auth()->user()->id : 'Not authenticated'));
        
        try {
            $building = AddBuilding::with(['blocks' => function($query) {
                $query->where('status', 'active');
            }])->findOrFail($id);

            Log::info('Building found: ' . $building->name);

            // Decode JSON fields
            $building->additional_areas = $this->decodeField($building->additional_areas);
            $building->gates = $this->decodeField($building->gates);
            $building->amenities = $this->decodeField($building->amenities);
            $building->custom_amenities = $this->decodeField($building->custom_amenities);
            $building->facilities = $this->decodeField($building->facilities);
            $building->allocated_amenities = $this->decodeField($building->allocated_amenities);

            // Get amenities from the amenities table
            $instituteId = auth()->user()->institute_id ?? $building->institute_id;
            $amenitiesData = [];
            
            if ($instituteId && class_exists(\App\Models\Amenity::class)) {
                try {
                    $amenities = Amenity::where('institute_id', $instituteId)
                        ->where('is_active', true)
                        ->withCount('units')
                        ->get();
                    
                    foreach ($amenities as $amenity) {
                        $amenitiesData[$amenity->name] = [
                            'id' => $amenity->id,
                            'amenity_id' => $amenity->amenity_id,
                            'name' => $amenity->name,
                            'category' => $amenity->category ?? 'general',
                            'total_units' => $amenity->units_count ?? 0,
                            'count' => $amenity->count ?? 0,
                        ];
                    }
                    Log::info('Loaded ' . count($amenitiesData) . ' amenities');
                } catch (\Exception $e) {
                    Log::warning('Could not load amenities: ' . $e->getMessage());
                }
            }

            // Get blocks data
            $blocksData = [];
            if ($building->blocks) {
                foreach ($building->blocks as $block) {
                    $blocksData[] = [
                        'id' => $block->id,
                        'name' => $block->name,
                        'code' => $block->code,
                        'description' => $block->description,
                        'status' => $block->status,
                        'total_floors' => $block->total_floors ?? 0,
                        'total_area' => $block->total_area ?? 0,
                        'area_unit' => $block->area_unit ?? 'sq_ft',
                        'additional_areas' => $this->decodeField($block->additional_areas),
                        'gates' => $this->decodeField($block->gates),
                        'custom_amenities' => $this->decodeField($block->custom_amenities),
                        'allocated_amenities' => $this->decodeField($block->allocated_amenities),
                        'allocated_facilities' => $this->decodeField($block->allocated_facilities),
                    ];
                }
            }

            $responseData = [
                'id' => $building->id,
                'name' => $building->name,
                'code' => $building->code,
                'area_value' => $building->area_value ?? 0,
                'area_unit' => $building->area_unit ?? 'sq_ft',
                'number_of_blocks' => $building->number_of_blocks ?? 1,
                'blocks' => $blocksData,
                'additional_areas' => $building->additional_areas ?? [],
                'gates' => $building->gates ?? [],
                'amenities' => $amenitiesData,
                'custom_amenities' => $building->custom_amenities ?? [],
                'facilities' => $building->facilities ?? [],
                'allocated_amenities' => $building->allocated_amenities ?? [],
                'status' => $building->status ?? 'active',
            ];

            Log::info('Building data prepared successfully');

            return response()->json([
                'success' => true,
                'data' => $responseData,
                'message' => 'Building retrieved successfully.'
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Building not found: ' . $id);
            return response()->json([
                'success' => false,
                'message' => 'Building not found.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching building: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve building: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * Store a newly created building.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:add_buildings,code|max:100',
            'institute_id' => 'nullable|string|max:100',
            'branch_id' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'year_established' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'total_floors' => 'nullable|integer|min:0',
            'total_blocks' => 'nullable|integer|min:0',
            'total_rooms' => 'nullable|integer|min:0',
            'total_washrooms' => 'nullable|integer|min:0',
            'status' => 'nullable|in:active,inactive',
            'photo' => 'nullable|string',
            'photo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'area_value' => 'nullable|numeric|min:0',
            'area_unit' => 'nullable|string|max:50',
            'number_of_blocks' => 'nullable|integer|min:1',
            'gates' => 'nullable|json',
            'additional_areas' => 'nullable|json',
            'amenities' => 'nullable|json',
            'custom_amenities' => 'nullable|json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Validation failed.'
            ], 422);
        }

        try {
            $data = $request->all();
            
            // Handle photo upload
            if ($request->has('photo') && $request->photo) {
                $imageData = $request->photo;
                $imageName = 'building_' . time() . '.png';
                $path = 'buildings/' . $imageName;
                
                if (preg_match('/^data:image\/(\w+);base64,/', $imageData)) {
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                    $imageData = base64_decode($imageData);
                    Storage::disk('public')->put($path, $imageData);
                    $data['photo'] = $path;
                }
            } elseif ($request->hasFile('photo_file')) {
                $file = $request->file('photo_file');
                $path = $file->store('buildings', 'public');
                $data['photo'] = $path;
            }

            // CRITICAL: Set institute and branch IDs from authenticated user
            $data['institute_id'] = auth()->user()->institute_id;
            $data['branch_id'] = auth()->user()->branch_id;
            
            // Handle JSON fields
            if ($request->has('gates') && is_string($request->gates)) {
                $data['gates'] = $request->gates;
            }
            if ($request->has('additional_areas') && is_string($request->additional_areas)) {
                $data['additional_areas'] = $request->additional_areas;
            }
            if ($request->has('amenities') && is_string($request->amenities)) {
                $data['amenities'] = $request->amenities;
            }
            if ($request->has('custom_amenities') && is_string($request->custom_amenities)) {
                $data['custom_amenities'] = $request->custom_amenities;
            }
            
            // Store number_of_blocks
            if ($request->has('number_of_blocks')) {
                $data['number_of_blocks'] = $request->number_of_blocks;
            }
            
            $building = AddBuilding::create($data);

            if ($building->photo) {
                $building->photo_url = Storage::disk('public')->url($building->photo);
            }

            return response()->json([
                'success' => true,
                'data' => $building,
                'message' => 'Building created successfully.'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating building: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create building.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    
     /**
     * Update the specified building.
     */
    public function update(Request $request, string $id): JsonResponse
    {

        try {
            Log::info('=== Building Update Method Called ===');
            Log::info('Building ID: ' . $id);
            Log::info('Request data: ', $request->all());

            /*
            * IMPORTANT:
            * $id is the AddBuilding primary key.
            *
            * OLD:
            * AddBuilding::where('institute_id', $id)->first();
            *
            * This was causing $building to become null because
            * route parameter 1 was being treated as institute_id.
            */
            $building = AddBuilding::where('institute_id', $id)
            ->orwhere('id', $id)
            ->first();

            if (!$building) {
                Log::warning('Building not found for ID: ' . $id);

                return response()->json([
                    'success'     => false,
                    'message'     => 'Building not found.',
                    'building_id' => $id,
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name'                  => 'sometimes|required|string|max:255',
                'code'                  => 'nullable|string|max:100',
                'institute_id'          => 'nullable|string|max:100',
                'branch_id'             => 'nullable|string|max:100',
                'address'               => 'nullable|string',
                'description'           => 'nullable|string',
                'year_established'      => [
                    'nullable',
                    'integer',
                    'min:1900',
                    'max:' . (date('Y') + 1),
                ],
                'total_floors'          => 'nullable|integer|min:0',
                'total_blocks'          => 'nullable|integer|min:0',
                'total_rooms'           => 'nullable|integer|min:0',
                'total_washrooms'       => 'nullable|integer|min:0',
                'status'                => 'nullable|in:active,inactive',
                'photo'                 => 'nullable|string',
                'photo_file'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'remove_photo'          => 'nullable|boolean',
                'area_value'            => 'nullable|numeric|min:0',
                'area_unit'             => 'nullable|string|max:50',
                'number_of_blocks'      => 'nullable|integer|min:1',
                'gates'                 => 'nullable|json',
                'additional_areas'      => 'nullable|json',
                'amenities'             => 'nullable|json',
                'custom_amenities'      => 'nullable|json',
                'allocated_amenities'   => 'nullable|json',
                'facilities'            => 'nullable|json',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors(),
                    'message' => 'Validation failed.',
                ], 422);
            }

            /*
            * Only update fields that actually belong to AddBuilding.
            *
            * This also prevents fields such as:
            *  - status-wifi
            *  - count-wifi
            *  - status-cctv
            *  - etc.
            *
            * from accidentally being sent to Eloquent update().
            */
            $allowedFields = [
                'name',
                'code',
                'institute_id',
                'branch_id',
                'address',
                'description',
                'year_established',
                'total_floors',
                'total_blocks',
                'total_rooms',
                'total_washrooms',
                'status',
                'area_value',
                'area_unit',
                'number_of_blocks',
            ];

            $data = $request->only($allowedFields);

            /*
            * Handle JSON fields.
            */
            $jsonFields = [
                'gates',
                'additional_areas',
                'amenities',
                'custom_amenities',
                'allocated_amenities',
                'facilities',
            ];

            foreach ($jsonFields as $field) {
                if ($request->has($field)) {
                    $value = $request->input($field);

                    /*
                    * If the request already contains an array,
                    * convert it to JSON before saving.
                    */
                    if (is_array($value)) {
                        $data[$field] = json_encode($value);
                    }
                    /*
                    * If the request contains a JSON string,
                    * keep it as JSON.
                    */
                    elseif (is_string($value)) {
                        $data[$field] = $value;
                    }
                }
            }

            /*
            * ==========================================
            * HANDLE PHOTO REMOVAL
            * ==========================================
            */
            if ($request->boolean('remove_photo')) {
                if (!empty($building->photo)) {
                    if (Storage::disk('public')->exists($building->photo)) {
                        Storage::disk('public')->delete($building->photo);
                    }
                }
                $data['photo'] = null;
            }
            /*
            * ==========================================
            * HANDLE BASE64 PHOTO
            * ==========================================
            */
            elseif ($request->filled('photo')) {
                $imageData = $request->input('photo');

                /*
                * Only process the value if it is a base64 image.
                */
                if (preg_match(
                    '/^data:image\/([a-zA-Z0-9]+);base64,/',
                    $imageData,
                    $matches
                )) {
                    /*
                    * Delete old photo.
                    */
                    if (!empty($building->photo)) {
                        if (Storage::disk('public')->exists($building->photo)) {
                            Storage::disk('public')->delete($building->photo);
                        }
                    }

                    $extension = strtolower($matches[1]);

                    /*
                    * Normalize jpeg extension.
                    */
                    if ($extension === 'jpeg') {
                        $extension = 'jpg';
                    }

                    $imageData = substr(
                        $imageData,
                        strpos($imageData, ',') + 1
                    );

                    $decodedImage = base64_decode($imageData);

                    if ($decodedImage === false) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid base64 image data.',
                        ], 422);
                    }

                    $imageName = 'building_' . time() . '_' . uniqid() . '.' . $extension;
                    $path = 'buildings/' . $imageName;

                    Storage::disk('public')->put(
                        $path,
                        $decodedImage
                    );

                    $data['photo'] = $path;
                }
            }
            /*
            * ==========================================
            * HANDLE NORMAL FILE UPLOAD
            * ==========================================
            */
            elseif ($request->hasFile('photo_file')) {
                /*
                * Delete old photo.
                */
                if (!empty($building->photo)) {
                    if (Storage::disk('public')->exists($building->photo)) {
                        Storage::disk('public')->delete($building->photo);
                    }
                }

                $file = $request->file('photo_file');
                $path = $file->store(
                    'buildings',
                    'public'
                );

                $data['photo'] = $path;
            }

            /*
            * ==========================================
            * UPDATE BUILDING
            * ==========================================
            */
            $building->update($data);

            /*
            * Refresh model from database.
            */
            $building->refresh();

            /*
            * Add photo URL to response.
            */
            if (!empty($building->photo)) {
                $building->photo_url = Storage::disk('public')
                    ->url($building->photo);
            } else {
                $building->photo_url = null;
            }

            Log::info(
                'Building updated successfully. Building ID: ' . $building->id
            );

            return response()->json([
                'success' => true,
                'data'    => $building,
                'message' => 'Building updated successfully.',
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error(
                'Database error while updating building: ' . $e->getMessage()
            );
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Database error while updating building.',
                'error'   => $e->getMessage(),
            ], 500);

        } catch (\Exception $e) {
            Log::error(
                'Error updating building: ' . $e->getMessage()
            );
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update building.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Remove the specified building (soft delete).
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $building = AddBuilding::findOrFail($id);
            $building->delete();

            return response()->json([
                'success' => true,
                'message' => 'Building deleted successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Building not found.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting building: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete building.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore a soft-deleted building.
     */
    public function restore(string $id): JsonResponse
    {
        try {
            $building = AddBuilding::withTrashed()->findOrFail($id);
            
            if (!$building->trashed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Building is not deleted.'
                ], 400);
            }

            $building->restore();

            return response()->json([
                'success' => true,
                'data' => $building,
                'message' => 'Building restored successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Building not found.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error restoring building: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore building.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Permanently delete a building.
     */
    public function forceDelete(string $id): JsonResponse
    {
        try {
            $building = AddBuilding::withTrashed()->findOrFail($id);
            
            if ($building->photo) {
                Storage::disk('public')->delete($building->photo);
            }
            
            $building->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Building permanently deleted.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Building not found.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error force deleting building: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to permanently delete building.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get building statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $totalBuildings = AddBuilding::count();
            $activeBuildings = AddBuilding::where('status', 'active')->count();
            $inactiveBuildings = AddBuilding::where('status', 'inactive')->count();
            
            $totalRooms = AddBuilding::sum('total_rooms');
            $totalFloors = AddBuilding::sum('total_floors');
            $totalBlocks = AddBuilding::sum('total_blocks');
            $totalWashrooms = AddBuilding::sum('total_washrooms');

            return response()->json([
                'success' => true,
                'data' => [
                    'total_buildings' => $totalBuildings,
                    'active_buildings' => $activeBuildings,
                    'inactive_buildings' => $inactiveBuildings,
                    'total_rooms' => $totalRooms,
                    'total_floors' => $totalFloors,
                    'total_blocks' => $totalBlocks,
                    'total_washrooms' => $totalWashrooms,
                ],
                'message' => 'Building statistics retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching statistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search buildings by name or code.
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $searchTerm = $request->input('search', '');
            
            $buildings = AddBuilding::query()
                ->where(function($query) use ($searchTerm) {
                    $query->where('name', 'like', "%{$searchTerm}%")
                          ->orWhere('code', 'like', "%{$searchTerm}%");
                })
                ->orderBy('name')
                ->get()
                ->map(function($building) {
                    if ($building->photo) {
                        $building->photo_url = Storage::disk('public')->url($building->photo);
                    }
                    return $building;
                });

            return response()->json([
                'success' => true,
                'data' => $buildings,
                'message' => 'Buildings retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error searching buildings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to search buildings.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all buildings (for dropdowns and filters).
     */
    public function getAllBuildings(): JsonResponse
    {
        try {
            Log::info('Fetching all buildings for dropdown');
            
            $buildings = AddBuilding::orderBy('name')
                ->get(['id', 'name', 'code', 'status']);
            
            Log::info('All buildings fetched:', [
                'count' => $buildings->count(),
                'buildings' => $buildings->toArray()
            ]);
            
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
            Log::error('Error fetching buildings:', [
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
     * Sync campus data to AddBuilding table.
     */
    public function syncCampusToBuilding(Request $request): JsonResponse
    {
        try {
            $merchantId = auth()->user()->institute_id;
            $campus = InstituteBasicDetails::where('fincap_merchant_id', $merchantId)->first();
            
            if (!$campus) {
                return response()->json([
                    'success' => false,
                    'message' => 'Campus not found.'
                ], 404);
            }

            $existingBuilding = AddBuilding::where('name', $request->name ?? $campus->name)
                ->where('institute_id', $merchantId)
                ->first();

            $buildingData = [
                'name' => $request->name ?? $campus->name,
                'code' => $request->code ?? $campus->fincap_merchant_id,
                'institute_id' => $merchantId,
                'branch_id' => auth()->user()->branch_id,
                'address' => $campus->address_line_1 ?? null,
                'area_value' => $request->area_value ?? 0.00,
                'area_unit' => $request->area_unit ?? 'sq_ft',
                'number_of_blocks' => $request->number_of_blocks ?? 1,
                'description' => $request->description ?? null,
                'additional_areas' => $request->additional_areas ?? null,
                'gates' => $request->gates ?? null,
                'amenities' => $request->amenities ?? null,
                'custom_amenities' => $request->custom_amenities ?? null,
                'facilities' => $request->facilities ?? null,
                'status' => 'active',
            ];

            if ($existingBuilding) {
                $existingBuilding->update($buildingData);
                $building = $existingBuilding->fresh();
                $message = 'Building updated successfully from campus data.';
            } else {
                $building = AddBuilding::create($buildingData);
                $message = 'Building created successfully from campus data.';
            }

            return response()->json([
                'success' => true,
                'data' => $building,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error syncing campus to building: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync campus to building.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Campus Infrastructure page.
     */
    public function infrastructure(Request $request)
    {
        try {
            $campusId = auth()->user()->institute_id;
            $campus = InstituteBasicDetails::where('fincap_merchant_id', $campusId)->firstOrFail();
            
            // Get or create building
            $building = AddBuilding::where('institute_id', $campusId)->first();
            if (!$building) {
                $building = AddBuilding::create([
                    'name' => $campus->name ?? 'Main Campus',
                    'code' => $campus->fincap_merchant_id,
                    'institute_id' => $campusId,
                    'branch_id' => auth()->user()->branch_id,
                    'status' => 'active',
                    'area_value' => 0.00,
                ]);
            }
            
            // Pass the request to syncCampusToBuilding
            $this->syncCampusToBuilding($request);
            
            return view('instituteAdmin.CreateBuildings.buildingManagementInfrastructure', compact('campus'));
            
        } catch (\Exception $e) {
            Log::error('Error in infrastructure page: ' . $e->getMessage());
            abort(500, 'Error loading infrastructure page');
        }
    }

    /**
     * Blocks Setup page.
     */
    public function blocksSetup($campusId)
    {
        try {
            $campus = AddBuilding::where('institute_id', $campusId)->firstOrFail();
            $buildings = AddBuilding::where('institute_id', $campusId)->get();
            
            // Get blocks for the campus
            $blocks = AddBlock::where('building_id', $campus->id)
                ->where('status', 'active')
                ->get();
            
            // Get amenities from database
            $amenitiesData = [];
            if (class_exists(\App\Models\Amenity::class)) {
                try {
                    $amenities = Amenity::where('institute_id', $campusId)
                        ->where('is_active', true)
                        ->where('count', '>', 0)
                        ->withCount('units')
                        ->get();
                   
                    foreach ($amenities as $amenity) {
                        $amenitiesData[$amenity->name] = [
                            'id' => $amenity->id,
                            'amenity_id' => $amenity->amenity_id,
                            'name' => $amenity->name,
                            'category' => $amenity->category ?? 'general',
                            'total_units' => $amenity->units_count ?? 0,
                            'count' => $amenity->count ?? 0,
                        ];
                    }
                } catch (\Exception $e) {
                    Log::warning('Could not load amenities: ' . $e->getMessage());
                }
            }
            
            $campusData = [
                'id' => $campus->id,
                'name' => $campus->name,
                'code' => $campus->code,
                'area_value' => $campus->area_value ?? 0,
                'area_unit' => $campus->area_unit ?? 'sq_ft',
                'number_of_blocks' => $campus->number_of_blocks ?? 1,
                'blocks' => $blocks->toArray(),
                'additional_areas' => $campus->additional_areas ?? [],
                'gates' => $campus->gates ?? [],
                'amenities' => $amenitiesData,
                'custom_amenities' => $campus->custom_amenities ?? [],
                'facilities' => $campus->facilities ?? [],
                'allocated_amenities' => $campus->allocated_amenities ?? [],
            ];

            return view('instituteAdmin.CreateBuildings.buildingManagementBuildings', 
                compact('campus', 'buildings', 'blocks', 'campusData')
            );
            
        } catch (\Exception $e) {
            Log::error('Error in blocksSetup: ' . $e->getMessage());
            abort(500, 'Error loading blocks setup page');
        }
    }

    /**
     * Helper function to decode JSON fields.
     */
    private function decodeField($field)
    {
        if (is_null($field)) {
            return [];
        }
        if (is_array($field) || is_object($field)) {
            return (array) $field;
        }
        if (is_string($field)) {
            $decoded = json_decode($field, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded ?? [];
            }
            return [];
        }
        return [];
    }
}