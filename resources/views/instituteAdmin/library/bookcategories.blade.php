@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Book Categories Management</title>
<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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

    /* Add Button - Enhanced */
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
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        text-decoration: none;
    }

    .add-btn i {
        font-size: 18px;
    }

    /* Stats Cards - Enhanced */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border: none;
        border-radius: 16px;
        padding: 24px;
        transition: all 0.4s;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-gradient);
        transition: width 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 20px 35px rgba(67, 97, 238, 0.15);
    }

    .stat-card:hover::before {
        width: 8px;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        background: var(--primary-gradient);
        color: white;
        font-size: 24px;
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.2);
    }

    .stat-number {
        font-size: 28px;
        font-weight: 800;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1.2;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 14px;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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
    
    .table-responsive{
        overflow-x: hidden;
    }

    /* Category Badge - Enhanced */
    .category-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 4px 8px;
        border-radius: 30px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    }

    .category-icon {
        width: 32px;
        height: 32px;
        background: var(--primary-gradient);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.2);
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
        color: white;
        transition: all 0.3s;
        font-size: 14px;
        display: flex;
        align-items: center;
        border: 1px solid transparent;
        margin-right: 5px;
    }

    .bulk-action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .bulk-action-btn:active {
        transform: translateY(-1px);
    }

    .bulk-action-btn.download {
        background: var(--success-gradient);
    }

    .bulk-action-btn.delete {
        background: var(--danger-gradient);
    }

    .bulk-action-btn.clear {
        background: linear-gradient(135deg, #64748b, #475569);
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

    .filter-grid {
        display: flex;
        gap: 15px;
        flex: 1;
        flex-wrap: wrap;
    }

    .filter-group {
        position: relative;
        flex: 1;
        min-width: 250px;
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
        border: 2px solid #e2e8f0;
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
        border: 1px solid #e2e8f0;
    }

    .btn-filter-secondary:hover {
        background: #e2e8f0;
        color: #475569;
    }

    /* Action Buttons - Enhanced */
    .table-actions {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

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
        color: white;
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
        color: white;
    }

    .action-btn:active {
        transform: translateY(-1px);
    }

    .action-btn-edit {
        background: var(--warning-gradient);
    }

    .action-btn-delete {
        background: var(--danger-gradient);
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

    .alert .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
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

    .empty-state p {
        color: #64748b;
    }

    .empty-state .btn-primary {
        background: var(--primary-gradient);
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        margin-top: 15px;
    }

    .empty-state .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
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

    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #e2e8f0;
        border-top-color: var(--primary-color);
        border-radius: 50%;
        animation: spin 0.9s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    #pageLoader {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(5px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    /* Text utilities */
    .fw-semibold {
        font-weight: 600 !important;
    }

    .text-muted {
        color: #64748b !important;
    }

    .small {
        font-size: 12px;
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

        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-grid {
            flex-direction: column;
        }

        .filter-group {
            min-width: 100%;
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
        
        .action-btn {
            width: 100%;
        }

        .stats-container {
            grid-template-columns: 1fr;
        }
    }

    /* Delete form inline */
    .delete-form {
        display: inline;
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
        padding: 0px 20px;
    }

    /* Showing Text */
    .pagination-info {
        font-size: 13px;
        color: #6b7280;
    }

</style>

<div id="pageLoader" style="
    display:none;
    position:fixed;
    inset:0;
    background:rgba(255,255,255,0.9);
    backdrop-filter: blur(5px);
    z-index:9999;
    align-items:center;
    justify-content:center;
    ">
    <div class="spinner"></div>
</div>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-bookmark-fill"></i>
            Book Categories Management
        </h1>
        <a href="{{ route('library.category.create') }}" class="add-btn">
            <i class="bi bi-plus-circle-fill"></i>
            Add Category
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

    {{-- Stats Cards --}}
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-bookmark-fill"></i>
            </div>
            <div class="stat-number">{{ $categories->count() }}</div>
            <div class="stat-label">Total Categories</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--success-gradient);">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stat-number">{{ $categories->count() }}</div>
            <div class="stat-label">Active Categories</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--warning-gradient);">
                <i class="bi bi-clock-fill"></i>
            </div>
            <div class="stat-number">0</div>
            <div class="stat-label">Recently Added</div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="filter-container">
        <form method="GET" id="filterForm" class="filter-form">
            <div class="filter-grid">
                <div class="filter-group">
                    <i class="bi bi-search"></i>
                    <input type="search" name="search" class="filter-input" placeholder="Search Category"
                        list="categoryNameList" value="{{ request('search') }}" autocomplete="off">
                    <datalist id="categoryNameList">
                        @foreach($categories as $category)
                        <option value="{{ $category->name }}">
                        @endforeach
                    </datalist>
                </div>
            </div>

            <div class="filter-actions">
                <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary">
                    <i class="bi bi-x-circle-fill"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Bulk Actions Container --}}
    <div class="bulk-actions-container" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 categories selected</div>
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

    {{-- Categories Table --}}
    <div class="table-responsive custom-table-wrapper" id="tableWrapper">
        <table class="erp-table">
            <thead>
                <tr>
                    <th class="sticky-checkbox" width="40">
                        <input type="checkbox" id="selectAll" class="select-checkbox">
                    </th>
                    <th class="sticky-main sortable" onclick="sortTable('book_categories_id')">
                        ID
                        <div class="sort-icons">
                            <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'book_categories_id' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'book_categories_id' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('name')">
                        Category Name
                        <div class="sort-icons">
                            <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'name' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'name' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('description')">
                        Description
                        <div class="sort-icons">
                            <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'description' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'description' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="categoriesContainer">
                @forelse($categories as $category)
                <tr>
                    <td class="sticky-checkbox">
                        <input type="checkbox" class="category-checkbox select-checkbox"
                            value="{{ $category->book_categories_id }}">
                    </td>
                    <td class="sticky-main">
                        <span class="category-badge">
                            <span class="category-icon d-none">
                                {{ substr($category->name, 0, 1) }}
                            </span>
                            <span class="fw-semibold">{{ $category->book_categories_id }}</span>
                        </span>
                    </td>
                    <td>
                        <span class="fw-semibold">{{ $category->name }}</span>
                    </td>
                    <td>
                        @if($category->description)
                        <span class="text-muted">{{ Str::limit($category->description, 60) }}</span>
                        @else
                        <span class="text-muted fst-italic">No description</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="table-actions">
                            <a href="{{ route('library.category.create', $category->book_categories_id) }}"
                                class="action-btn action-btn-edit d-block" title="Edit Category">
                                <i class="bi bi-pencil-fill"></i>
                                <span class="small">Edit</span>
                            </a>

                            <form action="{{ route('library.category.create', $category->book_categories_id) }}"
                                method="POST" class="d-inline delete-form" onsubmit="return confirmDelete(this)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn action-btn-delete d-block"
                                    title="Delete Category">
                                    <i class="bi bi-trash-fill"></i>
                                    <span class="small">Delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-inboxes-fill"></i>
                            </div>
                            <h4>No Categories Found</h4>
                            <p>Try adjusting your filters or add a new category</p>
                            <a href="{{ route('library.category.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle-fill me-2"></i>Add First Category
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Floating Horizontal Scrollbar -->
    <div class="table-scroll-top" id="tableScrollTop">
        <div class="table-scroll-inner"></div>
    </div>

    {{-- Results Count --}}
    <!-- @if($categories->count() > 0)
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-sm text-gray-600">
            <i class="bi bi-layout-text-window me-1"></i>
            Showing {{ $categories->count() }} results
        </div>
    </div>
    @endif -->

