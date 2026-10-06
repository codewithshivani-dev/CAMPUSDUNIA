<?php

namespace App\Http\Controllers\institute\Admin\OutPassController;
use App\Http\Controllers\Controller;

use App\Models\OutPassEmployee;
use App\Models\EmployeeDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OutPassEmployeeController extends Controller
{
    /**
     * Display a listing of employees.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = OutPassEmployee::orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('employee_id', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('department', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $employees = $query->paginate(15)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $employees
            ]);
        }

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new employee.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employees.create');
    }

    /**
     * Store a newly created employee.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
   
        // $validator = Validator::make($request->all(), [
        //     'employee_id' => 'required',
        //     'full_name' => 'required',
        //     'contact_number' => 'required',
        //     'address' => 'required',
        //     'department' => 'required',
        //     'designation' => 'nullable',
        //     'email' => 'required',
        //     'reporting_manager' => 'nullable',
        //     'emergency_contact' => 'required',
        //     'joining_date' => 'required',
        //     'institute_id' => 'nullable',
        //     'branch_id' => 'nullable',
        //     'user_id' => 'nullable',
        //     'emergency_contact_name' => 'nullable',
        //     'salary' => 'nullable',
        //     'employment_type' => 'required',
        //     'work_shift' => 'nullable',
        // ]);
        
        // if ($validator->fails()) {
        //     if ($request->wantsJson()) {
        //         return response()->json([
        //             'success' => false,
        //             'errors' => $validator->errors()
        //         ], 422);
        //     }
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        // try {
            DB::beginTransaction();

            $employee = OutPassEmployee::create($request->all());

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Employee created successfully.',
                    'data' => $employee
                ], 201);
            }

            return redirect()->route('employees.index')
                ->with('success', 'Employee created successfully.');

        // } catch (\Exception $e) {
        //     DB::rollBack();
            
        //     $error = 'Failed to create employee: ' . $e->getMessage();
            
        //     if ($request->wantsJson()) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => $error
        //         ], 500);
        //     }
            
        //     return redirect()->back()
        //         ->with('error', $error)
        //         ->withInput();
        // }
    }

    /**
     * Display the specified employee.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = OutPassEmployee::findOrFail($id);
        // Get employee's out passes
        $outPasses = \App\Models\OutPassMigration::where('requester_type', OutPassEmployee::class)
            ->where('requester_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'employee' => $employee,
                    'out_passes' => $outPasses
                ]
            ]);
        }

        return view('employees.show', compact('employee', 'outPasses'));
    }

    /**
     * Show the form for editing the specified employee.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = OutPassEmployee::findOrFail($id);
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the specified employee.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */ 
    public function update(Request $request, $id)
    {
        $employee = OutPassEmployee::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|unique:out_pass_employees,employee_id,' . $id,
            'full_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string',
            'department' => 'required|string|max:100',
            'designation' => 'required|string|max:100',
            'email' => 'required|email|unique:out_pass_employees,email,' . $id,
            'reporting_manager' => 'required|string|max:255',
            'emergency_contact' => 'required|string|max:20',
            'joining_date' => 'required|date',
            'emergency_contact_name' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'employment_type' => 'required|in:permanent,contract,trainee,intern',
            'work_shift' => 'required|in:morning,evening,night,general',
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

            $employee->update($request->all());

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Employee updated successfully.',
                    'data' => $employee
                ]);
            }

            return redirect()->route('employees.show', $id)
                ->with('success', 'Employee updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            $error = 'Failed to update employee: ' . $e->getMessage();
            
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
     * Remove the specified employee.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = OutPassEmployee::findOrFail($id);

        // Check if employee has any pending out passes
        $hasActivePasses = \App\Models\OutPassMigration::where('requester_type', OutPassEmployee::class)
            ->where('requester_id', $employee->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($hasActivePasses) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete employee with active out passes.'
                ], 403);
            }
            return redirect()->route('employees.index')
                ->with('error', 'Cannot delete employee with active out passes.');
        }

        $employee->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Employee deleted successfully.'
            ]);
        }

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

    /**
     * Fetch employee details by ID (AJAX) - Used by blade form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fetchEmployee(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Employee ID is required'
            ], 422);
        }

        $employee = EmployeeDetails::with('department','designationRelation')->where('employee_id', $request->employee_id)->first();
      
        if ($employee) {
            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $employee->name,
                    'contact' => $employee->mobile_number,
                    'address' => $employee->addressline1,
                    'dob' => $employee->dob,
                    'department' =>$employee->getRelation('department')->department ,
                    'designation' => $employee->designationRelation->designation,
                    'email' => $employee->email,
                    'manager' => $employee->reporting_manager,
                    'emergencyContact' => $employee->emergency_contact_number,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Employee not found'
        ], 404);
    }

    /**
     * Toggle employee active status.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus($id)
    {
        $employee = OutPassEmployee::findOrFail($id);
        $employee->is_active = !$employee->is_active;
        $employee->save();

        $status = $employee->is_active ? 'activated' : 'deactivated';

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Employee {$status} successfully.",
                'is_active' => $employee->is_active
            ]);
        }

        return redirect()->back()
            ->with('success', "Employee {$status} successfully.");
    }

    /**
     * Search employees (AJAX).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $search = $request->get('q', '');
        
        $employees = OutPassEmployee::where('full_name', 'LIKE', "%{$search}%")
            ->orWhere('employee_id', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->orWhere('department', 'LIKE', "%{$search}%")
            ->where('is_active', true)
            ->limit(10)
            ->get(['id', 'employee_id', 'full_name', 'email', 'department', 'designation']);

        return response()->json($employees);
    }

    /**
     * Get employees by department.
     *
     * @param  string  $department
     * @return \Illuminate\Http\Response
     */
    public function getByDepartment($department)
    {
        $employees = OutPassEmployee::where('department', $department)
            ->where('is_active', true)
            ->get(['id', 'employee_id', 'full_name', 'designation']);

        return response()->json([
            'success' => true,
            'data' => $employees
        ]);
    }

    /**
     * Get employee statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics()
    {
        $stats = [
            'total' => OutPassEmployee::count(),
            'active' => OutPassEmployee::where('is_active', true)->count(),
            'inactive' => OutPassEmployee::where('is_active', false)->count(),
            'by_department' => OutPassEmployee::where('is_active', true)
                ->select('department', \DB::raw('count(*) as total'))
                ->groupBy('department')
                ->get(),
            'by_employment_type' => OutPassEmployee::where('is_active', true)
                ->select('employment_type', \DB::raw('count(*) as total'))
                ->groupBy('employment_type')
                ->get(),
            'by_work_shift' => OutPassEmployee::where('is_active', true)
                ->select('work_shift', \DB::raw('count(*) as total'))
                ->groupBy('work_shift')
                ->get(),
        ];

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        }

        return view('employees.statistics', compact('stats'));
    }
}