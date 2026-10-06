@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Block - {{ $block->name ?? 'Block' }}</title>
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
            margin: 5px 0 0 0;
        }

        .back-btn {
            background: rgba(255,255,255,0.2)!important;
            color: white!important;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            border: 2px solid rgba(255,255,255,0.3);
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.35)!important;
            color: white!important;
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
            font-size: 1.5rem;
        }

        .form-card h2 i { color: var(--primary-color); }

        .form-card h3 {
            color: var(--text-dark);
            margin: 1.5rem 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--border-color);
            font-size: 1.1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-card h3 i { color: var(--primary-color); }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

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
            background: #f8fafc;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
            background: white;
        }

        select.form-control {
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%234361ee' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            padding-right: 40px;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        /* Dynamic cards */
        .dynamic-card {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 14px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .dynamic-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 15px rgba(67,97,238,0.1);
        }

        .dynamic-card .card-badge,
        .dynamic-card .card-badge-sm {
            position: absolute;
            top: -12px;
            left: 16px;
            background: var(--primary-gradient);
            color: white;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .dynamic-card .card-badge-sm {
            background: var(--success-gradient);
        }

        .dynamic-card .card-row {
            display: grid;
            gap: 0.75rem;
        }

        .dynamic-card .form-group { margin-bottom: 0; }

        .add-btn-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1rem;
        }

        /* Amenity Checkbox Row */
        .amenity-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 14px;
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            margin-bottom: 6px;
            transition: all 0.2s;
        }

        .amenity-row:hover {
            border-color: var(--primary-color);
            background: #f0f4ff;
        }

        .amenity-row input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
            cursor: pointer;
            flex-shrink: 0;
        }

        .amenity-row .amenity-name {
            flex: 1;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-dark);
        }

        .amenity-row .amenity-qty {
            width: 70px;
            padding: 6px 10px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.85rem;
            text-align: center;
        }

        .amenity-row .amenity-qty:disabled {
            opacity: 0.5;
            background: #f1f5f9;
            cursor: not-allowed;
        }

        /* Facility Row */
        .facility-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 14px;
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            margin-bottom: 6px;
            transition: all 0.2s;
        }

        .facility-row:hover {
            border-color: var(--primary-color);
            background: #f0f4ff;
        }

        .facility-row input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
            cursor: pointer;
            flex-shrink: 0;
        }

        .facility-row .facility-icon {
            width: 30px;
            height: 30px;
            background: #d1fae5;
            color: #065f46;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .facility-row .facility-info {
            flex: 1;
            min-width: 0;
        }

        .facility-row .facility-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .facility-row .facility-type-badge {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #0d9488;
            letter-spacing: 0.3px;
        }

        .empty-text {
            color: var(--text-muted);
            font-size: 0.9rem;
            padding: 8px 0;
            font-style: italic;
        }

        /* Feature toggle */
        .feature-toggle-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
        }

        .feature-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            background: #f8fafc;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .feature-toggle:hover {
            border-color: var(--primary-color);
            background: #f0f4ff;
        }

        .feature-toggle input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
            cursor: pointer;
        }

        .feature-toggle label {
            font-weight: 600;
            color: var(--text-dark);
            cursor: pointer;
            font-size: 0.9rem;
            margin: 0;
        }

        /* Buttons */
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

        .btn-outline-primary {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-outline-primary:hover {
            background: var(--primary-gradient);
            color: white;
            transform: translateY(-2px);
        }

        .btn-outline-success {
            background: white;
            color: #10b981;
            border: 2px solid #10b981;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-outline-success:hover {
            background: var(--success-gradient);
            color: white;
            transform: translateY(-2px);
        }

        .btn-outline-danger {
            background: white;
            color: #dc3545;
            border: 2px solid #dc3545;
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-outline-danger:hover {
            background: var(--danger-gradient);
            color: white;
            transform: translateY(-2px);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--border-color);
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        /* Loading state */
        .loading-container {
            text-align: center;
            padding: 4rem 2rem;
        }

        .loading-container .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid var(--border-color);
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Toast */
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
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast.error {
            background: var(--danger-gradient);
        }

        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
        }

        /* Section divider */
        .section-divider {
            border-top: 2px solid var(--border-color);
            margin: 1.5rem 0;
            padding-top: 1.5rem;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            .form-card { padding: 1.5rem; }
            .form-actions { flex-direction: column; }
            .form-actions .btn { justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <h1><i class="fas fa-edit"></i> Edit Block</h1>
                <p>Update all block information and details</p>
            </div>
            <a href="{{ route('blocks.list') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Blocks
            </a>
        </div>

        <div class="form-card">
            <h2><i class="fas fa-building"></i> Block Details</h2>

            <form id="edit-block-form">
                <input type="hidden" id="block-id" value="{{ $block->id ?? '' }}">

                <!-- ============================================== -->
                <!-- BASIC INFO                                     -->
                <!-- ============================================== -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Building <span style="color: #dc3545;">*</span></label>
                        <select id="building-id" class="form-control" required>
                            @foreach($buildings as $b)
                                <option value="{{ $b->id }}" {{ ($block->building_id ?? '') == $b->id ? 'selected' : '' }}>
                                    {{ $b->name }}{{ $b->code ? ' (' . $b->code . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Block Name <span style="color: #dc3545;">*</span></label>
                        <input type="text" id="block-name" class="form-control" required
                               value="{{ $block->name ?? '' }}" maxlength="100">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Block Code</label>
                        <input type="text" id="block-code" class="form-control"
                               value="{{ $block->code ?? '' }}" maxlength="20" placeholder="e.g., MB">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select id="block-status" class="form-control">
                            <option value="active" {{ ($block->status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ ($block->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="under_maintenance" {{ ($block->status ?? '') === 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total Floors</label>
                        <input type="number" id="total-floors" class="form-control" min="0"
                               value="{{ $block->total_floors ?? 0 }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total Rooms</label>
                        <input type="number" id="total-rooms" class="form-control" min="0"
                               value="{{ $block->total_rooms ?? 0 }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total Capacity</label>
                        <input type="number" id="total-capacity" class="form-control" min="0"
                               value="{{ $block->total_capacity ?? 0 }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea id="block-description" class="form-control" rows="3"
                              placeholder="Brief description...">{{ $block->description ?? '' }}</textarea>
                </div>

                <!-- ============================================== -->
                <!-- AREA                                           -->
                <!-- ============================================== -->
                <h3><i class="fas fa-vector-square"></i> Total Area of Block</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Area Unit</label>
                        <select id="area-unit" class="form-control">
                            <option value="sq_ft" {{ ($block->area_unit ?? '') === 'sq_ft' ? 'selected' : '' }}>Sq. Ft.</option>
                            <option value="sq_m" {{ ($block->area_unit ?? '') === 'sq_m' ? 'selected' : '' }}>Sq. M.</option>
                            <option value="sq_yd" {{ ($block->area_unit ?? '') === 'sq_yd' ? 'selected' : '' }}>Sq. Yd.</option>
                            <option value="gaj" {{ ($block->area_unit ?? '') === 'gaj' ? 'selected' : '' }}>Gaj</option>
                            <option value="marla" {{ ($block->area_unit ?? '') === 'marla' ? 'selected' : '' }}>Marla</option>
                            <option value="kanal" {{ ($block->area_unit ?? '') === 'kanal' ? 'selected' : '' }}>Kanal</option>
                            <option value="acre" {{ ($block->area_unit ?? '') === 'acre' ? 'selected' : '' }}>Acre</option>
                            <option value="hectare" {{ ($block->area_unit ?? '') == 'hectare' ? 'selected' : '' }}>Hectare</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Area Value</label>
                        <input type="number" id="total-area" class="form-control" min="0" step="0.01"
                               value="{{ $block->total_area ?? 0 }}">
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- FEATURES                                       -->
                <!-- ============================================== -->
                <h3><i class="fas fa-cogs"></i> Block Features</h3>
                <div class="feature-toggle-row">
                    <div class="feature-toggle">
                        <input type="checkbox" id="has-lift" {{ ($block->has_lift ?? false) ? 'checked' : '' }}>
                        <label for="has-lift"><i class="fas fa-elevator"></i> Lift / Elevator</label>
                    </div>
                    <div class="feature-toggle">
                        <input type="checkbox" id="has-fire-safety" {{ ($block->has_fire_safety ?? false) ? 'checked' : '' }}>
                        <label for="has-fire-safety"><i class="fas fa-fire-extinguisher"></i> Fire Safety</label>
                    </div>
                    <div class="feature-toggle">
                        <input type="checkbox" id="has-disabled-access" {{ ($block->has_disabled_access ?? false) ? 'checked' : '' }}>
                        <label for="has-disabled-access"><i class="fas fa-wheelchair"></i> Disabled Access</label>
                    </div>
                    <div class="feature-toggle">
                        <input type="checkbox" id="has-security-system" {{ ($block->has_security_system ?? false) ? 'checked' : '' }}>
                        <label for="has-security-system"><i class="fas fa-shield-alt"></i> Security System</label>
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- ADDITIONAL AREAS                               -->
                <!-- ============================================== -->
                <h3><i class="fas fa-map"></i> Additional Areas</h3>
                <div id="additional-areas-container">
                    {{-- Populated by JS --}}
                </div>
                <div class="add-btn-row">
                    <button type="button" class="btn-outline-primary" onclick="addAreaCard()">
                        <i class="fas fa-plus"></i> Add Area
                    </button>
                </div>

                <!-- ============================================== -->
                <!-- GATES                                          -->
                <!-- ============================================== -->
                <h3><i class="fas fa-door-open"></i> Gates / Entrances</h3>
                <div id="gates-container">
                    {{-- Populated by JS --}}
                </div>
                <div class="add-btn-row">
                    <button type="button" class="btn-outline-primary" onclick="addGateCard()">
                        <i class="fas fa-plus"></i> Add Gate
                    </button>
                </div>

                <!-- ============================================== -->
                <!-- CUSTOM AMENITIES                               -->
                <!-- ============================================== -->
                <h3><i class="fas fa-plus-circle" style="color:#10b981;"></i> Custom Amenities</h3>
                <div id="custom-amenities-container">
                    {{-- Populated by JS --}}
                </div>
                <div class="add-btn-row">
                    <button type="button" class="btn-outline-success" onclick="addCustomAmenityCard()">
                        <i class="fas fa-plus"></i> Add Custom Amenity
                    </button>
                </div>

                <!-- ============================================== -->
                <!-- ALLOCATED AMENITIES                            -->
                <!-- ============================================== -->
                <h3><i class="fas fa-concierge-bell"></i> Allocated Amenities</h3>
                <div id="allocated-amenities-container">
                    {{-- Populated by JS --}}
                </div>

                <!-- ============================================== -->
                <!-- ALLOCATED FACILITIES                           -->
                <!-- ============================================== -->
                <h3><i class="fas fa-building"></i> Allocated Facilities</h3>
                <div id="allocated-facilities-container">
                    {{-- Populated by JS --}}
                </div>

                <!-- ============================================== -->
                <!-- FORM ACTIONS                                   -->
                <!-- ============================================== -->
                <div class="form-actions">
                    <a href="{{ route('blocks.list') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <i class="fas fa-save"></i> Update Block
                    </button>
                </div>
            </form>
        </div>

        <!-- Toast -->
        <div id="toast" class="toast">
            <i class="fas fa-check-circle"></i>
            <span id="toast-message">Block updated successfully!</span>
        </div>
    </div>

<script>
    const API_BASE_URL = '{{ url('/') }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    const BLOCK_ID = '{{ $block->id ?? '' }}';

    // ------------------------------------------------------------
    // DATA FROM SERVER
    // ------------------------------------------------------------
    const BLOCK = @json($block);
    // const CAMPUS_FACILITIES = @json($block->building->facilities ?? []);
    const CAMPUS_FACILITIES = @json($campusFacilities ?? []);
    // Counters for new cards
    let areaCounter = 0;
    let gateCounter = 0;
    let customAmenityCounter = 0;

    // ------------------------------------------------------------
    // FACILITY LOOKUP (for showing full names)
    // ------------------------------------------------------------
    const facilityIcons = {
        parking: 'fa-parking',
        playground: 'fa-futbol',
        swimming_pool: 'fa-swimming-pool',
        clubhouse: 'fa-home',
        warehouse: 'fa-warehouse',
        store_room: 'fa-boxes',
        auditorium: 'fa-theater-masks',
        washrooms: 'fa-restroom'
    };
    const facilityLabels = {
        parking: 'Parking',
        playground: 'Playground',
        swimming_pool: 'Swimming Pool',
        clubhouse: 'Clubhouse',
        warehouse: 'Warehouse',
        store_room: 'Store Room',
        auditorium: 'Auditorium',
        washrooms: 'Washroom'
    };

    let facilityLookup = {};

    function buildFacilityLookup() {
        facilityLookup = {};
        if (!CAMPUS_FACILITIES || typeof CAMPUS_FACILITIES !== 'object') return;

        for (const type in CAMPUS_FACILITIES) {
            const typeData = CAMPUS_FACILITIES[type];
            if (!typeData || typeof typeData !== 'object') continue;

            const icon = facilityIcons[type] || 'fa-building';
            const label = facilityLabels[type] || type;

            // Clubhouse: single object
            if (type === 'clubhouse') {
                if (typeData.id) {
                    facilityLookup[typeData.id] = {
                        name: typeData.name || label,
                        type: type,
                        type_label: label,
                        icon: icon
                    };
                }
                continue;
            }

            // Washrooms: detailed_washrooms / entries / array
            if (type === 'washrooms') {
                let entries = [];
                if (Array.isArray(typeData.detailed_washrooms)) entries = typeData.detailed_washrooms;
                else if (Array.isArray(typeData.entries)) entries = typeData.entries;
                else if (Array.isArray(typeData)) entries = typeData;

                entries.forEach(e => {
                    if (e && e.id) {
                        facilityLookup[e.id] = {
                            name: e.name || label,
                            type: type,
                            type_label: label,
                            icon: icon
                        };
                    }
                });
                continue;
            }

            // Other facilities: nested keys
            ['basement', 'open', 'indoor', 'outdoor', 'entries'].forEach(nk => {
                if (Array.isArray(typeData[nk])) {
                    typeData[nk].forEach(e => {
                        if (e && e.id) {
                            facilityLookup[e.id] = {
                                name: e.name || label,
                                type: type,
                                type_label: label,
                                icon: icon
                            };
                        }
                    });
                }
            });
        }
    }

    // ------------------------------------------------------------
    // SAFE PARSE
    // ------------------------------------------------------------
    function safeParse(val) {
        if (!val) return [];
        if (typeof val === 'object') return val;
        try { return JSON.parse(val) || []; } catch (e) { return []; }
    }

    // ------------------------------------------------------------
    // ESCAPE
    // ------------------------------------------------------------
    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        const map = { '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#039;' };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    // ------------------------------------------------------------
    // ADDITIONAL AREAS
    // ------------------------------------------------------------
    function addAreaCard(data) {
        data = data || {};
        areaCounter++;
        const id = areaCounter;
        const container = document.getElementById('additional-areas-container');
        const card = document.createElement('div');
        card.className = 'dynamic-card';
        card.id = `area-card-${id}`;
        card.innerHTML = `
            <div class="card-badge-sm">Area #${id}</div>
            <div class="card-row" style="grid-template-columns:2fr 1fr 1fr;">
                <div class="form-group">
                    <label>Area Name</label>
                    <input type="text" class="form-control area-name" value="${escapeHtml(data.name || '')}" placeholder="e.g., Garden">
                </div>
                <div class="form-group">
                    <label>Area Unit</label>
                    <select class="form-control area-unit">
                        <option value="sq_ft" ${(data.unit === 'sq_ft' || !data.unit) ? 'selected' : ''}>Sq. Ft.</option>
                        <option value="sq_m" ${data.unit === 'sq_m' ? 'selected' : ''}>Sq. M.</option>
                        <option value="sq_yd" ${data.unit === 'sq_yd' ? 'selected' : ''}>Sq. Yd.</option>
                        <option value="gaj" ${data.unit === 'gaj' ? 'selected' : ''}>Gaj</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Area Value</label>
                    <input type="number" class="form-control area-value" value="${data.area || ''}" placeholder="Enter area" min="0" step="0.01">
                </div>
            </div>
            <div style="text-align:right;margin-top:0.5rem;">
                <button type="button" class="btn-outline-danger" onclick="document.getElementById('area-card-${id}').remove()">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
        `;
        container.appendChild(card);
    }

    // ------------------------------------------------------------
    // GATES
    // ------------------------------------------------------------
    function addGateCard(data) {
        data = data || {};
        gateCounter++;
        const id = gateCounter;
        const container = document.getElementById('gates-container');
        const card = document.createElement('div');
        card.className = 'dynamic-card';
        card.id = `gate-card-${id}`;
        card.innerHTML = `
            <div class="card-badge-sm">Gate #${id}</div>
            <div class="card-row" style="grid-template-columns:1fr 1fr;">
                <div class="form-group">
                    <label>Gate Name</label>
                    <input type="text" class="form-control gate-name" value="${escapeHtml(data.name || '')}" placeholder="e.g., Main Gate">
                </div>
                <div class="form-group">
                    <label>Gate Number</label>
                    <input type="text" class="form-control gate-number" value="${escapeHtml(data.number || '')}" placeholder="e.g., G-01">
                </div>
            </div>
            <div style="text-align:right;margin-top:0.5rem;">
                <button type="button" class="btn-outline-danger" onclick="document.getElementById('gate-card-${id}').remove()">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
        `;
        container.appendChild(card);
    }

    // ------------------------------------------------------------
    // CUSTOM AMENITIES
    // ------------------------------------------------------------
    function addCustomAmenityCard(data) {
        data = data || {};
        customAmenityCounter++;
        const id = customAmenityCounter;
        const container = document.getElementById('custom-amenities-container');
        const card = document.createElement('div');
        card.className = 'dynamic-card';
        card.id = `custom-card-${id}`;
        card.innerHTML = `
            <div class="card-badge-sm">Custom #${id}</div>
            <div class="card-row" style="grid-template-columns:2fr 1fr;">
                <div class="form-group">
                    <label>Amenity Name</label>
                    <input type="text" class="form-control custom-name" value="${escapeHtml(data.name || '')}" placeholder="e.g., Projector">
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" class="form-control custom-qty" value="${data.quantity || 1}" min="1">
                </div>
            </div>
            <div style="text-align:right;margin-top:0.5rem;">
                <button type="button" class="btn-outline-danger" onclick="document.getElementById('custom-card-${id}').remove()">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
        `;
        container.appendChild(card);
    }

    // ------------------------------------------------------------
    // ALLOCATED AMENITIES (from block's allocated_amenities)
    // ------------------------------------------------------------
    function renderAllocatedAmenities() {
        const container = document.getElementById('allocated-amenities-container');
        const alloc = safeParse(BLOCK.allocated_amenities);

        if (!alloc || Object.keys(alloc).length === 0) {
            container.innerHTML = '<div class="empty-text">No amenities allocated to this block.</div>';
            return;
        }

        let html = '';
        for (const key in alloc) {
            const val = alloc[key];
            const qty = (val && typeof val === 'object') ? (val.quantity || 1) : val;
            const displayName = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

            html += `
                <div class="amenity-row">
                    <input type="checkbox" class="alloc-amenity-check" data-key="${escapeHtml(key)}" checked>
                    <span class="amenity-name">${escapeHtml(displayName)}</span>
                    <input type="number" class="amenity-qty alloc-amenity-qty" data-key="${escapeHtml(key)}"
                           value="${qty}" min="1">
                </div>
            `;
        }
        container.innerHTML = html;
    }

    // ------------------------------------------------------------
    // ALLOCATED FACILITIES (resolve IDs to names)
    // ------------------------------------------------------------
    function renderAllocatedFacilities() {
        const container = document.getElementById('allocated-facilities-container');
        const allocIds = safeParse(BLOCK.allocated_facilities);

        if (!allocIds || allocIds.length === 0) {
            container.innerHTML = '<div class="empty-text">No facilities allocated to this block.</div>';
            return;
        }

        let html = '';
        allocIds.forEach(item => {
            const id = (item && typeof item === 'object') ? item.id : item;
            const info = facilityLookup[id];
            const name = info ? info.name : 'Unknown Facility';
            const typeLabel = info ? info.type_label : 'Unknown';
            const icon = info ? info.icon : 'fa-question-circle';

            html += `
                <div class="facility-row">
                    <input type="checkbox" class="alloc-facility-check" data-id="${escapeHtml(id)}" checked>
                    <div class="facility-icon"><i class="fas ${icon}"></i></div>
                    <div class="facility-info">
                        <div class="facility-name">${escapeHtml(name)}</div>
                        <div class="facility-type-badge">${escapeHtml(typeLabel)}</div>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
    }

    // ------------------------------------------------------------
    // COLLECT FORM DATA
    // ------------------------------------------------------------
    function collectFormData() {
        // Areas
        const areas = [];
        document.querySelectorAll('#additional-areas-container .dynamic-card').forEach(card => {
            const name = card.querySelector('.area-name')?.value.trim() || '';
            const unit = card.querySelector('.area-unit')?.value || 'sq_ft';
            const value = parseFloat(card.querySelector('.area-value')?.value) || 0;
            if (name || value) {
                areas.push({ name, unit, area: value });
            }
        });

        // Gates
        const gates = [];
        document.querySelectorAll('#gates-container .dynamic-card').forEach(card => {
            const name = card.querySelector('.gate-name')?.value.trim() || '';
            const number = card.querySelector('.gate-number')?.value.trim() || '';
            if (name || number) {
                gates.push({ name, number });
            }
        });

        // Custom amenities
        const customAmenities = [];
        document.querySelectorAll('#custom-amenities-container .dynamic-card').forEach(card => {
            const name = card.querySelector('.custom-name')?.value.trim() || '';
            const quantity = parseInt(card.querySelector('.custom-qty')?.value) || 1;
            if (name) {
                customAmenities.push({ name, quantity });
            }
        });

        // Allocated amenities
        const allocatedAmenities = {};
        document.querySelectorAll('.alloc-amenity-check').forEach(cb => {
            if (cb.checked) {
                const key = cb.getAttribute('data-key');
                const qtyInput = document.querySelector(`.alloc-amenity-qty[data-key="${key}"]`);
                const qty = parseInt(qtyInput?.value) || 1;
                allocatedAmenities[key] = qty;
            }
        });

        // Allocated facilities
        const allocatedFacilities = [];
        document.querySelectorAll('.alloc-facility-check').forEach(cb => {
            if (cb.checked) {
                allocatedFacilities.push(cb.getAttribute('data-id'));
            }
        });

        return {
            building_id: document.getElementById('building-id').value,
            name: document.getElementById('block-name').value.trim(),
            code: document.getElementById('block-code').value.trim(),
            description: document.getElementById('block-description').value.trim(),
            status: document.getElementById('block-status').value,
            total_floors: parseInt(document.getElementById('total-floors').value) || 0,
            total_rooms: parseInt(document.getElementById('total-rooms').value) || 0,
            total_capacity: parseInt(document.getElementById('total-capacity').value) || 0,
            total_area: parseFloat(document.getElementById('total-area').value) || 0,
            area_unit: document.getElementById('area-unit').value,
            has_lift: document.getElementById('has-lift').checked,
            has_fire_safety: document.getElementById('has-fire-safety').checked,
            has_disabled_access: document.getElementById('has-disabled-access').checked,
            has_security_system: document.getElementById('has-security-system').checked,
            additional_areas: areas,
            gates: gates,
            custom_amenities: customAmenities,
            allocated_amenities: allocatedAmenities,
            allocated_facilities: allocatedFacilities
        };
    }

    // ------------------------------------------------------------
    // SUBMIT
    // ------------------------------------------------------------
    async function submitForm(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submit-btn');
        const originalHTML = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Updating...';
        submitBtn.disabled = true;

        const data = collectFormData();

        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('_token', CSRF_TOKEN);
        formData.append('building_id', data.building_id);
        formData.append('name', data.name);
        formData.append('code', data.code);
        formData.append('description', data.description);
        formData.append('status', data.status);
        formData.append('total_floors', data.total_floors);
        formData.append('total_rooms', data.total_rooms);
        formData.append('total_capacity', data.total_capacity);
        formData.append('total_area', data.total_area);
        formData.append('area_unit', data.area_unit);
        formData.append('has_lift', data.has_lift ? 1 : 0);
        formData.append('has_fire_safety', data.has_fire_safety ? 1 : 0);
        formData.append('has_disabled_access', data.has_disabled_access ? 1 : 0);
        formData.append('has_security_system', data.has_security_system ? 1 : 0);
        formData.append('additional_areas', JSON.stringify(data.additional_areas));
        formData.append('gates', JSON.stringify(data.gates));
        formData.append('custom_amenities', JSON.stringify(data.custom_amenities));
        formData.append('allocated_amenities', JSON.stringify(data.allocated_amenities));
        formData.append('allocated_facilities', JSON.stringify(data.allocated_facilities));

        try {
            const response = await fetch(`${API_BASE_URL}/blocks/${BLOCK_ID}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                showToast(result.message || 'Block updated successfully!');
                setTimeout(() => {
                    window.location.href = '{{ route("blocks.list") }}';
                }, 1200);
            } else {
                showToast(result.message || 'Failed to update block', 'error');
                submitBtn.innerHTML = originalHTML;
                submitBtn.disabled = false;
            }
        } catch (error) {
            console.error('Error updating block:', error);
            showToast('Failed to update block', 'error');
            submitBtn.innerHTML = originalHTML;
            submitBtn.disabled = false;
        }
    }

    // ------------------------------------------------------------
    // TOAST
    // ------------------------------------------------------------
    function showToast(message, type) {
        type = type || 'success';
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');

        toastMessage.textContent = message;

        if (type === 'error') {
            toast.classList.add('error');
            toast.querySelector('i').className = 'fas fa-exclamation-circle';
        } else {
            toast.classList.remove('error');
            toast.querySelector('i').className = 'fas fa-check-circle';
        }

        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3000);
    }

    // ------------------------------------------------------------
    // INIT
    // ------------------------------------------------------------
    document.addEventListener('DOMContentLoaded', function () {
        if (!BLOCK_ID) {
            showToast('Invalid block ID', 'error');
            return;
        }

        buildFacilityLookup();

        // Preload dynamic cards from existing data
        const areas = safeParse(BLOCK.additional_areas);
        areas.forEach(a => addAreaCard(a));

        const gates = safeParse(BLOCK.gates);
        gates.forEach(g => addGateCard(g));

        const customs = safeParse(BLOCK.custom_amenities);
        customs.forEach(c => addCustomAmenityCard(c));

        renderAllocatedAmenities();
        renderAllocatedFacilities();

        // Bind checkbox toggle to qty inputs
        document.querySelectorAll('.alloc-amenity-check').forEach(cb => {
            cb.addEventListener('change', function () {
                const key = this.getAttribute('data-key');
                const qty = document.querySelector(`.alloc-amenity-qty[data-key="${key}"]`);
                if (qty) qty.disabled = !this.checked;
            });
        });

        // Submit
        document.getElementById('edit-block-form').addEventListener('submit', submitForm);
    });
</script>
</body>
</html>
@endsection