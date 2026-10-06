{{-- resources/views/instituteAdmin/Inventory/stock-out/show.blade.php --}}

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

    .status-badge {
        padding: 0.3rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-block;
    }

    .status-badge.pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-badge.approved {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-badge.completed {
        background: #d1fae5;
        color: #065f46;
    }

    .status-badge.cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .detail-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
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
        gap: 8px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }

    .detail-item {
        padding: 0.5rem;
    }

    .detail-item .label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-item .value {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-top: 0.2rem;
    }

    .detail-item .value .sub-text {
        font-weight: 400;
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
    }

    .items-table th {
        background: #f8fafc;
        padding: 0.75rem 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.8rem;
        color: var(--text-muted);
        text-transform: uppercase;
        border-bottom: 2px solid var(--border-color);
    }

    .items-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--border-color);
        font-size: 0.9rem;
    }

    .items-table tr:last-child td {
        border-bottom: none;
    }

    .items-table .total-row td {
        font-weight: 700;
        border-top: 2px solid var(--text-dark);
        padding-top: 1rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 0.5rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        border: none;
        transition: var(--transition);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .btn-approve {
        background: var(--primary-color);
        color: white;
    }

    .btn-approve:hover {
        background: var(--primary-dark);
    }

    .btn-edit {
        background: #fef3c7;
        color: #92400e;
    }

    .btn-edit:hover {
        background: #fde68a;
    }

    .btn-print {
        background: #e2e8f0;
        color: var(--text-dark);
    }

    .btn-print:hover {
        background: #cbd5e1;
    }

    .btn-delete {
        background: var(--danger-color);
        color: white;
    }

    .btn-delete:hover {
        background: #dc2626;
    }

    .btn-back-list {
        background: #f1f5f9;
        color: var(--text-dark);
        border: 2px solid var(--border-color);
    }

    .btn-back-list:hover {
        background: #e2e8f0;
    }

    .location-transfer {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 0;
    }

    .location-transfer .arrow {
        color: var(--primary-color);
        font-size: 1.2rem;
    }

    .payment-details {
        background: #f0fdf4;
        border: 2px solid #bbf7d0;
        border-radius: 12px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .payment-details .payment-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.75rem;
    }

    .payment-details .payment-item .label {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
    }

    .payment-details .payment-item .value {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-dark);
    }

    .payment-details .payment-item .value.amount {
        color: var(--success-color);
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .items-table {
            font-size: 0.8rem;
        }

        .items-table th,
        .items-table td {
            padding: 0.5rem;
        }

        .action-buttons {
            justify-content: center;
        }
    }

    @media print {
        .no-print {
            display: none !important;
        }
        .page-header {
            background: #f8fafc !important;
            color: var(--text-dark) !important;
            box-shadow: none !important;
        }
        .page-header h1 i {
            background: #e2e8f0 !important;
            color: var(--text-dark) !important;
        }
        .btn-back {
            display: none !important;
        }
        .detail-card {
            box-shadow: none !important;
            border-color: #d1d5db !important;
        }
        .status-badge {
            border: 1px solid #d1d5db !important;
        }
        .status-badge.pending { background: #fef3c7 !important; }
        .status-badge.approved { background: #dbeafe !important; }
        .status-badge.completed { background: #d1fae5 !important; }
        .status-badge.cancelled { background: #fee2e2 !important; }
    }

    .timeline-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color);
        position: relative;
    }

    .timeline-item:last-child {
        border-bottom: none;
    }

    .timeline-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        flex-shrink: 0;
        margin-top: 4px;
        border: 2px solid white;
        box-shadow: 0 0 0 2px var(--border-color);
    }

    .timeline-dot.created { background: var(--success-color); }
    .timeline-dot.approved { background: var(--primary-color); }
    .timeline-dot.completed { background: var(--success-color); }
    .timeline-dot.cancelled { background: var(--danger-color); }

    .timeline-content {
        flex: 1;
    }

    .timeline-content .title {
        font-weight: 600;
        color: var(--text-dark);
    }

    .timeline-content .meta {
        font-size: 0.85rem;
        color: var(--text-muted);
    }
</style>

<!-- ============================================ -->
<!-- PAGE HEADER -->
<!-- ============================================ -->
<div class="page-header no-print">
    <div>
        <h1><i class="fas fa-eye"></i> Stock Out Details</h1>
        <p>View complete details of stock out #{{ $stockOut->stock_out_code }}</p>
    </div>
    <div class="action-buttons">
        <a href="{{ route('inventory.stock-out.index') }}" class="btn-action btn-back-list">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        @if($stockOut->status === 'pending')
            <a href="{{ route('inventory.stock-out.edit', $stockOut->id) }}" class="btn-action btn-edit">
                <i class="fas fa-edit"></i> Edit
            </a>
            <button type="button" class="btn-action btn-approve" onclick="confirmApprove({{ $stockOut->id }}, '{{ $stockOut->type }}')">
                <i class="fas fa-check"></i> Approve
            </button>
            <button type="button" class="btn-action btn-delete" onclick="confirmDelete({{ $stockOut->id }})">
                <i class="fas fa-trash"></i> Delete
            </button>
        @endif
        @if($stockOut->status === 'approved' && $stockOut->type === 'transfer')
            <button type="button" class="btn-action btn-approve" onclick="confirmComplete({{ $stockOut->id }})">
                <i class="fas fa-check-double"></i> Complete Transfer
            </button>
        @endif
        <a href="{{ route('inventory.stock-out.print', $stockOut->id) }}" class="btn-action btn-print" target="_blank">
            <i class="fas fa-print"></i> Print
        </a>
    </div>
</div>

<!-- ============================================ -->
<!-- STOCK OUT INFORMATION -->
<!-- ============================================ -->
<div class="detail-card">
    <div class="card-title">
        <i class="fas fa-info-circle"></i> Stock Out Information
    </div>
    <div class="detail-grid">
        <div class="detail-item">
            <div class="label">ID</div>
            <div class="value">#{{ $stockOut->id }}</div>
        </div>
        <div class="detail-item">
            <div class="label">Stock Out Code</div>
            <div class="value"><strong>{{ $stockOut->stock_out_code }}</strong></div>
        </div>
        <div class="detail-item">
            <div class="label">Type</div>
            <div class="value">
                @if($stockOut->type === 'sell')
                    <i class="fas fa-shopping-cart text-success"></i> Sale
                @else
                    <i class="fas fa-exchange-alt text-primary"></i> Transfer
                @endif
                @if($stockOut->sub_type)
                    <span class="sub-text">({{ ucfirst(str_replace('_', ' ', $stockOut->sub_type)) }})</span>
                @endif
            </div>
        </div>
        <div class="detail-item">
            <div class="label">Status</div>
            <div class="value">
                <span class="status-badge {{ $stockOut->status }}">
                    {{ ucfirst($stockOut->status) }}
                </span>
            </div>
        </div>
        <div class="detail-item">
            <div class="label">Created By</div>
            <div class="value">{{ $stockOut->creator->name ?? 'N/A' }}</div>
        </div>
        <div class="detail-item">
            <div class="label">Created At</div>
            <div class="value">{{ $stockOut->created_at->format('d M Y, h:i A') }}</div>
        </div>
        @if($stockOut->approved_by)
        <div class="detail-item">
            <div class="label">Approved By</div>
            <div class="value">{{ $stockOut->approver->name ?? 'N/A' }}</div>
        </div>
        <div class="detail-item">
            <div class="label">Approved At</div>
            <div class="value">{{ $stockOut->approved_at ? \Carbon\Carbon::parse($stockOut->approved_at)->format('d M Y, h:i A') : 'N/A' }}</div>
        </div>
        @endif
        @if($stockOut->received_by)
        <div class="detail-item">
            <div class="label">Received By</div>
            <div class="value">{{ $stockOut->receiver->name ?? 'N/A' }}</div>
        </div>
        <div class="detail-item">
            <div class="label">Received At</div>
            <div class="value">{{ $stockOut->received_at ? \Carbon\Carbon::parse($stockOut->received_at)->format('d M Y, h:i A') : 'N/A' }}</div>
        </div>
        @endif
        @if($stockOut->expected_arrival_date)
        <div class="detail-item">
            <div class="label">Expected Arrival</div>
            <div class="value">{{ \Carbon\Carbon::parse($stockOut->expected_arrival_date)->format('d M Y') }}</div>
        </div>
        @endif
        @if($stockOut->reason)
        <div class="detail-item" style="grid-column: 1 / -1;">
            <div class="label">Reason</div>
            <div class="value">{{ $stockOut->reason }}</div>
        </div>
        @endif
        @if($stockOut->notes)
        <div class="detail-item" style="grid-column: 1 / -1;">
            <div class="label">Notes</div>
            <div class="value">{{ $stockOut->notes }}</div>
        </div>
        @endif
    </div>
</div>

<!-- ============================================ -->
<!-- LOCATION INFORMATION -->
<!-- ============================================ -->
<div class="detail-card">
    <div class="card-title">
        <i class="fas fa-map-marker-alt"></i> Location Information
    </div>
    <div class="detail-grid">
        <div class="detail-item">
            <div class="label">From Location</div>
            <div class="value">
                @if($stockOut->fromWarehouse)
                    <div>
                        <i class="fas fa-warehouse text-primary"></i> 
                        <strong>{{ $stockOut->fromWarehouse->warehouse_name }}</strong>
                        <span class="sub-text">({{ $stockOut->fromWarehouse->warehouse_code }})</span>
                    </div>
                @elseif($stockOut->fromStore)
                    <div>
                        <i class="fas fa-store text-warning"></i> 
                        <strong>{{ $stockOut->fromStore->store_name }}</strong>
                        <span class="sub-text">({{ $stockOut->fromStore->store_code }})</span>
                    </div>
                @else
                    N/A
                @endif
            </div>
        </div>

        <div class="detail-item">
            <div class="label">To Location</div>
            <div class="value">
                @if($stockOut->toWarehouse)
                    <div>
                        <i class="fas fa-warehouse text-success"></i> 
                        <strong>{{ $stockOut->toWarehouse->warehouse_name }}</strong>
                        <span class="sub-text">({{ $stockOut->toWarehouse->warehouse_code }})</span>
                    </div>
                @elseif($stockOut->toStore)
                    <div>
                        <i class="fas fa-store text-success"></i> 
                        <strong>{{ $stockOut->toStore->store_name }}</strong>
                        <span class="sub-text">({{ $stockOut->toStore->store_code }})</span>
                    </div>
                @else
                    N/A
                @endif
            </div>
        </div>

        @if($stockOut->type === 'transfer')
        <div class="detail-item" style="grid-column: 1 / -1;">
            <div class="label">Transfer Path</div>
            <div class="value">
                <div class="location-transfer">
                    <div>
                        @if($stockOut->fromWarehouse)
                            <i class="fas fa-warehouse"></i> {{ $stockOut->fromWarehouse->warehouse_name }}
                        @elseif($stockOut->fromStore)
                            <i class="fas fa-store"></i> {{ $stockOut->fromStore->store_name }}
                        @else
                            N/A
                        @endif
                    </div>
                    <div class="arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <div>
                        @if($stockOut->toWarehouse)
                            <i class="fas fa-warehouse"></i> {{ $stockOut->toWarehouse->warehouse_name }}
                        @elseif($stockOut->toStore)
                            <i class="fas fa-store"></i> {{ $stockOut->toStore->store_name }}
                        @else
                            N/A
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- ============================================ -->
<!-- ITEMS -->
<!-- ============================================ -->
<div class="detail-card">
    <div class="card-title">
        <i class="fas fa-boxes"></i> Items
        <span class="badge bg-primary ms-2">
            {{ count($stockOut->items ?? []) }}
        </span>
    </div>
    <div style="overflow-x: auto;">
        <table class="items-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th style="text-align: center;">Quantity</th>
                    @if($stockOut->type === 'sell')
                    <th style="text-align: right;">Unit Price</th>
                    <th style="text-align: right;">Total</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php
                    $items = $stockOut->items ?? [];
                    $grandTotal = 0;
                @endphp
                @forelse($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <span class="badge bg-secondary">{{ $item['code'] ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <strong>{{ $item['name'] ?? 'Unknown Item' }}</strong>
                    </td>
                    <td style="text-align: center;">
                        <span class="fw-bold">{{ $item['quantity'] ?? 0 }}</span>
                    </td>
                    @if($stockOut->type === 'sell')
                    <td style="text-align: right;">
                        {{ isset($item['price']) ? '₹ ' . number_format($item['price'], 2) : 'N/A' }}
                    </td>
                    <td style="text-align: right;">
                        @php
                            $total = ($item['quantity'] ?? 0) * ($item['price'] ?? 0);
                            $grandTotal += $total;
                        @endphp
                        <span class="fw-bold text-success">₹ {{ number_format($total, 2) }}</span>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $stockOut->type === 'sell' ? 6 : 4 }}" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                        <i class="fas fa-box-open" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                        No items found in this stock out.
                    </td>
                </tr>
                @endforelse
                @if($stockOut->type === 'sell' && count($items) > 0)
                <tr class="total-row">
                    <td colspan="5" style="text-align: right; font-size: 1.1rem;">
                        Grand Total
                    </td>
                    <td style="text-align: right; font-size: 1.1rem; color: var(--success-color);">
                        ₹ {{ number_format($grandTotal, 2) }}
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================ -->
<!-- FINANCIAL DETAILS (for Sell) -->
<!-- ============================================ -->
@if($stockOut->type === 'sell')
<div class="detail-card">
    <div class="card-title">
        <i class="fas fa-money-bill-wave"></i> Financial Details
    </div>
    <div class="payment-details">
        <div class="payment-grid">
            <div class="payment-item">
                <div class="label">Subtotal</div>
                <div class="value amount">₹ {{ number_format($stockOut->subtotal ?? 0, 2) }}</div>
            </div>
            <div class="payment-item">
                <div class="label">Discount</div>
                <div class="value amount" style="color: var(--danger-color);">- ₹ {{ number_format($stockOut->discount_amount ?? 0, 2) }}</div>
            </div>
            <div class="payment-item">
                <div class="label">Tax</div>
                <div class="value amount">+ ₹ {{ number_format($stockOut->tax_amount ?? 0, 2) }}</div>
            </div>
            <div class="payment-item">
                <div class="label">Total Amount</div>
                <div class="value amount" style="font-size: 1.2rem;">₹ {{ number_format($stockOut->total_amount ?? 0, 2) }}</div>
            </div>
            <div class="payment-item">
                <div class="label">Amount Received</div>
                <div class="value amount">₹ {{ number_format($stockOut->amount_received ?? 0, 2) }}</div>
            </div>
            <div class="payment-item">
                <div class="label">Payment Method</div>
                <div class="value">{{ ucfirst(str_replace('_', ' ', $stockOut->payment_method ?? 'N/A')) }}</div>
            </div>
            <div class="payment-item">
                <div class="label">Payment Status</div>
                <div class="value">
                    <span class="status-badge {{ $stockOut->payment_status ?? 'pending' }}">
                        {{ ucfirst($stockOut->payment_status ?? 'Pending') }}
                    </span>
                </div>
            </div>
            @if($stockOut->transaction_id)
            <div class="payment-item">
                <div class="label">Transaction ID</div>
                <div class="value">{{ $stockOut->transaction_id }}</div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- CUSTOMER INFORMATION (for Sell) -->
<!-- ============================================ -->
<div class="detail-card">
    <div class="card-title">
        <i class="fas fa-user"></i> Customer Information
    </div>
    <div class="detail-grid">
        <div class="detail-item">
            <div class="label">Customer Name</div>
            <div class="value">{{ $stockOut->customer_name ?? 'N/A' }}</div>
        </div>
        <div class="detail-item">
            <div class="label">Phone</div>
            <div class="value">{{ $stockOut->customer_phone ?? 'N/A' }}</div>
        </div>
        <div class="detail-item">
            <div class="label">Email</div>
            <div class="value">{{ $stockOut->customer_email ?? 'N/A' }}</div>
        </div>
        <div class="detail-item">
            <div class="label">Customer Type</div>
            <div class="value">{{ ucfirst($stockOut->customer_type ?? 'N/A') }}</div>
        </div>
        @if($stockOut->customer_address)
        <div class="detail-item" style="grid-column: 1 / -1;">
            <div class="label">Address</div>
            <div class="value">{{ $stockOut->customer_address }}</div>
        </div>
        @endif
    </div>
</div>
@endif

<!-- ============================================ -->
<!-- TIMELINE -->
<!-- ============================================ -->
<div class="detail-card">
    <div class="card-title">
        <i class="fas fa-history"></i> Timeline
    </div>
    <div style="padding: 0.5rem 0;">
        <div class="timeline-item">
            <div class="timeline-dot created"></div>
            <div class="timeline-content">
                <div class="title">Created</div>
                <div class="meta">
                    {{ $stockOut->created_at->format('d M Y, h:i A') }} by {{ $stockOut->creator->name ?? 'N/A' }}
                </div>
            </div>
        </div>

        @if($stockOut->approved_at)
        <div class="timeline-item">
            <div class="timeline-dot approved"></div>
            <div class="timeline-content">
                <div class="title">Approved</div>
                <div class="meta">
                    {{ \Carbon\Carbon::parse($stockOut->approved_at)->format('d M Y, h:i A') }} by {{ $stockOut->approver->name ?? 'N/A' }}
                </div>
            </div>
        </div>
        @endif

        @if($stockOut->received_at)
        <div class="timeline-item">
            <div class="timeline-dot completed"></div>
            <div class="timeline-content">
                <div class="title">{{ $stockOut->type === 'sell' ? 'Completed' : 'Received' }}</div>
                <div class="meta">
                    {{ \Carbon\Carbon::parse($stockOut->received_at)->format('d M Y, h:i A') }} by {{ $stockOut->receiver->name ?? 'N/A' }}
                </div>
            </div>
        </div>
        @endif

        @if($stockOut->status === 'cancelled')
        <div class="timeline-item">
            <div class="timeline-dot cancelled"></div>
            <div class="timeline-content">
                <div class="title">Cancelled</div>
                <div class="meta">
                    {{ $stockOut->updated_at->format('d M Y, h:i A') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    /**
     * Confirm Approve - For both Sale and Transfer
     * For Transfer: Approve + Auto Complete
    */
    function confirmApprove(id, type) {
        let title, text, confirmText, url;
        
        if (type === 'sell') {
            title = 'Approve Sale?';
            text = 'This will reduce stock from the source location and mark the sale as completed.';
            confirmText = 'Yes, Approve Sale';
            url = `/inventory/stock-out/${id}/approve`;
        } else {
            title = 'Approve & Complete Transfer?';
            text = 'This will:\n1. Approve the transfer\n2. Reduce stock from source location\n3. Add stock to destination location\n4. Mark as completed\n\n⚠️ This action cannot be undone!';
            confirmText = 'Yes, Complete Transfer';
            url = `/inventory/stock-out/${id}/approve`;
        }
        
        Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#ef4444',
            confirmButtonText: confirmText,
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(text || 'Network response was not ok');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        return data;
                    } else {
                        throw new Error(data.message || 'Something went wrong');
                    }
                })
                .catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error.message}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Success!',
                    text: result.value?.message || 'Operation completed successfully.',
                    icon: 'success',
                    confirmButtonColor: '#4361ee',
                }).then(() => {
                    window.location.reload();
                });
            }
        });
    }

    /**
     * Confirm Complete Transfer
    */
    function confirmComplete(id) {
        Swal.fire({
            title: 'Complete Transfer?',
            text: 'This will reduce stock from source and add it to destination. This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Yes, Complete Transfer',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(`/inventory/stock-out/${id}/complete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(text || 'Network response was not ok');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        return data;
                    } else {
                        throw new Error(data.message || 'Something went wrong');
                    }
                })
                .catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error.message}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Transfer Completed!',
                    text: result.value?.message || 'Stock has been moved successfully.',
                    icon: 'success',
                    confirmButtonColor: '#10b981',
                }).then(() => {
                    window.location.reload();
                });
            }
        });
    }
    
    /**
     * Confirm Delete
    */
    function confirmDelete(id) {
       Swal.fire({
           title: 'Delete Stock Out?',
           text: 'This action cannot be undone!',
           icon: 'error',
           showCancelButton: true,
           confirmButtonColor: '#ef4444',
           cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch('{{ route("inventory.stock-out.destroy", "") }}/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        return data;
                    } else {
                        throw new Error(data.message || 'Something went wrong');
                    }
                })
                .catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error.message}`);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleted!',
                    text: 'Stock Out has been deleted.',
                    icon: 'success',
                    confirmButtonColor: '#4361ee',
                }).then(() => {
                    window.location.href = '{{ route("inventory.stock-out.index") }}';
                });
            }
        });
    }
</script>
@endsection