<?php

namespace App\Http\Controllers\institute\Admin\PayrollPolicy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
use Illuminate\Support\Facades\Log;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
class EmployeeSalaryStructureController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;


    public function storesalarystructure(Request $request)
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
            'status' => 'nullable|in:active,inactive' ,
            'department_category_id' => 'nullable|string|max:50',
            'department_id' => 'nullable|string|max:50',
            'designation_id' => 'nullable|string|max:50',
            'employee_id' => 'nullable|string|max:50',
            'override' => 'nullable|boolean',
            'fixed_ctc_annual' => 'required|numeric|min:0',
            'variable_ctc_annual' => 'nullable|numeric|min:0',
            'basic_salary_monthly' => 'required|numeric|min:0',
            'basic_salary_annual' => 'required|numeric|min:0'
        ]);

        if (empty($validated['employee_id'])) {
            return response()->json([
                'success' => false,
                'message' => 'Employee ID is required.'
            ], 422);
        }

        $validated['variable_ctc_annual'] = $validated['variable_ctc_annual'] ?? 0;
        $validated['total_ctc_annual'] = $validated['fixed_ctc_annual'] + $validated['variable_ctc_annual'];
        $validated['monthly_fixed'] = round($validated['fixed_ctc_annual'] / 12, 2);
        $validated['monthly_variable'] = round($validated['variable_ctc_annual'] / 12, 2);
        $validated['basic_salary_percentage'] = $validated['fixed_ctc_annual'] > 0 
            ? round(($validated['basic_salary_annual'] / $validated['fixed_ctc_annual']) * 100, 2) 
            : 0;

        $validated['institute_id'] = $context['institute_id'];
        $validated['branch_id'] = $context['is_branch_admin'] ? $context['branch_id'] : null;

        // Check if salary structure already exists (including inactive ones)
        $salaryStructure = EmployeeSalaryStructure::where([
            'employee_id' => $validated['employee_id'],
            'financial_year' => $validated['financial_year'],
            'institute_id' => $validated['institute_id']
        ])->first();

        $override = filter_var($request->input('override'), FILTER_VALIDATE_BOOLEAN);

        // If structure exists and is ACTIVE, and override is false, ask for override
        if ($salaryStructure && $salaryStructure->status === 'active' && !$override) {
            $employeeName = $this->getCommonQuery(EmployeeDetails::class)
                ->where('employee_id', $validated['employee_id'])
                ->value('name') ?? 'This employee';

            return response()->json([
                'success' => false,
                'exists' => true,
                'is_inactive' => false,
                'message' => "{$employeeName} already has an ACTIVE salary structure for {$validated['financial_year']}. Please choose to override or view the existing structure.",
                'data' => [
                    'salary_structure_id' => $salaryStructure->salary_structure_id,
                    'employee_id' => $validated['employee_id'],
                    'employee_name' => $employeeName,
                    'financial_year' => $validated['financial_year'],
                    'structure_type' => $salaryStructure->structure_type ?? 'department',
                    'status' => $salaryStructure->status,
                    'view_url' => route('institute.payroll.salary.details', $salaryStructure->salary_structure_id),
                ]
            ], 409);
        }

        // If structure exists (active or inactive) and we are saving
        if ($salaryStructure) {
            $salaryStructure->load(['allowances', 'deductions', 'preview']);
            
            $previousData = [
                'basic_salary_monthly' => $salaryStructure->basic_salary_monthly,
                'basic_salary_annual' => $salaryStructure->basic_salary_annual,
                'fixed_ctc_annual' => $salaryStructure->fixed_ctc_annual,
                'variable_ctc_annual' => $salaryStructure->variable_ctc_annual,
                'total_ctc_annual' => $salaryStructure->total_ctc_annual,
                'allowances' => $salaryStructure->allowances ? $salaryStructure->allowances->toArray() : null,
                'deductions' => $salaryStructure->deductions ? $salaryStructure->deductions->toArray() : null,
                'preview' => $salaryStructure->preview ? $salaryStructure->preview->toArray() : null
            ];
            
            // CRITICAL: Always set status to 'active' when updating/overriding
            $validated['status'] = 'active';
            
            // Update the structure
            $salaryStructure->update($validated);
            
            // Force a direct database update to ensure status is changed
            EmployeeSalaryStructure::where('salary_structure_id', $salaryStructure->salary_structure_id)
                ->update(['status' => 'active']);
            
            // Log the action
            \App\Models\SalaryStructureLog::updateOrCreate(
                [
                    'salary_structure_id' => $salaryStructure->salary_structure_id,
                ],
                [
                    'institute_id' => $context['institute_id'],
                    'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                    'structure_type' => $salaryStructure->structure_type,
                    'department_id' => $salaryStructure->department_id,
                    'employee_id' => $salaryStructure->employee_id,
                    'financial_year' => $salaryStructure->financial_year,
                    'action' => 'Override',
                    'change_type' => 'Base Structure',
                    'previous_data' => json_encode($previousData),
                    'current_data' => null,
                    'changed_by' => auth()->id() ?? null,
                ]
            );
            
            $message = 'Salary structure overridden and activated successfully.';
            $wasRecentlyCreated = false;
        } else {
            // Create new structure with active status
            $validated['salary_structure_id'] = 'SAL' . strtoupper(substr(md5(uniqid()), 0, 8));
            $validated['status'] = 'active';
            
            $salaryStructure = EmployeeSalaryStructure::create($validated);
            
            // Log the creation
            \App\Models\SalaryStructureLog::create([
                'salary_structure_id' => $salaryStructure->salary_structure_id,
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                'structure_type' => $salaryStructure->structure_type,
                'department_id' => $salaryStructure->department_id,
                'employee_id' => $salaryStructure->employee_id,
                'financial_year' => $salaryStructure->financial_year,
                'action' => 'Create',
                'change_type' => 'Base Structure',
                'previous_data' => null,
                'current_data' => null,
                'changed_by' => auth()->id() ?? null,
            ]);
            
            $message = 'Salary structure created successfully.';
            $wasRecentlyCreated = true;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'salary_structure_id' => $salaryStructure->salary_structure_id,
                'overridden' => !$wasRecentlyCreated,
                'status' => $salaryStructure->status
            ]
        ], $wasRecentlyCreated ? 201 : 200);
    }

    public function getAllSalaryStructures()
    {
        // ✅ Get institute context
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $data = EmployeeSalaryStructure::where(
            'institute_id',
            $context['institute_id']
        )
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getSalaryStructure($salary_structure_id)
    {
        // ✅ Get institute context
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $salaryStructure = EmployeeSalaryStructure::where(
            'salary_structure_id',
            $salary_structure_id
        )
            ->where(
                'institute_id',
                $context['institute_id']
            )
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $salaryStructure
        ]);
    }

    // Allowances
    public function storeSalaryAllowances(Request $request)
    {
        $request->merge(json_decode($request->getContent(), true));

        // ✅ Get institute context
        $context = $this->getInstituteBranchContext();

        // ✅ Institute access check
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $validated = $request->validate([
            'salary_structure_id' => 'required|string|exists:employee_salary_structures,salary_structure_id',

            'hra_selected' => 'boolean',
            'hra_type' => 'in:percentage,fixed',
            'hra_value' => 'numeric|min:0',
            'hra_value_monthly' => 'numeric|min:0',
            'hra_value_annual' => 'numeric|min:0',

            'conveyance_selected' => 'boolean',
            'conveyance_type' => 'in:percentage,fixed',
            'conveyance_value' => 'numeric|min:0',
            'conveyance_value_monthly' => 'numeric|min:0',
            'conveyance_value_annual' => 'numeric|min:0',

            'medical_selected' => 'boolean',
            'medical_type' => 'in:percentage,fixed',
            'medical_value' => 'numeric|min:0',
            'medical_value_monthly' => 'numeric|min:0',
            'medical_value_annual' => 'numeric|min:0',

            'special_selected' => 'boolean',
            'special_type' => 'in:percentage,fixed',
            'special_value' => 'numeric|min:0',
            'special_value_monthly' => 'numeric|min:0',
            'special_value_annual' => 'numeric|min:0',

            'lta_selected' => 'boolean',
            'lta_type' => 'in:percentage,fixed',
            'lta_value' => 'numeric|min:0',
            'lta_value_monthly' => 'numeric|min:0',
            'lta_value_annual' => 'numeric|min:0',

            'education_selected' => 'boolean',
            'education_type' => 'in:percentage,fixed',
            'education_value' => 'numeric|min:0',
            'education_value_monthly' => 'numeric|min:0',
            'education_value_annual' => 'numeric|min:0',

            'custom_allowances' => 'nullable|array',
            'custom_allowances.*.name' => 'required|string',
            'custom_allowances.*.type' => 'required|in:fixed,percentage',
            'custom_allowances.*.description' => 'nullable|string|max:255',
            'custom_allowances.*.selected' => 'nullable|boolean',
            'custom_allowances.*.enabled' => 'nullable|boolean',
            'custom_allowances.*.value' => 'required|numeric',
            'custom_allowances.*.value_monthly' => 'required|numeric',
            'custom_allowances.*.value_annual' => 'required|numeric',

        ]);

        \Log::info($validated);

        // ✅ Attach institute only
        $validated['institute_id'] = $context['institute_id'];

        // ✅ Secure updateOrCreate condition
        SalaryStructureAllowances::updateOrCreate(
            [
                'salary_structure_id' => $validated['salary_structure_id'],
                'institute_id' => $context['institute_id']
            ],
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Allowances saved successfully'
        ]);
    }

    public function getAllSalaryAllowances()
    {
        // ✅ Get institute context
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $data = SalaryStructureAllowances::where(
            'institute_id',
            $context['institute_id']
        )->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getSalaryAllowances($salary_structure_id)
    {
        // ✅ Get institute context
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $allowances = SalaryStructureAllowances::where(
            'salary_structure_id',
            $salary_structure_id
        )
            ->where(
                'institute_id',
                $context['institute_id']
            )
            ->first();

        return response()->json([
            'success' => true,
            'data' => $allowances
        ]);
    }


    // Bonus
    public function storeSalaryBonus(Request $request)
    {
        if ($request->isJson()) {
            $request->merge($request->json()->all());
        }

        // ✅ Get institute context
        $context = $this->getInstituteBranchContext();

        // ✅ Institute access check
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $validated = $request->validate([
            'salary_structure_id' =>
                'required|string|exists:employee_salary_structures,salary_structure_id',

            'bonuses' => 'required|array|min:1',

            'bonuses.*.bonus_name' =>
                'required|string|max:100',

            'bonuses.*.bonus_type' =>
                'required|in:fixed,percentage,ctc_percentage',

            'bonuses.*.frequency' =>
                'required|in:monthly,quarterly,yearly',

            'bonuses.*.bonus_value' =>
                'required|numeric|min:0',

            'bonuses.*.bonus_value_monthly' =>
                'required|numeric|min:0',

            'bonuses.*.bonus_value_annual' =>
                'required|numeric|min:0',

            'bonuses.*.description' =>
                'nullable|string|max:255',
        ]);

        foreach ($validated['bonuses'] as $bonus) {

            // ✅ Attach institute only
            SalaryStructureBonus::updateOrCreate(
                [
                    'salary_structure_id' => $validated['salary_structure_id'],
                    'bonus_name' => $bonus['bonus_name'],
                    'institute_id' => $context['institute_id']
                ],
                [
                    'bonus_type' => $bonus['bonus_type'],
                    'frequency' => $bonus['frequency'],
                    'bonus_value' => $bonus['bonus_value'],
                    'bonus_value_monthly' => $bonus['bonus_value_monthly'],
                    'bonus_value_annual' => $bonus['bonus_value_annual'],
                    'description' => $bonus['description'] ?? null,
                    'institute_id' => $context['institute_id'],
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Bonuses saved successfully',
        ], 201);
    }


    public function getAllSalaryBonuses()
    {
        // ✅ Get institute context
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $data = SalaryStructureBonus::where(
            'institute_id',
            $context['institute_id']
        )->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getSalaryBonuses($salary_structure_id)
    {
        // ✅ Get institute context
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $bonuses = SalaryStructureBonus::where(
            'salary_structure_id',
            $salary_structure_id
        )
            ->where(
                'institute_id',
                $context['institute_id']
            )
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bonuses
        ]);
    }

    // Overtime
    public function storeSalaryOvertime(Request $request)
    {
        // Merge JSON safely
        if ($request->isJson()) {
            $request->merge($request->json()->all());
        }

        // ✅ Get institute context
        $context = $this->getInstituteBranchContext();

        // ✅ Institute check
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $validated = $request->validate([
            'salary_structure_id' => 'nullable|string|exists:employee_salary_structures,salary_structure_id',

            'rule_name' => 'nullable|string|max:100',
            'rate_type' => 'nullable|in:per_hour,fixed,percentage',
            'frequency' => 'nullable|in:monthly,quarterly,yearly',

            'rate_value' => 'nullable|numeric|min:0',
            'rate_value_monthly' => 'nullable|numeric|min:0',
            'rate_value_annual' => 'nullable|numeric|min:0',

            'applicable_days' => 'nullable|array',
            'applicable_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',

            'min_hours' => 'nullable|numeric|min:0',
            'description' => 'nullable|string'
        ]);

        // ✅ Attach only institute_id
        SalaryStructureOvertime::updateOrCreate(
            [
                'salary_structure_id' => $validated['salary_structure_id'],
                'institute_id' => $context['institute_id']
            ],
            array_merge($validated, [
                'institute_id' => $context['institute_id'],
            ])
        );

        return response()->json([
            'success' => true,
            'message' => 'Overtime rule saved successfully'
        ], 201);
    }

    public function getAllSalaryOvertime()
    {
        // ✅ Get institute context
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $data = SalaryStructureOvertime::where(
            'institute_id',
            $context['institute_id']
        )->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    
    public function getSalaryOvertime($salary_structure_id)
    {
        // ✅ Get institute context
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $overtime = SalaryStructureOvertime::where(
            'salary_structure_id',
            $salary_structure_id
        )
            ->where(
                'institute_id',
                $context['institute_id']
            )
            ->first();

        return response()->json([
            'success' => true,
            'data' => $overtime
        ]);
    }

    // Deductions
    public function storeSalaryDeductions(Request $request)
    {
        // Merge JSON safely
        if ($request->isJson()) {
            $request->merge($request->json()->all());
        }

        // ✅ ADD: Institute context
        $context = $this->getInstituteBranchContext();

        // ✅ ADD: Institute validation
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $validated = $request->validate([
            'salary_structure_id' => 'required|string|exists:employee_salary_structures,salary_structure_id',
            'pt_selected' => 'boolean',
            'pt_type' => 'nullable|in:percentage,fixed,slabs',
            'pt_value' => 'nullable|numeric|min:0',
            'pt_value_monthly' => 'nullable|numeric|min:0',
            'pt_value_annual' => 'nullable|numeric|min:0',
            'pt_slabs' => 'nullable|array',
            'lst_selected' => 'boolean',
            'lst_type' => 'nullable|in:percentage,fixed,slabs',
            'lst_value' => 'nullable|numeric|min:0',
            'lst_value_monthly' => 'nullable|numeric|min:0',
            'lst_value_annual' => 'nullable|numeric|min:0',
            'lst_slabs' => 'nullable|array',
            'tds_selected' => 'boolean',
            'tds_value' => 'nullable|numeric|min:0',
            'tds_value_monthly' => 'nullable|numeric|min:0',
            'tds_value_annual' => 'nullable|numeric|min:0',
            'tds_slabs' => 'nullable|array',
            'insurance_selected' => 'boolean',
            'insurance_type' => 'nullable|in:fixed,percentage',
            'insurance_value' => 'nullable|numeric|min:0',
            'insurance_value_monthly' => 'nullable|numeric|min:0',
            'insurance_value_annual' => 'nullable|numeric|min:0',
            'advance_selected' => 'boolean',
            'advance_type' => 'nullable|in:fixed,percentage',
            'advance_value' => 'nullable|numeric|min:0',
            'advance_value_monthly' => 'nullable|numeric|min:0',
            'advance_value_annual' => 'nullable|numeric|min:0',

            'custom_deductions' => 'nullable|array',
            'custom_deductions.*.name' => 'required|string',
            'custom_deductions.*.type' => 'required|in:fixed,percentage',
            'custom_deductions.*.value' => 'required|numeric',
            'custom_deductions.*.value_monthly' => 'required|numeric',
            'custom_deductions.*.value_annual' => 'required|numeric',
        ]);

        SalaryStructureDeduction::updateOrCreate(
            [
                'salary_structure_id' => $validated['salary_structure_id'],
                'institute_id' => $context['institute_id'], // ✅ Only institute
            ],
            array_merge($validated, [
                'institute_id' => $context['institute_id'], // ✅ Only institute
            ])
        );

        return response()->json([
            'success' => true,
            'message' => 'Salary deductions saved successfully'
        ], 201);
    }

    public function getAllSalaryDeductions()
    {
        // ✅ Institute context
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $data = SalaryStructureDeduction::where(
            'institute_id',
            $context['institute_id']
        )->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getSalaryDeductions($salary_structure_id)
    {
        // ✅ Institute context
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $deductions = SalaryStructureDeduction::where(
            'salary_structure_id',
            $salary_structure_id
        )
            ->where(
                'institute_id',
                $context['institute_id']
            )
            ->first();

        return response()->json([
            'success' => true,
            'data' => $deductions
        ]);
    }

public function storeSalaryPreview(Request $request)
    {
        // Merge JSON safely
        if ($request->isJson()) {
            $request->merge($request->json()->all());
        }
        // :white_check_mark: ADD: Institute context
        $context = $this->getInstituteBranchContext();
        // :white_check_mark: ADD: Access check
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        \Log::info('SALARY PREVIEW REQUEST', $request->all());
        $validated = $request->validate([
            'salary_structure_id' =>
                'required|string|exists:employee_salary_structures,salary_structure_id',
            /* ===============================
            | Earnings
            =============================== */
            'basic_salary_monthly' => 'nullable|numeric|min:0',
            'basic_salary_annual' => 'nullable|numeric|min:0',
            'hra_monthly' => 'nullable|numeric|min:0',
            'hra_annual' => 'nullable|numeric|min:0',
            'conveyance_monthly' => 'nullable|numeric|min:0',
            'conveyance_annual' => 'nullable|numeric|min:0',
            'medical_monthly' => 'nullable|numeric|min:0',
            'medical_annual' => 'nullable|numeric|min:0',
            'special_allowance_monthly' => 'nullable|numeric|min:0',
            'special_allowance_annual' => 'nullable|numeric|min:0',
            'lta_monthly' => 'nullable|numeric|min:0',
            'lta_annual' => 'nullable|numeric|min:0',
            'education_allowance_monthly' => 'nullable|numeric|min:0',
            'education_allowance_annual' => 'nullable|numeric|min:0',
            'bonus_monthly' => 'nullable|numeric|min:0',
            'bonus_annual' => 'nullable|numeric|min:0',
            'overtime_monthly' => 'nullable|numeric|min:0',
            'overtime_annual' => 'nullable|numeric|min:0',
            'total_earnings_monthly' => 'nullable|numeric|min:0',
            'total_earnings_annual' => 'nullable|numeric|min:0',
            /* ===============================
            | Deductions
            =============================== */
            'pt_monthly' => 'nullable|numeric|min:0',
            'pt_annual' => 'nullable|numeric|min:0',
            'lst_monthly' => 'nullable|numeric|min:0',
            'lst_annual' => 'nullable|numeric|min:0',
            'tds_monthly' => 'nullable|numeric|min:0',
            'tds_annual' => 'nullable|numeric|min:0',
            'insurance_premium_monthly' => 'nullable|numeric|min:0',
            'insurance_premium_annual' => 'nullable|numeric|min:0',
            'advance_salary_monthly' => 'nullable|numeric|min:0',
            'advance_salary_annual' => 'nullable|numeric|min:0',
            'pf_employee_monthly' => 'nullable|numeric|min:0',
            'pf_employee_annual' => 'nullable|numeric|min:0',
            'esi_employee_monthly' => 'nullable|numeric|min:0',
            'esi_employee_annual' => 'nullable|numeric|min:0',

            'nps_employee_monthly' => 'nullable|numeric|min:0',
            'nps_employee_annual' => 'nullable|numeric|min:0',

            'total_deductions_monthly' => 'nullable|numeric|min:0',
            'total_deductions_annual' => 'nullable|numeric|min:0',
            /* ===============================
            | Summary
            =============================== */
            'gross_salary_monthly' => 'nullable|numeric|min:0',
            'gross_salary_annual' => 'nullable|numeric|min:0',
            'net_salary_monthly' => 'nullable|numeric|min:0',
            'net_salary_annual' => 'nullable|numeric|min:0',
            'total_allowances_monthly' => 'nullable|numeric|min:0',
            'total_allowances_annual' => 'nullable|numeric|min:0',
            'total_bonus_monthly' => 'nullable|numeric|min:0',
            'total_bonus_annual' => 'nullable|numeric|min:0',
            'total_overtime_monthly' => 'nullable|numeric|min:0',
            'total_overtime_annual' => 'nullable|numeric|min:0',
            /* ===============================
            | Employer
            =============================== */
            'employer_pf_monthly' => 'nullable|numeric|min:0',
            'employer_pf_annual' => 'nullable|numeric|min:0',
            'employer_esi_monthly' => 'nullable|numeric|min:0',
            'employer_esi_annual' => 'nullable|numeric|min:0',
            'employer_nps_monthly' => 'nullable|numeric|min:0',
            'employer_nps_annual' => 'nullable|numeric|min:0',

            /* ===============================
            | Cost
            =============================== */
            'total_cost_monthly' => 'nullable|numeric|min:0',
            'total_cost_annual' => 'nullable|numeric|min:0',
            /* ===============================
            | Additional JSON Field
            =============================== */
            // 'additional_details' => 'nullable|array',
        ]);
        $defaults = collect([
            'basic_salary_monthly',
            'basic_salary_annual',
            'hra_monthly',
            'hra_annual',
            'conveyance_monthly',
            'conveyance_annual',
            'medical_monthly',
            'medical_annual',
            'special_allowance_monthly',
            'special_allowance_annual',
            'lta_monthly',
            'lta_annual',
            'education_allowance_monthly',
            'education_allowance_annual',
            'bonus_monthly',
            'bonus_annual',
            'overtime_monthly',
            'overtime_annual',
            'total_earnings_monthly',
            'total_earnings_annual',
            'pt_monthly',
            'pt_annual',
            'lst_monthly',
            'lst_annual',
            'tds_monthly',
            'tds_annual',
            'insurance_premium_monthly',
            'insurance_premium_annual',
            'advance_salary_monthly',
            'advance_salary_annual',
            'pf_employee_monthly',
            'pf_employee_annual',
            'esi_employee_monthly',
            'esi_employee_annual',
            'nps_employee_monthly',
            'nps_employee_annual',
            'total_deductions_monthly',
            'total_deductions_annual',
            'gross_salary_monthly',
            'gross_salary_annual',
            'net_salary_monthly',
            'net_salary_annual',
            'total_allowances_monthly',
            'total_allowances_annual',
            'total_bonus_monthly',
            'total_bonus_annual',
            'total_overtime_monthly',
            'total_overtime_annual',
            'employer_pf_monthly',
            'employer_pf_annual',
            'employer_esi_monthly',
            'employer_esi_annual',
            'employer_nps_monthly',
            'employer_nps_annual',
            'total_cost_monthly',
            'total_cost_annual'
        ])->mapWithKeys(fn($f) => [$f => 0])->toArray();
        $data = array_merge($defaults, $validated);
        // :white_check_mark: Extract JSON field safely
        $additionalDetails = $request->input('additional_details', []);
        SalaryPreview::updateOrCreate(
            [
                'salary_structure_id' => $data['salary_structure_id'],
                'institute_id' => $context['institute_id']
            ],
            array_merge($data, [
                'institute_id' => $context['institute_id'],
                // :white_check_mark: Store JSON
                'additional_details' => json_encode($additionalDetails),
            ])
        );
        return response()->json([
            'success' => true,
            'message' => 'Salary preview saved successfully'
        ], 201);
    }

    public function getAllSalaryPreviews()
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $data = SalaryPreview::where(
            'institute_id',
            $context['institute_id']
        )->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getSalaryPreview($salary_structure_id)
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $preview = SalaryPreview::where(
            'salary_structure_id',
            $salary_structure_id
        )
            ->where(
                'institute_id',
                $context['institute_id']
            )
            ->first();

        return response()->json([
            'success' => true,
            'data' => $preview
        ]);
    }

    // Dropdown list (salary structures)
    public function salarystructureDropdown()
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $salarystructure = EmployeeSalaryStructure::where(
            'institute_id',
            $context['institute_id']
        )
            ->orderBy('created_at', 'desc')
            ->pluck('salary_structure_id');

        return response()->json([
            'success' => true,
            'data' => $salarystructure
        ]);
    }

    public function viewSalaryManagement(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        // Get filter parameters from request
        $selectedFinancialYear = $request->input('financial_year');
        $selectedStatus = $request->input('status');
        $searchQuery = $request->input('search');

        $employeeIds = [];
        $departmentIds = [];

        if ($searchQuery) {

            $matchedEmployees = EmployeeDetails::where(
                    'institute_id',
                    $context['institute_id']
                )
                ->where('status', 'active')
          
               
                ->where(function ($q) use ($searchQuery) {

                    $q->where('name', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('employee_code', 'LIKE', "%{$searchQuery}%");

                })
                ->get([
                    'employee_id',
                    'department_id'
                ]);

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
                ->where('department', 'LIKE', "%{$searchQuery}%")
                ->pluck('department_id')
                ->toArray();

            $departmentIds = array_unique(array_merge(
                $employeeDepartmentIds,
                $searchDepartmentIds
            ));
        }


        // Build query for salary structures - only ACTIVE by default
        $salaryStructuresQuery = EmployeeSalaryStructure::where(
                'institute_id',
                $context['institute_id']
            )
            ->where('status', 'active')
            ->with([
                'allowances',
                'bonuses',
                'overtime',
                'deductions',
                'preview'
            ]);

        if (!empty($employeeIds)) {

            $salaryStructuresQuery->whereIn(
                'employee_id',
                $employeeIds
            );
        }


        // Apply financial year filter
        if (!empty($selectedFinancialYear)) {
            $salaryStructuresQuery->where('financial_year', $selectedFinancialYear);
        }

        // Get filtered salary structures
        $salaryStructures = $salaryStructuresQuery->orderBy('created_at', 'desc')->get();

        // Get ALL active employees for the institute
        $employeesQuery = $this->getCommonQuery(EmployeeDetails::class)
            ->select(
                'employee_id',
                'employee_code',
                'name',
                'department_id'
            )
            ->where('institute_id', $context['institute_id'])
            ->where('status', 'active')
            ->orderBy('name');

            if ($searchQuery) {

                if (!empty($employeeIds)) {

                    // Show ONLY matching employees
                    $employeesQuery->whereIn(
                        'employee_id',
                        $employeeIds
                    );

                }

                elseif (!empty($departmentIds)) {

                    // Show ALL employees of matching departments
                    $employeesQuery->whereIn(
                        'department_id',
                        $departmentIds
                    );

                }

                else {

                    $employeesQuery->whereRaw('1 = 0');
                }
            }

        $allEmployees = $employeesQuery->get();
        // Get ALL departments
        $departmentQuery = $this->getCommonQuery(Departments::class)
            ->orderBy('department');

            if ($searchQuery) {

                if (!empty($departmentIds)) {

                    $departmentQuery->whereIn(
                        'department_id',
                        $departmentIds
                    );

                } elseif (!empty($employeeIds)) {

                    // Show ONLY departments of matching employees
                    $departmentQuery->whereIn(
                        'department_id',
                        $employeeDepartmentIds
                    );

                } else {

                    $departmentQuery->whereRaw('1 = 0');
                }
            }

        $allDepartments = $departmentQuery
            ->paginate(5)
            ->appends($request->query());

        // Get all structure IDs for related data
        $structureIds = $salaryStructures->pluck('salary_structure_id')->toArray();
        
        // Fetch related data
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

        // Group salary structures by employee for easy access
        $structuresByEmployee = [];
        foreach ($salaryStructures as $structure) {
            $structuresByEmployee[$structure->employee_id] = $structure;
        }

        // For AJAX requests, return filtered data
        if ($request->ajax() || $request->wantsJson()) {
            // Group employees by department
            $employeesByDept = [];
            foreach ($allEmployees as $employee) {
                $deptId = $employee->department_id;
                if (!isset($employeesByDept[$deptId])) {
                    $employeesByDept[$deptId] = [];
                }
                $employeesByDept[$deptId][] = $employee;
            }

            // Build enriched departments structure
            $enrichedDepartments = [];
            foreach ($allDepartments as $department) {
                $departmentEmployees = $employeesByDept[$department->department_id] ?? [];
                $enrichedEmployees = [];
                
                foreach ($departmentEmployees as $employee) {
                    $structure = $structuresByEmployee[$employee->employee_id] ?? null;
                    
                    // Apply status filter
                    if (!empty($selectedStatus)) {
                        if ($selectedStatus === 'no_structure' && $structure) {
                            continue;
                        }
                        if ($selectedStatus !== 'no_structure' && (!$structure || $structure->status !== $selectedStatus)) {
                            continue;
                        }
                    }
                    
                    // Apply search filter
                    if (!empty($searchQuery)) {
                        $searchLower = strtolower($searchQuery);
                        if (!str_contains(strtolower($employee->name), $searchLower) && 
                            !str_contains(strtolower($department->department), $searchLower) &&
                            !str_contains(strtolower($employee->employee_code ?? ''), $searchLower)) {
                            continue;
                        }
                    }
                    
                    $preview = $structure ? ($previews[$structure->salary_structure_id] ?? null) : null;
                    
                    $enrichedEmployees[] = [
                        'employee_id' => $employee->employee_id,
                        'employee_code' => $employee->employee_code,
                        'name' => $employee->name,
                        'department_id' => $employee->department_id,
                        'salary_structure' => $structure ? [
                            'salary_structure_id' => $structure->salary_structure_id,
                            'financial_year' => $structure->financial_year,
                            'fixed_ctc_annual' => $structure->fixed_ctc_annual,
                            'variable_ctc_annual' => $structure->variable_ctc_annual,
                            'total_ctc_annual' => $structure->total_ctc_annual,
                            'basic_salary_monthly' => $structure->basic_salary_monthly,
                            'basic_salary_annual' => $structure->basic_salary_annual,
                            'status' => $structure->status,
                            'created_at' => $structure->created_at,
                            'finalized_at' => $structure->finalized_at,
                            'net_salary_monthly' => $preview ? $preview->net_salary_monthly : null,
                            'net_salary_annual' => $preview ? $preview->net_salary_annual : null,
                        ] : null
                    ];
                }
                
                // Only include departments that have employees after filtering
                if (!empty($enrichedEmployees)) {
                    $enrichedDepartments[] = [
                        'department_id' => $department->department_id,
                        'department_name' => $department->department,
                        'employees' => $enrichedEmployees
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'departments' => $enrichedDepartments,
                'filters' => [
                    'financial_year' => $selectedFinancialYear,
                    'status' => $selectedStatus,
                    'search' => $searchQuery
                ]
            ]);
        }

        // Generate financial years list for the view dropdown
        $currentYear = date('Y');
        $financialYearsList = [];
        for($i = -2; $i <= 2; $i++) {
            $start = $currentYear + $i;
            $end = $start + 1;
            $financialYearsList[] = $start . '-' . $end;
        }
        
        // Calculate current financial year for display
        $currentFinancialYear = date('Y') . '-' . (date('Y') + 1);
        if(date('m') < 4) {
            $currentFinancialYear = (date('Y') - 1) . '-' . date('Y');
        }
        $displayFinancialYear = $selectedFinancialYear ?: $currentFinancialYear;

        // For non-AJAX requests, return the view with all active data
        return view('instituteAdmin.Payroll.SalaryManagement', [
            'departments' => $allDepartments,
            'employees' => $allEmployees,
            'salaryStructures' => $salaryStructures,
            'structuresByEmployee' => $structuresByEmployee,
            'previews' => $previews,
            'allowances' => $allowances,
            'bonuses' => $bonuses,
            'overtime' => $overtime,
            'deductions' => $deductions,
            'selectedFinancialYear' => $selectedFinancialYear,
            'selectedStatus' => $selectedStatus,
            'searchQuery' => $searchQuery,
            'displayFinancialYear' => $displayFinancialYear,
            'currentFinancialYear' => $currentFinancialYear,
            'financialYearsList' => $financialYearsList
        ]);
    }    

    public function viewSalaryDetails($salaryStructureId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        // Get the salary structure with all relations
        $structure = EmployeeSalaryStructure::where('salary_structure_id', $salaryStructureId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$structure) {
            abort(404, 'Salary structure not found');
        }

        // Get related data
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

        // Get employee details
        $employee = $this->getCommonQuery(EmployeeDetails::class)
            ->where('employee_id', $structure->employee_id)
            ->first();

        // Get department details
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

    public function checkExistingStructures(Request $request)
    {
        $employeeIds = $request->employee_ids;
        $financialYear = $request->financial_year;

        $existing = EmployeeSalaryStructure::whereIn('employee_id', $employeeIds)
            ->where('financial_year', $financialYear)
            ->get(['employee_id', 'salary_structure_id']);

        if ($existing->isEmpty()) {
            return response()->json([
                'exists' => false
            ]);
        }

        // Get employee names
        $employees = EmployeeDetails::whereIn('employee_id', $existing->pluck('employee_id'))
            ->get(['employee_id', 'name']);

        return response()->json([
            'exists' => true,
            'employees' => $employees
        ]);
    }

    public function getExistingStructuresByDepartment(Request $request)
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
        
        if (!$departmentId) {
            return response()->json([
                'success' => false,
                'message' => 'Department ID is required'
            ], 422);
        }
        
        // Get all employees in the department first
        $employeeIds = $this->getCommonQuery(EmployeeDetails::class)
            ->where('department_id', $departmentId)
            ->where('institute_id', $context['institute_id'])
            ->pluck('employee_id');
        
        // Get ONLY ACTIVE salary structures
        $query = EmployeeSalaryStructure::where('institute_id', $context['institute_id'])
            ->whereIn('employee_id', $employeeIds)
            ->where('status', 'active');  // CRITICAL: Only active structures
        
        if ($financialYear) {
            $query->where('financial_year', $financialYear);
        }
        
        $structures = $query->get(['employee_id', 'salary_structure_id', 'status', 'updated_at']);
        Log::info('Existing Structures for Department ' . $structures);
        return response()->json([
            'success' => true,
            'data' => $structures
        ]);
    }

    public function editSalaryStructure($salaryStructureId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        // Get the salary structure with all relations
        $structure = EmployeeSalaryStructure::where('salary_structure_id', $salaryStructureId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$structure) {
            abort(404, 'Salary structure not found');
        }

        // Get related data
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

        // Get employee details
        $employee = $this->getCommonQuery(EmployeeDetails::class)
            ->where('employee_id', $structure->employee_id)
            ->first();

        // Get department details
        $department = null;
        if ($employee && $employee->department_id) {
            $department = $this->getCommonQuery(Departments::class)
                ->where('department_id', $employee->department_id)
                ->first();
        }

        // Get the policy data for reference
        $policy = null;
        if ($structure->payroll_policy_id) {
            $policy = \App\Models\ProvidentFundPolicy::where('payroll_policy_id', $structure->payroll_policy_id)->first();
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

    public function updateSalaryStructure(Request $request, $salaryStructureId)
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

        // Find existing structure
        $structure = EmployeeSalaryStructure::where('salary_structure_id', $salaryStructureId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$structure) {
            return response()->json([
                'success' => false,
                'message' => 'Salary structure not found'
            ], 404);
        }

        // Validate input
        $validated = $request->validate([
            'basic_salary_monthly' => 'required|numeric|min:0',
            'basic_salary_annual' => 'required|numeric|min:0',
            'fixed_ctc_annual' => 'required|numeric|min:0',
            'variable_ctc_annual' => 'nullable|numeric|min:0',
            'total_ctc_annual' => 'nullable|numeric|min:0',
        ]);

        // Calculate derived values
        $validated['variable_ctc_annual'] = $validated['variable_ctc_annual'] ?? 0;
        $validated['total_ctc_annual'] = $validated['fixed_ctc_annual'] + $validated['variable_ctc_annual'];
        $validated['monthly_fixed'] = round($validated['fixed_ctc_annual'] / 12, 2);
        $validated['monthly_variable'] = round($validated['variable_ctc_annual'] / 12, 2);
        $validated['basic_salary_percentage'] = $validated['fixed_ctc_annual'] > 0 
            ? round(($validated['basic_salary_annual'] / $validated['fixed_ctc_annual']) * 100, 2) 
            : 0;

        // Update structure
        $structure->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Salary structure updated successfully',
            'data' => $structure
        ]);
    }

    public function updateSalaryAllowances(Request $request, $salaryStructureId)
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
            'hra_selected' => 'nullable|boolean',
            'hra_value' => 'nullable|numeric|min:0',
            'hra_value_monthly' => 'nullable|numeric|min:0',
            'hra_value_annual' => 'nullable|numeric|min:0',
            'conveyance_selected' => 'nullable|boolean',
            'conveyance_value' => 'nullable|numeric|min:0',
            'conveyance_value_monthly' => 'nullable|numeric|min:0',
            'conveyance_value_annual' => 'nullable|numeric|min:0',
            'medical_selected' => 'nullable|boolean',
            'medical_value' => 'nullable|numeric|min:0',
            'medical_value_monthly' => 'nullable|numeric|min:0',
            'medical_value_annual' => 'nullable|numeric|min:0',
            'special_selected' => 'nullable|boolean',
            'special_value' => 'nullable|numeric|min:0',
            'special_value_monthly' => 'nullable|numeric|min:0',
            'special_value_annual' => 'nullable|numeric|min:0',
            'lta_selected' => 'nullable|boolean',
            'lta_value' => 'nullable|numeric|min:0',
            'lta_value_monthly' => 'nullable|numeric|min:0',
            'lta_value_annual' => 'nullable|numeric|min:0',
            'education_selected' => 'nullable|boolean',
            'education_value' => 'nullable|numeric|min:0',
            'education_value_monthly' => 'nullable|numeric|min:0',
            'education_value_annual' => 'nullable|numeric|min:0',
            'custom_allowances' => 'nullable|array',
        ]);

        $allowances = SalaryStructureAllowances::updateOrCreate(
            [
                'salary_structure_id' => $salaryStructureId,
                'institute_id' => $context['institute_id']
            ],
            array_merge($validated, [
                'institute_id' => $context['institute_id'],
                'salary_structure_id' => $salaryStructureId
            ])
        );

        return response()->json([
            'success' => true,
            'message' => 'Allowances updated successfully',
            'data' => $allowances
        ]);
    }

    public function updateSalaryDeductions(Request $request, $salaryStructureId)
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
            'pt_selected' => 'nullable|boolean',
            'pt_value' => 'nullable|numeric|min:0',
            'pt_value_monthly' => 'nullable|numeric|min:0',
            'pt_value_annual' => 'nullable|numeric|min:0',
            'lst_selected' => 'nullable|boolean',
            'lst_value' => 'nullable|numeric|min:0',
            'lst_value_monthly' => 'nullable|numeric|min:0',
            'lst_value_annual' => 'nullable|numeric|min:0',
            'tds_selected' => 'nullable|boolean',
            'tds_value' => 'nullable|numeric|min:0',
            'tds_value_monthly' => 'nullable|numeric|min:0',
            'tds_value_annual' => 'nullable|numeric|min:0',
            'insurance_selected' => 'nullable|boolean',
            'insurance_value' => 'nullable|numeric|min:0',
            'insurance_value_monthly' => 'nullable|numeric|min:0',
            'insurance_value_annual' => 'nullable|numeric|min:0',
            'advance_selected' => 'nullable|boolean',
            'advance_value' => 'nullable|numeric|min:0',
            'advance_value_monthly' => 'nullable|numeric|min:0',
            'advance_value_annual' => 'nullable|numeric|min:0',
            'pf_employee_monthly' => 'nullable|numeric|min:0',
            'pf_employee_annual' => 'nullable|numeric|min:0',
            'esi_employee_monthly' => 'nullable|numeric|min:0',
            'esi_employee_annual' => 'nullable|numeric|min:0',
            'nps_employee_monthly' => 'nullable|numeric|min:0',
            'nps_employee_annual' => 'nullable|numeric|min:0',
            'employer_pf_monthly' => 'nullable|numeric|min:0',
            'employer_pf_annual' => 'nullable|numeric|min:0',
            'employer_esi_monthly' => 'nullable|numeric|min:0',
            'employer_esi_annual' => 'nullable|numeric|min:0',
            'employer_nps_monthly' => 'nullable|numeric|min:0',
            'employer_nps_annual' => 'nullable|numeric|min:0',
        ]);

        $deductions = SalaryStructureDeduction::updateOrCreate(
            [
                'salary_structure_id' => $salaryStructureId,
                'institute_id' => $context['institute_id']
            ],
            array_merge($validated, [
                'institute_id' => $context['institute_id'],
                'salary_structure_id' => $salaryStructureId
            ])
        );

        return response()->json([
            'success' => true,
            'message' => 'Deductions updated successfully',
            'data' => $deductions
        ]);
    }

    public function updateSalaryPreview(Request $request, $salaryStructureId)
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
            'basic_salary_monthly' => 'nullable|numeric|min:0',
            'basic_salary_annual' => 'nullable|numeric|min:0',
            'total_earnings_monthly' => 'nullable|numeric|min:0',
            'total_earnings_annual' => 'nullable|numeric|min:0',
            'total_deductions_monthly' => 'nullable|numeric|min:0',
            'total_deductions_annual' => 'nullable|numeric|min:0',
            'gross_salary_monthly' => 'nullable|numeric|min:0',
            'gross_salary_annual' => 'nullable|numeric|min:0',
            'net_salary_monthly' => 'nullable|numeric|min:0',
            'net_salary_annual' => 'nullable|numeric|min:0',
            'total_cost_monthly' => 'nullable|numeric|min:0',
            'total_cost_annual' => 'nullable|numeric|min:0',
            'employer_pf_monthly' => 'nullable|numeric|min:0',
            'employer_pf_annual' => 'nullable|numeric|min:0',
            'employer_esi_monthly' => 'nullable|numeric|min:0',
            'employer_esi_annual' => 'nullable|numeric|min:0',
            'employer_nps_monthly' => 'nullable|numeric|min:0',
            'employer_nps_annual' => 'nullable|numeric|min:0',
        ]);

        $preview = SalaryPreview::updateOrCreate(
            [
                'salary_structure_id' => $salaryStructureId,
                'institute_id' => $context['institute_id']
            ],
            array_merge($validated, [
                'institute_id' => $context['institute_id'],
                'salary_structure_id' => $salaryStructureId
            ])
        );

        return response()->json([
            'success' => true,
            'message' => 'Salary preview updated successfully',
            'data' => $preview
        ]);
    }

    public function getPoliciesByDepartment(Request $request)
    {
        $financialYear = $request->input('financial_year');
        $departmentId = $request->input('department_id');
        
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        
        // Get department policy
        $departmentPolicy = PayrollPolicy::where('financial_year', $financialYear)
            ->where('department_id', $departmentId)
            ->where('policy_type', 'department')
            ->where('institute_id', $context['institute_id'])
            ->first();
        
        // Get employee policies for this department
        $employeePolicies = PayrollPolicy::where('financial_year', $financialYear)
            ->where('department_id', $departmentId)
            ->where('policy_type', 'employee')
            ->where('institute_id', $context['institute_id'])
            ->with(['employee'])
            ->get();
        
        // Get ACTIVE existing salary structures for these employees
        $employeeIds = $employeePolicies->pluck('employee_id')->toArray();
        
        $existingStructures = [];
        if (!empty($employeeIds)) {
            $existingStructures = EmployeeSalaryStructure::whereIn('employee_id', $employeeIds)
                ->where('financial_year', $financialYear)
                ->where('institute_id', $context['institute_id'])
                ->where(function($query) {
                    $query->where('status', 'active')
                        ->orWhereNull('status'); // Include NULL as active for backward compatibility
                })
                ->get()
                ->keyBy('employee_id');
        }
        
        $formattedEmployeePolicies = [];
        foreach ($employeePolicies as $policy) {
            $existingStructure = $existingStructures->get($policy->employee_id);
            
            $formattedEmployeePolicies[] = [
                'payroll_policy_id' => $policy->payroll_policy_id,
                'employee_id' => $policy->employee_id,
                'employee_name' => $policy->employee->name ?? 'Unknown',
                'employee_code' => $policy->employee->employee_code ?? '',
                'financial_year' => $policy->financial_year,
                'has_existing_structure' => !is_null($existingStructure),
                'existing_structure_id' => $existingStructure ? $existingStructure->salary_structure_id : null,
                'existing_structure_status' => $existingStructure ? ($existingStructure->status ?? 'active') : null
            ];
        }
        Log::info('Existing Structures for Department ' . $formattedEmployeePolicies);
        return response()->json([
            'success' => true,
            'data' => [
                'department_policy' => $departmentPolicy,
                'employee_policies' => $formattedEmployeePolicies
            ]
        ]);
    }


}


