@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    :root {
        --primary-color: #4361ee;
        --primary-dark: #3a0ca3;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header h1 {
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.5rem;
    }

    .page-header h1 i {
        background: rgba(255, 255, 255, 0.2);
        padding: 10px;
        border-radius: 12px;
        font-size: 1.3rem;
    }

    .page-header p {
        margin: 0.25rem 0 0 0;
        opacity: 0.9;
        font-size: 0.9rem;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.7rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        color: white;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-box {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        border: 2px solid var(--border-color);
        transition: var(--transition);
    }

    .stat-box:hover {
        transform: translateY(-2px);
        box-shadow: var(--card-shadow);
    }

    .stat-box .number {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--text-dark);
    }

    .stat-box .label {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-box .number.green { color: var(--success-color); }
    .stat-box .number.blue { color: var(--primary-color); }
    .stat-box .number.orange { color: var(--warning-color); }
    .stat-box .number.red { color: var(--danger-color); }

    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        border: 2px solid var(--border-color);
        margin-bottom: 1.5rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }

    .filter-group label {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-group input,
    .filter-group select {
        padding: 0.5rem 0.8rem;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        font-size: 0.85rem;
        transition: var(--transition);
        background: white;
        width: 100%;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 0.5rem;
        align-items: end;
    }

    .filter-actions .btn {
        border-radius: 10px;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        font-size: 0.85rem;
        border: none;
        transition: var(--transition);
        cursor: pointer;
        white-space: nowrap;
    }

    .filter-actions .btn-primary {
        background: var(--primary-color);
        color: white;
    }

    .filter-actions .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(67, 97, 238, 0.3);
    }

    .filter-actions .btn-secondary {
        background: #e2e8f0;
        color: var(--text-dark);
    }

    .filter-actions .btn-secondary:hover {
        background: #cbd5e1;
        transform: translateY(-2px);
    }

    /* ============================================
       TABLE STYLES
    ============================================ */
    .table-container {
        background: white;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        border: 2px solid var(--border-color);
        overflow: hidden;
    }

    .table-scroll {
        overflow-x: auto;
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }

    .table-custom thead {
        background: #f8fafc;
        border-bottom: 2px solid var(--border-color);
    }

    .table-custom thead th {
        padding: 0.8rem 0.8rem;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        text-align: left;
        white-space: nowrap;
        position: sticky;
        top: 0;
        background: #f8fafc;
        z-index: 10;
    }

    .table-custom thead th:first-child {
        padding-left: 1rem;
    }

    .table-custom tbody tr {
        border-bottom: 1px solid var(--border-color);
        transition: var(--transition);
    }

    .table-custom tbody tr:hover {
        background: #fafbff;
    }

    .table-custom tbody tr:last-child {
        border-bottom: none;
    }

    .table-custom tbody td {
        padding: 0.75rem 0.8rem;
        vertical-align: middle;
        color: var(--text-dark);
    }

    .table-custom tbody td:first-child {
        padding-left: 1rem;
    }

    /* Code */
    .tx-code {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--text-dark);
    }

    .tx-code small {
        font-weight: 400;
        color: var(--text-muted);
        font-size: 0.7rem;
        display: block;
    }

    /* Badges - Status */
    .badge-status {
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }

    .badge-status.pending { background: #fef3c7; color: #92400e; }
    .badge-status.approved { background: #dbeafe; color: #1e40af; }
    .badge-status.in-transit { background: #e0e7ff; color: #3730a3; }
    .badge-status.completed { background: #d1fae5; color: #065f46; }
    .badge-status.cancelled { background: #fee2e2; color: #991b1b; }

    /* Badges - Type */
    .badge-type {
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }

    .badge-type.transfer { background: #e0e7ff; color: #3730a3; }
    .badge-type.sell { background: #fce7f3; color: #9d174d; }

    /* Badges - Payment */
    .badge-payment {
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }

    .badge-payment.pending { background: #fef3c7; color: #92400e; }
    .badge-payment.on_hold { background: #fef3c7; color: #92400e; }
    .badge-payment.completed { background: #d1fae5; color: #065f46; }
    .badge-payment.rejected { background: #fee2e2; color: #991b1b; }
    .badge-payment.cancelled { background: #fee2e2; color: #991b1b; }
    .badge-payment.refunded { background: #fce4ec; color: #721c24; }
    .badge-payment.partially_refunded { background: #fff3e0; color: #e65100; }

    /* Amount */
    .amount-sale {
        font-weight: 700;
        color: var(--success-color);
    }

    .amount-transfer {
        color: var(--primary-color);
        font-weight: 500;
    }

    /* Customer */
    .customer-info {
        display: flex;
        flex-direction: column;
    }

    .customer-info .name {
        font-weight: 500;
        color: var(--text-dark);
    }

    .customer-info .phone {
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    /* Location */
    .location-info {
        display: flex;
        flex-direction: column;
        font-size: 0.75rem;
    }

    .location-info .from {
        color: var(--text-muted);
    }

    .location-info .to {
        color: var(--text-dark);
        font-weight: 500;
    }

    .location-info .arrow {
        color: var(--text-muted);
        font-size: 0.6rem;
        margin: 0 2px;
    }

    /* Actions */
    .actions {
        display: flex;
        gap: 0.3rem;
    }

    .btn-action {
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 3px;
        text-decoration: none;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        text-decoration: none;
    }

    .btn-action.view { background: #dbeafe; color: #1e40af; }
    .btn-action.view:hover { background: #bfdbfe; }

    .btn-action.print { background: #e0e7ff; color: #3730a3; }
    .btn-action.print:hover { background: #c7d2fe; }

    .btn-action.refund { background: #fce4ec; color: #721c24; }
    .btn-action.refund:hover { background: #f8bbd0; }

    .btn-action.logistics { background: #e8f5e9; color: #1b5e20; }
    .btn-action.logistics:hover { background: #c8e6c9; }

    .btn-action.approve { background: #d1fae5; color: #065f46; }
    .btn-action.approve:hover { background: #a7f3d0; }

    .btn-action.complete { background: #dbeafe; color: #1e40af; }
    .btn-action.complete:hover { background: #bfdbfe; }

    .btn-action.cancel { background: #fee2e2; color: #991b1b; }
    .btn-action.cancel:hover { background: #fecaca; }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    .empty-state h4 {
        color: var(--text-dark);
        font-weight: 600;
    }

    .empty-state p {
        color: var(--text-muted);
    }

    /* Pagination */
    .pagination-container {
        padding: 1rem 1.5rem 1.5rem;
        border-top: 2px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .pagination-container .info {
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    .pagination-container .pagination {
        margin: 0;
    }

    .pagination-container .pagination .page-link {
        border: 2px solid var(--border-color);
        border-radius: 8px;
        margin: 0 2px;
        color: var(--text-dark);
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        font-size: 0.8rem;
        transition: var(--transition);
    }

    .pagination-container .pagination .page-link:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .pagination-container .pagination .active .page-link {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }

    /* ============================================
       RESPONSIVE
    ============================================ */
    @media (max-width: 992px) {
        .stats-row {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .filter-actions {
            flex-direction: column;
            width: 100%;
        }

        .filter-actions .btn {
            width: 100%;
            justify-content: center;
        }

        .table-custom {
            font-size: 0.75rem;
            min-width: 900px;
        }

        .table-custom tbody td {
            padding: 0.5rem 0.4rem;
        }

        .btn-action {
            font-size: 0.65rem;
            padding: 0.2rem 0.5rem;
        }

        .pagination-container {
            flex-direction: column;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .stats-row {
            grid-template-columns: 1fr 1fr;
        }

        .stat-box .number {
            font-size: 1.2rem;
        }

        .stat-box .label {
            font-size: 0.6rem;
        }
    }

    /* Sorting indicators */
    .sortable {
        cursor: pointer;
        user-select: none;
    }

    .sortable:hover {
        color: var(--primary-color);
    }

    .sortable .sort-icon {
        margin-left: 4px;
        font-size: 0.6rem;
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-exchange-alt"></i> Inventory Transactions</h1>
            <p>Manage all sales, transfers, payments, refunds and logistics</p>
        </div>
        <div>
            <a href="{{ route('inventory.dashboard') }}" class="d-none btn-back">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-row">
        <div class="stat-box">
            <div class="number blue">{{ $stats['total_transactions'] ?? 0 }}</div>
            <div class="label">Total Transactions</div>
        </div>
        <div class="stat-box">
            <div class="number green">{{ $stats['total_sales'] ?? 0 }}</div>
            <div class="label">Sales</div>
        </div>
        <div class="stat-box">
            <div class="number blue">{{ $stats['total_transfers'] ?? 0 }}</div>
            <div class="label">Transfers</div>
        </div>
        <div class="stat-box">
            <div class="number green">₹{{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
            <div class="label">Total Revenue</div>
        </div>
        <div class="stat-box">
            <div class="number orange">{{ $stats['pending_payments'] ?? 0 }}</div>
            <div class="label">Pending Payments</div>
        </div>
        <div class="stat-box">
            <div class="number red">{{ $stats['total_refunds'] ?? 0 }}</div>
            <div class="label">Refunds</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <form method="GET" action="{{ route('inventory.transactions.index') }}" class="filter-grid">
            <div class="filter-group">
                <label><i class="fas fa-search"></i> Search</label>
                <input type="text" name="search" placeholder="Code, customer, transaction..." value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <label><i class="fas fa-tag"></i> Type</label>
                <select name="type">
                    <option value="">All Types</option>
                    <option value="sell" {{ request('type') == 'sell' ? 'selected' : '' }}>Sale</option>
                    <option value="transfer" {{ request('type') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                </select>
            </div>
            <div class="filter-group">
                <label><i class="fas fa-circle"></i> Status</label>
                <select name="status">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="in-transit" {{ request('status') == 'in-transit' ? 'selected' : '' }}>In Transit</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="filter-group">
                <label><i class="fas fa-credit-card"></i> Payment Status</label>
                <select name="payment_status">
                    <option value="">All Payment Status</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="on_hold" {{ request('payment_status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                    <option value="completed" {{ request('payment_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="rejected" {{ request('payment_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="cancelled" {{ request('payment_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    <option value="partially_refunded" {{ request('payment_status') == 'partially_refunded' ? 'selected' : '' }}>Partially Refunded</option>
                </select>
            </div>
            <div class="filter-group">
                <label><i class="fas fa-money-bill"></i> Payment Method</label>
                <select name="payment_method">
                    <option value="">All Methods</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>COD</option>
                    <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                    <option value="upi" {{ request('payment_method') == 'upi' ? 'selected' : '' }}>UPI</option>
                    <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="cheque" {{ request('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                    <option value="online" {{ request('payment_method') == 'online' ? 'selected' : '' }}>Online Transfer</option>
                    <option value="pg" {{ request('payment_method') == 'pg' ? 'selected' : '' }}>Payment Gateway</option>
                </select>
            </div>
            <div class="filter-group">
                <label><i class="fas fa-calendar"></i> Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div class="filter-group">
                <label><i class="fas fa-calendar"></i> Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}">
            </div>
            <div class="filter-group filter-actions">
                <div class="d-flex" style="gap: 5px">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Apply
                    </button>
                    <a href="{{ route('inventory.transactions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-undo"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="table-container">
        <div class="table-scroll">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Transaction Id</th>
                        <th class="sortable">Code</th>
                        <th class="sortable">Type</th>
                        <th class="sortable">Status</th>
                        <th class="sortable">Customer / Location</th>
                        <th class="sortable">Payment</th>
                        <th class="sortable">Amount</th>
                        <th class="sortable">Logistics</th>
                        <th class="sortable">Created</th>
                        <th class="sortable">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        @php
                            $isSell = $transaction->type === 'sell';
                            $locationFrom = $transaction->fromWarehouse->warehouse_name ?? $transaction->fromStore->store_name ?? 'N/A';
                            $locationTo = $transaction->toWarehouse->warehouse_name ?? $transaction->toStore->store_name ?? 'N/A';
                            $customerName = $transaction->customer_name ?? 'Walk-in';
                            $customerPhone = $transaction->customer_phone ?? 'N/A';
                            $logistics = $transaction->logistics ?? null;
                            $transporter = $logistics['transporter'] ?? null;
                            $amount = $transaction->total_amount ?? 0;
                            $paymentStatus = $transaction->payment_status ?? 'pending';
                            $status = $transaction->status ?? 'pending';
                            $items = $transaction->items ?? [];
                            $itemsCount = is_array($items) ? count($items) : 0;
                            $txCode = $transaction->stock_out_code ?? '#' . $transaction->id;
                        @endphp
                        <tr>
                            <td>
                                <small>{{ $transaction->id }}</small>
                            </td>
                            <!-- Code -->
                            <td>
                                <div class="tx-code">
                                    {{ $txCode }}
                                </div>
                            </td>

                            <!-- Type -->
                            <td>
                                <span class="badge-type {{ $isSell ? 'sell' : 'transfer' }}">
                                    <i class="fas {{ $isSell ? 'fa-shopping-cart' : 'fa-exchange-alt' }}"></i>
                                    {{ $isSell ? 'Sale' : 'Transfer' }}
                                </span>
                                @if(!$isSell && $itemsCount > 0)
                                    <small class="d-block text-muted">{{ $itemsCount }} item(s)</small>
                                @endif
                            </td>

                            <!-- Status -->
                            <td>
                                <span class="badge-status {{ $status }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>

                            <!-- Customer / Location -->
                            <td>
                                @if($isSell)
                                    <div class="customer-info">
                                        <span class="name"><i class="fas fa-user"></i> {{ $customerName }}</span>
                                        @if($customerPhone !== 'N/A')
                                            <span class="phone"><i class="fas fa-phone"></i> {{ $customerPhone }}</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="location-info">
                                        <span class="from"><i class="fas fa-arrow-right"></i> From: {{ $locationFrom }}</span>
                                        <span class="to"><i class="fas fa-arrow-left"></i> To: {{ $locationTo }}</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Payment -->
                            <td>
                                <span class="badge-payment {{ $paymentStatus }}">
                                    {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}
                                </span>
                                @if($transaction->payment_method)
                                    <small class="d-block text-muted">
                                        <i class="fas fa-credit-card"></i> {{ ucfirst($transaction->payment_method) }}
                                    </small>
                                @endif
                            </td>

                            <!-- Amount -->
                            <td>
                                @if($isSell)
                                    <span class="amount-sale">₹{{ number_format($amount, 2) }}</span>
                                @else
                                    <span class="amount-transfer">₹{{ number_format($amount, 2) }}</span>
                                @endif
                            </td>

                            <!-- Logistics -->
                            <td>
                                @if($transporter)
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold"><i class="fas fa-truck"></i> {{ $transporter }}</span>
                                        @if(isset($logistics['tracking_number']))
                                            <small class="text-muted">Track: {{ $logistics['tracking_number'] }}</small>
                                        @endif
                                    </div>
                                @else
                                    <a href="{{ route('inventory.transactions.logistics', $transaction->id) }}" 
                                       class="btn-action logistics" title="Update Logistics">
                                        <i class="fas fa-truck"></i> Add Logistics
                                    </a>
                                @endif
                            </td>

                            <!-- Created -->
                            <td>
                                <div class="d-flex flex-column">
                                    <span>{{ $transaction->created_at->format('d M Y') }}</span>
                                    <small class="text-muted">{{ $transaction->created_at->format('h:i A') }}</small>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td>
                                <div class="actions">
                                    <a href="{{ route('inventory.transactions.show', $transaction->id) }}" 
                                       class="btn-action view" title="View Details">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('inventory.transactions.print', $transaction->id) }}" 
                                       class="btn-action print" target="_blank" title="Print Receipt">
                                        <i class="fas fa-print"></i> Print
                                    </a>
                                    
                                    @if($status === 'pending')
                                        @if($isSell)
                                            <a href="{{ route('inventory.transactions.approve', $transaction->id) }}" 
                                               class="btn-action approve" title="Approve Sale">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        @endif
                                    @endif

                                    @if($isSell && !in_array($paymentStatus, ['refunded', 'completed']) && $status === 'completed')
                                        <a href="{{ route('inventory.transactions.refund', $transaction->id) }}" 
                                           class="btn-action refund" title="Process Refund">
                                            <i class="fas fa-undo"></i> Refund
                                        </a>
                                    @endif

                                    @if($status === 'approved' && !$isSell)
                                        <a href="{{ route('inventory.transactions.complete', $transaction->id) }}" 
                                           class="btn-action complete" title="Complete Transfer">
                                            <i class="fas fa-check-double"></i>
                                        </a>
                                    @endif

                                    @if(in_array($status, ['pending', 'approved']))
                                        <a href="{{ route('inventory.transactions.cancel', $transaction->id) }}" 
                                           class="btn-action cancel" title="Cancel Transaction"
                                           onclick="return confirm('Are you sure you want to cancel this transaction?')">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="fas fa-exchange-alt"></i>
                                    <h4>No Transactions Found</h4>
                                    <p class="text-muted">No inventory transactions match your filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
            <div class="pagination-container">
                <div class="info">
                    Showing {{ $transactions->firstItem() ?? 0 }} to {{ $transactions->lastItem() ?? 0 }} 
                    of {{ $transactions->total() }} transactions
                </div>
                <div>
                    {{ $transactions->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

@endsection