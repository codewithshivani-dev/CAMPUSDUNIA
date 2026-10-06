@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Subjects Management</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons (keeping for compatibility) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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

    .add-btn {
        padding: 12px 24px;
        background: var(--primary-gradient);
        border: none;
        color: #fff!important;
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
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin: 0;
    }

    .filter-grid {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        flex: 1;
    }

    .filter-group {
        position: relative;
        /* flex: 1; */
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
        /* width: 100%; */
        padding: 12px 12px 12px 40px;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        background: #fff;
        transition: all 0.3s;
        height: 45px;
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
        border: 1px solid var(--border);
    }

    .btn-filter-secondary:hover {
        background: var(--primary-gradient);
        color: white !important;
        border-color: transparent;
    }

    .table-responsive{
        overflow-x: hidden;
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

    .status-active {
        background: var(--success-gradient);
    }

    .status-inactive {
        background: var(--danger-gradient);
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

    .bulk-action-btn.notice {
        background: var(--warning-gradient);
    }

    .bulk-action-btn.notice:hover {
        background: linear-gradient(135deg, #d97706, #b45309);
    }

    .bulk-action-btn.exit {
        background: var(--danger-gradient);
    }

    .bulk-action-btn.exit:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
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

    /* Action Buttons - Enhanced */
    .table-actions {
        display: flex;
        gap: 8px;
        justify-content: center;
        flex-wrap: wrap;
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

    /* Sub-subjects count */
    .sub-subjects-count {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: var(--primary-color);
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        border: 1px solid var(--primary-color);
        display: inline-block;
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

    /* View Panel Styles */
    #viewSubjectsPanel {
        position: fixed;
        top: 0;
        right: -100%;
        width: 100%;
        max-width: 800px;
        height: 100vh;
        background: #fff;
        box-shadow: -5px 0 25px rgba(0, 0, 0, 0.15);
        z-index: 300;
        overflow-y: auto;
        transition: right 0.4s ease;
    }

    #viewSubjectsPanel.open {
        right: 0;
    }

    .view-panel-header {
        padding: 18px 20px;
        font-size: 18px;
        font-weight: 700;
        background: var(--primary-gradient);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: none;
    }

    .view-panel-header i {
        font-size: 1.3rem;
    }

    .view-panel-header .close-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .view-panel-header .close-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .view-panel-content {
        padding: 20px;
    }

    .view-section {
        margin-bottom: 20px;
        border: 2px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }

    .view-section-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 12px 15px;
        font-weight: 700;
        border-bottom: 2px solid var(--border);
        color: var(--primary-color);
    }

    .view-section-body {
        padding: 15px;
    }

    .view-row {
        display: flex;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
    }

    .view-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .view-label {
        font-weight: 600;
        width: 40%;
        color: var(--gray);
    }

    .view-value {
        width: 60%;
        color: var(--dark);
        font-weight: 500;
    }

    .view-value .subject-id {
        color: var(--primary-color);
        font-weight: 600;
        font-family: monospace;
        background: var(--primary-light);
        padding: 2px 8px;
        border-radius: 4px;
    }

    .badge.bg-success {
        background: var(--success-gradient) !important;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 500;
    }

    .badge.bg-secondary {
        background: linear-gradient(135deg, #64748b, #475569) !important;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 500;
    }

    /* Alert styling */
    .alert {
        border-radius: 12px;
        border: none;
        padding: 15px 20px;
        margin-bottom: 20px;
        animation: slideIn 0.3s ease;
        border-left: 4px solid transparent;
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
        color: #166534;
        border-left-color: var(--success-color);
    }

    .alert-danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        border-left-color: var(--danger-color);
    }

    .alert .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* Pagination styling - Enhanced */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding: 15px 20px;
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        flex-wrap: wrap;
        gap: 15px;
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

    .pagination {
        display: flex;
        list-style: none;
        gap: 5px;
        padding: 0;
        margin: 0;
    }

    .page-item .page-link {
        padding: 8px 14px;
        border: 2px solid var(--border);
        border-radius: 8px;
        text-decoration: none;
        color: #475569;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.3s;
        display: inline-block;
        background: white;
    }

    .page-item .page-link:hover {
        background: var(--primary-gradient);
        color: white !important;
        border-color: transparent;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
    }

    .page-item.active .page-link {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
    }

    .page-item.disabled .page-link {
        background: #f1f5f9;
        color: #94a3b8;
        border-color: var(--border);
        opacity: 0.6;
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

    /* Banner */
    .banner {
        height: 200px;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
        background: var(--primary-gradient);
        display: flex;
        align-items: center;
        animation: fadeIn 0.8s ease;
        margin-bottom: 30px;
    }

    .banner img.bg {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.8);
    }

    .banner .meta {
        position: absolute;
        left: 28px;
        bottom: 22px;
        background: rgba(255, 255, 255, 0.95);
        padding: 14px 20px;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .banner .meta h3 {
        color: var(--primary-color);
        font-weight: 700;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            /* flex-direction: column; */
            align-items: flex-start;
            gap: 16px;
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
        }

        /* .add-btn {
            width: 100%;
            justify-content: center;
        } */

        .filter-form {
            flex-direction: column;
            align-items: stretch;
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

        .table-actions {
            flex-direction: column;
            gap: 4px;
        }

        .action-btn {
            width: 100%;
        }

        .view-row {
            flex-direction: column;
        }

        .view-label,
        .view-value {
            width: 100%;
        }

        .view-label {
            margin-bottom: 5px;
        }

        .pagination-wrapper {
            flex-direction: column;
            align-items: flex-start;
        }

        .pagination {
            flex-wrap: wrap;
        }
    }
</style>

@php
$courseLabel = (isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type === 'School')
? 'Class'
: 'Course';
@endphp

<!-- Header with institute name -->
<!-- <div class="banner mb-4">
    @if(!empty($bannerPath))
    <img src="{{ asset('/image/'.$fincapMerchants->documents->first()->institute_image_path) }}"
        alt="institute image" class="bg">
    @else
    <img src="{{ asset('/image/'.$fincapMerchants->documents->first()->institute_image_path) }}"
        alt="institute image" class="bg">
    @endif
    <div class="meta">
        <h3 style="margin:0;">
            {{ $fincapMerchants->name ?? $fincapMerchants->fincap_merchant_name ?? 'Institute Name' }}</h3>
        <p style="margin:0;color:#444;">
            {{ $fincapMerchants->state ?? $fincapMerchants->fincap_merchant_state ?? '' }},
            {{ $fincapMerchants->city ?? $fincapMerchants->fincap_merchant_city ?? '' }}</p>
    </div>
</div> -->

<div id="pageLoader">
    <div class="spinner"></div>
</div>

<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-journal-bookmark-fill"></i>
            Subjects Management
        </h1>
        <a href="{{ route('subject.add') }}" class="add-btn">
            <i class="bi bi-plus-circle"></i>
            Add Subject
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
        <form method="GET" class="filter-form" id="filterForm">
            <div class="filter-grid">
                {{-- Department --}}
                <div class="filter-group">
                    <i class="bi bi-diagram-3"></i>
                    <input list="departmentList" id="departmentName" class="filter-input"
                        value="{{ request('department_name') }}" placeholder="Department">
                    <input type="hidden" name="department_id" id="departmentId" value="{{ request('department_id') }}">
                    <datalist id="departmentList">
                        @foreach($departments as $dept)
                        <option value="{{ $dept->department }}" data-id="{{ $dept->department_id }}"></option>
                        @endforeach
                    </datalist>
                </div>

                {{-- Subject --}}
                <div class="filter-group">
                    <i class="bi bi-journal-text"></i>
                    <input list="subjectList" name="subject_name" class="filter-input"
                        value="{{ request('subject_name') }}" placeholder="Subject Name">
                    <datalist id="subjectList">
                        @foreach($subjectsList as $sub)
                        <option value="{{ $sub->subject_name }}">
                        @endforeach
                    </datalist>
                </div>

                {{-- Course Type --}}
                <div class="filter-group">
                    <i class="bi bi-book"></i>
                    <input list="courseTypeList" name="course_type" class="filter-input"
                        value="{{ request('course_type') }}" placeholder="{{ $courseLabel }} Type">
                    <datalist id="courseTypeList">
                        @foreach($courseTypes as $type)
                        <option value="{{ $type }}">
                        @endforeach
                    </datalist>
                </div>

                {{-- Sub Type --}}
                <div class="filter-group">
                    <i class="bi bi-bookmark"></i>
                    <input list="subTypeList" name="sub_type" class="filter-input" value="{{ request('sub_type') }}"
                        placeholder="{{ $courseLabel }} Sub Type">
                    <datalist id="subTypeList">
                        @foreach($subTypes as $type)
                        <option value="{{ $type }}">
                        @endforeach
                    </datalist>
                </div>

                {{-- Semester --}}
                <div class="filter-group">
                    <i class="bi bi-calendar"></i>
                    <input list="semesterList" name="semester_id" class="filter-input"
                        value="{{ request('semester_id') }}" placeholder="Semester/Term">
                    <datalist id="semesterList">
                        @foreach($sem as $s)
                        <option value="{{ $s->semester_id }}">
                        @endforeach
                    </datalist>
                </div>

                {{-- Academic Year --}}
                <div class="filter-group d-none">
                    <i class="bi bi-calendar-year"></i>
                    <input list="academicYearList" name="academic_year" class="filter-input"
                        value="{{ request('academic_year') }}" placeholder="Academic Year">
                    <datalist id="academicYearList">
                        @foreach($years as $year)
                        <option value="{{ $year }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="filter-actions">
                    <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                        Reset Filters
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Bulk Actions Container --}}
    <div class="bulk-actions-container d-none" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 subjects selected</div>
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
        {{-- Subjects Table --}}
        <div class="table-responsive custom-table-wrapper" id="tableWrapper">
            <table class="erp-table">
                <thead>
                    <tr>
                        <th width="40" class="d-none">
                            <input type="checkbox" id="selectAll" class="select-checkbox">
                        </th>
                        <th class="sticky-checkbox">#</th>
                        <th class="sticky-main sortable" onclick="sortTable('category')">
                            Category
                            </br>
                            Department
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <!--<th class="sortable" onclick="sortTable('department')">-->
                        <!--    <div class="sort-icons">-->
                        <!--        <i class="sort-icon bi bi-caret-up-fill"></i>-->
                        <!--        <i class="sort-icon bi bi-caret-down-fill"></i>-->
                        <!--    </div>-->
                        <!--</th>-->
                        <th class="sortable" onclick="sortTable('course_type')">
                            {{ $courseLabel }} Type
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('course_sub_type')">
                            {{ $courseLabel }} Sub Type
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('semester')">
                            Semester/Term
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('subject_name')">
                            Subject Name
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('sub_subjects')">
                            Sub Subjects
                            <div class="sort-icons">
                                <i class="sort-icon bi bi-caret-up-fill"></i>
                                <i class="sort-icon bi bi-caret-down-fill"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('assigned_date')">
                            Assigned Date
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
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjects as $index => $sub)
                    <tr>
                        <td class="d-none">
                            <input type="checkbox" class="subject-checkbox select-checkbox" value="{{ $sub->id }}">
                        </td>
                        <td class="sticky-checkbox"><span style="color: var(--primary-color); font-weight: 600;">{{ $index+1 }}</span></td>
                        <td class="sticky-main">{{ $sub->category_name ?? 'N/A' }}
                            </br>
                            {{ $sub->department ?? 'N/A' }}
                        </td>
                        <td>{{ $sub->course_type ?? 'N/A' }}</td>
                        <td>{{ $sub->sub_type ?? 'N/A' }}</td>
                        <td>
                            @if($sub->semester_id === 'all_semesters')
                            <span>All Semesters/Terms</span>
                            @else
                            <span>{{ $sub->semester_id }}</span>
                            @endif
                        </td>
                        <td>{{ $sub->subject_name }}</td>
                        <td>
                            @php
                            $subSubjectsCount = \App\Models\SubSubject::where('subject_id', $sub->subject_id)->count();
                            @endphp
    
                            @if($subSubjectsCount > 0)
                            <span class="sub-subjects-count">{{ $subSubjectsCount }} Sub Subject(s)</span>
                            @else
                            <span class="text-muted">None</span>
                            @endif
                        </td>
                        <td>{{ date('d-m-Y', strtotime($sub->assigned_date)) }}</td>
                        <td>
                            @if($sub->status == 'Active')
                            <span class="status-badge status-active">Active</span>
                            @else
                            <span class="status-badge status-inactive">Inactive</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="table-actions">
                                <div>
                                    <a href="{{ url('/subject-coursewisebyid/' . $sub->id) }}"
                                        class="action-btn action-btn-view" title="View Details">
                                        <i class="fa-regular fa-eye"></i>
                                        <span class="small">View</span>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if($subjects->count() == 0)
                    <tr>
                        <td colspan="13">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-journal-bookmark-fill"></i>
                                </div>
                                <h4>No subjects found</h4>
                                <p>Try adjusting your filters or add a new subject</p>
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
    
        <div class="pagination-wrapper">
            <div class="pagination-info">
                <i class="bi bi-layout-text-window"></i>
                Showing {{ $subjects->firstItem() }} to {{ $subjects->lastItem() }}
                of {{ $subjects->total() }} subjects
            </div>
    
            @if ($subjects->hasPages())
            <nav>
                <ul class="pagination mb-0">
                    {{-- Previous Page --}}
                    @if ($subjects->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">Prev</span></li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $subjects->previousPageUrl() }}">Prev</a>
                    </li>
                    @endif
    
                    {{-- Page Numbers --}}
                    @for ($i = 1; $i <= $subjects->lastPage(); $i++)
                        <li class="page-item {{ $subjects->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $subjects->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
    
                    {{-- Next Page --}}
                    @if ($subjects->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $subjects->nextPageUrl() }}">Next</a>
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

