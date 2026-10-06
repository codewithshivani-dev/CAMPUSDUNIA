@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Lifts/Elevators</title>
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

        .lift-type-selector {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .lift-type-option {
            padding: 0.5rem;
            border: 2px solid #ddd;
            border-radius: 6px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .lift-type-option:hover {
            border-color: #4361ee;
        }

        .lift-type-option.selected {
            background: #4361ee;
            color: white;
            border-color: #4361ee;
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
            
            .lift-type-selector {
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
                <h1><i class="fas fa-elevator"></i> Manage Lifts/Elevators</h1>
                <p>Add and manage lifts/elevators in building blocks</p>
            </div>
            <a href="{{ route('infrastructure.main') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Main Menu
            </a>
        </div>

        <!-- Stats Card -->
        <div class="stats-card">
            <h3><i class="fas fa-chart-bar"></i> Lift Statistics</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value" id="totalLifts">0</div>
                    <div class="stat-label">Total Lifts</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="blocksWithLifts">0</div>
                    <div class="stat-label">Blocks with Lifts</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="passengerLifts">0</div>
                    <div class="stat-label">Passenger Lifts</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="serviceLifts">0</div>
                    <div class="stat-label">Service Lifts</div>
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            <!-- Form Card -->
            <div class="form-card">
                <h2><i class="fas fa-plus-circle"></i> Add New Lift</h2>
                
                <div class="form-group">
                    <label for="blockSelect" class="form-label">Select Block *</label>
                    <div class="select-with-icon">
                        <select id="blockSelect" class="form-control">
                            <option value="">Select a building block</option>
                        </select>
                        <i class="fas fa-building select-icon"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="liftName" class="form-label">Lift Name/Number *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-hashtag input-icon"></i>
                        <input type="text" id="liftName" class="form-control" placeholder="e.g., Lift-A, Elevator-1, Main Lift" maxlength="50">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Lift Type *</label>
                    <div class="lift-type-selector">
                        <div class="lift-type-option selected" onclick="selectLiftType('passenger')">
                            <i class="fas fa-user"></i>
                            <div>Passenger</div>
                        </div>
                        <div class="lift-type-option" onclick="selectLiftType('service')">
                            <i class="fas fa-box"></i>
                            <div>Service</div>
                        </div>
                        <div class="lift-type-option" onclick="selectLiftType('freight')">
                            <i class="fas fa-dolly"></i>
                            <div>Freight</div>
                        </div>
                    </div>
                    <input type="hidden" id="liftType" value="passenger">
                </div>
                
                <div class="form-group">
                    <label for="capacity" class="form-label">Capacity (Persons/Kg)</label>
                    <div class="input-with-icon">
                        <i class="fas fa-weight input-icon"></i>
                        <input type="number" id="capacity" class="form-control" placeholder="e.g., 8 persons or 1000 kg" min="1">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="floorsServed" class="form-label">Floors Served (Optional)</label>
                    <div class="input-with-icon">
                        <i class="fas fa-layer-group input-icon"></i>
                        <input type="text" id="floorsServed" class="form-control" placeholder="e.g., Ground-5, All floors, 1-10">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="liftDescription" class="form-label">Description (Optional)</label>
                    <textarea id="liftDescription" class="form-control" rows="3" placeholder="Brief description or special features..."></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-redo"></i> Clear
                    </button>
                    <button type="button" class="btn btn-primary" onclick="addLift()">
                        <i class="fas fa-plus"></i> Add Lift
                    </button>
                </div>
            </div>

            <!-- List Card -->
            <div class="list-card">
                <div class="list-header">
                    <h2><i class="fas fa-list"></i> All Lifts</h2>
                    <div class="items-count" id="liftsCount">0 items</div>
                </div>
                
                <div class="filter-controls">
                    <select id="blockFilter" class="filter-select" onchange="filterLifts()">
                        <option value="">All Blocks</option>
                    </select>
                    <select id="typeFilter" class="filter-select" onchange="filterLifts()">
                        <option value="">All Types</option>
                        <option value="passenger">Passenger</option>
                        <option value="service">Service</option>
                        <option value="freight">Freight</option>
                    </select>
                    <input type="text" id="searchInput" class="filter-select" placeholder="Search lifts..." oninput="filterLifts()">
                </div>
                
                <div class="items-list" id="liftsList">
                    <!-- Lifts will be listed here -->
                </div>
            </div>
        </div>

        <!-- Navigation Card -->
        <div class="stats-card" style="margin-top: 2rem;">
            <h3><i class="fas fa-arrow-right"></i> Next Steps</h3>
            <p style="margin: 1rem 0; color: #666;">After adding lifts, proceed to assign descriptive names to rooms.</p>
            <a href="{{ route('room-names.page') }}" class="btn btn-success" style="text-decoration: none;">
                <i class="fas fa-arrow-right"></i> Go to Room Names Management
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

        let selectedLiftType = 'passenger';

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            populateBlockSelects();
            loadLiftsList();
            updateStatistics();
        });

        // Populate block selects
        function populateBlockSelects() {
            const blockSelect = document.getElementById('blockSelect');
            const blockFilter = document.getElementById('blockFilter');
            
            let options = '<option value="">Select a building block</option>';
            let filterOptions = '<option value="">All Blocks</option>';
            
            buildingData.blocks.forEach(block => {
                options += `<option value="${block.id}">${block.name}</option>`;
                filterOptions += `<option value="${block.id}">${block.name}</option>`;
            });
            
            blockSelect.innerHTML = options;
            blockFilter.innerHTML = filterOptions;
        }

        // Select lift type
        function selectLiftType(type) {
            selectedLiftType = type;
            document.getElementById('liftType').value = type;
            
            // Update UI
            document.querySelectorAll('.lift-type-option').forEach(option => {
                option.classList.remove('selected');
            });
            event.target.closest('.lift-type-option').classList.add('selected');
        }

        // Add lift
        function addLift() {
            const blockId = parseInt(document.getElementById('blockSelect').value);
            const liftName = document.getElementById('liftName').value.trim();
            const liftType = selectedLiftType;
            const capacity = document.getElementById('capacity').value ? document.getElementById('capacity').value : null;
            const floorsServed = document.getElementById('floorsServed').value.trim();
            const description = document.getElementById('liftDescription').value.trim();
            
            if (!blockId) {
                alert('Please select a block');
                return;
            }
            
            if (!liftName) {
                alert('Please enter a lift name/number');
                return;
            }
            
            const block = buildingData.blocks.find(b => b.id === blockId);
            if (!block) {
                alert('Selected block not found');
                return;
            }
            
            // Check for duplicates in same block
            if (buildingData.lifts.find(l => l.blockId === blockId && l.name.toLowerCase() === liftName.toLowerCase())) {
                alert('Lift with this name already exists in this block');
                return;
            }
            
            const lift = {
                id: Date.now(),
                blockId: blockId,
                name: liftName,
                type: liftType,
                capacity: capacity,
                floorsServed: floorsServed || null,
                description: description || null,
                blockName: block.name,
                status: 'operational',
                createdAt: new Date().toISOString(),
                updatedAt: new Date().toISOString()
            };
            
            buildingData.lifts.push(lift);
            saveData();
            loadLiftsList();
            updateStatistics();
            resetForm();
            
            alert('Lift added successfully!');
        }

        // Load lifts list
        function loadLiftsList() {
            const liftsList = document.getElementById('liftsList');
            const liftsCount = document.getElementById('liftsCount');
            
            // Sort lifts by block name and lift name
            const sortedLifts = [...buildingData.lifts].sort((a, b) => {
                const blockCompare = a.blockName.localeCompare(b.blockName);
                if (blockCompare !== 0) return blockCompare;
                return a.name.localeCompare(b.name);
            });
            
            if (sortedLifts.length === 0) {
                liftsList.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-elevator"></i>
                        <p>No lifts added yet</p>
                        <small>Add your first lift using the form</small>
                    </div>
                `;
                liftsCount.textContent = '0 items';
                return;
            }
            
            liftsList.innerHTML = sortedLifts.map(lift => `
                <div class="item-row" id="lift-${lift.id}">
                    <div class="item-info">
                        <div class="item-icon" style="background: ${getLiftTypeColor(lift.type)};">
                            <i class="${getLiftTypeIcon(lift.type)}"></i>
                        </div>
                        <div class="item-details">
                            <h4>${lift.name} 
                                <span style="color: #666; font-weight: normal;">- ${lift.blockName}</span>
                            </h4>
                            <small>${getLiftTypeName(lift.type)}${lift.description ? ` • ${lift.description}` : ''}</small>
                            <div class="item-stats">
                                <span class="stat-badge ${getLiftTypeBadgeClass(lift.type)}">
                                    <i class="${getLiftTypeIcon(lift.type)}"></i>
                                    ${getLiftTypeName(lift.type)}
                                </span>
                                ${lift.capacity ? `
                                <span class="stat-badge">
                                    <i class="fas fa-weight"></i> ${lift.capacity} ${lift.type === 'passenger' ? 'persons' : 'kg'}
                                </span>
                                ` : ''}
                                ${lift.floorsServed ? `
                                <span class="stat-badge">
                                    <i class="fas fa-layer-group"></i> ${lift.floorsServed}
                                </span>
                                ` : ''}
                                <span class="stat-badge ${lift.status === 'operational' ? 'badge-success' : 'badge-warning'}">
                                    <i class="fas fa-power-off"></i> ${lift.status === 'operational' ? 'Operational' : 'Maintenance'}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="item-actions">
                        <button class="action-btn edit-btn" onclick="editLift(${lift.id})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="action-btn delete-btn" onclick="deleteLift(${lift.id})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            `).join('');
            
            liftsCount.textContent = `${sortedLifts.length} ${sortedLifts.length === 1 ? 'item' : 'items'}`;
        }

        // Filter lifts
        function filterLifts() {
            const blockFilter = document.getElementById('blockFilter').value;
            const typeFilter = document.getElementById('typeFilter').value;
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            
            const filteredLifts = buildingData.lifts.filter(lift => {
                const matchesBlock = !blockFilter || lift.blockId === parseInt(blockFilter);
                const matchesType = !typeFilter || lift.type === typeFilter;
                const matchesSearch = !searchTerm || 
                    lift.name.toLowerCase().includes(searchTerm) ||
                    lift.blockName.toLowerCase().includes(searchTerm) ||
                    (lift.description && lift.description.toLowerCase().includes(searchTerm));
                
                return matchesBlock && matchesType && matchesSearch;
            });
            
            const liftsList = document.getElementById('liftsList');
            
            if (filteredLifts.length === 0) {
                liftsList.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <p>No lifts found</p>
                        <small>Try changing your search or filter criteria</small>
                    </div>
                `;
                return;
            }
            
            liftsList.innerHTML = filteredLifts.map(lift => `
                <div class="item-row" id="lift-${lift.id}">
                    <div class="item-info">
                        <div class="item-icon" style="background: ${getLiftTypeColor(lift.type)};">
                            <i class="${getLiftTypeIcon(lift.type)}"></i>
                        </div>
                        <div class="item-details">
                            <h4>${lift.name} 
                                <span style="color: #666; font-weight: normal;">- ${lift.blockName}</span>
                            </h4>
                            <small>${getLiftTypeName(lift.type)}</small>
                            <div class="item-stats">
                                <span class="stat-badge ${getLiftTypeBadgeClass(lift.type)}">
                                    <i class="${getLiftTypeIcon(lift.type)}"></i>
                                    ${getLiftTypeName(lift.type)}
                                </span>
                                <span class="stat-badge ${lift.status === 'operational' ? 'badge-success' : 'badge-warning'}">
                                    <i class="fas fa-power-off"></i> ${lift.status === 'operational' ? 'Operational' : 'Maintenance'}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="item-actions">
                        <button class="action-btn edit-btn" onclick="editLift(${lift.id})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="action-btn delete-btn" onclick="deleteLift(${lift.id})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // Get lift type icon
        function getLiftTypeIcon(type) {
            const icons = {
                'passenger': 'fas fa-user',
                'service': 'fas fa-box',
                'freight': 'fas fa-dolly'
            };
            return icons[type] || 'fas fa-elevator';
        }

        // Get lift type color
        function getLiftTypeColor(type) {
            const colors = {
                'passenger': '#4361ee',
                'service': '#f72585',
                'freight': '#7209b7'
            };
            return colors[type] || '#4361ee';
        }

        // Get lift type badge class
        function getLiftTypeBadgeClass(type) {
            const classes = {
                'passenger': 'badge-primary',
                'service': 'badge-warning',
                'freight': 'badge-success'
            };
            return classes[type] || '';
        }

        // Get lift type name
        function getLiftTypeName(type) {
            const names = {
                'passenger': 'Passenger Lift',
                'service': 'Service Lift',
                'freight': 'Freight Lift'
            };
            return names[type] || 'Lift';
        }

        // Edit lift
        function editLift(id) {
            const lift = buildingData.lifts.find(l => l.id === id);
            if (!lift) return;
            
            const newName = prompt('Edit lift name:', lift.name);
            if (!newName || !newName.trim()) return;
            
            const newCapacity = prompt('Edit capacity (optional):', lift.capacity || '');
            const newFloorsServed = prompt('Edit floors served (optional):', lift.floorsServed || '');
            const newDescription = prompt('Edit description (optional):', lift.description || '');
            const newStatus = prompt('Edit status (operational/maintenance):', lift.status || 'operational');
            
            // Check for duplicates (excluding current lift)
            if (buildingData.lifts.find(l => l.id !== id && l.blockId === lift.blockId && l.name.toLowerCase() === newName.toLowerCase().trim())) {
                alert('Another lift with this name already exists in this block');
                return;
            }
            
            lift.name = newName.trim();
            lift.capacity = newCapacity || null;
            lift.floorsServed = newFloorsServed.trim() || null;
            lift.description = newDescription.trim() || null;
            lift.status = newStatus.toLowerCase() === 'maintenance' ? 'maintenance' : 'operational';
            lift.updatedAt = new Date().toISOString();
            
            saveData();
            loadLiftsList();
            updateStatistics();
            alert('Lift updated successfully!');
        }

        // Delete lift
        function deleteLift(id) {
            if (confirm('Are you sure you want to delete this lift?')) {
                buildingData.lifts = buildingData.lifts.filter(l => l.id !== id);
                saveData();
                loadLiftsList();
                updateStatistics();
                alert('Lift deleted successfully!');
            }
        }

        // Update statistics
        function updateStatistics() {
            const totalLifts = buildingData.lifts.length;
            const passengerLifts = buildingData.lifts.filter(l => l.type === 'passenger').length;
            const serviceLifts = buildingData.lifts.filter(l => l.type === 'service').length;
            const freightLifts = buildingData.lifts.filter(l => l.type === 'freight').length;
            
            // Calculate blocks with lifts
            const blocksWithLifts = buildingData.blocks.filter(block => 
                buildingData.lifts.some(lift => lift.blockId === block.id)
            ).length;
            
            document.getElementById('totalLifts').textContent = totalLifts;
            document.getElementById('blocksWithLifts').textContent = blocksWithLifts;
            document.getElementById('passengerLifts').textContent = passengerLifts;
            document.getElementById('serviceLifts').textContent = serviceLifts + freightLifts; // Combine service and freight
        }

        // Reset form
        function resetForm() {
            document.getElementById('liftName').value = '';
            document.getElementById('capacity').value = '';
            document.getElementById('floorsServed').value = '';
            document.getElementById('liftDescription').value = '';
            
            // Reset lift type to passenger
            document.querySelectorAll('.lift-type-option').forEach(option => {
                option.classList.remove('selected');
            });
            document.querySelector('.lift-type-option').classList.add('selected');
            selectedLiftType = 'passenger';
            document.getElementById('liftType').value = 'passenger';
        }

        // Save data
        function saveData() {
            localStorage.setItem('buildingInfrastructure', JSON.stringify(buildingData));
        }
    </script>
</body>
</html>
@endsection