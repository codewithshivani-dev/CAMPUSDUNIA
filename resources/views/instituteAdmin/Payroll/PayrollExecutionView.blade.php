@extends('instituteAdmin.Payroll.PayrollManagement')

@section('payroll-content')
<title>Payroll Execution</title>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --primary-color: #4361ee;
    --secondary-color: #3a0ca3;
    --success-gradient: linear-gradient(135deg, #10b981, #059669);
    --success-color: #10b981;
    --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
    --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
    --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
    --border-color: #e2e8f0;
    --text-dark: #1e293b;
    --text-muted: #64748b;
    --bg-light: #f8fafc;
    --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
    --transition: all 0.2s ease;
}

.card {
    padding: 10px;
    border: none;
    border-radius: 24px;
    box-shadow: var(--card-shadow);
    background: #ffffff;
    overflow: hidden;
}

.card-header-view {
    background: var(--primary-gradient) !important;
    border-bottom: none;
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    border-radius: 20px 20px 0 0;
}

.card-header-view .form-title {
    font-weight: 700;
    font-size: 1.45rem;
    color: white;
    margin: 0;
}

.card-header-view .form-title i {
    color: white;
    margin-right: 10px;
}

.card-header-view .text-muted {
    color: rgba(255, 255, 255, 0.85) !important;
}

.card-header-view .btn-outline-primary {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    transition: var(--transition);
}

.card-header-view .btn-outline-primary:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-1px);
}

/* Filter Section */
.filter-section {
    background: var(--bg-light);
    padding: 20px;
    border-radius: 16px;
    margin-block: 20px;
    border: 1px solid var(--border-color);
}

.filter-label {
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--text-dark);
    margin-bottom: 8px;
    display: block;
}

.filter-select {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid var(--border-color);
    border-radius: 12px;
    font-size: 0.9rem;
    background: white;
    transition: var(--transition);
}

.filter-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.btn-execute-main {
    background: var(--success-gradient);
    border: none;
    border-radius: 50px;
    padding: 12px 28px;
    font-weight: 600;
    color: white;
    transition: var(--transition);
}

.btn-execute-main:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

/* Department Card */
.department-card {
    border: 1px solid var(--border-color);
    border-radius: 16px;
    margin-bottom: 16px;
    transition: var(--transition);
    background: white;
}

.department-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.department-header {
    padding: 16px 20px;
    background: #fafbfc;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    cursor: pointer;
    transition: var(--transition);
}

.department-header:hover {
    background: #f8fafc;
}

.department-name {
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--text-dark);
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.execution-badge {
    background: #e0f2fe;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 500;
    color: #0369a1;
}

.execution-badge i {
    margin-right: 5px;
}

.department-stats {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.stat-badge {
    font-size: 0.75rem;
    padding: 4px 12px;
    border-radius: 20px;
    background: #f1f5f9;
    color: var(--text-muted);
}

.btn-execute-dept {
    background: var(--success-color);
    border: none;
    border-radius: 30px;
    padding: 6px 16px;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
    transition: var(--transition);
}

.btn-execute-dept:hover:not(:disabled) {
    transform: translateY(-1px);
    filter: brightness(1.05);
}

.btn-execute-dept:disabled {
    background: #cbd5e1;
    cursor: not-allowed;
}

.btn-edit-dept {
    background: var(--primary-color);
    border: none;
    border-radius: 30px;
    padding: 6px 16px;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: var(--transition);
}

.btn-edit-dept:hover {
    background: #2563eb;
    color: white;
    transform: translateY(-1px);
}

.btn-logs {
    background: #64748b;
    border: none;
    border-radius: 30px;
    padding: 6px 16px;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
    cursor: pointer;
    transition: var(--transition);
}

.btn-logs:hover {
    background: #475569;
    transform: translateY(-1px);
}

.department-body {
    padding: 16px 20px;
    display: none;
}

.department-body.show {
    display: block;
}

.department-logs {
    padding: 0 20px 16px 20px;
    display: none;
}

.department-logs.show {
    display: block;
}

.employee-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}

.status-finalized {
    background: #d1fae5;
    color: #059669;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.status-pending {
    background: #fef3c7;
    color: #d97706;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.status-executed {
    background: #dbeafe;
    color: #2563eb;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-individual {
    background: var(--primary-color);
    border: none;
    border-radius: 20px;
    padding: 5px 14px;
    font-size: 0.7rem;
    font-weight: 500;
    color: white;
    transition: var(--transition);
}

.btn-individual:hover:not(:disabled) {
    transform: translateY(-1px);
    filter: brightness(1.05);
}

