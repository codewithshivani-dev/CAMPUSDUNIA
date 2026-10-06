<?php

namespace App\Http\Controllers;

use App\Models\OutPassAccompanyPerson;
use App\Models\OutPassMigration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OutPassAccompanyPersonController extends Controller
{
    /**
     * Display a listing of accompany persons.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = OutPassAccompanyPerson::with(['outPass'])
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

        if ($request->filled('relationship')) {
            $query->where('relationship', 'LIKE', "%{$request->relationship}%");
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('mobile', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('id_proof_number', 'LIKE', "%{$search}%");
            });
        }

        $accompanyPersons = $query->paginate(15)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $accompanyPersons
            ]);
        }

        return view('accompany-persons.index', compact('accompanyPersons'));
    }

    /**
     * Show the form for creating a new accompany person.
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

        return view('accompany-persons.create', compact('outPass', 'outPassId'));
    }

    /**
     * Store a newly created accompany person in storage.
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
            'mobile' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'relationship' => 'required|string|max:100',
            'id_proof_type' => 'nullable|string|max:50',
            'id_proof_number' => 'nullable|string|max:100',
            'photo' => 'nullable|image|max:2048',
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

            // Handle photo upload
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('accompany-persons', 'public');
            }

            // Create accompany person
            $accompanyPerson = new OutPassAccompanyPerson();
            $accompanyPerson->institute_id = $request->institute_id;
            $accompanyPerson->branch_id = $request->branch_id;
            $accompanyPerson->out_pass_id = $request->out_pass_id;
            $accompanyPerson->name = $request->name;
            $accompanyPerson->mobile = $request->mobile;
            $accompanyPerson->email = $request->email;
            $accompanyPerson->relationship = $request->relationship;
            $accompanyPerson->id_proof_type = $request->id_proof_type;
            $accompanyPerson->id_proof_number = $request->id_proof_number;
            $accompanyPerson->photo = $photoPath;
            
            $accompanyPerson->save();

            // If associated with an out pass, update the accompany_data JSON
            if ($request->out_pass_id) {
                $this->syncOutPassAccompanyData($request->out_pass_id);
            }

            DB::commit();

            $message = 'Accompany person added successfully.';

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $accompanyPerson
                ], 201);
            }

            // Redirect based on context
            if ($request->out_pass_id) {
                return redirect()->route('out-passes.show', $request->out_pass_id)
                    ->with('success', $message);
            }

            return redirect()->route('accompany-persons.show', $accompanyPerson->id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            $error = 'Failed to add accompany person: ' . $e->getMessage();
            
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
     * Display the specified accompany person.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $accompanyPerson = OutPassAccompanyPerson::with(['outPass'])
            ->findOrFail($id);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $accompanyPerson
            ]);
        }

        return view('accompany-persons.show', compact('accompanyPerson'));
    }

    /**
     * Show the form for editing the specified accompany person.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $accompanyPerson = OutPassAccompanyPerson::findOrFail($id);
        
        return view('accompany-persons.edit', compact('accompanyPerson'));
    }

    /**
     * Update the specified accompany person in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $accompanyPerson = OutPassAccompanyPerson::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'relationship' => 'required|string|max:100',
            'id_proof_type' => 'nullable|string|max:50',
            'id_proof_number' => 'nullable|string|max:100',
            'photo' => 'nullable|image|max:2048',
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

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo
                if ($accompanyPerson->photo) {
                    \Storage::disk('public')->delete($accompanyPerson->photo);
                }
                $accompanyPerson->photo = $request->file('photo')->store('accompany-persons', 'public');
            }

            // Update accompany person
            $accompanyPerson->name = $request->name;
            $accompanyPerson->mobile = $request->mobile;
            $accompanyPerson->email = $request->email;
            $accompanyPerson->relationship = $request->relationship;
            $accompanyPerson->id_proof_type = $request->id_proof_type;
            $accompanyPerson->id_proof_number = $request->id_proof_number;
            
            $accompanyPerson->save();

            // If associated with an out pass, update the accompany_data JSON
            if ($accompanyPerson->out_pass_id) {
                $this->syncOutPassAccompanyData($accompanyPerson->out_pass_id);
            }

            DB::commit();

            $message = 'Accompany person updated successfully.';

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $accompanyPerson
                ]);
            }

            return redirect()->route('accompany-persons.show', $id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            $error = 'Failed to update accompany person: ' . $e->getMessage();
            
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
     * Remove the specified accompany person from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $accompanyPerson = OutPassAccompanyPerson::findOrFail($id);
        
        $outPassId = $accompanyPerson->out_pass_id;

        try {
            DB::beginTransaction();

            // Delete photo if exists
            if ($accompanyPerson->photo) {
                \Storage::disk('public')->delete($accompanyPerson->photo);
            }

            $accompanyPerson->delete();

            // If associated with an out pass, update the accompany_data JSON
            if ($outPassId) {
                $this->syncOutPassAccompanyData($outPassId);
            }

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Accompany person deleted successfully.'
                ]);
            }

            // Redirect based on context
            if ($outPassId) {
                return redirect()->route('out-passes.show', $outPassId)
                    ->with('success', 'Accompany person deleted successfully.');
            }

            return redirect()->route('accompany-persons.index')
                ->with('success', 'Accompany person deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            $error = 'Failed to delete accompany person: ' . $e->getMessage();
            
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
     * Get accompany persons by out pass ID.
     *
     * @param  int  $outPassId
     * @return \Illuminate\Http\Response
     */
    public function getByOutPass($outPassId)
    {
        $accompanyPersons = OutPassAccompanyPerson::where('out_pass_id', $outPassId)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $accompanyPersons
        ]);
    }

    /**
     * Search accompany persons (AJAX).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $search = $request->get('q');
        
        $accompanyPersons = OutPassAccompanyPerson::where('name', 'LIKE', "%{$search}%")
            ->orWhere('mobile', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get(['id', 'name', 'mobile', 'relationship']);

        return response()->json($accompanyPersons);
    }

    /**
     * Sync accompany data JSON in out pass.
     *
     * @param  int  $outPassId
     * @return void
     */
    private function syncOutPassAccompanyData($outPassId)
    {
        $accompanyPersons = OutPassAccompanyPerson::where('out_pass_id', $outPassId)
            ->get(['name', 'mobile', 'email', 'relationship', 'id_proof_type', 'id_proof_number']);

        $outPass = OutPassMigration::find($outPassId);
        if ($outPass) {
            $outPass->accompany_data = json_encode($accompanyPersons);
            $outPass->save();
        }
    }

    /**
     * Bulk store accompany persons for an out pass.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'out_pass_id' => 'required|integer|exists:out_pass_migrations,id',
            'persons' => 'required|array|min:1',
            'persons.*.name' => 'required|string|max:255',
            'persons.*.mobile' => 'required|string|max:20',
            'persons.*.email' => 'nullable|email|max:255',
            'persons.*.relationship' => 'required|string|max:100',
            'persons.*.id_proof_type' => 'nullable|string|max:50',
            'persons.*.id_proof_number' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Delete existing accompany persons for this out pass
            OutPassAccompanyPerson::where('out_pass_id', $request->out_pass_id)->delete();

            // Create new accompany persons
            $createdPersons = [];
            foreach ($request->persons as $personData) {
                $person = new OutPassAccompanyPerson();
                $person->out_pass_id = $request->out_pass_id;
                $person->name = $personData['name'];
                $person->mobile = $personData['mobile'];
                $person->email = $personData['email'] ?? null;
                $person->relationship = $personData['relationship'];
                $person->id_proof_type = $personData['id_proof_type'] ?? null;
                $person->id_proof_number = $personData['id_proof_number'] ?? null;
                $person->save();
                
                $createdPersons[] = $person;
            }

            // Sync accompany data JSON in out pass
            $this->syncOutPassAccompanyData($request->out_pass_id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($createdPersons) . ' accompany persons added successfully.',
                'data' => $createdPersons
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add accompany persons: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get accompany persons statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics()
    {
        $stats = [
            'total' => OutPassAccompanyPerson::count(),
            'by_relationship' => OutPassAccompanyPerson::select('relationship', \DB::raw('count(*) as total'))
                ->groupBy('relationship')
                ->get(),
            'by_id_proof_type' => OutPassAccompanyPerson::whereNotNull('id_proof_type')
                ->select('id_proof_type', \DB::raw('count(*) as total'))
                ->groupBy('id_proof_type')
                ->get(),
            'recent' => OutPassAccompanyPerson::with('outPass')
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

        return view('accompany-persons.statistics', compact('stats'));
    }

    /**
     * Export accompany persons data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $query = OutPassAccompanyPerson::with('outPass');

        if ($request->filled('out_pass_id')) {
            $query->where('out_pass_id', $request->out_pass_id);
        }

        $accompanyPersons = $query->get();

        $filename = 'accompany-persons-' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Add CSV headers
        fputcsv($handle, [
            'ID',
            'Out Pass Code',
            'Name',
            'Mobile',
            'Email',
            'Relationship',
            'ID Proof Type',
            'ID Proof Number',
            'Created At'
        ]);

        // Add data rows
        foreach ($accompanyPersons as $person) {
            fputcsv($handle, [
                $person->id,
                $person->outPass->pass_code ?? 'N/A',
                $person->name,
                $person->mobile,
                $person->email,
                $person->relationship,
                $person->id_proof_type,
                $person->id_proof_number,
                $person->created_at->format('Y-m-d H:i:s')
            ]);
        }

        fclose($handle);
        exit;
    }
}