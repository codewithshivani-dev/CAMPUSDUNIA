@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
:root {
    --primary: #4361ee;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --secondary: #64748b;
    --info: #0ea5e9;
    --pending: #fef3c7;
    --completed: #d1fae5;
    --in-progress: #dbeafe;
    --blocked: #f1f5f9;
}

.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px 15px;
}

/* Welcome Card */
.welcome-card {
    background: linear-gradient(135deg, var(--primary), #3a0ca3);
    color: white;
    padding: 25px 30px;
    border-radius: 16px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(67, 97, 238, 0.2);
}

.welcome-card h2 {
    margin: 0 0 8px 0;
    font-size: 24px;
}

.employee-code {
    background: rgba(255, 255, 255, 0.2);
    padding: 5px 15px;
    border-radius: 20px;
    display: inline-block;
    font-size: 13px;
    margin-right: 10px;
}

.employee-avatar {
    width: 70px;
    height: 70px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    font-weight: 600;
}

/* Filter Bar - Auto Filter */
.filter-bar {
    background: white;
    border-radius: 12px;
    padding: 15px 20px;
    margin-bottom: 25px;
    border: 1px solid #e2e8f0;
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    align-items: flex-end;
}

.filter-group {
    flex: 1;
    min-width: 150px;
}

.filter-group label {
    display: block;
    margin-bottom: 5px;
    font-size: 12px;
    font-weight: 600;
    color: var(--secondary);
}

.filter-select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
    background: white;
}

.filter-select:focus {
    outline: none;
    border-color: var(--primary);
}

.btn-filter {
    padding: 8px 20px;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
}

.btn-filter.reset {
    background: var(--secondary);
}

/* Journey Timeline */
.journey-container {
    background: white;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    margin-bottom: 30px;
}

.journey-header {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    padding: 15px 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.journey-title {
    font-weight: 600;
    font-size: 16px;
    color: #1e293b;
}

.journey-progress {
    display: flex;
    align-items: center;
    gap: 15px;
}

.progress-text {
    font-size: 13px;
    color: var(--secondary);
}

.progress-bar-custom {
    width: 200px;
    height: 6px;
    background: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--primary), var(--success));
    border-radius: 10px;
    transition: width 0.3s ease;
}

.timeline {
    padding: 30px 20px;
    display: flex;
    justify-content: space-between;
    position: relative;
    flex-wrap: wrap;
}

.timeline::before {
    content: '';
    position: absolute;
    top: 50px;
    left: 10%;
    right: 10%;
    height: 2px;
    background: #e2e8f0;
    z-index: 1;
}

.timeline-step {
    flex: 1;
    text-align: center;
    position: relative;
    z-index: 2;
    min-width: 120px;
    cursor: pointer;
}

.step-icon {
    width: 50px;
    height: 50px;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
    font-size: 20px;
    transition: all 0.3s;
    background: white;
}

.step-icon.completed {
    background: var(--success);
    border-color: var(--success);
    color: white;
}

.step-icon.in-progress {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
    animation: pulse 2s infinite;
}

.step-icon.blocked {
    background: #f1f5f9;
    border-color: #e2e8f0;
    color: #94a3b8;
}

.step-icon.pending {
    background: white;
    border-color: #e2e8f0;
    color: #94a3b8;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(67, 97, 238, 0.4);
    }

    70% {
        box-shadow: 0 0 0 10px rgba(67, 97, 238, 0);
    }

    100% {
        box-shadow: 0 0 0 0 rgba(67, 97, 238, 0);
    }
}

.step-title {
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 4px;
}

.step-title.completed {
    color: var(--success);
}

.step-title.in-progress {
    color: var(--primary);
}

.step-title.pending {
    color: var(--secondary);
}

.step-title.blocked {
    color: #94a3b8;
}

.step-desc {
    font-size: 11px;
    color: var(--secondary);
    margin-bottom: 8px;
}

.step-date {
    font-size: 10px;
    color: #94a3b8;
}

/* Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    border: 1px solid #e2e8f0;
    transition: all 0.3s;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-bottom: 12px;
}

.stat-icon.attendance {
    background: #dbeafe;
    color: var(--primary);
}

.stat-icon.salary {
    background: #d1fae5;
    color: var(--success);
}

.stat-icon.slip {
    background: #fed7aa;
    color: var(--warning);
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-number.primary {
    color: var(--primary);
}

.stat-number.success {
    color: var(--success);
}

.stat-number.warning {
    color: var(--warning);
}

.stat-number.danger {
    color: var(--danger);
}

.stat-label {
    font-size: 13px;
    color: var(--secondary);
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.status-badge.pending {
    background: #fef3c7;
    color: #d97706;
}

.status-badge.reviewed {
    background: #dbeafe;
    color: #2563eb;
}

.status-badge.finalized {
    background: #d1fae5;
    color: #059669;
}

/* Rules Cards */
.rules-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.rule-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
}

.rule-header {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    padding: 12px 20px;
    border-bottom: 1px solid #e2e8f0;
    font-weight: 600;
    font-size: 14px;
}

.rule-header i {
    margin-right: 8px;
}

.rule-header i.fa-gavel {
    color: var(--warning);
}

.rule-header i.fa-chart-line {
    color: var(--primary);
}

.rule-body {
    padding: 15px 20px;
}

.rule-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.rule-item:last-child {
    border-bottom: none;
}

.rule-label {
    font-size: 13px;
    color: var(--secondary);
}

.rule-value {
    font-weight: 500;
    font-size: 13px;
}

.rule-value.approved {
    color: var(--success);
}

.rule-value.unapproved {
    color: var(--danger);
}

.deduction-table {
    width: 100%;
    font-size: 13px;
}

.deduction-table td {
    padding: 6px 0;
}

.deduction-percentage {
    font-family: monospace;
    font-weight: 600;
}

/* Section Styles */
.section {
    background: white;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    margin-bottom: 25px;
}

.section-header {
    background: #f8fafc;
    padding: 12px 20px;
    border-bottom: 1px solid #e2e8f0;
    font-weight: 600;
    font-size: 14px;
}

.section-header i {
    color: var(--primary);
    margin-right: 8px;
}

.section-content {
    padding: 20px;
}

/* Data Table */
.data-table {
    width: 100%;
}

.data-table th,
.data-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.data-table th {
    background: #f8fafc;
    font-weight: 600;
    font-size: 13px;
    color: var(--secondary);
}

/* Modal */
.modal-content {
    border-radius: 12px;
}

.modal-header {
    background: linear-gradient(135deg, var(--primary), #3a0ca3);
    color: white;
    border-radius: 12px 12px 0 0;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

/* Loader */
.page-loader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.page-loader.active {
    display: flex;
}

.loader-spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid var(--primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    background: white;
    padding: 10px;
    border-radius: 50%;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .timeline {
        flex-direction: column;
        gap: 20px;
    }

    .timeline::before {
        display: none;
    }

    .timeline-step {
        text-align: left;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .step-icon {
        margin: 0;
    }

    .stats-grid,
    .rules-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .welcome-card h2 {
        font-size: 20px;
    }
}
</style>

<div id="pageLoader" class="page-loader">
    <div class="loader-spinner"></div>
</div>

<div class="dashboard-container">
    <!-- Welcome Card -->
    <div class="welcome-card">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2>Welcome, {{ $employee->name }}!</h2>
                <p>Track your attendance, salary status, and download salary slips</p>
                <div>
                    <span class="employee-code">
                        <i class="fas fa-id-card"></i> ID: {{ $employee->employee_code }}
                    </span>
                    <span class="employee-code">
                        <i class="fas fa-building"></i> {{ $department->department ?? 'N/A' }}
                    </span>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="employee-avatar">
                    {{ strtoupper(substr($employee->name, 0, 2)) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar - AUTO FILTER on change -->
    <div class="filter-bar">
        <div class="filter-group">
            <label><i class="fas fa-calendar-alt"></i> Year</label>
            <select id="filterYear" class="filter-select">
                @foreach($availableYears as $y)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label><i class="fas fa-calendar-month"></i> Month</label>
            <select id="filterMonth" class="filter-select">
                @foreach($months as $m => $mName)
                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ $mName }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <button class="btn-filter reset" onclick="resetFilter()"><i class="fas fa-undo-alt"></i> Reset</button>
        </div>
    </div>

    <!-- Journey Timeline -->
    <div class="journey-container">
        <div class="journey-header">
            <div class="journey-title">
                <i class="fas fa-road text-primary me-2"></i>
                Salary Status - {{ Carbon\Carbon::create($year, $month)->format('F Y') }}
            </div>
            <div class="journey-progress">
                <span class="progress-text">{{ $currentMonthStatus['completed_steps'] }}/{{ $currentMonthStatus['total_steps'] }} Steps Completed</span>
                <div class="progress-bar-custom">
                    <div class="progress-fill" style="width: {{ $currentMonthStatus['progress_percentage'] }}%"></div>
                </div>
                <span class="progress-text">{{ $currentMonthStatus['progress_percentage'] }}%</span>
            </div>
        </div>
        <div class="timeline">
            @php
                // Define steps with correct keys that match controller's journey_steps
                $stepKeys = [
                    'attendance_status' => [
                        'title' => 'Attendance',
                        'description' => 'Daily attendance recorded',
                        'icon' => 'fa-calendar-check'
                    ],
                    'attendance_finalized_status' => [
                        'title' => 'Attendance Finalized',
                        'description' => 'Attendance reviewed & approved by HR',
                        'icon' => 'fa-check-double'
                    ],
                    'salary_finalized_status' => [
                        'title' => 'Salary Finalized',
                        'description' => 'Final salary approved by finance',
                        'icon' => 'fa-rupee-sign'
                    ],
                    'payroll_status' => [
                        'title' => 'Payroll Executed',
                        'description' => 'Payroll processed successfully',
                        'icon' => 'fa-chart-line'
                    ],
                    'slip_status' => [
                        'title' => 'Salary Slip',
                        'description' => 'Salary slip generated & available',
                        'icon' => 'fa-file-invoice-dollar'
                    ]
                ];
                
                // Get journey steps from controller data
                $journeySteps = $currentMonthStatus['journey_steps'] ?? [];
            @endphp

            @foreach($stepKeys as $key => $step)
                @php
                    $stepData = $journeySteps[$key] ?? ['status' => 'pending', 'completed_at' => null];
                    $status = $stepData['status'];
                    $completedAt = $stepData['completed_at'] ?? null;
                @endphp
                <div class="timeline-step">
                    <div class="step-icon {{ $status }}">
                        <i class="fas {{ $step['icon'] }}"></i>
                    </div>
                    <div class="step-title {{ $status }}">
                        {{ $step['title'] }}
                    </div>
                    <div class="step-desc">{{ $step['description'] }}</div>
                    @if($completedAt)
                    <div class="step-date">
                        <i class="fas fa-check-circle"></i>
                        {{ \Carbon\Carbon::parse($completedAt)->format('d M, h:i A') }}
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon attendance">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-number primary">{{ $currentMonthStatus['attendance_percentage'] }}%</div>
            <div class="stat-label">Attendance Rate</div>
            <div class="mt-2">
                <span class="status-badge {{ $currentMonthStatus['attendance_status'] }}">
                    {{ ucfirst($currentMonthStatus['attendance_status']) }}
                </span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon salary">
                <i class="fas fa-rupee-sign"></i>
            </div>
            <div class="stat-number success">₹{{ number_format($currentMonthStatus['final_payable'], 2) }}</div>
            <div class="stat-label">Final Payable</div>
            <div class="mt-2">
                <span class="status-badge {{ $currentMonthStatus['salary_status'] }}">
                    {{ ucfirst($currentMonthStatus['salary_status']) }}
                </span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon slip">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div class="stat-number warning">
                {{ $currentMonthStatus['present_days'] }}/{{ $currentMonthStatus['present_days'] + $currentMonthStatus['absent_days'] + $currentMonthStatus['leave_days'] }}
            </div>
            <div class="stat-label">Present/Absent/Leave</div>
            <div class="mt-2">
                @if($currentMonthStatus['slip_generated'])
                <a href="#" onclick="viewSalarySlip({{ $year }}, {{ $month }})" class="text-success">
                    <i class="fas fa-download"></i> Download Slip
                </a>
                @else
                <span class="text-muted">Slip not generated</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Deduction Rules & Payroll Execution Rules -->
    <div class="rules-grid">
        <!-- Deduction Rules Card -->
        <div class="rule-card">
            <div class="rule-header">
                <i class="fas fa-gavel"></i> Leave Deduction Rules
                <small class="text-muted float-end">As per company policy</small>
            </div>
            <div class="rule-body">
                @if(count($deductionRules) > 0)
                <table class="deduction-table">
                    <thead>
                        <tr>
                            <th><strong>Leave Type</strong></th>
                            <th class="d-none"><strong>Category</strong></th>
                            <th><strong>Approved</strong></th>
                            <th><strong>Unapproved</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deductionRules as $rule)
                        <tr>
                            <td class="rule-label">{{ $rule['leave_type'] }}</td>
                            <td class="rule-label d-none">
                                @if($rule['leave_category'] == 'short_leave')
                                <span class="badge bg-info">Short Leave</span>
                                @elseif($rule['leave_category'] == 'half_day')
                                <span class="badge bg-warning">Half Day</span>
                                @else
                                <span class="badge bg-secondary">Full Day</span>
                                @endif
                            </td>
                            <td class="rule-value approved">{{ $rule['approved_percentage'] }}%</td>
                            <td class="rule-value unapproved">{{ $rule['unapproved_percentage'] }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if(array_filter($deductionRules, function($r) { return $r['requires_doctor_certificate']; }))
                <div class="mt-3 pt-2 border-top">
                    <small class="text-muted">
                        <i class="fas fa-file-medical"></i>
                        <strong>Doctor Certificate Required for:</strong>
                        @foreach(array_filter($deductionRules, function($r) { return $r['requires_doctor_certificate'];
                        }) as $rule)
                        {{ $rule['leave_type'] }}@if(!$loop->last), @endif
                        @endforeach
                    </small>
                </div>
                @endif
                @else
                <div class="text-center py-3">
                    <i class="fas fa-info-circle text-muted"></i>
                    <p class="text-muted mt-2">No deduction rules configured. Using default policy.</p>
                </div>
                @endif
            </div>
        </div>
        <!-- Payroll Execution Rules Card -->
        <div class="rule-card">
            <div class="rule-header">
                <i class="fas fa-chart-line"></i> Payroll Execution Rules
                <small class="text-muted float-end">Department: {{ $department->department ?? 'Default' }}</small>
            </div>
            <div class="rule-body">
                <div class="rule-item">
                    <span class="rule-label"><i class="fas fa-calendar-week"></i> Payroll Cycle</span>
                    <span class="rule-value">
                        @if($payrollRules['payroll_cycle'] === 'days')
                       Day Cycle
                        @else
                        Monthly (Calendar Days)
                        @endif
                    </span>
                </div>
                @if($payrollRules['payroll_cycle'] === 'days' && $payrollRules['cycle_days'])
                <div class="rule-item">
                    <span class="rule-label"><i class="fas fa-sync-alt"></i> Cycle Duration</span>
                    <span class="rule-value">{{ $payrollRules['cycle_days'] }} days per cycle</span>
                </div>
                @endif
                <div class="rule-item">
                    <span class="rule-label"><i class="fas fa-calendar-day"></i> Execution Day</span>
                    <span class="rule-value">
                        @if($payrollRules['execution_day'])
                        {{ $payrollRules['execution_day'] }}{{ date('S', mktime(0,0,0,1,$payrollRules['execution_day'],2000)) }}
                        of each month
                        @else
                        1st of each month
                        @endif
                    </span>
                </div>
                <div class="rule-item">
                    <span class="rule-label"><i class="fas fa-calculator"></i> Calculation Basis</span>
                    <span class="rule-value">{{ $payrollRules['calculation_basis'] }}</span>
                </div>
                <div class="rule-item">
                    <span class="rule-label"><i class="fas fa-chart-simple"></i> Daily Rate Formula</span>
                    <span class="rule-value">Monthly Net Salary ÷
                        {{ $payrollRules['payroll_cycle'] === 'days' ? $payrollRules['cycle_days'] : 'Working Days' }}</span>
                </div>
                <div class="rule-item">
                    <span class="rule-label"><i class="fas fa-percent"></i> Absent Deduction</span>
                    <span class="rule-value">100% of Daily Rate per absent day</span>
                </div>
                <div class="mt-3 pt-2 border-top">
                    <small class="text-muted">
                        <i class="fas fa-calendar-alt"></i>
                        @if($payrollRules['execution_day'])
                        Payroll is processed on the
                        <strong>{{ $payrollRules['execution_day'] }}{{ date('S', mktime(0,0,0,1,$payrollRules['execution_day'],2000)) }}
                            of each month</strong>
                        @else
                        Payroll is processed at month-end
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Current Month Details -->
            <div class="section">
                <div class="section-header">
                    <i class="fas fa-chart-line"></i> Monthly Attendance Summary
                </div>
                <div class="section-content">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="stat-number success">{{ $currentMonthStatus['present_days'] }}</div>
                            <div class="stat-label">Present</div>
                        </div>
                        <div class="col-4">
                            <div class="stat-number danger">{{ $currentMonthStatus['absent_days'] }}</div>
                            <div class="stat-label">Absent</div>
                        </div>
                        <div class="col-4">
                            <div class="stat-number warning">{{ $currentMonthStatus['leave_days'] }}</div>
                            <div class="stat-label">Leave</div>
                        </div>
                    </div>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar bg-primary"
                            style="width: {{ $currentMonthStatus['attendance_percentage'] }}%"></div>
                    </div>
                    <div class="text-center mt-2">
                        <small class="text-muted">Working Days:
                            {{ $currentMonthStatus['present_days'] + $currentMonthStatus['absent_days'] + $currentMonthStatus['leave_days'] }}</small>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-sm btn-outline-primary w-100"
                            onclick="viewAttendanceDetails({{ $year }}, {{ $month }})">
                            <i class="fas fa-eye"></i> View  Attendance
                        </button>
                    </div>
                </div>
            </div>

            <!-- Salary History -->
            <div class="section">
                <div class="section-header">
                    <i class="fas fa-history"></i> Salary History (Last 6 Months)
                </div>
                <div class="section-content">
                    @if(count($salaryHistory) > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salaryHistory as $history)
                            <tr>
                                <td>{{ $history['month_name'] }}</td>
                                <td class="fw-bold">₹{{ number_format($history['final_payable'], 2) }}</td>
                                <td>
                                    @if($history['status'] == 'finalized')
                                        <span class="status-badge finalized">
                                            <i class="fas fa-check-circle"></i> {{ $history['status_label'] }}
                                        </span>
                                    @elseif($history['status'] == 'salary_finalized')
                                        <span class="status-badge reviewed">
                                            <i class="fas fa-clock"></i> {{ $history['status_label'] }}
                                        </span>
                                    @else
                                        <span class="status-badge pending">
                                            <i class="fas fa-calculator"></i> {{ $history['status_label'] }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($history['slip_id'])
                                        <button class="btn btn-sm btn-primary" onclick="viewSalarySlip({{ $history['year'] }}, {{ $history['month'] }})">
                                            <i class="fas fa-eye"></i> View Slip
                                        </button>
                                    @elseif($history['status'] == 'salary_finalized')
                                        <span class="text-muted">Payroll Pending</span>
                                    @else
                                        <span class="text-muted">Processing</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-file-invoice-dollar fa-2x text-muted mb-2"></i>
                        <p class="text-muted">No salary records found</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Yearly Summary -->
            <div class="section">
                <div class="section-header">
                    <i class="fas fa-chart-pie"></i> {{ $year }} Yearly Summary
                </div>
                <div class="section-content">
                    <div class="text-center mb-3">
                        <div class="stat-number success">₹{{ number_format($yearlySummary['total_earned'], 2) }}</div>
                        <div class="stat-label">Total Earned (Year to Date)</div>
                    </div>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="stat-number primary">{{ $yearlySummary['months_finalized'] }}</div>
                            <div class="stat-label">Months Finalized</div>
                        </div>
                        <div class="col-6">
                            <div class="stat-number warning">{{ $yearlySummary['avg_attendance'] }}%</div>
                            <div class="stat-label">Avg Attendance</div>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="stat-number success">{{ $yearlySummary['total_present'] }}</div>
                            <div class="stat-label">Present</div>
                        </div>
                        <div class="col-4">
                            <div class="stat-number danger">{{ $yearlySummary['total_absent'] }}</div>
                            <div class="stat-label">Absent</div>
                        </div>
                        <div class="col-4">
                            <div class="stat-number warning">{{ $yearlySummary['total_leaves'] }}</div>
                            <div class="stat-label">Leaves</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="section d-none">
                <div class="section-header">
                    <i class="fas fa-link"></i> Quick Actions
                </div>
                <div class="section-content">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" onclick="viewSalarySlip({{ $year }}, {{ $month }})">
                            <i class="fas fa-file-invoice-dollar"></i> View Current Salary Slip
                        </button>
                        <button class="btn btn-outline-info" onclick="viewAttendanceDetails({{ $year }}, {{ $month }})">
                            <i class="fas fa-calendar-check"></i> View Current Attendance
                        </button>
                        <button class="btn btn-outline-success" onclick="window.print()">
                            <i class="fas fa-print"></i> Print Dashboard
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Details Modal -->
<div class="modal fade" id="attendanceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-calendar-check me-2"></i>Attendance Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="attendanceModalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2">Loading attendance details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Salary Slip Modal -->
<div class="modal fade" id="salarySlipModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-invoice-dollar me-2"></i>Salary Slip</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="salarySlipModalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2">Loading salary slip...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showLoader() {
    document.getElementById('pageLoader').classList.add('active');
}

function hideLoader() {
    document.getElementById('pageLoader').classList.remove('active');
}

// AUTO FILTER - Automatically submit when year or month changes
document.getElementById('filterYear')?.addEventListener('change', function() {
    applyFilter();
});

document.getElementById('filterMonth')?.addEventListener('change', function() {
    applyFilter();
});

function applyFilter() {
    const year = document.getElementById('filterYear').value;
    const month = document.getElementById('filterMonth').value;
    const url = new URL(window.location.href);
    url.searchParams.set('year', year);
    url.searchParams.set('month', month);
    showLoader();
    window.location.href = url.toString();
}

function resetFilter() {
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();
    const currentMonth = currentDate.getMonth() + 1;
    const url = new URL(window.location.href);
    url.searchParams.set('year', currentYear);
    url.searchParams.set('month', currentMonth);
    showLoader();
    window.location.href = url.toString();
}

window.addEventListener('load', function() {
    hideLoader();
});

function scrollToSection(section) {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

function viewAttendanceDetails(year, month) {
    const modal = new bootstrap.Modal(document.getElementById('attendanceModal'));
    const modalBody = document.getElementById('attendanceModalBody');

    modalBody.innerHTML =
        '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2">Loading attendance details...</p></div>';
    modal.show();

    fetch(`/employee/attendance/details?year=${year}&month=${month}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let html = `
                    <div class="mb-3 p-3 bg-light rounded">
                        <div class="row">
                            <div class="col-md-4"><strong>Present:</strong> ${data.data.attendance.present_days}</div>
                            <div class="col-md-4"><strong>Absent:</strong> ${data.data.attendance.absent_days}</div>
                            <div class="col-md-4"><strong>Leave:</strong> ${data.data.attendance.leave_days}</div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4"><strong>Working Days:</strong> ${data.data.attendance.working_days}</div>
                            <div class="col-md-4"><strong>Weekend:</strong> ${data.data.attendance.weekend_days}</div>
                            <div class="col-md-4"><strong>Attendance:</strong> ${data.data.attendance.attendance_percentage}%</div>
                        </div>
                    </div>
                    <h6>Daily Breakdown</h6>
                    <div class="row">
                `;

                if (data.data.details) {
                    Object.entries(data.data.details).forEach(([date, details]) => {
                        const statusColor = details.status === 'present' ? 'success' : (details.status ===
                            'absent' ? 'danger' : 'warning');
                        html += `
                            <div class="col-md-3 mb-2">
                                <div class="card">
                                    <div class="card-body p-2 text-center">
                                        <div class="small text-muted">${new Date(date).toLocaleDateString()}</div>
                                        <div class="badge bg-${statusColor} mt-1">${details.status_text}</div>
                                        ${details.check_in ? `<div class="small mt-1">In: ${details.check_in}</div>` : ''}
                                        ${details.check_out ? `<div class="small">Out: ${details.check_out}</div>` : ''}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
                html += `</div>`;
                modalBody.innerHTML = html;
            } else {
                modalBody.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            }
        })
        .catch(error => {
            modalBody.innerHTML = `<div class="alert alert-danger">Error loading attendance details</div>`;
        });
}

function viewSalarySlip(year, month) {
    const modal = new bootstrap.Modal(document.getElementById('salarySlipModal'));
    const modalBody = document.getElementById('salarySlipModalBody');

    modalBody.innerHTML =
        '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2">Loading salary slip...</p></div>';
    modal.show();

    fetch(`/employee/salary-slip?year=${year}&month=${month}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const slip = data.data.slip;
                const details = data.data.details;
                const isFinal = data.data.is_final || false;

                let earningsHtml = '';
                if (details.earnings_breakdown) {
                    Object.entries(details.earnings_breakdown).forEach(([key, value]) => {
                        if (value > 0) {
                            earningsHtml +=
                                `<div class="d-flex justify-content-between mb-2"><span>${key.replace(/_/g, ' ').toUpperCase()}</span><span>₹${parseFloat(value).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span></div>`;
                        }
                    });
                }

                let deductionsHtml = '';
                if (details.standard_deductions_breakdown) {
                    Object.entries(details.standard_deductions_breakdown).forEach(([key, value]) => {
                        if (value > 0) {
                            let displayName = key.replace(/_/g, ' ').toUpperCase();
                            deductionsHtml +=
                                `<div class="d-flex justify-content-between mb-2"><span>${displayName}</span><span class="text-danger">-₹${parseFloat(value).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span></div>`;
                        }
                    });
                }

                const totalDeductions = (slip.gross_salary || 0) - (slip.final_payable || slip.payable_salary || 0);

                const html = `
                    <div class="salary-slip">
                        <div class="text-center mb-4">
                            <h3>${isFinal ? 'FINAL SALARY SLIP' : 'SALARY SLIP'}</h3>
                            <p><strong>${new Date(slip.salary_month || `${year}-${month}-01`).toLocaleDateString('en-US', {month: 'long', year: 'numeric'})}</strong></p>
                            ${isFinal ? '<span class="badge bg-success mb-2">FINALIZED</span>' : ''}
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <strong>Employee:</strong> ${slip.employee_name || slip.name}<br>
                                <strong>ID:</strong> ${slip.employee_id}<br>
                                <strong>Department:</strong> ${details.department || 'N/A'}
                            </div>
                            <div class="col-md-6">
                                <strong>Slip ID:</strong> ${slip.slip_id || slip.salaryslip_id}<br>
                                <strong>Generated:</strong> ${new Date(slip.generated_at || slip.created_at).toLocaleDateString()}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-success text-white">Earnings</div>
                                    <div class="card-body">
                                        ${earningsHtml || '<p class="text-muted">No earnings data</p>'}
                                        <hr>
                                        <div class="d-flex justify-content-between fw-bold">
                                            <span>Gross Salary</span>
                                            <span>₹${parseFloat(slip.gross_salary || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-danger text-white">Deductions</div>
                                    <div class="card-body">
                                        ${deductionsHtml || '<p class="text-muted">No deductions</p>'}
                                        <hr>
                                        <div class="d-flex justify-content-between fw-bold">
                                            <span>Total Deductions</span>
                                            <span class="text-danger">-₹${parseFloat(totalDeductions).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-success text-center">
                            <h4 class="mb-0">Net Payable: ₹${parseFloat(slip.final_payable || slip.payable_salary || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}</h4>
                        </div>
                        ${!isFinal ? '<div class="alert alert-warning mt-3"><small><i class="fas fa-info-circle"></i> This is a preliminary salary slip.</small></div>' : ''}
                    </div>
                `;
                modalBody.innerHTML = html;
            } else {
                modalBody.innerHTML =
                    `<div class="alert alert-warning">${data.message || 'Salary slip not available'}</div>`;
            }
        })
        .catch(error => {
            modalBody.innerHTML = `<div class="alert alert-danger">Error loading salary slip</div>`;
        });
}
</script>

@endsection