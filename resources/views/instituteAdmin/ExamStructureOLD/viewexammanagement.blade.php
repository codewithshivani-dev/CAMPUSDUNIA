@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --primary-light: rgba(67, 97, 238, 0.1);
        --success-gradient: linear-gradient(135deg, #10b981, #059669);
        --success-color: #10b981;
        --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        --danger-color: #ef4444;
        --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
        --dark: #1f2937;
        --gray: #6b7280;
        --light-gray: #f9fafb;
        --border: #e5e7eb;
        --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
    }
    
    .table-responsive{
        overflow-x: hidden;
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
        animation: fadeIn 0.5s ease;
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

    .add-btn {
        padding: 12px 24px;
        background: var(--primary-gradient);
        border: none;
        color: #fff !important;
        cursor: pointer;
        border-radius: 10px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .add-btn:hover {
        transform: translateY(-3px);
        color: #fff;
        text-decoration: none;
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

    /* Filter container - Enhanced */
    .filter-container {
        background: white;
        border-radius: 16px;
        padding: 24px;
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
        flex-direction: column;
        gap: 15px;
    }

    .filter-grid {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
        position: relative;
    }

    .filter-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark);
        font-size: 14px;
        letter-spacing: 0.3px;
    }

    .filter-group .bi {
        position: absolute;
        left: 12px;
        top: 12px;
        color: var(--primary-color);
        z-index: 1;
        font-size: 16px;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 12px 12px 12px 40px;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        background: #fff;
        transition: all 0.3s;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        transform: translateY(-2px);
    }

    .filter-group input:hover,
    .filter-group select:hover {
        border-color: var(--secondary-color);
    }

    .filter-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 10px;
        padding-top: 15px;
        border-top: 1px solid var(--border);
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
        border: 1px solid var(--border);
    }

    .btn-filter-secondary:hover {
        background: var(--primary-gradient);
        color: white !important;
        border-color: transparent;
    }

    .btn-filter-success {
        background: var(--success-gradient);
        color: white;
    }

    .btn-filter-success:hover {
        background: linear-gradient(135deg, #059669, #10b981);
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
        display: none;
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
        transition: all 0.3s;
        font-size: 14px;
        display: flex;
        align-items: center;
        border: 1px solid transparent;
        margin-right: 5px;
        color: white;
    }

    .bulk-action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        color: white !important;
    }

    .bulk-action-btn:active {
        transform: translateY(-1px);
    }

    .bulk-action-btn.download {
        background: var(--success-gradient);
    }

    .bulk-action-btn.download:hover {
        background: linear-gradient(135deg, #059669, #10b981);
    }

    .bulk-action-btn.delete {
        background: var(--danger-gradient);
    }

    .bulk-action-btn.delete:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
    }

    .bulk-action-btn.clear {
        background: linear-gradient(135deg, #64748b, #475569);
    }

    .bulk-action-btn.clear:hover {
        background: linear-gradient(135deg, #475569, #334155);
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

    /* Checkbox styling */
    .select-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
        border-radius: 4px;
        border: 2px solid #cbd5e1;
        transition: all 0.2s;
        margin: 0;
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

    .status-draft {
        background: linear-gradient(135deg, #64748b, #475569);
    }

    .status-scheduled {
        background: var(--info-gradient);
    }

    .status-ongoing {
        background: var(--warning-gradient);
    }

    .status-completed {
        background: var(--success-gradient);
    }

    .status-cancelled {
        background: var(--danger-gradient);
    }

    /* Published Badges */
    .published-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        margin-left: 8px;
        color: white;
    }

    .badge-published {
        background: var(--success-gradient);
    }

    .badge-draft {
        background: linear-gradient(135deg, #64748b, #475569);
    }

    /* Action Buttons - Enhanced */
    .table-actions {
        display: flex;
        gap: 8px;
        justify-content: center;
        /*flex-wrap: wrap;*/
    }

    .action-btn {
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 5px;
        justify-content: center;
        color: white;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        min-width: 70px;
    }

    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        color: white !important;
    }

    .action-btn:active {
        transform: translateY(-1px);
    }

    .action-btn-view {
        background: var(--info-gradient);
    }

    .action-btn-view:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
    }

    .action-btn-edit {
        background: var(--warning-gradient);
    }

    .action-btn-edit:hover {
        background: linear-gradient(135deg, #d97706, #b45309);
    }

    .action-btn-delete {
        background: var(--danger-gradient);
    }

    .action-btn-delete:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
    }

    /* Pagination - Enhanced */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding: 15px 20px;
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .pagination-info {
        font-size: 14px;
        color: var(--gray);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .pagination-info i {
        color: var(--primary-color);
    }

    .pagination-controls {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .btn-outline-primary {
        padding: 8px 16px;
        border: 2px solid var(--border);
        border-radius: 8px;
        background: white;
        color: var(--dark);
        font-weight: 500;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-outline-primary:hover:not(:disabled) {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
    }

    .btn-outline-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
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
        color: var(--dark);
        margin-bottom: 10px;
        font-weight: 600;
    }

    .empty-state p {
        color: var(--gray);
    }

    /* Loading Spinner */
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 8px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Modal Styles - Enhanced */
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .modal-header {
        background: var(--primary-gradient);
        border-bottom: none;
        padding: 20px 24px;
    }

    .modal-title {
        font-weight: 600;
        color: white;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-title i {
        font-size: 1.3rem;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }

    .modal-body {
        padding: 24px;
    }

    .modal-footer {
        border-top: 1px solid var(--border);
        padding: 16px 24px;
    }

    /* Exam Details Grid */
    .exam-details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .detail-item {
        margin-bottom: 15px;
    }

    .detail-label {
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 5px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        color: var(--dark);
        padding: 10px 14px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 8px;
        border: 1px solid var(--border);
        font-size: 14px;
        min-height: 42px;
        display: flex;
        align-items: center;
    }

    /* Form Styles - Enhanced */
    .edit-form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark);
        font-size: 14px;
        letter-spacing: 0.3px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px 14px;
        border-radius: 8px;
        border: 2px solid var(--border);
        font-size: 14px;
        background: #fff;
        transition: all 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        transform: translateY(-2px);
    }

    .form-group input:hover,
    .form-group select:hover,
    .form-group textarea:hover {
        border-color: var(--secondary-color);
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    /* Custom Alert */
    .custom-alert {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        border: none;
        padding: 15px 20px;
    }

    /* Text utilities */
    .text-muted {
        color: var(--gray) !important;
    }

    .small {
        font-size: 12px;
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

        .add-btn {
            width: 100%;
            justify-content: center;
        }

        .filter-grid {
            flex-direction: column;
        }

        .filter-group {
            min-width: 100%;
        }

        .filter-actions {
            flex-direction: column;
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

        .table-actions {
            flex-direction: column;
            gap: 4px;
        }

        .action-btn {
            width: 100%;
        }

        .exam-details-grid,
        .edit-form-grid {
            grid-template-columns: 1fr;
        }

        .pagination-container {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .pagination-controls {
            flex-direction: column;
            width: 100%;
        }

        .btn-outline-primary {
            width: 100%;
            justify-content: center;
        }
    }

    /* Loading spinner */
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #ccc;
        border-top-color: var(--primary-color);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
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

</style>

<div id="pageLoader">
    <div class="spinner"></div>
</div>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-calendar-check-fill"></i>
            Exam Management
        </h1>
        <a href="{{ route('institute-admin.exam-structure.create') }}" class="add-btn">
            <i class="bi bi-plus-circle"></i>
            Create New Exam
        </a>
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
        <form method="GET" id="filterForm" class="filter-form">
            <div class="filter-grid">
                <div class="filter-group">
                    <i class="bi bi-search"></i>
                    <input type="search" name="exam_name" list="examNameList" class="filter-input auto-filter"
                        placeholder="Search exam">
                    <datalist id="examNameList"></datalist>
                </div>

                <div class="filter-group">
                    <i class="bi bi-book"></i>
                    <input type="search" name="subject" list="subjectList" class="filter-input auto-filter"
                        placeholder="Search subject">
                    <datalist id="subjectList"></datalist>
                </div>

                <div class="filter-group">
                    <i class="bi bi-clock"></i>
                    <input type="search" name="duration" list="durationList" class="filter-input auto-filter"
                        placeholder="Duration">
                    <datalist id="durationList"></datalist>
                </div>

                <div class="filter-group">
                    <i class="bi bi-door-open"></i>
                    <input type="search" name="classroom" list="classroomList" class="filter-input auto-filter"
                        placeholder="Classroom">
                    <datalist id="classroomList"></datalist>
                </div>
            </div>
            
            <div class="filter-actions">
                <button type="button" class="btn-filter btn-filter-secondary" onclick="window.location='{{ url()->current() }}'">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </button>
            </div>
            
            <input type="hidden" id="sortBy" value="exam_id">
            <input type="hidden" id="sortOrder" value="desc">
        </form>
    </div>

    {{-- Bulk Actions Container --}}
    <div class="bulk-actions-container active" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 exams selected</div>
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
                Clear Selection
            </button>
        </div>
    </div>

    <div>
        {{-- Exams Table --}}
        <div class="table-responsive custom-table-wrapper w-100" id="tableWrapper">
            <table class="erp-table" id="examsTable">
                <thead>
                    <tr>
                        <th class="sticky-checkbox" width="40">
                            <input type="checkbox" id="selectAll" class="select-checkbox">
                        </th>
                        <th class="sticky-main sortable" onclick="sortTable('exam_id')">
                            Exam ID
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'exam_id' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'exam_id' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('exam_name')">
                            Exam Name
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'exam_name' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'exam_name' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('subject_name')">
                            Subject
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'subject_name' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'subject_name' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('exam_date')">
                            Date & Time
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'exam_date' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'exam_date' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('duration_minutes')">
                            Duration
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'duration_minutes' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'duration_minutes' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('classroom_id')">
                            Classroom
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'classroom_id' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'classroom_id' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
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
                <tbody id="examsTableBody">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
    
            <!-- No Data State -->
            <div id="noData" class="empty-state" style="display: none;">
                <div class="empty-state-icon">
                    <i class="bi bi-calendar-x"></i>
                </div>
                <h4>No Exams Found</h4>
                <p>No exams match your filter criteria. Try adjusting your filters.</p>
            </div>
        </div>
        
        <!-- Floating Horizontal Scrollbar -->
        <div class="table-scroll-top" id="tableScrollTop">
            <div class="table-scroll-inner"></div>
        </div>
    
        {{-- Pagination --}}
        <div class="pagination-container" id="paginationContainer" style="display: none;">
            <div class="pagination-info">
                <i class="bi bi-layout-text-window"></i>
                <span id="paginationInfo">Showing 0 to 0 of 0 entries</span>
            </div>
            <div class="pagination-controls">
                <button id="prevPage" class="btn-outline-primary" disabled>
                    <i class="bi bi-chevron-left"></i> Previous
                </button>
                <span id="pageInfo" class="align-self-center mx-3" style="color: var(--gray);">Page 1 of 1</span>
                <button id="nextPage" class="btn-outline-primary" disabled>
                    Next <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Exam Modal -->
<div class="modal fade" id="viewExamModal" tabindex="-1" aria-labelledby="viewExamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-calendar-check"></i>
                    Exam Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="exam-details-grid" id="examDetailsContent">
                    <!-- Details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background: linear-gradient(135deg, #64748b, #475569); border: none; padding: 8px 20px; border-radius: 8px; color: white;">Close</button>
                <button type="button" class="btn btn-primary" onclick="editExam()" style="background: var(--primary-gradient); border: none; padding: 8px 20px; border-radius: 8px;">
                    <i class="bi bi-pencil-square me-2"></i> Edit Exam
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Exam Modal -->
<div class="modal fade" id="editExamModal" tabindex="-1" aria-labelledby="editExamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="editExamForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editExamId" name="id">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square"></i>
                        Edit Exam
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="edit-form-grid">
                        <div class="form-group">
                            <label for="editExamName">Exam Name *</label>
                            <input type="text" id="editExamName" name="exam_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editExamDate">Exam Date *</label>
                            <input type="date" id="editExamDate" name="exam_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editStartTime">Start Time *</label>
                            <input type="time" id="editStartTime" name="start_time" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editEndTime">End Time *</label>
                            <input type="time" id="editEndTime" name="end_time" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editDuration">Duration (minutes) *</label>
                            <input type="number" id="editDuration" name="duration_minutes" class="form-control" min="15"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="editClassroom">Classroom *</label>
                            <input type="text" id="editClassroom" name="classroom_id" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editTotalMarks">Total Marks *</label>
                            <input type="number" id="editTotalMarks" name="total_marks" class="form-control" min="1"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="editPassingMarks">Passing Marks *</label>
                            <input type="number" id="editPassingMarks" name="passing_marks" class="form-control" min="0"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="editStatus">Status *</label>
                            <select id="editStatus" name="status" class="form-control" required>
                                <option value="draft">Draft</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editIsPublished">Published Status</label>
                            <select id="editIsPublished" name="is_published" class="form-control">
                                <option value="0">Not Published</option>
                                <option value="1">Published</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background: linear-gradient(135deg, #64748b, #475569); border: none; padding: 8px 20px; border-radius: 8px; color: white;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background: var(--primary-gradient); border: none; padding: 8px 20px; border-radius: 8px;">
                        <i class="bi bi-save me-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color: white;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: brightness(0) invert(1);"></button>
            </div>
            <div class="modal-body">
                <p style="color: var(--dark);">Are you sure you want to delete this exam? This action cannot be undone.</p>
                <input type="hidden" id="deleteExamId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background: linear-gradient(135deg, #64748b, #475569); border: none; padding: 8px 20px; border-radius: 8px; color: white;">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()" style="background: var(--danger-gradient); border: none; padding: 8px 20px; border-radius: 8px;">
                    <i class="bi bi-trash-fill me-2"></i> Delete Exam
                </button>
            </div>
        </div>
    </div>
</div>


<script>
function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}

document.getElementById('filterForm')?.addEventListener('submit', e => {
    showLoader();
    e.preventDefault();
});

document.addEventListener('DOMContentLoaded', function() {
    // Global variables
    let currentPage = 1;
    let perPage = 20;
    let totalPages = 1;
    let currentExamId = null;
    let currentExams = [];

    // CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Initialize
    loadExams();

    // Bulk Selection Management
    const selectAllCheckbox = document.getElementById('selectAll');
    const bulkActionsContainer = document.getElementById('bulkActionsContainer');
    const selectedCountElement = document.getElementById('selectedCount');

    function updateSelectionUI() {
        const selectedCount = document.querySelectorAll('.exam-checkbox:checked').length;

        if (selectedCount > 0) {
            bulkActionsContainer.classList.add('active');
            selectedCountElement.textContent = selectedCount + ' exam(s) selected';

            // Update select all checkbox state
            selectAllCheckbox.checked = selectedCount === currentExams.length;
            selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < currentExams.length;
        } else {
            // bulkActionsContainer.classList.remove('active');
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
            selectedCountElement.textContent = '0 exams selected';
        }
    }

    // Bulk Action Functions
    function clearSelection() {
        document.querySelectorAll('.exam-checkbox:checked').forEach(checkbox => {
            checkbox.checked = false;
        });
        document.getElementById('selectAll').checked = false;
        document.getElementById('bulkActionsContainer').classList.remove('active');
    }

    window.bulkAction = function(action, format=null) {
            const selectedExams = Array.from(document.querySelectorAll('.exam-checkbox:checked'))
                .map(checkbox => checkbox.value);

            if (selectedExams.length === 0) {
                alert('Please select at least one exam.');
                return;
            }

            switch (action) {
                case 'download':

                        if (!format) {
                            alert("Please select a format");
                            return;
                        }

                        const ids = selectedExams.join(',');

                        const url = `/offline-exams/download?ids=${ids}&type=${format}`;

                        window.location.href = url;
                break;    


                case 'bulk_delete':
                    if (confirm(
                            `Are you sure you want to delete ${selectedExams.length} exam(s)? This action cannot be undone.`
                        )) {
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
                            alert(`${selectedExams.length} exams deleted successfully`);
                        }, 1500);
                    }
                    break;

                default:
                    alert(`${action} action triggered for ${selectedExams.length} exams`);
            }
        }

        // Sorting Functionality
        window.sortTable = function(column) {
            const sortByInput = document.getElementById('sortBy');
            const sortOrderInput = document.getElementById('sortOrder');

            let newOrder = 'asc';

            if (sortByInput.value === column) {
                newOrder = sortOrderInput.value === 'asc' ? 'desc' : 'asc';
            }

            sortByInput.value = column;
            sortOrderInput.value = newOrder;

            currentPage = 1;
            loadExams();
        }

        document.querySelectorAll('.auto-filter').forEach(input => {
            let timer;
            input.addEventListener('input', () => {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    currentPage = 1;
                    loadExams();
                }, 400);
            });
        });

        // document.getElementById('exportBtn').addEventListener('click', exportToExcel);

        document.getElementById('prevPage').addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                loadExams();
            }
        });

        document.getElementById('nextPage').addEventListener('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                loadExams();
            }
        });

        // Search input with debounce
        // let searchTimeout;
        // document.getElementById('search').addEventListener('input', function() {
        //     clearTimeout(searchTimeout);
        //     searchTimeout = setTimeout(() => {
        //         currentPage = 1;
        //         loadExams();
        //     }, 500);
        // });



        function loadExams() {
            const examsTable = document.getElementById('examsTable');
            const noData = document.getElementById('noData');
            const paginationContainer = document.getElementById('paginationContainer');

            examsTable.style.display = 'none';
            noData.style.display = 'none';
            paginationContainer.style.display = 'none';

            // ✅ CORRECT FILTER KEYS
            const filters = {
                page: currentPage,
                per_page: perPage,

                exam_name: document.querySelector('[name="exam_name"]')?.value || '',
                subject: document.querySelector('[name="subject"]')?.value || '',
                duration: document.querySelector('[name="duration"]')?.value || '',
                classroom: document.querySelector('[name="classroom"]')?.value || '',

                sort_by: document.getElementById('sortBy').value,
                sort_order: document.getElementById('sortOrder').value
            };

            fetch(`{{ route('institute-admin.exam-management.data') }}?${new URLSearchParams(filters)}`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.exams.length) {
                        currentExams = data.exams;

                        // Populate datalists directly from exams (frontend-only)
                        const examNames = new Set();
                        const subjects = new Set();
                        const durations = new Set();
                        const classrooms = new Set();

                        data.exams.forEach(exam => {
                            if (exam.exam_name) examNames.add(exam.exam_name);
                            if (exam.subject?.subject_name) subjects.add(exam.subject.subject_name);
                            if (exam.duration_minutes) durations.add(exam.duration_minutes);
                            if (exam.classroom_id) classrooms.add(exam.classroom_id);
                        });

                        const fill = (id, values) => {
                            const list = document.getElementById(id);
                            list.innerHTML = '';
                            values.forEach(v => {
                                const o = document.createElement('option');
                                o.value = v;
                                list.appendChild(o);
                            });
                        };

                        fill('examNameList', examNames);
                        fill('subjectList', subjects);
                        fill('durationList', durations);
                        fill('classroomList', classrooms);

                        // Edit form submission
                        document.getElementById('editExamForm').addEventListener('submit', function(e) {
                            e.preventDefault();
                            updateExam();
                        });

                        renderExamsTable(data.exams);
                        updatePagination(data);

                        examsTable.style.display = 'table';
                        paginationContainer.style.display = 'flex';
                    } else {
                        noData.style.display = 'block';
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Failed to load exams');
                });

            console.log('FILTERS SENT:', filters);
        }

        function renderExamsTable(exams) {
            const tbody = document.getElementById('examsTableBody');
            tbody.innerHTML = '';

            exams.forEach(exam => {
                const row = document.createElement('tr');

                // Format date and time
                const examDate = new Date(exam.exam_date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
                const startTime = formatTime(exam.start_time);
                const endTime = formatTime(exam.end_time);

                // Status badge
                const statusClass = `status-${exam.status}`;
                const statusText = exam.status.charAt(0).toUpperCase() + exam.status.slice(1);
                const totalMinutes = exam.duration_minutes;

                const hours = Math.floor(totalMinutes / 60);
                const minutes = totalMinutes % 60;
                let durationText = '';
                if (hours > 0) {
                    durationText += `${hours} hour${hours > 1 ? 's' : ''} `;
                }
                if (minutes > 0) {
                    durationText += `${minutes} min${minutes > 1 ? 's' : ''}`;
                }

                // Published badge
                const publishedBadge = exam.is_published ?
                    '<span class="published-badge badge-published">Published</span>' :
                    '<span class="published-badge badge-draft">Draft</span>';

                row.innerHTML = `
                    <td class="sticky-checkbox">
                        <input type="checkbox" class="exam-checkbox select-checkbox" value="${exam.id}">
                    </td>
                    <td class="sticky-main">
                        <span style="font-weight: 600; color: var(--primary-color);">${exam.exam_id}</span>
                    </td>
                    <td>${exam.exam_name} ${publishedBadge}</td>
                    <td>${exam.subject?.subject_name || 'N/A'}</td>
                    <td>
                        <div><i class="bi bi-calendar" style="color: var(--primary-color); font-size: 11px;"></i> ${examDate}</div>
                        <small class="text-muted"><i class="bi bi-clock" style="color: var(--primary-color); font-size: 11px;"></i> ${startTime} - ${endTime}</small>
                    </td>
                    <td><i class="bi bi-hourglass-split" style="color: var(--primary-color); font-size: 11px; margin-right: 4px;"></i>${durationText.trim()}</td>
                    <td><i class="bi bi-door-open" style="color: var(--primary-color); font-size: 11px; margin-right: 4px;"></i>${exam.classroom_id || 'N/A'}</td>
                    <td>
                        <span class="status-badge ${statusClass}">${statusText}</span>
                    </td>
                    <td class="text-center">
                        <div class="table-actions">
                            <button class="action-btn action-btn-view" onclick="viewExam(${exam.id})" title="View Details">
                                <i class="bi bi-eye"></i>
                                <span class="small">View</span>
                            </button>
                            <button class="action-btn action-btn-edit" onclick="editExam(${exam.id})" title="Edit">
                                <i class="bi bi-pencil"></i>
                                <span class="small">Edit</span>
                            </button>
                            <button class="action-btn action-btn-delete" onclick="deleteExam(${exam.id})" title="Delete">
                                <i class="bi bi-trash"></i>
                                <span class="small">Delete</span>
                            </button>
                        </div>
                    </td>
                `;

                tbody.appendChild(row);
            });

            // Add event listeners to checkboxes
            const examCheckboxes = document.querySelectorAll('.exam-checkbox');
            examCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectionUI);
            });

            // Add select all functionality
            selectAllCheckbox.addEventListener('change', function() {
                examCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectionUI();
            });
        }

        function updatePagination(data) {
            totalPages = data.last_page;

            // Update pagination info
            const start = ((currentPage - 1) * perPage) + 1;
            const end = Math.min(currentPage * perPage, data.total);
            document.getElementById('paginationInfo').textContent =
                `Showing ${start} to ${end} of ${data.total} entries`;

            document.getElementById('pageInfo').textContent =
                `Page ${currentPage} of ${totalPages}`;

            // Update button states
            document.getElementById('prevPage').disabled = currentPage <= 1;
            document.getElementById('nextPage').disabled = currentPage >= totalPages;
        }

        function clearFilters() {
            document.getElementById('search').value = '';
            document.getElementById('filterCategory').value = '';
            document.getElementById('filterDepartment').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterDateFrom').value = '';
            document.getElementById('filterDateTo').value = '';
            document.getElementById('sortBy').value = 'exam_id';
            document.getElementById('sortOrder').value = 'asc';
        }

        function formatTime(timeString) {
            if (!timeString) return 'N/A';

            try {
                const [hours, minutes] = timeString.split(':');
                const hour = parseInt(hours);
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const displayHour = hour % 12 || 12;
                return `${displayHour}:${minutes} ${ampm}`;
            } catch (e) {
                return timeString;
            }
        }

        window.viewExam = function(examId) {
            console.log('View exam clicked with ID:', examId);
            currentExamId = examId;

            fetch(`{{ route("institute-admin.exam-management.details", ":id") }}`.replace(':id', examId), {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Exam details response:', data);
                    if (data.success) {
                        renderExamDetails(data.exam);
                        const modalElement = document.getElementById('viewExamModal');
                        if (modalElement && typeof bootstrap !== 'undefined') {
                            const modal = new bootstrap.Modal(modalElement);
                            modal.show();
                        } else {
                            // Fallback
                            modalElement.style.display = 'block';
                            modalElement.classList.add('show');
                            document.body.classList.add('modal-open');
                            // Add backdrop
                            const backdrop = document.createElement('div');
                            backdrop.className = 'modal-backdrop fade show';
                            document.body.appendChild(backdrop);
                        }
                    } else {
                        alert('Error loading exam details: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading exam details');
                });
        }

        function renderExamDetails(exam) {
            const container = document.getElementById('examDetailsContent');

            // Format data
            const examDate = new Date(exam.exam_date).toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const startTime = formatTime(exam.start_time);
            const endTime = formatTime(exam.end_time);

            const statusClass = `status-${exam.status}`;
            const statusText = exam.status.charAt(0).toUpperCase() + exam.status.slice(1);

            container.innerHTML = `
                <div class="detail-item">
                    <div class="detail-label">Exam ID</div>
                    <div class="detail-value"><strong style="color: var(--primary-color);">${exam.exam_id}</strong></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Exam Name</div>
                    <div class="detail-value">${exam.exam_name}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Subject</div>
                    <div class="detail-value">${exam.subject?.subject_name || 'N/A'}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Course</div>
                    <div class="detail-value">${exam.course?.course_type || 'N/A'}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Department</div>
                    <div class="detail-value">${exam.department?.department || 'N/A'}</div>
                </div>
            
                <div class="detail-item">
                    <div class="detail-label">Exam Date</div>
                    <div class="detail-value">${examDate}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Time</div>
                    <div class="detail-value">${startTime} to ${endTime}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Duration</div>
                    <div class="detail-value">${exam.duration_minutes} minutes</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Classroom</div>
                    <div class="detail-value">${exam.classroom_id || 'N/A'}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Total Marks</div>
                    <div class="detail-value">${exam.total_marks}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Passing Marks</div>
                    <div class="detail-value">${exam.passing_marks}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Sections</div>
                    <div class="detail-value">
                        ${exam.section_names && exam.section_names.trim() !== '' 
                            ? exam.section_names 
                            : 'N/A'}
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Published</div>
                    <div class="detail-value">${exam.is_published ? 'Yes' : 'No'}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value"><span class="status-badge ${statusClass}">${statusText}</span></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Created At</div>
                    <div class="detail-value">${new Date(exam.created_at).toLocaleString()}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Last Updated</div>
                    <div class="detail-value">${new Date(exam.updated_at).toLocaleString()}</div>
                </div>
            `;
        }

        window.editExam = function(examId = null) {
            const examIdToEdit = examId || currentExamId;

            if (!examIdToEdit) return;

            // Close view modal if open
            const viewModalElement = document.getElementById('viewExamModal');
            if (viewModalElement) {
                const modal = bootstrap.Modal.getInstance(viewModalElement);
                if (modal) modal.hide();
            }

            fetch(`{{ route("institute-admin.exam-management.details", ":id") }}`.replace(':id',
                    examIdToEdit), {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Edit exam response:', data);
                    if (data.success) {
                        populateEditForm(data.exam);

                        // Show edit modal
                        const modalElement = document.getElementById('editExamModal');
                        if (modalElement && typeof bootstrap !== 'undefined') {
                            const modal = new bootstrap.Modal(modalElement);
                            modal.show();
                        }
                    } else {
                        alert('Error loading exam details: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading exam details');
                });
        }

        function populateEditForm(exam) {
            console.log('Populating edit form with exam:', exam);

            document.getElementById('editExamId').value = exam.id || '';
            document.getElementById('editExamName').value = exam.exam_name || '';

            // ✅ FIXED DATE FORMAT
            if (exam.exam_date) {
                document.getElementById('editExamDate').value =
                    exam.exam_date.split('T')[0].split(' ')[0];
            }

            document.getElementById('editStartTime').value = exam.start_time?.substring(0, 5) || '';
            document.getElementById('editEndTime').value = exam.end_time?.substring(0, 5) || '';
            document.getElementById('editDuration').value = exam.duration_minutes || '';
            document.getElementById('editClassroom').value = exam.classroom_id || '';
            document.getElementById('editTotalMarks').value = exam.total_marks || '';
            document.getElementById('editPassingMarks').value = exam.passing_marks || '';
            document.getElementById('editStatus').value = exam.status || 'draft';
            document.getElementById('editIsPublished').value = exam.is_published ? '1' : '0';
        }

        function updateExam() {
            const examId = document.getElementById('editExamId').value;
            console.log('Updating exam ID:', examId);

            // Create FormData from the form
            const formElement = document.getElementById('editExamForm');
            const formData = new FormData(formElement);

            // Add method spoofing for PUT
            formData.append('_method', 'PUT');

            const submitBtn = document.querySelector('#editExamForm button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="loading-spinner"></span> Saving...';
            submitBtn.disabled = true;

            fetch(`{{ route("institute-admin.exam-management.update", ":id") }}`.replace(':id', examId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Update response:', data);
                    if (data.success) {
                        // Close modal
                        const modalElement = document.getElementById('editExamModal');
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) modal.hide();

                        // Show success message
                        showAlert('Exam updated successfully!', 'success');

                        // Reload exams
                        loadExams();
                    } else {
                        if (data.errors) {
                            let errorMessage = 'Validation errors:\n';
                            Object.values(data.errors).forEach(errors => {
                                errors.forEach(error => {
                                    errorMessage += `• ${error}\n`;
                                });
                            });
                            alert(errorMessage);
                        } else {
                            alert('Error updating exam: ' + (data.message || 'Unknown error'));
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating exam');
                })
                .finally(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        }

        window.deleteExam = function(examId) {
            console.log('Delete exam clicked with ID:', examId);
            document.getElementById('deleteExamId').value = examId;

            // Show delete confirmation modal
            const modalElement = document.getElementById('deleteConfirmModal');
            if (modalElement && typeof bootstrap !== 'undefined') {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        }

        window.confirmDelete = function() {
            const examId = document.getElementById('deleteExamId').value;
            console.log('Confirm delete for exam ID:', examId);

            fetch(`{{ route("institute-admin.exam-management.delete", ":id") }}`.replace(':id', examId), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Delete response:', data);
                    if (data.success) {
                        // Close modal
                        const modalElement = document.getElementById('deleteConfirmModal');
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) modal.hide();

                        // Show success message
                        showAlert('Exam deleted successfully!', 'success');

                        // Reload exams
                        loadExams();
                    } else {
                        alert('Error deleting exam: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting exam');
                });
        }

        function exportToExcel() {
            // Prepare filters
            const filters = {
                search: document.getElementById('search').value,
                category_id: document.getElementById('filterCategory').value,
                department_id: document.getElementById('filterDepartment').value,
                status: document.getElementById('filterStatus').value,
                date_from: document.getElementById('filterDateFrom').value,
                date_to: document.getElementById('filterDateTo').value
            };

            // Create download link
            const url = `{{ route("institute-admin.exam-management.export") }}?${new URLSearchParams(filters)}`;
            window.open(url, '_blank');
        }

        function showAlert(message, type = 'info') {
            // Remove existing alerts
            const existingAlert = document.querySelector('.custom-alert');
            if (existingAlert) existingAlert.remove();

            // Create alert
            const alertEl = document.createElement('div');
            alertEl.className = `alert alert-${type} alert-dismissible fade show custom-alert`;
            alertEl.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertEl.innerHTML = `
                <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-info-circle-fill'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(alertEl);

            // Auto remove after 5 seconds
            setTimeout(() => {
                alertEl.remove();
            }, 5000);
        }
    });
</script>
@endsection