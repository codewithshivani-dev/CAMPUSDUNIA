@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Transaction Records</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --success-gradient: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    --primary: #4361ee;
    --primary-light: #e6eeff;
    --primary-lighter: #f0f4ff;
    --success: #10b981;
    --success-light: #d1fae5;
    --info: #3b82f6;
    --warning: #f59e0b;
    --danger: #ef4444;
    --dark: #1f2937;
    --border: #e5e7eb;
    --gray-500: #6b7280;
    --gray-400: #9ca3af;
}

/* Filter Section */
.filter-section {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    border: 1px solid var(--border);
}

.filter-section h5 {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.filter-group label {
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--gray-500);
    margin-bottom: 0.25rem;
    display: block;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-input,
.filter-select {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid var(--border);
    border-radius: 10px;
    font-size: 0.85rem;
    background: white;
}

.filter-input:focus,
.filter-select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.filter-buttons {
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border);
}

.btn-apply {
    background: var(--primary);
    color: white;
    border: none;
    padding: 0.5rem 1.5rem;
    border-radius: 10px;
    font-weight: 500;
}

.btn-reset {
    background: white;
    border: 1px solid var(--border);
    padding: 0.5rem 1.5rem;
    border-radius: 10px;
}

/* Active Filters */
.active-filters {
    background: white;
    border-radius: 12px;
    padding: 0.75rem 1rem;
    margin-bottom: 1.5rem;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    align-items: center;
    border: 1px solid var(--border);
}

.filter-tag {
    background: var(--primary-lighter);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    color: var(--primary);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

/* Table Container with Horizontal Scroll */
/*.table-wrapper {*/
/*    background: white;*/
/*    border-radius: 16px;*/
/*    border: 1px solid var(--border);*/
    /*overflow-x: auto;*/
/*    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);*/
/*}*/

/*.transaction-table {*/
/*    width: 100%;*/
/*    border-collapse: collapse;*/
/*}*/

/*.transaction-table th {*/
/*    background: #f9fafb;*/
/*    padding: 1rem 1rem;*/
/*    text-align: left;*/
/*    font-weight: 600;*/
/*    text-transform: uppercase;*/
/*    letter-spacing: 0.5px;*/
/*    border-bottom: 1px solid var(--border);*/
/*    white-space: nowrap;*/
/*}*/

/*.transaction-table td {*/
/*    padding: 1rem 1rem;*/
/*    border-bottom: 1px solid var(--border);*/
/*    vertical-align: top;*/
/*}*/

/*.transaction-table tr:hover {*/
/*    background: var(--primary-lighter);*/
/*}*/

/* Badges and Styling */
.transaction-id {
    font-weight: 600;
    font-family: monospace;
    font-size: 0.75rem;
    color: var(--dark);
    word-break: break-all;
}

.ref-id {
    font-size: 0.65rem;
    color: var(--gray-500);
    margin-top: 0.25rem;
    font-family: monospace;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.6rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 500;
    white-space: nowrap;
}

.status-paid {
    background: var(--success-light);
    color: var(--success);
}

.status-created {
    background: #e6f7ff;
    color: #1890ff;
}

.status-failed {
    background: #fff2f0;
    color: var(--danger);
}

.status-expired {
    background: #fff7e6;
    color: #fa8c16;
}

.payment-type-badge,
.payment-mode-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 500;
    white-space: nowrap;
}

.payment-type-pg {
    background: #fff7e6;
    color: #fa8c16;
}

.payment-type-cash {
    background: #f6ffed;
    color: #52c41a;
}

.payment-type-bank {
    background: #e6f7ff;
    color: #1890ff;
}

.mode-cash {
    background: #f6ffed;
    color: #52c41a;
}

.mode-card {
    background: #e6f7ff;
    color: #1890ff;
}

.mode-upi {
    background: #f9f0ff;
    color: #722ed1;
}

.mode-netbanking {
    background: #fff7e6;
    color: #fa8c16;
}

.mode-wallet {
    background: #e6fffb;
    color: #13c2c2;
}

.mode-payment-link {
    background: #fff7e6;
    color: #fa8c16;
}

.amount-cell {
    font-weight: 700;
    color: var(--dark);
    font-size: 0.9rem;
}

.total-amount-cell {
    font-weight: 800;
    color: var(--primary);
    font-size: 1rem;
    background: var(--primary-lighter);
    padding: 0.25rem 0.5rem;
    border-radius: 8px;
    display: inline-block;
}

.total-amount-same {
    font-weight: 700;
    color: var(--dark);
    font-size: 0.9rem;
    display: inline-block;
}

.charge-box {
    background: #f8f9fa;
    padding: 0.5rem;
    border-radius: 8px;
    border-left: 3px solid var(--primary);
}

.charge-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.7rem;
    padding: 0.25rem 0;
}

.charge-label {
   
    font-weight: 500;
}

.charge-value {
    font-weight: 600;
    color: #722ed1;
}

.service-charge {
    color: #fa8c16;
}

.gst-charge {
    color: #722ed1;
}

.fee-indicators {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.fee-active {
    font-size: 0.65rem;
    padding: 0.15rem 0.4rem;
    background: #e6f7ff;
    color: #1890ff;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    width: fit-content;
}

.gateway-id,
.payment-link {
    font-size: 12px;
    font-family: monospace;
    color: #000;
    word-break: break-all;
}

.payment-link {
    color: var(--info);
    text-decoration: none;
}

.payment-link:hover {
    text-decoration: underline;
}

.user-name {
    font-weight: 500;
    font-size: 0.8rem;
}

.user-id,
.user-type {
    font-size: 0.65rem;
    color: var(--gray-500);
    margin-top: 0.15rem;
}

.type-badge {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info);
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
    font-size: 0.65rem;
    display: inline-block;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    border: 1px solid var(--border);
    background: white;
    color: var(--gray-500);
    cursor: pointer;
    transition: all 0.2s;
}

.action-btn:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: var(--gray-500);
}

