{{-- resources/views/instituteAdmin/Inventory/store/view.blade.php --}}

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

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        border: 2px solid var(--border-color);
        transition: var(--transition);
        text-align: center;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
    }

    .stat-card .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }

    .stat-card .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
    }

    .stat-card .stat-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .stat-card .stat-icon.blue { color: #4361ee; }
    .stat-card .stat-icon.green { color: #10b981; }
    .stat-card .stat-icon.red { color: #ef4444; }
    .stat-card .stat-icon.yellow { color: #f59e0b; }

    .info-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        border: 2px solid var(--border-color);
        margin-bottom: 1.5rem;
    }

    .info-card .card-title {
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-card .card-title i {
        color: #4361ee;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-row .label {
        font-weight: 600;
        color: var(--text-muted);
    }

    .info-row .value {
        font-weight: 500;
        color: var(--text-dark);
    }

    .status-badge {
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-badge.active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-badge.inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-badge.default {
        background: #fef3c7;
        color: #92400e;
    }

    .utilization-bar {
        width: 100%;
        height: 8px;
        background: #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .utilization-bar .fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.6s ease;
    }

    .utilization-bar .fill.low {
        background: #10b981;
    }

    .utilization-bar .fill.medium {
        background: #f59e0b;
    }

    .utilization-bar .fill.high {
        background: #ef4444;
    }

    .item-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .item-list .item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--border-color);
        transition: var(--transition);
    }

    .item-list .item-row:hover {
        background: #f8fafc;
    }

    .item-list .item-row:last-child {
        border-bottom: none;
    }

    .item-list .item-name {
        font-weight: 600;
        color: var(--text-dark);
    }

    .item-list .item-code {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-left: 0.5rem;
    }

    .item-list .item-stock {
        font-weight: 600;
    }

    .item-list .item-stock.low {
        color: #ef4444;
    }

    .item-list .item-stock.medium {
        color: #f59e0b;
    }

    .item-list .item-stock.high {
        color: #10b981;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .stat-card {
            margin-bottom: 1rem;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-store"></i>{{ $store->store_name }}</h1>
            <p>{{ $store->store_code }} | {{ $store->warehouse->warehouse_name ?? 'N/A' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('inventory.stores.edit', $store->id) }}" class="btn-back">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('inventory.stores.stock-summary', $store->id) }}" class="btn btn-info">
                <i class="fas fa-chart-bar"></i> Stock Summary
            </a>
            <a href="{{ route('inventory.stores.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Status Badges -->
    <div class="mb-3">
        <span class="status-badge {{ $store->status ? 'active' : 'inactive' }}">
            {{ $store->status ? 'Active' : 'Inactive' }}
        </span>
        @if($store->is_default)
            <span class="status-badge default">
                <i class="fas fa-star"></i> Default Store
            </span>
        @endif
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-boxes"></i></div>
                <div class="stat-number">{{ $uniqueProducts }}</div>
                <div class="stat-label">Unique Products</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-cubes"></i></div>
                <div class="stat-number">{{ number_format($totalStock) }}</div>
                <div class="stat-label">Total Stock</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-number">{{ $outOfStock }}</div>
                <div class="stat-label">Out of Stock</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon yellow"><i class="fas fa-bell"></i></div>
                <div class="stat-number">{{ $lowStockItems }}</div>
                <div class="stat-label">Low Stock Items</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Store Details -->
        <div class="col-md-6">
            <div class="info-card">
                <div class="card-title">
                    <i class="fas fa-info-circle"></i> Store Details
                </div>
                <div class="info-row">
                    <span class="label">Store Name</span>
                    <span class="value">{{ $store->store_name }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Store Code</span>
                    <span class="value">{{ $store->store_code }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Warehouse</span>
                    <span class="value">{{ $store->warehouse->warehouse_name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Floor</span>
                    <span class="value">{{ $store->floor->floor_name ?? 'N/A' }}</span>
                </div>
                <div class="d-none info-row">
                    <span class="label">Room</span>
                    <span class="value">{{ $store->room->room_name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Capacity</span>
                    <span class="value">{{ $store->capacity ?? 0 }} units</span>
                </div>
                <div class="info-row">
                    <span class="label">Current Utilization</span>
                    <span class="value">
                        {{ $store->getUtilizationPercentageAttribute() }}%
                        <div class="utilization-bar">
                            @php
                                $percentage = $store->getUtilizationPercentageAttribute();
                                $class = 'low';
                                if ($percentage > 70) $class = 'high';
                                elseif ($percentage > 40) $class = 'medium';
                            @endphp
                            <div class="fill {{ $class }}" style="width: {{ $percentage }}%"></div>
                        </div>
                    </span>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="info-card">
                <div class="card-title">
                    <i class="fas fa-address-card"></i> Contact Information
                </div>
                <div class="info-row">
                    <span class="label">Contact Person</span>
                    <span class="value">{{ $store->contact_person ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Phone</span>
                    <span class="value">{{ $store->phone ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Email</span>
                    <span class="value">{{ $store->email ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Address -->
            <div class="info-card">
                <div class="card-title">
                    <i class="fas fa-map-marker-alt"></i> Address
                </div>
                <div class="info-row">
                    <span class="label">Address</span>
                    <span class="value">{{ $store->address ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">City</span>
                    <span class="value">{{ $store->city ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">State</span>
                    <span class="value">{{ $store->state ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Pincode</span>
                    <span class="value">{{ $store->pincode ?? 'N/A' }}</span>
                </div>
                @if($store->latitude && $store->longitude)
                    <div class="info-row">
                        <span class="label">Coordinates</span>
                        <span class="value">{{ $store->latitude }}, {{ $store->longitude }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Items -->
        <div class="col-md-6">
            <div class="info-card">
                <div class="card-title">
                    <i class="fas fa-box"></i> Items in Store
                    <span class="badge bg-primary ms-2">{{ $store->items_count }}</span>
                </div>
                @if($store->items->count() > 0)
                    <div class="item-list">
                        @foreach($store->items as $item)
                            <div class="item-row">
                                <div>
                                    <span class="item-name">{{ $item->item_name }}</span>
                                    <span class="item-code">{{ $item->item_code }}</span>
                                </div>
                                <div>
                                    <span class="item-stock 
                                        @if($item->current_stock <= $item->reorder_level) low
                                        @elseif($item->current_stock <= $item->reorder_level * 2) medium
                                        @else high
                                        @endif">
                                        {{ number_format($item->current_stock) }}
                                    </span>
                                    <span class="text-muted" style="font-size: 0.8rem;">
                                        ({{ $item->available_stock }} available)
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($store->items_count > 20)
                        <div class="text-center mt-3">
                            <small class="text-muted">Showing first 20 items</small>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-box-open" style="font-size: 3rem; color: #d1d5db;"></i>
                        <p class="mt-2 text-muted">No items in this store</p>
                    </div>
                @endif
            </div>

            <!-- Audit Information -->
            <div class="info-card">
                <div class="card-title">
                    <i class="fas fa-history"></i> Audit Information
                </div>
                <div class="info-row">
                    <span class="label">Created By</span>
                    <span class="value">{{ $store->creator->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Created At</span>
                    <span class="value">{{ $store->created_at ? $store->created_at->format('d M Y, h:i A') : 'N/A' }}</span>
                </div>
                @if($store->updated_at)
                    <div class="info-row">
                        <span class="label">Last Updated By</span>
                        <span class="value">{{ $store->updater->name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Last Updated At</span>
                        <span class="value">{{ $store->updated_at->format('d M Y, h:i A') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection