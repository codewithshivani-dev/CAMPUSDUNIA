@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Transport Fee Payments</title>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
<!-- Flatpickr Datepicker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- jsPDF for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<!-- html2canvas for capturing HTML as image -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --primary: #4361ee;
    --primary-light: #e6eeff;
    --primary-lighter: #f0f4ff;
    --success: #10b981;
    --success-light: #d1fae5;
    --info: #3b82f6;
    --info-light: #dbeafe;
    --warning: #f59e0b;
    --warning-light: #fef3c7;
    --danger: #ef4444;
    --danger-light: #fee2e2;
    --purple: #8b5cf6;
    --purple-light: #ede9fe;
    --teal: #0d9488;
    --teal-light: #ccfbf1;
    --dark: #1f2937;
    --light: #f9fafb;
    --border: #e5e7eb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
}

/* Header Section */
.dashboard-header {
    background: linear-gradient(135deg, var(--primary) 0%, #3a56d4 100%);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.dashboard-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle at 100% 0%, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    pointer-events: none;
}

.header-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.header-subtitle {
    font-size: 1rem;
    opacity: 0.9;
    margin-bottom: 1.5rem;
}

/* Stats Cards */
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 12px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: transform 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.stat-icon-primary {
    background: var(--primary-lighter);
    color: var(--primary);
}

.stat-icon-success {
    background: var(--success-light);
    color: var(--success);
}

.stat-icon-danger {
    background: var(--danger-light);
    color: var(--danger);
}

.stat-icon-purple {
    background: var(--purple-light);
    color: var(--purple);
}

.stat-icon-teal {
    background: var(--teal-light);
    color: var(--teal);
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.85rem;
    color: var(--gray-500);
    font-weight: 500;
}

/* Filter Section */
.filter-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border: 1px solid var(--border);
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.filter-group {
    margin-bottom: 0;
}

.filter-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--dark);
    margin-bottom: 0.5rem;
    display: block;
}

.filter-select,
.filter-input {
    width: 100%;
    padding: 0.625rem 0.875rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 0.875rem;
    background: white;
    transition: all 0.2s ease;
}

.filter-select:focus,
.filter-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.filter-buttons {
    display: flex;
    margin-top: 1rem;
    padding-top: 1rem;
}

.filter-btn {
    padding: 0.625rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-apply {
    background: var(--primary);
    color: white;
    border: 1px solid var(--primary);
}

.btn-apply:hover {
    background: #3a56d4;
    border-color: #3a56d4;
    transform: translateY(-1px);
}

.btn-reset {
    background: white;
    color: var(--dark);
    border: 1px solid var(--border);
}

.btn-reset:hover {
    background: var(--gray-100);
    border-color: var(--gray-300);
}

/* Table Design */
.academic-table {
    background: white;
    border-radius: 12px;
    /*overflow: hidden;*/
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid var(--border);
}

/*.table-header {*/
/*    background: linear-gradient(135deg, var(--dark) 0%, var(--gray-600) 100%);*/
/*    color: white;*/
/*}*/

/*.table-header th {*/
/*    font-weight: 500;*/
/*    padding: 1rem 1.25rem;*/
/*    border: none;*/
/*    font-size: 0.85rem;*/
/*    text-transform: uppercase;*/
/*    letter-spacing: 0.5px;*/
/*    white-space: nowrap;*/
/*}*/

/*.table-body tr {*/
/*    border-bottom: 1px solid var(--border);*/
/*    transition: all 0.2s ease;*/
/*}*/

/*.table-body tr:hover {*/
/*    background-color: var(--primary-light);*/
/*}*/

/*.table-body td {*/
/*    padding: 1rem 1.25rem;*/
/*    vertical-align: middle;*/
/*    border: none;*/
/*}*/

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

/* Student Info Column */
.student-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.student-name {
    font-weight: 600;
    color: var(--dark);
    font-size: 0.95rem;
}

.duration-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    background: var(--primary-lighter);
    color: var(--primary);
    width: fit-content;
}

/* Academic Info Column */
.academic-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    min-width: 220px;
}

.department {
    font-size: 0.75rem;
    color: var(--gray-500);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.course-name {
    font-weight: 600;
    color: var(--dark);
    font-size: 0.95rem;
}

.academic-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.25rem;
}

.meta-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.2rem 0.5rem;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 500;
}

.batch-tag {
    background: var(--primary-lighter);
    color: var(--primary);
}

.year-tag {
    background: var(--success-light);
    color: var(--success);
}

.semester-tag {
    background: var(--purple-light);
    color: var(--purple);
}

.section-tag {
    background: var(--gray-100);
    color: var(--gray-600);
}

/* Mode Info */
.mode-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.mode-type {
    font-weight: 600;
    color: var(--dark);
    font-size: 0.9rem;
}

.mode-badges {
    display: flex;
    gap: 0.375rem;
}

.mode-badge {
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 500;
}

.full-time-badge {
    background: var(--success-light);
    color: var(--success);
}

.offline-badge {
    background: var(--info-light);
    color: var(--info);
}

/* Amount Columns */
.amount-column {
    text-align: right;
    min-width: 110px;
}

.amount-display {
    font-weight: 700;
    font-size: 1rem;
    color: var(--dark);
}

.amount-label {
    font-size: 0.75rem;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
    font-weight: 600;
}

.fee-amount {
    color: var(--primary);
}

.late-fee-container {
    text-align: right;
}

.late-fee-amount {
    font-weight: 600;
    color: var(--danger);
}

.no-late-fee {
    color: var(--gray-400);
    font-style: italic;
    font-size: 0.85rem;
}

.discount-container {
    text-align: right;
}

.discount-amount {
    font-weight: 600;
    color: var(--success);
}

.no-discount {
    color: var(--gray-400);
    font-style: italic;
    font-size: 0.85rem;
}

.payable-amount {
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--dark);
    background: var(--primary-lighter);
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    display: inline-block;
    border: 2px solid var(--primary);
}

.date-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.date-group {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
}

.date-label {
    font-size: 0.7rem;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.date-value {
    font-size: 0.85rem;
    color: var(--dark);
    font-weight: 500;
    background: var(--gray-100);
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    border: 1px solid var(--border);
}

.status-column {
    min-width: 100px;
}

.status-badge {
    padding: 0.35rem 0.875rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.status-paid {
    background-color: var(--success-light);
    color: var(--success);
}

.status-pending {
    background-color: #fef3c7;
    color: #d97706;
}

.status-overdue {
    background-color: var(--danger-light);
    color: var(--danger);
}

.updated-info {
    font-size: 0.75rem;
    color: var(--gray-500);
    display: flex;
    align-items: center;
    gap: 0.375rem;
    min-width: 120px;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.action-btn {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: white;
    color: var(--gray-500);
    transition: all 0.2s ease;
}

.action-btn:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
    transform: translateY(-1px);
}

/* Payment Modal Styles */
.payment-modal {
    max-width: 500px;
}

.payment-details {
    background: var(--gray-100);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
}

.payment-detail-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border);
}

