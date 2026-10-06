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
    }

    .page-header-modern {
        background: var(--primary-gradient);
        border-radius: 20px;
        padding: 1.75rem 2rem;
        box-shadow: 0 20px 60px -15px rgba(99, 102, 241, 0.15);
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
        color: #ffffff;
        font-size: 0.85rem;
        margin-top: 0.1rem;
        opacity: 0.9;
    }

    .btn-secondary-gradient {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        border-radius: 12px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-secondary-gradient:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-2px);
        color: white;
    }

    .form-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 20px 60px -15px rgba(99, 102, 241, 0.15);
        border: 1px solid #f1f5f9;
    }

    .form-card .card-header-custom {
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 1rem;
        margin-bottom: 1.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .form-card .card-header-custom h4 {
        font-weight: 700;
        color: #0f172a;
        font-size: 1.1rem;
    }

    .form-card .card-header-custom .badge-mandatory {
        background: #fef2f2;
        color: #dc2626;
        font-size: 0.7rem;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-weight: 600;
    }

    .form-label {
        font-weight: 600;
        color: #334155;
        font-size: 0.85rem;
        margin-bottom: 0.4rem;
    }

    .form-label .text-danger {
        color: #dc3545;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.65rem 1rem;
        font-size: 0.9rem;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        background: #f8fafc;
    }

    .form-control:focus, .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        background: white;
    }

    .form-control[readonly] {
        background: #f1f5f9;
        cursor: not-allowed;
        opacity: 0.8;
    }

    .form-control-color {
        padding: 0.25rem;
        height: 42px;
    }

    .input-group-text {
        background: #f1f5f9;
        border: 2px solid #e2e8f0;
        border-right: none;
        font-weight: 600;
        color: #475569;
        border-radius: 12px 0 0 12px;
    }

    .input-group .form-control {
        border-left: none;
        border-radius: 0 12px 12px 0;
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    .config-details-card {
        background: #f0f4ff;
        border: 2px solid #e0e7ff;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        display: none;
    }

    .config-details-card h6 {
        color: #4338ca;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .config-details-card .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 0.5rem 1rem;
    }

    .config-details-card .detail-item .label {
        font-size: 0.65rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .config-details-card .detail-item .value {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.9rem;
    }

    /* Searchable Dropdown Styles */
    .search-dropdown-wrapper {
        position: relative;
    }

    .search-dropdown-wrapper .search-input {
        position: relative;
        margin-bottom: 0.5rem;
    }

    .search-dropdown-wrapper .search-input input {
        width: 100%;
        padding: 0.6rem 1rem 0.6rem 2.5rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.9rem;
        transition: all 0.25s ease;
        background: #f8fafc;
    }

    .search-dropdown-wrapper .search-input input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        background: white;
        outline: none;
    }

    .search-dropdown-wrapper .search-input .search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .search-dropdown-wrapper .search-input .clear-search {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        cursor: pointer;
        display: none;
        font-size: 0.8rem;
    }

    .search-dropdown-wrapper .search-input .clear-search:hover {
        color: #dc3545;
    }

    .category-options-container {
        max-height: 300px;
        overflow-y: auto;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        background: white;
        display: none;
        position: absolute;
        width: 100%;
        z-index: 1000;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }

    .category-options-container.show {
        display: block;
    }

    .category-option {
        padding: 0.7rem 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.15s ease;
    }

    .category-option:last-child {
        border-bottom: none;
    }

    .category-option:hover {
        background: #eef2ff;
    }

    .category-option.active {
        background: #eef2ff;
        border-left: 3px solid #6366f1;
    }

    .category-option .cat-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .category-option .cat-info .cat-icon {
        font-size: 1.1rem;
        width: 28px;
        text-align: center;
    }

    .category-option .cat-info .cat-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.9rem;
    }

    .category-option .cat-info .cat-hsn {
        font-size: 0.7rem;
        color: #94a3b8;
        font-family: monospace;
        margin-left: 0.3rem;
    }

    .category-option .cat-gst-badge {
        font-size: 0.65rem;
        background: #dbeafe;
        color: #1d4ed8;
        padding: 0.15rem 0.6rem;
        border-radius: 50px;
        font-weight: 600;
        white-space: nowrap;
    }

    .category-option .cat-gst-badge.exempt {
        background: #fef3c7;
        color: #92400e;
    }

    .category-option .cat-gst-badge.high {
        background: #fee2e2;
        color: #991b1b;
    }

    .selected-display {
        background: #f1f5f9;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.65rem 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.25s ease;
    }

    .selected-display:hover {
        border-color: #6366f1;
        background: #f8fafc;
    }

    .selected-display .selected-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .selected-display .selected-info .placeholder-text {
        color: #94a3b8;
        font-size: 0.9rem;
    }

    .selected-display .selected-info .selected-icon {
        font-size: 1.2rem;
    }

    .selected-display .selected-info .selected-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 0.95rem;
    }

    .selected-display .selected-info .selected-gst {
        font-size: 0.7rem;
        color: #64748b;
        margin-left: 0.3rem;
    }

    .selected-display .dropdown-arrow {
        color: #94a3b8;
        transition: transform 0.25s ease;
    }

    .selected-display .dropdown-arrow.open {
        transform: rotate(180deg);
    }

    .no-results {
        padding: 1.5rem;
        text-align: center;
        color: #94a3b8;
        font-size: 0.9rem;
    }

    /* Tax Preview */
    .tax-preview-box {
        background: #f0fdf4;
        border: 2px solid #bbf7d0;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        margin-top: 1rem;
        display: none;
    }

    .tax-preview-box .tax-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 0.75rem;
    }

    .tax-preview-box .tax-item {
        text-align: center;
        padding: 0.5rem;
        background: white;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
    }

    .tax-preview-box .tax-item .label {
        font-size: 0.65rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        display: block;
    }

    .tax-preview-box .tax-item .value {
        font-weight: 700;
        font-size: 1rem;
        color: #1e293b;
    }

    .tax-preview-box .tax-item .value.cgst { color: #2563eb; }
    .tax-preview-box .tax-item .value.sgst { color: #16a34a; }
    .tax-preview-box .tax-item .value.total { color: #dc2626; }

    /* Subcategory Table */
    .subcategory-table th {
        background: #f8fafc;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #475569;
        padding: 0.75rem 1rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .subcategory-table td {
        padding: 0.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .subcategory-table .removeRow {
        background: #fee2e2;
        color: #991b1b;
        border: none;
        border-radius: 8px;
        padding: 0.3rem 0.6rem;
        transition: all 0.25s ease;
    }

    .subcategory-table .removeRow:hover {
        background: #fecaca;
        transform: scale(1.05);
    }

    .btn-success-custom {
        background: var(--success-gradient);
        border: none;
        color: white;
        border-radius: 12px;
        padding: 0.7rem 2rem;
        font-weight: 600;
        transition: all 0.25s ease;
    }

    .btn-success-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.35);
        color: white;
    }

    .btn-secondary-custom {
        background: #f1f5f9;
        border: 2px solid #e2e8f0;
        color: #475569 !important;
        border-radius: 12px;
        padding: 0.7rem 2rem;
        font-weight: 600;
        transition: all 0.25s ease;
    }

    .btn-secondary-custom:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }

    .btn-primary-sm {
        background: var(--primary-gradient);
        border: none;
        color: white;
        border-radius: 10px;
        padding: 0.4rem 1rem;
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.25s ease;
    }

    .btn-primary-sm:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        color: white;
    }

    /* Preview */
    #previewWrapper {
        position: relative;
        display: inline-block;
    }

    #clearVisual {
        display: none;
        position: absolute;
        top: -8px;
        right: -8px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #dc3545;
        color: #fff;
        cursor: pointer;
        text-align: center;
        line-height: 22px;
        font-size: 12px;
        font-weight: bold;
        transition: all 0.3s ease;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    #clearVisual:hover {
        transform: scale(1.1);
    }

    .tax-section {
        background: #fafbff;
        border: 2px solid #e0e7ff;
        border-radius: 16px;
        padding: 1.5rem;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .page-header-modern {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
            padding: 1.25rem;
        }

        .form-card {
            padding: 1.25rem;
        }

        .tax-preview-box .tax-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .category-option {
            flex-wrap: wrap;
            gap: 0.3rem;
        }

        .category-option .cat-gst-badge {
            font-size: 0.55rem;
            padding: 0.1rem 0.4rem;
        }
    }
    a{
        text-decoration: none;
    }
