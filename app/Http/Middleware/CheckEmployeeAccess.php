<?php
// app/Http/Middleware/CheckEmployeeAccess.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Helpers\MenuHelper;
use Illuminate\Support\Facades\Auth;

class CheckEmployeeAccess
{
    public function handle(Request $request, Closure $next, $permission = null)
    {
        $user = Auth::user();
        // Allow superadmin to access everything
        if ($user->hasRole('superadmin')) {
            return $next($request);
        }

        // For admin, check if route is in admin menu
        if ($user->hasRole('admin')) {
            $currentRoute = $request->route()->getName() ?: $request->path();
            $menuItem = MenuItem::where('route', 'like', '%' . $currentRoute . '%')->first();
            if ($menuItem && in_array('admin', $menuItem->allowed_roles ?? [])) {
                return $next($request);
            }
        }

        // For employees, check assigned responsibilities
        if ($user->hasRole('employee') && $user->employee) {
            $currentPath = $request->path();
            
            // Find menu item for current route
            $menuItem = MenuItem::where('route', 'like', '%' . $currentPath . '%')->first();
            
            if ($menuItem && $user->employee->hasMenuItemAccess($menuItem->id)) {
                // Check specific permission if provided
                if ($permission) {
                    if ($user->employee->hasMenuPermission($menuItem->id, $permission)) {
                        return $next($request);
                    }
                } else {
                    return $next($request);
                }
            }
        }

        // For students
        if ($user->hasRole('student')) {
            $currentPath = $request->path();
            $menuItem = MenuItem::where('route', 'like', '%' . $currentPath . '%')->first();
            
            if ($menuItem && in_array('student', $menuItem->allowed_roles ?? [])) {
                return $next($request);
            }
        }

        // If no access, redirect to dashboard with error
        return redirect()->route('dashboard')
            ->with('error', 'You do not have permission to access this page.');
    }
}