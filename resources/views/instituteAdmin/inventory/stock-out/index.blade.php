{{-- resources/views/instituteAdmin/Inventory/stock-out/index.blade.php --}}

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

    .btn-primary-custom {
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

    .btn-primary-custom:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        color: white;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
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
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
    }

    .stat-box .label {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        border: 2px solid var(--border-color);
        margin-bottom: 1.5rem;
    }

    .stock-out-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: var(--card-shadow);
        border: 2px solid var(--border-color);
        transition: var(--transition);
        margin-bottom: 1rem;
    }

    .stock-out-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    }

    .stock-out-card .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .stock-out-card .code {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--text-dark);
    }

    .stock-out-card .code small {
        font-weight: 400;
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    .stock-out-card .details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 0.75rem;
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 2px solid var(--border-color);
    }

    .stock-out-card .detail-item {
        display: flex;
        flex-direction: column;
    }

    .stock-out-card .detail-item .label {
        font-size: 0.7rem;
        color: var(--text-muted);
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .stock-out-card .detail-item .value {
        font-size: 0.9rem;
        color: var(--text-dark);
        font-weight: 500;
    }

    .stock-out-card .actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 2px solid var(--border-color);
    }

    .badge-status {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-status.pending { background: #fef3c7; color: #92400e; }
    .badge-status.approved { background: #dbeafe; color: #1e40af; }
    .badge-status.completed { background: #d1fae5; color: #065f46; }
    .badge-status.in-transit { background: #f9fab2; color: #414602; }
    .badge-status.cancelled { background: #fee2e2; color: #991b1b; }

    .badge-type {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-type.transfer { background: #e0e7ff; color: #3730a3; }
    .badge-type.sell { background: #fce7f3; color: #9d174d; }

    .btn-action {
        padding: 0.3rem 0.8rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .btn-action.view { background: #dbeafe; color: #1e40af; }
    .btn-action.edit { background: #fef3c7; color: #92400e; }
    .btn-action.approve { background: #d1fae5; color: #065f46; }
    .btn-action.complete { background: #a7f3d0; color: #065f46; }
    .btn-action.cancel { background: #fee2e2; color: #991b1b; }
    .btn-action.delete { background: #fecaca; color: #991b1b; }
    .btn-action.print { background: #e0e7ff; color: #3730a3; }

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

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    a {
        text-decoration: none;
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-arrow-right"></i> Stock Out</h1>
            <p>Manage transfers and sales</p>
        </div>
        <div>
            <a href="{{ route('inventory.stock-out.create') }}" class="btn-primary-custom">
                <i class="fas fa-plus-circle"></i> New Stock Out
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-row">
        <div class="stat-box">
            <div class="number">{{ $stats['total'] }}</div>
            <div class="label">Total</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $stats['pending'] }}</div>
            <div class="label">Pending</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $stats['approved'] }}</div>
            <div class="label">Approved</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $stats['completed'] }}</div>
            <div class="label">Completed</div>
        </div>
        <div class="stat-box">
            <div class="number">{{ $stats['cancelled'] }}</div>
            <div class="label">Cancelled</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <form method="GET" action="{{ route('inventory.stock-out.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" 
                    placeholder="Code, customer, transaction..." 
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="transfer" {{ request('type') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="sell" {{ request('type') == 'sell' ? 'selected' : '' }}>Sale</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="in-transit" {{ request('status') == 'in-transit' ? 'selected' : '' }}>In-Transit</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('inventory.stock-out.index') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- List -->
    @if($stockOuts->count() > 0)
        @foreach($stockOuts as $stockOut)
            <div class="stock-out-card">
                <div class="header">
                    <div>
                        <span class="code">
                            {{ $stockOut->stock_out_code }}
                            <small>{{ $stockOut->created_at->format('d M Y, h:i A') }}</small>
                        </span>
                        <span class="badge-status {{ $stockOut->status }}">
                            {{ ucfirst($stockOut->status) }}
                        </span>
                        <span class="badge-type {{ $stockOut->type }}">
                            {{ ucfirst($stockOut->type) }}
                        </span>
                        @if($stockOut->type === 'transfer')
                            <span class="badge bg-secondary">{{ str_replace('_', ' ', ucfirst($stockOut->sub_type)) }}</span>
                        @endif
                        @if($stockOut->payment_status === 'paid')
                            <span class="badge bg-success">Paid</span>
                        @endif
                    </div>
                    <div>
                        @php
                            // Since items is already cast to array, use it directly
                            $itemsArray = $stockOut->items;
                            $totalQuantity = is_array($itemsArray) ? collect($itemsArray)->sum('quantity') : 0;
                        @endphp
                        <span class="fw-bold">{{ number_format($totalQuantity) }} units</span>
                        @if($stockOut->type === 'sell' && $stockOut->total_amount)
                            <span class="ms-2 text-success">₹ {{ number_format($stockOut->total_amount, 2) }}</span>
                        @endif
                    </div>
                </div>

                <div class="details">
                    <div class="detail-item">
                        <span class="label">Item(s)</span>
                        <span class="value">
                            @php
                                $itemsArray = $stockOut->items;
                                $itemNames = is_array($itemsArray) ? collect($itemsArray)->pluck('name')->implode(', ') : 'N/A';
                            @endphp
                            {{ $itemNames ?: 'N/A' }}
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="label">From</span>
                        <span class="value">
                            @if($stockOut->from_warehouse_id)
                                Warehouse #{{ $stockOut->from_warehouse_id }}
                            @elseif($stockOut->from_store_id)
                                Store #{{ $stockOut->from_store_id }}
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="label">To</span>
                        <span class="value">
                            @if($stockOut->type === 'sell')
                                {{ $stockOut->customer_name ?? 'Customer' }}
                            @elseif($stockOut->to_warehouse_id)
                                Warehouse #{{ $stockOut->to_warehouse_id }}
                            @elseif($stockOut->to_store_id)
                                Store #{{ $stockOut->to_store_id }}
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                    @if($stockOut->type === 'sell')
                        <div class="detail-item">
                            <span class="label">Payment</span>
                            <span class="value">{{ ucfirst($stockOut->payment_method ?? 'N/A') }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Customer</span>
                            <span class="value">{{ $stockOut->customer_name ?? 'N/A' }}</span>
                        </div>
                    @endif
                    <div class="detail-item">
                        <span class="label">Created By</span>
                        <span class="value">{{ $stockOut->created_by ? 'User #'.$stockOut->created_by : 'N/A' }}</span>
                    </div>
                </div>

                <div class="actions">
                    <a href="{{ route('inventory.stock-out.show', $stockOut->id) }}" class="btn-action view">
                        <i class="fas fa-eye"></i> View
                    </a>
                    
                    @if($stockOut->status === 'pending')
                        <a href="{{ route('inventory.stock-out.edit', $stockOut->id) }}" class="btn-action edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('inventory.stock-out.approve', $stockOut->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-action approve" onclick="return confirm('Approve this stock out?')">
                                <i class="fas fa-check"></i> Approve
                            </button>
                        </form>
                        <form action="{{ route('inventory.stock-out.cancel', $stockOut->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-action cancel" onclick="return confirm('Cancel this stock out?')">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                        </form>
                    @endif

                    @if($stockOut->status === 'approved' && $stockOut->type === 'transfer')
                        <form action="{{ route('inventory.stock-out.complete', $stockOut->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-action complete" onclick="return confirm('Complete this transfer?')">
                                <i class="fas fa-check-double"></i> Complete
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('inventory.stock-out.print', $stockOut->id) }}" class="btn-action print" target="_blank">
                        <i class="fas fa-print"></i> Print
                    </a>

                    @if($stockOut->status === 'pending')
                        <form action="{{ route('inventory.stock-out.destroy', $stockOut->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action delete" onclick="return confirm('Delete this stock out?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                Showing {{ $stockOuts->firstItem() }} to {{ $stockOuts->lastItem() }} of {{ $stockOuts->total() }} records
            </div>
            <div>
                {{ $stockOuts->appends(request()->query())->links() }}
            </div>
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <h4>No Stock Out Records</h4>
            <p class="text-muted">Start by creating a new transfer or sale.</p>
        </div>
    @endif
</div>
@endsection