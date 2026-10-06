@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Floors</title>
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

        .header-content p { opacity: 0.9; font-size: 1rem; }

        .back-btn {
            background: var(--primary-gradient)!important;
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
        }

        .back-btn:hover { background: var(--primary-gradient)!important; color: white!important; transform: translateY(-2px); }

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

        .form-card h3 {
            color: var(--text-dark);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
            font-weight: 700;
        }
        .form-card h3 i { color: var(--primary-color); }

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

        textarea.form-control { resize: vertical; min-height: 80px; }

        /* Existing Floor Card */
        .existing-floor-card {
            background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
            border: 2px solid #6ee7b7;
            border-radius: 14px;
            padding: 1.25rem;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
        }

        .existing-floor-card:hover {
            border-color: #10b981;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);
        }

        .existing-floor-card .existing-badge {
            background: var(--success-gradient);
            color: white;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 0.5rem;
        }

        .existing-floor-card .floor-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .existing-floor-card .floor-name {
            font-weight: 700;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .existing-floor-card .floor-meta {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .existing-floor-card .floor-stats {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 0.5rem;
        }

        .existing-floor-card .stat-tag {
            background: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .existing-floors-section {
            margin-bottom: 1.5rem;
        }

        .existing-floors-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .existing-count-badge {
            background: var(--success-gradient);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .no-floors-message {
            text-align: center;
            padding: 1.5rem;
            color: var(--text-muted);
            background: #f8fafc;
            border-radius: 10px;
            border: 1px dashed var(--border-color);
        }

        /* Floor Card (for adding new) */
        .floor-card {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 16px;
            padding: 1.75rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .floor-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.1);
        }

        .floor-card .card-badge {
            position: absolute;
            top: -14px;
            left: 20px;
            background: var(--primary-gradient);
            color: white;
            padding: 5px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .section-divider {
            border-top: 2px solid var(--border-color);
            margin: 1.5rem 0;
            padding-top: 1.25rem;
        }

        .section-divider h4 {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-divider h4 i { color: var(--primary-color); }

        .dynamic-card {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 14px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .dynamic-card:hover { border-color: var(--primary-color); box-shadow: 0 4px 15px rgba(67, 97, 238, 0.1); }

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

        .dynamic-card .card-row { display: grid; gap: 0.75rem; }
        .dynamic-card .form-group { margin-bottom: 0; }
        .dynamic-card .form-group label { font-size: 0.85rem; margin-bottom: 0.3rem; }
        .dynamic-card .form-group input,
        .dynamic-card .form-group select { padding: 0.6rem 0.9rem; font-size: 0.9rem; }

        .custom-amenity-card {
            background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
            border: 2px dashed #6ee7b7;
            border-radius: 14px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .custom-amenity-card:hover { border-color: #10b981; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.1); }

        .custom-amenity-card .card-badge-sm {
            position: absolute;
            top: -12px;
            left: 16px;
            background: var(--success-gradient);
            color: white;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 0.75rem;
        }

        .amenity-group {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            transition: all 0.3s;
        }

        .amenity-group.has-checked { border-color: var(--primary-color); background: #f0f4ff; }

        .amenity-group h5 {
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 700;
        }
        .amenity-group h5 i { color: var(--primary-color); font-size: 0.85rem; }

        .checkbox-group { display: flex; flex-direction: column; gap: 0.4rem; }

        .checkbox-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.4rem;
        }

        .checkbox-item .checkbox-label-wrap { display: flex; align-items: center; gap: 0.4rem; flex: 1; }

        .checkbox-item input[type="checkbox"] {
            width: 16px; height: 16px; cursor: pointer; accent-color: var(--primary-color); flex-shrink: 0;
        }

        .checkbox-item label { font-size: 0.78rem; color: #555; cursor: pointer; font-weight: 500; }

        .amenity-count-input {
            width: 60px; padding: 4px 6px; border: 2px solid var(--border-color); border-radius: 6px;
            font-size: 0.75rem; text-align: center; transition: all 0.3s; display: none;
        }
        .amenity-count-input.show { display: block; }
        .amenity-count-input:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(67,97,238,0.1); }

        .amenity-count-label { font-size: 0.65rem; color: var(--text-muted); display: none; white-space: nowrap; }
        .amenity-count-label.show { display: inline; }

        .btn {
            padding: 0.75rem 1.5rem; border: none; border-radius: 12px; font-size: 1rem;
            font-weight: 600; cursor: pointer; transition: all 0.3s;
            display: inline-flex; align-items: center; gap: 8px; text-decoration: none;
        }

        .btn-primary { background: var(--primary-gradient); color: white; box-shadow: 0 4px 15px rgba(67,97,238,0.3); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(67,97,238,0.4); }

        .btn-secondary { background: #f1f5f9; color: var(--text-dark); border: 2px solid var(--border-color); }
        .btn-secondary:hover { background: #e2e8f0; transform: translateY(-2px); }

        .btn-outline-primary {
            background: white; color: var(--primary-color); border: 2px solid var(--primary-color);
            padding: 0.5rem 1rem; border-radius: 10px; font-weight: 600; font-size: 0.85rem;
            cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-outline-primary:hover { background: var(--primary-gradient); color: white; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(67,97,238,0.3); }

        .btn-outline-success {
            background: white; color: #10b981; border: 2px solid #10b981;
            padding: 0.5rem 1rem; border-radius: 10px; font-weight: 600; font-size: 0.85rem;
            cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-outline-success:hover { background: var(--success-gradient); color: white; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(16,185,129,0.3); }

        .btn-outline-danger {
            background: white; color: #dc3545; border: 2px solid #dc3545;
            padding: 0.5rem 1rem; border-radius: 10px; font-weight: 600; font-size: 0.85rem;
            cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-outline-danger:hover { background: var(--danger-gradient); color: white; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(239,68,68,0.3); }

        .add-btn-row { display: flex; justify-content: flex-end; margin-bottom: 1rem; }

        .new-floors-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-top: 1rem;
            border-top: 2px solid var(--border-color);
        }

        .form-actions {
            display: flex; gap: 1rem; margin-top: 1.5rem;
            padding-top: 1.5rem; border-top: 2px solid var(--border-color);
        }

        .toast {
            position: fixed; bottom: 20px; right: 20px;
            background: var(--success-gradient); color: white;
            padding: 16px 24px; border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            display: flex; align-items: center; gap: 10px; z-index: 1001;
            transform: translateY(100px); opacity: 0; transition: all 0.3s ease;
        }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.error { background: var(--danger-gradient); }

        .loading-spinner {
            display: inline-block; width: 20px; height: 20px;
            border: 3px solid rgba(255,255,255,0.3); border-top: 3px solid white;
            border-radius: 50%; animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        @media (max-width: 768px) {
            .header { flex-direction: column; gap: 1rem; text-align: center; }
            .form-card { padding: 1.5rem; }
            .form-actions { flex-direction: column; }
            .amenities-grid { grid-template-columns: repeat(2, 1fr); }
            .dynamic-card .card-row { grid-template-columns: 1fr; }
        }
        @media (max-width: 480px) { .amenities-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="header">
            <div class="header-content">
                <h1><i class="fas fa-plus-circle"></i> Add Floors</h1>
                <p>View existing floors and add new floors to a building block</p>
            </div>
            <a href="{{ route('floors.view') }}" class="back-btn">View Floors</a>
        </div>

        <div class="form-card">
            <h2><i class="fas fa-plus-circle"></i> Manage Floors</h2>
            
            <div class="form-group">
                <label for="buildingId" class="form-label">Building <span style="color: #dc3545;">*</span></label>
                <select id="buildingId" class="form-control" onchange="loadBlocks()">
                    <option value="">Select Building</option>
                    @foreach($buildings as $building)
                        <option value="{{ $building->id }}">{{ $building->name }} ({{ $building->code }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="blockSelect" class="form-label">Select Block <span style="color: #dc3545;">*</span></label>
                <select id="blockSelect" class="form-control" onchange="loadExistingFloors()">
                    <option value="">Select a building block</option>
                </select>
            </div>

            <!-- Existing Floors Section -->
            <div id="existingFloorsSection" style="display: none;">
                <div class="section-divider" style="margin-top: 0.5rem;">
                    <div class="existing-floors-header">
                        <h3 style="margin: 0;"><i class="fas fa-check-circle" style="color: #10b981;"></i> Existing Floors</h3>
                        <span class="existing-count-badge" id="existingFloorsCount">0 floors</span>
                    </div>
                </div>
                <div class="existing-floors-section" id="existingFloorsContainer">
                    <!-- Existing floors will be loaded here -->
                </div>
                <div id="noExistingFloors" class="no-floors-message" style="display: none;">
                    <i class="fas fa-info-circle"></i> No existing floors found for this block. Add new floors below.
                </div>
            </div>

            <!-- New Floors Section -->
            <div class="new-floors-header">
                <h3 style="margin: 0;"><i class="fas fa-plus-circle"></i> Add New Floors</h3>
            </div>
            <div id="floors-container">
                <!-- Default Floor #1 -->
                <div class="floor-card" id="floor-1" data-floor-id="1">
                    <div class="card-badge">New Floor #1</div>
                    <div class="card-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group"><label class="form-label">Floor Number/Name <span style="color:#dc3545;">*</span></label><input type="text" class="form-control floor-number" placeholder="e.g., Ground Floor, 1st Floor" maxlength="50"></div>
                        <div class="form-group"><label class="form-label">Number of Rooms</label><input type="number" class="form-control floor-rooms" placeholder="e.g., 10" min="0"></div>
                    </div>
                    <div class="form-group" style="margin-top:0.75rem;"><label class="form-label">Description</label><textarea class="form-control floor-description" rows="2" placeholder="Brief description of the floor..."></textarea></div>
                    
                    <div class="section-divider"><h4><i class="fas fa-vector-square"></i> Total Area of Floor</h4></div>
                    <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;"><div class="form-group"><label>Area Value</label><input type="number" class="form-control floor-area-value" placeholder="Enter area" min="0" step="0.01"></div><div class="form-group"><label>Area Unit</label><select class="form-control floor-area-unit"><option value="sq_ft">Sq. Ft.</option><option value="sq_m">Sq. M.</option><option value="sq_yd">Sq. Yd.</option><option value="gaj">Gaj</option></select></div></div>

                    <div class="section-divider"><h4><i class="fas fa-map"></i> Additional Areas</h4></div>
                    <div class="floor-areas-container" data-floor-id="1"><div class="dynamic-card area-card"><div class="card-badge-sm">Area #1</div><div class="card-row" style="grid-template-columns:2fr 1fr 1fr;"><div class="form-group"><label>Area Name</label><input type="text" class="form-control area-name" placeholder="e.g., Office"></div><div class="form-group"><label>Area Value</label><input type="number" class="form-control area-value" placeholder="Enter area" min="0" step="0.01"></div><div class="form-group"><label>Area Unit</label><select class="form-control area-unit"><option value="sq_ft">Sq. Ft.</option><option value="sq_m">Sq. M.</option><option value="sq_yd">Sq. Yd.</option><option value="gaj">Gaj</option></select></div></div></div></div>
                    <div class="add-btn-row"><button type="button" class="btn-outline-primary" onclick="addAreaToFloor(1)"><i class="fas fa-plus"></i> Add Area</button></div>

                    <div class="section-divider"><h4><i class="fas fa-door-open"></i> Entry/Exit Points</h4></div>
                    <div class="floor-gates-container" data-floor-id="1"><div class="dynamic-card gate-card"><div class="card-badge-sm">Entry #1</div><div class="card-row" style="grid-template-columns:1fr 1fr;"><div class="form-group"><label>Entry Name</label><input type="text" class="form-control gate-name" placeholder="e.g., Main Door"></div><div class="form-group"><label>Entry Number</label><input type="text" class="form-control gate-number" placeholder="e.g., E-01"></div></div></div></div>
                    <div class="add-btn-row"><button type="button" class="btn-outline-primary" onclick="addGateToFloor(1)"><i class="fas fa-plus"></i> Add Entry</button></div>

                    <div class="section-divider"><h4><i class="fas fa-concierge-bell"></i> Amenities</h4></div>
                    <div class="amenities-grid floor-amenities" data-floor-id="1" id="amenities-1"></div>

                    <div class="section-divider"><h4><i class="fas fa-plus-circle" style="color:#10b981;"></i> Custom Amenities</h4></div>
                    <div class="floor-custom-amenities-container" data-floor-id="1"><div class="custom-amenity-card"><div class="card-badge-sm">Custom #1</div><div class="card-row" style="grid-template-columns:2fr 1fr;"><div class="form-group"><label>Amenity Name</label><input type="text" class="form-control custom-amenity-name" placeholder="e.g., Projector"></div><div class="form-group"><label>Quantity</label><input type="number" class="form-control custom-amenity-qty" placeholder="Qty" min="1" value="1"></div></div></div></div>
                    <div class="add-btn-row"><button type="button" class="btn-outline-success" onclick="addCustomAmenityToFloor(1)"><i class="fas fa-plus"></i> Add Custom</button></div>
                </div>
            </div>

            <div class="add-btn-row" style="margin-top: 1rem;">
                <button type="button" class="btn-outline-primary" onclick="addFloor()" style="padding: 0.75rem 1.5rem; font-size: 0.9rem;">
                    <i class="fas fa-plus"></i> Add Another Floor
                </button>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                    <i class="fas fa-redo"></i> Clear All
                </button>
                <button type="button" class="btn btn-primary" onclick="saveAllFloors()" id="saveBtn">
                    <i class="fas fa-save"></i> Save New Floors
                </button>
            </div>
        </div>
    </div>

    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message">Floors saved successfully!</span>
    </div>

    <script>
        const API_BASE_URL = '{{ url('/') }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';
        
        let floorCounter = 1;
        let areaCounters = { 1: 1 };
        let gateCounters = { 1: 1 };
        let customAmenityCounters = { 1: 1 };

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('amenities-1').innerHTML = generateAmenitiesHTML(1);
        });

        async function loadBlocks() {
            const buildingId = document.getElementById('buildingId').value;
            const blockSelect = document.getElementById('blockSelect');
            document.getElementById('existingFloorsSection').style.display = 'none';
            
            if (!buildingId) { 
                blockSelect.innerHTML = '<option value="">Select a building block</option>'; 
                return; 
            }
            blockSelect.innerHTML = '<option value="">Loading blocks...</option>';
            try {
                const response = await fetch(`${API_BASE_URL}/rooms/blocks/${buildingId}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const result = await response.json();
                if (result.success) {
                    let options = '<option value="">Select a building block</option>';
                    result.data.forEach(block => { 
                        options += `<option value="${block.id}">${escapeHtml(block.name)}</option>`; 
                    });
                    blockSelect.innerHTML = options;
                } else { blockSelect.innerHTML = '<option value="">No blocks found</option>'; }
            } catch (error) { blockSelect.innerHTML = '<option value="">Error loading blocks</option>'; }
        }

        // Load existing floors for selected block
        async function loadExistingFloors() {
            const blockId = document.getElementById('blockSelect').value;
            const section = document.getElementById('existingFloorsSection');
            const container = document.getElementById('existingFloorsContainer');
            const noFloors = document.getElementById('noExistingFloors');
            const countBadge = document.getElementById('existingFloorsCount');
            
            if (!blockId) {
                section.style.display = 'none';
                return;
            }

            container.innerHTML = '<div style="text-align:center;padding:1rem;color:var(--text-muted);"><i class="fas fa-spinner fa-spin"></i> Loading existing floors...</div>';
            section.style.display = 'block';
            noFloors.style.display = 'none';

            try {
                const response = await fetch(`${API_BASE_URL}/floors/by-block/${blockId}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const result = await response.json();
                
                if (result.success && result.data && result.data.length > 0) {
                    const floors = result.data;
                    countBadge.textContent = `${floors.length} floor${floors.length > 1 ? 's' : ''}`;
                    
                    let html = '';
                    floors.forEach(floor => {
                        const areasCount = floor.additional_areas ? (Array.isArray(floor.additional_areas) ? floor.additional_areas.length : 0) : 0;
                        const gatesCount = floor.gates ? (Array.isArray(floor.gates) ? floor.gates.length : 0) : 0;
                        const amenitiesCount = floor.amenities ? Object.values(floor.amenities).filter(a => a && a.enabled).length : 0;
                        const rooms = floor.total_rooms || floor.rooms || 'N/A';
                        
                        html += `
                            <div class="existing-floor-card">
                                <span class="existing-badge"><i class="fas fa-check-circle"></i> Existing</span>
                                <div class="floor-info">
                                    <div>
                                        <div class="floor-name"><i class="fas fa-layer-group"></i> ${escapeHtml(floor.floor_number || floor.name)}</div>
                                        <div class="floor-meta">Rooms: ${rooms} | Area: ${floor.area_value || 'N/A'} ${formatUnit(floor.area_unit)}</div>
                                    </div>
                                </div>
                                <div class="floor-stats">
                                    ${areasCount > 0 ? `<span class="stat-tag"><i class="fas fa-map"></i> ${areasCount} Areas</span>` : ''}
                                    ${gatesCount > 0 ? `<span class="stat-tag"><i class="fas fa-door-open"></i> ${gatesCount} Entries</span>` : ''}
                                    ${amenitiesCount > 0 ? `<span class="stat-tag"><i class="fas fa-concierge-bell"></i> ${amenitiesCount} Amenities</span>` : ''}
                                    ${floor.description ? `<span class="stat-tag"><i class="fas fa-info-circle"></i> ${escapeHtml(floor.description).substring(0, 40)}</span>` : ''}
                                </div>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                    noFloors.style.display = 'none';
                } else {
                    container.innerHTML = '';
                    noFloors.style.display = 'block';
                    countBadge.textContent = '0 floors';
                }
            } catch (error) {
                container.innerHTML = '';
                noFloors.style.display = 'block';
                noFloors.innerHTML = '<i class="fas fa-exclamation-circle"></i> Failed to load existing floors';
                countBadge.textContent = '0 floors';
            }
        }

        function generateAmenitiesHTML(id) {
            return `
                <div class="amenity-group"><h5><i class="fas fa-snowflake"></i> Air Conditioning</h5><div class="checkbox-group">
                    <div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="has_ac" onchange="toggleAmenityCountFloor(this,${id},'has_ac')"><label>Air Conditioning</label></div><span class="amenity-count-label">Qty:</span><input type="number" class="amenity-count-input" data-key="has_ac" placeholder="Qty" min="1" value="1" onchange="updateFloorAmenityHighlight(this,${id})"></div>
                    <div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="is_central_ac" onchange="toggleAmenityCountFloor(this,${id},'is_central_ac')"><label>Central AC</label></div><span class="amenity-count-label">Qty:</span><input type="number" class="amenity-count-input" data-key="is_central_ac" placeholder="Qty" min="1" value="1" onchange="updateFloorAmenityHighlight(this,${id})"></div>
                </div></div>
                <div class="amenity-group"><h5><i class="fas fa-tint"></i> Water Facility</h5><div class="checkbox-group">
                    <div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="has_water_facility" onchange="toggleAmenityCountFloor(this,${id},'has_water_facility')"><label>Water Available</label></div><span class="amenity-count-label">Qty:</span><input type="number" class="amenity-count-input" data-key="has_water_facility" placeholder="Qty" min="1" value="1" onchange="updateFloorAmenityHighlight(this,${id})"></div>
                    <div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="has_water_cooler" onchange="toggleAmenityCountFloor(this,${id},'has_water_cooler')"><label>Water Cooler</label></div><span class="amenity-count-label">Qty:</span><input type="number" class="amenity-count-input" data-key="has_water_cooler" placeholder="Qty" min="1" value="1" onchange="updateFloorAmenityHighlight(this,${id})"></div>
                    <div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="has_drinking_water" onchange="toggleAmenityCountFloor(this,${id},'has_drinking_water')"><label>Drinking Water</label></div><span class="amenity-count-label">Qty:</span><input type="number" class="amenity-count-input" data-key="has_drinking_water" placeholder="Qty" min="1" value="1" onchange="updateFloorAmenityHighlight(this,${id})"></div>
                </div></div>
                <div class="amenity-group"><h5><i class="fas fa-fire-extinguisher"></i> Fire Safety</h5><div class="checkbox-group">
                    <div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="has_fire_extinguisher" onchange="toggleAmenityCountFloor(this,${id},'has_fire_extinguisher')"><label>Fire Extinguisher</label></div><span class="amenity-count-label">Qty:</span><input type="number" class="amenity-count-input" data-key="has_fire_extinguisher" placeholder="Qty" min="1" value="1" onchange="updateFloorAmenityHighlight(this,${id})"></div>
                    <div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="has_fire_alarm" onchange="toggleAmenityCountFloor(this,${id},'has_fire_alarm')"><label>Fire Alarm</label></div><span class="amenity-count-label">Qty:</span><input type="number" class="amenity-count-input" data-key="has_fire_alarm" placeholder="Qty" min="1" value="1" onchange="updateFloorAmenityHighlight(this,${id})"></div>
                    <div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="has_emergency_exit" onchange="toggleAmenityCountFloor(this,${id},'has_emergency_exit')"><label>Emergency Exit</label></div><span class="amenity-count-label">Qty:</span><input type="number" class="amenity-count-input" data-key="has_emergency_exit" placeholder="Qty" min="1" value="1" onchange="updateFloorAmenityHighlight(this,${id})"></div>
                </div></div>
                <div class="amenity-group"><h5><i class="fas fa-elevator"></i> Lifts</h5><div class="checkbox-group">
                    <div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="has_lift" onchange="toggleAmenityCountFloor(this,${id},'has_lift')"><label>Lift/Elevator</label></div><span class="amenity-count-label">Qty:</span><input type="number" class="amenity-count-input" data-key="has_lift" placeholder="Qty" min="1" value="1" onchange="updateFloorAmenityHighlight(this,${id})"></div>
                </div></div>
                <div class="amenity-group"><h5><i class="fas fa-toilet"></i> Washrooms</h5><div class="checkbox-group">
                    <div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="has_washroom" onchange="toggleAmenityCountFloor(this,${id},'has_washroom')"><label>Has Washroom</label></div><span class="amenity-count-label">Qty:</span><input type="number" class="amenity-count-input" data-key="has_washroom" placeholder="Qty" min="1" value="1" onchange="updateFloorAmenityHighlight(this,${id})"></div>
                </div></div>
                <div class="amenity-group"><h5><i class="fas fa-star"></i> Other Facilities</h5><div class="checkbox-group">
                    <div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="has_wifi" onchange="toggleAmenityCountFloor(this,${id},'has_wifi')"><label>Wi-Fi</label></div><span class="amenity-count-label">Qty:</span><input type="number" class="amenity-count-input" data-key="has_wifi" placeholder="Qty" min="1" value="1" onchange="updateFloorAmenityHighlight(this,${id})"></div>
                    <div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="has_projector" onchange="toggleAmenityCountFloor(this,${id},'has_projector')"><label>Projector Room</label></div><span class="amenity-count-label">Qty:</span><input type="number" class="amenity-count-input" data-key="has_projector" placeholder="Qty" min="1" value="1" onchange="updateFloorAmenityHighlight(this,${id})"></div>
                    <div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="has_conference" onchange="toggleAmenityCountFloor(this,${id},'has_conference')"><label>Conference Room</label></div><span class="amenity-count-label">Qty:</span><input type="number" class="amenity-count-input" data-key="has_conference" placeholder="Qty" min="1" value="1" onchange="updateFloorAmenityHighlight(this,${id})"></div>
                    <div class="checkbox-item"><div class="checkbox-label-wrap"><input type="checkbox" class="amenity-checkbox" data-key="has_library" onchange="toggleAmenityCountFloor(this,${id},'has_library')"><label>Library</label></div><span class="amenity-count-label">Qty:</span><input type="number" class="amenity-count-input" data-key="has_library" placeholder="Qty" min="1" value="1" onchange="updateFloorAmenityHighlight(this,${id})"></div>
                </div></div>`;
        }

        function addFloor() {
            floorCounter++;
            const id = floorCounter;
            areaCounters[id] = 1; gateCounters[id] = 1; customAmenityCounters[id] = 1;
            const container = document.getElementById('floors-container');
            const html = `
                <div class="floor-card" id="floor-${id}" data-floor-id="${id}">
                    <div class="card-badge">New Floor #${id}</div>
                    <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;"><div class="form-group"><label class="form-label">Floor Number/Name <span style="color:#dc3545;">*</span></label><input type="text" class="form-control floor-number" placeholder="e.g., Ground Floor" maxlength="50"></div><div class="form-group"><label class="form-label">Number of Rooms</label><input type="number" class="form-control floor-rooms" placeholder="e.g., 10" min="0"></div></div>
                    <div class="form-group" style="margin-top:0.75rem;"><label class="form-label">Description</label><textarea class="form-control floor-description" rows="2" placeholder="Brief description..."></textarea></div>
                    <div class="section-divider"><h4><i class="fas fa-vector-square"></i> Total Area</h4></div>
                    <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;"><div class="form-group"><label>Area Value</label><input type="number" class="form-control floor-area-value" placeholder="Enter area" min="0" step="0.01"></div><div class="form-group"><label>Area Unit</label><select class="form-control floor-area-unit"><option value="sq_ft">Sq. Ft.</option><option value="sq_m">Sq. M.</option><option value="sq_yd">Sq. Yd.</option><option value="gaj">Gaj</option></select></div></div>
                    <div class="section-divider"><h4><i class="fas fa-map"></i> Additional Areas</h4></div>
                    <div class="floor-areas-container" data-floor-id="${id}"><div class="dynamic-card area-card"><div class="card-badge-sm">Area #1</div><div class="card-row" style="grid-template-columns:2fr 1fr 1fr;"><div class="form-group"><label>Area Name</label><input type="text" class="form-control area-name" placeholder="e.g., Office"></div><div class="form-group"><label>Area Value</label><input type="number" class="form-control area-value" placeholder="Enter area" min="0" step="0.01"></div><div class="form-group"><label>Area Unit</label><select class="form-control area-unit"><option value="sq_ft">Sq. Ft.</option><option value="sq_m">Sq. M.</option><option value="sq_yd">Sq. Yd.</option><option value="gaj">Gaj</option></select></div></div></div></div>
                    <div class="add-btn-row"><button type="button" class="btn-outline-primary" onclick="addAreaToFloor(${id})"><i class="fas fa-plus"></i> Add Area</button></div>
                    <div class="section-divider"><h4><i class="fas fa-door-open"></i> Entry/Exit</h4></div>
                    <div class="floor-gates-container" data-floor-id="${id}"><div class="dynamic-card gate-card"><div class="card-badge-sm">Entry #1</div><div class="card-row" style="grid-template-columns:1fr 1fr;"><div class="form-group"><label>Entry Name</label><input type="text" class="form-control gate-name" placeholder="e.g., Main Door"></div><div class="form-group"><label>Entry Number</label><input type="text" class="form-control gate-number" placeholder="e.g., E-01"></div></div></div></div>
                    <div class="add-btn-row"><button type="button" class="btn-outline-primary" onclick="addGateToFloor(${id})"><i class="fas fa-plus"></i> Add Entry</button></div>
                    <div class="section-divider"><h4><i class="fas fa-concierge-bell"></i> Amenities</h4></div>
                    <div class="amenities-grid floor-amenities" data-floor-id="${id}">${generateAmenitiesHTML(id)}</div>
                    <div class="section-divider"><h4><i class="fas fa-plus-circle" style="color:#10b981;"></i> Custom Amenities</h4></div>
                    <div class="floor-custom-amenities-container" data-floor-id="${id}"><div class="custom-amenity-card"><div class="card-badge-sm">Custom #1</div><div class="card-row" style="grid-template-columns:2fr 1fr;"><div class="form-group"><label>Amenity Name</label><input type="text" class="form-control custom-amenity-name" placeholder="e.g., Projector"></div><div class="form-group"><label>Quantity</label><input type="number" class="form-control custom-amenity-qty" placeholder="Qty" min="1" value="1"></div></div></div></div>
                    <div class="add-btn-row"><button type="button" class="btn-outline-success" onclick="addCustomAmenityToFloor(${id})"><i class="fas fa-plus"></i> Add Custom</button></div>
                    <div style="text-align:right;margin-top:1rem;"><button type="button" class="btn-outline-danger" onclick="removeFloor(${id})"><i class="fas fa-trash"></i> Remove</button></div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
            document.getElementById(`floor-${id}`).scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function removeFloor(id) {
            if (document.querySelectorAll('.floor-card').length <= 1) { showToast('At least one new floor row is required', 'error'); return; }
            const card = document.getElementById(`floor-${id}`);
            if (card) { card.style.opacity = '0'; card.style.transform = 'scale(0.95)'; setTimeout(() => card.remove(), 300); }
        }

        function addAreaToFloor(floorId) {
            if (!areaCounters[floorId]) areaCounters[floorId] = 1;
            areaCounters[floorId]++; const c = areaCounters[floorId];
            const container = document.querySelector(`.floor-areas-container[data-floor-id="${floorId}"]`);
            const card = document.createElement('div'); card.className = 'dynamic-card area-card';
            card.innerHTML = `<div class="card-badge-sm">Area #${c}</div><div class="card-row" style="grid-template-columns:2fr 1fr 1fr;"><div class="form-group"><label>Area Name</label><input type="text" class="form-control area-name" placeholder="e.g., Office"></div><div class="form-group"><label>Area Value</label><input type="number" class="form-control area-value" placeholder="Enter area" min="0" step="0.01"></div><div class="form-group"><label>Area Unit</label><select class="form-control area-unit"><option value="sq_ft">Sq. Ft.</option><option value="sq_m">Sq. M.</option><option value="sq_yd">Sq. Yd.</option><option value="gaj">Gaj</option></select></div></div><div style="text-align:right;margin-top:0.5rem;"><button type="button" class="btn-outline-danger" onclick="this.closest('.dynamic-card').remove()"><i class="fas fa-trash"></i></button></div>`;
            container.appendChild(card);
        }

        function addGateToFloor(floorId) {
            if (!gateCounters[floorId]) gateCounters[floorId] = 1;
            gateCounters[floorId]++; const c = gateCounters[floorId];
            const container = document.querySelector(`.floor-gates-container[data-floor-id="${floorId}"]`);
            const card = document.createElement('div'); card.className = 'dynamic-card gate-card';
            card.innerHTML = `<div class="card-badge-sm">Entry #${c}</div><div class="card-row" style="grid-template-columns:1fr 1fr;"><div class="form-group"><label>Entry Name</label><input type="text" class="form-control gate-name" placeholder="e.g., Main Door"></div><div class="form-group"><label>Entry Number</label><input type="text" class="form-control gate-number" placeholder="e.g., E-01"></div></div><div style="text-align:right;margin-top:0.5rem;"><button type="button" class="btn-outline-danger" onclick="this.closest('.dynamic-card').remove()"><i class="fas fa-trash"></i></button></div>`;
            container.appendChild(card);
        }

        function addCustomAmenityToFloor(floorId) {
            if (!customAmenityCounters[floorId]) customAmenityCounters[floorId] = 1;
            customAmenityCounters[floorId]++; const c = customAmenityCounters[floorId];
            const container = document.querySelector(`.floor-custom-amenities-container[data-floor-id="${floorId}"]`);
            const card = document.createElement('div'); card.className = 'custom-amenity-card';
            card.innerHTML = `<div class="card-badge-sm">Custom #${c}</div><div class="card-row" style="grid-template-columns:2fr 1fr;"><div class="form-group"><label>Amenity Name</label><input type="text" class="form-control custom-amenity-name" placeholder="e.g., Projector"></div><div class="form-group"><label>Quantity</label><input type="number" class="form-control custom-amenity-qty" placeholder="Qty" min="1" value="1"></div></div><div style="text-align:right;margin-top:0.5rem;"><button type="button" class="btn-outline-danger" onclick="this.closest('.custom-amenity-card').remove()"><i class="fas fa-trash"></i></button></div>`;
            container.appendChild(card);
        }

        function toggleAmenityCountFloor(checkbox, floorId, key) {
            const floor = document.getElementById(`floor-${floorId}`);
            if (!floor) return;
            const countInput = floor.querySelector(`.amenity-count-input[data-key="${key}"]`);
            if (!countInput) return;
            const countLabel = countInput.previousElementSibling;
            if (checkbox.checked) { countInput.classList.add('show'); countLabel.classList.add('show'); countInput.focus(); }
            else { countInput.classList.remove('show'); countLabel.classList.remove('show'); countInput.value = 1; }
            updateFloorAmenityHighlight(checkbox, floorId);
        }

        function updateFloorAmenityHighlight(element, floorId) {
            const floor = document.getElementById(`floor-${floorId}`);
            if (!floor) return;
            floor.querySelectorAll('.amenity-group').forEach(group => {
                const hasChecked = Array.from(group.querySelectorAll('.amenity-checkbox')).some(cb => cb.checked);
                if (hasChecked) group.classList.add('has-checked'); else group.classList.remove('has-checked');
            });
        }

        function getFloorData(floorId) {
            const floor = document.getElementById(`floor-${floorId}`);
            if (!floor) return null;
            const name = floor.querySelector('.floor-number')?.value?.trim() || '';
            if (!name) return null;

            const areas = []; floor.querySelectorAll('.area-card').forEach(card => {
                const n = card.querySelector('.area-name')?.value?.trim() || '';
                const v = card.querySelector('.area-value')?.value || '';
                const u = card.querySelector('.area-unit')?.value || '';
                if (n) areas.push({ name: n, area: v || null, unit: u });
            });

            const gates = []; floor.querySelectorAll('.gate-card').forEach(card => {
                const n = card.querySelector('.gate-name')?.value?.trim() || '';
                const num = card.querySelector('.gate-number')?.value?.trim() || '';
                if (n || num) gates.push({ name: n, number: num });
            });

            const amenityKeys = ['has_ac','is_central_ac','has_water_facility','has_water_cooler','has_drinking_water','has_fire_extinguisher','has_fire_alarm','has_emergency_exit','has_lift','has_washroom','has_wifi','has_projector','has_conference','has_library'];
            const amenities = {};
            amenityKeys.forEach(key => {
                const cb = floor.querySelector(`.amenity-checkbox[data-key="${key}"]`);
                const ci = floor.querySelector(`.amenity-count-input[data-key="${key}"]`);
                amenities[key] = { enabled: cb?.checked || false, count: cb?.checked ? (parseInt(ci?.value) || 1) : 0 };
            });

            const customAmenities = []; floor.querySelectorAll('.custom-amenity-card').forEach(card => {
                const n = card.querySelector('.custom-amenity-name')?.value?.trim() || '';
                const q = parseInt(card.querySelector('.custom-amenity-qty')?.value) || 1;
                if (n) customAmenities.push({ name: n, quantity: q });
            });

            return { floor_number: name, rooms: floor.querySelector('.floor-rooms')?.value || '', description: floor.querySelector('.floor-description')?.value?.trim() || '', area_value: floor.querySelector('.floor-area-value')?.value || '', area_unit: floor.querySelector('.floor-area-unit')?.value || 'sq_ft', additional_areas: areas, gates: gates, amenities: amenities, custom_amenities: customAmenities };
        }

        async function saveAllFloors() {
            const buildingId = document.getElementById('buildingId').value;
            const blockId = document.getElementById('blockSelect').value;
            if (!buildingId) { showToast('Please select a building', 'error'); return; }
            if (!blockId) { showToast('Please select a block', 'error'); return; }

            const floors = [];
            document.querySelectorAll('.floor-card').forEach(card => {
                const data = getFloorData(card.getAttribute('data-floor-id'));
                if (data && data.floor_number) floors.push(data);
            });
            if (floors.length === 0) { showToast('Please fill at least one floor name', 'error'); return; }

            const saveBtn = document.getElementById('saveBtn');
            const originalHTML = saveBtn.innerHTML;
            saveBtn.innerHTML = '<span class="loading-spinner"></span> Saving...';
            saveBtn.disabled = true;

            const formData = new FormData();
            formData.append('building_id', buildingId);
            formData.append('block_id', blockId);
            formData.append('floors', JSON.stringify(floors));
            formData.append('_token', CSRF_TOKEN);

            try {
                const response = await fetch(`${API_BASE_URL}/floors/bulk`, {
                    method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const result = await response.json();
                if (result.success) { 
                    showToast(result.message || 'Floors saved!'); 
                    loadExistingFloors(); // Refresh existing floors
                }
                else { showToast(result.message || 'Failed', 'error'); }
            } catch (error) { showToast('An error occurred', 'error'); }
            finally { saveBtn.innerHTML = originalHTML; saveBtn.disabled = false; }
        }

        function resetForm() { window.location.reload(); }

        function formatUnit(unit) {
            const units = { 'sq_ft': 'Sq. Ft.', 'sq_m': 'Sq. M.', 'sq_yd': 'Sq. Yd.', 'gaj': 'Gaj', 'marla': 'Marla', 'kanal': 'Kanal', 'acre': 'Acre' };
            return units[unit] || unit || '';
        }

        function escapeHtml(text) {
            if (!text) return '';
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return String(text).replace(/[&<>"']/g, m => map[m]);
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            document.getElementById('toast-message').textContent = message;
            if (type === 'error') { toast.classList.add('error'); toast.querySelector('i').className = 'fas fa-exclamation-circle'; }
            else { toast.classList.remove('error'); toast.querySelector('i').className = 'fas fa-check-circle'; }
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }
    </script>
</body>
</html>
@endsection