.payment-detail-row:last-child {
    border-bottom: none;
}

.payment-amount-display {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary);
    text-align: center;
    margin: 1rem 0;
}

.payment-method-options {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
    margin: 1.5rem 0;
}

.payment-method-btn {
    padding: 1rem;
    border: 2px solid var(--border);
    border-radius: 8px;
    background: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
    cursor: pointer;
}

.payment-method-btn:hover {
    border-color: var(--primary);
    background: var(--primary-light);
}

.payment-method-btn.active {
    border-color: var(--primary);
    background: var(--primary-light);
}

.payment-method-icon {
    font-size: 1.5rem;
    color: var(--primary);
}

.emi-options {
    margin-top: 1rem;
    padding: 1rem;
    background: var(--gray-100);
    border-radius: 8px;
    display: none;
}

.emi-option {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: 6px;
    margin-bottom: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.emi-option:hover {
    background: var(--primary-light);
}

.emi-option.active {
    border-color: var(--primary);
    background: var(--primary-light);
}

.table-footer {
    background: white;
    padding: 1rem 1.25rem;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.records-count {
    font-size: 0.875rem;
    color: var(--gray-500);
}

.pagination-buttons {
    display: flex;
    gap: 0.5rem;
}

.pagination-btn {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: white;
    color: var(--dark);
    font-weight: 500;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.pagination-btn:hover:not(:disabled) {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--gray-500);
}

.empty-icon {
    font-size: 3rem;
    color: var(--gray-300);
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Receipt Modal Styles */
.receipt-modal {
    max-width: 800px;
}

.receipt-container {
    padding: 0;
}

.receipt-paper {
    background: white;
    padding: 40px;
    font-family: 'Courier New', monospace;
    max-width: 210mm;
    margin: 0 auto;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

.receipt-header {
    text-align: center;
    margin-bottom: 30px;
    border-bottom: 3px double #333;
    padding-bottom: 20px;
}

.institute-name {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 5px;
    color: #2c3e50;
}

.receipt-title {
    font-size: 22px;
    font-weight: bold;
    color: #2c3e50;
    margin: 15px 0;
    text-transform: uppercase;
}

.receipt-body {
    margin: 30px 0;
}

.receipt-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px dashed #ddd;
}

.receipt-row.header {
    font-weight: bold;
    background: #f8f9fa;
    padding: 10px 0;
    border-bottom: 2px solid #333;
}

.receipt-label {
    flex: 1;
    font-weight: 500;
}

.receipt-value {
    flex: 2;
    text-align: right;
}

.amount-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin: 30px 0;
    border: 1px solid #dee2e6;
}

.amount-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
}

.amount-total {
    font-size: 20px;
    font-weight: bold;
    border-top: 2px solid #333;
    padding-top: 10px;
    margin-top: 10px;
}

.receipt-footer {
    margin-top: 40px;
    text-align: center;
    border-top: 3px double #333;
    padding-top: 20px;
}

.signature-section {
    display: flex;
    justify-content: space-between;
    margin: 40px 0;
}

.signature-box {
    text-align: center;
    flex: 1;
    padding: 0 20px;
}

.signature-line {
    width: 200px;
    height: 1px;
    background: #333;
    margin: 30px auto 10px;
}

.terms {
    font-size: 12px;
    color: #7f8c8d;
    margin-top: 20px;
    text-align: left;
}

.watermark {
    position: absolute;
    opacity: 0.1;
    font-size: 120px;
    transform: rotate(-45deg);
    top: 30%;
    left: 10%;
    color: #333;
    pointer-events: none;
}

.receipt-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-top: 30px;
    padding: 20px;
    border-top: 1px solid #dee2e6;
    background: #f8f9fa;
}

@media print {
    body * {
        visibility: hidden;
    }

    .receipt-paper,
    .receipt-paper * {
        visibility: visible;
    }

    .receipt-paper {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none;
    }

    .receipt-actions {
        display: none;
    }
}

.receipt-preview {
    max-height: 600px;
    overflow-y: auto;
    margin-bottom: 20px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
}


.date-range-wrapper {
    display: flex;
    gap: 12px;
    align-items: center;
}

.date-field {
    position: relative;
    width: 100%;
}

.date-field input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    background: #fff;
    font-size: 14px;
}

/* Floating Label */
.date-field label {
    position: absolute;
    top: 50%;
    left: 12px;
    transform: translateY(-50%);
    background: #fff;
    padding: 0 4px;
    font-size: 12px;
    color: #6b7280;
    pointer-events: none;
    transition: 0.2s ease;
}

/* Move label up when focused or has value */
.date-field input:focus+label,
.date-field input:not(:placeholder-shown)+label {
    top: 3px;
    font-size: 11px;
    color: #2563eb;
}

/* Focus style */
.date-field input:focus {
    border-color: #2563eb;
    outline: none;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
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
    white-space: nowrap;
    font-size: 14px;
}

.btn-filter:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    color: white !important;
    text-decoration: none !important;
}

.btn-filter:active {
    transform: translateY(-1px);
}

.btn-filter-secondary {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}

.btn-filter-secondary:hover {
    background: #e2e8f0;
}

.btn-apply {
    background: var(--primary);
    color: white;
    border: 1px solid var(--primary);
}

.btn-apply:hover {
    background: #3a56d4;
    border-color: #3a56d4;
    transform: translateY(-1px);
}

.btn-reset {
    background: white;
    color: var(--dark);
    border: 1px solid var(--border);
}

.btn-reset:hover {
    background: var(--gray-100);
    border-color: var(--gray-300);
}

.table-responsive{
    overflow-x: hidden;
}
</style>

<div id="pageLoader">
    <div class="spinner"></div>
</div>