.table-footer {
    background: white;
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.records-count {
    font-size: 0.8rem;
    color: var(--gray-500);
}

.pagination-btn {
    padding: 0.4rem 1rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: white;
    font-size: 0.8rem;
}

/* Tooltip */
.has-tooltip {
    position: relative;
    cursor: help;
}

.tooltip-text {
    visibility: hidden;
    background: var(--dark);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.7rem;
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    white-space: nowrap;
    z-index: 10;
}

.has-tooltip:hover .tooltip-text {
    visibility: visible;
}

/* Fixed Receipt Modal Styles */
.receipt-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

.receipt-modal.show {
    display: flex !important;
}

body.modal-open {
    overflow: hidden;
}

.receipt-modal-dialog {
    position: relative;
    width: auto;
    max-width: 800px;
    margin: 1.75rem auto;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.receipt-modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    background-color: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.receipt-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #4361ee 0%, #3a56d4 100%);
    color: white;
    border-bottom: none;
}

.receipt-modal-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.receipt-modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
    color: white;
    opacity: 0.8;
    cursor: pointer;
    padding: 0;
    margin: 0;
    transition: opacity 0.2s;
}

.receipt-modal-close:hover {
    opacity: 1;
}

.receipt-modal-body {
    position: relative;
    flex: 1 1 auto;
    padding: 0;
    max-height: 70vh;
    overflow-y: auto;
}

.receipt-modal-footer {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-end;
    gap: 0.5rem;
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

.receipt-preview {
    background: #f5f5f5;
    padding: 20px;
}

/* Receipt Paper Styles */
.receipt-paper {
    background: white;
    padding: 40px;
    font-family: 'Courier New', monospace;
    max-width: 210mm;
    margin: 0 auto;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    position: relative;
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

.institute-address {
    font-size: 12px;
    color: #666;
    margin-bottom: 5px;
}

.institute-contact {
    font-size: 11px;
    color: #666;
}

.receipt-title {
    font-size: 22px;
    font-weight: bold;
    color: #2c3e50;
    margin: 15px 0;
    text-transform: uppercase;
}

.watermark {
    position: absolute;
    opacity: 0.08;
    font-size: 120px;
    transform: rotate(-45deg);
    top: 30%;
    left: 10%;
    color: #333;
    pointer-events: none;
    font-weight: bold;
}

/* Student Details Section */
.student-details-section {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.student-info-box {
    flex: 1;
    border: 1px solid #ddd;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.student-info-box h4 {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #4361ee;
    border-bottom: 1px solid #ddd;
    padding-bottom: 5px;
}

.info-row {
    margin-bottom: 5px;
    font-size: 12px;
}

.info-label {
    font-weight: 600;
    display: inline-block;
    min-width: 100px;
}

/* Academic Information */
.academic-section {
    border: 1px solid #ddd;
    padding: 15px;
    background: #f8f9fa;
    margin-bottom: 15px;
    border-radius: 8px;
}

.academic-section h4 {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #4361ee;
    border-bottom: 1px solid #ddd;
    padding-bottom: 5px;
}

.academic-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 5px 15px;
    font-size: 12px;
}

/* Fee Details Section */
.fee-details-section {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
}

.fee-box {
    flex: 1;
    border: 1px solid #ddd;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.fee-box h4 {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #4361ee;
    border-bottom: 1px solid #ddd;
    padding-bottom: 5px;
}

.fee-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 12px;
}

.fee-label {
    font-weight: 500;
}

.fee-amount {
    font-weight: 600;
}

.total-fee-row {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
    padding-top: 8px;
    border-top: 2px solid #333;
    font-weight: bold;
    font-size: 14px;
}

.late-fee {
    color: #dc3545;
}

.discount {
    color: #28a745;
}

/* Payment Information */
.payment-box {
    border: 1px solid #ddd;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 15px;
}

.payment-box h4 {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #4361ee;
    border-bottom: 1px solid #ddd;
    padding-bottom: 5px;
}

.payment-status-paid {
    background: #d1fae5;
    color: #065f46;
    padding: 4px 12px;
    border-radius: 20px;
    display: inline-block;
    font-size: 12px;
    font-weight: 600;
}

/* Receipt Footer */
.receipt-footer-section {
    background: #f1f8ff;
    border: 1px solid #d1e7ff;
    padding: 12px;
    margin-top: 20px;
    border-radius: 8px;
}

.receipt-footer-section h4 {
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 8px;
}

.terms-list {
    font-size: 11px;
    margin-bottom: 0;
    padding-left: 20px;
}

.terms-list li {
    margin-bottom: 3px;
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
        padding: 20px;
    }

    .receipt-modal-footer {
        display: none;
    }
}