<!-- View Subject Panel -->
<div id="viewSubjectsPanel">
    <div class="view-panel-header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-journal-text"></i>
            Subject Details
        </div>
        <button class="close-btn" onclick="closeViewPanel()">×</button>
    </div>
    <div class="view-panel-content" id="subjectDetails">
        <!-- Content will be loaded dynamically -->
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--primary-gradient); color: white;">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: brightness(0) invert(1);"></button>
            </div>
            <div class="modal-body">
                <p style="color: var(--dark);">Are you sure you want to delete this subject? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background: linear-gradient(135deg, #64748b, #475569); border: none; padding: 8px 20px; border-radius: 8px; color: white;">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" style="background: var(--danger-gradient); border: none; padding: 8px 20px; border-radius: 8px;">Delete</button>
            </div>
        </div>
    </div>
</div>



<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}

document.addEventListener("DOMContentLoaded", function() {

    const form = document.getElementById("filterForm");
    const inputs = form.querySelectorAll(".filter-input");

    let debounceTimer;

    inputs.forEach(input => {

        // Trigger when selecting from datalist
        input.addEventListener("change", function() {
            showLoader();
            autoSubmit();
        });

        // Trigger while typing (with debounce)
        input.addEventListener("keyup", function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                showLoader();
                autoSubmit();
            }, 600); // delay to avoid too many reloads
        });

        const deptName = document.getElementById("departmentName");
        const deptId = document.getElementById("departmentId");
        const options = document.querySelectorAll("#departmentList option");

        // Show selected department name if ID exists
        if (deptId.value) {
            options.forEach(option => {
                if (option.dataset.id === deptId.value) {
                    deptName.value = option.value;
                }
            });
        }


    });

    function autoSubmit() {

        const deptName = document.getElementById("departmentName");
        const deptId = document.getElementById("departmentId");
        const options = document.querySelectorAll("#departmentList option");

        let matched = false;

        options.forEach(option => {
            if (option.value === deptName.value) {
                deptId.value = option.dataset.id;
                matched = true;
            }
        });

        // If user cleared or typed something else
        if (!matched) {
            deptId.value = '';
        }

        form.submit();
    }

});


