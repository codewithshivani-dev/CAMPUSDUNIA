@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
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

    .btn-back {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.7rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        transition: all 0.3s;
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

    .btn-edit {
        background: white;
        color: var(--primary-color);
        padding: 0.7rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
        color: var(--primary-color);
    }

    .btn-logs {
        background: #fef3c7;
        color: #92400e;
        padding: 0.7rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-logs:hover {
        background: #fde68a;
        transform: translateY(-2px);
        color: #92400e;
    }

    .detail-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--card-shadow);
        border: 2px solid var(--border-color);
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .detail-item label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 0.25rem;
    }

    .detail-item p {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
    }

    .badge-status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-active {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-default {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .stat-box {
        background: #f8fafc;
        padding: 1rem;
        border-radius: 12px;
        text-align: center;
        border: 1px solid var(--border-color);
    }

    .stat-box .number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #4361ee;
    }

    .stat-box .label {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .meta-info {
        background: #f8fafc;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        margin-top: 1.5rem;
    }

    .meta-info .meta-item {
        display: inline-block;
        margin-right: 2rem;
        font-size: 0.85rem;
    }

    .meta-info .meta-item .label {
        color: var(--text-muted);
        font-weight: 500;
    }

    .meta-info .meta-item .value {
        font-weight: 600;
        color: var(--text-dark);
    }

    .address-block {
        background: #f8fafc;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        margin-top: 0.5rem;
        font-size: 0.9rem;
        color: var(--text-dark);
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .detail-card {
            padding: 1.25rem;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .meta-info .meta-item {
            display: block;
            margin-right: 0;
            margin-bottom: 0.5rem;
        }

        .stat-box {
            margin-bottom: 0.75rem;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-warehouse"></i> Warehouse Details</h1>
            <p>View warehouse information</p>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('inventory.warehouses.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <a href="{{ route('inventory.warehouses.stock-summary', $warehouse->id) }}" 
            class="btn-stock-summary" style="background: #dbeafe; color: #1d4ed8; padding: 0.7rem 1.5rem; border-radius: 12px; font-weight: 600; border: none; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                <i class="fas fa-boxes"></i> Stock Summary
            </a>
            <a href="{{ route('inventory.warehouses.logs', $warehouse->id) }}" class="btn-logs">
                <i class="fas fa-history"></i> View Logs
            </a>
            <a href="{{ route('inventory.warehouses.edit', $warehouse->id) }}" class="btn-edit">
                <i class="fas fa-pen"></i> Edit
            </a>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-box">
                <div class="number">{{ number_format($totalStock, 2) }}</div>
                <div class="label">Total Items in Stock</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-box">
                <div class="number">{{ $uniqueProducts }}</div>
                <div class="label">Unique Products</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-box">
                <div class="number">{{ $outOfStock }}</div>
                <div class="label">Out of Stock</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-box">
                <div class="number">{{ $lowStockItems }}</div>
                <div class="label">Low Stock Items</div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="detail-card">
        <div class="detail-grid">
            <div class="detail-item">
                <label>Warehouse Name</label>
                <p>{{ $warehouse->warehouse_name }}</p>
            </div>

            <div class="detail-item">
                <label>Warehouse Code</label>
                <p><span class="badge bg-secondary">{{ $warehouse->warehouse_code }}</span></p>
            </div>

            <div class="detail-item">
                <label>Status</label>
                <p>
                    <span class="badge-status {{ $warehouse->status ? 'badge-active' : 'badge-inactive' }}">
                        {{ $warehouse->status ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>

            <div class="detail-item">
                <label>Default</label>
                <p>
                    @if($warehouse->is_default)
                        <span class="badge badge-default"><i class="fas fa-check"></i> Default Warehouse</span>
                    @else
                        <span class="text-muted">No</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="detail-grid" style="margin-top: 1.5rem;">
            <div class="detail-item">
                <label>Capacity</label>
                <p>
                    @if($warehouse->capacity)
                        {{ number_format($warehouse->capacity) }} units
                        <br>
                        <small class="text-muted">Current utilization: {{ number_format($warehouse->current_utilization ?? 0) }} units</small>
                        <br>
                        <div class="progress mt-1" style="height: 8px;">
                            @php
                                $percentage = $warehouse->capacity > 0 
                                    ? min(100, ($warehouse->current_utilization / $warehouse->capacity) * 100) 
                                    : 0;
                                $color = $percentage > 80 ? '#ef4444' : ($percentage > 60 ? '#f59e0b' : '#10b981');
                            @endphp
                            <div class="progress-bar" style="width: {{ $percentage }}%; background: {{ $color }};"></div>
                        </div>
                        <small class="text-muted">{{ round($percentage, 1) }}% utilized</small>
                    @else
                        <span class="text-muted">Not specified</span>
                    @endif
                </p>
            </div>

            <div class="detail-item">
                <label>Total Items</label>
                <p>
                    <span class="badge bg-info" style="font-size: 1rem; padding: 0.5rem 1rem;">
                        {{ $warehouse->items_count ?? 0 }} items
                    </span>
                </p>
            </div>

            <div class="detail-item">
                <label>Contact Person</label>
                <p>{{ $warehouse->contact_person ?? 'N/A' }}</p>
            </div>

            <div class="detail-item">
                <label>Phone</label>
                <p>{{ $warehouse->phone ?? 'N/A' }}</p>
            </div>

            <div class="detail-item">
                <label>Email</label>
                <p>{{ $warehouse->email ?? 'N/A' }}</p>
            </div>

            <div class="detail-item">
                <label>Location</label>
                <p>
                    @if($warehouse->latitude && $warehouse->longitude)
                        <span class="text-muted">Lat: {{ $warehouse->latitude }}, Lng: {{ $warehouse->longitude }}</span>
                    @else
                        <span class="text-muted">Not set</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Address -->
        @if($warehouse->address)
        <div class="row mt-3">
            <div class="col-md-12">
                <label class="fw-bold" style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Address</label>
                <div class="address-block">
                    {{ $warehouse->address }}
                    @if($warehouse->city || $warehouse->state || $warehouse->pincode)
                        <br>
                        @if($warehouse->city){{ $warehouse->city }}, @endif
                        @if($warehouse->state){{ $warehouse->state }} @endif
                        @if($warehouse->pincode)- {{ $warehouse->pincode }}@endif
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Meta Information -->
        <div class="meta-info">
            <span class="meta-item">
                <span class="label">Created:</span>
                <span class="value">{{ $warehouse->created_at->format('d M Y, h:i A') }}</span>
            </span>
            @if($warehouse->creator)
                <span class="meta-item">
                    <span class="label">Created By:</span>
                    <span class="value">{{ $warehouse->creator->name }}</span>
                </span>
            @endif
            @if($warehouse->updated_at && $warehouse->updated_at != $warehouse->created_at)
                <span class="meta-item">
                    <span class="label">Last Updated:</span>
                    <span class="value">{{ $warehouse->updated_at->format('d M Y, h:i A') }}</span>
                </span>
            @endif
            @if($warehouse->updater)
                <span class="meta-item">
                    <span class="label">Updated By:</span>
                    <span class="value">{{ $warehouse->updater->name }}</span>
                </span>
            @endif
        </div>
    </div>
</div>
@endsection