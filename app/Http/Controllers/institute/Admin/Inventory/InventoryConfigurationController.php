<?php

namespace App\Http\Controllers\institute\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Inventory\InventoryConfiguration;
use App\Models\Inventory\InventoryActivityLog;

class InventoryConfigurationController extends Controller
{
    private function instituteId()
    {
        return auth()->user()->institute_id;
    }

    private function generateConfigurationCode()
    {
        $prefix = 'INVCNF';
        $random = strtoupper(Str::random(6));
        $code = $prefix . '-' . $random;

        while (InventoryConfiguration::where('configuration_code', $code)->exists()) {
            $random = strtoupper(Str::random(6));
            $code = $prefix . '-' . $random;
        }

        return $code;
    }

    private function logActivity($action, $recordId, $oldData = null, $newData = null, $remarks = null)
    {
        InventoryActivityLog::create([
            'institute_id' => $this->instituteId(),
            'module' => 'inventory_configuration',
            'action' => $action,
            'record_id' => $recordId,
            'old_data' => $oldData,
            'new_data' => $newData,
            'remarks' => $remarks,
            'created_by' => auth()->id()
        ]);
    }

    public function index()
    {
        $configurations = InventoryConfiguration::where('institute_id', $this->instituteId())
            ->latest()
            ->get();

        return view('instituteAdmin.inventory.configuration.list', compact('configurations'));
    }

    public function create()
    {
        return view('instituteAdmin.inventory.configuration.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'configuration_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-_]+$/',
                'unique:inventory_configurations,configuration_name,NULL,id,institute_id,' . $this->instituteId()
            ],
            'costing_method' => 'required|in:FIFO,LIFO,WEIGHTED_AVERAGE',
        ]);

        $configuration = InventoryConfiguration::create([
            'institute_id' => $this->instituteId(),
            'configuration_name' => $request->configuration_name,
            'configuration_code' => $this->generateConfigurationCode(),
            'multi_warehouse' => $request->multi_warehouse ?? 0,
            'barcode_enabled' => $request->barcode_enabled ?? 0,
            'qr_enabled' => $request->qr_enabled ?? 0,
            'batch_tracking' => $request->batch_tracking ?? 0,
            'serial_tracking' => $request->serial_tracking ?? 0,
            'costing_method' => $request->costing_method,
            'is_configured' => 1,
            'status' => 'active',
            'created_by' => auth()->id()
        ]);

        $this->logActivity('created', $configuration->id, null, $configuration->toArray());

        return redirect()
            ->route('inventory.configuration')
            ->with('success', 'Configuration created successfully.');
    }

    public function view($id)
    {
        try {
            $configuration = InventoryConfiguration::where([
                'id' => $id,
                'institute_id' => $this->instituteId()
            ])->firstOrFail();

            return view('instituteAdmin.inventory.configuration.view', compact('configuration'));
        } catch (\Exception $e) {
            return redirect()->route('inventory.configuration')
                ->with('error', 'Configuration not found.');
        }
    }

    public function edit($id)
    {
        $configuration = InventoryConfiguration::where([
            'id' => $id,
            'institute_id' => $this->instituteId()
        ])->firstOrFail();

        return view('instituteAdmin.inventory.configuration.edit', compact('configuration'));
    }

    public function update(Request $request, $id)
    {
        $configuration = InventoryConfiguration::where([
            'id' => $id,
            'institute_id' => $this->instituteId()
        ])->firstOrFail();

        $request->validate([
            'configuration_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-_]+$/',
                'unique:inventory_configurations,configuration_name,' . $id . ',id,institute_id,' . $this->instituteId()
            ],
            'costing_method' => 'required|in:FIFO,LIFO,WEIGHTED_AVERAGE',
        ]);

        $oldData = $configuration->toArray();

        $configuration->update([
            'configuration_name' => $request->configuration_name,
            'multi_warehouse' => $request->multi_warehouse ?? 0,
            'barcode_enabled' => $request->barcode_enabled ?? 0,
            'qr_enabled' => $request->qr_enabled ?? 0,
            'batch_tracking' => $request->batch_tracking ?? 0,
            'serial_tracking' => $request->serial_tracking ?? 0,
            'costing_method' => $request->costing_method,
            'is_configured' => $request->is_configured ?? 1,
            'updated_by' => auth()->id()
        ]);

        $this->logActivity('updated', $configuration->id, $oldData, $configuration->fresh()->toArray());

        return redirect()
            ->route('inventory.configuration')
            ->with('success', 'Configuration updated successfully.');
    }

    /**
     * Toggle configuration status (Active/Inactive)
     */
    public function toggleStatus($id)
    {
        $configuration = InventoryConfiguration::where([
            'id' => $id,
            'institute_id' => $this->instituteId()
        ])->firstOrFail();

        $oldData = $configuration->toArray();

        // Toggle status
        $newStatus = $configuration->status === 'active' ? 'inactive' : 'active';
        $configuration->update([
            'status' => $newStatus,
            'updated_by' => auth()->id()
        ]);

        $this->logActivity('status_toggle', $configuration->id, $oldData, $configuration->fresh()->toArray(), 
            'Configuration status changed from ' . $oldData['status'] . ' to ' . $newStatus
        );

        $message = $newStatus === 'active' 
            ? 'Configuration activated successfully.' 
            : 'Configuration deactivated successfully.';

        return redirect()
            ->route('inventory.configuration')
            ->with('success', $message);
    }

    /**
     * Check if configuration can be deleted (for UI)
     */
    public function checkDelete($id)
    {
        $configuration = InventoryConfiguration::where([
            'id' => $id,
            'institute_id' => $this->instituteId()
        ])->firstOrFail();

        $canDelete = $configuration->canBeDeleted();
        $blockers = $canDelete ? [] : $configuration->getDeletionBlockers();

        return response()->json([
            'can_delete' => $canDelete,
            'blockers' => $blockers
        ]);
    }

    public function destroy($id)
    {
        $configuration = InventoryConfiguration::where([
            'id' => $id,
            'institute_id' => $this->instituteId()
        ])->firstOrFail();

        $oldData = $configuration->toArray();

        if (!$configuration->canBeDeleted()) {
            $blockers = $configuration->getDeletionBlockers();
            $messages = collect($blockers)->pluck('message')->implode(', ');

            return redirect()
                ->route('inventory.configuration')
                ->with('error', "Cannot delete this configuration: {$messages}.");
        }

        $this->logActivity('deleted', $configuration->id, $oldData, null, 'Configuration deleted');

        $configuration->delete();

        return redirect()
            ->route('inventory.configuration')
            ->with('success', 'Configuration deleted successfully.');
    }

    public function logs($id)
    {
        $configuration = InventoryConfiguration::where([
            'id' => $id,
            'institute_id' => $this->instituteId()
        ])->firstOrFail();

        $logs = InventoryActivityLog::where([
            'institute_id' => $this->instituteId(),
            'module' => 'inventory_configuration',
            'record_id' => $id
        ])
        ->with('user')
        ->latest()
        ->paginate(20);

        return view('instituteAdmin.inventory.configuration.logs', compact('configuration', 'logs'));
    }
}