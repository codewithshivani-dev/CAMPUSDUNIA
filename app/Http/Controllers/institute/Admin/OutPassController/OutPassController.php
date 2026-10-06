<?php

namespace App\Http\Controllers\institute\Admin\OutPassController;
use App\Http\Controllers\Controller;

use App\Models\OutPassMigration;
use App\Models\OutPassStudents;
use App\Models\OutPassEmployee;
use App\Models\OutPassVehicle;
use App\Models\OutPassAccompanyPerson;
use App\Models\OutPassPassenger;
use App\Models\OutPassHistory;
use App\Models\OutPassPdfGeneration;
use App\Models\OutPassApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OutPassController extends Controller
{
    /**
     * Display a listing of out passes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = OutPassMigration::with(['requester', 'creator', 'approver'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('pass_type')) {
            $query->where('pass_type', $request->pass_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('out_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('out_date', '<=', $request->date_to);
        }

        if ($request->filled('institute_id')) {
            $query->where('institute_id', $request->institute_id);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('pass_code', 'LIKE', "%{$search}%")
                  ->orWhere('purpose', 'LIKE', "%{$search}%")
                  ->orWhere('destination', 'LIKE', "%{$search}%")
                  ->orWhere('visitor_name', 'LIKE', "%{$search}%")
                  ->orWhere('driver_name', 'LIKE', "%{$search}%")
                  ->orWhere('vehicle_number', 'LIKE', "%{$search}%");
            });
        }

        $outPasses = $query->paginate(15)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $outPasses
            ]);
        }

        return view('out-passes.index', compact('outPasses'));
    }

    /**
     * Show the form for creating a new out pass.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('out-passes.create');
    }

    /**
     * Store a newly created out pass from the blade form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFromForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pass_type' => 'required|in:student,employee,vehicle,visitor',
            'out_date' => 'required|date',
            'out_time' => 'required',
            'purpose' => 'required|string',
            'destination' => 'required|string',
            
            // Student fields
            'student_id' => 'required_if:pass_type,student',
            'student_name' => 'required_if:pass_type,student',
            'student_contact' => 'required_if:pass_type,student',
            'student_address' => 'required_if:pass_type,student',
            'student_class' => 'required_if:pass_type,student',
            'student_section' => 'required_if:pass_type,student',
            'student_roll_no' => 'required_if:pass_type,student',
            'student_email' => 'required_if:pass_type,student|email',
            'student_parent_name' => 'required_if:pass_type,student',
            'student_parent_contact' => 'required_if:pass_type,student',
            
            // Employee fields
            'employee_id' => 'required_if:pass_type,employee',
            'employee_name' => 'required_if:pass_type,employee',
            'employee_contact' => 'required_if:pass_type,employee',
            'employee_address' => 'required_if:pass_type,employee',
            'employee_department' => 'required_if:pass_type,employee',
            'employee_designation' => 'required_if:pass_type,employee',
            'employee_email' => 'required_if:pass_type,employee|email',
            'employee_manager' => 'required_if:pass_type,employee',
            'employee_emergency_contact' => 'required_if:pass_type,employee',
            
            // Vehicle fields
            'vehicle_number' => 'required_if:pass_type,vehicle',
            'vehicle_type' => 'required_if:pass_type,vehicle',
            'vehicle_model' => 'nullable',
            'driver_name' => 'required_if:pass_type,vehicle',
            'driver_contact' => 'required_if:pass_type,vehicle',
            'driver_address' => 'required_if:pass_type,vehicle',
            'driver_license' => 'nullable',
            
            // Visitor fields
            'visitor_name' => 'required_if:pass_type,visitor',
            'visitor_contact' => 'required_if:pass_type,visitor',
            'visitor_address' => 'required_if:pass_type,visitor',
            'visitor_id_proof' => 'required_if:pass_type,visitor',
            'visitor_company' => 'nullable',
            'person_to_meet' => 'required_if:pass_type,visitor',
            
            // Accompany and passenger data
            'accompany_data' => 'nullable|json',
            'passenger_data' => 'nullable|json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Generate pass code
            $passCode = $this->generatePassCode($request->pass_type);

            // Create main out pass record
            $outPass = new OutPassMigration();
            $outPass->institute_id = session('institute_id') ?? $request->institute_id ?? Auth::user()->institute_id ?? null;
            $outPass->branch_id = session('branch_id') ?? $request->branch_id ?? null;
            $outPass->pass_code = $passCode;
            $outPass->pass_type = $request->pass_type;
            $outPass->status = 'pending';
            $outPass->out_date = $request->out_date;
            $outPass->out_time = $request->out_time;
            $outPass->purpose = $request->purpose;
            $outPass->destination = $request->destination;
            $outPass->remarks = $request->remarks ?? null;
            $outPass->generated_ip = $request->ip();
            $outPass->created_by = Auth::id();
            
            // Set type-specific fields and requester
            $this->setTypeSpecificFields($outPass, $request);
            
            $outPass->save();

            // Handle accompany persons (for student/employee)
            if ($request->filled('accompany_data')) {
                $accompanyData = json_decode($request->accompany_data, true);
                if (!empty($accompanyData)) {
                    $this->saveAccompanyPersons($outPass->id, $accompanyData);
                }
            }

            // Handle passengers (for vehicle)
            if ($request->filled('passenger_data')) {
                $passengerData = json_decode($request->passenger_data, true);
                if (!empty($passengerData)) {
                    $this->savePassengers($outPass->id, $passengerData);
                }
            }

            // Create initial approval record
            $this->createInitialApproval($outPass->id);

            // Log history
            $this->logHistory($outPass->id, 'created', null, 'pending', 'Out pass request submitted');

            DB::commit();

            // Load relationships for response
            $outPass->load(['requester', 'accompanyPersons', 'passengers']);

            return response()->json([
                'success' => true,
                'message' => 'Out pass request submitted successfully!',
                'pass_code' => $passCode,
                'data' => $outPass
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit out pass request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified out pass.
     *
     * @param  string  $passCode
     * @return \Illuminate\Http\Response
     */
    public function show($passCode)
    {
        $outPass = OutPassMigration::with(['requester', 'creator', 'approver', 'accompanyPersons', 'passengers'])
            ->where('pass_code', $passCode)
            ->firstOrFail();

        // Log view in history
        $this->logHistory($outPass->id, 'viewed', null, null, 'Out pass viewed');

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $outPass
            ]);
        }

        return view('out-passes.show', compact('outPass'));
    }

    /**
     * Update the specified out pass.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $outPass = OutPassMigration::findOrFail($id);

        if ($outPass->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending out passes can be updated.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'out_date' => 'sometimes|date',
            'out_time' => 'sometimes',
            'purpose' => 'sometimes|string',
            'destination' => 'sometimes|string',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $oldData = $outPass->toArray();
            
            $outPass->fill($request->only([
                'out_date', 'out_time', 'purpose', 'destination', 'remarks'
            ]));
            
            $outPass->save();

            // Log history
            $this->logHistory($outPass->id, 'updated', null, null, 'Out pass updated');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Out pass updated successfully.',
                'data' => $outPass
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update out pass: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified out pass.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $outPass = OutPassMigration::findOrFail($id);

        if ($outPass->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending out passes can be deleted.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            // Delete related records
            OutPassAccompanyPerson::where('out_pass_id', $id)->delete();
            OutPassPassenger::where('out_pass_id', $id)->delete();
            OutPassHistory::where('out_pass_id', $id)->delete();
            OutPassApproval::where('out_pass_id', $id)->delete();
            
            $outPass->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Out pass deleted successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete out pass: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve an out pass.
     *
     * @param  string  $passCode
     * @return \Illuminate\Http\Response
     */
    public function approvePass($passCode)
    {
        try {
            DB::beginTransaction();
            $outPass = OutPassMigration::where('pass_code', $passCode)->first();
            dd($outPass);
            
            if ($outPass->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending out passes can be approved.'
                ], 403);
            }

            $oldStatus = $outPass->status;
            $outPass->status = 'approved';
            $outPass->approved_by = Auth::id();
            $outPass->approved_at = now();
            $outPass->approved_ip = request()->ip();
            $outPass->save();

            // Update approval record
            OutPassApproval::where('out_pass_id', $outPass->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'approved',
                    'action_at' => now()
                ]);

            // Log history
            $this->logHistory($outPass->id, 'approved', $oldStatus, 'approved', 'Out pass approved');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Out pass approved successfully',
                'data' => $outPass
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve out pass: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject an out pass.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $passCode
     * @return \Illuminate\Http\Response
     */
    public function rejectPass(Request $request, $passCode)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $outPass = OutPassMigration::where('pass_code', $passCode)->firstOrFail();
            
            if ($outPass->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending out passes can be rejected.'
                ], 403);
            }

            $oldStatus = $outPass->status;
            $outPass->status = 'rejected';
            $outPass->rejection_reason = $request->rejection_reason;
            $outPass->approved_by = Auth::id();
            $outPass->approved_at = now();
            $outPass->approved_ip = $request->ip();
            $outPass->save();

            // Update approval record
            OutPassApproval::where('out_pass_id', $outPass->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'rejected',
                    'action_at' => now(),
                    'comments' => $request->rejection_reason
                ]);

            // Log history
            $this->logHistory($outPass->id, 'rejected', $oldStatus, 'rejected', $request->rejection_reason);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Out pass rejected successfully',
                'data' => $outPass
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject out pass: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel an out pass.
     *
     * @param  string  $passCode
     * @return \Illuminate\Http\Response
     */
    public function cancelPass($passCode)
    {
        try {
            DB::beginTransaction();

            $outPass = OutPassMigration::where('pass_code', $passCode)->firstOrFail();

            if (!in_array($outPass->status, ['pending', 'approved'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending or approved passes can be cancelled.'
                ], 403);
            }

            $oldStatus = $outPass->status;
            $outPass->status = 'cancelled';
            $outPass->save();

            // Log history
            $this->logHistory($outPass->id, 'cancelled', $oldStatus, 'cancelled', 'Out pass cancelled');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Out pass cancelled successfully',
                'data' => $outPass
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel out pass: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark out pass as returned.
     *
     * @param  string  $passCode
     * @return \Illuminate\Http\Response
     */
    public function markReturned($passCode)
    {
        try {
            DB::beginTransaction();

            $outPass = OutPassMigration::where('pass_code', $passCode)->firstOrFail();

            if ($outPass->status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only approved passes can be marked as returned.'
                ], 403);
            }

            $oldStatus = $outPass->status;
            $outPass->status = 'used';
            $outPass->actual_return_time = Carbon::now()->format('H:i:s');
            $outPass->save();

            // Log history
            $this->logHistory($outPass->id, 'returned', $oldStatus, 'used', 'Out pass marked as returned');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Out pass marked as returned successfully',
                'data' => $outPass
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark out pass as returned: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get out pass statistics.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function statistics(Request $request)
    {
        $query = OutPassMigration::query();

        if ($request->filled('institute_id')) {
            $query->where('institute_id', $request->institute_id);
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        $stats = [
            'total' => $query->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'approved' => (clone $query)->where('status', 'approved')->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
            'used' => (clone $query)->where('status', 'used')->count(),
            'expired' => (clone $query)->where('status', 'expired')->count(),
            'today' => (clone $query)->whereDate('out_date', Carbon::today())->count(),
            'this_week' => (clone $query)->whereBetween('out_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'this_month' => (clone $query)->whereMonth('out_date', Carbon::now()->month)->count(),
            'by_type' => [
                'student' => (clone $query)->where('pass_type', 'student')->count(),
                'employee' => (clone $query)->where('pass_type', 'employee')->count(),
                'vehicle' => (clone $query)->where('pass_type', 'vehicle')->count(),
                'visitor' => (clone $query)->where('pass_type', 'visitor')->count(),
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Search out passes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $search = $request->get('q', '');
        
        $outPasses = OutPassMigration::where('pass_code', 'LIKE', "%{$search}%")
            ->orWhere('visitor_name', 'LIKE', "%{$search}%")
            ->orWhere('driver_name', 'LIKE', "%{$search}%")
            ->orWhere('vehicle_number', 'LIKE', "%{$search}%")
            ->orWhere('purpose', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get(['id', 'pass_code', 'pass_type', 'status', 'out_date', 'destination']);

        return response()->json([
            'success' => true,
            'data' => $outPasses
        ]);
    }

    /**
     * Save PDF generation record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function savePdfGeneration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pass_code' => 'required|exists:out_pass_migrations,pass_code',
            'file_name' => 'required|string',
            'file_path' => 'nullable|string',
            'file_size' => 'nullable|integer',
            'includeSignature' => 'nullable|boolean',
            'includeQR' => 'nullable|boolean',
            'includeWatermark' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $outPass = OutPassMigration::where('pass_code', $request->pass_code)->firstOrFail();

            $pdfGeneration = OutPassPdfGeneration::create([
                'out_pass_id' => $outPass->id,
                'generated_by' => Auth::id(),
                'file_name' => $request->file_name,
                'file_path' => $request->file_path,
                'file_size' => $request->file_size,
                'status' => 'generated',
                'options' => json_encode([
                    'includeSignature' => $request->includeSignature ?? true,
                    'includeQR' => $request->includeQR ?? true,
                    'includeWatermark' => $request->includeWatermark ?? true,
                ]),
                'downloaded_ip' => $request->ip(),
            ]);

            // Log history
            $this->logHistory($outPass->id, 'downloaded', null, null, 'PDF generated');

            return response()->json([
                'success' => true,
                'message' => 'PDF generation record saved',
                'data' => $pdfGeneration
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save PDF record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get out pass history.
     *
     * @param  string  $passCode
     * @return \Illuminate\Http\Response
     */
    public function getHistory($passCode)
    {
        $outPass = OutPassMigration::where('pass_code', $passCode)->firstOrFail();

        $history = OutPassHistory::where('out_pass_id', $outPass->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    /**
     * Set type-specific fields for out pass.
     *
     * @param  \App\Models\OutPassMigration  $outPass
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    private function setTypeSpecificFields($outPass, $request)
    {
        switch ($request->pass_type) {
            case 'student':
                // Find or create student record
                $student = OutPassStudents::firstOrCreate(
                    ['student_id' => $request->student_id],
                    [
                        'full_name' => $request->student_name,
                        'contact_number' => $request->student_contact,
                        'address' => $request->student_address,
                        'class' => $request->student_class,
                        'section' => $request->student_section,
                        'roll_number' => $request->student_roll_no,
                        'email' => $request->student_email,
                        'parent_name' => $request->student_parent_name,
                        'parent_contact' => $request->student_parent_contact,
                        'admission_year' => Carbon::now()->year,
                    ]
                );
                $outPass->requester_type = OutPassStudents::class;
                $outPass->requester_id = $student->id;
                break;

            case 'employee':
                // Find or create employee record
                $employee = OutPassEmployee::firstOrCreate(
                    ['employee_id' => $request->employee_id],
                    [
                        'full_name' => $request->employee_name,
                        'contact_number' => $request->employee_contact,
                        'address' => $request->employee_address,
                        'department' => $request->employee_department,
                        'designation' => $request->employee_designation,
                        'email' => $request->employee_email,
                        'reporting_manager' => $request->employee_manager,
                        'emergency_contact' => $request->employee_emergency_contact,
                        'joining_date' => Carbon::now(),
                    ]
                );
                $outPass->requester_type = OutPassEmployee::class;
                $outPass->requester_id = $employee->id;
                break;

            case 'vehicle':
                $outPass->vehicle_number = $request->vehicle_number;
                $outPass->vehicle_type = $request->vehicle_type;
                $outPass->vehicle_model = $request->vehicle_model;
                $outPass->driver_name = $request->driver_name;
                $outPass->driver_contact = $request->driver_contact;
                $outPass->driver_address = $request->driver_address;
                $outPass->driver_license = $request->driver_license;
                
                // Save or update vehicle record
                OutPassVehicle::updateOrCreate(
                    ['vehicle_number' => $request->vehicle_number],
                    [
                        'vehicle_type' => $request->vehicle_type,
                        'model' => $request->vehicle_model,
                        'driver_name' => $request->driver_name,
                        'driver_contact' => $request->driver_contact,
                        'driver_address' => $request->driver_address,
                        'driver_license' => $request->driver_license,
                        'is_active' => true,
                    ]
                );
                break;

            case 'visitor':
                $outPass->visitor_name = $request->visitor_name;
                $outPass->visitor_contact = $request->visitor_contact;
                $outPass->visitor_address = $request->visitor_address;
                $outPass->visitor_id_proof = $request->visitor_id_proof;
                $outPass->visitor_company = $request->visitor_company;
                $outPass->person_to_meet = $request->person_to_meet;
                break;
        }
    }

    /**
     * Save accompany persons.
     *
     * @param  int  $outPassId
     * @param  array  $accompanyData
     * @return void
     */
    private function saveAccompanyPersons($outPassId, $accompanyData)
    {
        foreach ($accompanyData as $person) {
            if (!empty($person['name']) || !empty($person['mobile'])) {
                OutPassAccompanyPerson::create([
                    'out_pass_id' => $outPassId,
                    'name' => $person['name'] ?? '',
                    'mobile' => $person['mobile'] ?? '',
                    'email' => $person['email'] ?? null,
                    'relationship' => $person['relationship'] ?? '',
                    'id_proof_type' => $person['id_proof_type'] ?? null,
                    'id_proof_number' => $person['id_proof_number'] ?? null,
                ]);
            }
        }
    }

    /**
     * Save passengers.
     *
     * @param  int  $outPassId
     * @param  array  $passengerData
     * @return void
     */
    private function savePassengers($outPassId, $passengerData)
    {
        foreach ($passengerData as $passenger) {
            if (!empty($passenger['name']) || !empty($passenger['phone'])) {
                OutPassPassenger::create([
                    'out_pass_id' => $outPassId,
                    'name' => $passenger['name'] ?? '',
                    'phone' => $passenger['phone'] ?? '',
                    'relation' => $passenger['relation'] ?? '',
                    'id_proof_type' => $passenger['id_proof_type'] ?? null,
                    'id_proof_number' => $passenger['id_proof_number'] ?? null,
                    'age' => $passenger['age'] ?? null,
                ]);
            }
        }
    }

    /**
     * Create initial approval record.
     *
     * @param  int  $outPassId
     * @return void
     */
    private function createInitialApproval($outPassId)
    {
        // Get default approver (you can customize this logic)
        $approverId = $this->getDefaultApproverId();
        
        if ($approverId) {
            OutPassApproval::create([
                'out_pass_id' => $outPassId,
                'approver_id' => $approverId,
                'approval_level' => 'primary',
                'status' => 'pending',
            ]);
        }
    }

    /**
     * Log history.
     *
     * @param  int  $outPassId
     * @param  string  $action
     * @param  string|null  $previousStatus
     * @param  string|null  $newStatus
     * @param  string|null  $remarks
     * @return void
     */
    private function logHistory($outPassId, $action, $previousStatus = null, $newStatus = null, $remarks = null)
    {
        OutPassHistory::create([
            'out_pass_id' => $outPassId,
            'user_id' => Auth::id(),
            'action' => $action,
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'remarks' => $remarks,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Generate unique pass code.
     *
     * @param  string  $type
     * @return string
     */
    private function generatePassCode($type)
    {
        $prefixes = [
            'student' => 'STU',
            'employee' => 'EMP',
            'vehicle' => 'VEH',
            'visitor' => 'VIS'
        ];

        $prefix = $prefixes[$type] ?? 'OUT';
        $date = Carbon::now()->format('ymd');
        $random = strtoupper(substr(uniqid(), -4));
        
        $count = OutPassMigration::whereDate('created_at', Carbon::today())->count() + 1;
        $sequence = str_pad($count, 3, '0', STR_PAD_LEFT);

        return "{$prefix}-{$date}-{$sequence}-{$random}";
    }

    /**
     * Get default approver ID.
     *
     * @return int|null
     */
    private function getDefaultApproverId()
    {
        // Try to find an admin user
        $admin = \App\Models\User::where('role', 'admin')->first();
        if ($admin) {
            return $admin->id;
        }
        
        // Fallback to first user
        $firstUser = \App\Models\User::first();
        return $firstUser ? $firstUser->id : null;
    }

    /**
     * Check expired passes (can be called by cron job).
     *
     * @return \Illuminate\Http\Response
     */
    public function checkExpired()
    {
        $count = OutPassMigration::where('status', 'approved')
            ->where('out_date', '<', Carbon::today())
            ->orWhere(function ($query) {
                $query->where('status', 'approved')
                      ->whereDate('out_date', Carbon::today())
                      ->whereTime('out_time', '<', Carbon::now()->subHours(24)->format('H:i:s'));
            })
            ->update(['status' => 'expired']);

        return response()->json([
            'success' => true,
            'message' => "{$count} expired passes updated.",
            'expired_count' => $count
        ]);
    }
}