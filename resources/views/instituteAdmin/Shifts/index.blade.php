{{-- resources/views/instituteAdmin/Shifts/index.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<title>Shift Management</title>

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
    
    .status-pending {
        background: var(--warning-gradient);
        color: white;
        border: none;
    }
    
    /* Priority Badges - Enhanced */
    .priority-badge {
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        color: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .priority-high {
        background: var(--danger-gradient);
    }
    
    .priority-medium {
        background: var(--warning-gradient);
    }
    
    .priority-low {
        background: var(--success-gradient);
    }
    
    /* Bulk Actions - Enhanced */
    .bulk-actions-container {
        gap: 10px;
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
    
    .bulk-action-btn.assign {
        background: var(--info-gradient);
    }
    
    .bulk-action-btn.activate {
        background: var(--success-gradient);
    }
    
    .bulk-action-btn.deactivate {
        background: var(--warning-gradient);
    }
    
    .bulk-action-btn.delete {
        background: var(--danger-gradient);
    }
    
    .bulk-action-btn.clear {
        background: linear-gradient(135deg, #64748b, #475569);
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
        /* background: var(--primary-gradient); */
    }

    .filter-form{
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
        margin: 0px;
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
    
    .filter-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
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
        font-size: 14px;
        white-space: nowrap;
        text-decoration: none;
    }
    
    .btn-filter:hover {
        transform: translateY(-3px);
        text-decoration: none;
        /*color: white !important;*/
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
        color: #475569 !important;
        border: 1px solid #e2e8f0;
    }
    
    .btn-filter-secondary:hover {
        background: #e2e8f0;
        color: #475569 !important;
    }
    
    /* Page Header - Enhanced */
    .page-header {
        display: flex;
        flex-wrap: wrap;
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
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        text-decoration: none;
        color: white;
    }
    
    .action-btn:active {
        transform: translateY(-1px);
    }
    
    .action-btn-assign {
        background: var(--info-gradient);
    }
    
    .action-btn-edit {
        background: var(--warning-gradient);
        color: white;
    }
    
    .action-btn-delete {
        background: var(--danger-gradient);
        color: white;
    }
    
    /* Statistics Cards - Enhanced */
    .stat-card {
        background: white;
        border: none;
        border-radius: 16px;
        padding: 16px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.4s;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
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
        /* background: var(--primary-gradient); */
        transition: width 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 20px 35px rgba(67, 97, 238, 0.15);
    }
    
    .stat-card:hover::before {
        width: 8px;
    }
    
    .stat-card .stat-info {
        position: relative;
        z-index: 1;
    }
    
    .stat-card .stat-value {
        font-size: 28px;
        font-weight: 800;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 4px;
    }
    
    .stat-card .stat-label {
        font-size: 14px;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .stat-card .stat-icon {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.2);
        position: relative;
        z-index: 1;
    }
    
    /* Form Card - Enhanced */
    .form-card {
        background: white;
        border-radius: 16px;
        border: none;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
    
    .form-card-header {
        background: var(--primary-gradient);
        color: white;
        padding: 16px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
    }
    
    .form-card-header i {
        font-size: 18px;
    }
    
    .form-card-header .btn-light {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        transition: all 0.3s;
    }
    
    .form-card-header .btn-light:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }
    
    .form-card-body {
        padding: 24px;
    }
    
    /* Badge styles for flexibility */
    .flexibility-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .flexibility-badge.flexible {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #93c5fd;
    }
    
    .flexibility-badge.fixed {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
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
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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
    
    .modal-title.text-danger {
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
    
    /* Dropdown Menu */
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
    
    /* Pagination Enhancement */
    .pagination {
        gap: 5px;
    }
    
    .page-link {
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        color: #475569;
        transition: all 0.3s;
        padding: 8px 12px;
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
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .page-header {
            gap: 15px;
            padding: 20px;
        }
        
        .page-title {
            font-size: 24px;
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

        .stat-card {
            padding: 20px;
        }
        
        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            font-size: 24px;
        }
    }
    
    /* Text colors */
    .text-primary {
        color: var(--primary-color) !important;
    }
    
    .text-success {
        color: #10b981 !important;
    }
    
    .text-info {
        color: #3b82f6 !important;
    }
    
    .text-warning {
        color: #f59e0b !important;
    }
    
    .text-danger {
        color: #dc2626 !important;
    }
    
    .text-muted {
        color: #94a3b8 !important;
    }
    
    .fw-bold {
        font-weight: 600 !important;
    }
    
    .small {
        font-size: 12px;
    }
    
    /* Time display */
    #timeDisplay {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 8px 15px;
        border-radius: 8px;
        display: inline-block;
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

    .spinner {
        width:40px;
        height:40px;
        border:4px solid #ccc;
        border-top-color:#4361ee;
        border-radius:50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    .table-responsive{
        overflow-x: auto;
    }
</style>

<div id="pageLoader" style="
        display:none;
        position:fixed;
        inset:0;
        background:rgba(255,255,255,0.7);
        z-index:9999;
        align-items:center;
        justify-content:center;
    ">
        <div class="spinner"></div>
    </div>

<div class="container-fluid">
    <!-- Page Header - Enhanced -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-clock-history"></i>
            Shift 
        </h1>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('shifts.assignform') }}" class="btn-filter" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); padding: 10px 20px; border-radius: 10px; font-weight: 500; text-decoration: none; transition: all 0.3s;">
                <i class="bi bi-person-plus me-1"></i>Assign Shift
            </a>
            <button class="btn-filter" style="background: white; color: var(--primary-color); border: none; padding: 10px 20px; border-radius: 10px; font-weight: 500; transition: all 0.3s;" onclick="window.location.href='{{ route('manage.shifts.create') }}'">
                <i class="bi bi-plus-circle me-1"></i>Create Shift
            </button>
        </div>
    </div>

    <!-- Statistics Cards - Enhanced -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-info">
                    <div class="stat-value" id="totalShifts">0</div>
                    <div class="stat-label">Total Shifts</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-clock-history"></i>
                </div>                
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-info">
                    <div class="stat-value" id="activeShifts">0</div>
                    <div class="stat-label">Active Shifts</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>                
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-info">
                    <div class="stat-value" id="assignedEmployees">0</div>
                    <div class="stat-label">Assigned Employees</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>                
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-info">
                    <div class="stat-value" id="assignedStudents">0</div>
                    <div class="stat-label">Assigned Students</div>
                </div>            
                <div class="stat-icon">
                    <i class="bi bi-person-badge"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters - Enhanced with Flexibility Filter -->
    <div class="filter-container">
        <form method="GET" class="filter-form" id="filterForm">
            <div class="filter-group">
                <i class="bi bi-search"></i>
                <input
                    type="text"
                    name="search"
                    class="filter-input"
                    list="shiftNames"
                    value="{{ request('search') }}"
                    placeholder="Search shifts..."
                >
                <datalist id="shiftNames">
                    @foreach($shiftNames as $shift)
                        <option value="{{ $shift->shift_name }}"></option>
                    @endforeach
                </datalist>
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

            <!-- NEW: Flexibility Filter -->
            <div class="filter-group">
                <i class="bi bi-sliders2"></i>
                <select name="flexibility" class="filter-input">
                    <option value="">All Types</option>
                    <option value="flexible" {{ request('flexibility') == 'flexible' ? 'selected' : '' }}>Flexible Hours</option>
                    <option value="fixed" {{ request('flexibility') == 'fixed' ? 'selected' : '' }}>Fixed Hours</option>
                </select>
            </div>

            <div class="filter-actions">
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

    <!-- Bulk Actions Container - Enhanced -->
    <div class="bulk-actions-container" id="bulkActionsContainer" style="display: none;">
        <div class="selected-count" id="selectedCount">0 shifts selected</div>
        <div class="d-flex flex-wrap">
            <div class="dropdown">
                <button class="bulk-action-btn download dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <i class="bi bi-download"></i>
                    Download
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0)"
                        onclick="bulkAction('download','excel')">
                            <i class="bi bi-file-earmark-excel text-success me-2"></i>
                            Excel (.xlsx)
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0)"
                        onclick="bulkAction('download','csv')">
                            <i class="bi bi-file-earmark-text text-primary me-2"></i>
                            CSV (.csv)
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0)"
                        onclick="bulkAction('download','pdf')">
                            <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                            PDF
                        </a>
                    </li>
                </ul>
            </div>
            <button class="bulk-action-btn activate" onclick="bulkAction('activate')">
                <i class="bi bi-check-circle"></i>
                Activate
            </button>
            <button class="bulk-action-btn deactivate" onclick="bulkAction('deactivate')">
                <i class="bi bi-x-circle"></i>
                Deactivate
            </button>
            <button class="bulk-action-btn delete" onclick="bulkAction('delete')">
                <i class="bi bi-trash"></i>
                Bulk Delete
            </button>
            <button class="bulk-action-btn clear" onclick="clearSelection()">
                <i class="bi bi-x-lg"></i>
                Clear
            </button>
        </div>
    </div>    

    <!-- Shifts Table - Enhanced -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-clock"></i>
                All Shifts
            </div>
            <div class="d-flex gap-2">
                <span class="badge bg-light text-dark" id="flexibleCount">0 Flexible</span>
                <span class="badge bg-secondary" id="fixedCount">0 Fixed</span>
            </div>
        </div>
        <div class="p-3">
            <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th class="sticky-checkbox" width="40">
                                <input type="checkbox" id="selectAll" class="select-checkbox">
                            </th>
                            <th class="sticky-main sortable" onclick="sortTable('shift_name')">
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
                            <th class="sortable">Weekly Off</th>
                            <!-- NEW: Flexibility Column -->
                            <th class="sortable" onclick="sortTable('flexible_working_hours')">
                                Flexibility
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'flexible_working_hours' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'flexible_working_hours' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
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
                            <th class="text-center d-none">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="shiftTableBody">
                        @forelse($shifts as $shift)
                            <tr id="row-{{ $shift->id }}" class="{{ $shift->is_currently_active ? 'active-row' : '' }}">
                                <td class="sticky-checkbox">
                                    <input type="checkbox" class="shift-checkbox select-checkbox" value="{{ $shift->id }}">
                                </td>
                                <td class="sticky-main fw-bold">{{ $shift->shift_name }}</td>
                                <td>
                                    <span class="priority-badge priority-{{ $shift->priority }}">
                                        {{ ucfirst($shift->priority) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        <div>{{ $shift->start_date ? $shift->start_date->format('d M Y') : '-' }}</div>
                                        <div class="text-muted">to</div>
                                        <div>{{ $shift->end_date ? $shift->end_date->format('d M Y') : 'Ongoing' }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <div>{{ $shift->formatted_start_time }} <span class="d-none">({{ $shift->start_time }})</span></div>
                                        <div class="text-muted">to</div>
                                        <div>{{ $shift->formatted_end_time }} <span class="d-none">({{ $shift->end_time }})</span></div>
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
                                <!-- NEW: Flexibility Column -->
                                <td>
                                    @if($shift->flexible_working_hours)
                                        <span class="flexibility-badge flexible">
                                            <i class="bi bi-sliders2"></i> Flexible
                                        </span>
                                    @else
                                        <span class="flexibility-badge fixed">
                                            <i class="bi bi-lock"></i> Fixed
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">
                                      
                                        <div class="text-primary">👨‍💼Employee: {{ $shift->employees_count }}</div>
                                        <div class="text-info">👨‍🎓:Student: {{ $shift->students_count }}</div>
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
                                        class="action-btn action-btn-assign d-none" title="Assign Shift">
                                            <i class="bi bi-person-plus"></i>
                                            <span class="small">Assign</span>
                                        </a>
                                        <button class="action-btn action-btn-delete d-none" onclick="deleteShift({{ $shift->id }})" title="Delete Shift">
                                            <i class="bi bi-trash"></i>
                                            <span class="small">Delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="15" class="text-center">
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
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Showing {{ $shifts->firstItem() ?? 0 }} to {{ $shifts->lastItem() ?? 0 }}
                    of {{ $shifts->total() }} shifts
                </div>

                @if ($shifts->hasPages())
                <nav>
                    <ul class="pagination mb-0">
                        @if ($shifts->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">Prev</span></li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $shifts->previousPageUrl() }}">Prev</a>
                        </li>
                        @endif

                        @for ($i = 1; $i <= $shifts->lastPage(); $i++)
                            <li class="page-item {{ $shifts->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ $shifts->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        @if ($shifts->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $shifts->nextPageUrl() }}">Next</a>
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
</div>

<!-- Delete Confirmation Modal - Enhanced -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
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
                <button type="button" class="btn-filter btn-filter-primary" id="confirmDeleteBtn" style="background: var(--danger-gradient);">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('filterForm');
    if (!form) return;

    const delay = 500;
    let typingTimer;

    const searchInput = form.querySelector('input[name="search"]');
    const prioritySelect = form.querySelector('select[name="priority"]');
    const statusSelect = form.querySelector('select[name="status"]');
    const flexibilitySelect = form.querySelector('select[name="flexibility"]');

    // 🔍 Auto search (typing)
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                showLoader();
                form.submit();
            }, delay);
        });
    }

    // 🎯 Auto submit on dropdown change
    [prioritySelect, statusSelect, flexibilitySelect].forEach(select => {
        if (select) {
            select.addEventListener('change', () => {
                showLoader();
                form.submit();
            });
        }
    });
    
    // Initialize bulk actions
    updateSelectionUI();
    loadShiftStatistics();
    updateFlexibilityCounts();
});

function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}

// Bulk Selection Management
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const shiftCheckboxes = document.querySelectorAll('.shift-checkbox');

    // Select All functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            shiftCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectionUI();
        });
    }

    // Individual checkbox change
    shiftCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionUI);
    });
});

function updateSelectionUI() {
    const checkboxes = document.querySelectorAll('.shift-checkbox');
    const checked = document.querySelectorAll('.shift-checkbox:checked');

    const bulkActionsContainer = document.getElementById('bulkActionsContainer');
    const selectedCountElement = document.getElementById('selectedCount');
    const selectAllCheckbox = document.getElementById('selectAll');

    const selectedCount = checked.length;
    const totalCount = checkboxes.length;

    // Update count text
    if (selectedCountElement) {
        selectedCountElement.textContent = `${selectedCount} shift(s) selected`;
    }

    // Active state
    if (bulkActionsContainer) {
        if (selectedCount > 0) {
            bulkActionsContainer.style.display = 'flex';
        } else {
            bulkActionsContainer.style.display = 'none';
        }
    }

    // Select-all checkbox state
    if (selectAllCheckbox && totalCount > 0) {
        selectAllCheckbox.checked = selectedCount === totalCount;
        selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < totalCount;
    }
}

function clearSelection() {
    document.querySelectorAll('.shift-checkbox:checked').forEach(cb => {
        cb.checked = false;
    });

    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.checked = false;
        selectAll.indeterminate = false;
    }

    updateSelectionUI();
}

