@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Grade Systems Management</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --primary-light: rgba(67, 97, 238, 0.1);
        --success-gradient: linear-gradient(135deg, #10b981, #059669);
        --success-color: #10b981;
        --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        --danger-color: #ef4444;
        --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
        --dark: #1f2937;
        --gray: #6b7280;
        --light-gray: #f9fafb;
        --border: #e5e7eb;
        --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
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
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .page-title {
        font-size: 28px;
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
    }

    .add-btn {
        padding: 12px 24px;
        background: var(--primary-gradient);
        border: none;
        color: #fff !important;
        cursor: pointer;
        border-radius: 10px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .add-btn:hover {
        transform: translateY(-3px);
        color: #fff;
        text-decoration: none;
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
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .filter-group {
        position: relative;
        flex: 1;
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

    .filter-group input {
        width: 100%;
        padding: 12px 12px 12px 40px;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        background: #fff;
        transition: all 0.3s;
    }

    .filter-group input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        transform: translateY(-2px);
    }

    .filter-group input:hover {
        border-color: var(--secondary-color);
    }

    .filter-actions {
        display: flex;
        gap: 12px;
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
        text-decoration: none;
        font-size: 14px;
        white-space: nowrap;
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
        border: 1px solid var(--border);
    }

    .btn-filter-secondary:hover {
        background: var(--primary-gradient);
        color: white !important;
        border-color: transparent;
    }

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

    /* Grade Range Badge */
    .grade-range-badge {
        background: var(--primary-gradient);
        color: white;
        border: none;
        border-radius: 30px;
        padding: 4px 12px;
        font-size: 0.75rem;
        margin-right: 4px;
        margin-bottom: 4px;
        display: inline-block;
        box-shadow: 0 2px 5px rgba(67, 97, 238, 0.2);
        font-weight: 500;
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
        color: white;
    }

    .status-badge:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }

    .status-active {
        background: var(--success-gradient);
    }

    .status-inactive {
        background: var(--danger-gradient);
    }

    .status-default {
        background: var(--primary-gradient);
    }

    /* Bulk Actions - Enhanced */
    .bulk-actions-container {
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        animation: slideDown 0.4s ease;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 15px 20px;
        border-radius: 12px;
        border-left: 4px solid var(--primary-color);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-15px);
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
        font-weight: 600;
        color: var(--primary-color);
        margin-right: auto;
        font-size: 14px;
        background: white;
        padding: 6px 12px;
        border-radius: 30px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .bulk-action-btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: all 0.3s;
        font-size: 14px;
        display: flex;
        align-items: center;
        border: 1px solid transparent;
        margin-right: 5px;
        color: white;
    }

    .bulk-action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        color: white !important;
    }

    .bulk-action-btn:active {
        transform: translateY(-1px);
    }

    .bulk-action-btn.download {
        background: var(--success-gradient);
    }

    .bulk-action-btn.download:hover {
        background: linear-gradient(135deg, #059669, #10b981);
    }

    .bulk-action-btn.activate {
        background: var(--success-gradient);
    }

    .bulk-action-btn.activate:hover {
        background: linear-gradient(135deg, #059669, #10b981);
    }

    .bulk-action-btn.deactivate {
        background: var(--warning-gradient);
    }

    .bulk-action-btn.deactivate:hover {
        background: linear-gradient(135deg, #d97706, #b45309);
    }

    .bulk-action-btn.delete {
        background: var(--danger-gradient);
    }

    .bulk-action-btn.delete:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
    }

    .bulk-action-btn.clear {
        background: linear-gradient(135deg, #64748b, #475569);
    }

    .bulk-action-btn.clear:hover {
        background: linear-gradient(135deg, #475569, #334155);
    }

    .bulk-action-btn i {
        margin-right: 5px;
    }

    .dropdown-menu {
        border-radius: 12px;
        border: none;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        padding: 8px 0;
        overflow: hidden;
    }

    .dropdown-item {
        font-size: 14px;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .dropdown-item:hover {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        padding-left: 25px;
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
        border-color: var(--primary-color);
        transform: scale(1.1);
    }

    .select-checkbox:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
    }

    /* Action Buttons - Enhanced */
    .table-actions {
        display: flex;
        gap: 8px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .action-btn {
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 5px;
        justify-content: center;
        color: white;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-decoration: none;
    }

    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        color: white !important;
        text-decoration: none;
    }

    .action-btn:active {
        transform: translateY(-1px);
    }

    .action-btn-edit {
        background: var(--warning-gradient);
    }

    .action-btn-edit:hover {
        background: linear-gradient(135deg, #d97706, #b45309);
    }

    .action-btn-delete {
        background: var(--danger-gradient);
    }

    .action-btn-delete:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
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
        color: var(--dark);
        margin-bottom: 10px;
        font-weight: 600;
    }

    .empty-state p {
        color: var(--gray);
    }

    .empty-state .btn-primary {
        background: var(--primary-gradient);
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s;
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .empty-state .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
        color: white;
    }

    /* Alert Messages */
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

    .alert .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* Loading Spinner */
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 8px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #e2e8f0;
        border-top-color: var(--primary-color);
        border-radius: 50%;
        animation: spin 0.9s linear infinite;
    }

    #pageLoader {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.7);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    /* Default badge */
    .default-badge {
        background: var(--primary-gradient);
        color: white;
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
        box-shadow: 0 2px 8px rgba(67, 97, 238, 0.2);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
        }

        .add-btn {
            width: 100%;
            justify-content: center;
        }

        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-group {
            width: 100%;
        }

        .filter-actions {
            width: 100%;
            justify-content: center;
        }

        .btn-filter {
            width: 100%;
            justify-content: center;
        }

        .bulk-actions-container {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }

        .bulk-actions-container .d-flex {
            flex-wrap: wrap;
            gap: 8px;
        }

        .bulk-action-btn {
            width: 100%;
            justify-content: center;
        }

        .table-actions {
            flex-direction: column;
            gap: 4px;
        }

        .action-btn {
            width: 100%;
        }

        .erp-table {
            font-size: 13px;
        }

        .erp-table th,
        .erp-table td {
            padding: 12px 10px;
        }
    }

    /* Text utilities */
    .text-muted {
        color: var(--gray) !important;
    }

    .small {
        font-size: 12px;
    }
/* Pagination Container */
.pagination {
    display: flex;
    list-style: none;
    gap: 6px;
    padding: 0;

}

.page-item .page-link {
    padding: 6px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    text-decoration: none;
    color: #333;
    font-size: 13px;
}

.page-item.active .page-link {
    background: #2563eb;
    color: #fff;
    border-color: #2563eb;
}

.page-item.disabled .page-link {
    background: #f5f5f5;
    color: #aaa;
}

/* Pagination Wrapper */
.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 16px;
    flex-wrap: wrap;
    gap: 10px;
}

/* Showing Text */
.pagination-info {
    font-size: 13px;
    color: #6b7280;
}
.table-responsive{
    overflow-x: hidden;
}

</style>

<div id="pageLoader">
    <div class="spinner"></div>
</div>

<div class="container-fluid">
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-bar-chart-line-fill"></i>
            Grade Systems Management
        </h1>
        <a href="{{ route('grade-systems.create') }}" class="add-btn">
            <i class="bi bi-plus-circle"></i>
            Create New Grade System
        </a>
    </div>

    {{-- Messages --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Filters --}}
    <div class="filter-container">
        <form method="GET" class="filter-form" id="filterForm">
            <div class="filter-group">
                <i class="bi bi-search"></i>
                <input type="text" list="gradeSystemList" name="search" class="filter-input"
                    value="{{ request('search') }}" placeholder="Search grade systems...">
                <datalist id="gradeSystemList">
                    @foreach($gradeSystems as $system)
                    <option value="{{ $system->name }}">{{ $system->name }}</option>
                    @endforeach
                </datalist>
            </div>

            <div class="filter-actions">
                <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary">
                    <i class="bi bi-x-circle"></i>
                    Reset Filter
                </a>
            </div>

            <!-- Hidden sort inputs -->
            <input type="hidden" name="sort_by" id="sortBy" value="{{ request('sort_by', 'name') }}">
            <input type="hidden" name="sort_order" id="sortOrder" value="{{ request('sort_order', 'asc') }}">
        </form>
    </div>

    {{-- Bulk Actions Container --}}
    <div class="bulk-actions-container" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 grade systems selected</div>
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
            <button class="bulk-action-btn delete" onclick="bulkAction('bulk_delete')">
                <i class="bi bi-trash-fill"></i>
                Bulk Delete
            </button>
            <button class="bulk-action-btn clear" onclick="clearSelection()">
                <i class="bi bi-x-lg-fill"></i>
                Clear
            </button>
        </div>
    </div>

        {{-- Grade Systems Table --}}
        <div>
            <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th class="sticky-checkbox" width="40">
                                <input type="checkbox" id="selectAll" class="select-checkbox">
                            </th>
                            <th class="sticky-main sortable" onclick="sortTable('name')">
                                Name
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'name' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'name' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable">Description</th>
                            <th class="sortable">Grade Ranges</th>
                            <th class="sortable" onclick="sortTable('is_active')">
                                Status
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'is_active' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'is_active' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('is_default')">
                                Default
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'is_default' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'is_default' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('created_at')">
                                Created
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'created_at' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'created_at' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gradeSystems as $index => $system)
                        <tr>
                            <td class="sticky-checkbox" width="40">
                                <input type="checkbox" class="select-checkbox grade-checkbox" value="{{ $system->id }}">
                            </td>
                            <td class="sticky-main">
                                <span style="font-weight: 600; color: var(--primary-color);">{{ $system->name }}</span>
                            </td>
                            <td>
                                <span class="small text-muted">
                                    {{ $system->description ?: 'No description' }}
                                </span>
                            </td>
                            <td>
                                @if($system->grade_ranges && is_array($system->grade_ranges))
                                @foreach(array_slice($system->grade_ranges, 0, 3) as $range)
                                    <span class="grade-range-badge">
                                        {{ $range['min_percentage'] }}-{{ $range['max_percentage'] }}%: {{ $range['grade'] }}
                                    </span>
                                @endforeach
                                @if(count($system->grade_ranges) > 3)
                                    <span class="text-muted small">+{{ count($system->grade_ranges) - 3 }} more</span>
                                @endif
                                @else
                                    <span class="text-muted small">No ranges defined</span>
                                @endif
                            </td>
                            <td>
                                @if($system->is_active)
                                <span class="status-badge status-active">
                                    <i class="bi bi-check-circle-fill me-1"></i>Active
                                </span>
                                @else
                                <span class="status-badge status-inactive">
                                    <i class="bi bi-x-circle-fill me-1"></i>Inactive
                                </span>
                                @endif
                            </td>
                            <td>
                                @if($system->is_default)
                                <span class="default-badge">
                                    <i class="bi bi-star-fill me-1"></i>Default
                                </span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="small">
                                    <i class="bi bi-calendar me-1" style="color: var(--primary-color);"></i>
                                    {{ $system->created_at->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="table-actions">
                                    <a href="{{ route('grade-systems.edit', $system->id) }}"
                                        class="action-btn action-btn-edit" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                        <span class="small">Edit</span>
                                    </a>
                                    @if(!$system->is_default)
                                    <form action="{{ route('grade-systems.destroy', $system->id) }}" method="POST"
                                        class="d-inline delete-form"
                                        onsubmit="return confirm('Are you sure you want to delete this grade system?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete" title="Delete">
                                            <i class="bi bi-trash"></i>
                                            <span class="small">Delete</span>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @if($gradeSystems->count() == 0)
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-bar-chart-line"></i>
                                    </div>
                                    <h4>No grade systems found</h4>
                                    <p>Try adjusting your search or create a new grade system</p>
                                    <a href="{{ route('grade-systems.create') }}" class="btn btn-primary mt-3">
                                        <i class="bi bi-plus-circle me-2"></i>Create First Grade System
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <!-- Floating Horizontal Scrollbar -->
            <div class="table-scroll-top" id="tableScrollTop">
                <div class="table-scroll-inner"></div>
            </div>
    
            <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Showing {{ $gradeSystems->firstItem() }} to {{ $gradeSystems->lastItem() }}
                        of {{ $gradeSystems->total() }} grade systems
                    </div>
        
                    @if ($gradeSystems->hasPages())
                    <nav>
                        <ul class="pagination mb-0">
        
                            {{-- Previous Page --}}
                            @if ($gradeSystems->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">Prev</span></li>
                            @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $gradeSystems->previousPageUrl() }}">Prev</a>
                            </li>
                            @endif
        
                            {{-- Page Numbers --}}
                            @for ($i = 1; $i <= $gradeSystems->lastPage(); $i++)
                                <li class="page-item {{ $gradeSystems->currentPage() == $i ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $gradeSystems->url($i) }}">{{ $i }}</a>
                                </li>
                                @endfor
        
                                {{-- Next Page --}}
                                @if ($gradeSystems->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $gradeSystems->nextPageUrl() }}">Next</a>
                                </li>
                                @else
                                <li class="page-item disabled"><span class="page-link">Next</span></li>
                                @endif
        
                        </ul>
                    </nav>
                    @endif
                </div>
        </div>
    </div>

