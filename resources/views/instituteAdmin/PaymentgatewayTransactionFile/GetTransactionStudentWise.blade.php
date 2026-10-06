@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <title>Transaction Records</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        :root {
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
        
        body {
            background-color: #f8fafc;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
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
        
        .filter-input, .filter-select {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 0.85rem;
            background: white;
        }
        
        .filter-input:focus, .filter-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67,97,238,0.1);
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
        .table-wrapper {
            background: white;
            border-radius: 16px;
            border: 1px solid var(--border);
            overflow-x: auto;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        
        .transaction-table {
            width: 100%;
            min-width: 1500px;
            border-collapse: collapse;
            font-size: 0.8rem;
        }
        
        .transaction-table th {
            background: #f9fafb;
            padding: 1rem 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--gray-500);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        
        .transaction-table td {
            padding: 1rem 1rem;
            border-bottom: 1px solid var(--border);
            vertical-align: top;
        }
        
        .transaction-table tr:hover {
            background: var(--primary-lighter);
        }
        
        /* Column Widths */
        .col-ref { min-width: 180px; }
        .col-date { min-width: 120px; }
        .col-pg-type { min-width: 100px; }
        .col-mode { min-width: 100px; }
        .col-payment-details { min-width: 180px; }
        .col-amount { min-width: 120px; }
        .col-service-charges { min-width: 150px; }
        .col-gst-charges { min-width: 120px; }
        .col-total { min-width: 120px; }
        .col-user { min-width: 160px; }
        .col-status { min-width: 90px; }
        .col-trans-type { min-width: 120px; }
        .col-actions { min-width: 100px; }
        
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
        
        .payment-type-badge, .payment-mode-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 500;
            white-space: nowrap;
        }
        
        .payment-type-pg { background: #fff7e6; color: #fa8c16; }
        .payment-type-cash { background: #f6ffed; color: #52c41a; }
        .payment-type-bank { background: #e6f7ff; color: #1890ff; }
        
        .mode-cash { background: #f6ffed; color: #52c41a; }
        .mode-card { background: #e6f7ff; color: #1890ff; }
        .mode-upi { background: #f9f0ff; color: #722ed1; }
        .mode-netbanking { background: #fff7e6; color: #fa8c16; }
        .mode-wallet { background: #e6fffb; color: #13c2c2; }
        .mode-payment-link { background: #fff7e6; color: #fa8c16; }
        
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
            color: var(--gray-500);
            font-weight: 500;
        }
        
        .charge-value {
            font-weight: 600;
            color: var(--dark);
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
        
        .gateway-id, .payment-link {
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
        
        .user-id, .user-type {
            font-size: 0.65rem;
            color: var(--gray-500);
            margin-top: 0.15rem;
        }
        
        .type-badge {
            background: rgba(59,130,246,0.1);
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
        
        /* Receipt Modal */
        .receipt-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .receipt-content {
            background: white;
            width: 500px;
            max-width: 90%;
            max-height: 85vh;
            border-radius: 20px;
            overflow-y: auto;
        }
        
        .receipt-header {
            background: linear-gradient(135deg, var(--primary), #3a56d4);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .receipt-body {
            padding: 1.5rem;
        }
        
        .receipt-row {
            display: flex;
            justify-content: space-between;
            padding: 0.6rem 0;
            border-bottom: 1px solid var(--border);
        }
        
        .receipt-footer {
            padding: 1rem;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            border-top: 1px solid var(--border);
        }
        
        @media (max-width: 768px) {
            .filter-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-buttons {
                flex-direction: column;
            }
            
            .btn-apply, .btn-reset {
                width: 100%;
            }
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
                <button class="btn btn-primary btn-sm" onclick="createNewTransaction()">
                    <i class="bi bi-plus-circle"></i> New Transaction
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

        <!-- Table with Horizontal Scroll -->
        <div class="table-wrapper">
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th class="col-ref">TRANSACTION REF</th>
                        <th class="col-date">DATE & TIME</th>
                        <th class="col-pg-type">Fee Categories</th>
                        <th class="col-pg-type">Payment TYPE</th>
                        <th class="col-mode">PAYMENT MODE</th>
                        <th class="col-payment-details">PAYMENT DETAILS</th>
                        <th class="col-amount">AMOUNT</th>
                        <th class="col-service-charges">SERVICE CHARGES</th>
                        <th class="col-gst-charges">GST CHARGES</th>
                        <th class="col-total">TOTAL AMOUNT</th>
                        <th class="col-user">USER DETAILS</th>
                        <th class="col-status">STATUS</th>
                        <th class="col-trans-type">Fee TYPE</th>
                        <th class="col-actions">ACTIONS</th>
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
                                    $totalAmount = floatval($transaction['total_amount'] ?? ($amount + $serviceChargesAmount + $gstChargesAmount));
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
                            <tr class="transaction-row"
                                data-id="{{ $transaction['transaction_reference'] }}"
                                data-status="{{ $status }}"
                                data-pg-type="{{ $pgType }}"
                                data-payment-mode="{{ $paymentMode }}"
                                data-date="{{ \Carbon\Carbon::parse($transaction['created_at'])->format('Y-m-d') }}"
                                data-user-id="{{ $transaction['user_id'] ?? '' }}"
                                data-amount="{{ $amount }}"
                                data-total-amount="{{ $totalAmount }}"
                                data-gateway-id="{{ $transaction['gateway_payment_id'] ?? '' }}"
                                data-transaction='@json($transaction)'>
                                
                                <!-- Transaction Reference -->
                                <td class="col-ref">
                                    <div class="transaction-id">{{ $transaction['transaction_reference'] }}</div>
                                    @if(isset($transaction['user_transaction_refered_id']))
                                        <div class="ref-id">Ref: {{ $transaction['user_transaction_refered_id'] }}</div>
                                    @endif
                                </td>
                                
                                <!-- Date & Time -->
                                <td class="col-date">
                                    <div>{{ \Carbon\Carbon::parse($transaction['created_at'])->format('d M Y') }}</div>
                                    <div class="ref-id">{{ \Carbon\Carbon::parse($transaction['created_at'])->format('h:i A') }}</div>
                                    @if(isset($transaction['updated_at']) && $transaction['updated_at'] != $transaction['created_at'])
                                        <div class="ref-id">Updated: {{ \Carbon\Carbon::parse($transaction['updated_at'])->format('d M h:i A') }}</div>
                                    @endif
                                </td>
                                
                                <!-- PG Type -->
                                <td class="col-pg-type">
                                    <span class="payment-type-badge {{ $pgTypeClass }}">
                                        <i class="bi {{ $pgType == 'PG' ? 'bi-credit-card' : ($pgType == 'Cash' ? 'bi-cash' : 'bi-bank') }}"></i>
                                        {{ $pgType == 'PG' ? 'Gateway' : $pgType }}
                                    </span>
                                </td>

                                <!-- PG Type -->
                                <td class="col-pg-type">
                                    <span class="payment-type-badge {{ $pgTypeClass }}">
                                        <i class="bi {{ $pgType == 'PG' ? 'bi-credit-card' : ($pgType == 'Cash' ? 'bi-cash' : 'bi-bank') }}"></i>
                                        {{ $paymentType ? $paymentType : '' }}
                                    </span>
                                </td>
                                
                                <!-- Payment Mode -->
                                <td class="col-mode">
                                    <span class="payment-mode-badge {{ $paymentModeClass }}">
                                        <i class="bi {{ $paymentMode == 'cash' ? 'bi-cash-stack' : ($paymentMode == 'card' ? 'bi-credit-card' : ($paymentMode == 'upi' ? 'bi-phone' : 'bi-credit-card')) }}"></i>
                                        {{ ucfirst(str_replace('_', ' ', $paymentMode)) }}
                                    </span>
                                </td>
                                
                                <!-- Payment Details -->
                                <td class="col-payment-details">
                                    @if(isset($transaction['gateway_payment_id']) && $transaction['gateway_payment_id'])
                                        <div class="has-tooltip">
                                            <span class="ref-id fw-bold">Gateway ID:</span>
                                            <div class="gateway-id fw-bold">{{ substr($transaction['gateway_payment_id'], 0, 20) }}...</div>
                                            <span class="tooltip-text fw-bold">{{ $transaction['gateway_payment_id'] }}</span>
                                        </div>
                                    @endif
                                </td>
                                
                                <!-- Amount -->
                                <td class="col-amount">
                                    <div class="amount-cell">{{ $transaction['currency'] ?? 'INR' }} {{ number_format($amount, 2) }}</div>
                                </td>
                                
                                <!-- Service Charges -->
                                <td class="col-service-charges">
                                    @if($serviceChargesAmount > 0)
                                        <div class="charge-box">
                                            <div class="charge-item">
                                                <span class="charge-label">Type:</span>
                                                <span class="charge-value">{{ $serviceChargesType }}</span>
                                            </div>
                                            <div class="charge-item">
                                                <span class="charge-label">Amount:</span>
                                                <span class="charge-value">{{ $transaction['currency'] ?? 'INR' }} {{ number_format($serviceChargesAmount, 2) }}</span>
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
                                                <span class="charge-label">Type:</span>
                                                <span class="charge-value">{{ $gstCharges }}</span>
                                            </div>
                                            <div class="charge-item">
                                                <span class="charge-label">Amount:</span>
                                                <span class="charge-value gst-charge">{{ $transaction['currency'] ?? 'INR' }} {{ number_format($gstChargesAmount, 2) }}</span>
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
                                            <strong>{{ $transaction['currency'] ?? 'INR' }} {{ number_format($totalAmount, 2) }}</strong>
                                        </div>
                                        <div class="ref-id mt-1">(incl. charges)</div>
                                    @else
                                        <div class="total-amount-same">
                                            <strong>{{ $transaction['currency'] ?? 'INR' }} {{ number_format($totalAmount, 2) }}</strong>
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
                                        <i class="bi {{ $status == 'paid' ? 'bi-check-circle' : ($status == 'failed' ? 'bi-x-circle' : 'bi-clock') }}"></i>
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
                                        <button class="action-btn" onclick="viewDetails('{{ $transaction['transaction_reference'] }}')" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="action-btn" onclick="generateReceipt('{{ $transaction['transaction_reference'] }}')" title="Receipt">
                                            <i class="bi bi-receipt"></i>
                                        </button>
                                        <button class="action-btn" onclick="printTransaction('{{ $transaction['transaction_reference'] }}')" title="Print">
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
                                <button class="btn btn-sm btn-primary mt-3" onclick="createNewTransaction()">Create Transaction</button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
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
    </div>

    <!-- Receipt Modal -->
    <div id="receiptModal" class="receipt-modal">
        <div class="receipt-content">
            <div class="receipt-header">
                <i class="bi bi-receipt fs-2"></i>
                <h5 class="mb-0 mt-2">Payment Receipt</h5>
            </div>
            <div class="receipt-body" id="receiptBody"></div>
            <div class="receipt-footer">
                <button class="btn btn-sm btn-outline-secondary" onclick="closeReceiptModal()">Close</button>
                <button class="btn btn-sm btn-primary" onclick="printReceipt()">Print Receipt</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#dateRange", {
            mode: "range",
            dateFormat: "Y-m-d",
            maxDate: "today"
        });

        let activeFilters = {};
        let currentReceiptData = null;
        const allTransactions = @json($transactions);
        
        const pgTypeMap = {
            'PG': 'Payment Gateway',
            'Cash': 'Cash',
            'Banking': 'Banking'
        };
        
        const paymentModeMap = {
            'cash': 'Cash', 'card': 'Card', 'upi': 'UPI',
            'netbanking': 'Net Banking', 'wallet': 'Wallet', 'payment_link': 'Payment Link'
        };

        function viewDetails(id) {
            const transaction = allTransactions.find(t => t.transaction_reference === id);
            if (transaction) {
                const amount = parseFloat(transaction.amount || 0).toFixed(2);
                const serviceCharges = parseFloat(transaction.service_charges_amount || 0).toFixed(2);
                const gstCharges = parseFloat(transaction.gst_charges_amount || 0).toFixed(2);
                const hasCharges = (serviceCharges > 0 || gstCharges > 0);
                let totalAmount = amount;
                
                if (hasCharges) {
                    totalAmount = parseFloat(transaction.total_amount || (parseFloat(amount) + parseFloat(serviceCharges) + parseFloat(gstCharges))).toFixed(2);
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
                document.getElementById('receiptModal').style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }
        
        function renderReceipt(transaction) {
            const receiptBody = document.getElementById('receiptBody');
            const amount = parseFloat(transaction.amount || 0).toFixed(2);
            const serviceChargesAmount = parseFloat(transaction.service_charges_amount || 0).toFixed(2);
            const gstChargesAmount = parseFloat(transaction.gst_charges_amount || 0).toFixed(2);
            const serviceChargesType = transaction.service_charges_type || 'N/A';
            const serviceCharges = parseFloat(transaction.service_charges || 0).toFixed(2);
            const gstCharges = transaction.gst_charges || 'N/A';
            const currency = transaction.currency || 'INR';
            const status = transaction.payment_status || transaction.status || 'created';
            
            const hasCharges = (serviceChargesAmount > 0 || gstChargesAmount > 0);
            let totalAmount = amount;
            
            if (hasCharges) {
                totalAmount = parseFloat(transaction.total_amount || (parseFloat(amount) + parseFloat(serviceChargesAmount) + parseFloat(gstChargesAmount))).toFixed(2);
            }
            
            receiptBody.innerHTML = `
                <div class="receipt-row"><span>Transaction ID</span><strong>${transaction.transaction_reference}</strong></div>
                <div class="receipt-row"><span>Date & Time</span><span>${new Date(transaction.created_at).toLocaleString()}</span></div>
                <div class="receipt-row"><span>User</span><span>${transaction.user_name || 'N/A'} (${transaction.user_id || 'N/A'})</span></div>
                <div class="receipt-row"><span>Payment Mode</span><span>${transaction.payment_mode || 'N/A'}</span></div>
                <div class="receipt-row"><span>Gateway ID</span><span class="small">${transaction.gateway_payment_id || 'N/A'}</span></div>
                ${serviceChargesAmount > 0 ? `
                <div class="receipt-row">
                    <span>Service Charges (${serviceChargesType} ${serviceCharges}%)</span>
                    <span>${currency} ${serviceChargesAmount}</span>
                </div>
                ` : ''}
                ${gstChargesAmount > 0 ? `
                <div class="receipt-row">
                    <span>GST (${gstCharges})</span>
                    <span>${currency} ${gstChargesAmount}</span>
                </div>
                ` : ''}
                <div class="receipt-row"><span>Subtotal</span><span>${currency} ${amount}</span></div>
                <div class="receipt-row" style="border-top:2px solid #e5e7eb;margin-top:8px;padding-top:12px;font-weight:bold;background:#f8f9fa;">
                    <span>Total Amount</span>
                    <span style="color:#4361ee;font-size:1.1rem;">${currency} ${totalAmount}</span>
                </div>
                ${!hasCharges ? '<div class="receipt-row"><span class="text-muted small">Note</span><span class="text-muted small">No additional charges applied</span></div>' : ''}
                <div class="receipt-row"><span>Status</span><span style="color:${status === 'paid' ? '#10b981' : '#f59e0b'}">${status.toUpperCase()}</span></div>
            `;
        }
        
        function printReceipt() {
            if (currentReceiptData) {
                const printWindow = window.open('', '_blank');
                const amount = parseFloat(currentReceiptData.amount || 0).toFixed(2);
                const serviceChargesAmount = parseFloat(currentReceiptData.service_charges_amount || 0).toFixed(2);
                const gstChargesAmount = parseFloat(currentReceiptData.gst_charges_amount || 0).toFixed(2);
                const hasCharges = (serviceChargesAmount > 0 || gstChargesAmount > 0);
                let totalAmount = amount;
                
                if (hasCharges) {
                    totalAmount = parseFloat(currentReceiptData.total_amount || (parseFloat(amount) + parseFloat(serviceChargesAmount) + parseFloat(gstChargesAmount))).toFixed(2);
                }
                
                const currency = currentReceiptData.currency || 'INR';
                
                printWindow.document.write(`
                    <!DOCTYPE html><html><head><title>Receipt</title>
                    <style>
                        body{font-family:Arial;padding:20px}
                        .receipt{max-width:500px;margin:0 auto}
                        .header{text-align:center;margin-bottom:20px}
                        .row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #eee}
                        .total{font-weight:bold;border-top:2px solid #333;margin-top:10px;padding-top:10px;background:#f8f9fa}
                        .charges-row{background:#f8f9fa;margin-top:5px}
                        .note{font-size:11px;color:#666;text-align:center;margin-top:15px}
                    </style>
                    </head><body><div class="receipt">
                    <div class="header"><h2>Payment Receipt</h2><p>${new Date().toLocaleDateString()}</p></div>
                    <div class="row"><strong>Transaction ID</strong><span>${currentReceiptData.transaction_reference}</span></div>
                    <div class="row"><strong>Date</strong><span>${new Date(currentReceiptData.created_at).toLocaleString()}</span></div>
                    <div class="row"><strong>User</strong><span>${currentReceiptData.user_name || 'N/A'}</span></div>
                    <div class="row"><strong>Payment Mode</strong><span>${currentReceiptData.payment_mode || 'N/A'}</span></div>
                    ${serviceChargesAmount > 0 ? `<div class="row charges-row"><strong>Service Charges</strong><span>${currency} ${serviceChargesAmount}</span></div>` : ''}
                    ${gstChargesAmount > 0 ? `<div class="row charges-row"><strong>GST</strong><span>${currency} ${gstChargesAmount}</span></div>` : ''}
                    <div class="row"><strong>Subtotal</strong><span>${currency} ${amount}</span></div>
                    <div class="row total"><strong>TOTAL AMOUNT</strong><span style="color:#4361ee;font-size:1.2rem;">${currency} ${totalAmount}</span></div>
                    ${!hasCharges ? '<div class="note">No additional charges applied</div>' : ''}
                    <div class="row"><strong>Status</strong><span>${(currentReceiptData.payment_status || currentReceiptData.status || 'created').toUpperCase()}</span></div>
                    </div><script>window.onload=function(){window.print();setTimeout(function(){window.close();},500)}<\/script></body></html>
                `);
                printWindow.document.close();
            }
        }
        
        function printTransaction(id) { generateReceipt(id); }
        function exportTransactions() { alert('Export to CSV/Excel'); }
        function createNewTransaction() { alert('Create new transaction'); }
        function closeReceiptModal() { 
            document.getElementById('receiptModal').style.display = 'none';
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
        
        function clearAllFilters() { resetFilters(); }
        
        document.getElementById('transactionRefFilter')?.addEventListener('input', applyFilters);
        document.getElementById('userIdFilter')?.addEventListener('input', applyFilters);
        document.getElementById('gatewayIdFilter')?.addEventListener('input', applyFilters);
        document.getElementById('minAmount')?.addEventListener('input', applyFilters);
        document.getElementById('maxAmount')?.addEventListener('input', applyFilters);
        document.getElementById('statusFilter')?.addEventListener('change', applyFilters);
        document.getElementById('pgTypeFilter')?.addEventListener('change', applyFilters);
        document.getElementById('paymentModeFilter')?.addEventListener('change', applyFilters);
        
        window.onclick = function(event) {
            if (event.target === document.getElementById('receiptModal')) closeReceiptModal();
        };
    </script>
@endsection