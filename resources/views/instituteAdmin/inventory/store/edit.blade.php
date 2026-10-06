{{-- resources/views/instituteAdmin/Inventory/store/edit.blade.php --}}

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

    .btn-success {
        background: #10b981;
        border: none;
        color: white;
        border-radius: 12px;
        padding: 0.6rem 2rem;
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-success:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.35);
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
        background: #4361ee;
        border: none;
        color: white;
        border-radius: 12px;
        padding: 0.4rem 1.2rem;
        font-weight: 600;
        font-size: 0.85rem;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-location:hover {
        background: #3a0ca3;
        transform: translateY(-2px);
        color: white;
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

    .store-info-box {
        background: #eff6ff;
        border: 2px solid #93c5fd;
        border-radius: 12px;
        padding: 15px;
        margin-top: 10px;
    }

    .store-info-box .info-row {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        border-bottom: 1px solid #dbeafe;
    }

    .store-info-box .info-row:last-child {
        border-bottom: none;
    }

    .store-info-box .label {
        font-weight: 600;
        color: var(--text-dark);
    }

    .store-info-box .value {
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
            <h1><i class="fas fa-edit"></i>Edit Store</h1>
            <p>Update store details</p>
        </div>
        <a href="{{ route('inventory.stores.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Stores
        </a>
    </div>

    <!-- Form -->
    <div class="form-card">
        <form method="POST" action="{{ route('inventory.stores.update', $store->id) }}" id="storeForm">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Store Name <span class="text-danger">*</span></label>
                    <input type="text" name="store_name" class="form-control @error('store_name') is-invalid @enderror" 
                        value="{{ old('store_name', $store->store_name) }}" required>
                    @error('store_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Store Code <span class="text-danger">*</span></label>
                    <input type="text" name="store_code" class="form-control @error('store_code') is-invalid @enderror" 
                        value="{{ old('store_code', $store->store_code) }}" required>
                    @error('store_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Warehouse <span class="text-danger">*</span></label>
                    <select name="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror" required>
                        <option value="">Select Warehouse</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" 
                                {{ old('warehouse_id', $store->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->warehouse_name }} ({{ $warehouse->warehouse_code }})
                            </option>
                        @endforeach
                    </select>
                    @error('warehouse_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Floor</label>
                    <select name="floor_id" class="form-select @error('floor_id') is-invalid @enderror">
                        <option value="">Select Floor</option>
                        @foreach($floors as $floor)
                            <option value="{{ $floor->id }}" 
                                {{ old('floor_id', $store->floor_id) == $floor->id ? 'selected' : '' }}>
                                {{ $floor->floor_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('floor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Room</label>
                    <select name="room_id" class="form-select @error('room_id') is-invalid @enderror">
                        <option value="">Select Room</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" 
                                {{ old('room_id', $store->room_id) == $room->id ? 'selected' : '' }}>
                                {{ $room->room_name }} ({{ $room->room_code ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Capacity (Units)</label>
                    <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror" 
                        value="{{ old('capacity', $store->capacity) }}" min="0">
                    @error('capacity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control" 
                        value="{{ old('contact_person', $store->contact_person) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" 
                        value="{{ old('phone', $store->phone) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" 
                        value="{{ old('email', $store->email) }}">
                </div>
            </div>

            <!-- Address -->
            <h5 class="mb-3"><i class="fas fa-address-book" style="color: #4361ee;"></i> Address</h5>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="Street address">{{ old('address', $store->address) }}</textarea>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city', $store->city) }}" placeholder="City">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">State</label>
                    <input type="text" name="state" class="form-control" value="{{ old('state', $store->state) }}" placeholder="State">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Pincode</label>
                    <input type="text" name="pincode" class="form-control" value="{{ old('pincode', $store->pincode) }}" placeholder="Pincode">
                </div>

                <div class="col-md-12">
                    <div class="location-section">
                        <div class="section-title">
                            <i class="fas fa-map-marker-alt"></i> Location Details
                            <button type="button" id="getLocationBtn" class="btn-location ms-auto">
                                <i class="fas fa-location-dot"></i> Get My Location
                            </button>
                        </div>
                        
                        <div id="locationStatus" class="location-status info">
                            <i class="fas fa-info-circle"></i> Click "Get My Location" to automatically fill coordinates
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="text" name="latitude" id="latitude" class="form-control" 
                                    value="{{ old('latitude', $store->latitude) }}" placeholder="e.g., 28.7041" readonly>
                                <small class="text-muted">Auto-filled from your location</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="text" name="longitude" id="longitude" class="form-control" 
                                    value="{{ old('longitude', $store->longitude) }}" placeholder="e.g., 77.1025" readonly>
                                <small class="text-muted">Auto-filled from your location</small>
                            </div>
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
                               value="1" {{ old('is_default', $store->is_default) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isDefault">
                            Set as Default Store
                        </label>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="status" class="form-check-input" id="status" 
                               value="1" {{ old('status', $store->status) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">
                            Active
                        </label>
                    </div>
                </div>
            </div>

            <!-- Store Info -->
            <div class="store-info-box">
                <h6><i class="fas fa-info-circle"></i> Store Information</h6>
                <div class="info-row">
                    <span class="label">Current Utilization:</span>
                    <span class="value">{{ $store->getUtilizationPercentageAttribute() }}%</span>
                </div>
                <div class="info-row">
                    <span class="label">Total Items:</span>
                    <span class="value">{{ $store->items()->count() }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Created:</span>
                    <span class="value">{{ $store->created_at ? $store->created_at->format('d M Y, h:i A') : 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Last Updated:</span>
                    <span class="value">{{ $store->updated_at ? $store->updated_at->format('d M Y, h:i A') : 'N/A' }}</span>
                </div>
            </div>

            <hr>

            <!-- Submit -->
            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Update Store
                </button>
                <a href="{{ route('inventory.stores.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                                errorMessage = 'An unknown error occurred.';
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
    });
</script>

@endsection