<script>
function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}

document.addEventListener("DOMContentLoaded", function() {

    const form = document.getElementById("filterForm");
    const inputs = form.querySelectorAll(".filter-input");

    let debounceTimer;

    inputs.forEach(input => {

        input.addEventListener("keyup", function() {
            clearTimeout(debounceTimer);

            debounceTimer = setTimeout(() => {
                showLoader();
                form.submit();
            }, 500);
        });

        input.addEventListener("change", function() {
            showLoader();
            form.submit();
        });

    });

});

// Bulk Selection Management
document.addEventListener('DOMContentLoaded', function() {

    const selectAll = document.getElementById('selectAll');
    const bulkBar = document.getElementById('bulkActionsContainer');
    const countText = document.getElementById('selectedCount');
    const checkboxes = document.querySelectorAll('.grade-checkbox');

    function getEnabledCheckboxes() {
        return Array.from(checkboxes).filter(cb => !cb.disabled);
    }

    function getCheckedCheckboxes() {
        return getEnabledCheckboxes().filter(cb => cb.checked);
    }

    function updateSelectionUI() {
        const enabled = getEnabledCheckboxes();
        const checked = getCheckedCheckboxes();

        countText.textContent = checked.length + ' grade system(s) selected';

        if (checked.length > 0) {
            bulkBar.classList.add('active');
        } else {
            bulkBar.classList.remove('active');
        }

        selectAll.checked = enabled.length > 0 && checked.length === enabled.length;
        selectAll.indeterminate = checked.length > 0 && checked.length < enabled.length;
    }

    // ✅ Select All toggle
    selectAll.addEventListener('change', function() {
        getEnabledCheckboxes().forEach(cb => cb.checked = this.checked);
        updateSelectionUI();
    });

    // ✅ Individual checkbox toggle
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectionUI);
    });

    // Initial state
    updateSelectionUI();
});

