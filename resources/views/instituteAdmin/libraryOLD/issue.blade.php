@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<title> Book Issue Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Base Styles from Employee Management Page */
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
    
    /* Status Badges - Book Specific */
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
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }
    
    .status-returned {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    
    .status-overdue {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    
    .status-reissued {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
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
    
    .bulk-action-btn.return {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    
    .bulk-action-btn.return:hover {
        background: #bbf7d0;
    }
    
    .bulk-action-btn.renew {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    
    .bulk-action-btn.renew:hover {
        background: #fde68a;
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
    
    .filter-grid {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
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

    .btn-filter-secondary:hover {
        text-decoration: none;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .action-btn:active {
        transform: translateY(0);
    }
    
    .action-btn-view {
        background: #e0f2fe;
        color: #0369a1;
        border-color: #bae6fd;
    }
    
    .action-btn-return {
        background: #dcfce7;
        color: #166534;
        border-color: #bbf7d0;
    }
    
    .action-btn-renew {
        background: #fef3c7;
        color: #92400e;
        border-color: #fde68a;
    }
    
    .action-btn-view:hover {
        background: #bae6fd;
    }
    
    .action-btn-return:hover {
        background: #bbf7d0;
    }
    
    .action-btn-renew:hover {
        background: #fde68a;
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
    
    /* Issue Form Card */
    .issue-form-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
    }
    
    .issue-form-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: white;
    }
    
    .issue-form-header h2 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .issue-form-body {
        padding: 24px;
    }
    
    .issue-form-grid {
        /* display: grid; */
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-size: 0.9rem;
        font-weight: 500;
        color: #1e293b;
    }
    
    .form-label .required {
        color: #ef4444;
    }
    
    /* Book Info */
    .book-info {
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .available {
        color: #10b981;
        font-weight: 500;
    }
    
    .book-id {
        color: #3b82f6;
        font-family: monospace;
        background: #eff6ff;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.8rem;
    }
    
    /* Person Type */
    .person-type {
        display: inline-block;
        font-size: 0.75rem;
        padding: 2px 8px;
        border-radius: 4px;
        background: #f3f4f6;
        color: #6b7280;
        margin-left: 6px;
    }
    
    /* Select2 Styling */
    .select2-container--default .select2-selection--single {
        height: 44px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        transition: all 0.2s;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 44px;
        padding-left: 14px;
        font-size: 0.95rem;
        color: #1e293b;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 44px;
        right: 10px;
    }
    
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Date Input */
    .date-input {
        width: 100%;
        height: 44px;
        padding: 0 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.95rem;
        background: white;
        color: #1e293b;
        transition: all 0.2s;
    }
    
    .date-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Submit Button */
    .submit-btn {
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 32px;
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
    }
    
    .submit-btn:hover {
        background: #2563eb;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }
    
    /* Search Box */
    .search-box {
        position: relative;
        max-width: 300px;
    }
    
    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }
    
    .search-input {
        width: 100%;
        padding: 10px 12px 10px 36px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Book count badge */
    .book-count {
        display: inline-block;
        background: #3b82f6;
        color: white;
        font-size: 0.8rem;
        padding: 2px 8px;
        border-radius: 12px;
        margin-left: 10px;
        font-weight: 500;
    }
    
    /* Responsive adjustments */
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
        
        .bulk-actions-container {
            flex-direction: column;
            align-items: stretch;
        }
        
        .bulk-actions-container .d-flex {
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .issue-form-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        
        .search-box {
            max-width: 100%;
        }
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
</style>

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-book"></i>
            Book Issue Management
        </h1>
        <div class="search-box">
            <i class="bi bi-search search-icon"></i>
            <input type="text" 
                   id="bookSearch" 
                   class="search-input" 
                   placeholder="Search books...">
        </div>
    </div>

    <!-- Issue Form Card -->
    <div class="issue-form-card">
        <div class="issue-form-header">
            <h2><i class="bi bi-arrow-up-circle"></i> Issue New Book</h2>
        </div>
        <div class="issue-form-body">
            <form method="POST" action="{{ route('library.issue.store') }}">
                @csrf
                
                <div class="issue-form-grid">
                    <!-- Book Selection -->
                    <div class="form-group">
                        <label class="form-label">Select Book <span class="required">*</span></label>
                        <select name="library_book_id" id="library_book_id" class="select2" required>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}" 
                                        data-book-id="{{ $book->librarybook_id ?? '' }}"
                                        data-title="{{ $book->title }}"
                                        data-copies="{{ $book->available_copies }}"
                                        data-total="{{ $book->total_copies }}">
                                    {{ $book->title }} 
                                    @if($book->librarybook_id)
                                        <span class="person-type">ID: {{ $book->librarybook_id }}</span>
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <div class="book-info">
                            <span class="available" id="available-copies"></span>
                            <span class="book-id" id="book-id-display"></span>
                        </div>
                    </div>

                    <!-- Issue To Type -->
                    <div class="form-group">
                        <label class="form-label">Issue To <span class="required">*</span></label>
                        <select name="issueable_type" id="issueable_type" class="select2" required>
                            <option value="App\Models\StudentParentDetails">Student</option>
                            <option value="App\Models\EmployeeDetails">Employee</option>
                        </select>
                    </div>

                    <!-- Person Selection -->
                    <div class="form-group">
                        <label class="form-label">Select Person <span class="required">*</span></label>
                        <select name="issueable_id" id="issueable_id" class="select2" required>
                            <optgroup label="Students">
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" data-type="student">
                                        {{ $student->first_name }} {{ $student->last_name }}
                                        @if($student->registration_number)
                                            <span class="person-type">#{{ $student->registration_number }}</span>
                                        @endif
                                    </option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Employees">
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" data-type="employee">
                                        {{ $employee->name }}
                                        @if($employee->employee_code)
                                            <span class="person-type">#{{ $employee->employee_code }}</span>
                                        @endif
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <!-- Due Date -->
                    <div class="form-group">
                        <label class="form-label">Due Date <span class="required">*</span></label>
                        <input type="date" name="due_date" id="due_date" class="date-input" required>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="bi bi-check-circle"></i> Issue Book
                </button>
            </form>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-container">
        <form method="GET" class="filter-form" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" class="filter-input"
                           value="{{ request('search') }}" placeholder="Search books or persons...">
                </div>

                <div class="filter-group">
                    <i class="bi bi-person"></i>
                    <select name="status" class="filter-input">
                        <option value="">All Status</option>
                        <option value="issued" {{ request('status') == 'issued' ? 'selected' : '' }}>Issued</option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="reissued" {{ request('status') == 'reissued' ? 'selected' : '' }}>Reissued</option>
                    </select>
                </div>

                <!-- <div class="filter-group">
                    <i class="bi bi-calendar"></i>
                    <input type="date" name="due_date" class="filter-input"
                           value="{{ request('due_date') }}" placeholder="Due Date">
                </div> -->

                <div class="filter-group">
                    <i class="bi bi-people"></i>
                    <select name="issueable_type" class="filter-input">
                        <option value="">All Types</option>
                        <option value="student" {{ request('issueable_type') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="employee" {{ request('issueable_type') == 'employee' ? 'selected' : '' }}>Employee</option>
                    </select>
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
        </form>
    </div>

    <!-- Bulk Actions Container -->
    <div class="bulk-actions-container" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 books selected</div>
        <div class="d-flex flex-wrap">
            <button class="bulk-action-btn return" onclick="bulkAction('return')">
                <i class="bi bi-check-circle"></i>
                Mark as Returned
            </button>
            <button class="bulk-action-btn renew" onclick="bulkAction('renew')">
                <i class="bi bi-arrow-clockwise"></i>
                Renew Books
            </button>
            <button class="bulk-action-btn clear mr-0" onclick="clearSelection()">
                <i class="bi bi-x-lg"></i>
                Clear
            </button>
        </div>
    </div>

    <!-- Issued Books Table -->
    <div class="card">
        <div class="card-header">
            <div>
                <h4><i class="bi bi-list-check"></i> Issued Books 
                    <span class="book-count">{{ $issuedBooks->count() }}</span>
                </h4>
            </div>
        </div>
         
        <div class="card-body">
            @if($issuedBooks->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-book"></i>
                    </div>
                    <h4>No books are currently issued</h4>
                    <p>Try adjusting your filters or issue a new book</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="erp-table">
                        <thead>
                            <tr>
                                <th width="40">
                                    <input type="checkbox" id="selectAll" class="select-checkbox">
                                </th>                                
                                <th class="sortable">
                                    Book
                                    <div class="sort-icons">
                                        <i class="sort-icon bi bi-caret-up-fill"></i>
                                        <i class="sort-icon bi bi-caret-down-fill"></i>
                                    </div>
                                </th>
                                <th class="sortable">
                                    Issued To
                                    <div class="sort-icons">
                                        <i class="sort-icon bi bi-caret-up-fill"></i>
                                        <i class="sort-icon bi bi-caret-down-fill"></i>
                                    </div>                                    
                                </th>
                                <th class="sortable">
                                    Issue Date
                                    <div class="sort-icons">
                                        <i class="sort-icon bi bi-caret-up-fill"></i>
                                        <i class="sort-icon bi bi-caret-down-fill"></i>
                                    </div>                                    
                                </th>
                                <th class="sortable">
                                    Due Date
                                    <div class="sort-icons">
                                        <i class="sort-icon bi bi-caret-up-fill"></i>
                                        <i class="sort-icon bi bi-caret-down-fill"></i>
                                    </div>                                    
                                </th>
                                <th class="sortable">
                                    Status
                                    <div class="sort-icons">
                                        <i class="sort-icon bi bi-caret-up-fill"></i>
                                        <i class="sort-icon bi bi-caret-down-fill"></i>
                                    </div>                                    
                                </th>
                                <!-- <th class="text-center">Actions</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($issuedBooks as $issuedBook)
                            <tr>
                                <td>
                                    <input type="checkbox" class="book-checkbox select-checkbox" value="{{ $issuedBook->id }}">
                                </td>
                                <td>
                                    <strong>{{ $issuedBook->libraryBook->title ?? 'N/A' }}</strong>
                                    <div class="book-info">
                                        <span class="small text-primary">ID: {{ $issuedBook->libraryBook->librarybook_id ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($issuedBook->issueable_type == 'App\Models\StudentParentDetails')
                                        <div class="font-medium">{{ $issuedBook->issueable->first_name ?? 'N/A' }} 
                                        {{ $issuedBook->issueable->last_name ?? '' }}</div>
                                        <div class="small text-primary">
                                            Student • {{ $issuedBook->issueable->registration_number ?? 'N/A' }}
                                        </div>
                                    @elseif($issuedBook->issueable_type == 'App\Models\EmployeeDetails')
                                        <div class="font-medium">{{ $issuedBook->issueable->name ?? 'N/A' }}</div>
                                        <div class="small text-primary">
                                            Employee • {{ $issuedBook->issueable->employee_code ?? 'N/A' }}
                                        </div>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($issuedBook->issue_date)->format('d M Y') }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($issuedBook->due_date)->format('d M Y') }}
                                    @if($issuedBook->status == 'overdue')
                                        <div class="text-xs text-red-500 font-medium mt-1">Overdue</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $issuedBook->status }}">
                                        {{ ucfirst($issuedBook->status) }}
                                    </span>
                                </td>
                                <!-- <td class="text-center">
                                    <div class="table-actions d-flex">
                                        @if($issuedBook->status == 'issued')
                                            <button class="action-btn action-btn-return d-block" 
                                                    onclick="returnBook('{{ $issuedBook->id }}')"
                                                    title="Mark as Returned">
                                                <div>
                                                    <i class="bi bi-check-circle"></i>
                                                </div>
                                                <div>
                                                    <span class="small">Return</span>
                                                </div>
                                            </button>
                                            <button class="action-btn action-btn-renew d-block" 
                                                    onclick="renewBook('{{ $issuedBook->id }}')"
                                                    title="Renew Book">
                                                <div>
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </div>
                                                <div>
                                                    <span class="small">Renew</span>
                                                </div>

                                            </button>
                                        @endif
                                        <button class="action-btn action-btn-view d-block" 
                                                onclick="viewBookDetails('{{ $issuedBook->id }}')"
                                                title="View Details">
                                            <div>
                                                <i class="bi bi-eye"></i>
                                            </div>
                                            <div>
                                                <span class="small">View</span>
                                            </div>
                                        </button>
                                    </div>
                                </td> -->
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Pagination (disabled since collection is used) -->
    <div class="d-flex justify-content-end mt-4 text-muted small">
        Total records: {{ $issuedBooks->count() }}
    </div>
</div>

<!-- jQuery and Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Bulk Selection Management (from Employee Management)
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

    // Initialize Select2
    $('#library_book_id, #issueable_id, #issueable_type').select2({
        placeholder: 'Select an option',
        allowClear: false,
        width: '100%'
    });

    $('#library_book_id').select2({
        placeholder: 'Search by Book ID or Title',
        width: '100%',
        matcher: function (params, data) {
            if ($.trim(params.term) === '') {
                return data;
            }

            if (!data.text) {
                return null;
            }

            const term = params.term.toLowerCase();
            const text = data.text.toLowerCase();

            if (text.includes(term)) {
                return data;
            }

            return null;
        }
    });

    // Store book data
    const booksData = @json($books->map(function($book) {
        return [
            'id' => $book->id,
            'available_copies' => $book->available_copies,
            'total_copies' => $book->total_copies
        ];
    }));

    // Function to update book info display
    function updateBookInfo(bookId) {
        const book = booksData.find(b => b.id == bookId);
        if (book) {
            $('#available-copies').text(`${book.available_copies} of ${book.total_copies} copies available`);
            $('#book-id-display').text(`ID: ${book.librarybook_id || 'N/A'}`);
        }
    }

    // Update available copies when book is selected
    $('#library_book_id').on('change', function() {
        updateBookInfo($(this).val());
    });

    // Trigger on page load
    $('#library_book_id').trigger('change');

    // Store original options
    const allOptions = $('#issueable_id option').clone();

    // Filter persons based on type
    function filterPersons() {
        const type = $('#issueable_type').val();
        $('#issueable_id').empty();

        allOptions.each(function() {
            const personType = $(this).data('type');
            if ((type.includes('Student') && personType == 'student') ||
                (type.includes('Employee') && personType == 'employee')) {
                $('#issueable_id').append($(this).clone());
            }
        });

        // Reinitialize Select2
        $('#issueable_id').select2({
            placeholder: 'Select an option',
            allowClear: false,
            width: '100%'
        });
    }

    // Trigger on change
    $('#issueable_type').on('change', filterPersons);

    // Filter on page load
    filterPersons();

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    $('#due_date').attr('min', today);
    
    // Set default due date to 7 days from now
    const nextWeek = new Date();
    nextWeek.setDate(nextWeek.getDate() + 7);
    const nextWeekStr = nextWeek.toISOString().split('T')[0];
    $('#due_date').val(nextWeekStr);

    // Bulk Action Functions
    function clearSelection() {
        document.querySelectorAll('.book-checkbox:checked').forEach(checkbox => {
            checkbox.checked = false;
        });
        if (document.getElementById('selectAll')) {
            document.getElementById('selectAll').checked = false;
        }
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
            case 'return':
                if (confirm(`Mark ${selectedBooks.length} book(s) as returned?`)) {
                    // Show loading state
                    const btn = event.target.closest('button');
                    const originalHTML = btn.innerHTML;
                    btn.innerHTML = '<span class="loading-spinner"></span> Processing...';
                    btn.disabled = true;
                    
                    // Simulate API call
                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                        alert(`${selectedBooks.length} books marked as returned`);
                        clearSelection();
                        location.reload();
                    }, 1000);
                }
                break;
                
            case 'renew':
                if (confirm(`Renew ${selectedBooks.length} book(s) for 7 more days?`)) {
                    // Show loading state
                    const btn = event.target.closest('button');
                    const originalHTML = btn.innerHTML;
                    btn.innerHTML = '<span class="loading-spinner"></span> Processing...';
                    btn.disabled = true;
                    
                    // Simulate API call
                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                        alert(`${selectedBooks.length} books renewed successfully`);
                        clearSelection();
                        location.reload();
                    }, 1000);
                }
                break;
                
            default:
                alert(`${action} action triggered for ${selectedBooks.length} books`);
        }
    }

    // Individual book actions
    function returnBook(bookId) {
        if (confirm('Mark this book as returned?')) {
            // Show loading
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<span class="loading-spinner"></span>';
            btn.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                alert('Book marked as returned');
                location.reload();
            }, 800);
        }
    }

    function renewBook(bookId) {
        if (confirm('Renew this book for 7 more days?')) {
            // Show loading
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<span class="loading-spinner"></span>';
            btn.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                alert('Book renewed successfully');
                location.reload();
            }, 800);
        }
    }

    function viewBookDetails(bookId) {
        // Implement view details functionality
        alert('View details for book ID: ' + bookId);
    }

    // Search functionality
    $('#bookSearch').on('keyup', function () {
        const value = $(this).val().toLowerCase();
        $('.erp-table tbody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Filter form submission with loading state
    document.getElementById('filterForm')?.addEventListener('submit', function(e) {
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
});
</script>

@endsection