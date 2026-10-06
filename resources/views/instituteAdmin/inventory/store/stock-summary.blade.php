{{-- resources/views/instituteAdmin/Inventory/store/stock-summary.blade.php --}}

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

    .btn-view {
        background: white;
        color: var(--primary-color);
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

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
        color: var(--primary-color);
    }

    .stat-box {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        text-align: center;
        border: 2px solid var(--border-color);
        transition: var(--transition);
    }

    .stat-box:hover {
        transform: translateY(-4px);
        box-shadow: var(--card-shadow);
        border-color: #4361ee;
    }

    .stat-box .number {
        font-size: 2rem;
        font-weight: 700;
        color: #4361ee;
    }

    .stat-box .label {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
    }

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

    .table-custom tbody tr {
        transition: var(--transition);
    }

    .table-custom tbody tr:hover {
        background-color: #f8fafc;
    }

    .stock-status {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .stock-status.in-stock {
        background: #d1fae5;
        color: #065f46;
    }

    .stock-status.low-stock {
        background: #fef3c7;
        color: #92400e;
    }

    .stock-status.out-of-stock {
        background: #fee2e2;
        color: #991b1b;
    }

    .stock-status.expired {
        background: #fecaca;
        color: #dc2626;
    }

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

    .empty-state p {
        color: var(--text-muted);
        max-width: 400px;
        margin: 0 auto;
    }

    /* Search/Filter Bar */
    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .filter-bar .filter-input {
        flex: 1;
        min-width: 200px;
        padding: 0.5rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        font-size: 0.85rem;
        background: white;
        transition: var(--transition);
    }

    .filter-bar .filter-input:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .filter-bar .filter-select {
        padding: 0.5rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        font-size: 0.85rem;
        background: white;
        min-width: 150px;
        transition: var(--transition);
    }

    .filter-bar .filter-select:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .filter-bar .btn-reset {
        padding: 0.4rem 1.2rem;
        background: #f1f5f9;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.8rem;
        color: var(--text-muted);
        cursor: pointer;
        transition: var(--transition);
    }

    .filter-bar .btn-reset:hover {
        background: #e2e8f0;
        color: var(--text-dark);
    }

    .store-info-badge {
        background: #dbeafe;
        color: #1e40af;
        padding: 0.3rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .store-info-badge i {
        margin-right: 6px;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .stat-box {
            padding: 1rem;
        }

        .stat-box .number {
            font-size: 1.5rem;
        }

        .filter-bar {
            flex-direction: column;
        }

        .filter-bar .filter-input,
        .filter-bar .filter-select {
            min-width: 100%;
        }

        .table-custom thead th,
        .table-custom tbody td {
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
        }
    }

    @media (max-width: 480px) {
        .card-custom .card-body {
            padding: 0.75rem;
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
            <h1><i class="fas fa-boxes"></i> Stock Summary</h1>
            <p>
                <span class="store-info-badge">
                    <i class="fas fa-store"></i> {{ $store->store_name }}
                </span>
                <span class="store-info-badge ms-2">
                    <i class="fas fa-code"></i> {{ $store->store_code }}
                </span>
                @if($store->warehouse)
                <span class="store-info-badge ms-2">
                    <i class="fas fa-warehouse"></i> {{ $store->warehouse->warehouse_name }}
                </span>
                @endif
            </p>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('inventory.stores.view', $store->id) }}" class="btn-view">
                <i class="fas fa-eye"></i> View Store
            </a>
            <a href="{{ route('inventory.stores.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-box">
                <div class="number">{{ number_format($totalStock, 2) }}</div>
                <div class="label">Total Items in Stock</div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-box">
                <div class="number">{{ $uniqueProducts }}</div>
                <div class="label">Products</div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-box">
                <div class="number">{{ $outOfStock }}</div>
                <div class="label">Out of Stock</div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stat-box">
                <div class="number">{{ $lowStockItems }}</div>
                <div class="label">Low Stock Items</div>
            </div>
        </div>
    </div>

    <!-- Utilization Overview -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card-custom">
                <div class="card-header">
                    <span><i class="fas fa-chart-bar"></i> Utilization Overview</span>
                    <span style="font-size: 0.85rem; color: var(--text-muted);">
                        Capacity: <strong>{{ $store->capacity ?? 0 }}</strong> units | 
                        Current: <strong>{{ $store->current_utilization ?? 0 }}</strong> units | 
                        <strong>{{ $store->getUtilizationPercentageAttribute() }}%</strong> utilized
                    </span>
                </div>
                <div class="card-body">
                    <div class="progress" style="height: 25px; border-radius: 12px; background: #e5e7eb;">
                        @php
                            $percentage = $store->getUtilizationPercentageAttribute();
                            $color = 'bg-success';
                            if ($percentage > 70) $color = 'bg-danger';
                            elseif ($percentage > 40) $color = 'bg-warning';
                        @endphp
                        <div class="progress-bar {{ $color }}" 
                             role="progressbar" 
                             style="width: {{ min($percentage, 100) }}%; border-radius: 12px; font-weight: 700; font-size: 0.9rem;"
                             aria-valuenow="{{ $percentage }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ number_format($percentage, 1) }}%
                        </div>
                    </div>
                    @if($store->capacity > 0 && $percentage >= 90)
                        <div class="alert alert-warning mt-2 mb-0">
                            <i class="fas fa-exclamation-triangle"></i> 
                            <strong>Warning:</strong> Store is almost at full capacity. Consider expanding or redistributing stock.
                        </div>
                    @endif
                    @if($store->capacity > 0 && $percentage >= 100)
                        <div class="alert alert-danger mt-2 mb-0">
                            <i class="fas fa-exclamation-circle"></i> 
                            <strong>Critical:</strong> Store is at or over capacity! Please take immediate action.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Search/Filter Bar -->
    <div class="filter-bar">
        <input type="text" id="searchStock" class="filter-input" placeholder="🔍 Search items by name or code...">
        <select id="filterStockStatus" class="filter-select">
            <option value="">All Status</option>
            <option value="in-stock">In Stock</option>
            <option value="low-stock">Low Stock</option>
            <option value="out-of-stock">Out of Stock</option>
            <option value="expired">Expired</option>
        </select>
        <button onclick="resetStockFilters()" class="btn-reset">
            <i class="fas fa-undo"></i> Reset
        </button>
    </div>

    <!-- Stock Table -->
    <div class="card-custom">
        <div class="card-header">
            <span>
                <i class="fas fa-list"></i> Stock Items
                <span class="badge bg-info ms-2">{{ $store->items->count() }}</span>
            </span>
            <span style="font-size: 0.8rem; color: var(--text-muted);">
                Total Stock: <strong>{{ number_format($totalStock, 2) }}</strong>
            </span>
        </div>
        <div class="card-body">
            <div class="table-responsive-custom">
                <table class="table-custom" id="stockTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th style="text-align: right;">Current Stock</th>
                            <th style="text-align: right;">Available Stock</th>
                            <th style="text-align: right;">Reserved Stock</th>
                            <th style="text-align: right;">Reorder Level</th>
                            <th>Status</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($store->items as $item)
                        <tr data-stock="{{ $item->stock_status ?? 'in-stock' }}">
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge bg-secondary">{{ $item->item_code }}</span></td>
                            <td>
                                <a href="{{ route('inventory.items.view', $item->id) }}" style="color: var(--text-dark); text-decoration: none;">
                                    <strong>{{ $item->item_name }}</strong>
                                </a>
                            </td>
                            <td style="text-align: right;">{{ number_format($item->current_stock, 2) }}</td>
                            <td style="text-align: right;">{{ number_format($item->available_stock, 2) }}</td>
                            <td style="text-align: right;">{{ number_format($item->reserved_stock ?? 0, 2) }}</td>
                            <td style="text-align: right;">{{ number_format($item->reorder_level, 2) }}</td>
                            <td>
                                @php
                                    $status = 'in-stock';
                                    $statusText = 'In Stock';
                                    if($item->current_stock <= 0) {
                                        $status = 'out-of-stock';
                                        $statusText = 'Out of Stock';
                                    } elseif($item->available_stock <= $item->reorder_level) {
                                        $status = 'low-stock';
                                        $statusText = 'Low Stock';
                                    }
                                    // Check if expired (if expiry_date exists)
                                    if($item->expiry_date && $item->expiry_date < now()) {
                                        $status = 'expired';
                                        $statusText = 'Expired';
                                    }
                                @endphp
                                <span class="stock-status {{ $status }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('inventory.items.view', $item->id) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="View Item Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="fas fa-box"></i></div>
                                    <h5>No Items in this Store</h5>
                                    <p>There are currently no items in this store. Add items or transfer stock to this store.</p>
                                    <a href="{{ route('inventory.stock-out.create') }}" class="d-none btn btn-primary mt-2">
                                        <i class="fas fa-plus-circle"></i> Add Stock
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // ============================================
    // SEARCH & FILTER
    // ============================================

    document.getElementById('searchStock').addEventListener('keyup', filterStockTable);
    document.getElementById('filterStockStatus').addEventListener('change', filterStockTable);

    function filterStockTable() {
        const search = document.getElementById('searchStock').value.toLowerCase();
        const status = document.getElementById('filterStockStatus').value;

        const rows = document.querySelectorAll('#stockTable tbody tr');

        rows.forEach(row => {
            const name = row.querySelector('td:nth-child(3)')?.textContent?.toLowerCase() || '';
            const code = row.querySelector('td:nth-child(2)')?.textContent?.toLowerCase() || '';
            const rowStatus = row.dataset.stock || '';

            let show = true;

            if (search && !name.includes(search) && !code.includes(search)) {
                show = false;
            }

            if (status && rowStatus !== status) {
                show = false;
            }

            row.style.display = show ? '' : 'none';
        });
    }

    function resetStockFilters() {
        document.getElementById('searchStock').value = '';
        document.getElementById('filterStockStatus').value = '';
        filterStockTable();
    }

    // ============================================
    // INITIALIZE
    // ============================================

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any tooltips or additional functionality
    });
</script>

@endsection