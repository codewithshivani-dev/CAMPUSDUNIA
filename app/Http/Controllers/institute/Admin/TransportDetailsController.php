<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\StudentParentAddress;
use App\Models\StudentParentBankAccount;
use Illuminate\Support\Facades\Auth;
use App\Models\TransportDetails;
use App\Models\TransportationFee;
use App\Models\StudentParentDocuments;
use App\Models\TransportRoute;
use App\Models\StudentAcademicTransportDetails;
use App\Models\StudentTransportFeeStructure;
use App\Models\EmployeeTransportFeeStructure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\TransportRoutesExport;
use Maatwebsite\Excel\Facades\Excel;

class TransportDetailsController extends Controller
{
    use \App\Traits\InstituteBranchAccess;
    public function create()
    {
        $instituteId = Auth::user()->institute_id;
        $routes = TransportRoute::where('institute_id', $instituteId)
            ->where('is_active', true)
            ->orderBy('route_name')
            ->get();
            
        return view('instituteAdmin.DashboardFiles.AddTransport', compact('routes'));
    }
    
public function index(Request $request)
    {
        $instituteId = Auth::user()->institute_id;
        
        // Get route summary statistics
        $routeSummary = TransportDetails::where('transport_routes.institute_id', $instituteId)
            ->select(
                'transport_routes.route_id',
                'transport_routes.route_name',
                'parent_route.route_name as parent_route_name',
                DB::raw('COUNT(*) as total_buses'),
                DB::raw('SUM(CASE WHEN transport_routes.status = 1 THEN 1 ELSE 0 END) as active_buses'),
                DB::raw('SUM(transport_routes.sitting_capacity) as total_capacity'),
                DB::raw('AVG(transport_routes.sitting_capacity) as avg_capacity'),
                DB::raw('SUM(CASE WHEN transport_routes.route_type = "morning" THEN 1 ELSE 0 END) as morning_buses'),
                DB::raw('SUM(CASE WHEN transport_routes.route_type = "evening" THEN 1 ELSE 0 END) as evening_buses'),
                DB::raw('SUM(CASE WHEN transport_routes.route_type = "both" THEN 1 ELSE 0 END) as both_buses')
            )
            ->leftJoin('routes as parent_route', 'transport_routes.route_id', '=', 'parent_route.route_reference_id')
            ->groupBy('transport_routes.route_id', 'transport_routes.route_name', 'parent_route.route_name')
            ->get();
        
        // Get total routes count for stats
        $totalRoutes = $routeSummary->count();
        
        // Start query with relationships for detailed view
        $query = TransportDetails::where('transport_routes.institute_id', $instituteId)
            ->leftJoin('routes as parent_route', 'transport_routes.route_id', '=', 'parent_route.route_reference_id')
            ->select(
                'transport_routes.*', 
                'parent_route.route_name as parent_route_name',
                'parent_route.description as route_description',
                'parent_route.is_active as route_status'
            )
            ->with(['fees' => function ($query) {
                $query->where('status', 'active')
                    ->orderBy('created_at', 'desc');
            }]);
        
        // Apply filters if present
        if ($request->filled('bus_number')) {
            $query->where('transport_routes.bus_number', 'LIKE', '%' . $request->bus_number . '%');
        }
        
        if ($request->filled('route_name')) {
            $query->where(function ($q) use ($request) {
                $q->where('transport_routes.route_name', 'LIKE', '%' . $request->route_name . '%')
                ->orWhere('parent_route.route_name', 'LIKE', '%' . $request->route_name . '%');
            });
        }
        
        if ($request->filled('driver_name')) {
            $query->where('transport_routes.driver_name', 'LIKE', '%' . $request->driver_name . '%');
        }
        
        if ($request->filled('route_type')) {
            $query->where('transport_routes.route_type', $request->route_type);
        }
        
        if ($request->filled('status')) {
            $query->where('transport_routes.status', $request->status);
        }
        
        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['bus_number', 'route_name', 'driver_name', 'route_type', 'created_at', 'sitting_capacity'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy('transport_routes.' . $sortBy, $sortOrder);
        } else {
            $query->orderBy('transport_routes.created_at', 'desc');
        }
        
        // Get paginated results
        $transportRoutes = $query->paginate(12);
        
        // Add additional statistics for each route including occupied seats
        $transportRoutes->getCollection()->transform(function ($route) use ($instituteId) {
            // Decode stops and helpers
            $route->stops_array = json_decode($route->stops, true) ?? [];
            $route->helpers_array = json_decode($route->helpers, true) ?? [];
            
            // Calculate stop statistics
            $route->total_stops = count($route->stops_array);
            
            // Get route type with proper label
            $route->route_type_label = match($route->route_type) {
                'morning' => 'Morning Pickup',
                'evening' => 'Evening Drop',
                'both' => 'Both Directions',
                default => ucfirst($route->route_type)
            };
            
            // Get status with proper label
            $route->status_label = $route->status ? 'Active' : 'Inactive';
            $route->status_class = $route->status ? 'success' : 'danger';
            
            // Format times
            $route->formatted_start_time = $route->estimated_start_time 
                ? date('h:i A', strtotime($route->estimated_start_time)) 
                : 'Not set';
            $route->formatted_end_time = $route->estimated_end_time 
                ? date('h:i A', strtotime($route->estimated_end_time)) 
                : 'Not set';
            
            // Calculate occupied seats (students + employees assigned to this bus)
            $occupiedSeats = 0;
            
            // Count students assigned to this bus
            $studentCount = StudentTransportFeeStructure::where('institute_id', $instituteId)
                ->where('fee_type_id', $route->transport_reference_id)
                ->where('payment_status', '!=', 'cancelled')
                ->where(function($query) {
                    $query->whereNull('pay_date')
                        ->orWhere('pay_date', '>=', now()->subMonths(1)); // Consider recent assignments
                })
                ->distinct('student_hash_id')
                ->count('student_hash_id');
            
            // Count employees assigned to this bus
            $employeeCount = EmployeeTransportFeeStructure::where('institute_id', $instituteId)
                ->where('fee_type_id', $route->transport_reference_id)
                ->where('payment_status', '!=', 'cancelled')
                ->where(function($query) {
                    $query->whereNull('pay_date')
                        ->orWhere('pay_date', '>=', now()->subMonths(1));
                })
                ->distinct('employee_id')
                ->count('employee_id');
            
            $occupiedSeats = $studentCount + $employeeCount;
            
            // Calculate available seats
            $availableSeats = max(0, $route->sitting_capacity - $occupiedSeats);
            
            // Calculate occupancy percentage
            $occupancyPercentage = $route->sitting_capacity > 0 
                ? round(($occupiedSeats / $route->sitting_capacity) * 100, 1) 
                : 0;
            
            // Add these values to the route object
            $route->occupied_seats = $occupiedSeats;
            $route->available_seats = $availableSeats;
            $route->occupancy_percentage = $occupancyPercentage;
            
            // Get assignment counts by stop if available
            $stopAssignments = [];
            $studentsByStop = StudentTransportFeeStructure::where('institute_id', $instituteId)
                ->where('fee_type_id', $route->transport_reference_id)
                ->where('payment_status', '!=', 'cancelled')
                ->select('transport_stop_id', DB::raw('COUNT(DISTINCT student_hash_id) as count'))
                ->groupBy('transport_stop_id')
                ->get()
                ->keyBy('transport_stop_id');
            
            $employeesByStop = EmployeeTransportFeeStructure::where('institute_id', $instituteId)
                ->where('fee_type_id', $route->transport_reference_id)
                ->where('payment_status', '!=', 'cancelled')
                ->select('transport_stop_id', DB::raw('COUNT(DISTINCT employee_id) as count'))
                ->groupBy('transport_stop_id')
                ->get()
                ->keyBy('transport_stop_id');
            
            // Combine stop assignments
            foreach ($route->stops_array as $stop) {
                $stopId = $stop['id'] ?? '';
                $stopName = $stop['name'] ?? '';
                
                $stopTotal = 0;
                if (isset($studentsByStop[$stopId])) {
                    $stopTotal += $studentsByStop[$stopId]->count;
                }
                if (isset($employeesByStop[$stopId])) {
                    $stopTotal += $employeesByStop[$stopId]->count;
                }
                
                if ($stopTotal > 0) {
                    $stopAssignments[] = [
                        'stop_id' => $stopId,
                        'stop_name' => $stopName,
                        'assigned_count' => $stopTotal,
                        'order' => $stop['order'] ?? 0
                    ];
                }
            }
            
            $route->stop_assignments = $stopAssignments;
            
            return $route;
        });

        $routeNames = TransportRoute::where('institute_id', $instituteId)
            ->whereNotNull('route_name')
            ->distinct()
            ->orderBy('route_name')
            ->pluck('route_name');
        
        // Calculate summary statistics for all buses
        $totalCapacity = $transportRoutes->getCollection()->sum('sitting_capacity');
        $totalOccupied = $transportRoutes->getCollection()->sum('occupied_seats');
        $totalAvailable = $transportRoutes->getCollection()->sum('available_seats');
        $overallOccupancyPercentage = $totalCapacity > 0 ? round(($totalOccupied / $totalCapacity) * 100, 1) : 0;
        
        return view('instituteAdmin.DashboardFiles.AllTransportDetails', compact(
            'transportRoutes', 
            'routeSummary',
            'totalRoutes',
            'totalCapacity',
            'totalOccupied',
            'totalAvailable',
            'overallOccupancyPercentage',
            'routeNames'
        ));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Transport Details
            'route_id' => 'required|string|max:50',
            'bus_number' => 'required|string|max:50',
            'vehicle_number' => 'required|string|max:20',
            'driver_name' => 'required|string|max:100',
            'driver_contact' => 'required|string|regex:/^[0-9]{10}$/',
            'route_name' => 'nullable|string|max:255',
            'route_type' => 'required|in:morning,evening,both',
            'sitting_capacity' => 'required|integer|min:1|max:100',
            'helpers' => 'required|array',
            'helpers.*.name' => 'required|string|max:100',
            'helpers.*.contact' => 'required|string|regex:/^[0-9]{10}$/',
            'bus_stops' => 'required|array|min:1',
            'bus_stops.*' => 'required|string|max:255',
            'stop_ids' => 'required|array',
            'stop_ids.*' => 'required|string|max:50',
            
            // Timing Fields
            'estimated_start_hour' => 'nullable',
            'estimated_start_minute' => 'nullable',
            'estimated_start_ampm' => 'nullable|in:AM,PM',
            'estimated_end_hour' => 'nullable',
            'estimated_end_minute' => 'nullable',
            'estimated_end_ampm' => 'nullable|in:AM,PM',
            
            // Stop Timings based on route type
            'stop_timings' => 'required|array',
            'stop_timings.*.stop_id' => 'required|string|max:50',
            'stop_timings.*.pickup_hour' => 'nullable',
            'stop_timings.*.pickup_minute' => 'nullable',
            'stop_timings.*.pickup_ampm' => 'nullable|in:AM,PM',
            'stop_timings.*.drop_hour' => 'nullable',
            'stop_timings.*.drop_minute' => 'nullable',
            'stop_timings.*.drop_ampm' => 'nullable|in:AM,PM',
            
            // Fee Structure Details
            'academic_year' => 'required|string|max:20',
            'monthly_fee' => 'required|numeric|min:1',
            'stop_fees' => 'nullable|array',
            'stop_fees.*' => 'nullable|numeric|min:0',
            'stop_fee_ids' => 'nullable|array',
            'stop_fee_ids.*' => 'nullable|string|max:50',
            'late_fee_type' => 'nullable|in:fixed,percentage',
            'late_fee_value' => 'nullable|numeric|min:0',
            'partially_fee_type' => 'nullable|in:fixed,percentage',
            'partially_fee_value' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        // try {
            $instituteId = Auth::user()->institute_id;
            
            // Begin transaction to ensure both transport and fee are saved
            // DB::beginTransaction();
            
            // Generate transport reference ID
            $transportReferenceId = TransportDetails::generateReferenceId($instituteId);
            
            // 1. Convert AM/PM times to 24-hour format
            $estimatedStartTime = null;
            $estimatedEndTime = null;
            
            if ($request->estimated_start_hour && $request->estimated_start_minute && $request->estimated_start_ampm) {
                $estimatedStartTime = $this->convertTo24HourFormat(
                    $request->estimated_start_hour,
                    $request->estimated_start_minute,
                    $request->estimated_start_ampm
                );
            }
            
            if ($request->estimated_end_hour && $request->estimated_end_minute && $request->estimated_end_ampm) {
                $estimatedEndTime = $this->convertTo24HourFormat(
                    $request->estimated_end_hour,
                    $request->estimated_end_minute,
                    $request->estimated_end_ampm
                );
            }
            
            // 2. Process stop timings based on route type
            $stopTimings = [];
            foreach ($request->stop_timings as $index => $timing) {
                $stopId = $timing['stop_id'];
                
                // Process pickup time if route is morning or both
                if ($request->route_type === 'morning' || $request->route_type === 'both') {
                    if (isset($timing['pickup_hour']) && isset($timing['pickup_minute']) && isset($timing['pickup_ampm'])) {
                        $stopTimings[$stopId]['pickup'] = $this->convertTo24HourFormat(
                            $timing['pickup_hour'],
                            $timing['pickup_minute'],
                            $timing['pickup_ampm']
                        );
                    }
                }
                
                // Process drop time if route is evening or both
                if ($request->route_type === 'evening' || $request->route_type === 'both') {
                    if (isset($timing['drop_hour']) && isset($timing['drop_minute']) && isset($timing['drop_ampm'])) {
                        $stopTimings[$stopId]['drop'] = $this->convertTo24HourFormat(
                            $timing['drop_hour'],
                            $timing['drop_minute'],
                            $timing['drop_ampm']
                        );
                    }
                }
            }
            
            // 3. Save Transport Details
            $transport = new TransportDetails();
            $transport->institute_id = $instituteId;
            $transport->route_id = $request->route_id; // Added route_id
            $transport->bus_number = $request->bus_number;
            $transport->vehicle_number = $request->vehicle_number;
            $transport->driver_name = $request->driver_name;
            $transport->driver_contact = $request->driver_contact;
            $transport->route_name = $request->route_name;
            $transport->route_type = $request->route_type;
            $transport->sitting_capacity = $request->sitting_capacity;
            $transport->helpers = json_encode($request->helpers);
            
            // Store stops with their IDs and timings
            $stopsWithDetails = [];
            foreach ($request->bus_stops as $index => $stopName) {
                $stopId = $request->stop_ids[$index] ?? $this->generateStopId();
                
                $stopData = [
                    'id' => $stopId,
                    'name' => $stopName,
                    'order' => $index,
                ];
                
                // Add pickup time if exists for this stop
                if (isset($stopTimings[$stopId]['pickup'])) {
                    $stopData['pickup_time'] = $stopTimings[$stopId]['pickup'];
                }
                
                // Add drop time if exists for this stop
                if (isset($stopTimings[$stopId]['drop'])) {
                    $stopData['drop_time'] = $stopTimings[$stopId]['drop'];
                }
                
                $stopsWithDetails[] = $stopData;
            }
            
            $transport->stops = json_encode($stopsWithDetails);
            
            // Store estimated timings
            $transport->estimated_start_time = $estimatedStartTime;
            $transport->estimated_end_time = $estimatedEndTime;
            
            // Keep old fields for compatibility (nullable)
            $transport->morning_pickup_time = null;
            $transport->evening_drop_time = null;
            
            $transport->transport_reference_id = $transportReferenceId;
            $transport->save();
            
            // 4. Save Transport Fee Structure
            // Prepare fee breakdown with stop-wise fees
            $feeBreakdown = [];
            $monthlyFee = (float) $request->monthly_fee;
            $stopFees = $request->stop_fees ?? [];
            $stopFeeIds = $request->stop_fee_ids ?? [];
            
            // Create fee breakdown for each stop
            foreach ($request->bus_stops as $index => $stopName) {
                $stopId = $request->stop_ids[$index] ?? $this->generateStopId();
                
                // Get fee for this stop (individual or monthly)
                $stopFee = isset($stopFees[$index]) && $stopFees[$index] > 0 
                    ? (float) $stopFees[$index] 
                    : $monthlyFee;
                
                $feeBreakdown[] = [
                    'stop_id' => $stopId,
                    'stop_name' => $stopName,
                    'monthly_fee' => $stopFee,
                    'order' => $index,
                    'created_at' => now()->toDateTimeString(),
                ];
            }
            
            $transportFee = new TransportationFee();
            $transportFee->institute_id = $instituteId;
            $transportFee->route_id = $request->route_id; // Added route_id
            $transportFee->transport_id = $transport->id;
            $transportFee->transport_reference_id = $transportReferenceId;
            $transportFee->academic_year = $request->academic_year;
            $transportFee->fee_duration = 'monthly';
            $transportFee->monthly_fee = $monthlyFee;
            
            // Calculate annual fee for reference (monthly × 12)
            $transportFee->annual_fee = $monthlyFee * 12;
            
            $transportFee->fee_breakdown = json_encode($feeBreakdown);
            
            // Store individual stop fees with their IDs
            $individualStopFees = [];
            foreach ($stopFees as $index => $fee) {
                if ($fee > 0) {
                    $stopId = $stopFeeIds[$index] ?? $request->stop_ids[$index] ?? $this->generateStopId();
                    $individualStopFees[] = [
                        'stop_id' => $stopId,
                        'stop_index' => $index,
                        'monthly_fee' => (float) $fee,
                        'stop_name' => $request->bus_stops[$index] ?? "Stop $index"
                    ];
                }
            }
            $transportFee->stop_fees = json_encode($individualStopFees);
            
            // Set late fee
            if ($request->late_fee_type && $request->late_fee_value > 0) {
                $transportFee->late_fee_type = $request->late_fee_type;
                $transportFee->late_fee_value = (float) $request->late_fee_value;
            }
            
            // Set partial fee
            if ($request->partially_fee_type && $request->partially_fee_value > 0) {
                $transportFee->partially_fee_type = $request->partially_fee_type;
                $transportFee->partially_fee_value = (float) $request->partially_fee_value;
            }
                        
            $transportFee->save();
            
            // DB::commit();

            return redirect()->route('view.transport.details')
                ->with('success', 'Transport details and fee structure saved successfully!');

        // } catch (\Exception $e) {
        //     DB::rollBack();
            
        //     return redirect()->back()
        //         ->with('error', 'Error saving transport details: ' . $e->getMessage())
        //         ->withInput();
        // }
    }
    /**
     * Convert AM/PM time to 24-hour format
     */
    private function convertTo24HourFormat($hour, $minute, $ampm)
    {
        $hour = (int) $hour;
        $minute = (int) $minute;
        
        // Convert to 24-hour format
        if ($ampm === 'PM' && $hour != 12) {
            $hour += 12;
        } elseif ($ampm === 'AM' && $hour == 12) {
            $hour = 0;
        }
        
        // Format as HH:MM
        return sprintf('%02d:%02d', $hour, $minute);
    }

