@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Transport Management</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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

    /* Stats Cards - Enhanced */
    .stats-cards {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .stat-card {
        background: white;
        border: none;
        border-radius: 16px;
        padding: 20px;
        flex: 1;
        min-width: 200px;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: all 0.3s;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-gradient);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.15);
    }

    .stat-icon {
        width: 55px;
        height: 55px;
        border-radius: 12px;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 28px;
    }

    .stat-content h3 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: var(--dark);
        line-height: 1.2;
    }

    .stat-content p {
        margin: 0;
        color: var(--gray);
        font-size: 14px;
        font-weight: 500;
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
        flex-direction: column;
        gap: 16px;
    }

    .filter-grid {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        flex: 1;
    }

    .filter-group {
        position: relative;
        /*flex: 1;*/
        min-width: 200px;
    }

    .filter-group .bi {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
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
        gap: 12px;
        align-items: center;
        justify-content: flex-end;
    }

    .btn-filter {
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        text-decoration: none;
        font-size: 14px;
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

    /* Route Group Header - Enhanced */
    .route-group-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-left: 4px solid var(--primary-color);
        font-weight: 600;
        color: var(--dark);
        cursor: pointer;
    }

    .route-group-header td {
        padding: 16px 15px;
        font-size: 1.05rem;
    }

    .route-group-header:hover {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    }

    .collapse-icon {
        transition: transform 0.3s;
        display: inline-block;
        color: var(--primary-color);
        font-size: 1.2rem;
    }

    .collapsed .collapse-icon {
        transform: rotate(-90deg);
    }

    /* Route Summary */
    .route-summary {
        display: flex;
        gap: 10px;
        font-size: 13px;
        color: var(--gray);
    }

    .route-summary-item {
        display: flex;
        align-items: center;
        gap: 5px;
        background: white;
        padding: 5px 12px;
        border-radius: 30px;
        border: 1px solid var(--border);
    }

    .route-summary-item i {
        color: var(--primary-color);
    }

    /* Route Badges */
    .route-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        margin: 2px;
        display: inline-block;
        font-weight: 600;
        color: white;
    }

    .route-type-morning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .route-type-evening {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }

    .route-type-both {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    /* Helper Chips */
    .helper-chip {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        display: inline-block;
        margin: 2px;
        border: 1px solid var(--border);
        color: var(--dark);
    }

    .helper-chip i {
        color: var(--primary-color);
        margin-right: 3px;
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

    .status-active {
        background: var(--success-gradient);
    }

    .status-inactive {
        background: linear-gradient(135deg, #64748b, #475569);
    }

    .badge.bg-success-subtle {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0) !important;
        color: #166534 !important;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 500;
    }

    .badge.bg-warning-subtle {
        background: linear-gradient(135deg, #fef3c7, #fde68a) !important;
        color: #92400e !important;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 500;
    }

    .badge.bg-danger-subtle {
        background: linear-gradient(135deg, #fee2e2, #fecaca) !important;
        color: #b91c1c !important;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 500;
    }

    .badge.bg-info-subtle {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe) !important;
        color: #1e40af !important;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 500;
    }

    /* Action Buttons - Enhanced */
    .action-btn {
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        color: white;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        color: white !important;
        text-decoration: none;
    }

    .action-btn:active {
        transform: translateY(-1px);
    }

    .action-btn-primary {
        background: var(--info-gradient);
    }

    .action-btn-primary:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
    }

    .action-btn-secondary {
        background: linear-gradient(135deg, #64748b, #475569);
    }

    .action-btn-secondary:hover {
        background: linear-gradient(135deg, #475569, #334155);
    }

    .custom-gap {
        gap: 8px;
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

    .alert ul {
        margin-left: 20px;
    }

    .alert .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* Pagination */
    .pagination-info {
        color: var(--gray);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .pagination-info i {
        color: var(--primary-color);
    }

    .pagination {
        gap: 5px;
    }

    .page-link {
        border-radius: 8px;
        border: 2px solid var(--border);
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
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #e2e8f0;
        border-top-color: var(--primary-color);
        border-radius: 50%;
        animation: spin 0.9s linear infinite;
    }

    #pageLoader {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.7);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    /* Modal Styles */
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

    .modal-header .modal-title {
        color: white;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-header .modal-title i {
        font-size: 1.3rem;
    }

    .modal-header .btn-close,
    .modal-header .modal-close-button {
        filter: brightness(0) invert(1);
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        opacity: 0.8;
        transition: opacity 0.3s;
    }

    .modal-header .btn-close:hover,
    .modal-header .modal-close-button:hover {
        opacity: 1;
    }

    .modal-body {
        padding: 24px;
    }

    /* Fee Cards */
    .card.mb-4 {
        border: 2px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .card-header.bg-light {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9) !important;
        border-bottom: 2px solid var(--border);
        padding: 12px 16px;
    }

    .card-header.bg-light h6 {
        color: var(--primary-color);
        font-weight: 600;
        margin: 0;
    }
    
    .table-responsive{
        overflow-x: hidden;
    }

    .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead th {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        color: var(--dark);
        font-weight: 600;
        border-bottom: 2px solid var(--border);
    }

    .table tbody tr:hover {
        background: var(--primary-light);
    }

    .table tfoot {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    }

    /* Text utilities */
    .text-muted {
        color: var(--gray) !important;
    }

    .fw-bold {
        font-weight: 700;
    }

    .text-primary {
        color: var(--primary-color) !important;
    }

    .text-success {
        color: var(--success-color) !important;
    }

    .text-danger {
        color: var(--danger-color) !important;
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

        .stats-cards {
            flex-direction: column;
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
            margin-right: 0;
        }

        .erp-table th,
        .erp-table td {
            padding: 10px;
            font-size: 13px;
        }

        .action-btn {
            padding: 6px 8px;
            font-size: 11px;
        }

        .route-summary {
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .route-summary-item {
            width: 100%;
        }

        .custom-gap {
            gap: 4px;
            flex-wrap: wrap;
        }

        .d-flex.justify-content-between.align-items-center.mt-4 {
            flex-direction: column;
            gap: 15px;
        }
    }
</style>

<div id="pageLoader">
    <div class="spinner"></div>
</div>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-bus-front-fill"></i>
            Transport Management
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.transport.routes.create') }}" class="btn-filter btn-filter-primary">
                <i class="bi bi-plus-circle"></i>
                Add New Route
            </a>
            <a href="{{ route('admin.transport.add') }}" class="btn-filter btn-filter-primary">
                <i class="bi bi-plus-circle"></i>
                Add New Bus
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    @php
    $totalBuses = $transportRoutes->total();
    $activeBuses = $transportRoutes->where('status', 1)->count();
    $totalCapacity = $transportRoutes->sum('sitting_capacity');
    $totalRoutes = isset($routeSummary) ? $routeSummary->count() :
    $transportRoutes->getCollection()->groupBy('route_id')->count();
    @endphp

    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-signpost-split"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $totalRoutes }}</h3>
                <p>Total Routes</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-bus-front"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $totalBuses }}</h3>
                <p>Total Buses</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $activeBuses }}</h3>
                <p>Active Buses</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $totalCapacity }}</h3>
                <p>Total Capacity</p>
            </div>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filters -->
    <div class="filter-container">
        <span class="small text-muted"><i class="bi bi-info-circle me-1"></i>Type or select any to search</span>
        <form method="GET" class="filter-form" id="filterForm">
            <div class="filter-grid">
                {{-- Bus Number --}}
                <div class="filter-group">
                    <i class="bi bi-bus-front"></i>
                    <input type="text" name="bus_number" class="filter-input" list="busNumbers"
                        value="{{ request('bus_number') }}" placeholder="Bus Number">
                    <datalist id="busNumbers">
                        @foreach($transportRoutes->pluck('bus_number')->filter()->unique() as $bus)
                        <option value="{{ $bus }}">
                        @endforeach
                    </datalist>
                </div>

                {{-- Route Name --}}
                <div class="filter-group">
                    <i class="bi bi-signpost"></i>
                    <input type="text" name="route_name" class="filter-input" list="routeNames"
                        value="{{ request('route_name') }}" placeholder="Route Name">
                    <datalist id="routeNames">
                        @foreach($routeNames as $route)
                        <option value="{{ $route }}" ></option>
                        @endforeach
                    </datalist>
                </div>

                {{-- Driver Name --}}
                <div class="filter-group">
                    <i class="bi bi-person-badge"></i>
                    <input type="text" name="driver_name" class="filter-input" list="driverNames"
                        value="{{ request('driver_name') }}" placeholder="Driver Name">
                    <datalist id="driverNames">
                        @foreach($transportRoutes->pluck('driver_name')->filter()->unique() as $driver)
                        <option value="{{ $driver }}">
                        @endforeach
                    </datalist>
                </div>

                {{-- Route Type --}}
                <div class="filter-group">
                    <i class="bi bi-clock"></i>
                    <input type="text" name="route_type" class="filter-input" list="routeTypes"
                        value="{{ request('route_type') }}" placeholder="Route Type">
                    <datalist id="routeTypes">
                        <option value="morning">Morning</option>
                        <option value="evening">Evening</option>
                        <option value="both">Both</option>
                    </datalist>
                </div>

                {{-- Status --}}
                <div class="filter-group">
                    <i class="bi bi-info-circle"></i>
                    <input type="text" name="status" class="filter-input" list="statusList"
                        value="{{ request('status') }}" placeholder="Status">
                    <datalist id="statusList">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </datalist>
                </div>
                
                <div class="filter-actions">
                    <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                        Reset Filters
                    </a>
                </div>
            </div>

            <!-- Hidden sort inputs -->
            <input type="hidden" name="sort_by" id="sortBy" value="{{ request('sort_by', 'created_at') }}">
            <input type="hidden" name="sort_order" id="sortOrder" value="{{ request('sort_order', 'desc') }}">
        </form>
    </div>

    @if($transportRoutes->count() > 0)
    @php
    $groupedByRoute = $transportRoutes->getCollection()->groupBy('route_id');
    @endphp

    {{-- Bulk Actions Container --}}
    <div class="bulk-actions-container" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 routes selected</div>
        <div class="d-flex flex-wrap">
            <div class="dropdown">
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
        <div class="table-responsive custom-table-wrapper" id="tableWrapper">
            <table class="erp-table">
                <thead>
                    <tr>
                        <th class="sticky-checkbox" width="40">
                            <input type="checkbox" id="selectAll" class="select-checkbox">
                        </th>
                        <th class="sticky-main sortable" onclick="sortTable('bus_details')">
                            Bus Details
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'bus_details' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'bus_details' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('route_info')">
                            Route Info
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'route_info' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'route_info' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('driver_details')">
                            Driver Details
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'driver_details' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'driver_details' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('schedule')">
                            Schedule
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'schedule' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'schedule' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('capacity')">
                            Capacity
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'capacity' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'capacity' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('fee')">
                            Fee
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'fee' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'fee' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('status')">
                            Status
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'status' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'status' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable text-center">Actions</th>
                    </tr>
                </thead>
    
                <tbody>
                    @foreach($groupedByRoute as $routeId => $buses)
                    @php
                    $firstBus = $buses->first();
                    $routeName = $firstBus->parent_route_name ?? $firstBus->route_name;
                    $totalBusesInRoute = $buses->count();
                    $activeBusesInRoute = $buses->where('status', 1)->count();
                    $inactiveBusesInRoute = $totalBusesInRoute - $activeBusesInRoute;
                    $totalCapacityInRoute = $buses->sum('sitting_capacity');
                    $avgStopsInRoute = round($buses->avg('total_stops'), 1);
                    @endphp
    
                    <!-- Route Group Header -->
                    <tr class="route-group-header" onclick="toggleRouteBuses('{{ $routeId }}')">
                        <td colspan="9">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div>
                                    <i class="bi bi-chevron-down collapse-icon me-2" id="icon-{{ $routeId }}"></i>
                                    <strong style="color: var(--primary-color);">{{ $routeName }}</strong>
                                    @if(isset($firstBus->route_description) && $firstBus->route_description)
                                    <small class="text-muted ms-2" title="{{ $firstBus->route_description }}">
                                        <i class="bi bi-info-circle"></i>
                                        {{ Str::limit($firstBus->route_description, 40) }}
                                    </small>
                                    @endif
                                </div>
                                <div class="route-summary">
                                    <span class="route-summary-item">
                                        <i class="bi bi-bus-front"></i>
                                        {{ $totalBusesInRoute }} Bus(es)
                                    </span>
                                    <span class="route-summary-item">
                                        <i class="bi bi-people"></i>
                                        {{ $totalCapacityInRoute }} Seats
                                    </span>
                                    <span class="route-summary-item">
                                        <i class="bi bi-geo-alt"></i>
                                        {{ $avgStopsInRoute }} Avg Stops
                                    </span>
                                </div>
                            </div>
                        </td>
                    </tr>
    
                    <!-- Bus Rows for this Route -->
                    @foreach($buses as $bus)
                    <tr class="route-bus-row route-{{ $routeId }} child-row" style="display: none;">
                        <td class="sticky-checkbox">
                            <input type="checkbox" class="route-checkbox select-checkbox" value="{{ $routeId}}">
                        </td>
                        <!-- Bus Details -->
                        <td class="sticky-main">
                            <div class="fw-bold" style="color: var(--primary-color);">{{ $bus->bus_number }}</div>
                            <div class="text-muted small">
                                <i class="bi bi-truck"></i> {{ $bus->vehicle_number }}
                            </div>
                            @if($bus->transport_reference_id)
                            <div class="text-muted small">
                                <i class="bi bi-qr-code"></i> {{ $bus->transport_reference_id }}
                            </div>
                            @endif
                        </td>
    
                        <!-- Route Info -->
                        <td>
                            <div>{{ $bus->route_name }}</div>
                            <div class="mt-1">
                                <span class="route-badge route-type-{{ $bus->route_type }}">
                                    @if($bus->route_type == 'morning')
                                    <i class="bi bi-sun"></i> Morning
                                    @elseif($bus->route_type == 'evening')
                                    <i class="bi bi-moon"></i> Evening
                                    @else
                                    <i class="bi bi-arrow-left-right"></i> Both
                                    @endif
                                </span>
                            </div>
                            @if($bus->total_stops > 0)
                            <div class="text-muted small">
                                <i class="bi bi-geo-alt"></i> {{ $bus->total_stops }} Stops
                            </div>
                            @endif
                        </td>
    
                        <!-- Driver Details -->
                        <td>
                            <div class="fw-bold">{{ $bus->driver_name }}</div>
                            <div class="text-muted small">
                                <i class="bi bi-telephone"></i> {{ $bus->driver_contact }}
                            </div>
                            @if(isset($bus->helpers_array) && count($bus->helpers_array) > 0)
                            <div class="mt-1">
                                @foreach($bus->helpers_array as $helper)
                                <span class="helper-chip">
                                    <i class="bi bi-person"></i> {{ $helper['name'] ?? 'Helper' }}
                                </span>
                                @endforeach
                            </div>
                            @endif
                        </td>
    
                        <!-- Schedule -->
                        <td>
                            @if(isset($bus->formatted_start_time) && $bus->formatted_start_time != 'Not set')
                            <div><i class="bi bi-clock" style="color: var(--primary-color);"></i> Start: {{ $bus->formatted_start_time }}</div>
                            @endif
                            @if(isset($bus->formatted_end_time) && $bus->formatted_end_time != 'Not set')
                            <div><i class="bi bi-clock" style="color: var(--primary-color);"></i> End: {{ $bus->formatted_end_time }}</div>
                            @endif
                            @if(!isset($bus->formatted_start_time) || $bus->formatted_start_time == 'Not set' &&
                            (!isset($bus->formatted_end_time) || $bus->formatted_end_time == 'Not set'))
                            <span class="text-muted">Not scheduled</span>
                            @endif
                        </td>
    
                        <!-- Capacity -->
                        <td>
                            @php
                            $occupied = $bus->occupied_seats ?? 0;
                            $available = $bus->available_seats ?? $bus->sitting_capacity;
                            @endphp
    
                            <div class="capacity-box">
                                <div class="small text-muted">
                                    <i class="bi bi-people"></i> Total: <span class="fw-medium text-dark">{{ $bus->sitting_capacity }}</span> |
                                    <span class="text-danger"><i class="bi bi-person-fill"></i> {{ $occupied }}</span> |
                                    <span class="text-success"><i class="bi bi-person-plus"></i> {{ $available }}</span>
                                </div>
    
                                <div class="mt-1">
                                    @if($available == 0)
                                    <span class="badge" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 4px 10px; border-radius: 20px;">Full</span>
                                    @elseif($available <= 5) 
                                    <span class="badge" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; padding: 4px 10px; border-radius: 20px;">Only {{ $available }} left</span>
                                    @elseif($occupied == 0)
                                    <span class="badge" style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; padding: 4px 10px; border-radius: 20px;">No assignments</span>
                                    @else
                                    <span class="badge" style="background: var(--success-gradient); color: white; padding: 4px 10px; border-radius: 20px;">Available</span>
                                    @endif
                                </div>
                            </div>
                        </td>
    
                        <!-- Fee -->
                        <td>
                            @if($bus->fees && $bus->fees->isNotEmpty())
                            @php $currentFee = $bus->fees->first(); @endphp
                            <div class="fw-bold text-success">₹{{ number_format($currentFee->monthly_fee, 2) }}</div>
                            <div class="text-muted small">
                                <i class="bi bi-calendar"></i> {{ $currentFee->academic_year }}
                            </div>
                            @if($currentFee->stop_fees)
                            <div class="text-muted small">
                                <i class="bi bi-geo-alt"></i> Stop-wise fees available
                            </div>
                            @endif
                            @else
                            <span class="text-muted">Not set</span>
                            @endif
                        </td>
    
                        <!-- Status -->
                        <td>
                            <span class="status-badge {{ $bus->status ? 'status-active' : 'status-inactive' }}">
                                {{ $bus->status ? 'Active' : 'Inactive' }}
                            </span>
                            @if(!$bus->status && isset($bus->route_status) && !$bus->route_status)
                            <div class="text-muted small">Route inactive</div>
                            @endif
                        </td>
    
                        <!-- Actions -->
                        <td class="text-center">
                            <div class="d-flex justify-content-center custom-gap">
                                <button class="action-btn action-btn-primary"
                                    onclick="viewFeeDetails('{{ $bus->id }}', '{{ $bus->bus_number }}')"
                                    title="View Fee Structure" data-bs-toggle="tooltip">
                                    <i class="bi bi-cash-stack"></i>
                                    <span class="d-none d-md-inline">Fee</span>
                                </button>
                                <button class="action-btn action-btn-secondary"
                                    onclick="viewBusStops('{{ $bus->id }}', '{{ $bus->bus_number }}')" title="View Stops"
                                    data-bs-toggle="tooltip">
                                    <i class="bi bi-geo-alt"></i>
                                    <span class="d-none d-md-inline">Stops</span>
                                </button>
                                <a href="{{ url('/transport/assign-fee') }}" class="action-btn action-btn-secondary"
                                    title="Assign Students" data-bs-toggle="tooltip">
                                    <i class="bi bi-person-plus"></i>
                                    <span class="d-none d-md-inline">Assign</span>
                                </a>
                            </div>
                            <div class="text-muted small mt-1">
                                <i class="bi bi-clock"></i>
                                {{ \Carbon\Carbon::parse($bus->created_at)->format('d M Y') }}
                            </div>
                        </td>
                    </tr>
                    @endforeach
    
                    <!-- Route Summary Row -->
                    <tr class="route-bus-row route-{{ $routeId }} child-row" style="display: none; background: linear-gradient(135deg, #f8fafc, #f1f5f9);">
                        <td colspan="9">
                            <div class="d-flex justify-content-between align-items-center p-2">
                                <div>
                                    <i class="bi bi-bar-chart-fill" style="color: var(--primary-color);"></i>
                                    <strong>Route Summary:</strong>
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    <span><i class="bi bi-bus-front" style="color: var(--primary-color);"></i> Total Buses: {{ $totalBusesInRoute }}</span>
                                    <span><i class="bi bi-people" style="color: var(--primary-color);"></i> Total Capacity: {{ $totalCapacityInRoute }}</span>
                                    <span><i class="bi bi-geo-alt" style="color: var(--primary-color);"></i> Avg Stops/Bus: {{ $avgStopsInRoute }}</span>
                                </div>
                            </div>
                        </td>
                    </tr>
    
                    <!-- Spacer between routes -->
                    @if(!$loop->last)
                    <tr class="route-spacer" style="display: none;">
                        <td colspan="9" style="padding: 10px; background: transparent;"></td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Floating Horizontal Scrollbar -->
        <div class="table-scroll-top" id="tableScrollTop">
            <div class="table-scroll-inner"></div>
        </div>
    </div>
    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="pagination-info">
            <i class="bi bi-layout-text-window"></i>
            Showing {{ $transportRoutes->firstItem() ?? 0 }} to {{ $transportRoutes->lastItem() ?? 0 }} of
            {{ $transportRoutes->total() }} buses
        </div>
        <div>
            {{ $transportRoutes->appends(request()->query())->links() }}
        </div>
    </div>

    @else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="bi bi-bus-front-fill"></i>
        </div>
        <h4>No Transport Details Found</h4>
        <p class="text-muted">
            @if(count(array_filter(request()->all())) > 0)
            No transport details match your current filters. Try adjusting your search criteria.
            @else
            No buses or routes have been created yet. Get started by adding your first route and bus.
            @endif
        </p>
        <div class="mt-3 d-flex gap-3 justify-content-center">
            <a href="{{ route('admin.transport.routes.create') }}" class="btn-filter btn-filter-primary">
                <i class="bi bi-plus-circle"></i> Add New Route
            </a>
            <a href="{{ route('admin.transport.add') }}" class="btn-filter btn-filter-primary">
                <i class="bi bi-plus-circle"></i> Add New Bus
            </a>
        </div>
    </div>
    @endif
</div>

<!-- Stops Modal -->
<div class="modal fade" id="stopsModal" tabindex="-1" aria-labelledby="stopsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stopsModalLabel">
                    <i class="bi bi-geo-alt"></i>
                    Bus Stops Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="stopsModalContent">
                <div class="text-center py-4">
                    <div class="spinner" style="width: 40px; height: 40px; border: 4px solid var(--border); border-top-color: var(--primary-color);"></div>
                    <p class="mt-2">Loading stops...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Students Modal -->
<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignModalLabel">
                    <i class="bi bi-person-plus"></i>
                    Assign Students to Bus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="assignModalContent">
                <div class="text-center py-4">
                    <div class="spinner" style="width: 40px; height: 40px; border: 4px solid var(--border); border-top-color: var(--primary-color);"></div>
                    <p class="mt-2">Loading student list...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fee Details Modal -->
<div class="modal fade" id="feeModal" tabindex="-1" aria-labelledby="feeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feeModalLabel">
                    <i class="bi bi-cash-stack"></i>
                    Transport Fee Structure
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="feeModalContent">
                <div class="text-center py-5">
                    <div class="spinner" style="width: 40px; height: 40px; border: 4px solid var(--border); border-top-color: var(--primary-color);"></div>
                    <p class="mt-2">Loading fee details...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Toggle route buses visibility
function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filterForm');
    let debounceTimer;

    document.querySelectorAll('.filter-input').forEach(input => {
        // Auto-submit on select / datalist selection
        input.addEventListener('change', () => {
            showLoader();
            form.submit();
        });

        // Auto-submit while typing (debounced)
        input.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                showLoader();
                form.submit();
            }, 600);
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const routeCheckboxes = document.querySelectorAll('.route-checkbox');
    const selectedCountElement = document.getElementById('selectedCount');

    // Select All
    selectAllCheckbox.addEventListener('change', function() {
        routeCheckboxes.forEach(cb => cb.checked = this.checked);
        updateSelectionCount();
    });

    // Individual checkbox change
    routeCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectionCount);
    });

    function updateSelectionCount() {
        const selectedCount = document.querySelectorAll('.route-checkbox:checked').length;
        selectedCountElement.textContent = `${selectedCount} route(s) selected`;
        selectAllCheckbox.checked = selectedCount === routeCheckboxes.length;
        selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < routeCheckboxes.length;
    }

    // Tooltip init
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].forEach(el => new bootstrap.Tooltip(el));

    // Expand first route by default
    const firstRouteHeader = document.querySelector('.route-group-header');
    if (firstRouteHeader) {
        const onclickAttr = firstRouteHeader.getAttribute('onclick');
        const match = onclickAttr ? onclickAttr.match(/'([^']+)'/) : null;
        const firstRouteId = match ? match[1] : null;
        if (firstRouteId) {
            setTimeout(() => {
                toggleRouteBuses(firstRouteId);
            }, 100);
        }
    }
});

// Clear selection
function clearSelection() {
    document.querySelectorAll('.route-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAll').checked = false;
    document.getElementById('selectAll').indeterminate = false;
    document.getElementById('selectedCount').textContent = '0 route(s) selected';
}

function bulkAction(action, format = null) {
    const selectedRoutes = Array.from(document.querySelectorAll('.route-checkbox:checked'))
        .map(checkbox => checkbox.value);
    if (selectedRoutes.length === 0) {
        alert('Please select at least one route.');
        return;
    }
    switch (action) {
        case 'download':
            if (!format) {
                alert("Please select a format");
                return;
            }
            const ids = selectedRoutes.join(',');
            const url = `/transport-data/download?ids=${ids}&type=${format}`;
            window.location.href = url;
            break;

        case 'bulk_delete':
            if (confirm(`Are you sure you want to delete ${selectedRoutes.length} route(s)? This action cannot be undone.`)) {
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<span class="loading-spinner"></span> Deleting...';
                btn.disabled = true;

                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                    clearSelection();
                    alert(`${selectedRoutes.length} routes deleted successfully`);
                }, 1500);
            }
            break;

        default:
            alert(`${action} action triggered for ${selectedRoutes.length} routes`);
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
    document.getElementById('filterForm').submit();
}

// Filter form submission with loading state
document.getElementById('filterForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('.btn-filter-primary');
    if (submitBtn) {
        const originalHTML = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Applying...';
        submitBtn.disabled = true;

        setTimeout(() => {
            submitBtn.innerHTML = originalHTML;
            submitBtn.disabled = false;
        }, 2000);
    }
});

