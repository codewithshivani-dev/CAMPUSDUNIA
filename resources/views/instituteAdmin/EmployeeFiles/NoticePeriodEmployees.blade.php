@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<title>Employees on Notice Period</title>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
    --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
    --success-gradient: linear-gradient(135deg, #10b981, #059669);
    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* ===== STATS CARDS ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.stat-card {
    background: #fff;
    border-radius: 12px;
    padding: 18px 20px;
    box-shadow: var(--shadow-sm);
    border-left: 4px solid #4361ee;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stat-card .stat-icon {
    font-size: 24px;
    opacity: 0.7;
}

.stat-card .stat-number {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
}

.stat-card .stat-label {
    font-size: 14px;
    color: #64748b;
    font-weight: 500;
}

.stat-card.warning {
    border-left-color: #f59e0b;
}

.stat-card.warning .stat-number {
    color: #d97706;
}

.stat-card.danger {
    border-left-color: #ef4444;
}

.stat-card.danger .stat-number {
    color: #dc2626;
}

.stat-card.success {
    border-left-color: #10b981;
}

.stat-card.success .stat-number {
    color: #059669;
}

/* ===== FILTER CONTAINER ===== */
.filter-container {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: var(--shadow-sm);
}

.filter-form {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    align-items: flex-end;
}

.filter-group {
    flex: 1;
    min-width: 150px;
}

.filter-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    margin-bottom: 4px;
}

.filter-input {
    width: 100%;
    padding: 10px 12px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s;
    background: #f8fafc;
}

.filter-input:focus {
    outline: none;
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    background: #fff;
}

.filter-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

/* ===== NOTICE PERIOD TABLE - FIXED SCROLL ===== */
.notice-table-wrapper {
    background: #fff;
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
    /* CRITICAL: Enable scrolling */
    overflow: auto;
    max-height: 550px;
    overflow-y: auto;
    overflow-x: auto;
    /* For smooth scrolling */
    scroll-behavior: smooth;
}

/* Custom scrollbar styling */
.notice-table-wrapper::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.notice-table-wrapper::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.notice-table-wrapper::-webkit-scrollbar-thumb {
    background: #c1c7cd;
    border-radius: 4px;
}

.notice-table-wrapper::-webkit-scrollbar-thumb:hover {
    background: #a0a7ae;
}

/* Table styling */
.notice-table {
    width: 100%;
    min-width: 1000px; /* Ensures horizontal scroll when needed */
    border-collapse: collapse;
    margin-bottom: 0;
}

/* Sticky header for table */
.notice-table thead {
    background: var(--primary-gradient);
    position: sticky;
    top: 0;
    z-index: 10;
}

.notice-table th {
    padding: 14px 16px;
    text-align: left;
    color: #fff;
    font-weight: 600;
    font-size: 13px;
    letter-spacing: 0.3px;
    white-space: nowrap;
    cursor: pointer;
    user-select: none;
    position: relative;
    background: var(--primary-gradient); /* Ensure background stays */
}

.notice-table th.sortable:hover {
    background: rgba(255, 255, 255, 0.1);
}

.sort-icons {
    display: inline-flex;
    flex-direction: column;
    margin-left: 6px;
    font-size: 10px;
    vertical-align: middle;
}

.sort-icon {
    color: rgba(255, 255, 255, 0.4);
    line-height: 1;
}

.sort-icon.active {
    color: #fff;
}

.notice-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    font-size: 14px;
    color: #1e293b;
    background: #fff;
}

.notice-table tbody tr {
    transition: background 0.2s;
}

.notice-table tbody tr:hover {
    background: #f8fafc;
}

.notice-table tbody tr:hover td {
    background: #f8fafc;
}

.notice-table tbody tr.overdue-row td {
    background: #fef2f2;
}

.notice-table tbody tr.overdue-row:hover td {
    background: #fee2e2;
}

.notice-table tbody tr.ending-today td {
    background: #fffbeb;
}

.notice-table tbody tr.ending-today:hover td {
    background: #fef3c7;
}

/* ===== STATUS BADGES ===== */
.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-notice {
    background: #dbeafe;
    color: #1e40af;
}

.status-overdue {
    background: #fee2e2;
    color: #991b1b;
    animation: pulse-warning 2s infinite;
}

.status-today {
    background: #fef3c7;
    color: #92400e;
}