.btn-individual:disabled {
    background: #cbd5e1;
    cursor: not-allowed;
}

.warning-message {
    background: #fef3c7;
    border-left: 4px solid #f59e0b;
    padding: 10px 15px;
    border-radius: 10px;
    font-size: 0.75rem;
    margin-top: 10px;
}

.toggle-icon {
    transition: transform 0.2s;
}

/* Logs Container */
.logs-container {
    background: var(--bg-light);
    border-radius: 12px;
    padding: 15px;
    border: 1px solid var(--border-color);
}

.logs-table {
    width: 100%;
    font-size: 0.75rem;
    border-collapse: collapse;
}

.logs-table th {
    background: #f1f5f9;
    padding: 10px 12px;
    font-weight: 600;
    color: var(--text-muted);
    border-bottom: 1px solid var(--border-color);
}

.logs-table td {
    padding: 8px 12px;
    border-bottom: 1px solid var(--border-color);
}

.logs-table tr:last-child td {
    border-bottom: none;
}

/* Modal Styles */
.execution-type-card {
    transition: var(--transition);
    background: white;
    min-height: 295px;
    cursor: pointer;
}

.execution-type-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.execution-type-card.selected {
    border-color: var(--success-color) !important;
    background: #f0fdf4;
}

.icon-circle {
    transition: var(--transition);
}

.execution-type-card:hover .icon-circle {
    transform: scale(1.05);
}

.radio-custom {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    background: var(--bg-light);
    border-radius: 50px;
    transition: var(--transition);
}

.radio-custom:hover {
    background: #e2e8f0;
}

.form-check-input:checked {
    background-color: var(--success-color);
    border-color: var(--success-color);
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    backdrop-filter: blur(3px);
}

.loading-content {
    background: white;
    padding: 30px 40px;
    border-radius: 20px;
    text-align: center;
    animation: fadeInUp 0.3s ease;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Status badges */
.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 500;
    display: inline-block;
}

.employee-checkbox-item {
    transition: var(--transition);
}

.employee-checkbox-item:hover {
    background: var(--bg-light);
}

.employee-checkbox-multi:disabled + label {
    cursor: not-allowed;
}

/* Alert overrides */
.alert-info {
    background: #eff6ff;
    border: none;
    border-radius: 12px;
}