    /**
     * Generate a random stop ID
     */
    private function generateStopId()
    {
        return 'STP_' . strtoupper(Str::random(8));
    }

    /**
     * Get periods by duration
     */
    private function getPeriodsByDuration($duration)
    {
        switch ($duration) {
            case 'monthly': return 12;
            case 'quarterly': return 4;
            case 'half_yearly': return 2;
            case 'yearly': return 1;
            default: return 1;
        }
    }

    /**
     * Get period names by duration
     */
    private function getPeriodNames($duration)
    {
        switch ($duration) {
            case 'monthly':
                return ['January', 'February', 'March', 'April', 'May', 'June', 
                        'July', 'August', 'September', 'October', 'November', 'December'];
            case 'quarterly':
                return ['Quarter 1 (Jan-Mar)', 'Quarter 2 (Apr-Jun)', 
                        'Quarter 3 (Jul-Sep)', 'Quarter 4 (Oct-Dec)'];
            case 'half_yearly':
                return ['Half Year 1 (Jan-Jun)', 'Half Year 2 (Jul-Dec)'];
            case 'yearly':
                return ['Annual Fee'];
            default:
                return ['Period 1'];
        }
    }

    public function showDetails($id)
    {
        $transport = TransportDetails::with('fees')->findOrFail($id);
        
        // Check if user has permission to view this transport
        if ($transport->institute_id != Auth::user()->institute_id) {
            abort(403, 'Unauthorized access');
        }
        
        return response()->json($transport);
    }

