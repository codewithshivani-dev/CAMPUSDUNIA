<?php

namespace App\Http\Controllers\institute\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\InventoryUnit;
use App\Models\Inventory\InventoryCategory;
use App\Models\Inventory\InventorySubCategory;
use App\Models\Inventory\InventoryActivityLog;

class InventoryUnitController extends Controller
{
    private function instituteId()
    {
        return auth()->user()->institute_id;
    }

    public function index()
    {
        $units = InventoryUnit::with(['category', 'subcategory'])
            ->where('institute_id', $this->instituteId())
            ->latest()
            ->get();

        return view(
            'instituteAdmin.inventory.unit.index',
            compact('units')
        );
    }

    public function create()
    {
        // Get categories for dropdown
        $categories = InventoryCategory::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->get();

        // Get subcategories for dropdown (will be loaded via AJAX)
        $subcategories = collect();

        return view(
            'instituteAdmin.inventory.unit.create',
            compact('categories', 'subcategories')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => [
                'required',
                'exists:inventory_categories,id'
            ],
            'subcategory_id' => [
                'nullable',
                'exists:inventory_sub_categories,id'
            ],
            'unit_name' => [
                'required',
                'max:255',
                'unique:inventory_units,unit_name,NULL,id,institute_id,' . $this->instituteId()
            ],
            'unit_code' => [
                'required',
                'max:50',
                'unique:inventory_units,unit_code,NULL,id,institute_id,' . $this->instituteId()
            ]
        ]);

        $unit = InventoryUnit::create([
            'institute_id' => $this->instituteId(),
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'unit_name' => $request->unit_name,
            'unit_code' => strtoupper($request->unit_code),
            'description' => $request->description,
            'status' => $request->status ?? 1,
            'created_by' => auth()->id()
        ]);

        // Log the creation
        $this->logActivity('create', $unit->id, null, $unit->toArray(), 'Unit created');

        return redirect()
            ->route('inventory.units.index')
            ->with('success', 'Unit Created Successfully');
    }

    public function edit($id)
    {
        $unit = InventoryUnit::where('institute_id', $this->instituteId())
            ->with(['category', 'subcategory'])
            ->findOrFail($id);

        $categories = InventoryCategory::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->get();

        // Get subcategories for the selected category
        $subcategories = InventorySubCategory::where('category_id', $unit->category_id)
            ->where('status', 1)
            ->where('institute_id', $this->instituteId())
            ->get();

        return view(
            'instituteAdmin.inventory.unit.edit',
            compact('unit', 'categories', 'subcategories')
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => [
                'required',
                'exists:inventory_categories,id'
            ],
            'subcategory_id' => [
                'nullable',
                'exists:inventory_sub_categories,id'
            ],
            'unit_name' => [
                'required',
                'max:255',
                'unique:inventory_units,unit_name,' . $id . ',id,institute_id,' . $this->instituteId()
            ],
            'unit_code' => [
                'required',
                'max:50',
                'unique:inventory_units,unit_code,' . $id . ',id,institute_id,' . $this->instituteId()
            ]
        ]);

        $unit = InventoryUnit::where('institute_id', $this->instituteId())
            ->findOrFail($id);

        $oldData = $unit->toArray();

        $unit->update([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'unit_name' => $request->unit_name,
            'unit_code' => strtoupper($request->unit_code),
            'description' => $request->description,
            'status' => $request->status ?? 1,
            'updated_by' => auth()->id()
        ]);

        // Log the update
        $this->logActivity('update', $unit->id, $oldData, $unit->toArray(), 'Unit updated');

        return redirect()
            ->route('inventory.units.index')
            ->with('success', 'Unit Updated Successfully');
    }

    public function destroy($id)
    {
        $unit = InventoryUnit::where('institute_id', $this->instituteId())
            ->findOrFail($id);

        // Check if unit can be deleted
        if (!$unit->canBeDeleted()) {
            $blockers = $unit->getDeletionBlockers();
            $message = 'Cannot delete this unit because: ';
            $messages = [];
            
            foreach ($blockers as $blocker) {
                $messages[] = $blocker['message'];
            }
            
            return response()->json([
                'success' => false,
                'message' => $message . implode(', ', $messages)
            ], 422);
        }

        $oldData = $unit->toArray();

        // Log the deletion
        $this->logActivity('delete', $unit->id, $oldData, null, 'Unit deleted');

        $unit->update(['deleted_by' => auth()->id()]);
        $unit->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function logs($id)
    {
        $unit = InventoryUnit::where('institute_id', $this->instituteId())
            ->with(['category', 'subcategory'])
            ->findOrFail($id);

        $logs = InventoryActivityLog::where('institute_id', $this->instituteId())
            ->where('module', 'unit')
            ->where('record_id', $unit->id)
            ->with('user')
            ->latest()
            ->paginate(10);

        return view(
            'instituteAdmin.inventory.unit.log',
            compact('unit', 'logs')
        );
    }

    /**
     * Log activity for unit
     */
    private function logActivity($action, $recordId, $oldData = null, $newData = null, $title = null)
    {
        InventoryActivityLog::create([
            'institute_id' => $this->instituteId(),
            'module' => 'unit',
            'action' => $action,
            'record_id' => $recordId,
            'old_data' => $oldData,
            'new_data' => $newData,
            'title' => $title,
            'created_by' => auth()->id()
        ]);
    }
}