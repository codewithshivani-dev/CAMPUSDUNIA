<?php
// app/Helpers/MenuHelper.php

namespace App\Helpers;

use App\Models\MenuItem;
use App\Models\EmployeeDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class MenuHelper
{
    /**
     * Get menu items for current user
     */
    public static function getUserMenu()
    {
        $user = Auth::user();
        Log::info($user);
        // Superadmin sees everything
        if ($user->hasRole('superadmin')) {
            return MenuItem::root()
                ->active()
                ->with(['children' => function($query) {
                    $query->active()->orderBy('order');
                }])
                ->orderBy('order')
                ->get();
        }

        // Admin sees admin items by default
        if ($user->hasRole('admin')) {
            return MenuItem::root()
                ->active()
                ->where(function($query) {
                    $query->whereJsonContains('allowed_roles', 'admin')
                          ->orWhereJsonContains('allowed_roles', 'superadmin');
                })
                ->with(['children' => function($query) {
                    $query->active()->orderBy('order');
                }])
                ->orderBy('order')
                ->get();
        }

        // Employees get their assigned responsibilities
        if ($user->hasRole('employee')) {
            // // Only for employee role

            // // Get employee details
            // $employee = EmployeeDetails::where('user_id', $user->id)->first();

            // if (! $employee) {
            //     return collect();
            // }

            // // Fetch assigned menu items via pivot
            // $assignedItems = $employee->responsibilities()
            //     ->wherePivot('can_view', 1)
            //     ->active()
            //     ->orderBy('order')
            //     ->get();

            // // ✅ Fallback to role-based menu if no assignments
            // if ($assignedItems->isEmpty()) {
            //     return MenuItem::root()
            //         ->active()
            //         ->whereJsonContains('allowed_roles', 'employee')
            //         ->with(['children' => function ($query) {
            //             $query->active()->orderBy('order');
            //         }])
            //         ->orderBy('order')
            //         ->get();
            // }

            // // Build menu tree (parent → children)
            // dd($assignedItems);
            // $rootItems = $assignedItems
            //     ->whereNull('parent_id')
            //     ->values();
            // dd($rootItems);
            // foreach ($rootItems as $rootItem) {
            //     $rootItem->children = $assignedItems
            //         ->where('parent_id', $rootItem->id)
            //         ->values();
            // }

            // return $rootItems;
            
        if (! $user->hasRole('employee')) {
            return collect();
        }

        $employee = EmployeeDetails::where('user_id', $user->id)->first();

        if (! $employee) {
            return collect();
        }

        // 1️⃣ Fetch assigned menu items (usually children)
        $assignedItems = $employee->responsibilities()
            ->wherePivot('can_view', 1)
            ->active()
            ->orderBy('order')
            ->get();

        // Fallback if nothing assigned
        if ($assignedItems->isEmpty()) {
            return MenuItem::whereNull('parent_id')
                ->active()
                ->whereJsonContains('allowed_roles', 'employee')
                ->with(['children' => function ($q) {
                    $q->active()->orderBy('order');
                }])
                ->orderBy('order')
                ->get();
        }

        // 2️⃣ Collect parent IDs from assigned menus
        $parentIds = $assignedItems
            ->pluck('parent_id')
            ->filter()        // remove nulls
            ->unique()
            ->values();

        // 3️⃣ Fetch missing parent menu items
        $parentItems = MenuItem::whereIn('id', $parentIds)
            ->active()
            ->get();

        // 4️⃣ Merge parent + child menus
        $allMenus = $assignedItems
            ->merge($parentItems)
            ->unique('id');

        // 5️⃣ Build recursive menu tree (INLINE)
        $buildTree = function ($items, $parentId = null) use (&$buildTree) {
            return $items
                ->where('parent_id', $parentId)
                ->sortBy('order')
                ->values()
                ->map(function ($item) use ($items, &$buildTree) {
                    $item->children = $buildTree($items, $item->id);
                    return $item;
                });
        };

        return $buildTree($allMenus);
    }

        // Students see student items
        if ($user->hasRole('student')) {
            return MenuItem::root()
                ->active()
                ->whereJsonContains('allowed_roles', 'student')
                ->with(['children' => function($query) {
                    $query->active()->orderBy('order');
                }])
                ->orderBy('order')
                ->get();
        }

        return collect([]);
    }

    /**
     * Get menu for specific employee
     */
    public static function getEmployeeMenu(EmployeeDetails $employee)
    {
        // Log::info($employee);
        $assignedItems = $this->getAccessibleMenuItems($employee);
        Log::info($assignedItems);
        if ($assignedItems->isEmpty()) {
            // Return default employee menu if no specific assignments
            return MenuItem::root()
                ->active()
                ->whereJsonContains('allowed_roles', 'employee')
                ->with(['children' => function($query) {
                    $query->active()->orderBy('order');
                }])
                ->orderBy('order')
                ->get();
        }

        // Get root items from assigned items
        $rootItems = $assignedItems->filter(function ($item) {
            return is_null($item->parent_id);
        })->sortBy('order');

        // Add children to root items
        foreach ($rootItems as $rootItem) {
            $rootItem->children = $assignedItems->filter(function ($item) use ($rootItem) {
                return $item->parent_id == $rootItem->id;
            })->sortBy('order');
        }

        return $rootItems;
    }

    /**
     * Render menu HTML
     */
    public static function renderMenu($menuItems)
    {
        $html = '';
        
        foreach ($menuItems as $item) {
            if ($item->hasChildren()) {
                $html .= self::renderDropdown($item);
            } else {
                $html .= self::renderSingleItem($item);
            }
        }
        
        return $html;
    }

    /**
     * Render single menu item
     */
    private static function renderSingleItem($item)
    {
        $activeClass = self::isActive($item) ? 'active' : '';
        $url = $item->route ? url($item->route) : '#';
        
        return '
        <li class="nav-item">
            <a class="nav-link navitem ' . $activeClass . '" href="' . $url . '">
                <i class="' . $item->icon . '"></i>
                <span>' . e($item->name) . '</span>
            </a>
        </li>';
    }

    /**
     * Render dropdown menu item
     */
    private static function renderDropdown($item)
    {
        $isActive = self::isDropdownActive($item);
        $showClass = $isActive ? 'show' : '';
        $activeClass = $isActive ? 'active' : '';
        $expanded = $isActive ? 'true' : 'false';
        
        $html = '
        <li class="nav-item">
            <a class="nav-link navitem collapsed ' . $activeClass . '" href="#" 
               data-toggle="collapse" data-target="#collapse' . $item->id . '" 
               aria-expanded="' . $expanded . '" 
               aria-controls="collapse' . $item->id . '">
                <i class="' . $item->icon . '"></i>
                <span>' . e($item->name) . '</span>
            </a>
            <div id="collapse' . $item->id . '" class="collapse ' . $showClass . '" 
                 data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">';
        
        foreach ($item->children as $child) {
            $childActive = self::isActive($child) ? 'active' : '';
            $childUrl = $child->route ? url($child->route) : '#';
            $html .= '<a class="collapse-item ' . $childActive . '" href="' . $childUrl . '">' . e($child->name) . '</a>';
        }
        
        $html .= '</div></div></li>';
        
        return $html;
    }

    /**
     * Check if menu item is active
     */
    private static function isActive($item)
    {
        if (!$item->route) {
            return false;
        }
        
        $currentPath = request()->path();
        $itemPath = ltrim(parse_url($item->route, PHP_URL_PATH), '/');
        
        return $currentPath === $itemPath || str_starts_with($currentPath, $itemPath . '/');
    }

    /**
     * Check if dropdown should be active
     */
    private static function isDropdownActive($item)
    {
        foreach ($item->children as $child) {
            if (self::isActive($child)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has permission to access menu item
     */
    public static function canAccess($permissionName, $employee = null)
    {
        $user = Auth::user();
        
        if ($user->hasRole('superadmin')) {
            return true;
        }

        if (!$employee && $user->employee) {
            $employee = $user->employee;
        }

        if ($employee) {
            $menuItem = MenuItem::where('permission_name', $permissionName)->first();
            
            if ($menuItem) {
                return $employee->hasMenuItemAccess($menuItem->id);
            }
        }

        return false;
    }
}