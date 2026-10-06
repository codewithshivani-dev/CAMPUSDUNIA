<?php

namespace App\Http\Controllers\institute\Admin\PromoteEmployee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeDetails;
use App\Models\Departments;
use App\Models\DepartmentCategory;
use App\Models\Designations;
use App\Models\EmployeeProbationLog;
use App\Models\EmployeeSalaryStructure;
use App\Models\InstituteBasicDetails;
use App\Traits\InstituteBranchAccess;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeePromotionController extends Controller
{
    use InstituteBranchAccess;

    /**
     * Display list of all employees with promotion actions.
     */
    public function index(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        $merchantId = auth()->user()->institute_id;

        // Get filter data for dropdowns
        $categories = $this->getCommonQuery(DepartmentCategory::class)
            ->withCount('departments')
            ->orderBy('category_name')
            ->get();

        $departments = $this->getCommonQuery(Departments::class)
            ->with('category')
            ->orderBy('department')
            ->get();

        $designations = $this->getCommonQuery(Designations::class)
            ->where('status', 'active')
            ->orderBy('designations')
            ->get();

        // Build query for all employees
        $query = EmployeeDetails::select(
            'employee_details.*',
            'departments.department as department_name'
        )
        ->leftJoin('departments', 'employee_details.department_id', '=', 'departments.department_id')
        ->where('employee_details.institute_id', $merchantId);

        // Apply filters
        if ($request->filled('department_category_id')) {
            $query->where('employee_details.department_category_id', $request->department_category_id);
        }

        if ($request->filled('department_id')) {
            $query->where('employee_details.department_id', $request->department_id);
        }

        if ($request->filled('designation_id')) {
            $query->where('employee_details.designation_id', $request->designation_id);
        }

        if ($request->filled('employee_code')) {
            $query->where('employee_details.employee_code', 'LIKE', '%' . $request->employee_code . '%');
        }

        if ($request->filled('name')) {
            $query->where('employee_details.name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('employee_details.name', 'LIKE', '%' . $search . '%')
                ->orWhere('employee_details.employee_code', 'LIKE', '%' . $search . '%')
                ->orWhere('employee_details.email', 'LIKE', '%' . $search . '%')
                ->orWhere('employee_details.designation', 'LIKE', '%' . $search . '%')
                ->orWhere('departments.department', 'LIKE', '%' . $search . '%');
            });
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'name');
        $sortOrder = $request->input('sort_order', 'asc');

        $allowedSorts = ['name', 'employee_code', 'department', 'designation', 'employment_type', 'doj'];
        if (in_array($sortBy, $allowedSorts)) {
            if ($sortBy === 'department') {
                $query->orderBy('departments.department', $sortOrder);
            } else {
                $query->orderBy('employee_details.' . $sortBy, $sortOrder);
            }
        } else {
            $query->orderBy('employee_details.name', 'asc');
        }

        // Paginate results
        $employees = $query->paginate(15);

        // Get promotion counts and salary structure status for each employee
        foreach ($employees as $employee) {
            $employee->promotion_count = EmployeeProbationLog::where('employee_id', $employee->id)->count();
            $employee->latest_promotion = EmployeeProbationLog::where('employee_id', $employee->id)
                ->orderBy('promotion_date', 'desc')
                ->first();
            
            // FIXED: Get the salary structure record with ID
            $salaryStructure = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($salaryStructure) {
                $employee->salary_structure_id = $salaryStructure->salary_structure_id;
                $employee->has_active_salary_structure = $salaryStructure->is_active == true;
                $employee->has_inactive_salary_structure = $salaryStructure->is_active == false;
            } else {
                $employee->salary_structure_id = null;
                $employee->has_active_salary_structure = false;
                $employee->has_inactive_salary_structure = false;
            }
        }

        return view('instituteAdmin.EmployeePromotion.index', [
            'employees' => $employees,
            'categories' => $categories,
            'departments' => $departments,
            'designations' => $designations,
            'context' => $context,
            'filters' => $request->all(),
        ]);
    }

    /**
     * Show the edit/update form for an employee.
     */
    public function edit($id)
    {
        $merchantId = auth()->user()->institute_id;

        $employee = EmployeeDetails::where('id', $id)
            ->where('institute_id', $merchantId)
            ->firstOrFail();

        // Get available designations
        $designations = $this->getCommonQuery(Designations::class)
            ->where('status', 'active')
            ->orderBy('designations')
            ->get();

        // Get current salary structure
        $currentSalaryStructure = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
            ->where('is_active', true)
            ->first();

        // Check if employee has inactive salary structure (awaiting new one)
        $hasInactiveSalaryStructure = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
            ->where('is_active', false)
            ->exists();

        // Get promotion history
        $promotionHistory = EmployeeProbationLog::where('employee_id', $employee->id)
            ->orderBy('promotion_date', 'desc')
            ->limit(10)
            ->get();

        $context = $this->getInstituteBranchContext();

        return view('instituteAdmin.EmployeePromotion.update', [
            'employee' => $employee,
            'designations' => $designations,
            'currentSalaryStructure' => $currentSalaryStructure,
            'hasInactiveSalaryStructure' => $hasInactiveSalaryStructure,
            'promotionHistory' => $promotionHistory,
            'context' => $context,
        ]);
    }

    /**
     * Show employee promotion history.
     */
    public function history($id)
    {
        $merchantId = auth()->user()->institute_id;

        $employee = EmployeeDetails::where('id', $id)
            ->where('institute_id', $merchantId)
            ->firstOrFail();

        // Get complete promotion history
        $promotionHistory = EmployeeProbationLog::where('employee_id', $employee->id)
            ->orderBy('promotion_date', 'desc')
            ->paginate(20);

        // Get statistics
        $stats = [
            'total' => $promotionHistory->total(),
            'by_type' => EmployeeProbationLog::where('employee_id', $employee->id)
                ->select('promotion_type', DB::raw('count(*) as count'))
                ->groupBy('promotion_type')
                ->get(),
            'latest' => EmployeeProbationLog::where('employee_id', $employee->id)
                ->orderBy('promotion_date', 'desc')
                ->first(),
        ];

        $context = $this->getInstituteBranchContext();

        return view('instituteAdmin.EmployeePromotion.history', [
            'employee' => $employee,
            'promotionHistory' => $promotionHistory,
            'stats' => $stats,
            'context' => $context,
        ]);
    }

    /**
     * Update employee employment type with double confirmation.
     */
    public function updateEmploymentType(Request $request, $id)
    {
        // try {
            $request->validate([
                'employment_type' => 'required|in:Full-time,Part-time,Contract-based,Probation-Period',
                'probation_days' => 'nullable|integer|min:1|max:365',
                'doj' => 'nullable|date',
                'confirmation_token' => 'required|string',
            ]);

            // Verify confirmation token
            if (!$this->verifyConfirmationToken($request->confirmation_token)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Confirmation required. Please confirm the action again.',
                ], 400);
            }

            DB::beginTransaction();

            $merchantId = auth()->user()->institute_id;
            $employee = EmployeeDetails::where('id', $id)
                ->where('institute_id', $merchantId)
                ->firstOrFail();

            // Get salary structure BEFORE any changes
            $salaryStructureBefore = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
                ->where('is_active', true)
                ->first();

            $oldEmploymentType = $employee->employment_type;
            $newEmploymentType = $request->employment_type;

            // Always ask about salary structure - default to keeping it
            $keepSalaryStructure = $request->input('keep_salary_structure', true);
            $inactivatedSalaryStructureId = null;
            $salaryStructureAfter = null;

            // Handle salary structure inactivation
            if (!$keepSalaryStructure && $salaryStructureBefore) {
                $inactivatedSalaryStructureId = $salaryStructureBefore->salary_structure_id;
                $salaryStructureBefore->is_active = false;
                $salaryStructureBefore->status = 'inactive';
                $salaryStructureBefore->save();
                // Store the inactivated structure details for logging
                $salaryStructureAfter = $salaryStructureBefore->fresh();
            }

            // Update employee
            $employee->employment_type = $newEmploymentType;
            
            // If changing to probation, handle probation days
            if ($newEmploymentType === 'Probation-Period') {
                $employee->probation_days = $request->probation_days ?? 90;
                if ($request->filled('doj')) {
                    $employee->doj = $request->doj;
                }
            } elseif ($oldEmploymentType === 'Probation-Period') {
                $employee->probation_days = null;
            }

            $employee->save();

            // If salary structure was kept active, get the after state
            if ($keepSalaryStructure && $salaryStructureBefore) {
                $salaryStructureAfter = $salaryStructureBefore->fresh();
            }

            // Create promotion log with proper salary data
            $this->createPromotionLog($employee, [
                'old_value' => $oldEmploymentType,
                'new_value' => $newEmploymentType,
                'type' => 'Employment Type',
                'promotion_type' => 'employment_type',
                'salary_structure_kept' => $keepSalaryStructure,
                'inactivated_salary_structure_id' => $inactivatedSalaryStructureId,
                'old_employment_type' => $oldEmploymentType,
                'new_employment_type' => $newEmploymentType,
                'probation_days' => $employee->probation_days,
                'salary_structure_before' => $salaryStructureBefore ? $salaryStructureBefore->toArray() : null,
                'salary_structure_after' => $salaryStructureAfter ? $salaryStructureAfter->toArray() : null,
            ]);

            DB::commit();
            $salaryStructureUrl = url('/institute/admin/ctc-salary-configuration/salary-structure/create');
            return response()->json([
                'success' => true,
                'message' => 'Employment type updated successfully.',
                'awaiting_salary_structure' => !$keepSalaryStructure,
                'has_active_salary_structure' => $keepSalaryStructure,
                'salary_structure_url' => $salaryStructureUrl,
            ]);

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     \Log::error('Employment type update error: ' . $e->getMessage());
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to update employment type: ' . $e->getMessage(),
        //     ], 500);
        // }
    }

     /**
     * Update employee designation with double confirmation.
     */
    public function updateDesignation(Request $request, $id)
    {
        // try {
            $request->validate([
                'designation_id' => 'required|exists:designations,designation_id',
                'confirmation_token' => 'required|string',
            ]);

            // Verify confirmation token
            if (!$this->verifyConfirmationToken($request->confirmation_token)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Confirmation required. Please confirm the action again.',
                ], 400);
            }

            DB::beginTransaction();

            $merchantId = auth()->user()->institute_id;
            $employee = EmployeeDetails::where('id', $id)
                ->where('institute_id', $merchantId)
                ->firstOrFail();

            // Get salary structure BEFORE any changes
            $salaryStructureBefore = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
                ->where('is_active', true)
                ->first();

            $newDesignation = Designations::where('designation_id', $request->designation_id)->firstOrFail();
            $oldDesignation = $employee->designation;

            // Always ask about salary structure - default to keeping it
            $keepSalaryStructure = $request->input('keep_salary_structure', true);
            $inactivatedSalaryStructureId = null;
            $salaryStructureAfter = null;

            // Handle salary structure inactivation
            if (!$keepSalaryStructure && $salaryStructureBefore) {
                $inactivatedSalaryStructureId = $salaryStructureBefore->salary_structure_id;
                $salaryStructureBefore->is_active = false;
                $salaryStructureBefore->status = 'inactive';
                $salaryStructureBefore->save();
                $salaryStructureAfter = $salaryStructureBefore->fresh();
            }

            // Update employee
            $employee->designation_id = $request->designation_id;
            $employee->designation = $newDesignation->designations;
            $employee->save();

            // If salary structure was kept active, get the after state
            if ($keepSalaryStructure && $salaryStructureBefore) {
                $salaryStructureAfter = $salaryStructureBefore->fresh();
            }

            // Create promotion log with proper salary data
            $this->createPromotionLog($employee, [
                'old_value' => $oldDesignation,
                'new_value' => $newDesignation->designations,
                'type' => 'Designation',
                'promotion_type' => 'designation',
                'salary_structure_kept' => $keepSalaryStructure,
                'inactivated_salary_structure_id' => $inactivatedSalaryStructureId,
                'old_designation' => $oldDesignation,
                'new_designation' => $newDesignation->designations,
                'old_designation_id' => $employee->designation_id,
                'new_designation_id' => $request->designation_id,
                'salary_structure_before' => $salaryStructureBefore ? $salaryStructureBefore->toArray() : null,
                'salary_structure_after' => $salaryStructureAfter ? $salaryStructureAfter->toArray() : null,
            ]);

            DB::commit();
            $salaryStructureUrl = url('/institute/admin/ctc-salary-configuration/salary-structure/create');
            return response()->json([
                'success' => true,
                'message' => 'Designation updated successfully.',
                'awaiting_salary_structure' => !$keepSalaryStructure,
                'has_active_salary_structure' => $keepSalaryStructure,
                'salary_structure_url' => $salaryStructureUrl,
            ]);

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     \Log::error('Designation update error: ' . $e->getMessage());
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to update designation: ' . $e->getMessage(),
        //     ], 500);
        // }
    }

    /**
     * Inactivate salary structure (no new structure assigned here).
     */
    public function inactivateSalaryStructure(Request $request, $id)
    {
        // try {
            $request->validate([
                'confirmation_token' => 'required|string',
            ]);

            // Verify confirmation token
            if (!$this->verifyConfirmationToken($request->confirmation_token)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Confirmation required. Please confirm the action again.',
                ], 400);
            }

            DB::beginTransaction();

            $merchantId = auth()->user()->institute_id;
            $employee = EmployeeDetails::where('id', $id)
                ->where('institute_id', $merchantId)
                ->firstOrFail();

            // Get salary structure BEFORE inactivation
            $salaryStructureBefore = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
                ->where('is_active', true)
                ->first();

            if (!$salaryStructureBefore) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active salary structure found for this employee.',
                ], 404);
            }

            $oldSalaryStructureId = $salaryStructureBefore->salary_structure_id;
            $oldBasicSalary = $salaryStructureBefore->basic_salary_monthly;
            $oldTotalCTC = $salaryStructureBefore->total_ctc_annual;

            // Inactivate salary structure
            $salaryStructureBefore->is_active = false;
            $salaryStructureBefore->status = 'inactive';
            $salaryStructureBefore->save();

            // Get the inactivated structure for logging
            $salaryStructureAfter = $salaryStructureBefore->fresh();

            // Create promotion log for salary structure inactivation
            $this->createPromotionLog($employee, [
                'old_value' => 'Salary Structure #' . $oldSalaryStructureId,
                'new_value' => 'Inactivated (Awaiting New Structure)',
                'type' => 'Salary Structure',
                'promotion_type' => 'salary_structure_inactivation',
                'salary_structure_kept' => false,
                'inactivated_salary_structure_id' => $oldSalaryStructureId,
                'old_salary_structure_id' => $oldSalaryStructureId,
                'old_basic_salary_monthly' => $oldBasicSalary,
                'old_total_ctc_annual' => $oldTotalCTC,
                'inactivated_at' => Carbon::now()->toDateTimeString(),
                'awaiting_new_structure' => true,
                'salary_structure_before' => $salaryStructureBefore->toArray(),
                'salary_structure_after' => $salaryStructureAfter->toArray(),
            ]);

            DB::commit();
            $salaryStructureUrl = url('/institute/admin/ctc-salary-configuration/salary-structure/create');
            return response()->json([
                'success' => true,
                'message' => 'Salary structure has been inactivated successfully.',
                'awaiting_salary_structure' => true,
                'salary_structure_url' => $salaryStructureUrl,
                'inactivated_structure_id' => $oldSalaryStructureId,
            ]);

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     \Log::error('Salary structure inactivation error: ' . $e->getMessage());
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to inactivate salary structure: ' . $e->getMessage(),
        //     ], 500);
        // }
    }

    /**
     * Show salary structure assignment form.
     */
    public function assignSalaryStructure($id)
    {
        $salaryStructureUrl = url('/institute/admin/ctc-salary-configuration/salary-structure/create');
        $merchantId = auth()->user()->institute_id;
        $employee = EmployeeDetails::where('id', $id)
            ->where('institute_id', $merchantId)
            ->firstOrFail();

        // Check if employee already has active salary structure
        $hasActiveSalaryStructure = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
            ->where('is_active', true)
            ->exists();

        // Get inactive salary structures
        $inactiveStructures = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
            ->where('is_active', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $context = $this->getInstituteBranchContext();

        return view('instituteAdmin.EmployeePromotion.assign_salary_structure', [
            'employee' => $employee,
            'hasActiveSalaryStructure' => $hasActiveSalaryStructure,
            'inactiveStructures' => $inactiveStructures,
            'context' => $context,
            'salaryStructureUrl' => $salaryStructureUrl,
        ]);
    }

    
    /**
     * Create promotion log entry with unique promotion ID and detailed salary info.
     */
    private function createPromotionLog($employee, $data)
    {
        // Generate unique promotion ID
        $promotionId = $this->generatePromotionId($employee);
        
        // Get promotion type from data
        $promotionType = $data['promotion_type'] ?? 'other';
        
        // Prepare additional data with all salary structure details
        $additionalData = [
            'promotion_id' => $promotionId,
            'old_value' => $data['old_value'] ?? null,
            'new_value' => $data['new_value'] ?? null,
            'type' => $data['type'] ?? null,
            'promotion_type' => $promotionType,
            'performed_by' => auth()->user()->name ?? 'System',
            'performed_at' => Carbon::now()->toDateTimeString(),
            'salary_structure_kept' => $data['salary_structure_kept'] ?? null,
            'inactivated_salary_structure_id' => $data['inactivated_salary_structure_id'] ?? null,
        ];

        // Add employment type specific data
        if (isset($data['old_employment_type'])) {
            $additionalData['old_employment_type'] = $data['old_employment_type'];
            $additionalData['new_employment_type'] = $data['new_employment_type'];
        }

        // Add designation specific data
        if (isset($data['old_designation'])) {
            $additionalData['old_designation'] = $data['old_designation'];
            $additionalData['new_designation'] = $data['new_designation'];
        }

        // Add probation days if set
        if (isset($data['probation_days'])) {
            $additionalData['probation_days'] = $data['probation_days'];
        }

        // Add salary structure before (with complete details)
        if (isset($data['salary_structure_before']) && $data['salary_structure_before']) {
            $additionalData['salary_structure_before'] = [
                'salary_structure_id' => $data['salary_structure_before']['salary_structure_id'] ?? null,
                'basic_salary_monthly' => $data['salary_structure_before']['basic_salary_monthly'] ?? null,
                'basic_salary_annual' => $data['salary_structure_before']['basic_salary_annual'] ?? null,
                'fixed_ctc_annual' => $data['salary_structure_before']['fixed_ctc_annual'] ?? null,
                'variable_ctc_annual' => $data['salary_structure_before']['variable_ctc_annual'] ?? null,
                'total_ctc_annual' => $data['salary_structure_before']['total_ctc_annual'] ?? null,
                'status' => $data['salary_structure_before']['status'] ?? null,
                'is_active' => $data['salary_structure_before']['is_active'] ?? null,
                'effective_from' => $data['salary_structure_before']['effective_from'] ?? null,
            ];
        }

        // Add salary structure after (with complete details)
        if (isset($data['salary_structure_after']) && $data['salary_structure_after']) {
            $additionalData['salary_structure_after'] = [
                'salary_structure_id' => $data['salary_structure_after']['salary_structure_id'] ?? null,
                'basic_salary_monthly' => $data['salary_structure_after']['basic_salary_monthly'] ?? null,
                'basic_salary_annual' => $data['salary_structure_after']['basic_salary_annual'] ?? null,
                'fixed_ctc_annual' => $data['salary_structure_after']['fixed_ctc_annual'] ?? null,
                'variable_ctc_annual' => $data['salary_structure_after']['variable_ctc_annual'] ?? null,
                'total_ctc_annual' => $data['salary_structure_after']['total_ctc_annual'] ?? null,
                'status' => $data['salary_structure_after']['status'] ?? null,
                'is_active' => $data['salary_structure_after']['is_active'] ?? null,
                'effective_from' => $data['salary_structure_after']['effective_from'] ?? null,
            ];
        }

        // Add inactivation specific data
        if (isset($data['old_salary_structure_id'])) {
            $additionalData['old_salary_structure_id'] = $data['old_salary_structure_id'];
            $additionalData['old_basic_salary_monthly'] = $data['old_basic_salary_monthly'] ?? null;
            $additionalData['old_total_ctc_annual'] = $data['old_total_ctc_annual'] ?? null;
            $additionalData['inactivated_at'] = $data['inactivated_at'] ?? null;
            $additionalData['awaiting_new_structure'] = $data['awaiting_new_structure'] ?? false;
        }

        // Add assignment specific data
        if (isset($data['new_salary_structure_id'])) {
            $additionalData['new_salary_structure_id'] = $data['new_salary_structure_id'];
            $additionalData['new_basic_salary_monthly'] = $data['new_basic_salary_monthly'] ?? null;
            $additionalData['new_total_ctc_annual'] = $data['new_total_ctc_annual'] ?? null;
            $additionalData['effective_from'] = $data['effective_from'] ?? null;
            $additionalData['assigned_at'] = Carbon::now()->toDateTimeString();
        }

        // ========== FIXED: Determine salary structure status ==========
        $salaryStructureKept = $data['salary_structure_kept'] ?? null;
        $hasSalaryStructureBefore = isset($data['salary_structure_before']) && $data['salary_structure_before'];
        $hasSalaryStructureAfter = isset($data['salary_structure_after']) && $data['salary_structure_after'];
        $inactivatedId = $data['inactivated_salary_structure_id'] ?? null;

        // Get the salary after array for checking
        $salaryAfter = $data['salary_structure_after'] ?? null;

        // Convert string "0" to boolean false for comparison
        $salaryStructureKeptBool = filter_var($salaryStructureKept, FILTER_VALIDATE_BOOLEAN);

        // Check if salary structure was inactivated (either by direct action or during promotion)
        $wasInactivated = false;

        // Case 1: Direct salary structure inactivation
        if ($promotionType === 'salary_structure_inactivation') {
            $wasInactivated = true;
            $additionalData['salary_structure_status'] = 'inactivated';
            $additionalData['salary_structure_status_message'] = 'Salary structure was inactivated';
            $additionalData['needs_new_salary_structure'] = true;
        }
        // Case 2: Salary structure was inactivated during promotion (employment type or designation change)
        elseif ($salaryStructureKeptBool === false && $inactivatedId) {
            $wasInactivated = true;
            $additionalData['salary_structure_status'] = 'inactivated';
            $additionalData['salary_structure_status_message'] = 'Salary structure was inactivated during promotion. Awaiting new structure.';
            $additionalData['needs_new_salary_structure'] = true;
        }
        // Case 3: Check if the salary structure after is inactive (fallback detection)
        elseif ($hasSalaryStructureAfter && isset($salaryAfter['is_active']) && $salaryAfter['is_active'] === false) {
            $wasInactivated = true;
            $additionalData['salary_structure_status'] = 'inactivated';
            $additionalData['salary_structure_status_message'] = 'Salary structure was inactivated';
            $additionalData['needs_new_salary_structure'] = true;
        }
        // Case 4: Salary structure was kept active
        elseif ($salaryStructureKeptBool === true && $hasSalaryStructureBefore) {
            $additionalData['salary_structure_status'] = 'kept_active';
            $additionalData['salary_structure_status_message'] = 'Salary structure remained active during promotion';
            $additionalData['needs_new_salary_structure'] = false;
        }
        // Case 5: Salary structure was assigned
        elseif ($promotionType === 'salary_structure_assignment') {
            $additionalData['salary_structure_status'] = 'assigned';
            $additionalData['salary_structure_status_message'] = 'New salary structure was assigned';
            $additionalData['needs_new_salary_structure'] = false;
        }
        // Case 6: No salary structure change or no salary structure exists
        else {
            $additionalData['salary_structure_status'] = 'not_applicable';
            $additionalData['salary_structure_status_message'] = 'No salary structure change';
            $additionalData['needs_new_salary_structure'] = false;
        }

        return EmployeeProbationLog::create([
            'employee_id' => $employee->id,
            'promotion_id' => $promotionId,
            'employee_code' => $employee->employee_code,
            'employee_name' => $employee->name,
            'doj' => $employee->doj,
            'promotion_date' => Carbon::now(),
            'promoted_by' => auth()->user()->name ?? 'System',
            'promoted_by_user_id' => auth()->user()->id ?? null,
            'promotion_type' => $promotionType,
            'department_before' => $employee->department_name ?? null,
            'designation_before' => $data['old_designation'] ?? $employee->designation ?? null,
            'salary_structure_kept' => $data['salary_structure_kept'] ?? null,
            'inactivated_salary_structure_id' => $data['inactivated_salary_structure_id'] ?? null,
            'additional_data' => $additionalData,
        ]);
    }

    private function generatePromotionId($employee)
    {
        $count = EmployeeProbationLog::where('employee_id', $employee->id)->count() + 1;

        return sprintf(
            'PR%s-%s-%02d',
            now()->format('ymd'),
            strtoupper(substr($employee->employee_code, -4)),
            $count
        );
    }


    /**
     * Generate and verify confirmation token for double confirmation.
     */
    private function generateConfirmationToken()
    {
        return bin2hex(random_bytes(32)) . '_' . time();
    }

    private function verifyConfirmationToken($token)
    {
        // Split token and time
        $parts = explode('_', $token);
        if (count($parts) !== 2) {
            return false;
        }

        // Check if token is not older than 5 minutes
        $timestamp = (int) $parts[1];
        if (time() - $timestamp > 300) { // 5 minutes
            return false;
        }

        return true;
    }


    /**
     * Get confirmation token for double confirmation.
     */
    public function getConfirmationToken()
    {
        return response()->json([
            'success' => true,
            'confirmation_token' => $this->generateConfirmationToken(),
        ]);
    }
}