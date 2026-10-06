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

    .text-warning {
        color: #f59e0b;
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

    .location-section .section-title i {
        color: #4361ee;
    }

    .current-location {
        background: #e0e7ff;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        color: #4338ca;
        font-weight: 500;
    }

    .btn-primary {
        background: var(--primary-gradient);
        border: none;
        color: white;
        border-radius: 12px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.35);
        color: white;
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

    .form-check-label {
        font-weight: 500;
        color: var(--text-dark);
        margin-left: 0.5rem;
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
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-edit"></i> Edit Warehouse</h1>
            <p>Update warehouse location details</p>
        </div>
        <a href="{{ route('inventory.warehouses.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Warehouses
        </a>
    </div>

    <!-- Form -->
    <div class="form-card">
        <form method="POST" action="{{ route('inventory.warehouses.update', $warehouse->id) }}" id="warehouseForm">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <h5 class="mb-3"><i class="fas fa-info-circle" style="color: #4361ee;"></i> Basic Information</h5>
            <div class="location-section">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Block <span class="text-danger">*</span></label>
                        <select name="block_id" id="block_id" class="form-select @error('block_id') is-invalid @enderror" required>
                            <option value="">Select Block</option>
                            @foreach($blocks as $block)
                                <option value="{{ $block->id }}" {{ old('block_id', $warehouse->block_id) == $block->id ? 'selected' : '' }}>
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
                            @foreach($floors as $floor)
                                <option value="{{ $floor->id }}" 
                                    {{ old('floor_id', $warehouse->floor_id) == $floor->id ? 'selected' : '' }}
                                    data-block-id="{{ $floor->block_id }}"
                                    data-warehouse-name="{{ $floor->warehouse_name ?? '' }}"
                                    data-warehouse-code="{{ $floor->warehouse_id ?? '' }}">
                                    {{ $floor->floor_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('floor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted" id="floor_hint">Select a floor</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Warehouse Name <span class="text-danger">*</span>
                            <span class="auto-filled-badge" id="autoFillBadge" style="display: {{ $warehouse->warehouse_name ? 'inline-block' : 'none' }};">
                                <i class="fas fa-magic"></i> Auto-filled
                            </span>
                        </label>
                        <input type="text" name="warehouse_name" id="warehouse_name" 
                            class="form-control @error('warehouse_name') is-invalid @enderror" 
                            value="{{ old('warehouse_name', $warehouse->warehouse_name) }}" 
                            placeholder="Warehouse name" 
                            readonly required>
                        @error('warehouse_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted" id="warehouse_name_hint">Auto-filled from floor data</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Warehouse Code <span class="text-danger">*</span>
                            <span class="auto-filled-badge" id="autoFillBadgeCode" style="display: {{ $warehouse->warehouse_code ? 'inline-block' : 'none' }};">
                                <i class="fas fa-magic"></i> Auto-filled
                            </span>
                        </label>
                        <input type="text" name="warehouse_code" id="warehouse_code" 
                            class="form-control @error('warehouse_code') is-invalid @enderror" 
                            value="{{ old('warehouse_code', $warehouse->warehouse_code) }}" 
                            placeholder="Warehouse code" 
                            readonly required>
                        @error('warehouse_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted" id="code_hint">Auto-filled from floor data</small>
                    </div>

                    <!-- Warehouse Info Box -->
                    <div class="col-md-12">
                        <div class="warehouse-info-box show" id="warehouseInfoBox">
                            <h6><i class="fas fa-warehouse"></i> Warehouse Details</h6>
                            <div class="info-row">
                                <span class="label">Warehouse:</span>
                                <span class="value" id="infoWarehouseName">{{ $warehouse->warehouse_name ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Code:</span>
                                <span class="value" id="infoWarehouseCode">{{ $warehouse->warehouse_code ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Floor:</span>
                                <span class="value" id="infoFloorName">
                                    @php
                                        $floorName = $floors->where('id', $warehouse->floor_id)->first();
                                    @endphp
                                    {{ $floorName ? $floorName->floor_name : 'N/A' }}
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="label">Block:</span>
                                <span class="value" id="infoBlockName">
                                    @php
                                        $blockName = $blocks->where('id', $warehouse->block_id)->first();
                                    @endphp
                                    {{ $blockName ? $blockName->name : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control" 
                               value="{{ old('contact_person', $warehouse->contact_person) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" 
                               value="{{ old('phone', $warehouse->phone) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" 
                               value="{{ old('email', $warehouse->email) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Capacity (Units)</label>
                        <input type="number" name="capacity" class="form-control" 
                               value="{{ old('capacity', $warehouse->capacity) }}" min="0">
                        <small class="text-muted">
                            Current utilization: {{ number_format($warehouse->current_utilization ?? 0) }} units
                            @if($warehouse->capacity)
                                ({{ round(($warehouse->current_utilization / $warehouse->capacity) * 100, 1) }}% used)
                            @endif
                        </small>
                    </div>
                </div>
            </div>

            <!-- Address -->
            <h5 class="mb-3"><i class="fas fa-address-book" style="color: #4361ee;"></i> Address</h5>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $warehouse->address) }}</textarea>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" 
                           value="{{ old('city', $warehouse->city) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">State</label>
                    <input type="text" name="state" class="form-control" 
                           value="{{ old('state', $warehouse->state) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Pincode</label>
                    <input type="text" name="pincode" class="form-control" 
                           value="{{ old('pincode', $warehouse->pincode) }}">
                </div>

                <div class="location-section">
                    <div class="section-title">
                        <i class="fas fa-map-marker-alt"></i> Location Details
                        <button type="button" id="getLocationBtn" class="btn btn-primary ms-auto" style="padding: 0.4rem 1rem; font-size: 0.85rem;">
                            <i class="fas fa-location-dot"></i> Get My Location
                        </button>
                    </div>
                    
                    <div id="locationStatus" class="location-status info" style="padding: 10px 15px; border-radius: 8px; margin-top: 10px; font-size: 0.9rem; background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd;">
                        <i class="fas fa-info-circle"></i> Click "Get My Location" to update coordinates
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Latitude</label>
                            <input type="text" name="latitude" id="latitude" class="form-control" 
                                   value="{{ old('latitude', $warehouse->latitude) }}" placeholder="e.g., 28.7041">
                            <small class="text-muted">Auto-filled from your location</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Longitude</label>
                            <input type="text" name="longitude" id="longitude" class="form-control" 
                                   value="{{ old('longitude', $warehouse->longitude) }}" placeholder="e.g., 77.1025">
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
                        <input type="checkbox" name="is_default" class="form-check-input" id="isDefault" 
                               value="1" {{ old('is_default', $warehouse->is_default) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isDefault">
                            Set as Default Warehouse
                        </label>
                    </div>
                    @if($warehouse->is_default)
                        <small class="text-warning"><i class="fas fa-info-circle"></i> This is currently the default warehouse</small>
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="status" class="form-check-input" id="status" 
                               value="1" {{ old('status', $warehouse->status) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">
                            Active
                        </label>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Submit -->
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Warehouse
                </button>
                <a href="{{ route('inventory.warehouses.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
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
        
        // Store all floors data from PHP
        const allFloors = @json($floors);
        const currentFloorId = '{{ old('floor_id', $warehouse->floor_id) }}';
        
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
                if (floor.warehouse_name) {
                    option.dataset.warehouseName = floor.warehouse_name;
                    option.dataset.warehouseCode = floor.warehouse_id;
                }
                if (selectedFloorId && floor.id == selectedFloorId) {
                    option.selected = true;
                }
                floorSelect.appendChild(option);
            });
            
            floorSelect.disabled = false;
            floorHint.textContent = 'Select a floor';
            
            // If a floor was selected, auto-fill warehouse
            if (selectedFloorId) {
                autoFillWarehouse(selectedFloorId);
            }
        }
        
        // Function to reset warehouse fields
        function resetWarehouseFields() {
            warehouseName.value = '';
            warehouseName.placeholder = 'Select a floor to auto-fill';
            warehouseName.readOnly = true;
            warehouseName.style.backgroundColor = '#f8f9fa';
            
            warehouseCode.value = '';
            warehouseCode.placeholder = 'Select a floor to auto-fill';
            warehouseCode.readOnly = true;
            warehouseCode.style.backgroundColor = '#f8f9fa';
            
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
            
            // Find the selected floor
            const selectedFloor = allFloors.find(f => parseInt(f.id) === parseInt(floorId));
            
            if (selectedFloor) {
                const hasWarehouse = selectedFloor.warehouse_name && selectedFloor.warehouse_id;
                
                if (hasWarehouse) {
                    warehouseName.value = selectedFloor.warehouse_name;
                    warehouseName.placeholder = selectedFloor.warehouse_name;
                    warehouseName.readOnly = true;
                    warehouseName.style.backgroundColor = '#f1f5f9';
                    
                    warehouseCode.value = selectedFloor.warehouse_id;
                    warehouseCode.placeholder = selectedFloor.warehouse_id;
                    warehouseCode.readOnly = true;
                    warehouseCode.style.backgroundColor = '#f1f5f9';
                    
                    autoFillBadge.style.display = 'inline-block';
                    autoFillBadgeCode.style.display = 'inline-block';
                    
                    codeHint.textContent = 'Auto-filled from floor data';
                    warehouseNameHint.textContent = 'Auto-filled from floor data';
                    
                    warehouseInfoBox.classList.add('show');
                    infoWarehouseName.textContent = selectedFloor.warehouse_name;
                    infoWarehouseCode.textContent = selectedFloor.warehouse_id;
                    
                    const floorName = selectedFloor.floor_name || 'N/A';
                    const blockId = selectedFloor.block_id;
                    const blockName = blockId && blockNames[blockId] ? blockNames[blockId] : 'N/A';
                    
                    infoFloorName.textContent = floorName;
                    infoBlockName.textContent = blockName;
                } else {
                    warehouseName.value = '';
                    warehouseName.placeholder = 'No warehouse exists for this floor';
                    warehouseName.readOnly = true;
                    warehouseName.style.backgroundColor = '#fef3c7';
                    
                    warehouseCode.value = '';
                    warehouseCode.placeholder = 'No warehouse exists for this floor';
                    warehouseCode.readOnly = true;
                    warehouseCode.style.backgroundColor = '#fef3c7';
                    
                    autoFillBadge.style.display = 'none';
                    autoFillBadgeCode.style.display = 'none';
                    
                    codeHint.textContent = 'No warehouse found for this floor';
                    warehouseNameHint.textContent = 'No warehouse found for this floor';
                    
                    warehouseInfoBox.classList.remove('show');
                }
            } else {
                resetWarehouseFields();
            }
        }
        
        // Event listener for floor selection change
        floorSelect.addEventListener('change', function() {
            const floorId = this.value;
            if (floorId) {
                autoFillWarehouse(floorId);
            } else {
                resetWarehouseFields();
            }
        });
        
        // Event listener for block selection change
        blockSelect.addEventListener('change', function() {
            const blockId = this.value;
            const selectedFloorId = floorSelect.value;
            filterFloors(blockId, null);
        });
        
        // Initialize with current values
        const currentBlockId = '{{ old('block_id', $warehouse->block_id) }}';
        if (currentBlockId) {
            filterFloors(currentBlockId, currentFloorId);
        }
        
        // Location Detection
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
                getLocationBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Getting location...';
                locationStatus.className = 'location-status loading';
                locationStatus.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Fetching your location...';
                
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        latitudeInput.value = lat.toFixed(6);
                        longitudeInput.value = lng.toFixed(6);
                        
                        locationStatus.className = 'location-status success';
                        locationStatus.innerHTML = '<i class="fas fa-check-circle"></i> Location detected successfully!';
                        
                        getLocationBtn.disabled = false;
                        getLocationBtn.innerHTML = '<i class="fas fa-location-dot"></i> Update Location';
                        
                        getAddressFromCoords(lat, lng);
                    },
                    function(error) {
                        let errorMessage = '';
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = 'Location permission denied.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = 'Location unavailable.';
                                break;
                            case error.TIMEOUT:
                                errorMessage = 'Request timed out.';
                                break;
                            default:
                                errorMessage = 'Unknown error occurred.';
                        }
                        
                        locationStatus.className = 'location-status error';
                        locationStatus.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + errorMessage;
                        
                        getLocationBtn.disabled = false;
                        getLocationBtn.innerHTML = '<i class="fas fa-location-dot"></i> Get My Location';
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
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
                            const parts = [];
                            if (address.road) parts.push(address.road);
                            if (address.suburb) parts.push(address.suburb);
                            if (address.city || address.town || address.village) {
                                parts.push(address.city || address.town || address.village);
                            }
                            if (parts.length > 0) {
                                addressTextarea.value = parts.join(', ');
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
                .catch(error => console.log('Reverse geocoding failed:', error));
        }
        
        // Form validation
        const form = document.getElementById('warehouseForm');
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
        });
    });
</script>

@endsection