@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Room - {{ $room->room_number ?? 'Room' }}</title>
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

        * { box-sizing: border-box; }

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
        .form-card h2 i { color: var(--primary-color); }

        .form-card h3 {
            color: var(--text-dark);
            margin: 1.5rem 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--border-color);
            font-size: 1.1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-card h3 i { color: var(--primary-color); }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
        }

        .form-group { margin-bottom: 1.25rem; }
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
        textarea.form-control { resize: vertical; min-height: 80px; }

        select.form-control {
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%234361ee' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            padding-right: 40px;
        }

        /* Spec groups */
        .specs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }
        .spec-group {
            background: var(--bg-light);
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
        .spec-group h4 i { color: var(--primary-color); font-size: 1rem; }

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
        .area-option:hover { border-color: var(--primary-color); }
        .area-option.selected {
            background: var(--primary-gradient);
            color: white;
            border-color: var(--primary-color);
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

        /* Buttons */
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
        .btn-secondary:hover { background: #e2e8f0; transform: translateY(-2px); }
        .btn-success {
            background: var(--success-gradient);
            color: white;
        }
        .btn-success:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(16,185,129,0.4); }
        .btn-danger {
            background: var(--danger-gradient);
            color: white;
        }
        .btn-danger:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(239,68,68,0.4); }
        .btn-outline-primary {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-outline-primary:hover {
            background: var(--primary-gradient);
            color: white;
            transform: translateY(-2px);
        }
        .btn-outline-success {
            background: white;
            color: #10b981;
            border: 2px solid #10b981;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-outline-success:hover {
            background: var(--success-gradient);
            color: white;
            transform: translateY(-2px);
        }
        .btn-outline-danger {
            background: white;
            color: #dc3545;
            border: 2px solid #dc3545;
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-outline-danger:hover {
            background: var(--danger-gradient);
            color: white;
            transform: translateY(-2px);
        }
        .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.8rem; }
        .btn:disabled { opacity: 0.7; cursor: not-allowed; }

        .add-btn-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1rem;
        }

        /* Dynamic cards */
        .dynamic-card {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 14px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            position: relative;
        }
        .dynamic-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 15px rgba(67,97,238,0.1);
        }
        .dynamic-card .card-badge-sm {
            position: absolute;
            top: -12px;
            left: 16px;
            background: var(--success-gradient);
            color: white;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        .dynamic-card .card-row {
            display: grid;
            gap: 0.75rem;
            margin-top: 0.5rem;
        }
        .dynamic-card .form-group { margin-bottom: 0; }
        .dynamic-card .form-group label { font-size: 0.85rem; margin-bottom: 0.3rem; }
        .dynamic-card .form-group input,
        .dynamic-card .form-group select { padding: 0.6rem 0.9rem; font-size: 0.9rem; }

        .custom-amenity-card {
            background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
            border: 2px dashed #6ee7b7;
            border-radius: 14px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            position: relative;
        }
        .custom-amenity-card .card-badge-sm {
            background: var(--success-gradient);
        }

        /* Facility checkbox cards — same as floor/block edit */
        .facility-checkbox-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 8px;
        }
        .facility-checkbox-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            user-select: none;
        }
        .facility-checkbox-card:hover {
            border-color: var(--primary-color);
            background: #f0f4ff;
        }
        .facility-checkbox-card.checked {
            border-color: var(--primary-color);
            background: #f0f4ff;
        }
        .facility-checkbox-card input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
            cursor: pointer;
            flex-shrink: 0;
        }
        .facility-checkbox-card .facility-icon-box {
            width: 32px;
            height: 32px;
            background: #d1fae5;
            color: #065f46;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .facility-checkbox-card .facility-text { flex: 1; min-width: 0; }
        .facility-checkbox-card .facility-name {
            display: block;
            font-weight: 600;
            color: #1e293b;
            font-size: 0.9rem;
        }
        .facility-checkbox-card .facility-type {
            display: block;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #0d9488;
            letter-spacing: 0.3px;
        }

        /* Amenity toggle chips */
        .amenity-toggle {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            background: #f1f5f9;
            border: 2px solid #e2e8f0;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
            margin: 0 6px 6px 0;
        }
        .amenity-toggle:hover {
            border-color: var(--primary-color);
            transform: translateY(-1px);
        }
        .amenity-toggle.on {
            background: #dcfce7;
            border-color: #86efac;
            color: #166534;
        }
        .amenity-toggle.on i { color: #10b981; }
        .amenity-toggle .qty-input {
            width: 50px;
            padding: 2px 6px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 0.75rem;
            text-align: center;
            margin-left: 6px;
        }

        .empty-text {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-style: italic;
            padding: 8px 0;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--border-color);
            flex-wrap: wrap;
            justify-content: flex-end;
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
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.error { background: var(--danger-gradient); }

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
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .header { flex-direction: column; text-align: center; }
            .form-card { padding: 1.5rem; }
            .form-actions { flex-direction: column; }
            .specs-grid { grid-template-columns: 1fr; }
            .count-inputs { grid-template-columns: 1fr; }
            .area-selector { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <h1><i class="fas fa-edit"></i> Edit Room</h1>
                <p>Editing: {{ $room->room_number }}{{ $room->room_name ? ' - ' . $room->room_name : '' }}</p>
            </div>
            <a href="{{ route('rooms.list') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Rooms
            </a>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <h2><i class="fas fa-door-open"></i> Room Details</h2>

            <form id="editRoomForm">
                <input type="hidden" id="roomId" value="{{ $room->id }}">

                <!-- Basic Info -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Room Number <span style="color: #dc3545;">*</span></label>
                        <input type="text" id="roomNumber" class="form-control" required
                               value="{{ $room->room_number ?? '' }}" maxlength="20">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Room Name</label>
                        <input type="text" id="roomName" class="form-control"
                               value="{{ $room->room_name ?? '' }}" maxlength="100">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Room Type</label>
                        <select id="roomType" class="form-control" onchange="toggleCustomRoomType()">
                            <option value="classroom" {{ ($room->room_type ?? '') === 'classroom' ? 'selected' : '' }}>Classroom</option>
                            <option value="lab" {{ ($room->room_type ?? '') === 'lab' ? 'selected' : '' }}>Laboratory</option>
                            <option value="office" {{ ($room->room_type ?? '') === 'office' ? 'selected' : '' }}>Office</option>
                            <option value="conference" {{ ($room->room_type ?? '') === 'conference' ? 'selected' : '' }}>Conference</option>
                            <option value="library" {{ ($room->room_type ?? '') === 'library' ? 'selected' : '' }}>Library</option>
                            <option value="store_room" {{ ($room->room_type ?? '') === 'store_room' ? 'selected' : '' }}>Store Room</option>
                            <option value="other" {{ ($room->room_type ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="form-group" id="customTypeContainer" style="{{ ($room->room_type ?? '') === 'other' ? '' : 'display:none;' }}">
                        <label class="form-label">Custom Room Type</label>
                        <input type="text" id="customRoomType" class="form-control"
                               value="{{ $room->custom_room_type ?? '' }}" maxlength="50">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea id="roomDescription" class="form-control" rows="2">{{ $room->description ?? '' }}</textarea>
                </div>

                <!-- Location (read-only) -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Building</label>
                        <input type="text" class="form-control" readonly
                               value="{{ $room->building->name ?? 'N/A' }}" style="background:#f1f5f9;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Block</label>
                        <input type="text" class="form-control" readonly
                               value="{{ $room->block->name ?? 'N/A' }}" style="background:#f1f5f9;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Floor</label>
                        <input type="text" class="form-control" readonly
                               value="{{ $room->floor->floor_number ?? 'N/A' }}" style="background:#f1f5f9;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select id="roomStatus" class="form-control">
                            <option value="active" {{ ($room->status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ ($room->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="under_maintenance" {{ ($room->status ?? '') === 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                            <option value="renovation" {{ ($room->status ?? '') === 'renovation' ? 'selected' : '' }}>Renovation</option>
                        </select>
                    </div>
                </div>

                <!-- Specs -->
                <h3><i class="fas fa-cogs"></i> Room Specifications</h3>
                <div class="specs-grid">
                    <!-- Area -->
                    <div class="spec-group">
                        <h4><i class="fas fa-ruler-combined"></i> Room Area</h4>
                        <div class="area-selector">
                            <div class="area-option {{ ($room->area_type ?? 'super') === 'super' ? 'selected' : '' }}"
                                 id="areaSuper" onclick="selectAreaType('super')">Super Area</div>
                            <div class="area-option {{ ($room->area_type ?? '') === 'carpet' ? 'selected' : '' }}"
                                 id="areaCarpet" onclick="selectAreaType('carpet')">Carpet Area</div>
                        </div>
                        <input type="hidden" id="areaType" value="{{ $room->area_type ?? 'super' }}">
                        <div class="inline-input-group" style="margin-top:0.5rem;">
                            <input type="number" id="roomArea" placeholder="Area"
                                   value="{{ $room->area ?? '' }}" min="0" step="0.01">
                            <span>sq. ft.</span>
                        </div>
                    </div>

                    <!-- Capacity -->
                    <div class="spec-group">
                        <h4><i class="fas fa-users"></i> Room Capacity</h4>
                        <div class="inline-input-group">
                            <input type="number" id="roomCapacity" placeholder="Persons"
                                   value="{{ $room->capacity ?? '' }}" min="0">
                            <span>persons</span>
                        </div>
                    </div>

                    <!-- Electrical -->
                    <div class="spec-group">
                        <h4><i class="fas fa-lightbulb"></i> Electrical Items</h4>
                        <div class="count-inputs">
                            <div class="count-input">
                                <input type="number" id="lightsCount" value="{{ $room->lights_count ?? 0 }}" min="0">
                                <label>Lights</label>
                            </div>
                            <div class="count-input">
                                <input type="number" id="fansCount" value="{{ $room->fans_count ?? 0 }}" min="0">
                                <label>Fans</label>
                            </div>
                            <div class="count-input">
                                <input type="number" id="acCount" value="{{ $room->ac_count ?? 0 }}" min="0">
                                <label>AC Units</label>
                            </div>
                            <div class="count-input">
                                <input type="number" id="socketsCount" value="{{ $room->sockets_count ?? 0 }}" min="0">
                                <label>Sockets</label>
                            </div>
                        </div>
                    </div>

                    <!-- Fire Safety -->
                    <div class="spec-group">
                        <h4><i class="fas fa-fire-extinguisher"></i> Fire Safety</h4>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="hasFireExtinguisher" {{ $room->has_fire_extinguisher ? 'checked' : '' }}>
                                <label for="hasFireExtinguisher">Fire Extinguisher</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="hasFireAlarm" {{ $room->has_fire_alarm ? 'checked' : '' }}>
                                <label for="hasFireAlarm">Fire Alarm</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="hasSmokeDetector" {{ $room->has_smoke_detector ? 'checked' : '' }}>
                                <label for="hasSmokeDetector">Smoke Detector</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="hasEmergencyExit" {{ $room->has_emergency_exit ? 'checked' : '' }}>
                                <label for="hasEmergencyExit">Emergency Exit</label>
                            </div>
                        </div>
                    </div>

                    <!-- Other Amenities -->
                    <div class="spec-group">
                        <h4><i class="fas fa-star"></i> Other Amenities</h4>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="hasWashroom" {{ $room->has_washroom ? 'checked' : '' }}>
                                <label for="hasWashroom">Attached Washroom</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="hasProjector" {{ $room->has_projector ? 'checked' : '' }}>
                                <label for="hasProjector">Projector</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="hasWhiteboard" {{ $room->has_whiteboard ? 'checked' : '' }}>
                                <label for="hasWhiteboard">Whiteboard</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="hasSmartBoard" {{ $room->has_smart_board ? 'checked' : '' }}>
                                <label for="hasSmartBoard">Smart Board</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="hasWifi" {{ $room->has_wifi ? 'checked' : '' }}>
                                <label for="hasWifi">Wi-Fi Access</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="hasWaterCooler" {{ $room->has_water_cooler ? 'checked' : '' }}>
                                <label for="hasWaterCooler">Water Cooler</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="hasCCTV" {{ $room->has_cctv ? 'checked' : '' }}>
                                <label for="hasCCTV">CCTV Camera</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Allocated Amenities (from Floor) -->
                <h3><i class="fas fa-cubes"></i> Allocated Amenities (from Floor)</h3>
                <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.75rem;">
                    <i class="fas fa-info-circle"></i> Toggle which floor amenities are present in this room
                </p>
                <div id="floor-amenities-container">
                    @php
                        $floorAmenityKeys = collect($floorAllocatedAmenities)
                            ->filter(fn($v, $k) => $k !== 'facility_entries' && $v == 1)
                            ->keys();
                    @endphp

                    @if($floorAmenityKeys->isEmpty())
                        <div class="empty-text">No amenities allocated to the parent floor.</div>
                    @else
                        @foreach($floorAmenityKeys as $key)
                            @php
                                $isSelected = !empty($selectedFloorAmenities[$key]);
                                $displayName = ucfirst(str_replace('_', ' ', $key));
                            @endphp
                            <span class="amenity-toggle {{ $isSelected ? 'on' : '' }}"
                                  data-key="{{ $key }}"
                                  onclick="toggleFloorAmenity(this)">
                                <i class="fas fa-{{ $isSelected ? 'check-circle' : 'circle' }}"></i>
                                {{ $displayName }}
                            </span>
                        @endforeach
                    @endif
                </div>

                <!-- Allocated Facilities (from Floor) -->
                <h3><i class="fas fa-building"></i> Allocated Facilities (from Floor)</h3>
                <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 0.75rem;">
                    <i class="fas fa-info-circle"></i> Select which floor facilities are assigned to this room
                </p>
                <div id="floor-facilities-container">
                    @if(empty($floorFacilityOptions))
                        <div class="empty-text">No facilities allocated to the parent floor.</div>
                    @else
                        <div class="facility-checkbox-grid">
                            @foreach($floorFacilityOptions as $facility)
                                @php
                                    $isChecked = in_array($facility['id'], (array) $selectedFloorFacilities);
                                @endphp
                                <label class="facility-checkbox-card {{ $isChecked ? 'checked' : '' }}"
                                       data-facility-id="{{ $facility['id'] }}">
                                    <input type="checkbox" class="floor-facility-check"
                                           data-id="{{ $facility['id'] }}"
                                           {{ $isChecked ? 'checked' : '' }}
                                           onchange="toggleFloorFacility('{{ $facility['id'] }}', this.checked)">
                                    <span class="facility-icon-box">
                                        <i class="fas {{ $facility['icon'] ?? 'fa-building' }}"></i>
                                    </span>
                                    <span class="facility-text">
                                        <span class="facility-name">{{ $facility['name'] }}</span>
                                        <span class="facility-type">{{ $facility['type_label'] }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Additional Areas -->
                <h3><i class="fas fa-map"></i> Additional Areas</h3>
                <div id="additional-areas-container">
                    {{-- Populated by JS --}}
                </div>
                <div class="add-btn-row">
                    <button type="button" class="btn-outline-primary" onclick="addAreaCard()">
                        <i class="fas fa-plus"></i> Add Area
                    </button>
                </div>

                <!-- Gates / Entries -->
                <h3><i class="fas fa-door-open"></i> Gates / Entries</h3>
                <div id="gates-container">
                    {{-- Populated by JS --}}
                </div>
                <div class="add-btn-row">
                    <button type="button" class="btn-outline-primary" onclick="addGateCard()">
                        <i class="fas fa-plus"></i> Add Gate
                    </button>
                </div>

                <!-- Custom Amenities -->
                <h3><i class="fas fa-plus-circle" style="color:#10b981;"></i> Custom Amenities</h3>
                <div id="custom-amenities-container">
                    {{-- Populated by JS --}}
                </div>
                <div class="add-btn-row">
                    <button type="button" class="btn-outline-success" onclick="addCustomAmenityCard()">
                        <i class="fas fa-plus"></i> Add Custom Amenity
                    </button>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('rooms.list') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
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

    <!-- Toast -->
    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message">Room updated successfully!</span>
    </div>

    <script>
        const API_BASE_URL = '{{ url('/') }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const ROOM_ID = '{{ $room->id }}';

        // ============================================================
        // SERVER DATA
        // ============================================================
        const ROOM = @json($room);
        const INITIAL_ROOM_AREAS = @json($roomAdditionalAreas ?? []);
        const INITIAL_ROOM_GATES = @json($roomGates ?? []);
        const INITIAL_CUSTOM_AMENITIES = @json($roomCustomAmenities ?? []);
        const INITIAL_SELECTED_FACILITIES = @json($selectedFloorFacilities ?? []);
        const INITIAL_SELECTED_AMENITIES = @json($selectedFloorAmenities ?? []);

        // ============================================================
        // STATE
        // ============================================================
        let selectedFacilityIds = Array.isArray(INITIAL_SELECTED_FACILITIES)
            ? INITIAL_SELECTED_FACILITIES.map(f => typeof f === 'string' ? f : (f.id || ''))
            : [];
        let selectedFloorAmenities = Object.assign({}, INITIAL_SELECTED_AMENITIES || {});
        let areaCounter = 0;
        let gateCounter = 0;
        let customAmenityCounter = 0;
        let selectedAreaType = '{{ $room->area_type ?? "super" }}';

        // ============================================================
        // AREA TYPE TOGGLE
        // ============================================================
        function selectAreaType(type) {
            selectedAreaType = type;
            document.getElementById('areaType').value = type;
            document.getElementById('areaSuper').classList.toggle('selected', type === 'super');
            document.getElementById('areaCarpet').classList.toggle('selected', type === 'carpet');
        }

        // ============================================================
        // CUSTOM ROOM TYPE
        // ============================================================
        function toggleCustomRoomType() {
            const t = document.getElementById('roomType').value;
            document.getElementById('customTypeContainer').style.display = t === 'other' ? 'block' : 'none';
            if (t !== 'other') document.getElementById('customRoomType').value = '';
        }

        // ============================================================
        // FLOOR AMENITY TOGGLES
        // ============================================================
        function toggleFloorAmenity(el) {
            const key = el.getAttribute('data-key');
            const isOn = el.classList.contains('on');
            el.classList.toggle('on', !isOn);
            el.querySelector('i').className = 'fas fa-' + (!isOn ? 'check-circle' : 'circle');
            selectedFloorAmenities[key] = !isOn ? 1 : 0;
        }

        // ============================================================
        // FLOOR FACILITY CHECKBOXES
        // ============================================================
        function toggleFloorFacility(id, checked) {
            if (checked) {
                if (!selectedFacilityIds.includes(id)) selectedFacilityIds.push(id);
            } else {
                selectedFacilityIds = selectedFacilityIds.filter(fid => fid !== id);
            }
            const card = document.querySelector(`.facility-checkbox-card[data-facility-id="${id}"]`);
            if (card) card.classList.toggle('checked', checked);
        }

        // ============================================================
        // ADDITIONAL AREAS
        // ============================================================
        function addAreaCard(data) {
            data = data || {};
            areaCounter++;
            const id = areaCounter;
            const container = document.getElementById('additional-areas-container');
            const card = document.createElement('div');
            card.className = 'dynamic-card';
            card.id = `area-card-${id}`;
            card.innerHTML = `
                <div class="card-badge-sm">Area #${id}</div>
                <div class="card-row" style="grid-template-columns:2fr 1fr 1fr;">
                    <div class="form-group">
                        <label>Area Name</label>
                        <input type="text" class="form-control area-name" value="${escapeHtml(data.name || '')}" placeholder="e.g., Balcony">
                    </div>
                    <div class="form-group">
                        <label>Area Unit</label>
                        <select class="form-control area-unit">
                            <option value="sq_ft" ${(data.unit === 'sq_ft' || !data.unit) ? 'selected' : ''}>Sq. Ft.</option>
                            <option value="sq_m" ${data.unit === 'sq_m' ? 'selected' : ''}>Sq. M.</option>
                            <option value="sq_yd" ${data.unit === 'sq_yd' ? 'selected' : ''}>Sq. Yd.</option>
                            <option value="gaj" ${data.unit === 'gaj' ? 'selected' : ''}>Gaj</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Area Value</label>
                        <input type="number" class="form-control area-value" value="${data.area || ''}" placeholder="Enter area" min="0" step="0.01">
                    </div>
                </div>
                <div style="text-align:right;margin-top:0.5rem;">
                    <button type="button" class="btn-outline-danger" onclick="document.getElementById('area-card-${id}').remove()">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            `;
            container.appendChild(card);
        }

        // ============================================================
        // GATES
        // ============================================================
        function addGateCard(data) {
            data = data || {};
            gateCounter++;
            const id = gateCounter;
            const container = document.getElementById('gates-container');
            const card = document.createElement('div');
            card.className = 'dynamic-card';
            card.id = `gate-card-${id}`;
            card.innerHTML = `
                <div class="card-badge-sm">Gate #${id}</div>
                <div class="card-row" style="grid-template-columns:1fr 1fr;">
                    <div class="form-group">
                        <label>Gate Name</label>
                        <input type="text" class="form-control gate-name" value="${escapeHtml(data.name || '')}" placeholder="e.g., Main Door">
                    </div>
                    <div class="form-group">
                        <label>Gate Number</label>
                        <input type="text" class="form-control gate-number" value="${escapeHtml(data.number || '')}" placeholder="e.g., E-01">
                    </div>
                </div>
                <div style="text-align:right;margin-top:0.5rem;">
                    <button type="button" class="btn-outline-danger" onclick="document.getElementById('gate-card-${id}').remove()">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            `;
            container.appendChild(card);
        }

        // ============================================================
        // CUSTOM AMENITIES
        // ============================================================
        function addCustomAmenityCard(data) {
            data = data || {};
            customAmenityCounter++;
            const id = customAmenityCounter;
            const container = document.getElementById('custom-amenities-container');
            const card = document.createElement('div');
            card.className = 'custom-amenity-card';
            card.id = `custom-card-${id}`;
            card.innerHTML = `
                <div class="card-badge-sm">Custom #${id}</div>
                <div class="card-row" style="grid-template-columns:2fr 1fr;">
                    <div class="form-group">
                        <label>Amenity Name</label>
                        <input type="text" class="form-control custom-name" value="${escapeHtml(data.name || '')}" placeholder="e.g., Mini Fridge">
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" class="form-control custom-qty" value="${data.quantity || 1}" min="1">
                    </div>
                </div>
                <div style="text-align:right;margin-top:0.5rem;">
                    <button type="button" class="btn-outline-danger" onclick="document.getElementById('custom-card-${id}').remove()">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            `;
            container.appendChild(card);
        }

        // ============================================================
        // COLLECT FORM DATA
        // ============================================================
        function collectFormData() {
            // Additional areas
            const areas = [];
            document.querySelectorAll('#additional-areas-container .dynamic-card').forEach(card => {
                const name = card.querySelector('.area-name')?.value.trim() || '';
                const unit = card.querySelector('.area-unit')?.value || 'sq_ft';
                const value = parseFloat(card.querySelector('.area-value')?.value) || 0;
                if (name || value) areas.push({ name, unit, area: value });
            });

            // Gates
            const gates = [];
            document.querySelectorAll('#gates-container .dynamic-card').forEach(card => {
                const name = card.querySelector('.gate-name')?.value.trim() || '';
                const number = card.querySelector('.gate-number')?.value.trim() || '';
                if (name || number) gates.push({ name, number });
            });

            // Custom amenities
            const customAmenities = [];
            document.querySelectorAll('#custom-amenities-container .custom-amenity-card').forEach(card => {
                const name = card.querySelector('.custom-name')?.value.trim() || '';
                const quantity = parseInt(card.querySelector('.custom-qty')?.value) || 1;
                if (name) customAmenities.push({ name, quantity });
            });

            // Amenities (boolean flags)
            const amenities = {
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
            };

            return {
                room_number: document.getElementById('roomNumber').value.trim(),
                room_name: document.getElementById('roomName').value.trim() || null,
                description: document.getElementById('roomDescription').value.trim() || null,
                room_type: document.getElementById('roomType').value,
                custom_room_type: document.getElementById('roomType').value === 'other'
                    ? (document.getElementById('customRoomType').value.trim() || null)
                    : null,
                status: document.getElementById('roomStatus').value,
                area: parseFloat(document.getElementById('roomArea').value) || null,
                area_type: selectedAreaType,
                capacity: parseInt(document.getElementById('roomCapacity').value) || null,
                lights_count: parseInt(document.getElementById('lightsCount').value) || 0,
                fans_count: parseInt(document.getElementById('fansCount').value) || 0,
                ac_count: parseInt(document.getElementById('acCount').value) || 0,
                sockets_count: parseInt(document.getElementById('socketsCount').value) || 0,
                has_ac: parseInt(document.getElementById('acCount').value) > 0 ? 'yes' : 'no',
                ...amenities,
                additional_areas: areas,
                gates: gates,
                custom_amenities: customAmenities,
                selected_floor_amenities: selectedFloorAmenities,
                selected_floor_facilities: selectedFacilityIds,
            };
        }

        // ============================================================
        // HELPERS
        // ============================================================
        function escapeHtml(text) {
            if (text === null || text === undefined) return '';
            const map = { '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#039;' };
            return String(text).replace(/[&<>"']/g, m => map[m]);
        }

        function showToast(message, type) {
            type = type || 'success';
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
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
            toast._timeout = setTimeout(() => toast.classList.remove('show'), 4000);
        }

        // ============================================================
        // SUBMIT
        // ============================================================
        async function submitForm(e) {
            e.preventDefault();

            const data = collectFormData();
            if (!data.room_number) {
                showToast('Room number is required', 'error');
                return;
            }
            if (data.room_type === 'other' && !data.custom_room_type) {
                showToast('Please enter a custom room type', 'error');
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            const originalHTML = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="loading-spinner-btn"></span> Updating...';
            submitBtn.disabled = true;

            try {
                const response = await fetch(`${API_BASE_URL}/rooms/${ROOM_ID}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showToast('Room updated successfully!');
                    setTimeout(() => {
                        window.location.href = '{{ route("rooms.list") }}';
                    }, 1000);
                } else {
                    showToast(result.message || 'Failed to update room', 'error');
                    submitBtn.innerHTML = originalHTML;
                    submitBtn.disabled = false;
                }
            } catch (error) {
                console.error('Update error:', error);
                showToast('Failed to update room', 'error');
                submitBtn.innerHTML = originalHTML;
                submitBtn.disabled = false;
            }
        }

        // ============================================================
        // DELETE
        // ============================================================
        async function deleteRoom() {
            if (!confirm('Are you sure you want to delete this room?')) return;

            const deleteBtn = document.getElementById('deleteBtn');
            const originalHTML = deleteBtn.innerHTML;
            deleteBtn.innerHTML = '<span class="loading-spinner-btn"></span> Deleting...';
            deleteBtn.disabled = true;

            try {
                const response = await fetch(`${API_BASE_URL}/rooms/${ROOM_ID}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const result = await response.json();
                if (result.success) {
                    showToast('Room deleted successfully!');
                    setTimeout(() => window.location.href = '{{ route("rooms.list") }}', 1000);
                } else {
                    showToast(result.message || 'Failed to delete room', 'error');
                    deleteBtn.innerHTML = originalHTML;
                    deleteBtn.disabled = false;
                }
            } catch (error) {
                console.error('Delete error:', error);
                showToast('Failed to delete room', 'error');
                deleteBtn.innerHTML = originalHTML;
                deleteBtn.disabled = false;
            }
        }

        // ============================================================
        // INIT
        // ============================================================
        document.addEventListener('DOMContentLoaded', function () {
            // Load initial dynamic cards
            (INITIAL_ROOM_AREAS || []).forEach(a => addAreaCard(a));
            (INITIAL_ROOM_GATES || []).forEach(g => addGateCard(g));
            (INITIAL_CUSTOM_AMENITIES || []).forEach(c => addCustomAmenityCard(c));

            // Bind form submit
            document.getElementById('editRoomForm').addEventListener('submit', submitForm);
        });
    </script>
</body>
</html>
@endsection