<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Departments;
use App\Models\DepartmentCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Traits\InstituteBranchAccess;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\CategoryDepartmentExport;
use Maatwebsite\Excel\Facades\Excel;
class AddDepartmentController extends Controller
{
    use InstituteBranchAccess; 

    public function showdepartmentpage()
    {
        $context = $this->getInstituteBranchContext();
        
        // Use common query that automatically applies the correct scope
        $categories = $this->getCommonQuery(DepartmentCategory::class)
            ->withCount('departments')
            ->orderBy('category_name')
            ->get();

        $departments = $this->getCommonQuery(Departments::class)
            ->with('category')
            ->orderBy('department')
            ->get();

        return view('instituteAdmin.DashboardFiles.AddDepartments', [
            'categories' => $categories,
            'departments' => $departments,
            'user_type' => $context['is_branch_admin'] ? 'branch_admin' : 'institute_admin'
        ]);
    }

    public function AddDepartmentCategory(Request $request)
    {
        // Validate the request
        $request->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        // Get institute/branch context
        $context = $this->getInstituteBranchContext();

        // Check if user has institute access
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        // Check for duplicate category name within the same scope
        $validator = Validator::make($request->all(), [
            'category_name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($context) {
                    $existingCategory = DepartmentCategory::where('institute_id', $context['institute_id'])
                        ->where('branch_id', $context['is_branch_admin'] ? $context['branch_id'] : null)
                        ->where('category_name', $value)
                        ->exists();

                    if ($existingCategory) {
                        if ($context['is_branch_admin']) {
                            $fail('This category name already exists');
                        } else {
                            $fail('This category name already exists');
                        }
                    }
                }
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Generate unique category ID
        $categoryId = 'CAT-' . strtoupper(Str::random(8));

        // Prepare data with proper context
        $data = $this->createWithInstituteBranchContext([
            'department_category_id' => $categoryId,
            'category_name' => $request->category_name,
            'description' => $request->description,
            'status' => 'active'
        ]);

        // Create the category
        DepartmentCategory::create($data);

        // Show appropriate success message
        $message = $context['is_branch_admin'] 
            ? 'Department category added successfully' 
            : 'Department category added successfully';

        return redirect()->route('departments.page')->with('success', $message);
    }


    public function AddDepartments(Request $request)
    {
        $request->validate([
            // 'department_category_id' => 'required|exists:department_categories,department_category_id',
            // 'departments' => 'required|array|min:1',
            // 'departments.*.name' => 'required|string|max:255',
        ]);

        // Get institute/branch context
        $context = $this->getInstituteBranchContext();

        // Check if user has institute access
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        $createdCount = 0;

        foreach ($request->departments as $dept) {
            $departmentId = 'DEPT-' . strtoupper(Str::random(8));
            
            // Prepare data with proper context (just like categories)
            $data = $this->createWithInstituteBranchContext([
                'department_id' => $departmentId,
                'department_category_id' => $request->department_category_id,
                'department' => $dept['name'],
                'description' => $dept['description'] ?? null,
                'status' => 'active'
            ]);

            Departments::create($data);
            $createdCount++;
        }

        // Show appropriate success message
        $message = $context['is_branch_admin'] 
            ? $createdCount . ' departments added successfully' 
            : $createdCount . ' departments added successfully';

        return redirect()->route('departments.view-all')->with('success', $message);
    }

    public function getCategoryDetails($categoryId)
    {
        $category = DepartmentCategory::findOrFail($categoryId);
        return response()->json($category);
    }
       

    public function viewAllCategoriesAndDepartments(Request $request)
    {
        $context = $this->getInstituteBranchContext();

        $categoryId   = $request->get('category_id');
        $departmentId = $request->get('department_id');
        $search       = $request->get('search');
    
        // ✅ If department is selected, force its category
        if ($departmentId) {
            $categoryId = Departments::where('department_id', $departmentId)
                ->value('department_category_id');
        }

        // ✅ Get employee counts by department
        $employeeCounts = DB::table('employee_details')
            ->select('department_id', DB::raw('COUNT(*) as total_employees'))
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function ($q) use ($context) {
                $q->where('branch_id', $context['branch_id']);
            })
            ->groupBy('department_id')
            ->pluck('total_employees', 'department_id');

        // ✅ Categories with departments and their employee counts
        $categories = $this->getCommonQuery(DepartmentCategory::class)
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->where('department_category_id', $categoryId);
            })
            ->with(['departments' => function ($q) use ($departmentId, $search, $context) {
                
                // Apply institute context
                $q->where('institute_id', $context['institute_id']);
                
                if ($context['is_branch_admin'] && $context['branch_id']) {
                    $q->where('branch_id', $context['branch_id']);
                }
                
                if ($departmentId) {
                    $q->where('department_id', $departmentId);
                }

                if ($search && !$departmentId) {
                    $q->where('department', 'like', "%{$search}%");
                }

                $q->orderBy('department');
            }])
            ->orderBy('category_name')
            ->paginate(5);

        // Attach employee counts to each department
        foreach ($categories as $category) {
            if ($category->departments) {
                foreach ($category->departments as $department) {
                    $department->employee_count = $employeeCounts[$department->department_id] ?? 0;
                }
            }
        }

        // For datalist
        $departments = $this->getCommonQuery(Departments::class)
            ->with('category')
            ->select('department_id', 'department', 'department_category_id', 'description', 'created_at')
            ->orderBy('department')
            ->get();

        // Attach employee counts to departments list
        foreach ($departments as $department) {
            $department->employee_count = $employeeCounts[$department->department_id] ?? 0;
        }
            
        $selectedCategoryName = $categoryId
            ? DepartmentCategory::where('department_category_id', $categoryId)->value('category_name')
            : null;

        $selectedDepartmentName = $departmentId
            ? Departments::with('category')
                ->where('department_id', $departmentId)
                ->first()
            : null;
            
        return view('instituteAdmin.DashboardFiles.ViewAllCategoriesDepartment', [
            'categories'  => $categories,
            'departments' => $departments,
            'filters' => [
                'category_id'   => $categoryId,
                'department_id' => $departmentId,
                'search'        => $search,
            ],
            'user_type' => $context['is_branch_admin'] ? 'branch_admin' : 'institute_admin',
            'selectedCategoryName'   => $selectedCategoryName,
            'selectedDepartmentName' => $selectedDepartmentName,
        ]);
    }

    public function editDepartment(Request $request)
    {
        $request->validate([
            // 'department_id' => 'required|exists:departments,department_id',
            // 'department' => 'required|string|max:255',
            // 'description' => 'nullable|string|max:500',
        ]);

        $department = Departments::where('department_id', $request->department_id)->first();
        $oldName = $department->department;
        $department->update([
            'department' => $request->department,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'old_name' => $oldName,
            'department' => $department
        ]);
    }

    public function deleteDepartment(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,department_id',
        ]);

        $department = Departments::where('department_id', $request->department_id)->first();
        $department->delete();

        return response()->json([
            'success' => true,
            'department' => $department
        ]);
    }

    public function downloadCategoriesDepartments(Request $request)
    {
        $validated = $request->validate([
            'type'          => 'required|in:excel,csv,pdf',
            'ids'           => 'nullable|string',
            'select_all'    => 'nullable|boolean',
            'category_id'   => 'nullable|integer',
            'department_id' => 'nullable|integer',
            'search'        => 'nullable|string',
        ]);

        $context = $this->getInstituteBranchContext();

        $categoryId   = $request->category_id;
        $departmentId = $request->department_id;
        $search       = $request->search;

        $categoryQuery = $this->getCommonQuery(DepartmentCategory::class)
            ->when($categoryId, fn ($q) =>
                $q->where('department_category_id', $categoryId)
            );

        /**
         * ✅ SELECT ALL (respect filters from listing page)
         */
        if ($request->boolean('select_all')) {

            $categories = $categoryQuery
                ->with(['departments' => function ($q) use ($departmentId, $search) {

                    if ($departmentId) {
                        $q->where('department_id', $departmentId);
                    }

                    if ($search && !$departmentId) {
                        $q->where('department', 'LIKE', "%{$search}%");
                    }

                    $q->orderBy('department');
                }])
                ->orderBy('category_name')
                ->get()
                ->filter(fn ($cat) => $cat->departments->isNotEmpty())
                ->values();
        }

        /**
         * ✅ SELECTED DEPARTMENTS ONLY
         */
        elseif ($request->filled('ids')) {

            $departmentIds = array_filter(explode(',', $validated['ids']));

            $categories = $categoryQuery
                ->whereHas('departments', fn ($q) =>
                    $q->whereIn('department_id', $departmentIds)
                )
                ->with(['departments' => fn ($q) =>
                    $q->whereIn('department_id', $departmentIds)
                    ->orderBy('department')
                ])
                ->orderBy('category_name')
                ->get();
        }

        /**
         * ❌ Nothing selected
         */
        else {
            return back()->with('error', 'No items selected for export');
        }

        if ($categories->isEmpty()) {
            return back()->with('error', 'No data found for export');
        }
        
    
        $fileName = 'categories_departments_' . now()->format('Y_m_d_His');

        switch ($validated['type']) {
            case 'excel':
                return Excel::download(
                    new CategoryDepartmentExport($categories),
                    "{$fileName}.xlsx"
                );

            case 'csv':
                return Excel::download(
                    new CategoryDepartmentExport($categories),
                    "{$fileName}.csv"
                );

            case 'pdf':
                return Pdf::loadView(
                    'pdf.categories_departments_export',
                    compact('categories', 'context')
                )->download("{$fileName}.pdf");
        }
    }

}