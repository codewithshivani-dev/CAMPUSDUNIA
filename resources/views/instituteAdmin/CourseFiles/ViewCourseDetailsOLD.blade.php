@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Class Management</title>
<style>
    /* ERP Table Styles */
    .erp-table {
        width: 100%;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        margin-bottom: 20px;
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
    
    .dropdown-menu {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    .dropdown-item {
        font-size: 14px;
        padding: 8px 14px;
        display: flex;
        align-items: center;
    }

    .dropdown-item:hover {
        background-color: #f1f5f9;
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
        gap: 13px;
        margin: 10px 0px;
    }

    .filter-group {
        position: relative;
        flex: 1;
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
    }
    
    .filter-grid {
        display: flex;
        gap: 13px;
        flex-wrap: wrap;
        flex: 1;
    }
    
    .filter-actions {
        display: flex;
        gap: 12px;
        align-items: center;
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
        white-space: nowrap;
        text-decoration: none;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        text-decoration: none;
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
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .page-title i {
        color: #3b82f6;
    }
    
    /* Action Buttons */
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
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .action-btn-edit {
        background: #fef2c8;
        color: #92400e;
        border-color: #fde68a;
    }
    
    .action-btn-delete {
        background: #fee2e2;
        color: #dc2626;
        border-color: #fecaca;
    }
    
    .action-btn-view {
        background: #e0f2fe;
        color: #0369a1;
        border-color: #bae6fd;
    }    
    
    .action-btn-edit:hover {
        background: #fde68a;
    }
    
    .action-btn-delete:hover {
        background: #fecaca;
    }
    
    .action-btn-view:hover {
        background: #bae6fd;
        color: #0369a1;
        text-decoration: none;
    }
    
    .custom-gap {
        gap: 5px;
    }
    
    /* Add New Button */
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
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        text-decoration: none;
    }
    
    /* Course Logo */
    .course-logo {
        width: 50px;
        height: 50px;
        border-radius: 6px;
        object-fit: cover;
        border: 2px solid #e2e8f0;
    }
    
    /* Branches Styling */
    .branches-container {
        display: inline-flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    
    .branches-container.multi-branches {
        display: block;
    }
    
    .branches-container.multi-branches .branch-badge {
        display: block;
        margin-bottom: 4px;
    }
    
    .branch-badge {
        display: inline-block;
        padding: 4px 8px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        font-size: 11px;
        color: #475569;
        margin: 2px;
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
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
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
    
    /* Alert Messages */
    .alert {
        border: none;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .alert-success {
        background: #dcfce7;
        border: 1px solid #bbf7d0;
        color: #166534;
    }
    
    .alert-danger {
        background: #fee2e2;
        border: 1px solid #fecaca;
        color: #991b1b;
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
    
    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .modal-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        border-radius: 12px 12px 0 0;
        padding: 20px 24px;
    }
    
    .modal-title {
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .modal-body {
        padding: 24px;
    }
    
    /* Side Panel */
    #overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.3);
        z-index: 1049;
    }
    
    #sidePanel {
        position: fixed;
        top: 0;
        right: -650px;
        width: 650px;
        height: 100%;
        background: #fff;
        z-index: 1050;
        box-shadow: -2px 0 10px rgba(0, 0, 0, 0.2);
        padding: 16px;
        transition: right 0.3s ease-in-out;
        overflow-y: auto;
    }

    #sidePanel.open {
        right: 0;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .filter-grid {
            flex-direction: column;
            gap: 12px;
        }
        
        .filter-group {
            min-width: 100%;
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
        
        #sidePanel {
            width: 100%;
            right: -100%;
        }
        
        .bulk-actions-container {
            flex-direction: column;
            align-items: stretch;
        }
        
        .bulk-actions-container .d-flex {
            flex-wrap: wrap;
            gap: 8px;
        }
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
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-book"></i>
            @php
                $courseLabel = (isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type === 'School')
                    ? 'Class'
                    : 'Class';
            @endphp
            {{ $courseLabel }} Management
        </h1>
        <a href="{{ route('course.basic.form') }}" class="add-btn">
            <i class="bi bi-plus-circle"></i>
            Add New {{ $courseLabel == 'Class' ? 'Class' : 'Course' }}
        </a>
    </div>

    <!-- Messages -->
    @if (session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle"></i>
        {{ session('error') }}
    </div>
    @endif

    <!-- Filters Section -->
    <div class="filter-container">
        <form method="GET" class="filter-form" id="filterForm">
            <div class="filter-grid">
                    <!-- category -->
                <div class="filter-group d-none">
                    <i class="bi bi-tags"></i>

                    <input type="text"
                        class="filter-input"
                        list="categoryList"
                        placeholder="Category"
                        id="categoryInput">

                    <input type="hidden" name="category_id" id="categoryId">

                    <datalist id="categoryList">
                        @foreach($departmentCategories as $cat)
                            <option value="{{ $cat->category_name }}" data-id="{{ $cat->id }}"></option>
                        @endforeach
                    </datalist>
                </div>

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

                <button type="submit" class="btn-filter btn-filter-primary d-none">
                    <i class="bi bi-funnel"></i>
                    Apply Filters
                </button>
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
    <div class="table-responsive">
        <input type="hidden" id="filteredTotal" value="{{ $courses->total() }}">
        <table class="erp-table" id="coursesTable">
            <thead>
                <tr>
                    <th class="d-none" width="40">
                        <input type="checkbox" id="selectAll" class="select-checkbox">
                    </th>
                    <th class="sortable" onclick="sortTable('name')">
                        {{ $courseLabel == 'Classes' ? 'Class' : 'Class' }} 
                        <br>
                        <span class="small" style="place-content: end;">Total students</span>
                        <div class="sort-icons">
                            <i class="sort-icon bi bi-caret-up-fill"></i>
                            <i class="sort-icon bi bi-caret-down-fill"></i>
                        </div>
                    </th>
                    <th class="sortable d-none" onclick="sortTable('category')">
                        Category
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
                    <th class="sortable d-none" onclick="sortTable('date')">
                        Added Date
                        <div class="sort-icons">
                            <i class="sort-icon bi bi-caret-up-fill"></i>
                            <i class="sort-icon bi bi-caret-down-fill"></i>
                        </div>
                    </th>
                    <th class="d-none text-center">Actions</th>
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
                    <td>
                        <div class="">
                            <span style="place-content: end;">{{ $course->sub_type ?? $course->course->finacp_merchant_sub_category_type }}</span>
                            <br>
                            <span class="badge {{ ($course->student_count ?? 0) > 0 ? 'bg-success text-white' : 'bg-secondary' }}" 
                                style="font-size: 12px; padding: 4px 4px;">
                                <i class="bi bi-people-fill me-1"></i>
                                {{ $course->student_count ?? 0 }} Students
                            </span>
                        </div>
                    </td>
                    <!-- <td>
                        {{ $course->course->departmentCategory->category_name ?? 'N/A' }}
                    </td> -->
                    <td>
                        {{ optional($course->getRelation('department'))->department }}
                        <br>
                        <span class="small">
                            {{ $course->course->departmentCategory->category_name ?? 'N/A' }}
                        </span>
                    </td>
                    <td>
                        {{ $course->course_length ?? '' }} {{ $course->course_duration ?? '' }}
                        @if(empty($course->course_length) && empty($course->course_duration))
                            -
                        @endif
                    </td>
                        <!-- @if($course->course->productDetails->count() > 0)
                            @foreach($course->course->productDetails as $branch)
                                <span class="d-block medium-text">
                                    {{ \Illuminate\Support\Str::limit($branch->sub_type ?? 'Branch', 10) }}
                                </span>
                            @endforeach
                        @endif -->                    
                    <td>
                        @if(!empty($course->sections) && count($course->sections) > 0)

                        @foreach($course->sections as $section)

                        @php
                        $totalSeats = $section['seats'] ?? 0;
                        $students = $section['student_count'] ?? 0;
                        $available = $section['available_seats'] ?? 0;
                        $percentage = $totalSeats > 0 ? ($students / $totalSeats) * 100 : 0;
                        @endphp

                        <div class="mb-2">
                            <!-- Section Name -->
                            <div class="fw-bold mb-1">
                                <!--<i class="bi bi-grid-3x3-gap-fill text-primary me-1"></i>-->
                                {{ $section['name'] ?? 'N/A' }}
                            </div>

                            <!-- Capacity Info -->
                            <div class="fw-semibold small">
                                Capacity: {{ $totalSeats }} |
                                Occupied: <span class="fw-bold" style="color: orangered;">{{ $students }}</span> |
                                Available:
                                <span class="fw-bold 
                                    {{ $available > 5 ? 'text-success' :
                                    ($available > 0 ? 'text-success' : 'text-success') }}">
                                    {{ $available }}
                                </span>
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
                            {{ ucfirst($status) }}
                        </span>
                    </td>

                    <td class="d-none">
                        @if($course->created_at)
                            {{ $course->created_at->format('M d, Y') }}
                        @else
                            -
                        @endif
                    </td>
                    
                    <td class="d-none text-center">
                        <div class="custom-gap d-flex justify-content-center">
                            <button class="action-btn action-btn-view view-course d-block" 
                                    data-id="{{ $course->id }}"
                                    title="View Details">
                                <div>
                                    <i class="bi bi-eye"></i>
                                </div>
                                <div>
                                    <span class="small">View</span>
                                </div>
                            </button>                        
                            <button class="action-btn action-btn-edit edit-course d-none" 
                                    data-id="{{ $course->id }}"
                                    title="Edit">
                                <div>
                                    <i class="bi bi-pencil"></i>
                                </div>
                                <div>
                                    <span class="small">Edit</span>
                                </div>
                            </button>
                            <button class="action-btn action-btn-delete delete-course d-none" 
                                    data-id="{{ $course->id }}"
                                    data-name="{{ $course->sub_type ?? $course->finacp_merchant_sub_category_type }}"
                                    title="Delete">
                                <div>
                                    <i class="bi bi-trash"></i>
                                </div>
                                <div>
                                    <span class="small">Delete</span>
                                </div>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center">
        <div class="text-sm text-gray-600">
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
        <a href="{{ route('course.basic.form') }}" class="btn-filter btn-filter-primary mt-2">
            <i class="bi bi-plus-circle"></i>
            Add New {{ $courseLabel == 'Classes' ? 'Class' : 'Course' }}
        </a>
    </div>
    @endif
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
                $('#filterForm').submit();
            }, 500);
        }

        // Department mapping - FIXED
        $('#departmentInput').on('input', function() {
            const mapped = mapDatalistValue('departmentInput', 'departmentId', 'departmentList');
            if (mapped) {
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
</script>
@endsection