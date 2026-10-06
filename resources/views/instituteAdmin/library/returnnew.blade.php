@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Return Books Management</title>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
/* ERP Table Styles */
.erp-table {
    width: 100%;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.erp-table thead {
    background: #f8fafc;
    border-bottom: 2px solid #e2e8f0;
}

.erp-table th {
    width: auto;
    padding: 10px 14px;
    font-weight: 600;
    color: #475569;
    text-align: left;
    font-size: 13px;
    border-bottom: 1px solid #e2e8f0;
    cursor: pointer;
    user-select: none;
    transition: background-color 0.2s;
    position: relative;
}

.erp-table th:hover {
    background-color: #f1f5f9;
}

.erp-table th.sortable {
    padding-right: 28px;
}

.sort-icons {
    position: absolute;
    right: 8px;
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
    color: #3b82f6;
}

.erp-table td {
    padding: 10px 14px;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
    font-size: 13px;
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

.erp-table tbody tr.selected-row {
    background-color: #e0f2fe !important;
}

/* Status Badges */
.status-badge {
    padding: 3px 10px;
    border-radius: 16px;
    font-size: 11px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: transform 0.2s;
}

.status-badge:hover {
    transform: scale(1.05);
}

.status-issued {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.status-overdue {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.status-returned {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #bfdbfe;
}

.status-reissued {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

/* Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 1px solid #e2e8f0;
    animation: fadeIn 0.5s ease;
}

.page-title {
    font-size: 22px;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.page-subtitle {
    color: #64748b;
    font-size: 13px;
    margin-top: 4px;
}

/* Stats Cards */
.stats-card {
    transition: transform 0.2s;
    border-radius: 10px;
}

.stats-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.stats-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

/* Suggestions Box */
.suggestions-box {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
}

.suggestion-item {
    padding: 10px 16px;
    cursor: pointer;
    transition: background 0.2s;
    border-bottom: 1px solid #f1f5f9;
}

.suggestion-item:hover {
    background: #f8fafc;
}

.suggestion-item strong {
    color: #2563eb;
}

.suggestion-item small {
    color: #64748b;
}

.suggestion-group {
    padding: 8px 16px;
    background: #f8fafc;
    font-weight: 600;
    color: #475569;
    font-size: 12px;
}

/* Filter Badges */
.filter-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: #f1f5f9;
    border-radius: 20px;
    font-size: 13px;
    color: #1e293b;
    text-decoration: none;
}

.filter-badge i {
    color: #64748b;
}

.filter-badge .remove {
    cursor: pointer;
    margin-left: 6px;
    color: #94a3b8;
}

.filter-badge .remove:hover {
    color: #dc2626;
}

/* Card Styling - SMALLER */
.card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border: 1px solid #e2e8f0;
    margin-bottom: 20px;
    overflow: hidden;
}

.card-header {
    padding: 12px 20px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h2 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-body {
    padding: 20px;
}

/* Book Count Badge */
.book-count {
    background: #3b82f6;
    color: white;
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 12px;
    font-weight: 500;
}

/* Back Button - SMALLER */
.add-btn {
    padding: 8px 16px;
    background-color: #007BFF;
    border: none;
    color: white;
    cursor: pointer;
    border-radius: 5px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 500;
    font-size: 13px;
}

.add-btn:hover {
    background-color: #0056b3;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
}

/* Radio Select */
.radio-select {
    width: 16px;
    height: 16px;
    cursor: pointer;
    border-radius: 50%;
    border: 2px solid #cbd5e1;
    transition: all 0.2s;
}

.radio-select:checked {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

/* Book Info */
.book-info {
    font-size: 11px;
    color: #64748b;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.book-id {
    color: #3b82f6;
    font-family: monospace;
    background: #eff6ff;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 10px;
}

/* Amount Cells */
.amount-cell {
    font-family: 'SF Mono', 'Monaco', 'Inconsolata', monospace;
    font-weight: 500;
}

.text-danger {
    color: #dc2626 !important;
}

.text-success {
    color: #16a34a !important;
}

.text-warning {
    color: #d97706 !important;
}

/* Filter container - SMALLER */
.filter-container {
    background: #fff;
    border-radius: 6px;
    padding: 6px 12px;
    border: 1px solid #e2e8f0;
}

.search-box {
    position: relative;
    width: 260px;
}

.search-icon {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 13px;
}

.search-input {
    width: 100%;
    height: 34px;
    padding: 0 10px 0 32px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 13px;
    background: white;
    color: #334155;
    transition: all 0.2s;
}

.search-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Fine Summary Card - SMALLER */
.fine-card {
    border-left: 4px solid #f59e0b;
    margin-top: 20px;
}

.fine-card .card-title {
    font-size: 15px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.fine-card .form-label {
    font-size: 13px;
    font-weight: 500;
    color: #475569;
    margin-bottom: 6px;
}

.fine-card .form-select,
.fine-card .form-control {
    height: 38px;
    padding: 0 12px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 13px;
    background: white;
    transition: all 0.2s;
}

.fine-card .form-select:focus,
.fine-card .form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Fine Type Cards - SMALLER */
.fine-type-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 14px;
    margin-bottom: 14px;
    transition: all 0.2s;
}

.fine-type-card:hover {
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.fine-type-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 0;
}

.fine-type-icon {
    width: 38px;
    height: 38px;
    border-radius: 8px;
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
    color: #1e293b;
    margin: 0;
}

.fine-type-amount {
    font-size: 18px;
    font-weight: 700;
    color: #dc2626;
}

.fine-type-desc {
    color: #64748b;
    font-size: 12px;
    margin-top: 2px;
}

/* Fine Breakdown - SMALLER */
.fine-breakdown {
    background: #f8fafc;
    border-radius: 8px;
    padding: 16px;
    margin: 16px 0;
    border: 1px solid #e2e8f0;
}

.fine-breakdown-title {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.fine-breakdown-table {
    width: 100%;
}

.fine-breakdown-table td {
    padding: 6px 0;
    border-bottom: 1px dashed #e2e8f0;
    font-size: 13px;
}

.fine-breakdown-table tr:last-child td {
    border-bottom: none;
    padding-top: 10px;
    font-weight: 700;
}

/* Payment Method Cards - SMALLER */
.payment-method-container {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin: 16px 0;
}

.payment-method-card {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px 16px;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    flex: 1;
    min-width: 120px;
}

.payment-method-card:hover {
    border-color: #3b82f6;
    background: #f8fafc;
}

.payment-method-card.selected {
    border-color: #3b82f6;
    background: #eff6ff;
}

.payment-method-icon {
    font-size: 24px;
    margin-bottom: 6px;
}

.payment-method-title {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 2px;
    font-size: 14px;
}

.payment-method-desc {
    font-size: 11px;
    color: #64748b;
}

/* Online Options Container - SMALLER */
.online-options-container {
    margin-top: 16px;
    padding: 16px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.online-options-title {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.online-options-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

@media (max-width: 640px) {
    .online-options-grid {
        grid-template-columns: 1fr;
    }
}

/* QR Code Section - SMALLER */
.qr-section {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 16px;
    text-align: center;
}

.qr-placeholder {
    width: 150px;
    height: 150px;
    background: #f8fafc;
    border: 2px dashed #cbd5e1;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: #64748b;
}

.qr-placeholder i {
    font-size: 36px;
    margin-bottom: 8px;
}

.qr-placeholder p {
    font-size: 12px;
    margin: 0;
}

/* Payment Link Section - SMALLER */
.payment-link-section {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 16px;
}

.payment-link-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.payment-link-icon {
    width: 38px;
    height: 38px;
    background: #eff6ff;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3b82f6;
    font-size: 20px;
}

.payment-link-title {
    font-size: 15px;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.payment-link-desc {
    font-size: 11px;
    color: #64748b;
    margin-top: 2px;
}

/* Payment Link Container - SMALLER */
.payment-link-container {
    background: #f8fafc;
    border-radius: 6px;
    padding: 12px;
    margin-top: 12px;
    border: 1px solid #e2e8f0;
}

.payment-link-container .input-group {
    display: flex;
    align-items: center;
}

.payment-link-container .form-control {
    height: 34px;
    font-size: 12px;
}

/* Submit Button - SMALLER */
.submit-btn {
    background: #3b82f6;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 10px 24px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.submit-btn:hover {
    background: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(59, 130, 246, 0.2);
}

.btn-outline-primary {
    background: white;
    color: #3b82f6;
    border: 1px solid #3b82f6;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-outline-primary:hover {
    background: #eff6ff;
    color: #3b82f6;
}

.btn-outline-primary.small {
    padding: 6px 12px;
    font-size: 12px;
}

/* Empty State - SMALLER */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #64748b;
}

.empty-state-icon {
    font-size: 40px;
    color: #cbd5e1;
    margin-bottom: 12px;
}

.empty-state h4 {
    font-size: 18px;
    margin-bottom: 8px;
}

.empty-state p {
    font-size: 13px;
}

/* Loading Animation */
.loading-spinner {
    display: inline-block;
    width: 14px;
    height: 14px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #3b82f6;
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

/* Book Copy Details - SMALLER */
.book-copy-details {
    background: #eff6ff;
    border-radius: 8px;
    padding: 14px;
    margin-top: 14px;
    border-left: 4px solid #3b82f6;
}

.book-copy-details table {
    width: 100%;
    font-size: 12px;
}

.book-copy-details td {
    padding: 4px 6px;
}

.book-copy-details td:first-child {
    color: #64748b;
    width: 35%;
}

/* Fine Type Selector - SMALLER */
.fine-type-selector {
    background: #f8fafc;
    border-radius: 8px;
    padding: 14px;
    margin-top: 14px;
    border-left: 4px solid #3b82f6;
}

.fine-type-selector h6 {
    font-size: 14px;
    margin-bottom: 12px;
}
/* Divider - SMALLER */
.divider {
    display: flex;
    align-items: center;
    text-align: center;
    color: #64748b;
    font-size: 13px;
    margin: 20px 0;
}

.divider::before,
.divider::after {
    content: '';
    flex: 1;
    border-bottom: 1px solid #e2e8f0;
}

.divider::before {
    margin-right: 12px;
}

.divider::after {
    margin-left: 12px;
}

/* Auto Fine Badge */
.auto-fine-badge {
    background: #e6f7e6;
    color: #0e7e0e;
    padding: 3px 10px;
    border-radius: 16px;
    font-size: 11px;
    font-weight: 500;
}

/* Fine Amount Preview */
.fine-amount-preview {
    font-size: 16px;
    font-weight: 700;
    color: #dc2626;
    margin-top: 6px;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
}

.pagination {
    display: flex;
    padding-left: 0;
    list-style: none;
    border-radius: 6px;
}

.pagination li {
    margin: 0 2px;
}

.pagination li a,
.pagination li span {
    position: relative;
    display: block;
    padding: 6px 12px;
    margin-left: -1px;
    line-height: 1.25;
    color: #3b82f6;
    background-color: #fff;
    border: 1px solid #e2e8f0;
    font-size: 13px;
    text-decoration: none;
}

.pagination li.active span {
    z-index: 3;
    color: #fff;
    background-color: #3b82f6;
    border-color: #3b82f6;
}

.pagination li.disabled span {
    color: #cbd5e1;
    pointer-events: none;
    cursor: auto;
    background-color: #fff;
    border-color: #e2e8f0;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .card-header {
        flex-direction: column;
        gap: 10px;
        align-items: stretch;
    }

    .search-box {
        width: 100%;
    }

    .filter-container {
        width: 100%;
    }

    .payment-method-container {
        flex-direction: column;
    }

    .payment-method-card {
        width: 100%;
    }

    .online-options-grid {
        grid-template-columns: 1fr;
    }
}

.text-muted {
    color: white !important;
}

/* Add to your existing CSS */
.submit-btn:not(.d-none) {
    display: inline-flex !important;
}

.payment-method-card.selected {
    border-color: #3b82f6;
    background: #eff6ff;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
}
  /* Reduce yellow section padding */
    #damageDescriptionSection {
        padding: 10px 12px !important;
    }

    /* Make inputs compact */
    #damageDescriptionSection .form-control {
        height: 32px;
        font-size: 13px;
        padding: 4px 8px;
    }

    /* Make textarea small */
    #damageDescriptionSection textarea {
        height: 32px !important;
        resize: none;
    }

    /* Smaller labels */
    #damageDescriptionSection label {
        font-size: 13px;
        margin-bottom: 4px;
    }
</style>

<div class="container-fluid px-4">
    <!-- Header with Stats -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Return Books Management</h1>
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
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stats-card bg-primary bg-opacity-10 border-0">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Active Issues</h6>
                            <h3 class="fw-bold mb-0 text-white">{{ $totalIssues ?? $issues->total() ?? 0 }}</h3>
                        </div>
                        <div class="stats-icon bg-primary text-white">
                            <i class="bi bi-book"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stats-card bg-warning bg-opacity-10 border-0">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Overdue Books</h6>
                            <h3 class="fw-bold mb-0 text-white">{{ $totalOverdue ?? 0 }}</h3>
                        </div>
                        <div class="stats-icon bg-warning text-white">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stats-card bg-danger bg-opacity-10 border-0">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">With Fine</h6>
                            <h3 class="fw-bold mb-0 text-white">{{ $totalWithFine ?? 0 }}</h3>
                        </div>
                        <div class="stats-icon bg-danger text-white">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stats-card bg-success bg-opacity-10 border-0">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Ready to Return</h6>
                            <h3 class="fw-bold mb-0 text-white">{{ ($totalIssues ?? 0) - ($totalWithFine ?? 0) }}</h3>
                        </div>
                        <div class="stats-icon bg-success text-white">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filter Section - SINGLE FILTER FORM -->
    <div class="card mb-4">
        <div class="card-header">
            <h2 class="mb-0">
                <i class="bi bi-funnel text-primary"></i>
                Advanced Filters
            </h2>
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse"
                data-bs-target="#filterCollapse">
                <i class="bi bi-sliders2"></i> Toggle Filters
            </button>
        </div>
        <div class="collapse show" id="filterCollapse">
            <div class="card-body">
                <!-- FILTER FORM - GET method to library.return.create -->
                <form method="GET" action="{{ route('library.return.create') }}" id="filterForm">
                    <div class="row g-3">
                        <!-- Search by Student/Employee/Copy ID -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-search"></i> Search
                            </label>
                            <div class="position-relative">
                                <input type="text" name="search" id="globalSearch" class="form-control"
                                    placeholder="Search by Student Name, Reg. No., Copy ID, Book Title..."
                                    value="{{ request('search') }}" autocomplete="off">
                                <div id="searchSuggestions" class="suggestions-box" style="display: none;"></div>
                                @if(request('search'))
                                <a href="{{ route('library.return.create') }}"
                                    class="position-absolute end-0 top-50 translate-middle-y me-3 text-muted">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                                @endif
                            </div>
                        </div>

                        <!-- Overdue Status Filter - REAL TIME -->
                        <div class="col-md-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-clock-history"></i> Overdue Status
                            </label>
                            <select name="overdue_status" class="form-control">
                                <option value="">All Issues</option>
                                <option value="overdue" {{ request('overdue_status') == 'overdue' ? 'selected' : '' }}>Overdue (Past Due Date)</option>
                                <option value="not_overdue" {{ request('overdue_status') == 'not_overdue' ? 'selected' : '' }}>Not Overdue</option>
                            </select>
                            <small class="text-muted d-block mt-1">Based on due date vs today</small>
                        </div>

                        <!-- Fine Status Filter -->
                        <div class="col-md-3">
                            <label class="form-label fw-bold">
                                <i class="bi bi-cash"></i> Fine Status
                            </label>
                            <select name="fine_status" class="form-control">
                                <option value="">All Fines</option>
                                <option value="with_fine" {{ request('fine_status') == 'with_fine' ? 'selected' : '' }}>
                                    With Fine</option>
                                <option value="without_fine"
                                    {{ request('fine_status') == 'without_fine' ? 'selected' : '' }}>Without Fine
                                </option>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-calendar"></i> Issue Date From
                            </label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-calendar"></i> Due Date To
                            </label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>

                        <!-- Quick Filters -->
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="bi bi-funnel"></i> Apply Filters
                                </button>
                                <a href="{{ route('library.return.create') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Quick Filter Dropdown (Simplified) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-lightning-charge"></i> Quick Filters
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('library.return.create', ['overdue_status' => 'overdue']) }}">
                        <i class="bi bi-exclamation-triangle text-warning"></i> Overdue Books
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('library.return.create', ['fine_status' => 'with_fine']) }}">
                        <i class="bi bi-cash-coin text-danger"></i> With Fine
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('library.return.create') }}">
                        <i class="bi bi-book text-primary"></i> All Active Issues
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('library.return.create', ['from_date' => \Carbon\Carbon::now()->subDays(7)->format('Y-m-d')]) }}">
                        <i class="bi bi-calendar-week"></i> Issued in last 7 days
                    </a></li>
                </ul>
            </div>
        </div>
        <div>
            <span class="text-muted me-2">Showing {{ $issues->firstItem() ?? 0 }} - {{ $issues->lastItem() ?? 0 }} of
                {{ $issues->total() ?? 0 }} issues</span>
            <select class="form-select form-select-sm d-inline-block w-auto" onchange="window.location.href=this.value">
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

    <!-- Main Card -->
    <div class="card">
        <div class="card-header">
            <h2>
                <i class="bi bi-list-check text-primary"></i>
                Issued Books
                <span class="book-count">{{ $issues->total() ?? 0 }}</span>
            </h2>
            <div class="filter-container">
                <div class="search-box">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="tableSearch" class="search-input" placeholder="Quick filter in table...">
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Books Table -->
            <div class="table-responsive">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th width="50" class="text-center">Select</th>
                            <th class="sortable" onclick="sortTable('title')">
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
                            <th class="sortable" onclick="sortTable('days_overdue')" class="text-center">
                                Overdue Days
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill"></i>
                                    <i class="sort-icon bi bi-caret-down-fill"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('fine_amount')" class="text-end">
                                Fine Amount
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill"></i>
                                    <i class="sort-icon bi bi-caret-down-fill"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('payment_status')" class="text-center">
                                Payment Status
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill"></i>
                                    <i class="sort-icon bi bi-caret-down-fill"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('status')" class="text-center">
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

                        $paidAmount = $issue->paymentLinks->amount ?? '0.00';
                        $remainingAmount = $fineAmount - $paidAmount;

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
                        <tr class="book-row" id="row-{{ $issue->id }}" 
                            data-days-overdue="{{ $daysOverdue }}"
                            data-fine-amount="{{ $fineAmount }}"
                            data-is-overdue="{{ $isOverdue ? '1' : '0' }}"
                            data-grace-days="{{ $overdueRule->grace_period_days ?? 0 }}">
                            <td class="text-center">
                                <input type="radio" name="book_issue_id" value="{{ $issue->id }}"
                                data-fine="{{ $fineAmount }}" 
                                data-title="{{ $issue->libraryBook->title ?? '' }}"
                                data-copy-id="{{ $issue->copy->copy_id ?? '' }}"
                                data-book-cost="{{ $issue->libraryBook->price ?? 0 }}" 
                                data-id="{{ $issue->id }}"
                                data-days="{{ $daysOverdue }}" 
                                data-is-overdue="{{ $isOverdue ? '1' : '0' }}"
                                data-book-issue-id="{{ $issue->book_issue_id }}"  
                                class="radio-select"
                                {{ $issue->status == 'returned' ? 'disabled' : '' }} required>
                                   
                            </td>
                            <td>
                                <strong>{{ $issue->libraryBook->title ?? 'N/A' }}</strong>
                                <div class="book-info">
                                    @if($isOverdue && $issue->status != 'returned')
                                    <span class="auto-fine-badge">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        Overdue Fine
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($issue->copy)
                                <div class="copy-details">
                                    <span class="copy-id-badge"
                                        style="background: #eff6ff; color: #3b82f6; padding: 3px 6px; border-radius: 4px; font-family: monospace; font-size: 12px; display: inline-block; margin-bottom: 4px;">
                                        <i class="bi bi-upc-scan"></i>
                                        {{ $issue->copy->copy_id ?? '' }}
                                    </span>
                                    @if($issue->copy->writer_name ?? false)
                                    <div class="small text-secondary mt-1">
                                        <i class="bi bi-pencil"></i>
                                        {{ $issue->copy->writer_name }}
                                    </div>
                                    @endif
                                </div>
                                @else
                                <span class="text-muted small">Copy details not available</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $name ?? 'N/A' }}</strong>
                                <div class="book-info">
                                    @if($issue->issueable_type == 'App\Models\StudentParentDetails')
                                    <span class="small text-primary">Student ({{ $code }})</span>
                                    @elseif($issue->issueable_type == 'App\Models\EmployeeDetails')
                                    <span class="small text-primary">Employee ({{ $code }})</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($issue->issue_date)->format('d M Y') }}</td>
                            <td>
                                <span class="{{ $isOverdue ? 'text-danger fw-medium' : '' }}">
                                    {{ $due->format('d M Y') }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($daysOverdue > 0)
                                <span class="fw-medium text-danger">
                                    {{ $daysOverdue }} days
                                </span>
                                @else
                                <span class="status-badge status-returned">
                                    On time
                                </span>
                                @endif
                            </td>
                            <td class="text-end amount-cell">
                                <span id="fine-display-{{ $issue->id }}" class="fw-bold">
                                    ₹{{ number_format($fineAmount, 2) }}
                                </span>
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
                                    <i class="bi bi-check-circle"></i>
                                    @elseif($paymentStatus == 'Pending')
                                    <i class="bi bi-clock"></i>
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
                                'issued' => 'bi-arrow-up-circle',
                                'overdue' => 'bi-exclamation-triangle',
                                'reissued' => 'bi-arrow-repeat',
                                'returned' => 'bi-arrow-down-circle',
                                default => 'bi-question-circle'
                                };
                                @endphp
                                <span class="status-badge {{ $badgeClass }}">
                                    <i class="bi {{ $statusIcon }}"></i>
                                    {{ ucfirst($issue->status) }}
                                </span>
                                @if($isOverdue && $issue->status != 'overdue' && $issue->status != 'returned')
                                <br><small class="text-danger">(Actually overdue)</small>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-inbox"></i>
                                    </div>
                                    <h4>No Books Found</h4>
                                    <p class="mb-3">No issued books match your search criteria</p>
                                    <a href="{{ route('library.return.create') }}" class="btn btn-outline-primary">
                                        <i class="bi bi-arrow-counterclockwise"></i> Clear Filters
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(method_exists($issues, 'links'))
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Showing {{ $issues->firstItem() ?? 0 }} to {{ $issues->lastItem() ?? 0 }} of
                    {{ $issues->total() ?? 0 }} entries
                </div>
                <div class="pagination-wrapper">
                    {{ $issues->appends(request()->query())->links() }}
                </div>
            </div>
            @endif

            <!-- STORE FORM - for return submission (POST) -->
            <form method="POST" action="{{ route('library.return.store') }}" id="returnForm">
                @csrf
                
                <!-- Hidden input for selected book issue ID -->
                <input type="hidden" name="book_issue_id" id="selected_book_issue_id" value="">
                
                <!-- Hidden field for use_manual_fine -->
                <input type="hidden" name="use_manual_fine" id="use_manual_fine" value="0">
                 <!-- Add this near your other hidden inputs -->
                <input type="hidden" name="transaction_ref_id" id="transaction_ref_id" value="">
                <!-- Add these near your other hidden inputs -->
                <input type="hidden" name="total_fine_amount" id="total_fine_amount" value="0">
                <input type="hidden" name="manual_fine_amount" id="manual_fine_amount" value="0">
                <input type="hidden" name="overdue_fine_amount" id="overdue_fine_amount" value="0">
                <input type="hidden" name="fine_breakdown" id="fine_breakdown" value="">
                <!-- Fine & Payment Section -->
                <div id="fineSummary" class="d-none mt-4">
                    <div class="card fine-card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-cash-coin text-warning"></i>
                                Fine & Payment Details
                            </h5>

                            <!-- Selected Book Info -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Selected Book</label>
                                    <div class="h5 fw-bold text-dark" id="selectedBook" style="font-size: 18px;">-</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Total Fine Amount</label>
                                    <div class="h3 text-warning fw-bold" id="selectedFine" style="font-size: 24px;">₹0.00
                                    </div>
                                </div>
                            </div>

                            <!-- Book Copy Details Container -->
                            <div id="bookCopyDetailsContainer"></div>

                            <!-- Auto Overdue Fine Info -->
                            <div id="overdueFineInfo" class="alert alert-info d-none"
                                style="padding: 12px; font-size: 13px;">
                                <i class="bi bi-info-circle"></i>
                                <strong>Overdue Fine Applied Automatically</strong>
                                <span id="overdueFineDetails"></span>
                            </div>

                            <!-- Manual Fine Type Selection -->
                            <div id="manualFineSection" class="fine-type-selector d-none">
                                <h6 class="fw-bold mb-2">
                                    <i class="bi bi-exclamation-triangle text-warning"></i>
                                    Additional Fines
                                </h6>
                                <div class="row g-2">
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
                                        <div id="fineAmountPreview" class="fine-amount-preview text-end"></div>
                                    </div>
                                </div>

                                <!-- Damage Description -->
                                <div id="damageDescriptionSection" class="damage-description" style="display: none;">
                                    <div class="row g-2 align-items-end">
                                        
                                        <!-- Pages Count -->
                                        <div class="col-md-3">
                                            <label class="fw-bold">Pages Count</label>
                                            <input type="number" 
                                                name="pages_count" 
                                                class="form-control"
                                                placeholder="Pages">
                                        </div>

                                        <!-- Damage Description -->
                                        <div class="col-md-6">
                                            <label class="fw-bold">Damage Description</label>
                                            <textarea name="damage_description"
                                                    class="form-control"
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
                                <!-- Offline Payment (Cash) -->
                                <div class="payment-method-card" data-method="offline">
                                    <div class="payment-method-icon">
                                        <i class="bi bi-cash-stack text-success"></i>
                                    </div>
                                    <div class="payment-method-title">Offline</div>
                                    <div class="payment-method-desc">Pay by Cash</div>
                                </div>

                                <!-- Online Payment -->
                                <div class="payment-method-card" data-method="online">
                                    <div class="payment-method-icon">
                                        <i class="bi bi-globe text-primary"></i>
                                    </div>
                                    <div class="payment-method-title">Online</div>
                                    <div class="payment-method-desc">QR Code or Payment Link</div>
                                </div>

                                <!-- Waive Off -->
                                <div class="payment-method-card" data-method="waive-off">
                                    <div class="payment-method-icon">
                                        <i class="bi bi-gift text-warning"></i>
                                    </div>
                                    <div class="payment-method-title">Waive Off</div>
                                    <div class="payment-method-desc">Waive the entire fine</div>
                                </div>
                            </div>

                            <!-- Hidden payment method input -->
                            <input type="hidden" name="payment_method" id="payment_method" value="">
                            
                            <!-- Online Payment Options (QR + Payment Link) -->
                            <div id="onlineOptionsContainer" class="online-options-container" style="display: none;">
                                <div class="online-options-title">
                                    <i class="bi bi-credit-card"></i>
                                    Online Payment Options
                                </div>

                                <div class="online-options-grid">
                                    <!-- QR Code Option -->
                                    <div class="qr-section">
                                        <h6 class="fw-bold mb-2" style="font-size: 14px;">
                                            <i class="bi bi-qr-code-scan"></i>
                                            Scan QR Code
                                        </h6>
                                        <div class="qr-placeholder">
                                            <i class="bi bi-qr-code"></i>
                                            <p class="mt-1">QR Code</p>
                                            <small class="text-muted">Coming Soon</small>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">Scan using any UPI app</small>
                                        </div>
                                    </div>

                                    <!-- Payment Link Option -->
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
                                    <i class="bi bi-check-circle me-2"></i>Mark as Returned
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
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
                resetPaymentMethod();
                // In the radio change event handler, after calculating fines
                document.getElementById('overdue_fine_amount').value = overdueFineAmount;
                selectedFine = parseFloat(this.dataset.fine || 0);
                selectedBookId = this.value;
                selectedBookTitle = this.dataset.title || '';
                selectedBookCopyId = this.dataset.copyId || '';
                bookCost = parseFloat(this.dataset.bookCost || 0);
                
                // Set the hidden input in the return form
                if (selectedBookIssueId) {
                    selectedBookIssueId.value = this.value;
                }
                actualBookIssueId = this.dataset.bookIssueId || ''; // Add this line
                console.log('Actual book_issue_id:', actualBookIssueId); // For debugging
                overdueDays = parseInt(this.dataset.days || 0);
                graceDays = parseInt(row ? row.dataset.graceDays || 0 : 0);
                hasOverdueFine = this.dataset.isOverdue === '1' || overdueDays > 0;
                overdueFineAmount = selectedFine;
                useManualFine = 0;
                if (useManualFineInput) useManualFineInput.value = 0;

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
                        </small>
                    `;
                }

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

                // Update total fine
                if (fineAmountEl) fineAmountEl.textContent = formatCurrency(selectedFine);

                // Check payment method visibility
                checkPaymentMethodVisibility();
                updateFineBreakdown();
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
            document.getElementById('manual_fine_amount').value = manualFineAmount;
                document.getElementById('total_fine_amount').value = selectedFine;

                // Store fine breakdown as JSON
                const fineBreakdown = [];
                const appliedFines = appliedFinesContainer.querySelectorAll('.fine-type-card');
                appliedFines.forEach(fine => {
                    fineBreakdown.push({
                        type: fine.dataset.fineType,
                        amount: parseFloat(fine.dataset.amount)
                    });
                });
                document.getElementById('fine_breakdown').value = JSON.stringify(fineBreakdown);
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
                    if (paymentMethodInput) paymentMethodInput.value = 'cash';

                    // Hide online options
                    if (onlineOptionsContainer) onlineOptionsContainer.style.display = 'none';
                    if (linkResult) linkResult.innerHTML = '';

                    // Show return button immediately
                    if (returnbtn) {
                        returnbtn.classList.remove('d-none');
                        returnbtn.disabled = false;
                    }

                    console.log('Payment method set to: cash');

                } else if (method === 'online') {
                    // Online payment - will generate payment link
                    if (selectedFine > 0) {
                        // For online, we'll set payment_method to 'online' first
                        if (paymentMethodInput) paymentMethodInput.value = 'online';

                        if (onlineOptionsContainer) onlineOptionsContainer.style.display =
                            'block';
                        if (returnbtn) {
                            returnbtn.classList.add('d-none');
                            returnbtn.disabled = false;
                        }

                        console.log('Payment method set to: online');
                    } else {
                        alert('No fine amount to pay online.');
                        this.classList.remove('selected');
                        if (paymentMethodInput) paymentMethodInput.value = '';
                        selectedPaymentMethod = '';
                    }

                } else if (method === 'waive-off') {
                    // Waive off - payment_method = waive-off
                    if (paymentMethodInput) paymentMethodInput.value = 'waive-off';

                    // Hide online options
                    if (onlineOptionsContainer) onlineOptionsContainer.style.display = 'none';
                    if (linkResult) linkResult.innerHTML = '';

                    // Show return button immediately
                    if (returnbtn) {
                        returnbtn.classList.remove('d-none');
                        returnbtn.disabled = false;
                    }

                    console.log('Payment method set to: waive-off');
                }
            });
        });
    }

    // 4. Generate Payment Link
    if (generateLinkBtn) {
        generateLinkBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const selectedRadio = document.querySelector('input[name="book_issue_id"]:checked');
            if (!selectedRadio) {
                alert("Please select a book first.");
                return;
            }

            const bookIssueTableId = selectedRadio.value; // This is the table ID (6)
            const actualBookIssueId = selectedRadio.dataset.bookIssueId; // This is the actual book_issue_id (BI123456)
            
            console.log('Table ID:', bookIssueTableId);
            console.log('Actual Book Issue ID:', actualBookIssueId);
            
            if (!actualBookIssueId || selectedFine <= 0) {
                alert("Please select a valid book with fine amount first.");
                return;
            }

            if (selectedPaymentMethod !== 'online') {
                alert("Please select Online payment method first.");
                return;
            }

            generateLinkBtn.disabled = true;
            generateLinkBtn.innerHTML = '<span class="loading-spinner"></span> Generating...';

            // Build description with actual issue ID reference
            let description = `Fine for Book Issue #${actualBookIssueId}: ${selectedBookTitle}, Copy: ${selectedBookCopyId}`;

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

            // Log for debugging
            console.log('Generating payment link for actual book_issue_id:', actualBookIssueId);
            console.log('Amount:', selectedFine);
            console.log('Description:', description);

            fetch("{{ route('payment.link.create') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        amount: selectedFine,
                        user_transaction_refered_id: actualBookIssueId, // Now passing the actual book_issue_id
                        payment_type: "library-book-fine",
                        description: description
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log('Payment link response:', data);
                    
                    if (data.success) {
                        if (paymentMethodInput) paymentMethodInput.value = 'online';

                        // Store the transaction reference ID in the hidden input
                        const transactionRefInput = document.getElementById('transaction_ref_id');
                        if (transactionRefInput && data.transaction_id) {
                            transactionRefInput.value = data.transaction_id;
                        }

                        if (linkResult) {
                            linkResult.innerHTML = `
                            <div class="payment-link-container">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="badge bg-success me-2">✓</span>
                                        <strong>Payment Link Generated</strong>
                                        <small class="d-block text-muted mt-1">
                                            Issue #${actualBookIssueId} | Amount: ${formatCurrency(selectedFine)} | ${selectedBookTitle} | Copy: ${selectedBookCopyId}
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
                                        <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText('${data.payment_link}')">
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
                            <br><small>${err.message}</small>
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
   
    // 5. Form validation and submission
    if (returnForm) {
        returnForm.addEventListener('submit', function(e) {
            e.preventDefault();

            console.log('Return form submission triggered');
            
            const selectedRadio = document.querySelector('input[name="book_issue_id"]:checked');
            if (!selectedRadio) {
                alert('Please select a book to return');
                return false;
            }

            // Make sure the hidden input has the value
            if (selectedBookIssueId) {
                selectedBookIssueId.value = selectedRadio.value;
            }

            const row = selectedRadio.closest('tr');
            const returnStatus = row ? row.querySelector('td:last-child .status-badge')?.textContent
                .toLowerCase() || '' : '';

            if (returnStatus.includes('returned') || returnStatus.includes('lost') || returnStatus
                .includes('damaged')) {
                alert('This book has already been processed');
                return false;
            }

            // Validate payment method if fine exists
            if (selectedFine > 0) {
                if (!selectedPaymentMethod) {
                    alert('Please select a payment method');
                    return false;
                }

        
                const hasDamagedFine = appliedFinesContainer ?
                    Array.from(appliedFinesContainer.querySelectorAll('.fine-type-card'))
                    .some(card => card.dataset.fineType === 'damaged') : false;

                if (hasDamagedFine) {
                    const damageDesc = document.querySelector('textarea[name="damage_description"]');
                    const pagesCount = document.querySelector('input[name="pages_count"]');
                       // Before form submission, update the total fine amount
                    document.getElementById('total_fine_amount').value = selectedFine;
                    // Validate Damage Description
                    if (!damageDesc || !damageDesc.value.trim()) {
                        alert('Please provide damage description for damaged books');
                        return false;
                    }

                    // Validate Pages Count
                    if (!pagesCount || !pagesCount.value.trim()) {
                        alert('Please enter pages count for damaged books');
                        return false;
                    }

                    if (parseInt(pagesCount.value) <= 0) {
                        alert('Pages count must be greater than 0');
                        return false;
                    }
                    }

                // For online payment, ensure payment link is generated
                if (selectedPaymentMethod === 'online') {
                    const hasPaymentLink = linkResult ? linkResult.querySelector(
                        '.payment-link-container') !== null : false;

                    if (!hasPaymentLink) {
                        alert('Please generate a payment link for online payment');
                        return false;
                    }
                }
            }
           
            // Log final data before submission
            console.log('Submitting return form with data:', {
                book_issue_id: selectedBookIssueId?.value,
                fine_type: fineTypeSelect?.value,
                damage_description: document.querySelector('textarea[name="damage_description"]')?.value,
                payment_method: paymentMethodInput?.value,
                use_manual_fine: useManualFineInput?.value
            });

            // Submit the form
            this.submit();
        });
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
            filters.push(`Overdue: ${urlParams.get('overdue_status') === 'overdue' ? 'Overdue' : 'Not Overdue'}`);
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