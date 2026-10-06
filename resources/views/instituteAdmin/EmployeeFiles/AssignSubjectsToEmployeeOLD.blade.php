@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Assigned Subjects to Employees</title>
<style>
    /* ERP Table Styles */
    .erp-table {
        width: 100%;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
    }
    
    .erp-table thead {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .erp-table th {
        padding: 12px 16px;
        font-weight: 600;
        color: #475569;
        text-align: left;
        font-size: 14px;
        border-bottom: 1px solid #e2e8f0;
        cursor: pointer;
        user-select: none;
        transition: background-color 0.2s;
        position: relative;
    }
    
    .erp-table th:hover {
        background-color: #f1f5f9;
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
        color: #cbd5e1;
        font-size: 12px;
        line-height: 1;
    }
    
    .sort-icon.active {
        color: #3b82f6;
    }
    
    .erp-table td {
        padding: 12px 16px;
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
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .erp-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Status Badges */
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
        transition: transform 0.2s;
    }
    
    .status-badge:hover {
        transform: scale(1.05);
    }
    
    .status-active {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    
    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    
    /* Bulk Actions */
    .bulk-actions-container {
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    animation: slideDown 0.3s ease;
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
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
        font-weight: 500;
        color: #475569;
        margin-right: auto;
        font-size: 14px;
    }
    
    .bulk-action-btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        color: #475569;
        transition: all 0.2s;
        font-size: 14px;
        display: flex;
        align-items: center;
        border: 1px solid transparent;
        margin-right: 5px;
    }
    
    .bulk-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .bulk-action-btn:active {
        transform: translateY(0);
    }
    
    .bulk-action-btn.download {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    
    .bulk-action-btn.download:hover {
        background: #bbf7d0;
    }
    
    .bulk-action-btn.delete {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    
    .bulk-action-btn.delete:hover {
        background: #fecaca;
    }
    
    .bulk-action-btn.clear {
        background: transparent;
        color: #64748b;
        border: 1px solid #cbd5e1;
    }
    
    .bulk-action-btn.clear:hover {
        background: #f1f5f9;
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
        border-color: #3b82f6;
    }
    
    .select-checkbox:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
    }
    
    /* Filter container */
    .filter-container {
        background: #fff;
        border-radius: 8px;
        padding: 6px 14px;
        margin-bottom: 20px;
        border: 1px solid #e2e8f0;
        animation: slideUp 0.3s ease;
    }

    .filter-form {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        
        margin:10px 0px;
    }

    .filter-grid {
        display: flex;
        gap: 13px;
        flex-wrap: wrap;
        flex: 1;
    }
    
    .filter-group {
        position: relative;
        min-width: 250px;
    }
    
    .filter-group .bi {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        z-index: 1;
    }
    
    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 8px 10px 8px 35px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 14px;
        background: #fff;
        transition: all 0.2s;
    }
    
    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        transform: translateY(-1px);
    }
    
    .filter-group input:hover,
    .filter-group select:hover {
        border-color: #cbd5e1;
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
    
    .filter-actions {
        display: flex;
        gap: 12px;
    }
    
    .btn-filter {
        padding: 8px 20px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        text-decoration: none;
    }
    
    .btn-filter:active {
        transform: translateY(0);
    }
    
    .btn-filter-primary {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    
    .btn-filter-primary:hover {
        background: #2563eb;
        color: white;
    }
    
    .btn-filter-secondary {
        background: #f1f5f9;
        color: #475569;
        border-color: #e2e8f0;
    }
    
    .btn-filter-secondary:hover {
        background: #e2e8f0;
        color: #475569;
    }
    
    /* Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e2e8f0;
        animation: fadeIn 0.5s ease;
    }
    
    .page-title {
        font-size: 24px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }
    
    /* Banner */
    .banner {
        height: 260px;
        border-radius: 18px;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #2154BE, #3E70B3);
        display: flex;
        align-items: center;
        animation: fadeIn 0.8s ease;
        margin-bottom: 30px;
    }
    
    .banner img.bg {
        width: 100%;
        height: 260px;
        object-fit: cover;
        filter: brightness(0.8);
    }
    
    .banner .meta {
        position: absolute;
        left: 28px;
        bottom: 22px;
        background: rgba(255, 255, 255, 0.95);
        padding: 14px 20px;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        animation: slideInLeft 0.5s ease;
    }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    /* Action Buttons */
    .add-btn {
        padding: 10px 20px;
        background-color: #007BFF;
        border: none;
        color: white;
        cursor: pointer;
        border-radius: 5px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        text-decoration: none;
    }
    
    .add-btn:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        text-decoration: none;
        color: white;
    }
    
    .table-actions {
        display: flex;
        gap: 5px;
        justify-content: center;
    }
    
    .action-btn {
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 5px;
        justify-content: center;
        text-decoration: none;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        text-decoration: none;
    }
    
    .action-btn:active {
        transform: translateY(0);
    }
    
    .action-btn-view {
        background: #e0f2fe;
        color: #0369a1;
        border-color: #bae6fd;
    }
    
    .action-btn-edit {
        background: #fef2c8;
        color: #92400e;
        border-color: #fef2c8;
    }
    
    .action-btn-delete {
        background: #fee2e2;
        color: #dc2626;
        border-color: #fecaca;
    }
    
    .action-btn-view:hover {
        background: #bae6fd;
        color: #0369a1;
    }
    
    .action-btn-edit:hover {
        background: #fef2c8;
        color: #92400e;
    }
    
    .action-btn-delete:hover {
        background: #fecaca;
        color: #dc2626;
    }
    
    /* Loading Animation */
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #3b82f6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #64748b;
    }
    
    .empty-state-icon {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 16px;
    }
    
    /* Subject ID styling */
    .subject-id {
        font-size: 11px;
        color: #6c757d;
        font-family: monospace;
        background: #f8f9fa;
        padding: 2px 6px;
        border-radius: 3px;
        border: 1px solid #e9ecef;
    }
       

    
    /* View Panel Styles */
    #viewAssignmentPanel {
        position: fixed;
        top: 0;
        right: -100%;
        width: 100%;
        max-width: 800px;
        height: 100vh;
        background: #fff;
        box-shadow: -2px 0 8px rgba(0,0,0,.2);
        z-index: 300;
        overflow-y: auto;
        transition: right 0.4s ease;
    }
    
    #viewAssignmentPanel.open {
        right: 0;
    }
    
    .view-panel-header {
        padding: 15px;
        font-size: 18px;
        font-weight: bold;
        background-color: #DCEEFF;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #ccc;
    }
    
    .view-panel-content {
        padding: 15px;
    }
    
    .view-section {
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
    }
    
    .view-section-header {
        background-color: #F5F5F5;
        padding: 10px 15px;
        font-weight: bold;
        border-bottom: 1px solid #ddd;
    }
    
    .view-section-body {
        padding: 15px;
    }
    
    .view-row {
        display: flex;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .view-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .view-label {
        font-weight: bold;
        width: 40%;
        color: #555;
    }
    
    .view-value {
        width: 60%;
    }
    
    /* Alert styling */
    .alert {
        border-radius: 8px;
        border: 1px solid transparent;
        padding: 12px 16px;
        margin-bottom: 20px;
        animation: fadeIn 0.3s ease;
    }
    
    .alert-success {
        background-color: #dcfce7;
        color: #166534;
        border-color: #bbf7d0;
    }
    
    .alert-danger {
        background-color: #fee2e2;
        color: #991b1b;
        border-color: #fecaca;
    }
    
    .alert .btn-close {
        padding: 12px;
    }
    
    /* Pagination styling */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #e2e8f0;
    }
    
    .pagination-info {
        font-size: 14px;
        color: #64748b;
    }
    
    /* Employee name styling */
    .employee-name {
        font-weight: 500;
        color: #1e293b;
    }
    
    .employee-id {
        font-size: 11px;
        color: #64748b;
        margin-top: 2px;
    }
    
    /* Subject display */
    .subject-display {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .subject-name {
        font-weight: 500;
        color: #334155;
    }
    
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
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
        
        .filter-actions {
            width: 100%;
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
        
        .table-actions {
            flex-direction: column;
            gap: 4px;
        }
        
        .action-btn {
            width: 100%;
        }
        
        .view-row {
            flex-direction: column;
        }
        
        .view-label,
        .view-value {
            width: 100%;
        }
        
        .view-label {
            margin-bottom: 5px;
        }
    }
</style>

@php
    $courseLabel = (isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type === 'School')
        ? 'Class'
        : 'Course';
@endphp

<!-- Header with institute name -->

<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">Assign Subjects - Employee</h1>
        <a href="{{ route('assign-subjects.toemployee') }}" class="add-btn">
            <i class="bi bi-plus-circle"></i>
            Assign Subjects
        </a>
    </div>

    {{-- Messages --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
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
                          
                        </datalist>                        
                </div>

                {{-- Subject Name --}}
                <div class="filter-group">
                    <i class="bi bi-journal-text"></i>
                    <input type="text" list="subjectNamesList" name="subject_name" class="filter-input"
                        value="{{ request('subject_name') }}" placeholder="Subject Name">
                        <datalist id="subjectNamesList">
                          
                        </datalist>
                </div>

                {{-- Course Type --}}
                <div class="filter-group">
                    <i class="bi bi-book"></i>
                    <input type="text" name="course_type" class="filter-input"
                        value="{{ request('course_type') }}" placeholder="{{ $courseLabel }} Type">
                </div>

                {{-- Subject Type --}}
                <div class="filter-group">
                    <i class="bi bi-diagram-3"></i>
                    <select name="subject_type" class="filter-input">
                        <option value=""> Subject Types</option>
                        <option value="main_subject" {{ request('subject_type') == 'main_subject' ? 'selected' : '' }}>Main Subject</option>
                        <option value="sub_subject" {{ request('subject_type') == 'sub_subject' ? 'selected' : '' }}>Sub Subject</option>
                    </select>
                </div>

                {{-- Section --}}
                <div class="filter-group">
                    <i class="bi bi-columns"></i>
                    <select name="section_filter" class="filter-input">
                        <option value=""> Sections</option>
                        <option value="all" {{ request('section_filter') == 'all' ? 'selected' : '' }}>All Sections</option>
                        <option value="specific" {{ request('section_filter') == 'specific' ? 'selected' : '' }}>Specific Section</option>
                    </select>
                </div>

                <button type="submit" class="btn-filter btn-filter-primary">
                    <i class="bi bi-funnel"></i>
                    Apply Filters
                </button>
                
                <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary">
                    <i class="bi bi-x-circle"></i>
                    Reset Filters
                </a>
            </div>

            <div class="filter-actions">

            </div>
            
            <!-- Hidden sort inputs -->
            <input type="hidden" name="sort_by" id="sortBy" value="{{ request('sort_by', 'assigned_date') }}">
            <input type="hidden" name="sort_order" id="sortOrder" value="{{ request('sort_order', 'desc') }}">
        </form>
    </div>

    {{-- Bulk Actions Container --}}
    <div class="bulk-actions-container active d-none" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 assignments selected</div>
        <div class="d-flex flex-wrap">
            <span style="margin-right:5px;">
                    <select class="bulk-action-btn download" onchange="bulkAction('download', this.value)"
                        style="margin-right: 0px;margin-top:0px;">
                        <option value="">Download</option>
                        <option value="excel">Excel</option>
                        <option value="csv">CSV</option>
                    </select>
                </span>
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

    {{-- Assignments Table --}}
    <div class="table-responsive">
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
                    <th class="sortable" onclick="sortTable('employee')">
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
                    <td>
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
                            <div>
                               <a href="{{ url('/assign-subjects/' . $assign->id) }}"
                                            class="action-btn action-btn-view d-block" title="View Details">
                                            <div>
                                                <i class="fa-regular fa-eye"></i>
                                            </div>
                                            <div>
                                                <span class="small">View</span>
                                            </div>
                                        </a>
                            </div>
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
<!-- Bootstrap Icons -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<script>
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

    function bulkAction(action, format=null) {
        const selectedCheckboxes = Array.from(document.querySelectorAll('.assignment-checkbox:checked'))
            .map(checkbox => checkbox.value);
        
        if (selectedCheckboxes.length === 0) {
            alert('Please select at least one assignment.');
            return;
        }
        
        switch(action) {
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
                if (confirm(`Are you sure you want to delete ${selectedCheckboxes.length} assignment(s)? This action cannot be undone.`)) {
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
        $("#assignmentDetails").html('<div class="text-center py-4"><div class="loading-spinner mb-2"></div><p>Loading assignment details...</p></div>');

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
        const submitBtn = this.querySelector('.btn-filter-primary');
        const originalHTML = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Applying...';
        submitBtn.disabled = true;
        
        // Re-enable button after 2 seconds in case of error
        setTimeout(() => {
            submitBtn.innerHTML = originalHTML;
            submitBtn.disabled = false;
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

