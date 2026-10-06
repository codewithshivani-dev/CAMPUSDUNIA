<?php

namespace App\Http\Controllers;

use App\Models\OutPassPassenger;
use App\Models\OutPassMigration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OutPassPassengerController extends Controller
{
    /**
     * Display a listing of passengers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = OutPassPassenger::with(['outPass'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('out_pass_id')) {
            $query->where('out_pass_id', $request->out_pass_id);
        }

        if ($request->filled('institute_id')) {
            $query->where('institute_id', $request->institute_id);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('relation')) {
            $query->where('relation', 'LIKE', "%{$request->relation}%");
        }

        if ($request->filled('age_from')) {
            $query->where('age', '>=', $request->age_from);
        }

        if ($request->filled('age_to')) {
            $query->where('age', '<=', $request->age_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('id_proof_number', 'LIKE', "%{$search}%");
            });
        }

        $passengers = $query->paginate(15)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $passengers
            ]);
        }

        return view('passengers.index', compact('passengers'));
    }

    /**
     * Show the form for creating a new passenger.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $outPassId = $request->get('out_pass_id');
        $outPass = null;
        
        if ($outPassId) {
            $outPass = OutPassMigration::find($outPassId);
        }

        return view('passengers.create', compact('outPass', 'outPassId'));
    }

    /**
     * Store a newly created passenger in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'institute_id' => 'nullable|string',
            'branch_id' => 'nullable|string',
            'out_pass_id' => 'nullable|integer|exists:out_pass_migrations,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'relation' => 'required|string|max:100',
            'id_proof_type' => 'nullable|string|max:50',
            'id_proof_number' => 'nullable|string|max:100',
            'age' => 'nullable|integer|min:0|max:150',
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

            // Create passenger
            $passenger = new OutPassPassenger();
            $passenger->institute_id = $request->institute_id;
            $passenger->branch_id = $request->branch_id;
            $passenger->out_pass_id = $request->out_pass_id;
            $passenger->name = $request->name;
            $passenger->phone = $request->phone;
            $passenger->relation = $request->relation;
            $passenger->id_proof_type = $request->id_proof_type;
            $passenger->id_proof_number = $request->id_proof_number;
            $passenger->age = $request->age;
            
            $passenger->save();

            // If associated with an out pass, update the passenger_data JSON
            if ($request->out_pass_id) {
                $this->syncOutPassPassengerData($request->out_pass_id);
            }

            DB::commit();

            $message = 'Passenger added successfully.';

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $passenger
                ], 201);
            }

            // Redirect based on context
            if ($request->out_pass_id) {
                return redirect()->route('out-passes.show', $request->out_pass_id)
                    ->with('success', $message);
            }

            return redirect()->route('passengers.show', $passenger->id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            $error = 'Failed to add passenger: ' . $e->getMessage();
            
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
     * Display the specified passenger.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $passenger = OutPassPassenger::with(['outPass'])
            ->findOrFail($id);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $passenger
            ]);
        }

        return view('passengers.show', compact('passenger'));
    }

    /**
     * Show the form for editing the specified passenger.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $passenger = OutPassPassenger::findOrFail($id);
        
        return view('passengers.edit', compact('passenger'));
    }

    /**
     * Update the specified passenger in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $passenger = OutPassPassenger::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'relation' => 'required|string|max:100',
            'id_proof_type' => 'nullable|string|max:50',
            'id_proof_number' => 'nullable|string|max:100',
            'age' => 'nullable|integer|min:0|max:150',
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

            // Update passenger
            $passenger->name = $request->name;
            $passenger->phone = $request->phone;
            $passenger->relation = $request->relation;
            $passenger->id_proof_type = $request->id_proof_type;
            $passenger->id_proof_number = $request->id_proof_number;
            $passenger->age = $request->age;
            
            $passenger->save();

            // If associated with an out pass, update the passenger_data JSON
            if ($passenger->out_pass_id) {
                $this->syncOutPassPassengerData($passenger->out_pass_id);
            }

            DB::commit();

            $message = 'Passenger updated successfully.';

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $passenger
                ]);
            }

            return redirect()->route('passengers.show', $id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            $error = 'Failed to update passenger: ' . $e->getMessage();
            
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
     * Remove the specified passenger from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $passenger = OutPassPassenger::findOrFail($id);
        
        $outPassId = $passenger->out_pass_id;

        try {
            DB::beginTransaction();

            $passenger->delete();

            // If associated with an out pass, update the passenger_data JSON
            if ($outPassId) {
                $this->syncOutPassPassengerData($outPassId);
            }

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Passenger deleted successfully.'
                ]);
            }

            // Redirect based on context
            if ($outPassId) {
                return redirect()->route('out-passes.show', $outPassId)
                    ->with('success', 'Passenger deleted successfully.');
            }

            return redirect()->route('passengers.index')
                ->with('success', 'Passenger deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            $error = 'Failed to delete passenger: ' . $e->getMessage();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $error
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', $error);
        }
    }

    /**
     * Get passengers by out pass ID.
     *
     * @param  int  $outPassId
     * @return \Illuminate\Http\Response
     */
    public function getByOutPass($outPassId)
    {
        $passengers = OutPassPassenger::where('out_pass_id', $outPassId)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $passengers
        ]);
    }

    /**
     * Search passengers (AJAX).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $search = $request->get('q');
        
        $passengers = OutPassPassenger::where('name', 'LIKE', "%{$search}%")
            ->orWhere('phone', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get(['id', 'name', 'phone', 'relation']);

        return response()->json($passengers);
    }

    /**
     * Sync passenger data JSON in out pass.
     *
     * @param  int  $outPassId
     * @return void
     */
    private function syncOutPassPassengerData($outPassId)
    {
        $passengers = OutPassPassenger::where('out_pass_id', $outPassId)
            ->get(['name', 'phone', 'relation', 'id_proof_type', 'id_proof_number', 'age']);

        $outPass = OutPassMigration::find($outPassId);
        if ($outPass) {
            $outPass->passenger_data = json_encode($passengers);
            $outPass->save();
        }
    }

    /**
     * Bulk store passengers for an out pass.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'out_pass_id' => 'required|integer|exists:out_pass_migrations,id',
            'passengers' => 'required|array|min:1',
            'passengers.*.name' => 'required|string|max:255',
            'passengers.*.phone' => 'required|string|max:20',
            'passengers.*.relation' => 'required|string|max:100',
            'passengers.*.id_proof_type' => 'nullable|string|max:50',
            'passengers.*.id_proof_number' => 'nullable|string|max:100',
            'passengers.*.age' => 'nullable|integer|min:0|max:150',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Delete existing passengers for this out pass
            OutPassPassenger::where('out_pass_id', $request->out_pass_id)->delete();

            // Create new passengers
            $createdPassengers = [];
            foreach ($request->passengers as $passengerData) {
                $passenger = new OutPassPassenger();
                $passenger->out_pass_id = $request->out_pass_id;
                $passenger->name = $passengerData['name'];
                $passenger->phone = $passengerData['phone'];
                $passenger->relation = $passengerData['relation'];
                $passenger->id_proof_type = $passengerData['id_proof_type'] ?? null;
                $passenger->id_proof_number = $passengerData['id_proof_number'] ?? null;
                $passenger->age = $passengerData['age'] ?? null;
                $passenger->save();
                
                $createdPassengers[] = $passenger;
            }

            // Sync passenger data JSON in out pass
            $this->syncOutPassPassengerData($request->out_pass_id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($createdPassengers) . ' passengers added successfully.',
                'data' => $createdPassengers
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add passengers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get passenger statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics()
    {
        $stats = [
            'total' => OutPassPassenger::count(),
            'average_age' => OutPassPassenger::whereNotNull('age')->avg('age'),
            'by_relation' => OutPassPassenger::select('relation', \DB::raw('count(*) as total'))
                ->groupBy('relation')
                ->get(),
            'by_id_proof_type' => OutPassPassenger::whereNotNull('id_proof_type')
                ->select('id_proof_type', \DB::raw('count(*) as total'))
                ->groupBy('id_proof_type')
                ->get(),
            'age_groups' => [
                'children' => OutPassPassenger::where('age', '<', 18)->count(),
                'adults' => OutPassPassenger::whereBetween('age', [18, 60])->count(),
                'seniors' => OutPassPassenger::where('age', '>', 60)->count(),
            ],
            'recent' => OutPassPassenger::with('outPass')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
        ];

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        }

        return view('passengers.statistics', compact('stats'));
    }

    /**
     * Export passengers data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $query = OutPassPassenger::with('outPass');

        if ($request->filled('out_pass_id')) {
            $query->where('out_pass_id', $request->out_pass_id);
        }

        if ($request->filled('relation')) {
            $query->where('relation', $request->relation);
        }

        $passengers = $query->get();

        $filename = 'passengers-' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Add CSV headers
        fputcsv($handle, [
            'ID',
            'Out Pass Code',
            'Name',
            'Phone',
            'Relation',
            'Age',
            'ID Proof Type',
            'ID Proof Number',
            'Created At'
        ]);

        // Add data rows
        foreach ($passengers as $passenger) {
            fputcsv($handle, [
                $passenger->id,
                $passenger->outPass->pass_code ?? 'N/A',
                $passenger->name,
                $passenger->phone,
                $passenger->relation,
                $passenger->age,
                $passenger->id_proof_type,
                $passenger->id_proof_number,
                $passenger->created_at->format('Y-m-d H:i:s')
            ]);
        }

        fclose($handle);
        exit;
    }

    /**
     * Verify passenger by phone number.
     *
     * @param  string  $phone
     * @return \Illuminate\Http\Response
     */
    public function verifyByPhone($phone)
    {
        $passengers = OutPassPassenger::where('phone', $phone)
            ->with('outPass')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($passengers->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No passenger found with this phone number'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $passengers,
            'count' => $passengers->count()
        ]);
    }

    /**
     * Get frequent passengers (by phone number).
     *
     * @param  int  $limit
     * @return \Illuminate\Http\Response
     */
    public function frequentPassengers($limit = 10)
    {
        $frequentPassengers = OutPassPassenger::select('phone', 'name', \DB::raw('count(*) as trip_count'))
            ->groupBy('phone', 'name')
            ->orderBy('trip_count', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $frequentPassengers
        ]);
    }

    /**
     * Get passengers by age range.
     *
     * @param  int  $minAge
     * @param  int  $maxAge
     * @return \Illuminate\Http\Response
     */
    public function getByAgeRange($minAge, $maxAge)
    {
        $passengers = OutPassPassenger::whereBetween('age', [$minAge, $maxAge])
            ->with('outPass')
            ->orderBy('age')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $passengers,
            'count' => $passengers->count()
        ]);
    }

    /**
     * Update multiple passengers for an out pass.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $outPassId
     * @return \Illuminate\Http\Response
     */
    public function updateForOutPass(Request $request, $outPassId)
    {
        $validator = Validator::make($request->all(), [
            'passengers' => 'required|array',
            'passengers.*.id' => 'sometimes|integer|exists:out_pass_passengers,id',
            'passengers.*.name' => 'required|string|max:255',
            'passengers.*.phone' => 'required|string|max:20',
            'passengers.*.relation' => 'required|string|max:100',
            'passengers.*.id_proof_type' => 'nullable|string|max:50',
            'passengers.*.id_proof_number' => 'nullable|string|max:100',
            'passengers.*.age' => 'nullable|integer|min:0|max:150',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $updatedPassengers = [];

            foreach ($request->passengers as $passengerData) {
                if (isset($passengerData['id'])) {
                    // Update existing passenger
                    $passenger = OutPassPassenger::find($passengerData['id']);
                    if ($passenger && $passenger->out_pass_id == $outPassId) {
                        $passenger->name = $passengerData['name'];
                        $passenger->phone = $passengerData['phone'];
                        $passenger->relation = $passengerData['relation'];
                        $passenger->id_proof_type = $passengerData['id_proof_type'] ?? null;
                        $passenger->id_proof_number = $passengerData['id_proof_number'] ?? null;
                        $passenger->age = $passengerData['age'] ?? null;
                        $passenger->save();
                        
                        $updatedPassengers[] = $passenger;
                    }
                } else {
                    // Create new passenger
                    $passenger = new OutPassPassenger();
                    $passenger->out_pass_id = $outPassId;
                    $passenger->name = $passengerData['name'];
                    $passenger->phone = $passengerData['phone'];
                    $passenger->relation = $passengerData['relation'];
                    $passenger->id_proof_type = $passengerData['id_proof_type'] ?? null;
                    $passenger->id_proof_number = $passengerData['id_proof_number'] ?? null;
                    $passenger->age = $passengerData['age'] ?? null;
                    $passenger->save();
                    
                    $updatedPassengers[] = $passenger;
                }
            }

            // Sync passenger data JSON in out pass
            $this->syncOutPassPassengerData($outPassId);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($updatedPassengers) . ' passengers updated successfully.',
                'data' => $updatedPassengers
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update passengers: ' . $e->getMessage()
            ], 500);
        }
    }
}