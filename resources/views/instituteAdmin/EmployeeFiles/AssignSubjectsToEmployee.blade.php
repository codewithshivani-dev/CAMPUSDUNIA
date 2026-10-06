@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Assigned Subjects to Employees</title>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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

    /* Page Header */
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
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(5px);
        transition: all 0.3s;
        text-decoration: none;
        cursor: pointer;
        font-size: 14px;
    }

    .add-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        text-decoration: none;
        color: white;
    }

    .add-btn i {
        font-size: 18px;
    }

    /* Alert Messages */
    .alert {
        border: none;
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideIn 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
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
        opacity: 0.8;
    }

    /* Filter Container */
    .filter-container {
        background: white;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 25px;
        border: 2px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        animation: slideUp 0.3s ease;
    }

    .filter-form {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 15px;
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
        flex: 1 1 auto;
    }

    .filter-group i {
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
        background: white;
        transition: all 0.3s;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .filter-group input:hover,
    .filter-group select:hover {
        border-color: var(--secondary-color);
    }

    .btn-filter {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        font-size: 14px;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        text-decoration: none;
    }

    .btn-filter-secondary {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        color: #475569;
        border: 2px solid #e2e8f0;
    }

    .btn-filter-secondary:hover {
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        color: #1e293b;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Bulk Actions Container */
    .bulk-actions-container {
        background: white;
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        animation: slideDown 0.3s ease;
        border: 2px solid var(--primary-color);
        box-shadow: 0 5px 20px rgba(67, 97, 238, 0.1);
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
        font-weight: 600;
        color: var(--primary-color);
        margin-right: auto;
        font-size: 14px;
        background: rgba(67, 97, 238, 0.1);
        padding: 6px 12px;
        border-radius: 30px;
    }

    .bulk-action-btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.3s;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .bulk-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .bulk-action-btn.download {
        background: var(--success-gradient);
        color: white;
    }

    .bulk-action-btn.download:hover {
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }

    .bulk-action-btn.delete {
        background: var(--danger-gradient);
        color: white;
    }

    .bulk-action-btn.delete:hover {
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
    }

    .bulk-action-btn.clear {
        background: linear-gradient(135deg, #94a3b8, #64748b);
        color: white;
    }

    .bulk-action-btn.clear:hover {
        box-shadow: 0 8px 20px rgba(100, 116, 139, 0.3);
    }

    /* Table Styles */
    .table-responsive {
        overflow-x: hidden;
    }

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
        padding: 16px 20px;
        font-weight: 700;
        color: white;
        text-align: left;
        font-size: 14px;
        border-bottom: 2px solid #e2e8f0;
        cursor: pointer;
        user-select: none;
        transition: background-color 0.2s;
        position: relative;
        text-transform: uppercase;
        letter-spacing: 0.3px;
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
        color: var(--primary-color);
    }

    .erp-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 14px;
        vertical-align: middle;
    }

    .erp-table tbody tr {
        transition: all 0.3s;
    }

    .erp-table tbody tr:hover {
        background-color: #f8fafc;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .erp-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Checkbox styling */
    .select-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
        border: 2px solid #cbd5e1;
        border-radius: 5px;
        transition: all 0.2s;
        accent-color: var(--primary-color);
    }

    .select-checkbox:hover {
        border-color: var(--primary-color);
        transform: scale(1.1);
    }

    /* Employee Info */
    .employee-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 15px;
        margin-bottom: 2px;
    }

    .employee-id {
        font-size: 11px;
        color: #64748b;
        font-family: monospace;
    }

    /* Subject Display */
    .subject-display {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .subject-name {
        font-weight: 600;
        color: #334155;
        font-size: 14px;
    }

    .text-muted {
        color: #94a3b8 !important;
        font-size: 12px;
    }

    /* Badges */
    .subject-type-badge {
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge-main {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
        border: 1px solid #93c5fd;
    }

    .badge-sub {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
        border: 1px solid #fcd34d;
    }

    .section-badge {
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-all-sections {
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        color: #3730a3;
        border: 1px solid #a5b4fc;
    }

    .badge-single-section {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
        border: 1px solid #6ee7b7;
    }

    .semester-display {
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        background: linear-gradient(135deg, #f3e8ff, #e9d5ff);
        color: #6b21a5;
        border: 1px solid #d8b4fe;
        display: inline-block;
    }

    /* Status Badges */
    .status-badge {
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
        transition: all 0.3s;
    }

    .status-badge:hover {
        transform: scale(1.05);
    }

    .status-active {
        background: var(--success-gradient);
        color: white;
    }

    .status-inactive {
        background: var(--danger-gradient);
        color: white;
    }

    /* Action Buttons */
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
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        background: white;
        border: 2px solid transparent;
    }

    .action-btn-view {
        background: linear-gradient(135deg, #e0f2fe, #bae6fd);
        color: #0369a1;
        border-color: #7dd3fc;
    }

    .action-btn-view:hover {
        background: linear-gradient(135deg, #bae6fd, #7dd3fc);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(3, 105, 161, 0.2);
        text-decoration: none;
        color: #0369a1;
    }

    .action-btn i {
        font-size: 14px;
    }

    .action-btn .small {
        font-size: 11px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #64748b;
    }

    .empty-state-icon {
        font-size: 64px;
        color: #cbd5e1;
        margin-bottom: 20px;
    }

    .empty-state h4 {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    /* Subject ID */
    .subject-id {
        font-size: 11px;
        color: #64748b;
        font-family: monospace;
        background: #f1f5f9;
        padding: 2px 6px;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
    }

    /* View Panel Styles */
    #viewAssignmentPanel {
        position: fixed;
        top: 0;
        right: -100%;
        width: 100%;
        max-width: 700px;
        height: 100vh;
        background: white;
        box-shadow: -5px 0 30px rgba(0, 0, 0, 0.15);
        z-index: 1050;
        overflow-y: auto;
        transition: right 0.4s ease;
    }

    #viewAssignmentPanel.open {
        right: 0;
    }

    .view-panel-header {
        background: var(--primary-gradient);
        color: white;
        padding: 20px 25px;
        font-size: 18px;
        font-weight: 700;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: none;
    }

    .view-panel-header i {
        font-size: 22px;
    }

    .close-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        font-size: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .close-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .view-panel-content {
        padding: 25px;
    }

    .view-section {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .view-section-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 12px 15px;
        font-weight: 700;
        color: var(--primary-color);
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .view-section-body {
        padding: 15px;
    }

    .view-row {
        display: flex;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px dashed #e2e8f0;
    }

    .view-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .view-label {
        font-weight: 600;
        width: 35%;
        color: #475569;
        font-size: 13px;
    }

    .view-value {
        width: 65%;
        color: #1e293b;
        font-size: 14px;
    }

    /* Loading Spinner */
    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid var(--primary-color);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

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
        to {
            transform: rotate(360deg);
        }
    }

    /* Page Loader */
    #pageLoader {
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.9);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
    }

    /* Datalist styling */
    datalist {
        display: none;
    }

    /* Responsive */
    @media (max-width: 992px) {
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
            display: flex;
            justify-content: center;
        }

        .btn-filter {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 15px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
        }

        .add-btn {
            width: 100%;
            justify-content: center;
        }

        .bulk-actions-container {
            flex-direction: column;
            align-items: flex-start;
        }

        .selected-count {
            margin-right: 0;
            width: 100%;
            text-align: center;
        }

        .d-flex {
            width: 100%;
            flex-direction: column;
            gap: 8px;
        }

        .bulk-action-btn {
            width: 100%;
            justify-content: center;
        }

        .table-actions {
            flex-direction: column;
        }

        .action-btn {
            width: 100%;
            justify-content: center;
        }

        .view-row {
            flex-direction: column;
        }

        .view-label,
        .view-value {
            width: 100%;
        }

        .view-label {
            margin-bottom: 4px;
        }
    }

    @media (max-width: 576px) {
        .erp-table {
            display: block;
            overflow-x: auto;
        }
    }
</style>

@php
$courseLabel = (isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type === 'School')
? 'Class'
: 'Course';
@endphp

<!-- Page Loader -->
<div id="pageLoader" style="display:none;">
    <div class="spinner"></div>
</div>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-person-workspace"></i>
            Assign Subjects - Employee
        </h1>
        <a href="{{ route('assign-subjects.toemployee') }}" class="add-btn">
            <i class="bi bi-plus-circle"></i>
            Assign Subjects
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
            <div class="filter-grid">
                {{-- Employee Name --}}
                <div class="filter-group">
                    <i class="bi bi-person"></i>
                    <input type="text" list="employeeNamesList" name="employee_name" class="filter-input"
                        value="{{ request('employee_name') }}" placeholder="Employee Name">
                    <datalist id="employeeNamesList">
                        @foreach($employeeList as $employee)
                        <option value="{{ $employee }}"></option>
                        @endforeach
                    </datalist>
                </div>

                {{-- Subject Name --}}
                <div class="filter-group">
                    <i class="bi bi-journal-text"></i>
                    <input type="text" list="subjectNamesList" name="subject_name" class="filter-input"
                        value="{{ request('subject_name') }}" placeholder="Subject Name">
                    <datalist id="subjectNamesList">
                        @foreach($subjectList as $subject)
                        <option value="{{ $subject }}"></option>
                        @endforeach
                    </datalist>
                </div>

                {{-- Subject Type --}}
                <div class="filter-group">
                    <i class="bi bi-diagram-3"></i>
                    <input type="text" list="subjectTypeList" name="subject_type" class="filter-input auto-submit"
                        value="{{ request('subject_type') }}" placeholder="Subject Type">
                    <datalist id="subjectTypeList">
                        <option value="main_subject">Main Subject</option>
                        <option value="sub_subject">Sub Subject</option>
                    </datalist>
                </div>

                {{-- Section Filter --}}
                <div class="filter-group">
                    <i class="bi bi-columns"></i>
                    <input type="text" list="sectionNamesList" name="section_filter" class="filter-input auto-submit"
                        value="{{ request('section_filter') }}" placeholder="Search Section">
                    <datalist id="sectionNamesList">
                        @foreach($sectionList as $section)
                        <option value="{{ $section }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary">
                    <i class="bi bi-x-circle"></i>
                    Reset Filters
                </a>
            </div>

            <!-- Hidden sort inputs -->
            <input type="hidden" name="sort_by" id="sortBy" value="{{ request('sort_by', 'assigned_date') }}">
            <input type="hidden" name="sort_order" id="sortOrder" value="{{ request('sort_order', 'desc') }}">
        </form>
    </div>

    {{-- Bulk Actions Container --}}
    <div class="bulk-actions-container d-none" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 assignments selected</div>
        <div class="d-flex flex-wrap gap-2">
            <select class="bulk-action-btn download" onchange="bulkAction('download', this.value)" style="border: none;">
                <option value="">Download</option>
                <option value="excel">Excel</option>
                <option value="csv">CSV</option>
            </select>
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
    
    <div>
        {{-- Assignments Table --}}
        <div class="table-responsive custom-table-wrapper" id="tableWrapper">
            <table class="erp-table">
                <thead>
                    <tr>
                        <th width="40" class="d-none">
                            <input type="checkbox" id="selectAll" class="select-checkbox">
                        </th>
                        <th class="sortable d-none" onclick="sortTable('serial')">
                            #
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="sticky-main-2 sortable" onclick="sortTable('employee')">
                            Employee
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('subject')">
                            Subject
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('type')">
                            Type
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
                        <th class="sortable" onclick="sortTable('semester')">
                            Semester
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('assigned_date')">
                            Assigned Date
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignedSubjects as $index => $assign)
                    <tr>
                        <td class="d-none">
                            <input type="checkbox" class="assignment-checkbox select-checkbox" value="{{ $assign->id }}">
                        </td>
                        <td class="d-none">{{ $index+1 }}</td>
                        <td class="sticky-main-2">
                            <div class="employee-name">{{ $assign->employee_name ?? $assign->name ?? 'N/A' }}</div>
                            @if($assign->employee_id)
                            <!-- <div class="employee-id">{{ $assign->employee_id }}</div> -->
                            @endif
                        </td>
                        <td>
                            <div class="subject-display">
                                <span class="subject-name">{{ $assign->display_subject_name ?? ($assign->main_subject_name ?? 'N/A') }}</span>
                                @if(isset($assign->subject_type) && $assign->subject_type === 'sub_subject' && $assign->sub_subject_name)
                                <small class="text-muted">{{ $assign->sub_subject_name }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if(isset($assign->subject_type) && $assign->subject_type === 'sub_subject')
                            <span class="subject-type-badge badge-sub">Sub</span>
                            @else
                            <span class="subject-type-badge badge-main">Main</span>
                            @endif
                        </td>
                        <td>
                            @if(isset($assign->section_name))
                            @if($assign->section_id === 'all')
                            <span class="section-badge badge-all-sections">All Sections</span>
                            @else
                            <span class="section-badge badge-single-section">{{ $assign->section_name }}</span>
                            @endif
                            @elseif(isset($assign->section_id))
                            @if($assign->section_id === 'all')
                            <span class="section-badge badge-all-sections">All Sections</span>
                            @else
                            <span class="section-badge badge-single-section">Section {{ $assign->section_id }}</span>
                            @endif
                            @else
                            <span>N/A</span>
                            @endif
                        </td>
                        <td>
                            @if(isset($assign->semester_id) && $assign->semester_id === 'all_semesters')
                            <span class="semester-display">All Semesters</span>
                            @elseif(isset($assign->semester_id))
                            <span class="semester-display">Semester {{ $assign->semester_id }}</span>
                            @else
                            <span>N/A</span>
                            @endif
                        </td>
                        <td>{{ isset($assign->assigned_date) ? date('d-m-Y', strtotime($assign->assigned_date)) : 'N/A' }}</td>
                        <td class="text-center">
                            <div class="table-actions">
                                <a href="{{ url('/assign-subjects/' . $assign->id) }}" class="action-btn action-btn-view">
                                    <i class="fa-regular fa-eye"></i>
                                    <span class="small">View</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-journal-plus"></i>
                                </div>
                                <h4>No assignments found</h4>
                                <p>Try adjusting your filters or assign subjects to employees</p>
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
    </div>

</div>

<!-- View Assignment Panel -->
<div id="viewAssignmentPanel">
    <div class="view-panel-header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-journal-check"></i>
            Assignment Details
        </div>
        <button class="close-btn" onclick="closeViewPanel()">×</button>
    </div>

    <div class="view-panel-content" id="assignmentDetails">
        <!-- Content will be loaded dynamically -->
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
// All JavaScript remains exactly the same
function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}
document.querySelectorAll('.filter-input').forEach(function(input) {
    input.addEventListener('change', function() {
        showLoader();
        document.getElementById('filterForm').submit();
    });
});

