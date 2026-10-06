@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Syllabus Management</title>
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
        padding: 10px 20px;
        background: var(--primary-gradient);
        border: none;
        color: #fff!important;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }

    .add-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        text-decoration: none;
    }

    .add-btn i {
        font-size: 18px;
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

    .alert .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* Month box cards */
    .syllabus-month-box {
        border: 1px solid rgba(67, 97, 238, 0.16);
        border-radius: 18px;
        overflow: hidden;
        background: #ffffff;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .syllabus-month-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px rgba(67, 97, 238, 0.12);
    }

    .syllabus-month-box .card-header {
        border: none;
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }

    .syllabus-month-box .month-label {
        font-size: 0.9rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        opacity: 0.8;
    }

    .syllabus-month-box .card-body {
        padding: 1.2rem 1.25rem;
    }

    .syllabus-month-box .topic-name {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.65rem;
    }

    .syllabus-month-box .topic-description {
        color: #475569;
        margin-bottom: 1rem;
        line-height: 1.6;
    }

    .syllabus-month-box .file-panel {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #f8fafc;
        padding: 1rem;
        min-height: 150px;
    }

    .syllabus-month-box .file-title {
        display: block;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.35rem;
    }

    .syllabus-month-box .file-meta {
        color: #64748b;
        font-size: 0.88rem;
        margin-bottom: 1rem;
    }

    .syllabus-month-box .file-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .syllabus-month-box .file-actions .btn {
        min-width: 110px;
    }

    @media (max-width: 767px) {
        .syllabus-month-box .card-header,
        .syllabus-month-box .file-panel {
            flex-direction: column;
            gap: 0.75rem;
        }
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
        flex-wrap: wrap;
        gap: 15px;
    }

    .filter-grid {
        display: flex;
        flex: 1;
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
        white-space: nowrap;
        text-decoration: none;
        font-size: 14px;
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

    .btn-filter-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .btn-filter-secondary:hover {
        color: #475569 !important;
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

    .action-btn-view {
        background: var(--info-gradient);
    }

    /* Pagination - Enhanced */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding: 15px 0;
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

    /* Modal Enhancements - FIXED */
    .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .modal-header {
        background: var(--primary-gradient);
        border-bottom: none;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }

    .modal-title {
        font-weight: 600;
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 18px;
    }

    /* Custom close button styling */
    .modal-close-custom {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 18px;
        padding: 0;
    }

    .modal-close-custom:hover {
        background: var(--danger-gradient);
        transform: rotate(90deg);
    }

    .modal-close-custom i {
        font-size: 18px;
    }

    .modal-body.bg-light {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9) !important;
        padding: 25px;
    }

    .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 20px 25px;
        background: white;
    }

    /* Card styles for topics */
    .modal-body .card {
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s;
    }

    .modal-body .card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.1);
    }

    .modal-body .card-body {
        padding: 20px;
    }

    .modal-body .card h6 {
        color: var(--primary-color);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* View File Button */
    .btn-outline-primary {
        background: transparent;
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        border-radius: 10px;
        padding: 8px 16px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-outline-primary:hover {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border: 2px solid #e2e8f0;
        color: #475569;
        border-radius: 10px;
        font-weight: 500;
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        transform: translateY(-2px);
    }

    .btn-light {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 12px;
        transition: all 0.3s;
    }

    .btn-light:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    /* Loading Spinner */
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
        z-index: 99999;
        align-items: center;
        justify-content: center;
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
            flex: 1;
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

        .table-actions {
            flex-direction: column;
            gap: 4px;
        }

        .action-btn {
            width: 100%;
        }
    }

    /* Text utilities */
    .fw-semibold {
        font-weight: 600 !important;
        color: var(--primary-color);
    }

    .text-primary {
        color: var(--primary-color) !important;
    }

    .text-muted {
        color: #64748b !important;
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

@php
$courseLabel = (isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type === 'School')
? 'Class'
: 'Course';
@endphp

<div id="pageLoader">
    <div class="spinner"></div>
</div>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-journal-bookmark-fill"></i>
            Syllabus Management
        </h1>
        <a href="{{ route('instituteAdmin.syllabus.create') }}" class="add-btn">
            <i class="bi bi-plus-circle-fill"></i>
            Upload New
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Filters -->
    <div class="filter-container">
        <form method="GET" action="{{ route('instituteAdmin.syllabus.viewAll') }}" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <i class="bi bi-search"></i>
                    <input type="text" name="subject" list="subjectList" value="{{ request('subject') }}"
                        placeholder="Search Subject" class="filter-input auto-filter">
                    <datalist id="subjectList">
                        @foreach($subjects as $s)
                        <option value="{{ $s }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="filter-group">
                    <i class="bi bi-book-fill"></i>
                    <input type="text" name="course" list="courseList" value="{{ request('course') }}"
                        placeholder="Search {{ $courseLabel }}" class="filter-input auto-filter">
                    <datalist id="courseList">
                        @foreach($courses as $c)
                        <option value="{{ $c }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="filter-group d-none">
                    <i class="bi bi-person-fill"></i>
                    <input type="text" name="uploaded_by" list="uploaderList" value="{{ request('uploaded_by') }}"
                        placeholder="Uploaded By" class="filter-input auto-filter">
                    <datalist id="uploaderList">
                        @foreach($uploaders as $u)
                        <option value="{{ $u }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="filter-group">
                    <i class="bi bi-pencil-fill"></i>
                    <input type="text" name="title" list="titleList" value="{{ request('title') }}"
                        placeholder="Syllabus Title" class="filter-input auto-filter">
                    <datalist id="titleList">
                        @foreach($titles as $t)
                        <option value="{{ $t }}">
                        @endforeach
                    </datalist>
                </div>
            </div>

            <div class="filter-actions">
                <a href="{{ route('instituteAdmin.syllabus.viewAll') }}" class="btn-filter btn-filter-secondary mt-4">
                    <i class="bi bi-x-circle-fill"></i>
                    Reset Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Bulk Actions Container -->
    <div class="bulk-actions-container" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 syllabus selected</div>
        <div class="d-flex flex-wrap">
            <div class="dropdown">
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

<script>
function escapeHtml(text) {
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

document.addEventListener('DOMContentLoaded', function() {
    const syllabusDetailBaseUrl = "{{ url('/syllabus/detail') }}";
    const syllabusEditBaseUrl = "{{ url('/syllabus/edit') }}";

    // Try fetching fresh syllabus rows via AJAX and replace table body
    fetch('/instituteAdmin/syllabus/list-json')
        .then(res => res.json())
        .then(payload => {
            if (!payload || !Array.isArray(payload.data)) return;
            const tbody = document.getElementById('syllabusTableBody');
            if (!tbody) return;
            const rows = payload.data.map(s => {
                const fileUrl = s.sample_file_path ? ('/image/' + s.sample_file_path) : '';
                const months = (Array.isArray(s.monthly_labels) && s.monthly_labels.length) ? s.monthly_labels.join(', ') : (s.whole_semester_uploaded ? 'Whole semester' : 'N/A');
                const monthCount = s.monthly_count || 0;
                const monthText = s.whole_semester_uploaded ? 'Whole semester' : (monthCount > 0 ? `${monthCount} month${monthCount>1? 's':''}: ${months}` : 'N/A');
                return `
                    <tr>
                        <td class="sticky-checkbox"><input type="checkbox" class="syllabus-checkbox select-checkbox" value="${s.subject_id}"></td>
                        <td class="sticky-main"><span class="fw-semibold">${escapeHtml(s.subject_name || 'N/A')}</span></td>
                        <td>${escapeHtml(s.course_type || 'N/A')}</td>
                        <td>${escapeHtml(s.sub_type || 'N/A')}</td>
                        <td>${escapeHtml(s.title || 'N/A')}</td>
                        <td>${escapeHtml(s.uploader || 'Admin')}</td>
                        <td>${escapeHtml(monthText)}</td>
                        <td>${s.latest_uploaded_date ? new Date(s.latest_uploaded_date).toLocaleDateString() : ''}</td>
                        <td class="text-center">
                            <div class="table-actions">
                                <a href="${syllabusDetailBaseUrl}/${s.subject_id}" class="action-btn action-btn-view syllabus-detail-btn d-inline-block me-1">
                                    <i class="bi bi-eye-fill"></i><span class="small">View</span>
                                </a>
                                <a href="${syllabusEditBaseUrl}/${s.subject_id}" class="action-btn action-btn-edit syllabus-edit-btn d-inline-block btn btn-primary">
                                    <i class="bi bi-pencil-square"></i><span class="small">Edit</span>
                                </a>
                            </div>
                        </td>
                    </tr>`;
            }).join('');
            tbody.innerHTML = rows;
        })
        .catch(err => console.error('Failed to load syllabus rows:', err));
});
</script>

    <div>
        <!-- Syllabus Table -->
        <div class="table-responsive custom-table-wrapper" id="tableWrapper">
            <table class="erp-table">
                <thead>
                    <tr>
                        <th class="sticky-checkbox" width="40">
                            <input type="checkbox" id="selectAll" class="select-checkbox">
                        </th>
                        <th class="sticky-main sortable" onclick="sortTable('subject_name')">
                            Subject
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'subject_name' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'subject_name' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('course_type')">
                            {{ $courseLabel }}
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'course_type' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'course_type' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('sub_type')">
                            {{ $courseLabel }} Sub Type
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'sub_type' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'sub_type' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('title')">
                            Title
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'title' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'title' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('employee_name')">
                            Uploaded By
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'employee_name' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'employee_name' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('term_value')">
                            Months
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'term_value' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'term_value' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('uploaded_date')">
                            Date
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'uploaded_date' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'uploaded_date' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="syllabusTableBody">
                    {{-- Blade fallback rendering for non-JS users --}}
                    @php
                        // Group using the paginator's collection by subject name (subject_id not selected in query)
                        $grouped = $allsyllabuses->getCollection()->groupBy(function($it) { return $it->subject_name ?? 'N/A'; });
                    @endphp
                    @foreach($grouped as $subjectKey => $items)
                        @php
                            $first = $items->first();
                            $subjectId = $first->subject_id ?? $first->topic_subject_id ?? null;
                            $monthly = $items->filter(function($it){ return trim((string)($it->term_type ?? '')) === 'monthly' && trim((string)($it->term_value ?? '')) !== ''; })->pluck('term_value')->unique()->values()->all();
                            $whole = $items->contains(function($it){ $tt = trim((string)($it->term_type ?? '')); return $tt === '' || $tt === 'semester' || $tt === 'yearly'; });
                            $monthCount = count($monthly);
                            $monthText = $whole ? 'Whole semester' : ($monthCount > 0 ? ($monthCount . ' month' . ($monthCount>1? 's':'' ) . ': ' . implode(', ', $monthly)) : 'N/A');
                        @endphp
                        <tr>
                            <td class="sticky-checkbox">
                                <input type="checkbox" class="syllabus-checkbox select-checkbox" value="{{ $first->syllabus_id ?? $subjectKey }}">
                            </td>
                            <td class="sticky-main"><span class="fw-semibold">{{ $subjectKey ?? ($first->subject_name ?? 'N/A') }}</span></td>
                            <td>{{ $first->course_type ?? 'N/A' }}</td>
                            <td>{{ $first->sub_type ?? 'N/A' }}</td>
                            <td>{{ $first->title ?? 'N/A' }}</td>
                            <td>{{ $first->employee_name ?? 'Admin' }}</td>
                            <td>{{ $monthText }}</td>
                            <td>{{ \Carbon\Carbon::parse($items->max('created_at'))->format('d M Y') }}</td>
                            <td class="text-center">
                                <div class="table-actions">
                                    @if($subjectId)
                                        <a href="{{ route('instituteAdmin.syllabus.detail', ['subjectId' => $subjectId]) }}" class="action-btn action-btn-view syllabus-detail-btn d-inline-block me-1">
                                            <i class="bi bi-eye-fill"></i>
                                            <span class="small">View</span>
                                        </a>
                                        <a href="{{ route('instituteAdmin.syllabus.edit', ['subjectId' => $subjectId]) }}" class="action-btn action-btn-edit syllabus-edit-btn d-inline-block">
                                            <i class="bi bi-pencil-square"></i>
                                            <span class="small">Edit</span>
                                        </a>
                                    @else
                                        <span class="text-muted small">No subject ID</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @if($allsyllabuses->count() == 0)
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-journal-text"></i>
                                </div>
                                <h4>No syllabus uploaded yet</h4>
                                <p>Try uploading a new syllabus to get started</p>
                            </div>
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
    
        <!-- Pagination -->
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Showing {{ $allsyllabuses->firstItem() }} to {{ $allsyllabuses->lastItem() }}
                of {{ $allsyllabuses->total() }} syllabus
            </div>
    
            @if ($allsyllabuses->hasPages())
            <nav>
                <ul class="pagination mb-0">
                    {{-- Previous Page --}}
                    @if ($allsyllabuses->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">Prev</span></li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $allsyllabuses->previousPageUrl() }}">Prev</a>
                    </li>
                    @endif
    
                    {{-- Page Numbers --}}
                    @for ($i = 1; $i <= $allsyllabuses->lastPage(); $i++)
                        <li class="page-item {{ $allsyllabuses->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $allsyllabuses->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
    
                    {{-- Next Page --}}
                    @if ($allsyllabuses->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $allsyllabuses->nextPageUrl() }}">Next</a>
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
document.addEventListener('DOMContentLoaded', function() {

        const selectAllCheckbox = document.getElementById('selectAll');
        const bulkActionsContainer = document.getElementById('bulkActionsContainer');
        const selectedCountElement = document.getElementById('selectedCount');

        // 🔥 Always get fresh checkboxes
        function getAllCheckboxes() {
            return document.querySelectorAll('.syllabus-checkbox');
        }

        function getCheckedCheckboxes() {
            return document.querySelectorAll('.syllabus-checkbox:checked');
        }

        // ✅ Select All
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                getAllCheckboxes().forEach(cb => cb.checked = this.checked);
                updateSelectionUI();
            });
        }

        // ✅ Individual checkbox change (use event delegation if dynamic table)
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('syllabus-checkbox')) {
                updateSelectionUI();
            }
        });

        function updateSelectionUI() {
            const total = getAllCheckboxes().length;
            const selected = getCheckedCheckboxes().length;

            if (selected > 0) {
                bulkActionsContainer.classList.add('active');
                selectedCountElement.textContent = `${selected} syllabus selected`;
            } else {
                bulkActionsContainer.classList.remove('active');
                selectedCountElement.textContent = `0 syllabus selected`; // 🔥 FIX
            }

            // ✅ Handle select all state properly
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = selected === total && total > 0;
                selectAllCheckbox.indeterminate = selected > 0 && selected < total;
            }
        }
    });

    // Bulk Action Functions
    function clearSelection() {
        document.querySelectorAll('.syllabus-checkbox').forEach(cb => cb.checked = false);

        const selectAll = document.getElementById('selectAll');
        if (selectAll) {
            selectAll.checked = false;
            selectAll.indeterminate = false;
        }

        document.getElementById('bulkActionsContainer').classList.remove('active');

        // 🔥 FIX: reset count
        document.getElementById('selectedCount').textContent = `0 syllabus selected`;
    }

