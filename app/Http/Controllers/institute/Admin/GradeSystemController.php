<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Traits\InstituteBranchAccess;
use App\Models\GradeSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\GradeSystemsExport;
use Maatwebsite\Excel\Facades\Excel;

class GradeSystemController extends Controller
{
    use InstituteBranchAccess;

    public function index()
    {
        $context = $this->getInstituteBranchContext();
    
        $gradeSystems = GradeSystem::where('institute_id', $context['institute_id'])
    
            ->when($context['is_branch_admin'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
    
            // Search filter
            ->when(request('search'), function($query) {
                $query->where('name', 'like', '%' . request('search') . '%');
            })
    
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            // ✅ Pagination added
            ->paginate(15)
            ->withQueryString();
    
        return view('instituteAdmin.ExamStructure.grade_systems', compact('gradeSystems'));
    }

    public function create()
    {
        $context = $this->getInstituteBranchContext();
        return view('instituteAdmin.ExamStructure.create_grade_system', compact('context'));
    }

    public function store(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        // ✅ Decode JSON grade_ranges
        $decodedRanges = json_decode($request->grade_ranges, true);

        if (!is_array($decodedRanges) || empty($decodedRanges)) {
            return back()
                ->withErrors(['grade_ranges' => 'Grade ranges are required'])
                ->withInput();
        }

        // Merge decoded array back into request
        $request->merge([
            'grade_ranges' => $decodedRanges
        ]);

        $validator = Validator::make($request->all(), [
            // 'name' => 'required|string|max:255',
            // 'description' => 'nullable|string',
            // 'grade_ranges' => 'required|array|min:1',
            // 'grade_ranges.*.min_percentage' => 'required|numeric|min:0|max:100',
            // 'grade_ranges.*.max_percentage' => 'required|numeric|min:0|max:100',
            // 'grade_ranges.*.grade' => 'required|string|max:10',
            // 'grade_ranges.*.grade_point' => 'nullable|numeric',
            // 'grade_ranges.*.description' => 'nullable|string',
            // 'is_default' => 'boolean',
            // 'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // If default, unset others
        if ($request->is_default) {
            GradeSystem::where('institute_id', $context['institute_id'])
                ->when(
                    $context['is_branch_admin'],
                    fn ($q) => $q->where('branch_id', $context['branch_id']),
                    fn ($q) => $q->whereNull('branch_id')
                )
                ->update(['is_default' => false]);
        }

        GradeSystem::create([
            'institute_id' => $context['institute_id'],
            'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
            'name' => $request->name,
            'description' => $request->description,
            'grade_ranges' => $request->grade_ranges, // ✅ auto-casted
            'is_default' => $request->boolean('is_default'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('grade-systems.index')
            ->with('success', 'Grade system created successfully.');
    }


    public function edit($id)
    {
        $context = $this->getInstituteBranchContext();
        $gradeSystem = GradeSystem::where('id', $id)
            ->where('institute_id', $context['institute_id'])
            ->firstOrFail();
            
        return view('instituteAdmin.ExamStructure.edit_grade_system', compact('gradeSystem', 'context'));
    }

   public function update(Request $request, $id)
    {
        $context = $this->getInstituteBranchContext();
        $gradeSystem = GradeSystem::where('id', $id)
            ->where('institute_id', $context['institute_id'])
            ->firstOrFail();

        // ✅ Decode JSON string
        $decodedRanges = json_decode($request->grade_ranges, true);

        if (!is_array($decodedRanges) || empty($decodedRanges)) {
            return back()
                ->withErrors(['grade_ranges' => 'Grade ranges are required'])
                ->withInput();
        }

        // ✅ Merge back as array
        $request->merge([
            'grade_ranges' => $decodedRanges
        ]);

        $validator = Validator::make($request->all(), [
            // 'name' => 'required|string|max:255',
            // 'description' => 'nullable|string',
            // 'grade_ranges' => 'required|array|min:1',
            // 'grade_ranges.*.min_percentage' => 'required|numeric|min:0|max:100',
            // 'grade_ranges.*.max_percentage' => 'required|numeric|min:0|max:100',
            // 'grade_ranges.*.grade' => 'required|string|max:10',
            // 'grade_ranges.*.grade_point' => 'nullable|numeric',
            // 'grade_ranges.*.description' => 'nullable|string',
            // 'is_default' => 'boolean',
            // 'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Default logic
        if ($request->boolean('is_default') && !$gradeSystem->is_default) {
            GradeSystem::where('institute_id', $context['institute_id'])
                ->when(
                    $context['is_branch_admin'],
                    fn ($q) => $q->where('branch_id', $context['branch_id']),
                    fn ($q) => $q->whereNull('branch_id')
                )
                ->where('id', '!=', $id)
                ->update(['is_default' => false]);
        }

        // ✅ NO json_encode needed (cast already exists)
        $gradeSystem->update([
            'name' => $request->name,
            'description' => $request->description,
            'grade_ranges' => $request->grade_ranges,
            'is_default' => $request->boolean('is_default'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('grade-systems.index')
            ->with('success', 'Grade system updated successfully.');
    }

    public function destroy($id)
    {
        $context = $this->getInstituteBranchContext();
        $gradeSystem = GradeSystem::where('id', $id)
            ->where('institute_id', $context['institute_id'])
            ->firstOrFail();
            
        // Don't delete if it's the default
        if ($gradeSystem->is_default) {
            return redirect()->back()
                ->with('error', 'Cannot delete the default grade system. Set another as default first.');
        }
        
        $gradeSystem->delete();
        
        return redirect()->route('grade-systems.index')
            ->with('success', 'Grade system deleted successfully.');
    }

    public function getGradeSystems(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        $gradeSystems = GradeSystem::where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->where('is_active', true)
            ->get()
            ->map(function($system) {
                return [
                    'id' => $system->id,
                    'name' => $system->name,
                    'is_default' => $system->is_default,
                    'grade_ranges' => $system->grade_ranges
                ];
            });
            
        return response()->json([
            'success' => true,
            'grade_systems' => $gradeSystems
        ]);
    }
    
    public function downloadGradeSystems(Request $request)
    {
        // ✅ Validate export type
        $validated = $request->validate([
            'type' => 'required|in:excel,csv,pdf',
        ]);

        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json(['message' => 'Institute not found'], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | FETCH BASE DATA (same as index)
        |--------------------------------------------------------------------------
        */

        $gradeSystems = GradeSystem::where('institute_id', $context['institute_id'])

            ->when($context['is_branch_admin'], function ($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function ($query) {
                return $query->whereNull('branch_id');
            })

            ->get();

        $data = collect();

        foreach ($gradeSystems as $gradeSystem) {

            $data[] = [
                'name' => $gradeSystem->name,
                'description' => $gradeSystem->description,
                'grade_ranges' => $gradeSystem->grade_ranges,
                'is_active' => $gradeSystem->is_active,
                'is_default' => $gradeSystem->is_default ? 'Yes' : 'No',
                'created_at' => $gradeSystem->created_at,
                'updated_at' => $gradeSystem->updated_at,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | APPLY SAME FILTERS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = strtolower($request->search);

            $data = $data->filter(function ($row) use ($search) {
                return str_contains(strtolower($row['name']), $search);
            });
        }

        $data = $data->values();

        if ($data->isEmpty()) {
            return response()->json(['message' => 'No grade systems found'], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | EXPORT SWITCH
        |--------------------------------------------------------------------------
        */

        switch ($validated['type']) {

            case 'excel':
                return Excel::download(
                    new GradeSystemsExport($data),
                    'grade_systems.xlsx'
                );

            case 'csv':
                return Excel::download(
                    new GradeSystemsExport($data),
                    'grade_systems.csv'
                );

            case 'pdf':
                $pdf = Pdf::loadView('pdf.grade_systems_export', [
                    'grades' => $data
                ]);

                return $pdf->download('grade_systems.pdf');

            default:
                return response()->json(['message' => 'Invalid type'], 400);
        }
    }
}