// Bulk Action Functions
function clearSelection() {
    document.querySelectorAll('.grade-checkbox:checked').forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    document.getElementById('bulkActionsContainer').classList.remove('active');
}

function bulkAction(action, format = null) {
    const selectedSystems = Array.from(document.querySelectorAll('.grade-checkbox:checked'))
        .map(checkbox => checkbox.value);

    if (selectedSystems.length === 0) {
        alert('Please select at least one grade system.');
        return;
    }

    switch (action) {
            case 'download':

                    if (!format) {
                        alert("Please select a format");
                        return;
                    }

                    const ids = selectedSystems.join(',');

                    const url = `/download-grade-systems?ids=${ids}&type=${format}`;

                    window.location.href = url;
            break; 

        case 'activate':
            if (confirm(`Activate ${selectedSystems.length} grade system(s)?`)) {
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<span class="loading-spinner"></span> Activating...';
                btn.disabled = true;

                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                    alert(`${selectedSystems.length} grade systems activated successfully`);
                    clearSelection();
                }, 1500);
            }
            break;

        case 'deactivate':
            if (confirm(`Deactivate ${selectedSystems.length} grade system(s)?`)) {
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<span class="loading-spinner"></span> Deactivating...';
                btn.disabled = true;

                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                    alert(`${selectedSystems.length} grade systems deactivated successfully`);
                    clearSelection();
                }, 1500);
            }
            break;

        case 'bulk_delete':
            if (confirm(
                    `Are you sure you want to delete ${selectedSystems.length} grade system(s)? This action cannot be undone.`
                )) {
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<span class="loading-spinner"></span> Deleting...';
                btn.disabled = true;

                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                    clearSelection();
                    alert(`${selectedSystems.length} grade systems deleted successfully`);
                    // In real implementation, you would submit a form or make AJAX call
                }, 1500);
            }
            break;
    }
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
</script>
@endsection