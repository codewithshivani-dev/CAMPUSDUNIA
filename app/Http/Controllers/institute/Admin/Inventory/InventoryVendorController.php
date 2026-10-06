<?php

namespace App\Http\Controllers\institute\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\InventoryVendor;
use Illuminate\Support\Str;

class InventoryVendorController extends Controller
{
    private function instituteId()
    {
        return auth()->user()->institute_id;
    }

    private function branchId()
    {
        return auth()->user()->branch_id ?? session('current_branch_id');
    }

    public function index()
    {
        $vendors = InventoryVendor::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->orderBy('vendor_name')
            ->paginate(20);

        return view('instituteAdmin.inventory.vendor.index', compact('vendors'));
    }

    public function create()
    {
        return view('instituteAdmin.inventory.vendor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_name' => 'required|string|max:255',
            'vendor_code' => 'nullable|string|max:50|unique:inventory_vendors,vendor_code',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'gst_number' => 'nullable|string|max:50',
        ]);

        $vendor = InventoryVendor::create([
            'institute_id' => $this->instituteId(),
            'branch_id' => $this->branchId(),
            'vendor_name' => $request->vendor_name,
            'vendor_code' => $request->vendor_code ?? 'VEN-' . Str::random(6),
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'gst_number' => $request->gst_number,
            'status' => 1,
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('inventory.vendors.index')
            ->with('success', 'Vendor created successfully!');
    }

    public function edit($id)
    {
        $vendor = InventoryVendor::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->findOrFail($id);

        return view('instituteAdmin.inventory.vendor.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $vendor = InventoryVendor::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->findOrFail($id);

        $request->validate([
            'vendor_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'gst_number' => 'nullable|string|max:50',
            'status' => 'nullable|boolean',
        ]);

        $vendor->update([
            'vendor_name' => $request->vendor_name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'gst_number' => $request->gst_number,
            'status' => $request->status ?? 1,
            'updated_by' => auth()->id(),
        ]);

        return redirect()
            ->route('inventory.vendors.index')
            ->with('success', 'Vendor updated successfully!');
    }

    public function destroy($id)
    {
        $vendor = InventoryVendor::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->findOrFail($id);

        if (!$vendor->canBeDeleted()) {
            return back()->with('error', 'Cannot delete vendor with associated items.');
        }

        $vendor->delete();

        return redirect()
            ->route('inventory.vendors.index')
            ->with('success', 'Vendor deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $vendor = InventoryVendor::where('institute_id', $this->instituteId())
            ->where('branch_id', $this->branchId())
            ->findOrFail($id);

        $vendor->update([
            'status' => !$vendor->status,
            'updated_by' => auth()->id(),
        ]);

        return redirect()
            ->route('inventory.vendors.index')
            ->with('success', 'Vendor status updated successfully!');
    }
}