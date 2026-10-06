<?php

namespace App\Http\Controllers\HostelManagement;

use App\Http\Controllers\Controller;
use App\Models\AddRooms;
use App\Models\AddBuilding;
use App\Models\AddBlock;
use App\Models\AddFloor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    /**
     * Display all rooms.
     */
    public function index()
    {
        $rooms = AddRooms::latest()->get();

        return view(
            'hostel_management_system.room.index',
            compact('rooms')
        );
    }

    /**
     * Show create room form.
     */
    public function create()
    {
        $buildings = AddBuilding::latest()->get();
        $blocks = AddBlock::latest()->get();
        $floors = AddFloor::latest()->get();

        return view(
            'hostel_management_system.room.create',
            compact('buildings', 'blocks', 'floors')
        );
    }

    /**
     * Store a new room.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'institute_id' => 'nullable|string|max:255',
            'branch_id' => 'nullable|string|max:255',

            'building_id' => 'required|string|max:255',
            'block_id' => 'required|string|max:255',
            'floor_id' => 'required|string|max:255',

            'floor_level' => 'nullable|string|max:255',

            'min_capacity' => 'nullable|string|max:255',

            /*
            |--------------------------------------------------------------------------
            | Room Number
            |--------------------------------------------------------------------------
            | Same room number cannot be used twice on the same floor.
            */

            'room_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('add_rooms', 'room_number')
                    ->where(function ($query) use ($request) {
                        return $query->where(
                            'floor_id',
                            $request->floor_id
                        );
                    }),
            ],

            'room_name' => 'nullable|string|max:255',

            'room_type' => 'required|string|max:255',
            'custom_room_type' => 'nullable|string|max:255',

            'description' => 'nullable|string',

            'area' => 'nullable|numeric|min:0',
            'area_type' => 'nullable|in:super,carpet',
            'capacity' => 'nullable|integer|min:0',

            'lights_count' => 'nullable|integer|min:0',
            'fans_count' => 'nullable|integer|min:0',
            'ac_count' => 'nullable|integer|min:0',
            'sockets_count' => 'nullable|integer|min:0',

            'has_fire_extinguisher' => 'nullable|boolean',
            'has_fire_alarm' => 'nullable|boolean',
            'has_smoke_detector' => 'nullable|boolean',
            'has_emergency_exit' => 'nullable|boolean',

            'has_washroom' => 'nullable|boolean',
            'washroom_gender' => 'nullable|in:male,female,unisex',
            'washroom_capacity' => 'nullable|integer|min:0',

            'has_projector' => 'nullable|boolean',
            'has_whiteboard' => 'nullable|boolean',
            'has_smart_board' => 'nullable|boolean',
            'has_wifi' => 'nullable|boolean',
            'has_water_cooler' => 'nullable|boolean',
            'has_cctv' => 'nullable|boolean',

            'has_ac' => 'nullable|string|max:255',

            'has_blackboard' => 'nullable|boolean',
            'has_chairs' => 'nullable|boolean',
            'has_tables' => 'nullable|boolean',
            'has_curtains' => 'nullable|boolean',
            'has_blinds' => 'nullable|boolean',
            'has_air_purifier' => 'nullable|boolean',
            'has_heater' => 'nullable|boolean',

            'status' => 'nullable|string|max:255',
            'occupancy_status' => 'nullable|string|max:255',

            'department' => 'nullable|string|max:255',
            'in_charge_name' => 'nullable|string|max:255',
            'in_charge_contact' => 'nullable|string|max:255',

            'special_notes' => 'nullable|string',

            'last_maintenance_date' => 'nullable|date',
            'next_maintenance_date' => 'nullable|date',
            'maintenance_notes' => 'nullable|string',

            'created_by' => 'nullable|string|max:255',
            'updated_by' => 'nullable|string|max:255',

            'occupancy_type' => 'nullable|string|max:255',
            'booking_required' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'usage_type' => 'nullable|string|max:255',
            'available_from' => 'nullable|string|max:255',
            'available_to' => 'nullable|string|max:255',

            'length' => 'nullable|string|max:255',
            'width' => 'nullable|string|max:255',
            'height' => 'nullable|string|max:255',

            'doors' => 'nullable|string|max:255',
            'windows' => 'nullable|string|max:255',

            'door_type' => 'nullable|string|max:255',
            'window_type' => 'nullable|string|max:255',
            'lighting_type' => 'nullable|string|max:255',
            'ac_type' => 'nullable|string|max:255',

            'has_lan' => 'nullable|string|max:255',
            'network_type' => 'nullable|string|max:255',
            'security_level' => 'nullable|string|max:255',

            'washroom_type' => 'nullable|string|max:255',

            'has_balcony' => 'nullable|string|max:255',
            'balcony_area' => 'nullable|string|max:255',
            'balcony_units' => 'nullable',

            'accessibility_level' => 'nullable|string|max:255',

        ], [

            /*
            |--------------------------------------------------------------------------
            | Custom Validation Messages
            |--------------------------------------------------------------------------
            */

            'room_number.required' =>
                'Room number is required.',

            'room_number.unique' =>
                'This room number is already given on this floor. Please choose another room number.',

            'building_id.required' =>
                'Please select a building.',

            'block_id.required' =>
                'Please select a block.',

            'floor_id.required' =>
                'Please select a floor.',

            'room_type.required' =>
                'Please select a room type.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Checkbox Values
        |--------------------------------------------------------------------------
        */

        $booleanFields = [
            'has_fire_extinguisher',
            'has_fire_alarm',
            'has_smoke_detector',
            'has_emergency_exit',
            'has_washroom',
            'has_projector',
            'has_whiteboard',
            'has_smart_board',
            'has_wifi',
            'has_water_cooler',
            'has_cctv',
            'has_blackboard',
            'has_chairs',
            'has_tables',
            'has_curtains',
            'has_blinds',
            'has_air_purifier',
            'has_heater',
        ];

        foreach ($booleanFields as $field) {
            $validated[$field] = $request->boolean($field);
        }


        /*
        |--------------------------------------------------------------------------
        | Default Values
        |--------------------------------------------------------------------------
        */

        $validated['lights_count'] =
            $validated['lights_count'] ?? 0;

        $validated['fans_count'] =
            $validated['fans_count'] ?? 0;

        $validated['ac_count'] =
            $validated['ac_count'] ?? 0;

        $validated['sockets_count'] =
            $validated['sockets_count'] ?? 0;

        $validated['status'] =
            $validated['status'] ?? 'active';

        $validated['occupancy_status'] =
            $validated['occupancy_status'] ?? 'vacant';

        $validated['area_type'] =
            $validated['area_type'] ?? 'super';


        /*
        |--------------------------------------------------------------------------
        | AC
        |--------------------------------------------------------------------------
        | Database column has_ac is VARCHAR.
        */

        $validated['has_ac'] =
            $request->input('has_ac', 'no');


        /*
        |--------------------------------------------------------------------------
        | JSON Fields
        |--------------------------------------------------------------------------
        */

        $jsonFields = [
            'selected_floor_amenities',
            'selected_floor_facilities',
            'additional_areas',
            'gates',
            'custom_amenities',
            'allocated_amenities',
            'room_specifications',
            'balcony_units',
        ];

        foreach ($jsonFields as $field) {

            $validated[$field] =
                $this->prepareJsonField(
                    $request->input($field),
                    []
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Room
        |--------------------------------------------------------------------------
        */

        AddRooms::create($validated);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('hostel.rooms.index')
            ->with(
                'success',
                'Room added successfully.'
            );
    }


    /**
     * Prepare JSON database fields.
     */
    private function prepareJsonField(
        $value,
        $default = []
    ) {
        /*
        |--------------------------------------------------------------------------
        | Empty value
        |--------------------------------------------------------------------------
        */

        if ($value === null || $value === '') {

            return json_encode($default);
        }


        /*
        |--------------------------------------------------------------------------
        | Array value
        |--------------------------------------------------------------------------
        */

        if (is_array($value)) {

            return json_encode($value);
        }


        /*
        |--------------------------------------------------------------------------
        | String value
        |--------------------------------------------------------------------------
        */

        if (is_string($value)) {

            json_decode($value);

            if (json_last_error() === JSON_ERROR_NONE) {

                return $value;
            }

            return json_encode([$value]);
        }


        /*
        |--------------------------------------------------------------------------
        | Default
        |--------------------------------------------------------------------------
        */

        return json_encode($default);
    }
}