function toggleRouteBuses(routeId) {
    const rows = document.querySelectorAll(`.route-${routeId}`);
    const icon = document.getElementById(`icon-${routeId}`);
    const isHidden = rows[0]?.style.display === 'none';

    rows.forEach(row => {
        row.style.display = isHidden ? 'table-row' : 'none';
    });

    if (icon) {
        icon.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(-90deg)';
    }
}

// View bus stops
function viewBusStops(busId, busNumber) {
    $('#stopsModalLabel').html(`<i class="bi bi-geo-alt"></i> Bus Stops - ${busNumber}`);
    $('#stopsModalContent').html(`
        <div class="text-center py-5">
            <div class="spinner" style="width: 40px; height: 40px; border: 4px solid var(--border); border-top-color: var(--primary-color);"></div>
            <p class="mt-2">Loading stops for Bus ${busNumber}...</p>
        </div>
    `);
    $('#stopsModal').modal('show');

    $.ajax({
        url: `/institute/admin/transport/${busId}/stops`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                $('#stopsModalContent').html(response.html);
            } else {
                $('#stopsModalContent').html(`
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                        ${response.message || 'Error loading stops'}
                    </div>
                    <div class="text-center mt-3">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                `);
            }
        },
        error: function(xhr) {
            let errorMsg = 'Error loading stops';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            $('#stopsModalContent').html(`
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    ${errorMsg}
                </div>
                <div class="text-center mt-3">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            `);
        }
    });
}

