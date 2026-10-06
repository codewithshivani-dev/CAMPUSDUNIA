@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details - {{ $room->room_number ?? 'Room' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
            --primary-color: #4361ee;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #0ea5e9;
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

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            color: white;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header h1 i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 12px;
        }

        .header .subtitle {
            opacity: 0.9;
            font-size: 1rem;
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-white {
            background: white;
            color: var(--primary-color);
        }

        .btn-white:hover {
            background: #f0f4ff;
            transform: translateY(-2px);
            color: var(--primary-color);
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
            color: white;
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            color: white;
        }

        /* Main Content */
        .room-detail {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Room Header Card */
        .room-header-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
            border: 2px solid var(--border-color);
            margin-bottom: 2rem;
            display: flex;
            gap: 2rem;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .room-icon-large {
            width: 100px;
            height: 100px;
            border-radius: 16px;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            flex-shrink: 0;
        }

        .room-header-info {
            flex: 1;
        }

        .room-header-info .room-name {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0 0 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .room-header-info .room-number-badge {
            font-family: 'Courier New', monospace;
            background: rgba(67, 97, 238, 0.08);
            padding: 4px 14px;
            border-radius: 8px;
            font-size: 0.9rem;
            color: var(--primary-color);
            font-weight: 600;
        }

        .room-header-info .room-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 0.5rem;
        }

        .room-header-info .room-meta .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .room-header-info .room-meta .meta-item i {
            color: var(--primary-color);
            width: 18px;
        }

        .room-header-info .room-status {
            display: inline-flex;
            align-items: center;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .status-under_maintenance {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .status-renovation {
            background: #e0e7ff;
            color: #3730a3;
            border: 1px solid #c7d2fe;
        }

        .occupancy-vacant {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .occupancy-occupied {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .occupancy-partially_occupied {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .room-header-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
            transition: all 0.3s;
        }

        .info-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .info-card .card-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 0.75rem;
        }

        .info-card .card-icon.blue { background: #eff6ff; color: #2563eb; }
        .info-card .card-icon.green { background: #ecfdf5; color: #059669; }
        .info-card .card-icon.orange { background: #fffbeb; color: #d97706; }
        .info-card .card-icon.purple { background: #f5f3ff; color: #7c3aed; }
        .info-card .card-icon.teal { background: #ecfdf5; color: #0d9488; }
        .info-card .card-icon.pink { background: #fdf2f8; color: #db2777; }
        .info-card .card-icon.rose { background: #fff1f2; color: #e11d48; }
        .info-card .card-icon.indigo { background: #e0e7ff; color: #4f46e5; }

        .info-card .card-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-card .card-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-top: 4px;
        }

        /* Detail Sections */
        .detail-section {
            background: white;
            border-radius: 16px;
            padding: 1.5rem 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
        }

        .detail-section .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0 0 1rem 0;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--border-color);
        }

        .detail-section .section-title i {
            color: var(--primary-color);
        }

        .detail-section .section-title .count-badge {
            font-size: 0.8rem;
            font-weight: 400;
            color: var(--text-muted);
            margin-left: auto;
        }

        .tag-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            background: var(--bg-light);
            border: 1px solid var(--border-color);
            color: var(--text-dark);
            transition: all 0.2s;
        }

        .tag:hover {
            border-color: var(--primary-color);
            background: #f0f4ff;
        }

        .tag i {
            color: var(--primary-color);
            font-size: 0.7rem;
        }

        .tag.green {
            background: #f0fdf4;
            border-color: #bbf7d0;
            color: #166534;
        }

        .tag.green i { color: #10b981; }

        .tag.purple {
            background: #f5f3ff;
            border-color: #c4b5fd;
            color: #5b21b6;
        }

        .tag.purple i { color: #7c3aed; }

        .tag.orange {
            background: #fffbeb;
            border-color: #fde68a;
            color: #92400e;
        }

        .tag.orange i { color: #f59e0b; }

        .tag.teal {
            background: #ecfdf5;
            border-color: #6ee7b7;
            color: #065f46;
        }

        .tag.teal i { color: #0d9488; }

        .tag.rose {
            background: #fff1f2;
            border-color: #fecdd3;
            color: #be123c;
        }

        .tag.rose i { color: #e11d48; }

        .tag.indigo {
            background: #e0e7ff;
            border-color: #c7d2fe;
            color: #3730a3;
        }

        .tag.indigo i { color: #4f46e5; }

        /* Facility tag with type badge — matches block/floor view */
        .facility-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border: 1px solid #6ee7b7;
            color: #065f46;
            transition: all 0.2s;
        }

        .facility-tag:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.2);
        }

        .facility-tag i { color: #0d9488; }
        .facility-tag .facility-type {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            background: rgba(13, 148, 136, 0.15);
            padding: 2px 8px;
            border-radius: 10px;
            color: #0d9488;
            letter-spacing: 0.3px;
        }

        .amenity-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            background: #f0f4ff;
            border: 1px solid rgba(67, 97, 238, 0.2);
            color: var(--primary-color);
            transition: all 0.2s;
        }

        .amenity-tag:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .amenity-tag i { color: var(--primary-color); }
        .amenity-tag .count {
            background: rgba(67, 97, 238, 0.1);
            padding: 1px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            margin-left: 4px;
        }

        .amenity-tag.present {
            background: #dcfce7;
            border-color: #bbf7d0;
            color: #166534;
        }

        .amenity-tag.present i { color: #10b981; }

        .amenity-tag.absent {
            background: #f1f5f9;
            border-color: #e2e8f0;
            color: #94a3b8;
        }

        .amenity-tag.absent i { color: #94a3b8; }

        .empty-text {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* Specifications Grid */
        .spec-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .spec-item {
            background: var(--bg-light);
            padding: 0.75rem 1rem;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .spec-item .spec-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .spec-item .spec-value {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .spec-item .spec-value i {
            color: var(--primary-color);
            margin-right: 4px;
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--success-color);
            color: white;
            padding: 14px 20px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1001;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast.error {
            background: var(--danger-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
            }

            .room-header-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 1.5rem;
            }

            .room-icon-large {
                width: 80px;
                height: 80px;
                font-size: 2.5rem;
            }

            .room-header-info .room-name {
                font-size: 1.5rem;
                justify-content: center;
            }

            .room-header-info .room-meta {
                justify-content: center;
            }

            .room-header-actions {
                justify-content: center;
            }

            .info-grid {
                grid-template-columns: 1fr 1fr;
            }

            .detail-section {
                padding: 1rem;
            }

            .spec-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="header">
            <div>
                <h1><i class="fas fa-door-open"></i> Room Details</h1>
                <p class="subtitle"><i class="fas fa-info-circle"></i> Complete overview of the room</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('rooms.list') }}" class="btn btn-white">
                    <i class="fas fa-arrow-left"></i> Back to Rooms
                </a>
                <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Room
                </a>
            </div>
        </div>

        @if($room)
        <!-- Room Header -->
        <div class="room-detail">
            <div class="room-header-card">
                <div class="room-icon-large">
                    <i class="fas fa-door-open"></i>
                </div>
                <div class="room-header-info">
                    <div class="room-name">
                        {{ $room->room_number }}
                        <span class="room-number-badge">{{ $room->room_name ?? 'No Name' }}</span>
                    </div>
                    <div class="room-meta">
                        <span class="meta-item">
                            <i class="fas fa-university"></i> 
                            Building: {{ $room->building->name ?? 'N/A' }}
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-building"></i> 
                            Block: {{ $room->block->name ?? 'N/A' }}
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-layer-group"></i> 
                            Floor: {{ $room->floor->floor_number ?? 'N/A' }}
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-tag"></i> 
                            Type: {{ $room->room_type_display ?? $room->room_type }}
                        </span>
                    </div>
                    <div>
                        <span class="room-status status-{{ $room->status ?? 'active' }}">
                            <i class="fas {{ $room->status === 'active' ? 'fa-check-circle' : ($room->status === 'inactive' ? 'fa-times-circle' : 'fa-exclamation-triangle') }}"></i>
                            {{ ucfirst(str_replace('_', ' ', $room->status ?? 'Active')) }}
                        </span>
                        <span class="room-status occupancy-{{ $room->occupancy_status ?? 'vacant' }}" style="margin-left: 10px;">
                            <i class="fas {{ $room->occupancy_status === 'vacant' ? 'fa-check-circle' : ($room->occupancy_status === 'occupied' ? 'fa-user' : 'fa-users') }}"></i>
                            {{ ucfirst(str_replace('_', ' ', $room->occupancy_status ?? 'Vacant')) }}
                        </span>
                    </div>
                    <div class="room-header-actions">
                        <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Room
                        </a>
                        <button onclick="deleteRoom({{ $room->id }}, '{{ addslashes($room->room_number) }}')" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Room
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="info-grid">
                <div class="info-card">
                    <div class="card-icon blue"><i class="fas fa-users"></i></div>
                    <div class="card-label">Capacity</div>
                    <div class="card-value">{{ $room->capacity ?? 'N/A' }} persons</div>
                </div>
                <div class="info-card">
                    <div class="card-icon green"><i class="fas fa-vector-square"></i></div>
                    <div class="card-label">Area</div>
                    <div class="card-value">{{ $room->area ?? 'N/A' }} {{ $room->area_type ?? 'sq.ft.' }}</div>
                </div>
                <div class="info-card">
                    <div class="card-icon orange"><i class="fas fa-lightbulb"></i></div>
                    <div class="card-label">Lights</div>
                    <div class="card-value">{{ $room->lights_count ?? 0 }}</div>
                </div>
                <div class="info-card">
                    <div class="card-icon purple"><i class="fas fa-concierge-bell"></i></div>
                    <div class="card-label">Amenities</div>
                    <div class="card-value">
                        @php
                            $amenityCount = 0;
                            $amenityList = [
                                'has_ac' => 'AC',
                                'has_wifi' => 'Wi-Fi',
                                'has_projector' => 'Projector',
                                'has_whiteboard' => 'Whiteboard',
                                'has_smart_board' => 'Smart Board',
                                'has_washroom' => 'Washroom',
                                'has_cctv' => 'CCTV',
                                'has_fire_extinguisher' => 'Fire Ext.',
                            ];
                            foreach ($amenityList as $key => $label) {
                                if ($room->$key) $amenityCount++;
                            }
                        @endphp
                        {{ $amenityCount }}
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($room->description)
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-align-left"></i> Description
                </div>
                <p style="color: var(--text-dark); font-size: 0.95rem; line-height: 1.6; margin: 0;">
                    {{ $room->description }}
                </p>
            </div>
            @endif

            <!-- Room Specifications -->
            @if($roomSpecifications && count($roomSpecifications) > 0)
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-cogs"></i> Room Specifications
                </div>
                <div class="spec-grid">
                    <!-- General Specifications -->
                    @if(isset($roomSpecifications['capacity']) || isset($roomSpecifications['min_capacity']))
                        <div class="spec-item">
                            <span class="spec-label">Capacity</span>
                            <span class="spec-value">{{ $roomSpecifications['capacity'] ?? 'N/A' }} persons</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Min Capacity</span>
                            <span class="spec-value">{{ $roomSpecifications['min_capacity'] ?? 'N/A' }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Occupancy Type</span>
                            <span class="spec-value">{{ ucfirst($roomSpecifications['occupancy_type'] ?? 'N/A') }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Category</span>
                            <span class="spec-value">{{ ucfirst($roomSpecifications['category'] ?? 'N/A') }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Usage Type</span>
                            <span class="spec-value">{{ ucfirst($roomSpecifications['usage_type'] ?? 'N/A') }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Booking Required</span>
                            <span class="spec-value">{{ ucfirst($roomSpecifications['booking_required'] ?? 'N/A') }}</span>
                        </div>
                    @endif

                    <!-- Dimensions -->
                    @if(isset($roomSpecifications['area']) || isset($roomSpecifications['length']))
                        <div class="spec-item">
                            <span class="spec-label">Area</span>
                            <span class="spec-value">{{ $roomSpecifications['area'] ?? 'N/A' }} sq.ft.</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Length</span>
                            <span class="spec-value">{{ $roomSpecifications['length'] ?? 'N/A' }} ft</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Width</span>
                            <span class="spec-value">{{ $roomSpecifications['width'] ?? 'N/A' }} ft</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Height</span>
                            <span class="spec-value">{{ $roomSpecifications['height'] ?? 'N/A' }} ft</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Doors</span>
                            <span class="spec-value">{{ $roomSpecifications['doors'] ?? 0 }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Windows</span>
                            <span class="spec-value">{{ $roomSpecifications['windows'] ?? 0 }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Door Type</span>
                            <span class="spec-value">{{ ucfirst($roomSpecifications['door_type'] ?? 'N/A') }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Window Type</span>
                            <span class="spec-value">{{ ucfirst($roomSpecifications['window_type'] ?? 'N/A') }}</span>
                        </div>
                    @endif

                    <!-- Electrical -->
                    @if(isset($roomSpecifications['lights_count']) || isset($roomSpecifications['fans_count']))
                        <div class="spec-item">
                            <span class="spec-label">Lights</span>
                            <span class="spec-value">{{ $roomSpecifications['lights_count'] ?? 0 }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Fans</span>
                            <span class="spec-value">{{ $roomSpecifications['fans_count'] ?? 0 }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">AC Units</span>
                            <span class="spec-value">{{ $roomSpecifications['ac_count'] ?? 0 }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Sockets</span>
                            <span class="spec-value">{{ $roomSpecifications['sockets_count'] ?? 0 }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Lighting Type</span>
                            <span class="spec-value">{{ ucfirst($roomSpecifications['lighting_type'] ?? 'N/A') }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">AC Type</span>
                            <span class="spec-value">{{ ucfirst($roomSpecifications['ac_type'] ?? 'N/A') }}</span>
                        </div>
                    @endif

                    <!-- Connectivity -->
                    @if(isset($roomSpecifications['has_wifi']) || isset($roomSpecifications['internet_speed']))
                        <div class="spec-item">
                            <span class="spec-label">Wi-Fi</span>
                            <span class="spec-value">{{ ($roomSpecifications['has_wifi'] ?? false) ? 'Yes' : 'No' }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">LAN</span>
                            <span class="spec-value">{{ ($roomSpecifications['has_lan'] ?? false) ? 'Yes' : 'No' }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Telephone</span>
                            <span class="spec-value">{{ ($roomSpecifications['has_telephone'] ?? false) ? 'Yes' : 'No' }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">CCTV</span>
                            <span class="spec-value">{{ ($roomSpecifications['has_cctv'] ?? false) ? 'Yes' : 'No' }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Internet Speed</span>
                            <span class="spec-value">{{ $roomSpecifications['internet_speed'] ?? 'N/A' }} Mbps</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Network Type</span>
                            <span class="spec-value">{{ ucfirst($roomSpecifications['network_type'] ?? 'N/A') }}</span>
                        </div>
                    @endif

                    <!-- Security -->
                    @if(isset($roomSpecifications['security_level']))
                        <div class="spec-item">
                            <span class="spec-label">Security Level</span>
                            <span class="spec-value">{{ ucfirst($roomSpecifications['security_level'] ?? 'N/A') }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Door Lock</span>
                            <span class="spec-value">{{ ($roomSpecifications['has_door_lock'] ?? false) ? 'Yes' : 'No' }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Smart Lock</span>
                            <span class="spec-value">{{ ($roomSpecifications['has_smart_lock'] ?? false) ? 'Yes' : 'No' }}</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-label">Intercom</span>
                            <span class="spec-value">{{ ($roomSpecifications['has_intercom'] ?? false) ? 'Yes' : 'No' }}</span>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Amenities -->
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-concierge-bell"></i> Amenities
                    <span class="count-badge">{{ $amenityCount ?? 0 }} amenities</span>
                </div>
                <div class="tag-container">
                    @php
                        $amenities = [
                            'has_ac' => ['label' => 'Air Conditioning', 'icon' => 'fa-snowflake', 'value' => $room->has_ac === 'yes'],
                            'has_wifi' => ['label' => 'Wi-Fi', 'icon' => 'fa-wifi', 'value' => $room->has_wifi],
                            'has_projector' => ['label' => 'Projector', 'icon' => 'fa-video', 'value' => $room->has_projector],
                            'has_whiteboard' => ['label' => 'Whiteboard', 'icon' => 'fa-chalkboard', 'value' => $room->has_whiteboard],
                            'has_smart_board' => ['label' => 'Smart Board', 'icon' => 'fa-chalkboard-teacher', 'value' => $room->has_smart_board],
                            'has_washroom' => ['label' => 'Washroom', 'icon' => 'fa-toilet', 'value' => $room->has_washroom],
                            'has_cctv' => ['label' => 'CCTV', 'icon' => 'fa-video', 'value' => $room->has_cctv],
                            'has_fire_extinguisher' => ['label' => 'Fire Extinguisher', 'icon' => 'fa-fire-extinguisher', 'value' => $room->has_fire_extinguisher],
                            'has_fire_alarm' => ['label' => 'Fire Alarm', 'icon' => 'fa-bell', 'value' => $room->has_fire_alarm],
                            'has_smoke_detector' => ['label' => 'Smoke Detector', 'icon' => 'fa-smog', 'value' => $room->has_smoke_detector],
                            'has_emergency_exit' => ['label' => 'Emergency Exit', 'icon' => 'fa-door-open', 'value' => $room->has_emergency_exit],
                            'has_water_cooler' => ['label' => 'Water Cooler', 'icon' => 'fa-tint', 'value' => $room->has_water_cooler],
                            'has_desk' => ['label' => 'Desk', 'icon' => 'fa-chair', 'value' => $room->has_desk],
                            'has_chair' => ['label' => 'Chairs', 'icon' => 'fa-chair', 'value' => $room->has_chair],
                            'has_cabinets' => ['label' => 'Cabinets', 'icon' => 'fa-cabinet', 'value' => $room->has_cabinets],
                            'has_bed' => ['label' => 'Bed', 'icon' => 'fa-bed', 'value' => $room->has_bed],
                            'has_tv' => ['label' => 'TV', 'icon' => 'fa-tv', 'value' => $room->has_tv],
                            'has_kitchenette' => ['label' => 'Kitchenette', 'icon' => 'fa-utensils', 'value' => $room->has_kitchenette],
                            'has_fridge' => ['label' => 'Refrigerator', 'icon' => 'fa-fridge', 'value' => $room->has_fridge],
                            'has_microwave' => ['label' => 'Microwave', 'icon' => 'fa-microchip', 'value' => $room->has_microwave],
                            'has_hot_water' => ['label' => 'Hot Water', 'icon' => 'fa-hot-tub', 'value' => $room->has_hot_water],
                            'has_shower' => ['label' => 'Shower', 'icon' => 'fa-shower', 'value' => $room->has_shower],
                            'has_bathtub' => ['label' => 'Bathtub', 'icon' => 'fa-bath', 'value' => $room->has_bathtub],
                            'has_wheelchair_access' => ['label' => 'Wheelchair Access', 'icon' => 'fa-wheelchair', 'value' => $room->has_wheelchair_access],
                            'has_grab_bars' => ['label' => 'Grab Bars', 'icon' => 'fa-hand-holding', 'value' => $room->has_grab_bars],
                            'has_visual_alerts' => ['label' => 'Visual Alerts', 'icon' => 'fa-eye', 'value' => $room->has_visual_alerts],
                            'has_lan' => ['label' => 'LAN Port', 'icon' => 'fa-network-wired', 'value' => $room->has_lan],
                            'has_telephone' => ['label' => 'Telephone', 'icon' => 'fa-phone', 'value' => $room->has_telephone],
                            'has_door_lock' => ['label' => 'Door Lock', 'icon' => 'fa-lock', 'value' => $room->has_door_lock],
                            'has_smart_lock' => ['label' => 'Smart Lock', 'icon' => 'fa-fingerprint', 'value' => $room->has_smart_lock],
                            'has_intercom' => ['label' => 'Intercom', 'icon' => 'fa-comment', 'value' => $room->has_intercom],
                            'has_sprinkler' => ['label' => 'Sprinkler System', 'icon' => 'fa-water', 'value' => $room->has_sprinkler],
                            'has_emergency_light' => ['label' => 'Emergency Light', 'icon' => 'fa-lightbulb', 'value' => $room->has_emergency_light],
                        ];
                    @endphp

                    @php $hasAmenity = false; @endphp
                    @foreach($amenities as $key => $amenity)
                        @if($amenity['value'])
                            @php $hasAmenity = true; @endphp
                            <span class="amenity-tag present">
                                <i class="fas {{ $amenity['icon'] }}"></i>
                                {{ $amenity['label'] }}
                            </span>
                        @endif
                    @endforeach

                    @if(!$hasAmenity)
                        <span class="empty-text">No amenities configured for this room.</span>
                    @endif
                </div>
            </div>

            <!-- Floor Amenities & Facilities -->
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-layer-group"></i> Floor Amenities & Facilities
                    <span class="count-badge">Assigned from floor</span>
                </div>
                <div class="tag-container">
                    {{-- Floor Amenities (key → value pairs) --}}
                    @if($selectedFloorAmenities && count($selectedFloorAmenities) > 0)
                        @foreach($selectedFloorAmenities as $key => $value)
                            @php
                                $displayName = ucfirst(str_replace('_', ' ', $key));
                            @endphp
                            <span class="tag teal">
                                <i class="fas fa-cube"></i>
                                {{ $displayName }}
                                @if(is_numeric($value) && $value > 1)
                                    <span style="background: rgba(13, 148, 136, 0.15); padding: 1px 8px; border-radius: 12px; font-size: 0.7rem; margin-left: 4px;">
                                        ×{{ $value }}
                                    </span>
                                @endif
                            </span>
                        @endforeach
                    @endif

                    {{-- Floor Facilities (RESOLVED NAMES with icons + type labels) --}}
                    @if(!empty($resolvedSelectedFacilities) && count($resolvedSelectedFacilities) > 0)
                        @foreach($resolvedSelectedFacilities as $facility)
                            <span class="facility-tag">
                                <i class="fas {{ $facility['icon'] ?? 'fa-building' }}"></i>
                                {{ $facility['name'] }}
                                @if(!empty($facility['type_label']) && $facility['type_label'] !== 'Unknown')
                                    <span class="facility-type">{{ $facility['type_label'] }}</span>
                                @endif
                            </span>
                        @endforeach
                    @endif

                    {{-- Empty state --}}
                    @if(
                        (empty($selectedFloorAmenities) || count($selectedFloorAmenities) == 0) &&
                        (empty($resolvedSelectedFacilities) || count($resolvedSelectedFacilities) == 0)
                    )
                        <span class="empty-text">
                            <i class="fas fa-info-circle"></i>
                            No floor amenities or facilities assigned to this room.
                        </span>
                    @endif
                </div>
            </div>

            <!-- Department & In-charge -->
            @if($room->department || $room->in_charge_name)
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-users"></i> Department & In-charge
                </div>
                <div class="spec-grid">
                    @if($room->department)
                    <div class="spec-item">
                        <span class="spec-label">Department</span>
                        <span class="spec-value"><i class="fas fa-building"></i> {{ $room->department }}</span>
                    </div>
                    @endif
                    @if($room->in_charge_name)
                    <div class="spec-item">
                        <span class="spec-label">In-charge</span>
                        <span class="spec-value"><i class="fas fa-user"></i> {{ $room->in_charge_name }}</span>
                    </div>
                    @endif
                    @if($room->in_charge_contact)
                    <div class="spec-item">
                        <span class="spec-label">Contact</span>
                        <span class="spec-value"><i class="fas fa-phone"></i> {{ $room->in_charge_contact }}</span>
                    </div>
                    @endif
                    @if($room->special_notes)
                    <div class="spec-item">
                        <span class="spec-label">Special Notes</span>
                        <span class="spec-value">{{ $room->special_notes }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Maintenance -->
            @if($room->last_maintenance_date || $room->next_maintenance_date)
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-tools"></i> Maintenance Information
                </div>
                <div class="spec-grid">
                    @if($room->last_maintenance_date)
                    <div class="spec-item">
                        <span class="spec-label">Last Maintenance</span>
                        <span class="spec-value"><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($room->last_maintenance_date)->format('d M Y') }}</span>
                    </div>
                    @endif
                    @if($room->next_maintenance_date)
                    <div class="spec-item">
                        <span class="spec-label">Next Maintenance</span>
                        <span class="spec-value"><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($room->next_maintenance_date)->format('d M Y') }}</span>
                    </div>
                    @endif
                    @if($room->maintenance_notes)
                    <div class="spec-item" style="grid-column: 1 / -1;">
                        <span class="spec-label">Maintenance Notes</span>
                        <span class="spec-value">{{ $room->maintenance_notes }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>
        @else
        <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 16px; border: 2px solid var(--border-color);">
            <i class="fas fa-exclamation-circle" style="font-size: 4rem; color: var(--danger-color); display: block; margin-bottom: 1rem;"></i>
            <h3 style="color: var(--text-dark);">Room Not Found</h3>
            <p style="color: var(--text-muted);">The room you are looking for does not exist or has been deleted.</p>
            <a href="{{ route('rooms.list') }}" class="btn btn-primary" style="margin-top: 1rem;">
                <i class="fas fa-arrow-left"></i> Back to Rooms
            </a>
        </div>
        @endif

        <!-- Toast -->
        <div id="toast" class="toast">
            <i class="fas fa-check-circle"></i>
            <span id="toastMessage">Success!</span>
        </div>
    </div>

    <script>
        const API_BASE_URL = '{{ url('/') }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            
            if (!toast || !toastMessage) {
                alert(message);
                return;
            }
            
            toastMessage.textContent = message;
            toast.className = 'toast';
            if (type === 'error') {
                toast.style.background = '#ef4444';
                toast.querySelector('i').className = 'fas fa-exclamation-circle';
            } else {
                toast.style.background = '#10b981';
                toast.querySelector('i').className = 'fas fa-check-circle';
            }
            
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        async function deleteRoom(id, name) {
            if (!confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
                return;
            }

            try {
                const response = await fetch(`${API_BASE_URL}/rooms/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast(`Room "${name}" deleted successfully!`);
                    setTimeout(() => {
                        window.location.href = '{{ route("rooms.list") }}';
                    }, 1500);
                } else {
                    showToast(result.message || 'Failed to delete room', 'error');
                }
            } catch (error) {
                console.error('Error deleting room:', error);
                showToast('Failed to delete room', 'error');
            }
        }
    </script>
</body>
</html>
@endsection