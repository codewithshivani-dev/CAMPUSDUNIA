@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Block Details - {{ $block->name ?? 'Block' }}</title>
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

        .block-detail {
            max-width: 1200px;
            margin: 0 auto;
        }

        .block-header-card {
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

        .block-icon-large {
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

        .block-header-info {
            flex: 1;
        }

        .block-header-info .block-name {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0 0 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .block-header-info .block-code {
            font-family: 'Courier New', monospace;
            background: rgba(67, 97, 238, 0.08);
            padding: 4px 14px;
            border-radius: 8px;
            font-size: 0.9rem;
            color: var(--primary-color);
            font-weight: 600;
        }

        .block-header-info .block-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 0.5rem;
        }

        .block-header-info .block-meta .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .block-header-info .block-meta .meta-item i {
            color: var(--primary-color);
            width: 18px;
        }

        .block-header-info .block-status {
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

        .block-header-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

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

        .tag.green { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
        .tag.green i { color: #10b981; }
        .tag.purple { background: #f5f3ff; border-color: #c4b5fd; color: #5b21b6; }
        .tag.purple i { color: #7c3aed; }
        .tag.orange { background: #fffbeb; border-color: #fde68a; color: #92400e; }
        .tag.orange i { color: #f59e0b; }
        .tag.teal { background: #ecfdf5; border-color: #6ee7b7; color: #065f46; }
        .tag.teal i { color: #0d9488; }
        .tag.blue { background: #eff6ff; border-color: #bfdbfe; color: #1e40af; }
        .tag.blue i { color: #2563eb; }

        /* Facility tag with type badge */
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

        .empty-text {
            color: var(--text-muted);
            font-size: 0.9rem;
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

        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.error { background: var(--danger-color); }

        @media (max-width: 768px) {
            .header { flex-direction: column; text-align: center; }
            .block-header-card { flex-direction: column; align-items: center; text-align: center; padding: 1.5rem; }
            .block-icon-large { width: 80px; height: 80px; font-size: 2.5rem; }
            .block-header-info .block-name { font-size: 1.5rem; justify-content: center; }
            .block-header-info .block-meta { justify-content: center; }
            .block-header-actions { justify-content: center; }
            .info-grid { grid-template-columns: 1fr 1fr; }
            .detail-section { padding: 1rem; }
        }

        @media (max-width: 480px) {
            .info-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="header">
            <div>
                <h1><i class="fas fa-building"></i> Block Details</h1>
                <p class="subtitle"><i class="fas fa-info-circle"></i> Complete overview of the building block</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('blocks.list') }}" class="btn btn-white">
                    <i class="fas fa-arrow-left"></i> Back to Blocks
                </a>
                <a href="{{ route('blocks.edit', $block->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Block
                </a>
            </div>
        </div>

        @if($block)
        <div class="block-detail">
            <!-- Block Header Card -->
            <div class="block-header-card">
                <div class="block-icon-large">
                    <i class="fas fa-building"></i>
                </div>
                <div class="block-header-info">
                    <div class="block-name">
                        {{ $block->name }}
                        <span class="block-code">{{ $block->code ?? 'No Code' }}</span>
                    </div>
                    <div class="block-meta">
                        <span class="meta-item">
                            <i class="fas fa-university"></i> 
                            Building: {{ $block->building->name ?? 'N/A' }}
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-vector-square"></i> 
                            Area: {{ ($block->total_area ?? 0) > 0 ? $block->total_area . ' ' . ($block->area_unit ?? 'sq_ft') : 'Not specified' }}
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-layer-group"></i> 
                            Floors: {{ $block->total_floors ?? 0 }}
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-door-open"></i> 
                            Rooms: {{ $block->total_rooms ?? 0 }}
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-users"></i> 
                            Capacity: {{ $block->total_capacity ?? 0 }}
                        </span>
                    </div>
                    <div>
                        <span class="block-status status-{{ $block->status ?? 'active' }}">
                            <i class="fas {{ $block->status === 'active' ? 'fa-check-circle' : ($block->status === 'inactive' ? 'fa-times-circle' : 'fa-exclamation-triangle') }}"></i>
                            {{ ucfirst(str_replace('_', ' ', $block->status ?? 'Active')) }}
                        </span>
                    </div>
                    <div class="block-header-actions">
                        <a href="{{ route('blocks.edit', $block->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Block
                        </a>
                        <button onclick="deleteBlock({{ $block->id }}, '{{ addslashes($block->name) }}')" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Block
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="info-grid">
                <div class="info-card">
                    <div class="card-icon blue"><i class="fas fa-layer-group"></i></div>
                    <div class="card-label">Total Floors</div>
                    <div class="card-value">{{ $block->total_floors ?? 0 }}</div>
                </div>
                <div class="info-card">
                    <div class="card-icon green"><i class="fas fa-door-open"></i></div>
                    <div class="card-label">Total Rooms</div>
                    <div class="card-value">{{ $block->total_rooms ?? 0 }}</div>
                </div>
                <div class="info-card">
                    <div class="card-icon orange"><i class="fas fa-users"></i></div>
                    <div class="card-label">Total Capacity</div>
                    <div class="card-value">{{ $block->total_capacity ?? 0 }}</div>
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
                    <div class="card-icon pink"><i class="fas fa-building"></i></div>
                    <div class="card-label">Facilities</div>
                    <div class="card-value">{{ $facilitiesCount ?? 0 }}</div>
                </div>
            </div>

            <!-- Description -->
            @if($block->description)
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-align-left"></i> Description
                </div>
                <p style="color: var(--text-dark); font-size: 0.95rem; line-height: 1.6; margin: 0;">
                    {{ $block->description }}
                </p>
            </div>
            @endif

            <!-- Allocated Facilities (FULL NAMES NOW) -->
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-building"></i> Allocated Facilities
                    <span style="font-size: 0.8rem; font-weight: 400; color: var(--text-muted); margin-left: auto;">
                        {{ $facilitiesCount }} {{ $facilitiesCount === 1 ? 'facility' : 'facilities' }}
                    </span>
                </div>
                <div class="tag-container">
                    @if(!empty($resolvedFacilities) && count($resolvedFacilities) > 0)
                        @foreach($resolvedFacilities as $facility)
                            <span class="facility-tag">
                                <i class="fas {{ $facility['icon'] ?? 'fa-building' }}"></i>
                                {{ $facility['name'] }}
                                @if(!empty($facility['type_label']) && $facility['type_label'] !== 'Unknown')
                                    <span class="facility-type">{{ $facility['type_label'] }}</span>
                                @endif
                            </span>
                        @endforeach
                    @else
                        <span class="empty-text">
                            <i class="fas fa-info-circle"></i>
                            No facilities have been allocated to this block.
                        </span>
                    @endif
                </div>
            </div>

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

            <!-- Gates -->
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-door-open"></i> Gates / Entrances
                    <span style="font-size: 0.8rem; font-weight: 400; color: var(--text-muted); margin-left: auto;">
                        {{ $gatesCount ?? 0 }} gates
                    </span>
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

            <!-- Allocated Amenities -->
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-concierge-bell"></i> Allocated Amenities
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
                                @if(is_numeric($qty) && $qty > 1)
                                    <span class="count">×{{ $qty }}</span>
                                @endif
                            </span>
                        @endforeach
                    @else
                        <span class="empty-text">No amenities allocated.</span>
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
                            <span class="tag orange">
                                <i class="fas fa-plus-circle"></i>
                                {{ $amenity['name'] ?? 'Unnamed' }}
                                @if(($amenity['quantity'] ?? 0) > 0)
                                    <span class="count" style="background: rgba(245, 158, 11, 0.15); padding: 1px 8px; border-radius: 12px; font-size: 0.7rem; margin-left: 4px;">
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

            <!-- Features -->
            <div class="detail-section">
                <div class="section-title">
                    <i class="fas fa-cogs"></i> Block Features
                </div>
                <div class="tag-container">
                    @if($hasLift)
                        <span class="tag purple"><i class="fas fa-elevator"></i> Lift/Elevator</span>
                    @endif
                    @if($hasFireSafety)
                        <span class="tag orange"><i class="fas fa-fire-extinguisher"></i> Fire Safety</span>
                    @endif
                    @if($hasDisabledAccess)
                        <span class="tag blue"><i class="fas fa-wheelchair"></i> Disabled Access</span>
                    @endif
                    @if($hasSecuritySystem)
                        <span class="tag teal"><i class="fas fa-shield-alt"></i> Security System</span>
                    @endif
                    @if(!$hasLift && !$hasFireSafety && !$hasDisabledAccess && !$hasSecuritySystem)
                        <span class="empty-text">No features configured.</span>
                    @endif
                </div>
            </div>
        </div>
        @else
        <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 16px; border: 2px solid var(--border-color);">
            <i class="fas fa-exclamation-circle" style="font-size: 4rem; color: var(--danger-color); display: block; margin-bottom: 1rem;"></i>
            <h3 style="color: var(--text-dark);">Block Not Found</h3>
            <p style="color: var(--text-muted);">The block you are looking for does not exist or has been deleted.</p>
            <a href="{{ route('blocks.list') }}" class="btn btn-primary" style="margin-top: 1rem;">
                <i class="fas fa-arrow-left"></i> Back to Blocks
            </a>
        </div>
        @endif

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
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        async function deleteBlock(id, name) {
            if (!confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
                return;
            }

            try {
                const response = await fetch(`${API_BASE_URL}/blocks/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    showToast(`Block "${name}" deleted successfully!`);
                    setTimeout(() => {
                        window.location.href = '{{ route("blocks.list") }}';
                    }, 1500);
                } else {
                    showToast(result.message || 'Failed to delete block', 'error');
                }
            } catch (error) {
                console.error('Error deleting block:', error);
                showToast('Failed to delete block', 'error');
            }
        }
    </script>
</body>
</html>
@endsection