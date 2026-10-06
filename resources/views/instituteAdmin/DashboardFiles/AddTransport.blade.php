@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --primary-light: rgba(67, 97, 238, 0.1);
        --success-gradient: linear-gradient(135deg, #10b981, #059669);
        --success-color: #10b981;
        --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        --danger-color: #ef4444;
        --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
        --purple-gradient: linear-gradient(135deg, #8b5cf6, #7c3aed);
        --dark: #1f2937;
        --gray: #6b7280;
        --light-gray: #f9fafb;
        --border: #e5e7eb;
        --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
    }

    /* Page Header */
    .page-header {
        background: var(--primary-gradient);
        padding: 25px 30px;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .page-header h3 {
        color: white;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        font-size: 1.8rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    }

    .page-header h3 i {
        background: rgba(255, 255, 255, 0.2);
        padding: 15px;
        border-radius: 12px;
        margin-right: 15px;
        font-size: 2rem;
    }

    .view-btn {
        padding: 12px 24px;
        background: var(--primary-gradient);
        border: none;
        color: #fff !important;
        cursor: pointer;
        border-radius: 10px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .view-btn:hover {
        transform: translateY(-3px);
        text-decoration: none;
    }

    /* Main Card */
    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: none;
        overflow: hidden;
        animation: fadeInUp 0.6s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card-header {
        background: var(--primary-gradient);
        color: white;
        border-bottom: none;
        padding: 20px 25px;
    }

    .card-header h3 {
        margin: 0;
        font-weight: 600;
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.5rem;
    }

    .card-header h3 i {
        font-size: 1.8rem;
    }

    .card-body {
        padding: 30px;
    }

    /* Section Box */
    .section-box {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px solid var(--border);
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 25px;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .section-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-gradient);
    }

    .section-box:hover {
        border-color: var(--primary-color);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.1);
        transform: translateY(-2px);
    }

    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--border);
    }

    .section-icon {
        font-size: 28px;
        margin-right: 15px;
        background: var(--primary-gradient);
        color: white;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
    }

    .section-title {
        margin: 0;
        color: var(--primary-color);
        font-weight: 700;
        font-size: 1.2rem;
    }

    /* Helper Section */
    .helper-section {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .helper-section:hover {
        border-color: var(--primary-color);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.1);
        transform: translateY(-2px);
    }

    .helper-section .remove-helper {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border: none;
        color: var(--danger-color);
        cursor: pointer;
        font-size: 1.2rem;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .helper-section .remove-helper:hover:not(:disabled) {
        background: var(--danger-gradient);
        color: white;
        transform: scale(1.1);
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
    }

    .helper-section .remove-helper:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Stop Card */
    .stop-card {
        background: white;
        border: 2px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .stop-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: transparent;
        transition: all 0.3s;
    }

    .stop-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.1);
    }

    .stop-card:hover::before {
        background: var(--primary-gradient);
    }

    .stop-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--border);
    }

    .stop-number {
        display: inline-flex;
        background: var(--primary-gradient);
        color: white;
        width: 35px;
        height: 35px;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-right: 10px;
        font-weight: 700;
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
    }

    .remove-stop {
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 500;
        border: none;
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: var(--danger-color);
        transition: all 0.3s;
    }

    .remove-stop:hover {
        background: var(--danger-gradient);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
    }

    /* No Stops Message */
    .no-stops-message {
        text-align: center;
        padding: 40px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px dashed var(--primary-color);
        border-radius: 12px;
        margin-bottom: 20px;
        color: var(--gray);
    }

    .no-stops-message i {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .no-stops-message h5 {
        color: var(--dark);
        font-weight: 600;
    }

    /* Route Info Card */
    .route-info-card {
        background: linear-gradient(135deg, #e8edff, #dbe4ff);
        border-left: 4px solid var(--primary-color);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        border: 1px solid var(--primary-color);
    }

    .route-info-card h6 {
        color: var(--primary-color);
        font-weight: 700;
    }

    .route-info-card p {
        color: var(--dark);
    }

    /* Form Controls */
    .form-label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 8px;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        letter-spacing: 0.3px;
    }

    .form-label i {
        margin-right: 8px;
        color: var(--primary-color);
        font-size: 1rem;
    }

    .form-control,
    .form-select {
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s;
        background: white;
        width: 100%;
        height: 45px;
        padding: 0 14px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
        transform: translateY(-2px);
    }

    .form-control:hover,
    .form-select:hover {
        border-color: var(--secondary-color);
    }

    .form-control[readonly],
    .form-control.bg-light {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9) !important;
        border-color: var(--border);
        color: var(--dark);
        cursor: default;
    }

    .input-group-text {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px solid var(--border);
        border-radius: 10px 0 0 10px;
        color: var(--primary-color);
        font-weight: 600;
    }

    /* Time Picker Group */
    .time-picker-group {
        display: flex;
        gap: 5px;
        align-items: center;
    }

    .time-input {
        flex: 1;
    }

    .am-pm-select {
        width: 80px;
    }

    /* Alert Messages */
    .alert {
        border: none;
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 20px;
        animation: slideIn 0.5s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #166534;
        border-left: 4px solid var(--success-color);
    }

    .alert-danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        border-left: 4px solid var(--danger-color);
    }

    .alert-info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
        border-left: 4px solid var(--primary-color);
    }

    .alert ul {
        margin-left: 20px;
    }

    .alert .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* Stop Fee Section */
    .stop-fee-section {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        border: 2px solid var(--border);
    }

    .stop-fee-header {
        background: var(--primary-gradient);
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .stop-fee-row {
        margin-bottom: 20px;
        padding: 20px;
        background: white;
        border-radius: 10px;
        border: 2px solid var(--border);
        transition: all 0.3s;
    }

    .stop-fee-row:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.1);
    }

    /* Buttons */
    .btn {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95rem;
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .btn-primary {
        background: var(--primary-gradient);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-primary:hover::before {
        left: 100%;
    }

    .btn-success {
        background: var(--success-gradient);
        color: white;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #059669, #10b981);
    }

    .btn-outline-primary {
        background: transparent;
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
    }

    .btn-outline-primary:hover {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
    }

    .btn-outline-danger {
        background: transparent;
        border: 2px solid var(--danger-color);
        color: var(--danger-color);
    }

    .btn-outline-danger:hover {
        background: var(--danger-gradient);
        color: white;
        border-color: transparent;
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 0.85rem;
    }

    /* Loading Spinner */
    .loading-spinner {
        display: none;
        text-align: center;
        padding: 30px;
    }

    .loading-spinner i {
        color: var(--primary-color);
    }

    .fa-spinner,
    .fa-spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    /* Form Switch */
    .form-switch {
        padding-left: 2.5rem;
    }

    .form-switch .form-check-input {
        width: 3rem;
        height: 1.5rem;
        cursor: pointer;
        border: 2px solid var(--border);
        background-color: white;
    }

    .form-switch .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .form-switch .form-check-label {
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-switch .form-check-label i {
        color: var(--primary-color);
    }

    /* Disabled Section */
    .section-disabled {
        opacity: 0.7;
        pointer-events: none;
        position: relative;
    }

    .section-disabled::after {
        content: "Select a route first";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 15px 30px;
        border-radius: 50px;
        font-size: 16px;
        font-weight: 600;
        z-index: 1000;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        white-space: nowrap;
    }

    /* Cards */
    .card.shadow-sm {
        background: white;
        border-radius: 16px;
        border: 1px solid var(--border);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .card.shadow-sm .card-title {
        color: var(--primary-color);
        font-weight: 600;
    }

    /* Text utilities */
    .text-muted {
        color: var(--gray) !important;
    }

    .text-success {
        color: var(--success-color) !important;
    }

    .fw-semibold {
        font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 15px;
            text-align: center;
            padding: 20px;
        }

        .page-header h3 {
            font-size: 1.5rem;
        }

        .view-btn {
            width: 100%;
            justify-content: center;
        }

        .card-body {
            padding: 20px;
        }

        .section-box {
            padding: 20px;
        }

        .form-control,
        .form-select,
        .btn {
            font-size: 0.9rem;
        }

        .time-picker-group {
            flex-wrap: wrap;
        }

        .am-pm-select {
            width: 100%;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 10px;
        }

        .btn {
            width: 100%;
        }

        .section-disabled::after {
            font-size: 14px;
            padding: 10px 20px;
            white-space: normal;
            width: 80%;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .page-header h3 {
            font-size: 1.2rem;
        }

        .card-body {
            padding: 15px;
        }

        .section-box {
            padding: 15px;
        }

        .row {
            margin: 0;
        }

        [class*="col-"] {
            padding: 5px;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h3>
            <i class="fas fa-bus"></i>
            Add Transport & Fee Structure
        </h3>
        <a href="{{ route('view.transport.details') }}" class="view-btn">
            <i class="fas fa-eye me-1"></i>View Transport Details
        </a>
    </div>

    <div class="main-card">
        <div class="card-header">
            <h3>
                <i class="fas fa-plus-circle"></i>
                New Transport Registration
            </h3>
        </div>
        <div class="card-body">
            <!-- Success/Error Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.transport.store') }}" id="transportFeeForm">
                @csrf

                <!-- Route Selection Section (Always Enabled) -->
                <div class="section-box">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-map"></i>
                        </div>
                        <h5 class="section-title">Select Route</h5>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <label class="form-label">
                                <i class="fas fa-route"></i>
                                Choose Route *
                            </label>
                            <select name="route_id" id="routeSelect" class="form-control" required>
                                <option value="">-- Select a Route --</option>
                                @foreach($routes as $route)
                                <option value="{{ $route->route_reference_id }}">{{ $route->route_name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Select the route for this bus</small>
                        </div>
                        <div class="col-md-4 d-flex align-items-end" style="margin-bottom: 10px;">
                            <a href="{{ route('admin.transport.routes.create') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-plus me-1"></i>Add New Route
                            </a>
                        </div>
                    </div>

                    <!-- Route Information Display (Shown after selection) -->
                    <div id="routeInfoContainer" style="display: none;" class="route-info-card mt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-2"><i class="fas fa-info-circle me-1"></i>Selected Route Details</h6>
                                <p class="mb-1"><strong>Route Name:</strong> <span id="selectedRouteName"></span></p>
                                <p class="mb-1"><strong>Total Stops:</strong> <span id="selectedRouteStops"></span></p>
                            </div>
                            <div class="text-success">
                                <i class="fas fa-check-circle fa-3x"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Loading Spinner -->
                    <div id="routeLoadingSpinner" class="loading-spinner">
                        <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                        <p class="mt-2">Loading route details...</p>
                    </div>
                </div>

                <!-- All Other Sections (Disabled until route is selected) -->
                <div id="allOtherSections" class="section-disabled">
                    <!-- Basic Transport Details -->
                    <div class="section-box">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-truck"></i>
                            </div>
                            <h5 class="section-title">Basic Transport Details</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-hashtag"></i>
                                    Bus Number *
                                </label>
                                <input type="text" name="bus_number" class="form-control" placeholder="e.g. Bus 12A" disabled required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-car"></i>
                                    Vehicle Number *
                                </label>
                                <input type="text" name="vehicle_number" class="form-control" placeholder="e.g. RJ14AB1234" disabled required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user"></i>
                                    Driver Name *
                                </label>
                                <input type="text" name="driver_name" class="form-control" disabled required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-phone"></i>
                                    Driver Contact *
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">+91</span>
                                    <input type="tel" name="driver_contact" class="form-control" pattern="[0-9]{10}"
                                        minlength="10" maxlength="10" placeholder="9876543210" disabled required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-road"></i>
                                    Route Type *
                                </label>
                                <select name="route_type" id="routeTypeSelect" class="form-control" disabled required>
                                    <option value="">Select Route Type</option>
                                    <option value="morning">Morning Pickup Only</option>
                                    <option value="evening">Evening Drop Only</option>
                                    <option value="both">Both Directions (Morning & Evening)</option>
                                </select>
                                <small class="text-muted">Select when this bus operates</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-chair"></i>
                                    Sitting Capacity *
                                </label>
                                <input type="number" name="sitting_capacity" class="form-control" min="1" max="100"
                                    placeholder="e.g. 40" disabled required>
                                <small class="text-muted">Maximum number of students this bus can carry</small>
                            </div>
                        </div>
                    </div>

                    <!-- Overall Timing Section (Dynamically populated) -->
                    <div id="overallTimingSection" class="row mb-3">
                        <!-- Timing fields will be dynamically added here -->
                    </div>

                    <!-- Multiple Helpers -->
                    <div class="section-box">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h5 class="section-title">Transport Helpers</h5>
                        </div>

                        <div id="helpersContainer">
                            <div class="helper-section">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0"><i class="fas fa-user me-2" style="color: var(--primary-color);"></i>Helper 1</h6>
                                    <button type="button" class="btn remove-helper" disabled>
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Helper Name *</label>
                                        <input type="text" name="helpers[0][name]" class="form-control helper-field"
                                            placeholder="Helper Name" disabled required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Helper Contact *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">+91</span>
                                            <input type="tel" name="helpers[0][contact]" class="form-control helper-field"
                                                pattern="[0-9]{10}" minlength="10" maxlength="10" placeholder="9876543210"
                                                disabled required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-primary" id="addHelper" disabled>
                                <i class="fas fa-plus me-1"></i>Add Another Helper
                            </button>
                        </div>
                    </div>

                    <!-- Bus Stops & Timings Box -->
                    <div class="section-box">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h5 class="section-title">Bus Stops</h5>
                        </div>

                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            Add bus stops in the order they will be visited. For example:
                            <ul class="mb-0 mt-2">
                                <li><strong>Morning Pickup:</strong> First stop to last stop</li>
                                <li><strong>Evening Drop:</strong> First stop after school to last stop</li>
                                <li><strong>Both Directions:</strong> Add stops once, timings will be set for both pickup and drop</li>
                            </ul>
                        </div>

                        <!-- No stops message -->
                        <div id="noStopsMessage" class="no-stops-message" style="display: block;">
                            <i class="fas fa-map-marker-alt"></i>
                            <h5>No Bus Stops Added Yet</h5>
                            <p class="mb-3">Click the "Add Stop" button below to add your first bus stop</p>
                        </div>

                        <!-- Bus stops container -->
                        <div id="busStopsContainer" class="mb-4">
                            <!-- Bus stops will be added here -->
                        </div>

                        <div class="text-center">
                            <button type="button" class="btn btn-primary" onclick="addBusStop()" disabled id="addStopBtn">
                                <i class="fas fa-plus me-1"></i>Add Stop
                            </button>
                        </div>
                    </div>

                    <!-- Fee Structure Section -->
                    <div class="section-box">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <h5 class="section-title">Fee Structure</h5>
                        </div>

                        <!-- Hidden fee duration field (monthly) -->
                        <input type="hidden" name="fee_duration" value="monthly">

                        <!-- Academic Year -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="fas fa-calendar"></i>
                                    Academic Year *
                                </label>
                                <select name="academic_year" class="form-control" disabled required>
                                    <option value="">Select Academic Year</option>
                                    @php
                                    $currentYear = date('Y');
                                    for($i = 0; $i < 6; $i++) { 
                                        $year = $currentYear + $i; 
                                        $academicYear = "{$year}-" . ($year + 1); 
                                        echo "<option value='{$academicYear}'>{$academicYear}</option>"; 
                                    } 
                                    @endphp
                                </select>
                            </div>
                        </div>

                        <!-- Monthly Fee Input -->
                        <div class="card shadow-sm p-4 mb-4">
                            <h6 class="mb-3" style="color: var(--primary-color);">
                                <i class="fas fa-money-bill-wave me-2"></i>Transport Fee
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Monthly Transport Fee *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" name="monthly_fee" id="monthly_fee" class="form-control"
                                            min="0" step="0.01" placeholder="Enter monthly transport fee" disabled required>
                                    </div>
                                    <small class="text-muted">Fee per month for using the transport service</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Annual Fee (Auto-calculated)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" id="annual_fee_calculated" class="form-control" readonly disabled>
                                    </div>
                                    <small class="text-muted">Monthly fee × 12 months</small>
                                </div>
                            </div>
                        </div>

                        <!-- Stop-wise Fees -->
                        <div class="stop-fee-section" id="stopFeeSection" style="display: none;">
                            <div class="stop-fee-header">
                                <i class="fas fa-map-marker-alt me-2"></i>Individual Stop Fees (Optional)
                            </div>
                            <p class="text-muted mb-3">
                                Optionally set different fees for each bus stop. If not set, all stops will use the monthly fee above.
                            </p>
                            <div id="stopFeesContainer">
                                <!-- Stop fees will be dynamically added here -->
                            </div>
                        </div>

                        <!-- Fee Distribution Toggle -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="enableStopFees" disabled>
                                    <label class="form-check-label fw-semibold" for="enableStopFees">
                                        <i class="fas fa-sliders-h me-1"></i>
                                        Set different fees for each stop
                                    </label>
                                    <small class="text-muted d-block mt-1">
                                        If enabled, you can set individual fees for each bus stop. Otherwise, all stops will have the same monthly fee.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Late Payment Fee -->
                        <div class="card shadow-sm p-4 mb-4">
                            <h6 class="mb-3" style="color: var(--primary-color);">
                                <i class="fas fa-clock me-2"></i>Late Payment Fee (Optional)
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Late Fee Type</label>
                                    <select name="late_fee_type" id="late_fee_type" class="form-control" disabled>
                                        <option value="">No Late Fee</option>
                                        <option value="fixed">Fixed Amount</option>
                                        <option value="percentage">Percentage</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Late Fee Value</label>
                                    <div class="input-group">
                                        <input type="number" name="late_fee_value" id="late_fee_value" class="form-control"
                                            min="0" step="0.01" placeholder="Enter amount/percentage" disabled>
                                        <span class="input-group-text" id="late_fee_suffix">₹</span>
                                    </div>
                                    <small class="text-muted" id="late_fee_note">
                                        Enter fixed amount or percentage for late payments
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Partial Payment Fee -->
                        <div class="card shadow-sm p-4 mb-4">
                            <h6 class="mb-3" style="color: var(--primary-color);">
                                <i class="fas fa-percentage me-2"></i>Partial Payment Fee (Optional)
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Partial Fee Type</label>
                                    <select name="partially_fee_type" id="partially_fee_type" class="form-control" disabled>
                                        <option value="">No Partial Fee</option>
                                        <option value="fixed">Fixed Amount</option>
                                        <option value="percentage">Percentage</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Partial Fee Value</label>
                                    <div class="input-group">
                                        <input type="number" name="partially_fee_value" id="partially_fee_value"
                                            class="form-control" min="0" step="0.01" placeholder="Enter amount/percentage" disabled>
                                        <span class="input-group-text" id="partially_fee_suffix">₹</span>
                                    </div>
                                    <small class="text-muted" id="partially_fee_note">
                                        Additional charge for partial payments
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden field for stop IDs -->
                    <div id="stopIdsContainer"></div>

                    <!-- Status -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked disabled>
                            <label class="form-check-label" for="is_active">
                                <i class="fas fa-power-off me-1"></i>
                                Make this fee structure active
                            </label>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success px-5" id="submitBtn" disabled>
                        <i class="fas fa-save me-2"></i>Save Transport & Fee Structure
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let stopCount = 0;
    let helperCount = 1;
    let stopIds = {};
    let selectedRouteData = null;

    // Function to generate a random alphanumeric ID
    function generateStopId() {
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = 'STP_';
        for (let i = 0; i < 8; i++) {
            result += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        return result;
    }

    // Function to enable/disable all sections based on route selection
    function toggleSections(enable) {
        const sections = $('#allOtherSections');
        const inputs = sections.find('input, select, button, textarea');
        const submitBtn = $('#submitBtn');
        const addStopBtn = $('#addStopBtn');
        const addHelperBtn = $('#addHelper');
        
        if (enable) {
            sections.removeClass('section-disabled');
            inputs.prop('disabled', false);
            submitBtn.prop('disabled', false);
            addStopBtn.prop('disabled', false);
            addHelperBtn.prop('disabled', false);

            // Special handling for helper remove button
            if (helperCount === 1) {
                $('.remove-helper').first().prop('disabled', true);
            } else {
                $('.remove-helper').prop('disabled', false);
            }
            
            // Initialize overall timing after enabling
            updateOverallTiming();
        } else {
            sections.addClass('section-disabled');
            inputs.prop('disabled', true);
            submitBtn.prop('disabled', true);
            addStopBtn.prop('disabled', true);
            addHelperBtn.prop('disabled', true);
            $('.remove-helper').prop('disabled', true);
        }
    }

    // Function to fetch route details
    function fetchRouteDetails(routeId) {
        if (!routeId) {
            $('#routeInfoContainer').hide();
            toggleSections(false);
            return;
        }

        $('#routeLoadingSpinner').show();

        // Use direct URL instead of named route to avoid parameter issues
        const url = '/admin/transport/get-route-details/' + routeId;

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#routeLoadingSpinner').hide();

                if (response.success) {
                    selectedRouteData = response.data;

                    // Display route info
                    $('#selectedRouteName').text(selectedRouteData.route_name);
                    $('#selectedRouteStops').text(selectedRouteData.stops_count || 0);

                    $('#routeInfoContainer').show();

                    // Enable all sections
                    toggleSections(true);
                    
                    // Update overall timing based on selected route type
                    updateOverallTiming();
                } else {
                    alert('Error loading route details');
                    toggleSections(false);
                }
            },
            error: function(xhr) {
                $('#routeLoadingSpinner').hide();
                alert('Error fetching route details: ' + (xhr.responseJSON?.message || 'Unknown error'));
                toggleSections(false);
            }
        });
    }

    // Route selection change handler
    $('#routeSelect').on('change', function() {
        const routeId = $(this).val();
        fetchRouteDetails(routeId);
    });

    // Route type change handler
    $('#routeTypeSelect').on('change', function() {
        // Only update if sections are enabled
        if (!$('#allOtherSections').hasClass('section-disabled')) {
            updateOverallTiming();
            if (stopCount > 0) {
                if (confirm('Changing route type will reset all stop timings. Do you want to continue?')) {
                    updateExistingStopsTiming();
                } else {
                    // Revert the selection
                    $(this).val($(this).data('previous-value'));
                }
            }
        }
    });

    // Store previous route type value
    $('#routeTypeSelect').on('focus', function() {
        $(this).data('previous-value', $(this).val());
    });

    // Update overall timing section based on route type
    function updateOverallTiming() {
        const routeType = $('#routeTypeSelect').val();
        let timingHtml = '';
        
        if (routeType === 'morning') {
            timingHtml = `
                <div class="col-md-6">
                    <label class="form-label">Estimated Start Time *</label>
                    <small class="text-muted d-block mb-2">When the bus starts its morning route</small>
                    <div class="time-picker-group">
                        <input type="number" name="estimated_start_hour" 
                               class="form-control time-input" min="1" max="12" 
                               placeholder="HH" value="7" required>
                        <span>:</span>
                        <input type="number" name="estimated_start_minute" 
                               class="form-control time-input" min="0" max="59" 
                               placeholder="MM" value="30" required>
                        <select name="estimated_start_ampm" class="form-select am-pm-select" required>
                            <option value="AM" selected>AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="estimated_end_hour" value="">
                <input type="hidden" name="estimated_end_minute" value="">
                <input type="hidden" name="estimated_end_ampm" value="">
            `;
        } else if (routeType === 'evening') {
            timingHtml = `
                <div class="col-md-6">
                    <label class="form-label">Estimated End Time *</label>
                    <small class="text-muted d-block mb-2">When the bus starts its evening route</small>
                    <div class="time-picker-group">
                        <input type="number" name="estimated_end_hour" 
                               class="form-control time-input" min="1" max="12" 
                               placeholder="HH" value="3" required>
                        <span>:</span>
                        <input type="number" name="estimated_end_minute" 
                               class="form-control time-input" min="0" max="59" 
                               placeholder="MM" value="30" required>
                        <select name="estimated_end_ampm" class="form-select am-pm-select" required>
                            <option value="AM">AM</option>
                            <option value="PM" selected>PM</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="estimated_start_hour" value="">
                <input type="hidden" name="estimated_start_minute" value="">
                <input type="hidden" name="estimated_start_ampm" value="">
            `;
        } else if (routeType === 'both') {
            timingHtml = `
                <div class="col-md-6 mb-3">
                    <label class="form-label">Estimated Start Time *</label>
                    <small class="text-muted d-block mb-2">When the bus starts its morning route</small>
                    <div class="time-picker-group">
                        <input type="number" name="estimated_start_hour" 
                               class="form-control time-input" min="1" max="12" 
                               placeholder="HH" value="7" required>
                        <span>:</span>
                        <input type="number" name="estimated_start_minute" 
                               class="form-control time-input" min="0" max="59" 
                               placeholder="MM" value="30" required>
                        <select name="estimated_start_ampm" class="form-select am-pm-select" required>
                            <option value="AM" selected>AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Estimated End Time *</label>
                    <small class="text-muted d-block mb-2">When the bus starts its evening route</small>
                    <div class="time-picker-group">
                        <input type="number" name="estimated_end_hour" 
                               class="form-control time-input" min="1" max="12" 
                               placeholder="HH" value="3" required>
                        <span>:</span>
                        <input type="number" name="estimated_end_minute" 
                               class="form-control time-input" min="0" max="59" 
                               placeholder="MM" value="30" required>
                        <select name="estimated_end_ampm" class="form-select am-pm-select" required>
                            <option value="AM">AM</option>
                            <option value="PM" selected>PM</option>
                        </select>
                    </div>
                </div>
            `;
        }
        
        $('#overallTimingSection').html(timingHtml);
    }

    // Helper Management
    $('#addHelper').on('click', function() {
        if (helperCount >= 5) {
            alert('Maximum 5 helpers allowed');
            return;
        }
        
        const helperHtml = `
            <div class="helper-section border p-3 rounded mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Helper ${helperCount + 1}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-helper">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Helper Name *</label>
                        <input type="text" name="helpers[${helperCount}][name]" class="form-control helper-field" 
                               placeholder="Helper Name" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Helper Contact *</label>
                        <div class="input-group">
                            <span class="input-group-text">+91</span>
                            <input type="tel" name="helpers[${helperCount}][contact]" class="form-control helper-field"
                                   pattern="[0-9]{10}" minlength="10" maxlength="10"
                                   placeholder="9876543210" required>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('#helpersContainer').append(helperHtml);
        helperCount++;
        
        // Enable remove button for first helper
        $('.remove-helper').first().prop('disabled', false);
    });

    $(document).on('click', '.remove-helper', function() {
        if (helperCount <= 1) {
            alert('At least one helper is required');
            return;
        }
        
        $(this).closest('.helper-section').remove();
        helperCount--;
        
        // Update helper numbers
        $('.helper-section').each(function(index) {
            $(this).find('h6').text(`Helper ${index + 1}`);
        });
        
        // Disable remove button for first helper if only one left
        if (helperCount === 1) {
            $('.remove-helper').first().prop('disabled', true);
        }
    });

    // Calculate Annual Fee from Monthly
    function calculateAnnualFee() {
        const monthlyFee = parseFloat($('#monthly_fee').val()) || 0;
        const annualFee = monthlyFee * 12;
        $('#annual_fee_calculated').val(annualFee.toFixed(2));
    }

    // Bus Stops Management - Create stop card with timing
    function addBusStop() {
        if (stopCount >= 20) {
            alert('Maximum 20 stops allowed');
            return;
        }

        const stopId = generateStopId();
        stopIds[stopCount] = stopId;
        
        const routeType = $('#routeTypeSelect').val();
        const stopNumber = stopCount + 1;
        
        // Create timing HTML based on route type
        let timingHtml = '';
        if (routeType === 'morning') {
            timingHtml = `
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Pickup Time *</label>
                        <div class="time-picker-group">
                            <input type="number" name="stop_timings[${stopCount}][pickup_hour]" 
                                   class="form-control time-input" min="1" max="12" 
                                   placeholder="HH" required>
                            <span>:</span>
                            <input type="number" name="stop_timings[${stopCount}][pickup_minute]" 
                                   class="form-control time-input" min="0" max="59" 
                                   placeholder="MM" required>
                            <select name="stop_timings[${stopCount}][pickup_ampm]" 
                                    class="form-select am-pm-select" required>
                                <option value="AM">AM</option>
                                <option value="PM">PM</option>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="stop_timings[${stopCount}][stop_id]" value="${stopId}">
            `;
        } else if (routeType === 'evening') {
            timingHtml = `
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Drop Time *</label>
                        <div class="time-picker-group">
                            <input type="number" name="stop_timings[${stopCount}][drop_hour]" 
                                   class="form-control time-input" min="1" max="12" 
                                   placeholder="HH" required>
                            <span>:</span>
                            <input type="number" name="stop_timings[${stopCount}][drop_minute]" 
                                   class="form-control time-input" min="0" max="59" 
                                   placeholder="MM" required>
                            <select name="stop_timings[${stopCount}][drop_ampm]" 
                                    class="form-select am-pm-select" required>
                                <option value="AM">AM</option>
                                <option value="PM">PM</option>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="stop_timings[${stopCount}][stop_id]" value="${stopId}">
            `;
        } else if (routeType === 'both') {
            timingHtml = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Morning Pickup Time *</label>
                        <div class="time-picker-group">
                            <input type="number" name="stop_timings[${stopCount}][pickup_hour]" 
                                   class="form-control time-input" min="1" max="12" 
                                   placeholder="HH" required>
                            <span>:</span>
                            <input type="number" name="stop_timings[${stopCount}][pickup_minute]" 
                                   class="form-control time-input" min="0" max="59" 
                                   placeholder="MM" required>
                            <select name="stop_timings[${stopCount}][pickup_ampm]" 
                                    class="form-select am-pm-select" required>
                                <option value="AM">AM</option>
                                <option value="PM">PM</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Evening Drop Time *</label>
                        <div class="time-picker-group">
                            <input type="number" name="stop_timings[${stopCount}][drop_hour]" 
                                   class="form-control time-input" min="1" max="12" 
                                   placeholder="HH" required>
                            <span>:</span>
                            <input type="number" name="stop_timings[${stopCount}][drop_minute]" 
                                   class="form-control time-input" min="0" max="59" 
                                   placeholder="MM" required>
                            <select name="stop_timings[${stopCount}][drop_ampm]" 
                                    class="form-select am-pm-select" required>
                                <option value="AM">AM</option>
                                <option value="PM">PM</option>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="stop_timings[${stopCount}][stop_id]" value="${stopId}">
            `;
        }

        const stopHtml = `
            <div class="stop-card" id="stop-${stopCount}" data-stop-id="${stopId}">
                <div class="stop-header">
                    <div>
                        <span class="stop-number">${stopNumber}</span>
                        <h6 class="d-inline-block mb-0">Stop ${stopNumber}</h6>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-stop" onclick="removeStop(${stopCount})">
                        <i class="fas fa-times"></i> Remove
                    </button>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Stop Name/Address *</label>
                        <input type="text" name="bus_stops[${stopCount}]" 
                               class="form-control bus-stop-field" 
                               placeholder="e.g., Sector 17 Bus Stand, Chandigarh"
                               data-index="${stopCount}"
                               required>
                    </div>
                </div>
                
                ${timingHtml}
            </div>
        `;
        
        // Hide no stops message
        $('#noStopsMessage').hide();
        
        // Add stop to container
        $('#busStopsContainer').append(stopHtml);
        
        // Add hidden input for stop ID
        $('#stopIdsContainer').append(`
            <input type="hidden" name="stop_ids[${stopCount}]" value="${stopId}" class="stop-id-field" data-index="${stopCount}">
        `);
        
        // Add stop fee input if enabled
        if ($('#enableStopFees').is(':checked')) {
            addStopFeeInput(stopCount, stopId);
        }
        
        stopCount++;
    }

    function removeStop(index) {
        if (stopCount <= 1) {
            alert('At least one stop is required');
            return;
        }
        
        const stopId = $(`#stop-${index}`).data('stop-id');
        
        // Remove stop card
        $(`#stop-${index}`).remove();
        $(`.stop-id-field[value="${stopId}"]`).remove();
        
        // Remove from stopIds object
        delete stopIds[index];
        stopCount--;
        
        // Re-index all stops
        reindexStops();
        
        // Update stop fees if enabled
        updateStopFees();
        
        // Show no stops message if no stops left
        if (stopCount === 0) {
            $('#noStopsMessage').show();
        }
    }

    function reindexStops() {
        let newIndex = 0;
        $('.stop-card').each(function() {
            const currentId = $(this).data('stop-id');
            $(this).attr('id', `stop-${newIndex}`);
            $(this).find('.stop-number').text(newIndex + 1);
            $(this).find('h6').text(`Stop ${newIndex + 1}`);
            
            // Update stop field
            const stopField = $(this).find('.bus-stop-field');
            stopField.attr('name', `bus_stops[${newIndex}]`);
            stopField.data('index', newIndex);
            
            // Update timing fields
            $(this).find('input[type="number"], select').each(function() {
                const name = $(this).attr('name');
                if (name && name.includes('stop_timings')) {
                    const newName = name.replace(/stop_timings\[\d+\]/, `stop_timings[${newIndex}]`);
                    $(this).attr('name', newName);
                }
            });
            
            // Update hidden stop_id
            $(this).find('input[name*="stop_id"]').val(currentId);
            
            // Update hidden stop_ids input
            const hiddenInput = $(`.stop-id-field[value="${currentId}"]`);
            hiddenInput.attr('name', `stop_ids[${newIndex}]`);
            hiddenInput.data('index', newIndex);
            
            // Update stopIds mapping
            stopIds[newIndex] = currentId;
            
            newIndex++;
        });
        
        // Remove any extra stopIds entries
        for (let i = newIndex; i < 20; i++) {
            delete stopIds[i];
        }
        
        stopCount = newIndex;
    }

    // Update stop timings when route type changes
    function updateExistingStopsTiming() {
        const routeType = $('#routeTypeSelect').val();
        
        $('.stop-card').each(function() {
            const index = $(this).find('.bus-stop-field').data('index');
            const stopId = $(this).data('stop-id');
            
            // Remove old timing fields
            $(this).find('.row:last-child').remove();
            $(this).find('input[name*="stop_id"]').remove();
            
            // Create new timing HTML based on route type
            let timingHtml = '';
            if (routeType === 'morning') {
                timingHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Pickup Time *</label>
                            <div class="time-picker-group">
                                <input type="number" name="stop_timings[${index}][pickup_hour]" 
                                       class="form-control time-input" min="1" max="12" 
                                       placeholder="HH" required>
                                <span>:</span>
                                <input type="number" name="stop_timings[${index}][pickup_minute]" 
                                       class="form-control time-input" min="0" max="59" 
                                       placeholder="MM" required>
                                <select name="stop_timings[${index}][pickup_ampm]" 
                                        class="form-select am-pm-select" required>
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="stop_timings[${index}][stop_id]" value="${stopId}">
                `;
            } else if (routeType === 'evening') {
                timingHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Drop Time *</label>
                            <div class="time-picker-group">
                                <input type="number" name="stop_timings[${index}][drop_hour]" 
                                       class="form-control time-input" min="1" max="12" 
                                       placeholder="HH" required>
                                <span>:</span>
                                <input type="number" name="stop_timings[${index}][drop_minute]" 
                                       class="form-control time-input" min="0" max="59" 
                                       placeholder="MM" required>
                                <select name="stop_timings[${index}][drop_ampm]" 
                                        class="form-select am-pm-select" required>
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="stop_timings[${index}][stop_id]" value="${stopId}">
                `;
            } else if (routeType === 'both') {
                timingHtml = `
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Morning Pickup Time *</label>
                            <div class="time-picker-group">
                                <input type="number" name="stop_timings[${index}][pickup_hour]" 
                                       class="form-control time-input" min="1" max="12" 
                                       placeholder="HH" required>
                                <span>:</span>
                                <input type="number" name="stop_timings[${index}][pickup_minute]" 
                                       class="form-control time-input" min="0" max="59" 
                                       placeholder="MM" required>
                                <select name="stop_timings[${index}][pickup_ampm]" 
                                        class="form-select am-pm-select" required>
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Evening Drop Time *</label>
                            <div class="time-picker-group">
                                <input type="number" name="stop_timings[${index}][drop_hour]" 
                                       class="form-control time-input" min="1" max="12" 
                                       placeholder="HH" required>
                                <span>:</span>
                                <input type="number" name="stop_timings[${index}][drop_minute]" 
                                       class="form-control time-input" min="0" max="59" 
                                       placeholder="MM" required>
                                <select name="stop_timings[${index}][drop_ampm]" 
                                        class="form-select am-pm-select" required>
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="stop_timings[${index}][stop_id]" value="${stopId}">
                `;
            }
            
            $(this).append(timingHtml);
        });
    }

    // Stop Fees Management
    function addStopFeeInput(stopIndex, stopId) {
        const container = $('#stopFeesContainer');
        const stopName = $(`.bus-stop-field[data-index="${stopIndex}"]`).val() || `Stop ${stopIndex + 1}`;
        
        const feeHtml = `
            <div class="stop-fee-row">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <label class="form-label fw-semibold">Stop ${stopIndex + 1}</label>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Stop Name</label>
                        <input type="text" class="form-control" value="${stopName}" readonly>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Monthly Fee *</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" name="stop_fees[${stopIndex}]" 
                                   class="form-control stop-fee-input" 
                                   data-stop-index="${stopIndex}"
                                   data-stop-id="${stopId}"
                                   placeholder="Enter fee for this stop"
                                   min="0" step="0.01"
                                   value="${$('#monthly_fee').val() || ''}">
                        </div>
                        <input type="hidden" name="stop_fee_ids[${stopIndex}]" value="${stopId}">
                    </div>
                </div>
            </div>
        `;
        
        container.append(feeHtml);
        
        // Show the section if hidden
        $('#stopFeeSection').show();
    }

    function updateStopFees() {
        $('#stopFeesContainer').empty();
        
        if (!$('#enableStopFees').is(':checked')) {
            $('#stopFeeSection').hide();
            return;
        }
        
        $('.bus-stop-field').each(function(index) {
            const stopId = $(this).closest('.stop-card').data('stop-id');
            addStopFeeInput($(this).data('index'), stopId);
        });
    }

    // Toggle stop fees
    $('#enableStopFees').on('change', function() {
        if (this.checked) {
            updateStopFees();
        } else {
            $('#stopFeeSection').hide();
            $('#stopFeesContainer').empty();
        }
    });

    // Update stop name in fee section
    $(document).on('input', '.bus-stop-field', function() {
        const stopIndex = $(this).data('index');
        const stopId = $(this).closest('.stop-card').data('stop-id');
        const stopName = $(this).val() || `Stop ${parseInt(stopIndex) + 1}`;
        
        // Update in fee section if enabled
        if ($('#enableStopFees').is(':checked')) {
            $(`.stop-fee-row:has(input[data-stop-id="${stopId}"]) input[readonly]`).val(stopName);
        }
    });

    // When monthly fee changes
    $('#monthly_fee').on('input', function() {
        calculateAnnualFee();
        
        if ($('#enableStopFees').is(':checked')) {
            const monthlyFee = $(this).val();
            $('.stop-fee-input').each(function() {
                if (!$(this).val() || $(this).val() == $('#monthly_fee').data('prev-value')) {
                    $(this).val(monthlyFee);
                }
            });
        }
        $('#monthly_fee').data('prev-value', $(this).val());
    });

    // Late Fee Handling
    $('#late_fee_type').on('change', function() {
        const type = $(this).val();
        const input = $('#late_fee_value');
        const suffix = $('#late_fee_suffix');
        const note = $('#late_fee_note');
        
        if (type === 'fixed') {
            input.prop('disabled', false).prop('required', false);
            suffix.text('₹');
            note.text('Enter fixed amount charged for late payments');
        } else if (type === 'percentage') {
            input.prop('disabled', false).prop('required', false);
            suffix.text('%');
            note.text('Enter percentage of monthly fee charged for late payments');
        } else {
            input.prop('disabled', true).prop('required', false).val('');
            suffix.text('₹');
            note.text('Enter fixed amount or percentage for late payments');
        }
    });

    // Partial Fee Handling
    $('#partially_fee_type').on('change', function() {
        const type = $(this).val();
        const input = $('#partially_fee_value');
        const suffix = $('#partially_fee_suffix');
        const note = $('#partially_fee_note');
        
        if (type === 'fixed') {
            input.prop('disabled', false).prop('required', false);
            suffix.text('₹');
            note.text('Enter fixed amount charged for partial payments');
        } else if (type === 'percentage') {
            input.prop('disabled', false).prop('required', false);
            suffix.text('%');
            note.text('Enter percentage of payment charged as partial payment fee');
        } else {
            input.prop('disabled', true).prop('required', false).val('');
            suffix.text('₹');
            note.text('Additional charge for partial payments');
        }
    });

    // Validate time inputs
    function validateTimeInputs() {
        let isValid = true;
        
        // Validate overall timing fields
        const routeType = $('#routeTypeSelect').val();
        
        if (routeType === 'morning') {
            const startHour = $('input[name="estimated_start_hour"]').val();
            const startMinute = $('input[name="estimated_start_minute"]').val();
            const startAmpm = $('select[name="estimated_start_ampm"]').val();
            
            if (!startHour || !startMinute || !startAmpm) {
                alert('Please fill in estimated start time');
                isValid = false;
            }
        } else if (routeType === 'evening') {
            const endHour = $('input[name="estimated_end_hour"]').val();
            const endMinute = $('input[name="estimated_end_minute"]').val();
            const endAmpm = $('select[name="estimated_end_ampm"]').val();
            
            if (!endHour || !endMinute || !endAmpm) {
                alert('Please fill in estimated end time');
                isValid = false;
            }
        } else if (routeType === 'both') {
            const startHour = $('input[name="estimated_start_hour"]').val();
            const startMinute = $('input[name="estimated_start_minute"]').val();
            const startAmpm = $('select[name="estimated_start_ampm"]').val();
            const endHour = $('input[name="estimated_end_hour"]').val();
            const endMinute = $('input[name="estimated_end_minute"]').val();
            const endAmpm = $('select[name="estimated_end_ampm"]').val();
            
            if (!startHour || !startMinute || !startAmpm || !endHour || !endMinute || !endAmpm) {
                alert('Please fill in both estimated start and end times');
                isValid = false;
            }
        }
        
        // Validate stop timings
        $('.time-input[type="number"]').each(function() {
            const value = parseInt($(this).val());
            const min = parseInt($(this).attr('min'));
            const max = parseInt($(this).attr('max'));
            
            if (isNaN(value) || value < min || value > max) {
                alert(`Please enter a valid time (${min}-${max})`);
                $(this).focus();
                isValid = false;
                return false;
            }
        });
        
        return isValid;
    }

    // Form Validation and Submission
    $('#transportFeeForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validate route selected
        if (!$('#routeSelect').val()) {
            alert('Please select a route first');
            return;
        }
        
        // Validate all required fields
        const requiredFields = [
            'bus_number',
            'vehicle_number',
            'driver_name',
            'driver_contact',
            'route_type',
            'sitting_capacity',
            'academic_year',
            'monthly_fee'
        ];
        
        for (const field of requiredFields) {
            const value = $(`[name="${field}"]`).val();
            if (!value || value.trim() === '') {
                alert(`Please fill in ${field.replace('_', ' ')}`);
                $(`[name="${field}"]`).focus();
                return;
            }
        }
        
        // Validate bus stops
        if (stopCount === 0) {
            alert('Please add at least one bus stop');
            return;
        }
        
        // Validate monthly fee
        const monthlyFee = parseFloat($('#monthly_fee').val());
        if (isNaN(monthlyFee) || monthlyFee <= 0) {
            alert('Please enter a valid monthly transport fee');
            $('#monthly_fee').focus();
            return;
        }
        
        // Validate stop fees if enabled
        if ($('#enableStopFees').is(':checked')) {
            let hasInvalidFees = false;
            $('.stop-fee-input').each(function() {
                const feeValue = parseFloat($(this).val()) || 0;
                if (feeValue <= 0) {
                    hasInvalidFees = true;
                    alert('Please enter valid fees for all stops');
                    $(this).focus();
                    return false;
                }
            });
            
            if (hasInvalidFees) return;
        }
        
        // Validate all time inputs
        if (!validateTimeInputs()) {
            return;
        }
        
        // Validate optional fees if type is selected
        if ($('#late_fee_type').val()) {
            const lateFeeValue = parseFloat($('#late_fee_value').val());
            if (isNaN(lateFeeValue) || lateFeeValue <= 0) {
                alert('Please enter a valid late fee value');
                $('#late_fee_value').focus();
                return;
            }
        }
        
        if ($('#partially_fee_type').val()) {
            const partialFeeValue = parseFloat($('#partially_fee_value').val());
            if (isNaN(partialFeeValue) || partialFeeValue <= 0) {
                alert('Please enter a valid partial fee value');
                $('#partially_fee_value').focus();
                return;
            }
        }
        
        // Show loading
        $('#submitBtn').prop('disabled', true).html(
            '<i class="fas fa-spinner fa-spin me-2"></i>Saving...');
        
        // Submit form
        this.submit();
    });

    // Initialize
    $(document).ready(function() {
        // Initially disable all sections
        toggleSections(false);
        
        // Initialize fee type handlers
        $('#late_fee_type').trigger('change');
        $('#partially_fee_type').trigger('change');
        
        // Calculate initial annual fee
        calculateAnnualFee();
        
        // Store initial monthly fee value
        $('#monthly_fee').data('prev-value', $('#monthly_fee').val());
        
        // Add AM/PM validation
        $(document).on('change', '.time-input[type="number"]', function() {
            let value = parseInt($(this).val());
            const min = parseInt($(this).attr('min'));
            const max = parseInt($(this).attr('max'));
            
            if (isNaN(value)) {
                $(this).val(min);
            } else if (value < min) {
                $(this).val(min);
            } else if (value > max) {
                $(this).val(max);
            }
        });

        // Check if route_id is passed in URL (for pre-selection)
        const urlParams = new URLSearchParams(window.location.search);
        const routeId = urlParams.get('route_id');
        if (routeId) {
            $('#routeSelect').val(routeId).trigger('change');
        }
    });
</script>
@endsection