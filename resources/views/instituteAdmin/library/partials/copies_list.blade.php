@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Book Copies - {{ $book->title }}</title>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --success-gradient: linear-gradient(135deg, #10b981, #059669);
        --success-color: #10b981;
        --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        --danger-color: #ef4444;
        --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
        --light-bg: #f8fafc;
        --border-color: #e2e8f0;
        --card-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    /* Dashboard Header */
    .dashboard-header {
        background: var(--primary-gradient);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
        animation: fadeInDown 0.5s ease;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dashboard-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
        pointer-events: none;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .header-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 1;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .header-title i {
        font-size: 2.2rem;
        filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
    }

    .header-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 0;
        position: relative;
        z-index: 1;
    }

    .book-info {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        position: relative;
        z-index: 1;
    }

    .book-info-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
    }

    .book-info-item i {
        opacity: 0.8;
    }

    /* Stats Cards */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        border: 2px solid var(--border-color);
        transition: all 0.3s;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
    }

    .stat-icon-primary {
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        color: var(--primary-color);
    }

    .stat-icon-success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: var(--success-color);
    }

    .stat-icon-danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: var(--danger-color);
    }

    .stat-content {
        flex: 1;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.2;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Action Buttons */
    .action-buttons-top {
        display: flex;
        gap: 12px;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .btn-action {
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        text-decoration: none;
    }

    .btn-action:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        text-decoration: none;
    }

    .btn-add {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .btn-add:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.25);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.25);
        color: white;
    }

    /* Table Card */
    .table-card {
        background: white;
        border-radius: 20px;
        /*overflow: hidden;*/
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        border: 2px solid var(--border-color);
        animation: fadeInUp 0.6s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card-header-custom {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 20px 25px;
        border-bottom: 2px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .card-header-custom h5 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header-custom h5 i {
        color: var(--primary-color);
        font-size: 1.2rem;
    }

    .btn-add-copy {
        padding: 8px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        background: var(--success-gradient);
        color: white;
        border: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-add-copy:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        color: white;
        text-decoration: none;
    }

    .card-body-custom {
        padding: 0;
    }

    /* Table Design */
    .table-responsive {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .table-header {
        background: linear-gradient(135deg, #1e293b, #0f172a);
        color: white;
    }

    .table-header th {
        padding: 16px 20px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        white-space: nowrap;
        color: white;
    }

    .table-header th i {
        margin-right: 6px;
        color: rgba(255, 255, 255, 0.8);
    }

    .table-body tr {
        border-bottom: 1px solid var(--border-color);
        transition: all 0.3s;
    }

    .table-body tr:hover {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .table-body td {
        padding: 20px;
        vertical-align: middle;
        border: none;
    }

    /* Copy ID */
    .copy-id {
        font-weight: 700;
        color: var(--primary-color);
        font-family: monospace;
        font-size: 0.9rem;
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .status-available {
        background: var(--success-gradient);
        color: white;
    }

    .status-issued {
        background: var(--danger-gradient);
        color: white;
    }

    /* Condition Badge */
    .condition-badge {
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        color: #475569;
    }

    .condition-badge.new { background: var(--success-gradient); color: white; }
    .condition-badge.good { background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; }
    .condition-badge.fair { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; }
    .condition-badge.poor { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; }
    .condition-badge.damage { background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; }

    /* Location Info */
    .location-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .location-text {
        font-size: 0.8rem;
        color: #475569;
    }

    /* Action Buttons in Table */
    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .btn-table {
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 0.7rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: all 0.3s;
        border: 2px solid var(--border-color);
        cursor: pointer;
        background: white;
        color: #475569;
        text-decoration: none;
    }

    .btn-table:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        text-decoration: none;
    }

    .btn-table-edit:hover {
        background: var(--warning-gradient);
        color: white;
        border-color: transparent;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 16px;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 20px;
    }

    .empty-state h4 {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #64748b;
    }

    /* Alert Messages */
    .alert {
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
        border: none;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideInRight 0.3s ease;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .alert-success {
        background: var(--success-gradient);
        color: white;
    }

    .alert .btn-close {
        filter: invert(1);
        opacity: 0.8;
    }

    /* Pagination */
    .pagination-wrapper {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 16px 20px;
        border-top: 2px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .records-count {
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 500;
    }

    .pagination-buttons {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .pagination-buttons nav {
        margin: 0;
    }

    .pagination-buttons .pagination {
        margin: 0;
    }

    .pagination-buttons .page-link {
        border-radius: 10px;
        border: 2px solid var(--border-color);
        color: #475569;
        font-weight: 600;
        transition: all 0.3s;
        margin: 0 2px;
    }

    .pagination-buttons .page-link:hover {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
    }

    .pagination-buttons .active .page-link {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
    }

    /* Back Button Container */
    .back-button-container {
        margin-top: 20px;
    }

    .btn-back-bottom {
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        color: #475569;
        border: 2px solid var(--border-color);
        text-decoration: none;
        transition: all 0.3s;
    }

    .btn-back-bottom:hover {
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        color: #1e293b;
        transform: translateY(-2px);
        text-decoration: none;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .stats-container {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .container {
            padding: 15px;
        }

        .dashboard-header {
            padding: 20px;
        }

        .header-title {
            font-size: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
        }

        .action-buttons-top {
            margin-top: 15px;
            width: 100%;
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }

        .stats-container {
            grid-template-columns: 1fr;
        }

        .book-info {
            flex-direction: column;
            gap: 8px;
        }

        .card-header-custom {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-add-copy {
            width: 100%;
            justify-content: center;
        }

        .action-buttons {
            flex-direction: column;
            align-items: center;
        }

        .btn-table {
            width: 100%;
            justify-content: center;
        }

        .pagination-wrapper {
            flex-direction: column;
            text-align: center;
        }
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
            padding: 15px 8px;
            font-weight: 600;
            color: white;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            cursor: pointer;
            user-select: none;
            transition: background-color 0.2s;
            position: relative;
        }

        .erp-table th h6 {
            color: rgba(255,255,255,0.9);
            font-size: 11px;
            margin: 2px 0 0;
        }

        .erp-table th:hover {
            background-color: rgba(255,255,255,0.1);
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
            color: rgba(255,255,255,0.5);
            font-size: 12px;
            line-height: 1;
        }

        .sort-icon.active {
            color: white;
        }

        .erp-table td {
            padding: 15px 8px;
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .erp-table tbody tr:last-child td {
            border-bottom: none;
        }
    
        .table-responsive{
            overflow-x: hidden;
        }
</style>

<div class="container py-4">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="header-title">
                    <i class="bi bi-files"></i>Book Copies
                </h1>
                <p class="header-subtitle">Manage all copies of this book</p>
            </div>
            <div class="action-buttons-top">
                <a href="{{ route('library.books.add-copy', $book->librarybook_id) }}" class="btn-action btn-add">
                    <i class="bi bi-plus-circle"></i>Add New Copy
                </a>
                <a href="{{ route('library.data') }}" class="btn-action btn-back">
                    <i class="bi bi-arrow-left"></i>Back to Books List
                </a>
            </div>
        </div>

        <!-- Book Information -->
        <div class="book-info">
            <div class="book-info-item">
                <i class="bi bi-upc-scan"></i>
                <span><strong>Book ID:</strong> {{ $book->librarybook_id }}</span>
            </div>
            <div class="book-info-item">
                <i class="bi bi-book"></i>
                <span><strong>Title:</strong> {{ $book->title }}</span>
            </div>
            <div class="book-info-item">
                <i class="bi bi-tag"></i>
                <span><strong>Subject:</strong> {{ $book->subject }}</span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon stat-icon-primary">
                <i class="bi bi-files"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $copies->total() }}</div>
                <div class="stat-label">Total Copies</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $availableCopies }}</div>
                <div class="stat-label">Available</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-danger">
                <i class="bi bi-book-half"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $issuedCopies }}</div>
                <div class="stat-label">Issued</div>
            </div>
        </div>
    </div>

    <!-- Messages -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Table Card -->
    <div class="table-card">
        <div class="card-header-custom">
            <h5>
                <i class="bi bi-list-ul"></i>
                All Copies
            </h5>
            <a href="{{ route('library.books.add-copy', $book->librarybook_id) }}" class="btn-add-copy">
                <i class="bi bi-plus-circle"></i> Add New Copy
            </a>
        </div>
        <div class="card-body-custom">
            <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                <table class="erp-table table">
                    <thead class="table-header">
                        <tr>
                            <th class="sticky-main-2 sortable"><i class="bi bi-upc-scan"></i> COPY ID</th>
                            <th class="sortable"><i class="bi bi-flag"></i> STATUS</th>
                            <th class="sortable"><i class="bi bi-pencil"></i> AUTHOR</th>
                            <th class="sortable"><i class="bi bi-calendar"></i> PUBLISH DATE</th>
                            <th class="sortable"><i class="bi bi-file-text"></i> PAGES</th>
                            <th class="sortable"><i class="bi bi-currency-rupee"></i> COST</th>
                            <th class="sortable"><i class="bi bi-geo-alt"></i> LOCATION</th>
                            <th class="sortable"><i class="bi bi-clipboard-check"></i> CONDITION</th>
                            <th class="sortable"><i class="bi bi-gear"></i> ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        @forelse($copies as $copy)
                        <tr>
                            <td class="sticky-main-2">
                                <span class="copy-id">{{ $copy->copy_id }}</span>
                            </td>
                            <td>
                                <span class="status-badge {{ $copy->status == 'available' ? 'status-available' : 'status-issued' }}">
                                    <i class="bi bi-{{ $copy->status == 'available' ? 'check-circle' : 'exclamation-circle' }}"></i>
                                    {{ ucfirst($copy->status) }}
                                </span>
                            </td>
                            <td>{{ $copy->writer_name ?? '-' }}</td>
                            <td>{{ $copy->publishing_date ? date('d M Y', strtotime($copy->publishing_date)) : '-' }}</td>
                            <td>{{ $copy->pages ?? '-' }}</td>
                            <td>{{ $copy->cost ? '₹' . number_format($copy->cost, 2) : '-' }}</td>
                            <td>
                                @if($copy->rack || $copy->shelf)
                                <div class="location-info">
                                    @if($copy->rack)
                                    <span class="location-text"><i class="bi bi-grid-3x3"></i> Rack: {{ $copy->rack }}</span>
                                    @endif
                                    @if($copy->shelf)
                                    <span class="location-text"><i class="bi bi-layers"></i> Shelf: {{ $copy->shelf }}</span>
                                    @endif
                                </div>
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                @if($copy->condition)
                                    <span class="condition-badge {{ $copy->condition }}">
                                        <i class="bi {{ 
                                            $copy->condition == 'new' ? 'bi-star' : 
                                            ($copy->condition == 'good' ? 'bi-check-circle' : 
                                            ($copy->condition == 'fair' ? 'bi-exclamation-triangle' : 
                                            ($copy->condition == 'poor' ? 'bi-arrow-down-circle' : 'bi-tools'))) 
                                        }}"></i>
                                        {{ ucfirst($copy->condition) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('library.copies.edit', $copy->id) }}" 
                                       class="btn-table btn-table-edit">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
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
                                    <h4>No Copies Found</h4>
                                    <p>This book doesn't have any copies yet. Add your first copy to get started.</p>
                                    <a href="{{ route('library.books.add-copy', $book->librarybook_id) }}" class="btn-add-copy" style="display: inline-flex;">
                                        <i class="bi bi-plus-circle"></i> Add New Copy
                                    </a>
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
            
            <!-- Pagination -->
            @if($copies->hasPages())
            <div class="pagination-wrapper">
                <div class="records-count">
                    <i class="bi bi-list-ul me-2"></i>
                    Showing {{ $copies->firstItem() }} to {{ $copies->lastItem() }} of {{ $copies->total() }} copies
                </div>
                <div class="pagination-buttons">
                    {{ $copies->links('pagination::bootstrap-4') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function confirmDelete(copyId) {
    if (confirm('Are you sure you want to delete this copy? This action cannot be undone.')) {
        document.getElementById('delete-form-' + copyId).submit();
    }
}
</script>
@endsection