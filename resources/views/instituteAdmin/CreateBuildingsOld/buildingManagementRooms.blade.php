@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room</title>
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

        .room-type-selector {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 0.75rem;
        }

        .room-type-option {
            padding: 0.75rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
            font-weight: 500;
            color: var(--text-muted);
        }

        .room-type-option i {
            display: block;
            font-size: 1.5rem;
            margin-bottom: 6px;
            color: var(--primary-color);
        }

        .room-type-option:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.1);
        }

        .room-type-option.selected {
            background: var(--primary-gradient);
            color: white;
            border-color: var(--primary-color);
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }

        .room-type-option.selected i {
            color: white;
        }

        #customRoomTypeContainer {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
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
        }

        .count-input input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
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

        .washroom-type-selector {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .washroom-type-option {
            padding: 0.6rem;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.8rem;
            font-weight: 500;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .washroom-type-option:hover {
            border-color: var(--primary-color);
        }

        .washroom-type-option.selected {
            background: var(--primary-gradient);
            color: white;
            border-color: var(--primary-color);
        }

        .inline-input {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .inline-input input {
            flex: 1;
            padding: 8px 12px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-size: 0.875rem;
            transition: all 0.3s;
        }

        .inline-input input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .inline-input span {
            font-size: 0.85rem;
            color: var(--text-muted);
            white-space: nowrap;
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

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--border-color);
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

        .toast.warning {
            background: var(--warning-gradient);
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
            
            .room-type-selector {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .washroom-type-selector {
                grid-template-columns: repeat(3, 1fr);
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
                <h1><i class="fas fa-door-open"></i> Add Room</h1>
                <p>Add a new room with complete specifications to a building floor</p>
            </div>
            <a href="{{ route('rooms.view') }}" class="back-btn">
                 View Rooms
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
            <a href="{{ route('rooms.page') }}" class="nav-btn active">
                <i class="fas fa-plus-circle"></i> Add Room
            </a>
            <a href="{{ route('rooms.view') }}" class="nav-btn">
                <i class="fas fa-list"></i> View Rooms
            </a>
        </div> -->

        <!-- Add Room Form -->
        <div class="form-card">
            <h2><i class="fas fa-plus-circle"></i> Add New Room</h2>
            
            <form id="add-room-form">
                <!-- Basic Information -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="buildingId" class="form-label">Building <span style="color: #dc3545;">*</span></label>
                            <select id="buildingId" class="form-control" onchange="loadBlocks()">
                                <option value="">Select Building</option>
                                @foreach($buildings as $building)
                                    <option value="{{ $building->id }}">{{ $building->name }} ({{ $building->code }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="blockSelect" class="form-label">Select Block <span style="color: #dc3545;">*</span></label>
                            <select id="blockSelect" class="form-control" onchange="updateFloorSelect()">
                                <option value="">Select a building block</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="floorSelect" class="form-label">Select Floor <span style="color: #dc3545;">*</span></label>
                            <select id="floorSelect" class="form-control">
                                <option value="">Select a floor</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="roomNumber" class="form-label">Room Number <span style="color: #dc3545;">*</span></label>
                            <div class="input-with-icon">
                                <i class="fas fa-hashtag input-icon"></i>
                                <input type="text" id="roomNumber" class="form-control" placeholder="e.g., 101, 201, Lab-1" maxlength="20">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="roomName" class="form-label">Room Name (Optional)</label>
                            <div class="input-with-icon">
                                <i class="fas fa-tag input-icon"></i>
                                <input type="text" id="roomName" class="form-control" placeholder="e.g., Principal's Office, Computer Lab" maxlength="100">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="roomDescription" class="form-label">Description (Optional)</label>
                            <textarea id="roomDescription" class="form-control" rows="2" placeholder="Brief description of the room..."></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Room Type -->
                <div class="form-group">
                    <label class="form-label">Room Type <span style="color: #dc3545;">*</span></label>
                    <div class="room-type-selector">
                        <div class="room-type-option selected" onclick="selectRoomType('classroom')">
                            <i class="fas fa-chalkboard"></i>
                            <span>Classroom</span>
                        </div>
                        <div class="room-type-option" onclick="selectRoomType('lab')">
                            <i class="fas fa-flask"></i>
                            <span>Laboratory</span>
                        </div>
                        <div class="room-type-option" onclick="selectRoomType('office')">
                            <i class="fas fa-briefcase"></i>
                            <span>Office</span>
                        </div>
                        <div class="room-type-option" onclick="selectRoomType('conference')">
                            <i class="fas fa-users"></i>
                            <span>Conference</span>
                        </div>
                        <div class="room-type-option" onclick="selectRoomType('library')">
                            <i class="fas fa-book"></i>
                            <span>Library</span>
                        </div>
                        <div class="room-type-option" onclick="selectRoomType('other')">
                            <i class="fas fa-door-closed"></i>
                            <span>Other</span>
                        </div>
                    </div>
                    <input type="hidden" id="roomType" value="classroom">
                    
                    <div id="customRoomTypeContainer" style="display: none; margin-top: 12px;">
                        <div class="input-with-icon">
                            <i class="fas fa-keyboard input-icon"></i>
                            <input type="text" id="customRoomType" class="form-control" 
                                   placeholder="Enter custom room type (e.g., Auditorium, Gym, Storage)" 
                                   maxlength="50" oninput="updateCustomRoomType()">
                        </div>
                        <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 5px;">
                            Specify your custom room type
                        </small>
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
                                <div class="area-option selected" onclick="selectAreaType('super')">
                                    Super Area
                                </div>
                                <div class="area-option" onclick="selectAreaType('carpet')">
                                    Carpet Area
                                </div>
                            </div>
                            <input type="hidden" id="areaType" value="super">
                            <div class="inline-input">
                                <input type="number" id="roomArea" placeholder="Area in sq. ft." min="1" step="0.01">
                                <span>sq. ft.</span>
                            </div>
                        </div>

                        <!-- Room Capacity -->
                        <div class="spec-group">
                            <h4><i class="fas fa-users"></i> Room Capacity (Optional)</h4>
                            <div class="inline-input">
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

                        <!-- Washroom Details -->
                        <div class="spec-group">
                            <h4><i class="fas fa-toilet"></i> Washroom (Optional)</h4>
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="hasWashroom" value="1" onchange="toggleWashroomDetails()">
                                    <label for="hasWashroom">Attached Washroom</label>
                                </div>
                            </div>
                            
                            <div id="washroomDetails" style="display: none; margin-top: 0.75rem;">
                                <div class="washroom-type-selector">
                                    <div class="washroom-type-option selected" onclick="selectWashroomGender('male')">
                                        <i class="fas fa-male"></i> Male
                                    </div>
                                    <div class="washroom-type-option" onclick="selectWashroomGender('female')">
                                        <i class="fas fa-female"></i> Female
                                    </div>
                                    <div class="washroom-type-option" onclick="selectWashroomGender('unisex')">
                                        <i class="fas fa-restroom"></i> Unisex
                                    </div>
                                </div>
                                <input type="hidden" id="washroomGender" value="male">
                                
                                <div class="inline-input" style="margin-top: 0.5rem;">
                                    <input type="number" id="washroomCapacity" placeholder="Capacity" min="1" max="10">
                                    <span>persons</span>
                                </div>
                            </div>
                        </div>

                        <!-- Other Amenities -->
                        <div class="spec-group">
                            <h4><i class="fas fa-star"></i> Other Amenities (Optional)</h4>
                            <div class="checkbox-group">
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
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-redo"></i> Clear Form
                    </button>
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <i class="fas fa-plus"></i> Add Room
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message">Room added successfully!</span>
    </div>

    <script>
        const API_BASE_URL = '{{ url('/') }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';
        
        let selectedRoomType = 'classroom';
        let selectedAreaType = 'super';
        let selectedWashroomGender = 'male';
        let customRoomTypeValue = '';

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('add-room-form').addEventListener('submit', function(e) {
                e.preventDefault();
                addRoom();
            });
        });

        // Load blocks
        async function loadBlocks() {
            const buildingId = document.getElementById('buildingId').value;
            const blockSelect = document.getElementById('blockSelect');
            const floorSelect = document.getElementById('floorSelect');
            
            floorSelect.innerHTML = '<option value="">Select a floor</option>';
            
            if (!buildingId) {
                blockSelect.innerHTML = '<option value="">Select a building block</option>';
                return;
            }

            blockSelect.innerHTML = '<option value="">Loading blocks...</option>';

            try {
                const response = await fetch(`${API_BASE_URL}/rooms/blocks/${buildingId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    let options = '<option value="">Select a building block</option>';
                    result.data.forEach(block => {
                        options += `<option value="${block.id}">${escapeHtml(block.name)}</option>`;
                    });
                    blockSelect.innerHTML = options;
                } else {
                    blockSelect.innerHTML = '<option value="">No blocks found</option>';
                }
            } catch (error) {
                console.error('Error loading blocks:', error);
                blockSelect.innerHTML = '<option value="">Error loading blocks</option>';
            }
        }

        // Update floor select
        async function updateFloorSelect() {
            const blockId = document.getElementById('blockSelect').value;
            const floorSelect = document.getElementById('floorSelect');
            
            if (!blockId) {
                floorSelect.innerHTML = '<option value="">Select a floor</option>';
                return;
            }

            floorSelect.innerHTML = '<option value="">Loading floors...</option>';
            
            try {
                const response = await fetch(`${API_BASE_URL}/rooms/floors-by-block/${blockId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    let options = '<option value="">Select a floor</option>';
                    result.data.forEach(floor => {
                        options += `<option value="${floor.id}">${escapeHtml(floor.floor_number)}</option>`;
                    });
                    floorSelect.innerHTML = options;
                } else {
                    floorSelect.innerHTML = '<option value="">No floors found</option>';
                }
            } catch (error) {
                console.error('Error loading floors:', error);
                floorSelect.innerHTML = '<option value="">Error loading floors</option>';
            }
        }

        // Select room type
        function selectRoomType(type) {
            selectedRoomType = type;
            document.querySelectorAll('.room-type-option').forEach(option => {
                option.classList.remove('selected');
            });
            event.target.closest('.room-type-option').classList.add('selected');
            
            const customContainer = document.getElementById('customRoomTypeContainer');
            if (type === 'other') {
                customContainer.style.display = 'block';
                setTimeout(() => {
                    document.getElementById('customRoomType').focus();
                }, 100);
            } else {
                customContainer.style.display = 'none';
                customRoomTypeValue = '';
                document.getElementById('customRoomType').value = '';
            }
            
            document.getElementById('roomType').value = type;
        }

        // Update custom room type
        function updateCustomRoomType() {
            customRoomTypeValue = document.getElementById('customRoomType').value.trim();
        }

        // Select area type
        function selectAreaType(type) {
            selectedAreaType = type;
            document.querySelectorAll('.area-option').forEach(option => {
                option.classList.remove('selected');
            });
            event.target.closest('.area-option').classList.add('selected');
            document.getElementById('areaType').value = type;
        }

        // Select washroom gender
        function selectWashroomGender(gender) {
            selectedWashroomGender = gender;
            document.getElementById('washroomGender').value = gender;
            
            document.querySelectorAll('.washroom-type-option').forEach(option => {
                option.classList.remove('selected');
            });
            event.target.closest('.washroom-type-option').classList.add('selected');
        }

        // Toggle washroom details
        function toggleWashroomDetails() {
            const hasWashroom = document.getElementById('hasWashroom').checked;
            document.getElementById('washroomDetails').style.display = hasWashroom ? 'block' : 'none';
        }

        // Add room
        async function addRoom() {
            const buildingId = document.getElementById('buildingId').value;
            const blockId = document.getElementById('blockSelect').value;
            const floorId = document.getElementById('floorSelect').value;
            const roomNumber = document.getElementById('roomNumber').value.trim();
            const roomName = document.getElementById('roomName').value.trim();
            const description = document.getElementById('roomDescription').value.trim();
            const roomArea = parseFloat(document.getElementById('roomArea').value) || null;
            const roomCapacity = parseInt(document.getElementById('roomCapacity').value) || null;
            
            if (!buildingId) {
                showToast('Please select a building', 'error');
                return;
            }

            if (!blockId) {
                showToast('Please select a block', 'error');
                return;
            }
            
            if (!floorId) {
                showToast('Please select a floor', 'error');
                return;
            }
            
            if (!roomNumber) {
                showToast('Please enter a room number', 'error');
                return;
            }
            
            if (selectedRoomType === 'other' && !customRoomTypeValue) {
                showToast('Please enter a custom room type', 'error');
                return;
            }
            
            const roomData = {
                building_id: buildingId,
                block_id: blockId,
                floor_id: floorId,
                room_number: roomNumber,
                room_name: roomName || null,
                room_type: selectedRoomType,
                custom_room_type: selectedRoomType === 'other' ? customRoomTypeValue : null,
                description: description || null,
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
                washroom_gender: document.getElementById('hasWashroom').checked ? selectedWashroomGender : null,
                washroom_capacity: document.getElementById('hasWashroom').checked ? 
                    parseInt(document.getElementById('washroomCapacity').value) || 1 : null,
                has_projector: document.getElementById('hasProjector').checked,
                has_whiteboard: document.getElementById('hasWhiteboard').checked,
                has_smart_board: document.getElementById('hasSmartBoard').checked,
                has_wifi: document.getElementById('hasWifi').checked,
                has_water_cooler: document.getElementById('hasWaterCooler').checked,
                has_cctv: document.getElementById('hasCCTV').checked,
                has_ac: document.getElementById('acCount').value > 0 ? 'yes' : 'no',
                status: 'active',
                occupancy_status: 'vacant',
            };
            
            const submitBtn = document.getElementById('submit-btn');
            const originalHTML = submitBtn.innerHTML;
            submitBtn.innerHTML = '<div class="loading-spinner"></div> Saving...';
            submitBtn.disabled = true;

            try {
                const response = await fetch(`${API_BASE_URL}/rooms`, {
                    method: 'POST',
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
                    showToast(result.message || 'Room added successfully!', 'success');
                    resetForm();
                } else {
                    if (result.errors) {
                        let errorMessage = '';
                        Object.values(result.errors).forEach(errors => {
                            errorMessage += errors.join(', ') + ' ';
                        });
                        showToast(errorMessage || 'Validation failed', 'error');
                    } else {
                        showToast(result.message || 'Failed to add room', 'error');
                    }
                }
            } catch (error) {
                console.error('Error adding room:', error);
                showToast('An error occurred. Please try again.', 'error');
            } finally {
                submitBtn.innerHTML = originalHTML;
                submitBtn.disabled = false;
            }
        }

        // Reset form
        function resetForm() {
            document.getElementById('buildingId').value = '';
            document.getElementById('blockSelect').innerHTML = '<option value="">Select a building block</option>';
            document.getElementById('floorSelect').innerHTML = '<option value="">Select a floor</option>';
            document.getElementById('roomNumber').value = '';
            document.getElementById('roomName').value = '';
            document.getElementById('roomDescription').value = '';
            document.getElementById('roomArea').value = '';
            document.getElementById('roomCapacity').value = '';
            
            document.getElementById('lightsCount').value = '0';
            document.getElementById('fansCount').value = '0';
            document.getElementById('acCount').value = '0';
            document.getElementById('socketsCount').value = '0';
            
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => { checkbox.checked = false; });
            
            document.getElementById('washroomDetails').style.display = 'none';
            document.getElementById('washroomCapacity').value = '';
            
            document.querySelectorAll('.room-type-option').forEach(option => {
                option.classList.remove('selected');
            });
            document.querySelector('.room-type-option:first-child').classList.add('selected');
            selectedRoomType = 'classroom';
            customRoomTypeValue = '';
            document.getElementById('roomType').value = 'classroom';
            document.getElementById('customRoomTypeContainer').style.display = 'none';
            document.getElementById('customRoomType').value = '';
            
            document.querySelectorAll('.area-option').forEach(option => {
                option.classList.remove('selected');
            });
            document.querySelector('.area-option:first-child').classList.add('selected');
            selectedAreaType = 'super';
            document.getElementById('areaType').value = 'super';
            
            document.querySelectorAll('.washroom-type-option').forEach(option => {
                option.classList.remove('selected');
            });
            document.querySelector('.washroom-type-option:first-child').classList.add('selected');
            selectedWashroomGender = 'male';
            document.getElementById('washroomGender').value = 'male';
            
            document.getElementById('roomNumber').focus();
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
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            
            toastMessage.textContent = message;
            
            toast.classList.remove('error', 'warning');
            
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