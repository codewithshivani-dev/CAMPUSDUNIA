@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Building Infrastructure Management</title>
    <style>
        .header {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header p {
            opacity: 0.9;
            font-size: 1rem;
        }

        .progress-container {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 3rem;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 3px;
            background: #e0e0e0;
            transform: translateY(-50%);
            z-index: 1;
        }

        .progress-bar {
            position: absolute;
            top: 50%;
            left: 0;
            height: 3px;
            background: #4361ee;
            transform: translateY(-50%);
            z-index: 2;
            transition: width 0.4s ease;
        }

        .step {
            position: relative;
            z-index: 3;
            text-align: center;
            width: 100px;
        }

        .step-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            border: 3px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
            color: #666;
            transition: all 0.3s ease;
        }

        .step.active .step-circle {
            background: #4361ee;
            border-color: #4361ee;
            color: white;
            box-shadow: 0 0 0 8px rgba(67, 97, 238, 0.2);
        }

        .step.completed .step-circle {
            background: #28a745;
            border-color: #28a745;
            color: white;
        }

        .step-label {
            font-size: 0.9rem;
            color: #666;
            font-weight: 600;
        }

        .step.active .step-label {
            color: #4361ee;
        }

        .step.completed .step-label {
            color: #28a745;
        }

        .step-content {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-header h2 {
            color: #333;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .form-header p {
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }

        .single-field-form {
            max-width: 500px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 2rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: #444;
            font-size: 1.1rem;
        }

        .form-control {
            width: 100%;
            padding: 1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #4361ee;
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        }

        .input-with-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .input-with-icon input {
            padding-left: 45px;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #eee;
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
            min-width: 120px;
            justify-content: center;
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

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .list-summary {
            background: #f8f9ff;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 2rem;
            border: 1px solid #e0e0e0;
        }

        .list-summary h4 {
            margin-bottom: 1rem;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .items-list {
            max-height: 200px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            background: white;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            border: 1px solid #eee;
        }

        .item-name {
            font-weight: 500;
            color: #333;
        }

        .item-actions {
            display: flex;
            gap: 0.5rem;
        }

        .item-actions button {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .item-actions .edit-btn {
            background: #4361ee;
            color: white;
        }

        .item-actions .delete-btn {
            background: #dc3545;
            color: white;
        }

        .empty-list {
            text-align: center;
            padding: 1.5rem;
            color: #666;
        }

        .empty-list i {
            font-size: 2rem;
            color: #ddd;
            margin-bottom: 0.5rem;
        }

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }

        .step-indicator {
            text-align: center;
            margin: 1rem 0;
            font-weight: 600;
            color: #4361ee;
        }

        .summary-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid #e0e0e0;
        }

        .summary-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
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

        .summary-items {
            display: grid;
            gap: 0.5rem;
        }

        .summary-item {
            padding: 0.5rem;
            background: #f8f9ff;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            .progress-steps {
                flex-direction: column;
                gap: 2rem;
                align-items: center;
            }
            
            .progress-steps::before {
                width: 3px;
                height: 80%;
                left: 50%;
                transform: translateX(-50%);
            }
            
            .progress-bar {
                width: 3px;
                height: 0;
            }
            
            .step {
                width: 100%;
                display: flex;
                align-items: center;
                gap: 1rem;
            }
            
            .step-circle {
                margin: 0;
                flex-shrink: 0;
            }
            
            .step-label {
                text-align: left;
            }
            
            .form-actions {
                flex-direction: column;
                gap: 1rem;
            }
            
            .btn {
                width: 100%;
            }
            
            .navigation-buttons {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-building"></i> Building Infrastructure Management</h1>
            <p>Step-by-step configuration of your building infrastructure</p>
        </div>

        <!-- Progress Steps -->
        <div class="progress-container">
            <div class="progress-steps" id="progressSteps">
                <div class="step active" data-step="1">
                    <div class="step-circle">1</div>
                    <div class="step-label">Blocks</div>
                </div>
                <div class="step" data-step="2">
                    <div class="step-circle">2</div>
                    <div class="step-label">Floors</div>
                </div>
                <div class="step" data-step="3">
                    <div class="step-circle">3</div>
                    <div class="step-label">Rooms</div>
                </div>
                <div class="step" data-step="4">
                    <div class="step-circle">4</div>
                    <div class="step-label">Washrooms</div>
                </div>
                <div class="step" data-step="5">
                    <div class="step-circle">5</div>
                    <div class="step-label">Lifts</div>
                </div>
                <div class="step" data-step="6">
                    <div class="step-circle">6</div>
                    <div class="step-label">Room Names</div>
                </div>
                <div class="progress-bar" id="progressBar"></div>
            </div>
        </div>

        <!-- Step 1: Blocks -->
        <div class="step-content" id="step1" style="display: block;">
            <div class="form-header">
                <h2><i class="fas fa-building"></i> Add Building Blocks</h2>
                <p>Start by adding the building blocks in your campus</p>
            </div>
            
            <div class="single-field-form">
                <div class="form-group">
                    <label for="blockName" class="form-label">Block Name *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-building input-icon"></i>
                        <input type="text" id="blockName" class="form-control" placeholder="e.g., Main Building, Science Block, Admin Block" maxlength="100">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="addBlock()">
                        <i class="fas fa-plus"></i> Add Block
                    </button>
                    <button type="button" class="btn btn-primary" onclick="nextStep(2)">
                        <i class="fas fa-arrow-right"></i> Next: Floors
                    </button>
                </div>
            </div>
            
            <div class="list-summary">
                <h4><i class="fas fa-list"></i> Added Blocks</h4>
                <div class="items-list" id="blocksList">
                    <!-- Blocks will be listed here -->
                </div>
            </div>
        </div>

        <!-- Step 2: Floors -->
        <div class="step-content" id="step2" style="display: none;">
            <div class="form-header">
                <h2><i class="fas fa-layer-group"></i> Add Floors</h2>
                <p>Add floors to the selected building block</p>
            </div>
            
            <div class="single-field-form">
                <div class="form-group">
                    <label for="blockSelect" class="form-label">Select Block *</label>
                    <select id="blockSelect" class="form-control" onchange="updateFloorsList()">
                        <option value="">Select a block</option>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="floorNumber" class="form-label">Floor Number/Name *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-layer-group input-icon"></i>
                        <input type="text" id="floorNumber" class="form-control" placeholder="e.g., Ground Floor, 1st Floor, Basement, Mezzanine" maxlength="50">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="addFloor()">
                        <i class="fas fa-plus"></i> Add Floor
                    </button>
                    <div class="navigation-buttons">
                        <button type="button" class="btn btn-secondary" onclick="prevStep(1)">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(3)">
                            <i class="fas fa-arrow-right"></i> Next: Rooms
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="list-summary">
                <h4><i class="fas fa-list"></i> Added Floors</h4>
                <div class="items-list" id="floorsList">
                    <!-- Floors will be listed here -->
                </div>
            </div>
        </div>

        <!-- Step 3: Rooms -->
        <div class="step-content" id="step3" style="display: none;">
            <div class="form-header">
                <h2><i class="fas fa-door-open"></i> Add Rooms</h2>
                <p>Add rooms to the selected floor</p>
            </div>
            
            <div class="single-field-form">
                <div class="form-group">
                    <label for="floorSelect" class="form-label">Select Floor *</label>
                    <select id="floorSelect" class="form-control" onchange="updateRoomsList()">
                        <option value="">Select a floor</option>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="roomNumber" class="form-label">Room Number *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-door-open input-icon"></i>
                        <input type="text" id="roomNumber" class="form-control" placeholder="e.g., 101, 201, Lab-1, Auditorium-1" maxlength="20">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="addRoom()">
                        <i class="fas fa-plus"></i> Add Room
                    </button>
                    <div class="navigation-buttons">
                        <button type="button" class="btn btn-secondary" onclick="prevStep(2)">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(4)">
                            <i class="fas fa-arrow-right"></i> Next: Washrooms
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="list-summary">
                <h4><i class="fas fa-list"></i> Added Rooms</h4>
                <div class="items-list" id="roomsList">
                    <!-- Rooms will be listed here -->
                </div>
            </div>
        </div>

        <!-- Step 4: Washrooms -->
        <div class="step-content" id="step4" style="display: none;">
            <div class="form-header">
                <h2><i class="fas fa-toilet"></i> Add Washrooms</h2>
                <p>Configure washrooms on floors and attach to rooms</p>
            </div>
            
            <div class="single-field-form">
                <div class="form-group">
                    <label for="washroomType" class="form-label">Washroom Type *</label>
                    <select id="washroomType" class="form-control" onchange="toggleWashroomType()">
                        <option value="floor">Washroom on Floor</option>
                        <option value="attached">Attached to Room</option>
                    </select>
                </div>
                
                <div class="form-group" id="floorWashroomGroup">
                    <label for="washroomFloorSelect" class="form-label">Select Floor *</label>
                    <select id="washroomFloorSelect" class="form-control">
                        <option value="">Select a floor</option>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
                
                <div class="form-group" id="attachedWashroomGroup" style="display: none;">
                    <label for="roomForWashroom" class="form-label">Select Room *</label>
                    <select id="roomForWashroom" class="form-control">
                        <option value="">Select a room</option>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="washroomName" class="form-label">Washroom Name/Number *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-toilet input-icon"></i>
                        <input type="text" id="washroomName" class="form-control" placeholder="e.g., Gents-1, Ladies-1, Washroom-A" maxlength="50">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="addWashroom()">
                        <i class="fas fa-plus"></i> Add Washroom
                    </button>
                    <div class="navigation-buttons">
                        <button type="button" class="btn btn-secondary" onclick="prevStep(3)">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(5)">
                            <i class="fas fa-arrow-right"></i> Next: Lifts
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="list-summary">
                <h4><i class="fas fa-list"></i> Added Washrooms</h4>
                <div class="items-list" id="washroomsList">
                    <!-- Washrooms will be listed here -->
                </div>
            </div>
        </div>

        <!-- Step 5: Lifts -->
        <div class="step-content" id="step5" style="display: none;">
            <div class="form-header">
                <h2><i class="fas fa-elevator"></i> Add Lifts</h2>
                <p>Add lifts/elevators to building blocks</p>
            </div>
            
            <div class="single-field-form">
                <div class="form-group">
                    <label for="liftBlockSelect" class="form-label">Select Block *</label>
                    <select id="liftBlockSelect" class="form-control">
                        <option value="">Select a block</option>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="liftName" class="form-label">Lift Name/Number *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-elevator input-icon"></i>
                        <input type="text" id="liftName" class="form-control" placeholder="e.g., Lift-A, Elevator-1, Main Lift" maxlength="50">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="addLift()">
                        <i class="fas fa-plus"></i> Add Lift
                    </button>
                    <div class="navigation-buttons">
                        <button type="button" class="btn btn-secondary" onclick="prevStep(4)">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(6)">
                            <i class="fas fa-arrow-right"></i> Next: Room Names
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="list-summary">
                <h4><i class="fas fa-list"></i> Added Lifts</h4>
                <div class="items-list" id="liftsList">
                    <!-- Lifts will be listed here -->
                </div>
            </div>
        </div>

        <!-- Step 6: Room Names -->
        <div class="step-content" id="step6" style="display: none;">
            <div class="form-header">
                <h2><i class="fas fa-tag"></i> Assign Room Names</h2>
                <p>Assign descriptive names to rooms</p>
            </div>
            
            <div class="single-field-form">
                <div class="form-group">
                    <label for="roomToName" class="form-label">Select Room *</label>
                    <select id="roomToName" class="form-control">
                        <option value="">Select a room</option>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="roomDescriptiveName" class="form-label">Room Descriptive Name *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-tag input-icon"></i>
                        <input type="text" id="roomDescriptiveName" class="form-control" placeholder="e.g., Principal's Office, Computer Lab-1, Main Auditorium" maxlength="100">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="addRoomName()">
                        <i class="fas fa-plus"></i> Add Name
                    </button>
                    <div class="navigation-buttons">
                        <button type="button" class="btn btn-secondary" onclick="prevStep(5)">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                        <button type="button" class="btn btn-success" onclick="completeSetup()">
                            <i class="fas fa-check"></i> Complete Setup
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="list-summary">
                <h4><i class="fas fa-list"></i> Named Rooms</h4>
                <div class="items-list" id="roomNamesList">
                    <!-- Named rooms will be listed here -->
                </div>
            </div>
        </div>

        <!-- Summary Section -->
        <div id="summarySection" style="display: none;">
            <div class="summary-card">
                <div class="summary-header">
                    <div class="summary-title"><i class="fas fa-building"></i> Blocks</div>
                    <div class="summary-count" id="blocksCount">0</div>
                </div>
                <div class="summary-items" id="blocksSummary"></div>
            </div>
            
            <div class="summary-card">
                <div class="summary-header">
                    <div class="summary-title"><i class="fas fa-layer-group"></i> Floors</div>
                    <div class="summary-count" id="floorsCount">0</div>
                </div>
                <div class="summary-items" id="floorsSummary"></div>
            </div>
            
            <div class="summary-card">
                <div class="summary-header">
                    <div class="summary-title"><i class="fas fa-door-open"></i> Rooms</div>
                    <div class="summary-count" id="roomsCount">0</div>
                </div>
                <div class="summary-items" id="roomsSummary"></div>
            </div>
            
            <div class="summary-card">
                <div class="summary-header">
                    <div class="summary-title"><i class="fas fa-toilet"></i> Washrooms</div>
                    <div class="summary-count" id="washroomsCount">0</div>
                </div>
                <div class="summary-items" id="washroomsSummary"></div>
            </div>
            
            <div class="summary-card">
                <div class="summary-header">
                    <div class="summary-title"><i class="fas fa-elevator"></i> Lifts</div>
                    <div class="summary-count" id="liftsCount">0</div>
                </div>
                <div class="summary-items" id="liftsSummary"></div>
            </div>
            
            <div class="summary-card">
                <div class="summary-header">
                    <div class="summary-title"><i class="fas fa-tag"></i> Named Rooms</div>
                    <div class="summary-count" id="namedRoomsCount">0</div>
                </div>
                <div class="summary-items" id="namedRoomsSummary"></div>
            </div>
            
            <div style="text-align: center; margin-top: 2rem;">
                <button class="btn btn-primary" onclick="exportData()">
                    <i class="fas fa-download"></i> Export Configuration
                </button>
                <button class="btn btn-secondary" onclick="resetAll()" style="margin-left: 1rem;">
                    <i class="fas fa-redo"></i> Start Over
                </button>
            </div>
        </div>
    </div>

    <script>
        // Data structure
        let buildingData = JSON.parse(localStorage.getItem('buildingInfrastructure')) || {
            blocks: [],
            floors: [],
            rooms: [],
            washrooms: [],
            lifts: [],
            roomNames: []
        };

        let currentStep = 1;

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeData();
            updateProgressBar();
            loadBlocksList();
            updateAllSelects();
        });

        // Initialize data
        function initializeData() {
            if (!buildingData.blocks) buildingData.blocks = [];
            if (!buildingData.floors) buildingData.floors = [];
            if (!buildingData.rooms) buildingData.rooms = [];
            if (!buildingData.washrooms) buildingData.washrooms = [];
            if (!buildingData.lifts) buildingData.lifts = [];
            if (!buildingData.roomNames) buildingData.roomNames = [];
            
            saveData();
        }

        // Save data to localStorage
        function saveData() {
            localStorage.setItem('buildingInfrastructure', JSON.stringify(buildingData));
        }

        // Update progress bar
        function updateProgressBar() {
            const progressBar = document.getElementById('progressBar');
            const steps = document.querySelectorAll('.step');
            const totalSteps = steps.length;
            const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
            
            progressBar.style.width = progress + '%';
            
            steps.forEach(step => {
                const stepNum = parseInt(step.dataset.step);
                step.classList.remove('active', 'completed');
                
                if (stepNum === currentStep) {
                    step.classList.add('active');
                } else if (stepNum < currentStep) {
                    step.classList.add('completed');
                }
            });
        }

        // Navigation functions
        function nextStep(step) {
            // Validate current step before proceeding
            if (step === 2 && buildingData.blocks.length === 0) {
                alert('Please add at least one block before proceeding to floors.');
                return;
            }
            
            if (step === 3 && buildingData.floors.length === 0) {
                alert('Please add at least one floor before proceeding to rooms.');
                return;
            }
            
            if (step === 4 && buildingData.rooms.length === 0) {
                alert('Please add at least one room before proceeding to washrooms.');
                return;
            }
            
            if (step === 5 && buildingData.washrooms.length === 0) {
                alert('Please add at least one washroom before proceeding to lifts.');
                return;
            }
            
            if (step === 6 && buildingData.lifts.length === 0) {
                alert('Please add at least one lift before proceeding to room names.');
                return;
            }
            
            document.getElementById('step' + currentStep).style.display = 'none';
            currentStep = step;
            document.getElementById('step' + currentStep).style.display = 'block';
            updateProgressBar();
            updateAllSelects();
        }

        function prevStep(step) {
            document.getElementById('step' + currentStep).style.display = 'none';
            currentStep = step;
            document.getElementById('step' + currentStep).style.display = 'block';
            updateProgressBar();
            updateAllSelects();
        }

        // Update all select dropdowns
        function updateAllSelects() {
            updateBlockSelect();
            updateFloorSelect();
            updateRoomSelect();
            updateWashroomSelects();
            updateLiftBlockSelect();
            updateRoomToNameSelect();
        }

        // Update block select dropdown
        function updateBlockSelect() {
            const blockSelect = document.getElementById('blockSelect');
            const liftBlockSelect = document.getElementById('liftBlockSelect');
            
            if (blockSelect) {
                let options = '<option value="">Select a block</option>';
                buildingData.blocks.forEach(block => {
                    options += `<option value="${block.id}">${block.name}</option>`;
                });
                blockSelect.innerHTML = options;
            }
            
            if (liftBlockSelect) {
                let options = '<option value="">Select a block</option>';
                buildingData.blocks.forEach(block => {
                    options += `<option value="${block.id}">${block.name}</option>`;
                });
                liftBlockSelect.innerHTML = options;
            }
        }

        // Update floor select dropdown
        function updateFloorSelect() {
            const floorSelect = document.getElementById('floorSelect');
            const washroomFloorSelect = document.getElementById('washroomFloorSelect');
            
            if (floorSelect) {
                let options = '<option value="">Select a floor</option>';
                buildingData.floors.forEach(floor => {
                    const block = buildingData.blocks.find(b => b.id === floor.blockId);
                    options += `<option value="${floor.id}">${block ? block.name + ' - ' : ''}${floor.number}</option>`;
                });
                floorSelect.innerHTML = options;
            }
            
            if (washroomFloorSelect) {
                let options = '<option value="">Select a floor</option>';
                buildingData.floors.forEach(floor => {
                    const block = buildingData.blocks.find(b => b.id === floor.blockId);
                    options += `<option value="${floor.id}">${block ? block.name + ' - ' : ''}${floor.number}</option>`;
                });
                washroomFloorSelect.innerHTML = options;
            }
        }

        // Update room select dropdown
        function updateRoomSelect() {
            const roomForWashroom = document.getElementById('roomForWashroom');
            
            if (roomForWashroom) {
                let options = '<option value="">Select a room</option>';
                buildingData.rooms.forEach(room => {
                    const floor = buildingData.floors.find(f => f.id === room.floorId);
                    const block = floor ? buildingData.blocks.find(b => b.id === floor.blockId) : null;
                    options += `<option value="${room.id}">${block ? block.name + ' - ' : ''}${floor ? floor.number + ' - ' : ''}Room ${room.number}</option>`;
                });
                roomForWashroom.innerHTML = options;
            }
        }

        // Toggle washroom type
        function toggleWashroomType() {
            const type = document.getElementById('washroomType').value;
            document.getElementById('floorWashroomGroup').style.display = type === 'floor' ? 'block' : 'none';
            document.getElementById('attachedWashroomGroup').style.display = type === 'attached' ? 'block' : 'none';
        }

        // Update washroom selects
        function updateWashroomSelects() {
            updateFloorSelect();
            updateRoomSelect();
        }

        // Update room to name select
        function updateRoomToNameSelect() {
            const roomToName = document.getElementById('roomToName');
            
            if (roomToName) {
                // Get rooms that don't have names yet
                let options = '<option value="">Select a room</option>';
                buildingData.rooms.forEach(room => {
                    const hasName = buildingData.roomNames.find(rn => rn.roomId === room.id);
                    if (!hasName) {
                        const floor = buildingData.floors.find(f => f.id === room.floorId);
                        const block = floor ? buildingData.blocks.find(b => b.id === floor.blockId) : null;
                        options += `<option value="${room.id}">${block ? block.name + ' - ' : ''}${floor ? floor.number + ' - ' : ''}Room ${room.number}</option>`;
                    }
                });
                roomToName.innerHTML = options;
            }
        }

        // Add block
        function addBlock() {
            const blockName = document.getElementById('blockName').value.trim();
            
            if (!blockName) {
                alert('Please enter a block name');
                return;
            }
            
            // Check for duplicates
            if (buildingData.blocks.find(b => b.name.toLowerCase() === blockName.toLowerCase())) {
                alert('Block with this name already exists');
                return;
            }
            
            const block = {
                id: Date.now(),
                name: blockName,
                createdAt: new Date().toISOString()
            };
            
            buildingData.blocks.push(block);
            saveData();
            loadBlocksList();
            updateAllSelects();
            
            document.getElementById('blockName').value = '';
            alert('Block added successfully!');
        }

        // Load blocks list
        function loadBlocksList() {
            const blocksList = document.getElementById('blocksList');
            
            if (buildingData.blocks.length === 0) {
                blocksList.innerHTML = `
                    <div class="empty-list">
                        <i class="fas fa-building"></i>
                        <p>No blocks added yet</p>
                    </div>
                `;
                return;
            }
            
            blocksList.innerHTML = buildingData.blocks.map(block => `
                <div class="item-row">
                    <div class="item-name">${block.name}</div>
                    <div class="item-actions">
                        <button class="edit-btn" onclick="editBlock(${block.id})">Edit</button>
                        <button class="delete-btn" onclick="deleteBlock(${block.id})">Delete</button>
                    </div>
                </div>
            `).join('');
        }

        // Edit block
        function editBlock(id) {
            const block = buildingData.blocks.find(b => b.id === id);
            if (!block) return;
            
            const newName = prompt('Edit block name:', block.name);
            if (newName && newName.trim()) {
                block.name = newName.trim();
                block.updatedAt = new Date().toISOString();
                saveData();
                loadBlocksList();
                updateAllSelects();
            }
        }

        // Delete block
        function deleteBlock(id) {
            if (confirm('Are you sure you want to delete this block? All associated floors, rooms, and other data will also be deleted.')) {
                // Get floor IDs of this block
                const blockFloors = buildingData.floors.filter(f => f.blockId === id);
                const floorIds = blockFloors.map(f => f.id);
                
                // Delete associated floors
                buildingData.floors = buildingData.floors.filter(f => f.blockId !== id);
                
                // Delete associated rooms
                buildingData.rooms = buildingData.rooms.filter(r => !floorIds.includes(r.floorId));
                
                // Delete associated washrooms
                buildingData.washrooms = buildingData.washrooms.filter(w => 
                    w.type === 'floor' ? !floorIds.includes(w.floorId) : true
                );
                
                // Delete associated room names
                buildingData.roomNames = buildingData.roomNames.filter(rn => 
                    !buildingData.rooms.some(r => r.id === rn.roomId)
                );
                
                // Delete associated lifts
                buildingData.lifts = buildingData.lifts.filter(l => l.blockId !== id);
                
                // Delete block
                buildingData.blocks = buildingData.blocks.filter(b => b.id !== id);
                
                saveData();
                loadBlocksList();
                updateAllSelects();
                alert('Block and all associated data deleted successfully!');
            }
        }

        // Add floor
        function addFloor() {
            const blockId = parseInt(document.getElementById('blockSelect').value);
            const floorNumber = document.getElementById('floorNumber').value.trim();
            
            if (!blockId) {
                alert('Please select a block');
                return;
            }
            
            if (!floorNumber) {
                alert('Please enter a floor number/name');
                return;
            }
            
            // Check for duplicates in same block
            if (buildingData.floors.find(f => f.blockId === blockId && f.number.toLowerCase() === floorNumber.toLowerCase())) {
                alert('Floor with this name/number already exists in this block');
                return;
            }
            
            const block = buildingData.blocks.find(b => b.id === blockId);
            if (!block) {
                alert('Selected block not found');
                return;
            }
            
            const floor = {
                id: Date.now(),
                blockId: blockId,
                number: floorNumber,
                blockName: block.name,
                createdAt: new Date().toISOString()
            };
            
            buildingData.floors.push(floor);
            saveData();
            updateFloorsList();
            updateAllSelects();
            
            document.getElementById('floorNumber').value = '';
            alert('Floor added successfully!');
        }

        // Update floors list
        function updateFloorsList() {
            const floorsList = document.getElementById('floorsList');
            const blockId = parseInt(document.getElementById('blockSelect').value);
            
            if (!blockId) {
                floorsList.innerHTML = `
                    <div class="empty-list">
                        <i class="fas fa-layer-group"></i>
                        <p>Select a block to view floors</p>
                    </div>
                `;
                return;
            }
            
            const blockFloors = buildingData.floors.filter(f => f.blockId === blockId);
            
            if (blockFloors.length === 0) {
                floorsList.innerHTML = `
                    <div class="empty-list">
                        <i class="fas fa-layer-group"></i>
                        <p>No floors added for this block yet</p>
                    </div>
                `;
                return;
            }
            
            floorsList.innerHTML = blockFloors.map(floor => `
                <div class="item-row">
                    <div class="item-name">${floor.number}</div>
                    <div class="item-actions">
                        <button class="edit-btn" onclick="editFloor(${floor.id})">Edit</button>
                        <button class="delete-btn" onclick="deleteFloor(${floor.id})">Delete</button>
                    </div>
                </div>
            `).join('');
        }

        // Edit floor
        function editFloor(id) {
            const floor = buildingData.floors.find(f => f.id === id);
            if (!floor) return;
            
            const newNumber = prompt('Edit floor number/name:', floor.number);
            if (newNumber && newNumber.trim()) {
                floor.number = newNumber.trim();
                floor.updatedAt = new Date().toISOString();
                saveData();
                updateFloorsList();
                updateAllSelects();
            }
        }

        // Delete floor
        function deleteFloor(id) {
            if (confirm('Are you sure you want to delete this floor? All associated rooms and washrooms will also be deleted.')) {
                // Delete associated rooms
                buildingData.rooms = buildingData.rooms.filter(r => r.floorId !== id);
                
                // Delete associated washrooms
                buildingData.washrooms = buildingData.washrooms.filter(w => w.floorId !== id);
                
                // Delete associated room names
                buildingData.roomNames = buildingData.roomNames.filter(rn => 
                    !buildingData.rooms.some(r => r.id === rn.roomId)
                );
                
                // Delete floor
                buildingData.floors = buildingData.floors.filter(f => f.id !== id);
                
                saveData();
                updateFloorsList();
                updateAllSelects();
                alert('Floor and all associated data deleted successfully!');
            }
        }

        // Add room
        function addRoom() {
            const floorId = parseInt(document.getElementById('floorSelect').value);
            const roomNumber = document.getElementById('roomNumber').value.trim();
            
            if (!floorId) {
                alert('Please select a floor');
                return;
            }
            
            if (!roomNumber) {
                alert('Please enter a room number');
                return;
            }
            
            // Check for duplicates on same floor
            if (buildingData.rooms.find(r => r.floorId === floorId && r.number.toLowerCase() === roomNumber.toLowerCase())) {
                alert('Room with this number already exists on this floor');
                return;
            }
            
            const floor = buildingData.floors.find(f => f.id === floorId);
            if (!floor) {
                alert('Selected floor not found');
                return;
            }
            
            const block = buildingData.blocks.find(b => b.id === floor.blockId);
            
            const room = {
                id: Date.now(),
                floorId: floorId,
                number: roomNumber,
                floorNumber: floor.number,
                blockName: block ? block.name : '',
                createdAt: new Date().toISOString()
            };
            
            buildingData.rooms.push(room);
            saveData();
            updateRoomsList();
            updateAllSelects();
            
            document.getElementById('roomNumber').value = '';
            alert('Room added successfully!');
        }

        // Update rooms list
        function updateRoomsList() {
            const roomsList = document.getElementById('roomsList');
            const floorId = parseInt(document.getElementById('floorSelect').value);
            
            if (!floorId) {
                roomsList.innerHTML = `
                    <div class="empty-list">
                        <i class="fas fa-door-open"></i>
                        <p>Select a floor to view rooms</p>
                    </div>
                `;
                return;
            }
            
            const floorRooms = buildingData.rooms.filter(r => r.floorId === floorId);
            
            if (floorRooms.length === 0) {
                roomsList.innerHTML = `
                    <div class="empty-list">
                        <i class="fas fa-door-open"></i>
                        <p>No rooms added for this floor yet</p>
                    </div>
                `;
                return;
            }
            
            roomsList.innerHTML = floorRooms.map(room => `
                <div class="item-row">
                    <div class="item-name">${room.number}</div>
                    <div class="item-actions">
                        <button class="edit-btn" onclick="editRoom(${room.id})">Edit</button>
                        <button class="delete-btn" onclick="deleteRoom(${room.id})">Delete</button>
                    </div>
                </div>
            `).join('');
        }

        // Edit room
        function editRoom(id) {
            const room = buildingData.rooms.find(r => r.id === id);
            if (!room) return;
            
            const newNumber = prompt('Edit room number:', room.number);
            if (newNumber && newNumber.trim()) {
                room.number = newNumber.trim();
                room.updatedAt = new Date().toISOString();
                saveData();
                updateRoomsList();
                updateAllSelects();
            }
        }

        // Delete room
        function deleteRoom(id) {
            if (confirm('Are you sure you want to delete this room?')) {
                // Remove from room names
                buildingData.roomNames = buildingData.roomNames.filter(rn => rn.roomId !== id);
                // Remove attached washrooms
                buildingData.washrooms = buildingData.washrooms.filter(w => w.roomId !== id);
                // Delete room
                buildingData.rooms = buildingData.rooms.filter(r => r.id !== id);
                
                saveData();
                updateRoomsList();
                updateAllSelects();
                alert('Room deleted successfully!');
            }
        }

        // Add washroom
        function addWashroom() {
            const type = document.getElementById('washroomType').value;
            const floorId = type === 'floor' ? parseInt(document.getElementById('washroomFloorSelect').value) : null;
            const roomId = type === 'attached' ? parseInt(document.getElementById('roomForWashroom').value) : null;
            const washroomName = document.getElementById('washroomName').value.trim();
            
            if (type === 'floor' && !floorId) {
                alert('Please select a floor');
                return;
            }
            
            if (type === 'attached' && !roomId) {
                alert('Please select a room');
                return;
            }
            
            if (!washroomName) {
                alert('Please enter a washroom name/number');
                return;
            }
            
            // Check for duplicates
            if (type === 'floor') {
                if (buildingData.washrooms.find(w => w.type === 'floor' && w.floorId === floorId && w.name.toLowerCase() === washroomName.toLowerCase())) {
                    alert('Washroom with this name already exists on this floor');
                    return;
                }
            } else {
                if (buildingData.washrooms.find(w => w.type === 'attached' && w.roomId === roomId && w.name.toLowerCase() === washroomName.toLowerCase())) {
                    alert('Washroom with this name already exists for this room');
                    return;
                }
            }
            
            const washroom = {
                id: Date.now(),
                type: type,
                floorId: floorId,
                roomId: roomId,
                name: washroomName,
                createdAt: new Date().toISOString()
            };
            
            // Set additional info based on type
            if (type === 'floor' && floorId) {
                const floor = buildingData.floors.find(f => f.id === floorId);
                const block = floor ? buildingData.blocks.find(b => b.id === floor.blockId) : null;
                washroom.location = `${block ? block.name : ''} - ${floor ? floor.number : ''}`;
            } else if (type === 'attached' && roomId) {
                const room = buildingData.rooms.find(r => r.id === roomId);
                const floor = room ? buildingData.floors.find(f => f.id === room.floorId) : null;
                const block = floor ? buildingData.blocks.find(b => b.id === floor.blockId) : null;
                washroom.location = `${block ? block.name : ''} - ${floor ? floor.number : ''} - Room ${room ? room.number : ''}`;
            }
            
            buildingData.washrooms.push(washroom);
            saveData();
            updateWashroomsList();
            updateAllSelects();
            
            document.getElementById('washroomName').value = '';
            alert('Washroom added successfully!');
        }

        // Update washrooms list
        function updateWashroomsList() {
            const washroomsList = document.getElementById('washroomsList');
            
            if (buildingData.washrooms.length === 0) {
                washroomsList.innerHTML = `
                    <div class="empty-list">
                        <i class="fas fa-toilet"></i>
                        <p>No washrooms added yet</p>
                    </div>
                `;
                return;
            }
            
            washroomsList.innerHTML = buildingData.washrooms.map(washroom => `
                <div class="item-row">
                    <div class="item-name">
                        <div>${washroom.name}</div>
                        <small style="color: #666;">${washroom.type === 'floor' ? 'Floor Washroom' : 'Attached to Room'} - ${washroom.location || ''}</small>
                    </div>
                    <div class="item-actions">
                        <button class="edit-btn" onclick="editWashroom(${washroom.id})">Edit</button>
                        <button class="delete-btn" onclick="deleteWashroom(${washroom.id})">Delete</button>
                    </div>
                </div>
            `).join('');
        }

        // Edit washroom
        function editWashroom(id) {
            const washroom = buildingData.washrooms.find(w => w.id === id);
            if (!washroom) return;
            
            const newName = prompt('Edit washroom name:', washroom.name);
            if (newName && newName.trim()) {
                washroom.name = newName.trim();
                washroom.updatedAt = new Date().toISOString();
                saveData();
                updateWashroomsList();
                alert('Washroom updated successfully!');
            }
        }

        // Delete washroom
        function deleteWashroom(id) {
            if (confirm('Are you sure you want to delete this washroom?')) {
                buildingData.washrooms = buildingData.washrooms.filter(w => w.id !== id);
                saveData();
                updateWashroomsList();
                alert('Washroom deleted successfully!');
            }
        }

        // Add lift
        function addLift() {
            const blockId = parseInt(document.getElementById('liftBlockSelect').value);
            const liftName = document.getElementById('liftName').value.trim();
            
            if (!blockId) {
                alert('Please select a block');
                return;
            }
            
            if (!liftName) {
                alert('Please enter a lift name/number');
                return;
            }
            
            // Check for duplicates in same block
            if (buildingData.lifts.find(l => l.blockId === blockId && l.name.toLowerCase() === liftName.toLowerCase())) {
                alert('Lift with this name already exists in this block');
                return;
            }
            
            const block = buildingData.blocks.find(b => b.id === blockId);
            if (!block) {
                alert('Selected block not found');
                return;
            }
            
            const lift = {
                id: Date.now(),
                blockId: blockId,
                name: liftName,
                blockName: block.name,
                createdAt: new Date().toISOString()
            };
            
            buildingData.lifts.push(lift);
            saveData();
            updateLiftsList();
            updateAllSelects();
            
            document.getElementById('liftName').value = '';
            alert('Lift added successfully!');
        }

        // Update lifts list
        function updateLiftsList() {
            const liftsList = document.getElementById('liftsList');
            
            if (buildingData.lifts.length === 0) {
                liftsList.innerHTML = `
                    <div class="empty-list">
                        <i class="fas fa-elevator"></i>
                        <p>No lifts added yet</p>
                    </div>
                `;
                return;
            }
            
            liftsList.innerHTML = buildingData.lifts.map(lift => `
                <div class="item-row">
                    <div class="item-name">${lift.name} (${lift.blockName})</div>
                    <div class="item-actions">
                        <button class="edit-btn" onclick="editLift(${lift.id})">Edit</button>
                        <button class="delete-btn" onclick="deleteLift(${lift.id})">Delete</button>
                    </div>
                </div>
            `).join('');
        }

        // Edit lift
        function editLift(id) {
            const lift = buildingData.lifts.find(l => l.id === id);
            if (!lift) return;
            
            const newName = prompt('Edit lift name:', lift.name);
            if (newName && newName.trim()) {
                lift.name = newName.trim();
                lift.updatedAt = new Date().toISOString();
                saveData();
                updateLiftsList();
                alert('Lift updated successfully!');
            }
        }

        // Delete lift
        function deleteLift(id) {
            if (confirm('Are you sure you want to delete this lift?')) {
                buildingData.lifts = buildingData.lifts.filter(l => l.id !== id);
                saveData();
                updateLiftsList();
                alert('Lift deleted successfully!');
            }
        }

        // Add room name
        function addRoomName() {
            const roomId = parseInt(document.getElementById('roomToName').value);
            const roomName = document.getElementById('roomDescriptiveName').value.trim();
            
            if (!roomId) {
                alert('Please select a room');
                return;
            }
            
            if (!roomName) {
                alert('Please enter a descriptive name for the room');
                return;
            }
            
            const room = buildingData.rooms.find(r => r.id === roomId);
            if (!room) {
                alert('Selected room not found');
                return;
            }
            
            // Check if room already has a name
            const existingName = buildingData.roomNames.find(rn => rn.roomId === roomId);
            if (existingName) {
                existingName.name = roomName;
                existingName.updatedAt = new Date().toISOString();
            } else {
                const floor = buildingData.floors.find(f => f.id === room.floorId);
                const block = floor ? buildingData.blocks.find(b => b.id === floor.blockId) : null;
                
                const roomNameObj = {
                    id: Date.now(),
                    roomId: roomId,
                    name: roomName,
                    roomNumber: room.number,
                    floorNumber: floor ? floor.number : '',
                    blockName: block ? block.name : '',
                    createdAt: new Date().toISOString()
                };
                
                buildingData.roomNames.push(roomNameObj);
            }
            
            saveData();
            updateRoomNamesList();
            updateAllSelects();
            
            document.getElementById('roomDescriptiveName').value = '';
            alert('Room name added successfully!');
        }

        // Update room names list
        function updateRoomNamesList() {
            const roomNamesList = document.getElementById('roomNamesList');
            
            if (buildingData.roomNames.length === 0) {
                roomNamesList.innerHTML = `
                    <div class="empty-list">
                        <i class="fas fa-tag"></i>
                        <p>No rooms named yet</p>
                    </div>
                `;
                return;
            }
            
            roomNamesList.innerHTML = buildingData.roomNames.map(rn => `
                <div class="item-row">
                    <div class="item-name">
                        <div>${rn.name}</div>
                        <small style="color: #666;">${rn.blockName} - ${rn.floorNumber} - Room ${rn.roomNumber}</small>
                    </div>
                    <div class="item-actions">
                        <button class="edit-btn" onclick="editRoomName(${rn.id})">Edit</button>
                        <button class="delete-btn" onclick="deleteRoomName(${rn.id})">Delete</button>
                    </div>
                </div>
            `).join('');
        }

        // Edit room name
        function editRoomName(id) {
            const roomName = buildingData.roomNames.find(rn => rn.id === id);
            if (!roomName) return;
            
            const newName = prompt('Edit room name:', roomName.name);
            if (newName && newName.trim()) {
                roomName.name = newName.trim();
                roomName.updatedAt = new Date().toISOString();
                saveData();
                updateRoomNamesList();
                alert('Room name updated successfully!');
            }
        }

        // Delete room name
        function deleteRoomName(id) {
            if (confirm('Are you sure you want to delete this room name?')) {
                buildingData.roomNames = buildingData.roomNames.filter(rn => rn.id !== id);
                saveData();
                updateRoomNamesList();
                updateAllSelects();
                alert('Room name deleted successfully!');
            }
        }

        // Complete setup
        function completeSetup() {
            // Show summary section
            document.getElementById('step6').style.display = 'none';
            document.getElementById('summarySection').style.display = 'block';
            document.getElementById('progressSteps').style.display = 'none';
            document.querySelector('.progress-container').style.display = 'none';
            
            // Update summary
            updateSummary();
        }

        // Update summary
        function updateSummary() {
            // Blocks
            document.getElementById('blocksCount').textContent = buildingData.blocks.length;
            document.getElementById('blocksSummary').innerHTML = buildingData.blocks.map(b => 
                `<div class="summary-item">${b.name}</div>`
            ).join('');
            
            // Floors
            document.getElementById('floorsCount').textContent = buildingData.floors.length;
            document.getElementById('floorsSummary').innerHTML = buildingData.floors.map(f => {
                const block = buildingData.blocks.find(b => b.id === f.blockId);
                return `<div class="summary-item">${block ? block.name : ''} - ${f.number}</div>`;
            }).join('');
            
            // Rooms
            document.getElementById('roomsCount').textContent = buildingData.rooms.length;
            document.getElementById('roomsSummary').innerHTML = buildingData.rooms.map(r => {
                const floor = buildingData.floors.find(f => f.id === r.floorId);
                const block = floor ? buildingData.blocks.find(b => b.id === floor.blockId) : null;
                return `<div class="summary-item">${block ? block.name : ''} - ${floor ? floor.number : ''} - Room ${r.number}</div>`;
            }).join('');
            
            // Washrooms
            document.getElementById('washroomsCount').textContent = buildingData.washrooms.length;
            document.getElementById('washroomsSummary').innerHTML = buildingData.washrooms.map(w => 
                `<div class="summary-item">${w.name} (${w.type === 'floor' ? 'Floor Washroom' : 'Attached to Room'})</div>`
            ).join('');
            
            // Lifts
            document.getElementById('liftsCount').textContent = buildingData.lifts.length;
            document.getElementById('liftsSummary').innerHTML = buildingData.lifts.map(l => 
                `<div class="summary-item">${l.name} (${l.blockName})</div>`
            ).join('');
            
            // Named Rooms
            document.getElementById('namedRoomsCount').textContent = buildingData.roomNames.length;
            document.getElementById('namedRoomsSummary').innerHTML = buildingData.roomNames.map(rn => 
                `<div class="summary-item">${rn.name} (Room ${rn.roomNumber})</div>`
            ).join('');
        }

        // Export data
        function exportData() {
            const dataStr = JSON.stringify(buildingData, null, 2);
            const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);
            
            const exportFileDefaultName = 'building-infrastructure-' + new Date().toISOString().split('T')[0] + '.json';
            
            const linkElement = document.createElement('a');
            linkElement.setAttribute('href', dataUri);
            linkElement.setAttribute('download', exportFileDefaultName);
            linkElement.click();
            
            alert('Configuration exported successfully!');
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
                
                saveData();
                location.reload();
            }
        }
    </script>
</body>
</html>
@endsection