    public function getFeeDetails($id)
    {
        try {
            $transport = TransportDetails::with('fees')->findOrFail($id);
            
            if ($transport->fees->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No fee structure found for this route'
                ]);
            }
            
            $currentFee = $transport->fees->first();
            
            return response()->json([
                'success' => true,
                'fee' => $currentFee
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading fee details'
            ]);
        }
    }

    /**
     * Get bus stops for AJAX request
     */
    public function getBusStops($id)
    {
        try {
            $bus = TransportDetails::with('fees')->findOrFail($id);
            
            // Check authorization
            if ($bus->institute_id != Auth::user()->institute_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }
            
            // Decode stops data
            $stops = json_decode($bus->stops, true) ?? [];
            $helpers = json_decode($bus->helpers, true) ?? [];
            
            // Get current fee info
            $currentFee = $bus->fees->first();
            $feeBreakdown = $currentFee ? json_decode($currentFee->fee_breakdown, true) : [];
            
            // Prepare HTML response
            $html = view('instituteAdmin.DashboardFiles.Partials.BusStopsPartial', compact('bus', 'stops', 'helpers', 'feeBreakdown'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'bus' => [
                    'id' => $bus->id,
                    'bus_number' => $bus->bus_number,
                    'vehicle_number' => $bus->vehicle_number,
                    'route_name' => $bus->route_name,
                    'total_stops' => count($stops)
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading stops: ' . $e->getMessage()
            ], 404);
        }
    }
    
    public function downloadTransportData(Request $request)
{
    // ✅ Validate export type
    $validated = $request->validate([
        'type' => 'required|in:excel,csv,pdf',
    ]);

    $institute_id = auth()->user()->institute_id;

    if (!$institute_id) {
        return response()->json(['message' => 'Institute not found'], 403);
    }

    /*
    |--------------------------------------------------------------------------
    | FETCH BASE DATA
    |--------------------------------------------------------------------------
    */

    $transports = TransportDetails::where('institute_id', $institute_id)->get();

    $transportData = collect();

    foreach ($transports as $route) {

        $fee = $route->fees()->where('status','active')->latest()->first();

        $transportData[] = [
            'transport_reference_id' => $route->transport_reference_id,
            'bus_number' => $route->bus_number,
            'vehicle_number' => $route->vehicle_number,
            'sitting_capacity' => $route->sitting_capacity,
            'driver_name' => $route->driver_name,
            'driver_contact' => $route->driver_contact,
            'helpers' => json_encode($route->helpers),
            'route_name' => $route->route_name,
            'route_type' => $route->route_type,
            'stops' => json_encode($route->stops),
            'estimated_start_time' => $route->estimated_start_time,
            'estimated_end_time' => $route->estimated_end_time,
            'morning_pickup_time' => $route->morning_pickup_time,
            'evening_drop_time' => $route->evening_drop_time,
            'status' => $route->status,
            'created_at' => $route->created_at,
            'updated_at' => $route->updated_at,
            'monthly_fee' => $fee->monthly_fee ?? null,
            'fee_status' => $fee->status ?? null,
            'fee_created_at' => $fee->created_at ?? null,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | APPLY FILTERS
    |--------------------------------------------------------------------------
    */

    if ($request->filled('bus_number')) {
        $transportData = $transportData->where('bus_number', $request->bus_number);
    }

    if ($request->filled('vehicle_number')) {
        $transportData = $transportData->filter(function ($row) use ($request) {
            return stripos($row['vehicle_number'], $request->vehicle_number) !== false;
        });
    }

    if ($request->filled('driver_name')) {
        $search = strtolower($request->driver_name);

        $transportData = $transportData->filter(function ($row) use ($search) {
            return str_contains(strtolower($row['driver_name']), $search);
        });
    }

    if ($request->filled('route_name')) {
        $transportData = $transportData->filter(function ($row) use ($request) {
            return stripos($row['route_name'], $request->route_name) !== false;
        });
    }

    if ($request->filled('route_type')) {
        $transportData = $transportData->where('route_type', $request->route_type);
    }

    if ($request->filled('status')) {
        $transportData = $transportData->where('status', $request->status);
    }

    $transportData = $transportData->values();

    if ($transportData->isEmpty()) {
        return response()->json(['message' => 'No transport routes found'], 404);
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT SWITCH
    |--------------------------------------------------------------------------
    */

    switch ($validated['type']) {

        case 'excel':
            return Excel::download(
                new TransportRoutesExport($transportData),
                'transport_routes.xlsx'
            );

        case 'csv':
            return Excel::download(
                new TransportRoutesExport($transportData),
                'transport_routes.csv'
            );

        case 'pdf':
            $pdf = Pdf::loadView('pdf.transport_routes_export', [
                'routes' => $transportData
            ]);
            return $pdf->download('transport_routes.pdf');

        default:
            return response()->json(['message' => 'Invalid type'], 400);
    }
}

}    