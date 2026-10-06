<?php

namespace App\Http\Controllers\HostelManagement;

use App\Http\Controllers\Controller;
use App\Models\AddBlock;
use App\Models\AddBuilding;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function index()
    {
        $blocks = AddBlock::latest()->get();

        return view(
            'hostel_management_system.block.index',
            compact('blocks')
        );
    }

    public function create()
    {
        $buildings = AddBuilding::latest()->get();

        return view(
            'hostel_management_system.block.create',
            compact('buildings')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'institute_id' => 'required|string|max:255',
            'branch_id' => 'required|string|max:255',
            'building_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
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

            'additional_areas' => 'nullable',
            'gates' => 'nullable',
            'custom_amenities' => 'nullable',
            'allocated_amenities' => 'nullable',
            'allocated_facilities' => 'nullable',
        ]);

        $validated['total_floors'] = $validated['total_floors'] ?? 0;
        $validated['total_rooms'] = $validated['total_rooms'] ?? 0;
        $validated['total_capacity'] = $validated['total_capacity'] ?? 0;
        $validated['total_washrooms'] = $validated['total_washrooms'] ?? 0;

        $validated['has_lift'] = $request->boolean('has_lift');
        $validated['lift_count'] = $validated['lift_count'] ?? 0;

        $validated['has_fire_safety'] = $request->boolean('has_fire_safety');
        $validated['has_disabled_access'] = $request->boolean('has_disabled_access');
        $validated['has_security_system'] = $request->boolean('has_security_system');

        $validated['status'] = $validated['status'] ?? 'active';

        $validated['additional_areas'] = $this->prepareJsonField(
            $request->input('additional_areas'),
            []
        );

        $validated['gates'] = $this->prepareJsonField(
            $request->input('gates'),
            []
        );

        $validated['custom_amenities'] = $this->prepareJsonField(
            $request->input('custom_amenities'),
            []
        );

        $validated['allocated_amenities'] = $this->prepareJsonField(
            $request->input('allocated_amenities'),
            []
        );

        $validated['allocated_facilities'] = $this->prepareJsonField(
            $request->input('allocated_facilities'),
            []
        );

        AddBlock::create($validated);

        return redirect()
            ->route('hostel.blocks.index')
            ->with('success', 'Block added successfully.');
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