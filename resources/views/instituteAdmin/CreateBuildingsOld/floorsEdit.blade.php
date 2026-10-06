@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Floor</title>
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
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
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

        .error-container { 
            text-align: center; 
            padding: 4rem 2rem; 
            background: white;
            border-radius: 16px;
            border: 2px solid var(--border-color);
        }
        .error-container i { 
            font-size: 4rem; 
            color: #ef4444; 
            margin-bottom: 1rem; 
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
        .form-card h2 i { 
            color: var(--primary-color); 
        }

        .form-group { 
            margin-bottom: 1.5rem; 
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

        @media (max-width: 768px) {
            .header { 
                flex-direction: column; 
                gap: 1rem; 
                text-align: center; 
            }
            .form-card { 
                padding: 1.5rem; 
            }
            .form-actions { 
                flex-direction: column; 
            }
            .amenities-grid { 
                grid-template-columns: 1fr; 
            }
            .toast {
                left: 20px;
                right: 20px;
                min-width: auto;
            }
            .add-item-container {
                flex-direction: column;
            }
            .add-item-container input {
                width: 100%;
            }
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

        <div id="main-content">
            <div id="loading-state" class="loading-container">
                <div class="spinner"></div>
                <h3 style="color: var(--text-dark);">Loading Floor Data...</h3>
                <p style="color: var(--text-muted);">Please wait while we fetch the floor details.</p>
            </div>

            <div id="error-state" class="error-container" style="display: none;">
                <i class="fas fa-exclamation-circle"></i>
                <h3 style="color: var(--text-dark);">Failed to Load Floor</h3>
                <p id="error-message" style="color: var(--text-muted);"></p>
                <a href="{{ route('floors.list') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    <i class="fas fa-arrow-left"></i> Back to Floors
                </a>
            </div>

            <div id="edit-form-container" style="display: none;">
                <div class="form-card">
                    <h2><i class="fas fa-edit"></i> Edit Floor Details</h2>
                    
                    <!-- Floor Info Summary -->
                    <div class="floor-info-box" id="floor-info-box">
                        <div class="info-item">
                            <span class="label">Floor ID</span>
                            <span class="value" id="floor-id-display">-</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Status</span>
                            <span class="value" id="floor-status-display">-</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Created</span>
                            <span class="value" id="floor-created-display">-</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Building</span>
                            <span class="value" id="floor-building-display">-</span>
                        </div>
                    </div>

                    <form id="edit-floor-form">
                        <div class="form-group">
                            <label for="block-select" class="form-label">Block <span style="color: #dc3545;">*</span></label>
                            <select id="block-select" class="form-control" required>
                                <option value="">Loading blocks...</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="floor-number" class="form-label">Floor Number/Name <span style="color: #dc3545;">*</span></label>
                            <input type="text" id="floor-number" class="form-control" required placeholder="e.g., Floor 1, Ground Floor, Mezzanine" maxlength="50">
                        </div>

                        <div class="form-group">
                            <label for="floor-description" class="form-label">Description (Optional)</label>
                            <textarea id="floor-description" class="form-control" rows="3" placeholder="Enter a brief description of the floor..."></textarea>
                        </div>

                        <!-- Floor Amenities (from amenities column) -->
                        <div class="amenities-section">
                            <h3><i class="fas fa-concierge-bell"></i> Floor Amenities</h3>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">
                                <i class="fas fa-info-circle"></i> Check the amenities available on this floor
                            </p>
                            <div class="amenities-grid">
                                <!-- Air Conditioning Group -->
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

                                <!-- Water Facility Group -->
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

                                <!-- Fire Safety Group -->
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

                                <!-- Lifts Group -->
                                <div class="amenity-group">
                                    <h4><i class="fas fa-elevator"></i> Lifts</h4>
                                    <div class="checkbox-group">
                                        <div class="checkbox-item">
                                            <input type="checkbox" id="hasLift" value="1">
                                            <label for="hasLift">Lift/Elevator</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Washrooms Group -->
                                <div class="amenity-group">
                                    <h4><i class="fas fa-toilet"></i> Washrooms</h4>
                                    <div class="checkbox-group">
                                        <div class="checkbox-item">
                                            <input type="checkbox" id="hasWashroom" value="1">
                                            <label for="hasWashroom">Has Washroom</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Other Facilities Group -->
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

                        <!-- Allocated Amenities (from block) - Editable -->
                        <div class="allocated-section">
                            <h4><i class="fas fa-cubes"></i> Allocated Amenities (from Block)</h4>
                            <div id="allocated-amenities-container" class="tag-container">
                                <span class="empty-text">Loading allocated amenities...</span>
                            </div>
                            <div class="add-item-container">
                                <input type="text" id="new-allocated-amenity" placeholder="Add amenity (e.g., swimming_pool)" class="form-control" style="flex: 1;">
                                <button type="button" class="btn btn-success btn-sm" onclick="addAllocatedAmenity()">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            </div>
                        </div>

                        <!-- Allocated Facilities (from block) - Editable -->
                        <div class="allocated-section">
                            <h4><i class="fas fa-building"></i> Allocated Facilities (from Block)</h4>
                            <div id="allocated-facilities-container" class="tag-container">
                                <span class="empty-text">Loading allocated facilities...</span>
                            </div>
                            <div class="add-item-container">
                                <input type="text" id="new-allocated-facility" placeholder="Add facility (e.g., washroom_1)" class="form-control" style="flex: 1;">
                                <button type="button" class="btn btn-success btn-sm" onclick="addAllocatedFacility()">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            </div>
                        </div>

                        <!-- Custom Amenities - Editable -->
                        <div class="allocated-section">
                            <h4><i class="fas fa-plus-circle" style="color: #10b981;"></i> Custom Amenities</h4>
                            <div id="custom-amenities-container" class="tag-container">
                                <span class="empty-text">Loading custom amenities...</span>
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
        </div>
    </div>

    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message">Floor updated successfully!</span>
    </div>

    <script>
        const API_BASE_URL = '{{ url('/') }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';
        
        // Data stores
        let allocatedAmenities = {};
        let allocatedFacilities = [];
        let customAmenities = [];
        
        function getFloorIdFromUrl() {
            const path = window.location.pathname.replace(/\/$/, '');
            const parts = path.split('/');
            const editIndex = parts.indexOf('edit');
            if (editIndex > 0 && parts.length > editIndex + 1) {
                return parts[editIndex - 1];
            }
            return parts[parts.length - 2];
        }
        
        const FLOOR_ID = getFloorIdFromUrl();
        console.log('Floor ID from URL:', FLOOR_ID);

        document.addEventListener('DOMContentLoaded', function() {
            if (!FLOOR_ID || FLOOR_ID === 'edit' || isNaN(FLOOR_ID)) {
                showError('Invalid floor ID.');
                return;
            }
            
            loadBlocks();
            loadFloorData(FLOOR_ID);
            
            document.getElementById('edit-floor-form').addEventListener('submit', function(e) { 
                e.preventDefault(); 
                updateFloor(); 
            });
        });

        async function loadBlocks() {
            try {
                const response = await fetch(`${API_BASE_URL}/blocks?per_page=100`, { 
                    headers: { 
                        'Accept': 'application/json', 
                        'X-Requested-With': 'XMLHttpRequest' 
                    } 
                });
                const result = await response.json();
                console.log('Blocks response:', result);
                
                const select = document.getElementById('block-select');
                let blocks = [];
                
                if (result.success) {
                    if (result.data && result.data.data && Array.isArray(result.data.data)) {
                        blocks = result.data.data;
                    } else if (result.data && Array.isArray(result.data)) {
                        blocks = result.data;
                    } else if (Array.isArray(result)) {
                        blocks = result;
                    }
                }
                
                if (blocks.length > 0) {
                    select.innerHTML = '<option value="">Select Block</option>' + 
                        blocks.map(b => `<option value="${b.id}">${escapeHtml(b.name)}</option>`).join('');
                } else {
                    select.innerHTML = '<option value="">No blocks available</option>';
                }
            } catch (error) { 
                console.error('Error loading blocks:', error); 
                document.getElementById('block-select').innerHTML = '<option value="">Error loading blocks</option>';
            }
        }

        async function loadFloorData(id) {
            console.log('Loading floor data for ID:', id);
            
            try {
                let response = await fetch(`${API_BASE_URL}/floors/${id}`, { 
                    headers: { 
                        'Accept': 'application/json', 
                        'X-Requested-With': 'XMLHttpRequest' 
                    } 
                });
                
                console.log('Floor response status:', response.status);
                
                let result = await response.json();
                console.log('Floor API response:', result);
                
                let floorData = null;
                
                if (result.success && result.data) {
                    floorData = result.data;
                } else if (result.success && result.floor) {
                    floorData = result.floor;
                } else if (result.id) {
                    floorData = result;
                } else if (!result.success) {
                    console.log('Trying edit endpoint...');
                    response = await fetch(`${API_BASE_URL}/floors/${id}/edit`, { 
                        headers: { 
                            'Accept': 'application/json', 
                            'X-Requested-With': 'XMLHttpRequest' 
                        } 
                    });
                    result = await response.json();
                    console.log('Edit endpoint response:', result);
                    
                    if (result.success && result.data) {
                        floorData = result.data;
                    }
                }
                
                if (floorData) {
                    populateForm(floorData);
                    showEditForm();
                } else {
                    showError('Floor not found. The floor may have been deleted or the ID is invalid.');
                }
            } catch (error) { 
                console.error('Error loading floor:', error);
                showError('Failed to load floor data. Please try again.'); 
            }
        }

        function populateForm(floor) {
            console.log('Populating form with floor data:', floor);
            
            // Set basic fields
            document.getElementById('block-select').value = floor.block_id || '';
            document.getElementById('floor-number').value = floor.floor_number || '';
            document.getElementById('floor-description').value = floor.description || '';
            
            // Display floor info
            document.getElementById('floor-id-display').textContent = '#' + (floor.id || FLOOR_ID);
            document.getElementById('floor-status-display').textContent = floor.status || 'Active';
            document.getElementById('floor-status-display').style.color = 
                (floor.status === 'active' || floor.status === null) ? '#10b981' : '#ef4444';
            
            if (floor.created_at) {
                const date = new Date(floor.created_at);
                document.getElementById('floor-created-display').textContent = date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            } else {
                document.getElementById('floor-created-display').textContent = 'N/A';
            }
            
            document.getElementById('floor-building-display').textContent = floor.building?.name || 'N/A';
            
            // Parse amenities
            let amenities = floor.amenities;
            if (typeof amenities === 'string') {
                try {
                    amenities = JSON.parse(amenities);
                } catch (e) {
                    amenities = {};
                }
            }
            if (!amenities || typeof amenities !== 'object') {
                amenities = {};
            }
            
            console.log('Parsed amenities:', amenities);
            
            // Set checkbox values
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
                'hasProjector': 'has_projector',
                'hasConference': 'has_conference',
                'hasLibrary': 'has_library'
            };
            
            for (const [elementId, dbKey] of Object.entries(amenityMap)) {
                const checkbox = document.getElementById(elementId);
                if (checkbox) {
                    checkbox.checked = !!amenities[dbKey];
                }
            }

            // Initialize data stores
            allocatedAmenities = floor.allocated_amenities || {};
            allocatedFacilities = floor.allocated_facility_entries || [];
            customAmenities = floor.custom_amenities || [];

            // Render all editable sections
            renderAllocatedAmenities();
            renderAllocatedFacilities();
            renderCustomAmenities();
        }

        function renderAllocatedAmenities() {
            const container = document.getElementById('allocated-amenities-container');
            const keys = Object.keys(allocatedAmenities).filter(key => key !== 'facility_entries');
            
            if (keys.length > 0) {
                container.innerHTML = keys.map(key => {
                    const value = allocatedAmenities[key];
                    const displayName = ucwords(key.replace(/_/g, ' '));
                    const isEnabled = (value == 1 || value === true);
                    return `<span class="tag teal">
                        <i class="fas fa-${isEnabled ? 'check-circle' : 'circle'}"></i>
                        ${displayName}
                        <span class="remove-btn" onclick="toggleAllocatedAmenity('${key}')" title="Toggle amenity">
                            <i class="fas fa-${isEnabled ? 'toggle-on' : 'toggle-off'}"></i>
                        </span>
                        <span class="remove-btn" onclick="removeAllocatedAmenity('${key}')" title="Remove amenity">
                            <i class="fas fa-times"></i>
                        </span>
                    </span>`;
                }).join('');
            } else {
                container.innerHTML = '<span class="empty-text">No amenities allocated from block.</span>';
            }
        }

        function renderAllocatedFacilities() {
            const container = document.getElementById('allocated-facilities-container');
            
            if (allocatedFacilities.length > 0) {
                container.innerHTML = allocatedFacilities.map((facility, index) => {
                    const name = typeof facility === 'string' ? facility : (facility.name || facility.id || 'Facility');
                    return `<span class="tag orange">
                        <i class="fas fa-building"></i>
                        ${ucwords(name.replace(/_/g, ' '))}
                        <span class="remove-btn" onclick="removeAllocatedFacility(${index})" title="Remove facility">
                            <i class="fas fa-times"></i>
                        </span>
                    </span>`;
                }).join('');
            } else {
                container.innerHTML = '<span class="empty-text">No facilities allocated from block.</span>';
            }
        }

        function renderCustomAmenities() {
            const container = document.getElementById('custom-amenities-container');
            
            if (customAmenities.length > 0) {
                container.innerHTML = customAmenities.map((amenity, index) => {
                    if (amenity && amenity.name) {
                        const qty = amenity.quantity || 0;
                        return `<span class="tag pink">
                            <i class="fas fa-plus-circle"></i>
                            ${ucwords(amenity.name)}
                            ${qty > 0 ? `<span style="background: rgba(219, 39, 119, 0.15); padding: 1px 8px; border-radius: 12px; font-size: 0.7rem; margin-left: 4px;">×${qty}</span>` : ''}
                            <span class="remove-btn" onclick="removeCustomAmenity(${index})" title="Remove amenity">
                                <i class="fas fa-times"></i>
                            </span>
                        </span>`;
                    }
                    return '';
                }).filter(html => html).join('');
            } else {
                container.innerHTML = '<span class="empty-text">No custom amenities added.</span>';
            }
        }

        // Allocated Amenity Functions
        function addAllocatedAmenity() {
            const input = document.getElementById('new-allocated-amenity');
            const key = input.value.trim().toLowerCase().replace(/\s+/g, '_');
            
            if (!key) {
                showToast('Please enter an amenity name.', 'error');
                return;
            }
            
            if (allocatedAmenities[key] !== undefined) {
                showToast('This amenity already exists.', 'error');
                return;
            }
            
            allocatedAmenities[key] = 1;
            input.value = '';
            renderAllocatedAmenities();
            showToast('Amenity added successfully!');
        }

        function toggleAllocatedAmenity(key) {
            allocatedAmenities[key] = allocatedAmenities[key] == 1 ? 0 : 1;
            renderAllocatedAmenities();
        }

        function removeAllocatedAmenity(key) {
            if (confirm(`Remove "${key}" from allocated amenities?`)) {
                delete allocatedAmenities[key];
                renderAllocatedAmenities();
                showToast('Amenity removed successfully!');
            }
        }

        // Allocated Facility Functions
        function addAllocatedFacility() {
            const input = document.getElementById('new-allocated-facility');
            const name = input.value.trim();
            
            if (!name) {
                showToast('Please enter a facility name.', 'error');
                return;
            }
            
            // Check if already exists
            const exists = allocatedFacilities.some(f => {
                const fName = typeof f === 'string' ? f : (f.name || f.id);
                return fName.toLowerCase() === name.toLowerCase();
            });
            
            if (exists) {
                showToast('This facility already exists.', 'error');
                return;
            }
            
            allocatedFacilities.push(name);
            input.value = '';
            renderAllocatedFacilities();
            showToast('Facility added successfully!');
        }

        function removeAllocatedFacility(index) {
            const name = typeof allocatedFacilities[index] === 'string' ? allocatedFacilities[index] : 'facility';
            if (confirm(`Remove "${name}" from allocated facilities?`)) {
                allocatedFacilities.splice(index, 1);
                renderAllocatedFacilities();
                showToast('Facility removed successfully!');
            }
        }

        // Custom Amenity Functions
        function addCustomAmenity() {
            const nameInput = document.getElementById('new-custom-amenity-name');
            const qtyInput = document.getElementById('new-custom-amenity-qty');
            const name = nameInput.value.trim();
            const qty = parseInt(qtyInput.value) || 1;
            
            if (!name) {
                showToast('Please enter an amenity name.', 'error');
                return;
            }
            
            // Check if already exists
            const exists = customAmenities.some(a => a.name.toLowerCase() === name.toLowerCase());
            if (exists) {
                showToast('This custom amenity already exists.', 'error');
                return;
            }
            
            customAmenities.push({ name: name, quantity: qty });
            nameInput.value = '';
            qtyInput.value = '1';
            renderCustomAmenities();
            showToast('Custom amenity added successfully!');
        }

        function removeCustomAmenity(index) {
            const name = customAmenities[index]?.name || 'amenity';
            if (confirm(`Remove "${name}" from custom amenities?`)) {
                customAmenities.splice(index, 1);
                renderCustomAmenities();
                showToast('Custom amenity removed successfully!');
            }
        }

        function ucwords(str) {
            return str.replace(/\w\S*/g, function(txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        }

        function showEditForm() {
            document.getElementById('loading-state').style.display = 'none';
            document.getElementById('error-state').style.display = 'none';
            document.getElementById('edit-form-container').style.display = 'block';
        }

        function showError(message) {
            document.getElementById('loading-state').style.display = 'none';
            document.getElementById('error-state').style.display = 'block';
            document.getElementById('edit-form-container').style.display = 'none';
            document.getElementById('error-message').textContent = message;
        }

        async function updateFloor() {
            const submitBtn = document.getElementById('submit-btn');
            const originalHTML = submitBtn.innerHTML;
            
            // Validate form
            const blockId = document.getElementById('block-select').value;
            const floorNumber = document.getElementById('floor-number').value.trim();
            
            if (!blockId) {
                showToast('Please select a block.', 'error');
                return;
            }
            
            if (!floorNumber) {
                showToast('Please enter a floor number/name.', 'error');
                document.getElementById('floor-number').focus();
                return;
            }
            
            submitBtn.innerHTML = '<span class="loading-spinner-btn"></span> Updating...';
            submitBtn.disabled = true;

            // Build amenities object
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
                has_projector: document.getElementById('hasProjector').checked ? 1 : 0,
                has_conference: document.getElementById('hasConference').checked ? 1 : 0,
                has_library: document.getElementById('hasLibrary').checked ? 1 : 0
            };

            // Include facility_entries in allocated_amenities
            const allocatedAmenitiesWithFacilities = { ...allocatedAmenities };
            if (allocatedFacilities.length > 0) {
                allocatedAmenitiesWithFacilities.facility_entries = allocatedFacilities;
            }

            const formData = new FormData();
            formData.append('block_id', blockId);
            formData.append('floor_number', floorNumber);
            formData.append('description', document.getElementById('floor-description').value.trim() || '');
            formData.append('amenities', JSON.stringify(amenities));
            formData.append('allocated_amenities', JSON.stringify(allocatedAmenitiesWithFacilities));
            formData.append('allocated_facility_entries', JSON.stringify(allocatedFacilities));
            formData.append('custom_amenities', JSON.stringify(customAmenities));
            formData.append('_method', 'PUT');
            formData.append('_token', CSRF_TOKEN);

            try {
                const response = await fetch(`${API_BASE_URL}/floors/${FLOOR_ID}`, {
                    method: 'POST', 
                    body: formData, 
                    headers: { 
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                console.log('Update response:', result);
                
                if (result.success) {
                    showToast(result.message || 'Floor updated successfully!');
                    setTimeout(() => { 
                        window.location.href = '{{ route("floors.list") }}'; 
                    }, 1500);
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

        function escapeHtml(text) {
            if (!text) return '';
            const map = { 
                '&': '&amp;', 
                '<': '&lt;', 
                '>': '&gt;', 
                '"': '&quot;', 
                "'": '&#039;' 
            };
            return String(text).replace(/[&<>"']/g, m => map[m]);
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            
            if (!toast || !toastMessage) {
                alert(message);
                return;
            }
            
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
            toast._timeout = setTimeout(() => {
                toast.classList.remove('show');
            }, 4000);
        }
    </script>
</body>
</html>
@endsection