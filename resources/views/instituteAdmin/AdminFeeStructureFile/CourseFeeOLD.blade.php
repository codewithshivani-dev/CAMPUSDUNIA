@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Class Fee Payments</title>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
<!-- Flatpickr Datepicker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
:root {
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

body {
    background-color: #f8fafc;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
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
    gap: 0.75rem;
    justify-content: flex-end;
    margin-top: 1rem;
    padding-top: 1rem;
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

/* Table Design */
.academic-table {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid var(--border);
}

.table-header {
    background: linear-gradient(135deg, var(--dark) 0%, var(--gray-600) 100%);
    color: white;
}

.table-header th {
    font-weight: 500;
    padding: 1rem 1.25rem;
    border: none;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

.table-body tr {
    border-bottom: 1px solid var(--border);
    transition: all 0.2s ease;
}

.table-body tr:hover {
    background-color: var(--primary-light);
}

.table-body td {
    padding: 1rem 1.25rem;
    vertical-align: middle;
    border: none;
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

.department-course {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
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

.pagination-buttons {
    display: flex;
    gap: 0.5rem;
    align-items: center;
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
    text-decoration: none;
    cursor: pointer;
}

.pagination-btn:hover:not(:disabled) {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
    text-decoration: none;
}

.pagination-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-info {
    padding: 0.5rem 1rem;
    background: var(--gray-100);
    border-radius: 8px;
    font-size: 0.875rem;
    color: var(--gray-600);
}

.records-count {
    font-size: 0.875rem;
    color: var(--gray-500);
}
</style>

<div id="pageLoader">
    <div class="spinner"></div>
</div>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="header-title">
                    <i class="bi bi-building-fill me-2"></i>Class Fee Payments
                </h1>
                <p class="header-subtitle">Track and manage all Class fee payments across departments</p>
            </div>
            <button class="btn btn-light d-none align-items-center gap-2">
                <i class="bi bi-plus-circle"></i> New Payment
            </button>
        </div>

        <!-- Stats Cards (calculated from grouped $students) -->

        @php
        $totalPayable = 0;
        $totalPaid = 0;
        $totalPending = 0;
        $totalOverdue = 0;
        $totalPaidCount = 0;
        $totalPendingCount = 0;
        $totalOverdueCount = 0;

        // Use $allStudentsData for accurate stats
        foreach($allStudentsData as $student) {
        foreach($student['installments'] as $inst) {
        $payable = $inst['payable_amount'] ?? 0;
        $totalPayable += $payable;

        if($inst['payment_status'] == 'paid') {
        $totalPaid += $payable;
        $totalPaidCount++;
        } else {
        $dueDate = \Carbon\Carbon::parse($inst['due_date']);
        $today = \Carbon\Carbon::today();
        if($dueDate->lt($today)) {
        $totalOverdue += $payable;
        $totalOverdueCount++;
        } else {
        $totalPending += $payable;
        $totalPendingCount++;
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
                    <!-- Change this line from $students to $paginator -->
                    <div class="stat-value">{{ $paginator->total() }}</div>
                    <div class="stat-label">Total Students</div>
                </div>
            </div>
            <!-- Rest of stats remain the same -->
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
                <div class="stat-icon stat-icon-teal">
                    <i class="bi bi-credit-card"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $totalPaidCount }}</div>
                    <div class="stat-label">Paid Payments</div>
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

    <!-- Filter Section (unique values from $students) -->
    @php
    // Use allStudentsData for unique values in filters
    $courses = array_unique(array_column($allStudentsData, 'course'));
    $batches = array_unique(array_column($allStudentsData, 'batch'));
    @endphp

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
                    <label class="filter-label">Class</label>
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
                    <label class="filter-label">Payment Status</label>
                    <select class="filter-select auto-submit" name="payment_status">
                        <option value="">All Status</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending
                        </option>
                        <option value="overdue" {{ request('payment_status') == 'overdue' ? 'selected' : '' }}>Overdue
                        </option>
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

                <div class="filter-group d-none">
                    <label class="filter-label">Items Per Page</label>
                    <select name="per_page" class="filter-select auto-submit" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All</option>
                    </select>
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
    </form>

    <!-- Table: one row per student, showing current installment -->
    <div class="academic-table">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-header">
                    <tr>
                        <th>STUDENT</th>
                        <th>ACADEMIC DETAILS</th>
                        <th>MODE</th>
                        <th class="text-right">FEE AMOUNT</th>
                        <th class="text-right">LATE FEE</th>
                        <th class="text-right">DISCOUNT</th>
                        <th class="text-right">PAYABLE</th>
                        <th>DATES / STATUS</th>
                        <th>UPDATED</th>
                        <th class="text-center">ACTIONS</th>
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
                        <td>
                            <div class="student-info">
                                <div class="student-name">{{ $student['student_name'] }}</div>
                                <div class="duration-badge">
                                    <i class="bi bi-person-badge"></i> {{ $student['student_reg'] }}
                                </div>
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

                        <!-- Current Fee Amount -->
                        <td class="amount-column">
                            <!-- <div class="amount-label">Current Fee</div> -->
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
                                // Calculate payable as: fee_amount + late_fee - discount
                                $payableAmount = ($current['fee_amount'] ?? 0) +
                                ($current['late_fee'] ?? 0) -
                                ($current['discount'] ?? 0);
                                }
                                @endphp
                                ₹{{ number_format($payableAmount, 2) }}
                            </div>
                        </td>

                        <!-- Due Date & Status (combined for brevity) -->
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
                                    onclick="newrecordPayment('{{ $student['student_hash_id'] }}', {{ $current['id'] }})">
                                    <i class="bi bi-cash-coin me-2"></i>Collect Fee

                                </button>
                                @endif

                                <button class="btn btn-sm btn-info w-100 text-white"
                                    title="View complete payment history"
                                    onclick="viewAllInstallments('{{ trim($student['student_hash_id']) }}')">
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
                        <td colspan="10" class="empty-state">
                            <div class="empty-icon"><i class="bi bi-building"></i></div>
                            <h4 class="text-muted mb-2">No Class fee records found</h4>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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
                <h5 class="modal-title" id="paymentModalLabel">Record Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="payment-details">
                    <h6 id="studentNameDisplay"></h6>
                    <div class="payment-detail-row">
                        <span>Class:</span>
                        <span id="courseDisplay"></span>
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
                    <input type="text" class="form-control" id="transactionId" placeholder="TXN1234567890">
                    <small class="text-muted">Enter transaction ID if available, or leave blank for automatic
                        generation.</small>
                    <input type="hidden" id="feeReferenceId" value="">
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

<!-- Receipt Modal (unchanged structure) -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true"
    style="z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered receipt-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">Fee Payment Receipt</h5>
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

<div class="modal fade" id="installmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5>All Installments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="height: 500px; overflow-x: scroll;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Due Date</th>
                            <th>Payment Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="installmentTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- jsPDF for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<!-- html2canvas for capturing HTML as image -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<!-- Flatpickr and JavaScript -->
<script>
// Initialize flatpickr date range picker
flatpickr("#dateRangeFilter", {
    mode: "range",
    dateFormat: "Y-m-d",
    placeholder: "Select date range"
});

// Global variables
let activeFilters = {};
let selectedPaymentMethod = null;
let selectedEMIPlan = null;
let currentStudentHash = null; // For payment modal
let currentInstallmentId = null; // For payment/receipt
let currentReceiptData = null; // For receipt generation
let currentPaymentIndex = null;

// Data passed from Laravel
const studentInstallments = @json($allStudentsData); // Keyed by student_hash
const paymentData = @json(array_values($allStudentsData)); // Indexed array

// ==================== FILTERING FUNCTIONS ====================

function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}


// 🔥 Department handling (same as before)
const deptInput = document.querySelector('input[list="departmentsList"]');
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

    hiddenInput.value = selectedId;
    showLoader();
    this.form.submit();
});


// 🔥 GLOBAL AUTO FILTER WITH TRIM SUPPORT
document.querySelectorAll('.auto-submit').forEach(input => {

    input.addEventListener('input', function() {
        clearTimeout(this.delayTimer);

        this.delayTimer = setTimeout(() => {

            let value = this.value;

            // ✅ Trim check
            if (typeof value === 'string') {
                value = value.trim();
            }

            // ✅ If empty after trim → reset + submit
            if (value === '') {
                this.value = '';
            }
            showLoader();
            this.form.submit();

        }, 1000);
    });

    input.addEventListener('change', function() {
        this.form.submit();
    });
});

// ==================== PAYMENT RECORDING (NEW) ====================
window.newrecordPayment = function(studentHash, installmentId) {
    const student = studentInstallments[studentHash];
    if (!student || !student.current_installment) {
        alert('No current installment found for this student.');
        return;
    }

    // Store these globally for use in confirmPayment
    currentStudentHash = studentHash;
    currentInstallmentId = installmentId; // This will be used as 'id' in the payment record

    openPaymentModal(student);
};

function openPaymentModal(student) {
    const inst = student.current_installment;

    // Original fee amount (before discount)
    const originalFee = parseFloat(inst.fee_amount || 0);
    const lateFee = parseFloat(inst.late_fee || 0);
    const discount = parseFloat(inst.discount || 0);
    const payableAmount = originalFee + lateFee - discount;

    document.getElementById('studentNameDisplay').textContent = student.student_name;
    document.getElementById('courseDisplay').textContent = `${student.course} - ${student.department}`;

    // Show original fee amount
    document.getElementById('feeAmountDisplay').textContent = '₹' + originalFee.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    });

    document.getElementById('lateFeeDisplay').textContent = lateFee > 0 ?
        '+₹' + lateFee.toLocaleString('en-IN', {
            minimumFractionDigits: 2
        }) : '₹0.00';
    document.getElementById('discountDisplay').textContent = discount > 0 ?
        '-₹' + discount.toLocaleString('en-IN', {
            minimumFractionDigits: 2
        }) : '₹0.00';
    document.getElementById('payableAmountDisplay').textContent = '₹' + payableAmount.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    });
    document.getElementById('finalAmountDisplay').textContent = '₹' + payableAmount.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    });

    // Clear transaction ID field - let backend generate if empty
    document.getElementById('transactionId').value = '';

    // Reset UI
    selectedPaymentMethod = null;
    document.querySelectorAll('.payment-method-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById('confirmPaymentBtn').disabled = true;

    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    modal.show();
}

