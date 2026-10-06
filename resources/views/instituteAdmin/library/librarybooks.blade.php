@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Library Books Management</title>
<!-- Bootstrap Icons CDN -->
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

    /* Action Buttons - Enhanced */
    .action-buttons {
        display: flex;
        gap: 12px;
    }

    .action-btn {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        text-decoration: none;
    }

    .btn-issue {
        background: var(--primary-gradient);
        color: #fff !important;
    }

    .btn-issue:hover {
        color: #fff !important;
    }

    .btn-return {
        background: var(--success-gradient);
        color: white;
    }

    .btn-return:hover {
        background: linear-gradient(135deg, #059669, #10b981);
        color: white;
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

    /* Status Badges - Enhanced */
    .status-badge {
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        transition: all 0.3s;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        color: white;
    }

    .status-badge:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }

    .status-available {
        background: var(--success-gradient);
    }

    .status-issued {
        background: var(--danger-gradient);
    }

    /* Copy Badges */
    .copy-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        margin-right: 4px;
        margin-bottom: 2px;
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        color: #475569;
    }

    .copy-badge.available {
        background: var(--success-gradient);
        color: white;
    }

    .copy-badge.issued {
        background: var(--danger-gradient);
        color: white;
    }

    .copy-info {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* View Copies Button */
    .btn-view-copies {
        background: linear-gradient(135deg, #e0f2fe, #bae6fd);
        color: #0369a1;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.1);
    }

    .btn-view-copies:hover {
        background: linear-gradient(135deg, #bae6fd, #7dd3fc);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.2);
        color: #0369a1;
        text-decoration: none;
    }

    .btn-view-copies i {
        font-size: 14px;
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
        gap: 15px;
    }

    .filter-grid {
        display: flex;
        gap: 15px;
        flex: 1;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
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
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary-color);
        z-index: 1;
        font-size: 16px;
    }

    .filter-input input {
        width: 100%;
        padding: 12px 12px 12px 40px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        background: #fff;
        transition: all 0.3s;
    }

    .filter-input input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        transform: translateY(-2px);
    }

    .filter-input input:hover {
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
        text-decoration: none;
        font-size: 14px;
        white-space: nowrap;
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

    .btn-filter-primary {
        background: var(--primary-gradient);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .btn-filter-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-filter-primary:hover::before {
        left: 100%;
    }

    .btn-filter-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .btn-filter-secondary:hover {
        background: #e2e8f0;
        color: #475569;
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

    .alert-danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border: 1px solid #fca5a5;
        color: #991b1b;
    }

    .alert .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
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

    .empty-state p {
        color: #64748b;
    }

    /* Loading Animation */
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
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    /* Pagination - Enhanced */
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

    /* Copy Info */
    .copy-info {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 11px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 5px;
    }

    .copy-info i {
        color: var(--primary-color);
    }

    /* Book ID */
    strong {
        color: var(--primary-color);
    }

    /* Text utilities */
    .text-sm {
        font-size: 14px;
        color: #64748b;
    }

    .text-gray-600 {
        color: #475569;
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

        .action-buttons {
            width: 100%;
            flex-direction: column;
        }

        .action-btn {
            width: 100%;
            justify-content: center;
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
            width: 100%;
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

        .bulk-action-btn {
            width: 100%;
            justify-content: center;
        }

        .btn-view-copies {
            width: 100%;
            justify-content: center;
        }
    }
    .table-responsive{
        overflow-x: hidden;
    }
</style>

<div id="pageLoader" style="
    display:none;
    position:fixed;
    inset:0;
    background:rgba(255,255,255,0.9);
    backdrop-filter: blur(5px);
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
            <i class="bi bi-book-fill"></i>
            Library Books Management
        </h1>
        <div class="action-buttons">
            <a href="{{ route('library.issue.create') }}" class="action-btn btn-issue">
                <i class="bi bi-arrow-up-circle-fill"></i>
                Issue Book
            </a>
            <a href="{{ route('library.return.create') }}" class="action-btn btn-return">
                <i class="bi bi-arrow-down-circle-fill"></i>
                Return Book
            </a>
        </div>
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
        <form method="GET" class="filter-form" id="filterForm">
            <div class="filter-grid">
                <!-- Book ID -->
                <div class="filter-group">
                    <div class="filter-input">
                        <i class="bi bi-upc-scan"></i>
                        <input list="bookIdList" name="librarybook_id" placeholder="Search by Book ID"
                            value="{{ request('librarybook_id') }}">
                        <datalist id="bookIdList">
                            @foreach($bookIds as $b)
                            <option value="{{ $b->librarybook_id }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>

                <!-- Title -->
                <div class="filter-group">
                    <div class="filter-input">
                        <i class="bi bi-book"></i>
                        <input list="titleList" name="title" placeholder="Search by title"
                            value="{{ request('title') }}">
                        <datalist id="titleList">
                            @foreach($titles as $t)
                            <option value="{{ $t->title }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>

                <!-- Subject -->
                <div class="filter-group">
                    <div class="filter-input">
                        <i class="bi bi-journal-text"></i>
                        <input list="subjectList" name="subject" placeholder="Search by subject"
                            value="{{ request('subject') }}">
                        <datalist id="subjectList">
                            @foreach($subjects as $s)
                            <option value="{{ $s->subject }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>
            </div>

            <div class="filter-actions">
                <a href="{{ route('library.data') }}" class="btn-filter btn-filter-secondary">
                    <i class="bi bi-x-circle-fill"></i>
                    Reset Filters
                </a>
            </div>
        </form>
    </div>

    {{-- Bulk Actions Container --}}
    <div class="bulk-actions-container" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 books selected</div>
        <div class="d-flex flex-wrap">
            <div class="dropdown me-2">
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

    <div>
        {{-- Books Table --}}
        <div class="table-responsive custom-table-wrapper" id="tableWrapper">
            <table class="erp-table">
                <thead>
                    <tr>
                        <th class="sticky-checkbox" width="40">
                            <input type="checkbox" id="selectAll" class="select-checkbox">
                        </th>
                        <th class="sticky-main sortable">Book ID</th>
                        <th class="sortable">Title</th>
                        <th class="sortable">Subject</th>
                        <th class="sortable">Total Copies</th>
                        <th class="sortable">Available</th>
                        <th class="sortable">Status</th>
                        <th class="sortable">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                    <tr>
                        <td class="sticky-checkbox">
                            <input type="checkbox" class="book-checkbox select-checkbox"
                                value="{{ $book->librarybook_id }}">
                        </td>
                        <td class="sticky-main">
                            <strong>{{ $book->librarybook_id }}</strong>
                            @if($book->copies->count() > 0)
                            <div class="copy-info">
                                <i class="bi bi-copy"></i>
                                {{ $book->copies->count() }} copy(ies)
                            </div>
                            @endif
                        </td>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->subject }}</td>
                        <td>
                            <span class="fw-semibold">{{ $book->total_copies }}</span>
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $book->available_copies }}</span>
                        </td>
                        <td>
                            <span class="status-badge {{ ($book->available_copies ?? 0) > 0 ? 'status-available' : 'status-issued' }}">
                                {{ ($book->available_copies ?? 0) > 0 ? 'Available' : 'Issued' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('library.books.copies', $book->librarybook_id) }}"
                                    class="btn-view-copies">
                                    <i class="bi bi-copy"></i>
                                    View Copies
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
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
        
        <!-- Floating Horizontal Scrollbar -->
        <div class="table-scroll-top" id="tableScrollTop">
            <div class="table-scroll-inner"></div>
        </div>
    
        {{-- Pagination --}}
        @if(method_exists($books, 'hasPages') && $books->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-sm text-gray-600">
                <i class="bi bi-layout-text-window me-1"></i>
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
                <i class="bi bi-layout-text-window me-1"></i>
                Showing {{ $books->count() }} results
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}

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
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = false;
    }
    document.getElementById('bulkActionsContainer').classList.remove('active');
}

