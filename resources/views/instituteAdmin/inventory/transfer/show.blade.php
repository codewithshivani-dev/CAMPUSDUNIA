@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .page-header {
        background: var(--primary-gradient);
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

    .btn-action-header {
        background: white;
        color: #4361ee;
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

    .btn-action-header:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
        color: #4361ee;
    }

    .transfer-flow {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        background: #f8fafc;
        border-radius: 12px;
        margin: 1rem 0;
        border: 2px solid var(--border-color);
        flex-wrap: wrap;
        gap: 1rem;
    }

    .transfer-flow .warehouse-box {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-align: center;
        min-width: 150px;
    }

    .transfer-flow .warehouse-box.source {
        background: #fee2e2;
        color: #991b1b;
        border: 2px solid #fecaca;
    }

    .transfer-flow .warehouse-box.destination {
        background: #d1fae5;
        color: #065f46;
        border: 2px solid #a7f3d0;
    }

    .transfer-flow .arrow {
        font-size: 2rem;
        color: #94a3b8;
    }

    .transfer-flow .quantity-badge {
        background: #e0e7ff;
        color: #4338ca;
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
        font-size: 1rem;
        font-weight: 700;
    }

    .card-custom {
        background: white;
        border-radius: 20px;
        border: 2px solid var(--border-color);
        overflow: hidden;
        box-shadow: var(--card-shadow);
        margin-bottom: 1.5rem;
    }

    .card-custom .card-header {
        background: #f8fafc;
        padding: 1rem 1.5rem;
        border-bottom: 2px solid var(--border-color);
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .card-custom .card-header i {
        color: #4361ee;
        margin-right: 8px;
    }

    .card-custom .card-body {
        padding: 1.5rem;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .detail-item label {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 0.25rem;
    }

    .detail-item p {
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--text-dark);
        margin: 0;
    }

    .badge-status {
        display: inline-block;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-approved { background: #dbeafe; color: #1d4ed8; }
    .badge-completed { background: #d1fae5; color: #065f46; }
    .badge-cancelled { background: #fee2e2; color: #991b1b; }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-top: 1rem;
    }

    .btn-action {
        padding: 0.5rem 1.2rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.8rem;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .btn-approve { background: #fef3c7; color: #92400e; }
    .btn-approve:hover { background: #fde68a; }

    .btn-start { background: #d1fae5; color: #065f46; }
    .btn-start:hover { background: #a7f3d0; }

    .btn-cancel { background: #fee2e2; color: #991b1b; }
    .btn-cancel:hover { background: #fecaca; }

    .btn-print { background: #e0e7ff; color: #4338ca; }
    .btn-print:hover { background: #c7d2fe; }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .transfer-flow {
            flex-direction: column;
        }

        .transfer-flow .arrow {
            transform: rotate(90deg);
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            justify-content: center;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-exchange-alt"></i> Transfer #{{ $transfer->transfer_code }}</h1>
            <p>{{ $transfer->item->item_name ?? 'N/A' }} - {{ number_format($transfer->quantity, 2) }} units</p>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            @if($transfer->status == 'COMPLETED')
                <a href="{{ route('inventory.transfers.print', $transfer->id) }}" class="btn-action-header" target="_blank">
                    <i class="fas fa-print"></i> Print
                </a>
            @endif
            <a href="{{ route('inventory.transfers.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Transfer Flow -->
    <div class="card-custom">
        <div class="card-body">
            <div class="transfer-flow">
                <div class="warehouse-box source">
                    <i class="fas fa-arrow-right"></i> SOURCE
                    <br>
                    <strong>{{ $transfer->fromWarehouse->warehouse_name ?? 'N/A' }}</strong>
                    <br>
                    <small style="font-weight: normal; color: #666;">{{ $transfer->fromWarehouse->warehouse_code ?? '' }}</small>
                </div>
                <div class="arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>
                <div class="warehouse-box destination">
                    <i class="fas fa-arrow-left"></i> DESTINATION
                    <br>
                    <strong>{{ $transfer->toWarehouse->warehouse_name ?? 'N/A' }}</strong>
                    <br>
                    <small style="font-weight: normal; color: #666;">{{ $transfer->toWarehouse->warehouse_code ?? '' }}</small>
                </div>
                <div class="quantity-badge">
                    {{ number_format($transfer->quantity, 2) }} units
                </div>
            </div>

            <!-- Status -->
            <div style="text-align: center; margin-top: 1rem;">
                <span class="badge-status badge-{{ strtolower(str_replace(' ', '_', $transfer->status_text)) }}">
                    {{ $transfer->status_text }}
                </span>
                @if($transfer->is_overdue)
                    <span class="badge-status" style="background: #fecaca; color: #dc2626;">Overdue</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Details -->
    <div class="card-custom">
        <div class="card-header">
            <span><i class="fas fa-info-circle"></i> Transfer Details</span>
        </div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Item</label>
                    <p>{{ $transfer->item->item_name ?? 'N/A' }}</p>
                    <small class="text-muted">Code: {{ $transfer->item->item_code ?? 'N/A' }}</small>
                </div>

                <div class="detail-item">
                    <label>Quantity</label>
                    <p>{{ number_format($transfer->quantity, 2) }}</p>
                </div>

                <div class="detail-item">
                    <label>Transfer Date</label>
                    <p>{{ $transfer->transfer_date ? $transfer->transfer_date->format('d M Y, h:i A') : 'N/A' }}</p>
                </div>

                <div class="detail-item">
                    <label>Expected Arrival</label>
                    <p>{{ $transfer->expected_arrival_date ? $transfer->expected_arrival_date->format('d M Y') : 'N/A' }}</p>
                </div>

                <div class="detail-item">
                    <label>Created By</label>
                    <p>{{ $transfer->creator->name ?? 'System' }}</p>
                    <small class="text-muted">{{ $transfer->created_at->format('d M Y, h:i A') }}</small>
                </div>

                @if($transfer->approved_by)
                <div class="detail-item">
                    <label>Approved By</label>
                    <p>{{ $transfer->approver->name ?? 'N/A' }}</p>
                    <small class="text-muted">{{ $transfer->approved_at ? $transfer->approved_at->format('d M Y, h:i A') : '' }}</small>
                </div>
                @endif

                @if($transfer->received_by)
                <div class="detail-item">
                    <label>Received By</label>
                    <p>{{ $transfer->receiver->name ?? 'N/A' }}</p>
                    <small class="text-muted">{{ $transfer->received_date ? $transfer->received_date->format('d M Y, h:i A') : '' }}</small>
                </div>
                @endif

                @if($transfer->reason)
                <div class="detail-item">
                    <label>Reason</label>
                    <p>{{ $transfer->reason }}</p>
                </div>
                @endif
            </div>

            @if($transfer->notes)
            <div class="mt-3" style="padding: 1rem; background: #f8fafc; border-radius: 12px; border: 1px solid var(--border-color);">
                <label style="font-size: 0.7rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Notes</label>
                <p class="mb-0">{{ $transfer->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    @if(in_array($transfer->status, ['PENDING', 'APPROVED']))
    <div class="card-custom">
        <div class="card-body">
            <div class="action-buttons">
                @if($transfer->status == 'PENDING')
                    <form method="POST" action="{{ route('inventory.transfers.approve', $transfer->id) }}" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="btn-action btn-approve" onclick="return confirm('Approve this transfer?')">
                            <i class="fas fa-check"></i> Approve Transfer
                        </button>
                    </form>
                @endif

                @if($transfer->status == 'APPROVED')
                    <form method="POST" action="{{ route('inventory.transfers.start', $transfer->id) }}" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="btn-action btn-start" onclick="return confirm('Start this transfer? This will move stock between warehouses.')">
                            <i class="fas fa-play"></i> Start Transfer
                        </button>
                    </form>
                @endif

                @if(in_array($transfer->status, ['PENDING', 'APPROVED']))
                    <form method="POST" action="{{ route('inventory.transfers.cancel', $transfer->id) }}" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="btn-action btn-cancel" onclick="return confirm('Cancel this transfer?')">
                            <i class="fas fa-times"></i> Cancel Transfer
                        </button>
                    </form>
                @endif

                @if($transfer->status == 'PENDING')
                    <a href="{{ route('inventory.transfers.edit', $transfer->id) }}" class="btn-action btn-edit" style="background: #fef3c7; color: #92400e;">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

@endsection