function bulkAction(action, format=null) {
    const selectedShifts = Array.from(document.querySelectorAll('.shift-checkbox:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedShifts.length === 0) {
        showAlert('Please select at least one shift.', 'warning');
        return;
    }
    
    switch(action) {
        case 'download':
            if (!format) {
                showAlert('Please select a format', 'warning');
                return;
            }
            const ids = selectedShifts.join(',');
            const url = `/shift/download?ids=${ids}&type=${format}`;
            window.location.href = url;
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
    showLoader();
    
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
        const loader = document.getElementById('pageLoader');
        if (loader) loader.style.display = 'none';
    });
}

function deleteShifts(shiftIds) {
    showLoader();
    
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
            updateFlexibilityCounts();
            clearSelection();
            
            // Show empty state if no rows left
            const tbody = document.querySelector('.erp-table tbody');
            const rows = tbody.querySelectorAll('tr:not(.empty-state)');
            if (rows.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="15" class="text-center">
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
        const loader = document.getElementById('pageLoader');
        if (loader) loader.style.display = 'none';
    });
}

// Load statistics
function loadShiftStatistics() {
    const shifts = @json($shifts->items());
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

// Update flexibility counts
function updateFlexibilityCounts() {
    const shifts = @json($shifts->items());
    const flexibleCount = shifts.filter(shift => shift.flexible_working_hours).length;
    const fixedCount = shifts.length - flexibleCount;
    
    const flexibleEl = document.getElementById('flexibleCount');
    const fixedEl = document.getElementById('fixedCount');
    
    if (flexibleEl) flexibleEl.textContent = `${flexibleCount} Flexible`;
    if (fixedEl) fixedEl.textContent = `${fixedCount} Fixed`;
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
    
    showLoader();
    document.getElementById('filterForm').submit();
}

// Delete single shift
function deleteShift(id) {
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
    
    document.getElementById('confirmDeleteBtn').onclick = function() {
        const btn = this;
        const originalText = btn.textContent;
        btn.innerHTML = '<span class="loading-spinner"></span> Deleting...';
        btn.disabled = true;
        
        showLoader();
        
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
                updateFlexibilityCounts();
                deleteModal.hide();
                showAlert('Shift deleted successfully!', 'success');
                
                // Show empty state if no rows left
                const tbody = document.querySelector('.erp-table tbody');
                const rows = tbody.querySelectorAll('tr:not(.empty-state)');
                if (rows.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="15" class="text-center">
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
            const loader = document.getElementById('pageLoader');
            if (loader) loader.style.display = 'none';
        });
    };
}

// Utility function to show alerts
function showAlert(message, type) {
    // Remove existing alerts
    document.querySelectorAll('.alert-dismissible').forEach(alert => {
        if (alert.parentNode) alert.remove();
    });
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.style.cssText = `
        position: fixed; top: 20px; right: 20px; z-index: 9999;
        max-width: 450px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        animation: slideIn 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    `;
    
    let icon = '';
    switch(type) {
        case 'success':
            icon = 'check-circle-fill';
            break;
        case 'warning':
            icon = 'exclamation-triangle-fill';
            break;
        case 'danger':
            icon = 'exclamation-circle-fill';
            break;
        case 'info':
            icon = 'info-circle-fill';
            break;
        default:
            icon = 'info-circle-fill';
    }
    
    alertDiv.innerHTML = `
        <i class="bi bi-${icon} fs-5"></i>
        <span>${message}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" style="margin-left: auto;"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Add slideIn animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }
`;
document.head.appendChild(style);
</script>

@endsection