@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --success-gradient: linear-gradient(135deg, #10b981, #059669);
        --success-color: #10b981;
        --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
        --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding: 20px 30px;
        background: var(--primary-gradient);
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .page-title i {
        font-size: 32px;
        filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
    }

    /* Form Card */
    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        border: none;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .card-header {
        background: var(--primary-gradient);
        color: white;
        border-radius: 0;
        padding: 20px 30px;
        border: none;
    }

    .card-header h5 {
        margin: 0;
        font-weight: 700;
        font-size: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header h5 i {
        font-size: 24px;
        background: rgba(255, 255, 255, 0.2);
        padding: 8px;
        border-radius: 12px;
    }

    .card-body {
        padding: 30px;
    }

    /* Card Sections */
    .card-section {
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        border: 2px solid #e2e8f0;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
    }

    .card-section:hover {
        border-color: var(--primary-color);
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.1);
    }

    .card-section h5 {
        color: var(--primary-color);
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 12px;
        margin-bottom: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-section h5 i {
        font-size: 20px;
    }

    /* Form Labels */
    .form-label {
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .form-label i {
        color: var(--primary-color);
        margin-right: 5px;
    }

    /* Form Controls */
    .form-control,
    .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 15px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
        height: auto;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
    }

    .form-control:hover,
    .form-select:hover {
        border-color: var(--secondary-color);
    }

    .input-group-text {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px solid #e2e8f0;
        border-right: none;
        border-radius: 12px 0 0 12px;
        color: var(--primary-color);
        font-weight: 500;
        padding: 0 15px;
    }

    .input-group .form-control {
        border-left: none;
        border-radius: 0 12px 12px 0;
    }

    /* Fee Preview - Enhanced */
    .fee-preview {
        background: var(--primary-gradient);
        color: white;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 25px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
        position: relative;
        overflow: hidden;
    }

    .fee-preview::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .fee-preview h5 {
        color: white;
        font-weight: 700;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
        z-index: 1;
    }

    .fee-preview h5 i {
        font-size: 24px;
        background: rgba(255, 255, 255, 0.2);
        padding: 8px;
        border-radius: 12px;
    }

    .fee-preview .form-label {
        color: rgba(255, 255, 255, 0.8);
        font-size: 12px;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .fee-preview .preview-value {
        color: white;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .fee-preview #preview_total_annual {
        font-size: 28px;
        font-weight: 800;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .fee-preview .text-warning {
        color: #fbbf24 !important;
    }

    .fee-preview .text-danger {
        color: #f87171 !important;
    }

    .fee-preview .border-top {
        border-color: rgba(255, 255, 255, 0.2) !important;
    }

    .fee-preview small {
        color: rgba(255, 255, 255, 0.6);
    }

    /* Buttons */
    .btn {
        border-radius: 12px;
        font-weight: 600;
        padding: 12px 24px;
        transition: all 0.3s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .btn:active {
        transform: translateY(-1px);
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

    .btn-warning {
        background: var(--warning-gradient);
        color: white;
    }

    .btn-outline-secondary {
        background: transparent;
        border: 2px solid #e2e8f0;
        color: #475569;
    }

    .btn-outline-secondary:hover {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-color: #cbd5e1;
        color: #1e293b;
        transform: translateY(-2px);
    }

    /* Alert Messages */
    .alert {
        border: none;
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .alert-success {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        border: 1px solid #86efac;
        color: #166534;
    }

    .alert-danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border: 1px solid #fca5a5;
        color: #991b1b;
    }

    .alert-warning {
        background: linear-gradient(135deg, #fed7aa, #fdba74);
        border: 1px solid #fcd34d;
        color: #92400e;
    }

    .alert-info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border: 1px solid #93c5fd;
        color: #1e40af;
    }

    .alert .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* Form Check */
    .form-check-input {
        width: 20px;
        height: 20px;
        cursor: pointer;
        border: 2px solid #cbd5e1;
        border-radius: 6px;
        transition: all 0.2s;
        margin-top: 0;
    }

    .form-check-input:hover {
        border-color: var(--primary-color);
        transform: scale(1.1);
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
    }

    .form-check-label {
        color: #475569;
        font-weight: 500;
        cursor: pointer;
        margin-left: 8px;
    }

    .form-check-label strong {
        color: var(--primary-color);
    }

    /* Form Switch */
    .form-switch .form-check-input {
        width: 40px;
        height: 20px;
        border-radius: 20px;
        background-color: #cbd5e1;
        border: 2px solid #cbd5e1;
        transition: all 0.3s;
    }

    .form-switch .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
    }

    /* Small Text */
    small.text-muted {
        color: #94a3b8 !important;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }

    /* Invalid Feedback */
    .is-invalid {
        border-color: #ef4444 !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23ef4444'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23ef4444' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .invalid-feedback {
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #ef4444;
    }

    /* Loading Spinner */
    .fa-spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
        }

        .card-body {
            padding: 20px;
        }

        .card-section {
            padding: 20px;
        }

        .fee-preview {
            padding: 20px;
        }

        .fee-preview .row > div {
            margin-bottom: 15px;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .text-end {
            text-align: center !important;
        }

        .btn-outline-secondary,
        .btn-warning,
        .btn-primary {
            margin-bottom: 10px;
            width: 100%;
        }
    }
</style>

<div class="container-fluid">
    <!-- Main Card -->
    <div class="form-card">
        <div class="card-header">
            <h1 class="page-title">
                <i class="bi bi-building-fill"></i>
                Create Hostel Fee Structure
            </h1>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('hostel.fees.store') }}" method="POST" id="hostelFeeForm">
                @csrf
                
                <!-- Basic Information -->
                <div class="card-section">
                    <h5><i class="bi bi-info-circle-fill"></i>Basic Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-building-fill"></i> Hostel Name *</label>
                            <input type="text" name="hostel_name" class="form-control" placeholder="e.g., Boys Hostel A" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-people-fill"></i> Hostel Type *</label>
                            <select name="hostel_type" class="form-control" required>
                                <option value="">Select Type</option>
                                <option value="boys">Boys Hostel</option>
                                <option value="girls">Girls Hostel</option>
                                <option value="co-ed">Co-Educational Hostel</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Room Type and Monthly Fee -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-door-open-fill"></i> Room Type *</label>
                            <select name="room_type" class="form-control" required>
                                <option value="">Select Room Type</option>
                                <option value="single">Single Room</option>
                                <option value="double">Double Sharing</option>
                                <option value="triple">Triple Sharing</option>
                                <option value="dormitory">Dormitory (4+ beds)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-cash-stack"></i> Monthly Fee (₹) *</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="monthly_fee" id="monthly_fee" class="form-control" 
                                       min="0" step="0.01" placeholder="e.g., 5000" required>
                            </div>
                            <small class="text-muted">Basic monthly accommodation fee</small>
                        </div>
                    </div>
                </div>

                <!-- Additional Charges -->
                <div class="card-section">
                    <h5><i class="bi bi-file-earmark-text-fill"></i>Additional Charges (Optional)</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><i class="bi bi-shield-lock-fill"></i> Security Deposit (₹)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="security_deposit" id="security_deposit" class="form-control" 
                                       min="0" step="0.01" placeholder="One-time deposit" value="0">
                            </div>
                            <small class="text-muted">Refundable security deposit (one-time)</small>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><i class="bi bi-wrench-fill"></i> Maintenance Fee (₹)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="maintenance_fee" id="maintenance_fee" class="form-control" 
                                       min="0" step="0.01" placeholder="Annual maintenance" value="0">
                            </div>
                            <small class="text-muted">Annual maintenance fee</small>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><i class="bi bi-lightbulb-fill"></i> Utility Charges (₹)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="utility_charges" id="utility_charges" class="form-control" 
                                       min="0" step="0.01" placeholder="Annual utilities" value="0">
                            </div>
                            <small class="text-muted">Annual utility charges</small>
                        </div>
                    </div>
                </div>

                <!-- Late Fee Configuration -->
                <div class="card-section">
                    <h5><i class="bi bi-clock-fill"></i>Late Fee Configuration (Optional)</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-tag-fill"></i> Late Fee Type</label>
                            <select name="late_fee_type" id="late_fee_type" class="form-control">
                                <option value="none">No Late Fee</option>
                                <option value="fixed">Fixed Amount (per month)</option>
                                <option value="percentage">Percentage (of monthly fee)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-123"></i> Late Fee Value</label>
                            <div class="input-group">
                                <span class="input-group-text" id="late_fee_suffix">₹</span>
                                <input type="number" name="late_fee_value" id="late_fee_value" 
                                       class="form-control" min="0" step="0.01" 
                                       placeholder="Enter amount/percentage" value="0">
                            </div>
                            <small class="text-muted" id="late_fee_note">
                                Enter fixed amount or percentage for late payments
                            </small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-calendar-heart-fill"></i> Grace Period (Days)</label>
                            <div class="input-group">
                                <span class="input-group-text">days</span>
                                <input type="number" name="grace_period" class="form-control" 
                                       min="0" value="5" placeholder="Number of grace days">
                            </div>
                            <small class="text-muted">Number of days before late fee is applied</small>
                        </div>
                    </div>
                </div>

                <!-- Partial Fee Configuration -->
                <div class="card-section">
                    <h5><i class="bi bi-percent"></i>Partial Fee Configuration (Optional)</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-tag-fill"></i> Partial Fee Type</label>
                            <select name="partially_fee_type" id="partially_fee_type" class="form-control">
                                <option value="none">No Partial Fee</option>
                                <option value="fixed">Fixed Amount (per payment)</option>
                                <option value="percentage">Percentage (of installment)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-123"></i> Partial Fee Value</label>
                            <div class="input-group">
                                <span class="input-group-text" id="partially_fee_suffix">₹</span>
                                <input type="number" name="partially_fee_value" id="partially_fee_value" 
                                    class="form-control" min="0" step="0.01" 
                                    placeholder="Enter amount/percentage" value="0">
                            </div>
                            <small class="text-muted" id="partially_fee_note">
                                Enter fixed amount or percentage for partial payments
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Academic Year and Capacity -->
                <div class="card-section">
                    <h5><i class="bi bi-calendar-fill"></i>Academic Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-calendar-fill"></i> Academic Year *</label>
                            <select name="academic_year" class="form-control" required>
                                <option value="">Select Academic Year</option>
                                @foreach($academicYears as $year)
                                <option value="{{ $year['value'] }}">{{ $year['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-people-fill"></i> Total Capacity (Seats) *</label>
                            <div class="input-group">
                                <span class="input-group-text">seats</span>
                                <input type="number" name="total_capacity" class="form-control" 
                                       min="1" placeholder="e.g., 50" required>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label"><i class="bi bi-pencil-fill"></i> Description (Optional)</label>
                            <textarea name="description" class="form-control" rows="3"
                                      placeholder="Optional description about hostel facilities, rules, etc."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Fee Preview -->
                <div class="fee-preview">
                    <h5 class="text-white mb-4"><i class="bi bi-eye-fill"></i>Annual Fee Preview</h5>
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="form-label">Monthly Fee:</div>
                            <div id="preview_monthly_fee" class="preview-value">₹0.00</div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-label">Security Deposit:</div>
                            <div id="preview_security_deposit" class="preview-value">₹0.00</div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-label">Maintenance Fee:</div>
                            <div id="preview_maintenance_fee" class="preview-value">₹0.00</div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-label">Utility Charges:</div>
                            <div id="preview_utility_charges" class="preview-value">₹0.00</div>
                        </div>
                    </div>
                    
                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <div class="form-label">Late Fee (per month):</div>
                            <div id="preview_late_fee" class="preview-value">₹0.00</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label">Partial Fee (per installment):</div>
                            <div id="preview_partial_fee" class="preview-value">₹0.00</div>
                        </div>
                    </div>
                    
                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <div class="form-label">Annual Base Fee:</div>
                            <div id="preview_annual_base" class="preview-value">₹0.00</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label">Total Annual Fee:</div>
                            <div id="preview_total_annual" class="preview-value">₹0.00</div>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top">
                        <small class="text-white-50">
                            <i class="bi bi-info-circle-fill me-1"></i>
                            Note: Late and Partial fees will be applied when assigning to students/employees
                        </small>
                    </div>
                </div>

                <!-- Status -->
                <div class="card-section">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">
                            <strong><i class="bi bi-check-circle-fill me-1"></i>Make this hostel fee structure active</strong>
                        </label>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('hostel.fees.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left-fill me-2"></i>Back to List
                    </a>
                    <div>
                        <button type="button" class="btn btn-warning me-2" onclick="resetForm()">
                            <i class="bi bi-arrow-repeat me-2"></i>Reset
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-check-circle-fill me-2"></i>Create Hostel Structure
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize all fields
    toggleLateFeeField();
    togglePartialFeeField();
    updatePreview();
    
    // Event listeners for preview updates
    $('#monthly_fee, #security_deposit, #maintenance_fee, #utility_charges').on('input', updatePreview);
    
    // Late fee listeners
    $('#late_fee_type').on('change', function() {
        toggleLateFeeField();
        updatePreview();
    });
    
    $('#late_fee_value').on('input', updatePreview);
    
    // Partial fee listeners
    $('#partially_fee_type').on('change', function() {
        togglePartialFeeField();
        updatePreview();
    });
    
    $('#partially_fee_value').on('input', updatePreview);
    
    // Form validation
    $('#hostelFeeForm').on('submit', function(e) {
        e.preventDefault();
        
        // Clear previous validation
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        let hasError = false;
        
        // Validate required fields
        const requiredFields = [
            {name: 'hostel_name', label: 'Hostel Name'},
            {name: 'hostel_type', label: 'Hostel Type'},
            {name: 'room_type', label: 'Room Type'},
            {name: 'monthly_fee', label: 'Monthly Fee'},
            {name: 'academic_year', label: 'Academic Year'},
            {name: 'total_capacity', label: 'Total Capacity'},
        ];
        
        requiredFields.forEach(field => {
            const element = $(`[name="${field.name}"]`);
            const value = element.val();
            
            if (!value || value.trim() === '') {
                showFieldError(element, `${field.label} is required`);
                hasError = true;
            }
        });
        
        // Validate monthly fee
        const monthlyFee = parseFloat($('#monthly_fee').val()) || 0;
        if (monthlyFee <= 0) {
            showFieldError($('#monthly_fee'), 'Please enter a valid monthly fee (greater than 0)');
            hasError = true;
        }
        
        // Validate capacity
        const capacity = parseFloat($('input[name="total_capacity"]').val()) || 0;
        if (capacity <= 0) {
            showFieldError($('input[name="total_capacity"]'), 'Please enter a valid capacity (greater than 0)');
            hasError = true;
        }
        
        // Validate late fee if enabled
        if ($('#late_fee_type').val() !== 'none') {
            const lateFeeValue = parseFloat($('#late_fee_value').val()) || 0;
            if (lateFeeValue <= 0) {
                showFieldError($('#late_fee_value'), 'Please enter a valid late fee value');
                hasError = true;
            }
            
            if ($('#late_fee_type').val() === 'percentage' && lateFeeValue > 100) {
                if (!confirm('Late fee percentage is unusually high (' + lateFeeValue + '%). Continue anyway?')) {
                    showFieldError($('#late_fee_value'), 'Please adjust the late fee percentage');
                    hasError = true;
                }
            }
        }
        
        // Validate partial fee if enabled
        if ($('#partially_fee_type').val() !== 'none') {
            const partialFeeValue = parseFloat($('#partially_fee_value').val()) || 0;
            if (partialFeeValue <= 0) {
                showFieldError($('#partially_fee_value'), 'Please enter a valid partial fee value');
                hasError = true;
            }
            
            if ($('#partially_fee_type').val() === 'percentage' && partialFeeValue > 100) {
                if (!confirm('Partial fee percentage is unusually high (' + partialFeeValue + '%). Continue anyway?')) {
                    showFieldError($('#partially_fee_value'), 'Please adjust the partial fee percentage');
                    hasError = true;
                }
            }
        }
        
        if (hasError) {
            return false;
        }
        
        // Show loading
        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html(
            '<i class="bi bi-arrow-repeat fa-spin me-2"></i>Saving...'
        );
        
        // Submit form
        this.submit();
    });
});

function toggleLateFeeField() {
    const lateFeeType = $('#late_fee_type').val();
    const input = $('#late_fee_value');
    const suffix = $('#late_fee_suffix');
    const note = $('#late_fee_note');
    
    if (lateFeeType === 'fixed') {
        input.prop('disabled', false);
        input.prop('required', true);
        suffix.text('₹');
        note.text('Fixed amount charged per month for late payments');
    } else if (lateFeeType === 'percentage') {
        input.prop('disabled', false);
        input.prop('required', true);
        suffix.text('%');
        note.text('Percentage of monthly fee charged for late payments');
    } else {
        input.prop('disabled', true);
        input.prop('required', false);
        input.val('0');
        suffix.text('₹');
        note.text('Enter fixed amount or percentage for late payments');
    }
}

function togglePartialFeeField() {
    const partialFeeType = $('#partially_fee_type').val();
    const input = $('#partially_fee_value');
    const suffix = $('#partially_fee_suffix');
    const note = $('#partially_fee_note');
    
    if (partialFeeType === 'fixed') {
        input.prop('disabled', false);
        input.prop('required', true);
        suffix.text('₹');
        note.text('Fixed amount charged per installment for partial payments');
    } else if (partialFeeType === 'percentage') {
        input.prop('disabled', false);
        input.prop('required', true);
        suffix.text('%');
        note.text('Percentage of installment charged for partial payments');
    } else {
        input.prop('disabled', true);
        input.prop('required', false);
        input.val('0');
        suffix.text('₹');
        note.text('Enter fixed amount or percentage for partial payments');
    }
}

function updatePreview() {
    // Get all values
    const monthlyFee = parseFloat($('#monthly_fee').val()) || 0;
    const securityDeposit = parseFloat($('#security_deposit').val()) || 0;
    const maintenanceFee = parseFloat($('#maintenance_fee').val()) || 0;
    const utilityCharges = parseFloat($('#utility_charges').val()) || 0;
    
    // Calculate annual base fee
    const annualBaseFee = monthlyFee * 12;
    
    // Calculate late fee
    let lateFee = 0;
    const lateFeeType = $('#late_fee_type').val();
    const lateFeeValue = parseFloat($('#late_fee_value').val()) || 0;
    
    if (lateFeeType === 'fixed') {
        lateFee = lateFeeValue;
    } else if (lateFeeType === 'percentage') {
        lateFee = (monthlyFee * lateFeeValue) / 100;
    }
    
    // Calculate partial fee
    let partialFee = 0;
    const partialFeeType = $('#partially_fee_type').val();
    const partialFeeValue = parseFloat($('#partially_fee_value').val()) || 0;
    
    if (partialFeeType === 'fixed') {
        partialFee = partialFeeValue;
    } else if (partialFeeType === 'percentage') {
        // Base on monthly fee for preview
        partialFee = (monthlyFee * partialFeeValue) / 100;
    }
    
    // Calculate total annual fee (excluding late/partial as they're per payment)
    const totalAnnualFee = annualBaseFee + securityDeposit + maintenanceFee + utilityCharges;
    
    // Update preview display
    $('#preview_monthly_fee').text('₹' + monthlyFee.toFixed(2));
    $('#preview_security_deposit').text('₹' + securityDeposit.toFixed(2));
    $('#preview_maintenance_fee').text('₹' + maintenanceFee.toFixed(2));
    $('#preview_utility_charges').text('₹' + utilityCharges.toFixed(2));
    $('#preview_late_fee').text('₹' + lateFee.toFixed(2));
    $('#preview_partial_fee').text('₹' + partialFee.toFixed(2));
    $('#preview_annual_base').text('₹' + annualBaseFee.toFixed(2));
    $('#preview_total_annual').text('₹' + totalAnnualFee.toFixed(2));
    
    // Add warnings for high fees
    if (partialFeeType !== 'none' && partialFeeValue > 0) {
        if (partialFeeType === 'percentage' && partialFeeValue > 50) {
            $('#preview_partial_fee').addClass('text-warning').removeClass('preview-value');
        } else if (partialFeeType === 'fixed' && partialFee > (monthlyFee * 0.5)) {
            $('#preview_partial_fee').addClass('text-warning').removeClass('preview-value');
        } else {
            $('#preview_partial_fee').removeClass('text-warning text-danger').addClass('preview-value');
        }
    } else {
        $('#preview_partial_fee').removeClass('text-warning text-danger').addClass('preview-value');
    }
    
    if (lateFeeType !== 'none' && lateFeeValue > 0) {
        if (lateFeeType === 'percentage' && lateFeeValue > 30) {
            $('#preview_late_fee').addClass('text-danger').removeClass('preview-value');
        } else if (lateFeeType === 'fixed' && lateFee > (monthlyFee * 0.3)) {
            $('#preview_late_fee').addClass('text-danger').removeClass('preview-value');
        } else {
            $('#preview_late_fee').removeClass('text-warning text-danger').addClass('preview-value');
        }
    } else {
        $('#preview_late_fee').removeClass('text-warning text-danger').addClass('preview-value');
    }
}

function resetForm() {
    if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
        $('#hostelFeeForm')[0].reset();
        toggleLateFeeField();
        togglePartialFeeField();
        updatePreview();
    }
}

function showFieldError(element, message) {
    element.addClass('is-invalid');
    element.after('<div class="invalid-feedback">' + message + '</div>');
    element.focus();
}
</script>
@endsection