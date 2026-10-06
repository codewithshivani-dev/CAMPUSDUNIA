@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<title>Return Books Management</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

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

    body {
        background-color: #f8fafc;
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

    .page-subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
        margin-top: 4px;
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

    /* Stats Cards - Enhanced */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border: none;
        border-radius: 16px;
        padding: 25px;
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
        margin-bottom: 15px;
        font-size: 24px;
    }

    .stat-number {
        font-size: 32px;
        font-weight: 700;
        color: var(--dark);
        line-height: 1.2;
    }

    .stat-label {
        font-size: 14px;
        color: var(--gray);
        margin-top: 5px;
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
        position: relative;
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

    .erp-table tbody tr.selected-row {
        background: rgba(67, 97, 238, 0.1) !important;
        border-left: 4px solid var(--primary-color);
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
        color: black;
    }

    .status-badge:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }

    .status-issued {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .status-overdue {
        background: var(--danger-gradient);
    }

    .status-returned {
        background: var(--success-gradient);
    }

    .status-reissued {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
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
        color: white;
    }

    .bulk-action-btn.download:hover {
        background: linear-gradient(135deg, #059669, #10b981);
    }

    .bulk-action-btn.delete {
        background: var(--danger-gradient);
        color: white;
    }

    .bulk-action-btn.delete:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
    }

    .bulk-action-btn.return {
        background: var(--primary-gradient);
        color: white;
    }

    .bulk-action-btn.return:hover {
        background: linear-gradient(135deg, #3a0ca3, #4361ee);
    }

    .bulk-action-btn.clear {
        background: linear-gradient(135deg, #64748b, #475569);
        color: white;
    }

    .bulk-action-btn.clear:hover {
        background: linear-gradient(135deg, #475569, #334155);
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
        border-color: var(--primary-color);
        transform: scale(1.1);
    }

    .select-checkbox:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
    }

    /* Card Styling */
    .card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: none;
        margin-bottom: 20px;
        /*overflow: hidden;*/
    }

    .card-header {
        padding: 18px 24px;
        background: var(--primary-gradient);
        border-bottom: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h2 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header h2 i {
        font-size: 1.4rem;
        filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
    }

    .card-body {
        padding: 24px;
    }

    /* Book Count Badge */
    .book-count {
        display: inline-block;
        background: white;
        color: var(--primary-color);
        font-size: 0.85rem;
        padding: 4px 12px;
        border-radius: 30px;
        margin-left: 10px;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Copy ID Badge */
    .copy-id-badge {
        background: var(--primary-light);
        color: var(--primary-color);
        padding: 6px 12px;
        border-radius: 6px;
        font-family: monospace;
        font-size: 0.85rem;
        font-weight: 600;
        border: 1px solid var(--primary-color);
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .copy-id-badge i {
        font-size: 0.9rem;
    }

    /* Book Info */
    .book-info {
        font-size: 12px;
        color: var(--gray);
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Auto Fine Badge */
    .auto-fine-badge {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
        padding: 4px 8px;
        border-radius: 16px;
        font-size: 11px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        border: 1px solid #fbbf24;
    }

    /* Fine Card */
    .fine-card {
        border: none;
        margin-top: 30px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 16px;
        overflow: hidden;
    }

    .fine-card .card-body {
        padding: 25px;
    }

    .fine-card .card-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--border);
    }

    /* Fine Type Selector */
    .fine-type-selector {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-top: 15px;
        border-left: 4px solid var(--warning-gradient);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .fine-type-selector h6 {
        font-size: 15px;
        margin-bottom: 15px;
        color: var(--dark);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .fine-type-selector h6 i {
        color: #f59e0b;
    }

    /* Fine Type Cards */
    .fine-type-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .fine-type-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.15);
        border-color: var(--primary-color);
    }

    .fine-type-header {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .fine-type-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .fine-type-icon.overdue {
        background: #fee2e2;
        color: #dc2626;
    }

    .fine-type-icon.damaged {
        background: #fff3cd;
        color: #856404;
    }

    .fine-type-icon.lost {
        background: #f8d7da;
        color: #721c24;
    }

    .fine-type-icon.misplaced {
        background: #e2d5f8;
        color: #5a3e8a;
    }

    .fine-type-icon.other {
        background: #e2e3e5;
        color: #383d41;
    }

    .fine-type-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--dark);
        margin: 0;
    }

    .fine-type-amount {
        font-size: 18px;
        font-weight: 700;
        color: #dc2626;
        margin-left: auto;
    }

    .fine-type-desc {
        color: var(--gray);
        font-size: 12px;
        margin-top: 2px;
    }

    /* Fine Breakdown */
    .fine-breakdown {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin: 20px 0;
        border: 1px solid var(--border);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .fine-breakdown-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .fine-breakdown-table {
        width: 100%;
    }

    .fine-breakdown-table td {
        padding: 8px 0;
        border-bottom: 1px dashed var(--border);
        font-size: 14px;
    }

    .fine-breakdown-table tr:last-child td {
        border-bottom: none;
        padding-top: 12px;
        font-weight: 700;
        color: var(--dark);
    }

    /* Book Copy Details */
    .book-copy-details {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border-radius: 12px;
        padding: 20px;
        margin: 20px 0;
        border-left: 4px solid var(--primary-color);
        border: 1px solid var(--border);
    }

    .book-copy-details h6 {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 15px;
    }

    .book-copy-details table {
        width: 100%;
        font-size: 13px;
    }

    .book-copy-details td {
        padding: 6px 8px;
        border: none;
    }

    .book-copy-details td:first-child {
        color: var(--gray);
        width: 35%;
        font-weight: 500;
    }

    .book-copy-details td:last-child {
        color: var(--dark);
        font-weight: 500;
    }

    /* Payment Method Cards */
    .payment-method-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin: 25px 0;
    }

    .payment-method-card {
        background: white;
        border: 2px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        flex: 1;
        min-width: 120px;
    }

    .payment-method-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(67, 97, 238, 0.15);
    }

    .payment-method-card.selected {
        border-color: var(--primary-color);
        background: var(--primary-light);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.2);
    }

    .payment-method-icon {
        font-size: 32px;
        margin-bottom: 10px;
    }

    .payment-method-title {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 4px;
        font-size: 14px;
    }

    .payment-method-desc {
        font-size: 12px;
        color: var(--gray);
    }

    /* Online Options */
    .online-options-container {
        margin-top: 20px;
        padding: 25px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 12px;
        border: 1px solid var(--border);
    }

    .online-options-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .online-options-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .qr-section {
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 25px;
        text-align: center;
    }

    .qr-placeholder {
        width: 160px;
        height: 160px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px dashed var(--primary-color);
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        color: var(--primary-color);
    }

    .qr-placeholder i {
        font-size: 48px;
        margin-bottom: 8px;
    }

    .payment-link-section {
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 25px;
    }

    .payment-link-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .payment-link-icon {
        width: 45px;
        height: 45px;
        background: var(--primary-light);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 22px;
    }

    .payment-link-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--dark);
        margin: 0;
    }

    .payment-link-desc {
        font-size: 12px;
        color: var(--gray);
        margin-top: 2px;
    }

    .payment-link-container {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 10px;
        padding: 16px;
        margin-top: 15px;
        border: 1px solid var(--border);
    }

    /* Submit Button */
    .submit-btn {
        background: var(--primary-gradient);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 14px 36px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        position: relative;
        overflow: hidden;
    }

    .submit-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .submit-btn:hover::before {
        left: 100%;
    }

    .submit-btn:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
    }

    .btn-outline-primary {
        background: white;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-outline-primary:hover {
        background: var(--primary-gradient);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.2);
        border-color: transparent;
    }

    /* Loading Spinner */
    .loading-spinner {
        display: inline-block;
        width: 18px;
        height: 18px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-top: 3px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 8px;
    }

    @keyframes spin {
        to {
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

    /* Empty State */
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

    /* Quick Filter Dropdown */
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

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 20px;
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

    /* Floating Date */
    .floating-date {
        position: relative;
        display: flex;
        align-items: center;
    }

    .floating-date input {
        width: 100%;
        padding: 12px 12px 12px 40px;
        border: 2px solid var(--border);
        border-radius: 10px;
        background: #fff;
        font-size: 14px;
        transition: all 0.3s;
    }

    .floating-date label {
        position: absolute;
        left: 40px;
        top: 50%;
        transform: translateY(-50%);
        background: #fff;
        padding: 0 4px;
        color: var(--gray);
        font-size: 14px;
        pointer-events: none;
        transition: 0.2s ease;
    }

    .floating-date input:focus+label,
    .floating-date input:not(:placeholder-shown)+label {
        top: 2px;
        font-size: 11px;
        color: var(--primary-color);
    }

    /* Filter Badge */
    .filter-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-radius: 20px;
        font-size: 13px;
        color: var(--dark);
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid transparent;
    }

    .filter-badge i {
        color: var(--primary-color);
    }

    .filter-badge .remove {
        cursor: pointer;
        margin-left: 6px;
        color: var(--gray);
        font-size: 16px;
        font-weight: bold;
    }

    .filter-badge .remove:hover {
        color: var(--danger-color);
    }

    .filter-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .filter-badge.bg-danger {
        background: var(--danger-gradient) !important;
        color: white;
    }

    /* Text utilities */
    .text-secondary {
        color: var(--gray) !important;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .text-secondary i {
        color: var(--primary-color);
        font-size: 12px;
    }

    .text-danger {
        color: var(--danger-color) !important;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .fw-medium {
        font-weight: 600;
        color: var(--dark);
    }

    .fw-bold {
        font-weight: 700;
    }

    .text-sm {
        font-size: 13px;
        color: var(--gray);
    }

    /* Divider */
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        color: var(--gray);
        font-size: 13px;
        margin: 25px 0;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid var(--border);
    }

    .divider::before {
        margin-right: 12px;
    }

    .divider::after {
        margin-left: 12px;
    }

    /* Damage Description */
    .damage-description {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 10px;
        padding: 20px;
        margin-top: 15px;
        border: 1px solid var(--border);
    }

    /* Form Controls */
    .form-control,
    .form-select {
        height: 42px;
        padding: 0 14px;
        border: 2px solid var(--border);
        border-radius: 8px;
        font-size: 14px;
        background: white;
        transition: all 0.3s;
    }

    .form-control:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        transform: translateY(-2px);
    }

    .form-control:hover,
    .form-select:hover {
        border-color: var(--secondary-color);
    }

    textarea.form-control {
        height: auto;
        padding: 10px 14px;
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

    .alert-info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border: 1px solid #93c5fd;
        color: #1e40af;
    }

    /* Suggestions Box */
    .suggestions-box {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid var(--border);
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
    }

    .suggestion-item {
        padding: 12px 16px;
        cursor: pointer;
        transition: all 0.2s;
        border-bottom: 1px solid #f1f5f9;
    }

    .suggestion-item:hover {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        transform: translateX(5px);
    }

    .suggestion-item strong {
        color: var(--primary-color);
    }

    .suggestion-group {
        padding: 8px 16px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        font-weight: 600;
        color: var(--primary-color);
        font-size: 13px;
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

        .stats-container {
            grid-template-columns: 1fr;
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

        .online-options-grid {
            grid-template-columns: 1fr;
        }

        .payment-method-container {
            flex-direction: column;
        }

        .payment-method-card {
            width: 100%;
        }

        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .card-header .d-flex {
            width: 100%;
        }

        select.form-select-sm {
            width: 100% !important;
            margin-top: 10px;
        }
    }

    /* Placeholder styling */
    ::placeholder {
        color: #a0aec0;
        opacity: 0.8;
        font-size: 0.9rem;
    }

    /* Focus styles */
    .btn:focus-visible,
    .form-control:focus-visible,
    .form-select:focus-visible {
        outline: 2px solid var(--primary-color);
        outline-offset: 2px;
    }
    .table-responsive{
        overflow-x: hidden;
    }
    
    .erp-table thead .sticky-main,
    .erp-table tbody .sticky-main{
        left: 54px;
    }
</style>

<div id="pageLoader">
    <div class="spinner"></div>
</div>

<div class="container-fluid">
    <!-- Header with Stats -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="bi bi-arrow-return-left"></i>
                Return Books Management
            </h1>
            <div class="page-subtitle">Manage book returns, fines and payments</div>
        </div>
        <a href="{{ route('library.issue.create') }}" class="add-btn">
            <i class="bi bi-arrow-left"></i>
            Back to Issues
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

    <!-- Stats Cards - Enhanced -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(67, 97, 238, 0.1); color: var(--primary-color);">
                <i class="bi bi-book-fill"></i>
            </div>
            <div class="stat-number">{{ $totalIssues ?? $issues->total() ?? 0 }}</div>
            <div class="stat-label">Total Active Issues</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #d97706;">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="stat-number">{{ $totalOverdue ?? 0 }}</div>
            <div class="stat-label">Overdue Books</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(220, 38, 38, 0.1); color: #dc2626;">
                <i class="bi bi-cash-coin"></i>
            </div>
            <div class="stat-number">{{ $totalWithFine ?? 0 }}</div>
            <div class="stat-label">With Fine</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(22, 163, 74, 0.1); color: #16a34a;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stat-number">{{ ($totalIssues ?? 0) - ($totalWithFine ?? 0) }}</div>
            <div class="stat-label">Ready to Return</div>
        </div>
    </div>

    <!-- Advanced Filter Section - Enhanced -->
    <div class="filter-container">
        <form method="GET" action="{{ route('library.return.create') }}" id="filterForm" class="filter-form">
            <div class="filter-grid">
                {{-- Student / Employee Filter --}}
                <div class="filter-group">
                    <i class="bi bi-person-fill"></i>
                    <input type="search" id="issueable_search" list="issueableList"
                        placeholder="Student / Employee Name" autocomplete="off">
                    <input type="hidden" name="issueable_id" id="issueable_id" value="{{ request('issueable_id') }}">
                    <datalist id="issueableList">
                        @foreach($students as $student)
                        <option data-id="{{ $student['id'] }}" value="{{ $student['name'] }}">
                            @endforeach
                            @foreach($employees as $employee)
                        <option data-id="{{ $employee['id'] }}" value="{{ $employee['name'] }}">
                            @endforeach
                    </datalist>
                </div>

                {{-- Book Filter --}}
                <div class="filter-group">
                    <i class="bi bi-book-fill"></i>
                    <input type="search" name="book_id" list="bookList" placeholder="Book Title"
                        value="{{ request('book_id') }}" onchange="this.form.submit()">
                    <datalist id="bookList">
                        @foreach($books as $book)
                        <option value="{{ $book->id }}">
                            {{ $book->title }}
                        </option>
                        @endforeach
                    </datalist>
                </div>

                {{-- Copy ID Filter --}}
                <div class="filter-group">
                    <i class="bi bi-upc-scan"></i>
                    <input type="search" name="copy_id" list="copyList" placeholder="Copy ID"
                        value="{{ request('copy_id') }}" onchange="this.form.submit()">
                    <datalist id="copyList">
                        @foreach($copyIds as $copyId)
                        <option value="{{ $copyId }}">
                            @endforeach
                    </datalist>
                </div>

                {{-- Overdue Status --}}
                <div class="filter-group">
                    <i class="bi bi-clock-history"></i>
                    <input type="search" name="overdue_status" list="overdueList" placeholder="Overdue Status"
                        value="{{ request('overdue_status') }}" onchange="this.form.submit()">
                    <datalist id="overdueList">
                        <option value="overdue">Overdue</option>
                        <option value="not_overdue">Not Overdue</option>
                    </datalist>
                </div>

                {{-- Fine Status --}}
                <div class="filter-group">
                    <i class="bi bi-cash"></i>
                    <input type="search" name="fine_status" list="fineList" placeholder="Fine Status"
                        value="{{ request('fine_status') }}" onchange="this.form.submit()">
                    <datalist id="fineList">
                        <option value="with_fine">With Fine</option>
                        <option value="without_fine">Without Fine</option>
                    </datalist>
                </div>

                {{-- Issue Date --}}
                <div class="filter-group floating-date">
                    <i class="bi bi-calendar"></i>
                    <input type="date" name="issue_date" class="filter-input" value="{{ request('issue_date') }}"
                        onchange="this.form.submit()" placeholder=" ">
                    <label>Issue Date</label>
                </div>

                {{-- Due Date --}}
                <div class="filter-group floating-date">
                    <i class="bi bi-calendar"></i>
                    <input type="date" name="due_date" class="filter-input" value="{{ request('due_date') }}"
                        onchange="this.form.submit()" placeholder=" ">
                    <label>Due Date</label>
                </div>

                <div class="filter-actions">
                    <a href="{{ route('library.return.create') }}" class="btn-filter btn-filter-secondary">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Quick Filter Dropdown -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-lightning-charge-fill"></i> Quick Filters
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item"
                            href="{{ route('library.return.create', ['overdue_status' => 'overdue']) }}">
                            <i class="bi bi-exclamation-triangle-fill text-warning"></i> Overdue Books
                        </a></li>
                    <li><a class="dropdown-item"
                            href="{{ route('library.return.create', ['fine_status' => 'with_fine']) }}">
                            <i class="bi bi-cash-coin text-danger"></i> With Fine
                        </a></li>
                    <li><a class="dropdown-item" href="{{ route('library.return.create') }}">
                            <i class="bi bi-book-fill text-primary"></i> All Active Issues
                        </a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item"
                            href="{{ route('library.return.create', ['from_date' => \Carbon\Carbon::now()->subDays(7)->format('Y-m-d')]) }}">
                            <i class="bi bi-calendar-week"></i> Issued in last 7 days
                        </a></li>
                </ul>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-sm">
                <i class="bi bi-layout-text-window me-1" style="color: var(--primary-color);"></i>
                Showing {{ $issues->firstItem() ?? 0 }} - {{ $issues->lastItem() ?? 0 }} of {{ $issues->total() ?? 0 }} issues
            </span>
            <select class="form-select form-select-sm" onchange="window.location.href=this.value" style="width: 120px;">
                <option value="{{ request()->fullUrlWithQuery(['per_page' => 15]) }}"
                    {{ request('per_page') == 15 ? 'selected' : '' }}>15 per page</option>
                <option value="{{ request()->fullUrlWithQuery(['per_page' => 30]) }}"
                    {{ request('per_page') == 30 ? 'selected' : '' }}>30 per page</option>
                <option value="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}"
                    {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                <option value="{{ request()->fullUrlWithQuery(['per_page' => 100]) }}"
                    {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
            </select>
        </div>
    </div>

    <!-- Bulk Actions Container - Enhanced -->
    <div class="bulk-actions-container" id="bulkActionsContainer">
        <div class="selected-count" id="selectedCount">0 issues selected</div>
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
            <button class="bulk-action-btn return d-none" onclick="bulkAction('bulk_return')">
                <i class="bi bi-arrow-return-left"></i>
                Bulk Return
            </button>
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

    <!-- Main Card -->
    <div class="card">
        <div class="card-header">
            <h2>
                <i class="bi bi-list-check"></i>
                Issued Books
                <span class="book-count">{{ $issues->total() ?? 0 }}</span>
            </h2>
        </div>

        <div class="card-body">
            <!-- Books Table with Checkboxes -->
            <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th class="d-none" width="40">
                                <input type="checkbox" id="selectAll" class="select-checkbox">
                            </th>
                            <th class="sticky-checkbox small" width="40">Select</th>
                            <th class="sticky-main sortable" onclick="sortTable('title')">
                                Book Title
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill"></i>
                                    <i class="sort-icon bi bi-caret-down-fill"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('copy_id')">
                                Copy Details
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill"></i>
                                    <i class="sort-icon bi bi-caret-down-fill"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('issued_to')">
                                Issued To
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill"></i>
                                    <i class="sort-icon bi bi-caret-down-fill"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('issue_date')">
                                Issue Date
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill"></i>
                                    <i class="sort-icon bi bi-caret-down-fill"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('due_date')">
                                Due Date
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill"></i>
                                    <i class="sort-icon bi bi-caret-down-fill"></i>
                                </div>
                            </th>
                            <th class="sortable text-center" onclick="sortTable('days_overdue')">
                                Overdue Days
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill"></i>
                                    <i class="sort-icon bi bi-caret-down-fill"></i>
                                </div>
                            </th>
                            <th class="sortable text-end" onclick="sortTable('fine_amount')">
                                Fine Amount
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill"></i>
                                    <i class="sort-icon bi bi-caret-down-fill"></i>
                                </div>
                            </th>
                            <th class="sortable text-center" onclick="sortTable('payment_status')">
                                Payment Status
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill"></i>
                                    <i class="sort-icon bi bi-caret-down-fill"></i>
                                </div>
                            </th>
                            <th class="sortable text-center" onclick="sortTable('status')">
                                Issue Status
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill"></i>
                                    <i class="sort-icon bi bi-caret-down-fill"></i>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="issuedBooksContainer">
                        @forelse($issues as $issue)
                        @php
                        // Calculate overdue details in REAL TIME based on due date vs today
                        $due = \Carbon\Carbon::parse($issue->due_date);
                        $today = \Carbon\Carbon::today();
                        $daysOverdue = $today->gt($due) ? $due->diffInDays($today) : 0;

                        // Determine if book is overdue in real time
                        $isOverdue = $daysOverdue > 0;

                        $merchantId = auth()->user()->institute_id;

                        // Get active overdue fine rule for this institute
                        $overdueRule = \App\Models\LibraryFineRule::where('institute_id', $merchantId)
                        ->where('fine_type', 'overdue')
                        ->where('is_active', true)
                        ->first();

                        // Calculate overdue fine based on rule or default (REAL TIME)
                        if ($overdueRule && $daysOverdue > 0) {
                        $graceDays = $overdueRule->grace_period_days ?? 0;
                        $effectiveDays = max(0, $daysOverdue - $graceDays);

                        if ($effectiveDays > 0) {
                        if ($overdueRule->frequency == 'one_time') {
                        $fineAmount = $overdueRule->amount;
                        } else {
                        $fineAmount = $effectiveDays * $overdueRule->amount;
                        }

                        // Handle percentage
                        if ($overdueRule->amount_type == 'percentage_of_book_cost' && $issue->libraryBook) {
                        $bookCost = $issue->libraryBook->price ?? 0;
                        $fineAmount = ($overdueRule->amount / 100) * $bookCost;
                        if ($overdueRule->frequency != 'one_time') {
                        $fineAmount = $fineAmount * $effectiveDays;
                        }
                        }
                        } else {
                        $fineAmount = 0;
                        }
                        } else {
                        $fineAmount = $daysOverdue * 5; // Default fine
                        }

                        // If already returned, get fine from record
                        if ($issue->status == "returned") {
                        $fineAmount = $issue->fine->amount ?? 0;
                        $daysOverdue = $issue->fine->days_overdue ?? 0;
                        $isOverdue = false;
                        }

                        // Check if payment was made online
                        $hasPaidOnline = isset($issue->paymentLinks) && $issue->paymentLinks->status === 'paid';
                        $paidAmount = $hasPaidOnline ? ($issue->paymentLinks->amount ?? 0) : 0;

                        // Keep the original fine amount for display
                        $displayFine = $fineAmount;

                        // Get issuer details
                        if($issue->issueable_type == "App\Models\StudentParentDetails"){
                        $name = $issue->issueable->first_name ?? 'N/A';
                        $code = $issue->issueable->registration_number ?? 'N/A';
                        }elseif($issue->issueable_type == "App\Models\EmployeeDetails"){
                        $name = $issue->issueable->name ?? 'N/A';
                        $code = $issue->issueable->employee_code ?? 'N/A';
                        }

                        // Overdue badge class based on real time calculation
                        $overdueBadgeClass = $isOverdue ? 'status-overdue' : 'status-returned';
                        $overdueBadgeText = $isOverdue ? 'Overdue' : 'On time';
                        @endphp
                        <tr class="book-row" id="row-{{ $issue->id }}" data-days-overdue="{{ $daysOverdue }}"
                            data-fine-amount="{{ $fineAmount }}" data-is-overdue="{{ $isOverdue ? '1' : '0' }}"
                            data-grace-days="{{ $overdueRule->grace_period_days ?? 0 }}">
                            <td class="d-none">
                                <input type="checkbox" class="issue-checkbox select-checkbox" value="{{ $issue->id }}"
                                    data-fine="{{ $fineAmount }}" data-title="{{ $issue->libraryBook->title ?? '' }}"
                                    data-copy-id="{{ $issue->copy->copy_id ?? '' }}"
                                    data-book-cost="{{ $issue->libraryBook->price ?? 0 }}" data-id="{{ $issue->id }}"
                                    data-days="{{ $daysOverdue }}" data-is-overdue="{{ $isOverdue ? '1' : '0' }}"
                                    data-book-issue-id="{{ $issue->book_issue_id }}"
                                    data-has-paid-online="{{ $hasPaidOnline ? '1' : '0' }}"
                                    data-paid-amount="{{ $paidAmount }}"
                                    {{ $issue->status == 'returned' ? 'disabled' : '' }}>
                            </td>
                            <td class="sticky-checkbox text-center">
                                <input type="radio" name="book_issue_id" value="{{ $issue->id }}"
                                    data-fine="{{ $fineAmount }}" data-title="{{ $issue->libraryBook->title ?? '' }}"
                                    data-copy-id="{{ $issue->copy->copy_id ?? '' }}"
                                    data-book-cost="{{ $issue->libraryBook->price ?? 0 }}" data-id="{{ $issue->id }}"
                                    data-book-issue-id="{{ $issue->book_issue_id }}" data-days="{{ $daysOverdue }}"
                                    data-is-overdue="{{ $isOverdue ? '1' : '0' }}"
                                    data-has-paid-online="{{ $hasPaidOnline ? '1' : '0' }}"
                                    data-paid-amount="{{ $paidAmount }}" class="radio-select"
                                    {{ $issue->status == 'returned' ? 'disabled' : '' }} required>
                            </td>
                            <td class="sticky-main">
                                <strong style="color: var(--primary-color);">{{ $issue->libraryBook->title ?? 'N/A' }}</strong>
                                <div class="book-info">
                                    @if($isOverdue && $issue->status != 'returned')
                                    <span class="auto-fine-badge">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                        Overdue Fine
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($issue->copy)
                                <div class="copy-details">
                                    <span class="copy-id-badge">
                                        <i class="bi bi-upc-scan"></i>
                                        {{ $issue->copy->copy_id ?? '' }}
                                    </span>
                                    @if($issue->copy->writer_name ?? false)
                                    <div class="text-secondary mt-1">
                                        <i class="bi bi-pencil-fill"></i>
                                        {{ $issue->copy->writer_name }}
                                    </div>
                                    @endif
                                </div>
                                @else
                                <span class="text-secondary">Copy details not available</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $name ?? 'N/A' }}</strong>
                                <div class="book-info">
                                    @if($issue->issueable_type == 'App\Models\StudentParentDetails')
                                    <span class="text-secondary">
                                        <i class="bi bi-person-video3"></i> Student ({{ $code }})
                                    </span>
                                    @elseif($issue->issueable_type == 'App\Models\EmployeeDetails')
                                    <span class="text-secondary">
                                        <i class="bi bi-person-badge"></i> Employee ({{ $code }})
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($issue->issue_date)->format('d M Y') }}</td>
                            <td>
                                <span class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}">
                                    {{ $due->format('d M Y') }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($daysOverdue > 0)
                                <span class="fw-bold text-danger">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                    {{ $daysOverdue }} days
                                </span>
                                @else
                                <span class="status-badge status-returned">
                                    <i class="bi bi-check-circle"></i>
                                    On time
                                </span>
                                @endif
                            </td>
                            <td class="text-end amount-cell">
                                <span id="fine-display-{{ $issue->id }}" class="fw-bold" style="color: #dc2626;">
                                    ₹{{ number_format($displayFine, 2) }}
                                </span>
                                @if($hasPaidOnline)
                                <br><small class="text-success"><i class="bi bi-check-circle-fill"></i> Paid: ₹{{ number_format($paidAmount, 2) }}</small>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                if(isset($issue->paymentLinks->status)) {
                                $badgepgClass = match($issue->paymentLinks->status) {
                                'created' => 'status-issued',
                                'failed', 'expired' => 'status-overdue',
                                'paid' => 'status-returned',
                                default => ''
                                };
                                $paymentStatus = ucfirst($issue->paymentLinks->status);
                                } else {
                                if(isset($issue->fine->payment_method) && $issue->fine->payment_method == 'cash') {
                                $badgepgClass = 'status-returned';
                                $paymentStatus = 'Paid';
                                } elseif (!isset($issue->fine->payment_method)){
                                $badgepgClass = '';
                                $paymentStatus = 'Not Required';
                                } else {
                                $badgepgClass = 'status-overdue';
                                $paymentStatus = 'Pending';
                                }
                                }
                                @endphp
                                <span class="status-badge {{ $badgepgClass }}">
                                    @if($paymentStatus == 'Paid')
                                    <i class="bi bi-check-circle-fill"></i>
                                    @elseif($paymentStatus == 'Pending')
                                    <i class="bi bi-clock-fill"></i>
                                    @endif
                                    {{ $paymentStatus }}
                                </span>
                            </td>
                            <td class="text-center">
                                @php
                                $badgeClass = match($issue->status) {
                                'issued' => 'status-issued',
                                'overdue' => 'status-overdue',
                                'reissued' => 'status-reissued',
                                'returned' => 'status-returned',
                                default => ''
                                };
                                $statusIcon = match($issue->status) {
                                'issued' => 'bi-arrow-up-circle-fill',
                                'overdue' => 'bi-exclamation-triangle-fill',
                                'reissued' => 'bi-arrow-repeat',
                                'returned' => 'bi-arrow-down-circle-fill',
                                default => 'bi-question-circle-fill'
                                };
                                @endphp
                                <span class="status-badge {{ $badgeClass }}">
                                    <i class="bi {{ $statusIcon }}"></i>
                                    {{ ucfirst($issue->status) }}
                                </span>
                                @if($isOverdue && $issue->status != 'overdue' && $issue->status != 'returned')
                                <br><small class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> Actually overdue</small>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-inbox-fill"></i>
                                    </div>
                                    <h4>No Books Found</h4>
                                    <p class="mb-3">No issued books match your search criteria</p>
                                    <a href="{{ route('library.return.create') }}" class="btn-outline-primary">
                                        <i class="bi bi-arrow-counterclockwise"></i> Clear Filters
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

            <!-- Results Count and Pagination -->
            <!-- @if($issues->count() > 0)
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-sm">
                    <i class="bi bi-layout-text-window me-1" style="color: var(--primary-color);"></i>
                    Showing {{ $issues->firstItem() ?? 0 }} to {{ $issues->lastItem() ?? 0 }} of {{ $issues->total() ?? 0 }} entries
                </div>
                @if(method_exists($issues, 'links'))
                <div class="pagination-wrapper">
                    {{ $issues->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
            @endif -->

            <!-- Pagination -->
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Showing {{ $issues->firstItem() }} to {{ $issues->lastItem() }}
                    of {{ $issues->total() }} returned books
                </div>

                @if ($issues->hasPages())
                <nav>
                    <ul class="pagination mb-0">

                        {{-- Previous Page --}}
                        @if ($issues->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">Prev</span></li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $issues->previousPageUrl() }}">Prev</a>
                        </li>
                        @endif

                        {{-- Page Numbers --}}
                        @for ($i = 1; $i <= $issues->lastPage(); $i++)
                            <li class="page-item {{ $issues->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ $issues->url($i) }}">{{ $i }}</a>
                            </li>
                            @endfor

                            {{-- Next Page --}}
                            @if ($issues->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $issues->nextPageUrl() }}">Next</a>
                            </li>
                            @else
                            <li class="page-item disabled"><span class="page-link">Next</span></li>
                            @endif

                    </ul>
                </nav>
                @endif
            </div>

            <!-- STORE FORM - for return submission (POST) -->
            <form method="POST" action="{{ route('library.return.store') }}" id="returnForm">
                @csrf

                <!-- Hidden input for selected book issue ID -->
                <input type="hidden" name="book_issue_id" id="selected_book_issue_id" value="">
                <!-- Hidden field for total fine amount -->
                <input type="hidden" name="total_fine_amount" id="total_fine_amount" value="0">
                <!-- Hidden field for use_manual_fine -->
                <input type="hidden" name="use_manual_fine" id="use_manual_fine" value="0">
                <!-- Hidden payment method input -->
                <input type="hidden" name="payment_method" id="payment_method" value="">
                <!-- Hidden field for transaction reference -->
                <input type="hidden" name="transaction_ref_id" id="transaction_ref_id" value="">

                <!-- Fine & Payment Section -->
                <div id="fineSummary" class="d-none mt-4">
                    <div class="card fine-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-cash-coin"></i>
                                Fine & Payment Details
                            </h5>

                            <!-- Selected Book Info -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="text-sm mb-2">Selected Book</label>
                                    <div class="h5 fw-bold" id="selectedBook" style="color: var(--primary-color); font-size: 18px;">-</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-sm mb-2">Total Fine Amount</label>
                                    <div class="h3 fw-bold" id="selectedFine" style="color: #dc2626; font-size: 28px;">₹0.00</div>
                                </div>
                            </div>

                            <!-- Book Copy Details Container -->
                            <div id="bookCopyDetailsContainer"></div>

                            <!-- Auto Overdue Fine Info -->
                            <div id="overdueFineInfo" class="alert alert-info d-none">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <strong>Overdue Fine Applied Automatically</strong>
                                <span id="overdueFineDetails" class="ms-2"></span>
                            </div>

                            <!-- Manual Fine Type Selection -->
                            <div id="manualFineSection" class="fine-type-selector d-none">
                                <h6>
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    Additional Fines
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <select name="fine_type" id="fine_type" class="form-select">
                                            <option value="">Select Fine Type</option>
                                            <option value="damaged">Damaged Book</option>
                                            <option value="lost">Lost Book</option>
                                            <option value="misplaced">Misplaced Book</option>
                                            <option value="other">Other Fine</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <div id="fineAmountPreview" class="fine-amount-preview text-end" style="font-size: 18px; font-weight: 700; color: #dc2626;"></div>
                                    </div>
                                </div>

                                <!-- Damage Description -->
                                <div id="damageDescriptionSection" class="damage-description" style="display: none;">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-3">
                                            <label class="fw-bold text-sm">Pages Count</label>
                                            <input type="number" name="pages_count" class="form-control"
                                                placeholder="Pages">
                                        </div>
                                        <div class="col-md-9">
                                            <label class="fw-bold text-sm">Damage Description</label>
                                            <textarea name="damage_description" class="form-control" rows="2"
                                                placeholder="Describe damage..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Applied Fines Container -->
                            <div id="appliedFinesContainer"></div>

                            <!-- Fine Breakdown Container -->
                            <div id="fineBreakdownContainer"></div>

                            <!-- Divider -->
                            <div class="divider">Select Payment Method</div>

                            <!-- Payment Method Cards -->
                            <div class="payment-method-container" id="paymentMethodContainer">
                                <div class="payment-method-card" data-method="offline">
                                    <div class="payment-method-icon">
                                        <i class="bi bi-cash-stack" style="color: #10b981;"></i>
                                    </div>
                                    <div class="payment-method-title">Offline</div>
                                    <div class="payment-method-desc">Pay by Cash</div>
                                </div>
                                <div class="payment-method-card" data-method="online">
                                    <div class="payment-method-icon">
                                        <i class="bi bi-globe" style="color: var(--primary-color);"></i>
                                    </div>
                                    <div class="payment-method-title">Online</div>
                                    <div class="payment-method-desc">QR Code or Payment Link</div>
                                </div>
                                <div class="payment-method-card" data-method="waive-off">
                                    <div class="payment-method-icon">
                                        <i class="bi bi-gift" style="color: #f59e0b;"></i>
                                    </div>
                                    <div class="payment-method-title">Waive Off</div>
                                    <div class="payment-method-desc">Waive the entire fine</div>
                                </div>
                            </div>

                            <!-- Online Payment Options -->
                            <div id="onlineOptionsContainer" class="online-options-container" style="display: none;">
                                <div class="online-options-title">
                                    <i class="bi bi-credit-card"></i>
                                    Online Payment Options
                                </div>
                                <div class="online-options-grid">
                                    <div class="qr-section">
                                        <h6 class="fw-bold mb-3" style="font-size: 14px; color: var(--primary-color);">
                                            <i class="bi bi-qr-code-scan"></i>
                                            Scan QR Code
                                        </h6>
                                        <div class="qr-placeholder">
                                            <i class="bi bi-qr-code"></i>
                                            <p class="mt-2">QR Code</p>
                                            <small class="text-muted">Coming Soon</small>
                                        </div>
                                    </div>
                                    <div class="payment-link-section">
                                        <div class="payment-link-header">
                                            <div class="payment-link-icon">
                                                <i class="bi bi-link-45deg"></i>
                                            </div>
                                            <div>
                                                <h6 class="payment-link-title">Payment Link</h6>
                                                <div class="payment-link-desc">Generate and share payment link</div>
                                            </div>
                                        </div>
                                        <button type="button" id="generateLinkBtn" class="btn-outline-primary w-100">
                                            <i class="bi bi-link-45deg me-2"></i>Generate Payment Link
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Link Result -->
                            <div id="linkResult" class="mt-3"></div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="submit-btn" id="returned_btn">
                                    <i class="bi bi-check-circle-fill me-2"></i>Mark as Returned
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}

document.addEventListener('DOMContentLoaded', function() {

    const searchInput = document.getElementById('issueable_search');
    const hiddenInput = document.getElementById('issueable_id');
    const options = document.querySelectorAll('#issueableList option');

    // When selecting from datalist
    searchInput.addEventListener('input', function() {
        let selectedName = this.value;
        let found = false;

        options.forEach(option => {
            if (option.value === selectedName) {
                hiddenInput.value = option.dataset.id;
                found = true;
            }
        });

        if (!found) {
            hiddenInput.value = '';
        }
        showLoader();
        document.getElementById('filterForm').submit();
    });

    // Show selected value after reload
    const selectedId = hiddenInput.value;
    if (selectedId) {
        options.forEach(option => {
            if (option.dataset.id === selectedId) {
                searchInput.value = option.value;
            }
        });
    }

});

// Bulk Selection Management
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const issueCheckboxes = document.querySelectorAll('.issue-checkbox');
    const bulkActionsContainer = document.getElementById('bulkActionsContainer');
    const selectedCountElement = document.getElementById('selectedCount');

    // Select All functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            issueCheckboxes.forEach(checkbox => {
                if (!checkbox.disabled) {
                    checkbox.checked = this.checked;
                }
            });
            updateSelectionUI();
        });
    }

    // Individual checkbox change
    issueCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionUI);
    });

    function updateSelectionUI() {
        const selectedCount = document.querySelectorAll('.issue-checkbox:checked').length;
        const totalCheckboxes = document.querySelectorAll('.issue-checkbox:not(:disabled)').length;

        if (selectedCount > 0) {
            bulkActionsContainer.classList.add('active');
            selectedCountElement.textContent = selectedCount + ' issue(s) selected';

            // Update select all checkbox state
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = selectedCount === totalCheckboxes && totalCheckboxes > 0;
                selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < totalCheckboxes;
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

// Clear Selection
function clearSelection() {
    document.querySelectorAll('.issue-checkbox:checked').forEach(checkbox => {
        checkbox.checked = false;
    });
    const selectAll = document.getElementById('selectAll');
    if (selectAll) selectAll.checked = false;
    document.getElementById('bulkActionsContainer').classList.remove('active');
}

// Bulk Action Functions
function bulkAction(action, format = null) {
    const selectedIssues = Array.from(document.querySelectorAll('.issue-checkbox:checked'))
        .map(checkbox => checkbox.value);

    if (selectedIssues.length === 0) {
        alert('Please select at least one issue.');
        return;
    }

    switch (action) {
            case 'download':

                    if (!format) {
                        alert("Please select a format");
                        return;
                    }

                    const ids = selectedIssues.join(',');

                    const url = `/download-book-return?ids=${ids}&type=${format}`;

                    window.location.href = url;
            break;  

        case 'bulk_delete':
            if (!confirm(`Delete ${selectedIssues.length} book(s)?`)) return;
            clearSelection();
            alert('Deleted successfully');
            break;
        case 'bulk_return':
            if (confirm(`Are you sure you want to return ${selectedIssues.length} book(s)?`)) {
                showLoader();
                // Add your bulk return logic here
                setTimeout(() => {
                    const loader = document.getElementById('pageLoader');
                    if (loader) loader.style.display = 'none';
                    alert(`${selectedIssues.length} books returned successfully`);
                    clearSelection();
                }, 1500);
            }
            break;

        default:
            alert(`${action} action triggered for ${selectedIssues.length} issues`);
    }
}

// Table Search
const tableSearch = document.getElementById('tableSearch');
if (tableSearch) {
    tableSearch.addEventListener('keyup', function() {
        const value = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.book-row');
        rows.forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
        });
    });
}

