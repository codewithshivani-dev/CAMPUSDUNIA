@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<title>View Designations</title>
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --primary-color: #4361ee;
    --secondary-color: #3a0ca3;
    --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #ccc;
    border-top-color: var(--primary-color);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
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
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border: none;
}

.status-inactive {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border: none;
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
    transition: all 0.2s;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    padding-left: 25px;
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

.filter-container span.small {
    color: var(--primary-color);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-form {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
}

.filter-grid {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    flex: 1;
}

.filter-group {
    position: relative;
    min-width: 250px;
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
}

.btn-filter:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    text-decoration: none;
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

/* Header - Enhanced but without animations */
.page-header {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding: 15px 15px;
    background: var(--primary-gradient);
    border-radius: 16px;
    box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
}

.page-title {
    /*font-size: 28px;*/
    font-weight: 700;
    color: white;
    margin: 0;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

.add-btn {
    /*padding: 10px 20px;*/
    background: var(--primary-gradient);
    border: none;
    color: white;
    cursor: pointer;
    border-radius: 12px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    text-decoration: none;
}



.add-btn i {
    font-size: 18px;
}

/* Action Buttons - Enhanced */
.table-actions {
    display: flex;
    gap: 5px;
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
    display: flex;
    align-items: center;
    gap: 5px;
    justify-content: center;
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
}

.action-btn:active {
    transform: translateY(-1px);
}

.action-btn-view {
    background: linear-gradient(135deg, #e0f2fe, #bae6fd);

}

/* Role Tags - Enhanced */
.role-tag {
    display: inline-block;
    background: linear-gradient(135deg, #4361ee, #3a0ca3);
    color: white;
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 12px;
    margin: 4px;
    font-weight: 500;
    box-shadow: 0 4px 10px rgba(67, 97, 238, 0.2);
}

/* Loading Animation */
.loading-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
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

/* Alert Messages - Enhanced */
.alert {
    border: none;
    border-radius: 12px;
    padding: 15px 20px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
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

/* Modal Enhancements */
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

.modal-title.text-primary {
    color: white !important;
}

.modal-header .btn-close {
    background: rgba(255, 255, 255, 0.2);
    opacity: 1;
    border-radius: 50%;
    padding: 8px;
    transition: all 0.3s;
}

.modal-header .btn-close:hover {
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

.modal-footer .btn {
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 500;
}

.modal-footer .btn-primary {
    background: var(--primary-gradient);
    border: none;
}

.modal-footer .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
}

.modal-footer .btn-secondary {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    color: #475569;
}

.modal-footer .btn-secondary:hover {
    background: #e2e8f0;
    transform: translateY(-2px);
}

/* Card Styles for View Modal */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: all 0.3s;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.card.bg-light {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9) !important;
}

.card-title {
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 15px;
}

/* Pagination Enhancement */
.pagination {
    gap: 5px;
}

.page-link {
    border-radius: 8px;
    border: none;
    color: #475569;
    transition: all 0.3s;
}

.page-link:hover {
    background: var(--primary-gradient);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
}

.page-item.active .page-link {
    background: var(--primary-gradient);
    border-color: transparent;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .filter-form {
        flex-direction: column;
        gap: 10px;
    }

    .filter-grid {
        width: 100%;
    }

    .filter-actions {
        width: 100%;
        justify-content: space-between;
    }

    .bulk-actions-container {
        flex-direction: column;
        align-items: stretch;
    }

    .bulk-actions-container .d-flex {
        flex-wrap: wrap;
        gap: 8px;
    }

    .table-actions {
        flex-direction: column;
        gap: 4px;
    }

    .action-btn {
        width: 100%;
    }
    
    .page-header {
        /*align-items: flex-start;*/
        gap: 15px;
    }
    
    .add-btn {
        width: 100%;
        justify-content: center;
    }
}
.table-responsive{
    overflow-x: hidden;
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
    <div class="page-header">
        <h4 class="page-title"><i class="bi bi-briefcase-fill me-2"></i>View Designations</h4>
        <a href="{{ route('designations.create') }}" class="btn add-btn" style="text-decoration: none;color:white !important;">
            <i class="bi bi-plus-circle"></i>
            Add Designation
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
        <span class="small"><i class="bi bi-funnel-fill me-2"></i>* Type or Select any to Search</span>
        <form method="GET" class="filter-form mt-3" id="filterForm">
            <div class="filter-grid">

                {{-- Designation --}}
                <div class="filter-group">
                    <i class="bi bi-briefcase"></i>

                    {{-- visible field (name) --}}
                    <input list="designationList" class="filter-input auto-filter" id="designationInput"
                        name="designation_name" placeholder="Search Designation"
                        value="{{ request('designation_name') }}" oninput="applyDesignationFilter(this)">

                    {{-- hidden field (id sent to backend) --}}
                    <input type="hidden" name="designation_id" id="designation_id">

                    <datalist id="designationList">
                        @foreach($designations as $designation)
                        <option value="{{ $designation->designations }}" data-id="{{ $designation->designation_id }}">
                            @endforeach
                    </datalist>
                </div>

                {{-- Department Category --}}
                <div class="filter-group d-none">
                    <i class="bi bi-diagram-3"></i>

                    <input list="categoryList" id="category_name" class="filter-input auto-filter"
                        placeholder="Department Category" value="{{ old('category_name') }}" name="" autocomplete="off"
                        oninput="setCategoryId(this.value)">

                    <input type="hidden" name="department_category_id" id="department_category_id"
                        value="{{ request('department_category_id') }}">

                    <datalist id="categoryList">
                        @foreach($categories as $category)
                        <option value="{{ $category->category_name }}"
                            data-id="{{ $category->department_category_id }}">
                            @endforeach
                    </datalist>
                </div>

                {{-- Role --}}
                <div class="filter-group d-none">
                    <i class="bi bi-person-badge"></i>
                    <input list="roleList" name="role" class="filter-input auto-filter" value="{{ request('role') }}"
                        placeholder="Role">
                    <datalist id="roleList">
                        @foreach($roles as $role)
                        <option value="{{ $role->name }}">
                            @endforeach
                    </datalist>
                </div>


                {{-- Status --}}
                <div class="filter-group d-none">
                    <i class="bi bi-toggle-on"></i>
                    <select name="status" class="filter-input auto-filter">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                        </option>
                    </select>
                </div>

            </div>

            <div class="filter-actions">
                <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary">
                    <i class="bi bi-x-circle"></i> Reset Filters
                </a>
            </div>
        </form>

        {{-- Hidden sort --}}
        <input type="hidden" name="sort_by" value="{{ request('sort_by', 'designations') }}">
        <input type="hidden" name="sort_order" value="{{ request('sort_order', 'asc') }}">

    </div>


    {{-- Bulk Actions Container --}}
    <div class="bulk-actions-container active d-none" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 designations selected</div>
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
            <button class="bulk-action-btn clear mr-0" onclick="clearSelection()">
                <i class="bi bi-x-lg"></i>
                Clear
            </button>
        </div>
    </div>

    {{-- Designations Table --}}
    <div class="table-responsive custom-table-wrapper" id="tableWrapper">
        <table class="erp-table">
            <thead>
                <tr>
                    <th width="40 " class="d-none">
                        <input type="checkbox" id="selectAll" class="select-checkbox">
                    </th>
                    <th class="sticky-main-2 sortable" onclick="sortTable('designations')">
                        Designation
                        <div class="sort-icons">
                            <i
                                class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'designations' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i
                                class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'designations' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('category_name')">
                        Category
                        <div class="sort-icons">
                            <i
                                class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'category_name' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i
                                class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'category_name' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('roles')">
                        Role
                        <div class="sort-icons">
                            <i
                                class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'roles' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i
                                class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'roles' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('status')">
                        Status
                        <div class="sort-icons">
                            <i
                                class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'status' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i
                                class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'status' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('created_at')">
                        Created
                        <div class="sort-icons">
                            <i
                                class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'created_at' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i
                                class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'created_at' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="text-center d-none">Actions</th>
                </tr>
            </thead>

            <tbody id="designationsContainer">
                @forelse($designations as $emp)
                <tr>
                    <td class="d-none">
                        <input type="checkbox" class="designation-checkbox select-checkbox" value="{{ $emp->id }}">
                    </td>
                    <td class="sticky-main-2">
                        <span style="font-weight: 500;">{{ $emp->designations }}</span>
                            @if($emp->designation_id)
                        @endif
                        <br>
                        <span class="badge bg-info ms-2 text-white" style="font-size: 12px;">
                            <i class="bi bi-people-fill"></i> @php
                                $total = $emp->employees_count ?? $emp->employees()->count();
                                $active = $emp->active_employees_count ?? 0;
                            @endphp
                            {{ $total }} Employee(s)
                        </span>
                    </td>
                    <td>
                        {{ $emp->departmentCategory->category_name }}
                            @if($emp->department_category_id)
                        @endif

                    </td>
                    <td>
                        @if($emp->roles)
                        @php
                        $roles = $emp->roles;
                        if (is_string($roles)) {
                        try {
                        $roles = json_decode($roles, true) ?? [$roles];
                        } catch (Exception $e) {
                        $roles = [$roles];
                        }
                        }
                        if (!is_array($roles)) {
                        $roles = [$roles];
                        }
                        @endphp
                        @foreach(array_slice($roles, 0, 2) as $role)
                        <span class="role-tag">{{ $role }}</span>
                        @endforeach
                        @if(count($roles) > 2)
                        <span class="role-tag" style="background: linear-gradient(135deg, #64748b, #475569);">+{{ count($roles) - 2 }}</span>
                        @endif
                        @else
                        <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @php
                        $statusClass = $emp->status === 'active' ? 'status-active' : 'status-inactive';
                        $statusText = ucfirst($emp->status);
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                    </td>
                    <td>{{ $emp->created_at->format('d M Y') }}</td>
                    <td class="text-center d-none">
                        <div class="table-actions">
                            <div>
                                <button class="action-btn action-btn-view"
                                    onclick="viewDesignation('{{ $emp->id }}')" title="View Details">
                                    <div>
                                        <i class="fa-regular fa-eye"></i>
                                    </div>
                                    <div>
                                        <span class="small">View</span>
                                    </div>
                                </button>

                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <h4 style="color: #1e293b; margin-bottom: 10px;">No designations found</h4>
                            <p class="text-muted">Try adjusting your filters or add a new designation</p>
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

    {{-- Pagination --}}
    @if(method_exists($designations, 'hasPages') && $designations->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-sm text-gray-600">
            @if($designations->total() > 0)
            Showing {{ $designations->firstItem() ?? 0 }} to {{ $designations->lastItem() ?? 0 }} of
            {{ $designations->total() }} results
            @else
            Showing 0 results
            @endif
        </div>
        <div>
            {{ $designations->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
        </div>
    </div>
    @elseif($designations->count() > 0)
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-sm text-gray-600">
            Showing {{ $designations->count() }} results
        </div>
    </div>
    @endif


</div>

<!-- View Designation Modal -->
<div class="modal fade" id="viewDesignationModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-tie me-2"></i>Designation Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewDesignationBody">
                <!-- Content will be loaded via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('designations.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i>Create New Designation
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}

function setDesignationId(name) {
    const options = document.querySelectorAll('#designationList option');
    let foundId = '';

    options.forEach(option => {
        if (option.value === name) {
            foundId = option.dataset.id;
        }
    });

    document.getElementById('designation_id').value = foundId;
}

function setCategoryId(name) {
    const options = document.querySelectorAll('#categoryList option');
    let foundId = '';

    options.forEach(option => {
        if (option.value === name) {
            foundId = option.dataset.id;
        }
    });

    document.getElementById('department_category_id').value = foundId;
}

function applyDesignationFilter(input) {
    const options = document.querySelectorAll('#designationList option');
    let designationId = '';

    options.forEach(option => {
        if (option.value === input.value) {
            designationId = option.dataset.id;
        }
    });

    document.getElementById('designation_id').value = designationId;
}

function applyCategoryFilter(input) {
    const options = document.querySelectorAll('#categoryList option');
    let categoryId = '';

    options.forEach(option => {
        if (option.value === input.value) {
            categoryId = option.dataset.id;
        }
    });

    document.getElementById('department_category_id').value = categoryId;
}

document.addEventListener('DOMContentLoaded', function() {

    const categoryInput = document.getElementById('category_name');
    const form = document.getElementById('filterForm');
    const autoFilters = document.querySelectorAll('.auto-filter');
    let debounceTimer;

    // Restore ID if page reload has a category name
    if (categoryInput && categoryInput.value) {
        setCategoryId(categoryInput.value);
    }

    autoFilters.forEach(input => {
        input.addEventListener('input', autoSubmit);
        input.addEventListener('change', autoSubmit);
    });


    function autoSubmit() {

        // Always update category ID first
        if (categoryInput) {
            setCategoryId(categoryInput.value);
        }

        // Prevent submit if ID does not match any category
        const categoryId = document.getElementById('department_category_id').value;

        // If user is still typing an invalid value, DO NOT submit
        if (categoryInput && categoryInput.value !== '' && categoryId === '') {
            return; // stop auto-submit
        }

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {

            showLoader();

            form.submit();
        }, 500);
    }
    restoreDesignationFromId();
});

// Bulk Selection Management
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const designationCheckboxes = document.querySelectorAll('.designation-checkbox');
    const selectedCountElement = document.getElementById('selectedCount');

    // Select All functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            designationCheckboxes.forEach(cb => {
                cb.checked = this.checked;
            });
            updateSelectionUI();

        });
    }

    // Individual checkbox change
    designationCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectionUI);
    });

    function updateSelectionUI() {
        const checkedBoxes = document.querySelectorAll('.designation-checkbox:checked');
        const selectedCount = checkedBoxes.length;

        // ðŸ”¹ Just update count text
        selectedCountElement.textContent =
            selectedCount > 0 ?
            `${selectedCount} designation(s) selected` :
            'No designations selected';

        // ðŸ”¹ Update Select All state
        if (selectAllCheckbox) {
            selectAllCheckbox.checked =
                selectedCount === designationCheckboxes.length && selectedCount > 0;

            selectAllCheckbox.indeterminate =
                selectedCount > 0 && selectedCount < designationCheckboxes.length;
        }
    }
});

