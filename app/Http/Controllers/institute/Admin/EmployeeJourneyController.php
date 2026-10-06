<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeDetails;
use App\Models\Departments;
use App\Models\Designations;
use App\Models\EmployeeProbationLog;
use App\Models\InstituteBasicDetails;
use App\Models\DepartmentCategory;
use App\Models\EmployeeSalaryStructure;
use App\Models\EmployeeLeaveBalance;
use App\Models\LetterTemplate;
use App\Models\Letter;
use App\Models\EmployeeDocumentRequest;
use App\Models\EmployeeExit;
use App\Models\EmployeeExitPolicy;
use App\Models\EmployeeExitTaskAssignment;
use App\Models\PolicyAssignment;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeJourneyController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;


    public function index($id)
    {
        $context = $this->getInstituteBranchContext();
        $merchantId = auth()->user()->institute_id;

        // Get employee details with relationships
        $employee = EmployeeDetails::select(
            'employee_details.*',
            'departments.department as department_name',
            'department_categories.category_name as department_category_name'
        )
        ->leftJoin('departments', 'employee_details.department_id', '=', 'departments.department_id')
        ->leftJoin('department_categories', 'employee_details.department_category_id', '=', 'department_categories.department_category_id')
        ->where('employee_details.id', $id)
        ->where('employee_details.institute_id', $merchantId)
        ->firstOrFail();

        // Get all documents grouped by document type
        $allDocuments = $this->getAllDocumentsGroupedByType($employee);

        // Build 5 major stages
        $stages = $this->buildJourneyStages($employee, $allDocuments);

        // Get summary card data
        $summaryData = $this->getSummaryData($employee);

        return view('instituteAdmin.EmployeeFiles.EmployeeJourney', [
            'employee' => $employee,
            'stages' => $stages,
            'documents' => $allDocuments,
            'summaryData' => $summaryData,
            'user_type' => $context['is_branch_admin'] ? 'branch_admin' : 'institute_admin',
        ]);
    }

    /**
     * Build 5 Major Journey Stages
     */
    private function buildJourneyStages($employee, $allDocuments)
    {
        return [
            'onboarding' => $this->getOnboardingStage($employee, $allDocuments),
            'joining' => $this->getJoiningStage($employee, $allDocuments),
            'asset_allocation' => $this->getAssetAllocationStage($employee, $allDocuments),
            'promotions' => $this->getPromotionsStage($employee, $allDocuments),
            'exit' => $this->getExitStage($employee, $allDocuments),
        ];
      
    }

    /**
     * STAGE 1: ONBOARDING - Fetch created_at date
     */
    private function getOnboardingStage($employee, $allDocuments)
    {
        $status = 'pending';
        $statusLabel = 'Pending';

        if ($employee->doj) {
            $status = 'completed';
            $statusLabel = 'Completed';
        }

        return [
            'id' => 'onboarding',
            'title' => 'Onboarding',
            'icon' => 'fas fa-user-plus',
            'color' => 'primary',
            'status' => $status,
            'status_label' => $statusLabel,
            'date' => $employee->created_at ? Carbon::parse($employee->created_at)->format('d M, Y') : null,
            'tabs' => [
                'overview' => [
                    'title' => 'Overview',
                    'icon' => 'fas fa-info-circle',
                    'data' => [
                        'Onboarded Date' => $employee->created_at ? Carbon::parse($employee->created_at)->format('d M, Y') : 'N/A',
                        'Employee Code' => $employee->employee_code ?? 'N/A',
                        'Status' => $this->getStatusBadge($employee),
                    ]
                ],
                'documents' => $this->getDocumentsTab($allDocuments, 'Onboarding'),
            ]
        ];
    }

    /**
     * STAGE 2: JOINING - Only 2 tabs at controller level (Overview + Documents).
     * Overview aggregates 5 sub-steps: Department, Team Members, Salary Structure,
     * Leave Quota, Reimbursement Policy — each carries its own 'is_assigned' flag
     * so the view can render an Assigned / Not Assigned badge per step.
     */
    private function getJoiningStage($employee, $allDocuments)
    {
        return [
            'id' => 'joining',
            'title' => 'Joining',
            'icon' => 'fas fa-handshake',
            'color' => 'info',
            'status' => $employee->doj ? 'completed' : 'pending',
            'status_label' => $employee->doj ? 'Completed' : 'Pending',
            'date' => $employee->doj ? Carbon::parse($employee->doj)->format('d M, Y') : null,
            'tabs' => [
                // sub-steps live inside "overview" conceptually, but are kept as
                // individual keys here so each can be looked up by the view
                'department' => [
                    'title' => 'Department',
                    'icon' => 'fas fa-building',
                    'data' => $this->getDepartmentData($employee),
                ],
                'team_members' => [
                    'title' => 'Team Members',
                    'icon' => 'fas fa-users',
                    'data' => $this->getTeamMembers($employee),
                ],
                'reporting_manager' => [
                    'title' => 'Reporting Manager',
                    'icon' => 'fas fa-users',
                    'data' => $this->getReportingManager($employee),
                ],
                'salary_structure' => [
                    'title' => 'Salary Structure',
                    'icon' => 'fas fa-money-bill-wave',
                    'data' => $this->getSalaryStructureData($employee),
                ],
                'leave_quota' => [
                    'title' => 'Leave Quota',
                    'icon' => 'fas fa-calendar-alt',
                    'data' => $this->getLeaveQuotaData($employee),
                ],
                'reimbursement_policy' => [
                    'title' => 'Reimbursement Policy',
                    'icon' => 'fas fa-receipt',
                    'data' => $this->getReimbursementPolicy($employee),
                ],
                'documents' => $this->getDocumentsTab($allDocuments, 'Joining'),
            ]
        ];
    }

    /**
     * STAGE 3: ASSET ALLOCATION - Static Data Only
     */
    private function getAssetAllocationStage($employee, $allDocuments)
    {
        
        return [
            'id' => 'asset_allocation',
            'title' => 'Asset Allocation',
            'icon' => 'fas fa-laptop',
            'color' => 'warning',
            'status' => 'pending',
            'status_label' => 'Not Assigned',
            'date' => null,
            'tabs' => [
                'overview' => [
                    'title' => 'Overview',
                    'icon' => 'fas fa-info-circle',
                    'data' => [
                        'has_assets' => false,
                        'allocations' => collect(),
                        'total_assets' => 0,
                        'message' => 'No assets have been allocated to this employee yet.',
                        'overview' => [
                            'Status' => 'No Assets Assigned',
                            'Total Assets' => 0,
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
                    ]
                ],
                'documents' => $this->getDocumentsTab($allDocuments, 'Other'),
                
            ]
        ];
    }
    

    /**
     * STAGE 4: PROMOTIONS - based on employment/designation/salary changes
     */
    private function getPromotionsStage($employee, $allDocuments)
    {
        $promotionData = $this->getPromotionHistoryWithDocuments($employee, $allDocuments);

        return [
            'id' => 'promotions',
            'title' => 'Promotions',
            'icon' => 'fas fa-arrow-up',
            'color' => 'success',
            'status' => !empty($promotionData['promotions']) ? 'completed' : 'pending',
            'status_label' => !empty($promotionData['promotions']) ? 'Completed' : 'Pending',
            'date' => null,
            'tabs' => [
                'history' => [
                    'title' => 'Promotion History',
                    'icon' => 'fas fa-history',
                    'data' => $promotionData,
                ],
                'documents' => $this->getDocumentsTab($allDocuments, 'Promotion and Performance'),
            ]
        ];
    }

    /**
     * STAGE 5: EXIT - STATIC DATA
     */
    private function getExitStage($employee, $allDocuments)
    {
        $exitData = $this->getExitJourneyData($employee);

        return [
            'id' => 'exit',
            'title' => 'Exit',
            'icon' => 'fas fa-sign-out-alt',
            'color' => 'danger',
            'status' => $employee->status === 'inactive' ? 'completed' : 'pending',
            'status_label' => $employee->status === 'inactive' ? 'Completed' : 'Pending',
            'date' => $employee->exit_date ? Carbon::parse($employee->exit_date)->format('d M, Y') : null,
            'tabs' => [
                'journey' => [
                    'title' => 'Exit Journey',
                    'icon' => 'fas fa-road',
                    'data' => $exitData,
                ],
                'documents' => $this->getDocumentsTab($allDocuments, 'Exit'),
            ]
        ];
    }

    /**
     * Get Summary Card Data (rendered as 3 separate cards in the view)
     */
    private function getSummaryData($employee)
    {
        $currentCTC = $this->getCurrentCTC($employee);

        return [
            'employment_type' => $employee->employment_type ?? 'N/A',
            'department' => $employee->department_name ?? 'N/A',
            'ctc' => $currentCTC,
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
     * B. Team Members — matched by department_id, shown in tabular form
     * with name, designation, and date of joining.
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
     * C. Reporting Manager — matched by reporting_to, shown with name, designation, and contact details.
     */
    private function getReportingManager($employee)
    {
        if((!$employee->reporting_to)) {
                if ((!$employee->reporting_to)) {
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
        else{
            
            $reportingManager = EmployeeDetails::where('employee_id', $employee->employee_id)
                ->where('status', 'active')
                ->select('name', 'designation', 'employee_code', 'profile_photo', 'doj', 'designation_id','department_id')
                ->first();
            dd($reportingManager);
            return [
                'is_assigned' => !!$reportingManager,
                'message' => $reportingManager ? null : 'Reporting manager not found.',
                'manager' => $reportingManager,
            ];
        }  
       
    }

    /**
     * C. Salary Structure with History
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
     * Get Current CTC (used in the standalone summary card)
     */
    private function getCurrentCTC($employee)
    {
        $currentSalary = EmployeeSalaryStructure::where('employee_id', $employee->employee_id)
            ->where('is_active', true)
            ->first();

        if ($currentSalary && isset($currentSalary->total_ctc_annual)) {
            return '₹' . number_format($currentSalary->total_ctc_annual, 2);
        }

        return 'N/A';
    }

    /**
     * D. Leave Quota Data with Assignment Type and Allocation Period
     */
    private function getLeaveQuotaData($employee)
    {
        $currentSession = $this->getCurrentAcademicSession();
        
        // First try to get employee-specific leave quotas
        $leaveQuotas = EmployeeLeaveBalance::where('employee_id', $employee->employee_id)
            ->where('session_year', $currentSession)
            ->get();

        $isAssigned = $leaveQuotas->count() > 0;

        // If no employee-specific quotas found, try department-level quotas
        if (!$isAssigned && !empty($employee->department_id)) {
            $departmentQuotas = EmployeeLeaveBalance::where('department_id', $employee->department_id)
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
     * E. Reimbursement Policy - STATIC DATA
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
     * Get Promotion History with Documents.
     * Shows: Employment Type, Designation, Salary Assignment with proper from/to
     */
    private function getPromotionHistoryWithDocuments($employee, $allDocuments)
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
            $toValue   = $item->to_value;

            switch ($item->change_type) {

                case 'employment_type':

                    $fromValue = $fromValue
                        ?? data_get($item->additional_data, 'employment_type_before')
                        ?? $item->employment_type_before
                        ?? 'N/A';

                    $toValue = $toValue
                        ?? data_get($item->additional_data, 'employment_type_after')
                        ?? $item->employment_type_after
                        ?? 'N/A';

                    $fromDisplay =  $this->formatEmploymentType($fromValue);
                    $toDisplay   =  $this->formatEmploymentType($toValue);

                    break;


                case 'designation':

                    $fromValue = $fromValue
                        ?? data_get($item->additional_data, 'designation_before')
                        ?? $item->designation_before
                        ?? 'N/A';

                    $toValue = $toValue
                        ?? data_get($item->additional_data, 'designation_after')
                        ?? $item->designation_after
                        ?? $fromValue;

                    $fromDisplay = $fromValue;
                    $toDisplay   = $toValue;

                    break;


                default:

                    $fromDisplay = $fromValue ?: 'N/A';
                    $toDisplay   = $toValue ?: 'N/A';
            }

            $promotionDocuments = $this->getPromotionDocuments($item, $allDocuments);

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
                'documents' => $promotionDocuments,
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
        
        // Remove probation text if present
        $cleanValue = preg_replace('/\s*\(Probation:.*\)/', '', $value);
        $cleanValue = trim($cleanValue);
        
        // Map common employment types to readable format
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

            /*
            |--------------------------------------------------------------------------
            | Employment Type
            |--------------------------------------------------------------------------
            */

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

                    'from_value' =>
                        $additionalData['old_employment_type']
                        ?? $additionalData['employment_type_before']
                        ?? $additionalData['old_value']
                        ?? $log->employment_type_before
                        ?? 'N/A',

                    'to_value' =>
                        $additionalData['new_employment_type']
                        ?? $additionalData['employment_type_after']
                        ?? $additionalData['new_value']
                        ?? $log->employment_type_after
                        ?? 'N/A',

                    'promotion_date' => $log->promotion_date,
                    'promoted_by' => $log->promoted_by,
                    'created_at' => $log->created_at,
                    'additional_data' => $additionalData,
                ];

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Designation
            |--------------------------------------------------------------------------
            */

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

                    'from_value' =>
                        $additionalData['old_designation']
                        ?? $additionalData['designation_before']
                        ?? $additionalData['old_value']
                        ?? $log->designation_before
                        ?? 'N/A',

                    'to_value' =>
                        $additionalData['new_designation']
                        ?? $additionalData['designation_after']
                        ?? $additionalData['new_value']
                        ?? $log->designation_after
                        ?? $additionalData['old_designation']
                        ?? $additionalData['designation_before']
                        ?? $log->designation_before
                        ?? 'N/A',

                    'promotion_date' => $log->promotion_date,
                    'promoted_by' => $log->promoted_by,
                    'created_at' => $log->created_at,
                    'additional_data' => $additionalData,
                ];

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Salary Structure Assignment
            |--------------------------------------------------------------------------
            */

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

                    'from_value' => is_array($salaryBefore)
                        ? '₹' . number_format($salaryBefore['total_ctc_annual'] ?? 0)
                        : 'N/A',

                    'to_value' => is_array($salaryAfter)
                        ? '₹' . number_format($salaryAfter['total_ctc_annual'] ?? 0)
                        : 'Assigned',

                    'promotion_date' => $log->promotion_date,
                    'promoted_by' => $log->promoted_by,
                    'created_at' => $log->created_at,
                    'additional_data' => $additionalData,
                ];

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Salary Structure Inactivation
            |--------------------------------------------------------------------------
            */

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

                    'from_value' => is_array($salaryBefore)
                        ? '₹' . number_format($salaryBefore['total_ctc_annual'] ?? 0)
                        : 'Active',

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
     * Get Promotion Documents
     */
    private function getPromotionDocuments($promotionItem, $allDocuments)
    {
        $docs = [];

        // Check if there's a document associated with this promotion
        if (isset($allDocuments['Promotion and Performance'])) {
            foreach ($allDocuments['Promotion and Performance'] as $doc) {
                $docs[] = $doc;
            }
        }

        return $docs;
    }


    /**
     * Get exit journey using actual employee exit records, policy assignments and task assignments.
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

        $daysRemaining = max(0, now()->diffInDays($end, false));

        return [
            'start_date' => $start->format('d M, Y'),
            'end_date' => $end->format('d M, Y'),
            'days_remaining' => $daysRemaining,
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
     * Get Status Badge HTML
     */
    private function getStatusBadge($employee)
    {
        $status = $employee->status ?? 'pending';
        $badgeClass = $status === 'active' ? 'success' : ($status === 'inactive' ? 'danger' : 'warning');
        $icon = $status === 'active' ? 'fa-check-circle' : ($status === 'inactive' ? 'fa-times-circle' : 'fa-clock');

        return '<span class="badge bg-' . $badgeClass . ' rounded-pill px-3 py-2">
                    <i class="fas ' . $icon . ' me-1"></i>
                    ' . ucfirst($status) . '
                </span>';
    }

    /**
     * Get Documents Tab
     */
    private function getDocumentsTab($allDocuments, $documentType)
    {
        $documents = $this->filterDocumentsByType($allDocuments, $documentType);

        if (empty($documents)) {
            return [
                'title' => 'Documents',
                'icon' => 'fas fa-file-alt',
                'empty' => true,
                'message' => 'No ' . $documentType . ' documents available.',
                'documents' => [],
            ];
        }

        return [
            'title' => 'Documents',
            'icon' => 'fas fa-file-alt',
            'documents' => $documents,
            'empty' => false,
        ];
    }

    /**
     * Filter documents by document type
     */
    private function filterDocumentsByType($allDocuments, $documentType)
    {
        $filtered = [];

        if (isset($allDocuments[$documentType])) {
            foreach ($allDocuments[$documentType] as $doc) {
                $filtered[] = $doc;
            }
        }

        return $filtered;
    }

    private function getAllDocumentsGroupedByType($employee)
{
    $documents = [];

    // try {

        $letters = Letter::query()
            ->leftJoin(
                'official_documents',
                'letters.official_documenttype_id',
                '=',
                'official_documents.official_documenttype_id'
            )
            ->leftJoin(
                'letter_templates',
                'letters.letter_id',
                '=',
                'letter_templates.letter_id'
            )
            ->where('letters.employee_id', $employee->id)
            ->select(
                'letters.*',
                'official_documents.official_document_type',
                'letter_templates.title as template_title'
            )
            ->orderBy('letters.created_at', 'desc')
            ->get();
        
        foreach ($letters as $letter) {

            $documentType = $letter->official_document_type ?: 'Other';

            if (!isset($documents[$documentType])) {
                $documents[$documentType] = [];
            }

         $documents[$documentType][] = (object) [
            'id' => $letter->id,
            'name' => $letter->title,
            'letter_id' => $letter->letter_id,
            'reference_id' => $letter->reference_id,
            'file_path' => $letter->official_document_file,
            'file_exists' => $this->checkFileExists($letter->official_document_file),
            'type' => 'PDF',
            'date' => Carbon::parse($letter->created_at)->format('d M, Y'),
            'icon' => $this->getDocumentIcon($documentType),
            'color' => $this->getDocumentColor($documentType),
                ];
                }

    // } catch (\Exception $e) {
    //     Log::error($e->getMessage());
    // }

    return $documents;
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
            if (\Storage::disk('public')->exists($filePath)) {
                return true;
            }
            if (\Storage::disk('local')->exists($filePath)) {
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
                if (\Storage::disk('public')->exists($filePath)) {
                    $size = \Storage::disk('public')->size($filePath);
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
     * Get document icon based on type
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
     * Get document color based on type
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
}