@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4><i class="fas fa-edit"></i> Edit Category</h4>
            <div>
                <a href="{{ route('inventory.categories.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body">
            <form
                method="POST"
                action="{{ route('inventory.categories.update', $category->id) }}"
                enctype="multipart/form-data"
            >
                @csrf
                @method('POST')

                <input
                    type="hidden"
                    name="remove_icon_image"
                    id="remove_icon_image"
                    value="0"
                >

                {{-- Status Toggle Section --}}
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="p-3" style="background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 fw-bold">Category Status</h6>
                                    <small class="text-muted">Current status of this category</small>
                                </div>
                                <div>
                                    @if($category->status)
                                        <span class="badge bg-success" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                                            <i class="fas fa-check-circle"></i> Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                                            <i class="fas fa-times-circle"></i> Inactive
                                        </span>
                                    @endif
                                    <button type="button" class="btn btn-sm {{ $category->status ? 'btn-warning' : 'btn-success' }} ms-2" 
                                            onclick="toggleCategoryStatus({{ $category->id }}, {{ $category->status ? '0' : '1' }})">
                                        <i class="fas {{ $category->status ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                        {{ $category->status ? 'Mark Inactive' : 'Mark Active' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Configuration Selection --}}
                    <div class="col-md-12 mb-3">
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
                                <option value="{{ $config->id }}" 
                                    {{ old('configuration_id', $category->configuration_id) == $config->id ? 'selected' : '' }}>
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
                            Select which inventory configuration this category belongs to.
                            This will determine the costing method and features for items in this category.
                        </small>
                    </div>
                </div>

                {{-- Configuration Details Card (shown when selected) --}}
                <div id="configDetails" style="display: {{ old('configuration_id', $category->configuration_id) ? 'block' : 'none' }}; margin-bottom: 1.5rem;">
                    <div class="card" style="background: #f8fafc; border: 2px solid #e0e7ff;">
                        <div class="card-body">
                            <h6 class="mb-2" style="color: #4338ca;">
                                <i class="fas fa-info-circle"></i> Configuration Details
                            </h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <small class="text-muted">Costing Method</small>
                                    <p class="mb-0" id="configCostingMethod">
                                        @php
                                            $selectedConfig = $configurations->firstWhere('id', old('configuration_id', $category->configuration_id));
                                        @endphp
                                        {{ $selectedConfig ? str_replace('_', ' ', $selectedConfig->costing_method) : '-' }}
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Multi Warehouse</small>
                                    <p class="mb-0" id="configMultiWarehouse">
                                        {{ $selectedConfig ? ($selectedConfig->multi_warehouse ? '✅ Enabled' : '❌ Disabled') : '-' }}
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Barcode</small>
                                    <p class="mb-0" id="configBarcode">
                                        {{ $selectedConfig ? ($selectedConfig->barcode_enabled ? '✅ Enabled' : '❌ Disabled') : '-' }}
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">QR Code</small>
                                    <p class="mb-0" id="configQR">
                                        {{ $selectedConfig ? ($selectedConfig->qr_enabled ? '✅ Enabled' : '❌ Disabled') : '-' }}
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Batch Tracking</small>
                                    <p class="mb-0" id="configBatchTracking">
                                        {{ $selectedConfig ? ($selectedConfig->batch_tracking ? '✅ Enabled' : '❌ Disabled') : '-' }}
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Serial Tracking</small>
                                    <p class="mb-0" id="configSerialTracking">
                                        {{ $selectedConfig ? ($selectedConfig->serial_tracking ? '✅ Enabled' : '❌ Disabled') : '-' }}
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Status</small>
                                    <p class="mb-0" id="configStatus">
                                        {{ $selectedConfig ? ($selectedConfig->status === 'active' ? '✅ Active' : '❌ Inactive') : '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Predefined Category Selection --}}
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">
                            Category Type <span class="text-danger">*</span>
                        </label>
                        <div class="predefined-select-wrapper">
                            <select
                                id="categoryType"
                                class="form-control @error('category_type') is-invalid @enderror"
                                required
                            >
                                <option value="">-- Select Category Type --</option>
                                <option value="custom" {{ $category->isPredefined() ? '' : 'selected' }}>✏️ Add Custom Category</option>
                                <option value="stationery" {{ $category->category_name == 'Stationery' ? 'selected' : '' }}>📝 Stationery</option>
                                <option value="furniture" {{ $category->category_name == 'Furniture' ? 'selected' : '' }}>🪑 Furniture</option>
                                <option value="electronics" {{ $category->category_name == 'Electronics' ? 'selected' : '' }}>💻 Electronics</option>
                                <option value="it_assets" {{ $category->category_name == 'IT Assets' ? 'selected' : '' }}>🖥️ IT Assets</option>
                                <option value="grocery" {{ $category->category_name == 'Grocery' ? 'selected' : '' }}>🛒 Grocery</option>
                                <option value="vegetables" {{ $category->category_name == 'Vegetables' ? 'selected' : '' }}>🥬 Vegetables</option>
                                <option value="fruits" {{ $category->category_name == 'Fruits' ? 'selected' : '' }}>🍎 Fruits</option>
                                <option value="dairy" {{ $category->category_name == 'Dairy' ? 'selected' : '' }}>🥛 Dairy</option>
                                <option value="kitchen_items" {{ $category->category_name == 'Kitchen Items' ? 'selected' : '' }}>🍳 Kitchen Items</option>
                                <option value="cleaning_material" {{ $category->category_name == 'Cleaning Material' ? 'selected' : '' }}>🧹 Cleaning Material</option>
                                <option value="uniform" {{ $category->category_name == 'Uniform' ? 'selected' : '' }}>👕 Uniform</option>
                                <option value="books" {{ $category->category_name == 'Books' ? 'selected' : '' }}>📚 Books</option>
                                <option value="medicines" {{ $category->category_name == 'Medicines' ? 'selected' : '' }}>💊 Medicines</option>
                                <option value="sports_items" {{ $category->category_name == 'Sports Items' ? 'selected' : '' }}>⚽ Sports Items</option>
                                <option value="consumables" {{ $category->category_name == 'Consumables' ? 'selected' : '' }}>📦 Consumables</option>
                                <option value="non_consumables" {{ $category->category_name == 'Non Consumables' ? 'selected' : '' }}>🏷️ Non Consumables</option>
                            </select>
                            <span class="badge-custom" id="categoryTypeBadge">{{ $category->isPredefined() ? $category->category_name : 'Custom' }}</span>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Select a predefined category or choose "Add Custom Category" to create your own.
                        </small>
                    </div>
                </div>

                {{-- Category Name & Code --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Category Name <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <span class="input-group-text" id="categoryPrefix">
                                {{ $category->category_code ? explode('-', $category->category_code)[0] : 'CAT' }}
                            </span>

                            <input
                                type="text"
                                id="categoryName"
                                name="category_name"
                                value="{{ old('category_name', $category->category_name) }}"
                                class="form-control @error('category_name') is-invalid @enderror"
                                required
                                readonly
                            >
                        </div>
                        @error('category_name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                        <div id="categoryNameHelp" class="form-text text-muted" style="font-size: 0.75rem; margin-top: 0.25rem;">
                            <i class="fas {{ $category->isPredefined() ? 'fa-lock' : 'fa-unlock' }}" id="nameLockIcon"></i> 
                            <span id="nameLockText">{{ $category->isPredefined() ? 'Predefined category - locked' : 'Custom category - editable' }}</span>
                        </div>
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
                                value="{{ old('category_code', $category->category_code) }}"
                                class="form-control @error('category_code') is-invalid @enderror"
                                placeholder="UNI-A7K2"
                                required
                                readonly
                            >

                            <button type="button" id="generateCategoryCode" class="btn btn-primary" {{ $category->isPredefined() ? 'disabled' : '' }}>
                                <i class="fas {{ $category->isPredefined() ? 'fa-lock' : 'fa-sync-alt' }}"></i> 
                                {{ $category->isPredefined() ? 'Generated' : 'Generate' }}
                            </button>
                        </div>
                        @error('category_code')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                        <div id="categoryCodeHelp" class="form-text text-muted" style="font-size: 0.75rem; margin-top: 0.25rem;">
                            <i class="fas {{ $category->isPredefined() ? 'fa-lock' : 'fa-unlock' }}" id="codeLockIcon"></i> 
                            <span id="codeLockText">{{ $category->isPredefined() ? 'Predefined code - locked' : 'Custom code - editable' }}</span>
                        </div>
                    </div>
                </div>

                <div class="row align-items-end">
                    {{-- Icon --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label"> System Icon </label>

                        <select
                            name="icon"
                            id="iconSelect"
                            class="form-control"
                        >
                            <option value="">Select Icon</option>
                            <option value="fa-shirt" {{ old('icon', $category->icon) == 'fa-shirt' ? 'selected' : '' }}>👕 Uniform</option>
                            <option value="fa-laptop" {{ old('icon', $category->icon) == 'fa-laptop' ? 'selected' : '' }}>💻 Electronics</option>
                            <option value="fa-pencil" {{ old('icon', $category->icon) == 'fa-pencil' ? 'selected' : '' }}>✏️ Stationery</option>
                            <option value="fa-couch" {{ old('icon', $category->icon) == 'fa-couch' ? 'selected' : '' }}>🛋 Furniture</option>
                            <option value="fa-futbol" {{ old('icon', $category->icon) == 'fa-futbol' ? 'selected' : '' }}>⚽ Sports</option>
                            <option value="fa-pills" {{ old('icon', $category->icon) == 'fa-pills' ? 'selected' : '' }}>💊 Medicine</option>
                            <option value="fa-book" {{ old('icon', $category->icon) == 'fa-book' ? 'selected' : '' }}>📚 Books</option>
                            <option value="fa-computer" {{ old('icon', $category->icon) == 'fa-computer' ? 'selected' : '' }}>🖥 IT Assets</option>
                            <option value="fa-box" {{ old('icon', $category->icon) == 'fa-box' ? 'selected' : '' }}>📦 Inventory</option>
                            <option value="fa-apple-alt" {{ old('icon', $category->icon) == 'fa-apple-alt' ? 'selected' : '' }}>🍎 Fruits</option>
                            <option value="fa-carrot" {{ old('icon', $category->icon) == 'fa-carrot' ? 'selected' : '' }}>🥕 Vegetables</option>
                            <option value="fa-cheese" {{ old('icon', $category->icon) == 'fa-cheese' ? 'selected' : '' }}>🧀 Dairy</option>
                            <option value="fa-utensils" {{ old('icon', $category->icon) == 'fa-utensils' ? 'selected' : '' }}>🍳 Kitchen</option>
                            <option value="fa-broom" {{ old('icon', $category->icon) == 'fa-broom' ? 'selected' : '' }}>🧹 Cleaning</option>
                            <option value="fa-shopping-cart" {{ old('icon', $category->icon) == 'fa-shopping-cart' ? 'selected' : '' }}>🛒 Grocery</option>
                        </select>
                    </div>

                    {{-- Color --}}
                    <div class="col-md-2 mb-3">
                        <label class="form-label"> Icon Color </label>

                        <input
                            type="color"
                            id="iconColor"
                            name="icon_color"
                            value="{{ old('icon_color', $category->icon_color ?? '#0d6efd') }}"
                            class="form-control form-control-color"
                        />
                    </div>

                    {{-- Upload --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label"> Upload Custom Icon </label>

                        <input
                            type="file"
                            id="iconImage"
                            name="icon_image"
                            accept=".png,.jpg,.jpeg,.svg,.webp,image/png,image/jpeg,image/svg+xml,image/webp"
                            class="form-control"
                        />
                        @error('icon_image')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Allowed: PNG, JPG, JPEG, SVG, WEBP (Max 1 MB)
                        </small>
                    </div>

                    {{-- Preview --}}
                    <div class="col-md-2 mb-3 text-center">
                        <label class="form-label d-block"> Preview </label>

                        <div
                            id="previewWrapper"
                            style="position: relative; display: inline-block"
                        >
                            <div id="previewContainer">
                                @if($category->icon_image)
                                    <img
                                        src="{{ asset('storage/' . $category->icon_image) }}"
                                        style="width:60px;height:60px;object-fit:contain;border-radius:8px;"
                                        id="previewImage"
                                    >
                                @elseif($category->icon)
                                    <i
                                        class="fa-solid {{ $category->icon }}"
                                        style="font-size:50px;color:{{ $category->icon_color ?? '#0d6efd' }};"
                                    ></i>
                                @else
                                    <i
                                        class="fa-solid fa-icons"
                                        style="font-size:50px;color:#0d6efd;"
                                    ></i>
                                @endif
                            </div>

                            <span
                                id="clearVisual"
                                style="
                                    display: {{ ($category->icon_image || $category->icon) ? 'block' : 'none' }};
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
                                "
                            >
                                ×
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Description --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label">
                            Description
                            <span class="text-muted small">(Optional)</span>
                        </label>

                        <textarea
                            name="description"
                            rows="3"
                            class="form-control"
                        >{{ old('description', $category->description) }}</textarea>
                    </div>
                </div>

                <hr />

                {{-- Sub Categories Section --}}
                <div
                    class="d-flex justify-content-between align-items-center mb-3"
                >
                    <h5 class="mb-0">
                        <i class="fas fa-tags"></i> Sub Categories
                    </h5>

                    <button
                        type="button"
                        id="addSubCategory"
                        class="btn btn-primary btn-sm"
                    >
                        <i class="fas fa-plus"></i> Add Sub Category
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="subcategoryTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 35%;">Name <span class="text-danger">*</span></th>
                                <th style="width: 45%;">Code <span class="text-danger">*</span></th>
                                <th style="width: 20%;">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($category->subCategories as $index => $sub)
                                <tr>
                                    <td>
                                        <input
                                            type="text"
                                            name="subcategories[{{ $index }}][name]"
                                            value="{{ $sub->subcategory_name }}"
                                            class="form-control subcategory-name"
                                            placeholder="Enter sub category name"
                                            required
                                        />
                                        <input
                                            type="hidden"
                                            name="subcategories[{{ $index }}][id]"
                                            value="{{ $sub->id }}"
                                        />
                                    </td>

                                    <td>
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                name="subcategories[{{ $index }}][code]"
                                                value="{{ $sub->subcategory_code }}"
                                                class="form-control subcategory-code"
                                                placeholder="UNI-BOY-X8P2"
                                                required
                                                readonly
                                            >

                                            <button type="button" class="btn btn-primary generateSubCode" disabled>
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger removeRow" title="Remove sub category">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td>
                                        <input
                                            type="text"
                                            name="subcategories[0][name]"
                                            class="form-control subcategory-name"
                                            placeholder="Enter sub category name"
                                            required
                                        />
                                    </td>

                                    <td>
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                name="subcategories[0][code]"
                                                class="form-control subcategory-code"
                                                placeholder="UNI-BOY-X8P2"
                                                required
                                            >

                                            <button type="button" class="btn btn-primary generateSubCode">
                                                <i class="fas fa-sync-alt"></i> Generate
                                            </button>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger removeRow">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Sub categories are optional. You can add multiple sub categories to organize your inventory.
                    </small>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Update Category
                    </button>

                    <a href="{{ route('inventory.categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Status Toggle Confirmation Modal --}}
<div class="modal fade" id="statusToggleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
            <div class="modal-header" style="border-bottom: 2px solid #fef3c7; background: #fffbeb; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title" style="color: #92400e; font-weight: 700;">
                    <i class="fas fa-exclamation-triangle" style="color: #f59e0b;"></i>
                    Confirm Status Change
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <i class="fas fa-exchange-alt" style="font-size: 3rem; color: #f59e0b;"></i>
                </div>
                <h6 style="color: var(--text-dark); font-weight: 600; margin-bottom: 0.5rem; text-align: center;">
                    Are you sure you want to <span id="statusActionText" style="font-weight: 700;"></span> this category?
                </h6>
                <p style="color: var(--text-muted); text-align: center; font-size: 0.9rem;" id="statusDescription">
                    This will <span id="statusEffectText"></span> the category <strong>"{{ $category->category_name }}"</strong>.
                </p>
                <div style="background: #f8fafc; padding: 1rem; border-radius: 12px; border: 1px solid var(--border-color); margin-top: 1rem;">
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">
                        <i class="fas fa-info-circle"></i> 
                        <span id="statusInfoText"></span>
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 2px solid var(--border-color); background: #f8fafc; border-radius: 0 0 16px 16px;">
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

<style>
    .predefined-select-wrapper {
        position: relative;
    }

    .predefined-select-wrapper .badge-custom {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: #e0e7ff;
        color: #4338ca;
        font-size: 0.6rem;
        font-weight: 600;
        padding: 0.15rem 0.6rem;
        border-radius: 50px;
        pointer-events: none;
    }

    .form-control[readonly] {
        background: #f1f5f9;
        cursor: not-allowed;
        opacity: 0.7;
    }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    // ============================================
    // PREDEFINED CATEGORIES DATA
    // ============================================
    const predefinedCategories = {
        stationery: {
            name: 'Stationery',
            prefix: 'STA',
            icon: 'fa-pencil',
            color: '#0d6efd'
        },
        furniture: {
            name: 'Furniture',
            prefix: 'FUR',
            icon: 'fa-couch',
            color: '#8b5cf6'
        },
        electronics: {
            name: 'Electronics',
            prefix: 'ELE',
            icon: 'fa-laptop',
            color: '#0ea5e9'
        },
        it_assets: {
            name: 'IT Assets',
            prefix: 'ITA',
            icon: 'fa-computer',
            color: '#6366f1'
        },
        grocery: {
            name: 'Grocery',
            prefix: 'GRO',
            icon: 'fa-shopping-cart',
            color: '#f59e0b'
        },
        vegetables: {
            name: 'Vegetables',
            prefix: 'VEG',
            icon: 'fa-carrot',
            color: '#22c55e'
        },
        fruits: {
            name: 'Fruits',
            prefix: 'FRU',
            icon: 'fa-apple-alt',
            color: '#ef4444'
        },
        dairy: {
            name: 'Dairy',
            prefix: 'DAI',
            icon: 'fa-cheese',
            color: '#fbbf24'
        },
        kitchen_items: {
            name: 'Kitchen Items',
            prefix: 'KIT',
            icon: 'fa-utensils',
            color: '#f97316'
        },
        cleaning_material: {
            name: 'Cleaning Material',
            prefix: 'CLE',
            icon: 'fa-broom',
            color: '#06b6d4'
        },
        uniform: {
            name: 'Uniform',
            prefix: 'UNI',
            icon: 'fa-shirt',
            color: '#8b5cf6'
        },
        books: {
            name: 'Books',
            prefix: 'BOO',
            icon: 'fa-book',
            color: '#6366f1'
        },
        medicines: {
            name: 'Medicines',
            prefix: 'MED',
            icon: 'fa-pills',
            color: '#ef4444'
        },
        sports_items: {
            name: 'Sports Items',
            prefix: 'SPO',
            icon: 'fa-futbol',
            color: '#f59e0b'
        },
        consumables: {
            name: 'Consumables',
            prefix: 'CON',
            icon: 'fa-box',
            color: '#14b8a6'
        },
        non_consumables: {
            name: 'Non Consumables',
            prefix: 'NON',
            icon: 'fa-tag',
            color: '#8b5cf6'
        }
    };

    // ============================================
    // STATUS TOGGLE
    // ============================================

    function toggleCategoryStatus(id, newStatus) {
        const modal = document.getElementById('statusToggleModal');
        const actionText = document.getElementById('statusActionText');
        const effectText = document.getElementById('statusEffectText');
        const infoText = document.getElementById('statusInfoText');

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
            form.action = `{{ route('inventory.categories.toggle-status', '') }}/${id}`;
            form.innerHTML = `
                @csrf
                @method('POST')
                <input type="hidden" name="status" value="${newStatus}">
            `;
            document.body.appendChild(form);
            form.submit();
        };
    }

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
    // CATEGORY TYPE SELECTION HANDLER
    // ============================================
    $('#categoryType').on('change', function() {
        const selected = $(this).val();
        const nameInput = $('#categoryName');
        const codeInput = $('#category_code');
        const generateBtn = $('#generateCategoryCode');
        const isEditMode = true;
        
        // Update badge text
        const badge = $('#categoryTypeBadge');
        const selectedText = $(this).find('option:selected').text();
        badge.text(selectedText || 'Select Type');

        // Reset fields
        if (!isEditMode || selected === 'custom') {
            nameInput.val('');
            codeInput.val('');
        }
        nameInput.removeAttr('readonly');
        codeInput.removeAttr('readonly');
        
        // Reset icon and color
        $('#iconSelect').val('');
        $('#iconColor').val('#0d6efd');
        
        // Clear preview
        if (!$('#iconImage').val()) {
            $('#previewContainer').html('<i class="fa-solid fa-icons" style="font-size:50px;color:#0d6efd;"></i>');
            $('#clearVisual').hide();
        }

        if (selected === 'custom') {
            // Custom category - enable fields for manual entry
            nameInput.removeAttr('readonly');
            codeInput.removeAttr('readonly');
            nameInput.attr('placeholder', 'Enter custom category name');
            codeInput.attr('placeholder', 'Enter custom code or generate');
            generateBtn.prop('disabled', false);
            generateBtn.html('<i class="fas fa-sync-alt"></i> Generate');
            
            // Preserve existing values if in edit mode
            @if(isset($category))
                nameInput.val('{{ old('category_name', $category->category_name) }}');
                codeInput.val('{{ old('category_code', $category->category_code) }}');
            @endif
            
            $('#nameLockIcon').removeClass('fa-lock').addClass('fa-unlock');
            $('#nameLockText').text('Enter a custom category name');
            $('#codeLockIcon').removeClass('fa-lock').addClass('fa-unlock');
            $('#codeLockText').text('Enter a custom code or generate');
            
            // Update prefix
            const currentName = nameInput.val().trim();
            $('#categoryPrefix').text(currentName ? generatePrefix(currentName) : 'CAT');
            
            // Allow editing
            nameInput.css('background', '#f8fafc');
            codeInput.css('background', '#f8fafc');
            
        } else if (selected && predefinedCategories[selected]) {
            // Predefined category - lock fields and auto-fill
            const data = predefinedCategories[selected];
            nameInput.val(data.name);
            codeInput.val(data.prefix + '-' + randomCode());
            
            nameInput.attr('readonly', true);
            codeInput.attr('readonly', true);
            nameInput.attr('placeholder', '');
            codeInput.attr('placeholder', '');
            
            generateBtn.prop('disabled', true);
            generateBtn.html('<i class="fas fa-lock"></i> Generated');
            
            // Auto-fill icon and color
            if (data.icon) {
                $('#iconSelect').val(data.icon);
                if (data.color) {
                    $('#iconColor').val(data.color);
                }
                // Update preview
                $('#previewContainer').html(
                    '<i class="fa-solid ' + data.icon + '" style="font-size:50px;color:' + (data.color || '#0d6efd') + ';"></i>'
                );
                $('#clearVisual').show();
            }
            
            // Update prefix display
            $('#categoryPrefix').text(data.prefix);
            
            // Update help text
            $('#nameLockIcon').removeClass('fa-unlock').addClass('fa-lock');
            $('#nameLockText').text('Predefined category - locked');
            $('#codeLockIcon').removeClass('fa-unlock').addClass('fa-lock');
            $('#codeLockText').text('Predefined code - locked');
            
            // Style to show locked state
            nameInput.css('background', '#f1f5f9');
            codeInput.css('background', '#f1f5f9');
            
        } else {
            // No selection - disabled state
            nameInput.attr('readonly', true);
            codeInput.attr('readonly', true);
            nameInput.attr('placeholder', 'Select a category type above');
            codeInput.attr('placeholder', 'Select a category type above');
            generateBtn.prop('disabled', true);
            generateBtn.html('<i class="fas fa-sync-alt"></i> Generate');
            
            $('#nameLockIcon').removeClass('fa-unlock').addClass('fa-lock');
            $('#nameLockText').text('Select a category type above');
            $('#codeLockIcon').removeClass('fa-unlock').addClass('fa-lock');
            $('#codeLockText').text('Select a category type above');
            
            nameInput.css('background', '#f1f5f9');
            codeInput.css('background', '#f1f5f9');
            $('#categoryPrefix').text('CAT');
        }
    });

    // ============================================
    // CODE GENERATION LOGIC
    // ============================================

    // Generate button click
    $('#generateCategoryCode').on('click', function() {
        const categoryType = $('#categoryType').val();
        
        // Only allow generation for custom categories
        if (categoryType !== 'custom') {
            alert('Code is auto-generated for predefined categories. Select "Add Custom Category" to generate a custom code.');
            return;
        }
        
        let categoryName = $('#categoryName').val().trim();
        if (categoryName === '') {
            alert('Enter Category Name First');
            return;
        }

        let prefix = generatePrefix(categoryName);
        let newCode = prefix + '-' + randomCode();
        $('#category_code').val(newCode);
        
        // Disable generate button after generating
        $(this).prop('disabled', true);
        $(this).html('<i class="fas fa-lock"></i> Generated');
    });

    // When category name changes in custom mode, update prefix
    $('#categoryName').on('input', function() {
        const categoryType = $('#categoryType').val();
        if (categoryType === 'custom') {
            const name = $(this).val().trim();
            $('#categoryPrefix').text(name ? generatePrefix(name) : 'CAT');
        }
    });

    // ============================================
    // SUB CATEGORY CODE GENERATION
    // ============================================

    function updateSubCategoryGenerateButtons() {
        const categoryCode = $('#category_code').val().trim();
        const hasCategoryCode = categoryCode !== '';

        $('.generateSubCode').each(function() {
            const row = $(this).closest('tr');
            const subCode = row.find('.subcategory-code').val().trim();

            if (subCode !== '') {
                $(this).prop('disabled', true);
                $(this).html('<i class="fas fa-lock"></i>');
            } else if (hasCategoryCode) {
                const subName = row.find('.subcategory-name').val().trim();
                if (subName !== '') {
                    $(this).prop('disabled', false);
                    $(this).html('<i class="fas fa-sync-alt"></i> Generate');
                } else {
                    $(this).prop('disabled', true);
                    $(this).html('<i class="fas fa-sync-alt"></i> Generate');
                }
            } else {
                $(this).prop('disabled', true);
                $(this).html('<i class="fas fa-sync-alt"></i> Generate');
            }
        });
    }

    // Subcategory name change - clear code and enable generate
    $(document).on('input', '.subcategory-name', function() {
        const row = $(this).closest('tr');
        const codeInput = row.find('.subcategory-code');
        codeInput.val('');

        const categoryCode = $('#category_code').val().trim();
        const subName = $(this).val().trim();
        const generateBtn = row.find('.generateSubCode');

        if (categoryCode !== '' && subName !== '') {
            generateBtn.prop('disabled', false);
            generateBtn.html('<i class="fas fa-sync-alt"></i> Generate');
        } else {
            generateBtn.prop('disabled', true);
            generateBtn.html('<i class="fas fa-sync-alt"></i> Generate');
        }
    });

    // Subcategory generate button click
    $(document).on('click', '.generateSubCode', function() {
        const row = $(this).closest('tr');
        const categoryCode = $('#category_code').val().trim();

        if (categoryCode === '') {
            alert('Generate Category Code First');
            return;
        }

        const subCategoryName = row.find('.subcategory-name').val().trim();

        if (subCategoryName === '') {
            alert('Enter Sub Category Name First');
            return;
        }

        const categoryPrefix = categoryCode.split('-')[0];
        const subPrefix = generatePrefix(subCategoryName);
        const newCode = categoryPrefix + '-' + subPrefix + '-' + randomCode();

        row.find('.subcategory-code').val(newCode);

        $(this).prop('disabled', true);
        $(this).html('<i class="fas fa-lock"></i>');
    });

    // ============================================
    // HELPER FUNCTIONS
    // ============================================

    function randomCode(length = 4) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let result = '';
        for (let i = 0; i < length; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return result;
    }

    function generatePrefix(name) {
        name = name.trim();
        if (!name) return 'CAT';

        let words = name.replace(/&/g, ' ').split(/\s+/).filter(w =>
            !['and', 'of', 'the'].includes(w.toLowerCase())
        );

        if (words.length === 1) {
            return words[0].substring(0, 3).toUpperCase();
        }
        return words.map(w => w.charAt(0)).join('').toUpperCase();
    }

    // ============================================
    // ICON PREVIEW LOGIC
    // ============================================

    let defaultPreview = '<i class="fa-solid fa-icons" style="font-size:50px;color:#0d6efd;"></i>';

    $("#iconSelect").on("change", function () {
        let iconClass = $(this).val();

        if (iconClass !== "") {
            $("#iconImage").val("");
            $('#remove_icon_image').val(0);

            $("#previewContainer").html(
                '<i class="fa-solid ' + iconClass + '" style="font-size:50px;color:' + $("#iconColor").val() + ';"></i>'
            );

            $("#clearVisual").show();
        } else {
            if (!$("#iconImage").val()) {
                restoreOriginalPreview();
            }
        }
    });

    $("#iconColor").on("input", function () {
        let icon = $("#iconSelect").val();
        if (icon !== "") {
            $("#previewContainer").html(
                '<i class="fa-solid ' + icon + '" style="font-size:50px;color:' + $(this).val() + ';"></i>'
            );
        }
    });

    $("#iconImage").on("change", function () {
        let file = this.files[0];

        if (!file) {
            if (!$("#iconSelect").val()) {
                restoreOriginalPreview();
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
        $('#remove_icon_image').val(0);

        let reader = new FileReader();
        reader.onload = function (e) {
            $("#previewContainer").html(
                '<img src="' + e.target.result + '" style="width:60px;height:60px;object-fit:contain;border-radius:8px;">'
            );
            $("#clearVisual").show();
        };
        reader.readAsDataURL(file);
    });

    function restoreOriginalPreview() {
        @if($category->icon_image)
            $('#previewContainer').html(
                '<img src="{{ asset('storage/' . $category->icon_image) }}" style="width:60px;height:60px;object-fit:contain;border-radius:8px;">'
            );
            $('#clearVisual').show();
        @elseif($category->icon)
            $('#previewContainer').html(
                '<i class="fa-solid {{ $category->icon }}" style="font-size:50px;color:{{ $category->icon_color ?? '#0d6efd' }};"></i>'
            );
            $('#clearVisual').show();
        @else
            $('#previewContainer').html(defaultPreview);
            $('#clearVisual').hide();
        @endif

        $('#iconSelect').val('{{ old('icon', $category->icon) }}');
        $('#iconColor').val('{{ old('icon_color', $category->icon_color ?? '#0d6efd') }}');
    }

    $("#clearVisual").on("click", function () {
        $("#iconSelect").val("");
        $("#iconImage").val("");
        $('#remove_icon_image').val(1);
        $("#previewContainer").html(defaultPreview);
        $(this).hide();
    });

    // ============================================
    // DYNAMIC SUB CATEGORIES
    // ============================================

    let index = {{ count($category->subCategories) > 0 ? count($category->subCategories) : 1 }};
    $("#addSubCategory").click(function () {
        $("#subcategoryTable tbody").append(`
            <tr>
                <td>
                    <input type="text" name="subcategories[${index}][name]" class="form-control subcategory-name" placeholder="Enter sub category name" required>
                </td>
                <td>
                    <div class="input-group">
                        <input type="text" name="subcategories[${index}][code]" class="form-control subcategory-code" placeholder="UNI-BOY-X8P2" required>
                        <button type="button" class="btn btn-primary generateSubCode">
                            <i class="fas fa-sync-alt"></i> Generate
                        </button>
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger removeRow" title="Remove sub category">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);
        index++;

        setTimeout(function() {
            updateSubCategoryGenerateButtons();
        }, 50);
    });

    $(document).on("click", ".removeRow", function () {
        if ($('#subcategoryTable tbody tr').length > 1) {
            if (confirm('Are you sure you want to remove this sub category?')) {
                $(this).closest("tr").remove();
            }
        } else {
            alert('You must keep at least one sub category row. You can leave the fields empty if not needed.');
        }
    });

    // ============================================
    // INITIALIZE
    // ============================================

    $(document).ready(function() {
        // Trigger configuration details
        if ($('#configuration_id').val()) {
            $('#configuration_id').trigger('change');
        }

        // Initialize subcategory generate buttons
        setTimeout(function() {
            updateSubCategoryGenerateButtons();
        }, 100);

        // Trigger category type change based on current category
        const currentCategoryType = $('#categoryType').val();
        if (currentCategoryType) {
            $('#categoryType').trigger('change');
        }

        // If category code exists, lock it
        const existingCode = $('#category_code').val().trim();
        if (existingCode && $('#categoryType').val() !== 'custom') {
            $('#generateCategoryCode').prop('disabled', true);
            $('#generateCategoryCode').html('<i class="fas fa-lock"></i> Generated');
        }
    });

    // Update subcategory buttons when category code changes
    $(document).on('change', '#category_code', function() {
        updateSubCategoryGenerateButtons();
    });
</script>
@endsection