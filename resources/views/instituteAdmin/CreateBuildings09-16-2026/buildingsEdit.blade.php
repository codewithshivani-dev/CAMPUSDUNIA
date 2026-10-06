@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Building - {{ $building->name ?? 'Building' }}</title>
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

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
            border: 2px solid var(--border-color);
            max-width: 1200px;
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

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .form-group .required {
            color: var(--danger-color);
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
            background: #f8fafc;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
            background: white;
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 4px;
            font-size: 0.8rem;
            color: var(--danger-color);
        }

        .invalid-feedback.show {
            display: block;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1.5rem;
        }

        .form-row-4 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 1.5rem;
        }

        .building-image-preview {
            width: 150px;
            height: 150px;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid var(--border-color);
            background: var(--bg-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        .building-image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--border-color);
            flex-wrap: wrap;
        }

        /* Amenity Status Styles */
        .amenity-status-group {
            display: flex;
            gap: 15px;
            margin-top: 8px;
            flex-wrap: wrap;
        }

        .amenity-status-option {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .amenity-status-option input[type="radio"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary-color);
            cursor: pointer;
        }

        .amenity-status-option label {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-dark);
            cursor: pointer;
            margin-bottom: 0;
        }

        .amenity-status-option .status-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 4px;
        }

        .status-dot.working { background: var(--success-color); }
        .status-dot.repair { background: var(--warning-color); }
        .status-dot.damaged { background: var(--danger-color); }
        .status-dot.maintenance { background: var(--info-color); }

        .amenity-item {
            background: var(--bg-light);
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            transition: all 0.3s;
        }

        .amenity-item:hover {
            border-color: var(--primary-color);
        }

        .amenity-item .amenity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .amenity-item .amenity-header .amenity-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .amenity-item .amenity-header .amenity-icon {
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .amenity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }

        .facility-item {
            background: var(--bg-light);
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            transition: all 0.3s;
        }

        .facility-item:hover {
            border-color: var(--primary-color);
        }

        .facility-item .facility-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .facility-item .facility-header .facility-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .facility-item .facility-header .facility-name i {
            color: var(--primary-color);
        }

        .facility-item .facility-controls {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .dynamic-card {
            background: var(--bg-light);
            border: 2px solid var(--border-color);
            border-radius: 14px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .dynamic-card:hover {
            border-color: var(--primary-color);
        }

        .dynamic-card .card-badge {
            position: absolute;
            top: -12px;
            left: 16px;
            background: var(--primary-gradient);
            color: white;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .dynamic-card .card-row {
            display: grid;
            gap: 0.75rem;
        }

        .dynamic-card .card-row-2 {
            grid-template-columns: 1fr 1fr;
        }

        .dynamic-card .card-row-3 {
            grid-template-columns: 1fr 1fr 1fr;
        }

        .dynamic-card .card-row-4 {
            grid-template-columns: 1fr 1fr 1fr 1fr;
        }

        .dynamic-card .form-group {
            margin-bottom: 0;
        }

        .dynamic-card .form-group label {
            font-size: 0.8rem;
            margin-bottom: 0.3rem;
        }

        .dynamic-card .form-group input,
        .dynamic-card .form-group select {
            padding: 0.6rem 0.9rem;
            font-size: 0.85rem;
        }

        .btn-sm {
            padding: 0.3rem 0.8rem;
            font-size: 0.75rem;
            border-radius: 6px;
        }

        .btn-outline-danger {
            background: white;
            color: var(--danger-color);
            border: 2px solid var(--danger-color);
        }

        .btn-outline-danger:hover {
            background: var(--danger-color);
            color: white;
        }

        .add-btn-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1rem;
            gap: 0.5rem;
        }

        .btn-outline-primary {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-outline-primary:hover {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-outline-success {
            background: white;
            color: var(--success-color);
            border: 2px solid var(--success-color);
        }

        .btn-outline-success:hover {
            background: var(--success-color);
            color: white;
        }

        .section-divider {
            border-top: 2px solid var(--border-color);
            margin: 1.5rem 0;
            padding-top: 1rem;
        }

        .section-divider h3 {
            color: var(--text-dark);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .section-divider h3 i {
            color: var(--primary-color);
        }

        /* Washroom Detail Card Styles */
        .washroom-detail-card {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            position: relative;
            transition: all 0.3s;
        }

        .washroom-detail-card:hover {
            border-color: var(--primary-color);
        }

        .washroom-detail-card .washroom-badge {
            position: absolute;
            top: -10px;
            left: 12px;
            background: var(--primary-gradient);
            color: white;
            padding: 2px 12px;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 600;
        }

        .washroom-detail-card .washroom-type-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .washroom-detail-card .washroom-type-badge.male {
            background: #dbeafe;
            color: #1e40af;
        }

        .washroom-detail-card .washroom-type-badge.female {
            background: #fce7f3;
            color: #9d174d;
        }

        .washroom-detail-card .washroom-type-badge.unisex {
            background: #dcfce7;
            color: #166534;
        }

        .washroom-detail-card .washroom-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .washroom-detail-card .washroom-row .form-group {
            margin-bottom: 0;
        }

        .washroom-detail-card .washroom-row .form-group label {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 0.2rem;
        }

        .washroom-detail-card .washroom-row .form-group input {
            padding: 4px 8px;
            font-size: 0.8rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            width: 100%;
        }

        .washroom-detail-card .washroom-row .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .washroom-detail-card .washroom-remove-btn {
            margin-top: 0.5rem;
            text-align: right;
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

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
            }

            .form-card {
                padding: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-row-3 {
                grid-template-columns: 1fr;
            }

            .form-row-4 {
                grid-template-columns: 1fr 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .form-actions .btn {
                width: 100%;
                justify-content: center;
            }

            .dynamic-card .card-row-2,
            .dynamic-card .card-row-3,
            .dynamic-card .card-row-4 {
                grid-template-columns: 1fr;
            }

            .amenity-grid {
                grid-template-columns: 1fr;
            }

            .facility-item .facility-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .facility-item .facility-controls {
                flex-direction: column;
                align-items: flex-start;
                width: 100%;
            }

            .washroom-detail-card .washroom-row {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 480px) {
            .form-row-4 {
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
                <h1><i class="fas fa-edit"></i> Edit Building</h1>
                <p class="subtitle"><i class="fas fa-info-circle"></i> Update building information and amenities</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('buildings.list') }}" class="btn btn-white">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <a href="/view-building/{{ $building->id }}" class="btn btn-primary">
                    <i class="fas fa-eye"></i> View
                </a>
            </div>
        </div>

        @if(isset($building) && $building)
        <!-- Edit Form -->
        <div class="form-card">
            <h2><i class="fas fa-building"></i> Edit Building: {{ $building->name }}</h2>

            <form id="editBuildingForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="section-divider">
                    <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Building Name <span class="required">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $building->name) }}" required>
                        <div class="invalid-feedback" id="name-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="code">Building Code <span class="required">*</span></label>
                        <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $building->code) }}" required>
                        <div class="invalid-feedback" id="code-error"></div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $building->address) }}">
                        <div class="invalid-feedback" id="address-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="year_established">Year Established</label>
                        <input type="number" class="form-control" id="year_established" name="year_established" value="{{ old('year_established', $building->year_established) }}" min="1900" max="{{ date('Y') + 1 }}">
                        <div class="invalid-feedback" id="year_established-error"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $building->description) }}</textarea>
                    <div class="invalid-feedback" id="description-error"></div>
                </div>

                <!-- Area Information -->
                <div class="section-divider">
                    <h3><i class="fas fa-vector-square"></i> Area & Configuration</h3>
                </div>

                <div class="form-row-3">
                    <div class="form-group">
                        <label for="area_value">Area Value</label>
                        <input type="number" class="form-control" id="area_value" name="area_value" value="{{ old('area_value', $building->area_value) }}" step="0.01" min="0">
                        <div class="invalid-feedback" id="area_value-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="area_unit">Area Unit</label>
                        <select class="form-control" id="area_unit" name="area_unit">
                            <option value="sq_ft" {{ $building->area_unit == 'sq_ft' ? 'selected' : '' }}>Sq. Ft.</option>
                            <option value="sq_m" {{ $building->area_unit == 'sq_m' ? 'selected' : '' }}>Sq. M.</option>
                            <option value="sq_yd" {{ $building->area_unit == 'sq_yd' ? 'selected' : '' }}>Sq. Yd.</option>
                            <option value="gaj" {{ $building->area_unit == 'gaj' ? 'selected' : '' }}>Gaj</option>
                            <option value="marla" {{ $building->area_unit == 'marla' ? 'selected' : '' }}>Marla</option>
                            <option value="kanal" {{ $building->area_unit == 'kanal' ? 'selected' : '' }}>Kanal</option>
                            <option value="acre" {{ $building->area_unit == 'acre' ? 'selected' : '' }}>Acre</option>
                            <option value="hectare" {{ $building->area_unit == 'hectare' ? 'selected' : '' }}>Hectare</option>
                        </select>
                        <div class="invalid-feedback" id="area_unit-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="number_of_blocks">Number of Blocks</label>
                        <input type="number" class="form-control" id="number_of_blocks" name="number_of_blocks" value="{{ old('number_of_blocks', $building->number_of_blocks ?? 1) }}" min="1">
                        <div class="invalid-feedback" id="number_of_blocks-error"></div>
                    </div>
                </div>

                <!-- Status & Image -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="active" {{ $building->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $building->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <div class="invalid-feedback" id="status-error"></div>
                    </div>
                    <div class="form-group">
                        <label>Building Image</label>
                        <div class="building-image-preview" id="imagePreview">
                            @if($building->photo_url)
                                <img src="{{ $building->photo_url }}" alt="{{ $building->name }}">
                            @else
                                <i class="fas fa-building"></i>
                            @endif
                        </div>
                        <input type="file" class="form-control" id="photo_file" name="photo_file" accept="image/*">
                        <small class="text-muted">Upload a new image (JPEG, PNG, GIF - max 5MB)</small>
                        <div class="invalid-feedback" id="photo_file-error"></div>
                        @if($building->photo)
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="remove_photo" name="remove_photo" value="1">
                                <label class="form-check-label" for="remove_photo">Remove current image</label>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Gates -->
                <div class="section-divider">
                    <h3><i class="fas fa-door-open"></i> Gates / Entrances</h3>
                </div>

                <div id="gates-container">
                    @if(isset($gates) && count($gates) > 0)
                        @foreach($gates as $index => $gate)
                            @if($gate && ($gate['name'] ?? ''))
                                <div class="dynamic-card" id="gate-{{ $index + 1 }}">
                                    <div class="card-badge">Gate #{{ $index + 1 }}</div>
                                    <div class="card-row card-row-2">
                                        <div class="form-group">
                                            <label>Gate Name</label>
                                            <input type="text" class="form-control gate-name" value="{{ $gate['name'] ?? '' }}" placeholder="e.g., Main Gate">
                                        </div>
                                        <div class="form-group">
                                            <label>Gate Number</label>
                                            <input type="text" class="form-control gate-number" value="{{ $gate['number'] ?? '' }}" placeholder="e.g., G-01">
                                        </div>
                                    </div>
                                    <div style="text-align:right;margin-top:0.5rem;">
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeGate(this)"><i class="fas fa-trash"></i> Remove</button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="dynamic-card" id="gate-1">
                            <div class="card-badge">Gate #1</div>
                            <div class="card-row card-row-2">
                                <div class="form-group">
                                    <label>Gate Name</label>
                                    <input type="text" class="form-control gate-name" placeholder="e.g., Main Gate">
                                </div>
                                <div class="form-group">
                                    <label>Gate Number</label>
                                    <input type="text" class="form-control gate-number" placeholder="e.g., G-01">
                                </div>
                            </div>
                            <div style="text-align:right;margin-top:0.5rem;">
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeGate(this)"><i class="fas fa-trash"></i> Remove</button>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="add-btn-row">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addGate()"><i class="fas fa-plus"></i> Add Gate</button>
                </div>

                <!-- Additional Areas -->
                <div class="section-divider">
                    <h3><i class="fas fa-map"></i> Additional Areas</h3>
                </div>

                <div id="additional-areas-container">
                    @if(isset($additionalAreas) && count($additionalAreas) > 0)
                        @foreach($additionalAreas as $index => $area)
                            @if($area && ($area['name'] ?? ''))
                                <div class="dynamic-card" id="area-{{ $index + 1 }}">
                                    <div class="card-badge">Area #{{ $index + 1 }}</div>
                                    <div class="card-row card-row-3">
                                        <div class="form-group">
                                            <label>Area Name</label>
                                            <input type="text" class="form-control area-name" value="{{ $area['name'] ?? '' }}" placeholder="e.g., Garden">
                                        </div>
                                        <div class="form-group">
                                            <label>Area Unit</label>
                                            <select class="form-control area-unit">
                                                <option value="sq_ft" {{ ($area['unit'] ?? 'sq_ft') == 'sq_ft' ? 'selected' : '' }}>Sq. Ft.</option>
                                                <option value="sq_m" {{ ($area['unit'] ?? '') == 'sq_m' ? 'selected' : '' }}>Sq. M.</option>
                                                <option value="sq_yd" {{ ($area['unit'] ?? '') == 'sq_yd' ? 'selected' : '' }}>Sq. Yd.</option>
                                                <option value="gaj" {{ ($area['unit'] ?? '') == 'gaj' ? 'selected' : '' }}>Gaj</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Area Value</label>
                                            <input type="number" class="form-control area-value" value="{{ $area['area'] ?? '' }}" placeholder="Enter area" min="0" step="0.01">
                                        </div>
                                    </div>
                                    <div style="text-align:right;margin-top:0.5rem;">
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeArea(this)"><i class="fas fa-trash"></i> Remove</button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="dynamic-card" id="area-1">
                            <div class="card-badge">Area #1</div>
                            <div class="card-row card-row-3">
                                <div class="form-group">
                                    <label>Area Name</label>
                                    <input type="text" class="form-control area-name" placeholder="e.g., Garden">
                                </div>
                                <div class="form-group">
                                    <label>Area Unit</label>
                                    <select class="form-control area-unit">
                                        <option value="sq_ft">Sq. Ft.</option>
                                        <option value="sq_m">Sq. M.</option>
                                        <option value="sq_yd">Sq. Yd.</option>
                                        <option value="gaj">Gaj</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Area Value</label>
                                    <input type="number" class="form-control area-value" placeholder="Enter area" min="0" step="0.01">
                                </div>
                            </div>
                            <div style="text-align:right;margin-top:0.5rem;">
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeArea(this)"><i class="fas fa-trash"></i> Remove</button>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="add-btn-row">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addArea()"><i class="fas fa-plus"></i> Add Area</button>
                </div>

                <!-- ====== WASHROOM SECTION ====== -->
                <div class="section-divider">
                    <h3><i class="fas fa-restroom"></i> Washrooms</h3>
                </div>

                <div class="facility-item">
                    <div class="facility-header">
                        <span class="facility-name">
                            <i class="fas fa-restroom"></i>
                            Washroom Configuration
                        </span>
                    </div>
                    <div class="facility-controls">
                        <div class="form-check" style="margin-bottom: 0;">
                            <input class="form-check-input" type="checkbox" id="has-washrooms" 
                                   onchange="toggleWashrooms(this)" 
                                   {{ isset($facilities['washrooms']) && is_array($facilities['washrooms']) && count($facilities['washrooms']) > 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="has-washrooms" style="font-size: 0.85rem; cursor: pointer;">
                                Enable Washrooms
                            </label>
                        </div>
                    </div>
                    <div id="washrooms-details" style="{{ isset($facilities['washrooms']) && is_array($facilities['washrooms']) && count($facilities['washrooms']) > 0 ? '' : 'display: none;' }} margin-top: 1rem;">
                        <div class="form-row-3">
                            <div class="form-group">
                                <label>Number of Male Washrooms</label>
                                <input type="number" id="male-washroom-count" class="form-control" min="0" value="{{ $maleWashroomCount ?? 0 }}" onchange="generateWashroomEntries()">
                            </div>
                            <div class="form-group">
                                <label>Number of Female Washrooms</label>
                                <input type="number" id="female-washroom-count" class="form-control" min="0" value="{{ $femaleWashroomCount ?? 0 }}" onchange="generateWashroomEntries()">
                            </div>
                            <div class="form-group">
                                <label>Number of Unisex Washrooms</label>
                                <input type="number" id="unisex-washroom-count" class="form-control" min="0" value="{{ $unisexWashroomCount ?? 0 }}" onchange="generateWashroomEntries()">
                            </div>
                        </div>
                        <div id="washrooms-container" style="margin-top: 1rem;"></div>
                        <div class="alert-info" style="font-size:0.8rem;margin-top:0.5rem; background: #f0f9ff; padding: 8px 14px; border-radius: 8px; border: 1px solid #7dd3fc;">
                            <i class="fas fa-info-circle"></i> For each washroom, specify the number of toilets, urinals (for male/unisex), and washbasins.
                        </div>
                    </div>
                </div>

                <!-- Predefined Amenities with Status -->
                <div class="section-divider">
                    <h3><i class="fas fa-concierge-bell"></i> Amenities with Status</h3>
                    <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: -0.5rem;">
                        Select amenities and their current status (Working, Under Repair, Damaged, Maintenance)
                    </p>
                </div>

                <div class="amenity-grid" id="amenitiesGrid">
                    @php
                        $amenityLabels = [
                            'wifi' => ['label' => 'Wi-Fi', 'icon' => 'fa-wifi'],
                            'lan' => ['label' => 'LAN', 'icon' => 'fa-network-wired'],
                            'internet_lab' => ['label' => 'Internet Lab', 'icon' => 'fa-laptop'],
                            'cctv' => ['label' => 'CCTV', 'icon' => 'fa-video'],
                            'fire_alarm' => ['label' => 'Fire Alarm', 'icon' => 'fa-bell'],
                            'security_guard' => ['label' => 'Security Guard', 'icon' => 'fa-shield-alt'],
                            'biometric' => ['label' => 'Biometric Access', 'icon' => 'fa-fingerprint'],
                            'elevator' => ['label' => 'Elevator/Lift', 'icon' => 'fa-elevator'],
                            'escalator' => ['label' => 'Escalator', 'icon' => 'fa-stairs'],
                            'ramp' => ['label' => 'Wheelchair Ramp', 'icon' => 'fa-wheelchair'],
                            'generator' => ['label' => 'Generator', 'icon' => 'fa-bolt'],
                            'ups' => ['label' => 'UPS System', 'icon' => 'fa-battery-three-quarters'],
                            'solar_panel' => ['label' => 'Solar Panels', 'icon' => 'fa-sun'],
                            'ac' => ['label' => 'Air Conditioning', 'icon' => 'fa-snowflake'],
                            'heater' => ['label' => 'Heater', 'icon' => 'fa-fire'],
                            'exhaust_fan' => ['label' => 'Exhaust Fan', 'icon' => 'fa-fan'],
                            'washroom' => ['label' => 'Washrooms', 'icon' => 'fa-restroom'],
                            'drinking_water' => ['label' => 'Drinking Water', 'icon' => 'fa-tint'],
                            'cafeteria' => ['label' => 'Cafeteria/Canteen', 'icon' => 'fa-utensils'],
                            'tuck_shop' => ['label' => 'Tuck Shop', 'icon' => 'fa-store'],
                            'library' => ['label' => 'Library', 'icon' => 'fa-book'],
                            'computer_lab' => ['label' => 'Computer Lab', 'icon' => 'fa-desktop'],
                            'science_lab' => ['label' => 'Science Lab', 'icon' => 'fa-flask'],
                            'seminar_hall' => ['label' => 'Seminar Hall', 'icon' => 'fa-users'],
                            'conference_room' => ['label' => 'Conference Room', 'icon' => 'fa-handshake'],
                            'gym' => ['label' => 'Gym/Fitness Center', 'icon' => 'fa-dumbbell'],
                            'indoor_games' => ['label' => 'Indoor Games Room', 'icon' => 'fa-gamepad'],
                            'medical_room' => ['label' => 'Medical Room', 'icon' => 'fa-notes-medical'],
                            'ambulance' => ['label' => 'Ambulance Service', 'icon' => 'fa-ambulance'],
                            'atm' => ['label' => 'ATM', 'icon' => 'fa-money-bill'],
                            'stationery_shop' => ['label' => 'Stationery Shop', 'icon' => 'fa-pen'],
                            'photocopy' => ['label' => 'Photocopy/Print', 'icon' => 'fa-print'],
                            'prayer_room' => ['label' => 'Prayer Room', 'icon' => 'fa-mosque'],
                            'daycare' => ['label' => 'Daycare/Creche', 'icon' => 'fa-baby'],
                            'guest_room' => ['label' => 'Guest Room', 'icon' => 'fa-hotel'],
                            'hostel' => ['label' => 'Hostel/Dormitory', 'icon' => 'fa-bed'],
                            'staff_room' => ['label' => 'Staff Room', 'icon' => 'fa-user-tie'],
                            'admin_office' => ['label' => 'Admin Office', 'icon' => 'fa-building-columns'],
                            'laundry' => ['label' => 'Laundry', 'icon' => 'fa-soap']
                        ];
                    @endphp

                    @foreach($amenityLabels as $key => $amenity)
                        @php
                            $enabled = false;
                            $status = 'working';
                            $count = 1;
                            if (isset($amenities[$key])) {
                                $value = $amenities[$key];
                                if (is_array($value)) {
                                    $enabled = isset($value['enabled']) && ($value['enabled'] === true || $value['enabled'] === 'true' || $value['enabled'] === 1 || $value['enabled'] === '1');
                                    $status = $value['status'] ?? 'working';
                                    $count = $value['count'] ?? 1;
                                } else {
                                    $enabled = $value === true || $value === 'true' || $value === 1 || $value === '1';
                                }
                            }
                        @endphp
                        <div class="amenity-item">
                            <div class="amenity-header">
                                <span class="amenity-name"><i class="fas {{ $amenity['icon'] }}" style="color: var(--primary-color); margin-right: 8px;"></i>{{ $amenity['label'] }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                <div class="form-check" style="margin-bottom: 0;">
                                    <input class="form-check-input" type="checkbox" id="amenity-{{ $key }}" 
                                           onchange="toggleAmenityStatus('{{ $key }}', this)" 
                                           {{ $enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="amenity-{{ $key }}" style="font-size: 0.85rem; cursor: pointer;">
                                        Enable
                                    </label>
                                </div>
                                <div id="amenity-status-{{ $key }}" style="{{ $enabled ? '' : 'display: none;' }}">
                                    <div class="amenity-status-group">
                                        <div class="amenity-status-option">
                                            <span class="status-dot working"></span>
                                            <input type="radio" id="status-{{ $key }}-working" name="status-{{ $key }}" value="working" 
                                                   {{ $status == 'working' ? 'checked' : '' }}>
                                            <label for="status-{{ $key }}-working">Working</label>
                                        </div>
                                        <div class="amenity-status-option">
                                            <span class="status-dot repair"></span>
                                            <input type="radio" id="status-{{ $key }}-repair" name="status-{{ $key }}" value="repair" 
                                                   {{ $status == 'repair' ? 'checked' : '' }}>
                                            <label for="status-{{ $key }}-repair">Repair</label>
                                        </div>
                                        <div class="amenity-status-option">
                                            <span class="status-dot damaged"></span>
                                            <input type="radio" id="status-{{ $key }}-damaged" name="status-{{ $key }}" value="damaged" 
                                                   {{ $status == 'damaged' ? 'checked' : '' }}>
                                            <label for="status-{{ $key }}-damaged">Damaged</label>
                                        </div>
                                        <div class="amenity-status-option">
                                            <span class="status-dot maintenance"></span>
                                            <input type="radio" id="status-{{ $key }}-maintenance" name="status-{{ $key }}" value="maintenance" 
                                                   {{ $status == 'maintenance' ? 'checked' : '' }}>
                                            <label for="status-{{ $key }}-maintenance">Maint.</label>
                                        </div>
                                    </div>
                                    <div style="margin-top: 6px;">
                                        <label style="font-size: 0.75rem; color: var(--text-muted);">Quantity:</label>
                                        <input type="number" id="count-{{ $key }}" name="count-{{ $key }}" 
                                               value="{{ $count }}" min="1" style="width: 60px; padding: 4px 8px; border: 2px solid var(--border-color); border-radius: 6px; font-size: 0.8rem;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Custom Amenities -->
                <div class="section-divider">
                    <h3><i class="fas fa-plus-circle" style="color: #10b981;"></i> Custom Amenities</h3>
                </div>

                <div id="custom-amenities-container">
                    @if(isset($customAmenities) && count($customAmenities) > 0)
                        @foreach($customAmenities as $index => $amenity)
                            @if($amenity && ($amenity['name'] ?? ''))
                                <div class="dynamic-card" id="custom-amenity-{{ $index + 1 }}">
                                    <div class="card-badge">Custom #{{ $index + 1 }}</div>
                                    <div class="card-row card-row-2">
                                        <div class="form-group">
                                            <label>Amenity Name</label>
                                            <input type="text" class="form-control custom-amenity-name" value="{{ $amenity['name'] ?? '' }}" placeholder="e.g., Projector">
                                        </div>
                                        <div class="form-group">
                                            <label>Quantity</label>
                                            <input type="number" class="form-control custom-amenity-qty" value="{{ $amenity['quantity'] ?? 1 }}" min="1">
                                        </div>
                                    </div>
                                    <div style="text-align:right;margin-top:0.5rem;">
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeCustomAmenity(this)"><i class="fas fa-trash"></i> Remove</button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="dynamic-card" id="custom-amenity-1">
                            <div class="card-badge">Custom #1</div>
                            <div class="card-row card-row-2">
                                <div class="form-group">
                                    <label>Amenity Name</label>
                                    <input type="text" class="form-control custom-amenity-name" placeholder="e.g., Projector">
                                </div>
                                <div class="form-group">
                                    <label>Quantity</label>
                                    <input type="number" class="form-control custom-amenity-qty" value="1" min="1">
                                </div>
                            </div>
                            <div style="text-align:right;margin-top:0.5rem;">
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeCustomAmenity(this)"><i class="fas fa-trash"></i> Remove</button>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="add-btn-row">
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="addCustomAmenity()"><i class="fas fa-plus"></i> Add Custom</button>
                </div>

                <!-- Facilities with Status -->
                <div class="section-divider">
                    <h3><i class="fas fa-building"></i> Facilities with Status</h3>
                    <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: -0.5rem;">
                        Select facilities and their current status (Working, Under Repair, Damaged, Maintenance)
                    </p>
                </div>

                @php
                    $facilityTypes = [
                        'parking' => ['label' => 'Parking', 'icon' => 'fa-parking'],
                        'playground' => ['label' => 'Playground', 'icon' => 'fa-futbol'],
                        'swimming_pool' => ['label' => 'Swimming Pool', 'icon' => 'fa-swimming-pool'],
                        'clubhouse' => ['label' => 'Clubhouse', 'icon' => 'fa-home'],
                        'warehouse' => ['label' => 'Warehouse', 'icon' => 'fa-warehouse'],
                        'store_room' => ['label' => 'Store Room', 'icon' => 'fa-boxes'],
                        'auditorium' => ['label' => 'Auditorium', 'icon' => 'fa-theater-masks']
                    ];
                @endphp

                @foreach($facilityTypes as $key => $facility)
                    @php
                        $enabled = false;
                        $status = 'working';
                        $count = 1;
                        if (isset($facilities[$key])) {
                            $value = $facilities[$key];
                            if (is_array($value)) {
                                $enabled = isset($value['enabled']) && ($value['enabled'] === true || $value['enabled'] === 'true' || $value['enabled'] === 1 || $value['enabled'] === '1');
                                $status = $value['status'] ?? 'working';
                                $count = $value['count'] ?? 1;
                            } else {
                                $enabled = $value === true || $value === 'true' || $value === 1 || $value === '1';
                            }
                        }
                    @endphp
                    <div class="facility-item">
                        <div class="facility-header">
                            <span class="facility-name">
                                <i class="fas {{ $facility['icon'] }}"></i>
                                {{ $facility['label'] }}
                            </span>
                        </div>
                        <div class="facility-controls">
                            <div class="form-check" style="margin-bottom: 0;">
                                <input class="form-check-input" type="checkbox" id="facility-{{ $key }}" 
                                       onchange="toggleFacility('{{ $key }}', this)" 
                                       {{ $enabled ? 'checked' : '' }}>
                                <label class="form-check-label" for="facility-{{ $key }}" style="font-size: 0.85rem; cursor: pointer;">
                                    Enable
                                </label>
                            </div>
                            <div id="facility-status-{{ $key }}" style="{{ $enabled ? '' : 'display: none;' }}">
                                <div class="amenity-status-group" style="margin-top: 0;">
                                    <div class="amenity-status-option">
                                        <span class="status-dot working"></span>
                                        <input type="radio" id="facility-status-{{ $key }}-working" name="facility-status-{{ $key }}" value="working" 
                                               {{ $status == 'working' ? 'checked' : '' }}>
                                        <label for="facility-status-{{ $key }}-working">Working</label>
                                    </div>
                                    <div class="amenity-status-option">
                                        <span class="status-dot repair"></span>
                                        <input type="radio" id="facility-status-{{ $key }}-repair" name="facility-status-{{ $key }}" value="repair" 
                                               {{ $status == 'repair' ? 'checked' : '' }}>
                                        <label for="facility-status-{{ $key }}-repair">Repair</label>
                                    </div>
                                    <div class="amenity-status-option">
                                        <span class="status-dot damaged"></span>
                                        <input type="radio" id="facility-status-{{ $key }}-damaged" name="facility-status-{{ $key }}" value="damaged" 
                                               {{ $status == 'damaged' ? 'checked' : '' }}>
                                        <label for="facility-status-{{ $key }}-damaged">Damaged</label>
                                    </div>
                                    <div class="amenity-status-option">
                                        <span class="status-dot maintenance"></span>
                                        <input type="radio" id="facility-status-{{ $key }}-maintenance" name="facility-status-{{ $key }}" value="maintenance" 
                                               {{ $status == 'maintenance' ? 'checked' : '' }}>
                                        <label for="facility-status-{{ $key }}-maintenance">Maint.</label>
                                    </div>
                                </div>
                            </div>
                            <div id="facility-count-{{ $key }}" style="{{ $enabled ? '' : 'display: none;' }}">
                                <label style="font-size: 0.75rem; color: var(--text-muted);">Count:</label>
                                <input type="number" id="facility-count-input-{{ $key }}" 
                                       value="{{ $count }}" min="1" style="width: 60px; padding: 4px 8px; border: 2px solid var(--border-color); border-radius: 6px; font-size: 0.8rem;">
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="form-actions">
                    <a href="{{ route('buildings.list') }}" class="btn btn-white">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-success" id="saveBtn">
                        <i class="fas fa-save"></i> Update Building
                    </button>
                </div>
            </form>
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

        <!-- Toast Notification -->
        <div id="toast" class="toast">
            <i class="fas fa-check-circle"></i>
            <span id="toastMessage">Building updated successfully!</span>
        </div>
    </div>

    <script>
        const API_BASE_URL = '{{ url('/') }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const BUILDING_ID = '{{ $building->id ?? '' }}';

        let gateCounter = {{ isset($gates) && count($gates) > 0 ? count($gates) : 1 }};
        let areaCounter = {{ isset($additionalAreas) && count($additionalAreas) > 0 ? count($additionalAreas) : 1 }};
        let customAmenityCounter = {{ isset($customAmenities) && count($customAmenities) > 0 ? count($customAmenities) : 1 }};
        let washroomCounter = 0;

        // ===== WASHROOM FUNCTIONS =====
        function toggleWashrooms(checkbox) {
            const details = document.getElementById('washrooms-details');
            if (details) {
                details.style.display = checkbox.checked ? 'block' : 'none';
                if (checkbox.checked) {
                    generateWashroomEntries();
                } else {
                    document.getElementById('washrooms-container').innerHTML = '';
                }
            }
        }

        function generateWashroomEntries() {
            const maleCount = parseInt(document.getElementById('male-washroom-count').value) || 0;
            const femaleCount = parseInt(document.getElementById('female-washroom-count').value) || 0;
            const unisexCount = parseInt(document.getElementById('unisex-washroom-count').value) || 0;
            const container = document.getElementById('washrooms-container');
            container.innerHTML = '';
            
            if (maleCount === 0 && femaleCount === 0 && unisexCount === 0) {
                container.innerHTML = '<div style="color:var(--text-muted);font-size:0.85rem;padding:0.5rem 0;"><i class="fas fa-info-circle"></i> No washrooms configured. Add at least one.</div>';
                return;
            }

            let html = '<div class="row">';
            let counter = 0;

            // Male washrooms
            for (let i = 1; i <= maleCount; i++) {
                counter++;
                const existingData = getExistingWashroomData(counter);
                html += generateWashroomCard(counter, 'male', existingData?.name || `Male Washroom ${i}`, existingData);
                if (counter % 2 === 0) { html += '</div><div class="row">'; }
            }

            // Female washrooms
            for (let i = 1; i <= femaleCount; i++) {
                counter++;
                const existingData = getExistingWashroomData(counter);
                html += generateWashroomCard(counter, 'female', existingData?.name || `Female Washroom ${i}`, existingData);
                if (counter % 2 === 0) { html += '</div><div class="row">'; }
            }

            // Unisex washrooms
            for (let i = 1; i <= unisexCount; i++) {
                counter++;
                const existingData = getExistingWashroomData(counter);
                html += generateWashroomCard(counter, 'unisex', existingData?.name || `Unisex Washroom ${i}`, existingData);
                if (counter % 2 === 0) { html += '</div><div class="row">'; }
            }

            html += '</div>';
            container.innerHTML = html;
            washroomCounter = counter;
        }

        function getExistingWashroomData(index) {
            // Try to get existing washroom data from the stored facilities
            @php
                $washroomData = isset($facilities['washrooms']) && is_array($facilities['washrooms']) ? $facilities['washrooms'] : [];
            @endphp
            const existingWashrooms = @json($washroomData);
            if (existingWashrooms && existingWashrooms[index - 1]) {
                return existingWashrooms[index - 1];
            }
            return null;
        }

        function generateWashroomCard(index, type, label, existingData) {
            const typeBadgeClass = type === 'male' ? 'male' : (type === 'female' ? 'female' : 'unisex');
            const typeIcon = type === 'male' ? 'fa-mars' : (type === 'female' ? 'fa-venus' : 'fa-venus-mars');
            const typeLabel = type.charAt(0).toUpperCase() + type.slice(1);
            
            const toilets = existingData?.toilets ?? 2;
            const urinals = (type === 'male' || type === 'unisex') ? (existingData?.urinals ?? 2) : 0;
            const washbasins = existingData?.washbasins ?? 2;
            const area = existingData?.area ?? '';
            const capacity = existingData?.capacity ?? '';
            
            const urinalField = (type === 'male' || type === 'unisex') ? `
                <div class="form-group">
                    <label><i class="fas fa-toilet"></i> Urinals</label>
                    <input type="number" id="washroom-${index}-urinals" class="form-control" min="0" value="${urinals}" placeholder="Urinals">
                </div>
            ` : '';

            return `
                <div class="col-lg-6 mb-3">
                    <div class="washroom-detail-card" id="washroom-card-${index}">
                        <div class="washroom-badge">Washroom #${index}</div>
                        <span class="washroom-type-badge ${typeBadgeClass}"><i class="fas ${typeIcon}"></i> ${typeLabel}</span>
                        <div class="form-group" style="margin-top:0.5rem;">
                            <label>Washroom Name</label>
                            <input type="text" id="washroom-${index}-name" class="form-control" value="${escapeHtml(label)}" placeholder="e.g., Ground Floor Male">
                        </div>
                        <div class="washroom-row">
                            <div class="form-group">
                                <label><i class="fas fa-toilet"></i> Toilets</label>
                                <input type="number" id="washroom-${index}-toilets" class="form-control" min="0" value="${toilets}" placeholder="Toilets">
                            </div>
                            ${urinalField}
                            <div class="form-group">
                                <label><i class="fas fa-sink"></i> Washbasins</label>
                                <input type="number" id="washroom-${index}-washbasins" class="form-control" min="0" value="${washbasins}" placeholder="Washbasins">
                            </div>
                        </div>
                        <div class="washroom-row" style="margin-top:0.5rem;">
                            <div class="form-group">
                                <label>Area (sq. ft.)</label>
                                <input type="number" id="washroom-${index}-area" class="form-control" min="0" step="0.01" value="${area}" placeholder="Area">
                            </div>
                            <div class="form-group">
                                <label>Capacity (Persons)</label>
                                <input type="number" id="washroom-${index}-capacity" class="form-control" min="0" value="${capacity}" placeholder="Capacity">
                            </div>
                        </div>
                        <div class="washroom-remove-btn">
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeWashroomCard(${index})"><i class="fas fa-trash"></i> Remove</button>
                        </div>
                    </div>
                </div>
            `;
        }

        function removeWashroomCard(index) {
            const card = document.getElementById(`washroom-card-${index}`);
            if (card) {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    card.remove();
                    const container = document.getElementById('washrooms-container');
                    if (container && container.children.length === 0) {
                        container.innerHTML = '<div style="color:var(--text-muted);font-size:0.85rem;padding:0.5rem 0;"><i class="fas fa-info-circle"></i> No washrooms configured.</div>';
                    }
                }, 300);
            }
        }

        function getWashroomData() {
            const washrooms = [];
            const cards = document.querySelectorAll('#washrooms-container .washroom-detail-card');
            cards.forEach(card => {
                const id = card.id.replace('washroom-card-', '');
                const typeBadge = card.querySelector('.washroom-type-badge');
                let type = 'male';
                if (typeBadge) {
                    if (typeBadge.classList.contains('female')) type = 'female';
                    else if (typeBadge.classList.contains('unisex')) type = 'unisex';
                }
                const name = document.getElementById(`washroom-${id}-name`)?.value?.trim() || '';
                const toilets = parseInt(document.getElementById(`washroom-${id}-toilets`)?.value) || 0;
                const urinals = parseInt(document.getElementById(`washroom-${id}-urinals`)?.value) || 0;
                const washbasins = parseInt(document.getElementById(`washroom-${id}-washbasins`)?.value) || 0;
                const area = parseFloat(document.getElementById(`washroom-${id}-area`)?.value) || null;
                const capacity = parseInt(document.getElementById(`washroom-${id}-capacity`)?.value) || null;
                
                if (name || toilets > 0 || urinals > 0 || washbasins > 0) {
                    washrooms.push({
                        name: name || `${type.charAt(0).toUpperCase() + type.slice(1)} Washroom`,
                        type: type,
                        toilets: toilets,
                        urinals: urinals,
                        washbasins: washbasins,
                        area: area,
                        capacity: capacity
                    });
                }
            });
            return washrooms;
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // ===== TOGGLE AMENITY STATUS =====
        function toggleAmenityStatus(key, checkbox) {
            const statusDiv = document.getElementById('amenity-status-' + key);
            if (statusDiv) {
                statusDiv.style.display = checkbox.checked ? 'block' : 'none';
            }
        }

        // ===== TOGGLE FACILITY =====
        function toggleFacility(key, checkbox) {
            const statusDiv = document.getElementById('facility-status-' + key);
            const countDiv = document.getElementById('facility-count-' + key);
            if (statusDiv) {
                statusDiv.style.display = checkbox.checked ? 'block' : 'none';
            }
            if (countDiv) {
                countDiv.style.display = checkbox.checked ? 'block' : 'none';
            }
        }

        // ===== ADD/REMOVE GATES =====
        function addGate() {
            gateCounter++;
            const container = document.getElementById('gates-container');
            const card = document.createElement('div');
            card.className = 'dynamic-card';
            card.id = 'gate-' + gateCounter;
            card.innerHTML = `
                <div class="card-badge">Gate #${gateCounter}</div>
                <div class="card-row card-row-2">
                    <div class="form-group">
                        <label>Gate Name</label>
                        <input type="text" class="form-control gate-name" placeholder="e.g., Main Gate">
                    </div>
                    <div class="form-group">
                        <label>Gate Number</label>
                        <input type="text" class="form-control gate-number" placeholder="e.g., G-01">
                    </div>
                </div>
                <div style="text-align:right;margin-top:0.5rem;">
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeGate(this)"><i class="fas fa-trash"></i> Remove</button>
                </div>
            `;
            container.appendChild(card);
        }

        function removeGate(btn) {
            const card = btn.closest('.dynamic-card');
            if (card && document.querySelectorAll('#gates-container .dynamic-card').length > 1) {
                card.remove();
            } else {
                showToast('At least one gate is required.', 'error');
            }
        }

        // ===== ADD/REMOVE AREAS =====
        function addArea() {
            areaCounter++;
            const container = document.getElementById('additional-areas-container');
            const card = document.createElement('div');
            card.className = 'dynamic-card';
            card.id = 'area-' + areaCounter;
            card.innerHTML = `
                <div class="card-badge">Area #${areaCounter}</div>
                <div class="card-row card-row-3">
                    <div class="form-group">
                        <label>Area Name</label>
                        <input type="text" class="form-control area-name" placeholder="e.g., Garden">
                    </div>
                    <div class="form-group">
                        <label>Area Unit</label>
                        <select class="form-control area-unit">
                            <option value="sq_ft">Sq. Ft.</option>
                            <option value="sq_m">Sq. M.</option>
                            <option value="sq_yd">Sq. Yd.</option>
                            <option value="gaj">Gaj</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Area Value</label>
                        <input type="number" class="form-control area-value" placeholder="Enter area" min="0" step="0.01">
                    </div>
                </div>
                <div style="text-align:right;margin-top:0.5rem;">
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeArea(this)"><i class="fas fa-trash"></i> Remove</button>
                </div>
            `;
            container.appendChild(card);
        }

        function removeArea(btn) {
            const card = btn.closest('.dynamic-card');
            if (card && document.querySelectorAll('#additional-areas-container .dynamic-card').length > 1) {
                card.remove();
            } else {
                showToast('At least one area is required.', 'error');
            }
        }

        // ===== ADD/REMOVE CUSTOM AMENITIES =====
        function addCustomAmenity() {
            customAmenityCounter++;
            const container = document.getElementById('custom-amenities-container');
            const card = document.createElement('div');
            card.className = 'dynamic-card';
            card.id = 'custom-amenity-' + customAmenityCounter;
            card.innerHTML = `
                <div class="card-badge">Custom #${customAmenityCounter}</div>
                <div class="card-row card-row-2">
                    <div class="form-group">
                        <label>Amenity Name</label>
                        <input type="text" class="form-control custom-amenity-name" placeholder="e.g., Projector">
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" class="form-control custom-amenity-qty" value="1" min="1">
                    </div>
                </div>
                <div style="text-align:right;margin-top:0.5rem;">
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeCustomAmenity(this)"><i class="fas fa-trash"></i> Remove</button>
                </div>
            `;
            container.appendChild(card);
        }

        function removeCustomAmenity(btn) {
            const card = btn.closest('.dynamic-card');
            if (card && document.querySelectorAll('#custom-amenities-container .dynamic-card').length > 1) {
                card.remove();
            } else {
                showToast('At least one custom amenity is required.', 'error');
            }
        }

        // ===== SHOW TOAST =====
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

        // ===== IMAGE PREVIEW =====
        document.getElementById('photo_file').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    preview.innerHTML = `<img src="${event.target.result}" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = `<i class="fas fa-building"></i>`;
            }
        });

        // ===== FORM SUBMISSION =====
        document.getElementById('editBuildingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const saveBtn = document.getElementById('saveBtn');
            const originalText = saveBtn.innerHTML;
            
            // Collect gates data
            const gates = [];
            document.querySelectorAll('#gates-container .dynamic-card').forEach(card => {
                const name = card.querySelector('.gate-name')?.value?.trim() || '';
                const number = card.querySelector('.gate-number')?.value?.trim() || '';
                gates.push({ name, number });
            });
            formData.append('gates', JSON.stringify(gates));

            // Collect additional areas data
            const areas = [];
            document.querySelectorAll('#additional-areas-container .dynamic-card').forEach(card => {
                const name = card.querySelector('.area-name')?.value?.trim() || '';
                const unit = card.querySelector('.area-unit')?.value || 'sq_ft';
                const area = card.querySelector('.area-value')?.value || '';
                areas.push({ name, unit, area });
            });
            formData.append('additional_areas', JSON.stringify(areas));

            // Collect custom amenities data
            const customAmenities = [];
            document.querySelectorAll('#custom-amenities-container .dynamic-card').forEach(card => {
                const name = card.querySelector('.custom-amenity-name')?.value?.trim() || '';
                const quantity = parseInt(card.querySelector('.custom-amenity-qty')?.value) || 1;
                if (name) {
                    customAmenities.push({ name, quantity });
                }
            });
            formData.append('custom_amenities', JSON.stringify(customAmenities));

            // Collect amenities with status
            const amenities = {};
            const amenityKeys = ['wifi', 'lan', 'internet_lab', 'cctv', 'fire_alarm', 'security_guard', 
                'biometric', 'elevator', 'escalator', 'ramp', 'generator', 'ups', 'solar_panel', 
                'ac', 'heater', 'exhaust_fan', 'washroom', 'drinking_water', 'cafeteria', 'tuck_shop',
                'library', 'computer_lab', 'science_lab', 'seminar_hall', 'conference_room', 'gym',
                'indoor_games', 'medical_room', 'ambulance', 'atm', 'stationery_shop', 'photocopy',
                'prayer_room', 'daycare', 'guest_room', 'hostel', 'staff_room', 'admin_office', 'laundry'];
            
            amenityKeys.forEach(key => {
                const checkbox = document.getElementById('amenity-' + key);
                if (checkbox && checkbox.checked) {
                    const statusRadios = document.querySelectorAll('input[name="status-' + key + '"]');
                    let status = 'working';
                    statusRadios.forEach(radio => {
                        if (radio.checked) status = radio.value;
                    });
                    const countInput = document.getElementById('count-' + key);
                    const count = countInput ? parseInt(countInput.value) || 1 : 1;
                    amenities[key] = { enabled: true, status: status, count: count };
                }
            });
            formData.append('amenities', JSON.stringify(amenities));

            // Collect facilities data with status
            const facilities = {};
            const facilityKeys = ['parking', 'playground', 'swimming_pool', 'clubhouse', 'warehouse', 'store_room', 'auditorium'];
            facilityKeys.forEach(key => {
                const checkbox = document.getElementById('facility-' + key);
                if (checkbox && checkbox.checked) {
                    const statusRadios = document.querySelectorAll('input[name="facility-status-' + key + '"]');
                    let status = 'working';
                    statusRadios.forEach(radio => {
                        if (radio.checked) status = radio.value;
                    });
                    const countInput = document.getElementById('facility-count-input-' + key);
                    const count = countInput ? parseInt(countInput.value) || 1 : 1;
                    facilities[key] = { enabled: true, status: status, count: count };
                }
            });

            // Collect washroom data
            const hasWashrooms = document.getElementById('has-washrooms').checked;
            if (hasWashrooms) {
                const washrooms = getWashroomData();
                if (washrooms.length > 0) {
                    facilities['washrooms'] = washrooms;
                }
            }

            formData.append('facilities', JSON.stringify(facilities));

            // Show loading state
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Updating...';
            saveBtn.disabled = true;
            
            // Clear previous errors
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.classList.remove('show'));

            fetch(`${API_BASE_URL}/buildings/${BUILDING_ID}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Building updated successfully!');
                    setTimeout(() => {
                        window.location.href = '/institute/admin/view-building/' + BUILDING_ID;
                    }, 1500);
                } else {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const input = document.getElementById(field);
                            const errorDiv = document.getElementById(field + '-error');
                            if (input) input.classList.add('is-invalid');
                            if (errorDiv) {
                                errorDiv.textContent = data.errors[field][0];
                                errorDiv.classList.add('show');
                            }
                        });
                        showToast('Please fix the validation errors.', 'error');
                    } else {
                        showToast(data.message || 'Failed to update building.', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while updating the building.', 'error');
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        });

        // ===== INITIALIZE WASHROOMS ON PAGE LOAD =====
        document.addEventListener('DOMContentLoaded', function() {
            // Generate washroom entries if enabled
            const hasWashroomsCheckbox = document.getElementById('has-washrooms');
            if (hasWashroomsCheckbox && hasWashroomsCheckbox.checked) {
                generateWashroomEntries();
            }
        });
    </script>
</body>
</html>
@endsection