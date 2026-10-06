@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    /* add-store.blade.php */
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
        box-shadow: 0 15px 35px rgba(16, 185, 129, 0.3);
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
        background: var(--step-active);
        margin: 0 10px;
        transition: var(--transition);
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

    .step-item.completed .step-number {
        background: #10b981;
        color: white;
    }

    .step-item.active .step-number {
        background: var(--step-active);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .step-label {
        font-weight: 600;
        color: var(--text-muted);
        font-size: 0.85rem;
        white-space: nowrap;
    }

    .step-item.completed .step-label {
        color: #10b981;
    }

    .step-item.active .step-label {
        color: var(--text-dark);
    }

    .warehouse-info-card {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border: 2px solid #10b981;
        border-radius: 16px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .warehouse-info-card .label {
        font-size: 0.85rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .warehouse-info-card .value {
        font-weight: 700;
        color: var(--text-dark);
        font-size: 1.1rem;
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

    .form-label .apply-all-btn {
        font-size: 0.7rem;
        font-weight: 400;
        color: #10b981;
        cursor: pointer;
        background: none;
        border: none;
        padding: 0;
        text-decoration: underline;
        display: none;
    }

    .form-label .apply-all-btn.visible {
        display: inline-block;
    }

    .form-label .apply-all-btn:hover {
        color: #059669;
    }

    .form-control, .form-select {
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .form-control:focus, .form-select:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }

    .form-control:disabled, .form-select:disabled {
        background-color: #f1f5f9;
        cursor: not-allowed;
    }

    .form-control[readonly] {
        background-color: #f1f5f9;
        cursor: default;
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
        background: #10b981;
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
        background-color: #10b981;
        border-color: #10b981;
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

    .store-info-box {
        background: #eff6ff;
        border: 2px solid #93c5fd;
        border-radius: 12px;
        padding: 15px;
        margin-top: 10px;
        display: none;
    }

    .store-info-box.show {
        display: block;
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

    .room-checkbox-group {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .room-checkbox-group .form-check {
        padding: 0.5rem 1rem;
        background: white;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        transition: var(--transition);
        margin-bottom: 0;
    }

    .room-checkbox-group .form-check:hover {
        border-color: #10b981;
        background: #f0fdf4;
    }

    .room-checkbox-group .form-check-input:checked + .form-check-label {
        color: #10b981;
        font-weight: 600;
    }

    .room-checkbox-group .form-check-input:checked {
        background-color: #10b981;
        border-color: #10b981;
    }

    .room-checkbox-group .form-check.auto-selected {
        background: #f0fdf4;
        border-color: #10b981;
        border-width: 2px;
    }

    .room-checkbox-group .form-check.auto-selected .form-check-input:disabled {
        opacity: 0.8;
    }

    .selected-count {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: 0.5rem;
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

    .apply-all-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #10b981;
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        transform: translateX(120%);
        transition: transform 0.3s ease;
        font-weight: 500;
    }

    .apply-all-toast.show {
        transform: translateX(0);
    }

    .room-detail-input {
        margin-top: 0.3rem;
    }

    .room-detail-input .form-control-sm {
        font-size: 0.8rem;
        padding: 0.2rem 0.5rem;
        border-radius: 6px;
    }

    .store-summary-info {
        background: #f0fdf4;
        border: 1px solid #10b981;
        border-radius: 8px;
        padding: 10px 15px;
        margin-top: 10px;
    }

    .store-summary-info .store-item {
        padding: 3px 0;
        border-bottom: 1px dashed #d1fae5;
    }

    .store-summary-info .store-item:last-child {
        border-bottom: none;
    }

    .store-summary-info .store-name {
        font-weight: 600;
        color: var(--text-dark);
    }

    .store-summary-info .store-code {
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    .common-details-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .common-details-header .apply-all-btn-main {
        font-size: 0.8rem;
        padding: 0.3rem 1rem;
        border-radius: 20px;
        display: none;
    }

    .common-details-header .apply-all-btn-main.visible {
        display: inline-block;
    }

    .default-store-section {
        background: #fffbeb;
        border: 2px solid #fcd34d;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        display: none;
    }

    .default-store-section.show {
        display: block;
    }

    .default-store-section .section-title {
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .default-store-section .section-title i {
        color: #d97706;
    }

    .default-store-option {
        padding: 0.5rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        transition: var(--transition);
        cursor: pointer;
    }

    .default-store-option:hover {
        border-color: #fcd34d;
        background: #fffbeb;
    }

    .default-store-option.selected {
        border-color: #fcd34d;
        background: #fffbeb;
    }

    .default-store-option .form-check-input:checked + .form-check-label {
        color: #d97706;
        font-weight: 600;
    }

    .default-store-badge {
        background: #fef3c7;
        color: #d97706;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
        border: 1px solid #fcd34d;
        margin-left: 8px;
    }

    .default-store-badge i {
        color: #d97706;
    }

    .store-item-default {
        background: #fffbeb !important;
        border-color: #fcd34d !important;
    }

    .no-rooms-selected-message {
        background: #fef3c7;
        border: 2px solid #fcd34d;
        border-radius: 12px;
        padding: 15px;
        margin: 10px 0;
        display: none;
    }

    .no-rooms-selected-message.show {
        display: block;
    }

    .no-rooms-selected-message i {
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

        .warehouse-info-card {
            flex-direction: column;
            text-align: center;
        }

        .room-checkbox-group {
            grid-template-columns: 1fr;
        }

        .room-detail-input {
            margin-left: 0 !important;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-store"></i>Add Store</h1>
            <p>Step 2 of 2: Add store location to warehouse</p>
        </div>
        <a href="{{ route('inventory.warehouses.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Skip & Finish
        </a>
    </div>

    <!-- Step Progress -->
    <div class="step-progress">
        <div class="step-item completed">
            <div class="step-number">✓</div>
            <div class="step-label">Warehouse Details</div>
        </div>
        <div class="step-item active">
            <div class="step-number">2</div>
            <div class="step-label">Store Details</div>
        </div>
    </div>

    <div class="step-indicator">
        <strong>Step 2 of 2:</strong> Add store(s) to <strong>{{ $warehouse->warehouse_name }}</strong> ({{ $warehouse->warehouse_code }})
    </div>

    <!-- Warehouse Info -->
    <div class="warehouse-info-card">
        <div>
            <span class="label">Warehouse:</span>
            <span class="value">{{ $warehouse->warehouse_name }}</span>
            <span class="badge bg-success ms-2">{{ $warehouse->warehouse_code }}</span>
        </div>
        <div>
            <span class="label">Location:</span>
            <span class="value">{{ $warehouse->getLocationPathAttribute() ?? 'N/A' }}</span>
        </div>
        <div>
            <span class="label">Status:</span>
            <span class="badge {{ $warehouse->status ? 'bg-success' : 'bg-danger' }}">
                {{ $warehouse->status ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="apply-all-toast" id="applyAllToast">
        <i class="fas fa-check-circle"></i> Applied to all selected rooms!
    </div>

    <!-- Form -->
    <div class="form-card">
        <form method="POST" action="{{ route('inventory.warehouses.store-store', $warehouse->id) }}" id="storeForm">
            @csrf

            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle"></i> 
                Stores will be created for each selected room. Each store will have a unique name and code based on the room.
                <br>
                <small class="text-muted">You can set common details for all rooms or customize per room.</small>
            </div>

            <!-- Basic Information -->
            <h5 class="mb-3"><i class="fas fa-info-circle" style="color: #10b981;"></i> Store Information</h5>
            
            <!-- Location Information -->
            <div class="location-section">
                <div class="section-title">
                    <i class="fas fa-map-marker-alt"></i> Store Location Details
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Warehouse <span class="text-danger">*</span></label>
                        <select name="warehouse_id" id="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror" required>
                            <option value="">Select Warehouse</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" 
                                    {{ old('warehouse_id', $warehouse->id) == $wh->id ? 'selected' : '' }}
                                    data-block-id="{{ $wh->block_id }}">
                                    {{ $wh->warehouse_name }} ({{ $wh->warehouse_code }})
                                </option>
                            @endforeach
                        </select>
                        @error('warehouse_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Floor <span class="text-danger">*</span></label>
                        <select name="floor_id" id="floor_id" class="form-select @error('floor_id') is-invalid @enderror" required>
                            <option value="">Select Floor</option>
                            @foreach($floors as $floor)
                                <option value="{{ $floor->id }}" 
                                    {{ old('floor_id') == $floor->id ? 'selected' : '' }}
                                    data-block-id="{{ $floor->block_id }}">
                                    {{ $floor->floor_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('floor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted" id="floor_hint">Select a floor to see available store room(s)</small>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">
                            Store Room(s) <span class="text-danger">*</span>
                            <span class="auto-filled-badge" id="autoFillBadge" style="display: none;">
                                <i class="fas fa-magic"></i> Auto-filled
                            </span>
                        </label>
                        <div id="roomContainer">
                            <div class="alert alert-warning" id="noRoomsMessage">
                                <i class="fas fa-info-circle"></i> Please select a floor to see available store rooms.
                            </div>
                            <div id="roomList" style="display: none;">
                                <div class="room-checkbox-group" id="roomCheckboxGroup">
                                    <!-- Rooms will be dynamically populated -->
                                </div>
                                <div class="selected-count" id="selectedCount">Selected: 0 room(s)</div>
                            </div>
                            <!-- No rooms selected message (shown when floor is selected but all rooms are unchecked) -->
                            <div class="no-rooms-selected-message" id="noRoomsSelectedMessage">
                                <i class="fas fa-info-circle"></i> No store rooms selected. Please select at least one room to create stores.
                            </div>
                        </div>
                        <input type="hidden" name="room_ids" id="selectedRoomIds" value="">
                        @error('room_ids')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Store Details -->
            <div class="location-section">
                <div class="section-title">
                    <i class="fas fa-store"></i> Store Details
                    <small class="text-muted" style="font-weight: 400; font-size: 0.8rem;">
                        (Set common details or customize per room)
                    </small>
                </div>

                <!-- Common Details -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="common-details-header">
                            <h6 class="text-muted" style="font-size: 0.85rem; margin: 0;">
                                <i class="fas fa-arrow-down"></i> Common Details <span id="roomCountLabel" style="font-weight: 400;">(Apply to all selected Store rooms)</span>
                            </h6>
                            <button type="button" id="applyCommonToAllBtn" class="btn btn-sm btn-success apply-all-btn-main" style="font-size: 0.7rem; padding: 0.2rem 0.8rem;">
                                <i class="fas fa-arrow-right"></i> Apply to All
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Contact Person
                            <button type="button" class="apply-all-btn" id="applyContactToAll">Apply to All</button>
                        </label>
                        <input type="text" name="common_contact_person" id="common_contact_person" 
                            class="form-control" value="{{ old('common_contact_person') }}" 
                            placeholder="Common contact person">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Phone
                            <button type="button" class="apply-all-btn" id="applyPhoneToAll">Apply to All</button>
                        </label>
                        <input type="text" name="common_phone" id="common_phone" 
                            class="form-control" value="{{ old('common_phone') }}" 
                            placeholder="Common phone number">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Email
                            <button type="button" class="apply-all-btn" id="applyEmailToAll">Apply to All</button>
                        </label>
                        <input type="email" name="common_email" id="common_email" 
                            class="form-control" value="{{ old('common_email') }}" 
                            placeholder="Common email address">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Capacity (Units)
                            <button type="button" class="apply-all-btn" id="applyCapacityToAll">Apply to All</button>
                        </label>
                        <input type="number" name="common_capacity" id="common_capacity" 
                            class="form-control" value="{{ old('common_capacity') }}" 
                            min="0" placeholder="Common capacity">
                        <small class="text-muted">Storage capacity for each store</small>
                    </div>
                </div>

                <!-- Per Room Details -->
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-muted" style="font-size: 0.85rem;">
                            <i class="fas fa-pencil-alt"></i> Per Store Room Details (Optional - Override common details)
                        </h6>
                        <div id="perRoomDetailsContainer">
                            <div class="alert alert-warning" id="noRoomDetailsMessage">
                                <i class="fas fa-info-circle"></i> Select rooms above to customize per-room details.
                            </div>
                            <div id="perRoomDetailsList" style="display: none;">
                                <!-- Per room details will be dynamically populated -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Store Summary -->
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-muted" style="font-size: 0.85rem;">
                            <i class="fas fa-list"></i> Stores to be Created
                        </h6>
                        <div id="storeSummaryContainer">
                            <div class="alert alert-warning" id="noStoreSummaryMessage">
                                <i class="fas fa-info-circle"></i> Select rooms above to see stores that will be created.
                            </div>
                            <div id="storeSummaryList" style="display: none;">
                                <!-- Store summary will be dynamically populated -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Store Info Box -->
                <div class="store-info-box" id="storeInfoBox">
                    <h6><i class="fas fa-store"></i> Store Summary</h6>
                    <div class="info-row">
                        <span class="label">Rooms Selected:</span>
                        <span class="value" id="infoRooms">-</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Floor:</span>
                        <span class="value" id="infoFloorName">-</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Total Stores:</span>
                        <span class="value" id="infoTotalStores">0</span>
                    </div>
                </div>
            </div>

            <!-- Default Store Selection -->
            <div class="default-store-section" id="defaultStoreSection">
                <div class="section-title">
                    <i class="fas fa-star"></i>
                    <span>Select which store should be the <strong>default</strong></span>
                    <span class="badge bg-warning text-dark ms-2">Choose one</span>
                </div>
                
                <p class="text-muted" style="font-size: 0.85rem;">
                    <i class="fas fa-arrow-right"></i> Choose which store will be marked as default. 
                    This store will be used as the primary store for this warehouse.
                </p>
                
                <div id="defaultStoreOptions" class="row">
                    <!-- Dynamically populated with radio buttons -->
                </div>
                
                <div id="singleStoreDefaultMessage" class="alert alert-info" style="display: none;">
                    <i class="fas fa-info-circle"></i> Only one store is being created. It will be automatically set as the default store.
                </div>
            </div>

            <!-- Address with Location Detection -->
            <h5 class="mb-3"><i class="fas fa-address-book" style="color: #10b981;"></i> Address</h5>
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
            </div>

            <!-- Settings -->
            <h5 class="mb-3"><i class="fas fa-cog" style="color: #10b981;"></i> Settings</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_default" class="form-check-input" id="isDefault" 
                               value="1" {{ old('is_default') ? 'checked' : '' }}>
                        <label class="form-check-label" for="isDefault">
                            Set as Default Store
                        </label>
                        <span id="defaultStoreAutoNotice" style="display: none; color: #d97706; font-size: 0.8rem; margin-left: 0.5rem;">
                            <i class="fas fa-info-circle"></i> Auto-set as default (only store)
                        </span>
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
            <div class="mt-3 d-flex gap-2 justify-content-between">
                <div>
                    <a href="{{ route('inventory.warehouses.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Skip & Finish
                    </a>
                </div>
                <div>
                    <a href="{{ route('inventory.warehouses.edit', $warehouse->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Warehouse
                    </a>
                    <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                        <i class="fas fa-save"></i> Save Stores & Finish
                    </button>
                </div>
            </div>
            <small class="text-muted d-block mt-2">
                <i class="fas fa-info-circle"></i> You can add more stores later from the warehouse details page.
            </small>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const allFloors = @json($floors);
        const allRooms = @json($rooms);
        const currentWarehouseBlockId = '{{ $warehouse->block_id }}';
        
        // Store floor names for display
        const floorNames = {};
        @foreach($floors as $floor)
            floorNames[{{ $floor->id }}] = '{{ $floor->floor_name }}';
        @endforeach

        // Store room details for display
        const roomDetails = {};
        @foreach($rooms as $room)
            roomDetails[{{ $room->id }}] = {
                name: '{{ $room->room_name }}',
                code: '{{ $room->room_code ?? $room->id }}',
                room_type: '{{ $room->room_type }}'
            };
        @endforeach

        // DOM Elements
        const warehouseSelect = document.getElementById('warehouse_id');
        const floorSelect = document.getElementById('floor_id');
        const floorHint = document.getElementById('floor_hint');
        const roomCheckboxGroup = document.getElementById('roomCheckboxGroup');
        const noRoomsMessage = document.getElementById('noRoomsMessage');
        const noRoomsSelectedMessage = document.getElementById('noRoomsSelectedMessage');
        const roomList = document.getElementById('roomList');
        const selectedCount = document.getElementById('selectedCount');
        const selectedRoomIds = document.getElementById('selectedRoomIds');
        const autoFillBadge = document.getElementById('autoFillBadge');
        const storeInfoBox = document.getElementById('storeInfoBox');
        const infoRooms = document.getElementById('infoRooms');
        const infoFloorName = document.getElementById('infoFloorName');
        const infoTotalStores = document.getElementById('infoTotalStores');
        const submitBtn = document.getElementById('submitBtn');
        const roomCountLabel = document.getElementById('roomCountLabel');
        const applyAllBtnMain = document.getElementById('applyCommonToAllBtn');
        const defaultStoreSection = document.getElementById('defaultStoreSection');
        const defaultStoreOptions = document.getElementById('defaultStoreOptions');
        const singleStoreDefaultMessage = document.getElementById('singleStoreDefaultMessage');
        const isDefaultCheckbox = document.getElementById('isDefault');
        const defaultStoreAutoNotice = document.getElementById('defaultStoreAutoNotice');
        
        // Common details elements
        const commonContact = document.getElementById('common_contact_person');
        const commonPhone = document.getElementById('common_phone');
        const commonEmail = document.getElementById('common_email');
        const commonCapacity = document.getElementById('common_capacity');
        
        // Per room details
        const perRoomDetailsList = document.getElementById('perRoomDetailsList');
        const noRoomDetailsMessage = document.getElementById('noRoomDetailsMessage');
        
        // Store summary
        const storeSummaryList = document.getElementById('storeSummaryList');
        const noStoreSummaryMessage = document.getElementById('noStoreSummaryMessage');
        
        // Location elements
        const getLocationBtn = document.getElementById('getLocationBtn');
        const locationStatus = document.getElementById('locationStatus');
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        const addressTextarea = document.getElementById('address');
        const cityInput = document.getElementById('city');
        const stateInput = document.getElementById('state');
        const pincodeInput = document.getElementById('pincode');

        // Apply All buttons
        const applyAllBtns = document.querySelectorAll('.apply-all-btn');

        // Toast notification
        const toast = document.getElementById('applyAllToast');

        let isAutoSelected = false;
        let selectedRoomData = [];
        let currentFloorId = null;

        // Show toast notification
        function showToast(message) {
            toast.textContent = message || 'Applied to all selected rooms!';
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // Toggle visibility of Apply to All buttons based on selected room count
        function toggleApplyAllButtons(count) {
            const show = count > 1;
            applyAllBtns.forEach(btn => {
                btn.classList.toggle('visible', show);
            });
            applyAllBtnMain.classList.toggle('visible', show);
            if (roomCountLabel) {
                roomCountLabel.textContent = show ? '(Apply to all selected Store rooms)' : '';
            }
        }

        // Clear all rooms (called when floor changes or no rooms found)
        function clearAllRooms() {
            roomCheckboxGroup.innerHTML = '';
            roomList.style.display = 'none';
            noRoomsMessage.style.display = 'block';
            noRoomsMessage.innerHTML = '<i class="fas fa-info-circle"></i> Please select a floor to see available store rooms.';
            noRoomsSelectedMessage.classList.remove('show');
            selectedCount.textContent = 'Selected: 0 room(s)';
            selectedRoomIds.value = '';
            submitBtn.disabled = true;
            
            // Hide all details
            storeInfoBox.classList.remove('show');
            autoFillBadge.style.display = 'none';
            perRoomDetailsList.style.display = 'none';
            noRoomDetailsMessage.style.display = 'block';
            storeSummaryList.style.display = 'none';
            noStoreSummaryMessage.style.display = 'block';
            toggleApplyAllButtons(0);
            defaultStoreSection.classList.remove('show');
            isDefaultCheckbox.checked = false;
            isDefaultCheckbox.disabled = true;
            defaultStoreAutoNotice.style.display = 'none';
        }

        // Filter floors based on warehouse's block
        function filterFloors(blockId, selectedFloorId = null) {
            floorSelect.innerHTML = '<option value="">Select Floor</option>';
            
            if (!blockId) {
                floorSelect.disabled = true;
                floorHint.textContent = 'Please select a warehouse first';
                clearAllRooms();
                return;
            }

            const filteredFloors = allFloors.filter(floor => floor.block_id == blockId);

            if (filteredFloors.length === 0) {
                floorSelect.innerHTML = '<option value="">No floors available</option>';
                floorSelect.disabled = true;
                floorHint.textContent = 'No floors found for this warehouse\'s block';
                clearAllRooms();
                return;
            }

            filteredFloors.forEach(floor => {
                const option = document.createElement('option');
                option.value = floor.id;
                option.textContent = floor.floor_name;
                if (selectedFloorId && floor.id == selectedFloorId) {
                    option.selected = true;
                }
                floorSelect.appendChild(option);
            });

            floorSelect.disabled = false;
            floorHint.textContent = 'Select a floor to see store rooms';

            if (selectedFloorId) {
                currentFloorId = selectedFloorId;
                loadRooms(selectedFloorId);
            }
        }

        // Load rooms for selected floor (only rooms with type 'store')
        function loadRooms(floorId) {
            if (!floorId) {
                clearAllRooms();
                return;
            }

            currentFloorId = floorId;
            const storeRooms = allRooms.filter(room => 
                room.floor_id == floorId && 
                room.room_type && 
                room.room_type.toLowerCase() === 'store'
            );

            roomCheckboxGroup.innerHTML = '';

            if (storeRooms.length === 0) {
                roomList.style.display = 'block';
                noRoomsMessage.style.display = 'block';
                noRoomsMessage.innerHTML = '<i class="fas fa-info-circle"></i> No store rooms found on this floor.';
                noRoomsSelectedMessage.classList.remove('show');
                selectedCount.textContent = 'Selected: 0 room(s)';
                selectedRoomIds.value = '';
                submitBtn.disabled = true;
                
                storeInfoBox.classList.remove('show');
                autoFillBadge.style.display = 'none';
                perRoomDetailsList.style.display = 'none';
                noRoomDetailsMessage.style.display = 'block';
                storeSummaryList.style.display = 'none';
                noStoreSummaryMessage.style.display = 'block';
                toggleApplyAllButtons(0);
                defaultStoreSection.classList.remove('show');
                isDefaultCheckbox.checked = false;
                isDefaultCheckbox.disabled = true;
                defaultStoreAutoNotice.style.display = 'none';
                return;
            }

            // Show the room list
            roomList.style.display = 'block';
            noRoomsMessage.style.display = 'none';
            noRoomsSelectedMessage.classList.remove('show');

            const isSingleRoom = storeRooms.length === 1;

            storeRooms.forEach(room => {
                const div = document.createElement('div');
                div.className = 'form-check';
                if (isSingleRoom) {
                    div.classList.add('auto-selected');
                }
                
                const input = document.createElement('input');
                input.type = 'checkbox';
                input.className = 'form-check-input room-checkbox';
                input.id = 'room_' + room.id;
                input.value = room.id;
                input.dataset.roomName = room.room_name;
                input.dataset.roomCode = room.room_code || room.id;

                if (isSingleRoom) {
                    input.checked = true;
                    input.disabled = true;
                    isAutoSelected = true;
                }

                const label = document.createElement('label');
                label.className = 'form-check-label';
                label.htmlFor = 'room_' + room.id;
                label.textContent = room.room_name + (room.room_code ? ' (' + room.room_code + ')' : '');
                if (isSingleRoom) {
                    label.innerHTML += ' <span class="badge bg-success" style="font-size: 0.6rem;">Auto-selected</span>';
                }

                div.appendChild(input);
                div.appendChild(label);
                roomCheckboxGroup.appendChild(div);

                if (!input.disabled) {
                    input.addEventListener('change', function() {
                        updateSelectedRooms();
                    });
                }
            });

            if (isSingleRoom) {
                updateSelectedRooms();
            } else {
                // Multiple rooms - show selection prompt but KEEP ROOMS VISIBLE
                noRoomsMessage.style.display = 'block';
                noRoomsMessage.innerHTML = '<i class="fas fa-info-circle"></i> Please select at least one store room.';
                roomList.style.display = 'block';
                
                selectedRoomIds.value = '';
                selectedCount.textContent = 'Selected: 0 room(s)';
                submitBtn.disabled = true;
                
                storeInfoBox.classList.remove('show');
                autoFillBadge.style.display = 'none';
                perRoomDetailsList.style.display = 'none';
                noRoomDetailsMessage.style.display = 'block';
                storeSummaryList.style.display = 'none';
                noStoreSummaryMessage.style.display = 'block';
                toggleApplyAllButtons(0);
                defaultStoreSection.classList.remove('show');
                isDefaultCheckbox.checked = false;
                isDefaultCheckbox.disabled = true;
                defaultStoreAutoNotice.style.display = 'none';
            }

            const floorName = floorNames[floorId] || 'N/A';
            infoFloorName.textContent = floorName;
        }

        // Update selected rooms
        function updateSelectedRooms() {
            const checkedRooms = document.querySelectorAll('.room-checkbox:checked:not(:disabled)');
            const autoSelectedRooms = document.querySelectorAll('.room-checkbox:checked:disabled');
            
            const allCheckedRooms = [...checkedRooms, ...autoSelectedRooms];
            
            const selectedIds = [];
            const roomNames = [];

            allCheckedRooms.forEach(room => {
                const roomId = parseInt(room.value);
                if (!isNaN(roomId)) {
                    selectedIds.push(roomId);
                    roomNames.push(room.dataset.roomName);
                }
            });

            selectedRoomData = selectedIds.map(id => ({
                id: id,
                name: roomDetails[id]?.name || 'Room ' + id,
                code: roomDetails[id]?.code || id
            }));

            selectedRoomIds.value = selectedIds.join(',');

            const count = selectedIds.length;
            selectedCount.textContent = 'Selected: ' + count + ' room' + (count !== 1 ? 's' : '');

            submitBtn.disabled = count === 0;
            toggleApplyAllButtons(count);

            if (count === 0 && currentFloorId) {
                // No rooms selected - show selection prompt
                noRoomsMessage.style.display = 'block';
                noRoomsMessage.innerHTML = '<i class="fas fa-info-circle"></i> Please select at least one store room.';
                roomList.style.display = 'block';
                noRoomsSelectedMessage.classList.add('show');
                
                storeInfoBox.classList.remove('show');
                infoRooms.textContent = '-';
                infoTotalStores.textContent = '0';
                autoFillBadge.style.display = 'none';
                
                perRoomDetailsList.style.display = 'none';
                noRoomDetailsMessage.style.display = 'block';
                
                storeSummaryList.style.display = 'none';
                noStoreSummaryMessage.style.display = 'block';
                defaultStoreSection.classList.remove('show');
                isDefaultCheckbox.checked = false;
                isDefaultCheckbox.disabled = true;
                defaultStoreAutoNotice.style.display = 'none';
                return;
            }

            if (count === 0) {
                clearAllRooms();
                return;
            }

            // Rooms are selected
            noRoomsMessage.style.display = 'none';
            roomList.style.display = 'block';
            noRoomsSelectedMessage.classList.remove('show');

            storeInfoBox.classList.add('show');
            infoRooms.textContent = roomNames.join(', ');
            infoTotalStores.textContent = count;
            autoFillBadge.style.display = 'inline-block';

            updatePerRoomDetails();
            updateStoreSummary();
            updateDefaultStoreSelection();
        }

        // Update per-room details section
        function updatePerRoomDetails() {
            const roomIds = selectedRoomIds.value.split(',').filter(id => id && id !== 'undefined');
            
            if (roomIds.length === 0) {
                perRoomDetailsList.style.display = 'none';
                noRoomDetailsMessage.style.display = 'block';
                return;
            }

            noRoomDetailsMessage.style.display = 'none';
            perRoomDetailsList.style.display = 'block';
            perRoomDetailsList.innerHTML = '';

            roomIds.forEach((id) => {
                const roomId = parseInt(id);
                if (isNaN(roomId)) return;
                
                const room = roomDetails[roomId];
                if (!room) return;

                const div = document.createElement('div');
                div.className = 'row mb-2 p-2';
                div.style.cssText = 'background: white; border-radius: 8px; border: 1px solid var(--border-color);';
                
                div.innerHTML = `
                    <div class="col-md-3">
                        <strong style="font-size: 0.9rem;">${room.name}</strong>
                    </div>
                    <div class="col-md-3 room-detail-input">
                        <input type="text" name="per_room_contact[${roomId}]" class="form-control form-control-sm" 
                            placeholder="Contact Person" value="${commonContact.value || ''}">
                    </div>
                    <div class="col-md-2 room-detail-input">
                        <input type="text" name="per_room_phone[${roomId}]" class="form-control form-control-sm" 
                            placeholder="Phone" value="${commonPhone.value || ''}">
                    </div>
                    <div class="col-md-2 room-detail-input">
                        <input type="email" name="per_room_email[${roomId}]" class="form-control form-control-sm" 
                            placeholder="Email" value="${commonEmail.value || ''}">
                    </div>
                    <div class="col-md-2 room-detail-input">
                        <input type="number" name="per_room_capacity[${roomId}]" class="form-control form-control-sm" 
                            placeholder="Capacity" min="0" value="${commonCapacity.value || ''}">
                    </div>
                `;
                
                perRoomDetailsList.appendChild(div);
            });
        }

        // Update store summary
        function updateStoreSummary() {
            const roomIds = selectedRoomIds.value.split(',').filter(id => id && id !== 'undefined');
            
            if (roomIds.length === 0) {
                storeSummaryList.style.display = 'none';
                noStoreSummaryMessage.style.display = 'block';
                return;
            }

            noStoreSummaryMessage.style.display = 'none';
            storeSummaryList.style.display = 'block';
            storeSummaryList.innerHTML = '';

            roomIds.forEach((id, index) => {
                const roomId = parseInt(id);
                if (isNaN(roomId)) return;
                
                const room = roomDetails[roomId];
                if (!room) return;

                const storeName = room.name;
                const storeCode = 'ST-' + (room.code ? room.code.toUpperCase() : 'RM' + String(roomId).padStart(4, '0'));

                const div = document.createElement('div');
                div.className = 'store-summary-info';
                div.innerHTML = `
                    <div class="store-item">
                        <span class="store-name">${storeName}</span>
                        <span class="store-code">(${storeCode})</span>
                    </div>
                `;
                storeSummaryList.appendChild(div);
            });
        }

        // Update default store selection - FIXED: Only one default store allowed
        function updateDefaultStoreSelection() {
            const roomIds = selectedRoomIds.value.split(',').filter(id => id && id !== 'undefined');
            const count = roomIds.length;
            
            if (count === 0) {
                defaultStoreSection.classList.remove('show');
                isDefaultCheckbox.checked = false;
                isDefaultCheckbox.disabled = true;
                defaultStoreAutoNotice.style.display = 'none';
                return;
            }

            defaultStoreSection.classList.add('show');
            
            const isSingleRoom = count === 1;
            
            if (isSingleRoom) {
                // Only one store - auto-set as default
                singleStoreDefaultMessage.style.display = 'block';
                defaultStoreOptions.innerHTML = '';
                isDefaultCheckbox.checked = true;
                isDefaultCheckbox.disabled = true;
                defaultStoreAutoNotice.style.display = 'inline';
                return;
            }

            // Multiple stores - user must select ONE default
            singleStoreDefaultMessage.style.display = 'none';
            isDefaultCheckbox.disabled = true;
            isDefaultCheckbox.checked = false;
            defaultStoreAutoNotice.style.display = 'none';

            defaultStoreOptions.innerHTML = '';

            // Check if there's already a default store selected (from old input)
            let hasDefaultSelected = false;
            let defaultRoomId = null;

            // Try to get previously selected default from old input or use first room
            const oldDefault = document.querySelector('input[name="default_store_room_id"]:checked');
            if (oldDefault) {
                defaultRoomId = oldDefault.value;
                hasDefaultSelected = true;
            }

            // If no default selected, use the first room as default
            if (!hasDefaultSelected && roomIds.length > 0) {
                defaultRoomId = roomIds[0];
                hasDefaultSelected = true;
            }

            roomIds.forEach((id, index) => {
                const roomId = parseInt(id);
                if (isNaN(roomId)) return;
                
                const room = roomDetails[roomId];
                if (!room) return;

                const storeName = room.name;
                const storeCode = 'ST-' + (room.code ? room.code.toUpperCase() : 'RM' + String(roomId).padStart(4, '0'));
                const isDefault = (defaultRoomId == roomId);

                const div = document.createElement('div');
                div.className = 'col-md-4 mb-2';
                div.innerHTML = `
                    <div class="default-store-option ${isDefault ? 'selected' : ''}">
                        <div class="form-check">
                            <input type="radio" name="default_store_room_id" class="form-check-input default-store-radio" 
                                id="default_store_${roomId}" value="${roomId}"
                                ${isDefault ? 'checked' : ''}>
                            <label class="form-check-label" for="default_store_${roomId}">
                                <strong>${storeName}</strong>
                                <br><small class="text-muted">${storeCode}</small>
                                ${isDefault ? '<span class="default-store-badge"><i class="fas fa-star"></i> Default</span>' : ''}
                            </label>
                        </div>
                    </div>
                `;
                defaultStoreOptions.appendChild(div);

                // Add click handler for visual selection
                const optionDiv = div.querySelector('.default-store-option');
                const radio = div.querySelector('.default-store-radio');
                
                // Click on the whole option div
                optionDiv.addEventListener('click', function(e) {
                    // Don't trigger if clicking on the radio input itself (to avoid double handling)
                    if (e.target.tagName === 'INPUT') return;
                    
                    // Unselect all options
                    document.querySelectorAll('.default-store-option').forEach(el => {
                        el.classList.remove('selected');
                        // Remove default badge from all
                        const badge = el.querySelector('.default-store-badge');
                        if (badge) badge.remove();
                    });
                    
                    // Select this option
                    this.classList.add('selected');
                    radio.checked = true;
                    
                    // Add default badge to this option's label
                    const label = this.querySelector('.form-check-label');
                    if (label) {
                        // Remove any existing badge first
                        const existingBadge = label.querySelector('.default-store-badge');
                        if (existingBadge) existingBadge.remove();
                        label.innerHTML += ' <span class="default-store-badge"><i class="fas fa-star"></i> Default</span>';
                    }
                });

                // Radio change handler
                radio.addEventListener('change', function() {
                    // Unselect all options
                    document.querySelectorAll('.default-store-option').forEach(el => {
                        el.classList.remove('selected');
                        const badge = el.querySelector('.default-store-badge');
                        if (badge) badge.remove();
                    });
                    
                    // Select this option
                    const parentOption = this.closest('.default-store-option');
                    if (parentOption) {
                        parentOption.classList.add('selected');
                        const label = parentOption.querySelector('.form-check-label');
                        if (label) {
                            const existingBadge = label.querySelector('.default-store-badge');
                            if (existingBadge) existingBadge.remove();
                            label.innerHTML += ' <span class="default-store-badge"><i class="fas fa-star"></i> Default</span>';
                        }
                    }
                });
            });
        }

        // Apply common details to all per-room inputs
        function applyCommonToAll() {
            const contactVal = commonContact.value;
            const phoneVal = commonPhone.value;
            const emailVal = commonEmail.value;
            const capacityVal = commonCapacity.value;

            const perRoomInputs = perRoomDetailsList.querySelectorAll('.room-detail-input input');
            perRoomInputs.forEach(input => {
                if (input.name.includes('per_room_contact')) {
                    input.value = contactVal;
                } else if (input.name.includes('per_room_phone')) {
                    input.value = phoneVal;
                } else if (input.name.includes('per_room_email')) {
                    input.value = emailVal;
                } else if (input.name.includes('per_room_capacity')) {
                    input.value = capacityVal;
                }
            });

            showToast('Applied common details to all selected rooms!');
        }

        // ============================================
        // LOCATION DETECTION
        // ============================================

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

        function getAddressFromCoords(lat, lng) {
            const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data && data.address) {
                        const address = data.address;
                        const parts = [];
                        if (address.road) parts.push(address.road);
                        if (address.suburb) parts.push(address.suburb);
                        if (address.city || address.town || address.village) {
                            parts.push(address.city || address.town || address.village);
                        }
                        if (parts.length > 0 && addressTextarea) {
                            addressTextarea.value = parts.join(', ');
                        }
                        if (cityInput && (address.city || address.town || address.village)) {
                            cityInput.value = address.city || address.town || address.village;
                        }
                        if (stateInput && address.state) {
                            stateInput.value = address.state;
                        }
                        if (pincodeInput && address.postcode) {
                            pincodeInput.value = address.postcode;
                        }
                    }
                })
                .catch(error => console.log('Reverse geocoding failed:', error));
        }

        // ============================================
        // EVENT LISTENERS
        // ============================================

        document.getElementById('applyCommonToAllBtn').addEventListener('click', applyCommonToAll);
        document.getElementById('applyContactToAll').addEventListener('click', applyCommonToAll);
        document.getElementById('applyPhoneToAll').addEventListener('click', applyCommonToAll);
        document.getElementById('applyEmailToAll').addEventListener('click', applyCommonToAll);
        document.getElementById('applyCapacityToAll').addEventListener('click', applyCommonToAll);

        [commonContact, commonPhone, commonEmail, commonCapacity].forEach(field => {
            field.addEventListener('change', function() {
                if (selectedRoomIds.value) {
                    updatePerRoomDetails();
                }
            });
        });

        // Warehouse change - auto-select floor
        warehouseSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const blockId = selectedOption ? selectedOption.dataset.blockId : null;
            
            if (blockId) {
                const floorsForBlock = allFloors.filter(floor => floor.block_id == blockId);
                if (floorsForBlock.length > 0) {
                    const firstFloorId = floorsForBlock[0].id;
                    filterFloors(blockId, firstFloorId);
                    floorSelect.value = firstFloorId;
                    loadRooms(firstFloorId);
                    return;
                }
            }
            filterFloors(blockId, null);
            clearAllRooms();
        });

        // Floor change - load rooms
        floorSelect.addEventListener('change', function() {
            const floorId = this.value;
            if (floorId) {
                currentFloorId = floorId;
                loadRooms(floorId);
            } else {
                clearAllRooms();
            }
        });

        // Initialize with current warehouse
        const currentWarehouseId = '{{ old('warehouse_id', $warehouse->id) }}';
        if (currentWarehouseId) {
            const selectedOption = warehouseSelect.querySelector('option[value="' + currentWarehouseId + '"]');
            if (selectedOption) {
                const blockId = selectedOption.dataset.blockId;
                const oldFloorId = '{{ old('floor_id') }}';
                if (oldFloorId) {
                    filterFloors(blockId, oldFloorId);
                    floorSelect.value = oldFloorId;
                    loadRooms(oldFloorId);
                } else if (blockId) {
                    const floorsForBlock = allFloors.filter(floor => floor.block_id == blockId);
                    if (floorsForBlock.length > 0) {
                        const firstFloorId = floorsForBlock[0].id;
                        filterFloors(blockId, firstFloorId);
                        floorSelect.value = firstFloorId;
                        loadRooms(firstFloorId);
                    }
                }
            }
        } else {
            floorSelect.disabled = true;
        }

        // Form validation - ensure at least one room is selected and default is chosen for multiple stores
        const form = document.getElementById('storeForm');
        form.addEventListener('submit', function(e) {
            const selectedRooms = document.querySelectorAll('.room-checkbox:checked');
            if (selectedRooms.length === 0) {
                e.preventDefault();
                alert('Please select at least one store room.');
                return;
            }

            if (!selectedRoomIds.value) {
                e.preventDefault();
                alert('Please select at least one store room.');
                return;
            }

            // Check if multiple stores are selected and ensure a default is chosen
            const roomIds = selectedRoomIds.value.split(',').filter(id => id && id !== 'undefined');
            if (roomIds.length > 1) {
                const defaultSelected = document.querySelector('input[name="default_store_room_id"]:checked');
                if (!defaultSelected) {
                    e.preventDefault();
                    alert('Please select which store should be the default store.');
                    return;
                }
            }
        });
    });
</script>
@endsection