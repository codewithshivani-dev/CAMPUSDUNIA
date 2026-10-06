<?php
// app/Http\Controllers/Admin/EmployeeResponsibilityController.php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDetails;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeResponsibilityController extends Controller
{
    /**
     * Show form to assign responsibilities
     */
    // public function assignForm($employeeId)
    // {
    //     $employee = EmployeeDetails::with(['responsibilities' => function($query) {
    //         $query->withPivot(['can_view', 'can_create', 'can_edit', 'can_delete']);
    //     }])->findOrFail($employeeId);
    //     dd($employee);
    //     $menuItems = MenuItem::root()
    //         ->active()
    //         ->with(['children' => function($query) {
    //             $query->active()->orderBy('order');
    //         }])
    //         ->orderBy('order')
    //         ->get();

    //     return view('instituteAdmin.EmployeeFiles.assign-responsibilities', compact('employee', 'menuItems'));
    // }

    public function assignForm($employeeId)
    {
        
        // Make sure you're using the correct relationship name
        $employee = EmployeeDetails::with(['responsibilities' => function($query) {
            $query->withPivot(['can_view', 'can_create', 'can_edit', 'can_delete']);
        }])->where('employee_id', $employeeId)->first();
        // Debug to see what you're getting
        // dd($employee->responsibilities);

        $menuItemsdata = MenuItem::root()
            ->active()
            ->with(['children' => function($query) {
                $query->active()->orderBy('order');
            }])
            ->orderBy('order')
            ->get();
        return view('instituteAdmin.EmployeeFiles.assign-responsibilities', compact('employee', 'menuItemsdata'));
    }

    /**
     * Assign responsibilities to employee
     */
    public function assign(Request $request, $employeeId)
    {
        $request->validate([
            'responsibilities' => 'nullable|array',
            'responsibilities.*' => 'exists:menu_items,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'array:view,create,edit,delete'
        ]);

        $employee = EmployeeDetails::findOrFail($employeeId);

        DB::transaction(function () use ($employee, $request) {
            $syncData = [];
            $responsibilities = $request->responsibilities ?? [];

            // Prepare sync data
            foreach ($responsibilities as $menuItemId) {
                $permissions = $request->permissions[$menuItemId] ?? [
                    'view' => true,
                    'create' => false,
                    'edit' => false,
                    'delete' => false
                ];

                $syncData[$menuItemId] = [
                    'can_view' => $permissions['view'] ?? false,
                    'can_create' => $permissions['create'] ?? false,
                    'can_edit' => $permissions['edit'] ?? false,
                    'can_delete' => $permissions['delete'] ?? false
                ];
            }

            // Sync responsibilities
            $employee->responsibilities()->sync($syncData);

            // Update cache
            $employee->update([
                'assigned_responsibilities' => array_keys($syncData),
                'menu_permissions' => $syncData
            ]);

            // Log the assignment
            activity()
                ->causedBy(auth()->user())
                ->performedOn($employee)
                ->withProperties([
                    'responsibilities' => array_keys($syncData),
                    'permissions' => $syncData
                ])
                ->log('assigned responsibilities');
        });

        return redirect()->back()
            ->with('success', 'Responsibilities assigned successfully.');
    }

    /**
     * Get employee's menu for preview
     */
    public function getEmployeeMenu($employeeId)
    {
       
        $employee = EmployeeDetails::findOrFail($employeeId);
        $menuItems = $employee->getAccessibleMenuItems();

        return view('partials.dynamic-sidebar', compact('menuItems'));
    }

    /**
     * Get all menu items with hierarchy
     */
    public function getAllMenuItems()
    {
        $menuItems = MenuItem::root()
            ->active()
            ->with(['allChildren'])
            ->orderBy('order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $menuItems
        ]);
    }

    /**
     * Get employee's current responsibilities
     */
    public function getEmployeeResponsibilities($employeeId)
    {
        $employee = EmployeeDetails::with('responsibilities')->findOrFail($employeeId);

        $responsibilities = $employee->responsibilities->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'permissions' => [
                    'view' => (bool) $item->pivot->can_view,
                    'create' => (bool) $item->pivot->can_create,
                    'edit' => (bool) $item->pivot->can_edit,
                    'delete' => (bool) $item->pivot->can_delete
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $responsibilities
        ]);
    }

    /**
     * Reset employee responsibilities to default
     */
    public function resetToDefault($employeeId)
    {
        $employee = EmployeeDetails::findOrFail($employeeId);

        // Get default menu items for employee role
        $defaultItems = MenuItem::whereJsonContains('allowed_roles', 'employee')
            ->orWhere(function($query) {
                $query->whereJsonContains('allowed_roles', 'admin')
                      ->whereJsonContains('allowed_roles', 'superadmin');
            })
            ->pluck('id')
            ->toArray();

        DB::transaction(function () use ($employee, $defaultItems) {
            $syncData = [];
            
            foreach ($defaultItems as $itemId) {
                $syncData[$itemId] = [
                    'can_view' => true,
                    'can_create' => false,
                    'can_edit' => false,
                    'can_delete' => false
                ];
            }

            $employee->responsibilities()->sync($syncData);
            $employee->update([
                'assigned_responsibilities' => $defaultItems,
                'menu_permissions' => $syncData
            ]);

            activity()
                ->causedBy(auth()->user())
                ->performedOn($employee)
                ->log('reset responsibilities to default');
        });

        return redirect()->back()
            ->with('success', 'Responsibilities reset to default successfully.');
    }
}