@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Probation Employees Management</title>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.page-header {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding: 20px 25px;
    background: linear-gradient(135deg, #4361ee, #3a0ca3);
    border-radius: 15px;
    animation: fadeIn 0.5s ease;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
}

.page-title {
    font-weight: 600;
    color: white;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-title i {
    background: rgba(255, 255, 255, 0.2);
    padding: 10px 12px;
    border-radius: 12px;
}

.page-title .badge-count {
    background: rgba(255, 255, 255, 0.25);
    color: white;
    font-size: 14px;
    padding: 4px 14px;
    border-radius: 20px;
    margin-left: 10px;
}

.filter-container {
    background: #fff;
    border-radius: 12px;
    padding: 20px 25px;
    margin-bottom: 20px;
    border: 1px solid #e2e8f0;
    animation: slideUp 0.3s ease;
    box-shadow: var(--shadow-sm);
}

.filter-grid {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    flex: 1;
}

.filter-group {
    flex: 1;
    min-width: 160px;
}

.filter-group label {
    font-size: 12px;
    font-weight: 600;
    color: #475569;
    margin-bottom: 4px;
    display: block;
}

.filter-input {
    padding: 10px 14px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    background: #fff;
    transition: all 0.2s;
    width: 100%;
}

.filter-input:focus {
    outline: none;
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.filter-input:hover {
    border-color: #cbd5e1;
}

.filter-info {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #e2e8f0;
    font-size: 13px;
    color: #64748b;
}

.bulk-actions-container {
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    animation: slideDown 0.3s ease;
    background: #fff;
    padding: 14px 20px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: var(--shadow-sm);
    gap: 12px;
}

.bulk-actions-container .selected-count {
    font-weight: 500;
    color: #4361ee;
    font-size: 14px;
    background: #e0e7ff;
    padding: 5px 16px;
    border-radius: 20px;
}

.bulk-action-btn {
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    color: #475569;
    transition: all 0.2s;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 6px;
    border: 1px solid transparent;
}

.bulk-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.bulk-action-btn.success {
    background: #dcfce7;
    color: #166534;
    border-color: #bbf7d0;
}
.bulk-action-btn.success:hover {
    background: #bbf7d0;
}
.bulk-action-btn.secondary {
    background: #f1f5f9;
    color: #475569;
    border-color: #e2e8f0;
}
.bulk-action-btn.secondary:hover {
    background: #e2e8f0;
}
.bulk-action-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none !important;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    background: white;
    box-shadow: var(--shadow-sm);
}

.erp-table {
    width: 100%;
    min-width: 1100px;
    background: #fff;
    border-collapse: collapse;
}

.table-responsive::-webkit-scrollbar {
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.erp-table thead {
    background: var(--primary-gradient);
    position: sticky;
    top: 0;
    z-index: 10;
}

.erp-table th {
    padding: 14px 12px;
    font-weight: 600;
    color: white;
    text-align: left;
    font-size: 13px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    cursor: pointer;
    user-select: none;
    transition: background-color 0.2s;
    position: relative;
    white-space: nowrap;
}

.erp-table th:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.erp-table td {
    padding: 14px 12px;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
    font-size: 13px;
    vertical-align: middle;
}

.erp-table tbody tr {
    transition: background-color 0.2s, transform 0.2s;
}
.erp-table tbody tr:hover {
    background-color: #f8fafc;
}
.erp-table tbody tr:last-child td {
    border-bottom: none;
}

.erp-table tbody tr.completed-probation {
    background-color: #f0fdf4;
}
.erp-table tbody tr.completed-probation:hover {
    background-color: #dcfce7;
}

.select-checkbox {
    width: 18px;
    height: 18px;
    cursor: pointer;
    border-radius: 4px;
    border: 2px solid #cbd5e1;
    transition: all 0.2s;
    accent-color: #4361ee;
}

.select-checkbox:hover {
    border-color: #3b82f6;
}

.sticky-checkbox {
    width: 40px;
    text-align: center;
}

.employee-name {
    font-weight: 600;
    color: #0f172a;
}
.employee-code {
    color: #64748b;
    font-size: 12px;
}

.probation-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}

.probation-active {
    background: #fef3c7;
    color: #92400e;
}
.probation-ending-soon {
    background: #fef3c7;
    color: #92400e;
    animation: pulse-warning 1.5s ease-in-out infinite;
}
.probation-overdue {
    background: #fee2e2;
    color: #991b1b;
    animation: pulse-danger 1s ease-in-out infinite;
}
.probation-today {
    background: #dbeafe;
    color: #1e40af;
    animation: pulse-info 1.5s ease-in-out infinite;
}
.probation-completed {
    background: #d1fae5;
    color: #065f46;
}

@keyframes pulse-warning {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.6; }
}
@keyframes pulse-danger {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
@keyframes pulse-info {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.days-left {
    font-weight: 600;
    font-size: 13px;
}
.days-left.positive {
    color: #059669;
}
.days-left.negative {
    color: #dc2626;
}
.days-left.zero {
    color: #2563eb;
}

.emp-type-badge {
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}
.emp-type-full-time {
    background: #dcfce7;
    color: #166534;
}
.emp-type-part-time {
    background: #f1f5f9;
    color: #475569;
}
.emp-type-contract {
    background: #dbeafe;
    color: #1e40af;
}
.emp-type-probation {
    background: #fef3c7;
    color: #92400e;
}

.designation-badge {
    display: inline-block;
    padding: 4px 12px;
    background: #e0e7ff;
    color: #4338ca;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.table-actions {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    justify-content: center;
}

.action-btn {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 4px;
    text-decoration: none;
    white-space: nowrap;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-decoration: none;
}

.action-btn-success {
    background: #dcfce7;
    color: #166534;
    border-color: #bbf7d0;
}
.action-btn-success:hover {
    background: #bbf7d0;
    color: #166534;
}

.action-btn-view {
    background: #e0f2fe;
    color: #0369a1;
    border-color: #bae6fd;
}
.action-btn-view:hover {
    background: #bae6fd;
    color: #0369a1;
}

.action-btn-disabled {
    background: #f1f5f9;
    color: #94a3b8;
    border-color: #e2e8f0;
    cursor: not-allowed;
    opacity: 0.7;
}
.action-btn-disabled:hover {
    transform: none;
    box-shadow: none;
}

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

.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 20px;
}
.pagination-info {
    font-size: 14px;
    color: #64748b;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #e2e8f0;
    border-top-color: #4361ee;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.awaiting-banner {
    background: #fffbeb;
    border-left: 4px solid #f59e0b;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 20px;
    display: none;
    box-shadow: var(--shadow-sm);
}
.awaiting-banner .banner-title {
    color: #92400e;
    font-weight: 600;
    font-size: 15px;
    margin-bottom: 8px;
}
.awaiting-banner .banner-title i {
    margin-right: 8px;
}
.awaiting-banner .employee-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin: 8px 0 12px 0;
}
.awaiting-banner .employee-chip {
    background: white;
    border: 1px solid #fcd34d;
    border-radius: 20px;
    padding: 4px 14px;
    font-size: 13px;
    color: #78350f;
}
.awaiting-banner .btn-group {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 8px;
}

.promotion-history-chip {
    display: inline-block;
    background: #f1f5f9;
    border-radius: 12px;
    padding: 2px 8px;
    font-size: 10px;
    color: #64748b;
    margin-top: 2px;
}
.promotion-history-chip i {
    margin-right: 3px;
}

/* Salary Structure Column Styles */
.salary-structure-cell {
    min-width: 200px;
}

.salary-structure-wrapper {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.salary-status {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.salary-actions {
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
}

.salary-action-btn {
    padding: 3px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 500;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 4px;
    text-decoration: none;
    white-space: nowrap;
}

.salary-action-btn:hover {
    transform: translateY(-1px);
    text-decoration: none;
}

.salary-action-view {
    background: #e0f2fe;
    color: #0369a1;
    border-color: #bae6fd;
}

.salary-action-view:hover {
    background: #bae6fd;
    color: #0369a1;
}

.salary-action-create {
    background: #dbeafe;
    color: #1e40af;
    border-color: #bfdbfe;
}

.salary-action-create:hover {
    background: #bfdbfe;
    color: #1e40af;
}

.status-badge {
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 600;
}

.status-active {
    background: #dcfce7;
    color: #166534;
}

.status-awaiting {
    background: #fef3c7;
    color: #92400e;
}

.status-not-assigned {
    background: #f1f5f9;
    color: #64748b;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
        padding: 16px 20px;
    }
    .filter-grid {
        width: 100%;
        flex-direction: column;
    }
    .filter-group {
        min-width: 100%;
    }
 
    .table-actions {
        flex-direction: column;
        gap: 4px;
    }
    .action-btn {
        width: 100%;
        justify-content: center;
    }
    .salary-structure-cell {
        min-width: 180px;
    }
}


</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-12">
            <!-- Page Header -->
            <div class="page-header">
                <h4 class="page-title">
                    <i class="fas fa-clock"></i>
                     Employees on Probation
                    <span class="badge-count d-none" id="headerTotal">{{ $employees->total() }}</span>
                </h4>
                <div style="display: flex; gap: 10px;">
                    <button type="button" class="btn btn-light btn-sm" onclick="refreshStats()" style="border-radius: 8px; font-weight: 500;">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <a href="{{ route('employee.promotion.index') }}" class="btn btn-light btn-sm" style="border-radius: 8px; font-weight: 500;">
                        <i class="fas fa-arrow-left"></i> All Employees
                    </a>
                </div>
            </div>

            <!-- Awaiting Salary Structure Banner -->
            <div class="awaiting-banner" id="awaitingSalaryStructureBanner">
                <div class="banner-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span id="awaitingBannerMessage">Pending Salary Structure Assignment!</span>
                </div>
                <p class="text-muted small mb-2" id="awaitingBannerSubText">
                    The following employee(s) have been promoted but are awaiting salary structure assignment:
                </p>
                <div class="employee-list" id="awaitingEmployeeList"></div>
                <div class="btn-group">
                    <a href="{{ url('/institute/admin/payroll/salary-structure') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-arrow-right me-1"></i> Assign Salary Structure
                    </a>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="dismissBanner()">
                        <i class="fas fa-times me-1"></i> Dismiss
                    </button>
                </div>
            </div>


            <!-- Filter Section -->
            <div class="filter-container" id="filterContainer">
                <form id="filterForm" method="GET" action="{{ route('probation.employees') }}">
                    <div class="filter-grid">
                        <div class="filter-group">
                            <label for="department_category_id"><i class="fas fa-layer-group me-1"></i> Department Category</label>
                            <select class="filter-input" name="department_category_id" id="department_category_id" onchange="submitFilterWithLoader()">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->department_category_id }}" {{ request('department_category_id') == $category->department_category_id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="department_id"><i class="fas fa-building me-1"></i> Department</label>
                            <select class="filter-input" name="department_id" id="department_id" onchange="submitFilterWithLoader()">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                <option value="{{ $dept->department_id }}" {{ request('department_id') == $dept->department_id ? 'selected' : '' }}>
                                    {{ $dept->department }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="designation_id"><i class="fas fa-briefcase me-1"></i> Designation</label>
                            <select class="filter-input" name="designation_id" id="designation_id" onchange="submitFilterWithLoader()">
                                <option value="">All Designations</option>
                                @foreach($designations as $desig)
                                <option value="{{ $desig->designation_id }}" {{ request('designation_id') == $desig->designation_id ? 'selected' : '' }}>
                                    {{ $desig->designations }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="employee_code"><i class="fas fa-id-badge me-1"></i> Employee Code</label>
                            <input type="text" class="filter-input" name="employee_code" id="employee_code" value="{{ request('employee_code') }}" placeholder="Search by code..." oninput="debounceSubmitWithLoader(this)">
                        </div>

                        <div class="filter-group">
                            <label for="name"><i class="fas fa-user me-1"></i> Employee Name</label>
                            <input type="text" class="filter-input" name="name" id="name" value="{{ request('name') }}" placeholder="Search by name..." oninput="debounceSubmitWithLoader(this)">
                        </div>

                        <div class="filter-group">
                            <label for="probation_status"><i class="fas fa-filter me-1"></i> Probation Status</label>
                            <select class="filter-input" name="probation_status" id="probation_status" onchange="submitFilterWithLoader()">
                                <option value="">All Status</option>
                                <option value="active" {{ request('probation_status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="ending_soon" {{ request('probation_status') == 'ending_soon' ? 'selected' : '' }}>Ending Soon</option>
                                <option value="today" {{ request('probation_status') == 'today' ? 'selected' : '' }}>Ending Today</option>
                                <option value="overdue" {{ request('probation_status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="completed" {{ request('probation_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="sort_by"><i class="fas fa-sort me-1"></i> Sort By</label>
                            <select class="filter-input" name="sort_by" id="sort_by" onchange="submitFilterWithLoader()">
                                <option value="probation_end_date" {{ request('sort_by') == 'probation_end_date' ? 'selected' : '' }}>Probation End Date</option>
                                <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                                <option value="employee_code" {{ request('sort_by') == 'employee_code' ? 'selected' : '' }}>Employee Code</option>
                                <option value="department" {{ request('sort_by') == 'department' ? 'selected' : '' }}>Department</option>
                                <option value="doj" {{ request('sort_by') == 'doj' ? 'selected' : '' }}>Date of Joining</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="sort_order"><i class="fas fa-arrow-up-wide-short me-1"></i> Order</label>
                            <select class="filter-input" name="sort_order" id="sort_order" onchange="submitFilterWithLoader()">
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                            </select>
                        </div>
                    </div>

                    <div class="filter-info">
                        <i class="fas fa-info-circle me-1"></i> Filters apply automatically
                        @if(request()->hasAny(['department_category_id', 'department_id', 'designation_id', 'employee_code', 'name', 'probation_status']))
                        <a href="{{ route('probation.employees') }}" class="text-danger ms-2" style="text-decoration: none; font-weight: 500;" onclick="showLoader()">
                            <i class="fas fa-times-circle"></i> Clear Filters
                        </a>
                        @endif
                        <span class="ms-3" id="resultCount">{{ $employees->total() }} results found</span>
                    </div>
                </form>
            </div>

            <!-- Bulk Actions -->
            <div class="bulk-actions-container" id="bulkActionsContainer">
                <span class="selected-count" id="selectedCount">0 employees selected</span>
                <div class="d-flex flex-wrap" style="gap: 8px;">
                    <button type="button" class="bulk-action-btn success" id="bulkPromoteBtn" onclick="openBulkPromotionConfirmation()" disabled>
                        <i class="fas fa-arrow-up"></i> Promote Selected to Full-Time
                    </button>
                    <button type="button" class="bulk-action-btn secondary" onclick="selectAll()">
                        <i class="fas fa-check-double"></i> Select All
                    </button>
                    <button type="button" class="bulk-action-btn secondary" onclick="deselectAll()">
                        <i class="fas fa-times"></i> Deselect All
                    </button>
                </div>
            </div>

            <!-- Employees Table -->
            <div>
                <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                    <div id="tableLoader" style="display: none; text-align: center; padding: 60px 20px;">
                        <div class="spinner" style="margin: 0 auto 15px; width: 50px; height: 50px;"></div>
                        <p style="color: #475569; font-weight: 500;">Loading employees...</p>
                    </div>
                    <div id="tableContent">
                        <table class="erp-table">
                            <thead>
                                <tr>
                                    <th class="sticky-checkbox" width="40">
                                        <input type="checkbox" id="selectAllCheckbox" class="select-checkbox" onchange="toggleAllCheckboxes(this)">
                                    </th>
                                    <th class="sticky-main sortable">Employee</th>
                                    <th class="sortable">Department</th>
                                    <th class="sortable">Designation</th>
                                    <th class="sortable">Employment Type</th>
                                    <th class="sortable">DOJ</th>
                                    <th class="sortable">Probation Days</th>
                                    <th class="sortable">Probation End</th>
                                    <th class="sortable">Days Left</th>
                                    <th class="sortable">Status</th>
                                    <th class="sortable salary-structure-cell">Salary Structure</th>
                                    <th class="sortable">Promotion History</th>
                                    <th class="sortable text-center">Promote</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $employee)
                                <tr class="{{ $employee->probation_status == 'completed' ? 'completed-probation' : '' }}">
                                    <td class="sticky-checkbox">
                                        @if($employee->employment_type == 'Probation-Period')
                                        <input type="checkbox" class="employee-checkbox select-checkbox" value="{{ $employee->id }}">
                                        @else
                                        <span class="text-muted" style="font-size: 11px;">-</span>
                                        @endif
                                    </td>
                                    <td class="sticky-main">
                                        <div class="employee-name">{{ $employee->name }}</div>
                                        <div class="employee-code"><i class="fas fa-id-badge me-1"></i> {{ $employee->employee_code }}</div>
                                        <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                            <i class="fas fa-envelope me-1"></i> {{ $employee->email ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>{{ $employee->department_name ?? 'N/A' }}</td>
                                    <td><span class="designation-badge">{{ $employee->designation ?? 'N/A' }}</span></td>
                                    <td>
                                        <span class="emp-type-badge 
                                            @if($employee->employment_type == 'Full-time') emp-type-full-time
                                            @elseif($employee->employment_type == 'Part-time') emp-type-part-time
                                            @elseif($employee->employment_type == 'Contract-based') emp-type-contract
                                            @else emp-type-probation @endif">
                                            {{ $employee->employment_type ?? 'N/A' }}
                                        </span>
                                        @if($employee->employment_type == 'Probation-Period')
                                        <span class="probation-badge" style="font-size: 9px; padding: 1px 6px;">(Active)</span>
                                        @endif
                                    </td>
                                    <td>{{ $employee->doj ? \Carbon\Carbon::parse($employee->doj)->format('d-m-Y') : 'N/A' }}</td>
                                    <td>{{ $employee->probation_days ?? 'N/A' }}</td>
                                    <td>
                                        @if($employee->probation_end_date)
                                        {{ \Carbon\Carbon::parse($employee->probation_end_date)->format('d-m-Y') }}
                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($employee->probation_days_delta !== null)
                                        <span class="days-left 
                                            @if($employee->probation_days_delta > 0) positive
                                            @elseif($employee->probation_days_delta < 0) negative
                                            @else zero @endif">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ $employee->probation_days_delta > 0 ? '+' : '' }}{{ $employee->probation_days_delta }} days
                                        </span>
                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($employee->probation_status == 'active')
                                        <span class="probation-badge probation-active"><i class="fas fa-check-circle me-1"></i> Active</span>
                                        @elseif($employee->probation_status == 'ending-soon')
                                        <span class="probation-badge probation-ending-soon"><i class="fas fa-hourglass-end me-1"></i> Ending Soon</span>
                                        @elseif($employee->probation_status == 'overdue')
                                        <span class="probation-badge probation-overdue"><i class="fas fa-exclamation-triangle me-1"></i> Overdue</span>
                                        @elseif($employee->probation_status == 'today')
                                        <span class="probation-badge probation-today"><i class="fas fa-calendar-day me-1"></i> Ends Today</span>
                                        @elseif($employee->probation_status == 'completed')
                                        <span class="probation-badge probation-completed"><i class="fas fa-check-double me-1"></i> Completed</span>
                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td class="salary-structure-cell">
                                        <div class="salary-structure-wrapper">
                                            <div class="salary-status">
                                                @if($employee->has_active_salary_structure)
                                                <span class="status-badge status-active">
                                                    <i class="fas fa-check-circle me-1"></i> Active
                                                </span>
                                                @elseif($employee->has_inactive_salary_structure)
                                                <span class="status-badge status-awaiting">
                                                    <i class="fas fa-clock me-1"></i> Awaiting Assignment
                                                </span>
                                                @else
                                                <span class="status-badge status-not-assigned">
                                                    <i class="fas fa-times-circle me-1"></i> Not Assigned
                                                </span>
                                                @endif
                                            </div>

                                            <div class="salary-actions">
                                                @if($employee->has_active_salary_structure && isset($employee->salary_structure_id))
                                                    {{-- Active Salary Structure - Show View Button --}}
                                                    <a href="{{ url('/institute/admin/payroll/salary-details/' . $employee->salary_structure_id) }}"
                                                        class="salary-action-btn salary-action-view" title="View Salary Structure Details" target="_blank">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                @elseif($employee->has_inactive_salary_structure && isset($employee->salary_structure_id))
                                                    {{-- Inactive Structure - Show Assign Button --}}
                                                    <a href="{{ url('/institute/admin/payroll/salary-structure') }}"
                                                        class="salary-action-btn salary-action-create" title="Assign New Salary Structure" target="_blank">
                                                        <i class="fas fa-plus-circle"></i> Assign
                                                    </a>
                                                @else
                                                    {{-- No Salary Structure - Show Create Button --}}
                                                    <a href="{{ url('/institute/admin/payroll/salary-structure') }}"
                                                        class="salary-action-btn salary-action-create" title="Create Salary Structure" target="_blank">
                                                        <i class="fas fa-plus-circle"></i> Assign
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($employee->promotion_count > 0)
                                        <span class="promotion-history-chip">
                                            <i class="fas fa-arrow-up"></i> {{ $employee->promotion_count }} promo(s)
                                        </span>
                                        @if($employee->latest_promotion)
                                        <div style="font-size: 10px; color: #94a3b8; margin-top: 2px;">
                                            Last: {{ \Carbon\Carbon::parse($employee->latest_promotion->promotion_date)->format('d-m-Y') }}
                                        </div>
                                        @endif
                                        @else
                                        <span class="text-muted" style="font-size: 11px;">No promotions</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="table-actions" style="justify-content: center;">
                                            @if($employee->employment_type == 'Probation-Period')
                                            <button type="button" class="action-btn action-btn-success" 
                                                onclick="openPromotionConfirmation('{{ $employee->id }}', '{{ addslashes($employee->name) }}', 'Current: Probation Period - Ends {{ $employee->probation_end_date ? \Carbon\Carbon::parse($employee->probation_end_date)->format('d-m-Y') : 'N/A' }}')"
                                                title="Promote to Full-Time">
                                                <i class="fas fa-arrow-up"></i> Update
                                            </button>
                                            @else
                                            <span class="action-btn action-btn-disabled" title="Already promoted from probation">
                                                <i class="fas fa-check-circle"></i> Promoted
                                            </span>
                                            @endif
                                            <a href="{{ route('employee.promotion.history', $employee->id) }}" class="action-btn action-btn-view" title="View Promotion History">
                                                <i class="fas fa-history"></i> View
                                            </a>
                                            <a class="d-none" href="{{ url('/employee-details/' . $employee->id) }}" class="action-btn action-btn-view d-block" title="View Details">
                                                <i class="fa-regular fa-eye"></i>
                                                <span class="small">View</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="13">
                                        <div class="empty-state">
                                            <div class="empty-state-icon"><i class="fas fa-users"></i></div>
                                            <h4>No Probation Employees Found</h4>
                                            <p>No employees with probation history found.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($employees->hasPages())
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Showing {{ $employees->firstItem() ?? 0 }} to {{ $employees->lastItem() ?? 0 }} of {{ $employees->total() }} results
                    </div>
                    <div>
                        {{ $employees->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODALS -->
<!-- ============================================ -->

<!-- Promotion Confirmation Modal -->
<div class="modal fade" id="promotionConfirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #4361ee, #3a0ca3); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-user-check me-2"></i> Confirm Promotion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-user-plus" style="font-size: 48px; color: #4361ee;"></i>
                </div>
                <h5 class="text-center" id="promotionEmployeeName">Employee Name</h5>
                <p class="text-center text-muted" id="promotionEmployeeDetails">Current: Probation</p>

                <hr>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Salary Structure Decision</strong>
                    <p class="mb-0 mt-1" style="font-size: 14px;">
                        Do you want to keep the employee's current salary structure active after the promotion?
                    </p>
                </div>

                <div class="d-flex gap-3 justify-content-center mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="salaryStructureDecision" id="keepStructureYes" value="1" checked>
                        <label class="form-check-label" for="keepStructureYes">
                            <i class="fas fa-check-circle text-success me-1"></i>
                            <strong>Yes</strong> - Keep active
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="salaryStructureDecision" id="keepStructureNo" value="0">
                        <label class="form-check-label" for="keepStructureNo">
                            <i class="fas fa-times-circle text-danger me-1"></i>
                            <strong>No</strong> - Inactivate
                        </label>
                    </div>
                </div>

                <div id="deactivationWarning" class="alert alert-warning mt-3" style="display: none;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Note:</strong> The employee's current salary structure will be marked as inactive. 
                    You will need to assign a new salary structure after promotion.
                </div>

                <input type="hidden" id="promotionEmployeeId" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmPromotion()">
                    <i class="fas fa-user-check me-1"></i> Confirm Promotion
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Promotion Confirmation Modal -->
<div class="modal fade" id="bulkPromotionConfirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #4361ee, #3a0ca3); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-users me-2"></i> Bulk Promotion Confirmation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-users" style="font-size: 48px; color: #4361ee;"></i>
                </div>
                <h5 class="text-center" id="bulkPromotionCount">0 employees selected</h5>
                <div style="max-height: 150px; overflow-y: auto; background: #f8fafc; border-radius: 8px; padding: 10px; margin: 10px 0;">
                    <div id="bulkPromotionEmployeeList"></div>
                </div>

                <hr>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Salary Structure Decision</strong>
                    <p class="mb-0 mt-1" style="font-size: 14px;">
                        Do you want to keep the selected employees' current salary structures active after the promotion?
                    </p>
                </div>

                <div class="d-flex gap-3 justify-content-center mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="bulkSalaryStructureDecision" id="bulkKeepStructureYes" value="1" checked>
                        <label class="form-check-label" for="bulkKeepStructureYes">
                            <i class="fas fa-check-circle text-success me-1"></i>
                            <strong>Yes</strong> - Keep active
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="bulkSalaryStructureDecision" id="bulkKeepStructureNo" value="0">
                        <label class="form-check-label" for="bulkKeepStructureNo">
                            <i class="fas fa-times-circle text-danger me-1"></i>
                            <strong>No</strong> - Inactivate
                        </label>
                    </div>
                </div>

                <div id="bulkDeactivationWarning" class="alert alert-warning mt-3" style="display: none;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Note:</strong> The selected employees' current salary structures will be marked as inactive. 
                    You will need to assign new salary structures for the promoted positions.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmBulkPromotion()">
                    <i class="fas fa-user-check me-1"></i> Confirm Promotion
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Page Loader -->
<div id="pageLoader" style="display: none; position: fixed; inset: 0; background: rgba(255,255,255,0.7); z-index: 9999; align-items: center; justify-content: center;">
    <div class="spinner"></div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ============================================
// DOM READY
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    loadStatistics();
    updateSelectedCount();
    checkAwaitingSalaryStructures();

    const total = document.querySelectorAll('.employee-checkbox').length;
    if (total > 0) {
        document.getElementById('resultCount').textContent = total + ' results found';
    }
});

// ============================================
// STATISTICS
// ============================================
function loadStatistics() {
    fetch('{{ route("probation.statistics") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('statTotal').textContent = data.statistics.total;
                document.getElementById('statCompleted').textContent = data.statistics.completed;
                document.getElementById('statEndingSoon').textContent = data.statistics.ending_soon;
                document.getElementById('statOverdue').textContent = data.statistics.overdue;
                document.getElementById('statToday').textContent = data.statistics.ending_today;
                document.getElementById('headerTotal').textContent = data.statistics.total + data.statistics.completed;
            }
        })
        .catch(error => console.error('Error loading statistics:', error));
}

function refreshStats() {
    const btn = event?.target?.closest('button') || document.querySelector('[onclick="refreshStats()"]');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Loading...';
    }

    loadStatistics();

    setTimeout(() => {
        if (btn) {
            btn.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh';
            btn.disabled = false;
        }
    }, 1000);
}

// ============================================
// LOADER FUNCTIONS
// ============================================
function showLoader() {
    document.getElementById('pageLoader').style.display = 'flex';
}

function hideLoader() {
    document.getElementById('pageLoader').style.display = 'none';
}

function showTableLoader() {
    document.getElementById('tableLoader').style.display = 'block';
    document.getElementById('tableContent').style.display = 'none';
}

function hideTableLoader() {
    document.getElementById('tableLoader').style.display = 'none';
    document.getElementById('tableContent').style.display = 'block';
}

function submitFilterWithLoader() {
    showTableLoader();
    document.getElementById('filterForm').submit();
}

let debounceTimer;

function debounceSubmitWithLoader(element) {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        showTableLoader();
        document.getElementById('filterForm').submit();
    }, 500);
}

// ============================================
// SELECTION MANAGEMENT
// ============================================
function toggleAllCheckboxes(masterCheckbox) {
    const checkboxes = document.querySelectorAll('.employee-checkbox');
    checkboxes.forEach(cb => cb.checked = masterCheckbox.checked);
    updateSelectedCount();
}

function selectAll() {
    document.querySelectorAll('.employee-checkbox').forEach(cb => cb.checked = true);
    document.getElementById('selectAllCheckbox').checked = true;
    updateSelectedCount();
}

function deselectAll() {
    document.querySelectorAll('.employee-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAllCheckbox').checked = false;
    updateSelectedCount();
}

function updateSelectedCount() {
    const selected = document.querySelectorAll('.employee-checkbox:checked').length;
    const total = document.querySelectorAll('.employee-checkbox').length;

    document.getElementById('selectedCount').textContent = selected + ' employee(s) selected';
    document.getElementById('bulkPromoteBtn').disabled = selected === 0;

    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    if (selected === 0) {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
    } else if (selected === total && total > 0) {
        selectAllCheckbox.checked = true;
        selectAllCheckbox.indeterminate = false;
    } else {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = true;
    }
}

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('employee-checkbox')) {
        updateSelectedCount();
    }
});

// ============================================
// SINGLE PROMOTION
// ============================================
function openPromotionConfirmation(employeeId, employeeName, employeeDetails) {
    document.getElementById('promotionEmployeeId').value = employeeId;
    document.getElementById('promotionEmployeeName').textContent = employeeName;
    document.getElementById('promotionEmployeeDetails').textContent = employeeDetails || 'Current: Probation Period';

    document.getElementById('keepStructureYes').checked = true;
    document.getElementById('keepStructureNo').checked = false;
    document.getElementById('deactivationWarning').style.display = 'none';

    const modal = new bootstrap.Modal(document.getElementById('promotionConfirmationModal'));
    modal.show();
}

// ============================================
// RADIO BUTTON TOGGLE FOR WARNINGS
// ============================================
document.addEventListener('change', function(e) {
    if (e.target.name === 'salaryStructureDecision') {
        const warning = document.getElementById('deactivationWarning');
        warning.style.display = e.target.value === '0' ? 'block' : 'none';
    }
    if (e.target.name === 'bulkSalaryStructureDecision') {
        const warning = document.getElementById('bulkDeactivationWarning');
        warning.style.display = e.target.value === '0' ? 'block' : 'none';
    }
});

// ============================================
// CONFIRM SINGLE PROMOTION
// ============================================
function confirmPromotion() {
    const employeeId = document.getElementById('promotionEmployeeId').value;
    const keepSalaryStructure = document.querySelector('input[name="salaryStructureDecision"]:checked').value === '1';

    const modal = bootstrap.Modal.getInstance(document.getElementById('promotionConfirmationModal'));
    modal.hide();

    Swal.fire({
        title: 'Processing...',
        text: 'Please wait while we process the promotion.',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => { Swal.showLoading(); }
    });

    const url = `/probation-employees/${employeeId}/promote`;
    const data = { keep_salary_structure: keepSalaryStructure };

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(response => {
        Swal.close();

        if (response.success) {
            let message = response.message;
            if (!keepSalaryStructure) {
                message += '<br><br><div class="alert alert-warning mt-2">' +
                    '<i class="fas fa-exclamation-triangle me-2"></i>' +
                    'The employee\'s salary structure has been inactivated. ' +
                    '<a href="' + response.employee.salary_structure_url + '" class="btn btn-primary btn-sm ms-2">' +
                    '<i class="fas fa-arrow-right me-1"></i> Assign New Salary Structure</a></div>';
            }

            Swal.fire({
                title: 'Promoted!',
                html: message,
                icon: 'success',
                confirmButtonColor: '#4361ee',
                timer: keepSalaryStructure ? 3000 : 6000,
                timerProgressBar: true
            });

            // Reload page to reflect changes
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            Swal.fire({ title: 'Error!', text: response.message || 'Failed to promote employee', icon: 'error', confirmButtonColor: '#dc2626' });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.close();
        Swal.fire({ title: 'Error!', text: 'Failed to promote employee. Please try again.', icon: 'error', confirmButtonColor: '#dc2626' });
    });
}

// ============================================
// BULK PROMOTION
// ============================================
function openBulkPromotionConfirmation() {
    const selected = document.querySelectorAll('.employee-checkbox:checked');
    if (selected.length === 0) {
        Swal.fire({ title: 'No Selection', text: 'Please select at least one employee to promote.', icon: 'warning', confirmButtonColor: '#d97706' });
        return;
    }

    const employeeNames = Array.from(selected).map(cb => {
        const row = cb.closest('tr');
        return row.querySelector('.employee-name')?.textContent || 'Unknown';
    });

    document.getElementById('bulkPromotionCount').textContent = `${selected.length} employee(s) selected`;
    document.getElementById('bulkPromotionEmployeeList').innerHTML =
        employeeNames.map(name => `<div style="padding: 4px 8px; border-bottom: 1px solid #e2e8f0;">• ${name}</div>`).join('');

    document.getElementById('bulkKeepStructureYes').checked = true;
    document.getElementById('bulkKeepStructureNo').checked = false;
    document.getElementById('bulkDeactivationWarning').style.display = 'none';

    const modal = new bootstrap.Modal(document.getElementById('bulkPromotionConfirmationModal'));
    modal.show();
}

function confirmBulkPromotion() {
    const selected = document.querySelectorAll('.employee-checkbox:checked');
    const employeeIds = Array.from(selected).map(cb => cb.value);
    const keepSalaryStructure = document.querySelector('input[name="bulkSalaryStructureDecision"]:checked').value === '1';

    if (employeeIds.length === 0) {
        Swal.fire({ title: 'Error!', text: 'No employees selected.', icon: 'error', confirmButtonColor: '#dc2626' });
        return;
    }

    const modal = bootstrap.Modal.getInstance(document.getElementById('bulkPromotionConfirmationModal'));
    modal.hide();

    Swal.fire({
        title: 'Processing...',
        text: 'Please wait while we process the promotions.',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => { Swal.showLoading(); }
    });

    const url = '{{ route("probation.bulk.promote") }}';
    const data = { employee_ids: employeeIds, keep_salary_structure: keepSalaryStructure };

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(response => {
        Swal.close();

        if (response.success) {
            let message = response.message;
            if (!keepSalaryStructure) {
                message += '<br><br><div class="alert alert-warning mt-2">' +
                    '<i class="fas fa-exclamation-triangle me-2"></i>' +
                    'The employees\' salary structures have been inactivated. ' +
                    '<a href="' + response.salary_structure_url + '" class="btn btn-primary btn-sm ms-2">' +
                    '<i class="fas fa-arrow-right me-1"></i> Assign New Salary Structures</a></div>';
            }

            Swal.fire({
                title: 'Promoted!',
                html: message,
                icon: 'success',
                confirmButtonColor: '#4361ee',
                timer: keepSalaryStructure ? 3000 : 6000,
                timerProgressBar: true
            });

            // Reload page to reflect changes
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            Swal.fire({ title: 'Error!', text: response.message || 'Failed to promote employees', icon: 'error', confirmButtonColor: '#dc2626' });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.close();
        Swal.fire({ title: 'Error!', text: 'Failed to promote employees. Please try again.', icon: 'error', confirmButtonColor: '#dc2626' });
    });
}

// ============================================
// AWAITING SALARY STRUCTURE CHECK
// ============================================
function checkAwaitingSalaryStructures() {
    fetch('{{ route("institute.probation.check-salary-structure") }}', {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.awaiting_count > 0) {
            showAwaitingBanner(data.awaiting_employees);
        } else {
            document.getElementById('awaitingSalaryStructureBanner').style.display = 'none';
        }
    })
    .catch(error => console.error('Error checking salary structures:', error));
}

function showAwaitingBanner(employees) {
    const banner = document.getElementById('awaitingSalaryStructureBanner');
    const listContainer = document.getElementById('awaitingEmployeeList');
    listContainer.innerHTML = '';

    employees.forEach(emp => {
        const chip = document.createElement('span');
        chip.className = 'employee-chip';
        chip.innerHTML = `<strong>${emp.name}</strong> (${emp.employee_code}) - Promoted: ${emp.promotion_date}`;
        listContainer.appendChild(chip);
    });

    document.getElementById('awaitingBannerMessage').textContent =
        `${employees.length} Employee(s) Pending Salary Structure Assignment!`;
    document.getElementById('awaitingBannerSubText').textContent =
        `The following ${employees.length} employee(s) have been promoted but are awaiting salary structure assignment:`;

    banner.style.display = 'block';
}

function dismissBanner() {
    document.getElementById('awaitingSalaryStructureBanner').style.display = 'none';
    sessionStorage.setItem('salaryStructureBannerDismissed', 'true');
}

// ============================================
// AUTO-SUBMIT ON SELECT CHANGE
// ============================================
document.addEventListener('change', function(e) {
    if (e.target.closest('.filter-container') &&
        (e.target.tagName === 'SELECT' || e.target.type === 'checkbox')) {
        if (e.target.id !== 'selectAllCheckbox' && !e.target.classList.contains('employee-checkbox')) {
            submitFilterWithLoader();
        }
    }
});
</script>
@endsection