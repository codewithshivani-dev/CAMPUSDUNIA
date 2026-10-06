<?php

namespace App\Http\Controllers\HostelManagement;

use App\Http\Controllers\Controller;
use App\Models\AddBuilding;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index()
    {
        $buildings = AddBuilding::latest()->get();

        return view(
            'hostel_management_system.building.index',
            compact('buildings')
        );
    }

    public function create()
    {
        return view('hostel_management_system.building.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'institute_id' => 'required|string|max:255',
            'branch_id' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'year_established' => 'nullable|integer|min:1900|max:' . date('Y'),
            'total_floors' => 'nullable|integer|min:0',
            'total_blocks' => 'nullable|integer|min:0',
            'total_rooms' => 'nullable|integer|min:0',
            'total_washrooms' => 'nullable|integer|min:0',
            'status' => 'nullable|in:active,inactive',
            'area_value' => 'nullable|numeric|min:0',
            'area_unit' => 'nullable|string|max:255',
            'photo' => 'nullable|string|max:255',
            'number_of_blocks' => 'nullable|integer|min:0',
        ]);

        $validated['total_floors'] = $validated['total_floors'] ?? 0;
        $validated['total_blocks'] = $validated['total_blocks'] ?? 0;
        $validated['total_rooms'] = $validated['total_rooms'] ?? 0;
        $validated['total_washrooms'] = $validated['total_washrooms'] ?? 0;
        $validated['status'] = $validated['status'] ?? 'active';

        AddBuilding::create($validated);

        return redirect()
            ->route('hostel.buildings.index')
            ->with('success', 'Building added successfully.');
    }
}