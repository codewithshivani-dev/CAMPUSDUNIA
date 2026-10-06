<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeDetails;
use App\Models\Departments;
use App\Models\DepartmentCategory;
use App\Models\Designations;
use App\Models\EmployeeProbationLog;
use App\Models\EmployeeSalaryStructure;
use App\Traits\InstituteBranchAccess;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProbationEmployeeController extends Controller
{
    use InstituteBranchAccess;

    /**
     * Display a listing of employees with probation history.
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
        
        // Build query for employees with probation history
        $query = EmployeeDetails::select(
            'employee_details.*',
            'departments.department as department_name'
        )
        ->leftJoin('departments', 'employee_details.department_id', '=', 'departments.department_id')
        ->where('employee_details.institute_id', $merchantId)
        ->where(function($q) {
            $q->where('employee_details.employment_type', 'Probation-Period')
              ->orWhereExists(function($sub) {
                  $sub->select(DB::raw(1))
                      ->from('employee_probation_logs')
                      ->whereColumn('employee_probation_logs.employee_id', 'employee_details.id')
                      ->where(function($logQ) {
                          $logQ->where('employee_probation_logs.employment_type_before', 'Probation-Period')
                               ->orWhere('employee_probation_logs.employment_type_after', 'Full-time');
                      });
              });
        });
        
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
        
        // Probation status filter
        if ($request->filled('probation_status')) {
            switch ($request->probation_status) {
                case 'active':
                    $query->where('employee_details.employment_type', 'Probation-Period')
                          ->whereRaw('DATE_ADD(doj, INTERVAL probation_days DAY) > CURDATE()');
                    break;
                case 'ending_soon':
                    $query->where('employee_details.employment_type', 'Probation-Period')
                          ->whereRaw('DATE_ADD(doj, INTERVAL probation_days DAY) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)');
                    break;
                case 'overdue':
                    $query->where('employee_details.employment_type', 'Probation-Period')
                          ->whereRaw('DATE_ADD(doj, INTERVAL probation_days DAY) < CURDATE()');
                    break;
                case 'today':
                    $query->where('employee_details.employment_type', 'Probation-Period')
                          ->whereRaw('DATE_ADD(doj, INTERVAL probation_days DAY) = CURDATE()');
                    break;
                case 'completed':
                    $query->where('employee_details.employment_type', '!=', 'Probation-Period')
                          ->whereExists(function($sub) {
                              $sub->select(DB::raw(1))
                                  ->from('employee_probation_logs')
                                  ->whereColumn('employee_probation_logs.employee_id', 'employee_details.id')
                                  ->where('employee_probation_logs.employment_type_before', 'Probation-Period');
                          });
                    break;
            }
        }
        
        // Sorting
        $sortBy = $request->input('sort_by', 'probation_end_date');
        $sortOrder = $request->input('sort_order', 'asc');
        
        if ($sortBy === 'probation_end_date') {
            $query->orderByRaw('DATE_ADD(doj, INTERVAL probation_days DAY) ' . $sortOrder);
        } elseif ($sortBy === 'name') {
            $query->orderBy('employee_details.name', $sortOrder);
        } elseif ($sortBy === 'employee_code') {
            $query->orderBy('employee_details.employee_code', $sortOrder);
        } elseif ($sortBy === 'department') {
            $query->orderBy('departments.department', $sortOrder);
        } elseif ($sortBy === 'doj') {
            $query->orderBy('employee_details.doj', $sortOrder);
        } else {
            $query->orderBy('employee_details.created_at', 'desc');
        }
        
        // Paginate results
        $employees = $query->paginate(15);
        
        // Calculate additional data for each employee
        foreach ($employees as $employee) {
            $employee->probation_status = $employee->getProbationStatusAttribute();
            $employee->probation_days_delta = $employee->getProbationDaysDeltaAttribute();
            $employee->probation_end_date = $employee->getProbationEndDateAttribute();
            
            // Get promotion count and latest promotion
            $employee->promotion_count = EmployeeProbationLog::where('employee_id', $employee->id)
                ->where('employment_type_before', 'Probation-Period')
                ->count();
            
            $employee->latest_promotion = EmployeeProbationLog::where('employee_id', $employee->id)
                ->where('employment_type_before', 'Probation-Period')
                ->orderBy('promotion_date', 'desc')
                ->first();

            // Get salary structure status
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
        
        return view('instituteAdmin.EmployeeFiles.ProbationEmployees', [
            'employees' => $employees,
            'categories' => $categories,
            'departments' => $departments,
            'designations' => $designations,
            'context' => $context,
            'filters' => $request->all(),
        ]);
    }

    /**
     * Promote a single probation employee to full-time.
     */
    public function promote(Request $request, $id)
    {
        try {
            $request->validate([
                'keep_salary_structure' => 'nullable|boolean',
            ]);
            
            DB::beginTransaction();
            
            $employee = EmployeeDetails::where('id', $id)->first();
            
            if (!$employee) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Employee not found'
                ], 404);
            }
            
            if ($employee->employment_type !== 'Probation-Period') {
                return response()->json([
                    'success' => false, 
                    'message' => 'Employee is not currently on probation'
                ], 400);
            }
            
            $currentUser = auth()->user();
            
            // Get probation details before updating
            $probationStartDate = $employee->doj;
            $probationEndDate = $employee->getProbationEndDateAttribute();
            $probationDays = $employee->probation_days;
            $departmentBefore = $employee->department_name ?? $employee->department_id;
            $designationBefore = $employee->designation;
            
            $keepSalaryStructure = $request->input('keep_salary_structure', true);
            $inactivatedSalaryStructureId = null;
            $salaryStructureBefore = null;
            
            // Get current salary structure details before any changes
            $currentSalaryStructure = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
                ->where('is_active', true)
                ->first();
            
            // Store salary structure before for logging
            if ($currentSalaryStructure) {
                $salaryStructureBefore = $currentSalaryStructure->toArray();
            }
            
            // Handle salary structure - INACTIVATE if admin chose "No"
            if (!$keepSalaryStructure && $currentSalaryStructure) {
                $inactivatedSalaryStructureId = $currentSalaryStructure->salary_structure_id;
                $currentSalaryStructure->is_active = false;
                $currentSalaryStructure->status = 'inactive';
                $currentSalaryStructure->save();
            }
            
            // Update employee
            $employee->employment_type = 'Full-time';
            $employee->probation_days = null;
            $employee->promotion_date = Carbon::now();
            $employee->save();
            
            // Check if there's any salary structure after promotion
            $salaryStructureAfter = null;
            if ($keepSalaryStructure && $currentSalaryStructure) {
                // Salary structure kept active
                $salaryStructureAfter = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
                    ->where('is_active', true)
                    ->first();
                if ($salaryStructureAfter) {
                    $salaryStructureAfter = $salaryStructureAfter->toArray();
                }
            } else {
                // Check if a new structure was assigned (in case admin assigned one)
                $newStructure = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
                    ->where('is_active', true)
                    ->first();
                if ($newStructure) {
                    $salaryStructureAfter = $newStructure->toArray();
                }
            }
            
            // Create promotion log with unique promotion ID
            $this->createPromotionLog($employee, [
                'doj' => $probationStartDate,
                'probation_days' => $probationDays,
                'probation_start_date' => $probationStartDate,
                'probation_end_date' => $probationEndDate,
                'employment_type_before' => 'Probation-Period',
                'employment_type_after' => 'Full-time',
                'promotion_date' => Carbon::now(),
                'promoted_by' => $currentUser->name ?? 'System',
                'promoted_by_user_id' => $currentUser->id ?? null,
                'promotion_type' => 'employment_type_promotion',
                'department_before' => $departmentBefore,
                'designation_before' => $designationBefore,
                'salary_structure_kept' => $keepSalaryStructure,
                'inactivated_salary_structure_id' => $inactivatedSalaryStructureId,
                'salary_structure_before' => $salaryStructureBefore,
                'salary_structure_after' => $salaryStructureAfter,
            ]);
            
            DB::commit();
            
            $salaryStructureUrl = url('/institute/admin/payroll/salary-structure');
            
            return response()->json([
                'success' => true,
                'message' => $keepSalaryStructure 
                    ? 'Employee promoted to full-time successfully.'
                    : 'Employee promoted to full-time successfully. Salary structure has been inactivated.',
                'employee' => [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'employee_code' => $employee->employee_code,
                    'promotion_date' => Carbon::now()->format('d-m-Y H:i:s'),
                    'salary_structure_kept' => $keepSalaryStructure,
                    'awaiting_salary_structure' => !$keepSalaryStructure,
                    'salary_structure_url' => $salaryStructureUrl,
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Promotion error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to promote employee: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk promote multiple probation employees to full-time.
     */
    public function bulkPromote(Request $request)
    {
        try {
            $request->validate([
                'employee_ids' => 'required|array',
                'employee_ids.*' => 'exists:employee_details,id',
                'keep_salary_structure' => 'nullable|boolean',
            ]);
            
            $employeeIds = $request->employee_ids;
            
            $employees = EmployeeDetails::whereIn('id', $employeeIds)
                ->where('employment_type', 'Probation-Period')
                ->get();
            
            if ($employees->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No probation employees found to promote'
                ], 400);
            }
            
            DB::beginTransaction();
            
            $currentUser = auth()->user();
            $promotedCount = 0;
            $promotedNames = [];
            $promotionDate = Carbon::now();
            $keepSalaryStructure = $request->input('keep_salary_structure', true);
            
            foreach ($employees as $employee) {
                $probationStartDate = $employee->doj;
                $probationEndDate = $employee->getProbationEndDateAttribute();
                $probationDays = $employee->probation_days;
                $departmentBefore = $employee->department_name ?? $employee->department_id;
                $designationBefore = $employee->designation;
                $inactivatedSalaryStructureId = null;
                $salaryStructureBefore = null;
                
                // Get current salary structure
                $currentSalaryStructure = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
                    ->where('is_active', true)
                    ->first();
                
                // Store salary structure before for logging
                if ($currentSalaryStructure) {
                    $salaryStructureBefore = $currentSalaryStructure->toArray();
                }
                
                // Handle salary structure - INACTIVATE if admin chose "No"
                if (!$keepSalaryStructure && $currentSalaryStructure) {
                    $inactivatedSalaryStructureId = $currentSalaryStructure->salary_structure_id;
                    $currentSalaryStructure->is_active = false;
                    $currentSalaryStructure->status = 'inactive';
                    $currentSalaryStructure->save();
                }
                
                $employee->employment_type = 'Full-time';
                $employee->probation_days = null;
                $employee->promotion_date = $promotionDate;
                $employee->save();
                
                // Check for salary structure after
                $salaryStructureAfter = null;
                if ($keepSalaryStructure && $currentSalaryStructure) {
                    $salaryStructureAfter = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
                        ->where('is_active', true)
                        ->first();
                    if ($salaryStructureAfter) {
                        $salaryStructureAfter = $salaryStructureAfter->toArray();
                    }
                } else {
                    $newStructure = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
                        ->where('is_active', true)
                        ->first();
                    if ($newStructure) {
                        $salaryStructureAfter = $newStructure->toArray();
                    }
                }
                
                $promotedCount++;
                $promotedNames[] = $employee->name;
                
                // Create promotion log with unique promotion ID
                $this->createPromotionLog($employee, [
                    'doj' => $probationStartDate,
                    'probation_days' => $probationDays,
                    'probation_start_date' => $probationStartDate,
                    'probation_end_date' => $probationEndDate,
                    'employment_type_before' => 'Probation-Period',
                    'employment_type_after' => 'Full-time',
                    'promotion_date' => $promotionDate,
                    'promoted_by' => $currentUser->name ?? 'System',
                    'promoted_by_user_id' => $currentUser->id ?? null,
                    'promotion_type' => 'bulk_employment_type',
                    'department_before' => $departmentBefore,
                    'designation_before' => $designationBefore,
                    'salary_structure_kept' => $keepSalaryStructure,
                    'inactivated_salary_structure_id' => $inactivatedSalaryStructureId,
                    'salary_structure_before' => $salaryStructureBefore,
                    'salary_structure_after' => $salaryStructureAfter,
                    'additional_data' => [
                        'bulk_promotion_count' => count($employees),
                    ]
                ]);
            }
            
            DB::commit();
            
            $salaryStructureUrl = url('/institute/admin/payroll/salary-structure');
            
            return response()->json([
                'success' => true,
                'message' => $keepSalaryStructure
                    ? "Successfully promoted {$promotedCount} employee(s) to full-time."
                    : "Successfully promoted {$promotedCount} employee(s) to full-time. Salary structures have been inactivated.",
                'promoted' => $promotedNames,
                'count' => $promotedCount,
                'promotion_date' => $promotionDate->format('d-m-Y H:i:s'),
                'salary_structure_kept' => $keepSalaryStructure,
                'awaiting_salary_structures' => !$keepSalaryStructure,
                'salary_structure_url' => $salaryStructureUrl
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Bulk promotion error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to promote employees: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create promotion log entry with unique promotion ID and detailed salary info.
     * This matches the EmployeePromotionController implementation.
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
            'employment_type_before' => $data['employment_type_before'] ?? null,
            'employment_type_after' => $data['employment_type_after'] ?? null,
            'promotion_type' => $promotionType,
            'performed_by' => auth()->user()->name ?? 'System',
            'performed_at' => Carbon::now()->toDateTimeString(),
            'salary_structure_kept' => $data['salary_structure_kept'] ?? null,
            'inactivated_salary_structure_id' => $data['inactivated_salary_structure_id'] ?? null,
        ];

        // Add probation days if set
        if (isset($data['probation_days'])) {
            $additionalData['probation_days'] = $data['probation_days'];
        }

        // Add probation start and end dates
        if (isset($data['probation_start_date'])) {
            $additionalData['probation_start_date'] = $data['probation_start_date'];
        }
        if (isset($data['probation_end_date'])) {
            $additionalData['probation_end_date'] = $data['probation_end_date'];
        }

        // Add designation details
        if (isset($data['designation_before'])) {
            $additionalData['designation_before'] = $data['designation_before'];
        }
        if (isset($data['designation_after'])) {
            $additionalData['designation_after'] = $data['designation_after'];
        }

        // Add department details
        if (isset($data['department_before'])) {
            $additionalData['department_before'] = $data['department_before'];
        }
        if (isset($data['department_after'])) {
            $additionalData['department_after'] = $data['department_after'];
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

        // Add additional data if provided
        if (isset($data['additional_data']) && is_array($data['additional_data'])) {
            $additionalData = array_merge($additionalData, $data['additional_data']);
        }

        // Determine salary structure status
        $salaryStructureKept = $data['salary_structure_kept'] ?? null;
        $hasSalaryStructureBefore = isset($data['salary_structure_before']) && $data['salary_structure_before'];
        $inactivatedId = $data['inactivated_salary_structure_id'] ?? null;

        // Convert string "0" to boolean false for comparison
        $salaryStructureKeptBool = filter_var($salaryStructureKept, FILTER_VALIDATE_BOOLEAN);

        // FIXED: Proper salary structure status detection
        if ($salaryStructureKeptBool === false && $inactivatedId) {
            // Salary structure was explicitly inactivated
            $additionalData['salary_structure_status'] = 'inactivated';
            $additionalData['salary_structure_status_message'] = 'Salary structure was inactivated during promotion. Awaiting new structure.';
            $additionalData['needs_new_salary_structure'] = true;
        } elseif ($salaryStructureKeptBool === false && $hasSalaryStructureBefore) {
            // Salary structure existed but was inactivated
            $additionalData['salary_structure_status'] = 'inactivated';
            $additionalData['salary_structure_status_message'] = 'Salary structure was inactivated during promotion. Awaiting new structure.';
            $additionalData['needs_new_salary_structure'] = true;
        } elseif ($salaryStructureKeptBool === true && $hasSalaryStructureBefore) {
            // Salary structure was kept active
            $additionalData['salary_structure_status'] = 'kept_active';
            $additionalData['salary_structure_status_message'] = 'Salary structure remained active during promotion';
            $additionalData['needs_new_salary_structure'] = false;
        } elseif (!$hasSalaryStructureBefore && !$salaryStructureKeptBool) {
            // No salary structure existed and admin chose to inactivate (nothing to inactivate)
            $additionalData['salary_structure_status'] = 'no_structure';
            $additionalData['salary_structure_status_message'] = 'No salary structure existed before promotion';
            $additionalData['needs_new_salary_structure'] = true;
        } elseif (!$hasSalaryStructureBefore && $salaryStructureKeptBool) {
            // No salary structure existed but admin chose to keep (nothing to keep)
            $additionalData['salary_structure_status'] = 'no_structure';
            $additionalData['salary_structure_status_message'] = 'No salary structure existed before promotion';
            $additionalData['needs_new_salary_structure'] = true;
        } else {
            $additionalData['salary_structure_status'] = 'not_applicable';
            $additionalData['salary_structure_status_message'] = 'No salary structure change';
            $additionalData['needs_new_salary_structure'] = false;
        }

        // Get employee's current designation
        $currentDesignation = $employee->designation ?? $data['designation_before'] ?? null;

        return EmployeeProbationLog::create([
            'employee_id' => $employee->id,
            'promotion_id' => $promotionId,
            'employee_code' => $employee->employee_code,
            'employee_name' => $employee->name,
            'doj' => $data['doj'] ?? $employee->doj,
            'probation_days' => $data['probation_days'] ?? null,
            'probation_start_date' => $data['probation_start_date'] ?? null,
            'probation_end_date' => $data['probation_end_date'] ?? null,
            'employment_type_before' => $data['employment_type_before'] ?? 'Probation-Period',
            'employment_type_after' => $data['employment_type_after'] ?? 'Full-time',
            'promotion_date' => $data['promotion_date'] ?? Carbon::now(),
            'promoted_by' => $data['promoted_by'] ?? auth()->user()->name ?? 'System',
            'promoted_by_user_id' => $data['promoted_by_user_id'] ?? auth()->user()->id ?? null,
            'promotion_type' => $promotionType,
            'department_before' => $data['department_before'] ?? null,
            'designation_before' => $data['designation_before'] ?? $currentDesignation,
            'salary_structure_kept' => $data['salary_structure_kept'] ?? null,
            'new_salary_structure_assigned' => isset($data['salary_structure_after']) && $data['salary_structure_after'] ? true : false,
            'inactivated_salary_structure_id' => $data['inactivated_salary_structure_id'] ?? null,
            'additional_data' => $additionalData,
        ]);
    }

    /**
     * Generate unique promotion ID.
     * This matches the EmployeePromotionController implementation.
     */
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
     * Get probation employee statistics.
     */
    public function getStatistics()
    {
        try {
            $merchantId = auth()->user()->institute_id;
            
            $totalProbation = EmployeeDetails::where('institute_id', $merchantId)
                ->where('employment_type', 'Probation-Period')
                ->count();
            
            $completed = EmployeeDetails::where('institute_id', $merchantId)
                ->where('employment_type', '!=', 'Probation-Period')
                ->whereExists(function($sub) {
                    $sub->select(DB::raw(1))
                        ->from('employee_probation_logs')
                        ->whereColumn('employee_probation_logs.employee_id', 'employee_details.id')
                        ->where('employee_probation_logs.employment_type_before', 'Probation-Period');
                })
                ->count();
            
            $endingSoon = EmployeeDetails::where('institute_id', $merchantId)
                ->where('employment_type', 'Probation-Period')
                ->whereRaw('DATE_ADD(doj, INTERVAL probation_days DAY) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)')
                ->count();
            
            $overdue = EmployeeDetails::where('institute_id', $merchantId)
                ->where('employment_type', 'Probation-Period')
                ->whereRaw('DATE_ADD(doj, INTERVAL probation_days DAY) < CURDATE()')
                ->count();
            
            $endingToday = EmployeeDetails::where('institute_id', $merchantId)
                ->where('employment_type', 'Probation-Period')
                ->whereRaw('DATE_ADD(doj, INTERVAL probation_days DAY) = CURDATE()')
                ->count();
            
            return response()->json([
                'success' => true,
                'statistics' => [
                    'total' => $totalProbation,
                    'completed' => $completed,
                    'ending_soon' => $endingSoon,
                    'overdue' => $overdue,
                    'ending_today' => $endingToday
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Statistics error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics'
            ], 500);
        }
    }

    /**
     * Check employees awaiting salary structure assignment.
     */
    public function checkAwaitingSalaryStructures()
    {
        try {
            $merchantId = auth()->user()->institute_id;
            
            $awaitingEmployees = EmployeeProbationLog::join('employee_details', 'employee_probation_logs.employee_id', '=', 'employee_details.id')
                ->where('employee_details.institute_id', $merchantId)
                ->where('employee_probation_logs.salary_structure_kept', false)
                ->where('employee_probation_logs.new_salary_structure_assigned', false)
                ->whereNotNull('employee_probation_logs.promotion_date')
                ->orderBy('employee_probation_logs.promotion_date', 'desc')
                ->select('employee_probation_logs.*')
                ->get()
                ->unique('employee_id')
                ->values()
                ->map(function($log) {
                    return [
                        'id' => $log->employee_id,
                        'name' => $log->employee_name,
                        'employee_code' => $log->employee_code,
                        'designation' => $log->designation_before,
                        'promotion_date' => $log->promotion_date ? Carbon::parse($log->promotion_date)->format('d-m-Y') : null,
                        'probation_log_id' => $log->id,
                        'inactivated_salary_structure_id' => $log->inactivated_salary_structure_id
                    ];
                });
            
            return response()->json([
                'success' => true,
                'awaiting_count' => $awaitingEmployees->count(),
                'awaiting_employees' => $awaitingEmployees,
                'salary_structure_url' => route('institute.payroll.structure')
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error checking salary structure status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to check salary structure status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate confirmation token for double confirmation.
     */
    public function getConfirmationToken()
    {
        return response()->json([
            'success' => true,
            'confirmation_token' => $this->generateConfirmationToken(),
        ]);
    }

    /**
     * Generate confirmation token.
     */
    private function generateConfirmationToken()
    {
        return bin2hex(random_bytes(32)) . '_' . time();
    }
    
}