// Sorting Functionality
function sortTable(column) {
    const th = event.currentTarget;
    const sortIcons = th.querySelectorAll('.sort-icon');

    // Toggle sort order
    let sortOrder = 'asc';
    if (sortIcons[0].classList.contains('active')) {
        sortOrder = 'desc';
        sortIcons[0].classList.remove('active');
        sortIcons[1].classList.add('active');
    } else if (sortIcons[1].classList.contains('active')) {
        sortOrder = 'asc';
        sortIcons[1].classList.remove('active');
        sortIcons[0].classList.add('active');
    } else {
        sortIcons[0].classList.add('active');
    }

    const tbody = document.querySelector('#issuedBooksContainer');
    if (!tbody) return;

    const rows = Array.from(tbody.querySelectorAll('tr'));

    rows.sort((a, b) => {
        let aValue = getCellValue(a, column);
        let bValue = getCellValue(b, column);

        if (column === 'fine_amount') {
            aValue = parseFloat(aValue.replace(/[^\d.-]/g, '')) || 0;
            bValue = parseFloat(bValue.replace(/[^\d.-]/g, '')) || 0;
            return sortOrder === 'asc' ? aValue - bValue : bValue - aValue;
        } else if (column === 'issue_date' || column === 'due_date') {
            aValue = new Date(aValue);
            bValue = new Date(bValue);
            return sortOrder === 'asc' ? aValue - bValue : bValue - aValue;
        } else if (column === 'days_overdue') {
            aValue = parseInt(aValue) || 0;
            bValue = parseInt(bValue) || 0;
            return sortOrder === 'asc' ? aValue - bValue : bValue - aValue;
        } else {
            aValue = aValue.toLowerCase();
            bValue = bValue.toLowerCase();
            return sortOrder === 'asc' ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
        }
    });

    rows.forEach(row => tbody.appendChild(row));
}