@media (max-width: 768px) {
    .filter-grid {
        grid-template-columns: 1fr;
    }

    .filter-buttons {
        flex-direction: column;
    }

    .btn-apply,
    .btn-reset {
        width: 100%;
    }

    .student-details-section {
        flex-direction: column;
    }

    .fee-details-section {
        flex-direction: column;
    }

    .academic-grid {
        grid-template-columns: 1fr;
    }

    .receipt-paper {
        padding: 20px;
    }
    
    .receipt-modal-dialog {
        margin: 1rem;
        max-width: calc(100% - 2rem);
    }
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
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 fw-bold mb-1">
                <i class="bi bi-credit-card me-2 text-primary"></i>Transaction Records
            </h1>
            <p class="text-muted mb-0 small">Complete transaction history with all details</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm" onclick="exportTransactions()">
                <i class="bi bi-download"></i> Export
            </button>

        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <h5><i class="bi bi-funnel"></i> Filter Transactions</h5>

        <div class="filter-grid">
            <div class="filter-group">
                <label>DATE RANGE</label>
                <input type="text" class="filter-input" id="dateRange" placeholder="Select date range">
            </div>
            <div class="filter-group">
                <label>STATUS</label>
                <select class="filter-select" id="statusFilter">
                    <option value="">All</option>
                    <option value="paid">Paid</option>
                    <option value="created">Created</option>
                    <option value="failed">Failed</option>
                    <option value="expired">Expired</option>
                </select>
            </div>
            <div class="filter-group">
                <label>PG TYPE</label>
                <select class="filter-select" id="pgTypeFilter">
                    <option value="">All</option>
                    <option value="PG">Payment Gateway</option>
                    <option value="Cash">Cash</option>
                    <option value="Banking">Banking</option>
                </select>
            </div>
            <div class="filter-group">
                <label>PAYMENT MODE</label>
                <select class="filter-select" id="paymentModeFilter">
                    <option value="">All</option>
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                    <option value="upi">UPI</option>
                    <option value="netbanking">Net Banking</option>
                    <option value="wallet">Wallet</option>
                </select>
            </div>
            <div class="filter-group">
                <label>TRANSACTION REF</label>
                <input type="text" class="filter-input" id="transactionRefFilter" placeholder="Search">
            </div>
            <div class="filter-group">
                <label>USER ID / NAME</label>
                <input type="text" class="filter-input" id="userIdFilter" placeholder="Search">
            </div>
            <div class="filter-group">
                <label>GATEWAY ID</label>
                <input type="text" class="filter-input" id="gatewayIdFilter" placeholder="Search">
            </div>
            <div class="filter-group">
                <label>AMOUNT RANGE</label>
                <div class="d-flex gap-2">
                    <input type="number" class="filter-input" id="minAmount" placeholder="Min">
                    <input type="number" class="filter-input" id="maxAmount" placeholder="Max">
                </div>
            </div>
        </div>

        <div class="filter-buttons">
            <button class="btn-reset" onclick="resetFilters()"><i class="bi bi-arrow-clockwise"></i> Reset</button>
            <button class="btn-apply" onclick="applyFilters()"><i class="bi bi-search"></i> Apply Filters</button>
        </div>
    </div>

    <!-- Active Filters -->
    <div class="active-filters" id="activeFiltersContainer" style="display: none;">
        <i class="bi bi-funnel-fill small text-muted"></i>
        <div id="activeFiltersList"></div>
        <a href="#" class="ms-auto small text-danger" onclick="clearAllFilters()">Clear all</a>
    </div>

    <div>
        <!-- Table with Horizontal Scroll -->
        <div class="table-wrapper table-responsive custom-table-wrapper" id="tableWrapper">
            <table class="erp-table transaction-table">
                <thead>
                    <tr>
                        <th class="sticky-main-2 sortable col-ref">TRANSACTION REF</th>
                        <th class="sortable col-date">DATE & TIME</th>
                        <th class="sortable col-pg-type">Fee Categories</th>
                        <th class="sortable col-pg-type">Payment TYPE</th>
                        <th class="sortable col-mode">PAYMENT MODE</th>
                        <th class="sortable col-payment-details">PAYMENT DETAILS</th>
                        <th class="sortable col-amount">AMOUNT</th>
                        <th class="sortable col-service-charges">SERVICE CHARGES</th>
                        <th class="sortable col-gst-charges">GST CHARGES</th>
                        <th class="sortable col-total">TOTAL AMOUNT</th>
                        <th class="sortable col-user">USER DETAILS</th>
                        <th class="sortable col-status">STATUS</th>
                        <th class="sortable col-trans-type">Fee TYPE</th>
                        <th class="sortable col-actions">ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="transactionsTableBody">
                    @forelse($transactions as $transaction)
                    @if(isset($transaction['transaction_reference']))
                    @php
                    $pgType = $transaction['payment_type'] ?? ($transaction['payment_type'] ?? '');
                    $paymentType = $transaction['pg_type'] ?? ($transaction['pg_type'] ?? 'PG');
                    $paymentMode = $transaction['payment_mode'] ?? 'payment_link';
                    $status = $transaction['payment_status'] ?? $transaction['status'] ?? 'created';
                    $amount = floatval($transaction['amount'] ?? 0);
                    $serviceChargesType = $transaction['service_charges_type'] ?? 'N/A';
                    $serviceCharges = floatval($transaction['service_charges'] ?? 0);
                    $serviceChargesAmount = floatval($transaction['service_charges_amount'] ?? 0);
                    $gstCharges = $transaction['gst_charges'] ?? 'N/A';
                    $gstChargesAmount = floatval($transaction['gst_charges_amount'] ?? 0);
    
                    // Calculate total amount properly
                    $hasCharges = ($serviceChargesAmount > 0 || $gstChargesAmount > 0);
                    if ($hasCharges) {
                    $totalAmount = floatval($transaction['total_amount'] ?? ($amount + $serviceChargesAmount +
                    $gstChargesAmount));
                    } else {
                    $totalAmount = $amount;
                    }
    
                    $pgTypeClass = "payment-type-" . strtolower($pgType);
                    $paymentModeClass = "mode-" . str_replace('_', '-', $paymentMode);
                    $statusClass = match($status) {
                    'paid' => 'status-paid',
                    'created' => 'status-created',
                    'failed' => 'status-failed',
                    'expired' => 'status-expired',
                    default => 'status-created'
                    };
                    @endphp
                    <tr class="transaction-row" data-id="{{ $transaction['transaction_reference'] }}"
                        data-status="{{ $status }}" data-pg-type="{{ $pgType }}" data-payment-mode="{{ $paymentMode }}"
                        data-date="{{ \Carbon\Carbon::parse($transaction['created_at'])->format('Y-m-d') }}"
                        data-user-id="{{ $transaction['user_id'] ?? '' }}" data-amount="{{ $amount }}"
                        data-total-amount="{{ $totalAmount }}"
                        data-gateway-id="{{ $transaction['gateway_payment_id'] ?? '' }}"
                        data-transaction='@json($transaction)'>
    
                        <!-- Transaction Reference -->
                        <td class="sticky-main-2 col-ref">
                            <div class="transaction-id">{{ $transaction['transaction_reference'] }}</div>
                            @if(isset($transaction['user_transaction_refered_id']))
                            <div class="ref-id">Ref: {{ $transaction['user_transaction_refered_id'] }}</div>
                            @endif
                        </td>
    
                        <!-- Date & Time -->
                        <td class="col-date">
                            <div>{{ \Carbon\Carbon::parse($transaction['created_at'])->format('d M Y') }}</div>
                            <div class="ref-id">{{ \Carbon\Carbon::parse($transaction['created_at'])->format('h:i A') }}
                            </div>
                            @if(isset($transaction['updated_at']) && $transaction['updated_at'] !=
                            $transaction['created_at'])
                            <div class="ref-id">Updated:
                                {{ \Carbon\Carbon::parse($transaction['updated_at'])->format('d M h:i A') }}</div>
                            @endif
                        </td>
    
                        <!-- PG Type -->
                        <td class="col-pg-type">
                            <span class="payment-type-badge {{ $pgTypeClass }}">
                                <i
                                    class="bi {{ $pgType == 'PG' ? 'bi-credit-card' : ($pgType == 'Cash' ? 'bi-cash' : 'bi-bank') }}"></i>
                                {{ $pgType == 'PG' ? 'Gateway' : $pgType }}
                            </span>
                        </td>
    
                        <!-- Payment Type -->
                        <td class="col-pg-type">
                            <span class="payment-type-badge {{ $pgTypeClass }}">
                                <i
                                    class="bi {{ $pgType == 'PG' ? 'bi-credit-card' : ($pgType == 'Cash' ? 'bi-cash' : 'bi-bank') }}"></i>
                                {{ $paymentType ? $paymentType : '' }}
                            </span>
                        </td>
    
                        <!-- Payment Mode -->
                        <td class="col-mode">
                            <span class="payment-mode-badge {{ $paymentModeClass }}">
                                <i
                                    class="bi {{ $paymentMode == 'cash' ? 'bi-cash-stack' : ($paymentMode == 'card' ? 'bi-credit-card' : ($paymentMode == 'upi' ? 'bi-phone' : 'bi-credit-card')) }}"></i>
                                {{ ucfirst(str_replace('_', ' ', $paymentMode)) }}
                            </span>
                        </td>
    
                        <!-- Payment Details -->
                        <td class="col-payment-details">
                            @if(isset($transaction['gateway_payment_id']) && $transaction['gateway_payment_id'])
                            <div class="has-tooltip">
                                <span class="ref-id fw-bold">Gateway ID:</span>
                                <div class="gateway-id fw-bold">{{ substr($transaction['gateway_payment_id'], 0, 20) }}...
                                </div>
                                <span class="tooltip-text fw-bold">{{ $transaction['gateway_payment_id'] }}</span>
                            </div>
                            @endif
                        </td>
    
                        <!-- Amount -->
                        <td class="col-amount">
                            <div class="amount-cell">{{ $transaction['currency'] ?? 'INR' }} {{ number_format($amount, 2) }}
                            </div>
                        </td>
    
                        <!-- Service Charges -->
                        <td class="col-service-charges">
                            @if($serviceChargesAmount > 0)
                            <div class="charge-box">
                                <div class="charge-item">
                                    <!--<span class="charge-label">Type:</span>-->
                                    <span class="charge-label">{{ $serviceChargesType }}</span>
                                </div>
                                <div class="charge-item">
                                    <span class="charge-label">Amount:</span>
                                    <span class="charge-value">{{ $transaction['currency'] ?? 'INR' }}
                                        {{ number_format($serviceChargesAmount, 2) }}</span>
                                </div>
                            </div>
                            @else
                            <span class="ref-id">—</span>
                            @endif
                        </td>
    
                        <!-- GST Charges -->
                        <td class="col-gst-charges">
                            @if($gstChargesAmount > 0)
                            <div class="charge-box">
                                <div class="charge-item">
                                    <span class="charge-label">GST</span>
                                    <span class="charge-value">{{ $gstCharges }}</span>
                                </div>
                                <div class="charge-item">
                                    <span class="charge-label">Amount:</span>
                                    <span class="charge-value gst-charge">{{ $transaction['currency'] ?? 'INR' }}
                                        {{ number_format($gstChargesAmount, 2) }}</span>
                                </div>
                            </div>
                            @else
                            <span class="ref-id">—</span>
                            @endif
                        </td>
    
                        <!-- Total Amount - Dynamic based on charges -->
                        <td class="col-total">
                            @if($hasCharges)
                            <div class="total-amount-cell">
                                <strong>{{ $transaction['currency'] ?? 'INR' }}
                                    {{ number_format($totalAmount, 2) }}</strong>
                            </div>
                            <div class="ref-id mt-1">(incl. charges)</div>
                            @else
                            <div class="total-amount-same">
                                <strong>{{ $transaction['currency'] ?? 'INR' }}
                                    {{ number_format($totalAmount, 2) }}</strong>
                            </div>
                            <div class="ref-id mt-1">(no charges)</div>
                            @endif
                        </td>
    
                        <!-- User Details -->
                        <td class="col-user">
                            <div class="user-name">{{ $transaction['user_name'] ?? 'N/A' }}</div>
                            @if(isset($transaction['user_id']))
                            <div class="user-id">ID: {{ $transaction['user_id'] }}</div>
                            @endif
                            @if(isset($transaction['user_type']))
                            <div class="user-type">Type: {{ ucfirst($transaction['user_type']) }}</div>
                            @endif
                            @if(isset($transaction['institute_id']))
                            <div class="user-type">Institute: {{ $transaction['institute_id'] }}</div>
                            @endif
                        </td>
    
                        <!-- Status -->
                        <td class="col-status">
                            <span class="status-badge {{ $statusClass }}">
                                <i
                                    class="bi {{ $status == 'paid' ? 'bi-check-circle' : ($status == 'failed' ? 'bi-x-circle' : 'bi-clock') }}"></i>
                                {{ ucfirst($status) }}
                            </span>
                        </td>
    
                        <!-- Transaction Type & Fee Types -->
                        <td class="col-trans-type">
                            @if(isset($transaction['transaction_type']))
                            <span class="type-badge">
                                {{ str_replace('_', ' ', ucfirst($transaction['transaction_type'])) }}
                            </span>
                            @endif
                            @if(isset($transaction['fee_type']) && $transaction['fee_type'])
                            <div class="fee-indicators mt-1">
                                @if(is_array($transaction['fee_type']))
                                @foreach($transaction['fee_type'] as $fee => $feeStatus)
                                @if($feeStatus)
                                <span class="fee-active">
                                    <i class="bi bi-check-circle"></i>
                                    {{ str_replace('_', ' ', ucfirst($fee)) }}
                                </span>
                                @endif
                                @endforeach
                                @elseif(is_string($transaction['fee_type']))
                                <span class="fee-active">
                                    <i class="bi bi-check-circle"></i>
                                    {{ str_replace('_', ' ', ucfirst($transaction['fee_type'])) }}
                                </span>
                                @endif
                            </div>
                            @endif
                        </td>
    
                        <!-- Actions -->
                        <td class="col-actions">
                            <div class="action-buttons">
                                <button class="action-btn"
                                    onclick="generateReceipt('{{ $transaction['transaction_reference'] }}')"
                                    title="Receipt">
                                    <i class="bi bi-receipt"></i>
                                </button>
                                <button class="action-btn"
                                    onclick="printTransaction('{{ $transaction['transaction_reference'] }}')" title="Print">
                                    <i class="bi bi-printer"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="13" class="empty-state">
                            <i class="bi bi-inbox display-4 text-muted"></i>
                            <p class="mt-2 mb-0">No transactions found</p>
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
    </div>
        @if(count($transactions) > 0)
        <div class="table-footer">
            <div class="records-count">
                Showing <span id="visibleCount">{{ count($transactions) }}</span> transactions
            </div>
            <div class="d-flex gap-2">
                <button class="pagination-btn" disabled>Previous</button>
                <button class="pagination-btn">Next</button>
            </div>
        </div>
        @endif
</div>

<!-- Fixed Receipt Modal -->
<div id="receiptModal" class="receipt-modal">
    <div class="receipt-modal-dialog">
        <div class="receipt-modal-content">
            <div class="receipt-modal-header">
                <h5 class="receipt-modal-title">
                    <i class="bi bi-receipt me-2"></i>Payment Receipt
                </h5>
                <button type="button" class="receipt-modal-close" onclick="closeReceiptModal()">&times;</button>
            </div>
            <div class="receipt-modal-body">
                <div class="receipt-preview" id="receiptPreview">
                    <!-- Receipt will be generated here -->
                </div>
            </div>
            <div class="receipt-modal-footer">
                <button type="button" class="btn btn-outline-primary" onclick="printReceipt()">
                    <i class="bi bi-printer me-2"></i>Print Receipt
                </button>
                <button type="button" class="btn btn-primary" onclick="downloadReceiptAsPDF()">
                    <i class="bi bi-download me-2"></i>Download PDF
                </button>
                <button type="button" class="btn btn-success" onclick="downloadReceiptAsImage()">
                    <i class="bi bi-image me-2"></i>Download Image
                </button>
                <button type="button" class="btn btn-secondary" onclick="closeReceiptModal()">
                    <i class="bi bi-x-circle me-2"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
// Institute details from PHP
const instituteDetails = {
    name: "{{ $serviceInstitutedetails->name ?? 'Institute Name' }}",
    address_line1: "{{ $serviceInstitutedetails->address_line_1 ?? '' }}",
    address_line2: "{{ $serviceInstitutedetails->address_line_2 ?? '' }}",
    state: "{{ $serviceInstitutedetails->state ?? '' }}",
    city: "{{ $serviceInstitutedetails->city ?? '' }}",
    pincode: "{{ $serviceInstitutedetails->pincode ?? '' }}",
    phone: "{{ $serviceInstitutedetails->contact_number ?? '' }}",
    email: "{{ $serviceInstitutedetails->email ?? '' }}",
    website: "{{ $serviceInstitutedetails->website ?? '' }}"
};

// Helper function to get formatted address
function getFormattedAddress() {
    const addressParts = [];
    if (instituteDetails.address_line1) addressParts.push(instituteDetails.address_line1);
    if (instituteDetails.address_line2) addressParts.push(instituteDetails.address_line2);
    if (instituteDetails.city) addressParts.push(instituteDetails.city);
    if (instituteDetails.state) addressParts.push(instituteDetails.state);
    if (instituteDetails.pincode) addressParts.push(instituteDetails.pincode);
    return addressParts.join(', ');
}

flatpickr("#dateRange", {
    mode: "range",
    dateFormat: "Y-m-d",
    maxDate: "today"
});

let activeFilters = {};
let currentReceiptData = null;
const allTransactions = @json($transactions);

function viewDetails(id) {
    const transaction = allTransactions.find(t => t.transaction_reference === id);
    if (transaction) {
        const amount = parseFloat(transaction.amount || 0).toFixed(2);
        const serviceCharges = parseFloat(transaction.service_charges_amount || 0).toFixed(2);
        const gstCharges = parseFloat(transaction.gst_charges_amount || 0).toFixed(2);
        const hasCharges = (serviceCharges > 0 || gstCharges > 0);
        let totalAmount = amount;

        if (hasCharges) {
            totalAmount = parseFloat(transaction.total_amount || (parseFloat(amount) + parseFloat(serviceCharges) +
                parseFloat(gstCharges))).toFixed(2);
        }

        let chargesMessage = '';
        if (serviceCharges > 0) {
            chargesMessage += `\nService Charges: ${transaction.currency || 'INR'} ${serviceCharges}`;
        }
        if (gstCharges > 0) {
            chargesMessage += `\nGST Charges: ${transaction.currency || 'INR'} ${gstCharges}`;
        }

        alert(`Transaction Details:\n\n` +
            `Reference: ${transaction.transaction_reference}\n` +
            `Date: ${transaction.created_at}\n` +
            `Amount: ${transaction.currency || 'INR'} ${amount}\n` +
            `${chargesMessage}` +
            `\nTotal Amount: ${transaction.currency || 'INR'} ${totalAmount}\n` +
            `Status: ${transaction.payment_status || transaction.status}\n` +
            `User: ${transaction.user_name || 'N/A'} (${transaction.user_id || 'N/A'})\n` +
            `Gateway ID: ${transaction.gateway_payment_id || 'N/A'}`);
    }
}

function generateReceipt(id) {
    const transaction = allTransactions.find(t => t.transaction_reference === id);
    if (transaction) {
        currentReceiptData = transaction;
        renderReceipt(transaction);
        // Show modal
        const modal = document.getElementById('receiptModal');
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function renderReceipt(transaction) {
    const receiptPreview = document.getElementById('receiptPreview');

    const amount = parseFloat(transaction.amount || 0);
    const serviceChargesAmount = parseFloat(transaction.service_charges_amount || 0);
    const gstChargesAmount = parseFloat(transaction.gst_charges_amount || 0);
    const serviceChargesType = transaction.service_charges_type || 'N/A';
    const serviceCharges = parseFloat(transaction.service_charges || 0);
    const gstCharges = transaction.gst_charges || 'N/A';
    const currency = transaction.currency || 'INR';
    const status = transaction.payment_status || transaction.status || 'created';
    const paymentMode = transaction.payment_mode || 'N/A';
    const feeType = transaction.fee_type || '';
    const gatewayId = transaction.gateway_payment_id || '';
    const pgType = transaction.pg_type || '';

    const hasCharges = (serviceChargesAmount > 0 || gstChargesAmount > 0);
    let totalAmount = amount;

    if (hasCharges) {
        totalAmount = parseFloat(transaction.total_amount || (amount + serviceChargesAmount + gstChargesAmount));
    }

    const receiptNumber = generateReceiptNumberForTransaction(transaction.transaction_reference);
    const payDate = transaction.created_at ? new Date(transaction.created_at) : new Date();
    const formattedPayDate = payDate.toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
    const formattedPayTime = payDate.toLocaleTimeString('en-IN', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    });

    // Format fee type display
    let feeTypeDisplay = 'Course Fee';
    if (feeType) {
        if (typeof feeType === 'object') {
            const feeNames = Object.keys(feeType).filter(k => feeType[k]).map(k => {
                return k.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            });
            feeTypeDisplay = feeNames.join(', ');
        } else if (typeof feeType === 'string') {
            feeTypeDisplay = feeType.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        }
    }

    // Get student details from transaction
    const studentName = transaction.user_name || 'N/A';
    const studentRegNo = transaction.user_transaction_refered_id || transaction.user_id || 'N/A';

    // Get academic information from transaction data
    const department = transaction.department || 'N/A';
    const course = transaction.course || 'N/A';
    const batch = transaction.batch || 'N/A';
    const academicYear = transaction.academic_year || 'N/A';
    const semester = transaction.semester || 'N/A';
    const section = transaction.section || 'N/A';

    const receiptHTML = `
                <div class="receipt-paper" id="receiptContent">
                    <div class="watermark">PAID</div>
                    <div class="receipt-header">
                        <div class="institute-name">${instituteDetails.name}</div>
                        <div class="institute-address">${getFormattedAddress()}</div>
                        <div class="institute-contact">
                            Phone: ${instituteDetails.phone || 'N/A'} | Email: ${instituteDetails.email || 'N/A'}
                        </div>
                        <div class="receipt-title">PAYMENT RECEIPT</div>
                    </div>
                    
                    <!-- Student Information -->
                    <div class="student-details-section">
                        <div class="student-info-box">
                            <h4><i class="bi bi-person-circle"></i> STUDENT INFORMATION</h4>
                            <div class="info-row">
                                <span class="info-label">Name:</span> ${studentName}
                            </div>
                            <div class="info-row">
                                <span class="info-label">Reg No:</span> ${studentRegNo}
                            </div>
                        </div>
                        <div class="student-info-box">
                            <h4><i class="bi bi-receipt"></i> RECEIPT INFORMATION</h4>
                            <div class="info-row">
                                <span class="info-label">Receipt No:</span> ${receiptNumber}
                            </div>
                            <div class="info-row">
                                <span class="info-label">Date:</span> ${formattedPayDate} ${formattedPayTime}
                            </div>
                            <div class="info-row">
                                <span class="info-label">Transaction ID:</span> ${transaction.transaction_reference}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Academic Information -->
                    <div class="academic-section">
                        <h4><i class="bi bi-mortarboard"></i> ACADEMIC INFORMATION</h4>
                        <div class="academic-grid">
                            <div><span class="info-label">Department:</span> ${department}</div>
                            <div><span class="info-label">Class:</span> ${course}</div>
                            <div><span class="info-label">Batch:</span> ${batch}</div>
                            <div><span class="info-label">Year:</span> ${academicYear}</div>
                            <div><span class="info-label">Semester:</span> ${semester}</div>
                            
                        </div>
                    </div>
                    
                    <!-- Fee Details -->
                    <div class="fee-details-section">
                        <div class="fee-box">
                            <h4><i class="bi bi-cash-stack"></i> FEE DETAILS</h4>
                            <div class="fee-row">
                                <span class="fee-label">Fee Type:</span>
                                <span class="fee-amount">${feeTypeDisplay}</span>
                            </div>
                            <div class="fee-row">
                                <span class="fee-label">Payment Date:</span>
                                <span class="fee-amount">${formattedPayDate}</span>
                            </div>
                            <div class="fee-row">
                                <span class="fee-label">Payment Time:</span>
                                <span class="fee-amount">${formattedPayTime}</span>
                            </div>
                            <div class="fee-row">
                                <span class="fee-label">Payment Mode:</span>
                                <span class="fee-amount">${paymentMode.toUpperCase()}</span>
                            </div>
                            ${gatewayId ? `
                            <div class="fee-row">
                                <span class="fee-label">Gateway ID:</span>
                                <span class="fee-amount" style="font-family:monospace; font-size:10px;">${gatewayId}</span>
                            </div>
                            ` : ''}
                        </div>
                        <div class="fee-box">
                            <h4><i class="bi bi-currency-rupee"></i> AMOUNT BREAKDOWN</h4>
                            <div class="fee-row">
                                <span class="fee-label">Base Amount:</span>
                                <span class="fee-amount">${currency} ${amount.toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                            </div>
                            ${serviceChargesAmount > 0 ? `
                            <div class="fee-row">
                                <span class="fee-label">Service Charges (${serviceChargesType} ${serviceCharges}%):</span>
                                <span class="fee-amount">+ ${currency} ${serviceChargesAmount.toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                            </div>
                            ` : ''}
                            ${gstChargesAmount > 0 ? `
                            <div class="fee-row">
                                <span class="fee-label">GST (${gstCharges}):</span>
                                <span class="fee-amount">+ ${currency} ${gstChargesAmount.toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                            </div>
                            ` : ''}
                            <div class="total-fee-row">
                                <span>TOTAL PAYABLE:</span>
                                <span>${currency} ${totalAmount.toLocaleString('en-IN', {minimumFractionDigits: 2})}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Information -->
                    <div class="payment-box">
                        <h4><i class="bi bi-credit-card-2-front"></i> PAYMENT INFORMATION</h4>
                        <div class="fee-row">
                            <span class="fee-label">Status:</span>
                            <span class="payment-status-paid">
                                <i class="bi bi-check-circle"></i> PAID
                            </span>
                        </div>
                        <div class="fee-row">
                            <span class="fee-label">Method:</span>
                            <span>${paymentMode.toUpperCase()} Payment</span>
                        </div>
                        ${pgType ? `
                        <div class="fee-row">
                            <span class="fee-label">Payment Type:</span>
                            <span>${pgType}</span>
                        </div>
                        ` : ''}
                       
                    </div>
                    
                    <!-- Footer with Terms -->
                    <div class="receipt-footer-section">
                        <h4>Terms & Conditions:</h4>
                        <ul class="terms-list">
                            <li>This is a computer generated receipt and does not require signature.</li>
                            <li>For any queries, contact accounts department within 7 days.</li>
                        </ul>
                    </div>
                </div>
            `;

    receiptPreview.innerHTML = receiptHTML;
}

function generateReceiptNumberForTransaction(transactionRef) {
    const date = new Date();
    const year = date.getFullYear().toString().substr(-2);
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    const shortRef = transactionRef ? transactionRef.substr(-6) : '000000';
    return `RCPT${year}${month}${day}${shortRef}`;
}

// PDF Download function for receipt
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
            backgroundColor: '#ffffff',
            logging: false
        });
        const imgData = canvas.toDataURL('image/png');

        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'a4'
        });
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const imgHeight = (canvas.height * pdfWidth) / canvas.width;

        pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, imgHeight);

        const fileName = `Fee_Receipt_${currentReceiptData.transaction_reference}_${currentReceiptData.user_name?.replace(/\s+/g, '_') || 'receipt'}.pdf`;
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
        link.download = `Fee_Receipt_${currentReceiptData.transaction_reference}_${currentReceiptData.user_name?.replace(/\s+/g, '_') || 'receipt'}.png`;
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
    alertDiv.style.background = '#10b981';
    alertDiv.style.color = 'white';
    alertDiv.style.borderRadius = '12px';
    alertDiv.style.padding = '0.8rem 1.2rem';
    alertDiv.innerHTML = `
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div>
                        <strong>${fileType} Downloaded Successfully!</strong>
                        <div class="small opacity-75">Receipt saved to your device</div>
                    </div>
                </div>
            `;
    document.body.appendChild(alertDiv);
    setTimeout(() => alertDiv.remove(), 3000);
}

