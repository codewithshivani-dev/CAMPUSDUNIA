@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<title>Fee Structure Management</title>
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
    --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
}

/* ERP Table Styles - Enhanced */
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
    padding: 15px 16px;
    font-weight: 600;
    color: white;
    text-align: left;
    font-size: 14px;
    border-bottom: none;
    cursor: pointer;
    user-select: none;
    transition: all 0.2s;
    position: relative;
    letter-spacing: 0.3px;
}

.erp-table th:hover {
    background: rgba(255, 255, 255, 0.1);
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
    color: rgba(255, 255, 255, 0.5);
    font-size: 12px;
    line-height: 1;
}

.sort-icon.active {
    color: white;
    text-shadow: 0 0 8px rgba(255, 255, 255, 0.5);
}

.erp-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
    font-size: 14px;
    vertical-align: middle;
}

.erp-table tbody tr {
    transition: all 0.3s;
}

.erp-table tbody tr:hover {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(67, 97, 238, 0.1);
}

.erp-table tbody tr:last-child td {
    border-bottom: none;
}

/* Filter container - Enhanced */
.filter-container {
    background: white;
    border-radius: 16px;
    padding: 20px 24px;
    margin-bottom: 20px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    position: relative;
    overflow: hidden;
}

.filter-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--primary-gradient);
}

.filter-form {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 16px;
}

.filter-group {
    position: relative;
    flex: 1;
    min-width: 200px;
}

.filter-group .bi {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary-color);
    z-index: 1;
    font-size: 16px;
}

.filter-group select,
.filter-group input {
    width: 100%;
    padding: 12px 12px 12px 40px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    background: #fff;
    transition: all 0.3s;
}

.filter-group select:focus,
.filter-group input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    transform: translateY(-2px);
}

.filter-group select:hover,
.filter-group input:hover {
    border-color: var(--secondary-color);
}

.filter-grid {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    flex: 1;
}

.filter-actions {
    display: flex;
    gap: 12px;
    align-items: center;
}

.btn-filter {
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    font-size: 14px;
    text-decoration: none;
}

.btn-filter-secondary:hover {
    text-decoration: none;
}

.btn-filter:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    text-decoration: none;
    color: white !important;
}

.btn-filter:active {
    transform: translateY(-1px);
}

.btn-filter-primary {
    background: var(--primary-gradient);
    color: white;
    position: relative;
    overflow: hidden;
}

.btn-filter-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn-filter-primary:hover::before {
    left: 100%;
}

.btn-filter-secondary {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}

.btn-filter-secondary:hover {
    background: #e2e8f0;
}

/* Page Header - Enhanced */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px 30px;
    background: var(--primary-gradient);
    border-radius: 16px;
    box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
}

.page-title {
    /*font-size: 28px;*/
    font-weight: 700;
    color: white;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

.page-title i {
    font-size: 32px;
    filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
    color: white;
}

/* Action Buttons - Enhanced */
.action-btn {
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    position: relative;
    overflow: hidden;
}

.action-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.action-btn:hover::before {
    width: 200px;
    height: 200px;
}

.action-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    text-decoration: none;
}

.action-btn:active {
    transform: translateY(-1px);
}

