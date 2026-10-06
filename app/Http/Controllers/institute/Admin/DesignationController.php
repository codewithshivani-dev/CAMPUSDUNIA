<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use App\Models\Designations;
use App\Models\DepartmentCategory;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\DesignationExport;
use Maatwebsite\Excel\Facades\Excel;

class DesignationController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;

    /**
     * Display designations page with separate add form and view section
     */
    public function create()
    {
        $context = $this->getInstituteBranchContext();
        
        // Get designations with common scope
        $designations = $this->getCommonQuery(Designations::class)
            ->with('departmentCategory')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get categories for dropdown
        $categories = $this->getCommonQuery(DepartmentCategory::class)
            ->where('status', 'active')
            ->orderBy('category_name')
            ->get();

        // Get roles from Spatie permission package
         $roles = Role::where('guard_name', 'web')
            ->whereIn('name', ['student', 'employee'])
            ->orderBy('name')
            ->get();

        return view('instituteAdmin.DashboardFiles.AddDesignations', [
            'designations' => $designations,
            'categories' => $categories,
            'roles' => $roles,
            'context' => $context
        ]);
    }

    /**
     * Store new designation
     */
     public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'department_category_id' => 'required|exists:department_categories,department_category_id',
            'designations' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'roles' => 'required',
            'roles.*' => 'exists:roles,name',
            'status' => 'nullable',
            'designation_id' => 'nullable|string|unique:designations,designation_id'
        ]);
       
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get institute/branch context
        $context = $this->getInstituteBranchContext();

        // Check if user has institute access
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        // Check for duplicate designation name within the same scope and category
        $existingDesignation = $this->getCommonQuery(Designations::class)
            ->where('department_category_id', $request->department_category_id)
            ->where('designations', $request->designations)
            ->exists();

        if ($existingDesignation) {
            return redirect()->back()
                ->with('error', 'This designation name already exists in the selected category.')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Generate unique designation ID if not provided
            $designationId = $request->designation_id ?: 'DESG-' . strtoupper(Str::random(8));

            // Prepare data - only include fields that exist in the designations table
            $data = [
                'designation_id' => $designationId,
                'department_category_id' => $request->department_category_id,
                'designations' => $request->designations,
                'description' => $request->description,
                'roles' => $request->roles,
                'status' =>'active',
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['branch_id'] // This will be null for institute admin
            ];

            // Remove any null values that might cause issues
            $data = array_filter($data, function($value) {
                return !is_null($value);
            });

            // Create the designation
            Designations::create($data);

            DB::commit();

            // Show appropriate success message
            $message = $context['is_branch_admin'] 
                ? 'Designation added successfully' 
                : 'Designation added successfully';

          return redirect()->route('designations.view')->with('success', $message);


        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Designation creation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to add designation: ' . $e->getMessage())
                ->withInput();
        }
    }

        public function view(Request $request)
        {
            $context = $this->getInstituteBranchContext();

            $query = $this->getCommonQuery(Designations::class)
                ->with('departmentCategory');

            // if ($request->filled('designation')) {
            //     $query->where('designations', 'like', '%' . $request->designation . '%');
            // }
            if ($request->filled('designation_id')) {
                $query->where('designation_id', $request->designation_id);
            }

            if ($request->filled('category')) {
                $query->whereHas('departmentCategory', function ($q) use ($request) {
                    $q->where('category_name', 'like', '%' . $request->category . '%');
                });
            }

            if ($request->filled('role')) {
                $query->where('roles', 'like', '%' . $request->role . '%');
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $designations = $query
                ->orderBy(
                    $request->get('sort_by', 'designations'),
                    $request->get('sort_order', 'asc')
                )
                ->get();

            $categories = $this->getCommonQuery(DepartmentCategory::class)
                ->orderBy('category_name')
                ->get();

            $roles = Role::where('guard_name', 'web')->orderBy('name')->get();

            return view('instituteAdmin.DashboardFiles.Designationslist', compact(
                'designations',
                'categories',
                'roles',
                'context'
            ));
        }
    
    public function details($id)
    {
        try {
            $context = $this->getInstituteBranchContext();
            
            $designation = $this->getCommonQuery(Designations::class)
                ->with('departmentCategory')
                ->where('id', $id)
                ->first();

            if (!$designation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Designation not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'designation' => $designation
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Designation details error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred'
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $context = $this->getInstituteBranchContext();
            $searchTerm = $request->get('search_term');
            
            $designations = $this->getCommonQuery(Designations::class)
                ->with('departmentCategory')
                ->where(function($query) use ($searchTerm) {
                    $query->where('designations', 'like', '%' . $searchTerm . '%')
                        ->orWhere('description', 'like', '%' . $searchTerm . '%')
                        ->orWhere('designation_id', 'like', '%' . $searchTerm . '%')
                        ->orWhereHas('departmentCategory', function($q) use ($searchTerm) {
                            $q->where('category_name', 'like', '%' . $searchTerm . '%');
                        });
                })
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'designations' => $designations
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Designation search error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Search failed'
            ], 500);
        }
    }
    /**
     * Get designations by category (AJAX)
     */
    public function getDesignationsByCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:department_categories,department_category_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid category ID'
            ], 422);
        }

        try {
            $designations = $this->getCommonQuery(Designations::class)
                ->where('department_category_id', $request->category_id)
                ->where('status', 'active')
                ->orderBy('designations')
                ->get(['id', 'designations', 'designation_id']);

            return response()->json([
                'success' => true,
                'designations' => $designations
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching designations by category: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch designations'
            ], 500);
        }
    }


    public function downloadDesignationData(Request $request)
    {
        $request->validate([
            'type'       => 'required|in:excel,csv,pdf',
            'ids'        => 'nullable|string',
            'select_all' => 'nullable|boolean',
        ]);

        // ✅ SAME BASE QUERY AS VIEW
        $query = $this->getCommonQuery(Designations::class)
            ->with('departmentCategory');

        /*
        |--------------------------------------
        | Apply Same Filters as View
        |--------------------------------------
        */

        if ($request->filled('designation_id')) {
            $query->where('designation_id', $request->designation_id);
        }

        if ($request->filled('category')) {
            $query->whereHas('departmentCategory', function ($q) use ($request) {
                $q->where('category_name', 'like', '%' . $request->category . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('roles', 'like', '%' . $request->role . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------
        | Select All OR Selected IDs
        |--------------------------------------
        */

        if ($request->select_all == 1) {

            // no extra condition — export filtered dataset

        } elseif ($request->filled('ids')) {

            $ids = array_filter(explode(',', $request->ids));
            $query->whereIn('id', $ids); // ✅ use PRIMARY KEY

        } else {
            return response()->json([
                'status'  => false,
                'message' => 'No records selected for export'
            ], 400);
        }

        /*
        |--------------------------------------
        | Sorting (same as view)
        |--------------------------------------
        */

        $allowedSortColumns = ['designations', 'designation_id', 'status', 'created_at'];

        $sortBy = $request->get('sort_by', 'designations');
        $sortOrder = $request->get('sort_order', 'asc');

        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'designations';
        }

        $designations = $query
            ->orderBy($sortBy, $sortOrder)
            ->get();

        if ($designations->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'No records found'
            ], 404);
        }

        /*
        |--------------------------------------
        | Export
        |--------------------------------------
        */

        $fileName = 'designations_' . now()->format('Y_m_d_His');

        switch ($request->type) {

            case 'excel':
                return Excel::download(
                    new DesignationExport($designations),
                    $fileName . '.xlsx'
                );

            case 'csv':
                return Excel::download(
                    new DesignationExport($designations),
                    $fileName . '.csv'
                );

            case 'pdf':
                $pdf = Pdf::loadView(
                    'pdf.designations_export',
                    compact('designations')
                );
                return $pdf->download($fileName . '.pdf');

            default:
                return response()->json([
                    'status'  => false,
                    'message' => 'Invalid export type'
                ], 400);
        }
    }

}