// Bulk Selection Management
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const subjectCheckboxes = document.querySelectorAll('.subject-checkbox');
    const bulkActionsContainer = document.getElementById('bulkActionsContainer');
    const selectedCountElement = document.getElementById('selectedCount');

    // Select All functionality
    selectAllCheckbox.addEventListener('change', function() {
        subjectCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectionUI();
    });

    // Individual checkbox change
    subjectCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionUI);
    });

    function updateSelectionUI() {
        const selectedCount = document.querySelectorAll('.subject-checkbox:checked').length;

        if (selectedCount > 0) {
            bulkActionsContainer.classList.add('active');
            selectedCountElement.textContent = selectedCount + ' subject(s) selected';

            // Update select all checkbox state
            selectAllCheckbox.checked = selectedCount === subjectCheckboxes.length;
            selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < subjectCheckboxes.length;
        } else {
            bulkActionsContainer.classList.remove('active');
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
    }
});

// Bulk Action Functions
function clearSelection() {
    document.querySelectorAll('.subject-checkbox:checked').forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    document.getElementById('bulkActionsContainer').classList.remove('active');
}

function bulkAction(action, format = null) {
    const selectedSubjects = Array.from(document.querySelectorAll('.subject-checkbox:checked'))
        .map(checkbox => checkbox.value);

    if (selectedSubjects.length === 0) {
        alert('Please select at least one subject.');
        return;
    }

    switch (action) {
            case 'download':

                    if (!format) {
                        alert("Please select a format");
                        return;
                    }

                    const ids = selectedSubjects.join(',');

                    const url = `/download-subjects-coursewise?ids=${ids}&type=${format}`;

                    window.location.href = url;
            break; 

        case 'bulk_delete':
            if (confirm(
                    `Are you sure you want to delete ${selectedSubjects.length} subject(s)? This action cannot be undone.`
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
                    alert(`${selectedSubjects.length} subjects deleted successfully`);
                }, 1500);
            }
            break;

        default:
            alert(`${action} action triggered for ${selectedSubjects.length} subjects`);
    }
}