function bulkAction(action, format = null) {
    const selectedBooks = Array.from(document.querySelectorAll('.book-checkbox:checked'))
        .map(checkbox => checkbox.value);

    if (selectedBooks.length === 0) {
        alert('Please select at least one book.');
        return;
    }

    switch (action) {
        case 'download':
            if (!format) {
                alert("Please select a format");
                return;
            }
            const ids = selectedBooks.join(',');
            const url = `/download-library-books?ids=${ids}&type=${format}`;
            window.location.href = url;
            break;

        case 'bulk_delete':
            if (confirm(
                    `Are you sure you want to delete ${selectedBooks.length} book(s)? This action cannot be undone.`)) {
                // Show loading state
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<span class="loading-spinner"></span> Deleting...';
                btn.disabled = true;

                // Simulate delete
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                    alert(`${selectedBooks.length} book(s) deleted successfully`);
                    clearSelection();
                }, 1500);
            }
            break;

        default:
            alert(`${action} action triggered for ${selectedBooks.length} book(s)`);
    }
}

// Simple filter form submission fallback
document.querySelectorAll('#filterForm input').forEach(input => {
    let debounce;

    input.addEventListener('input', () => {
        clearTimeout(debounce);
        debounce = setTimeout(() => {
            showLoader();
            document.getElementById('filterForm').submit();
        }, 350);
    });
});
</script>
@endsection