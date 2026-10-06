<?php

namespace App\Http\Controllers\institute\Admin\PayrollPolicy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayrollExecution;
use App\Models\Departments;
use App\Models\FinalSalarySlip;
use App\Models\EmployeeDetails;
use App\Models\SalaryReview;
use App\Traits\InstituteBranchAccess;
use Carbon\Carbon;

class PayrollExecutionController extends Controller
{
    use InstituteBranchAccess;

    public function index()
    {
        $merchantId = auth()->user()->institute_id;
        $departments = Departments::where('institute_id', $merchantId)->get();
        
        return view('instituteAdmin.Payroll.PayrollExecution', compact('departments'));
    }
    
    public function saveConfiguration(Request $request)
    {
        $request->validate([
            'financial_year' => 'required|string',
            'apply_to' => 'required|in:all,specific',
            'department_id' => 'required_if:apply_to,specific|exists:departments,department_id',
            'payroll_cycle' => 'required|in:monthly,days',
            'execution_day' => 'required|integer|min:1|max:31',
            'cycle_days' => 'required_if:payroll_cycle,days|nullable|integer|min:1',
            'description' => 'nullable|string'
        ]);
        
        $merchantId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();
        
        $newCycle = $request->payroll_cycle;
        $newDay = $request->execution_day; // Always taken
        $newCycleDays = $newCycle === 'days' ? $request->cycle_days : null;

        // If editing an existing configuration
        if ($request->has('edit_id') && $request->edit_id) {
            $existing = PayrollExecution::where('id', $request->edit_id)
                ->where('institute_id', $merchantId)
                ->first();

            if ($existing) {
                $oldCycle = $existing->payroll_cycle;
                $oldDay = $existing->execution_day;
                $oldCycleDays = $existing->cycle_days;
                $oldFinYear = $existing->financial_year;

                $existing->update([
                    'financial_year' => $request->financial_year,
                    'payroll_cycle' => $newCycle,
                    'execution_day' => $newDay,
                    'cycle_days' => $newCycleDays,
                    'notes' => $request->description,
                ]);

                if ((string)$oldDay !== (string)$newDay || (string)$oldCycle !== (string)$newCycle || (string)$oldCycleDays !== (string)$newCycleDays || $oldFinYear !== $request->financial_year) {
                    \App\Models\PayrollExecutionLogs::create([
                        'payroll_execution_id' => $existing->id,
                        'department_id' => $existing->department_id,
                        'old_financial_year' => $oldFinYear,
                        'new_financial_year' => $request->financial_year,
                        'old_cycle' => $oldCycle,
                        'new_cycle' => $newCycle,
                        'old_cycle_days' => $oldCycleDays,
                        'new_cycle_days' => $newCycleDays,
                        'old_execution_day' => $oldDay,
                        'new_execution_day' => $newDay,
                        'changed_by_name' => auth()->user()->name ?? (auth()->user()->employee_id ?? 'Admin'),
                        'remark' => "Configuration modified dynamically."
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Payroll configuration updated successfully'
                ]);
            }
        }

        // If 'apply_to' is all, loop through all departments, else just the selected one.
        if ($request->apply_to === 'all') {
            $departments = Departments::where('institute_id', $merchantId)->get();
            $departmentIds = $departments->pluck('department_id')->toArray();
        } else {
            $departmentIds = [$request->department_id];
        }

        // Check for existing records to prompt override warning
        if (!$request->has('edit_id') && !$request->boolean('force_override')) {
            $existingCount = PayrollExecution::whereIn('department_id', $departmentIds)
                ->where('institute_id', $merchantId)
                ->count();
                
            if ($existingCount > 0) {
                $scopeText = $request->apply_to === 'all' ? ($existingCount === count($departmentIds) ? "all departments" : "{$existingCount} of the selected departments") : "this department";
                return response()->json([
                    'success' => false,
                    'require_confirmation' => true,
                    'message' => "Execution rules already exist for {$scopeText}. This action will override the existing configurations. Do you want to continue?"
                ]);
            }
        }

        // Shared execution ID for this batch of inserts/updates if created newly
        $executionId = 'EXE-' . date('Y-m') . '-' . strtoupper(substr(uniqid(), -4));

        foreach ($departmentIds as $deptId) {
            // Check if existing configuration for this department
            $existing = PayrollExecution::where('department_id', $deptId)
                ->where('institute_id', $merchantId)
                ->first();

            if ($existing) {
                $oldCycle = $existing->payroll_cycle;
                $oldDay = $existing->execution_day;
                $oldCycleDays = $existing->cycle_days;
                $oldFinYear = $existing->financial_year;

                $existing->update([
                    'financial_year' => $request->financial_year,
                    'payroll_cycle' => $newCycle,
                    'execution_day' => $newDay,
                    'cycle_days' => $newCycleDays,
                    'execution_id' => $existing->execution_id ?: $executionId,
                    'notes' => $request->description,
                ]);

                if ((string)$oldDay !== (string)$newDay || (string)$oldCycle !== (string)$newCycle || (string)$oldCycleDays !== (string)$newCycleDays || $oldFinYear !== $request->financial_year) {
                    \App\Models\PayrollExecutionLogs::create([
                        'payroll_execution_id' => $existing->id,
                        'department_id' => $deptId,
                        'old_financial_year' => $oldFinYear,
                        'new_financial_year' => $request->financial_year,
                        'old_cycle' => $oldCycle,
                        'new_cycle' => $newCycle,
                        'old_cycle_days' => $oldCycleDays,
                        'new_cycle_days' => $newCycleDays,
                        'old_execution_day' => $oldDay,
                        'new_execution_day' => $newDay,
                        'changed_by_name' => auth()->user()->name ?? (auth()->user()->employee_id ?? 'Admin'),
                        'remark' => "Configuration modified dynamically."
                    ]);
                }
            } else {
                PayrollExecution::create([
                    'execution_id' => $executionId,
                    'institute_id' => $merchantId,
                    'branch_id' => $context['branch_id'] ?? null,
                    'financial_year' => $request->financial_year,
                    'department_id' => $deptId,
                    'payroll_cycle' => $newCycle,
                    'execution_day' => $newDay,
                    'cycle_days' => $newCycleDays,
                    'notes' => $request->description,
                    'processed_by' => auth()->user()->employee_id ?? (auth()->user()->id ?? null)
                ]);
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Payroll configuration saved successfully'
        ]);
    }
    
    public function getConfigurations()
    {
        $merchantId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();
        
        $configurations = PayrollExecution::where('institute_id', $merchantId)
            ->where('branch_id', $context['branch_id'])
            ->with('department')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $configurations
        ]);
    }
    
    public function getConfiguration($id)
    {
        $merchantId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();
        
        $configuration = PayrollExecution::where('id', $id)
            ->where('institute_id', $merchantId)
            ->where('branch_id', $context['branch_id'])
            ->first();
        
        if (!$configuration) {
            return response()->json([
                'success' => false,
                'message' => 'Configuration not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $configuration
        ]);
    }
    
    public function deleteConfiguration($id)
    {
        $merchantId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();
        
        $configuration = PayrollExecution::where('id', $id)
            ->where('institute_id', $merchantId)
            ->where('branch_id', $context['branch_id'])
            ->first();
        
        if (!$configuration) {
            return response()->json([
                'success' => false,
                'message' => 'Configuration not found'
            ], 404);
        }
        
        $configuration->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Configuration deleted successfully'
        ]);
    }
    
    public function viewPage(Request $request)
    {
        $merchantId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();
        
        // Get filter values
        $filterYear = $request->get('year', Carbon::now()->year);
        $selectedMonth = $request->get('month', Carbon::now()->month);
        $filterDepartmentId = $request->get('department_id');
        
        // FIX: Extract just the year from financial year if it's a range (e.g., "2026-2027" -> "2026")
        if (strpos($filterYear, '-') !== false) {
            $selectedYear = substr($filterYear, 0, 4); // Take first 4 characters as year
        } else {
            $selectedYear = $filterYear;
        }
        
        // Available years from payroll_executions table (financial years)
        $availableYears = PayrollExecution::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->whereNotNull('financial_year')
            ->orderBy('financial_year', 'asc')
            ->pluck('financial_year')
            ->unique()
            ->toArray();
        
        // If no years found, provide default years
        if (empty($availableYears)) {
            $availableYears = range(Carbon::now()->subYears(2)->year, Carbon::now()->addYear()->year);
        }
        
        // Get months for filter
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = Carbon::create()->month($i)->format('F');
        }
        
        // Get all departments
        $departmentsQuery = Departments::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->with(['payrollExecution' => function($query) {
                $query->where('status', 'active');
            }]);
        
        if ($filterDepartmentId) {
            $departmentsQuery->where('department_id', $filterDepartmentId);
        }
        
        $departments = $departmentsQuery->get();
        
        // Get all employees list for individual selection
        $allEmployeesList = EmployeeDetails::where('employee_details.institute_id', $context['institute_id'])
            ->where('employee_details.status', 'active')
            ->leftJoin('departments', 'departments.department_id', '=', 'employee_details.department_id')
            ->select('employee_details.*', 'departments.department as department_name')
            ->get();
        
        // Load employees for each department
        foreach ($departments as $department) {
            $department->employees = EmployeeDetails::where('employee_details.department_id', $department->department_id)
                ->where('employee_details.institute_id', $context['institute_id'])
                ->where('employee_details.status', 'active')
                ->leftJoin('departments', 'departments.department_id', '=', 'employee_details.department_id')
                ->select('employee_details.*', 'departments.department as department_name')
                ->get();
        }
        
        // Calculate department status for each department
        $departmentStatus = [];
        $employeeStatuses = [];
        
        foreach ($departments as $department) {
            $status = $this->getDepartmentExecutionStatus($department, $selectedYear, $selectedMonth, $context);
            $departmentStatus[$department->department_id] = $status;
            
            // Get individual employee statuses
            foreach ($department->employees as $employee) {
                $isFinalized = SalaryReview::where('salary_reviews.institute_id', $context['institute_id'])
                    ->where('salary_reviews.branch_id', $context['branch_id'])
                    ->where('salary_reviews.employee_id', $employee->employee_id)
                    ->where('salary_reviews.year', $selectedYear)
                    ->where('salary_reviews.month', $selectedMonth)
                    ->where('salary_reviews.review_status', 'finalized')
                    ->exists();
                
                $isExecuted = FinalSalarySlip::where('final_salary_slips.employee_id', $employee->employee_id)
                    ->where('final_salary_slips.year', $selectedYear)
                    ->where('final_salary_slips.month', $selectedMonth)
                    ->exists();
                
                $employeeStatuses[$employee->employee_id] = [
                    'is_finalized' => $isFinalized,
                    'is_executed' => $isExecuted,
                    'can_execute' => $isFinalized && !$isExecuted,
                ];
            }
        }
        
        // Get current month's execution date
        $executionDate = $this->getCurrentMonthExecutionDate($context);
        
        return view('instituteAdmin.Payroll.PayrollExecutionView', compact(
            'departments',
            'departmentStatus',
            'employeeStatuses',
            'allEmployeesList',
            'selectedYear',
            'selectedMonth',
            'availableYears',
            'months',
            'filterDepartmentId',
            'executionDate'
        ));
    }

    private function getDepartmentExecutionStatus($department, $year, $month, $context)
    {
        // Get employee count for this department
        $employees = EmployeeDetails::where('employee_details.department_id', $department->department_id)
            ->where('employee_details.institute_id', $context['institute_id'])
            ->where('employee_details.status', 'active')
            ->count();
        
        if ($employees == 0) {
            return [
                'can_execute' => false,
                'status' => 'no_employees',
                'message' => 'No employees in this department',
                'finalized_count' => 0,
                'total_count' => 0,
                'executed_count' => 0,
            ];
        }
        
        // Get finalized salary count - FIXED: Use direct query instead of whereHas with ambiguous column
        $finalizedCount = SalaryReview::where('salary_reviews.institute_id', $context['institute_id'])
            ->where('salary_reviews.branch_id', $context['branch_id'])
            ->where('salary_reviews.year', $year)
            ->where('salary_reviews.month', $month)
            ->where('salary_reviews.review_status', 'finalized')
            ->whereIn('salary_reviews.employee_id', function($query) use ($department) {
                $query->select('employee_id')
                    ->from('employee_details')
                    ->where('department_id', $department->department_id)
                    ->where('status', 'active');
            })
            ->count();
        
        // Get executed count
        $executedCount = FinalSalarySlip::where('final_salary_slips.year', $year)
            ->where('final_salary_slips.month', $month)
            ->whereIn('final_salary_slips.employee_id', function($query) use ($department) {
                $query->select('employee_id')
                    ->from('employee_details')
                    ->where('department_id', $department->department_id)
                    ->where('status', 'active');
            })
            ->count();
        
        // Get latest execution date for this department
        $latestExecutionDate = null;
        if ($executedCount > 0) {
            $latestExecution = FinalSalarySlip::where('final_salary_slips.year', $year)
                ->where('final_salary_slips.month', $month)
                ->whereIn('final_salary_slips.employee_id', function($query) use ($department) {
                    $query->select('employee_id')
                        ->from('employee_details')
                        ->where('department_id', $department->department_id)
                        ->where('status', 'active');
                })
                ->orderBy('generated_at', 'desc')
                ->first();
            
            if ($latestExecution) {
                $latestExecutionDate = $latestExecution->generated_at->format('d M Y');
            }
        }
        
        $allFinalized = ($finalizedCount == $employees && $employees > 0);
        $allExecuted = ($executedCount == $employees && $employees > 0);
        
        if ($allExecuted) {
            $status = 'executed';
            $canExecute = false;
            $message = 'All employees have been processed';
        } elseif ($allFinalized) {
            $status = 'ready';
            $canExecute = true;
            $message = 'Ready for payroll execution';
        } elseif ($finalizedCount > 0) {
            $status = 'partial';
            $canExecute = false;
            $message = $finalizedCount . ' out of ' . $employees . ' salaries finalized';
        } else {
            $status = 'not_ready';
            $canExecute = false;
            $message = 'No salaries finalized yet';
        }
        
        return [
            'can_execute' => $canExecute,
            'status' => $status,
            'message' => $message,
            'finalized_count' => $finalizedCount,
            'total_count' => $employees,
            'executed_count' => $executedCount,
            'execution_date' => $latestExecutionDate,
        ];
    }

    /**
     * Get current month's execution date
     */
    private function getCurrentMonthExecutionDate($context)
    {
        // Get the first active payroll configuration
        $payrollConfig = PayrollExecution::where('institute_id', $context['institute_id'])
            ->where('branch_id', $context['branch_id'])
            ->where('status', 'active')
            ->first();
        
        if ($payrollConfig && $payrollConfig->execution_day) {
            $currentDate = Carbon::now();
            $executionDay = $payrollConfig->execution_day;
            $executionDate = Carbon::create($currentDate->year, $currentDate->month, $executionDay);
            
            if ($executionDay > $executionDate->daysInMonth) {
                $executionDate = Carbon::create($currentDate->year, $currentDate->month, $executionDate->daysInMonth);
            }
            
            return $executionDate->format('Y-m-d');
        }
        
        // Default to 10th of current month
        return Carbon::now()->format('Y-m-10');
    }
}