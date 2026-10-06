@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Assign Subjects to Students</title>
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
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
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
        gap: 15px;
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
    
    
    /* Subjects count */
    .subjects-count {
        background: #e0f2fe;
        color: #0369a1;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 500;
        border: 1px solid #bae6fd;
    }
    
    /* View Panel Styles */
    #viewStudentSubjectsPanel {
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
    
    #viewStudentSubjectsPanel.open {
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
    
    /* Badge for course info */
    .course-badge {
        background: #f3e8ff;
        color: #7c3aed;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        border: 1px solid #e9d5ff;
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
        <h1 class="page-title">Assign Subjects - Students</h1>
        <a href="{{ route('student-subject-assignments.form') }}" class="add-btn">
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
                {{-- Student Name --}}
                <div class="filter-group">
                    <i class="bi bi-person"></i>
                    <input type="text" name="student_name" class="filter-input"
                        value="{{ request('student_name') }}" placeholder="Student Name">
                </div>

                {{-- Student ID --}}
                <div class="filter-group">
                    <i class="bi bi-person-badge"></i>
                    <input type="text" name="student_id" class="filter-input"
                        value="{{ request('student_id') }}" placeholder="Student ID">
                </div>

                {{-- Course Type --}}
                <div class="filter-group">
                    <i class="bi bi-book"></i>
                    <input type="text" name="course_type" class="filter-input"
                        value="{{ request('course_type') }}" placeholder="{{ $courseLabel }} Type">
                </div>

                {{-- Status --}}
                <div class="filter-group">
                    <i class="bi bi-info-circle"></i>
                    <select name="status" class="filter-input">
                        <option value="">Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Action buttons -->
                <button type="submit" class="btn-filter btn-filter-primary">
                    <i class="bi bi-funnel"></i>
                    Apply Filters
                </button>
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
                        <th class="sortable" onclick="sortTable('student_name')">
                            Student Name
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('student_id')">
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
                            <input type="checkbox" class="assignment-checkbox select-checkbox" value="{{ $assignment->id }}">
                        </td>
                        <td class="d-none">{{ $index+1 }}</td>
                        <td>{{ $assignment->student_name ?? 'N/A' }}</td>
                        <td><span class="student-id">{{ $assignment->student_hash_id ?? 'N/A' }}</span></td>
                        <td>
                            <div class="d-flex flex-column">
                                <span>{{ $assignment->course_type ?? 'N/A' }}</span>
                                @if($assignment->sub_type)
                                <small class="text-muted">{{ $assignment->sub_type }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="subjects-count">{{ $assignment->subject_count ?? 0 }} Subjects</span>
                        </td>
                        <td>{{ date('d-m-Y', strtotime($assignment->assigned_date)) }}</td>
                        <td>
                            <span class="status-badge status-active">Active</span>
                        </td>
                        <td class="text-center">
                            <div class="table-actions">
                                <div>
                                        <a href="{{ url('/student-subject-assignments/' . $assignment->student_hash_id) }}" class="action-btn action-btn-view d-block"
                                            title="View Details">
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
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

    function bulkAction(action , format=null) {
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

                    const url = `/download/student-subjects?ids=${ids}&type=${format}`;

                    window.location.href = url;

                    break;
                
            case 'bulk_delete':
                if (confirm(`Are you sure you want to remove ${selectedCheckboxes.length} assignment(s)? This action cannot be undone.`)) {
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
window.viewStudentSubjects = function (studentId) {
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
        dataType: 'html', // ✅ IMPORTANT
        success: function (response) {
            // response is FULL BLADE HTML
            $('#studentSubjectsDetails').html(response);
        },
        error: function (xhr) {
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

    // Initialize global functions
    window.closeAssignmentPanel = function() {
        // Close assignment panel if exists
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
        // Reset form logic if exists
        const form = document.getElementById('assignmentForm');
        if (form) {
            form.reset();
        }
    };

    window.resetDownstreamSelects = function() {
        // Reset selects logic if exists
        const deptSelect = document.getElementById('department_id');
        const courseSelect = document.getElementById('course_type');
        const subTypeSelect = document.getElementById('course_subtype_id');
        
        if (deptSelect) deptSelect.disabled = true;
        if (courseSelect) courseSelect.disabled = true;
        if (subTypeSelect) subTypeSelect.disabled = true;
    };

document.addEventListener('DOMContentLoaded', function () {

            const form = document.getElementById('filterForm');
            let typingTimer;

            const delay = 400;

            const autoInputs = [
                'course_type',
                'sub_type',
                'course_duration'
            ];

            autoInputs.forEach(name => {

                const input = document.querySelector(`input[name="${name}"]`);

                if (!input) return;

                input.addEventListener('input', function () {

                    clearTimeout(typingTimer);

                    typingTimer = setTimeout(() => {
                        form.submit();
                    }, delay);

                });
            });

        });
</script>
@endsection