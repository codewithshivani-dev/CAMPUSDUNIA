@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<title>Course Management</title>
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
    }

    .page-title {
        font-weight: 600;
        color: white;
        margin: 0;
        display: flex;
        flex-wrap: wrap;
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
        /*padding: 12px 25px;*/
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
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }

    /* Filter Container */
    .filter-container {
        background: white;
        border-radius: 15px;
        padding: 20px 25px;
        margin-bottom: 25px;
        border: 2px solid #e0e0e0;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    .filter-form {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        align-items: flex-end;
    }

    .filter-grid {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        flex: 1;
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

    .filter-group .filter-input {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 14px;
        background: #fff;
        transition: all 0.3s;
    }

    .filter-group .filter-input:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .filter-group .filter-input:hover {
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
        font-size: 14px;
    }

    .btn-filter-primary {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
    }

    .btn-filter-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
    }

    .btn-filter-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 2px solid #e0e0e0;
    }

    .btn-filter-secondary:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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
        color: #ffff;
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
        transform: translateY(-2px);
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

    /* Course Logo */
    .course-logo {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        object-fit: cover;
        border: 3px solid #4361ee;
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.2);
    }

    /* Branches Styling */
    .branches-container {
        display: inline-flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .branches-container.multi-branches {
        display: block;
    }

    .branches-container.multi-branches .branch-badge {
        display: inline-block;
        margin-bottom: 5px;
        margin-right: 5px;
    }

    .branch-badge {
        display: inline-block;
        padding: 5px 12px;
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        border: 1px solid #4361ee40;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        color: #4361ee;
        box-shadow: 0 2px 4px rgba(67, 97, 238, 0.1);
    }

    /* Action Buttons */
    .action-btn {
        padding: 8px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        min-width: 70px;
        justify-content: center;
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

    .action-btn i {
        font-size: 14px;
    }

    .custom-gap {
        gap: 10px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        background: #f8fafc;
        border-radius: 15px;
        border: 2px dashed #e0e0e0;
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

    /* Loading Animation */
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

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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
        filter: invert(1);
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

        .filter-form {
            flex-direction: column;
        }

        .filter-grid {
            flex-direction: column;
            gap: 15px;
        }

        .filter-group {
            min-width: 100%;
        }

        .filter-actions {
            width: 100%;
            justify-content: center;
        }

        .erp-table th,
        .erp-table td {
            padding: 10px 12px;
            font-size: 13px;
        }

        .action-btn {
            padding: 6px 10px;
            min-width: 60px;
            font-size: 11px;
        }

        .custom-gap {
            gap: 5px;
        }
    }

    @media (max-width: 480px) {
        .bulk-actions-container {
            flex-direction: column;
            gap: 10px;
            text-align: center;
        }

        .selected-count {
            margin-right: 0;
            margin-bottom: 10px;
        }

        .bulk-actions-container .d-flex {
            justify-content: center;
        }
    }
    .table-responsive{
        overflow-x: hidden;
    }
</style>

<div class="container-fluid">
    <!-- Page Header with Gradient -->
    <div class="page-header">
        <h4 class="page-title">
            <i class="bi bi-book"></i>
            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                Class Management
            @else
                Course Management
            @endif
        </h4>
        <a href="{{ route('AddInstitutecourses') }}" class="btn add-btn">
            <i class="bi bi-plus-circle"></i>
            Add New
        </a>
    </div>

    <!-- Filters Section -->
    <div class="filter-container d-none">
        <form method="GET" class="filter-form" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <i class="bi bi-tags"></i>
                    <select class="filter-input" id="categoryFilter">
                        <option value="">All Categories</option>
                        @foreach($departmentCategories as $category)
                            <option value="{{ $category->category_name }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <i class="bi bi-building"></i>
                    <select class="filter-input" id="departmentFilter">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->department }}">{{ $dept->department }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <i class="bi bi-search"></i>
                    <input type="text" class="filter-input" id="searchFilter" 
                           placeholder="Search courses...">
                </div>
            </div>

            <div class="filter-actions">
                <button type="button" class="btn-filter btn-filter-primary" onclick="applyFilters()">
                    <i class="bi bi-funnel"></i>
                    Apply
                </button>
                <button type="button" class="btn-filter btn-filter-secondary" onclick="resetFilters()">
                    <i class="bi bi-x-circle"></i>
                    Reset
                </button>
            </div>
        </form>
    </div>

    <!-- Bulk Actions Container -->
    <div class="bulk-actions-container d-none" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 selected</div>
        <div class="d-flex flex-wrap">
            <button class="bulk-action-btn delete" onclick="bulkDelete()">
                <i class="bi bi-trash"></i>
                Delete Selected
            </button>
            <button class="bulk-action-btn clear mr-0" onclick="clearSelection()">
                <i class="bi bi-x-lg"></i>
                Clear
            </button>
        </div>
    </div>

    <div>
        <!-- Courses Table -->
        <div class="table-responsive custom-table-wrapper" id="tableWrapper">
            <table class="erp-table" id="coursesTable">
                <thead>
                    <tr>
                        <th class="d-none" width="40">
                            <input type="checkbox" id="selectAll" class="select-checkbox">
                        </th>
                        <th class="sticky-main-2 sortable" onclick="sortTable('name')">
                            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                Class Name
                            @else
                                Course Name
                            @endif
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="d-none">Logo</th>
                        <th class="sortable" onclick="sortTable('category')">
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
                        <th class="sortable" onclick="sortTable('branches')">
                            Streams
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('date')">
                            Added Date
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
       
                    @foreach($courses as $course)
    
                    <tr class="course-item" 
                        data-category="{{ $course->departmentCategory->category_name ?? '' }}"
                        data-department="{{ optional($course->getRelation('department'))->department }}"
                        data-name="{{ $course->finacp_merchant_sub_category_type }}"
                        data-id="{{ $course->finacp_merchant_sub_category_id }}"
                        data-branches="{{ $course->productDetails->count() }}"
                        data-date="{{ $course->created_at->timestamp }}">
                        <td class="d-none">
                            <input type="checkbox" class="course-checkbox select-checkbox" value="{{ $course->finacp_merchant_sub_category_id }}">
                        </td>
                        <td class="sticky-main-2 fw-semibold">{{ $course->finacp_merchant_sub_category_type }}</td>
                        <td class="d-none">
                            @if($course->finacp_merchant_sub_category_logo)
                                <img src="{{ asset('storage/' . $course->finacp_merchant_sub_category_logo) }}" 
                                     class="course-logo"
                                     alt="{{ $course->finacp_merchant_sub_category_type }}">
                            @else
                                <div class="text-muted text-center">-</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge" style="background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #4361ee; padding: 6px 12px; border-radius: 30px;">
                                {{ $course->departmentCategory->category_name ?? 'N/A' }}
                            </span>
                        </td>
                        
                        <td>
                            <span class="badge" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1e40af; padding: 6px 12px; border-radius: 30px;">
                                {{ optional($course->getRelation('department'))->department }}
                            </span>
                        </td>
                      
                        <td>
                            @if($course->productDetails->count() > 0)
                                <div class="branches-container {{ $course->productDetails->count() > 1 ? 'multi-branches' : '' }}">
                                    @foreach($course->productDetails as $branch)
                                        <span class="branch-badge">
                                            {{ \Illuminate\Support\Str::limit($branch->sub_type, 15) }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $course->created_at->format('M d, Y') }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="custom-gap d-flex justify-content-center">
                                <button class="action-btn action-btn-edit edit-course" 
                                        data-id="{{ $course->finacp_merchant_sub_category_id }}"
                                        title="Edit">
                                       <div>
                                            <i class="bi bi-pencil"></i>
                                       </div>
                                       <div>
                                            <span class="small">Edit</span>
                                       </div>
                                </button>
                                <button class="action-btn action-btn-delete delete-course" 
                                        data-id="{{ $course->finacp_merchant_sub_category_id }}"
                                        data-name="{{ $course->finacp_merchant_sub_category_type }}"
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
                    @if($courses->count() == 0)
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-book"></i>
                                </div>
                                <h4>No records found</h4>
                                <p>Try adjusting your filters or add a new record</p>
                                <a href="{{ route('AddInstitutecourses') }}" class="add-btn" style="display: inline-block; background: var(--primary-gradient); color: white; padding: 10px 25px;">
                                    <i class="bi bi-plus-circle"></i>
                                    Add New
                                </a>
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
                            Edit Course
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-tag me-1 text-primary"></i>
                                Course Name *
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
</div>

