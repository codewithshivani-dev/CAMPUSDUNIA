@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Floor Details - {{ $floor->floor_number ?? 'Floor' }}</title>
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

        .btn-success {
            background: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            color: white;
        }

        /* Main Content */
        .floor-detail {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Floor Header Card */
        .floor-header-card {
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

        .floor-icon-large {
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

        .floor-header-info {
            flex: 1;
        }

        .floor-header-info .floor-name {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0 0 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .floor-header-info .floor-code {
            font-family: 'Courier New', monospace;
            background: rgba(67, 97, 238, 0.08);
            padding: 4px 14px;
            border-radius: 8px;
            font-size: 0.9rem;
            color: var(--primary-color);
            font-weight: 600;
        }

        .floor-header-info .floor-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 0.5rem;
        }

        .floor-header-info .floor-meta .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .floor-header-info .floor-meta .meta-item i {
            color: var(--primary-color);
            width: 18px;
        }

        .floor-header-info .floor-status {
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

        .floor-header-actions {
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

        .tag.pink {
            background: #fce7f3;
            border-color: #f9a8d4;
            color: #831843;
        }

        .tag.pink i { color: #db2777; }

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

        .empty-text {
            color: var(--text-muted);
            font-size: 0.9rem;
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

            .floor-header-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 1.5rem;
            }

            .floor-icon-large {
                width: 80px;
                height: 80px;
                font-size: 2.5rem;
            }

            .floor-header-info .floor-name {
                font-size: 1.5rem;
                justify-content: center;
            }

            .floor-header-info .floor-meta {
                justify-content: center;
            }

            .floor-header-actions {
                justify-content: center;
            }

            .info-grid {
                grid-template-columns: 1fr 1fr;
            }

            .detail-section {
                padding: 1rem;
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
                <h1><i class="fas fa-layer-group"></i> Floor Details</h1>
                <p class="subtitle"><i class="fas fa-info-circle"></i> Complete overview of the floor</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('floors.list') }}" class="btn btn-white">
                    <i class="fas fa-arrow-left"></i> Back to Floors
                </a>
                <a href="{{ route('floors.edit', $floor->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Floor
                </a>
            </div>
        </div>

        @if($floor)
        <!-- Floor Header -->
        <div class="floor-detail">
            <div class="floor-header-card">
                <div class="floor-icon-large">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="floor-header-info">
                    <div class="floor-name">
                        {{ $floor->floor_number }}
                        <span class="floor-code">{{ $floor->floor_name ?? 'No Name' }}</span>
                    </div>
                    <div class="floor-meta">
                        <span class="meta-item">
                            <i class="fas fa-university"></i> 
                            Building: {{ $floor->building->name ?? 'N/A' }}
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-building"></i> 
                            Block: {{ $floor->block->name ?? 'N/A' }}
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-vector-square"></i> 
                            Area: {{ ($floor->total_area ?? 0) > 0 ? $floor->total_area . ' ' . ($floor->area_unit ?? 'sq_ft') : 'Not specified' }}
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-door-open"></i> 
                            Rooms: {{ $floor->total_rooms ?? 0 }}
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-users"></i> 
                            Capacity: {{ $floor->total_capacity ?? 0 }}
                        </span>
                        @if($floor->floor_level !== null)
                        <span class="meta-item">
                            <i class="fas fa-level-up-alt"></i> 
                            Level: {{ $floor->floor_level }}
                        </span>
                        @endif
                    </div>
                    <div>
                        <span class="floor-status status-{{ $floor->status ?? 'active' }}">
                            <i class="fas {{ $floor->status === 'active' ? 'fa-check-circle' : ($floor->status === 'inactive' ? 'fa-times-circle' : 'fa-exclamation-triangle') }}"></i>
                            {{ ucfirst(str_replace('_', ' ', $floor->status ?? 'Active')) }}
                        </span>
                    </div>
                    <div class="floor-header-actions">
                        <a href="{{ route('floors.edit', $floor->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Floor
                        </a>
                        <button onclick="deleteFloor({{ $floor->id }}, '{{ addslashes($floor->floor_number) }}')" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Floor
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="info-grid">
                <div class="info-card">
                    <div class="card-icon blue"><i class="fas fa-door-open"></i></div>
                    <div class="card-label">Total Rooms</div>
                    <div class="card-value">{{ $floor->total_rooms ?? 0 }}</div>
                </div>
                <div class="info-card">
                    <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
                    <div class="card-label">Available Rooms</div>
                    <div class="card-value">{{ $floor->available_rooms ?? 0 }}</div>
                </div>
                <div class="info-card">
                    <div class="card-icon orange"><i class="fas fa-users"></i></div>
                    <div class="card-label">Total Capacity</div>
                    <div class="card-value">{{ $floor->total_capacity ?? 0 }}</div>
                </div>
                <div class="info-card">
                    <div class="card-icon purple"><i class="fas fa-concierge-bell"></i></div>
                    <div class="card-label">Amenities</div>
                    <div class="card-value">{{ $totalAmenities ?? 0 }}</div>
                </div>
                <div class="info-card">
                    <div class="card-icon teal"><i class="fas fa-map"></i></div>
                    <div class="card-label">Additional Areas</div>
                    <div class="card-value">{{ $areasCount ?? 0 }}</div>
                </div>
                <div class="info-card">
                    <div class="card-icon pink"><i class="fas fa-door-open"></i></div>
                    <div class="card-label">Entries/Exits</div>
                    <div class="card-value">{{ $gatesCount ?? 0 }}</div>
                </div>
            </div>

            <!-- Description -->
            @if($floor->description)
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-align-left"></i> Description
                </div>
                <p style="color: var(--text-dark); font-size: 0.95rem; line-height: 1.6; margin: 0;">
                    {{ $floor->description }}
                </p>
            </div>
            @endif

            <!-- Additional Areas -->
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-map"></i> Additional Areas
                    <span style="font-size: 0.8rem; font-weight: 400; color: var(--text-muted); margin-left: auto;">
                        {{ $areasCount ?? 0 }} areas
                    </span>
                </div>
                <div class="tag-container">
                    @if($additionalAreas && count($additionalAreas) > 0)
                        @foreach($additionalAreas as $area)
                            @if($area && ($area['name'] ?? ''))
                            <span class="tag green">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $area['name'] ?? 'Unnamed Area' }}
                                @if($area['area'] ?? '')
                                <span style="font-size: 0.7rem; color: var(--text-muted);">
                                    — {{ $area['area'] }} {{ $area['unit'] ?? 'sq_ft' }}
                                </span>
                                @endif
                            </span>
                            @endif
                        @endforeach
                    @else
                        <span class="empty-text">No additional areas added.</span>
                    @endif
                </div>
            </div>

            <!-- Gates / Entries -->
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-door-open"></i> Entries / Exits
                    <span style="font-size: 0.8rem; font-weight: 400; color: var(--text-muted); margin-left: auto;">
                        {{ $gatesCount ?? 0 }} entries
                    </span>
                </div>
                <div class="tag-container">
                    @if($gates && count($gates) > 0)
                        @foreach($gates as $gate)
                            @if($gate && ($gate['name'] ?? ''))
                            <span class="tag purple">
                                <i class="fas fa-door-open"></i>
                                {{ $gate['name'] ?? 'Unnamed Entry' }}
                                @if($gate['number'] ?? '')
                                <span style="font-size: 0.7rem; color: var(--text-muted);">({{ $gate['number'] }})</span>
                                @endif
                            </span>
                            @endif
                        @endforeach
                    @else
                        <span class="empty-text">No entries or exits added.</span>
                    @endif
                </div>
            </div>

            <!-- Floor Amenities -->
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-concierge-bell"></i> Floor Amenities
                </div>
                <div class="tag-container">
                    @php
                        $amenityIcons = [
                            'has_ac' => 'fa-snowflake',
                            'has_water_facility' => 'fa-tint',
                            'has_fire_extinguisher' => 'fa-fire-extinguisher',
                            'has_lift' => 'fa-elevator',
                            'has_washroom' => 'fa-toilet',
                            'has_wifi' => 'fa-wifi',
                            'has_fire_alarm' => 'fa-bell',
                            'has_library' => 'fa-book',
                            'has_staff_room' => 'fa-users',
                            'has_conference_room' => 'fa-users',
                            'has_common_room' => 'fa-users',
                            'has_projector_room' => 'fa-video',
                            'has_disabled_access' => 'fa-wheelchair'
                        ];
                        $amenityLabels = [
                            'has_ac' => 'AC',
                            'has_water_facility' => 'Water Facility',
                            'has_fire_extinguisher' => 'Fire Extinguisher',
                            'has_lift' => 'Lift',
                            'has_washroom' => 'Washroom',
                            'has_wifi' => 'Wi-Fi',
                            'has_fire_alarm' => 'Fire Alarm',
                            'has_library' => 'Library',
                            'has_staff_room' => 'Staff Room',
                            'has_conference_room' => 'Conference Room',
                            'has_common_room' => 'Common Room',
                            'has_projector_room' => 'Projector Room',
                            'has_disabled_access' => 'Disabled Access'
                        ];
                    @endphp

                    @foreach($amenityLabels as $key => $label)
                        @php
                            $value = $floor->$key ?? false;
                        @endphp
                        @if($value)
                            <span class="tag teal">
                                <i class="fas {{ $amenityIcons[$key] ?? 'fa-check' }}"></i>
                                {{ $label }}
                            </span>
                        @endif
                    @endforeach

                    @php
                        $hasAnyAmenity = false;
                        foreach($amenityLabels as $key => $label) {
                            if ($floor->$key ?? false) { $hasAnyAmenity = true; break; }
                        }
                    @endphp
                    @if(!$hasAnyAmenity)
                        <span class="empty-text">No amenities configured for this floor.</span>
                    @endif
                </div>
            </div>

            <!-- Allocated Amenities (from Block) -->
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-cubes"></i> Allocated Amenities (from Block)
                    <span style="font-size: 0.8rem; font-weight: 400; color: var(--text-muted); margin-left: auto;">
                        {{ $amenitiesCount ?? 0 }} amenities
                    </span>
                </div>
                <div class="tag-container">
                    @if($allocatedAmenities && count($allocatedAmenities) > 0)
                        @foreach($allocatedAmenities as $key => $value)
                            @php
                                $displayName = ucfirst(str_replace('_', ' ', $key));
                                $qty = is_array($value) ? ($value['quantity'] ?? $value) : $value;
                            @endphp
                            <span class="amenity-tag">
                                <i class="fas fa-cube"></i>
                                {{ $displayName }}
                                @if($qty > 1)
                                    <span class="count">{{ $qty }}</span>
                                @endif
                            </span>
                        @endforeach
                    @else
                        <span class="empty-text">No amenities allocated from block.</span>
                    @endif
                </div>
            </div>

            <!-- Allocated Facilities (from Block) -->
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-building"></i> Allocated Facilities (from Block)
                    <span style="font-size: 0.8rem; font-weight: 400; color: var(--text-muted); margin-left: auto;">
                        {{ $facilitiesCount ?? 0 }} facilities
                    </span>
                </div>
                <div class="tag-container">
                    @if($allocatedFacilities && count($allocatedFacilities) > 0)
                        @foreach($allocatedFacilities as $facility)
                            @php
                                $facilityName = is_array($facility) ? ($facility['name'] ?? $facility['id'] ?? 'Facility') : $facility;
                            @endphp
                            <span class="tag orange">
                                <i class="fas fa-building"></i>
                                {{ ucfirst(str_replace('_', ' ', $facilityName)) }}
                            </span>
                        @endforeach
                    @else
                        <span class="empty-text">No facilities allocated from block.</span>
                    @endif
                </div>
            </div>

            <!-- Custom Amenities -->
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-plus-circle" style="color: #10b981;"></i> Custom Amenities
                    <span style="font-size: 0.8rem; font-weight: 400; color: var(--text-muted); margin-left: auto;">
                        {{ $customAmenitiesCount ?? 0 }} custom amenities
                    </span>
                </div>
                <div class="tag-container">
                    @if($customAmenities && count($customAmenities) > 0)
                        @foreach($customAmenities as $amenity)
                            @if($amenity && ($amenity['name'] ?? ''))
                            <span class="tag pink">
                                <i class="fas fa-plus-circle"></i>
                                {{ $amenity['name'] ?? 'Unnamed' }}
                                @if($amenity['quantity'] ?? 0 > 0)
                                    <span class="count" style="background: rgba(219, 39, 119, 0.15); padding: 1px 8px; border-radius: 12px; font-size: 0.7rem; margin-left: 4px;">
                                        ×{{ $amenity['quantity'] }}
                                    </span>
                                @endif
                            </span>
                            @endif
                        @endforeach
                    @else
                        <span class="empty-text">No custom amenities added.</span>
                    @endif
                </div>
            </div>

            <!-- Statistics -->
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-chart-bar"></i> Room Statistics
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
                    <div style="background: var(--bg-light); padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border-color);">
                        <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">Total Rooms</div>
                        <div style="font-size: 1.2rem; font-weight: 700; color: var(--text-dark);">{{ $floor->total_rooms ?? 0 }}</div>
                    </div>
                    <div style="background: var(--bg-light); padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border-color);">
                        <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">Available Rooms</div>
                        <div style="font-size: 1.2rem; font-weight: 700; color: var(--success-color);">{{ $floor->available_rooms ?? 0 }}</div>
                    </div>
                    <div style="background: var(--bg-light); padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border-color);">
                        <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">Occupied Rooms</div>
                        <div style="font-size: 1.2rem; font-weight: 700; color: var(--warning-color);">{{ $floor->occupied_rooms ?? 0 }}</div>
                    </div>
                    <div style="background: var(--bg-light); padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border-color);">
                        <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600;">Total Capacity</div>
                        <div style="font-size: 1.2rem; font-weight: 700; color: var(--text-dark);">{{ $floor->total_capacity ?? 0 }}</div>
                    </div>
                </div>
                @if(($floor->total_rooms ?? 0) > 0)
                <div style="margin-top: 0.75rem; padding: 0.5rem 1rem; background: #f0f4ff; border-radius: 8px; border: 1px solid rgba(67, 97, 238, 0.2);">
                    <span style="font-size: 0.85rem; color: var(--text-dark);">
                        <strong>Occupancy Rate:</strong> 
                        {{ $floor->total_rooms > 0 ? round((($floor->occupied_rooms ?? 0) / $floor->total_rooms) * 100, 2) : 0 }}%
                    </span>
                </div>
                @endif
            </div>
        </div>
        @else
        <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 16px; border: 2px solid var(--border-color);">
            <i class="fas fa-exclamation-circle" style="font-size: 4rem; color: var(--danger-color); display: block; margin-bottom: 1rem;"></i>
            <h3 style="color: var(--text-dark);">Floor Not Found</h3>
            <p style="color: var(--text-muted);">The floor you are looking for does not exist or has been deleted.</p>
            <a href="{{ route('floors.list') }}" class="btn btn-primary" style="margin-top: 1rem;">
                <i class="fas fa-arrow-left"></i> Back to Floors
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

        async function deleteFloor(id, name) {
            if (!confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
                return;
            }

            try {
                const response = await fetch(`${API_BASE_URL}/floors/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast(`Floor "${name}" deleted successfully!`);
                    setTimeout(() => {
                        window.location.href = '{{ route("floors.list") }}';
                    }, 1500);
                } else {
                    showToast(result.message || 'Failed to delete floor', 'error');
                }
            } catch (error) {
                console.error('Error deleting floor:', error);
                showToast('Failed to delete floor', 'error');
            }
        }
    </script>
</body>
</html>
@endsection