<div class="container-fluid">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="header-title">
                    <i class="bi bi-bus-front-fill me-2"></i>Transport Fee Payments
                </h1>
                <p class="header-subtitle">Track and manage all Transport fee payments across departments</p>
            </div>
        </div>
        <!-- Stats Cards -->
        <!-- At the top of the stats section, add this check -->
        @php
        $totalPayable = 0;
        $totalPaidCount = 0;
        $totalOverdueCount = 0;
        $totalDiscountAll = 0;
        $totalOriginalAll = 0;

        if($paginator && count($paginator) > 0) {
            foreach($paginator as $student) {
                $totalOriginalAll += $student['total_original_fee'] ?? 0;
                $totalDiscountAll += $student['total_discount'] ?? 0;
                $totalPayable += $student['total_payable'] ?? 0;
    
                foreach($student['installments'] as $inst) {
                    if($inst['payment_status'] == 'paid') {
                        $totalPaidCount++;
                    } else {
                        $dueDate = \Carbon\Carbon::parse($inst['due_date']);
                        $today = \Carbon\Carbon::today();
                        if($dueDate->lt($today)) {
                            $totalOverdueCount++;
                        }
                    }
                }
            }
        }
        @endphp
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon stat-icon-primary">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ count($paginator ) }}</div>
                    <div class="stat-label">Total Students</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon-success">
                    <i class="bi bi-currency-rupee"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">₹{{ number_format($totalPayable, 2) }}</div>
                    <div class="stat-label">Total Payable</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon-purple">
                    <i class="bi bi-tag"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">₹{{ number_format($totalDiscountAll, 2) }}</div>
                    <div class="stat-label">Total Discount</div>
                    <div class="stat-label small">(Applied per month)</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $totalOverdueCount }}</div>
                    <div class="stat-label">Overdue Payments</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    @php
    $courses = array_unique(array_column(array_values($paginator->items()), 'course'));
    $batches = array_unique(array_column(array_values($paginator->items()), 'batch'));
    @endphp

    <div class="filter-container">
        <form method="GET">
            <div class="filter-section">
                <div class="filter-row">

                    <div class="filter-group">
                        <label class="filter-label">Department</label>
                        <input list="departmentsList" name="department" value="{{ request('department') }}"
                            class="filter-input" placeholder="Search Department">

                        <input type="hidden" name="department_id" id="department_id_hidden"
                            value="{{ request('department_id') }}">

                        <datalist id="departmentsList">
                            @foreach($departments as $dept)
                            <option value="{{ $dept->department }}" data-id="{{ $dept->department_id }}"></option>
                            @endforeach
                        </datalist>

                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Course</label>
                        <input list="courseList" name="course" value="{{ request('course') }}"
                            class="filter-input auto-submit">

                        <datalist id="courseList">
                            @foreach($courses as $course)
                            <option value="{{ $course }}">
                                @endforeach
                        </datalist>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Batch</label>
                        <input list="batchList" name="batch" value="{{ request('batch') }}"
                            class="filter-input auto-submit">

                        <datalist id="batchList">
                            @foreach($batches as $batch)
                            <option value="{{ $batch }}">
                                @endforeach
                        </datalist>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Academic Year</label>
                        <select class="filter-select auto-submit" name="academic_year">
                            <option value="">Select Academic Year</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" {{ (request('academic_year') == $year || (isset($academicYearFilter) && $academicYearFilter == $year)) ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Payment Status</label>
                        <select class="filter-select auto-submit" name="payment_status">
                            <option value="">All Status</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid
                            </option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>
                                Pending</option>
                            <option value="overdue" {{ request('payment_status') == 'overdue' ? 'selected' : '' }}>
                                Overdue</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Search Student</label>
                        <input type="text" name="student_search" value="{{ request('student_search') }}"
                            class="filter-input auto-submit" placeholder="Search student">
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Payment Type</label>
                        <input list="paymentTypeList" name="payment_type" value="{{ request('payment_type') }}"
                            class="filter-input auto-submit">

                        <datalist id="paymentTypeList">
                            <option value="online">
                            <option value="cash">
                            <option value="bank">
                            <option value="emi">
                        </datalist>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Mode Type</label>
                        <input list="modeTypeList" name="mode_type" value="{{ request('mode_type') }}"
                            class="filter-input auto-submit">

                        <datalist id="modeTypeList">
                            <option value="full_time">
                            <option value="part_time">
                        </datalist>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Date Range</label>

                        <div class="date-range-wrapper">

                            <!-- Start Date -->
                            <div class="date-field">
                                <input type="date" name="start_date" value="{{ request('start_date') }}"
                                    class="filter-input auto-submit" placeholder=" ">
                                <label>Start Date</label>
                            </div>

                            <!-- End Date -->
                            <div class="date-field">
                                <input type="date" name="end_date" value="{{ request('end_date') }}"
                                    class="filter-input auto-submit" placeholder=" ">
                                <label>End Date</label>
                            </div>

                        </div>
                    </div>
                <div class="filter-buttons">
                    <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary"
                        style="color:#64748b !important;text-decoration:none">
                        <i class="bi bi-x-circle"></i>
                        Reset Filters
                    </a>
                </div>
                </div>

            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="academic-table">
        <div class="table-responsive custom-table-wrapper" id="tableWrapper">
            <table class="erp-table table table-hover mb-0">
                <thead class="table-header">
                    <tr>
                        <th class="sticky-main-2 sortable">STUDENT</th>
                        <th class="sortable">ACADEMIC DETAILS</th>
                        <th class="sortable">MODE</th>
                        <th class="sortable">TRANSPORT STOP</th>
                        <th class="sortable">FEE AMOUNT</th>
                        <th class="sortable">LATE FEE</th>
                        <th class="sortable">DISCOUNT</th>
                        <th class="sortable">PAYABLE</th>
                        <th class="sortable">DATES / STATUS</th>
                        <th class="sortable">UPDATED</th>
                        <th class="sortable">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="table-body" id="paymentsTableBody">
                    @forelse($paginator as $student)
                    @php
                    $current = $student['current_installment'];
                    $hasCurrent = !is_null($current);
                    $dueDate = $hasCurrent ? \Carbon\Carbon::parse($current['due_date']) : null;
                    $status = $hasCurrent ? ($current['payment_status'] === 'paid' ? 'paid' : ($dueDate &&
                    $dueDate->lt(\Carbon\Carbon::today()) ? 'overdue' : 'pending')) : 'no_installments';
                    @endphp
                    <tr class="payment-row" data-student-hash="{{ $student['student_hash_id'] }}">
                        <!-- Student Column -->
                        <td class="sticky-main-2">
                            <div class="student-info">
                                <div class="student-name">{{ $student['student_name'] }}</div>
                                <div class="duration-badge">
                                    <i class="bi bi-person-badge"></i> {{ $student['student_reg'] }}
                                </div>
                                @if($hasCurrent && isset($current['fee_duration_type']))
                                <div class="duration-badge mt-1">{{ $current['fee_duration_type'] }}</div>
                                @endif
                            </div>
                        </td>

                        <!-- Academic Details Column -->
                        <td>
                            <div class="academic-info">
                                <div class="department">{{ $student['department'] }}</div>
                                <div class="course-name">{{ $student['course'] }}</div>
                                <div class="academic-meta">
                                    <span class="meta-tag batch-tag"><i class="bi bi-calendar-week"></i>
                                        {{ $student['batch'] }}</span>
                                    <span class="meta-tag year-tag"><i class="bi bi-calendar"></i>
                                        {{ $student['academic_year'] }}</span>
                                    <span class="meta-tag semester-tag"><i class="bi bi-journal"></i>
                                        {{ $student['semester'] }}</span>
                                    <span class="meta-tag section-tag"><i class="bi bi-people"></i>
                                        {{ $student['section'] }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Mode Column -->
                        <td>
                            <div class="mode-info">
                                <div class="mode-type">{{ ucfirst($student['mode_type']) }}</div>
                                <span class="mode-badge offline-badge">{{ ucfirst($student['mode_of_course']) }}</span>
                            </div>
                        </td>

                        <!-- Transport Stop -->
                        <td>
                            <div class="transport-stop">
                                <i class="bi bi-geo-alt-fill text-primary me-1"></i>
                                {{ $hasCurrent && isset($current['transport_stop']) ? $current['transport_stop'] : $student['transport_stop'] ?? 'Not Assigned' }}
                            </div>
                        </td>

                        <!-- Current Fee Amount -->
                        <td class="amount-column">
                            <div class="amount-display fee-amount">
                                ₹{{ $hasCurrent ? number_format($current['fee_amount'], 2) : '0.00' }}
                            </div>
                        </td>

                        <!-- Current Late Fee -->
                        <td class="amount-column">
                            <div class="amount-label">Late Fee</div>
                            <div class="late-fee-container">
                                @if($hasCurrent && $current['late_fee'] > 0)
                                <div class="late-fee-amount">+₹{{ number_format($current['late_fee'], 2) }}</div>
                                @else
                                <div class="no-late-fee">No late fee</div>
                                @endif
                            </div>
                        </td>

                        <!-- Current Discount -->
                        <td class="amount-column">
                            <div class="amount-label">Discount</div>
                            <div class="discount-container">
                                @if($hasCurrent && $current['discount'] > 0)
                                <div class="discount-amount">-₹{{ number_format($current['discount'], 2) }}</div>
                                @else
                                <div class="no-discount">No discount</div>
                                @endif
                            </div>
                        </td>

                        <!-- Current Payable -->
                        <td class="amount-column">
                            <div class="amount-label">Payable</div>
                            <div class="payable-amount">
                                @php
                                $payableAmount = 0;
                                if($hasCurrent) {
                                $payableAmount = ($current['fee_amount'] ?? 0) +
                                ($current['late_fee'] ?? 0) -
                                ($current['discount'] ?? 0);
                                }
                                @endphp
                                ₹{{ number_format($payableAmount, 2) }}
                            </div>
                        </td>

                        <!-- Due Date & Status -->
                        <td>
                            <div class="date-info">
                                <div class="date-group">
                                    <div class="date-label">Due Date</div>
                                    <div class="date-value">
                                        {{ $hasCurrent ? \Carbon\Carbon::parse($current['due_date'])->format('d M Y') : 'N/A' }}
                                    </div>
                                </div>
                                <div class="status-column mt-2">
                                    @if($status == 'paid')
                                    <span class="status-badge status-paid"><i class="bi bi-check-circle"></i>
                                        Paid</span>
                                    @elseif($status == 'overdue')
                                    <span class="status-badge status-overdue"><i class="bi bi-exclamation-triangle"></i>
                                        Overdue</span>
                                    @elseif($status == 'pending')
                                    <span class="status-badge status-pending"><i class="bi bi-clock"></i> Pending</span>
                                    @else
                                    <span class="status-badge status-none"><i class="bi bi-dash"></i> No fee</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Updated At -->
                        <td>
                            <div class="updated-info">
                                <i class="bi bi-clock-history"></i>
                                {{ $hasCurrent ? \Carbon\Carbon::parse($current['updated_at'])->diffForHumans() : 'N/A' }}
                            </div>
                        </td>

                        <!-- Actions -->
                        <td>
                            <div class="action-buttons"
                                style="display: flex; flex-direction: column; gap: 8px; min-width: 120px;">
                                @if($hasCurrent && $current['payment_status'] != 'paid')
                                <button class="btn btn-sm btn-success w-100"
                                    title="Click to record payment for this installment"
                                    onclick="recordPayment('{{ $student['student_hash_id'] }}', {{ $current['id'] }})">
                                    <i class="bi bi-cash-coin me-2"></i>Collect Fee
                                </button>
                                @endif

                                <button class="btn btn-sm btn-info w-100 text-white"
                                    title="View complete payment history"
                                    onclick="window.open('{{ route('admin.transport.fee.installments', ['student_hash' => trim($student['student_hash_id']), 'academic_year' => request('academic_year')]) }}', '_blank')">
                                    <i class="bi bi-clock-history me-2"></i>View More
                                </button>

                                @if($hasCurrent && $current['payment_status'] == 'paid')
                                <button class="btn btn-sm btn-primary w-100" title="View payment receipt"
                                    onclick="viewReceipt('{{ $student['student_hash_id'] }}', {{ $current['id'] }})">
                                    <i class="bi bi-receipt me-2"></i>View Receipt
                                    <small class="d-block" style="font-size: 10px;">
                                        Paid:
                                        {{ \Carbon\Carbon::parse($current['pay_date'] ?? $current['due_date'])->format('d M Y') }}
                                    </small>
                                </button>
                                @endif

                                @if(!$hasCurrent)
                                <div class="alert alert-warning py-2 px-3 mb-0" style="font-size: 12px;">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    No current installment
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="empty-state">
                            <div class="empty-icon"><i class="bi bi-bus-front"></i></div>
                            <h4 class="text-muted mb-2">No transport fee records found</h4>
                            <p class="text-muted">Start by adding transport fee for students</p>
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
        <!-- Table Footer -->
        <!-- Table Footer with Pagination -->
        @if($paginator->total() > 0)
        <div class="table-footer">
            <div class="records-count">
                Showing <span>{{ $paginator->firstItem() }}</span> to <span>{{ $paginator->lastItem() }}</span>
                of <span>{{ $paginator->total() }}</span> students
            </div>
            <div class="pagination-buttons">
                @if ($paginator->onFirstPage())
                <button class="pagination-btn" disabled>
                    <i class="bi bi-chevron-left"></i> Previous
                </button>
                @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn"
                    onclick="showLoader(); return true;">
                    <i class="bi bi-chevron-left"></i> Previous
                </a>
                @endif

                <div class="pagination-info">
                    Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
                </div>

                @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn" onclick="showLoader(); return true;">
                    Next <i class="bi bi-chevron-right"></i>
                </a>
                @else
                <button class="pagination-btn" disabled>
                    Next <i class="bi bi-chevron-right"></i>
                </button>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered payment-modal">
        <div class="modal-content" style="width: 650px;">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Record Transport Fee Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="payment-details">
                    <h6 id="studentNameDisplay"></h6>
                    <div class="payment-detail-row">
                        <span>Course:</span>
                        <span id="courseDisplay"></span>
                    </div>
                    <div class="payment-detail-row">
                        <span>Transport Stop:</span>
                        <span id="transportStopDisplay"></span>
                    </div>
                    <div class="payment-detail-row">
                        <span>Fee Amount:</span>
                        <span id="feeAmountDisplay"></span>
                    </div>
                    <div class="payment-detail-row">
                        <span>Late Fee:</span>
                        <span id="lateFeeDisplay"></span>
                    </div>
                    <div class="payment-detail-row">
                        <span>Discount:</span>
                        <span id="discountDisplay"></span>
                    </div>
                    <div class="payment-detail-row">
                        <span>Payable Amount:</span>
                        <strong id="payableAmountDisplay"></strong>
                    </div>
                </div>

                <div class="payment-amount-display" id="finalAmountDisplay"></div>

                <div class="mb-3">
                    <label class="form-label">Payment Date</label>
                    <input type="date" class="form-control" id="paymentDate" value="{{ date('Y-m-d') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Transaction ID (Optional)</label>
                    <input type="text" class="form-control" id="transactionId"
                        placeholder="Enter transaction ID or leave empty for auto-generation">
                    <small class="text-muted">Leave empty to auto-generate a unique transaction ID</small>
                </div>

                <div class="mb-3">
                    <label class="form-label mb-3">Select Payment Method</label>
                    <div class="payment-method-options">
                        <div class="payment-method-btn" data-method="cash" onclick="selectPaymentMethod('cash')">
                            <i class="bi bi-cash payment-method-icon"></i>
                            <span>Cash</span>
                        </div>
                        <div class="payment-method-btn" data-method="bank" onclick="selectPaymentMethod('bank')">
                            <i class="bi bi-credit-card payment-method-icon"></i>
                            <span>Bank Transfer</span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmPaymentBtn" onclick="confirmPayment()"
                        disabled>
                        <i class="bi bi-check-circle me-2"></i>Confirm Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true"
    style="z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered receipt-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">Transport Fee Payment Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body receipt-container">
                <div class="receipt-preview" id="receiptPreview">
                    <!-- Receipt will be generated here -->
                </div>
                <div class="receipt-actions">
                    <button type="button" class="btn btn-outline-primary" onclick="printReceipt()">
                        <i class="bi bi-printer me-2"></i>Print Receipt
                    </button>
                    <button type="button" class="btn btn-primary" onclick="downloadReceiptAsPDF()">
                        <i class="bi bi-download me-2"></i>Download PDF
                    </button>
                    <button type="button" class="btn btn-success" onclick="downloadReceiptAsImage()">
                        <i class="bi bi-image me-2"></i>Download Image
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Initialize flatpickr date range picker (only if element exists)
const dateRangeFilter = document.getElementById("dateRangeFilter");
if (dateRangeFilter) {
    flatpickr("#dateRangeFilter", {
        mode: "range",
        dateFormat: "Y-m-d",
        placeholder: "Select date range"
    });
}

// Global variables
let activeFilters = {};
let selectedPaymentMethod = null;
let currentStudentHash = null;
let currentInstallmentId = null;
let currentReceiptData = null;

// Data passed from Laravel - This is an array now
const studentInstallments = @json($paginator -> items());

// ==================== FILTERING FUNCTIONS ====================

function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    // Department handling
    const deptInput = document.querySelector('input[list="departmentsList"]');
    if (deptInput) {
        const hiddenInput = document.getElementById('department_id_hidden');

        deptInput.addEventListener('change', function() {
            const inputValue = this.value.trim();
            const options = document.querySelectorAll('#departmentsList option');

            let selectedId = '';

            options.forEach(option => {
                if (option.value === inputValue) {
                    selectedId = option.dataset.id;
                }
            });

            if (hiddenInput) hiddenInput.value = selectedId;
            showLoader();
            this.form.submit();
        });
    }

    // GLOBAL AUTO FILTER WITH TRIM SUPPORT
    document.querySelectorAll('.auto-submit').forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(this.delayTimer);

            this.delayTimer = setTimeout(() => {
                let value = this.value;

                if (typeof value === 'string') {
                    value = value.trim();
                }

                if (value === '') {
                    this.value = '';
                }
                showLoader();
                this.form.submit();
            }, 400);
        });

        input.addEventListener('change', function() {
            this.form.submit();
        });
    });

    const dateRangeFilter = document.getElementById('dateRangeFilter');
    if (dateRangeFilter) {
        flatpickr("#dateRangeFilter", {
            mode: "range",
            dateFormat: "Y-m-d",
            placeholder: "Select date range"
        });
    }
});