.action-btn-view {
    background: linear-gradient(135deg, #e0f2fe, #bae6fd);
    color: #0369a1;
}

.action-btn-edit {
    background: linear-gradient(135deg, #fef2c8, #fde68a);
    color: #92400e;
}

.action-btn-secondary {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    color: #475569;
}

.action-btn-view:hover {
    background: #bae6fd;
}

.action-btn-edit:hover {
    background: #fde68a;
}

.action-btn-secondary:hover {
    background: #e2e8f0;
}

.custom-gap {
    gap: 8px;
}

/* Status Badges - Enhanced */
.status-badge {
    padding: 6px 16px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
    transition: all 0.3s;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.status-badge:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
}

.status-active {
    background: var(--success-gradient);
    color: white;
    border: none;
}

.status-inactive {
    background: var(--danger-gradient);
    color: white;
    border: none;
}

/* Course Group Header - Enhanced */
.course-group-header {
    background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
    border-left: 4px solid var(--primary-color);
    font-weight: 600;
    color: #1e293b;
}

.course-group-header td {
    padding: 16px 16px;
    font-size: 15px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
}

/* Course Badge - Enhanced */
.course-badge {
    background: linear-gradient(135deg, #4361ee, #3a0ca3);
    color: white;
    padding: 4px 6px;
    border-radius: 30px;
    font-size: 10px;
    display: inline-block;
    font-weight: 500;
    box-shadow: 0 4px 10px rgba(67, 97, 238, 0.2);
    margin: 2px;
}

/* Fee Amount Styles - Enhanced */
.fee-amount {
    /*font-weight: 700;*/
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    /*font-size: 1.1rem;*/
}

/* Seats Info - Single line format matching Class Management */
.seats-info-single {
    display: flex;
    align-items: center;
    gap: 2px;
    /*flex-wrap: wrap;*/
    font-size: 12px;
}

.seat-item-single {
    display: flex;
    align-items: center;
    gap: 4px;
    background: #f8fafc;
    padding: 4px 10px;
    border-radius: 20px;
    transition: all 0.3s;
}

.seat-item-single:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}

.seat-icon-single {
    color: var(--primary-color);
    font-size: 12px;
}

.seat-label-single {
    font-weight: 600;
    color: #64748b;
}

.seat-value-single {
    font-weight: 700;
}

.seat-value-single.capacity {
    color: var(--primary-color);
}

.seat-value-single.occupied {
    color: #dc2626;
}

.seat-value-single.available {
    color: #10b981;
}

.progress {
    height: 5px;
    border-radius: 3px;
    background: #e2e8f0;
    overflow: hidden;
    margin-top: 6px;
}

.progress-bar {
    background: var(--success-gradient);
    transition: width 0.3s ease;
}

/* Empty State - Enhanced */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #64748b;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
}

.empty-state-icon {
    font-size: 64px;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 20px;
}

.empty-state h4 {
    color: #1e293b;
    margin-bottom: 10px;
}

/* Alert Messages - Enhanced */
.alert {
    border: none;
    border-radius: 12px;
    padding: 15px 20px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.alert-success {
    background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    border: 1px solid #86efac;
    color: #166534;
}

.alert-danger {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border: 1px solid #fca5a5;
    color: #991b1b;
}

/* Loading Animation */
.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

/* Modal Styles - Enhanced */
.modal-content {
    border: none;
    border-radius: 20px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    overflow: hidden;
}

.modal-header {
    background: var(--primary-gradient);
    border-bottom: none;
    padding: 20px 24px;
}

.modal-title {
    font-weight: 600;
    color: white;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 18px;
}

.modal-close-button {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: white;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s;
}

.modal-close-button:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
    color: white;
}

.modal-body {
    padding: 24px;
}

/* Details Row for Modal */
.details-row {
    padding: 10px 0;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
}

.details-label {
    font-weight: 600;
    color: #475569;
    min-width: 140px;
    font-size: 14px;
}

.details-value {
    color: #1e293b;
    font-weight: 500;
}

/* Fee Details Panel - Enhanced */
.fee-details-panel {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 10px;
}

.fee-detail-item {
    padding: 12px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 10px;
    margin-bottom: 10px;
    border-left: 4px solid #10b981;
    transition: all 0.3s;
}

.fee-detail-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.fee-detail-item.no-fee {
    border-left-color: #64748b;
    background: #f1f5f9;
}

/* Card in Modal */
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: all 0.3s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .filter-grid {
        flex-direction: column;
        gap: 12px;
    }

    .filter-group {
        min-width: 100%;
    }

    .filter-actions {
        width: 100%;
        /*justify-content: flex-end;*/
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
        padding: 20px;
    }

    .erp-table th,
    .erp-table td {
        padding: 10px 12px;
        font-size: 13px;
    }

    .action-btn {
        padding: 6px 10px;
        font-size: 11px;
    }

    .seats-info-single {
        gap: 8px;
    }

    .seat-item-single {
        padding: 3px 8px;
        font-size: 12px;
    }
}

/* Additional styles from Class Management */
.fw-semibold {
    font-weight: 600;
}

.text-primary {
    color: var(--primary-color) !important;
}

.text-success {
    color: #10b981 !important;
}

.text-danger {
    color: #dc2626 !important;
}

.text-muted {
    color: #64748b !important;
}

.bg-primary {
    background: var(--primary-gradient) !important;
}

.badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
}