@keyframes pulse-warning {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

/* ===== DAYS REMAINING BAR ===== */
.days-bar {
    width: 100%;
    max-width: 120px;
    height: 6px;
    background: #e2e8f0;
    border-radius: 3px;
    overflow: hidden;
    margin-top: 4px;
}

.days-bar-fill {
    height: 100%;
    border-radius: 3px;
    transition: width 0.6s ease;
}

.days-bar-fill.green {
    background: #10b981;
}

.days-bar-fill.yellow {
    background: #f59e0b;
}

.days-bar-fill.red {
    background: #ef4444;
}

/* ===== PAGE HEADER ===== */
.page-header {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding: 20px 24px;
    background: var(--primary-gradient);
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
}

.page-title {
    color: #fff;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-title i {
    background: rgba(255, 255, 255, 0.2);
    padding: 10px;
    border-radius: 12px;
    font-size: 20px;
}

.page-title small {
    font-weight: 400;
    font-size: 14px;
    opacity: 0.8;
    display: block;
    margin-top: 2px;
}

.btn-back {
    padding: 10px 20px;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    border-radius: 10px;
    color: #fff;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
    text-decoration: none;
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    color: #fff;
}

.btn-back i {
    margin-right: 8px;
}

/* ===== ACTION BUTTONS ===== */
.table-actions {
    display: flex;
    gap: 6px;
    justify-content: center;
    flex-wrap: wrap;
}

.action-btn {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
    text-decoration: none;
}

.action-btn-view {
    background: #e0f2fe;
    color: #0369a1;
}

.action-btn-view:hover {
    background: #bae6fd;
    color: #0369a1;
}

.action-btn-edit {
    background: #fef3c7;
    color: #92400e;
}

.action-btn-edit:hover {
    background: #fde68a;
    color: #92400e;
}

.action-btn-approve {
    background: #dcfce7;
    color: #166534;
}

.action-btn-approve:hover {
    background: #bbf7d0;
    color: #166534;
}

.action-btn-delete {
    background: #fee2e2;
    color: #dc2626;
}

.action-btn-delete:hover {
    background: #fecaca;
    color: #dc2626;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #64748b;
}

.empty-state-icon {
    font-size: 56px;
    color: #cbd5e1;
    margin-bottom: 16px;
}

/* ===== PAGINATION ===== */
.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    background: #fff;
    border-radius: 0 0 12px 12px;
    border-top: 1px solid #f1f5f9;
    flex-wrap: wrap;
    gap: 10px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .filter-form {
        flex-direction: column;
    }
    
    .filter-group {
        min-width: 100%;
    }
    
    .notice-table-wrapper {
        max-height: 400px;
        overflow-x: auto;
    }
    
    .notice-table {
        min-width: 800px;
    }
    
    .pagination-wrapper {
        flex-direction: column;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .notice-table {
        min-width: 650px;
    }
    
    .page-title {
        font-size: 16px;
    }
    
    .page-title i {
        font-size: 16px;
        padding: 8px;
    }
}
</style>

<!-- ===== PAGE HEADER ===== -->
<div class="page-header">
    <div class="page-title">
        <i class="fas fa-clock"></i>
        <div>
            Employees on Notice Period
            <small>Employees who have submitted resignation and are serving notice period</small>
        </div>
    </div>
    <a href="{{ route('employees.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Employees
    </a>
</div>

<!-- ===== STATS CARDS ===== -->
<div class="stats-grid d-none">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-number">{{ $totalNotice }}</div>
        <div class="stat-label">Total on Notice</div>
    </div>
    <div class="stat-card warning">
        <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
        <div class="stat-number">{{ $endingToday }}</div>
        <div class="stat-label">Ending Today</div>
    </div>
    <div class="stat-card danger">
        <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-number">{{ $overdue }}</div>
        <div class="stat-label">Overdue</div>
    </div>
</div>

<!-- ===== FILTERS ===== -->
<div class="filter-container">
    <form method="GET" class="filter-form" id="filterForm">
        <div class="filter-group">
            <label for="name"><i class="fas fa-user"></i> Employee Name</label>
            <input list="employeeNamesList" name="name" id="name" class="filter-input" 
                   value="{{ request('name') }}" placeholder="Search by name">
            <datalist id="employeeNamesList">
                @foreach($employeeNames as $emp)
                    <option value="{{ $emp->name }}">
                @endforeach
            </datalist>
        </div>

        <div class="filter-group">
            <label for="employee_code"><i class="fas fa-id-badge"></i> Employee Code</label>
            <input list="employeeCodeList" name="employee_code" id="employee_code" class="filter-input" 
                   value="{{ request('employee_code') }}" placeholder="Search by code">
            <datalist id="employeeCodeList">
                @foreach($employeeCodes as $code)
                    <option value="{{ $code->employee_code }}">
                @endforeach
            </datalist>
        </div>

        <div class="filter-group">
            <label for="department_id"><i class="fas fa-building"></i> Department</label>
            <select name="department_id" id="department_id" class="filter-input">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->department_id }}" 
                            {{ request('department_id') == $dept->department_id ? 'selected' : '' }}>
                        {{ $dept->department }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label for="designation"><i class="fas fa-briefcase"></i> Designation</label>
            <input list="designationList" name="designation" id="designation" class="filter-input" 
                   value="{{ request('designation') }}" placeholder="Search designation">
            <datalist id="designationList">
                @foreach($designations as $ds)
                    <option value="{{ $ds->designation }}">
                @endforeach
            </datalist>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn-filter btn-filter-primary d-none">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary">
                <i class="fas fa-undo-alt"></i> Reset
            </a>
        </div>

        <!-- Hidden sort inputs -->
        <input type="hidden" name="sort_by" id="sortBy" value="{{ request('sort_by', 'employee_details.created_at') }}">
        <input type="hidden" name="sort_order" id="sortOrder" value="{{ request('sort_order', 'desc') }}">
    </form>
</div>

<!-- ===== RESULTS COUNT ===== -->
<div class="mb-3">
    <div class="alert alert-secondary py-2 mb-0" style="font-size: 0.95rem; border-radius: 8px;">
        Showing <strong>{{ $employees->total() }}</strong> employee{{ $employees->total() == 1 ? '' : 's' }} on notice period
        @if(request()->filled('name') || request()->filled('employee_code') || request()->filled('department_id') || request()->filled('designation'))
            <span class="text-muted">(filtered)</span>
        @endif
    </div>
</div>

<!-- ===== NOTICE PERIOD TABLE ===== -->
<div class="notice-table-wrapper">
    <table class="notice-table">
        <thead>
            <tr>
                <th class="sortable" onclick="sortTable('employee_details.employee_code')">
                    Employee Code
                    <span class="sort-icons">
                        <i class="sort-icon fas fa-caret-up {{ request('sort_by') == 'employee_details.employee_code' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                        <i class="sort-icon fas fa-caret-down {{ request('sort_by') == 'employee_details.employee_code' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                    </span>
                </th>
                <th class="sortable" onclick="sortTable('employee_details.name')">
                    Employee Name
                    <span class="sort-icons">
                        <i class="sort-icon fas fa-caret-up {{ request('sort_by') == 'employee_details.name' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                        <i class="sort-icon fas fa-caret-down {{ request('sort_by') == 'employee_details.name' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                    </span>
                </th>
                <th class="sortable" onclick="sortTable('employee_details.designation')">
                    Designation
                    <span class="sort-icons">
                        <i class="sort-icon fas fa-caret-up {{ request('sort_by') == 'employee_details.designation' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                        <i class="sort-icon fas fa-caret-down {{ request('sort_by') == 'employee_details.designation' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                    </span>
                </th>
                <th class="sortable" onclick="sortTable('departments.department')">
                    Department
                    <span class="sort-icons">
                        <i class="sort-icon fas fa-caret-up {{ request('sort_by') == 'departments.department' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                        <i class="sort-icon fas fa-caret-down {{ request('sort_by') == 'departments.department' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                    </span>
                </th>
                <th class="sortable" onclick="sortTable('employee_exits.notice_end_date')">
                    Notice Period
                    <span class="sort-icons">
                        <i class="sort-icon fas fa-caret-up {{ request('sort_by') == 'employee_exits.notice_end_date' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                        <i class="sort-icon fas fa-caret-down {{ request('sort_by') == 'employee_exits.notice_end_date' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                    </span>
                </th>
                <th>Days Remaining</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $emp)
                @php
                    $exit = $emp->activeExit;
                    $noticeEndDate = $exit ? \Carbon\Carbon::parse($exit->notice_end_date) : null;
                    $daysRemaining = $noticeEndDate ? \Carbon\Carbon::now()->diffInDays($noticeEndDate, false) : null;
                    $isOverdue = $daysRemaining !== null && $daysRemaining < 0;
                    $isEndingToday = $daysRemaining !== null && $daysRemaining == 0;
                    
                    $barColor = 'green';
                    $barWidth = 100;
                    if ($isOverdue) {
                        $barColor = 'red';
                        $barWidth = 100;
                    } elseif ($isEndingToday) {
                        $barColor = 'yellow';
                        $barWidth = 100;
                    } elseif ($daysRemaining !== null) {
                        // Calculate progress (closer to end = more red)
                        $totalDays = $exit->notice_period_days ?? 30;
                        $progress = min(100, max(0, ((1 - $daysRemaining / $totalDays) * 100)));
                        $barWidth = $progress;
                        if ($progress > 70) $barColor = 'yellow';
                        if ($progress > 90) $barColor = 'red';
                    }
                    
                    $rowClass = '';
                    if ($isOverdue) $rowClass = 'overdue-row';
                    if ($isEndingToday) $rowClass = 'ending-today';
                @endphp
                <tr class="{{ $rowClass }}">
                    <td>
                        <strong class="text-primary">{{ $emp->employee_code }}</strong>
                    </td>
                    <td>
                        <strong>{{ $emp->name }}</strong>
                        <div class="small text-muted">
                            <i class="fas fa-envelope"></i> {{ $emp->email ?? 'N/A' }}
                        </div>
                    </td>
                    <td>{{ $emp->designation ?? 'N/A' }}
                        
                    </td>
                    <td>{{ $emp->department_name ?? 'N/A' }}</td>
                    <td>
                        @if($noticeEndDate)
                            <div>
                                <i class="fas fa-calendar-alt"></i>
                                Ending: {{ $noticeEndDate->format('d-M-Y') }}
                            </div>
                            @if($exit->notice_start_date)
                                <div class="small text-muted">
                                    Started: {{ \Carbon\Carbon::parse($exit->notice_start_date)->format('d-M-Y') }}
                                </div>
                            @endif
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($daysRemaining !== null)
                            <div>
                                <strong class="{{ $isOverdue ? 'text-danger' : ($isEndingToday ? 'text-warning' : 'text-success') }}">
                                    @if($isOverdue)
                                        <i class="fas fa-exclamation-triangle"></i> {{ abs($daysRemaining) }} day(s) overdue
                                    @elseif($isEndingToday)
                                        <i class="fas fa-clock"></i> Ends today!
                                    @else
                                        {{ $daysRemaining }} day(s)
                                    @endif
                                </strong>
                            </div>
                            <div class="days-bar">
                                <div class="days-bar-fill {{ $barColor }}" style="width: {{ $barWidth }}%;"></div>
                            </div>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($isOverdue)
                            <span class="status-badge status-overdue">
                                <i class="fas fa-exclamation-circle"></i> Overdue
                            </span>
                        @elseif($isEndingToday)
                            <span class="status-badge status-today">
                                <i class="fas fa-calendar-day"></i> Ends Today
                            </span>
                        @else
                            <span class="status-badge status-notice">
                                <i class="fas fa-hourglass-half"></i> Notice Period
                            </span>
              
                            @endif
                    </td>
                    <td class="text-center">
                        <div class="table-actions justify-content-center">
                            <a href="{{ url('/employee-details/' . $emp->id) }}" 
                               class="action-btn action-btn-view" title="View Employee">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('employees.edit', $emp->employee_id) }}" 
                               class="action-btn action-btn-edit" title="Edit Employee">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($exit && $exit->exit_status === 'notice_period')
                                <a href="{{ route('employee.exit.initiate', $emp->employee_id) }}" 
                                   class="action-btn action-btn-approve" title="Manage Exit Process">
                                    <i class="fas fa-check-circle"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h4>No Employees on Notice Period</h4>
                            <p>There are currently no employees serving their notice period.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ===== PAGINATION ===== -->
    @if($employees->hasPages())
        <div class="pagination-wrapper">
            <div class="text-sm text-gray-600">
                Showing {{ $employees->firstItem() ?? 0 }} to {{ $employees->lastItem() ?? 0 }} of {{ $employees->total() }} results
            </div>
            <div>
                {{ $employees->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
            </div>
        </div>
    @endif
</div>

<!-- ===== SCRIPTS ===== -->
<script>
// Sorting function
function sortTable(column) {
    const currentSortBy = document.getElementById('sortBy').value;
    const currentSortOrder = document.getElementById('sortOrder').value;
    
    let newSortOrder = 'asc';
    if (currentSortBy === column) {
        newSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
    }
    
    document.getElementById('sortBy').value = column;
    document.getElementById('sortOrder').value = newSortOrder;
    document.getElementById('filterForm').submit();
}

// Auto-submit on filter changes (with debounce)
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filterForm');
    let typingTimer;
    const delay = 800;
    
    // Auto-submit for text inputs
    const autoInputs = ['name', 'employee_code', 'designation'];
    autoInputs.forEach(name => {
        const input = document.querySelector(`input[name="${name}"]`);
        if (!input) return;
        
        input.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                form.submit();
            }, delay);
        });
    });
    
    // Auto-submit for select dropdowns
    const selectInputs = ['department_id'];
    selectInputs.forEach(name => {
        const select = document.querySelector(`select[name="${name}"]`);
        if (!select) return;
        
        select.addEventListener('change', function() {
            form.submit();
        });
    });
});

// Show loader on form submit
document.getElementById('filterForm')?.addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('.btn-filter-primary');
    if (submitBtn) {
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Loading...';
        submitBtn.disabled = true;
    }
});
</script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@endsection