@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
        :root {
        --primary-color: #4361ee;
        --primary-dark: #3a0ca3;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --border-radius-lg: 16px;
        --border-radius-md: 12px;
        --border-radius-sm: 8px;
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --success-gradient: linear-gradient(135deg, #34d399 0%, #10b981 100%);
        --warning-gradient: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        --danger-gradient: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
        --transition-smooth: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        --border-soft: #f1f5f9;
    }

    .page-header-modern {
        background: var(--primary-gradient);
        border-radius: 20px;
        padding: 1.75rem 2rem;
        box-shadow: var(--card-shadow);
        margin-bottom: 2rem;
        border: 1px solid rgba(99, 102, 241, 0.08);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
    }

    .page-header-modern .header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-header-modern .header-left .icon-wrapper {
        width: 52px;
        height: 52px;
        background: var(--primary-gradient);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 8px 24px rgba(99, 102, 241, 0.25);
    }

    .page-header-modern .header-left .title-section h4 {
        font-weight: 700;
        margin: 0;
        color: white;
        letter-spacing: -0.02em;
    }

    .page-header-modern .header-left .title-section .subtitle {
        color: white;
        font-size: 0.9rem;
        margin-top: 0.1rem;
    }

    .page-header-modern .header-left .title-section .subtitle span {
        background: #f1f5f9;
        padding: 0.15rem 0.7rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        color: #475569;
    }

    .btn-primary-gradient {
        background: var(--primary-gradient);
        border: none;
        color: white;
        border-radius: 12px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        font-size: 0.85rem;
        transition: var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 16px rgba(99, 102, 241, 0.2);
    }

    .btn-primary-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(99, 102, 241, 0.35);
        color: white;
    }

    .btn-secondary-gradient {
        background: #f1f5f9;
        border: none;
        color: #475569;
        border-radius: 12px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        font-size: 0.85rem;
        transition: var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-secondary-gradient:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
        color: #1e293b;
    }

    .card-modern {
        background: white;
        border-radius: 24px;
        border: none;
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }

    .card-modern .card-header {
        background: white;
        border-bottom: 1px solid var(--border-soft);
        padding: 1.25rem 1.75rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
    }

    .card-modern .card-body {
        padding: 0 !important;
    }

    /* Alert Styles */
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
        background: #d1fade;
        color: #166534;
    }

    .alert-modern.alert-danger {
        background: #fef2f2;
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }

    .alert-modern i {
        font-size: 1.25rem;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Table */
    .table-responsive-modern {
        overflow-x: auto;
        border-radius: 16px;
        border: 1px solid var(--border-soft);
    }

    .table-custom {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .table-custom thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e9edf2;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .table-custom tbody td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-soft);
        background-color: white;
    }

    .table-custom tbody tr:last-child td {
        border-bottom: none;
    }

    .table-custom tbody tr {
        transition: var(--transition-smooth);
    }

    .table-custom tbody tr:hover {
        background-color: #fafbff;
        box-shadow: inset 0 0 0 2px rgba(99, 102, 241, 0.06);
    }

    /* Icon Cell */
    .icon-cell {
        width: 50px;
    }

    .icon-cell .icon-wrapper-sm {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        transition: var(--transition-smooth);
    }

    .icon-cell .icon-wrapper-sm:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .icon-cell .icon-wrapper-sm img {
        width: 32px;
        height: 32px;
        object-fit: contain;
        border-radius: 6px;
    }

    .icon-cell .icon-wrapper-sm i {
        font-size: 1.5rem;
    }

    /* Category Name */
    .category-name {
        font-weight: 600;
        color: var(--text-dark);
    }

    .category-type-badge {
        font-size: 0.6rem;
        padding: 0.1rem 0.5rem;
        border-radius: 50px;
        background: #e0e7ff;
        color: #4338ca;
        font-weight: 600;
        margin-left: 0.3rem;
    }

    .category-type-badge.predefined {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .category-type-badge.custom {
        background: #fef3c7;
        color: #92400e;
    }

    .subcategory-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .subcategory-tags .tag {
        background: #f1f5f9;
        color: #475569;
        font-size: 0.65rem;
        padding: 0.1rem 0.5rem;
        border-radius: 50px;
        font-weight: 500;
    }

    /* Code Badge */
    .code-badge {
        background: #f1f5f9;
        color: #475569;
        font-weight: 600;
        padding: 0.2rem 0.8rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-family: 'Courier New', monospace;
    }

    /* Configuration */
    .config-badge {
        background: #e0e7ff;
        color: #4338ca;
        font-weight: 600;
        padding: 0.2rem 0.8rem;
        border-radius: 50px;
        font-size: 0.7rem;
    }

    .config-method {
        font-size: 0.7rem;
        color: var(--text-muted);
        display: block;
        margin-top: 0.1rem;
        text-transform: capitalize;
    }

    /* Stats */
    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.2rem 0.7rem;
        border-radius: 50px;
    }

    .stat-badge.items {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .stat-badge.subcats {
        background: #fef3c7;
        color: #92400e;
    }

    .stat-badge.empty {
        color: var(--text-muted);
        font-weight: 400;
        font-size: 0.7rem;
    }

    /* Status Badge - Clickable */
    .status-badge {
        font-weight: 600;
        padding: 0.25rem 0.9rem;
        border-radius: 50px;
        font-size: 0.7rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        cursor: pointer;
        transition: var(--transition-smooth);
        border: none;
    }

    .status-badge.active {
        background: #dcfce7;
        color: #15803d;
    }

    .status-badge.active:hover {
        background: #bbf7d0;
        transform: scale(1.05);
    }

    .status-badge.inactive {
        background: #fee2e2;
        color: #b91c1c;
    }

    .status-badge.inactive:hover {
        background: #fecaca;
        transform: scale(1.05);
    }

    /* Action Buttons */
    .action-group {
        display: flex;
        gap: 0.4rem;
        flex-wrap: wrap;
    }

    .btn-action {
        border: none;
        border-radius: 10px;
        padding: 0.3rem 0.8rem;
        font-size: 0.7rem;
        font-weight: 600;
        transition: var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .btn-action.btn-view {
        background: #e0e7ff;
        color: #4338ca;
    }
    .btn-action.btn-view:hover {
        background: #c7d2fe;
        transform: translateY(-1px);
    }

    .btn-action.btn-logs {
        background: #e0e7ff;
        color: #4338ca;
    }
    .btn-action.btn-logs:hover {
        background: #c7d2fe;
        transform: translateY(-1px);
    }

    .btn-action.btn-status-toggle {
        background: #fef3c7;
        color: #92400e;
    }
    .btn-action.btn-status-toggle:hover {
        background: #fde68a;
        transform: translateY(-1px);
    }

    /* Hidden Edit/Delete buttons (for future use) */
    .btn-action.btn-edit-hidden {
        background: #fef3c7;
        color: #92400e;
        display: none !important;
    }
    .btn-action.btn-edit-hidden:hover {
        background: #fde68a;
        transform: translateY(-1px);
    }

    .btn-action.btn-delete-hidden {
        background: #fee2e2;
        color: #b91c1c;
        display: none !important;
    }
    .btn-action.btn-delete-hidden:hover {
        background: #fecaca;
        transform: translateY(-1px);
    }

    .btn-action.btn-blocked-hidden {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
        cursor: not-allowed;
        opacity: 0.7;
        display: none !important;
    }

    /* Show hidden buttons for super admin - future implementation */
    .show-edit-delete .btn-action.btn-edit-hidden,
    .show-edit-delete .btn-action.btn-delete-hidden,
    .show-edit-delete .btn-action.btn-blocked-hidden {
        display: inline-flex !important;
    }

    /* Empty State */
    .empty-state-modern {
        text-align: center;
        padding: 3.5rem 1.5rem;
    }

    .empty-state-modern .empty-icon {
        font-size: 3.5rem;
        color: #e2e8f0;
        margin-bottom: 1rem;
    }

    .empty-state-modern h5 {
        color: var(--text-dark);
        font-weight: 600;
    }

    .empty-state-modern p {
        color: var(--text-muted);
        max-width: 400px;
        margin: 0 auto;
    }

    /* Modal */
    .modal-modern .modal-content {
        border: none;
        border-radius: 24px;
        box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.3);
        overflow: hidden;
    }

    .modal-modern .modal-header {
        border-bottom: 2px solid #fef3c7;
        padding: 1.25rem 1.75rem;
        background: #fffbeb;
    }

    .modal-modern .modal-header .modal-title {
        font-weight: 700;
        color: #92400e;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .modal-modern .modal-body {
        padding: 1.75rem;
    }

    .modal-modern .modal-footer {
        border-top: 1px solid var(--border-soft);
        padding: 1rem 1.75rem;
        background: #fafbff;
        border-radius: 0 0 24px 24px;
    }

    .blocker-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        background: #fef3c7;
        border-radius: 12px;
        border-left: 4px solid #f59e0b;
        margin-bottom: 0.75rem;
    }

    .blocker-item:last-child {
        margin-bottom: 0;
    }

    .blocker-item i {
        color: #92400e;
        font-size: 1.1rem;
    }

    .blocker-item .blocker-text {
        color: var(--text-dark);
        font-size: 0.9rem;
    }

    .blocker-item .blocker-text strong {
        color: #92400e;
    }

    .blocker-item .blocker-text .hint {
        font-size: 0.75rem;
        color: var(--text-muted);
        display: block;
        margin-top: 0.1rem;
    }

    .info-box {
        background: #f8fafc;
        padding: 1rem 1.25rem;
        border-radius: 12px;
        border: 1px solid var(--border-soft);
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .info-box i {
        color: #6366f1;
        font-size: 1.1rem;
        margin-top: 0.1rem;
    }

    .info-box p {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin: 0;
    }

    /* Search/Filter Bar */
    .filter-bar-simple {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid var(--border-soft);
    }

    .filter-bar-simple .filter-input {
        flex: 1;
        min-width: 200px;
        padding: 0.5rem 1rem;
        border: 2px solid var(--border-soft);
        border-radius: 10px;
        font-size: 0.85rem;
        background: white;
        transition: var(--transition-smooth);
    }

    .filter-bar-simple .filter-input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .filter-bar-simple .filter-select {
        padding: 0.5rem 1rem;
        border: 2px solid var(--border-soft);
        border-radius: 10px;
        font-size: 0.85rem;
        background: white;
        min-width: 150px;
        transition: var(--transition-smooth);
    }

    .filter-bar-simple .filter-select:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header-modern {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
            padding: 1.25rem;
        }

        .page-header-modern .header-left {
            flex-wrap: wrap;
        }

        .page-header-modern .header-left .icon-wrapper {
            width: 44px;
            height: 44px;
            font-size: 1.2rem;
        }

        .card-modern .card-header {
            flex-direction: column;
            gap: 0.75rem;
            align-items: stretch;
        }

        .card-modern .card-body {
            padding: 1rem;
        }

        .table-custom thead th,
        .table-custom tbody td {
            padding: 0.75rem 0.9rem;
        }

        .action-group {
            flex-direction: column;
            gap: 0.3rem;
        }

        .btn-action {
            justify-content: center;
            padding: 0.3rem 0.6rem;
            font-size: 0.65rem;
        }

        .stat-badge {
            font-size: 0.6rem;
            padding: 0.15rem 0.5rem;
        }

        .subcategory-tags .tag {
            font-size: 0.55rem;
            padding: 0.05rem 0.4rem;
        }
    }

    @media (max-width: 480px) {
        .page-header-modern .header-left .title-section h4 {
            font-size: 1.1rem;
        }

        .btn-primary-gradient,
        .btn-secondary-gradient {
            padding: 0.4rem 1rem;
            font-size: 0.75rem;
            width: 100%;
            justify-content: center;
        }

        .icon-cell .icon-wrapper-sm {
            width: 36px;
            height: 36px;
        }

        .icon-cell .icon-wrapper-sm i {
            font-size: 1.1rem;
        }

        .icon-cell .icon-wrapper-sm img {
            width: 26px;
            height: 26px;
        }
    }

    a {
        text-decoration: none;
    }
