@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Building - {{ $building->name ?? 'Building' }}</title>
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

        .btn-success {
            background: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
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
        .building-detail {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Building Header Card */
        .building-header-card {
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

        .building-icon-large {
            width: 120px;
            height: 120px;
            border-radius: 16px;
            overflow: hidden;
            flex-shrink: 0;
            border: 2px solid var(--border-color);
            background: var(--bg-light);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .building-icon-large img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .building-icon-large i {
            font-size: 3.5rem;
            color: var(--text-muted);
        }

        .building-header-info {
            flex: 1;
        }

        .building-header-info .building-name {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0 0 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .building-header-info .building-code {
            font-family: 'Courier New', monospace;
            background: rgba(67, 97, 238, 0.08);
            padding: 4px 14px;
            border-radius: 8px;
            font-size: 0.9rem;
            color: var(--primary-color);
            font-weight: 600;
        }

        .building-header-info .building-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 0.5rem;
        }

        .building-header-info .building-meta .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .building-header-info .building-meta .meta-item i {
            color: var(--primary-color);
            width: 18px;
        }

        .building-header-info .building-status {
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

        .building-header-actions {
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
        .info-card .card-icon.yellow { background: #fefce8; color: #ca8a04; }

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

        .detail-section .section-title .badge-count {
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

        .tag.red {
            background: #fef2f2;
            border-color: #fca5a5;
            color: #991b1b;
        }

        .tag.red i { color: #ef4444; }

        .tag.yellow {
            background: #fefce8;
            border-color: #fde68a;
            color: #854d0e;
        }

        .tag.yellow i { color: #ca8a04; }

        .tag.pink {
            background: #fdf2f8;
            border-color: #f9a8d4;
            color: #9d174d;
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

        .amenity-tag .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 4px;
        }

        .amenity-tag .status-dot.working { background: var(--success-color); }
        .amenity-tag .status-dot.repair { background: var(--warning-color); }
        .amenity-tag .status-dot.damaged { background: var(--danger-color); }
        .amenity-tag .status-dot.maintenance { background: var(--info-color); }

        .empty-text {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        /* Washroom Section Styles */
        .washroom-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }

        .washroom-card {
            background: var(--bg-light);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1rem;
            transition: all 0.3s;
        }

        .washroom-card:hover {
            border-color: var(--primary-color);
        }

        .washroom-card .washroom-type {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 2px 12px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .washroom-card .washroom-type.male {
            background: #dbeafe;
            color: #1e40af;
        }

        .washroom-card .washroom-type.female {
            background: #fce7f3;
            color: #9d174d;
        }

        .washroom-card .washroom-type.unisex {
            background: #dcfce7;
            color: #166534;
        }

        .washroom-card .washroom-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }

        .washroom-card .washroom-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 0.5rem;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .washroom-card .washroom-details .detail-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .washroom-card .washroom-details .detail-item i {
            color: var(--primary-color);
            font-size: 0.7rem;
        }

        .washroom-card .washroom-details .detail-item .value {
            font-weight: 600;
            color: var(--text-dark);
        }

        .washroom-card .washroom-area {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.5rem;
            padding-top: 0.5rem;
            border-top: 1px dashed var(--border-color);
        }

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

            .building-header-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 1.5rem;
            }

            .building-icon-large {
                width: 100px;
                height: 100px;
            }

            .building-icon-large i {
                font-size: 2.5rem;
            }

            .building-header-info .building-name {
                font-size: 1.5rem;
                justify-content: center;
            }

            .building-header-info .building-meta {
                justify-content: center;
            }

            .building-header-actions {
                justify-content: center;
            }

            .info-grid {
                grid-template-columns: 1fr 1fr;
            }

            .detail-section {
                padding: 1rem;
            }

            .washroom-grid {
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
                <h1><i class="fas fa-building"></i> Building Details</h1>
                <p class="subtitle"><i class="fas fa-info-circle"></i> Complete overview of the building</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('buildings.list') }}" class="btn btn-white">
                    <i class="fas fa-arrow-left"></i> Back to Buildings
                </a>
                <a href="{{ route('buildings.edit', $building->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Building
                </a>
            </div>
        </div>

        @if($building)
        <!-- Building Detail -->
        <div class="building-detail">
            <!-- Building Header -->
            <div class="building-header-card">
                <div class="building-icon-large">
                    @if($building->photo_url)
                        <img src="{{ $building->photo_url }}" alt="{{ $building->name }}">
                    @else
                        <i class="fas fa-building"></i>
                    @endif
                </div>
                <div class="building-header-info">
                    <div class="building-name">
                        {{ $building->name }}
                        <span class="building-code">{{ $building->code ?? 'No Code' }}</span>
                    </div>
                    <div class="building-meta">
                        <span class="meta-item">
                            <i class="fas fa-map-marker-alt"></i> 
                            {{ $building->address ?? 'Address not specified' }}
                        </span>
                        @if($building->year_established)
                        <span class="meta-item">
                            <i class="fas fa-calendar-alt"></i> 
                            Established: {{ $building->year_established }}
                        </span>
                        @endif
                        <span class="meta-item">
                            <i class="fas fa-vector-square"></i> 
                            Area: {{ ($building->area_value ?? 0) > 0 ? $building->area_value . ' ' . ($building->area_unit ?? 'sq_ft') : 'Not specified' }}
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-cubes"></i> 
                            Blocks: {{ $building->number_of_blocks ?? 0 }}
                        </span>
                    </div>
                    <div>
                        <span class="building-status status-{{ $building->status ?? 'active' }}">
                            <i class="fas {{ $building->status === 'active' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ ucfirst(str_replace('_', ' ', $building->status ?? 'Active')) }}
                        </span>
                    </div>
                    <div class="building-header-actions">
                        <a href="{{ route('buildings.edit', $building->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Building
                        </a>
                        <button onclick="deleteBuilding({{ $building->id }}, '{{ addslashes($building->name) }}')" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Building
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="info-grid">
                <div class="info-card">
                    <div class="card-icon blue"><i class="fas fa-cubes"></i></div>
                    <div class="card-label">Total Blocks</div>
                    <div class="card-value">{{ $building->number_of_blocks ?? 0 }}</div>
                </div>
                <div class="info-card">
                    <div class="card-icon green"><i class="fas fa-layer-group"></i></div>
                    <div class="card-label">Total Floors</div>
                    <div class="card-value">{{ $totalFloors ?? 0 }}</div>
                </div>
                <div class="info-card">
                    <div class="card-icon orange"><i class="fas fa-door-open"></i></div>
                    <div class="card-label">Total Rooms</div>
                    <div class="card-value">{{ $totalRooms ?? 0 }}</div>
                </div>
                <div class="info-card">
                    <div class="card-icon purple"><i class="fas fa-concierge-bell"></i></div>
                    <div class="card-label">Amenities</div>
                    <div class="card-value">{{ $totalAmenities ?? 0 }}</div>
                </div>
                <div class="info-card">
                    <div class="card-icon teal"><i class="fas fa-restroom"></i></div>
                    <div class="card-label">Washrooms</div>
                    <div class="card-value">{{ $washroomCount ?? 0 }}</div>
                </div>
                <div class="info-card">
                    <div class="card-icon pink"><i class="fas fa-map"></i></div>
                    <div class="card-label">Additional Areas</div>
                    <div class="card-value">{{ $areasCount ?? 0 }}</div>
                </div>
            </div>

            <!-- Description -->
            @if($building->description)
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-align-left"></i> Description
                </div>
                <p style="color: var(--text-dark); font-size: 0.95rem; line-height: 1.6; margin: 0;">
                    {{ $building->description }}
                </p>
            </div>
            @endif

            <!-- Additional Areas -->
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-map"></i> Additional Areas
                    <span class="badge-count">{{ $areasCount ?? 0 }} areas</span>
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

            <!-- Gates -->
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-door-open"></i> Gates / Entrances
                    <span class="badge-count">{{ $gatesCount ?? 0 }} gates</span>
                </div>
                <div class="tag-container">
                    @if($gates && count($gates) > 0)
                        @foreach($gates as $gate)
                            @if($gate && ($gate['name'] ?? ''))
                            <span class="tag purple">
                                <i class="fas fa-door-open"></i>
                                {{ $gate['name'] ?? 'Unnamed Gate' }}
                                @if($gate['number'] ?? '')
                                <span style="font-size: 0.7rem; color: var(--text-muted);">({{ $gate['number'] }})</span>
                                @endif
                            </span>
                            @endif
                        @endforeach
                    @else
                        <span class="empty-text">No gates or entrances added.</span>
                    @endif
                </div>
            </div>

            <!-- ====== WASHROOMS SECTION ====== -->
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-restroom"></i> Washrooms
                    <span class="badge-count">{{ $washroomCount ?? 0 }} washrooms</span>
                </div>
                @if($washrooms && count($washrooms) > 0)
                    <div class="washroom-grid">
                        @foreach($washrooms as $washroom)
                            @if($washroom && ($washroom['name'] ?? ''))
                            <div class="washroom-card">
                                <span class="washroom-type {{ $washroom['type'] ?? 'male' }}">
                                    <i class="fas {{ ($washroom['type'] ?? 'male') === 'male' ? 'fa-mars' : (($washroom['type'] ?? 'male') === 'female' ? 'fa-venus' : 'fa-venus-mars') }}"></i>
                                    {{ ucfirst($washroom['type'] ?? 'Male') }}
                                </span>
                                <div class="washroom-name">{{ $washroom['name'] ?? 'Unnamed Washroom' }}</div>
                                <div class="washroom-details">
                                    <span class="detail-item">
                                        <i class="fas fa-toilet"></i>
                                        Toilets: <span class="value">{{ $washroom['toilets'] ?? 0 }}</span>
                                    </span>
                                    @if(($washroom['type'] ?? 'male') === 'male' || ($washroom['type'] ?? 'male') === 'unisex')
                                    <span class="detail-item">
                                        <i class="fas fa-toilet"></i>
                                        Urinals: <span class="value">{{ $washroom['urinals'] ?? 0 }}</span>
                                    </span>
                                    @endif
                                    <span class="detail-item">
                                        <i class="fas fa-sink"></i>
                                        Washbasins: <span class="value">{{ $washroom['washbasins'] ?? 0 }}</span>
                                    </span>
                                </div>
                                @if(($washroom['area'] ?? 0) > 0 || ($washroom['capacity'] ?? 0) > 0)
                                <div class="washroom-area">
                                    @if($washroom['area'] ?? 0)
                                    <span>Area: {{ $washroom['area'] }} sq. ft.</span>
                                    @endif
                                    @if($washroom['capacity'] ?? 0)
                                    <span style="margin-left: 10px;">Capacity: {{ $washroom['capacity'] }} persons</span>
                                    @endif
                                </div>
                                @endif
                            </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <span class="empty-text">No washrooms configured.</span>
                @endif
            </div>

            <!-- Amenities with Status -->
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-concierge-bell"></i> Amenities
                    <span class="badge-count">{{ $totalAmenities ?? 0 }} amenities</span>
                </div>
                <div class="tag-container">
                    @if($amenities && count($amenities) > 0)
                        @foreach($amenities as $key => $value)
                            @if(is_array($value) && ($value['enabled'] ?? false))
                                @php
                                    $displayName = ucfirst(str_replace('_', ' ', $key));
                                    $status = $value['status'] ?? 'working';
                                    $count = $value['count'] ?? 1;
                                    $statusLabel = ucfirst($status);
                                @endphp
                                <span class="amenity-tag">
                                    <span class="status-dot {{ $status }}"></span>
                                    {{ $displayName }}
                                    @if($count > 1)
                                        <span class="count">×{{ $count }}</span>
                                    @endif
                                    <span style="font-size: 0.6rem; color: var(--text-muted); margin-left: 4px;">
                                        ({{ $statusLabel }})
                                    </span>
                                </span>
                            @endif
                        @endforeach
                    @else
                        <span class="empty-text">No amenities configured.</span>
                    @endif
                </div>
            </div>

            <!-- Custom Amenities -->
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-plus-circle" style="color: #10b981;"></i> Custom Amenities
                    <span class="badge-count">{{ $customAmenitiesCount ?? 0 }} custom amenities</span>
                </div>
                <div class="tag-container">
                    @if($customAmenities && count($customAmenities) > 0)
                        @foreach($customAmenities as $amenity)
                            @if($amenity && ($amenity['name'] ?? ''))
                            <span class="tag orange">
                                <i class="fas fa-plus-circle"></i>
                                {{ $amenity['name'] ?? 'Unnamed' }}
                                @if($amenity['quantity'] ?? 0 > 0)
                                    <span style="background: rgba(245, 158, 11, 0.15); padding: 1px 8px; border-radius: 12px; font-size: 0.7rem; margin-left: 4px;">
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

            <!-- Facilities with Status -->
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-building"></i> Facilities
                    <span class="badge-count">{{ $facilitiesCount ?? 0 }} facilities</span>
                </div>
                <div class="tag-container">
                    @php
                        $facilityLabels = [
                            'parking' => ['label' => 'Parking', 'icon' => 'fa-parking'],
                            'playground' => ['label' => 'Playground', 'icon' => 'fa-futbol'],
                            'swimming_pool' => ['label' => 'Swimming Pool', 'icon' => 'fa-swimming-pool'],
                            'clubhouse' => ['label' => 'Clubhouse', 'icon' => 'fa-home'],
                            'warehouse' => ['label' => 'Warehouse', 'icon' => 'fa-warehouse'],
                            'store_room' => ['label' => 'Store Room', 'icon' => 'fa-boxes'],
                            'auditorium' => ['label' => 'Auditorium', 'icon' => 'fa-theater-masks']
                        ];
                    @endphp
                    @if($facilities && count($facilities) > 0)
                        @foreach($facilities as $key => $value)
                            @if(is_array($value) && ($value['enabled'] ?? false))
                                @php
                                    $label = $facilityLabels[$key]['label'] ?? ucfirst(str_replace('_', ' ', $key));
                                    $icon = $facilityLabels[$key]['icon'] ?? 'fa-building';
                                    $status = $value['status'] ?? 'working';
                                    $count = $value['count'] ?? 1;
                                    $statusLabel = ucfirst($status);
                                    $colorClass = $status === 'working' ? 'green' : ($status === 'repair' ? 'yellow' : ($status === 'damaged' ? 'red' : 'purple'));
                                @endphp
                                <span class="tag {{ $colorClass }}">
                                    <i class="fas {{ $icon }}"></i>
                                    {{ $label }}
                                    @if($count > 1)
                                        <span style="background: rgba(0,0,0,0.05); padding: 1px 8px; border-radius: 12px; font-size: 0.7rem; margin-left: 4px;">
                                            ×{{ $count }}
                                        </span>
                                    @endif
                                    <span style="font-size: 0.6rem; color: var(--text-muted); margin-left: 4px;">
                                        ({{ $statusLabel }})
                                    </span>
                                </span>
                            @endif
                        @endforeach
                    @else
                        <span class="empty-text">No facilities configured.</span>
                    @endif
                </div>
            </div>

            <!-- Blocks Overview -->
            @if(isset($blocks) && count($blocks) > 0)
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-cubes"></i> Blocks in this Building
                    <span class="badge-count">{{ count($blocks) }} blocks</span>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem;">
                    @foreach($blocks as $block)
                       
                            <div style="background: var(--bg-light); border: 1px solid var(--border-color); border-radius: 12px; padding: 1rem; transition: all 0.3s; cursor: pointer;">
                                <div style="font-weight: 600; color: var(--text-dark); display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-building" style="color: var(--primary-color);"></i>
                                    {{ $block->name }}
                                </div>
                                <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">
                                    <span>{{ $block->total_floors ?? 0 }} floors</span>
                                    <span style="margin-left: 12px;">{{ $block->total_rooms ?? 0 }} rooms</span>
                                    @if($block->status)
                                    <span style="margin-left: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                        <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: {{ $block->status === 'active' ? '#10b981' : '#ef4444' }};"></span>
                                        {{ ucfirst($block->status) }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @else
        <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 16px; border: 2px solid var(--border-color);">
            <i class="fas fa-exclamation-circle" style="font-size: 4rem; color: var(--danger-color); display: block; margin-bottom: 1rem;"></i>
            <h3 style="color: var(--text-dark);">Building Not Found</h3>
            <p style="color: var(--text-muted);">The building you are looking for does not exist or has been deleted.</p>
            <a href="{{ route('buildings.list') }}" class="btn btn-primary" style="margin-top: 1rem;">
                <i class="fas fa-arrow-left"></i> Back to Buildings
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

        async function deleteBuilding(id, name) {
            if (!confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
                return;
            }

            try {
                const response = await fetch(`${API_BASE_URL}/buildings/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast(`Building "${name}" deleted successfully!`);
                    setTimeout(() => {
                        window.location.href = '{{ route("buildings.list") }}';
                    }, 1500);
                } else {
                    showToast(result.message || 'Failed to delete building', 'error');
                }
            } catch (error) {
                console.error('Error deleting building:', error);
                showToast('Failed to delete building', 'error');
            }
        }
    </script>
</body>
</html>
@endsection