function selectPaymentMethod(method) {
    selectedPaymentMethod = method;
    document.querySelectorAll('.payment-method-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.method === method) btn.classList.add('active');
    });
    document.getElementById('confirmPaymentBtn').disabled = false;
}

function selectEMIPlan(installments) {
    selectedEMIPlan = installments;
    document.querySelectorAll('.emi-option').forEach(option => {
        option.classList.remove('active');
        if (parseInt(option.dataset.installments) === installments) {
            option.classList.add('active');
        }
    });
    document.getElementById('confirmPaymentBtn').disabled = false;
}

function confirmPayment() {
    if (!selectedPaymentMethod) {
        alert('Please select a payment method');
        return;
    }

    // Get the student data using the stored student hash
    const student = studentInstallments[currentStudentHash];
    if (!student || !student.current_installment) {
        alert('Student or installment data not found');
        return;
    }

    const installment = student.current_installment;
    const paymentDate = document.getElementById('paymentDate').value;

    // Get transaction ID - send as is, backend will generate if empty
    const transactionId = document.getElementById('transactionId').value.trim();

    // Calculate payable amount
    const feeAmount = parseFloat(installment.fee_amount || 0);
    const lateFee = parseFloat(installment.late_fee || 0);
    const discount = parseFloat(installment.discount || 0);
    const payableAmount = feeAmount + lateFee - discount;

    const paymentRecord = {
        type: 'Course',
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
        transaction_id: transactionId // Send as is, backend handles empty
    };

    // Log the data being sent for debugging
    console.log('Sending payment data:', paymentRecord);

    // Make AJAX call to process payment
    $.ajax({
        url: "{{ route('admin.payments.confirm') }}",
        type: 'POST',
        data: paymentRecord,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            $('#confirmPaymentBtn').prop('disabled', true).text('Processing...');
        },
        success: function(response) {
            console.log('Payment success:', response);
            if (response.status === true) {
                updatePaymentStatusInTable(currentStudentHash);

                // Show success message with reference ID from response
                showPaymentSuccess({
                    student_name: student.student_name,
                    payable_amount: payableAmount,
                    reference_id: response.reference_id || transactionId || 'Generated'
                });

                const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                modal.hide();

                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                alert(response.message || 'Payment failed');
            }
        },
        error: function(xhr) {
            console.error('Payment error:', xhr.responseText);
            if (xhr.responseJSON && xhr.responseJSON.message) {
                alert('Error: ' + xhr.responseJSON.message);
            } else {
                alert('Server error occurred. Please check console for details.');
            }
        },
        complete: function() {
            $('#confirmPaymentBtn').prop('disabled', false).text('Confirm Payment');
        }
    });
}