function getCellValue(row, column) {
    if (!row || !row.cells) return '';

    const cells = row.cells;
    const columnIndex = getColumnIndex(column);

    if (columnIndex === -1) return '';

    return cells[columnIndex]?.textContent || '';
}

function getColumnIndex(column) {
    const columns = {
        'title': 2,
        'copy_id': 3,
        'issued_to': 4,
        'issue_date': 5,
        'due_date': 6,
        'days_overdue': 7,
        'fine_amount': 8,
        'payment_status': 9,
        'status': 10
    };
    return columns[column] || -1;
}

// Search Suggestions (keep your existing search suggestion code)
const students = @json($students ?? []);
const employees = @json($employees ?? []);
const copyIds = @json($copyIds ?? []);
const searchInput = document.getElementById('globalSearch');
const suggestionsBox = document.getElementById('searchSuggestions');

if (searchInput && suggestionsBox) {
    let searchTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();

        if (query.length < 2) {
            suggestionsBox.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            showSuggestions(query);
        }, 300);
    });

    function showSuggestions(query) {
        query = query.toLowerCase();
        let html = '';

        // Filter students
        const matchedStudents = Array.isArray(students) ? students.filter(s =>
            s && s.display && (s.display.toLowerCase().includes(query) ||
                (s.registration_number && s.registration_number.toLowerCase().includes(query)))
        ).slice(0, 5) : [];

        if (matchedStudents.length > 0) {
            html += '<div class="suggestion-group"><strong>Students</strong></div>';
            matchedStudents.forEach(s => {
                html += `
                    <div class="suggestion-item" onclick="selectSuggestion('${s.display.replace(/'/g, "\\'")}')">
                        <i class="bi bi-person text-primary"></i>
                        <strong>${s.name || ''}</strong>
                        <small class="ms-2">${s.registration_number || ''}</small>
                    </div>
                `;
            });
        }

        // Filter employees
        const matchedEmployees = Array.isArray(employees) ? employees.filter(e =>
            e && e.display && (e.display.toLowerCase().includes(query) ||
                (e.employee_code && e.employee_code.toLowerCase().includes(query)))
        ).slice(0, 5) : [];

        if (matchedEmployees.length > 0) {
            html += '<div class="suggestion-group mt-2"><strong>Employees</strong></div>';
            matchedEmployees.forEach(e => {
                html += `
                    <div class="suggestion-item" onclick="selectSuggestion('${e.display.replace(/'/g, "\\'")}')">
                        <i class="bi bi-briefcase text-success"></i>
                        <strong>${e.name || ''}</strong>
                        <small class="ms-2">${e.employee_code || ''}</small>
                    </div>
                `;
            });
        }

        // Filter copy IDs
        const matchedCopies = Array.isArray(copyIds) ? copyIds.filter(c =>
            c && c.toLowerCase().includes(query)
        ).slice(0, 5) : [];

        if (matchedCopies.length > 0) {
            html += '<div class="suggestion-group mt-2"><strong>Copy IDs</strong></div>';
            matchedCopies.forEach(c => {
                html += `
                    <div class="suggestion-item" onclick="selectSuggestion('${c.replace(/'/g, "\\'")}')">
                        <i class="bi bi-upc-scan text-warning"></i>
                        <code>${c}</code>
                    </div>
                `;
            });
        }

        if (html === '') {
            html = '<div class="p-3 text-muted">No suggestions found</div>';
        }

        suggestionsBox.innerHTML = html;
        suggestionsBox.style.display = 'block';
    }

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (searchInput && suggestionsBox && !searchInput.contains(e.target) && !suggestionsBox.contains(e
                .target)) {
            suggestionsBox.style.display = 'none';
        }
    });
}

