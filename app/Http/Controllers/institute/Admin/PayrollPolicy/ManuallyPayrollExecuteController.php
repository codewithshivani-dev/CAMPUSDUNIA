<?php

namespace App\Http\Controllers\institute\Admin\PayrollPolicy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayrollExecution;
use App\Models\PayrollExecutionHistory;
use App\Models\SalaryReview;
use App\Models\FinalSalarySlip;
use App\Models\EmployeeDetails;
use App\Models\Departments;
use App\Traits\InstituteBranchAccess;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ManuallyPayrollExecuteController extends Controller
{
    use InstituteBranchAccess;

    /**
     * Display payroll execution main page
     */
    public function index()
    {
        $context = $this->getInstituteBranchContext();
        $currentDate = Carbon::now();
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;
        
        // Get all departments with their payroll configurations
        $departments = Departments::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->with(['payrollExecution' => function($query) {
                $query->where('status', 'active');
            }])
            ->get();
        
        // Calculate execution status for each department
        $departmentStatus = [];
        foreach ($departments as $department) {
            $status = $this->getDepartmentExecutionStatus($department, $currentYear, $currentMonth, $context);
            $departmentStatus[$department->department_id] = $status;
        }
        
        // Get recent execution histories
        $recentExecutions = PayrollExecutionHistory::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get current month's execution date
        $executionDate = $this->getExecutionDate($context, $currentYear, $currentMonth);
        
        return view('instituteAdmin.Payroll.PayrollExecutionIndex', compact(
            'departments',
            'departmentStatus',
            'recentExecutions',
            'executionDate',
            'currentYear',
            'currentMonth'
        ));
    }
    
    public function showExecuteForm(Request $request)
    {
        $request->validate([
            'type' => 'required|in:department,individual,multiple',
            'department_id' => 'required_if:type,department|exists:departments,department_id',
            'employee_id' => 'required_if:type,individual|exists:employee_details,employee_id',
            'employee_ids' => 'required_if:type,multiple|string',
        ]);
        
        $context = $this->getInstituteBranchContext();
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', Carbon::now()->month);
        
        $executionDate = $this->getExecutionDate($context, $year, $month);
        $plannedDate = Carbon::parse($executionDate);
        
        $type = $request->type;
        $employees = collect();
        $department = null;
        $selectedEmployee = null;
        $totalEmployees = 0;
        $readyCount = 0;
        $pendingCount = 0;
        $preSelectedEmployeeIds = [];
        
        if ($type == 'department') {
            // Department-wise execution
            $department = Departments::where('department_id', $request->department_id)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            $employees = EmployeeDetails::where('employee_details.department_id', $request->department_id)
                ->where('employee_details.institute_id', $context['institute_id'])
                ->where('employee_details.status', 'active')
                ->leftJoin('departments', 'departments.department_id', '=', 'employee_details.department_id')
                ->select('employee_details.*', 'departments.department as department_name')
                ->get();
            
            $totalEmployees = $employees->count();
            
            foreach ($employees as $employee) {
                $salaryReview = SalaryReview::where('institute_id', $context['institute_id'])
                    ->where('branch_id', $context['branch_id'])
                    ->where('employee_id', $employee->employee_id)
                    ->where('year', $year)
                    ->where('month', $month)
                    ->where('review_status', 'finalized')
                    ->first();
                
                $existingSlip = FinalSalarySlip::where('employee_id', $employee->employee_id)
                    ->where('year', $year)
                    ->where('month', $month)
                    ->first();
                
                $employee->salary_finalized = !is_null($salaryReview);
                $employee->already_executed = !is_null($existingSlip);
                $employee->can_execute = $employee->salary_finalized && !$employee->already_executed;
                
                if ($employee->can_execute) {
                    $readyCount++;
                } elseif (!$employee->salary_finalized) {
                    $pendingCount++;
                }
            }
        } 
        elseif ($type == 'multiple') {
            // Multiple employees execution
            $employeeIdsString = $request->get('employee_ids');
            $employeeIds = explode(',', $employeeIdsString);
            
            $employees = EmployeeDetails::whereIn('employee_details.employee_id', $employeeIds)
                ->where('employee_details.institute_id', $context['institute_id'])
                ->where('employee_details.status', 'active')
                ->leftJoin('departments', 'departments.department_id', '=', 'employee_details.department_id')
                ->select('employee_details.*', 'departments.department as department_name')
                ->get();
            
            $totalEmployees = $employees->count();
            
            foreach ($employees as $employee) {
                $salaryReview = SalaryReview::where('institute_id', $context['institute_id'])
                    ->where('branch_id', $context['branch_id'])
                    ->where('employee_id', $employee->employee_id)
                    ->where('year', $year)
                    ->where('month', $month)
                    ->where('review_status', 'finalized')
                    ->first();
                
                $existingSlip = FinalSalarySlip::where('employee_id', $employee->employee_id)
                    ->where('year', $year)
                    ->where('month', $month)
                    ->first();
                
                $employee->salary_finalized = !is_null($salaryReview);
                $employee->already_executed = !is_null($existingSlip);
                $employee->can_execute = $employee->salary_finalized && !$employee->already_executed;
                
                if ($employee->can_execute) {
                    $readyCount++;
                    $preSelectedEmployeeIds[] = $employee->employee_id;
                } elseif (!$employee->salary_finalized) {
                    $pendingCount++;
                }
            }
            
            // Create a virtual department for display
            $department = (object)[
                'department' => 'Multiple Employees (' . $totalEmployees . ' selected)',
                'department_id' => 'multiple'
            ];
        }
        else {
            // Individual execution
            $selectedEmployee = EmployeeDetails::where('employee_details.employee_id', $request->employee_id)
                ->where('employee_details.institute_id', $context['institute_id'])
                ->leftJoin('departments', 'departments.department_id', '=', 'employee_details.department_id')
                ->select('employee_details.*', 'departments.department as department_name')
                ->first();
            
            if ($selectedEmployee) {
                $salaryReview = SalaryReview::where('salary_reviews.institute_id', $context['institute_id'])
                    ->where('salary_reviews.branch_id', $context['branch_id'])
                    ->where('salary_reviews.employee_id', $selectedEmployee->employee_id)
                    ->where('salary_reviews.year', $year)
                    ->where('salary_reviews.month', $month)
                    ->where('salary_reviews.review_status', 'finalized')
                    ->first();
                
                $existingSlip = FinalSalarySlip::where('final_salary_slips.employee_id', $selectedEmployee->employee_id)
                    ->where('final_salary_slips.year', $year)
                    ->where('final_salary_slips.month', $month)
                    ->first();
                
                $selectedEmployee->salary_finalized = !is_null($salaryReview);
                $selectedEmployee->already_executed = !is_null($existingSlip);
                $selectedEmployee->can_execute = $selectedEmployee->salary_finalized && !$selectedEmployee->already_executed;
            }
        }
        
        return view('instituteAdmin.Payroll.ExecutePayrollForm', compact(
            'type',
            'employees',
            'department',
            'selectedEmployee',
            'plannedDate',
            'year',
            'month',
            'totalEmployees',
            'readyCount',
            'pendingCount',
            'preSelectedEmployeeIds'
        ));
    }
    
    /**
     * Execute payroll for department, multiple employees, or individual
     */
    public function executePayroll(Request $request)
    {
        $request->validate([
            'type' => 'required|in:department,individual,multiple',
            'department_id' => 'required_if:type,department|exists:departments,department_id',
            'employee_ids' => 'required_if:type,department|array',
            'multiple_employee_ids' => 'required_if:type,multiple|array',
            'employee_id' => 'required_if:type,individual|exists:employee_details,employee_id',
            'year' => 'required|integer',
            'month' => 'required|integer',
            'planned_date' => 'required|date',
        ]);
        
        $context = $this->getInstituteBranchContext();
        $actualDate = Carbon::now();
        $plannedDate = Carbon::parse($request->planned_date);
        
        $successCount = 0;
        $failedEmployees = [];
        $executionRecords = [];
        
        DB::beginTransaction();
        
        try {
            if ($request->type == 'department') {
                // ========== DEPARTMENT-WISE EXECUTION ==========
                $employeeIds = $request->employee_ids;
                $department = Departments::where('department_id', $request->department_id)->first();
                
                // Get payroll configuration for department
                $payrollConfig = PayrollExecution::where('department_id', $request->department_id)
                    ->where('institute_id', $context['institute_id'])
                    ->first();
                
                $financialYear = $payrollConfig ? $payrollConfig->financial_year : null;
                
                // Generate ONE execution ID for the entire department batch
                $batchExecutionId = $this->generateExecutionId();
                
                // Create SEPARATE execution history record for EACH employee with SAME execution_id
                foreach ($employeeIds as $employeeId) {
                    $employee = EmployeeDetails::where('employee_id', $employeeId)
                        ->where('institute_id', $context['institute_id'])
                        ->first();
                    
                    if (!$employee) {
                        $failedEmployees[] = [
                            'employee_id' => $employeeId,
                            'employee_name' => 'Unknown',
                            'reason' => 'Employee not found'
                        ];
                        continue;
                    }
                    
                    // Create individual execution history record for this employee with SAME batch ID
                    $executionHistory = PayrollExecutionHistory::create([
                        'execution_id' => $batchExecutionId, // SAME for all employees in this department
                        'institute_id' => $context['institute_id'],
                        'branch_id' => $context['branch_id'],
                        'department_id' => $request->department_id,
                        'employee_id' => $employeeId,
                        'employee_name' => $employee->name ?? null,
                        'employee_code' => $employee->employee_code ?? null,
                        'year' => $request->year,
                        'month' => $request->month,
                        'financial_year' => $financialYear,
                        'planned_execution_date' => $plannedDate,
                        'actual_execution_date' => $actualDate,
                        'execution_type' => 'department',
                        'status' => 'processing',
                        'notes' => $request->notes ?? "Payroll executed for employee in department: " . ($department->department ?? 'N/A'),
                        'executed_by' => auth()->user()->id,
                    ]);
                    
                    $executionRecords[] = $executionHistory;
                    
                    // Execute payroll for this employee
                    $result = $this->executeSingleEmployeePayroll(
                        $employeeId, 
                        $request->year, 
                        $request->month, 
                        $executionHistory->id,
                        $context
                    );
                    
                    if ($result['success']) {
                        $successCount++;
                        $executionHistory->update([
                            'status' => 'completed',
                            'execution_summary' => [
                                'total_employees' => 1,
                                'successful' => 1,
                                'failed' => 0,
                            ]
                        ]);
                    } else {
                        $failedEmployees[] = [
                            'employee_id' => $employeeId,
                            'employee_name' => $result['employee_name'] ?? $employee->name,
                            'reason' => $result['message']
                        ];
                        $executionHistory->update([
                            'status' => 'failed',
                            'execution_summary' => [
                                'total_employees' => 1,
                                'successful' => 0,
                                'failed' => 1,
                            ],
                            'failed_employees' => [[
                                'employee_id' => $employeeId,
                                'employee_name' => $result['employee_name'] ?? $employee->name,
                                'reason' => $result['message']
                            ]]
                        ]);
                    }
                }
            } 
            elseif ($request->type == 'multiple') {
                // ========== MULTIPLE EMPLOYEES EXECUTION ==========
                $employeeIds = $request->multiple_employee_ids;
                
                // Generate ONE execution ID for the entire multiple employees batch
                $batchExecutionId = $this->generateExecutionId();
                
                // Create SEPARATE execution history record for EACH selected employee with SAME execution_id
                foreach ($employeeIds as $employeeId) {
                    $employee = EmployeeDetails::where('employee_id', $employeeId)
                        ->where('institute_id', $context['institute_id'])
                        ->first();
                    
                    if (!$employee) {
                        $failedEmployees[] = [
                            'employee_id' => $employeeId,
                            'employee_name' => 'Unknown',
                            'reason' => 'Employee not found'
                        ];
                        continue;
                    }
                    
                    // Get payroll configuration using employee's department
                    $payrollConfig = PayrollExecution::where('department_id', $employee->department_id)
                        ->where('institute_id', $context['institute_id'])
                        ->first();

                    $financialYear = $payrollConfig ? $payrollConfig->financial_year : null;
                    
                    // Create individual execution history record for this employee with SAME batch ID
                    $executionHistory = PayrollExecutionHistory::create([
                        'execution_id' => $batchExecutionId, // SAME for all selected employees
                        'institute_id' => $context['institute_id'],
                        'branch_id' => $context['branch_id'],
                        'department_id' => $employee->department_id,
                        'employee_id' => $employeeId,
                        'employee_name' => $employee->name ?? null,
                        'employee_code' => $employee->employee_code ?? null,
                        'year' => $request->year,
                        'month' => $request->month,
                        'financial_year' => $financialYear,
                        'planned_execution_date' => $plannedDate,
                        'actual_execution_date' => $actualDate,
                        'execution_type' => 'multiple',
                        'status' => 'processing',
                        'notes' => $request->notes ?? "Manual payroll execution for selected employee",
                        'executed_by' => auth()->user()->id,
                    ]);
                    
                    $executionRecords[] = $executionHistory;
                    
                    // Execute payroll for this employee
                    $result = $this->executeSingleEmployeePayroll(
                        $employeeId, 
                        $request->year, 
                        $request->month, 
                        $executionHistory->id,
                        $context
                    );
                    
                    if ($result['success']) {
                        $successCount++;
                        $executionHistory->update([
                            'status' => 'completed',
                            'execution_summary' => [
                                'total_employees' => 1,
                                'successful' => 1,
                                'failed' => 0,
                            ]
                        ]);
                    } else {
                        $failedEmployees[] = [
                            'employee_id' => $employeeId,
                            'employee_name' => $result['employee_name'] ?? $employee->name,
                            'reason' => $result['message']
                        ];
                        $executionHistory->update([
                            'status' => 'failed',
                            'execution_summary' => [
                                'total_employees' => 1,
                                'successful' => 0,
                                'failed' => 1,
                            ],
                            'failed_employees' => [[
                                'employee_id' => $employeeId,
                                'employee_name' => $result['employee_name'] ?? $employee->name,
                                'reason' => $result['message']
                            ]]
                        ]);
                    }
                }
            } 
            else {
                // ========== INDIVIDUAL EXECUTION ==========
                $employee = EmployeeDetails::where('employee_id', $request->employee_id)
                    ->where('institute_id', $context['institute_id'])
                    ->first();
                
                if (!$employee) {
                    throw new \Exception('Employee not found');
                }
                
                $executionId = $this->generateExecutionId();
                
                // Get payroll configuration using employee's department_id
                $payrollConfig = PayrollExecution::where('department_id', $employee->department_id)
                    ->where('institute_id', $context['institute_id'])
                    ->first();
                
                $financialYear = $payrollConfig ? $payrollConfig->financial_year : null;
                
                // Create single execution history record for the individual employee
                $executionHistory = PayrollExecutionHistory::create([
                    'execution_id' => $executionId,
                    'institute_id' => $context['institute_id'],
                    'branch_id' => $context['branch_id'],
                    'employee_id' => $request->employee_id,
                    'employee_name' => $employee->name ?? null,
                    'employee_code' => $employee->employee_code ?? null,
                    'department_id' => $employee->department_id,
                    'year' => $request->year,
                    'month' => $request->month,
                    'financial_year' => $financialYear,
                    'planned_execution_date' => $plannedDate,
                    'actual_execution_date' => $actualDate,
                    'execution_type' => 'individual',
                    'status' => 'processing',
                    'notes' => $request->notes ?? "Manual payroll execution for individual employee",
                    'executed_by' => auth()->user()->id,
                ]);
                
                $result = $this->executeSingleEmployeePayroll(
                    $request->employee_id, 
                    $request->year, 
                    $request->month, 
                    $executionHistory->id,
                    $context
                );
                
                if ($result['success']) {
                    $successCount = 1;
                    $executionHistory->update([
                        'status' => 'completed',
                        'execution_summary' => [
                            'total_employees' => 1,
                            'successful' => 1,
                            'failed' => 0,
                        ]
                    ]);
                } else {
                    $executionHistory->update([
                        'status' => 'failed',
                        'execution_summary' => [
                            'total_employees' => 1,
                            'successful' => 0,
                            'failed' => 1,
                        ],
                        'failed_employees' => [[
                            'employee_id' => $request->employee_id,
                            'employee_name' => $result['employee_name'] ?? $employee->name,
                            'reason' => $result['message']
                        ]]
                    ]);
                }
            }
            
            DB::commit();
            
            // Prepare success message
            $message = $successCount > 0 
                ? "Payroll executed successfully for {$successCount} employee(s)." 
                : "Payroll execution failed. No employees were processed.";
            
            if (count($failedEmployees) > 0) {
                $message .= " Failed: " . count($failedEmployees) . " employee(s).";
            }
            
            return redirect()->route('execution.history')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payroll execution error: ' . $e->getMessage(), [
                'type' => $request->type,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('payroll.viewPage')
                ->with('error', 'Error executing payroll: ' . $e->getMessage());
        }
    }
    
    /**
     * Execute payroll for a single employee
     */
    private function executeSingleEmployeePayroll($employeeId, $year, $month, $executionHistoryId, $context)
    {
        try {
            $employee = EmployeeDetails::where('employee_id', $employeeId)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            if (!$employee) {
                return [
                    'success' => false,
                    'employee_name' => 'Unknown',
                    'message' => 'Employee not found'
                ];
            }
            
            // Check if salary is finalized
            $salaryReview = SalaryReview::where('institute_id', $context['institute_id'])
                ->where('branch_id', $context['branch_id'])
                ->where('employee_id', $employeeId)
                ->where('year', $year)
                ->where('month', $month)
                ->where('review_status', 'finalized')
                ->first();
            
            if (!$salaryReview) {
                return [
                    'success' => false,
                    'employee_name' => $employee->name,
                    'message' => 'Salary not finalized for ' . Carbon::create($year, $month, 1)->format('F Y')
                ];
            }
            
            // Check if already executed
            $existingSlip = FinalSalarySlip::where('employee_id', $employeeId)
                ->where('year', $year)
                ->where('month', $month)
                ->first();
            
            if ($existingSlip) {
                return [
                    'success' => false,
                    'employee_name' => $employee->name,
                    'message' => 'Payroll already executed for this period'
                ];
            }
            
            // Get payroll configuration using employee's department
            $payrollConfig = PayrollExecution::where('department_id', $employee->department_id)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            $salaryMonth = Carbon::create($year, $month, 1)->format('Y-m');
            $slipId = $this->generateFinalSlipId($salaryReview, $salaryMonth);
            
            // Prepare and create final salary slip
            $slipData = $this->prepareFinalSlipData($salaryReview, $employee, $payrollConfig, $salaryMonth, $slipId, $executionHistoryId);
            
            // Add institute and branch info
            $slipData['institute_id'] = $context['institute_id'];
            $slipData['branch_id'] = $context['branch_id'];
            
            FinalSalarySlip::create($slipData);
            
            // Update payroll execution last run
            if ($payrollConfig) {
                $payrollConfig->update(['last_run_at' => now()]);
            }
            
            return [
                'success' => true,
                'employee_name' => $employee->name,
                'message' => 'Successfully executed',
                'slip_id' => $slipId
            ];
            
        } catch (\Exception $e) {
            Log::error('Single employee payroll execution error: ' . $e->getMessage(), [
                'employee_id' => $employeeId,
                'year' => $year,
                'month' => $month,
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'employee_name' => $employee->name ?? 'Unknown',
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Display execution history
     */
    public function history(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        $year = $request->get('year');
        $month = $request->get('month');
        $status = $request->get('status');
        $financialYear = $request->get('financial_year');
        $departmentId = $request->get('department_id');
        $employeeId = $request->get('employee_id');
        
        $query = PayrollExecutionHistory::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->with(['department', 'employee']); 
        
        // Filter by financial year
        if ($financialYear) {
            $query->where('financial_year', $financialYear);
        }
        
        // Filter by year
        if ($year) {
            $query->where('year', $year);
        }
        
        // Filter by month
        if ($month) {
            $query->where('month', $month);
        }
        
        // Filter by status
        if ($status) {
            $query->where('status', $status);
        }
        
        // Filter by department
        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }
        
        // Filter by employee
        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }
        
        $executions = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get available years for filter
        $availableYears = range(Carbon::now()->subYears(2)->year, Carbon::now()->year);
        
        // Get financial years from payroll_executions table
        $financialYears = PayrollExecution::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->whereNotNull('financial_year')
            ->distinct()
            ->pluck('financial_year')
            ->toArray();
        
        // Get months for filter
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = Carbon::create()->month($i)->format('F');
        }
        
        // Get departments list for filter
        $departmentsList = Departments::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->orderBy('department')
            ->get();
        
        // Get employees list for filter
        $employeesList = EmployeeDetails::where('institute_id', $context['institute_id'])
            ->where('status', 'active')
            ->select('employee_id', 'name', 'employee_code')
            ->orderBy('name')
            ->get();
        
        return view('instituteAdmin.Payroll.PayrollExecutionHistory', compact(
            'executions',
            'year',
            'month',
            'status',
            'financialYear',
            'departmentId',
            'employeeId',
            'availableYears',
            'financialYears',
            'months',
            'departmentsList',
            'employeesList'
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
                'remaining_count' => 0,
                'progress_percentage' => 0
            ];
        }
        
        // Get finalized salary count
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
        
        // Calculate remaining count
        $remainingCount = $employees - $executedCount;
        $progressPercentage = $employees > 0 ? ($executedCount / $employees) * 100 : 0;
        
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
            'remaining_count' => $remainingCount,
            'progress_percentage' => round($progressPercentage, 1)
        ];
    }
    
    /**
     * Get execution date for specific year and month
     */
    private function getExecutionDate($context, $year = null, $month = null)
    {
        // Get the first active payroll configuration
        $payrollConfig = PayrollExecution::where('institute_id', $context['institute_id'])
            ->where('branch_id', $context['branch_id'])
            ->where('status', 'active')
            ->first();
        
        // Use provided year/month or default to current date
        $targetYear = $year ?? Carbon::now()->year;
        $targetMonth = $month ?? Carbon::now()->month;
        
        if ($payrollConfig && $payrollConfig->execution_day) {
            $executionDay = $payrollConfig->execution_day;
            $executionDate = Carbon::create($targetYear, $targetMonth, $executionDay);
            
            if ($executionDay > $executionDate->daysInMonth) {
                $executionDate = Carbon::create($targetYear, $targetMonth, $executionDate->daysInMonth);
            }
            
            return $executionDate->format('Y-m-d');
        }
        
        // Default to 10th of target month
        return Carbon::create($targetYear, $targetMonth, 10)->format('Y-m-d');
    }
    
    /**
     * Generate execution ID
     */
    private function generateExecutionId()
    {
        $date = Carbon::now();
        $prefix = 'PAYEXEC';
        $dateStr = $date->format('Ym');
        $random = strtoupper(substr(uniqid(), -6));
        
        return $prefix . '-' . $dateStr . '-' . $random;
    }
    
    /**
     * Generate final slip ID
     */
    private function generateFinalSlipId($salaryReview, $salaryMonth)
    {
        $date = Carbon::parse($salaryMonth);
        $yearMonth = $date->format('Ym');
        $employeeCode = substr($salaryReview->employee_id, -6);
        $timestamp = now()->format('His');
        
        return "FINAL-{$yearMonth}-{$employeeCode}-{$timestamp}";
    }
    
    /**
     * Prepare final slip data
     */
    private function prepareFinalSlipData($salaryReview, $employee, $payrollConfig, $salaryMonth, $slipId, $executionHistoryId)
    {
        $finalizationDetails = $salaryReview->finalization_details ?? [];
        
        // Calculate pay date based on execution day
        $payDate = $this->calculatePayDate($salaryReview->year, $salaryReview->month, $payrollConfig);
        
        return [
            'slip_id' => $slipId,
            'employee_id' => $employee->employee_id,
            'employee_name' => $employee->name,
            'employee_code' => $employee->employee_code,
            'salary_review_id' => $salaryReview->id,
            'execution_history_id' => $executionHistoryId,
            'year' => $salaryReview->year,
            'month' => $salaryReview->month,
            'salary_month' => $salaryMonth,
            
            // Salary Amounts
            'basic_salary' => $salaryReview->basic_salary ?? 0,
            'gross_salary' => $salaryReview->gross_salary ?? 0,
            'net_salary' => $salaryReview->monthly_net_salary ?? 0,
            'final_payable' => $salaryReview->final_payable_salary ?? 0,
            
            // Deductions
            'standard_deductions' => $finalizationDetails['standard_deductions_breakdown'] ?? [],
            'attendance_deductions' => $finalizationDetails['attendance_deductions_breakdown'] ?? [],
            'other_deductions' => $salaryReview->other_deductions_details ?? [],
            'total_deductions' => $salaryReview->total_deductions ?? 0,
            
            // Earnings
            'earnings_breakdown' => $salaryReview->earnings_breakdown ?? [],
            
            // Attendance Summary
            'attendance_summary' => $salaryReview->attendance_summary ?? [],
            
            // Leave Breakdown
            'leave_breakdown' => $salaryReview->leave_breakdown ?? [],
            
            // Bank Details
            'bank_name' => $employee->bank_name ?? null,
            'account_number' => $employee->bank_account_number ? 'XXXX' . substr($employee->bank_account_number, -4) : null,
            'ifsc_code' => $employee->ifsc_code ?? null,
            'pan_number' => $employee->pan_number ?? null,
            
            // Payroll Execution Info
            'additional_details' => [
                'payroll_cycle' => $payrollConfig->payroll_cycle ?? 'monthly',
                'cycle_days' => $payrollConfig->cycle_days ?? null,
                'execution_day' => $payrollConfig->execution_day ?? null,
                'pay_date' => $payDate,
                'financial_year' => $payrollConfig->financial_year ?? null,
                'salary_review_finalized_at' => $salaryReview->finalized_at,
                'salary_review_finalized_by' => $salaryReview->finalized_by,
                'calculation_note' => $finalizationDetails['calculation_formula'] ?? null,
                'execution_type' => 'manual',
                'executed_by' => auth()->user()->name ?? 'System'
            ],
            
            // Generation Info
            'generation_type' => 'manual',
            'generated_at' => now(),
            'generated_by' => auth()->user()->id,
            
            // Institute/Branch
            'institute_id' => $salaryReview->institute_id,
            'branch_id' => $salaryReview->branch_id,
        ];
    }
    
    /**
     * Calculate pay date
     */
    private function calculatePayDate($year, $month, $payrollConfig)
    {
        if (!$payrollConfig || !$payrollConfig->execution_day) {
            return Carbon::create($year, $month, 1)->endOfMonth()->format('d M Y');
        }
        
        $executionDay = $payrollConfig->execution_day;
        $payDate = Carbon::create($year, $month, $executionDay);
        
        if ($executionDay > $payDate->daysInMonth) {
            $payDate = Carbon::create($year, $month, $payDate->daysInMonth);
        }
        
        return $payDate->format('d M Y');
    }

    /**
     * View execution details
     */
    public function viewExecution($id)
    {
        $context = $this->getInstituteBranchContext();
        
        $execution = PayrollExecutionHistory::where('id', $id)
            ->where('institute_id', $context['institute_id'])
            ->with(['department', 'employee', 'executedBy'])
            ->first();
        
        if (!$execution) {
            return redirect()->route('execution.history')
                ->with('error', 'Execution record not found');
        }
        
        // Get all slips - if execution_history_id column exists
        $slips = collect(); // Initialize empty collection
        
        // Try to get slips by execution_history_id if column exists
        try {
            $slips = FinalSalarySlip::where('execution_history_id', $id)->get();
        } catch (\Exception $e) {
            // If column doesn't exist, get slips by period and employee/department
            if ($execution->execution_type == 'individual' && $execution->employee_id) {
                $slips = FinalSalarySlip::where('employee_id', $execution->employee_id)
                    ->where('year', $execution->year)
                    ->where('month', $execution->month)
                    ->orderBy('generated_at', 'desc')
                    ->get();
            } elseif ($execution->department_id) {
                $employeeIds = EmployeeDetails::where('department_id', $execution->department_id)
                    ->where('status', 'active')
                    ->pluck('employee_id');
                    
                $slips = FinalSalarySlip::whereIn('employee_id', $employeeIds)
                    ->where('year', $execution->year)
                    ->where('month', $execution->month)
                    ->orderBy('generated_at', 'desc')
                    ->get();
            }
        }
        
        return view('instituteAdmin.Payroll.PayrollExecutionDetail', compact('execution', 'slips'));
    }
}