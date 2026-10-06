@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<title>Class Management</title>
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --success-gradient: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
        --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
        --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
        --shadow-lg: 0 10px 25px rgba(0,0,0,0.1);
        --shadow-hover: 0 20px 40px rgba(0,0,0,0.15);
    }

    * {
        transition: all 0.3s ease;
    }

    /* Page Header with Gradient */
    .page-header {
        background: var(--primary-gradient);
        padding: 15px 15px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        animation: fadeIn 0.5s ease;
    }

    .page-title {
        font-weight: 600;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-title i {
        background: rgba(255,255,255,0.2);
        padding: 12px;
        border-radius: 12px;
    }

    /* Add New Button */
    .add-btn {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        cursor: pointer;
        border-radius: 50px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        text-decoration: none;
        backdrop-filter: blur(10px);
    }

    .add-btn:hover {
        background: rgba(255,255,255,0.3);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        text-decoration: none;
    }

    /* Alert Messages */
    .alert {
        border: none;
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 20px;
        animation: slideInDown 0.5s ease;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-success {
        background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
        color: #1e7e34;
    }

    .alert-danger {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: #721c24;
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    /* Filter container */
    .filter-container {
        background: white;
        border-radius: 15px;
        padding: 15px 20px;
        margin-bottom: 25px;
        border: 2px solid #e0e0e0;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        animation: slideUp 0.3s ease;
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

    .filter-form {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 15px;
        margin: 0;
    }

    .filter-grid {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filter-group {
        position: relative;
        flex: 1;
        min-width: 200px;
    }

    .filter-group .bi {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #4361ee;
        z-index: 1;
        font-size: 1rem;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 14px;
        background: #fff;
        transition: all 0.3s;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .filter-group input:hover,
    .filter-group select:hover {
        border-color: #3a0ca3;
    }

    .filter-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .btn-filter {
        padding: 12px 25px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        text-decoration: none;
        font-size: 14px;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        text-decoration: none;
    }

    .btn-filter-primary {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
    }

    .btn-filter-primary:hover {
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
        color: white;
    }

    .btn-filter-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 2px solid #e0e0e0;
    }

    .btn-filter-secondary:hover {
        background: #e2e8f0;
    }

    /* Bulk Actions */
    .bulk-actions-container {
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        animation: slideDown 0.3s ease;
        background: linear-gradient(135deg, #f8faff 0%, #f0f4ff 100%);
        padding: 15px 20px;
        border-radius: 12px;
        border: 2px solid #4361ee;
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
        font-weight: 600;
        color: #4361ee;
        margin-right: auto;
        font-size: 14px;
        background: white;
        padding: 6px 15px;
        border-radius: 30px;
        border: 2px solid #4361ee;
    }

    .bulk-action-btn {
        padding: 8px 20px;
        border-radius: 30px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        color: #475569;
        transition: all 0.2s;
        font-size: 14px;
        display: flex;
        align-items: center;
        border: 2px solid transparent;
        margin-right: 5px;
    }

    .bulk-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .bulk-action-btn.download {
        background: #dcfce7;
        color: #166534;
        border: 2px solid #bbf7d0;
    }

    .bulk-action-btn.download:hover {
        background: #bbf7d0;
    }

    .bulk-action-btn.delete {
        background: #fee2e2;
        color: #dc2626;
        border: 2px solid #fecaca;
    }

    .bulk-action-btn.delete:hover {
        background: #fecaca;
    }

    .bulk-action-btn.clear {
        background: transparent;
        color: #64748b;
        border: 2px solid #cbd5e1;
    }

    .bulk-action-btn.clear:hover {
        background: #f1f5f9;
    }

    .bulk-action-btn i {
        margin-right: 8px;
    }

    .dropdown-menu {
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        padding: 8px;
    }

    .dropdown-item {
        font-size: 14px;
        padding: 10px 15px;
        display: flex;
        align-items: center;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .dropdown-item:hover {
        background: linear-gradient(135deg, #f8faff 0%, #f0f4ff 100%);
        color: #4361ee;
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
        padding: 15px 16px;
        font-weight: 700;
        color: white;
        text-align: left;
        font-size: 14px;
        border-bottom: 1px solid #e0e0e0;
        cursor: pointer;
        user-select: none;
        transition: background-color 0.2s;
        position: relative;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .erp-table th:hover {
        background-color: rgba(67, 97, 238, 0.05);
    }

    .erp-table th.sortable {
        padding-right: 35px;
    }

    .sort-icons {
        position: absolute;
        right: 12px;
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
        color: #4361ee;
    }

    .erp-table td {
        padding: 15px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 14px;
        vertical-align: middle;
    }

    .erp-table tbody tr {
        transition: all 0.3s ease;
    }

    .erp-table tbody tr:hover {
        background: linear-gradient(135deg, #f8faff 0%, #f0f4ff 100%);
        /*transform: translateY(-2px);*/
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .erp-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Checkbox styling */
    .select-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
        border-radius: 6px;
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

    /* Badge Styling */
    .badge {
        /*padding: 6px 12px;*/
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge.bg-success {
        background: linear-gradient(135deg, #10b981, #059669) !important;
        color: white;
    }

    .badge.bg-secondary {
        background: linear-gradient(135deg, #94a3b8, #64748b) !important;
        color: white;
    }

    /* Section Styling */
    .fw-bold.mb-1 {
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .fw-bold.mb-1 i {
        color: #4361ee;
    }

    .fw-semibold.small {
        background: #f8fafc;
        padding: 6px 10px;
        border-radius: 8px;
        border-left: 3px solid #4361ee;
    }

    .text-success {
        color: #10b981 !important;
        font-weight: 600;
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 15px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        transition: transform 0.2s;
    }

    .status-badge:hover {
        transform: scale(1.05);
    }

    .status-active {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .status-inactive {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    /* Action Buttons */
    .action-btn {
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 5px;
        justify-content: center;
        min-width: 70px;
    }

    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    .action-btn-edit {
        background: #fef2c8;
        color: #92400e;
        border-color: #fde68a;
    }

    .action-btn-edit:hover {
        background: #fde68a;
    }

    .action-btn-delete {
        background: #fee2e2;
        color: #dc2626;
        border-color: #fecaca;
    }

    .action-btn-delete:hover {
        background: #fecaca;
    }

    .action-btn-view {
        background: #e0f2fe;
        color: #0369a1;
        border-color: #bae6fd;
    }

    .action-btn-view:hover {
        background: #bae6fd;
        color: #0369a1;
        text-decoration: none;
    }

    .custom-gap {
        gap: 8px;
    }

    /* Pagination */
    .d-flex.justify-content-between.align-items-center {
        margin-top: 20px;
        /*padding: 15px 0;*/
    }

    .text-sm.text-gray-600 {
        color: #64748b;
        font-size: 14px;
    }

    .pagination {
        gap: 5px;
    }

    .page-item .page-link {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        color: #475569;
        padding: 8px 12px;
        transition: all 0.3s;
    }

    .page-item.active .page-link {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white;
    }

    .page-item .page-link:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        transform: translateY(-2px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #f8fafc;
        border-radius: 15px;
        border: 2px dashed #e0e0e0;
        animation: fadeIn 0.5s ease;
    }

    .empty-state-icon {
        font-size: 60px;
        color: #cbd5e1;
        margin-bottom: 20px;
    }

    .empty-state h4 {
        color: #475569;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #94a3b8;
        margin-bottom: 20px;
    }

    /* Loading Spinner */
    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #4361ee;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loading-spinner {
        display: inline-block;
        width: 18px;
        height: 18px;
        border: 3px solid rgba(255,255,255,0.3);
        border-top: 3px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 8px;
    }

    /* Side Panel */
    #overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1049;
        backdrop-filter: blur(5px);
    }

    #sidePanel {
        position: fixed;
        top: 0;
        right: -700px;
        width: 650px;
        height: 100%;
        background: #fff;
        z-index: 1050;
        box-shadow: -5px 0 30px rgba(0, 0, 0, 0.3);
        padding: 25px;
        transition: right 0.3s ease-in-out;
        overflow-y: auto;
    }

    #sidePanel.open {
        right: 0;
    }

    #sidePanel h4 {
        color: #2c3e50;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    #sidePanel h4 i {
        color: #4361ee;
    }

    #closePanel {
        background: #f1f5f9;
        border: 2px solid #e0e0e0;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        transition: all 0.3s;
    }

    #closePanel:hover {
        background: #fee2e2;
        border-color: #dc2626;
        color: #dc2626;
        transform: rotate(90deg);
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
        padding: 12px 20px;
        border-radius: 8px 8px 0 0;
        transition: all 0.3s;
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

    .tab-content {
        padding: 20px 0;
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
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
    }

    .modal-title i {
        background: rgba(255,255,255,0.2);
        padding: 8px;
        border-radius: 10px;
    }

    .modal-header .btn-close {
        background: rgba(255,255,255,0.2);
        opacity: 1;
        border-radius: 50%;
        padding: 8px;
    }

    .modal-body {
        padding: 25px;
    }

    .modal-footer {
        border-top: 2px solid #e0e0e0;
        padding: 20px 25px;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
    }

    .form-control {
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 12px 15px;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
    }

    .btn-primary {
        background: var(--primary-gradient);
        border: none;
        border-radius: 12px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
    }

    .btn-secondary {
        background: #f1f5f9;
        border: 2px solid #e0e0e0;
        color: #475569;
        border-radius: 12px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545, #b02a37);
        border: none;
        border-radius: 12px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(220, 53, 69, 0.4);
    }

    .alert-warning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border: none;
        border-radius: 12px;
        color: #92400e;
        font-weight: 500;
        padding: 15px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            gap: 15px;
        }

        .filter-group {
            min-width: 100%;
        }

        .erp-table th,
        .erp-table td {
            padding: 10px 12px;
            font-size: 13px;
        }

        #sidePanel {
            width: 100%;
            right: -100%;
            padding: 15px;
        }

        .bulk-actions-container {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }

        .bulk-actions-container .d-flex {
            justify-content: center;
        }

        .custom-gap {
            flex-direction: column;
            gap: 5px;
        }

        .action-btn {
            width: 100%;
        }
    }
</style>

<div id="pageLoader" style="
    display:none;
    position:fixed;
    inset:0;
    background:rgba(255,255,255,0.9);
    z-index:9999;
    align-items:center;
    justify-content:center;
    backdrop-filter: blur(5px);
">
    <div class="spinner"></div>
</div>

<div class="container-fluid">
    <!-- Page Header with Gradient -->
    <div class="page-header">
        <h4 class="page-title">
            <i class="bi bi-book"></i>
            @php
                $courseLabel = (isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type === 'School')
                    ? 'Class'
                    : 'Class';
            @endphp
            {{ $courseLabel }} Management
        </h4>
        <a href="{{ route('course.basic.form') }}" class="btn add-btn">
            <i class="bi bi-plus-circle"></i>
            Add New {{ $courseLabel == 'Class' ? 'Class' : 'Course' }}
        </a>
    </div>

    <!-- Messages -->
    @if (session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle-fill"></i>
        {{ session('error') }}
    </div>
    @endif

    <!-- Filters Section -->
    <div class="filter-container">
        <form method="GET" class="filter-form" id="filterForm">
            <div class="filter-grid">
                <!-- department -->
                <div class="filter-group">
                    <i class="bi bi-building"></i>
                    <input type="text"
                        class="filter-input"
                        list="departmentList"
                        placeholder="Department"
                        id="departmentInput"
                        value="{{ request('departmentInput') ?? (optional($departments->firstWhere('department_id', request('department_id')))->department ?? '') }}">
                    <input type="hidden" name="department_id" id="departmentId" value="{{ request('department_id') }}">
                    <datalist id="departmentList">
                        @foreach($departments as $dept)
                            <option value="{{ $dept->department }}" data-id="{{ $dept->department_id }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <!-- search names -->
                <div class="filter-group">
                    <i class="bi bi-search"></i>
                    <input type="text"
                        class="filter-input"
                        list="courseTypeList"
                        name="course_type"
                        id="courseTypeInput"
                        placeholder="Class Name"
                        value="{{ request('course_type') }}">
                    <datalist id="courseTypeList">
                        @foreach($courseTypes as $type)
                            <option value="{{ $type->course_type }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <!-- duration -->
                <div class="filter-group">
                    <i class="bi bi-hourglass-split"></i>
                    <input list="courseDurationList" name="duration" class="filter-input"
                        value="{{ request('duration') }}" placeholder="Duration">
                    <datalist id="courseDurationList">
                        @foreach($courseDuration ?? [] as $cd)
                        <option value="{{ $cd->course_duration }}">
                        @endforeach
                    </datalist>
                </div>
            </div>

            <div class="filter-actions">
                <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary">
                    <i class="bi bi-x-circle"></i>
                    Reset Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Bulk Actions Container -->
    <div class="bulk-actions-container d-none" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 selected</div>
        <div class="d-flex flex-wrap">
            <div class="dropdown me-2">
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

    <!-- Courses Table -->
    @if($courses->count() > 0)
    <div class="table-responsive custom-table-wrapper" id="tableWrapper">
        <input type="hidden" id="filteredTotal" value="{{ $courses->total() }}">
        <table class="erp-table" id="coursesTable">
            <thead>
                <tr>
                    <th class="d-none" width="40">
                        <input type="checkbox" id="selectAll" class="select-checkbox">
                    </th>
                    <th class="sticky-main-2 sortable" onclick="sortTable('name')">
                        {{ $courseLabel == 'Classes' ? 'Class' : 'Class' }} 
                        <br>
                        <span class="small">Total students</span>
                        <div class="sort-icons">
                            <i class="sort-icon bi bi-caret-up-fill"></i>
                            <i class="sort-icon bi bi-caret-down-fill"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('department')">
                        Department
                        <div class="sort-icons">
                            <i class="sort-icon bi bi-caret-up-fill"></i>
                            <i class="sort-icon bi bi-caret-down-fill"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('duration')">
                        Duration
                        <div class="sort-icons">
                            <i class="sort-icon bi bi-caret-up-fill"></i>
                            <i class="sort-icon bi bi-caret-down-fill"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('section')">
                        Section
                        <div class="sort-icons">
                            <i class="sort-icon bi bi-caret-up-fill"></i>
                            <i class="sort-icon bi bi-caret-down-fill"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('status')">
                        Status
                        <div class="sort-icons">
                            <i class="sort-icon bi bi-caret-up-fill"></i>
                            <i class="sort-icon bi bi-caret-down-fill"></i>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                <tr class="course-item" 
                    data-category="{{ $course->course->departmentCategory->category_name ?? '' }}"
                    data-department="{{ optional($course->getRelation('department'))->department ?? 'N/A' }}"
                    data-name="{{ $course->sub_type ?? $course->course->finacp_merchant_sub_category_type }}"
                    data-id="{{ $course->id }}"
                    data-course-id="{{ $course->course->finacp_merchant_sub_category_id }}"
                    data-branches="{{ $course->productDetails ? $course->productDetails->count() : 0 }}"
                    data-duration="{{ $course->course_length }} {{ $course->course_duration }}"
                    data-status="{{ $course->status ?? 'active' }}"
                    data-date="{{ $course->created_at ? $course->created_at->timestamp : time() }}">

                    <td class="d-none">
                        <input type="checkbox" class="course-checkbox select-checkbox" value="{{ $course->id }}">
                    </td>
                    <td class="sticky-main-2">
                        <div>
                            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type === 'School')
                                <span class="fw-semibold">{{ $course->sub_type }}</span>
                            @else
                                <span class="fw-semibold">{{ $course->course->finacp_merchant_sub_category_type ?? '' }}</span>
                                <span class="fw-semibold">{{ $course->sub_type }}</span>
                            @endif
                            <br>
                            <span class="badge {{ ($course->student_count ?? 0) > 0 ? 'bg-success' : 'bg-secondary' }}">
                                <i class="bi bi-people-fill me-1"></i>
                                {{ $course->student_count ?? 0 }} Students
                            </span>
                        </div>
                    </td>
                    <td>
                        <span class="fw-semibold">{{ optional($course->getRelation('department'))->department }}</span>
                        <br>
                        <span class="small text-muted">
                            {{ $course->course->departmentCategory->category_name ?? 'N/A' }}
                        </span>
                    </td>
                    <td>
                        @if($course->course_length && $course->course_duration)
                            <span class="fw-semibold">{{ $course->course_length }} {{ $course->course_duration }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if(!empty($course->sections) && count($course->sections) > 0)
                            @foreach($course->sections as $section)
                                @php
                                    $totalSeats = $section['seats'] ?? 0;
                                    $students = $section['student_count'] ?? 0;
                                    $available = $section['available_seats'] ?? 0;
                                @endphp
                                <div class="mb-2">
                                    <div class="fw-bold mb-1">
                                        <i class="bi bi-grid-3x3-gap-fill text-primary me-1"></i>
                                        {{ $section['name'] ?? 'N/A' }}
                                    </div>
                                    <div class="fw-semibold small">
                                        <i class="bi bi-people text-muted me-1"></i>
                                        Capacity: {{ $totalSeats }} | 
                                        <span class="text-danger fw-bold">Occupied: {{ $students }}</span> | 
                                        <span class="text-success fw-bold">Available: {{ $available }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <span class="text-muted">No sections</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $status = $course->status ?? 'active';
                        @endphp
                        <span class="status-badge {{ $status === 'active' ? 'status-active' : 'status-inactive' }}">
                            <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i>
                            {{ ucfirst($status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center">
        <div class="text-sm text-gray-600">
            <i class="bi bi-layout-text-window me-1"></i>
            Showing {{ $courses->firstItem() ?? 0 }} to {{ $courses->lastItem() ?? 0 }} of {{ $courses->total() }} results
        </div>
        <div>
            {{ $courses->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="bi bi-book"></i>
        </div>
        <h4>No {{ $courseLabel }} Found</h4>
        <p>Try adjusting your filters or add a new {{ $courseLabel == 'Classes' ? 'class' : 'course' }}</p>
        <a href="{{ route('course.basic.form') }}" class="btn-filter btn-filter-primary mt-3">
            <i class="bi bi-plus-circle"></i>
            Add New {{ $courseLabel == 'Classes' ? 'Class' : 'Course' }}
        </a>
    </div>
    @endif
</div>

<!-- Overlay -->
<div id="overlay"></div>

<!-- View Course Panel -->
<div id="sidePanel">
    <div class="d-flex justify-content-between align-items-center">
        <h4>
            <i class="bi bi-info-circle"></i>
            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                Class 
            @else
                Course
            @endif Details <span id="courseNameHeading"></span>
        </h4>
        <button id="closePanel" class="btn btn-sm btn-outline-secondary">&times;</button>
    </div>
    <hr>

    <ul class="nav nav-tabs" id="courseTabs" role="tablist">
        <li class="nav-item"><a class="nav-link active" id="basic-tab" data-toggle="tab" href="#tabCourseBasic" role="tab">Basic</a></li>
        <li class="nav-item"><a class="nav-link" id="meta-tab" data-toggle="tab" href="#tabCourseMeta" role="tab">Meta</a></li>
    </ul>

    <div class="tab-content mt-3">
        <div class="tab-pane fade show active" id="tabCourseBasic" role="tabpanel"></div>
        <div class="tab-pane fade" id="tabCourseMeta" role="tabpanel"></div>
    </div>
</div>

<!-- Edit Course Modal -->
<div class="modal fade" id="editCourseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editCourseForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="course_id" id="edit_course_id">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil"></i>
                        Edit {{ $courseLabel == 'Classes' ? 'Class' : 'Course' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-tag me-1 text-primary"></i>
                            {{ $courseLabel == 'Classes' ? 'Class' : 'Course' }} Name *
                        </label>
                        <input type="text" name="sub_type" id="edit_sub_type" class="form-control" required>
                    </div>
                    
                    <!-- Branches Container -->
                    <div class="mb-3">
                        <label class="form-label" id="branchesLabel">
                            <i class="bi bi-code-branch me-1 text-primary"></i>
                            Branches
                        </label>
                        <div id="edit_branches_container"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span id="saveBtnText">Save Changes</span>
                        <span class="loading-spinner d-none" id="saveSpinner"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCourseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete "<span id="courseNameToDelete" class="fw-bold text-danger"></span>"?</p>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    This will also delete all associated branches.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <span id="deleteBtnText">Delete</span>
                    <span class="loading-spinner d-none" id="deleteSpinner"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('click', '.view-course', function() {
        const id = $(this).data('id');
        window.location.href = '/course-details/' + id;
    });
    
    document.addEventListener('DOMContentLoaded', function () {

        const selectAllCheckbox = document.getElementById('selectAll');
        const bulkActionsContainer = document.getElementById('bulkActionsContainer');
        const selectedCountElement = document.getElementById('selectedCount');

        let selectAllFiltersMode = false;
        let updateTimer = null;

        function scheduleUpdate() {
            if (updateTimer) clearTimeout(updateTimer);
            updateTimer = setTimeout(updateSelectionUI, 30);
        }

        // ================= CHECKBOX CHANGE =================
        document.addEventListener('change', function (e) {
            if (!e.target.classList.contains('course-checkbox')) return;

            // ✅ FIXED CONDITION
            if (selectAllFiltersMode && e.target.checked === false) {
                selectAllFiltersMode = false;
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }

            scheduleUpdate();
        });

        // ================= SELECT ALL =================
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function () {
                const filteredTotal =
                    parseInt(document.getElementById('filteredTotal')?.value || 0);

                selectAllFiltersMode = this.checked && filteredTotal > 0;

                document.querySelectorAll('.course-checkbox').forEach(cb => {
                    cb.checked = this.checked;
                    cb.dispatchEvent(new Event('change', { bubbles: true }));
                });

                scheduleUpdate();
            });
        }

        // ================= UI UPDATE =================
        function updateSelectionUI() {
            // Safeguard: if UI elements are missing, do nothing
            if (!bulkActionsContainer || !selectedCountElement) return;

            const checkboxes = document.querySelectorAll('.course-checkbox');
            const selectedCount =
                document.querySelectorAll('.course-checkbox:checked').length;
            const filteredTotal =
                parseInt(document.getElementById('filteredTotal')?.value || 0);

            if (selectAllFiltersMode && filteredTotal > 0) {
                bulkActionsContainer.classList.add('active');
                selectedCountElement.textContent =
                    filteredTotal + ' course(s) selected';

                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.indeterminate = false;
                }
            }
            else if (selectedCount > 0) {
                bulkActionsContainer.classList.add('active');
                selectedCountElement.textContent =
                    selectedCount + ' course(s) selected';

                if (selectAllCheckbox) {
                    selectAllCheckbox.checked =
                        selectedCount === checkboxes.length && checkboxes.length > 0;
                    selectAllCheckbox.indeterminate =
                        selectedCount > 0 && selectedCount < checkboxes.length;
                }
            }
            else {
                bulkActionsContainer.classList.remove('active');
                selectedCountElement.textContent = '0 courses selected';
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                }
            }
        }

        // ================= AUTO-SELECT AFTER FILTER =================

        // Detect if ANY filter is applied via URL params
        const urlParams = new URLSearchParams(window.location.search);

        const filterKeys = [
            'category_id',
            'department_id',
            'course_type',
            'course_duration',
            'sub_type',
            'product_id',
            'search'
        ];

        const filterApplied = filterKeys.some(key => {
            const val = urlParams.get(key);
            return val !== null && val !== '';
        });

        if (filterApplied) {
            const filteredTotal =
                parseInt(document.getElementById('filteredTotal')?.value || 0);

            const visibleCheckboxes =
                document.querySelectorAll('.course-checkbox');

            if (visibleCheckboxes.length > 0 && filteredTotal > 0) {
                visibleCheckboxes.forEach(cb => cb.checked = true);

                selectAllFiltersMode = true;
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;

                updateSelectionUI();
            }
        }


        updateSelectionUI();
    });

    function clearSelection() {
        document.querySelectorAll('.course-checkbox:checked').forEach(checkbox => {
            checkbox.checked = false;
            // Dispatch change to update UI
            checkbox.dispatchEvent(new Event('change', { bubbles: true }));
        });
    }

    function bulkAction(action, format = null) {

        const selectAllCheckbox = document.getElementById('selectAll');
        const selectAllChecked = selectAllCheckbox ? selectAllCheckbox.checked : false;

        let ids = [];
        if (!selectAllChecked) {
            ids = Array.from(document.querySelectorAll('.course-checkbox:checked'))
                .map(cb => cb.value);
        }

        const filterForm = document.getElementById('filterForm');
        let hasFilter = false;

        if (filterForm) {
            filterForm.querySelectorAll('input, select').forEach(input => {
                if (input.value && input.value.trim() !== '') {
                    hasFilter = true;
                }
            });
        }

        if (action === 'download') {

            if (!format) {
                alert("Please select format");
                return;
            }

            const params = new URLSearchParams();
            params.append('type', format);

            // ✅ Priority 1: select all (filtered)
            if (selectAllChecked) {
                const formData = new FormData(filterForm);
                formData.forEach((value, key) => {
                    if (value) params.append(key, value);
                });
                params.append('select_all', 1);
            }
            // ✅ Priority 2: explicit selection
            else if (ids.length > 0) {
                params.append('ids', ids.join(','));
            }
            // ✅ Priority 3: filters only
            else if (hasFilter) {
                const formData = new FormData(filterForm);
                formData.forEach((value, key) => {
                    if (value) params.append(key, value);
                });
                params.append('select_all', 1);
            }
            else {
                alert('Please select courses or apply filters.');
                return;
            }

            window.location.href = `/download-courses?${params.toString()}`;
        }
    }

    $(document).ready(function() {
        let debounceTimer;

        function mapDatalistValue(inputId, hiddenId, datalistId) {
            const input = document.getElementById(inputId);
            const hidden = document.getElementById(hiddenId);
            const list = document.getElementById(datalistId);
            if (!input || !hidden || !list) return false;

            const options = Array.from(list.options);
            const inputVal = (input.value || '').trim().toLowerCase();

            const matchingOption = options.find(opt => (opt.value || '').trim().toLowerCase() === inputVal);

            if (matchingOption) {
                hidden.value = matchingOption.dataset.id || '';
                return true;
            } else {
                // No exact match: clear hidden but keep visible value
                hidden.value = '';
                return false;
            }
        }

        function autoSubmit() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                showLoader();
                $('#filterForm').submit();
            }, 500);
        }

        // Department mapping - FIXED
        $('#departmentInput').on('input', function() {
            const mapped = mapDatalistValue('departmentInput', 'departmentId', 'departmentList');
    
            // Submit if valid option OR if field cleared
            if (mapped || $(this).val().trim() === '') {
                showLoader();
                autoSubmit();
            }
        });

        // Initialize hidden departmentId from existing visible value on load
        mapDatalistValue('departmentInput', 'departmentId', 'departmentList');

        // Make sure the hidden input gets updated before form submission
        $('#filterForm').on('submit', function(e) {
            // Map department value one more time before submission
            mapDatalistValue('departmentInput', 'departmentId', 'departmentList');
            
            // Show loading state
            $('.btn-filter-primary')
                .html('<span class="loading-spinner"></span> Filtering...')
                .prop('disabled', true);
        });

        // Course Type input
        $('#courseTypeInput').on('input', autoSubmit);

        // Duration input
        $('input[list="courseDurationList"]').on('input', autoSubmit);

        // Also handle blur event to ensure mapping happens when user clicks away
        $('#departmentInput').on('blur', function() {
            mapDatalistValue('departmentInput', 'departmentId', 'departmentList');
        });
    });
    
    function showLoader() {
        const loader = document.getElementById('pageLoader');
        if (loader) loader.style.display = 'flex';
    }
</script>
@endsection