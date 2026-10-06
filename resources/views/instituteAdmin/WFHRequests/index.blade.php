@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'Work From Home Requests')

@section('content')
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --success-gradient: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
    --shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.15);
}

/* Page Header */
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
    gap: 10px;
}

.page-title i {
    background: rgba(255, 255, 255, 0.2);
    padding: 10px 12px;
    border-radius: 12px;
}

/* Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.stats-card-modern {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    border: none;
    position: relative;
    overflow: hidden;
}

.stats-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.stats-card-modern.primary::before { background: linear-gradient(90deg, #4361ee, #3a0ca3); }
.stats-card-modern.warning::before { background: linear-gradient(90deg, #f093fb, #f5576c); }
.stats-card-modern.success::before { background: linear-gradient(90deg, #84fab0, #8fd3f4); }
.stats-card-modern.info::before { background: linear-gradient(90deg, #4facfe, #00f2fe); }
.stats-card-modern.danger::before { background: linear-gradient(90deg, #fa709a, #fee140); }
.stats-card-modern.secondary::before { background: linear-gradient(90deg, #fccb90, #d57eeb); }

.stats-card-modern:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-hover);
}

.stats-card-modern .stats-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: white;
    margin-bottom: 10px;
}

.stats-card-modern .stats-icon.primary { background: linear-gradient(135deg, #4361ee, #3a0ca3); }
.stats-card-modern .stats-icon.warning { background: linear-gradient(135deg, #f093fb, #f5576c); }
.stats-card-modern .stats-icon.success { background: linear-gradient(135deg, #84fab0, #8fd3f4); }
.stats-card-modern .stats-icon.info { background: linear-gradient(135deg, #4facfe, #00f2fe); }
.stats-card-modern .stats-icon.danger { background: linear-gradient(135deg, #fa709a, #fee140); }
.stats-card-modern .stats-icon.secondary { background: linear-gradient(135deg, #fccb90, #d57eeb); }

.stats-card-modern .stats-number {
    font-size: 26px;
    font-weight: 700;
    color: #2d3436;
    line-height: 1.2;
}

.stats-card-modern .stats-label {
    font-size: 13px;
    color: #636e72;
    font-weight: 500;
    margin-top: 2px;
}

/* Filter Container */
.filter-container {
    background: #fff;
    border-radius: 15px;
    padding: 20px 25px;
    margin-bottom: 24px;
    border: 1px solid #e2e8f0;
    box-shadow: var(--shadow-sm);
}

.filter-form {
    justify-content: space-between;
    align-items: center;
    gap: 16px;
}

.filter-grid {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    flex: 1;
}

.filter-group {
    flex: 1;
    min-width: 150px;
}