<div class="pagination-wrapper">
        <div class="pagination-info">
            Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }}
            of {{ $categories->total() }} books
        </div>

        @if ($categories->hasPages())
        <nav>
            <ul class="pagination mb-0">

                {{-- Previous Page --}}
                @if ($categories->onFirstPage())
                <li class="page-item disabled"><span class="page-link">Prev</span></li>
                @else
                <li class="page-item">
                    <a class="page-link" href="{{ $categories->previousPageUrl() }}">Prev</a>
                </li>
                @endif

                {{-- Page Numbers --}}
                @for ($i = 1; $i <= $categories->lastPage(); $i++)
                    <li class="page-item {{ $categories->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ $categories->url($i) }}">{{ $i }}</a>
                    </li>
                    @endfor

                    {{-- Next Page --}}
                    @if ($categories->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $categories->nextPageUrl() }}">Next</a>
                    </li>
                    @else
                    <li class="page-item disabled"><span class="page-link">Next</span></li>
                    @endif

            </ul>
        </nav>
        @endif
    </div>

</div>

<script>
function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}

// Bulk Selection Management
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    const bulkActionsContainer = document.getElementById('bulkActionsContainer');
    const selectedCountElement = document.getElementById('selectedCount');

    // Select All functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            categoryCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectionUI();
        });
    }

    // Individual checkbox change
    categoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionUI);
    });

    function updateSelectionUI() {
        const selectedCount = document.querySelectorAll('.category-checkbox:checked').length;

        if (selectedCount > 0) {
            bulkActionsContainer.classList.add('active');
            selectedCountElement.textContent = selectedCount + ' category(s) selected';

            // Update select all checkbox state
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = selectedCount === categoryCheckboxes.length;
                selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < categoryCheckboxes
                    .length;
            }
        } else {
            bulkActionsContainer.classList.remove('active');
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
        }
    }
});