// ==================== PAYMENT RECORDING ====================
function recordPayment(studentHash, installmentId) {
    // Find the student in the array
    const student = studentInstallments.find(s => s.student_hash_id === studentHash);

    if (!student || !student.current_installment) {
        Swal.fire({
            icon: 'warning',
            title: 'No Installment Found',
            text: 'No current installment found for this student.',
            confirmButtonColor: '#4361ee'
        });
        return;
    }

    currentStudentHash = studentHash;
    currentInstallmentId = installmentId;

    openPaymentModal(student);
}

function openPaymentModal(student) {
    const inst = student.current_installment;

    const originalFee = parseFloat(inst.fee_amount || 0);
    const lateFee = parseFloat(inst.late_fee || 0);
    const discount = parseFloat(inst.discount || 0);
    const payableAmount = originalFee + lateFee - discount;

    const studentNameDisplay = document.getElementById('studentNameDisplay');
    const courseDisplay = document.getElementById('courseDisplay');
    const transportStopDisplay = document.getElementById('transportStopDisplay');
    const feeAmountDisplay = document.getElementById('feeAmountDisplay');
    const lateFeeDisplay = document.getElementById('lateFeeDisplay');
    const discountDisplay = document.getElementById('discountDisplay');
    const payableAmountDisplay = document.getElementById('payableAmountDisplay');
    const finalAmountDisplay = document.getElementById('finalAmountDisplay');

    if (studentNameDisplay) studentNameDisplay.textContent = student.student_name;
    if (courseDisplay) courseDisplay.textContent = `${student.course} - ${student.department}`;
    if (transportStopDisplay) transportStopDisplay.textContent = inst.transport_stop || student.transport_stop ||
        'Not Assigned';
    if (feeAmountDisplay) feeAmountDisplay.textContent = '₹' + originalFee.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    });
    if (lateFeeDisplay) lateFeeDisplay.textContent = lateFee > 0 ?
        '+₹' + lateFee.toLocaleString('en-IN', {
            minimumFractionDigits: 2
        }) : '₹0.00';
    if (discountDisplay) discountDisplay.textContent = discount > 0 ?
        '-₹' + discount.toLocaleString('en-IN', {
            minimumFractionDigits: 2
        }) : '₹0.00';
    if (payableAmountDisplay) payableAmountDisplay.textContent = '₹' + payableAmount.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    });
    if (finalAmountDisplay) finalAmountDisplay.textContent = '₹' + payableAmount.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    });

    const transactionIdInput = document.getElementById('transactionId');
    if (transactionIdInput) transactionIdInput.value = '';

    selectedPaymentMethod = null;
    document.querySelectorAll('.payment-method-btn').forEach(btn => btn.classList.remove('active'));

    const confirmBtn = document.getElementById('confirmPaymentBtn');
    if (confirmBtn) confirmBtn.disabled = true;

    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    modal.show();
}

