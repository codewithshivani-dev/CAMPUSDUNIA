<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TransportRoute;
use App\Models\TransportDetails;
use Illuminate\Support\Facades\Validator;

class TransportRouteController extends Controller
{
    use \App\Traits\InstituteBranchAccess;

    /**
     * Display a listing of routes
     */
    public function index()
    {
        $instituteId = Auth::user()->institute_id;
        
        // Get routes with buses count and also get active buses count
        $routes = TransportRoute::where('institute_id', $instituteId)
            ->withCount(['buses', 'activeBuses'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('instituteAdmin.DashboardFiles.AllTransportRoutes', compact('routes'));
    }

    /**
     * Show form to create a new route
     */
    public function create()
    {
        return view('instituteAdmin.DashboardFiles.AddTransportRoute');
    }

    /**
     * Store a newly created route
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'route_name' => 'required|string|max:255|unique:routes,route_name,NULL,id,institute_id,' . Auth::user()->institute_id,
            'description' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $instituteId = Auth::user()->institute_id;
        
        // Generate route reference ID
        $routeReferenceId = TransportRoute::generateReferenceId($instituteId);
        
        // Create route
        $route = TransportRoute::create([
            'institute_id' => $instituteId,
            'route_name' => $request->route_name,
            'description' => $request->description,
            'route_reference_id' => $routeReferenceId,
            'is_active' => true,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('admin.transport.routes.index')
            ->with('success', 'Transport route created successfully! You can now add buses to this route.');
    }

    /**
     * Display the specified route with detailed stats
     */
    public function show($id)
    {
        $route = TransportRoute::with(['buses' => function($query) {
                $query->with('fees');
            }])
            ->withCount(['buses', 'activeBuses'])
            ->findOrFail($id);
        
        if ($route->institute_id != Auth::user()->institute_id) {
            abort(403);
        }

        // Get additional statistics
        $totalBuses = $route->buses->count();
        $activeBuses = $route->buses->where('status', 1)->count();
        $inactiveBuses = $totalBuses - $activeBuses;
        
        // Get capacity statistics
        $totalCapacity = $route->buses->sum('sitting_capacity');
        $avgCapacity = $totalBuses > 0 ? round($totalCapacity / $totalBuses) : 0;
        
        // Get route type distribution
        $routeTypes = $route->buses->groupBy('route_type')->map->count();
        
        // Get fee statistics
        $fees = $route->buses->pluck('fees')->flatten();
        $avgMonthlyFee = $fees->avg('monthly_fee');
        $minMonthlyFee = $fees->min('monthly_fee');
        $maxMonthlyFee = $fees->max('monthly_fee');

        return view('instituteAdmin.DashboardFiles.ViewTransportRoute', compact(
            'route',
            'totalBuses',
            'activeBuses',
            'inactiveBuses',
            'totalCapacity',
            'avgCapacity',
            'routeTypes',
            'avgMonthlyFee',
            'minMonthlyFee',
            'maxMonthlyFee'
        ));
    }

    /**
     * Show form to edit route
     */
    public function edit($id)
    {
        $route = TransportRoute::findOrFail($id);
        
        if ($route->institute_id != Auth::user()->institute_id) {
            abort(403);
        }

        return view('instituteAdmin.DashboardFiles.EditTransportRoute', compact('route'));
    }

    /**
     * Update the specified route
     */
    public function update(Request $request, $id)
    {
        $route = TransportRoute::findOrFail($id);
        
        if ($route->institute_id != Auth::user()->institute_id) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'route_name' => 'required|string|max:255|unique:routes,route_name,' . $id . ',id,institute_id,' . Auth::user()->institute_id,
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $route->update([
                'route_name' => $request->route_name,
                'is_active' => $request->has('is_active')
            ]);

            return redirect()->route('admin.transport.routes.index')
                ->with('success', 'Route updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating route: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified route
     */
    public function destroy($id)
    {
        $route = TransportRoute::findOrFail($id);
        
        if ($route->institute_id != Auth::user()->institute_id) {
            abort(403);
        }

        // Check if route has buses
        if ($route->buses()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete route because it has ' . $route->buses()->count() . ' buses assigned to it.');
        }

        try {
            $route->delete();
            return redirect()->route('admin.transport.routes.index')
                ->with('success', 'Route deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting route: ' . $e->getMessage());
        }
    }

    /**
     * Get routes for dropdown (API)
     */
    public function getRoutesForDropdown()
    {
        $instituteId = Auth::user()->institute_id;
        $routes = TransportRoute::where('institute_id', $instituteId)
            ->where('is_active', true)
            ->withCount('activeBuses')
            ->select('id', 'route_name', 'route_reference_id')
            ->orderBy('route_name')
            ->get()
            ->map(function($route) {
                return [
                    'id' => $route->route_reference_id,
                    'route_name' => $route->route_name . ' (' . $route->active_buses_count . ' active buses)',
                    'buses_count' => $route->active_buses_count
                ];
            });

        return response()->json($routes);
    }

    /**
     * Get route details for AJAX request with complete statistics
     */
    public function getRouteDetails($id)
    {
        try {
            $route = TransportRoute::where('route_reference_id', $id)
                ->with(['buses' => function($query) {
                    $query->with('fees');
                }])
                ->withCount(['buses', 'activeBuses'])
                ->firstOrFail();
            
            if ($route->institute_id != Auth::user()->institute_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            // Calculate route statistics
            $totalBuses = $route->buses->count();
            $activeBuses = $route->buses->where('status', 1)->count();
            $totalCapacity = $route->buses->sum('sitting_capacity');
            
            // Get route type distribution
            $routeTypes = [
                'morning' => $route->buses->where('route_type', 'morning')->count(),
                'evening' => $route->buses->where('route_type', 'evening')->count(),
                'both' => $route->buses->where('route_type', 'both')->count()
            ];
            
            // Get bus details for the response
            $buses = $route->buses->map(function($bus) {
                $currentFee = $bus->fees->first();
                return [
                    'id' => $bus->id,
                    'bus_number' => $bus->bus_number,
                    'vehicle_number' => $bus->vehicle_number,
                    'driver_name' => $bus->driver_name,
                    'route_type' => $bus->route_type,
                    'sitting_capacity' => $bus->sitting_capacity,
                    'status' => $bus->status,
                    'monthly_fee' => $currentFee ? $currentFee->monthly_fee : null,
                    'academic_year' => $currentFee ? $currentFee->academic_year : null,
                    'estimated_start_time' => $bus->estimated_start_time,
                    'estimated_end_time' => $bus->estimated_end_time,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $route->route_reference_id,
                    'route_name' => $route->route_name,
                    'description' => $route->description,
                    'total_buses' => $totalBuses,
                    'active_buses' => $activeBuses,
                    'inactive_buses' => $totalBuses - $activeBuses,
                    'total_capacity' => $totalCapacity,
                    'avg_capacity' => $totalBuses > 0 ? round($totalCapacity / $totalBuses) : 0,
                    'route_types' => $routeTypes,
                    'buses' => $buses,
                    'created_at' => $route->created_at->format('Y-m-d H:i:s'),
                    'is_active' => $route->is_active,
                    'stops_count' => $route->buses->sum(function($bus) {
                        return count(json_decode($bus->stops ?? '[]', true));
                    }),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Route not found: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Export route statistics
     */
    public function exportStats($id)
    {
        try {
            $route = TransportRoute::with(['buses' => function($query) {
                    $query->with('fees');
                }])
                ->findOrFail($id);
            
            if ($route->institute_id != Auth::user()->institute_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Prepare statistics for export
            $stats = [
                'route_name' => $route->route_name,
                'total_buses' => $route->buses->count(),
                'active_buses' => $route->buses->where('status', 1)->count(),
                'inactive_buses' => $route->buses->where('status', 0)->count(),
                'total_capacity' => $route->buses->sum('sitting_capacity'),
                'average_capacity' => $route->buses->count() > 0 ? round($route->buses->avg('sitting_capacity')) : 0,
                'morning_routes' => $route->buses->where('route_type', 'morning')->count(),
                'evening_routes' => $route->buses->where('route_type', 'evening')->count(),
                'both_routes' => $route->buses->where('route_type', 'both')->count(),
                'average_monthly_fee' => $route->buses->pluck('fees')->flatten()->avg('monthly_fee'),
                'total_monthly_revenue' => $route->buses->pluck('fees')->flatten()->sum('monthly_fee'),
                'created_at' => $route->created_at->format('Y-m-d'),
                'status' => $route->is_active ? 'Active' : 'Inactive'
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error exporting statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}