.badge.bg-success {
    background: linear-gradient(135deg, #10b981, #059669) !important;
    color: white;
}

.badge.bg-secondary {
    background: linear-gradient(135deg, #94a3b8, #64748b) !important;
    color: white;
}

#pageLoader {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(255, 255, 255, 0.7);
    z-index: 99999;
    align-items: center;
    justify-content: center;
}

#pageLoader .spinner {
    width: 46px;
    height: 46px;
    border: 4px solid #e5e7eb;
    border-top-color: #2563eb;
    border-radius: 50%;
    animation: spin 0.9s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
.table-responsive{
    overflow-x: hidden;
}
</style>

<div id="pageLoader">
    <div class="spinner"></div>
</div>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h4 class="page-title">
            <i class="bi bi-cash-coin"></i>
            View Fee Structure
        </h4>
        <a href="{{ route('course.fee.form') }}" class="btn-filter btn-filter-primary">
            <i class="bi bi-plus-circle"></i>
            Create New
        </a>
    </div>

    <!-- Messages -->
    @if(session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle-fill"></i>{{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Filters -->
    <div class="filter-container">
        <form method="GET" action="{{ route('fee.structure.view') }}" id="filterForm" class="filter-form">
            <div class="filter-grid">

                <!-- Department -->
                <div class="filter-group">
                    <i class="bi bi-diagram-3"></i>
                    <input list="departmentList" class="filter-input" id="departmentInput"
                        placeholder="Search Department" autocomplete="off">

                    <datalist id="departmentList">
                        @foreach($filterData['departments'] as $id => $name)
                        <option value="{{ $name }}" data-id="{{ $id }}"></option>
                        @endforeach
                    </datalist>

                    <input type="hidden" name="department_id" id="department_id" value="{{ request('department_id') }}">
                </div>

                <!-- Course Type -->
                <div class="filter-group">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                    <input list="courseTypeList" class="filter-input" id="courseTypeInput" placeholder="Search Class">

                    <datalist id="courseTypeList">
                        @foreach($filterData['course_types'] as $type)
                        <option value="{{ $type }}"></option>
                        @endforeach
                    </datalist>

                    <input type="hidden" name="course_type" id="course_type" value="{{ request('course_type') }}">
                </div>

                <!-- Academic Year -->
                <div class="filter-group">
                    <i class="bi bi-calendar-range"></i>
                    <input list="academicYearList" class="filter-input" id="academicYearInput"
                        placeholder="Search Academic Year">

                    <datalist id="academicYearList">
                        @foreach($filterData['academic_years'] as $year)
                        <option value="{{ $year }}"></option>
                        @endforeach
                    </datalist>

                    <input type="hidden" name="academic_year" id="academic_year" value="{{ request('academic_year') }}">
                </div>

                <!-- Mode of Course -->
                <div class="filter-group d-none">
                    <i class="bi bi-laptop"></i>
                    <select name="mode_of_course" class="filter-input">
                        <option value="">All Modes</option>
                        <option value="Online" {{ request('mode_of_course') == 'Online' ? 'selected' : '' }}>Online
                        </option>
                        <option value="Offline" {{ request('mode_of_course') == 'Offline' ? 'selected' : '' }}>Offline
                        </option>
                        <option value="Hybrid" {{ request('mode_of_course') == 'Hybrid' ? 'selected' : '' }}>Hybrid
                        </option>
                    </select>
                </div>

                <!-- Product / Branch (kept hidden as per your original UI) -->
                <div class="filter-group d-none">
                    <i class="bi bi-diagram-3"></i>
                    <select name="product_id" class="filter-input">
                        <option value="">All Branches</option>
                        @foreach($filterData['products'] as $id => $product)
                        <option value="{{ $id }}" {{ request('product_id') == $id ? 'selected' : '' }}>
                            {{ $product }}
                        </option>
                        @endforeach
                    </select>
                </div>


            </div>

            <!-- Hidden sort inputs -->
            <input type="hidden" id="sortBy" name="sort_by" value="{{ request('sort_by','created_at') }}">
            <input type="hidden" id="sortOrder" name="sort_order" value="{{ request('sort_order','desc') }}">

            <div class="filter-actions">
                <a href="{{ route('fee.structure.view') }}" class="btn-filter btn-filter-secondary">
                    <i class="bi bi-x-circle"></i> Reset Filters
                </a>
            </div>

        </form>
    </div>

    @if($feeStructures->count() > 0)
    @php
    $groupedStructures = $feeStructures->groupBy(function($item) {
    return $item->course_type . '|' . $item->product_id;
    });
    @endphp

    <!-- Bulk Actions Container -->
    <div class="bulk-actions-container active d-none" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 fee structures selected</div>
        <div class="d-flex flex-wrap">
            <div class="dropdown me-2">
                <button class="bulk-action-btn download dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-download"></i>
                    Download
                </button>

                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="bulkAction('download','excel')">
                            <i class="bi bi-file-earmark-excel text-success me-2"></i>
                            Excel (.xlsx)
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="bulkAction('download','csv')">
                            <i class="bi bi-file-earmark-text text-primary me-2"></i>
                            CSV (.csv)
                        </a>
                    </li>
                </ul>
            </div>
            <button class="bulk-action-btn delete" onclick="bulkAction('delete')">
                <i class="bi bi-trash"></i>
                Bulk Delete
            </button>
            <button class="bulk-action-btn clear mr-0" onclick="clearSelection()">
                <i class="bi bi-x-lg"></i>
                Clear Selection
            </button>
        </div>
    </div>

<div>
    <div class="table-responsive custom-table-wrapper" id="tableWrapper">
        <table class="erp-table">
            <thead>
                <tr>
                    <th width="40" class="d-none">
                        <input type="checkbox" id="selectAll" class="select-checkbox">
                    </th>
                    <th class="sticky-main-2 sortable" onclick="sortTable('course_type')">
                        Class
                        <div class="sort-icons">
                            <i
                                class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'course_type' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i
                                class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'course_type' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('total_fee')">
                        Fees
                        <div class="sort-icons">
                            <i
                                class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'total_fee' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i
                                class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'total_fee' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('total_seats')">
                        Seats
                        <div class="sort-icons">
                            <i
                                class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'total_seats' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i
                                class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'total_seats' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('is_active')">
                        Status
                        <div class="sort-icons">
                            <i
                                class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'is_active' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i
                                class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'is_active' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('created_at')">
                        Created Date
                        <div class="sort-icons">
                            <i
                                class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'created_at' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i
                                class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'created_at' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('updated_at')">
                        Modified Date
                        <div class="sort-icons">
                            <i
                                class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'updated_at' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i
                                class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'updated_at' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupedStructures as $groupKey => $structures)
                @php
                $firstStructure = $structures->first();
                $courseType = $firstStructure->course_type;
                $subType = $firstStructure->sub_type;
                $totalInGroup = $structures->count();
                @endphp

                <!-- Course Group Header -->
                <tr class="course-group-header">
                    <td class="sticky-main-2" colspan="6">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong style="color: var(--primary-color);">
                                    <i class="bi bi-folder-fill me-2"></i>{{ $courseType }}
                                </strong>
                                <small class="text-muted ms-2">
                                    <i class="bi bi-diagram-3"></i> {{ $subType }}
                                </small>
                            </div>
                            <div>
                                <span class="course-badge">
                                    <i class="bi bi-calendar-range me-1"></i>{{ $totalInGroup }} Academic Year(s)
                                </span>
                            </div>
                        </div>
                    </td>
                </tr>

                <!-- Academic Year Rows -->
                @foreach($structures as $feeStructure)
                <tr>

                    <td class="d-none">
                        <input type="checkbox" class="fee-checkbox select-checkbox" value="{{ $feeStructure->id }}">
                    </td>
                    <!-- Course/Branch Info -->
                    <td class="sticky-main-2">
                        <div class="font-medium">{{ $feeStructure->course_type }}</div>
                        <div class="mb-1">
                            <span class="course-badge">
                                <i class="bi bi-calendar"></i>
                                {{ $feeStructure->academic_year }}
                            </span>
                        </div>
                    </td>

                    <!-- Fees (Enhanced) -->
                    <td>
                        <div class="fee-amount">
                           Total Fee: ₹{{ number_format($feeStructure->total_fee, 2) }}
                        </div>
                        <div class="text-sm text-gray-500">
                            @php
                            $courseFee = $feeStructure->course_fee;
                            $registrationFee = $feeStructure->registration_fee;
                            $hasCourseFee = is_array($courseFee) && !empty($courseFee['payments']);
                            $hasRegistrationFee = is_array($registrationFee) && !empty($registrationFee['payments']);
                            @endphp

                            @if($hasCourseFee || $hasRegistrationFee)
                            <div>
                                @if($hasCourseFee)
                                <span class="text-primary">Class: ₹{{
                                            array_sum(array_column($courseFee['payments'], 'amount')) 
                                        }}</span>
                                @endif

                                @if($hasRegistrationFee)
                                <br>
                                <span class="text-success">Registration: ₹{{
                                            array_sum(array_column($registrationFee['payments'], 'amount')) 
                                        }}</span>
                                @endif
                            </div>
                            @else
                            <span class="text-muted">No fees configured</span>
                            @endif
                        </div>
                    </td>

                    <!-- Seats - Single line format matching Class Management -->
                    <td>
                        <div class="seats-info-single">
                            <div class="seat-item-single">
                                <i class="bi bi-chair seat-icon-single"></i>
                                <span class="seat-label-single">Capacity:</span>
                                <span class="seat-value-single capacity">{{ $feeStructure->total_seats }}</span>
                            </div>

                            <div class="seat-item-single">
                                <i class="bi bi-people seat-icon-single"></i>
                                <span class="seat-label-single">Occupied:</span>
                                <span class="seat-value-single occupied">{{ $feeStructure->allocated_seats }}</span>
                            </div>

                            <div class="seat-item-single">
                                <i class="bi bi-clock seat-icon-single"></i>
                                <span class="seat-label-single">Available:</span>
                                <span class="seat-value-single available">{{ $feeStructure->available_seats }}</span>
                            </div>
                        </div>

                        @if($feeStructure->total_seats > 0)
                        @php
                        $allocationPercentage = ($feeStructure->allocated_seats / $feeStructure->total_seats) * 100;
                        @endphp
                        <div class="progress mt-2" style="height: 5px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $allocationPercentage }}%"
                                title="{{ number_format($allocationPercentage, 1) }}% occupied">
                            </div>
                        </div>
                        <small class="text-gray-500" style="font-size: 0.7rem;">
                            {{ number_format($allocationPercentage, 1) }}% occupied
                        </small>
                        @endif
                    </td>

                    <!-- Status -->
                    <td>
                        <span class="status-badge {{ $feeStructure->is_active ? 'status-active' : 'status-inactive' }}">
                            {{ $feeStructure->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>

                    <!-- Created Date -->
                    <td>
                        <div class="text-sm text-gray-500">
                            <i class="bi bi-clock"></i>
                            {{ \Carbon\Carbon::parse($feeStructure->created_at)->format('d M Y') }}
                        </div>
                    </td>
                    
                    <!-- Modified Date -->
                    <td>
                        <div class="text-sm text-gray-500">
                            <i class="bi bi-pencil-square"></i>
                            {{ $feeStructure->updated_at ? \Carbon\Carbon::parse($feeStructure->updated_at)->format('d M Y') : '-' }}
                        </div>
                    </td>

                    <!-- Actions (Enhanced) -->
                    <td class="text-center">
                        <div class="d-flex justify-content-center custom-gap">
                            <button class="action-btn action-btn-view d-block" data-bs-toggle="modal"
                                data-bs-target="#detailsModal{{ $feeStructure->id }}" title="View Details">
                                <div>
                                    <i class="bi bi-eye"></i>
                                </div>
                                <div>
                                    <span>View</span>
                                </div>
                            </button>
                            <a href="{{ route('course.fee.structure.edit', [
                                'fee_structure_id' => $feeStructure->id
                                ]) }}"
                                class="btn btn-sm btn-primary">

                                    <i class="fas fa-edit"></i> Edit
                            </a>

                            @if($feeStructure->batch_id)
                            <button class="action-btn action-btn-secondary d-block"
                                onclick="viewBatchDetails('{{ $feeStructure->batch_id }}')" title="View Batch">
                                <div>
                                    <i class="bi bi-list"></i>
                                </div>
                                <div>
                                    <span>Batch</span>
                                </div>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>

                <!-- Details Modal (Enhanced) -->
                <div class="modal fade" id="detailsModal{{ $feeStructure->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="bi bi-info-circle"></i>
                                    Fee Structure Details
                                </h5>
                                <button type="button" class="modal-close-button" data-bs-dismiss="modal">×</button>
                            </div>
                            <div class="modal-body">
                                <!-- Keep original modal body content with enhanced styling -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="details-row">
                                            <span class="details-label">Class:</span>
                                            <span class="details-value">{{ $feeStructure->course_type }}</span>
                                        </div>
                                        <div class="details-row">
                                            <span class="details-label">Branch:</span>
                                            <span class="details-value">{{ $feeStructure->sub_type }}</span>
                                        </div>
                                        <div class="details-row">
                                            <span class="details-label">Academic Year:</span>
                                            <span class="details-value">{{ $feeStructure->academic_year }}</span>
                                        </div>
                                        <div class="details-row">
                                            <span class="details-label">Batch Year:</span>
                                            <span class="details-value">{{ $feeStructure->batch_year }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="details-row">
                                            <span class="details-label">Batch:</span>
                                            <span class="details-value">{{ $feeStructure->batch }}</span>
                                        </div>

                                        <div class="details-row">
                                            <span class="details-label">Status:</span>
                                            <span class="details-value">
                                                <span
                                                    class="status-badge {{ $feeStructure->is_active ? 'status-active' : 'status-inactive' }}">
                                                    {{ $feeStructure->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </span>
                                        </div>
                                        <div class="details-row">
                                            <span class="details-label">Session:</span>
                                            <span class="details-value">{{ $feeStructure->session_range }}</span>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <!-- Seats Details -->
                                <h6 class="mb-3" style="color: var(--primary-color);"><i
                                        class="bi bi-chair me-2"></i>Seats Information</h6>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="details-row">
                                            <span class="details-label">Capacity:</span>
                                            <span class="details-value fw-bold">{{ $feeStructure->total_seats }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="details-row">
                                            <span class="details-label">Occupied:</span>
                                            <span
                                                class="details-value text-danger fw-bold">{{ $feeStructure->allocated_seats }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="details-row">
                                            <span class="details-label">Available:</span>
                                            <span
                                                class="details-value text-success fw-bold">{{ $feeStructure->available_seats }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sections -->
                                @if(count($feeStructure->sections) > 0)
                                <h6 class="mb-3" style="color: var(--primary-color);"><i
                                        class="bi bi-th-list me-2"></i>Sections</h6>
                                <div class="row">
                                    @foreach($feeStructure->sections as $section)
                                    <div class="col-md-4 mb-2">
                                        <div class="card p-2">
                                            <div class="d-flex justify-content-between">
                                                <strong>{{ $section['name'] ?? 'Unnamed' }}</strong>
                                                <span class="text-success">{{ $section['seats'] ?? 0 }} seats</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <hr>
                                @endif

                                <!-- Fee Details -->
                                <h6 class="mb-3" style="color: var(--primary-color);"><i
                                        class="bi bi-cash-coin me-2"></i>Fee Details</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="fee-details-panel">
                                            @if(is_array($feeStructure->course_fee) &&
                                            !empty($feeStructure->course_fee['payments']))
                                            @foreach($feeStructure->course_fee['payments'] as $index => $payment)
                                            <div class="fee-detail-item">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <strong>{{ isset($feeStructure->course_fee['duration']) && $feeStructure->course_fee['duration'] === 'One Time' ? 'Fee' : $feeStructure->course_fee['duration'].' Payment ' . ($index + 1) }}</strong>
                                                        @if(isset($payment['start_date']) && $payment['start_date'])
                                                        <div class="text-muted" style="font-size: 0.8rem;">
                                                            {{ $payment['start_date'] }} -
                                                            {{ $payment['end_date'] ?? 'N/A' }}
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="fee-amount">
                                                        ₹{{ number_format($payment['amount'] ?? 0, 2) }}
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach

                                            @if(isset($feeStructure->course_fee['late_fee']))
                                            <div class="fee-detail-item">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <strong class="text-warning">
                                                            <i class="bi bi-clock me-1"></i>Late Fee
                                                        </strong>
                                                        <div class="text-muted" style="font-size: 0.8rem;">
                                                            {{ ucfirst($feeStructure->course_fee['late_fee']['type'] ?? 'flat') }}
                                                        </div>
                                                    </div>
                                                    <div class="text-warning">
                                                        ₹{{ number_format($feeStructure->course_fee['late_fee']['amount'] ?? 0, 2) }}
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if(isset($feeStructure->course_fee['partial_payment']))
                                            <div class="fee-detail-item">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <strong class="text-info">
                                                            <i class="bi bi-percent me-1"></i>Partial Payment
                                                        </strong>
                                                        <div class="text-muted" style="font-size: 0.8rem;">
                                                            {{ ucfirst($feeStructure->course_fee['partial_payment']['type'] ?? 'percentage') }}
                                                        </div>
                                                    </div>
                                                    <div class="text-info">
                                                        ₹{{ number_format($feeStructure->course_fee['partial_payment']['amount'] ?? 0, 2) }}
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @else
                                            <div class="fee-detail-item no-fee">
                                                <div class="text-center text-muted">
                                                    No class fee configured
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="fee-details-panel">
                                            @if(is_array($feeStructure->registration_fee) &&
                                            !empty($feeStructure->registration_fee['payments']))
                                            @foreach($feeStructure->registration_fee['payments'] as $index => $payment)
                                            <div class="fee-detail-item">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <strong>Registration Fee</strong>
                                                        @if(isset($payment['start_date']) && $payment['start_date'])
                                                        <div class="text-muted" style="font-size: 0.8rem;">
                                                            {{ $payment['start_date'] }} -
                                                            {{ $payment['end_date'] ?? 'N/A' }}
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="fee-amount">
                                                        ₹{{ number_format($payment['amount'] ?? 0, 2) }}
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach

                                            @if(isset($feeStructure->registration_fee['late_fee']))
                                            <div class="fee-detail-item">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <strong class="text-warning">
                                                            <i class="bi bi-clock me-1"></i>Late Fee
                                                        </strong>
                                                        <div class="text-muted" style="font-size: 0.8rem;">
                                                            {{ ucfirst($feeStructure->registration_fee['late_fee']['type'] ?? 'flat') }}
                                                        </div>
                                                    </div>
                                                    <div class="text-warning">
                                                        ₹{{ number_format($feeStructure->registration_fee['late_fee']['amount'] ?? 0, 2) }}
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if(isset($feeStructure->registration_fee['partial_payment']))
                                            <div class="fee-detail-item">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <strong class="text-info">
                                                            <i class="bi bi-percent me-1"></i>Partial Payment
                                                        </strong>
                                                        <div class="text-muted" style="font-size: 0.8rem;">
                                                            {{ ucfirst($feeStructure->registration_fee['partial_payment']['type'] ?? 'percentage') }}
                                                        </div>
                                                    </div>
                                                    <div class="text-info">
                                                        ₹{{ number_format($feeStructure->registration_fee['partial_payment']['amount'] ?? 0, 2) }}
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @else
                                            <div class="fee-detail-item no-fee">
                                                <div class="text-center text-muted">
                                                    No registration fee configured
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Fee -->
                                <div class="text-end mt-4">
                                    <h4 class="text-success">
                                        <i class="bi bi-file-earmark-text me-2"></i>
                                        Total Fee: ₹{{ number_format($feeStructure->total_fee, 2) }}
                                    </h4>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <a href="{{ route('course.fee.structure.edit', [
                                    'fee_structure_id' => $feeStructure->id
                                    ]) }}"
                                    class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                @if(!$loop->last)
                <!-- Spacer between course groups -->
                <tr>
                    <td colspan="6" style="padding: 10px; background: #f8f9fa;"></td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
        <div class="mt-3 d-flex justify-content-center">
            {{ $feeStructures->links() }}
        </div>
    </div>
    
    <div class="table-scroll-top" id="tableScrollTop">
        <div class="table-scroll-inner"></div>
    </div>
</div>
    <!-- Batch Details Modal -->
    <div class="modal fade" id="batchDetailsModal" tabindex="-1" aria-labelledby="batchDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="batchDetailsModalLabel">
                        <i class="bi bi-list"></i>
                        Batch Details
                    </h5>
                    <button type="button" class="modal-close-button" data-bs-dismiss="modal"
                        aria-label="Close">×</button>
                </div>
                <div class="modal-body" id="batchDetailsContent">
                    Loading...
                </div>
            </div>
        </div>
    </div>

    @else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="bi bi-cash-coin"></i>
        </div>
        <h4>No Fee Structures Found</h4>
        <p class="text-muted">
            @if(count(array_filter($filters)) > 0)
            No fee structures match your current filters.
            @else
            No fee structures have been created yet.
            @endif
        </p>
        <a href="{{ route('course.fee.form') }}" class="btn-filter btn-filter-primary mt-3">
            <i class="bi bi-plus-circle"></i> Create New Fee Structure
        </a>
    </div>
    @endif
</div>

<script>
/* ==========================
   PAGE LOADER
========================== */
function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}

document.addEventListener("DOMContentLoaded", () => {

    function setupFilter(inputId, hiddenId, datalistId) {
        const input = document.getElementById(inputId);
        const hidden = document.getElementById(hiddenId);
        const datalist = document.getElementById(datalistId);
        const form = document.getElementById("filterForm");

        if (!input || !hidden || !datalist) return;

        // Restore selected value
        if (hidden.value) {
            const option = [...datalist.options].find(
                o => o.dataset.id == hidden.value || o.value == hidden.value
            );
            if (option) input.value = option.value;
        }

        // When selecting value
        input.addEventListener("change", () => {
            const option = [...datalist.options].find(
                o => o.value.trim() === input.value.trim()
            );

            if (option) {
                hidden.value = option.dataset.id ?? option.value;
                showLoader();
                form.submit();
            }
        });

        // When clearing value
        input.addEventListener("input", () => {
            if (input.value === "" && hidden.value !== "") {
                hidden.value = "";
                showLoader();
                form.submit();
            }
        });
    }

    setupFilter("departmentInput", "department_id", "departmentList");
    setupFilter("courseTypeInput", "course_type", "courseTypeList");
    setupFilter("academicYearInput", "academic_year", "academicYearList");

});

/* ==========================
   SORTING
========================== */
function sortTable(column) {
    document.getElementById('sortBy').value = column;
    document.getElementById('sortOrder').value =
        document.getElementById('sortOrder').value === 'asc' ? 'desc' : 'asc';

    showLoader();
    document.getElementById('filterForm').submit();
}

// Checkbox functionality
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const feeCheckboxes = document.querySelectorAll('.fee-checkbox');
    const selectedCountElement = document.getElementById('selectedCount');

    if (!selectAllCheckbox) return;

    // Select All functionality
    selectAllCheckbox.addEventListener('change', function() {
        feeCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectionCount();
    });

    // Individual checkbox change
    feeCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionCount);
    });

    function updateSelectionCount() {
        const selectedCount = document.querySelectorAll('.fee-checkbox:checked').length;
        selectedCountElement.textContent = selectedCount + ' fee structure(s) selected';

        // Update select all checkbox state
        selectAllCheckbox.checked = selectedCount > 0 && selectedCount === feeCheckboxes.length;
        selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < feeCheckboxes.length;
    }

    // Update count initially
    updateSelectionCount();
});