// Bulk Action Functions
function clearSelection() {
    document.querySelectorAll('.category-checkbox:checked').forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    document.getElementById('bulkActionsContainer').classList.remove('active');
}

function bulkAction(action, format = null) {
    const selectedCategories = Array.from(document.querySelectorAll('.category-checkbox:checked'))
        .map(checkbox => checkbox.value);

    if (selectedCategories.length === 0) {
        alert('Please select at least one category.');
        return;
    }

    switch (action) {
        case 'download':

            if (!format) {
                alert("Please select a format");
                return;
            }

            const ids = selectedCategories.join(',');

            const url = `/download-book-categories?ids=${ids}&type=${format}`;

            window.location.href = url;

            break;

        case 'bulk_delete':
            if (confirm(
                    `Are you sure you want to delete ${selectedCategories.length} category(s)? This action cannot be undone.`
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
                    alert(`${selectedCategories.length} categories deleted successfully`);
                }, 1500);
            }
            break;

        default:
            alert(`${action} action triggered for ${selectedCategories.length} categories`);
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

// Delete confirmation
function confirmDelete(form) {
    if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
        // Show loading state
        const btn = form.querySelector('button[type="submit"]');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<span class="loading-spinner"></span> Deleting...';
        btn.disabled = true;
        return true;
    }
    return false;
}

// Search Functionality
document.getElementById('searchDesignations')?.addEventListener('input', async function(e) {
    const searchTerm = e.target.value.trim();

    if (searchTerm.length === 0) {
        return;
    }

    if (searchTerm.length < 2) {
        return;
    }

    try {
        const response = await fetch(`/categories/search?search_term=${encodeURIComponent(searchTerm)}`);
        const result = await response.json();

        if (result.success) {
            if (result.categories.length > 0) {
                let html = '';
                result.categories.forEach(category => {
                    html += `
                        <tr>
                            <td>
                                <input type="checkbox" class="category-checkbox select-checkbox" value="${category.book_categories_id}">
                            </td>
                            <td>
                                <span class="category-badge">
                                    <span class="category-icon">
                                        ${category.name.charAt(0)}
                                    </span>
                                    <span class="fw-semibold">${category.book_categories_id}</span>
                                </span>
                            </td>
                            <td>
                                <span class="fw-semibold">${category.name}</span>
                            </td>
                            <td>
                                ${category.description ? `<span class="text-muted">${category.description.substring(0, 60)}${category.description.length > 60 ? '...' : ''}</span>` : '<span class="text-muted fst-italic">No description</span>'}
                            </td>
                            <td class="text-center">
                                <div class="table-actions">
                                    <a href="/library/category/create/${category.book_categories_id}" 
                                       class="action-btn action-btn-edit" title="Edit Category">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="/library/category/create/${category.book_categories_id}" 
                                          method="POST" class="d-inline delete-form"
                                          onsubmit="return confirmDelete(this)">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete" title="Delete Category">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                document.getElementById('categoriesContainer').innerHTML = html;
            } else {
                document.getElementById('categoriesContainer').innerHTML = `
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-inboxes-fill"></i>
                                </div>
                                <h4>No Categories Found</h4>
                                <p>Try adjusting your search term</p>
                            </div>
                        </td>
                    </tr>
                `;
            }
        }
    } catch (error) {
        console.error('Error searching categories:', error);
    }
});

// Filter form submission with loading state
const searchInput = document.querySelector('input[name="search"]');
let debounceTimer;

if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {
            showLoader();
            document.getElementById('filterForm').submit();
        }, 400); // smooth typing experience
    });
}
</script>
@endsection