function selectPaymentMethod(method) {
    selectedPaymentMethod = method;
    document.querySelectorAll('.payment-method-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.method === method) btn.classList.add('active');
    });
    const confirmBtn = document.getElementById('confirmPaymentBtn');
    if (confirmBtn) confirmBtn.disabled = false;
}

function confirmPayment() {
    if (!selectedPaymentMethod) {
        Swal.fire({
            icon: 'warning',
            title: 'Payment Method Required',
            text: 'Please select a payment method',
            confirmButtonColor: '#4361ee'
        });
        return;
    }

    // Find the student in the array
    const student = studentInstallments.find(s => s.student_hash_id === currentStudentHash);

    if (!student || !student.current_installment) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Student or installment data not found',
            confirmButtonColor: '#4361ee'
        });
        return;
    }

    const installment = student.current_installment;
    const paymentDateInput = document.getElementById('paymentDate');
    const transactionIdInput = document.getElementById('transactionId');

    const paymentDate = paymentDateInput ? paymentDateInput.value : '';
    const transactionId = transactionIdInput ? transactionIdInput.value.trim() : '';

    const feeAmount = parseFloat(installment.fee_amount || 0);
    const lateFee = parseFloat(installment.late_fee || 0);
    const discount = parseFloat(installment.discount || 0);
    const payableAmount = feeAmount + lateFee - discount;

    const paymentRecord = {
        type: 'Transport',
        id: currentInstallmentId,
        payableAmount: payableAmount,
        paymentMethod: selectedPaymentMethod,
        student_hash_id: currentStudentHash,
        student_name: student.student_name,
        course: student.course,
        fee_amount: feeAmount,
        late_fee: lateFee,
        discount: discount,
        payment_date: paymentDate,
        transaction_id: transactionId
    };

    console.log('Sending payment data:', paymentRecord);

    // Show loading SweetAlert
    Swal.fire({
        title: 'Processing Payment...',
        text: 'Please wait while we process the payment',
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: "{{ route('admin.payments.confirm') }}",
        type: 'POST',
        data: paymentRecord,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('Payment success:', response);
            if (response.status === true) {
                // Close any open modals
                const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                if (modal) modal.hide();

                // Show success SweetAlert
                Swal.fire({
                    icon: 'success',
                    title: 'Payment Successful! 🎉',
                    html: `
                        <div style="text-align: left; font-size: 14px; margin-top: 10px;">
                            <p><strong>Student:</strong> ${student.student_name}</p>
                            <p><strong>Amount:</strong> ₹${payableAmount.toFixed(2)}</p>
                            <p><strong>Reference ID:</strong> <code>${response.reference_id || transactionId || 'Generated'}</code></p>
                            <p><strong>Payment Method:</strong> ${selectedPaymentMethod.toUpperCase()}</p>
                        </div>
                    `,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#10b981',
                    timer: 3000,
                    timerProgressBar: true
                }).then((result) => {
                    // Reload after SweetAlert closes
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Payment Failed',
                    text: response.message || 'Payment could not be processed',
                    confirmButtonColor: '#4361ee'
                });
            }
        },
        error: function(xhr) {
            console.error('Payment error:', xhr.responseText);
            let errorMessage = 'Server error occurred. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            Swal.fire({
                icon: 'error',
                title: 'Payment Failed',
                text: errorMessage,
                confirmButtonColor: '#4361ee'
            });
        },
        complete: function() {
            const confirmBtn = document.getElementById('confirmPaymentBtn');
            if (confirmBtn) {
                confirmBtn.disabled = false;
                confirmBtn.textContent = 'Confirm Payment';
            }
        }
    });
}