</style>

<div class="container-fluid px-0">

    {{-- Page Header --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="icon-wrapper">
                <i class="fas fa-folder-tree"></i>
            </div>
            <div class="title-section">
                <h4>Inventory Categories</h4>
                <div class="subtitle">
                    <span>{{ $categories->count() }} categorie(s)</span>
                    Manage your inventory organization
                </div>
            </div>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('inventory.dashboard') }}" class="d-none btn-secondary-gradient">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('inventory.categories.create') }}" class="btn-primary-gradient">
                <i class="fas fa-plus-circle"></i> Add Category
            </a>
        </div>
    </div>

    {{-- Search/Filter Bar --}}
    <div class="filter-bar-simple">
        <input type="text" id="searchCategory" class="filter-input" placeholder="🔍 Search categories by name or code...">
        <select id="filterConfiguration" class="filter-select">
            <option value="">All Configurations</option>
            @php
                $configs = $categories->pluck('configuration')->filter();
            @endphp
            @foreach($configs->unique('id') as $config)
                <option value="{{ $config->id }}">{{ $config->configuration_name }}</option>
            @endforeach
        </select>
        <select id="filterStatus" class="filter-select">
            <option value="">All Status</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
        <button onclick="resetFilters()" class="btn-secondary-gradient" style="padding: 0.4rem 1.2rem; font-size: 0.8rem;">
            <i class="fas fa-undo"></i> Reset
        </button>
    </div>

    {{-- Main Card --}}
    <div class="card-modern">
        <div class="card-header">
            <span style="font-weight: 600; color: var(--text-dark);">
                <i class="fas fa-list me-2" style="color: #6366f1;"></i> All Categories
            </span>
            <span style="font-size: 0.8rem; color: var(--text-muted);">
                Total: <strong style="color: var(--text-dark);">{{ $categories->count() }}</strong>
            </span>
        </div>

        <div class="card-body">

            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert-modern alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.7rem;"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert-modern alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.7rem;"></button>
                </div>
            @endif

            {{-- Table --}}
            <div class="table-responsive-modern">
                <table class="table-custom" id="categoryTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th class="sortable">Icon</th>
                            <th class="sortable">Category</th>
                            <th class="sortable">Sub Category</th>
                            <th class="sortable">Description</th>
                            <th class="sortable">Code</th>
                            <th class="sortable">Configuration</th>
                            <th class="sortable">Items</th>
                            <th class="sortable">Status</th>
                            <th class="sortable">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($categories as $category)
                        <tr data-config="{{ $category->configuration_id }}" data-status="{{ $category->status }}">
                            <td>{{ $loop->iteration }}</td>

                            {{-- Icon --}}
                            <td class="icon-cell">
                                <div class="icon-wrapper-sm">
                                    @if($category->icon_image)
                                        <img src="{{ asset('storage/'.$category->icon_image) }}" alt="{{ $category->category_name }}">
                                    @elseif($category->icon)
                                        <i class="fa-solid {{ $category->icon }}" style="color: {{ $category->icon_color ?? '#6366f1' }};"></i>
                                    @else
                                        <i class="fa-solid fa-folder" style="color: #94a3b8;"></i>
                                    @endif
                                </div>
                            </td>

                            {{-- Category Name with Type Badge --}}
                            <td>
                                <span class="category-name">{{ $category->category_name }}</span>
                                <span class="d-none category-type-badge {{ $category->isPredefined() ? 'predefined' : 'custom' }}">
                                    {{ $category->isPredefined() ? '📌 Predefined' : '✏️ Custom' }}
                                </span>
                            </td>

                            {{-- Sub Categories --}}
                            <td>
                                @if($category->subCategories->count() > 0)
                                <div class="subcategory-tags">
                                    @foreach($category->subCategories->take(3) as $sub)
                                    <span class="tag">{{ $sub->subcategory_name }}</span>
                                    @endforeach
                                    @if($category->subCategories->count() > 3)
                                    <span class="tag">+{{ $category->subCategories->count() - 3 }} more</span>
                                    @endif
                                </div>
                                @else
                                    <span class="text-muted" style="font-size: 0.75rem;">No sub categories</span>
                                @endif
                            </td>

                            {{-- Description --}}
                            <td>
                                <span class="category-description">{{ Str::limit($category->description, 50) }}</span>
                            </td>

                            {{-- Code --}}
                            <td>
                                <span class="code-badge">{{ $category->category_code }}</span>
                            </td>

                            {{-- Configuration --}}
                            <td>
                                @if($category->configuration)
                                    <span class="config-badge">
                                        <i class="fas fa-tag me-1"></i> {{ $category->configuration->configuration_name }}
                                    </span>
                                    <span class="config-method">
                                        <i class="fas fa-calculator me-1"></i> {{ str_replace('_', ' ', $category->configuration->costing_method) }}
                                    </span>
                                @else
                                    <span style="color: var(--text-muted); font-size: 0.8rem;">Not assigned</span>
                                @endif
                            </td>

                            {{-- Items Count --}}
                            <td>
                                @if($category->itemsCount() > 0)
                                    <span class="stat-badge items">
                                        <i class="fas fa-box"></i> {{ $category->itemsCount() }} Items
                                    </span>
                                @else
                                    <span class="stat-badge empty">No Items</span>
                                @endif
                            </td>

                            {{-- Status - Clickable Toggle --}}
                            <td>
                                <button 
                                    onclick="toggleCategoryStatus({{ $category->id }}, {{ $category->status ? '0' : '1' }})"
                                    class="status-badge {{ $category->status ? 'active' : 'inactive' }}"
                                    title="Click to toggle status"
                                >
                                    <i class="fas fa-circle" style="font-size: 0.4rem;"></i>
                                    {{ $category->status ? 'Active' : 'Inactive' }}
                                    <i class="fas fa-exchange-alt" style="font-size: 0.6rem; opacity: 0.6;"></i>
                                </button>
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="action-group">
                                    <a href="{{ route('inventory.categories.view', $category->id) }}"
                                       class="btn-action btn-view" title="View Category">
                                        <i class="fas fa-eye"></i> View
                                    </a>

                                    <a href="{{ route('inventory.categories.log', $category->id) }}"
                                       class="btn-action btn-logs" title="View Logs">
                                        <i class="fas fa-history"></i> Logs
                                    </a>

                                    <button 
                                        onclick="toggleCategoryStatus({{ $category->id }}, {{ $category->status ? '0' : '1' }})"
                                        class="btn-action btn-status-toggle" 
                                        title="{{ $category->status ? 'Deactivate' : 'Activate' }} Category"
                                    >
                                        <i class="fas {{ $category->status ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                        {{ $category->status ? 'Deactivate' : 'Activate' }}
                                    </button>

                                    {{-- Hidden Edit button (for future use) --}}
                                    <a href="{{ route('inventory.categories.edit', $category->id) }}"
                                       class="btn-action btn-edit-hidden" title="Edit Category">
                                        <i class="fas fa-pen"></i> Edit
                                    </a>

                                    {{-- Hidden Delete button (for future use) --}}
                                    @if($category->canBeDeleted())
                                        <button onclick="deleteCategory({{ $category->id }})"
                                                class="btn-action btn-delete-hidden" title="Delete Category">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    @else
                                        <button onclick="showCategoryDeleteBlocked({{ $category->id }})"
                                                class="btn-action btn-blocked-hidden" title="Cannot delete - has associated items or subcategories">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state-modern">
                                    <div class="empty-icon"><i class="fas fa-folder-open"></i></div>
                                    <h5>No Categories Found</h5>
                                    <p>Click "Add Category" to create your first inventory category and start organizing your items.</p>
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

