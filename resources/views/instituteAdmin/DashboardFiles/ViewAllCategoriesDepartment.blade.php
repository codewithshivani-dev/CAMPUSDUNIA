@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Department Management</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
/* Eye-catching gradient background for key elements */
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --primary-color: #4361ee;
    --secondary-color: #3a0ca3;
    --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
}

/* ERP Table Styles - matching fee structure page */
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

/* Filter container - enhanced */
.filter-container {
    background: white;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    animation: slideUp 0.4s ease;
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

.filter-container h6 {
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-form {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    margin: 10px 0px;
}

.filter-group {
    position: relative;
    flex: 1;
    min-width: 220px;
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

.filter-group input,
.filter-group select {
    width: 100%;
    padding: 12px 12px 12px 40px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    background: #fff;
    transition: all 0.3s;
}

.filter-group input:focus,
.filter-group select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    transform: translateY(-2px);
}

.filter-group input:hover,
.filter-group select:hover {
    border-color: var(--secondary-color);
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
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
}

.btn-filter:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    color: white !important;
    text-decoration: none !important;
}

.btn-filter:active {
    transform: translateY(-1px);
}

.btn-filter-primary {
    background: var(--primary-gradient);
    color: white;
    position: relative;
    overflow: hidden;
    text-decoration: none;
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
 
/* Page Header - enhanced (animation removed) */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px 30px;
    background: var(--primary-gradient);
    border-radius: 16px;
    box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
    display: none;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
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
    position: relative;
    z-index: 1;
}

.page-title i {
    font-size: 32px;
    filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
}

/* Action Buttons - enhanced */
.action-btn {
    padding: 8px 12px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: transparent;
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
}

.action-btn-view {
    background: linear-gradient(135deg, #e0f2fe, #bae6fd);
    color: #0369a1;
}

.action-btn-edit {
    background: linear-gradient(135deg, #fef2c8, #fde68a);
    color: #92400e;
}

.action-btn-delete {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #991b1b;
}

.action-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.custom-gap {
    gap: 10px;
}

/* Stats Cards - enhanced with gradient */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.4s;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    border: none;
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

.stat-info {
    display: flex;
    flex-direction: column;
    position: relative;
    z-index: 1;
}

.stat-number {
    font-size: 32px;
    font-weight: 800;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    line-height: 1.2;
}

.stat-label {
    color: #64748b;
    font-size: 14px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-icon {
    font-size: 40px;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    opacity: 0.8;
}

/* Search Section */
.search-section {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 24px;
    border: 1px solid #e2e8f0;
}

.search-box {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s;
    background: #fff;
}

.search-box:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
}

.controls {
    display: flex;
    gap: 12px;
    align-items: center;
}

.filter-select {
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    background: white;
    min-width: 200px;
    transition: all 0.3s;
}

.filter-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
}

/* Category Group Header - enhanced */
.category-group-header {
    background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
    border-left: 4px solid var(--primary-color);
    font-weight: 600;
    color: #1e293b;
}

.category-group-header td {
    padding: 16px 16px;
    font-size: 15px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
}

.category-badge {
    background: var(--primary-gradient);
    color: white;
    padding: 6px 16px;
    border-radius: 30px;
    font-size: 12px;
    width: max-content;
    display: inline-block;
    font-weight: 600;
    box-shadow: 0 4px 10px rgba(67, 97, 238, 0.2);
}

/* Department ID badge */
.dept-id-badge {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    color: #475569;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-family: monospace;
}

/* Description column */
.dept-description {
    color: #64748b;
    font-size: 13px;
    line-height: 1.5;
    max-width: 300px;
}

/* Empty State */
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

/* Modal refinements */
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
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    border-top: 1px solid #e2e8f0;
    padding: 16px 24px;
}

/* Checkbox styles */
.checkbox-column {
    width: 40px;
    text-align: center;
}

.select-checkbox {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--primary-color);
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
}

/* Bulk Actions Container - enhanced */
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
    background: linear-gradient(135deg, #10b981, #059669);
}

