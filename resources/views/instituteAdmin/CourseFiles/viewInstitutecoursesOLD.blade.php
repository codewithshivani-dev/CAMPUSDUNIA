@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Course Management</title>
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
        margin-bottom: 2px;
        display: none;
        align-items: center;
        flex-wrap: wrap;
        animation: slideDown 0.3s ease;
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
    }
    
    /* Filter container */
    .filter-container {
        background: #fff;
        border-radius: 8px;
        padding: 6px 14px;
        margin-bottom: 20px;
        border: 1px solid #e2e8f0;
    }

    .filter-form {
        display: flex;
        justify-content: space-between;
        gap: 16px;
    }

    .filter-group {
        position: relative;
        flex: 1;
        min-width: 200px;
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
    
    .filter-grid {
        display: flex;
        gap: 16px;
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
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
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
    
    .action-btn-edit:hover {
        background: #fde68a;
    }
    
    .action-btn-delete:hover {
        background: #fecaca;
    }
    .custom-gap{
        gap:5px;
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
        color:white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
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
    /* .branch-badge {
        display: inline-block;
        padding: 4px 8px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        font-size: 11px;
        color: #475569;
        margin: 2px;
    } */


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
        
        .action-btn {
            padding: 4px 8px;
            font-size: 11px;
        }
    }
</style>

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-book"></i>
            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                Classes Management
            @else
                Courses Management
            @endif
        </h1>
        <a href="{{ route('AddInstitutecourses') }}" class="add-btn">
            <i class="bi bi-plus-circle"></i>
            Add New
        </a>
    </div>

    <!-- Filters Section -->
    <div class="filter-container">
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
                           placeholder="Search...">
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
    <div class="bulk-actions-container" id="bulkActionsContainer">
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

    <!-- Courses Table -->
    <div class="table-responsive">
        <table class="erp-table" id="coursesTable">
            <thead>
                <tr>
                    <th width="40">
                        <input type="checkbox" id="selectAll" class="select-checkbox">
                    </th>
                    <th class="sortable" onclick="sortTable('name')">
                        @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                            Class
                        @else
                            Course
                        @endif Name
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
                    <!-- <th class="sortable" onclick="sortTable('id')">
                        ID
                        <div class="sort-icons">
                            <i class="sort-icon bi bi-caret-up-fill"></i>
                            <i class="sort-icon bi bi-caret-down-fill"></i>
                        </div>
                    </th> -->
                    <th class="sortable" onclick="sortTable('branches')">
                        Stream
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
                    <td>
                        <input type="checkbox" class="course-checkbox select-checkbox" value="{{ $course->finacp_merchant_sub_category_id }}">
                    </td>
                    <td class="">{{ $course->finacp_merchant_sub_category_type }}</td>
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
                        {{ $course->departmentCategory->category_name ?? 'N/A' }}
                    </td>
                    
                    <td class="">
                        {{ optional($course->getRelation('department'))->department }}
                    </td>
                  
                    <td>
                        @if($course->productDetails->count() > 0)
                            <div class="branches-container {{ $course->productDetails->count() > 1 ? 'multi-branches' : '' }}">
                                @foreach($course->productDetails as $branch)
                                    <span class="branch-badge">
                                        {{ \Illuminate\Support\Str::limit($branch->sub_type, 10) }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        {{ $course->created_at->format('M d, Y') }}
                    </td>
                    <td class="text-center">
                        <div class="custom-gap d-flex justify-content-center">
                            <button class="action-btn action-btn-edit edit-course d-block" 
                                    data-id="{{ $course->finacp_merchant_sub_category_id }}"
                                    title="Edit">
                                   <div>
                                        <i class="bi bi-pencil"></i>
                                   </div>
                                   <div>
                                        <span class="small">Edit</span>
                                   </div>
                            </button>
                            <button class="action-btn action-btn-delete delete-course d-block" 
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
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
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
                            <i class="bi bi-pencil me-1"></i>
                            Edit
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">
                                Name *
                            </label>
                            <input type="text" name="sub_type" id="edit_sub_type" class="form-control" required>
                        </div>
                        
                        <!-- Branches Container -->
                        <div class="mb-3">
                            <label class="form-label" id="branchesLabel">Branches</label>
                            <div id="edit_branches_container"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <span id="saveBtnText">Save</span>
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
                    <h5 class="modal-title text-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete "<span id="courseNameToDelete" class="font-weight-bold"></span>"?</p>

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
        function applyFilters() {
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
        }

        // Reset filters function
        function resetFilters() {
            $('#categoryFilter').val('');
            $('#departmentFilter').val('');
            $('#searchFilter').val('');
            applyFilters();
        }

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