// Bulk Selection Management
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const assignmentCheckboxes = document.querySelectorAll('.assignment-checkbox');
    const bulkActionsContainer = document.getElementById('bulkActionsContainer');
    const selectedCountElement = document.getElementById('selectedCount');

    // Select All functionality
    selectAllCheckbox.addEventListener('change', function() {
        assignmentCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectionUI();
    });

    // Individual checkbox change
    assignmentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionUI);
    });

    function updateSelectionUI() {
        const selectedCount = document.querySelectorAll('.assignment-checkbox:checked').length;

        if (selectedCount > 0) {
            bulkActionsContainer.classList.add('active');
            selectedCountElement.textContent = selectedCount + ' assignment(s) selected';

            // Update select all checkbox state
            selectAllCheckbox.checked = selectedCount === assignmentCheckboxes.length;
            selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < assignmentCheckboxes.length;
        } else {
            bulkActionsContainer.classList.remove('active');
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
    }
});

// Bulk Action Functions
function clearSelection() {
    document.querySelectorAll('.assignment-checkbox:checked').forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    document.getElementById('bulkActionsContainer').classList.remove('active');
}

function bulkAction(action, format = null) {
    const selectedCheckboxes = Array.from(document.querySelectorAll('.assignment-checkbox:checked'))
        .map(checkbox => checkbox.value);

    if (selectedCheckboxes.length === 0) {
        alert('Please select at least one assignment.');
        return;
    }

    switch (action) {
        case 'download':

            if (!format) {
                alert("Please select a format");
                return;
            }

            const ids = selectedCheckboxes.join(',');

            const url = `/employee-subjects/download?ids=${ids}&type=${format}`;

            window.location.href = url;

            break;

        case 'bulk_delete':
            if (confirm(
                    `Are you sure you want to delete ${selectedCheckboxes.length} assignment(s)? This action cannot be undone.`
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
                    alert(`${selectedCheckboxes.length} assignments deleted successfully`);
                }, 1500);
            }
            break;

        default:
            alert(`${action} action triggered for ${selectedCheckboxes.length} assignments`);
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

// Panel Functions
function openViewPanel() {
    document.getElementById("viewAssignmentPanel").classList.add("open");
    document.body.style.overflow = "hidden";
}

function closeViewPanel() {
    const viewPanel = document.getElementById("viewAssignmentPanel");
    viewPanel.classList.remove("open");
    viewPanel.style.right = "";
    document.body.style.overflow = "";
}

// View Assignment Function (existing function preserved)
window.viewAssignment = function(id) {
    openViewPanel();

    // Reset content
    $("#assignmentDetails").html(
        '<div class="text-center py-4"><div class="loading-spinner mb-2"></div><p>Loading assignment details...</p></div>'
    );

    $.get('/assign-subjects/' + id, function(response) {
        if (!response.success) {
            $('#assignmentDetails').html(`
                    <div class="alert alert-danger">
                        ${response.message || 'Failed to load assignment details'}
                    </div>
                    <button class="btn btn-secondary mt-3" onclick="closeViewPanel()">
                        <i class="bi bi-x-circle me-1"></i> Close
                    </button>
                `);
            return;
        }

        let data = response.data;
        const courseLabel = "{{ $courseLabel }}";

        // Format days of week
        let daysOfWeek = '';
        if (data.days_of_week && Array.isArray(data.days_of_week) && data.days_of_week.length > 0) {
            daysOfWeek = data.days_of_week.join(', ');
        } else if (data.days_of_week && typeof data.days_of_week === 'string') {
            daysOfWeek = data.days_of_week;
        } else {
            daysOfWeek = '-';
        }

        // Format dates
        let formatDate = (dateString) => {
            if (!dateString) return '-';
            let date = new Date(dateString);
            return date.toLocaleDateString('en-IN');
        };

        // Format time
        let formatTime = (timeString) => {
            if (!timeString) return '-';
            return timeString;
        };

        let html = `
                <div class="view-section">
                    <div class="view-section-header">Assignment Information</div>
                    <div class="view-section-body">
                        <div class="view-row">
                            <div class="view-label">Assignment ID</div>
                            <div class="view-value"><span class="subject-id">${data.emp_assign_subject_id || '-'}</span></div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Status</div>
                            <div class="view-value">
                                ${data.status === 'active' ? 
                                    '<span class="status-badge status-active">Active</span>' : 
                                    '<span class="status-badge status-inactive">' + (data.status || 'Inactive') + '</span>'}
                            </div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Assigned Date</div>
                            <div class="view-value">${formatDate(data.assigned_date)}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Remarks</div>
                            <div class="view-value">${data.remarks || '-'}</div>
                        </div>
                    </div>
                </div>

                <div class="view-section">
                    <div class="view-section-header">Employee Information</div>
                    <div class="view-section-body">
                        <div class="view-row">
                            <div class="view-label">Employee Name</div>
                            <div class="view-value">${data.employee_name || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Employee ID</div>
                            <div class="view-value">${data.employee_id || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Department</div>
                            <div class="view-value">
                                ${data.department_name || '-'} 
                                ${data.department_id ? '(<span class="subject-id">' + data.department_id + '</span>)' : ''}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="view-section">
                    <div class="view-section-header">${courseLabel} Information</div>
                    <div class="view-section-body">
                        <div class="view-row">
                            <div class="view-label">${courseLabel} Type</div>
                            <div class="view-value">${data.course_type || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">${courseLabel} Sub-type</div>
                            <div class="view-value">${data.branch_name || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Section</div>
                            <div class="view-value">
                                ${data.section_id === 'all' ? 
                                    '<span class="section-badge badge-all-sections">All Sections</span>' : 
                                    '<span class="section-badge badge-single-section">' + (data.section_id || '-') + '</span>'}
                            </div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Semester</div>
                            <div class="view-value">
                                ${data.semester_id === 'all_semesters' ? 
                                    '<span class="semester-display">All Semesters</span>' : 
                                    '<span class="semester-display">' + (data.semester_id || '-') + '</span>'}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="view-section">
                    <div class="view-section-header">Subject Information</div>
                    <div class="view-section-body">
                        <div class="view-row">
                            <div class="view-label">Subject Type</div>
                            <div class="view-value">
                                ${data.subject_type === 'sub_subject' ? 
                                    '<span class="subject-type-badge badge-sub">Sub Subject</span>' : 
                                    '<span class="subject-type-badge badge-main">Main Subject</span>'}
                            </div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Display Name</div>
                            <div class="view-value">${data.subject_display_name || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Main Subject</div>
                            <div class="view-value">
                                ${data.main_subject_name || '-'} 
                                ${data.subject_id ? '(<span class="subject-id">' + data.subject_id + '</span>)' : ''}
                            </div>
                        </div>
            `;

        if (data.subject_type === 'sub_subject' && data.sub_subject_name) {
            html += `
                    <div class="view-row">
                        <div class="view-label">Sub-Subject</div>
                        <div class="view-value">
                            ${data.sub_subject_name} 
                            ${data.sub_subject_id ? '(<span class="subject-id">' + data.sub_subject_id + '</span>)' : ''}
                        </div>
                    </div>
                `;
        }

        html += `
                    </div>
                </div>

                <div class="view-section">
                    <div class="view-section-header">Lecture Timing Details</div>
                    <div class="view-section-body">
                        <div class="view-row">
                            <div class="view-label">Frequency</div>
                            <div class="view-value">
                                ${data.frequency ? 
                                    '<span>' + data.frequency.charAt(0).toUpperCase() + data.frequency.slice(1) + '</span>' : 
                                    '-'}
                            </div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Start Time</div>
                            <div class="view-value">${formatTime(data.start_time)}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">End Time</div>
                            <div class="view-value">${formatTime(data.end_time)}</div>
                        </div>
            `;

        if (data.frequency === 'weekly') {
            html += `
                    <div class="view-row">
                        <div class="view-label">Days of Week</div>
                        <div class="view-value">${daysOfWeek}</div>
                    </div>
                `;
        }

        if (data.frequency === 'monthly' && data.day_of_month) {
            html += `
                    <div class="view-row">
                        <div class="view-label">Day of Month</div>
                        <div class="view-value">${data.day_of_month}</div>
                    </div>
                `;
        }

        html += `
                        <div class="view-row">
                            <div class="view-label">Valid From</div>
                            <div class="view-value">${formatDate(data.valid_from)}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Valid To</div>
                            <div class="view-value">${formatDate(data.valid_to)}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Location</div>
                            <div class="view-value">${data.location || '-'}</div>
                        </div>
                    </div>
                </div>

                <div class="view-section">
                    <div class="view-section-header">Timestamps</div>
                    <div class="view-section-body">
                        <div class="view-row">
                            <div class="view-label">Assignment Created</div>
                            <div class="view-value">${formatDate(data.created_at)}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Assignment Updated</div>
                            <div class="view-value">${formatDate(data.updated_at)}</div>
                        </div>
            `;

        if (data.lecture_created_at) {
            html += `
                    <div class="view-row">
                        <div class="view-label">Lecture Timing Created</div>
                        <div class="view-value">${formatDate(data.lecture_created_at)}</div>
                    </div>
                `;
        }

        if (data.lecture_updated_at) {
            html += `
                    <div class="view-row">
                        <div class="view-label">Lecture Timing Updated</div>
                        <div class="view-value">${formatDate(data.lecture_updated_at)}</div>
                    </div>
                `;
        }

        html += `
                    </div>
                </div>
            `;

        $('#assignmentDetails').html(html);

    }).fail(function(xhr) {
        console.error('Error loading assignment:', xhr);
        $('#assignmentDetails').html(`
                <div class="alert alert-danger">
                    Failed to load assignment details. Please try again.
                </div>
                <button class="btn btn-secondary mt-3" onclick="closeViewPanel()">
                    <i class="bi bi-x-circle me-1"></i> Close
                </button>
            `);
    });
}

// Filter form submission with loading state
document.getElementById('filterForm').addEventListener('submit', function(e) {
    // Re-enable button after 2 seconds in case of error
    setTimeout(() => {
        // This function was using a submitBtn that doesn't exist, so we're removing that part
    }, 2000);
});

// Close panel on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeViewPanel();
    }
});
</script>
@endsection