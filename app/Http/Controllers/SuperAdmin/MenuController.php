<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class MenuController extends Controller
{
    /**
     * Display a listing of the menu items.
     */
    public function index(Request $request)
    {
        $menuItems = MenuItem::with('parent')
            ->orderBy('parent_id')
            ->orderBy('order')
            ->get();

        $statistics = [
            'total' => MenuItem::count(),
            'active' => MenuItem::where('is_active', 1)->count(),
            'inactive' => MenuItem::where('is_active', 0)->count(),
            'parents' => MenuItem::whereNull('parent_id')->count(),
            'children' => MenuItem::whereNotNull('parent_id')->count()
        ];

        return view('superadmin.MenuItems.index', compact('menuItems', 'statistics'));
    }

    /**
     * Show the form for creating a new menu item.
     */
    public function create()
    {
        $parentMenus = MenuItem::whereNull('parent_id')
            ->where('is_active', 1)
            ->orderBy('order')
            ->get();

        $roles = Role::all();

        return view('superadmin.MenuItems.create', compact('parentMenus', 'roles'));
    }

    /**
     * Store a newly created menu item in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'route' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'permission_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'allowed_roles' => 'nullable|array',
            'allowed_roles.*' => 'string|exists:roles,name'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();
            
            // Set default values
            $data['icon'] = $data['icon'] ?? 'fas fa-circle';
            $data['order'] = $data['order'] ?? 0;
            $data['is_active'] = isset($data['is_active']) ? 1 : 0;
            
            // Handle allowed_roles - convert array to JSON
            if ($request->has('allowed_roles') && is_array($request->allowed_roles)) {
                $data['allowed_roles'] = array_filter($request->allowed_roles);
            } else {
                $data['allowed_roles'] = null;
            }

            // Remove empty values
            $data = array_filter($data, function($value) {
                return $value !== '' && $value !== null;
            });

            $menuItem = MenuItem::create($data);

            // Reorder siblings if parent exists
            if ($request->has('parent_id') && $request->parent_id) {
                $this->reorderMenuItems($request->parent_id);
            }

            // Clear cache
            Cache::forget('menu_items');

            DB::commit();

            return redirect()
                ->route('superadmin.MenuItems.index')
                ->with('success', 'Menu item "' . $menuItem->name . '" created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create menu item: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified menu item.
     */
    public function show(MenuItem $menu)
    {
        $menu->load(['parent', 'children']);
        return view('superadmin.MenuItems.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified menu item.
     */
    public function edit(MenuItem $menu)
    {
        $parentMenus = MenuItem::whereNull('parent_id')
            ->where('id', '!=', $menu->id)
            ->where('is_active', 1)
            ->orderBy('order')
            ->get();

        $roles = Role::all();

        return view('superadmin.MenuItems.edit', compact('menu', 'parentMenus', 'roles'));
    }

    /**
     * Update the specified menu item in storage.
     */
    public function update(Request $request, MenuItem $menu)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'route' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id|not_in:' . $menu->id,
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'permission_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'allowed_roles' => 'nullable|array',
            'allowed_roles.*' => 'string|exists:roles,name'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();
            
            // Set default values
            $data['icon'] = $data['icon'] ?? 'fas fa-circle';
            $data['order'] = $data['order'] ?? 0;
            $data['is_active'] = isset($data['is_active']) ? 1 : 0;
            
            // Handle allowed_roles
            if ($request->has('allowed_roles') && is_array($request->allowed_roles)) {
                $data['allowed_roles'] = array_filter($request->allowed_roles);
            } else {
                $data['allowed_roles'] = null;
            }

            // Remove empty values
            $data = array_filter($data, function($value) {
                return $value !== '' && $value !== null;
            });

            // Store old parent_id to reorder both old and new parents
            $oldParentId = $menu->parent_id;
            $newParentId = $request->parent_id;

            $menu->update($data);

            // Reorder siblings if parent changed
            if ($oldParentId != $newParentId) {
                if ($oldParentId) {
                    $this->reorderMenuItems($oldParentId);
                }
                if ($newParentId) {
                    $this->reorderMenuItems($newParentId);
                }
            }

            // Clear cache
            Cache::forget('menu_items');

            DB::commit();

            return redirect()
                ->route('superadmin.MenuItems.index')
                ->with('success', 'Menu item "' . $menu->name . '" updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update menu item: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified menu item from storage.
     */
    public function destroy(MenuItem $menu)
    {
        try {
            DB::beginTransaction();

            $menuName = $menu->name;
            $parentId = $menu->parent_id;

            // Check if it has children
            if ($menu->children()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete "' . $menuName . '" because it has child menu items. Delete children first.');
            }

            $menu->delete();

            // Reorder siblings if parent exists
            if ($parentId) {
                $this->reorderMenuItems($parentId);
            }

            // Clear cache
            Cache::forget('menu_items');

            DB::commit();

            return redirect()
                ->route('superadmin.MenuItems.index')
                ->with('success', 'Menu item "' . $menuName . '" deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to delete menu item: ' . $e->getMessage());
        }
    }

    /**
     * Toggle menu item status (active/inactive).
     */
    public function toggleStatus(MenuItem $menu)
    {
        try {
            $menu->is_active = !$menu->is_active;
            $menu->save();

            // Clear cache
            Cache::forget('menu_items');

            $status = $menu->is_active ? 'activated' : 'deactivated';

            return response()->json([
                'success' => true,
                'message' => 'Menu item "' . $menu->name . '" ' . $status . ' successfully.',
                'status' => $menu->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle menu status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder menu items.
     */
    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.order' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

            foreach ($request->items as $itemData) {
                MenuItem::where('id', $itemData['id'])
                    ->update(['order' => $itemData['order']]);
            }

            // Clear cache
            Cache::forget('menu_items');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Menu items reordered successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder menu items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete menu items.
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:menu_items,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Check if any selected items have children
            $itemsWithChildren = MenuItem::whereIn('id', $request->ids)
                ->whereHas('children')
                ->pluck('name')
                ->toArray();

            if (!empty($itemsWithChildren)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete items that have children: ' . implode(', ', $itemsWithChildren)
                ], 422);
            }

            $parentIds = MenuItem::whereIn('id', $request->ids)
                ->whereNotNull('parent_id')
                ->pluck('parent_id')
                ->unique();

            MenuItem::whereIn('id', $request->ids)->delete();

            // Reorder siblings for affected parents
            foreach ($parentIds as $parentId) {
                $this->reorderMenuItems($parentId);
            }

            // Clear cache
            Cache::forget('menu_items');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' menu items deleted successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete menu items: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get menu items for sidebar (API endpoint).
     */
    public function getSidebarMenu()
    {
        try {
            $user = auth()->user();
            $userRoles = $user->roles()->pluck('name')->toArray();

            // Get menu items with permissions check
            $menuItems = MenuItem::with(['children' => function($query) use ($userRoles) {
                $query->where('is_active', 1)
                    ->orderBy('order')
                    ->where(function($q) use ($userRoles) {
                        $q->whereNull('allowed_roles')
                          ->orWhere(function($sub) use ($userRoles) {
                              foreach ($userRoles as $role) {
                                  $sub->orWhereJsonContains('allowed_roles', $role);
                              }
                          });
                    });
            }])
            ->whereNull('parent_id')
            ->where('is_active', 1)
            ->orderBy('order')
            ->where(function($query) use ($userRoles) {
                $query->whereNull('allowed_roles')
                      ->orWhere(function($sub) use ($userRoles) {
                          foreach ($userRoles as $role) {
                              $sub->orWhereJsonContains('allowed_roles', $role);
                          }
                      });
            })
            ->get();

            return response()->json([
                'success' => true,
                'data' => $menuItems
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch sidebar menu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Private method to reorder menu items within a parent.
     */
    private function reorderMenuItems($parentId)
    {
        $siblings = MenuItem::where('parent_id', $parentId)
            ->orderBy('order')
            ->get();

        $order = 0;
        foreach ($siblings as $sibling) {
            $sibling->update(['order' => $order++]);
        }
    }
}