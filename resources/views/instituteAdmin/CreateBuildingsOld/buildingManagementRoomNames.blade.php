@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Room Names</title>
    <style>
        .header {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            position: relative;
            z-index: 1;
        }

        .header-content p {
            opacity: 0.9;
            font-size: 1rem;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .content-wrapper {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }

        @media (max-width: 900px) {
            .content-wrapper {
                grid-template-columns: 1fr;
            }
        }

        .form-card {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            height: fit-content;
        }

        .form-card h2 {
            color: #333;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #444;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #4361ee;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .select-with-icon {
            position: relative;
        }

        .select-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            pointer-events: none;
        }

        .input-with-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .input-with-icon input {
            padding-left: 40px;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .list-card {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .list-header h2 {
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-controls {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .filter-select {
            flex: 1;
            padding: 0.5rem;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .items-count {
            background: #4361ee;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .items-list {
            max-height: 500px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #f8f9ff;
            border-radius: 8px;
            margin-bottom: 0.75rem;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
        }

        .item-row:hover {
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .item-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
        }

        .item-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .item-details {
            flex: 1;
            min-width: 0;
        }

        .item-details h4 {
            color: #333;
            margin-bottom: 0.25rem;
            word-wrap: break-word;
        }

        .item-details small {
            color: #666;
            font-size: 0.875rem;
            display: block;
        }

        .item-stats {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
            flex-wrap: wrap;
        }

        .stat-badge {
            background: #e9ecef;
            color: #495057;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            flex-shrink: 0;
        }

        .badge-primary {
            background: #4361ee;
            color: white;
        }

        .badge-success {
            background: #28a745;
            color: white;
        }

        .badge-warning {
            background: #ffc107;
            color: #212529;
        }

        .badge-info {
            background: #17a2b8;
            color: white;
        }

        .item-actions {
            display: flex;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        .action-btn {
            padding: 0.5rem 0.75rem;
            border: none;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            flex-shrink: 0;
        }

        .edit-btn {
            background: #4361ee;
            color: white;
        }

        .edit-btn:hover {
            background: #3a56d4;
        }

        .delete-btn {
            background: #dc3545;
            color: white;
        }

        .delete-btn:hover {
            background: #c82333;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
            background: #f8f9ff;
            border-radius: 8px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #4361ee;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #666;
        }

        .room-type-badges {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
            flex-wrap: wrap;
        }

        .room-type-badge {
            background: #e9ecef;
            color: #495057;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
        }

        .room-type-badge.classroom {
            background: #4361ee;
            color: white;
        }

        .room-type-badge.lab {
            background: #f72585;
            color: white;
        }

        .room-type-badge.office {
            background: #4cc9f0;
            color: white;
        }

        .room-type-badge.washroom {
            background: #7209b7;
            color: white;
        }

        .room-type-badge.other {
            background: #f8961e;
            color: white;
        }

        .loading-spinner {
            text-align: center;
            padding: 2rem;
        }

        .loading-spinner i {
            font-size: 2rem;
            color: #4361ee;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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
            background: #dc3545;
        }

        .toast.warning {
            background: #ffc107;
            color: #212529;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            .header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
            
            .filter-controls {
                flex-direction: column;
            }
            
            .item-row {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }
            
            .item-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .item-icon {
                align-self: flex-start;
            }
            
            .item-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <h1><i class="fas fa-tag"></i> Manage Room Names</h1>
                <p>Assign descriptive names to rooms for easy identification</p>
            </div>
            <a href="{{ route('infrastructure.main') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Main Menu
            </a>
        </div>

        <!-- Stats Card -->
        <div class="stats-card">
            <h3><i class="fas fa-chart-bar"></i> Room Naming Statistics</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value" id="totalRooms">0</div>
                    <div class="stat-label">Total Rooms</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="namedRooms">0</div>
                    <div class="stat-label">Named Rooms</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="unnamedRooms">0</div>
                    <div class="stat-label">Unnamed Rooms</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="namingPercentage">0%</div>
                    <div class="stat-label">Completion Rate</div>
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            <!-- Form Card -->
            <div class="form-card">
                <h2><i class="fas fa-plus-circle"></i> 
                    <span id="formTitle">Assign Room Name</span>
                </h2>
                
                <div class="form-group">
                    <label for="buildingSelect" class="form-label">Building *</label>
                    <div class="select-with-icon">
                        <select id="buildingSelect" class="form-control" onchange="loadBlocksByBuilding()">
                            <option value="">Select Building</option>
                            @foreach($buildings as $building)
                                <option value="{{ $building->id }}">{{ $building->name }} ({{ $building->code }})</option>
                            @endforeach
                        </select>
                        <i class="fas fa-university select-icon"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="blockSelect" class="form-label">Block *</label>
                    <div class="select-with-icon">
                        <select id="blockSelect" class="form-control" onchange="loadFloorsByBlock()">
                            <option value="">Select Block</option>
                        </select>
                        <i class="fas fa-building select-icon"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="floorSelect" class="form-label">Floor *</label>
                    <div class="select-with-icon">
                        <select id="floorSelect" class="form-control" onchange="loadRoomsByFloor()">
                            <option value="">Select Floor</option>
                        </select>
                        <i class="fas fa-layer-group select-icon"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="roomSelect" class="form-label">Select Room *</label>
                    <div class="select-with-icon">
                        <select id="roomSelect" class="form-control">
                            <option value="">Select a room</option>
                        </select>
                        <i class="fas fa-door-open select-icon"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="officialName" class="form-label">Official Name *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-tag input-icon"></i>
                        <input type="text" id="officialName" class="form-control" placeholder="e.g., Principal's Office, Computer Lab-1, Main Auditorium" maxlength="255">
                    </div>
                    <small style="color: #666; display: block; margin-top: 0.25rem;">This will be the official name displayed for the room</small>
                </div>
                
                <div class="form-group">
                    <label for="alternativeName" class="form-label">Alternative Name (Optional)</label>
                    <div class="input-with-icon">
                        <i class="fas fa-tags input-icon"></i>
                        <input type="text" id="alternativeName" class="form-control" placeholder="e.g., HOD Cabin, Server Room" maxlength="255">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="purpose" class="form-label">Purpose (Optional)</label>
                    <div class="input-with-icon">
                        <i class="fas fa-bullseye input-icon"></i>
                        <input type="text" id="purpose" class="form-control" placeholder="e.g., Teaching, Laboratory Work, Administration" maxlength="500">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="department" class="form-label">Department (Optional)</label>
                    <div class="input-with-icon">
                        <i class="fas fa-graduation-cap input-icon"></i>
                        <input type="text" id="department" class="form-control" placeholder="e.g., Computer Science, Administration, Mathematics" maxlength="255">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="inChargeName" class="form-label">In-Charge Name (Optional)</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user-tie input-icon"></i>
                        <input type="text" id="inChargeName" class="form-control" placeholder="e.g., Dr. John Doe, Prof. Jane Smith" maxlength="255">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="inChargeContact" class="form-label">In-Charge Contact (Optional)</label>
                    <div class="input-with-icon">
                        <i class="fas fa-phone input-icon"></i>
                        <input type="text" id="inChargeContact" class="form-control" placeholder="e.g., 9876543210, johndoe@example.com" maxlength="50">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="specialNotes" class="form-label">Special Notes (Optional)</label>
                    <textarea id="specialNotes" class="form-control" rows="3" placeholder="Any special features, equipment, or other important notes..."></textarea>
                </div>
                
                <!-- Hidden fields -->
                <input type="hidden" id="currentRoomNameId" value="">
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()" id="resetBtn">
                        <i class="fas fa-redo"></i> Clear
                    </button>
                    <button type="button" class="btn btn-primary" onclick="saveRoomName()" id="saveBtn">
                        <i class="fas fa-tag"></i> Assign Name
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="cancelEdit()" id="cancelBtn" style="display: none;">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </div>

            <!-- List Card -->
            <div class="list-card">
                <div class="list-header">
                    <h2><i class="fas fa-list"></i> Named Rooms</h2>
                    <div class="items-count" id="namedRoomsCount">0 items</div>
                </div>
                
                <!-- Filter Controls -->
                <div class="filter-controls">
                    <select id="buildingFilter" class="filter-select" onchange="filterNamedRooms()">
                        <option value="">All Buildings</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}">{{ $building->name }}</option>
                        @endforeach
                    </select>
                    <select id="departmentFilter" class="filter-select" onchange="filterNamedRooms()">
                        <option value="">All Departments</option>
                    </select>
                    <input type="text" id="searchInput" class="filter-select" placeholder="Search room names..." oninput="searchNamedRooms()">
                </div>
                
                <div class="items-list" id="roomNamesList">
                    <!-- Named rooms will be loaded here -->
                    <div class="loading-spinner" id="loadingSpinner">
                        <i class="fas fa-spinner"></i>
                        <p>Loading room names...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Card -->
        <div class="stats-card" style="margin-top: 2rem;">
            <h3><i class="fas fa-check-circle"></i> Configuration Complete!</h3>
            <p style="margin: 1rem 0; color: #666;">You have completed all steps of building infrastructure configuration.</p>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <a href="{{ route('infrastructure.main') }}" class="btn btn-success" style="text-decoration: none;">
                    <i class="fas fa-home"></i> Back to Main Menu
                </a>
                <a href="{{ route('summary.page') }}" class="btn btn-primary" style="text-decoration: none;">
                    <i class="fas fa-chart-bar"></i> View Complete Summary
                </a>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message">Operation successful!</span>
    </div>

    <script>
        const API_BASE_URL = '{{ url('/api') }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';
        
        let currentEditingRoomName = null;

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadNamedRoomsList();
            updateStatistics();
            
            // Add event listeners for filters
            document.getElementById('buildingFilter').addEventListener('change', function() {
                loadDepartmentsByBuilding();
            });
        });

        // Load blocks by building
        async function loadBlocksByBuilding() {
            const buildingId = document.getElementById('buildingSelect').value;
            const blockSelect = document.getElementById('blockSelect');
            const floorSelect = document.getElementById('floorSelect');
            const roomSelect = document.getElementById('roomSelect');
            
            if (!buildingId) {
                blockSelect.innerHTML = '<option value="">Select Block</option>';
                floorSelect.innerHTML = '<option value="">Select Floor</option>';
                roomSelect.innerHTML = '<option value="">Select Room</option>';
                return;
            }
            
            try {
                const response = await fetch(`${API_BASE_URL}/floors/blocks-by-building/${buildingId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    let options = '<option value="">Select Block</option>';
                    result.data.forEach(block => {
                        options += `<option value="${block.id}">${block.name} ${block.code ? `(${block.code})` : ''}</option>`;
                    });
                    blockSelect.innerHTML = options;
                    
                    // Reset dependent selects
                    floorSelect.innerHTML = '<option value="">Select Floor</option>';
                    roomSelect.innerHTML = '<option value="">Select Room</option>';
                }
            } catch (error) {
                console.error('Error loading blocks:', error);
                showToast('Failed to load blocks', 'error');
            }
        }

        // Load floors by block
        async function loadFloorsByBlock() {
            const blockId = document.getElementById('blockSelect').value;
            const floorSelect = document.getElementById('floorSelect');
            const roomSelect = document.getElementById('roomSelect');
            
            if (!blockId) {
                floorSelect.innerHTML = '<option value="">Select Floor</option>';
                roomSelect.innerHTML = '<option value="">Select Room</option>';
                return;
            }
            
            try {
                const response = await fetch(`${API_BASE_URL}/floors/by-block/${blockId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    let options = '<option value="">Select Floor</option>';
                    result.data.forEach(floor => {
                        options += `<option value="${floor.id}">${floor.floor_number} ${floor.floor_name ? `- ${floor.floor_name}` : ''}</option>`;
                    });
                    floorSelect.innerHTML = options;
                    
                    // Reset room select
                    roomSelect.innerHTML = '<option value="">Select Room</option>';
                }
            } catch (error) {
                console.error('Error loading floors:', error);
                showToast('Failed to load floors', 'error');
            }
        }

        // Load rooms by floor
        async function loadRoomsByFloor() {
            const floorId = document.getElementById('floorSelect').value;
            const roomSelect = document.getElementById('roomSelect');
            
            if (!floorId) {
                roomSelect.innerHTML = '<option value="">Select Room</option>';
                return;
            }
            
            try {
                const response = await fetch(`${API_BASE_URL}/room-names/rooms-by-floor/${floorId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    let options = '<option value="">Select Room</option>';
                    result.data.forEach(room => {
                        const roomType = getRoomTypeName(room.room_type);
                        options += `<option value="${room.id}">Room ${room.room_number} (${roomType})</option>`;
                    });
                    roomSelect.innerHTML = options;
                }
            } catch (error) {
                console.error('Error loading rooms:', error);
                showToast('Failed to load rooms', 'error');
            }
        }

        // Load departments by building for filter
        async function loadDepartmentsByBuilding() {
            const buildingId = document.getElementById('buildingFilter').value;
            const departmentFilter = document.getElementById('departmentFilter');
            
            if (!buildingId) {
                departmentFilter.innerHTML = '<option value="">All Departments</option>';
                return;
            }
            
            try {
                const response = await fetch(`${API_BASE_URL}/room-names/departments-by-building/${buildingId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    let options = '<option value="">All Departments</option>';
                    result.data.forEach(department => {
                        options += `<option value="${department}">${department}</option>`;
                    });
                    departmentFilter.innerHTML = options;
                }
            } catch (error) {
                console.error('Error loading departments:', error);
                departmentFilter.innerHTML = '<option value="">All Departments</option>';
            }
        }

        // Get room type name
        function getRoomTypeName(type) {
            const names = {
                'classroom': 'Classroom',
                'lab': 'Laboratory',
                'office': 'Office',
                'washroom': 'Washroom',
                'other': 'Other'
            };
            return names[type] || 'Room';
        }

        // Save room name (create or update)
        async function saveRoomName() {
            const roomId = document.getElementById('roomSelect').value;
            const officialName = document.getElementById('officialName').value.trim();
            const alternativeName = document.getElementById('alternativeName').value.trim();
            const purpose = document.getElementById('purpose').value.trim();
            const department = document.getElementById('department').value.trim();
            const inChargeName = document.getElementById('inChargeName').value.trim();
            const inChargeContact = document.getElementById('inChargeContact').value.trim();
            const specialNotes = document.getElementById('specialNotes').value.trim();
            const roomNameId = document.getElementById('currentRoomNameId').value;

            // Validation
            if (!roomId) {
                showToast('Please select a room', 'error');
                return;
            }

            if (!officialName) {
                showToast('Please enter an official name for the room', 'error');
                return;
            }

            // Collect form data
            const formData = new FormData();
            formData.append('room_id', roomId);
            formData.append('official_name', officialName);
            
            if (alternativeName) formData.append('alternative_name', alternativeName);
            if (purpose) formData.append('purpose', purpose);
            if (department) formData.append('department', department);
            if (inChargeName) formData.append('in_charge_name', inChargeName);
            if (inChargeContact) formData.append('in_charge_contact', inChargeContact);
            if (specialNotes) formData.append('special_notes', specialNotes);
            
            formData.append('_token', CSRF_TOKEN);

            try {
                let response;
                if (roomNameId) {
                    // Update existing room name
                    formData.append('_method', 'PUT');
                    response = await fetch(`${API_BASE_URL}/room-names/${roomNameId}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                } else {
                    // Create new room name
                    response = await fetch(`${API_BASE_URL}/room-names`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                }

                const result = await response.json();

                if (result.success) {
                    showToast(result.message);
                    resetForm();
                    loadNamedRoomsList();
                    updateStatistics();
                    // Refresh room select to exclude newly named room
                    await loadRoomsByFloor();
                } else {
                    if (result.errors) {
                        // Display validation errors
                        let errorMessage = 'Validation failed:\n';
                        Object.values(result.errors).forEach(errors => {
                            errorMessage += errors.join('\n') + '\n';
                        });
                        showToast(errorMessage, 'error');
                    } else {
                        showToast(result.message || 'Operation failed', 'error');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred. Please try again.', 'error');
            }
        }

        // Load named rooms list
        async function loadNamedRoomsList() {
            try {
                document.getElementById('loadingSpinner').style.display = 'block';
                
                const response = await fetch(`${API_BASE_URL}/room-names/named-rooms?per_page=100`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                document.getElementById('loadingSpinner').style.display = 'none';
                
                if (result.success) {
                    displayNamedRooms(result.data.data || result.data);
                } else {
                    console.error('Failed to load named rooms');
                    showToast('Failed to load named rooms', 'error');
                }
            } catch (error) {
                console.error('Error loading named rooms:', error);
                document.getElementById('loadingSpinner').style.display = 'none';
                showToast('Failed to load named rooms', 'error');
            }
        }

        // Search named rooms
        async function searchNamedRooms() {
            const searchTerm = document.getElementById('searchInput').value;
            
            if (!searchTerm.trim()) {
                loadNamedRoomsList();
                return;
            }

            try {
                document.getElementById('loadingSpinner').style.display = 'block';
                
                const response = await fetch(`${API_BASE_URL}/room-names/search?search=${encodeURIComponent(searchTerm)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                document.getElementById('loadingSpinner').style.display = 'none';
                
                if (result.success) {
                    displayNamedRooms(result.data.data || result.data);
                }
            } catch (error) {
                console.error('Error searching named rooms:', error);
                document.getElementById('loadingSpinner').style.display = 'none';
            }
        }

        // Filter named rooms
        async function filterNamedRooms() {
            const buildingId = document.getElementById('buildingFilter').value;
            const department = document.getElementById('departmentFilter').value;
            
            let url = `${API_BASE_URL}/room-names/named-rooms?per_page=100`;
            
            if (buildingId) url += `&building_id=${buildingId}`;
            if (department) url += `&department=${encodeURIComponent(department)}`;
            
            try {
                document.getElementById('loadingSpinner').style.display = 'block';
                
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                document.getElementById('loadingSpinner').style.display = 'none';
                
                if (result.success) {
                    displayNamedRooms(result.data.data || result.data);
                }
            } catch (error) {
                console.error('Error filtering named rooms:', error);
                document.getElementById('loadingSpinner').style.display = 'none';
            }
        }

        // Display named rooms in list
        function displayNamedRooms(roomNames) {
            const roomNamesList = document.getElementById('roomNamesList');
            const namedRoomsCount = document.getElementById('namedRoomsCount');
            
            if (!roomNames || roomNames.length === 0) {
                roomNamesList.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-tag"></i>
                        <p>No rooms named yet</p>
                        <small>Assign names to rooms using the form</small>
                    </div>
                `;
                namedRoomsCount.textContent = '0 items';
                return;
            }
            
            namedRoomsCount.textContent = `${roomNames.length} ${roomNames.length === 1 ? 'item' : 'items'}`;
            
            const html = roomNames.map(roomName => {
                const room = roomName.room;
                const building = room?.building;
                const block = room?.block;
                const floor = room?.floor;
                
                return `
                    <div class="item-row" id="roomname-${roomName.id}">
                        <div class="item-info">
                            <div class="item-icon">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="item-details">
                                <h4>${roomName.official_name}</h4>
                                <small>
                                    <i class="fas fa-university"></i> ${building?.name || 'Unknown Building'} → 
                                    <i class="fas fa-building"></i> ${block?.name || 'Unknown Block'} → 
                                    <i class="fas fa-layer-group"></i> ${floor?.floor_number || 'Unknown Floor'} → 
                                    <i class="fas fa-door-open"></i> Room ${room?.room_number || 'Unknown'}
                                </small>
                                <div class="room-type-badges">
                                    <span class="room-type-badge ${room?.room_type || 'other'}">
                                        ${getRoomTypeName(room?.room_type)}
                                    </span>
                                    ${roomName.purpose ? `<span class="stat-badge badge-info">🎯 ${roomName.purpose}</span>` : ''}
                                    ${roomName.department ? `<span class="stat-badge badge-success">🏢 ${roomName.department}</span>` : ''}
                                    ${roomName.in_charge_name ? `<span class="stat-badge badge-warning">👤 ${roomName.in_charge_name}</span>` : ''}
                                </div>
                                ${roomName.alternative_name ? `<small style="margin-top: 0.5rem; color: #666;">Also known as: ${roomName.alternative_name}</small>` : ''}
                                ${roomName.special_notes ? `<small style="margin-top: 0.5rem; color: #666; display: block;">${roomName.special_notes}</small>` : ''}
                            </div>
                        </div>
                        <div class="item-actions">
                            <button class="action-btn edit-btn" onclick="editNamedRoom(${roomName.id})">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="action-btn delete-btn" onclick="deleteNamedRoom(${roomName.id}, '${roomName.official_name.replace(/'/g, "\\'")}')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
            
            roomNamesList.innerHTML = html;
        }

        // Edit named room
        async function editNamedRoom(id) {
            try {
                const response = await fetch(`${API_BASE_URL}/room-names/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    const roomName = result.data;
                    currentEditingRoomName = roomName;
                    
                    // Fill form with room name data
                    document.getElementById('officialName').value = roomName.official_name;
                    document.getElementById('alternativeName').value = roomName.alternative_name || '';
                    document.getElementById('purpose').value = roomName.purpose || '';
                    document.getElementById('department').value = roomName.department || '';
                    document.getElementById('inChargeName').value = roomName.in_charge_name || '';
                    document.getElementById('inChargeContact').value = roomName.in_charge_contact || '';
                    document.getElementById('specialNotes').value = roomName.special_notes || '';
                    document.getElementById('currentRoomNameId').value = roomName.id;
                    
                    // Load building, block, floor, and room
                    if (roomName.room) {
                        // Set building
                        document.getElementById('buildingSelect').value = roomName.room.building_id;
                        
                        // Load blocks for the building
                        await loadBlocksByBuilding();
                        
                        // Set block after a short delay
                        setTimeout(() => {
                            document.getElementById('blockSelect').value = roomName.room.block_id;
                            
                            // Load floors for the block
                            loadFloorsByBlock().then(() => {
                                setTimeout(() => {
                                    document.getElementById('floorSelect').value = roomName.room.floor_id;
                                    
                                    // Load rooms for the floor
                                    loadRoomsByFloor().then(() => {
                                        setTimeout(() => {
                                            document.getElementById('roomSelect').value = roomName.room_id;
                                        }, 300);
                                    });
                                }, 300);
                            });
                        }, 300);
                    }
                    
                    // Update form UI for edit mode
                    document.getElementById('formTitle').textContent = 'Edit Room Name';
                    document.getElementById('saveBtn').innerHTML = '<i class="fas fa-save"></i> Update Name';
                    document.getElementById('cancelBtn').style.display = 'flex';
                    document.getElementById('resetBtn').style.display = 'none';
                    
                    // Scroll to form
                    document.getElementById('buildingSelect').focus();
                }
            } catch (error) {
                console.error('Error loading room name:', error);
                showToast('Failed to load room name details', 'error');
            }
        }

        // Delete named room
        async function deleteNamedRoom(id, name) {
            if (confirm(`Are you sure you want to delete "${name}"?\n\nThis will remove the name assignment but keep the room.`)) {
                try {
                    const response = await fetch(`${API_BASE_URL}/room-names/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        showToast(result.message);
                        loadNamedRoomsList();
                        updateStatistics();
                        
                        // If we were editing the deleted room name, reset the form
                        if (currentEditingRoomName && currentEditingRoomName.id === id) {
                            cancelEdit();
                        }
                        
                        // Refresh room select to include the now-unnamed room
                        await loadRoomsByFloor();
                    } else {
                        showToast(result.message || 'Failed to delete room name', 'error');
                    }
                } catch (error) {
                    console.error('Error deleting room name:', error);
                    showToast('Failed to delete room name', 'error');
                }
            }
        }

        // Cancel edit mode
        function cancelEdit() {
            resetForm();
            currentEditingRoomName = null;
        }

        // Reset form
        function resetForm() {
            document.getElementById('buildingSelect').value = '';
            document.getElementById('blockSelect').innerHTML = '<option value="">Select Block</option>';
            document.getElementById('floorSelect').innerHTML = '<option value="">Select Floor</option>';
            document.getElementById('roomSelect').innerHTML = '<option value="">Select Room</option>';
            document.getElementById('officialName').value = '';
            document.getElementById('alternativeName').value = '';
            document.getElementById('purpose').value = '';
            document.getElementById('department').value = '';
            document.getElementById('inChargeName').value = '';
            document.getElementById('inChargeContact').value = '';
            document.getElementById('specialNotes').value = '';
            document.getElementById('currentRoomNameId').value = '';
            
            // Reset form UI
            document.getElementById('formTitle').textContent = 'Assign Room Name';
            document.getElementById('saveBtn').innerHTML = '<i class="fas fa-tag"></i> Assign Name';
            document.getElementById('cancelBtn').style.display = 'none';
            document.getElementById('resetBtn').style.display = 'flex';
            
            document.getElementById('buildingSelect').focus();
        }

        // Update statistics
        async function updateStatistics() {
            try {
                const response = await fetch(`${API_BASE_URL}/room-names/naming-statistics`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    const stats = result.data;
                    document.getElementById('totalRooms').textContent = stats.total_rooms;
                    document.getElementById('namedRooms').textContent = stats.named_rooms;
                    document.getElementById('unnamedRooms').textContent = stats.unnamed_rooms;
                    document.getElementById('namingPercentage').textContent = stats.naming_percentage + '%';
                }
            } catch (error) {
                console.error('Error loading statistics:', error);
            }
        }

        // Show toast notification
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            
            toastMessage.textContent = message;
            
            // Remove all existing classes
            toast.className = 'toast';
            
            if (type === 'error') {
                toast.classList.add('error');
                toast.querySelector('i').className = 'fas fa-exclamation-circle';
            } else if (type === 'warning') {
                toast.classList.add('warning');
                toast.querySelector('i').className = 'fas fa-exclamation-triangle';
            } else {
                toast.querySelector('i').className = 'fas fa-check-circle';
            }
            
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
    </script>
</body>
</html>
@endsection