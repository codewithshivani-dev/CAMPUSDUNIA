<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeDetails;
use App\Models\Departments;
use App\Models\Designations;
use App\Models\EmployeeProbationLog;
use App\Models\EmployeeSuspensionLog;
use App\Models\InstituteBasicDetails;
use App\Models\DepartmentCategory;
use App\Models\EmployeeSalaryStructure;
use App\Models\SalaryPreview;
use App\Models\LetterTemplate;
use App\Models\Letter;
use App\Models\EmployeeDocumentRequest;
use App\Models\EmployeeExit;
use App\Models\EmployeeExitPolicy;
use App\Models\EmployeeExitTaskAssignment;
use App\Models\PolicyAssignment;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use App\Traits\SendsInstituteNotifications;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EmployeeSelfJourneyController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships, SendsInstituteNotifications;

    public function index()
    {
        $context = $this->getInstituteBranchContext();
    
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }
        
        $user = Auth::user();
     
        $employee = EmployeeDetails::where('user_id', $user->id)->firstOrFail();
        $employeeId = $employee->employee_id;
       
        $employee = EmployeeDetails::select(
            'employee_details.*',
            'departments.department as department_name',
            'department_categories.category_name as department_category_name'
        )
        ->leftJoin('departments', 'employee_details.department_id', '=', 'departments.department_id')
        ->leftJoin('department_categories', 'employee_details.department_category_id', '=', 'department_categories.department_category_id')
        ->where('employee_details.employee_id', $employeeId)
        ->firstOrFail();
      
  
        $institute = InstituteBasicDetails::where('fincap_merchant_id', $employee->institute_id)->first();

        // Get all data for joining sub-steps (matching admin version)
        $joiningData = $this->getJoiningData($employee);
        
        // Get asset allocation data (static)
        $assetAllocationData = $this->getAssetAllocationData($employee);
        
        // Get promotion data
        $promotionData = $this->getPromotionHistoryWithDocuments($employee);
        
        // Get exit data
        $exitDetails = $this->getExitJourneyData($employee);

        $isOnProbation = $employee->employment_type === 'Probation-Period';
        $probationEndDate = $isOnProbation ? $employee->getProbationEndDateAttribute() : null;
        $probationStatus = $isOnProbation ? $employee->getProbationStatusAttribute() : null;
        $probationDaysDelta = $isOnProbation ? $employee->getProbationDaysDeltaAttribute() : null;

        $currentSalaryStructure = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
            ->where('is_active', true)
            ->first();
       
        $documents = $this->getAllEmployeeDocuments($employee);
        
        return view('instituteAdmin.EmployeeSelfJourney.EmployeeSelfJourney', [
            'employee' => $employee,
            'institute' => $institute,
            'joiningData' => $joiningData,
            'assetAllocationData' => $assetAllocationData,
            'promotionData' => $promotionData,
            'exitDetails' => $exitDetails,
            'documents' => $documents,
            'isOnProbation' => $isOnProbation,
            'probationEndDate' => $probationEndDate,
            'probationStatus' => $probationStatus,
            'probationDaysDelta' => $probationDaysDelta,
            'currentSalaryStructure' => $currentSalaryStructure,
            'user_type' => $context['is_branch_admin'] ? 'branch_admin' : 'institute_admin'
        ]);
    }

    /**
     * Get Joining Data with all sub-steps (matching admin version)
     */
    private function getJoiningData($employee)
    {
        return [
            'department' => $this->getDepartmentData($employee),
            'team_members' => $this->getTeamMembers($employee),
            'reporting_manager' => $this->getReportingManager($employee),
            'salary_structure' => $this->getSalaryStructureData($employee),
            'leave_quota' => $this->getLeaveQuotaData($employee),
            'reimbursement_policy' => $this->getReimbursementPolicy($employee),
        ];
    }

    /**
     * A. Department Data with History (tabular in view)
     */
    private function getDepartmentData($employee)
    {
        $isAssigned = !empty($employee->department_id);

        if (!$isAssigned) {
            return [
                'is_assigned' => false,
                'message' => 'No department has been assigned yet.',
            ];
        }

        $history = $this->getDepartmentHistory($employee);

        return [
            'is_assigned' => true,
            'current_department' => $employee->department_name ?? 'N/A',
            'current_designation' => $employee->designation ?? 'N/A',
            'department_category' => $employee->department_category_name ?? 'N/A',
            'DOJ' => $employee->doj ? Carbon::parse($employee->doj)->format('d M, Y') : 'N/A',
            'history' => $history,
        ];
    }

    /**
     * Department History (from probation/promotion logs)
     */
    private function getDepartmentHistory($employee)
    {
        $history = [];

        $logs = EmployeeProbationLog::where('employee_id', $employee->id)
            ->where(function ($query) {
                $query->where('promotion_type', 'designation')
                      ->orWhere('promotion_type', 'department')
                      ->orWhere('promotion_type', 'designation_promotion');
            })
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($logs as $log) {
            $additionalData = is_string($log->additional_data)
                ? json_decode($log->additional_data, true)
                : ($log->additional_data ?? []);

            $history[] = [
                'date' => Carbon::parse($log->created_at)->format('d M, Y'),
                'from_department' => $additionalData['old_department'] ?? 'N/A',
                'to_department' => $additionalData['new_department'] ?? $employee->department_name,
                'from_designation' => $additionalData['old_designation'] ?? 'N/A',
                'to_designation' => $additionalData['new_designation'] ?? $employee->designation,
                'performed_by' => $log->promoted_by ?? 'System',
            ];
        }

        if (empty($history) && $employee->doj) {
            $history[] = [
                'date' => Carbon::parse($employee->doj)->format('d M, Y'),
                'from_department' => 'N/A',
                'to_department' => $employee->department_name ?? 'N/A',
                'from_designation' => 'N/A',
                'to_designation' => $employee->designation ?? 'N/A',
                'performed_by' => 'System',
            ];
        }

        return $history;
    }

    /**
     * B. Team Members — matched by department_id
     */
    private function getTeamMembers($employee)
    {
        if (empty($employee->department_id)) {
            return [
                'is_assigned' => false,
                'message' => 'No team members assigned yet.',
                'members' => [],
                'count' => 0,
            ];
        }

        $teamMembers = EmployeeDetails::where('department_id', $employee->department_id)
            ->where('id', '!=', $employee->id)
            ->where('status', 'active')
            ->select('name', 'designation', 'employee_code', 'profile_photo', 'doj')
            ->get();

        return [
            'is_assigned' => $teamMembers->count() > 0,
            'members' => $teamMembers,
            'count' => $teamMembers->count(),
            'message' => $teamMembers->count() > 0 ? null : 'No team members found in the same department.',
        ];
    }

    /**
     * C. Reporting Manager
     */
    private function getReportingManager($employee)
    {
        if (empty($employee->reporting_to)) {
            return [
                'is_assigned' => false,
                'message' => 'No reporting manager assigned yet.',
                'manager' => null,
            ];
        }

        $reportingManager = EmployeeDetails::where('employee_id', $employee->reporting_to)
            ->where('status', 'active')
            ->select('name', 'designation', 'employee_code', 'profile_photo', 'doj')
            ->first();

        return [
            'is_assigned' => !!$reportingManager,
            'message' => $reportingManager ? null : 'Reporting manager not found.',
            'manager' => $reportingManager,
        ];
    }

    /**
     * D. Salary Structure with History
     */
    private function getSalaryStructureData($employee)
    {
        $salaryStructures = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
            ->orderBy('created_at', 'asc')
            ->get();

        $isAssigned = $salaryStructures->count() > 0;

        if (!$isAssigned) {
            return [
                'is_assigned' => false,
                'message' => 'No salary structure has been assigned yet.',
                'current' => null,
                'history' => [],
            ];
        }

        $history = [];
        foreach ($salaryStructures as $structure) {
            $history[] = [
                'id' => $structure->salary_structure_id ?? 'N/A',
                'financial_year' => $structure->financial_year ?? 'N/A',
                'basic_monthly' => isset($structure->basic_salary_monthly) ? '₹' . number_format($structure->basic_salary_monthly, 2) : 'N/A',
                'basic_annual' => isset($structure->basic_salary_annual) ? '₹' . number_format($structure->basic_salary_annual, 2) : 'N/A',
                'fixed_ctc_annual' => isset($structure->fixed_ctc_annual) ? '₹' . number_format($structure->fixed_ctc_annual, 2) : 'N/A',
                'total_ctc_annual' => isset($structure->total_ctc_annual) ? '₹' . number_format($structure->total_ctc_annual, 2) : 'N/A',
                'effective_date' => Carbon::parse($structure->created_at)->format('d M, Y'),
                'is_active' => $structure->is_active ?? false,
                'change_type' => $structure->change_type ?? ($structure->is_active ? 'Initial Assignment' : 'Promotion Change'),
            ];
        }

        return [
            'is_assigned' => true,
            'current' => $salaryStructures->where('is_active', true)->first(),
            'history' => $history,
        ];
    }

    /**
     * E. Leave Quota Data
     */
    private function getLeaveQuotaData($employee)
    {
        $currentSession = $this->getCurrentAcademicSession();
        
        // First try to get employee-specific leave quotas
        $leaveQuotas = \App\Models\EmployeeLeaveBalance::where('employee_id', $employee->employee_id)
            ->where('session_year', $currentSession)
            ->get();

        $isAssigned = $leaveQuotas->count() > 0;

        // If no employee-specific quotas found, try department-level quotas
        if (!$isAssigned && !empty($employee->department_id)) {
            $departmentQuotas = \App\Models\EmployeeLeaveBalance::where('department_id', $employee->department_id)
                ->where('session_year', $currentSession)
                ->where('assignment_type', 'department')
                ->get();
            
            if ($departmentQuotas->count() > 0) {
                $leaveQuotas = $departmentQuotas;
                $isAssigned = true;
            }
        }

        // Format quota data for display
        $formattedQuotas = [];
        foreach ($leaveQuotas as $quota) {
            $formattedQuotas[] = [
                'leave_type' => $quota->leave_type,
                'total_allocated' => $quota->total_allocated ?? 0,
                'remaining' => $quota->remaining ?? 0,
                'used' => ($quota->total_allocated ?? 0) - ($quota->remaining ?? 0),
                'assigned_date' => $quota->created_at ? Carbon::parse($quota->created_at)->format('d M, Y') : 'N/A',
                'assignment_type' => $quota->assignment_type ?? 'employee',
                'session_year' => $quota->session_year ?? $currentSession,
                'allocation_period' => $quota->allocation_period ?? $currentSession,
                'is_custom' => $quota->is_custom ?? false,
            ];
        }

        return [
            'is_assigned' => $isAssigned,
            'quotas' => $formattedQuotas,
            'has_view_button' => $isAssigned,
            'message' => $isAssigned ? null : 'No leave quota assigned yet.',
            'quota_source' => $isAssigned ? ($leaveQuotas->first()->employee_id ? 'Employee' : 'Department') : null,
        ];
    }

    private function getCurrentAcademicSession()
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        
        // Academic session: April to March
        if ($currentMonth >= 4) {
            return $currentYear . '-' . ($currentYear + 1);
        } else {
            return ($currentYear - 1) . '-' . $currentYear;
        }
    }

    /**
     * F. Reimbursement Policy - STATIC DATA (same as admin)
     */
    private function getReimbursementPolicy($employee)
    {
        return [
            'is_assigned' => true,
            'policy' => [
                'name' => 'Employee Reimbursement Policy',
                'description' => 'Employees can claim reimbursement for work-related expenses.',
                'categories' => [
                    'Travel' => 'Up to ₹5,000 per month',
                    'Meals' => 'Up to ₹2,000 per month',
                    'Communication' => 'Up to ₹1,000 per month',
                    'Other' => 'Subject to manager approval',
                ],
                'submission_deadline' => 'Within 30 days of expense',
                'approval_process' => 'Manager → Finance → HR',
            ],
            'message' => null,
        ];
    }

    /**
     * Asset Allocation Data - STATIC (same as admin)
     */
    private function getAssetAllocationData($employee)
    {
        // Check if employee has any assets allocated
        $hasAssets = false;
        $allocations = collect();

        try {
            // Try to get actual asset allocations if the model exists
            if (class_exists(\App\Models\EmployeeAssetAllocation::class)) {
                $allocations = \App\Models\EmployeeAssetAllocation::where('employee_id', $employee->employee_id)
                    ->with('asset')
                    ->get();
                $hasAssets = $allocations->count() > 0;
            }
        } catch (\Exception $e) {
            Log::info('Asset allocation model not found, using static data');
        }

        return [
            'has_assets' => $hasAssets,
            'allocations' => $allocations,
            'total_assets' => $allocations->count(),
            'message' => $hasAssets ? null : 'No assets have been allocated to you yet.',
            'overview' => [
                'Status' => $hasAssets ? 'Assets Allocated' : 'No Assets Assigned',
                'Total Assets' => $allocations->count(),
                'Department' => $employee->department_name ?? 'N/A',
                'Designation' => $employee->designation ?? 'N/A',
            ],
            'eligible_assets' => [
                'Laptop / Desktop',
                'Official Email Account',
                'ID Card',
                'Access Card',
                'SIM Card',
                'Software Licenses',
                'Headset',
                'Office Keys',
            ],
            'allocation_process' => [
                'Department Head raises an asset request.',
                'IT/Admin verifies asset availability.',
                'HR approves the allocation.',
                'Employee receives and acknowledges the asset.',
                'Asset is tagged and mapped to the employee.',
            ],
            'policies' => [
                'Assets remain company property.',
                'Employees are responsible for proper care.',
                'Loss or damage must be reported immediately.',
                'Unauthorized software installation is prohibited.',
                'Periodic asset audits may be conducted.',
            ],
            'return_policy' => [
                'All company assets must be returned during transfer or exit.',
                'Assets will be verified before Full & Final settlement.',
                'Damaged or missing assets may incur recovery charges.',
            ],
        ];
    }

    /**
     * Promotion History with Documents
     */
    private function getPromotionHistoryWithDocuments($employee)
    {
        $completeHistory = $this->getCompletePromotionHistory($employee);

        if ($completeHistory->isEmpty()) {
            return [
                'promotions' => [],
                'has_promotions' => false,
                'message' => 'No promotions recorded yet.',
            ];
        }

        $promotionList = [];

        foreach ($completeHistory as $item) {
            $displayType = $item->title;
            $fromValue = $item->from_value;
            $toValue = $item->to_value;

            switch ($item->change_type) {
                case 'employment_type':
                    $fromValue = $fromValue ?? data_get($item->additional_data, 'employment_type_before') ?? $item->employment_type_before ?? 'N/A';
                    $toValue = $toValue ?? data_get($item->additional_data, 'employment_type_after') ?? $item->employment_type_after ?? 'N/A';
                    $fromDisplay = $this->formatEmploymentType($fromValue);
                    $toDisplay = $this->formatEmploymentType($toValue);
                    break;

                case 'designation':
                    $fromValue = $fromValue ?? data_get($item->additional_data, 'designation_before') ?? $item->designation_before ?? 'N/A';
                    $toValue = $toValue ?? data_get($item->additional_data, 'designation_after') ?? $item->designation_after ?? $fromValue;
                    $fromDisplay = $fromValue;
                    $toDisplay = $toValue;
                    break;

                default:
                    $fromDisplay = $fromValue ?: 'N/A';
                    $toDisplay = $toValue ?: 'N/A';
            }

            $promotionList[] = [
                'id' => $item->log_id,
                'promotion_id' => $item->promotion_id,
                'type' => $displayType,
                'change_type' => $item->change_type,
                'icon' => $item->icon,
                'color' => $item->color,
                'from' => $fromDisplay,
                'to' => $toDisplay,
                'performed_by' => $item->promoted_by ?? 'System',
                'date' => $item->promotion_date
                    ? Carbon::parse($item->promotion_date)->format('d M, Y')
                    : Carbon::parse($item->created_at)->format('d M, Y'),
            ];
        }

        return [
            'promotions' => $promotionList,
            'has_promotions' => true,
            'message' => null,
        ];
    }

    /**
     * Format employment type for display
     */
    private function formatEmploymentType($value)
    {
        if (empty($value) || $value === 'N/A') {
            return 'N/A';
        }
        
        $cleanValue = preg_replace('/\s*\(Probation:.*\)/', '', $value);
        $cleanValue = trim($cleanValue);
        
        $types = [
            'probation' => 'Probation',
            'probation_period' => 'Probation Period',
            'full_time' => 'Full-Time',
            'part_time' => 'Part-Time',
            'contract' => 'Contract-based',
            'contract_based' => 'Contract-based',
            'temporary' => 'Temporary',
            'intern' => 'Intern',
            'consultant' => 'Consultant',
            'freelance' => 'Freelance',
            'Full-time' => 'Full-Time',
            'Part-time' => 'Part-Time',
        ];
        
        $key = strtolower(str_replace(' ', '_', $cleanValue));
        return $types[$key] ?? ucfirst(str_replace('_', ' ', $cleanValue));
    }

    /**
     * Get Complete Promotion History
     */
    private function getCompletePromotionHistory($employee)
    {
        $logs = EmployeeProbationLog::where('employee_id', $employee->id)
            ->orderByDesc('promotion_date')
            ->orderByDesc('created_at')
            ->get();

        $history = [];

        foreach ($logs as $log) {
            $additionalData = is_array($log->additional_data)
                ? $log->additional_data
                : json_decode($log->additional_data ?? '{}', true);

            $additionalData = $additionalData ?: [];

            $promotionType = $log->promotion_type
                ?? ($additionalData['promotion_type'] ?? 'other');

            if ($promotionType === 'probation') {
                continue;
            }

            // Employment Type
            if (in_array($promotionType, [
                'employment_type',
                'employment_type_promotion',
                'bulk_employment_type'
            ])) {
                $history[] = (object)[
                    'log_id' => $log->id,
                    'promotion_id' => $log->promotion_id,
                    'promotion_type' => $promotionType,
                    'change_type' => 'employment_type',
                    'title' => 'Employment Type',
                    'icon' => 'fas fa-user-tag',
                    'color' => 'primary',
                    'from_value' => $additionalData['old_employment_type'] ?? $additionalData['employment_type_before'] ?? $additionalData['old_value'] ?? $log->employment_type_before ?? 'N/A',
                    'to_value' => $additionalData['new_employment_type'] ?? $additionalData['employment_type_after'] ?? $additionalData['new_value'] ?? $log->employment_type_after ?? 'N/A',
                    'promotion_date' => $log->promotion_date,
                    'promoted_by' => $log->promoted_by,
                    'created_at' => $log->created_at,
                    'additional_data' => $additionalData,
                ];
                continue;
            }

            // Designation
            if (in_array($promotionType, [
                'designation',
                'designation_promotion'
            ])) {
                $history[] = (object)[
                    'log_id' => $log->id,
                    'promotion_id' => $log->promotion_id,
                    'promotion_type' => $promotionType,
                    'change_type' => 'designation',
                    'title' => 'Designation',
                    'icon' => 'fas fa-briefcase',
                    'color' => 'success',
                    'from_value' => $additionalData['old_designation'] ?? $additionalData['designation_before'] ?? $additionalData['old_value'] ?? $log->designation_before ?? 'N/A',
                    'to_value' => $additionalData['new_designation'] ?? $additionalData['designation_after'] ?? $additionalData['new_value'] ?? $log->designation_after ?? 'N/A',
                    'promotion_date' => $log->promotion_date,
                    'promoted_by' => $log->promoted_by,
                    'created_at' => $log->created_at,
                    'additional_data' => $additionalData,
                ];
                continue;
            }

            // Salary Structure Assignment
            if ($promotionType === 'salary_structure_assignment') {
                $salaryBefore = $additionalData['salary_structure_before'] ?? null;
                $salaryAfter = $additionalData['salary_structure_after'] ?? null;

                $history[] = (object)[
                    'log_id' => $log->id,
                    'promotion_id' => $log->promotion_id,
                    'promotion_type' => $promotionType,
                    'change_type' => 'salary_assignment',
                    'title' => 'Salary Assignment',
                    'icon' => 'fas fa-money-bill-wave',
                    'color' => 'info',
                    'from_value' => is_array($salaryBefore) ? '₹' . number_format($salaryBefore['total_ctc_annual'] ?? 0) : 'N/A',
                    'to_value' => is_array($salaryAfter) ? '₹' . number_format($salaryAfter['total_ctc_annual'] ?? 0) : 'Assigned',
                    'promotion_date' => $log->promotion_date,
                    'promoted_by' => $log->promoted_by,
                    'created_at' => $log->created_at,
                    'additional_data' => $additionalData,
                ];
                continue;
            }

            // Salary Structure Inactivation
            if ($promotionType === 'salary_structure_inactivation') {
                $salaryBefore = $additionalData['salary_structure_before'] ?? null;

                $history[] = (object)[
                    'log_id' => $log->id,
                    'promotion_id' => $log->promotion_id,
                    'promotion_type' => $promotionType,
                    'change_type' => 'salary_inactivation',
                    'title' => 'Salary Inactivation',
                    'icon' => 'fas fa-ban',
                    'color' => 'danger',
                    'from_value' => is_array($salaryBefore) ? '₹' . number_format($salaryBefore['total_ctc_annual'] ?? 0) : 'Active',
                    'to_value' => 'Inactive',
                    'promotion_date' => $log->promotion_date,
                    'promoted_by' => $log->promoted_by,
                    'created_at' => $log->created_at,
                    'additional_data' => $additionalData,
                ];
                continue;
            }
        }

        return collect($history)
            ->sortByDesc(function ($item) {
                return strtotime($item->promotion_date ?? $item->created_at);
            })
            ->values();
    }

    /**
     * Exit Journey Data - dynamic based on active exit, assigned policy and task workflow.
     */
    private function getExitJourneyData($employee)
    {
        $context = $this->getInstituteBranchContext();
        $instituteId = $context['institute_id'] ?? $employee->institute_id;

        $exitPolicy = $this->getAssignedExitPolicyForEmployee($employee, $instituteId);

        $activeExit = EmployeeExit::where('employee_id', $employee->employee_id)
            ->whereIn('exit_status', ['pending_approval', 'approved', 'notice_period', 'exited'])
            ->with(['approvals' => function ($query) {
                $query->orderBy('step_number', 'asc');
            }])
            ->latest('created_at')
            ->first();

        $taskAssignments = $activeExit ? $this->getDynamicExitTaskAssignments($activeExit->id) : collect();
        $noticeDetails = $activeExit ? $this->calculateExitNoticeDetails($activeExit) : null;

        $journey = $this->buildDynamicExitJourney($activeExit, $exitPolicy, $noticeDetails, $taskAssignments);

        return [
            'stages' => $journey,
            'is_exit_initiated' => $activeExit !== null,
            'exit_status' => $activeExit ? $activeExit->exit_status : 'not_initiated',
            'message' => $activeExit ? null : 'Exit process has not been initiated.',
        ];
    }

    private function getAssignedExitPolicyForEmployee($employee, $instituteId)
    {
        $policies = PolicyAssignment::where(function ($query) use ($employee) {
                $query->where('employee_id', $employee->employee_id)
                    ->orWhere('department_id', $employee->department_id)
                    ->orWhere('assignment_type', 'all_department');
            })
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now());
            })
            ->with('policy')
            ->get();

        $matchingPolicy = null;

        foreach ($policies as $assignment) {
            if (!$assignment->policy || !$assignment->policy->is_active) {
                continue;
            }

            if ($assignment->employee_id == $employee->employee_id) {
                $matchingPolicy = $assignment->policy;
                break;
            }

            if ($assignment->department_id == $employee->department_id) {
                $matchingPolicy = $assignment->policy;
                break;
            }

            if ($assignment->assignment_type === 'all_department') {
                $matchingPolicy = $assignment->policy;
            }
        }

        if (!$matchingPolicy) {
            $matchingPolicy = EmployeeExitPolicy::where('institute_id', $instituteId)
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->first();
        }

        return $matchingPolicy;
    }

    private function getDynamicExitTaskAssignments($exitId)
    {
        if (!$exitId) {
            return collect();
        }

        $tasks = EmployeeExitTaskAssignment::where('exit_id', $exitId)->get();

        foreach ($tasks as $task) {
            if ($task->assigned_to_user_id) {
                $user = \App\Models\User::find($task->assigned_to_user_id);
                if ($user) {
                    $task->assigned_user_email = $user->email;
                    $task->assigned_user_phone = $user->phone ?? null;
                }

                $assignedEmployee = EmployeeDetails::where('user_id', $task->assigned_to_user_id)->first();
                if ($assignedEmployee) {
                    $task->assigned_employee_designation = $assignedEmployee->designation;
                    $task->assigned_employee_code = $assignedEmployee->employee_code;
                    $task->assigned_employee_profile_photo = $assignedEmployee->profile_photo ?? null;
                }
            }
        }

        return $tasks->keyBy('task_type');
    }

    private function calculateExitNoticeDetails($activeExit)
    {
        $start = $activeExit->notice_start_date ? Carbon::parse($activeExit->notice_start_date) : null;
        $end = $activeExit->notice_end_date ? Carbon::parse($activeExit->notice_end_date) : null;

        if (!$start || !$end) {
            return [
                'start_date' => null,
                'end_date' => null,
                'days_remaining' => 0,
                'is_overdue' => false,
            ];
        }

        return [
            'start_date' => $start->format('d M, Y'),
            'end_date' => $end->format('d M, Y'),
            'days_remaining' => max(0, now()->diffInDays($end, false)),
            'is_overdue' => $end->isPast() && $activeExit->exit_status === 'notice_period',
        ];
    }

    private function buildDynamicExitJourney($activeExit, $exitPolicy, $noticeDetails, $taskAssignments)
    {
        $journey = [];
        $hasActiveExit = $activeExit !== null;
        $hasPolicy = $exitPolicy !== null;
        $currentStatus = $activeExit ? $activeExit->exit_status : null;
        $isExited = $currentStatus === 'exited';
        $isNoticePeriod = $currentStatus === 'notice_period';
        $isPendingApproval = $currentStatus === 'pending_approval';
        $isApproved = $currentStatus === 'approved';
        $initiationSource = $activeExit ? ($activeExit->initiation_source ?? 'employee') : 'employee';
        $exitType = $activeExit ? ($activeExit->exit_type ?? 'Resignation') : 'Resignation';
        $isAdminInitiated = $initiationSource === 'admin';

        $baseDate = $activeExit ? ($activeExit->approved_at ? Carbon::parse($activeExit->approved_at) : Carbon::parse($activeExit->created_at)) : null;
        $expectedExitDate = null;
        if ($baseDate && $exitPolicy) {
            $noticeDuration = $exitPolicy->notice_period_days ?? $exitPolicy->default_notice_period ?? 45;
            $expectedExitDate = $baseDate->copy()->addDays($noticeDuration);
        }

        $submissionDate = $activeExit ? Carbon::parse($activeExit->created_at) : null;
        $submissionStatus = $hasActiveExit ? 'completed' : 'pending';

        if ($isAdminInitiated) {
            $title = 'Exit Initiated by Admin';
            $icon = 'fas fa-user-cog';
            $description = $hasActiveExit ? 'Admin has initiated the exit process for this employee.' : 'Admin will initiate the exit process.';
            $statusLabel = $submissionStatus === 'completed' ? 'Completed' : 'Action Required';
            $dataExitType = ucwords(str_replace('_', ' ', $exitType));
        } else {
            $title = 'Submit Resignation';
            $icon = 'fas fa-pen';
            $description = $hasActiveExit ? 'Resignation submitted successfully and the workflow is active.' : 'Submit the resignation request to begin the exit process.';
            $statusLabel = $submissionStatus === 'completed' ? 'Completed' : 'Action Required';
            $dataExitType = $activeExit ? ucwords(str_replace('_', ' ', $activeExit->exit_type ?? 'Resignation')) : 'N/A';
        }

        $journey[] = [
            'key' => 'submission',
            'title' => $title,
            'icon' => $icon,
            'color' => $isAdminInitiated ? 'secondary' : 'primary',
            'status' => $submissionStatus,
            'status_label' => $statusLabel,
            'date' => $submissionDate ? $submissionDate->format('d M, Y') : null,
            'description' => $description,
            'is_enabled' => true,
            'is_first' => true,
            'is_locked' => !$hasPolicy,
            'is_admin_initiated' => $isAdminInitiated,
            'exit_type' => $exitType,
            'initiation_source' => $initiationSource,
            'assigned_to' => null,
            'data' => [
                'submitted_at' => $submissionDate ? $submissionDate->format('d M Y g:i A') : 'Not submitted',
                'exit_type' => $dataExitType,
                'initiation_source' => ucfirst($initiationSource),
                'exit_reason' => $activeExit ? ucwords(str_replace('_', ' ', $activeExit->exit_reason ?? 'N/A')) : 'N/A',
            ],
        ];

        if ($hasPolicy) {
            $approvalStatus = 'pending';
            $approvalDate = null;
            $approvers = [];

            if ($hasActiveExit) {
                if ($isPendingApproval) {
                    $approvalStatus = 'in-progress';
                } elseif ($isNoticePeriod || $isApproved || $isExited) {
                    $approvalStatus = 'completed';
                    $approvalDate = $activeExit->approved_at ? Carbon::parse($activeExit->approved_at) : $submissionDate;
                    $approvers = $activeExit->approvals ?? [];
                }
            }

            if ($isAdminInitiated && ($isNoticePeriod || $isApproved || $isExited)) {
                $approvalStatus = 'completed';
                $approvalDate = $activeExit->created_at ? Carbon::parse($activeExit->created_at) : null;
            }

            $journey[] = [
                'key' => 'approval',
                'title' => 'Request Status',
                'icon' => 'fas fa-user-check',
                'color' => 'info',
                'status' => $approvalStatus,
                'status_label' => $approvalStatus === 'completed' ? 'Approved' : 'Pending',
                'date' => $approvalDate ? $approvalDate->format('d M, Y') : null,
                'description' => $isAdminInitiated
                    ? ($approvalStatus === 'completed' ? 'Exit was auto-approved by admin.' : 'Waiting for the admin initiated approval step.')
                    : ($approvalStatus === 'completed' ? 'The resignation request has been approved.' : 'Awaiting approval from the configured approvers.'),
                'is_enabled' => true,
                'is_locked' => !$hasActiveExit || $isPendingApproval,
                'is_admin_initiated' => $isAdminInitiated,
                'assigned_to' => null,
                'data' => [
                    'approvers' => $approvers,
                    'approved_at' => $approvalDate ? $approvalDate->format('d M Y g:i A') : ($hasActiveExit ? 'Pending' : 'Will start after submission'),
                    'status' => $hasActiveExit ? ucwords(str_replace('_', ' ', $currentStatus)) : 'Not initiated',
                ],
            ];
        }

        if ($hasPolicy) {
            $noticeStatus = 'pending';
            $noticeDuration = $exitPolicy->notice_period_days ?? $exitPolicy->default_notice_period ?? 45;
            $noticeStart = null;
            $noticeEnd = null;
            $daysRemaining = 0;

            if ($hasActiveExit) {
                if ($isNoticePeriod) {
                    $noticeStatus = 'in-progress';
                    $noticeStart = $noticeDetails['start_date'] ?? ($activeExit->approved_at ? Carbon::parse($activeExit->approved_at)->format('d M, Y') : null);
                    $noticeEnd = $noticeDetails['end_date'] ?? ($expectedExitDate ? $expectedExitDate->format('d M, Y') : null);
                    $daysRemaining = $noticeDetails['days_remaining'] ?? 0;
                } elseif ($isApproved || $isExited) {
                    $noticeStatus = 'completed';
                    $noticeStart = $noticeDetails['start_date'] ?? ($activeExit->approved_at ? Carbon::parse($activeExit->approved_at)->format('d M, Y') : null);
                    $noticeEnd = $noticeDetails['end_date'] ?? ($expectedExitDate ? $expectedExitDate->format('d M, Y') : null);
                    $daysRemaining = 0;
                }
            }

            $noticeDisplay = $noticeStatus === 'completed' ? 'Completed' : ($noticeStatus === 'in-progress' ? ($daysRemaining > 0 ? $daysRemaining . ' days remaining' : 'Notice period ending soon') : $noticeDuration . ' days');

            $journey[] = [
                'key' => 'notice_period',
                'title' => 'Notice Period',
                'icon' => 'fas fa-hourglass-half',
                'color' => 'warning',
                'status' => $noticeStatus,
                'status_label' => $noticeStatus === 'completed' ? 'Completed' : ($noticeStatus === 'in-progress' ? 'In Progress' : 'Pending'),
                'date' => $noticeDisplay,
                'description' => $noticeStatus === 'completed'
                    ? 'Notice period has been completed.'
                    : ($noticeStatus === 'in-progress' ? ($daysRemaining > 0 ? $daysRemaining . ' days remaining before exit.' : 'Notice period is ending soon.') : 'Notice period is pending and will begin after approval.'),
                'is_enabled' => true,
                'is_locked' => !$hasActiveExit || $isPendingApproval,
                'is_admin_initiated' => $isAdminInitiated,
                'assigned_to' => null,
                'data' => [
                    'notice_days' => $noticeDuration,
                    'start_date' => $noticeStart ?? 'N/A',
                    'end_date' => $noticeEnd ?? 'N/A',
                    'days_remaining' => $daysRemaining,
                    'is_overdue' => $noticeDetails['is_overdue'] ?? false,
                ],
            ];
        }

        if ($hasPolicy && $exitPolicy->kt_required) {
            $ktStatus = 'pending';
            $ktTask = $taskAssignments->get('kt');

            if ($ktTask) {
                $ktStatus = $ktTask->status;
            }

            if ($hasActiveExit && $isExited) {
                $ktStatus = 'completed';
            }

            $journey[] = [
                'key' => 'knowledge_transfer',
                'title' => 'Knowledge Transfer',
                'icon' => 'fas fa-chalkboard-teacher',
                'color' => 'success',
                'status' => $ktStatus,
                'status_label' => $ktStatus === 'completed' ? 'Completed' : 'Pending',
                'date' => null,
                'description' => 'Transfer knowledge, tasks and documentation before the last working day.',
                'is_enabled' => true,
                'is_locked' => !$hasActiveExit || $isPendingApproval,
                'is_admin_initiated' => $isAdminInitiated,
                'assigned_to' => $ktTask ? $ktTask->assigned_to_user_id : null,
                'assigned_to_name' => $ktTask ? $ktTask->assigned_to_name : null,
                'assigned_to_role' => $ktTask ? $ktTask->assigned_to_role : null,
                'task_id' => $ktTask ? $ktTask->id : null,
                'task_deadline' => $ktTask ? $ktTask->deadline : null,
                'task_instructions' => $ktTask ? $ktTask->instructions : null,
                'data' => [
                    'kt_duration' => $exitPolicy->kt_days ?? 5,
                    'requirements' => $exitPolicy->kt_requirements ?? [],
                    'status' => $ktStatus,
                ],
            ];
        }

        if ($hasPolicy && !empty($exitPolicy->clearance_workflow) && $exitPolicy->clearance_workflow !== 'none') {
            $clearanceStatus = 'pending';
            $assetTask = $taskAssignments->get('asset_clearance');
            if ($assetTask) {
                $clearanceStatus = $assetTask->status;
            }
            if ($hasActiveExit && $isExited) {
                $clearanceStatus = 'completed';
            }

            $journey[] = [
                'key' => 'clearance',
                'title' => 'Assets & Clearance',
                'icon' => 'fas fa-clipboard-check',
                'color' => 'info',
                'status' => $clearanceStatus,
                'status_label' => $clearanceStatus === 'completed' ? 'Completed' : 'Pending',
                'date' => null,
                'description' => 'Return assets and complete the clearance checklist before exit.',
                'is_enabled' => true,
                'is_locked' => !$hasActiveExit || $isPendingApproval,
                'is_admin_initiated' => $isAdminInitiated,
                'assigned_to' => null,
                'task_id' => $assetTask ? $assetTask->id : null,
                'data' => [
                    'clearance_duration' => $exitPolicy->clearance_days ?? 7,
                    'workflow' => $exitPolicy->clearance_workflow ?? 'sequential',
                    'status' => $clearanceStatus,
                ],
            ];
        }

        if ($hasPolicy && $exitPolicy->exit_interview_required) {
            $interviewStatus = 'pending';
            $interviewTask = $taskAssignments->get('exit_interview');
            if ($interviewTask) {
                $interviewStatus = $interviewTask->status;
            }
            if ($hasActiveExit && $isExited) {
                $interviewStatus = 'completed';
            }

            $journey[] = [
                'key' => 'exit_interview',
                'title' => 'Exit Interview',
                'icon' => 'fas fa-comments',
                'color' => 'warning',
                'status' => $interviewStatus,
                'status_label' => $interviewStatus === 'completed' ? 'Completed' : 'Pending',
                'date' => null,
                'description' => 'Complete the employee exit interview before final release.',
                'is_enabled' => true,
                'is_locked' => !$hasActiveExit || $isPendingApproval,
                'is_admin_initiated' => $isAdminInitiated,
                'assigned_to' => $interviewTask ? $interviewTask->assigned_to_user_id : null,
                'assigned_to_name' => $interviewTask ? $interviewTask->assigned_to_name : null,
                'assigned_to_role' => $interviewTask ? $interviewTask->assigned_to_role : null,
                'task_id' => $interviewTask ? $interviewTask->id : null,
                'task_deadline' => $interviewTask ? $interviewTask->deadline : null,
                'data' => [
                    'interview_duration' => $exitPolicy->interview_days ?? 5,
                    'status' => $interviewStatus,
                ],
            ];
        }

        if ($hasPolicy && $exitPolicy->fnf_required) {
            $fnfStatus = 'pending';
            $fnfTask = $taskAssignments->get('fnf');
            if ($fnfTask) {
                $fnfStatus = $fnfTask->status;
            }
            if ($hasActiveExit && $isExited) {
                $fnfStatus = 'completed';
            }

            $journey[] = [
                'key' => 'fnf',
                'title' => 'FNF Settlement',
                'icon' => 'fas fa-file-invoice-dollar',
                'color' => 'danger',
                'status' => $fnfStatus,
                'status_label' => $fnfStatus === 'completed' ? 'Completed' : 'Pending',
                'date' => null,
                'description' => 'Process final settlement, dues, reimbursements and closure amounts.',
                'is_enabled' => true,
                'is_locked' => !$hasActiveExit || $isPendingApproval,
                'is_admin_initiated' => $isAdminInitiated,
                'assigned_to' => $fnfTask ? $fnfTask->assigned_to_user_id : null,
                'assigned_to_name' => $fnfTask ? $fnfTask->assigned_to_name : null,
                'assigned_to_role' => $fnfTask ? $fnfTask->assigned_to_role : null,
                'task_id' => $fnfTask ? $fnfTask->id : null,
                'task_deadline' => $fnfTask ? $fnfTask->deadline : null,
                'data' => [
                    'fnf_duration' => $exitPolicy->fnf_processing_days ?? 30,
                    'items' => $exitPolicy->fnf_items ?? [],
                    'status' => $fnfStatus,
                ],
            ];
        }

        return $journey;
    }

    /**
     * Get all employee documents grouped by document type
     * (Matching admin version exactly)
     */
    private function getAllEmployeeDocuments($employee)
    {
        $documents = [];
        
     
            // Get all letters for this employee with their document types
            $letters = Letter::query()
                ->leftJoin(
                    'official_documents',
                    'letters.official_documenttype_id',
                    '=', 
                    'official_documents.official_documenttype_id'
                )
                ->leftJoin(
                    'letter_templates',
                    'letters.template_id',
                    '=', 
                    'letter_templates.id'
                )
                ->where('letters.employee_id', $employee->id)
                ->select(
                    'letters.*',
                    'official_documents.official_document_type',
                    'letter_templates.title as template_title',
                    'letter_templates.document_type as template_document_type',
                    'letter_templates.key as template_key'
                )
                ->orderBy('letters.created_at', 'desc')
                ->get();
            
           
            // Get all letter templates for this institute with official document types
            $letterTemplates = LetterTemplate::where('letter_templates.institute_id', $employee->institute_id)
                ->leftJoin(
                    'official_documents',
                    'letter_templates.official_documenttype_id',
                    '=',
                    'official_documents.official_documenttype_id'
                )
                ->select(
                    'letter_templates.*',
                    'official_documents.official_document_type as official_doc_type'
                )
                ->orderBy('letter_templates.document_type')
                ->orderBy('letter_templates.title')
                ->get();
          
            
            // Get all pending document requests for this employee
            $pendingRequests = EmployeeDocumentRequest::where('employee_id', $employee->employee_id)
                ->whereIn('request_status', ['pending', 'approved', 'processing'])
                ->get()
                ->keyBy('template_id');
            
            // Group generated documents by official_document_type
            foreach ($letters as $letter) {
                // Try to get document type from various sources
                $documentType = $this->getDocumentTypeFromSources(
                    $letter->official_document_type,
                    $letter->template_document_type,
                    $letter->template_key,
                    $letter->title
                );
                
                if (!isset($documents[$documentType])) {
                    $documents[$documentType] = [];
                }
                
                // Check if there's a pending request for this document
                $pendingRequest = $pendingRequests->get($letter->template_id);
                
                $documents[$documentType][] = (object) [
                    'id' => $letter->id,
                    'name' => $letter->title ?? $letter->template_title ?? 'Document',
                    'letter_id' => $letter->letter_id,
                    'template_id' => $letter->template_id,
                    'reference_id' => $letter->reference_id,
                    'file_path' => $letter->official_document_file,
                    'file_exists' => $this->checkFileExists($letter->official_document_file),
                    'type' => $documentType,
                    'date' => Carbon::parse($letter->created_at)->format('d M, Y'),
                    'icon' => $this->getDocumentIcon($documentType),
                    'color' => $this->getDocumentColor($documentType),
                    'status' => 'generated',
                    'generated_letter_id' => $letter->letter_id ?? $letter->id,
                    'generated_at' => Carbon::parse($letter->created_at)->format('d M, Y'),
                    'size' => $this->getFileSizeFromPath($letter->official_document_file),
                    'document_request_id' => null,
                ];
            }
            
            // Also add letter templates that don't have generated documents yet
            foreach ($letterTemplates as $template) {
                // Check if this template already has a generated document
                $existingLetter = $letters->firstWhere('template_id', $template->id);
                
                if ($existingLetter) {
                    continue; // Skip if already generated
                }
                
                // Determine document type from template
                $docType = $this->getDocumentTypeFromSources(
                    $template->official_doc_type,
                    $template->document_type,
                    $template->key,
                    $template->title
                );
                
                // Check if there's a pending request
                $pendingRequest = $pendingRequests->get($template->id);
                
                // Determine document status
                if ($pendingRequest) {
                    $docStatus = $pendingRequest->request_status;
                    $requestId = $pendingRequest->document_request_id;
                } else {
                    $docStatus = 'not_requested';
                    $requestId = null;
                }
                
                if (!isset($documents[$docType])) {
                    $documents[$docType] = [];
                }
                
                $documents[$docType][] = (object) [
                    'id' => $template->id,
                    'name' => $template->title,
                    'key' => $template->key,
                    'letter_id' => $template->letter_id,
                    'template_id' => $template->id,
                    'reference_id' => null,
                    'file_path' => null,
                    'file_exists' => false,
                    'type' => $docType,
                    'date' => $template->created_at ? Carbon::parse($template->created_at)->format('d M, Y') : 'N/A',
                    'size' => '---',
                    'icon' => $this->getDocumentIcon($docType),
                    'color' => $this->getDocumentColor($docType),
                    'description' => $template->key,
                    'can_request' => true,
                    'letter_template_id' => $template->id,
                    'official_documenttype_id' => $template->official_documenttype_id,
                    'status' => $docStatus,
                    'document_request_id' => $requestId,
                    'generated_letter_id' => null,
                    'generated_at' => null,
                ];
            }
            
            // Sort documents within each group by date (newest first)
            foreach ($documents as $type => $docs) {
                usort($docs, function($a, $b) {
                    return strtotime($b->date) - strtotime($a->date);
                });
                $documents[$type] = $docs;
            }
            
        return $documents;
    }

        /**
     * Get document type from multiple sources
     */
    private function getDocumentTypeFromSources($officialDocType, $templateDocType, $templateKey, $templateTitle)
    {
        // First priority: official document type
        if (!empty($officialDocType)) {
            return $this->normalizeDocumentType($officialDocType);
        }
        
        // Second priority: template document type
        if (!empty($templateDocType)) {
            return $this->normalizeDocumentType($templateDocType);
        }
        
        // Third priority: determine from key or title
        return $this->getDocumentTypeFromKeyOrTitle($templateKey, $templateTitle);
    }
    
        /**
     * Determine document type from template key or title
     */
    private function getDocumentTypeFromKeyOrTitle($key, $title)
    {
        $searchText = strtolower(trim(($key ?? '') . ' ' . ($title ?? '')));
        
        // Onboarding documents
        if (strpos($searchText, 'appointment') !== false || 
            strpos($searchText, 'offer') !== false || 
            strpos($searchText, 'onboarding') !== false || 
            strpos($searchText, 'joining letter') !== false) {
            return 'Onboarding';
        }
        
        // Joining documents
        if (strpos($searchText, 'joining') !== false || 
            strpos($searchText, 'induction') !== false) {
            return 'Joining';
        }
        
        // Salary/Payroll documents
        if (strpos($searchText, 'salary') !== false || 
            strpos($searchText, 'payroll') !== false || 
            strpos($searchText, 'increment') !== false || 
            strpos($searchText, 'appraisal') !== false) {
            return 'Payroll';
        }
        
        // Promotion documents
        if (strpos($searchText, 'promotion') !== false || 
            strpos($searchText, 'performance') !== false) {
            return 'Promotion and Performance';
        }
        
        // Exit documents
        if (strpos($searchText, 'exit') !== false || 
            strpos($searchText, 'termination') !== false || 
            strpos($searchText, 'resignation') !== false || 
            strpos($searchText, 'relieving') !== false) {
            return 'Exit';
        }
        
        // Experience documents
        if (strpos($searchText, 'experience') !== false || 
            strpos($searchText, 'service certificate') !== false) {
            return 'Experience';
        }
        
        // Disciplinary documents
        if (strpos($searchText, 'disciplinary') !== false || 
            strpos($searchText, 'warning') !== false || 
            strpos($searchText, 'show cause') !== false) {
            return 'Disciplinary';
        }
        
        return 'Other';
    }

    /**
     * Normalize document type to match expected group names
     */
    private function normalizeDocumentType($documentType)
    {
        if (empty($documentType) || $documentType === 'Other') {
            return 'Other';
        }
        
        // Common document type mappings
        $mappings = [
            'onboarding' => 'Onboarding',
            'joining' => 'Joining',
            'employment' => 'Employment Type',
            'employment_type' => 'Employment Type',
            'payroll' => 'Payroll',
            'salary' => 'Payroll',
            'promotion' => 'Promotion and Performance',
            'performance' => 'Promotion and Performance',
            'promotion and performance' => 'Promotion and Performance',
            'exit' => 'Exit',
            'termination' => 'Exit',
            'resignation' => 'Exit',
            'experience' => 'Experience',
            'disciplinary' => 'Disciplinary',
        ];
        
        $key = strtolower(trim($documentType));
        return $mappings[$key] ?? ucfirst($key);
    }


    /**
     * Check if file exists in storage
     */
    private function checkFileExists($filePath)
    {
        if (empty($filePath)) {
            return false;
        }

        $paths = [
            storage_path('app/public/' . $filePath),
            storage_path('app/' . $filePath),
            public_path('storage/' . $filePath),
            public_path($filePath)
        ];

        foreach ($paths as $path) {
            if (file_exists($path) && is_file($path)) {
                return true;
            }
        }

        try {
            if (Storage::disk('public')->exists($filePath)) {
                return true;
            }
            if (Storage::disk('local')->exists($filePath)) {
                return true;
            }
        } catch (\Exception $e) {
            Log::error('Storage check failed: ' . $e->getMessage());
        }

        return false;
    }

    /**
     * Get file size from path
     */
    private function getFileSizeFromPath($filePath)
    {
        try {
            if (empty($filePath)) {
                return '---';
            }

            $paths = [
                storage_path('app/public/' . $filePath),
                storage_path('app/' . $filePath),
                public_path('storage/' . $filePath),
                public_path($filePath)
            ];

            foreach ($paths as $path) {
                if (file_exists($path) && is_file($path)) {
                    $size = filesize($path);
                    if ($size < 1024) {
                        return $size . ' B';
                    } elseif ($size < 1048576) {
                        return round($size / 1024, 1) . ' KB';
                    } else {
                        return round($size / 1048576, 1) . ' MB';
                    }
                }
            }

            try {
                if (Storage::disk('public')->exists($filePath)) {
                    $size = Storage::disk('public')->size($filePath);
                    if ($size < 1024) {
                        return $size . ' B';
                    } elseif ($size < 1048576) {
                        return round($size / 1024, 1) . ' KB';
                    } else {
                        return round($size / 1048576, 1) . ' MB';
                    }
                }
            } catch (\Exception $e) {
                Log::error('Storage size check failed: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Error getting file size: ' . $e->getMessage());
        }
        return '---';
    }

    /**
     * Get document icon based on type (matching admin version)
     */
    private function getDocumentIcon($documentType)
    {
        $icons = [
            'Onboarding' => 'fas fa-user-plus',
            'Joining' => 'fas fa-handshake',
            'Employment Type' => 'fas fa-user-tag',
            'Payroll' => 'fas fa-money-bill-wave',
            'Promotion and Performance' => 'fas fa-trophy',
            'Exit' => 'fas fa-sign-out-alt',
            'Experience' => 'fas fa-certificate',
            'Disciplinary' => 'fas fa-gavel',
            'Other' => 'fas fa-file-alt',
        ];

        return $icons[$documentType] ?? 'fas fa-file-alt';
    }

    /**
     * Get document color based on type (matching admin version)
     */
    private function getDocumentColor($documentType)
    {
        $colors = [
            'Onboarding' => 'primary',
            'Joining' => 'info',
            'Employment Type' => 'warning',
            'Payroll' => 'success',
            'Promotion and Performance' => 'primary',
            'Exit' => 'danger',
            'Experience' => 'success',
            'Disciplinary' => 'danger',
            'Other' => 'secondary',
        ];

        return $colors[$documentType] ?? 'secondary';
    }

    // ============================================
    // API ENDPOINTS
    // ============================================

    /**
     * API endpoint to get employee journey data
     */
    public function getJourneyData($id)
    {
        try {
            $context = $this->getInstituteBranchContext();
            $merchantId = auth()->user()->institute_id;

            $employee = EmployeeDetails::where('id', $id)
                ->where('institute_id', $merchantId)
                ->firstOrFail();

            $joiningData = $this->getJoiningData($employee);
            $assetAllocationData = $this->getAssetAllocationData($employee);
            $promotionData = $this->getPromotionHistoryWithDocuments($employee);
            $exitDetails = $this->getExitJourneyData($employee);
            $documents = $this->getAllEmployeeDocuments($employee);

            return response()->json([
                'success' => true,
                'data' => [
                    'employee' => $employee,
                    'joining_data' => $joiningData,
                    'asset_allocation' => $assetAllocationData,
                    'promotion_data' => $promotionData,
                    'exit_details' => $exitDetails,
                    'documents' => $documents,
                    'is_on_probation' => $employee->employment_type === 'Probation-Period'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching employee journey data: ' . $e->getMessage(), [
                'employee_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching employee journey data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if document file exists
     */
    public function checkDocumentFile($path)
    {
        try {
            if (empty($path)) {
                return response('', 404);
            }

            $paths = [
                storage_path('app/public/' . $path),
                storage_path('app/' . $path),
                public_path('storage/' . $path),
                public_path($path)
            ];

            foreach ($paths as $fullPath) {
                if (file_exists($fullPath) && is_file($fullPath)) {
                    return response('', 200);
                }
            }

            if (Storage::disk('public')->exists($path)) {
                return response('', 200);
            }

            return response('', 404);
        } catch (\Exception $e) {
            Log::error('Error checking document file: ' . $e->getMessage(), ['path' => $path]);
            return response('', 404);
        }
    }

    /**
     * Get document file for viewing
     */
    public function getDocumentFile($path)
    {
        try {
            if (empty($path)) {
                abort(404, 'File path is empty');
            }

            $paths = [
                storage_path('app/public/' . $path),
                storage_path('app/' . $path),
                public_path('storage/' . $path),
                public_path($path)
            ];

            foreach ($paths as $fullPath) {
                if (file_exists($fullPath) && is_file($fullPath)) {
                    $mimeType = mime_content_type($fullPath);
                    $fileName = basename($fullPath);
                    
                    return response()->file($fullPath, [
                        'Content-Type' => $mimeType,
                        'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                        'Cache-Control' => 'public, max-age=3600'
                    ]);
                }
            }

            if (Storage::disk('public')->exists($path)) {
                return response()->file(Storage::disk('public')->path($path));
            }

            abort(404, 'File not found');
        } catch (\Exception $e) {
            Log::error('Error getting document file: ' . $e->getMessage(), ['path' => $path]);
            abort(404, 'File not found: ' . $e->getMessage());
        }
    }

    /**
     * Download document file
     */
    public function downloadDocumentFile($path)
    {
        try {
            if (empty($path)) {
                abort(404, 'File path is empty');
            }

            $paths = [
                storage_path('app/public/' . $path),
                storage_path('app/' . $path),
                public_path('storage/' . $path),
                public_path($path)
            ];

            foreach ($paths as $fullPath) {
                if (file_exists($fullPath) && is_file($fullPath)) {
                    $fileName = basename($fullPath);
                    return response()->download($fullPath, $fileName);
                }
            }

            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->download($path);
            }

            abort(404, 'File not found');
        } catch (\Exception $e) {
            Log::error('Error downloading document: ' . $e->getMessage(), ['path' => $path]);
            abort(404, 'File not found: ' . $e->getMessage());
        }
    }
}