function clearSelection() {
    document.querySelectorAll('.fee-checkbox:checked').forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    document.getElementById('selectAll').indeterminate = false;
    document.getElementById('selectedCount').textContent = '0 fee structure(s) selected';
}


function bulkAction(action, format = null) {
    const selectedFees = Array.from(document.querySelectorAll('.fee-checkbox:checked'))
        .map(checkbox => checkbox.value);

    if (selectedFees.length === 0) {
        alert('Please select at least one fee structure.');
        return;
    }

    switch (action) {
        case 'download':

            if (!format) {
                alert("Please select a format");
                return;
            }

            const ids = selectedFees.join(',');

            const url = `/course/fee-structure/download?ids=${ids}&type=${format}`;

            window.location.href = url;

            break;

        case 'delete':
            if (confirm(
                    `Are you sure you want to delete ${selectedFees.length} fee structure(s)? This action cannot be undone.`
                )) {
                // Show loading state
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<span class="loading-spinner"></span> Deleting...';
                btn.disabled = true;

                // Simulate delete
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                    clearSelection();
                    alert(`${selectedFees.length} fee structures deleted successfully (demo)`);
                }, 1500);
            }
            break;
    }
}

function viewBatchDetails(batchId) {
    $.ajax({
        url: "{{ route('batch.details.view') }}",
        method: "GET",
        data: {
            batch_id: batchId
        },
        beforeSend: function() {
            $('#batchDetailsContent').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading batch details...</p>
                </div>
            `);
        },
        success: function(response) {
            $('#batchDetailsContent').html(response);
            $('#batchDetailsModal').modal('show');
        },
        error: function() {
            $('#batchDetailsContent').html(`
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Error loading batch details. Please try again.
                </div>
            `);
            $('#batchDetailsModal').modal('show');
        }
    });
}

// Sorting Functionality
function sortTable(column) {
    const currentSortBy = new URLSearchParams(window.location.search).get('sort_by') || 'created_at';
    const currentSortOrder = new URLSearchParams(window.location.search).get('sort_order') || 'desc';

    let newSortOrder = 'asc';

    if (currentSortBy === column) {
        newSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
    }

    // Build URL with new sort parameters
    const url = new URL(window.location.href);
    url.searchParams.set('sort_by', column);
    url.searchParams.set('sort_order', newSortOrder);

    // Submit the form
    window.location.href = url.toString();
}
</script>

@endsection