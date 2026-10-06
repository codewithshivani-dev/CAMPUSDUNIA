<?php

namespace App\Http\Controllers\HostelManagement;

use App\Http\Controllers\Controller;
use App\Models\AddFloor;
use App\Models\AddBuilding;
use App\Models\AddBlock;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    public function index()
    {
        $floors = AddFloor::latest()->get();

        return view(
            'hostel_management_system.floor.index',
            compact('floors')
        );
    }

    public function create()
    {
        $buildings = AddBuilding::latest()->get();
        $blocks = AddBlock::latest()->get();

        return view(
            'hostel_management_system.floor.create',
            compact('buildings', 'blocks')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'institute_id' => 'nullable|string|max:255',
            'branch_id' => 'nullable|string|max:255',

            'building_id' => 'required|integer',
            'block_id' => 'required|integer',

            'floor_number' => 'required|string|max:255',
            'floor_name' => 'nullable|string|max:255',

            'warehouse_name' => 'nullable|string|max:255',
            'warehouse_id' => 'nullable|string|max:255',

            'description' => 'nullable|string',

            'has_ac' => 'nullable|boolean',
            'is_central_ac' => 'nullable|boolean',
            'ac_units' => 'nullable|integer|min:0',

            'has_water_facility' => 'nullable|boolean',
            'has_water_cooler' => 'nullable|boolean',
            'has_drinking_water' => 'nullable|boolean',
            'water_cooler_count' => 'nullable|integer|min:0',

            'has_fire_extinguisher' => 'nullable|boolean',
            'has_fire_alarm' => 'nullable|boolean',
            'has_emergency_exit' => 'nullable|boolean',
            'fire_extinguisher_count' => 'nullable|integer|min:0',

            'has_lift' => 'nullable|boolean',
            'lift_type' => 'nullable|in:passenger,service',
            'lift_capacity' => 'nullable|integer|min:0',
            'lift_weight_limit' => 'nullable|integer|min:0',
            'lift_count' => 'nullable|integer|min:0',

            'has_washroom' => 'nullable|boolean',
            'washroom_type' => 'nullable|in:common,private',
            'washroom_gender' => 'nullable|in:male,female,unisex',
            'washroom_capacity' => 'nullable|integer|min:0',
            'washroom_count' => 'nullable|integer|min:0',

            'has_wifi' => 'nullable|boolean',
            'has_projector_room' => 'nullable|boolean',
            'has_conference_room' => 'nullable|boolean',
            'has_library' => 'nullable|boolean',
            'has_staff_room' => 'nullable|boolean',
            'has_common_room' => 'nullable|boolean',
            'has_disabled_access' => 'nullable|boolean',

            'total_rooms' => 'nullable|integer|min:0',
            'occupied_rooms' => 'nullable|integer|min:0',
            'available_rooms' => 'nullable|integer|min:0',
            'total_capacity' => 'nullable|integer|min:0',

            'total_area' => 'nullable|numeric|min:0',
            'area_unit' => 'nullable|string|max:255',

            'additional_areas' => 'nullable',
            'gates' => 'nullable',
            'custom_amenities' => 'nullable',
            'allocated_amenities' => 'nullable',
            'allocated_facility_entries' => 'nullable',

            'status' => 'nullable|in:active,inactive,under_maintenance',

            'floor_level' => 'nullable|integer',
            'floor_height' => 'nullable|numeric|min:0',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Default values
        |--------------------------------------------------------------------------
        */

        $validated['has_ac'] = $request->boolean('has_ac');
        $validated['is_central_ac'] = $request->boolean('is_central_ac');

        $validated['has_water_facility'] = $request->boolean('has_water_facility');
        $validated['has_water_cooler'] = $request->boolean('has_water_cooler');
        $validated['has_drinking_water'] = $request->boolean('has_drinking_water');

        $validated['has_fire_extinguisher'] =
            $request->boolean('has_fire_extinguisher');

        $validated['has_fire_alarm'] =
            $request->boolean('has_fire_alarm');

        $validated['has_emergency_exit'] =
            $request->boolean('has_emergency_exit');

        $validated['has_lift'] = $request->boolean('has_lift');

        $validated['has_washroom'] =
            $request->boolean('has_washroom');

        $validated['has_wifi'] =
            $request->boolean('has_wifi');

        $validated['has_projector_room'] =
            $request->boolean('has_projector_room');

        $validated['has_conference_room'] =
            $request->boolean('has_conference_room');

        $validated['has_library'] =
            $request->boolean('has_library');

        $validated['has_staff_room'] =
            $request->boolean('has_staff_room');

        $validated['has_common_room'] =
            $request->boolean('has_common_room');

        $validated['has_disabled_access'] =
            $request->boolean('has_disabled_access');


        /*
        |--------------------------------------------------------------------------
        | Numeric defaults
        |--------------------------------------------------------------------------
        */

        $validated['ac_units'] =
            $validated['ac_units'] ?? 0;

        $validated['water_cooler_count'] =
            $validated['water_cooler_count'] ?? 0;

        $validated['fire_extinguisher_count'] =
            $validated['fire_extinguisher_count'] ?? 0;

        $validated['lift_count'] =
            $validated['lift_count'] ?? 0;

        $validated['washroom_count'] =
            $validated['washroom_count'] ?? 0;

        $validated['total_rooms'] =
            $validated['total_rooms'] ?? 0;

        $validated['occupied_rooms'] =
            $validated['occupied_rooms'] ?? 0;

        $validated['total_capacity'] =
            $validated['total_capacity'] ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Calculate available rooms automatically
        |--------------------------------------------------------------------------
        */

        $validated['available_rooms'] =
            max(
                0,
                $validated['total_rooms'] -
                $validated['occupied_rooms']
            );


        /*
        |--------------------------------------------------------------------------
        | Default status
        |--------------------------------------------------------------------------
        */

        $validated['status'] =
            $validated['status'] ?? 'active';


        /*
        |--------------------------------------------------------------------------
        | JSON fields
        |--------------------------------------------------------------------------
        */

        $validated['additional_areas'] =
            $this->prepareJsonField(
                $request->input('additional_areas'),
                []
            );

        $validated['gates'] =
            $this->prepareJsonField(
                $request->input('gates'),
                []
            );

        $validated['custom_amenities'] =
            $this->prepareJsonField(
                $request->input('custom_amenities'),
                []
            );

        $validated['allocated_amenities'] =
            $this->prepareJsonField(
                $request->input('allocated_amenities'),
                []
            );

        $validated['allocated_facility_entries'] =
            $this->prepareJsonField(
                $request->input('allocated_facility_entries'),
                []
            );


        /*
        |--------------------------------------------------------------------------
        | Save floor
        |--------------------------------------------------------------------------
        */

        AddFloor::create($validated);

        return redirect()
            ->route('hostel.floors.index')
            ->with('success', 'Floor added successfully.');
    }

    private function prepareJsonField($value, $default = [])
    {
        if ($value === null || $value === '') {
            return json_encode($default);
        }

        if (is_array($value)) {
            return json_encode($value);
        }

        if (is_string($value)) {

            json_decode($value);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $value;
            }

            return json_encode([$value]);
        }

        return json_encode($default);
    }
}