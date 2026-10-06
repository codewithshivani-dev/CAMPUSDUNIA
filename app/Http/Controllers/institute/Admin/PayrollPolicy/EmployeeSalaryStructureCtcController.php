<?php

namespace App\Http\Controllers\institute\Admin\PayrollPolicy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\EmployeeSalaryStructure;
use App\Models\SalaryStructureAllowances;
use App\Models\SalaryStructureOvertime;
use App\Models\SalaryStructureBonus;
use App\Models\SalaryStructureDeduction;
use App\Models\SalaryPreview;
use App\Models\ProvidentFundPolicy;
use App\Models\PayrollPolicyAllowance;
use App\Models\PayrollPolicyTaxDeduction;
use App\Models\PayrollPolicyOtherDeduction;
use App\Models\Departments; 
use App\Models\EmployeeDetails;
use App\Models\Designations;
use App\Models\SalaryStructureLog;
use Illuminate\Support\Facades\Log;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;

class EmployeeSalaryStructureCtcController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;

    const EMPLOYMENT_TYPES = ['probation', 'full-time', 'part-time', 'contractual', 'promotion', 'appraisal'];
    const ESI_LIMIT = 21000;

    // ============================================
    // GET FUNCTIONS (RENAMED WITH CTC PREFIX)
    // ============================================

    /**
     * Show the salary structure creation form
     */
    public function createCtcSalaryStructure()
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        return view('instituteAdmin.Payroll.SalaryStructureCTC');
    }


    /**
     * Get employees by department for salary structure
     */
    public function getCtcSalaryEmployeesByDepartment(Request $request, $departmentId)
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $employmentType = $request->employment_type;
        $policyId = $request->policy_id;

        $employees = EmployeeDetails::where('department_id', $departmentId)
            ->where('status', 'active')
            ->get();

        if ($policyId) {
            $policy = ProvidentFundPolicy::where('payroll_policy_id', $policyId)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if ($policy) {
                $employmentType = $policy->policy_employment_type;
            }
        }

        if (in_array($employmentType, ['promotion', 'appraisal'])) {
            return response()->json([
                'success' => true,
                'data' => $employees->values()
            ]);
        }

        $employees = $employees->filter(function ($emp) use ($employmentType) {
            return $this->normalizeEmploymentType($emp->employment_type)
                == $this->normalizeEmploymentType($employmentType);
        })->values();

        return response()->json([
            'success' => true,
            'data' => $employees
        ]);
    }

    /**
     * Get existing CTC structures by department
     */
    public function getCtcExistingStructuresByDepartment(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        
        $departmentId = $request->input('department_id');
        $financialYear = $request->input('financial_year');
        $employmentType = $request->input('employment_type');
        
        if (!$departmentId) {
            return response()->json([
                'success' => false,
                'message' => 'Department ID is required'
            ], 422);
        }
        
        $employeeIds = $this->getCommonQuery(EmployeeDetails::class)
            ->where('department_id', $departmentId)
            ->where('institute_id', $context['institute_id'])
            ->pluck('employee_id');
        
        $query = EmployeeSalaryStructure::where('institute_id', $context['institute_id'])
            ->whereIn('employee_id', $employeeIds)
            ->where('status', 'active');
        
        if ($financialYear) {
            $query->where('financial_year', $financialYear);
        }
        
        if ($employmentType) {
            $query->where('employment_type', $employmentType);
        }
        
        $structures = $query->get(['employee_id', 'salary_structure_id', 'status', 'updated_at', 'employment_type']);
        
        return response()->json([
            'success' => true,
            'data' => $structures
        ]);
    }


    /**
     * Store CTC salary structure - ALWAYS creates new and deactivates previous
     */
    public function storeCtcSalaryStructure(Request $request)
    {
        if ($request->isJson()) {
            $request->merge($request->json()->all());
        }

        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $validated = $request->validate([
            'payroll_policy_id' => 'required|string|max:50',
            'structure_type' => 'required|in:employee,department',
            'financial_year' => 'required|string|max:20',
            'status' => 'nullable|in:active,inactive',
            'department_category_id' => 'nullable|string|max:50',
            'department_id' => 'nullable|string|max:50',
            'designation_id' => 'nullable|string|max:50',
            'employee_id' => 'required|string|max:50',
            // REMOVED: 'override' => 'nullable|boolean',
            'fixed_ctc_annual' => 'required|numeric|min:0',
            'variable_ctc_annual' => 'nullable|numeric|min:0',
            'basic_salary_monthly' => 'required|numeric|min:0',
            'basic_salary_annual' => 'required|numeric|min:0',
            'basic_salary_percentage' => 'nullable|numeric|min:0|max:100',
            'employment_type' => 'nullable|string|max:50',
            'policy_employment_type' => 'nullable|string|max:50',
        ]);

        $employee = EmployeeDetails::where('employee_id', $validated['employee_id'])->first();
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.'
            ], 404);
        }

        $policy = ProvidentFundPolicy::where('payroll_policy_id', $validated['payroll_policy_id'])->first();
        if (!$policy) {
            return response()->json([
                'success' => false,
                'message' => 'Payroll Policy not found.'
            ], 404);
        }

        $validated = array_merge(
            $validated,
            $this->resolveCtcStructureMetadata($validated, $employee, $policy)
        );

        $validated['variable_ctc_annual'] = $validated['variable_ctc_annual'] ?? 0;
        $validated['total_ctc_annual'] = $validated['fixed_ctc_annual'] + $validated['variable_ctc_annual'];
        $validated['monthly_fixed'] = round($validated['fixed_ctc_annual'] / 12, 2);
        $validated['monthly_variable'] = round($validated['variable_ctc_annual'] / 12, 2);
        $validated['basic_salary_percentage'] = $validated['basic_salary_percentage'] ?? 0;

        $validated['institute_id'] = $context['institute_id'];
        $validated['branch_id'] = $context['is_branch_admin'] ? $context['branch_id'] : null;

        // ============================================
        // NEW LOGIC: Deactivate previous structures, create new one
        // ============================================
        
        // Find existing ACTIVE structure for this employee and financial year
        $existingStructure = EmployeeSalaryStructure::where([
            'employee_id' => $validated['employee_id'],
            'financial_year' => $validated['financial_year'],
            'institute_id' => $validated['institute_id'],
            'status' => 'active'
        ])->first();

        $previousSnapshot = null;
        $oldStructureId = null;

        if ($existingStructure) {
            // Load related data for snapshot
            $existingStructure->load(['allowances', 'deductions', 'preview']);
            $previousSnapshot = $this->ctcSalaryStructureSnapshot($existingStructure);
            $oldStructureId = $existingStructure->salary_structure_id;
            
            // DEACTIVATE the previous structure (instead of overriding)
            $existingStructure->status = 'inactive';
            $existingStructure->is_active = false;
            $existingStructure->save();
            
            // Log the deactivation
            SalaryStructureLog::create([
                'salary_structure_id' => $existingStructure->salary_structure_id,
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                'structure_type' => $existingStructure->structure_type,
                'department_id' => $existingStructure->department_id,
                'employee_id' => $existingStructure->employee_id,
                'financial_year' => $existingStructure->financial_year,
                'action' => 'Deactivated',
                'change_type' => 'New Structure Created',
                'previous_data' => json_encode(['status' => 'active']),
                'current_data' => json_encode(['status' => 'inactive']),
                'changed_by' => auth()->id() ?? null,
                'message' => "Deactivated due to new salary structure creation"
            ]);
        }

        // ============================================
        // CREATE NEW STRUCTURE (always a fresh row)
        // ============================================
        $validated['salary_structure_id'] = 'SAL' . strtoupper(substr(md5(uniqid() . microtime()), 0, 8));
        $validated['status'] = 'active';
        $validated['is_active'] = true;
        
        // Add reference to the old structure if it was deactivated
        if ($oldStructureId) {
            $validated['previous_structure_id'] = $oldStructureId;
        }
        
        $salaryStructure = EmployeeSalaryStructure::create($validated);

        // Log the new structure creation
        SalaryStructureLog::create([
            'salary_structure_id' => $salaryStructure->salary_structure_id,
            'institute_id' => $context['institute_id'],
            'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
            'structure_type' => $salaryStructure->structure_type,
            'department_id' => $salaryStructure->department_id,
            'employee_id' => $salaryStructure->employee_id,
            'financial_year' => $salaryStructure->financial_year,
            'action' => 'Created',
            'change_type' => 'New Salary Structure',
            'previous_data' => $previousSnapshot ? json_encode($previousSnapshot) : null,
            'current_data' => json_encode($salaryStructure->toArray()),
            'changed_by' => auth()->id() ?? null,
            'message' => $oldStructureId 
                ? "New structure created. Previous structure {$oldStructureId} deactivated." 
                : "New salary structure created."
        ]);

        $message = $oldStructureId 
            ? 'New salary structure created successfully. Previous structure was deactivated.'
            : 'Salary structure created successfully.';

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'salary_structure_id' => $salaryStructure->salary_structure_id,
                'status' => $salaryStructure->status,
                'designation_id' => $validated['designation_id'],
                'employment_type' => $validated['employment_type'],
                'policy_employment_type' => $validated['policy_employment_type'],
                'previous_structure_deactivated' => $oldStructureId ? true : false,
                'previous_structure_id' => $oldStructureId,
            ]
        ], 201);
    }


    protected function resolveCtcStructureMetadata(array $validated, $employee, $policy): array
    {
        $resolved = $validated;

        $hasDesignation = $this->hasMeaningfulValue($validated['designation_id'] ?? null);
        $resolved['designation_id'] = $hasDesignation
            ? $validated['designation_id']
            : ($employee->designation_id ?? null);

        $hasEmploymentType = $this->hasMeaningfulValue($validated['employment_type'] ?? null);
        $resolved['employment_type'] = $hasEmploymentType
            ? $this->normalizeEmploymentType($validated['employment_type'])
            : $this->normalizeEmploymentType($employee->employment_type ?? 'full-time');

        $hasPolicyEmploymentType = $this->hasMeaningfulValue($validated['policy_employment_type'] ?? null);
        $resolved['policy_employment_type'] = $hasPolicyEmploymentType
            ? $this->normalizeEmploymentType($validated['policy_employment_type'])
            : $this->normalizeEmploymentType($policy->policy_employment_type ?? 'full-time');

        return $resolved;
    }

    protected function hasMeaningfulValue($value): bool
    {
        if ($value === null) {
            return false;
        }

        if (is_string($value)) {
            return trim($value) !== '';
        }

        return !empty($value);
    }

    protected function ctcSalaryStructureSnapshot(EmployeeSalaryStructure $structure): array
    {
        return [
            'structure' => $structure->toArray(),
            'allowances' => $structure->allowances ? $structure->allowances->toArray() : null,
            'deductions' => $structure->deductions ? $structure->deductions->toArray() : null,
            'preview' => $structure->preview ? $structure->preview->toArray() : null,
            'bonuses' => $structure->bonuses ? $structure->bonuses->toArray() : [],
            'overtime' => $structure->overtime ? $structure->overtime->toArray() : null,
        ];
    }

    /**
     * View CTC salary management page
     */
    // public function viewCtcSalaryManagement(Request $request)
    // {
    //     $context = $this->getInstituteBranchContext();

    //     if (!$context['institute_id']) {
    //         return redirect()->back()->with('error', 'You are not associated with any institute.');
    //     }

    //     $selectedFinancialYear = $request->input('financial_year');
    //     $selectedStatus = $request->input('status');
    //     $selectedDepartmentId = $request->input('department_id');
    //     $selectedStructureFilter = $request->input('structure_filter');
    //     $searchQuery = $request->input('search');

    //     $employeeIds = [];
    //     $departmentIds = [];

    //     if ($searchQuery) {
    //         $matchedEmployees = EmployeeDetails::where('institute_id', $context['institute_id'])
    //             ->where('status', 'active')
    //             ->where(function ($q) use ($searchQuery) {
    //                 $q->where('name', 'LIKE', "%{$searchQuery}%")
    //                     ->orWhere('employee_code', 'LIKE', "%{$searchQuery}%");
    //             })
    //             ->get(['employee_id', 'department_id']);

    //         $employeeIds = $matchedEmployees->pluck('employee_id')->toArray();
    //         $employeeDepartmentIds = $matchedEmployees->pluck('department_id')->filter()->toArray();

    //         $searchDepartmentIds = Departments::where('institute_id', $context['institute_id'])
    //             ->where('department', 'LIKE', "%{$searchQuery}%")
    //             ->pluck('department_id')
    //             ->toArray();

    //         $departmentIds = array_unique(array_merge($employeeDepartmentIds, $searchDepartmentIds));
    //     }

    //     $salaryStructuresQuery = EmployeeSalaryStructure::where('institute_id', $context['institute_id'])
    //         ->with(['allowances', 'bonuses', 'overtime', 'deductions', 'preview'])
    //         ->orderBy('created_at', 'desc');

    //     $allSalaryStructuresQuery = EmployeeSalaryStructure::where('institute_id', $context['institute_id'])
    //         ->with(['allowances', 'bonuses', 'overtime', 'deductions', 'preview'])
    //         ->orderBy('created_at', 'desc');

    //     if (!empty($selectedStatus)) {
    //         $salaryStructuresQuery->where('status', $selectedStatus);
    //         $allSalaryStructuresQuery->where('status', $selectedStatus);
    //     } else {
    //         $salaryStructuresQuery->where('status', 'active');
    //     }

    //     if (!empty($employeeIds)) {
    //         $salaryStructuresQuery->whereIn('employee_id', $employeeIds);
    //     }

    //     if (!empty($selectedFinancialYear)) {
    //         $salaryStructuresQuery->where('financial_year', $selectedFinancialYear);
    //     }

    //     $salaryStructures = $salaryStructuresQuery->orderBy('created_at', 'desc')->get();
    //     $allSalaryStructures = $allSalaryStructuresQuery->orderBy('created_at', 'desc')->get();

    //     $employeesQuery = $this->getCommonQuery(EmployeeDetails::class)
    //         ->select('employee_id', 'employee_code', 'name', 'department_id', 'employment_type', 'designation_id', 'designation')
    //         ->where('institute_id', $context['institute_id'])
    //         ->where('status', 'active')
    //         ->orderBy('name');

    //     if ($searchQuery) {
    //         if (!empty($employeeIds)) {
    //             $employeesQuery->whereIn('employee_id', $employeeIds);
    //         } elseif (!empty($departmentIds)) {
    //             $employeesQuery->whereIn('department_id', $departmentIds);
    //         } else {
    //             $employeesQuery->whereRaw('1 = 0');
    //         }
    //     }

    //     if (!empty($selectedDepartmentId)) {
    //         $employeesQuery->where('department_id', $selectedDepartmentId);
    //     }

    //     $allEmployees = $employeesQuery->get();

    //     $designations = $allEmployees
    //         ->whereNotNull('designation_id')
    //         ->pluck('designation', 'designation_id')
    //         ->toArray();

    //     $departmentQuery = $this->getCommonQuery(Departments::class)
    //         ->orderBy('department');

    //     if ($searchQuery) {
    //         if (!empty($departmentIds)) {
    //             $departmentQuery->whereIn('department_id', $departmentIds);
    //         } elseif (!empty($employeeIds)) {
    //             $departmentQuery->whereIn('department_id', $employeeDepartmentIds ?? []);
    //         } else {
    //             $departmentQuery->whereRaw('1 = 0');
    //         }
    //     }

    //     $allDepartments = $departmentQuery->get();

    //     $designationIds = collect($allEmployees)
    //         ->pluck('designation_id')
    //         ->merge($salaryStructures->pluck('designation_id'))
    //         ->filter(fn ($value) => !empty($value))
    //         ->unique()
    //         ->values();

    //     $designationLookup = [];
    //     if ($designationIds->isNotEmpty()) {
    //         $designationLookup = Designations::whereIn('designation_id', $designationIds)
    //             ->get()
    //             ->keyBy('designation_id');
    //     }

    //     $resolveDesignationName = function ($employee, $structure) use ($designationLookup) {
    //         $designationName = $employee->designation ?? 'N/A';

    //         $designationId = null;
    //         if (!empty($structure->designation_id)) {
    //             $designationId = $structure->designation_id;
    //         } elseif (!empty($employee->designation_id)) {
    //             $designationId = $employee->designation_id;
    //         }

    //         if (!empty($designationId) && isset($designationLookup[$designationId])) {
    //             $designationRecord = $designationLookup[$designationId];
    //             $designationName = $designationRecord->designations ?? $designationRecord->designation ?? $designationName;
    //         }

    //         return $designationName;
    //     };

    //     $structureIds = $salaryStructures->pluck('salary_structure_id')->toArray();

    //     $allowances = SalaryStructureAllowances::whereIn('salary_structure_id', $structureIds)
    //         ->where('institute_id', $context['institute_id'])
    //         ->get()
    //         ->keyBy('salary_structure_id');

    //     $bonuses = SalaryStructureBonus::whereIn('salary_structure_id', $structureIds)
    //         ->where('institute_id', $context['institute_id'])
    //         ->get()
    //         ->groupBy('salary_structure_id');

    //     $overtime = SalaryStructureOvertime::whereIn('salary_structure_id', $structureIds)
    //         ->where('institute_id', $context['institute_id'])
    //         ->get()
    //         ->keyBy('salary_structure_id');

    //     $deductions = SalaryStructureDeduction::whereIn('salary_structure_id', $structureIds)
    //         ->where('institute_id', $context['institute_id'])
    //         ->get()
    //         ->keyBy('salary_structure_id');

    //     $previews = SalaryPreview::whereIn('salary_structure_id', $structureIds)
    //         ->where('institute_id', $context['institute_id'])
    //         ->get()
    //         ->keyBy('salary_structure_id');

    //     $structuresByEmployee = [];
    //     foreach ($salaryStructures as $structure) {
    //         $structuresByEmployee[$structure->employee_id] = $structure;
    //     }

    //     $allStructuresByEmployee = [];
    //     $structurePriority = function ($structure) {
    //         $isActive = ($structure->status === 'active' || $structure->status === null);
    //         $createdAt = $structure->created_at ? strtotime($structure->created_at) : 0;
    //         return [$isActive ? 1 : 0, $createdAt, (int) ($structure->salary_structure_id ?? 0)];
    //     };

    //     foreach ($allSalaryStructures as $structure) {
    //         $employeeId = $structure->employee_id;
    //         $existing = $allStructuresByEmployee[$employeeId] ?? null;

    //         if (!$existing) {
    //             $allStructuresByEmployee[$employeeId] = $structure;
    //             continue;
    //         }

    //         $existingPriority = $structurePriority($existing);
    //         $newPriority = $structurePriority($structure);

    //         if ($newPriority[0] > $existingPriority[0]) {
    //             $allStructuresByEmployee[$employeeId] = $structure;
    //         } elseif ($newPriority[0] === $existingPriority[0] && $newPriority[1] > $existingPriority[1]) {
    //             $allStructuresByEmployee[$employeeId] = $structure;
    //         } elseif ($newPriority[0] === $existingPriority[0] && $newPriority[1] === $existingPriority[1] && $newPriority[2] > $existingPriority[2]) {
    //             $allStructuresByEmployee[$employeeId] = $structure;
    //         }
    //     }

    //     $departmentSummaries = [];
    //     foreach ($allDepartments as $department) {
    //         $departmentEmployees = [];
    //         foreach ($allEmployees as $employee) {
    //             if ((string) $employee->department_id !== (string) $department->department_id) {
    //                 continue;
    //             }

    //             $structure = $allStructuresByEmployee[$employee->employee_id] ?? null;
    //             $hasStructure = (bool) $structure;

    //             $departmentEmployees[] = [
    //                 'employee' => $employee,
    //                 'structure' => $structure,
    //                 'has_structure' => $hasStructure,
    //                 'designation_name' => $resolveDesignationName($employee, $structure),
    //             ];
    //         }

    //         $filteredEntries = [];
    //         foreach ($departmentEmployees as $entry) {
    //             if (!empty($selectedStructureFilter)) {
    //                 if ($selectedStructureFilter === 'with' && empty($entry['structure'])) {
    //                     continue;
    //                 }
    //                 if ($selectedStructureFilter === 'without' && !empty($entry['structure'])) {
    //                     continue;
    //                 }
    //             }

    //             $filteredEntries[] = $entry;
    //         }

    //         $withStructures = collect($departmentEmployees)->filter(fn ($entry) => !empty($entry['structure']))->count();
    //         $withoutStructures = count($departmentEmployees) - $withStructures;

    //         $page = max(1, (int) $request->get('page', 1));
    //         $perPage = 15;
    //         $entries = collect($filteredEntries)->values();
    //         $slicedEntries = $entries->slice(($page - 1) * $perPage, $perPage)->values();

    //         $departmentEmployeesPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
    //             $slicedEntries,
    //             $entries->count(),
    //             $perPage,
    //             $page,
    //             [
    //                 'path' => $request->url(),
    //                 'query' => $request->query(),
    //             ]
    //         );
    //         $departmentEmployeesPaginator->appends($request->query());

    //         $departmentSummaries[] = [
    //             'department' => $department,
    //             'employees' => $departmentEmployeesPaginator,
    //             'total_employees' => count($departmentEmployees),
    //             'with_structures' => $withStructures,
    //             'without_structures' => $withoutStructures,
    //         ];
    //     }

    //     $structureRows = [];
    //     foreach ($allEmployees as $employee) {
    //         $structure = $structuresByEmployee[$employee->employee_id] ?? null;
    //         if (!$structure) {
    //             continue;
    //         }

    //         $structureRows[] = [
    //             'employee' => $employee,
    //             'structure' => $structure,
    //             'preview' => $previews[$structure->salary_structure_id] ?? null,
    //             'designation_name' => $resolveDesignationName($employee, $structure),
    //         ];
    //     }

    //     $structureRowsPaginator = null;
    //     $page = max(1, (int) $request->get('page', 1));
    //     $perPage = 15;
    //     if (!empty($structureRows)) {
    //         $structureRowsCollection = collect($structureRows)->values();
    //         $slicedStructureRows = $structureRowsCollection->slice(($page - 1) * $perPage, $perPage)->values();

    //         $structureRowsPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
    //             $slicedStructureRows,
    //             $structureRowsCollection->count(),
    //             $perPage,
    //             $page,
    //             [
    //                 'path' => $request->url(),
    //                 'query' => $request->query(),
    //             ]
    //         );
    //         $structureRowsPaginator->appends($request->query());
    //     }

    //     $selectedDepartmentSummary = null;
    //     $departmentEmployeesPaginator = null;
    //     if (!empty($selectedDepartmentId)) {
    //         $selectedDepartmentSummary = collect($departmentSummaries)->first(function ($summary) use ($selectedDepartmentId) {
    //             return (string) $summary['department']->department_id === (string) $selectedDepartmentId;
    //         });

    //         if ($selectedDepartmentSummary) {
    //             $page = max(1, (int) $request->get('page', 1));
    //             $perPage = 15;
    //             $entries = collect($selectedDepartmentSummary['employees'])->values();
    //             $slicedEntries = $entries->slice(($page - 1) * $perPage, $perPage)->values();

    //             $departmentEmployeesPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
    //                 $slicedEntries,
    //                 $entries->count(),
    //                 $perPage,
    //                 $page,
    //                 [
    //                     'path' => $request->url(),
    //                     'query' => $request->query(),
    //                 ]
    //             );
    //             $departmentEmployeesPaginator->appends($request->query());

    //             $selectedDepartmentSummary['employees'] = $departmentEmployeesPaginator;
    //         }
    //     }

    //     if ($request->ajax() || $request->wantsJson()) {
    //         return response()->json([
    //             'success' => true,
    //             'departments' => $departmentSummaries,
    //             'structure_rows' => $structureRows,
    //             'filters' => [
    //                 'financial_year' => $selectedFinancialYear,
    //                 'status' => $selectedStatus,
    //                 'department_id' => $selectedDepartmentId,
    //                 'structure_filter' => $selectedStructureFilter,
    //                 'search' => $searchQuery,
    //             ],
    //         ]);
    //     }

    //     $currentYear = date('Y');
    //     $financialYearsList = [];
    //     for ($i = -2; $i <= 2; $i++) {
    //         $start = $currentYear + $i;
    //         $end = $start + 1;
    //         $financialYearsList[] = $start . '-' . $end;
    //     }

    //     $currentFinancialYear = date('Y') . '-' . (date('Y') + 1);
    //     if (date('m') < 4) {
    //         $currentFinancialYear = (date('Y') - 1) . '-' . date('Y');
    //     }
    //     $displayFinancialYear = $selectedFinancialYear ?: $currentFinancialYear;

    //     return view('instituteAdmin.Payroll.SalaryManagement', [
    //         'departments' => $allDepartments,
    //         'employees' => $allEmployees,
    //         'designations' => $designations,
    //         'salaryStructures' => $salaryStructures,
    //         'structuresByEmployee' => $structuresByEmployee,
    //         'previews' => $previews,
    //         'allowances' => $allowances,
    //         'bonuses' => $bonuses,
    //         'overtime' => $overtime,
    //         'deductions' => $deductions,
    //         'selectedFinancialYear' => $selectedFinancialYear,
    //         'selectedStatus' => $selectedStatus,
    //         'selectedDepartmentId' => $selectedDepartmentId,
    //         'selectedStructureFilter' => $selectedStructureFilter,
    //         'searchQuery' => $searchQuery,
    //         'displayFinancialYear' => $displayFinancialYear,
    //         'currentFinancialYear' => $currentFinancialYear,
    //         'financialYearsList' => $financialYearsList,
    //         'allDepartmentsList' => $allDepartments,
    //         'departmentSummaries' => $departmentSummaries,
    //         'structureRows' => $structureRows,
    //         'structureRowsPaginator' => $structureRowsPaginator,
    //         'allStructuresByEmployee' => $allStructuresByEmployee,
    //         'selectedDepartmentSummary' => $selectedDepartmentSummary,
    //         'departmentEmployeesPaginator' => $departmentEmployeesPaginator,
    //     ]);
    // }
    
    public function viewCtcSalaryManagement(Request $request)
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        $selectedFinancialYear = $request->input('financial_year');
        $selectedStatus = $request->input('status');
        $selectedDepartmentId = $request->input('department_id');
        $selectedStructureFilter = $request->input('structure_filter');
        $searchQuery = $request->input('search');

        $employeeIds = [];
        $departmentIds = [];

        if ($searchQuery) {
            $matchedEmployees = EmployeeDetails::where('institute_id', $context['institute_id'])
                ->where('status', 'active')
                ->where(function ($q) use ($searchQuery) {
                    $q->where('name', 'LIKE', "%{$searchQuery}%")
                        ->orWhere('employee_code', 'LIKE', "%{$searchQuery}%");
                })
                ->get(['employee_id', 'department_id']);

            $employeeIds = $matchedEmployees->pluck('employee_id')->toArray();
            $employeeDepartmentIds = $matchedEmployees->pluck('department_id')->filter()->toArray();

            $searchDepartmentIds = Departments::where('institute_id', $context['institute_id'])
                ->where('department', 'LIKE', "%{$searchQuery}%")
                ->pluck('department_id')
                ->toArray();

            $departmentIds = array_unique(array_merge($employeeDepartmentIds, $searchDepartmentIds));
        }

        // ============================================
        // ✅ FIX: Get ALL salary structures ordered by latest first
        // ============================================
        $allSalaryStructuresQuery = EmployeeSalaryStructure::where('institute_id', $context['institute_id'])
            ->with(['allowances', 'bonuses', 'overtime', 'deductions', 'preview'])
            ->orderBy('created_at', 'desc');

        if (!empty($selectedStatus)) {
            $allSalaryStructuresQuery->where('status', $selectedStatus);
        }

        if (!empty($selectedFinancialYear)) {
            $allSalaryStructuresQuery->where('financial_year', $selectedFinancialYear);
        }

        $allSalaryStructures = $allSalaryStructuresQuery->get();

        // ============================================
        // ✅ FIX: Get ACTIVE structures for the main table, ordered by latest
        // ============================================
        $salaryStructuresQuery = EmployeeSalaryStructure::where('institute_id', $context['institute_id'])
            ->with(['allowances', 'bonuses', 'overtime', 'deductions', 'preview'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc');

        if (!empty($employeeIds)) {
            $salaryStructuresQuery->whereIn('employee_id', $employeeIds);
        }

        if (!empty($selectedFinancialYear)) {
            $salaryStructuresQuery->where('financial_year', $selectedFinancialYear);
        }

        $salaryStructures = $salaryStructuresQuery->get();

        // ============================================
        // EMPLOYEES QUERY
        // ============================================
        $employeesQuery = $this->getCommonQuery(EmployeeDetails::class)
            ->select('employee_id', 'employee_code', 'name', 'department_id', 'employment_type', 'designation_id', 'designation')
            ->where('institute_id', $context['institute_id'])
            ->where('status', 'active')
            ->orderBy('name');

        if ($searchQuery) {
            if (!empty($employeeIds)) {
                $employeesQuery->whereIn('employee_id', $employeeIds);
            } elseif (!empty($departmentIds)) {
                $employeesQuery->whereIn('department_id', $departmentIds);
            } else {
                $employeesQuery->whereRaw('1 = 0');
            }
        }

        if (!empty($selectedDepartmentId)) {
            $employeesQuery->where('department_id', $selectedDepartmentId);
        }

        $allEmployees = $employeesQuery->get();

        $designations = $allEmployees
            ->whereNotNull('designation_id')
            ->pluck('designation', 'designation_id')
            ->toArray();

        // ============================================
        // DEPARTMENTS QUERY
        // ============================================
        $departmentQuery = $this->getCommonQuery(Departments::class)
            ->orderBy('department');

        if ($searchQuery) {
            if (!empty($departmentIds)) {
                $departmentQuery->whereIn('department_id', $departmentIds);
            } elseif (!empty($employeeIds)) {
                $departmentQuery->whereIn('department_id', $employeeDepartmentIds ?? []);
            } else {
                $departmentQuery->whereRaw('1 = 0');
            }
        }

        $allDepartments = $departmentQuery->get();

        // ============================================
        // DESIGNATION LOOKUP
        // ============================================
        $designationIds = collect($allEmployees)
            ->pluck('designation_id')
            ->merge($salaryStructures->pluck('designation_id'))
            ->filter(fn ($value) => !empty($value))
            ->unique()
            ->values();

        $designationLookup = [];
        if ($designationIds->isNotEmpty()) {
            $designationLookup = Designations::whereIn('designation_id', $designationIds)
                ->get()
                ->keyBy('designation_id');
        }

        $resolveDesignationName = function ($employee, $structure) use ($designationLookup) {
            $designationName = $employee->designation ?? 'N/A';

            $designationId = null;
            if (!empty($structure->designation_id)) {
                $designationId = $structure->designation_id;
            } elseif (!empty($employee->designation_id)) {
                $designationId = $employee->designation_id;
            }

            if (!empty($designationId) && isset($designationLookup[$designationId])) {
                $designationRecord = $designationLookup[$designationId];
                $designationName = $designationRecord->designations ?? $designationRecord->designation ?? $designationName;
            }

            return $designationName;
        };

        // ============================================
        // GET RELATED DATA
        // ============================================
        $structureIds = $salaryStructures->pluck('salary_structure_id')->toArray();

        $allowances = SalaryStructureAllowances::whereIn('salary_structure_id', $structureIds)
            ->where('institute_id', $context['institute_id'])
            ->get()
            ->keyBy('salary_structure_id');

        $bonuses = SalaryStructureBonus::whereIn('salary_structure_id', $structureIds)
            ->where('institute_id', $context['institute_id'])
            ->get()
            ->groupBy('salary_structure_id');

        $overtime = SalaryStructureOvertime::whereIn('salary_structure_id', $structureIds)
            ->where('institute_id', $context['institute_id'])
            ->get()
            ->keyBy('salary_structure_id');

        $deductions = SalaryStructureDeduction::whereIn('salary_structure_id', $structureIds)
            ->where('institute_id', $context['institute_id'])
            ->get()
            ->keyBy('salary_structure_id');

        $previews = SalaryPreview::whereIn('salary_structure_id', $structureIds)
            ->where('institute_id', $context['institute_id'])
            ->get()
            ->keyBy('salary_structure_id');

        // ============================================
        // ✅ FIX: Build structures by employee (keep latest only)
        // ============================================
        $structuresByEmployee = [];
        foreach ($salaryStructures as $structure) {
            $employeeId = $structure->employee_id;
            $existing = $structuresByEmployee[$employeeId] ?? null;
            
            // Keep the latest structure for each employee
            if (!$existing || strtotime($structure->created_at) > strtotime($existing->created_at)) {
                $structuresByEmployee[$employeeId] = $structure;
            }
        }

        // ============================================
        // ✅ FIX: Build all structures by employee (keep latest only, regardless of status)
        // ============================================
        $allStructuresByEmployee = [];
        foreach ($allSalaryStructures as $structure) {
            $employeeId = $structure->employee_id;
            $existing = $allStructuresByEmployee[$employeeId] ?? null;
            
            // Keep the latest structure for each employee
            if (!$existing || strtotime($structure->created_at) > strtotime($existing->created_at)) {
                $allStructuresByEmployee[$employeeId] = $structure;
            }
        }

        // ============================================
        // BUILD DEPARTMENT SUMMARIES
        // ============================================
        $departmentSummaries = [];
        foreach ($allDepartments as $department) {
            $departmentEmployees = [];
            foreach ($allEmployees as $employee) {
                if ((string) $employee->department_id !== (string) $department->department_id) {
                    continue;
                }

                $structure = $allStructuresByEmployee[$employee->employee_id] ?? null;
                $hasStructure = (bool) $structure;

                $departmentEmployees[] = [
                    'employee' => $employee,
                    'structure' => $structure,
                    'has_structure' => $hasStructure,
                    'designation_name' => $resolveDesignationName($employee, $structure),
                    'created_at' => $structure ? $structure->created_at : null,
                ];
            }

            // ✅ FIX: Sort department employees by structure creation date (newest first)
            usort($departmentEmployees, function($a, $b) {
                // If both have structures, compare creation dates
                if ($a['structure'] && $b['structure']) {
                    $dateA = strtotime($a['structure']->created_at);
                    $dateB = strtotime($b['structure']->created_at);
                    return $dateB - $dateA; // Descending - newest first
                }
                // If only one has a structure, put the one with structure first
                if ($a['structure']) return -1;
                if ($b['structure']) return 1;
                // If neither has structure, sort by name
                return strcmp($a['employee']->name, $b['employee']->name);
            });

            $filteredEntries = [];
            foreach ($departmentEmployees as $entry) {
                if (!empty($selectedStructureFilter)) {
                    if ($selectedStructureFilter === 'with' && empty($entry['structure'])) {
                        continue;
                    }
                    if ($selectedStructureFilter === 'without' && !empty($entry['structure'])) {
                        continue;
                    }
                }
                $filteredEntries[] = $entry;
            }

            $withStructures = collect($departmentEmployees)->filter(fn ($entry) => !empty($entry['structure']))->count();
            $withoutStructures = count($departmentEmployees) - $withStructures;

            $page = max(1, (int) $request->get('page', 1));
            $perPage = 15;
            $entries = collect($filteredEntries)->values();
            $slicedEntries = $entries->slice(($page - 1) * $perPage, $perPage)->values();

            $departmentEmployeesPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $slicedEntries,
                $entries->count(),
                $perPage,
                $page,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]
            );
            $departmentEmployeesPaginator->appends($request->query());

            $departmentSummaries[] = [
                'department' => $department,
                'employees' => $departmentEmployeesPaginator,
                'total_employees' => count($departmentEmployees),
                'with_structures' => $withStructures,
                'without_structures' => $withoutStructures,
            ];
        }

        // ============================================
        // ✅ FIX: Build structure rows sorted by latest first
        // ============================================
        $structureRows = [];
        foreach ($allEmployees as $employee) {
            $structure = $structuresByEmployee[$employee->employee_id] ?? null;
            if (!$structure) {
                continue;
            }

            $structureRows[] = [
                'employee' => $employee,
                'structure' => $structure,
                'preview' => $previews[$structure->salary_structure_id] ?? null,
                'designation_name' => $resolveDesignationName($employee, $structure),
                'created_at' => $structure->created_at,
            ];
        }

        // ✅ FIX: Sort structure rows by created_at (newest first)
        usort($structureRows, function($a, $b) {
            $dateA = strtotime($a['created_at']);
            $dateB = strtotime($b['created_at']);
            return $dateB - $dateA;
        });

        $structureRowsPaginator = null;
        $page = max(1, (int) $request->get('page', 1));
        $perPage = 15;
        if (!empty($structureRows)) {
            $structureRowsCollection = collect($structureRows)->values();
            $slicedStructureRows = $structureRowsCollection->slice(($page - 1) * $perPage, $perPage)->values();

            $structureRowsPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $slicedStructureRows,
                $structureRowsCollection->count(),
                $perPage,
                $page,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]
            );
            $structureRowsPaginator->appends($request->query());
        }

        // ============================================
        // SELECTED DEPARTMENT SUMMARY
        // ============================================
        $selectedDepartmentSummary = null;
        $departmentEmployeesPaginator = null;
        if (!empty($selectedDepartmentId)) {
            $selectedDepartmentSummary = collect($departmentSummaries)->first(function ($summary) use ($selectedDepartmentId) {
                return (string) $summary['department']->department_id === (string) $selectedDepartmentId;
            });

            if ($selectedDepartmentSummary) {
                $page = max(1, (int) $request->get('page', 1));
                $perPage = 15;
                $entries = collect($selectedDepartmentSummary['employees'])->values();
                $slicedEntries = $entries->slice(($page - 1) * $perPage, $perPage)->values();

                $departmentEmployeesPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                    $slicedEntries,
                    $entries->count(),
                    $perPage,
                    $page,
                    [
                        'path' => $request->url(),
                        'query' => $request->query(),
                    ]
                );
                $departmentEmployeesPaginator->appends($request->query());

                $selectedDepartmentSummary['employees'] = $departmentEmployeesPaginator;
            }
        }

        // ============================================
        // AJAX RESPONSE
        // ============================================
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'departments' => $departmentSummaries,
                'structure_rows' => $structureRows,
                'filters' => [
                    'financial_year' => $selectedFinancialYear,
                    'status' => $selectedStatus,
                    'department_id' => $selectedDepartmentId,
                    'structure_filter' => $selectedStructureFilter,
                    'search' => $searchQuery,
                ],
            ]);
        }

        // ============================================
        // FINANCIAL YEARS LIST
        // ============================================
        $currentYear = date('Y');
        $financialYearsList = [];
        for ($i = -2; $i <= 2; $i++) {
            $start = $currentYear + $i;
            $end = $start + 1;
            $financialYearsList[] = $start . '-' . $end;
        }

        $currentFinancialYear = date('Y') . '-' . (date('Y') + 1);
        if (date('m') < 4) {
            $currentFinancialYear = (date('Y') - 1) . '-' . date('Y');
        }
        $displayFinancialYear = $selectedFinancialYear ?: $currentFinancialYear;

        // ============================================
        // VIEW
        // ============================================
        return view('instituteAdmin.Payroll.SalaryManagement', [
            'departments' => $allDepartments,
            'employees' => $allEmployees,
            'designations' => $designations,
            'salaryStructures' => $salaryStructures,
            'structuresByEmployee' => $structuresByEmployee,
            'previews' => $previews,
            'allowances' => $allowances,
            'bonuses' => $bonuses,
            'overtime' => $overtime,
            'deductions' => $deductions,
            'selectedFinancialYear' => $selectedFinancialYear,
            'selectedStatus' => $selectedStatus,
            'selectedDepartmentId' => $selectedDepartmentId,
            'selectedStructureFilter' => $selectedStructureFilter,
            'searchQuery' => $searchQuery,
            'displayFinancialYear' => $displayFinancialYear,
            'currentFinancialYear' => $currentFinancialYear,
            'financialYearsList' => $financialYearsList,
            'allDepartmentsList' => $allDepartments,
            'departmentSummaries' => $departmentSummaries,
            'structureRows' => $structureRows,
            'structureRowsPaginator' => $structureRowsPaginator,
            'allStructuresByEmployee' => $allStructuresByEmployee,
            'selectedDepartmentSummary' => $selectedDepartmentSummary,
            'departmentEmployeesPaginator' => $departmentEmployeesPaginator,
        ]);
    }


    /**
     * View CTC salary details
     */
    public function viewCtcSalaryDetails($salaryStructureId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        $structure = EmployeeSalaryStructure::where('salary_structure_id', $salaryStructureId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$structure) {
            abort(404, 'Salary structure not found');
        }

        $allowances = SalaryStructureAllowances::where('salary_structure_id', $salaryStructureId)
            ->where('institute_id', $context['institute_id'])
            ->first();
            
        $bonuses = SalaryStructureBonus::where('salary_structure_id', $salaryStructureId)
            ->where('institute_id', $context['institute_id'])
            ->get();
            
        $overtime = SalaryStructureOvertime::where('salary_structure_id', $salaryStructureId)
            ->where('institute_id', $context['institute_id'])
            ->first();
            
        $deductions = SalaryStructureDeduction::where('salary_structure_id', $salaryStructureId)
            ->where('institute_id', $context['institute_id'])
            ->first();
            
        $preview = SalaryPreview::where('salary_structure_id', $salaryStructureId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        $employee = $this->getCommonQuery(EmployeeDetails::class)
            ->where('employee_id', $structure->employee_id)
            ->first();

        $department = null;
        if ($employee && $employee->department_id) {
            $department = $this->getCommonQuery(Departments::class)
                ->where('department_id', $employee->department_id)
                ->first();
        }

        return view('instituteAdmin.Payroll.SalaryDetails', [
            'structure' => $structure,
            'allowances' => $allowances,
            'bonuses' => $bonuses,
            'overtime' => $overtime,
            'deductions' => $deductions,
            'preview' => $preview,
            'employee' => $employee,
            'department' => $department
        ]);
    }

    /**
     * Edit CTC salary structure page
     */
    public function editCtcSalaryStructure($salaryStructureId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        $structure = EmployeeSalaryStructure::where('salary_structure_id', $salaryStructureId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$structure) {
            abort(404, 'Salary structure not found');
        }

        $allowances = SalaryStructureAllowances::where('salary_structure_id', $salaryStructureId)
            ->where('institute_id', $context['institute_id'])
            ->first();
            
        $bonuses = SalaryStructureBonus::where('salary_structure_id', $salaryStructureId)
            ->where('institute_id', $context['institute_id'])
            ->get();
            
        $overtime = SalaryStructureOvertime::where('salary_structure_id', $salaryStructureId)
            ->where('institute_id', $context['institute_id'])
            ->first();
            
        $deductions = SalaryStructureDeduction::where('salary_structure_id', $salaryStructureId)
            ->where('institute_id', $context['institute_id'])
            ->first();
            
        $preview = SalaryPreview::where('salary_structure_id', $salaryStructureId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        $employee = $this->getCommonQuery(EmployeeDetails::class)
            ->where('employee_id', $structure->employee_id)
            ->first();

        $department = null;
        if ($employee && $employee->department_id) {
            $department = $this->getCommonQuery(Departments::class)
                ->where('department_id', $employee->department_id)
                ->first();
        }

        $policy = null;
        if ($structure->payroll_policy_id) {
            $policy = ProvidentFundPolicy::where('payroll_policy_id', $structure->payroll_policy_id)->first();
        }

        return view('instituteAdmin.Payroll.SalaryStructureEdit', [
            'structure' => $structure,
            'allowances' => $allowances,
            'bonuses' => $bonuses,
            'overtime' => $overtime,
            'deductions' => $deductions,
            'preview' => $preview,
            'employee' => $employee,
            'department' => $department,
            'policy' => $policy
        ]);
    }

    /**
     * Normalize employment type for consistent comparison
     */
    protected function normalizeEmploymentType($type)
    {
        if (empty($type)) {
            return 'full-time';
        }

        $type = strtolower(trim($type));

        $map = [
            'full-time' => 'full-time',
            'full time' => 'full-time',
            'fulltime' => 'full-time',

            'part-time' => 'part-time',
            'part time' => 'part-time',
            'parttime' => 'part-time',

            'contract-based' => 'contractual',
            'contractual' => 'contractual',
            'contract' => 'contractual',

            'probation-period' => 'probation',
            'probation period' => 'probation',
            'probation' => 'probation',

            'promotion' => 'promotion',
            'appraisal' => 'appraisal',
        ];

        return $map[$type] ?? $type;
    }

    /**
     * Sync employment type with CTC structures - inactivates structures if employment type doesn't match
     */
    public function syncCtcEmploymentTypeWithStructures(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $employeeId = $request->input('employee_id');
        
        if (!$employeeId) {
            return response()->json([
                'success' => false,
                'message' => 'Employee ID is required'
            ], 422);
        }

        $employee = $this->getCommonQuery(EmployeeDetails::class)
            ->where('employee_id', $employeeId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        $structures = EmployeeSalaryStructure::where('employee_id', $employeeId)
            ->where('status', 'active')
            ->get();

        $updatedCount = 0;
        foreach ($structures as $structure) {
            $structureNorm = $this->normalizeEmploymentType($structure->employment_type);
            $employeeNorm = $this->normalizeEmploymentType($employee->employment_type);
            
            if ($structureNorm !== $employeeNorm) {
                $structure->status = 'inactive';
                $structure->save();
                $updatedCount++;

                SalaryStructureLog::create([
                    'salary_structure_id' => $structure->salary_structure_id,
                    'institute_id' => $context['institute_id'],
                    'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                    'structure_type' => $structure->structure_type,
                    'department_id' => $structure->department_id,
                    'employee_id' => $structure->employee_id,
                    'financial_year' => $structure->financial_year,
                    'action' => 'Inactivated',
                    'change_type' => 'Employment Type Mismatch',
                    'previous_data' => json_encode(['employment_type' => $structure->employment_type]),
                    'current_data' => json_encode(['employment_type' => $employee->employment_type]),
                    'changed_by' => auth()->id() ?? null,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$updatedCount} CTC salary structure(s) inactivated due to employment type change",
            'data' => [
                'employee_id' => $employeeId,
                'current_employment_type' => $employee->employment_type,
                'structures_inactivated' => $updatedCount
            ]
        ]);
    }

    /**
     * Update employee employment type - inactivates CTC structures on type change
     */
    public function updateCtcEmployeeEmploymentType(Request $request, $employeeId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        
        $validated = $request->validate([
            'employment_type' => 'required|in:probation,full-time,part-time,contractual,promotion,appraisal'
        ]);
        
        $employee = $this->getCommonQuery(EmployeeDetails::class)
            ->where('employee_id', $employeeId)
            ->where('institute_id', $context['institute_id'])
            ->first();
            
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }
        
        $oldEmploymentType = $employee->employment_type;
        $newEmploymentType = $validated['employment_type'];
        $updatedCount = 0;
        
        if ($oldEmploymentType !== $newEmploymentType) {
            $updatedCount = EmployeeSalaryStructure::where('employee_id', $employeeId)
                ->where('institute_id', $context['institute_id'])
                ->where('status', 'active')
                ->update(['status' => 'inactive']);
            
            SalaryStructureLog::create([
                'institute_id' => $context['institute_id'],
                'employee_id' => $employeeId,
                'action' => 'Employment Type Changed',
                'change_type' => 'Employment Type',
                'previous_data' => json_encode(['employment_type' => $oldEmploymentType]),
                'current_data' => json_encode(['employment_type' => $newEmploymentType]),
                'changed_by' => auth()->id() ?? null,
                'message' => "Employment type changed from {$oldEmploymentType} to {$newEmploymentType}. {$updatedCount} CTC salary structure(s) inactivated."
            ]);
        }
        
        $employee->employment_type = $newEmploymentType;
        $employee->save();
        
        return response()->json([
            'success' => true,
            'message' => "Employment type updated from {$oldEmploymentType} to {$newEmploymentType}. {$updatedCount} CTC salary structure(s) inactivated.",
            'data' => [
                'employee_id' => $employeeId,
                'old_employment_type' => $oldEmploymentType,
                'new_employment_type' => $newEmploymentType,
                'structures_inactivated' => $updatedCount
            ]
        ]);
    }

    public function ctcAiSuggestion(Request $request)
    {
        try {
            $validated = $request->validate([
                'ctc' => 'required|numeric|min:0',
                'variable_ctc' => 'nullable|numeric|min:0',
                'basic_percentage' => 'nullable|numeric|min:0|max:100',
                'financial_year' => 'nullable|string',
                'employment_type' => 'nullable|string',
                'policy_id' => 'nullable|string',
                'policy_structure' => 'nullable|array',
            ]);
            
            $validated['variable_ctc'] = $validated['variable_ctc'] ?? 0;
            $validated['basic_percentage'] = $validated['basic_percentage'] ?? 40;
            $validated['employment_type'] = $validated['employment_type'] ?? 'full-time';
            $validated['financial_year'] = $validated['financial_year'] ?? date('Y') . '-' . (date('Y') + 1);
            
            $optimizer = new \App\Services\SalaryOptimizerService();
            $result = $optimizer->optimizeWithPolicy($validated);

            $enabledKeys = null;
            if (!empty($validated['policy_structure']['allowances']) && is_array($validated['policy_structure']['allowances'])) {
                $enabledKeys = [];
                foreach ($validated['policy_structure']['allowances'] as $k => $v) {
                    if (is_array($v)) {
                        if (isset($v['enabled']) && ($v['enabled'] == true || $v['enabled'] == 1)) {
                            $enabledKeys[] = $k;
                        }
                    } else {
                        if ($v) $enabledKeys[] = $k;
                    }
                }
            }

            if ($enabledKeys === null && !empty($validated['policy_id'])) {
                try {
                    $allowancesModel = \App\Models\PayrollPolicyAllowance::where('payroll_policy_id', $validated['policy_id'])->first();
                    if ($allowancesModel) {
                        $enabledKeys = [];
                        $allowanceTypes = ['hra', 'conveyance', 'medical', 'special', 'lta', 'education'];
                        foreach ($allowanceTypes as $type) {
                            $selKey = $type . '_selected';
                            if (isset($allowancesModel->{$selKey}) && ($allowancesModel->{$selKey} == 1 || $allowancesModel->{$selKey} === '1')) {
                                $enabledKeys[] = $type;
                            }
                        }

                        if (!empty($allowancesModel->custom_allowances) && is_array($allowancesModel->custom_allowances)) {
                            foreach ($allowancesModel->custom_allowances as $idx => $custom) {
                                $isCustomEnabled = true;
                                if (is_array($custom)) {
                                    if (isset($custom['selected'])) $isCustomEnabled = ($custom['selected'] == 1 || $custom['selected'] === '1');
                                    elseif (isset($custom['enabled'])) $isCustomEnabled = ($custom['enabled'] == 1 || $custom['enabled'] === '1');
                                }
                                if ($isCustomEnabled) $enabledKeys[] = 'custom_' . $idx;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    $enabledKeys = null;
                }
            }

            $applyData = [
                'basic_percentage' => $result['basic_percentage'],
                'allowances' => [],
            ];

            foreach ($result['allowances'] as $key => $allowance) {
                $isEnabled = $enabledKeys === null ? true : in_array($key, $enabledKeys, true);
                if (!$isEnabled) continue;

                $applyData['allowances'][] = [
                    'id' => $key,
                    'name' => $allowance['name'] ?? ($allowance['display_name'] ?? $key),
                    'type' => $allowance['type'] ?? 'fixed',
                    'value' => $allowance['value'] ?? 0,
                    'monthly' => $allowance['monthly'] ?? 0,
                    'annual' => $allowance['annual'] ?? 0,
                ];
            }
            
            return response()->json([
                'success' => true,
                'message' => $this->generateCtcAIMessage($result),
                'data' => [
                    'applyData' => $applyData,
                    'health_score' => $result['health_score'],
                    'changes' => $result['changes'],
                    'structure' => [
                        'basic_percentage' => $result['basic_percentage'],
                        'basic_monthly' => $result['basic_monthly'],
                        'basic_annual' => $result['basic_annual'],
                        'gross_monthly' => $result['gross_monthly'],
                        'gross_annual' => $result['gross_annual'],
                        'net_monthly' => $result['net_monthly'],
                        'net_annual' => $result['net_annual'],
                        'allowances' => (function() use ($result, $validated, $enabledKeys) {
                            $out = [];
                            foreach ($result['allowances'] as $k => $a) {
                                $isEnabled = $enabledKeys === null ? true : in_array($k, $enabledKeys, true);
                                $a['enabled'] = $isEnabled;
                                $out[$k] = $a;
                            }
                            return $out;
                        })(),
                        'allowances_total_monthly' => $result['allowances_total_monthly'],
                        'allowances_total_annual' => $result['allowances_total_annual'],
                        'deductions' => $result['deductions'],
                        'deductions_total_monthly' => $result['deductions_total_monthly'],
                        'deductions_total_annual' => $result['deductions_total_annual'],
                        'employer_contributions' => $result['employer_contributions'],
                        'employer_total_monthly' => $result['employer_total_monthly'],
                        'employer_total_annual' => $result['employer_total_annual'],
                        'total_cost' => $result['total_cost'],
                        'difference' => $result['difference'],
                        'fixed_ctc' => $result['fixed_ctc'],
                        'variable_ctc' => $result['variable_ctc'],
                        'total_ctc' => $result['total_ctc'],
                        'employee_statutory' => $result['employee_statutory'],
                        'tax_deductions' => $result['tax_deductions'],
                        'other_deductions' => $result['other_deductions'],
                    ],
                    'validation' => $result['validation'],
                    'explanation' => $this->generateCtcExplanation($result),
                    'summary' => $result['summary'],
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('CTC AI Suggestion Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'CTC Optimization failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate CTC AI message
     */
    protected function generateCtcAIMessage(array $result): string
    {
        $validation = $result['validation'];
        $isMatch = $validation['is_match'] ?? $validation['match'] ?? false;
        $difference = $validation['difference'] ?? 0;
        
        if ($isMatch) {
            return '🎯 Perfect! Your CTC salary structure is optimally balanced and matches the target CTC exactly.';
        }
        
        if (abs($difference) < 1000) {
            return '📊 Your CTC salary structure is almost perfectly balanced. Small adjustments suggested.';
        }
        
        if ($difference > 0) {
            $suggestion = $validation['suggestion'] ?? 'Consider increasing flexible allowances.';
            return '📈 Your structure is under budget. ' . $suggestion;
        }
        
        $suggestion = $validation['suggestion'] ?? 'Consider reducing flexible allowances.';
        return '📉 Your structure is over budget. ' . $suggestion;
    }

    /**
     * Generate CTC explanation
     */
    protected function generateCtcExplanation(array $result): string
    {
        $parts = [];
        
        $parts[] = "Basic salary is " . round($result['basic_percentage'], 2) . "% of Fixed CTC.";
        
        if (!empty($result['employer_contributions'])) {
            $contribs = [];
            foreach ($result['employer_contributions'] as $key => $value) {
                if ($value > 0) {
                    $contribs[] = strtoupper($key) . ' (₹' . number_format($value) . '/month)';
                }
            }
            if (!empty($contribs)) {
                $parts[] = "Employer contributions: " . implode(', ', $contribs) . ".";
            }
        }
        
        $validation = $result['validation'];
        if ($validation['is_match'] ?? $validation['match'] ?? false) {
            $parts[] = "Structure exactly matches target Fixed CTC of ₹" . number_format($validation['target'] ?? 0) . ".";
        } else {
            $parts[] = "Difference of ₹" . number_format(abs($validation['difference'] ?? 0)) . " from target Fixed CTC.";
        }
        
        $parts[] = "Special Allowance is used as the balancing component.";
        
        return implode(' ', $parts);
    }

    /**
     * Validate that total CTC matches breakdown sum
     */
    protected function validateCtcMatch(array $result, float $targetCTC): array
    {
        $totalCost = $result['total_cost'] ?? 0;
        $difference = $targetCTC - $totalCost;
        $differencePercent = $targetCTC > 0 ? ($difference / $targetCTC) * 100 : 0;
        
        if (abs($difference) < 100) {
            return [
                'match' => true,
                'is_match' => true,
                'match_status' => '✅ Perfect Match',
                'match_color' => '#dcfce7',
                'match_border' => '#22c55e',
                'match_text' => '#15803d',
                'difference_status' => 'Balanced',
                'difference_color' => '#dcfce7',
                'difference_border' => '#22c55e',
                'difference_text' => '#15803d',
                'difference' => $difference,
                'difference_percent' => $differencePercent,
                'target' => $targetCTC,
                'calculated' => $totalCost
            ];
        }
        
        if (abs($differencePercent) < 5) {
            return [
                'match' => false,
                'is_match' => false,
                'match_status' => '⚠️ Slight Difference',
                'match_color' => '#fef3c7',
                'match_border' => '#f59e0b',
                'match_text' => '#92400e',
                'difference_status' => $difference > 0 ? 'Under Budget' : 'Over Budget',
                'difference_color' => '#fef3c7',
                'difference_border' => '#f59e0b',
                'difference_text' => '#92400e',
                'difference' => $difference,
                'difference_percent' => $differencePercent,
                'target' => $targetCTC,
                'calculated' => $totalCost,
                'suggestion' => $difference > 0 
                    ? 'Increase allowances by ₹' . number_format(abs($difference)) 
                    : 'Reduce allowances by ₹' . number_format(abs($difference))
            ];
        }
        
        return [
            'match' => false,
            'is_match' => false,
            'match_status' => '❌ Needs Adjustment',
            'match_color' => '#fee2e2',
            'match_border' => '#ef4444',
            'match_text' => '#991b1b',
            'difference_status' => $difference > 0 ? 'Under Budget' : 'Over Budget',
            'difference_color' => '#fee2e2',
            'difference_border' => '#ef4444',
            'difference_text' => '#991b1b',
            'difference' => $difference,
            'difference_percent' => $differencePercent,
            'target' => $targetCTC,
            'calculated' => $totalCost,
            'suggestion' => $difference > 0 
                ? 'Significantly under budget. Consider increasing special allowance or adding bonuses.' 
                : 'Significantly over budget. Consider reducing special allowance or other flexible components.'
        ];
    }

    /**
     * Format optimizer result for display
     */
    protected function formatCtcOptimizerResult(array $result, array $validation): string
    {
        $html = '';
        
        $html .= '<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 16px; border-radius: 12px; color: white; margin-bottom: 16px;">';
        $html .= '<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">';
        $html .= '<div><strong style="font-size: 18px;">' . $result['summary'] . '</strong></div>';
        $html .= '<div style="background: ' . ($result['health_score'] >= 90 ? '#22c55e' : ($result['health_score'] >= 70 ? '#f59e0b' : '#ef4444')) . '; padding: 4px 16px; border-radius: 20px; font-weight: 700;">' . $result['health_score'] . '% Health Score</div>';
        $html .= '</div>';
        
        if (!$validation['match']) {
            $html .= '<div style="background: ' . ($validation['match_color']) . '; padding: 10px 14px; border-radius: 8px; margin-top: 10px; border-left: 4px solid ' . $validation['match_border'] . ';">';
            $html .= '<strong style="color: ' . $validation['match_text'] . ';">CTC Validation: ' . $validation['match_status'] . '</strong><br>';
            $html .= '<span style="font-size: 13px; color: ' . $validation['match_text'] . ';">';
            $html .= 'Target CTC: ₹' . number_format($result['total_ctc']) . ' | Calculated: ₹' . number_format($result['total_cost']) . ' | Difference: ₹' . number_format($validation['difference']);
            if (isset($validation['suggestion'])) {
                $html .= '<br><strong>💡 Suggestion:</strong> ' . $validation['suggestion'];
            }
            $html .= '</span>';
            $html .= '</div>';
        } else {
            $html .= '<div style="background: #dcfce7; padding: 8px 14px; border-radius: 8px; margin-top: 10px; border-left: 4px solid #22c55e;">';
            $html .= '<span style="color: #15803d; font-weight: 500;">✅ CTC perfectly matches breakdown!</span>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        if (!empty($result['changes'])) {
            $html .= '<div style="background: #f0fdf4; padding: 16px; border-radius: 10px; border-left: 4px solid #22c55e; margin-bottom: 12px;">';
            $html .= '<h6 style="margin: 0 0 10px 0; color: #166534;"><i class="fas fa-check-circle"></i> Recommended Changes</h6>';
            
            foreach ($result['changes'] as $change) {
                $icon = $change['monthly_change'] > 0 ? '↑' : '↓';
                $color = $change['monthly_change'] > 0 ? '#16a34a' : '#dc2626';
                
                $html .= '<div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px dashed #dcfce7; font-size: 14px;">';
                $html .= '<span><strong>' . $change['component'] . '</strong> ' . $change['from'] . ' → ' . $change['to'] . '</span>';
                $html .= '<span style="color: ' . $color . '; font-weight: 600;">' . $icon . ' ₹' . number_format(abs($change['monthly_change'])) . '/month</span>';
                $html .= '</div>';
            }
            
            $html .= '</div>';
        }
        
        $html .= '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">';
        
        $html .= '<div style="background: #f8fafc; padding: 12px; border-radius: 8px;">';
        $html .= '<h6 style="margin: 0 0 8px 0; color: #1e293b;">📊 CTC Allowances</h6>';
        foreach ($result['allowances'] as $key => $allowance) {
            $html .= '<div style="display: flex; justify-content: space-between; font-size: 13px; padding: 2px 0;">';
            $html .= '<span style="color: #64748b;">' . ($allowance['name'] ?? ucfirst($key)) . '</span>';
            $html .= '<span style="font-weight: 500;">₹' . number_format($allowance['monthly'] ?? 0) . '/mo</span>';
            $html .= '</div>';
        }
        $html .= '</div>';
        
        $html .= '<div style="background: #f8fafc; padding: 12px; border-radius: 8px;">';
        $html .= '<h6 style="margin: 0 0 8px 0; color: #1e293b;">💸 CTC Deductions</h6>';
        foreach ($result['deductions'] as $deduction) {
            $html .= '<div style="display: flex; justify-content: space-between; font-size: 13px; padding: 2px 0;">';
            $html .= '<span style="color: #64748b;">' . ($deduction['name'] ?? 'Unknown') . '</span>';
            $html .= '<span style="font-weight: 500;">₹' . number_format($deduction['monthly'] ?? 0) . '/mo</span>';
            $html .= '</div>';
        }
        $html .= '</div>';
        
        $html .= '</div>';
        
        $html .= '<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 12px;">';
        $html .= '<div style="background: #dbeafe; padding: 10px; border-radius: 8px; text-align: center;">';
        $html .= '<div style="font-size: 12px; color: #64748b;">Basic Monthly</div>';
        $html .= '<div style="font-weight: 700; color: #1e293b;">₹' . number_format($result['basic_monthly'] ?? 0) . '</div>';
        $html .= '</div>';
        $html .= '<div style="background: #dcfce7; padding: 10px; border-radius: 8px; text-align: center;">';
        $html .= '<div style="font-size: 12px; color: #64748b;">Total Monthly</div>';
        $html .= '<div style="font-weight: 700; color: #1e293b;">₹' . number_format($result['gross_monthly'] ?? 0) . '</div>';
        $html .= '</div>';
        $html .= '<div style="background: #fef3c7; padding: 10px; border-radius: 8px; text-align: center;">';
        $html .= '<div style="font-size: 12px; color: #64748b;">Net Monthly</div>';
        $html .= '<div style="font-weight: 700; color: #1e293b;">₹' . number_format(($result['gross_monthly'] ?? 0) - ($result['deductions_total_monthly'] ?? 0)) . '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Extract suggestions from optimizer result
     */
    protected function extractCtcSuggestions(array $result): array
    {
        $suggestions = [];
        
        $suggestions[] = 'Basic percentage optimized to ' . round($result['basic_percentage'], 2) . '%';
        
        $difference = $result['difference'] ?? 0;
        if (abs($difference) < 100) {
            $suggestions[] = '✅ CTC structure is perfectly balanced!';
        } else {
            $suggestions[] = 'Remaining difference: ₹' . number_format($difference);
            if ($difference > 0) {
                $suggestions[] = '💡 Consider increasing allowances to utilize full CTC';
            } else {
                $suggestions[] = '💡 Consider reducing allowances to match CTC';
            }
        }
        
        if (($result['health_score'] ?? 0) >= 90) {
            $suggestions[] = '🎯 Excellent health score!';
        } elseif (($result['health_score'] ?? 0) >= 70) {
            $suggestions[] = '📊 Good structure with room for improvement';
        } else {
            $suggestions[] = '⚠️ Consider reviewing the structure for better optimization';
        }
        
        foreach ($result['changes'] as $change) {
            if (strpos($change['component'], 'Allowance') !== false || strpos($change['component'], 'Allowance') !== false) {
                $suggestions[] = $change['component'] . ' adjusted from ' . $change['from'] . ' to ' . $change['to'];
            }
        }
        
        return $suggestions;
    }

    /**
     * Get recommendations for display
     */
    protected function getCtcRecommendations(array $result): array
    {
        $validation = $result['validation'] ?? [];
        
        return [
            [
                'label' => 'Basic Percentage',
                'value' => round($result['basic_percentage'], 2) . '%',
                'note' => 'Optimized Basic',
                'color' => '#dbeafe',
                'borderColor' => '#3b82f6',
                'valueColor' => '#1d4ed8'
            ],
            [
                'label' => 'Health Score',
                'value' => round($result['health_score']) . '%',
                'note' => 'Structure Quality',
                'color' => '#dcfce7',
                'borderColor' => '#22c55e',
                'valueColor' => '#15803d'
            ],
            [
                'label' => 'Difference',
                'value' => '₹' . number_format($validation['difference'] ?? 0),
                'note' => $validation['difference_status'] ?? 'Balanced',
                'color' => $validation['difference_color'] ?? '#dcfce7',
                'borderColor' => $validation['difference_border'] ?? '#22c55e',
                'valueColor' => $validation['difference_text'] ?? '#15803d'
            ],
            [
                'label' => 'Total Cost',
                'value' => '₹' . number_format($result['total_cost']),
                'note' => $validation['match_status'] ?? 'Calculated',
                'color' => $validation['match_color'] ?? '#dcfce7',
                'borderColor' => $validation['match_border'] ?? '#22c55e',
                'valueColor' => $validation['match_text'] ?? '#15803d'
            ]
        ];
    }

    ////////////////////////////////////////


    public function viewCtcEmployeeSalaryStructures($employeeId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not associated with any institute.'
                ], 403);
            }
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        // Get employee details
        $employee = $this->getCommonQuery(EmployeeDetails::class)
            ->where('employee_id', $employeeId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$employee) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found.'
                ], 404);
            }
            abort(404, 'Employee not found');
        }

        // Get department details
        $department = null;
        if ($employee->department_id) {
            $department = $this->getCommonQuery(Departments::class)
                ->where('department_id', $employee->department_id)
                ->first();
        }

        // Get all salary structures for this employee
        $salaryStructures = EmployeeSalaryStructure::where('employee_id', $employeeId)
            ->where('institute_id', $context['institute_id'])
            ->orderBy('financial_year', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Load related data for all structures
        $structureIds = $salaryStructures->pluck('salary_structure_id')->toArray();
        
        $allowances = SalaryStructureAllowances::whereIn('salary_structure_id', $structureIds)
            ->where('institute_id', $context['institute_id'])
            ->get()
            ->keyBy('salary_structure_id');
            
        $bonuses = SalaryStructureBonus::whereIn('salary_structure_id', $structureIds)
            ->where('institute_id', $context['institute_id'])
            ->get()
            ->groupBy('salary_structure_id');
            
        $overtime = SalaryStructureOvertime::whereIn('salary_structure_id', $structureIds)
            ->where('institute_id', $context['institute_id'])
            ->get()
            ->keyBy('salary_structure_id');
            
        $deductions = SalaryStructureDeduction::whereIn('salary_structure_id', $structureIds)
            ->where('institute_id', $context['institute_id'])
            ->get()
            ->keyBy('salary_structure_id');
            
        $previews = SalaryPreview::whereIn('salary_structure_id', $structureIds)
            ->where('institute_id', $context['institute_id'])
            ->get()
            ->keyBy('salary_structure_id');

        // Get policy information
        $policyIds = $salaryStructures->pluck('payroll_policy_id')->filter()->toArray();
        $policies = [];
        if (!empty($policyIds)) {
            $policies = ProvidentFundPolicy::whereIn('payroll_policy_id', $policyIds)
                ->get()
                ->keyBy('payroll_policy_id');
        }

        // Prepare structured data for view
        $structuredData = [];
        foreach ($salaryStructures as $structure) {
            $policy = $policies[$structure->payroll_policy_id] ?? null;
            
            $structuredData[] = [
                'structure' => $structure,
                'allowances' => $allowances[$structure->salary_structure_id] ?? null,
                'bonuses' => $bonuses[$structure->salary_structure_id] ?? collect(),
                'overtime' => $overtime[$structure->salary_structure_id] ?? null,
                'deductions' => $deductions[$structure->salary_structure_id] ?? null,
                'preview' => $previews[$structure->salary_structure_id] ?? null,
                'policy' => $policy,
                'statistics' => $this->calculateCtcStatistics($structure, $previews[$structure->salary_structure_id] ?? null)
            ];
        }

        // Calculate summary statistics
        $summary = $this->calculateCtcEmployeeSummary($salaryStructures, $previews);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'employee' => $employee,
                    'department' => $department,
                    'structures' => $structuredData,
                    'summary' => $summary,
                    'total_structures' => $salaryStructures->count()
                ]
            ]);
        }

        return view('instituteAdmin.Payroll.EmployeeSalaryStructureView', [
            'employee' => $employee,
            'department' => $department,
            'structuredData' => $structuredData,
            'summary' => $summary,
            'totalStructures' => $salaryStructures->count()
        ]);
    }

    /**
     * Calculate statistics for a single CTC structure
     */
    protected function calculateCtcStatistics($structure, $preview): array
    {
        $allowancesTotal = 0;
        $deductionsTotal = 0;
        $bonusesTotal = 0;
        
        if ($preview) {
            $allowancesTotal = ($preview->total_allowances_monthly ?? 0) + 
                            ($preview->total_bonus_monthly ?? 0) + 
                            ($preview->total_overtime_monthly ?? 0);
            $deductionsTotal = $preview->total_deductions_monthly ?? 0;
            $bonusesTotal = $preview->total_bonus_monthly ?? 0;
        }
        
        $netMonthly = $preview ? $preview->net_salary_monthly : 0;
        $netAnnual = $preview ? $preview->net_salary_annual : 0;
        $totalCost = $preview ? $preview->total_cost_monthly : 0;
        
        $ctcUtilized = $structure->total_ctc_annual > 0 
            ? round((($structure->fixed_ctc_annual + $structure->variable_ctc_annual) / $structure->total_ctc_annual) * 100, 2)
            : 0;
        
        return [
            'allowances_total_monthly' => $allowancesTotal,
            'allowances_total_annual' => $allowancesTotal * 12,
            'deductions_total_monthly' => $deductionsTotal,
            'deductions_total_annual' => $deductionsTotal * 12,
            'bonuses_total_monthly' => $bonusesTotal,
            'bonuses_total_annual' => $bonusesTotal * 12,
            'net_monthly' => $netMonthly,
            'net_annual' => $netAnnual,
            'total_cost_monthly' => $totalCost,
            'total_cost_annual' => $totalCost * 12,
            'ctc_utilized_percentage' => $ctcUtilized,
            'basic_percentage' => $structure->total_ctc_annual > 0 
                ? round(($structure->basic_salary_annual / $structure->total_ctc_annual) * 100, 2)
                : 0
        ];
    }

    /**
     * Calculate summary statistics for all employee structures
     */
    protected function calculateCtcEmployeeSummary($salaryStructures, $previews): array
    {
        $totalStructures = $salaryStructures->count();
        $activeStructures = $salaryStructures->where('status', 'active')->count();
        $inactiveStructures = $salaryStructures->where('status', 'inactive')->count();
        
        $latestStructure = $salaryStructures->first();
        $latestPreview = $latestStructure ? $previews[$latestStructure->salary_structure_id] ?? null : null;
        
        $totalFixedCTC = $salaryStructures->sum('fixed_ctc_annual');
        $totalVariableCTC = $salaryStructures->sum('variable_ctc_annual');
        $totalCTC = $salaryStructures->sum('total_ctc_annual');
        
        $latestNetMonthly = $latestPreview ? $latestPreview->net_salary_monthly : 0;
        $latestNetAnnual = $latestPreview ? $latestPreview->net_salary_annual : 0;
        
        $avgCTC = $totalStructures > 0 ? round($totalCTC / $totalStructures, 2) : 0;
        
        // Get financial years
        $financialYears = $salaryStructures->pluck('financial_year')->unique()->values()->toArray();
        
        // Get employment types
        $employmentTypes = $salaryStructures->pluck('employment_type')->unique()->values()->toArray();
        
        return [
            'total_structures' => $totalStructures,
            'active_structures' => $activeStructures,
            'inactive_structures' => $inactiveStructures,
            'total_fixed_ctc' => $totalFixedCTC,
            'total_variable_ctc' => $totalVariableCTC,
            'total_ctc' => $totalCTC,
            'average_ctc' => $avgCTC,
            'latest_net_monthly' => $latestNetMonthly,
            'latest_net_annual' => $latestNetAnnual,
            'financial_years' => $financialYears,
            'employment_types' => $employmentTypes,
            'latest_structure' => $latestStructure,
            'has_active_structure' => $activeStructures > 0
        ];
    }


    /**
     * Download employee salary structure report
     */
    public function downloadCtcEmployeeSalaryStructures($employeeId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        $employee = $this->getCommonQuery(EmployeeDetails::class)
            ->where('employee_id', $employeeId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$employee) {
            abort(404, 'Employee not found');
        }

        $salaryStructures = EmployeeSalaryStructure::where('employee_id', $employeeId)
            ->where('institute_id', $context['institute_id'])
            ->orderBy('financial_year', 'desc')
            ->get();

        $structureIds = $salaryStructures->pluck('salary_structure_id')->toArray();
        
        $previews = SalaryPreview::whereIn('salary_structure_id', $structureIds)
            ->where('institute_id', $context['institute_id'])
            ->get()
            ->keyBy('salary_structure_id');

        // Generate CSV report
        $filename = 'salary_structures_' . $employee->employee_code . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($salaryStructures, $previews, $employee) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'Financial Year',
                'Status',
                'Employment Type',
                'Fixed CTC (Annual)',
                'Variable CTC (Annual)',
                'Total CTC (Annual)',
                'Basic Monthly',
                'Basic Annual',
                'Net Monthly',
                'Net Annual',
                'Total Cost Monthly',
                'Total Cost Annual',
                'Created At',
                'Updated At'
            ]);
            
            // Add data rows
            foreach ($salaryStructures as $structure) {
                $preview = $previews[$structure->salary_structure_id] ?? null;
                
                fputcsv($file, [
                    $structure->financial_year,
                    $structure->status ?? 'active',
                    $structure->employment_type ?? 'full-time',
                    $structure->fixed_ctc_annual ?? 0,
                    $structure->variable_ctc_annual ?? 0,
                    $structure->total_ctc_annual ?? 0,
                    $structure->basic_salary_monthly ?? 0,
                    $structure->basic_salary_annual ?? 0,
                    $preview ? $preview->net_salary_monthly : 0,
                    $preview ? $preview->net_salary_annual : 0,
                    $preview ? $preview->total_cost_monthly : 0,
                    $preview ? $preview->total_cost_annual : 0,
                    $structure->created_at,
                    $structure->updated_at
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}


