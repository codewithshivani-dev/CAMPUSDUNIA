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

    .page-header p {
        opacity: 0.9;
        margin: 0;
    }

    .btn-create {
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

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
        color: #4361ee;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-box {
        background: white;
        padding: 1rem;
        border-radius: 12px;
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
    }

    .stat-box .label {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .stat-box.total .number { color: #4361ee; }
    .stat-box.pending .number { color: #f59e0b; }
    .stat-box.approved .number { color: #3b82f6; }
    .stat-box.completed .number { color: #10b981; }
    .stat-box.cancelled .number { color: #ef4444; }

    .card-custom {
        background: white;
        border-radius: 20px;
        border: 2px solid var(--border-color);
        overflow: hidden;
        box-shadow: var(--card-shadow);
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

    .table-responsive-custom {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    .table-custom thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.75rem 1rem;
        border-bottom: 2px solid var(--border-color);
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .table-custom tbody td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
        background-color: white;
    }

    .table-custom tbody tr:last-child td {
        border-bottom: none;
    }

    .table-custom tbody tr:hover {
        background-color: #f8fafc;
    }

    .badge-status {
        display: inline-block;
        padding: 0.25rem 0.7rem;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-approved { background: #dbeafe; color: #1d4ed8; }
    .badge-in_transit { background: #e0e7ff; color: #4338ca; }
    .badge-completed { background: #d1fae5; color: #065f46; }
    .badge-cancelled { background: #fee2e2; color: #991b1b; }

    .badge-overdue {
        background: #fecaca;
        color: #dc2626;
        font-size: 0.55rem;
        padding: 0.1rem 0.5rem;
        border-radius: 12px;
        font-weight: 600;
    }

    .btn-action {
        border: none;
        border-radius: 8px;
        padding: 0.3rem 0.6rem;
        font-size: 0.65rem;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 3px;
        text-decoration: none;
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .btn-view { background: #e0e7ff; color: #4338ca; }
    .btn-view:hover { background: #c7d2fe; }

    .btn-edit { background: #fef3c7; color: #92400e; }
    .btn-edit:hover { background: #fde68a; }

    .btn-print { background: #e0e7ff; color: #4338ca; }
    .btn-print:hover { background: #c7d2fe; }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--text-muted);
    }

    .empty-state .empty-icon {
        font-size: 3rem;
        color: var(--border-color);
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: var(--text-dark);
        font-weight: 600;
    }

    .pagination-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 2px solid var(--border-color);
    }

    .pagination-custom .pagination-info {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .stats-row {
            grid-template-columns: repeat(3, 1fr);
        }

        .table-custom thead th,
        .table-custom tbody td {
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
        }

        .pagination-custom {
            flex-direction: column;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }

        .stat-box .number {
            font-size: 1.2rem;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-exchange-alt"></i> Warehouse Transfers</h1>
            <p>Manage inventory transfers between warehouses</p>
        </div>
        <a href="{{ route('inventory.transfers.create') }}" class="btn-create">
            <i class="fas fa-plus-circle"></i> Create Transfer
        </a>
    </div>

    <!-- Stats Row -->
    <div class="stats-row">
        <div class="stat-box total">
            <div class="number">{{ $stats['total'] }}</div>
            <div class="label">Total Transfers</div>
        </div>
        <div class="stat-box pending">
            <div class="number">{{ $stats['pending'] }}</div>
            <div class="label">Pending</div>
        </div>
        <div class="stat-box approved">
            <div class="number">{{ $stats['approved'] }}</div>
            <div class="label">Approved</div>
        </div>
        <div class="stat-box completed">
            <div class="number">{{ $stats['completed'] }}</div>
            <div class="label">Completed</div>
        </div>
        <div class="stat-box cancelled">
            <div class="number">{{ $stats['cancelled'] }}</div>
            <div class="label">Cancelled</div>
        </div>
    </div>

    <!-- Table -->
    <div class="card-custom">
        <div class="card-header">
            <span>
                <i class="fas fa-list"></i> Transfer List
                <span class="badge bg-secondary ms-2">{{ $transfers->total() }}</span>
            </span>
        </div>
        <div class="card-body">
            <div class="table-responsive-custom">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Transfer Code</th>
                            <th>Item</th>
                            <th>From → To</th>
                            <th style="text-align: right;">Quantity</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $transfer)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $transfer->transfer_code }}</span>
                                @if($transfer->is_overdue)
                                    <span class="badge-overdue">Overdue</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $transfer->item->item_name ?? 'N/A' }}</strong>
                                <br>
                                <small class="text-muted">{{ $transfer->item->item_code ?? '' }}</small>
                            </td>
                            <td>
                                <span class="text-danger">{{ $transfer->fromWarehouse->warehouse_name ?? 'N/A' }}</span>
                                <i class="fas fa-arrow-right mx-1" style="color: #94a3b8; font-size: 0.7rem;"></i>
                                <span class="text-success">{{ $transfer->toWarehouse->warehouse_name ?? 'N/A' }}</span>
                            </td>
                            <td style="text-align: right;">
                                <strong>{{ number_format($transfer->quantity, 2) }}</strong>
                            </td>
                            <td>
                                <span class="badge-status badge-{{ strtolower(str_replace(' ', '_', $transfer->status_text)) }}">
                                    {{ $transfer->status_text }}
                                </span>
                            </td>
                            <td>
                                {{ $transfer->created_at->format('d M Y') }}
                                <br>
                                <small class="text-muted">{{ $transfer->created_at->format('h:i A') }}</small>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 0.3rem; justify-content: center; flex-wrap: wrap;">
                                    <a href="{{ route('inventory.transfers.show', $transfer->id) }}" 
                                       class="btn-action btn-view" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($transfer->status == 'PENDING')
                                        <a href="{{ route('inventory.transfers.edit', $transfer->id) }}" 
                                           class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                    @if($transfer->status == 'COMPLETED')
                                        <a href="{{ route('inventory.transfers.print', $transfer->id) }}" 
                                           class="btn-action btn-print" title="Print" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="fas fa-exchange-alt"></i></div>
                                    <h5>No Transfers Found</h5>
                                    <p>Click "Create Transfer" to start a warehouse transfer.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-custom">
                <div class="pagination-info">
                    Showing <strong>{{ $transfers->firstItem() ?? 0 }}</strong> to 
                    <strong>{{ $transfers->lastItem() ?? 0 }}</strong> of 
                    <strong>{{ $transfers->total() }}</strong> entries
                </div>
                <div>
                    {{ $transfers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection