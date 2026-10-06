@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<title>Salary Review for Payroll</title>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --primary-color: #4361ee;
    --secondary-color: #3a0ca3;
    --success-gradient: linear-gradient(135deg, #10b981, #059669);
    --success-color: #10b981;
    --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
    --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
    --border-color: #e2e8f0;
    --text-dark: #1e293b;
    --text-muted: #64748b;
    --bg-light: #f8fafc;
    --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
    --transition: all 0.2s ease;
}

/* Page Header */
.page-header {
    background: var(--primary-gradient);
    padding: 25px 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
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
    font-size: 24px;
}

.page-header h1 i {
    background: rgba(255, 255, 255, 0.2);
    padding: 12px;
    border-radius: 12px;
    margin-right: 15px;
}

.page-header .btn-light {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    transition: var(--transition);
}

.page-header .btn-light:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
}

/* Selection Bar */
.selection-bar {
    background: white;
    border-radius: 15px;
    padding: 15px 20px;
    margin-bottom: 20px;
    border: 2px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    position: sticky;
    top: 0;
    z-index: 100;
    background: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.selection-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.selection-count {
    font-weight: 600;
    color: var(--primary-color);
    font-size: 16px;
}

.selection-actions {
    display: flex;
    gap: 10px;
}

/* Filters Section */
.filters-section {
    background: var(--bg-light);
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 25px;
    border: 2px solid var(--border-color);
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

.filter-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--text-dark);
    font-size: 13px;
}

.filter-input {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid var(--border-color);
    border-radius: 10px;
    font-size: 14px;
    transition: var(--transition);
    background: white;
}

.filter-input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.filter-actions {
    align-items: end;
    display: flex;
    gap: 10px;
}

.btn-reset {
    background: #64748b;
    color: white !important;
    border: none;
    padding: 10px 20px;
    border-radius: 10px;
    cursor: pointer;
    transition: var(--transition);
}

.btn-reset:hover {
    background: #475569;
    transform: translateY(-2px);
}

/* Employee Cards */
.employees-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 80px;
    transition: opacity 0.3s ease;
}

.employees-container.loading {
    opacity: 0.6;
    pointer-events: none;
}

.employee-card {
    background: white;
    border-radius: 15px;
    border: 2px solid var(--border-color);
    overflow: hidden;
    transition: var(--transition);
    position: relative;
}

