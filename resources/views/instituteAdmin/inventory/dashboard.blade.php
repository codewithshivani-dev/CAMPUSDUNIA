@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #3b82f6;
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ============================================
       DASHBOARD HEADER
       ============================================ */
    .dashboard-header {
        background: var(--primary-gradient);
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
    }

    .dashboard-header h1 {
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.5rem;
    }

    .dashboard-header h1 i {
        background: rgba(255, 255, 255, 0.2);
        padding: 10px;
        border-radius: 12px;
        font-size: 1.3rem;
    }

    .dashboard-header .subtitle {
        opacity: 0.9;
        font-size: 0.95rem;
        margin-top: 0.25rem;
    }

    .dashboard-header .badge-config {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.4rem 1.2rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* ============================================
       HIERARCHICAL FILTER BAR
       ============================================ */
    .filter-bar-advanced {
        background: white;
        padding: 1.25rem 1.5rem;
        border-radius: 16px;
        border: 2px solid var(--border-color);
        margin-bottom: 1.5rem;
    }

    .filter-bar-advanced .filter-title {
        font-weight: 700;
        color: var(--text-dark);
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-bar-advanced .filter-title i {
        color: var(--primary-color);
    }

    .filter-bar-advanced .filter-title .clear-all {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--danger-color);
        cursor: pointer;
        margin-left: auto;
        text-decoration: none;
    }

    .filter-bar-advanced .filter-title .clear-all:hover {
        text-decoration: underline;
    }

    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: center;
    }

    .filter-row .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        flex: 1;
        min-width: 150px;
    }

    .filter-row .filter-group label {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-row .filter-group select,
    .filter-row .filter-group input {
        padding: 0.5rem 0.8rem;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        font-size: 0.85rem;
        background: #f8fafc;
        transition: var(--transition);
        width: 100%;
        color: var(--text-dark);
    }

    .filter-row .filter-group select:focus,
    .filter-row .filter-group input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        background: white;
    }

    .filter-row .filter-group select:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .filter-row .filter-group .filter-hint {
        font-size: 0.65rem;
        color: var(--text-muted);
        font-style: italic;
    }

    .filter-row .filter-actions {
        display: flex;
        gap: 0.5rem;
        align-items: flex-end;
        padding-bottom: 0.1rem;
    }

    .filter-row .filter-actions .btn-filter {
        padding: 0.5rem 1.5rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: var(--transition);
        background: var(--primary-gradient);
        color: white;
    }

    .filter-row .filter-actions .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
    }

    .filter-row .filter-actions .btn-reset {
        padding: 0.5rem 1.2rem;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: var(--transition);
        background: white;
        color: var(--text-muted);
    }

    .filter-row .filter-actions .btn-reset:hover {
        border-color: var(--danger-color);
        color: var(--danger-color);
    }

    /* ============================================
       QUICK NAVIGATION
       ============================================ */
    .quick-nav {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .quick-nav .nav-item {
        background: white;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--text-dark);
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .quick-nav .nav-item:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: var(--card-shadow);
    }

    .quick-nav .nav-item i {
        color: var(--primary-color);
        font-size: 1rem;
    }

    .quick-nav .nav-item .badge-count {
        background: var(--primary-color);
        color: white;
        border-radius: 50px;
        padding: 0.1rem 0.6rem;
        font-size: 0.65rem;
        margin-left: 4px;
    }

    .quick-nav .nav-item .badge-count.danger {
        background: var(--danger-color);
    }
    .quick-nav .nav-item .badge-count.warning {
        background: var(--warning-color);
    }
    .quick-nav .nav-item .badge-count.success {
        background: var(--success-color);
    }

    .quick-nav .nav-item.active-filter {
        border-color: var(--primary-color);
        background: #eef2ff;
    }

    /* ============================================
       STAT CARDS
       ============================================ */
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        border: 2px solid var(--border-color);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        height: 100%;
        cursor: pointer;
        text-decoration: none;
        display: block;
        color: inherit;
    }

    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
        border-color: var(--primary-color);
    }

    .stat-card .stat-icon {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 2.5rem;
        opacity: 0.12;
        color: var(--primary-color);
    }

    .stat-card .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        line-height: 1.2;
    }

    .stat-card .stat-number .currency {
        font-size: 1.2rem;
        color: var(--text-muted);
    }

    .stat-card .stat-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
        font-weight: 500;
    }

    .stat-card .stat-sub {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-top: 0.3rem;
    }

    .stat-card .stat-value-sm {
        font-size: 0.85rem;
        font-weight: 600;
    }

    .stat-card.primary .stat-icon { color: var(--primary-color); }
    .stat-card.success .stat-icon { color: var(--success-color); }
    .stat-card.warning .stat-icon { color: var(--warning-color); }
    .stat-card.danger .stat-icon { color: var(--danger-color); }
    .stat-card.info .stat-icon { color: var(--info-color); }

    /* KPI Grid */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 0.75rem;
    }

    .kpi-item {
        background: white;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        text-align: center;
        transition: var(--transition);
    }

    .kpi-item:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: var(--card-shadow);
    }

    .kpi-item .kpi-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-dark);
    }

    .kpi-item .kpi-label {
        font-size: 0.65rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        font-weight: 600;
    }

    .kpi-item .kpi-sub {
        font-size: 0.6rem;
        color: var(--text-muted);
    }

    /* ============================================
       ALERT CARDS
       ============================================ */
    .alert-card {
        border-left: 4px solid;
        padding: 0.8rem 1rem;
        margin-bottom: 0.5rem;
        background: #f8fafc;
        border-radius: 8px;
        transition: var(--transition);
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .alert-card:hover {
        background: #f1f5f9;
        transform: translateX(4px);
    }

    .alert-card.danger { border-left-color: var(--danger-color); }
    .alert-card.warning { border-left-color: var(--warning-color); }
    .alert-card.info { border-left-color: var(--info-color); }
    .alert-card.success { border-left-color: var(--success-color); }

    .alert-card .alert-title {
        font-weight: 600;
        color: var(--text-dark);
    }

    .alert-card .alert-detail {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .alert-card .alert-badge {
        font-size: 0.65rem;
        padding: 0.1rem 0.6rem;
        border-radius: 50px;
        font-weight: 600;
    }

    /* ============================================
       CARDS
       ============================================ */
    .card-custom {
        background: white;
        border-radius: 16px;
        border: 2px solid var(--border-color);
        overflow: hidden;
        transition: var(--transition);
        height: 100%;
    }

    .card-custom:hover {
        border-color: var(--primary-color);
        box-shadow: var(--card-shadow);
    }

    .card-custom .card-header-custom {
        padding: 1rem 1.5rem;
        border-bottom: 2px solid var(--border-color);
        background: #f8fafc;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-custom .card-header-custom i {
        color: var(--primary-color);
        margin-right: 8px;
    }

    .card-custom .card-body-custom {
        padding: 1.25rem 1.5rem;
    }

    /* ============================================
       TOP ITEMS
       ============================================ */
    .top-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .top-item:last-child {
        border-bottom: none;
    }

    .top-item .rank {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--primary-gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .top-item .rank.gold { background: #f59e0b; }
    .top-item .rank.silver { background: #94a3b8; }
    .top-item .rank.bronze { background: #cd7f32; }

    .top-item .item-info {
        flex: 1;
    }

    .top-item .item-info .name {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.85rem;
    }

    .top-item .item-info .meta {
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    .top-item .item-qty {
        font-weight: 700;
        color: var(--primary-color);
        font-size: 0.85rem;
        white-space: nowrap;
    }

    /* ============================================
       ACTIVE FILTER INDICATOR
       ============================================ */
    .active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        padding: 0.5rem 0;
        margin-bottom: 0.5rem;
    }

    .active-filters .filter-tag {
        background: #eef2ff;
        color: var(--primary-color);
        padding: 0.2rem 0.8rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .active-filters .filter-tag .remove {
        cursor: pointer;
        color: var(--danger-color);
        font-weight: 700;
    }

    .active-filters .filter-tag .remove:hover {
        color: #dc2626;
    }

    /* ============================================
       RESPONSIVE
       ============================================ */
    @media (max-width: 768px) {
        .dashboard-header {
            padding: 1rem 1.25rem;
        }

        .dashboard-header h1 {
            font-size: 1.2rem;
        }

        .filter-row {
            flex-direction: column;
        }

        .filter-row .filter-group {
            min-width: 100%;
        }

        .filter-row .filter-actions {
            width: 100%;
            flex-direction: column;
        }

        .filter-row .filter-actions .btn-filter,
        .filter-row .filter-actions .btn-reset {
            width: 100%;
            text-align: center;
        }

        .quick-nav {
            justify-content: center;
        }

        .quick-nav .nav-item {
            padding: 0.4rem 0.8rem;
            font-size: 0.75rem;
        }

        .stat-card .stat-number {
            font-size: 1.5rem;
        }

        .kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .stat-card {
            padding: 1rem;
        }

        .stat-card .stat-number {
            font-size: 1.2rem;
        }

        .card-custom .card-header-custom {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }

        .card-custom .card-body-custom {
            padding: 0.75rem 1rem;
        }

        .kpi-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ============================================
       ANIMATIONS
       ============================================ */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-in {
        animation: fadeInUp 0.5s ease forwards;
    }

    .animate-in:nth-child(1) { animation-delay: 0.05s; }
    .animate-in:nth-child(2) { animation-delay: 0.10s; }
    .animate-in:nth-child(3) { animation-delay: 0.15s; }
    .animate-in:nth-child(4) { animation-delay: 0.20s; }
    .animate-in:nth-child(5) { animation-delay: 0.25s; }
    .animate-in:nth-child(6) { animation-delay: 0.30s; }
    .animate-in:nth-child(7) { animation-delay: 0.35s; }
    .animate-in:nth-child(8) { animation-delay: 0.40s; }
</style>

<div class="container-fluid">

    <!-- ==========================================
    DASHBOARD HEADER
    ========================================== -->
    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>
                    <i class="fas fa-chart-pie"></i>
                    Inventory Dashboard
                </h1>
                <div class="subtitle">
                    Welcome back, {{ auth()->user()->name ?? 'Admin' }}!
                    <span class="badge-config ms-2">
                        <i class="fas fa-cog"></i>
                        {{ $configuration->configuration_name ?? 'Configuration Required' }}
                    </span>
                    @if($configuration)
                        <span class="badge-config ms-1">
                            <i class="fas fa-calculator"></i>
                            {{ str_replace('_', ' ', $configuration->costing_method ?? 'N/A') }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <span class="badge-config">
                    <i class="fas fa-calendar-alt"></i>
                    {{ now()->format('d M Y, h:i A') }}
                </span>
                <!-- <span class="badge-config ms-1" id="lastUpdate">
                    <i class="fas fa-sync-alt"></i>
                    Live
                </span> -->
                <span class="badge-config ms-1" style="background: rgba(255,255,255,0.15);">
                    <i class="fas fa-clock"></i>
                    {{ $periodStats['period_label'] ?? 'Today' }}
                </span>
            </div>
        </div>
    </div>

    <!-- ==========================================
    QUICK NAVIGATION
    ========================================== -->
    <div class="quick-nav">
        <a href="{{ route('inventory.configuration') }}" class="nav-item">
            <i class="fas fa-cogs"></i> Configurations
            <span class="badge-count">{{ $configurations-> count() }}</span>
        </a>
        <a href="{{ route('inventory.categories.index') }}" class="nav-item">
            <i class="fas fa-folder-tree"></i> Categories
            <span class="badge-count">{{ $totalCategories ?? 0 }}</span>
        </a>
        <a href="{{ route('inventory.subcategories.index') }}" class="d-none nav-item">
            <i class="fas fa-tags"></i> Sub Categories
        </a>
        <a href="{{ route('inventory.warehouses.index') }}" class="nav-item">
            <i class="fas fa-warehouse"></i> Warehouses
            <span class="badge-count">{{ $totalWarehouses ?? 0 }}</span>
        </a>
        <a href="{{ route('inventory.stores.index') }}" class="nav-item">
            <i class="fas fa-warehouse"></i> Stores
            <span class="badge-count">{{ $totalStores ?? 0 }}</span>
        </a>
        <a href="{{ route('inventory.items.index') }}" class="nav-item">
            <i class="fas fa-boxes"></i> Items
            <span class="badge-count">{{ $totalItems ?? 0 }}</span>
        </a>
        <a href="{{ route('inventory.transfers.index') }}" class="nav-item">
            <i class="fas fa-exchange-alt"></i> Stock Out
            @if(isset($pendingTransfers) && $pendingTransfers > 0)
                <span class="badge-count warning">{{ $pendingTransfers }}</span>
            @endif
        </a>
        <a href="{{ route('inventory.receipts.in.index') }}" class="nav-item">
            <i class="fas fa-sign-in-alt"></i> In Receipts
            @if(isset($pendingGRN) && $pendingGRN > 0)
                <span class="badge-count warning">{{ $pendingGRN }}</span>
            @endif
        </a>
        <a href="{{ route('inventory.receipts.out.index') }}" class="nav-item">
            <i class="fas fa-sign-out-alt"></i> Out Receipts
        </a>
    </div>

    <!-- ==========================================
    HIERARCHICAL FILTER BAR
    ========================================== -->
    <div class="filter-bar-advanced">
        <div class="filter-title">
            <i class="fas fa-sliders-h"></i> Filter Dashboard
            <span class="d-none" style="font-weight: 400; font-size: 0.75rem; color: var(--text-muted);">
                (Drill down to specific data)
            </span>
            <a href="{{ route('inventory.dashboard') }}" class="clear-all">
                <i class="fas fa-times-circle"></i> Clear All Filters
            </a>
        </div>

        <form method="GET" action="{{ route('inventory.dashboard') }}" id="filterForm">
            <div class="filter-row">
                <!-- Configuration -->
                <div class="filter-group">
                    <label for="filter_configuration"><i class="fas fa-cog"></i> Configuration</label>
                    <select name="configuration_id" id="filter_configuration" onchange="this.form.submit()">
                        <option value="">All Configurations</option>
                        @if(isset($configurations) && $configurations->count() > 0)
                            @foreach($configurations as $config)
                                <option value="{{ $config->id }}" {{ request('configuration_id') == $config->id ? 'selected' : '' }}>
                                    {{ $config->configuration_name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Category -->
                <div class="filter-group">
                    <label for="filter_category"><i class="fas fa-folder-tree"></i> Category</label>
                    <select name="category_id" id="filter_category" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @if(isset($filterCategories) && $filterCategories->count() > 0)
                            @foreach($filterCategories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Sub Category -->
                <div class="filter-group">
                    <label for="filter_subcategory"><i class="fas fa-tags"></i> Sub Category</label>
                    <select name="subcategory_id" id="filter_subcategory" onchange="this.form.submit()">
                        <option value="">All Sub Categories</option>
                        @if(isset($filterSubCategories) && $filterSubCategories->count() > 0)
                            @foreach($filterSubCategories as $subcategory)
                                <option value="{{ $subcategory->id }}" {{ request('subcategory_id') == $subcategory->id ? 'selected' : '' }}>
                                    {{ $subcategory->subcategory_name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Warehouse -->
                <div class="filter-group">
                    <label for="filter_warehouse"><i class="fas fa-warehouse"></i> Warehouse</label>
                    <select name="warehouse_id" id="filter_warehouse" onchange="this.form.submit()">
                        <option value="">All Warehouses</option>
                        @if(isset($filterWarehouses) && $filterWarehouses->count() > 0)
                            @foreach($filterWarehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->warehouse_name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Item -->
                <div class="filter-group">
                    <label for="filter_item"><i class="fas fa-box"></i> Item</label>
                    <select name="item_id" id="filter_item" onchange="this.form.submit()">
                        <option value="">All Items</option>
                        @if(isset($filterItems) && $filterItems->count() > 0)
                            @foreach($filterItems as $item)
                                <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->item_name }} ({{ $item->item_code }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Date Range -->
                <div class="filter-group" style="flex: 1.5;">
                    <label for="filter_period"><i class="fas fa-calendar-alt"></i> Period</label>
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        <select name="period" id="filter_period" onchange="toggleDateInputs()" style="flex: 1; min-width: 120px;">
                            <option value="today" {{ request('period', 'today') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>This Year</option>
                            <option value="financial" {{ request('period') == 'financial' ? 'selected' : '' }}>Financial Year</option>
                            <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                        <input type="date" name="from_date" id="filter_from_date" class="filter-date-input" 
                               value="{{ request('from_date', now()->subDays(30)->format('Y-m-d')) }}"
                               style="display: {{ request('period') == 'custom' ? 'inline-block' : 'none' }}; padding: 0.4rem 0.8rem; border: 2px solid var(--border-color); border-radius: 8px;">
                        <span id="date_to_label" style="display: {{ request('period') == 'custom' ? 'inline' : 'none' }}; color: var(--text-muted); font-weight: 600;">to</span>
                        <input type="date" name="to_date" id="filter_to_date" class="filter-date-input" 
                               value="{{ request('to_date', now()->format('Y-m-d')) }}"
                               style="display: {{ request('period') == 'custom' ? 'inline-block' : 'none' }}; padding: 0.4rem 0.8rem; border: 2px solid var(--border-color); border-radius: 8px;">
                    </div>
                </div>

                <!-- Actions -->
                <div class="filter-group">
                    <div class="filter-actions justify-content-end">
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-search"></i> Apply Filters
                        </button>
                        <a href="{{ route('inventory.dashboard') }}" class="btn-reset">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <!-- Active Filters Display -->
        @php
            $activeFilters = [];
            if (request('configuration_id')) {
                $config = $configurations->firstWhere('id', request('configuration_id'));
                if ($config) $activeFilters[] = 'Configuration: ' . $config->configuration_name;
            }
            if (request('category_id')) {
                $cat = $filterCategories->firstWhere('id', request('category_id'));
                if ($cat) $activeFilters[] = 'Category: ' . $cat->category_name;
            }
            if (request('subcategory_id')) {
                $sub = $filterSubCategories->firstWhere('id', request('subcategory_id'));
                if ($sub) $activeFilters[] = 'Sub Category: ' . $sub->subcategory_name;
            }
            if (request('warehouse_id')) {
                $wh = $filterWarehouses->firstWhere('id', request('warehouse_id'));
                if ($wh) $activeFilters[] = 'Warehouse: ' . $wh->warehouse_name;
            }
            if (request('item_id')) {
                $item = $filterItems->firstWhere('id', request('item_id'));
                if ($item) $activeFilters[] = 'Item: ' . $item->item_name;
            }
        @endphp

        @if(count($activeFilters) > 0)
            <div class="active-filters">
                @foreach($activeFilters as $filter)
                    <span class="filter-tag">
                        <i class="fas fa-check-circle"></i>
                        {{ $filter }}
                        <span class="remove" onclick="removeFilter('{{ strtolower(str_replace(' ', '_', explode(': ', $filter)[0])) }}')">&times;</span>
                    </span>
                @endforeach
            </div>
        @endif
    </div>

    <!-- ==========================================
    STATS ROW 1 - MAIN METRICS
    ========================================== -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3 animate-in">
            <a href="{{ route('inventory.items.index') }}" class="stat-card primary">
                <div class="stat-icon"><i class="fas fa-boxes"></i></div>
                <div class="stat-number">{{ $totalItems ?? 0 }}</div>
                <div class="stat-label">Total Items</div>
                <div class="stat-sub">
                    <span class="text-success">{{ $activeItems ?? 0 }}</span> active
                    <span class="text-danger">| {{ $inactiveItems ?? 0 }}</span> inactive
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 mb-3 animate-in">
            <a href="{{ route('inventory.categories.index') }}" class="stat-card success">
                <div class="stat-icon"><i class="fas fa-folder-tree"></i></div>
                <div class="stat-number">{{ $totalCategories ?? 0 }}</div>
                <div class="stat-label">Categories</div>
                <div class="stat-sub">
                    <span class="text-success">{{ $activeCategories ?? 0 }}</span> active
                    <span class="text-danger">| {{ $inactiveCategories ?? 0 }}</span> inactive
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 mb-3 animate-in">
            <a href="{{ route('inventory.warehouses.index') }}" class="stat-card warning">
                <div class="stat-icon"><i class="fas fa-warehouse"></i></div>
                <div class="stat-number">{{ $totalWarehouses ?? 0 }}</div>
                <div class="stat-label">Warehouses</div>
                <div class="stat-sub">
                    <span class="text-success">{{ $activeWarehouses ?? 0 }}</span> active
                    <span class="text-danger">| {{ $inactiveWarehouses ?? 0 }}</span> inactive
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 mb-3 animate-in">
            <div class="stat-card info">
                <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-number">{{ $alertSummary['total'] ?? 0 }}</div>
                <div class="stat-label">Total Alerts</div>
                <div class="stat-sub">
                    <span class="text-danger">{{ $alertSummary['out_of_stock'] ?? 0 }}</span> out of stock
                    <span class="text-warning">| {{ $alertSummary['low_stock'] ?? 0 }}</span> low stock
                    <span class="text-danger">| {{ $alertSummary['expired'] ?? 0 }}</span> expired
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================
    STATS ROW 2 - STOCK VALUE
    ========================================== -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3 animate-in">
            <div class="stat-card" style="border-color: #10b981;">
                <div class="stat-icon" style="color: #10b981;"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stat-number">
                    <span class="currency">₹</span>{{ number_format($totalStockValue ?? 0, 2) }}
                </div>
                <div class="stat-label">Total Stock Value</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3 animate-in">
            <div class="stat-card" style="border-color: #f59e0b;">
                <div class="stat-icon" style="color: #f59e0b;"><i class="fas fa-cubes"></i></div>
                <div class="stat-number">{{ number_format($totalStockQuantity ?? 0, 2) }}</div>
                <div class="stat-label">Total Stock Quantity</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3 animate-in">
            <div class="stat-card" style="border-color: #3b82f6;">
                <div class="stat-icon" style="color: #3b82f6;"><i class="fas fa-sign-in-alt"></i></div>
                <div class="stat-number">{{ $periodStats['receipts_in'] ?? 0 }}</div>
                <div class="stat-label">In Receipts</div>
                <div class="stat-sub">{{ $periodStats['period_label'] ?? 'Selected Period' }}</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3 animate-in">
            <div class="stat-card" style="border-color: #8b5cf6;">
                <div class="stat-icon" style="color: #8b5cf6;"><i class="fas fa-sign-out-alt"></i></div>
                <div class="stat-number">{{ $periodStats['receipts_out'] ?? 0 }}</div>
                <div class="stat-label">Out Receipts</div>
                <div class="stat-sub">{{ $periodStats['period_label'] ?? 'Selected Period' }}</div>
            </div>
        </div>
    </div>

    <!-- ==========================================
    TODAY'S KPIs
    ========================================== -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <span>
                        <i class="fas fa-chart-line" style="color: #f59e0b;"></i>
                        Today's Overview ({{ $periodStats['period_label'] ?? 'Selected Period' }})
                    </span>
                    <span class="text-muted" style="font-size: 0.75rem;">
                        <i class="fas fa-clock"></i> {{ now()->format('d M Y') }}
                    </span>
                </div>
                <div class="card-body-custom">
                    <div class="kpi-grid">
                        <div class="kpi-item">
                            <div class="kpi-value" style="color: #10b981;">₹{{ number_format($todayStats['purchase'] ?? 0, 2) }}</div>
                            <div class="kpi-label">Purchase</div>
                            <div class="kpi-sub">Today's purchases</div>
                        </div>
                        <div class="kpi-item">
                            <div class="kpi-value" style="color: #3b82f6;">₹{{ number_format($todayStats['sale'] ?? 0, 2) }}</div>
                            <div class="kpi-label">Sale</div>
                            <div class="kpi-sub">Today's sales</div>
                        </div>
                        <div class="kpi-item">
                            <div class="kpi-value" style="color: #f59e0b;">{{ number_format($todayStats['consumption'] ?? 0, 2) }}</div>
                            <div class="kpi-label">Consumption</div>
                            <div class="kpi-sub">Items consumed today</div>
                        </div>
                        <div class="kpi-item">
                            <div class="kpi-value" style="color: #ef4444;">{{ number_format($todayStats['damage'] ?? 0, 2) }}</div>
                            <div class="kpi-label">Damage</div>
                            <div class="kpi-sub">Items damaged today</div>
                        </div>
                        <div class="kpi-item">
                            <div class="kpi-value" style="color: #dc2626;">{{ $todayStats['expiry'] ?? 0 }}</div>
                            <div class="kpi-label">Expiry</div>
                            <div class="kpi-sub">Items expiring today</div>
                        </div>
                        <div class="kpi-item">
                            <div class="kpi-value" style="color: #dc2626;">₹{{ number_format($todayStats['expiry_value'] ?? 0, 2) }}</div>
                            <div class="kpi-label">Expiry Value</div>
                            <div class="kpi-sub">Value of expiring items</div>
                        </div>
                        <div class="kpi-item">
                            <div class="kpi-value" style="color: #f59e0b;">{{ $pendingGRN ?? 0 }}</div>
                            <div class="kpi-label">Pending GRN</div>
                            <div class="kpi-sub">Awaiting completion</div>
                        </div>
                        <div class="kpi-item">
                            <div class="kpi-value" style="color: #f59e0b;">{{ $pendingTransfers ?? 0 }}</div>
                            <div class="kpi-label">Pending Transfers</div>
                            <div class="kpi-sub">Awaiting approval</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================
    ALERTS SECTION
    ========================================== -->
    <div class="row mb-4">
        <div class="col-md-6 animate-in">
            <div class="card-custom">
                <div class="card-header-custom">
                    <span>
                        <i class="fas fa-exclamation-triangle" style="color: #f59e0b;"></i>
                        Low Stock Alerts
                        <span class="badge bg-warning text-dark ms-2">{{ $lowStockCount ?? 0 }}</span>
                    </span>
                    <a href="{{ route('inventory.items.index') }}?filter=low_stock" class="view-all">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body-custom">
                    @if(isset($lowStockItems) && $lowStockItems->count() > 0)
                        @foreach($lowStockItems->take(5) as $item)
                            <a href="{{ route('inventory.items.view', $item->id) }}" class="alert-card warning">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="alert-title">{{ $item->item_name }}</div>
                                        <div class="alert-detail">
                                            Available: <strong class="text-danger">{{ number_format($item->available_stock, 2) }}</strong>
                                            | Reorder Level: {{ number_format($item->reorder_level ?? 10, 2) }}
                                            | Warehouse: {{ $item->warehouse->warehouse_name ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <span class="alert-badge bg-warning text-dark">Low Stock</span>
                                </div>
                            </a>
                        @endforeach
                        @if($lowStockItems->count() > 5)
                            <div class="text-center mt-2">
                                <a href="{{ route('inventory.items.index') }}?filter=low_stock" class="text-primary">
                                    + {{ $lowStockItems->count() - 5 }} more low stock items
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-muted text-center py-3 mb-0">
                            <i class="fas fa-check-circle text-success"></i>
                            No low stock items. Great!
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 animate-in">
            <div class="card-custom">
                <div class="card-header-custom">
                    <span>
                        <i class="fas fa-clock" style="color: #ef4444;"></i>
                        Expiry Alerts
                        <span class="badge bg-danger ms-2">{{ ($expiredItems ?? 0) + ($nearExpiry30Days ?? 0) }}</span>
                    </span>
                    <a href="{{ route('inventory.items.index') }}?filter=expired" class="view-all">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body-custom">
                    <!-- Expiry Breakdown -->
                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Near Expiry (3 days):</span>
                                <span class="fw-bold text-warning">{{ $nearExpiry3Days ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Near Expiry (7 days):</span>
                                <span class="fw-bold text-warning">{{ $nearExpiry7Days ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Near Expiry (15 days):</span>
                                <span class="fw-bold text-warning">{{ $nearExpiry15Days ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Near Expiry (30 days):</span>
                                <span class="fw-bold text-warning">{{ $nearExpiry30Days ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                    @if(isset($expiredItemsList) && $expiredItemsList->count() > 0)
                        @foreach($expiredItemsList->take(3) as $item)
                            <a href="{{ route('inventory.items.view', $item->id) }}" class="alert-card danger">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="alert-title">{{ $item->item_name }}</div>
                                        <div class="alert-detail">
                                            Expired: <strong class="text-danger">{{ $item->expiry_date->format('d M Y') }}</strong>
                                            | Stock: {{ number_format($item->available_stock, 2) }}
                                        </div>
                                    </div>
                                    <span class="alert-badge bg-danger text-white">Expired</span>
                                </div>
                            </a>
                        @endforeach
                    @endif

                    @if(isset($expiringSoonItemsList) && $expiringSoonItemsList->count() > 0)
                        @foreach($expiringSoonItemsList->take(3) as $item)
                            <a href="{{ route('inventory.items.view', $item->id) }}" class="alert-card warning">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="alert-title">{{ $item->item_name }}</div>
                                        <div class="alert-detail">
                                            Expires: <strong class="text-warning">{{ $item->expiry_date->format('d M Y') }}</strong>
                                            ({{ $item->days_until_expiry ?? 0 }} days)
                                            | Stock: {{ number_format($item->available_stock, 2) }}
                                        </div>
                                    </div>
                                    <span class="alert-badge bg-warning text-dark">Expiring Soon</span>
                                </div>
                            </a>
                        @endforeach
                    @endif

                    @if(($expiredItemsList->count() ?? 0) == 0 && ($expiringSoonItemsList->count() ?? 0) == 0)
                        <p class="text-muted text-center py-3 mb-0">
                            <i class="fas fa-check-circle text-success"></i>
                            No expiry alerts. All items are valid!
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================
    TOP SELLING & MOST USED PRODUCTS
    ========================================== -->
    <div class="row mb-4">
        <div class="col-lg-6 animate-in">
            <div class="card-custom">
                <div class="card-header-custom">
                    <span>
                        <i class="fas fa-trophy" style="color: #f59e0b;"></i>
                        Top Selling Products
                    </span>
                    <span class="text-muted" style="font-size: 0.75rem;">
                        {{ $periodStats['period_label'] ?? 'Today' }}
                    </span>
                </div>
                <div class="card-body-custom">
                    @if(isset($topSellingProducts) && $topSellingProducts->count() > 0)
                        @foreach($topSellingProducts->take(5) as $index => $product)
                            <div class="top-item">
                                <div class="rank 
                                    @if($index == 0) gold 
                                    @elseif($index == 1) silver 
                                    @elseif($index == 2) bronze 
                                    @endif">
                                    {{ $index + 1 }}
                                </div>
                                <div class="item-info">
                                    <div class="name">{{ $product->item->item_name ?? 'N/A' }}</div>
                                    <div class="meta">
                                        {{ $product->item->category->category_name ?? 'N/A' }}
                                        | {{ $product->item->item_code ?? '' }}
                                    </div>
                                </div>
                                <div class="item-qty">
                                    {{ number_format($product->total_sold, 2) }} sold
                                    <br>
                                    <span style="font-weight: 400; color: var(--text-muted); font-size: 0.6rem;">
                                        ₹{{ number_format($product->total_revenue, 2) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3 mb-0">No sales data available</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 animate-in">
            <div class="card-custom">
                <div class="card-header-custom">
                    <span>
                        <i class="fas fa-fire" style="color: #ef4444;"></i>
                        Most Used Products
                    </span>
                    <span class="text-muted" style="font-size: 0.75rem;">
                        {{ $periodStats['period_label'] ?? 'Today' }}
                    </span>
                </div>
                <div class="card-body-custom">
                    @if(isset($mostUsedProducts) && $mostUsedProducts->count() > 0)
                        @foreach($mostUsedProducts->take(5) as $index => $product)
                            <div class="top-item">
                                <div class="rank">
                                    {{ $index + 1 }}
                                </div>
                                <div class="item-info">
                                    <div class="name">{{ $product->item->item_name ?? 'N/A' }}</div>
                                    <div class="meta">
                                        {{ $product->item->category->category_name ?? 'N/A' }}
                                        | {{ $product->item->item_code ?? '' }}
                                    </div>
                                </div>
                                <div class="item-qty">
                                    {{ number_format($product->total_used, 2) }} used
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3 mb-0">No consumption data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================
    WAREHOUSE UTILIZATION
    ========================================== -->
    @if(isset($warehouses) && $warehouses->count() > 0)
    <div class="row mb-4 animate-in">
        <div class="col-md-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <span>
                        <i class="fas fa-chart-bar"></i>
                        Warehouse Utilization
                    </span>
                    <a href="{{ route('inventory.warehouses.index') }}" class="view-all">
                        Manage <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        @foreach($warehouses as $warehouse)
                            <div class="col-md-4 col-lg-3 mb-3">
                                <div class="text-center">
                                    <a href="{{ route('inventory.warehouses.stock-summary', $warehouse->id) }}" style="text-decoration: none; color: inherit;">
                                        <h6 class="mb-1">{{ $warehouse->warehouse_name }}</h6>
                                        <small class="text-muted">{{ $warehouse->warehouse_code }}</small>

                                        <div style="height: 100px; display: flex; align-items: flex-end; justify-content: center; padding: 10px 0;">
                                            @php
                                                $percentage = $warehouse->capacity > 0
                                                    ? min(100, ($warehouse->current_utilization / $warehouse->capacity) * 100)
                                                    : 0;
                                                $color = $percentage > 80 ? '#ef4444' : ($percentage > 60 ? '#f59e0b' : '#10b981');
                                            @endphp
                                            <div style="width: 50px; height: {{ max($percentage, 5) }}%; background: {{ $color }}; border-radius: 4px 4px 0 0; min-height: 10px; transition: height 0.8s ease;"></div>
                                        </div>

                                        <div class="d-flex justify-content-between small">
                                            <span class="text-muted">Utilized</span>
                                            <span class="fw-bold" style="color: {{ $color }};">{{ round($percentage, 1) }}%</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar" style="width: {{ $percentage }}%; background: {{ $color }};"></div>
                                        </div>
                                        <small class="text-muted">
                                            {{ number_format($warehouse->current_utilization ?? 0) }} / {{ number_format($warehouse->capacity ?? 0) }} units
                                        </small>
                                        <br>
                                        <span class="badge bg-{{ $warehouse->status ? 'success' : 'danger' }}">
                                            {{ $warehouse->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- ==========================================
    RECENT ACTIVITIES - 3 COLUMN LAYOUT
    ========================================== -->
    <div class="row mb-4">
        <div class="col-lg-4 animate-in">
            <div class="card-custom">
                <div class="card-header-custom">
                    <span>
                        <i class="fas fa-sign-out-alt" style="color: #8b5cf6;"></i>
                        Recent Out Receipts
                    </span>
                    <a href="{{ route('inventory.receipts.out.index') }}" class="view-all">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body-custom" style="max-height: 300px; overflow-y: auto;">
                    @if(isset($recentReceiptsOut) && $recentReceiptsOut->count() > 0)
                        @foreach($recentReceiptsOut as $receipt)
                            <a href="{{ route('inventory.receipts.out.show', $receipt->id) }}" style="text-decoration: none; color: inherit;">
                                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                    <div>
                                        <strong>{{ $receipt->receipt_number }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $receipt->item->item_name ?? 'N/A' }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge {{ $receipt->status_badge }}">{{ $receipt->status_text }}</span>
                                        <br>
                                        <small class="text-muted">{{ $receipt->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3 mb-0">No recent out receipts</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4 animate-in">
            <div class="card-custom">
                <div class="card-header-custom">
                    <span>
                        <i class="fas fa-sign-in-alt" style="color: #3b82f6;"></i>
                        Recent In Receipts
                    </span>
                    <a href="{{ route('inventory.receipts.in.index') }}" class="view-all">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body-custom" style="max-height: 300px; overflow-y: auto;">
                    @if(isset($recentReceiptsIn) && $recentReceiptsIn->count() > 0)
                        @foreach($recentReceiptsIn as $receipt)
                            <a href="{{ route('inventory.receipts.in.show', $receipt->id) }}" style="text-decoration: none; color: inherit;">
                                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                    <div>
                                        <strong>{{ $receipt->receipt_number }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $receipt->item->item_name ?? 'N/A' }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge {{ $receipt->status_badge }}">{{ $receipt->status_text }}</span>
                                        <br>
                                        <small class="text-muted">{{ $receipt->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3 mb-0">No recent in receipts</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4 animate-in">
            <div class="card-custom">
                <div class="card-header-custom">
                    <span>
                        <i class="fas fa-exchange-alt" style="color: #f59e0b;"></i>
                        Recent Transfers
                    </span>
                    <a href="{{ route('inventory.transfers.index') }}" class="view-all">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body-custom" style="max-height: 300px; overflow-y: auto;">
                    @if(isset($recentTransfers) && $recentTransfers->count() > 0)
                        @foreach($recentTransfers as $transfer)
                            <a href="{{ route('inventory.transfers.show', $transfer->id) }}" style="text-decoration: none; color: inherit;">
                                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                    <div>
                                        <strong>{{ $transfer->transfer_code }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $transfer->item->item_name ?? 'N/A' }}</small>
                                        <br>
                                        <small class="text-muted">
                                            {{ $transfer->fromWarehouse->warehouse_name ?? 'N/A' }}
                                            <i class="fas fa-arrow-right text-muted"></i>
                                            {{ $transfer->toWarehouse->warehouse_name ?? 'N/A' }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge {{ $transfer->status_badge }}">{{ $transfer->status_text }}</span>
                                        <br>
                                        <small class="text-muted">{{ $transfer->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <p class="text-muted text-center py-3 mb-0">No recent transfers</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================
    RECENT STOCK MOVEMENTS
    ========================================== -->
    <div class="row">
        <div class="col-lg-12 animate-in">
            <div class="card-custom">
                <div class="card-header-custom">
                    <span>
                        <i class="fas fa-history" style="color: #3b82f6;"></i>
                        Recent Stock Movements
                    </span>
                    <span class="text-muted" style="font-size: 0.75rem;">
                        {{ $periodStats['period_label'] ?? 'Today' }}
                    </span>
                </div>
                <div class="card-body-custom" style="max-height: 400px; overflow-y: auto;">
                    @if(isset($recentMovements) && $recentMovements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Type</th>
                                        <th>Qty</th>
                                        <th>Warehouse</th>
                                        <th>User</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentMovements->take(10) as $movement)
                                        <tr>
                                            <td>
                                                <a href="{{ route('inventory.items.view', $movement->item_id) }}" style="color: var(--text-dark);">
                                                    {{ $movement->item->item_name ?? 'N/A' }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $movement->movement_type == 'IN' ? 'success' : 'danger' }}">
                                                    {{ $movement->movement_type == 'IN' ? 'Stock In' : 'Stock Out' }}
                                                </span>
                                            </td>
                                            <td>{{ number_format($movement->quantity, 2) }}</td>
                                            <td>{{ $movement->warehouse->warehouse_name ?? 'N/A' }}</td>
                                            <td>{{ $movement->creator->name ?? 'System' }}</td>
                                            <td>
                                                <small class="text-muted">{{ $movement->created_at->diffForHumans() }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3 mb-0">No recent stock movements</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

<!-- ==========================================
SCRIPTS
========================================== -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        // Auto-refresh alerts every 60 seconds
        setInterval(function() {
            refreshAlerts();
        }, 60000);

        // Auto-refresh dashboard stats every 2 minutes
        setInterval(function() {
            refreshStats();
        }, 120000);
    });

    // ==========================================
    // TOGGLE DATE INPUTS
    // ==========================================

    function toggleDateInputs() {
        const period = document.getElementById('filter_period').value;
        const fromDate = document.getElementById('filter_from_date');
        const toDate = document.getElementById('filter_to_date');
        const toLabel = document.getElementById('date_to_label');

        if (period === 'custom') {
            fromDate.style.display = 'inline-block';
            toDate.style.display = 'inline-block';
            toLabel.style.display = 'inline';
        } else {
            fromDate.style.display = 'none';
            toDate.style.display = 'none';
            toLabel.style.display = 'none';
        }
    }

    // ==========================================
    // REMOVE FILTER
    // ==========================================

    function removeFilter(filterType) {
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);

        const paramMap = {
            'configuration': 'configuration_id',
            'category': 'category_id',
            'sub_category': 'subcategory_id',
            'warehouse': 'warehouse_id',
            'item': 'item_id'
        };

        const paramName = paramMap[filterType];
        if (paramName) {
            params.delete(paramName);
        }

        url.search = params.toString();
        window.location.href = url.toString();
    }

    // ==========================================
    // REFRESH ALERTS (AJAX)
    // ==========================================

    function refreshAlerts() {
        $.ajax({
            url: '{{ route("inventory.dashboard.alerts") }}',
            method: 'GET',
            success: function(data) {
                const lastUpdate = document.getElementById('lastUpdate');
                if (lastUpdate) {
                    lastUpdate.innerHTML = '<i class="fas fa-sync-alt"></i> Updated just now';
                    setTimeout(() => {
                        lastUpdate.innerHTML = '<i class="fas fa-sync-alt"></i> Live';
                    }, 3000);
                }
            },
            error: function(xhr) {
                console.error('Error refreshing alerts:', xhr);
            }
        });
    }

    // ==========================================
    // REFRESH STATS (AJAX)
    // ==========================================

    function refreshStats() {
        $.ajax({
            url: '{{ route("inventory.dashboard.quick-stats") }}',
            method: 'GET',
            success: function(data) {
                console.log('Stats refreshed:', data);
            },
            error: function(xhr) {
                console.error('Error refreshing stats:', xhr);
            }
        });
    }

    // ==========================================
    // INITIALIZE
    // ==========================================

    document.addEventListener('DOMContentLoaded', function() {
        toggleDateInputs();

        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.get('configuration_id')) {
            document.querySelector('.nav-item i.fa-cogs')?.closest('.nav-item')?.classList.add('active-filter');
        }
        if (urlParams.get('category_id')) {
            document.querySelector('.nav-item i.fa-folder-tree')?.closest('.nav-item')?.classList.add('active-filter');
        }
        if (urlParams.get('warehouse_id')) {
            document.querySelector('.nav-item i.fa-warehouse')?.closest('.nav-item')?.classList.add('active-filter');
        }
    });
</script>
@endsection