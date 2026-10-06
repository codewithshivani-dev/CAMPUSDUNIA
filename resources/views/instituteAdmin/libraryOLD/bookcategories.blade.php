@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Book Categories Management</title>
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
        flex: 1;
    }

       .filter-group {
        position: relative;
    }
    
    .filter-group .bi {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        z-index: 1;
    }
    
    .filter-group input {
        width: 100%;
        padding: 8px 10px 8px 35px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 14px;
        background: #fff;
        transition: all 0.2s;
    }
    
    .filter-group input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        transform: translateY(-1px);
    }
    
    .filter-group input:hover {
        border-color: #cbd5e1;
    } 
    
    /* .filter-input {
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 14px;
        background: #fff;
        transition: all 0.2s;
        flex: 1;
    }
    
    .filter-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        transform: translateY(-1px);
    }
    
    .filter-input:hover {
        border-color: #cbd5e1;
    }
     */
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
        color: white;
        text-decoration: none;
    }
    
    /* Action Buttons */
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
    }
    
    .action-btn:active {
        transform: translateY(0);
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
    
    .action-btn-edit:hover {
        background: #fef2c8;
        color: #92400e;
    }
    
    .action-btn-delete:hover {
        background: #fecaca;
        color: #dc2626;
    }
    
    /* Category Badge */
    .category-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 4px 8px;
        border-radius: 4px;
    }
    
    .category-icon {
        width: 30px;
        height: 30px;
        background: #3b82f6;
        color: white;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
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
    
    /* Tooltips */
    .tooltip {
        position: relative;
        display: inline-block;
    }
    
    .tooltip .tooltip-text {
        visibility: hidden;
        width: 120px;
        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        margin-left: -60px;
        opacity: 0;
        transition: opacity 0.3s;
        font-size: 12px;
    }
    
    .tooltip .tooltip-text::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #333 transparent transparent transparent;
    }
    
    .tooltip:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
    }
    
    /* Stats Cards */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .stat-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        transition: transform 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }
    
    .stat-number {
        font-size: 24px;
        font-weight: 600;
        color: #1e293b;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 14px;
        color: #64748b;
        margin-top: 4px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
            gap: 10px;
        }
        
        .filter-grid {
            width: 100%;
        }
        
        .filter-actions {
            width: 100%;
            justify-content: space-between;
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
        
        .stats-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container">
    <div class="page-header">
        <h1 class="page-title">Book Categories Management</h1>
        <a href="{{ route('library.category.create') }}" class="add-btn">
            <i class="bi bi-plus-circle"></i>
            Add Category
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

    {{-- Stats Cards --}}
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <i class="bi bi-journal-bookmark"></i>
            </div>
            <div class="stat-number">{{ $categories->count() }}</div>
            <div class="stat-label">Total Categories</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(34, 197, 94, 0.1); color: #16a34a;">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-number">{{ $categories->count() }}</div>
            <div class="stat-label">Active Categories</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(251, 191, 36, 0.1); color: #d97706;">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="stat-number">0</div>
            <div class="stat-label">Recently Added</div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="filter-container">
        <form method="GET" class="filter-form" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <i class="bi bi-journals"></i>
                    <input list="categoryNamesList" name="search" class="filter-input"
                        value="{{ request('search') }}" placeholder="Search Categories">
                    <datalist id="categoryNamesList">
                        @foreach($categories as $category)
                        <option value="{{ $category->name }}">
                        @endforeach
                    </datalist>
                </div>
            </div>

            <div class="filter-actions">
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
            <input type="hidden" name="sort_by" id="sortBy" value="{{ request('sort_by', 'name') }}">
            <input type="hidden" name="sort_order" id="sortOrder" value="{{ request('sort_order', 'asc') }}">
        </form>
    </div>

    {{-- Bulk Actions Container --}}
    <div class="bulk-actions-container" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 categories selected</div>
        <div class="d-flex flex-wrap">
            <button class="bulk-action-btn download" onclick="bulkAction('download')">
                <i class="bi bi-download"></i>
                Download
            </button>
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

    {{-- Categories Table --}}
    <div class="table-responsive">
        <table class="erp-table">
            <thead>
                <tr>
                    <th width="40">
                        <input type="checkbox" id="selectAll" class="select-checkbox">
                    </th>
                    <th class="sortable" onclick="sortTable('book_categories_id')">
                        ID
                        <div class="sort-icons">
                            <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'book_categories_id' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'book_categories_id' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('name')">
                        Category Name
                        <div class="sort-icons">
                            <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'name' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'name' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('description')">
                        Description
                        <div class="sort-icons">
                            <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'description' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'description' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="categoriesContainer">
                @forelse($categories as $category)
                <tr>
                    <td>
                        <input type="checkbox" class="category-checkbox select-checkbox" value="{{ $category->book_categories_id }}">
                    </td>
                    <td>
                        <span class="category-badge">
                            <span class="category-icon">
                                {{ substr($category->name, 0, 1) }}
                            </span>
                            {{ $category->book_categories_id }}
                        </span>
                    </td>
                    <td>
                        <strong>{{ $category->name }}</strong>
                    </td>
                    <td>
                        @if($category->description)
                            <span class="text-muted">{{ Str::limit($category->description, 60) }}</span>
                        @else
                            <span class="text-muted fst-italic">No description</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="table-actions">
                            <a href="{{ route('library.category.create', $category->book_categories_id) }}" 
                               class="action-btn action-btn-edit d-block" title="Edit Category">
                                 <div>
                                    <i class="fa-regular fa-pen-to-square"></i>
                                 </div>
                                 <div>
                                    <span class="small">Edit</span>
                                 </div>
                            </a>
                            
                            <form action="{{ route('library.category.create', $category->book_categories_id) }}" 
                                  method="POST" class="d-inline delete-form"
                                  onsubmit="return confirmDelete(this)">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="action-btn action-btn-delete d-block" title="Delete Category">
                                    <div>
                                        <i class="fa-regular fa-trash-can"></i>
                                    </div>
                                    <div>
                                        <span class="small">Delete</span>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-inboxes"></i>
                            </div>
                            <h4>No Categories Found</h4>
                            <p>Try adjusting your filters or add a new category</p>
                            <a href="{{ route('library.category.create') }}" class="btn btn-primary" style="margin-top: 16px;">
                                <i class="bi bi-plus-circle me-2"></i>Add First Category
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Results Count --}}
    @if($categories->count() > 0)
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-sm text-gray-600">
            Showing {{ $categories->count() }} results
        </div>
    </div>
    @endif
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Bulk Selection Management
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
        const bulkActionsContainer = document.getElementById('bulkActionsContainer');
        const selectedCountElement = document.getElementById('selectedCount');

        // Select All functionality
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                categoryCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectionUI();
            });
        }

        // Individual checkbox change
        categoryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectionUI);
        });

        function updateSelectionUI() {
            const selectedCount = document.querySelectorAll('.category-checkbox:checked').length;
            
            if (selectedCount > 0) {
                bulkActionsContainer.classList.add('active');
                selectedCountElement.textContent = selectedCount + ' category(s) selected';
                
                // Update select all checkbox state
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = selectedCount === categoryCheckboxes.length;
                    selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < categoryCheckboxes.length;
                }
            } else {
                bulkActionsContainer.classList.remove('active');
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                }
            }
        }
    });

    // Bulk Action Functions
    function clearSelection() {
        document.querySelectorAll('.category-checkbox:checked').forEach(checkbox => {
            checkbox.checked = false;
        });
        document.getElementById('selectAll').checked = false;
        document.getElementById('bulkActionsContainer').classList.remove('active');
    }

    function bulkAction(action) {
        const selectedCategories = Array.from(document.querySelectorAll('.category-checkbox:checked'))
            .map(checkbox => checkbox.value);
        
        if (selectedCategories.length === 0) {
            alert('Please select at least one category.');
            return;
        }
        
        switch(action) {
            case 'download':
                if (confirm(`Download data for ${selectedCategories.length} category(s)?`)) {
                    // Show loading state
                    const btn = event.target.closest('button');
                    const originalHTML = btn.innerHTML;
                    btn.innerHTML = '<span class="loading-spinner"></span> Processing...';
                    btn.disabled = true;
                    
                    // Simulate download
                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                        alert('Download started for ' + selectedCategories.length + ' categories');
                    }, 1000);
                }
                break;
                
            case 'bulk_delete':
                if (confirm(`Are you sure you want to delete ${selectedCategories.length} category(s)? This action cannot be undone.`)) {
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
                        alert(`${selectedCategories.length} categories deleted successfully`);
                    }, 1500);
                }
                break;
                
            default:
                alert(`${action} action triggered for ${selectedCategories.length} categories`);
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

    // Delete confirmation
    function confirmDelete(form) {
        if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
            // Show loading state
            const btn = form.querySelector('button[type="submit"]');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<span class="loading-spinner"></span> Deleting...';
            btn.disabled = true;
            return true;
        }
        return false;
    }

    // Search Functionality
    document.getElementById('searchDesignations')?.addEventListener('input', async function(e) {
        const searchTerm = e.target.value.trim();
        
        if (searchTerm.length === 0) {
            return;
        }

        if (searchTerm.length < 2) {
            return;
        }

        try {
            const response = await fetch(`/categories/search?search_term=${encodeURIComponent(searchTerm)}`);
            const result = await response.json();

            if (result.success) {
                if (result.categories.length > 0) {
                    let html = '';
                    result.categories.forEach(category => {
                        html += `
                            <tr>
                                <td>
                                    <input type="checkbox" class="category-checkbox select-checkbox" value="${category.book_categories_id}">
                                </td>
                                <td>
                                    <span class="category-badge">
                                        <span class="category-icon">
                                            ${category.name.charAt(0)}
                                        </span>
                                        ${category.book_categories_id}
                                    </span>
                                </td>
                                <td>
                                    <strong>${category.name}</strong>
                                </td>
                                <td>
                                    ${category.description ? `<span class="text-muted">${category.description.substring(0, 60)}${category.description.length > 60 ? '...' : ''}</span>` : '<span class="text-muted fst-italic">No description</span>'}
                                </td>
                                <td class="text-center">
                                    <div class="table-actions">
                                        <a href="/library/category/create/${category.book_categories_id}" 
                                           class="action-btn action-btn-edit" title="Edit Category">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                        <form action="/library/category/create/${category.book_categories_id}" 
                                              method="POST" class="d-inline delete-form"
                                              onsubmit="return confirmDelete(this)">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" class="action-btn action-btn-delete" title="Delete Category">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    document.getElementById('categoriesContainer').innerHTML = html;
                } else {
                    document.getElementById('categoriesContainer').innerHTML = `
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-inboxes"></i>
                                    </div>
                                    <h4>No Categories Found</h4>
                                    <p>Try adjusting your search term</p>
                                </div>
                            </td>
                        </tr>
                    `;
                }
            }
        } catch (error) {
            console.error('Error searching categories:', error);
        }
    });

    // Filter form submission with loading state
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('.btn-filter-primary');
        if (submitBtn) {
            const originalHTML = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="loading-spinner"></span> Applying...';
            submitBtn.disabled = true;
            
            // Re-enable button after 2 seconds in case of error
            setTimeout(() => {
                submitBtn.innerHTML = originalHTML;
                submitBtn.disabled = false;
            }, 2000);
        }
    });
</script>
@endsection