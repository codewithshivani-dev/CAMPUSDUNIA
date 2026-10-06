<?php

namespace App\Http\Controllers\institute\Admin\OutPassController;
use App\Http\Controllers\Controller;

use App\Models\OutPassVehicle;
use App\Models\OutPassEmployee;
use App\Models\OutPassStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OutPassVehicleController extends Controller
{
    /**
     * Display a listing of vehicles.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = OutPassVehicle::with(['owner'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('vehicle_type')) {
            $query->where('vehicle_type', $request->vehicle_type);
        }

        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->filled('institute_id')) {
            $query->where('institute_id', $request->institute_id);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('insurance_expiring')) {
            $days = $request->insurance_expiring;
            $query->whereBetween('insurance_expiry', [Carbon::now(), Carbon::now()->addDays($days)]);
        }

        if ($request->filled('fitness_expiring')) {
            $days = $request->fitness_expiring;
            $query->whereBetween('fitness_expiry', [Carbon::now(), Carbon::now()->addDays($days)]);
        }

        if ($request->filled('license_expiring')) {
            $days = $request->license_expiring;
            $query->whereBetween('license_expiry', [Carbon::now(), Carbon::now()->addDays($days)]);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('vehicle_number', 'LIKE', "%{$search}%")
                  ->orWhere('model', 'LIKE', "%{$search}%")
                  ->orWhere('driver_name', 'LIKE', "%{$search}%")
                  ->orWhere('driver_contact', 'LIKE', "%{$search}%")
                  ->orWhere('driver_license', 'LIKE', "%{$search}%");
            });
        }

        // Owner filter
        if ($request->filled('owner_type') && $request->filled('owner_id')) {
            $query->where('owner_type', 'App\\Models\\' . $request->owner_type)
                  ->where('owner_id', $request->owner_id);
        }

        $vehicles = $query->paginate(15)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $vehicles
            ]);
        }

        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new vehicle.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = OutPassEmployee::where('is_active', true)->get();
        $students = OutPassStudent::where('is_active', true)->get();
        
        return view('vehicles.create', compact('employees', 'students'));
    }

    /**
     * Store a newly created vehicle in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'institute_id' => 'nullable|string',
            'branch_id' => 'nullable|string',
            'vehicle_number' => 'required|string|max:50|unique:out_pass_vehicles,vehicle_number',
            'vehicle_type' => 'required|string|max:50',
            'model' => 'required|string|max:100',
            'color' => 'nullable|string|max:30',
            'chassis_number' => 'nullable|string|max:100',
            'engine_number' => 'nullable|string|max:100',
            'registration_date' => 'nullable|string|max:20',
            'insurance_expiry' => 'nullable|date',
            'fitness_expiry' => 'nullable|date',
            'fuel_type' => 'nullable|string|max:30',
            
            // Owner information
            'owner_type' => 'nullable|in:Employee,Student',
            'owner_id' => 'nullable|integer',
            
            // Driver information
            'driver_name' => 'required|string|max:255',
            'driver_contact' => 'required|string|max:20',
            'driver_address' => 'required|string',
            'driver_license' => 'required|string|max:50',
            'license_expiry' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Set owner type and ID
            $ownerType = null;
            $ownerId = null;

            if ($request->filled('owner_type') && $request->filled('owner_id')) {
                $ownerType = 'App\\Models\\OutPass' . $request->owner_type;
                $ownerId = $request->owner_id;
            }

            // Create vehicle
            $vehicle = new OutPassVehicle();
            $vehicle->institute_id = $request->institute_id;
            $vehicle->branch_id = $request->branch_id;
            $vehicle->vehicle_number = strtoupper($request->vehicle_number);
            $vehicle->vehicle_type = $request->vehicle_type;
            $vehicle->model = $request->model;
            $vehicle->color = $request->color;
            $vehicle->chassis_number = $request->chassis_number;
            $vehicle->engine_number = $request->engine_number;
            $vehicle->registration_date = $request->registration_date;
            $vehicle->insurance_expiry = $request->insurance_expiry;
            $vehicle->fitness_expiry = $request->fitness_expiry;
            $vehicle->fuel_type = $request->fuel_type;
            $vehicle->owner_type = $ownerType;
            $vehicle->owner_id = $ownerId;
            $vehicle->driver_name = $request->driver_name;
            $vehicle->driver_contact = $request->driver_contact;
            $vehicle->driver_address = $request->driver_address;
            $vehicle->driver_license = strtoupper($request->driver_license);
            $vehicle->license_expiry = $request->license_expiry;
            $vehicle->is_active = true;
            
            $vehicle->save();

            DB::commit();

            $message = 'Vehicle registered successfully. Vehicle Number: ' . $vehicle->vehicle_number;

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $vehicle->load('owner')
                ], 201);
            }

            return redirect()->route('vehicles.show', $vehicle->id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            $error = 'Failed to register vehicle: ' . $e->getMessage();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $error
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', $error)
                ->withInput();
        }
    }

    /**
     * Display the specified vehicle.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vehicle = OutPassVehicle::with(['owner'])
            ->findOrFail($id);

        // Get vehicle out pass history
        $outPasses = \App\Models\OutPassMigration::where('vehicle_number', $vehicle->vehicle_number)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'vehicle' => $vehicle,
                    'out_passes' => $outPasses
                ]
            ]);
        }

        return view('vehicles.show', compact('vehicle', 'outPasses'));
    }

    /**
     * Show the form for editing the specified vehicle.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vehicle = OutPassVehicle::findOrFail($id);
        $employees = OutPassEmployee::where('is_active', true)->get();
        $students = OutPassStudent::where('is_active', true)->get();
        
        return view('vehicles.edit', compact('vehicle', 'employees', 'students'));
    }

    /**
     * Update the specified vehicle in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vehicle = OutPassVehicle::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'vehicle_number' => 'required|string|max:50|unique:out_pass_vehicles,vehicle_number,' . $id,
            'vehicle_type' => 'required|string|max:50',
            'model' => 'required|string|max:100',
            'color' => 'nullable|string|max:30',
            'chassis_number' => 'nullable|string|max:100',
            'engine_number' => 'nullable|string|max:100',
            'registration_date' => 'nullable|string|max:20',
            'insurance_expiry' => 'nullable|date',
            'fitness_expiry' => 'nullable|date',
            'fuel_type' => 'nullable|string|max:30',
            
            // Owner information
            'owner_type' => 'nullable|in:Employee,Student',
            'owner_id' => 'nullable|integer',
            
            // Driver information
            'driver_name' => 'required|string|max:255',
            'driver_contact' => 'required|string|max:20',
            'driver_address' => 'required|string',
            'driver_license' => 'required|string|max:50',
            'license_expiry' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Set owner type and ID
            $ownerType = null;
            $ownerId = null;

            if ($request->filled('owner_type') && $request->filled('owner_id')) {
                $ownerType = 'App\\Models\\OutPass' . $request->owner_type;
                $ownerId = $request->owner_id;
            }

            // Update vehicle
            $vehicle->vehicle_number = strtoupper($request->vehicle_number);
            $vehicle->vehicle_type = $request->vehicle_type;
            $vehicle->model = $request->model;
            $vehicle->color = $request->color;
            $vehicle->chassis_number = $request->chassis_number;
            $vehicle->engine_number = $request->engine_number;
            $vehicle->registration_date = $request->registration_date;
            $vehicle->insurance_expiry = $request->insurance_expiry;
            $vehicle->fitness_expiry = $request->fitness_expiry;
            $vehicle->fuel_type = $request->fuel_type;
            $vehicle->owner_type = $ownerType;
            $vehicle->owner_id = $ownerId;
            $vehicle->driver_name = $request->driver_name;
            $vehicle->driver_contact = $request->driver_contact;
            $vehicle->driver_address = $request->driver_address;
            $vehicle->driver_license = strtoupper($request->driver_license);
            $vehicle->license_expiry = $request->license_expiry;
            
            $vehicle->save();

            DB::commit();

            $message = 'Vehicle updated successfully.';

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $vehicle->load('owner')
                ]);
            }

            return redirect()->route('vehicles.show', $id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            $error = 'Failed to update vehicle: ' . $e->getMessage();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $error
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', $error)
                ->withInput();
        }
    }

    /**
     * Remove the specified vehicle from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vehicle = OutPassVehicle::findOrFail($id);

        // Check if vehicle has any active out passes
        $activePasses = \App\Models\OutPassMigration::where('vehicle_number', $vehicle->vehicle_number)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($activePasses) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete vehicle with active out passes.'
                ], 403);
            }
            return redirect()->route('vehicles.index')
                ->with('error', 'Cannot delete vehicle with active out passes.');
        }

        $vehicle->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Vehicle deleted successfully.'
            ]);
        }

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }

    /**
     * Toggle vehicle active status.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus($id)
    {
        $vehicle = OutPassVehicle::findOrFail($id);
        $vehicle->is_active = !$vehicle->is_active;
        $vehicle->save();

        $status = $vehicle->is_active ? 'activated' : 'deactivated';

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Vehicle {$status} successfully.",
                'is_active' => $vehicle->is_active
            ]);
        }

        return redirect()->back()
            ->with('success', "Vehicle {$status} successfully.");
    }

    /**
     * Search vehicles (AJAX).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $search = $request->get('q');
        
        $vehicles = OutPassVehicle::where('vehicle_number', 'LIKE', "%{$search}%")
            ->orWhere('model', 'LIKE', "%{$search}%")
            ->orWhere('driver_name', 'LIKE', "%{$search}%")
            ->orWhere('driver_license', 'LIKE', "%{$search}%")
            ->where('is_active', true)
            ->limit(10)
            ->get(['id', 'vehicle_number', 'vehicle_type', 'model', 'driver_name']);

        return response()->json($vehicles);
    }

    /**
     * Get vehicle by number (AJAX).
     *
     * @param  string  $number
     * @return \Illuminate\Http\Response
     */
    public function getByNumber($number)
    {
        $vehicle = OutPassVehicle::where('vehicle_number', $number)
            ->where('is_active', true)
            ->first();

        if ($vehicle) {
            return response()->json([
                'success' => true,
                'data' => $vehicle
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Vehicle not found'
        ], 404);
    }

    /**
     * Get vehicles by owner (AJAX).
     *
     * @param  string  $ownerType
     * @param  int  $ownerId
     * @return \Illuminate\Http\Response
     */
    public function getByOwner($ownerType, $ownerId)
    {
        $ownerType = 'App\\Models\\OutPass' . $ownerType;
        
        $vehicles = OutPassVehicle::where('owner_type', $ownerType)
            ->where('owner_id', $ownerId)
            ->where('is_active', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $vehicles
        ]);
    }

    /**
     * Get expiring documents.
     *
     * @return \Illuminate\Http\Response
     */
    public function expiringDocuments()
    {
        $thirtyDaysFromNow = Carbon::now()->addDays(30);

        $expiringInsurance = OutPassVehicle::where('is_active', true)
            ->whereNotNull('insurance_expiry')
            ->whereBetween('insurance_expiry', [Carbon::now(), $thirtyDaysFromNow])
            ->get();

        $expiringFitness = OutPassVehicle::where('is_active', true)
            ->whereNotNull('fitness_expiry')
            ->whereBetween('fitness_expiry', [Carbon::now(), $thirtyDaysFromNow])
            ->get();

        $expiringLicense = OutPassVehicle::where('is_active', true)
            ->whereNotNull('license_expiry')
            ->whereBetween('license_expiry', [Carbon::now(), $thirtyDaysFromNow])
            ->get();

        $expiredInsurance = OutPassVehicle::where('is_active', true)
            ->whereNotNull('insurance_expiry')
            ->where('insurance_expiry', '<', Carbon::now())
            ->get();

        $expiredFitness = OutPassVehicle::where('is_active', true)
            ->whereNotNull('fitness_expiry')
            ->where('fitness_expiry', '<', Carbon::now())
            ->get();

        $expiredLicense = OutPassVehicle::where('is_active', true)
            ->whereNotNull('license_expiry')
            ->where('license_expiry', '<', Carbon::now())
            ->get();

        $data = [
            'expiring_insurance' => $expiringInsurance,
            'expiring_fitness' => $expiringFitness,
            'expiring_license' => $expiringLicense,
            'expired_insurance' => $expiredInsurance,
            'expired_fitness' => $expiredFitness,
            'expired_license' => $expiredLicense,
            'counts' => [
                'expiring_insurance' => $expiringInsurance->count(),
                'expiring_fitness' => $expiringFitness->count(),
                'expiring_license' => $expiringLicense->count(),
                'expired_insurance' => $expiredInsurance->count(),
                'expired_fitness' => $expiredFitness->count(),
                'expired_license' => $expiredLicense->count(),
            ]
        ];

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        return view('vehicles.expiring', compact('data'));
    }

    /**
     * Get vehicle statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics()
    {
        $stats = [
            'total' => OutPassVehicle::count(),
            'active' => OutPassVehicle::where('is_active', true)->count(),
            'inactive' => OutPassVehicle::where('is_active', false)->count(),
            'by_type' => OutPassVehicle::where('is_active', true)
                ->select('vehicle_type', DB::raw('count(*) as total'))
                ->groupBy('vehicle_type')
                ->get(),
            'by_fuel_type' => OutPassVehicle::where('is_active', true)
                ->select('fuel_type', DB::raw('count(*) as total'))
                ->groupBy('fuel_type')
                ->get(),
            'documents' => [
                'insurance_expired' => OutPassVehicle::where('is_active', true)
                    ->whereNotNull('insurance_expiry')
                    ->where('insurance_expiry', '<', Carbon::now())
                    ->count(),
                'insurance_expiring_30days' => OutPassVehicle::where('is_active', true)
                    ->whereNotNull('insurance_expiry')
                    ->whereBetween('insurance_expiry', [Carbon::now(), Carbon::now()->addDays(30)])
                    ->count(),
                'fitness_expired' => OutPassVehicle::where('is_active', true)
                    ->whereNotNull('fitness_expiry')
                    ->where('fitness_expiry', '<', Carbon::now())
                    ->count(),
                'fitness_expiring_30days' => OutPassVehicle::where('is_active', true)
                    ->whereNotNull('fitness_expiry')
                    ->whereBetween('fitness_expiry', [Carbon::now(), Carbon::now()->addDays(30)])
                    ->count(),
                'license_expired' => OutPassVehicle::where('is_active', true)
                    ->whereNotNull('license_expiry')
                    ->where('license_expiry', '<', Carbon::now())
                    ->count(),
                'license_expiring_30days' => OutPassVehicle::where('is_active', true)
                    ->whereNotNull('license_expiry')
                    ->whereBetween('license_expiry', [Carbon::now(), Carbon::now()->addDays(30)])
                    ->count(),
            ]
        ];

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        }

        return view('vehicles.statistics', compact('stats'));
    }

    /**
     * Bulk import vehicles.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Process CSV file
        $file = $request->file('file');
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        
        // Remove header row
        $header = array_shift($data);
        
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($data as $row) {
            try {
                // Map CSV columns to vehicle data
                $vehicleData = [
                    'vehicle_number' => $row[0] ?? null,
                    'vehicle_type' => $row[1] ?? null,
                    'model' => $row[2] ?? null,
                    'driver_name' => $row[3] ?? null,
                    'driver_contact' => $row[4] ?? null,
                    'driver_address' => $row[5] ?? null,
                    'driver_license' => $row[6] ?? null,
                ];

                // Validate required fields
                if (!$vehicleData['vehicle_number'] || !$vehicleData['driver_name']) {
                    $errorCount++;
                    $errors[] = "Row " . ($successCount + $errorCount + 1) . ": Missing required fields";
                    continue;
                }

                // Check if vehicle already exists
                $exists = OutPassVehicle::where('vehicle_number', $vehicleData['vehicle_number'])->exists();
                if ($exists) {
                    $errorCount++;
                    $errors[] = "Vehicle number {$vehicleData['vehicle_number']} already exists";
                    continue;
                }

                // Create vehicle
                OutPassVehicle::create($vehicleData);
                $successCount++;

            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Row " . ($successCount + $errorCount) . ": " . $e->getMessage();
            }
        }

        $message = "Import completed: {$successCount} vehicles imported, {$errorCount} errors.";
        
        if ($errorCount > 0) {
            return redirect()->route('vehicles.index')
                ->with('warning', $message)
                ->with('import_errors', $errors);
        }

        return redirect()->route('vehicles.index')
            ->with('success', $message);
    }
}