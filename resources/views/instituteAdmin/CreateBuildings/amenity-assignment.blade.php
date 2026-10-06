@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Assign Amenity Units</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
            --primary-color: #4361ee;
            --success-gradient: linear-gradient(135deg, #10b981, #059669);
            --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
            --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
            --info-gradient: linear-gradient(135deg, #0ea5e9, #0284c7);
            --border-color: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --shadow: 0 8px 25px rgba(0,0,0,0.05);
        }
        * { box-sizing: border-box; }

        .header {             background: var(--primary-gradient);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 15px 35px rgba(67,97,238,0.3);
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
        .header-content p {
            opacity: 0.9;
            font-size: 1rem;
            margin: 5px 0 0 0;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: var(--shadow);
            border: 2px solid var(--border-color);
            max-width: 1400px;
            margin: 0 auto;
        }

        .amenity-info {
            background: linear-gradient(135deg, #f0f4ff, #e8edff);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 2px solid rgba(67,97,238,0.15);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        .amenity-info .icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            background: var(--primary-gradient);
            color: white;
        }
        .amenity-info .details h3 {
            margin: 0 0 4px 0;
            font-weight: 700;
            color: var(--text-dark);
        }
        .amenity-info .details p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        .amenity-info .stats {
            display: flex;
            gap: 2rem;
            margin-left: auto;
        }
        .amenity-info .stats .stat-item {
            text-align: center;
        }
        .amenity-info .stats .stat-item .number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        .amenity-info .stats .stat-item .label {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* ============================================
           TAB STYLES
           ============================================ */
        .tab-container {
            margin-top: 1.5rem;
            margin-bottom: 2rem;
        }
        .tab-nav {
            display: flex;
            gap: 0;
            border-bottom: 3px solid var(--border-color);
            margin-bottom: 0;
            list-style: none;
            padding: 0;
            background: #f8fafc;
            border-radius: 12px 12px 0 0;
            overflow: hidden;
        }
        .tab-nav li {
            flex: 1;
            min-width: 120px;
        }
        .tab-nav .tab-btn {
            width: 100%;
            padding: 14px 20px;
            border: none;
            background: transparent;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .tab-nav .tab-btn i {
            font-size: 1.1rem; 
        }
        .tab-nav .tab-btn:hover {
            background: rgba(67,97,238,0.05);
            color: var(--text-dark);
        }
        .tab-nav .tab-btn.active {
            color: var(--primary-color);
            background: white;
        }
        .tab-nav .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--primary-gradient);
            border-radius: 3px 3px 0 0;
        }
        .tab-nav .tab-btn .badge {
            display: inline-block;
            background: #e2e8f0;
            color: var(--text-muted);
            padding: 0 8px;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: 700;
            line-height: 1.6;
        }
        .tab-nav .tab-btn.active .badge {
            background: rgba(67,97,238,0.15);
            color: var(--primary-color);
        }

        .tab-panel {
            display: none;
            padding: 1.5rem 0;
            background: white;
            border-radius: 0 0 12px 12px;
        }
        .tab-panel.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .tab-panel .form-group {
            margin-bottom: 1rem;
        }
        .tab-panel .form-group label {
            display: block;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
        }

        /* ============================================
           ASSIGNMENT FORM
           ============================================ */
        .assignment-form {
            margin-top: 0.5rem;
        }

        .units-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .units-table th {
            background: #f8fafc;
            padding: 12px 15px;
            text-align: left;
            font-weight: 700;
            color: var(--text-dark);
            border-bottom: 2px solid var(--border-color);
            font-size: 0.85rem;
        }
        .units-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }
        .units-table tr:hover {
            background: #f8fafc;
        }
        .units-table .unit-checkbox {
            width: 20px;
            height: 20px;
            accent-color: var(--primary-color);
            cursor: pointer;
        }
        .units-table .unit-checkbox:disabled {
            opacity: 0.5;
            cursor: not-allowed;
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
            box-shadow: 0 4px 15px rgba(67,97,238,0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67,97,238,0.4);
        }
        .btn-success {
            background: var(--success-gradient);
            color: white;
        }
        .btn-success:hover {
            transform: translateY(-2px);
        }
        .btn-danger {
            background: var(--danger-gradient);
            color: white;
        }
        .btn-danger:hover {
            transform: translateY(-2px);
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
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
            border-radius: 8px;
        }
        .btn-info {
            background: var(--info-gradient);
            color: white;
        }
        .btn-info:hover {
            transform: translateY(-2px);
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
            box-shadow: 0 0 0 4px rgba(67,97,238,0.1);
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
        .form-control[readonly] {
            background: #f1f5f9;
            cursor: default;
        }

        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--success-gradient);
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
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
        .toast.info {
            background: var(--info-gradient);
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

        .status-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 600;
        }
        .status-badge.available { background: #dcfce7; color: #166534; }
        .status-badge.assigned { background: #dbeafe; color: #1e40af; }
        .status-badge.maintenance { background: #fef3c7; color: #92400e; }
        .status-badge.retired { background: #fef2f2; color: #991b1b; }

        .selected-count {
            padding: 0.5rem 1rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            font-size: 0.9rem;
            color: var(--text-dark);
        }
        .selected-count strong {
            color: var(--primary-color);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--border-color);
            flex-wrap: wrap;
            align-items: center;
        }

        .assigned-units-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid var(--border-color);
        }

        /* ============================================
           MODAL STYLES
           ============================================ */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }
        .modal-overlay.show {
            display: flex;
        }

        .modal-box {
            background: white;
            border-radius: 20px;
            max-width: 700px;
            width: 95%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            animation: slideUp 0.3s ease;
        }
        .modal-header {
            padding: 1.5rem 2rem;
            border-bottom: 2px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            background: white;
            border-radius: 20px 20px 0 0;
            z-index: 10;
        }
        .modal-header h3 {
            margin: 0;
            font-weight: 700;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .modal-header h3 i {
            color: var(--primary-color);
        }
        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-muted);
            transition: all 0.3s;
            padding: 4px 8px;
            border-radius: 8px;
        }
        .modal-close:hover {
            background: #fef2f2;
            color: #dc2626;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-body .spec-item {
            display: flex;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .modal-body .spec-item:last-child {
            border-bottom: none;
        }
        .modal-body .spec-label {
            font-weight: 600;
            color: var(--text-dark);
            width: 40%;
            flex-shrink: 0;
        }
        .modal-body .spec-value {
            color: var(--text-muted);
            width: 60%;
        }
        .modal-body .no-specs {
            text-align: center;
            padding: 2rem;
            color: var(--text-muted);
        }
        .modal-body .no-specs i {
            font-size: 2rem;
            color: var(--border-color);
            margin-bottom: 0.5rem;
        }

        .modal-footer {
            padding: 1rem 2rem;
            border-top: 2px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            background: #f8fafc;
            border-radius: 0 0 20px 20px;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .hierarchy-path {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: var(--text-muted);
        }
        .hierarchy-path .separator {
            color: var(--border-color);
        }
        .hierarchy-path .current {
            color: var(--primary-color);
            font-weight: 600;
        }

        .building-info-box {
            background: linear-gradient(135deg, #f0f4ff, #e8edff);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            border: 1px solid rgba(67,97,238,0.15);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        .building-info-box i {
            color: var(--primary-color);
            font-size: 1.1rem;
        }
        .building-info-box .building-name {
            font-weight: 600;
            color: var(--text-dark);
        }
        .building-info-box .building-code {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .auto-building-display {
            background: #f8fafc;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-dark);
        }
        .auto-building-display i {
            color: var(--primary-color);
            font-size: 1.2rem;
        }
        .auto-building-display .label {
            color: var(--text-muted);
            font-size: 0.85rem;
        }
        .auto-building-display .value {
            font-weight: 600;
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .header { flex-direction: column; text-align: center; }
            .form-card { padding: 1.5rem; }
            .amenity-info { flex-direction: column; text-align: center; }
            .amenity-info .stats { margin-left: 0; }
            .tab-nav { flex-wrap: wrap; }
            .tab-nav li { flex: 1 1 50%; min-width: 100px; }
            .tab-nav .tab-btn { padding: 10px 12px; font-size: 0.8rem; }
            .units-table { font-size: 0.8rem; }
            .units-table th, .units-table td { padding: 8px 10px; }
            .modal-box { width: 98%; margin: 1rem; }
            .modal-body .spec-item { flex-direction: column; }
            .modal-body .spec-label { width: 100%; margin-bottom: 4px; }
            .modal-body .spec-value { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <h1><i class="fas fa-arrow-right"></i> Assign Amenity Units</h1>
                <p>Select units to assign to a building, block, floor, or room</p>
            </div>
            <div>
                <a href="{{ route('institute.admin.amenities.management') }}" class="btn btn-secondary" style="background:rgba(255,255,255,0.2);color:white;border:2px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-left"></i> Back to Amenities
                </a>
                <a href="{{ route('institute.admin.amenities.assigned.refresh') }}" class="btn btn-secondary" style="background:rgba(255,255,255,0.2);color:white;border:2px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-arrow-right"></i> View Assigned Units
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="form-card">
            <!-- Amenity Info -->
            <div class="amenity-info">
                <div class="icon">
                    <i class="fas {{ $amenity->icon ?? 'fa-cube' }}"></i>
                </div>
                <div class="details">
                    <h3>{{ $amenity->name }}</h3>
                    <p style="display:none;">Category: {{ ucfirst($amenity->category ?? 'General') }}</p>
                    <p style="font-size: 0.8rem; margin-top: 4px;">
                        <span class="status-badge available">Available: {{ $availableUnits->count() }}</span>
                        <span class="status-badge assigned" style="margin-left: 8px;">Assigned: {{ $assignedUnits->count() }}</span>
                    </p>
                </div>
                <div class="stats">
                    <div class="stat-item">
                        <div class="number">{{ $availableUnits->count() }}</div>
                        <div class="label">Available Units</div>
                    </div>
                    <div class="stat-item">
                        <div class="number">{{ $assignedUnits->count() }}</div>
                        <div class="label">Assigned Units</div>
                    </div>
                    <div class="stat-item">
                        <div class="number">{{ $amenity->units->count() }}</div>
                        <div class="label">Total Units</div>
                    </div>
                </div>
            </div>

            <!-- Assignment Form -->
            <form id="assignmentForm" class="assignment-form">
                @csrf
                <input type="hidden" name="amenity_id" value="{{ $amenity->amenity_id }}">
                <input type="hidden" name="building_id" id="hiddenBuildingId" value="">
                
                <!-- Auto-selected Building Display -->
                <div class="auto-building-display" style="margin-bottom: 1.5rem;">
                    <i class="fas fa-building"></i>
                    <span class="label">Building:</span>
                    <span class="value" id="autoBuildingDisplay">Loading building information...</span>
                </div>

                <!-- ============================================
                     TAB NAVIGATION
                     ============================================ -->
                <div class="tab-container">
                    <ul class="tab-nav" role="tablist">
                        <li role="tab">
                            <button type="button" class="tab-btn active" data-tab="tab-building" onclick="switchTab('tab-building')">
                                <i class="fas fa-building"></i> Building
                            </button>
                        </li>
                        <li role="tab">
                            <button type="button" class="tab-btn" data-tab="tab-block" onclick="switchTab('tab-block')">
                                <i class="fas fa-layer-group"></i> Block
                                <span class="badge">{{ $blocks->count() }}</span>
                            </button>
                        </li>
                        <li role="tab">
                            <button type="button" class="tab-btn" data-tab="tab-floor" onclick="switchTab('tab-floor')">
                                <i class="fas fa-arrows-alt-v"></i> Floor
                                <span class="badge">{{ $floors->count() }}</span>
                            </button>
                        </li>
                        <li role="tab">
                            <button type="button" class="tab-btn" data-tab="tab-room" onclick="switchTab('tab-room')">
                                <i class="fas fa-door-open"></i> Room
                                <span class="badge">{{ $rooms->count() }}</span>
                            </button>
                        </li>
                    </ul>

                    <!-- ============================================
                         TAB PANEL: BUILDING
                         ============================================ -->
                    <div id="tab-building" class="tab-panel active">
                        <div class="building-info-box">
                            <i class="fas fa-info-circle"></i>
                            <span>Units will be assigned to <span class="building-name">{{ $buildings->first()->name ?? 'Building' }}</span> level</span>
                        </div>
                        <div class="form-group">
                            <label>Building (Auto-selected) <span style="color: #dc3545;">*</span></label>
                            <div class="auto-building-display" style="background: #f0f4ff; border-color: rgba(67,97,238,0.2);">
                                <i class="fas fa-check-circle" style="color: #10b981;"></i>
                                <span class="value" id="buildingDisplayValue">{{ $buildings->first()->name ?? 'No building found' }}</span>
                                <span class="label" style="margin-left: auto; font-size: 0.75rem; color: #10b981;">Auto-selected</span>
                            </div>
                            <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 4px;">
                                <i class="fas fa-info-circle"></i> Building is automatically selected. Units will be assigned to this building.
                            </small>
                        </div>
                    </div>

                    <!-- ============================================
                         TAB PANEL: BLOCK
                         ============================================ -->
                    <div id="tab-block" class="tab-panel">
                        <div class="building-info-box">
                            <i class="fas fa-info-circle"></i>
                            <span>Units will be assigned to a <span class="building-name">Block</span> within the auto-selected building</span>
                        </div>
                        <div class="form-group">
                            <label for="blockSelect">Select Block <span style="color: #dc3545;">*</span></label>
                            <select id="blockSelect" name="block_id" class="form-control" onchange="updateHierarchyPath()">
                                <option value="">Select Block</option>
                                @foreach($blocks as $block)
                                    <option value="{{ $block->id }}" data-building="{{ $block->building_id }}" data-building-name="{{ $block->building->name ?? '' }}">
                                        {{ $block->name }} @if($block->code)({{ $block->code }})@endif
                                        @if(isset($block->building)) - {{ $block->building->name }}@endif
                                    </option>
                                @endforeach
                            </select>
                            <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 4px;">
                                <i class="fas fa-info-circle"></i> Units will be assigned to the selected block
                            </small>
                        </div>
                        <div id="blockBuildingDisplay" style="margin-top: 0.5rem; font-size: 0.9rem; color: var(--text-muted); display: none;">
                            <i class="fas fa-building"></i> Building: <span id="blockBuildingName"></span>
                        </div>
                    </div>

                    <!-- ============================================
                         TAB PANEL: FLOOR
                         ============================================ -->
                    <div id="tab-floor" class="tab-panel">
                        <div class="building-info-box">
                            <i class="fas fa-info-circle"></i>
                            <span>Units will be assigned to a <span class="building-name">Floor</span> within a block</span>
                        </div>
                        <div class="form-group">
                            <label for="blockForFloor">Select Block <span style="color: #dc3545;">*</span></label>
                            <select id="blockForFloor" name="block_id_floor" class="form-control" onchange="filterFloorOptionsByBlock()">
                                <option value="">Select Block</option>
                                @foreach($blocks as $block)
                                    <option value="{{ $block->id }}" data-building="{{ $block->building_id }}" data-building-name="{{ $block->building->name ?? '' }}">
                                        {{ $block->name }} @if($block->code)({{ $block->code }})@endif
                                        @if(isset($block->building)) - {{ $block->building->name }}@endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="floorSelect">Select Floor <span style="color: #dc3545;">*</span></label>
                            <select id="floorSelect" name="floor_id" class="form-control" onchange="updateHierarchyPath()">
                                <option value="">Select Floor</option>
                                @foreach($floors as $floor)
                                    <option value="{{ $floor->id }}" data-block="{{ $floor->block_id }}" data-block-name="{{ $floor->block->name ?? '' }}">
                                        Floor {{ $floor->floor_number }} @if($floor->floor_name)({{ $floor->floor_name }})@endif
                                        @if(isset($floor->block)) - {{ $floor->block->name }}@endif
                                    </option>
                                @endforeach
                            </select>
                            <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 4px;">
                                <i class="fas fa-info-circle"></i> Units will be assigned to the selected floor
                            </small>
                        </div>
                        <div id="floorBuildingDisplay" style="margin-top: 0.5rem; font-size: 0.9rem; color: var(--text-muted); display: none;">
                            <i class="fas fa-building"></i> Building: <span id="floorBuildingName"></span>
                        </div>
                    </div>

                    <!-- ============================================
                         TAB PANEL: ROOM
                         ============================================ -->
                    <div id="tab-room" class="tab-panel">
                        <div class="building-info-box">
                            <i class="fas fa-info-circle"></i>
                            <span>Units will be assigned to a <span class="building-name">Room</span> within a floor</span>
                        </div>
                        <div class="form-group">
                            <label for="blockForRoom">Select Block <span style="color: #dc3545;">*</span></label>
                            <select id="blockForRoom" name="block_id_room" class="form-control" onchange="filterRoomOptionsByBlock()">
                                <option value="">Select Block</option>
                                @foreach($blocks as $block)
                                    <option value="{{ $block->id }}" data-building="{{ $block->building_id }}" data-building-name="{{ $block->building->name ?? '' }}">
                                        {{ $block->name }} @if($block->code)({{ $block->code }})@endif
                                        @if(isset($block->building)) - {{ $block->building->name }}@endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="floorForRoom">Select Floor <span style="color: #dc3545;">*</span></label>
                            <select id="floorForRoom" name="floor_id_room" class="form-control" onchange="filterRoomOptionsByFloor()">
                                <option value="">Select Floor</option>
                                @foreach($floors as $floor)
                                    <option value="{{ $floor->id }}" data-block="{{ $floor->block_id }}" data-block-name="{{ $floor->block->name ?? '' }}">
                                        Floor {{ $floor->floor_number }} @if($floor->floor_name)({{ $floor->floor_name }})@endif
                                        @if(isset($floor->block)) - {{ $floor->block->name }}@endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="roomSelect">Select Room <span style="color: #dc3545;">*</span></label>
                            <select id="roomSelect" name="room_id" class="form-control" onchange="updateHierarchyPath()">
                                <option value="">Select Room</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" data-floor="{{ $room->floor_id }}" data-floor-name="{{ $room->floor->floor_number ?? '' }}">
                                        Room {{ $room->room_number }} @if($room->room_name)({{ $room->room_name }})@endif
                                        @if(isset($room->floor)) - Floor {{ $room->floor->floor_number }}@endif
                                    </option>
                                @endforeach
                            </select>
                            <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 4px;">
                                <i class="fas fa-info-circle"></i> Units will be assigned to the selected room
                            </small>
                        </div>
                        <div id="roomBuildingDisplay" style="margin-top: 0.5rem; font-size: 0.9rem; color: var(--text-muted); display: none;">
                            <i class="fas fa-building"></i> Building: <span id="roomBuildingName"></span>
                        </div>
                    </div>

                    <!-- ============================================
                         HIERARCHY PATH DISPLAY
                         ============================================ -->
                    <div id="hierarchyPath" class="hierarchy-path">
                        <span>📍</span>
                        <span class="current" id="pathDisplay">No location selected</span>
                    </div>
                </div>

                <!-- Notes -->
                <div class="form-group">
                    <label for="notes">Notes (Optional)</label>
                    <textarea id="notes" name="notes" class="form-control" rows="2" placeholder="Any additional notes about this assignment..."></textarea>
                </div>

                <!-- Units Table -->
                <h4 style="margin-top: 1.5rem;">
                    <i class="fas fa-list"></i> Select Units to Assign
                    <span class="selected-count" id="selectedCount">Selected: <strong id="selectedCountNumber">0</strong> units</span>
                </h4>

                @if($availableUnits->count() > 0)
                    <div class="table-responsive">
                        <table class="units-table">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">
                                        <input type="checkbox" id="selectAll" class="unit-checkbox" onchange="toggleAllUnits()">
                                    </th>
                                    <th>Unit Number</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availableUnits as $unit)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="unit_ids[]" value="{{ $unit->unit_id }}" class="unit-checkbox unit-select" onchange="updateSelectedCount()">
                                        </td>
                                        <td><strong>{{ $unit->unit_number }}</strong></td>
                                        <td>{{ $unit->name }}</td>
                                        <td><span class="status-badge available">Available</span></td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" onclick="viewSpecs('{{ $unit->unit_id }}')">
                                                <i class="fas fa-eye"></i> View Specs
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="text-align: center; padding: 2rem; color: var(--text-muted); background: #f8fafc; border-radius: 12px;">
                        <i class="fas fa-check-circle" style="font-size: 2rem; color: #10b981;"></i>
                        <p style="margin-top: 0.5rem;">All units are already assigned. No available units to assign.</p>
                    </div>
                @endif

                <!-- Assigned Units Section -->
                @if($assignedUnits->count() > 0)
                    <div class="assigned-units-section">
                        <h4><i class="fas fa-check-circle" style="color: #10b981;"></i> Assigned Units</h4>
                        <div class="table-responsive">
                            <table class="units-table">
                                <thead>
                                    <tr>
                                        <th>Unit Number</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Assigned To</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assignedUnits as $unit)
                                        <tr>
                                            <td><strong>{{ $unit->unit_number }}</strong></td>
                                            <td>{{ $unit->name }}</td>
                                            <td><span class="status-badge assigned">Assigned</span></td>
                                            <td>
                                                @if($unit->assignment)
                                                    {{ ucfirst($unit->assignment->assigned_to_type) }}: {{ $unit->assignment->getAssignedToDisplayAttribute() }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm" onclick="viewSpecs('{{ $unit->unit_id }}')">
                                                    <i class="fas fa-eye"></i> Specs
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="unassignUnit('{{ $unit->unit_id }}')">
                                                    <i class="fas fa-undo"></i> Unassign
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-success" id="assignBtn" {{ $availableUnits->count() > 0 ? '' : 'disabled' }}>
                        <i class="fas fa-check"></i> Assign Selected Units
                    </button>
                    <a href="{{ route('institute.admin.amenities.management') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <span style="font-size: 0.85rem; color: var(--text-muted); margin-left: auto;">
                        <i class="fas fa-info-circle"></i> Selected units will be assigned to the selected location
                    </span>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================
         SPECIFICATIONS MODAL
         ============================================ -->
    <div id="specsModal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h3><i class="fas fa-cog"></i> <span id="modalUnitTitle">Unit Specifications</span></h3>
                <button type="button" class="modal-close" onclick="closeSpecsModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <div style="text-align: center; padding: 2rem;">
                    <div class="loading-spinner" style="width: 40px; height: 40px; margin: 0 auto; border-color: var(--primary-color); border-top-color: transparent;"></div>
                    <p style="margin-top: 1rem; color: var(--text-muted);">Loading specifications...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeSpecsModal()">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toastMessage"></span>
    </div>

    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const API_BASE_URL = '{{ url('/') }}';

        // Store building data from backend
        const buildingData = {
            id: '{{ $buildings->first()->id ?? '' }}',
            name: '{{ $buildings->first()->name ?? 'No building found' }}',
            code: '{{ $buildings->first()->code ?? '' }}'
        };

        // ============================================
        // TOAST NOTIFICATION
        // ============================================
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const messageEl = document.getElementById('toastMessage');
            
            messageEl.textContent = message;
            toast.className = 'toast' + (type === 'error' ? ' error' : type === 'info' ? ' info' : '');
            toast.querySelector('i').className = type === 'error' ? 'fas fa-exclamation-circle' : 
                                                   type === 'info' ? 'fas fa-info-circle' : 'fas fa-check-circle';
            toast.classList.add('show');
            
            clearTimeout(window.toastTimeout);
            window.toastTimeout = setTimeout(() => toast.classList.remove('show'), 3000);
        }

        // ============================================
        // TAB SWITCHING
        // ============================================
        function switchTab(tabId) {
            // Hide all panels
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.remove('active');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected panel
            document.getElementById(tabId).classList.add('active');
            
            // Activate button
            document.querySelector(`.tab-btn[data-tab="${tabId}"]`).classList.add('active');
            
            // Update hierarchy path
            updateHierarchyPath();
        }

        // ============================================
        // HIERARCHY PATH
        // ============================================
        function updateHierarchyPath() {
            const activeTab = document.querySelector('.tab-panel.active');
            const pathDisplay = document.getElementById('pathDisplay');
            
            if (!activeTab) {
                pathDisplay.textContent = 'No location selected';
                return;
            }
            
            const tabId = activeTab.id;
            let path = '';
            
            switch(tabId) {
                case 'tab-building': {
                    const buildingName = buildingData.name || 'Building';
                    path = `🏢 Building: ${buildingName} (Auto-selected)`;
                    break;
                }
                case 'tab-block': {
                    const blockSelect = document.getElementById('blockSelect');
                    const block = blockSelect.options[blockSelect.selectedIndex];
                    if (block && block.value) {
                        const buildingName = block.getAttribute('data-building-name') || '';
                        path = `📦 Block: ${block.text}`;
                        if (buildingName) {
                            path += ` (${buildingName})`;
                        }
                        document.getElementById('blockBuildingDisplay').style.display = 'block';
                        document.getElementById('blockBuildingName').textContent = buildingName || 'N/A';
                    } else {
                        path = 'Please select a block';
                        document.getElementById('blockBuildingDisplay').style.display = 'none';
                    }
                    break;
                }
                case 'tab-floor': {
                    const blockSelect = document.getElementById('blockForFloor');
                    const floorSelect = document.getElementById('floorSelect');
                    const block = blockSelect.options[blockSelect.selectedIndex];
                    const floor = floorSelect.options[floorSelect.selectedIndex];
                    if (block && block.value && floor && floor.value) {
                        const buildingName = block.getAttribute('data-building-name') || '';
                        path = `📐 Floor: ${floor.text}`;
                        if (buildingName) {
                            path += ` (${buildingName})`;
                        }
                        document.getElementById('floorBuildingDisplay').style.display = 'block';
                        document.getElementById('floorBuildingName').textContent = buildingName || 'N/A';
                    } else {
                        path = 'Please select a block and floor';
                        document.getElementById('floorBuildingDisplay').style.display = 'none';
                    }
                    break;
                }
                case 'tab-room': {
                    const blockSelect = document.getElementById('blockForRoom');
                    const floorSelect = document.getElementById('floorForRoom');
                    const roomSelect = document.getElementById('roomSelect');
                    const block = blockSelect.options[blockSelect.selectedIndex];
                    const floor = floorSelect.options[floorSelect.selectedIndex];
                    const room = roomSelect.options[roomSelect.selectedIndex];
                    if (block && block.value && floor && floor.value && room && room.value) {
                        const buildingName = block.getAttribute('data-building-name') || '';
                        path = `🚪 Room: ${room.text}`;
                        if (buildingName) {
                            path += ` (${buildingName})`;
                        }
                        document.getElementById('roomBuildingDisplay').style.display = 'block';
                        document.getElementById('roomBuildingName').textContent = buildingName || 'N/A';
                    } else {
                        path = 'Please select a block, floor, and room';
                        document.getElementById('roomBuildingDisplay').style.display = 'none';
                    }
                    break;
                }
                default:
                    path = 'No location selected';
            }
            
            pathDisplay.textContent = path;
        }

        // ============================================
        // FILTER FUNCTIONS FOR DROPDOWNS
        // ============================================
        function filterFloorOptionsByBlock() {
            const blockId = document.getElementById('blockForFloor').value;
            const floorSelect = document.getElementById('floorSelect');
            
            const options = floorSelect.querySelectorAll('option');
            options.forEach(opt => {
                if (opt.value === '') {
                    opt.style.display = 'block';
                    return;
                }
                const floorBlock = opt.getAttribute('data-block');
                opt.style.display = (blockId === '' || floorBlock === blockId) ? 'block' : 'none';
            });
            
            floorSelect.value = '';
            updateHierarchyPath();
        }

        function filterRoomOptionsByBlock() {
            const blockId = document.getElementById('blockForRoom').value;
            const floorSelect = document.getElementById('floorForRoom');
            
            const floorOptions = floorSelect.querySelectorAll('option');
            floorOptions.forEach(opt => {
                if (opt.value === '') {
                    opt.style.display = 'block';
                    return;
                }
                const floorBlock = opt.getAttribute('data-block');
                opt.style.display = (blockId === '' || floorBlock === blockId) ? 'block' : 'none';
            });
            
            floorSelect.value = '';
            document.getElementById('roomSelect').value = '';
            updateHierarchyPath();
        }

        function filterRoomOptionsByFloor() {
            const floorId = document.getElementById('floorForRoom').value;
            const roomSelect = document.getElementById('roomSelect');
            
            const options = roomSelect.querySelectorAll('option');
            options.forEach(opt => {
                if (opt.value === '') {
                    opt.style.display = 'block';
                    return;
                }
                const roomFloor = opt.getAttribute('data-floor');
                opt.style.display = (floorId === '' || roomFloor === floorId) ? 'block' : 'none';
            });
            
            roomSelect.value = '';
            updateHierarchyPath();
        }

        // ============================================
        // SELECTION FUNCTIONS
        // ============================================
        function updateSelectedCount() {
            const selected = document.querySelectorAll('.unit-select:checked').length;
            document.getElementById('selectedCountNumber').textContent = selected;
            
            const allCheckboxes = document.querySelectorAll('.unit-select');
            const checkedCheckboxes = document.querySelectorAll('.unit-select:checked');
            const selectAll = document.getElementById('selectAll');
            
            if (allCheckboxes.length > 0 && checkedCheckboxes.length === allCheckboxes.length) {
                selectAll.checked = true;
            } else {
                selectAll.checked = false;
            }
        }

        function toggleAllUnits() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.unit-select');
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            updateSelectedCount();
        }

        // ============================================
        // SPECIFICATIONS MODAL
        // ============================================
        function viewSpecs(unitId) {
            const modal = document.getElementById('specsModal');
            const modalBody = document.getElementById('modalBody');
            const modalTitle = document.getElementById('modalUnitTitle');
            
            modalBody.innerHTML = `
                <div style="text-align: center; padding: 2rem;">
                    <div class="loading-spinner" style="width: 40px; height: 40px; margin: 0 auto; border-color: var(--primary-color); border-top-color: transparent;"></div>
                    <p style="margin-top: 1rem; color: var(--text-muted);">Loading specifications...</p>
                </div>
            `;
            
            modal.classList.add('show');
            
            fetch(API_BASE_URL + '/institute/admin/campus/amenity-unit-specifications/' + unitId, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    const unit = data.data;
                    modalTitle.textContent = unit.name + ' - Specifications';
                    
                    let specsHtml = '';
                    const specs = unit.specifications || {};
                    
                    if (typeof specs === 'object' && Object.keys(specs).length > 0) {
                        for (const [key, value] of Object.entries(specs)) {
                            if (value !== null && value !== '' && value !== 'null') {
                                const displayKey = key.replace(/_/g, ' ').replace(/\b\w/g, function(l) { return l.toUpperCase(); });
                                specsHtml += `
                                    <div class="spec-item">
                                        <div class="spec-label">${escapeHtml(displayKey)}</div>
                                        <div class="spec-value">${escapeHtml(String(value))}</div>
                                    </div>
                                `;
                            }
                        }
                    }
                    
                    if (specsHtml) {
                        modalBody.innerHTML = `
                            <div style="margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-color);">
                                <span class="status-badge ${unit.status}">${unit.status || 'N/A'}</span>
                                <span style="margin-left: 1rem; color: var(--text-muted); font-size: 0.85rem;">
                                    <i class="fas fa-hashtag"></i> ${unit.unit_number || 'N/A'}
                                </span>
                            </div>
                            ${specsHtml}
                        `;
                    } else {
                        modalBody.innerHTML = `
                            <div class="no-specs">
                                <i class="fas fa-info-circle"></i>
                                <p>No specifications available for this unit.</p>
                            </div>
                        `;
                    }
                } else {
                    modalBody.innerHTML = `
                        <div class="no-specs">
                            <i class="fas fa-exclamation-triangle" style="color: #f59e0b;"></i>
                            <p>${data.message || 'Failed to load specifications.'}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error fetching unit specs:', error);
                modalBody.innerHTML = `
                    <div class="no-specs">
                        <i class="fas fa-exclamation-circle" style="color: #ef4444;"></i>
                        <p>Error loading specifications. Please try again.</p>
                    </div>
                `;
                showToast('Error loading specifications', 'error');
            });
        }

        function closeSpecsModal() {
            document.getElementById('specsModal').classList.remove('show');
        }

        document.getElementById('specsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeSpecsModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSpecsModal();
            }
        });

        // ============================================
        // ESCAPE HTML HELPER
        // ============================================
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.appendChild(document.createTextNode(text));
            return div.innerHTML;
        }

        // ============================================
        // FORM SUBMISSION
        // ============================================
        document.getElementById('assignmentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const selectedUnits = document.querySelectorAll('.unit-select:checked');
            if (selectedUnits.length === 0) {
                showToast('Please select at least one unit to assign.', 'error');
                return;
            }

            // Determine which tab is active and get the selected location
            const activeTab = document.querySelector('.tab-panel.active');
            if (!activeTab) {
                showToast('Please select a location to assign to.', 'error');
                return;
            }

            const tabId = activeTab.id;
            let assignedToType = '';
            let assignedToId = '';
            let buildingId = buildingData.id || '';
            let blockId = '';
            let floorId = '';
            let roomId = '';

            switch(tabId) {
                case 'tab-building': {
                    assignedToType = 'building';
                    assignedToId = buildingId;
                    if (!assignedToId) {
                        showToast('No building selected. Please contact administrator.', 'error');
                        return;
                    }
                    break;
                }
                case 'tab-block': {
                    assignedToType = 'block';
                    assignedToId = document.getElementById('blockSelect').value;
                    blockId = assignedToId;
                    if (!assignedToId) {
                        showToast('Please select a block.', 'error');
                        return;
                    }
                    break;
                }
                case 'tab-floor': {
                    assignedToType = 'floor';
                    assignedToId = document.getElementById('floorSelect').value;
                    blockId = document.getElementById('blockForFloor').value;
                    floorId = assignedToId;
                    if (!assignedToId || !blockId) {
                        showToast('Please select a block and floor.', 'error');
                        return;
                    }
                    break;
                }
                case 'tab-room': {
                    assignedToType = 'room';
                    assignedToId = document.getElementById('roomSelect').value;
                    blockId = document.getElementById('blockForRoom').value;
                    floorId = document.getElementById('floorForRoom').value;
                    roomId = assignedToId;
                    if (!assignedToId || !blockId || !floorId) {
                        showToast('Please select a block, floor, and room.', 'error');
                        return;
                    }
                    break;
                }
                default: {
                    showToast('Please select a valid location.', 'error');
                    return;
                }
            }

            const unitIds = Array.from(selectedUnits).map(cb => cb.value);

            const formData = {
                unit_ids: unitIds,
                assigned_to_type: assignedToType,
                assigned_to_id: assignedToId,
                amenity_id: '{{ $amenity->amenity_id }}',
                building_id: buildingId,
                block_id: blockId,
                floor_id: floorId,
                room_id: roomId,
                notes: document.getElementById('notes').value,
                _token: CSRF_TOKEN
            };

            const btn = document.getElementById('assignBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="loading-spinner"></span> Assigning...';
            btn.disabled = true;

            fetch(API_BASE_URL + '/institute/admin/amenities/assign', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(data.message || 'Failed to assign units.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while assigning units.', 'error');
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });

        // ============================================
        // UNASSIGN UNIT
        // ============================================
        function unassignUnit(unitId) {
            if (!confirm('Are you sure you want to unassign this unit? It will become available again.')) {
                return;
            }

            fetch(API_BASE_URL + '/institute/admin/amenities/units/' + unitId + '/unassign', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast(data.message || 'Failed to unassign unit.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while unassigning the unit.', 'error');
            });
        }

        // ============================================
        // INITIALIZATION
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            // Set the building display
            const buildingDisplay = document.getElementById('autoBuildingDisplay');
            const buildingDisplayValue = document.getElementById('buildingDisplayValue');
            const hiddenBuildingId = document.getElementById('hiddenBuildingId');
            
            if (buildingData.id) {
                const displayText = buildingData.name + (buildingData.code ? ' (' + buildingData.code + ')' : '');
                if (buildingDisplay) buildingDisplay.textContent = displayText;
                if (buildingDisplayValue) buildingDisplayValue.textContent = displayText;
                if (hiddenBuildingId) hiddenBuildingId.value = buildingData.id;
            } else {
                if (buildingDisplay) buildingDisplay.textContent = 'No building found';
                if (buildingDisplayValue) buildingDisplayValue.textContent = 'No building found';
                showToast('No building found for this institute.', 'error');
            }
            
            updateSelectedCount();
            updateHierarchyPath();
            
            // Initialize all filter dropdowns
            filterFloorOptionsByBlock();
            filterRoomOptionsByBlock();
            filterRoomOptionsByFloor();
        });
    </script>
</body>
</html>
@endsection