<script src="/instituteadmin-vendor/jquery/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Bulk Selection Management
        const selectAllCheckbox = $('#selectAll');
        const courseCheckboxes = $('.course-checkbox');
        const bulkActionsContainer = $('#bulkActionsContainer');
        const selectedCountElement = $('#selectedCount');

        // Select All functionality
        selectAllCheckbox.on('change', function() {
            courseCheckboxes.prop('checked', this.checked);
            updateSelectionUI();
        });

        // Individual checkbox change
        courseCheckboxes.on('change', updateSelectionUI);

        function updateSelectionUI() {
            const selectedCount = $('.course-checkbox:checked').length;
            
            if (selectedCount > 0) {
                bulkActionsContainer.addClass('active');
                selectedCountElement.text(selectedCount + ' selected');
                
                // Update select all checkbox state
                selectAllCheckbox.prop('checked', selectedCount === courseCheckboxes.length);
                selectAllCheckbox.prop('indeterminate', selectedCount > 0 && selectedCount < courseCheckboxes.length);
            } else {
                bulkActionsContainer.removeClass('active');
                selectAllCheckbox.prop('checked', false);
                selectAllCheckbox.prop('indeterminate', false);
            }
        }

        // Clear selection
        window.clearSelection = function() {
            courseCheckboxes.prop('checked', false);
            updateSelectionUI();
        };

        // Bulk delete
        window.bulkDelete = function() {
            const selectedCourses = $('.course-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
            
            if (selectedCourses.length === 0) {
                alert('Please select at least one course.');
                return;
            }
            
            if (confirm(`Delete ${selectedCourses.length} selected course(s)?`)) {
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
                    alert(`${selectedCourses.length} courses deleted successfully`);
                    // In real implementation, send AJAX request to delete all selected courses
                }, 1000);
            }
        };

        // Sorting functionality
        let currentSortColumn = null;
        let currentSortOrder = 'asc';

        window.sortTable = function(column) {
            const rows = $('.course-item').get();
            
            rows.sort((a, b) => {
                const aValue = $(a).data(column);
                const bValue = $(b).data(column);
                
                if (column === 'date') {
                    return currentSortOrder === 'asc' ? aValue - bValue : bValue - aValue;
                } else if (column === 'branches') {
                    return currentSortOrder === 'asc' ? aValue - bValue : bValue - aValue;
                } else {
                    const aText = String(aValue || '').toLowerCase();
                    const bText = String(bValue || '').toLowerCase();
                    return currentSortOrder === 'asc' 
                        ? aText.localeCompare(bText)
                        : bText.localeCompare(aText);
                }
            });

            // Toggle sort order for next click
            if (currentSortColumn === column) {
                currentSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
            } else {
                currentSortColumn = column;
                currentSortOrder = 'asc';
            }

            // Update sort icons
            $('.sort-icon').removeClass('active');
            $(`.sortable[onclick*="${column}"] .sort-icon`).eq(currentSortOrder === 'asc' ? 0 : 1).addClass('active');

            // Reorder table
            const tbody = $('#coursesTable tbody');
            tbody.empty();
            rows.forEach(row => tbody.append(row));
        };

        // Apply filters function
        window.applyFilters = function() {
            const categoryValue = $('#categoryFilter').val().toLowerCase();
            const departmentValue = $('#departmentFilter').val().toLowerCase();
            const searchValue = $('#searchFilter').val().toLowerCase();
            
            $('.course-item').each(function() {
                const $row = $(this);
                const name = $row.data('name').toLowerCase();
                const category = $row.data('category').toLowerCase();
                const department = $row.data('department').toLowerCase();
                
                const matchesCategory = !categoryValue || category.includes(categoryValue);
                const matchesDepartment = !departmentValue || department.includes(departmentValue);
                const matchesSearch = !searchValue || name.includes(searchValue);
                
                if (matchesCategory && matchesDepartment && matchesSearch) {
                    $row.show();
                } else {
                    $row.hide();
                }
            });
        };

        // Reset filters function
        window.resetFilters = function() {
            $('#categoryFilter').val('');
            $('#departmentFilter').val('');
            $('#searchFilter').val('');
            applyFilters();
        };

        // Initialize filter event listeners
        $('#categoryFilter, #departmentFilter').on('change', applyFilters);
        $('#searchFilter').on('input', applyFilters);

        // ======= Edit Course =======
        $(document).on('click', '.edit-course', function() {
            const courseId = $(this).data('id');

            if (!courseId) {
                alert('Course ID not found!');
                return;
            }

            $.get("{{ route('institute.admin.courses.details') }}", {
                finacp_merchant_sub_category_id: courseId
            }).done(function(res) {
                if (!res.success) {
                    alert(res.message);
                    return;
                }

                // Fill course fields
                $('#edit_course_id').val(res.course.finacp_merchant_sub_category_id);
                $('#edit_sub_type').val(res.course.finacp_merchant_sub_category_type);

                // Fill branches dynamically
                const branchesContainer = $('#edit_branches_container');
                branchesContainer.empty();

                let branches = res.course.product_details || [];
                if (branches.length > 0) {
                    branches.forEach((branch, index) => {
                        const branchHtml = `
                            <div class="mb-2">
                                <input type="text" name="branches[${index}][sub_type]" 
                                       class="form-control" 
                                       value="${branch.sub_type}" required>
                                <input type="hidden" name="branches[${index}][id]" value="${branch.id}">
                            </div>
                        `;
                        branchesContainer.append(branchHtml);
                    });
                } else {
                    branchesContainer.html('<p class="text-muted">No branches available</p>');
                }

                $('#editCourseModal').modal('show');
            });
        });

        // Submit Edit Course Form
        $('#editCourseForm').on('submit', function(e) {
            e.preventDefault();
            const courseId = $('#edit_course_id').val();

            if (!courseId) {
                alert('Course ID is missing.');
                return;
            }

            const formData = $(this).serialize() + `&id=${courseId}`;

            $.ajax({
                url: "{{ route('institute.admin.courses.edit') }}",
                type: 'POST',
                data: formData,
                success: function(data) {
                    if (data.success) {
                        alert('Updated successfully!');
                        $('#editCourseModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Failed to update.');
                    }
                },
                error: function() {
                    alert('Something went wrong.');
                }
            });
        });

        // ======= Delete Course =======
        let deleteCourseId = null;

        $(document).on('click', '.delete-course', function() {
            deleteCourseId = $(this).data('id');
            const courseName = $(this).data('name');

            $('#courseNameToDelete').text(courseName);
            $('#deleteCourseModal').modal('show');
        });

        // Confirm delete button
        $('#confirmDeleteBtn').on('click', function() {
            if (!deleteCourseId) return;

            $.ajax({
                url: "{{ route('institute.admin.courses.delete') }}",
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    course_id: deleteCourseId
                },
                success: function(data) {
                    if (data.success) {
                        alert('Deleted successfully!');
                        $('#deleteCourseModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Failed to delete.');
                    }
                },
                error: function() {
                    alert('Something went wrong.');
                }
            });
        });
    });
</script>

@endsection