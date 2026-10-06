@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --danger-gradient: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
        --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --border-radius-lg: 16px;
        --border-radius-md: 12px;
        --border-radius-sm: 8px;
    }

    .inventory-container {
        padding: 0;
        background: #f8fafc;
        min-height: 100vh;
    }

    .page-header{
        background: var(--primary-gradient);
        border-radius: var(--border-radius-lg);
        padding: 1.75rem 2rem;
        box-shadow: var(--card-shadow);
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .page-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #ffffff;
    }

    .page-title h4 {
        margin: 0;
        font-weight: 700;
        color: #ffffff;
    }

    .page-title i {
        font-size: 1.5rem;
        color: #fefeff;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn-modern {
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-modern-primary {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.35);
    }

    .btn-modern-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.45);
        color: white;
        text-decoration: none;
    }

    .btn-modern-secondary {
        background: #e2e8f0;
        color: #475569;
    }

    .btn-modern-secondary:hover {
        background: #cbd5e1;
        transform: translateY(-2px);
        text-decoration: none;
        color: #475569;
    }

    .btn-modern-success {
        background: var(--success-gradient);
        color: white;
        box-shadow: 0 4px 12px rgba(17, 153, 142, 0.35);
    }

    .btn-modern-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(17, 153, 142, 0.45);
        color: white;
        text-decoration: none;
    }

    .filter-section {
        background: white;
        padding: 1.25rem 1.5rem;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        margin-bottom: 1.5rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .filter-group label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-group input,
    .filter-group select {
        padding: 0.6rem 0.9rem;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
        background: white;
        font-size: 0.9rem;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
    }

    .table-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden;
    }
 
    .table-scroll {
        overflow-x: auto;
    }

    .table-custom {
        width: 100%;
    }

    .table-custom thead th {
        background: #f1f5f9;
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #475569;
        border: none;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table-custom thead th:first-child {
        border-radius: 8px 0 0 8px;
    }

    .table-custom thead th:last-child {
        border-radius: 0 8px 8px 0;
    }

    .table-custom tbody tr {
        background: white;
        transition: all 0.2s ease;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .table-custom tbody tr:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .table-custom tbody td {
        padding: 0.85rem 1rem;
        border: none;
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .table-custom tbody td:first-child {
        border-radius: 10px 0 0 10px;
    }

    .table-custom tbody td:last-child {
        border-radius: 0 10px 10px 0;
    }

    .item-code {
        font-family: 'Courier New', monospace;
        font-size: 0.75rem;
        background: #f1f5f9;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        color: #475569;
        font-weight: 600;
        white-space: nowrap;
    }

    .item-name-cell {
        font-weight: 600;
        color: #1e293b;
    }

    .item-brand {
        font-size: 0.75rem;
        color: #94a3b8;
        display: block;
        margin-top: 0.1rem;
    }

    .badge-stock {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-block;
        white-space: nowrap;
    }

    .badge-stock.in-stock {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-stock.low-stock {
        background: #fef3c7;
        color: #92400e;
        animation: pulse-warning 2s infinite;
    }

    .badge-stock.out-of-stock {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-stock.expired {
        background: #fecaca;
        color: #dc2626;
    }

    @keyframes pulse-warning {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .price-cell {
        font-weight: 600;
        color: #1e293b;
    }

    .price-cell .currency {
        font-size: 0.7rem;
        color: #94a3b8;
    }

    .margin-positive {
        color: #15803d;
        font-weight: 600;
    }

    .margin-negative {
        color: #dc2626;
        font-weight: 600;
    }

    .status-toggle-btn {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .status-toggle-btn.active {
        background: #dcfce7;
        color: #15803d;
    }

    .status-toggle-btn.active:hover {
        background: #bbf7d0;
        transform: scale(1.05);
    }

    .status-toggle-btn.inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-toggle-btn.inactive:hover {
        background: #fecaca;
        transform: scale(1.05);
    }

    .action-buttons {
        display: flex;
        gap: 0.4rem;
        flex-wrap: wrap;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        color: white;
        text-decoration: none;
        font-size: 0.8rem;
        cursor: pointer;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        color: white;
        text-decoration: none;
    }

    .action-btn-view {
        background: #3b82f6;
    }

    .action-btn-edit {
        background: #f59e0b;
    }

    .action-btn-toggle {
        background: #8b5cf6;
    }

    .action-btn-delete {
        background: #ef4444;
    }

    .action-btn-delete:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .action-btn-delete:disabled:hover {
        transform: none;
        box-shadow: none;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: #475569;
        font-weight: 600;
    }

    .empty-state p {
        color: #94a3b8;
    }

    .pagination-container {
        padding: 1rem 1.5rem 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .alert-modern {
        border: none;
        border-radius: 14px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideDown 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .alert-modern.alert-success {
        background: #f0fdf4;
        color: #166534;
        border-left: 4px solid #22c55e;
    }

    .alert-modern.alert-danger {
        background: #fef2f2;
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }

    .alert-modern i {
        font-size: 1.25rem;
    }

    .alert-modern .btn-close {
        margin-left: auto;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .stock-info {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }

    .stock-info .sub-text {
        font-size: 0.7rem;
        color: #94a3b8;
    }

    .expiry-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.6rem;
        border-radius: 12px;
        font-weight: 600;
    }

    .expiry-badge.good {
        background: #dcfce7;
        color: #15803d;
    }

    .expiry-badge.warning {
        background: #fef3c7;
        color: #92400e;
    }

    .expiry-badge.danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .expiry-badge.expired {
        background: #fecaca;
        color: #dc2626;
    }

    /* Location Styles */
    .location-info {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }

    .location-info .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.3rem 0.7rem;
        font-weight: 600;
    }

    .location-hierarchy {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
    }

    .location-main {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        font-weight: 600;
        font-size: 0.85rem;
        color: #1e293b;
    }

    .location-main i {
        font-size: 0.8rem;
    }

    .location-store {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        padding-left: 1.2rem;
        font-size: 0.8rem;
        color: #475569;
    }

    .location-store i {
        font-size: 0.7rem;
    }

    .location-details {
        padding-left: 1.2rem;
        font-size: 0.6rem;
        color: #94a3b8;
    }

    .location-details i {
        margin-right: 0.15rem;
    }

    .location-arrow {
        color: #94a3b8;
        font-size: 0.6rem;
        margin: 0 0.2rem;
    }

    .badge-location-warehouse {
        background: rgba(59, 130, 246, 0.1);
        color: #2563eb;
    }

    .badge-location-store {
        background: rgba(34, 197, 94, 0.1);
        color: #16a34a;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .header-actions {
            flex-direction: column;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .table-custom {
            font-size: 0.8rem;
            min-width: 900px;
        }

        .table-custom tbody td {
            padding: 0.6rem 0.5rem;
        }

        .action-buttons {
            flex-direction: column;
            gap: 0.3rem;
        }

        .location-store {
            padding-left: 0.8rem;
        }
    }
</style>

<div class="inventory-container">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-title">
            <i class="fas fa-boxes"></i>
            <div>
                <h4>Stock In</h4>
                <small>Manage all your inventory items</small>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('inventory.dashboard') }}" class="d-none btn-modern btn-modern-secondary">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
            <a href="{{ route('inventory.items.create') }}" class="btn-modern btn-modern-primary">
                <i class="fas fa-plus-circle"></i> Add Stock
            </a>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert-modern alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-modern alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Filter Section --}}
    <div class="filter-section">
        <form id="filterForm" method="GET" action="{{ route('inventory.items.index') }}" class="filter-grid">
            <div class="filter-group">
                <label for="searchItems"><i class="fas fa-search"></i> Search</label>
                <input type="text" id="searchItems" name="search" placeholder="Search by name, code, SKU..." value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <label for="filterCategory"><i class="fas fa-tag"></i> Category</label>
                <select id="filterCategory" name="category">
                    <option value="">All Categories</option>
                    @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label for="filterWarehouse"><i class="fas fa-warehouse"></i> Warehouse</label>
                <select id="filterWarehouse" name="warehouse">
                    <option value="">All Warehouses</option>
                    @foreach($warehouses ?? [] as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ request('warehouse') == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->warehouse_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label for="filterStatus"><i class="fas fa-circle"></i> Status</label>
                <select id="filterStatus" name="status">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="filter-group" style="display: flex; flex-direction: row; gap: 0.5rem; align-items: end;">
                <button type="submit" class="btn-modern btn-modern-primary" style="flex: 1;">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('inventory.items.index') }}" class="btn-modern btn-modern-secondary" style="flex: 1; text-align: center;">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-container">
        <div class="table-scroll">
            <table class="table-custom" id="itemsTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th class="sortable">Code</th>
                        <th class="sortable">Item Name</th>
                        <th class="sortable">Category</th>
                        <th class="sortable">Location</th>
                        <th class="sortable">Unit</th>
                        <th class="sortable">Stock</th>
                        <th class="sortable">Buying Price</th>
                        <th class="sortable">Selling Price</th>
                        <th class="sortable">Margin</th>
                        <th class="sortable">Expiry</th>
                        <th class="sortable">Status</th>
                        <th class="sortable">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $item)
                        @php
                            $stockBadge = 'in-stock';
                            $stockText = 'In Stock';
                            if($item->current_stock <= 0) {
                                $stockBadge = 'out-of-stock';
                                $stockText = 'Out of Stock';
                            } elseif($item->available_stock <= $item->reorder_level && $item->current_stock > 0) {
                                $stockBadge = 'low-stock';
                                $stockText = 'Low Stock';
                            }

                            // Expiry status
                            $expiryBadge = 'good';
                            $expiryText = 'Good';
                            if($item->expiry_date) {
                                $daysUntilExpiry = $item->days_until_expiry ?? null;
                                if($daysUntilExpiry !== null) {
                                    if($daysUntilExpiry < 0) {
                                        $expiryBadge = 'expired';
                                        $expiryText = 'Expired';
                                    } elseif($daysUntilExpiry <= 30) {
                                        $expiryBadge = 'danger';
                                        $expiryText = 'Expiring Soon';
                                    } elseif($daysUntilExpiry <= 90) {
                                        $expiryBadge = 'warning';
                                        $expiryText = 'Near Expiry';
                                    }
                                }
                            }

                            $margin = $item->margin ?? 0;
                            $marginClass = $margin >= 0 ? 'margin-positive' : 'margin-negative';
                            $marginIcon = $margin >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                        @endphp
                        <tr data-category="{{ $item->category_id }}" data-warehouse="{{ $item->warehouse_id }}" data-status="{{ $item->status ? '1' : '0' }}">
                            <td>{{ $items->firstItem() + $index }}</td>
                            <td>
                                <span class="item-code">{{ $item->item_code }}</span>
                            </td>
                            <td>
                                <div class="item-name-cell">
                                    {{ $item->item_name }}
                                    @if($item->brand)
                                        <span class="item-brand"><i class="fas fa-tag"></i> {{ $item->brand }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-opacity-10 text-info">
                                    <i class="fas fa-folder"></i> 
                                    {{ $item->category->category_name ?? 'N/A' }}
                                </span>
                                @if($item->subcategory)
                                    <br><small class="text-muted">{{ $item->subcategory->subcategory_name }}</small>
                                @endif
                            </td>
                            <td>
                                @if($item->store)
                                    <div class="location-info">
                                        {{-- Warehouse --}}
                                        @if($item->store->warehouse)
                                            <div class="location-main">
                                                <i class="fas fa-warehouse text-primary"></i>
                                                <span>{{ $item->store->warehouse->warehouse_name }}</span>
                                                <span class="d-none">
                                                    @if($item->store->warehouse->warehouse_code)
                                                        <small class="text-muted">
                                                            ({{ $item->store->warehouse->warehouse_code }})
                                                        </small>
                                                    @endif
                                                </span>
                                            </div>
                                        @endif

                                        {{-- Store --}}
                                        <div class="location-store">
                                            @if($item->store->warehouse)
                                                <i class="fas fa-long-arrow-alt-right location-arrow"></i>
                                            @endif

                                            <i class="fas fa-store text-success"></i>

                                            <strong>{{ $item->store->store_name }}</strong>
                                            <span class="d-none">
                                                @if($item->store->store_code)
                                                    <small class="text-muted">
                                                        ({{ $item->store->store_code }})
                                                    </small>
                                                @endif
                                            </span>
                                        </div>

                                        {{-- Building / Floor / Room --}}
                                        @php
                                            $location = [];

                                            if($item->store->building_id)
                                                $location[] = 'Building: '.$item->store->building_id;

                                            if($item->store->block_id)
                                                $location[] = 'Block: '.$item->store->block_id;

                                            if($item->store->floor_id)
                                                $location[] = 'Floor: '.$item->store->floor_id;

                                            if($item->store->room_id)
                                                $location[] = 'Room: '.$item->store->room_id;
                                        @endphp

                                        @if(count($location))
                                            <div class="location-details small text-muted ps-4">
                                                <i class="fas fa-location-dot"></i>
                                                {{ implode(' → ', $location) }}
                                            </div>
                                        @endif

                                        {{-- Contact --}}
                                        @if($item->store->contact_person || $item->store->phone)
                                            <div class="location-details small text-secondary ps-4">

                                                @if($item->store->contact_person)
                                                    <i class="fas fa-user"></i>
                                                    {{ $item->store->contact_person }}
                                                @endif

                                                @if($item->store->phone)
                                                    <span class="ms-2">
                                                        <i class="fas fa-phone"></i>
                                                        {{ $item->store->phone }}
                                                    </span>
                                                @endif

                                            </div>
                                        @endif
                                    </div>

                                @elseif($item->warehouse)
                                    <div class="location-info">
                                        <div class="location-main">
                                            <i class="fas fa-warehouse text-primary"></i>
                                            <span>{{ $item->warehouse->warehouse_name }}</span>
                                            <span class="d-none">
                                                @if($item->warehouse->warehouse_code)
                                                    <small class="text-muted">
                                                        ({{ $item->warehouse->warehouse_code }})
                                                    </small>
                                                @endif
                                            </span>
                                        </div>

                                        @php
                                            $location = [];
                                            if($item->warehouse->building_id)
                                                $location[] = 'Building: '.$item->warehouse->building_id;
                                            if($item->warehouse->block_id)
                                                $location[] = 'Block: '.$item->warehouse->block_id;
                                            if($item->warehouse->floor_id)
                                                $location[] = 'Floor: '.$item->warehouse->floor_id;
                                            if($item->warehouse->room_id)
                                                $location[] = 'Room: '.$item->warehouse->room_id;
                                        @endphp

                                        @if(count($location))
                                            <div class="location-details small text-muted ps-4">
                                                <i class="fas fa-location-dot"></i>
                                                {{ implode(' → ', $location) }}
                                            </div>
                                        @endif

                                        @if($item->warehouse->address)
                                            <div class="location-details small text-secondary ps-4">
                                                <i class="fas fa-map-marker-alt"></i>
                                                {{ Str::limit($item->warehouse->address,50) }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-question-circle"></i>
                                        No location assigned
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-opacity-10 text-secondary">
                                    {{ $item->unit_name ?? $item->unit_id ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <div class="stock-info">
                                    <span class="badge-stock {{ $stockBadge }}">
                                        <i class="fas fa-cube"></i> 
                                        {{ number_format($item->current_stock, 2) }} {{ $item->unit_code ?? '' }}
                                    </span>
                                    @if($item->available_stock <= $item->reorder_level && $item->current_stock > 0)
                                        <span class="sub-text text-warning">
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            Reorder at {{ number_format($item->reorder_level, 2) }}
                                        </span>
                                    @endif
                                    @if($item->current_stock > 0)
                                        <span class="sub-text">
                                            Available: {{ number_format($item->available_stock, 2) }}
                                            @if($item->reserved_stock > 0)
                                                (Reserved: {{ number_format($item->reserved_stock, 2) }})
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="price-cell">
                                    <span class="currency">₹</span> {{ number_format($item->buying_price, 2) }}
                                </div>
                            </td>
                            <td>
                                <div class="price-cell">
                                    <span class="currency">₹</span> {{ number_format($item->selling_price, 2) }}
                                </div>
                            </td>
                            <td>
                                <span class="{{ $marginClass }}">
                                    <i class="fas {{ $marginIcon }}"></i>
                                    {{ number_format($margin, 1) }}%
                                </span>
                            </td>
                            <td>
                                @if($item->expiry_date)
                                    <div>
                                        <span class="expiry-badge {{ $expiryBadge }}">
                                            <i class="fas fa-calendar-alt"></i>
                                            {{ \Carbon\Carbon::parse($item->expiry_date)->format('d M Y') }}
                                        </span>
                                        @if($item->days_until_expiry !== null)
                                            <br><small class="text-muted">
                                                {{ $item->days_until_expiry >= 0 ? $item->days_until_expiry . ' days left' : 'Overdue by ' . abs($item->days_until_expiry) . ' days' }}
                                            </small>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <button onclick="toggleItemStatus({{ $item->id }}, {{ $item->status ? '0' : '1' }})" 
                                        class="status-toggle-btn {{ $item->status ? 'active' : 'inactive' }}"
                                        title="Click to toggle status">
                                    <i class="fas fa-circle" style="font-size: 0.4rem;"></i>
                                    {{ $item->status ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('inventory.items.view', $item->id) }}" 
                                       class="action-btn action-btn-view" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('inventory.items.edit', $item->id) }}" 
                                       class="action-btn action-btn-edit" 
                                       title="Edit Item">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <button onclick="toggleItemStatus({{ $item->id }}, {{ $item->status ? '0' : '1' }})"
                                            class="action-btn action-btn-toggle" 
                                            title="{{ $item->status ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas {{ $item->status ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                    </button>
                                    @if($item->canBeDeleted())
                                        <button class="action-btn action-btn-delete delete-item"
                                                data-id="{{ $item->id }}"
                                                data-name="{{ $item->item_name }}"
                                                title="Delete Item">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @else
                                        <button class="action-btn action-btn-delete" 
                                                title="Cannot delete - has associated data" 
                                                disabled>
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13">
                                <div class="empty-state">
                                    <i class="fas fa-box-open"></i>
                                    <h5>No Items Found</h5>
                                    <p>Click "Add Item" to create your first inventory item.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($items->hasPages())
            <div class="pagination-container">
                {{ $items->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Status Toggle Confirmation Modal --}}
<div class="modal fade" id="statusToggleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
            <div class="modal-header" style="border-bottom: 2px solid #fef3c7; background: #fffbeb; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title" style="color: #92400e; font-weight: 700;">
                    <i class="fas fa-exchange-alt" style="color: #f59e0b;"></i>
                    Confirm Status Change
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <div style="width: 72px; height: 72px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: #f59e0b;"></i>
                    </div>
                </div>
                <h6 style="color: #1e293b; font-weight: 600; margin-bottom: 0.5rem; text-align: center;">
                    Are you sure you want to <span id="statusActionText" style="font-weight: 700; text-transform: uppercase;"></span> this item?
                </h6>
                <p style="color: #64748b; text-align: center; font-size: 0.9rem;" id="statusDescription">
                    This will <span id="statusEffectText"></span> the item <strong id="statusItemName"></strong>.
                </p>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 12px; border: 1px solid #e2e8f0; margin-top: 1rem;">
                    <p style="font-size: 0.85rem; color: #64748b; margin: 0;">
                        <i class="fas fa-info-circle" style="color: #3b82f6;"></i> 
                        <span id="statusInfoText"></span>
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 2px solid #e2e8f0; background: #f8fafc; border-radius: 0 0 16px 16px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 0.6rem 2rem; border-radius: 10px;">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" id="confirmStatusToggle" style="padding: 0.6rem 2rem; border-radius: 10px; background: var(--primary-gradient); color: white; border: none; font-weight: 600;">
                    <i class="fas fa-check"></i> Confirm
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
            <div class="modal-header" style="border-bottom: 2px solid #fee2e2; background: #fef2f2; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title" style="color: #991b1b; font-weight: 700;">
                    <i class="fas fa-trash-alt" style="color: #ef4444;"></i>
                    Delete Item
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <div style="width: 72px; height: 72px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: #ef4444;"></i>
                    </div>
                </div>
                <h6 style="color: #1e293b; font-weight: 600; text-align: center;">
                    Are you sure you want to delete <span id="deleteItemName" style="color: #991b1b;"></span>?
                </h6>
                <p style="color: #64748b; text-align: center; font-size: 0.9rem;">
                    This action cannot be undone. All associated data will be permanently removed.
                </p>
            </div>
            <div class="modal-footer" style="border-top: 2px solid #e2e8f0; background: #f8fafc; border-radius: 0 0 16px 16px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 0.6rem 2rem; border-radius: 10px;">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDelete" style="padding: 0.6rem 2rem; border-radius: 10px; font-weight: 600;">
                    <i class="fas fa-trash-alt"></i> Delete Permanently
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        let deleteItemId = null;

        // =============================================
        // STATUS TOGGLE
        // =============================================
        let toggleItemId = null;
        let toggleNewStatus = null;

        window.toggleItemStatus = function(id, newStatus) {
            toggleItemId = id;
            toggleNewStatus = newStatus;

            const modal = document.getElementById('statusToggleModal');
            const actionText = document.getElementById('statusActionText');
            const effectText = document.getElementById('statusEffectText');
            const infoText = document.getElementById('statusInfoText');
            const itemName = document.getElementById('statusItemName');

            // Get item name from the row
            const row = $(`#itemsTable tbody tr`).filter(function() {
                return $(this).find(`button[onclick*="toggleItemStatus(${id},"]`).length > 0;
            }).first();
            const name = row.find('.item-name-cell').text().trim() || 'this item';
            itemName.textContent = `"${name}"`;

            if (newStatus == 1) {
                actionText.textContent = 'ACTIVATE';
                actionText.style.color = '#15803d';
                effectText.textContent = 'activate';
                infoText.textContent = 'Activated items can be used in inventory operations, purchases, and sales.';
            } else {
                actionText.textContent = 'DEACTIVATE';
                actionText.style.color = '#991b1b';
                effectText.textContent = 'deactivate';
                infoText.textContent = 'Deactivated items will not be available for new transactions. Existing data will remain intact.';
            }

            const bootstrap = window.bootstrap;
            const modalInstance = new bootstrap.Modal(modal);
            modalInstance.show();
        };

        document.getElementById('confirmStatusToggle').addEventListener('click', function() {
            if (!toggleItemId || toggleNewStatus === null) return;

            const modal = document.getElementById('statusToggleModal');
            const bootstrap = window.bootstrap;
            const modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('inventory.items.toggle-status', '') }}/${toggleItemId}`;
            form.innerHTML = `
                @csrf
                @method('POST')
            `;
            document.body.appendChild(form);
            form.submit();
        });

        // =============================================
        // DELETE ITEM
        // =============================================
        $(document).on('click', '.delete-item', function() {
            deleteItemId = $(this).data('id');
            const itemName = $(this).data('name') || 'this item';
            document.getElementById('deleteItemName').textContent = `"${itemName}"`;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });

        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (!deleteItemId) return;

            const modal = document.getElementById('deleteModal');
            const bootstrap = window.bootstrap;
            const modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
            }

            $.ajax({
                url: '{{ route("inventory.items.delete", "") }}/' + deleteItemId,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message || 'Failed to delete item');
                    }
                },
                error: function(xhr) {
                    let message = 'Error deleting item. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    alert(message);
                    console.error(xhr);
                }
            });
        });

        // =============================================
        // AUTO-FILTER FORM SUBMISSION
        // =============================================
        $('#searchItems, #filterCategory, #filterWarehouse, #filterStatus').on('change keyup', function() {
            if ($(this).is('select') || $(this).is('#searchItems')) {
                $('#filterForm').submit();
            }
        });

        // Debounce search input
        let searchTimeout;
        $('#searchItems').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                $('#filterForm').submit();
            }, 500);
        });
    });
</script>

@endsection