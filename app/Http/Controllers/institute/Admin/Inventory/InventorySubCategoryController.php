<?php

namespace App\Http\Controllers\institute\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\InventoryCategory;
use App\Models\Inventory\InventorySubCategory;
use App\Services\Inventory\InventoryLogger;

class InventorySubCategoryController extends Controller
{
    private function instituteId()
    {
        return auth()->user()->institute_id;
    }

    public function index()
    {
        $subcategories = InventorySubCategory::with(['category'])
            ->where('institute_id', $this->instituteId())
            ->latest()
            ->get();

        return view('instituteAdmin.inventory.subcategory.index', compact('subcategories'));
    }

    public function create()
    {
        $categories = InventoryCategory::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->get();

        return view('instituteAdmin.inventory.subcategory.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:inventory_categories,id',
            'subcategory_name' => [
                'required',
                'max:255',
                'unique:inventory_sub_categories,subcategory_name,NULL,id,institute_id,' . $this->instituteId()
            ],
            'subcategory_code' => [
                'required',
                'max:50',
                'unique:inventory_sub_categories,subcategory_code,NULL,id,institute_id,' . $this->instituteId()
            ],
        ]);

        $subcategory = InventorySubCategory::create([
            'institute_id' => $this->instituteId(),
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_code' => strtoupper($request->subcategory_code),
            'description' => $request->description,
            'status' => $request->status ?? 1,
            'created_by' => auth()->id()
        ]);

        InventoryLogger::log([
            'module' => 'inventory_subcategory',
            'action' => 'CREATE',
            'record_id' => $subcategory->id,
            'new_data' => $subcategory->toArray(),
            'remarks' => 'Subcategory created: ' . $subcategory->subcategory_name
        ]);

        return redirect()
            ->route('inventory.subcategories.index')
            ->with('success', 'Sub Category Created Successfully');
    }

    public function edit($id)
    {
        $subcategory = InventorySubCategory::where('institute_id', $this->instituteId())
            ->with(['category'])
            ->findOrFail($id);

        $categories = InventoryCategory::where('institute_id', $this->instituteId())
            ->where('status', 1)
            ->get();

        return view('instituteAdmin.inventory.subcategory.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $subcategory = InventorySubCategory::where('institute_id', $this->instituteId())->findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:inventory_categories,id',
            'subcategory_name' => [
                'required',
                'max:255',
                'unique:inventory_sub_categories,subcategory_name,' . $id . ',id,institute_id,' . $this->instituteId()
            ],
            'subcategory_code' => [
                'required',
                'max:50',
                'unique:inventory_sub_categories,subcategory_code,' . $id . ',id,institute_id,' . $this->instituteId()
            ],
        ]);

        $oldData = $subcategory->toArray();

        $subcategory->update([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_code' => strtoupper($request->subcategory_code),
            'description' => $request->description,
            'status' => $request->status ?? 1,
            'updated_by' => auth()->id()
        ]);

        InventoryLogger::log([
            'module' => 'inventory_subcategory',
            'action' => 'UPDATE',
            'record_id' => $subcategory->id,
            'old_data' => $oldData,
            'new_data' => $subcategory->fresh()->toArray(),
            'remarks' => 'Subcategory updated: ' . $subcategory->subcategory_name
        ]);

        return redirect()
            ->route('inventory.subcategories.index')
            ->with('success', 'Sub Category Updated Successfully');
    }

    public function destroy($id)
    {
        $subcategory = InventorySubCategory::where('institute_id', $this->instituteId())->findOrFail($id);

        if (!$subcategory->canBeDeleted()) {
            $blockers = $subcategory->getDeletionBlockers();
            $messages = [];

            foreach ($blockers as $blocker) {
                $messages[] = $blocker['message'];
            }

            return response()->json([
                'success' => false,
                'message' => 'Cannot delete this subcategory: ' . implode(', ', $messages),
                'blockers' => $blockers
            ], 422);
        }

        InventoryLogger::log([
            'module' => 'inventory_subcategory',
            'action' => 'DELETE',
            'record_id' => $subcategory->id,
            'old_data' => $subcategory->toArray(),
            'remarks' => 'Subcategory deleted: ' . $subcategory->subcategory_name
        ]);

        $subcategory->update(['deleted_by' => auth()->id()]);
        $subcategory->delete();

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Get subcategories by category ID (for AJAX)
     */
    public function getByCategory($categoryId)
    {
        $subcategories = InventorySubCategory::where('category_id', $categoryId)
            ->where('status', 1)
            ->where('institute_id', $this->instituteId())
            ->get(['id', 'subcategory_name', 'subcategory_code']);

        return response()->json($subcategories);
    }
}