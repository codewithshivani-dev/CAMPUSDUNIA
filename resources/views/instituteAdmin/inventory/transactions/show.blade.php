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

    .detail-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: var(--card-shadow);
        border: 2px solid var(--border-color);
        margin-bottom: 1.5rem;
    }

    .detail-card .card-title {
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .detail-card .card-title i {
        color: var(--primary-color);
        font-size: 1.2rem;
    }

    .detail-card .card-title .badge {
        font-size: 0.65rem;
        padding: 0.2rem 0.7rem;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.75rem;
    }

    .detail-item {
        padding: 0.5rem 0.75rem;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #f1f5f9;
    }

    .detail-item .label {
        font-size: 0.65rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-item .value {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-top: 0.1rem;
        word-break: break-word;
    }

    .detail-item .value .sub-text {
        font-weight: 400;
        color: var(--text-muted);
        font-size: 0.75rem;
    }

    .badge-status {
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-status.pending { background: #fef3c7; color: #92400e; }
    .badge-status.approved { background: #dbeafe; color: #1e40af; }
    .badge-status.in-transit { background: #e0e7ff; color: #3730a3; }
    .badge-status.completed { background: #d1fae5; color: #065f46; }
    .badge-status.cancelled { background: #fee2e2; color: #991b1b; }

    .badge-payment {
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-payment.pending { background: #fef3c7; color: #92400e; }
    .badge-payment.on_hold { background: #fef3c7; color: #92400e; }
    .badge-payment.completed { background: #d1fae5; color: #065f46; }
    .badge-payment.rejected { background: #fee2e2; color: #991b1b; }
    .badge-payment.cancelled { background: #fee2e2; color: #991b1b; }
    .badge-payment.refunded { background: #fce4ec; color: #721c24; }
    .badge-payment.partially_refunded { background: #fff3e0; color: #e65100; }

    .badge-type {
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-type.transfer { background: #e0e7ff; color: #3730a3; }
    .badge-type.sell { background: #fce7f3; color: #9d174d; }

    /* ============================================
       TABLE STYLES - OPTIMIZED FOR LARGE DATA
    ============================================ */
    .table-responsive-wrapper {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.82rem;
        min-width: 600px;
    }

    .table-custom thead {
        background: #f8fafc;
        border-bottom: 2px solid var(--border-color);
    }

    .table-custom thead th {
        padding: 0.6rem 0.8rem;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        text-align: left;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
        background: #f8fafc;
    }

    .table-custom tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: var(--transition);
    }

    .table-custom tbody tr:hover {
        background: #fafbff;
    }

    .table-custom tbody tr:last-child {
        border-bottom: none;
    }

    .table-custom tbody td {
        padding: 0.5rem 0.8rem;
        vertical-align: middle;
        color: var(--text-dark);
    }

    .table-custom .item-name {
        font-weight: 500;
        color: var(--text-dark);
    }

    .table-custom .item-code {
        font-size: 0.7rem;
        color: var(--text-muted);
        background: #f1f5f9;
        padding: 0.1rem 0.4rem;
        border-radius: 4px;
        display: inline-block;
    }

    .table-custom .text-right {
        text-align: right;
    }

    .table-custom .text-center {
        text-align: center;
    }

    .table-custom .total-row {
        background: #f8fafc;
        font-weight: 700;
        border-top: 2px solid var(--border-color);
    }

    .table-custom .total-row td {
        padding: 0.6rem 0.8rem;
    }

    /* Logistics Card */
    .logistics-card {
        background: #f0fdf4;
        border: 2px solid #bbf7d0;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-top: 0.5rem;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.5rem 1.5rem;
    }

    .logistics-card .log-item {
        display: flex;
        flex-direction: column;
        gap: 0.1rem;
    }

    .logistics-card .log-item .label {
        font-weight: 600;
        color: var(--text-muted);
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .logistics-card .log-item .value {
        font-weight: 500;
        color: var(--text-dark);
        font-size: 0.85rem;
    }

    .logistics-empty {
        color: var(--text-muted);
        font-size: 0.9rem;
        padding: 0.5rem 0;
    }

    .logistics-empty i {
        margin-right: 0.5rem;
    }

    /* Buttons */
    .btn-action {
        padding: 0.45rem 1.2rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.8rem;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        text-decoration: none;
    }

    .btn-primary-custom {
        background: var(--primary-color);
        color: white;
    }

    .btn-primary-custom:hover {
        background: var(--primary-dark);
        color: white;
    }

    .btn-success-custom {
        background: var(--success-color);
        color: white;
    }

    .btn-success-custom:hover {
        background: #059669;
        color: white;
    }

    .btn-warning-custom {
        background: var(--warning-color);
        color: white;
    }

    .btn-warning-custom:hover {
        background: #d97706;
        color: white;
    }

    .btn-danger-custom {
        background: var(--danger-color);
        color: white;
    }

    .btn-danger-custom:hover {
        background: #dc2626;
        color: white;
    }

    .btn-outline-secondary {
        background: #f1f5f9;
        color: var(--text-dark);
        border: 2px solid var(--border-color);
    }

    .btn-outline-secondary:hover {
        background: #e2e8f0;
        color: var(--text-dark);
    }

    /* Action Buttons Group */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .action-buttons .btn-action {
        flex: 0 1 auto;
    }

    /* Modal */
    .modal-content {
        border-radius: 16px;
        border: none;
    }

    .modal-header {
        border-radius: 16px 16px 0 0;
        padding: 1.25rem 1.5rem;
    }

    .modal-header.bg-primary { background: var(--primary-color); color: white; }
    .modal-header.bg-success { background: var(--success-color); color: white; }
    .modal-header.bg-danger { background: var(--danger-color); color: white; }
    .modal-header.bg-warning { background: var(--warning-color); color: white; }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }

    .modal-body {
        padding: 1.5rem;
        max-height: 70vh;
        overflow-y: auto;
    }

    .modal-footer {
        border-radius: 0 0 16px 16px;
        padding: 1rem 1.5rem;
        border-top: 2px solid var(--border-color);
    }

    .form-control, .form-select {
        border: 2px solid var(--border-color);
        border-radius: 10px;
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.85rem;
        margin-bottom: 0.3rem;
    }

    .form-label .required-star {
        color: var(--danger-color);
        margin-left: 2px;
    }

    .input-group-text {
        background: #f1f5f9;
        border: 2px solid var(--border-color);
        border-right: none;
        border-radius: 10px 0 0 10px;
        font-weight: 600;
    }

    .input-group .form-control {
        border-radius: 0 10px 10px 0;
    }

    .text-muted-small {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .detail-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .logistics-card {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .page-header .d-flex {
            justify-content: center;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .logistics-card {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-buttons .btn-action {
            width: 100%;
            justify-content: center;
        }

        .table-custom {
            font-size: 0.75rem;
            min-width: 500px;
        }

        .table-custom thead th,
        .table-custom tbody td {
            padding: 0.4rem 0.5rem;
        }
    }

    @media (max-width: 480px) {
        .detail-card {
            padding: 1rem;
        }

        .modal-body {
            padding: 1rem;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-eye"></i> Transaction Details</h1>
            <p>Reference: <strong>{{ $transaction->stock_out_code }}</strong> | #{{ $transaction->id }}</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('inventory.transactions.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="{{ route('inventory.transactions.print', $transaction->id) }}" class="btn-back" target="_blank">
                <i class="fas fa-print"></i> Print
            </a>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- TRANSACTION INFORMATION -->
    <!-- ============================================ -->
    <div class="detail-card">
        <div class="card-title">
            <i class="fas fa-info-circle"></i> Transaction Information
            <span class="badge bg-primary ms-2">{{ $transaction->id }}</span>
        </div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="label">Transaction Code</div>
                <div class="value">{{ $transaction->stock_out_code }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Transaction ID</div>
                <div class="value">{{ $transaction->id ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Receipt Number</div>
                <div class="value">{{ $transaction->receipt_number ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Type</div>
                <div class="value">
                    <span class="badge-type {{ $transaction->type }}">
                        {{ ucfirst($transaction->type) }}
                        @if($transaction->sub_type)
                            <span class="sub-text">({{ str_replace('_', ' ', ucfirst($transaction->sub_type)) }})</span>
                        @endif
                    </span>
                </div>
            </div>
            <div class="detail-item">
                <div class="label">Status</div>
                <div class="value">
                    <span class="badge-status {{ $transaction->status }}">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </div>
            </div>
            <div class="detail-item">
                <div class="label">Payment Status</div>
                <div class="value">
                    <span class="badge-payment {{ $transaction->payment_status ?? 'pending' }}">
                        {{ ucfirst(str_replace('_', ' ', $transaction->payment_status ?? 'Pending')) }}
                    </span>
                </div>
            </div>
            <div class="detail-item">
                <div class="label">Payment Method</div>
                <div class="value">{{ ucfirst($transaction->payment_method ?? 'N/A') }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Created At</div>
                <div class="value">{{ $transaction->created_at->format('d M Y, h:i A') }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Updated At</div>
                <div class="value">{{ $transaction->updated_at->format('d M Y, h:i A') }}</div>
            </div>
            <div class="detail-item">
                <div class="label">Created By</div>
                <div class="value">{{ $transaction->creator->name ?? 'N/A' }}</div>
            </div>
            @if($transaction->approved_by)
                <div class="detail-item">
                    <div class="label">Approved By</div>
                    <div class="value">{{ $transaction->approver->name ?? 'N/A' }}</div>
                </div>
            @endif
            @if($transaction->print_count > 0)
                <div class="detail-item">
                    <div class="label">Print Count</div>
                    <div class="value">{{ $transaction->print_count }} times</div>
                </div>
            @endif
            @if($transaction->reason)
                <div class="detail-item" style="grid-column: 1 / -1;">
                    <div class="label">Reason</div>
                    <div class="value">{{ $transaction->reason }}</div>
                </div>
            @endif
            @if($transaction->notes)
                <div class="detail-item" style="grid-column: 1 / -1;">
                    <div class="label">Notes</div>
                    <div class="value">{{ $transaction->notes }}</div>
                </div>
            @endif
        </div>
    </div>

    <!-- ============================================ -->
    <!-- LOCATION & CUSTOMER DETAILS -->
    <!-- ============================================ -->
    <div class="detail-card">
        <div class="card-title">
            <i class="fas fa-map-marker-alt"></i> Location & Customer Details
        </div>
        <div class="detail-grid">
            @if($transaction->type === 'sell')
                <div class="detail-item">
                    <div class="label">Customer Name</div>
                    <div class="value">{{ $transaction->customer_name ?? 'Walk-in Customer' }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Phone</div>
                    <div class="value">{{ $transaction->customer_phone ?? 'N/A' }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Email</div>
                    <div class="value">{{ $transaction->customer_email ?? 'N/A' }}</div>
                </div>
                <div class="detail-item">
                    <div class="label">Customer Type</div>
                    <div class="value">{{ ucfirst($transaction->customer_type ?? 'Individual') }}</div>
                </div>
                @if($transaction->gst_number)
                    <div class="detail-item">
                        <div class="label">GST Number</div>
                        <div class="value">{{ $transaction->gst_number }}</div>
                    </div>
                @endif
                @if($transaction->pan_number)
                    <div class="detail-item">
                        <div class="label">PAN Number</div>
                        <div class="value">{{ $transaction->pan_number }}</div>
                    </div>
                @endif
                @if($transaction->customer_address)
                    <div class="detail-item" style="grid-column: 1 / -1;">
                        <div class="label">Address</div>
                        <div class="value">{{ $transaction->customer_address }}</div>
                    </div>
                @endif
            @else
                <div class="detail-item">
                    <div class="label">From Location</div>
                    <div class="value">
                        @if($transaction->fromWarehouse)
                            <i class="fas fa-warehouse text-primary"></i> 
                            {{ $transaction->fromWarehouse->warehouse_name }}
                            <span class="sub-text">({{ $transaction->fromWarehouse->warehouse_code }})</span>
                        @elseif($transaction->fromStore)
                            <i class="fas fa-store text-warning"></i> 
                            {{ $transaction->fromStore->store_name }}
                            <span class="sub-text">({{ $transaction->fromStore->store_code }})</span>
                        @else
                            N/A
                        @endif
                    </div>
                </div>
                <div class="detail-item">
                    <div class="label">To Location</div>
                    <div class="value">
                        @if($transaction->toWarehouse)
                            <i class="fas fa-warehouse text-success"></i> 
                            {{ $transaction->toWarehouse->warehouse_name }}
                            <span class="sub-text">({{ $transaction->toWarehouse->warehouse_code }})</span>
                        @elseif($transaction->toStore)
                            <i class="fas fa-store text-success"></i> 
                            {{ $transaction->toStore->store_name }}
                            <span class="sub-text">({{ $transaction->toStore->store_code }})</span>
                        @else
                            N/A
                        @endif
                    </div>
                </div>
                @if($transaction->from_location_path || $transaction->to_location_path)
                    <div class="detail-item" style="grid-column: 1 / -1;">
                        <div class="label">Location Path</div>
                        <div class="value">
                            @if($transaction->from_location_path)
                                <span class="text-muted">From:</span> {{ $transaction->from_location_path }}
                                <span class="mx-2">→</span>
                            @endif
                            @if($transaction->to_location_path)
                                <span class="text-muted">To:</span> {{ $transaction->to_location_path }}
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- ============================================ -->
    <!-- FINANCIAL DETAILS -->
    <!-- ============================================ -->
    <div class="detail-card">
        <div class="card-title">
            <i class="fas fa-money-bill-wave"></i> Financial Details
        </div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="label">Subtotal</div>
                <div class="value">₹{{ number_format($transaction->subtotal ?? 0, 2) }}</div>
            </div>
            @if(($transaction->discount_amount ?? 0) > 0)
                <div class="detail-item">
                    <div class="label">Discount</div>
                    <div class="value" style="color: var(--danger-color);">- ₹{{ number_format($transaction->discount_amount, 2) }}</div>
                </div>
            @endif
            @if(($transaction->tax_amount ?? 0) > 0)
                <div class="detail-item">
                    <div class="label">Tax</div>
                    <div class="value">+ ₹{{ number_format($transaction->tax_amount, 2) }}</div>
                </div>
            @endif
            @if(($transaction->service_charges ?? 0) > 0)
                <div class="detail-item">
                    <div class="label">Service Charges</div>
                    <div class="value">+ ₹{{ number_format($transaction->service_charges, 2) }}</div>
                </div>
            @endif
            @if(($transaction->gst_charges ?? 0) > 0)
                <div class="detail-item">
                    <div class="label">GST Charges</div>
                    <div class="value">+ ₹{{ number_format($transaction->gst_charges, 2) }}</div>
                </div>
            @endif
            <div class="detail-item" style="background: #d1fae5; border: 2px solid #a7f3d0;">
                <div class="label">Total Amount</div>
                <div class="value" style="font-size: 1.2rem; color: var(--success-color);">
                    ₹{{ number_format($transaction->total_amount ?? 0, 2) }}
                </div>
            </div>
            @if($transaction->amount_received)
                <div class="detail-item" style="background: #dbeafe; border: 2px solid #bfdbfe;">
                    <div class="label">Amount Received</div>
                    <div class="value" style="color: var(--primary-color);">
                        ₹{{ number_format($transaction->amount_received, 2) }}
                    </div>
                </div>
            @endif
            @if($transaction->change_amount)
                <div class="detail-item">
                    <div class="label">Change Amount</div>
                    <div class="value" style="color: var(--warning-color);">
                        ₹{{ number_format($transaction->change_amount, 2) }}
                    </div>
                </div>
            @endif
            @if($transaction->payment_notes)
                <div class="detail-item" style="grid-column: 1 / -1;">
                    <div class="label">Payment Notes</div>
                    <div class="value">{{ $transaction->payment_notes }}</div>
                </div>
            @endif
        </div>
    </div>

    <!-- ============================================ -->
    <!-- ITEMS TABLE - Optimized for large data -->
    <!-- ============================================ -->
    <div class="detail-card">
        <div class="card-title">
            <i class="fas fa-boxes"></i> Items
            <span class="badge bg-primary ms-2">{{ count($transaction->items ?? []) }}</span>
            @if(($transaction->items ?? []))
                <span class="badge bg-secondary ms-1">{{ count($transaction->items ?? []) }} items</span>
            @endif
        </div>

        <div class="table-responsive-wrapper">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 30%;">Item Name</th>
                        <th style="width: 15%;">Code</th>
                        <th style="width: 10%; text-align: center;">Qty</th>
                        @if($transaction->type === 'sell')
                            <th style="width: 15%; text-align: right;">Unit Price</th>
                            <th style="width: 20%; text-align: right;">Total</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @php
                        $items = $transaction->items ?? [];
                        $grandTotal = 0;
                        $totalQuantity = 0;
                    @endphp

                    @forelse($items as $index => $item)
                        @php
                            $itemTotal = ($item['quantity'] ?? 0) * ($item['price'] ?? 0);
                            $grandTotal += $itemTotal;
                            $totalQuantity += ($item['quantity'] ?? 0);
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="item-name">{{ $item['name'] ?? 'N/A' }}</span>
                                @if(isset($item['description']))
                                    <br><small class="text-muted">{{ $item['description'] }}</small>
                                @endif
                            </td>
                            <td><span class="item-code">{{ $item['code'] ?? 'N/A' }}</span></td>
                            <td class="text-center">{{ $item['quantity'] ?? 0 }}</td>
                            @if($transaction->type === 'sell')
                                <td class="text-right">₹{{ number_format($item['price'] ?? 0, 2) }}</td>
                                <td class="text-right" style="font-weight: 600;">₹{{ number_format($itemTotal, 2) }}</td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $transaction->type === 'sell' ? 6 : 4 }}" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                <i class="fas fa-box-open" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                                No items found in this transaction.
                            </td>
                        </tr>
                    @endforelse

                    @if($transaction->type === 'sell' && count($items) > 0)
                        <tr class="total-row">
                            <td colspan="{{ $transaction->type === 'sell' ? 3 : 2 }}" style="text-align: right;">
                                <strong>Total</strong>
                            </td>
                            <td class="text-center"><strong>{{ $totalQuantity }}</strong></td>
                            @if($transaction->type === 'sell')
                                <td></td>
                                <td class="text-right" style="color: var(--success-color); font-size: 1.05rem;">
                                    <strong>₹{{ number_format($grandTotal, 2) }}</strong>
                                </td>
                            @endif
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- LOGISTICS DETAILS -->
    <!-- ============================================ -->
    @php $logistics = $transaction->logistics ?? null; @endphp
    <div class="detail-card">
        <div class="card-title">
            <i class="fas fa-truck"></i> Logistics Details
            @if($logistics && is_array($logistics) && count($logistics) > 0)
                <span class="badge bg-success ms-2">Available</span>
            @else
                <span class="badge bg-secondary ms-2">Not Added</span>
            @endif
        </div>

        @if($logistics && is_array($logistics) && count($logistics) > 0)
            <div class="logistics-card">
                @if(isset($logistics['transporter']))
                    <div class="log-item">
                        <span class="label">Transporter</span>
                        <span class="value">{{ $logistics['transporter'] }}</span>
                    </div>
                @endif
                @if(isset($logistics['vehicle_number']) || isset($logistics['vehicle']))
                    <div class="log-item">
                        <span class="label">Vehicle Number</span>
                        <span class="value">{{ $logistics['vehicle_number'] ?? $logistics['vehicle'] ?? 'N/A' }}</span>
                    </div>
                @endif
                @if(isset($logistics['driver_name']) || isset($logistics['driver']))
                    <div class="log-item">
                        <span class="label">Driver Name</span>
                        <span class="value">{{ $logistics['driver_name'] ?? $logistics['driver'] ?? 'N/A' }}</span>
                    </div>
                @endif
                @if(isset($logistics['driver_phone']))
                    <div class="log-item">
                        <span class="label">Driver Phone</span>
                        <span class="value">{{ $logistics['driver_phone'] }}</span>
                    </div>
                @endif
                @if(isset($logistics['tracking_number']))
                    <div class="log-item">
                        <span class="label">Tracking Number</span>
                        <span class="value">{{ $logistics['tracking_number'] }}</span>
                    </div>
                @endif
                @if(isset($logistics['expected_delivery_date']))
                    <div class="log-item">
                        <span class="label">Expected Delivery</span>
                        <span class="value">{{ \Carbon\Carbon::parse($logistics['expected_delivery_date'])->format('d M Y') }}</span>
                    </div>
                @endif
                @if(isset($logistics['notes']))
                    <div class="log-item" style="grid-column: 1 / -1;">
                        <span class="label">Notes</span>
                        <span class="value">{{ $logistics['notes'] }}</span>
                    </div>
                @endif
                @if(isset($logistics['updated_at']))
                    <div class="log-item" style="grid-column: 1 / -1;">
                        <span class="label">Last Updated</span>
                        <span class="value">{{ \Carbon\Carbon::parse($logistics['updated_at'])->format('d M Y, h:i A') }}</span>
                    </div>
                @endif
            </div>
        @else
            <p class="logistics-empty">
                <i class="fas fa-info-circle"></i> No logistics details have been added for this transaction.
            </p>
        @endif

        <div class="mt-3">
            <button type="button" class="btn-action btn-primary-custom" data-bs-toggle="modal" data-bs-target="#logisticsModal">
                <i class="fas fa-edit"></i> Update Logistics
            </button>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- ACTION BUTTONS -->
    <!-- ============================================ -->
    <div class="detail-card">
        <div class="card-title">
            <i class="fas fa-cogs"></i> Actions
        </div>
        <div class="action-buttons">
            <a href="{{ route('inventory.transactions.print', $transaction->id) }}" class="btn-action btn-primary-custom" target="_blank">
                <i class="fas fa-print"></i> Print Receipt
            </a>

            @if($transaction->status === 'pending')
                @if($transaction->type === 'sell')
                    <a href="#" class="btn-action btn-success-custom" onclick="approveTransaction({{ $transaction->id }})">
                        <i class="fas fa-check"></i> Approve Sale
                    </a>
                @endif
            @endif

            @if($transaction->type === 'sell' && !in_array($transaction->payment_status, ['refunded', 'completed']) && $transaction->status === 'completed')
                <button type="button" class="btn-action btn-warning-custom" data-bs-toggle="modal" data-bs-target="#refundModal">
                    <i class="fas fa-undo"></i> Process Refund
                </button>
            @endif

            @if($transaction->status === 'approved' && $transaction->type === 'transfer')
                <a href="#" class="btn-action btn-success-custom" onclick="completeTransfer({{ $transaction->id }})">
                    <i class="fas fa-check-double"></i> Complete Transfer
                </a>
            @endif

            @if(in_array($transaction->status, ['pending', 'approved']))
                <a href="#" class="btn-action btn-danger-custom" onclick="cancelTransaction({{ $transaction->id }})">
                    <i class="fas fa-times"></i> Cancel Transaction
                </a>
            @endif

            <button type="button" class="btn-action btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#paymentStatusModal">
                <i class="fas fa-credit-card"></i> Update Payment Status
            </button>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- PAYMENT STATUS MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="paymentStatusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title"><i class="fas fa-credit-card"></i> Update Payment Status</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('inventory.transactions.update-payment-status', $transaction->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Payment Status <span class="required-star">*</span></label>
                        <select name="payment_status" class="form-select" required>
                            <option value="pending" {{ ($transaction->payment_status ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="on_hold" {{ ($transaction->payment_status ?? '') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                            <option value="completed" {{ ($transaction->payment_status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="rejected" {{ ($transaction->payment_status ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="cancelled" {{ ($transaction->payment_status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="refunded" {{ ($transaction->payment_status ?? '') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            <option value="partially_refunded" {{ ($transaction->payment_status ?? '') == 'partially_refunded' ? 'selected' : '' }}>Partially Refunded</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Notes</label>
                        <textarea name="payment_notes" class="form-control" rows="3" placeholder="Add notes about payment status...">{{ $transaction->payment_notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- REFUND MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="refundModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-undo"></i> Process Refund</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('inventory.transactions.refund', $transaction->id) }}">
                @csrf
                <div class="modal-body">
                    @php
                        $maxRefundable = ($transaction->amount_received ?? $transaction->total_amount ?? 0);
                    @endphp
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Refund Amount <span class="required-star">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" name="refund_amount" class="form-control" 
                                       placeholder="0.00" required min="0.01" max="{{ $maxRefundable }}"
                                       value="{{ $maxRefundable }}">
                            </div>
                            <small class="text-muted-small">
                                Max refundable: ₹{{ number_format($maxRefundable, 2) }}
                            </small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Refund Method <span class="required-star">*</span></label>
                            <select name="refund_method" class="form-select" required>
                                <option value="">Select Method</option>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                                <option value="upi">UPI</option>
                                <option value="online">Online Transfer</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Refund Reason <span class="required-star">*</span></label>
                        <textarea name="refund_reason" class="form-control" rows="2" required placeholder="Reason for refund..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Refund Notes</label>
                        <textarea name="refund_notes" class="form-control" rows="2" placeholder="Additional notes..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Refund Items (Optional)</label>
                        <div class="table-responsive-wrapper" style="max-height: 200px; overflow-y: auto;">
                            <table class="table-custom" style="min-width: 300px;">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">
                                            <input type="checkbox" id="selectAllItems" onclick="toggleAllItems(this)">
                                        </th>
                                        <th>Item</th>
                                        <th style="text-align: center;">Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaction->items ?? [] as $index => $item)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="refund_items[{{ $index }}][id]" 
                                                       value="{{ $item['id'] ?? $index }}"
                                                       class="item-checkbox">
                                            </td>
                                            <td>
                                                <span class="item-name">{{ $item['name'] ?? 'N/A' }}</span>
                                                <br><small class="text-muted">{{ $item['code'] ?? '' }}</small>
                                            </td>
                                            <td style="text-align: center;">
                                                <input type="number" name="refund_items[{{ $index }}][quantity]" 
                                                       class="form-control form-control-sm" style="width: 70px; display: inline-block;"
                                                       value="{{ $item['quantity'] ?? 0 }}" min="0" max="{{ $item['quantity'] ?? 0 }}">
                                                <input type="hidden" name="refund_items[{{ $index }}][name]" value="{{ $item['name'] ?? '' }}">
                                                <input type="hidden" name="refund_items[{{ $index }}][code]" value="{{ $item['code'] ?? '' }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted-small">Select items to reverse stock upon refund.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Process Refund</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- LOGISTICS MODAL -->
<!-- ============================================ -->
<div class="modal fade" id="logisticsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title"><i class="fas fa-truck"></i> Update Logistics</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('inventory.transactions.logistics', $transaction->id) }}">
                @csrf
                <div class="modal-body">
                    @php $logistics = $transaction->logistics ?? []; @endphp
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Transporter</label>
                            <input type="text" name="transporter" class="form-control" 
                                   placeholder="e.g., Blue Dart, DTDC" 
                                   value="{{ $logistics['transporter'] ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Vehicle Number</label>
                            <input type="text" name="vehicle_number" class="form-control" 
                                   placeholder="e.g., UP 32 AB 1234" 
                                   value="{{ $logistics['vehicle_number'] ?? $logistics['vehicle'] ?? '' }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Driver Name</label>
                            <input type="text" name="driver_name" class="form-control" 
                                   placeholder="Driver name" 
                                   value="{{ $logistics['driver_name'] ?? $logistics['driver'] ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Driver Phone</label>
                            <input type="text" name="driver_phone" class="form-control" 
                                   placeholder="Driver phone number" 
                                   value="{{ $logistics['driver_phone'] ?? '' }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tracking Number</label>
                            <input type="text" name="tracking_number" class="form-control" 
                                   placeholder="Tracking / AWB number" 
                                   value="{{ $logistics['tracking_number'] ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Expected Delivery Date</label>
                            <input type="date" name="expected_delivery_date" class="form-control" 
                                   value="{{ isset($logistics['expected_delivery_date']) ? \Carbon\Carbon::parse($logistics['expected_delivery_date'])->format('Y-m-d') : '' }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Logistics Notes</label>
                        <textarea name="logistics_notes" class="form-control" rows="3" 
                                  placeholder="Additional logistics notes...">{{ $logistics['notes'] ?? '' }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Update Logistics</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- JAVASCRIPT -->
<!-- ============================================ -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ============================================
    // SELECT ALL ITEMS FOR REFUND
    // ============================================
    function toggleAllItems(checkbox) {
        document.querySelectorAll('.item-checkbox').forEach(function(item) {
            item.checked = checkbox.checked;
        });
    }

    // ============================================
    // APPROVE TRANSACTION
    // ============================================
    function approveTransaction(id) {
        Swal.fire({
            title: 'Approve Sale?',
            text: 'This will approve the sale and process the stock reduction.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Yes, Approve',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: function() {
                return fetch('/inventory/stock-out/' + id + '/approve', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        return data;
                    } else {
                        throw new Error(data.message || 'Something went wrong');
                    }
                })
                .catch(function(error) {
                    Swal.showValidationMessage('Request failed: ' + error.message);
                });
            }
        }).then(function(result) {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Sale approved successfully.',
                    icon: 'success',
                    confirmButtonColor: '#4361ee'
                }).then(function() {
                    window.location.reload();
                });
            }
        });
    }

    // ============================================
    // COMPLETE TRANSFER
    // ============================================
    function completeTransfer(id) {
        Swal.fire({
            title: 'Complete Transfer?',
            text: 'This will move stock from source to destination. This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Yes, Complete',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: function() {
                return fetch('/inventory/stock-out/' + id + '/complete', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        return data;
                    } else {
                        throw new Error(data.message || 'Something went wrong');
                    }
                })
                .catch(function(error) {
                    Swal.showValidationMessage('Request failed: ' + error.message);
                });
            }
        }).then(function(result) {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Transfer Completed!',
                    text: 'Stock has been moved successfully.',
                    icon: 'success',
                    confirmButtonColor: '#4361ee'
                }).then(function() {
                    window.location.reload();
                });
            }
        });
    }

    // ============================================
    // CANCEL TRANSACTION
    // ============================================
    function cancelTransaction(id) {
        Swal.fire({
            title: 'Cancel Transaction?',
            text: 'This action cannot be undone.',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Cancel',
            cancelButtonText: 'No, Keep',
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: function() {
                return fetch('/inventory/stock-out/' + id + '/cancel', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        return data;
                    } else {
                        throw new Error(data.message || 'Something went wrong');
                    }
                })
                .catch(function(error) {
                    Swal.showValidationMessage('Request failed: ' + error.message);
                });
            }
        }).then(function(result) {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Cancelled!',
                    text: 'Transaction has been cancelled.',
                    icon: 'success',
                    confirmButtonColor: '#4361ee'
                }).then(function() {
                    window.location.reload();
                });
            }
        });
    }

    // ============================================
    // AUTO-CALCULATE REFUND MAX
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        var refundAmount = document.querySelector('input[name="refund_amount"]');
        if (refundAmount) {
            refundAmount.addEventListener('input', function() {
                var max = parseFloat(this.getAttribute('max')) || 0;
                var val = parseFloat(this.value) || 0;
                if (val > max) {
                    this.value = max.toFixed(2);
                }
            });
        }
    });

    console.log('✅ Transaction Details Loaded');
    console.log('📌 Transaction: {{ $transaction->stock_out_code }}');
    console.log('📊 Type: {{ ucfirst($transaction->type) }}');
    console.log('💰 Amount: ₹{{ number_format($transaction->total_amount ?? 0, 2) }}');
</script>

@endsection