.bulk-action-btn.delete {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

.bulk-action-btn.clear {
    background: linear-gradient(135deg, #64748b, #475569);
}

.bulk-action-btn i {
    margin-right: 5px;
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

/* Dropdown Menu Styles */
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

.dropdown-item i {
    font-size: 16px;
}

/* Department row styling when checkbox is checked */
tr.department-row.selected {
    background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
}

tr.department-row.selected:hover {
    background: linear-gradient(135deg, #e0f2fe, #bae6fd);
}

/* Loading spinner */
.loading-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid var(--primary-color);
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

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #ccc;
    border-top-color: var(--primary-color);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
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

/* Badge styles */
.badge.bg-success {
    background: linear-gradient(135deg, #10b981, #059669) !important;
    padding: 8px 16px;
    border-radius: 30px;
    font-weight: 500;
    box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
}

.badge.bg-info {
    background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
    padding: 8px 16px;
    border-radius: 30px;
    font-weight: 500;
    box-shadow: 0 4px 10px rgba(59, 130, 246, 0.2);
}

/* Responsive */
@media (max-width: 768px) {
    .filter-grid {
        flex-direction: column;
        gap: 12px;
    }

    .filter-actions {
        width: 100%;
        justify-content: flex-end;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .erp-table th,
    .erp-table td {
        padding: 8px 12px;
        font-size: 13px;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }
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
.clickable-card{
    cursor:pointer;
}

.clickable-card:hover{
    transform: translateY(-5px) scale(1.02);
}
</style>

<div id="pageLoader">
    <div class="spinner"></div>
</div>

<div class="container-fluid">
    <!-- Page Header (animation removed) -->
    <div class="page-header">
        <h4 class="page-title">
            <i class="bi bi-building"></i>
            View Departments
        </h4>
        <a href="{{ route('departments.page') }}" class="btn-filter btn-filter-primary">
            <i class="bi bi-plus-circle"></i>
            Add Department
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card clickable-card"
             onclick="openStatsModal('categories')">
            <div class="stat-info">
                <span class="stat-number">{{ $categories->count() }}</span>
                <span class="stat-label">Total Categories</span>
            </div>
            <div class="stat-icon">
                <i class="bi bi-folder"></i>
            </div>
        </div>
        <div class="stat-card clickable-card"
             onclick="openStatsModal('departments')">
            <div class="stat-info">
                <span class="stat-number">{{ $departments->count() }}</span>
                <span class="stat-label">Total Departments</span>
            </div>
            <div class="stat-icon">
                <i class="bi bi-building"></i>
            </div>
        </div>
        <!-- Total Employees -->
        <div class="stat-card clickable-card"
             onclick="window.location.href='{{ url('/institute/admin/addemployees') }}'">
            <div class="stat-info">
                <span class="stat-number">
                    {{ $departments->sum('employee_count') }}
                </span>
                <span class="stat-label">
                    Total Employees
                </span>
            </div>
            <div class="stat-icon">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
        <div class="stat-card d-none">
            <div class="stat-info">
                <span
                    class="stat-number">{{ $categories->avg('departments_count') > 0 ? number_format($categories->avg('departments_count'), 1) : 0 }}</span>
                <span class="stat-label">Avg per Category</span>
            </div>
            <div class="stat-icon">
                <i class="bi bi-bar-chart"></i>
            </div>
        </div>
        <div class="stat-card d-none">
            <div class="stat-info">
                <span class="stat-number">{{ $categories->max('departments_count') }}</span>
                <span class="stat-label">Most in Category</span>
            </div>
            <div class="stat-icon">
                <i class="bi bi-trophy"></i>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="filter-container">
        <h6><i class="bi bi-funnel-fill"></i> *Select or Type to Search</h6>
        <form method="GET" class="filter-form" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <i class="bi bi-folder"></i>
                    <input type="text" id="categorySearch" class="filter-input" list="categoryList"
                        placeholder="Search category..." value="{{ $selectedCategoryName ?? '' }}">

                    <datalist id="categoryList">
                        @foreach($categories as $category)
                        <option value="{{ $category->category_name }}"
                            data-id="{{ $category->department_category_id }}">
                        </option>
                        @endforeach
                    </datalist>

                    <input type="hidden" id="categoryId" name="category_id" value="{{ request('category_id') }}">
                </div>

                <div class="filter-group">
                    <i class="bi bi-building"></i>
                    <input type="text" id="departmentSearch" class="filter-input" list="departmentList"
                        placeholder="Search department..." value="{{ $selectedDepartmentName
                        ? $selectedDepartmentName->department . ' (' . $selectedDepartmentName->category->category_name . ')'
                        : '' }}">

                    <datalist id="departmentList">
                        @foreach($departments as $department)
                        <option
                            value="{{ $department->department }} ({{ $department->category->category_name ?? 'No Category' }})"
                            data-id="{{ $department->department_id }}"
                            data-category-id="{{ $department->department_category_id }}">
                        </option>
                        @endforeach
                    </datalist>


                    <input type="hidden" id="departmentId" name="department_id" value="{{ request('department_id') }}">
                </div>
                
                <div class="filter-actions">
                    <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary" style="color:#64748b !important;text-decoration:none">
                        <i class="bi bi-x-circle"></i>
                        Reset Filters
                    </a>
                </div>

            </div>

            <!-- Hidden sort inputs -->
            <input type="hidden" name="sort_by" id="sortBy" value="{{ request('sort_by', 'employee_code') }}">
            <input type="hidden" name="sort_order" id="sortOrder" value="{{ request('sort_order', 'asc') }}">
        </form>
    </div>

    <!-- Bulk Actions Container -->
    <div class="bulk-actions-container active d-none" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 departments selected</div>
        <div class="d-flex flex-wrap">
            <div class="dropdown ">
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
                <i class="bi bi-trash"></i>
                Bulk Delete
            </button>
            <button class="bulk-action-btn clear" onclick="clearSelection()">
                <i class="bi bi-x-lg"></i>
                Clear
            </button>
        </div>
    </div>

    <!-- Departments Table -->
    @if($categories->count() > 0)
    <div class="table-responsive">
    <input type="hidden" id="filteredTotal" value="{{ $departments->count() }}">
    <table class="erp-table" id="departmentsTable">
        <thead>
            <tr>
                <th class="checkbox-column d-none">
                    <input type="checkbox" id="selectAll" class="select-checkbox">
                </th>
                <th class="sticky-checkbox" width="40">#</th>
                <th class="sticky-main sortable" onclick="sortTable('department')">
                    <span style="border-bottom: 1px solid">Category</span>
                    </br>
                    Department
                    <div class="sort-icons">
                        <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'department' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                        <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'department' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                    </div>
                </th>
                <th class="sortable" onclick="sortTable('description')">
                    Description
                    <div class="sort-icons">
                        <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'description' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                        <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'description' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                    </div>
                </th>
                <th class="sortable" onclick="sortTable('employees')">
                    Employees
                    <div class="sort-icons">
                        <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'employees' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                        <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'employees' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                    </div>
                </th>
                <th class="sortable" onclick="sortTable('created_at')">
                    Created Date
                    <div class="sort-icons">
                        <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'created_at' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                        <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'created_at' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                    </div>
                </th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($categories as $category)
            @php
            $departmentsInCategory = $category->departments;
            $deptCount = $departmentsInCategory->count();
            $categoryTotalEmployees = $departmentsInCategory->sum('employee_count');
            @endphp

            <!-- Category Group Header -->
            <tr class="category-group-header" data-category-id="{{ $category->department_category_id }}">

                <td colspan="3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong style="font-size: 16px; color: var(--primary-color);">
                                <i class="bi bi-folder-fill me-2"></i>{{ $category->category_name }}
                            </strong>
                        </div>
                    </div>
                </td>
                <td colspan="2">
                    <div>
                        @if($categoryTotalEmployees > 0)
                            <span class="badge bg-info ms-2 text-white" style="font-size: 12px;">
                                <i class="bi bi-people-fill"></i> {{ $categoryTotalEmployees }}  Employees
                            </span>
                        @endif
                    </div>
                </td>
                <td>
                    <div style="display: flex; text-align: end;">
                        <span class="category-badge">
                            {{ $deptCount }} {{ Str::plural('Department', $deptCount) }}
                        </span>
                        <!-- Toggle button -->
                        <button class="action-btn action-btn-view ms-3"
                            onclick="toggleCategoryRows('{{ $category->department_category_id }}')"
                            title="Toggle departments" style="padding: 4px 10px;">
                            <i class="bi bi-chevron-up" id="icon-{{ $category->department_category_id }}"></i>
                        </button>
                    </div>
                </td>
            </tr>

            <!-- Department Rows -->
            @forelse($departmentsInCategory as $index => $department)
            <tr class="department-row category-{{ $category->department_category_id }}"
                data-dept-id="{{ $department->department_id }}"
                data-dept-name="{{ strtolower($department->department) }}">
                <td class="checkbox-column d-none">
                    <input type="checkbox" class="department-checkbox select-checkbox"
                        value="{{ $department->department_id }}">
                </td>
                <td class="sticky-main-2" style="text-align: center;">{{ $loop->iteration }}</td>
                <td class="sticky-main">
                    <div class="d-flex align-items-center">
                        <span style="font-weight: 500;">{{ $department->department }}</span>
                    </div>
                </td>
                <td style="max-width: 200px;">
                    <span class="dept-description">{{ $department->description ?: 'No description provided' }}</span>
                </td>
                <!--<td>-->
                <!--    <div class="d-flex align-items-center">-->
                <!--        @if($department->employee_count > 0)-->
                <!--            <span class="badge bg-success text-white" style="font-size: 13px; padding: 6px 12px;">-->
                <!--                <i class="bi bi-people-fill me-1"></i>-->
                <!--                {{ $department->employee_count }} -->
                                
                <!--            </span>-->
                            
                            <!-- Quick view button for employees -->
                <!--            <button class="d-none btn btn-sm btn-link text-primary ms-2 view-employees"-->
                <!--                    data-department-id="{{ $department->department_id }}"-->
                <!--                    data-department-name="{{ $department->department }}"-->
                <!--                    title="View Employees List">-->
                <!--                <i class="bi bi-eye"></i>-->
                <!--            </button>-->
                <!--        @else-->
                <!--            <span style="font-size: 13px; padding: 6px 12px; color: #94a3b8;">-->
                <!--                <i class="bi bi-people-fill me-1"></i>-->
                <!--                No Employee-->
                <!--            </span>-->
                <!--        @endif-->
                <!--    </div>-->
                <!--</td>-->
                <td>
                    <div class="d-flex align-items-center">
                        @if($department->employee_count > 0)
                            <a href="{{ url('/institute/admin/addemployees') }}?name=&employee_code=&department_id={{ $department->department_id }}&department_name_input={{ urlencode($department->department) }}&designation=&sort_by=employee_code&sort_order=asc"
                               class="text-decoration-none">
                                <span class="badge bg-success text-white"
                                      style="font-size: 13px; padding: 6px 12px; cursor:pointer;">
                                    <i class="bi bi-people-fill me-1"></i>
                                    {{ $department->employee_count }}
                                </span>
                            </a>
                        @else
                            <span style="font-size: 13px; padding: 6px 12px; color: #94a3b8;">
                                <i class="bi bi-people-fill me-1"></i>
                                No Employee
                            </span>
                        @endif
                    </div>
                </td>
                <td>
                    <span class="">
                        {{ $department->created_at ? $department->created_at->format('d M Y') : 'N/A' }}
                    </span>
                </td>
                <td class="text-center">
                    <div class="d-flex justify-content-center custom-gap">
                        <button class="action-btn action-btn-edit d-block"
                            onclick="openEditModal('{{ $department->department_id }}', '{{ addslashes($department->department) }}', '{{ addslashes($department->description) }}')"
                            title="Edit Department">
                            <div>
                                <i class="bi bi-pencil"></i>
                            </div>
                            <div>
                                <span>Edit</span>
                            </div>
                        </button>
                        <button class="action-btn action-btn-delete d-block"
                            onclick="openDeleteModal('{{ $department->department_id }}', '{{ addslashes($department->department) }}')"
                            title="Delete Department"
                            @if($department->employee_count > 0)
                            disabled
                            style="opacity: 0.5; cursor: not-allowed;"
                            data-bs-toggle="tooltip" 
                            title="Cannot delete department with {{ $department->employee_count }} employee(s)"
                            @endif>
                            <div>
                                <i class="bi bi-trash"></i>
                            </div>
                            <div>
                                <span>Delete</span>
                            </div>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <!-- No departments in this category -->
            <tr class="department-row category-{{ $category->department_category_id }}">
                <td class="checkbox-column"></td>
                <td></td>
                <td colspan="6">
                    <div class="empty-state"
                        style="padding: 24px; margin: 0; border: none; background: transparent;">
                        <i class="bi bi-inbox" style="font-size: 24px; color: #cbd5e1;"></i>
                        <p style="margin: 8px 0 0; color: #64748b;">No departments in this category.</p>
                    </div>
                </td>
            </tr>
            @endforelse

            <!-- Spacer between categories -->
            @if(!$loop->last)
            <tr>
                <td colspan="7" style="padding: 10px; background: #f8fafc;"></td>
            </tr>
            @endif
            @endforeach
        </tbody>
        
    </table>
</div>
    @else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="bi bi-diagram-3"></i>
        </div>
        <h4 style="color: #1e293b; margin-bottom: 10px;">No Categories Found</h4>
        <p class="text-muted">Get started by creating your first department category.</p>
        <a href="{{ route('departments.page') }}" class="btn-filter btn-filter-primary mt-3">
            <i class="bi bi-plus-circle"></i> Create First Category
        </a>
    </div>
    @endif
    
    <div class="pagination-wrapper">
        <div class="pagination-info">
            Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }}
            of {{ $categories->total() }} employees
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

    <!-- Edit Department Modal -->
    <div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="editDepartmentLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="editDepartmentForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-pencil-square"></i>
                            Edit Department
                        </h5>
                        <button type="button" class="modal-close-button" data-bs-dismiss="modal"
                            aria-label="Close">×</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="department_id" id="edit_department_id">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Department Name</label>
                            <input type="text" name="department" id="edit_department_name" class="form-control"
                                required style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" id="edit_department_description" class="form-control"
                                rows="3" style="border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px; padding: 10px 20px;">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="background: var(--primary-gradient); border: none; border-radius: 10px; padding: 10px 20px;">
                            <i class="bi bi-check-circle"></i> Update Department
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Department Modal -->
    <div class="modal fade" id="deleteDepartmentModal" tabindex="-1" aria-labelledby="deleteDepartmentLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="deleteDepartmentForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-exclamation-triangle text-danger"></i>
                            Delete Department
                        </h5>
                        <button type="button" class="modal-close-button" data-bs-dismiss="modal"
                            aria-label="Close">×</button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete <strong id="delete_department_name"></strong>?</p>
                        <input type="hidden" name="department_id" id="delete_department_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px; padding: 10px 20px;">Cancel</button>
                        <button type="submit" class="btn btn-danger" style="background: linear-gradient(135deg, #ef4444, #dc2626); border: none; border-radius: 10px; padding: 10px 20px;">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Stats Modal -->
    <div class="modal fade" id="statsModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
    
                <div class="modal-header">
                    <h5 class="modal-title" id="statsModalTitle">
                        <i class="bi bi-list"></i>
                        Details
                    </h5>
    
                    <button type="button"
                            class="modal-close-button"
                            data-bs-dismiss="modal">
                        ×
                    </button>
                </div>
    
                <div class="modal-body">
    
                    <!-- Categories List -->
                    <div id="categoriesList" style="display:none;">
    
                        <div class="list-group">
    
                            @foreach($categories as $category)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                
                                <div>
                                    <h6 class="mb-1">
                                        <i class="bi bi-folder-fill text-primary me-2"></i>
                                        {{ $category->category_name }}
                                    </h6>
    
                                    <small class="text-muted d-none">
                                        {{ $category->departments->count() }}
                                        Departments
                                    </small>
                                </div>
    
                                <span class="badge bg-primary rounded-pill d-none">
                                    {{ $category->departments->count() }}
                                </span>
    
                            </div>
                            @endforeach
    
                        </div>
    
                    </div>
    
                    <!-- Departments List -->
                    <div id="departmentsList" style="display:none;">
    
                        <div class="list-group">
    
                            @foreach($departments as $department)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
    
                                <div>
                                    <h6 class="mb-1">
                                        <i class="bi bi-building text-success me-2"></i>
                                        {{ $department->department }}
                                    </h6>
    
                                    <small class="text-muted">
                                        {{ $department->category->category_name ?? 'No Category' }}
                                    </small>
                                </div>
    
                                <span class="badge bg-success rounded-pill d-none">
                                    {{ $department->employee_count ?? 0 }}
                                    Employees
                                </span>
    
                            </div>
                            @endforeach
    
                        </div>
    
                    </div>
    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery and Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ============================================
// GLOBAL VARIABLES
// ============================================
let isSelectAllActive = false;
let selectedDepartmentIds = new Set();
let currentFilters = {};

function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}

function initializeSelectionManagement() {

    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.department-checkbox');

    if (selectAll) {
        selectAll.addEventListener('change', function() {

            if (this.checked) {
                isSelectAllActive = true;
                selectedDepartmentIds.clear();

                checkboxes.forEach(cb => {
                    cb.checked = true;
                    cb.closest('tr')?.classList.add('selected');
                });

            } else {
                isSelectAllActive = false;

                checkboxes.forEach(cb => {
                    cb.checked = false;
                    cb.closest('tr')?.classList.remove('selected');
                });
            }

            updateSelectionUI();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {

            if (this.checked) {
                selectedDepartmentIds.add(this.value);
                this.closest('tr')?.classList.add('selected');
            } else {
                selectedDepartmentIds.delete(this.value);
                this.closest('tr')?.classList.remove('selected');
            }

            if (isSelectAllActive && !this.checked) {
                selectAll.checked = false;
                isSelectAllActive = false;
            }

            updateSelectionUI();
        });
    });
}
// ============================================
// INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', function() {

    initializeSelectionManagement();
    setupFilterAutoSubmit();
    loadCurrentFilters();

    // Auto-select all when filters exist
    if (hasActiveFilters()) {

        autoSelectVisibleDepartments();
    }


    restoreSelectionState();
});


