<?php
namespace App\Http\Controllers\institute\Admin\PayrollPolicy;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProvidentFundPolicy;
use App\Models\Departments;
use App\Models\EmployeeDetails;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
class ProvidentFundPolicyController extends Controller
{
use InstituteBranchAccess, DepartmentRelationships;
    public function store(Request $request)
    {
         $context = $this->getInstituteBranchContext();
         // :white_check_mark: Check if user has institute access
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }
             
        // $request->merge(json_decode($request->getContent(), true));
        $validated = $request->validate([
            'financial_year' => 'required|string|max:20',
            'department_id' => 'nullable|string|max:50',
            'employee_id'   => 'nullable|string|max:50',
            'enable_pf' => 'required|in:0,1',
            'pf_employee_enabled' => 'required|in:0,1',
            'pf_employee_type' => 'nullable|required_if:pf_employee_enabled,1|in:percentage,fixed',
            'pf_employee_value' => 'nullable|required_if:pf_employee_enabled,1|numeric|min:0',
            'pf_employer_enabled' => 'required|in:0,1',
            'pf_employer_type' => 'nullable|required_if:pf_employer_enabled,1|in:percentage,fixed',
            'pf_employer_value' => 'nullable|required_if:pf_employer_enabled,1|numeric|min:0',
            'enable_esi' => 'required|in:0,1',
            'esi_employee_enabled' => 'required|in:0,1',
            'esi_employee_type' => 'nullable|required_if:esi_employee_enabled,1|in:percentage,fixed',
            'esi_employee_value' => 'nullable|required_if:esi_employee_enabled,1|numeric|min:0',
            'esi_employer_enabled' => 'required|in:0,1',
            'esi_employer_type' => 'nullable|required_if:esi_employer_enabled,1|in:percentage,fixed',
            'esi_employer_value' => 'nullable|required_if:esi_employer_enabled,1|numeric|min:0',
        ]);
        // :white_check_mark: STRING POLICY ID
        $validated['payroll_policy_id'] =
            'PAY' . strtoupper(substr(md5(uniqid()), 0, 8));
          // ✅ Store institute & branch IDs
        $validated['institute_id'] = $context['institute_id'];
        $validated['branch_id'] = $context['is_branch_admin']
            ? $context['branch_id']
            : null;
    
        $policy = ProvidentFundPolicy::create($validated);
        return response()->json([
            'success' => true,
            'data' => [
                'payroll_policy_id' => $policy->payroll_policy_id
            ]
        ], 201);
    }
    public function show($payroll_policy_id)
    {
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            throw new \Exception('You are not associated with any institute.');
        }
        $policy = $this->getCommonQuery(ProvidentFundPolicy::class)
            ->where('payroll_policy_id', $payroll_policy_id)
            ->first();
        if (!$policy) {
            return response()->json([
                'success' => false,
                'message' => 'Provident Fund policy not found.'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $policy
        ], 200);
    }
    public function index()
    {
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            throw new \Exception('You are not associated with any institute.');
        }
        $policies = $this->getCommonQuery(ProvidentFundPolicy::class)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'success' => true,
            'data' => $policies
        ], 200);
    }
    public function payrollPolicyDropdown()
    {
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            throw new \Exception('You are not associated with any institute.');
        }
        $policies = $this->getCommonQuery(ProvidentFundPolicy::class)
            ->orderBy('created_at', 'desc')
            ->get([
                'payroll_policy_id',
                'financial_year'
            ]);
        return response()->json([
            'success' => true,
            'data' => $policies
        ], 200);
    }
    public function getPayrollDepartment(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            throw new \Exception('You are not associated with any institute.');
        }
        $departments = $this->getCommonQuery(Departments::class)
            ->orderBy('department')
            ->get(['department_id', 'department']);
        return response()->json([
            'status' => true,
            'data' => $departments
        ]);
    }
    public function getPayrollDepartmentsById(Request $request, $departmentId)
    {
        $context = $this->getInstituteBranchContext();
        // Check if user has institute access
        if (!$context['institute_id']) {
            throw new \Exception('You are not associated with any institute.');
        }
        $department = $this->getCommonQuery(Departments::class)
            ->where('department_id', $departmentId)
            ->select('department')
            ->first();
        if($department){
            return response()->json([
                'status' => true,
                'data' => $department,
            ], 201);
        }else{
            return response()->json([
                'status' => false,
                'data' => " ",
            ], 400);
        }
    }
    
        public function getPayrollEmployee(Request $request, $employeeId = null)
        {
            $context = $this->getInstituteBranchContext();
            // Check institute access
            if (!$context['institute_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not associated with any institute.'
                ], 403);
            }
            $query = $this->getCommonQuery(EmployeeDetails::class)
                ->select('employee_id', 'employee_code', 'name');
            // If employeeId is provided → single employee
            if ($employeeId) {
                $employee = $query
                    ->where('employee_id', $employeeId)
                    ->first();
                if (!$employee) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Employee not found'
                    ], 404);
                }
                return response()->json([
                    'success' => true,
                    'data' => $employee
                ], 200);
            }
            // If no employeeId → all employees
            $employees = $query->orderBy('name')->get();
            return response()->json([
                'success' => true,
                'data' => $employees
            ], 200);
        }

        public function getEmployeesByDepartment(Request $request, $departmentId)
        {
            $context = $this->getInstituteBranchContext();
            // Check institute access
            if (!$context['institute_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not associated with any institute.'
                ], 403);
            }
            $employees = $this->getCommonQuery(EmployeeDetails::class)
                ->where('department_id', $departmentId)
                ->select('employee_id', 'employee_code', 'name')
                ->orderBy('name')
                ->get();
            if ($employees->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No employees found for this department'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $employees
            ], 200);
        }
        // public function getPayrollEmployee(Request $request, $employeeId)
    // {
    //     $context = $this->getInstituteBranchContext();
    //     // Check if user has institute access
    //     if (!$context['institute_id']) {
    //         throw new \Exception('You are not associated with any institute.');
    //     }
    //     $employeeid = $this->getCommonQuery(EmployeeDetails::class)
    //         ->where('employee_id', $employeeId)
    //         ->select('employee_code', 'name')
    //         ->first();
    //     if($employeeid){
    //         return response()->json([
    //             'success' => true,
    //             'data' => $employeeid,
    //         ], 201);
    //     }else{
    //         return response()->json([
    //             'success' => false,
    //             'data' => " ",
    //         ], 400);
    //     }
    // }


}