function printReceipt() {
    const receiptContent = document.getElementById('receiptContent');
    if (!receiptContent) return;

    const printWindow = window.open('', '_blank');
    const originalContent = receiptContent.cloneNode(true);

    printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Payment Receipt</title>
                    <style>
                        * { margin: 0; padding: 0; box-sizing: border-box; }
                        body { 
                            font-family: 'Courier New', monospace; 
                            padding: 20px; 
                            background: white;
                        }
                        .receipt-paper {
                            background: white;
                            padding: 40px;
                            max-width: 210mm;
                            margin: 0 auto;
                            position: relative;
                        }
                        .watermark {
                            position: absolute;
                            opacity: 0.08;
                            font-size: 120px;
                            transform: rotate(-45deg);
                            top: 30%;
                            left: 10%;
                            color: #333;
                            pointer-events: none;
                            font-weight: bold;
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
                        .institute-address {
                            font-size: 12px;
                            color: #666;
                            margin-bottom: 5px;
                        }
                        .institute-contact {
                            font-size: 11px;
                            color: #666;
                        }
                        .receipt-title {
                            font-size: 22px;
                            font-weight: bold;
                            color: #2c3e50;
                            margin: 15px 0;
                            text-transform: uppercase;
                        }
                        .student-details-section {
                            display: flex;
                            gap: 20px;
                            margin-bottom: 20px;
                        }
                        .student-info-box {
                            flex: 1;
                            border: 1px solid #ddd;
                            padding: 15px;
                            background: #f8f9fa;
                            border-radius: 8px;
                        }
                        .student-info-box h4 {
                            font-size: 13px;
                            font-weight: 600;
                            margin-bottom: 10px;
                            color: #4361ee;
                            border-bottom: 1px solid #ddd;
                            padding-bottom: 5px;
                        }
                        .info-row {
                            margin-bottom: 5px;
                            font-size: 12px;
                        }
                        .info-label {
                            font-weight: 600;
                            display: inline-block;
                            min-width: 100px;
                        }
                        .academic-section {
                            border: 1px solid #ddd;
                            padding: 15px;
                            background: #f8f9fa;
                            margin-bottom: 15px;
                            border-radius: 8px;
                        }
                        .academic-section h4 {
                            font-size: 13px;
                            font-weight: 600;
                            margin-bottom: 10px;
                            color: #4361ee;
                            border-bottom: 1px solid #ddd;
                            padding-bottom: 5px;
                        }
                        .academic-grid {
                            display: grid;
                            grid-template-columns: repeat(2, 1fr);
                            gap: 5px 15px;
                            font-size: 12px;
                        }
                        .fee-details-section {
                            display: flex;
                            gap: 20px;
                            margin-bottom: 15px;
                        }
                        .fee-box {
                            flex: 1;
                            border: 1px solid #ddd;
                            padding: 15px;
                            background: #f8f9fa;
                            border-radius: 8px;
                        }
                        .fee-box h4 {
                            font-size: 13px;
                            font-weight: 600;
                            margin-bottom: 10px;
                            color: #4361ee;
                            border-bottom: 1px solid #ddd;
                            padding-bottom: 5px;
                        }
                        .fee-row {
                            display: flex;
                            justify-content: space-between;
                            margin-bottom: 8px;
                            font-size: 12px;
                        }
                        .fee-label {
                            font-weight: 500;
                        }
                        .fee-amount {
                            font-weight: 600;
                        }
                        .total-fee-row {
                            display: flex;
                            justify-content: space-between;
                            margin-top: 10px;
                            padding-top: 8px;
                            border-top: 2px solid #333;
                            font-weight: bold;
                            font-size: 14px;
                        }
                        .payment-box {
                            border: 1px solid #ddd;
                            padding: 15px;
                            background: #f8f9fa;
                            border-radius: 8px;
                            margin-bottom: 15px;
                        }
                        .payment-box h4 {
                            font-size: 13px;
                            font-weight: 600;
                            margin-bottom: 10px;
                            color: #4361ee;
                            border-bottom: 1px solid #ddd;
                            padding-bottom: 5px;
                        }
                        .payment-status-paid {
                            background: #d1fae5;
                            color: #065f46;
                            padding: 4px 12px;
                            border-radius: 20px;
                            display: inline-block;
                            font-size: 12px;
                            font-weight: 600;
                        }
                        .receipt-footer-section {
                            background: #f1f8ff;
                            border: 1px solid #d1e7ff;
                            padding: 12px;
                            margin-top: 20px;
                            border-radius: 8px;
                        }
                        .receipt-footer-section h4 {
                            font-size: 12px;
                            font-weight: 600;
                            margin-bottom: 8px;
                        }
                        .terms-list {
                            font-size: 11px;
                            margin-bottom: 0;
                            padding-left: 20px;
                        }
                        .terms-list li {
                            margin-bottom: 3px;
                        }
                        @media print {
                            body { padding: 0; }
                            .receipt-paper { padding: 20px; }
                        }
                    </style>
                </head>
                <body>
                    ${originalContent.outerHTML}
                    <script>
                        window.onload = function() {
                            window.print();
                            setTimeout(function() { window.close(); }, 500);
                        };
                    <\/script>
                </body>
                </html>
            `);
    printWindow.document.close();
}

function printTransaction(id) {
    generateReceipt(id);
}

function exportTransactions() {
    alert('Export to CSV/Excel feature coming soon');
}

function closeReceiptModal() {
    const modal = document.getElementById('receiptModal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
}

function applyFilters() {
    const dateRange = document.getElementById('dateRange').value;
    const status = document.getElementById('statusFilter').value;
    const pgType = document.getElementById('pgTypeFilter').value;
    const paymentMode = document.getElementById('paymentModeFilter').value;
    const transactionRef = document.getElementById('transactionRefFilter').value.toLowerCase();
    const userId = document.getElementById('userIdFilter').value.toLowerCase();
    const gatewayId = document.getElementById('gatewayIdFilter').value.toLowerCase();
    const minAmount = document.getElementById('minAmount').value;
    const maxAmount = document.getElementById('maxAmount').value;

    const rows = document.querySelectorAll('.transaction-row');
    let visibleCount = 0;

    rows.forEach(row => {
        let show = true;
        const rowData = row.dataset;
        const rowText = row.textContent.toLowerCase();

        if (dateRange) {
            const [start, end] = dateRange.split(' to ');
            if (rowData.date < start || (end && rowData.date > end)) show = false;
        }
        if (status && rowData.status !== status) show = false;
        if (pgType && rowData.pgType !== pgType) show = false;
        if (paymentMode && rowData.paymentMode !== paymentMode) show = false;
        if (transactionRef && !rowData.id?.toLowerCase().includes(transactionRef)) show = false;
        if (userId && !rowText.includes(userId)) show = false;
        if (gatewayId && !rowData.gatewayId?.toLowerCase().includes(gatewayId)) show = false;

        const rowAmount = parseFloat(rowData.totalAmount || rowData.amount);
        if (minAmount && rowAmount < parseFloat(minAmount)) show = false;
        if (maxAmount && rowAmount > parseFloat(maxAmount)) show = false;

        row.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });

    document.getElementById('visibleCount').textContent = visibleCount;
}

function resetFilters() {
    document.getElementById('dateRange').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('pgTypeFilter').value = '';
    document.getElementById('paymentModeFilter').value = '';
    document.getElementById('transactionRefFilter').value = '';
    document.getElementById('userIdFilter').value = '';
    document.getElementById('gatewayIdFilter').value = '';
    document.getElementById('minAmount').value = '';
    document.getElementById('maxAmount').value = '';

    document.querySelectorAll('.transaction-row').forEach(row => row.style.display = '');
    document.getElementById('visibleCount').textContent = document.querySelectorAll('.transaction-row').length;
    document.getElementById('activeFiltersContainer').style.display = 'none';
}

function clearAllFilters() {
    resetFilters();
}

// Event listeners for filters
document.getElementById('transactionRefFilter')?.addEventListener('input', applyFilters);
document.getElementById('userIdFilter')?.addEventListener('input', applyFilters);
document.getElementById('gatewayIdFilter')?.addEventListener('input', applyFilters);
document.getElementById('minAmount')?.addEventListener('input', applyFilters);
document.getElementById('maxAmount')?.addEventListener('input', applyFilters);
document.getElementById('statusFilter')?.addEventListener('change', applyFilters);
document.getElementById('pgTypeFilter')?.addEventListener('change', applyFilters);
document.getElementById('paymentModeFilter')?.addEventListener('change', applyFilters);

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('receiptModal');
    if (event.target === modal) {
        closeReceiptModal();
    }
};
</script>
@endsection