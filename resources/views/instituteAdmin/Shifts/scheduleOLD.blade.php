@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Shift Schedule Overview</title>
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
    /* ERP Table Styles */
    .erp-table {
        width: 100%;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
    }
    
    .erp-table thead {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .erp-table th {
        padding: 12px 16px;
        font-weight: 600;
        color: #475569;
        text-align: left;
        font-size: 14px;
        border-bottom: 1px solid #e2e8f0;
        cursor: pointer;
        user-select: none;
        transition: background-color 0.2s;
        position: relative;
    }
    
    .erp-table th:hover {
        background-color: #f1f5f9;
    }
    
    .erp-table th.sortable {
        padding-right: 30px;
    }
    
    .sort-icons {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .sort-icon {
        color: #cbd5e1;
        font-size: 12px;
        line-height: 1;
    }
    
    .sort-icon.active {
        color: #3b82f6;
    }
    
    .erp-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 14px;
        vertical-align: middle;
    }
    
    .erp-table tbody tr {
        transition: background-color 0.2s, transform 0.2s;
    }
    
    .erp-table tbody tr:hover {
        background-color: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .erp-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Status Badges */
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
        transition: transform 0.2s;
    }
    
    .status-badge:hover {
        transform: scale(1.05);
    }
    
    .status-active {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    
    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    
    .status-pending {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    
    .status-unassigned {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }
    
    /* Assignment Type Badges */
    .assignment-badge {
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 500;
        display: inline-block;
    }
    
    .assignment-individual {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    
    .assignment-department {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }
    
    /* Bulk Actions */
    .bulk-actions-container {
        margin-bottom: 2px;
        display: none;
        align-items: center;
        flex-wrap: wrap;
        animation: slideDown 0.3s ease;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .bulk-actions-container.active {
        display: flex;
    }
    
    .selected-count {
        font-weight: 500;
        color: #475569;
        margin-right: auto;
        font-size: 14px;
    }
    
    .bulk-action-btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        color: #475569;
        transition: all 0.2s;
        font-size: 14px;
        display: flex;
        align-items: center;
        border: 1px solid transparent;
        margin-right: 5px;
    }
    
    .bulk-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .bulk-action-btn:active {
        transform: translateY(0);
    }
    
    .bulk-action-btn.assign {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    
    .bulk-action-btn.assign:hover {
        background: #bbf7d0;
    }
    
    .bulk-action-btn.reassign {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    
    .bulk-action-btn.reassign:hover {
        background: #fde68a;
    }
    
    .bulk-action-btn.remove {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    
    .bulk-action-btn.remove:hover {
        background: #fecaca;
    }
    
    .bulk-action-btn.clear {
        background: transparent;
        color: #64748b;
        border: 1px solid #cbd5e1;
    }
    
    .bulk-action-btn.clear:hover {
        background: #f1f5f9;
    }

    .bulk-action-btn i {
        margin-right: 5px;
    }

    /* Checkbox styling */
    .select-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
        border-radius: 4px;
        border: 2px solid #cbd5e1;
        transition: all 0.2s;
    }
    
    .select-checkbox:hover {
        border-color: #3b82f6;
    }
    
    .select-checkbox:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
    }
    
    /* Filter container */
    .filter-container {
        background: #fff;
        border-radius: 8px;
        padding: 6px 14px;
        margin-bottom: 20px;
        border: 1px solid #e2e8f0;
        animation: slideUp 0.3s ease;
    }

    .filter-form {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .filter-group {
        position: relative;
        flex: 1;
        min-width: 200px;
    }
    
    .filter-group .bi {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        z-index: 1;
    }
    
    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 8px 10px 8px 35px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 14px;
        background: #fff;
        transition: all 0.2s;
    }
    
    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        transform: translateY(-1px);
    }
    
    .filter-group input:hover,
    .filter-group select:hover {
        border-color: #cbd5e1;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .filter-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .btn-filter {
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        white-space: nowrap;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .btn-filter:active {
        transform: translateY(0);
    }
    
    .btn-filter-primary {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    
    .btn-filter-primary:hover {
        background: #2563eb;
    }
    
    .btn-filter-secondary {
        background: #f1f5f9;
        color: #475569;
        border-color: #e2e8f0;
    }
    
    .btn-filter-secondary:hover {
        background: #e2e8f0;
        text-decoration: none;
        color: #475569;
    }
    
    /* Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e2e8f0;
        animation: fadeIn 0.5s ease;
    }
    
    .page-title {
        font-size: 24px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }
    
    /* Statistics Cards */
    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        height: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    
    .stat-card .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 12px;
    }
    
    .stat-card .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }
    
    .stat-card .stat-label {
        font-size: 14px;
        color: #64748b;
        font-weight: 500;
    }
    
    /* Employee Avatar */
    .employee-avatar {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 16px;
    }
    
    /* Priority Badges */
    .priority-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 500;
    }
    
    .priority-high {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .priority-medium {
        background: #fef3c7;
        color: #92400e;
    }
    
    .priority-low {
        background: #dcfce7;
        color: #166534;
    }
    
    /* Time Display */
    .time-display {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .time-start {
        color: #10b981;
        font-weight: 500;
        font-size: 13px;
    }
    
    .time-end {
        color: #ef4444;
        font-weight: 500;
        font-size: 13px;
    }
    
    .time-separator {
        color: #94a3b8;
        font-size: 11px;
        text-align: center;
    }
    
    /* Loading Animation */
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #3b82f6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #64748b;
    }
    
    .empty-state-icon {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 16px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch;
            gap: 16px;
        }
        
        .bulk-actions-container {
            flex-direction: column;
            align-items: stretch;
        }
        
        .bulk-actions-container .d-flex {
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .filter-form {
            flex-direction: column;
        }
        
        .filter-group {
            min-width: 100%;
        }
        
        .filter-actions {
            width: 100%;
            justify-content: stretch;
        }
        
        .btn-filter {
            flex: 1;
            justify-content: center;
        }
    }

    /* Alert styling */
    .alert-dismissible {
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    /* Assign button */
    .assign-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #3b82f6;
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 100;
    }
    
    .assign-btn:hover {
        background: #2563eb;
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    }
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">📅 Shift Schedule Overview</h1>
        <div class="d-flex gap-2">
           <a href="{{ route('shifts.assignform') }}" class="btn-filter btn-filter-primary" style="font-size: 14px; padding: 6px 12px;">
                <i class="bi bi-person-plus me-1"></i>Assign Shifts
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #dbeafe;">
                    <i class="bi bi-people text-primary"></i>
                </div>
                <div class="stat-value" id="totalEmployees">{{ $employees->count() }}</div>
                <div class="stat-label">Total Employees</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #dcfce7;">
                    <i class="bi bi-person-check text-success"></i>
                </div>
                <div class="stat-value" id="individualShiftsCount">0</div>
                <div class="stat-label">Individual Shifts</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e0f2fe;">
                    <i class="bi bi-building text-info"></i>
                </div>
                <div class="stat-value" id="departmentShiftsCount">0</div>
                <div class="stat-label">Department Shifts</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #fef3c7;">
                    <i class="bi bi-clock text-warning"></i>
                </div>
                <div class="stat-value" id="noShiftsCount">0</div>
                <div class="stat-label">No Shifts Assigned</div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Filters -->
    <div class="filter-container">
        <form class="filter-form" id="filterForm">
            <div class="filter-group">
                <i class="bi bi-search"></i>
                <input type="text" id="searchName" class="filter-input" placeholder="Search by name or ID...">
            </div>

            <div class="filter-group">
                <i class="bi bi-diagram-3"></i>
                <select id="filterCategory" class="filter-input">
                    <option value="">All Categories</option>
                    @php
                        $categories = $employees->pluck('department_category_name')->filter()->unique()->sort();
                    @endphp
                    @foreach($categories as $category)
                        @if($category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <i class="bi bi-funnel"></i>
                <select id="filterShiftSource" class="filter-input">
                    <option value="">All Sources</option>
                    <option value="individual">Individual Shifts</option>
                    <option value="department">Department Shifts</option>
                    <option value="none">No Shift</option>
                </select>
            </div>

            <div class="filter-group">
                <i class="bi bi-toggle-on"></i>
                <select id="filterShiftStatus" class="filter-input">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="button" class="btn-filter btn-filter-primary" onclick="applyFilters()">
                    <i class="bi bi-funnel"></i>
                    Apply Filters
                </button>
                <button class="btn-filter btn-filter-secondary" onclick="resetFilters()">
                    <i class="bi bi-arrow-clockwise"></i>
                    Reset Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Bulk Actions Container -->
    <div class="bulk-actions-container d-none" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 employees selected</div>
        <div class="d-flex flex-wrap">
            <button class="bulk-action-btn assign" onclick="bulkAction('assign')">
                <i class="bi bi-person-plus"></i>
                Assign Shift
            </button>
            <button class="bulk-action-btn reassign" onclick="bulkAction('reassign')">
                <i class="bi bi-arrow-repeat"></i>
                Reassign Shift
            </button>
            <button class="bulk-action-btn remove" onclick="bulkAction('remove')">
                <i class="bi bi-trash"></i>
                Remove Shift
            </button>
            <button class="bulk-action-btn clear mr-0" onclick="clearSelection()">
                <i class="bi bi-x-lg"></i>
                Clear
            </button>
        </div>
    </div>

    <!-- Main Table -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th class="d-none" width="40">
                                <input type="checkbox" id="selectAll" class="select-checkbox">
                            </th>
                            <th class="sortable" onclick="sortTable('name')">
                                Employee
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill"></i>
                                    <i class="sort-icon bi bi-caret-down-fill"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('department_name')">
                                Department
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill"></i>
                                    <i class="sort-icon bi bi-caret-down-fill"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('shift_name')">
                                Shift
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill"></i>
                                    <i class="sort-icon bi bi-caret-down-fill"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('start_time')">
                                Timing
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill"></i>
                                    <i class="sort-icon bi bi-caret-down-fill"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('working_hours')">
                                Duration
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill"></i>
                                    <i class="sort-icon bi bi-caret-down-fill"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('break_minutes')">
                                Break
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill"></i>
                                    <i class="sort-icon bi bi-caret-down-fill"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('grace_minutes')">
                                Grace
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill"></i>
                                    <i class="sort-icon bi bi-caret-down-fill"></i>
                                </div>
                            </th>
                            <th>Weekly Off</th>
                            <th class="sortable" onclick="sortTable('status')">
                                Status
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill"></i>
                                    <i class="sort-icon bi bi-caret-down-fill"></i>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="employeesTableBody">
                        @forelse($employees as $emp)
                            @php
                                // Determine shift information
                                $hasIndividualShift = isset($emp->employee_shift);
                                $hasDepartmentShift = isset($emp->department_shift);
                                $hasShift = $hasIndividualShift || $hasDepartmentShift;
                                
                                // Get the appropriate shift data
                                if ($hasIndividualShift) {
                                    $shift = $emp->employee_shift;
                                    $shiftSource = 'individual';
                                    $assignmentType = 'Individual';
                                    $assignmentClass = 'assignment-individual';
                                } elseif ($hasDepartmentShift) {
                                    $shift = $emp->department_shift;
                                    $shiftSource = 'department';
                                    $assignmentType = 'Department';
                                    $assignmentClass = 'assignment-department';
                                } else {
                                    $shift = null;
                                    $shiftSource = 'none';
                                    $assignmentType = null;
                                    $assignmentClass = '';
                                }
                                
                                // Get shift status
                                $shiftStatus = $emp->status ?? null;
                                $isActive = $shiftStatus == 'active';
                                
                                // Calculate duration if shift exists
                                $duration = 'N/A';
                                if ($shift && $shift->start_time && $shift->end_time) {
                                    $start = \Carbon\Carbon::parse($shift->start_time);
                                    $end = \Carbon\Carbon::parse($shift->end_time);
                                    $durationHours = $start->diffInHours($end);
                                    $durationMinutes = $start->diffInMinutes($end) % 60;
                                    $duration = $durationHours . 'h';
                                    if ($durationMinutes > 0) {
                                        $duration .= ' ' . $durationMinutes . 'm';
                                    }
                                }
                                
                                // Prepare weekly off days
                                $weeklyOffs = [];
                                if ($shift && $shift->weekly_off_days) {
                                    $weeklyOffs = is_array($shift->weekly_off_days) 
                                        ? $shift->weekly_off_days 
                                        : json_decode($shift->weekly_off_days, true);
                                }
                                
                                // Get initials for avatar
                                $initials = '';
                                if ($emp->name) {
                                    $nameParts = explode(' ', $emp->name);
                                    if (count($nameParts) >= 2) {
                                        $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                                    } else {
                                        $initials = strtoupper(substr($emp->name, 0, 2));
                                    }
                                }
                            @endphp
                            <tr class="employee-row" 
                                data-name="{{ strtolower($emp->name . ' ' . $emp->employee_code) }}"
                                data-department="{{ strtolower($emp->department_name ?? '') }}"
                                data-category="{{ strtolower($emp->department_category_name ?? '') }}"
                                data-source="{{ $shiftSource }}"
                                data-status="{{ $isActive ? 'active' : ($hasShift ? 'inactive' : 'none') }}"
                                data-employee-id="{{ $emp->id }}">
                                <td class="d-none">
                                    <input type="checkbox" class="employee-checkbox select-checkbox" value="{{ $emp->id }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div>
                                            <div class="fw-bold">{{ $emp->name }}</div>
                                            <div class="small text-primary">{{ $emp->designation }}</div>
                                            <!-- <div class="small text-muted">{{ $emp->employee_code }}</div> -->
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($emp->department_name)
                                        <div>
                                            <div class="d-inline-block">
                                                {{ $emp->department_name }}
                                            </div>
                                            @if($emp->department_category_name)
                                                <div class="small text-primary">
                                                    {{ $emp->department_category_name }}
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">No Department</span>
                                    @endif
                                </td>
                                <td>
                                    @if($hasShift)
                                        <div>
                                            <div class="fw-bold">{{ $shift->shift_name }}</div>
                                            @if($assignmentType)
                                                <span class="assignment-badge {{ $assignmentClass }}">
                                                    {{ $assignmentType }}
                                                </span>
                                            @endif
                                            @if($shift->priority)
                                                <div class="mt-1">
                                                    <span class="priority-badge priority-{{ $shift->priority }}">
                                                        {{ ucfirst($shift->priority) }} Priority
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">No Shift Assigned</span>
                                    @endif
                                </td>
                                <td>
                                    @if($hasShift && $shift->start_time && $shift->end_time)
                                        <div class="time-display">
                                            <span class="time-start">
                                                {{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }}
                                            </span>
                                            <span class="time-separator">→</span>
                                            <span class="time-end">
                                                {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($hasShift)
                                        <span class="">{{ $duration }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($hasShift)
                                        <span class="">
                                            {{ $shift->break_minutes ?? 0 }} min
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($hasShift)
                                        <span class="">
                                            {{ $shift->grace_minutes ?? 0 }} min
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($hasShift && $weeklyOffs && count($weeklyOffs) > 0)
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach(array_slice($weeklyOffs, 0, 2) as $day)
                                                <span class="">
                                                    {{ substr($day, 0, 3) }}
                                                </span>
                                            @endforeach
                                            @if(count($weeklyOffs) > 2)
                                                <span class="badge bg-gray-100 text-gray-500 border border-gray-200 text-xs">
                                                    +{{ count($weeklyOffs) - 2 }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>
                                <td>
                                    @if($hasShift)
                                        <span class="status-badge {{ $isActive ? 'status-active' : 'status-inactive' }}">
                                            {{ $isActive ? 'Active' : 'Inactive' }}
                                        </span>
                                    @else
                                        <span class="status-badge status-unassigned">
                                            Not Assigned
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="bi bi-person-x"></i>
                                        </div>
                                        <h4>No Employees Found</h4>
                                        <p>No employees have been assigned shifts yet.</p>
                                        <a href="{{ route('shifts.assignform') }}" class="btn-filter btn-filter-primary mt-2">
                                            <i class="bi bi-person-plus me-1"></i>Assign Shifts
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Floating Assign Button -->
<a href="{{ route('shifts.assignform') }}" class="assign-btn" title="Assign Shifts">
    <i class="bi bi-plus-lg"></i>
</a>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculate statistics on load
    calculateStatistics();
    
    // Setup filter event listeners
    setupFilters();
    
    // Setup bulk selection
    setupBulkSelection();
    
    // Setup sorting
    setupSorting();
    
    // Initialize filters from URL params if present
    initializeFromUrl();
});

// Bulk Selection Functions
function setupBulkSelection() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
    const bulkActionsContainer = document.getElementById('bulkActionsContainer');
    const selectedCountElement = document.getElementById('selectedCount');

    // Select All functionality
    selectAllCheckbox.addEventListener('change', function() {
        employeeCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectionUI();
    });

    // Individual checkbox change
    employeeCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionUI);
    });

    function updateSelectionUI() {
        const selectedCount = document.querySelectorAll('.employee-checkbox:checked').length;
        
        if (selectedCount > 0) {
            bulkActionsContainer.classList.add('active');
            selectedCountElement.textContent = selectedCount + ' employee(s) selected';
            
            // Update select all checkbox state
            selectAllCheckbox.checked = selectedCount === employeeCheckboxes.length;
            selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < employeeCheckboxes.length;
        } else {
            bulkActionsContainer.classList.remove('active');
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
    }
}

function clearSelection() {
    document.querySelectorAll('.employee-checkbox:checked').forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    document.getElementById('bulkActionsContainer').classList.remove('active');
}

function bulkAction(action) {
    const selectedEmployees = Array.from(document.querySelectorAll('.employee-checkbox:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedEmployees.length === 0) {
        showAlert('Please select at least one employee.', 'warning');
        return;
    }
    
    switch(action) {
        case 'assign':
            window.location.href = `{{ route('shifts.assignform') }}?employee_ids=${selectedEmployees.join(',')}`;
            break;
            
        case 'reassign':
            if (confirm(`Reassign shift for ${selectedEmployees.length} employee(s)?`)) {
                // This would typically redirect to a reassign form
                window.location.href = `{{ route('shifts.assignform') }}?employee_ids=${selectedEmployees.join(',')}&action=reassign`;
            }
            break;
            
        case 'remove':
            if (confirm(`Remove shift assignment from ${selectedEmployees.length} employee(s)? This will unassign their current shifts.`)) {
                removeShiftAssignments(selectedEmployees);
            }
            break;
            
        default:
            showAlert(`${action} action triggered for ${selectedEmployees.length} employees`, 'info');
    }
}

function removeShiftAssignments(employeeIds) {
    const btn = event?.target?.closest('button');
    if (btn) {
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<span class="loading-spinner"></span> Removing...';
        btn.disabled = true;
    }

    // This is a placeholder - you would need to implement the actual API endpoint
    console.log('Removing shifts for employees:', employeeIds);
    
    // Simulate API call
    setTimeout(() => {
        // Update UI to show removed shifts
        employeeIds.forEach(id => {
            const row = document.querySelector(`.employee-row[data-employee-id="${id}"]`);
            if (row) {
                // Update shift info to show "No Shift Assigned"
                const shiftCell = row.cells[3];
                shiftCell.innerHTML = `<span class="text-muted">No Shift Assigned</span>`;
                
                // Update timing cell
                const timingCell = row.cells[4];
                timingCell.innerHTML = `<span class="text-muted">-</span>`;
                
                // Update duration cell
                const durationCell = row.cells[5];
                durationCell.innerHTML = `<span class="text-muted">-</span>`;
                
                // Update break cell
                const breakCell = row.cells[6];
                breakCell.innerHTML = `<span class="text-muted">-</span>`;
                
                // Update grace cell
                const graceCell = row.cells[7];
                graceCell.innerHTML = `<span class="text-muted">-</span>`;
                
                // Update weekly off cell
                const weeklyOffCell = row.cells[8];
                weeklyOffCell.innerHTML = `<span class="text-muted">None</span>`;
                
                // Update status cell
                const statusCell = row.cells[9];
                statusCell.innerHTML = `<span class="status-badge status-unassigned">Not Assigned</span>`;
                
                // Update data attributes
                row.dataset.source = 'none';
                row.dataset.status = 'none';
            }
        });
        
        // Recalculate statistics
        calculateStatistics();
        clearSelection();
        showAlert(`${employeeIds.length} shift assignment(s) removed successfully!`, 'success');
        
        if (btn) {
            btn.innerHTML = originalHTML;
            btn.disabled = false;
        }
    }, 1000);
}

// Sorting Functions
function setupSorting() {
    let currentSort = {
        column: null,
        order: 'asc' // 'asc' or 'desc'
    };

    document.querySelectorAll('.sortable').forEach(header => {
        header.addEventListener('click', function() {
            const column = this.getAttribute('onclick').match(/sortTable\('(.+)'\)/)[1];
            
            if (currentSort.column === column) {
                // Toggle order if same column
                currentSort.order = currentSort.order === 'asc' ? 'desc' : 'asc';
            } else {
                // New column, start with asc
                currentSort.column = column;
                currentSort.order = 'asc';
            }
            
            sortTable(column);
        });
    });
}

function sortTable(column) {
    const rows = Array.from(document.querySelectorAll('.employee-row:not([style*="display: none"])'));
    const tbody = document.getElementById('employeesTableBody');
    
    // Remove existing empty state row if present
    const existingEmptyRow = document.querySelector('.empty-state-row');
    if (existingEmptyRow) {
        tbody.removeChild(existingEmptyRow);
    }
    
    // Get current sort state from data attribute or default
    let currentSortColumn = column;
    let currentSortOrder = 'asc';
    
    // Check if this column is already sorted
    const sortableHeader = document.querySelector(`th[onclick*="sortTable('${column}')"]`);
    if (sortableHeader) {
        const sortIcons = sortableHeader.querySelectorAll('.sort-icon');
        
        // Reset all sort icons
        document.querySelectorAll('.sort-icon').forEach(icon => {
            icon.classList.remove('active');
        });
        
        // Set active sort icon
        if (currentSortOrder === 'asc') {
            sortIcons[0].classList.add('active');
        } else {
            sortIcons[1].classList.add('active');
        }
    }
    
    // Sort rows based on column
    rows.sort((a, b) => {
        let aValue, bValue;
        
        switch(column) {
            case 'name':
                aValue = a.querySelector('td:nth-child(2) .fw-bold').textContent.toLowerCase();
                bValue = b.querySelector('td:nth-child(2) .fw-bold').textContent.toLowerCase();
                break;
                
            case 'department_name':
                aValue = a.querySelector('td:nth-child(3) > div > div').textContent.toLowerCase();
                bValue = b.querySelector('td:nth-child(3) > div > div').textContent.toLowerCase();
                break;
                
            case 'shift_name':
                aValue = a.querySelector('td:nth-child(4) .fw-bold').textContent.toLowerCase();
                bValue = b.querySelector('td:nth-child(4) .fw-bold').textContent.toLowerCase();
                // Handle "No Shift Assigned" as empty string for proper sorting
                if (aValue === 'no shift assigned') aValue = '';
                if (bValue === 'no shift assigned') bValue = '';
                break;
                
            case 'start_time':
                aValue = a.querySelector('td:nth-child(5) .time-start')?.textContent || '';
                bValue = b.querySelector('td:nth-child(5) .time-start')?.textContent || '';
                break;
                
            case 'working_hours':
                aValue = parseFloat(a.querySelector('td:nth-child(6)').textContent.replace('h', '').replace('m', '').trim()) || 0;
                bValue = parseFloat(b.querySelector('td:nth-child(6)').textContent.replace('h', '').replace('m', '').trim()) || 0;
                break;
                
            case 'break_minutes':
                aValue = parseInt(a.querySelector('td:nth-child(7)').textContent) || 0;
                bValue = parseInt(b.querySelector('td:nth-child(7)').textContent) || 0;
                break;
                
            case 'grace_minutes':
                aValue = parseInt(a.querySelector('td:nth-child(8)').textContent) || 0;
                bValue = parseInt(b.querySelector('td:nth-child(8)').textContent) || 0;
                break;
                
            case 'status':
                aValue = a.querySelector('td:nth-child(10) .status-badge').textContent.toLowerCase();
                bValue = b.querySelector('td:nth-child(10) .status-badge').textContent.toLowerCase();
                break;
                
            default:
                return 0;
        }
        
        // Handle string comparison
        if (typeof aValue === 'string' && typeof bValue === 'string') {
            if (currentSortOrder === 'asc') {
                return aValue.localeCompare(bValue);
            } else {
                return bValue.localeCompare(aValue);
            }
        }
        
        // Handle number comparison
        if (currentSortOrder === 'asc') {
            return aValue - bValue;
        } else {
            return bValue - aValue;
        }
    });
    
    // Reorder rows in table
    rows.forEach(row => {
        tbody.appendChild(row);
    });
    
    // Show empty state if no rows visible
    const visibleRows = document.querySelectorAll('.employee-row:not([style*="display: none"])');
    if (visibleRows.length === 0) {
        showEmptyState(true);
    }
}

// Existing Functions (unchanged)
function calculateStatistics() {
    let individualCount = 0;
    let departmentCount = 0;
    let noShiftCount = 0;
    
    const rows = document.querySelectorAll('.employee-row');
    rows.forEach(row => {
        const source = row.dataset.source || 'none';
        if (source === 'individual') {
            individualCount++;
        } else if (source === 'department') {
            departmentCount++;
        } else {
            noShiftCount++;
        }
    });
    
    document.getElementById('individualShiftsCount').textContent = individualCount;
    document.getElementById('departmentShiftsCount').textContent = departmentCount;
    document.getElementById('noShiftsCount').textContent = noShiftCount;
}

function setupFilters() {
    // Add event listeners to all filter inputs
    const filterInputs = document.querySelectorAll('#searchName, #filterCategory, #filterShiftSource, #filterShiftStatus');
    filterInputs.forEach(input => {
        input.addEventListener('input', applyFilters);
        input.addEventListener('change', applyFilters);
    });
}

function applyFilters() {
    const searchName = document.getElementById('searchName').value.toLowerCase();
    const filterCategory = document.getElementById('filterCategory').value.toLowerCase();
    const filterShiftSource = document.getElementById('filterShiftSource').value;
    const filterShiftStatus = document.getElementById('filterShiftStatus').value;
    
    const rows = document.querySelectorAll('.employee-row');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const name = row.dataset.name || '';
        const category = row.dataset.category || '';
        const source = row.dataset.source || 'none';
        const status = row.dataset.status || 'none';
        
        const nameMatch = !searchName || name.includes(searchName);
        const categoryMatch = !filterCategory || category.includes(filterCategory);
        const sourceMatch = !filterShiftSource || source === filterShiftSource;
        const statusMatch = !filterShiftStatus || status === filterShiftStatus;
        
        if (nameMatch && categoryMatch && sourceMatch && statusMatch) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Update URL with filter parameters
    updateUrlParams();
    
    // Show empty state if no rows visible
    showEmptyState(visibleCount === 0);
    
    // Clear selection when filtering
    clearSelection();
}

function resetFilters() {
    document.getElementById('searchName').value = '';
    document.getElementById('filterCategory').value = '';
    document.getElementById('filterShiftSource').value = '';
    document.getElementById('filterShiftStatus').value = '';
    
    applyFilters();
}

function updateUrlParams() {
    const params = new URLSearchParams();
    
    const searchName = document.getElementById('searchName').value;
    const filterCategory = document.getElementById('filterCategory').value;
    const filterShiftSource = document.getElementById('filterShiftSource').value;
    const filterShiftStatus = document.getElementById('filterShiftStatus').value;
    
    if (searchName) params.set('search', searchName);
    if (filterCategory) params.set('category', filterCategory);
    if (filterShiftSource) params.set('source', filterShiftSource);
    if (filterShiftStatus) params.set('status', filterShiftStatus);
    
    const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
    window.history.replaceState({}, '', newUrl);
}

function initializeFromUrl() {
    const params = new URLSearchParams(window.location.search);
    
    if (params.has('search')) {
        document.getElementById('searchName').value = params.get('search');
    }
    if (params.has('category')) {
        document.getElementById('filterCategory').value = params.get('category');
    }
    if (params.has('source')) {
        document.getElementById('filterShiftSource').value = params.get('source');
    }
    if (params.has('status')) {
        document.getElementById('filterShiftStatus').value = params.get('status');
    }
    
    // Apply filters if any URL parameters exist
    if (params.toString()) {
        applyFilters();
    }
}

function showEmptyState(show) {
    // Remove existing empty state row if any
    const existingEmptyRow = document.querySelector('.empty-state-row');
    if (existingEmptyRow) {
        existingEmptyRow.remove();
    }
    
    if (show) {
        const tbody = document.querySelector('#employeesTableBody');
        const emptyRow = document.createElement('tr');
        emptyRow.className = 'empty-state-row';
        emptyRow.innerHTML = `
            <td colspan="11" class="text-center">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-search"></i>
                    </div>
                    <h4>No Employees Found</h4>
                    <p>Try adjusting your filters or clear them to see all employees</p>
                    <button onclick="resetFilters()" class="btn-filter btn-filter-primary mt-2">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reset Filters
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(emptyRow);
    }
}

function showAlert(message, type) {
    // Remove existing alerts
    document.querySelectorAll('.alert-dismissible').forEach(alert => {
        if (alert.parentNode) alert.remove();
    });
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    
    let icon = '';
    switch(type) {
        case 'success':
            icon = 'check-circle';
            break;
        case 'warning':
            icon = 'exclamation-triangle';
            break;
        case 'danger':
            icon = 'exclamation-circle';
            break;
        case 'info':
            icon = 'info-circle';
            break;
        default:
            icon = 'info-circle';
    }
    
    alertDiv.innerHTML = `
        <i class="bi bi-${icon}-fill me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container-fluid');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
    }
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Export functions for external use
window.resetFilters = resetFilters;
window.applyFilters = applyFilters;
window.clearSelection = clearSelection;
window.bulkAction = bulkAction;
</script>
@endsection