document.addEventListener('DOMContentLoaded', function() {

    const form = document.getElementById('filterForm');

    const categoryInput = document.getElementById('categorySearch');
    const categoryIdInput = document.getElementById('categoryId');
    const categoryOptions = document.querySelectorAll('#categoryList option');

    const deptInput = document.getElementById('departmentSearch');
    const deptIdInput = document.getElementById('departmentId');
    const deptOptions = document.querySelectorAll('#departmentList option');

    // CATEGORY FILTER (ID BASED)
    categoryInput.addEventListener('change', function() {
        const match = Array.from(categoryOptions)
            .find(opt => opt.value === this.value);

        categoryIdInput.value = match ? match.dataset.id : '';
        deptIdInput.value = ''; // reset department
        showLoader();
        form.submit();
    });

    // DEPARTMENT FILTER (ID BASED)
    deptInput.addEventListener('change', function() {
        const match = Array.from(deptOptions)
            .find(opt => opt.value === this.value);

        if (match) {
            deptIdInput.value = match.dataset.id;
            categoryIdInput.value = match.dataset.categoryId; // enforce category
        } else {
            deptIdInput.value = '';
        }
        showLoader();
        form.submit();
    });

});

function autoSelectVisibleDepartments() {
    selectedDepartmentIds.clear();

    document.querySelectorAll('.department-row').forEach(row => {
        if (row.offsetParent !== null) { // visible rows only
            const checkbox = row.querySelector('.department-checkbox');
            if (checkbox) {
                checkbox.checked = true;
                selectedDepartmentIds.add(checkbox.value);
                row.classList.add('selected');
            }
        }
    });

    isSelectAllActive = false;
    updateSelectionUI();
}

