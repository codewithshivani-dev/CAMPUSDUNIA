@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
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
        display: flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(5px);
        transition: all 0.3s;
        text-decoration: none;
    }

    .add-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: white;
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
        transition: all 0.3s;
    }

    .filter-container:hover {
        border-color: var(--primary-color);
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.1);
    }

    .filter-form {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .filter-grid {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        flex: 1;
    }

    .filter-group {
        position: relative;
        min-width: 220px;
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
        padding: 10px 15px 10px 40px;
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
        transform: translateY(-1px);
    }

    .filter-group input:hover,
    .filter-group select:hover {
        border-color: var(--secondary-color);
    }

    .btn-filter {
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        font-size: 14px;
    }

    .btn-filter-primary {
        background: var(--primary-gradient);
        color: white;
    }

    .btn-filter-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        color: white;
    }

    .btn-filter-secondary {
        background: transparent;
        color: #475569;
        border-color: #e2e8f0;
    }

    .btn-filter-secondary:hover {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-color: #cbd5e1;
        color: #1e293b;
        transform: translateY(-2px);
    }

    /* Bulk Actions Container */
    .bulk-actions-container {
        background: white;
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 20px;
        border: 2px solid var(--success-color);
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.1);
        animation: slideDown 0.3s ease;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
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
        color: var(--success-color);
        margin-right: auto;
        font-size: 14px;
        background: rgba(16, 185, 129, 0.1);
        padding: 6px 15px;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
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
        background: white;
    }

    .bulk-action-btn.download {
        background: var(--success-gradient);
        color: white;
        border: none;
    }

    .bulk-action-btn.download:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
    }

    .bulk-action-btn.delete {
        background: var(--danger-gradient);
        color: white;
    }

    .bulk-action-btn.delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
    }

    .bulk-action-btn.clear {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        color: #475569;
        border: 2px solid #cbd5e1;
    }

    .bulk-action-btn.clear:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
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

    /* Checkbox Styling */
    .select-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
        border: 2px solid #cbd5e1;
        border-radius: 6px;
        transition: all 0.2s;
        accent-color: var(--primary-color);
    }

    .select-checkbox:hover {
        border-color: var(--primary-color);
        transform: scale(1.1);
    }

    .select-checkbox:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
    }

    .status-badge:hover {
        transform: scale(1.05);
    }

    .status-active {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #166534;
        border: 1px solid #86efac;
    }

    .status-inactive {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    /* Student Info */
    .student-name {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .student-name i {
        font-size: 16px;
        background: rgba(67, 97, 238, 0.1);
        padding: 4px;
        border-radius: 6px;
    }

    .student-reg {
        font-size: 11px;
        color: #64748b;
        margin-top: 4px;
        background: #f1f5f9;
        padding: 2px 8px;
        border-radius: 12px;
        display: inline-block;
    }

    .student-id {
        font-size: 10px;
        color: #64748b;
        font-family: monospace;
        background: #f1f5f9;
        padding: 2px 6px;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
    }

    /* Subjects Count */
    .subjects-count {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid #93c5fd;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .subjects-count i {
        font-size: 12px;
    }

    /* Course Badge */
    .course-badge {
        background: linear-gradient(135deg, #f3e8ff, #e9d5ff);
        color: #7c3aed;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        border: 1px solid #d8b4fe;
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
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        color: var(--primary-color);
        border: 2px solid #e2e8f0;
    }

    .action-btn-view {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
        border-color: #93c5fd;
    }

    .action-btn-view:hover {
        background: linear-gradient(135deg, #bfdbfe, #93c5fd);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
        color: #1e40af;
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
        padding: 50px 20px;
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border-radius: 12px;
    }

    .empty-state-icon {
        font-size: 64px;
        color: var(--primary-color);
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state h4 {
        color: #334155;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #94a3b8;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 25px;
        padding: 15px 20px;
        background: white;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        flex-wrap: wrap;
        gap: 15px;
    }

    .pagination-info {
        font-size: 13px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pagination-info i {
        color: var(--primary-color);
    }

    .pagination {
        display: flex;
        list-style: none;
        gap: 8px;
        padding: 0;
        margin: 0;
    }

    .page-item .page-link {
        padding: 8px 14px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        text-decoration: none;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s;
        background: white;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .page-item.active .page-link {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
    }

    .page-item:not(.active):not(.disabled) .page-link:hover {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .page-item.disabled .page-link {
        background: #f1f5f9;
        color: #94a3b8;
        border-color: #e2e8f0;
        cursor: not-allowed;
    }

    /* View Panel */
    #viewStudentSubjectsPanel {
        position: fixed;
        top: 0;
        right: -100%;
        width: 100%;
        max-width: 700px;
        height: 100vh;
        background: white;
        box-shadow: -5px 0 30px rgba(0, 0, 0, 0.2);
        z-index: 1050;
        overflow-y: auto;
        transition: right 0.4s ease;
        border-left: 2px solid var(--primary-color);
    }

    #viewStudentSubjectsPanel.open {
        right: 0;
    }

    .view-panel-header {
        background: var(--primary-gradient);
        padding: 20px 25px;
        font-size: 18px;
        font-weight: 700;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: none;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .view-panel-header .close-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        border-radius: 8px;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        cursor: pointer;
        transition: all 0.3s;
        border: none;
    }

    .view-panel-header .close-btn:hover {
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
        background: white;
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

    .view-section-header i {
        font-size: 18px;
    }

    .view-section-body {
        padding: 20px;
    }

    .view-row {
        display: flex;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
    }

    .view-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .view-label {
        font-weight: 600;
        width: 40%;
        color: #475569;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .view-value {
        width: 60%;
        color: #334155;
        font-size: 14px;
    }

    /* Loading Spinner */
    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top-color: var(--primary-color);
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
        to { transform: rotate(360deg); }
    }

    #pageLoader {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(5px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    /* Responsive */
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

        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-grid {
            flex-direction: column;
            gap: 10px;
        }

        .filter-group {
            min-width: 100%;
        }

        .bulk-actions-container {
            flex-direction: column;
            align-items: stretch;
        }

        .bulk-actions-container .d-flex {
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
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
            justify-content: center;
        }

        .view-row {
            flex-direction: column;
            gap: 5px;
        }

        .view-label,
        .view-value {
            width: 100%;
        }

        .pagination-wrapper {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
    }
    .table-responsive{
        overflow-x: hidden;
    }
</style>

@php
$courseLabel = (isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type === 'School')
? 'Class'
: 'Course';
@endphp

<!-- Loader -->
<div id="pageLoader">
    <div class="spinner"></div>
</div>

<!-- Header with institute name -->
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-journal-bookmark-fill"></i>
            Assign Subjects - Students
        </h1>
        <a href="{{ route('student-subject-assignments.form') }}" class="add-btn">
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
        <form method="GET" id="filterForm">
            <div class="filter-grid">
                {{-- Student Name --}}
                <div class="filter-group">
                    <i class="bi bi-person-fill"></i>
                    <input list="studentsList" name="student_name" class="filter-input" placeholder="Student Name"
                        value="{{ request('student_name') }}">
                    <datalist id="studentsList">
                        @foreach($students as $student)
                        <option value="{{ $student->student_name }}" data-id="{{ $student->student_hash_id }}">
                        @endforeach
                    </datalist>
                </div>

                {{-- Student ID hidden --}}
                <input type="hidden" name="student_id" id="student_id" value="{{ request('student_id') }}">

                {{-- Course Type --}}
                <div class="filter-group d-none">
                    <i class="bi bi-book-fill"></i>
                    <input list="courseTypes" name="course_type" class="filter-input" placeholder="Course Type"
                        value="{{ request('course_type') }}">
                    <datalist id="courseTypes">
                        @foreach($courseTypes as $course)
                        <option value="{{ $course }}">
                        @endforeach
                    </datalist>
                </div>

                {{-- Sub Type --}}
                <div class="filter-group">
                    <i class="bi bi-bookmark-fill"></i>
                    <input list="subTypes" name="sub_type" class="filter-input" placeholder="Sub Type"
                        value="{{ request('sub_type') }}">
                    <datalist id="subTypes">
                        @foreach($subTypes as $sub)
                        <option value="{{ $sub }}">
                        @endforeach
                    </datalist>
                </div>

                {{-- Status --}}
                <div class="filter-group">
                    <i class="bi bi-info-circle-fill"></i>
                    <input list="statusList" name="status" class="filter-input" placeholder="Status"
                        value="{{ request('status') }}">
                    <datalist id="statusList">
                        @foreach($statuses as $status)
                        <option value="{{ $status }}">
                        @endforeach
                    </datalist>
                </div>

                <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary">
                    <i class="bi bi-x-circle-fill"></i>
                    Reset Filters
                </a>
            </div>
        </form>
    </div>

    {{-- Bulk Actions Container --}}
    <div class="bulk-actions-container d-none" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">
            <i class="bi bi-check-circle-fill"></i>
            <span>0 assignments selected</span>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <select class="bulk-action-btn download" onchange="bulkAction('download', this.value)" style="margin-right: 0;">
                <option value="">Download</option>
                <option value="excel">Excel</option>
                <option value="csv">CSV</option>
            </select>
            <button class="bulk-action-btn delete" onclick="bulkAction('bulk_delete')">
                <i class="bi bi-trash-fill"></i>
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
                        <th class="d-none" width="40">
                            <input type="checkbox" id="selectAll" class="select-checkbox">
                        </th>
                        <th class="sortable d-none" onclick="sortTable('serial')">
                            #
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="sticky-main-2 sortable" onclick="sortTable('student_name')">
                            Student Name
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="sortable d-none" onclick="sortTable('student_id')">
                            Student ID
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('course')">
                            {{ $courseLabel }}
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('subjects_assigned')">
                            Subjects Assigned
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
                        <th class="sortable" onclick="sortTable('status')">
                            Status
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $index => $assignment)
                    <tr>
                        <td class="d-none">
                            <input type="checkbox" class="assignment-checkbox select-checkbox"
                                value="{{ $assignment->id }}">
                        </td>
                        <td class="d-none">{{ $index+1 }}</td>
                        <td class="sticky-main-2">
                            <div class="student-name">
                                <i class="bi bi-person-badge-fill"></i>
                                {{ $assignment->student_name ?? 'N/A' }}
                            </div>
                            <span class="student-reg">{{ $assignment->registration_number ?? 'N/A' }}</span>
                        </td>
                        <td class="d-none"><span class="student-id">{{ $assignment->student_hash_id ?? 'N/A' }}</span></td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="d-none">{{ $assignment->course_type ?? 'N/A' }}</span>
                                <span class="course-badge">{{ $assignment->sub_type }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="subjects-count">
                                <i class="bi bi-book-fill"></i>
                                {{ $assignment->subject_count ?? 0 }} Subjects
                            </span>
                        </td>
                        <td>{{ date('d-m-Y', strtotime($assignment->assigned_date)) }}</td>
                        <td>
                            <span class="status-badge status-active">
                                <i class="bi bi-check-circle-fill"></i> Active
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="table-actions">
                                <a href="{{ url('/student-subject-assignments/' . $assignment->student_hash_id) }}"
                                   class="action-btn action-btn-view" title="View Details">
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
                                    <i class="bi bi-journal-check"></i>
                                </div>
                                <h4>No assignments found</h4>
                                <p>Try adjusting your filters or assign subjects to students</p>
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
    
        <!-- Pagination -->
        <div class="pagination-wrapper">
            <div class="pagination-info">
                <i class="bi bi-files"></i>
                Showing {{ $assignments->firstItem() }} to {{ $assignments->lastItem() }}
                of {{ $assignments->total() }} assignments
            </div>
    
            @if ($assignments->hasPages())
            <nav>
                <ul class="pagination mb-0">
                    {{-- Previous Page --}}
                    @if ($assignments->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link"><i class="bi bi-chevron-left"></i> Prev</span>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $assignments->previousPageUrl() }}">
                            <i class="bi bi-chevron-left"></i> Prev
                        </a>
                    </li>
                    @endif
    
                    {{-- Page Numbers --}}
                    @for ($i = 1; $i <= $assignments->lastPage(); $i++)
                        <li class="page-item {{ $assignments->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $assignments->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
    
                    {{-- Next Page --}}
                    @if ($assignments->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $assignments->nextPageUrl() }}">
                            Next <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                    @else
                    <li class="page-item disabled">
                        <span class="page-link">Next <i class="bi bi-chevron-right"></i></span>
                    </li>
                    @endif
                </ul>
            </nav>
            @endif
        </div>
    </div>
</div>

<!-- View Student Subjects Panel -->
<div id="viewStudentSubjectsPanel">
    <div class="view-panel-header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-person-lines-fill"></i>
            Student Subject Details
        </div>
        <button class="close-btn" onclick="closeViewPanel()">×</button>
    </div>

    <div class="view-panel-content" id="studentSubjectsDetails">
        <!-- Content will be loaded dynamically -->
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<script>
// ALL JAVASCRIPT REMAINS EXACTLY THE SAME - NOT CHANGED
function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}

document.querySelectorAll('.filter-input').forEach(function(input) {
    input.addEventListener('change', function() {
        if (input.name === "student_name") {
            let studentIdField = document.getElementById('student_id');
            let list = document.getElementById('studentsList').options;
            let found = false;

            for (let option of list) {
                if (option.value === input.value) {
                    studentIdField.value = option.dataset.id;
                    found = true;
                    break;
                }
            }

            // ✅ If input cleared or invalid name, remove student_id
            if (!found || input.value.trim() === "") {
                studentIdField.value = "";
            }
        }
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
            bulkActionsContainer.classList.remove('d-none');
            selectedCountElement.innerHTML = '<i class="bi bi-check-circle-fill"></i> ' + selectedCount + ' assignment(s) selected';

            // Update select all checkbox state
            selectAllCheckbox.checked = selectedCount === assignmentCheckboxes.length;
            selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < assignmentCheckboxes.length;
        } else {
            bulkActionsContainer.classList.remove('active');
            bulkActionsContainer.classList.add('d-none');
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
    document.getElementById('bulkActionsContainer').classList.add('d-none');
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
            const url = `/download/student-subjects?ids=${ids}&type=${format}`;
            window.location.href = url;
            break;

        case 'bulk_delete':
            if (confirm(
                    `Are you sure you want to remove ${selectedCheckboxes.length} assignment(s)? This action cannot be undone.`
                )) {
                // Show loading state
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<span class="loading-spinner"></span> Removing...';
                btn.disabled = true;

                // Simulate delete
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                    clearSelection();
                    alert(`${selectedCheckboxes.length} assignments removed successfully`);
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
    document.getElementById("viewStudentSubjectsPanel").classList.add("open");
    document.body.style.overflow = "hidden";
}

function closeViewPanel() {
    const viewPanel = document.getElementById("viewStudentSubjectsPanel");
    viewPanel.classList.remove("open");
    viewPanel.style.right = "";
    document.body.style.overflow = "";
}

// View Student Subjects Function
window.viewStudentSubjects = function(studentId) {
    openViewPanel();

    $("#studentSubjectsDetails").html(`
        <div class="text-center py-4">
            <div class="loading-spinner mb-2"></div>
            <p>Loading student details...</p>
        </div>
    `);

    $.ajax({
        url: '/student-subject-assignments/' + studentId,
        method: 'GET',
        dataType: 'html',
        success: function(response) {
            $('#studentSubjectsDetails').html(response);
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            $('#studentSubjectsDetails').html(`
                <div class="alert alert-danger">
                    Failed to load student subject details.
                </div>
                <button class="btn btn-secondary mt-3" onclick="closeViewPanel()">
                    <i class="bi bi-x-circle me-1"></i> Close
                </button>
            `);
        }
    });
};

// Filter form submission with loading state
document.getElementById('filterForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('.btn-filter-primary');
    if (submitBtn) {
        const originalHTML = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Applying...';
        submitBtn.disabled = true;

        setTimeout(() => {
            submitBtn.innerHTML = originalHTML;
            submitBtn.disabled = false;
        }, 2000);
    }
});

// Close panel on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeViewPanel();
    }
});

// Initialize global functions
window.closeAssignmentPanel = function() {
    const assignmentPanel = document.getElementById("assignmentPanel");
    const overlay = document.getElementById("overlay");

    if (assignmentPanel) {
        assignmentPanel.classList.remove("open");
    }

    if (overlay) {
        overlay.classList.remove("show");
    }

    document.body.style.overflow = "";
};

window.resetAssignmentForm = function() {
    const form = document.getElementById('assignmentForm');
    if (form) {
        form.reset();
    }
};

window.resetDownstreamSelects = function() {
    const deptSelect = document.getElementById('department_id');
    const courseSelect = document.getElementById('course_type');
    const subTypeSelect = document.getElementById('course_subtype_id');

    if (deptSelect) deptSelect.disabled = true;
    if (courseSelect) courseSelect.disabled = true;
    if (subTypeSelect) subTypeSelect.disabled = true;
};

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filterForm');
    let typingTimer;
    const delay = 400;
    const autoInputs = ['course_type', 'sub_type', 'course_duration'];

    autoInputs.forEach(name => {
        const input = document.querySelector(`input[name="${name}"]`);
        if (!input) return;

        input.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                form.submit();
            }, delay);
        });
    });
});
</script>
@endsection