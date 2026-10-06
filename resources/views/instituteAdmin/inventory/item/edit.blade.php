@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    :root {
        --primary-color: #4361ee;
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
        border-radius: var(--border-radius-lg);
        padding: 1.75rem 2rem;
        box-shadow: var(--card-shadow);
        margin-bottom: 2rem;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .page-header-modern .header-icon {
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
        flex-shrink: 0;
    }

    .page-header-modern .header-title h4 {
        margin: 0;
        font-weight: 700;
        color: white;
    }

    .page-header-modern .header-title p {
        margin: 0;
        font-size: 0.85rem;
        color: white;
        opacity: 0.8;
    }

    .page-header-modern .ms-auto {
        margin-left: auto;
    }

    .btn-header {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-header:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        color: white;
    }

    .form-card {
        background: white;
        border-radius: var(--border-radius-lg);
        padding: 2rem;
        border: 1px solid var(--border-color);
        box-shadow: var(--card-shadow);
    }

    .form-section-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-section-title i {
        color: var(--primary-color);
        font-size: 1.2rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.85rem;
        margin-bottom: 0.3rem;
    }

    .form-label .required-star {
        color: var(--danger-color);
        margin-left: 2px;
    }

    .form-control, .form-select {
        border: 2px solid var(--border-color);
        border-radius: var(--border-radius-md);
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        transition: var(--transition);
        background: white;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: var(--danger-color);
    }

    .invalid-feedback {
        color: var(--danger-color);
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    .form-control[readonly] {
        background: #f1f5f9;
        cursor: not-allowed;
    }

    .form-hint {
        font-size: 0.72rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
    }

    .input-group-text {
        background: #f1f5f9;
        border-radius: var(--border-radius-md) 0 0 var(--border-radius-md);
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--text-dark);
        border: 2px solid var(--border-color);
        border-right: none;
    }

    .input-group .form-control {
        border-radius: 0 var(--border-radius-md) var(--border-radius-md) 0;
    }

    .input-group .form-select {
        border-radius: 0 var(--border-radius-md) var(--border-radius-md) 0;
        max-width: 80px;
    }

    .info-card {
        background: #f8fafc;
        border-radius: var(--border-radius-md);
        padding: 1.25rem;
        border: 1px solid var(--border-color);
        margin-bottom: 1rem;
    }

    .info-card-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-card-title .badge {
        font-size: 0.6rem;
        padding: 0.2rem 0.6rem;
    }

    .stock-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }

    .stock-info-item {
        padding: 10px;
    }

    .stock-info-item .label {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: var(--text-muted);
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .stock-info-item .value {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-dark);
    }

    .stock-info-item .value.text-success { color: var(--success-color); }
    .stock-info-item .value.text-danger { color: var(--danger-color); }
    .stock-info-item .value.text-warning { color: var(--warning-color); }

    .btn-primary-custom {
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: var(--border-radius-sm);
        padding: 0.5rem 1.2rem;
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-primary-custom:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(67, 97, 238, 0.3);
        color: white;
    }

    .btn-success-custom {
        background: var(--success-gradient);
        color: white;
        border: none;
        border-radius: var(--border-radius-sm);
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-success-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
        color: white;
    }

    .btn-secondary-custom {
        background: #f1f5f9;
        color: var(--text-dark);
        border: none;
        border-radius: var(--border-radius-sm);
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-secondary-custom:hover {
        background: var(--border-color);
        transform: translateY(-2px);
        color: var(--text-dark);
    }

    .form-check-input {
        width: 1.1rem;
        height: 1.1rem;
        margin-top: 0.2rem;
    }

    .form-check-label {
        font-weight: 500;
        margin-left: 0.3rem;
    }

    .badge-status {
        font-size: 0.7rem;
        padding: 0.3rem 0.8rem;
        border-radius: 12px;
    }

    .badge-status.active {
        background: #dcfce7;
        color: #166534;
    }

    .badge-status.inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .depreciation-section {
        display: {{ old('depreciation_applicable', $item->depreciation_applicable) ? 'block' : 'none' }};
    }

    @media (max-width: 768px) {
        .page-header-modern {
            flex-direction: column;
            text-align: center;
        }

        .page-header-modern .ms-auto {
            margin-left: 0 !important;
            width: 100%;
        }

        .form-card {
            padding: 1.25rem;
        }

        .stock-info-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

<!-- PAGE HEADER -->
<div class="container-fluid">
    <div class="page-header-modern">
        <div class="header-icon">
            <i class="fas fa-edit"></i>
        </div>
        <div class="header-title">
            <h4>Edit Inventory Item</h4>
            <p>Update item details for <strong>{{ $item->item_name }} </strong> ({{ $item->item_code }})</p>
        </div>
        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('inventory.items.view', $item->id) }}" class="btn-header">
                <i class="fas fa-eye"></i> View
            </a>
            <a href="{{ route('inventory.items.index') }}" class="btn-header">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="form-card">
        <!-- Stock Information -->
        <div class="info-card" style="background: #f0fdf4; border-color: #bbf7d0;">
            <div class="info-card-title">
                <i class="fas fa-boxes text-success"></i> Stock Information
                <span class="badge bg-success">Current</span>
            </div>
            <div class="row">
                <div class="stock-info-item col-md-4">
                    <div class="label">Item Code</div>
                    <div class="value"><span class="badge bg-secondary">{{ $item->item_code }}</span></div>
                </div>
                <div class="stock-info-item col-md-4">
                    <div class="label">Current Stock</div>
                    <div class="value">{{ number_format($item->current_stock, 2) }}</div>
                </div>
                <div class="stock-info-item col-md-4">
                    <div class="label">Available Stock</div>
                    <div class="value text-success">{{ number_format($item->available_stock, 2) }}</div>
                </div>
                <div class="stock-info-item col-md-4">
                    <div class="label">Reserved Stock</div>
                    <div class="value text-warning">{{ number_format($item->reserved_stock, 2) }}</div>
                </div>
                @if($item->expiry_date)
                <div class="stock-info-item col-md-4">
                    <div class="label">Expiry Status</div>
                    <div class="value">
                        <span class="badge {{ $item->shelf_life_badge }}">
                            {{ $item->shelf_life_status }}
                        </span>
                        @if($item->days_until_expiry)
                            <span class="ms-2" style="font-size:0.8rem;">{{ $item->days_until_expiry }} days remaining</span>
                        @endif
                    </div>
                </div>
                @endif
                <div class="stock-info-item col-md-4">
                    <div class="label">Status</div>
                    <div class="value">
                        <span class="badge-status {{ $item->status ? 'active' : 'inactive' }}">
                            {{ $item->status ? '🟢 Active' : '🔴 Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('inventory.items.update', $item->id) }}" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <!-- ============================================ -->
            <!-- BASIC INFORMATION -->
            <!-- ============================================ -->
            <div class="form-section-title">
                <i class="fas fa-info-circle"></i> Basic Information
                <span class="badge bg-primary">Required</span>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Item Name <span class="required-star">*</span></label>
                    <input type="text" name="item_name" id="itemName" 
                           class="form-control @error('item_name') is-invalid @enderror" 
                           value="{{ old('item_name', $item->item_name) }}" required>
                    @error('item_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Item Type <span class="required-star">*</span></label>
                    <select name="item_type" class="form-control @error('item_type') is-invalid @enderror" required>
                        <option value="">Select Type</option>
                        <option value="CONSUMABLE" {{ old('item_type', $item->item_type) == 'CONSUMABLE' ? 'selected' : '' }}>🧻 Consumable</option>
                        <option value="NON_CONSUMABLE" {{ old('item_type', $item->item_type) == 'NON_CONSUMABLE' ? 'selected' : '' }}>🔧 Non-Consumable</option>
                        <option value="ASSET" {{ old('item_type', $item->item_type) == 'ASSET' ? 'selected' : '' }}>📊 Asset</option>
                        <option value="SERVICE" {{ old('item_type', $item->item_type) == 'SERVICE' ? 'selected' : '' }}>🛎️ Service</option>
                    </select>
                    @error('item_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">SKU</label>
                    <div class="input-group">
                        <input type="text" name="sku" id="sku" 
                               class="form-control @error('sku') is-invalid @enderror" 
                               value="{{ old('sku', $item->sku) }}" placeholder="Auto-generated">
                        <button type="button" id="generateSku" class="btn btn-primary" title="Generate SKU">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    @error('sku')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Unique SKU for this item. Click generate to auto-create.</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Brand</label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand', $item->brand) }}" 
                           placeholder="e.g., Apple, Samsung, Nike">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Model</label>
                    <input type="text" name="model" class="form-control" value="{{ old('model', $item->model) }}" 
                           placeholder="e.g., iPhone 14, Air Max">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Manufacturer</label>
                    <input type="text" name="manufacturer" class="form-control" value="{{ old('manufacturer', $item->manufacturer) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Manufacturer Part Number</label>
                    <input type="text" name="manufacturer_part_number" class="form-control" 
                           value="{{ old('manufacturer_part_number', $item->manufacturer_part_number) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">HSN / SAC Code</label>
                    <input type="text" name="hsn_code" class="form-control" 
                           value="{{ old('hsn_code', $item->hsn_code) }}" placeholder="e.g., 84713000">
                    <div class="form-hint">Harmonized System of Nomenclature code for GST</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Barcode</label>
                    <input type="text" name="barcode" class="form-control" 
                           value="{{ old('barcode', $item->barcode) }}" placeholder="Scan or enter barcode">
                    <div class="form-hint">Unique barcode for this item.</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" 
                              placeholder="Enter item description">{{ old('description', $item->description) }}</textarea>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- WAREHOUSE & CLASSIFICATION -->
            <!-- ============================================ -->
            <div class="form-section-title">
                <i class="fas fa-warehouse"></i> Warehouse & Classification
                <span class="badge bg-primary">Required</span>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Warehouse <span class="required-star">*</span></label>
                    <select name="warehouse_id" class="form-control @error('warehouse_id') is-invalid @enderror" required>
                        <option value="">Select Warehouse</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" 
                                {{ old('warehouse_id', $item->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->warehouse_name }} ({{ $warehouse->warehouse_code }})
                                @if($warehouse->is_default) - Default @endif
                            </option>
                        @endforeach
                    </select>
                    @error('warehouse_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Store</label>
                    <select name="store_id" class="form-control @error('store_id') is-invalid @enderror">
                        <option value="">Select Store</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" 
                                {{ old('store_id', $item->store_id) == $store->id ? 'selected' : '' }}
                                data-warehouse-id="{{ $store->warehouse_id }}">
                                {{ $store->store_name }} ({{ $store->store_code }})
                            </option>
                        @endforeach
                    </select>
                    @error('store_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Store within the warehouse.</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Vendor</label>
                    <select name="vendor_id" class="form-control @error('vendor_id') is-invalid @enderror">
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" 
                                {{ old('vendor_id', $item->vendor_id) == $vendor->id ? 'selected' : '' }}>
                                {{ $vendor->vendor_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('vendor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Category <span class="required-star">*</span></label>
                    <select name="category_id" id="category_id" 
                            class="form-control @error('category_id') is-invalid @enderror" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }} ({{ $category->category_code }})
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Sub Category</label>
                    <select name="subcategory_id" id="subcategory_id" 
                            class="form-control @error('subcategory_id') is-invalid @enderror">
                        <option value="">Select Sub Category</option>
                        @foreach($subcategories as $sub)
                            <option value="{{ $sub->id }}" 
                                {{ old('subcategory_id', $item->subcategory_id) == $sub->id ? 'selected' : '' }}>
                                {{ $sub->subcategory_name }} ({{ $sub->subcategory_code }})
                            </option>
                        @endforeach
                    </select>
                    @error('subcategory_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Optional. Select a category first to see subcategories.</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Location Details</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="text" name="rack_number" class="form-control" placeholder="Rack" 
                                   value="{{ old('rack_number', $item->rack_number) }}">
                        </div>
                        <div class="col-6">
                            <input type="text" name="shelf_number" class="form-control" placeholder="Shelf" 
                                   value="{{ old('shelf_number', $item->shelf_number) }}">
                        </div>
                        <div class="d-none col-4">
                            <input type="text" name="bin_number" class="form-control" placeholder="Bin" 
                                   value="{{ old('bin_number', $item->bin_number) }}">
                        </div>
                    </div>
                    <div class="form-hint">Physical location of this item in the warehouse.</div>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- PRICING & STOCK -->
            <!-- ============================================ -->
            <div class="form-section-title">
                <i class="fas fa-money-bill-wave"></i> Pricing & Stock
                <span class="badge bg-primary">Required</span>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Buying Price <span class="required-star">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="number" step="0.01" name="buying_price" 
                               class="form-control @error('buying_price') is-invalid @enderror" 
                               value="{{ old('buying_price', $item->buying_price) }}" required>
                    </div>
                    @error('buying_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Selling Price <span class="required-star">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="number" step="0.01" name="selling_price" 
                               class="form-control @error('selling_price') is-invalid @enderror" 
                               value="{{ old('selling_price', $item->selling_price) }}" required>
                    </div>
                    @error('selling_price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Margin</label>
                    <div class="input-group">
                        <input type="text" id="marginDisplay" class="form-control" readonly 
                               value="{{ $item->margin }}%">
                        <span class="input-group-text">%</span>
                    </div>
                    <div class="form-hint">Auto-calculated from buying and selling price.</div>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Profit / Unit</label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="text" id="profitDisplay" class="form-control" readonly 
                               value="{{ number_format($item->profit_per_unit, 2) }}">
                    </div>
                    <div class="form-hint">Auto-calculated.</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Opening Stock</label>
                    <input type="number" step="0.01" name="opening_stock" 
                           class="form-control" value="{{ old('opening_stock', $item->opening_stock) }}" min="0" readonly>
                    <div class="form-hint">Initial stock. This cannot be changed after creation.</div>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Opening Stock Value</label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="text" id="openingStockValue" class="form-control" readonly 
                               value="{{ number_format($item->opening_stock_value, 2) }}">
                    </div>
                    <div class="form-hint">Auto-calculated: Opening Stock × Buying Price</div>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Reorder Level</label>
                    <input type="number" step="0.01" name="reorder_level" 
                           class="form-control" value="{{ old('reorder_level', $item->reorder_level) }}" min="0">
                    <div class="form-hint">When stock falls below this level, reorder alert is triggered.</div>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Reorder Quantity</label>
                    <input type="number" step="0.01" name="reorder_quantity" 
                           class="form-control" value="{{ old('reorder_quantity', $item->reorder_quantity) }}" min="0">
                    <div class="form-hint">Quantity to reorder when stock is low.</div>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- UNIT & MEASUREMENT -->
            <!-- ============================================ -->
            <div class="form-section-title">
                <i class="fas fa-ruler-combined"></i> Unit & Measurement
                <span class="badge bg-primary">Required</span>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Unit <span class="required-star">*</span></label>
                    <select name="unit_id" class="form-control @error('unit_id') is-invalid @enderror" required>
                        <option value="">Select Unit</option>
                        @foreach($unitOptions as $unit)
                            <option value="{{ $unit['id'] }}" 
                                {{ old('unit_id', $item->unit_id) == $unit['id'] ? 'selected' : '' }}>
                                {{ $unit['name'] }} ({{ $unit['code'] }})
                            </option>
                        @endforeach
                        <option class="d-none" value="custom" {{ old('unit_id', $item->unit_id) == 'custom' ? 'selected' : '' }}>✏️ Custom Unit</option>
                    </select>
                    @error('unit_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Unit Name</label>
                    <input type="text" name="unit_name" class="form-control" 
                           value="{{ old('unit_name', $item->unit_name) }}" placeholder="Unit Name">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Unit Code</label>
                    <input type="text" name="unit_code" class="form-control" 
                           value="{{ old('unit_code', $item->unit_code) }}" placeholder="Unit Code">
                </div>
            </div>

            <!-- ============================================ -->
            <!-- SHELF LIFE & TRACKING -->
            <!-- ============================================ -->
            <div class="form-section-title">
                <i class="fas fa-clock"></i> Shelf Life & Tracking
                <span class="badge bg-secondary">Optional</span>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Shelf Life (Days)</label>
                    <input type="number" name="shelf_life_days" id="shelfLifeDays" 
                           class="form-control" value="{{ old('shelf_life_days', $item->shelf_life_days) }}" min="0">
                    <div class="form-hint">Number of days this item is valid from manufacturing date.</div>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Manufacturing Date</label>
                    <input type="date" name="manufacturing_date" id="manufacturingDate" 
                           class="form-control" value="{{ old('manufacturing_date', $item->manufacturing_date ? $item->manufacturing_date->format('Y-m-d') : '') }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Expiry Date</label>
                    <input type="date" name="expiry_date" id="expiryDate" 
                           class="form-control @error('expiry_date') is-invalid @enderror" 
                           value="{{ old('expiry_date', $item->expiry_date ? $item->expiry_date->format('Y-m-d') : '') }}">
                    @error('expiry_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Auto-calculated from manufacturing date + shelf life.</div>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Batch Number</label>
                    <input type="text" name="batch_number" class="form-control" 
                           value="{{ old('batch_number', $item->batch_number) }}" placeholder="e.g., BATCH-2024-001">
                    <div class="form-hint">For batch tracking.</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="track_batch" class="form-check-input" id="trackBatch" value="1" 
                               {{ old('track_batch', $item->track_batch) ? 'checked' : '' }}>
                        <label class="form-check-label" for="trackBatch">
                            <i class="fas fa-boxes"></i> Track by Batch
                        </label>
                    </div>
                    <div class="form-hint">Enable batch tracking for this item.</div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="track_serial" class="form-check-input" id="trackSerial" value="1" 
                               {{ old('track_serial', $item->track_serial) ? 'checked' : '' }}>
                        <label class="form-check-label" for="trackSerial">
                            <i class="fas fa-hashtag"></i> Track by Serial Number
                        </label>
                    </div>
                    <div class="form-hint">Enable serial number tracking for this item.</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Serial Number</label>
                    <input type="text" name="serial_number" class="form-control" 
                           value="{{ old('serial_number', $item->serial_number) }}" placeholder="e.g., SN-2024-001">
                    <div class="form-hint">For individual item tracking.</div>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- DEPRECIATION -->
            <!-- ============================================ -->
            <div class="form-section-title">
                <i class="fas fa-chart-line"></i> Depreciation (For Assets)
                <span class="badge bg-warning text-dark">Asset Only</span>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="form-check mt-2">
                        <input type="checkbox" name="depreciation_applicable" class="form-check-input" id="depreciationApplicable" value="1" 
                               {{ old('depreciation_applicable', $item->depreciation_applicable) ? 'checked' : '' }}>
                        <label class="form-check-label" for="depreciationApplicable">
                            <i class="fas fa-calculator"></i> Depreciation Applicable
                        </label>
                    </div>
                    <div class="form-hint">Check if this item depreciates over time (assets).</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Asset Life (Months)</label>
                    <input type="number" name="asset_life_months" class="form-control" 
                           value="{{ old('asset_life_months', $item->asset_life_months) }}" min="0">
                    <div class="form-hint">Expected useful life of this asset in months.</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Depreciation Method</label>
                    <select name="depreciation_method" class="form-control">
                        <option value="straight_line" {{ old('depreciation_method', $item->depreciation_method) == 'straight_line' ? 'selected' : '' }}>Straight Line</option>
                        <option value="declining_balance" {{ old('depreciation_method', $item->depreciation_method) == 'declining_balance' ? 'selected' : '' }}>Declining Balance</option>
                        <option value="sum_of_years" {{ old('depreciation_method', $item->depreciation_method) == 'sum_of_years' ? 'selected' : '' }}>Sum of Years</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Salvage Value</label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="number" step="0.01" name="salvage_value" class="form-control" 
                               value="{{ old('salvage_value', $item->salvage_value) }}" min="0">
                    </div>
                    <div class="form-hint">Estimated value after depreciation.</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Current Book Value</label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="text" class="form-control" readonly 
                               value="{{ number_format($item->book_value, 2) }}">
                    </div>
                    <div class="form-hint">Auto-calculated based on depreciation.</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Remaining Life</label>
                    <input type="text" class="form-control" readonly 
                           value="{{ $item->remaining_life ?? 'N/A' }} months">
                    <div class="form-hint">Months remaining in asset life.</div>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- WEIGHT & DIMENSIONS -->
            <!-- ============================================ -->
            <div class="form-section-title">
                <i class="fas fa-weight"></i> Weight & Dimensions
                <span class="badge bg-secondary">Optional</span>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Weight</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="weight" class="form-control" 
                               value="{{ old('weight', $item->weight) }}">
                        <select name="weight_unit" class="form-control" style="max-width: 80px;">
                            <option value="kg" {{ old('weight_unit', $item->weight_unit) == 'kg' ? 'selected' : '' }}>kg</option>
                            <option value="g" {{ old('weight_unit', $item->weight_unit) == 'g' ? 'selected' : '' }}>g</option>
                            <option value="lb" {{ old('weight_unit', $item->weight_unit) == 'lb' ? 'selected' : '' }}>lb</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Length</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="length" class="form-control" 
                               value="{{ old('length', $item->length) }}">
                        <select name="dimension_unit" class="form-control" style="max-width: 80px;">
                            <option value="cm" {{ old('dimension_unit', $item->dimension_unit) == 'cm' ? 'selected' : '' }}>cm</option>
                            <option value="in" {{ old('dimension_unit', $item->dimension_unit) == 'in' ? 'selected' : '' }}>in</option>
                            <option value="m" {{ old('dimension_unit', $item->dimension_unit) == 'm' ? 'selected' : '' }}>m</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Width</label>
                    <input type="number" step="0.01" name="width" class="form-control" 
                           value="{{ old('width', $item->width) }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Height</label>
                    <input type="number" step="0.01" name="height" class="form-control" 
                           value="{{ old('height', $item->height) }}">
                </div>
            </div>

            <!-- ============================================ -->
            <!-- STATUS -->
            <!-- ============================================ -->
            <div class="form-section-title">
                <i class="fas fa-toggle-on"></i> Status
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="status" class="form-check-input" id="status" value="1" 
                               {{ old('status', $item->status) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">
                            <i class="fas fa-check-circle text-success"></i> Active
                        </label>
                        <span class="text-muted small ms-2">Inactive items cannot be used in inventory operations.</span>
                    </div>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- FORM ACTIONS -->
            <!-- ============================================ -->
            <hr>
            <div class="d-flex gap-3 mt-3">
                <button type="submit" class="btn btn-success-custom">
                    <i class="fas fa-save"></i> Update Item
                </button>
                <a href="{{ route('inventory.items.index') }}" class="btn btn-secondary-custom">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <a href="{{ route('inventory.items.view', $item->id) }}" class="btn btn-primary-custom ms-auto">
                    <i class="fas fa-eye"></i> View Item
                </a>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {

        // =============================================
        // CATEGORY -> SUBCATEGORY DYNAMIC LOADING
        // =============================================
        $('#category_id').on('change', function() {
            let categoryId = $(this).val();
            let currentSubCategoryId = '{{ old('subcategory_id', $item->subcategory_id) }}';
            
            if (!categoryId) {
                $('#subcategory_id').html('<option value="">Select Sub Category</option>');
                return;
            }

            $('#subcategory_id').html('<option>Loading...</option>');

            $.get('/inventory/subcategories/by-category/' + categoryId, function(response) {
                let html = '<option value="">Select Sub Category</option>';
                response.forEach(function(item) {
                    let selected = (item.id == currentSubCategoryId) ? 'selected' : '';
                    html += '<option value="'+item.id+'" '+selected+'>' + item.subcategory_name + ' (' + item.subcategory_code + ')</option>';
                });
                $('#subcategory_id').html(html);
            }).fail(function() {
                $('#subcategory_id').html('<option value="">Error loading subcategories</option>');
            });
        });

        // =============================================
        // WAREHOUSE -> STORE FILTER
        // =============================================
        $('select[name="warehouse_id"]').on('change', function() {
            const warehouseId = $(this).val();
            $('select[name="store_id"] option').each(function() {
                const whId = $(this).data('warehouse-id');
                if ($(this).val() === '') {
                    $(this).show();
                } else if (whId && whId != warehouseId) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
            if ($('select[name="store_id"]').val() && 
                $('select[name="store_id"] option:selected').css('display') === 'none') {
                $('select[name="store_id"]').val('');
            }
        });

        // =============================================
        // AUTO-CALCULATE EXPIRY DATE
        // =============================================
        $('#shelfLifeDays, #manufacturingDate').on('change input', function() {
            let shelfLife = parseInt($('#shelfLifeDays').val()) || 0;
            let manufacturingDate = $('#manufacturingDate').val();
            
            if (manufacturingDate && shelfLife > 0) {
                let date = new Date(manufacturingDate);
                date.setDate(date.getDate() + shelfLife);
                let year = date.getFullYear();
                let month = String(date.getMonth() + 1).padStart(2, '0');
                let day = String(date.getDate()).padStart(2, '0');
                $('#expiryDate').val(year + '-' + month + '-' + day);
            }
        });

        // =============================================
        // CALCULATE MARGIN & PROFIT
        // =============================================
        function calculateMargin() {
            let buying = parseFloat($('input[name="buying_price"]').val()) || 0;
            let selling = parseFloat($('input[name="selling_price"]').val()) || 0;
            
            let margin = 0;
            if (buying > 0) {
                margin = ((selling - buying) / buying) * 100;
            }
            $('#marginDisplay').val(margin.toFixed(2) + '%');
            
            let profit = selling - buying;
            $('#profitDisplay').val(profit.toFixed(2));
            
            let openingStock = parseFloat($('input[name="opening_stock"]').val()) || 0;
            $('#openingStockValue').val((openingStock * buying).toFixed(2));
        }

        $('input[name="buying_price"], input[name="selling_price"]').on('input', function() {
            calculateMargin();
        });

        // =============================================
        // GENERATE SKU
        // =============================================
        $('#generateSku').on('click', function() {
            let name = $('#itemName').val().trim();
            if (!name) {
                alert('Please enter Item Name first to generate SKU.');
                return;
            }
            
            let prefix = name.substring(0, 3).toUpperCase();
            let random = Math.random().toString(36).substring(2, 7).toUpperCase();
            $('#sku').val(prefix + '-' + random);
        });

        // =============================================
        // TOGGLE DEPRECIATION FIELDS
        // =============================================
        $('#depreciationApplicable').on('change', function() {
            if ($(this).is(':checked')) {
                $('.depreciation-section').show();
            } else {
                $('.depreciation-section').hide();
            }
        });

        // =============================================
        // TRACKING TOGGLES
        // =============================================
        $('#trackBatch, #trackSerial').on('change', function() {
            // Show/hide related fields if needed
        });

        // =============================================
        // TRIGGER ON PAGE LOAD
        // =============================================
        // Trigger category change to load subcategories
        if ($('#category_id').val()) {
            $('#category_id').trigger('change');
        }
        
        // Trigger warehouse change to filter stores
        if ($('select[name="warehouse_id"]').val()) {
            $('select[name="warehouse_id"]').trigger('change');
        }
        
        // Calculate initial margin
        calculateMargin();

        // Show/hide depreciation section
        if ($('#depreciationApplicable').is(':checked')) {
            $('.depreciation-section').show();
        }

        console.log('✅ Edit Item Page Loaded');
        console.log('📦 Item: {{ $item->item_name }} ({{ $item->item_code }})');
        console.log('📊 Stock: {{ $item->current_stock }} units');
        console.log('🔄 Use "Generate SKU" button to auto-generate SKU');
        console.log('📅 Expiry date auto-calculates from Manufacturing Date + Shelf Life');
    });
</script>

@endsection