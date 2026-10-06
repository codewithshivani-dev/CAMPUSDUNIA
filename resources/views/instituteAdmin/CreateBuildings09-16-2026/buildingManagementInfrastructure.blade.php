@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Campus Infrastructure Setup</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-gradient: linear-gradient(135deg, #10b981, #059669);
            --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
            --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
            --info-gradient: linear-gradient(135deg, #0ea5e9, #0284c7);
            --border-color: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b; 
        }
        
        * { box-sizing: border-box; }
        
        .header {
            background: var(--primary-gradient);
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
        
        .header-content h1 i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 12px;
        } 
        
        .header-content p {
            opacity: 0.9;
            font-size: 1rem;
        }
        
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
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
        
        .form-card h3 {
            color: var(--text-dark);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
            font-weight: 700;
        }
        
        .form-card h3 i {
            color: var(--primary-color);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.95rem;
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
        
        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }
        
        .is-invalid {
            border-color: #dc3545 !important;
        }
        
        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 4px;
            font-size: 0.8rem;
            color: #dc3545;
            font-weight: 500;
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
        
        .btn-secondary {
            background: #f1f5f9;
            color: var(--text-dark);
            border: 2px solid var(--border-color);
        }
        
        .btn-secondary:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background: var(--success-gradient);
            color: white;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
        }
        
        .btn-purple {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-purple:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67,97,238,0.4);
            color: white;
        }
        
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
        
        .btn-outline-danger {
            background: white;
            color: #dc3545;
            border: 2px solid #dc3545;
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
        
        .btn-outline-danger:hover {
            background: var(--danger-gradient);
            color: white;
            transform: translateY(-2px);
        }
        
        .section-divider {
            border-top: 2px solid var(--border-color);
            margin: 1.25rem 0;
            padding-top: 1rem;
        }
        
        .section-divider h3 {
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
            font-weight: 700;
        }
        
        .section-divider h3 i {
            color: var(--primary-color);
        }
        
        .alert-info {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            color: #075985;
            border: 1px solid #7dd3fc;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        
        .alert-info i {
            font-size: 1.2rem;
            margin-top: 2px;
            color: #0ea5e9;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            color: #166534;
            border: 1px solid #86efac;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        
        .alert-success i {
            font-size: 1.2rem;
            margin-top: 2px;
            color: #10b981;
        }
        
        .alert-error {
            background: linear-gradient(135deg, #fef2f2, #fecaca);
            color: #991b1b;
            border: 1px solid #fca5a5;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        
        .alert-error i {
            font-size: 1.2rem;
            margin-top: 2px;
            color: #ef4444;
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
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.85);
            z-index: 9998;
            justify-content: center;
            align-items: center;
        }
        
        .loading-spinner .spinner-border {
            width: 3.5rem;
            height: 3.5rem;
            color: var(--primary-color);
        }
        
        /* Facility Cards */
        .facility-card {
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s;
        }
        
        .facility-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(67,97,238,0.08);
        }
        
        .facility-card h5 {
            color: var(--text-dark);
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .facility-card h5 i {
            color: var(--primary-color);
        }
        
        .facility-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1rem;
        }
        
        .facility-toggle label {
            font-weight: 500;
            cursor: pointer;
        }
        
        .facility-toggle input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary-color);
        }
        
        .facility-details {
            display: none;
            padding-top: 0.75rem;
            border-top: 1px dashed var(--border-color);
        }
        
        .facility-details.show {
            display: block;
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
        }
        
        .inline-input span {
            font-size: 0.85rem;
            color: var(--text-muted);
            white-space: nowrap;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -0.75rem;
        }
        
        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 0 0.75rem;
        }
        
        .col-md-4 {
            flex: 0 0 33.333%;
            max-width: 33.333%;
            padding: 0 0.75rem;
        }
        
        .col-md-12 {
            flex: 0 0 100%;
            max-width: 100%;
            padding: 0 0.75rem;
        }
        
        .col-lg-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 0 0.75rem;
        }
        
        /* Multi Entry Cards */
        .multi-entry-card {
            background: white;
            border: 1px dashed var(--primary-color);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            position: relative;
        }
        
        .multi-entry-card .entry-badge {
            position: absolute;
            top: -10px;
            left: 12px;
            background: var(--primary-gradient);
            color: white;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 600;
        }
        
        .card-badge {
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
        
        .card-badge-sm {
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
        
        .dynamic-card .card-row {
            display: grid;
            gap: 0.75rem;
        }
        
        .dynamic-card .form-group {
            margin-bottom: 0;
        }
        
        .dynamic-card .form-group label {
            font-size: 0.85rem;
            margin-bottom: 0.3rem;
        }
        
        .dynamic-card .form-group input,
        .dynamic-card .form-group select {
            padding: 0.6rem 0.9rem;
            font-size: 0.9rem;
        }
        
        .add-btn-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1rem;
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
        
        /* Amenities Redirect Card - Small & Bottom */
        .amenities-redirect-card {
            background: linear-gradient(135deg, #f0f4ff, #e8edff);
            border: 2px solid var(--primary-color);
            border-radius: 12px;
            padding: 1.2rem 1.5rem;
            margin: 1.5rem 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .amenities-redirect-card .left-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .amenities-redirect-card .icon {
            font-size: 2rem;
            color: var(--primary-color);
            flex-shrink: 0;
        }
        
        .amenities-redirect-card .text-content h5 {
            font-weight: 700;
            color: var(--text-dark);
            margin: 0 0 2px 0;
            font-size: 1rem;
        }
        
        .amenities-redirect-card .text-content p {
            color: var(--text-muted);
            margin: 0;
            font-size: 0.85rem;
        }
        
        .amenities-redirect-card .btn-purple {
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
            }
            .form-card {
                padding: 1.5rem;
            }
            .col-md-6, .col-md-4, .col-lg-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            .form-actions {
                flex-direction: column;
            }
            .form-actions .btn {
                justify-content: center;
            }
            .amenities-redirect-card {
                flex-direction: column;
                text-align: center;
                padding: 1rem;
            }
            .amenities-redirect-card .left-content {
                flex-direction: column;
                text-align: center;
            }
        }
        
        @media (max-width: 480px) {
            .form-card {
                padding: 1rem;
            }
            .header-content h1 {
                font-size: 1.5rem;
            }
            .amenities-redirect-card .text-content h5 {
                font-size: 0.9rem;
            }
            .amenities-redirect-card .text-content p {
                font-size: 0.8rem;
            }
            .amenities-redirect-card .icon {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="loading-spinner" id="loadingSpinner">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="container-fluid">
        <div class="header">
            <div class="header-content">
                <h1><i class="fas fa-university"></i> Campus Infrastructure Setup</h1>
                <p>Configure your campus details, facilities, and infrastructure</p>
            </div>
            <a href="{{ route('buildings.list') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Campuses
            </a>
        </div>

        <div class="form-card">
            <h2><i class="fas fa-plus-circle"></i> Campus Infrastructure</h2>
            
            <div id="errorAlert" class="alert-error" style="display:none;">
                <i class="fas fa-exclamation-circle"></i>
                <div><strong>Please fix:</strong><ul id="errorList"></ul></div>
            </div>
            
            @if($campus)
            <form id="campus-form">
                <input type="hidden" id="building-id" value="{{ $campus->fincap_merchant_id ?? '' }}">
                <input type="hidden" id="institute-id" value="{{ auth()->user()->institute_id ?? '' }}">
                
                <!-- Campus Info -->
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Campus:</strong> {{ $campus->name ?? 'N/A' }}
                        <span class="badge bg-primary ms-2">{{ $campus->fincap_merchant_id ?? 'N/A' }}</span>
                        <br>
                        <small>{{ $campus->address_line_1 ?? '' }}, {{ $campus->city ?? '' }}, {{ $campus->state ?? '' }}</small>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- AREA SECTION                                 -->
                <!-- ============================================ -->
                <div class="section-divider">
                    <h3><i class="fas fa-vector-square"></i> Total Area of Campus</h3>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Area Unit <span class="text-danger">*</span></label>
                            <select id="area-unit" class="form-control">
                                <option value="sq_ft" {{ ($campus->area_unit ?? 'sq_ft') == 'sq_ft' ? 'selected' : '' }}>Sq. Ft.</option>
                                <option value="sq_m" {{ ($campus->area_unit ?? '') == 'sq_m' ? 'selected' : '' }}>Sq. M.</option>
                                <option value="sq_yd" {{ ($campus->area_unit ?? '') == 'sq_yd' ? 'selected' : '' }}>Sq. Yd.</option>
                                <option value="gaj" {{ ($campus->area_unit ?? '') == 'gaj' ? 'selected' : '' }}>Gaj</option>
                                <option value="marla" {{ ($campus->area_unit ?? '') == 'marla' ? 'selected' : '' }}>Marla</option>
                                <option value="kanal" {{ ($campus->area_unit ?? '') == 'kanal' ? 'selected' : '' }}>Kanal</option>
                                <option value="acre" {{ ($campus->area_unit ?? '') == 'acre' ? 'selected' : '' }}>Acre</option>
                                <option value="hectare" {{ ($campus->area_unit ?? '') == 'hectare' ? 'selected' : '' }}>Hectare</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Area Value</label>
                            <input type="number" id="area-value" class="form-control" 
                                   placeholder="Enter total campus area" min="0" step="0.01" 
                                   value="{{ $campus->area_value ?? '' }}">
                        </div>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- NUMBER OF BLOCKS                            -->
                <!-- ============================================ -->
                <div class="section-divider">
                    <h3><i class="fas fa-cubes"></i> Block Configuration</h3>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Number of Blocks <span class="text-danger">*</span></label>
                            <input type="number" id="num-of-blocks" class="form-control" 
                                   placeholder="Enter number of blocks" min="1" 
                                   value="{{ $campus->number_of_blocks ?? 1 }}">
                            <small class="text-muted">Enter 1 for a single block, or more for multiple blocks.</small>
                        </div>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- PARKING FACILITY                            -->
                <!-- ============================================ -->
                <div class="section-divider">
                    <h3><i class="fas fa-parking"></i> Parking Facility</h3>
                </div>
                <div class="facility-card">
                    <h5><i class="fas fa-parking"></i> Parking Configuration</h5>
                    <div class="facility-toggle">
                        <input type="checkbox" id="has-parking" onchange="toggleFacilityDetails('parking-details')">
                        <label for="has-parking">Parking Facility</label>
                    </div>
                    <div class="facility-details" id="parking-details">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Number of Basement Parking</label>
                                    <input type="number" id="basement-parking-count" class="form-control" min="0" value="1" onchange="generateBasementParkingEntries()">
                                </div>
                                <div id="basement-parking-container"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Number of Open Parking</label>
                                    <input type="number" id="open-parking-count" class="form-control" min="0" value="1" onchange="generateOpenParkingEntries()">
                                </div>
                                <div id="open-parking-container"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- PLAYGROUND FACILITY                         -->
                <!-- ============================================ -->
                <div class="section-divider">
                    <h3><i class="fas fa-futbol"></i> Playground</h3>
                </div>
                <div class="facility-card">
                    <h5><i class="fas fa-futbol"></i> Playground Configuration</h5>
                    <div class="facility-toggle">
                        <input type="checkbox" id="has-playground" onchange="toggleFacilityDetails('playground-details')">
                        <label for="has-playground">Playground</label>
                    </div>
                    <div class="facility-details" id="playground-details">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Number of Indoor Playgrounds</label>
                                    <input type="number" id="indoor-playground-count" class="form-control" min="0" value="1" onchange="generateIndoorPlaygroundEntries()">
                                </div>
                                <div id="indoor-playground-container"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Number of Outdoor Playgrounds</label>
                                    <input type="number" id="outdoor-playground-count" class="form-control" min="0" value="1" onchange="generateOutdoorPlaygroundEntries()">
                                </div>
                                <div id="outdoor-playground-container"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- SWIMMING POOL FACILITY                      -->
                <!-- ============================================ -->
                <div class="section-divider">
                    <h3><i class="fas fa-swimming-pool"></i> Swimming Pool</h3>
                </div>
                <div class="facility-card">
                    <h5><i class="fas fa-swimming-pool"></i> Swimming Pool Configuration</h5>
                    <div class="facility-toggle">
                        <input type="checkbox" id="has-swimming-pool" onchange="toggleFacilityDetails('pool-details')">
                        <label for="has-swimming-pool">Swimming Pool</label>
                    </div>
                    <div class="facility-details" id="pool-details">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Number of Indoor Pools</label>
                                    <input type="number" id="indoor-pool-count" class="form-control" min="0" value="1" onchange="generateIndoorPoolEntries()">
                                </div>
                                <div id="indoor-pool-container"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Number of Outdoor Pools</label>
                                    <input type="number" id="outdoor-pool-count" class="form-control" min="0" value="1" onchange="generateOutdoorPoolEntries()">
                                </div>
                                <div id="outdoor-pool-container"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- CLUBHOUSE FACILITY                          -->
                <!-- ============================================ -->
                <div class="section-divider">
                    <h3><i class="fas fa-home"></i> Clubhouse</h3>
                </div>
                <div class="facility-card">
                    <h5><i class="fas fa-home"></i> Clubhouse Configuration</h5>
                    <div class="facility-toggle">
                        <input type="checkbox" id="has-clubhouse" onchange="toggleFacilityDetails('clubhouse-details')">
                        <label for="has-clubhouse">Clubhouse</label>
                    </div>
                    <div class="facility-details" id="clubhouse-details">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Area</label>
                                    <div class="inline-input">
                                        <input type="number" id="clubhouse-area" class="form-control" placeholder="Area" min="0" step="0.01">
                                        <span>Sq. Ft.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Capacity (Persons)</label>
                                    <input type="number" id="clubhouse-capacity" class="form-control" placeholder="Max persons" min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- WAREHOUSE FACILITY                          -->
                <!-- ============================================ -->
                <div class="section-divider">
                    <h3><i class="fas fa-warehouse"></i> Warehouse</h3>
                </div>
                <div class="facility-card">
                    <h5><i class="fas fa-warehouse"></i> Warehouse Configuration</h5>
                    <div class="facility-toggle">
                        <input type="checkbox" id="has-warehouse" onchange="toggleFacilityDetails('warehouse-details')">
                        <label for="has-warehouse">Warehouse</label>
                    </div>
                    <div class="facility-details" id="warehouse-details">
                        <div class="form-group">
                            <label>Number of Warehouses</label>
                            <input type="number" id="warehouse-count" class="form-control" min="0" value="1" onchange="generateWarehouseEntries()">
                        </div>
                        <div id="warehouse-entries-container"></div>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- STORE ROOM FACILITY                         -->
                <!-- ============================================ -->
                <div class="section-divider">
                    <h3><i class="fas fa-boxes"></i> Store Room</h3>
                </div>
                <div class="facility-card">
                    <h5><i class="fas fa-boxes"></i> Store Room Configuration</h5>
                    <div class="facility-toggle">
                        <input type="checkbox" id="has-store" onchange="toggleFacilityDetails('store-details')">
                        <label for="has-store">Store Room</label>
                    </div>
                    <div class="facility-details" id="store-details">
                        <div class="form-group">
                            <label>Number of Store Rooms</label>
                            <input type="number" id="store-room-count" class="form-control" min="0" value="1" onchange="generateStoreEntries()">
                        </div>
                        <div id="store-entries-container"></div>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- AUDITORIUM FACILITY                         -->
                <!-- ============================================ -->
                <div class="section-divider">
                    <h3><i class="fas fa-theater-masks"></i> Auditorium</h3>
                </div>
                <div class="facility-card">
                    <h5><i class="fas fa-theater-masks"></i> Auditorium Configuration</h5>
                    <div class="facility-toggle">
                        <input type="checkbox" id="has-auditorium" onchange="toggleFacilityDetails('auditorium-details')">
                        <label for="has-auditorium">Auditorium</label>
                    </div>
                    <div class="facility-details" id="auditorium-details">
                        <div class="form-group">
                            <label>Number of Auditoriums</label>
                            <input type="number" id="auditorium-count" class="form-control" min="0" value="1" onchange="generateAuditoriumEntries()">
                        </div>
                        <div id="auditorium-entries-container"></div>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- WASHROOM SECTION                            -->
                <!-- ============================================ -->
                <div class="section-divider">
                    <h3><i class="fas fa-restroom"></i> Washrooms</h3>
                </div>
                <div class="facility-card">
                    <h5><i class="fas fa-restroom"></i> Washroom Configuration</h5>
                    <div class="facility-toggle">
                        <input type="checkbox" id="has-washrooms" onchange="toggleFacilityDetails('washrooms-details')">
                        <label for="has-washrooms">Add Washrooms</label>
                    </div>
                    <div class="facility-details" id="washrooms-details">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Number of Male Washrooms</label>
                                    <input type="number" id="male-washroom-count" class="form-control" min="0" value="0" onchange="generateWashroomEntries()">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Number of Female Washrooms</label>
                                    <input type="number" id="female-washroom-count" class="form-control" min="0" value="0" onchange="generateWashroomEntries()">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Number of Unisex Washrooms</label>
                                    <input type="number" id="unisex-washroom-count" class="form-control" min="0" value="0" onchange="generateWashroomEntries()">
                                </div>
                            </div>
                        </div>
                        <div id="washrooms-container" style="margin-top:1rem;"></div>
                        <div class="alert-info" style="font-size:0.8rem;margin-top:0.5rem;">
                            <i class="fas fa-info-circle"></i> For each washroom, specify the number of toilets, urinals (for male/unisex), and washbasins.
                        </div>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- ADDITIONAL AREAS                            -->
                <!-- ============================================ -->
                <div class="section-divider">
                    <h3><i class="fas fa-map"></i> Additional Areas</h3>
                </div>
                <div id="additional-areas-container">
                    <div class="dynamic-card" id="additional-area-1">
                        <div class="card-badge">Area #1</div>
                        <div class="card-row" style="grid-template-columns:2fr 1fr 1fr;">
                            <div class="form-group">
                                <label>Area Name</label>
                                <input type="text" id="area-name-1" class="form-control" placeholder="e.g., Garden">
                            </div>
                            <div class="form-group">
                                <label>Area Unit</label>
                                <select id="area-unit-1" class="form-control area-unit-select">
                                    <option value="sq_ft">Sq. Ft.</option>
                                    <option value="sq_m">Sq. M.</option>
                                    <option value="sq_yd">Sq. Yd.</option>
                                    <option value="gaj">Gaj</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Area Value</label>
                                <input type="number" id="area-value-1" class="form-control" placeholder="Enter area" min="0" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add-btn-row">
                    <button type="button" class="btn-outline-primary" onclick="addAdditionalArea()">
                        <i class="fas fa-plus"></i> Add Area
                    </button>
                </div>

                <!-- ============================================ -->
                <!-- GATES                                      -->
                <!-- ============================================ -->
                <div class="section-divider">
                    <h3><i class="fas fa-door-open"></i> Gates</h3>
                </div>
                <div id="gates-container">
                    <div class="dynamic-card" id="gate-1">
                        <div class="card-badge">Gate #1</div>
                        <div class="card-row" style="grid-template-columns:1fr 1fr;">
                            <div class="form-group">
                                <label>Gate Name</label>
                                <input type="text" id="gate-name-1" class="form-control" placeholder="e.g., Main Gate">
                            </div>
                            <div class="form-group">
                                <label>Gate Number</label>
                                <input type="text" id="gate-number-1" class="form-control" placeholder="e.g., G-01">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add-btn-row">
                    <button type="button" class="btn-outline-primary" onclick="addGate()">
                        <i class="fas fa-plus"></i> Add Gate
                    </button>
                </div>

                <!-- ============================================ -->
                <!-- CUSTOM AMENITIES (Only if needed)           -->
                <!-- ============================================ -->
                <div style="margin-top:1.5rem;">
                    <h4 style="color:var(--text-dark);font-weight:700;margin-bottom:1rem;">
                        <i class="fas fa-plus-circle" style="color:#10b981;"></i> Custom Amenities
                    </h4>
                    <div id="custom-amenities-container">
                        <div class="custom-amenity-card" id="custom-amenity-1">
                            <div class="card-badge">Custom #1</div>
                            <div class="card-row" style="grid-template-columns:2fr 1fr 1fr;gap:0.75rem;">
                                <div class="form-group">
                                    <label>Amenity Name</label>
                                    <input type="text" id="custom-amenity-name-1" class="form-control" placeholder="e.g., Projector">
                                </div>
                                <div class="form-group">
                                    <label>Amenity Type</label>
                                    <select id="custom-amenity-type-1" class="form-control" onchange="onCustomAmenityTypeChange(1)">
                                        <option value="count">Quantity Based</option>
                                        <option value="space">Space/Area Based</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Quantity</label>
                                    <input type="number" id="custom-amenity-qty-1" class="form-control" value="1" min="1">
                                </div>
                            </div>
                            <div id="custom-amenity-space-1" class="custom-amenity-space-details" style="display:none;margin-top:0.75rem;padding-top:0.75rem;border-top:1px dashed var(--border-color);">
                                <div class="card-row" style="grid-template-columns:1fr 1fr;gap:0.75rem;">
                                    <div class="form-group">
                                        <label>Area per Unit</label>
                                        <div class="inline-input">
                                            <input type="number" id="custom-amenity-area-1" class="form-control" placeholder="Area" min="0" step="0.01">
                                            <span id="custom-amenity-area-unit-label-1">Sq. Ft.</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Total Area</label>
                                        <div class="inline-input">
                                            <input type="number" id="custom-amenity-total-area-1" class="form-control" placeholder="Auto-calculated" readonly style="background:#e8edff;">
                                            <span>Sq. Ft.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="text-align:right;margin-top:0.75rem;">
                                <button type="button" class="btn-outline-danger" onclick="removeCustomAmenity(1)">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="add-btn-row">
                        <button type="button" class="btn-outline-success" onclick="addCustomAmenity()">
                            <i class="fas fa-plus"></i> Add Custom
                        </button>
                    </div>
                </div>

                <!-- ============================================ -->
                <!-- AMENITIES - REDIRECT TO DEDICATED PAGE      -->
                <!-- (MOVED TO BOTTOM - SMALLER)                 -->
                <!-- ============================================ -->
                <div class="amenities-redirect-card">
                    <div class="left-content">
                        <div class="icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div class="text-content">
                            <h5>Manage Campus Amenities</h5>
                            <p>Configure counts, create units, and manage specifications</p>
                        </div>
                    </div>
                    <a href="{{ route('institute.admin.campus.amenities') }}" class="btn btn-purple">
                        <i class="fas fa-arrow-right me-2"></i>Go to Amenities
                    </a>
                </div>

                <!-- ============================================ -->
                <!-- FORM ACTIONS                               -->
                <!-- ============================================ -->
                <div class="form-actions">
                    <button type="button" class="btn btn-primary" onclick="saveCampusInfrastructure()">
                        <i class="fas fa-save"></i> Save Campus
                    </button>
                    <a href="{{ route('buildings.page', ['campus' => $campus->fincap_merchant_id ?? '']) }}" 
                       class="btn btn-success" id="continueToBlocksBtn">
                        <i class="fas fa-arrow-right"></i> Continue to Blocks
                    </a>
                </div>
            </form>
            @endif
        </div>
    </div>

    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message"></span>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    const API_BASE_URL = '{{ url('/') }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    const CAMPUS_ID = '{{ $campus->fincap_merchant_id ?? '' }}';

    // ==========================================
    // COUNTERS & VARIABLES
    // ==========================================
    let areaCounter = 1;
    let gateCounter = 1;
    let customAmenityCounter = 1;
    let basementParkingCounter = 0;
    let openParkingCounter = 0;
    let indoorPlaygroundCounter = 0;
    let outdoorPlaygroundCounter = 0;
    let indoorPoolCounter = 0;
    let outdoorPoolCounter = 0;
    let warehouseEntryCounter = 0;
    let storeEntryCounter = 0;
    let auditoriumCounter = 0;
    let washroomCounter = 0;
    let campusAreaUnit = 'sq_ft';

    // ==========================================
    // TOAST NOTIFICATION
    // ==========================================
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');
        
        toastMessage.textContent = message;
        toast.className = 'toast' + (type === 'error' ? ' error' : type === 'info' ? ' info' : '');
        toast.querySelector('i').className = type === 'error' ? 'fas fa-exclamation-circle' : 
                                               type === 'info' ? 'fas fa-info-circle' : 'fas fa-check-circle';
        toast.classList.add('show');
        
        clearTimeout(window.toastTimeout);
        window.toastTimeout = setTimeout(() => toast.classList.remove('show'), 3000);
    }

    // ==========================================
    // UTILITY FUNCTIONS
    // ==========================================
    function generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0,
                v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

    function removeCard(cardId) {
        const card = document.getElementById(cardId);
        if (card) {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.95)';
            setTimeout(() => card.remove(), 300);
        }
    }

    function toggleFacilityDetails(detailsId) {
        const d = document.getElementById(detailsId);
        if (d) d.classList.toggle('show');
    }

    // ==========================================
    // ADDITIONAL AREAS
    // ==========================================
    function addAdditionalArea() {
        areaCounter++;
        const c = document.getElementById('additional-areas-container');
        const card = document.createElement('div');
        card.className = 'dynamic-card';
        card.id = `additional-area-${areaCounter}`;
        card.innerHTML = `
            <div class="card-badge">Area #${areaCounter}</div>
            <div class="card-row" style="grid-template-columns:2fr 1fr 1fr;">
                <div class="form-group">
                    <label>Area Name</label>
                    <input type="text" id="area-name-${areaCounter}" class="form-control" placeholder="e.g., Garden">
                </div>
                <div class="form-group">
                    <label>Area Unit</label>
                    <select id="area-unit-${areaCounter}" class="form-control area-unit-select">
                        <option value="sq_ft">Sq. Ft.</option>
                        <option value="sq_m">Sq. M.</option>
                        <option value="sq_yd">Sq. Yd.</option>
                        <option value="gaj">Gaj</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Area Value</label>
                    <input type="number" id="area-value-${areaCounter}" class="form-control" placeholder="Enter area" min="0" step="0.01">
                </div>
            </div>
            <div style="text-align:right;margin-top:0.75rem;">
                <button type="button" class="btn-outline-danger" onclick="removeCard('additional-area-${areaCounter}')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        c.appendChild(card);
    }

    // ==========================================
    // GATES
    // ==========================================
    function addGate() {
        gateCounter++;
        const c = document.getElementById('gates-container');
        const card = document.createElement('div');
        card.className = 'dynamic-card';
        card.id = `gate-${gateCounter}`;
        card.innerHTML = `
            <div class="card-badge">Gate #${gateCounter}</div>
            <div class="card-row" style="grid-template-columns:1fr 1fr;">
                <div class="form-group">
                    <label>Gate Name</label>
                    <input type="text" id="gate-name-${gateCounter}" class="form-control" placeholder="e.g., Side Entrance">
                </div>
                <div class="form-group">
                    <label>Gate Number</label>
                    <input type="text" id="gate-number-${gateCounter}" class="form-control" placeholder="e.g., G-02">
                </div>
            </div>
            <div style="text-align:right;margin-top:0.75rem;">
                <button type="button" class="btn-outline-danger" onclick="removeCard('gate-${gateCounter}')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        c.appendChild(card);
    }

    // ==========================================
    // CUSTOM AMENITIES
    // ==========================================
    function addCustomAmenity() {
        customAmenityCounter++;
        const container = document.getElementById('custom-amenities-container');
        const card = document.createElement('div');
        card.className = 'custom-amenity-card';
        card.id = `custom-amenity-${customAmenityCounter}`;
        card.innerHTML = `
            <div class="card-badge">Custom #${customAmenityCounter}</div>
            <div class="card-row" style="grid-template-columns:2fr 1fr 1fr;gap:0.75rem;">
                <div class="form-group">
                    <label>Amenity Name</label>
                    <input type="text" id="custom-amenity-name-${customAmenityCounter}" class="form-control" placeholder="e.g., Projector">
                </div>
                <div class="form-group">
                    <label>Amenity Type</label>
                    <select id="custom-amenity-type-${customAmenityCounter}" class="form-control" onchange="onCustomAmenityTypeChange(${customAmenityCounter})">
                        <option value="count">Quantity Based</option>
                        <option value="space">Space/Area Based</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" id="custom-amenity-qty-${customAmenityCounter}" class="form-control" value="1" min="1">
                </div>
            </div>
            <div id="custom-amenity-space-${customAmenityCounter}" class="custom-amenity-space-details" style="display:none;margin-top:0.75rem;padding-top:0.75rem;border-top:1px dashed var(--border-color);">
                <div class="card-row" style="grid-template-columns:1fr 1fr;gap:0.75rem;">
                    <div class="form-group">
                        <label>Area per Unit</label>
                        <div class="inline-input">
                            <input type="number" id="custom-amenity-area-${customAmenityCounter}" class="form-control" placeholder="Area" min="0" step="0.01">
                            <span id="custom-amenity-area-unit-label-${customAmenityCounter}">Sq. Ft.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Total Area</label>
                        <div class="inline-input">
                            <input type="number" id="custom-amenity-total-area-${customAmenityCounter}" class="form-control" placeholder="Auto-calculated" readonly style="background:#e8edff;">
                            <span>Sq. Ft.</span>
                        </div>
                    </div>
                </div>
            </div>
            <div style="text-align:right;margin-top:0.75rem;">
                <button type="button" class="btn-outline-danger" onclick="removeCustomAmenity(${customAmenityCounter})">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
        `;
        container.appendChild(card);
        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function removeCustomAmenity(counter) {
        const card = document.getElementById(`custom-amenity-${counter}`);
        if (card) {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.95)';
            setTimeout(() => card.remove(), 300);
        }
    }

    function onCustomAmenityTypeChange(counter) {
        const typeSelect = document.getElementById(`custom-amenity-type-${counter}`);
        const spaceDetails = document.getElementById(`custom-amenity-space-${counter}`);
        const qtyInput = document.getElementById(`custom-amenity-qty-${counter}`);
        const areaInput = document.getElementById(`custom-amenity-area-${counter}`);
        
        if (typeSelect && spaceDetails) {
            const type = typeSelect.value;
            if (type === 'space') {
                spaceDetails.style.display = 'block';
                if (qtyInput) qtyInput.setAttribute('onchange', `updateCustomAmenityTotalArea(${counter})`);
                if (areaInput) areaInput.setAttribute('onchange', `updateCustomAmenityTotalArea(${counter})`);
                updateCustomAmenityTotalArea(counter);
            } else {
                spaceDetails.style.display = 'none';
                if (qtyInput) qtyInput.removeAttribute('onchange');
            }
        }
    }

    function updateCustomAmenityTotalArea(counter) {
        const qty = parseInt(document.getElementById(`custom-amenity-qty-${counter}`)?.value) || 0;
        const areaPerUnit = parseFloat(document.getElementById(`custom-amenity-area-${counter}`)?.value) || 0;
        const totalAreaInput = document.getElementById(`custom-amenity-total-area-${counter}`);
        if (totalAreaInput) totalAreaInput.value = (qty * areaPerUnit).toFixed(2);
    }

    // ==========================================
    // PARKING GENERATORS
    // ==========================================
    function generateBasementParkingEntries() {
        const count = parseInt(document.getElementById('basement-parking-count').value) || 0;
        const container = document.getElementById('basement-parking-container');
        container.innerHTML = '';
        if (count <= 0) return;
        let html = '<div class="row">';
        for (let i = 1; i <= count; i++) {
            basementParkingCounter++;
            html += `
                <div class="col-lg-12 mb-3">
                    <div class="multi-entry-card" id="basement-parking-${basementParkingCounter}">
                        <div class="entry-badge">Basement #${i}</div>
                        <div class="card-row" style="grid-template-columns:2fr 1fr 1fr;gap:0.75rem;margin-top:0.5rem;">
                            <div class="form-group">
                                <label>Basement Name/Level</label>
                                <input type="text" class="form-control basement-name" placeholder="e.g., B1">
                            </div>
                            <div class="form-group">
                                <label>Area</label>
                                <div class="inline-input">
                                    <input type="number" class="form-control basement-area" placeholder="Area" min="0" step="0.01">
                                    <span>Sq. Ft.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Capacity (Vehicles)</label>
                                <input type="number" class="form-control basement-capacity" placeholder="Vehicles" min="0">
                            </div>
                        </div>
                        <div style="text-align:right;margin-top:0.5rem;">
                            <button type="button" class="btn-outline-danger" onclick="removeCard('basement-parking-${basementParkingCounter}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
        }
        html += '</div>';
        container.innerHTML = html;
    }

    function generateOpenParkingEntries() {
        const count = parseInt(document.getElementById('open-parking-count').value) || 0;
        const container = document.getElementById('open-parking-container');
        container.innerHTML = '';
        if (count <= 0) return;
        let html = '<div class="row">';
        for (let i = 1; i <= count; i++) {
            openParkingCounter++;
            html += `
                <div class="col-lg-12 mb-3">
                    <div class="multi-entry-card" id="open-parking-${openParkingCounter}">
                        <div class="entry-badge">Open #${i}</div>
                        <div class="card-row" style="grid-template-columns:2fr 1fr 1fr;gap:0.75rem;margin-top:0.5rem;">
                            <div class="form-group">
                                <label>Parking Area Name</label>
                                <input type="text" class="form-control open-name" placeholder="e.g., Front Parking">
                            </div>
                            <div class="form-group">
                                <label>Area</label>
                                <div class="inline-input">
                                    <input type="number" class="form-control open-area" placeholder="Area" min="0" step="0.01">
                                    <span>Sq. Ft.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Capacity (Vehicles)</label>
                                <input type="number" class="form-control open-capacity" placeholder="Vehicles" min="0">
                            </div>
                        </div>
                        <div style="text-align:right;margin-top:0.5rem;">
                            <button type="button" class="btn-outline-danger" onclick="removeCard('open-parking-${openParkingCounter}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
        }
        html += '</div>';
        container.innerHTML = html;
    }

    // ==========================================
    // PLAYGROUND GENERATORS
    // ==========================================
    function generateIndoorPlaygroundEntries() {
        const count = parseInt(document.getElementById('indoor-playground-count').value) || 0;
        const container = document.getElementById('indoor-playground-container');
        container.innerHTML = '';
        if (count <= 0) return;
        let html = '<div class="row">';
        for (let i = 1; i <= count; i++) {
            indoorPlaygroundCounter++;
            html += `
                <div class="col-lg-12 mb-3">
                    <div class="multi-entry-card" id="indoor-playground-${indoorPlaygroundCounter}">
                        <div class="entry-badge">Indoor #${i}</div>
                        <div class="card-row" style="grid-template-columns:2fr 1fr 1fr;gap:0.75rem;margin-top:0.5rem;">
                            <div class="form-group">
                                <label>Playground Name</label>
                                <input type="text" class="form-control indoor-pg-name" placeholder="e.g., Table Tennis Room">
                            </div>
                            <div class="form-group">
                                <label>Area</label>
                                <div class="inline-input">
                                    <input type="number" class="form-control indoor-pg-area" placeholder="Area" min="0" step="0.01">
                                    <span>Sq. Ft.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Capacity</label>
                                <input type="number" class="form-control indoor-pg-capacity" placeholder="Persons" min="0">
                            </div>
                        </div>
                        <div style="text-align:right;margin-top:0.5rem;">
                            <button type="button" class="btn-outline-danger" onclick="removeCard('indoor-playground-${indoorPlaygroundCounter}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
        }
        html += '</div>';
        container.innerHTML = html;
    }

    function generateOutdoorPlaygroundEntries() {
        const count = parseInt(document.getElementById('outdoor-playground-count').value) || 0;
        const container = document.getElementById('outdoor-playground-container');
        container.innerHTML = '';
        if (count <= 0) return;
        let html = '<div class="row">';
        for (let i = 1; i <= count; i++) {
            outdoorPlaygroundCounter++;
            html += `
                <div class="col-lg-12 mb-3">
                    <div class="multi-entry-card" id="outdoor-playground-${outdoorPlaygroundCounter}">
                        <div class="entry-badge">Outdoor #${i}</div>
                        <div class="card-row" style="grid-template-columns:2fr 1fr 1fr;gap:0.75rem;margin-top:0.5rem;">
                            <div class="form-group">
                                <label>Playground Name</label>
                                <input type="text" class="form-control outdoor-pg-name" placeholder="e.g., Football Ground">
                            </div>
                            <div class="form-group">
                                <label>Area</label>
                                <div class="inline-input">
                                    <input type="number" class="form-control outdoor-pg-area" placeholder="Area" min="0" step="0.01">
                                    <span>Sq. Ft.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Capacity</label>
                                <input type="number" class="form-control outdoor-pg-capacity" placeholder="Persons" min="0">
                            </div>
                        </div>
                        <div style="text-align:right;margin-top:0.5rem;">
                            <button type="button" class="btn-outline-danger" onclick="removeCard('outdoor-playground-${outdoorPlaygroundCounter}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
        }
        html += '</div>';
        container.innerHTML = html;
    }

    // ==========================================
    // POOL GENERATORS
    // ==========================================
    function generateIndoorPoolEntries() {
        const count = parseInt(document.getElementById('indoor-pool-count').value) || 0;
        const container = document.getElementById('indoor-pool-container');
        container.innerHTML = '';
        if (count <= 0) return;
        let html = '<div class="row">';
        for (let i = 1; i <= count; i++) {
            indoorPoolCounter++;
            html += `
                <div class="col-lg-12 mb-3">
                    <div class="multi-entry-card" id="indoor-pool-${indoorPoolCounter}">
                        <div class="entry-badge">Indoor Pool #${i}</div>
                        <div class="card-row" style="grid-template-columns:2fr 1fr 1fr;gap:0.75rem;margin-top:0.5rem;">
                            <div class="form-group">
                                <label>Pool Name</label>
                                <input type="text" class="form-control indoor-pool-name" placeholder="e.g., Main Indoor Pool">
                            </div>
                            <div class="form-group">
                                <label>Area</label>
                                <div class="inline-input">
                                    <input type="number" class="form-control indoor-pool-area" placeholder="Area" min="0" step="0.01">
                                    <span>Sq. Ft.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Capacity (Persons)</label>
                                <input type="number" class="form-control indoor-pool-capacity" placeholder="Persons" min="0">
                            </div>
                        </div>
                        <div style="text-align:right;margin-top:0.5rem;">
                            <button type="button" class="btn-outline-danger" onclick="removeCard('indoor-pool-${indoorPoolCounter}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
        }
        html += '</div>';
        container.innerHTML = html;
    }

    function generateOutdoorPoolEntries() {
        const count = parseInt(document.getElementById('outdoor-pool-count').value) || 0;
        const container = document.getElementById('outdoor-pool-container');
        container.innerHTML = '';
        if (count <= 0) return;
        let html = '<div class="row">';
        for (let i = 1; i <= count; i++) {
            outdoorPoolCounter++;
            html += `
                <div class="col-lg-12 mb-3">
                    <div class="multi-entry-card" id="outdoor-pool-${outdoorPoolCounter}">
                        <div class="entry-badge">Outdoor Pool #${i}</div>
                        <div class="card-row" style="grid-template-columns:2fr 1fr 1fr;gap:0.75rem;margin-top:0.5rem;">
                            <div class="form-group">
                                <label>Pool Name</label>
                                <input type="text" class="form-control outdoor-pool-name" placeholder="e.g., Main Outdoor Pool">
                            </div>
                            <div class="form-group">
                                <label>Area</label>
                                <div class="inline-input">
                                    <input type="number" class="form-control outdoor-pool-area" placeholder="Area" min="0" step="0.01">
                                    <span>Sq. Ft.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Capacity (Persons)</label>
                                <input type="number" class="form-control outdoor-pool-capacity" placeholder="Persons" min="0">
                            </div>
                        </div>
                        <div style="text-align:right;margin-top:0.5rem;">
                            <button type="button" class="btn-outline-danger" onclick="removeCard('outdoor-pool-${outdoorPoolCounter}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
        }
        html += '</div>';
        container.innerHTML = html;
    }

    // ==========================================
    // WAREHOUSE GENERATORS
    // ==========================================
    function generateWarehouseEntries() {
        const count = parseInt(document.getElementById('warehouse-count').value) || 0;
        const container = document.getElementById('warehouse-entries-container');
        container.innerHTML = '';
        if (count <= 0) return;
        let html = '<div class="row">';
        for (let i = 1; i <= count; i++) {
            warehouseEntryCounter++;
            html += `
                <div class="col-lg-6 mb-3">
                    <div class="multi-entry-card" id="warehouse-entry-${warehouseEntryCounter}">
                        <div class="entry-badge">Warehouse #${i}</div>
                        <div class="card-row" style="grid-template-columns:2fr 1fr 1fr 1fr;gap:0.75rem;margin-top:0.5rem;">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control wh-name" placeholder="e.g., Main Warehouse">
                            </div>
                            <div class="form-group">
                                <label>Area</label>
                                <div class="inline-input">
                                    <input type="number" class="form-control wh-area" placeholder="Area" min="0" step="0.01">
                                    <span>Sq. Ft.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Capacity</label>
                                <div class="inline-input">
                                    <input type="number" class="form-control wh-capacity" placeholder="Capacity" min="0">
                                    <span id="wh-unit-label-${warehouseEntryCounter}">Tons</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Unit</label>
                                <select class="form-control wh-unit" onchange="updateWhUnitLabel(this,${warehouseEntryCounter})">
                                    <option value="tons">Tons</option>
                                    <option value="kg">Kg</option>
                                    <option value="liters">Liters</option>
                                    <option value="cubic_m">Cubic M</option>
                                    <option value="units">Units</option>
                                </select>
                            </div>
                        </div>
                        <div style="text-align:right;margin-top:0.5rem;">
                            <button type="button" class="btn-outline-danger" onclick="removeCard('warehouse-entry-${warehouseEntryCounter}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
        }
        html += '</div>';
        container.innerHTML = html;
    }

    function updateWhUnitLabel(sel, counter) {
        const l = document.getElementById(`wh-unit-label-${counter}`);
        if (l) l.textContent = sel.options[sel.selectedIndex].text;
    }

    // ==========================================
    // STORE GENERATORS
    // ==========================================
    function generateStoreEntries() {
        const count = parseInt(document.getElementById('store-room-count').value) || 0;
        const container = document.getElementById('store-entries-container');
        container.innerHTML = '';
        if (count <= 0) return;
        let html = '<div class="row">';
        for (let i = 1; i <= count; i++) {
            storeEntryCounter++;
            html += `
                <div class="col-lg-6 mb-3">
                    <div class="multi-entry-card" id="store-entry-${storeEntryCounter}">
                        <div class="entry-badge">Store #${i}</div>
                        <div class="card-row" style="grid-template-columns:2fr 1fr 1fr 1fr;gap:0.75rem;margin-top:0.5rem;">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control st-name" placeholder="e.g., Equipment Store">
                            </div>
                            <div class="form-group">
                                <label>Area</label>
                                <div class="inline-input">
                                    <input type="number" class="form-control st-area" placeholder="Area" min="0" step="0.01">
                                    <span>Sq. Ft.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Capacity</label>
                                <div class="inline-input">
                                    <input type="number" class="form-control st-capacity" placeholder="Capacity" min="0">
                                    <span id="st-unit-label-${storeEntryCounter}">Units</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Unit</label>
                                <select class="form-control st-unit" onchange="updateStUnitLabel(this,${storeEntryCounter})">
                                    <option value="units">Units</option>
                                    <option value="kg">Kg</option>
                                    <option value="liters">Liters</option>
                                    <option value="cubic_m">Cubic M</option>
                                    <option value="boxes">Boxes</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-row" style="grid-template-columns:1fr 1fr;gap:0.75rem;margin-top:0.5rem;">
                            <div class="form-group">
                                <label>Storage Type</label>
                                <select class="form-control st-type">
                                    <option value="general">General</option>
                                    <option value="cold">Cold Storage</option>
                                    <option value="chemical">Chemical</option>
                                    <option value="equipment">Equipment</option>
                                    <option value="food">Food</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Temp Controlled</label>
                                <select class="form-control st-temp">
                                    <option value="no">No</option>
                                    <option value="yes">Yes</option>
                                </select>
                            </div>
                        </div>
                        <div style="text-align:right;margin-top:0.5rem;">
                            <button type="button" class="btn-outline-danger" onclick="removeCard('store-entry-${storeEntryCounter}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
        }
        html += '</div>';
        container.innerHTML = html;
    }

    function updateStUnitLabel(sel, counter) {
        const l = document.getElementById(`st-unit-label-${counter}`);
        if (l) l.textContent = sel.options[sel.selectedIndex].text;
    }

    // ==========================================
    // AUDITORIUM GENERATORS
    // ==========================================
    function generateAuditoriumEntries() {
        const count = parseInt(document.getElementById('auditorium-count').value) || 0;
        const container = document.getElementById('auditorium-entries-container');
        container.innerHTML = '';
        if (count <= 0) return;
        let html = '<div class="row">';
        for (let i = 1; i <= count; i++) {
            auditoriumCounter++;
            html += `
                <div class="col-lg-6 mb-3">
                    <div class="multi-entry-card" id="auditorium-entry-${auditoriumCounter}">
                        <div class="entry-badge">Auditorium #${i}</div>
                        <div class="card-row" style="grid-template-columns:1fr 1fr 1fr;gap:0.75rem;margin-top:0.5rem;">
                            <div class="form-group">
                                <label>Name (optional)</label>
                                <input type="text" class="form-control aud-name" placeholder="e.g., Main Hall">
                            </div>
                            <div class="form-group">
                                <label>Area</label>
                                <div class="inline-input">
                                    <input type="number" class="form-control aud-area" placeholder="Area" min="0" step="0.01">
                                    <span>Sq. Ft.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Seating Capacity</label>
                                <input type="number" class="form-control aud-capacity" placeholder="Seats" min="0">
                            </div>
                        </div>
                        <div class="card-row" style="grid-template-columns:1fr 1fr;gap:0.75rem;margin-top:0.5rem;">
                            <div class="form-group">
                                <label>Stage</label>
                                <select class="form-control aud-stage">
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>
                        <div style="text-align:right;margin-top:0.5rem;">
                            <button type="button" class="btn-outline-danger" onclick="removeCard('auditorium-entry-${auditoriumCounter}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
        }
        html += '</div>';
        container.innerHTML = html;
    }

    // ==========================================
    // WASHROOM GENERATORS
    // ==========================================
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
        for (let i = 1; i <= maleCount; i++) { counter++; html += generateWashroomCard(counter, 'male', `Male Washroom ${i}`); }
        for (let i = 1; i <= femaleCount; i++) { counter++; html += generateWashroomCard(counter, 'female', `Female Washroom ${i}`); }
        for (let i = 1; i <= unisexCount; i++) { counter++; html += generateWashroomCard(counter, 'unisex', `Unisex Washroom ${i}`); }
        html += '</div>';
        container.innerHTML = html;
        washroomCounter = counter;
    }

    function generateWashroomCard(index, type, label) {
        const typeBadgeClass = type === 'male' ? 'male' : (type === 'female' ? 'female' : 'unisex');
        const typeIcon = type === 'male' ? 'fa-mars' : (type === 'female' ? 'fa-venus' : 'fa-venus-mars');
        const typeLabel = type.charAt(0).toUpperCase() + type.slice(1);
        const urinalField = (type === 'male' || type === 'unisex') ? `
            <div class="form-group">
                <label><i class="fas fa-toilet"></i> Urinals</label>
                <input type="number" id="washroom-${index}-urinals" class="form-control" min="0" value="2" placeholder="Urinals">
            </div>
        ` : '';
        return `
            <div class="col-lg-6 mb-3">
                <div class="washroom-detail-card" id="washroom-card-${index}" data-washroom-index="${index}">
                    <div class="washroom-badge">Washroom #${index}</div>
                    <span class="washroom-type-badge ${typeBadgeClass}"><i class="fas ${typeIcon}"></i> ${typeLabel}</span>
                    <div class="form-group" style="margin-top:0.5rem;">
                        <label>Washroom Name</label>
                        <input type="text" id="washroom-${index}-name" class="form-control" value="${label}" placeholder="e.g., Ground Floor Male">
                    </div>
                    <div class="washroom-row">
                        <div class="form-group">
                            <label><i class="fas fa-toilet"></i> Toilets</label>
                            <input type="number" id="washroom-${index}-toilets" class="form-control" min="0" value="2" placeholder="Toilets">
                        </div>
                        ${urinalField}
                        <div class="form-group">
                            <label><i class="fas fa-sink"></i> Washbasins</label>
                            <input type="number" id="washroom-${index}-washbasins" class="form-control" min="0" value="2" placeholder="Washbasins">
                        </div>
                    </div>
                    <div class="washroom-row" style="margin-top:0.5rem;">
                        <div class="form-group">
                            <label>Area (sq. ft.)</label>
                            <input type="number" id="washroom-${index}-area" class="form-control" min="0" step="0.01" value="50" placeholder="Area">
                        </div>
                        <div class="form-group">
                            <label>Capacity (Persons)</label>
                            <input type="number" id="washroom-${index}-capacity" class="form-control" min="0" value="4" placeholder="Capacity">
                        </div>
                    </div>
                    <div class="washroom-remove-btn">
                        <button type="button" class="btn-outline-danger btn-sm" onclick="removeWashroomCard(${index})">
                            <i class="fas fa-trash"></i> Remove
                        </button>
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

    // ==========================================
    // SAVE CAMPUS INFRASTRUCTURE
    // ==========================================
    function saveCampusInfrastructure() {
        const buildingId = document.getElementById('building-id')?.value;
        if (!buildingId) {
            showToast('Building ID not found', 'error');
            return;
        }
        
        const data = {
            area_value: parseFloat(document.getElementById('area-value')?.value) || 0,
            area_unit: document.getElementById('area-unit')?.value || 'sq_ft',
            number_of_blocks: parseInt(document.getElementById('num-of-blocks')?.value) || 1,
            _token: CSRF_TOKEN,
            _method: 'PUT'
        };
        
        const btn = event?.target;
        const originalText = btn?.innerHTML || 'Save Campus';
        if (btn) {
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
            btn.disabled = true;
        }
        
        $.ajax({
            url: '/buildings/' + buildingId,
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    showToast(response.message || 'Campus saved successfully!', 'success');
                    document.getElementById('continueToBlocksBtn').style.display = 'inline-flex';
                } else {
                    showToast(response.message || 'Failed to save campus', 'error');
                    if (response.errors) {
                        console.error('Validation errors:', response.errors);
                    }
                }
            },
            error: function(xhr) {
                let message = 'An error occurred while saving.';
                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                showToast(message, 'error');
                console.error('Error saving:', xhr);
            },
            complete: function() {
                if (btn) {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            }
        });
    }

    // ==========================================
    // INITIALIZATION
    // ==========================================
    document.addEventListener('DOMContentLoaded', function() {
        // Set campus area unit
        campusAreaUnit = document.getElementById('area-unit').value;
        
        // Generate default entries
        generateBasementParkingEntries();
        generateOpenParkingEntries();
        generateIndoorPlaygroundEntries();
        generateOutdoorPlaygroundEntries();
        generateIndoorPoolEntries();
        generateOutdoorPoolEntries();
        generateWarehouseEntries();
        generateStoreEntries();
        generateAuditoriumEntries();
        
        // Set up enter key handler for inputs
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.closest('.dynamic-card')) {
                e.preventDefault();
            }
        });
    });
    </script>
</body>
</html>
@endsection