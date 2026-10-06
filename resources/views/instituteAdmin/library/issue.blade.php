@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<title>Book Issue Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Modern Design System with New Colors */
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
        --dark: #1f2937;
        --gray: #6b7280;
        --light-bg: #f8fafc;
        --border: #e5e7eb;
    }

    /* Page Header */
    .page-header {
        background: var(--primary-gradient);
        border-radius: 20px;
        padding: 25px 30px;
        margin-bottom: 25px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.25);
        animation: slideDown 0.5s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
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
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    }

    .page-title i {
        font-size: 32px;
        filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.1));
    }

    /* Issue Form Card */
    .issue-form-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        transition: all 0.3s;
        border: 1px solid var(--border);
    }

    .issue-form-card:hover {
        box-shadow: 0 15px 40px rgba(67, 97, 238, 0.12);
        transform: translateY(-2px);
    }

    .issue-form-header {
        padding: 20px 25px;
        border-bottom: 2px solid var(--border);
        background: linear-gradient(135deg, #ffffff, #f8fafc);
    }

    .issue-form-header h2 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .issue-form-header h2 i {
        color: var(--primary-color);
        font-size: 1.5rem;
    }

    .issue-form-body {
        padding: 25px;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        margin-bottom: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-label .required {
        color: #ef4444;
        font-size: 1rem;
    }

    /* Select2 Styling */
    .select2-container--default .select2-selection--single {
        height: 48px;
        border: 2px solid var(--border);
        border-radius: 12px;
        background: white;
        transition: all 0.3s;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 48px;
        padding-left: 16px;
        font-size: 0.95rem;
        color: var(--dark);
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 48px;
        right: 12px;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: var(--primary-color);
        box-shadow: var(--accent-glow);
    }

    .select2-container--default .select2-selection--single:hover {
        border-color: var(--primary-color);
    }

    /* Book Info */
    .book-info {
        font-size: 0.85rem;
        color: var(--gray);
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        padding: 8px 12px;
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        /*border-radius: 10px;*/
        /*border-left: 3px solid var(--primary-color);*/
    }

    .available {
        color: var(--success-color);
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    /* Copies Table Container */
    .copies-table-container {
        margin-top: 20px;
        border: 1px solid var(--border);
        border-radius: 12px;
        /*overflow: hidden;*/
        display: none;
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .copies-table-container.active {
        display: block;
        animation: fadeInUp 0.4s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .copies-table {
        width: 100%;
        border-collapse: collapse;
    }

    .copies-table th {
        padding: 14px 16px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        font-weight: 700;
        color: var(--dark);
        text-align: left;
        border-bottom: 2px solid var(--border);
        font-size: 0.85rem;
    }

    .copies-table td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border);
        transition: all 0.2s;
    }

    .copies-table tbody tr {
        cursor: pointer;
        transition: all 0.2s;
    }

    .copies-table tbody tr:hover {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        transform: translateX(2px);
    }

    .copies-table tbody tr.selected {
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        border-left: 3px solid var(--primary-color);
    }

    .copy-select-radio {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: var(--primary-color);
    }

    .copy-id-badge {
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        color: var(--primary-color);
        padding: 6px 12px;
        border-radius: 8px;
        font-family: monospace;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        transition: all 0.2s;
    }

    .copy-id-badge:hover {
        transform: scale(1.02);
        box-shadow: var(--accent-glow);
    }

    /* No Copies Message */
    .no-copies {
        padding: 40px 20px;
        text-align: center;
        color: var(--gray);
        border: 2px dashed var(--border);
        border-radius: 12px;
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        margin-top: 15px;
    }

    .no-copies i {
        font-size: 48px;
        color: var(--gray);
        margin-bottom: 10px;
    }

    /* Person Type Cards */
    .person-type-cards {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 15px;
    }

    .person-type-card {
        border: 2px solid var(--border);
        border-radius: 16px;
        padding: 25px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        position: relative;
        overflow: hidden;
    }

    .person-type-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--primary-gradient);
        transform: scaleX(0);
        transition: transform 0.3s;
    }

    .person-type-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-4px);
        box-shadow: var(--accent-glow);
    }

    .person-type-card:hover::before {
        transform: scaleX(1);
    }

    .person-type-card.selected {
        border-color: var(--primary-color);
        background: linear-gradient(135deg, #eef2ff, #ffffff);
        box-shadow: var(--accent-glow);
    }

    .person-type-card i {
        font-size: 48px;
        margin-bottom: 12px;
        color: var(--gray);
        transition: all 0.3s;
    }

    .person-type-card.selected i {
        color: var(--primary-color);
    }

    .person-type-card h5 {
        margin: 0 0 8px 0;
        color: var(--dark);
        font-weight: 700;
        font-size: 1.1rem;
    }

    .person-type-card p {
        margin: 0;
        color: var(--gray);
        font-size: 0.85rem;
    }

    /* Person Search Box */
    .person-search-box {
        margin-top: 25px;
        display: none;
        animation: fadeInUp 0.4s ease;
    }

    .person-search-box.active {
        display: block;
    }

    .person-search-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .person-search-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .person-search-title i {
        color: var(--primary-color);
        font-size: 1.2rem;
    }

    .btn-outline-secondary {
        background: transparent;
        border: 1px solid var(--border);
        color: var(--gray);
        padding: 8px 16px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .btn-outline-secondary:hover {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
    }

    /* Person Search Input */
    .person-search-input {
        position: relative;
        margin-bottom: 15px;
    }

    .person-search-input .bi {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
        font-size: 1rem;
    }

    .person-search-input input {
        width: 100%;
        padding: 12px 12px 12px 42px;
        border: 2px solid var(--border);
        border-radius: 12px;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .person-search-input input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: var(--accent-glow);
    }

    /* Person List Container */
    .person-list-container {
        max-height: 350px;
        overflow-y: auto;
        border: 1px solid var(--border);
        border-radius: 12px;
        margin-top: 15px;
        display: none;
        background: white;
    }

    .person-list-container.active {
        display: block;
    }

    .person-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .person-list-item {
        padding: 15px 20px;
        border-bottom: 1px solid var(--border);
        cursor: pointer;
        transition: all 0.2s;
    }

    .person-list-item:hover {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        transform: translateX(4px);
    }

    .person-list-item.selected {
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        border-left: 4px solid var(--primary-color);
    }

    .person-name {
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 6px;
        font-size: 1rem;
    }

    .person-details {
        display: flex;
        gap: 15px;
        font-size: 0.8rem;
        color: var(--gray);
        flex-wrap: wrap;
    }

    .person-detail {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* No Persons Message */
    .no-persons {
        text-align: center;
        padding: 40px 20px;
        color: var(--gray);
    }

    .no-persons i {
        font-size: 48px;
        color: var(--gray);
        margin-bottom: 12px;
    }

    /* Date Input */
    .date-input {
        width: 100%;
        height: 48px;
        padding: 0 16px;
        border: 2px solid var(--border);
        border-radius: 12px;
        font-size: 0.95rem;
        background: white;
        transition: all 0.3s;
    }

    .date-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: var(--accent-glow);
    }

    /* Submit Button */
    .submit-btn {
        background: var(--primary-gradient);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 14px 32px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-top: 20px;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .submit-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
    }

    .submit-btn:disabled {
        background: linear-gradient(135deg, #94a3b8, #64748b);
        cursor: not-allowed;
        transform: none;
    }

    /* Filter Container */
    .filter-container {
        background: white;
        border-radius: 16px;
        padding: 20px 25px;
        margin-bottom: 25px;
        border: 1px solid var(--border);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
    }

    .filter-container:hover {
        box-shadow: 0 6px 16px rgba(67, 97, 238, 0.1);
    }

    .filter-form {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
    }

    .filter-grid {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        flex: 1;
    }

    .filter-group {
        position: relative;
        flex: 1;
        min-width: 230px;
    }

    .filter-group .bi {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary-color);
        z-index: 1;
        font-size: 1rem;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 10px 10px 10px 38px;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 0.9rem;
        background: white;
        transition: all 0.3s;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: var(--accent-glow);
    }

    .filter-group input:hover,
    .filter-group select:hover {
        border-color: var(--primary-color);
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
        font-size: 0.9rem;
        white-space: nowrap;
    }

    .btn-filter-primary {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 2px 8px rgba(67, 97, 238, 0.2);
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
        transform: translateY(-2px);
    }

    .btn-filter-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    }

    /* Bulk Actions Container */
    .bulk-actions-container {
        margin-bottom: 20px;
        display: none;
        align-items: center;
        flex-wrap: wrap;
        animation: slideDown 0.4s ease;
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        padding: 15px 20px;
        border-radius: 12px;
        border-left: 4px solid var(--primary-color);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .bulk-actions-container.active {
        display: flex;
    }

    .selected-count {
        font-weight: 600;
        color: var(--primary-color);
        margin-right: auto;
        font-size: 0.9rem;
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
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-right: 8px;
        color: white;
    }

    .bulk-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .bulk-action-btn.return {
        background: var(--success-gradient);
    }

    .bulk-action-btn.renew {
        background: var(--warning-gradient);
    }

    .bulk-action-btn.clear {
        background: linear-gradient(135deg, #64748b, #475569);
        color: white;
    }

    /* Card and Table Styles */
    .card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        /*overflow: hidden;*/
        margin-bottom: 20px;
    }

    .card-header {
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        border-bottom: 2px solid var(--border);
        padding: 20px 25px;
    }

    .card-header h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header h4 i {
        color: var(--primary-color);
        font-size: 1.3rem;
    }

    .card-body {
        padding: 0;
    }

    /* ERP Table */
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
        padding: 16px 20px;
        font-weight: 700;
        color: white;
        text-align: left;
        font-size: 0.85rem;
        cursor: pointer;
        user-select: none;
        transition: all 0.2s;
        position: relative;
    }

    .erp-table th:hover {
        background: rgba(67, 97, 238, 0.05);
    }

    .erp-table th.sortable {
        padding-right: 35px;
    }

    .sort-icons {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .sort-icon {
        color: #cbd5e1;
        font-size: 10px;
        line-height: 1;
    }

    .sort-icon.active {
        color: var(--primary-color);
    }

    .erp-table td {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        color: #334155;
        font-size: 0.85rem;
        vertical-align: middle;
    }

    .erp-table tbody tr {
        transition: all 0.3s;
    }

    .erp-table tbody tr:hover {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        transform: translateX(2px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        transition: all 0.3s;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .status-badge:hover {
        transform: scale(1.05);
    }

    .status-issued {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
    }

    .status-returned {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }

    .status-overdue {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
    }

    .status-reissued {
        background: linear-gradient(135deg, #fed7aa, #ffedd5);
        color: #92400e;
    }

    /* Copy Details */
    .copy-details {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .copy-id-badge {
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        color: var(--primary-color);
        padding: 4px 10px;
        border-radius: 6px;
        font-family: monospace;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        width: fit-content;
    }

    /* Checkbox Styling */
    .select-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
        border-radius: 5px;
        border: 2px solid #cbd5e1;
        transition: all 0.2s;
        accent-color: var(--primary-color);
    }

    .select-checkbox:hover {
        border-color: var(--primary-color);
        transform: scale(1.1);
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

    .loading-container {
        display: none;
        text-align: center;
        padding: 20px;
    }

    .loading-container.active {
        display: block;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--gray);
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border-radius: 16px;
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

    /* Book Count Badge */
    .book-count {
        display: inline-block;
        background: var(--primary-gradient);
        color: white;
        font-size: 0.75rem;
        padding: 3px 10px;
        border-radius: 30px;
        margin-left: 10px;
        font-weight: 500;
    }

    /* Text Utilities */
    .text-primary {
        color: var(--primary-color) !important;
    }

    .text-success {
        color: var(--success-color) !important;
    }

    .text-danger {
        color: #dc2626 !important;
    }

    .text-secondary {
        color: var(--gray) !important;
    }

    .text-xs {
        font-size: 0.7rem;
    }

    .font-medium {
        font-weight: 500;
    }

    .small {
        font-size: 0.75rem;
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .filter-grid {
            flex-direction: column;
            gap: 15px;
        }
        
        .filter-group {
            min-width: 100%;
        }
        
        .filter-actions {
            width: 100%;
            justify-content: flex-end;
        }
        
        .person-type-cards {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 15px;
        }
        
        .page-header {
            padding: 20px;
        }
        
        .page-title {
            font-size: 22px;
        }
        
        .issue-form-body {
            padding: 20px;
        }
        
        .bulk-actions-container {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }
        
        .bulk-actions-container .d-flex {
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .bulk-action-btn {
            width: 100%;
            justify-content: center;
        }
        
        .erp-table th,
        .erp-table td {
            padding: 12px 15px;
        }
        
        .person-details {
            flex-direction: column;
            gap: 5px;
        }
    }

    .filter-container h6 {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    } 

    .date-group {
        position: relative;
    }

    .floating-label {
        position: absolute;
        top: -8px;
        left: 35px;
        background: white;
        padding: 0 6px;
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        z-index: 2;
    }

    /* Adjust icon spacing */
    .date-group i {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
    }

    .date-group .filter-input {
        padding-left: 35px;
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
    .table-responsive{
        overflow-x: auto;
    }
</style>

<div id="pageLoader">
    <div class="spinner"></div>
</div>


<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-book"></i>
            Book Issue Management
        </h1>
    </div>

    <!-- Issue Form Card -->
    <div class="issue-form-card">
        <div class="issue-form-header">
            <h2><i class="bi bi-arrow-up-circle"></i> Issue New Book</h2>
        </div>
        <div class="issue-form-body">
            <form method="POST" action="{{ route('library.issue.store') }}" id="issueForm">
                @csrf
                
                <!-- Book Selection -->
                <div class="form-group">
                    <label class="form-label">Select Book <span class="required">*</span></label>
                    <select name="librarybook_id" id="librarybook_id" class="select2" required>
                        <option value="">Select a book</option>
                        @foreach($books as $book)
                            <option value="{{ $book->librarybook_id }}" 
                                    data-book-id="{{ $book->librarybook_id }}"
                                    data-copies="{{ $book->available_copies ?? 0 }}"
                                    data-total="{{ $book->total_copies }}">
                                {{ $book->title }} (ID: {{ $book->librarybook_id }})
                            </option>
                        @endforeach
                    </select>
                    <div class="book-info">
                        <span class="available" id="available-copies">Select a book to see available copies</span>
                    </div>
                </div>

                <!-- Copy Selection Table (hidden initially) -->
                <div class="custom-table-wrapper" id="tableWrapper">
                    <div class="copies-table-container" id="copy-selection">
                        <table class="copies-table">
                            <thead>
                                <tr>
                                    <th width="40">Select</th>
                                    <th>Copy ID</th>
                                    <th>Writer Name</th>
                                    <th>Condition</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="copies-table-body">
                                <!-- Copies will be loaded here via AJAX -->
                            </tbody>
                        </table>
                        <div id="no-copies-message" class="no-copies" style="display: none;">
                            <i class="bi bi-exclamation-circle"></i>
                            <p class="mt-2 mb-0">No available copies for this book</p>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="copy_id" id="selected_copy_id" required>

                <!-- Department Selection -->
                <div class="form-group">
                    <label class="form-label">Select Department <span class="required">*</span></label>
                    <select name="department_id" id="department_id" class="select2" required>
                        <option value="">Select department</option>
                        @if(isset($departments) && $departments->count() > 0)
                            @foreach($departments as $department)
                                <option value="{{ $department->department_id }}">
                                    {{ $department->department }}
                                    @if($department->branch)
                                        (Branch: {{ $department->branch->branch_name }})
                                    @endif
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Person Type Selection (Cards) -->
                <div class="form-group">
                    <label class="form-label">Issue To <span class="required">*</span></label>
                    <div class="person-type-cards" id="person-type-cards" style="display: none;">
                        <div class="person-type-card" data-type="student">
                            <i class="bi bi-person-video3"></i>
                            <h5>Student</h5>
                            <p>Issue to a student</p>
                        </div>
                        <div class="person-type-card" data-type="employee">
                            <i class="bi bi-person-badge"></i>
                            <h5>Employee</h5>
                            <p>Issue to staff/faculty</p>
                        </div>
                    </div>
                    <input type="hidden" name="issueable_type" id="issueable_type" value="">
                    <input type="hidden" name="issueable_id" id="issueable_id" value="">
                </div>

                <!-- Person Search Box (Initially Hidden) -->
                <div class="person-search-box" id="person-search-box">
                    <div class="person-search-header">
                        <div class="person-search-title">
                            <i class="bi bi-search"></i>
                            <span id="search-title">Search</span>
                        </div>
                        <button type="button" class="btn-outline-secondary" id="change-type-btn">
                            <i class="bi bi-arrow-left-right"></i> Change Type
                        </button>
                    </div>
                    
                    <div class="person-search-input">
                        <i class="bi bi-search"></i>
                        <input type="text" id="person-search" placeholder="Search by name, registration number, employee code...">
                    </div>
                    
                    <div class="loading-container" id="person-loading">
                        <span class="loading-spinner"></span> Loading...
                    </div>
                    
                    <div class="person-list-container" id="person-list-container">
                        <ul class="person-list" id="person-list">
                            <!-- Persons will be loaded here -->
                        </ul>
                    </div>
                    
                    <div class="no-persons" id="no-persons" style="display: none;">
                        <i class="bi bi-people"></i>
                        <p class="mt-2">No persons found. Try a different search.</p>
                    </div>
                </div>

                <!-- Due Date -->
                <div class="form-group">
                    <label class="form-label">Due Date <span class="required">*</span></label>
                    <input type="date" name="due_date" id="due_date" class="date-input" required>
                </div>

                <button type="submit" class="submit-btn" id="submit-btn" disabled>
                    <i class="bi bi-check-circle"></i> Issue Book
                </button>
            </form>
        </div>
    </div>

    
<!-- Filters -->
<div class="filter-container">
   <h6><i class="bi bi-funnel-fill"></i> *Select or Type to Search</h6> 
    <form method="GET" id="filterForm" class="filter-form">
        <div class="filter-grid">
            {{-- Book Filter --}}
            <div class="filter-group">
                <i class="bi bi-book"></i>
                <input type="search" name="book_id" list="bookList" class="filter-input auto-filter"
                    value="{{ request('book_id') }}" placeholder="Search Book...">
                <datalist id="bookList">
                    @foreach($books as $book)
                    <option value="{{ $book->librarybook_id }}">
                        {{ $book->title }}
                    </option>
                    @endforeach
                </datalist>
            </div>

            {{-- Copy Filter --}}
            <div class="filter-group">
                <i class="bi bi-upc-scan"></i>
                <input type="search" name="copy_id" list="copyList" class="filter-input auto-filter"
                    value="{{ request('copy_id') }}" placeholder="Search Copy ID...">
                <datalist id="copyList">
                    @foreach($issuedBooks->unique(function($item) { return $item->copy->copy_id ?? null; }) as $issue)
                        @if($issue->copy && $issue->copy->copy_id)
                            <option value="{{ $issue->copy->copy_id }}">
                                {{ $issue->copy->copy_id }}
                            </option>
                        @endif
                    @endforeach
                </datalist>
            </div>

            {{-- Student / Employee Filter --}}
            <div class="filter-group">
                <i class="bi bi-person"></i>
                    <input type="search" name="person_search" class="filter-input auto-filter"
                        placeholder="Search Student / Employee (Name / ID)"
                        value="{{ trim(request('person_search')) }}">
            </div>

            {{-- Issued Date Filter --}}
            <!-- <div class="filter-group">
                <i class="bi bi-calendar"></i>
                <input type="date" name="issue_date" class="filter-input auto-filter"
                    value="{{ request('issue_date') }}" placeholder="Issue Date">
            </div> -->

            {{-- Due Date From Filter --}}
            <div class="filter-group date-group">
                <label class="floating-label">Issue Date</label>
                <i class="bi bi-calendar-event"></i>
                <input type="date" name="issue_date" 
                    class="filter-input auto-filter"
                    value="{{ request('issue_date') }}">
            </div>

            {{-- Due Date To Filter --}}
            <div class="filter-group date-group">
                <label class="floating-label">Due Date To</label>
                <i class="bi bi-calendar-check"></i>
                <input type="date" name="due_date" 
                    class="filter-input auto-filter"
                    value="{{ request('due_date') }}">
            </div>

            <div class="filter-actions">
                <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary">
                    <i class="bi bi-x-circle"></i>
                    Reset Filter
                </a>
            </div>
        </div>
    </form>
</div>

    <!-- Bulk Actions Container -->
    <div class="bulk-actions-container d-none" id="bulkActionsContainer">
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
            <button class="bulk-action-btn clear" onclick="clearSelection()">
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
                <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                    <table class="erp-table">
                        <thead>
                            <tr>
                                <th class="sticky-checkbox" width="40">
                                    <input type="checkbox" id="selectAll" class="select-checkbox">
                                </th>             
                                <th class="sticky-main sortable">
                                    Book
                                    <div class="sort-icons">
                                        <i class="sort-icon bi bi-caret-up-fill"></i>
                                        <i class="sort-icon bi bi-caret-down-fill"></i>
                                    </div>
                                </th>
                                <th class="sortable">
                                    Copy Details
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($issuedBooks as $issuedBook)
                            <tr>
                                <td class="sticky-checkbox">
                                    <input type="checkbox" class="book-checkbox select-checkbox" value="{{ $issuedBook->librarybook_id }}">
                                </td>
                                <td class="sticky-main">
                                    <strong>{{ $issuedBook->libraryBook->title ?? 'N/A' }}</strong>
                                    <div class="book-info" style="margin-top: 5px; padding: 0;">
                                        <span class="small text-primary">ID: {{ $issuedBook->libraryBook->librarybook_id ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <!-- Copy Details -->
                                    @if($issuedBook->copy)
                                        <div class="copy-details">
                                            <span class="copy-id-badge">
                                                <i class="bi bi-upc-scan"></i> {{ $issuedBook->copy->copy_id }}
                                            </span>
                                            @if($issuedBook->copy->writer_name)
                                                <div class="small text-secondary mt-1">
                                                    <i class="bi bi-pencil"></i> {{ $issuedBook->copy->writer_name }}
                                                </div>
                                            @endif
                                            @if($issuedBook->copy->condition)
                                                <div class="small text-secondary">
                                                    <i class="bi bi-info-circle"></i> Condition: 
                                                    <span style="
                                                        @if($issuedBook->copy->condition == 'New') color: #059669;
                                                        @elseif($issuedBook->copy->condition == 'Good') color: #0284c7;
                                                        @elseif($issuedBook->copy->condition == 'Fair') color: #b45309;
                                                        @elseif($issuedBook->copy->condition == 'Poor') color: #b91c1c;
                                                        @endif
                                                    ">
                                                        {{ $issuedBook->copy->condition }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small">Copy details not available</span>
                                    @endif
                                </td>
                                <td>
                                    @if($issuedBook->issueable_type == 'App\Models\StudentParentDetails')
                                        <div class="font-medium">{{ $issuedBook->issueable->first_name ?? 'N/A' }} 
                                        {{ $issuedBook->issueable->last_name ?? '' }}</div>
                                        <div class="small text-primary">
                                            <i class="bi bi-person-video3"></i> Student • {{ $issuedBook->issueable->registration_number ?? 'N/A' }}
                                        </div>
                                    @elseif($issuedBook->issueable_type == 'App\Models\EmployeeDetails')
                                        <div class="font-medium">{{ $issuedBook->issueable->name ?? 'N/A' }}</div>
                                        <div class="small text-primary">
                                            <i class="bi bi-person-badge"></i> Employee • {{ $issuedBook->issueable->employee_code ?? 'N/A' }}
                                        </div>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($issuedBook->issue_date)->format('d M Y') }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($issuedBook->due_date)->format('d M Y') }}
                                    @if($issuedBook->status == 'overdue')
                                        <div class="text-xs text-danger font-medium mt-1">
                                            <i class="bi bi-exclamation-triangle"></i> Overdue
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $issuedBook->status }}">
                                        {{ ucfirst($issuedBook->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
            @endif
            <!-- Floating Horizontal Scrollbar -->
            <div class="table-scroll-top d-none" id="tableScrollTop">
                <div class="table-scroll-inner"></div>
            </div>
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

function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}

// ✅ AUTO SUBMIT FOR ALL FILTER INPUTS
$(document).on('input change', '.auto-filter', function () {
    clearTimeout(window.filterTimer);

    window.filterTimer = setTimeout(() => {
        showLoader();
        $('#filterForm').submit();
    }, 400); // debounce (avoid too many requests)
});

// Sync person search with hidden inputs for filter
// $(document).ready(function() {
//     // Handle person selection from datalist for filter
//     $('#person_search').on('change', function() {
//         const selectedName = this.value;
//         const options = document.querySelectorAll('#issueableList option');
        
//         let selectedId = '';
//         let selectedType = '';
        
//         options.forEach(option => {
//             if (option.value === selectedName) {
//                 selectedId = option.dataset.id;
//                 selectedType = option.dataset.type;
//             }
//         });
        
//         $('#issueable_id_filter').val(selectedId);
//         $('#issueable_type_filter').val(selectedType);
        
//         if (selectedId !== '') {
//             clearTimeout(window.filterTimer);
//             window.filterTimer = setTimeout(() => {
//                 $('#filterForm').submit();
//             }, 300);
//         }
//     });
    
//     // Restore selected person on page load
//     const issueableId = $('#issueable_id_filter').val();
//     if (issueableId) {
//         const options = document.querySelectorAll('#issueableList option');
//         options.forEach(option => {
//             if (option.dataset.id == issueableId) {
//                 $('#person_search').val(option.value);
//             }
//         });
//     }
// });

// ✅ Restore selected person on page load
function restoreSelectedPerson() {
    const selectedId = $('#issueable_id').val();
    if (!selectedId) return;

    const options = document.querySelectorAll('#issueableList option');
    options.forEach(option => {
        if (option.dataset.id === selectedId) {
            $('#person_search').val(option.value);
        }
    });
}

$(document).ready(function () {
    restoreSelectedPerson();
});

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
    $('.select2').select2({
        placeholder: 'Select an option',
        allowClear: false,
        width: '100%'
    });

    // Variables from old file
    let selectedPersonType = '';
    let selectedPersonId = '';
    let selectedPersonName = '';
    let currentDepartmentId = '';

    // Load copies when book is selected (FROM OLD FILE)
    $('#librarybook_id').on('change', function() {
        const bookId = $(this).val();
        const $copySection = $('#copy-selection');
        const $copiesTableBody = $('#copies-table-body');
        const $noCopiesMessage = $('#no-copies-message');
        
        if (!bookId) {
            $copySection.removeClass('active');
            $('#available-copies').text('Select a book to see available copies');
            $('#selected_copy_id').val('');
            validateForm();
            return;
        }
        
        // Show loading
        $copiesTableBody.html(
            `<tr>
                <td colspan="5" class="text-center py-4">
                    <span class="loading-spinner"></span> Loading copies...
                </td>
            </tr>`
        );
        $copySection.addClass('active');
        $noCopiesMessage.hide();
        
        // Update available copies info
        const selectedOption = $(this).find('option:selected');
        const availableCopies = selectedOption.data('copies');
        const totalCopies = selectedOption.data('total');
        const libraryBookId = selectedOption.data('book-id');
        
        $('#available-copies').text(`${availableCopies} of ${totalCopies} copies available`);
        
        // Load copies via AJAX
        $.ajax({
            url: `/library/books/${libraryBookId}/available-copies`,
            method: 'GET',
            success: function(response) {
                console.log('Copies response:', response);
                if (response.success && response.copies.length > 0) {
                    renderCopiesTable(response.copies);
                } else {
                    $copiesTableBody.empty();
                    $noCopiesMessage.show();
                }
                validateForm();
            },
            error: function(xhr, status, error) {
                console.error('Error loading copies:', error);
                $copiesTableBody.html(
                    `<tr>
                        <td colspan="5" class="text-center text-danger py-4">
                            Error loading copies: ${error}
                        </td>
                    </tr>`
                );
                validateForm();
            }
        });
    });

    // Render copies in table (FROM OLD FILE)
    function renderCopiesTable(copies) {
        const $copiesTableBody = $('#copies-table-body');
        $copiesTableBody.empty();
        
        if (copies.length === 0) {
            $copiesTableBody.html(
                `<tr>
                    <td colspan="5" class="text-center py-4">
                        No available copies found
                    </td>
                </tr>`
            );
            return;
        }
        
        copies.forEach(copy => {
            const copyRow = `
                <tr data-copy-id="${copy.copy_id}">
                    <td>
                        <input type="radio" name="copy_radio" class="copy-select-radio" value="${copy.copy_id}">
                    </td>
                    <td>
                        <span class="copy-id-badge">${copy.copy_id}</span>
                    </td>
                    <td>${copy.writer_name || 'N/A'}</td>
                    <td>${copy.condition || 'N/A'}</td>
                    <td>
                        <span style="background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                            Available
                        </span>
                    </td>
                </tr>
            `;
            $copiesTableBody.append(copyRow);
        });
        
        // Add click handler for copy rows
        $('tr[data-copy-id]').on('click', function(e) {
            // Don't trigger if clicking on radio button
            if (!$(e.target).is('input')) {
                const $radio = $(this).find('.copy-select-radio');
                $radio.prop('checked', true);
                selectCopy($radio.val());
            }
        });
        
        // Add change handler for radio buttons
        $('.copy-select-radio').on('change', function() {
            selectCopy($(this).val());
        });
    }

    // Handle copy selection (FROM OLD FILE)
    function selectCopy(copyId) {
        // Remove selection from all rows
        $('tr[data-copy-id]').removeClass('selected');
        
        // Add selection to selected row
        $(`tr[data-copy-id="${copyId}"]`).addClass('selected');
        
        // Update hidden input
        $('#selected_copy_id').val(copyId);
        
        validateForm();
    }

    // Department change handler (FROM NEW FILE)
    $('#department_id').on('change', function() {
        currentDepartmentId = $(this).val();
        
        // Reset person selection
        resetPersonSelection();
        
        // Show person type cards only if department is selected
        if (currentDepartmentId) {
            $('#person-type-cards').show();
        } else {
            $('#person-type-cards').hide();
            $('#person-search-box').removeClass('active');
        }
        
        validateForm();
    });

    // Person type card selection (FROM NEW FILE)
    $('.person-type-card').on('click', function() {
        if (!currentDepartmentId) {
            alert('Please select a department first');
            return;
        }
        
        // Remove selection from all cards
        $('.person-type-card').removeClass('selected');
        
        // Add selection to clicked card
        $(this).addClass('selected');
        
        // Set person type
        selectedPersonType = $(this).data('type');
        $('#issueable_type').val(selectedPersonType === 'student' 
            ? 'App\\Models\\StudentParentDetails' 
            : 'App\\Models\\EmployeeDetails');
            
        // Show search box
        $('#person-search-box').addClass('active');
        
        // Update search title
        $('#search-title').text('Search ' + (selectedPersonType === 'student' ? 'Students' : 'Employees'));
        
        // Clear and focus search
        $('#person-search').val('').focus();
        
        // Load persons
        loadPersons('');
    });

    // Change type button (FROM NEW FILE)
    $('#change-type-btn').on('click', function() {
        $('#person-search-box').removeClass('active');
        $('.person-type-card').removeClass('selected');
        selectedPersonType = '';
        selectedPersonId = '';
        selectedPersonName = '';
        $('#issueable_type').val('');
        $('#issueable_id').val('');
        validateForm();
    });

    // Person search input handler (FROM NEW FILE)
    $('#person-search').on('input', function() {
        const searchTerm = $(this).val();
        loadPersons(searchTerm);
    });

    // Load persons function (FROM NEW FILE)
    function loadPersons(searchTerm = '') {
        if (!selectedPersonType || !currentDepartmentId) return;
        
        // Show loading
        $('#person-list').html(`
            <li class="person-list-item text-center py-4">
                <span class="loading-spinner"></span> Loading...
            </li>
        `);
        $('#person-list-container').addClass('active');
        $('#no-persons').hide();
        $('#person-loading').addClass('active');
        
        // Determine endpoint
        const endpoint = selectedPersonType === 'student' 
            ? '/ajax/students-by-department' 
            : '/ajax/employees-by-department';
        
        // Make AJAX request
        $.ajax({
            url: endpoint,
            method: 'GET',
            data: {
                department_id: currentDepartmentId,
                search: searchTerm
            },
            success: function(response) {
                $('#person-list').empty();
                $('#person-loading').removeClass('active');
                
                let persons = [];
                if (response.success) {
                    persons = selectedPersonType === 'student' 
                        ? (response.students || [])
                        : (response.employees || []);
                }
                
                if (persons.length === 0) {
                    $('#person-list-container').removeClass('active');
                    $('#no-persons').show();
                    return;
                }
                
                // Render persons
                persons.forEach(person => {
                    let displayName = '';
                    let details = [];
                    let personId = ''; // This will store the ID we need to send
                    
                    if (selectedPersonType === 'student') {
                        // Student - Use the primary key (id)
                        displayName = `${person.first_name || ''} ${person.middle_name || ''} ${person.last_name || ''}`.trim();
                        personId = person.id; // Use simple ID, not hash ID
                        
                        if (person.registration_number) {
                            details.push(`<span class="person-detail"><i class="bi bi-card-text"></i> ${person.registration_number}</span>`);
                        }
                        if (person.class_name) {
                            details.push(`<span class="person-detail"><i class="bi bi-book"></i> ${person.class_name}</span>`);
                        }
                    } else {
                        // Employee - Use primary key (id)
                        displayName = person.name || '';
                        personId = person.id; // Use simple ID
                        
                        if (person.employee_code) {
                            details.push(`<span class="person-detail"><i class="bi bi-card-text"></i> ${person.employee_code}</span>`);
                        }
                        if (person.designation && person.designation !== 'N/A') {
                            details.push(`<span class="person-detail"><i class="bi bi-briefcase"></i> ${person.designation}</span>`);
                        }
                    }
                    
                    const listItem = `
                        <li class="person-list-item" 
                            data-id="${personId}"
                            data-name="${displayName}">
                            <div class="person-name">${displayName}</div>
                            <div class="person-details">
                                ${details.join('')}
                            </div>
                        </li>
                    `;

                    $('#person-list').append(listItem);
                });
                
                // Add click handlers
                $('.person-list-item').on('click', function() {
                    $('.person-list-item').removeClass('selected');
                    $(this).addClass('selected');
                    
                    selectedPersonId = $(this).data('id');
                    selectedPersonName = $(this).data('name');
                    
                    $('#issueable_id').val(selectedPersonId);
                    
                    // Debug - log the selected ID
                    console.log('Selected person ID (simple ID):', selectedPersonId);
                    console.log('Selected person type:', selectedPersonType);
                    
                    // Update search input with selected person
                    $('#person-search').val(selectedPersonName);
                    
                    validateForm();
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading persons:', error);
                $('#person-list').html(`
                    <li class="person-list-item text-center text-danger py-4">
                        Error loading data. Please try again.
                    </li>
                `);
                $('#person-loading').removeClass('active');
            }
        });
    }

    // Reset person selection (FROM NEW FILE)
    function resetPersonSelection() {
        $('.person-type-card').removeClass('selected');
        $('#person-search-box').removeClass('active');
        selectedPersonType = '';
        selectedPersonId = '';
        selectedPersonName = '';
        $('#issueable_type').val('');
        $('#issueable_id').val('');
        $('#person-search').val('');
        $('#person-list').empty();
        $('#person-list-container').removeClass('active');
        $('#no-persons').hide();
        $('#person-loading').removeClass('active');
    }

    // Set default due date (FROM OLD FILE)
    const today = new Date();
    const nextWeek = new Date(today);
    nextWeek.setDate(today.getDate() + 7);
    const nextWeekStr = nextWeek.toISOString().split('T')[0];
    $('#due_date').val(nextWeekStr);
    $('#due_date').attr('min', today.toISOString().split('T')[0]);

    // Form validation (FROM OLD FILE with updates)
    function validateForm() {
        const bookSelected = $('#librarybook_id').val() !== '';
        const copySelected = $('#selected_copy_id').val() !== '';
        const departmentSelected = currentDepartmentId !== '';
        const personTypeSelected = selectedPersonType !== '';
        const personSelected = selectedPersonId !== '';
        const dueDate = $('#due_date').val();
        
        // Check if all required fields are filled
        const isValid = bookSelected && copySelected && departmentSelected && 
                       personTypeSelected && personSelected && dueDate;
        
        // Enable/disable submit button
        $('#submit-btn').prop('disabled', !isValid);
        
        return isValid;
    }

    // Validate form on any change (FROM OLD FILE)
    $('#librarybook_id, #due_date').on('change', validateForm);
    $('#selected_copy_id').on('change', validateForm);

    // Form submission (FROM OLD FILE)
    $('#issueForm').on('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            alert('Please fill all required fields');
            return false;
        }
        
        // Show loading on submit button
        $('#submit-btn').prop('disabled', true).html('<span class="loading-spinner"></span> Processing...');
    });

    // Filter form submission (FROM OLD FILE)
    $('#filterForm').on('submit', function(e) {
        const submitBtn = $(this).find('.btn-filter-primary');
        submitBtn.prop('disabled', true).html('<span class="loading-spinner"></span> Applying...');
    });

    // Bulk Action Functions (FROM OLD FILE)
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
});
</script>
@endsection