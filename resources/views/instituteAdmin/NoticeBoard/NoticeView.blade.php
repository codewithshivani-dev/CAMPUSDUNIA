@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Notice Board Management</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@php
    $user = Auth::user();
    $canManageNotices = $user->hasRole(['admin', 'superadmin']);
@endphp

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
        --purple-gradient: linear-gradient(135deg, #8b5cf6, #7c3aed);
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
        text-decoration: none;
    }

    /* Statistics Cards - Enhanced */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        border: none;
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
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        font-size: 24px;
    }

    .stat-icon-primary {
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
    }

    .stat-icon-success {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success-color);
    }

    .stat-icon-warning {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .stat-icon-secondary {
        background: rgba(100, 116, 139, 0.1);
        color: var(--gray);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--gray);
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
        justify-content: space-between;
        align-items: flex-end;
        gap: 15px;
    }

    .filter-grid {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        flex: 1;
    }

    .filter-group {
        position: relative;
        min-width: 250px;
        flex: 1;
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
        height: 45px;
        box-sizing: border-box;
        appearance: none;
        cursor: pointer;
    }

    .filter-group select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%234361ee' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 0 0 1 .753 1.659l-4.796 5.48a1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        padding-right: 40px;
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
        margin-bottom: 0;
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
        height: 45px;
        box-sizing: border-box;
        text-decoration: none;
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

    /* Filter Tabs - Enhanced */
    .filter-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border);
        flex-wrap: wrap;
    }

    .filter-tab {
        padding: 10px 20px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px solid var(--border);
        border-radius: 30px;
        cursor: pointer;
        font-weight: 600;
        color: #475569;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        text-decoration: none;
    }

    .filter-tab:hover {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.2);
    }

    .filter-tab.active {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
    }

    .filter-tab i {
        font-size: 1rem;
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

    .bulk-action-btn.publish {
        background: var(--success-gradient);
    }

    .bulk-action-btn.publish:hover {
        background: linear-gradient(135deg, #059669, #10b981);
    }

    .bulk-action-btn.archive {
        background: linear-gradient(135deg, #64748b, #475569);
    }

    .bulk-action-btn.archive:hover {
        background: linear-gradient(135deg, #475569, #334155);
    }

    .bulk-action-btn.download {
        background: var(--info-gradient);
    }

    .bulk-action-btn.download:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
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

    .status-published {
        background: var(--success-gradient);
    }

    .status-draft {
        background: var(--warning-gradient);
    }

    .status-archived {
        background: linear-gradient(135deg, #64748b, #475569);
    }

    /* Recipient Tag - Enhanced */
    .recipient-tag {
        padding: 6px 14px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 30px;
        font-size: 12px;
        color: var(--primary-color);
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid var(--border);
    }

    .recipient-tag i {
        color: var(--primary-color);
    }

    /* Action Buttons - Enhanced */
    .table-actions {
        display: flex;
        gap: 8px;
        justify-content: center;
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
        min-width: 80px;
        text-decoration: none;
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

    .action-btn-edit {
        background: var(--warning-gradient);
    }

    .action-btn-edit:hover {
        background: linear-gradient(135deg, #d97706, #b45309);
    }

    .action-btn-view {
        background: var(--info-gradient);
    }

    .action-btn-view:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
    }

    .action-btn-delete {
        background: var(--danger-gradient);
    }

    .action-btn-delete:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
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

    /* Alert Messages */
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

    /* Modal Styles - Enhanced */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        padding: 20px;
    }

    .modal-content {
        background: #fff;
        padding: 30px;
        border-radius: 16px;
        width: 95%;
        max-width: 700px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: fadeIn 0.4s ease-out;
        border: none;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border);
    }

    .modal-header h3 {
        font-size: 22px;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-header h3 i {
        color: var(--primary-color);
    }

    .modal-close-btn {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border: none;
        font-size: 20px;
        color: var(--gray);
        cursor: pointer;
        padding: 8px 16px;
        border-radius: 8px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close-btn:hover {
        background: var(--danger-gradient);
        color: white;
        transform: scale(1.05);
    }

    .modal-body {
        line-height: 1.6;
        color: var(--dark);
    }

    .modal-meta {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 14px;
        color: var(--gray);
        border-left: 4px solid var(--primary-color);
    }

    .modal-meta strong {
        color: var(--primary-color);
        font-weight: 600;
    }

    .modal-attachment {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 2px solid var(--border);
    }

    .attachment-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: white;
        text-decoration: none;
        font-weight: 600;
        padding: 10px 20px;
        background: var(--primary-gradient);
        border-radius: 8px;
        transition: all 0.3s;
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
    }

    .attachment-link:hover {
        background: linear-gradient(135deg, #3a0ca3, #4361ee);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
        color: white;
        text-decoration: none;
    }

    .attachment-link i {
        font-size: 1.1rem;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .filter-form {
            flex-direction: column;
            align-items: stretch;
            gap: 15px;
        }

        .filter-grid {
            width: 100%;
        }

        .filter-group {
            min-width: calc(50% - 8px);
        }

        .filter-actions {
            width: 100%;
            justify-content: flex-end;
        }

        .stats-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }

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

        .stats-container {
            grid-template-columns: 1fr;
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

        .filter-tabs {
            flex-direction: column;
        }

        .filter-tab {
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
    }
    /* Pagination Container */
    .pagination {
        display: flex;
        list-style: none;
        gap: 6px;
        padding: 0;
    }

    .page-item .page-link {
        padding: 6px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        text-decoration: none;
        color: #333;
        font-size: 13px;
    }

    .page-item.active .page-link {
        background: #2563eb;
        color: #fff;
        border-color: #2563eb;
    }

    .page-item.disabled .page-link {
        background: #f5f5f5;
        color: #aaa;
    }

    /* Pagination Wrapper */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 16px;
        flex-wrap: wrap;
        gap: 10px;
    }

    /* Showing Text */
    .pagination-info {
        font-size: 13px;
        color: #6b7280;
    }
</style>

<div id="pageLoader">
    <div class="spinner"></div>
</div>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-megaphone-fill"></i>
            View Notice
        </h1>
    
        <div class="d-flex align-items-center gap-2">
            {{-- Back button for users who cannot manage notices --}}
            @unless($canManageNotices)
                <a href="{{ url()->previous() }}" class="add-btn">
                    <i class="bi bi-arrow-left"></i>
                    Back
                </a>
            @endunless
    
            {{-- Create Notice Button --}}
            @if($canManageNotices)
                <a href="{{ route('notice-board.create') }}" class="add-btn">
                    <i class="bi bi-plus-circle"></i>
                    Create New Notice
                </a>
            @endif
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

    {{-- Statistics Cards --}}
    @if($canManageNotices)
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon stat-icon-primary">
                    <i class="bi bi-file-text-fill"></i>
                </div>
                <div class="stat-value">{{ $notices->count() }}</div>
                <div class="stat-label">Total Notices</div>
            </div>
    
            <div class="stat-card">
                <div class="stat-icon stat-icon-success">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-value">{{ $notices->where('status', 'published')->count() }}</div>
                <div class="stat-label">Published</div>
            </div>
    
            <div class="stat-card">
                <div class="stat-icon stat-icon-warning">
                    <i class="bi bi-save-fill"></i>
                </div>
                <div class="stat-value">{{ $notices->where('status', 'draft')->count() }}</div>
                <div class="stat-label">Drafts</div>
            </div>
    
            <div class="stat-card">
                <div class="stat-icon stat-icon-secondary">
                    <i class="bi bi-archive-fill"></i>
                </div>
                <div class="stat-value">{{ $notices->where('status', 'archived')->count() }}</div>
                <div class="stat-label">Archived</div>
            </div>
        </div>
    @endif

    {{-- Filters --}}
    <div class="filter-container">
        <form method="GET" class="filter-form" id="filterForm">
            <div class="filter-grid">
                {{-- Search Title --}}
                <div class="filter-group">
                    <i class="bi bi-search"></i>
                    <input type="search" name="title" class="filter-input auto-submit"
                        placeholder="Search notice title..." list="titleList" value="{{ request('title') }}">
                    <datalist id="titleList">
                        @foreach($allTitles as $title)
                        <option value="{{ $title }}">
                        @endforeach
                    </datalist>
                </div>

                {{-- Recipient Filter --}}
                @if($canManageNotices)
                    <div class="filter-group">
                        <i class="bi bi-people-fill"></i>
                        <input type="search" name="recipient_type" class="filter-input auto-submit"
                            placeholder="Select recipient" list="recipientList" value="{{ request('recipient_type') }}">
                        <datalist id="recipientList">
                            <option value="whole">Whole</option>
                            <option value="academic">Academic</option>
                            <option value="nonacademic">Non-Academic</option>
                        </datalist>
                    </div>
                @endif

                {{-- Date Filter --}}
                <div class="filter-group">
                    <i class="bi bi-calendar"></i>
                    <input type="search" name="date" class="filter-input auto-submit" placeholder="Filter by date"
                        list="dateList" value="{{ request('date') }}">
                    <datalist id="dateList">
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
                    </datalist>
                </div>
            </div>

            <div class="filter-actions">
                <a href="{{ route('notice-board.view') }}" class="btn-filter btn-filter-secondary">
                    <i class="bi bi-arrow-clockwise"></i> Reset Filters
                </a>
            </div>

            <!-- Hidden status input for filter tabs -->
            <input type="hidden" name="status" id="statusInput" value="{{ request('status') }}">
        </form>
    </div>

    <!-- Filter Tabs -->
    @if($canManageNotices)
        <div class="filter-tabs">
            <a href="{{ route('notice-board.view') }}" class="filter-tab {{ request('status') == null ? 'active' : '' }}">
                <i class="bi bi-list-ul"></i> All Notices
            </a>
            <a href="{{ route('notice-board.view', ['status' => 'published']) }}"
                class="filter-tab {{ request('status') == 'published' ? 'active' : '' }}">
                <i class="bi bi-send-check-fill"></i> Published
            </a>
            <a href="{{ route('notice-board.view', ['status' => 'draft']) }}"
                class="filter-tab {{ request('status') == 'draft' ? 'active' : '' }}">
                <i class="bi bi-save-fill"></i> Drafts
            </a>
            <a href="{{ route('notice-board.view', ['status' => 'archived']) }}"
                class="filter-tab {{ request('status') == 'archived' ? 'active' : '' }}">
                <i class="bi bi-archive-fill"></i> Archived
            </a>
        </div>
    @endif

    {{-- Bulk Actions Container --}}
    @if($canManageNotices)
    <div class="bulk-actions-container" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 notice(s) selected</div>
        <div class="d-flex flex-wrap">
            <button class="bulk-action-btn publish d-none" onclick="bulkAction('publish')">
                <i class="bi bi-send-check"></i>
                Bulk Publish
            </button>
            <button class="bulk-action-btn archive d-none" onclick="bulkAction('archive')">
                <i class="bi bi-archive"></i>
                Bulk Archive
            </button>
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
            <button class="bulk-action-btn delete" onclick="bulkAction('delete')">
                <i class="bi bi-trash-fill"></i>
                Bulk Delete
            </button>
            <button class="bulk-action-btn clear" onclick="clearSelection()">
                <i class="bi bi-x-lg-fill"></i>
                Clear Selection
            </button>
        </div>
    </div>
    @endif

    <div>
        {{-- Notices Table --}}
        <div class="table-responsive custom-table-wrapper" id="tableWrapper">
            <table class="erp-table" id="noticesTable">
                <thead>
                     <tr>
                        @if($canManageNotices)
                            <th class="sticky-checkbox" width="40">
                                <input type="checkbox" id="selectAll" class="select-checkbox">
                            </th>
                        @endif
                        <th class="{{ $canManageNotices ? 'sticky-main' : 'sticky-main-2' }} sortable" onclick="sortTable('title')">
                            Notice Title
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('title')">
                            Description
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        @if($canManageNotices)
                            <th class="sortable" onclick="sortTable('recipient_type')">
                                Recipients
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
                        @endif
                        <th class="sortable" onclick="sortTable('created_at')">
                            Created Date
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="text-center">Actions</th>
                     </tr>
                </thead>
                <tbody id="noticesTableBody">
                    @forelse($notices as $notice)
                    <tr data-id="{{ $notice->id }}" data-title="{{ $notice->title }}" data-status="{{ $notice->status }}"
                        data-recipient="{{ $notice->recipient_type }}"
                        data-date="{{ $notice->created_at->format('Y-m-d') }}">
                        @if($canManageNotices)
                            <td class="sticky-checkbox">
                                <input type="checkbox" class="notice-checkbox select-checkbox" value="{{ $notice->id }}">
                            </td>
                        @endif
                        <td class="{{ $canManageNotices ? 'sticky-main' : 'sticky-main-2' }}">
                            <div class="fw-medium" style="color: var(--primary-color); margin-bottom: 5px;">
                                {{ $notice->title }}
                            </div>
                            @if($notice->attachment)
                            <div style="font-size: 12px; color: var(--gray);">
                                <i class="bi bi-paperclip"></i> Has attachment
                            </div>
                            @endif
                        </td>
                        <td>
                            <span class="description">
                                <!--<i class="bi bi-people-fill"></i>-->
                                {{ ucfirst(strip_tags($notice->content)) }}
                            </span>
                        </td>
                        @if($canManageNotices)
                            <td>
                                <span class="recipient-tag">
                                    <i class="bi bi-people-fill"></i>
                                    {{ ucfirst($notice->recipient_type) }}
                                </span>
                            </td>
                            <td>
                                @php
                                $statusClass = 'status-' . $notice->status;
                                $statusIcon = $notice->status === 'published' ? 'bi-send-check-fill' :
                                ($notice->status === 'draft' ? 'bi-save-fill' : 'bi-archive-fill');
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    <i class="bi {{ $statusIcon }} me-1"></i>
                                    {{ ucfirst($notice->status) }}
                                </span>
                            </td>
                        @endif
                        <td>
                            <div class="fw-medium">{{ $notice->created_at->format('M d, Y') }}</div>
                            <div style="font-size: 12px; color: var(--gray);">
                                <i class="bi bi-clock"></i> {{ $notice->created_at->format('h:i A') }}
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="table-actions">
                                @if($canManageNotices)
                                    <a href="{{ route('notice.board.edit', $notice->id) }}" class="action-btn action-btn-edit"
                                        title="Edit">
                                        <i class="bi bi-pencil"></i>
                                        <span class="small">Edit</span>
                                    </a>
                                @endif
                                <button class="action-btn action-btn-view" 
                                    data-title="{{ $notice->title }}"
                                    data-content="{!! htmlspecialchars($notice->content) !!}"
                                    data-date="{{ $notice->created_at->format('M d, Y h:i A') }}"
                                    data-recipient="{{ ucfirst($notice->recipient_type) }}"
                                    data-attachment="{{ $notice->attachment ? asset('storage/'.$notice->attachment) : '' }}"
                                    onclick="showNoticeModal(this)" 
                                    title="View">
                                    <i class="bi bi-eye"></i>
                                    <span class="small">View</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $canManageNotices ? 6 : 5 }}">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-file-text-fill"></i>
                                </div>
                                <h4>No Notices Found</h4>
                                <p>Your notices will appear here.</p>
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
        
        <!-- pagination -->
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Showing {{ $notices->firstItem() }} to {{ $notices->lastItem() }}
                of {{ $notices->total() }} notices
            </div>
    
            @if ($notices->hasPages())
            <nav>
                <ul class="pagination mb-0">
                    {{-- Previous Page --}}
                    @if ($notices->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">Prev</span></li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $notices->previousPageUrl() }}">Prev</a>
                        </li>
                    @endif
    
                    {{-- Page Numbers --}}
                    @for ($i = 1; $i <= $notices->lastPage(); $i++)
                        <li class="page-item {{ $notices->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $notices->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
    
                    {{-- Next Page --}}
                    @if ($notices->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $notices->nextPageUrl() }}">Next</a>
                        </li>
                    @else
                        <li class="page-item disabled"><span class="page-link">Next</span></li>
                    @endif
                </ul>
            </nav>
            @endif
        </div>
    </div>
</div>

<!-- View Notice Modal - FIXED -->
<div id="viewNoticeModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle"></h3>
            <button type="button" class="modal-close-btn" onclick="closeNoticeModal()" aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="modal-meta" id="modalMeta"></div>
            <div id="modalContent"></div>
            <div class="modal-attachment" id="modalAttachment"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function showLoader() {
            const loader = document.getElementById('pageLoader');
            if (loader) loader.style.display = 'flex';
        }
    
        /* =====================================================
           AUTO FILTERS (with debounce)
        ====================================================== */
        let filterTimeout;
    
        document.querySelectorAll('.auto-submit').forEach(input => {
            // On dropdown change
            input.addEventListener('change', function() {
                showLoader();
                this.form.submit();
            });
    
            // On typing (search fields)
            input.addEventListener('input', function() {
                clearTimeout(filterTimeout);
    
                filterTimeout = setTimeout(() => {
                    if (this.value === '' || this.type === 'search') {
                        showLoader();
                        this.form.submit();
                    }
                }, 500);
            });
        });
    
        /* =====================================================
           CHECKBOX + BULK ACTIONS
        ====================================================== */
    
        const selectAllCheckbox = document.getElementById('selectAll');
        const selectedCountElement = document.getElementById('selectedCount');
        const bulkContainer = document.getElementById('bulkActionsContainer');
    
        function getNoticeCheckboxes() {
            return document.querySelectorAll('.notice-checkbox');
        }
    
        function getCheckedCheckboxes() {
            return document.querySelectorAll('.notice-checkbox:checked');
        }
    
        function updateSelectionUI() {
            const total = getNoticeCheckboxes().length;
            const selected = getCheckedCheckboxes().length;
    
            if (selectedCountElement) {
                selectedCountElement.textContent = `${selected} notice(s) selected`;
            }
    
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = selected === total && total > 0;
                selectAllCheckbox.indeterminate = selected > 0 && selected < total;
            }
        }
    
        /* ------------------------------
           SELECT ALL
        ------------------------------ */
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                getNoticeCheckboxes().forEach(cb => {
                    cb.checked = this.checked;
                });
                updateSelectionUI();
            });
        }
    
        /* ------------------------------
           INDIVIDUAL CHECKBOXES
        ------------------------------ */
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('notice-checkbox')) {
                updateSelectionUI();
            }
        });
    
        /* ------------------------------
           CLEAR SELECTION (GLOBAL)
        ------------------------------ */
        window.clearSelection = function() {
            getNoticeCheckboxes().forEach(cb => cb.checked = false);
    
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
    
            updateSelectionUI();
        };
    
        window.bulkAction = function(action, format=null) {
            const selectedNotices = Array.from(document.querySelectorAll('.notice-checkbox:checked'))
                .map(checkbox => checkbox.value);
    
            if (selectedNotices.length === 0) {
                alert('Please select at least one notice.');
                return;
            }
    
            switch (action) {
                case 'publish':
                    if (confirm(`Publish ${selectedNotices.length} notice(s)?`)) {
                        const btn = event.target.closest('button');
                        const originalHTML = btn.innerHTML;
                        btn.innerHTML = '<span class="loading-spinner"></span> Publishing...';
                        btn.disabled = true;
    
                        setTimeout(() => {
                            btn.innerHTML = originalHTML;
                            btn.disabled = false;
                            clearSelection();
                            alert(`${selectedNotices.length} notices published successfully`);
                        }, 1500);
                    }
                    break;
    
                case 'archive':
                    if (confirm(`Archive ${selectedNotices.length} notice(s)?`)) {
                        const btn = event.target.closest('button');
                        const originalHTML = btn.innerHTML;
                        btn.innerHTML = '<span class="loading-spinner"></span> Archiving...';
                        btn.disabled = true;
    
                        setTimeout(() => {
                            btn.innerHTML = originalHTML;
                            btn.disabled = false;
                            clearSelection();
                            alert(`${selectedNotices.length} notices archived`);
                        }, 1500);
                    }
                    break;
    
                case 'download':
                    if (!format) {
                        alert("Please select a format");
                        return;
                    }
                    const ids = selectedNotices.join(',');
                    const url = `/notices/download?ids=${ids}&type=${format}`;
                    window.location.href = url;
                    break;
    
                case 'delete':
                    if (confirm(`Delete ${selectedNotices.length} notice(s)? This action cannot be undone.`)) {
                        const btn = event.target.closest('button');
                        const originalHTML = btn.innerHTML;
                        btn.innerHTML = '<span class="loading-spinner"></span> Deleting...';
                        btn.disabled = true;
    
                        setTimeout(() => {
                            btn.innerHTML = originalHTML;
                            btn.disabled = false;
                            clearSelection();
                            alert(`${selectedNotices.length} notices deleted successfully`);
                        }, 1500);
                    }
                    break;
            }
        }
    
        // Sorting Functionality
        window.sortTable = function(column) {
            // This would need to be implemented with actual sorting logic
            // For now, just show a message
            console.log('Sorting by:', column);
        };
    
        // Global functions for modals
        window.showNoticeModal = function(button) {
            const modal = document.getElementById('viewNoticeModal');
            document.getElementById('modalTitle').innerHTML = `<i class="bi bi-file-text-fill me-2"></i>${button.dataset.title}`;
    
            // Set content
            document.getElementById('modalContent').innerHTML = button.dataset.content;
    
            // Set metadata
            const meta = document.getElementById('modalMeta');
            meta.innerHTML = `
                <div><i class="bi bi-people-fill me-2" style="color: var(--primary-color);"></i><strong>Recipient:</strong> ${button.dataset.recipient}</div>
                <div><i class="bi bi-calendar-fill me-2" style="color: var(--primary-color);"></i><strong>Date:</strong> ${button.dataset.date}</div>
            `;
    
            // Set attachment if exists
            const attachmentDiv = document.getElementById('modalAttachment');
            if (button.dataset.attachment && button.dataset.attachment !== '') {
                attachmentDiv.innerHTML = `
                    <a href="${button.dataset.attachment}" target="_blank" class="attachment-link">
                        <i class="bi bi-paperclip"></i> View Attachment
                    </a>
                `;
            } else {
                attachmentDiv.innerHTML = '';
            }
    
            modal.style.display = 'flex';
        };
    
        window.closeNoticeModal = function() {
            document.getElementById('viewNoticeModal').style.display = 'none';
        };
    
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('viewNoticeModal');
            if (event.target === modal) {
                closeNoticeModal();
            }
        });
    
        // Add ESC key support to close modal
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('viewNoticeModal');
                if (modal && modal.style.display === 'flex') {
                    closeNoticeModal();
                }
            }
        });
    });
</script>
@endsection