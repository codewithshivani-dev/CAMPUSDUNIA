<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\AmenityUnit;
use App\Models\AddBuilding;
use App\Models\AddBlock;
use App\Models\AddFloor;
use App\Models\Asset;
use App\Models\AddRooms;
use App\Models\AssetCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\AmenityAssignment;

class AmenitiesController extends Controller
{
    /**
     * Display a listing of amenities.
     */
    public function index()
    {
        $instituteId = auth()->user()->institute_id;
        
        $amenities = Amenity::where('institute_id', $instituteId)
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();
        
        return view('instituteAdmin.CreateBuildings.amenities', compact('amenities'));
    }

   
     public function getAmenitiesData(): JsonResponse
{
    $instituteId = auth()->user()->institute_id;

    $categories = AssetCategory::with('assets')->get();

    $existingAmenityIds = Amenity::where('institute_id', $instituteId)
        ->where('is_active', true)
        ->pluck('asset_id')
        ->toArray();

    $data = [];

    foreach ($categories as $category) {

        $assets = [];

        foreach ($category->assets as $asset) {

            /*
            |--------------------------------------------------------------------------
            | Specifications
            |--------------------------------------------------------------------------
            | Asset model may already cast specifications to array.
            | Therefore, only json_decode when the value is a string.
            |--------------------------------------------------------------------------
            */

            $specs = $asset->specifications;

            if (is_string($specs)) {
                $specs = json_decode($specs, true);
            }

            if (!is_array($specs)) {
                $specs = [];
            }

            /*
            |--------------------------------------------------------------------------
            | Remove empty specification values
            |--------------------------------------------------------------------------
            */

            $specs = array_filter($specs, function ($value) {
                return $value !== null
                    && $value !== ''
                    && $value !== 'null';
            });

            /*
            |--------------------------------------------------------------------------
            | Check whether this asset is already selected
            |--------------------------------------------------------------------------
            */

            $isSelected = in_array(
                $asset->asset_id,
                $existingAmenityIds
            );

            $assets[] = [
                'id' => $asset->id,
                'asset_id' => $asset->asset_id,
                'asset_name' => $asset->asset_name,
                'specifications' => $specs,
                'created_at' => $asset->created_at,
                'updated_at' => $asset->updated_at,
                'is_selected' => $isSelected,
            ];
        }

        $data[] = [
            'id' => $category->id,
            'category_id' => $category->category_id,
            'name' => $category->name,
            'assets' => $assets,
        ];
    }

    return response()->json([
        'success' => true,
        'data' => $data,
        'selected_amenities' => $existingAmenityIds,
        'total_categories' => count($data),
        'total_amenities' => collect($data)->sum(function ($cat) {
            return count($cat['assets']);
        }),
        'total_selected' => count($existingAmenityIds),
    ]);
}

