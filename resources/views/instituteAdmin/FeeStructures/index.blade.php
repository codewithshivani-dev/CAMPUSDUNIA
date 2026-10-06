@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Hostel Fee Structure Management</title>
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
        text-shadow: 0 0 8px rgba(255,255,255,0.5);
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
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    
    .status-badge:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
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
    
    /* Hostel Type Badges - Enhanced */
    .hostel-type-badge {
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        text-transform: capitalize;
        display: inline-block;
        color: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .type-boys {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }
    
    .type-girls {
        background: linear-gradient(135deg, #ec4899, #db2777);
    }
    
    .type-coed {
        background: linear-gradient(135deg, #10b981, #059669);
    }
    
    /* Fee Details Styling */
    .fee-details {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    
    .fee-item {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        padding: 4px 8px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 30px;
    }
    
    .fee-label {
        font-weight: 600;
        color: #475569;
    }
    
    .fee-value {
        font-weight: 700;
        color: var(--primary-color);
    }
    
    .fee-amount {
        font-weight: 700;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 16px;
    }
    
    .fee-reference-id {
        font-family: monospace;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        padding: 5px 5px;
        border-radius: 6px;
        font-size: 12px;
        color: #475569;
        font-weight: 500;
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
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
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
    
    .selected-count {
        font-weight: 600;
        color: var(--primary-color);
        margin-right: auto;
        font-size: 14px;
        background: white;
        padding: 6px 12px;
        border-radius: 30px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
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
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
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
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
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
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
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
        align-items: center;
        flex-wrap: wrap;
        margin: 0;
    }

    .filter-grid {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        flex: 1;
    }

    .filter-group {
        position: relative;
        min-width: 200px;
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
        margin-left: 15px;
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
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
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
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
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
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Pagination styling - Enhanced */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 2px solid #e2e8f0;
    }
    
    .pagination-info {
        font-size: 14px;
        color: #64748b;
    }
    
    .pagination {
        gap: 5px;
    }
    
    .page-link {
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        color: #475569;
        padding: 8px 14px;
        transition: all 0.3s;
    }
    
    .page-link:hover {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
    }
    
    .page-item.active .page-link {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white;
    }
    
    /* Modal Styles - Enhanced */
    .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        overflow: hidden;
    }
    
    .modal-header {
        background: var(--primary-gradient);
        border-bottom: none;
        padding: 20px 25px;
    }
    
    .modal-title {
        font-weight: 600;
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 18px;
    }
    
    .modal-header .btn-close {
        background: rgba(255,255,255,0.2);
        opacity: 1;
        border-radius: 50%;
        padding: 8px;
        transition: all 0.3s;
    }
    
    .modal-header .btn-close:hover {
        background: rgba(255,255,255,0.3);
        transform: rotate(90deg);
    }
    
    .modal-body {
        padding: 25px;
    }
    
    .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 20px 25px;
    }
    
    .modal .card {
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s;
    }
    
    .modal .card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.1);
    }
    
    .modal .card-body {
        padding: 20px;
    }
    
    .modal .card-title {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .modal .alert-primary {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border: 1px solid #93c5fd;
        color: #1e40af;
        border-radius: 12px;
    }
    
    .modal .bg-light {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9) !important;
        border-radius: 12px;
    }
    
    /* Page Loader */
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
    .fw-bold {
        font-weight: 600 !important;
    }
    
    .text-muted {
        color: #94a3b8 !important;
    }
    
    .text-primary {
        color: var(--primary-color) !important;
    }
    
    .text-success {
        color: var(--success-color) !important;
    }
    
    .text-warning {
        color: #f59e0b !important;
    }
    
    .small {
        font-size: 12px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch;
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
            gap: 12px;
        }

        .filter-group {
            min-width: 100%;
        }

        .filter-actions {
            margin-left: 0;
            justify-content: center;
        }

        .bulk-actions-container {
            flex-direction: column;
            align-items: stretch;
        }

        .bulk-actions-container .d-flex {
            flex-wrap: wrap;
            gap: 8px;
        }

        .btn-filter {
            width: 100%;
            justify-content: center;
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
    padding: 0px 20px;
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
            <i class="bi bi-building-fill"></i>
            Hostel Fee Structure
        </h1>
        <a href="{{ route('hostel.fees.create') }}" class="add-btn">
            <i class="bi bi-plus-circle-fill"></i>
            Add New Fee Structure
        </a>
    </div>

    {{-- Messages --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Filters --}}
    <div class="filter-container">
        <h6><i class="bi bi-funnel-fill"></i> *Select or Type to Search</h6>

        <form method="GET" class="filter-form" id="filterForm">
            <div class="filter-grid">

                {{-- Hostel Name --}}
                <div class="filter-group">
                    <i class="bi bi-building-fill"></i>
                    <input type="text" class="filter-input datalist-input" list="hostelNameList"
                        placeholder="Hostel Name" name="hostel_name" value="{{ request('hostel_name') }}">
                    <datalist id="hostelNameList">
                        @foreach($hostelFees->pluck('hostel_name')->unique() as $name)
                        <option value="{{ $name }}"></option>
                        @endforeach
                    </datalist>
                </div>

                {{-- Hostel Type --}}
                <div class="filter-group">
                    <i class="bi bi-people-fill"></i>
                    <input type="text" class="filter-input datalist-input" list="hostelTypeList"
                        placeholder="Hostel Type" name="hostel_type" value="{{ request('hostel_type') }}">
                    <datalist id="hostelTypeList">
                        <option value="boys">
                        <option value="girls">
                        <option value="coed">
                    </datalist>
                </div>

                {{-- Academic Year --}}
                <div class="filter-group">
                    <i class="bi bi-calendar-fill"></i>
                    <input type="text" class="filter-input datalist-input" list="academicYearList"
                        placeholder="Academic Year" name="academic_year" value="{{ request('academic_year') }}">
                    <datalist id="academicYearList">
                        @foreach($hostelFees->pluck('academic_year')->unique() as $year)
                        <option value="{{ $year }}"></option>
                        @endforeach
                    </datalist>
                </div>

                {{-- Status --}}
                <div class="filter-group">
                    <i class="bi bi-info-circle-fill"></i>
                    <input type="text" class="filter-input datalist-input" list="statusList" placeholder="Status"
                        name="status" value="{{ request('status') }}">
                    <datalist id="statusList">
                        <option value="Active"></option>
                        <option value="Inactive"></option>
                    </datalist>
                </div>
            </div>

            <div class="filter-actions">
                <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary">
                    <i class="bi bi-x-circle-fill"></i>
                    Reset
                </a>
            </div>

            {{-- Hidden sorting --}}
            <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at') }}">
            <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">
        </form>
    </div>

    {{-- Bulk Actions Container --}}
    <div class="bulk-actions-container" id="bulkActionsContainer">
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
    
    <div>
        {{-- Hostel Fees Table --}}
        <div class="table-responsive custom-table-wrapper" id="tableWrapper">
            <table class="erp-table">
                <thead>
                    <tr>
                        <th class="sticky-checkbox" width="40">
                            <input type="checkbox" id="selectAll" class="select-checkbox">
                        </th>
                        <th class="sticky-main sortable" onclick="sortTable('hostel_fee_reference_id')">
                            Hostel Name
                            </br>
                            Reference ID
                            <div class="sort-icons">
                                <i
                                    class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'hostel_fee_reference_id' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'hostel_fee_reference_id' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <!--<th class="sortable" onclick="sortTable('hostel_name')">-->
                        <!--    <div class="sort-icons">-->
                        <!--        <i-->
                        <!--            class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'hostel_name' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>-->
                        <!--        <i-->
                        <!--            class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'hostel_name' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>-->
                        <!--    </div>-->
                        <!--</th>-->
                        <th class="sortable" onclick="sortTable('hostel_type')">
                            Type
                            <div class="sort-icons">
                                <i
                                    class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'hostel_type' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'hostel_type' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('academic_year')">
                            Academic Year
                            <div class="sort-icons">
                                <i
                                    class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'academic_year' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'academic_year' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th>Fee Details</th>
                        <th class="sortable" onclick="sortTable('total_fee')">
                            Total Fee
                            <div class="sort-icons">
                                <i
                                    class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'total_fee' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'total_fee' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
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
                        <th class="text-center d-none">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hostelFees as $fee)
                    <tr>
                        <td class="sticky-checkbox">
                            <input type="checkbox" class="fee-checkbox select-checkbox" value="{{ $fee->id }}">
                        </td>
                        <td class="sticky-main">
                            <div class="fw-bold">{{ $fee->hostel_name }}</div>
                            <span class="fee-reference-id">{{ $fee->hostel_fee_reference_id }}</span>
                        </td>
                        <!--<td>-->
                        <!--</td>-->
                        <td>
                            <span class="hostel-type-badge type-{{ $fee->hostel_type }}">
                                {{ ucfirst($fee->hostel_type) }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $fee->academic_year }}</div>
                        </td>
                        <td>
                            <div class="fee-details">
                                <div class="fee-item">
                                    <span class="fee-label">Security Deposit:</span>
                                    <span class="fee-value">₹{{ number_format($fee->security_deposit, 2) }}</span>
                                </div>
                                <div class="fee-item">
                                    <span class="fee-label">Maintenance:</span>
                                    <span class="fee-value">₹{{ number_format($fee->maintenance_fee, 2) }}</span>
                                </div>
                                <div class="fee-item">
                                    <span class="fee-label">Utilities:</span>
                                    <span class="fee-value">₹{{ number_format($fee->utility_charges, 2) }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fee-amount">₹{{ number_format($fee->total_fee, 2) }}</div>
                            <small class="text-muted">per month</small>
                        </td>
                        <td>
                            @if($fee->status)
                            <span class="status-badge status-active">Active</span>
                            @else
                            <span class="status-badge status-inactive">Inactive</span>
                            @endif
                        </td>
                        <td class="text-center d-none">
                            <div class="table-actions">
                                <button class="action-btn action-btn-view d-block" onclick="viewFeeDetails('{{ $fee->id }}')" title="View Details">
                                    <i class="bi bi-eye-fill"></i>
                                    <span class="small">View</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-house-door-fill"></i>
                                </div>
                                <h4>No hostel fee structures found</h4>
                                <p>Try adjusting your filters or add a new fee structure</p>
                                <a href="{{ route('hostel.fees.create') }}" class="btn-filter btn-filter-primary mt-3">
                                    <i class="bi bi-plus-circle-fill"></i> Add New Fee Structure
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
    
        {{-- Pagination --}}
        <div class="pagination-wrapper">
                <div class="pagination-info">
                    Showing {{ $hostelFees->firstItem() }} to {{ $hostelFees->lastItem() }}
                    of {{ $hostelFees->total() }} fees
                </div>
    
                @if ($hostelFees->hasPages())
                <nav>
                    <ul class="pagination mb-0">
    
                        {{-- Previous Page --}}
                        @if ($hostelFees->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">Prev</span></li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $hostelFees->previousPageUrl() }}">Prev</a>
                        </li>
                        @endif
    
                        {{-- Page Numbers --}}
                        @for ($i = 1; $i <= $hostelFees->lastPage(); $i++)
                            <li class="page-item {{ $hostelFees->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ $hostelFees->url($i) }}">{{ $i }}</a>
                            </li>
                            @endfor
    
                            {{-- Next Page --}}
                            @if ($hostelFees->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $hostelFees->nextPageUrl() }}">Next</a>
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

    <!-- Fee Details Modal - Enhanced -->
    <div class="modal fade" id="viewFeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-cash-stack-fill me-2"></i>
                        Fee Structure Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="feeDetailsContent">
                    <!-- Content loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
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
    const feeCheckboxes = document.querySelectorAll('.fee-checkbox');
    const selectedCountElement = document.getElementById('selectedCount');
    const bulkActionsContainer = document.getElementById('bulkActionsContainer');

    // Initialize count display
    updateSelectionCount();

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
        const selectedCount =
            document.querySelectorAll('.fee-checkbox:checked').length;

        selectedCountElement.textContent =
            selectedCount + ' fee structure(s) selected';

        // Show/hide active state based on selection
        if (selectedCount > 0) {
            bulkActionsContainer.classList.add('active');
        } else {
            bulkActionsContainer.classList.remove('active');
        }

        if (feeCheckboxes.length === 0) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
            return;
        }

        selectAllCheckbox.checked = selectedCount === feeCheckboxes.length;
        selectAllCheckbox.indeterminate =
            selectedCount > 0 && selectedCount < feeCheckboxes.length;
    }

    // Make updateSelectionCount globally accessible for manual updates
    window.updateSelectionCount = updateSelectionCount;
});

// Bulk Action Functions
function clearSelection() {
    document.querySelectorAll('.fee-checkbox:checked').forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAll').checked = false;

    // Update count
    if (window.updateSelectionCount) {
        window.updateSelectionCount();
    }
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
            const url = `/hostel-fees/download?ids=${ids}&type=${format}`;
            window.location.href = url;
            break;

        case 'bulk_delete':
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
                    alert(`${selectedFees.length} fee structures deleted successfully`);
                }, 1500);
            }
            break;

        default:
            alert(`${action} action triggered for ${selectedFees.length} fee structures`);
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

// Filter form submission with loading state
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filterForm');
    let timer = null;
    const DELAY = 600;

    document.querySelectorAll('.datalist-input').forEach(input => {
        // Typing debounce
        input.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                showLoader();
                form.submit();
            }, DELAY);
        });

        // Enter key
        input.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                showLoader();
                form.submit();
            }
        });
    });
});

// View Fee Details Function
function viewFeeDetails(feeId) {
    // Show loading
    $('#feeDetailsContent').html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Loading fee details...</p>
        </div>
    `);

    const modal = new bootstrap.Modal(document.getElementById('viewFeeModal'));
    modal.show();

    // Simulate AJAX call - Replace with actual API endpoint
    setTimeout(() => {
        // This is a simulation - replace with actual data from your server
        const feeData = {
            id: feeId,
            reference_id: 'HF-2024-' + feeId,
            hostel_name: 'Sample Hostel',
            hostel_type: 'boys',
            academic_year: '2024-2025',
            security_deposit: 5000,
            maintenance_fee: 2000,
            utility_charges: 1000,
            total_fee: 8000,
            status: true,
            created_at: '2024-01-15',
            updated_at: '2024-01-15'
        };

        const getHostelTypeClass = (type) => {
            switch (type) {
                case 'boys':
                    return 'type-boys';
                case 'girls':
                    return 'type-girls';
                case 'coed':
                    return 'type-coed';
                default:
                    return 'type-boys';
            }
        };

        $('#feeDetailsContent').html(`
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-title"><i class="bi bi-building-fill me-2"></i>Hostel Information</h6>
                            <p><strong>Hostel Name:</strong> <span class="text-primary">${feeData.hostel_name}</span></p>
                            <p><strong>Reference ID:</strong> <span class="fee-reference-id">${feeData.reference_id}</span></p>
                            <p><strong>Hostel Type:</strong> 
                                <span class="hostel-type-badge ${getHostelTypeClass(feeData.hostel_type)}">
                                    ${feeData.hostel_type.charAt(0).toUpperCase() + feeData.hostel_type.slice(1)}
                                </span>
                            </p>
                            <p><strong>Academic Year:</strong> <span class="fw-bold">${feeData.academic_year}</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-title"><i class="bi bi-info-circle-fill me-2"></i>Status Information</h6>
                            <p><strong>Status:</strong> 
                                ${feeData.status ? 
                                    '<span class="status-badge status-active">Active</span>' : 
                                    '<span class="status-badge status-inactive">Inactive</span>'}
                            </p>
                            <p><strong>Created:</strong> ${new Date(feeData.created_at).toLocaleDateString('en-GB')}</p>
                            <p><strong>Last Updated:</strong> ${new Date(feeData.updated_at).toLocaleDateString('en-GB')}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-cash-stack-fill me-2"></i>Fee Breakdown</h6>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded mb-3" style="background: linear-gradient(135deg, #f8fafc, #f1f5f9);">
                                <div class="text-muted mb-2">Security Deposit</div>
                                <div class="h4 text-primary">₹${feeData.security_deposit.toLocaleString()}</div>
                                <small class="text-muted">One-time payment</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded mb-3" style="background: linear-gradient(135deg, #f8fafc, #f1f5f9);">
                                <div class="text-muted mb-2">Monthly Maintenance</div>
                                <div class="h4 text-success">₹${feeData.maintenance_fee.toLocaleString()}</div>
                                <small class="text-muted">Per month</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded mb-3" style="background: linear-gradient(135deg, #f8fafc, #f1f5f9);">
                                <div class="text-muted mb-2">Utility Charges</div>
                                <div class="h4 text-warning">₹${feeData.utility_charges.toLocaleString()}</div>
                                <small class="text-muted">Per month</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-primary mt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Total Monthly Fee:</strong>
                                <div class="text-muted">Including all charges</div>
                            </div>
                            <div class="h3 text-success" style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">₹${feeData.total_fee.toLocaleString()}</div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h6 class="mb-3"><i class="bi bi-calendar-fill me-2"></i>Annual Cost Calculation</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="text-muted mb-2">Quarterly (3 Months)</div>
                                    <div class="h5 text-primary">₹${(feeData.total_fee * 3).toLocaleString()}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="text-muted mb-2">Half Yearly (6 Months)</div>
                                    <div class="h5 text-success">₹${(feeData.total_fee * 6).toLocaleString()}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="text-muted mb-2">Annual (12 Months)</div>
                                    <div class="h5 text-warning">₹${(feeData.total_fee * 12).toLocaleString()}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `);
    }, 1000);
}
</script>
@endsection