// ============================================
// FILTER AUTO-SUBMIT
// ============================================
function setupFilterAutoSubmit() {
    const form = document.getElementById('filterForm');
    if (!form) return;

    const delay = 800;

    ['categorySearch', 'departmentSearch'].forEach(id => {
        const input = document.getElementById(id);
        if (!input) return;

        input.addEventListener('input', () => {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                // Do NOT submit if ID is missing
                if (
                    (id === 'categorySearch' && !categoryId.value) ||
                    (id === 'departmentSearch' && !departmentId.value)
                ) {
                    return;
                }
                form.submit();
            }, delay);
        });
    });
}

function hasActiveFilters() {
    const params = new URLSearchParams(window.location.search);
    return ['category_id', 'department_id'].some(
        k => params.has(k) && params.get(k).trim() !== ''
    );
}

function storeSelectionState() {
    if (isSelectAllActive) {
        sessionStorage.setItem('deptSelectAll', '1');
        sessionStorage.setItem('deptFilters', JSON.stringify(getCurrentFilters()));
    }
}

function restoreSelectionState() {
    if (sessionStorage.getItem('deptSelectAll') === '1') {
        const selectAll = document.getElementById('selectAll');
        if (selectAll) selectAll.click();
        sessionStorage.clear();
    }
}
// ============================================
// UI UPDATE
// ============================================
function updateSelectionUI() {
    const selectedCount = selectedDepartmentIds.size;
    const selectAllCheckbox = document.getElementById('selectAll');
    const selectedCountElement = document.getElementById('selectedCount');
    const bulkActionsContainer = document.getElementById('bulkActionsContainer');

    if (isSelectAllActive || (selectAllCheckbox && selectAllCheckbox.checked)) {
        const filteredTotalInput = document.getElementById('filteredTotal');
        const filteredTotal = filteredTotalInput ? parseInt(filteredTotalInput.value, 10) : 0;

        selectedCountElement.textContent = `${filteredTotal} department(s) selected (all)`;
        bulkActionsContainer?.classList.add('active', 'select-all-active');
    } else if (selectedCount > 0) {
        selectedCountElement.textContent = `${selectedCount} department(s) selected`;
        bulkActionsContainer?.classList.add('active');
        bulkActionsContainer?.classList.remove('select-all-active');

        if (selectAllCheckbox) {
            const totalCheckboxes = document.querySelectorAll('.department-checkbox').length;
            selectAllCheckbox.checked = selectedCount === totalCheckboxes;
            selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < totalCheckboxes;
        }
    } else {
        selectedCountElement.textContent = '0 departments selected';
        bulkActionsContainer?.classList.remove('active', 'select-all-active');
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
    }
}

