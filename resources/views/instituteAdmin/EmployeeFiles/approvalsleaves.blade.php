@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
    background: var(--primary-gradient);
    padding: 25px 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-header h2 {
    color: white;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
}

.page-header h2 i {
    background: rgba(255, 255, 255, 0.2);
    padding: 12px;
    border-radius: 12px;
    margin-right: 15px;
}

.legend-badges {
    display: flex;
    gap: 10px;
}

.legend-badges .badge {
    padding: 8px 15px;
    border-radius: 30px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 5px;
}

.badge-pending {
    background: linear-gradient(135deg, #ffc107, #ffb300);
    color: #212529;
}

.badge-approved {
    background: linear-gradient(135deg, #28a745, #1e7e34);
    color: white;
}

.badge-rejected {
    background: linear-gradient(135deg, #dc3545, #b02a37);
    color: white;
}

/* Filter Card */
.card.border-0.shadow-sm {
    border-radius: 15px !important;
    overflow: hidden;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.card.border-0.shadow-sm .card-body {
    padding: 25px;
}

.form-label.fw-semibold {
    color: #2c3e50;
    font-size: 0.95rem;
    margin-bottom: 8px;
    font-weight: 600;
}

.input-group-text {
    border-radius: 10px 0 0 10px;
    border: 2px solid #e0e0e0;
    border-right: none;
    font-size: 14px;
    background: white;
}

.form-control,
.form-select {
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 10px 15px;
    transition: all 0.3s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    outline: none;
}

.input-group .form-control {
    border-radius: 0 10px 10px 0 !important;
}

.btn-primary {
    background: var(--primary-gradient);
    border: none;
    border-radius: 10px;
    padding: 10px 25px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
}

.btn-outline-secondary {
    border: 2px solid #e0e0e0;
    color: #64748b;
    border-radius: 10px;
    padding: 10px 25px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    transform: translateY(-2px);
}

/* Tabs */
.nav-tabs {
    border-bottom: 2px solid #e0e0e0;
    margin-top: 20px;
}

.nav-tabs .nav-link {
    border: none;
    color: #64748b;
    font-weight: 600;
    padding: 12px 25px;
    border-radius: 10px 10px 0 0;
    transition: all 0.3s ease;
    margin-right: 5px;
}

.nav-tabs .nav-link:hover {
    color: #4361ee;
    background: #f8fafc;
}

.nav-tabs .nav-link.active {
    color: #4361ee;
    background: transparent;
    border-bottom: 3px solid #4361ee;
    font-weight: 700;
}

.nav-tabs .nav-link .badge {
    margin-left: 8px;
    padding: 4px 8px;
    border-radius: 20px;
}

/* Modern Table */
/*.table-container {*/
/*    background: white;*/
/*    border-radius: 15px;*/
    /*overflow: auto;*/
/*    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);*/
/*    border: 1px solid #eef2f6;*/
/*}*/

/*.modern-table {*/
/*    width: 100%;*/
/*    border-collapse: collapse;*/
/*    min-width: 1000px;*/
/*}*/

/*.modern-table thead {*/
/*    background: linear-gradient(135deg, #f8f9fc, #f1f4f9);*/
/*}*/

/*.modern-table th {*/
/*    padding: 16px 20px;*/
/*    text-align: left;*/
/*    font-size: 13px;*/
/*    font-weight: 600;*/
/*    color: #2c3e50;*/
/*    border-bottom: 2px solid #e9ecef;*/
/*    text-transform: uppercase;*/
/*    letter-spacing: 0.5px;*/
/*    white-space: nowrap;*/
/*}*/

/*.modern-table td {*/
/*    padding: 16px 20px;*/
/*    vertical-align: middle;*/
/*    border-bottom: 1px solid #eef2f6;*/
/*    font-size: 14px;*/
/*    color: #4a5568;*/
/*}*/

/*.modern-table tbody tr {*/
/*    transition: all 0.3s ease;*/
/*}*/

/*.modern-table tbody tr:hover {*/
/*    background: #f8fafc;*/
/*}*/

        /* ERP Table Styles */
        .erp-table {
            width: 100%;
            background: #fff;
            border-collapse: collapse;
        }

        .erp-table thead {
            background: var(--primary-gradient);
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .erp-table th {
            padding: 15px 8px;
            font-weight: 600;
            color: white;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            cursor: pointer;
            user-select: none;
            transition: background-color 0.2s;
            position: relative;
        }

        .erp-table th h6 {
            color: rgba(255,255,255,0.9);
            font-size: 11px;
            margin: 2px 0 0;
        }

        .erp-table th:hover {
            background-color: rgba(255,255,255,0.1);
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
            color: rgba(255,255,255,0.5);
            font-size: 12px;
            line-height: 1;
        }

        .sort-icon.active {
            color: white;
        }

        .erp-table td {
            padding: 15px 8px;
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

/* Employee Cell */
.employee-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.employee-avatar {
    width: 40px;
    height: 40px;
    background: var(--primary-gradient);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 14px;
    flex-shrink: 0;
}

.employee-info h6 {
    font-size: 14px;
    font-weight: 600;
    margin: 0 0 2px 0;
    color: #2c3e50;
}

.employee-info p {
    font-size: 11px;
    color: #7f8c8d;
    margin: 0;
}

.employee-info p i {
    font-size: 10px;
}

/* Department Badge */
.department-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
    background: #e0e7ff;
    color: #4338ca;
}

/* Leave Type Badge */
.leave-type-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
}

.leave-type-sick {
    background: #fee2e2;
    color: #dc2626;
}

.leave-type-casual {
    background: #dbeafe;
    color: #2563eb;
}

.leave-type-earned {
    background: #dcfce7;
    color: #16a34a;
}

.leave-type-unpaid {
    background: #fef3c7;
    color: #d97706;
}

.leave-type-maternity {
    background: #fce7f3;
    color: #db2777;
}

.leave-type-half_days {
    background: #fef3c7;
    color: #d97706;
}

.leave-type-short_leave {
    background: #e0e7ff;
    color: #4338ca;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-pending {
    background: #fff3e0;
    color: #f39c12;
}

.status-approved {
    background: #e8f8f5;
    color: #27ae60;
}

.status-rejected {
    background: #fdeded;
    color: #e74c3c;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-action {
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
    border: none;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-approve {
    background: #27ae60;
    color: white;
}

.btn-approve:hover {
    background: #219a52;
    transform: translateY(-2px);
}

.btn-reject {
    background: #e74c3c;
    color: white;
}

.btn-reject:hover {
    background: #c0392b;
    transform: translateY(-2px);
}

/* Document Link */
.document-link {
    color: #3498db;
    cursor: pointer;
    text-decoration: none;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.document-link:hover {
    text-decoration: underline;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 15px;
}

.empty-state i {
    font-size: 64px;
    color: #cbd5e1;
    margin-bottom: 20px;
}

.empty-state h5 {
    font-size: 18px;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 8px;
}

.empty-state p {
    color: #94a3b8;
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #ccc;
    border-top-color: #4361ee;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Active Filters */
.active-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #e2e8f0;
}

.filter-tag {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 5px 12px;
    background: #f1f5f9;
    border-radius: 20px;
    font-size: 12px;
    color: #475569;
}

.filter-tag i {
    cursor: pointer;
}

.filter-tag i:hover {
    color: #ef4444;
}

/* Text Truncate */
.text-truncate-custom {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .legend-badges {
        justify-content: center;
    }

    .table-container {
        border-radius: 10px;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn-action {
        width: 100%;
        text-align: center;
    }
}

.table-responsive{
    overflow-x: hidden;
}
</style>

<div class="container-fluid">
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="spinner"></div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Header with Gradient -->
    <div class="page-header">
        <h2>
            <i class="bi bi-clipboard-check"></i>
            Leave Requests
        </h2>
        <div class="legend-badges">
            <span class="badge badge-pending">
                <i class="bi bi-clock me-1"></i>Pending
            </span>
            <span class="badge badge-approved">
                <i class="bi bi-check-circle me-1"></i>Approved
            </span>
            <span class="badge badge-rejected">
                <i class="bi bi-x-circle me-1"></i>Rejected
            </span>
        </div>
    </div>

    <!-- Search + Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('leaves.approvals') }}" id="filterForm">
                <div class="row g-3">
                    <!-- Employee Search - Keep as is -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Search Employee</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="employee_name" id="employeeName" class="form-control filter-input"
                                placeholder="Name or Code" value="{{ request('employee_name') }}">
                            <input type="hidden" name="search" id="employeeId" value="{{ request('search') }}">
                        </div>
                        <!-- Make sure datalist has options -->
                        <datalist id="employeeList">
                            @foreach($employees as $emp)
                            <option value="{{ $emp->name }}" data-id="{{ $emp->employee_id }}"
                                data-code="{{ $emp->employee_code }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Department Filter -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Department</label>
                        <select name="department_id" id="departmentFilter" class="form-select filter-input">
                            <option value="">All Departments</option>
                            @foreach($departments ?? [] as $dept)
                            <option value="{{ $dept->department_id }}"
                                {{ request('department_id') == $dept->department_id ? 'selected' : '' }}>
                                {{ $dept->department }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Leave Type -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Leave Type</label>
                        <select name="leave_type" id="leaveTypeFilter" class="form-select filter-input">
                            <option value="">All Leave Types</option>
                            @if(!empty($leaveTypes) && count($leaveTypes) > 0)
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type }}" {{ request('leave_type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled>No leave types available</option>
                            @endif
                        </select>
                    </div>

                    <!-- Date Range Filter -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Date Range</label>
                        <select name="date_filter" id="dateFilter" class="form-select filter-input">
                            <option value="">All Time</option>
                            <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today
                            </option>
                            <option value="tomorrow" {{ request('date_filter') == 'tomorrow' ? 'selected' : '' }}>
                                Tomorrow</option>
                            <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>This Week
                            </option>
                            <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>This Month
                            </option>
                            <option value="year" {{ request('date_filter') == 'year' ? 'selected' : '' }}>This Year
                            </option>
                            <option value="custom" {{ request('date_filter') == 'custom' ? 'selected' : '' }}>Custom
                                Range</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="d-flex justify-content-end gap-2 mt-2">
                        <a href="{{ route('leaves.approvals') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset All Filters
                        </a>
                    </div>
                </div>
                <!-- Custom Date Range -->
                <div class="row mt-3" id="customDateRange"
                    style="display: {{ request('date_filter') == 'custom' ? 'flex' : 'none' }};">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">From Date</label>
                        <input type="date" name="from_date" id="fromDate" class="form-control filter-input"
                            value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">To Date</label>
                        <input type="date" name="to_date" id="toDate" class="form-control filter-input"
                            value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" id="applyCustomRange" class="btn btn-primary w-100">
                            <i class="bi bi-calendar-check"></i> Apply
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="approvalTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{ $activeTab == 'pending' ? 'active' : '' }}" data-tab="pending" href="#">
                <i class="bi bi-clock me-1"></i>Pending
                <span class="badge bg-warning">{{ $pending->count() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeTab == 'approved' ? 'active' : '' }}" data-tab="approved" href="#">
                <i class="bi bi-check-circle me-1"></i>Approved
                <span class="badge bg-success">{{ $approved->count() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeTab == 'rejected' ? 'active' : '' }}" data-tab="rejected" href="#">
                <i class="bi bi-x-circle me-1"></i>Rejected
                <span class="badge bg-danger">{{ $rejected->count() }}</span>
            </a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3">
        <!-- Pending Tab -->
        <div class="tab-pane fade show active" id="pending-tab-pane">
            @if($pending->count())
            <div class="table-container table-responsive custom-table-wrapper" id="tableWrapper">
                <table class="erp-table modern-table">
                    <thead>
                        <tr>
                            <th class="sticky-main-2 sortable">Employee</th>
                            <th class="sortable">Department</th>
                            <th class="sortable">Leave Type</th>
                            <th class="sortable">Duration</th>
                            <th class="sortable">Date Range</th>
                            <th class="sortable">Reason</th>
                            <th class="sortable">Document</th>
                            <th class="sortable">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pending as $approval)
                        <tr>
                            <td class="sticky-main-2">
                                <div class="employee-cell">
                                    <div class="employee-avatar">
                                        {{ strtoupper(substr($approval->employee_full_name ?? $approval->employee_name ?? 'NA', 0, 2)) }}
                                    </div>
                                    <div class="employee-info">
                                        <h6>{{ $approval->employee_full_name ?? $approval->employee_name ?? 'N/A' }}
                                        </h6>
                                        <p>
                                            <i class="bi bi-qr-code"></i> Code: {{ $approval->employee_code ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="department-badge">
                                    <i class="bi bi-building"></i> {{ $approval->department_name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="leave-type-badge leave-type-{{ strtolower($approval->leave_type) }}">
                                    {{ ucfirst($approval->leave_type) }}
                                </span>
                            </td>
                            <td>{{ $approval->total_days }} day(s)</td>
                            <td>
                                {{ \Carbon\Carbon::parse($approval->start_date)->format('d M, Y') }}
                                @if($approval->end_date && $approval->end_date != $approval->start_date)
                                → {{ \Carbon\Carbon::parse($approval->end_date)->format('d M, Y') }}
                                @endif
                            </td>
                            <td class="text-truncate-custom" title="{{ $approval->reason ?? 'No reason' }}">
                                {{ Str::limit($approval->reason ?? 'No reason', 40) }}
                            </td>
                            <td>
                                @if($approval->leave_document)
                                <a href="javascript:void(0)" class="document-link view-document"
                                    data-doc="{{ route('image', ['path' => $approval->leave_document]) }}">
                                    <i class="bi bi-paperclip"></i> View
                                </a>
                                @else
                                <span class="text-muted">No file</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <form action="{{ route('leaves.approval.update', $approval->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn-action btn-approve">
                                            <i class="bi bi-check-circle"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('leaves.approval.update', $approval->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="btn-action btn-reject">
                                            <i class="bi bi-x-circle"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h5>No Pending Approvals</h5>
                <p>You don't have any pending leave requests to approve.</p>
            </div>
            @endif
        </div>

        <!-- Approved Tab -->
        <div class="tab-pane fade" id="approved-tab-pane">
            @if($approved->count())
            <div class="table-container table-responsive custom-table-wrapper" id="tableWrapper">
                <table class="erp-table modern-table">
                    <thead>
                        <tr>
                            <th class="sticky-main-2 sortable">Employee</th>
                            <th class="sortable">Department</th>
                            <th class="sortable">Leave Type</th>
                            <th class="sortable">Duration</th>
                            <th class="sortable">Date Range</th>
                            <th class="sortable">Approved By</th>
                            <th class="sortable">Approved On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($approved as $approval)
                        <tr>
                            <td class="sticky-main-2">
                                <div class="employee-cell">
                                    <div class="employee-avatar">
                                        {{ strtoupper(substr($approval->employee_full_name ?? $approval->employee_name ?? 'NA', 0, 2)) }}
                                    </div>
                                    <div class="employee-info">
                                        <h6>{{ $approval->employee_full_name ?? $approval->employee_name ?? 'N/A' }}
                                        </h6>
                                        <p>
                                            <i class="bi bi-qr-code"></i> Code: {{ $approval->employee_code ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="department-badge">
                                    <i class="bi bi-building"></i> {{ $approval->department_name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="leave-type-badge leave-type-{{ strtolower($approval->leave_type) }}">
                                    {{ ucfirst($approval->leave_type) }}
                                </span>
                            </td>
                            <td>{{ $approval->total_days }} day(s)</td>
                            <td>
                                {{ \Carbon\Carbon::parse($approval->start_date)->format('d M, Y') }}
                                @if($approval->end_date && $approval->end_date != $approval->start_date)
                                → {{ \Carbon\Carbon::parse($approval->end_date)->format('d M, Y') }}
                                @endif
                            </td>
                            <td>{{ $approval->approver_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($approval->approved_date)->format('d M, Y h:i A') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="bi bi-check-circle"></i>
                <h5>No Approved Leaves</h5>
                <p>No leaves have been approved yet.</p>
            </div>
            @endif
        </div>

        <!-- Rejected Tab -->
        <div class="tab-pane fade" id="rejected-tab-pane">
            @if($rejected->count())
            <div class="table-container table-responsive custom-table-wrapper" id="tableWrapper">
                <table class="erp-table modern-table">
                    <thead>
                        <tr>
                            <th class="sticky-main-2 sortable">Employee</th>
                            <th class="sortable">Department</th>
                            <th class="sortable">Leave Type</th>
                            <th class="sortable">Duration</th>
                            <th class="sortable">Date Range</th>
                            <th class="sortable">Rejected By</th>
                            <th class="sortable">Rejected On</th>
                            <th class="sortable">Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rejected as $approval)
                        <tr>
                            <td class="sticky-main-2">
                                <div class="employee-cell">
                                    <div class="employee-avatar">
                                        {{ strtoupper(substr($approval->employee_full_name ?? $approval->employee_name ?? 'NA', 0, 2)) }}
                                    </div>
                                    <div class="employee-info">
                                        <h6>{{ $approval->employee_full_name ?? $approval->employee_name ?? 'N/A' }}
                                        </h6>
                                        <p>
                                            <i class="bi bi-qr-code"></i> Code: {{ $approval->employee_code ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="department-badge">
                                    <i class="bi bi-building"></i> {{ $approval->department_name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="leave-type-badge leave-type-{{ strtolower($approval->leave_type) }}">
                                    {{ ucfirst($approval->leave_type) }}
                                </span>
                            </td>
                            <td>{{ $approval->total_days }} day(s)</td>
                            <td>
                                {{ \Carbon\Carbon::parse($approval->start_date)->format('d M, Y') }}
                                @if($approval->end_date && $approval->end_date != $approval->start_date)
                                → {{ \Carbon\Carbon::parse($approval->end_date)->format('d M, Y') }}
                                @endif
                            </td>
                            <td>{{ $approval->approver_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($approval->approved_date)->format('d M, Y h:i A') }}</td>
                            <td class="text-truncate-custom" title="{{ $approval->comments ?? 'No reason' }}">
                                {{ Str::limit($approval->comments ?? 'No reason', 40) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="bi bi-x-circle"></i>
                <h5>No Rejected Leaves</h5>
                <p>No leaves have been rejected yet.</p>
            </div>
            @endif
        </div>
        
        <!-- Floating Horizontal Scrollbar -->
        <div class="table-scroll-top" id="tableScrollTop">
            <div class="table-scroll-inner"></div>
        </div>
    </div>
</div>

<!-- Document Modal -->
<div class="modal fade" id="documentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-file-earmark-text me-2"></i>Leave Document
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="min-height: 400px;">
                <div id="docLoader" class="text-center py-5">
                    <div class="spinner" style="width: 40px; height: 40px;"></div>
                    <p class="mt-2">Loading document...</p>
                </div>
                <img id="docImage" src="" style="display: none; max-width: 100%;" class="mx-auto d-block">
                <iframe id="docFrame" src="" style="display: none; width: 100%; height: 500px; border: none;"></iframe>
                <div id="docUnsupported" class="text-center py-5" style="display: none;">
                    <i class="bi bi-file-earmark-x" style="font-size: 48px; color: #cbd5e1;"></i>
                    <p class="mt-2">Preview not available for this file type</p>
                    <a href="#" id="downloadDoc" class="btn btn-primary" download>Download File</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function showLoading() {
    const loader = document.getElementById('loadingOverlay');
    if (loader) loader.style.display = 'flex';
}

function hideLoading() {
    const loader = document.getElementById('loadingOverlay');
    if (loader) loader.style.display = 'none';
}

function removeFilter(param, secondParam = null) {
    const url = new URL(window.location.href);
    url.searchParams.delete(param);
    if (secondParam) url.searchParams.delete(secondParam);
    showLoading();
    window.location.href = url.toString();
}

function removeCustomDateRange() {
    const url = new URL(window.location.href);
    url.searchParams.delete('from_date');
    url.searchParams.delete('to_date');
    url.searchParams.delete('date_filter');
    showLoading();
    window.location.href = url.toString();
}

document.addEventListener("DOMContentLoaded", function() {

    // ========== CUSTOM DATE RANGE HANDLING ==========
    const dateFilter = document.getElementById('dateFilter');
    const customDateRange = document.getElementById('customDateRange');
    const applyCustomRange = document.getElementById('applyCustomRange');
    const fromDate = document.getElementById('fromDate');
    const toDate = document.getElementById('toDate');

    // Show/hide custom date range based on selection
    if (dateFilter) {
        // Set initial visibility
        if (dateFilter.value === 'custom') {
            if (customDateRange) customDateRange.style.display = 'flex';
        } else {
            if (customDateRange) customDateRange.style.display = 'none';
        }

        // Handle change event
        dateFilter.addEventListener('change', function() {
            if (this.value === 'custom') {
                if (customDateRange) customDateRange.style.display = 'flex';
            } else {
                if (customDateRange) customDateRange.style.display = 'none';
                // Auto-submit when non-custom date filter is selected
                showLoading();
                document.getElementById('filterForm').submit();
            }
        });
    }

    // Apply custom date range
    if (applyCustomRange) {
        applyCustomRange.addEventListener('click', function() {
            if (!fromDate.value || !toDate.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Dates',
                    text: 'Please select both From Date and To Date',
                    confirmButtonColor: '#4361ee'
                });
                return;
            }

            if (new Date(fromDate.value) > new Date(toDate.value)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Date Range',
                    text: 'From Date cannot be greater than To Date',
                    confirmButtonColor: '#4361ee'
                });
                return;
            }

            showLoading();
            document.getElementById('filterForm').submit();
        });
    }

    // ========== EMPLOYEE SEARCH WITH DATALIST ==========
    const employeeName = document.getElementById('employeeName');
    const employeeId = document.getElementById('employeeId');
    const employeeList = document.getElementById('employeeList');
    let employeeOptions = [];

    // Store employee options for lookup
    if (employeeList) {
        employeeOptions = Array.from(employeeList.querySelectorAll('option')).map(opt => ({
            name: opt.value,
            id: opt.getAttribute('data-id')
        }));
    }

    // Set employee name from ID on page load (for preserving filter state)
    if (employeeId && employeeId.value && employeeName) {
        const matchingOpt = employeeOptions.find(opt => opt.id === employeeId.value);
        if (matchingOpt) {
            employeeName.value = matchingOpt.name;
        }
    }

    // Handle employee name change to update hidden ID
    if (employeeName && employeeId) {
        employeeName.addEventListener('change', function() {
            const selectedName = this.value;
            const matchingOpt = employeeOptions.find(opt => opt.name === selectedName);
            if (matchingOpt) {
                employeeId.value = matchingOpt.id;
            } else {
                employeeId.value = '';
            }
        });
    }

    // ========== AUTO-SUBMIT FOR ALL FILTERS ==========
    const filterForm = document.getElementById('filterForm');
    if (!filterForm) return;

    let debounceTimer;

    function submitForm() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            showLoading();
            filterForm.submit();
        }, 500);
    }

    // Get all filter inputs
    const filterInputs = document.querySelectorAll(
        '.filter-input, ' +
        'select[name="department_id"], ' +
        'select[name="leave_type"], ' +
        'select[name="date_filter"], ' +
        'input[name="employee_name"], ' +
        'input[name="from_date"], ' +
        'input[name="to_date"]'
    );

    // Add event listeners to all filter inputs
    filterInputs.forEach(input => {
        if (input.tagName === 'SELECT') {
            input.addEventListener('change', submitForm);
        } else if (input.type === 'date') {
            input.addEventListener('change', submitForm);
        } else if (input.type === 'text') {
            input.addEventListener('keyup', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    // For employee search, update hidden ID first
                    if (input.id === 'employeeName' && employeeId) {
                        const selectedName = employeeName.value;
                        const matchingOpt = employeeOptions.find(opt => opt.name ===
                            selectedName);
                        employeeId.value = matchingOpt ? matchingOpt.id : '';
                    }
                    showLoading();
                    filterForm.submit();
                }, 800);
            });
        }
    });

    // ========== TAB SWITCHING ==========
    const tabs = document.querySelectorAll('.nav-link[data-tab]');
    const panes = {
        pending: document.getElementById('pending-tab-pane'),
        approved: document.getElementById('approved-tab-pane'),
        rejected: document.getElementById('rejected-tab-pane')
    };

    function switchTab(tabName) {
        // Hide all panes
        Object.keys(panes).forEach(key => {
            if (panes[key]) {
                panes[key].classList.remove('show', 'active');
            }
        });

        // Show selected pane
        if (panes[tabName]) {
            panes[tabName].classList.add('show', 'active');
        }

        // Update active tab styling
        tabs.forEach(tab => {
            tab.classList.remove('active');
            if (tab.getAttribute('data-tab') === tabName) {
                tab.classList.add('active');
            }
        });

        // Update URL without page reload
        const url = new URL(window.location.href);
        url.searchParams.set('tab', tabName);
        window.history.pushState({}, '', url);
        
        // Initialize scrollbar on first page load
        setTimeout(() => {
            updateFloatingScrollbar();
        }, 200);
        
        // Update scrollbar on window resize
        window.addEventListener('resize', function() {
            updateFloatingScrollbar();
        });
    }
    
    function updateFloatingScrollbar() {
    
        const activePane = document.querySelector('.tab-pane.show.active');
        if (!activePane) return;
    
        const tableWrapper = activePane.querySelector('.custom-table-wrapper');
        if (!tableWrapper) return;
    
        const table = tableWrapper.querySelector('table');
        const topScroll = document.getElementById('tableScrollTop');
        const scrollInner = topScroll.querySelector('.table-scroll-inner');
    
        scrollInner.style.width = table.scrollWidth + 'px';
    
        topScroll.onscroll = () => {
            tableWrapper.scrollLeft = topScroll.scrollLeft;
        };
    
        tableWrapper.onscroll = () => {
            topScroll.scrollLeft = tableWrapper.scrollLeft;
        };
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            const tabName = this.getAttribute('data-tab');
            switchTab(tabName);
        });
    });

    // ========== APPROVAL FORM CONFIRMATION ==========
    const approvalForms = document.querySelectorAll('form[action*="approval"]');
    approvalForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const action = this.querySelector('input[name="action"]')?.value;
            if (!action) return;

            const actionText = action === 'approve' ? 'approve' : 'reject';
            const confirmMessage = `Are you sure you want to ${actionText} this leave request?`;

            if (!confirm(confirmMessage)) {
                e.preventDefault();
                return false;
            }
            showLoading();
        });
    });

    // ========== DOCUMENT VIEWER ==========
    const viewDocumentBtns = document.querySelectorAll('.view-document');
    const docLoader = document.getElementById('docLoader');
    const docImage = document.getElementById('docImage');
    const docFrame = document.getElementById('docFrame');
    const docUnsupported = document.getElementById('docUnsupported');
    const downloadDoc = document.getElementById('downloadDoc');

    function resetDocumentViewer() {
        if (docLoader) docLoader.style.display = 'block';
        if (docImage) docImage.style.display = 'none';
        if (docFrame) docFrame.style.display = 'none';
        if (docUnsupported) docUnsupported.style.display = 'none';
        if (docImage) docImage.src = '';
        if (docFrame) docFrame.src = '';
    }

    function handleImagePreview(url) {
        if (!docImage) return;
        docImage.onload = function() {
            if (docLoader) docLoader.style.display = 'none';
            docImage.style.display = 'block';
        };
        docImage.onerror = function() {
            if (docLoader) docLoader.style.display = 'none';
            if (docUnsupported) docUnsupported.style.display = 'block';
        };
        docImage.src = url;
    }

    function handlePdfPreview(url) {
        if (!docFrame) return;
        const viewerUrl = `https://docs.google.com/gview?url=${encodeURIComponent(url)}&embedded=true`;
        docFrame.onload = function() {
            if (docLoader) docLoader.style.display = 'none';
            docFrame.style.display = 'block';
        };
        docFrame.onerror = function() {
            // Fallback: try direct PDF
            docFrame.src = url;
        };
        docFrame.src = viewerUrl;
    }

    function handleOfficePreview(url) {
        if (!docFrame) return;
        const viewerUrl = `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(url)}`;
        docFrame.onload = function() {
            if (docLoader) docLoader.style.display = 'none';
            docFrame.style.display = 'block';
        };
        docFrame.onerror = function() {
            if (docLoader) docLoader.style.display = 'none';
            if (docUnsupported) docUnsupported.style.display = 'block';
        };
        docFrame.src = viewerUrl;
    }

    function handleUnsupportedFile() {
        if (docLoader) docLoader.style.display = 'none';
        if (docUnsupported) docUnsupported.style.display = 'block';
    }

    viewDocumentBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const docUrl = this.getAttribute('data-doc');
            if (!docUrl) return;

            const fileName = docUrl.split('/').pop();
            const ext = fileName.split('.').pop().toLowerCase();

            // Reset viewer
            resetDocumentViewer();

            // Set download link
            if (downloadDoc) {
                downloadDoc.href = docUrl;
            }

            // Show modal
            const modalElement = document.getElementById('documentModal');
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            }

            // Handle different file types
            if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(ext)) {
                handleImagePreview(docUrl);
            } else if (ext === 'pdf') {
                handlePdfPreview(docUrl);
            } else if (['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'].includes(ext)) {
                handleOfficePreview(docUrl);
            } else {
                handleUnsupportedFile();
            }
        });
    });

    // Reset document viewer when modal is closed
    const documentModal = document.getElementById('documentModal');
    if (documentModal) {
        documentModal.addEventListener('hidden.bs.modal', function() {
            resetDocumentViewer();
        });
    }

    // ========== AUTO-DISMISS ALERTS ==========
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => {
                if (alert.parentNode) alert.remove();
            }, 500);
        });
    }, 5000);

    // ========== INITIAL LOAD - RESTORE TAB FROM URL ==========
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    if (tabParam && panes[tabParam]) {
        switchTab(tabParam);
    }
});
</script>
@endsection