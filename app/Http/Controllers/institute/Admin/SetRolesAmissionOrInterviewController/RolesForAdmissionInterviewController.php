<?php
namespace App\Http\Controllers\institute\Admin\SetRolesAmissionOrInterviewController;

use App\Http\Controllers\Controller;

use App\Models\RolesForAdmissionInterviewProcess;
use App\Models\EmployeeDetails;
use App\Models\Departments;
use App\Models\FincapMerchantSubCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RolesForAdmissionInterviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $instituteId = Auth::user()->institute_id;
        $query = RolesForAdmissionInterviewProcess::with(['employee', 'department'])->where('institute_id',$instituteId);

        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('employee_code', 'LIKE', "%{$search}%");
            });
        }

        $roles = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get filter data
        $departments = Departments::where('institute_id',$instituteId)->get();
        $employees = EmployeeDetails::where('institute_id',$instituteId)->get();

        return view('roles.index', compact('roles', 'departments', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $instituteId = Auth::user()->institute_id;
        $employees = EmployeeDetails::where('status', 'active')->where('institute_id', $instituteId)->get();
        $departments = Departments::where('status', 'active')->where('institute_id', $instituteId)->get();

        
        return view('roles.create', compact('employees', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|string|unique:roles_for_admission_interivew_process,employee_id',
            'type' => 'required|in:counsellor,agent,interviewer',
            'department_id' => 'nullable|string',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'string',
            'status' => 'required|in:active,leave,detained,inactive,terminated'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $instituteId = Auth::user()->institute_id;
         $prefixMap = [
            'counsellor' => 'CNS',
            'agent' => 'AGT',
            'interviewer' => 'INTV',
        ];

        $prefix = $prefixMap[strtolower($request->type)] ?? 'ROL';

        $role = RolesForAdmissionInterviewProcess::create([
            'institute_id' => $instituteId ?? null,
            'branch_id' => $request->branch_id ?? null,
            'role_hash_id' => $prefix . '-' . strtoupper(Str::random(8)),
            'employee_id' => $request->employee_id,
            'type' => $request->type,
            'department_id' => $request->department_id,
            'class_id' => $request->class_ids,
            'status' => $request->status
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role assigned successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $role = RolesForAdmissionInterviewProcess::with(['employee', 'department'])
            ->findOrFail($id);
        
        // Load classes if any
        $classes = [];
        if ($role->class_id) {
            $classes = FincapMerchantSubCategories::whereIn('id', $role->class_id)->get();
        }

        return view('roles.show', compact('role', 'classes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = RolesForAdmissionInterviewProcess::findOrFail($id);
        $employees = EmployeeDetails::where('status', 'active')->get();
        $departments = Departments::where('status', 'active')->get();
        
        // Load classes if any
        $selectedClasses = [];
        if ($role->class_id) {
            $selectedClasses = FincapMerchantSubCategories::whereIn('id', $role->class_id)->get();
        }

        return view('roles.edit', compact('role', 'employees', 'departments', 'selectedClasses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $role = RolesForAdmissionInterviewProcess::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'institute_id' => 'required|string',
            'branch_id' => 'required|string',
            'employee_id' => 'required|string|unique:roles_for_admission_interivew_process,employee_id,' . $id,
            'type' => 'required|in:counsellor,agent,interviewer',
            'department_id' => 'nullable|string',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'string',
            'status' => 'required|in:active,leave,detained,inactive,terminated'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $role->update([
            'institute_id' => $request->institute_id,
            'branch_id' => $request->branch_id,
            'employee_id' => $request->employee_id,
            'type' => $request->type,
            'department_id' => $request->department_id,
            'class_id' => $request->class_ids,
            'status' => $request->status
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = RolesForAdmissionInterviewProcess::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Load classes by department (AJAX)
     */
    public function loadClassesByDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Department ID is required'
            ], 400);
        }
        $instituteId = Auth::user()->institute_id;
        $courses = FincapMerchantSubCategories::where('finacp_merchant_sub_category_id', $request->department_id)
            ->where('status', 'Active')
            ->where('institute_id',$instituteId)
            ->get(['finacp_merchant_sub_category_id', 'finacp_merchant_sub_category_type']);

        return response()->json([
            'status' => 'success',
            'courses' => $courses
        ]);
    }

    /**
     * Get employees by type (AJAX for filtering)
     */
    public function getEmployeesByType(Request $request)
    {
        $query = EmployeeDetails::where('status', 'active');

        if ($request->filled('type')) {
            // Add logic based on employee type if needed
            $query->where('employee_type', $request->type);
        }

        $employees = $query->get(['id', 'name', 'employee_code']);

        return response()->json([
            'status' => 'success',
            'employees' => $employees
        ]);
    }
}