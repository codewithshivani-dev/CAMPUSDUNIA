@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room</title>
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
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }

        .header-content h1 i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 12px;
            font-size: 1.3rem;
        }

        .header-content p {
            opacity: 0.9;
            font-size: 1rem;
            margin: 0;
        }

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

        .back-btn:hover {
            background: var(--primary-gradient)!important;
            color: white!important;
            transform: translateY(-2px);
            text-decoration: none;
        }

        .navigation {
            display: flex;
            gap: 10px;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .nav-btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--border-color);
        }

        .nav-btn:hover {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
            text-decoration: none;
        }

        .nav-btn.active {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
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

        .input-with-icon {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            z-index: 1;
        }

        .input-with-icon input {
            padding-left: 42px;
        }

        .inline-input-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .inline-input-group input {
            flex: 1;
            padding: 10px 14px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-size: 0.9rem;
            transition: all 0.3s;
            background: #f8fafc;
        }

        .inline-input-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            background: white;
        }

        .inline-input-group span {
            font-size: 0.9rem;
            color: var(--text-muted);
            white-space: nowrap;
            font-weight: 500;
        }

        .room-specs-section {
            margin-top: 2rem;
            border-top: 2px solid var(--border-color);
            padding-top: 1.5rem;
        }

        .room-specs-section h3 {
            color: var(--text-dark);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .room-specs-section h3 i {
            color: var(--primary-color);
        }

        .specs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .spec-group {
            background: #f8fafc;
            padding: 1.25rem;
            border-radius: 14px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .spec-group:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.08);
        }

        .spec-group h4 {
            color: var(--text-dark);
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
        }

        .spec-group h4 i {
            color: var(--primary-color);
            font-size: 1rem;
        }

        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkbox-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary-color);
        }

        .checkbox-item label {
            font-size: 0.875rem;
            color: #555;
            cursor: pointer;
        }

        .count-inputs {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .count-input {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .count-input input {
            width: 70px;
            padding: 8px 10px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-size: 0.875rem;
            text-align: center;
            transition: all 0.3s;
            background: #f8fafc;
        }

        .count-input input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            background: white;
        }

        .count-input label {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .area-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .area-option {
            padding: 0.6rem;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.85rem;
            font-weight: 500;
            background: white;
        }

        .area-option:hover {
            border-color: var(--primary-color);
        }

        .area-option.selected {
            background: var(--primary-gradient);
            color: white;
            border-color: var(--primary-color);
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

        .btn-danger {
            background: var(--danger-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--border-color);
            flex-wrap: wrap;
        }

        /* Loading Overlay */
        .loading-overlay {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 400px;
        }

        .loading-card {
            text-align: center;
            padding: 3rem;
        }

        .loading-spinner {
            display: inline-block;
            width: 48px;
            height: 48px;
            border: 4px solid var(--border-color);
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        }

        .btn .loading-spinner {
            width: 20px;
            height: 20px;
            border-width: 3px;
            margin-bottom: 0;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-card p {
            color: var(--text-muted);
            font-size: 1rem;
            font-weight: 500;
        }

        /* Error State */
        .error-state {
            text-align: center;
            padding: 3rem;
        }

        .error-state i {
            font-size: 4rem;
            color: #ef4444;
            margin-bottom: 1rem;
        }

        .error-state h4 {
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .error-state p {
            color: var(--text-muted);
            margin-bottom: 1.5rem;
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
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast.error {
            background: var(--danger-gradient);
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .header-content h1 {
                justify-content: center;
            }

            .form-card {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .navigation {
                flex-direction: column;
            }

            .specs-grid {
                grid-template-columns: 1fr;
            }

            .area-selector {
                grid-template-columns: 1fr;
            }

            .count-inputs {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <h1><i class="fas fa-edit"></i> Edit Room</h1>
                <p id="headerRoomInfo">Loading room details...</p>
            </div>
            <a href="{{ route('rooms.list') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to View Rooms
            </a>
        </div>

        <!-- Navigation -->
        <!-- <div class="navigation">
            <a href="{{ route('infrastructure.main') }}" class="nav-btn">
                <i class="fas fa-home"></i> Main Menu
            </a>
            <a href="{{ route('buildings.page') }}" class="nav-btn">
                <i class="fas fa-university"></i> Buildings
            </a>
            <a href="{{ route('blocks.page') }}" class="nav-btn">
                <i class="fas fa-building"></i> Blocks
            </a>
            <a href="{{ route('floors.page') }}" class="nav-btn">
                <i class="fas fa-layer-group"></i> Floors
            </a>
            <a href="{{ route('rooms.page') }}" class="nav-btn">
                <i class="fas fa-door-open"></i> Add Room
            </a>
            <a href="{{ route('rooms.list') }}" class="nav-btn">
                <i class="fas fa-list"></i> View Rooms
            </a>
            <a href="#" class="nav-btn active">
                <i class="fas fa-edit"></i> Edit Room
            </a>
        </div> -->

        <!-- Edit Room Form -->
        <div class="form-card" id="editFormContainer">
            <!-- Loading State -->
            <div class="loading-overlay" id="loadingState">
                <div class="loading-card">
                    <div class="loading-spinner"></div>
                    <p>Loading room details...</p>
                </div>
            </div>

            <!-- Error State -->
            <div class="error-state" id="errorState" style="display: none;">
                <i class="fas fa-exclamation-circle"></i>
                <h4>Room Not Found</h4>
                <p id="errorMessage">The room you are trying to edit could not be found.</p>
                <a href="{{ route('rooms.list') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to View Rooms
                </a>
            </div>

            <!-- Form Content -->
            <div id="formContent" style="display: none;">
                <h2><i class="fas fa-edit"></i> <span id="formTitle">Edit Room</span></h2>
                
                <form id="editRoomForm">
                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="roomNumber" class="form-label">Room Number <span style="color: #dc3545;">*</span></label>
                                <div class="input-with-icon">
                                    <i class="fas fa-hashtag input-icon"></i>
                                    <input type="text" id="roomNumber" class="form-control" placeholder="e.g., 101, 201, Lab-1" maxlength="20">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="roomName" class="form-label">Room Name (Optional)</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-tag input-icon"></i>
                                    <input type="text" id="roomName" class="form-control" placeholder="e.g., Principal's Office, Computer Lab" maxlength="100">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="roomDescription" class="form-label">Description (Optional)</label>
                        <textarea id="roomDescription" class="form-control" rows="2" placeholder="Brief description of the room..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="roomType" class="form-label">Room Type</label>
                                <select id="roomType" class="form-control" onchange="toggleCustomRoomType()">
                                    <option value="classroom">Classroom</option>
                                    <option value="lab">Laboratory</option>
                                    <option value="office">Office</option>
                                    <option value="conference">Conference</option>
                                    <option value="library">Library</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="customTypeContainer" style="display: none;">
                            <div class="form-group">
                                <label for="customRoomType" class="form-label">Custom Room Type</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-keyboard input-icon"></i>
                                    <input type="text" id="customRoomType" class="form-control" placeholder="Enter custom room type" maxlength="50">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Info (Read-only) -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Building</label>
                                <input type="text" id="buildingName" class="form-control" readonly disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Block</label>
                                <input type="text" id="blockName" class="form-control" readonly disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Floor</label>
                                <input type="text" id="floorName" class="form-control" readonly disabled>
                            </div>
                        </div>
                    </div>

                    <!-- Room Specifications Section -->
                    <div class="room-specs-section">
                        <h3><i class="fas fa-cogs"></i> Room Specifications</h3>
                        
                        <div class="specs-grid">
                            <!-- Room Area -->
                            <div class="spec-group">
                                <h4><i class="fas fa-ruler-combined"></i> Room Area (Optional)</h4>
                                <div class="area-selector">
                                    <div class="area-option selected" id="areaSuper" onclick="selectAreaType('super')">
                                        Super Area
                                    </div>
                                    <div class="area-option" id="areaCarpet" onclick="selectAreaType('carpet')">
                                        Carpet Area
                                    </div>
                                </div>
                                <input type="hidden" id="areaType" value="super">
                                <div class="inline-input-group" style="margin-top: 0.5rem;">
                                    <input type="number" id="roomArea" placeholder="Area in sq. ft." min="1" step="0.01">
                                    <span>sq. ft.</span>
                                </div>
                            </div>

                            <!-- Room Capacity -->
                            <div class="spec-group">
                                <h4><i class="fas fa-users"></i> Room Capacity (Optional)</h4>
                                <div class="inline-input-group">
                                    <input type="number" id="roomCapacity" placeholder="Number of persons" min="1">
                                    <span>persons</span>
                                </div>
                            </div>

                            <!-- Electrical Items Count -->
                            <div class="spec-group">
                                <h4><i class="fas fa-lightbulb"></i> Electrical Items (Optional)</h4>
                                <div class="count-inputs">
                                    <div class="count-input">
                                        <input type="number" id="lightsCount" placeholder="0" min="0" value="0">
                                        <label>Lights</label>
                                    </div>
                                    <div class="count-input">
                                        <input type="number" id="fansCount" placeholder="0" min="0" value="0">
                                        <label>Fans</label>
                                    </div>
                                    <div class="count-input">
                                        <input type="number" id="acCount" placeholder="0" min="0" value="0">
                                        <label>AC Units</label>
                                    </div>
                                    <div class="count-input">
                                        <input type="number" id="socketsCount" placeholder="0" min="0" value="0">
                                        <label>Sockets</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Fire Safety -->
                            <div class="spec-group">
                                <h4><i class="fas fa-fire-extinguisher"></i> Fire Safety (Optional)</h4>
                                <div class="checkbox-group">
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="hasFireExtinguisher" value="1">
                                        <label for="hasFireExtinguisher">Fire Extinguisher</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="hasFireAlarm" value="1">
                                        <label for="hasFireAlarm">Fire Alarm</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="hasSmokeDetector" value="1">
                                        <label for="hasSmokeDetector">Smoke Detector</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="hasEmergencyExit" value="1">
                                        <label for="hasEmergencyExit">Emergency Exit</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Other Amenities -->
                            <div class="spec-group">
                                <h4><i class="fas fa-star"></i> Other Amenities (Optional)</h4>
                                <div class="checkbox-group">
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="hasWashroom" value="1">
                                        <label for="hasWashroom">Attached Washroom</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="hasProjector" value="1">
                                        <label for="hasProjector">Projector</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="hasWhiteboard" value="1">
                                        <label for="hasWhiteboard">Whiteboard</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="hasSmartBoard" value="1">
                                        <label for="hasSmartBoard">Smart Board</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="hasWifi" value="1">
                                        <label for="hasWifi">Wi-Fi Access</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="hasWaterCooler" value="1">
                                        <label for="hasWaterCooler">Water Cooler</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="hasCCTV" value="1">
                                        <label for="hasCCTV">CCTV Camera</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('rooms.list') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancel
                        </a>
                        <button type="button" class="btn btn-danger" onclick="deleteRoom()" id="deleteBtn">
                            <i class="fas fa-trash"></i> Delete Room
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i> Update Room
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message"></span>
    </div>

    <script>
        const API_BASE_URL = '{{ url('/') }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';
        // Get room ID from Blade variable passed from route
        const roomId = '{{ $roomId ?? "" }}';
        let selectedAreaType = 'super';

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (!roomId) {
                showError('No room ID provided. Please select a room to edit from the View Rooms page.');
                return;
            }
            
            loadRoomData(roomId);
            
            document.getElementById('editRoomForm').addEventListener('submit', function(e) {
                e.preventDefault();
                updateRoom();
            });
        });

        // Load room data from API
        async function loadRoomData(roomId) {
            try {
                const response = await fetch(`${API_BASE_URL}/rooms/${roomId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    populateForm(result.data);
                    document.getElementById('loadingState').style.display = 'none';
                    document.getElementById('formContent').style.display = 'block';
                    
                    // Update header
                    document.getElementById('headerRoomInfo').textContent = 
                        `Editing: ${result.data.room_number}${result.data.room_name ? ' - ' + result.data.room_name : ''}`;
                } else {
                    showError(result.message || 'Room not found');
                }
            } catch (error) {
                console.error('Error loading room:', error);
                showError('Failed to load room details. Please try again.');
            }
        }

        // Populate form with room data
        function populateForm(room) {
            // Basic info
            document.getElementById('roomNumber').value = room.room_number || '';
            document.getElementById('roomName').value = room.room_name || '';
            document.getElementById('roomDescription').value = room.description || '';
            
            // Room type
            const isCustomType = room.room_type === 'other' && room.custom_room_type;
            document.getElementById('roomType').value = room.room_type || 'classroom';
            
            if (isCustomType) {
                document.getElementById('customTypeContainer').style.display = 'block';
                document.getElementById('customRoomType').value = room.custom_room_type || '';
            }
            
            // Location (read-only)
            document.getElementById('buildingName').value = room.building ? room.building.name : 'Unknown';
            document.getElementById('blockName').value = room.block ? room.block.name : 'Unknown';
            document.getElementById('floorName').value = room.floor ? room.floor.floor_number : 'Unknown';
            
            // Update form title
            document.getElementById('formTitle').textContent = 
                `Edit Room: ${room.room_number}${room.room_name ? ' - ' + room.room_name : ''}`;
            
            // Area
            if (room.area_type) {
                selectAreaType(room.area_type);
            }
            document.getElementById('roomArea').value = room.area || '';
            
            // Capacity
            document.getElementById('roomCapacity').value = room.capacity || '';
            
            // Electrical items
            document.getElementById('lightsCount').value = room.lights_count || 0;
            document.getElementById('fansCount').value = room.fans_count || 0;
            document.getElementById('acCount').value = room.ac_count || 0;
            document.getElementById('socketsCount').value = room.sockets_count || 0;
            
            // Amenities
            document.getElementById('hasWashroom').checked = room.has_washroom || false;
            document.getElementById('hasProjector').checked = room.has_projector || false;
            document.getElementById('hasWhiteboard').checked = room.has_whiteboard || false;
            document.getElementById('hasSmartBoard').checked = room.has_smart_board || false;
            document.getElementById('hasWifi').checked = room.has_wifi || false;
            document.getElementById('hasWaterCooler').checked = room.has_water_cooler || false;
            document.getElementById('hasCCTV').checked = room.has_cctv || false;
            
            // Fire safety
            document.getElementById('hasFireExtinguisher').checked = room.has_fire_extinguisher || false;
            document.getElementById('hasFireAlarm').checked = room.has_fire_alarm || false;
            document.getElementById('hasSmokeDetector').checked = room.has_smoke_detector || false;
            document.getElementById('hasEmergencyExit').checked = room.has_emergency_exit || false;
        }

        // Toggle custom room type
        function toggleCustomRoomType() {
            const roomType = document.getElementById('roomType').value;
            const customContainer = document.getElementById('customTypeContainer');
            customContainer.style.display = roomType === 'other' ? 'block' : 'none';
            
            if (roomType !== 'other') {
                document.getElementById('customRoomType').value = '';
            }
        }

        // Select area type
        function selectAreaType(type) {
            selectedAreaType = type;
            document.getElementById('areaType').value = type;
            
            document.getElementById('areaSuper').classList.remove('selected');
            document.getElementById('areaCarpet').classList.remove('selected');
            
            if (type === 'super') {
                document.getElementById('areaSuper').classList.add('selected');
            } else {
                document.getElementById('areaCarpet').classList.add('selected');
            }
        }

        // Update room via API
        async function updateRoom() {
            const roomNumber = document.getElementById('roomNumber').value.trim();
            const roomName = document.getElementById('roomName').value.trim();
            const description = document.getElementById('roomDescription').value.trim();
            const roomType = document.getElementById('roomType').value;
            const customRoomType = document.getElementById('customRoomType')?.value.trim() || null;
            const roomArea = parseFloat(document.getElementById('roomArea').value) || null;
            const roomCapacity = parseInt(document.getElementById('roomCapacity').value) || null;
            
            if (!roomNumber) {
                showToast('Please enter a room number', 'error');
                return;
            }
            
            if (roomType === 'other' && !customRoomType) {
                showToast('Please enter a custom room type', 'error');
                return;
            }
            
            const roomData = {
                room_number: roomNumber,
                room_name: roomName || null,
                description: description || null,
                room_type: roomType,
                custom_room_type: roomType === 'other' ? customRoomType : null,
                area: roomArea,
                area_type: selectedAreaType,
                capacity: roomCapacity,
                lights_count: parseInt(document.getElementById('lightsCount').value) || 0,
                fans_count: parseInt(document.getElementById('fansCount').value) || 0,
                ac_count: parseInt(document.getElementById('acCount').value) || 0,
                sockets_count: parseInt(document.getElementById('socketsCount').value) || 0,
                has_fire_extinguisher: document.getElementById('hasFireExtinguisher').checked,
                has_fire_alarm: document.getElementById('hasFireAlarm').checked,
                has_smoke_detector: document.getElementById('hasSmokeDetector').checked,
                has_emergency_exit: document.getElementById('hasEmergencyExit').checked,
                has_washroom: document.getElementById('hasWashroom').checked,
                has_projector: document.getElementById('hasProjector').checked,
                has_whiteboard: document.getElementById('hasWhiteboard').checked,
                has_smart_board: document.getElementById('hasSmartBoard').checked,
                has_wifi: document.getElementById('hasWifi').checked,
                has_water_cooler: document.getElementById('hasWaterCooler').checked,
                has_cctv: document.getElementById('hasCCTV').checked,
                has_ac: parseInt(document.getElementById('acCount').value) > 0 ? 'yes' : 'no',
            };
            
            const submitBtn = document.getElementById('submitBtn');
            const originalHTML = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="loading-spinner"></span> Updating...';
            submitBtn.disabled = true;
            
            try {
                const response = await fetch(`${API_BASE_URL}/rooms/${roomId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(roomData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast('Room updated successfully!', 'success');
                    
                    // Update header info
                    document.getElementById('headerRoomInfo').textContent = 
                        `Editing: ${roomNumber}${roomName ? ' - ' + roomName : ''}`;
                    document.getElementById('formTitle').textContent = 
                        `Edit Room: ${roomNumber}${roomName ? ' - ' + roomName : ''}`;
                } else {
                    if (result.errors) {
                        let errorMessage = '';
                        Object.values(result.errors).forEach(errors => {
                            errorMessage += errors.join(', ') + ' ';
                        });
                        showToast(errorMessage || 'Validation failed', 'error');
                    } else {
                        showToast(result.message || 'Failed to update room', 'error');
                    }
                }
            } catch (error) {
                console.error('Error updating room:', error);
                showToast('An error occurred. Please try again.', 'error');
            } finally {
                submitBtn.innerHTML = originalHTML;
                submitBtn.disabled = false;
            }
        }

        // Delete room via API
        async function deleteRoom() {
            if (!confirm('Are you sure you want to delete this room? This action cannot be undone.')) {
                return;
            }
            
            const deleteBtn = document.getElementById('deleteBtn');
            const originalHTML = deleteBtn.innerHTML;
            deleteBtn.innerHTML = '<span class="loading-spinner"></span> Deleting...';
            deleteBtn.disabled = true;
            
            try {
                const response = await fetch(`${API_BASE_URL}/rooms/${roomId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast('Room deleted successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route("rooms.list") }}';
                    }, 1000);
                } else {
                    showToast(result.message || 'Failed to delete room', 'error');
                }
            } catch (error) {
                console.error('Error deleting room:', error);
                showToast('Failed to delete room', 'error');
            } finally {
                deleteBtn.innerHTML = originalHTML;
                deleteBtn.disabled = false;
            }
        }

        // Show error state
        function showError(message) {
            document.getElementById('loadingState').style.display = 'none';
            document.getElementById('errorState').style.display = 'block';
            document.getElementById('errorMessage').textContent = message;
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            
            toastMessage.textContent = message;
            
            toast.classList.remove('error');
            
            if (type === 'error') {
                toast.classList.add('error');
                toast.querySelector('i').className = 'fas fa-exclamation-circle';
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