function updatePaymentStatusInTable(studentHash) {
    const row = document.querySelector(`.payment-row[data-student-hash="${studentHash}"]`);
    if (row) {
        const statusColumn = row.querySelector('.status-column');
        if (statusColumn) {
            statusColumn.innerHTML =
                '<span class="status-badge status-paid"><i class="bi bi-check-circle"></i> Paid</span>';
        }

        const actionButtons = row.querySelector('.action-buttons');
        if (actionButtons) {
            const paymentBtn = actionButtons.querySelector('[onclick*="recordPayment"]');
            if (paymentBtn) paymentBtn.remove();

            if (!actionButtons.querySelector('[onclick*="viewReceipt"]')) {
                const receiptBtn = document.createElement('button');
                receiptBtn.className = 'btn btn-sm btn-primary w-100';
                receiptBtn.title = 'View Receipt';
                receiptBtn.innerHTML = '<i class="bi bi-receipt me-2"></i>View Receipt';
                receiptBtn.setAttribute('onclick', `viewReceipt('${studentHash}', ${currentInstallmentId})`);
                actionButtons.appendChild(receiptBtn);
            }
        }
    }
}

function showPaymentSuccess(paymentRecord) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>
                <strong>Payment Successful!</strong>
                <div class="small">Amount: ₹${paymentRecord.payable_amount.toFixed(2)}</div>
                <div class="small">Student: ${paymentRecord.student_name}</div>
                <div class="small">Reference ID: <code>${paymentRecord.reference_id}</code></div>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.body.appendChild(alertDiv);
    setTimeout(() => alertDiv.remove(), 7000);
}