{{-- Status Toggle Confirmation Modal --}}
<div class="modal fade modal-modern" id="statusToggleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exchange-alt" style="color: #f59e0b;"></i> 
                    Confirm Status Change
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; margin-bottom: 1.25rem;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 2.5rem; color: #f59e0b;"></i>
                </div>
                <h6 style="font-weight: 600; color: var(--text-dark); margin-bottom: 0.5rem; text-align: center;">
                    Are you sure you want to <span id="statusActionText" style="font-weight: 700;"></span> this category?
                </h6>
                <p style="color: var(--text-muted); text-align: center; font-size: 0.9rem;" id="statusDescription">
                    This will <span id="statusEffectText"></span> the category <strong id="statusCategoryName"></strong>.
                </p>
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p id="statusInfoText"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px; padding: 0.5rem 2rem; font-weight: 600;">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" id="confirmStatusToggle" style="border-radius: 12px; padding: 0.5rem 2rem; font-weight: 600;">
                    <i class="fas fa-check"></i> Confirm
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Delete Blocked Modal --}}
<div class="modal fade modal-modern" id="deleteBlockedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-lock"></i> Cannot Delete Category
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; margin-bottom: 1.25rem;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 2.5rem; color: #f59e0b;"></i>
                </div>
                <h6 style="font-weight: 600; color: var(--text-dark); margin-bottom: 1rem; text-align: center;">
                    This category cannot be deleted because it has associated data.
                </h6>

                <div id="blockerMessages">
                    <!-- Dynamic content -->
                </div>

                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>To delete this category, first delete all items and subcategories associated with it.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px; padding: 0.5rem 2rem; font-weight: 600;">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        // Real-time search
        $('#searchCategory').on('keyup', function() {
            filterTable();
        });

        // Configuration filter
        $('#filterConfiguration').on('change', function() {
            filterTable();
        });

        // Status filter
        $('#filterStatus').on('change', function() {
            filterTable();
        });
    });

    function filterTable() {
        const search = $('#searchCategory').val().toLowerCase();
        const config = $('#filterConfiguration').val();
        const status = $('#filterStatus').val();

        $('#categoryTable tbody tr').each(function() {
            const row = $(this);
            const name = row.find('.category-name').text().toLowerCase();
            const code = row.find('.code-badge').text().toLowerCase();
            const rowConfig = row.data('config');
            const rowStatus = row.data('status');

            let show = true;

            if (search && !name.includes(search) && !code.includes(search)) {
                show = false;
            }

            if (config && rowConfig != config) {
                show = false;
            }

            if (status !== '' && rowStatus != status) {
                show = false;
            }

            row.toggle(show);
        });
    }

    function resetFilters() {
        $('#searchCategory').val('');
        $('#filterConfiguration').val('');
        $('#filterStatus').val('');
        filterTable();
    }

    // ============================================
    // STATUS TOGGLE
    // ============================================
    let currentCategoryId = null;
    let currentNewStatus = null;

    function toggleCategoryStatus(id, newStatus) {
        currentCategoryId = id;
        currentNewStatus = newStatus;

        const modal = document.getElementById('statusToggleModal');
        const actionText = document.getElementById('statusActionText');
        const effectText = document.getElementById('statusEffectText');
        const infoText = document.getElementById('statusInfoText');
        const categoryName = document.getElementById('statusCategoryName');

        // Get category name from the row
        const row = $(`#categoryTable tbody tr`).filter(function() {
            return $(this).find('.btn-status-toggle').data('id') == id || 
                   $(this).find('button[onclick*="toggleCategoryStatus(' + id + '"]').length > 0;
        }).first();

        const name = row.find('.category-name').text() || 'this category';
        categoryName.textContent = `"${name.trim()}"`;

        if (newStatus == 1) {
            actionText.textContent = 'ACTIVATE';
            actionText.style.color = '#15803d';
            effectText.textContent = 'activate';
            infoText.textContent = 'Activated categories can be used for inventory items. Existing items will remain unaffected.';
        } else {
            actionText.textContent = 'DEACTIVATE';
            actionText.style.color = '#991b1b';
            effectText.textContent = 'deactivate';
            infoText.textContent = 'Deactivated categories will not be available for new items. Existing items will remain but cannot be assigned to this category.';
        }

        const bootstrap = window.bootstrap;
        const modalInstance = new bootstrap.Modal(modal);
        modalInstance.show();

        document.getElementById('confirmStatusToggle').onclick = function() {
            modalInstance.hide();

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('inventory.categories.toggle-status', '') }}/${currentCategoryId}`;
            form.innerHTML = `
                @csrf
                @method('POST')
                <input type="hidden" name="status" value="${currentNewStatus}">
            `;
            document.body.appendChild(form);
            form.submit();
        };
    }

    // ============================================
    // DELETE CATEGORY
    // ============================================

    function deleteCategory(id) {
        if (confirm('⚠️ Are you sure you want to delete this category?\n\nThis action cannot be undone.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('inventory.categories.delete', '') }}/${id}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    function showCategoryDeleteBlocked(id) {
        fetch(`/inventory/categories/check-delete/${id}`)
            .then(response => response.json())
            .then(data => {
                const modal = document.getElementById('deleteBlockedModal');
                const messagesContainer = document.getElementById('blockerMessages');

                messagesContainer.innerHTML = '';

                if (data.blockers && data.blockers.length > 0) {
                    data.blockers.forEach(blocker => {
                        const div = document.createElement('div');
                        div.className = 'blocker-item';
                        const iconMap = {
                            'sub_categories': 'fa-tags',
                            'items': 'fa-box'
                        };
                        div.innerHTML = `
                            <i class="fas ${iconMap[blocker.type] || 'fa-exclamation-circle'}"></i>
                            <div class="blocker-text">
                                <strong>${blocker.count} ${blocker.type.replace('_', ' ')}</strong> found
                                <span class="hint">${blocker.message}</span>
                            </div>
                        `;
                        messagesContainer.appendChild(div);
                    });
                } else {
                    messagesContainer.innerHTML = `
                        <div class="blocker-item">
                            <i class="fas fa-info-circle"></i>
                            <div class="blocker-text">
                                <strong>Associated data exists</strong>
                                <span class="hint">Please delete all associated items and subcategories first.</span>
                            </div>
                        </div>
                    `;
                }

                const bootstrap = window.bootstrap;
                if (bootstrap && bootstrap.Modal) {
                    const modalInstance = new bootstrap.Modal(modal);
                    modalInstance.show();
                } else {
                    modal.style.display = 'block';
                    modal.classList.add('show');
                    document.body.classList.add('modal-open');
                    const backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    document.body.appendChild(backdrop);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const modal = document.getElementById('deleteBlockedModal');
                const messagesContainer = document.getElementById('blockerMessages');
                messagesContainer.innerHTML = `
                    <div class="blocker-item">
                        <i class="fas fa-box"></i>
                        <div class="blocker-text">
                            <strong>Items or Subcategories exist</strong>
                            <span class="hint">Please delete all associated items and subcategories first.</span>
                        </div>
                    </div>
                `;
                const bootstrap = window.bootstrap;
                if (bootstrap && bootstrap.Modal) {
                    const modalInstance = new bootstrap.Modal(modal);
                    modalInstance.show();
                }
            });
    }

    // Close modal on backdrop click (fallback)
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('deleteBlockedModal');
        if (e.target === modal) {
            closeModal(modal);
        }
    });

    document.querySelector('#deleteBlockedModal .btn-close')?.addEventListener('click', function() {
        closeModal(document.getElementById('deleteBlockedModal'));
    });

    document.querySelector('#deleteBlockedModal .btn-secondary')?.addEventListener('click', function() {
        closeModal(document.getElementById('deleteBlockedModal'));
    });

    function closeModal(modal) {
        const bootstrap = window.bootstrap;
        if (bootstrap && bootstrap.Modal) {
            const instance = bootstrap.Modal.getInstance(modal);
            if (instance) {
                instance.hide();
                return;
            }
        }
        modal.style.display = 'none';
        modal.classList.remove('show');
        document.body.classList.remove('modal-open');
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(b => b.remove());
    }
</script>
@endsection