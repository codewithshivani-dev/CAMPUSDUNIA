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
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-receipt"></i> Out Receipt #{{ $receipt->receipt_number }}</h4>
        <div>
            <a href="{{ route('inventory.receipts.out.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            @if($receipt->status == 'COMPLETED')
                <a href="{{ route('inventory.receipts.out.print', $receipt->id) }}" 
                   class="btn btn-primary" target="_blank">
                    <i class="fas fa-print"></i> Print
                </a>
            @endif
        </div>
    </div>

    <!-- Status and Actions -->
    <div class="receipt-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-1">{{ $receipt->receipt_number }}</h5>
                <small class="text-muted">Created: {{ $receipt->created_at->format('d M Y, h:i A') }}</small>
                <br>
                <small class="text-muted">Created By: {{ $receipt->creator->name ?? 'System' }}</small>
            </div>
            <div class="col-md-6 text-end">
                <span class="badge {{ $receipt->status_badge }} status-badge">
                    {{ $receipt->status_text }}
                </span>
                
                @if($receipt->status == 'DRAFT')
                    <form method="POST" action="{{ route('inventory.receipts.out.approve', $receipt->id) }}" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm ms-2" onclick="return confirm('Approve this receipt?')">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>
                @endif

                @if($receipt->status == 'APPROVED')
                    <form method="POST" action="{{ route('inventory.receipts.out.complete', $receipt->id) }}" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm ms-2" onclick="return confirm('Complete this receipt? This will update stock.')">
                            <i class="fas fa-check-double"></i> Complete
                        </button>
                    </form>
                @endif

                @if(in_array($receipt->status, ['DRAFT', 'APPROVED']))
                    <form method="POST" action="{{ route('inventory.receipts.out.cancel', $receipt->id) }}" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm ms-2" onclick="return confirm('Cancel this receipt?')">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </form>
                @endif

                @if($receipt->status == 'DRAFT')
                    <a href="{{ route('inventory.receipts.out.edit', $receipt->id) }}" 
                       class="btn btn-warning btn-sm ms-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Details -->
    <div class="card">
        <div class="card-body">
            <div class="info-grid">
                {{-- Left Column --}}
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

                    <div class="receipt-detail">
                        <strong>Quantity:</strong>
                        <span class="fw-bold">{{ number_format($receipt->quantity, 2) }}</span>
                    </div>

                    <div class="receipt-detail">
                        <strong>Unit Price:</strong>
                        <span>₹{{ number_format($receipt->unit_price, 2) }}</span>
                    </div>

                    <div class="receipt-detail">
                        <strong>Total Price:</strong>
                        <span class="fw-bold text-primary">₹{{ number_format($receipt->total_price, 2) }}</span>
                    </div>

                    <div class="receipt-detail">
                        <strong>Current Stock After:</strong>
                        @php
                            $remainingStock = ($receipt->item->current_stock ?? 0);
                            if ($receipt->status == 'COMPLETED') {
                                $remainingStock = ($receipt->item->current_stock ?? 0);
                            } else {
                                $remainingStock = ($receipt->item->current_stock ?? 0) - $receipt->quantity;
                            }
                        @endphp
                        <span class="badge bg-{{ $remainingStock > 0 ? 'success' : 'danger' }}">
                            {{ number_format($remainingStock, 2) }}
                        </span>
                    </div>
                </div>

                {{-- Right Column --}}
                <div>
                    <h6><i class="fas fa-warehouse"></i> Warehouse & Receipt Details</h6>
                    <hr>
                    <div class="receipt-detail">
                        <strong>Receipt Type:</strong>
                        <span class="badge bg-info">{{ $receipt->receipt_type_text }}</span>
                    </div>

                    <div class="receipt-detail">
                        <strong>From Warehouse:</strong>
                        <span>{{ $receipt->warehouse->warehouse_name ?? 'N/A' }}</span>
                        <br>
                        <small class="text-muted">Code: {{ $receipt->warehouse->warehouse_code ?? 'N/A' }}</small>
                        <br>
                        <small class="text-muted">Address: {{ $receipt->warehouse->address ?? 'N/A' }}</small>
                    </div>

                    @if($receipt->issued_to)
                    <div class="receipt-detail">
                        <strong>Issued To:</strong>
                        <span>{{ $receipt->issued_to }}</span>
                    </div>
                    @endif

                    @if($receipt->issued_by)
                    <div class="receipt-detail">
                        <strong>Issued By:</strong>
                        <span>{{ $receipt->issued_by }}</span>
                    </div>
                    @endif

                    @if($receipt->purpose)
                    <div class="receipt-detail">
                        <strong>Purpose:</strong>
                        <span>{{ $receipt->purpose }}</span>
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

                    @if($receipt->notes)
                    <div class="receipt-detail">
                        <strong>Notes:</strong>
                        <p class="mt-1 mb-0">{{ $receipt->notes }}</p>
                    </div>
                    @endif

                    <div class="receipt-detail">
                        <strong>Status History:</strong>
                        <br>
                        <span class="badge bg-secondary">Draft</span>
                        @if($receipt->approved_at)
                            <i class="fas fa-arrow-right text-muted"></i>
                            <span class="badge bg-warning">Approved</span>
                        @endif
                        @if($receipt->status == 'COMPLETED')
                            <i class="fas fa-arrow-right text-muted"></i>
                            <span class="badge bg-success">Completed</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Additional Info Row --}}
            @if($receipt->item->expiry_date || $receipt->item->batch_number)
            <div class="row mt-3">
                <div class="col-md-12">
                    <hr>
                    <h6><i class="fas fa-clock"></i> Additional Item Information</h6>
                    <div class="row">
                        @if($receipt->item->expiry_date)
                        <div class="col-md-3">
                            <strong>Expiry Date:</strong>
                            <span>{{ $receipt->item->expiry_date->format('d M Y') }}</span>
                            <span class="badge {{ $receipt->item->shelf_life_badge }} ms-2">
                                {{ $receipt->item->shelf_life_status }}
                            </span>
                        </div>
                        @endif
                        @if($receipt->item->batch_number)
                        <div class="col-md-3">
                            <strong>Batch Number:</strong>
                            <span class="badge bg-secondary">{{ $receipt->item->batch_number }}</span>
                        </div>
                        @endif
                        @if($receipt->item->serial_number)
                        <div class="col-md-3">
                            <strong>Serial Number:</strong>
                            <span class="badge bg-secondary">{{ $receipt->item->serial_number }}</span>
                        </div>
                        @endif
                        @if($receipt->item->rack_number)
                        <div class="col-md-3">
                            <strong>Location:</strong>
                            <span>Rack {{ $receipt->item->rack_number }}, Shelf {{ $receipt->item->shelf_number }}, Bin {{ $receipt->item->bin_number }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection