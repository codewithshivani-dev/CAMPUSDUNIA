@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Building Infrastructure Summary</title>
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

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .summary-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
        }

        .summary-card:hover {
            transform: translateY(-5px);
        }

        .summary-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .summary-title {
            font-weight: 600;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .summary-count {
            background: #4361ee;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .summary-list {
            max-height: 300px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .summary-item {
            padding: 0.75rem;
            background: #f8f9ff;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            border: 1px solid #e0e0e0;
        }

        .summary-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.25rem;
        }

        .item-name {
            font-weight: 500;
            color: #333;
        }

        .item-stats {
            display: flex;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #666;
        }

        .item-details {
            font-size: 0.875rem;
            color: #666;
            line-height: 1.4;
        }

        .item-badges {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
            flex-wrap: wrap;
        }

        .badge {
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

        .badge-info {
            background: #17a2b8;
            color: white;
        }

        .progress-section {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .progress-title {
            color: #333;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .progress-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .progress-item {
            text-align: center;
            padding: 1.5rem;
            background: #f8f9ff;
            border-radius: 8px;
        }

        .progress-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }

        .progress-value {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .progress-label {
            color: #666;
            font-size: 0.9rem;
        }

        .chart-section {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .chart-container {
            height: 300px;
            margin-top: 1.5rem;
            position: relative;
        }

        .chart-placeholder {
            height: 100%;
            background: #f8f9ff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
            flex-wrap: wrap;
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
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
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

        .completion-badge {
            position: relative;
            display: inline-block;
        }

        .completion-circle {
            width: 100px;
            height: 100px;
            position: relative;
            margin: 0 auto 1rem;
        }

        .circle-bg {
            fill: none;
            stroke: #e0e0e0;
            stroke-width: 8;
        }

        .circle-progress {
            fill: none;
            stroke: #4361ee;
            stroke-width: 8;
            stroke-linecap: round;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
            transition: stroke-dasharray 0.5s ease;
        }

        .completion-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .completion-percent {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            display: block;
        }

        .completion-label {
            font-size: 0.75rem;
            color: #666;
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
            
            .summary-grid {
                grid-template-columns: 1fr;
            }
            
            .progress-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <h1><i class="fas fa-chart-bar"></i> Building Infrastructure Summary</h1>
                <p>Complete overview of your campus infrastructure configuration</p>
            </div>
            <a href="{{ route('building.main') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Main Menu
            </a>
        </div>

        <!-- Progress Section -->
        <div class="progress-section">
            <h2 class="progress-title"><i class="fas fa-tasks"></i> Configuration Progress</h2>
            <div class="completion-badge">
                <div class="completion-circle">
                    <svg width="100" height="100" viewBox="0 0 100 100">
                        <circle class="circle-bg" cx="50" cy="50" r="45"></circle>
                        <circle class="circle-progress" cx="50" cy="50" r="45" id="progressCircle"></circle>
                    </svg>
                    <div class="completion-text">
                        <span class="completion-percent" id="completionPercent">0%</span>
                        <span class="completion-label">Complete</span>
                    </div>
                </div>
            </div>
            
            <div class="progress-grid">
                <div class="progress-item">
                    <div class="progress-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="progress-value" id="blocksCount">0</div>
                    <div class="progress-label">Building Blocks</div>
                </div>
                <div class="progress-item">
                    <div class="progress-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="progress-value" id="floorsCount">0</div>
                    <div class="progress-label">Floors</div>
                </div>
                <div class="progress-item">
                    <div class="progress-icon">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <div class="progress-value" id="roomsCount">0</div>
                    <div class="progress-label">Rooms</div>
                </div>
                <div class="progress-item">
                    <div class="progress-icon">
                        <i class="fas fa-toilet"></i>
                    </div>
                    <div class="progress-value" id="washroomsCount">0</div>
                    <div class="progress-label">Washrooms</div>
                </div>
                <div class="progress-item">
                    <div class="progress-icon">
                        <i class="fas fa-elevator"></i>
                    </div>
                    <div class="progress-value" id="liftsCount">0</div>
                    <div class="progress-label">Lifts</div>
                </div>
                <div class="progress-item">
                    <div class="progress-icon">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div class="progress-value" id="namedRoomsCount">0</div>
                    <div class="progress-label">Named Rooms</div>
                </div>
            </div>
        </div>

        <!-- Summary Grid -->
        <div class="summary-grid">
            <!-- Blocks Summary -->
            <div class="summary-card">
                <div class="summary-header">
                    <div class="summary-title"><i class="fas fa-building"></i> Building Blocks</div>
                    <div class="summary-count" id="blocksSummaryCount">0</div>
                </div>
                <div class="summary-list" id="blocksSummary">
                    <!-- Blocks will be listed here -->
                </div>
            </div>

            <!-- Floors Summary -->
            <div class="summary-card">
                <div class="summary-header">
                    <div class="summary-title"><i class="fas fa-layer-group"></i> Floors</div>
                    <div class="summary-count" id="floorsSummaryCount">0</div>
                </div>
                <div class="summary-list" id="floorsSummary">
                    <!-- Floors will be listed here -->
                </div>
            </div>

            <!-- Rooms Summary -->
            <div class="summary-card">
                <div class="summary-header">
                    <div class="summary-title"><i class="fas fa-door-open"></i> Rooms</div>
                    <div class="summary-count" id="roomsSummaryCount">0</div>
                </div>
                <div class="summary-list" id="roomsSummary">
                    <!-- Rooms will be listed here -->
                </div>
            </div>

            <!-- Washrooms Summary -->
            <div class="summary-card">
                <div class="summary-header">
                    <div class="summary-title"><i class="fas fa-toilet"></i> Washrooms</div>
                    <div class="summary-count" id="washroomsSummaryCount">0</div>
                </div>
                <div class="summary-list" id="washroomsSummary">
                    <!-- Washrooms will be listed here -->
                </div>
            </div>

            <!-- Lifts Summary -->
            <div class="summary-card">
                <div class="summary-header">
                    <div class="summary-title"><i class="fas fa-elevator"></i> Lifts</div>
                    <div class="summary-count" id="liftsSummaryCount">0</div>
                </div>
                <div class="summary-list" id="liftsSummary">
                    <!-- Lifts will be listed here -->
                </div>
            </div>

            <!-- Room Names Summary -->
            <div class="summary-card">
                <div class="summary-header">
                    <div class="summary-title"><i class="fas fa-tag"></i> Room Names</div>
                    <div class="summary-count" id="roomNamesSummaryCount">0</div>
                </div>
                <div class="summary-list" id="roomNamesSummary">
                    <!-- Room names will be listed here -->
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="chart-section">
            <h2 class="progress-title"><i class="fas fa-chart-pie"></i> Distribution Overview</h2>
            <div class="chart-container">
                <div class="chart-placeholder" id="chartPlaceholder">
                    <div style="text-align: center;">
                        <i class="fas fa-chart-pie" style="font-size: 3rem; margin-bottom: 1rem; color: #ddd;"></i>
                        <p>Room Type Distribution</p>
                        <small style="color: #999;">Rooms by category</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('building.main') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Back to Main Menu
            </a>
            <button class="btn btn-success" onclick="exportData()">
                <i class="fas fa-download"></i> Export Configuration
            </button>
            <button class="btn btn-secondary" onclick="printSummary()">
                <i class="fas fa-print"></i> Print Summary
            </button>
            <button class="btn btn-secondary" onclick="resetAll()">
                <i class="fas fa-redo"></i> Reset All Data
            </button>
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

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateStatistics();
            loadSummaryData();
            updateProgressCircle();
        });

        // Update statistics
        function updateStatistics() {
            // Update counts
            document.getElementById('blocksCount').textContent = buildingData.blocks.length;
            document.getElementById('floorsCount').textContent = buildingData.floors.length;
            document.getElementById('roomsCount').textContent = buildingData.rooms.length;
            document.getElementById('washroomsCount').textContent = buildingData.washrooms.length;
            document.getElementById('liftsCount').textContent = buildingData.lifts.length;
            document.getElementById('namedRoomsCount').textContent = buildingData.roomNames.length;
            
            // Update summary counts
            document.getElementById('blocksSummaryCount').textContent = buildingData.blocks.length;
            document.getElementById('floorsSummaryCount').textContent = buildingData.floors.length;
            document.getElementById('roomsSummaryCount').textContent = buildingData.rooms.length;
            document.getElementById('washroomsSummaryCount').textContent = buildingData.washrooms.length;
            document.getElementById('liftsSummaryCount').textContent = buildingData.lifts.length;
            document.getElementById('roomNamesSummaryCount').textContent = buildingData.roomNames.length;
            
            // Calculate completion percentage
            let completionScore = 0;
            if (buildingData.blocks.length > 0) completionScore += 16.67;
            if (buildingData.floors.length > 0) completionScore += 16.67;
            if (buildingData.rooms.length > 0) completionScore += 16.67;
            if (buildingData.washrooms.length > 0) completionScore += 16.67;
            if (buildingData.lifts.length > 0) completionScore += 16.67;
            if (buildingData.roomNames.length > 0) completionScore += 16.67;
            
            const completionPercentage = Math.round(completionScore);
            document.getElementById('completionPercent').textContent = completionPercentage + '%';
            
            // Update progress circle
            const progressCircle = document.getElementById('progressCircle');
            const radius = 45;
            const circumference = 2 * Math.PI * radius;
            const offset = circumference - (completionPercentage / 100) * circumference;
            progressCircle.style.strokeDasharray = `${circumference} ${circumference}`;
            progressCircle.style.strokeDashoffset = offset;
        }

        // Load summary data
        function loadSummaryData() {
            loadBlocksSummary();
            loadFloorsSummary();
            loadRoomsSummary();
            loadWashroomsSummary();
            loadLiftsSummary();
            loadRoomNamesSummary();
        }

        // Load blocks summary
        function loadBlocksSummary() {
            const blocksSummary = document.getElementById('blocksSummary');
            
            if (buildingData.blocks.length === 0) {
                blocksSummary.innerHTML = `
                    <div class="empty-state">
                        <small>No blocks configured</small>
                    </div>
                `;
                return;
            }
            
            blocksSummary.innerHTML = buildingData.blocks.slice(0, 5).map(block => {
                // Count floors and rooms for this block
                const blockFloors = buildingData.floors.filter(f => f.blockId === block.id);
                const blockRooms = buildingData.rooms.filter(r => {
                    const floor = buildingData.floors.find(f => f.id === r.floorId);
                    return floor && floor.blockId === block.id;
                }).length;
                
                return `
                <div class="summary-item">
                    <div class="summary-item-header">
                        <div class="item-name">${block.name}</div>
                        <div class="item-stats">
                            <span>${blockFloors.length} Floors</span>
                        </div>
                    </div>
                    <div class="item-details">
                        ${block.code ? `<small>Code: ${block.code}</small><br>` : ''}
                        <small>${blockRooms} Rooms</small>
                    </div>
                    <div class="item-badges">
                        ${blockFloors.length > 0 ? '<span class="badge badge-success">Has Floors</span>' : '<span class="badge badge-warning">No Floors</span>'}
                        ${blockRooms > 0 ? '<span class="badge badge-primary">Has Rooms</span>' : '<span class="badge">No Rooms</span>'}
                    </div>
                </div>
                `;
            }).join('');
            
            if (buildingData.blocks.length > 5) {
                blocksSummary.innerHTML += `
                <div class="summary-item" style="text-align: center; background: transparent; border: none;">
                    <small>+ ${buildingData.blocks.length - 5} more blocks</small>
                </div>
                `;
            }
        }

        // Load floors summary
        function loadFloorsSummary() {
            const floorsSummary = document.getElementById('floorsSummary');
            
            if (buildingData.floors.length === 0) {
                floorsSummary.innerHTML = `
                    <div class="empty-state">
                        <small>No floors configured</small>
                    </div>
                `;
                return;
            }
            
            floorsSummary.innerHTML = buildingData.floors.slice(0, 5).map(floor => {
                // Count rooms for this floor
                const floorRooms = buildingData.rooms.filter(r => r.floorId === floor.id).length;
                const block = buildingData.blocks.find(b => b.id === floor.blockId);
                
                return `
                <div class="summary-item">
                    <div class="summary-item-header">
                        <div class="item-name">${floor.number}</div>
                        <div class="item-stats">
                            <span>${floorRooms} Rooms</span>
                        </div>
                    </div>
                    <div class="item-details">
                        <small>${block ? block.name : 'Unknown Block'}</small>
                    </div>
                    <div class="item-badges">
                        ${floorRooms > 0 ? '<span class="badge badge-primary">Has Rooms</span>' : '<span class="badge">No Rooms</span>'}
                    </div>
                </div>
                `;
            }).join('');
            
            if (buildingData.floors.length > 5) {
                floorsSummary.innerHTML += `
                <div class="summary-item" style="text-align: center; background: transparent; border: none;">
                    <small>+ ${buildingData.floors.length - 5} more floors</small>
                </div>
                `;
            }
        }

        // Load rooms summary
        function loadRoomsSummary() {
            const roomsSummary = document.getElementById('roomsSummary');
            
            if (buildingData.rooms.length === 0) {
                roomsSummary.innerHTML = `
                    <div class="empty-state">
                        <small>No rooms configured</small>
                    </div>
                `;
                return;
            }
            
            roomsSummary.innerHTML = buildingData.rooms.slice(0, 5).map(room => {
                const floor = buildingData.floors.find(f => f.id === room.floorId);
                const block = floor ? buildingData.blocks.find(b => b.id === floor.blockId) : null;
                const hasWashroom = buildingData.washrooms.some(w => w.roomId === room.id);
                const hasName = buildingData.roomNames.some(rn => rn.roomId === room.id);
                
                return `
                <div class="summary-item">
                    <div class="summary-item-header">
                        <div class="item-name">Room ${room.number}</div>
                        <div class="item-stats">
                            <span>${getRoomTypeShortName(room.type)}</span>
                        </div>
                    </div>
                    <div class="item-details">
                        <small>${block ? block.name : ''} • ${floor ? floor.number : ''}</small>
                    </div>
                    <div class="item-badges">
                        ${hasName ? '<span class="badge badge-success">Named</span>' : '<span class="badge">Unnamed</span>'}
                        ${hasWashroom ? '<span class="badge badge-primary">Has Washroom</span>' : ''}
                    </div>
                </div>
                `;
            }).join('');
            
            if (buildingData.rooms.length > 5) {
                roomsSummary.innerHTML += `
                <div class="summary-item" style="text-align: center; background: transparent; border: none;">
                    <small>+ ${buildingData.rooms.length - 5} more rooms</small>
                </div>
                `;
            }
        }

        // Get room type short name
        function getRoomTypeShortName(type) {
            const names = {
                'classroom': 'Class',
                'lab': 'Lab',
                'office': 'Office',
                'other': 'Other'
            };
            return names[type] || 'Room';
        }

        // Load washrooms summary
        function loadWashroomsSummary() {
            const washroomsSummary = document.getElementById('washroomsSummary');
            
            if (buildingData.washrooms.length === 0) {
                washroomsSummary.innerHTML = `
                    <div class="empty-state">
                        <small>No washrooms configured</small>
                    </div>
                `;
                return;
            }
            
            washroomsSummary.innerHTML = buildingData.washrooms.slice(0, 5).map(washroom => {
                let location = '';
                if (washroom.type === 'floor') {
                    const floor = buildingData.floors.find(f => f.id === washroom.floorId);
                    const block = floor ? buildingData.blocks.find(b => b.id === floor.blockId) : null;
                    location = `${block ? block.name : ''} • ${floor ? floor.number : ''}`;
                } else {
                    const room = buildingData.rooms.find(r => r.id === washroom.roomId);
                    const floor = room ? buildingData.floors.find(f => f.id === room.floorId) : null;
                    const block = floor ? buildingData.blocks.find(b => b.id === floor.blockId) : null;
                    location = `${block ? block.name : ''} • Room ${room ? room.number : ''}`;
                }
                
                return `
                <div class="summary-item">
                    <div class="summary-item-header">
                        <div class="item-name">${washroom.name}</div>
                        <div class="item-stats">
                            <span>${washroom.type === 'floor' ? 'Floor' : 'Attached'}</span>
                        </div>
                    </div>
                    <div class="item-details">
                        <small>${location}</small>
                        <small style="display: block; margin-top: 0.25rem;">${getGenderName(washroom.gender)}${washroom.capacity ? ` • Capacity: ${washroom.capacity}` : ''}</small>
                    </div>
                    <div class="item-badges">
                        <span class="badge ${washroom.type === 'floor' ? 'badge-primary' : 'badge-success'}">
                            ${washroom.type === 'floor' ? 'Floor' : 'Attached'}
                        </span>
                        <span class="badge ${getGenderBadgeClass(washroom.gender)}">
                            ${getGenderName(washroom.gender)}
                        </span>
                    </div>
                </div>
                `;
            }).join('');
            
            if (buildingData.washrooms.length > 5) {
                washroomsSummary.innerHTML += `
                <div class="summary-item" style="text-align: center; background: transparent; border: none;">
                    <small>+ ${buildingData.washrooms.length - 5} more washrooms</small>
                </div>
                `;
            }
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

        // Get gender badge class
        function getGenderBadgeClass(gender) {
            const classes = {
                'male': 'badge-primary',
                'female': 'badge-warning',
                'unisex': 'badge-success'
            };
            return classes[gender] || '';
        }

        // Load lifts summary
        function loadLiftsSummary() {
            const liftsSummary = document.getElementById('liftsSummary');
            
            if (buildingData.lifts.length === 0) {
                liftsSummary.innerHTML = `
                    <div class="empty-state">
                        <small>No lifts configured</small>
                    </div>
                `;
                return;
            }
            
            liftsSummary.innerHTML = buildingData.lifts.slice(0, 5).map(lift => {
                const block = buildingData.blocks.find(b => b.id === lift.blockId);
                
                return `
                <div class="summary-item">
                    <div class="summary-item-header">
                        <div class="item-name">${lift.name}</div>
                        <div class="item-stats">
                            <span>${getLiftTypeName(lift.type)}</span>
                        </div>
                    </div>
                    <div class="item-details">
                        <small>${block ? block.name : 'Unknown Block'}</small>
                        ${lift.capacity ? `<small style="display: block; margin-top: 0.25rem;">Capacity: ${lift.capacity} ${lift.type === 'passenger' ? 'persons' : 'kg'}</small>` : ''}
                    </div>
                    <div class="item-badges">
                        <span class="badge ${getLiftTypeBadgeClass(lift.type)}">
                            ${getLiftTypeName(lift.type)}
                        </span>
                        <span class="badge ${lift.status === 'operational' ? 'badge-success' : 'badge-warning'}">
                            ${lift.status === 'operational' ? 'Operational' : 'Maintenance'}
                        </span>
                    </div>
                </div>
                `;
            }).join('');
            
            if (buildingData.lifts.length > 5) {
                liftsSummary.innerHTML += `
                <div class="summary-item" style="text-align: center; background: transparent; border: none;">
                    <small>+ ${buildingData.lifts.length - 5} more lifts</small>
                </div>
                `;
            }
        }

        // Get lift type name
        function getLiftTypeName(type) {
            const names = {
                'passenger': 'Passenger',
                'service': 'Service',
                'freight': 'Freight'
            };
            return names[type] || 'Lift';
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

        // Load room names summary
        function loadRoomNamesSummary() {
            const roomNamesSummary = document.getElementById('roomNamesSummary');
            
            if (buildingData.roomNames.length === 0) {
                roomNamesSummary.innerHTML = `
                    <div class="empty-state">
                        <small>No rooms named yet</small>
                    </div>
                `;
                return;
            }
            
            roomNamesSummary.innerHTML = buildingData.roomNames.slice(0, 5).map(roomName => {
                const room = buildingData.rooms.find(r => r.id === roomName.roomId);
                const hasWashroom = buildingData.washrooms.some(w => w.roomId === roomName.roomId);
                
                return `
                <div class="summary-item">
                    <div class="summary-item-header">
                        <div class="item-name" style="font-size: 0.9rem;">${roomName.name}</div>
                    </div>
                    <div class="item-details">
                        <small>${roomName.blockName} • ${roomName.floorNumber} • Room ${roomName.roomNumber}</small>
                        ${roomName.purpose ? `<small style="display: block; margin-top: 0.25rem;">🎯 ${roomName.purpose}</small>` : ''}
                        ${roomName.capacity ? `<small style="display: block;">👥 ${roomName.capacity} persons</small>` : ''}
                    </div>
                    <div class="item-badges">
                        <span class="badge badge-primary">
                            ${getRoomTypeName(roomName.roomType)}
                        </span>
                        ${hasWashroom ? '<span class="badge badge-success">🚽</span>' : ''}
                    </div>
                </div>
                `;
            }).join('');
            
            if (buildingData.roomNames.length > 5) {
                roomNamesSummary.innerHTML += `
                <div class="summary-item" style="text-align: center; background: transparent; border: none;">
                    <small>+ ${buildingData.roomNames.length - 5} more named rooms</small>
                </div>
                `;
            }
        }

        // Get room type name
        function getRoomTypeName(type) {
            const names = {
                'classroom': 'Classroom',
                'lab': 'Laboratory',
                'office': 'Office',
                'other': 'Other'
            };
            return names[type] || 'Room';
        }

        // Update progress circle
        function updateProgressCircle() {
            // Already updated in updateStatistics()
        }

        // Export data
        function exportData() {
            const dataStr = JSON.stringify(buildingData, null, 2);
            const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
            
            const exportFileDefaultName = 'building-infrastructure-summary-' + new Date().toISOString().split('T')[0] + '.json';
            
            const linkElement = document.createElement('a');
            linkElement.setAttribute('href', dataUri);
            linkElement.setAttribute('download', exportFileDefaultName);
            linkElement.click();
            
            alert('Configuration exported successfully!');
        }

        // Print summary
        function printSummary() {
            window.print();
        }

        // Reset all data
        function resetAll() {
            if (confirm('Are you sure you want to reset all data? This action cannot be undone.')) {
                buildingData = {
                    blocks: [],
                    floors: [],
                    rooms: [],
                    washrooms: [],
                    lifts: [],
                    roomNames: []
                };
                
                localStorage.setItem('buildingInfrastructure', JSON.stringify(buildingData));
                alert('All data has been reset!');
                location.reload();
            }
        }
    </script>
</body>
</html>
@endsection