// ============================================
// CLEAR SELECTION
// ============================================
function clearSelection() {
    document.querySelectorAll('.department-checkbox:checked').forEach(checkbox => {
        checkbox.checked = false;
        checkbox.closest('tr')?.classList.remove('selected');
    });

    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
    }

    isSelectAllActive = false;
    selectedDepartmentIds.clear();

    const bulkActionsContainer = document.getElementById('bulkActionsContainer');
    bulkActionsContainer?.classList.remove('active', 'select-all-active');
    updateSelectionUI();
}

// ============================================
// BULK ACTIONS
// ============================================
function bulkAction(action, format = null) {

    if (action !== 'download') return;

    if (selectedDepartmentIds.size === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No selection',
            text: 'Select at least one department',
            confirmButtonColor: '#4361ee'
        });
        return;
    }

    const ids = [...selectedDepartmentIds].join(',');

    let url = `/category-department/download?type=${format}&ids=${ids}`;

    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.src = url;

    document.body.appendChild(iframe);

    setTimeout(() => iframe.remove(), 3000);
}

function handleBulkDelete(selectAllCheckbox, selectedDepartments) {
    const isAll = selectAllCheckbox && selectAllCheckbox.checked;
    const count = isAll ? 'ALL' : selectedDepartments.length;

    Swal.fire({
        title: 'Are you sure?',
        text: isAll ?
            `You are about to delete ALL departments matching your current filters. This action cannot be undone.` :
            `You are about to delete ${selectedDepartments.length} department(s). This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete them!'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoadingForAction('Processing deletion...');

            const data = new FormData();
            if (isAll) {
                data.append('select_all', 1);
                data.append('filters', JSON.stringify(getCurrentFilters()));
            } else {
                data.append('ids', selectedDepartments.join(','));
            }

            // Simulate delete - replace with actual API call
            setTimeout(() => {
                hideLoadingForAction();
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: `${isAll ? 'All departments' : selectedDepartments.length + ' department(s)'} have been deleted.`,
                    confirmButtonColor: '#4361ee'
                }).then(() => {
                    location.reload();
                });
            }, 1500);
        }
    });
}

// ============================================
// FILTER HELPERS
// ============================================
function loadCurrentFilters() {
    const form = document.getElementById('filterForm');
    if (!form) return;

    const data = new FormData(form);
    currentFilters = {};

    for (let [k, v] of data) {
        if (v && !k.includes('sort') && !k.includes('_token')) {
            currentFilters[k] = v;
        }
    }
}

function getCurrentFilters() {
    loadCurrentFilters();
    return currentFilters;
}



function showLoadingForAction(message) {
    const container = document.getElementById('bulkActionsContainer');
    if (!container) return;

    const loadingDiv = document.createElement('div');
    loadingDiv.className = 'bulk-action-loading';
    loadingDiv.innerHTML = `<div class="loading-spinner"></div><span class="loading-message">${message}</span>`;
    container.appendChild(loadingDiv);
    container.classList.add('processing');
}

function hideLoadingForAction() {
    const container = document.getElementById('bulkActionsContainer');
    if (!container) return;

    container.querySelector('.bulk-action-loading')?.remove();
    container.classList.remove('processing');
}

// ============================================
// SORTING
// ============================================
function sortTable(column) {
    const sortBy = document.getElementById('sortBy');
    const sortOrder = document.getElementById('sortOrder');

    if (!sortBy || !sortOrder) return;

    const currentSortBy = sortBy.value;
    const currentSortOrder = sortOrder.value;

    let newSortOrder = 'asc';
    if (currentSortBy === column) {
        newSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
    }

    sortBy.value = column;
    sortOrder.value = newSortOrder;

    document.getElementById('filterForm').submit();
}

// ============================================
// TOGGLE CATEGORY ROWS
// ============================================
function toggleCategoryRows(categoryId) {
    const rows = document.querySelectorAll(`.department-row.category-${categoryId}`);
    const icon = document.getElementById(`icon-${categoryId}`);

    rows.forEach(row => {
        if (row.style.display === 'none') {
            row.style.display = '';
            icon.className = 'bi bi-chevron-up';
        } else {
            row.style.display = 'none';
            icon.className = 'bi bi-chevron-down';
        }
    });

    // Update selection UI after toggle
    updateSelectionUI();
}

// ============================================
// EVENT LISTENERS
// ============================================
function setupEventListeners() {
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'a') {
            e.preventDefault();
            document.getElementById('selectAll')?.click();
        }
        if (e.key === 'Escape') {
            clearSelection();
        }
    });
}

function setupFilterInputs() {

    const form = document.getElementById('filterForm');

    const categoryInput = document.getElementById('categorySearch');
    const categoryId = document.getElementById('categoryId');
    const categoryOptions = document.querySelectorAll('#categoryList option');

    const deptInput = document.getElementById('departmentSearch');
    const deptId = document.getElementById('departmentId');
    const deptOptions = document.querySelectorAll('#departmentList option');

    // Category filter
    categoryInput.addEventListener('change', function() {
        const match = [...categoryOptions].find(o => o.value === this.value);
        categoryId.value = match ? match.dataset.id : '';
        deptId.value = '';
        form.submit();
    });

    // Department filter
    deptInput.addEventListener('change', function() {
        const match = [...deptOptions].find(o => o.value === this.value);

        if (match) {
            deptId.value = match.dataset.id;
            categoryId.value = match.dataset.categoryId;
        } else {
            deptId.value = '';
        }
        form.submit();
    });
}
// ============================================
// MODAL FUNCTIONS
// ============================================
function openEditModal(id, name, description) {
    document.getElementById('edit_department_id').value = id;
    document.getElementById('edit_department_name').value = name;
    document.getElementById('edit_department_description').value = description;
    new bootstrap.Modal(document.getElementById('editDepartmentModal')).show();
}

function openDeleteModal(id, name) {
    document.getElementById('delete_department_id').value = id;
    document.getElementById('delete_department_name').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteDepartmentModal')).show();
}

// ============================================
// AJAX FORMS
// ============================================
$(document).ready(function() {
    // Edit Department Form
    $('#editDepartmentForm').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('departments.edit') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            success: function(data) {
                if (data.success) {
                    $('#editDepartmentModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Department updated successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message
                    });
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong!'
                });
            }
        });
    });

    // Delete Department Form
    $('#deleteDepartmentForm').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('departments.delete') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            success: function(data) {
                if (data.success) {
                    $('#deleteDepartmentModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Department deleted successfully',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message
                    });
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong!'
                });
            }
        });
    });
});

// ============================================
// MUTATION OBSERVER
// ============================================
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(mutation => {
        if (mutation.type === 'childList') {
            const newCheckboxes = document.querySelectorAll('.department-checkbox:not(.initialized)');
            if (newCheckboxes.length > 0) {
                newCheckboxes.forEach(cb => {
                    cb.classList.add('initialized');
                    cb.addEventListener('change', function() {
                        if (isSelectAllActive && !this.checked) {
                            document.getElementById('selectAll').checked = false;
                            isSelectAllActive = false;
                            document.getElementById('bulkActionsContainer')?.classList
                                .remove('select-all-active');
                        }
                        updateSelectionUI();
                    });
                });
                updateSelectionUI();
            }
        }
    });
});

// ============================================
// STATS MODAL
// ============================================
function openStatsModal(type)
{
    const modalTitle = document.getElementById('statsModalTitle');

    const categoriesList = document.getElementById('categoriesList');
    const departmentsList = document.getElementById('departmentsList');

    // Hide all first
    categoriesList.style.display = 'none';
    departmentsList.style.display = 'none';

    if(type === 'categories')
    {
        modalTitle.innerHTML = `
            <i class="bi bi-folder-fill me-2"></i>
            Categories List
        `;

        categoriesList.style.display = 'block';
    }
    else if(type === 'departments')
    {
        modalTitle.innerHTML = `
            <i class="bi bi-building me-2"></i>
            Departments List
        `;

        departmentsList.style.display = 'block';
    }

    new bootstrap.Modal(document.getElementById('statsModal')).show();
}

const tableContainer = document.querySelector('.table-responsive');
if (tableContainer) observer.observe(tableContainer, {
    childList: true,
    subtree: true
});
</script>
@endsection