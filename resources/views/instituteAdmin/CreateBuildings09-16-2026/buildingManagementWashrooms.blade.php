@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Washrooms</title>
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
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
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
            padding: 0.75px;
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
        }

        .item-details h4 {
            color: #333;
            margin-bottom: 0.25rem;
        }

        .item-details small {
            color: #666;
            font-size: 0.875rem;
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

        .item-actions {
            display: flex;
            gap: 0.5rem;
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

        .washroom-type-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .washroom-type-option {
            padding: 1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .washroom-type-option:hover {
            border-color: #4361ee;
            background: #f8f9ff;
        }

        .washroom-type-option.selected {
            background: #4361ee;
            color: white;
            border-color: #4361ee;
        }

        .washroom-type-option i {
            font-size: 1.5rem;
        }

        .gender-selector {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .gender-option {
            padding: 0.5rem;
            border: 2px solid #ddd;
            border-radius: 6px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .gender-option:hover {
            border-color: #4361ee;
        }

        .gender-option.selected {
            background: #4361ee;
            color: white;
            border-color: #4361ee;
        }

        .dynamic-select-group {
            display: none;
        }

        .dynamic-select-group.active {
            display: block;
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
            
            .washroom-type-selector {
                grid-template-columns: 1fr;
            }
            
            .gender-selector {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .item-row {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
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
                <h1><i class="fas fa-toilet"></i> Manage Washrooms</h1>
                <p>Add and manage washrooms on floors and attached to rooms</p>
            </div>
            <a href="{{ route('building.main') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Main Menu
            </a>
        </div>

        <!-- Stats Card -->
        <div class="stats-card">
            <h3><i class="fas fa-chart-bar"></i> Washroom Statistics</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value" id="totalWashrooms">0</div>
                    <div class="stat-label">Total Washrooms</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="floorWashrooms">0</div>
                    <div class="stat-label">Floor Washrooms</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="attachedWashrooms">0</div>
                    <div class="stat-label">Attached Washrooms</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="roomsWithWashrooms">0</div>
                    <div class="stat-label">Rooms with Washrooms</div>
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            <!-- Form Card -->
            <div class="form-card">
                <h2><i class="fas fa-plus-circle"></i> Add New Washroom</h2>
                
                <div class="washroom-type-selector">
                    <div class="washroom-type-option selected" onclick="selectWashroomType('floor')">
                        <i class="fas fa-building"></i>
                        <div>Floor Washroom</div>
                        <small>Common washroom on a floor</small>
                    </div>
                    <div class="washroom-type-option" onclick="selectWashroomType('attached')">
                        <i class="fas fa-door-closed"></i>
                        <div>Attached Washroom</div>
                        <small>Private washroom in a room</small>
                    </div>
                </div>
                <input type="hidden" id="washroomType" value="floor">
                
                <!-- Floor Washroom Fields -->
                <div class="dynamic-select-group active" id="floorWashroomGroup">
                    <div class="form-group">
                        <label for="floorSelect" class="form-label">Select Floor *</label>
                        <div class="select-with-icon">
                            <select id="floorSelect" class="form-control">
                                <option value="">Select a floor</option>
                            </select>
                            <i class="fas fa-layer-group select-icon"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Attached Washroom Fields -->
                <div class="dynamic-select-group" id="attachedWashroomGroup">
                    <div class="form-group">
                        <label for="roomSelect" class="form-label">Select Room *</label>
                        <div class="select-with-icon">
                            <select id="roomSelect" class="form-control">
                                <option value="">Select a room</option>
                            </select>
                            <i class="fas fa-door-open select-icon"></i>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="washroomName" class="form-label">Washroom Name *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-toilet input-icon"></i>
                        <input type="text" id="washroomName" class="form-control" placeholder="e.g., Gents-1, Ladies-1, Washroom-A" maxlength="50">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Gender Type</label>
                    <div class="gender-selector">
                        <div class="gender-option selected" onclick="selectGender('male')">
                            <i class="fas fa-male"></i>
                            <div>Male</div>
                        </div>
                        <div class="gender-option" onclick="selectGender('female')">
                            <i class="fas fa-female"></i>
                            <div>Female</div>
                        </div>
                        <div class="gender-option" onclick="selectGender('unisex')">
                            <i class="fas fa-restroom"></i>
                            <div>Unisex</div>
                        </div>
                    </div>
                    <input type="hidden" id="genderType" value="male">
                </div>
                
                <div class="form-group">
                    <label for="capacity" class="form-label">Capacity (Optional)</label>
                    <div class="input-with-icon">
                        <i class="fas fa-users input-icon"></i>
                        <input type="number" id="capacity" class="form-control" placeholder="e.g., 5" min="1" max="50">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="washroomDescription" class="form-label">Description (Optional)</label>
                    <textarea id="washroomDescription" class="form-control" rows="3" placeholder="Brief description or special features..."></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-redo"></i> Clear
                    </button>
                    <button type="button" class="btn btn-primary" onclick="addWashroom()">
                        <i class="fas fa-plus"></i> Add Washroom
                    </button>
                </div>
            </div>

            <!-- List Card -->
            <div class="list-card">
                <div class="list-header">
                    <h2><i class="fas fa-list"></i> All Washrooms</h2>
                    <div class="items-count" id="washroomsCount">0 items</div>
                </div>
                
                <div class="filter-controls">
                    <select id="typeFilter" class="filter-select" onchange="filterWashrooms()">
                        <option value="">All Types</option>
                        <option value="floor">Floor Washrooms</option>
                        <option value="attached">Attached Washrooms</option>
                    </select>
                    <select id="genderFilter" class="filter-select" onchange="filterWashrooms()">
                        <option value="">All Genders</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unisex">Unisex</option>
                    </select>
                    <input type="text" id="searchInput" class="filter-select" placeholder="Search washrooms..." oninput="filterWashrooms()">
                </div>
                
                <div class="items-list" id="washroomsList">
                    <!-- Washrooms will be listed here -->
                </div>
            </div>
        </div>

        <!-- Navigation Card -->
        <div class="stats-card" style="margin-top: 2rem;">
            <h3><i class="fas fa-arrow-right"></i> Next Steps</h3>
            <p style="margin: 1rem 0; color: #666;">After adding washrooms, proceed to add lifts/elevators.</p>
            <a href="{{ route('lifts.page') }}" class="btn btn-success" style="text-decoration: none;">
                <i class="fas fa-arrow-right"></i> Go to Lifts Management
            </a>
        </div>
    </div>

    <script>
        // Load building data
        let buildingData = JSON.parse(localStorage.getItem('buildingInfrastructure')) || {
            blocks: [],
            floors: [],
            rooms: [],
            washrooms: [],
            lifts: [],
            roomNames: []
        };

        let selectedWashroomType = 'floor';
        let selectedGender = 'male';

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            populateFloorSelect();
            populateRoomSelect();
            loadWashroomsList();
            updateStatistics();
        });

        // Populate floor select
        function populateFloorSelect() {
            const floorSelect = document.getElementById('floorSelect');
            
            let options = '<option value="">Select a floor</option>';
            buildingData.floors.forEach(floor => {
                const block = buildingData.blocks.find(b => b.id === floor.blockId);
                options += `<option value="${floor.id}">${block ? block.name + ' - ' : ''}${floor.number}</option>`;
            });
            
            floorSelect.innerHTML = options;
        }

        // Populate room select
        function populateRoomSelect() {
            const roomSelect = document.getElementById('roomSelect');
            
            let options = '<option value="">Select a room</option>';
            buildingData.rooms.forEach(room => {
                const floor = buildingData.floors.find(f => f.id === room.floorId);
                const block = floor ? buildingData.blocks.find(b => b.id === floor.blockId) : null;
                options += `<option value="${room.id}">${block ? block.name + ' - ' : ''}${floor ? floor.number + ' - ' : ''}Room ${room.number}</option>`;
            });
            
            roomSelect.innerHTML = options;
        }

        // Select washroom type
        function selectWashroomType(type) {
            selectedWashroomType = type;
            document.getElementById('washroomType').value = type;
            
            // Update UI
            document.querySelectorAll('.washroom-type-option').forEach(option => {
                option.classList.remove('selected');
            });
            event.target.closest('.washroom-type-option').classList.add('selected');
            
            // Show/hide appropriate fields
            if (type === 'floor') {
                document.getElementById('floorWashroomGroup').classList.add('active');
                document.getElementById('attachedWashroomGroup').classList.remove('active');
            } else {
                document.getElementById('floorWashroomGroup').classList.remove('active');
                document.getElementById('attachedWashroomGroup').classList.add('active');
            }
        }

        // Select gender
        function selectGender(gender) {
            selectedGender = gender;
            document.getElementById('genderType').value = gender;
            
            // Update UI
            document.querySelectorAll('.gender-option').forEach(option => {
                option.classList.remove('selected');
            });
            event.target.closest('.gender-option').classList.add('selected');
        }

        // Add washroom
        function addWashroom() {
            const washroomType = selectedWashroomType;
            const floorId = washroomType === 'floor' ? parseInt(document.getElementById('floorSelect').value) : null;
            const roomId = washroomType === 'attached' ? parseInt(document.getElementById('roomSelect').value) : null;
            const washroomName = document.getElementById('washroomName').value.trim();
            const gender = selectedGender;
            const capacity = document.getElementById('capacity').value ? parseInt(document.getElementById('capacity').value) : null;
            const description = document.getElementById('washroomDescription').value.trim();
            
            if (washroomType === 'floor' && !floorId) {
                alert('Please select a floor');
                return;
            }
            
            if (washroomType === 'attached' && !roomId) {
                alert('Please select a room');
                return;
            }
            
            if (!washroomName) {
                alert('Please enter a washroom name');
                return;
            }
            
            // Check for duplicates
            if (washroomType === 'floor') {
                if (buildingData.washrooms.find(w => w.type === 'floor' && w.floorId === floorId && w.name.toLowerCase() === washroomName.toLowerCase())) {
                    alert('Washroom with this name already exists on this floor');
                    return;
                }
            } else {
                if (buildingData.washrooms.find(w => w.type === 'attached' && w.roomId === roomId && w.name.toLowerCase() === washroomName.toLowerCase())) {
                    alert('Washroom with this name already exists in this room');
                    return;
                }
            }
            
            const washroom = {
                id: Date.now(),
                type: washroomType,
                floorId: floorId,
                roomId: roomId,
                name: washroomName,
                gender: gender,
                capacity: capacity,
                description: description || null,
                createdAt: new Date().toISOString(),
                updatedAt: new Date().toISOString()
            };
            
            // Set location information
            if (washroomType === 'floor' && floorId) {
                const floor = buildingData.floors.find(f => f.id === floorId);
                const block = floor ? buildingData.blocks.find(b => b.id === floor.blockId) : null;
                washroom.location = `${block ? block.name : ''} - ${floor ? floor.number : ''}`;
            } else if (washroomType === 'attached' && roomId) {
                const room = buildingData.rooms.find(r => r.id === roomId);
                const floor = room ? buildingData.floors.find(f => f.id === room.floorId) : null;
                const block = floor ? buildingData.blocks.find(b => b.id === floor.blockId) : null;
                washroom.location = `${block ? block.name : ''} - ${floor ? floor.number : ''} - Room ${room ? room.number : ''}`;
            }
            
            buildingData.washrooms.push(washroom);
            saveData();
            loadWashroomsList();
            updateStatistics();
            resetForm();
            
            alert('Washroom added successfully!');
        }

        // Load washrooms list
        function loadWashroomsList() {
            const washroomsList = document.getElementById('washroomsList');
            const washroomsCount = document.getElementById('washroomsCount');
            
            // Sort washrooms by type and name
            const sortedWashrooms = [...buildingData.washrooms].sort((a, b) => {
                if (a.type !== b.type) return a.type.localeCompare(b.type);
                if (a.location !== b.location) return a.location.localeCompare(b.location);
                return a.name.localeCompare(b.name);
            });
            
            if (sortedWashrooms.length === 0) {
                washroomsList.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-toilet"></i>
                        <p>No washrooms added yet</p>
                        <small>Add your first washroom using the form</small>
                    </div>
                `;
                washroomsCount.textContent = '0 items';
                return;
            }
            
            washroomsList.innerHTML = sortedWashrooms.map(washroom => `
                <div class="item-row" id="washroom-${washroom.id}">
                    <div class="item-info">
                        <div class="item-icon" style="background: ${getGenderColor(washroom.gender)};">
                            <i class="${getGenderIcon(washroom.gender)}"></i>
                        </div>
                        <div class="item-details">
                            <h4>${washroom.name} 
                                <span style="color: #666; font-weight: normal;">- ${washroom.type === 'floor' ? 'Floor Washroom' : 'Attached Washroom'}</span>
                            </h4>
                            <small>${washroom.location}</small>
                            <div class="item-stats">
                                <span class="stat-badge ${washroom.type === 'floor' ? 'badge-primary' : 'badge-success'}">
                                    <i class="${washroom.type === 'floor' ? 'fas fa-building' : 'fas fa-door-closed'}"></i>
                                    ${washroom.type === 'floor' ? 'Floor' : 'Attached'}
                                </span>
                                <span class="stat-badge ${getGenderBadgeClass(washroom.gender)}">
                                    <i class="${getGenderIcon(washroom.gender)}"></i>
                                    ${getGenderName(washroom.gender)}
                                </span>
                                ${washroom.capacity ? `
                                <span class="stat-badge">
                                    <i class="fas fa-users"></i> Capacity: ${washroom.capacity}
                                </span>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                    <div class="item-actions">
                        <button class="action-btn edit-btn" onclick="editWashroom(${washroom.id})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="action-btn delete-btn" onclick="deleteWashroom(${washroom.id})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            `).join('');
            
            washroomsCount.textContent = `${sortedWashrooms.length} ${sortedWashrooms.length === 1 ? 'item' : 'items'}`;
        }

        // Filter washrooms
        function filterWashrooms() {
            const typeFilter = document.getElementById('typeFilter').value;
            const genderFilter = document.getElementById('genderFilter').value;
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            
            const filteredWashrooms = buildingData.washrooms.filter(washroom => {
                const matchesType = !typeFilter || washroom.type === typeFilter;
                const matchesGender = !genderFilter || washroom.gender === genderFilter;
                const matchesSearch = !searchTerm || 
                    washroom.name.toLowerCase().includes(searchTerm) ||
                    (washroom.location && washroom.location.toLowerCase().includes(searchTerm)) ||
                    (washroom.description && washroom.description.toLowerCase().includes(searchTerm));
                
                return matchesType && matchesGender && matchesSearch;
            });
            
            const washroomsList = document.getElementById('washroomsList');
            
            if (filteredWashrooms.length === 0) {
                washroomsList.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <p>No washrooms found</p>
                        <small>Try changing your search or filter criteria</small>
                    </div>
                `;
                return;
            }
            
            washroomsList.innerHTML = filteredWashrooms.map(washroom => `
                <div class="item-row" id="washroom-${washroom.id}">
                    <div class="item-info">
                        <div class="item-icon" style="background: ${getGenderColor(washroom.gender)};">
                            <i class="${getGenderIcon(washroom.gender)}"></i>
                        </div>
                        <div class="item-details">
                            <h4>${washroom.name} 
                                <span style="color: #666; font-weight: normal;">- ${washroom.type === 'floor' ? 'Floor Washroom' : 'Attached Washroom'}</span>
                            </h4>
                            <small>${washroom.location}</small>
                            <div class="item-stats">
                                <span class="stat-badge ${washroom.type === 'floor' ? 'badge-primary' : 'badge-success'}">
                                    <i class="${washroom.type === 'floor' ? 'fas fa-building' : 'fas fa-door-closed'}"></i>
                                    ${washroom.type === 'floor' ? 'Floor' : 'Attached'}
                                </span>
                                <span class="stat-badge ${getGenderBadgeClass(washroom.gender)}">
                                    <i class="${getGenderIcon(washroom.gender)}"></i>
                                    ${getGenderName(washroom.gender)}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="item-actions">
                        <button class="action-btn edit-btn" onclick="editWashroom(${washroom.id})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="action-btn delete-btn" onclick="deleteWashroom(${washroom.id})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // Get gender icon
        function getGenderIcon(gender) {
            const icons = {
                'male': 'fas fa-male',
                'female': 'fas fa-female',
                'unisex': 'fas fa-restroom'
            };
            return icons[gender] || 'fas fa-toilet';
        }

        // Get gender color
        function getGenderColor(gender) {
            const colors = {
                'male': '#4361ee',
                'female': '#f72585',
                'unisex': '#7209b7'
            };
            return colors[gender] || '#4361ee';
        }

        // Get gender badge class
        function getGenderBadgeClass(gender) {
            const classes = {
                'male': 'badge-primary',
                'female': 'badge-warning',
                'unisex': 'badge-success'
            };
            return classes[gender] || '';
        }

        // Get gender name
        function getGenderName(gender) {
            const names = {
                'male': 'Male',
                'female': 'Female',
                'unisex': 'Unisex'
            };
            return names[gender] || 'Unknown';
        }

        // Edit washroom
        function editWashroom(id) {
            const washroom = buildingData.washrooms.find(w => w.id === id);
            if (!washroom) return;
            
            const newName = prompt('Edit washroom name:', washroom.name);
            if (!newName || !newName.trim()) return;
            
            const newDescription = prompt('Edit description (optional):', washroom.description || '');
            const newCapacity = prompt('Edit capacity (optional, leave empty for none):', washroom.capacity || '');
            
            // Check for duplicates (excluding current washroom)
            if (buildingData.washrooms.find(w => w.id !== id && 
                w.type === washroom.type && 
                ((w.type === 'floor' && w.floorId === washroom.floorId) || 
                 (w.type === 'attached' && w.roomId === washroom.roomId)) && 
                w.name.toLowerCase() === newName.toLowerCase().trim())) {
                alert('Another washroom with this name already exists at this location');
                return;
            }
            
            washroom.name = newName.trim();
            washroom.description = newDescription.trim() || null;
            washroom.capacity = newCapacity ? parseInt(newCapacity) : null;
            washroom.updatedAt = new Date().toISOString();
            
            saveData();
            loadWashroomsList();
            updateStatistics();
            alert('Washroom updated successfully!');
        }

        // Delete washroom
        function deleteWashroom(id) {
            if (confirm('Are you sure you want to delete this washroom?')) {
                buildingData.washrooms = buildingData.washrooms.filter(w => w.id !== id);
                saveData();
                loadWashroomsList();
                updateStatistics();
                alert('Washroom deleted successfully!');
            }
        }

        // Update statistics
        function updateStatistics() {
            const totalWashrooms = buildingData.washrooms.length;
            const floorWashrooms = buildingData.washrooms.filter(w => w.type === 'floor').length;
            const attachedWashrooms = buildingData.washrooms.filter(w => w.type === 'attached').length;
            
            // Calculate rooms with washrooms
            const roomsWithWashrooms = buildingData.rooms.filter(room => 
                buildingData.washrooms.some(w => w.roomId === room.id)
            ).length;
            
            document.getElementById('totalWashrooms').textContent = totalWashrooms;
            document.getElementById('floorWashrooms').textContent = floorWashrooms;
            document.getElementById('attachedWashrooms').textContent = attachedWashrooms;
            document.getElementById('roomsWithWashrooms').textContent = roomsWithWashrooms;
        }

        // Reset form
        function resetForm() {
            document.getElementById('washroomName').value = '';
            document.getElementById('capacity').value = '';
            document.getElementById('washroomDescription').value = '';
            
            // Reset gender to male
            document.querySelectorAll('.gender-option').forEach(option => {
                option.classList.remove('selected');
            });
            document.querySelector('.gender-option').classList.add('selected');
            selectedGender = 'male';
            document.getElementById('genderType').value = 'male';
        }

        // Save data
        function saveData() {
            localStorage.setItem('buildingInfrastructure', JSON.stringify(buildingData));
        }
    </script>
</body>
</html>
@endsection