// Search suggestion select
window.selectSuggestion = function(value) {
    const searchInput = document.getElementById('globalSearch');
    const suggestionsBox = document.getElementById('searchSuggestions');
    const filterForm = document.getElementById('filterForm');

    if (searchInput) {
        searchInput.value = value;
        if (suggestionsBox) suggestionsBox.style.display = 'none';
        if (filterForm) filterForm.submit();
    }
};
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    // ============ ELEMENT SELECTORS WITH NULL CHECKS ============
    const radios = document.querySelectorAll('input[name="book_issue_id"]');
    const fineSummary = document.getElementById('fineSummary');
    const returnbtn = document.getElementById('returned_btn');
    const fineAmountEl = document.getElementById('selectedFine');
    const fineBook = document.getElementById('selectedBook');
    const generateLinkBtn = document.getElementById('generateLinkBtn');
    const linkResult = document.getElementById('linkResult');
    const paymentMethodInput = document.getElementById('payment_method');
    const fineTypeSelect = document.getElementById('fine_type');
    const manualFineSection = document.getElementById('manualFineSection');
    const overdueFineInfo = document.getElementById('overdueFineInfo');
    const overdueFineDetails = document.getElementById('overdueFineDetails');
    const damageDescriptionSection = document.getElementById('damageDescriptionSection');
    const appliedFinesContainer = document.getElementById('appliedFinesContainer');
    const fineBreakdownContainer = document.getElementById('fineBreakdownContainer');
    const onlineOptionsContainer = document.getElementById('onlineOptionsContainer');
    const paymentMethodCards = document.querySelectorAll('.payment-method-card');
    const fineAmountPreview = document.getElementById('fineAmountPreview');
    const returnForm = document.getElementById('returnForm');
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('globalSearch');
    const suggestionsBox = document.getElementById('searchSuggestions');
    const tableSearch = document.getElementById('tableSearch');
    const paymentMethodContainer = document.getElementById('paymentMethodContainer');
    const selectedBookIssueId = document.getElementById('selected_book_issue_id');
    const useManualFineInput = document.getElementById('use_manual_fine');

    // ============ STATE VARIABLES ============
    let selectedFine = 0;
    let selectedBookId = null;
    let selectedBookTitle = '';
    let selectedBookCopyId = '';
    let selectedBookCopy = null;
    let hasOverdueFine = false;
    let overdueDays = 0;
    let graceDays = 0;
    let overdueFineAmount = 0;
    let bookCost = 0;
    let bookPages = 0;
    let bookCondition = '';
    let bookWriter = '';
    let bookPublishDate = '';
    let bookRack = '';
    let bookShelf = '';
    let selectedPaymentMethod = '';
    let manualFineAmount = 0;
    let manualFineRule = null;
    let manualFineType = '';
    let useManualFine = 0;
    let actualBookIssueId = '';
    let originalFineAmount = 0; // Add this to store original fine amount

    // ============ SEARCH SUGGESTIONS ============
    // Student and Employee data from backend
    const students = @json($students ?? []);
    const employees = @json($employees ?? []);
    const copyIds = @json($copyIds ?? []);

    if (searchInput && suggestionsBox) {
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 2) {
                suggestionsBox.style.display = 'none';
                return;
            }

            searchTimeout = setTimeout(() => {
                showSuggestions(query);
            }, 300);
        });

        function showSuggestions(query) {
            query = query.toLowerCase();
            let html = '';

            // Filter students
            const matchedStudents = Array.isArray(students) ? students.filter(s =>
                s && s.display && (s.display.toLowerCase().includes(query) ||
                    (s.registration_number && s.registration_number.toLowerCase().includes(query)))
            ).slice(0, 5) : [];

            if (matchedStudents.length > 0) {
                html += '<div class="suggestion-group"><strong>Students</strong></div>';
                matchedStudents.forEach(s => {
                    html += `
                        <div class="suggestion-item" onclick="selectSuggestion('${s.display.replace(/'/g, "\\'")}')">
                            <i class="bi bi-person text-primary"></i>
                            <strong>${s.name || ''}</strong>
                            <small class="ms-2">${s.registration_number || ''}</small>
                        </div>
                    `;
                });
            }

            // Filter employees
            const matchedEmployees = Array.isArray(employees) ? employees.filter(e =>
                e && e.display && (e.display.toLowerCase().includes(query) ||
                    (e.employee_code && e.employee_code.toLowerCase().includes(query)))
            ).slice(0, 5) : [];

            if (matchedEmployees.length > 0) {
                html += '<div class="suggestion-group mt-2"><strong>Employees</strong></div>';
                matchedEmployees.forEach(e => {
                    html += `
                        <div class="suggestion-item" onclick="selectSuggestion('${e.display.replace(/'/g, "\\'")}')">
                            <i class="bi bi-briefcase text-success"></i>
                            <strong>${e.name || ''}</strong>
                            <small class="ms-2">${e.employee_code || ''}</small>
                        </div>
                    `;
                });
            }

            // Filter copy IDs
            const matchedCopies = Array.isArray(copyIds) ? copyIds.filter(c =>
                c && c.toLowerCase().includes(query)
            ).slice(0, 5) : [];

            if (matchedCopies.length > 0) {
                html += '<div class="suggestion-group mt-2"><strong>Copy IDs</strong></div>';
                matchedCopies.forEach(c => {
                    html += `
                        <div class="suggestion-item" onclick="selectSuggestion('${c.replace(/'/g, "\\'")}')">
                            <i class="bi bi-upc-scan text-warning"></i>
                            <code>${c}</code>
                        </div>
                    `;
                });
            }

            if (html === '') {
                html = '<div class="p-3 text-muted">No suggestions found</div>';
            }

            suggestionsBox.innerHTML = html;
            suggestionsBox.style.display = 'block';
        }

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (searchInput && suggestionsBox && !searchInput.contains(e.target) && !suggestionsBox
                .contains(e.target)) {
                suggestionsBox.style.display = 'none';
            }
        });
    }

    // Search suggestion select - make it global
    window.selectSuggestion = function(value) {
        const searchInput = document.getElementById('globalSearch');
        const suggestionsBox = document.getElementById('searchSuggestions');
        const filterForm = document.getElementById('filterForm');

        if (searchInput) {
            searchInput.value = value;
            if (suggestionsBox) suggestionsBox.style.display = 'none';
            if (filterForm) filterForm.submit();
        }
    };

    // ============ TABLE SEARCH ============
    if (tableSearch) {
        tableSearch.addEventListener('keyup', function() {
            const value = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.book-row');
            rows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
            });
        });
    }

    // ============ HELPER FUNCTIONS ============

    function formatCurrency(amount) {
        return '₹' + parseFloat(amount || 0).toFixed(2);
    }

    /**
     * Show book copy details
     */
    function showBookCopyDetails(copyData) {
        const container = document.getElementById('bookCopyDetailsContainer');
        if (!container) return;

        let conditionBadge = '';
        switch (bookCondition) {
            case 'new':
                conditionBadge = '<span class="badge bg-success text-white">New</span>';
                break;
            case 'good':
                conditionBadge = '<span class="badge bg-info text-white">Good</span>';
                break;
            case 'fair':
                conditionBadge = '<span class="badge bg-warning text-white">Fair</span>';
                break;
            case 'poor':
                conditionBadge = '<span class="badge bg-danger text-white">Poor</span>';
                break;
            case 'damage':
                conditionBadge = '<span class="badge bg-dark">Damaged</span>';
                break;
            case 'lost':
                conditionBadge = '<span class="badge bg-danger text-white">Lost</span>';
                break;
            default:
                conditionBadge = '<span class="badge bg-secondary text-white">' + (bookCondition || 'N/A') +
                    '</span>';
        }

        container.innerHTML = `
            <div class="book-copy-details">
                <h6 class="fw-bold mb-2"><i class="bi bi-info-circle"></i> Book Copy Details</h6>
                <div class="row">
                    <div class="col-md-6">
                        <table>
                            <tr>
                                <td>Copy ID:</td>
                                <td><code>${selectedBookCopyId || 'N/A'}</code></td>
                            </tr>
                            <tr>
                                <td>Writer:</td>
                                <td>${bookWriter || 'N/A'}</td>
                            </tr>
                            <tr>
                                <td>Condition:</td>
                                <td>${conditionBadge}</td>
                            </tr>
                            <tr>
                                <td>Pages:</td>
                                <td>${bookPages > 0 ? bookPages + ' pages' : 'N/A'}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table>
                            <tr>
                                <td>Book Cost:</td>
                                <td class="fw-bold text-primary">${bookCost > 0 ? formatCurrency(bookCost) : 'N/A'}</td>
                            </tr>
                            <tr>
                                <td>Location:</td>
                                <td>${bookRack ? 'Rack: ' + bookRack : ''} ${bookShelf ? 'Shelf: ' + bookShelf : ''}</td>
                            </tr>
                            <tr>
                                <td>Publish Date:</td>
                                <td>${bookPublishDate || 'N/A'}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Display applied fine as a card
     */
    function displayAppliedFine(fineType, fineAmount, ruleData) {
        if (!appliedFinesContainer) return;

        const fineId = 'fine-' + Date.now();
        let icon = '';
        let iconClass = '';
        let title = '';
        let description = '';

        switch (fineType) {
            case 'overdue':
                icon = 'bi-clock-history';
                iconClass = 'overdue';
                title = 'Overdue Fine';
                description = `${overdueDays} days overdue${graceDays > 0 ? ` (${graceDays} days grace)` : ''}`;
                break;
            case 'lost':
                icon = 'bi-bookmark-x-fill';
                iconClass = 'lost';
                title = 'Lost Book Fine';
                description =
                    `Book cost: ${formatCurrency(bookCost)} + Fine: ${formatCurrency(ruleData?.amount || 0)}`;
                break;
            case 'damaged':
                icon = 'bi-exclamation-triangle-fill';
                iconClass = 'damaged';
                title = 'Damaged Book Fine';
                description = `Fine amount: ${formatCurrency(ruleData?.amount || 0)}`;
                break;
            case 'misplaced':
                icon = 'bi-question-circle-fill';
                iconClass = 'misplaced';
                title = 'Misplaced Book Fine';
                description = `Fine amount: ${formatCurrency(ruleData?.amount || 0)}`;
                break;
            case 'other':
                icon = 'bi-three-dots';
                iconClass = 'other';
                title = 'Other Fine';
                description = `Fine amount: ${formatCurrency(ruleData?.amount || 0)}`;
                break;
        }

        const fineCard = document.createElement('div');
        fineCard.id = fineId;
        fineCard.className = 'fine-type-card';
        fineCard.dataset.fineType = fineType;
        fineCard.dataset.amount = fineAmount;

        fineCard.innerHTML = `
            <div class="fine-type-header">
                <div class="fine-type-icon ${iconClass}">
                    <i class="bi ${icon}"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="fine-type-title">${title}</h6>
                    <div class="fine-type-desc">${description}</div>
                </div>
                <div class="fine-type-amount">${formatCurrency(fineAmount)}</div>
                ${fineType !== 'overdue' ? `
                    <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeFine('${fineId}')" style="padding: 2px 8px; font-size: 12px;">
                        <i class="bi bi-x">X</i>
                    </button>
                ` : ''}
            </div>
        `;

        appliedFinesContainer.appendChild(fineCard);
    }

    /**
     * Remove fine from applied fines - make it global
     */
    window.removeFine = function(fineId) {
        const fineCard = document.getElementById(fineId);
        if (fineCard) {
            const fineType = fineCard.dataset.fineType;
            const fineAmount = parseFloat(fineCard.dataset.amount);

            fineCard.remove();

            // Adjust total fine
            if (fineType === 'lost') {
                selectedFine -= (bookCost + fineAmount);
            } else {
                selectedFine -= fineAmount;
            }

            if (fineAmountEl) fineAmountEl.textContent = formatCurrency(selectedFine);

            const totalFineInput = document.getElementById('total_fine_amount');
            if (totalFineInput) totalFineInput.value = selectedFine;
            // Reset fine type select
            if (fineTypeSelect && fineType === fineTypeSelect.value) {
                fineTypeSelect.value = '';
                if (damageDescriptionSection) damageDescriptionSection.style.display = 'none';
                manualFineAmount = 0;
                manualFineType = '';
                useManualFine = 0;
                if (useManualFineInput) useManualFineInput.value = 0;
                if (fineAmountPreview) fineAmountPreview.innerHTML = '';
            }

            updateFineBreakdown();
            checkPaymentMethodVisibility();
        }
    };

    /**
     * Update fine breakdown table
     */
    function updateFineBreakdown() {
        if (!fineBreakdownContainer) return;

        if (selectedFine <= 0) {
            fineBreakdownContainer.innerHTML = '';
            return;
        }

        let breakdownHTML = `
            <div class="fine-breakdown">
                <h6 class="fine-breakdown-title">
                    <i class="bi bi-calculator"></i>
                    Fine Breakdown
                </h6>
                <table class="fine-breakdown-table">
        `;

        let totalFine = 0;

        // Get all applied fines
        const appliedFines = appliedFinesContainer ? appliedFinesContainer.querySelectorAll('.fine-type-card') :
            [];

        appliedFines.forEach(fine => {
            const fineType = fine.dataset.fineType;
            const amount = parseFloat(fine.dataset.amount);
            totalFine += amount;

            let fineName = '';
            switch (fineType) {
                case 'overdue':
                    fineName = 'Overdue Fine';
                    break;
                case 'lost':
                    fineName = 'Lost Book (Cost + Fine)';
                    break;
                case 'damaged':
                    fineName = 'Damaged Book Fine';
                    break;
                case 'misplaced':
                    fineName = 'Misplaced Book Fine';
                    break;
                case 'other':
                    fineName = 'Other Fine';
                    break;
                default:
                    fineName = fineType;
            }

            breakdownHTML += `
                <tr>
                    <td>${fineName}</td>
                    <td class="text-end">${formatCurrency(amount)}</td>
                </tr>
            `;
        });

        breakdownHTML += `
                <tr>
                    <td class="fw-bold">Total Fine Amount</td>
                    <td class="text-end fw-bold text-primary fs-6">${formatCurrency(totalFine)}</td>
                </tr>
            </table>
        </div>
        `;

        fineBreakdownContainer.innerHTML = breakdownHTML;
    }

    /**
     * Check and update payment method visibility
     */
    function checkPaymentMethodVisibility() {
        if (!paymentMethodContainer) return;

        if (selectedFine <= 0) {
            // No fine - show return button directly
            paymentMethodContainer.style.display = 'none';
            if (onlineOptionsContainer) onlineOptionsContainer.style.display = 'none';
            if (linkResult) linkResult.innerHTML = '';
            if (returnbtn) returnbtn.classList.remove('d-none');
            resetPaymentMethod();
        } else {
            // Has fine - show payment methods
            paymentMethodContainer.style.display = 'flex';
            if (returnbtn) returnbtn.classList.add('d-none');
        }
    }


    /**
     * Reset payment method selection
     */
    function resetPaymentMethod() {
        if (paymentMethodCards.length > 0) {
            paymentMethodCards.forEach(card => {
                card.classList.remove('selected');
            });
        }
        if (paymentMethodInput) paymentMethodInput.value = '';
        selectedPaymentMethod = '';
        if (onlineOptionsContainer) onlineOptionsContainer.style.display = 'none';
        if (linkResult) linkResult.innerHTML = '';
    }


    /**
     * Fetch book copy details
     */
    async function fetchBookCopyDetails(bookIssueId, copyId) {
        try {
            const response = await fetch('/library/get-copy-details', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    book_issue_id: bookIssueId,
                    copy_id: copyId
                })
            });

            const data = await response.json();
            if (data.success) {
                bookCost = parseFloat(data.copy.cost || 0);
                bookPages = parseInt(data.copy.pages || 0);
                bookCondition = data.copy.condition || '';
                bookWriter = data.copy.writer_name || '';
                bookPublishDate = data.copy.publishing_date || '';
                bookRack = data.copy.rack || '';
                bookShelf = data.copy.shelf || '';
                selectedBookCopy = data.copy;

                showBookCopyDetails(data.copy);
            }
            return data;
        } catch (error) {
            console.error('Error fetching copy details:', error);
            return {
                success: false
            };
        }
    }

    /**
     * Fetch fine amount from rules
     */
    async function fetchFineAmount(fineType, bookIssueId) {
        try {
            const response = await fetch('/library/get-fine-amount', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    fine_type: fineType,
                    book_issue_id: bookIssueId,
                    book_cost: bookCost,
                    book_pages: bookPages
                })
            });

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error fetching fine amount:', error);
            return {
                success: false,
                amount: 0,
                rule: null
            };
        }
    }

    // ============ EVENT HANDLERS ============

    // 1. Handle book selection
    if (radios.length > 0) {
        // ============ RADIO CHANGE HANDLER - COMPLETE FUNCTION ============
        radios.forEach(radio => {
            radio.addEventListener('change', async function() {
                // Reset everything
                document.querySelectorAll('.book-row').forEach(row => {
                    row.classList.remove('selected-row');
                });

                const row = this.closest('tr');
                if (row) row.classList.add('selected-row');

                // Clear previous data
                if (appliedFinesContainer) appliedFinesContainer.innerHTML = '';
                if (fineBreakdownContainer) fineBreakdownContainer.innerHTML = '';

                const bookCopyDetailsContainer = document.getElementById(
                    'bookCopyDetailsContainer');
                if (bookCopyDetailsContainer) bookCopyDetailsContainer.innerHTML = '';

                // Reset payment method
                resetPaymentMethod();

                // Clear payment method input
                if (paymentMethodInput) {
                    paymentMethodInput.value = '';
                }

                // Clear localStorage method
                localStorage.removeItem('method');

                // Hide fine summary initially until we have data
                if (fineSummary) fineSummary.classList.add('d-none');

                // Hide manual fine section
                if (manualFineSection) manualFineSection.classList.add('d-none');

                // Hide overdue fine info
                if (overdueFineInfo) overdueFineInfo.classList.add('d-none');

                // Hide online options
                if (onlineOptionsContainer) onlineOptionsContainer.style.display = 'none';

                // Clear link result
                if (linkResult) linkResult.innerHTML = '';

                // Hide return button
                if (returnbtn) returnbtn.classList.add('d-none');

                // Reset fine type select
                if (fineTypeSelect) fineTypeSelect.value = '';

                // Hide damage description
                if (damageDescriptionSection) damageDescriptionSection.style.display =
                    'none';

                // Clear fine amount preview
                if (fineAmountPreview) fineAmountPreview.innerHTML = '';

                // Reset state variables
                selectedPaymentMethod = '';
                manualFineAmount = 0;
                manualFineType = '';
                useManualFine = 0;
                if (useManualFineInput) useManualFineInput.value = 0;

                // Get data from the selected radio
                selectedFine = parseFloat(this.dataset.fine || 0);
                originalFineAmount = selectedFine; // Store the original fine amount
                selectedBookId = this.value;
                selectedBookTitle = this.dataset.title || '';
                selectedBookCopyId = this.dataset.copyId || '';
                bookCost = parseFloat(this.dataset.bookCost || 0);

                // Get the actual book issue ID from the data attribute
                actualBookIssueId = this.dataset.bookIssueId;

                // Check if this book has online payment already
                const hasPaidOnline = this.dataset.hasPaidOnline === '1';
                const paidAmount = parseFloat(this.dataset.paidAmount || 0);

                console.log('Book selected:', {
                    id: selectedBookId,
                    actual_issue_id: actualBookIssueId,
                    title: selectedBookTitle,
                    copy: selectedBookCopyId,
                    fine: selectedFine,
                    original_fine: originalFineAmount,
                    cost: bookCost,
                    has_paid_online: hasPaidOnline,
                    paid_amount: paidAmount
                });

                // If payment is already made online, store that info but KEEP THE FINE AMOUNT
                if (hasPaidOnline) {
                    localStorage.setItem('has_online_payment_' + actualBookIssueId, '1');
                    localStorage.setItem('paid_amount_' + actualBookIssueId, paidAmount);
                    localStorage.setItem('original_fine_' + actualBookIssueId,
                        selectedFine); // Store original fine

                    // IMPORTANT: Do NOT change selectedFine - keep the original fine amount
                    // This ensures the fine record will be created with the correct amount

                    console.log('Online payment detected - keeping fine amount:',
                        selectedFine);

                    // Auto-select online payment method and show that it's paid
                    if (paymentMethodCards.length > 0) {
                        // Find and click the online payment card
                        const onlineCard = Array.from(paymentMethodCards).find(card => card
                            .dataset.method === 'online');
                        if (onlineCard) {
                            setTimeout(() => {
                                onlineCard.click();

                                // Add a note that payment is already completed
                                if (linkResult) {
                                    linkResult.innerHTML = `
                                        <div class="alert alert-success">
                                            <i class="bi bi-check-circle-fill me-2"></i>
                                            <strong>Payment Already Completed</strong>
                                            <p class="mb-0 mt-1 small">This book has been paid online. Fine amount: ${formatCurrency(selectedFine)}. You can proceed with return.</p>
                                        </div>
                                    `;
                                }

                                // Show return button
                                if (returnbtn) {
                                    returnbtn.classList.remove('d-none');
                                    returnbtn.disabled = false;
                                }
                            }, 100);
                        }
                    }
                }

                // Set the hidden input in the return form
                if (selectedBookIssueId) {
                    selectedBookIssueId.value = this.value;
                }

                overdueDays = parseInt(this.dataset.days || 0);
                graceDays = parseInt(row ? row.dataset.graceDays || 0 : 0);
                hasOverdueFine = this.dataset.isOverdue === '1' || overdueDays > 0;
                overdueFineAmount = selectedFine;

                // Fetch book copy details
                if (selectedBookCopyId) {
                    await fetchBookCopyDetails(selectedBookId, selectedBookCopyId);
                }

                // Check if already returned
                if (row) {
                    const returnStatus = row.querySelector('td:last-child .status-badge')
                        ?.textContent.toLowerCase() || '';
                    if (returnStatus.includes('returned')) {
                        if (fineSummary) fineSummary.classList.add('d-none');
                        alert('This book is already returned.');
                        return;
                    }
                }

                // Update selected book info
                if (fineBook) {
                    fineBook.innerHTML = `
                        ${selectedBookTitle}
                        <small class="d-block text-muted mt-1" style="font-size: 12px;">
                            <code>Copy: ${selectedBookCopyId}</code>
                            ${bookCost > 0 ? `<span class="ms-2 badge bg-info" style="font-size: 11px;">Cost: ${formatCurrency(bookCost)}</span>` : ''}
                            ${hasPaidOnline ? `<span class="ms-2 badge bg-success" style="font-size: 11px;">Paid Online: ${formatCurrency(paidAmount)} (Fine: ${formatCurrency(selectedFine)})</span>` : ''}
                        </small>
                    `;
                }

                // Show fine summary
                if (fineSummary) fineSummary.classList.remove('d-none');

                // Handle overdue fine
                if (hasOverdueFine) {
                    if (overdueFineInfo) overdueFineInfo.classList.remove('d-none');
                    let overdueText = `Book is ${overdueDays} days overdue. `;

                    if (graceDays > 0) {
                        if (overdueDays <= graceDays) {
                            overdueText =
                                `Within grace period (${graceDays} days) - No fine applied.`;
                            overdueFineAmount = 0;
                            selectedFine = 0;
                        } else {
                            overdueText =
                                `${overdueDays} days overdue, ${graceDays} days grace period applied.`;
                        }
                    }

                    if (overdueFineDetails) overdueFineDetails.textContent = overdueText;

                    // Add overdue fine to applied fines
                    if (overdueFineAmount > 0) {
                        displayAppliedFine('overdue', overdueFineAmount, null);
                    }
                } else {
                    if (overdueFineInfo) overdueFineInfo.classList.add('d-none');
                }

                // Show manual fine section
                if (manualFineSection) manualFineSection.classList.remove('d-none');

                // Update total fine display
                if (fineAmountEl) {
                    fineAmountEl.textContent = formatCurrency(selectedFine);
                }

                // Update hidden total fine input
                const totalFineInput = document.getElementById('total_fine_amount');
                if (totalFineInput) {
                    totalFineInput.value = selectedFine;
                }

                // Check payment method visibility
                checkPaymentMethodVisibility();

                // Update fine breakdown
                updateFineBreakdown();

                // Clear any book-specific localStorage data from previous selections
                const keysToRemove = [];
                for (let i = 0; i < localStorage.length; i++) {
                    const key = localStorage.key(i);
                    if (key && (key.startsWith('book_') || key === 'current_book_id' ||
                            key === 'current_amount')) {
                        keysToRemove.push(key);
                    }
                }
                keysToRemove.forEach(key => localStorage.removeItem(key));

                console.log('Book selection completed. Final fine amount:', selectedFine);
            });
        });
    }

    // 2. Handle fine type change
    if (fineTypeSelect) {
        fineTypeSelect.addEventListener('change', async function() {
            const selectedFineType = this.value;

            if (!selectedFineType) {
                if (damageDescriptionSection) damageDescriptionSection.style.display = 'none';
                if (fineAmountPreview) fineAmountPreview.innerHTML = '';
                useManualFine = 0;
                if (useManualFineInput) useManualFineInput.value = 0;
                return;
            }

            // Show/hide damage description
            if (damageDescriptionSection) {
                damageDescriptionSection.style.display = selectedFineType === 'damaged' ? 'block' :
                    'none';
            }

            // Fetch fine amount
            this.disabled = true;
            if (fineAmountPreview) fineAmountPreview.innerHTML =
                '<span class="loading-spinner"></span> Loading...';

            try {
                const result = await fetchFineAmount(selectedFineType, selectedBookId);

                if (result.success && result.amount > 0) {
                    manualFineAmount = result.amount;
                    manualFineRule = result.rule;
                    manualFineType = selectedFineType;
                    useManualFine = 1;
                    if (useManualFineInput) useManualFineInput.value = 1;

                    let totalFineAmount = result.amount;
                    if (selectedFineType === 'lost') {
                        totalFineAmount = bookCost + result.amount;
                    }

                    if (fineAmountPreview) {
                        fineAmountPreview.innerHTML = `
                            <span class="text-warning fw-bold" style="font-size: 16px;">${formatCurrency(totalFineAmount)}</span>
                            <small class="d-block text-muted" style="font-size: 11px;">${result.calculation_details?.calculation || ''}</small>
                        `;
                    }

                    // Add to applied fines
                    displayAppliedFine(selectedFineType, totalFineAmount, result);

                    // Update total fine
                    selectedFine += totalFineAmount;
                    if (fineAmountEl) fineAmountEl.textContent = formatCurrency(selectedFine);

                    // Update hidden total fine input
                    const totalFineInput = document.getElementById('total_fine_amount');
                    if (totalFineInput) totalFineInput.value = selectedFine;

                    updateFineBreakdown();
                    checkPaymentMethodVisibility();
                } else {
                    alert(result.message || `No active fine rule found for ${selectedFineType}`);
                    this.value = '';
                    if (fineAmountPreview) fineAmountPreview.innerHTML = '';
                    useManualFine = 0;
                    if (useManualFineInput) useManualFineInput.value = 0;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error fetching fine amount. Please try again.');
                this.value = '';
                if (fineAmountPreview) fineAmountPreview.innerHTML = '';
                useManualFine = 0;
                if (useManualFineInput) useManualFineInput.value = 0;
            } finally {
                this.disabled = false;
            }
        });
    }

    // 3. Handle payment method selection
    if (paymentMethodCards.length > 0) {
        paymentMethodCards.forEach(card => {
            card.addEventListener('click', function(e) {
                e.preventDefault();

                const method = this.dataset.method;

                // Remove selected class from all cards
                paymentMethodCards.forEach(c => c.classList.remove('selected'));

                // Add selected class to current card
                this.classList.add('selected');

                // Set payment method based on selection
                selectedPaymentMethod = method;

                // Handle based on method
                if (method === 'offline') {
                    // Offline = Cash payment
                    if (paymentMethodInput) {
                        paymentMethodInput.value = 'cash';
                        console.log('Payment method set to: cash, value:', paymentMethodInput
                            .value);
                    }

                    // Store in localStorage
                    localStorage.setItem('method', 'cash');

                    // Hide online options
                    if (onlineOptionsContainer) onlineOptionsContainer.style.display = 'none';
                    if (linkResult) linkResult.innerHTML = '';

                    // Show return button immediately
                    if (returnbtn) {
                        returnbtn.classList.remove('d-none');
                        returnbtn.disabled = false;
                    }

                } else if (method === 'online') {
                    // Online payment
                    if (paymentMethodInput) {
                        paymentMethodInput.value = 'online';
                        console.log('Payment method set to: online, value:', paymentMethodInput
                            .value);
                    }

                    // Store in localStorage
                    localStorage.setItem('method', 'online');

                    // Check if this book already has an online payment
                    const hasOnlinePayment = localStorage.getItem('has_online_payment_' +
                        actualBookIssueId) === '1';

                    if (hasOnlinePayment) {
                        // Payment already made, get the original fine amount
                        const originalFine = localStorage.getItem('original_fine_' +
                            actualBookIssueId) || selectedFine;

                        // Make sure selectedFine is set to the original amount
                        if (selectedFine === 0 && originalFine > 0) {
                            selectedFine = parseFloat(originalFine);
                            if (fineAmountEl) fineAmountEl.textContent = formatCurrency(
                                selectedFine);
                            if (document.getElementById('total_fine_amount')) {
                                document.getElementById('total_fine_amount').value =
                                    selectedFine;
                            }
                        }

                        // Show success message with fine amount
                        if (linkResult) {
                            linkResult.innerHTML = `
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <strong>Payment Already Completed</strong>
                                    <p class="mb-0 mt-1 small">Fine amount: ${formatCurrency(selectedFine)}. You can proceed with return.</p>
                                </div>
                            `;
                        }

                        // Show return button
                        if (returnbtn) {
                            returnbtn.classList.remove('d-none');
                            returnbtn.disabled = false;
                        }

                        // Hide online options
                        if (onlineOptionsContainer) onlineOptionsContainer.style.display =
                            'none';
                    } else {
                        // For online, we'll show the online options
                        if (selectedFine > 0) {
                            if (onlineOptionsContainer) onlineOptionsContainer.style.display =
                                'block';
                            if (returnbtn) {
                                returnbtn.classList.add('d-none');
                            }
                        } else {
                            alert('No fine amount to pay online.');
                            this.classList.remove('selected');
                            if (paymentMethodInput) paymentMethodInput.value = '';
                            selectedPaymentMethod = '';
                        }
                    }

                } else if (method === 'waive-off') {
                    // Waive off
                    if (paymentMethodInput) {
                        paymentMethodInput.value = 'waive-off';
                        console.log('Payment method set to: waive-off, value:',
                            paymentMethodInput.value);
                    }

                    // Store in localStorage
                    localStorage.setItem('method', 'waive-off');

                    // Hide online options
                    if (onlineOptionsContainer) onlineOptionsContainer.style.display = 'none';
                    if (linkResult) linkResult.innerHTML = '';

                    // Show return button immediately
                    if (returnbtn) {
                        returnbtn.classList.remove('d-none');
                        returnbtn.disabled = false;
                    }
                }
            });
        });
    }

    // 4. Generate Payment Link - WITH UNIQUE TRANSACTION REFERENCE
    if (generateLinkBtn) {
        generateLinkBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const selectedRadio = document.querySelector('input[name="book_issue_id"]:checked');
            if (!selectedRadio) {
                alert("Please select a book first.");
                return;
            }

            const bookIssueTableId = selectedRadio.value;
            const actualBookIssueId = selectedRadio.dataset.bookIssueId;
            const bookTitle = selectedRadio.dataset.title || '';
            const copyId = selectedRadio.dataset.copyId || '';

            console.log('Table ID:', bookIssueTableId);
            console.log('Actual Book Issue ID:', actualBookIssueId);

            // ✅ Clear ONLY app-related localStorage keys
            Object.keys(localStorage).forEach(key => {
                if (key.startsWith('book_') || key.startsWith('current_')) {
                    localStorage.removeItem(key);
                }
            });

            // ✅ Verify clearing
            const remainingKeys = Object.keys(localStorage).filter(
                k => k.startsWith('book_') || k.startsWith('current_')
            );
            console.log(
                remainingKeys.length === 0 ?
                "✅ App storage cleared" :
                "⚠️ Still remaining:", remainingKeys
            );

            const storageKey = 'book_' + actualBookIssueId;

            // Store values
            localStorage.setItem(storageKey + '_amount', selectedFine);
            localStorage.setItem(storageKey + '_title', bookTitle);
            localStorage.setItem(storageKey + '_copy', copyId);

            // Backward compatibility
            localStorage.setItem('current_book_id', actualBookIssueId);
            localStorage.setItem('current_amount', selectedFine);

            console.log('✅ Stored in localStorage for book:', actualBookIssueId, 'amount:',
                selectedFine);

            if (!actualBookIssueId || selectedFine <= 0) {
                alert("Please select a valid book with fine amount first.");
                return;
            }

            if (selectedPaymentMethod !== 'online') {
                alert("Please select Online payment method first.");
                return;
            }

            let description =
                `Fine for Book Issue #${actualBookIssueId}: ${bookTitle}, Copy: ${copyId}`;

            generateLinkBtn.disabled = true;
            generateLinkBtn.innerHTML = '<span class="loading-spinner"></span> Generating...';

            const appliedFines = appliedFinesContainer ? appliedFinesContainer.querySelectorAll(
                '.fine-type-card') : [];
            appliedFines.forEach(fine => {
                const fineType = fine.dataset.fineType;
                if (fineType === 'lost') {
                    description += `, Lost Book - Cost: ${formatCurrency(bookCost)} + Fine`;
                } else if (fineType === 'damaged') {
                    description += `, Damaged Book`;
                } else if (fineType === 'overdue') {
                    description += `, Overdue (${overdueDays} days)`;
                }
            });

            fetch("{{ route('payment.link.create') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        amount: selectedFine,
                        user_transaction_refered_id: actualBookIssueId,
                        payment_type: "library-book-fine",
                        description: description,
                        metadata: {
                            book_issue_table_id: bookIssueTableId,
                            book_title: bookTitle,
                            copy_id: copyId
                        }
                    })
                })
                .then(async res => {
                    const text = await res.text();

                    try {
                        return JSON.parse(text);
                    } catch (err) {
                        console.error("🚨 Server did NOT return JSON:");
                        console.error(text); // shows actual HTML error page
                        throw new Error("Invalid JSON response");
                    }
                })
                .then(data => {
                    if (data.success) {
                        localStorage.setItem(storageKey + '_payment_link', data.payment_link);
                        localStorage.setItem(storageKey + '_payment_id', data.payment_id);

                        if (paymentMethodInput) paymentMethodInput.value = 'online';

                        if (linkResult) {
                            linkResult.innerHTML = `
                        <div class="payment-link-container" data-book-id="${actualBookIssueId}">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="badge bg-success me-2">✓</span>
                                    <strong>Payment Link Generated</strong>
                                    <small class="d-block text-muted mt-1">
                                        Amount: ${formatCurrency(selectedFine)} | ${bookTitle} | Copy: ${copyId}
                                    </small>
                                </div>
                                <a href="${data.payment_link}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>Open Link
                                </a>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted d-block">Share this link with the borrower:</small>
                                <div class="input-group input-group-sm mt-1">
                                    <input type="text" class="form-control" value="${data.payment_link}" readonly onclick="this.select()">
                                    <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText('${data.payment_link}').then(() => alert('Link copied!'))">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                        }

                        if (returnbtn) {
                            returnbtn.classList.remove('d-none');
                            returnbtn.disabled = false;
                        }
                    } else {
                        if (linkResult) {
                            linkResult.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            ${data.message || 'Error generating payment link'}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                        }
                    }
                })
                .catch(err => {
                    console.error('Payment link error:', err);
                    if (linkResult) {
                        linkResult.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-x-circle me-2"></i>
                        Error generating payment link. Please try again.
                    </div>
                `;
                    }
                })
                .finally(() => {
                    generateLinkBtn.disabled = false;
                    generateLinkBtn.innerHTML =
                        '<i class="bi bi-link-45deg me-2"></i>Generate Payment Link';
                });
        });
    }

    // Update the form submission handler
    if (returnForm) {
        returnForm.addEventListener('submit', function(e) {
            e.preventDefault();

            console.log('Return form submission triggered');

            const selectedRadio = document.querySelector('input[name="book_issue_id"]:checked');
            if (!selectedRadio) {
                alert('Please select a book to return');
                return false;
            }

            const bookIssueTableId = selectedRadio.value;
            const actualBookIssueId = selectedRadio.dataset.bookIssueId;

            // Make sure the hidden input has the value
            if (selectedBookIssueId) {
                selectedBookIssueId.value = bookIssueTableId;
            }

            const row = selectedRadio.closest('tr');
            const returnStatus = row ? row.querySelector('td:last-child .status-badge')?.textContent
                .toLowerCase() || '' : '';

            if (returnStatus.includes('returned') || returnStatus.includes('lost') || returnStatus
                .includes('damaged')) {
                alert('This book has already been processed');
                return false;
            }

            // Get book-specific data from localStorage
            const storageKey = 'book_' + actualBookIssueId;
            const storedAmount = localStorage.getItem(storageKey + '_amount');
            const storedPaymentMethod = localStorage.getItem('method');

            // Check if this book has an online payment already
            const hasOnlinePayment = localStorage.getItem('has_online_payment_' + actualBookIssueId) ===
                '1';

            // Get original fine if it exists (for online paid books)
            const originalFine = localStorage.getItem('original_fine_' + actualBookIssueId);

            // Determine the final fine amount - prioritize original fine for online paid books
            let finalFineAmount = 0;

            // Priority 1: Stored amount
            if (storedAmount && parseFloat(storedAmount) > 0) {
                finalFineAmount = parseFloat(storedAmount);
            }

            // Priority 2: If online payment exists, DO NOT override with 0
            if (hasOnlinePayment) {
                const paidAmt = localStorage.getItem('paid_amount_' + actualBookIssueId);
                if (paidAmt && parseFloat(paidAmt) > 0) {
                    finalFineAmount = parseFloat(paidAmt);
                }
            }

            // Fallback only if still zero
            if (finalFineAmount === 0 && selectedFine > 0) {
                finalFineAmount = selectedFine;
            }

            // Validate that we're processing the correct book
            const currentBookId = localStorage.getItem('current_book_id');
            if (currentBookId && currentBookId !== actualBookIssueId) {
                if (!confirm(
                        'Warning: The payment data in localStorage is for a different book. Continue anyway?'
                    )) {
                    return false;
                }
            }

            console.log('Processing return for book:', actualBookIssueId);
            console.log('Final fine amount to submit:', finalFineAmount);
            console.log('Selected payment method from UI:', selectedPaymentMethod);
            console.log('Stored payment method from localStorage:', storedPaymentMethod);
            console.log('Has online payment:', hasOnlinePayment);
            console.log('Payment method input value before setting:', paymentMethodInput ?
                paymentMethodInput.value : 'not found');

            // Set the form values - ALWAYS use the original fine amount, not 0
            document.getElementById('total_fine_amount').value = finalFineAmount;

            // IMPORTANT: Set payment method based on selection - PRIORITIZE selectedPaymentMethod from UI
            if (selectedPaymentMethod) {
                // Map UI method to actual payment method value
                let paymentMethodValue = '';
                if (selectedPaymentMethod === 'offline') {
                    paymentMethodValue = 'cash';
                } else if (selectedPaymentMethod === 'online') {
                    paymentMethodValue = 'online';
                } else if (selectedPaymentMethod === 'waive-off') {
                    paymentMethodValue = 'waive-off';
                }

                if (paymentMethodInput) {
                    paymentMethodInput.value = paymentMethodValue;
                    console.log('Setting payment method from UI selection:', paymentMethodValue);
                }
            } else if (storedPaymentMethod) {
                // Fallback to localStorage
                if (paymentMethodInput) {
                    paymentMethodInput.value = storedPaymentMethod;
                    console.log('Setting payment method from localStorage:', storedPaymentMethod);
                }
            } else if (hasOnlinePayment) {
                // If has online payment but no method selected, set to online
                if (paymentMethodInput) {
                    paymentMethodInput.value = 'online';
                    console.log('Setting payment method to online due to existing payment');
                }
            }

            console.log('Payment method input value AFTER setting:', paymentMethodInput ?
                paymentMethodInput.value : 'not found');

            // Validate payment method if fine exists
            if (finalFineAmount > 0) {
                // Check if payment method is set
                const finalPaymentMethod = paymentMethodInput ? paymentMethodInput.value : '';

                if (!finalPaymentMethod && !hasOnlinePayment) {
                    alert('Please select a payment method');
                    return false;
                }

                // For online payment, verify payment link exists OR payment was already made
                if (finalPaymentMethod === 'online' || hasOnlinePayment) {
                    const paymentLinkContainer = linkResult ? linkResult.querySelector(
                        '.payment-link-container[data-book-id="' + actualBookIssueId + '"]') : null;
                    const hasPaymentLink = paymentLinkContainer !== null;

                    // If no payment link but payment was made online, that's OK
                    if (!hasPaymentLink && !hasOnlinePayment) {
                        alert('Please generate a payment link for this specific book');
                        return false;
                    }
                }

                const hasDamagedFine = appliedFinesContainer ?
                    Array.from(appliedFinesContainer.querySelectorAll('.fine-type-card'))
                    .some(card => card.dataset.fineType === 'damaged') : false;

                if (hasDamagedFine) {
                    const damageDesc = document.querySelector('textarea[name="damage_description"]');
                    const pagesCount = document.querySelector('input[name="pages_count"]');

                    if (!damageDesc || !damageDesc.value.trim()) {
                        alert('Please provide damage description for damaged books');
                        return false;
                    }

                    if (!pagesCount || !pagesCount.value.trim()) {
                        alert('Please enter pages count for damaged books');
                        return false;
                    }

                    if (parseInt(pagesCount.value) <= 0) {
                        alert('Pages count must be greater than 0');
                        return false;
                    }
                }
            }

            console.log('Submitting return form with data:', {
                book_issue_id: selectedBookIssueId?.value,
                actual_book_issue_id: actualBookIssueId,
                total_fine_amount: document.getElementById('total_fine_amount').value,
                payment_method: paymentMethodInput ? paymentMethodInput.value : 'not set',
                fine_type: document.getElementById('fine_type')?.value,
                use_manual_fine: document.getElementById('use_manual_fine')?.value
            });

            // Submit the form
            this.submit();
            clearBookStorage(actualBookIssueId);
        });
    }

    // Update clear function to be book-specific
    function clearBookStorage(bookId) {
        const storageKey = 'book_' + bookId;
        const keys = [
            storageKey + '_amount',
            storageKey + '_title',
            storageKey + '_copy',
            storageKey + '_payment_link',
            storageKey + '_payment_id',
            'has_online_payment_' + bookId,
            'paid_amount_' + bookId,
            'original_fine_' + bookId,
            'current_book_id',
            'current_amount',
            'method'
        ];

        keys.forEach(key => localStorage.removeItem(key));
    }

    // Helper function for currency formatting if not already defined
    function formatCurrency(amount) {
        return '₹' + parseFloat(amount || 0).toFixed(2);
    }

    // 6. Sorting functionality
    window.sortTable = function(column) {
        document.querySelectorAll('.sort-icon').forEach(icon => {
            icon.classList.remove('active');
        });

        const th = event.currentTarget;
        const sortIcons = th.querySelectorAll('.sort-icon');

        let sortOrder = 'asc';
        if (sortIcons[0].classList.contains('active')) {
            sortOrder = 'desc';
            sortIcons[0].classList.remove('active');
            sortIcons[1].classList.add('active');
        } else if (sortIcons[1].classList.contains('active')) {
            sortOrder = 'asc';
            sortIcons[1].classList.remove('active');
            sortIcons[0].classList.add('active');
        } else {
            sortIcons[0].classList.add('active');
        }

        const tbody = document.querySelector('#issuedBooksContainer');
        if (!tbody) return;

        const rows = Array.from(tbody.querySelectorAll('tr'));

        rows.sort((a, b) => {
            let aValue = getCellValue(a, column);
            let bValue = getCellValue(b, column);

            if (column === 'fine_amount' || column === 'paid_amount') {
                aValue = parseFloat(aValue.replace(/[^\d.-]/g, '')) || 0;
                bValue = parseFloat(bValue.replace(/[^\d.-]/g, '')) || 0;
                return sortOrder === 'asc' ? aValue - bValue : bValue - aValue;
            } else if (column === 'issue_date' || column === 'due_date') {
                aValue = new Date(aValue);
                bValue = new Date(bValue);
                return sortOrder === 'asc' ? aValue - bValue : bValue - aValue;
            } else if (column === 'days_overdue') {
                aValue = parseInt(aValue) || 0;
                bValue = parseInt(bValue) || 0;
                return sortOrder === 'asc' ? aValue - bValue : bValue - aValue;
            } else {
                aValue = aValue.toLowerCase();
                bValue = bValue.toLowerCase();
                return sortOrder === 'asc' ? aValue.localeCompare(bValue) : bValue.localeCompare(
                    aValue);
            }
        });

        rows.forEach(row => tbody.appendChild(row));
    };

    function getCellValue(row, column) {
        if (!row || !row.cells) return '';

        const cells = row.cells;
        switch (column) {
            case 'title':
                return cells[1]?.querySelector('strong')?.textContent || cells[1]?.textContent || '';
            case 'copy_id':
                return cells[2]?.querySelector('.copy-id-badge')?.textContent || cells[2]?.textContent || '';
            case 'issued_to':
                return cells[3]?.querySelector('strong')?.textContent || cells[3]?.textContent || '';
            case 'issue_date':
                return cells[4]?.textContent || '';
            case 'due_date':
                return cells[5]?.querySelector('span')?.textContent || cells[5]?.textContent || '';
            case 'days_overdue': {
                const badge = cells[6]?.querySelector('.status-badge, .fw-medium');
                return badge ? badge.textContent.replace(/\D/g, '') : cells[6]?.textContent.replace(/\D/g,
                    '') || '0';
            }
            case 'fine_amount':
                return cells[7]?.textContent || '0';
            case 'payment_status':
                return cells[8]?.querySelector('.status-badge')?.textContent || cells[8]?.textContent || '';
            case 'status':
                return cells[9]?.querySelector('.status-badge')?.textContent || cells[9]?.textContent || '';
            default:
                return '';
        }
    }

    // ============ ACTIVE FILTERS DISPLAY ============
    function displayActiveFilters() {
        const urlParams = new URLSearchParams(window.location.search);
        const filters = [];

        if (urlParams.has('search') && urlParams.get('search')) {
            filters.push(`Search: "${urlParams.get('search')}"`);
        }
        if (urlParams.has('overdue_status') && urlParams.get('overdue_status')) {
            filters.push(
                `Overdue: ${urlParams.get('overdue_status') === 'overdue' ? 'Overdue' : 'Not Overdue'}`);
        }
        if (urlParams.has('fine_status') && urlParams.get('fine_status')) {
            filters.push(
                `Fine: ${urlParams.get('fine_status') === 'with_fine' ? 'With Fine' : 'Without Fine'}`);
        }
        if (urlParams.has('from_date') && urlParams.get('from_date')) {
            filters.push(`From: ${urlParams.get('from_date')}`);
        }
        if (urlParams.has('to_date') && urlParams.get('to_date')) {
            filters.push(`To: ${urlParams.get('to_date')}`);
        }

        if (filters.length > 0) {
            const filterBar = document.createElement('div');
            filterBar.className = 'd-flex flex-wrap gap-2 mt-3';
            filterBar.innerHTML = `
                <span class="text-muted me-2">Active Filters:</span>
                ${filters.map(f => `
                    <span class="filter-badge">
                        <i class="bi bi-funnel"></i> ${f}
                        <span class="remove" onclick="removeFilter('${f.split(':')[0].toLowerCase().trim()}')">×</span>
                    </span>
                `).join('')}
                <a href="{{ route('library.return.create') }}" class="filter-badge bg-danger text-white">
                    <i class="bi bi-x"></i> Clear All
                </a>
            `;

            const filterSection = document.querySelector('.card.mb-4');
            if (filterSection && !document.getElementById('activeFilters')) {
                filterSection.id = 'activeFilters';
                filterSection.appendChild(filterBar);
            }
        }
    }

    window.removeFilter = function(filterName) {
        const url = new URL(window.location.href);
        if (filterName === 'search') url.searchParams.delete('search');
        if (filterName === 'overdue') url.searchParams.delete('overdue_status');
        if (filterName.includes('fine')) url.searchParams.delete('fine_status');
        if (filterName === 'from') url.searchParams.delete('from_date');
        if (filterName === 'to') url.searchParams.delete('to_date');
        window.location.href = url.toString();
    };

    displayActiveFilters();

    // ============ INITIAL SETUP ============
    if (returnbtn) returnbtn.classList.add('d-none');
    if (generateLinkBtn) generateLinkBtn.disabled = false;

    // Hide payment method container initially
    if (paymentMethodContainer) paymentMethodContainer.style.display = 'none';

    console.log('Return Books JS initialized with real-time overdue calculation');
});
</script>
@endsection