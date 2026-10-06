<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssetCategory;
use App\Models\Asset;
use App\Models\Amenity;
use App\Models\AmenityUnit;
use App\Models\AddBuilding;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CampusAmenitiesController extends Controller
{
    /**
     * Show the campus amenities management page
     */
   public function index()
    {
        $instituteId = auth()->user()->institute_id;
        
        // Get all buildings for the dropdown
        $buildings = AddBuilding::where('institute_id', $instituteId)
            ->orderBy('name', 'asc')
            ->get();
        
        // Get the first building if exists
        $selectedBuilding = $buildings->first();
        $amenities = [];
        
        if ($selectedBuilding) {
            $amenities = Amenity::where('institute_id', $instituteId)
                ->where('is_active', true)
                ->orderBy('name', 'asc')
                ->get();
                
            // Get unit counts for each amenity
            $amenities = $amenities->map(function($amenity) {
                $unitCount = AmenityUnit::where('amenity_id', $amenity->id)->count();
                $amenity->unit_count = $unitCount;
                return $amenity;
            });
        }
        
        return view('instituteAdmin.CreateBuildings.campus-amenities', compact('buildings', 'selectedBuilding', 'amenities'));
    }
    /**
     * Get amenities data for a building
     */
    public function getAmenities($buildingId): JsonResponse
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
            
            // Get amenities for this institute
            $amenities = Amenity::where('institute_id', $instituteId)
                ->where('is_active', true)
                ->orderBy('name', 'asc')
                ->get();
            
            // Get unit counts for each amenity
            $amenities = $amenities->map(function($amenity) {
                $unitCount = AmenityUnit::where('amenity_id', $amenity->id)->count();
                $amenity->unit_count = $unitCount;
                return $amenity;
            });
            
            return response()->json([
                'success' => true,
                'data' => $amenities,
                'building' => $building,
                'total' => $amenities->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching amenities: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch amenities.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update amenity count
     */
    public function updateCount(Request $request, $amenityId): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'count' => 'required|integer|min:0'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
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
            
            // If count increased, create new units
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
                'message' => 'Unable to update amenity count.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get amenity units
     */
       public function getAmenityUnits($amenityId): JsonResponse
    {
        dd($amenityId);
        // try {
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
            
            $units = $amenity->units()->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'amenity' => $amenity,
                    'units' => $units,
                    'total_units' => $units->count()
                ]
            ]);
            
        // } catch (\Exception $e) {
        //     Log::error('Error fetching units: ' . $e->getMessage());
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Unable to fetch units.',
        //         'error' => $e->getMessage()
        //     ], 500);
        // }
    }

    /**
     * Create units for an amenity
     */
    public function createUnits(Request $request, $amenityId): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'count' => 'required|integer|min:1'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
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
            
            $unitsToCreate = $count;
            
            for ($i = 1; $i <= $unitsToCreate; $i++) {
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
            
            // Update the amenity count
            $amenity->count = $existingUnits + $unitsToCreate;
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
                'message' => 'Unable to create units.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single unit
     */
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
                'message' => 'Unable to fetch unit.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update unit specifications
     */
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
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
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
                'message' => 'Unable to update unit specifications.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the unit specifications page
     */
    public function showUnitSpecificationsPage($unitId)
    {
        $instituteId = auth()->user()->institute_id;

        $unit = AmenityUnit::where('unit_id', $unitId)
            ->whereHas('amenity', function ($query) use ($instituteId) {
                $query->where('institute_id', $instituteId);
            })
            ->with('amenity')
            ->firstOrFail();

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

        // Get the return URL if provided
        $returnUrl = request()->input('return_url', route('institute.admin.campus.amenities'));

        return view(
            'instituteAdmin.CreateBuildings.unit-specifications',
            compact('unit', 'specs', 'returnUrl')
        );
    }

    /**
     * Show the units view page
     */
    public function viewUnitsPage($amenityId)
    {
        try {
            $instituteId = auth()->user()->institute_id;
            $amenity = Amenity::where('amenity_id', $amenityId)
                ->where('institute_id', $instituteId)
                ->first();
            
            if (!$amenity) {
                abort(404, 'Amenity not found');
            }
            
            $units = $amenity->units()->orderBy('unit_number', 'asc')->get();
            
            return view('instituteAdmin.CreateBuildings.units-view', compact('amenity', 'units'));
            
        } catch (\Exception $e) {
            abort(404, 'Amenity not found');
        }
    }
}