<?php

namespace App\Http\Controllers\institute\Admin\BuildingManagementController;

use App\Http\Controllers\Controller;
use App\Models\AddBlock;
use App\Models\AddBuilding;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AddBlockController extends Controller
{
    /**
     * Display the blocks management page.
     */
    public function page()
    {
        $buildings = AddBuilding::where('status', 'active')
            ->orderBy('name')
            ->get();
        
        return view('instituteAdmin.CreateBuildings.buildingManagementBlock', compact('buildings'));
    }

    /**
     * Display a listing of blocks.
     */
    public function index(Request $request): JsonResponse
    {
        $blocks = AddBlock::with(['building' => function($query) {
                $query->select('id', 'name', 'code');
            }])
            ->when($request->filled('building_id'), function ($query) use ($request) {
                return $query->where('building_id', $request->building_id);
            })
            ->when($request->filled('institute_id'), function ($query) use ($request) {
                return $query->where('institute_id', $request->institute_id);
            })
            ->when($request->filled('branch_id'), function ($query) use ($request) {
                return $query->where('branch_id', $request->branch_id);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->filled('has_lift'), function ($query) use ($request) {
                return $query->where('has_lift', $request->boolean('has_lift'));
            })
            ->when($request->filled('has_fire_safety'), function ($query) use ($request) {
                return $query->where('has_fire_safety', $request->boolean('has_fire_safety'));
            })
            ->when($request->filled('has_disabled_access'), function ($query) use ($request) {
                return $query->where('has_disabled_access', $request->boolean('has_disabled_access'));
            })
            ->when($request->filled('has_security_system'), function ($query) use ($request) {
                return $query->where('has_security_system', $request->boolean('has_security_system'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);
       
        return response()->json([
            'success' => true,
            'data' => $blocks,
            'message' => 'Blocks retrieved successfully.'
        ]);
    }

    /**
     * Store a newly created block.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $blocks = $request->input('blocks');
            if (is_string($blocks)) {
                $blocks = json_decode($blocks, true);
            }

            if (!is_array($blocks) || empty($blocks)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid blocks data.'
                ], 422);
            }

            $buildingId = $request->input('building_id');
            if (!$buildingId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Building ID is required.'
                ], 422);
            }

            // Get existing block names for this building
            $existingNames = AddBlock::where('building_id', $buildingId)
                ->pluck('name')
                ->toArray();
            
            $createdBlocks = [];
            $processedNames = [];

            foreach ($blocks as $blockData) {
                $nameToUse = $blockData['name'] ?? 'Block';
                $nameToUse = trim($nameToUse);
                
                // If this name already exists in the database OR has been used in this request
                if (in_array($nameToUse, $existingNames) || in_array($nameToUse, $processedNames)) {
                    // Try to find a unique name by appending a number
                    $baseName = $nameToUse;
                    $counter = 1;
                    
                    // Check if the name already has a number at the end
                    if (preg_match('/^(.*?)(?:\s+(\d+))?$/', $nameToUse, $matches)) {
                        $baseName = $matches[1] ?? $nameToUse;
                        $counter = isset($matches[2]) ? (int)$matches[2] : 1;
                    }
                    
                    // Find the next available number
                    do {
                        $candidateName = trim($baseName) . ' ' . $counter;
                        $counter++;
                    } while (in_array($candidateName, $existingNames) || in_array($candidateName, $processedNames));
                    
                    $nameToUse = $candidateName;
                }
                
                $processedNames[] = $nameToUse;

                // Check if code already exists
                if (!empty($blockData['code'])) {
                    $codeExists = AddBlock::where('code', $blockData['code'])->exists();
                    if ($codeExists) {
                        // Append a number to make it unique
                        $baseCode = $blockData['code'];
                        $counter = 1;
                        do {
                            $candidateCode = $baseCode . '-' . $counter;
                            $counter++;
                        } while (AddBlock::where('code', $candidateCode)->exists());
                        $blockData['code'] = $candidateCode;
                    }
                }

                // Build creation data
                $createData = [
                    'building_id'          => $buildingId,
                    'institute_id'         => auth()->user()->institute_id ?? null,
                    'branch_id'            => auth()->user()->branch_id ?? null,
                    'name'                 => $nameToUse,
                    'code'                 => $blockData['code'] ?? null,
                    'description'          => $blockData['description'] ?? null,
                    'status'               => $blockData['status'] ?? 'active',
                    'total_floors'         => $blockData['floors'] ?? 0,
                    'total_area'           => $blockData['area_value'] ?? 0,
                    'area_unit'            => $blockData['area_unit'] ?? 'sq_ft',
                    'additional_areas'     => $blockData['additional_areas'] ?? [],
                    'gates'                => $blockData['gates'] ?? [],
                    'custom_amenities'     => $blockData['custom_amenities'] ?? [],
                    'allocated_amenities'  => $blockData['allocated_amenities'] ?? [],
                    'allocated_facilities' => $blockData['allocated_facility_entries'] ?? [],
                ];

                $block = AddBlock::create($createData);
                $block->load('building');
                $createdBlocks[] = $block;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Blocks created successfully.',
                'data'    => $createdBlocks,
                'names_used' => $processedNames
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create blocks: ' . $e->getMessage(),
                'error'   => $e->getMessage(),
                'line'    => $e->getLine()
            ], 500);
        }
    }

    /**
     * Display the specified block.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $block = AddBlock::with(['building' => function($query) {
                $query->select('id', 'name', 'code');
            }])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $block,
                'message' => 'Block retrieved successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Block not found.'
            ], 404);
        }
    }

    /**
     * Update the specified block.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $block = AddBlock::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'building_id' => 'sometimes|required|exists:buildings_page,id',
                'code' => 'nullable|string|max:100|unique:add_blocks,code,' . $id,
                'institute_id' => 'nullable|string|max:100',
                'branch_id' => 'nullable|string|max:100',
                'description' => 'nullable|string',
                'total_floors' => 'nullable|integer|min:0',
                'total_rooms' => 'nullable|integer|min:0',
                'total_capacity' => 'nullable|integer|min:0',
                'total_washrooms' => 'nullable|integer|min:0',
                'has_lift' => 'nullable|boolean',
                'lift_count' => 'nullable|integer|min:0',
                'has_fire_safety' => 'nullable|boolean',
                'has_disabled_access' => 'nullable|boolean',
                'has_security_system' => 'nullable|boolean',
                'total_area' => 'nullable|numeric|min:0',
                'status' => 'nullable|in:active,inactive,under_maintenance',
                'allocated_amenities' => 'nullable|json',
                'allocated_facilities' => 'nullable|json',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed.'
                ], 422);
            }

            // Check unique name within building (only if name is being changed)
            if ($request->has('name') || $request->has('building_id')) {
                $buildingId = $request->building_id ?? $block->building_id;
                $name = $request->name ?? $block->name;

                $existingBlock = AddBlock::where('building_id', $buildingId)
                    ->where('name', $name)
                    ->where('id', '!=', $id)
                    ->first();

                if ($existingBlock) {
                    return response()->json([
                        'success' => false,
                        'message' => 'A block with this name already exists in the selected building.'
                    ], 422);
                }
            }

            $data = $request->all();
            $block->update($data);
            $block->load('building');

            return response()->json([
                'success' => true,
                'data' => $block,
                'message' => 'Block updated successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Block not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update block.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified block (soft delete).
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $block = AddBlock::findOrFail($id);
            $block->delete();

            return response()->json([
                'success' => true,
                'message' => 'Block deleted successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Block not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete block.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore a soft-deleted block.
     */
    public function restore(string $id): JsonResponse
    {
        try {
            $block = AddBlock::withTrashed()->findOrFail($id);
            
            if (!$block->trashed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Block is not deleted.'
                ], 400);
            }

            $block->restore();

            return response()->json([
                'success' => true,
                'data' => $block,
                'message' => 'Block restored successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Block not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore block.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get block statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = AddBlock::query();

        if ($request->filled('building_id')) {
            $query->where('building_id', $request->building_id);
        }

        if ($request->filled('institute_id')) {
            $query->where('institute_id', $request->institute_id);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        $totalBlocks = $query->count();
        $activeBlocks = $query->clone()->where('status', 'active')->count();
        $inactiveBlocks = $query->clone()->where('status', 'inactive')->count();
        $underMaintenanceBlocks = $query->clone()->where('status', 'under_maintenance')->count();
        
        $totalRooms = $query->clone()->sum('total_rooms');
        $totalFloors = $query->clone()->sum('total_floors');
        $totalCapacity = $query->clone()->sum('total_capacity');
        $totalWashrooms = $query->clone()->sum('total_washrooms');
        $totalArea = $query->clone()->sum('total_area');
        
        $blocksWithLift = $query->clone()->where('has_lift', true)->count();
        $blocksWithFireSafety = $query->clone()->where('has_fire_safety', true)->count();
        $blocksWithDisabledAccess = $query->clone()->where('has_disabled_access', true)->count();
        $blocksWithSecuritySystem = $query->clone()->where('has_security_system', true)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_blocks' => $totalBlocks,
                'active_blocks' => $activeBlocks,
                'inactive_blocks' => $inactiveBlocks,
                'under_maintenance_blocks' => $underMaintenanceBlocks,
                'total_rooms' => $totalRooms,
                'total_floors' => $totalFloors,
                'total_capacity' => $totalCapacity,
                'total_washrooms' => $totalWashrooms,
                'total_area' => $totalArea,
                'blocks_with_lift' => $blocksWithLift,
                'blocks_with_fire_safety' => $blocksWithFireSafety,
                'blocks_with_disabled_access' => $blocksWithDisabledAccess,
                'blocks_with_security_system' => $blocksWithSecuritySystem,
                'average_rooms_per_block' => $totalBlocks > 0 ? round($totalRooms / $totalBlocks, 2) : 0,
                'average_capacity_per_block' => $totalBlocks > 0 ? round($totalCapacity / $totalBlocks, 2) : 0,
            ],
            'message' => 'Block statistics retrieved successfully.'
        ]);
    }

    /**
     * Get blocks by building ID.
     */
    public function getByBuilding(string $buildingId): JsonResponse
    {
        try {
            $blocks = AddBlock::where('building_id', $buildingId)
                ->where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name', 'code', 'description']);

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
     * Update block status.
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
            $block = AddBlock::findOrFail($id);
            $block->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'data' => $block,
                'message' => 'Block status updated successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Block not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update block status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search blocks by name or code.
     */
    public function search(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search', '');
        
        $blocks = AddBlock::with(['building' => function($query) {
                $query->select('id', 'name', 'code');
            }])
            ->where(function($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%")
                      ->orWhere('code', 'like', "%{$searchTerm}%")
                      ->orWhereHas('building', function($q) use ($searchTerm) {
                          $q->where('name', 'like', "%{$searchTerm}%")
                            ->orWhere('code', 'like', "%{$searchTerm}%");
                      });
            })
            ->orderBy('name')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $blocks,
            'message' => 'Blocks retrieved successfully.'
        ]);
    }

    /**
     * Get block details with allocations for floor setup.
     */
    public function getBlockWithAllocations(string $id): JsonResponse
    {
        try {
            $block = AddBlock::with(['building' => function($query) {
                $query->select('id', 'name', 'code');
            }])->findOrFail($id);

            $allocatedAmenities = $block->allocated_amenities ?? [];
            $allocatedFacilities = $block->allocated_facilities ?? [];
            $floors = (int) $block->total_floors ?? 1;

            $response = [
                'id' => $block->id,
                'name' => $block->name,
                'code' => $block->code,
                'description' => $block->description,
                'status' => $block->status,
                'floors' => $floors,
                'building_id' => $block->building_id,
                'building' => $block->building,
                'allocated_amenities' => $allocatedAmenities,
                'allocated_facility_entries' => $allocatedFacilities,
                'additional_areas' => $block->additional_areas ?? [],
                'gates' => $block->gates ?? [],
                'custom_amenities' => $block->custom_amenities ?? [],
                'area_value' => $block->total_area ?? 0,
                'area_unit' => $block->area_unit ?? 'sq_ft',
                'total_floors' => $floors,
            ];

            return response()->json([
                'success' => true,
                'data' => $response,
                'message' => 'Block details retrieved successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Block not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve block details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Display the block details page.
     */
    public function showDetails(string $id)
    {
        try {
            $block = AddBlock::with(['building' => function($query) {
                $query->select('id', 'name', 'code');
            }])->findOrFail($id);

            // Helper function to safely get data (handles both string JSON and array)
            $getData = function($field) use ($block) {
                $value = $block->$field;
                
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
            $additionalAreas = $getData('additional_areas');
            $gates = $getData('gates');
            $customAmenities = $getData('custom_amenities');
            $allocatedAmenities = $getData('allocated_amenities');
            $allocatedFacilities = $getData('allocated_facilities');

            // Calculate counts
            $gatesCount = count(array_filter($gates, function($g) { 
                return !empty($g['name']) || !empty($g['number']); 
            }));
            
            $areasCount = count(array_filter($additionalAreas, function($a) { 
                return !empty($a['name']); 
            }));
            
            $amenitiesCount = count($allocatedAmenities);
            $facilitiesCount = count($allocatedFacilities);
            $customAmenitiesCount = count(array_filter($customAmenities, function($a) { 
                return !empty($a['name']); 
            }));
            
            $totalAmenities = $amenitiesCount + $customAmenitiesCount;

            // Get features as boolean values
            $hasLift = (bool) ($block->has_lift ?? false);
            $hasFireSafety = (bool) ($block->has_fire_safety ?? false);
            $hasDisabledAccess = (bool) ($block->has_disabled_access ?? false);
            $hasSecuritySystem = (bool) ($block->has_security_system ?? false);

            return view('instituteAdmin.CreateBuildings.blocksView', compact(
                'block',
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
                'totalAmenities',
                'hasLift',
                'hasFireSafety',
                'hasDisabledAccess',
                'hasSecuritySystem'
            ));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Block not found');
        } catch (\Exception $e) {
            Log::error('Error loading block details: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            abort(500, 'Error loading block details');
        }
    }
      /**
     * Display the block edit page.
     */
    public function edit(string $id)
    {
        try {
            $block = AddBlock::findOrFail($id);

            // ----------------------------------------------------------
            // Load the building relation
            // ----------------------------------------------------------
            $building = AddBuilding::find($block->building_id);

            // ----------------------------------------------------------
            // Load the campus (the "owner" of the facilities JSON)
            // ----------------------------------------------------------
            // Try in order:
            //   1. The building itself (AddBuilding row)
            //   2. Any AddBuilding for this institute
            //   3. The first AddBuilding row (single-campus installs)
            // ----------------------------------------------------------
            $merchantId = auth()->user()->institute_id ?? null;

            $campusRecord = null;
            if ($building && !empty($building->facilities)) {
                $campusRecord = $building;
            } elseif ($merchantId) {
                $campusRecord = AddBuilding::where('institute_id', $merchantId)
                    ->whereNotNull('facilities')
                    ->first();
            }
            if (!$campusRecord) {
                $campusRecord = AddBuilding::whereNotNull('facilities')->first();
            }
            if (!$campusRecord) {
                $campusRecord = AddBuilding::first();
            }

            // ----------------------------------------------------------
            // Decode the campus facilities JSON
            // ----------------------------------------------------------
            $campusFacilities = [];
            if ($campusRecord && !empty($campusRecord->facilities)) {
                $raw = $campusRecord->facilities;
                if (is_string($raw)) {
                    $decoded = json_decode($raw, true);
                    $campusFacilities = is_array($decoded) ? $decoded : [];
                } elseif (is_array($raw)) {
                    $campusFacilities = $raw;
                }
            }

            \Log::info('=== Block Edit: Facility Load ===', [
                'block_id'            => $block->id,
                'block_building_id'   => $block->building_id,
                'building_found'      => $building ? $building->id : null,
                'campus_record_id'    => $campusRecord ? $campusRecord->id : null,
                'facilities_count'    => count($campusFacilities),
                'facility_types'      => array_keys($campusFacilities),
            ]);

            // ----------------------------------------------------------
            // Attach resolved facilities to the block for the view
            // ----------------------------------------------------------
            $block->building = $building;
            $block->campus_facilities = $campusFacilities;

            // ----------------------------------------------------------
            // Load buildings for the dropdown
            // ----------------------------------------------------------
            $buildings = AddBuilding::where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name', 'code']);

            return view(
                'instituteAdmin.CreateBuildings.blocksEdit',
                compact('block', 'buildings', 'campusFacilities')
            );

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Block not found');
        } catch (\Exception $e) {
            \Log::error('Error loading block edit page: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            abort(500, 'Error loading block edit page: ' . $e->getMessage());
        }
    }
}