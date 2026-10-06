@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<title>Execute Payroll - Confirmation</title>

<style>
    :root {
        --primary: #4361ee;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #3b82f6;
    }
    
    .page-header {
        background: linear-gradient(135deg, var(--primary), #3a0ca3);
        padding: 25px 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .page-header h1 {
        color: white;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .summary-card {
        background: white;
        border-radius: 15px;
        border: 1px solid #e2e8f0;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    
    .employee-list {
        max-height: 500px;
        overflow-y: auto;
    }
    
    .employee-item {
        background: #f8fafc;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 10px;
        border-left: 4px solid var(--success);
        transition: all 0.3s;
    }
    
    .employee-item:hover {
        background: #f1f5f9;
        transform: translateX(5px);
    }
    
    .employee-item.disabled {
        opacity: 0.6;
        background: #f1f5f9;
        border-left-color: #cbd5e1;
    }
    
    .btn-execute {
        background: linear-gradient(135deg, var(--success), #059669);
        color: white;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        transition: all 0.3s;
    }
    
    .btn-execute:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
    }
    
    .btn-execute:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
        transform: none;
    }
    
    .planned-date-info {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-block;
    }
    
    .status-ready {
        background: #d1fae5;
        color: #059669;
    }
    
    .status-not-ready {
        background: #fef3c7;
        color: #d97706;
    }
    
    .status-executed {
        background: #dbeafe;
        color: #2563eb;
    }
    
    .warning-box {
        background: #fef3c7;
        border-radius: 10px;
        padding: 12px 15px;
        border-left: 4px solid #f59e0b;
        margin-top: 15px;
    }
    
    .date-comparison {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #e2e8f0;
    }
    
    .planned-date {
        border-left: 4px solid #f59e0b;
    }
    
    .actual-date {
        border-left: 4px solid #10b981;
    }
    
    .date-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
    }
    
    .date-value {
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .execution-type-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .execution-department { background: #dbeafe; color: #2563eb; }
    .execution-multiple { background: #fed7aa; color: #c2410c; }
    .execution-individual { background: #d1fae5; color: #059669; }
    
    .summary-stats-box {
        background: #f0fdf4;
        border-radius: 12px;
        padding: 15px;
        margin-top: 15px;
    }
    
    .stat-number {
        font-size: 24px;
        font-weight: 700;
        color: #059669;
    }
    
    .department-list {
        max-height: 300px;
        overflow-y: auto;
    }
    
    .department-tag {
        display: inline-block;
        background: #e2e8f0;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 0.75rem;
        margin: 3px;
    }
</style>

<div class="container-fluid py-4">
    <div class="page-header">
        <h1>
            <i class="fas fa-play-circle"></i>
            Execute Payroll
        </h1>
        <div>
            <a href="{{ route('payroll.viewPage') }}" class="btn btn-light">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
    
    <!-- Date Comparison Card -->
    <div class="summary-card">
        <h5 class="mb-3"><i class="fas fa-calendar-alt text-primary"></i> Execution Dates</h5>
        <div class="row">
            <div class="col-md-6">
                <div class="date-comparison planned-date">
                    <div class="date-label">
                        <i class="fas fa-calendar-week"></i> Planned Execution Date
                    </div>
                    <div class="date-value">
                        {{ $plannedDate->format('l, d F Y') }}
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Based on payroll configuration
                    </small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="date-comparison actual-date">
                    <div class="date-label">
                        <i class="fas fa-calendar-day"></i> Actual Execution Date
                    </div>
                    <div class="date-value">
                        {{ now()->format('l, d F Y') }}
                        <span class="badge bg-success ms-2">
                            <i class="fas fa-clock"></i> {{ now()->format('h:i A') }}
                        </span>
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Current date and time
                    </small>
                </div>
            </div>
        </div>
        
        @if($plannedDate->format('Y-m-d') != now()->format('Y-m-d'))
        <div class="alert alert-warning mt-3 mb-0">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Note:</strong> You are executing payroll on {{ now()->format('d M Y') }}, 
            which is different from the planned date ({{ $plannedDate->format('d M Y') }}).
        </div>
        @endif
    </div>
    
    <!-- Execution Summary Card -->
    <div class="summary-card">
        <div class="row">
            <div class="col-md-6">
                <h5><i class="fas fa-info-circle text-primary"></i> Execution Details</h5>
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Execution Type:</th>
                        <td>
                            @if($type == 'department')
                                <span class="execution-type-badge execution-department">
                                    <i class="fas fa-building"></i> Department-wise
                                </span>
                            @elseif($type == 'multiple')
                                <span class="execution-type-badge execution-multiple">
                                    <i class="fas fa-users"></i> Multiple Employees
                                </span>
                            @else
                                <span class="execution-type-badge execution-individual">
                                    <i class="fas fa-user"></i> Individual
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Period:</th>
                        <td><strong>{{ Carbon\Carbon::create($year, $month, 1)->format('F Y') }}</strong></td>
                    </tr>
                    @if($type == 'department')
                    <tr>
                        <th>Department:</th>
                        <td><strong>{{ $department->department ?? 'N/A' }}</strong></td>
                    </tr>
                    @endif
                    <tr>
                        <th>Total Employees:</th>
                        <td><strong>{{ $totalEmployees }}</strong></td>
                    </tr>
                    <tr>
                        <th>Ready to Execute:</th>
                        <td>
                            <strong class="text-success">{{ $readyCount }}</strong> 
                            @if($pendingCount > 0)
                                <span class="text-muted">({{ $pendingCount }} pending finalization)</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <div class="planned-date-info">
                    <i class="fas fa-calendar-check"></i>
                    <strong>Payroll Processing Summary:</strong>
                    <ul class="mt-2 mb-0">
                        <li>📅 Planned Date: {{ $plannedDate->format('d M Y') }}</li>
                        <li>🕒 Actual Date: {{ now()->format('d M Y h:i A') }}</li>
                        <li>👤 Executed By: {{ auth()->user()->name ?? auth()->user()->email }}</li>
                        <li>📊 Total to Process: {{ $readyCount }} employee(s)</li>
                    </ul>
                </div>
                
                @if($type == 'department' && $pendingCount > 0)
                <div class="warning-box">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    <strong>Note:</strong> Only employees with finalized salaries will be executed. 
                    {{ $pendingCount }} employee(s) have not finalized their salaries yet.
                </div>
                @endif
            </div>
        </div>
    </div>
    
    @if($type == 'department')
        <!-- ========== DEPARTMENT-WISE EXECUTION ========== -->
        <div class="summary-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="fas fa-users"></i> Employees in {{ $department->department }}</h5>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAllEmployees" onclick="toggleSelectAll()">
                    <label class="form-check-label fw-bold" for="selectAllEmployees">
                        Select All Ready Employees
                    </label>
                </div>
            </div>
            
            <div class="employee-list">
                <form method="POST" action="{{ route('execution.execute') }}" id="executeForm">
                    @csrf
                    <input type="hidden" name="type" value="department">
                    <input type="hidden" name="department_id" value="{{ $department->department_id }}">
                    <input type="hidden" name="year" value="{{ $year }}">
                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="hidden" name="planned_date" value="{{ $plannedDate->format('Y-m-d') }}">
                    
                    <div id="employeesList">
                        @foreach($employees as $employee)
                            <div class="employee-item {{ !$employee->can_execute ? 'disabled' : '' }}">
                                <div class="form-check">
                                    <input class="form-check-input employee-checkbox" type="checkbox" 
                                           name="employee_ids[]" value="{{ $employee->employee_id }}"
                                           id="emp_{{ $employee->employee_id }}"
                                           {{ $employee->can_execute ? 'checked' : 'disabled' }}>
                                    <label class="form-check-label w-100" for="emp_{{ $employee->employee_id }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $employee->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $employee->employee_code }}</small>
                                            </div>
                                            <div>
                                                @if(!$employee->salary_finalized)
                                                    <span class="status-badge status-not-ready">
                                                        <i class="fas fa-hourglass-half"></i> Pending Finalization
                                                    </span>
                                                @elseif($employee->already_executed)
                                                    <span class="status-badge status-executed">
                                                        <i class="fas fa-check-circle"></i> Already Executed
                                                    </span>
                                                @else
                                                    <span class="status-badge status-ready">
                                                        <i class="fas fa-check-circle"></i> Ready to Execute
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        @if(!$employee->salary_finalized)
                                            <small class="text-muted d-block mt-1">
                                                <i class="fas fa-info-circle"></i> 
                                                Salary needs to be finalized before payroll execution.
                                                <a href="{{ route('salary.review.index', ['employee_id' => $employee->employee_id, 'year' => $year, 'month' => $month]) }}" target="_blank">
                                                    Click here to finalize
                                                </a>
                                            </small>
                                        @endif
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4">
                        <label class="form-label">Execution Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Add any notes about this payroll execution..."></textarea>
                    </div>
                    
                    <div class="mt-4 text-end">
                        <a href="{{ route('payroll.viewPage') }}" class="btn btn-secondary">Cancel</a>
                        <button type="button" class="btn btn-execute ms-2" onclick="confirmDepartmentExecution()">
                            <i class="fas fa-play-circle"></i> Execute Selected ({{ $readyCount }})
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
    @elseif($type == 'multiple')
        <!-- ========== MULTIPLE EMPLOYEES EXECUTION ========== -->
        <div class="summary-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="fas fa-users"></i> Selected Employees ({{ $totalEmployees }} selected)</h5>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAllEmployees" onclick="toggleSelectAll()">
                    <label class="form-check-label fw-bold" for="selectAllEmployees">
                        Select All Ready Employees
                    </label>
                </div>
            </div>
            
            @php
                $uniqueDepartments = $employees->unique('department_name')->pluck('department_name')->filter();
            @endphp
            @if($uniqueDepartments->count() > 0)
            <div class="mb-3">
                <small class="text-muted">Departments Included:</small>
                <div class="department-list mt-1">
                    @foreach($uniqueDepartments as $deptName)
                        <span class="department-tag">
                            <i class="fas fa-building"></i> {{ $deptName }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif
            
            <div class="employee-list">
                <form method="POST" action="{{ route('execution.execute') }}" id="executeForm">
                    @csrf
                    <input type="hidden" name="type" value="multiple">
                    <input type="hidden" name="year" value="{{ $year }}">
                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="hidden" name="planned_date" value="{{ $plannedDate->format('Y-m-d') }}">
                    
                    <div id="employeesList">
                        @foreach($employees as $employee)
                            <div class="employee-item {{ !$employee->can_execute ? 'disabled' : '' }}">
                                <div class="form-check">
                                    <input class="form-check-input employee-checkbox" type="checkbox" 
                                           name="multiple_employee_ids[]" value="{{ $employee->employee_id }}"
                                           id="emp_{{ $employee->employee_id }}"
                                           {{ $employee->can_execute && isset($preSelectedEmployeeIds) && in_array($employee->employee_id, $preSelectedEmployeeIds) ? 'checked' : ($employee->can_execute ? 'checked' : '') }}
                                           {{ !$employee->can_execute ? 'disabled' : '' }}>
                                    <label class="form-check-label w-100" for="emp_{{ $employee->employee_id }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $employee->name }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $employee->employee_code }} | 
                                                    <i class="fas fa-building"></i> {{ $employee->department_name ?? 'No Department' }}
                                                </small>
                                            </div>
                                            <div>
                                                @if(!$employee->salary_finalized)
                                                    <span class="status-badge status-not-ready">
                                                        <i class="fas fa-hourglass-half"></i> Pending Finalization
                                                    </span>
                                                @elseif($employee->already_executed)
                                                    <span class="status-badge status-executed">
                                                        <i class="fas fa-check-circle"></i> Already Executed
                                                    </span>
                                                @else
                                                    <span class="status-badge status-ready">
                                                        <i class="fas fa-check-circle"></i> Ready to Execute
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        @if(!$employee->salary_finalized)
                                            <small class="text-muted d-block mt-1">
                                                <i class="fas fa-info-circle"></i> 
                                                Salary needs to be finalized before payroll execution.
                                                <a href="{{ route('salary.review.index', ['employee_id' => $employee->employee_id, 'year' => $year, 'month' => $month]) }}" target="_blank">
                                                    Click here to finalize
                                                </a>
                                            </small>
                                        @endif
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4">
                        <label class="form-label">Execution Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Add any notes about this payroll execution..."></textarea>
                    </div>
                    
                    <div class="mt-4 text-end">
                        <a href="{{ route('payroll.viewPage') }}" class="btn btn-secondary">Cancel</a>
                        <button type="button" class="btn btn-execute ms-2" onclick="confirmMultipleExecution()">
                            <i class="fas fa-play-circle"></i> Execute Selected ({{ $readyCount }})
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
    @else
        <!-- ========== INDIVIDUAL EXECUTION ========== -->
        <div class="summary-card">
            <h5><i class="fas fa-user"></i> Employee Details</h5>
            <form method="POST" action="{{ route('execution.execute') }}" id="executeForm">
                @csrf
                <input type="hidden" name="type" value="individual">
                <input type="hidden" name="employee_id" value="{{ $selectedEmployee->employee_id }}">
                <input type="hidden" name="year" value="{{ $year }}">
                <input type="hidden" name="month" value="{{ $month }}">
                <input type="hidden" name="planned_date" value="{{ $plannedDate->format('Y-m-d') }}">
                
                <div class="employee-item" style="border-left-color: {{ $selectedEmployee->can_execute ? '#10b981' : '#f59e0b' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="fs-5">{{ $selectedEmployee->name }}</strong>
                            <br>
                            <small class="text-muted">
                                {{ $selectedEmployee->employee_code }} | 
                                <i class="fas fa-building"></i> {{ $selectedEmployee->department_name ?? 'No Department' }}
                            </small>
                        </div>
                        <div>
                            @if($selectedEmployee->can_execute)
                                <span class="status-badge status-ready">
                                    <i class="fas fa-check-circle"></i> Ready to Execute
                                </span>
                            @elseif($selectedEmployee->already_executed)
                                <span class="status-badge status-executed">
                                    <i class="fas fa-check-circle"></i> Already Executed
                                </span>
                            @else
                                <span class="status-badge status-not-ready">
                                    <i class="fas fa-hourglass-half"></i> Pending Finalization
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    @if(!$selectedEmployee->salary_finalized)
                        <div class="warning-box mt-3">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Salary not finalized!</strong> 
                            Please finalize the salary first.
                            <a href="{{ route('salary.review.index', ['employee_id' => $selectedEmployee->employee_id, 'year' => $year, 'month' => $month]) }}" target="_blank" class="ms-2">
                                Click here to finalize
                            </a>
                        </div>
                    @endif
                </div>
                
                <div class="mt-4">
                    <label class="form-label">Execution Notes (Optional)</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Add any notes about this payroll execution..."></textarea>
                </div>
                
                <div class="mt-4 text-end">
                    <a href="{{ route('payroll.viewPage') }}" class="btn btn-secondary">Cancel</a>
                    <button type="button" class="btn btn-execute ms-2" onclick="confirmIndividualExecution()" 
                            {{ !$selectedEmployee->can_execute ? 'disabled' : '' }}>
                        <i class="fas fa-play-circle"></i> Execute Payroll
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let selectedCount = {{ $readyCount ?? 0 }};
    
    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAllEmployees');
        if (!selectAll) return;
        
        const checkboxes = document.querySelectorAll('.employee-checkbox:not(:disabled)');
        checkboxes.forEach(cb => {
            cb.checked = selectAll.checked;
        });
        updateSelectedCount();
    }
    
    function updateSelectedCount() {
        selectedCount = document.querySelectorAll('.employee-checkbox:checked').length;
        const executeBtn = document.querySelector('.btn-execute');
        if (executeBtn) {
            executeBtn.innerHTML = `<i class="fas fa-play-circle"></i> Execute Selected (${selectedCount})`;
        }
    }
    
    function confirmDepartmentExecution() {
        if (selectedCount === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Employees Selected',
                text: 'Please select at least one employee to execute payroll.',
                confirmButtonColor: '#4361ee'
            });
            return;
        }
        
        Swal.fire({
            title: 'Confirm Department Payroll Execution',
            html: `
                <div class="text-left">
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Please confirm</strong>
                    </div>
                    <p><strong>Type:</strong> Department-wise</p>
                    <p><strong>Department:</strong> {{ $department->department ?? 'N/A' }}</p>
                    <p><strong>Period:</strong> {{ Carbon\Carbon::create($year, $month, 1)->format('F Y') }}</p>
                    <p><strong>Employees to process:</strong> ${selectedCount}</p>
                    <p><strong>Planned Date:</strong> {{ $plannedDate->format('d M Y') }}</p>
                    <p><strong>Actual Date:</strong> {{ now()->format('d M Y h:i A') }}</p>
                    <hr>
                    <p class="text-muted small">This action will generate final salary slips and cannot be undone.</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#dc2626',
            confirmButtonText: '<i class="fas fa-check-circle"></i> Yes, Execute Payroll',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while payroll is being executed',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                document.getElementById('executeForm').submit();
            }
        });
    }
    
    function confirmMultipleExecution() {
        if (selectedCount === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Employees Selected',
                text: 'Please select at least one employee to execute payroll.',
                confirmButtonColor: '#4361ee'
            });
            return;
        }
        
        Swal.fire({
            title: 'Confirm Multiple Employees Payroll Execution',
            html: `
                <div class="text-left">
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Please confirm</strong>
                    </div>
                    <p><strong>Type:</strong> Multiple Employees</p>
                    <p><strong>Period:</strong> {{ Carbon\Carbon::create($year, $month, 1)->format('F Y') }}</p>
                    <p><strong>Employees to process:</strong> ${selectedCount}</p>
                    <p><strong>Planned Date:</strong> {{ $plannedDate->format('d M Y') }}</p>
                    <p><strong>Actual Date:</strong> {{ now()->format('d M Y h:i A') }}</p>
                    <hr>
                    <p class="text-muted small">This action will generate final salary slips for selected employees and cannot be undone.</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#dc2626',
            confirmButtonText: '<i class="fas fa-check-circle"></i> Yes, Execute Payroll',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while payroll is being executed',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                document.getElementById('executeForm').submit();
            }
        });
    }
    
    function confirmIndividualExecution() {
        Swal.fire({
            title: 'Confirm Individual Payroll Execution',
            html: `
                <div class="text-left">
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Please confirm</strong>
                    </div>
                    <p><strong>Type:</strong> Individual-wise</p>
                    <p><strong>Employee:</strong> {{ $selectedEmployee->name ?? 'N/A' }}</p>
                    <p><strong>Period:</strong> {{ Carbon\Carbon::create($year, $month, 1)->format('F Y') }}</p>
                    <p><strong>Planned Date:</strong> {{ $plannedDate->format('d M Y') }}</p>
                    <p><strong>Actual Date:</strong> {{ now()->format('d M Y h:i A') }}</p>
                    <hr>
                    <p class="text-muted small">This action will generate the final salary slip and cannot be undone.</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#dc2626',
            confirmButtonText: '<i class="fas fa-check-circle"></i> Yes, Execute Payroll',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while payroll is being executed',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                document.getElementById('executeForm').submit();
            }
        });
    }
    
    // Attach event listeners for checkboxes
    @if($type == 'department' || $type == 'multiple')
    document.querySelectorAll('.employee-checkbox').forEach(cb => {
        if (cb.addEventListener) {
            cb.addEventListener('change', updateSelectedCount);
        }
    });
    @endif
    
    // Show any session messages
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#10b981',
        timer: 3000,
        showConfirmButton: true
    });
    @endif
    
    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session('error') }}',
        confirmButtonColor: '#dc2626'
    });
    @endif
</script>
@endsection