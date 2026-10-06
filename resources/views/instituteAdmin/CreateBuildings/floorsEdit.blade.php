@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Floor - {{ $floor->floor_number ?? 'Floor' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-gradient: linear-gradient(135deg, #10b981, #059669);
            --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
            --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
            --border-color: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --bg-light: #f8fafc;
        }

        * { box-sizing: border-box; }

        .header {
            background: var(--primary-gradient);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-content h1 {
            font-size: 28px;
            font-weight: 700;
            color: white;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .header-content h1 i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 12px;
        }
        .header-content p {
            opacity: 0.9;
            font-size: 1rem;
            margin: 0.25rem 0 0 0;
        }

        .back-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            border: 2px solid rgba(255,255,255,0.3);
        }
        .back-btn:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
            border: 2px solid var(--border-color);
            max-width: 1400px;
            margin: 0 auto;
        }
        .form-card h2 {
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-card h2 i { color: var(--primary-color); }

        .form-group { margin-bottom: 1.5rem; }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
        }
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
            font-family: inherit;
            background: white;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        }
        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
        }

        .amenities-section {
            margin-top: 2rem;
            border-top: 2px solid var(--border-color);
            padding-top: 1.5rem;
        }
        .amenities-section h3 {
            color: var(--text-dark);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .amenity-group {
            background: var(--bg-light);
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            transition: all 0.3s;
        }
        .amenity-group:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .amenity-group h4 {
            color: var(--text-dark);
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.5rem;
        }
        .amenity-group h4 i {
            color: var(--primary-color);
            width: 20px;
        }
        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 4px 0;
        }
        .checkbox-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary-color);
        }
        .checkbox-item label {
            font-size: 0.875rem;
            color: var(--text-dark);
            cursor: pointer;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-primary {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
        }
        .btn-secondary {
            background: #f1f5f9;
            color: var(--text-dark);
            border: 2px solid var(--border-color);
        }
        .btn-secondary:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }
        .btn-success {
            background: var(--success-gradient);
            color: white;
        }
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }
        .btn-danger {
            background: var(--danger-gradient);
            color: white;
        }
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
        }
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }
        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--border-color);
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--success-gradient);
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1001;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
            min-width: 300px;
        }
        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }
        .toast.error {
            background: var(--danger-gradient);
        }
        .loading-spinner-btn {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .floor-info-box {
            background: var(--bg-light);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }
        .floor-info-box .info-item {
            display: flex;
            flex-direction: column;
        }
        .floor-info-box .info-item .label {
            font-size: 0.7rem;
            text-transform: uppercase;
            color: var(--text-muted);
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .floor-info-box .info-item .value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .allocated-section {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--border-color);
        }

        .allocated-section h4 {
            color: var(--text-dark);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }
        .allocated-section h4 i {
            color: var(--primary-color);
        }

        .tag-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 0.75rem;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            background: var(--bg-light);
            border: 1px solid var(--border-color);
            color: var(--text-dark);
        }

        .tag.orange {
            background: #fffbeb;
            border-color: #fde68a;
            color: #92400e;
        }
        .tag.orange i { color: #f59e0b; }
        .tag.purple {
            background: #f5f3ff;
            border-color: #c4b5fd;
            color: #5b21b6;
        }
        .tag.purple i { color: #7c3aed; }
        .tag.green {
            background: #f0fdf4;
            border-color: #bbf7d0;
            color: #166534;
        }
        .tag.green i { color: #10b981; }
        .tag.teal {
            background: #ecfdf5;
            border-color: #6ee7b7;
            color: #065f46;
        }
        .tag.teal i { color: #0d9488; }
        .tag.pink {
            background: #fce7f3;
            border-color: #f9a8d4;
            color: #831843;
        }
        .tag.pink i { color: #db2777; }

        .tag .remove-btn {
            cursor: pointer;
            color: #ef4444;
            margin-left: 4px;
            font-size: 0.7rem;
            opacity: 0.6;
            transition: all 0.2s;
        }
        .tag .remove-btn:hover {
            opacity: 1;
            transform: scale(1.2);
        }

        .add-item-container {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
            flex-wrap: wrap;
        }
        .add-item-container input {
            flex: 1;
            min-width: 150px;
            padding: 8px 12px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.9rem;
        }
        .add-item-container input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .empty-text {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-style: italic;
        }

        /* Facility checkbox cards */
        .facility-checkbox-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 8px;
        }
        .facility-checkbox-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            user-select: none;
        }
        .facility-checkbox-card:hover {
            border-color: var(--primary-color);
            background: #f0f4ff;
        }
        .facility-checkbox-card.checked {
            border-color: var(--primary-color);
            background: #f0f4ff;
        }
        .facility-checkbox-card input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
            cursor: pointer;
            flex-shrink: 0;
        }
        .facility-checkbox-card .facility-icon-box {
            width: 32px;
            height: 32px;
            background: #d1fae5;
            color: #065f46;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .facility-checkbox-card .facility-text {
            flex: 1;
            min-width: 0;
        }
        .facility-checkbox-card .facility-name {
            display: block;
            font-weight: 600;
            color: #1e293b;
            font-size: 0.9rem;
        }
        .facility-checkbox-card .facility-type {
            display: block;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #0d9488;
            letter-spacing: 0.3px;
        }

        @media (max-width: 768px) {
            .header { flex-direction: column; gap: 1rem; text-align: center; }
            .form-card { padding: 1.5rem; }
            .form-actions { flex-direction: column; }
            .amenities-grid { grid-template-columns: 1fr; }
            .toast { left: 20px; right: 20px; min-width: auto; }
            .add-item-container { flex-direction: column; }
            .add-item-container input { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="header">
            <div class="header-content">
                <h1><i class="fas fa-edit"></i> Edit Floor</h1>
                <p>Update floor information, amenities, and facilities</p>
            </div>
            <a href="{{ route('floors.list') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Floors
            </a>
        </div>

        <div class="form-card">
            <h2><i class="fas fa-edit"></i> Edit Floor Details</h2>

            <!-- Floor Info Summary -->
            <div class="floor-info-box">
                <div class="info-item">
                    <span class="label">Floor ID</span>
                    <span class="value">#{{ $floor->id }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Status</span>
                    <span class="value" style="color: {{ ($floor->status ?? 'active') === 'active' ? '#10b981' : '#ef4444' }};">
                        {{ ucfirst(str_replace('_', ' ', $floor->status ?? 'Active')) }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="label">Created</span>
                    <span class="value">
                        {{ $floor->created_at ? \Carbon\Carbon::parse($floor->created_at)->format('M d, Y') : 'N/A' }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="label">Building</span>
                    <span class="value">{{ $floor->building->name ?? 'N/A' }}</span>
                </div>
            </div>

            <form id="edit-floor-form">
                <input type="hidden" id="floor-id" value="{{ $floor->id }}">

                <div class="form-row">
                    <div class="form-group">
                        <label for="block-select" class="form-label">Block <span style="color: #dc3545;">*</span></label>
                        <select id="block-select" class="form-control" required>
                            @if($floor->block)
                                <option value="{{ $floor->block->id }}" selected>
                                    {{ $floor->block->name }}{{ $floor->block->code ? ' (' . $floor->block->code . ')' : '' }}
                                </option>
                            @else
                                <option value="">Loading blocks...</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="floor-number" class="form-label">Floor Number/Name <span style="color: #dc3545;">*</span></label>
                        <input type="text" id="floor-number" class="form-control" required
                               value="{{ $floor->floor_number ?? '' }}"
                               placeholder="e.g., Floor 1, Ground Floor" maxlength="50">
                    </div>
                </div>

                <div class="form-group">
                    <label for="floor-description" class="form-label">Description (Optional)</label>
                    <textarea id="floor-description" class="form-control" rows="3"
                              placeholder="Enter a brief description of the floor...">{{ $floor->description ?? '' }}</textarea>
                </div>

                <!-- Floor Amenities -->
                <div class="amenities-section">
                    <h3><i class="fas fa-concierge-bell"></i> Floor Amenities</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">
                        <i class="fas fa-info-circle"></i> Check the amenities available on this floor
                    </p>
                    <div class="amenities-grid">
                        <div class="amenity-group">
                            <h4><i class="fas fa-snowflake"></i> Air Conditioning</h4>
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="hasAC" value="1">
                                    <label for="hasAC">Has AC</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="isCentralAC" value="1">
                                    <label for="isCentralAC">Central AC System</label>
                                </div>
                            </div>
                        </div>

                        <div class="amenity-group">
                            <h4><i class="fas fa-tint"></i> Water Facility</h4>
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="hasWaterFacility" value="1">
                                    <label for="hasWaterFacility">Water Available</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="hasWaterCooler" value="1">
                                    <label for="hasWaterCooler">Water Cooler</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="hasDrinkingWater" value="1">
                                    <label for="hasDrinkingWater">Drinking Water</label>
                                </div>
                            </div>
                        </div>

                        <div class="amenity-group">
                            <h4><i class="fas fa-fire-extinguisher"></i> Fire Safety</h4>
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="hasFireExtinguisher" value="1">
                                    <label for="hasFireExtinguisher">Fire Extinguisher</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="hasFireAlarm" value="1">
                                    <label for="hasFireAlarm">Fire Alarm System</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="hasEmergencyExit" value="1">
                                    <label for="hasEmergencyExit">Emergency Exit</label>
                                </div>
                            </div>
                        </div>

                        <div class="amenity-group">
                            <h4><i class="fas fa-elevator"></i> Lifts</h4>
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="hasLift" value="1">
                                    <label for="hasLift">Lift/Elevator</label>
                                </div>
                            </div>
                        </div>

                        <div class="amenity-group">
                            <h4><i class="fas fa-toilet"></i> Washrooms</h4>
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="hasWashroom" value="1">
                                    <label for="hasWashroom">Has Washroom</label>
                                </div>
                            </div>
                        </div>

                        <div class="amenity-group">
                            <h4><i class="fas fa-star"></i> Other Facilities</h4>
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="hasWifi" value="1">
                                    <label for="hasWifi">Wi-Fi Available</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="hasProjector" value="1">
                                    <label for="hasProjector">Projector Room</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="hasConference" value="1">
                                    <label for="hasConference">Conference Room</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="hasLibrary" value="1">
                                    <label for="hasLibrary">Library/Reading Room</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Allocated Facilities (from Block) - Checkbox List -->
                <div class="allocated-section">
                    <h4><i class="fas fa-building"></i> Allocated Facilities (from Block)</h4>
                    <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.75rem;">
                        <i class="fas fa-info-circle"></i> Select which block facilities are assigned to this floor
                    </p>
                    <div id="allocated-facilities-container">
                        <span class="empty-text">Loading facilities from block...</span>
                    </div>
                </div>

                <!-- Allocated Amenities (from Block) - Editable -->
                <div class="allocated-section">
                    <h4><i class="fas fa-cubes"></i> Allocated Amenities (from Block)</h4>
                    <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.75rem;">
                        <i class="fas fa-info-circle"></i> Toggle which block amenities are present on this floor
                    </p>
                    <div id="allocated-amenities-container" class="tag-container">
                        <span class="empty-text">Loading allocated amenities...</span>
                    </div>
                </div>

                <!-- Custom Amenities -->
                <div class="allocated-section">
                    <h4><i class="fas fa-plus-circle" style="color: #10b981;"></i> Custom Amenities</h4>
                    <div id="custom-amenities-container" class="tag-container">
                        <span class="empty-text">No custom amenities added.</span>
                    </div>
                    <div class="add-item-container">
                        <input type="text" id="new-custom-amenity-name" placeholder="Amenity name" class="form-control" style="flex: 1;">
                        <input type="number" id="new-custom-amenity-qty" placeholder="Qty" class="form-control" style="width: 80px;" value="1" min="1">
                        <button type="button" class="btn btn-success btn-sm" onclick="addCustomAmenity()">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('floors.list') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <i class="fas fa-save"></i> Update Floor
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message">Floor updated successfully!</span>
    </div>

    <script>
        const API_BASE_URL = '{{ url('/') }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const FLOOR_ID = '{{ $floor->id }}';

        // ============================================================
        // DATA FROM SERVER
        // ============================================================
        const FLOOR = @json($floor);
        const CAMPUS_FACILITIES = @json($campusFacilities ?? []);
        const BLOCK_ALLOCATED_FACILITY_IDS = @json($blockAllocatedFacilityIds ?? []);

        // ============================================================
        // STATE
        // ============================================================
        let allocatedAmenities = {};
        let customAmenities = [];
        let selectedFacilityIds = [];
        let facilityLookup = {};

        // ============================================================
        // FACILITY LOOKUP BUILDER
        // ============================================================
        function buildFacilityLookup() {
            facilityLookup = {};
            if (!CAMPUS_FACILITIES || typeof CAMPUS_FACILITIES !== 'object') return;

            const icons = {
                parking: 'fa-parking', playground: 'fa-futbol',
                swimming_pool: 'fa-swimming-pool', clubhouse: 'fa-home',
                warehouse: 'fa-warehouse', store_room: 'fa-boxes',
                auditorium: 'fa-theater-masks', washrooms: 'fa-restroom'
            };
            const labels = {
                parking: 'Parking', playground: 'Playground',
                swimming_pool: 'Swimming Pool', clubhouse: 'Clubhouse',
                warehouse: 'Warehouse', store_room: 'Store Room',
                auditorium: 'Auditorium', washrooms: 'Washroom'
            };

            for (const type in CAMPUS_FACILITIES) {
                const td = CAMPUS_FACILITIES[type];
                if (!td || typeof td !== 'object') continue;
                const icon = icons[type] || 'fa-building';
                const label = labels[type] || type;

                if (type === 'clubhouse' && td.id) {
                    facilityLookup[td.id] = { name: td.name || label, type_label: label, icon };
                    continue;
                }

                if (type === 'washrooms') {
                    const entries = Array.isArray(td.detailed_washrooms) ? td.detailed_washrooms :
                                    Array.isArray(td.entries) ? td.entries :
                                    Array.isArray(td) ? td : [];
                    entries.forEach(e => {
                        if (e && e.id) facilityLookup[e.id] = { name: e.name || label, type_label: label, icon };
                    });
                    continue;
                }

                ['basement', 'open', 'indoor', 'outdoor', 'entries'].forEach(nk => {
                    if (Array.isArray(td[nk])) {
                        td[nk].forEach(e => {
                            if (e && e.id) facilityLookup[e.id] = { name: e.name || label, type_label: label, icon };
                        });
                    }
                });
            }
        }

        // ============================================================
        // RENDER FACILITIES (Checkbox List)
        // ============================================================
        function renderAllocatedFacilities() {
            const container = document.getElementById('allocated-facilities-container');
            const blockIds = BLOCK_ALLOCATED_FACILITY_IDS || [];

            if (blockIds.length === 0) {
                container.innerHTML = '<span class="empty-text">No facilities allocated to the parent block. Assign facilities to the block first.</span>';
                return;
            }

            let html = '<div class="facility-checkbox-grid">';
            blockIds.forEach(id => {
                const info = facilityLookup[id];
                const name = info ? info.name : 'Unknown Facility';
                const label = info ? info.type_label : 'Unknown';
                const icon = info ? info.icon : 'fa-question-circle';
                const isChecked = selectedFacilityIds.includes(id);

                html += `
                    <label class="facility-checkbox-card ${isChecked ? 'checked' : ''}" data-facility-id="${id}">
                        <input type="checkbox" ${isChecked ? 'checked' : ''}
                               onchange="toggleFloorFacility('${id}', this.checked)">
                        <span class="facility-icon-box">
                            <i class="fas ${icon}"></i>
                        </span>
                        <span class="facility-text">
                            <span class="facility-name">${escapeHtml(name)}</span>
                            <span class="facility-type">${escapeHtml(label)}</span>
                        </span>
                    </label>
                `;
            });
            html += '</div>';
            container.innerHTML = html;
        }

        function toggleFloorFacility(id, checked) {
            if (checked) {
                if (!selectedFacilityIds.includes(id)) selectedFacilityIds.push(id);
            } else {
                selectedFacilityIds = selectedFacilityIds.filter(fid => fid !== id);
            }
            // Update card border
            const card = document.querySelector(`.facility-checkbox-card[data-facility-id="${id}"]`);
            if (card) card.classList.toggle('checked', checked);
        }

        // ============================================================
        // RENDER ALLOCATED AMENITIES
        // ============================================================
        function renderAllocatedAmenities() {
            const container = document.getElementById('allocated-amenities-container');
            const keys = Object.keys(allocatedAmenities).filter(k => k !== 'facility_entries');

            if (keys.length === 0) {
                container.innerHTML = '<span class="empty-text">No amenities allocated from block.</span>';
                return;
            }

            container.innerHTML = keys.map(key => {
                const value = allocatedAmenities[key];
                const displayName = ucwords(key.replace(/_/g, ' '));
                const isEnabled = (value == 1 || value === true || value === '1');
                return `<span class="tag teal" style="cursor:pointer;" onclick="toggleAllocatedAmenity('${key}')">
                    <i class="fas fa-${isEnabled ? 'check-circle' : 'circle'}"></i>
                    ${displayName}
                    <span style="font-size:0.65rem;opacity:0.7;margin-left:4px;">${isEnabled ? '(ON)' : '(OFF)'}</span>
                </span>`;
            }).join('');
        }

        function toggleAllocatedAmenity(key) {
            allocatedAmenities[key] = (allocatedAmenities[key] == 1) ? 0 : 1;
            renderAllocatedAmenities();
        }

        // ============================================================
        // RENDER CUSTOM AMENITIES
        // ============================================================
        function renderCustomAmenities() {
            const container = document.getElementById('custom-amenities-container');

            if (customAmenities.length === 0) {
                container.innerHTML = '<span class="empty-text">No custom amenities added.</span>';
                return;
            }

            container.innerHTML = customAmenities.map((a, index) => {
                if (!a || !a.name) return '';
                const qty = a.quantity || 0;
                return `<span class="tag pink">
                    <i class="fas fa-plus-circle"></i>
                    ${ucwords(a.name)}
                    ${qty > 0 ? `<span style="background:rgba(219,39,119,0.15);padding:1px 8px;border-radius:12px;font-size:0.7rem;margin-left:4px;">×${qty}</span>` : ''}
                    <span class="remove-btn" onclick="removeCustomAmenity(${index})" title="Remove">
                        <i class="fas fa-times"></i>
                    </span>
                </span>`;
            }).join('');
        }

        function addCustomAmenity() {
            const nameInput = document.getElementById('new-custom-amenity-name');
            const qtyInput = document.getElementById('new-custom-amenity-qty');
            const name = nameInput.value.trim();
            const qty = parseInt(qtyInput.value) || 1;

            if (!name) {
                showToast('Please enter an amenity name.', 'error');
                return;
            }

            if (customAmenities.some(a => a.name.toLowerCase() === name.toLowerCase())) {
                showToast('This custom amenity already exists.', 'error');
                return;
            }

            customAmenities.push({ name, quantity: qty });
            nameInput.value = '';
            qtyInput.value = '1';
            renderCustomAmenities();
            showToast('Custom amenity added!');
        }

        function removeCustomAmenity(index) {
            const name = customAmenities[index]?.name || 'amenity';
            if (confirm(`Remove "${name}" from custom amenities?`)) {
                customAmenities.splice(index, 1);
                renderCustomAmenities();
                showToast('Custom amenity removed!');
            }
        }

        // ============================================================
        // HELPERS
        // ============================================================
        function ucwords(str) {
            return str.replace(/\w\S*/g, t => t.charAt(0).toUpperCase() + t.substr(1).toLowerCase());
        }

        function escapeHtml(text) {
            if (text === null || text === undefined) return '';
            const map = { '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#039;' };
            return String(text).replace(/[&<>"']/g, m => map[m]);
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');

            if (!toast || !toastMessage) { alert(message); return; }

            toastMessage.textContent = message;
            toast.className = 'toast';
            if (type === 'error') {
                toast.classList.add('error');
                toast.querySelector('i').className = 'fas fa-exclamation-circle';
            } else {
                toast.querySelector('i').className = 'fas fa-check-circle';
            }
            toast.classList.add('show');

            clearTimeout(toast._timeout);
            toast._timeout = setTimeout(() => toast.classList.remove('show'), 4000);
        }

        // ============================================================
        // POPULATE FORM
        // ============================================================
        function populateForm() {
            // Amenities from boolean flags on floor
            const amenityMap = {
                'hasAC': 'has_ac',
                'isCentralAC': 'is_central_ac',
                'hasWaterFacility': 'has_water_facility',
                'hasWaterCooler': 'has_water_cooler',
                'hasDrinkingWater': 'has_drinking_water',
                'hasFireExtinguisher': 'has_fire_extinguisher',
                'hasFireAlarm': 'has_fire_alarm',
                'hasEmergencyExit': 'has_emergency_exit',
                'hasLift': 'has_lift',
                'hasWashroom': 'has_washroom',
                'hasWifi': 'has_wifi',
                'hasProjector': 'has_projector_room',
                'hasConference': 'has_conference_room',
                'hasLibrary': 'has_library'
            };

            for (const [elementId, dbKey] of Object.entries(amenityMap)) {
                const cb = document.getElementById(elementId);
                if (cb) cb.checked = !!FLOOR[dbKey];
            }

            // Parse allocated amenities
            allocatedAmenities = FLOOR.allocated_amenities || {};
            if (typeof allocatedAmenities === 'string') {
                try { allocatedAmenities = JSON.parse(allocatedAmenities); } catch(e) { allocatedAmenities = {}; }
            }
            if (!allocatedAmenities || typeof allocatedAmenities !== 'object') allocatedAmenities = {};

            // Parse custom amenities
            customAmenities = FLOOR.custom_amenities || [];
            if (typeof customAmenities === 'string') {
                try { customAmenities = JSON.parse(customAmenities); } catch(e) { customAmenities = []; }
            }
            if (!Array.isArray(customAmenities)) customAmenities = [];

            // Parse allocated facility IDs
            let floorFacilityIds = FLOOR.allocated_facility_entries || [];
            if (typeof floorFacilityIds === 'string') {
                try { floorFacilityIds = JSON.parse(floorFacilityIds); } catch(e) { floorFacilityIds = []; }
            }
            if (!Array.isArray(floorFacilityIds)) floorFacilityIds = [];
            selectedFacilityIds = floorFacilityIds
                .map(f => typeof f === 'string' ? f : (f.id || ''))
                .filter(Boolean);

            // Build facility lookup then render
            buildFacilityLookup();
            renderAllocatedFacilities();
            renderAllocatedAmenities();
            renderCustomAmenities();
        }

        // ============================================================
        // SUBMIT
        // ============================================================
        async function submitForm(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submit-btn');
            const originalHTML = submitBtn.innerHTML;

            const blockId = document.getElementById('block-select').value;
            const floorNumber = document.getElementById('floor-number').value.trim();

            if (!blockId) { showToast('Please select a block.', 'error'); return; }
            if (!floorNumber) { showToast('Please enter a floor number/name.', 'error'); return; }

            submitBtn.innerHTML = '<span class="loading-spinner-btn"></span> Updating...';
            submitBtn.disabled = true;

            // Build amenities object (boolean flags)
            const amenities = {
                has_ac: document.getElementById('hasAC').checked ? 1 : 0,
                is_central_ac: document.getElementById('isCentralAC').checked ? 1 : 0,
                has_water_facility: document.getElementById('hasWaterFacility').checked ? 1 : 0,
                has_water_cooler: document.getElementById('hasWaterCooler').checked ? 1 : 0,
                has_drinking_water: document.getElementById('hasDrinkingWater').checked ? 1 : 0,
                has_fire_extinguisher: document.getElementById('hasFireExtinguisher').checked ? 1 : 0,
                has_fire_alarm: document.getElementById('hasFireAlarm').checked ? 1 : 0,
                has_emergency_exit: document.getElementById('hasEmergencyExit').checked ? 1 : 0,
                has_lift: document.getElementById('hasLift').checked ? 1 : 0,
                has_washroom: document.getElementById('hasWashroom').checked ? 1 : 0,
                has_wifi: document.getElementById('hasWifi').checked ? 1 : 0,
                has_projector_room: document.getElementById('hasProjector').checked ? 1 : 0,
                has_conference_room: document.getElementById('hasConference').checked ? 1 : 0,
                has_library: document.getElementById('hasLibrary').checked ? 1 : 0
            };

            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('_token', CSRF_TOKEN);
            formData.append('block_id', blockId);
            formData.append('building_id', '{{ $floor->building_id }}');
            formData.append('floor_number', floorNumber);
            formData.append('description', document.getElementById('floor-description').value.trim() || '');
            formData.append('amenities', JSON.stringify(amenities));
            formData.append('allocated_amenities', JSON.stringify(allocatedAmenities));
            formData.append('allocated_facility_entries', JSON.stringify(selectedFacilityIds));
            formData.append('custom_amenities', JSON.stringify(customAmenities));

            try {
                const response = await fetch(`${API_BASE_URL}/floors/${FLOOR_ID}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    showToast(result.message || 'Floor updated successfully!');
                    setTimeout(() => {
                        window.location.href = '{{ route("floors.list") }}';
                    }, 1200);
                } else {
                    showToast(result.message || 'Failed to update floor', 'error');
                    submitBtn.innerHTML = originalHTML;
                    submitBtn.disabled = false;
                }
            } catch (error) {
                console.error('Update error:', error);
                showToast('Failed to update floor. Please try again.', 'error');
                submitBtn.innerHTML = originalHTML;
                submitBtn.disabled = false;
            }
        }

        // ============================================================
        // INIT
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            if (!FLOOR_ID) {
                showToast('Invalid floor ID', 'error');
                return;
            }

            populateForm();

            document.getElementById('edit-floor-form').addEventListener('submit', submitForm);
        });
    </script>
</body>
</html>
@endsection