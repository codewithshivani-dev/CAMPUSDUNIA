@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --hover-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        --border-color: #e8ecf1;
    }

    body {
        background: #f8fafc;
    }

    /* Page Header */
    .page-header {
        background: white;
        padding: 20px 28px;
        border-radius: 16px;
        margin-bottom: 24px;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .page-header-icon {
        width: 48px;
        height: 48px;
        background: var(--primary-gradient);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.3rem;
    }

    .page-header-info h5 {
        margin: 0;
        font-weight: 700;
        color: #1e293b;
        font-size: 1.2rem;
    }

    .page-header-info small {
        color: #64748b;
        font-weight: 500;
    }

    .header-badge {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        color: #1e40af;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        border: 1px solid #93c5fd;
    }

    /* Timeline / Stepper */
    .timeline {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 30px 0 24px;
        background: white;
        padding: 24px 32px;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border-color);
    }

    .timeline-line {
        position: absolute;
        top: 40px;
        left: 10%;
        height: 4px;
        background: var(--success-color);
        z-index: 0;
        transition: width 0.4s ease;
        width: 0%;
        border-radius: 2px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        top: 40px;
        left: 10%;
        width: 80%;
        height: 4px;
        background: var(--border-color);
        z-index: 0;
        border-radius: 2px;
    }

    .timeline-step {
        position: relative;
        z-index: 1;
        text-align: center;
        flex: 1;
        cursor: pointer;
    }

    .timeline-bullet {
        width: 40px;
        height: 40px;
        background: white;
        color: #94a3b8;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 3px solid var(--border-color);
    }

    .timeline-step.active .timeline-bullet {
        background: var(--primary-gradient);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .timeline-step.completed .timeline-bullet {
        background: var(--success-color);
        color: white;
        border-color: var(--success-color);
    }

    .timeline-step-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #94a3b8;
        transition: all 0.3s ease;
    }

    .timeline-step.active .timeline-step-label {
        color: var(--primary-color);
    }

    .timeline-step.completed .timeline-step-label {
        color: var(--success-color);
    }

    /* Main Card */
    .main-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    .main-card .card-header {
        background: var(--primary-gradient);
        color: white;
        padding: 18px 28px;
        border-bottom: none;
    }

    .main-card .card-header h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .main-card .card-body {
        padding: 28px;
    }

    /* Form Steps */
    .form-step {
        display: none;
        animation: fadeStep 0.3s ease-in-out;
    }

    .form-step.active {
        display: block;
    }

    @keyframes fadeStep {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Section Card */
    .section-card {
        border: 1px solid var(--border-color);
        border-radius: 14px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .section-card .section-header {
        background: linear-gradient(135deg, #f8fafc, #f0f4ff);
        padding: 14px 20px;
        border-bottom: 1px solid var(--border-color);
        font-weight: 700;
        font-size: 0.95rem;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-card .section-header i {
        color: var(--primary-color);
    }

    .section-card .section-body {
        padding: 20px;
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 6px;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-control,
    .form-select {
        border: 2px solid var(--border-color);
        border-radius: 10px;
        padding: 10px 14px;
        height: 46px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background: #f8fafc;
        color: #1e293b;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
        background: white;
    }

    .form-control:disabled,
    .form-select:disabled {
        background-color: #f1f5f9;
        border-color: #e2e8f0;
        opacity: 0.6;
        cursor: not-allowed;
    }

    textarea.form-control {
        height: auto;
        min-height: 80px;
        resize: vertical;
    }

    .input-group-text {
        background: #f8fafc;
        border: 2px solid var(--border-color);
        border-right: none;
        color: #64748b;
        font-weight: 600;
        border-radius: 10px 0 0 10px;
    }

    .input-group .form-control {
        border-radius: 0 10px 10px 0;
    }

    .text-muted {
        color: #94a3b8 !important;
        font-size: 0.8rem;
    }

    /* Radio Buttons */
    .form-check-inline {
        margin-right: 16px;
    }

    .form-check-input {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    /* Buttons */
    .btn {
        border-radius: 10px;
        padding: 10px 22px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn:hover:not(:disabled) {
        transform: translateY(-2px);
    }

    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    .btn-primary {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .btn-primary:hover:not(:disabled) {
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        border-color: transparent;
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .btn-success:hover:not(:disabled) {
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    }

    .btn-secondary {
        background: #f1f5f9;
        border-color: var(--border-color);
        color: #475569;
    }

    .btn-secondary:hover:not(:disabled) {
        background: #e2e8f0;
    }

    .btn-outline-primary {
        background: white;
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .btn-outline-primary:hover:not(:disabled) {
        background: var(--primary-color);
        color: white;
    }

    .btn-outline-secondary {
        background: white;
        border-color: var(--border-color);
        color: #64748b;
    }

    .btn-outline-secondary:hover:not(:disabled) {
        background: #f1f5f9;
    }

    .btn-sm {
        padding: 6px 14px;
        font-size: 0.8rem;
        border-radius: 8px;
    }

    .btn-danger {
        background: linear-gradient(135deg, #fef2f2, #fecaca);
        color: #991b1b;
        border-color: #fca5a5;
    }

    .btn-danger:hover:not(:disabled) {
        background: linear-gradient(135deg, #fecaca, #fca5a5);
    }

    /* OTP Section */
    .otp-verification-section {
        margin-top: 15px;
        padding: 20px;
        background: linear-gradient(135deg, #f0f4ff, #e8edff);
        border: 2px solid var(--primary-color);
        border-radius: 14px;
        transition: all 0.3s ease;
    }

    .otp-verification-section h6 {
        color: var(--primary-color);
        margin-bottom: 12px;
        font-size: 0.9rem;
        font-weight: 700;
    }

    .otp-digit-container {
        display: flex;
        gap: 12px;
        margin-bottom: 15px;
        justify-content: flex-start;
        flex-wrap: wrap;
    }

    .otp-digit {
        width: 55px;
        height: 55px;
        text-align: center;
        font-size: 1.3rem;
        font-weight: 700;
        border: 2px solid var(--primary-color);
        border-radius: 12px;
        background: white;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        padding: 0;
        line-height: 1.2;
        color: #1e293b;
    }

    .otp-digit:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.2);
        transform: scale(1.03);
    }

    .otp-digit:disabled {
        background-color: #f1f5f9;
        color: #64748b;
        border-color: #cbd5e1;
    }

    .otp-status {
        margin-top: 12px;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 500;
        display: none;
    }

    .otp-status.success {
        display: block;
        background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        color: #065f46;
        border-left: 4px solid var(--success-color);
    }

    .otp-status.error {
        display: block;
        background: linear-gradient(135deg, #fef2f2, #fecaca);
        color: #991b1b;
        border-left: 4px solid var(--danger-color);
    }

    .phone-edit-indicator,
    .email-edit-indicator {
        font-size: 0.75rem;
        color: var(--warning-color);
        margin-top: 4px;
        display: none;
        font-weight: 600;
    }

    /* Alerts */
    .alert {
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 20px;
        border: 2px solid transparent;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .alert-success {
        background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        border-color: #6ee7b7;
        color: #065f46;
    }

    .alert-danger {
        background: linear-gradient(135deg, #fef2f2, #fecaca);
        border-color: #fca5a5;
        color: #991b1b;
    }

    /* Step Actions */
    .step-actions {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
    }

    .step-actions.end {
        justify-content: flex-end;
    }

    /* Validation */
    .is-invalid {
        border-color: #dc3545 !important;
    }

    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.8rem;
        color: #dc3545;
        font-weight: 500;
    }

    /* Toast Notification */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .toast-message {
        margin-bottom: 10px;
        min-width: 300px;
        border-radius: 12px;
        padding: 14px 18px;
        animation: slideInRight 0.3s ease;
    }

    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    /* Probation Field Highlight */
    .probation-highlight {
        background: linear-gradient(135deg, #fefce8, #fef08a);
        border-color: #fde047 !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .timeline {
            flex-wrap: wrap;
            gap: 16px;
            padding: 16px;
        }

        .timeline::before,
        .timeline-line {
            display: none;
        }

        .timeline-step {
            flex: 1 1 30%;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .main-card .card-body {
            padding: 16px;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .step-actions {
            flex-direction: column;
        }

        .otp-digit {
            width: 48px;
            height: 48px;
            font-size: 1.1rem;
        }

        .otp-digit-container {
            gap: 8px;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-icon">
            <i class="bi bi-pencil-square"></i>
        </div>
        <div class="page-header-info">
            <h5>Edit Employee Details</h5>
            <small>Editing: {{ $employee->name }}</small>
        </div>
        <span class="header-badge ms-auto">
            <i class="bi bi-hash me-1"></i> {{ $employee->employee_code }}
        </span>
    </div>

    <!-- Timeline / Stepper -->
    <div class="timeline position-relative">
        <div class="timeline-line" id="timelineProgress"></div>
        <div class="timeline-step active" id="step1">
            <div class="timeline-bullet">1</div>
            <div class="timeline-step-label">Basic Details</div>
        </div>
        <div class="timeline-step" id="step2">
            <div class="timeline-bullet">2</div>
            <div class="timeline-step-label">Professional</div>
        </div>
        <div class="timeline-step" id="step3">
            <div class="timeline-bullet">3</div>
            <div class="timeline-step-label">Contact</div>
        </div>
        <div class="timeline-step" id="step4">
            <div class="timeline-bullet">4</div>
            <div class="timeline-step-label">Documents</div>
        </div>
        <div class="timeline-step" id="step5">
            <div class="timeline-bullet">5</div>
            <div class="timeline-step-label">Bank</div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="main-card">
        <div class="card-header">
            <h5><i class="bi bi-person-gear me-2"></i>Employee Details</h5>
        </div>
        <div class="card-body">
            <form id="employeeForm" action="{{ route('employees.update', $employee->employee_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Success Message -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Validation Errors -->
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <h6><i class="bi bi-exclamation-triangle me-2"></i>Please fix the following errors:</h6>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- ============================
                STEP 1 : BASIC DETAILS
                ============================ -->
                <div class="form-step active" data-step="1">
                    <div class="section-card">
                        <div class="section-header">
                            <i class="bi bi-person-badge"></i> Basic Information
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Department Category <span class="text-danger">*</span></label>
                                    <select class="form-select {{ $errors->has('department_category_id') ? 'is-invalid' : '' }}" name="department_category_id" id="department_category_id" onchange="loadDepartments(this.value)" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->department_category_id }}" {{ old('department_category_id', $employee->department_category_id) == $category->department_category_id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('department_category_id'))
                                    <div class="invalid-feedback">{{ $errors->first('department_category_id') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Department <span class="text-danger">*</span></label>
                                    <select class="form-select {{ $errors->has('department_id') ? 'is-invalid' : '' }}" name="department_id" id="department_id" required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $dept)
                                        <option value="{{ $dept->department_id }}" {{ old('department_id', $employee->department_id) == $dept->department_id ? 'selected' : '' }}>
                                            {{ $dept->department }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('department_id'))
                                    <div class="invalid-feedback">{{ $errors->first('department_id') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Employee Code</label>
                                    <input type="text" class="form-control" name="employee_code" id="employee_code" value="{{ old('employee_code', $employee->employee_code) }}" disabled>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" value="{{ old('name', $employee->name) }}" required placeholder="Enter full name">
                                    @if($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Designation <span class="text-danger">*</span></label>
                                    <select class="form-select {{ $errors->has('designation_id') ? 'is-invalid' : '' }}" id="designationSelect" name="designation_id" required>
                                        <option value="">Select Designation</option>
                                        @foreach($designations as $desig)
                                        <option value="{{ $desig->designation_id }}" {{ old('designation_id', $employee->designation_id) == $desig->designation_id ? 'selected' : '' }} data-name="{{ $desig->designations }}" data-roles="{{ $desig->roles }}">
                                            {{ $desig->designations }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('designation_id'))
                                    <div class="invalid-feedback">{{ $errors->first('designation_id') }}</div>
                                    @endif
                                </div>

                                <input type="hidden" name="designation" id="designationName" value="{{ old('designation', $employee->designation) }}">
                                <input type="hidden" name="assigned_role" id="assignedRole" value="{{ old('assigned_role', $employee->assigned_role) }}">

                                <!-- Phone with OTP -->
                                <div class="col-md-6 mb-3">
                                    <label>Mobile Number <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">+91</span>
                                        <input type="tel" class="form-control {{ $errors->has('mobile_number') ? 'is-invalid' : '' }}" name="mobile_number" id="mobile_number" value="{{ old('mobile_number', $employee->mobile_number) }}" required placeholder="10-digit number">
                                    </div>
                                    <div class="phone-edit-indicator" id="phoneEditIndicator">
                                        <i class="bi bi-pencil-square"></i> Phone number changed - OTP verification required
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="sendPhoneOtpBtn" style="display: none;">
                                            <i class="bi bi-chat-dots"></i> Send OTP
                                        </button>
                                    </div>
                                    @if($errors->has('mobile_number'))
                                    <div class="invalid-feedback">{{ $errors->first('mobile_number') }}</div>
                                    @endif

                                    <div id="phoneOtpSection" class="otp-verification-section" style="display: none;">
                                        <h6><i class="bi bi-shield-lock"></i> Phone Number Verification</h6>
                                        <p style="color: #475569; font-size: 0.85rem; margin-bottom: 12px;">Enter the 4-digit OTP sent to <strong id="phoneDisplay"></strong></p>
                                        <div class="otp-digit-container">
                                            <input type="text" class="phone-otp-digit otp-digit" maxlength="1" inputmode="numeric">
                                            <input type="text" class="phone-otp-digit otp-digit" maxlength="1" inputmode="numeric">
                                            <input type="text" class="phone-otp-digit otp-digit" maxlength="1" inputmode="numeric">
                                            <input type="text" class="phone-otp-digit otp-digit" maxlength="1" inputmode="numeric">
                                        </div>
                                        <div style="display: flex; gap: 10px; margin-top: 12px; flex-wrap: wrap;">
                                            <button type="button" class="btn btn-sm btn-primary" id="verifyPhoneOtpBtn" style="display: none;">
                                                <i class="bi bi-check-lg"></i> Verify OTP
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" id="resendPhoneOtpBtn" style="display: none;">
                                                <i class="bi bi-arrow-repeat"></i> Resend OTP
                                            </button>
                                        </div>
                                        <div id="phoneOtpStatus" class="otp-status"></div>
                                    </div>
                                </div>

                                <!-- Email with OTP -->
                                <div class="col-md-6 mb-3">
                                    <label>Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" id="email" value="{{ old('email', $employee->email) }}" required placeholder="example@company.com">
                                    <div class="email-edit-indicator" id="emailEditIndicator">
                                        <i class="bi bi-pencil-square"></i> Email changed - OTP verification required
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="sendEmailOtpBtn" style="display: none;">
                                            <i class="bi bi-envelope"></i> Send OTP to Email
                                        </button>
                                    </div>
                                    @if($errors->has('email'))
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                    @endif

                                    <div id="emailOtpSection" class="otp-verification-section" style="display: none;">
                                        <h6><i class="bi bi-shield-lock"></i> Email Verification</h6>
                                        <p style="color: #475569; font-size: 0.85rem; margin-bottom: 12px;">Enter the 4-digit OTP sent to <strong id="emailDisplay"></strong></p>
                                        <div class="otp-digit-container">
                                            <input type="text" class="email-otp-digit otp-digit" maxlength="1" inputmode="numeric">
                                            <input type="text" class="email-otp-digit otp-digit" maxlength="1" inputmode="numeric">
                                            <input type="text" class="email-otp-digit otp-digit" maxlength="1" inputmode="numeric">
                                            <input type="text" class="email-otp-digit otp-digit" maxlength="1" inputmode="numeric">
                                        </div>
                                        <div style="display: flex; gap: 10px; margin-top: 12px; flex-wrap: wrap;">
                                            <button type="button" class="btn btn-sm btn-primary" id="verifyEmailOtpBtn" style="display: none;">
                                                <i class="bi bi-check-lg"></i> Verify OTP
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" id="resendEmailOtpBtn" style="display: none;">
                                                <i class="bi bi-arrow-repeat"></i> Resend OTP
                                            </button>
                                        </div>
                                        <div id="emailOtpStatus" class="otp-status"></div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Gender <span class="text-danger">*</span></label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" value="male" {{ strtolower(old('gender', $employee->gender)) == 'male' ? 'checked' : '' }} required>
                                        <label class="form-check-label">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" value="female" {{ strtolower(old('gender', $employee->gender)) == 'female' ? 'checked' : '' }}>
                                        <label class="form-check-label">Female</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" value="other" {{ strtolower(old('gender', $employee->gender)) == 'other' ? 'checked' : '' }}>
                                        <label class="form-check-label">Other</label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Date of Birth <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control {{ $errors->has('dob') ? 'is-invalid' : '' }}" name="dob" value="{{ old('dob', $employee->dob) }}" required max="{{ date('Y-m-d') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Blood Group <span class="text-danger">*</span></label>
                                    <select class="form-select {{ $errors->has('blood_group') ? 'is-invalid' : '' }}" name="blood_group" required>
                                        <option value="">Select Blood Group</option>
                                        @php $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']; @endphp
                                        @foreach($bloodGroups as $bg)
                                        <option value="{{ $bg }}" {{ old('blood_group', $employee->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Nationality <span class="text-danger">*</span></label>
                                    <select class="form-select {{ $errors->has('nationality') ? 'is-invalid' : '' }}" name="nationality" required>
                                        <option value="">Select</option>
                                        @php $nationalities = ['Indian', 'American', 'British', 'Canadian', 'Australian', 'Others']; @endphp
                                        @foreach($nationalities as $nat)
                                        <option value="{{ $nat }}" {{ old('nationality', $employee->nationality) == $nat ? 'selected' : '' }}>{{ $nat }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Religion <span class="text-danger">*</span></label>
                                    <select class="form-select {{ $errors->has('religion') ? 'is-invalid' : '' }}" name="religion" required>
                                        <option value="">Select Religion</option>
                                        @php $religions = ['Hindu', 'Muslim', 'Christian', 'Sikh', 'Buddhist', 'Jain', 'Jewish', 'Others', 'Not Specified']; @endphp
                                        @foreach($religions as $rel)
                                        <option value="{{ $rel }}" {{ old('religion', $employee->religion) == $rel ? 'selected' : '' }}>{{ $rel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="section-card">
                        <div class="section-header">
                            <i class="bi bi-geo-alt"></i> Address Information
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Address Line 1 <span class="text-danger">*</span></label>
                                    <textarea class="form-control {{ $errors->has('addressline1') ? 'is-invalid' : '' }}" name="addressline1" required placeholder="House no, Street, Area">{{ old('addressline1', $employee->addressline1) }}</textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Address Line 2 <small class="text-muted">(Optional)</small></label>
                                    <textarea class="form-control" name="addressline2" placeholder="Landmark, Locality">{{ old('addressline2', $employee->addressline2) }}</textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>State <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}" name="state" value="{{ old('state', $employee->state) }}" required placeholder="State name">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>City <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" name="city" value="{{ old('city', $employee->city) }}" required placeholder="City name">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Pincode <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control {{ $errors->has('pincode') ? 'is-invalid' : '' }}" name="pincode" value="{{ old('pincode', $employee->pincode) }}" required maxlength="6" placeholder="6-digit pincode">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-actions end">
                        <button type="button" class="btn btn-primary" onclick="nextStep()">
                            Save & Next <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- ============================
                STEP 2 : PROFESSIONAL
                ============================ -->
                <div class="form-step" data-step="2">
                    <div class="section-card">
                        <div class="section-header">
                            <i class="bi bi-briefcase"></i> Professional Details
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Employment Type <span class="text-danger">*</span></label>
                                    <select class="form-select {{ $errors->has('employment_type') ? 'is-invalid' : '' }}" name="employment_type" required>
                                        <option value="">Select</option>
                                        <option value="Full-time" {{ old('employment_type', $employee->employment_type) == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                                        <option value="Part-time" {{ old('employment_type', $employee->employment_type) == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                                        <option value="Contract-based" {{ old('employment_type', $employee->employment_type) == 'Contract-based' ? 'selected' : '' }}>Contract-based</option>
                                        <option value="Probation-Period" {{ old('employment_type', $employee->employment_type) == 'Probation-Period' ? 'selected' : '' }}>Probation Period</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3" id="probationDaysWrapper">
                                    <label>Probation Days <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control {{ $errors->has('probation_days') ? 'is-invalid' : '' }}" name="probation_days" value="{{ old('probation_days', $employee->probation_days) }}" min="1" max="365">
                                    <small class="text-muted">Number of days for probation period (1-365)</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Salary Type <span class="text-danger">*</span></label>
                                    <select class="form-select {{ $errors->has('salary_type') ? 'is-invalid' : '' }}" name="salary_type" required>
                                        <option value="">Select</option>
                                        <option value="Monthly" {{ old('salary_type', $employee->salary_type) == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="Hourly" {{ old('salary_type', $employee->salary_type) == 'Hourly' ? 'selected' : '' }}>Hourly</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Date of Joining <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control {{ $errors->has('doj') ? 'is-invalid' : '' }}" name="doj" value="{{ old('doj', $employee->doj) }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>PF Number (UAN) <small class="text-muted">(Optional)</small></label>
                                    <input type="text" class="form-control {{ $errors->has('previous_pf_number') ? 'is-invalid' : '' }}" name="previous_pf_number" value="{{ old('previous_pf_number', $employee->previous_pf_number) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>ESI Number <small class="text-muted">(Optional)</small></label>
                                    <input type="text" class="form-control {{ $errors->has('esi_number') ? 'is-invalid' : '' }}" name="esi_number" value="{{ old('esi_number', $employee->esi_number) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Previous Employer <small class="text-muted">(Optional)</small></label>
                                    <input type="text" class="form-control" name="previous_employer_name" value="{{ old('previous_employer_name', $employee->previous_employer_name) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Date of Exit <small class="text-muted">(Last Organization)</small></label>
                                    <input type="date" class="form-control" name="previous_exit_date" value="{{ old('previous_exit_date', $employee->previous_exit_date) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-actions">
                        <button type="button" class="btn btn-secondary" onclick="prevStep()">
                            <i class="bi bi-arrow-left"></i> Previous
                        </button>
                        <button type="button" class="btn btn-primary" onclick="nextStep()">
                            Save & Next <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- ============================
                STEP 3 : CONTACT
                ============================ -->
                <div class="form-step" data-step="3">
                    <div class="section-card">
                        <div class="section-header">
                            <i class="bi bi-telephone"></i> Emergency Contact Details
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label>Emergency Contact Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control {{ $errors->has('emergency_contact_number') ? 'is-invalid' : '' }}" name="emergency_contact_number" value="{{ old('emergency_contact_number', $employee->emergency_contact_number) }}" required placeholder="10-digit number">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label>Contact Person Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control {{ $errors->has('contact_person_name') ? 'is-invalid' : '' }}" name="contact_person_name" value="{{ old('contact_person_name', $employee->contact_person_name) }}" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label>Relation <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control {{ $errors->has('relation_with_contact') ? 'is-invalid' : '' }}" name="relation_with_contact" value="{{ old('relation_with_contact', $employee->relation_with_contact) }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Reference Name <small class="text-muted">(Optional)</small></label>
                                    <input type="text" class="form-control" name="reference_name" value="{{ old('reference_name', $employee->reference_name) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Reference Contact Number <small class="text-muted">(Optional)</small></label>
                                    <input type="tel" class="form-control" name="reference_contact_number" value="{{ old('reference_contact_number', $employee->reference_contact_number) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-actions">
                        <button type="button" class="btn btn-secondary" onclick="prevStep()">
                            <i class="bi bi-arrow-left"></i> Previous
                        </button>
                        <button type="button" class="btn btn-primary" onclick="nextStep()">
                            Save & Next <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- ============================
                STEP 4 : DOCUMENTS
                ============================ -->
                <div class="form-step" data-step="4">
                    <div class="section-card">
                        <div class="section-header">
                            <i class="bi bi-file-earmark-text"></i> Document Details
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Aadhaar Card <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="aadhaar_card">
                                    @if($employee->aadhaar_card)
                                    <small class="text-muted">Current: <a href="{{ route('image', ['path' => $employee->aadhaar_card]) }}" target="_blank">View</a></small>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Aadhaar Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control {{ $errors->has('aadhaar_number') ? 'is-invalid' : '' }}" name="aadhaar_number" value="{{ old('aadhaar_number', $employee->aadhaar_number) }}" placeholder="12-digit number">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>PAN Card <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control {{ $errors->has('pan_card') ? 'is-invalid' : '' }}" name="pan_card">
                                    @if($employee->pan_card)
                                    <small class="text-muted">Current: <a href="{{ route('image', ['path' => $employee->pan_card]) }}" target="_blank">View</a></small>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>PAN Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control {{ $errors->has('pan_number') ? 'is-invalid' : '' }}" name="pan_number" value="{{ old('pan_number', $employee->pan_number) }}" placeholder="ABCDE1234F">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Is Communication Address Same? <span class="text-danger">*</span></label>
                                    <select class="form-select {{ $errors->has('is_address_same') ? 'is-invalid' : '' }}" name="is_address_same" id="is_address_same">
                                        <option value="">Select</option>
                                        <option value="yes" {{ old('is_address_same', $employee->is_address_same) == 'yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="no" {{ old('is_address_same', $employee->is_address_same) == 'no' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>

                                <div class="col-md-6 d-none" id="addressProofTypeWrapper">
                                    <div class="mb-3">
                                        <label>Address Proof Type</label>
                                        <select class="form-select {{ $errors->has('address_proof_type') ? 'is-invalid' : '' }}" id="address_proof_type" name="address_proof_type">
                                            <option value="">Select Document Type</option>
                                            <option value="driving_license" {{ old('address_proof_type', $employee->address_proof_type) == 'driving_license' ? 'selected' : '' }}>Driving License</option>
                                            <option value="passport" {{ old('address_proof_type', $employee->address_proof_type) == 'passport' ? 'selected' : '' }}>Passport</option>
                                            <option value="voter_id" {{ old('address_proof_type', $employee->address_proof_type) == 'voter_id' ? 'selected' : '' }}>Voter ID</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row d-none" id="addressProofFileWrapper">
                                <div class="col-md-6 mb-3">
                                    <label>Upload Address Proof</label>
                                    <input type="file" class="form-control {{ $errors->has('address_proof_file') ? 'is-invalid' : '' }}" id="address_proof_file" name="address_proof_file">
                                    @if($employee->address_proof_file)
                                    <small class="text-muted">Current: <a href="{{ route('image', ['path' => $employee->address_proof_file]) }}" target="_blank">View</a></small>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Address Proof Number</label>
                                    <input type="text" class="form-control {{ $errors->has('address_proof_number') ? 'is-invalid' : '' }}" name="address_proof_number" value="{{ old('address_proof_number', $employee->address_proof_number) }}">
                                </div>
                            </div>

                            <hr>

                            <div class="my-3">
                                <div class="d-flex w-100 justify-content-between mb-3 align-items-center">
                                    <h6 class="fw-bold mb-0">Additional Documents</h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addDocumentRow()">
                                        <i class="bi bi-plus-lg"></i> Add Document
                                    </button>
                                </div>

                                <div id="existingDocumentsContainer">
                                    @php
                                    $existingDocuments = [];
                                    if (!empty($employee->additional_documents)) {
                                        $existingDocuments = json_decode($employee->additional_documents, true);
                                        if (json_last_error() !== JSON_ERROR_NONE) {
                                            $existingDocuments = [];
                                        }
                                    }
                                    $oldDocuments = old('documents', []);
                                    $documents = !empty($oldDocuments) ? $oldDocuments : $existingDocuments;
                                    @endphp

                                    @if(count($documents) > 0)
                                        @foreach($documents as $index => $document)
                                        <div class="row mb-3 document-row align-items-end">
                                            <div class="col-md-4">
                                                <label>Document Name</label>
                                                <input type="text" class="form-control" name="documents[{{ $index }}][name]" value="{{ $document['name'] ?? '' }}" placeholder="e.g. Experience Letter">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Document Number</label>
                                                <input type="text" class="form-control" name="documents[{{ $index }}][number]" value="{{ $document['number'] ?? '' }}" placeholder="Document Number">
                                            </div>
                                            <div class="col-md-3">
                                                <label>Upload File</label>
                                                <input type="file" class="form-control" name="documents[{{ $index }}][file]">
                                                @if(isset($document['file']) && !empty($document['file']))
                                                <input type="hidden" name="documents[{{ $index }}][existing_file]" value="{{ $document['file'] }}">
                                                <small class="text-muted d-block mt-1">Current: <a href="{{ route('image', ['path' => $document['file']]) }}" target="_blank">View</a></small>
                                                @endif
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger btn-sm remove-doc-btn" onclick="removeDocumentRow(this)"><i class="bi bi-x-lg"></i></button>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="row mb-3 document-row align-items-end">
                                            <div class="col-md-4">
                                                <label>Document Name</label>
                                                <input type="text" class="form-control" name="documents[0][name]" placeholder="e.g. Experience Letter">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Document Number</label>
                                                <input type="text" class="form-control" name="documents[0][number]" placeholder="Document Number">
                                            </div>
                                            <div class="col-md-3">
                                                <label>Upload File</label>
                                                <input type="file" class="form-control" name="documents[0][file]">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger btn-sm remove-doc-btn" onclick="removeDocumentRow(this)"><i class="bi bi-x-lg"></i></button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div id="customDocumentsContainer"></div>
                            </div>
                        </div>
                    </div>

                    <div class="step-actions">
                        <button type="button" class="btn btn-secondary" onclick="prevStep()">
                            <i class="bi bi-arrow-left"></i> Previous
                        </button>
                        <button type="button" class="btn btn-primary" onclick="nextStep()">
                            Save & Next <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- ============================
                STEP 5 : BANK
                ============================ -->
                <div class="form-step" data-step="5">
                    <div class="section-card">
                        <div class="section-header">
                            <i class="bi bi-bank"></i> Bank Details
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Bank Name</label>
                                    <input type="text" class="form-control {{ $errors->has('bank_name') ? 'is-invalid' : '' }}" name="bank_name" value="{{ old('bank_name', $employee->bank_name) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Branch Name</label>
                                    <input type="text" class="form-control {{ $errors->has('branch_name') ? 'is-invalid' : '' }}" name="branch_name" value="{{ old('branch_name', $employee->branch_name) }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Account Number</label>
                                    <input type="text" class="form-control {{ $errors->has('account_number') ? 'is-invalid' : '' }}" name="account_number" value="{{ old('account_number', $employee->account_number) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>IFSC Code</label>
                                    <input type="text" class="form-control {{ $errors->has('ifsc_code') ? 'is-invalid' : '' }}" name="ifsc_code" value="{{ old('ifsc_code', $employee->ifsc_code) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-actions">
                        <button type="button" class="btn btn-secondary" onclick="prevStep()">
                            <i class="bi bi-arrow-left"></i> Previous
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-lg"></i> Update Details
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let currentStep = 0;
let maxCompletedStep = 0;
let documentIndex = 0;
let editableStep = 0;
const steps = document.querySelectorAll('.form-step');
const timelineSteps = document.querySelectorAll('.timeline-step');
const progressLine = document.getElementById('timelineProgress');

/* ============================
OTP VERIFICATION VARIABLES
============================ */
let originalPhone = '';
let originalEmail = '';
let isPhoneEdited = false;
let isEmailEdited = false;
let isPhoneVerified = false;
let isEmailVerified = false;
let phoneGeneratedOTP = '';
let emailGeneratedOTP = '';
let phoneOtpTimer = null;
let emailOtpTimer = null;

/* ============================
SHOW STEP
============================ */
function showStep(index) {
    if (index < 0 || index >= steps.length) return;

    steps.forEach((step, i) => {
        step.classList.toggle('active', i === index);
    });

    timelineSteps.forEach((step, i) => {
        step.classList.remove('active', 'completed');
        if (i < index) step.classList.add('completed');
        else if (i === index) step.classList.add('active');
    });

    const progressPercent = (index / (timelineSteps.length - 1)) * 80;
    progressLine.style.width = progressPercent + '%';
    currentStep = index;
}

/* ============================
TOGGLE PROBATION DAYS FIELD
============================ */
function toggleProbationDaysField() {
    const employmentType = document.querySelector('[name="employment_type"]');
    const probationDaysWrapper = document.getElementById('probationDaysWrapper');
    
    if (!employmentType || !probationDaysWrapper) return;
    
    if (employmentType.value === 'Probation-Period') {
        probationDaysWrapper.style.display = 'block';
        const probationDaysInput = document.querySelector('[name="probation_days"]');
        if (probationDaysInput) {
            probationDaysInput.setAttribute('required', 'required');
        }
    } else {
        probationDaysWrapper.style.display = 'none';
        const probationDaysInput = document.querySelector('[name="probation_days"]');
        if (probationDaysInput) {
            probationDaysInput.removeAttribute('required');
            probationDaysInput.classList.remove('is-invalid');
        }
    }
}

/* ============================
VALIDATE CURRENT STEP
============================ */
function validateCurrentStep() {
    if (isPhoneEdited && !isPhoneVerified) {
        alert('Please verify your phone number with OTP before proceeding.');
        const phoneSection = document.getElementById('phoneOtpSection');
        if (phoneSection) phoneSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
    
    if (isEmailEdited && !isEmailVerified) {
        alert('Please verify your email address with OTP before proceeding.');
        const emailSection = document.getElementById('emailOtpSection');
        if (emailSection) emailSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
    
    const fields = steps[currentStep].querySelectorAll('input, select, textarea');
    
    if (currentStep === 1) {
        const employmentType = document.querySelector('[name="employment_type"]');
        const probationDays = document.querySelector('[name="probation_days"]');
        
        if (employmentType && employmentType.value === 'Probation-Period') {
            if (!probationDays || !probationDays.value || probationDays.value < 1) {
                alert('Please enter the number of probation days (minimum 1 day)');
                if (probationDays) {
                    probationDays.focus();
                    probationDays.classList.add('is-invalid');
                }
                return false;
            }
            if (probationDays && probationDays.value > 365) {
                alert('Probation days cannot exceed 365 days');
                if (probationDays) {
                    probationDays.focus();
                    probationDays.classList.add('is-invalid');
                }
                return false;
            }
        }
    }
    
    for (let field of fields) {
        if (field.name === 'probation_days') {
            const employmentType = document.querySelector('[name="employment_type"]');
            if (employmentType && employmentType.value !== 'Probation-Period') {
                continue;
            }
        }
        
        if (field.required && !field.value) {
            field.reportValidity();
            return false;
        }
    }
    return true;
}

/* ============================
NEXT / PREV STEP
============================ */
function nextStep() {
    if (!validateCurrentStep()) return;

    saveCurrentStep(function() {
        if (currentStep < steps.length - 1) {
            showStep(currentStep + 1);
        }
    }, true);
}

function prevStep() {
    if (currentStep > 0) {
        saveCurrentStep(function() {
            showStep(currentStep - 1);
        }, false);
    }
}

function saveCurrentStep(callback, showSuccessToast = true) {
    const form = document.getElementById('employeeForm');
    const formData = new FormData(form);
    
    formData.delete('_method');
    formData.append('current_step', currentStep + 1);
    formData.append('is_partial_update', 'true');
    
    const nextBtn = document.querySelector('.form-step.active .btn-primary:not(.btn-secondary)');
    if (nextBtn) {
        nextBtn.disabled = true;
        nextBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                     document.querySelector('input[name="_token"]')?.value;
    
    const employeeId = '{{ $employee->employee_id }}';
    
    fetch('/employees/update-partial/' + employeeId, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (data.updated_values) {
                if (data.updated_values.mobile_number) {
                    originalPhone = data.updated_values.mobile_number;
                    const phoneInput = document.getElementById('mobile_number');
                    if (phoneInput) phoneInput.value = originalPhone;
                }
                if (data.updated_values.email) {
                    originalEmail = data.updated_values.email;
                    const emailInput = document.getElementById('email');
                    if (emailInput) emailInput.value = originalEmail;
                }
            }
            
            isPhoneEdited = false;
            isEmailEdited = false;
            
            const phoneIndicator = document.getElementById('phoneEditIndicator');
            const emailIndicator = document.getElementById('emailEditIndicator');
            const sendPhoneBtn = document.getElementById('sendPhoneOtpBtn');
            const sendEmailBtn = document.getElementById('sendEmailOtpBtn');
            
            if (phoneIndicator) phoneIndicator.style.display = 'none';
            if (emailIndicator) emailIndicator.style.display = 'none';
            if (sendPhoneBtn) sendPhoneBtn.style.display = 'none';
            if (sendEmailBtn) sendEmailBtn.style.display = 'none';
            
            if (showSuccessToast) {
                showToast('Step saved successfully!', 'success');
            }
            
            if (callback) callback();
        } else {
            alert('Error saving data: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error saving data. Please check console for details.');
    })
    .finally(() => {
        if (nextBtn) {
            nextBtn.disabled = false;
            nextBtn.innerHTML = 'Save & Next <i class="bi bi-arrow-right"></i>';
        }
    });
}

function showToast(message, type = 'success') {
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'toast-container';
        document.body.appendChild(toastContainer);
    }
    
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show toast-message`;
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    toastContainer.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) toast.remove();
    }, 3000);
}

/* ============================
TIMELINE CLICK
============================ */
timelineSteps.forEach((step, index) => {
    step.addEventListener('click', () => {
        showStep(index);
    });
});

/* ============================
DESIGNATION → HIDDEN FIELDS
============================ */
const designationSelect = document.getElementById('designationSelect');
const designationNameInput = document.getElementById('designationName');
const assignedRoleInput = document.getElementById('assignedRole');

if (designationSelect) {
    designationSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        designationNameInput.value = selectedOption.getAttribute('data-name') || '';
        const role = selectedOption.getAttribute('data-roles');
        assignedRoleInput.value = role && role !== 'null' ? role : 'employee';
    });
}

/* ============================
LOAD DEPARTMENTS BY CATEGORY
============================ */
function loadDepartments(categoryId, selectedDepartmentId = null) {
    const departmentSelect = document.getElementById('department_id');
    if (!categoryId) {
        departmentSelect.innerHTML = '<option value="">Select Department</option>';
        return;
    }
    departmentSelect.innerHTML = '<option value="">Loading...</option>';
    fetch(`/ajax/departments-by-category?category_id=${categoryId}`)
        .then(res => res.json())
        .then(data => {
            let options = '<option value="">Select Department</option>';
            if (data.departments && Array.isArray(data.departments)) {
                data.departments.forEach(department => {
                    const isSelected = department.department_id == selectedDepartmentId ? 'selected' : '';
                    options += `<option value="${department.department_id}" ${isSelected}>${department.department}</option>`;
                });
            }
            departmentSelect.innerHTML = options;
        })
        .catch(() => {
            departmentSelect.innerHTML = '<option value="">Error loading</option>';
        });
}

/* ============================
ADDRESS PROOF TOGGLE
============================ */
function toggleAddressProofFields() {
    const isAddressSame = document.getElementById('is_address_same');
    const proofTypeWrapper = document.getElementById('addressProofTypeWrapper');
    const proofFileWrapper = document.getElementById('addressProofFileWrapper');

    if (isAddressSame && isAddressSame.value === 'no') {
        proofTypeWrapper?.classList.remove('d-none');
        proofFileWrapper?.classList.remove('d-none');
    } else {
        proofTypeWrapper?.classList.add('d-none');
        proofFileWrapper?.classList.add('d-none');
    }
}

/* ============================
ADD/REMOVE DOCUMENT ROWS
============================ */
let documentCounter = {{ isset($documents) ? count($documents) : 1 }};

function addDocumentRow() {
    const container = document.getElementById('customDocumentsContainer');
    const row = document.createElement('div');
    row.classList.add('row', 'mb-3', 'document-row', 'align-items-end');
    row.innerHTML = `
        <div class="col-md-4">
            <label>Document Name</label>
            <input type="text" class="form-control" name="documents[${documentCounter}][name]" placeholder="e.g. Experience Letter">
        </div>
        <div class="col-md-4">
            <label>Document Number</label>
            <input type="text" class="form-control" name="documents[${documentCounter}][number]" placeholder="Document Number">
        </div>
        <div class="col-md-3">
            <label>Upload File</label>
            <input type="file" class="form-control" name="documents[${documentCounter}][file]">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-sm remove-doc-btn" onclick="removeDocumentRow(this)"><i class="bi bi-x-lg"></i></button>
        </div>
    `;
    container.appendChild(row);
    documentCounter++;
}

function removeDocumentRow(button) {
    const row = button.closest('.document-row');
    row.remove();
    const allRows = document.querySelectorAll('.document-row');
    if (allRows.length === 0) {
        documentCounter = 0;
        addDocumentRow();
    }
}

/* ============================
OTP VERIFICATION FUNCTIONS
============================ */
function setupPhoneWatcher() {
    const phoneInput = document.getElementById('mobile_number');
    if (!phoneInput) return;
    
    originalPhone = phoneInput.value;
    
    function updateButtonVisibility() {
        const currentValue = phoneInput.value.trim();
        const isEdited = currentValue !== originalPhone && currentValue.length === 10 && /^\d{10}$/.test(currentValue);
        
        isPhoneEdited = isEdited;
        
        const indicator = document.getElementById('phoneEditIndicator');
        if (indicator) indicator.style.display = isEdited ? 'block' : 'none';
        
        const sendBtn = document.getElementById('sendPhoneOtpBtn');
        if (sendBtn) sendBtn.style.display = isEdited ? 'inline-flex' : 'none';
        
        if (isEdited) resetPhoneVerification();
    }
    
    phoneInput.addEventListener('input', updateButtonVisibility);
    phoneInput.addEventListener('change', updateButtonVisibility);
    updateButtonVisibility();
}

function setupEmailWatcher() {
    const emailInput = document.getElementById('email');
    if (!emailInput) return;
    
    originalEmail = emailInput.value;
    
    function updateButtonVisibility() {
        const currentValue = emailInput.value.trim();
        const isEdited = currentValue !== originalEmail && currentValue && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(currentValue);
        
        isEmailEdited = isEdited;
        
        const indicator = document.getElementById('emailEditIndicator');
        if (indicator) indicator.style.display = isEdited ? 'block' : 'none';
        
        const sendBtn = document.getElementById('sendEmailOtpBtn');
        if (sendBtn) sendBtn.style.display = isEdited ? 'inline-flex' : 'none';
        
        if (isEdited) resetEmailVerification();
    }
    
    emailInput.addEventListener('input', updateButtonVisibility);
    emailInput.addEventListener('change', updateButtonVisibility);
    updateButtonVisibility();
}

function resetPhoneVerification() {
    isPhoneVerified = false;
    const otpSection = document.getElementById('phoneOtpSection');
    if (otpSection) otpSection.style.display = 'none';
    const statusDiv = document.getElementById('phoneOtpStatus');
    if (statusDiv) {
        statusDiv.className = 'otp-status';
        statusDiv.style.display = 'none';
    }
    if (phoneOtpTimer) clearInterval(phoneOtpTimer);
}

function resetEmailVerification() {
    isEmailVerified = false;
    const otpSection = document.getElementById('emailOtpSection');
    if (otpSection) otpSection.style.display = 'none';
    const statusDiv = document.getElementById('emailOtpStatus');
    if (statusDiv) {
        statusDiv.className = 'otp-status';
        statusDiv.style.display = 'none';
    }
    if (emailOtpTimer) clearInterval(emailOtpTimer);
}

function showPhoneOtpSection() {
    const phone = document.getElementById('mobile_number').value;
    if (!phone || phone.length !== 10) return;
    
    const otpSection = document.getElementById('phoneOtpSection');
    const phoneDisplay = document.getElementById('phoneDisplay');
    
    if (otpSection) otpSection.style.display = 'block';
    if (phoneDisplay) phoneDisplay.textContent = phone;
    
    document.querySelectorAll('.phone-otp-digit').forEach(input => {
        input.value = '';
        input.disabled = false;
    });
    
    const statusDiv = document.getElementById('phoneOtpStatus');
    if (statusDiv) {
        statusDiv.className = 'otp-status';
        statusDiv.style.display = 'none';
    }
    
    const firstDigit = document.querySelector('.phone-otp-digit');
    if (firstDigit) firstDigit.focus();
}

function showEmailOtpSection() {
    const email = document.getElementById('email').value;
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return;
    
    const otpSection = document.getElementById('emailOtpSection');
    const emailDisplay = document.getElementById('emailDisplay');
    
    if (otpSection) otpSection.style.display = 'block';
    if (emailDisplay) emailDisplay.textContent = email;
    
    document.querySelectorAll('.email-otp-digit').forEach(input => {
        input.value = '';
        input.disabled = false;
    });
    
    const statusDiv = document.getElementById('emailOtpStatus');
    if (statusDiv) {
        statusDiv.className = 'otp-status';
        statusDiv.style.display = 'none';
    }
    
    const firstDigit = document.querySelector('.email-otp-digit');
    if (firstDigit) firstDigit.focus();
}

function startPhoneOTPTimer() {
    let timeLeft = 120;
    const verifyBtn = document.getElementById('verifyPhoneOtpBtn');
    const resendBtn = document.getElementById('resendPhoneOtpBtn');
    const sendBtn = document.getElementById('sendPhoneOtpBtn');
    
    if (verifyBtn) {
        verifyBtn.style.display = 'inline-flex';
        verifyBtn.disabled = false;
        verifyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Verify OTP';
    }
    if (resendBtn) resendBtn.style.display = 'none';
    if (sendBtn) sendBtn.style.display = 'none';
    
    if (phoneOtpTimer) clearInterval(phoneOtpTimer);
    
    phoneOtpTimer = setInterval(function() {
        timeLeft--;
        let minutes = Math.floor(timeLeft / 60);
        let seconds = timeLeft % 60;
        
        if (verifyBtn && verifyBtn.style.display !== 'none' && !isPhoneVerified) {
            verifyBtn.innerHTML = `<i class="bi bi-check-lg"></i> Verify OTP (${minutes}:${seconds.toString().padStart(2, '0')})`;
        }
        
        if (timeLeft <= 0) {
            clearInterval(phoneOtpTimer);
            if (verifyBtn && !isPhoneVerified) {
                verifyBtn.style.display = 'none';
                verifyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Verify OTP';
            }
            if (resendBtn && !isPhoneVerified) {
                resendBtn.style.display = 'inline-flex';
            }
            if (!isPhoneVerified) {
                const statusDiv = document.getElementById('phoneOtpStatus');
                if (statusDiv) {
                    statusDiv.className = 'otp-status error';
                    statusDiv.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> OTP expired! Click "Resend OTP" to get a new code.';
                    statusDiv.style.display = 'block';
                }
            }
        }
    }, 1000);
}

function startEmailOTPTimer() {
    let timeLeft = 120;
    const verifyBtn = document.getElementById('verifyEmailOtpBtn');
    const resendBtn = document.getElementById('resendEmailOtpBtn');
    const sendBtn = document.getElementById('sendEmailOtpBtn');
    
    if (verifyBtn) {
        verifyBtn.style.display = 'inline-flex';
        verifyBtn.disabled = false;
        verifyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Verify OTP';
    }
    if (resendBtn) resendBtn.style.display = 'none';
    if (sendBtn) sendBtn.style.display = 'none';
    
    if (emailOtpTimer) clearInterval(emailOtpTimer);
    
    emailOtpTimer = setInterval(function() {
        timeLeft--;
        let minutes = Math.floor(timeLeft / 60);
        let seconds = timeLeft % 60;
        
        if (verifyBtn && verifyBtn.style.display !== 'none' && !isEmailVerified) {
            verifyBtn.innerHTML = `<i class="bi bi-check-lg"></i> Verify OTP (${minutes}:${seconds.toString().padStart(2, '0')})`;
        }
        
        if (timeLeft <= 0) {
            clearInterval(emailOtpTimer);
            if (verifyBtn && !isEmailVerified) {
                verifyBtn.style.display = 'none';
                verifyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Verify OTP';
            }
            if (resendBtn && !isEmailVerified) {
                resendBtn.style.display = 'inline-flex';
            }
            if (!isEmailVerified) {
                const statusDiv = document.getElementById('emailOtpStatus');
                if (statusDiv) {
                    statusDiv.className = 'otp-status error';
                    statusDiv.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> OTP expired! Click "Resend OTP" to get a new code.';
                    statusDiv.style.display = 'block';
                }
            }
        }
    }, 1000);
}

async function sendPhoneOTP() {
    if (!isPhoneEdited) {
        alert('Phone number not changed. OTP verification only required for edited numbers.');
        return;
    }
    
    const phone = document.getElementById('mobile_number').value.trim();
    if (!phone || phone.length !== 10) {
        alert('Please enter a valid 10-digit phone number');
        return;
    }
    
    const btn = document.getElementById('sendPhoneOtpBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Sending...';
    btn.disabled = true;
    
    isPhoneVerified = false;
    
    document.querySelectorAll('.phone-otp-digit').forEach(input => {
        input.value = '';
        input.disabled = false;
    });
    
    try {
        const response = await fetch('/send/otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ mobile_number: phone })
        });
        
        const result = await response.json();
        
        if (result.Success) {
            phoneGeneratedOTP = result.Success;
            alert('OTP sent successfully! Check your phone.');
            showPhoneOtpSection();
            startPhoneOTPTimer();
        } else {
            phoneGeneratedOTP = Math.floor(1000 + Math.random() * 9000).toString();
            alert(`Demo OTP: ${phoneGeneratedOTP}`);
            showPhoneOtpSection();
            startPhoneOTPTimer();
        }
    } catch (error) {
        phoneGeneratedOTP = Math.floor(1000 + Math.random() * 9000).toString();
        alert(`Demo OTP: ${phoneGeneratedOTP}`);
        showPhoneOtpSection();
        startPhoneOTPTimer();
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

async function sendEmailOTP() {
    if (!isEmailEdited) {
        alert('Email not changed. OTP verification only required for edited emails.');
        return;
    }
    
    const email = document.getElementById('email').value.trim();
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert('Please enter a valid email address');
        return;
    }
    
    const btn = document.getElementById('sendEmailOtpBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Sending...';
    btn.disabled = true;
    
    isEmailVerified = false;
    
    document.querySelectorAll('.email-otp-digit').forEach(input => {
        input.value = '';
        input.disabled = false;
    });
    
    try {
        const response = await fetch('/send-email-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                email_id: email,
                otp_verification_type: 'employee_email_verification'
            })
        });
        
        const result = await response.json();
        
        if (result.success || result.Success) {
            emailGeneratedOTP = result.otp || Math.floor(1000 + Math.random() * 9000).toString();
            alert('OTP sent to email!');
            showEmailOtpSection();
            startEmailOTPTimer();
        } else {
            emailGeneratedOTP = Math.floor(1000 + Math.random() * 9000).toString();
            alert(`Demo OTP: ${emailGeneratedOTP}`);
            showEmailOtpSection();
            startEmailOTPTimer();
        }
    } catch (error) {
        emailGeneratedOTP = Math.floor(1000 + Math.random() * 9000).toString();
        alert(`Demo OTP: ${emailGeneratedOTP}`);
        showEmailOtpSection();
        startEmailOTPTimer();
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

async function verifyPhoneOTP() {
    let enteredOTP = '';
    document.querySelectorAll('.phone-otp-digit').forEach(input => {
        enteredOTP += input.value;
    });
    
    if (enteredOTP.length !== 4) {
        alert('Please enter the complete 4-digit OTP');
        return;
    }
    
    const verifyBtn = document.getElementById('verifyPhoneOtpBtn');
    const originalText = verifyBtn.innerHTML;
    verifyBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Verifying...';
    verifyBtn.disabled = true;
    
    try {
        const phone = document.getElementById('mobile_number').value;
        const response = await fetch('/verify/otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                mobile_number: phone,
                otp: enteredOTP
            })
        });
        
        const result = await response.json();
        
        if (result.Success || enteredOTP === phoneGeneratedOTP) {
            isPhoneVerified = true;
            alert('Phone number verified successfully!');
            
            const statusDiv = document.getElementById('phoneOtpStatus');
            if (statusDiv) {
                statusDiv.className = 'otp-status success';
                statusDiv.innerHTML = '<i class="bi bi-check-circle-fill"></i> Phone number verified!';
                statusDiv.style.display = 'block';
            }
            
            document.querySelectorAll('.phone-otp-digit').forEach(input => {
                input.disabled = true;
            });
            
            const resendBtn = document.getElementById('resendPhoneOtpBtn');
            if (resendBtn) resendBtn.style.display = 'none';
            if (verifyBtn) verifyBtn.style.display = 'none';
            
            if (phoneOtpTimer) clearInterval(phoneOtpTimer);
        } else {
            alert('Invalid OTP. Please try again.');
            document.querySelectorAll('.phone-otp-digit').forEach(input => {
                input.value = '';
            });
            const firstDigit = document.querySelector('.phone-otp-digit');
            if (firstDigit) firstDigit.focus();
        }
    } catch (error) {
        if (enteredOTP === phoneGeneratedOTP) {
            isPhoneVerified = true;
            alert('Phone number verified!');
            const statusDiv = document.getElementById('phoneOtpStatus');
            if (statusDiv) {
                statusDiv.className = 'otp-status success';
                statusDiv.innerHTML = '<i class="bi bi-check-circle-fill"></i> Phone number verified!';
                statusDiv.style.display = 'block';
            }
            if (verifyBtn) verifyBtn.style.display = 'none';
        } else {
            alert('Error verifying OTP. Please try again.');
        }
    } finally {
        verifyBtn.innerHTML = originalText;
        verifyBtn.disabled = false;
    }
}

async function verifyEmailOTP() {
    let enteredOTP = '';
    document.querySelectorAll('.email-otp-digit').forEach(input => {
        enteredOTP += input.value;
    });
    
    if (enteredOTP.length !== 4) {
        alert('Please enter the complete 4-digit OTP');
        return;
    }
    
    const verifyBtn = document.getElementById('verifyEmailOtpBtn');
    const originalText = verifyBtn.innerHTML;
    verifyBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Verifying...';
    verifyBtn.disabled = true;
    
    try {
        const email = document.getElementById('email').value;
        const response = await fetch('/verify-email-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                otp: enteredOTP,
                email: email,
                otp_verification_type: 'employee_email_verification'
            })
        });
        
        const result = await response.json();
        
        if (result.success || result.Success || enteredOTP === emailGeneratedOTP) {
            isEmailVerified = true;
            alert('Email verified successfully!');
            
            const statusDiv = document.getElementById('emailOtpStatus');
            if (statusDiv) {
                statusDiv.className = 'otp-status success';
                statusDiv.innerHTML = '<i class="bi bi-check-circle-fill"></i> Email verified!';
                statusDiv.style.display = 'block';
            }
            
            document.querySelectorAll('.email-otp-digit').forEach(input => {
                input.disabled = true;
            });
            
            const resendBtn = document.getElementById('resendEmailOtpBtn');
            if (resendBtn) resendBtn.style.display = 'none';
            if (verifyBtn) verifyBtn.style.display = 'none';
            
            if (emailOtpTimer) clearInterval(emailOtpTimer);
        } else {
            alert('Invalid OTP. Please try again.');
            document.querySelectorAll('.email-otp-digit').forEach(input => {
                input.value = '';
            });
            const firstDigit = document.querySelector('.email-otp-digit');
            if (firstDigit) firstDigit.focus();
        }
    } catch (error) {
        if (enteredOTP === emailGeneratedOTP) {
            isEmailVerified = true;
            alert('Email verified!');
            const statusDiv = document.getElementById('emailOtpStatus');
            if (statusDiv) {
                statusDiv.className = 'otp-status success';
                statusDiv.innerHTML = '<i class="bi bi-check-circle-fill"></i> Email verified!';
                statusDiv.style.display = 'block';
            }
            if (verifyBtn) verifyBtn.style.display = 'none';
        } else {
            alert('Error verifying OTP. Please try again.');
        }
    } finally {
        verifyBtn.innerHTML = originalText;
        verifyBtn.disabled = false;
    }
}

function setupOtpDigitHandlers() {
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('phone-otp-digit')) {
            if (e.target.value.length === 1 && /^\d$/.test(e.target.value)) {
                const next = e.target.nextElementSibling;
                if (next && next.classList.contains('phone-otp-digit')) {
                    next.focus();
                }
            }
        }
        if (e.target.classList.contains('email-otp-digit')) {
            if (e.target.value.length === 1 && /^\d$/.test(e.target.value)) {
                const next = e.target.nextElementSibling;
                if (next && next.classList.contains('email-otp-digit')) {
                    next.focus();
                }
            }
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.target.classList.contains('phone-otp-digit') || e.target.classList.contains('email-otp-digit')) {
            if (e.key === 'Backspace' && e.target.value === '') {
                const prev = e.target.previousElementSibling;
                if (prev && (prev.classList.contains('phone-otp-digit') || prev.classList.contains('email-otp-digit'))) {
                    prev.focus();
                }
            }
        }
    });
}

/* ============================
DOCUMENT READY INITIALIZATION
============================ */
document.addEventListener('DOMContentLoaded', function() {
    setupPhoneWatcher();
    setupEmailWatcher();
    setupOtpDigitHandlers();
    
    const employmentTypeSelect = document.querySelector('[name="employment_type"]');
    if (employmentTypeSelect) {
        toggleProbationDaysField();
        employmentTypeSelect.addEventListener('change', function() {
            toggleProbationDaysField();
            const probationDaysInput = document.querySelector('[name="probation_days"]');
            if (probationDaysInput && this.value !== 'Probation-Period') {
                probationDaysInput.classList.remove('is-invalid');
            }
        });
    }
    
    const sendPhoneBtn = document.getElementById('sendPhoneOtpBtn');
    if (sendPhoneBtn) sendPhoneBtn.addEventListener('click', sendPhoneOTP);
    
    const verifyPhoneBtn = document.getElementById('verifyPhoneOtpBtn');
    if (verifyPhoneBtn) verifyPhoneBtn.addEventListener('click', verifyPhoneOTP);
    
    const resendPhoneBtn = document.getElementById('resendPhoneOtpBtn');
    if (resendPhoneBtn) resendPhoneBtn.addEventListener('click', sendPhoneOTP);
    
    const sendEmailBtn = document.getElementById('sendEmailOtpBtn');
    if (sendEmailBtn) sendEmailBtn.addEventListener('click', sendEmailOTP);
    
    const verifyEmailBtn = document.getElementById('verifyEmailOtpBtn');
    if (verifyEmailBtn) verifyEmailBtn.addEventListener('click', verifyEmailOTP);
    
    const resendEmailBtn = document.getElementById('resendEmailOtpBtn');
    if (resendEmailBtn) resendEmailBtn.addEventListener('click', sendEmailOTP);
    
    const categoryId = document.getElementById('department_category_id')?.value;
    const departmentId = @json($employee->department_id ?? null);
    if (categoryId && departmentId) {
        setTimeout(() => {
            loadDepartments(categoryId, departmentId);
        }, 100);
    }
    
    toggleAddressProofFields();
    const isAddressSame = document.getElementById('is_address_same');
    if (isAddressSame) {
        isAddressSame.addEventListener('change', toggleAddressProofFields);
    }
    
    showStep(0);
});
</script>
@endsection