// View fee details
function viewFeeDetails(busId, busNumber) {
    $('#feeModalLabel').html(`<i class="bi bi-cash-stack"></i> Fee Structure - Bus ${busNumber}`);
    $('#feeModalContent').html(`
        <div class="text-center py-5">
            <div class="spinner" style="width: 40px; height: 40px; border: 4px solid var(--border); border-top-color: var(--primary-color);"></div>
            <p class="mt-2">Loading fee details for Bus ${busNumber}...</p>
        </div>
    `);
    $('#feeModal').modal('show');

    $.ajax({
        url: `/institute/admin/transport/${busId}/fee-details`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                let fee = response.fee;
                let feeBreakdown = fee.fee_breakdown ? JSON.parse(fee.fee_breakdown) : [];
                let stopFees = fee.stop_fees ? JSON.parse(fee.stop_fees) : [];

                let html = `
                    <div class="container-fluid p-3">
                        <!-- Summary Card -->
                        <div class="card mb-4" style="border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-left: 4px solid var(--primary-color);">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-2">Academic Year</h6>
                                        <p class="h5 mb-3">${fee.academic_year || 'N/A'}</p>
                                        
                                        <h6 class="text-muted mb-2">Monthly Fee</h6>
                                        <p class="h3" style="color: var(--primary-color);">₹${parseFloat(fee.monthly_fee).toFixed(2)}</p>
                                        <small class="text-muted">Annual: ₹${parseFloat(fee.annual_fee || fee.monthly_fee * 12).toFixed(2)}</small>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-2">Fee Duration</h6>
                                        <p class="mb-3"><span class="badge" style="background: var(--info-gradient); color: white; padding: 5px 12px; border-radius: 20px;">${fee.fee_duration || 'Monthly'}</span></p>
                                        
                                        <h6 class="text-muted mb-2">Status</h6>
                                        <p><span class="badge ${fee.status === 'active' ? 'status-active' : 'status-inactive'}" style="padding: 5px 12px;">${fee.status || 'Active'}</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                `;

                // Late Fee & Partial Fee Section
                if ((fee.late_fee_type && fee.late_fee_value) || (fee.partially_fee_type && fee.partially_fee_value)) {
                    html += `
                        <div class="row mb-4">
                            ${fee.late_fee_type && fee.late_fee_value ? `
                            <div class="col-md-6">
                                <div class="card" style="border-left: 4px solid #f59e0b; border-radius: 12px;">
                                    <div class="card-body">
                                        <h6 style="color: #f59e0b;"><i class="bi bi-exclamation-triangle me-2"></i> Late Fee</h6>
                                        <p class="mb-0">
                                            ${fee.late_fee_type === 'percentage' ? 
                                                `${fee.late_fee_value}% of monthly fee` : 
                                                `₹${parseFloat(fee.late_fee_value).toFixed(2)} fixed`}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            ` : ''}
                            
                            ${fee.partially_fee_type && fee.partially_fee_value ? `
                            <div class="col-md-6">
                                <div class="card" style="border-left: 4px solid #10b981; border-radius: 12px;">
                                    <div class="card-body">
                                        <h6 style="color: #10b981;"><i class="bi bi-hourglass-split me-2"></i> Partial Fee</h6>
                                        <p class="mb-0">
                                            ${fee.partially_fee_type === 'percentage' ? 
                                                `${fee.partially_fee_value}% of monthly fee` : 
                                                `₹${parseFloat(fee.partially_fee_value).toFixed(2)} fixed`}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            ` : ''}
                        </div>
                    `;
                }

                // Stop-wise Fee Breakdown
                if (feeBreakdown.length > 0 || stopFees.length > 0) {
                    html += `
                        <div class="card" style="border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="bi bi-geo-alt" style="color: var(--primary-color);"></i> Stop-wise Fee Breakdown</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Stop Name</th>
                                                <th class="text-end">Monthly Fee (₹)</th>
                                                <th class="text-end">Annual Fee (₹)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                    `;

                    let stopsToShow = feeBreakdown.length > 0 ? feeBreakdown : stopFees;

                    stopsToShow.forEach(stop => {
                        let stopName = stop.stop_name || 'Unknown Stop';
                        let monthlyFee = parseFloat(stop.monthly_fee || stop.fee || fee.monthly_fee);
                        let annualFee = monthlyFee * 12;

                        html += `
                            <tr>
                                <td>
                                    <i class="bi bi-geo-alt-fill me-2" style="color: var(--primary-color);"></i>
                                    ${stopName}
                                    ${stop.stop_id ? `<br><small class="text-muted">ID: ${stop.stop_id}</small>` : ''}
                                </td>
                                <td class="text-end fw-bold">₹${monthlyFee.toFixed(2)}</td>
                                <td class="text-end text-muted">₹${annualFee.toFixed(2)}</td>
                            </tr>
                        `;
                    });

                    html += `
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <th>Total (Average)</th>
                                                <th class="text-end">₹${parseFloat(fee.monthly_fee).toFixed(2)}</th>
                                                <th class="text-end">₹${parseFloat(fee.annual_fee || fee.monthly_fee * 12).toFixed(2)}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    html += `
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            No stop-wise fee breakdown available. Using flat monthly fee for all stops.
                        </div>
                    `;
                }

                // Additional Information
                html += `
                        <div class="mt-3 text-muted small">
                            <i class="bi bi-clock"></i> Created: ${new Date(fee.created_at).toLocaleString()}
                            ${fee.updated_at !== fee.created_at ? `<br><i class="bi bi-pencil"></i> Updated: ${new Date(fee.updated_at).toLocaleString()}` : ''}
                        </div>
                    </div>
                `;

                $('#feeModalContent').html(html);
            } else {
                $('#feeModalContent').html(`
                    <div class="alert alert-warning m-3">
                        <i class="bi bi-exclamation-triangle"></i>
                        ${response.message || 'No fee structure found for this bus'}
                    </div>
                    <div class="text-center mb-3">
                        <button class="btn btn-primary" onclick="window.location.href='{{ route("admin.transport.add") }}'">
                            <i class="bi bi-plus-circle"></i> Add Fee Structure
                        </button>
                    </div>
                `);
            }
        },
        error: function(xhr) {
            let errorMsg = 'Error loading fee details';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            } else if (xhr.status === 404) {
                errorMsg = 'Fee details not found';
            } else if (xhr.status === 403) {
                errorMsg = 'You do not have permission to view these details';
            }

            $('#feeModalContent').html(`
                <div class="alert alert-danger m-3">
                    <i class="bi bi-exclamation-triangle"></i>
                    ${errorMsg}
                </div>
                <div class="text-center mb-3">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            `);
        }
    });
}
</script>
@endsection