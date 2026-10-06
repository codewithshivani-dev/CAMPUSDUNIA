@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Library Books Management</title>
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
    
    .status-issued {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    
    .status-available {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
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
        gap: 10px;
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
        flex-wrap: wrap;
    }
    
    .filter-group {
        flex: 1;
        min-width: 180px;
    }
    
    .filter-label {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 4px;
        font-weight: 500;
    }
    
    .filter-input {
        position: relative;
    }
    
    .filter-input .bi {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        z-index: 1;
    }
    
    .filter-input input {
        width: 100%;
        padding: 8px 10px 8px 35px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 14px;
        background: #fff;
        transition: all 0.2s;
    }
    
    .filter-input input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        transform: translateY(-1px);
    }
    
    .filter-input input:hover {
        border-color: #cbd5e1;
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
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    
    .action-btn {
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        text-decoration: none;
    }
    
    .btn-issue {
        background: #3b82f6;
        color: white;
    }
    
    .btn-issue:hover {
        background: #2563eb;
        color: white;
    }
    
    .btn-return {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    
    .btn-return:hover {
        background: #e2e8f0;
        color: #475569;
    }
    
    /* Table Actions */
    .table-actions {
        display: flex;
        gap: 5px;
        justify-content: center;
    }
    
    .table-action-btn {
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
    
    .table-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .table-action-btn:active {
        transform: translateY(0);
    }
    
    .btn-view {
        background: #e0f2fe;
        color: #0369a1;
        border-color: #bae6fd;
    }
    
    .btn-edit {
        background: #fef2c8;
        color: #92400e;
        border-color: #fef2c8;
    }
    
    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
        border-color: #fecaca;
        font-size: 12px;
    }
    
    .btn-view:hover {
        background: #bae6fd;
        color: #0369a1;
    }
    
    .btn-edit:hover {
        background: #fef2c8;
        color: #92400e;
        text-decoration: none;
    }
    
    .btn-delete:hover {
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
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
            gap: 10px;
        }
        
        .filter-grid {
            width: 100%;
        }
        
        .filter-group {
            min-width: 100%;
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
        
        .action-buttons {
            flex-direction: column;
            width: 100%;
        }
        
        .action-btn {
            width: 100%;
            justify-content: center;
        }
        
        .table-actions {
            flex-direction: column;
            gap: 4px;
        }
        
        .table-action-btn {
            width: 100%;
        }
        
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }
</style>

<div class="container">
    <div class="page-header">
        <h1 class="page-title">Library Books Management</h1>
        <div class="action-buttons">
            <a href="{{ route('library.issue.create') }}" class="action-btn btn-issue">
                <i class="bi bi-arrow-up-circle"></i>
                Issue Book
            </a>
            <a href="{{ route('library.return.create') }}" class="action-btn btn-return">
                <i class="bi bi-arrow-down-circle"></i>
                Return Book
            </a>
        </div>
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
                <!-- Book ID -->
                <div class="filter-group">
                    <div class="filter-label">Book ID</div>
                    <div class="filter-input">
                        <i class="bi bi-upc-scan"></i>
                        <input list="bookIdList" name="librarybook_id" 
                               placeholder="Enter Book ID" value="{{ request('librarybook_id') }}">
                        <datalist id="bookIdList">
                            @foreach($bookIds as $b)
                                <option value="{{ $b->librarybook_id }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>

                <!-- Title -->
                <div class="filter-group">
                    <div class="filter-label">Title</div>
                    <div class="filter-input">
                        <i class="bi bi-book"></i>
                        <input list="titleList" name="title" 
                               placeholder="Search by title" value="{{ request('title') }}">
                        <datalist id="titleList">
                            @foreach($titles as $t)
                                <option value="{{ $t->title }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>

                <!-- Subject -->
                <div class="filter-group">
                    <div class="filter-label">Subject</div>
                    <div class="filter-input">
                        <i class="bi bi-journal-text"></i>
                        <input list="subjectList" name="subject" 
                               placeholder="Search by subject" value="{{ request('subject') }}">
                        <datalist id="subjectList">
                            @foreach($subjects as $s)
                                <option value="{{ $s->subject }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter btn-filter-primary">
                    <i class="bi bi-funnel"></i>
                    Apply Filters
                </button>
                <a href="{{ route('library.data') }}" class="btn-filter btn-filter-secondary">
                    <i class="bi bi-x-circle"></i>
                    Reset Filters
                </a>
            </div>
        </form>
    </div>


    {{-- Bulk Actions Container --}}
    <div class="bulk-actions-container" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 books selected</div>
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

    {{-- Books Table --}}
    <div class="table-responsive">
        <table class="erp-table">
            <thead>
                <tr>
                    <th width="40">
                        <input type="checkbox" id="selectAll" class="select-checkbox">
                    </th>
                    <th class="sortable" onclick="sortTable('librarybook_id')">
                        Book ID
                        <div class="sort-icons">
                            <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'librarybook_id' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'librarybook_id' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('title')">
                        Title
                        <div class="sort-icons">
                            <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'title' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'title' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('subject')">
                        Subject
                        <div class="sort-icons">
                            <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'subject' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'subject' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('total_copies')">
                        Total Copies
                        <div class="sort-icons">
                            <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'total_copies' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'total_copies' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('available_copies')">
                        Available
                        <div class="sort-icons">
                            <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'available_copies' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'available_copies' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="sortable" onclick="sortTable('status')">
                        Status
                        <div class="sort-icons">
                            <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'status' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                            <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'status' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                        </div>
                    </th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                <tr>
                    <td>
                        <input type="checkbox" class="book-checkbox select-checkbox" value="{{ $book->librarybook_id }}">
                    </td>
                    <td><strong>{{ $book->librarybook_id }}</strong></td>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->subject }}</td>
                    <td>{{ $book->total_copies }}</td>
                    <td>{{ $book->available_copies }}</td>
                    <td>
                        <span class="status-badge {{ $book->status == 'issued' ? 'status-issued' : 'status-available' }}">
                            {{ ucfirst($book->status) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="table-actions">
                            <button class="table-action-btn btn-view d-block" onclick="viewBook('{{ $book->librarybook_id }}')" title="View Details">
                                <i class="fa-regular fa-eye"></i>
                                <div>
                                    <span class="small">View</span>
                                </div>
                            </button>
                            <a href="" class="table-action-btn btn-edit d-block" title="Edit Book">
                                <i class="fa-regular fa-pen-to-square"></i>
                                <div>
                                    <span class="small">Edit</span>
                                </div>
                            </a>
                            <form action="" method="POST" class="d-inline delete-form"
                                  onsubmit="return confirmDelete(this)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="table-action-btn btn-delete d-block" title="Delete Book">
                                    <i class="fa-regular fa-trash-can"></i>
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
                    <td colspan="9">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-book"></i>
                            </div>
                            <h4>No Books Found</h4>
                            <p>Try adjusting your filters or add books to your library</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if(method_exists($books, 'hasPages') && $books->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-sm text-gray-600">
            @if($books->total() > 0)
            Showing {{ $books->firstItem() ?? 0 }} to {{ $books->lastItem() ?? 0 }} of {{ $books->total() }} results
            @else
            Showing 0 results
            @endif
        </div>
        <div>
            {{ $books->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
        </div>
    </div>
    @elseif($books->count() > 0)
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-sm text-gray-600">
            Showing {{ $books->count() }} results
        </div>
    </div>
    @endif
</div>


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Bulk Selection Management
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const bookCheckboxes = document.querySelectorAll('.book-checkbox');
        const bulkActionsContainer = document.getElementById('bulkActionsContainer');
        const selectedCountElement = document.getElementById('selectedCount');

        // Select All functionality
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                bookCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectionUI();
            });
        }

        // Individual checkbox change
        bookCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectionUI);
        });

        function updateSelectionUI() {
            const selectedCount = document.querySelectorAll('.book-checkbox:checked').length;
            
            if (selectedCount > 0) {
                bulkActionsContainer.classList.add('active');
                selectedCountElement.textContent = selectedCount + ' book(s) selected';
                
                // Update select all checkbox state
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = selectedCount === bookCheckboxes.length;
                    selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < bookCheckboxes.length;
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
        document.querySelectorAll('.book-checkbox:checked').forEach(checkbox => {
            checkbox.checked = false;
        });
        document.getElementById('selectAll').checked = false;
        document.getElementById('bulkActionsContainer').classList.remove('active');
    }

    function bulkAction(action) {
        const selectedBooks = Array.from(document.querySelectorAll('.book-checkbox:checked'))
            .map(checkbox => checkbox.value);
        
        if (selectedBooks.length === 0) {
            alert('Please select at least one book.');
            return;
        }
        
        switch(action) {
            case 'download':
                if (confirm(`Download data for ${selectedBooks.length} book(s)?`)) {
                    // Show loading state
                    const btn = event.target.closest('button');
                    const originalHTML = btn.innerHTML;
                    btn.innerHTML = '<span class="loading-spinner"></span> Processing...';
                    btn.disabled = true;
                    
                    // Simulate download
                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                        alert('Download started for ' + selectedBooks.length + ' books');
                    }, 1000);
                }
                break;
                
            case 'bulk_delete':
                if (confirm(`Are you sure you want to delete ${selectedBooks.length} book(s)? This action cannot be undone.`)) {
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
                        alert(`${selectedBooks.length} books deleted successfully`);
                    }, 1500);
                }
                break;
                
            default:
                alert(`${action} action triggered for ${selectedBooks.length} books`);
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
        if (confirm('Are you sure you want to delete this book? This action cannot be undone.')) {
            // Show loading state
            const btn = form.querySelector('button[type="submit"]');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<span class="loading-spinner"></span> Deleting...';
            btn.disabled = true;
            return true;
        }
        return false;
    }

    // View Book Function
    function viewBook(bookId) {
        alert('View book details for: ' + bookId);
        // Implement your view functionality here
    }

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

    // Add hidden sort inputs to the form
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            // Check if sort inputs already exist
            if (!document.getElementById('sortBy')) {
                const sortByInput = document.createElement('input');
                sortByInput.type = 'hidden';
                sortByInput.name = 'sort_by';
                sortByInput.id = 'sortBy';
                sortByInput.value = '{{ request("sort_by", "librarybook_id") }}';
                filterForm.appendChild(sortByInput);
                
                const sortOrderInput = document.createElement('input');
                sortOrderInput.type = 'hidden';
                sortOrderInput.name = 'sort_order';
                sortOrderInput.id = 'sortOrder';
                sortOrderInput.value = '{{ request("sort_order", "asc") }}';
                filterForm.appendChild(sortOrderInput);
            }
        }
    });
</script>
@endsection