// ==================== RECEIPT FUNCTIONS ====================
function viewReceipt(studentHash, installmentId) {
    // Find the student in the array
    const student = studentInstallments.find(s => s.student_hash_id === studentHash);
    if (!student) return;

    const installment = student.installments.find(inst => inst.id == installmentId);
    if (!installment) return;

    console.log('Installment data:', {
        id: installment.id,
        pay_date: installment.pay_date,
        payment_status: installment.payment_status,
        transaction_id: installment.transaction_id
    });

    currentReceiptData = {
        ...student,
        ...installment,
        student_name: student.student_name,
        student_reg: student.student_reg,
        department: student.department,
        course: student.course,
        batch: student.batch,
        academic_year: student.academic_year,
        semester: student.semester,
        section: student.section,
        fee_amount: installment.fee_amount,
        late_fee: installment.late_fee,
        discount: installment.discount,
        payable_amount: installment.payable_amount,
        payment_type: installment.payment_type || 'cash',
        payment_status: installment.payment_status,
        pay_date: installment.pay_date,
        due_date: installment.due_date,
        transaction_id: installment.transaction_id,
        fee_duration_type: installment.fee_duration_type || '',
        transport_stop: installment.transport_stop || student.transport_stop || 'Not Assigned',
        receiptDate: new Date().toLocaleDateString('en-IN'),
        receiptTime: new Date().toLocaleTimeString('en-IN', {
            hour12: true,
            hour: '2-digit',
            minute: '2-digit'
        }),
        receiptNumber: generateReceiptNumber(),
        instituteName: "{{ $serviceInstitutedetails->name ?? 'Institute Name' }}",
        instituteAddress: "{{ $serviceInstitutedetails->address_line_1 ?? '' }} {{ $serviceInstitutedetails->address_line_2 ?? '' }} {{ $serviceInstitutedetails->state ?? '' }} {{ $serviceInstitutedetails->city ?? '' }} {{ $serviceInstitutedetails->pincode ?? '' }}",
        institutePhone: "{{ $serviceInstitutedetails->contact_number ?? '' }}",
        instituteEmail: "{{ $serviceInstitutedetails->email ?? '' }}",
        website: "{{ $serviceInstitutedetails->website ?? '' }}",
        terms: [
            "This is a computer generated receipt and does not require signature.",
            "Payment once made is non-refundable.",
            "Please keep this receipt for future reference.",
            "For any queries, contact accounts department within 7 days."
        ]
    };

    generateReceiptHTML(currentReceiptData);
    const modal = new bootstrap.Modal(document.getElementById('receiptModal'));
    modal.show();
}

function generateReceiptNumber() {
    const date = new Date();
    const year = date.getFullYear().toString().substr(-2);
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
    return `TRN${year}${month}${day}${random}`;
}

function generateReceiptHTML(payment) {
    const receiptPreview = document.getElementById('receiptPreview');
    if (!receiptPreview) return;

    const formatCurrency = (amount) => {
        return '₹' + parseFloat(amount || 0).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    };

    const payDate = payment.pay_date ? new Date(payment.pay_date).toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    }) : 'N/A';

    let payTime = '';
    if (payment.pay_date && payment.pay_date.toString().includes(' ')) {
        const dateObj = new Date(payment.pay_date);
        payTime = dateObj.toLocaleTimeString('en-IN', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    }

    const dueDate = payment.due_date ? new Date(payment.due_date).toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    }) : 'N/A';

    const feeAmount = parseFloat(payment.fee_amount || 0);
    const lateFee = parseFloat(payment.late_fee || 0);
    const discount = parseFloat(payment.discount || 0);
    const payable = parseFloat(payment.payable_amount || 0);

    const paymentStatusHTML = payment.payment_status === 'paid' ?
        '<span style="color: #28a745; font-weight: bold;"><i class="bi bi-check-circle"></i> PAID</span>' :
        '<span style="color: #dc3545; font-weight: bold;"><i class="bi bi-x-circle"></i> NOT PAID</span>';

    const paymentMethodHTML = {
        online: '<span style="color: #28a745;"><i class="bi bi-credit-card"></i> Online Payment</span>',
        cash: '<span style="color: #007bff;"><i class="bi bi-cash"></i> Cash Payment</span>',
        bank: '<span style="color: #6f42c1;"><i class="bi bi-bank"></i> Bank Transfer</span>'
    } [payment.payment_type] || '<span>N/A</span>';

    const receiptHTML = `
        <div class="receipt-paper" id="receiptContent">
            <div class="watermark">PAID</div>
            <div class="receipt-header">
                <div class="institute-name">${payment.instituteName}</div>
                <div class="receipt-title">TRANSPORT FEE PAYMENT RECEIPT</div>
                <div class="institute-address">${payment.instituteAddress}</div>
                <div class="institute-contact">Phone: ${payment.institutePhone} | Email: ${payment.instituteEmail}</div>
            </div>
            <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                <div style="flex:1; border:1px solid #ddd; padding:15px; background:#f8f9fa;">
                    <div style="font-weight:bold; margin-bottom:10px;"><i class="bi bi-person-circle"></i> STUDENT INFORMATION</div>
                    <div><span style="font-weight:500;">Name:</span> ${payment.student_name}</div>
                    <div><span style="font-weight:500;">Reg No:</span> ${payment.student_reg}</div>
                </div>
                <div style="flex:1; border:1px solid #ddd; padding:15px; background:#f8f9fa;">
                    <div style="font-weight:bold; margin-bottom:10px;"><i class="bi bi-receipt"></i> RECEIPT INFORMATION</div>
                    <div><span style="font-weight:500;">Receipt No:</span> ${payment.receiptNumber}-${payment.id}</div>
                    <div><span style="font-weight:500;">Date:</span> ${payment.receiptDate} ${payment.receiptTime}</div>
                </div>
            </div>
            <div style="border:1px solid #ddd; padding:15px; background:#f8f9fa; margin-bottom:20px;">
                <div style="font-weight:bold; margin-bottom:10px;"><i class="bi bi-mortarboard"></i> ACADEMIC INFORMATION</div>
                <div style="display: grid; grid-template-columns: repeat(3,1fr); gap:10px;">
                    <div><span style="font-weight:500;">Department:</span> ${payment.department}</div>
                    <div><span style="font-weight:500;">Course:</span> ${payment.course}</div>
                    <div><span style="font-weight:500;">Batch:</span> ${payment.batch}</div>
                    <div><span style="font-weight:500;">Academic Year:</span> ${payment.academic_year}</div>
                    <div><span style="font-weight:500;">Section:</span> ${payment.section}</div>
                </div>
            </div>
            <div style="border:1px solid #ddd; padding:15px; background:#f8f9fa; margin-bottom:20px;">
                <div style="font-weight:bold; margin-bottom:10px;"><i class="bi bi-bus-front"></i> TRANSPORT DETAILS</div>
                <div><span style="font-weight:500;">Transport Stop:</span> ${payment.transport_stop}</div>
                <div><span style="font-weight:500;">Duration:</span> ${payment.fee_duration_type}</div>
            </div>
            <div style="display: flex; gap: 20px; margin-bottom:20px;">
                <div style="flex:1; border:1px solid #ddd; padding:15px; background:#f8f9fa;">
                    <div style="font-weight:bold; margin-bottom:10px;"><i class="bi bi-cash-stack"></i> FEE DETAILS</div>
                    <div><span style="font-weight:500;">Due Date:</span> ${dueDate}</div>
                    <div><span style="font-weight:500;">Payment Date:</span> ${payDate} ${payTime ? `<br><small>at ${payTime}</small>` : ''}</div>
                    <div style="margin-top:15px;">
                        <div style="display:flex; justify-content:space-between;"><span>Transport Fee:</span> ${formatCurrency(feeAmount)}</div>
                        ${lateFee > 0 ? `<div style="display:flex; justify-content:space-between; color:#dc3545;"><span>Late Fee:</span> +${formatCurrency(lateFee)}</div>` : ''}
                        ${discount > 0 ? `<div style="display:flex; justify-content:space-between; color:#28a745;"><span>Discount:</span> -${formatCurrency(discount)}</div>` : ''}
                        <div style="display:flex; justify-content:space-between; font-weight:bold; border-top:2px solid #333; margin-top:8px; padding-top:8px;">
                            <span>TOTAL PAYABLE:</span> <span>${formatCurrency(payable)}</span>
                        </div>
                    </div>
                </div>
                <div style="flex:1; border:1px solid #ddd; padding:15px; background:#f8f9fa;">
                    <div style="font-weight:bold; margin-bottom:10px;"><i class="bi bi-credit-card-2-front"></i> PAYMENT INFORMATION</div>
                    <div><span style="font-weight:500;">Status:</span> ${paymentStatusHTML}</div>
                    <div><span style="font-weight:500;">Method:</span> ${paymentMethodHTML}</div>
                    ${payment.transaction_id ? `
                        <div><span style="font-weight:500;">Transaction ID:</span> <code>${payment.transaction_id}</code></div>
                    ` : ''}
                </div>
            </div>
            <div class="receipt-footer">
                <div style="background:#f1f8ff; border:1px solid #d1e7ff; padding:12px; margin-bottom:15px;">
                    <div style="font-weight:bold;">Terms & Conditions:</div>
                    <ul style="font-size:11px; margin-bottom:0;">
                        ${payment.terms.map(term => `<li>${term}</li>`).join('')}
                    </ul>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 20px; padding-top: 15px; border-top: 1px dashed #ddd;">
                    <div style="text-align: center; flex:1;">
                        <div style="margin-bottom: 5px;">____________________</div>
                        <div>Student Signature</div>
                    </div>
                    <div style="text-align: center; flex:1;">
                        <div style="margin-bottom: 5px;">____________________</div>
                        <div>Authorized Signatory</div>
                    </div>
                </div>
            </div>
        </div>
    `;

    receiptPreview.innerHTML = receiptHTML;
}

