{{-- resources/views/instituteAdmin/Inventory/warehouse/create.blade.php --}}

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
        --step-active: #4361ee;
        --step-inactive: #e2e8f0;
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

    .step-progress {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2rem;
        padding: 0 1rem;
    }

    .step-item {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        position: relative;
    }

    .step-item:not(:last-child)::after {
        content: '';
        flex: 1;
        height: 3px;
        background: var(--step-inactive);
        margin: 0 10px;
        transition: var(--transition);
    }

    .step-item.active:not(:last-child)::after {
        background: var(--step-active);
    }

    .step-item.completed:not(:last-child)::after {
        background: var(--step-active);
    }

    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--step-inactive);
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        transition: var(--transition);
        flex-shrink: 0;
    }

    .step-item.active .step-number {
        background: var(--step-active);
        color: white;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .step-item.completed .step-number {
        background: #10b981;
        color: white;
    }

    .step-label {
        font-weight: 600;
        color: var(--text-muted);
        font-size: 0.85rem;
        white-space: nowrap;
    }

    .step-item.active .step-label {
        color: var(--text-dark);
    }

    .step-item.completed .step-label {
        color: #10b981;
    }

    .form-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        box-shadow: var(--card-shadow);
        border: 2px solid var(--border-color);
    }

    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.85rem;
        margin-bottom: 0.4rem;
    }

    .form-control, .form-select {
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .form-control:focus, .form-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .form-control:disabled, .form-select:disabled {
        background-color: #f1f5f9;
        cursor: not-allowed;
    }

    .text-danger {
        color: #dc3545;
    }

    .text-muted {
        color: var(--text-muted);
    }

    .text-success {
        color: #10b981;
    }

    .location-section {
        background: #f8fafc;
        border-radius: 16px;
        padding: 1.5rem;
        border: 2px solid var(--border-color);
        margin-bottom: 1.5rem;
    }

    .location-section .section-title {
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        border: none;
        color: white;
        border-radius: 12px;
        padding: 0.6rem 2rem;
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.35);
    }

    .btn-secondary {
        background: #f1f5f9;
        border: 2px solid var(--border-color);
        color: var(--text-dark);
        border-radius: 12px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }

    .btn-location {
        background: #10b981;
        border: none;
        color: white;
        border-radius: 12px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-location:hover {
        background: #059669;
        transform: translateY(-2px);
        color: white;
    }

    .btn-location:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .form-check-input {
        border: 2px solid var(--border-color);
        border-radius: 6px;
        width: 18px;
        height: 18px;
        margin-top: 0.1rem;
    }

    .form-check-input:checked {
        background-color: #4361ee;
        border-color: #4361ee;
    }

    .form-check-input:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .form-check-label {
        font-weight: 500;
        color: var(--text-dark);
        margin-left: 0.5rem;
    }

    .step-indicator {
        text-align: center;
        margin-bottom: 2rem;
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .step-indicator strong {
        color: var(--text-dark);
    }

    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(67, 97, 238, 0.3);
        border-radius: 50%;
        border-top-color: #4361ee;
        animation: spin 0.8s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .location-status {
        padding: 10px 15px;
        border-radius: 8px;
        margin-top: 10px;
        font-size: 0.9rem;
    }

    .location-status.info {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #93c5fd;
    }

    .location-status.success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }

    .location-status.error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .location-status.loading {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fcd34d;
    }

    .auto-filled-badge {
        display: inline-block;
        background: #dbeafe;
        color: #1e40af;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-left: 8px;
    }

    .warehouse-info-box {
        background: #eff6ff;
        border: 2px solid #93c5fd;
        border-radius: 12px;
        padding: 15px;
        margin-top: 10px;
        display: none;
    }

    .warehouse-info-box.show {
        display: block;
    }

    .warehouse-info-box .info-row {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        border-bottom: 1px solid #dbeafe;
    }

    .warehouse-info-box .info-row:last-child {
        border-bottom: none;
    }

    .warehouse-info-box .label {
        font-weight: 600;
        color: var(--text-dark);
    }

    .warehouse-info-box .value {
        color: var(--text-muted);
    }

    .branch-context {
        background: #f1f5f9;
        padding: 10px 15px;
        border-radius: 12px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
        border-left: 4px solid #4361ee;
    }

    .branch-context i {
        color: #4361ee;
    }

    .branch-context .branch-name {
        font-weight: 600;
        color: var(--text-dark);
    }

    .default-warehouse-notice {
        background: #fef3c7;
        border: 2px solid #fcd34d;
        border-radius: 12px;
        padding: 15px;
        margin: 10px 0;
        display: none;
    }

    .default-warehouse-notice.show {
        display: block;
    }

    .default-warehouse-notice i {
        color: #d97706;
    }

    .default-badge {
        background: #fef3c7;
        color: #d97706;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
        border: 1px solid #fcd34d;
        margin-left: 8px;
    }

    .default-badge i {
        color: #d97706;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .form-card {
            padding: 1.25rem;
        }

        .location-section {
            padding: 1rem;
        }

        .step-progress {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .step-item:not(:last-child)::after {
            display: none;
        }

        .step-label {
            font-size: 0.75rem;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-warehouse"></i>Add Warehouse</h1>
            <p>Step 1 of 2: Create warehouse location</p>
        </div>
        <a href="{{ route('inventory.warehouses.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Cancel
        </a>
    </div>

    <!-- Step Progress -->
    <div class="step-progress">
        <div class="step-item active">
            <div class="step-number">1</div>
            <div class="step-label">Warehouse Details</div>
        </div>
        <div class="step-item">
            <div class="step-number">2</div>
            <div class="step-label">Store Details</div>
        </div>
    </div>

    <div class="step-indicator">
        <strong>Step 1 of 2:</strong> Enter warehouse information. After saving, you'll add stores to this warehouse.
    </div>

    <!-- Branch Context (Hidden for now) -->
    <div class="branch-context" style="display: none;">
        <i class="fas fa-code-branch"></i>
        <span>Creating for Branch: <span class="branch-name">{{ auth()->user()->branch?->branch_name ?? 'Main Institute' }}</span></span>
    </div>

    <!-- Default Warehouse Notice -->
    <div class="default-warehouse-notice" id="defaultWarehouseNotice">
        <i class="fas fa-info-circle"></i>
        <span>This will be the <strong>first warehouse</strong> and will be automatically set as <strong>default</strong>.</span>
    </div>

    <!-- Form -->
    <div class="form-card">
        <form method="POST" action="{{ route('inventory.warehouses.store') }}" id="warehouseForm">
            @csrf
            <input type="hidden" name="step" value="1">
            
            <!-- Add hidden field for branch_id (auto-filled from auth) -->
            <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">

            <!-- Basic Information -->
            <h5 class="mb-3"><i class="fas fa-info-circle" style="color: #4361ee;"></i> Basic Information</h5>
            <!-- Location Information -->
            <div class="location-section">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Block <span class="text-danger">*</span></label>
                        <select name="block_id" id="block_id" class="form-select @error('block_id') is-invalid @enderror" required>
                            <option value="">Select Block</option>
                            @foreach($blocks as $block)
                                <option value="{{ $block->id }}" {{ old('block_id') == $block->id ? 'selected' : '' }}>
                                    {{ $block->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('block_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Floor <span class="text-danger">*</span></label>
                        <select name="floor_id" id="floor_id" class="form-select @error('floor_id') is-invalid @enderror" required>
                            <option value="">Select Floor</option>
                            @if(old('block_id'))
                                @foreach($floors as $floor)
                                    @if($floor->block_id == old('block_id'))
                                        <option value="{{ $floor->id }}" {{ old('floor_id') == $floor->id ? 'selected' : '' }}>
                                            {{ $floor->floor_name }}
                                        </option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                        @error('floor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted" id="floor_hint">Please select a block first</small>
                    </div>

                    <!-- Warehouse Name - Auto-filled and read-only -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Warehouse Name <span class="text-danger">*</span>
                            <span class="auto-filled-badge" id="autoFillBadge" style="display: none;">
                                <i class="fas fa-magic"></i> Auto-filled
                            </span>
                        </label>
                        <input type="text" name="warehouse_name" id="warehouse_name" 
                            class="form-control @error('warehouse_name') is-invalid @enderror" 
                            value="{{ old('warehouse_name') }}" 
                            placeholder="Select a floor to auto-fill" 
                            readonly required>
                        @error('warehouse_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted" id="warehouse_name_hint">Auto-filled from selected floor</small>
                    </div>

                    <!-- Warehouse Code - Auto-filled and read-only -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Warehouse Code <span class="text-danger">*</span>
                            <span class="auto-filled-badge" id="autoFillBadgeCode" style="display: none;">
                                <i class="fas fa-magic"></i> Auto-filled
                            </span>
                        </label>
                        <input type="text" name="warehouse_code" id="warehouse_code" 
                            class="form-control @error('warehouse_code') is-invalid @enderror" 
                            value="{{ old('warehouse_code') }}" 
                            placeholder="Select a floor to auto-fill" 
                            readonly required>
                        @error('warehouse_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted" id="code_hint">Auto-filled from selected floor</small>
                    </div>

                    <!-- Warehouse Info Box -->
                    <div class="col-md-12">
                        <div class="warehouse-info-box" id="warehouseInfoBox">
                            <h6><i class="fas fa-warehouse"></i> Warehouse Details</h6>
                            <div class="info-row">
                                <span class="label">Warehouse:</span>
                                <span class="value" id="infoWarehouseName">-</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Code:</span>
                                <span class="value" id="infoWarehouseCode">-</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Floor:</span>
                                <span class="value" id="infoFloorName">-</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Block:</span>
                                <span class="value" id="infoBlockName">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Warehouse Capacity (Units)</label>
                        <input type="number" name="capacity" class="form-control" value="{{ old('capacity') }}" min="0" placeholder="Total storage capacity">
                        <small class="text-muted">Total storage capacity in units</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person') }}" placeholder="Contact person name">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="Phone number">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Email address">
                    </div>
                </div>
            </div>

            <!-- Address -->
            <h5 class="mb-3"><i class="fas fa-address-book" style="color: #4361ee;"></i> Address</h5>
            <div class="location-section">
                <div class="section-title">
                    <div id="locationStatus" class="location-status info">
                        <i class="fas fa-info-circle"></i> Click "Get My Location" to automatically fill address
                    </div>
                    <button type="button" id="getLocationBtn" class="btn-location ms-auto">
                        <i class="fas fa-location-dot"></i> Get My Location
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" id="address" class="form-control" rows="2" placeholder="Street address">{{ old('address') }}</textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">City</label>
                        <input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}" placeholder="City">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">State</label>
                        <input type="text" name="state" id="state" class="form-control" value="{{ old('state') }}" placeholder="State">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Pincode</label>
                        <input type="text" name="pincode" id="pincode" class="form-control" value="{{ old('pincode') }}" placeholder="Pincode">
                    </div>

                    <div class="section-title">
                        <i class="fas fa-map-marker-alt"></i> Location Details
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Latitude</label>
                            <input type="text" name="latitude" id="latitude" class="form-control" 
                                value="{{ old('latitude') }}" placeholder="e.g., 28.7041" readonly>
                            <small class="text-muted">Auto-filled from your location</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Longitude</label>
                            <input type="text" name="longitude" id="longitude" class="form-control" 
                                value="{{ old('longitude') }}" placeholder="e.g., 77.1025" readonly>
                            <small class="text-muted">Auto-filled from your location</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <h5 class="mb-3"><i class="fas fa-cog" style="color: #4361ee;"></i> Settings</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_default" class="form-check-input" id="isDefault" value="1">
                        <label class="form-check-label" for="isDefault">
                            Set as Default Warehouse
                            <span id="defaultNoticeText" style="display: none; color: #d97706; font-size: 0.8rem; margin-left: 0.5rem;">
                            </span>
                        </label>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="status" class="form-check-input" id="status" 
                               value="1" {{ old('status', 1) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">
                            Active
                        </label>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Submit -->
            <div class="mt-3 d-flex justify-content-between">
                <a href="{{ route('inventory.warehouses.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-arrow-right"></i> Save & Add Store
                </button>
            </div>
            <small class="text-muted d-block mt-2">
                <i class="fas fa-info-circle"></i> After saving, you'll be redirected to add stores to this warehouse.
            </small>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const blockSelect = document.getElementById('block_id');
        const floorSelect = document.getElementById('floor_id');
        const floorHint = document.getElementById('floor_hint');
        const warehouseName = document.getElementById('warehouse_name');
        const warehouseCode = document.getElementById('warehouse_code');
        const codeHint = document.getElementById('code_hint');
        const warehouseNameHint = document.getElementById('warehouse_name_hint');
        const autoFillBadge = document.getElementById('autoFillBadge');
        const autoFillBadgeCode = document.getElementById('autoFillBadgeCode');
        const warehouseInfoBox = document.getElementById('warehouseInfoBox');
        const infoWarehouseName = document.getElementById('infoWarehouseName');
        const infoWarehouseCode = document.getElementById('infoWarehouseCode');
        const infoFloorName = document.getElementById('infoFloorName');
        const infoBlockName = document.getElementById('infoBlockName');
        const isDefaultCheckbox = document.getElementById('isDefault');
        const defaultNoticeText = document.getElementById('defaultNoticeText');
        const defaultWarehouseNotice = document.getElementById('defaultWarehouseNotice');
        
        // Store all floors data from PHP
        const allFloors = @json($floors);
        
        // Use existingWarehouses for counting, floorWarehouses for auto-fill
        const existingWarehouses = @json($existingWarehouses ?? []);
        const floorWarehouses = @json($floorWarehouses ?? []);
        
        // Get total warehouses count from existingWarehouses
        const totalWarehouses = existingWarehouses.length;
        
        console.log('Total Warehouses in current context:', totalWarehouses);
        console.log('Existing Warehouses:', existingWarehouses);
        console.log('Floor Warehouses (for auto-fill):', floorWarehouses);
        console.log('Old is_default value:', '{{ old('is_default') }}');
        
        // ============================================
        // AUTO-SET DEFAULT FOR FIRST WAREHOUSE
        // ============================================
        
        // Check if this is the first warehouse (no warehouses exist in this context)
        // AND no old value is set (not editing)
        const hasOldValue = '{{ old('is_default') }}' !== '';
        
        if (totalWarehouses === 0 && !hasOldValue) {
            // First warehouse - auto-check default and disable
            console.log('First warehouse - auto-setting as default');
            isDefaultCheckbox.checked = true;
            isDefaultCheckbox.disabled = true;
            defaultNoticeText.style.display = 'inline';
            defaultWarehouseNotice.style.display = 'block';
            
            // Add visual indicator to the checkbox label
            const label = document.querySelector('label[for="isDefault"]');
            if (label) {
                // Remove existing badge if any
                const existingBadge = label.querySelector('.default-badge');
                if (existingBadge) {
                    existingBadge.remove();
                }
                const badge = document.createElement('span');
                badge.className = 'default-badge';
                badge.innerHTML = '<i class="fas fa-star"></i> First Warehouse - Auto Default';
                label.appendChild(badge);
            }
        } else {
            // Not the first warehouse, or has old value
            isDefaultCheckbox.disabled = false;
            defaultNoticeText.style.display = 'none';
            defaultWarehouseNotice.style.display = 'none';
            
            // If old value exists, use it
            if (hasOldValue) {
                const oldValue = '{{ old('is_default') }}';
                isDefaultCheckbox.checked = oldValue === '1';
                console.log('Using old value for is_default:', oldValue);
            }
        }
        
        // Store block names for display
        const blockNames = {};
        @foreach($blocks as $block)
            blockNames[{{ $block->id }}] = '{{ $block->name }}';
        @endforeach
        
        // Store floor names for display
        const floorNames = {};
        @foreach($floors as $floor)
            floorNames[{{ $floor->id }}] = '{{ $floor->floor_name }}';
        @endforeach
        
        // Function to filter floors based on selected block
        function filterFloors(blockId, selectedFloorId = null) {
            // Clear current options
            floorSelect.innerHTML = '<option value="">Select Floor</option>';
            
            if (!blockId) {
                floorSelect.disabled = true;
                floorHint.textContent = 'Please select a block first';
                resetWarehouseFields();
                return;
            }
            
            // Filter floors by block_id
            const filteredFloors = allFloors.filter(floor => floor.block_id == blockId);
            
            if (filteredFloors.length === 0) {
                floorSelect.innerHTML = '<option value="">No floors available for this block</option>';
                floorSelect.disabled = true;
                floorHint.textContent = 'No floors found for selected block';
                resetWarehouseFields();
                return;
            }
            
            // Add filtered floors to select
            filteredFloors.forEach(floor => {
                const option = document.createElement('option');
                option.value = floor.id;
                option.textContent = floor.floor_name;
                option.dataset.blockId = floor.block_id;
                if (selectedFloorId && floor.id == selectedFloorId) {
                    option.selected = true;
                }
                floorSelect.appendChild(option);
            });
            
            floorSelect.disabled = false;
            floorHint.textContent = 'Select a floor';
            
            // 🔥 FIX: AUTO-SELECT if only ONE floor exists for this block
            if (filteredFloors.length === 1 && !selectedFloorId) {
                const firstFloor = filteredFloors[0];
                floorSelect.value = firstFloor.id;
                floorHint.textContent = 'Floor auto-selected (only one available)';
                autoFillWarehouse(firstFloor.id);
                // Trigger change event for any other listeners
                floorSelect.dispatchEvent(new Event('change'));
            } else if (selectedFloorId) {
                autoFillWarehouse(selectedFloorId);
            }
        }
        
        // Function to reset warehouse fields
        function resetWarehouseFields() {
            warehouseName.value = '';
            warehouseName.placeholder = 'Select a floor to auto-fill';
            warehouseName.readOnly = true;
            warehouseName.style.backgroundColor = '#f8f9fa';
            warehouseName.classList.remove('auto-filled', 'no-warehouse');
            
            warehouseCode.value = '';
            warehouseCode.placeholder = 'Select a floor to auto-fill';
            warehouseCode.readOnly = true;
            warehouseCode.style.backgroundColor = '#f8f9fa';
            warehouseCode.classList.remove('auto-filled', 'no-warehouse');
            
            autoFillBadge.style.display = 'none';
            autoFillBadgeCode.style.display = 'none';
            warehouseInfoBox.classList.remove('show');
            codeHint.textContent = 'Auto-filled from selected floor';
            warehouseNameHint.textContent = 'Auto-filled from selected floor';
        }
        
        // Function to auto-fill warehouse based on floor
        function autoFillWarehouse(floorId) {
            if (!floorId) {
                resetWarehouseFields();
                return;
            }
            
            // Use floorWarehouses for auto-fill (these come from floor data)
            const floorWarehouse = floorWarehouses.find(w => {
                return parseInt(w.floor_id) === parseInt(floorId);
            });
            
            if (floorWarehouse) {
                warehouseName.value = floorWarehouse.warehouse_name;
                warehouseName.placeholder = floorWarehouse.warehouse_name;
                warehouseName.readOnly = true;
                warehouseName.style.backgroundColor = '#f1f5f9';
                warehouseName.classList.add('auto-filled');
                warehouseName.classList.remove('no-warehouse');
                
                warehouseCode.value = floorWarehouse.warehouse_code;
                warehouseCode.placeholder = floorWarehouse.warehouse_code;
                warehouseCode.readOnly = true;
                warehouseCode.style.backgroundColor = '#f1f5f9';
                warehouseCode.classList.add('auto-filled');
                warehouseCode.classList.remove('no-warehouse');
                
                autoFillBadge.style.display = 'inline-block';
                autoFillBadgeCode.style.display = 'inline-block';
                
                codeHint.textContent = 'Auto-filled from existing warehouse';
                warehouseNameHint.textContent = 'Auto-filled from existing warehouse';
                
                warehouseInfoBox.classList.add('show');
                infoWarehouseName.textContent = floorWarehouse.warehouse_name;
                infoWarehouseCode.textContent = floorWarehouse.warehouse_code;
                
                const selectedFloor = allFloors.find(f => parseInt(f.id) === parseInt(floorId));
                const floorName = selectedFloor ? selectedFloor.floor_name : 'N/A';
                const blockId = selectedFloor ? selectedFloor.block_id : null;
                const blockName = blockId && blockNames[blockId] ? blockNames[blockId] : 'N/A';
                
                infoFloorName.textContent = floorName;
                infoBlockName.textContent = blockName;
                
            } else {
                warehouseName.value = '';
                warehouseName.placeholder = 'No warehouse exists for this floor';
                warehouseName.readOnly = true;
                warehouseName.style.backgroundColor = '#fef3c7';
                warehouseName.classList.add('no-warehouse');
                warehouseName.classList.remove('auto-filled');
                
                warehouseCode.value = '';
                warehouseCode.placeholder = 'No warehouse exists for this floor';
                warehouseCode.readOnly = true;
                warehouseCode.style.backgroundColor = '#fef3c7';
                warehouseCode.classList.add('no-warehouse');
                warehouseCode.classList.remove('auto-filled');
                
                autoFillBadge.style.display = 'none';
                autoFillBadgeCode.style.display = 'none';
                
                codeHint.textContent = 'No warehouse found for this floor';
                warehouseNameHint.textContent = 'No warehouse found for this floor';
                
                warehouseInfoBox.classList.remove('show');
            }
        }
        
        // Event listener for floor selection change
        floorSelect.addEventListener('change', function() {
            const floorId = this.value;
            if (floorId) {
                autoFillWarehouse(floorId);
            } else {
                resetWarehouseFields();
                warehouseName.readOnly = true;
                warehouseCode.readOnly = true;
                warehouseName.style.backgroundColor = '#f8f9fa';
                warehouseCode.style.backgroundColor = '#f8f9fa';
                warehouseName.placeholder = 'Select a floor to auto-fill';
                warehouseCode.placeholder = 'Select a floor to auto-fill';
                codeHint.textContent = 'Auto-filled from selected floor';
                warehouseNameHint.textContent = 'Auto-filled from selected floor';
            }
        });
        
        // Event listener for block selection change
        blockSelect.addEventListener('change', function() {
            const blockId = this.value;
            
            // Reset fields before filtering
            resetWarehouseFields();
            warehouseName.readOnly = true;
            warehouseCode.readOnly = true;
            warehouseName.style.backgroundColor = '#f8f9fa';
            warehouseCode.style.backgroundColor = '#f8f9fa';
            warehouseName.placeholder = 'Select a floor to auto-fill';
            warehouseCode.placeholder = 'Select a floor to auto-fill';
            
            // 🔥 FIX: Filter floors - this will auto-select if only one exists
            filterFloors(blockId, null);
        });
        
        // Initialize with old values if any
        const oldBlockId = '{{ old('block_id') }}';
        const oldFloorId = '{{ old('floor_id') }}';
        
        if (oldBlockId) {
            filterFloors(oldBlockId, oldFloorId);
        } else {
            floorSelect.disabled = true;
            warehouseName.readOnly = true;
            warehouseCode.readOnly = true;
            warehouseName.style.backgroundColor = '#f8f9fa';
            warehouseCode.style.backgroundColor = '#f8f9fa';
        }
        
        // ============================================
        // LOCATION DETECTION
        // ============================================
        
        const getLocationBtn = document.getElementById('getLocationBtn');
        const locationStatus = document.getElementById('locationStatus');
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        
        if (getLocationBtn) {
            getLocationBtn.addEventListener('click', function() {
                if (!navigator.geolocation) {
                    locationStatus.className = 'location-status error';
                    locationStatus.innerHTML = '<i class="fas fa-exclamation-circle"></i> Geolocation is not supported by your browser';
                    return;
                }
                
                getLocationBtn.disabled = true;
                getLocationBtn.innerHTML = '<span class="loading-spinner"></span> Getting location...';
                locationStatus.className = 'location-status loading';
                locationStatus.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Fetching your location...';
                
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        latitudeInput.value = lat.toFixed(6);
                        longitudeInput.value = lng.toFixed(6);
                        
                        locationStatus.className = 'location-status success';
                        locationStatus.innerHTML = '<i class="fas fa-check-circle"></i> Location detected successfully! Coordinates have been filled.';
                        
                        getLocationBtn.disabled = false;
                        getLocationBtn.innerHTML = '<i class="fas fa-location-dot"></i> Update Location';
                        
                        getAddressFromCoords(lat, lng);
                    },
                    function(error) {
                        let errorMessage = '';
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = 'Location permission denied. Please enable location access in your browser settings.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = 'Location information is unavailable. Please check your GPS/network.';
                                break;
                            case error.TIMEOUT:
                                errorMessage = 'Location request timed out. Please try again.';
                                break;
                            default:
                                errorMessage = 'An unknown error occurred. Please try again.';
                        }
                        
                        locationStatus.className = 'location-status error';
                        locationStatus.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + errorMessage;
                        
                        getLocationBtn.disabled = false;
                        getLocationBtn.innerHTML = '<i class="fas fa-location-dot"></i> Get My Location';
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            });
        }
        
        function getAddressFromCoords(lat, lng) {
            const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data && data.address) {
                        const address = data.address;
                        const addressTextarea = document.querySelector('textarea[name="address"]');
                        if (addressTextarea) {
                            const addressParts = [];
                            if (address.road) addressParts.push(address.road);
                            if (address.suburb) addressParts.push(address.suburb);
                            if (address.city || address.town || address.village) {
                                addressParts.push(address.city || address.town || address.village);
                            }
                            if (addressParts.length > 0) {
                                addressTextarea.value = addressParts.join(', ');
                            }
                        }
                        
                        const cityInput = document.querySelector('input[name="city"]');
                        if (cityInput && (address.city || address.town || address.village)) {
                            cityInput.value = address.city || address.town || address.village;
                        }
                        
                        const stateInput = document.querySelector('input[name="state"]');
                        if (stateInput && address.state) {
                            stateInput.value = address.state;
                        }
                        
                        const pincodeInput = document.querySelector('input[name="pincode"]');
                        if (pincodeInput && address.postcode) {
                            pincodeInput.value = address.postcode;
                        }
                    }
                })
                .catch(error => {
                    console.log('Reverse geocoding failed:', error);
                });
        }
        
        // ============================================
        // FORM VALIDATION
        // ============================================
        
        const form = document.getElementById('warehouseForm');
        const submitBtn = document.getElementById('submitBtn');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!blockSelect.value) {
                    e.preventDefault();
                    blockSelect.classList.add('is-invalid');
                    alert('Please select a block');
                    return;
                }
                
                if (!floorSelect.value) {
                    e.preventDefault();
                    floorSelect.classList.add('is-invalid');
                    alert('Please select a floor');
                    return;
                }
                
                if (!warehouseName.value) {
                    e.preventDefault();
                    warehouseName.classList.add('is-invalid');
                    alert('Warehouse name is required');
                    return;
                }
                
                if (!warehouseCode.value) {
                    e.preventDefault();
                    warehouseCode.classList.add('is-invalid');
                    alert('Warehouse code is required');
                    return;
                }
                
                // If this is the first warehouse and default checkbox is disabled,
                // make sure it's checked before submitting
                if (isDefaultCheckbox.disabled && !isDefaultCheckbox.checked) {
                    isDefaultCheckbox.checked = true;
                }
            });
        }
        
        blockSelect.addEventListener('change', function() {
            this.classList.remove('is-invalid');
        });
        
        floorSelect.addEventListener('change', function() {
            this.classList.remove('is-invalid');
        });
        
        warehouseName.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
        
        warehouseCode.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
</script>

@endsection