.filter-group label {
    font-size: 12px;
    font-weight: 600;
    color: #636e72;
    margin-bottom: 4px;
    display: block;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.filter-input {
    padding: 9px 14px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    background: #fff;
    transition: all 0.2s;
    width: 100%;
}

.filter-input:focus {
    outline: none;
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    transform: translateY(-1px);
}

.filter-input:hover {
    border-color: #cbd5e1;
}

.filter-actions {
    margin-top: 10px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}

.btn-filter {
    padding: 9px 18px;
    border-radius: 10px;
    font-weight: 500;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    font-size: 14px;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.btn-filter-primary {
    background: var(--primary-gradient);
    color: white;
}

.btn-filter-primary:hover {
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    color: white;
}

.btn-filter-secondary {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}

.btn-filter-secondary:hover {
    background: #e2e8f0;
    color: #475569;
}

.btn-filter-success {
    background: linear-gradient(135deg, #00b894, #00cec9);
    color: white;
}

.btn-filter-success:hover {
    box-shadow: 0 4px 15px rgba(0, 206, 201, 0.3);
    color: white;
}

/* Bulk Actions */
.bulk-actions-container {
    display: none;
    margin-bottom: 16px;
    animation: slideDown 0.3s ease;
    background: #fff;
    padding: 15px 20px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: var(--shadow-sm);
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.bulk-actions-container.active {
    display: flex;
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

.selected-count {
    font-weight: 500;
    color: #4361ee;
    margin-right: auto;
    font-size: 14px;
    background: #e0e7ff;
    padding: 5px 16px;
    border-radius: 20px;
}

.bulk-action-btn {
    padding: 7px 16px;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all 0.2s;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.bulk-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.bulk-action-btn.approve {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.bulk-action-btn.approve:hover {
    background: #bbf7d0;
}

.bulk-action-btn.reject {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.bulk-action-btn.reject:hover {
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

/* Table Styles */
.erp-table {
    width: 100%;
    background: #fff;
    border-collapse: collapse;
    border-radius: 12px;
    overflow: hidden;
}

.erp-table thead {
    background: var(--primary-gradient);
    border-bottom: 2px solid #e2e8f0;
}

.erp-table th {
    padding: 14px 12px;
    font-weight: 600;
    color: white;
    text-align: left;
    font-size: 13px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.15);
}

.erp-table th .th-sub {
    font-size: 11px;
    font-weight: 400;
    color: rgba(255, 255, 255, 0.7);
    margin-top: 2px;
    display: block;
}

.erp-table td {
    padding: 14px 12px;
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
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.erp-table tbody tr:last-child td {
    border-bottom: none;
}

/* Status Badges */
.badge-status {
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.badge-status.pending {
    background: #fef3c7;
    color: #92400e;
}

.badge-status.approved {
    background: #dcfce7;
    color: #166534;
}

.badge-status.rejected {
    background: #fee2e2;
    color: #991b1b;
}

.badge-status.completed {
    background: #dbeafe;
    color: #1e40af;
}

.badge-status.cancelled {
    background: #e5e7eb;
    color: #4b5563;
}

/* Action Buttons */
.table-actions {
    display: flex;
    gap: 5px;
    justify-content: center;
    flex-wrap: wrap;
}

.action-btn {
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    text-decoration: none;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-decoration: none;
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

.action-btn-approve {
    background: #dcfce7;
    color: #166534;
    border-color: #bbf7d0;
}

.action-btn-approve:hover {
    background: #bbf7d0;
    color: #166534;
}

.action-btn-reject {
    background: #fee2e2;
    color: #991b1b;
    border-color: #fecaca;
}

.action-btn-reject:hover {
    background: #fecaca;
    color: #991b1b;
}

.action-btn-complete {
    background: #dbeafe;
    color: #1e40af;
    border-color: #bfdbfe;
}

.action-btn-complete:hover {
    background: #bfdbfe;
    color: #1e40af;
}

.action-btn-card {
    background: #fef3c7;
    color: #92400e;
    border-color: #fde68a;
}

.action-btn-card:hover {
    background: #fde68a;
    color: #92400e;
}

/* Select Checkbox */
.select-checkbox {
    width: 18px;
    height: 18px;
    cursor: pointer;
    border-radius: 4px;
    border: 2px solid #cbd5e1;
    transition: all 0.2s;
}

.select-checkbox:hover {
    border-color: #4361ee;
}

.select-checkbox:checked {
    background-color: #4361ee;
    border-color: #4361ee;
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

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
        padding: 15px 20px;
    }
    
    .filter-grid {
        width: 100%;
    }
    
    .filter-actions {
        width: 100%;
        justify-content: center;
    }
    
    .bulk-actions-container {
        flex-direction: column;
        align-items: stretch;
    }
    
    .bulk-actions-container .d-flex {
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .erp-table {
        font-size: 13px;
    }
    
    .erp-table th,
    .erp-table td {
        padding: 10px 8px;
    }
    
    .table-actions {
        flex-direction: column;
        gap: 4px;
    }
    
    .action-btn {
        width: 100%;
        justify-content: center;
    }
}

/* Filter Badges */
.filter-badge {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
    margin: 2px 4px 2px 0;
}

.filter-badge.primary { background: #e0e7ff; color: #3730a3; }
.filter-badge.success { background: #dcfce7; color: #166534; }
.filter-badge.warning { background: #fef3c7; color: #92400e; }
.filter-badge.danger { background: #fee2e2; color: #991b1b; }
.filter-badge.info { background: #dbeafe; color: #1e40af; }

/* Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Loading Spinner */
.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #ccc;
    border-top-color: #4361ee;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

#pageLoader {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(255,255,255,0.7);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

#pageLoader.show {
    display: flex;
}

/* Tooltip */
.tooltip-wrap {
    position: relative;
    display: inline-block;
}

.tooltip-wrap .tooltip-text {
    visibility: hidden;
    width: 120px;
    background: #333;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin-left: -60px;
    opacity: 0;
    transition: opacity 0.3s;
    font-size: 11px;
}

.tooltip-wrap .tooltip-text::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #333 transparent transparent transparent;
}

.tooltip-wrap:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
}
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h4 class="page-title">
            <i class="fas fa-home"></i>
            Work From Home Requests
        </h4>
        <div class="d-flex gap-2 d-none">
            <button class="add-btn" onclick="exportRequests()" style="background: linear-gradient(135deg, #00b894, #00cec9);">
                <i class="fas fa-file-export"></i>
                Export
            </button>
        </div>
    </div>

    <!-- Messages -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stats-card-modern primary">
            <div class="stats-icon primary">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stats-number">{{ $stats['total'] }}</div>
            <div class="stats-label">Total Requests</div>
        </div>
        <div class="stats-card-modern warning">
            <div class="stats-icon warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stats-number">{{ $stats['pending'] }}</div>
            <div class="stats-label">Pending</div>
        </div>
        <div class="stats-card-modern success">
            <div class="stats-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stats-number">{{ $stats['approved'] }}</div>
            <div class="stats-label">Approved</div>
        </div>
        <div class="stats-card-modern danger">
            <div class="stats-icon danger">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stats-number">{{ $stats['rejected'] }}</div>
            <div class="stats-label">Rejected</div>
        </div>
        <div class="stats-card-modern info">
            <div class="stats-icon info">
                <i class="fas fa-flag-checkered"></i>
            </div>
            <div class="stats-number">{{ $stats['completed'] }}</div>
            <div class="stats-label">Completed</div>
        </div>
        <div class="stats-card-modern secondary">
            <div class="stats-icon secondary">
                <i class="fas fa-home"></i>
            </div>
            <div class="stats-number">{{ $stats['active_today'] }}</div>
            <div class="stats-label">Today</div>
        </div>
    </div>

    <!-- Filter Container -->
    <div class="filter-container">
        <form method="GET" class="filter-form" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <label>Employee Name</label>
                    <input list="employeeNamesList" name="employee_name" class="filter-input" 
                           value="{{ request('employee_name') }}" placeholder="Search by name...">
                    <datalist id="employeeNamesList">
                        @foreach($employees ?? [] as $emp)
                        <option value="{{ $emp->name }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="filter-group">
                    <label>Employee Code</label>
                    <input list="employeeCodeList" name="employee_code" class="filter-input"
                           value="{{ request('employee_code') }}" placeholder="Search by code...">
                    <datalist id="employeeCodeList">
                        @foreach($employees ?? [] as $emp)
                        <option value="{{ $emp->employee_code }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="filter-group">
                    <label>Status</label>
                    <select name="request_status" class="filter-input">
                        <option value="">All Status</option>
                        @foreach($statusOptions as $status)
                        <option value="{{ $status }}" {{ (request('request_status') == $status) ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label>From Date</label>
                    <input type="date" name="date_from" class="filter-input" value="{{ request('date_from') }}">
                </div>

                <div class="filter-group">
                    <label>To Date</label>
                    <input type="date" name="date_to" class="filter-input" value="{{ request('date_to') }}">
                </div>
            </div>

            <div class="filter-actions d-none">
                <button type="submit" class="btn-filter btn-filter-primary">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('institute-admin.wfh-requests.index') }}" class="btn-filter btn-filter-secondary">
                    <i class="fas fa-undo-alt"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Result Count -->
    <div class="mb-3">
        <div class="alert alert-secondary py-2 mb-0" role="status" style="font-size: 0.95rem; border-radius: 10px;">
            Showing <strong>{{ $requests->total() }}</strong> request{{ $requests->total() == 1 ? '' : 's' }}
            @if(request()->filled('employee_name') || request()->filled('employee_code') || request()->filled('request_status') || request()->filled('date_from') || request()->filled('date_to'))
            <span class="ms-2">
                @if(request('employee_name'))<span class="filter-badge primary"><i class="fas fa-user"></i> {{ request('employee_name') }}</span>@endif
                @if(request('employee_code'))<span class="filter-badge info"><i class="fas fa-id-badge"></i> {{ request('employee_code') }}</span>@endif
                @if(request('request_status'))<span class="filter-badge warning"><i class="fas fa-tag"></i> {{ ucfirst(request('request_status')) }}</span>@endif
            </span>
            @endif
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions-container" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 requests selected</div>
        <div class="d-flex flex-wrap gap-2">
            <button class="bulk-action-btn approve" onclick="bulkAction('approve')">
                <i class="fas fa-check-circle"></i> Approve Selected
            </button>
            <button class="bulk-action-btn reject" onclick="bulkAction('reject')">
                <i class="fas fa-times-circle"></i> Reject Selected
            </button>
            <button class="bulk-action-btn clear" onclick="clearSelection()">
                <i class="fas fa-times"></i> Clear
            </button>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="table-responsive custom-table-wrapper">
        <table class="erp-table">
            <thead>
                <tr>
                    <th width="40">
                        <input type="checkbox" id="selectAll" class="select-checkbox">
                    </th>
                    <th>Request ID</th>
                    <th>Employee</th>
                    <th>Date Range</th>
                    <th>Duration</th>
                    <th>Working Hours</th>
                    <th>Status</th>
                    <th>Requested On</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)
                <tr>
                    <td>
                        <input type="checkbox" class="request-checkbox select-checkbox" value="{{ $request->id }}">
                    </td>
                    <td>
                        <strong>{{ $request->request_id }}</strong>
                    </td>
                    <td>
                        <strong>{{ $request->employee->name ?? 'N/A' }}</strong>
                        <span class="th-sub">{{ $request->employee->employee_code ?? 'N/A' }}</span>
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($request->start_date)->format('d M, Y') }}
                        <span class="mx-1">→</span>
                        {{ \Carbon\Carbon::parse($request->end_date)->format('d M, Y') }}
                    </td>
                    <td>
                        <span>{{ $request->duration }}</span>
                    </td>
                    <td>
                        @if($request->start_time && $request->end_time)
                            <span class="text-muted small">
                                {{ date('h:i A', strtotime($request->start_time)) }} - 
                                {{ date('h:i A', strtotime($request->end_time)) }}
                            </span>
                        @else
                            <span class="text-muted small">N/A</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge-status {{ $request->request_status }}">
                            <i class="fas 
                                @if($request->request_status == 'pending') fa-clock
                                @elseif($request->request_status == 'approved') fa-check-circle
                                @elseif($request->request_status == 'rejected') fa-times-circle
                                @elseif($request->request_status == 'completed') fa-flag-checkered
                                @elseif($request->request_status == 'cancelled') fa-ban
                                @endif">
                            </i>
                            {{ ucfirst($request->request_status) }}
                        </span>
                    </td>
                    <td>
                        <span class="text-muted small">{{ $request->created_at->format('d M, Y H:i') }}</span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <button onclick="viewRequest({{ $request->id }})" class="action-btn action-btn-view">
                                <i class="fas fa-eye"></i> View
                            </button>
                            @if($request->canApprove())
                                <button onclick="approveRequest({{ $request->id }})" class="action-btn action-btn-approve">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button onclick="rejectRequest({{ $request->id }})" class="action-btn action-btn-reject">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            @endif
                            @if($request->isApproved() && !$request->isCompleted())
                                <button onclick="completeRequest({{ $request->id }})" class="action-btn action-btn-complete d-none">
                                    <i class="fas fa-flag-checkered"></i> Complete
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <h4>No WFH Requests Found</h4>
                            <p>Try adjusting your filters or create a new request.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($requests->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-sm text-gray-600">
            Showing {{ $requests->firstItem() ?? 0 }} to {{ $requests->lastItem() ?? 0 }} of {{ $requests->total() }} results
        </div>
        <div>
            {{ $requests->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
        </div>
    </div>
    @endif
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkModalTitle">Bulk Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="bulkModalMessage">Are you sure you want to perform this action on selected requests?</p>
                <div class="form-group">
                    <label>Remarks (Optional)</label>
                    <textarea id="bulkRemarks" class="form-control" rows="3" 
                              placeholder="Add remarks for this bulk action..."></textarea>
                </div>
                <input type="hidden" id="bulkActionType" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="processBulkAction()">
                    <i class="fas fa-check"></i> Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Page Loader -->
<div id="pageLoader">
    <div class="spinner"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ============================================
// SELECT ALL & BULK SELECTION
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const bulkActionsContainer = document.getElementById('bulkActionsContainer');
    const selectedCountElement = document.getElementById('selectedCount');

    function updateSelectionUI() {
        const checkboxes = document.querySelectorAll('.request-checkbox');
        const checked = document.querySelectorAll('.request-checkbox:checked');
        const count = checked.length;

        if (count > 0) {
            bulkActionsContainer.classList.add('active');
            selectedCountElement.textContent = count + ' request(s) selected';
            selectAllCheckbox.checked = count === checkboxes.length && checkboxes.length > 0;
            selectAllCheckbox.indeterminate = count > 0 && count < checkboxes.length;
        } else {
            bulkActionsContainer.classList.remove('active');
            selectedCountElement.textContent = '0 requests selected';
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
    }

    // Select All
    selectAllCheckbox.addEventListener('change', function() {
        document.querySelectorAll('.request-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateSelectionUI();
    });

    // Individual checkbox
    document.querySelectorAll('.request-checkbox').forEach(cb => {
        cb.addEventListener('change', updateSelectionUI);
    });

    // Initial update
    updateSelectionUI();
});

// ============================================
// BULK ACTIONS
// ============================================
function clearSelection() {
    document.querySelectorAll('.request-checkbox:checked').forEach(cb => {
        cb.checked = false;
    });
    document.querySelectorAll('.request-checkbox').forEach(cb => {
        cb.dispatchEvent(new Event('change'));
    });
}

function bulkAction(action) {
    const selected = document.querySelectorAll('.request-checkbox:checked');
    if (selected.length === 0) {
        toastr.warning('Please select at least one request.');
        return;
    }

    const modal = new bootstrap.Modal(document.getElementById('bulkActionModal'));
    document.getElementById('bulkActionType').value = action;
    document.getElementById('bulkModalTitle').textContent = 
        action.charAt(0).toUpperCase() + action.slice(1) + ' Requests';
    document.getElementById('bulkModalMessage').textContent = 
        `Are you sure you want to ${action} ${selected.length} selected request(s)?`;
    document.getElementById('bulkRemarks').value = '';
    modal.show();
}

function processBulkAction() {
    const action = document.getElementById('bulkActionType').value;
    const remarks = document.getElementById('bulkRemarks').value;
    const selected = Array.from(document.querySelectorAll('.request-checkbox:checked')).map(cb => cb.value);

    if (selected.length === 0) {
        toastr.warning('No requests selected.');
        return;
    }

    showLoader();

    $.ajax({
        url: '{{ route("institute-admin.wfh-requests.bulk-update") }}',
        method: 'POST',
        data: {
            request_ids: selected,
            status: action,
            remarks: remarks,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            hideLoader();
            if (response.success) {
                toastr.success(response.message);
                $('#bulkActionModal').modal('hide');
                setTimeout(() => location.reload(), 1000);
            } else {
                toastr.error(response.message || 'Error processing bulk action');
            }
        },
        error: function(xhr) {
            hideLoader();
            toastr.error(xhr.responseJSON?.message || 'Error processing bulk action');
        }
    });
}

// ============================================
// INDIVIDUAL ACTIONS
// ============================================
function viewRequest(id) {
    window.location.href = '{{ url("institute-admin/wfh-requests") }}/' + id;
}

function approveRequest(id) {
    Swal.fire({
        title: 'Approve Request',
        text: 'Are you sure you want to approve this WFH request?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Approve',
        cancelButtonText: 'Cancel',
        input: 'textarea',
        inputPlaceholder: 'Add remarks (optional)...',
        inputAttributes: {
            'aria-label': 'Remarks'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            showLoader();
            $.ajax({
                url: '{{ url("institute-admin/wfh-requests") }}/' + id + '/update-status',
                method: 'POST',
                data: {
                    status: 'approved',
                    remarks: result.value || '',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    hideLoader();
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(() => location.reload(), 1000);
                    }
                },
                error: function(xhr) {
                    hideLoader();
                    toastr.error(xhr.responseJSON?.message || 'Error processing request');
                }
            });
        }
    });
}

function rejectRequest(id) {
    Swal.fire({
        title: 'Reject Request',
        text: 'Are you sure you want to reject this WFH request?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Reject',
        cancelButtonText: 'Cancel',
        input: 'textarea',
        inputPlaceholder: 'Please provide reason for rejection...',
        inputAttributes: {
            'aria-label': 'Rejection Reason',
            'required': false
        },
     
    }).then((result) => {
        if (result.isConfirmed) {
            showLoader();
            $.ajax({
                url: '{{ url("institute-admin/wfh-requests") }}/' + id + '/update-status',
                method: 'POST',
                data: {
                    status: 'rejected',
                    remarks: result.value,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    hideLoader();
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(() => location.reload(), 1000);
                    }
                },
                error: function(xhr) {
                    hideLoader();
                    toastr.error(xhr.responseJSON?.message || 'Error processing request');
                }
            });
        }
    });
}

function completeRequest(id) {
    Swal.fire({
        title: 'Complete Request',
        text: 'Mark this WFH request as completed?',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Complete',
        cancelButtonText: 'Cancel',
        input: 'textarea',
        inputPlaceholder: 'Add remarks (optional)...',
        inputAttributes: {
            'aria-label': 'Remarks'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            showLoader();
            $.ajax({
                url: '{{ url("institute-admin/wfh-requests") }}/' + id + '/update-status',
                method: 'POST',
                data: {
                    status: 'completed',
                    remarks: result.value || '',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    hideLoader();
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(() => location.reload(), 1000);
                    }
                },
                error: function(xhr) {
                    hideLoader();
                    toastr.error(xhr.responseJSON?.message || 'Error processing request');
                }
            });
        }
    });
}

// ============================================
// EXPORT
// ============================================
function exportRequests() {
    const filters = new URLSearchParams(window.location.search);
    window.location.href = '{{ route("institute-admin.wfh-requests.export") }}?' + filters.toString();
}

// ============================================
// LOADER HELPERS
// ============================================
function showLoader() {
    document.getElementById('pageLoader').style.display = 'flex';
}

function hideLoader() {
    document.getElementById('pageLoader').style.display = 'none';
}

// ============================================
// AUTO-SUBMIT FILTERS
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filterForm');
    let typingTimer;
    const delay = 800;

    // Auto-submit for text inputs
    const inputs = ['employee_name', 'employee_code'];
    inputs.forEach(name => {
        const input = document.querySelector(`input[name="${name}"]`);
        if (!input) return;

        input.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                showLoader();
                form.submit();
            }, delay);
        });
    });

    // Auto-submit for select and date inputs
    const selectors = ['request_status', 'date_from', 'date_to'];
    selectors.forEach(name => {
        const element = document.querySelector(`[name="${name}"]`);
        if (!element) return;

        element.addEventListener('change', function() {
            showLoader();
            form.submit();
        });
    });
});
</script>

@endsection