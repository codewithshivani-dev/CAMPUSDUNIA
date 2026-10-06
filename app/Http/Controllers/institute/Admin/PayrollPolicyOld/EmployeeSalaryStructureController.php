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
use Illuminate\Support\Facades\Log;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
class EmployeeSalaryStructureController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;
    public function storesalarystructure(Request $request)
    {
        // Merge raw JSON payload
        $request->merge(json_decode($request->getContent(), true));

        // ✅ Get institute context
        $context = $this->getInstituteBranchContext();

        // ✅ Check institute access
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        // Validation
        $validated = $request->validate([
            'payroll_policy_id' => 'required|string|max:50',
            'financial_year' => 'required|string|max:20',
            'department_category_id' => 'nullable|string|max:50',
            'department_id' => 'nullable|string|max:50',
            'designation_id' => 'nullable|string|max:50',
            'employee_id' => 'nullable|string|max:50',

            'fixed_ctc_annual' => 'required|numeric|min:0',
            'variable_ctc_annual' => 'nullable|numeric|min:0',
            'basic_salary_percentage' => 'required|numeric|min:0|max:100'
        ]);

        //    Calculations

        $validated['variable_ctc_annual'] =
            $validated['variable_ctc_annual'] ?? 0;

        $validated['total_ctc_annual'] =
            $validated['fixed_ctc_annual'] + $validated['variable_ctc_annual'];

        $validated['monthly_fixed'] =
            round($validated['fixed_ctc_annual'] / 12, 2);

        $validated['monthly_variable'] =
            round($validated['variable_ctc_annual'] / 12, 2);

        $validated['basic_salary_monthly'] =
            round(($validated['monthly_fixed'] * $validated['basic_salary_percentage']) / 100, 2);

        $validated['basic_salary_annual'] =
            round($validated['basic_salary_monthly'] * 12, 2);

        // ✅ Generate salary structure ID
        $validated['salary_structure_id'] =
            'SAL' . strtoupper(substr(md5(uniqid()), 0, 8));

        // ✅ Attach institute & branch
        $validated['institute_id'] = $context['institute_id'];
        $validated['branch_id'] = $context['is_branch_admin']
            ? $context['branch_id']
            : null;

        // Save
        $salaryStructure = EmployeeSalaryStructure::create($validated);

        return response()->json([
            'success' => true,
            'data' => [
                'salary_structure_id' => $salaryStructure->salary_structure_id
            ]
        ], 201);
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

    // SalaryPreview;

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
            /* ===============================
            | Cost
            =============================== */
            'total_cost_monthly' => 'nullable|numeric|min:0',
            'total_cost_annual' => 'nullable|numeric|min:0',
            /* ===============================
            | Additional JSON Field
            =============================== */
            'additional_details' => 'nullable|array',
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


}