// Helper function to update payment status in the table without reload
function updatePaymentStatusInTable(studentHash) {
    const row = document.querySelector(`.payment-row[data-student-hash="${studentHash}"]`);
    if (row) {
        // Update status badge
        const statusColumn = row.querySelector('.status-column');
        if (statusColumn) {
            statusColumn.innerHTML =
                '<span class="status-badge status-paid"><i class="bi bi-check-circle"></i> Paid</span>';
        }

        // Update action buttons (remove payment button, add receipt button)
        const actionButtons = row.querySelector('.action-buttons');
        if (actionButtons) {
            // Remove payment button if exists
            const paymentBtn = actionButtons.querySelector('[onclick*="newrecordPayment"]');
            if (paymentBtn) paymentBtn.remove();

            // Add receipt button if not exists
            if (!actionButtons.querySelector('[onclick*="viewReceipt"]')) {
                const receiptBtn = document.createElement('button');
                receiptBtn.className = 'action-btn';
                receiptBtn.title = 'View Receipt';
                receiptBtn.innerHTML = '<i class="bi bi-receipt"></i>';
                receiptBtn.setAttribute('onclick', `viewReceipt('${studentHash}', ${currentInstallmentId})`);
                actionButtons.appendChild(receiptBtn);
            }
        }
    }
}

