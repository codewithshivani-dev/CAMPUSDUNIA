@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Shift Management</title>
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
    
    .bulk-action-btn.activate {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    
    .bulk-action-btn.activate:hover {
        background: #fde68a;
    }
    
    .bulk-action-btn.deactivate {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    
    .bulk-action-btn.deactivate:hover {
        background: #fecaca;
    }
    
    .bulk-action-btn.delete {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    
    .bulk-action-btn.delete:hover {
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

    .bulk-action-btn i{
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

    .filter-form{
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
        color: white;
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
    
    /* Action Buttons */
    .table-actions {
        display: flex;
        gap: 5px;
        justify-content: center;
    }
    
    .action-btn {
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 5px;
        justify-content: center;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .action-btn:active {
        transform: translateY(0);
    }
    
    .action-btn-assign {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    
    .action-btn-edit {
        background: #fef2c8;
        color: #92400e;
        border-color: #fef2c8;
    }
    
    .action-btn-delete {
        background: #fee2e2;
        color: #dc2626;
        border-color: #fecaca;
    }
    
    .action-btn-assign:hover {
        background: #2563eb;
        text-decoration: none;
        color: white;
    }
    
    .action-btn-edit:hover {
        background: #fde68a;
        text-decoration: none;
        color: #92400e;
    }
    
    .action-btn-delete:hover {
        background: #fecaca;
    }
    
    /* Statistics Cards */
    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
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
    
    /* Form Card */
    .form-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    
    .form-card-header {
        background: #3b82f6;
        color: white;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .form-card-body {
        padding: 20px;
    }
    
    /* Priority Badges */
    .priority-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
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
        
        .table-actions {
            flex-direction: column;
            gap: 4px;
        }
        
        .action-btn {
            width: 100%;
        }
    }

    /* Alert styling */
    .alert-dismissible {
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">🕒 Shift Management</h1>
        <button class="btn-filter btn-filter-primary" onclick="toggleFormCollapse()">
            <i class="bi bi-plus-circle"></i>
            Create Shift
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e0f2fe;">
                    <i class="bi bi-clock-history text-primary"></i>
                </div>
                <div class="stat-value" id="totalShifts">0</div>
                <div class="stat-label">Total Shifts</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #dcfce7;">
                    <i class="bi bi-check-circle text-success"></i>
                </div>
                <div class="stat-value" id="activeShifts">0</div>
                <div class="stat-label">Active Shifts</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e0f2fe;">
                    <i class="bi bi-people text-info"></i>
                </div>
                <div class="stat-value" id="assignedEmployees">0</div>
                <div class="stat-label">Assigned Employees</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #fef3c7;">
                    <i class="bi bi-person-badge text-warning"></i>
                </div>
                <div class="stat-value" id="assignedStudents">0</div>
                <div class="stat-label">Assigned Students</div>
            </div>
        </div>
    </div>


    <!-- Shift Creation Card -->
    <div class="form-card mb-4" id="shiftFormCard">
        <div class="form-card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-plus-circle"></i>
                Create New Shift
            </div>
            <button class="btn btn-light btn-sm" onclick="toggleFormCollapse()">
                <i class="bi bi-chevron-down" id="collapseIcon"></i>
            </button>
        </div>
        <div class="collapse show" id="shiftFormCollapse">
            <div class="form-card-body">
                <form id="shiftForm" class="row g-3">
                    @csrf

                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Shift Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="shift_name" required placeholder="e.g., Morning Shift, Evening Shift">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Priority <span class="text-danger">*</span></label>
                        <select class="form-control" name="priority" required>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="low">Low</option>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Start Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="start_date" required min="{{ date('Y-m-d') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">End Date</label>
                        <input type="date" class="form-control" name="end_date" placeholder="Optional">
                        <small class="text-muted">Leave empty for ongoing shift</small>
                    </div>

                    <!-- Shift Timing Section -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Shift Start Time <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="time" class="form-control" id="start_time" name="start_time" required>
                            <span class="input-group-text bg-light">
                                <small id="start_time_display" class="text-muted"></small>
                            </span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-bold">Shift End Time <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="time" class="form-control" id="end_time" name="end_time" required>
                            <span class="input-group-text bg-light">
                                <small id="end_time_display" class="text-muted"></small>
                            </span>
                        </div>
                    </div>

                    <!-- Working Hours -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Working Hours <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="working_hours" name="working_hours" step="0.1" min="0" max="24" placeholder="Hours">
                            <span class="input-group-text">hours</span>
                        </div>
                        <small class="text-muted">Auto-calculated from start/end time</small>
                    </div>

                    <!-- Half Day & Short Leave Hours -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Half Day Hours</label>
                        <div class="input-group">
                            <input type="time" class="form-control" id="half_day_time" name="half_day_time" value="04:00">
                            <span class="input-group-text">HH:MM</span>
                        </div>
                        <input type="hidden" id="half_day_hours" name="half_day_hours" value="4.0">
                        <small class="text-muted">Default: 4 hours</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Short Leave Hours</label>
                        <div class="input-group">
                            <input type="time" class="form-control" id="short_leave_time" name="short_leave_time" value="02:00">
                            <span class="input-group-text">HH:MM</span>
                        </div>
                        <input type="hidden" id="short_leave_hours" name="short_leave_hours" value="2.0">
                        <small class="text-muted">Default: 2 hours</small>
                    </div>

                    <!-- Break Time Section - Single Row -->
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Break Time</label>
                                <div class="card border">
                                    <div class="card-body py-2">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-md-3">
                                                <label class="form-label small mb-1">Start Time</label>
                                                <input type="time" class="form-control form-control-sm break-time" id="break_start" name="break_start_time">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label small mb-1">End Time</label>
                                                <input type="time" class="form-control form-control-sm break-time" id="break_end" name="break_end_time">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label small mb-1">Duration</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" class="form-control" id="break_minutes" name="break_minutes" value="0" min="0" max="180" readonly>
                                                    <span class="input-group-text">min</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="d-flex h-100 align-items-end">
                                                    <small class="text-muted">Auto-calculated from break times</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grace Period -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Grace Period</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="grace_minutes" value="15" min="0" max="60">
                            <span class="input-group-text">minutes</span>
                        </div>
                        <small class="text-muted">Late arrival allowance</small>
                    </div>

                    <!-- Weekly Off Days -->
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Weekly Off Days</label>
                        <div class="card border">
                            <div class="card-body py-2">
                                <div class="row g-1">
                                    @php
                                        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                    @endphp
                                    @foreach($days as $day)
                                        <div class="col-3 col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="weekly_off_days[]" value="{{ $day }}" id="off_{{ strtolower($day) }}">
                                                <label class="form-check-label small" for="off_{{ strtolower($day) }}">{{ substr($day, 0, 3) }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Time Format Display (for user reference) -->
                    <div class="col-12">
                        <div class="alert alert-info py-2 mb-0 d-flex align-items-center">
                            <i class="bi bi-info-circle me-2 fs-5"></i>
                            <div>
                                <strong>Shift Timing Summary:</strong>
                                <span id="timeDisplay" class="ms-2">
                                    Times will be displayed here in 12-hour format
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <button type="reset" class="btn-filter btn-filter-secondary" onclick="resetTimeFields()">
                                <i class="bi bi-x-circle me-1"></i>Clear All
                            </button>
                            <button type="submit" class="btn-filter btn-filter-primary">
                                <i class="bi bi-plus-circle me-1"></i>Create Shift
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-container">
        <form method="GET" class="filter-form" id="filterForm">
            <div class="filter-group">
                <i class="bi bi-search"></i>
                <input type="text" name="search" class="filter-input" 
                       value="{{ request('search') }}" placeholder="Search shifts...">
            </div>

            <div class="filter-group">
                <i class="bi bi-funnel"></i>
                <select name="priority" class="filter-input">
                    <option value="">All Priorities</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>

            <div class="filter-group">
                <i class="bi bi-toggle-on"></i>
                <select name="status" class="filter-input">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter btn-filter-primary">
                    <i class="bi bi-funnel"></i>
                    Apply Filters
                </button>
                <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary">
                    <i class="bi bi-x-circle"></i>
                    Reset Filters
                </a>
            </div>
            
            <!-- Hidden sort inputs -->
            <input type="hidden" name="sort_by" id="sortBy" value="{{ request('sort_by', 'created_at') }}">
            <input type="hidden" name="sort_order" id="sortOrder" value="{{ request('sort_order', 'desc') }}">
        </form>
    </div>

    <!-- Bulk Actions Container -->
    <div class="bulk-actions-container" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 shifts selected</div>
        <div class="d-flex flex-wrap">
            <button class="bulk-action-btn assign" onclick="bulkAction('assign')">
                <i class="bi bi-person-plus"></i>
                Assign
            </button>
            <button class="bulk-action-btn activate" onclick="bulkAction('activate')">
                <i class="bi bi-toggle-on"></i>
                Activate
            </button>
            <button class="bulk-action-btn deactivate" onclick="bulkAction('deactivate')">
                <i class="bi bi-toggle-off"></i>
                Deactivate
            </button>
            <button class="bulk-action-btn delete" onclick="bulkAction('delete')">
                <i class="bi bi-trash"></i>
                Delete
            </button>
            <button class="bulk-action-btn clear mr-0" onclick="clearSelection()">
                <i class="bi bi-x-lg"></i>
                Clear
            </button>
        </div>
    </div>    

    <!-- Shifts Table -->
    <div class="form-card">
        <div class="form-card-header" style="background: #f8fafc; color: #1e293b;">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-clock"></i>
                All Shifts
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('shifts.assignform') }}" class="btn-filter btn-filter-primary" style="font-size: 14px; padding: 6px 12px;">
                    <i class="bi bi-person-plus me-1"></i>Assign Shifts
                </a>
            </div>
        </div>
        <div class="p-3">
            <div class="table-responsive">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="selectAll" class="select-checkbox">
                            </th>
                            <th class="sortable" onclick="sortTable('shift_name')">
                                Shift Name
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'shift_name' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'shift_name' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('priority')">
                                Priority
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'priority' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'priority' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('start_date')">
                                Date Range
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'start_date' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'start_date' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('start_time')">
                                Timing
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'start_time' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'start_time' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('working_hours')">
                                Working Hours
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'working_hours' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'working_hours' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('half_day_hours')">
                                Half Day
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'half_day_hours' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'half_day_hours' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('short_leave_hours')">
                                Short Leave
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'short_leave_hours' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'short_leave_hours' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('break_minutes')">
                                Break
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'break_minutes' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'break_minutes' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('grace_minutes')">
                                Grace
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'grace_minutes' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'grace_minutes' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th>Weekly Off</th>
                            <th class="sortable" onclick="sortTable('employees_count')">
                                Assigned
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'employees_count' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'employees_count' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('is_active')">
                                Status
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'is_active' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'is_active' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shifts as $shift)
                            <tr id="row-{{ $shift->id }}" class="{{ $shift->is_currently_active }}">
                                <td>
                                    <input type="checkbox" class="shift-checkbox select-checkbox" value="{{ $shift->id }}">
                                </td>
                                <td class="fw-bold">{{ $shift->shift_name }}</td>
                                <td>
                                    <span class="">
                                        {{ ucfirst($shift->priority) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        <div>{{ $shift->start_date->format('d M Y') }}</div>
                                        <div class="text-muted">to</div>
                                        <div>{{ $shift->end_date ? $shift->end_date->format('d M Y') : 'Ongoing' }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <div>{{ $shift->formatted_start_time }} ({{ $shift->start_time }})</div>
                                        <div class="text-muted">to</div>
                                        <div>{{ $shift->formatted_end_time }} ({{ $shift->end_time }})</div>
                                    </div>
                                </td>
                                <td>{{ number_format($shift->working_hours, 1) }} hrs</td>
                                <td>{{ number_format($shift->half_day_hours, 1) }} hrs</td>
                                <td>{{ number_format($shift->short_leave_hours, 1) }} hrs</td>
                                <td>{{ $shift->break_minutes }} min</td>
                                <td>{{ $shift->grace_minutes }} min</td>
                                <td>
                                    @if($shift->weekly_off_days && count($shift->weekly_off_days) > 0)
                                        <small>{{ implode(', ', array_slice($shift->weekly_off_days, 0, 2)) }}</small>
                                        @if(count($shift->weekly_off_days) > 2)
                                            <br><small class="text-muted">+{{ count($shift->weekly_off_days) - 2 }} more</small>
                                        @endif
                                    @else
                                        <span class="text-muted">None</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">
                                        <div class="text-primary">👨‍💼: {{ $shift->employees_count }}</div>
                                        <div class="text-info">👨‍🎓: {{ $shift->students_count }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge {{ $shift->is_active ? 'status-active' : 'status-inactive' }}">
                                        {{ $shift->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="table-actions">
                                        <a href="{{ route('shifts.assignform') }}?shift_id={{ $shift->id }}" 
                                           class="action-btn action-btn-assign" title="Assign Shift">
                                            <i class="bi bi-person-plus"></i>
                                            <span class="small">Assign</span>
                                        </a>
                                        <button class="action-btn action-btn-delete" onclick="deleteShift({{ $shift->id }})" title="Delete Shift">
                                            <i class="bi bi-trash"></i>
                                            <span class="small">Delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="bi bi-clock-history"></i>
                                        </div>
                                        <h4>No Shifts Found</h4>
                                        <p>Create your first shift to get started</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
          @if(method_exists($shifts, 'hasPages') && $shifts->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-sm text-gray-600">
                        Showing {{ $shifts->firstItem() ?? 0 }} to {{ $shifts->lastItem() ?? 0 }} of {{ $shifts->total() }} results
                    </div>
                    <div>
                        {{ $shifts->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this shift? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-filter btn-filter-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-filter btn-filter-primary" id="confirmDeleteBtn" style="background: #dc2626; border-color: #dc2626;">Delete</button>
            </div>
        </div>
    </div>
</div>


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    // Bulk Selection Management
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const shiftCheckboxes = document.querySelectorAll('.shift-checkbox');
        const bulkActionsContainer = document.getElementById('bulkActionsContainer');
        const selectedCountElement = document.getElementById('selectedCount');

        // Select All functionality
        selectAllCheckbox.addEventListener('change', function() {
            shiftCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectionUI();
        });

        // Individual checkbox change
        shiftCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectionUI);
        });

        function updateSelectionUI() {
            const selectedCount = document.querySelectorAll('.shift-checkbox:checked').length;
            
            if (selectedCount > 0) {
                bulkActionsContainer.classList.add('active');
                selectedCountElement.textContent = selectedCount + ' shift(s) selected';
                
                // Update select all checkbox state
                selectAllCheckbox.checked = selectedCount === shiftCheckboxes.length;
                selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < shiftCheckboxes.length;
            } else {
                bulkActionsContainer.classList.remove('active');
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
        }

        // Load statistics on page load
        loadShiftStatistics();
        setupTimeCalculations();
    });

    // Bulk Action Functions
    function clearSelection() {
        document.querySelectorAll('.shift-checkbox:checked').forEach(checkbox => {
            checkbox.checked = false;
        });
        document.getElementById('selectAll').checked = false;
        document.getElementById('bulkActionsContainer').classList.remove('active');
    }

    function bulkAction(action) {
        const selectedShifts = Array.from(document.querySelectorAll('.shift-checkbox:checked'))
            .map(checkbox => checkbox.value);
        
        if (selectedShifts.length === 0) {
            showAlert('Please select at least one shift.', 'warning');
            return;
        }
        
        switch(action) {
            case 'assign':
                if (confirm(`Assign ${selectedShifts.length} shift(s) to employees/students?`)) {
                    window.location.href = `{{ route('shifts.assignform') }}?shift_ids=${selectedShifts.join(',')}`;
                }
                break;
                
            case 'activate':
                if (confirm(`Activate ${selectedShifts.length} shift(s)?`)) {
                    updateShiftStatus(selectedShifts, true);
                }
                break;
                
            case 'deactivate':
                if (confirm(`Deactivate ${selectedShifts.length} shift(s)?`)) {
                    updateShiftStatus(selectedShifts, false);
                }
                break;
                
            case 'delete':
                if (confirm(`Delete ${selectedShifts.length} shift(s)? This action cannot be undone.`)) {
                    deleteShifts(selectedShifts);
                }
                break;
                
            default:
                showAlert(`${action} action triggered for ${selectedShifts.length} shifts`, 'info');
        }
    }

    function updateShiftStatus(shiftIds, isActive) {
        const btn = event?.target?.closest('button');
        if (btn) {
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<span class="loading-spinner"></span> Processing...';
            btn.disabled = true;
        }

        fetch(`/institute/admin/shifts/bulk-update-status`, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                shift_ids: shiftIds,
                is_active: isActive 
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update status badges in table
                shiftIds.forEach(id => {
                    const statusBadge = document.querySelector(`#row-${id} .status-badge`);
                    if (statusBadge) {
                        if (isActive) {
                            statusBadge.className = 'status-badge status-active';
                            statusBadge.textContent = 'Active';
                        } else {
                            statusBadge.className = 'status-badge status-inactive';
                            statusBadge.textContent = 'Inactive';
                        }
                    }
                });
                
                loadShiftStatistics();
                clearSelection();
                showAlert(`${shiftIds.length} shift(s) ${isActive ? 'activated' : 'deactivated'} successfully!`, 'success');
            } else {
                showAlert(data.message || 'Error updating shift status', 'danger');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            showAlert('Network error occurred. Please try again.', 'danger');
        })
        .finally(() => {
            if (btn) {
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            }
        });
    }

    function deleteShifts(shiftIds) {
        const btn = event?.target?.closest('button');
        if (btn) {
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<span class="loading-spinner"></span> Deleting...';
            btn.disabled = true;
        }

        fetch(`/institute/admin/shifts/bulk-delete`, {
            method: 'DELETE',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ shift_ids: shiftIds })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Remove rows from table
                shiftIds.forEach(id => {
                    const row = document.getElementById(`row-${id}`);
                    if (row) row.remove();
                });
                
                loadShiftStatistics();
                clearSelection();
                
                // Show empty state if no rows left
                const tbody = document.querySelector('.erp-table tbody');
                const rows = tbody.querySelectorAll('tr:not(.empty-state)');
                if (rows.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="14" class="text-center">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <h4>No Shifts Found</h4>
                                    <p>Create your first shift to get started</p>
                                </div>
                            </td>
                        </tr>`;
                }
                
                showAlert(`${shiftIds.length} shift(s) deleted successfully!`, 'success');
            } else {
                showAlert(data.message || 'Error deleting shifts', 'danger');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            showAlert('Network error occurred. Please try again.', 'danger');
        })
        .finally(() => {
            if (btn) {
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            }
        });
    }

    // Load statistics
    function loadShiftStatistics() {
        const shifts = @json($shifts);
        const totalShifts = shifts.length;
        const activeShifts = shifts.filter(shift => shift.is_active).length;
        
        let assignedEmployees = 0;
        let assignedStudents = 0;
        
        shifts.forEach(shift => {
            assignedEmployees += parseInt(shift.employees_count) || 0;
            assignedStudents += parseInt(shift.students_count) || 0;
        });
        
        document.getElementById('totalShifts').textContent = totalShifts;
        document.getElementById('activeShifts').textContent = activeShifts;
        document.getElementById('assignedEmployees').textContent = assignedEmployees;
        document.getElementById('assignedStudents').textContent = assignedStudents;
    }

    // Form collapse toggle
    function toggleFormCollapse() {
        const collapseElement = document.getElementById('shiftFormCollapse');
        const collapseIcon = document.getElementById('collapseIcon');
        const bsCollapse = new bootstrap.Collapse(collapseElement, {
            toggle: true
        });
        
        collapseElement.addEventListener('hidden.bs.collapse', function () {
            collapseIcon.className = 'bi bi-chevron-down';
        });
        
        collapseElement.addEventListener('shown.bs.collapse', function () {
            collapseIcon.className = 'bi bi-chevron-up';
        });
    }

    // Sorting Functionality
    function sortTable(column) {
        const currentSortBy = document.getElementById('sortBy').value;
        const currentSortOrder = document.getElementById('sortOrder').value;
        
        let newSortOrder = 'asc';
        
        if (currentSortBy === column) {
            newSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
        }
        
        document.getElementById('sortBy').value = column;
        document.getElementById('sortOrder').value = newSortOrder;
        
        // Submit the form
        document.getElementById('filterForm').submit();
    }

    // Time calculations
    function setupTimeCalculations() {
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        const workingHoursInput = document.getElementById('working_hours');
        const timeDisplay = document.getElementById('timeDisplay');
        const startTimeDisplay = document.getElementById('start_time_display');
        const endTimeDisplay = document.getElementById('end_time_display');
        const breakStart = document.getElementById('break_start');
        const breakEnd = document.getElementById('break_end');
        const breakMinutes = document.getElementById('break_minutes');
        
        function formatTimeTo12Hour(time24) {
            if (!time24) return '';
            
            let [hours, minutes] = time24.split(':');
            hours = parseInt(hours);
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            return `${hours.toString().padStart(2, '0')}:${minutes} ${ampm}`;
        }
        
        function calculateTimeDifference(start, end) {
            if (!start || !end) return 0;
            
            const startDate = new Date(`1970-01-01T${start}`);
            const endDate = new Date(`1970-01-01T${end}`);
            
            let diff = (endDate - startDate) / (1000 * 60 * 60);
            
            if (diff < 0) {
                diff += 24;
            }
            
            return Math.round(diff * 10) / 10;
        }
        
        function updateWorkingHours() {
            const startTime = startTimeInput.value;
            const endTime = endTimeInput.value;
            
            if (startTime && endTime) {
                const hours = calculateTimeDifference(startTime, endTime);
                workingHoursInput.value = hours;
                updateTimeDisplay();
            }
        }
        
        function updateTimeDisplay() {
            const startTime = startTimeInput.value;
            const endTime = endTimeInput.value;
            const workingHours = workingHoursInput.value;
            
            if (startTime) {
                startTimeDisplay.textContent = formatTimeTo12Hour(startTime);
            } else {
                startTimeDisplay.textContent = '';
            }
            
            if (endTime) {
                endTimeDisplay.textContent = formatTimeTo12Hour(endTime);
            } else {
                endTimeDisplay.textContent = '';
            }
            
            if (startTime && endTime) {
                const start12 = formatTimeTo12Hour(startTime);
                const end12 = formatTimeTo12Hour(endTime);
                timeDisplay.innerHTML = `<strong>Shift Timing:</strong> ${start12} to ${end12} (${workingHours} hours)`;
            } else {
                timeDisplay.textContent = 'Shift timing will be displayed here in 12-hour format';
            }
        }
        
        function calculateBreakDuration() {
            if (breakStart.value && breakEnd.value) {
                const start = new Date('1970-01-01T' + breakStart.value);
                const end = new Date('1970-01-01T' + breakEnd.value);
                
                let diff = (end - start) / (1000 * 60);
                
                if (diff < 0) {
                    diff += 24 * 60;
                }
                
                breakMinutes.value = Math.round(diff);
            } else {
                breakMinutes.value = 0;
            }
        }
        
        function updateHalfDayHours() {
            const timeInput = document.getElementById('half_day_time');
            const hiddenInput = document.getElementById('half_day_hours');
            const decimalHours = timeToDecimalHours(timeInput.value);
            hiddenInput.value = decimalHours.toFixed(2);
        }
        
        function updateShortLeaveHours() {
            const timeInput = document.getElementById('short_leave_time');
            const hiddenInput = document.getElementById('short_leave_hours');
            const decimalHours = timeToDecimalHours(timeInput.value);
            hiddenInput.value = decimalHours.toFixed(2);
        }
        
        function timeToDecimalHours(timeString) {
            if (!timeString) return 0;
            const [hours, minutes] = timeString.split(':').map(Number);
            return hours + (minutes / 60);
        }
        
        // Event listeners
        startTimeInput.addEventListener('change', updateWorkingHours);
        endTimeInput.addEventListener('change', updateWorkingHours);
        startTimeInput.addEventListener('input', updateTimeDisplay);
        endTimeInput.addEventListener('input', updateTimeDisplay);
        
        breakStart.addEventListener('change', calculateBreakDuration);
        breakEnd.addEventListener('change', calculateBreakDuration);
        breakStart.addEventListener('input', calculateBreakDuration);
        breakEnd.addEventListener('input', calculateBreakDuration);
        
        document.getElementById('half_day_time').addEventListener('input', updateHalfDayHours);
        document.getElementById('short_leave_time').addEventListener('input', updateShortLeaveHours);
        
        // Initialize
        updateTimeDisplay();
        updateHalfDayHours();
        updateShortLeaveHours();
    }

    // Make resetTimeFields globally accessible
    window.resetTimeFields = function() {
        const workingHoursInput = document.getElementById('working_hours');
        const timeDisplay = document.getElementById('timeDisplay');
        const startTimeDisplay = document.getElementById('start_time_display');
        const endTimeDisplay = document.getElementById('end_time_display');
        const breakMinutes = document.getElementById('break_minutes');
        
        workingHoursInput.value = '';
        timeDisplay.textContent = 'Shift timing will be displayed here in 12-hour format';
        startTimeDisplay.textContent = '';
        endTimeDisplay.textContent = '';
        breakMinutes.value = 0;
        
        document.getElementById('half_day_time').value = '04:00';
        document.getElementById('short_leave_time').value = '02:00';
        
        const halfDayHidden = document.getElementById('half_day_hours');
        const shortLeaveHidden = document.getElementById('short_leave_hours');
        if (halfDayHidden) halfDayHidden.value = '4.00';
        if (shortLeaveHidden) shortLeaveHidden.value = '2.00';
    };

    // Delete single shift
    function deleteShift(id) {
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
        
        document.getElementById('confirmDeleteBtn').onclick = function() {
            const btn = this;
            const originalText = btn.textContent;
            btn.innerHTML = '<span class="loading-spinner"></span> Deleting...';
            btn.disabled = true;
            
            fetch(`/institute/admin/shifts/${id}`, {
                method: 'DELETE',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`row-${id}`).remove();
                    loadShiftStatistics();
                    deleteModal.hide();
                    showAlert('Shift deleted successfully!', 'success');
                    
                    // Show empty state if no rows left
                    const tbody = document.querySelector('.erp-table tbody');
                    const rows = tbody.querySelectorAll('tr:not(.empty-state)');
                    if (rows.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="14" class="text-center">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="bi bi-clock-history"></i>
                                        </div>
                                        <h4>No Shifts Found</h4>
                                        <p>Create your first shift to get started</p>
                                    </div>
                                </td>
                            </tr>`;
                    }
                } else {
                    showAlert(data.message || 'Error deleting shift', 'danger');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                showAlert('Network error occurred. Please try again.', 'danger');
            })
            .finally(() => {
                btn.textContent = originalText;
                btn.disabled = false;
            });
        };
    }

    // Shift form submission (keep existing functionality)
    document.getElementById('shiftForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Creating...';
        submitBtn.disabled = true;
        
        let formData = new FormData(this);

        fetch('{{ route("manage.shifts.store") }}', {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => {
            if (!res.ok) {
                return res.json().then(data => {
                    throw new Error(data.errors ? Object.values(data.errors).flat().join(', ') : 'Network error');
                });
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                let s = data.shift;
                
                // Create new row
                let row = `
                    <tr id="row-${s.id}">
                        <td>
                            <input type="checkbox" class="shift-checkbox select-checkbox" value="${s.id}">
                        </td>
                        <td class="fw-bold">${s.shift_name}</td>
                        <td>
                            <span class="priority-badge priority-${s.priority}">
                                ${s.priority.charAt(0).toUpperCase() + s.priority.slice(1)}
                            </span>
                        </td>
                        <td>
                            <div class="small">
                                <div>${new Date(s.start_date).toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' })}</div>
                                <div class="text-muted">to</div>
                                <div>${s.end_date ? new Date(s.end_date).toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' }) : 'Ongoing'}</div>
                            </div>
                        </td>
                        <td>
                            <div class="small">
                                <div>${s.formatted_start_time} (${s.start_time})</div>
                                <div class="text-muted">to</div>
                                <div>${s.formatted_end_time} (${s.end_time})</div>
                            </div>
                        </td>
                        <td>${parseFloat(s.working_hours).toFixed(1)} hrs</td>
                        <td>${parseFloat(s.half_day_hours).toFixed(1)} hrs</td>
                        <td>${parseFloat(s.short_leave_hours).toFixed(1)} hrs</td>
                        <td>${s.break_minutes} min</td>
                        <td>${s.grace_minutes} min</td>
                        <td>
                            ${(s.weekly_off_days && s.weekly_off_days.length > 0) ? 
                                `<small>${s.weekly_off_days.slice(0,2).join(', ')}</small>` + 
                                (s.weekly_off_days.length > 2 ? `<br><small class="text-muted">+${s.weekly_off_days.length - 2} more</small>` : '') 
                                : '<span class="text-muted">None</span>'}
                        </td>
                        <td>
                            <div class="small">
                                <div class="text-primary">👨‍💼: 0</div>
                                <div class="text-info">👨‍🎓: 0</div>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge status-active">
                                Active
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="table-actions d-flex flex-column align-items-center gap-2">

                                <a href="{{ route('shifts.assignform') }}?shift_id=${s.id}"
                                class="action-btn action-btn-assign text-center d-block"
                                title="Assign Shift">
                                    <div>
                                        <i class="bi bi-person-plus"></i>
                                    </div>
                                    <div>
                                        <span class="small">Assign</span>
                                    </div>
                                </a>

                                <button class="action-btn action-btn-delete text-center d-block"
                                        onclick="deleteShift(${s.id})"
                                        title="Delete Shift">
                                    <div>
                                        <i class="bi bi-trash"></i>
                                    </div>
                                    <div>
                                        <span class="small">Delete</span>
                                    </div>
                                </button>

                            </div>
                        </td>
                    </tr>`;
                
                const tbody = document.querySelector('.erp-table tbody');
                const emptyRow = tbody.querySelector('tr:first-child');
                
                if (emptyRow && emptyRow.querySelector('.empty-state')) {
                    tbody.innerHTML = row;
                } else {
                    tbody.insertAdjacentHTML('afterbegin', row);
                }
                
                // Reset form
                this.reset();
                resetTimeFields();
                loadShiftStatistics();
                
                // Add checkbox event listener to new row
                const newCheckbox = document.querySelector(`#row-${s.id} .shift-checkbox`);
                newCheckbox.addEventListener('change', updateSelectionUI);
                
                showAlert('Shift created successfully!', 'success');
                
                // Collapse the form
                toggleFormCollapse();
            } else {
                let errorMessage = 'Error saving shift!';
                if (data.errors) {
                    errorMessage = Object.values(data.errors).flat().join(', ');
                }
                showAlert(errorMessage, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert(error.message || 'Network error occurred. Please try again.', 'danger');
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });

    // Utility function to show alerts
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
</script>
@endsection