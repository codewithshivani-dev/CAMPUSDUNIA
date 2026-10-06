@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-gradient: linear-gradient(135deg, #10b981, #059669);
            --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
            --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
            --info-gradient: linear-gradient(135deg, #0ea5e9, #0284c7);
            --border-color: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }
        *{box-sizing:border-box}
        .header{background:var(--primary-gradient);color:white;padding:1.5rem 2rem;border-radius:16px;margin-bottom:2rem;box-shadow:0 15px 35px rgba(67,97,238,0.3);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
        .header-content h1{font-size:28px;font-weight:700;color:white;margin:0;display:flex;align-items:center;gap:12px}
        .header-content h1 i{background:rgba(255,255,255,0.2);padding:10px;border-radius:12px}
        .header-content p{opacity:0.9;font-size:1rem}
        .header .btn-secondary{background:rgba(255,255,255,0.2);color:white;border:2px solid rgba(255,255,255,0.3);padding:0.75rem 1.5rem;border-radius:12px;font-weight:600;cursor:pointer;transition:all 0.3s;display:inline-flex;align-items:center;gap:8px;text-decoration:none}
        .header .btn-secondary:hover{background:rgba(255,255,255,0.3);transform:translateY(-2px)}
        .form-card{background:white;border-radius:20px;padding:2.5rem;box-shadow:0 8px 25px rgba(0,0,0,0.05);border:2px solid var(--border-color);max-width:1400px;margin:0 auto}
        .form-card h2{color:var(--text-dark);margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:2px solid var(--border-color);font-weight:700;display:flex;align-items:center;gap:10px}
        .form-card h2 i{color:var(--primary-color)}
        .form-card h3{color:var(--text-dark);margin-bottom:1rem;display:flex;align-items:center;gap:10px;font-size:1.1rem;font-weight:700}
        .form-card h3 i{color:var(--primary-color)}
        .form-group{margin-bottom:1.5rem;}
        .form-label{display:block;margin-bottom:0.5rem;font-weight:600;color:var(--text-dark);font-size:0.9rem}
        .form-group label{display:block;margin-bottom:0.5rem;color:var(--text-dark);font-weight:600;font-size:0.95rem}
        .form-control,.form-group input,.form-group textarea,.form-group select{width:100%;padding:12px 16px;border:2px solid var(--border-color);border-radius:12px;font-size:1rem;transition:all 0.3s;font-family:inherit;background:#f8fafc}
        .form-control:focus,.form-group input:focus,.form-group textarea:focus,.form-group select:focus{outline:none;border-color:var(--primary-color);box-shadow:0 0 0 4px rgba(67,97,238,0.1);background:white}
        select.form-control,select{cursor:pointer;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%234361ee' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 16px center;padding-right:40px}
        textarea.form-control,textarea{resize:vertical;min-height:80px}
        .row{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1.5rem}
        .col-md-4{min-width:0}
        .alert-info{background:linear-gradient(135deg,#f0f9ff,#e0f2fe);color:#075985;border:1px solid #7dd3fc;border-radius:12px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:flex-start;gap:10px}
        .alert-info i{font-size:1.2rem;margin-top:2px;color:#0ea5e9}
        .section-divider{border-top:2px solid var(--border-color);margin:1.25rem 0;padding-top:1rem}
        .section-divider h3{color:var(--text-dark);margin-bottom:0.5rem;display:flex;align-items:center;gap:10px;font-size:1.1rem;font-weight:700}
        .section-divider h3 i{color:var(--primary-color)}
        .section-divider h4{font-size:0.9rem;font-weight:700;color:var(--text-dark);display:flex;align-items:center;gap:8px}
        .section-divider h4 i{color:var(--primary-color)}
        .dynamic-card{background:white;border:2px solid var(--border-color);border-radius:14px;padding:1.25rem;margin-bottom:1rem;transition:all 0.3s ease;position:relative}
        .dynamic-card:hover{border-color:var(--primary-color);box-shadow:0 4px 15px rgba(67,97,238,0.1)}
        .dynamic-card .card-badge,.dynamic-card .card-badge-sm{position:absolute;top:-12px;left:16px;background:var(--primary-gradient);color:white;padding:3px 12px;border-radius:20px;font-size:0.7rem;font-weight:700}
        .dynamic-card .card-row{display:grid;gap:0.75rem}
        .dynamic-card .form-group{margin-bottom:0}
        .dynamic-card .form-group label{font-size:0.85rem;margin-bottom:0.3rem}
        .dynamic-card .form-group input,.dynamic-card .form-group select{padding:0.6rem 0.9rem;font-size:0.9rem}
        .btn{padding:0.75rem 1.5rem;border:none;border-radius:12px;font-size:1rem;font-weight:600;cursor:pointer;transition:all 0.3s;display:inline-flex;align-items:center;gap:8px;text-decoration:none}
        .btn-primary{background:var(--primary-gradient);color:white;box-shadow:0 4px 15px rgba(67,97,238,0.3)}
        .btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(67,97,238,0.4)}
        .btn-secondary{background:#f1f5f9;color:var(--text-dark);border:2px solid var(--border-color)}
        .btn-secondary:hover{background:#e2e8f0;transform:translateY(-2px)}
        .btn-success{background:var(--success-gradient);color:white}
        .btn-success:hover{transform:translateY(-2px)}
        .btn-outline-primary{background:white;color:var(--primary-color);border:2px solid var(--primary-color);padding:0.5rem 1rem;border-radius:10px;font-weight:600;font-size:0.85rem;cursor:pointer;transition:all 0.3s;display:inline-flex;align-items:center;gap:6px}
        .btn-outline-primary:hover{background:var(--primary-gradient);color:white;transform:translateY(-2px)}
        .btn-outline-success{background:white;color:#10b981;border:2px solid #10b981;padding:0.5rem 1rem;border-radius:10px;font-weight:600;font-size:0.85rem;cursor:pointer;transition:all 0.3s;display:inline-flex;align-items:center;gap:6px}
        .btn-outline-success:hover{background:var(--success-gradient);color:white;transform:translateY(-2px)}
        .btn-outline-danger{background:white;color:#dc3545;border:2px solid #dc3545;padding:0.5rem 1rem;border-radius:10px;font-weight:600;font-size:0.85rem;cursor:pointer;transition:all 0.3s;display:inline-flex;align-items:center;gap:6px}
        .btn-outline-danger:hover{background:var(--danger-gradient);color:white;transform:translateY(-2px)}
        .btn-sm{padding:5px 10px;font-size:0.7rem;border-radius:6px}
        .add-btn-row{display:flex;justify-content:flex-end;margin-bottom:1rem}
        .form-actions{display:flex;gap:1rem;margin-top:1.5rem;padding-top:1.5rem;border-top:2px solid var(--border-color)}
        .hidden{display:none!important}
        .step-content{display:none}
        .step-content.active{display:block}
        .loading-spinner{display:inline-block;width:20px;height:20px;border:3px solid rgba(255,255,255,0.3);border-top:3px solid white;border-radius:50%;animation:spin 1s linear infinite}
        @keyframes spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}
        @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
        .toast{position:fixed;bottom:20px;right:20px;background:var(--success-gradient);color:white;padding:16px 24px;border-radius:12px;box-shadow:0 8px 25px rgba(0,0,0,0.15);display:flex;align-items:center;gap:10px;z-index:1001;transform:translateY(100px);opacity:0;transition:all 0.3s ease}
        .toast.show{transform:translateY(0);opacity:1}
        .toast.error{background:var(--danger-gradient)}
        .toast.info{background:var(--info-gradient)}
        .floor-amenity-badge{display:inline-block;background:#f0f4ff;border:1px solid rgba(67,97,238,0.2);border-radius:20px;padding:2px 12px;font-size:0.7rem;font-weight:600;color:var(--primary-color);margin:2px 4px 2px 0}
        .floor-amenity-badge i{margin-right:4px}
        .floor-facility-badge{display:inline-block;background:#fef3c7;border:1px solid #fde68a;border-radius:20px;padding:2px 12px;font-size:0.7rem;font-weight:600;color:#92400e;margin:2px 4px 2px 0}
        .floor-facility-badge i{margin-right:4px}
        .room-selector-card{background:#f8fafc;border:2px solid var(--border-color);border-radius:14px;padding:1.25rem;margin-bottom:1.5rem;transition:all 0.3s}
        .room-selector-card:hover{border-color:var(--primary-color)}
        .room-selector-card .selector-header{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:0.75rem}
        .room-selector-card .selector-header h4{margin:0;font-size:0.95rem;font-weight:700;color:var(--text-dark);display:flex;align-items:center;gap:8px}
        .room-selector-card .selector-header h4 i{color:var(--primary-color)}
        .selected-item-badge{display:inline-flex;align-items:center;gap:6px;background:var(--success-gradient);color:white;padding:4px 14px;border-radius:20px;font-size:0.7rem;font-weight:600}
        .selected-item-badge i{font-size:0.6rem}
        .empty-state-msg{text-align:center;padding:2rem;color:var(--text-muted);background:#f8fafc;border-radius:12px;border:1px dashed var(--border-color)}
        .empty-state-msg i{font-size:2rem;display:block;margin-bottom:0.5rem;color:var(--primary-color);opacity:0.5}
        .room-detail-panel{display:none;animation:fadeIn 0.3s ease}
        .room-detail-panel.active{display:block}
        .room-detail-card{background:white;border:2px solid var(--border-color);border-radius:16px;padding:1.5rem;margin-top:1rem}
        .room-detail-card .card-row{display:grid;gap:1rem;margin-bottom:1rem}
        .room-detail-card .form-group{margin-bottom:0}
        .room-detail-card .form-group label{font-size:0.85rem;font-weight:600;color:var(--text-dark);margin-bottom:0.3rem}
        .room-detail-card .form-control{padding:8px 12px;font-size:0.9rem}
        .room-type-selector-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:0.5rem;margin-top:0.5rem}
        .room-type-selector-grid .room-type-option{padding:0.5rem;border:2px solid var(--border-color);border-radius:8px;text-align:center;cursor:pointer;transition:all 0.3s;background:white;font-weight:500;color:var(--text-muted);font-size:0.8rem}
        .room-type-selector-grid .room-type-option:hover{border-color:var(--primary-color)}
        .room-type-selector-grid .room-type-option.selected{background:var(--primary-gradient);color:white;border-color:var(--primary-color)}
        .room-type-selector-grid .room-type-option .room-type-badge{display:block;margin-top:3px;font-size:0.55rem}
        .room-amenities-selection{background:#f8fafc;border:1px solid var(--border-color);border-radius:12px;padding:1rem;margin-top:1rem}
        .room-amenities-selection .amenities-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:0.5rem;margin-top:0.5rem}
        .room-amenities-selection .amenity-item{display:flex;align-items:center;gap:0.5rem;padding:0.5rem;background:white;border:1px solid var(--border-color);border-radius:8px;transition:all 0.2s}
        .room-amenities-selection .amenity-item:hover{border-color:var(--primary-color)}
        .room-amenities-selection .amenity-item.selected{border-color:var(--primary-color);background:#f0f4ff}
        .room-amenities-selection .amenity-item input[type="checkbox"]{width:16px;height:16px;accent-color:var(--primary-color);cursor:pointer;flex-shrink:0}
        .room-amenities-selection .amenity-item label{font-size:0.85rem;font-weight:500;color:var(--text-dark);cursor:pointer;flex:1}
        .room-amenities-selection .amenity-item .amenity-qty{width:50px;padding:2px 6px;border:1px solid var(--border-color);border-radius:4px;font-size:0.75rem;text-align:center}
        .room-amenities-selection .amenity-item .amenity-qty:focus{outline:none;border-color:var(--primary-color)}
        .room-amenities-selection .amenity-item .amenity-qty:disabled{opacity:0.5;background:#f1f5f9}
        .room-amenities-selection .amenity-category{margin-bottom:0.75rem}
        .room-amenities-selection .amenity-category-title{font-weight:600;font-size:0.8rem;color:var(--text-muted);margin-bottom:0.25rem;display:flex;align-items:center;gap:6px}
        .room-amenities-selection .amenity-category-title i{color:var(--primary-color)}
        .room-specs-tabs{display:flex;gap:8px;margin-bottom:1.5rem;flex-wrap:wrap;border-bottom:2px solid var(--border-color);padding-bottom:0.5rem}
        .room-specs-tabs .tab-btn{padding:8px 20px;border:2px solid var(--border-color);border-radius:8px;background:white;cursor:pointer;font-weight:600;font-size:0.85rem;color:var(--text-muted);transition:all 0.3s}
        .room-specs-tabs .tab-btn:hover{border-color:var(--primary-color);color:var(--primary-color)}
        .room-specs-tabs .tab-btn.active{background:var(--primary-gradient);color:white;border-color:var(--primary-color)}
        .room-specs-tab-content{display:none;animation:fadeIn 0.3s ease}
        .room-specs-tab-content.active{display:block}
        .spec-group-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.25rem}
        .spec-group-card{background:#f8fafc;padding:1.25rem;border-radius:14px;border:1px solid var(--border-color);transition:all 0.3s}
        .spec-group-card:hover{border-color:var(--primary-color);box-shadow:0 2px 10px rgba(67,97,238,0.08)}
        .spec-group-card h4{color:var(--text-dark);margin-bottom:0.75rem;font-size:0.9rem;display:flex;align-items:center;gap:8px;font-weight:700}
        .spec-group-card h4 i{color:var(--primary-color)}
        .spec-row{display:grid;grid-template-columns:1fr 1fr;gap:0.75rem}
        .spec-row .form-group{margin-bottom:0}
        .spec-row .form-group label{font-size:0.8rem;font-weight:500;color:var(--text-muted)}
        .spec-row .form-group input,.spec-row .form-group select{padding:8px 12px;font-size:0.85rem}
        .checkbox-group{display:flex;flex-direction:column;gap:0.6rem}
        .checkbox-item{display:flex;align-items:center;gap:0.5rem}
        .checkbox-item input[type="checkbox"]{width:18px;height:18px;cursor:pointer;accent-color:var(--primary-color);flex-shrink:0}
        .checkbox-item label{font-size:0.85rem;color:#555;cursor:pointer;font-weight:500}
        .balcony-card{background:#f0fdf4;border:2px solid #86efac;border-radius:12px;padding:1rem;margin-bottom:0.75rem;position:relative}
        .balcony-card .balcony-badge{position:absolute;top:-10px;left:12px;background:var(--success-gradient);color:white;padding:2px 12px;border-radius:12px;font-size:0.65rem;font-weight:600}
        .area-deduction-summary{background:#f8fafc;border:1px solid var(--border-color);border-radius:8px;padding:10px 14px;margin:8px 0;font-size:0.85rem}
        .area-deduction-summary .deduction-item{display:flex;justify-content:space-between;padding:3px 0;border-bottom:1px dashed #e2e8f0}
        .area-deduction-summary .deduction-item:last-child{border-bottom:none}
        .area-deduction-summary .total-row{font-weight:700;color:var(--text-dark);margin-top:5px;padding-top:5px;border-top:2px solid var(--border-color)}
        .area-deduction-summary .positive{color:#059669}
        .area-deduction-summary .negative{color:#dc2626}
        .facility-toggle{display:flex;align-items:center;gap:10px;margin-bottom:1rem}
        .facility-toggle label{font-weight:500;cursor:pointer}
        .facility-toggle input[type="checkbox"]{width:18px;height:18px;cursor:pointer;accent-color:var(--primary-color)}
        .existing-count-badge{background:var(--success-gradient);color:white;padding:4px 12px;border-radius:20px;font-size:0.75rem;font-weight:600}
        .existing-floor-card{background:linear-gradient(135deg,#f0fdf4,#ecfdf5);border:2px solid #6ee7b7;border-radius:14px;padding:1.25rem;margin-bottom:0.75rem}
        .existing-floor-card .existing-badge{background:var(--success-gradient);color:white;padding:3px 12px;border-radius:20px;font-size:0.7rem;font-weight:700;display:inline-block;margin-bottom:0.5rem}
        .existing-floor-card .floor-info{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem}
        .existing-floor-card .floor-name{font-weight:700;color:var(--text-dark);font-size:0.9rem}
        .existing-floor-card .floor-meta{font-size:0.75rem;color:var(--text-muted)}
        .no-floors-message{text-align:center;padding:1.5rem;color:var(--text-muted);background:#f8fafc;border-radius:10px;border:1px dashed var(--border-color)}
        @media(max-width:768px){.header{flex-direction:column;gap:1rem;text-align:center}.form-card{padding:1.5rem}.form-actions{flex-direction:column}.row{grid-template-columns:1fr}}
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <h1><i class="fas fa-door-open"></i> Room Management</h1>
                <p>Configure rooms for your building blocks and floors</p>
            </div>
            <div>
                <a href="{{ route('blocks.page') }}" class="btn btn-secondary">
                    <i class="fas fa-cubes"></i> Back to Blocks
                </a>
                <a href="{{ route('buildings.list') }}" class="btn btn-secondary">
                    <i class="fas fa-university"></i> View Campuses
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="form-card">
            <h2><i class="fas fa-door-open"></i> Room Management</h2>

            <!-- Selection Filters -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group" style="width:270px;">
                        <label for="roomBuildingId" class="form-label">Building <span style="color:#dc3545;">*</span></label>
                        <select id="roomBuildingId" class="form-control" onchange="loadBlocksForRooms()">
                            <option value="">Select Building</option>
                            @if(isset($buildings) && count($buildings) > 0)
                                @foreach($buildings as $building)
                                    <option value="{{ $building->id }}">{{ $building->name }} ({{ $building->code }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group" style="width:270px;">
                        <label for="roomBlockSelect" class="form-label">Select Block <span style="color:#dc3545;">*</span></label>
                        <select id="roomBlockSelect" class="form-control" onchange="loadFloorsForRooms()">
                            <option value="">Select a building block</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group" style="width:270px;">
                        <label for="roomFloorSelect" class="form-label">Select Floor <span style="color:#dc3545;">*</span></label>
                        <select id="roomFloorSelect" class="form-control" onchange="loadExistingRoomsAndFloorAmenities()">
                            <option value="">Select a floor</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Floor Amenities Display -->
            <div id="floorAmenitiesDisplay" style="display:none; background:#f8fafc; border:1px solid var(--border-color); border-radius:12px; padding:1rem; margin-bottom:1rem;">
                <h4 style="font-size:0.9rem; font-weight:700; color:var(--text-dark);"><i class="fas fa-concierge-bell" style="color:var(--primary-color);"></i> Floor Amenities & Facilities</h4>
                <div id="floorAmenitiesList" style="margin-top:8px; display:flex; flex-wrap:wrap; gap:4px;"></div>
            </div>

            <!-- Existing Rooms Section -->
            <div id="existingRoomsSection" style="display:none;">
                <div class="section-divider" style="margin-top:0.5rem;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <h3 style="margin:0;"><i class="fas fa-check-circle" style="color:#10b981;"></i> Existing Rooms</h3>
                        <span class="existing-count-badge" id="existingRoomsCount">0 rooms</span>
                    </div>
                </div>
                <div id="existingRoomsContainer"></div>
                <div id="noExistingRooms" class="no-floors-message" style="display:none;"><i class="fas fa-info-circle"></i> No existing rooms found.</div>
            </div>

            <!-- Room Selector Dropdown -->
            <div class="room-selector-card" id="roomSelectorCard" style="display:none;">
                <div class="selector-header">
                    <h4><i class="fas fa-list"></i> Select Room to Configure</h4>
                    <span class="existing-count-badge" id="roomCountBadge">0 rooms</span>
                </div>
                <div class="form-group">
                    <label for="roomSelectorDropdown" class="form-label">Choose a Room <span style="color:#dc3545;">*</span></label>
                    <select id="roomSelectorDropdown" class="form-control" onchange="onRoomSelectorChange()">
                        <option value="">-- Select a room --</option>
                    </select>
                </div>
                <div style="margin-top:0.75rem;display:flex;gap:10px;flex-wrap:wrap;">
                    <span class="selected-item-badge" id="selectedRoomBadge" style="display:none;">
                        <i class="fas fa-check-circle"></i> <span id="selectedRoomName">-</span>
                    </span>
                    <button type="button" class="btn-outline-primary btn-sm" onclick="addRoomFromDropdown()" id="addRoomFromDropdownBtn">
                        <i class="fas fa-plus"></i> Add New Room
                    </button>
                    <button type="button" class="btn-outline-danger btn-sm" onclick="removeSelectedRoom()" id="removeSelectedRoomBtn" style="display:none;">
                        <i class="fas fa-trash"></i> Remove Room
                    </button>
                </div>
            </div>

            <!-- Room Detail Panel -->
            <div id="roomDetailPanel" class="room-detail-panel">
                <div id="roomDetailContainer"></div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('buildings.page') }}'">
                    <i class="fas fa-arrow-left"></i> Back to Blocks
                </button>
                <button type="button" class="btn btn-success" onclick="saveAllRooms()" id="saveRoomsBtn">
                    <i class="fas fa-save"></i> Save All Rooms
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"><i class="fas fa-check-circle"></i><span id="toast-message"></span></div>

    <script>
    // ===== COMPLETE JAVASCRIPT FOR ROOM MANAGEMENT =====

    // Configuration
    const API_BASE_URL = '{{ url('/') }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';

    // Data stores
    let roomDataMap = {};
    let existingRoomIds = [];
    let selectedRoomId = null;
    let roomTypeOptions = [];
    let campusFacilityEntries = {};
    let campusAreaUnit = 'sq_ft';
    let currentFloorAmenities = { amenities: {}, facilities: [] };
    let campusAmenityTotals = {};
    let campusCustomAmenityTotals = {};

    // ===== UTILITY FUNCTIONS =====
    function generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0,
                v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    function escapeHtml(t) { 
        if(!t) return ''; 
        const m={'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}; 
        return String(t).replace(/[&<>"']/g,c=>m[c]); 
    }

    function showToast(msg,type='success') { 
        const t=document.getElementById('toast');
        document.getElementById('toast-message').textContent=msg;
        t.className='toast'+(type==='error'?' error':(type==='info'?' info':''));
        t.querySelector('i').className=type==='error'?'fas fa-exclamation-circle':(type==='info'?'fas fa-info-circle':'fas fa-check-circle');
        t.classList.add('show');
        setTimeout(()=>t.classList.remove('show'),3000);
    }

    function convertArea(value, fromUnit, toUnit) { 
        const conversion = {'sq_ft':1,'sq_m':10.7639,'sq_yd':9,'gaj':9,'marla':272.25,'kanal':5445,'acre':43560,'hectare':107639,'bigha':27000,'biswa':1350};
        if(!value||isNaN(value)) return 0; 
        return (parseFloat(value)*(conversion[fromUnit]||1))/(conversion[toUnit]||1); 
    }

    function getUnitDisplayName(unit) { 
        const n={'sq_ft':'Sq. Ft.','sq_m':'Sq. M.','sq_yd':'Sq. Yd.','gaj':'Gaj','marla':'Marla','kanal':'Kanal','acre':'Acre','hectare':'Hectare','bigha':'Bigha','biswa':'Biswa'}; 
        return n[unit]||unit; 
    }

    // ===== ROOM TYPE FUNCTIONS =====
    async function fetchRoomTypes() {
        try {
            const response = await fetch(`${API_BASE_URL}/room-types`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF_TOKEN }
            });
            const result = await response.json();
            if (result.success && Array.isArray(result.data)) {
                roomTypeOptions = result.data.map(item => ({ id: item.name, name: item.name }));
                roomTypeOptions.push({ id: 'other', name: 'Other' });
            } else {
                setFallbackRoomTypes();
            }
        } catch (e) {
            setFallbackRoomTypes();
        }
    }

    function setFallbackRoomTypes() {
        roomTypeOptions = [
            { id: 'classroom', name: 'Classroom' },
            { id: 'office', name: 'Office' },
            { id: 'conference_room', name: 'Conference Room' },
            { id: 'seminar_hall', name: 'Seminar Hall' },
            { id: 'computer_lab', name: 'Computer Lab' },
            { id: 'science_lab', name: 'Science Lab' },
            { id: 'library', name: 'Library' },
            { id: 'cafeteria', name: 'Cafeteria' },
            { id: 'auditorium', name: 'Auditorium' },
            { id: 'gym', name: 'Gym' },
            { id: 'hostel', name: 'Hostel Room' },
            { id: 'guest_room', name: 'Guest Room' },
            { id: 'staff_room', name: 'Staff Room' },
            { id: 'admin_office', name: 'Admin Office' },
            { id: 'store_room', name: 'Store Room' },
            { id: 'prayer_room', name: 'Prayer Room' },
            { id: 'waiting_room', name: 'Waiting Room' },
            { id: 'medical_room', name: 'Medical Room' },
            { id: 'daycare', name: 'Daycare' },
            { id: 'residential', name: 'Residential' },
            { id: 'commercial', name: 'Commercial' },
            { id: 'other', name: 'Other' }
        ];
    }

    // ===== LOAD FUNCTIONS =====
    async function loadBlocksForRooms() {
        const bi = document.getElementById('roomBuildingId').value;
        const bs = document.getElementById('roomBlockSelect');
        const fs = document.getElementById('roomFloorSelect');
        fs.innerHTML = '<option value="">Select a floor</option>';
        document.getElementById('existingRoomsSection').style.display = 'none';
        document.getElementById('floorAmenitiesDisplay').style.display = 'none';
        document.getElementById('roomSelectorCard').style.display = 'none';
        document.getElementById('roomDetailPanel').classList.remove('active');
        roomDataMap = {};
        existingRoomIds = [];
        
        if (!bi) { 
            bs.innerHTML = '<option value="">Select a building block</option>'; 
            return; 
        }
        
        bs.innerHTML = '<option value="">Loading...</option>';
        try {
            const r = await fetch(`${API_BASE_URL}/rooms/blocks/${bi}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const result = await r.json();
            if (result.success) {
                let o = '<option value="">Select a building block</option>';
                result.data.forEach(b => { o += `<option value="${b.id}">${escapeHtml(b.name)}</option>`; });
                bs.innerHTML = o;
            } else { 
                bs.innerHTML = '<option value="">No blocks found</option>'; 
            }
        } catch (e) { 
            bs.innerHTML = '<option value="">Error</option>'; 
        }
    }

    async function loadFloorsForRooms() {
        const bi = document.getElementById('roomBlockSelect').value;
        const fs = document.getElementById('roomFloorSelect');
        document.getElementById('existingRoomsSection').style.display = 'none';
        document.getElementById('floorAmenitiesDisplay').style.display = 'none';
        document.getElementById('roomSelectorCard').style.display = 'none';
        document.getElementById('roomDetailPanel').classList.remove('active');
        roomDataMap = {};
        existingRoomIds = [];
        
        if (!bi) { 
            fs.innerHTML = '<option value="">Select a floor</option>'; 
            return; 
        }
        
        fs.innerHTML = '<option value="">Loading...</option>';
        try {
            const r = await fetch(`${API_BASE_URL}/rooms/floors-by-block/${bi}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const result = await r.json();
            if (result.success) {
                let o = '<option value="">Select a floor</option>';
                result.data.forEach(f => { 
                    const displayName = f.floor_number || f.name || `Floor ${f.id}`;
                    o += `<option value="${f.id}">${escapeHtml(displayName)}</option>`;
                });
                fs.innerHTML = o;
            } else { 
                fs.innerHTML = '<option value="">No floors found</option>'; 
            }
        } catch (e) { 
            fs.innerHTML = '<option value="">Error</option>'; 
        }
    }

    async function loadExistingRoomsAndFloorAmenities() {
        const fi = document.getElementById('roomFloorSelect').value;
        const s = document.getElementById('existingRoomsSection');
        const c = document.getElementById('existingRoomsContainer');
        const nr = document.getElementById('noExistingRooms');
        const cb = document.getElementById('existingRoomsCount');
        const floorAmenitiesDisplay = document.getElementById('floorAmenitiesDisplay');
        const floorAmenitiesList = document.getElementById('floorAmenitiesList');
        const roomSelectorCard = document.getElementById('roomSelectorCard');
        const roomDetailPanel = document.getElementById('roomDetailPanel');

        roomDataMap = {};
        existingRoomIds = [];
        document.getElementById('roomDetailContainer').innerHTML = '';
        roomDetailPanel.classList.remove('active');
        roomSelectorCard.style.display = 'none';

        if (!fi) {
            s.style.display = 'none';
            floorAmenitiesDisplay.style.display = 'none';
            roomSelectorCard.style.display = 'none';
            roomDetailPanel.classList.remove('active');
            return;
        }

        try {
            // Load floor amenities
            const r = await fetch(`${API_BASE_URL}/floors/${fi}/details`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const result = await r.json();
            
            if (result.success && result.data) {
                const floorData = result.data;
                currentFloorAmenities = {
                    amenities: floorData.allocated_amenities || {},
                    facilities: floorData.allocated_facilities || []
                };
                
                // Display amenities
                let amenitiesHtml = '';
                const allocs = floorData.allocated_amenities || {};
                const allocFacilities = floorData.allocated_facilities || [];
                
                Object.keys(allocs).forEach(key => {
                    const qty = allocs[key] || 0;
                    const displayName = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    amenitiesHtml += `<span class="floor-amenity-badge"><i class="fas fa-concierge-bell"></i> ${displayName} (${qty})</span>`;
                });
                
                allocFacilities.forEach(entryId => {
                    let name = entryId;
                    let entryData = null;
                    for (const type of Object.keys(campusFacilityEntries)) {
                        const found = campusFacilityEntries[type]?.find(e => e.id === entryId);
                        if (found) { name = found.name; entryData = found; break; }
                    }
                    let extraInfo = '';
                    if (entryData && entryData.toilets !== undefined) {
                        extraInfo = ` 🚽${entryData.toilets} 🚹${entryData.urinals||0} 🚰${entryData.washbasins}`;
                    }
                    if (entryData && entryData.area) extraInfo += ` 📐${parseFloat(entryData.area).toFixed(1)} sqft`;
                    amenitiesHtml += `<span class="floor-facility-badge"><i class="fas fa-building"></i> ${escapeHtml(name)}${extraInfo}</span>`;
                });
                
                if (amenitiesHtml) {
                    floorAmenitiesList.innerHTML = amenitiesHtml;
                    floorAmenitiesDisplay.style.display = 'block';
                } else {
                    floorAmenitiesList.innerHTML = '<span style="color:var(--text-muted);font-size:0.85rem;">No amenities allocated to this floor.</span>';
                    floorAmenitiesDisplay.style.display = 'block';
                }

                // Load existing rooms
                const roomStats = floorData.room_stats || {};
                let totalRooms = roomStats.total_rooms || 0;
                if (totalRooms === 0 && floorData.total_rooms) totalRooms = parseInt(floorData.total_rooms) || 0;
                
                const roomDropdown = document.getElementById('roomSelectorDropdown');
                roomDropdown.innerHTML = '<option value="">-- Select a room --</option>';
                let existingRoomNumbers = new Set();

                try {
                    const roomsResp = await fetch(`${API_BASE_URL}/rooms/by-floor/${fi}`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const roomsResult = await roomsResp.json();
                    if (roomsResult.success && roomsResult.data && roomsResult.data.length > 0) {
                        roomsResult.data.forEach(room => {
                            const roomId = room.id;
                            existingRoomNumbers.add(room.room_number);
                            roomDataMap[roomId] = room;
                            existingRoomIds.push(roomId);
                            const option = document.createElement('option');
                            option.value = roomId;
                            option.textContent = `${room.room_number}${room.room_name ? ' - ' + room.room_name : ''}`;
                            roomDropdown.appendChild(option);
                        });
                    }
                } catch (e) {}

                // Create new rooms based on total_rooms
                let roomCounter = 1;
                const roomsToCreate = Math.max(0, totalRooms - existingRoomIds.length);
                for (let i = 1; i <= roomsToCreate; i++) {
                    let roomNumber = `Room ${i}`;
                    let counter = 1;
                    while (existingRoomNumbers.has(roomNumber)) { 
                        counter++; 
                        roomNumber = `Room ${counter}`; 
                    }
                    existingRoomNumbers.add(roomNumber);
                    const roomId = `room_${Date.now()}_${i}`;
                    roomDataMap[roomId] = getDefaultRoomData(roomId, roomNumber);
                    existingRoomIds.push(roomId);
                    const option = document.createElement('option');
                    option.value = roomId;
                    option.textContent = roomNumber;
                    roomDropdown.appendChild(option);
                }

                document.getElementById('roomCountBadge').textContent = `${existingRoomIds.length} rooms`;
                roomSelectorCard.style.display = 'block';
                roomDetailPanel.classList.add('active');

                if (existingRoomIds.length > 0) {
                    roomDropdown.value = existingRoomIds[0];
                    onRoomSelectorChange();
                } else {
                    document.getElementById('roomDetailContainer').innerHTML = `
                        <div class="empty-state-msg">
                            <i class="fas fa-door-open"></i>
                            <p>No rooms configured for this floor. Click "Add New Room" to create one.</p>
                        </div>`;
                }

                if (totalRooms > 0) {
                    cb.textContent = `${totalRooms} rooms`;
                    s.style.display = 'block';
                    let h = `<div class="existing-floor-card">
                        <span class="existing-badge"><i class="fas fa-chart-bar"></i> Room Statistics</span>
                        <div class="floor-info">
                            <div>
                                <div class="floor-name"><i class="fas fa-door-open"></i> Total Rooms: ${roomStats.total_rooms || totalRooms}</div>
                                <div class="floor-meta">Available: ${roomStats.available_rooms || 0} | Occupied: ${roomStats.occupied_rooms || 0}</div>
                            </div>
                        </div>
                    </div>`;
                    c.innerHTML = h;
                    nr.style.display = 'none';
                } else { 
                    s.style.display = 'none'; 
                    nr.style.display = 'block'; 
                    cb.textContent = '0 rooms'; 
                }
            } else {
                floorAmenitiesList.innerHTML = '<span style="color:var(--text-muted);font-size:0.85rem;">No amenities data available.</span>';
                floorAmenitiesDisplay.style.display = 'block';
            }
        } catch (e) {
            console.error('Error loading floor data:', e);
            floorAmenitiesList.innerHTML = '<span style="color:var(--text-muted);font-size:0.85rem;">Could not load floor amenities.</span>';
            floorAmenitiesDisplay.style.display = 'block';
        }
        setTimeout(initRoomSpecTabs, 100);
    }

    function getDefaultRoomData(roomId, roomNumber) {
        return {
            id: roomId,
            room_number: roomNumber,
            room_name: '',
            room_type: 'classroom',
            custom_room_type: null,
            status: 'active',
            occupancy_status: 'vacant',
            floor_level: '',
            room_specifications: {
                capacity: null, min_capacity: null, occupancy_type: 'single',
                category: 'standard', usage_type: 'permanent',
                available_from: '09:00', available_to: '18:00', booking_required: 'no',
                area: null, length: null, width: null, height: null,
                doors: 0, windows: 0, door_type: 'single', window_type: 'casement',
                lights_count: 0, fans_count: 0, ac_count: 0, sockets_count: 0,
                lighting_type: 'led', ac_type: 'none',
                has_wifi: false, has_lan: false, has_telephone: false, has_cctv: false,
                internet_speed: null, network_type: 'both',
                has_projector: false, has_whiteboard: false, has_smart_board: false,
                has_desk: false, has_chair: false, has_cabinets: false,
                has_bed: false, has_tv: false, has_kitchenette: false,
                has_fridge: false, has_microwave: false, has_water_cooler: false,
                has_fire_extinguisher: false, has_fire_alarm: false,
                has_smoke_detector: false, has_sprinkler: false,
                has_emergency_exit: false, has_emergency_light: false,
                has_door_lock: false, has_smart_lock: false, has_intercom: false,
                security_level: 'medium',
                has_washroom: false, washroom_capacity: null, washroom_type: 'attached',
                washroom_area: 0, has_hot_water: false, has_shower: false,
                has_bathtub: false,
                has_wheelchair_access: false, has_grab_bars: false,
                has_visual_alerts: false, accessibility_level: 'none'
            },
            selected_floor_amenities: {},
            selected_floor_facilities: [],
            has_balcony: false,
            balcony_area: null,
            balcony_units: []
        };
    }

    // ===== ROOM SELECTOR FUNCTIONS =====
    function onRoomSelectorChange() {
        saveCurrentRoomData();
        const rid = document.getElementById('roomSelectorDropdown').value;
        if (!rid || !roomDataMap[rid]) {
            document.getElementById('roomDetailPanel').classList.remove('active');
            document.getElementById('roomDetailContainer').innerHTML = '';
            document.getElementById('selectedRoomBadge').style.display = 'none';
            document.getElementById('removeSelectedRoomBtn').style.display = 'none';
            selectedRoomId = null;
            return;
        }
        selectedRoomId = rid;
        const room = roomDataMap[rid];
        document.getElementById('selectedRoomBadge').style.display = 'inline-flex';
        document.getElementById('selectedRoomName').textContent = `${room.room_number}${room.room_name ? ' - ' + room.room_name : ''}`;
        document.getElementById('removeSelectedRoomBtn').style.display = 'inline-flex';
        document.getElementById('roomDetailPanel').classList.add('active');
        const container = document.getElementById('roomDetailContainer');
        container.innerHTML = buildRoomDetailHTML(rid, room);
        setTimeout(initRoomSpecTabs, 100);
        setTimeout(() => updateRoomAreaSummary(rid), 200);
    }

    function saveCurrentRoomData() {
        if (!selectedRoomId || !roomDataMap[selectedRoomId]) return;
        const data = getRoomDataFromDetail(selectedRoomId);
        if (data && data.room_number) {
            roomDataMap[selectedRoomId] = { ...roomDataMap[selectedRoomId], ...data };
        }
    }

    function updateRoomDropdownLabel(roomId, name) {
        const dropdown = document.getElementById('roomSelectorDropdown');
        if (dropdown) {
            const option = dropdown.querySelector(`option[value="${roomId}"]`);
            if (option) option.textContent = name;
        }
        const badge = document.getElementById('selectedRoomName');
        if (badge) badge.textContent = name;
    }

    // ===== ROOM CRUD OPERATIONS =====
    function addRoomFromDropdown() {
        saveCurrentRoomData();
        const fi = document.getElementById('roomFloorSelect').value;
        if (!fi) { showToast('Please select a floor first', 'error'); return; }
        
        const existingNumbers = new Set();
        Object.keys(roomDataMap).forEach(rid => { 
            const room = roomDataMap[rid]; 
            if (room && room.room_number) existingNumbers.add(room.room_number); 
        });
        
        let roomNumber = `Room ${existingRoomIds.length + 1}`;
        let counter = 1;
        while (existingNumbers.has(roomNumber)) { 
            counter++; 
            roomNumber = `Room ${counter}`; 
        }
        
        const rid = `new_${Date.now()}`;
        const roomData = getDefaultRoomData(rid, roomNumber);
        roomDataMap[rid] = roomData;
        existingRoomIds.push(rid);
        
        const dropdown = document.getElementById('roomSelectorDropdown');
        const option = document.createElement('option');
        option.value = rid;
        option.textContent = roomData.room_number;
        dropdown.appendChild(option);
        document.getElementById('roomCountBadge').textContent = `${existingRoomIds.length} rooms`;
        dropdown.value = rid;
        onRoomSelectorChange();
        showToast('New room added! Fill in the details and click Save.', 'info');
        setTimeout(initRoomSpecTabs, 100);
    }

    function removeRoomFromDropdown(rid) {
        saveCurrentRoomData();
        if (existingRoomIds.length <= 1) { 
            showToast('At least one room required', 'error'); 
            return; 
        }
        if (!confirm('Are you sure you want to delete this room?')) return;
        
        if (!rid.toString().startsWith('new_')) {
            fetch(`${API_BASE_URL}/rooms/${rid}`, {
                method: 'DELETE',
                headers: { 
                    'Accept': 'application/json', 
                    'X-CSRF-TOKEN': CSRF_TOKEN, 
                    'X-Requested-With': 'XMLHttpRequest' 
                }
            }).then(r => r.json()).then(result => {
                if (result.success) showToast('Room deleted successfully');
                else showToast(result.message || 'Failed to delete room', 'error');
            }).catch(e => showToast('Error deleting room', 'error'));
        }
        
        const index = existingRoomIds.indexOf(rid);
        if (index > -1) existingRoomIds.splice(index, 1);
        delete roomDataMap[rid];
        
        const dropdown = document.getElementById('roomSelectorDropdown');
        const option = dropdown?.querySelector(`option[value="${rid}"]`);
        if (option) option.remove();
        document.getElementById('roomCountBadge').textContent = `${existingRoomIds.length} rooms`;
        
        if (existingRoomIds.length > 0) { 
            dropdown.value = existingRoomIds[0]; 
            onRoomSelectorChange(); 
        } else {
            document.getElementById('roomDetailPanel').classList.remove('active');
            document.getElementById('roomDetailContainer').innerHTML = `
                <div class="empty-state-msg">
                    <i class="fas fa-door-open"></i>
                    <p>No rooms. Add a new room using the button above.</p>
                </div>`;
            document.getElementById('roomDetailPanel').classList.add('active');
            document.getElementById('selectedRoomBadge').style.display = 'none';
            document.getElementById('removeSelectedRoomBtn').style.display = 'none';
            selectedRoomId = null;
        }
    }

    function removeSelectedRoom() { 
        if (selectedRoomId) removeRoomFromDropdown(selectedRoomId); 
    }

    // ===== ROOM TYPE HANDLING =====
    function onRoomTypeSelect(roomId) {
        const select = document.getElementById(`roomTypeSelect-${roomId}`);
        const selectedTypeId = select.value;
        const room = roomDataMap[roomId];
        if (!room) return;
        
        let typeName = selectedTypeId;
        if (selectedTypeId !== 'other') {
            const foundType = roomTypeOptions.find(t => t.id === selectedTypeId);
            if (foundType) typeName = foundType.name;
        }
        room.room_type = typeName;
        room.room_type_id = selectedTypeId;
        
        if (selectedTypeId !== 'other') {
            room.custom_room_type = null;
            const typeNameForAuto = roomTypeOptions.find(t => t.id === selectedTypeId)?.name || '';
            const numberInput = document.getElementById(`roomDetailNumber-${roomId}`);
            if (numberInput && typeNameForAuto) {
                const currentNumber = numberInput.value.trim();
                if (!currentNumber || currentNumber.startsWith('Room ')) {
                    let typeCount = 0;
                    Object.keys(roomDataMap).forEach(rid => {
                        const r = roomDataMap[rid];
                        if (r && r.room_type === typeNameForAuto && r.id !== roomId) {
                            typeCount++;
                        }
                    });
                    const newName = `${typeNameForAuto} ${typeCount + 1}`;
                    numberInput.value = newName;
                    updateRoomDropdownLabel(roomId, newName);
                }
            }
        }
        
        const customContainer = document.getElementById(`customRoomTypeContainer-${roomId}`);
        if (customContainer) {
            customContainer.style.display = selectedTypeId === 'other' ? 'block' : 'none';
            if (selectedTypeId !== 'other') {
                const customInput = document.getElementById(`customRoomType-${roomId}`);
                if (customInput) customInput.value = '';
            }
        }
    }

    function onCustomRoomTypeInput(roomId) {
        const input = document.getElementById(`customRoomType-${roomId}`);
        const room = roomDataMap[roomId];
        if (room) {
            const customTypeName = input.value.trim();
            room.custom_room_type = customTypeName;
            room.room_type = customTypeName;
            
            const numberInput = document.getElementById(`roomDetailNumber-${roomId}`);
            if (numberInput && customTypeName) {
                const currentNumber = numberInput.value.trim();
                if (!currentNumber || currentNumber.startsWith('Room ') || currentNumber.startsWith('Custom ')) {
                    let typeCount = 0;
                    Object.keys(roomDataMap).forEach(rid => {
                        const r = roomDataMap[rid];
                        if (r && r.custom_room_type === customTypeName && r.id !== roomId) {
                            typeCount++;
                        }
                    });
                    const newName = `${customTypeName} ${typeCount + 1}`;
                    numberInput.value = newName;
                    updateRoomDropdownLabel(roomId, newName);
                }
            }
        }
    }

    // ===== BUILD ROOM DETAIL HTML =====
    function buildRoomDetailHTML(roomId, room) {
        const roomTypeId = room.room_type_id || room.room_type || 'classroom';
        let selectedTypeId = roomTypeId;
        if (!roomTypeOptions.find(t => t.id === selectedTypeId) && selectedTypeId !== 'other') {
            const found = roomTypeOptions.find(t => t.name === selectedTypeId);
            if (found) selectedTypeId = found.id;
        }
        const isOther = selectedTypeId === 'other' || !roomTypeOptions.find(t => t.id === selectedTypeId);
        const customType = room.custom_room_type || '';
        
        let typeName = selectedTypeId;
        if (selectedTypeId !== 'other') {
            const foundType = roomTypeOptions.find(t => t.id === selectedTypeId);
            if (foundType) typeName = foundType.name;
        } else if (customType) {
            typeName = customType;
        }

        const specs = room.room_specifications || {};

        // Build type options
        let typeOptionsHtml = '<select id="roomTypeSelect-' + roomId + '" class="form-control" onchange="onRoomTypeSelect(\'' + roomId + '\')">';
        roomTypeOptions.forEach(type => {
            const selected = type.id === selectedTypeId ? 'selected' : '';
            typeOptionsHtml += `<option value="${type.id}" ${selected}>${escapeHtml(type.name)}</option>`;
        });
        typeOptionsHtml += '</select>';

        // Custom type input
        const customTypeHtml = `
            <div id="customRoomTypeContainer-${roomId}" style="${isOther ? 'display:block;' : 'display:none;'}margin-top:12px;">
                <input type="text" id="customRoomType-${roomId}" class="form-control" value="${escapeHtml(customType)}" placeholder="Enter custom room type" maxlength="50" oninput="onCustomRoomTypeInput('${roomId}')">
            </div>
        `;

        // Floor amenities
        const floorAmenities = currentFloorAmenities || { amenities: {}, facilities: [] };
        const amenitiesList = floorAmenities.amenities || {};
        const facilitiesList = floorAmenities.facilities || [];
        const selectedAmenities = room.selected_floor_amenities || {};
        const selectedFacilities = room.selected_floor_facilities || [];

        let amenitiesSelectionHtml = '';
        const amenityKeys = Object.keys(amenitiesList);
        if (amenityKeys.length > 0) {
            amenitiesSelectionHtml += `<div class="amenity-category"><div class="amenity-category-title"><i class="fas fa-concierge-bell"></i> Floor Amenities</div><div class="amenities-grid">`;
            amenityKeys.forEach(key => {
                const total = amenitiesList[key] || 0;
                const selected = selectedAmenities[key] || 0;
                const isChecked = selected > 0;
                amenitiesSelectionHtml += `
                    <div class="amenity-item ${isChecked ? 'selected' : ''}">
                        <input type="checkbox" class="room-amenity-check" data-amenity-key="${key}" data-room-id="${roomId}" ${isChecked ? 'checked' : ''} onchange="toggleRoomAmenity('${roomId}', '${key}', this)">
                        <label>${escapeHtml(key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()))}</label>
                        <input type="number" class="amenity-qty" data-amenity-key="${key}" data-room-id="${roomId}" min="1" max="${total}" value="${selected || 1}" ${isChecked ? '' : 'disabled'} onchange="updateRoomAmenityQty('${roomId}', '${key}', this)">
                        <span style="font-size:0.65rem;color:var(--text-muted);">/ ${total}</span>
                    </div>`;
            });
            amenitiesSelectionHtml += `</div></div>`;
        }
        if (facilitiesList.length > 0) {
            amenitiesSelectionHtml += `<div class="amenity-category"><div class="amenity-category-title"><i class="fas fa-building"></i> Floor Facilities</div><div class="amenities-grid">`;
            facilitiesList.forEach(facility => {
                const facilityId = typeof facility === 'string' ? facility : facility.id || facility;
                const facilityName = typeof facility === 'string' ? facility : (facility.name || facilityId);
                const isChecked = selectedFacilities.includes(facilityId);
                let extraInfo = '';
                let facilityEntry = null;
                let areaValue = 0;
                for (const type of Object.keys(campusFacilityEntries)) {
                    const found = campusFacilityEntries[type]?.find(e => e.id === facilityId);
                    if (found) { facilityEntry = found; areaValue = found.area || 0; break; }
                }
                if (facilityEntry && facilityEntry.toilets !== undefined) extraInfo = ` 🚽${facilityEntry.toilets} 🚹${facilityEntry.urinals||0} 🚰${facilityEntry.washbasins}`;
                if (areaValue > 0) extraInfo += ` 📐${areaValue.toFixed(1)} sqft`;
                amenitiesSelectionHtml += `
                    <div class="amenity-item ${isChecked ? 'selected' : ''}">
                        <input type="checkbox" class="room-facility-check" data-facility-id="${facilityId}" data-room-id="${roomId}" ${isChecked ? 'checked' : ''} onchange="toggleRoomFacility('${roomId}', '${facilityId}', this)">
                        <label>${escapeHtml(facilityName)}${extraInfo}</label>
                    </div>`;
            });
            amenitiesSelectionHtml += `</div></div>`;
        }
        if (!amenitiesSelectionHtml) {
            amenitiesSelectionHtml = `<div style="color:var(--text-muted);font-size:0.85rem;padding:0.5rem 0;">No floor amenities or facilities available to assign.</div>`;
        }

        // Balcony
        const hasBalcony = room.has_balcony || false;
        const balconyArea = room.balcony_area || '';
        const balconyUnits = room.balcony_units || [];
        let balconyHtml = '';
        if (hasBalcony) {
            const unitCount = balconyUnits.length || 1;
            balconyHtml = `
                <div class="balcony-card" id="balcony-card-${roomId}">
                    <div class="balcony-badge"><i class="fas fa-balcony"></i> Balcony</div>
                    <div class="card-row" style="grid-template-columns:1fr 1fr;gap:0.75rem;margin-top:0.5rem;">
                        <div class="form-group">
                            <label>Number of Balcony Units</label>
                            <input type="number" id="roomBalconyCount-${roomId}" class="form-control" min="1" value="${unitCount}" onchange="updateBalconyUnits('${roomId}')">
                        </div>
                        <div class="form-group">
                            <label>Total Balcony Area (sq. ft.)</label>
                            <input type="number" id="roomBalconyArea-${roomId}" class="form-control" min="0" step="0.01" value="${balconyArea}" placeholder="Area" onchange="updateRoomAreaSummary('${roomId}')">
                        </div>
                    </div>
                    <div id="balcony-units-container-${roomId}" style="margin-top:0.5rem;">
                        ${balconyUnits.map((unit, idx) => `
                            <div class="dynamic-card" style="padding:0.75rem;margin-bottom:0.5rem;">
                                <div class="card-badge-sm">Unit #${idx + 1}</div>
                                <div class="card-row" style="grid-template-columns:1fr 1fr;gap:0.75rem;">
                                    <div class="form-group">
                                        <label>Area (sq. ft.)</label>
                                        <input type="number" class="form-control balcony-unit-area" min="0" step="0.01" value="${unit.area || ''}" placeholder="Area" onchange="updateBalconyUnitArea('${roomId}', ${idx}, this)">
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <input type="text" class="form-control balcony-unit-desc" value="${escapeHtml(unit.description || '')}" placeholder="e.g., Front balcony" onchange="updateBalconyUnitDesc('${roomId}', ${idx}, this)">
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        const roomAreaDeductionHtml = buildRoomAreaDeductionHTML(roomId);
        const washroomChecked = specs.has_washroom ? 'checked' : '';
        const washroomDetailsStyle = specs.has_washroom ? 'display:block;' : 'display:none;';
        const washroomArea = specs.washroom_area || 0;
        const balconyChecked = hasBalcony ? 'checked' : '';

        return `
            <div class="room-detail-card" id="roomDetailCard-${roomId}">
                <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div class="form-group">
                        <label class="form-label">Room Number <span style="color:#dc3545;">*</span></label>
                        <input type="text" id="roomDetailNumber-${roomId}" class="form-control" value="${escapeHtml(room.room_number || '')}" placeholder="e.g., 101, Lab-1" maxlength="20" onchange="updateRoomDropdownLabel('${roomId}', this.value)">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Room Name</label>
                        <input type="text" id="roomDetailName-${roomId}" class="form-control" value="${escapeHtml(room.room_name || '')}" placeholder="e.g., Computer Lab" maxlength="100">
                    </div>
                </div>
                <div class="card-row" style="display:grid;grid-template-columns:1fr;gap:1rem;margin-bottom:1rem;">
                    <div class="form-group">
                        <label class="form-label">Room Type <span style="color:#dc3545;">*</span></label>
                        ${typeOptionsHtml}
                        ${customTypeHtml}
                        <input type="hidden" id="roomDetailType-${roomId}" value="${escapeHtml(typeName)}">
                    </div>
                </div>
                <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select id="roomDetailStatus-${roomId}" class="form-control">
                            <option value="active" ${room.status === 'active' ? 'selected' : ''}>Active</option>
                            <option value="inactive" ${room.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Occupancy Status</label>
                        <select id="roomDetailOccupancy-${roomId}" class="form-control">
                            <option value="vacant" ${room.occupancy_status === 'vacant' ? 'selected' : ''}>Vacant</option>
                            <option value="occupied" ${room.occupancy_status === 'occupied' ? 'selected' : ''}>Occupied</option>
                            <option value="maintenance" ${room.occupancy_status === 'maintenance' ? 'selected' : ''}>Under Maintenance</option>
                            <option value="reserved" ${room.occupancy_status === 'reserved' ? 'selected' : ''}>Reserved</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Floor Level</label>
                        <input type="text" id="roomDetailFloorLevel-${roomId}" class="form-control" value="${escapeHtml(room.floor_level || '')}" placeholder="e.g., Ground, 1st">
                    </div>
                </div>

                <!-- Total Area Section -->
                <div class="section-divider"><h4><i class="fas fa-vector-square"></i> Room Area</h4></div>
                <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div class="form-group">
                        <label class="form-label">Area Unit</label>
                        <select id="roomDetailAreaUnit-${roomId}" class="form-control">
                            <option value="sq_ft">Sq. Ft.</option>
                            <option value="sq_m">Sq. M.</option>
                            <option value="sq_yd">Sq. Yd.</option>
                            <option value="gaj">Gaj</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Area Value</label>
                        <input type="number" id="roomDetailAreaValue-${roomId}" class="form-control" value="${specs.area || ''}" placeholder="Enter room area" min="0" step="0.01" onchange="updateRoomAreaSummary('${roomId}')">
                    </div>
                </div>

                <!-- Area Summary -->
                ${roomAreaDeductionHtml}

                <!-- Balcony Section -->
                <div class="section-divider"><h4><i class="fas fa-balcony"></i> Balcony</h4></div>
                <div class="facility-toggle">
                    <input type="checkbox" id="roomHasBalcony-${roomId}" ${balconyChecked} onchange="toggleRoomBalcony('${roomId}')">
                    <label for="roomHasBalcony-${roomId}">Has Balcony</label>
                </div>
                <div id="roomBalconyContainer-${roomId}" style="${hasBalcony ? 'display:block;' : 'display:none;'}">${balconyHtml}</div>

                <!-- Washroom Section -->
                <div class="section-divider"><h4><i class="fas fa-toilet"></i> Washroom</h4></div>
                <div class="facility-toggle">
                    <input type="checkbox" id="roomHasWashroom-${roomId}" ${washroomChecked} onchange="toggleRoomWashroom('${roomId}')">
                    <label for="roomHasWashroom-${roomId}">Has Attached Washroom</label>
                </div>
                <div id="roomWashroomContainer-${roomId}" style="${washroomDetailsStyle}margin-top:0.75rem;">
                    <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div class="form-group">
                            <label class="form-label">Washroom Area (sq. ft.)</label>
                            <input type="number" id="roomDetailWashroomArea-${roomId}" class="form-control" value="${washroomArea}" placeholder="Area" min="0" step="0.01" onchange="updateRoomAreaSummary('${roomId}')">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Washroom Type</label>
                            <select id="roomDetailWashroomType-${roomId}" class="form-control">
                                <option value="attached" ${specs.washroom_type === 'attached' ? 'selected' : ''}>Attached</option>
                                <option value="shared" ${specs.washroom_type === 'shared' ? 'selected' : ''}>Shared</option>
                                <option value="common" ${specs.washroom_type === 'common' ? 'selected' : ''}>Common</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-row" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:0.5rem;">
                        <div class="form-group">
                            <label class="form-label">Washroom Capacity (Persons)</label>
                            <input type="number" id="roomDetailWashroomCap-${roomId}" class="form-control" value="${specs.washroom_capacity || ''}" placeholder="Capacity" min="1">
                        </div>
                        <div class="form-group" style="display:flex;align-items:center;gap:10px;padding-top:8px;">
                            <input type="checkbox" id="roomDetailHotWater-${roomId}" ${specs.has_hot_water ? 'checked' : ''}>
                            <label for="roomDetailHotWater-${roomId}" style="margin:0;font-weight:500;">Hot Water</label>
                            <input type="checkbox" id="roomDetailShower-${roomId}" ${specs.has_shower ? 'checked' : ''}>
                            <label for="roomDetailShower-${roomId}" style="margin:0;font-weight:500;">Shower</label>
                        </div>
                    </div>
                </div>

                <!-- Floor Amenities -->
                <div class="section-divider"><h4><i class="fas fa-concierge-bell"></i> Floor Amenities & Facilities Assignment</h4></div>
                <div class="room-amenities-selection">${amenitiesSelectionHtml}</div>

                <!-- Specifications Tabs -->
                <div class="section-divider"><h4><i class="fas fa-cogs"></i> Room Specifications</h4></div>
                <div class="room-specs-tabs">
                    <button class="tab-btn active" onclick="switchRoomSpecTab('general', this)"><i class="fas fa-info-circle"></i> General</button>
                    <button class="tab-btn" onclick="switchRoomSpecTab('dimensions', this)"><i class="fas fa-ruler-combined"></i> Dimensions</button>
                    <button class="tab-btn" onclick="switchRoomSpecTab('utilities', this)"><i class="fas fa-bolt"></i> Utilities</button>
                    <button class="tab-btn" onclick="switchRoomSpecTab('furniture', this)"><i class="fas fa-chair"></i> Furniture</button>
                    <button class="tab-btn" onclick="switchRoomSpecTab('safety', this)"><i class="fas fa-shield-alt"></i> Safety</button>
                    <button class="tab-btn" onclick="switchRoomSpecTab('additional', this)"><i class="fas fa-plus-circle"></i> Additional</button>
                </div>

                <!-- Tab 1: General -->
                <div class="room-specs-tab-content active" id="tab-general">
                    <div class="spec-group-grid">
                        <div class="spec-group-card">
                            <h4><i class="fas fa-users"></i> Capacity & Occupancy</h4>
                            <div class="spec-row">
                                <div class="form-group">
                                    <label>Max Capacity (Persons)</label>
                                    <input type="number" id="roomDetailCapacity-${roomId}" class="form-control" value="${specs.capacity || ''}" placeholder="Persons" min="1">
                                </div>
                                <div class="form-group">
                                    <label>Min Capacity</label>
                                    <input type="number" id="roomDetailMinCapacity-${roomId}" class="form-control" value="${specs.min_capacity || ''}" placeholder="Min persons" min="0">
                                </div>
                            </div>
                            <div class="spec-row" style="margin-top:0.5rem;">
                                <div class="form-group">
                                    <label>Occupancy Type</label>
                                    <select id="roomDetailOccupancyType-${roomId}" class="form-control">
                                        <option value="single" ${specs.occupancy_type === 'single' ? 'selected' : ''}>Single</option>
                                        <option value="shared" ${specs.occupancy_type === 'shared' ? 'selected' : ''}>Shared</option>
                                        <option value="multiple" ${specs.occupancy_type === 'multiple' ? 'selected' : ''}>Multiple</option>
                                        <option value="flexible" ${specs.occupancy_type === 'flexible' ? 'selected' : ''}>Flexible</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Room Category</label>
                                    <select id="roomDetailCategory-${roomId}" class="form-control">
                                        <option value="standard" ${specs.category === 'standard' ? 'selected' : ''}>Standard</option>
                                        <option value="premium" ${specs.category === 'premium' ? 'selected' : ''}>Premium</option>
                                        <option value="deluxe" ${specs.category === 'deluxe' ? 'selected' : ''}>Deluxe</option>
                                        <option value="executive" ${specs.category === 'executive' ? 'selected' : ''}>Executive</option>
                                        <option value="economy" ${specs.category === 'economy' ? 'selected' : ''}>Economy</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="spec-group-card">
                            <h4><i class="fas fa-clock"></i> Usage & Timing</h4>
                            <div class="spec-row">
                                <div class="form-group">
                                    <label>Usage Type</label>
                                    <select id="roomDetailUsageType-${roomId}" class="form-control">
                                        <option value="permanent" ${specs.usage_type === 'permanent' ? 'selected' : ''}>Permanent</option>
                                        <option value="temporary" ${specs.usage_type === 'temporary' ? 'selected' : ''}>Temporary</option>
                                        <option value="flexible" ${specs.usage_type === 'flexible' ? 'selected' : ''}>Flexible</option>
                                        <option value="event_based" ${specs.usage_type === 'event_based' ? 'selected' : ''}>Event Based</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Available From</label>
                                    <input type="time" id="roomDetailAvailableFrom-${roomId}" class="form-control" value="${specs.available_from || '09:00'}">
                                </div>
                            </div>
                            <div class="spec-row" style="margin-top:0.5rem;">
                                <div class="form-group">
                                    <label>Available To</label>
                                    <input type="time" id="roomDetailAvailableTo-${roomId}" class="form-control" value="${specs.available_to || '18:00'}">
                                </div>
                                <div class="form-group">
                                    <label>Booking Required</label>
                                    <select id="roomDetailBookingRequired-${roomId}" class="form-control">
                                        <option value="yes" ${specs.booking_required === 'yes' ? 'selected' : ''}>Yes</option>
                                        <option value="no" ${specs.booking_required === 'no' ? 'selected' : ''}>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Dimensions -->
                <div class="room-specs-tab-content" id="tab-dimensions">
                    <div class="spec-group-grid">
                        <div class="spec-group-card">
                            <h4><i class="fas fa-vector-square"></i> Dimensions</h4>
                            <div class="spec-row">
                                <div class="form-group">
                                    <label>Length (ft)</label>
                                    <input type="number" id="roomDetailLength-${roomId}" class="form-control" value="${specs.length || ''}" placeholder="Length" min="0" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label>Width (ft)</label>
                                    <input type="number" id="roomDetailWidth-${roomId}" class="form-control" value="${specs.width || ''}" placeholder="Width" min="0" step="0.01">
                                </div>
                            </div>
                            <div class="spec-row" style="margin-top:0.5rem;">
                                <div class="form-group">
                                    <label>Height (ft)</label>
                                    <input type="number" id="roomDetailHeight-${roomId}" class="form-control" value="${specs.height || ''}" placeholder="Height" min="0" step="0.01">
                                </div>
                            </div>
                        </div>
                        <div class="spec-group-card">
                            <h4><i class="fas fa-door-open"></i> Doors & Windows</h4>
                            <div class="spec-row">
                                <div class="form-group">
                                    <label>Number of Doors</label>
                                    <input type="number" id="roomDetailDoors-${roomId}" class="form-control" value="${specs.doors || 0}" min="0">
                                </div>
                                <div class="form-group">
                                    <label>Number of Windows</label>
                                    <input type="number" id="roomDetailWindows-${roomId}" class="form-control" value="${specs.windows || 0}" min="0">
                                </div>
                            </div>
                            <div class="spec-row" style="margin-top:0.5rem;">
                                <div class="form-group">
                                    <label>Door Type</label>
                                    <select id="roomDetailDoorType-${roomId}" class="form-control">
                                        <option value="single" ${specs.door_type === 'single' ? 'selected' : ''}>Single Door</option>
                                        <option value="double" ${specs.door_type === 'double' ? 'selected' : ''}>Double Door</option>
                                        <option value="sliding" ${specs.door_type === 'sliding' ? 'selected' : ''}>Sliding</option>
                                        <option value="french" ${specs.door_type === 'french' ? 'selected' : ''}>French</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Window Type</label>
                                    <select id="roomDetailWindowType-${roomId}" class="form-control">
                                        <option value="casement" ${specs.window_type === 'casement' ? 'selected' : ''}>Casement</option>
                                        <option value="sliding" ${specs.window_type === 'sliding' ? 'selected' : ''}>Sliding</option>
                                        <option value="fixed" ${specs.window_type === 'fixed' ? 'selected' : ''}>Fixed</option>
                                        <option value="bay" ${specs.window_type === 'bay' ? 'selected' : ''}>Bay</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Utilities -->
                <div class="room-specs-tab-content" id="tab-utilities">
                    <div class="spec-group-grid">
                        <div class="spec-group-card">
                            <h4><i class="fas fa-bolt"></i> Electrical</h4>
                            <div class="spec-row">
                                <div class="form-group">
                                    <label>Lights</label>
                                    <input type="number" id="roomDetailLights-${roomId}" class="form-control" value="${specs.lights_count || 0}" min="0">
                                </div>
                                <div class="form-group">
                                    <label>Fans</label>
                                    <input type="number" id="roomDetailFans-${roomId}" class="form-control" value="${specs.fans_count || 0}" min="0">
                                </div>
                            </div>
                            <div class="spec-row" style="margin-top:0.5rem;">
                                <div class="form-group">
                                    <label>AC Units</label>
                                    <input type="number" id="roomDetailAC-${roomId}" class="form-control" value="${specs.ac_count || 0}" min="0">
                                </div>
                                <div class="form-group">
                                    <label>Power Sockets</label>
                                    <input type="number" id="roomDetailSockets-${roomId}" class="form-control" value="${specs.sockets_count || 0}" min="0">
                                </div>
                            </div>
                            <div class="spec-row" style="margin-top:0.5rem;">
                                <div class="form-group">
                                    <label>Lighting Type</label>
                                    <select id="roomDetailLightingType-${roomId}" class="form-control">
                                        <option value="led" ${specs.lighting_type === 'led' ? 'selected' : ''}>LED</option>
                                        <option value="fluorescent" ${specs.lighting_type === 'fluorescent' ? 'selected' : ''}>Fluorescent</option>
                                        <option value="incandescent" ${specs.lighting_type === 'incandescent' ? 'selected' : ''}>Incandescent</option>
                                        <option value="natural" ${specs.lighting_type === 'natural' ? 'selected' : ''}>Natural</option>
                                        <option value="mixed" ${specs.lighting_type === 'mixed' ? 'selected' : ''}>Mixed</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>AC Type</label>
                                    <select id="roomDetailACType-${roomId}" class="form-control">
                                        <option value="central" ${specs.ac_type === 'central' ? 'selected' : ''}>Central</option>
                                        <option value="split" ${specs.ac_type === 'split' ? 'selected' : ''}>Split</option>
                                        <option value="window" ${specs.ac_type === 'window' ? 'selected' : ''}>Window</option>
                                        <option value="none" ${specs.ac_type === 'none' ? 'selected' : ''}>None</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="spec-group-card">
                            <h4><i class="fas fa-wifi"></i> Connectivity</h4>
                            <div class="checkbox-group">
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailWifi-${roomId}" ${specs.has_wifi ? 'checked' : ''}><label>Wi-Fi</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailLAN-${roomId}" ${specs.has_lan ? 'checked' : ''}><label>LAN Port</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailTelephone-${roomId}" ${specs.has_telephone ? 'checked' : ''}><label>Telephone</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailCCTV-${roomId}" ${specs.has_cctv ? 'checked' : ''}><label>CCTV Coverage</label></div>
                            </div>
                            <div class="spec-row" style="margin-top:0.5rem;">
                                <div class="form-group">
                                    <label>Internet Speed (Mbps)</label>
                                    <input type="number" id="roomDetailInternetSpeed-${roomId}" class="form-control" value="${specs.internet_speed || ''}" placeholder="Speed" min="0">
                                </div>
                                <div class="form-group">
                                    <label>Network Type</label>
                                    <select id="roomDetailNetworkType-${roomId}" class="form-control">
                                        <option value="wired" ${specs.network_type === 'wired' ? 'selected' : ''}>Wired</option>
                                        <option value="wireless" ${specs.network_type === 'wireless' ? 'selected' : ''}>Wireless</option>
                                        <option value="both" ${specs.network_type === 'both' ? 'selected' : ''}>Both</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 4: Furniture -->
                <div class="room-specs-tab-content" id="tab-furniture">
                    <div class="spec-group-grid">
                        <div class="spec-group-card">
                            <h4><i class="fas fa-chair"></i> Furniture & Equipment</h4>
                            <div class="checkbox-group">
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailProjector-${roomId}" ${specs.has_projector ? 'checked' : ''}><label>Projector</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailWhiteboard-${roomId}" ${specs.has_whiteboard ? 'checked' : ''}><label>Whiteboard</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailSmartBoard-${roomId}" ${specs.has_smart_board ? 'checked' : ''}><label>Smart Board</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailDesk-${roomId}" ${specs.has_desk ? 'checked' : ''}><label>Desk / Table</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailChair-${roomId}" ${specs.has_chair ? 'checked' : ''}><label>Chairs</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailCabinets-${roomId}" ${specs.has_cabinets ? 'checked' : ''}><label>Cabinets / Storage</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailBed-${roomId}" ${specs.has_bed ? 'checked' : ''}><label>Bed</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailTV-${roomId}" ${specs.has_tv ? 'checked' : ''}><label>TV / Monitor</label></div>
                            </div>
                        </div>
                        <div class="spec-group-card">
                            <h4><i class="fas fa-utensils"></i> Kitchenette / Pantry</h4>
                            <div class="checkbox-group">
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailKitchenette-${roomId}" ${specs.has_kitchenette ? 'checked' : ''}><label>Kitchenette</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailFridge-${roomId}" ${specs.has_fridge ? 'checked' : ''}><label>Refrigerator</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailMicrowave-${roomId}" ${specs.has_microwave ? 'checked' : ''}><label>Microwave</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailWaterCooler-${roomId}" ${specs.has_water_cooler ? 'checked' : ''}><label>Water Cooler</label></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 5: Safety -->
                <div class="room-specs-tab-content" id="tab-safety">
                    <div class="spec-group-grid">
                        <div class="spec-group-card">
                            <h4><i class="fas fa-fire-extinguisher"></i> Fire Safety</h4>
                            <div class="checkbox-group">
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailFireExt-${roomId}" ${specs.has_fire_extinguisher ? 'checked' : ''}><label>Fire Extinguisher</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailFireAlarm-${roomId}" ${specs.has_fire_alarm ? 'checked' : ''}><label>Fire Alarm</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailSmokeDet-${roomId}" ${specs.has_smoke_detector ? 'checked' : ''}><label>Smoke Detector</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailSprinkler-${roomId}" ${specs.has_sprinkler ? 'checked' : ''}><label>Sprinkler System</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailEmergExit-${roomId}" ${specs.has_emergency_exit ? 'checked' : ''}><label>Emergency Exit</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailEmergLight-${roomId}" ${specs.has_emergency_light ? 'checked' : ''}><label>Emergency Lighting</label></div>
                            </div>
                        </div>
                        <div class="spec-group-card">
                            <h4><i class="fas fa-shield-alt"></i> Security</h4>
                            <div class="checkbox-group">
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailDoorLock-${roomId}" ${specs.has_door_lock ? 'checked' : ''}><label>Door Lock</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailSmartLock-${roomId}" ${specs.has_smart_lock ? 'checked' : ''}><label>Smart Lock</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailIntercom-${roomId}" ${specs.has_intercom ? 'checked' : ''}><label>Intercom</label></div>
                            </div>
                            <div class="spec-row" style="margin-top:0.5rem;">
                                <div class="form-group">
                                    <label>Security Level</label>
                                    <select id="roomDetailSecurityLevel-${roomId}" class="form-control">
                                        <option value="low" ${specs.security_level === 'low' ? 'selected' : ''}>Low</option>
                                        <option value="medium" ${specs.security_level === 'medium' ? 'selected' : ''}>Medium</option>
                                        <option value="high" ${specs.security_level === 'high' ? 'selected' : ''}>High</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 6: Additional -->
                <div class="room-specs-tab-content" id="tab-additional">
                    <div class="spec-group-grid">
                        <div class="spec-group-card">
                            <h4><i class="fas fa-notes-medical"></i> Accessibility</h4>
                            <div class="checkbox-group">
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailWheelchair-${roomId}" ${specs.has_wheelchair_access ? 'checked' : ''}><label>Wheelchair Accessible</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailGrabBars-${roomId}" ${specs.has_grab_bars ? 'checked' : ''}><label>Grab Bars</label></div>
                                <div class="checkbox-item"><input type="checkbox" id="roomDetailVisualAlerts-${roomId}" ${specs.has_visual_alerts ? 'checked' : ''}><label>Visual Alerts</label></div>
                            </div>
                            <div class="spec-row" style="margin-top:0.5rem;">
                                <div class="form-group">
                                    <label>Accessibility Level</label>
                                    <select id="roomDetailAccessibilityLevel-${roomId}" class="form-control">
                                        <option value="none" ${specs.accessibility_level === 'none' ? 'selected' : ''}>None</option>
                                        <option value="basic" ${specs.accessibility_level === 'basic' ? 'selected' : ''}>Basic</option>
                                        <option value="full" ${specs.accessibility_level === 'full' ? 'selected' : ''}>Full</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="margin-top:1.5rem;display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" class="btn btn-success" onclick="updateRoomFromDetail('${roomId}')"><i class="fas fa-save"></i> Update Room</button>
                    <button type="button" class="btn-outline-danger" onclick="removeRoomFromDropdown('${roomId}')"><i class="fas fa-trash"></i> Delete Room</button>
                </div>
            </div>
        `;
    }

    // ===== ROOM SPECIFICATION FUNCTIONS =====
    function switchRoomSpecTab(tabId, btn) {
        document.querySelectorAll('.room-specs-tabs .tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.room-specs-tab-content').forEach(c => c.classList.remove('active'));
        if (btn) btn.classList.add('active');
        const content = document.getElementById(`tab-${tabId}`);
        if (content) content.classList.add('active');
    }

    function initRoomSpecTabs() {
        const firstTab = document.querySelector('.room-specs-tabs .tab-btn');
        if (firstTab) {
            const tabId = firstTab.getAttribute('onclick')?.match(/'([^']+)'/)?.[1];
            if (tabId) {
                document.querySelectorAll('.room-specs-tabs .tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.room-specs-tab-content').forEach(c => c.classList.remove('active'));
                firstTab.classList.add('active');
                const content = document.getElementById(`tab-${tabId}`);
                if (content) content.classList.add('active');
            }
        }
    }

    // ===== ROOM AREA FUNCTIONS =====
    function buildRoomAreaDeductionHTML(roomId) {
        return `<div class="area-deduction-summary" id="roomAreaSummary-${roomId}">
            <div class="deduction-item"><span>Total Room Area:</span><span id="roomTotalArea-${roomId}">0.00</span></div>
            <div class="deduction-item"><span>Washroom Area Deduction:</span><span id="roomWashroomDeduction-${roomId}">0.00</span></div>
            <div class="deduction-item"><span>Balcony Area Deduction:</span><span id="roomBalconyDeduction-${roomId}">0.00</span></div>
            <div class="deduction-item"><span>Facilities Area Deduction:</span><span id="roomFacilityDeduction-${roomId}">0.00</span></div>
            <div class="deduction-item total-row"><span>Usable Area:</span><span id="roomUsableArea-${roomId}" class="positive">0.00</span></div>
        </div>`;
    }

    function updateRoomAreaSummary(roomId) {
        const roomCard = document.getElementById(`roomDetailCard-${roomId}`);
        if (!roomCard) return;
        
        const areaValueInput = document.getElementById(`roomDetailAreaValue-${roomId}`);
        const roomArea = parseFloat(areaValueInput?.value) || 0;
        
        const washroomAreaInput = document.getElementById(`roomDetailWashroomArea-${roomId}`);
        const washroomArea = parseFloat(washroomAreaInput?.value) || 0;
        
        const balconyAreaInput = document.getElementById(`roomBalconyArea-${roomId}`);
        const balconyArea = parseFloat(balconyAreaInput?.value) || 0;
        
        let facilityDeduction = 0;
        const room = roomDataMap[roomId];
        if (room && room.selected_floor_facilities) {
            room.selected_floor_facilities.forEach(facilityId => {
                for (const type of Object.keys(campusFacilityEntries)) {
                    const found = campusFacilityEntries[type]?.find(e => e.id === facilityId);
                    if (found && found.area) {
                        const areaVal = parseFloat(found.area);
                        if (!isNaN(areaVal) && areaVal > 0) {
                            const areaInSqFt = convertArea(areaVal, campusAreaUnit || 'sq_ft', 'sq_ft');
                            facilityDeduction += areaInSqFt;
                        }
                        break;
                    }
                }
            });
        }
        
        const usableArea = roomArea - washroomArea - balconyArea - facilityDeduction;
        
        const totalEl = document.getElementById(`roomTotalArea-${roomId}`);
        const washroomEl = document.getElementById(`roomWashroomDeduction-${roomId}`);
        const balconyEl = document.getElementById(`roomBalconyDeduction-${roomId}`);
        const facilityEl = document.getElementById(`roomFacilityDeduction-${roomId}`);
        const usableEl = document.getElementById(`roomUsableArea-${roomId}`);
        
        if (totalEl) totalEl.textContent = roomArea.toFixed(2);
        if (washroomEl) washroomEl.textContent = washroomArea.toFixed(2);
        if (balconyEl) balconyEl.textContent = balconyArea.toFixed(2);
        if (facilityEl) facilityEl.textContent = facilityDeduction.toFixed(2);
        if (usableEl) {
            usableEl.textContent = usableArea.toFixed(2);
            usableEl.className = usableArea >= 0 ? 'positive' : 'negative';
        }
    }

    // ===== BALCONY FUNCTIONS =====
    function toggleRoomBalcony(roomId) {
        const cb = document.getElementById(`roomHasBalcony-${roomId}`);
        const container = document.getElementById(`roomBalconyContainer-${roomId}`);
        if (container) {
            container.style.display = cb.checked ? 'block' : 'none';
        }
        const room = roomDataMap[roomId];
        if (room) {
            room.has_balcony = cb.checked;
            if (!cb.checked) {
                room.balcony_area = null;
                room.balcony_units = [];
            } else if (!room.balcony_units || room.balcony_units.length === 0) {
                room.balcony_units = [{ id: generateUUID(), area: '', description: '' }];
            }
        }
        updateRoomAreaSummary(roomId);
    }

    function updateBalconyUnits(roomId) {
        const countInput = document.getElementById(`roomBalconyCount-${roomId}`);
        const count = parseInt(countInput?.value) || 1;
        const container = document.getElementById(`balcony-units-container-${roomId}`);
        const room = roomDataMap[roomId];
        if (!container || !room) return;
        const existingUnits = room.balcony_units || [];
        let newUnits = [];
        for (let i = 0; i < count; i++) {
            if (i < existingUnits.length) newUnits.push(existingUnits[i]);
            else newUnits.push({ id: generateUUID(), area: '', description: '' });
        }
        room.balcony_units = newUnits;
        let html = '';
        newUnits.forEach((unit, idx) => {
            html += `
                <div class="dynamic-card" style="padding:0.75rem;margin-bottom:0.5rem;">
                    <div class="card-badge-sm">Unit #${idx + 1}</div>
                    <div class="card-row" style="grid-template-columns:1fr 1fr;gap:0.75rem;">
                        <div class="form-group">
                            <label>Area (sq. ft.)</label>
                            <input type="number" class="form-control balcony-unit-area" min="0" step="0.01" value="${unit.area || ''}" placeholder="Area" onchange="updateBalconyUnitArea('${roomId}', ${idx}, this)">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" class="form-control balcony-unit-desc" value="${escapeHtml(unit.description || '')}" placeholder="e.g., Front balcony" onchange="updateBalconyUnitDesc('${roomId}', ${idx}, this)">
                        </div>
                    </div>
                </div>`;
        });
        container.innerHTML = html;
        updateBalconyTotalArea(roomId);
    }

    function updateBalconyUnitArea(roomId, index, input) {
        const room = roomDataMap[roomId];
        if (room && room.balcony_units && room.balcony_units[index]) {
            const val = parseFloat(input.value);
            room.balcony_units[index].area = isNaN(val) ? 0 : val;
            updateBalconyTotalArea(roomId);
        }
    }

    function updateBalconyUnitDesc(roomId, index, input) {
        const room = roomDataMap[roomId];
        if (room && room.balcony_units && room.balcony_units[index]) {
            room.balcony_units[index].description = input.value;
        }
    }

    function updateBalconyTotalArea(roomId) {
        const room = roomDataMap[roomId];
        if (!room || !room.balcony_units) return;
        let totalArea = 0;
        room.balcony_units.forEach(unit => { 
            const val = parseFloat(unit.area); 
            if (!isNaN(val)) totalArea += val; 
        });
        room.balcony_area = totalArea;
        const areaInput = document.getElementById(`roomBalconyArea-${roomId}`);
        if (areaInput) areaInput.value = totalArea.toFixed(2);
        updateRoomAreaSummary(roomId);
    }

    // ===== WASHROOM FUNCTIONS =====
    function toggleRoomWashroom(roomId) {
        const cb = document.getElementById(`roomHasWashroom-${roomId}`);
        const container = document.getElementById(`roomWashroomContainer-${roomId}`);
        if (container) {
            container.style.display = cb.checked ? 'block' : 'none';
        }
        const room = roomDataMap[roomId];
        if (room && room.room_specifications) {
            room.room_specifications.has_washroom = cb.checked;
            if (!cb.checked) {
                room.room_specifications.washroom_area = 0;
                const areaInput = document.getElementById(`roomDetailWashroomArea-${roomId}`);
                if (areaInput) areaInput.value = 0;
            }
        }
        updateRoomAreaSummary(roomId);
    }

    // ===== AMENITY FUNCTIONS =====
    function toggleRoomAmenity(roomId, amenityKey, checkbox) {
        const room = roomDataMap[roomId];
        if (!room) return;
        if (!room.selected_floor_amenities) room.selected_floor_amenities = {};
        const qtyInput = checkbox.closest('.amenity-item').querySelector('.amenity-qty');
        if (checkbox.checked) {
            room.selected_floor_amenities[amenityKey] = 1;
            if (qtyInput) { qtyInput.disabled = false; qtyInput.value = 1; }
            checkbox.closest('.amenity-item').classList.add('selected');
        } else {
            delete room.selected_floor_amenities[amenityKey];
            if (qtyInput) { qtyInput.disabled = true; qtyInput.value = 1; }
            checkbox.closest('.amenity-item').classList.remove('selected');
        }
    }

    function updateRoomAmenityQty(roomId, amenityKey, input) {
        const room = roomDataMap[roomId];
        if (!room) return;
        let val = parseInt(input.value) || 1;
        const max = parseInt(input.getAttribute('max')) || 1;
        if (val < 1) val = 1;
        if (val > max) val = max;
        input.value = val;
        if (room.selected_floor_amenities) room.selected_floor_amenities[amenityKey] = val;
    }

    function toggleRoomFacility(roomId, facilityId, checkbox) {
        const room = roomDataMap[roomId];
        if (!room) return;
        if (!room.selected_floor_facilities) room.selected_floor_facilities = [];
        if (checkbox.checked) {
            if (!room.selected_floor_facilities.includes(facilityId)) room.selected_floor_facilities.push(facilityId);
            checkbox.closest('.amenity-item').classList.add('selected');
        } else {
            room.selected_floor_facilities = room.selected_floor_facilities.filter(id => id !== facilityId);
            checkbox.closest('.amenity-item').classList.remove('selected');
        }
        updateRoomAreaSummary(roomId);
    }

    // ===== GET ROOM DATA FROM DETAIL =====
    function getRoomDataFromDetail(rid) {
        const room = roomDataMap[rid];
        if (!room) return null;
        
        const roomTypeSelect = document.getElementById(`roomTypeSelect-${rid}`);
        const customTypeInput = document.getElementById(`customRoomType-${rid}`);
        const selectedTypeId = roomTypeSelect?.value || 'classroom';
        const customType = customTypeInput?.value?.trim() || '';
        
        let roomTypeName = selectedTypeId;
        if (selectedTypeId !== 'other') {
            const foundType = roomTypeOptions.find(t => t.id === selectedTypeId);
            if (foundType) roomTypeName = foundType.name;
        } else if (customType) {
            roomTypeName = customType;
        }
        
        const getVal = (id, defaultValue = null) => { 
            const el = document.getElementById(id); 
            return el ? el.value : defaultValue; 
        };
        const getChecked = (id) => { 
            const el = document.getElementById(id); 
            return el ? el.checked : false; 
        };
        
        const hasBalcony = document.getElementById(`roomHasBalcony-${rid}`)?.checked || false;
        const balconyUnits = [];
        const unitContainers = document.querySelectorAll(`#balcony-units-container-${rid} .dynamic-card`);
        unitContainers.forEach(card => {
            const areaInput = card.querySelector('.balcony-unit-area');
            const descInput = card.querySelector('.balcony-unit-desc');
            let unitId = card.getAttribute('data-unit-id');
            if (!unitId) { unitId = generateUUID(); card.setAttribute('data-unit-id', unitId); }
            balconyUnits.push({ 
                id: unitId, 
                area: parseFloat(areaInput?.value) || 0, 
                description: descInput?.value || '' 
            });
        });
        
        const roomArea = parseFloat(getVal(`roomDetailAreaValue-${rid}`)) || null;
        const washroomArea = parseFloat(getVal(`roomDetailWashroomArea-${rid}`)) || 0;
        const hasWashroom = getChecked(`roomHasWashroom-${rid}`);
        
        const roomSpecifications = {
            capacity: parseInt(getVal(`roomDetailCapacity-${rid}`)) || null,
            min_capacity: parseInt(getVal(`roomDetailMinCapacity-${rid}`)) || null,
            occupancy_type: getVal(`roomDetailOccupancyType-${rid}`, 'single'),
            category: getVal(`roomDetailCategory-${rid}`, 'standard'),
            usage_type: getVal(`roomDetailUsageType-${rid}`, 'permanent'),
            available_from: getVal(`roomDetailAvailableFrom-${rid}`, null),
            available_to: getVal(`roomDetailAvailableTo-${rid}`, null),
            booking_required: getVal(`roomDetailBookingRequired-${rid}`, 'no'),
            area: roomArea,
            length: parseFloat(getVal(`roomDetailLength-${rid}`)) || null,
            width: parseFloat(getVal(`roomDetailWidth-${rid}`)) || null,
            height: parseFloat(getVal(`roomDetailHeight-${rid}`)) || null,
            doors: parseInt(getVal(`roomDetailDoors-${rid}`)) || 0,
            windows: parseInt(getVal(`roomDetailWindows-${rid}`)) || 0,
            door_type: getVal(`roomDetailDoorType-${rid}`, 'single'),
            window_type: getVal(`roomDetailWindowType-${rid}`, 'casement'),
            lights_count: parseInt(getVal(`roomDetailLights-${rid}`)) || 0,
            fans_count: parseInt(getVal(`roomDetailFans-${rid}`)) || 0,
            ac_count: parseInt(getVal(`roomDetailAC-${rid}`)) || 0,
            sockets_count: parseInt(getVal(`roomDetailSockets-${rid}`)) || 0,
            lighting_type: getVal(`roomDetailLightingType-${rid}`, 'led'),
            ac_type: getVal(`roomDetailACType-${rid}`, 'none'),
            has_wifi: getChecked(`roomDetailWifi-${rid}`),
            has_lan: getChecked(`roomDetailLAN-${rid}`),
            has_telephone: getChecked(`roomDetailTelephone-${rid}`),
            has_cctv: getChecked(`roomDetailCCTV-${rid}`),
            internet_speed: parseInt(getVal(`roomDetailInternetSpeed-${rid}`)) || null,
            network_type: getVal(`roomDetailNetworkType-${rid}`, 'both'),
            has_projector: getChecked(`roomDetailProjector-${rid}`),
            has_whiteboard: getChecked(`roomDetailWhiteboard-${rid}`),
            has_smart_board: getChecked(`roomDetailSmartBoard-${rid}`),
            has_desk: getChecked(`roomDetailDesk-${rid}`),
            has_chair: getChecked(`roomDetailChair-${rid}`),
            has_cabinets: getChecked(`roomDetailCabinets-${rid}`),
            has_bed: getChecked(`roomDetailBed-${rid}`),
            has_tv: getChecked(`roomDetailTV-${rid}`),
            has_kitchenette: getChecked(`roomDetailKitchenette-${rid}`),
            has_fridge: getChecked(`roomDetailFridge-${rid}`),
            has_microwave: getChecked(`roomDetailMicrowave-${rid}`),
            has_water_cooler: getChecked(`roomDetailWaterCooler-${rid}`),
            has_fire_extinguisher: getChecked(`roomDetailFireExt-${rid}`),
            has_fire_alarm: getChecked(`roomDetailFireAlarm-${rid}`),
            has_smoke_detector: getChecked(`roomDetailSmokeDet-${rid}`),
            has_sprinkler: getChecked(`roomDetailSprinkler-${rid}`),
            has_emergency_exit: getChecked(`roomDetailEmergExit-${rid}`),
            has_emergency_light: getChecked(`roomDetailEmergLight-${rid}`),
            has_door_lock: getChecked(`roomDetailDoorLock-${rid}`),
            has_smart_lock: getChecked(`roomDetailSmartLock-${rid}`),
            has_intercom: getChecked(`roomDetailIntercom-${rid}`),
            security_level: getVal(`roomDetailSecurityLevel-${rid}`, 'medium'),
            has_washroom: hasWashroom,
            washroom_capacity: hasWashroom ? (parseInt(getVal(`roomDetailWashroomCap-${rid}`)) || 1) : null,
            washroom_type: getVal(`roomDetailWashroomType-${rid}`, 'attached'),
            washroom_area: hasWashroom ? washroomArea : 0,
            has_hot_water: getChecked(`roomDetailHotWater-${rid}`),
            has_shower: getChecked(`roomDetailShower-${rid}`),
            has_bathtub: getChecked(`roomDetailBathtub-${rid}`),
            has_wheelchair_access: getChecked(`roomDetailWheelchair-${rid}`),
            has_grab_bars: getChecked(`roomDetailGrabBars-${rid}`),
            has_visual_alerts: getChecked(`roomDetailVisualAlerts-${rid}`),
            accessibility_level: getVal(`roomDetailAccessibilityLevel-${rid}`, 'none')
        };
        
        const roomNumberInput = document.getElementById(`roomDetailNumber-${rid}`);
        const roomNameInput = document.getElementById(`roomDetailName-${rid}`);
        const statusInput = document.getElementById(`roomDetailStatus-${rid}`);
        const occupancyInput = document.getElementById(`roomDetailOccupancy-${rid}`);
        const floorLevelInput = document.getElementById(`roomDetailFloorLevel-${rid}`);
        
        return {
            id: rid,
            room_number: roomNumberInput?.value?.trim() || '',
            room_name: roomNameInput?.value?.trim() || null,
            room_type: roomTypeName,
            custom_room_type: selectedTypeId === 'other' ? customType : null,
            status: statusInput?.value || 'active',
            occupancy_status: occupancyInput?.value || 'vacant',
            floor_level: floorLevelInput?.value?.trim() || null,
            selected_floor_amenities: room.selected_floor_amenities || {},
            selected_floor_facilities: room.selected_floor_facilities || [],
            has_balcony: hasBalcony,
            balcony_area: balconyUnits.reduce((sum, u) => sum + (u.area || 0), 0),
            balcony_units: balconyUnits,
            room_specifications: roomSpecifications
        };
    }

    // ===== UPDATE ROOM =====
    async function updateRoomFromDetail(rid) {
        const data = getRoomDataFromDetail(rid);
        if (!data || !data.room_number) { 
            showToast('Room number is required', 'error'); 
            return; 
        }
        const btn = event?.target;
        const orig = btn?.innerHTML || 'Update Room';
        if (btn) { btn.innerHTML = '<span class="loading-spinner"></span> Updating...'; btn.disabled = true; }
        try {
            const r = await fetch(`${API_BASE_URL}/rooms/${rid}`, {
                method: 'PUT',
                headers: { 
                    'Content-Type': 'application/json', 
                    'Accept': 'application/json', 
                    'X-CSRF-TOKEN': CSRF_TOKEN, 
                    'X-Requested-With': 'XMLHttpRequest' 
                },
                body: JSON.stringify(data)
            });
            const result = await r.json();
            if (result.success) {
                showToast('Room updated successfully!');
                roomDataMap[rid] = { ...roomDataMap[rid], ...data };
                const dropdown = document.getElementById('roomSelectorDropdown');
                const option = dropdown?.querySelector(`option[value="${rid}"]`);
                if (option) option.textContent = `${data.room_number}${data.room_name ? ' - ' + data.room_name : ''}`;
                const fi = document.getElementById('roomFloorSelect').value;
                if (fi) await loadExistingRoomsAndFloorAmenities();
                if (dropdown) { dropdown.value = rid; onRoomSelectorChange(); }
            } else { 
                showToast(result.message || 'Failed to update room', 'error'); 
            }
        } catch (e) { 
            showToast('An error occurred', 'error'); 
        } finally { 
            if (btn) { btn.innerHTML = orig; btn.disabled = false; } 
        }
    }

    // ===== SAVE ALL ROOMS =====
    async function saveAllRooms() {
        saveCurrentRoomData();
        const fi = document.getElementById('roomFloorSelect').value;
        if (!fi) { showToast('Please select a floor', 'error'); return; }
        
        const rooms = [];
        existingRoomIds.forEach(rid => {
            if (rid.toString().startsWith('new_')) {
                const data = getRoomDataFromDetail(rid);
                if (data && data.room_number) { 
                    delete data.id; 
                    rooms.push(data); 
                }
            } else {
                const data = getRoomDataFromDetail(rid);
                if (data && data.room_number) rooms.push(data);
            }
        });
        
        if (rooms.length === 0) { showToast('No rooms to save', 'error'); return; }
        
        const btn = document.getElementById('saveRoomsBtn');
        const orig = btn?.innerHTML || 'Save All Rooms';
        if (btn) { btn.innerHTML = '<span class="loading-spinner"></span> Saving...'; btn.disabled = true; }
        try {
            const r = await fetch(`${API_BASE_URL}/rooms/bulk`, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'Accept': 'application/json', 
                    'X-CSRF-TOKEN': CSRF_TOKEN, 
                    'X-Requested-With': 'XMLHttpRequest' 
                },
                body: JSON.stringify({ floor_id: fi, rooms: rooms })
            });
            const result = await r.json();
            if (result.success) {
                showToast('All rooms saved successfully!');
                await loadExistingRoomsAndFloorAmenities();
            } else { 
                showToast(result.message || 'Failed to save rooms', 'error'); 
            }
        } catch (e) { 
            showToast('An error occurred', 'error'); 
        } finally { 
            if (btn) { btn.innerHTML = orig; btn.disabled = false; } 
        }
    }

    // ===== INITIALIZATION =====
    document.addEventListener('DOMContentLoaded', function() {
        fetchRoomTypes();
    });
    </script>
</body>
</html>
@endsection