function clearSelection() {
    document.querySelectorAll('.designation-checkbox').forEach(cb => {
        cb.checked = false;
    });

    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.checked = false;
        selectAll.indeterminate = false;
    }

    document.getElementById('selectedCount').textContent = 'No designations selected';
}

function bulkAction(action, format = null) {
    const selectedCheckboxes = Array.from(document.querySelectorAll('.designation-checkbox:checked'))
        .map(checkbox => checkbox.value);

    if (selectedCheckboxes.length === 0) {
        alert('Please select at least one designation.');
        return;
    }

    switch (action) {
        case 'download':

            if (!format) {
                alert("Please select a format");
                return;
            }

            const ids = selectedCheckboxes.join(',');

            const url = `/designations/download?ids=${ids}&type=${format}`;

            window.location.href = url;

            break;

        case 'bulk_delete':
            if (confirm(
                    `Are you sure you want to delete ${selectedCheckboxes.length} designation(s)? This action cannot be undone.`
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
                    alert(`${selectedCheckboxes.length} designations deleted successfully`);
                }, 1500);
            }
            break;

        default:
            alert(`${action} action triggered for ${selectedCheckboxes.length} designations`);
    }
}

function restoreDesignationFromId() {
    const designationId = document.getElementById('designation_id')?.value;
    const designationInput = document.getElementById('designationInput');
    const options = document.querySelectorAll('#designationList option');

    if (!designationId || !designationInput) return;

    options.forEach(option => {
        if (option.dataset.id === designationId) {
            designationInput.value = option.value;
        }
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
        const response = await fetch(`/designations/search?search_term=${encodeURIComponent(searchTerm)}`);
        const result = await response.json();

        if (result.success) {
            if (result.designations.length > 0) {
                let html = '';
                result.designations.forEach(designation => {
                    let roles = designation.roles;
                    if (typeof roles === 'string') {
                        try {
                            roles = JSON.parse(roles);
                        } catch (e) {
                            roles = [roles];
                        }
                    }
                    if (!Array.isArray(roles)) {
                        roles = [roles];
                    }

                    html += `
                                <tr>
                                    <td>
                                        <input type="checkbox" class="designation-checkbox select-checkbox" value="${designation.id}">
                                    </td>
                                    <td>
                                        ${designation.designations}
                                        ${designation.designation_id ? '<br><span class="small text-primary">' + designation.designation_id + '</span>' : ''}
                                    </td>
                                    <td>${designation.department_category?.category_name || 'Uncategorized'}</td>
                                    <td>
                                        ${roles.map(role => `<span class="role-tag">${role}</span>`).join(' ')}
                                    </td>
                                    <td>
                                        <span class="status-badge ${designation.status === 'active' ? 'status-active' : 'status-inactive'}">
                                            ${designation.status.charAt(0).toUpperCase() + designation.status.slice(1)}
                                        </span>
                                    </td>
                                    <td>${new Date(designation.created_at).toLocaleDateString('en-GB')}</td>
                                    <td class="text-center">
                                        <div class="table-actions">
                                            <button class="action-btn action-btn-view" onclick="viewDesignation('${designation.id}')" title="View Details">
                                                <i class="fa-regular fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            `;
                });
                document.getElementById('designationsContainer').innerHTML = html;
            } else {
                document.getElementById('designationsContainer').innerHTML = `
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <h4>No designations found</h4>
                                        <p>Try adjusting your search term</p>
                                    </div>
                                </td>
                            </tr>
                        `;
            }
        }
    } catch (error) {
        console.error('Error searching designations:', error);
    }
});