// Show success message
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

// ==================== INSTALLMENT VIEWING ====================
function viewAllInstallments(studentHash) {
    // Get the student data
    const student = studentInstallments[studentHash];

    if (!student) {
        console.error('Student not found for hash:', studentHash);
        alert('Student data not found. Please refresh the page.');
        return;
    }

    // Get the modal elements
    const modalElement = document.getElementById('installmentModal');
    const modalBody = document.querySelector('#installmentModal .modal-body');
    const modalTitle = document.querySelector('#installmentModal .modal-title');

    if (!modalElement || !modalBody) {
        console.error('Modal elements not found');
        return;
    }

    // Calculate monthly EMI after discount from first paid/pending installment
    let monthlyEMIAfterDiscount = 0;
    if (student.installments.length > 0) {
        const firstInst = student.installments[0];
        const feeAmount = parseFloat(firstInst.fee_amount || 0);
        const discount = parseFloat(firstInst.discount || 0);
        monthlyEMIAfterDiscount = feeAmount - discount; // 4650 - 200 = 4450
    }

    const totalInstallments = student.installments.length;

    // Calculate totals AFTER discount
    let totalFeeAfterDiscount = 0;
    let totalPaidAmount = 0;
    let totalPendingAmount = 0;
    let paidCount = 0;
    let pendingCount = 0;

    student.installments.forEach(inst => {
        const feeAmount = parseFloat(inst.fee_amount || 0);
        const lateFee = parseFloat(inst.late_fee || 0);
        const discount = parseFloat(inst.discount || 0);
        const payableAmount = feeAmount + lateFee - discount;

        totalFeeAfterDiscount += payableAmount;

        if (inst.payment_status === 'paid') {
            totalPaidAmount += payableAmount;
            paidCount++;
        } else {
            totalPendingAmount += payableAmount;
            pendingCount++;
        }
    });

    // Expected total based on monthly EMI after discount
    const expectedTotal = monthlyEMIAfterDiscount * totalInstallments; // 4450 × 12 = 53400

    console.log('Summary:', {
        monthlyEMIAfterDiscount,
        totalInstallments,
        expectedTotal,
        totalFeeAfterDiscount,
        totalPaidAmount,
        totalPendingAmount,
        paidCount,
        pendingCount
    });

    // Update modal title
    if (modalTitle) {
        modalTitle.innerHTML = `Installment Details: ${student.student_name}`;
    }

    // Clear modal body
    modalBody.innerHTML = '';

    // Create summary section - SIMPLE DESIGN
    const summaryDiv = document.createElement('div');
    summaryDiv.style.cssText = `
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 25px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    `;

    summaryDiv.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h5 style="margin: 0; color: #495057; font-weight: 600;">
                <i class="bi bi-calculator me-2"></i>Payment Summary
            </h5>
            <button class="btn btn-sm btn-outline-secondary" onclick="printInstallmentSummary()">
                <i class="bi bi-printer me-1"></i>Print
            </button>
        </div>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 20px;">
            <div style="background: white; padding: 15px; border-radius: 6px; border: 1px solid #e9ecef;">
                <div style="font-size: 13px; color: #6c757d; margin-bottom: 5px;">Monthly EMI (After Discount)</div>
                <div style="font-size: 22px; font-weight: 600; color: #212529;">₹${monthlyEMIAfterDiscount.toFixed(2)}</div>
                <div style="font-size: 11px; color: #6c757d;">₹4650 - ₹200 discount</div>
            </div>
            <div style="background: white; padding: 15px; border-radius: 6px; border: 1px solid #e9ecef;">
                <div style="font-size: 13px; color: #6c757d; margin-bottom: 5px;">Total (${totalInstallments} Months)</div>
                <div style="font-size: 22px; font-weight: 600; color: #212529;">₹${expectedTotal.toFixed(2)}</div>
                <div style="font-size: 11px; color: #6c757d;">${monthlyEMIAfterDiscount.toFixed(2)} × ${totalInstallments}</div>
            </div>
            <div style="background: white; padding: 15px; border-radius: 6px; border: 1px solid #e9ecef;">
                <div style="font-size: 13px; color: #6c757d; margin-bottom: 5px;">Paid Amount</div>
                <div style="font-size: 22px; font-weight: 600; color: #28a745;">₹${totalPaidAmount.toFixed(2)}</div>
                <div style="font-size: 11px; color: #6c757d;">${paidCount} installments</div>
            </div>
            <div style="background: white; padding: 15px; border-radius: 6px; border: 1px solid #e9ecef;">
                <div style="font-size: 13px; color: #6c757d; margin-bottom: 5px;">Pending Amount</div>
                <div style="font-size: 22px; font-weight: 600; color: #dc3545;">₹${totalPendingAmount.toFixed(2)}</div>
                <div style="font-size: 11px; color: #6c757d;">${pendingCount} installments</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
            <div style="background: white; padding: 12px 15px; border-radius: 6px; border: 1px solid #e9ecef;">
                <div style="font-size: 13px; color: #6c757d; margin-bottom: 5px;">Original Fee (Before Discount)</div>
                <div style="font-size: 18px; font-weight: 600;">₹${(monthlyEMIAfterDiscount + 200).toFixed(2)}/month</div>
                <div style="font-size: 11px; color: #6c757d;">Total: ₹${((monthlyEMIAfterDiscount + 200) * totalInstallments).toFixed(2)}</div>
            </div>
            <div style="background: white; padding: 12px 15px; border-radius: 6px; border: 1px solid #e9ecef;">
                <div style="font-size: 13px; color: #6c757d; margin-bottom: 5px;">Total Discount</div>
                <div style="font-size: 18px; font-weight: 600; color: #28a745;">₹${((monthlyEMIAfterDiscount + 200) * totalInstallments - expectedTotal).toFixed(2)}</div>
                <div style="font-size: 11px; color: #6c757d;">₹200 × ${totalInstallments} months</div>
            </div>
        </div>

        <div style="background: white; padding: 12px 15px; border-radius: 6px; border: 1px solid #e9ecef;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <span style="font-size: 14px; color: #495057;">Payment Progress</span>
                <span style="font-size: 14px; font-weight: 500;">${paidCount}/${totalInstallments} installments</span>
            </div>
            <div style="height: 8px; background: #e9ecef; border-radius: 4px; overflow: hidden;">
                <div style="height: 100%; width: ${(paidCount/totalInstallments)*100}%; background: #28a745;"></div>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 8px;">
                <span style="font-size: 12px; color: #6c757d;">Completed: ${((paidCount/totalInstallments)*100).toFixed(1)}%</span>
                <span style="font-size: 12px; color: #6c757d;">₹${totalPaidAmount.toFixed(2)} of ₹${expectedTotal.toFixed(2)}</span>
            </div>
        </div>
    `;

    modalBody.appendChild(summaryDiv);

    // Create table
    const tableContainer = document.createElement('div');
    tableContainer.style.cssText = `
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
        background: white;
    `;

    const table = document.createElement('table');
    table.style.cssText = 'width: 100%; border-collapse: collapse; font-size: 14px;';

    // Table Header
    const thead = document.createElement('thead');
    thead.style.cssText = 'background: #f8f9fa; border-bottom: 2px solid #dee2e6;';
    thead.innerHTML = `
        <tr>
            <th style="padding: 12px; text-align: center;">#</th>
            <th style="padding: 12px; text-align: left;">Due Date</th>
            <th style="padding: 12px; text-align: left;">Payment Date</th>
            <th style="padding: 12px; text-align: right;">Fee (₹)</th>
            <th style="padding: 12px; text-align: right;">Discount (₹)</th>
            <th style="padding: 12px; text-align: right;">Payable (₹)</th>
            <th style="padding: 12px; text-align: center;">Status</th>
            <th style="padding: 12px; text-align: center;">Action</th>
        </tr>
    `;
    table.appendChild(thead);

    // Table Body
    const tbody = document.createElement('tbody');

    student.installments.forEach((inst, index) => {
        const feeAmount = parseFloat(inst.fee_amount || 0);
        const lateFee = parseFloat(inst.late_fee || 0);
        const discount = parseFloat(inst.discount || 0);
        const payableAmount = feeAmount + lateFee - discount;

        // Format dates
        const dueDate = inst.due_date ? new Date(inst.due_date).toLocaleDateString('en-IN', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        }) : 'N/A';

        let paymentDate = '—';
        if (inst.payment_status === 'paid' && inst.pay_date) {
            paymentDate = new Date(inst.pay_date).toLocaleDateString('en-IN', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }

        // Status badge
        let statusBadge = '';
        if (inst.payment_status === 'paid') {
            statusBadge =
                '<span style="background: #d4edda; color: #155724; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;">Paid</span>';
        } else {
            const dueDateTime = new Date(inst.due_date).getTime();
            const today = new Date().setHours(0, 0, 0, 0);

            if (dueDateTime < today) {
                statusBadge =
                    '<span style="background: #f8d7da; color: #721c24; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;">Overdue</span>';
            } else {
                statusBadge =
                    '<span style="background: #fff3cd; color: #856404; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;">Pending</span>';
            }
        }

        const row = document.createElement('tr');
        row.style.cssText = 'border-bottom: 1px solid #e9ecef;';

        row.innerHTML = `
            <td style="padding: 12px; text-align: center;">${index + 1}</td>
            <td style="padding: 12px;">
                ${dueDate}
                ${lateFee > 0 ? `<br><small style="color: #dc3545;">Late Fee: +₹${lateFee.toFixed(2)}</small>` : ''}
            </td>
            <td style="padding: 12px;">
                ${inst.payment_status === 'paid' ? paymentDate : '—'}
                ${inst.payment_type ? `<br><small style="color: #6c757d;">via ${inst.payment_type}</small>` : ''}
            </td>
            <td style="padding: 12px; text-align: right;">₹${feeAmount.toFixed(2)}</td>
            <td style="padding: 12px; text-align: right; color: #28a745;">-₹${discount.toFixed(2)}</td>
            <td style="padding: 12px; text-align: right;"><strong>₹${payableAmount.toFixed(2)}</strong></td>
            <td style="padding: 12px; text-align: center;">${statusBadge}</td>
            <td style="padding: 12px; text-align: center;">
                ${inst.payment_status === 'paid' ? 
                    `<button class="btn btn-sm btn-outline-primary" 
                            onclick="viewReceipt('${studentHash}', ${inst.id})"
                            style="border: 1px solid #0d6efd; background: none; color: #0d6efd; padding: 4px 12px; border-radius: 4px; font-size: 12px;">
                        <i class="bi bi-receipt"></i> Receipt
                    </button>` : 
                    '—'
                }
            </td>
        `;

        tbody.appendChild(row);
    });

    table.appendChild(tbody);
    tableContainer.appendChild(table);
    modalBody.appendChild(tableContainer);

    // Show modal
    try {
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    } catch (error) {
        console.error('Error showing modal:', error);
    }
}
// Print function for installment summary
function printInstallmentSummary() {
    const modalBody = document.querySelector('#installmentModal .modal-body');

    if (!modalBody) {
        alert('No content to print');
        return;
    }

    const studentName = document.querySelector('#installmentModal .modal-title')?.textContent || 'Student';

    // Extract only content (NOT modal wrapper)
    const content = modalBody.innerHTML;

    const printWindow = window.open('', '_blank');

    printWindow.document.write(`
        <html>
        <head>
            <title>Installment Summary - ${studentName}</title>

            <style>
                body {
                    font-family: Arial, sans-serif;
                    padding: 20px;
                    color: #000;
                }

                h1, h2, h3 {
                    margin-bottom: 10px;
                }

                .header {
                    text-align: center;
                    margin-bottom: 20px;
                    border-bottom: 2px solid #000;
                    padding-bottom: 10px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 15px;
                }

                th, td {
                    border: 1px solid #000;
                    padding: 8px;
                    font-size: 12px;
                }

                th {
                    background: #f2f2f2;
                    text-align: left;
                }

                .summary-box {
                    border: 1px solid #000;
                    padding: 10px;
                    margin-bottom: 15px;
                }

                .no-print {
                    display: none;
                }

                @media print {
                    body {
                        margin: 0;
                    }
                }
            </style>

        </head>
        <body>

            <div class="header">
                <h2>Installment Summary</h2>
                <p>${studentName}</p>
                <small>Printed on: ${new Date().toLocaleString('en-IN')}</small>
            </div>

            ${content}

        </body>
        </html>
    `);

    printWindow.document.close();

    printWindow.focus();

    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 10);
}

// ==================== RECEIPT FUNCTIONS (ADAPTED) ====================
function viewReceipt(studentHash, installmentId) {
    const student = studentInstallments[studentHash];
    if (!student) return;

    const installment = student.installments.find(inst => inst.id == installmentId);
    if (!installment) return;

    // Log to verify pay_date is coming from backend
    console.log('Installment data:', {
        id: installment.id,
        pay_date: installment.pay_date,
        payment_status: installment.payment_status,
        fee_reference_id: installment.fee_reference_id
    });

    // Build receipt data object with proper pay_date
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
        Section: student.section,
        fee_amount: installment.fee_amount,
        late_fee_amount: installment.late_fee,
        discount_amount: installment.discount,
        pay_fee_amount: installment.payable_amount || (parseFloat(installment.fee_amount || 0) +
            parseFloat(installment.late_fee || 0) -
            parseFloat(installment.discount || 0)),
        payment_type: installment.payment_type || 'cash',
        payment_status: installment.payment_status,
        pay_date: installment.pay_date, // This comes from backend
        due_date: installment.due_date,
        start_date: student.start_date || '',
        fee_reference_id: installment.fee_reference_id,
        transaction_id: installment.transaction_id,
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
    return `RCPT${year}${month}${day}${random}`;
}

function generateReceiptHTML(payment) {
    const receiptPreview = document.getElementById('receiptPreview');

    const formatCurrency = (amount) => {
        return '₹' + parseFloat(amount || 0).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    };

    // Use pay_date from backend for payment date
    const payDate = payment.pay_date ? new Date(payment.pay_date).toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    }) : 'N/A';

    // Also format pay_time if available
    let payTime = '';
    if (payment.pay_date) {
        const dateObj = new Date(payment.pay_date);
        // Check if time is available (not just date)
        if (payment.pay_date.includes(' ')) {
            payTime = dateObj.toLocaleTimeString('en-IN', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
        }
    }

    const dueDate = payment.due_date ? new Date(payment.due_date).toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    }) : 'N/A';

    const startDate = payment.start_date ? new Date(payment.start_date).toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    }) : 'Not specified';

    const feeAmount = parseFloat(payment.fee_amount || 0);
    const lateFee = parseFloat(payment.late_fee_amount || 0);
    const discount = parseFloat(payment.discount_amount || 0);
    const payable = parseFloat(payment.pay_fee_amount || 0);

    const paymentStatusHTML = payment.payment_status === 'paid' ?
        '<span style="color: #28a745; font-weight: bold;"><i class="bi bi-check-circle"></i> PAID</span>' :
        '<span style="color: #dc3545; font-weight: bold;"><i class="bi bi-x-circle"></i> NOT PAID</span>';

    const paymentMethodHTML = {
        online: '<span style="color: #28a745;"><i class="bi bi-credit-card"></i> Online Payment</span>',
        cash: '<span style="color: #007bff;"><i class="bi bi-cash"></i> Cash Payment</span>',
        emi: '<span style="color: #ffc107;"><i class="bi bi-calendar-check"></i> EMI Payment</span>',
        bank: '<span style="color: #6f42c1;"><i class="bi bi-bank"></i> Bank Transfer</span>'
    } [payment.payment_type] || '<span>N/A</span>';

    const receiptHTML = `
        <div class="receipt-paper" id="receiptContent">
            <div class="watermark">PAID</div>
            <div class="receipt-header">
                <div class="institute-name">${payment.instituteName}</div>
                <div class="receipt-title">FEE PAYMENT RECEIPT</div>
                <div class="institute-address">${payment.instituteAddress}</div>
                <div class="institute-contact">Phone: ${payment.institutePhone} | Email: ${payment.instituteEmail}</div>
            </div>
            <div class="student-details-section" style="display: flex; gap: 20px; margin-bottom: 20px;">
                <div style="flex:1; border:1px solid #ddd; padding:15px; background:#f8f9fa;">
                    <div style="font-weight:bold; margin-bottom:10px;"><i class="bi bi-person-circle"></i> STUDENT INFORMATION</div>
                    <div><span style="font-weight:500;">Name:</span> ${payment.student_name}</div>
                    <div><span style="font-weight:500;">Reg No:</span> ${payment.student_reg}</div>
                </div>
                <div style="flex:1; border:1px solid #ddd; padding:15px; background:#f8f9fa;">
                    <div style="font-weight:bold; margin-bottom:10px;"><i class="bi bi-receipt"></i> RECEIPT INFORMATION</div>
                    <div><span style="font-weight:500;">Receipt No:</span> ${payment.receiptNumber}</div>
                    <div><span style="font-weight:500;">Date:</span> ${payment.receiptDate} ${payment.receiptTime}</div>
                </div>
            </div>
            <div style="border:1px solid #ddd; padding:15px; background:#f8f9fa; margin-bottom:20px;">
                <div style="font-weight:bold; margin-bottom:10px;"><i class="bi bi-mortarboard"></i> ACADEMIC INFORMATION</div>
                <div style="display: grid; grid-template-columns: repeat(3,1fr); gap:10px;">
                    <div><span style="font-weight:500;">Department:</span> ${payment.department}</div>
                    <div><span style="font-weight:500;">Class:</span> ${payment.course}</div>
                    <div><span style="font-weight:500;">Batch:</span> ${payment.batch}</div>
                    <div><span style="font-weight:500;">Academic Year:</span> ${payment.academic_year}</div>
                    <div><span style="font-weight:500;">Section:</span> ${payment.Section}</div>
                </div>
            </div>
            <div style="display: flex; gap: 20px; margin-bottom:20px;">
                <div style="flex:1; border:1px solid #ddd; padding:15px; background:#f8f9fa;">
                    <div style="font-weight:bold; margin-bottom:10px;"><i class="bi bi-cash-stack"></i> FEE DETAILS</div>
                    <div><span style="font-weight:500;">Fee Type:</span> Class Fee</div>
                    <div><span style="font-weight:500;">Due Date:</span> ${dueDate}</div>
                    <div><span style="font-weight:500;">Payment Date:</span> ${payDate} ${payTime ? `<br><small>at ${payTime}</small>` : ''}</div>
                    <div style="margin-top:15px;">
                        <div style="display:flex; justify-content:space-between;"><span>Class Fee:</span> ${formatCurrency(feeAmount)}</div>
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
                    ${payment.fee_reference_id ? `
                        <div><span style="font-weight:500;">Transaction ID:</span> <code>${payment.fee_reference_id}</code></div>
                    ` : ''}
                    ${payment.transaction_id ? `
                        <div><span style="font-weight:500;">Reference No:</span> <code>${payment.transaction_id}</code></div>
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
                <div style="display: none; justify-content: space-between; margin-top: 20px; padding-top: 15px; border-top: 1px dashed #ddd;">
                    <div class="d-none" style="text-align: center; flex:1;">
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
        const originalText = downloadBtn.innerHTML;
        downloadBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Generating PDF...';
        downloadBtn.disabled = true;

        const receiptElement = document.getElementById('receiptContent');
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
            `Course_Fee_Receipt_${currentReceiptData.receiptNumber}_${currentReceiptData.student_name.replace(/\s+/g, '_')}.pdf`;
        pdf.save(fileName);

        showDownloadSuccess('PDF');
    } catch (error) {
        console.error(error);
        alert('Error generating PDF. Please try again.');
    } finally {
        const downloadBtn = document.querySelector('#receiptModal .btn-primary');
        downloadBtn.innerHTML = originalText;
        downloadBtn.disabled = false;
    }
}

async function downloadReceiptAsImage() {
    if (!currentReceiptData) return;
    try {
        const downloadBtn = document.querySelector('#receiptModal .btn-success');
        const originalText = downloadBtn.innerHTML;
        downloadBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Generating Image...';
        downloadBtn.disabled = true;

        const receiptElement = document.getElementById('receiptContent');
        const canvas = await html2canvas(receiptElement, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#ffffff'
        });
        const imgData = canvas.toDataURL('image/png');

        const link = document.createElement('a');
        link.download =
            `Course_Fee_Receipt_${currentReceiptData.receiptNumber}_${currentReceiptData.student_name.replace(/\s+/g, '_')}.png`;
        link.href = imgData;
        link.click();

        showDownloadSuccess('Image');
    } catch (error) {
        console.error(error);
        alert('Error generating image. Please try again.');
    } finally {
        const downloadBtn = document.querySelector('#receiptModal .btn-success');
        downloadBtn.innerHTML = originalText;
        downloadBtn.disabled = false;
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
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.pagination-btn').forEach(link => {
        if (link.tagName === 'A') {
            link.addEventListener('click', function(e) {
                let loaderTimeout = setTimeout(() => {
                    showLoader();
                }, 100); // only show if it takes longer than 100ms

                window.addEventListener('beforeunload', function() {
                    clearTimeout(loaderTimeout);
                    showLoader();
                });
            });
        }
    });
});
// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    // Initialize flatpickr date range picker
    flatpickr("#dateRangeFilter", {
        mode: "range",
        dateFormat: "Y-m-d",
        placeholder: "Select date range"
    });

    // Add receipt button to action column for paid installments
    document.querySelectorAll('.payment-row').forEach(row => {
        const studentHash = row.dataset.studentHash;
        const student = studentInstallments[studentHash];
        if (student && student.current_installment && student.current_installment.payment_status ===
            'paid') {
            const actionCell = row.querySelector('.action-buttons');
            if (actionCell && !actionCell.querySelector('.receipt-btn')) {
                const receiptBtn = document.createElement('button');
                receiptBtn.className = 'action-btn receipt-btn';
                receiptBtn.title = 'View Receipt';
                receiptBtn.innerHTML = '<i class="bi bi-receipt"></i>';
                receiptBtn.onclick = () => viewReceipt(studentHash, student.current_installment.id);
                actionCell.appendChild(receiptBtn);
            }
        }
    });
});
</script>
@endsection