    public function getAmenities($buildingId = null): JsonResponse
    {
        try {
            $instituteId = auth()->user()->institute_id;
            
            $query = Amenity::where('institute_id', $instituteId)
                ->where('is_active', true)
                ->where('count', '>', 0)
                ->withCount(['units' => function($q) {
                    $q->where('status', 'available');
                }]);
            
            if ($buildingId) {
                $building = AddBuilding::where('id', $buildingId)
                    ->where('institute_id', $instituteId)
                    ->first();
                
                if ($building && $building->allocated_amenities) {
                    $allocatedAmenities = is_string($building->allocated_amenities) 
                        ? json_decode($building->allocated_amenities, true) 
                        : $building->allocated_amenities;
                    
                    if (!empty($allocatedAmenities)) {
                        $amenityNames = array_keys($allocatedAmenities);
                        $query->whereIn('name', $amenityNames);
                    }
                }
            }
            
            $amenities = $query->orderBy('name', 'asc')->get();
            
            $amenities = $amenities->filter(function($amenity) {
                return $amenity->units_count > 0;
            })->values();
            
            return response()->json([
                'success' => true,
                'data' => $amenities,
                'total' => $amenities->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching amenities: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch amenities.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function saveAmenities(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amenities' => 'required|array',
            'amenities.*' => 'string|exists:assets,asset_id'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            $instituteId = auth()->user()->institute_id;
            $selectedAssetIds = $request->amenities;
            
            $existingAmenityIds = Amenity::where('institute_id', $instituteId)
                ->pluck('asset_id')
                ->toArray();
            
            $toRemove = array_diff($existingAmenityIds, $selectedAssetIds);
            
            if (!empty($toRemove)) {
                Amenity::where('institute_id', $instituteId)
                    ->whereIn('asset_id', $toRemove)
                    ->update(['is_active' => false]);
            }
            
            foreach ($selectedAssetIds as $assetId) {
                $asset = Asset::where('asset_id', $assetId)->first();
                if (!$asset) continue;
                
                $category = AssetCategory::where('category_id', $asset->category_id)->first();
                
                $amenityId = 'AMN-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                while (Amenity::where('amenity_id', $amenityId)->exists()) {
                    $amenityId = 'AMN-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                }
                
                $specs = json_decode($asset->specifications, true);
                if (!is_array($specs)) {
                    $specs = [];
                }
                
                $existingAmenity = Amenity::where('asset_id', $assetId)
                    ->where('institute_id', $instituteId)
                    ->first();
                
                if ($existingAmenity) {
                    $existingAmenity->update([
                        'name' => $asset->asset_name,
                        'category_id' => $asset->category_id,
                        'category_name' => $category ? $category->name : null,
                        'specifications' => json_encode($specs),
                        'is_active' => true,
                        'updated_at' => now(),
                    ]);
                } else {
                    Amenity::create([
                        'amenity_id' => $amenityId,
                        'asset_id' => $asset->asset_id,
                        'institute_id' => $instituteId,
                        'name' => $asset->asset_name,
                        'category_id' => $asset->category_id,
                        'category_name' => $category ? $category->name : null,
                        'specifications' => json_encode($specs),
                        'is_active' => true,
                        'count' => 1,
                    ]);
                }
            }
            
            DB::commit();
            
            $activeCount = Amenity::where('institute_id', $instituteId)
                ->where('is_active', true)
                ->count();
            
            return response()->json([
                'success' => true,
                'message' => 'Amenities saved successfully.',
                'selected_count' => count($selectedAssetIds),
                'total_active' => $activeCount,
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving amenities: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to save amenities.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Sync selected amenities from category page (ADD ONLY - no removal)
     */
    public function syncAmenities(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'asset_ids' => 'required|array',
                'asset_ids.*' => 'string|exists:assets,asset_id'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $instituteId = auth()->user()->institute_id;
            $selectedAssetIds = $request->asset_ids;
            
            $addedCount = 0;
            $skippedCount = 0;
            
            foreach ($selectedAssetIds as $assetId) {
                $existingAmenity = Amenity::where('asset_id', $assetId)
                    ->where('institute_id', $instituteId)
                    ->first();
                
                if ($existingAmenity) {
                    if (!$existingAmenity->is_active) {
                        $existingAmenity->is_active = true;
                        $existingAmenity->save();
                        $addedCount++;
                    } else {
                        $skippedCount++;
                    }
                    continue;
                }
                
                $asset = Asset::where('asset_id', $assetId)->first();
                if (!$asset) continue;
                
                $category = AssetCategory::where('category_id', $asset->category_id)->first();
                
                $amenityId = 'AMN-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                while (Amenity::where('amenity_id', $amenityId)->exists()) {
                    $amenityId = 'AMN-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                }
                
                $specs = json_decode($asset->specifications, true);
                if (!is_array($specs)) {
                    $specs = [];
                }
                
                Amenity::create([
                    'amenity_id' => $amenityId,
                    'asset_id' => $asset->asset_id,
                    'institute_id' => $instituteId,
                    'name' => $asset->asset_name,
                    'category_id' => $asset->category_id,
                    'category_name' => $category ? $category->name : null,
                    'specifications' => json_encode($specs),
                    'is_active' => true,
                    'count' => 1,
                ]);
                $addedCount++;
            }
            
            $totalActive = Amenity::where('institute_id', $instituteId)
                ->where('is_active', true)
                ->count();
            
            $message = $addedCount > 0 
                ? $addedCount . ' new amenity(ies) added successfully.' 
                : 'All selected assets are already in your amenities.';
            
            if ($skippedCount > 0) {
                $message .= ' (' . $skippedCount . ' already existed)';
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'added_count' => $addedCount,
                'skipped_count' => $skippedCount,
                'total_active' => $totalActive,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error syncing amenities: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to sync amenities.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function showSelectedAmenities()
    {
        $instituteId = auth()->user()->institute_id;
        
        $amenities = Amenity::where('institute_id', $instituteId)
            ->where('is_active', true)
            ->where('count', '>', 0)
            ->orderBy('name', 'asc')
            ->get();
        
        return view('instituteAdmin.Assets.amenities-selection', compact('amenities'));
    }

    public function getSelectedAmenitiesData(): JsonResponse
    {
        try {
            $instituteId = auth()->user()->institute_id;
            
            $amenities = Amenity::where('institute_id', $instituteId)
                ->where('is_active', true)
                ->where('count', '>', 0)
                ->withCount('units')
                ->orderBy('name', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $amenities
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching selected amenities: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch selected amenities.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function removeSelectedAmenity($amenityId): JsonResponse
    {
        try {
            $instituteId = auth()->user()->institute_id;
            
            $amenity = Amenity::where('amenity_id', $amenityId)
                ->where('institute_id', $instituteId)
                ->first();
            
            if (!$amenity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Amenity not found.'
                ], 404);
            }
            
            $amenity->is_active = false;
            $amenity->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Amenity removed successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing amenity: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove amenity.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

         /**
 * Show assets for a specific category on a fresh page
 */
public function showCategoryAssets($categoryId)
{
    try {
        $category = AssetCategory::with('assets')->findOrFail($categoryId);

        $assets = $category->assets()
            ->orderBy('asset_name', 'asc')
            ->get();

        $assets = $assets->map(function ($asset) {

            /*
            |--------------------------------------------------------------------------
            | Specifications
            |--------------------------------------------------------------------------
            | Laravel may already cast specifications to an array.
            | Only decode when the value is actually a JSON string.
            |--------------------------------------------------------------------------
            */

            $specs = $asset->specifications;

            if (is_string($specs)) {
                $specs = json_decode($specs, true);
            }

            if (!is_array($specs)) {
                $specs = [];
            }

            /*
            |--------------------------------------------------------------------------
            | Remove empty specification values
            |--------------------------------------------------------------------------
            */

            $specs = array_filter($specs, function ($value) {
                return $value !== null
                    && $value !== ''
                    && $value !== 'null';
            });

            $asset->specifications = $specs;

            return $asset;
        });

        $instituteId = auth()->user()->institute_id;

        $selectedAmenityIds = Amenity::where('institute_id', $instituteId)
            ->where('is_active', true)
            ->pluck('asset_id')
            ->toArray();

        return view(
            'instituteAdmin.CreateBuildings.category-amenities',
            compact(
                'category',
                'assets',
                'selectedAmenityIds'
            )
        );

    } catch (\Exception $e) {

        \Log::error('Error loading category assets', [
            'category_id' => $categoryId,
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
        ]);

        abort(404, 'Category not found');
    }
}
    public function getAsset($assetId): JsonResponse
    {
        try {
            $instituteId = auth()->user()->institute_id;
            
            $asset = Asset::where('asset_id', $assetId)
                ->where('institute_id', $instituteId)
                ->first();
            
            if (!$asset) {
                return response()->json([
                    'success' => false,
                    'message' => 'Asset not found.'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $asset
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching asset: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch asset.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateSpecifications(Request $request, $assetId): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'specifications' => 'required|array',
                'specifications.*' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed.'
                ], 422);
            }

            $instituteId = auth()->user()->institute_id;
            
            $asset = Asset::where('asset_id', $assetId)
                ->where('institute_id', $instituteId)
                ->first();
            
            if (!$asset) {
                return response()->json([
                    'success' => false,
                    'message' => 'Asset not found.'
                ], 404);
            }
            
            $specs = array_filter($request->specifications, function($value) {
                return $value !== null && $value !== '' && $value !== 'null';
            });
            
            $asset->specifications = json_encode($specs);
            $asset->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Asset specifications updated successfully.',
                'data' => [
                    'asset_id' => $asset->asset_id,
                    'specifications' => $specs
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating asset specifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update asset specifications.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getBuildingAmenities($buildingId): JsonResponse
    {
        try {
            $instituteId = auth()->user()->institute_id;
            
            $building = AddBuilding::where('id', $buildingId)
                ->where('institute_id', $instituteId)
                ->first();
            
            if (!$building) {
                return response()->json([
                    'success' => false,
                    'message' => 'Building not found.'
                ], 404);
            }
            
            $allocatedAmenities = $building->allocated_amenities ?? [];
            if (is_string($allocatedAmenities)) {
                $allocatedAmenities = json_decode($allocatedAmenities, true) ?? [];
            }
            
            $amenities = Amenity::where('institute_id', $instituteId)
                ->where('is_active', true)
                ->orderBy('name', 'asc')
                ->get();
            
            $amenities = $amenities->map(function($amenity) use ($allocatedAmenities) {
                $amenity->allocated = isset($allocatedAmenities[$amenity->name]) 
                    ? $allocatedAmenities[$amenity->name] 
                    : 0;
                return $amenity;
            });
            
            return response()->json([
                'success' => true,
                'data' => $amenities,
                'building' => $building
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching building amenities: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch building amenities.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateCount(Request $request, $amenityId): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'count' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed.'
                ], 422);
            }

            $instituteId = auth()->user()->institute_id;
            
            $amenity = Amenity::where('amenity_id', $amenityId)
                ->where('institute_id', $instituteId)
                ->first();
            
            if (!$amenity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Amenity not found.'
                ], 404);
            }
            
            $oldCount = $amenity->count;
            $newCount = $request->count;
            
            $amenity->count = $newCount;
            $amenity->save();
            
            $createdUnits = [];
            
            if ($newCount > $oldCount) {
                $unitsToCreate = $newCount - $oldCount;
                
                for ($i = 1; $i <= $unitsToCreate; $i++) {
                    $unitNumber = str_pad($oldCount + $i, 3, '0', STR_PAD_LEFT);
                    $unitId = 'UNI-' . strtoupper(uniqid());
                    
                    $unit = AmenityUnit::create([
                        'unit_id' => $unitId,
                        'amenity_id' => $amenity->id,
                        'amenity_asset_id' => $amenity->asset_id,
                        'unit_number' => $unitNumber,
                        'name' => $amenity->name . ' - Unit ' . $unitNumber,
                        'specifications' => json_encode([]),
                        'status' => 'available',
                    ]);
                    
                    $createdUnits[] = $unit;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Count updated successfully.',
                'data' => [
                    'count' => $newCount,
                    'created_units' => $createdUnits,
                    'amenity_name' => $amenity->name,
                    'amenity_id' => $amenity->amenity_id,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating amenity count: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update amenity count.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function createUnits(Request $request, $amenityId): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'count' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed.'
                ], 422);
            }

            $instituteId = auth()->user()->institute_id;
            
            $amenity = Amenity::where('amenity_id', $amenityId)
                ->where('institute_id', $instituteId)
                ->first();
            
            if (!$amenity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Amenity not found.'
                ], 404);
            }
            
            $count = $request->count;
            $existingUnits = $amenity->units()->count();
            $createdUnits = [];
            
            for ($i = 1; $i <= $count; $i++) {
                $unitNumber = str_pad($existingUnits + $i, 3, '0', STR_PAD_LEFT);
                $unitId = 'UNI-' . strtoupper(uniqid());
                
                $unit = AmenityUnit::create([
                    'unit_id' => $unitId,
                    'amenity_id' => $amenity->id,
                    'amenity_asset_id' => $amenity->asset_id,
                    'unit_number' => $unitNumber,
                    'name' => $amenity->name . ' - Unit ' . $unitNumber,
                    'specifications' => json_encode([]),
                    'status' => 'available',
                ]);
                
                $createdUnits[] = $unit;
            }
            
            $amenity->count = $existingUnits + $count;
            $amenity->save();
            
            return response()->json([
                'success' => true,
                'message' => count($createdUnits) . ' unit(s) created successfully.',
                'data' => [
                    'units' => $createdUnits,
                    'total_units' => count($createdUnits) + $existingUnits,
                    'amenity_id' => $amenity->amenity_id,
                    'amenity_name' => $amenity->name,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating units: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create units.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAmenityUnits($amenityId): JsonResponse
    {
        try {
            $instituteId = auth()->user()->institute_id;
            
            $amenity = Amenity::where('amenity_id', $amenityId)
                ->where('institute_id', $instituteId)
                ->first();
            
            if (!$amenity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Amenity not found.'
                ], 404);
            }
            
            $units = $amenity->units()
                ->orderBy('unit_number', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'amenity' => $amenity,
                    'units' => $units,
                    'total_units' => $units->count()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching amenity units: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch amenity units.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUnit($unitId): JsonResponse
    {
        try {
            $instituteId = auth()->user()->institute_id;
            
            $unit = AmenityUnit::where('unit_id', $unitId)
                ->whereHas('amenity', function($query) use ($instituteId) {
                    $query->where('institute_id', $instituteId);
                })
                ->with('amenity')
                ->first();
            
            if (!$unit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unit not found.'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $unit
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching unit: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch unit.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateUnitSpecifications(Request $request, $unitId): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'specifications' => 'required|array',
                'specifications.*' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed.'
                ], 422);
            }

            $instituteId = auth()->user()->institute_id;
            
            $unit = AmenityUnit::where('unit_id', $unitId)
                ->whereHas('amenity', function($query) use ($instituteId) {
                    $query->where('institute_id', $instituteId);
                })
                ->first();
            
            if (!$unit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unit not found.'
                ], 404);
            }
            
            $newSpecs = array_filter($request->specifications, function($value) {
                return $value !== null && $value !== '' && $value !== 'null';
            });
            
            $unit->specifications = json_encode($newSpecs);
            $unit->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Unit specifications updated successfully.',
                'data' => [
                    'unit_id' => $unit->unit_id,
                    'specifications' => $newSpecs
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating unit specifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update unit specifications.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUnitSpecifications($unitId): JsonResponse
    {
        
            $instituteId = auth()->user()->institute_id;
           
            $unit = AmenityUnit::where('unit_id', $unitId)
                ->whereHas('amenity', function($query) use ($instituteId) {
                    $query->where('institute_id', $instituteId);
                })
                ->with('amenity')
                ->first();
            
            if (!$unit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unit not found.'
                ], 404);
            }
            
            $unitSpecs = $unit->specifications ?? [];
            
            if (is_string($unitSpecs)) {
                $decodedUnitSpecs = json_decode($unitSpecs, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decodedUnitSpecs)) {
                    $unitSpecs = $decodedUnitSpecs;
                } else {
                    $unitSpecs = [];
                }
            }
            
            $hasUnitSpecifications = is_array($unitSpecs) && count($unitSpecs) > 0;
            
            if ($hasUnitSpecifications) {
                $specs = $unitSpecs;
            } else {
                $specs = $unit->amenity->specifications ?? [];
                if (is_string($specs)) {
                    $decodedAmenitySpecs = json_decode($specs, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decodedAmenitySpecs)) {
                        $specs = $decodedAmenitySpecs;
                    } else {
                        $specs = [];
                    }
                }
                if (!is_array($specs)) {
                    $specs = [];
                }
            }
            
            $assignmentDetails = null;
            if ($unit->status === 'assigned' && $unit->assignment_id) {
                $assignment = AmenityAssignment::where('assignment_id', $unit->assignment_id)->first();
                if ($assignment) {
                    $assignmentDetails = [
                        'assigned_to_type' => $assignment->assigned_to_type,
                        'assigned_to_id' => $assignment->assigned_to_id,
                        'assigned_to_display' => $assignment->getAssignedToDisplayAttribute(),
                        'assigned_at' => $assignment->assigned_at ? $assignment->assigned_at->format('Y-m-d H:i:s') : null,
                        'notes' => $assignment->notes,
                        'status' => $assignment->status,
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'unit_id' => $unit->unit_id,
                    'unit_number' => $unit->unit_number,
                    'name' => $unit->name,
                    'status' => $unit->status,
                    'specifications' => $specs,
                    'amenity_name' => $unit->amenity ? $unit->amenity->name : null,
                    'amenity_category' => $unit->amenity ? $unit->amenity->category : null,
                    'assignment' => $assignmentDetails,
                    'created_at' => $unit->created_at ? $unit->created_at->format('Y-m-d H:i:s') : null,
                    'updated_at' => $unit->updated_at ? $unit->updated_at->format('Y-m-d H:i:s') : null,
                ]
            ]);
            
       
    }

    public function amenitiesManagement()
    {
        $instituteId = auth()->user()->institute_id;
      
        $buildings = AddBuilding::where('institute_id', $instituteId)
            ->orderBy('name', 'asc')
            ->get();
        
        $amenities = Amenity::where('institute_id', $instituteId)
            ->where('is_active', true)
            ->where('count', '>', 0)
            ->withCount(['units' => function($query) {
                $query->where('status', 'available');
            }])
            ->orderBy('name', 'asc')
            ->get();
        
        $amenities = $amenities->filter(function($amenity) {
            return $amenity->units_count > 0;
        });
        
        $selectedBuilding = $buildings->first();
        
        return view('instituteAdmin.CreateBuildings.amenities-management', compact('buildings', 'amenities', 'selectedBuilding'));
    }

    private function getDefaultAmenities(): array
    {
        return [
            ['name' => 'WiFi', 'category' => 'technology', 'icon' => 'fa-wifi'],
            ['name' => 'Internet Lab', 'category' => 'technology', 'icon' => 'fa-laptop'],
            ['name' => 'LAN', 'category' => 'technology', 'icon' => 'fa-network-wired'],
            ['name' => 'Projector', 'category' => 'technology', 'icon' => 'fa-projector'],
            ['name' => 'Smart Board', 'category' => 'technology', 'icon' => 'fa-chalkboard'],
            ['name' => 'CCTV', 'category' => 'security', 'icon' => 'fa-video'],
            ['name' => 'Biometric', 'category' => 'security', 'icon' => 'fa-fingerprint'],
            ['name' => 'Security Guard', 'category' => 'security', 'icon' => 'fa-shield-alt'],
            ['name' => 'Fire Alarm', 'category' => 'security', 'icon' => 'fa-fire-extinguisher'],
            ['name' => 'Fire Extinguisher', 'category' => 'security', 'icon' => 'fa-fire-extinguisher'],
            ['name' => 'Elevator', 'category' => 'accessibility', 'icon' => 'fa-elevator'],
            ['name' => 'Escalator', 'category' => 'accessibility', 'icon' => 'fa-arrow-up'],
            ['name' => 'Ramp', 'category' => 'accessibility', 'icon' => 'fa-wheelchair'],
            ['name' => 'Disabled Access', 'category' => 'accessibility', 'icon' => 'fa-wheelchair'],
            ['name' => 'AC', 'category' => 'utilities', 'icon' => 'fa-snowflake'],
            ['name' => 'Heater', 'category' => 'utilities', 'icon' => 'fa-fire'],
            ['name' => 'Exhaust Fan', 'category' => 'utilities', 'icon' => 'fa-fan'],
            ['name' => 'UPS', 'category' => 'utilities', 'icon' => 'fa-bolt'],
            ['name' => 'Solar Panel', 'category' => 'utilities', 'icon' => 'fa-solar-panel'],
            ['name' => 'Generator', 'category' => 'utilities', 'icon' => 'fa-industry'],
            ['name' => 'Washroom', 'category' => 'hygiene', 'icon' => 'fa-toilet'],
            ['name' => 'Drinking Water', 'category' => 'hygiene', 'icon' => 'fa-tint'],
            ['name' => 'Water Cooler', 'category' => 'hygiene', 'icon' => 'fa-tint'],
            ['name' => 'Cafeteria', 'category' => 'food', 'icon' => 'fa-utensils'],
            ['name' => 'Tuck Shop', 'category' => 'food', 'icon' => 'fa-store'],
            ['name' => 'Canteen', 'category' => 'food', 'icon' => 'fa-utensils'],
            ['name' => 'Library', 'category' => 'education', 'icon' => 'fa-book'],
            ['name' => 'Computer Lab', 'category' => 'education', 'icon' => 'fa-laptop'],
            ['name' => 'Seminar Hall', 'category' => 'education', 'icon' => 'fa-chalkboard-teacher'],
            ['name' => 'Conference Room', 'category' => 'education', 'icon' => 'fa-users'],
            ['name' => 'Classroom', 'category' => 'education', 'icon' => 'fa-chalkboard'],
            ['name' => 'Gym', 'category' => 'recreation', 'icon' => 'fa-dumbbell'],
            ['name' => 'Playground', 'category' => 'recreation', 'icon' => 'fa-futbol'],
            ['name' => 'Swimming Pool', 'category' => 'recreation', 'icon' => 'fa-swimming-pool'],
            ['name' => 'Sports', 'category' => 'recreation', 'icon' => 'fa-running'],
            ['name' => 'Medical Room', 'category' => 'medical', 'icon' => 'fa-medkit'],
            ['name' => 'Ambulance', 'category' => 'medical', 'icon' => 'fa-ambulance'],
            ['name' => 'First Aid', 'category' => 'medical', 'icon' => 'fa-briefcase-medical'],
            ['name' => 'ATM', 'category' => 'services', 'icon' => 'fa-atm'],
            ['name' => 'Stationery Shop', 'category' => 'services', 'icon' => 'fa-pen'],
            ['name' => 'Photocopy', 'category' => 'services', 'icon' => 'fa-copy'],
            ['name' => 'Laundry', 'category' => 'services', 'icon' => 'fa-tshirt'],
            ['name' => 'Salon', 'category' => 'services', 'icon' => 'fa-cut'],
            ['name' => 'Prayer Room', 'category' => 'accommodation', 'icon' => 'fa-pray'],
            ['name' => 'Daycare', 'category' => 'accommodation', 'icon' => 'fa-baby'],
            ['name' => 'Guest Room', 'category' => 'accommodation', 'icon' => 'fa-hotel'],
            ['name' => 'Staff Room', 'category' => 'accommodation', 'icon' => 'fa-users'],
            ['name' => 'Admin Office', 'category' => 'accommodation', 'icon' => 'fa-building'],
            ['name' => 'Hostel', 'category' => 'accommodation', 'icon' => 'fa-bed'],
        ];
    }

    public function showAssignmentPage($amenityId)
    {
        $instituteId = auth()->user()->institute_id;
        
        $amenity = Amenity::where('amenity_id', $amenityId)
            ->where('institute_id', $instituteId)
            ->where('is_active', true)
            ->with(['units' => function($query) {
                $query->where('status', 'available')
                    ->orWhere('status', 'assigned');
            }])
            ->firstOrFail();
        
        $availableUnits = $amenity->units->where('status', 'available');
        $assignedUnits = $amenity->units->where('status', 'assigned');
        
        $buildings = AddBuilding::where('institute_id', $instituteId)
            ->orderBy('name', 'asc')
            ->get();
        
        $blocks = AddBlock::where('institute_id', $instituteId)
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();
        
        $floors = AddFloor::where('institute_id', $instituteId)
            ->orderBy('floor_number', 'asc')
            ->get();
        
        $rooms = AddRooms::where('institute_id', $instituteId)
            ->orderBy('room_number', 'asc')
            ->get();
        
        return view('instituteAdmin.CreateBuildings.amenity-assignment', compact(
            'amenity',
            'availableUnits',
            'assignedUnits',
            'buildings',
            'blocks',
            'floors',
            'rooms'
        ));
    }

    public function assignUnits(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $validator = Validator::make($request->all(), [
                'unit_ids' => 'required|array',
                'unit_ids.*' => 'required|string|exists:amenity_units,unit_id',
                'assigned_to_type' => 'required|in:building,block,floor,room',
                'assigned_to_id' => 'required|string',
                'amenity_id' => 'required|string|exists:amenities,amenity_id',
                'building_id' => 'nullable|string',
                'block_id' => 'nullable|string',
                'floor_id' => 'nullable|string',
                'room_id' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed.'
                ], 422);
            }
            
            $assignedBy = auth()->user()->id;
            $assignedAt = now();
            $assignmentType = $request->assigned_to_type;
            $assignmentId = $request->assigned_to_id;
            
            $assignedUnits = [];
            
            foreach ($request->unit_ids as $unitId) {
                $unit = AmenityUnit::where('unit_id', $unitId)->first();
                if (!$unit || $unit->status !== 'available') {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Unit ' . $unitId . ' is not available for assignment.'
                    ], 422);
                }
                
                $assignmentUuid = AmenityAssignment::generateAssignmentId();
                
                $assignment = AmenityAssignment::create([
                    'assignment_id' => $assignmentUuid,
                    'unit_id' => $unit->unit_id,
                    'amenity_id' => $request->amenity_id,
                    'assigned_to_type' => $assignmentType,
                    'assigned_to_id' => $assignmentId,
                    'building_id' => $request->building_id,
                    'block_id' => $request->block_id,
                    'floor_id' => $request->floor_id,
                    'room_id' => $request->room_id,
                    'assigned_by' => $assignedBy,
                    'assigned_at' => $assignedAt,
                    'status' => 'active',
                    'notes' => $request->notes,
                    'specifications_snapshot' => $unit->specifications,
                ]);
                
                $unit->status = 'assigned';
                $unit->assignment_id = $assignmentUuid;
                $unit->save();
                
                $assignedUnits[] = [
                    'unit' => $unit,
                    'assignment' => $assignment,
                ];
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => count($assignedUnits) . ' unit(s) assigned successfully.',
                'data' => [
                    'assigned_units' => $assignedUnits,
                    'assignment_type' => $assignmentType,
                    'assignment_id' => $assignmentId,
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error assigning units: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign units: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function unassignUnit($unitId): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $instituteId = auth()->user()->institute_id;
            
            $unit = AmenityUnit::where('unit_id', $unitId)
                ->whereHas('amenity', function($query) use ($instituteId) {
                    $query->where('institute_id', $instituteId);
                })
                ->first();
            
            if (!$unit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unit not found.'
                ], 404);
            }
            
            if ($unit->status !== 'assigned') {
                return response()->json([
                    'success' => false,
                    'message' => 'This unit is not assigned.'
                ], 422);
            }
            
            $assignment = AmenityAssignment::where('assignment_id', $unit->assignment_id)->first();
            
            $unit->status = 'available';
            $unit->assignment_id = null;
            $unit->save();
            
            if ($assignment) {
                $assignment->status = 'inactive';
                $assignment->save();
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Unit unassigned successfully.',
                'data' => [
                    'unit' => $unit,
                    'assignment' => $assignment,
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error unassigning unit: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to unassign unit: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getAssignmentHistory($unitId): JsonResponse
    {
        try {
            $instituteId = auth()->user()->institute_id;
            
            $unit = AmenityUnit::where('unit_id', $unitId)
                ->whereHas('amenity', function($query) use ($instituteId) {
                    $query->where('institute_id', $instituteId);
                })
                ->with('assignment')
                ->first();
            
            if (!$unit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unit not found.'
                ], 404);
            }
            
            $assignments = AmenityAssignment::where('unit_id', $unit->id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'unit' => $unit,
                    'current_assignment' => $unit->assignment,
                    'assignment_history' => $assignments,
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching assignment history: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch assignment history.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



        /**
     * Show assigned amenities page with all data preloaded.
     */
    public function assignedAmenities()
    {
        $instituteId = auth()->user()->institute_id;

        // Get buildings, blocks, floors and rooms for filters/edit modal
        $buildings = AddBuilding::where('institute_id', $instituteId)
            ->orderBy('name', 'asc')
            ->get();

        $blocks = AddBlock::where('institute_id', $instituteId)
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();

        $floors = AddFloor::where('institute_id', $instituteId)
            ->orderBy('floor_number', 'asc')
            ->get();

        $rooms = AddRooms::where('institute_id', $instituteId)
            ->orderBy('room_number', 'asc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        | Assigned amenities are loaded here directly.
        | The Blade page does NOT need AJAX to get assigned amenities.
        |--------------------------------------------------------------------------
        */
        $assignedAmenities = $this->buildAssignedAmenitiesData($instituteId);

        // Statistics
        $totalAssignedUnits = 0;

        $buildingsWithAmenities = collect();
        $blocksWithAmenities = collect();
        $floorsWithAmenities = collect();
        $roomsWithAmenities = collect();

        foreach ($assignedAmenities as $assignment) {

            $unitCount = count($assignment['units'] ?? []);

            $totalAssignedUnits += $unitCount;

            if ($unitCount <= 0) {
                continue;
            }

            $type = $assignment['assignment']['assigned_to_type'] ?? '';
            $id   = $assignment['assignment']['assigned_to_id'] ?? '';

            switch ($type) {

                case 'building':
                    $buildingsWithAmenities->push($id);
                    break;

                case 'block':
                    $blocksWithAmenities->push($id);
                    break;

                case 'floor':
                    $floorsWithAmenities->push($id);
                    break;

                case 'room':
                    $roomsWithAmenities->push($id);
                    break;
            }
        }

        return view(
            'instituteAdmin.CreateBuildings.assigned-amenities',
            compact(
                'buildings',
                'blocks',
                'floors',
                'rooms',
                'assignedAmenities',
                'totalAssignedUnits',
                'buildingsWithAmenities',
                'blocksWithAmenities',
                'floorsWithAmenities',
                'roomsWithAmenities'
            )
        );
    }

    public function getAssignedAmenitiesData(): JsonResponse
    {
        try {
            $instituteId = auth()->user()->institute_id;

            $result = $this->buildAssignedAmenitiesData($instituteId);

            return response()->json([
                'success' => true,
                'data' => $result,
                'total' => count($result),
            ]);

        } catch (\Exception $e) {

            Log::error(
                'Error fetching assigned amenities: ' . $e->getMessage(),
                [
                    'trace' => $e->getTraceAsString()
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch assigned amenities.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

 
    /**
     * Build assigned amenities data for the Assigned Amenities page.
     *
     * Data is loaded directly from the database when the page opens.
     * No AJAX is required for the Assigned Amenities table.
     */
    private function buildAssignedAmenitiesData($instituteId): array
    {
        $result = [];

        /*
        |--------------------------------------------------------------------------
        | Get active assignments
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | amenity_assignments.unit_id contains amenity_units.unit_id
        | (string), NOT amenity_units.id.
        |
        */

        $assignments = AmenityAssignment::query()
            ->where('status', 'active')
            ->whereNotNull('unit_id')
            ->orderBy('created_at', 'desc')
            ->get();


        if ($assignments->isEmpty()) {
            return [];
        }


        /*
        |--------------------------------------------------------------------------
        | Get all assigned unit IDs
        |--------------------------------------------------------------------------
        */

        $unitIds = $assignments
            ->pluck('unit_id')
            ->filter()
            ->unique()
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Get units directly using unit_id
        |--------------------------------------------------------------------------
        */

        $units = AmenityUnit::query()
            ->whereIn('unit_id', $unitIds)
            ->with('amenity')
            ->get()
            ->keyBy('unit_id');


        /*
        |--------------------------------------------------------------------------
        | Group assignments
        |--------------------------------------------------------------------------
        */

        $groupedAssignments = $assignments->groupBy('assignment_id');


        foreach ($groupedAssignments as $assignmentId => $assignmentGroup) {

            $first = $assignmentGroup->first();

            if (!$first) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Get units for this assignment
            |--------------------------------------------------------------------------
            */

            $assignedUnits = collect();

            foreach ($assignmentGroup as $assignment) {

                if (empty($assignment->unit_id)) {
                    continue;
                }

                $unit = $units->get($assignment->unit_id);

                if ($unit) {
                    $assignedUnits->push($unit);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Remove duplicate units
            |--------------------------------------------------------------------------
            */

            $assignedUnits = $assignedUnits
                ->unique('unit_id')
                ->values();


            if ($assignedUnits->isEmpty()) {
                continue;
            }


            $firstUnit = $assignedUnits->first();

            $amenity = $firstUnit->amenity;


            if (!$amenity) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | CATEGORY
            |--------------------------------------------------------------------------
            |
            | In your data, $amenity->category can be a related model/object.
            | Do NOT send the complete object to Blade.
            |
            */

            $categoryName = 'General';
            $categoryId = null;


            $rawCategory = $amenity->category ?? null;


            if ($rawCategory instanceof \Illuminate\Database\Eloquent\Model) {

                $categoryName =
                    $rawCategory->name
                    ?? 'General';

                $categoryId =
                    $rawCategory->id
                    ?? $rawCategory->category_id
                    ?? null;

            } elseif (is_object($rawCategory)) {

                $categoryName =
                    $rawCategory->name
                    ?? 'General';

                $categoryId =
                    $rawCategory->id
                    ?? $rawCategory->category_id
                    ?? null;

            } elseif (is_array($rawCategory)) {

                $categoryName =
                    $rawCategory['name']
                    ?? 'General';

                $categoryId =
                    $rawCategory['id']
                    ?? $rawCategory['category_id']
                    ?? null;

            } elseif (is_string($rawCategory)) {

                /*
                | Sometimes the category is stored as JSON.
                */

                $decodedCategory =
                    json_decode($rawCategory, true);


                if (
                    json_last_error() === JSON_ERROR_NONE
                    && is_array($decodedCategory)
                ) {

                    $categoryName =
                        $decodedCategory['name']
                        ?? $decodedCategory['category']
                        ?? 'General';

                    $categoryId =
                        $decodedCategory['id']
                        ?? $decodedCategory['category_id']
                        ?? null;

                } else {

                    $categoryName =
                        trim($rawCategory) !== ''
                            ? $rawCategory
                            : 'General';
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Normalize category name
            |--------------------------------------------------------------------------
            */

            $categoryName = trim((string) $categoryName);

            if ($categoryName === '') {
                $categoryName = 'General';
            }


            /*
            |--------------------------------------------------------------------------
            | CATEGORY CLASS
            |--------------------------------------------------------------------------
            */

            $categoryClass = strtolower($categoryName);

            $categoryClass = preg_replace(
                '/[^a-z0-9]+/',
                '-',
                $categoryClass
            );

            $allowedCategories = [
                'technology',
                'security',
                'accessibility',
                'utilities',
                'hygiene',
                'food',
                'education',
                'recreation',
                'medical',
                'services',
                'accommodation',
            ];

            if (!in_array($categoryClass, $allowedCategories)) {
                $categoryClass = 'general';
            }


            /*
            |--------------------------------------------------------------------------
            | AMENITY SPECIFICATIONS
            |--------------------------------------------------------------------------
            */

            $amenitySpecifications =
                $amenity->specifications ?? [];


            /*
            | Decode JSON specifications.
            */

            if (is_string($amenitySpecifications)) {

                $decodedSpecifications =
                    json_decode(
                        $amenitySpecifications,
                        true
                    );


                if (
                    json_last_error() === JSON_ERROR_NONE
                    && is_array($decodedSpecifications)
                ) {

                    $amenitySpecifications =
                        $decodedSpecifications;

                } else {

                    $amenitySpecifications = [];
                }
            }


            /*
            | Convert object to array if necessary.
            */

            if (is_object($amenitySpecifications)) {

                $amenitySpecifications =
                    json_decode(
                        json_encode($amenitySpecifications),
                        true
                    );
            }


            if (!is_array($amenitySpecifications)) {
                $amenitySpecifications = [];
            }


            /*
            |--------------------------------------------------------------------------
            | Remove accidental category JSON from specifications
            |--------------------------------------------------------------------------
            |
            | Your current output contains the category object inside the
            | specifications area. Remove that metadata if it exists.
            |
            */

            if (
                isset($amenitySpecifications['category'])
                && is_array($amenitySpecifications['category'])
            ) {

                $categoryObject =
                    $amenitySpecifications['category'];


                /*
                | If category is only metadata, remove it.
                */

                if (
                    isset($categoryObject['category_id'])
                    || isset($categoryObject['created_at'])
                    || isset($categoryObject['updated_at'])
                ) {

                    unset(
                        $amenitySpecifications['category']
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Build unit data
            |--------------------------------------------------------------------------
            */

            $unitData = $assignedUnits
                ->map(function ($unit) use ($amenitySpecifications) {

                    $specifications =
                        $unit->specifications ?? [];


                    /*
                    | Decode unit specifications.
                    */

                    if (is_string($specifications)) {

                        $decoded =
                            json_decode(
                                $specifications,
                                true
                            );


                        if (
                            json_last_error() === JSON_ERROR_NONE
                            && is_array($decoded)
                        ) {

                            $specifications = $decoded;

                        } else {

                            $specifications = [];
                        }
                    }


                    /*
                    | Convert object to array.
                    */

                    if (is_object($specifications)) {

                        $specifications =
                            json_decode(
                                json_encode($specifications),
                                true
                            );
                    }


                    if (!is_array($specifications)) {
                        $specifications = [];
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | If the unit has no specifications,
                    | use amenity specifications.
                    |--------------------------------------------------------------------------
                    */

                    if (empty($specifications)) {
                        $specifications =
                            $amenitySpecifications;
                    }


                    return [

                        'id' =>
                            $unit->id,

                        'unit_id' =>
                            $unit->unit_id,

                        'unit_number' =>
                            $unit->unit_number,

                        'name' =>
                            $unit->name,

                        'status' =>
                            $unit->status,

                        'specifications' =>
                            $specifications,
                    ];

                })
                ->values()
                ->toArray();


            /*
            |--------------------------------------------------------------------------
            | LOCATION
            |--------------------------------------------------------------------------
            */

            $locationName =
                $this->getLocationDisplayName($first);


            $locationPath =
                $this->getLocationPath($first);


            /*
            |--------------------------------------------------------------------------
            | RESULT
            |--------------------------------------------------------------------------
            */

            $result[] = [

                'assignment_id' =>
                    $assignmentId,


                'assignment' => [

                    'assignment_id' =>
                        $first->assignment_id,

                    'assigned_to_type' =>
                        $first->assigned_to_type,

                    'assigned_to_id' =>
                        $first->assigned_to_id,

                    'building_id' =>
                        $first->building_id,

                    'block_id' =>
                        $first->block_id,

                    'floor_id' =>
                        $first->floor_id,

                    'room_id' =>
                        $first->room_id,

                    'assigned_at' =>
                        $first->assigned_at,

                    'notes' =>
                        $first->notes,

                    'status' =>
                        $first->status,
                ],


                /*
                |--------------------------------------------------------------------------
                | Clean amenity data
                |--------------------------------------------------------------------------
                */

                'amenity' => [

                    'id' =>
                        $amenity->id,

                    'amenity_id' =>
                        $amenity->amenity_id,

                    'code' =>
                        $amenity->code
                        ?? $amenity->amenity_id,

                    'name' =>
                        $amenity->name,

                    /*
                    | IMPORTANT:
                    | Send only the category name, not the category object.
                    */

                    'category' =>
                        $categoryName,

                    'category_id' =>
                        $categoryId,

                    'icon' =>
                        $amenity->icon
                        ?? 'fa-cube',

                    'specifications' =>
                        $amenitySpecifications,
                ],


                /*
                |--------------------------------------------------------------------------
                | First unit
                |--------------------------------------------------------------------------
                */

                'unit' =>
                    $unitData[0] ?? [],


                /*
                |--------------------------------------------------------------------------
                | All assigned units
                |--------------------------------------------------------------------------
                */

                'units' =>
                    $unitData,


                /*
                |--------------------------------------------------------------------------
                | Location
                |--------------------------------------------------------------------------
                */

                'location_name' =>
                    $locationName,

                'location_path' =>
                    $locationPath,


                'unit_count' =>
                    count($unitData),
            ];
        }


        return $result;
    }



   /**
 * Get location display name for an assignment.
 */
private function getLocationDisplayName($assignment)
{
    $type = $assignment->assigned_to_type;
    $id = $assignment->assigned_to_id;
    
    try {
        if ($type === 'building') {
            $building = AddBuilding::find($id);
            return $building ? $building->name . ' (Building)' : 'Building #' . $id;
        } elseif ($type === 'block') {
            $block = AddBlock::find($id);
            return $block ? $block->name . ' (Block)' : 'Block #' . $id;
        } elseif ($type === 'floor') {
            $floor = AddFloor::find($id);
            return $floor ? 'Floor ' . $floor->floor_number . ' (Floor)' : 'Floor #' . $id;
        } elseif ($type === 'room') {
            $room = AddRooms::find($id);
            return $room ? 'Room ' . $room->room_number . ' (Room)' : 'Room #' . $id;
        }
    } catch (\Exception $e) {
        \Log::error('Error getting location display name: ' . $e->getMessage());
    }
    
    return 'Unknown Location';
}

   /**
 * Get location path for an assignment.
 */
private function getLocationPath($assignment)
{
    $path = [];
    $type = $assignment->assigned_to_type;
    $id = $assignment->assigned_to_id;
    
    try {
        if ($type === 'building') {
            $building = AddBuilding::find($id);
            if ($building) {
                $path[] = ['type' => 'Building', 'name' => $building->name];
            }
        } elseif ($type === 'block') {
            $block = AddBlock::find($id);
            if ($block) {
                if ($block->building) {
                    $path[] = ['type' => 'Building', 'name' => $block->building->name ?? 'Unknown Building'];
                }
                $path[] = ['type' => 'Block', 'name' => $block->name];
            }
        } elseif ($type === 'floor') {
            $floor = AddFloor::find($id);
            if ($floor) {
                $block = AddBlock::find($floor->block_id);
                if ($block) {
                    if ($block->building) {
                        $path[] = ['type' => 'Building', 'name' => $block->building->name ?? 'Unknown Building'];
                    }
                    $path[] = ['type' => 'Block', 'name' => $block->name];
                }
                $path[] = ['type' => 'Floor', 'name' => 'Floor ' . $floor->floor_number];
            }
        } elseif ($type === 'room') {
            $room = AddRooms::find($id);
            if ($room) {
                $floor = AddFloor::find($room->floor_id);
                if ($floor) {
                    $block = AddBlock::find($floor->block_id);
                    if ($block) {
                        if ($block->building) {
                            $path[] = ['type' => 'Building', 'name' => $block->building->name ?? 'Unknown Building'];
                        }
                        $path[] = ['type' => 'Block', 'name' => $block->name];
                    }
                    $path[] = ['type' => 'Floor', 'name' => 'Floor ' . $floor->floor_number];
                }
                $path[] = ['type' => 'Room', 'name' => $room->room_number];
            }
        }
    } catch (\Exception $e) {
        \Log::error('Error getting location path: ' . $e->getMessage());
    }
    
    return $path;
}

    /**
     * Update an assignment.
     */
    public function updateAssignment(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $validator = Validator::make($request->all(), [
                'unit_id' => 'required|string|exists:amenity_units,unit_id',
                'assignment_id' => 'nullable|string',
                'assigned_to_type' => 'required|in:building,block,floor,room',
                'assigned_to_id' => 'required|string',
                'building_id' => 'nullable|string',
                'block_id' => 'nullable|string',
                'floor_id' => 'nullable|string',
                'room_id' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed.'
                ], 422);
            }
            
            $instituteId = auth()->user()->institute_id;
            $unitId = $request->unit_id;
            $assignmentId = $request->assignment_id;
            
            $unit = AmenityUnit::where('unit_id', $unitId)
                ->whereHas('amenity', function($query) use ($instituteId) {
                    $query->where('institute_id', $instituteId);
                })
                ->first();
            
            if (!$unit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unit not found.'
                ], 404);
            }
            
            if ($assignmentId) {
                $oldAssignment = AmenityAssignment::where('assignment_id', $assignmentId)->first();
                if ($oldAssignment) {
                    $oldAssignment->status = 'inactive';
                    $oldAssignment->save();
                }
            }
            
            $newAssignmentId = AmenityAssignment::generateAssignmentId();
            
            $assignment = AmenityAssignment::create([
                'assignment_id' => $newAssignmentId,
                'unit_id' => $unit->unit_id,
                'amenity_id' => $unit->amenity_id,
                'assigned_to_type' => $request->assigned_to_type,
                'assigned_to_id' => $request->assigned_to_id,
                'building_id' => $request->building_id,
                'block_id' => $request->block_id,
                'floor_id' => $request->floor_id,
                'room_id' => $request->room_id,
                'assigned_by' => auth()->user()->id,
                'assigned_at' => now(),
                'status' => 'active',
                'notes' => $request->notes,
                'specifications_snapshot' => $unit->specifications,
            ]);
            
            $unit->status = 'assigned';
            $unit->assignment_id = $newAssignmentId;
            $unit->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Assignment updated successfully.',
                'data' => [
                    'assignment' => $assignment,
                    'unit' => $unit,
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating assignment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update assignment: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unassign all units from an assignment.
     */
    public function unassignAllUnits($assignmentId): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            $instituteId = auth()->user()->institute_id;
            
            $units = AmenityUnit::where('assignment_id', $assignmentId)
                ->whereHas('amenity', function($query) use ($instituteId) {
                    $query->where('institute_id', $instituteId);
                })
                ->get();
            
            if ($units->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No units found for this assignment.'
                ], 404);
            }
            
            foreach ($units as $unit) {
                $unit->status = 'available';
                $unit->assignment_id = null;
                $unit->save();
            }
            
            $assignment = AmenityAssignment::where('assignment_id', $assignmentId)->first();
            if ($assignment) {
                $assignment->status = 'inactive';
                $assignment->save();
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => $units->count() . ' unit(s) unassigned successfully.',
                'data' => [
                    'unassigned_count' => $units->count(),
                    'assignment_id' => $assignmentId,
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error unassigning all units: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to unassign all units: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }
/**
 * View a single assignment details.
 */
public function viewAssignment($assignmentId)
{
    try {
        $instituteId = auth()->user()->institute_id;
        
        // Find the assignment
        $assignment = AmenityAssignment::where('assignment_id', $assignmentId)
            ->where('status', 'active')
            ->first();
        
        if (!$assignment) {
            abort(404, 'Assignment not found.');
        }
        
        // Get the unit
        $unit = AmenityUnit::where('unit_id', $assignment->unit_id)
            ->whereHas('amenity', function($query) use ($instituteId) {
                $query->where('institute_id', $instituteId);
            })
            ->with('amenity')
            ->first();
        
        if (!$unit) {
            abort(404, 'Unit not found.');
        }
        
        // Get amenity
        $amenity = $unit->amenity;
        
        // Build location path and name
        $locationPath = $this->getLocationPath($assignment);
        $locationName = $this->getLocationDisplayName($assignment);
        
        // Build assignment data
        $assignmentData = [
            'assignment_id' => $assignment->assignment_id,
            'assignment' => [
                'assignment_id' => $assignment->assignment_id,
                'assigned_to_type' => $assignment->assigned_to_type,
                'assigned_to_id' => $assignment->assigned_to_id,
                'building_id' => $assignment->building_id,
                'block_id' => $assignment->block_id,
                'floor_id' => $assignment->floor_id,
                'room_id' => $assignment->room_id,
                'assigned_at' => $assignment->assigned_at,
                'notes' => $assignment->notes,
                'status' => $assignment->status,
            ],
            'amenity' => $amenity ? [
                'amenity_id' => $amenity->amenity_id,
                'name' => $amenity->name,
                'category' => $amenity->category,
                'icon' => $amenity->icon,
                'code' => $amenity->code ?? $amenity->amenity_id,
                'specifications' => $amenity->specifications,
            ] : null,
            'units' => [[
                'unit_id' => $unit->unit_id,
                'unit_number' => $unit->unit_number,
                'name' => $unit->name,
                'status' => $unit->status,
                'specifications' => $unit->specifications,
            ]],
            'location_name' => $locationName,
            'location_path' => $locationPath,
            'specifications' => $unit->specifications ?: ($amenity ? $amenity->specifications : []),
        ];
        
        // Get buildings, blocks, floors, rooms for edit form
        $buildings = AddBuilding::where('institute_id', $instituteId)
            ->orderBy('name', 'asc')
            ->get();
        
        $blocks = AddBlock::where('institute_id', $instituteId)
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();
        
        $floors = AddFloor::where('institute_id', $instituteId)
            ->orderBy('floor_number', 'asc')
            ->get();
        
        $rooms = AddRooms::where('institute_id', $instituteId)
            ->orderBy('room_number', 'asc')
            ->get();
        
        return view('instituteAdmin.CreateBuildings.assigned-amenity-view', compact(
            'assignmentData',
            'buildings',
            'blocks',
            'floors',
            'rooms'
        ));
        
    } catch (\Exception $e) {
        \Log::error('Error viewing assignment: ' . $e->getMessage());
        abort(404, 'Assignment not found.');
    }
}

/**
 * View assignment details by unit ID (fallback).
 */
public function viewAssignmentByUnit($unitId)
{
    try {
        $instituteId = auth()->user()->institute_id;
        
        $unit = AmenityUnit::where('unit_id', $unitId)
            ->whereHas('amenity', function($query) use ($instituteId) {
                $query->where('institute_id', $instituteId);
            })
            ->with('amenity')
            ->first();
        
        if (!$unit) {
            abort(404, 'Unit not found.');
        }
        
        if (!$unit->assignment_id) {
            abort(404, 'Unit is not assigned.');
        }
        
        return $this->viewAssignment($unit->assignment_id);
        
    } catch (\Exception $e) {
        \Log::error('Error viewing assignment by unit: ' . $e->getMessage());
        abort(404, 'Unit not found.');
    }
}

/**
 * Build single assignment data structure.
 */
private function buildSingleAssignmentData($assignment)
{
    $unit = $assignment->unit;
    $amenity = $unit ? $unit->amenity : null;
    
    $locationPath = $this->getLocationPath($assignment);
    $locationName = $this->getLocationDisplayName($assignment);
    
    $units = [$unit ? [
        'unit_id' => $unit->unit_id,
        'unit_number' => $unit->unit_number,
        'name' => $unit->name,
        'status' => $unit->status,
        'specifications' => $unit->specifications,
    ] : []];
    
    return [
        'assignment_id' => $assignment->assignment_id,
        'assignment' => [
            'assignment_id' => $assignment->assignment_id,
            'assigned_to_type' => $assignment->assigned_to_type,
            'assigned_to_id' => $assignment->assigned_to_id,
            'building_id' => $assignment->building_id,
            'block_id' => $assignment->block_id,
            'floor_id' => $assignment->floor_id,
            'room_id' => $assignment->room_id,
            'assigned_at' => $assignment->assigned_at,
            'notes' => $assignment->notes,
            'status' => $assignment->status,
        ],
        'amenity' => $amenity ? [
            'amenity_id' => $amenity->amenity_id,
            'name' => $amenity->name,
            'category' => $amenity->category,
            'icon' => $amenity->icon,
            'code' => $amenity->code ?? $amenity->amenity_id,
            'specifications' => $amenity->specifications,
        ] : null,
        'units' => $units,
        'location_name' => $locationName,
        'location_path' => $locationPath,
        'specifications' => $unit ? $unit->specifications : ($amenity ? $amenity->specifications : []),
    ];
}

}