function printReceipt() {
    window.print();
}

async function downloadReceiptAsPDF() {
    if (!currentReceiptData) return;
    try {
        const downloadBtn = document.querySelector('#receiptModal .btn-primary');
        const originalText = downloadBtn ? downloadBtn.innerHTML : 'Download PDF';
        if (downloadBtn) {
            downloadBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Generating PDF...';
            downloadBtn.disabled = true;
        }

        const receiptElement = document.getElementById('receiptContent');
        if (!receiptElement) {
            alert('Receipt content not found');
            return;
        }

        const canvas = await html2canvas(receiptElement, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#ffffff'
        });
        const imgData = canvas.toDataURL('image/png');

        const {
            jsPDF
        } = window.jspdf;
        const pdf = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'a4'
        });
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const imgHeight = (canvas.height * pdfWidth) / canvas.width;

        pdf.addImage(imgData, 'PNG', 10, 10, pdfWidth - 20, imgHeight);
        const fileName =
            `Transport_Fee_Receipt_${currentReceiptData.receiptNumber}_${currentReceiptData.student_name.replace(/\s+/g, '_')}.pdf`;
        pdf.save(fileName);

        showDownloadSuccess('PDF');
    } catch (error) {
        console.error(error);
        alert('Error generating PDF. Please try again.');
    } finally {
        const downloadBtn = document.querySelector('#receiptModal .btn-primary');
        if (downloadBtn) {
            downloadBtn.innerHTML = originalText;
            downloadBtn.disabled = false;
        }
    }
}

async function downloadReceiptAsImage() {
    if (!currentReceiptData) return;
    try {
        const downloadBtn = document.querySelector('#receiptModal .btn-success');
        const originalText = downloadBtn ? downloadBtn.innerHTML : 'Download Image';
        if (downloadBtn) {
            downloadBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Generating Image...';
            downloadBtn.disabled = true;
        }

        const receiptElement = document.getElementById('receiptContent');
        if (!receiptElement) {
            alert('Receipt content not found');
            return;
        }

        const canvas = await html2canvas(receiptElement, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#ffffff'
        });
        const imgData = canvas.toDataURL('image/png');

        const link = document.createElement('a');
        link.download =
            `Transport_Fee_Receipt_${currentReceiptData.receiptNumber}_${currentReceiptData.student_name.replace(/\s+/g, '_')}.png`;
        link.href = imgData;
        link.click();

        showDownloadSuccess('Image');
    } catch (error) {
        console.error(error);
        alert('Error generating image. Please try again.');
    } finally {
        const downloadBtn = document.querySelector('#receiptModal .btn-success');
        if (downloadBtn) {
            downloadBtn.innerHTML = originalText;
            downloadBtn.disabled = false;
        }
    }
}

function showDownloadSuccess(fileType) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>
                <strong>${fileType} Downloaded Successfully!</strong>
                <div class="small">Receipt for ${currentReceiptData.student_name}</div>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.body.appendChild(alertDiv);
    setTimeout(() => alertDiv.remove(), 5000);
}
</script>
@endsection