// View Designation Function
async function viewDesignation(id) {
    try {
        const response = await fetch(`/designations/${id}/details`);
        const result = await response.json();

        if (result.success) {
            const designation = result.designation;

            // Fix: Handle roles whether they come as string or array
            let roles = designation.roles;
            if (typeof roles === 'string') {
                try {
                    roles = JSON.parse(roles);
                } catch (e) {
                    roles = [roles];
                }
            }
            if (!Array.isArray(roles)) {
                roles = [roles];
            }

            let html = `
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <h4 class="text-primary mb-2">${designation.designations}</h4>
                                <p class="text-muted mb-4">
                                    <i class="fas fa-tag me-2"></i>
                                    ${designation.department_category ? designation.department_category.category_name : 'Uncategorized'}
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <span class="status-badge ${designation.status === 'active' ? 'status-active' : 'status-inactive'} fs-6">
                                    ${designation.status.charAt(0).toUpperCase() + designation.status.slice(1)}
                                </span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-align-left me-2"></i>Description
                                        </h6>
                                        <p class="card-text mb-0">${designation.description || 'No description provided'}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-id-card me-2"></i>Designation ID
                                        </h6>
                                        <p class="card-text">
                                            <code class="fs-6">${designation.designation_id || 'N/A'}</code>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-user-shield me-2"></i>Assigned Role
                                        </h6>
                                        <div class="d-flex flex-wrap gap-2">
                    `;

            // Now roles is guaranteed to be an array
            roles.forEach(role => {
                html += `<span class="role-tag">${role}</span>`;
            });

            html += `
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-calendar-plus me-2"></i>Created Date
                                        </h6>
                                        <p class="card-text mb-0">
                                            ${new Date(designation.created_at).toLocaleDateString('en-US', { 
                                                year: 'numeric', 
                                                month: 'long', 
                                                day: 'numeric' 
                                            })}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-calendar-check me-2"></i>Last Updated
                                        </h6>
                                        <p class="card-text mb-0">
                                            ${new Date(designation.updated_at).toLocaleDateString('en-US', { 
                                                year: 'numeric', 
                                                month: 'long', 
                                                day: 'numeric' 
                                            })}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

            document.getElementById('viewDesignationBody').innerHTML = html;
            new bootstrap.Modal(document.getElementById('viewDesignationModal')).show();
        } else {
            alert(result.message || 'Failed to load designation details');
        }
    } catch (error) {
        console.error('Error loading designation:', error);
        alert('Failed to load designation details');
    }
}
</script>
@endsection