// Panel Functions
function openViewPanel() {
    document.getElementById("viewSubjectsPanel").classList.add("open");
    document.body.style.overflow = "hidden";
}

function closeViewPanel() {
    const viewPanel = document.getElementById("viewSubjectsPanel");
    viewPanel.classList.remove("open");
    viewPanel.style.right = "";
    document.body.style.overflow = "";
}

// View Subject Function
function viewSubject(id) {
    openViewPanel();

    // Reset content
    $("#subjectDetails").html(
        '<div class="text-center py-4"><div class="loading-spinner mb-2"></div><p>Loading subject details...</p></div>'
    );

    $.ajax({
        url: '/subject-coursewisebyid/' + id,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                let data = response.data;
                let subSubjects = response.sub_subjects || [];

                // Format the date
                let assignedDate = new Date(data.assigned_date);
                let formattedDate = assignedDate.toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });

                let html = `
                        <div class="view-section">
                            <div class="view-section-header">Subject Information</div>
                            <div class="view-section-body">
                                <div class="view-row">
                                    <div class="view-label">Subject ID</div>
                                    <div class="view-value"><span class="subject-id">${data.subject_id}</span></div>
                                </div>
                                <div class="view-row">
                                    <div class="view-label">Subject Name</div>
                                    <div class="view-value">${data.subject_name}</div>
                                </div>
                                <div class="view-row">
                                    <div class="view-label">${"{{ $courseLabel }}"} Type</div>
                                    <div class="view-value">${data.course_type || 'N/A'}</div>
                                </div>
                                <div class="view-row">
                                    <div class="view-label">${"{{ $courseLabel }}"} Sub Type</div>
                                    <div class="view-value">${data.sub_type || 'N/A'}</div>
                                </div>
                                <div class="view-row">
                                    <div class="view-label">Semester/Term</div>
                                    <div class="view-value">
                                        ${data.semester_id === 'all_semesters' ? 
                                            '<span>All Semesters/Terms</span>' : 
                                            '<span>' + data.semester_id + '</span>'}
                                    </div>
                                </div>
                                <div class="view-row">
                                    <div class="view-label">Assigned Date</div>
                                    <div class="view-value">${formattedDate}</div>
                                </div>
                                <div class="view-row">
                                    <div class="view-label">Status</div>
                                    <div class="view-value">
                                        ${data.status === 'Active' ? 
                                            '<span class="status-badge status-active">Active</span>' : 
                                            '<span class="status-badge status-inactive">Inactive</span>'}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                // Add Sub Subjects section
                if (subSubjects.length > 0) {
                    html += `
                            <div class="view-section">
                                <div class="view-section-header">Sub Subjects (${subSubjects.length})</div>
                                <div class="view-section-body">`;

                    subSubjects.forEach((subSub, index) => {
                        html += `
                                <div class="view-row">
                                    <div class="view-label">${index + 1}. ${subSub.sub_subject_name}</div>
                                    <div class="view-value">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="subject-id">${subSub.sub_subject_id}</span>
                                            <span class="badge ${subSub.status === 'active' ? 'bg-success' : 'bg-secondary'}">
                                                ${subSub.status}
                                            </span>
                                        </div>
                                    </div>
                                </div>`;
                    });

                    html += `</div></div>`;
                }

                // Add Remarks section
                html += `
                        <div class="view-section">
                            <div class="view-section-header">Additional Information</div>
                            <div class="view-section-body">
                                <div class="view-row">
                                    <div class="view-label">Remarks</div>
                                    <div class="view-value">${data.remarks || '-'}</div>
                                </div>
                                <div class="view-row">
                                    <div class="view-label">Created At</div>
                                    <div class="view-value">${new Date(data.created_at).toLocaleString('en-GB')}</div>
                                </div>
                                <div class="view-row">
                                    <div class="view-label">Updated At</div>
                                    <div class="view-value">${new Date(data.updated_at).toLocaleString('en-GB')}</div>
                                </div>
                            </div>
                        </div>
                    `;

                $('#subjectDetails').html(html);
            } else {
                $('#subjectDetails').html(`
                        <div class="alert alert-danger">
                            ${response.message || 'Failed to load subject details'}
                        </div>
                        <button class="btn btn-secondary mt-3" onclick="closeViewPanel()">
                            <i class="bi bi-x-circle me-1"></i> Close
                        </button>
                    `);
            }
        },
        error: function() {
            $('#subjectDetails').html(`
                    <div class="alert alert-danger">
                        Failed to load subject details. Please try again.
                    </div>
                    <button class="btn btn-secondary mt-3" onclick="closeViewPanel()">
                        <i class="bi bi-x-circle me-1"></i> Close
                    </button>
                `);
        }
    });
}

// Filter form submission with loading state
document.getElementById('filterForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('.btn-filter-primary');
    const originalHTML = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span class="loading-spinner"></span> Applying...';
    submitBtn.disabled = true;

    // Re-enable button after 2 seconds in case of error
    setTimeout(() => {
        submitBtn.innerHTML = originalHTML;
        submitBtn.disabled = false;
    }, 2000);
});

// Close panel on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeViewPanel();
    }
});
</script>
@endsection