/* Responsive */
@media (max-width: 768px) {
    .card-header-view {
        flex-direction: column;
        text-align: center;
    }
    
    .department-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .department-stats {
        flex-wrap: wrap;
    }
    
    .employee-row {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
    
    .employee-row > div {
        width: 100%;
    }
}
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-header-view">
            <div>
                <h3 class="form-title"><i class="bi bi-calculator-fill"></i> Payroll Execution</h3>
                <p class="text-muted mb-0 mt-2">Manage and execute payroll for departments and employees</p>
            </div>
            <div>
                <a href="{{ route('execution.history') }}" class="btn btn-outline-primary me-2">
                    <i class="bi bi-clock-history me-2"></i>History
                </a>
                <button type="button" class="btn-execute-main" onclick="openExecuteModal()">
                    <i class="bi bi-play-circle me-2"></i>Execute Payroll
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('payroll.viewPage') }}" id="filterForm">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="filter-label"><i class="bi bi-calendar-year me-1"></i> Select Financial Year</label>
                        <select name="year" class="filter-select" onchange="this.form.submit()">
                            @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="filter-label"><i class="bi bi-calendar3 me-1"></i> Select Month</label>
                        <select name="month" class="filter-select" onchange="this.form.submit()">
                            <option value="">-- All Months --</option>
                            @foreach($months as $monthNum => $monthName)
                            <option value="{{ $monthNum }}" {{ $selectedMonth == $monthNum ? 'selected' : '' }}>
                                {{ $monthName }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="filter-label"><i class="bi bi-building me-1"></i> Department</label>
                        <select name="department_id" class="filter-select" onchange="this.form.submit()">
                            <option value="">-- All Departments --</option>
                            @foreach($departments as $dept)
                            <option value="{{ $dept->department_id }}" {{ $filterDepartmentId == $dept->department_id ? 'selected' : '' }}>
                                {{ $dept->department }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('payroll.viewPage') }}" class="btn btn-secondary w-100" style="background: #fff;  border-radius: 12px; padding: 10px; color:#475569;">
                            <i class="bi bi-arrow-repeat me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Execution Date Info -->
        <div class="mb-3">
            <div class="alert alert-info">
                <i class="bi bi-calendar-check me-2"></i>
                <strong>Selected Period:</strong>
                {{ Carbon\Carbon::create((int)$selectedYear, (int)$selectedMonth, 1)->format('F Y') }}
            </div>
        </div>

        <!-- Departments List -->
        <div>
            @forelse($departments as $department)
            @php
                $deptStatus = $departmentStatus[$department->department_id] ?? null;
                $canExecuteDept = $deptStatus && $deptStatus['can_execute'];
                $hasAnyEmployee = $deptStatus && $deptStatus['total_count'] > 0;
                $executionDay = $department->payrollExecution->execution_day ?? null;
                $payrollConfig = $department->payrollExecution;
                $logs = $payrollConfig ? $payrollConfig->logs : collect();
                $deptCleanId = preg_replace('/[^a-zA-Z0-9]/', '', $department->department_id);
            @endphp

            <div class="department-card" data-dept-id="{{ $department->department_id }}">
                <div class="department-header" onclick="toggleDepartment('dept_{{ $deptCleanId }}')">
                    <div class="department-name">
                        <i class="bi bi-building fs-5" style="color: var(--primary-color);"></i>
                        <div class="d-flex" style="flex-direction: column;">
                            <span>{{ $department->department }}</span>
                            <span class="stat-badge" style="width: max-content;">
                                <i class="bi bi-people"></i> {{ $deptStatus['total_count'] ?? 0 }} Emp
                            </span>
                        </div>
                        @if($executionDay)
                        <span class="execution-badge">
                            <i class="bi bi-calendar-check"></i> Executes on
                            {{ $executionDay }}{{ date('S', mktime(0,0,0,0,$executionDay)) }} of month
                        </span>
                        @endif
                    </div>
                    <div class="department-stats">
                        <span class="stat-badge">
                            <i class="bi bi-check-circle text-success"></i> {{ $deptStatus['finalized_count'] ?? 0 }} Finalized
                        </span>
                        <span class="stat-badge">
                            <i class="bi bi-check-circle-fill text-primary"></i> {{ $deptStatus['executed_count'] ?? 0 }} Executed
                            <br>
                            <i class="bi bi-clock-history text-warning"></i> {{ ($deptStatus['total_count'] ?? 0) - ($deptStatus['executed_count'] ?? 0) }} Remaining
                        </span>
                        @if($payrollConfig)
                        <a href="{{ route('payroll.index', ['edit_id' => $payrollConfig->id]) }}" class="btn-edit-dept"
                            onclick="event.stopPropagation()">
                            <i class="bi bi-pencil-square me-1"></i> Edit
                        </a>
                        @endif
                        <button class="btn-execute-dept"
                            onclick="event.stopPropagation(); executeDepartment('{{ $department->department_id }}', '{{ addslashes($department->department) }}')"
                            {{ !$canExecuteDept ? 'disabled' : '' }}>
                            <i class="bi bi-play-fill me-1"></i> Execute
                        </button>
                        @if($logs && $logs->count() > 0)
                        <button class="btn-logs"
                            onclick="event.stopPropagation(); toggleLogs('logs_{{ $deptCleanId }}')">
                            <i class="bi bi-clock-history me-1"></i> Logs ({{ $logs->count() }})
                        </button>
                        @endif
                        <i class="bi bi-chevron-down toggle-icon ms-2"></i>
                    </div>
                </div>

                <div class="department-body" id="dept_{{ $deptCleanId }}">
                    @if($hasAnyEmployee)
                    <div class="row mb-2 px-2 fw-bold small text-muted">
                        <div class="col-md-4">Employee</div>
                        <div class="col-md-3">Code</div>
                        <div class="col-md-3">Status</div>
                        <div class="col-md-2">Action</div>
                    </div>
                    @foreach($department->employees as $employee)
                    @php
                    $empStatus = $employeeStatuses[$employee->employee_id] ?? null;
                    $isFinalized = $empStatus && $empStatus['is_finalized'];
                    $isExecuted = $empStatus && $empStatus['is_executed'];
                    @endphp
                    <div class="employee-row">
                        <div class="col-md-4">
                            <strong>{{ $employee->name }}</strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">{{ $employee->employee_code }}</small>
                        </div>
                        <div class="col-md-3">
                            @if($isExecuted)
                            <span class="status-executed"><i class="bi bi-check-circle-fill me-1"></i>Executed</span>
                            @elseif($isFinalized)
                            <span class="status-finalized"><i class="bi bi-check-circle me-1"></i>Finalized</span>
                            @else
                            <span class="status-pending"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                            @endif
                        </div>
                        <div class="col-md-2">
                            @if($isFinalized && !$isExecuted)
                            <button class="btn-individual"
                                onclick="executeIndividual('{{ $employee->employee_id }}', '{{ addslashes($employee->name) }}')">
                                <i class="bi bi-play-fill"></i> Execute
                            </button>
                            @elseif(!$isFinalized)
                            <button class="btn-individual" disabled title="Salary not finalized">
                                <i class="bi bi-lock"></i> Locked
                            </button>
                            @else
                            <button class="btn-individual" disabled title="Already executed">
                                <i class="bi bi-check-circle"></i> Done
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    @if(!$canExecuteDept && $deptStatus && $deptStatus['finalized_count'] < $deptStatus['total_count'])
                        <div class="warning-message">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ $deptStatus['finalized_count'] }} out of {{ $deptStatus['total_count'] }} employees have finalized salaries.
                            <strong>{{ $deptStatus['total_count'] - $deptStatus['finalized_count'] }}</strong> employees remaining to finalize.
                            Full department execution will be available once all salaries are finalized.
                        </div>
                    @endif
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-people fs-1 d-block mb-2"></i>
                        No employees found in this department
                    </div>
                    @endif
                </div>

                @if($payrollConfig && $logs && $logs->count() > 0)
                <div class="department-logs" id="logs_{{ $deptCleanId }}">
                    <div class="logs-container">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-primary m-0 fw-bold">
                                <i class="bi bi-clock-history me-2"></i>Execution Change Logs
                            </h6>
                            <small class="text-muted">{{ $payrollConfig->notes ?? 'No additional notes' }}</small>
                        </div>
                        <div class="table-responsive">
                            <table class="logs-table">
                                <thead>
                                    <tr>
                                        <th>Updated On</th>
                                        <th>Financial Year</th>
                                        <th>Payroll Cycle</th>
                                        <th>Cycle Days</th>
                                        <th>Execution Day</th>
                                        <th>Changed By</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ $log->old_financial_year ?? '-' }}</td>
                                        <td class="text-capitalize">{{ $log->old_cycle ?? '-' }}</td>
                                        <td>
                                            @if(($log->old_cycle ?? '') === 'days')
                                            {{ $log->old_cycle_days ?? $log->old_day ?? '-' }}
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>{{ $log->old_execution_day ?? ($log->old_day ?? '-') }}</td>
                                        <td>{{ $log->changed_by_name ?? '-' }}</td>
                                        <td>{{ $log->remark ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-3">
                                            <i class="bi bi-info-circle me-1"></i> No historical changes found.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @empty
            <div class="text-center py-5">
                <i class="bi bi-building fs-1 text-muted d-block mb-3"></i>
                <h5>No Departments Found</h5>
                <p class="text-muted">No departments are configured for payroll execution.</p>
                <a href="{{ route('payroll.index') }}" class="btn btn-primary">Configure Payroll</a>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Execute Payroll Modal -->
<div class="modal fade" id="executeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 20px; width: 100% !important;">
            <div class="modal-header bg-light border-0" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-play-circle-fill text-success me-2"></i>Execute Payroll
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info mb-4" style="background: #eff6ff; border: none; border-radius: 12px;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar-check fs-4 me-3"></i>
                        <div>
                            <strong>Selected Period:</strong>
                            {{ Carbon\Carbon::create((int)$selectedYear, (int)$selectedMonth, 1)->format('F Y') }}
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="execution-type-card" 
                            onclick="selectExecutionType('department')" 
                            id="deptCard"
                            style="cursor: pointer; transition: all 0.3s; border: 2px solid var(--border-color); border-radius: 16px; overflow: hidden;">
                            <div class="card-body p-4">
                                <div class="text-center mb-3">
                                    <div class="icon-circle mx-auto" style="width: 70px; height: 70px; background: #dbeafe; border-radius: 35px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-building fs-1" style="color: var(--primary-color);"></i>
                                    </div>
                                </div>
                                <h5 class="fw-bold text-center mb-2">Department-wise</h5>
                                <p class="text-muted small text-center mb-3">Execute payroll for all employees in a selected department at once</p>
                                <div class="d-flex justify-content-center">
                                    <div class="radio-custom">
                                        <input class="form-check-input" type="radio" name="execType" id="deptRadio" value="department" style="width: 18px; height: 18px; cursor: pointer;">
                                        <label class="form-check-label ms-2" for="deptRadio" style="cursor: pointer;">Select</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="execution-type-card" 
                            onclick="selectExecutionType('multiple')" 
                            id="multipleCard"
                            style="cursor: pointer; transition: all 0.3s; border: 2px solid var(--border-color); border-radius: 16px; overflow: hidden;">
                            <div class="card-body p-4">
                                <div class="text-center mb-3">
                                    <div class="icon-circle mx-auto" style="width: 70px; height: 70px; background: #fed7aa; border-radius: 35px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-people fs-1" style="color: #f59e0b;"></i>
                                    </div>
                                </div>
                                <h5 class="fw-bold text-center mb-2">Multiple Employees</h5>
                                <p class="text-muted small text-center mb-3">Select multiple employees from different departments</p>
                                <div class="d-flex justify-content-center">
                                    <div class="radio-custom">
                                        <input class="form-check-input" type="radio" name="execType" id="multipleRadio" value="multiple" style="width: 18px; height: 18px; cursor: pointer;">
                                        <label class="form-check-label ms-2" for="multipleRadio" style="cursor: pointer;">Select</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="execution-type-card" 
                            onclick="selectExecutionType('individual')" 
                            id="indCard"
                            style="cursor: pointer; transition: all 0.3s; border: 2px solid var(--border-color); border-radius: 16px; overflow: hidden;">
                            <div class="card-body p-4">
                                <div class="text-center mb-3">
                                    <div class="icon-circle mx-auto" style="width: 70px; height: 70px; background: #d1fae5; border-radius: 35px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-person fs-1" style="color: var(--success-color);"></i>
                                    </div>
                                </div>
                                <h5 class="fw-bold text-center mb-2">Single Employee</h5>
                                <p class="text-muted small text-center mb-3">Execute payroll for a single employee</p>
                                <div class="d-flex justify-content-center">
                                    <div class="radio-custom">
                                        <input class="form-check-input" type="radio" name="execType" id="indRadio" value="individual" style="width: 18px; height: 18px; cursor: pointer;">
                                        <label class="form-check-label ms-2" for="indRadio" style="cursor: pointer;">Select</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="selectedTypeMessage" class="mt-4 text-center" style="display: none;">
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <span id="selectedTypeText"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0" style="border-radius: 0 0 20px 20px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 10px 24px; border-radius: 10px;">
                    <i class="bi bi-x-circle me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-success" id="continueBtn" disabled onclick="proceedToExecute()" style="padding: 10px 24px; border-radius: 10px; background: var(--success-gradient); border: none;">
                    <i class="bi bi-arrow-right-circle me-1"></i>Continue
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let selectedExecType = null;
let loadingOverlay = null;

function toggleDepartment(id) {
    const element = document.getElementById(id);
    const card = element?.closest('.department-card');
    const icon = card?.querySelector('.toggle-icon');
    
    if (element) {
        if (element.classList.contains('show')) {
            element.classList.remove('show');
            if (icon) icon.style.transform = 'rotate(0deg)';
        } else {
            element.classList.add('show');
            if (icon) icon.style.transform = 'rotate(180deg)';
        }
    }
}

function toggleLogs(id) {
    const element = document.getElementById(id);
    if (element) {
        if (element.classList.contains('show')) {
            element.classList.remove('show');
        } else {
            element.classList.add('show');
        }
    }
}

function openExecuteModal() {
    selectedExecType = null;
    document.getElementById('deptRadio').checked = false;
    document.getElementById('multipleRadio').checked = false;
    document.getElementById('indRadio').checked = false;
    document.getElementById('continueBtn').disabled = true;
    document.getElementById('selectedTypeMessage').style.display = 'none';
    
    const deptCard = document.getElementById('deptCard');
    const multipleCard = document.getElementById('multipleCard');
    const indCard = document.getElementById('indCard');
    if (deptCard) deptCard.style.borderColor = '#e2e8f0';
    if (multipleCard) multipleCard.style.borderColor = '#e2e8f0';
    if (indCard) indCard.style.borderColor = '#e2e8f0';
    if (deptCard) deptCard.style.background = 'white';
    if (multipleCard) multipleCard.style.background = 'white';
    if (indCard) indCard.style.background = 'white';
    
    new bootstrap.Modal(document.getElementById('executeModal')).show();
}

function selectExecutionType(type) {
    selectedExecType = type;
    
    document.getElementById('deptRadio').checked = (type === 'department');
    document.getElementById('multipleRadio').checked = (type === 'multiple');
    document.getElementById('indRadio').checked = (type === 'individual');
    
    const deptCard = document.getElementById('deptCard');
    const multipleCard = document.getElementById('multipleCard');
    const indCard = document.getElementById('indCard');
    
    if (deptCard) deptCard.style.borderColor = '#e2e8f0';
    if (multipleCard) multipleCard.style.borderColor = '#e2e8f0';
    if (indCard) indCard.style.borderColor = '#e2e8f0';
    if (deptCard) deptCard.style.background = 'white';
    if (multipleCard) multipleCard.style.background = 'white';
    if (indCard) indCard.style.background = 'white';
    
    if (type === 'department') {
        if (deptCard) deptCard.style.borderColor = '#10b981';
        if (deptCard) deptCard.style.background = '#f0fdf4';
        document.getElementById('selectedTypeText').innerHTML = '<i class="bi bi-building me-2"></i> Department-wise execution selected - All employees in a department will be processed';
    } else if (type === 'multiple') {
        if (multipleCard) multipleCard.style.borderColor = '#f59e0b';
        if (multipleCard) multipleCard.style.background = '#fef3c7';
        document.getElementById('selectedTypeText').innerHTML = '<i class="bi bi-people me-2"></i> Multiple employees execution selected - You can select employees from different departments';
    } else {
        if (indCard) indCard.style.borderColor = '#10b981';
        if (indCard) indCard.style.background = '#f0fdf4';
        document.getElementById('selectedTypeText').innerHTML = '<i class="bi bi-person me-2"></i> Single employee execution selected';
    }
    
    document.getElementById('selectedTypeMessage').style.display = 'block';
    document.getElementById('continueBtn').disabled = false;
}

function showLoadingOverlay(message = 'Processing...') {
    hideLoadingOverlay();
    
    loadingOverlay = document.createElement('div');
    loadingOverlay.id = 'loadingOverlay';
    loadingOverlay.className = 'loading-overlay';
    loadingOverlay.innerHTML = `
        <div class="loading-content">
            <div class="spinner-border text-success mb-3" style="width: 50px; height: 50px;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5>${message}</h5>
            <p class="text-muted mb-0">Please wait while we prepare your request</p>
        </div>
    `;
    document.body.appendChild(loadingOverlay);
}

function hideLoadingOverlay() {
    if (loadingOverlay) {
        loadingOverlay.remove();
        loadingOverlay = null;
    }
}

function proceedToExecute() {
    let yearSelect = document.querySelector('select[name="year"]');
    let year = yearSelect ? yearSelect.value : new Date().getFullYear();
    
    if (year && year.includes('-')) {
        year = year.split('-')[0];
    }
    
    const month = document.querySelector('select[name="month"]')?.value || new Date().getMonth() + 1;
    
    const firstModal = bootstrap.Modal.getInstance(document.getElementById('executeModal'));
    if (firstModal) {
        firstModal.hide();
    }
    
    setTimeout(() => {
        if (selectedExecType === 'department') {
            Swal.fire({
                title: 'Select Department',
                html: `
                    <div class="text-start">
                        <label class="form-label fw-bold">Choose Department</label>
                        <select id="deptSelect" class="form-select form-select-lg mb-3">
                            <option value="">-- Select Department --</option>
                            @foreach($departments as $dept)
                                @php $canExec = ($departmentStatus[$dept->department_id]['can_execute'] ?? false); @endphp
                                <option value="{{ $dept->department_id }}" data-name="{{ addslashes($dept->department) }}" {{ !$canExec ? 'disabled' : '' }}>
                                    {{ $dept->department }} {{ !$canExec ? '(Not Ready)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="alert alert-info mt-2">
                            <i class="bi bi-info-circle me-2"></i>
                            Only departments with all salaries finalized are eligible for execution.
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-arrow-right-circle me-2"></i>Continue',
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    const select = document.getElementById('deptSelect');
                    if (!select.value) {
                        Swal.showValidationMessage('Please select a department');
                        return false;
                    }
                    return { id: select.value, name: select.options[select.selectedIndex].text };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoadingOverlay('Redirecting to execution form...');
                    window.location.href = `{{ route('execution.execute.form') }}?type=department&department_id=${result.value.id}&year=${year}&month=${month}`;
                }
            });
        } 
        else if (selectedExecType === 'multiple') {
            Swal.fire({
                title: 'Select Multiple Employees',
                html: `
                    <div class="text-start">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Search Employee</label>
                            <input type="text" id="empSearchMultiple" class="form-control" placeholder="Type to search...">
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold">Select Employees</label>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleAllEmployeesInModal()">
                                    <i class="bi bi-check2-all"></i> Select All Ready
                                </button>
                            </div>
                            <div id="employeesListMultiple" style="max-height: 400px; overflow-y: auto; border: 1px solid var(--border-color); border-radius: 8px;">
                                @foreach($allEmployeesList ?? [] as $emp)
                                    @php
                                        $isFinalized = \App\Models\SalaryReview::where('institute_id', $context['institute_id'] ?? auth()->user()->institute_id)
                                            ->where('employee_id', $emp->employee_id)
                                            ->where('year', $selectedYear ?? date('Y'))
                                            ->where('month', $selectedMonth ?? date('m'))
                                            ->where('review_status', 'finalized')
                                            ->exists();
                                        $isExecuted = \App\Models\FinalSalarySlip::where('employee_id', $emp->employee_id)
                                            ->where('year', $selectedYear ?? date('Y'))
                                            ->where('month', $selectedMonth ?? date('m'))
                                            ->exists();
                                    @endphp
                                    <div class="employee-checkbox-item" 
                                        data-name="{{ $emp->name }}" 
                                        data-code="{{ $emp->employee_code }}"
                                        data-finalized="{{ $isFinalized ? 'true' : 'false' }}"
                                        data-executed="{{ $isExecuted ? 'true' : 'false' }}"
                                        style="padding: 10px; border-bottom: 1px solid var(--border-color); {{ $isExecuted ? 'opacity: 0.6; background: #f1f5f9;' : '' }}">
                                        <div class="form-check">
                                            <input class="form-check-input employee-checkbox-multi" type="checkbox" 
                                                value="{{ $emp->employee_id }}" 
                                                id="emp_multi_{{ $emp->employee_id }}"
                                                {{ $isExecuted ? 'disabled' : '' }}
                                                {{ (!$isFinalized && !$isExecuted) ? 'disabled' : '' }}>
                                            <label class="form-check-label w-100" for="emp_multi_{{ $emp->employee_id }}">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ $emp->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $emp->employee_code }}</small>
                                                    </div>
                                                    <div>
                                                        @if($isExecuted)
                                                            <span class="status-badge status-executed" style="background: #dbeafe; color: #2563eb; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem;">
                                                                <i class="bi bi-check-circle-fill"></i> Already Executed
                                                            </span>
                                                        @elseif($isFinalized)
                                                            <span class="status-badge status-finalized" style="background: #d1fae5; color: #059669; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem;">
                                                                <i class="bi bi-check-circle"></i> Finalized
                                                            </span>
                                                        @else
                                                            <span class="status-badge status-pending" style="background: #fef3c7; color: #d97706; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem;">
                                                                <i class="bi bi-hourglass-split"></i> Pending Finalization
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if(!$isFinalized && !$isExecuted)
                                                    <small class="text-muted d-block mt-1">
                                                        <i class="bi bi-info-circle"></i> 
                                                        Salary needs to be finalized before payroll execution.
                                                    </small>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="alert alert-info mt-2">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Note:</strong> Only employees with <span class="text-success">Finalized</span> status can be selected. 
                            <span class="text-warning">Pending</span> employees need salary finalization first.
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-arrow-right-circle me-2"></i>Continue',
                cancelButtonText: 'Cancel',
                width: '700px',
                didOpen: () => {
                    const searchInput = document.getElementById('empSearchMultiple');
                    if (searchInput) {
                        searchInput.addEventListener('keyup', () => {
                            const term = searchInput.value.toLowerCase();
                            const items = document.querySelectorAll('.employee-checkbox-item');
                            items.forEach(item => {
                                const name = item.getAttribute('data-name').toLowerCase();
                                const code = item.getAttribute('data-code').toLowerCase();
                                if (name.includes(term) || code.includes(term)) {
                                    item.style.display = '';
                                } else {
                                    item.style.display = 'none';
                                }
                            });
                        });
                    }
                },
                preConfirm: () => {
                    const selectedEmployees = [];
                    document.querySelectorAll('.employee-checkbox-multi:checked').forEach(cb => {
                        const parent = cb.closest('.employee-checkbox-item');
                        selectedEmployees.push({
                            id: cb.value,
                            name: parent.getAttribute('data-name'),
                            finalized: parent.getAttribute('data-finalized') === 'true'
                        });
                    });
                    
                    if (selectedEmployees.length === 0) {
                        Swal.showValidationMessage('Please select at least one employee with finalized salary');
                        return false;
                    }
                    return selectedEmployees;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value.length > 0) {
                    const employeeIds = result.value.map(emp => emp.id).join(',');
                    showLoadingOverlay('Redirecting to execution form...');
                    window.location.href = `{{ route('execution.execute.form') }}?type=multiple&employee_ids=${employeeIds}&year=${year}&month=${month}`;
                }
            });
        }
        else {
            Swal.fire({
                title: 'Select Employee',
                html: `
                    <div class="text-start">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Search Employee</label>
                            <input type="text" id="empSearch" class="form-control" placeholder="Type to search...">
                        </div>
                        <label class="form-label fw-bold">Select Employee</label>
                        <select id="empSelect" class="form-select" size="8">
                            <option value="">-- Select Employee --</option>
                            @foreach($allEmployeesList ?? [] as $emp)
                                <option value="{{ $emp->employee_id }}" data-name="{{ addslashes($emp->name) }}">
                                    {{ $emp->name }} ({{ $emp->employee_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-arrow-right-circle me-2"></i>Continue',
                cancelButtonText: 'Cancel',
                didOpen: () => {
                    const searchInput = document.getElementById('empSearch');
                    const select = document.getElementById('empSelect');
                    if (searchInput) {
                        searchInput.addEventListener('keyup', () => {
                            const term = searchInput.value.toLowerCase();
                            Array.from(select.options).forEach(opt => {
                                if (opt.value === '') return;
                                opt.style.display = opt.text.toLowerCase().includes(term) ? '' : 'none';
                            });
                        });
                    }
                },
                preConfirm: () => {
                    const select = document.getElementById('empSelect');
                    if (!select.value) {
                        Swal.showValidationMessage('Please select an employee');
                        return false;
                    }
                    return { id: select.value, name: select.options[select.selectedIndex].text };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoadingOverlay('Redirecting to execution form...');
                    window.location.href = `{{ route('execution.execute.form') }}?type=individual&employee_id=${result.value.id}&year=${year}&month=${month}`;
                }
            });
        }
    }, 300);
}

function toggleAllEmployeesInModal() {
    const checkboxes = document.querySelectorAll('.employee-checkbox-multi:not(:disabled)');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
    });
}

function executeDepartment(deptId, deptName) {
    let yearSelect = document.querySelector('select[name="year"]');
    let year = yearSelect ? yearSelect.value : new Date().getFullYear();

    if (year && year.includes('-')) {
        year = year.split('-')[0];
    }

    const month = document.querySelector('select[name="month"]')?.value || new Date().getMonth() + 1;

    Swal.fire({
        title: 'Execute Department Payroll',
        html: `
            <div class="text-start">
                <div class="alert alert-info mb-3">
                    <i class="bi bi-building me-2"></i>
                    <strong>Department:</strong> ${deptName}
                </div>
                <div class="mb-2">
                    <i class="bi bi-calendar me-2"></i>
                    <strong>Period:</strong> ${getMonthName(month)} ${year}
                </div>
                <div class="alert alert-warning mt-3">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    This will generate salary slips for all finalized employees in this department.
                </div>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        confirmButtonText: '<i class="bi bi-check-circle me-2"></i>Yes, Execute',
        cancelButtonText: '<i class="bi bi-x-circle me-2"></i>Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoadingOverlay('Redirecting to execution form...');
            window.location.href = `{{ route('execution.execute.form') }}?type=department&department_id=${deptId}&year=${year}&month=${month}`;
        }
    });
}

function executeIndividual(empId, empName) {
    let yearSelect = document.querySelector('select[name="year"]');
    let year = yearSelect ? yearSelect.value : new Date().getFullYear();

    if (year && year.includes('-')) {
        year = year.split('-')[0];
    }

    const month = document.querySelector('select[name="month"]')?.value || new Date().getMonth() + 1;

    Swal.fire({
        title: 'Execute Individual Payroll',
        html: `
            <div class="text-start">
                <div class="alert alert-info mb-3">
                    <i class="bi bi-person me-2"></i>
                    <strong>Employee:</strong> ${empName}
                </div>
                <div>
                    <i class="bi bi-calendar me-2"></i>
                    <strong>Period:</strong> ${getMonthName(month)} ${year}
                </div>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        confirmButtonText: '<i class="bi bi-check-circle me-2"></i>Yes, Execute',
        cancelButtonText: '<i class="bi bi-x-circle me-2"></i>Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoadingOverlay('Redirecting to execution form...');
            window.location.href = `{{ route('execution.execute.form') }}?type=individual&employee_id=${empId}&year=${year}&month=${month}`;
        }
    });
}

function getMonthName(month) {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return months[parseInt(month) - 1] || '';
}

window.addEventListener('beforeunload', function() {
    hideLoadingOverlay();
});

document.addEventListener('DOMContentLoaded', function() {
    hideLoadingOverlay();
    
    window.addEventListener('pageshow', function() {
        hideLoadingOverlay();
    });
});
</script>
@endsection