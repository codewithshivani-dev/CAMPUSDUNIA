<?php
namespace App\Http\Controllers\institute\Admin\PayrollPolicy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProvidentFundPolicy;
use App\Models\Departments;
use App\Models\EmployeeDetails;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use App\Models\PayrollPolicyAllowance;
use App\Models\PayrollPolicyTaxDeduction;
use App\Models\PayrollPolicyOtherDeduction;
use App\Models\EmployeeSalaryStructure;
use App\Models\PayrollPolicyLog;
use App\Models\PayrollPolicyBonusOvertime;
use Illuminate\Support\Facades\DB;

class ProvidentFundPolicyController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;

    // public function store(Request $request)
    // {
    //     $context = $this->getInstituteBranchContext();

    //     if (!$context['institute_id']) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'You are not associated with any institute.'
    //         ], 403);
    //     }

    //     try {
    //         $validated = $request->validate([
    //             'financial_year' => 'required|string|max:20',
    //             'department_id' => 'nullable|string|max:50',
    //             'employee_id' => 'nullable|string|max:50',
    //             'payroll_type' => 'required|in:department,employee',
    //             'policy_employment_type' => 'required|in:full-time,part-time,probation,contractual,appraisal,promotion',
    //             'override' => 'nullable|boolean',
    //             'enable_pf' => 'required|in:0,1',
    //             'pf_employee_enabled' => 'required|in:0,1',
    //             'pf_employee_type' => 'nullable|required_if:pf_employee_enabled,1|in:percentage,fixed',
    //             'pf_employee_value' => 'nullable|required_if:pf_employee_enabled,1|numeric|min:0',
    //             'pf_employer_enabled' => 'required|in:0,1',
    //             'pf_employer_type' => 'nullable|required_if:pf_employer_enabled,1|in:percentage,fixed',
    //             'pf_employer_value' => 'nullable|required_if:pf_employer_enabled,1|numeric|min:0',
    //             'enable_esi' => 'required|in:0,1',
    //             'esi_employee_enabled' => 'required|in:0,1',
    //             'esi_employee_type' => 'nullable|required_if:esi_employee_enabled,1|in:percentage,fixed',
    //             'esi_employee_value' => 'nullable|required_if:esi_employee_enabled,1|numeric|min:0',
    //             'esi_employer_enabled' => 'required|in:0,1',
    //             'esi_employer_type' => 'nullable|required_if:esi_employer_enabled,1|in:percentage,fixed',
    //             'esi_employer_value' => 'nullable|required_if:esi_employer_enabled,1|numeric|min:0',
    //             'enable_nps' => 'required|in:0,1',
    //             'nps_employee_enabled' => 'required|in:0,1',
    //             'nps_employee_type' => 'nullable|required_if:nps_employee_enabled,1|in:percentage,fixed',
    //             'nps_employee_value' => 'nullable|required_if:nps_employee_enabled,1|numeric|min:0',
    //             'nps_employer_enabled' => 'required|in:0,1',
    //             'nps_employer_type' => 'nullable|required_if:nps_employer_enabled,1|in:percentage,fixed',
    //             'nps_employer_value' => 'nullable|required_if:nps_employer_enabled,1|numeric|min:0',
    //         ]);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation failed',
    //             'errors' => $e->errors()
    //         ], 422);
    //     }

    //     // Mode Handling
    //     if ($validated['payroll_type'] === 'employee') {
    //         if (empty($validated['employee_id'])) {
    //             return response()->json([
    //                 'success' => false, 
    //                 'message' => 'Employee ID is required'
    //             ], 422);
    //         }
    //         $employee = EmployeeDetails::where('employee_id', $validated['employee_id'])->first();
    //         if (!$employee) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Employee not found'
    //             ], 404);
    //         }
    //         $validated['department_id'] = $employee->department_id;
    //     } else {
    //         if (empty($validated['department_id'])) {
    //             return response()->json([
    //                 'success' => false, 
    //                 'message' => 'Department ID is required'
    //             ], 422);
    //         }
    //         $validated['employee_id'] = null;
    //     }

    //     try {
    //         // Check Existing Policy
    //         $query = ProvidentFundPolicy::where('institute_id', $context['institute_id'])
    //             ->where('financial_year', $validated['financial_year'])
    //             ->where('payroll_type', $validated['payroll_type'])
    //             ->where('policy_employment_type', $validated['policy_employment_type']);

    //         if ($validated['payroll_type'] === 'employee') {
    //             $query->where('employee_id', $validated['employee_id']);
    //         } else {
    //             $query->where('department_id', $validated['department_id'])->whereNull('employee_id');
    //         }

    //         $existingPolicy = $query->first();
    //         $override = filter_var($request->input('override', false), FILTER_VALIDATE_BOOLEAN);

    //         // Check for existing salary structures when policy exists
    //         $hasActiveStructures = false;
    //         $structuresCount = 0;
            
    //         if ($existingPolicy) {
    //             $structuresQuery = EmployeeSalaryStructure::where('payroll_policy_id', $existingPolicy->payroll_policy_id);
                
    //             if ($validated['payroll_type'] === 'employee') {
    //                 $structuresQuery->where('employee_id', $validated['employee_id']);
    //             } elseif ($validated['payroll_type'] === 'department') {
    //                 $structuresQuery->whereHas('employee', function($q) use ($validated) {
    //                     $q->where('department_id', $validated['department_id']);
    //                 });
    //             }
                
    //             $structuresCount = $structuresQuery->count();
    //             $hasActiveStructures = $structuresCount > 0;
    //         }

    //         if ($existingPolicy && !$override) {
    //             return response()->json([
    //                 'success' => false,
    //                 'exists' => true,
    //                 'has_structures' => $hasActiveStructures,
    //                 'structures_count' => $structuresCount,
    //                 'message' => 'Policy already exists for this selection.',
    //                 'data' => [
    //                     'payroll_policy_id' => $existingPolicy->payroll_policy_id,
    //                     'structures_count' => $structuresCount
    //                 ]
    //             ], 409);
    //         }

    //         // Prepare policy data
    //         $policyData = $validated;
    //         $policyData['payroll_policy_id'] = $existingPolicy ? $existingPolicy->payroll_policy_id : 'PAY' . strtoupper(substr(md5(uniqid()), 0, 8));
    //         $policyData['institute_id'] = $context['institute_id'];
    //         $policyData['branch_id'] = $context['is_branch_admin'] ? $context['branch_id'] : null;

    //         if ($existingPolicy && $override) {
    //             // Disable existing salary structures if any
    //             if ($hasActiveStructures) {
    //                 EmployeeSalaryStructure::where('payroll_policy_id', $existingPolicy->payroll_policy_id)
    //                     ->where(function($q) use ($validated) {
    //                         if ($validated['payroll_type'] === 'employee') {
    //                             $q->where('employee_id', $validated['employee_id']);
    //                         } elseif ($validated['payroll_type'] === 'department') {
    //                             $q->whereHas('employee', function($sq) use ($validated) {
    //                                 $sq->where('department_id', $validated['department_id']);
    //                             });
    //                         }
    //                     })
    //                     ->update(['status' => 'inactive']);
    //             }
                
    //             $oldFull = $this->getFullPolicySnapshot($existingPolicy->payroll_policy_id);
    //             $existingPolicy->update($policyData);
                
    //             PayrollPolicyAllowance::where('payroll_policy_id', $existingPolicy->payroll_policy_id)
    //                 ->update(['custom_allowances' => []]);
                
    //             PayrollPolicyOtherDeduction::where('payroll_policy_id', $existingPolicy->payroll_policy_id)
    //                 ->update(['custom_deductions' => []]);

    //             $newFull = $this->getFullPolicySnapshot($existingPolicy->payroll_policy_id);
    //             $this->logChange($existingPolicy, 'Override', 'Base Policy', $oldFull, $newFull);

    //             return response()->json([
    //                 'success' => true,
    //                 'message' => $hasActiveStructures ? 'Policy overridden successfully. Existing salary structures have been disabled.' : 'Policy overridden successfully',
    //                 'data' => ['payroll_policy_id' => $existingPolicy->payroll_policy_id]
    //             ], 200);
    //         }

    //         // Create New
    //         $policy = ProvidentFundPolicy::create($policyData);
            
    //         $this->logChange($policy, 'Creation', 'Full Policy', null, $this->getFullPolicySnapshot($policy->payroll_policy_id));

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Policy created successfully',
    //             'data' => ['payroll_policy_id' => $policy->payroll_policy_id]
    //         ], 201);

    //     } catch (\Exception $e) {
    //         \Log::error('Error in ProvidentFundPolicyController@store: ' . $e->getMessage(), [
    //             'trace' => $e->getTraceAsString()
    //         ]);
            
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred while saving the policy: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function store(Request $request)
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        try {
            $validated = $request->validate([
                'financial_year' => 'required|string|max:20',
                'department_id' => 'nullable|string|max:50',
                'employee_id' => 'nullable|string|max:50',
                'payroll_type' => 'required|in:department,employee',
                'policy_employment_type' => 'required|in:full-time,part-time,probation,contractual,appraisal,promotion',
                'override' => 'nullable|boolean',
                'enable_pf' => 'required|in:0,1',
                'pf_wage_limit' => 'nullable|numeric|min:0|max:100000',
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
                'enable_nps' => 'required|in:0,1',
                'nps_employee_enabled' => 'required|in:0,1',
                'nps_employee_type' => 'nullable|required_if:nps_employee_enabled,1|in:percentage,fixed',
                'nps_employee_value' => 'nullable|required_if:nps_employee_enabled,1|numeric|min:0',
                'nps_employer_enabled' => 'required|in:0,1',
                'nps_employer_type' => 'nullable|required_if:nps_employer_enabled,1|in:percentage,fixed',
                'nps_employer_value' => 'nullable|required_if:nps_employer_enabled,1|numeric|min:0',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        // Mode Handling
        if ($validated['payroll_type'] === 'employee') {
            if (empty($validated['employee_id'])) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Employee ID is required'
                ], 422);
            }
            $employee = EmployeeDetails::where('employee_id', $validated['employee_id'])->first();
            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found'
                ], 404);
            }
            $validated['department_id'] = $employee->department_id;
        } else {
            if (empty($validated['department_id'])) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Department ID is required'
                ], 422);
            }
            $validated['employee_id'] = null;
        }

        try {
            // Check Existing Policy
            $query = ProvidentFundPolicy::where('institute_id', $context['institute_id'])
                ->where('financial_year', $validated['financial_year'])
                ->where('payroll_type', $validated['payroll_type'])
                ->where('policy_employment_type', $validated['policy_employment_type']);

            if ($validated['payroll_type'] === 'employee') {
                $query->where('employee_id', $validated['employee_id']);
            } else {
                $query->where('department_id', $validated['department_id'])->whereNull('employee_id');
            }

            $existingPolicy = $query->first();
            $override = filter_var($request->input('override', false), FILTER_VALIDATE_BOOLEAN);

            // Check for existing salary structures when policy exists
            $hasActiveStructures = false;
            $structuresCount = 0;
            
            if ($existingPolicy) {
                $structuresQuery = EmployeeSalaryStructure::where('payroll_policy_id', $existingPolicy->payroll_policy_id);
                
                if ($validated['payroll_type'] === 'employee') {
                    $structuresQuery->where('employee_id', $validated['employee_id']);
                } elseif ($validated['payroll_type'] === 'department') {
                    $structuresQuery->whereHas('employee', function($q) use ($validated) {
                        $q->where('department_id', $validated['department_id']);
                    });
                }
                
                $structuresCount = $structuresQuery->count();
                $hasActiveStructures = $structuresCount > 0;
            }

            if ($existingPolicy && !$override) {
                return response()->json([
                    'success' => false,
                    'exists' => true,
                    'has_structures' => $hasActiveStructures,
                    'structures_count' => $structuresCount,
                    'message' => 'Policy already exists for this selection.',
                    'data' => [
                        'payroll_policy_id' => $existingPolicy->payroll_policy_id,
                        'structures_count' => $structuresCount
                    ]
                ], 409);
            }

            // Prepare policy data
            $policyData = $validated;
            $policyData['payroll_policy_id'] = $existingPolicy ? $existingPolicy->payroll_policy_id : 'PAY' . strtoupper(substr(md5(uniqid()), 0, 8));
            $policyData['institute_id'] = $context['institute_id'];
            $policyData['branch_id'] = $context['is_branch_admin'] ? $context['branch_id'] : null;

            // In the store method, replace the existing policy update section:

            if ($existingPolicy && $override) {
                // Disable existing salary structures if any
                if ($hasActiveStructures) {
                    EmployeeSalaryStructure::where('payroll_policy_id', $existingPolicy->payroll_policy_id)
                        ->where(function($q) use ($validated) {
                            if ($validated['payroll_type'] === 'employee') {
                                $q->where('employee_id', $validated['employee_id']);
                            } elseif ($validated['payroll_type'] === 'department') {
                                $q->whereHas('employee', function($sq) use ($validated) {
                                    $sq->where('department_id', $validated['department_id']);
                                });
                            }
                        })
                        ->update(['status' => 'inactive']);
                }
                
                $oldFull = $this->getFullPolicySnapshot($existingPolicy->payroll_policy_id);
                $existingPolicy->update($policyData);
                
                PayrollPolicyAllowance::where('payroll_policy_id', $existingPolicy->payroll_policy_id)
                    ->update(['custom_allowances' => []]);
                
                PayrollPolicyOtherDeduction::where('payroll_policy_id', $existingPolicy->payroll_policy_id)
                    ->update(['custom_deductions' => []]);

                $newFull = $this->getFullPolicySnapshot($existingPolicy->payroll_policy_id);
                
                // Log as Override - this will update existing log or create new one
                $this->logChange($existingPolicy, 'Override', 'Full Policy', $oldFull, $newFull);

                return response()->json([
                    'success' => true,
                    'message' => $hasActiveStructures ? 'Policy overridden successfully. Existing salary structures have been disabled.' : 'Policy overridden successfully',
                    'data' => ['payroll_policy_id' => $existingPolicy->payroll_policy_id]
                ], 200);
            }

            // In the create section:
            // Create New
            $policy = ProvidentFundPolicy::create($policyData);

            // Log as Creation - this will create a new log entry
            $this->logChange($policy, 'Creation', 'Full Policy', null, $this->getFullPolicySnapshot($policy->payroll_policy_id));

            return response()->json([
                'success' => true,
                'message' => 'Policy created successfully',
                'data' => ['payroll_policy_id' => $policy->payroll_policy_id]
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Error in ProvidentFundPolicyController@store: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving the policy: ' . $e->getMessage()
            ], 500);
        }
    }


    public function saveBonusOvertime(Request $request)
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $validated = $request->validate([
            'payroll_policy_id' => 'required|string',
            'overtime_enabled' => 'nullable|in:0,1',
            'bonuses' => 'nullable|array',
            'bonuses.*.name' => 'required|string|max:100',
            'bonuses.*.enabled' => 'nullable|in:0,1',
            'bonuses.*.month' => 'nullable|string|max:20',
            'bonuses.*.value' => 'nullable|numeric|min:0',
            'bonuses.*.type' => 'nullable|in:fixed,percentage',
        ]);

        // Check if policy exists
        $policy = $this->getCommonQuery(ProvidentFundPolicy::class)
            ->where('payroll_policy_id', $validated['payroll_policy_id'])
            ->first();

        if (!$policy) {
            return response()->json([
                'success' => false,
                'message' => 'Policy not found'
            ], 404);
        }

        // Prepare bonuses data
        $bonusesData = [];
        if (isset($validated['bonuses']) && is_array($validated['bonuses'])) {
            foreach ($validated['bonuses'] as $bonus) {
                if (!empty($bonus['name'])) {
                    $bonusesData[] = [
                        'name' => $bonus['name'],
                        'enabled' => isset($bonus['enabled']) ? (int)$bonus['enabled'] : 1,
                        'month' => $bonus['month'] ?? '',
                        'value' => isset($bonus['value']) ? (float)$bonus['value'] : 0,
                        'type' => $bonus['type'] ?? 'fixed',
                    ];
                }
            }
        }

        // Prepare data for update/create
        $data = [
            'payroll_policy_id' => $validated['payroll_policy_id'],
            'institute_id' => $context['institute_id'],
            'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
            'overtime_enabled' => isset($validated['overtime_enabled']) ? (int)$validated['overtime_enabled'] : 0,
            'bonuses' => $bonusesData,
        ];

        // Log before update
        $oldSnapshot = $this->getFullPolicySnapshot($validated['payroll_policy_id']);

        $bonusOvertime = PayrollPolicyBonusOvertime::updateOrCreate(
            [
                'payroll_policy_id' => $validated['payroll_policy_id'],
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
            ],
            $data
        );

        $newSnapshot = $this->getFullPolicySnapshot($validated['payroll_policy_id']);

        $this->logChange(
            $policy,
            'Edit',
            'Bonus/Overtime',
            $oldSnapshot,
            $newSnapshot
        );

        return response()->json([
            'success' => true,
            'message' => 'Bonus & Overtime configuration saved successfully',
            'data' => [
                'bonus_overtime' => $bonusOvertime,
                'bonuses_count' => count($bonusesData)
            ]
        ]);
    }

    /**
     * Get Bonus & Overtime configuration for a policy
     */
    public function getBonusOvertime($payrollPolicyId)
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $bonusOvertime = PayrollPolicyBonusOvertime::where('payroll_policy_id', $payrollPolicyId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$bonusOvertime) {
            return response()->json([
                'success' => true,
                'data' => [
                    'overtime_enabled' => 0,
                    'bonuses' => []
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $bonusOvertime
        ]);
    }


    // ==================== EXISTING METHODS (keeping as is) ====================

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
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        $query = $this->getCommonQuery(EmployeeDetails::class)
            ->select('employee_id', 'employee_code', 'name');
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
        $employees = $query->orderBy('name')->get();
        return response()->json([
            'success' => true,
            'data' => $employees
        ], 200);
    }

    public function getEmployeesByDepartment(Request $request, $departmentId)
    {
        $context = $this->getInstituteBranchContext();
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

    public function getEmployeeDetails($employeeId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        
        $employee = $this->getCommonQuery(EmployeeDetails::class)
            ->where('employee_id', $employeeId)
            ->with('department')
            ->first();
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'employee_id' => $employee->employee_id,
                'employee_code' => $employee->employee_code,
                'name' => $employee->name,
                'department' => $employee->department ? $employee->department->department : null,
                'department_id' => $employee->department_id,
                'designation' => $employee->designation
            ]
        ]);
    }

    public function viewPolicyManagement(Request $request)
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return redirect()->back()
                ->with('error', 'You are not associated with any institute.');
        }

        $financialYear = $request->get(
            'financial_year',
            date('m') < 4
                ? (date('Y') - 1) . '-' . date('Y')
                : date('Y') . '-' . (date('Y') + 1)
        );

        $status = $request->get('status', '');
        $search = trim($request->get('search', ''));
        $selectedDepartmentId = $request->get('department_id', '');

        $policyQuery = ProvidentFundPolicy::where(
            'institute_id',
            $context['institute_id']
        );

        if ($financialYear) {
            $policyQuery->where('financial_year', $financialYear);
        }

        if ($status) {
            $policyQuery->where('status', $status);
        }

        if (!empty($selectedDepartmentId)) {
            $policyQuery->where('department_id', $selectedDepartmentId);
        }

        $employeeIds = [];
        $departmentIds = [];

        if ($search) {
            $matchedEmployees = EmployeeDetails::where(
                    'institute_id',
                    $context['institute_id']
                )
                ->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('employee_code', 'LIKE', "%{$search}%");
                })
                ->get(['employee_id', 'department_id']);

            $employeeIds = $matchedEmployees
                ->pluck('employee_id')
                ->toArray();

            $employeeDepartmentIds = $matchedEmployees
                ->pluck('department_id')
                ->filter()
                ->toArray();

            $searchDepartmentIds = Departments::where(
                    'institute_id',
                    $context['institute_id']
                )
                ->where('department', 'LIKE', "%{$search}%")
                ->pluck('department_id')
                ->toArray();

            $departmentIds = array_unique(array_merge(
                $employeeDepartmentIds,
                $searchDepartmentIds
            ));

            if (!empty($employeeIds) || !empty($departmentIds)) {
                $policyQuery->where(function ($q) use (
                    $employeeIds,
                    $departmentIds
                ) {
                    if (!empty($employeeIds)) {
                        $q->orWhereIn('employee_id', $employeeIds);
                    }
                    if (!empty($departmentIds)) {
                        $q->orWhereIn('department_id', $departmentIds);
                    }
                });
            } else {
                $policyQuery->whereRaw('1 = 0');
            }
        }

        $policies = $policyQuery
            ->orderBy('created_at', 'desc')
            ->get();

        $policyIds = $policies->pluck('payroll_policy_id')->toArray();

        $policiesPaginator = (clone $policyQuery)
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'page', max(1, (int) $request->get('page', 1)))
            ->appends($request->query());

        $departmentQuery = $this->getCommonQuery(Departments::class)
            ->orderBy('department');

            if ($search) {
                if (!empty($searchDepartmentIds)) {
                    $departmentQuery->whereIn(
                        'department_id',
                        $searchDepartmentIds
                    );
                } elseif (!empty($employeeDepartmentIds)) {
                    $departmentQuery->whereIn(
                        'department_id',
                        $employeeDepartmentIds
                    );
                } else {
                    $departmentQuery->whereRaw('1 = 0');
                }
            }

        $departments = $departmentQuery->get();

        $employeesQuery = $this->getCommonQuery(EmployeeDetails::class)
            ->select(
                'employee_id',
                'employee_code',
                'name',
                'department_id'
            )
            ->orderBy('name');

        if ($search) {
            if (!empty($employeeIds)) {
                $employeesQuery->whereIn(
                    'employee_id',
                    $employeeIds
                );
            } elseif (!empty($searchDepartmentIds)) {
                $employeesQuery->whereIn(
                    'department_id',
                    $searchDepartmentIds
                );
            } else {
                $employeesQuery->whereRaw('1 = 0');
            }
        }

        $employees = $employeesQuery->get();

        $allowances = PayrollPolicyAllowance::whereIn(
                'payroll_policy_id',
                $policyIds
            )
            ->where('institute_id', $context['institute_id'])
            ->get()
            ->keyBy('payroll_policy_id');

        $taxDeductions = PayrollPolicyTaxDeduction::whereIn(
                'payroll_policy_id',
                $policyIds
            )
            ->where('institute_id', $context['institute_id'])
            ->get()
            ->keyBy('payroll_policy_id');

        $otherDeductions = PayrollPolicyOtherDeduction::whereIn(
                'payroll_policy_id',
                $policyIds
            )
            ->where('institute_id', $context['institute_id'])
            ->get()
            ->keyBy('payroll_policy_id');

        $bonusOvertimes = PayrollPolicyBonusOvertime::whereIn(
                'payroll_policy_id',
                $policyIds
            )
            ->where('institute_id', $context['institute_id'])
            ->get()
            ->keyBy('payroll_policy_id');

        $financialYears = ProvidentFundPolicy::where(
                'institute_id',
                $context['institute_id']
            )
            ->select('financial_year')
            ->distinct()
            ->orderBy('financial_year', 'desc')
            ->pluck('financial_year')
            ->toArray();

        return view('instituteAdmin.Payroll.PolicyManagement', [
            'policies' => $policies,
            'policiesPaginator' => $policiesPaginator,
            'allowances' => $allowances,
            'taxDeductions' => $taxDeductions,
            'otherDeductions' => $otherDeductions,
            'bonusOvertimes' => $bonusOvertimes,
            'departments' => $departments,
            'employees' => $employees,
            'financialYears' => $financialYears,
            'selectedFinancialYear' => $financialYear,
            'selectedStatus' => $status,
            'selectedDepartmentId' => $selectedDepartmentId,
            'searchQuery' => $search
        ]);
    }

    public function viewPolicyDetails($policyId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        $policy = $this->getCommonQuery(ProvidentFundPolicy::class)
            ->where('payroll_policy_id', $policyId)
            ->first();

        if (!$policy) {
            abort(404, 'Policy not found');
        }

        $allowance = PayrollPolicyAllowance::where('payroll_policy_id', $policyId)
            ->where('institute_id', $context['institute_id'])
            ->first();
            
        $taxDeduction = PayrollPolicyTaxDeduction::where('payroll_policy_id', $policyId)
            ->where('institute_id', $context['institute_id'])
            ->first();
            
        $otherDeduction = PayrollPolicyOtherDeduction::where('payroll_policy_id', $policyId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        $bonusOvertime = PayrollPolicyBonusOvertime::where('payroll_policy_id', $policyId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        $targetName = null;
        $targetType = null;
        
        if ($policy->employee_id) {
            $employee = $this->getCommonQuery(EmployeeDetails::class)
                ->where('employee_id', $policy->employee_id)
                ->first();
            $targetName = $employee ? $employee->name : 'Unknown Employee';
            $targetType = 'employee';
        } elseif ($policy->department_id) {
            $department = $this->getCommonQuery(Departments::class)
                ->where('department_id', $policy->department_id)
                ->first();
            $targetName = $department ? $department->department : 'Unknown Department';
            $targetType = 'department';
        } else {
            $targetType = 'global';
            $targetName = 'All Employees';
        }

        return view('instituteAdmin.Payroll.PolicyDetails', [
            'policy' => $policy,
            'allowance' => $allowance,
            'taxDeduction' => $taxDeduction,
            'otherDeduction' => $otherDeduction,
            'bonusOvertime' => $bonusOvertime,
            'targetName' => $targetName,
            'targetType' => $targetType
        ]);
    }

    public function checkDuplicate(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        
        $validated = $request->validate([
            'financial_year' => 'required|string|max:20',
            'payroll_type' => 'required|in:department,employee',
            'employee_id' => 'nullable|string|max:50',
            'department_id' => 'nullable|string|max:50',
            'policy_employment_type' => 'required|in:full-time,part-time,probation,contractual,appraisal,promotion',
        ]);
        
        $query = ProvidentFundPolicy::where('institute_id', $context['institute_id'])
            ->where('financial_year', $validated['financial_year'])
            ->where('payroll_type', $validated['payroll_type'])
            ->where('policy_employment_type', $validated['policy_employment_type']);
        
        if ($validated['payroll_type'] === 'employee' && !empty($validated['employee_id'])) {
            $query->where('employee_id', $validated['employee_id']);
        } elseif ($validated['payroll_type'] === 'department' && !empty($validated['department_id'])) {
            $query->where('department_id', $validated['department_id'])
                ->whereNull('employee_id');
        }
        
        $existingPolicy = $query->first();
        
        if ($existingPolicy) {
            $structuresCount = 0;
            
            if ($validated['payroll_type'] === 'employee' && !empty($validated['employee_id'])) {
                $structuresCount = EmployeeSalaryStructure::where('payroll_policy_id', $existingPolicy->payroll_policy_id)
                    ->where('employee_id', $validated['employee_id'])
                    ->where('status', 'active')
                    ->count();
            } elseif ($validated['payroll_type'] === 'department' && !empty($validated['department_id'])) {
                $employeeIds = EmployeeDetails::where('department_id', $validated['department_id'])
                    ->pluck('employee_id')
                    ->toArray();
                
                $structuresCount = EmployeeSalaryStructure::where('payroll_policy_id', $existingPolicy->payroll_policy_id)
                    ->whereIn('employee_id', $employeeIds)
                    ->where('status', 'active')
                    ->count();
            }
            
            return response()->json([
                'success' => true,
                'exists' => true,
                'data' => [
                    'payroll_policy_id' => $existingPolicy->payroll_policy_id,
                    'financial_year' => $existingPolicy->financial_year,
                    'created_at' => $existingPolicy->created_at,
                    'structures_count' => $structuresCount
                ]
            ]);
        }
        
        return response()->json([
            'success' => true,
            'exists' => false
        ]);
    }

    public function getDepartmentsWithPolicies(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        
        $financialYear = $request->query('financial_year');
        
        if (!$financialYear) {
            return response()->json([
                'success' => false,
                'message' => 'Financial year is required'
            ], 422);
        }
        
        $departmentPolicies = ProvidentFundPolicy::where('institute_id', $context['institute_id'])
            ->where('financial_year', $financialYear)
            ->where('payroll_type', 'department')
            ->whereNotNull('department_id')
            ->get();
        
        $employeePolicies = ProvidentFundPolicy::where('institute_id', $context['institute_id'])
            ->where('financial_year', $financialYear)
            ->where('payroll_type', 'employee')
            ->whereNotNull('employee_id')
            ->get();
        
        $departmentIdsFromDeptPolicies = $departmentPolicies->pluck('department_id')->toArray();
        
        $employeeIds = $employeePolicies->pluck('employee_id')->toArray();
        $departmentsFromEmployeePolicies = [];
        
        if (!empty($employeeIds)) {
            $employeesWithDepts = EmployeeDetails::whereIn('employee_id', $employeeIds)
                ->whereNotNull('department_id')
                ->get(['department_id']);
            $departmentsFromEmployeePolicies = $employeesWithDepts->pluck('department_id')->unique()->toArray();
        }
        
        $allDepartmentIds = array_unique(array_merge($departmentIdsFromDeptPolicies, $departmentsFromEmployeePolicies));
        
        if (empty($allDepartmentIds)) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }
        
        $departments = Departments::whereIn('department_id', $allDepartmentIds)
            ->orderBy('department')
            ->get(['department_id', 'department']);
        
        $departmentsWithPolicies = $departments->map(function($dept) use ($departmentPolicies, $employeePolicies) {
            $departmentPolicy = $departmentPolicies->firstWhere('department_id', $dept->department_id);
            
            $hasEmployeePolicies = false;
            if (!empty($employeePolicies)) {
                $employeeIdsWithPolicies = $employeePolicies->pluck('employee_id')->toArray();
                $employeesInDept = EmployeeDetails::where('department_id', $dept->department_id)
                    ->whereIn('employee_id', $employeeIdsWithPolicies)
                    ->exists();
                $hasEmployeePolicies = $employeesInDept;
            }
            
            return [
                'department_id' => $dept->department_id,
                'department' => $dept->department,
                'payroll_policy_id' => $departmentPolicy ? $departmentPolicy->payroll_policy_id : null,
                'policy_exists' => !is_null($departmentPolicy) || $hasEmployeePolicies,
                'policy_type' => !is_null($departmentPolicy) ? 'department' : ($hasEmployeePolicies ? 'employee' : null)
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $departmentsWithPolicies
        ]);
    }
    
    public function getPoliciesByDepartment(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        
        $financialYear = $request->query('financial_year');
        $departmentId = $request->query('department_id');
        
        if (!$financialYear || !$departmentId) {
            return response()->json([
                'success' => false,
                'message' => 'Financial year and department ID are required'
            ], 422);
        }
        
        $departmentPolicies = ProvidentFundPolicy::where('institute_id', $context['institute_id'])
            ->where('financial_year', $financialYear)
            ->where('payroll_type', 'department')
            ->where('department_id', $departmentId)
            ->get();
        
        $employeesInDept = EmployeeDetails::where('department_id', $departmentId)
            ->pluck('employee_id')
            ->toArray();
        
        $employeePolicies = ProvidentFundPolicy::where('institute_id', $context['institute_id'])
            ->where('financial_year', $financialYear)
            ->where('payroll_type', 'employee')
            ->whereIn('employee_id', $employeesInDept)
            ->get();
        
        $employees = EmployeeDetails::where('department_id', $departmentId)
            ->get(['employee_id', 'employee_code', 'name']);
        
        $employeeIds = $employeePolicies->pluck('employee_id')->toArray();
        $existingStructures = EmployeeSalaryStructure::whereIn('employee_id', $employeeIds)
            ->where('financial_year', $financialYear)
            ->where('institute_id', $context['institute_id'])
            ->get()
            ->keyBy('employee_id');
        
        $employeePoliciesWithDetails = [];
        foreach ($employeePolicies as $policy) {
            $employee = $employees->firstWhere('employee_id', $policy->employee_id);
            $existingStructure = $existingStructures->get($policy->employee_id);
            
            if ($employee) {
                $employeePoliciesWithDetails[] = [
                    'payroll_policy_id' => $policy->payroll_policy_id,
                    'employee_id' => $policy->employee_id,
                    'employee_name' => $employee->name,
                    'employee_code' => $employee->employee_code,
                    'financial_year' => $policy->financial_year,
                    'has_existing_structure' => !is_null($existingStructure),
                    'existing_structure_id' => $existingStructure ? $existingStructure->salary_structure_id : null,
                    'existing_structure_status' => $existingStructure ? ($existingStructure->status ?? 'inactive') : null
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'department_policy' => $departmentPolicies->first() ? [
                    'payroll_policy_id' => $departmentPolicies->first()->payroll_policy_id,
                    'financial_year' => $departmentPolicies->first()->financial_year,
                    'type' => 'department'
                ] : null,
                'department_policies' => $departmentPolicies->map(function($p) {
                    return [
                        'payroll_policy_id' => $p->payroll_policy_id,
                        'financial_year' => $p->financial_year,
                        'policy_employment_type' => $p->policy_employment_type,
                        'type' => 'department'
                    ];
                }),
                'employee_policies' => $employeePoliciesWithDetails,
                'has_any_policy' => $departmentPolicies->count() > 0 || count($employeePoliciesWithDetails) > 0
            ]
        ]);
    }

    public function getEmployeesByDepartmentForStructure(Request $request, $departmentId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        
        $financialYear = $request->query('financial_year');
        $policyType = $request->query('policy_type', 'department');
        
        $employees = EmployeeDetails::where('department_id', $departmentId)
            ->select('employee_id', 'employee_code', 'name')
            ->orderBy('name')
            ->get();
        
        if ($policyType === 'employee' && $financialYear) {
            $employeesWithPolicies = ProvidentFundPolicy::where('institute_id', $context['institute_id'])
                ->where('financial_year', $financialYear)
                ->where('payroll_type', 'employee')
                ->whereNotNull('employee_id')
                ->pluck('employee_id')
                ->toArray();
            
            $employees = $employees->filter(function($employee) use ($employeesWithPolicies) {
                return in_array($employee->employee_id, $employeesWithPolicies);
            });
        }
        
        return response()->json([
            'success' => true,
            'data' => $employees->values()
        ], 200);
    }

    public function getFullPolicyDetails($payrollPolicyId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        
        try {
            $policy = $this->getCommonQuery(ProvidentFundPolicy::class)
                ->where('payroll_policy_id', $payrollPolicyId)
                ->first();
            
            if (!$policy) {
                return response()->json([
                    'success' => false,
                    'message' => 'Policy not found'
                ], 404);
            }
            
            $allowances = PayrollPolicyAllowance::where('payroll_policy_id', $payrollPolicyId)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            $taxDeductions = PayrollPolicyTaxDeduction::where('payroll_policy_id', $payrollPolicyId)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            $otherDeductions = PayrollPolicyOtherDeduction::where('payroll_policy_id', $payrollPolicyId)
                ->where('institute_id', $context['institute_id'])
                ->first();

            $bonusOvertime = PayrollPolicyBonusOvertime::where('payroll_policy_id', $payrollPolicyId)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            // Convert to arrays and decode JSON fields
            $policyArray = $policy->toArray();
            $allowancesArray = $allowances ? $allowances->toArray() : null;
            $taxArray = $taxDeductions ? $taxDeductions->toArray() : null;
            $otherArray = $otherDeductions ? $otherDeductions->toArray() : null;
            $bonusArray = $bonusOvertime ? $bonusOvertime->toArray() : null;
            
            // Decode JSON fields
            $allowancesArray = $this->decodeJsonFields($allowancesArray);
            $taxArray = $this->decodeJsonFields($taxArray);
            $otherArray = $this->decodeJsonFields($otherArray);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'policy' => $policyArray,
                    'allowances' => $allowancesArray,
                    'tax_deductions' => $taxArray,
                    'other_deductions' => $otherArray,
                    'bonus_overtime' => $bonusArray
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getFullPolicyDetails: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading policy details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getEmployeesWithPolicies(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        
        $financialYear = $request->query('financial_year');
        
        if (!$financialYear) {
            return response()->json([
                'success' => false,
                'message' => 'Financial year is required'
            ], 422);
        }
        
        $employees = EmployeeDetails::where('institute_id', $context['institute_id'])
            ->select('employee_id', 'employee_code', 'name')
            ->orderBy('name')
            ->get();
        
        $existingPolicies = ProvidentFundPolicy::where('institute_id', $context['institute_id'])
            ->where('financial_year', $financialYear)
            ->where('payroll_type', 'employee')
            ->whereNotNull('employee_id')
            ->get()
            ->groupBy('employee_id');
        
        $employeesWithPolicies = $employees->map(function($employee) use ($existingPolicies) {

        $policies = $existingPolicies->get($employee->employee_id, collect());

            return [
                'employee_id' => $employee->employee_id,
                'employee_code' => $employee->employee_code,
                'name' => $employee->name,

                'has_policy' => $policies->count() > 0,

                'policies' => $policies->map(function($policy){
                    return [
                        'payroll_policy_id' => $policy->payroll_policy_id,
                        'employment_type' => $policy->policy_employment_type
                    ];
                })->values()
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $employeesWithPolicies
        ]);
    }

    public function getDepartmentsWithPoliciesForDropdown(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        
        $financialYear = $request->query('financial_year');
        
        if (!$financialYear) {
            return response()->json([
                'success' => false,
                'message' => 'Financial year is required'
            ], 422);
        }
        
        $departments = Departments::where('institute_id', $context['institute_id'])
            ->orderBy('department')
            ->get(['department_id', 'department']);
        
        $existingPolicies = ProvidentFundPolicy::where('institute_id', $context['institute_id'])
            ->where('financial_year', $financialYear)
            ->where('payroll_type', 'department')
            ->whereNotNull('department_id')
            ->get()
            ->groupBy('department_id');
        
        $departmentsWithPolicies = $departments->map(function($department) use ($existingPolicies) {

            $policies = $existingPolicies->get($department->department_id, collect());

            return [
                'department_id' => $department->department_id,
                'department' => $department->department,

                'has_policy' => $policies->count() > 0,

                'policies' => $policies->map(function($policy){
                    return [
                        'payroll_policy_id' => $policy->payroll_policy_id,
                        'employment_type' => $policy->policy_employment_type
                    ];
                })->values()
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $departmentsWithPolicies
        ]);
    }

    public function editPolicy($policyId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }
        
        $policy = $this->getCommonQuery(ProvidentFundPolicy::class)
            ->where('payroll_policy_id', $policyId)
            ->first();
        
        if (!$policy) {
            abort(404, 'Policy not found');
        }
        
        $allowances = PayrollPolicyAllowance::where('payroll_policy_id', $policyId)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        $taxDeductions = PayrollPolicyTaxDeduction::where('payroll_policy_id', $policyId)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        $otherDeductions = PayrollPolicyOtherDeduction::where('payroll_policy_id', $policyId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        $bonusOvertime = PayrollPolicyBonusOvertime::where('payroll_policy_id', $policyId)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        $targetName = null;
        $targetType = null;
        
        if ($policy->employee_id) {
            $employee = $this->getCommonQuery(EmployeeDetails::class)
                ->where('employee_id', $policy->employee_id)
                ->first();
            $targetName = $employee ? $employee->name : 'Unknown Employee';
            $targetType = 'employee';
        } elseif ($policy->department_id) {
            $department = $this->getCommonQuery(Departments::class)
                ->where('department_id', $policy->department_id)
                ->first();
            $targetName = $department ? $department->department : 'Unknown Department';
            $targetType = 'department';
        }
        
        return view('instituteAdmin.Payroll.PayrollConfigurationEdit', [
            'policy' => $policy,
            'allowances' => $allowances,
            'taxDeductions' => $taxDeductions,
            'otherDeductions' => $otherDeductions,
            'bonusOvertime' => $bonusOvertime,
            'targetName' => $targetName,
            'targetType' => $targetType,
            'policyId' => $policyId
        ]);
    }

    public function getPolicyData($policyId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        
        $policy = $this->getCommonQuery(ProvidentFundPolicy::class)
            ->where('payroll_policy_id', $policyId)
            ->first();
        
        if (!$policy) {
            return response()->json([
                'success' => false,
                'message' => 'Policy not found'
            ], 404);
        }
        
        // Get allowances
        $allowances = PayrollpolicyAllowance::where('payroll_policy_id', $policyId)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        // Get tax deductions
        $taxDeductions = PayrollPolicyTaxDeduction::where('payroll_policy_id', $policyId)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        // Get other deductions
        $otherDeductions = PayrollPolicyOtherDeduction::where('payroll_policy_id', $policyId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        $bonusOvertime = PayrollPolicyBonusOvertime::where('payroll_policy_id', $policyId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        
        return response()->json([
            'success' => true,
            'data' => [
                'policy' => $policy,
                'allowances' => $allowances,
                'tax_deductions' => $taxDeductions,
                'other_deductions' => $otherDeductions,
                'bonus_overtime' => $bonusOvertime
            ]
        ]);
    }


    public function updatePolicy(Request $request, $policyId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        
        $policy = $this->getCommonQuery(ProvidentFundPolicy::class)
            ->where('payroll_policy_id', $policyId)
            ->first();
        
        if (!$policy) {
            return response()->json([
                'success' => false,
                'message' => 'Policy not found'
            ], 404);
        }
        
        // Validate request
        $validated = $request->validate([
            'enable_pf' => 'required|in:0,1',
            'pf_employee_enabled' => 'required|in:0,1',
            'pf_wage_limit' => 'nullable|numeric|min:0',
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
            'enable_nps' => 'required|in:0,1',
            'nps_employee_enabled' => 'required|in:0,1',
            'nps_employee_type' => 'nullable|required_if:nps_employee_enabled,1|in:percentage,fixed',
            'nps_employee_value' => 'nullable|required_if:nps_employee_enabled,1|numeric|min:0',
            'nps_employer_enabled' => 'required|in:0,1',
            'nps_employer_type' => 'nullable|required_if:nps_employer_enabled,1|in:percentage,fixed',
            'nps_employer_value' => 'nullable|required_if:nps_employer_enabled,1|numeric|min:0',
        ]);
        
        // Capture OLD snapshot BEFORE update
        $oldFull = $this->getFullPolicySnapshot($policy->payroll_policy_id);
        
        // Update policy
        $policy->update($validated);
        
        // Capture NEW snapshot AFTER update
        $newFull = $this->getFullPolicySnapshot($policy->payroll_policy_id);
        
        // DIRECTLY LOG THE CHANGE - no extra function needed
        $this->logChange(
            $policy,
            'Edit',
            'Base Policy',
            ['base' => $oldFull['base']],
            ['base' => $newFull['base']]
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Policy updated successfully',
            'data' => ['payroll_policy_id' => $policy->payroll_policy_id]
        ]);
    }


    public function updateAllowances(Request $request, $policyId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        
        $policy = $this->getCommonQuery(ProvidentFundPolicy::class)
            ->where('payroll_policy_id', $policyId)
            ->first();
        
        if (!$policy) {
            return response()->json([
                'success' => false,
                'message' => 'Policy not found'
            ], 404);
        }
        
        $allowanceData = $request->validate([
            'hra_selected' => 'nullable|in:0,1',
            'hra_type' => 'nullable|in:percentage,fixed',
            'hra_value' => 'nullable|numeric|min:0',
            'conveyance_selected' => 'nullable|in:0,1',
            'conveyance_type' => 'nullable|in:percentage,fixed',
            'conveyance_value' => 'nullable|numeric|min:0',
            'medical_selected' => 'nullable|in:0,1',
            'medical_type' => 'nullable|in:percentage,fixed',
            'medical_value' => 'nullable|numeric|min:0',
            'special_selected' => 'nullable|in:0,1',
            'special_type' => 'nullable|in:percentage,fixed',
            'special_value' => 'nullable|numeric|min:0',
            'lta_selected' => 'nullable|in:0,1',
            'lta_type' => 'nullable|in:percentage,fixed',
            'lta_value' => 'nullable|numeric|min:0',
            'education_selected' => 'nullable|in:0,1',
            'education_type' => 'nullable|in:percentage,fixed',
            'education_value' => 'nullable|numeric|min:0',
            'custom_allowances' => 'nullable|array',
        ]);
        
        $allowanceData['payroll_policy_id'] = $policyId;
        $allowanceData['institute_id'] = $context['institute_id'];

        $allowance = PayrollPolicyAllowance::updateOrCreate(
            ['payroll_policy_id' => $policyId, 'institute_id' => $context['institute_id']],
            $allowanceData
        );

        return response()->json([
            'success' => true,
            'message' => 'Allowances updated successfully',
            'data' => $allowance
        ]);
    }

    public function updateTaxDeductions(Request $request, $policyId)
    {
        $decoded = json_decode($request->getContent(), true);
        if (is_array($decoded)) {
            $request->merge($decoded);
        }

        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        
        $policy = $this->getCommonQuery(ProvidentFundPolicy::class)
            ->where('payroll_policy_id', $policyId)
            ->first();
        
        if (!$policy) {
            return response()->json([
                'success' => false,
                'message' => 'Policy not found'
            ], 404);
        }

        $validated = $request->validate([
            'pt_selected' => 'nullable|in:0,1',
            'pt_type' => 'nullable|in:percentage,fixed,slabs',
            'pt_value' => 'nullable|numeric|min:0',
            'pt_slabs' => 'nullable|array',
            'lst_selected' => 'nullable|in:0,1',
            'lst_type' => 'nullable|in:percentage,fixed,slabs',
            'lst_value' => 'nullable|numeric|min:0',
            'lst_slabs' => 'nullable|array',
            'tds_selected' => 'nullable|in:0,1',
            'tds_type' => 'nullable|in:percentage,slabs',
            'tds_percentage' => 'nullable|numeric|min:0|max:100',
            'tds_slabs' => 'nullable|array',
        ]);

        $validated['payroll_policy_id'] = $policyId;
        $validated['institute_id'] = $context['institute_id'];

        $tax = PayrollPolicyTaxDeduction::updateOrCreate(
            ['payroll_policy_id' => $policyId, 'institute_id' => $context['institute_id']],
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Tax deductions updated successfully',
            'data' => $tax
        ]);
    }

    public function updateOtherDeductions(Request $request, $policyId)
    {
        $decoded = json_decode($request->getContent(), true);
        if (is_array($decoded)) {
            $request->merge($decoded);
        }

        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        
        $policy = $this->getCommonQuery(ProvidentFundPolicy::class)
            ->where('payroll_policy_id', $policyId)
            ->first();
        
        if (!$policy) {
            return response()->json([
                'success' => false,
                'message' => 'Policy not found'
            ], 404);
        }

        $validated = $request->validate([
            'insurance_selected' => 'nullable|in:0,1',
            'insurance_type' => 'nullable|in:percentage,fixed',
            'insurance_value' => 'nullable|numeric|min:0',
            'loan_selected' => 'nullable|in:0,1',
            'loan_type' => 'nullable|in:percentage,fixed',
            'loan_value' => 'nullable|numeric|min:0',
            'advance_selected' => 'nullable|in:0,1',
            'advance_type' => 'nullable|in:percentage,fixed',
            'advance_value' => 'nullable|numeric|min:0',
            'custom_deductions' => 'nullable|array',
        ]);

        $validated['payroll_policy_id'] = $policyId;
        $validated['institute_id'] = $context['institute_id'];

        $other = PayrollPolicyOtherDeduction::updateOrCreate(
            ['payroll_policy_id' => $policyId, 'institute_id' => $context['institute_id']],
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Other deductions updated successfully',
            'data' => $other
        ]);
    }

    
    public function logPolicySnapshot(Request $request, $policyId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        
        $policy = $this->getCommonQuery(ProvidentFundPolicy::class)
            ->where('payroll_policy_id', $policyId)
            ->first();
        
        if (!$policy) {
            return response()->json([
                'success' => false,
                'message' => 'Policy not found'
            ], 404);
        }
        
        $validated = $request->validate([
            'action' => 'required|in:Edit,Override,Creation',
            'change_type' => 'required|string',
            'previous_snapshot' => 'nullable|array',
        ]);
        
        // Get current full snapshot
        $currentSnapshot = $this->getFullPolicySnapshot($policyId);
        
        $this->logChange(
            $policy,
            $validated['action'],
            $validated['change_type'],
            $validated['previous_snapshot'],
            $currentSnapshot
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Policy snapshot logged successfully'
        ]);
    }


    private function getFullPolicySnapshot($policyId)
    {
        $context = $this->getInstituteBranchContext();

        $bonusOvertime = PayrollPolicyBonusOvertime::where('payroll_policy_id', $policyId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        return [
            'base' => \DB::table('provident_fund_policies')
                ->where('payroll_policy_id', $policyId)
                ->select('*', 'pf_wage_limit') 
                ->first(),

            'allowances' => \DB::table('payrollpolicy_allowances')
                ->where('payroll_policy_id', $policyId)
                ->where('institute_id', $context['institute_id'])
                ->first(),

            'tax' => \DB::table('payrollpolicy_tax_deductions')
                ->where('payroll_policy_id', $policyId)
                ->where('institute_id', $context['institute_id'])
                ->first(),

            'other' => \DB::table('payrollpolicy_other_deductions')
                ->where('payroll_policy_id', $policyId)
                ->where('institute_id', $context['institute_id'])
                ->first(),

            'bonus_overtime' => $bonusOvertime ? $bonusOvertime->toArray() : null

        ];
    }

    /**
     * Helper method to decode JSON fields in model arrays
     */
    private function decodeJsonFields(array $data)
    {
        $jsonFields = ['custom_allowances', 'custom_deductions', 'pt_slabs', 'lst_slabs', 'tds_slabs'];
        
        foreach ($jsonFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $decoded = json_decode($data[$field], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data[$field] = $decoded;
                }
            }
        }
        
        return $data;
    }

    private function logChange($policy, $action, $changeType, $previousSnapshot, $currentSnapshot)
    {
        $context = $this->getInstituteBranchContext();

        // Find existing log for this exact policy
        $existingLog = PayrollPolicyLog::where('payroll_policy_id', $policy->payroll_policy_id)->first();

        $logData = [
            'payroll_type' => $policy->payroll_type,
            'department_id' => $policy->department_id,
            'employee_id' => $policy->employee_id,
            'financial_year' => $policy->financial_year,
            'action' => $action,
            'change_type' => $changeType,
            'previous_data' => json_decode(json_encode($previousSnapshot), true),
            'current_data' => json_decode(json_encode($currentSnapshot), true),
            'changed_by' => auth()->user()->name ?? 'Admin'
        ];

        if ($existingLog) {
            // Update existing log
            $existingLog->update($logData);
        } else {
            // Create new log
            PayrollPolicyLog::create(array_merge($logData, [
                'payroll_policy_id' => $policy->payroll_policy_id,
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
            ]));
        }
    }

    public function viewLogs(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }
        
        $query = PayrollPolicyLog::where('institute_id', $context['institute_id']);
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('employee', function($sq) use ($search) {
                    $sq->where('name', 'LIKE', "%{$search}%");
                })->orWhereHas('department', function($sq) use ($search) {
                    $sq->where('department', 'LIKE', "%{$search}%");
                });
            });
        }
        
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        
        if ($request->filled('type')) {
            $query->where('change_type', $request->type);
        }
        
        $logs = $query->orderBy('created_at', 'desc')
            ->with(['employee', 'department'])
            ->paginate(15);
        
        return view('instituteAdmin.Payroll.PolicyLogs', [
            'logs' => $logs
        ]);
    }

    private function sanitizeForLog($data)
    {
        if (is_null($data)) {
            return null;
        }
        
        if (!is_array($data)) {
            return $data;
        }
        
        $sanitized = [];
        foreach ($data as $key => $value) {
            if (is_resource($value) || is_callable($value)) {
                continue;
            }
            
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizeForLog($value);
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }

    public function getPolicySnapshot($policyId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        
        $policy = $this->getCommonQuery(ProvidentFundPolicy::class)
            ->where('payroll_policy_id', $policyId)
            ->first();
        
        if (!$policy) {
            return response()->json([
                'success' => false,
                'message' => 'Policy not found'
            ], 404);
        }
        
        // Get allowances
        $allowances = PayrollpolicyAllowance::where('payroll_policy_id', $policyId)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        // Get tax deductions
        $taxDeductions = PayrollPolicyTaxDeduction::where('payroll_policy_id', $policyId)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        // Get other deductions
        $otherDeductions = PayrollPolicyOtherDeduction::where('payroll_policy_id', $policyId)
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        $bonusOvertime = PayrollPolicyBonusOvertime::where('payroll_policy_id', $policyId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        // Return in the same format expected by renderCurrentPolicyHTML
        return response()->json([
            'success' => true,
            'data' => [
                'policy' => $policy,
                'allowances' => $allowances,
                'tax_deductions' => $taxDeductions,
                'other_deductions' => $otherDeductions,
                'bonus_overtime' => $bonusOvertime
            ]
        ]);
    }

}