function bulkAction(action, format = null) {
    const selectedSyllabus = Array.from(document.querySelectorAll('.syllabus-checkbox:checked'))
        .map(checkbox => checkbox.value);

    if (selectedSyllabus.length === 0) {
        alert('Please select at least one syllabus.');
        return;
    }

    switch (action) {
        case 'download':
            if (!format) {
                alert("Please select a format");
                return;
            }
            const ids = selectedSyllabus.join(',');
            const url = `/syllabus/download?ids=${ids}&type=${format}`;
            window.location.href = url;
            break;

        case 'bulk_delete':
            if (confirm(
                    `Are you sure you want to delete ${selectedSyllabus.length} syllabus file(s)? This action cannot be undone.`
                )) {
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<span class="loading-spinner"></span> Deleting...';
                btn.disabled = true;

                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                    clearSelection();
                    alert(`${selectedSyllabus.length} syllabus files deleted successfully`);
                }, 1500);
            }
            break;

        default:
            alert(`${action} action triggered for ${selectedSyllabus.length} files`);
    }
}

// Sorting Functionality
function sortTable(column) {
    const currentSortBy = document.getElementById('sortBy')?.value;
    const currentSortOrder = document.getElementById('sortOrder')?.value;

    let newSortOrder = 'asc';

    if (currentSortBy === column) {
        newSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
    }

    // Create hidden inputs if they don't exist
    if (!document.getElementById('sortBy')) {
        const input1 = document.createElement('input');
        input1.type = 'hidden';
        input1.id = 'sortBy';
        input1.name = 'sort_by';
        document.getElementById('filterForm').appendChild(input1);
        
        const input2 = document.createElement('input');
        input2.type = 'hidden';
        input2.id = 'sortOrder';
        input2.name = 'sort_order';
        document.getElementById('filterForm').appendChild(input2);
    }

    document.getElementById('sortBy').value = column;
    document.getElementById('sortOrder').value = newSortOrder;

    // Submit the form
    document.getElementById('filterForm').submit();
}

// Filter form submission with loading state
document.querySelectorAll('.auto-filter').forEach(input => {
    let timer;
    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 400);
    });
});
</script>
@endsection