</style>

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="icon-wrapper">
                <i class="fas fa-plus-circle"></i>
            </div>
            <div class="title-section">
                <h4>Create Category</h4>
                <div class="subtitle">Add a new inventory category with predefined GST configuration</div>
            </div>
        </div>
        <div>
            <a href="{{ route('inventory.categories.index') }}" class="btn-secondary-gradient">
                <i class="fas fa-arrow-left"></i> Back to Categories
            </a>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="form-card">
        <div class="card-header-custom">
            <h4><i class="fas fa-folder-tree" style="color: #6366f1;"></i> Category Details</h4>
            <span class="badge-mandatory"><i class="fas fa-asterisk"></i> All fields are mandatory</span>
        </div>

        <form
            method="POST"
            action="{{ route('inventory.categories.store') }}"
            enctype="multipart/form-data"
            id="categoryForm"
        >
            @csrf

            {{-- Configuration Selection --}}
            <div class="row mb-4">
                <div class="col-md-12">
                    <label class="form-label">
                        Configuration <span class="text-danger">*</span>
                    </label>
                    <select
                        name="configuration_id"
                        class="form-control @error('configuration_id') is-invalid @enderror"
                        id="configuration_id"
                        required
                    >
                        <option value="">-- Select Configuration --</option>
                        @foreach($configurations as $config)
                            <option value="{{ $config->id }}" {{ old('configuration_id') == $config->id ? 'selected' : '' }}>
                                {{ $config->configuration_name }}
                                ({{ $config->configuration_code }})
                                @if($config->costing_method)
                                    - {{ str_replace('_', ' ', $config->costing_method) }}
                                @endif
                                @if($config->status === 'inactive')
                                    (Inactive)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('configuration_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Select the inventory configuration this category belongs to.
                    </small>
                </div>
            </div>

            {{-- Configuration Details --}}
            <div id="configDetails" class="config-details-card">
                <h6><i class="fas fa-info-circle"></i> Configuration Details</h6>
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="label">Costing Method</div>
                        <div class="value" id="configCostingMethod">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">Multi Warehouse</div>
                        <div class="value" id="configMultiWarehouse">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">Barcode</div>
                        <div class="value" id="configBarcode">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">QR Code</div>
                        <div class="value" id="configQR">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">Batch Tracking</div>
                        <div class="value" id="configBatchTracking">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">Serial Tracking</div>
                        <div class="value" id="configSerialTracking">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="label">Status</div>
                        <div class="value" id="configStatus">-</div>
                    </div>
                </div>
            </div>

            {{-- Category Selection with Search --}}
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">
                        Select Category <span class="text-danger">*</span>
                    </label>

                    <div class="search-dropdown-wrapper">
                        {{-- Hidden input to store selected value --}}
                        <input type="hidden" id="selectedCategoryKey" name="category_type" value="{{ old('category_type') }}">

                        {{-- Selected Display --}}
                        <div class="selected-display" id="selectedDisplay">
                            <div class="selected-info">
                                <span class="selected-icon" id="selectedIconDisplay"><i class="fas fa-folder" style="color: #6366f1;"></i></span>
                                <span class="placeholder-text" id="selectedPlaceholder">Search and select a category...</span>
                                <span class="selected-name" id="selectedNameDisplay" style="display:none;"></span>
                                <span class="selected-gst" id="selectedGstDisplay" style="display:none;"></span>
                            </div>
                            <span class="dropdown-arrow" id="dropdownArrow"><i class="fas fa-chevron-down"></i></span>
                        </div>

                        {{-- Search Input --}}
                        <div class="search-input" id="searchInputWrapper" style="display: none;">
                            <span class="search-icon"><i class="fas fa-search"></i></span>
                            <input type="text" id="categorySearchInput" placeholder="Search categories by name, HSN code, or description..." autofocus>
                            <span class="clear-search" id="clearSearch"><i class="fas fa-times-circle"></i></span>
                        </div>

                        {{-- Options Container --}}
                        <div class="category-options-container" id="categoryOptionsContainer">
                            @php
                                $predefined = \App\Models\Inventory\InventoryCategory::getPredefinedCategories();
                            @endphp
                            @foreach($predefined as $key => $data)
                                <div class="category-option" data-key="{{ $key }}" data-gst="{{ $data['gst_rate'] }}" data-hsn="{{ $data['hsn_code'] ?? 'N/A' }}" data-icon="{{ $data['icon'] ?? 'fa-folder' }}" data-color="{{ $data['color'] ?? '#6366f1' }}" data-prefix="{{ $data['prefix'] ?? substr($data['name'], 0, 3) }}" data-description="{{ $data['description'] ?? '' }}">
                                    <div class="cat-info">
                                        <span class="cat-icon"><i class="fas {{ $data['icon'] ?? 'fa-folder' }}" style="color: {{ $data['color'] ?? '#6366f1' }};"></i></span>
                                        <span class="cat-name">{{ $data['name'] }}</span>
                                        <span class="cat-hsn">HSN: {{ $data['hsn_code'] ?? 'N/A' }}</span>
                                    </div>
                                    @php
                                        $gstRate = $data['gst_rate'] ?? 0;
                                        $badgeClass = $gstRate == 0 ? 'exempt' : ($gstRate >= 18 ? 'high' : '');
                                    @endphp
                                    <span class="cat-gst-badge {{ $badgeClass }}">{{ $gstRate == 0 ? 'Exempt' : $gstRate . '% GST' }}</span>
                                </div>
                            @endforeach
                            <div class="no-results" style="display: none;">No categories found matching your search.</div>
                        </div>
                    </div>

                    @error('category_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Search and select a predefined category. All tax details will be auto-configured.
                    </small>
                </div>
            </div>

            {{-- Category Name & Code (Auto-filled) --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Category Name <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text" id="categoryPrefix">CAT</span>
                        <input
                            type="text"
                            id="categoryName"
                            name="category_name"
                            class="form-control @error('category_name') is-invalid @enderror"
                            value="{{ old('category_name') }}"
                            required
                            readonly
                            placeholder="Select a category above"
                        >
                    </div>
                    @error('category_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Category Code <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input
                            type="text"
                            id="category_code"
                            name="category_code"
                            class="form-control @error('category_code') is-invalid @enderror"
                            value="{{ old('category_code') }}"
                            placeholder="STA-A7K2"
                            required
                            readonly
                        >
                        <button type="button" id="generateCategoryCode" class="btn btn-primary" disabled>
                            <i class="fas fa-sync-alt"></i> Generate
                        </button>
                    </div>
                    @error('category_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Code is auto-generated based on the selected category.
                    </small>
                </div>
            </div>

            {{-- Icon Selection --}}
            <div class="row align-items-end">
                <div class="col-md-4 mb-3">
                    <label class="form-label">System Icon</label>
                    <select name="icon" id="iconSelect" class="form-control">
                        <option value="">Select Icon</option>
                        <option value="fa-shirt">👕 Uniform</option>
                        <option value="fa-laptop">💻 Electronics</option>
                        <option value="fa-pencil">✏️ Stationery</option>
                        <option value="fa-couch">🛋 Furniture</option>
                        <option value="fa-futbol">⚽ Sports</option>
                        <option value="fa-pills">💊 Medicine</option>
                        <option value="fa-book">📚 Books</option>
                        <option value="fa-computer">🖥 IT Assets</option>
                        <option value="fa-box">📦 Inventory</option>
                        <option value="fa-apple-alt">🍎 Fruits</option>
                        <option value="fa-carrot">🥕 Vegetables</option>
                        <option value="fa-cheese">🧀 Dairy</option>
                        <option value="fa-utensils">🍳 Kitchen</option>
                        <option value="fa-broom">🧹 Cleaning</option>
                        <option value="fa-shopping-cart">🛒 Grocery</option>
                        <option value="fa-pepper">🌶️ Spices</option>
                        <option value="fa-seedling">🌾 Grains</option>
                        <option value="fa-flask">🧪 Laboratory</option>
                        <option value="fa-hard-hat">🪖 Safety</option>
                        <option value="fa-snowflake">❄️ HVAC</option>
                        <option value="fa-faucet">🔧 Plumbing</option>
                        <option value="fa-bolt">⚡ Electrical</option>
                        <option value="fa-tools">🔩 Hardware</option>
                        <option value="fa-paint-roller">🎨 Paints</option>
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Icon Color</label>
                    <input type="color" id="iconColor" name="icon_color" value="#0d6efd" class="form-control form-control-color">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Upload Custom Icon</label>
                    <input type="file" id="iconImage" name="icon_image" accept=".png,.jpg,.jpeg,.svg,.webp,image/png,image/jpeg,image/svg+xml,image/webp" class="form-control">
                    <small class="text-muted">Allowed: PNG, JPG, JPEG, SVG, WEBP (Max 1 MB)</small>
                    @error('icon_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2 mb-3 text-center">
                    <label class="form-label d-block">Preview</label>
                    <div id="previewWrapper" style="position: relative; display: inline-block;">
                        <div id="previewContainer">
                            <i class="fa-solid fa-icons" style="font-size: 34px; color: #0d6efd;"></i>
                        </div>
                        <span id="clearVisual">×</span>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">
                        Description
                        <span class="text-muted small">(Optional)</span>
                    </label>
                    <textarea name="description" id="categoryDescription" rows="2" class="form-control">{{ old('description') }}</textarea>
                </div>
            </div>

            <hr>

            {{-- GST & Tax Configuration (Auto-filled) --}}
            <div class="tax-section">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem;">
                    <h6 style="color: #4338ca; font-weight: 700; margin: 0;">
                        <i class="fas fa-percentage" style="color: #10b981;"></i>
                        GST & Tax Configuration
                    </h6>
                    <span style="font-size: 0.7rem; background: #dbeafe; color: #1d4ed8; padding: 0.2rem 0.8rem; border-radius: 50px; font-weight: 600;">
                        Auto-configured based on category
                    </span>
                </div>

                {{-- GST Applicable Toggle --}}
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="is_gst_applicable" name="is_gst_applicable" value="1" checked style="width: 2.5rem; height: 1.25rem;">
                            <label class="form-check-label fw-bold" for="is_gst_applicable" style="font-size: 0.9rem;">
                                <i class="fas fa-check-circle text-success"></i>
                                GST Applicable
                            </label>
                            <small class="text-muted d-block mt-1" style="margin-left: 3rem;">
                                <i class="fas fa-info-circle"></i>
                                Enabled for taxable categories. Disabled for exempt categories (0% GST).
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Tax Fields --}}
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">GST Rate <span class="text-danger">*</span></label>
                        <input type="text" id="gst_rate_display" class="form-control" readonly style="background: #eff6ff; color: #2563eb; font-weight: 700; border-color: #bfdbfe;">
                        <input type="hidden" id="gst_rate" name="gst_rate" value="">
                        <small class="text-muted">Auto-selected based on category</small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">HSN Code <span class="text-danger">*</span></label>
                        <input type="text" id="hsn_code_display" class="form-control" readonly style="background: #f0fdf4; color: #16a34a; font-weight: 600; border-color: #bbf7d0;">
                        <input type="hidden" id="hsn_code_id" name="hsn_code_id" value="">
                        <small class="text-muted">Auto-selected based on category</small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tax Slab <span class="text-danger">*</span></label>
                        <input type="text" id="tax_slab_display" class="form-control" readonly style="background: #fef3c7; color: #92400e; font-weight: 600; border-color: #fde68a;">
                        <input type="hidden" id="tax_slab_id" name="tax_slab_id" value="">
                        <small class="text-muted">Auto-selected based on GST rate</small>
                    </div>
                </div>

                {{-- Tax Preview --}}
                <div id="taxPreviewBox" class="tax-preview-box" style="display: none;">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 style="color: #15803d; font-weight: 600; margin-bottom: 0.75rem; font-size: 0.9rem;">
                                <i class="fas fa-calculator"></i> Tax Calculation Preview
                            </h6>
                        </div>
                    </div>
                    <div class="tax-grid">
                        <div class="tax-item">
                            <span class="label">GST Rate</span>
                            <span class="value" id="previewGSTRate">-</span>
                        </div>
                        <div class="tax-item">
                            <span class="label">CGST</span>
                            <span class="value cgst" id="previewCGST">-</span>
                        </div>
                        <div class="tax-item">
                            <span class="label">SGST</span>
                            <span class="value sgst" id="previewSGST">-</span>
                        </div>
                        <div class="tax-item">
                            <span class="label">Total Tax</span>
                            <span class="value total" id="previewTotalTax">-</span>
                        </div>
                    </div>
                    <div class="mt-2 text-center">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Based on ₹100 base amount
                        </small>
                    </div>
                </div>

                {{-- Tax Calculator --}}
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div style="background: #f8fafc; border-radius: 12px; padding: 1rem; border: 1px solid #e2e8f0;">
                            <h6 style="font-weight: 600; margin-bottom: 0.75rem; font-size: 0.85rem;">
                                <i class="fas fa-calculator"></i> Tax Calculator
                            </h6>
                            <div class="row align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label" style="font-size: 0.8rem;">Amount (₹)</label>
                                    <input type="number" id="tax_amount_input" class="form-control" placeholder="Enter amount" value="100" min="0">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" style="font-size: 0.8rem;">CGST</label>
                                    <input type="text" id="tax_cgst" class="form-control" readonly style="background: #eff6ff; color: #2563eb; font-weight: 600;">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" style="font-size: 0.8rem;">SGST</label>
                                    <input type="text" id="tax_sgst" class="form-control" readonly style="background: #f0fdf4; color: #16a34a; font-weight: 600;">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" style="font-size: 0.8rem;">Total with Tax</label>
                                    <input type="text" id="tax_grand_total" class="form-control" readonly style="background: #fef2f2; color: #dc2626; font-weight: 700;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="tax_composition" id="tax_composition" value="">
            </div>

            <hr>

            {{-- Sub Categories --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0" style="font-size: 1rem; font-weight: 700;">
                    <i class="fas fa-tags" style="color: #6366f1;"></i> Sub Categories
                    <span class="text-muted" style="font-weight: 400; font-size: 0.8rem;">(Optional)</span>
                </h5>
                <button type="button" id="addSubCategory" class="btn-primary-sm">
                    <i class="fas fa-plus"></i> Add Sub Category
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered subcategory-table" id="subcategoryTable">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Name</th>
                            <th style="width: 40%;">Code</th>
                            <th style="width: 20%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="text" name="subcategories[0][name]" class="form-control subcategory-name" placeholder="Enter sub category name" style="font-size: 0.85rem;">
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="subcategories[0][code]" class="form-control subcategory-code" placeholder="UNI-BOY-X8P2" style="font-size: 0.85rem;">
                                    <button type="button" class="btn btn-primary generateSubCode" style="border-radius: 0 10px 10px 0; font-size: 0.75rem; padding: 0.3rem 0.8rem;">
                                        <i class="fas fa-sync-alt"></i> Generate
                                    </button>
                                </div>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn removeRow">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <small class="text-muted" class="mb-2" style="padding-inline: 10px;">
                    <i class="fas fa-info-circle"></i>
                    Sub categories are optional. Add multiple sub categories to organize your inventory.
                </small>
            </div>

            {{-- Form Actions --}}
            <div class="mt-4" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <button type="submit" class="btn-success-custom">
                    <i class="fas fa-save"></i> Save Category
                </button>
                <a href="{{ route('inventory.categories.index') }}" class="btn-secondary-custom">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function() {
    // ============================================
    // PREDEFINED CATEGORIES DATA
    // ============================================
    const predefinedData = @json(\App\Models\Inventory\InventoryCategory::getPredefinedCategories());

    // ============================================
    // SEARCHABLE DROPDOWN
    // ============================================
    let selectedKey = null;
    let isOpen = false;

    const $selectedDisplay = $('#selectedDisplay');
    const $searchInputWrapper = $('#searchInputWrapper');
    const $searchInput = $('#categorySearchInput');
    const $optionsContainer = $('#categoryOptionsContainer');
    const $clearSearch = $('#clearSearch');
    const $dropdownArrow = $('#dropdownArrow');
    const $selectedPlaceholder = $('#selectedPlaceholder');
    const $selectedNameDisplay = $('#selectedNameDisplay');
    const $selectedGstDisplay = $('#selectedGstDisplay');
    const $selectedIconDisplay = $('#selectedIconDisplay');

    // Load from old input
    const oldCategoryType = '{{ old('category_type') }}';
    if (oldCategoryType && predefinedData[oldCategoryType]) {
        selectCategory(oldCategoryType);
    }

    // Toggle dropdown
    $selectedDisplay.on('click', function(e) {
        e.stopPropagation();
        toggleDropdown();
    });

    function toggleDropdown() {
        isOpen = !isOpen;
        if (isOpen) {
            $optionsContainer.addClass('show');
            $searchInputWrapper.show();
            $dropdownArrow.addClass('open');
            setTimeout(function() {
                $searchInput.focus();
            }, 100);
        } else {
            $optionsContainer.removeClass('show');
            $searchInputWrapper.hide();
            $dropdownArrow.removeClass('open');
            $searchInput.val('');
            filterOptions('');
        }
    }

    // Close dropdown on outside click
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-dropdown-wrapper').length) {
            if (isOpen) {
                toggleDropdown();
            }
        }
    });

    // Search input
    $searchInput.on('keyup', function() {
        const query = $(this).val().toLowerCase();
        filterOptions(query);
        $clearSearch.toggle(query.length > 0);
    });

    $clearSearch.on('click', function() {
        $searchInput.val('');
        $clearSearch.hide();
        filterOptions('');
        $searchInput.focus();
    });

    function filterOptions(query) {
        let hasVisible = false;
        $optionsContainer.find('.category-option').each(function() {
            const text = $(this).text().toLowerCase();
            const match = text.includes(query);
            $(this).toggle(match);
            if (match) hasVisible = true;
        });
        $optionsContainer.find('.no-results').toggle(!hasVisible && query.length > 0);
    }

    // Category option click
    $optionsContainer.on('click', '.category-option', function() {
        const key = $(this).data('key');
        if (key && predefinedData[key]) {
            selectCategory(key);
            if (isOpen) toggleDropdown();
        }
    });

    function selectCategory(key) {
        const data = predefinedData[key];
        if (!data) return;

        selectedKey = key;
        $('#selectedCategoryKey').val(key);

        // Update selected display
        const icon = data.icon || 'fa-folder';
        const color = data.color || '#6366f1';
        const gstRate = data.gst_rate || 0;

        $selectedPlaceholder.hide();
        $selectedNameDisplay.show().text(data.name);
        $selectedGstDisplay.show().text(`(GST: ${gstRate}%)`);
        $selectedIconDisplay.html(`<i class="fas ${icon}" style="color: ${color};"></i>`);

        // Highlight active option
        $optionsContainer.find('.category-option').removeClass('active');
        $optionsContainer.find(`.category-option[data-key="${key}"]`).addClass('active');

        // Auto-fill name and code
        const prefix = data.prefix || data.name.substring(0, 3).toUpperCase();
        $('#categoryPrefix').text(prefix);
        $('#categoryName').val(data.name).attr('readonly', true).css('background', '#f1f5f9');

        const code = prefix + '-' + generateRandomCode();
        $('#category_code').val(code).attr('readonly', true).css('background', '#f1f5f9');
        $('#generateCategoryCode').prop('disabled', true).html('<i class="fas fa-lock"></i> Generated');

        // Auto-fill icon and color
        if (data.icon) {
            $('#iconSelect').val(data.icon);
            if (data.color) {
                $('#iconColor').val(data.color);
            }
            updatePreview(data.icon, data.color || '#0d6efd');
        }

        // Auto-fill description
        if (data.description) {
            $('#categoryDescription').val(data.description);
        }

        // Auto-fill GST details
        const gstRateVal = data.gst_rate || '0';
        const hsnCode = data.hsn_code || 'N/A';

        $('#gst_rate_display').val(gstRateVal + '%');
        $('#gst_rate').val(gstRateVal);

        $('#hsn_code_display').val(hsnCode);

        // Set HSN code ID
        @if(isset($hsnCodes))
            const hsnMatch = @json($hsnCodes).find(h => h.hsn_code === hsnCode);
            if (hsnMatch) {
                $('#hsn_code_id').val(hsnMatch.id);
            }
        @endif

        // Set tax slab
        const slabName = getTaxSlabName(gstRateVal);
        $('#tax_slab_display').val(slabName);
        $('#tax_slab_id').val(getTaxSlabId(gstRateVal));

        // GST applicable
        const isApplicable = gstRateVal > 0;
        $('#is_gst_applicable').prop('checked', isApplicable).trigger('change');

        // Tax composition
        const cgst = gstRateVal / 2;
        const sgst = gstRateVal / 2;
        $('#tax_composition').val(JSON.stringify([
            { type: 'cgst', rate: cgst },
            { type: 'sgst', rate: sgst }
        ]));

        // Update tax preview
        if (gstRateVal > 0) {
            $('#previewGSTRate').text(gstRateVal + '%');
            $('#previewCGST').text(cgst + '%');
            $('#previewSGST').text(sgst + '%');
            $('#previewTotalTax').text(gstRateVal + '%');
            $('#taxPreviewBox').show();
        } else {
            $('#previewGSTRate').text('0% (Exempt)');
            $('#previewCGST').text('0%');
            $('#previewSGST').text('0%');
            $('#previewTotalTax').text('0%');
            $('#taxPreviewBox').show();
        }

        calculateTax();
        updateSubCategoryGenerateButtons();
    }

    function resetFormFields() {
        $('#categoryName').val('').attr('readonly', true).css('background', '#f1f5f9');
        $('#category_code').val('').attr('readonly', true).css('background', '#f1f5f9');
        $('#categoryPrefix').text('CAT');
        $('#generateCategoryCode').prop('disabled', true).html('<i class="fas fa-sync-alt"></i> Generate');
        $('#selectedCategoryKey').val('');
        $('#gst_rate_display').val('');
        $('#gst_rate').val('');
        $('#hsn_code_display').val('');
        $('#hsn_code_id').val('');
        $('#tax_slab_display').val('');
        $('#tax_slab_id').val('');
        $('#taxPreviewBox').hide();
        $('#is_gst_applicable').prop('checked', true);
        $('#categoryDescription').val('');
        $('#tax_cgst').val('₹0.00');
        $('#tax_sgst').val('₹0.00');
        $('#tax_grand_total').val('₹100.00');
        $('#iconSelect').val('');
        $('#iconColor').val('#0d6efd');
        $('#previewContainer').html('<i class="fa-solid fa-icons" style="font-size:34px;color:#0d6efd;"></i>');
        $('#clearVisual').hide();

        $selectedPlaceholder.show();
        $selectedNameDisplay.hide();
        $selectedGstDisplay.hide();
        $selectedIconDisplay.html('<i class="fas fa-folder" style="color: #6366f1;"></i>');
        $optionsContainer.find('.category-option').removeClass('active');
        selectedKey = null;
    }

    function getTaxSlabName(rate) {
        const slabs = {
            '0': 'Exempt (0%)',
            '3': '3% GST',
            '5': '5% GST',
            '12': '12% GST',
            '18': '18% GST',
            '28': '28% GST'
        };
        return slabs[rate] || rate + '% GST';
    }

    function getTaxSlabId(rate) {
        @if(isset($taxSlabs))
            const slab = @json($taxSlabs).find(s => s.tax_rate == rate);
            return slab ? slab.id : '';
        @endif
        return '';
    }

    // ============================================
    // ICON PREVIEW
    // ============================================
    let defaultPreview = '<i class="fa-solid fa-icons" style="font-size:34px;color:#0d6efd;"></i>';

    function updatePreview(iconClass, color) {
        if (iconClass) {
            $('#previewContainer').html(
                '<i class="fa-solid ' + iconClass + '" style="font-size:34px;color:' + (color || '#0d6efd') + ';"></i>'
            );
            $('#clearVisual').show();
        }
    }

    $("#iconSelect").on("change", function() {
        let iconClass = $(this).val();
        if (iconClass) {
            $("#iconImage").val("");
            updatePreview(iconClass, $("#iconColor").val());
        } else {
            if (!$("#iconImage").val()) {
                $("#previewContainer").html(defaultPreview);
                $("#clearVisual").hide();
            }
        }
    });

    $("#iconColor").on("input", function() {
        let icon = $("#iconSelect").val();
        if (icon) {
            updatePreview(icon, $(this).val());
        }
    });

    $("#iconImage").on("change", function() {
        let file = this.files[0];
        if (!file) {
            if (!$("#iconSelect").val()) {
                $("#previewContainer").html(defaultPreview);
                $("#clearVisual").hide();
            }
            return;
        }

        const allowedTypes = ["image/png", "image/jpeg", "image/jpg", "image/webp", "image/svg+xml"];
        if (!allowedTypes.includes(file.type)) {
            alert("Only PNG, JPG, JPEG, SVG and WEBP files are allowed.");
            $(this).val("");
            return;
        }

        if (file.size > 1024 * 1024) {
            alert("Maximum file size allowed is 1 MB.");
            $(this).val("");
            return;
        }

        $("#iconSelect").val("");
        let reader = new FileReader();
        reader.onload = function(e) {
            $("#previewContainer").html(
                '<img src="' + e.target.result + '" style="width:60px;height:60px;object-fit:contain;border-radius:8px;">'
            );
            $("#clearVisual").show();
        };
        reader.readAsDataURL(file);
    });

    $("#clearVisual").on("click", function() {
        $("#iconSelect").val("");
        $("#iconColor").val("#0d6efd");
        $("#iconImage").val("");
        $("#previewContainer").html(defaultPreview);
        $(this).hide();
    });

    // ============================================
    // CONFIGURATION DETAILS
    // ============================================
    const configurations = @json($configurations);

    $('#configuration_id').on('change', function() {
        const configId = $(this).val();
        const detailsDiv = $('#configDetails');

        if (configId) {
            const config = configurations.find(c => c.id == configId);
            if (config) {
                $('#configCostingMethod').text(config.costing_method ? config.costing_method.replace('_', ' ') : '-');
                $('#configMultiWarehouse').text(config.multi_warehouse ? '✅ Enabled' : '❌ Disabled');
                $('#configBarcode').text(config.barcode_enabled ? '✅ Enabled' : '❌ Disabled');
                $('#configQR').text(config.qr_enabled ? '✅ Enabled' : '❌ Disabled');
                $('#configBatchTracking').text(config.batch_tracking ? '✅ Enabled' : '❌ Disabled');
                $('#configSerialTracking').text(config.serial_tracking ? '✅ Enabled' : '❌ Disabled');
                $('#configStatus').text(config.status === 'active' ? '✅ Active' : '❌ Inactive');
                detailsDiv.show();
            }
        } else {
            detailsDiv.hide();
        }
    });

    // ============================================
    // TAX CALCULATOR
    // ============================================
    function calculateTax() {
        const amount = parseFloat($('#tax_amount_input').val()) || 0;
        const rate = parseFloat($('#gst_rate').val()) || 0;

        if (rate > 0 && amount > 0) {
            const cgst = (amount * rate / 2) / 100;
            const sgst = (amount * rate / 2) / 100;
            const totalTax = cgst + sgst;

            $('#tax_cgst').val('₹' + cgst.toFixed(2));
            $('#tax_sgst').val('₹' + sgst.toFixed(2));
            $('#tax_grand_total').val('₹' + (amount + totalTax).toFixed(2));
        } else {
            $('#tax_cgst').val('₹0.00');
            $('#tax_sgst').val('₹0.00');
            $('#tax_grand_total').val('₹' + amount.toFixed(2));
        }
    }

    $('#tax_amount_input').on('input', calculateTax);

    // ============================================
    // GST APPLICABLE TOGGLE
    // ============================================
    $('#is_gst_applicable').on('change', function() {
        const isApplicable = $(this).is(':checked');
        if (!isApplicable) {
            $('#gst_rate_display').val('0% (Exempt)');
            $('#gst_rate').val('0');
            $('#tax_slab_display').val('Exempt (0%)');
            $('#tax_slab_id').val('');
            $('#previewGSTRate').text('0% (Exempt)');
            $('#previewCGST').text('0%');
            $('#previewSGST').text('0%');
            $('#previewTotalTax').text('0%');
            calculateTax();
        } else if (selectedKey && predefinedData[selectedKey]) {
            const data = predefinedData[selectedKey];
            const rate = data.gst_rate || '0';
            $('#gst_rate_display').val(rate + '%');
            $('#gst_rate').val(rate);
            $('#tax_slab_display').val(getTaxSlabName(rate));
            $('#tax_slab_id').val(getTaxSlabId(rate));
            $('#previewGSTRate').text(rate + '%');
            const cgst = rate / 2;
            const sgst = rate / 2;
            $('#previewCGST').text(cgst + '%');
            $('#previewSGST').text(sgst + '%');
            $('#previewTotalTax').text(rate + '%');
            calculateTax();
        }
    });

    // ============================================
    // CODE GENERATION
    // ============================================
    function generateRandomCode(length = 4) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let result = '';
        for (let i = 0; i < length; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return result;
    }

    $('#generateCategoryCode').on('click', function() {
        if (!selectedKey) {
            alert('Please select a category first.');
            return;
        }
        const data = predefinedData[selectedKey];
        const prefix = data.prefix || data.name.substring(0, 3).toUpperCase();
        const newCode = prefix + '-' + generateRandomCode();
        $('#category_code').val(newCode);
        $(this).prop('disabled', true).html('<i class="fas fa-lock"></i> Generated');
    });

    // ============================================
    // SUB CATEGORIES
    // ============================================
    let subIndex = 1;

    function updateSubCategoryGenerateButtons() {
        const categoryCode = $('#category_code').val().trim();
        const hasCategoryCode = categoryCode !== '';

        $('.generateSubCode').each(function() {
            const row = $(this).closest('tr');
            const subCode = row.find('.subcategory-code').val().trim();

            if (subCode !== '') {
                $(this).prop('disabled', true).html('<i class="fas fa-lock"></i>');
            } else if (hasCategoryCode) {
                const subName = row.find('.subcategory-name').val().trim();
                $(this).prop('disabled', subName === '').html('<i class="fas fa-sync-alt"></i> Generate');
            } else {
                $(this).prop('disabled', true).html('<i class="fas fa-sync-alt"></i> Generate');
            }
        });
    }

    $(document).on('input', '.subcategory-name', function() {
        const row = $(this).closest('tr');
        row.find('.subcategory-code').val('');
        updateSubCategoryGenerateButtons();
    });

    $(document).on('click', '.generateSubCode', function() {
        const row = $(this).closest('tr');
        const categoryCode = $('#category_code').val().trim();

        if (!categoryCode) {
            alert('Generate Category Code First');
            return;
        }

        const subName = row.find('.subcategory-name').val().trim();
        if (!subName) {
            alert('Enter Sub Category Name First');
            return;
        }

        const categoryPrefix = categoryCode.split('-')[0];
        const subPrefix = generateSubPrefix(subName);
        const newCode = categoryPrefix + '-' + subPrefix + '-' + generateRandomCode();

        row.find('.subcategory-code').val(newCode);
        $(this).prop('disabled', true).html('<i class="fas fa-lock"></i>');
    });

    function generateSubPrefix(name) {
        name = name.trim();
        if (!name) return 'SUB';
        let words = name.replace(/&/g, ' ').split(/\s+/).filter(w => !['and', 'of', 'the'].includes(w.toLowerCase()));
        if (words.length === 1) return words[0].substring(0, 3).toUpperCase();
        return words.map(w => w.charAt(0)).join('').toUpperCase();
    }

    $('#addSubCategory').click(function() {
        $('#subcategoryTable tbody').append(`
            <tr>
                <td>
                    <input type="text" name="subcategories[${subIndex}][name]" class="form-control subcategory-name" placeholder="Enter sub category name" style="font-size: 0.85rem;">
                </td>
                <td>
                    <div class="input-group">
                        <input type="text" name="subcategories[${subIndex}][code]" class="form-control subcategory-code" placeholder="UNI-BOY-X8P2" style="font-size: 0.85rem;">
                        <button type="button" class="btn btn-primary generateSubCode" style="border-radius: 0 10px 10px 0; font-size: 0.75rem; padding: 0.3rem 0.8rem;">
                            <i class="fas fa-sync-alt"></i> Generate
                        </button>
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn removeRow">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);
        subIndex++;
        updateSubCategoryGenerateButtons();
    });

    $(document).on("click", ".removeRow", function() {
        if ($('#subcategoryTable tbody tr').length > 1) {
            $(this).closest("tr").remove();
        } else {
            alert('You must keep at least one row. Leave fields empty if not needed.');
        }
    });

    // ============================================
    // INITIALIZE
    // ============================================
    if ($('#configuration_id').val()) {
        $('#configuration_id').trigger('change');
    }

    if (oldCategoryType && predefinedData[oldCategoryType]) {
        // Already handled
    }

    setTimeout(updateSubCategoryGenerateButtons, 100);

    $(document).on('change', '#category_code', updateSubCategoryGenerateButtons);
});
</script>
@endsection