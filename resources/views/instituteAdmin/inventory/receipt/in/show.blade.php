@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    .receipt-header {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        margin-bottom: 1.5rem;
    }
    .receipt-detail {
        padding: 0.75rem 0;
        border-bottom: 1px solid #e2e8f0;
    }
    .receipt-detail:last-child {
        border-bottom: none;
    }
    .status-badge {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .stat-box {
        background: #f8fafc;
        padding: 1rem;
        border-radius: 12px;
        text-align: center;
        border: 1px solid #e2e8f0;
    }
    .stat-box .number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #4361ee;
    }
    .stat-box .label {
        font-size: 0.8rem;
        color: #64748b;
    }
    .timeline {
        position: relative;
        padding-left: 20px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 6px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e2e8f0;
    }
    .timeline-item {
        position: relative;
        padding: 0.5rem 0 0.5rem 1.5rem;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -2px;
        top: 0.9rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #4361ee;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #4361ee;
    }
    .timeline-item.completed::before {
        background: #10b981;
        box-shadow: 0 0 0 2px #10b981;
    }
    .timeline-item.cancelled::before {
        background: #ef4444;
        box-shadow: 0 0 0 2px #ef4444;
    }
    .timeline-item.draft::before {
        background: #94a3b8;
        box-shadow: 0 0 0 2px #94a3b8;
    }
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-receipt"></i> In Receipt #{{ $receipt->receipt_number }}</h4>
        <div>
            <a href="{{ route('inventory.receipts.in.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            @if($receipt->status == 'COMPLETED')
                <a href="{{ route('inventory.receipts.in.print', $receipt->id) }}" 
                   class="btn btn-primary btn-sm" target="_blank">
                    <i class="fas fa-print"></i> Print
                </a>
            @endif
            @if($receipt->status == 'DRAFT')
                <a href="{{ route('inventory.receipts.in.edit', $receipt->id) }}" 
                   class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
            @endif
        </div>
    </div>

    <!-- Receipt Header -->
    <div class="receipt-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-1">{{ $receipt->receipt_number }}</h5>
                <small class="text-muted">Created: {{ $receipt->created_at->format('d M Y, h:i A') }}</small>
                <br>
                <small class="text-muted">Created By: {{ $receipt->creator->name ?? 'System' }}</small>
                <br>
                <small class="text-muted">Type: {{ $receipt->receipt_type_text }}</small>
            </div>
            <div class="col-md-6 text-end">
                <span class="badge {{ $receipt->status_badge }} status-badge">
                    {{ $receipt->status_text }}
                </span>
                
                @if($receipt->status == 'DRAFT')
                    <form method="POST" action="{{ route('inventory.receipts.in.approve', $receipt->id) }}" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm ms-2" onclick="return confirm('Approve this receipt?')">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>
                @endif

                @if($receipt->status == 'APPROVED')
                    <form method="POST" action="{{ route('inventory.receipts.in.complete', $receipt->id) }}" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm ms-2" onclick="return confirm('Complete this receipt? This will update stock.')">
                            <i class="fas fa-check-double"></i> Complete
                        </button>
                    </form>
                @endif

                @if(in_array($receipt->status, ['DRAFT', 'APPROVED']))
                    <form method="POST" action="{{ route('inventory.receipts.in.cancel', $receipt->id) }}" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm ms-2" onclick="return confirm('Cancel this receipt?')">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-box">
                <div class="number">{{ number_format($receipt->quantity, 2) }}</div>
                <div class="label">Quantity</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <div class="number">₹{{ number_format($receipt->unit_price, 2) }}</div>
                <div class="label">Unit Price</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <div class="number">₹{{ number_format($receipt->total_price, 2) }}</div>
                <div class="label">Total Price</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <div class="number">
                    @php
                        $currentStock = $receipt->item->current_stock ?? 0;
                        if ($receipt->status == 'COMPLETED') {
                            $currentStock = $receipt->item->current_stock ?? 0;
                        }
                    @endphp
                    {{ number_format($currentStock, 2) }}
                </div>
                <div class="label">Current Stock</div>
            </div>
        </div>
    </div>

    <!-- Details -->
    <div class="card">
        <div class="card-body">
            <div class="info-grid">
                <!-- Left Column -->
                <div>
                    <h6><i class="fas fa-box"></i> Item Details</h6>
                    <hr>
                    <div class="receipt-detail">
                        <strong>Item:</strong>
                        <span>{{ $receipt->item->item_name ?? 'N/A' }}</span>
                        <br>
                        <small class="text-muted">Code: {{ $receipt->item->item_code ?? 'N/A' }}</small>
                        <br>
                        <small class="text-muted">SKU: {{ $receipt->item->sku ?? 'N/A' }}</small>
                    </div>

                    <div class="receipt-detail">
                        <strong>Category:</strong>
                        <span>{{ $receipt->item->category->category_name ?? 'N/A' }}</span>
                        <br>
                        <small class="text-muted">{{ $receipt->item->category->category_code ?? '' }}</small>
                    </div>

                    <div class="receipt-detail">
                        <strong>Unit:</strong>
                        <span>{{ $receipt->item->unit->unit_name ?? 'N/A' }}</span>
                    </div>

                    @if(optional($receipt->item)->batch_number)
                        <div class="receipt-detail">
                            <strong>Batch Number:</strong>
                            <span class="badge bg-secondary">
                                {{ optional($receipt->item)->batch_number }}
                            </span>
                        </div>
                    @endif

                    @if(optional($receipt->item)->serial_number)
                        <div class="receipt-detail">
                            <strong>Serial Number:</strong>
                            <span class="badge bg-secondary">
                                {{ optional($receipt->item)->serial_number }}
                            </span>
                        </div>
                    @endif

                    @if(optional($receipt->item)->expiry_date)
                        <div class="receipt-detail">
                            <strong>Expiry Date:</strong>
                            <span>{{ optional($receipt->item)->expiry_date->format('d M Y') }}</span>

                            <span class="badge {{ optional($receipt->item)->shelf_life_badge }} ms-2">
                                {{ optional($receipt->item)->shelf_life_status }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div>
                    <h6><i class="fas fa-warehouse"></i> Receipt Details</h6>
                    <hr>
                    <div class="receipt-detail">
                        <strong>Receipt Number:</strong>
                        <span class="badge bg-secondary">{{ $receipt->receipt_number }}</span>
                    </div>

                    <div class="receipt-detail">
                        <strong>Receipt Type:</strong>
                        <span class="badge bg-info">{{ $receipt->receipt_type_text }}</span>
                    </div>

                    <div class="receipt-detail">
                        <strong>Warehouse:</strong>
                        <span>{{ $receipt->warehouse->warehouse_name ?? 'N/A' }}</span>
                        <br>
                        <small class="text-muted">Code: {{ $receipt->warehouse->warehouse_code ?? 'N/A' }}</small>
                    </div>

                    @if($receipt->supplier_name)
                    <div class="receipt-detail">
                        <strong>Supplier:</strong>
                        <span>{{ $receipt->supplier_name }}</span>
                    </div>
                    @endif

                    @if($receipt->supplier_invoice_number)
                    <div class="receipt-detail">
                        <strong>Invoice Number:</strong>
                        <span>{{ $receipt->supplier_invoice_number }}</span>
                    </div>
                    @endif

                    @if($receipt->approved_by)
                    <div class="receipt-detail">
                        <strong>Approved By:</strong>
                        <span>{{ $receipt->approver->name ?? 'N/A' }}</span>
                        <br>
                        <small class="text-muted">{{ $receipt->approved_at ? $receipt->approved_at->format('d M Y, h:i A') : '' }}</small>
                    </div>
                    @endif

                    @if($receipt->received_by)
                    <div class="receipt-detail">
                        <strong>Received By:</strong>
                        <span>{{ $receipt->receiver->name ?? 'N/A' }}</span>
                    </div>
                    @endif

                    @if($receipt->notes)
                    <div class="receipt-detail">
                        <strong>Notes:</strong>
                        <p class="mt-1 mb-0">{{ $receipt->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <hr>
                    <h6><i class="fas fa-clock"></i> Status Timeline</h6>
                    <div class="timeline">
                        <div class="timeline-item draft">
                            <strong>Draft</strong>
                            <br>
                            <small class="text-muted">{{ $receipt->created_at->format('d M Y, h:i A') }}</small>
                            <br>
                            <small class="text-muted">Created by {{ $receipt->creator->name ?? 'System' }}</small>
                        </div>

                        @if($receipt->approved_at)
                        <div class="timeline-item completed">
                            <strong>Approved</strong>
                            <br>
                            <small class="text-muted">{{ $receipt->approved_at->format('d M Y, h:i A') }}</small>
                            <br>
                            <small class="text-muted">Approved by {{ $receipt->approver->name ?? 'System' }}</small>
                        </div>
                        @endif

                        @if($receipt->status == 'COMPLETED')
                        <div class="timeline-item completed">
                            <strong>Completed</strong>
                            <br>
                            <small class="text-muted">{{ $receipt->updated_at->format('d M Y, h:i A') }}</small>
                            <br>
                            <small class="text-muted">Stock updated</small>
                        </div>
                        @endif

                        @if($receipt->status == 'CANCELLED')
                        <div class="timeline-item cancelled">
                            <strong>Cancelled</strong>
                            <br>
                            <small class="text-muted">{{ $receipt->updated_at->format('d M Y, h:i A') }}</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Other Warehouses -->
            @if(isset($otherWarehouses) && $otherWarehouses->count() > 0)
            <div class="row mt-4">
                <div class="col-md-12">
                    <hr>
                    <h6><i class="fas fa-warehouse"></i> Stock in Other Warehouses</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Warehouse</th>
                                    <th>Current Stock</th>
                                    <th>Available Stock</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($otherWarehouses as $stock)
                                <tr>
                                    <td>{{ $stock->warehouse->warehouse_name ?? 'N/A' }}</td>
                                    <td>{{ number_format($stock->current_stock, 2) }}</td>
                                    <td>{{ number_format($stock->available_stock, 2) }}</td>
                                    <td>
                                        @if($stock->available_stock > 0)
                                            <span class="badge bg-success">Available</span>
                                        @else
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Receipt History -->
            @if(isset($receiptHistory) && $receiptHistory->count() > 0)
            <div class="row mt-4">
                <div class="col-md-12">
                    <hr>
                    <h6><i class="fas fa-history"></i> Receipt History for this Item</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Receipt #</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Warehouse</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($receiptHistory as $history)
                                <tr>
                                    <td>
                                        <a href="{{ route('inventory.receipts.in.show', $history->id) }}">
                                            {{ $history->receipt_number }}
                                        </a>
                                    </td>
                                    <td>{{ $history->receipt_type_text }}</td>
                                    <td>{{ number_format($history->quantity, 2) }}</td>
                                    <td>{{ $history->warehouse->warehouse_name ?? 'N/A' }}</td>
                                    <td>{{ $history->created_at->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge {{ $history->status_badge }}">
                                            {{ $history->status_text }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Auto-refresh status if pending
    @if(in_array($receipt->status, ['DRAFT', 'APPROVED']))
        setTimeout(function() {
            location.reload();
        }, 30000); // Refresh every 30 seconds
    @endif
</script>

@endsection