.employee-card:hover {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.employee-card.selected {
    border-color: var(--primary-color);
    background: #f8faff;
    box-shadow: 0 5px 20px rgba(67, 97, 238, 0.15);
}

.employee-card.finalized-card {
    background: var(--bg-light);
    opacity: 0.95;
}

.employee-card.finalized-card .card-header {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
}

.card-header {
    padding: 20px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-bottom: 2px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.employee-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.employee-checkbox {
    width: 20px;
    height: 20px;
    cursor: pointer;
    accent-color: var(--primary-color);
}

.employee-avatar {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 18px;
}

.employee-details h4 {
    margin: 0 0 5px 0;
    font-weight: 600;
    color: var(--text-dark);
}

.employee-details p {
    margin: 0;
    font-size: 13px;
    color: var(--text-muted);
}

.shift-info {
    font-size: 11px;
    color: #6b7280;
    margin-top: 4px;
}

.weekly-off-badge {
    background: #f3f4f6;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 10px;
    display: inline-block;
}

.month-badge {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.month-badge i {
    font-size: 10px;
}

.employee-details h4 {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 5px;
}

.status-badge {
    padding: 8px 15px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
}

.status-pending {
    background: #fef3c7;
    color: #d97706;
}

.status-reviewed {
    background: #dbeafe;
    color: #2563eb;
}

.status-finalized {
    background: #d1fae5;
    color: #059669;
}

.view-only-badge {
    background: #e0e7ff;
    color: #4338ca;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
    margin-left: 10px;
}

.card-body {
    padding: 20px;
}

.salary-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.stat-item {
    text-align: center;
    padding: 12px;
    background: var(--bg-light);
    border-radius: 10px;
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 12px;
    color: var(--text-muted);
}

.stat-value.basic {
    color: var(--primary-color);
}

.stat-value.gross {
    color: #f59e0b;
}

.stat-value.net {
    color: #059669;
}

.stat-value.deduction {
    color: #dc2626;
}

.card-footer {
    padding: 15px 20px;
    background: #fafbff;
    border-top: 2px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.btn-review {
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    border: none;
}

.btn-primary-custom {
    background: var(--primary-gradient);
    color: white;
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
}

a {
    text-decoration: none;
}

.btn-success-custom {
    background: var(--success-gradient);
    color: white;
}

.btn-success-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
}

.btn-outline {
    background: transparent;
    border: 2px solid var(--border-color);
    color: #475569;
}

.btn-outline:hover {
    border-color: var(--primary-color);
    background: var(--bg-light);
}

.btn-outline-primary {
    background: transparent;
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
}

.btn-outline-primary:hover {
    background: var(--primary-gradient);
    color: white;
    border-color: transparent;
}

.btn-outline-warning {
    background: transparent;
    border: 2px solid #f59e0b;
    color: #d97706;
}

.btn-outline-warning:hover {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    border-color: transparent;
}

.period-badge {
    background: var(--warning-gradient);
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 20px;
    font-weight: 500;
    display: inline-block;
    color: white;
}

/* Fixed Bulk Actions Button */
.bulk-actions {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
    display: flex;
    gap: 10px;
}

.bulk-actions .btn {
    padding: 12px 24px;
    border-radius: 50px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    font-size: 16px;
    font-weight: 600;
}

/* Loader Styles */
.loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    backdrop-filter: blur(3px);
}

.loader-overlay.active {
    display: flex;
}

.loader-content {
    text-align: center;
    background: white;
    padding: 30px 40px;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid var(--border-color);
    border-top: 4px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 15px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loader-content p {
    margin: 0;
    color: var(--text-dark);
    font-weight: 500;
    font-size: 14px;
}

.loader-content .loader-text {
    color: var(--text-muted);
    font-size: 12px;
    margin-top: 5px;
}

/* Payroll Execution Section Styles */
.payroll-card {
    border: 2px solid var(--border-color);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 20px;
}

.payroll-header {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 15px;
}

.payroll-header h6 {
    margin: 0;
    font-weight: 600;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid var(--border-color);
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 500;
    color: #4a5568;
}

.info-value {
    font-weight: 600;
    color: var(--text-dark);
}

.badge-cycle-monthly {
    background: #3b82f6;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    color: white;
}

.badge-cycle-daily {
    background: #0ea5e9;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    color: white;
}

.calculation-note {
    background: #e0f2fe;
    border-left: 4px solid #0284c7;
    border-radius: 8px;
    padding: 12px;
    margin-top: 15px;
}

.calculation-note i {
    color: #0284c7;
    margin-right: 8px;
}

.calculation-note p {
    margin: 5px 0 0 0;
    font-size: 13px;
    color: #0c4a6e;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        text-align: center;
    }

    .card-header {
        flex-direction: column;
        text-align: center;
    }

    .employee-info {
        flex-direction: column;
    }

    .salary-stats {
        grid-template-columns: repeat(2, 1fr);
    }

    .card-footer {
        flex-direction: column;
    }

    .btn-review {
        width: 100%;
        justify-content: center;
    }

    .bulk-actions {
        bottom: 20px;
        right: 20px;
    }
    
    .bulk-actions .btn {
        padding: 10px 20px;
        font-size: 14px;
    }
    
    .selection-bar {
        flex-direction: column;
        text-align: center;
        position: relative;
        top: auto;
    }
}
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-rupee-sign"></i>
            Review Salary
        </h1>
        <div>
            <button type="button" class="btn btn-light d-none" onclick="window.print()">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <form method="GET" action="{{ route('salary.review.index') }}" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <label><i class="fas fa-calendar-alt"></i> Year</label>
                    <select name="year" class="filter-input" onchange="this.form.submit()">
                        @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label><i class="fas fa-calendar-month"></i> Month</label>
                    <select name="month" class="filter-input" onchange="this.form.submit()">
                        @foreach($months as $monthNum => $monthName)
                        <option value="{{ $monthNum }}" {{ $selectedMonth == $monthNum ? 'selected' : '' }}>
                            {{ $monthName }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label><i class="fas fa-building"></i> Department</label>
                    <select name="department_id" class="filter-input" onchange="this.form.submit()">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->department_id }}"
                            {{ $departmentId == $dept->department_id ? 'selected' : '' }}>
                            {{ $dept->department }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label><i class="fas fa-user"></i> Employee</label>
                    <select name="employee_id" class="filter-input" onchange="this.form.submit()">
                        <option value="">All Employees</option>
                        @foreach($allEmployees as $emp)
                        <option value="{{ $emp->employee_id }}"
                            {{ $employeeId == $emp->employee_id ? 'selected' : '' }}>
                            {{ $emp->name }} ({{ $emp->employee_code }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label><i class="fas fa-filter"></i> Status</label>
                    <select name="review_status" class="filter-input" onchange="this.form.submit()">
                        <option value="all" {{ $reviewStatus == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="pending" {{ $reviewStatus == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="finalized" {{ $reviewStatus == 'finalized' ? 'selected' : '' }}>Finalized</option>
                    </select>
                </div>
                
                <div class="filter-actions">
                    <a href="{{ route('salary.review.index') }}" class="btn btn-reset">
                        <i class="fas fa-undo-alt"></i> Reset Filters
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Period Indicator -->
    <div class="text-center mb-4">
        <div class="period-badge">
            <i class="fas fa-calendar-alt"></i>
            {{ Carbon\Carbon::create($selectedYear, $selectedMonth)->format('F Y') }}
        </div>
    </div>

    <!-- Selection Bar - Only show for non-finalized employees -->
    @if($hasPendingReviews && $reviewStatus != 'finalized')
    <div class="selection-bar" id="selectionBar">
        <div class="selection-info">
            <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()">
            <label for="selectAllCheckbox" class="mb-0 fw-bold">Select All Employees</label>
            <span class="selection-count" id="selectedCount">0 employees selected</span>
        </div>
        <div class="selection-actions">
            <button class="btn btn-outline" onclick="clearAllSelections()">
                <i class="fas fa-times"></i> Clear Selection
            </button>
        </div>
    </div>
    @endif

    <!-- Employees List -->
    <div class="employees-container">
        @forelse($salaryReviews as $data)
        @php
        $employee = $data['employee'];
        $salary = $data['salary'];
        $review = $data['review'];
        $status = $data['review_status'];
        @endphp

        <div class="employee-card {{ $status == 'finalized' ? 'finalized-card' : '' }}"
            data-employee-id="{{ $employee->employee_id }}" data-status="{{ $status }}">
            <div class="card-header">
                <div class="employee-info">
                    @if($status != 'finalized')
                    <input type="checkbox" class="employee-checkbox" data-employee-id="{{ $employee->employee_id }}"
                        onchange="updateSelection()">
                    @endif
                    <div class="employee-avatar">
                        {{ strtoupper(substr($employee->name, 0, 2)) }}
                    </div>
                    <div class="employee-details">
                        <h4>
                            {{ $employee->name }}
                            <span class="month-badge">
                                <i class="fas fa-calendar-alt"></i>
                                {{ Carbon\Carbon::create($selectedYear, $selectedMonth)->format('F Y') }}
                            </span>
                        </h4>
                        <p>
                            <i class="fas fa-building me-1"></i> {{ $employee->department_name ?? 'No Department' }} |
                            <i class="fas fa-id-card me-1"></i> {{ $employee->employee_code }}
                        </p>
                    </div>
                </div>
                <div>
                    <span class="status-badge status-{{ $status }}">
                        <i class="fas {{ $status == 'pending' ? 'fa-clock' : ($status == 'reviewed' ? 'fa-eye' : 'fa-check-circle') }} me-1"></i>
                        {{ ucfirst($status) }}
                    </span>
                    @if($status == 'finalized')
                    <span class="view-only-badge">
                        <i class="fas fa-eye"></i> View Only
                    </span>
                    @endif
                </div>
            </div>

            <div class="card-body">
                <!-- Salary Summary Stats with Icons -->
                <div class="salary-stats">
                    <div class="stat-item">
                        <div class="stat-value basic"><i class="fas fa-rupee-sign me-1" style="font-size: 18px;"></i> {{ number_format($salary['basic_salary'] ?? 0, 0) }}</div>
                        <div class="stat-label"><i class="fas fa-chart-line me-1"></i> Monthly Basic Salary</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value gross"><i class="fas fa-rupee-sign me-1" style="font-size: 18px;"></i> {{ number_format($salary['gross_salary'] ?? 0, 0) }}</div>
                        <div class="stat-label"><i class="fas fa-calculator me-1"></i> Monthly Gross Salary</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value net"><i class="fas fa-rupee-sign me-1" style="font-size: 18px;"></i> {{ number_format($salary['monthly_net_salary'] ?? 0, 0) }}</div>
                        <div class="stat-label"><i class="fas fa-wallet me-1"></i> Monthly Net Salary</div>
                    </div>
                    <div class="stat-item">
                        @php
                            $attendanceDeductionsTotal = $salary['attendance_deductions_total'] ?? (
                                ($salary['leave_deduction'] ?? 0) +
                                ($salary['absent_deduction'] ?? 0) +
                                ($salary['unpaid_leave_deduction'] ?? 0) +
                                ($salary['unapproved_leave_deduction'] ?? 0) +
                                ($salary['short_attendance_deduction'] ?? 0) +
                                ($salary['exceeded_short_leaves_deduction'] ?? 0) +
                                ($salary['exceeded_half_days_deduction'] ?? 0)
                            );
                        @endphp
                        <div class="stat-value deduction"><i class="fas fa-rupee-sign me-1" style="font-size: 18px;"></i> {{ number_format($attendanceDeductionsTotal, 0) }}</div>
                        <div class="stat-label"><i class="fas fa-clock me-1"></i> Attendance Deductions</div>
                    </div>
                    <div class="stat-item" style="background: linear-gradient(135deg, #10b98120, #05966920);">
                        <div class="stat-value" style="color: #059669;"><i class="fas fa-rupee-sign me-1" style="font-size: 18px;"></i> {{ number_format($salary['payable_salary'] ?? 0, 0) }}</div>
                        <div class="stat-label" style="color: #059669;"><i class="fas fa-hand-holding-usd me-1"></i> Payable Salary <small>(After Deductions)</small></div>
                    </div>
                </div> 
            </div>

            <div class="card-footer">
                <div>
                    @if($status == 'finalized' && $review)
                    @if($review->finalize_notes)
                    <small class="text-muted">
                        <i class="fas fa-sticky-note"></i> Notes: {{ $review->finalize_notes }}
                    </small>
                    <br>
                    @endif
                    <small class="text-success">
                        <i class="fas fa-check-circle"></i> Finalized on
                        {{ \Carbon\Carbon::parse($review->finalized_at)->format('d M Y h:i A') }}
                        @if($review->finalized_by)
                        by {{ $review->finalized_by_name ?? 'System' }}
                        @endif
                    </small>
                    @elseif($status == 'reviewed' && $review)
                    <small class="text-info">
                        <i class="fas fa-eye"></i> Reviewed on
                        {{ \Carbon\Carbon::parse($review->reviewed_at)->format('d M Y h:i A') }}
                        @if($review->reviewed_by)
                        by {{ $review->reviewed_by_name ?? 'System' }}
                        @endif
                    </small>
                    @endif
                </div>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    @if($status == 'pending')
                    <a href="{{ route('salary.review.show', ['employee_id' => $employee->employee_id, 'year' => $selectedYear, 'month' => $selectedMonth]) }}" class="btn-review btn-outline-primary">
                        <i class="fas fa-edit"></i> Review Salary
                    </a>
                    @endif

                    @if($status == 'reviewed')
                    <a href="{{ route('salary.review.show', ['employee_id' => $employee->employee_id, 'year' => $selectedYear, 'month' => $selectedMonth]) }}" class="btn-review btn-outline-warning">
                        <i class="fas fa-edit"></i> Edit Review
                    </a>
                    <button class="btn-review btn-success-custom"
                        onclick="finalizeSalary('{{ $employee->employee_id }}')">
                        <i class="fas fa-lock"></i> Finalize Salary
                    </button>
                    @endif

                   @if($status == 'finalized')
                    <a href="{{ route('salary.review.viewFinalized', ['employee_id' => $employee->employee_id, 'year' => $selectedYear, 'month' => $selectedMonth]) }}" 
                    class="btn-review btn-outline-primary" target="_blank">
                        <i class="fas fa-history"></i> View Details
                    </a>
                  @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="fas fa-rupee-sign display-1 text-muted mb-3"></i>
            <h4>No salary records found</h4>
            <p class="text-muted">No salary records match the selected filters for
                {{ Carbon\Carbon::create($selectedYear, $selectedMonth)->format('F Y') }}</p>
            <a href="{{ route('salary.review.index') }}" class="btn btn-primary mt-3">
                <i class="fas fa-undo-alt"></i> Reset Filters
            </a>
        </div>
        @endforelse
    </div>
</div>

<!-- Loader Overlay -->
<div id="loaderOverlay" class="loader-overlay">
    <div class="loader-content">
        <div class="spinner"></div>
        <p>Loading salary data...</p>
        <p class="loader-text">Please wait while we fetch the records</p>
    </div>
</div>

<!-- Fixed Bulk Actions Button -->
@if($hasPendingReviews && $reviewStatus != 'finalized')
<div class="bulk-actions">
    <button class="btn btn-success-custom" onclick="bulkFinalizeSalary()">
        <i class="fas fa-lock"></i> Finalize Selected (<span id="floatingSelectedCount">0</span>)
    </button>
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let currentEmployeeId = null;
let currentSalaryData = null;
let currentYear = @json($selectedYear);
let currentMonth = @json($selectedMonth);

// Loader functions
function showLoader() {
    const loader = document.getElementById('loaderOverlay');
    if (loader) {
        loader.classList.add('active');
    }
    const container = document.querySelector('.employees-container');
    if (container) {
        container.classList.add('loading');
    }
}

function hideLoader() {
    const loader = document.getElementById('loaderOverlay');
    if (loader) {
        loader.classList.remove('active');
    }
    const container = document.querySelector('.employees-container');
    if (container) {
        container.classList.remove('loading');
    }
}

function updateSelection() {
    const checkboxes = document.querySelectorAll('.employee-checkbox:checked');
    const selectedCount = checkboxes.length;
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const allCheckboxes = document.querySelectorAll('.employee-checkbox');
    const totalCheckboxes = allCheckboxes.length;

    const selectedCountSpan = document.getElementById('selectedCount');
    const floatingCountSpan = document.getElementById('floatingSelectedCount');

    if (selectedCountSpan) selectedCountSpan.innerText = `${selectedCount} employee(s) selected`;
    if (floatingCountSpan) floatingCountSpan.innerText = selectedCount;

    if (selectAllCheckbox) {
        if (selectedCount === totalCheckboxes && totalCheckboxes > 0) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        } else if (selectedCount > 0 && selectedCount < totalCheckboxes) {
            selectAllCheckbox.indeterminate = true;
        } else {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
    }

    document.querySelectorAll('.employee-card').forEach(card => {
        const checkbox = card.querySelector('.employee-checkbox');
        if (checkbox && checkbox.checked) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
    });
}

function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    if (!selectAllCheckbox) return;

    const isChecked = selectAllCheckbox.checked;
    const allCheckboxes = document.querySelectorAll('.employee-checkbox');

    allCheckboxes.forEach(checkbox => {
        checkbox.checked = isChecked;
    });

    updateSelection();
}

function clearAllSelections() {
    const allCheckboxes = document.querySelectorAll('.employee-checkbox');
    allCheckboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    updateSelection();
}

function reviewSalary(employeeId) {
    currentEmployeeId = employeeId;

    Swal.fire({
        title: 'Loading...',
        text: 'Fetching salary details',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`/salary/review/details?employee_id=${employeeId}&year=${currentYear}&month=${currentMonth}`)
        .then(response => response.json())
        .then(data => {
            Swal.close();

            if (data.success) {
                currentSalaryData = data.data;
                showSalaryReviewModal(data.data);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Not Found',
                    text: data.message || 'Salary data not found. Please finalize attendance first.',
                    confirmButtonColor: '#4361ee'
                });
            }
        })
        .catch(error => {
            Swal.close();
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Error loading salary details',
                confirmButtonColor: '#4361ee'
            });
        });
}

function showSalaryReviewModal(salaryData) {
    // Build Earnings HTML with Icons
    let earningsHtml = '';
    if (salaryData.earnings_breakdown) {
        Object.entries(salaryData.earnings_breakdown).forEach(([key, value]) => {
            if (value > 0) {
                let icon = 'fa-money-bill-wave';
                if (key.toLowerCase().includes('hra')) icon = 'fa-home';
                else if (key.toLowerCase().includes('conveyance')) icon = 'fa-car';
                else if (key.toLowerCase().includes('medical')) icon = 'fa-medkit';
                else if (key.toLowerCase().includes('special')) icon = 'fa-star';
                else if (key.toLowerCase().includes('lta')) icon = 'fa-plane';
                earningsHtml += `
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas ${icon} me-2 text-success"></i>${key.replace(/_/g, ' ').toUpperCase()}</span>
                        <span><i class="fas fa-rupee-sign me-1"></i>${parseFloat(value).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                    </div>
                `;
            }
        });
    }

    // Standard Deductions HTML with Icons
    let standardDeductionsHtml = '';
    if (salaryData.standard_deductions_breakdown && Object.keys(salaryData.standard_deductions_breakdown).length > 0) {
        Object.entries(salaryData.standard_deductions_breakdown).forEach(([key, value]) => {
            if (value > 0) {
                let icon = 'fa-chart-line';
                if (key.toLowerCase().includes('pf')) icon = 'fa-building';
                else if (key.toLowerCase().includes('esi')) icon = 'fa-hospital-user';
                else if (key.toLowerCase().includes('pt')) icon = 'fa-receipt';
                else if (key.toLowerCase().includes('tds')) icon = 'fa-file-invoice-dollar';
                standardDeductionsHtml += `
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas ${icon} me-2 text-danger"></i>${key.replace(/_/g, ' ').toUpperCase()}</span>
                        <span class="text-danger"><i class="fas fa-rupee-sign me-1"></i>-${parseFloat(value).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                    </div>
                `;
            }
        });
    } else {
        standardDeductionsHtml = '<p class="text-muted"><i class="fas fa-info-circle me-1"></i>No standard deductions</p>';
    }

    // Attendance Deductions HTML with Icons
    let attendanceDeductionsHtml = '';
    const attendanceDeductions = salaryData.attendance_deductions_breakdown || {};
    
    if (attendanceDeductions.leave_deduction > 0) {
        attendanceDeductionsHtml += `
            <div class="d-flex justify-content-between mb-2">
                <span><i class="fas fa-umbrella-beach me-2 text-warning"></i>Leave Deduction</span>
                <span class="text-danger"><i class="fas fa-rupee-sign me-1"></i>-${parseFloat(attendanceDeductions.leave_deduction).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
            </div>
        `;
    }
    if (attendanceDeductions.absent_deduction > 0) {
        attendanceDeductionsHtml += `
            <div class="d-flex justify-content-between mb-2">
                <span><i class="fas fa-user-slash me-2 text-danger"></i>Absent Deduction</span>
                <span class="text-danger"><i class="fas fa-rupee-sign me-1"></i>-${parseFloat(attendanceDeductions.absent_deduction).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
            </div>
        `;
    }
    if (attendanceDeductions.unpaid_leave_deduction > 0) {
        attendanceDeductionsHtml += `
            <div class="d-flex justify-content-between mb-2">
                <span><i class="fas fa-hourglass-half me-2 text-warning"></i>Unpaid Leave Deduction</span>
                <span class="text-danger"><i class="fas fa-rupee-sign me-1"></i>-${parseFloat(attendanceDeductions.unpaid_leave_deduction).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
            </div>
        `;
    }
    if (attendanceDeductions.unapproved_leave_deduction > 0) {
        attendanceDeductionsHtml += `
            <div class="d-flex justify-content-between mb-2">
                <span><i class="fas fa-exclamation-triangle me-2 text-danger"></i>Unapproved Leave Deduction</span>
                <span class="text-danger"><i class="fas fa-rupee-sign me-1"></i>-${parseFloat(attendanceDeductions.unapproved_leave_deduction).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
            </div>
        `;
    }
    
    if (attendanceDeductionsHtml === '') {
        attendanceDeductionsHtml = '<p class="text-muted"><i class="fas fa-info-circle me-1"></i>No attendance deductions</p>';
    }

    const otherDeductions = salaryData.other_deductions || 0;
    const otherDeductionsDetails = salaryData.other_deductions_details || [];
    
    // Build Payroll Execution Details HTML
    let payrollExecutionHtml = '';
    if (salaryData.payroll_execution && Object.keys(salaryData.payroll_execution).length > 0) {
        const pe = salaryData.payroll_execution;
        const cycleType = pe.payroll_cycle === 'days' ? 'Daily Cycle' : 'Monthly Cycle';
        const cycleIcon = pe.payroll_cycle === 'days' ? 'fa-calendar-day' : 'fa-calendar-alt';
        
        payrollExecutionHtml = `
            <div class="mb-4">
                <div class="card" style="border: 2px solid var(--border-color); border-radius: 12px; overflow: hidden;">
                    <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 15px;">
                        <h6 class="mb-0" style="font-weight: 600;">
                            <i class="fas ${cycleIcon} me-2"></i> Payroll Execution Details
                        </h6>
                    </div>
                    <div class="card-body" style="padding: 15px;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-row" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border-color);">
                                    <span style="font-weight: 500; color: #4a5568;">
                                        <i class="fas fa-chart-line me-2"></i>Payroll Cycle:
                                    </span>
                                    <span style="font-weight: 600; color: var(--text-dark);">
                                        <span class="badge ${pe.payroll_cycle === 'days' ? 'bg-info' : 'bg-primary'}" style="padding: 5px 12px;">
                                            ${cycleType}
                                        </span>
                                    </span>
                                </div>
                                ${pe.payroll_cycle === 'days' && pe.cycle_days ? `
                                <div class="info-row" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border-color);">
                                    <span style="font-weight: 500; color: #4a5568;">
                                        <i class="fas fa-clock me-2"></i>Cycle Days:
                                    </span>
                                    <span style="font-weight: 600; color: var(--text-dark);">${pe.cycle_days} days</span>
                                </div>
                                ` : ''}
                                ${pe.execution_day ? `
                                <div class="info-row" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border-color);">
                                    <span style="font-weight: 500; color: #4a5568;">
                                        <i class="fas fa-calendar-check me-2"></i>Execution Day:
                                    </span>
                                    <span style="font-weight: 600; color: var(--text-dark);">${pe.execution_day} of month</span>
                                </div>
                                ` : ''}
                            </div>
                            <div class="col-md-6">
                                <div class="info-row" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border-color);">
                                    <span style="font-weight: 500; color: #4a5568;">
                                        <i class="fas fa-dollar-sign me-2"></i>Daily Rate:
                                    </span>
                                    <span style="font-weight: 600; color: #059669;"><i class="fas fa-rupee-sign me-1"></i>${parseFloat(salaryData.daily_rate || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                                </div>
                                <div class="info-row" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border-color);">
                                    <span style="font-weight: 500; color: #4a5568;">
                                        <i class="fas fa-calendar-week me-2"></i>Days in Period:
                                    </span>
                                    <span style="font-weight: 600; color: var(--text-dark);">${salaryData.days_in_period || 0} days</span>
                                </div>
                                <div class="info-row" style="display: flex; justify-content: space-between; padding: 8px 0;">
                                    <span style="font-weight: 500; color: #4a5568;">
                                        <i class="fas fa-money-bill-wave me-2"></i>Pay Date:
                                    </span>
                                    <span style="font-weight: 600; color: #764ba2;">${pe.pay_date || 'End of Month'}</span>
                                </div>
                            </div>
                        </div>
                        ${pe.calculation_basis ? `
                        <div class="mt-3 p-2" style="background: #f0fdf4; border-radius: 8px;">
                            <small style="color: #059669;">
                                <i class="fas fa-info-circle me-1"></i>
                                ${pe.calculation_basis}
                            </small>
                        </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    } else {
        payrollExecutionHtml = `
            <div class="mb-4">
                <div class="card" style="border: 2px solid var(--border-color); border-radius: 12px; overflow: hidden;">
                    <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 15px;">
                        <h6 class="mb-0" style="font-weight: 600;">
                            <i class="fas fa-calendar-alt me-2"></i> Payroll Execution Details
                        </h6>
                    </div>
                    <div class="card-body" style="padding: 15px;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-row" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border-color);">
                                    <span style="font-weight: 500; color: #4a5568;">Payroll Cycle:</span>
                                    <span style="font-weight: 600; color: var(--text-dark);">Monthly Cycle</span>
                                </div>
                                <div class="info-row" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border-color);">
                                    <span style="font-weight: 500; color: #4a5568;">Daily Rate:</span>
                                    <span style="font-weight: 600; color: #059669;"><i class="fas fa-rupee-sign me-1"></i>${parseFloat(salaryData.daily_rate || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-row" style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--border-color);">
                                    <span style="font-weight: 500; color: #4a5568;">Days in Period:</span>
                                    <span style="font-weight: 600; color: var(--text-dark);">${salaryData.days_in_period || 0} days</span>
                                </div>
                                <div class="info-row" style="display: flex; justify-content: space-between; padding: 8px 0;">
                                    <span style="font-weight: 500; color: #4a5568;">Pay Date:</span>
                                    <span style="font-weight: 600; color: #764ba2;">End of Month</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 p-2" style="background: #f0fdf4; border-radius: 8px;">
                            <small style="color: #059669;">
                                <i class="fas fa-info-circle me-1"></i>
                                Salary calculated based on calendar days of the month (${salaryData.days_in_period || 0} days)
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    let calculationNoteHtml = '';
    if (salaryData.calculation_note) {
        calculationNoteHtml = `
            <div class="mb-4">
                <div class="alert alert-info" style="background: #e0f2fe; border-left: 4px solid #0284c7; border-radius: 8px;">
                    <i class="fas fa-calculator me-2"></i>
                    <strong>Calculation Note:</strong>
                    <p class="mb-0 mt-1" style="font-size: 13px;">${salaryData.calculation_note}</p>
                </div>
            </div>
        `;
    }

    const monthlyNetSalary = salaryData.monthly_net_salary || 0;
    const totalStandardDeductions = salaryData.total_standard_deductions || 0;
    const totalAttendanceDeductions = salaryData.total_attendance_deductions || 0;
    const finalPayable = salaryData.payable_salary || (monthlyNetSalary - totalAttendanceDeductions - otherDeductions);

    let otherDeductionsInputHtml = '';
    if (salaryData.review_status !== 'finalized') {
        otherDeductionsInputHtml = `
            <div class="mb-3" id="otherDeductionsContainer">
                <label class="form-label"><strong><i class="fas fa-minus-circle me-1"></i>Other Deductions</strong></label>
                <div id="deductionsList">
        `;
        
        if (otherDeductionsDetails.length > 0) {
            otherDeductionsDetails.forEach((deduction, index) => {
                otherDeductionsInputHtml += `
                    <div class="deduction-item input-group mb-2" data-index="${index}">
                        <input type="text" name="other_deduction_name[]" class="form-control" placeholder="Deduction Name" value="${deduction.name || ''}" style="flex: 2;">
                        <input type="number" name="other_deduction_amount[]" class="form-control deduction-amount" placeholder="Amount" value="${deduction.amount || 0}" step="0.01" style="flex: 1;">
                        <button type="button" class="btn btn-danger remove-deduction" onclick="removeDeductionItem(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            });
        } else {
            otherDeductionsInputHtml += `
                <div class="deduction-item input-group mb-2">
                    <input type="text" name="other_deduction_name[]" class="form-control" placeholder="Deduction Name (e.g., Loan, Advance, Fine)" style="flex: 2;">
                    <input type="number" name="other_deduction_amount[]" class="form-control deduction-amount" placeholder="Amount" value="0" step="0.01" style="flex: 1;">
                    <button type="button" class="btn btn-danger remove-deduction" onclick="removeDeductionItem(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
        }
        
        otherDeductionsInputHtml += `
                </div>
                <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addDeductionItem()">
                    <i class="fas fa-plus"></i> Add Deduction
                </button>
            </div>
        `;
    } else {
        let otherDeductionsDisplayHtml = '';
        if (otherDeductionsDetails.length > 0) {
            otherDeductionsDetails.forEach(deduction => {
                otherDeductionsDisplayHtml += `
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-minus-circle me-1 text-danger"></i>${deduction.name || 'Other Deduction'}</span>
                        <span class="text-danger"><i class="fas fa-rupee-sign me-1"></i>-${parseFloat(deduction.amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                    </div>
                `;
            });
        } else if (otherDeductions > 0) {
            otherDeductionsDisplayHtml = `
                <div class="d-flex justify-content-between mb-2">
                    <span><i class="fas fa-minus-circle me-1 text-danger"></i>Other Deductions</span>
                    <span class="text-danger"><i class="fas fa-rupee-sign me-1"></i>-${parseFloat(otherDeductions).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                </div>
            `;
        } else {
            otherDeductionsDisplayHtml = '<p class="text-muted"><i class="fas fa-info-circle me-1"></i>No other deductions applied</p>';
        }
        
        otherDeductionsInputHtml = `
            <div class="mb-3">
                <label class="form-label"><strong><i class="fas fa-minus-circle me-1"></i>Other Deductions</strong></label>
                ${otherDeductionsDisplayHtml}
            </div>
        `;
    }

    const isFinalized = salaryData.review_status === 'finalized';
    const isReviewed = salaryData.review_status === 'reviewed';

    const modalContent = `
        <form id="salaryReviewForm">
            <input type="hidden" name="employee_id" value="${currentEmployeeId}">
            <input type="hidden" name="year" value="${currentYear}">
            <input type="hidden" name="month" value="${currentMonth}">
            
            <div class="mb-3 p-3 bg-light rounded">
                <div class="row">
                    <div class="col-md-6">
                        <strong><i class="fas fa-user me-1"></i>Employee:</strong> ${salaryData.employee_name}<br>
                        <strong><i class="fas fa-calendar me-1"></i>Salary Month:</strong> ${salaryData.salary_month}
                    </div>
                    <div class="col-md-6">
                        <strong><i class="fas fa-info-circle me-1"></i>Status:</strong> 
                        <span class="badge ${isFinalized ? 'bg-success' : (isReviewed ? 'bg-info' : 'bg-warning')}">
                            ${salaryData.review_status?.toUpperCase() || 'PENDING'}
                        </span>
                    </div>
                </div>
            </div>
            
            ${payrollExecutionHtml}
            
            ${calculationNoteHtml}
            
            ${isFinalized ? `
            <div class="alert alert-info">
                <i class="fas fa-check-circle"></i>
                <strong>Salary Finalized!</strong> This salary has been finalized and is ready for payroll processing.
                ${salaryData.finalized_at ? `<br>Finalized on: ${new Date(salaryData.finalized_at).toLocaleString()}` : ''}
                ${salaryData.finalized_by ? `<br>Finalized by: ${salaryData.finalized_by}` : ''}
                ${salaryData.finalize_notes ? `<br>Notes: ${salaryData.finalize_notes}` : ''}
            </div>
            ` : `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Review Required!</strong> Please review the salary details below. You can adjust the net salary and add other deductions if needed.
            </div>
            `}
            
            <div class="mb-3">
                <h6><i class="fas fa-chart-simple me-1"></i>Attendance Summary</h6>
                <div class="row">
                    <div class="col-md-3">
                        <div class="alert alert-success text-center p-2">
                            <i class="fas fa-check-circle me-1"></i><strong>Present</strong><br>
                            ${salaryData.attendance_summary?.present_days || 0} days
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-danger text-center p-2">
                            <i class="fas fa-times-circle me-1"></i><strong>Absent</strong><br>
                            ${salaryData.attendance_summary?.absent_days || 0} days
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-warning text-center p-2">
                            <i class="fas fa-umbrella-beach me-1"></i><strong>Leave</strong><br>
                            ${salaryData.attendance_summary?.leave_days || 0} days
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-info text-center p-2">
                            <i class="fas fa-percent me-1"></i><strong>Attendance</strong><br>
                            ${salaryData.attendance_summary?.attendance_percentage || 0}%
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <i class="fas fa-plus-circle me-1"></i><strong>Earnings</strong>
                        </div>
                        <div class="card-body">
                            ${earningsHtml || '<p class="text-muted">No earnings data</p>'}
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span><i class="fas fa-calculator me-1"></i>Gross Salary</span>
                                <span><i class="fas fa-rupee-sign me-1"></i>${parseFloat(salaryData.gross_salary || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-danger text-white">
                            <i class="fas fa-minus-circle me-1"></i><strong>Standard Deductions</strong>
                            <small class="d-block text-white-50">(PF, ESI, PT, TDS, etc.)</small>
                        </div>
                        <div class="card-body">
                            ${standardDeductionsHtml}
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span><i class="fas fa-chart-line me-1"></i>Total Standard Deductions</span>
                                <span class="text-danger"><i class="fas fa-rupee-sign me-1"></i>-${parseFloat(salaryData.total_standard_deductions || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <i class="fas fa-clock me-1"></i><strong>Attendance Deductions</strong>
                            <small class="d-block text-muted">(Leave, Absent, etc.)</small>
                        </div>
                        <div class="card-body">
                            ${attendanceDeductionsHtml}
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span><i class="fas fa-clock me-1"></i>Total Attendance Deductions</span>
                                <span class="text-danger"><i class="fas fa-rupee-sign me-1"></i>-${parseFloat(salaryData.total_attendance_deductions || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            ${otherDeductionsInputHtml}
            
            <div class="mb-3 p-3" style="background: #f0fdf4; border-radius: 8px;">
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label"><strong><i class="fas fa-chart-line me-1"></i>Salary Calculation Summary</strong></label>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-wallet me-1"></i>Monthly Net Salary (Before Deductions)</span>
                            <span><i class="fas fa-rupee-sign me-1"></i>${parseFloat(monthlyNetSalary).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-danger">
                            <span><i class="fas fa-clock me-1"></i>Total Attendance Deductions</span>
                            <span><i class="fas fa-rupee-sign me-1"></i>-${parseFloat(totalAttendanceDeductions).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                        </div>
                        ${otherDeductions > 0 ? `
                        <div class="d-flex justify-content-between mb-2 text-danger">
                            <span><i class="fas fa-minus-circle me-1"></i>Other Deductions</span>
                            <span><i class="fas fa-rupee-sign me-1"></i>-${parseFloat(otherDeductions).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                        </div>
                        ` : ''}
                        <hr>
                        <div class="d-flex justify-content-between fw-bold" style="font-size: 16px;">
                            <span><i class="fas fa-hand-holding-usd me-1"></i>Final Payable Salary</span>
                            <span style="color: #059669;"><i class="fas fa-rupee-sign me-1"></i>${parseFloat(finalPayable).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                        </div>
                        <input type="hidden" name="net_salary" id="finalPayableSalaryInput" value="${finalPayable}">
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-sticky-note me-1"></i>Review Notes</label>
                <textarea name="review_notes" class="form-control" rows="2" 
                          placeholder="Add any notes about this salary review..."
                          ${isFinalized ? 'readonly' : ''}>${salaryData.review_notes || ''}</textarea>
            </div>
            
            ${salaryData.leave_breakdown && salaryData.leave_breakdown.length > 0 ? `
            <div class="mt-4">
                <h6 class="mb-3" style="color: #d97706; font-weight: 600;">
                    <i class="fas fa-calendar-times"></i> Leave Impact on Salary
                </h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead style="background: #fef3c7;">
                            <tr>
                                <th><i class="fas fa-tag me-1"></i>Leave Type</th>
                                <th class="text-center"><i class="fas fa-calendar-day me-1"></i>Days Taken</th>
                                <th class="text-center"><i class="fas fa-rupee-sign me-1"></i>Deduction Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${salaryData.leave_breakdown.map(leave => `
                                <tr>
                                    <td><i class="fas ${getLeaveIcon(leave.leave_type || leave.type)} me-1"></i><strong>${leave.leave_type || leave.type || 'Leave'}</strong></td>
                                    <td class="text-center">${leave.days_to_deduct || leave.days || 0} day(s)</td>
                                    <td class="text-center text-danger"><i class="fas fa-rupee-sign me-1"></i>${parseFloat(leave.deduction || leave.deduction_amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
            ` : ''}
        </form>
    `;

    Swal.fire({
        title: `<i class="fas fa-rupee-sign me-2"></i>${salaryData.employee_name} - Salary Review`,
        html: modalContent,
        width: '1000px',
        showCancelButton: !isFinalized,
        showConfirmButton: false,
        cancelButtonText: '<i class="fas fa-times me-1"></i>Close',
        cancelButtonColor: '#dc2626',
        didOpen: () => {
            if (!isFinalized) {
                const deductionAmounts = document.querySelectorAll('.deduction-amount');
                deductionAmounts.forEach(input => {
                    input.addEventListener('input', updateFinalPayable);
                });
                
                const modal = Swal.getPopup();
                const footer = document.createElement('div');
                footer.style.display = 'flex';
                footer.style.justifyContent = 'space-between';
                footer.style.marginTop = '15px';
                footer.style.paddingTop = '15px';
                footer.style.borderTop = '1px solid var(--border-color)';
                
                const finalizeBtn = document.createElement('button');
                finalizeBtn.className = 'btn btn-success';
                finalizeBtn.innerHTML = '<i class="fas fa-lock me-1"></i> Finalize Salary';
                finalizeBtn.style.marginRight = '10px';
                finalizeBtn.onclick = () => {
                    Swal.close();
                    confirmFinalizeSalary();
                };
                
                const reviewBtn = document.createElement('button');
                reviewBtn.className = 'btn btn-primary';
                reviewBtn.innerHTML = '<i class="fas fa-save me-1"></i> Save Review';
                reviewBtn.onclick = () => {
                    submitSalaryReview();
                };
                
                footer.appendChild(finalizeBtn);
                footer.appendChild(reviewBtn);
                
                const actions = modal.querySelector('.swal2-actions');
                if (actions) {
                    actions.parentNode.insertBefore(footer, actions);
                }
            }
        }
    });
}

function getLeaveIcon(leaveType) {
    const icons = {
        'Casual Leave': 'fa-coffee',
        'Sick Leave': 'fa-thermometer-half',
        'Earned Leave': 'fa-star',
        'Unpaid Leave': 'fa-money-bill-wave',
        'Maternity Leave': 'fa-baby',
        'Paternity Leave': 'fa-baby-carriage',
        'Short Leave': 'fa-hourglass-start',
        'Half Day': 'fa-sun',
        'Unapproved Leave': 'fa-exclamation-triangle'
    };
    return icons[leaveType] || 'fa-calendar-alt';
}

function updateFinalPayable() {
    const monthlyNetSalaryText = document.querySelector('.d-flex.justify-content-between.mb-2 span:last-child')?.innerText || '₹0';
    const monthlyNetSalary = parseFloat(monthlyNetSalaryText.replace(/[^0-9.-]/g, '')) || 0;
    
    const standardDeductionsText = document.querySelector('.card-header.bg-danger + .card-body .d-flex.justify-content-between.fw-bold span:last-child')?.innerText || '-₹0';
    const totalStandardDeductions = Math.abs(parseFloat(standardDeductionsText.replace(/[^0-9.-]/g, ''))) || 0;
    
    const attendanceDeductionsText = document.querySelector('.card-header.bg-warning + .card-body .d-flex.justify-content-between.fw-bold span:last-child')?.innerText || '-₹0';
    const totalAttendanceDeductions = Math.abs(parseFloat(attendanceDeductionsText.replace(/[^0-9.-]/g, ''))) || 0;
    
    let otherDeductionsTotal = 0;
    const deductionAmounts = document.querySelectorAll('.deduction-amount');
    deductionAmounts.forEach(input => {
        const amount = parseFloat(input.value) || 0;
        otherDeductionsTotal += amount;
    });
    
    const finalPayable = monthlyNetSalary - totalStandardDeductions - totalAttendanceDeductions - otherDeductionsTotal;
    const finalPayableInput = document.getElementById('finalPayableSalaryInput');
    if (finalPayableInput) {
        finalPayableInput.value = finalPayable.toFixed(2);
    }
    
    const summaryDiv = document.querySelector('.d-flex.justify-content-between.fw-bold[style*="font-size: 16px"] span:last-child');
    if (summaryDiv) {
        summaryDiv.innerHTML = `<i class="fas fa-rupee-sign me-1"></i>${finalPayable.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
    }
}

function addDeductionItem() {
    const deductionsList = document.getElementById('deductionsList');
    if (deductionsList) {
        const newItem = document.createElement('div');
        newItem.className = 'deduction-item input-group mb-2';
        newItem.innerHTML = `
            <input type="text" name="other_deduction_name[]" class="form-control" placeholder="Deduction Name" style="flex: 2;">
            <input type="number" name="other_deduction_amount[]" class="form-control deduction-amount" placeholder="Amount" value="0" step="0.01" style="flex: 1;">
            <button type="button" class="btn btn-danger remove-deduction" onclick="removeDeductionItem(this)">
                <i class="fas fa-trash"></i>
            </button>
        `;
        deductionsList.appendChild(newItem);
        
        const newAmountInput = newItem.querySelector('.deduction-amount');
        if (newAmountInput) {
            newAmountInput.addEventListener('input', updateFinalPayable);
        }
    }
}

function removeDeductionItem(button) {
    const item = button.closest('.deduction-item');
    if (item) {
        item.remove();
        updateFinalPayable();
    }
}

function submitSalaryReview() {
    const form = document.getElementById('salaryReviewForm');
    const formData = new FormData(form);
    
    const deductionNames = document.querySelectorAll('input[name="other_deduction_name[]"]');
    const deductionAmounts = document.querySelectorAll('input[name="other_deduction_amount[]"]');
    const otherDeductionsDetails = [];
    let totalOtherDeductions = 0;
    
    for (let i = 0; i < deductionNames.length; i++) {
        const name = deductionNames[i]?.value || '';
        const amount = parseFloat(deductionAmounts[i]?.value) || 0;
        if (amount > 0) {
            otherDeductionsDetails.push({ name, amount });
            totalOtherDeductions += amount;
        }
    }
    
    const data = {
        employee_id: formData.get('employee_id'),
        year: parseInt(formData.get('year')),
        month: parseInt(formData.get('month')),
        net_salary: parseFloat(formData.get('net_salary')),
        review_notes: formData.get('review_notes') || '',
        other_deductions: totalOtherDeductions,
        other_deductions_details: otherDeductionsDetails
    };
    
    Swal.fire({
        title: 'Save Review',
        text: 'Are you sure you want to save these salary details?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4361ee',
        cancelButtonColor: '#dc2626',
        confirmButtonText: 'Yes, Save',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch('/salary/review/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Salary reviewed successfully',
                        confirmButtonColor: '#10b981',
                        timer: 2000
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Error saving review',
                        confirmButtonColor: '#4361ee'
                    });
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Error saving review',
                    confirmButtonColor: '#4361ee'
                });
            });
        }
    });
}

function confirmFinalizeSalary() {
    Swal.fire({
        title: 'Finalize Salary',
        html: `
            <div class="text-left">
                <p><strong>Are you sure you want to finalize this salary?</strong></p>
                <p class="text-muted">This action will:</p>
                <ul class="text-muted small">
                    <li><i class="fas fa-lock me-1"></i>Lock the salary amount for ${currentSalaryData?.employee_name}</li>
                    <li><i class="fas fa-check-circle me-1"></i>Mark it as ready for payroll processing</li>
                    <li><i class="fas fa-ban me-1"></i>Cannot be undone after finalization</li>
                </ul>
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-sticky-note me-1"></i>Finalization Notes (Optional)</label>
                    <textarea id="finalizeNotes" class="form-control" rows="2" 
                              placeholder="Add any notes about this finalization..."></textarea>
                </div>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#dc2626',
        confirmButtonText: '<i class="fas fa-lock me-1"></i>Yes, Finalize Salary',
        cancelButtonText: '<i class="fas fa-times me-1"></i>Cancel',
        preConfirm: () => {
            const finalizeNotes = document.getElementById('finalizeNotes').value;
            return { finalize_notes: finalizeNotes };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Finalizing...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            const finalizeData = {
                employee_id: currentEmployeeId,
                year: currentYear,
                month: currentMonth,
                finalize_notes: result.value?.finalize_notes || ''
            };
            
            fetch('/salary/review/finalize', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(finalizeData)
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Finalized!',
                        text: 'Salary finalized successfully for payroll',
                        confirmButtonColor: '#10b981',
                        timer: 2000
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Error finalizing salary',
                        confirmButtonColor: '#4361ee'
                    });
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Error finalizing salary',
                    confirmButtonColor: '#4361ee'
                });
            });
        }
    });
}

function finalizeSalary(employeeId) {
    currentEmployeeId = employeeId;
    
    fetch(`/salary/review/details?employee_id=${employeeId}&year=${currentYear}&month=${currentMonth}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentSalaryData = data.data;
                confirmFinalizeSalary();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'Error loading salary details',
                    confirmButtonColor: '#4361ee'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Error loading salary details',
                confirmButtonColor: '#4361ee'
            });
        });
}

function viewFinalizedSalary(employeeId) {
    window.open(`/salary/review/view-finalized?employee_id=${employeeId}&year=${currentYear}&month=${currentMonth}`, '_blank');
}

function bulkFinalizeSalary() {
    const selectedCheckboxes = document.querySelectorAll('.employee-checkbox:checked');
    
    if (selectedCheckboxes.length === 0) {
        Swal.fire({
            icon: 'info',
            title: 'No Selection',
            text: 'Please select at least one employee to finalize salary.',
            confirmButtonColor: '#4361ee'
        });
        return;
    }
    
    const employeeIds = Array.from(selectedCheckboxes).map(cb => cb.dataset.employeeId);
    
    Swal.fire({
        title: 'Finalize Selected Salaries',
        html: `
            <div class="text-left">
                <p>You are about to finalize salary for <strong>${employeeIds.length}</strong> employee(s).</p>
                <p class="text-muted">This action will:</p>
                <ul class="text-muted small text-left">
                    <li><i class="fas fa-lock me-1"></i>Lock salary records for all selected employees</li>
                    <li><i class="fas fa-check-circle me-1"></i>Mark them as ready for payroll processing</li>
                    <li><i class="fas fa-ban me-1"></i>Cannot be undone after finalization</li>
                </ul>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#dc2626',
        confirmButtonText: '<i class="fas fa-lock me-1"></i>Yes, Finalize Selected',
        cancelButtonText: '<i class="fas fa-times me-1"></i>Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Finalizing...',
                text: 'Please wait while we finalize the salaries',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch('/salary/review/bulk-finalize', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    employee_ids: employeeIds,
                    year: currentYear,
                    month: currentMonth
                })
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Finalized!',
                        text: data.message,
                        confirmButtonColor: '#10b981',
                        timer: 2000
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Error performing bulk finalize',
                        confirmButtonColor: '#4361ee'
                    });
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Error performing bulk finalize',
                    confirmButtonColor: '#4361ee'
                });
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            showLoader();
        });
    }

    const filterInputs = document.querySelectorAll('.filter-input');
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.form) {
                showLoader();
                this.form.submit();
            }
        });
    });

    const resetBtn = document.querySelector('.btn-reset');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            showLoader();
        });
    }

    window.addEventListener('load', function() {
        setTimeout(() => {
            hideLoader();
        }, 300);
    });

    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            hideLoader();
        }
    });

    updateSelection();
});

window.reviewSalary = reviewSalary;
window.finalizeSalary = finalizeSalary;
window.viewFinalizedSalary = viewFinalizedSalary;
window.bulkFinalizeSalary = bulkFinalizeSalary;
window.updateSelection = updateSelection;
window.toggleSelectAll = toggleSelectAll;
window.clearAllSelections = clearAllSelections;
</script>
@endsection