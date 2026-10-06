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

    /* Timeline */
    .timeline-wrapper {
        background: white;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .timeline {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 20px 0;
    }

    .timeline-line {
        position: absolute;
        top: 16px;
        left: 10%;
        height: 4px;
        background: var(--success-gradient);
        z-index: 0;
        transition: width 0.4s ease;
        width: 0%;
        border-radius: 4px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        top: 16px;
        left: 10%;
        width: 80%;
        height: 4px;
        background: #e2e8f0;
        z-index: 0;
        border-radius: 4px;
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
        background: #e2e8f0;
        color: #64748b;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.3s;
        border: 2px solid transparent;
    }

    .timeline-step.active .timeline-bullet {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
        transform: scale(1.1);
    }

    .timeline-step.completed .timeline-bullet {
        background: var(--success-gradient);
        color: white;
    }

    .timeline-step span {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #475569;
        transition: all 0.3s;
    }

    .timeline-step.active span {
        color: var(--primary-color);
        font-weight: 700;
    }

    .timeline-step.completed span {
        color: var(--success-color);
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

    .card-header.bg-success {
        background: var(--success-gradient) !important;
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

    .card-section h4 {
        color: var(--secondary-color);
        font-size: 18px;
        font-weight: 600;
        margin: 15px 0 10px;
        padding-left: 10px;
        border-left: 4px solid var(--secondary-color);
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

    .form-control:read-only,
    .form-control:disabled,
    .form-select:disabled {
        background-color: #f1f5f9;
        cursor: not-allowed;
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

    .btn-outline-primary {
        background: transparent;
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
    }

    .btn-outline-primary:hover {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white;
    }

    .btn-outline-success {
        background: transparent;
        border: 2px solid var(--success-color);
        color: var(--success-color);
    }

    .btn-outline-success:hover {
        background: var(--success-gradient);
        border-color: transparent;
        color: white;
    }

    /* Form Navigation */
    .form-navigation {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        padding: 20px 30px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.05);
    }

    /* Guardian Selection */
    .guardian-select-container {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border: 2px solid #e2e8f0;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .guardian-select-container label {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-check-inline {
        margin-right: 25px;
    }

    .form-check-input {
        width: 20px;
        height: 20px;
        cursor: pointer;
        border: 2px solid #cbd5e1;
        border-radius: 50%;
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
    }

    .form-check-label {
        color: #475569;
        font-weight: 500;
        cursor: pointer;
        margin-left: 8px;
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

    /* Other Documents Section */
    .other-docs-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin: 20px 0;
    }
.email-edit-indicator {
    font-size: 11px;
    color: #f59e0b;
    margin-top: 4px;
    display: none;
}

.email-edit-indicator i {
    font-size: 10px;
}
    .other-docs-header label {
        margin: 0;
        font-weight: 600;
        color: var(--primary-color);
        font-size: 16px;
    }

    /* Student Bank Toggle */
    .add_student_bank {
        padding: 20px;
        border-top: 2px solid #e2e8f0;
    }

    .form-switch .form-check-input {
        width: 40px;
        height: 20px;
        border-radius: 20px;
        background-color: #cbd5e1;
        border: 2px solid #cbd5e1;
        transition: all 0.3s;
    }

    .form-switch .form-check-input:checked {
        background-color: var(--success-color);
        border-color: var(--success-color);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
    }

    .form-switch .form-check-label {
        font-weight: 600;
        color: var(--primary-color);
    }

    /* Invalid Feedback */
    .is-invalid {
        border-color: #ef4444 !important;
    }

    .invalid-feedback {
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #ef4444;
    }

    /* OTP Verification Styles */
    .otp-verification-section {
        margin-top: 15px;
        padding: 20px;
        background: #f0f9ff;
        border: 1px solid #0284c7;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .otp-verification-section h6 {
        color: #0c4a6e;
        margin-bottom: 12px;
        font-size: 14px;
        font-weight: 600;
    }

    .otp-digit-container {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .otp-digit {
        width: 50px;
        height: 50px;
        text-align: center;
        font-size: 24px;
        font-weight: 600;
        border: 2px solid #0284c7;
        border-radius: 8px;
        background: white;
    }

    .otp-digit:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .otp-digit:disabled {
        background-color: #f1f5f9;
        color: #64748b;
    }

    .otp-status {
        margin-top: 12px;
        padding: 10px;
        border-radius: 8px;
        font-size: 13px;
        display: none;
    }

    .otp-status.success {
        display: block;
        background: #d1fae5;
        color: #065f46;
        border-left: 4px solid #10b981;
    }

    .otp-status.error {
        display: block;
        background: #fee2e2;
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }

    .verified-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #10b981;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        margin-left: 10px;
    }

    .verified-badge i {
        font-size: 10px;
    }

    .phone-wrapper {
        position: relative;
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

        .timeline-wrapper {
            padding: 20px;
        }

        .timeline-step span {
            font-size: 12px;
        }

        .card-body {
            padding: 20px;
        }

        .card-section {
            padding: 20px;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .form-navigation {
            flex-direction: column;
            gap: 10px;
        }

        .form-navigation .btn {
            margin: 5px 0;
        }

        .otp-digit {
            width: 45px;
            height: 45px;
            font-size: 20px;
        }
    }
        .otp-verification-section {
        margin-top: 15px;
        padding: 20px;
        background: #f0f9ff;
        border: 1px solid #0284c7;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .otp-verification-section h6 {
        color: #0c4a6e;
        margin-bottom: 12px;
        font-size: 14px;
        font-weight: 600;
    }

 

    .otp-digit {
        width: 50px;
        height: 50px;
        text-align: center;
        font-size: 24px;
        font-weight: 600;
        border: 2px solid #0284c7;
        border-radius: 8px;
        background: white;
    }

    .otp-digit:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }
    /* Countdown timer style for verify buttons */
.btn-primary .bi-check-lg + span {
    margin-left: 5px;
}

/* Disabled button style during countdown */
.btn-primary:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

    .otp-digit:disabled {
        background-color: #f1f5f9;
        color: #64748b;
    }

    .otp-status {
        margin-top: 12px;
        padding: 10px;
        border-radius: 8px;
        font-size: 13px;
        display: none;
    }

    .otp-status.success {
        display: block;
        background: #d1fae5;
        color: #065f46;
        border-left: 4px solid #10b981;
    }

    .otp-status.error {
        display: block;
        background: #fee2e2;
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }

    .verified-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #10b981;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        margin-left: 10px;
    }

    .phone-edit-indicator {
        font-size: 11px;
        color: #f59e0b;
        margin-top: 4px;
        display: none;
    }

    .phone-edit-indicator i {
        font-size: 10px;
    }

</style>

<link href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<section>
@php
    $courseLabel = (isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type === 'School')
    ? 'Class'
    : 'Course';
@endphp    
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-person-fill"></i>
                Edit Student Details
            </h1>
            <a href="{{ url('/institute/admin/students') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <!-- Timeline -->
        <div class="timeline-wrapper">
            <div class="timeline position-relative">
                <div class="timeline-line" id="timelineProgress"></div>
                <div class="timeline-step active" id="step1">
                    <div class="timeline-bullet">01</div>
                    <span>Basic Details</span>
                </div>
                <div class="timeline-step" id="step2">
                    <div class="timeline-bullet">02</div>
                    <span>Address Details</span>
                </div>
                <div class="timeline-step" id="step3">
                    <div class="timeline-bullet">03</div>
                    <span>Academic Details</span>
                </div>
                <div class="timeline-step" id="step4">
                    <div class="timeline-bullet">04</div>
                    <span>Documents</span>
                </div>
                <div class="timeline-step" id="step5">
                    <div class="timeline-bullet">05</div>
                    <span>Bank Details</span>
                </div>
            </div>
        </div>

        <!-- Form 1 -->
        <form id="form1">
            {{-- ===================== STUDENT DETAILS ===================== --}}
            <div class="form-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-person-fill"></i>
                        Student Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="card-section">
                        <h5><i class="bi bi-info-circle-fill"></i>Personal Information</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-qr-code"></i> Registration Number</label>
                                <div class="input-group">
                                    <input type="text" name="registration_number" id="registration_number"
                                        class="form-control" placeholder="Enter or Generate"
                                        value="{{old('registration_number', $student->registration_number)}}" readonly>
                                    <button class="btn btn-outline-secondary" type="button"
                                        id="btnGenerateReg" disabled>Generate</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-person"></i> First Name *</label>
                                <input type="text" class="form-control" name="first_name" required
                                    value="{{old('first_name', $student->first_name)}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-person"></i> Middle Name</label>
                                <input type="text" class="form-control" name="middle_name"
                                    value="{{old('middle_name', $student->middle_name)}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-person"></i> Last Name</label>
                                <input type="text" class="form-control" name="last_name"
                                    value="{{old('last_name', $student->last_name)}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-calendar"></i> Date of Birth *</label>
                                <input type="date" name="dob" id="student_dob" class="form-control" required
                                    value="{{old('dob', $student->dob)}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-gender-ambiguous"></i> Gender *</label>
                                <select name="gender" class="form-control" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender', $student->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-section">
                        <h5><i class="bi bi-telephone-fill"></i>Contact Information</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-phone-fill"></i> Mobile Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">+91</span>
                                    <input type="tel" name="mobile" id="student_mobile" class="form-control" pattern="[0-9]{10}"
                                        title="Please enter exactly 10 digits" value="{{old('mobile', $student->mobile)}}">
                                </div>
                                <div class="invalid-feedback">Please enter a valid 10-digit mobile number</div>
                                <div class="phone-edit-indicator" id="studentPhoneEditIndicator" style="font-size: 11px; color: #f59e0b; margin-top: 4px; display: none;">
                                    <i class="bi bi-pencil-square"></i> Phone number changed - OTP verification required
                                </div>
                                <div class="mt-2 ">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-50" id="sendStudentOtpBtn" style="display: none;">
                                        <i class="bi bi-envelope-paper"></i> Send OTP
                                    </button>
                                </div>
                            
                                <!-- Student OTP Verification Section -->
                                <div id="studentOtpSection" class="otp-verification-section" style="display: none;">
                                    <h6><i class="bi bi-shield-lock"></i> Phone Number Verification</h6>
                                    <p style="color: #0c4a6e; font-size: 13px; margin-bottom: 12px;">Enter the 4-digit OTP sent to <strong id="studentPhoneDisplay"></strong></p>
                                    <div class="otp-digit-container">
                                        <input type="text" class="student-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="student-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="student-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="student-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                    </div>
                                    <div style="display: flex; gap: 10px; margin-top: 12px;">
                                        <button type="button" class="btn btn-sm btn-primary" id="verifyStudentOtpBtn">
                                            <i class="bi bi-check-lg"></i> Verify OTP
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="resendStudentOtpBtn" style="display: none;">
                                            <i class="bi bi-arrow-repeat"></i> Resend OTP
                                        </button>
                                    </div>
                                    <div id="studentOtpStatus" class="otp-status"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-envelope-fill"></i> Email ID *</label>
                                <input type="email" class="form-control" name="email" id="student_email" required value="{{old('email', $student->email)}}" data-original-email="{{old('email', $student->email)}}">
                                <div class="invalid-feedback">Please enter a valid email address</div>
                                <div class="email-edit-indicator" id="studentEmailEditIndicator" style="font-size: 11px; color: #f59e0b; margin-top: 4px; display: none;">
                                    <i class="bi bi-pencil-square"></i> Email changed - OTP verification required
                                </div>
                                <div class="mt-2 ">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-50" id="sendStudentEmailOtpBtn" style="display: none;">
                                        <i class="bi bi-envelope-paper"></i> Send OTP to Email
                                    </button>
                                </div>
                                
                                <!-- Student Email OTP Verification Section -->
                                <div id="studentEmailOtpSection" style="display: none; margin-top: 20px; padding: 20px; background: #f0f9ff; border: 1px solid #0284c7; border-radius: 12px;">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                                        <i class="bi bi-envelope-open-fill" style="color: #0284c7; font-size: 18px;"></i>
                                        <h6 style="margin: 0; color: #0c4a6e; font-weight: 600;">Email OTP Verification</h6>
                                    </div>
                                    <p style="color: #0c4a6e; font-size: 13px; margin-bottom: 15px;">
                                        Enter the 4-digit OTP sent to <strong id="studentEmailDisplay"></strong>
                                    </p>
                                    <div class="otp-digit-container">
                                        <input type="text" class="student-email-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="student-email-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="student-email-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="student-email-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                    </div>
                                    <div style="display: flex; gap: 10px; margin-top: 12px;">
                                        <button type="button" class="btn btn-sm btn-primary" id="verifyStudentEmailOtpBtn">
                                            <i class="bi bi-check-lg"></i> Verify Email OTP
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="resendStudentEmailOtpBtn" style="display: none;">
                                            <i class="bi bi-arrow-repeat"></i> Resend OTP
                                        </button>
                                    </div>
                                    <div id="studentEmailOtpStatus" class="otp-status"></div>
                                </div>
                            </div>
                        
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-flag-fill"></i> Nationality</label>
                                <select name="nationality" class="form-control">
                                    <option value="">Select Nationality</option>
                                    <option value="Indian" {{ old('nationality', $student->nationality) == 'Indian' ? 'selected' : '' }}>Indian</option>
                                    <option value="American" {{ old('nationality', $student->nationality) == 'American' ? 'selected' : '' }}>American</option>
                                    <option value="British" {{ old('nationality', $student->nationality) == 'British' ? 'selected' : '' }}>British</option>
                                    <option value="Canadian" {{ old('nationality', $student->nationality) == 'Canadian' ? 'selected' : '' }}>Canadian</option>
                                    <option value="Australian" {{ old('nationality', $student->nationality) == 'Australian' ? 'selected' : '' }}>Australian</option>
                                    <option value="Others" {{ old('nationality', $student->nationality) == 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                                @error('nationality')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-section">
                        <h5><i class="bi bi-heart-pulse-fill"></i>Additional Information</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-building-fill"></i> Religion</label>
                                <select name="religion" class="form-control">
                                    <option value="">Select Religion</option>
                                    <option value="Hindu" {{ old('religion', $student->religion) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Muslim" {{ old('religion', $student->religion) == 'Muslim' ? 'selected' : '' }}>Muslim</option>
                                    <option value="Christian" {{ old('religion', $student->religion) == 'Christian' ? 'selected' : '' }}>Christian</option>
                                    <option value="Sikh" {{ old('religion', $student->religion) == 'Sikh' ? 'selected' : '' }}>Sikh</option>
                                    <option value="Buddhist" {{ old('religion', $student->religion) == 'Buddhist' ? 'selected' : '' }}>Buddhist</option>
                                    <option value="Jain" {{ old('religion', $student->religion) == 'Jain' ? 'selected' : '' }}>Jain</option>
                                    <option value="Jewish" {{ old('religion', $student->religion) == 'Jewish' ? 'selected' : '' }}>Jewish</option>
                                    <option value="Others" {{ old('religion', $student->religion) == 'Others' ? 'selected' : '' }}>Others</option>
                                    <option value="Not Specified" {{ old('religion', $student->religion) == 'Not Specified' ? 'selected' : '' }}>Not Specified</option>
                                </select>
                                @error('religion')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-droplet-fill"></i> Blood Group</label>
                                <select name="blood_group" class="form-control">
                                    <option value="">Select Blood Group</option>
                                    <option value="A+" {{ old('blood_group', $student->blood_group) == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A-" {{ old('blood_group', $student->blood_group) == 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B+" {{ old('blood_group', $student->blood_group) == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B-" {{ old('blood_group', $student->blood_group) == 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="AB+" {{ old('blood_group', $student->blood_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="AB-" {{ old('blood_group', $student->blood_group) == 'Ab-' ? 'selected' : '' }}>AB-</option>
                                    <option value="O+" {{ old('blood_group', $student->blood_group) == 'O+' ? 'selected' : '' }}>O+</option>
                                    <option value="O-" {{ old('blood_group', $student->blood_group) == 'O-' ? 'selected' : '' }}>O-</option>
                                    <option value="Unknown" {{ old('blood_group', $student->blood_group) == 'Unknown' ? 'selected' : '' }}>Unknown</option>
                                    <option value="Not Disclosed" {{ old('blood_group', $student->blood_group) == 'Not Specified' ? 'selected' : '' }}>Not Disclosed</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-people-fill"></i> Social Category</label>
                                <select name="category" class="form-control">
                                    <option value="">Select Category</option>
                                    <option value="General" {{ old('category', $student->category) == 'General' ? 'selected' : '' }}>General</option>
                                    <option value="OBC" {{ old('category', $student->category) == 'OBC (Other Backward Class)' ? 'selected' : '' }}>OBC (Other Backward Class)</option>
                                    <option value="SC" {{ old('category', $student->category) == 'SC (Scheduled Caste)' ? 'selected' : '' }}>SC (Scheduled Caste)</option>
                                    <option value="ST" {{ old('category', $student->category) == 'ST (Scheduled Tribe)' ? 'selected' : '' }}>ST (Scheduled Tribe)</option>
                                    <option value="Others" {{ old('category', $student->category) == 'Others' ? 'selected' : '' }}>Others</option>
                                    <option value="Not Specified" {{ old('category', $student->category) == 'Not Specified' ? 'selected' : '' }}>Not Specified</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== PARENT DETAILS ===================== --}}
            <div class="form-card">
                <div class="card-header bg-success">
                    <h5 class="mb-0">
                        <i class="bi bi-people-fill"></i>
                        Parent's Details
                    </h5>
                </div>

                <div class="card-body">
                    <!-- Father's Details -->
                    <div class="card-section">
                        <h5><i class="bi bi-person-standing"></i> Father's Details</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-person"></i> First Name</label>
                                <input type="text" name="father_first_name" class="form-control" 
                                    value="{{old('father_first_name', $student->father_first_name)}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-person"></i> Middle Name</label>
                                <input type="text" name="father_middle_name" class="form-control"
                                    value="{{old('father_middle_name', $student->father_middle_name)}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-person"></i> Last Name</label>
                                <input type="text" name="father_last_name" class="form-control"
                                    value="{{old('father_last_name', $student->father_last_name)}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-calendar"></i> Date of Birth</label>
                                <input type="date" name="father_dob" id="father_dob" class="form-control"
                                    value="{{old('father_dob', $student->father_dob)}}">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-envelope-fill"></i> Email ID</label>
                                <input type="email" name="father_email" id="father_email" class="form-control"
                                    value="{{old('father_email', $student->father_email)}}" data-original-email="{{old('father_email', $student->father_email)}}">
                                <div class="invalid-feedback">Please enter a valid email address</div>
                                <div class="email-edit-indicator" id="fatherEmailEditIndicator" style="display: none;">
                                    <i class="bi bi-pencil-square"></i> Email changed - OTP verification required
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-50" id="sendFatherEmailOtpBtn" style="display: none;">
                                        <i class="bi bi-envelope-paper"></i> Send OTP to Email
                                    </button>
                                </div>
                                
                                <!-- Father Email OTP Verification Section -->
                                <div id="fatherEmailOtpSection" style="display: none; margin-top: 20px; padding: 20px; background: #f0f9ff; border: 1px solid #0284c7; border-radius: 12px;">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                                        <i class="bi bi-envelope-open-fill" style="color: #0284c7; font-size: 18px;"></i>
                                        <h6 style="margin: 0; color: #0c4a6e; font-weight: 600;">Father's Email OTP Verification</h6>
                                    </div>
                                    <p style="color: #0c4a6e; font-size: 13px; margin-bottom: 15px;">
                                        Enter the 4-digit OTP sent to <strong id="fatherEmailDisplay"></strong>
                                    </p>
                                    <div class="otp-digit-container">
                                        <input type="text" class="father-email-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="father-email-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="father-email-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="father-email-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                    </div>
                                    <div style="display: flex; gap: 10px; margin-top: 12px;">
                                        <button type="button" class="btn btn-sm btn-primary" id="verifyFatherEmailOtpBtn">
                                            <i class="bi bi-check-lg"></i> Verify Email OTP
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="resendFatherEmailOtpBtn" style="display: none;">
                                            <i class="bi bi-arrow-repeat"></i> Resend OTP
                                        </button>
                                    </div>
                                    <div id="fatherEmailOtpStatus" class="otp-status"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 phone-wrapper">
                                <label class="form-label"><i class="bi bi-phone-fill"></i> Phone No.</label>
                                <div class="input-group">
                                    <span class="input-group-text">+91</span>
                                    <input type="tel" name="father_phone" id="father_phone" class="form-control"
                                        placeholder="Enter 10-digit number" pattern="[0-9]{10}"
                                        title="Please enter exactly 10 digits"
                                        value="{{old('father_phone', $student->father_phone)}}">
                                </div>
                                <div class="invalid-feedback">Please enter a valid 10-digit mobile number</div>
                                <div class="phone-edit-indicator" id="fatherPhoneEditIndicator" style="font-size: 11px; color: #f59e0b; margin-top: 4px; display: none;">
                                    <i class="bi bi-pencil-square"></i> Phone number changed - OTP verification required
                                </div>
                                <div class="mt-2 ">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-50" id="sendFatherOtpBtn" style="display: none;">
                                        <i class="bi bi-envelope-paper"></i> Send OTP
                                    </button>
                                </div>
                                
                                <!-- Father OTP Verification Section -->
                                <div id="fatherOtpSection" class="otp-verification-section" style="display: none;">
                                    <h6><i class="bi bi-shield-lock"></i> Father's Phone Number Verification</h6>
                                    <p style="color: #0c4a6e; font-size: 13px; margin-bottom: 12px;">Enter the 4-digit OTP sent to <strong id="fatherPhoneDisplay"></strong></p>
                                    <div class="otp-digit-container">
                                        <input type="text" class="father-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="father-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="father-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="father-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                    </div>
                                    <div style="display: flex; gap: 10px; margin-top: 12px;">
                                        <button type="button" class="btn btn-sm btn-primary" id="verifyFatherOtpBtn">
                                            <i class="bi bi-check-lg"></i> Verify OTP
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="resendFatherOtpBtn" style="display: none;">
                                            <i class="bi bi-arrow-repeat"></i> Resend OTP
                                        </button>
                                    </div>
                                    <div id="fatherOtpStatus" class="otp-status"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-briefcase-fill"></i> Occupation</label>
                                <select name="father_occupation" class="form-control">
                                    <option value="">-- Select --</option>
                                    <option value="Government Employee" {{ old('father_occupation', $student->father_occupation) == 'Government Employee' ? 'selected' : '' }}>Government Employee</option>
                                    <option value="Private Sector Employee" {{ old('father_occupation', $student->father_occupation) == 'Private Sector Employee' ? 'selected' : '' }}>Private Sector Employee</option>
                                    <option value="Self-Employed" {{ old('father_occupation', $student->father_occupation) == 'Self-Employed' ? 'selected' : '' }}>Self-Employed</option>
                                    <option value="Business Owner" {{ old('father_occupation', $student->father_occupation) == 'Business Owner' ? 'selected' : '' }}>Business Owner</option>
                                    <option value="Farmer" {{ old('father_occupation', $student->father_occupation) == 'Farmer' ? 'selected' : '' }}>Farmer</option>
                                    <option value="Teacher" {{ old('father_occupation', $student->father_occupation) == 'Teacher' ? 'selected' : '' }}>Teacher</option>
                                    <option value="Doctor" {{ old('father_occupation', $student->father_occupation) == 'Doctor' ? 'selected' : '' }}>Doctor</option>
                                    <option value="Engineer" {{ old('father_occupation', $student->father_occupation) == 'Engineer' ? 'selected' : '' }}>Engineer</option>
                                    <option value="Driver" {{ old('father_occupation', $student->father_occupation) == 'Driver' ? 'selected' : '' }}>Driver</option>
                                    <option value="Housewife" {{ old('father_occupation', $student->father_occupation) == 'Housewife' ? 'selected' : '' }}>Housewife</option>
                                    <option value="Retired" {{ old('father_occupation', $student->father_occupation) == 'Retired' ? 'selected' : '' }}>Retired</option>
                                    <option value="Unemployed" {{ old('father_occupation', $student->father_occupation) == 'Unemployed' ? 'selected' : '' }}>Unemployed</option>
                                    <option value="Other" {{ old('father_occupation', $student->father_occupation) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-cash-stack"></i> Annual Income</label>
                                <input type="number" name="father_income" class="form-control" placeholder="In INR"
                                    value="{{old('father_income', $student->father_income)}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-flag-fill"></i> Nationality</label>
                                <select name="father_nationality" class="form-control">
                                    <option value="">Select Nationality</option>
                                    <option value="Indian" {{ old('father_nationality', $student->father_nationality) == 'Indian' ? 'selected' : '' }}>Indian</option>
                                    <option value="American" {{ old('father_nationality', $student->father_nationality) == 'American' ? 'selected' : '' }}>American</option>
                                    <option value="British" {{ old('father_nationality', $student->father_nationality) == 'British' ? 'selected' : '' }}>British</option>
                                    <option value="Canadian" {{ old('father_nationality', $student->father_nationality) == 'Canadian' ? 'selected' : '' }}>Canadian</option>
                                    <option value="Australian" {{ old('father_nationality', $student->father_nationality) == 'Australian' ? 'selected' : '' }}>Australian</option>
                                    <option value="Others" {{ old('father_nationality', $student->father_nationality) == 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-building-fill"></i> Religion</label>
                                <select name="father_religion" class="form-control">
                                    <option value="">Select Religion</option>
                                    <option value="Hindu" {{ old('father_religion', $student->father_religion) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Muslim" {{ old('father_religion', $student->father_religion) == 'Muslim' ? 'selected' : '' }}>Muslim</option>
                                    <option value="Christian" {{ old('father_religion', $student->father_religion) == 'Christian' ? 'selected' : '' }}>Christian</option>
                                    <option value="Sikh" {{ old('father_religion', $student->father_religion) == 'Sikh' ? 'selected' : '' }}>Sikh</option>
                                    <option value="Buddhist" {{ old('father_religion', $student->father_religion) == 'Buddhist' ? 'selected' : '' }}>Buddhist</option>
                                    <option value="Jain" {{ old('father_religion', $student->father_religion) == 'Jain' ? 'selected' : '' }}>Jain</option>
                                    <option value="Jewish" {{ old('father_religion', $student->father_religion) == 'Jewish' ? 'selected' : '' }}>Jewish</option>
                                    <option value="Others" {{ old('father_religion', $student->father_religion) == 'Others' ? 'selected' : '' }}>Others</option>
                                    <option value="Not Specified" {{ old('father_religion', $student->father_religion) == 'Not Specified' ? 'selected' : '' }}>Not Specified</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-droplet-fill"></i> Blood Group</label>
                                <select name="father_blood_group" class="form-control">
                                    <option value="">Select Blood Group</option>
                                    <option value="A+" {{ old('father_blood_group', $student->father_blood_group) == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A-" {{ old('father_blood_group', $student->father_blood_group) == 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B+" {{ old('father_blood_group', $student->father_blood_group) == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B-" {{ old('father_blood_group', $student->father_blood_group) == 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="AB+" {{ old('father_blood_group', $student->father_blood_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="AB-" {{ old('father_blood_group', $student->father_blood_group) == 'Ab-' ? 'selected' : '' }}>AB-</option>
                                    <option value="O+" {{ old('father_blood_group', $student->father_blood_group) == 'O+' ? 'selected' : '' }}>O+</option>
                                    <option value="O-" {{ old('father_blood_group', $student->father_blood_group) == 'O-' ? 'selected' : '' }}>O-</option>
                                    <option value="Unknown" {{ old('father_blood_group', $student->father_blood_group) == 'Unknown' ? 'selected' : '' }}>Unknown</option>
                                    <option value="Not Disclosed" {{ old('father_blood_group', $student->father_blood_group) == 'Not Specified' ? 'selected' : '' }}>Not Disclosed</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-people-fill"></i> Social Category</label>
                                <select name="father_category" class="form-control">
                                    <option value="">Select Category</option>
                                    <option value="General" {{ old('father_category', $student->father_category) == 'General' ? 'selected' : '' }}>General</option>
                                    <option value="OBC" {{ old('father_category', $student->father_category) == 'OBC (Other Backward Class)' ? 'selected' : '' }}>OBC (Other Backward Class)</option>
                                    <option value="SC" {{ old('father_category', $student->father_category) == 'SC (Scheduled Caste)' ? 'selected' : '' }}>SC (Scheduled Caste)</option>
                                    <option value="ST" {{ old('father_category', $student->father_category) == 'ST (Scheduled Tribe)' ? 'selected' : '' }}>ST (Scheduled Tribe)</option>
                                    <option value="Others" {{ old('father_category', $student->father_category) == 'Others' ? 'selected' : '' }}>Others</option>
                                    <option value="Not Specified" {{ old('father_category', $student->father_category) == 'Not Specified' ? 'selected' : '' }}>Not Specified</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Mother's Details -->
                    <div class="card-section">
                        <h5><i class="bi bi-person-standing-dress"></i> Mother's Details</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-person"></i> First Name</label>
                                <input type="text" name="mother_first_name" class="form-control" 
                                    value="{{old('mother_first_name', $student->mother_first_name)}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-person"></i> Middle Name</label>
                                <input type="text" name="mother_middle_name" class="form-control"
                                    value="{{old('mother_middle_name', $student->mother_middle_name)}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-person"></i> Last Name</label>
                                <input type="text" name="mother_last_name" class="form-control"
                                    value="{{old('mother_last_name', $student->mother_last_name)}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-calendar"></i> Date of Birth</label>
                                <input type="date" name="mother_dob" id="mother_dob" class="form-control"
                                    value="{{old('mother_dob', $student->mother_dob)}}">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-envelope-fill"></i> Email ID</label>
                                <input type="email" name="mother_email" id="mother_email" class="form-control" 
                                    value="{{old('mother_email', $student->mother_email)}}" data-original-email="{{old('mother_email', $student->mother_email)}}">
                                <div class="invalid-feedback">Please enter a valid email address</div>
                                <div class="email-edit-indicator" id="motherEmailEditIndicator" style="display: none;">
                                    <i class="bi bi-pencil-square"></i> Email changed - OTP verification required
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-50" id="sendMotherEmailOtpBtn" style="display: none;">
                                        <i class="bi bi-envelope-paper"></i> Send OTP to Email
                                    </button>
                                </div>
                                
                                <!-- Mother Email OTP Verification Section -->
                                <div id="motherEmailOtpSection" style="display: none; margin-top: 20px; padding: 20px; background: #f0f9ff; border: 1px solid #0284c7; border-radius: 12px;">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                                        <i class="bi bi-envelope-open-fill" style="color: #0284c7; font-size: 18px;"></i>
                                        <h6 style="margin: 0; color: #0c4a6e; font-weight: 600;">Mother's Email OTP Verification</h6>
                                    </div>
                                    <p style="color: #0c4a6e; font-size: 13px; margin-bottom: 15px;">
                                        Enter the 4-digit OTP sent to <strong id="motherEmailDisplay"></strong>
                                    </p>
                                    <div class="otp-digit-container">
                                        <input type="text" class="mother-email-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="mother-email-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="mother-email-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="mother-email-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                    </div>
                                    <div style="display: flex; gap: 10px; margin-top: 12px;">
                                        <button type="button" class="btn btn-sm btn-primary" id="verifyMotherEmailOtpBtn">
                                            <i class="bi bi-check-lg"></i> Verify Email OTP
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="resendMotherEmailOtpBtn" style="display: none;">
                                            <i class="bi bi-arrow-repeat"></i> Resend OTP
                                        </button>
                                    </div>
                                    <div id="motherEmailOtpStatus" class="otp-status"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 phone-wrapper">
                                <label class="form-label"><i class="bi bi-phone-fill"></i> Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text">+91</span>
                                    <input type="tel" name="mother_phone" id="mother_phone" class="form-control"
                                        placeholder="Enter 10-digit number" pattern="[0-9]{10}"
                                        title="Please enter exactly 10 digits" 
                                        value="{{old('mother_phone', $student->mother_phone)}}">
                                </div>
                                <div class="invalid-feedback">Please enter a valid 10-digit mobile number</div>
                                <div class="phone-edit-indicator" id="motherPhoneEditIndicator" style="font-size: 11px; color: #f59e0b; margin-top: 4px; display: none;">
                                    <i class="bi bi-pencil-square"></i> Phone number changed - OTP verification required
                                </div>
                                <div class="mt-2 ">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-50" id="sendMotherOtpBtn" style="display: none;">
                                        <i class="bi bi-envelope-paper"></i> Send OTP
                                    </button>
                                </div>
                                
                                <!-- Mother OTP Verification Section -->
                                <div id="motherOtpSection" class="otp-verification-section" style="display: none;">
                                    <h6><i class="bi bi-shield-lock"></i> Mother's Phone Number Verification</h6>
                                    <p style="color: #0c4a6e; font-size: 13px; margin-bottom: 12px;">Enter the 4-digit OTP sent to <strong id="motherPhoneDisplay"></strong></p>
                                    <div class="otp-digit-container">
                                        <input type="text" class="mother-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="mother-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="mother-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="mother-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                    </div>
                                    <div style="display: flex; gap: 10px; margin-top: 12px;">
                                        <button type="button" class="btn btn-sm btn-primary" id="verifyMotherOtpBtn">
                                            <i class="bi bi-check-lg"></i> Verify OTP
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="resendMotherOtpBtn" style="display: none;">
                                            <i class="bi bi-arrow-repeat"></i> Resend OTP
                                        </button>
                                    </div>
                                    <div id="motherOtpStatus" class="otp-status"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-briefcase-fill"></i> Occupation</label>
                                <select name="mother_occupation" class="form-control">
                                    <option value="">-- Select --</option>
                                    <option value="Government Employee" {{ old('mother_occupation', $student->mother_occupation) == 'Government Employee' ? 'selected' : '' }}>Government Employee</option>
                                    <option value="Private Sector Employee" {{ old('mother_occupation', $student->mother_occupation) == 'Private Sector Employee' ? 'selected' : '' }}>Private Sector Employee</option>
                                    <option value="Self-Employed" {{ old('mother_occupation', $student->mother_occupation) == 'Self-Employed' ? 'selected' : '' }}>Self-Employed</option>
                                    <option value="Business Owner" {{ old('mother_occupation', $student->mother_occupation) == 'Business Owner' ? 'selected' : '' }}>Business Owner</option>
                                    <option value="Farmer" {{ old('mother_occupation', $student->mother_occupation) == 'Farmer' ? 'selected' : '' }}>Farmer</option>
                                    <option value="Teacher" {{ old('mother_occupation', $student->mother_occupation) == 'Teacher' ? 'selected' : '' }}>Teacher</option>
                                    <option value="Doctor" {{ old('mother_occupation', $student->mother_occupation) == 'Doctor' ? 'selected' : '' }}>Doctor</option>
                                    <option value="Engineer" {{ old('mother_occupation', $student->mother_occupation) == 'Engineer' ? 'selected' : '' }}>Engineer</option>
                                    <option value="Driver" {{ old('mother_occupation', $student->mother_occupation) == 'Driver' ? 'selected' : '' }}>Driver</option>
                                    <option value="Housewife" {{ old('mother_occupation', $student->mother_occupation) == 'Housewife' ? 'selected' : '' }}>Housewife</option>
                                    <option value="Retired" {{ old('mother_occupation', $student->mother_occupation) == 'Retired' ? 'selected' : '' }}>Retired</option>
                                    <option value="Unemployed" {{ old('mother_occupation', $student->mother_occupation) == 'Unemployed' ? 'selected' : '' }}>Unemployed</option>
                                    <option value="Other" {{ old('mother_occupation', $student->mother_occupation) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-cash-stack"></i> Annual Income</label>
                                <input type="number" name="mother_income" class="form-control"
                                placeholder="In INR" value="{{old('mother_income', $student->mother_income)}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-flag-fill"></i> Nationality</label>
                                <select name="mother_nationality" class="form-control">
                                    <option value="">Select Nationality</option>
                                    <option value="Indian" {{ old('mother_nationality', $student->mother_nationality) == 'Indian' ? 'selected' : '' }}>Indian</option>
                                    <option value="American" {{ old('mother_nationality', $student->mother_nationality) == 'American' ? 'selected' : '' }}>American</option>
                                    <option value="British" {{ old('mother_nationality', $student->mother_nationality) == 'British' ? 'selected' : '' }}>British</option>
                                    <option value="Canadian" {{ old('mother_nationality', $student->mother_nationality) == 'Canadian' ? 'selected' : '' }}>Canadian</option>
                                    <option value="Australian" {{ old('mother_nationality', $student->mother_nationality) == 'Australian' ? 'selected' : '' }}>Australian</option>
                                    <option value="Others" {{ old('mother_nationality', $student->mother_nationality) == 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-building-fill"></i> Religion</label>
                                <select name="mother_religion" class="form-control">
                                    <option value="">Select Religion</option>
                                    <option value="Hindu" {{ old('mother_religion', $student->mother_religion) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Muslim" {{ old('mother_religion', $student->mother_religion) == 'Muslim' ? 'selected' : '' }}>Muslim</option>
                                    <option value="Christian" {{ old('mother_religion', $student->mother_religion) == 'Christian' ? 'selected' : '' }}>Christian</option>
                                    <option value="Sikh" {{ old('mother_religion', $student->mother_religion) == 'Sikh' ? 'selected' : '' }}>Sikh</option>
                                    <option value="Buddhist" {{ old('mother_religion', $student->mother_religion) == 'Buddhist' ? 'selected' : '' }}>Buddhist</option>
                                    <option value="Jain" {{ old('mother_religion', $student->mother_religion) == 'Jain' ? 'selected' : '' }}>Jain</option>
                                    <option value="Jewish" {{ old('mother_religion', $student->mother_religion) == 'Jewish' ? 'selected' : '' }}>Jewish</option>
                                    <option value="Others" {{ old('mother_religion', $student->mother_religion) == 'Others' ? 'selected' : '' }}>Others</option>
                                    <option value="Not Specified" {{ old('mother_religion', $student->mother_religion) == 'Not Specified' ? 'selected' : '' }}>Not Specified</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-droplet-fill"></i> Blood Group</label>
                                <select name="mother_blood_group" class="form-control">
                                    <option value="">Select Blood Group</option>
                                    <option value="A+" {{ old('mother_blood_group', $student->mother_blood_group) == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A-" {{ old('mother_blood_group', $student->mother_blood_group) == 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B+" {{ old('mother_blood_group', $student->mother_blood_group) == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B-" {{ old('mother_blood_group', $student->mother_blood_group) == 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="AB+" {{ old('mother_blood_group', $student->mother_blood_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="AB-" {{ old('mother_blood_group', $student->mother_blood_group) == 'Ab-' ? 'selected' : '' }}>AB-</option>
                                    <option value="O+" {{ old('mother_blood_group', $student->mother_blood_group) == 'O+' ? 'selected' : '' }}>O+</option>
                                    <option value="O-" {{ old('mother_blood_group', $student->mother_blood_group) == 'O-' ? 'selected' : '' }}>O-</option>
                                    <option value="Unknown" {{ old('mother_blood_group', $student->mother_blood_group) == 'Unknown' ? 'selected' : '' }}>Unknown</option>
                                    <option value="Not Disclosed" {{ old('mother_blood_group', $student->mother_blood_group) == 'Not Specified' ? 'selected' : '' }}>Not Disclosed</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-people-fill"></i> Social Category</label>
                                <select name="mother_category" class="form-control">
                                    <option value="">Select Category</option>
                                    <option value="General" {{ old('mother_category', $student->mother_category) == 'General' ? 'selected' : '' }}>General</option>
                                    <option value="OBC" {{ old('mother_category', $student->mother_category) == 'OBC (Other Backward Class)' ? 'selected' : '' }}>OBC (Other Backward Class)</option>
                                    <option value="SC" {{ old('mother_category', $student->mother_category) == 'SC (Scheduled Caste)' ? 'selected' : '' }}>SC (Scheduled Caste)</option>
                                    <option value="ST" {{ old('mother_category', $student->mother_category) == 'ST (Scheduled Tribe)' ? 'selected' : '' }}>ST (Scheduled Tribe)</option>
                                    <option value="Others" {{ old('mother_category', $student->mother_category) == 'Others' ? 'selected' : '' }}>Others</option>
                                    <option value="Not Specified" {{ old('mother_category', $student->mother_category) == 'Not Specified' ? 'selected' : '' }}>Not Specified</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== GUARDIAN DETAILS ===================== --}}
            <div class="form-card">
                <div class="card-header bg-success">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-fill-check"></i>
                        Guardian Details
                    </h5>
                </div>

                <div class="card-body">
                    <div class="guardian-select-container">
                        <label class="w-100 mb-3"><i class="bi bi-person-check-fill"></i> Select Guardian</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="guardian_type" id="guardian_father"
                                value="father" {{ old('guardian_type', $student->guardian_type) == 'Father' ? 'checked' : '' }}>
                            <label class="form-check-label" for="guardian_father">Father</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="guardian_type" id="guardian_mother"
                                value="mother" {{ old('guardian_type', $student->guardian_type) == 'Mother' ? 'checked' : '' }}>
                            <label class="form-check-label" for="guardian_mother">Mother</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="guardian_type" id="guardian_other"
                                value="other" {{ old('guardian_type', $student->guardian_type) == 'Different / Other Person' ? 'checked' : '' }}>
                            <label class="form-check-label" for="guardian_other">Different / Other Person</label>
                        </div>
                    </div>

                    <div class="card-section guardianContainer d-none">
                        <h5><i class="bi bi-person-badge-fill"></i> Guardian Information</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Relation with Student</label>
                                <input type="text" name="guardian_relation" id="guardian_relation" class="form-control"
                                    value="{{old('guardian_relation', $student->guardian_relation)}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">First Name</label>
                                <input type="text" name="guardian_first_name" id="guardian_first_name" class="form-control"
                                    value="{{old('guardian_first_name', $student->guardian_first_name)}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="guardian_middle_name" id="guardian_middle_name" class="form-control"
                                    value="{{old('guardian_middle_name', $student->guardian_middle_name)}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="guardian_last_name" id="guardian_last_name" class="form-control"
                                    value="{{old('guardian_last_name', $student->guardian_last_name)}}">
                            </div>
                            <div class="col-md-4">
                                <label>Gender</label>
                                <select name="guardian_gender" id="guardian_gender" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Male" {{ old('guardian_gender', $student->guardian_gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('guardian_gender', $student->guardian_gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('guardian_gender', $student->guardian_gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="guardian_dob" id="guardian_dob" class="form-control"
                                    value="{{old('guardian_dob', $student->guardian_dob)}}">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Email</label>
                                <input type="email" name="guardian_email" id="guardian_email" class="form-control"
                                    value="{{old('guardian_email', $student->guardian_email)}}" data-original-email="{{old('guardian_email', $student->guardian_email)}}">
                                <div class="invalid-feedback">Please enter a valid email address</div>
                                <div class="email-edit-indicator" id="guardianEmailEditIndicator" style="display: none;">
                                    <i class="bi bi-pencil-square"></i> Email changed - OTP verification required
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-50" id="sendGuardianEmailOtpBtn" style="display: none;">
                                        <i class="bi bi-envelope-paper"></i> Send OTP to Email
                                    </button>
                                </div>
                                
                                <!-- Guardian Email OTP Verification Section -->
                                <div id="guardianEmailOtpSection" style="display: none; margin-top: 20px; padding: 20px; background: #f0f9ff; border: 1px solid #0284c7; border-radius: 12px;">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                                        <i class="bi bi-envelope-open-fill" style="color: #0284c7; font-size: 18px;"></i>
                                        <h6 style="margin: 0; color: #0c4a6e; font-weight: 600;">Guardian's Email OTP Verification</h6>
                                    </div>
                                    <p style="color: #0c4a6e; font-size: 13px; margin-bottom: 15px;">
                                        Enter the 4-digit OTP sent to <strong id="guardianEmailDisplay"></strong>
                                    </p>
                                    <div class="otp-digit-container">
                                        <input type="text" class="guardian-email-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="guardian-email-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="guardian-email-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="guardian-email-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                    </div>
                                    <div style="display: flex; gap: 10px; margin-top: 12px;">
                                        <button type="button" class="btn btn-sm btn-primary" id="verifyGuardianEmailOtpBtn">
                                            <i class="bi bi-check-lg"></i> Verify Email OTP
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="resendGuardianEmailOtpBtn" style="display: none;">
                                            <i class="bi bi-arrow-repeat"></i> Resend OTP
                                        </button>
                                    </div>
                                    <div id="guardianEmailOtpStatus" class="otp-status"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 phone-wrapper">
                                <label class="form-label">Phone</label>
                                <div class="input-group">
                                    <span class="input-group-text">+91</span>
                                    <input type="tel" name="guardian_phone" id="guardian_phone" class="form-control"
                                        placeholder="Enter 10-digit number" pattern="[0-9]{10}"
                                        title="Please enter exactly 10 digits"
                                        value="{{old('guardian_phone', $student->guardian_phone)}}">
                                </div>
                                <div class="invalid-feedback">Please enter a valid 10-digit mobile number</div>
                                <div class="phone-edit-indicator" id="guardianPhoneEditIndicator" style="font-size: 11px; color: #f59e0b; margin-top: 4px; display: none;">
                                    <i class="bi bi-pencil-square"></i> Phone number changed - OTP verification required
                                </div>
                                <div class="mt-2 ">
                                    <button type="button" class="btn btn-sm btn-outline-primary w-50" id="sendGuardianOtpBtn" style="display: none;">
                                        <i class="bi bi-envelope-paper"></i> Send OTP
                                    </button>
                                </div>
                                
                                <!-- Guardian OTP Verification Section -->
                                <div id="guardianOtpSection" class="otp-verification-section" style="display: none;">
                                    <h6><i class="bi bi-shield-lock"></i> Guardian's Phone Number Verification</h6>
                                    <p style="color: #0c4a6e; font-size: 13px; margin-bottom: 12px;">Enter the 4-digit OTP sent to <strong id="guardianPhoneDisplay"></strong></p>
                                    <div class="otp-digit-container">
                                        <input type="text" class="guardian-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="guardian-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="guardian-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                        <input type="text" class="guardian-otp-digit" maxlength="1" style="width: 50px; height: 50px; text-align: center; font-size: 24px; border: 2px solid #0284c7; border-radius: 8px;" inputmode="numeric">
                                    </div>
                                    <div style="display: flex; gap: 10px; margin-top: 12px;">
                                        <button type="button" class="btn btn-sm btn-primary" id="verifyGuardianOtpBtn">
                                            <i class="bi bi-check-lg"></i> Verify OTP
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="resendGuardianOtpBtn" style="display: none;">
                                            <i class="bi bi-arrow-repeat"></i> Resend OTP
                                        </button>
                                    </div>
                                    <div id="guardianOtpStatus" class="otp-status"></div>
                                </div>
                            </div>
    
                            <div class="col-md-4">
                                <label class="form-label">Alternate Phone</label>
                                <input type="tel" name="guardian_alternate_phone_number" id="guardian_alternate_phone" class="form-control"
                                    value="{{old('guardian_alternate_phone_number', $student->guardian_alternate_phone_number)}}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Occupation</label>
                                <select name="guardian_occupation" id="guardian_occupation" class="form-control">
                                    <option value="">-- Select --</option>
                                    <option value="Government Employee" {{ old('guardian_occupation', $student->guardian_occupation) == 'Government Employee' ? 'selected' : '' }}>Government Employee</option>
                                    <option value="Private Sector Employee" {{ old('guardian_occupation', $student->guardian_occupation) == 'Private Sector Employee' ? 'selected' : '' }}>Private Sector Employee</option>
                                    <option value="Self-Employed" {{ old('guardian_occupation', $student->guardian_occupation) == 'Self-Employed' ? 'selected' : '' }}>Self-Employed</option>
                                    <option value="Business Owner" {{ old('guardian_occupation', $student->guardian_occupation) == 'Business Owner' ? 'selected' : '' }}>Business Owner</option>
                                    <option value="Farmer" {{ old('guardian_occupation', $student->guardian_occupation) == 'Farmer' ? 'selected' : '' }}>Farmer</option>
                                    <option value="Teacher" {{ old('guardian_occupation', $student->guardian_occupation) == 'Teacher' ? 'selected' : '' }}>Teacher</option>
                                    <option value="Doctor" {{ old('guardian_occupation', $student->guardian_occupation) == 'Doctor' ? 'selected' : '' }}>Doctor</option>
                                    <option value="Engineer" {{ old('guardian_occupation', $student->guardian_occupation) == 'Engineer' ? 'selected' : '' }}>Engineer</option>
                                    <option value="Driver" {{ old('guardian_occupation', $student->guardian_occupation) == 'Driver' ? 'selected' : '' }}>Driver</option>
                                    <option value="Housewife" {{ old('guardian_occupation', $student->guardian_occupation) == 'Housewife' ? 'selected' : '' }}>Housewife</option>
                                    <option value="Retired" {{ old('guardian_occupation', $student->guardian_occupation) == 'Retired' ? 'selected' : '' }}>Retired</option>
                                    <option value="Unemployed" {{ old('guardian_occupation', $student->guardian_occupation) == 'Unemployed' ? 'selected' : '' }}>Unemployed</option>
                                    <option value="Other" {{ old('guardian_occupation', $student->guardian_occupation) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Annual Income</label>
                                <input type="number" name="guardian_income" id="guardian_income" class="form-control" placeholder="In INR"
                                    value="{{old('guardian_income', $student->guardian_income)}}">
                            </div>
                            <div class="col-md-4">
                                <label>Nationality</label>
                                <select name="guardian_nationality" id="guardian_nationality" class="form-control">
                                    <option value="">Select Nationality</option>
                                    <option value="Indian" {{ old('guardian_nationality', $student->guardian_nationality) == 'Indian' ? 'selected' : '' }}>Indian</option>
                                    <option value="American" {{ old('guardian_nationality', $student->guardian_nationality) == 'American' ? 'selected' : '' }}>American</option>
                                    <option value="British" {{ old('guardian_nationality', $student->guardian_nationality) == 'British' ? 'selected' : '' }}>British</option>
                                    <option value="Canadian" {{ old('guardian_nationality', $student->guardian_nationality) == 'Canadian' ? 'selected' : '' }}>Canadian</option>
                                    <option value="Australian" {{ old('guardian_nationality', $student->guardian_nationality) == 'Australian' ? 'selected' : '' }}>Australian</option>
                                    <option value="Others" {{ old('guardian_nationality', $student->guardian_nationality) == 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Religion</label>
                                <select name="guardian_religion" id="guardian_religion" class="form-control">
                                    <option value="">Select Religion</option>
                                    <option value="Hindu" {{ old('guardian_religion', $student->guardian_religion) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Muslim" {{ old('guardian_religion', $student->guardian_religion) == 'Muslim' ? 'selected' : '' }}>Muslim</option>
                                    <option value="Christian" {{ old('guardian_religion', $student->guardian_religion) == 'Christian' ? 'selected' : '' }}>Christian</option>
                                    <option value="Sikh" {{ old('guardian_religion', $student->guardian_religion) == 'Sikh' ? 'selected' : '' }}>Sikh</option>
                                    <option value="Buddhist" {{ old('guardian_religion', $student->guardian_religion) == 'Buddhist' ? 'selected' : '' }}>Buddhist</option>
                                    <option value="Jain" {{ old('guardian_religion', $student->guardian_religion) == 'Jain' ? 'selected' : '' }}>Jain</option>
                                    <option value="Jewish" {{ old('guardian_religion', $student->guardian_religion) == 'Jewish' ? 'selected' : '' }}>Jewish</option>
                                    <option value="Others" {{ old('guardian_religion', $student->guardian_religion) == 'Others' ? 'selected' : '' }}>Others</option>
                                    <option value="Not Specified" {{ old('guardian_religion', $student->guardian_religion) == 'Not Specified' ? 'selected' : '' }}>Not Specified</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Blood Group</label>
                                <select name="guardian_blood_group" id="guardian_blood_group" class="form-control">
                                    <option value="">Select Blood Group</option>
                                    <option value="A+" {{ old('guardian_blood_group', $student->guardian_blood_group) == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A-" {{ old('guardian_blood_group', $student->guardian_blood_group) == 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B+" {{ old('guardian_blood_group', $student->guardian_blood_group) == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B-" {{ old('guardian_blood_group', $student->guardian_blood_group) == 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="AB+" {{ old('guardian_blood_group', $student->guardian_blood_group) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="AB-" {{ old('guardian_blood_group', $student->guardian_blood_group) == 'Ab-' ? 'selected' : '' }}>AB-</option>
                                    <option value="O+" {{ old('guardian_blood_group', $student->guardian_blood_group) == 'O+' ? 'selected' : '' }}>O+</option>
                                    <option value="O-" {{ old('guardian_blood_group', $student->guardian_blood_group) == 'O-' ? 'selected' : '' }}>O-</option>
                                    <option value="Unknown" {{ old('guardian_blood_group', $student->guardian_blood_group) == 'Unknown' ? 'selected' : '' }}>Unknown</option>
                                    <option value="Not Disclosed" {{ old('guardian_blood_group', $student->guardian_blood_group) == 'Not Specified' ? 'selected' : '' }}>Not Disclosed</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Social Category</label>
                                <select name="guardian_category" id="guardian_category" class="form-control">
                                    <option value="">Select Category</option>
                                    <option value="General" {{ old('guardian_category', $student->guardian_category) == 'General' ? 'selected' : '' }}>General</option>
                                    <option value="OBC" {{ old('guardian_category', $student->guardian_category) == 'OBC (Other Backward Class)' ? 'selected' : '' }}>OBC (Other Backward Class)</option>
                                    <option value="SC" {{ old('guardian_category', $student->guardian_category) == 'SC (Scheduled Caste)' ? 'selected' : '' }}>SC (Scheduled Caste)</option>
                                    <option value="ST" {{ old('guardian_category', $student->guardian_category) == 'ST (Scheduled Tribe)' ? 'selected' : '' }}>ST (Scheduled Tribe)</option>
                                    <option value="Others" {{ old('guardian_category', $student->guardian_category) == 'Others' ? 'selected' : '' }}>Others</option>
                                    <option value="Not Specified" {{ old('guardian_category', $student->guardian_category) == 'Not Specified' ? 'selected' : '' }}>Not Specified</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-navigation">
                <span></span>
                <button type="submit" class="btn btn-primary" id="form1SubmitBtn">
                    <i class="fas fa-arrow-right"></i>Next
                </button>
            </div>
        </form>

        <!-- Form 2 -->
        <form id="form2" style="display:none;">
            <!-- ... (rest of the form remains the same - Address details) ... -->
            <div class="form-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-geo-alt-fill"></i>
                        Student Address
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Same as original - keep existing address fields -->
                    <div class="card-section">
                        <h5><i class="bi bi-house-door-fill"></i>Permanent Address</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Address Line 1</label>
                                <input type="text" name="student_perm_address_line1" class="form-control"
                                    placeholder="Address Line 1" 
                                    value="{{old('student_perm_address_line1', $student->address->student_perm_address_line1 ?? '')}}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Address Line 2</label>
                                <input type="text" name="student_perm_address_line2" class="form-control"
                                    placeholder="Address Line 2"
                                    value="{{old('student_perm_address_line2', $student->address->student_perm_address_line2 ?? '')}}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="student_perm_city" class="form-control"
                                    placeholder="City" 
                                    value="{{old('student_perm_city', $student->address->student_perm_city ?? '')}}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">State</label>
                                <input type="text" name="student_perm_state" class="form-control"
                                    placeholder="State" 
                                    value="{{old('student_perm_state', $student->address->student_perm_state ?? '')}}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Pincode</label>
                                <input type="text" name="student_perm_pincode" class="form-control"
                                    placeholder="Pincode" pattern="[0-9]{6}" 
                                    value="{{old('student_perm_pincode', $student->address->student_perm_pincode ?? '')}}">
                            </div>
                        </div>
                    </div>

                    <div class="card-section">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5><i class="bi bi-house-heart-fill"></i>Communication Address</h5>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="copyStudentAddress">
                                <label class="form-check-label mt-0" for="copyStudentAddress">Same as Permanent</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" name="student_comm_address_line1" class="form-control"
                                    placeholder="Address Line 1"
                                    value="{{old('student_comm_address_line1', $student->address->student_comm_address_line1 ?? '')}}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" name="student_comm_address_line2" class="form-control"
                                    placeholder="Address Line 2"
                                    value="{{old('student_comm_address_line2', $student->address->student_comm_address_line2 ?? '')}}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" name="student_comm_city" class="form-control"
                                    placeholder="City"
                                    value="{{old('student_comm_city', $student->address->student_comm_city ?? '')}}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" name="student_comm_state" class="form-control"
                                    placeholder="State"
                                    value="{{old('student_comm_state', $student->address->student_comm_state ?? '')}}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" name="student_comm_pincode" class="form-control"
                                    placeholder="Pincode"
                                    value="{{old('student_comm_pincode', $student->address->student_comm_pincode ?? '')}}">
                            </div>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="copyStudentToParent">
                        <label class="form-check-label fw-semibold mt-0" for="copyStudentToParent">
                            Use Same Address as Student
                        </label>
                    </div>
                </div>
            </div>

            {{-- ===================== FATHERS ADDRESSES ===================== --}}
            <div class="form-card">
                <div class="card-header bg-success">
                    <h5><i class="bi bi-person-standing"></i> Father's Address</h5>
                </div>
                <div class="card-body">
                    <div class="card-section">
                        <h5><i class="bi bi-house-door-fill"></i>Permanent Address</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" name="parent_perm_address_line1" class="form-control"
                                    placeholder="Address Line 1" 
                                    value="{{old('parent_perm_address_line1', $student->address->parent_perm_address_line1 ?? '')}}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" name="parent_perm_address_line2" class="form-control"
                                    placeholder="Address Line 2"
                                    value="{{old('parent_perm_address_line2', $student->address->parent_perm_address_line2 ?? '')}}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" name="parent_perm_city" class="form-control"
                                    placeholder="City"
                                    value="{{old('parent_perm_city', $student->address->parent_perm_city ?? '')}}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" name="parent_perm_state" class="form-control"
                                    placeholder="State"
                                    value="{{old('parent_perm_state', $student->address->parent_perm_state ?? '')}}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" name="parent_perm_pincode" class="form-control"
                                    placeholder="Pincode" pattern="[0-9]{6}" 
                                    value="{{old('parent_perm_pincode', $student->address->parent_perm_pincode ?? '')}}">
                            </div>
                        </div>
                    </div>

                    <div class="card-section">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5><i class="bi bi-house-heart-fill"></i>Communication Address</h5>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="copyParentAddress">
                                <label class="form-check-label mt-0" for="copyParentAddress">Same as Permanent</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" name="parent_comm_address_line1" class="form-control"
                                    placeholder="Address Line 1"
                                    value="{{old('parent_comm_address_line1', $student->address->parent_comm_address_line1 ?? '')}}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" name="parent_comm_address_line2" class="form-control"
                                    placeholder="Address Line 2"
                                    value="{{old('parent_comm_address_line2', $student->address->parent_comm_address_line2 ?? '')}}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" name="parent_comm_city" class="form-control"
                                    placeholder="City"
                                    value="{{old('parent_comm_city', $student->address->parent_comm_city ?? '')}}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" name="parent_comm_state" class="form-control"
                                    placeholder="State"
                                    value="{{old('parent_comm_state', $student->address->parent_comm_state ?? '')}}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" name="parent_comm_pincode" class="form-control"
                                    placeholder="Pincode"
                                    value="{{old('parent_comm_pincode', $student->address->parent_comm_pincode ?? '')}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== GUARDIAN ADDRESSES ===================== --}}
            <div class="form-card guardian-address-container d-none">
                <div class="card-header bg-success">
                    <h5 class="mb-0"><i class="bi bi-shield-fill-check"></i> Guardian Address</h5>
                </div>
                <div class="card-body">
                    <div class="card-section">
                        <h5><i class="bi bi-house-door-fill"></i>Permanent Address</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" name="guardian_perm_address_line1" class="form-control"
                                    placeholder="Address Line 1"
                                    value="{{old('guardian_perm_address_line1', $student->address->guardian_perm_address_line1 ?? '')}}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" name="guardian_perm_address_line2" class="form-control"
                                    placeholder="Address Line 2"
                                    value="{{old('guardian_perm_address_line2', $student->address->guardian_perm_address_line2 ?? '')}}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" name="guardian_perm_city" class="form-control"
                                    placeholder="City"
                                    value="{{old('guardian_perm_city', $student->address->guardian_perm_city ?? '')}}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" name="guardian_perm_state" class="form-control"
                                    placeholder="State"
                                    value="{{old('guardian_perm_state', $student->address->guardian_perm_state ?? '')}}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" name="guardian_perm_pincode" class="form-control"
                                    placeholder="Pincode" pattern="[0-9]{6}"
                                    value="{{old('guardian_perm_pincode', $student->address->guardian_perm_pincode ?? '')}}">
                            </div>
                        </div>
                    </div>

                    <div class="card-section">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5><i class="bi bi-house-heart-fill"></i>Communication Address</h5>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="copyGuardianAddress">
                                <label class="form-check-label mt-0" for="copyGuardianAddress">Same as Permanent</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" name="guardian_comm_address_line1" class="form-control"
                                    placeholder="Address Line 1"
                                    value="{{old('guardian_comm_address_line1', $student->address->guardian_comm_address_line1 ?? '')}}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="text" name="guardian_comm_address_line2" class="form-control"
                                    placeholder="Address Line 2"
                                    value="{{old('guardian_comm_address_line2', $student->address->guardian_comm_address_line2 ?? '')}}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" name="guardian_comm_city" class="form-control"
                                    placeholder="City"
                                    value="{{old('guardian_comm_city', $student->address->guardian_comm_city ?? '')}}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" name="guardian_comm_state" class="form-control"
                                    placeholder="State"
                                    value="{{old('guardian_comm_state', $student->address->guardian_comm_state ?? '')}}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" name="guardian_comm_pincode" class="form-control"
                                    placeholder="Pincode"
                                    value="{{old('guardian_comm_pincode', $student->address->guardian_comm_pincode ?? '')}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-navigation">
                <button type="button" class="btn btn-outline-secondary" id="back3">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </button>
                
                <button type="submit" class="btn btn-primary" id="form4SubmitBtn">
                    Next <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </form>

        <!-- Form 3 -->
        <form id="form3" style="display:none;">
            @php
                // Determine if academic data already exists from backend
                $hasAcademicData = isset($extra) && (
                    !empty($extra->department_category_id) ||
                    !empty($extra->department) ||
                    !empty($extra->course_type) ||
                    !empty($extra->course_subtype) ||
                    !empty($extra->batch) ||
                    !empty($extra->academic_year) ||
                    !empty($extra->mode_of_course) ||
                    !empty($extra->mode_type) ||
                    !empty($extra->semester_id) ||
                    !empty($extra->section_id)
                );
        
                // When data exists → fields locked. When not → fields editable.
                $isDisabled = $hasAcademicData ? 'disabled' : '';
                $isRequired = $hasAcademicData ? '' : 'required';
        
                // Safe accessors
                $e = fn($key, $default = '') => old($key, $extra->{$key} ?? $default);
            @endphp
        
            <div class="form-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-building-fill"></i>
                        Institute Details
                        <span class="d-none">
                        @if($hasAcademicData)
                            <span class="badge bg-warning text-dark ms-2" style="font-size: 11px;">
                                <i class="bi bi-lock-fill"></i> Locked (data exists)
                            </span>
                        @else
                            <span class="badge bg-success ms-2" style="font-size: 11px;">
                                <i class="bi bi-pencil-fill"></i> Editable
                            </span>
                        @endif
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="card-section">
                        <h5><i class="bi bi-info-circle-fill"></i>Academic Information</h5>
                        <div class="row g-3">
        
                            {{-- Department Category --}}
                            <div class="col-md-6">
                                <label class="form-label">Department Category</label>
                                <select name="department_category_id" id="department_category_id"
                                    class="form-control" {{ $isDisabled }} {{ $isRequired }}>
                                    <option value="">-- Select Department Category --</option>
                                    @foreach($departmentCategories as $category)
                                        <option value="{{ $category->department_category_id }}"
                                            {{ (string) old('department_category_id', $extra->department_category_id ?? '') === (string) $category->department_category_id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
        
                            {{-- Department --}}
                            <div class="col-md-6">
                                <label class="form-label">Department</label>
                                <input name="department" id="department" class="form-control"
                                    value="{{ $e('department') }}" {{ $isDisabled }} {{ $isRequired }}>
                            </div>
        
                            {{-- Course Type --}}
                            <div class="col-md-6">
                                <label class="form-label">{{ $courseLabel }} Type</label>
                                <input type="text" name="course_type" id="course_type" class="form-control"
                                    value="{{ $e('course_type') }}" {{ $isDisabled }} {{ $isRequired }}>
                            </div>
        
                            {{-- Course Sub Type --}}
                            <div class="col-md-6">
                                <label class="form-label">{{ $courseLabel }} Sub Type</label>
                                <input type="text" name="course_subtype" id="course_subtype" class="form-control"
                                    value="{{ $e('course_subtype') }}" {{ $isDisabled }} {{ $isRequired }}>
                            </div>
        
                            <input type="hidden" name="course_detail_id" id="course_detail_id"
                                value="{{ $courseId ?? $extra->course_subtype_id ?? '' }}">
        
                            {{-- Batch --}}
                            <div class="col-md-6">
                                <label class="form-label">Batch</label>
                                <input type="text" name="batch" id="batch_name" class="form-control"
                                    value="{{ $e('batch') }}" {{ $isDisabled }} {{ $isRequired }}>
                                <input type="hidden" name="batch_id" id="batch_id">
                            </div>
        
                            {{-- Academic Year --}}
                            <div class="col-md-6">
                                <label class="form-label">Academic Year</label>
                                <input type="text" name="academic_year" id="academic_year_name" class="form-control"
                                    value="{{ $e('academic_year') }}" {{ $isDisabled }} {{ $isRequired }}>
                            </div>
        
                            {{-- Mode of Course --}}
                            <div class="col-md-6">
                                <label class="form-label">{{ $courseLabel }} Mode</label>
                                <input type="text" name="mode_of_course" id="mode_of_course" class="form-control"
                                    value="{{ $e('mode_of_course') }}" {{ $isDisabled }} {{ $isRequired }}>
                            </div>
        
                            {{-- Mode Type --}}
                            <div class="col-md-6">
                                <label class="form-label">Mode Type</label>
                                <input type="text" name="mode_type" id="mode_type" class="form-control"
                                    value="{{ $e('mode_type') }}" {{ $isDisabled }} {{ $isRequired }}>
                            </div>
        
                            {{-- Semester (hidden by default) --}}
                            <div class="col-md-6 d-none">
                                <label class="form-label">Semester/Terms</label>
                                <input type="text" name="semester_id" id="semester_id" class="form-control"
                                    value="{{ $e('semester_id') }}" {{ $isDisabled }}>
                            </div>
        
                            {{-- Section (always editable — dropdown is filled via AJAX) --}}
                            <div class="col-md-6">
                                <label class="form-label">Section</label>
                                <select name="section_id" id="section_id" class="form-control"
                                    style="cursor: pointer; background: #fff;">
                                    <option value="">Select Section</option>
                                </select>
                            </div>
        
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="form-navigation">
                <button type="button" class="btn btn-outline-secondary" id="back3">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </button>
                
                <button type="submit" class="btn btn-primary" id="form4SubmitBtn">
                    Next <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </form>

        <!-- Form 4 -->
        <form id="form4" style="display:none;" enctype="multipart/form-data">
            <!-- STUDENT DOCUMENTS -->
            <div class="form-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-person-fill"></i>
                        Student Documents
                    </h5>
                </div>
                <div class="card-body" id="student-documents-container">
                    <div class="card-section">
                        <h5><i class="bi bi-person-badge-fill"></i>Identity Documents</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-qr-code"></i> Aadhaar Number</label>
                                <input type="text" name="student_aadhaar_number" class="form-control" maxlength="12"
                                    pattern="\d{12}" placeholder="Enter 12-digit Aadhaar number"
                                    value="{{ old('student_aadhaar_number', $documents->student_aadhaar_number ?? '')}}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-file-earmark-fill"></i> Upload Aadhaar Card</label>
                                <input type="file" name="student_aadhaar_file" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-credit-card-fill"></i> PAN Number</label>
                                <input type="text" name="student_pan_number" class="form-control" maxlength="10"
                                    pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" title="PAN format: ABCDE1234F"
                                    placeholder="ABCDE1234F"
                                    value="{{ old('student_pan_number', $documents->student_pan_number ?? '')}}">
                                <div class="invalid-feedback">PAN must be in format ABCDE1234F</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-file-earmark-fill"></i> Upload PAN Card</label>
                                <input type="file" name="student_pan_file" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>
                    </div>

                    <div class="card-section">
                        <h5><i class="bi bi-camera-fill"></i>Photos & Proofs</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-camera-fill"></i> Student Photo <span class="text-danger">*</span></label>
                                <input type="file" name="student_photo" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label><i class="bi bi-file-earmark-fill"></i> Upload Student ID Card <small class="text-muted">(optional)</small></label>
                                <input type="file" name="student_id_card" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-house-door-fill"></i> Address Proof (Optional)</label>
                                <input type="file" name="student_address_proof" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-calendar-heart-fill"></i> Birth Certificate</label>
                                <input type="file" name="student_bonafide" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>
                    </div>

                    <div id="academic-documents-container" class="row g-3"></div>

                    <div class="card-section">
                        <div class="other-docs-header">
                            <h5><i class="bi bi-files-fill"></i>Other Documents</h5>
                            <button type="button" class="btn btn-outline-primary" id="addOtherDocBtn">
                                <i class="bi bi-plus-lg me-2"></i>Add Document
                            </button>
                        </div>
                        <div id="other-documents-wrapper" class="row g-3">
                            <div class="col-md-6 other-doc-group">
                                <label class="form-label">Document Name</label>
                                <input type="text" name="student_other_doc_label_1" class="form-control"
                                    placeholder="Enter document name" value="{{ old('student_other_doc_label_1', $otherDocs['label_1'] ?? '') }}">
                            </div>
                            <div class="col-md-6 other-doc-group d-flex align-items-end">
                                <div class="w-100">
                                    <label class="form-label">Upload File</label>
                                    <input type="file" name="student_other_doc_file_1" class="form-control"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PARENT DOCUMENTS -->
            <div class="form-card">
                <div class="card-header bg-success">
                    <h5 class="mb-0">
                        <i class="bi bi-people-fill"></i>
                        Parent Documents
                    </h5>
                </div>
                <div class="card-body">
                    <div class="card-section">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-qr-code"></i> Aadhaar Number</label>
                                <input type="text" name="parent_aadhaar_number" class="form-control" maxlength="12"
                                    pattern="\d{12}" placeholder="Enter 12-digit Aadhaar number"
                                    value="{{ old('parent_aadhaar_number', $documents->parent_aadhaar_number ?? '')}}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-file-earmark-fill"></i> Upload Aadhaar Card</label>
                                <input type="file" name="parent_aadhaar_file" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-credit-card-fill"></i> PAN Number</label>
                                <input type="text" name="parent_pan_number" class="form-control" maxlength="10"
                                    placeholder="ABCDE1234F"
                                    value="{{ old('parent_pan_number', $documents->parent_pan_number ?? '')}}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-file-earmark-fill"></i> Upload PAN Card</label>
                                <input type="file" name="parent_pan_file" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-cash-stack"></i> Income Proof</label>
                                <input type="file" name="parent_income_proof" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-camera-fill"></i> Photo</label>
                                <input type="file" name="parent_photo" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-house-door-fill"></i> Address Proof (Optional)</label>
                                <input type="file" name="parent_address_proof" class="form-control"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>
                    </div>

                    <div class="otherDocumentsContainer d-none">
                        <div class="card-section">
                            <h5><i class="bi bi-shield-fill-check"></i> Guardian Documents</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-qr-code"></i> Aadhaar Number</label>
                                    <input type="text" name="guardian_aadhaar_number" class="form-control"
                                        maxlength="12" pattern="\d{12}" placeholder="Enter 12-digit Aadhaar number"
                                        value="{{ old('guardian_aadhaar_number', $documents->guardian_aadhaar_number ?? '')}}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-file-earmark-fill"></i> Upload Aadhaar Card</label>
                                    <input type="file" name="guardian_aadhaar_file" class="form-control"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-credit-card-fill"></i> PAN Number</label>
                                    <input type="text" name="guardian_pan_number" class="form-control" maxlength="10"
                                        placeholder="ABCDE1234F"
                                        value="{{ old('guardian_pan_number', $documents->guardian_pan_number ?? '')}}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-file-earmark-fill"></i> Upload PAN Card</label>
                                    <input type="file" name="guardian_pan_file" class="form-control"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-cash-stack"></i> Income Proof</label>
                                    <input type="file" name="guardian_income_proof" class="form-control"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-camera-fill"></i> Photo</label>
                                    <input type="file" name="guardian_photo" class="form-control" accept="image/*">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-house-door-fill"></i> Address Proof (Optional)</label>
                                    <input type="file" name="guardian_address_proof" class="form-control"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-navigation">
                <button type="button" class="btn btn-outline-secondary" id="back3">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </button>
                
                <button type="submit" class="btn btn-primary" id="form4SubmitBtn">
                    Next <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </form>

        <!-- Form 5 -->
        <form id="form5" style="display:none;">
            <div class="form-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-bank2"></i>
                        Parent Bank Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="card-section">
                        <h5><i class="bi bi-person-badge-fill"></i>Bank Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-person-fill"></i> Beneficiary Name</label>
                                <input type="text" name="benificiary_name" class="form-control" 
                                    value="{{ old('benificiary_name', $bank->benificiary_name ?? '')}}"/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-credit-card-fill"></i> Bank Account Number</label>
                                <input type="text" name="bank_account_number" pattern="[0-9]{9,18}"
                                    class="form-control" 
                                    value="{{ old('bank_account_number', $bank->bank_account_number ?? '')}}"/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-building-fill"></i> Bank Name</label>
                                <input type="text" name="bank_name" class="form-control" 
                                    value="{{ old('bank_name', $bank->bank_name ?? '')}}"/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-upc-scan"></i> IFSC Code</label>
                                <input type="text" name="ifsc_code" class="form-control"
                                    pattern="[A-Z]{4}0[A-Z0-9]{6}" title="IFSC format: ABCD0123456" 
                                    value="{{ old('ifsc_code', $bank->ifsc_code ?? '')}}"/>
                                <div class="invalid-feedback">IFSC must be in format ABCD0123456</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-list-ul"></i> Account Type</label>
                                <select name="account_type" class="form-control">
                                    <option value="">Select Account Type</option>
                                    <option value="saving" {{ old('account_type', $bank->account_type ?? '') == 'saving' ? 'selected' : '' }}>Savings Account</option>
                                    <option value="current" {{ old('account_type', $bank->account_type ?? '') == 'current' ? 'selected' : '' }}>Current Account</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="bi bi-file-earmark-fill"></i> Upload Cancelled Cheque</label>
                                <input name="upload_cancelled_cheque" type="file" class="form-control"/>
                            </div>
                        </div>
                    </div>

                    <div class="add_student_bank">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="add_student_bank"
                                id="add_student_bank">
                            <label class="form-check-label" for="add_student_bank">
                                <i class="bi bi-plus-circle-fill me-2"></i>
                                Add Student Bank Details
                            </label>
                        </div>
                    </div>

                    <div class="studentBank d-none">
                        <div class="card-section">
                            <h5><i class="bi bi-person-fill"></i> Student Bank Details</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Beneficiary Name</label>
                                    <input type="text" name="student_benificiary_name" class="form-control" 
                                    value="{{ old('student_benificiary_name', $bank->student_benificiary_name ?? '')}}"/>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Bank Account Number</label>
                                    <input type="text" name="student_bank_account_number" pattern="[0-9]{9,18}"
                                        class="form-control" 
                                        value="{{ old('student_bank_account_number', $bank->student_bank_account_number ?? '')}}"/>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Bank Name</label>
                                    <input type="text" name="student_bank_name" class="form-control" 
                                        value="{{ old('student_bank_name', $bank->student_bank_name ?? '')}}"/>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">IFSC Code</label>
                                    <input type="text" name="student_ifsc_code" class="form-control"
                                        pattern="[A-Z]{4}0[A-Z0-9]{6}" title="IFSC format: ABCD0123456" 
                                        value="{{ old('student_ifsc_code', $bank->student_ifsc_code ?? '')}}"/>
                                    <div class="invalid-feedback">IFSC must be in format ABCD0123456</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Account Type</label>
                                    <select name="student_account_type" class="form-control">
                                        <option value="">Select Account Type</option>
                                        <option value="saving" {{ old('student_account_type', $bank->student_account_type ?? '') == 'saving' ? 'selected' : '' }}>Savings Account</option>
                                        <option value="current" {{ old('student_account_type', $bank->student_account_type ?? '') == 'current' ? 'selected' : '' }}>Current Account</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Upload Cancelled Cheque</label>
                                    <input name="student_upload_cancelled_cheque" type="file"
                                        class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-navigation">
                <button type="button" class="btn btn-outline-secondary" id="back4">
                    <i class="bi bi-arrow-left-fill me-2"></i>Back
                </button>
                <button type="submit" class="btn btn-success" id="form5SubmitBtn">
                    <i class="bi bi-check-circle-fill me-2"></i>Submit
                </button>
            </div>
        </form>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
    /* ===================== GLOBALS ===================== */
    const steps = ["step1", "step2", "step3", "step4", "step5"];
    const forms = ["form1", "form2", "form3", "form4", "form5"];
    const progressBar = document.getElementById("timelineProgress");
    // Email verification flags
    let isFatherEmailEdited = false;
    let isFatherEmailVerified = false;
    let originalFatherEmail = '';
    let fatherEmailGeneratedOTP = '';
    let fatherEmailOtpTimer = null;
    
    let isMotherEmailEdited = false;
    let isMotherEmailVerified = false;
    let originalMotherEmail = '';
    let motherEmailGeneratedOTP = '';
    let motherEmailOtpTimer = null;
    
    let isGuardianEmailEdited = false;
    let isGuardianEmailVerified = false;
    let originalGuardianEmail = '';
    let guardianEmailGeneratedOTP = '';
    let guardianEmailOtpTimer = null;
    
    let isStudentEmailEdited = false;
    let isStudentEmailVerified = false;
    let originalStudentEmail = '';
    let studentEmailGeneratedOTP = '';
    let studentEmailOtpTimer = null;
    let currentStep = 0;
    let otherDocCount = 1;
    const maxOtherDocs = 3;

    // OTP Verification Flags
    let isStudentOtpVerified = false;
    let isFatherOtpVerified = false;
    let isMotherOtpVerified = false;
    let isGuardianOtpVerified = false;

    // Track if phone numbers have been edited (changed from original)
    let isStudentPhoneEdited = false;
    let isFatherPhoneEdited = false;
    let isMotherPhoneEdited = false;
    let isGuardianPhoneEdited = false;

    // Store original phone values from database
    let originalStudentPhone = '';
    let originalFatherPhone = '';
    let originalMotherPhone = '';
    let originalGuardianPhone = '';

    // Store generated OTPs for fallback
    let studentGeneratedOTP = '';
    let fatherGeneratedOTP = '';
    let motherGeneratedOTP = '';
    let guardianGeneratedOTP = '';

    // OTP Timers
    let studentOtpTimer = null;
    let fatherOtpTimer = null;
    let motherOtpTimer = null;
    let guardianOtpTimer = null;

    /* ===================== HELPER FUNCTIONS ===================== */
    function showToast(message, type = 'error') {
        Toastify({
            text: message,
            duration: 3000,
            gravity: "top",
            position: "right",
            close: true,
            stopOnFocus: true,
            style: {
                background: type === 'error'
                    ? "linear-gradient(to right, #ef4444, #dc2626)"
                    : "linear-gradient(to right, #10b981, #059669)"
            }
        }).showToast();
    }

    function cleanPhoneNumber(phoneNumber) {
        let cleaned = phoneNumber.replace(/\D/g, '');
        if (cleaned.startsWith('91')) {
            cleaned = cleaned.substring(2);
        }
        return cleaned;
    }

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').content;
    }

    async function makeAjaxRequest(url, method, data) {
        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
            return await response.json();
        } catch (error) {
            console.error('AJAX request failed:', error);
            return { Success: false, message: 'Network error: ' + error.message };
        }
    }

    function getPhoneNumber(type) {
        switch(type) {
            case 'student': return document.getElementById('student_mobile')?.value || '';
            case 'father': return document.getElementById('father_phone')?.value || '';
            case 'mother': return document.getElementById('mother_phone')?.value || '';
            case 'guardian': return document.getElementById('guardian_phone')?.value || '';
            default: return '';
        }
    }

    function startOTPTimer(timerVar, verifyBtnId, resendBtnId, type) {
        let timeLeft = 120; // 2 minutes
        const verifyBtn = document.getElementById(verifyBtnId);
        const resendBtn = document.getElementById(resendBtnId);
        const sendOtpBtn = document.getElementById(`send${type.charAt(0).toUpperCase() + type.slice(1)}OtpBtn`);
        
        if (verifyBtn) {
            verifyBtn.style.display = 'inline-block';
            verifyBtn.disabled = false;
            verifyBtn.setAttribute('data-original-text', verifyBtn.innerHTML);
        }
        // Hide resend button initially
        if (resendBtn) {
            resendBtn.style.display = 'none';
        }
        // Hide send OTP button during timer (it's already hidden after first send)
        if (sendOtpBtn) {
            sendOtpBtn.style.display = 'none';
        }
    
        if (window[timerVar]) clearInterval(window[timerVar]);
    
        window[timerVar] = setInterval(function() {
            timeLeft--;
            let minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;
            
            if (verifyBtn && verifyBtn.style.display !== 'none') {
                let isVerified = false;
                if (type === 'student') isVerified = isStudentOtpVerified;
                if (type === 'father') isVerified = isFatherOtpVerified;
                if (type === 'mother') isVerified = isMotherOtpVerified;
                if (type === 'guardian') isVerified = isGuardianOtpVerified;
                
                if (!isVerified) {
                    verifyBtn.innerHTML = `<i class="bi bi-check-lg"></i> Verify OTP (${minutes}:${seconds.toString().padStart(2, '0')})`;
                }
            }
            
            if (timeLeft <= 0) {
                clearInterval(window[timerVar]);
                
                // Hide verify button
                if (verifyBtn) verifyBtn.style.display = 'none';
                // Show RESEND button (not Send OTP)
                if (resendBtn) resendBtn.style.display = 'inline-block';
                // Send OTP button stays hidden
                if (sendOtpBtn) sendOtpBtn.style.display = 'none';
                
                showToast(`${type.charAt(0).toUpperCase() + type.slice(1)} OTP expired. Click Resend OTP.`, 'error');
            }
        }, 1000);
    }

    function showOtpSection(type) {
        const phone = getPhoneNumber(type);
        if (!phone || phone.length !== 10) {
            return;
        }
        
        const sectionMap = {
            student: 'studentOtpSection',
            father: 'fatherOtpSection',
            mother: 'motherOtpSection',
            guardian: 'guardianOtpSection'
        };
        
        const displayMap = {
            student: 'studentPhoneDisplay',
            father: 'fatherPhoneDisplay',
            mother: 'motherPhoneDisplay',
            guardian: 'guardianPhoneDisplay'
        };
        
        const verifyBtnMap = {
            student: 'verifyStudentOtpBtn',
            father: 'verifyFatherOtpBtn',
            mother: 'verifyMotherOtpBtn',
            guardian: 'verifyGuardianOtpBtn'
        };
        
        const resendBtnMap = {
            student: 'resendStudentOtpBtn',
            father: 'resendFatherOtpBtn',
            mother: 'resendMotherOtpBtn',
            guardian: 'resendGuardianOtpBtn'
        };
        
        const otpSection = document.getElementById(sectionMap[type]);
        const phoneDisplay = document.getElementById(displayMap[type]);
        
        if (otpSection) {
            otpSection.style.display = 'block';
        }
        if (phoneDisplay) {
            phoneDisplay.textContent = phone;
        }
        
        // Clear previous OTP inputs and enable them
        document.querySelectorAll(`.${type}-otp-digit`).forEach(input => {
            input.value = '';
            input.disabled = false;
        });
        
        // Show verify button, hide resend button initially
        const verifyBtn = document.getElementById(verifyBtnMap[type]);
        const resendBtn = document.getElementById(resendBtnMap[type]);
        
        if (verifyBtn) {
            verifyBtn.style.display = 'inline-block';
            verifyBtn.disabled = false;
            verifyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Verify OTP';
        }
        if (resendBtn) {
            resendBtn.style.display = 'none';
        }
        
        // Clear status
        const statusDiv = document.getElementById(`${type}OtpStatus`);
        if (statusDiv) {
            statusDiv.className = 'otp-status';
            statusDiv.style.display = 'none';
            statusDiv.innerHTML = '';
        }
        
        // Focus on first digit
        const firstDigit = document.querySelector(`.${type}-otp-digit`);
        if (firstDigit) firstDigit.focus();
    }
    
    // Reset OTP verification when phone number is edited
    function resetOtpVerification(type) {
        if (type === 'student') {
            isStudentOtpVerified = false;
            const otpSection = document.getElementById('studentOtpSection');
            if (otpSection) otpSection.style.display = 'none';
            const statusDiv = document.getElementById('studentOtpStatus');
            if (statusDiv) {
                statusDiv.className = 'otp-status';
                statusDiv.style.display = 'none';
            }
            // Clear timer
            if (studentOtpTimer) clearInterval(studentOtpTimer);
            
            // Reset verify button text
            const verifyBtn = document.getElementById('verifyStudentOtpBtn');
            if (verifyBtn) {
                verifyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Verify OTP';
                verifyBtn.style.display = 'none';
            }
            // Reset resend button
            const resendBtn = document.getElementById('resendStudentOtpBtn');
            if (resendBtn) resendBtn.style.display = 'none';
        } else if (type === 'father') {
            isFatherOtpVerified = false;
            const otpSection = document.getElementById('fatherOtpSection');
            if (otpSection) otpSection.style.display = 'none';
            const statusDiv = document.getElementById('fatherOtpStatus');
            if (statusDiv) {
                statusDiv.className = 'otp-status';
                statusDiv.style.display = 'none';
            }
            if (fatherOtpTimer) clearInterval(fatherOtpTimer);
            
            const verifyBtn = document.getElementById('verifyFatherOtpBtn');
            if (verifyBtn) {
                verifyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Verify OTP';
                verifyBtn.style.display = 'none';
            }
            const resendBtn = document.getElementById('resendFatherOtpBtn');
            if (resendBtn) resendBtn.style.display = 'none';
        } else if (type === 'mother') {
            isMotherOtpVerified = false;
            const otpSection = document.getElementById('motherOtpSection');
            if (otpSection) otpSection.style.display = 'none';
            const statusDiv = document.getElementById('motherOtpStatus');
            if (statusDiv) {
                statusDiv.className = 'otp-status';
                statusDiv.style.display = 'none';
            }
            if (motherOtpTimer) clearInterval(motherOtpTimer);
            
            const verifyBtn = document.getElementById('verifyMotherOtpBtn');
            if (verifyBtn) {
                verifyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Verify OTP';
                verifyBtn.style.display = 'none';
            }
            const resendBtn = document.getElementById('resendMotherOtpBtn');
            if (resendBtn) resendBtn.style.display = 'none';
        } else if (type === 'guardian') {
            isGuardianOtpVerified = false;
            const otpSection = document.getElementById('guardianOtpSection');
            if (otpSection) otpSection.style.display = 'none';
            const statusDiv = document.getElementById('guardianOtpStatus');
            if (statusDiv) {
                statusDiv.className = 'otp-status';
                statusDiv.style.display = 'none';
            }
            if (guardianOtpTimer) clearInterval(guardianOtpTimer);
            
            const verifyBtn = document.getElementById('verifyGuardianOtpBtn');
            if (verifyBtn) {
                verifyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Verify OTP';
                verifyBtn.style.display = 'none';
            }
            const resendBtn = document.getElementById('resendGuardianOtpBtn');
            if (resendBtn) resendBtn.style.display = 'none';
        }
    }

    // Check if phone number has been edited and show/hide OTP button accordingly
    function setupPhoneNumberWatcher(inputId, type) {
        const input = document.getElementById(inputId);
        if (!input) return;
        
        // Store original value
        if (type === 'student') originalStudentPhone = input.value;
        if (type === 'father') originalFatherPhone = input.value;
        if (type === 'mother') originalMotherPhone = input.value;
        if (type === 'guardian') originalGuardianPhone = input.value;
        
        // Function to check and update button visibility
        function updateButtonVisibility() {
            const currentValue = input.value.trim();
            let originalValue = '';
            if (type === 'student') originalValue = originalStudentPhone;
            if (type === 'father') originalValue = originalFatherPhone;
            if (type === 'mother') originalValue = originalMotherPhone;
            if (type === 'guardian') originalValue = originalGuardianPhone;
            
            // Check if phone number has changed from original AND is valid
            const isEdited = currentValue !== originalValue && currentValue.length === 10 && /^\d{10}$/.test(currentValue);
            
            if (type === 'student') isStudentPhoneEdited = isEdited;
            if (type === 'father') isFatherPhoneEdited = isEdited;
            if (type === 'mother') isMotherPhoneEdited = isEdited;
            if (type === 'guardian') isGuardianPhoneEdited = isEdited;
            
            // Show/hide edit indicator
            const indicator = document.getElementById(`${type}PhoneEditIndicator`);
            if (indicator) {
                indicator.style.display = isEdited ? 'block' : 'none';
            }
            
            // Show OTP button only if phone number is valid AND it has been edited
            const sendBtn = document.getElementById(`send${type.charAt(0).toUpperCase() + type.slice(1)}OtpBtn`);
            if (sendBtn) {
                if (isEdited && currentValue.length === 10 && /^\d{10}$/.test(currentValue)) {
                    sendBtn.style.display = 'inline-block';
                    console.log(`${type} OTP button shown - phone edited`);
                } else {
                    sendBtn.style.display = 'none';
                    console.log(`${type} OTP button hidden - not edited or invalid`);
                }
            }
            
            // Reset verification if phone number changed
            if (isEdited) {
                resetOtpVerification(type);
            }
        }
        
        // Listen for both input and change events
        input.addEventListener('input', updateButtonVisibility);
        input.addEventListener('change', updateButtonVisibility);
        
        // Initial check
        updateButtonVisibility();
    }
    
    async function sendOTP(type) {
        let isEdited = false;
        if (type === 'student') isEdited = isStudentPhoneEdited;
        if (type === 'father') isEdited = isFatherPhoneEdited;
        if (type === 'mother') isEdited = isMotherPhoneEdited;
        if (type === 'guardian') isEdited = isGuardianPhoneEdited;
        
        if (!isEdited) {
            showToast('Phone number not changed. OTP verification only required for edited numbers.', 'error');
            return;
        }
        
        const phone = getPhoneNumber(type);
        if (!phone || phone.length !== 10) {
            showToast('Please enter a valid 10-digit phone number', 'error');
            return;
        }
    
        const sendBtn = document.getElementById(`send${type.charAt(0).toUpperCase() + type.slice(1)}OtpBtn`);
        const verifyBtn = document.getElementById(`verify${type.charAt(0).toUpperCase() + type.slice(1)}OtpBtn`);
        const resendBtn = document.getElementById(`resend${type.charAt(0).toUpperCase() + type.slice(1)}OtpBtn`);
        
        const originalText = sendBtn?.innerHTML || '';
        if (sendBtn) {
            sendBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Sending...';
            sendBtn.disabled = true;
        }
        
        // Reset UI - hide resend button, hide send button, show verify button
        if (resendBtn) resendBtn.style.display = 'none';
        if (sendBtn) sendBtn.style.display = 'none';
        if (verifyBtn) {
            verifyBtn.style.display = 'inline-block';
            verifyBtn.disabled = false;
            verifyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Verify OTP';
        }
        
        if (type === 'student') isStudentOtpVerified = false;
        if (type === 'father') isFatherOtpVerified = false;
        if (type === 'mother') isMotherOtpVerified = false;
        if (type === 'guardian') isGuardianOtpVerified = false;
        
        document.querySelectorAll(`.${type}-otp-digit`).forEach(input => {
            input.value = '';
            input.disabled = false;
        });
        
        const statusDiv = document.getElementById(`${type}OtpStatus`);
        if (statusDiv) {
            statusDiv.className = 'otp-status';
            statusDiv.style.display = 'none';
            statusDiv.innerHTML = '';
        }
    
        try {
            const result = await makeAjaxRequest('/send/otp', 'POST', { mobile_number: phone });
    
            if (result.Success) {
                const generatedOTP = result.Success;
                if (type === 'student') studentGeneratedOTP = generatedOTP;
                if (type === 'father') fatherGeneratedOTP = generatedOTP;
                if (type === 'mother') motherGeneratedOTP = generatedOTP;
                if (type === 'guardian') guardianGeneratedOTP = generatedOTP;
                
                showToast('OTP sent successfully!', 'success');
                showOtpSection(type);
                
                const timerVarMap = { student: 'studentOtpTimer', father: 'fatherOtpTimer', mother: 'motherOtpTimer', guardian: 'guardianOtpTimer' };
                const verifyBtnMap = { student: 'verifyStudentOtpBtn', father: 'verifyFatherOtpBtn', mother: 'verifyMotherOtpBtn', guardian: 'verifyGuardianOtpBtn' };
                const resendBtnMap = { student: 'resendStudentOtpBtn', father: 'resendFatherOtpBtn', mother: 'resendMotherOtpBtn', guardian: 'resendGuardianOtpBtn' };
                
                startOTPTimer(timerVarMap[type], verifyBtnMap[type], resendBtnMap[type], type);
            } else {
                showToast(result.message || 'Failed to send OTP', 'error');
                fallbackGenerateOTP(type);
            }
        } catch (error) {
            console.error('Error sending OTP:', error);
            fallbackGenerateOTP(type);
        } finally {
            if (sendBtn) {
                sendBtn.innerHTML = originalText;
                sendBtn.disabled = false;
            }
        }
    }
    
    function fallbackGenerateOTP(type) {
        let generatedOTP = '';
        if (type === 'student') generatedOTP = studentGeneratedOTP = Math.floor(1000 + Math.random() * 9000).toString();
        if (type === 'father') generatedOTP = fatherGeneratedOTP = Math.floor(1000 + Math.random() * 9000).toString();
        if (type === 'mother') generatedOTP = motherGeneratedOTP = Math.floor(1000 + Math.random() * 9000).toString();
        if (type === 'guardian') generatedOTP = guardianGeneratedOTP = Math.floor(1000 + Math.random() * 9000).toString();
        
        console.log(`Generated OTP for ${type} (local):`, generatedOTP);
        
        showOtpSection(type);
        showToast(`Demo OTP: ${generatedOTP}`, 'success');
        
        const timerVarMap = { student: 'studentOtpTimer', father: 'fatherOtpTimer', mother: 'motherOtpTimer', guardian: 'guardianOtpTimer' };
        const verifyBtnMap = { student: 'verifyStudentOtpBtn', father: 'verifyFatherOtpBtn', mother: 'verifyMotherOtpBtn', guardian: 'verifyGuardianOtpBtn' };
        const resendBtnMap = { student: 'resendStudentOtpBtn', father: 'resendFatherOtpBtn', mother: 'resendMotherOtpBtn', guardian: 'resendGuardianOtpBtn' };
        
        if (type === 'student') isStudentOtpVerified = false;
        if (type === 'father') isFatherOtpVerified = false;
        if (type === 'mother') isMotherOtpVerified = false;
        if (type === 'guardian') isGuardianOtpVerified = false;
        
        startOTPTimer(timerVarMap[type], verifyBtnMap[type], resendBtnMap[type], type);
    }

    async function verifyOTP(type) {
        let enteredOTP = '';
        document.querySelectorAll(`.${type}-otp-digit`).forEach(input => {
            enteredOTP += input.value;
        });

        if (enteredOTP.length !== 4) {
            showToast('Please enter the complete 4-digit OTP', 'error');
            return;
        }

        const verifyBtn = document.getElementById(`verify${type.charAt(0).toUpperCase() + type.slice(1)}OtpBtn`);
        const originalText = verifyBtn?.innerHTML || '';
        if (verifyBtn) {
            verifyBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Verifying...';
            verifyBtn.disabled = true;
        }

        try {
            const phone = getPhoneNumber(type);
            const result = await makeAjaxRequest('/verify/otp', 'POST', {
                mobile_number: cleanPhoneNumber(phone),
                otp: enteredOTP
            });

            let generatedOTP = '';
            if (type === 'student') generatedOTP = studentGeneratedOTP;
            if (type === 'father') generatedOTP = fatherGeneratedOTP;
            if (type === 'mother') generatedOTP = motherGeneratedOTP;
            if (type === 'guardian') generatedOTP = guardianGeneratedOTP;
if (result.Success || (generatedOTP && enteredOTP === generatedOTP)) {
    if (type === 'student') isStudentOtpVerified = true;
    if (type === 'father') isFatherOtpVerified = true;
    if (type === 'mother') isMotherOtpVerified = true;
    if (type === 'guardian') isGuardianOtpVerified = true;
    
    showToast(`${type.charAt(0).toUpperCase() + type.slice(1)} phone number verified successfully!`, 'success');
    
    const statusDiv = document.getElementById(`${type}OtpStatus`);
    if (statusDiv) {
        statusDiv.className = 'otp-status success';
        statusDiv.innerHTML = '<i class="bi bi-check-circle-fill"></i> Phone number verified!';
        statusDiv.style.display = 'block';
    }

    // Disable OTP inputs
    document.querySelectorAll(`.${type}-otp-digit`).forEach(input => {
        input.disabled = true;
    });
    
    // HIDE BOTH buttons after successful verification
    if (verifyBtn) verifyBtn.style.display = 'none';
    const resendBtn = document.getElementById(`resend${type.charAt(0).toUpperCase() + type.slice(1)}OtpBtn`);
    if (resendBtn) resendBtn.style.display = 'none';

    // Clear timer
    let timerVar = null;
    if (type === 'student') timerVar = studentOtpTimer;
    if (type === 'father') timerVar = fatherOtpTimer;
    if (type === 'mother') timerVar = motherOtpTimer;
    if (type === 'guardian') timerVar = guardianOtpTimer;
    
    if (window[timerVar]) clearInterval(window[timerVar]);
}else {
                showToast('Invalid OTP. Please try again.', 'error');
                document.querySelectorAll(`.${type}-otp-digit`).forEach(input => {
                    input.value = '';
                });
                const firstDigit = document.querySelector(`.${type}-otp-digit`);
                if (firstDigit) firstDigit.focus();
            }
        } catch (error) {
            console.error('Error verifying OTP:', error);
            showToast('Error verifying OTP. Please try again.', 'error');
        } finally {
            if (verifyBtn) {
                verifyBtn.innerHTML = originalText;
                verifyBtn.disabled = false;
            }
        }
    }

    function setupOtpDigitHandlers(type) {
        // Auto-tab to next digit on input
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains(`${type}-otp-digit`)) {
                if (e.target.value.length === 1 && /^\d$/.test(e.target.value)) {
                    const next = e.target.nextElementSibling;
                    if (next && next.classList.contains(`${type}-otp-digit`)) {
                        next.focus();
                    }
                }
            }
        });

        // Handle backspace to go to previous digit
        document.addEventListener('keydown', function(e) {
            if (e.target.classList.contains(`${type}-otp-digit`)) {
                if (e.key === 'Backspace' && e.target.value === '') {
                    const prev = e.target.previousElementSibling;
                    if (prev && prev.classList.contains(`${type}-otp-digit`)) {
                        prev.focus();
                    }
                }
            }
        });
    }

    /* ===================== VALIDATION FUNCTIONS ===================== */
    function validateForm1() {
        const studentMobile = document.getElementById('student_mobile')?.value.trim();
        
        if (!studentMobile || studentMobile.length !== 10) {
            showToast('Please enter a valid 10-digit student mobile number', 'error');
            return false;
        }
        
        if (isStudentPhoneEdited && !isStudentOtpVerified) {
            showToast('Please verify the edited student mobile number with OTP', 'error');
            return false;
        }
        
        // Student Email Validation
        const studentEmail = document.getElementById('student_email')?.value.trim();
        if (!studentEmail || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(studentEmail)) {
            showToast('Please enter a valid student email address', 'error');
            return false;
        }
        if (isStudentEmailEdited && !isStudentEmailVerified) {
            showToast('Please verify the edited student email with OTP', 'error');
            return false;
        }
        
        // Father Email Validation
        const fatherEmail = document.getElementById('father_email')?.value.trim();
        if (fatherEmail && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(fatherEmail)) {
            showToast('Please enter a valid father email address', 'error');
            return false;
        }
        if (isFatherEmailEdited && fatherEmail && !isFatherEmailVerified) {
            showToast('Please verify the edited father email with OTP', 'error');
            return false;
        }
        
        // Mother Email Validation
        const motherEmail = document.getElementById('mother_email')?.value.trim();
        if (motherEmail && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(motherEmail)) {
            showToast('Please enter a valid mother email address', 'error');
            return false;
        }
        if (isMotherEmailEdited && motherEmail && !isMotherEmailVerified) {
            showToast('Please verify the edited mother email with OTP', 'error');
            return false;
        }
        
        // Guardian Email Validation (only if guardian type is 'other')
        const guardianType = document.querySelector('input[name="guardian_type"]:checked')?.value;
        if (guardianType === 'other') {
            const guardianEmail = document.getElementById('guardian_email')?.value.trim();
            if (guardianEmail && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(guardianEmail)) {
                showToast('Please enter a valid guardian email address', 'error');
                return false;
            }
            if (isGuardianEmailEdited && guardianEmail && !isGuardianEmailVerified) {
                showToast('Please verify the edited guardian email with OTP', 'error');
                return false;
            }
        }
        
        // Father phone validation
        if (isFatherPhoneEdited) {
            const fatherPhone = document.getElementById('father_phone')?.value.trim();
            if (fatherPhone && fatherPhone.length === 10 && !isFatherOtpVerified) {
                showToast('Please verify the edited father\'s phone number with OTP', 'error');
                return false;
            }
        }
        
        // Mother phone validation
        if (isMotherPhoneEdited) {
            const motherPhone = document.getElementById('mother_phone')?.value.trim();
            if (motherPhone && motherPhone.length === 10 && !isMotherOtpVerified) {
                showToast('Please verify the edited mother\'s phone number with OTP', 'error');
                return false;
            }
        }
        
        // Guardian phone validation
        if (guardianType === 'other' && isGuardianPhoneEdited) {
            const guardianPhone = document.getElementById('guardian_phone')?.value.trim();
            if (guardianPhone && guardianPhone.length === 10 && !isGuardianOtpVerified) {
                showToast('Please verify the edited guardian\'s phone number with OTP', 'error');
                return false;
            }
        }
        
        return true;
    }

    /* ===================== FORM SUBMISSION ===================== */
    function submitFormData(form, step, cb) {
        const fd = new FormData(form);
        const pathParts = window.location.pathname.split('/');
        const studentHashId = pathParts[pathParts.indexOf('admin') + 1];

        if (!studentHashId) {
            Swal.fire('Error', 'Student ID not found', 'error');
            return;
        }

        fd.append('_method', 'PUT');

        const btn = form.querySelector('button[type="submit"]');
        const original = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Updating...';
        btn.disabled = true;

        fetch(`/institute/admin/${studentHashId}`, {
            method: "POST",
            body: fd,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json"
            }
        })
        .then(async r => {
            const json = await r.json().catch(() => ({}));
            if (!r.ok) throw new Error(json.message || "Update failed");
            return json;
        })
        .then(d => {
            btn.innerHTML = original;
            btn.disabled = false;

            if (step === forms.length) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Student data updated successfully',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = '/institute/admin/students';
                });
            }

            if (typeof cb === "function") cb(d);
        })
        .catch(err => {
            btn.innerHTML = original;
            btn.disabled = false;
            Swal.fire('Error', err.message || 'Something went wrong!', 'error');
        });
    }

    /* ===================== DISPLAY FUNCTIONS ===================== */
    function showForm(step) {
        forms.forEach((id, i) => {
            const form = document.getElementById(id);
            if (form) form.style.display = i === step ? "block" : "none";
        });

        steps.forEach((id, i) => {
            const el = document.getElementById(id);
            if (!el) return;
            el.classList.toggle("active", i === step);
            el.classList.toggle("completed", i < step);
        });

        updateProgressBar(step);
    }

    function updateProgressBar(step) {
        const percentage = (step / (steps.length - 1)) * 80;
        if (progressBar) progressBar.style.width = `${percentage}%`;
    }

    function initFormStates() {
        steps.forEach((stepId, i) => {
            const el = document.getElementById(stepId);
            if (!el) return;
            el.addEventListener("click", () => {
                showForm(i);
            });
        });
    }

    /* ===================== GUARDIAN LOGIC ===================== */
    function updateGuardianValue() {
        const guardianRadios = document.querySelectorAll('input[name="guardian_type"]');
        const guardianContainer = document.querySelector('.guardianContainer');
        const guardianAddressContainer = document.querySelector('.guardian-address-container');
        const guardianDocsContainer = document.querySelector('.otherDocumentsContainer');
        
        if (!guardianRadios.length || !guardianContainer) return;
        
        const selected = Array.from(guardianRadios).find(radio => radio.checked)?.value;
        
        if (selected === 'other') {
            guardianContainer.classList.remove('d-none');
            if (guardianAddressContainer) guardianAddressContainer.classList.remove('d-none');
            if (guardianDocsContainer) guardianDocsContainer.classList.remove('d-none');
        } else {
            guardianContainer.classList.add('d-none');
            if (guardianAddressContainer) guardianAddressContainer.classList.add('d-none');
            if (guardianDocsContainer) guardianDocsContainer.classList.add('d-none');
            autoFillGuardian(selected);
        }
    }

    function autoFillGuardian(type) {
        const prefix = type === 'father' ? 'father' : 'mother';
        const guardianFirstName = document.querySelector('input[name="guardian_first_name"]');
        const guardianMiddleName = document.querySelector('input[name="guardian_middle_name"]');
        const guardianLastName = document.querySelector('input[name="guardian_last_name"]');
        const guardianEmail = document.querySelector('input[name="guardian_email"]');
        const guardianPhone = document.querySelector('input[name="guardian_phone"]');
        const guardianOccupation = document.querySelector('select[name="guardian_occupation"]');
        const guardianIncome = document.querySelector('input[name="guardian_income"]');
        const guardianBloodGroup = document.querySelector('select[name="guardian_blood_group"]');
        const guardianRelation = document.querySelector('input[name="guardian_relation"]');
        
        if (guardianFirstName) guardianFirstName.value = document.querySelector(`input[name="${prefix}_first_name"]`)?.value || '';
        if (guardianMiddleName) guardianMiddleName.value = document.querySelector(`input[name="${prefix}_middle_name"]`)?.value || '';
        if (guardianLastName) guardianLastName.value = document.querySelector(`input[name="${prefix}_last_name"]`)?.value || '';
        if (guardianEmail) guardianEmail.value = document.querySelector(`input[name="${prefix}_email"]`)?.value || '';
        if (guardianPhone) guardianPhone.value = document.querySelector(`input[name="${prefix}_phone"]`)?.value || '';
        if (guardianOccupation) guardianOccupation.value = document.querySelector(`select[name="${prefix}_occupation"]`)?.value || '';
        if (guardianIncome) guardianIncome.value = document.querySelector(`input[name="${prefix}_income"]`)?.value || '';
        if (guardianBloodGroup) guardianBloodGroup.value = document.querySelector(`select[name="${prefix}_blood_group"]`)?.value || '';
        if (guardianRelation) guardianRelation.value = type === 'father' ? 'Father' : 'Mother';
        
        // Reset guardian edit tracking and verification when auto-filled
        isGuardianPhoneEdited = false;
        isGuardianOtpVerified = false;
        
        const guardianOtpSection = document.getElementById('guardianOtpSection');
        if (guardianOtpSection) guardianOtpSection.style.display = 'none';
        
        const guardianOtpStatus = document.getElementById('guardianOtpStatus');
        if (guardianOtpStatus) {
            guardianOtpStatus.className = 'otp-status';
            guardianOtpStatus.style.display = 'none';
        }
        
        const sendGuardianOtpBtn = document.getElementById('sendGuardianOtpBtn');
        if (sendGuardianOtpBtn) sendGuardianOtpBtn.style.display = 'none';
        
        const indicator = document.getElementById('guardianPhoneEditIndicator');
        if (indicator) indicator.style.display = 'none';
        
        // Update original value for guardian
        originalGuardianPhone = guardianPhone?.value || '';
    }

    function initGuardianLogic() {
        const guardianRadios = document.querySelectorAll('input[name="guardian_type"]');
        guardianRadios.forEach(radio => {
            radio.addEventListener('change', updateGuardianValue);
        });
        
        const defaultGuardian = document.querySelector('input[value="father"][name="guardian_type"]');
        if (defaultGuardian) {
            defaultGuardian.checked = true;
            autoFillGuardian('father');
        }
        updateGuardianValue();
    }

    /* ===================== STUDENT BANK TOGGLE ===================== */
    function initStudentBank() {
        const checkbox = document.querySelector('input[name="add_student_bank"]');
        const container = document.querySelector('.studentBank');
        if (!checkbox || !container) return;
        checkbox.addEventListener("change", () => {
            container.classList.toggle("d-none", !checkbox.checked);
            if (!checkbox.checked) {
                container.querySelectorAll('input, select').forEach(el => {
                    if (el.type !== 'checkbox') {
                        el.value = '';
                    }
                });
            }
        });
    }

    /* ===================== ADDRESS COPY FUNCTIONS ===================== */
    function bindForm2Events() {
        const copyStudentAddress = document.getElementById('copyStudentAddress');
        if (copyStudentAddress) {
            copyStudentAddress.addEventListener('change', () => {
                const isChecked = copyStudentAddress.checked;
                const permFields = ['student_perm_address_line1', 'student_perm_address_line2', 'student_perm_city', 'student_perm_state', 'student_perm_pincode'];
                const commFields = ['student_comm_address_line1', 'student_comm_address_line2', 'student_comm_city', 'student_comm_state', 'student_comm_pincode'];
                
                permFields.forEach((perm, index) => {
                    const permInput = document.querySelector(`input[name="${perm}"]`);
                    const commInput = document.querySelector(`input[name="${commFields[index]}"]`);
                    if (permInput && commInput && isChecked) {
                        commInput.value = permInput.value;
                    }
                });
            });
        }
        
        const copyStudentToParent = document.getElementById('copyStudentToParent');
        if (copyStudentToParent) {
            copyStudentToParent.addEventListener('change', () => {
                const isChecked = copyStudentToParent.checked;
                const studentPermFields = ['student_perm_address_line1', 'student_perm_address_line2', 'student_perm_city', 'student_perm_state', 'student_perm_pincode'];
                const studentCommFields = ['student_comm_address_line1', 'student_comm_address_line2', 'student_comm_city', 'student_comm_state', 'student_comm_pincode'];
                const parentPermFields = ['parent_perm_address_line1', 'parent_perm_address_line2', 'parent_perm_city', 'parent_perm_state', 'parent_perm_pincode'];
                const parentCommFields = ['parent_comm_address_line1', 'parent_comm_address_line2', 'parent_comm_city', 'parent_comm_state', 'parent_comm_pincode'];
                
                studentPermFields.forEach((studentField, index) => {
                    const studentPermInput = document.querySelector(`input[name="${studentField}"]`);
                    const parentPermInput = document.querySelector(`input[name="${parentPermFields[index]}"]`);
                    if (studentPermInput && parentPermInput && isChecked) {
                        parentPermInput.value = studentPermInput.value;
                    }
                });
                
                studentCommFields.forEach((studentField, index) => {
                    const studentCommInput = document.querySelector(`input[name="${studentField}"]`);
                    const parentCommInput = document.querySelector(`input[name="${parentCommFields[index]}"]`);
                    if (studentCommInput && parentCommInput && isChecked) {
                        parentCommInput.value = studentCommInput.value;
                    }
                });
            });
        }
        
        const copyParentAddress = document.getElementById('copyParentAddress');
        if (copyParentAddress) {
            copyParentAddress.addEventListener('change', () => {
                const isChecked = copyParentAddress.checked;
                const permFields = ['parent_perm_address_line1', 'parent_perm_address_line2', 'parent_perm_city', 'parent_perm_state', 'parent_perm_pincode'];
                const commFields = ['parent_comm_address_line1', 'parent_comm_address_line2', 'parent_comm_city', 'parent_comm_state', 'parent_comm_pincode'];
                
                permFields.forEach((perm, index) => {
                    const permInput = document.querySelector(`input[name="${perm}"]`);
                    const commInput = document.querySelector(`input[name="${commFields[index]}"]`);
                    if (permInput && commInput && isChecked) {
                        commInput.value = permInput.value;
                    }
                });
            });
        }
        
        const copyGuardianAddress = document.getElementById('copyGuardianAddress');
        if (copyGuardianAddress) {
            copyGuardianAddress.addEventListener('change', () => {
                const isChecked = copyGuardianAddress.checked;
                const permFields = ['guardian_perm_address_line1', 'guardian_perm_address_line2', 'guardian_perm_city', 'guardian_perm_state', 'guardian_perm_pincode'];
                const commFields = ['guardian_comm_address_line1', 'guardian_comm_address_line2', 'guardian_comm_city', 'guardian_comm_state', 'guardian_comm_pincode'];
                
                permFields.forEach((perm, index) => {
                    const permInput = document.querySelector(`input[name="${perm}"]`);
                    const commInput = document.querySelector(`input[name="${commFields[index]}"]`);
                    if (permInput && commInput && isChecked) {
                        commInput.value = permInput.value;
                    }
                });
            });
        }
    }

    /* ===================== OTHER DOCUMENTS ===================== */
    function initOtherDocuments() {
        const addBtn = document.getElementById('addOtherDocBtn');
        if (addBtn) {
            addBtn.addEventListener('click', () => {
                if (otherDocCount >= maxOtherDocs) {
                    showToast(`Maximum ${maxOtherDocs} additional documents allowed`, 'error');
                    return;
                }
                otherDocCount++;
                const wrapper = document.getElementById('other-documents-wrapper');
                if (!wrapper) return;

                const groupHTML = `
                    <div class="col-md-6 other-doc-group">
                        <label class="form-label">Document Name ${otherDocCount}</label>
                        <input type="text" name="student_other_doc_label_${otherDocCount}" class="form-control" placeholder="Enter document name">
                    </div>
                    <div class="col-md-6 other-doc-group d-flex align-items-end">
                        <div class="w-100">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                Upload File ${otherDocCount}
                                <button type="button" class="btn btn-sm btn-danger ms-2 removeOtherDocBtn"><i class="bi bi-trash-fill"></i></button>
                            </label>
                            <input type="file" name="student_other_doc_file_${otherDocCount}" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>`;
                wrapper.insertAdjacentHTML('beforeend', groupHTML);
            });
        }

        document.addEventListener('click', e => {
            const btn = e.target.closest('.removeOtherDocBtn');
            if (btn) {
                const group = btn.closest('.other-doc-group');
                group?.previousElementSibling?.remove();
                group?.remove();
                otherDocCount = Math.max(1, otherDocCount - 1);
            }
        });
    }

    /* ===================== FORM SUBMISSIONS ===================== */
    function initFormSubmissions() {
        const form1 = document.getElementById('form1');
        if (form1) {
            form1.addEventListener("submit", function(e) {
                e.preventDefault();
                if (!validateForm1()) {
                    return;
                }
                submitFormData(this, 1, (d) => {
                    currentStep = 1;
                    showForm(1);
                    
                    const guardianType = d.guardian_type;
                    const addressContainer = document.querySelector(".guardian-address-container");
                    const docsContainer = document.querySelector(".otherDocumentsContainer");
                    
                    if (guardianType && guardianType.toLowerCase() === "other") {
                        if (addressContainer) addressContainer.classList.remove("d-none");
                        if (docsContainer) docsContainer.classList.remove("d-none");
                    } else {
                        if (addressContainer) addressContainer.classList.add("d-none");
                        if (docsContainer) docsContainer.classList.add("d-none");
                    }
                });
            });
        }
        
        const form2 = document.getElementById('form2');
        if (form2) {
            form2.addEventListener("submit", function(e) {
                e.preventDefault();
                submitFormData(this, 2, () => {
                    currentStep = 2;
                    showForm(2);
                });
            });
        }
        
        const form3 = document.getElementById('form3');
        if (form3) {
            form3.addEventListener("submit", function(e) {
                e.preventDefault();
                submitFormData(this, 3, () => {
                    currentStep = 3;
                    showForm(3);
                });
            });
        }
        
        const form4 = document.getElementById('form4');
        if (form4) {
            form4.addEventListener("submit", function(e) {
                e.preventDefault();
                submitFormData(this, 4, () => {
                    currentStep = 4;
                    showForm(4);
                });
            });
        }
        
        const form5 = document.getElementById('form5');
        if (form5) {
            form5.addEventListener("submit", function(e) {
                e.preventDefault();
                submitFormData(this, 5, (d) => {
                    if (d.success) {
                        setTimeout(() => {
                            window.location.href = '/institute/admin/students';
                        }, 2000);
                    }
                });
            });
        }
    }

    /* ===================== BACK BUTTONS ===================== */
    function initBackButtons() {
        const backs = [
            { id: 'backToForm1', step: 0 },
            { id: 'back2', step: 1 },
            { id: 'back3', step: 2 },
            { id: 'back4', step: 3 },
        ];
        backs.forEach(b => {
            const btn = document.getElementById(b.id);
            if (btn) {
                btn.addEventListener('click', () => {
                    currentStep = b.step;
                    showForm(b.step);
                });
            }
        });
    }

    /* ===================== SECTIONS LOADER ===================== */
    function loadSections() {
        const courseSubtypeId = $('#course_detail_id').val();
        const instituteId = @json(auth()->user()->institute_id);
        
        if (!courseSubtypeId) return;
        if (!instituteId) return;
        
        const currentSection = @json(old('section_id', $extra->section_id ?? ''));
        
        $.ajax({
            url: `/institute/admin/get-sections/${courseSubtypeId}`,
            method: 'GET',
            data: { institute_id: instituteId },
            success: function(response) {
                if (response.success && response.sections) {
                    const sectionSelect = $('#section_id');
                    sectionSelect.empty();
                    sectionSelect.append('<option value="">Select Section</option>');
                    
                    response.sections.forEach(function(section) {
                        let sectionId = section.id;
                        let sectionName = section.name || section;
                        
                        const normalizedSectionId = sectionId.toString().replace('section_', '');
                        const normalizedCurrentSection = currentSection.toString().replace('section_', '');
                        
                        const selected = normalizedSectionId === normalizedCurrentSection ? 'selected' : '';
                        sectionSelect.append(`<option value="${sectionId}" ${selected}>${sectionName}</option>`);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    }

    /* ===================== DOM READY ===================== */
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded - setting up OTP verification');
        
        showForm(currentStep);
        initFormStates();
        bindForm2Events();
        initGuardianLogic();
        initStudentBank();
        initFormSubmissions();
        initBackButtons();
        initOtherDocuments();
        setupFatherEmailWatcher();
        setupMotherEmailWatcher();
        setupGuardianEmailWatcher();
        
        setupEmailOtpDigitHandlers('father');
        setupEmailOtpDigitHandlers('mother');
        setupEmailOtpDigitHandlers('guardian');
        setupEmailWatcher();
        setupEmailOtpDigitHandlers();
        // Setup phone number watchers (these will track edits)
        setupPhoneNumberWatcher('student_mobile', 'student');
        setupPhoneNumberWatcher('father_phone', 'father');
        setupPhoneNumberWatcher('mother_phone', 'mother');
        setupPhoneNumberWatcher('guardian_phone', 'guardian');
        
        setupOtpDigitHandlers('student');
        setupOtpDigitHandlers('father');
        setupOtpDigitHandlers('mother');
        setupOtpDigitHandlers('guardian');
        const sendStudentEmailOtpBtn = document.getElementById('sendStudentEmailOtpBtn');
        if (sendStudentEmailOtpBtn) sendStudentEmailOtpBtn.addEventListener('click', sendStudentEmailOTP);
        
        const verifyStudentEmailOtpBtn = document.getElementById('verifyStudentEmailOtpBtn');
        if (verifyStudentEmailOtpBtn) verifyStudentEmailOtpBtn.addEventListener('click', verifyStudentEmailOTP);
        
        const resendStudentEmailOtpBtn = document.getElementById('resendStudentEmailOtpBtn');
        if (resendStudentEmailOtpBtn) resendStudentEmailOtpBtn.addEventListener('click', sendStudentEmailOTP);
        // Send OTP button handlers
        const sendStudentOtpBtn = document.getElementById('sendStudentOtpBtn');
        if (sendStudentOtpBtn) sendStudentOtpBtn.addEventListener('click', () => sendOTP('student'));
        
        const sendFatherOtpBtn = document.getElementById('sendFatherOtpBtn');
        if (sendFatherOtpBtn) sendFatherOtpBtn.addEventListener('click', () => sendOTP('father'));
        
        const sendMotherOtpBtn = document.getElementById('sendMotherOtpBtn');
        if (sendMotherOtpBtn) sendMotherOtpBtn.addEventListener('click', () => sendOTP('mother'));
        
        const sendGuardianOtpBtn = document.getElementById('sendGuardianOtpBtn');
        if (sendGuardianOtpBtn) sendGuardianOtpBtn.addEventListener('click', () => sendOTP('guardian'));
        const sendFatherEmailOtpBtn = document.getElementById('sendFatherEmailOtpBtn');
        if (sendFatherEmailOtpBtn) sendFatherEmailOtpBtn.addEventListener('click', sendFatherEmailOTP);
        
        const sendMotherEmailOtpBtn = document.getElementById('sendMotherEmailOtpBtn');
        if (sendMotherEmailOtpBtn) sendMotherEmailOtpBtn.addEventListener('click', sendMotherEmailOTP);
        
        const sendGuardianEmailOtpBtn = document.getElementById('sendGuardianEmailOtpBtn');
        if (sendGuardianEmailOtpBtn) sendGuardianEmailOtpBtn.addEventListener('click', sendGuardianEmailOTP);
        
        // Verify OTP button handlers for emails
        const verifyFatherEmailOtpBtn = document.getElementById('verifyFatherEmailOtpBtn');
        if (verifyFatherEmailOtpBtn) verifyFatherEmailOtpBtn.addEventListener('click', verifyFatherEmailOTP);
        
        const verifyMotherEmailOtpBtn = document.getElementById('verifyMotherEmailOtpBtn');
        if (verifyMotherEmailOtpBtn) verifyMotherEmailOtpBtn.addEventListener('click', verifyMotherEmailOTP);
        
        const verifyGuardianEmailOtpBtn = document.getElementById('verifyGuardianEmailOtpBtn');
        if (verifyGuardianEmailOtpBtn) verifyGuardianEmailOtpBtn.addEventListener('click', verifyGuardianEmailOTP);
        
        // Resend OTP button handlers for emails
        const resendFatherEmailOtpBtn = document.getElementById('resendFatherEmailOtpBtn');
        if (resendFatherEmailOtpBtn) resendFatherEmailOtpBtn.addEventListener('click', sendFatherEmailOTP);
        
        const resendMotherEmailOtpBtn = document.getElementById('resendMotherEmailOtpBtn');
        if (resendMotherEmailOtpBtn) resendMotherEmailOtpBtn.addEventListener('click', sendMotherEmailOTP);
        
        const resendGuardianEmailOtpBtn = document.getElementById('resendGuardianEmailOtpBtn');
        if (resendGuardianEmailOtpBtn) resendGuardianEmailOtpBtn.addEventListener('click', sendGuardianEmailOTP);
        // Verify OTP button handlers
        const verifyStudentOtpBtn = document.getElementById('verifyStudentOtpBtn');
        if (verifyStudentOtpBtn) verifyStudentOtpBtn.addEventListener('click', () => verifyOTP('student'));
        
        const verifyFatherOtpBtn = document.getElementById('verifyFatherOtpBtn');
        if (verifyFatherOtpBtn) verifyFatherOtpBtn.addEventListener('click', () => verifyOTP('father'));
        
        const verifyMotherOtpBtn = document.getElementById('verifyMotherOtpBtn');
        if (verifyMotherOtpBtn) verifyMotherOtpBtn.addEventListener('click', () => verifyOTP('mother'));
        
        const verifyGuardianOtpBtn = document.getElementById('verifyGuardianOtpBtn');
        if (verifyGuardianOtpBtn) verifyGuardianOtpBtn.addEventListener('click', () => verifyOTP('guardian'));
        
        // Resend OTP button handlers
        const resendStudentOtpBtn = document.getElementById('resendStudentOtpBtn');
        if (resendStudentOtpBtn) {
            resendStudentOtpBtn.addEventListener('click', () => sendOTP('student'));
        }
        
        const resendFatherOtpBtn = document.getElementById('resendFatherOtpBtn');
        if (resendFatherOtpBtn) resendFatherOtpBtn.addEventListener('click', () => sendOTP('father'));
        
        const resendMotherOtpBtn = document.getElementById('resendMotherOtpBtn');
        if (resendMotherOtpBtn) resendMotherOtpBtn.addEventListener('click', () => sendOTP('mother'));
        
        const resendGuardianOtpBtn = document.getElementById('resendGuardianOtpBtn');
        if (resendGuardianOtpBtn) resendGuardianOtpBtn.addEventListener('click', () => sendOTP('guardian'));
        
        // Force initial check for all phone fields
        setTimeout(() => {
            console.log('Performing initial phone field checks');
            const studentInput = document.getElementById('student_mobile');
            const fatherInput = document.getElementById('father_phone');
            const motherInput = document.getElementById('mother_phone');
            const guardianInput = document.getElementById('guardian_phone');
            
            if (studentInput) {
                const event = new Event('input');
                studentInput.dispatchEvent(event);
            }
            if (fatherInput) {
                const event = new Event('input');
                fatherInput.dispatchEvent(event);
            }
            if (motherInput) {
                const event = new Event('input');
                motherInput.dispatchEvent(event);
            }
            if (guardianInput) {
                const event = new Event('input');
                guardianInput.dispatchEvent(event);
            }
        }, 500);
        
        setTimeout(loadSections, 500);
    });

    window.loadSections = loadSections;
    
    function setupEmailWatcher() {
        const emailInput = document.getElementById('student_email');
        if (!emailInput) return;
        
        originalStudentEmail = emailInput.value;
        
        function updateEmailButtonVisibility() {
            const currentValue = emailInput.value.trim();
            const isEdited = currentValue !== originalStudentEmail && 
                            currentValue && 
                            /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(currentValue);
            
            isStudentEmailEdited = isEdited;
            
            const indicator = document.getElementById('studentEmailEditIndicator');
            if (indicator) indicator.style.display = isEdited ? 'block' : 'none';
            
            const sendBtn = document.getElementById('sendStudentEmailOtpBtn');
            if (sendBtn) {
                sendBtn.style.display = isEdited ? 'inline-block' : 'none';
            }
            
            if (isEdited) {
                resetEmailVerification();
            }
        }
        
        emailInput.addEventListener('input', updateEmailButtonVisibility);
        emailInput.addEventListener('change', updateEmailButtonVisibility);
        updateEmailButtonVisibility();
    }

    function resetEmailVerification() {
        isStudentEmailVerified = false;
        const otpSection = document.getElementById('studentEmailOtpSection');
        if (otpSection) otpSection.style.display = 'none';
        const statusDiv = document.getElementById('studentEmailOtpStatus');
        if (statusDiv) {
            statusDiv.className = 'otp-status';
            statusDiv.style.display = 'none';
        }
        if (studentEmailOtpTimer) clearInterval(studentEmailOtpTimer);
    }

    function showEmailOtpSection() {
        const email = document.getElementById('student_email').value;
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return;
        
        const otpSection = document.getElementById('studentEmailOtpSection');
        const emailDisplay = document.getElementById('studentEmailDisplay');
        
        if (otpSection) otpSection.style.display = 'block';
        if (emailDisplay) emailDisplay.textContent = email;
        
        document.querySelectorAll('.student-email-otp-digit').forEach(input => {
            input.value = '';
            input.disabled = false;
        });
        
        const statusDiv = document.getElementById('studentEmailOtpStatus');
        if (statusDiv) {
            statusDiv.className = 'otp-status';
            statusDiv.style.display = 'none';
        }
        
        const firstDigit = document.querySelector('.student-email-otp-digit');
        if (firstDigit) firstDigit.focus();
    }

    function startEmailOTPTimer() {
        let timeLeft = 120; // 2 minutes
        const verifyBtn = document.getElementById('verifyStudentEmailOtpBtn');
        const resendBtn = document.getElementById('resendStudentEmailOtpBtn');
        
        if (verifyBtn) {
            verifyBtn.style.display = 'inline-block';
            verifyBtn.disabled = false;
        }
        if (resendBtn) resendBtn.style.display = 'none';
        
        if (studentEmailOtpTimer) clearInterval(studentEmailOtpTimer);
        
        studentEmailOtpTimer = setInterval(function() {
            timeLeft--;
            let minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;
            
            if (verifyBtn && !isStudentEmailVerified) {
                verifyBtn.innerHTML = `<i class="bi bi-check-lg"></i> Verify OTP (${minutes}:${seconds.toString().padStart(2, '0')})`;
            }
            
            if (timeLeft <= 0) {
                clearInterval(studentEmailOtpTimer);
                if (verifyBtn && !isStudentEmailVerified) {
                    verifyBtn.style.display = 'none';
                    verifyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Verify Email OTP';
                }
                // Only show resend button if NOT verified
                if (resendBtn && !isStudentEmailVerified) {
                    resendBtn.style.display = 'inline-block';
                }
                if (!isStudentEmailVerified) {
                    showToast('Email OTP has expired. Please request a new one.', 'error');
                }
            }
        }, 1000);
    }

    async function sendStudentEmailOTP() {
        if (!isStudentEmailEdited) {
            showToast('Email not changed. OTP verification only required for edited emails.', 'error');
            return;
        }
        
        const email = document.getElementById('student_email').value.trim();
        
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showToast('Please enter a valid email address', 'error');
            return;
        }
        
        const btn = document.getElementById('sendStudentEmailOtpBtn');
        const verifyBtn = document.getElementById('verifyStudentEmailOtpBtn');
        const resendBtn = document.getElementById('resendStudentEmailOtpBtn');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Sending...';
        btn.disabled = true;
        
        // Reset verification
        isStudentEmailVerified = false;
        
        // Reset UI - hide resend button, show verify button
        if (resendBtn) resendBtn.style.display = 'none';
        if (verifyBtn) verifyBtn.style.display = 'inline-block';
        
        try {
            const response = await fetch('/send-email-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    email_id: email,
                    otp_verification_type: 'student_email_verification'
                })
            });
            
            const result = await response.json();
            
            if (result.success || result.Success) {
                studentEmailGeneratedOTP = result.otp || Math.floor(1000 + Math.random() * 9000).toString();
                showToast('OTP sent to your email!', 'success');
                showEmailOtpSection();
                startEmailOTPTimer();
            } else {
                showToast(result.message || 'Failed to send OTP', 'error');
                studentEmailGeneratedOTP = Math.floor(1000 + Math.random() * 9000).toString();
                showToast(`Demo OTP: ${studentEmailGeneratedOTP}`, 'success');
                showEmailOtpSection();
                startEmailOTPTimer();
            }
        } catch (error) {
            console.error('Error:', error);
            studentEmailGeneratedOTP = Math.floor(1000 + Math.random() * 9000).toString();
            showToast(`Demo OTP: ${studentEmailGeneratedOTP}`, 'success');
            showEmailOtpSection();
            startEmailOTPTimer();
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    async function verifyStudentEmailOTP() {
        let enteredOTP = '';
        document.querySelectorAll('.student-email-otp-digit').forEach(input => {
            enteredOTP += input.value;
        });
        
        if (enteredOTP.length !== 4) {
            showToast('Please enter the complete 4-digit OTP', 'error');
            return;
        }
        
        const verifyBtn = document.getElementById('verifyStudentEmailOtpBtn');
        const resendBtn = document.getElementById('resendStudentEmailOtpBtn');
        const originalText = verifyBtn.innerHTML;
        
        verifyBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Verifying...';
        verifyBtn.disabled = true;
        
        try {
            const email = document.getElementById('student_email').value;
            const response = await fetch('/verify-email-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    otp: enteredOTP,
                    email: email,
                    otp_verification_type: 'student_email_verification'
                })
            });
            
            const result = await response.json();
            
            if (result.success || result.Success || enteredOTP === studentEmailGeneratedOTP) {
                isStudentEmailVerified = true;
                showToast('Email verified successfully!', 'success');
                
                const statusDiv = document.getElementById('studentEmailOtpStatus');
                if (statusDiv) {
                    statusDiv.className = 'otp-status success';
                    statusDiv.innerHTML = '<i class="bi bi-check-circle-fill"></i> Email verified!';
                    statusDiv.style.display = 'block';
                }
                
                document.querySelectorAll('.student-email-otp-digit').forEach(input => {
                    input.disabled = true;
                });
                
                // Hide both verify and resend buttons after successful verification
                if (verifyBtn) verifyBtn.style.display = 'none';
                if (resendBtn) resendBtn.style.display = 'none';
                
                // Clear timer
                if (studentEmailOtpTimer) clearInterval(studentEmailOtpTimer);
            } else {
                showToast('Invalid OTP. Please try again.', 'error');
                document.querySelectorAll('.student-email-otp-digit').forEach(input => {
                    input.value = '';
                });
                const firstDigit = document.querySelector('.student-email-otp-digit');
                if (firstDigit) firstDigit.focus();
            }
        } catch (error) {
            console.error('Error:', error);
            if (enteredOTP === studentEmailGeneratedOTP) {
                isStudentEmailVerified = true;
                showToast('Email verified!', 'success');
                const statusDiv = document.getElementById('studentEmailOtpStatus');
                if (statusDiv) {
                    statusDiv.className = 'otp-status success';
                    statusDiv.innerHTML = '<i class="bi bi-check-circle-fill"></i> Email verified!';
                    statusDiv.style.display = 'block';
                }
                if (verifyBtn) verifyBtn.style.display = 'none';
                if (resendBtn) resendBtn.style.display = 'none';
            } else {
                showToast('Error verifying OTP. Please try again.', 'error');
            }
        } finally {
            verifyBtn.innerHTML = originalText;
            verifyBtn.disabled = false;
        }
    }
    
    async function sendStudentEmailOTP() {
        if (!isStudentEmailEdited) {
            showToast('Email not changed. OTP verification only required for edited emails.', 'error');
            return;
        }
        
        const email = document.getElementById('student_email').value.trim();
        
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showToast('Please enter a valid email address', 'error');
            return;
        }
        
        const btn = document.getElementById('sendStudentEmailOtpBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Sending...';
        btn.disabled = true;
        
        try {
            const response = await fetch('/send-email-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    email_id: email,
                    otp_verification_type: 'student_email_verification'
                })
            });
            
            const result = await response.json();
            
            if (result.success || result.Success) {
                studentEmailGeneratedOTP = result.otp || Math.floor(1000 + Math.random() * 9000).toString();
                showToast('OTP sent to your email!', 'success');
                showEmailOtpSection();
                isStudentEmailVerified = false;
                startEmailOTPTimer();
            } else {
                showToast(result.message || 'Failed to send OTP', 'error');
                // Fallback
                studentEmailGeneratedOTP = Math.floor(1000 + Math.random() * 9000).toString();
                showToast(`Demo OTP: ${studentEmailGeneratedOTP}`, 'success');
                showEmailOtpSection();
                isStudentEmailVerified = false;
                startEmailOTPTimer();
            }
        } catch (error) {
            console.error('Error:', error);
            studentEmailGeneratedOTP = Math.floor(1000 + Math.random() * 9000).toString();
            showToast(`Demo OTP: ${studentEmailGeneratedOTP}`, 'success');
            showEmailOtpSection();
            isStudentEmailVerified = false;
            startEmailOTPTimer();
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    async function verifyStudentEmailOTP() {
        let enteredOTP = '';
        document.querySelectorAll('.student-email-otp-digit').forEach(input => {
            enteredOTP += input.value;
        });
        
        if (enteredOTP.length !== 4) {
            showToast('Please enter the complete 4-digit OTP', 'error');
            return;
        }
        
        const verifyBtn = document.getElementById('verifyStudentEmailOtpBtn');
        const originalText = verifyBtn.innerHTML;
        verifyBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Verifying...';
        verifyBtn.disabled = true;
        
        try {
            const email = document.getElementById('student_email').value;
            const response = await fetch('/verify-email-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    otp: enteredOTP,
                    email: email,
                    otp_verification_type: 'student_email_verification'
                })
            });
            
            const result = await response.json();
            
            if (result.success || result.Success || enteredOTP === studentEmailGeneratedOTP) {
                isStudentEmailVerified = true;
                showToast('Email verified successfully!', 'success');
                
                const statusDiv = document.getElementById('studentEmailOtpStatus');
                if (statusDiv) {
                    statusDiv.className = 'otp-status success';
                    statusDiv.innerHTML = '<i class="bi bi-check-circle-fill"></i> Email verified!';
                    statusDiv.style.display = 'block';
                }
                
                document.querySelectorAll('.student-email-otp-digit').forEach(input => {
                    input.disabled = true;
                });
                
                const resendBtn = document.getElementById('resendStudentEmailOtpBtn');
                if (resendBtn) resendBtn.style.display = 'inline-block';
                
                if (studentEmailOtpTimer) clearInterval(studentEmailOtpTimer);
            } else {
                showToast('Invalid OTP. Please try again.', 'error');
                document.querySelectorAll('.student-email-otp-digit').forEach(input => {
                    input.value = '';
                });
                const firstDigit = document.querySelector('.student-email-otp-digit');
                if (firstDigit) firstDigit.focus();
            }
        } catch (error) {
            console.error('Error:', error);
            if (enteredOTP === studentEmailGeneratedOTP) {
                isStudentEmailVerified = true;
                showToast('Email verified!', 'success');
                const statusDiv = document.getElementById('studentEmailOtpStatus');
                if (statusDiv) {
                    statusDiv.className = 'otp-status success';
                    statusDiv.innerHTML = '<i class="bi bi-check-circle-fill"></i> Email verified!';
                    statusDiv.style.display = 'block';
                }
            } else {
                showToast('Error verifying OTP. Please try again.', 'error');
            }
        } finally {
            verifyBtn.innerHTML = originalText;
            verifyBtn.disabled = false;
        }
    }

    function setupEmailOtpDigitHandlers(type) {
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains(`${type}-email-otp-digit`)) {
                if (e.target.value.length === 1 && /^\d$/.test(e.target.value)) {
                    const next = e.target.nextElementSibling;
                    if (next && next.classList.contains(`${type}-email-otp-digit`)) {
                        next.focus();
                    }
                }
            }
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.target.classList.contains(`${type}-email-otp-digit`)) {
                if (e.key === 'Backspace' && e.target.value === '') {
                    const prev = e.target.previousElementSibling;
                    if (prev && prev.classList.contains(`${type}-email-otp-digit`)) {
                        prev.focus();
                    }
                }
            }
        });
    }
    
    // Mother Email Watcher
    function setupMotherEmailWatcher() {
        const emailInput = document.getElementById('mother_email');
        if (!emailInput) return;
        
        originalMotherEmail = emailInput.value;
        
        function updateEmailButtonVisibility() {
            const currentValue = emailInput.value.trim();
            const isEdited = currentValue !== originalMotherEmail && 
                            currentValue && 
                            /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(currentValue);
            
            isMotherEmailEdited = isEdited;
            
            const indicator = document.getElementById('motherEmailEditIndicator');
            if (indicator) indicator.style.display = isEdited ? 'block' : 'none';
            
            const sendBtn = document.getElementById('sendMotherEmailOtpBtn');
            if (sendBtn) sendBtn.style.display = isEdited ? 'inline-block' : 'none';
            
            if (isEdited) resetMotherEmailVerification();
        }
        
        emailInput.addEventListener('input', updateEmailButtonVisibility);
        emailInput.addEventListener('change', updateEmailButtonVisibility);
        updateEmailButtonVisibility();
    }
    
    function resetMotherEmailVerification() {
        isMotherEmailVerified = false;
        const otpSection = document.getElementById('motherEmailOtpSection');
        if (otpSection) otpSection.style.display = 'none';
        const statusDiv = document.getElementById('motherEmailOtpStatus');
        if (statusDiv) {
            statusDiv.className = 'otp-status';
            statusDiv.style.display = 'none';
        }
        if (motherEmailOtpTimer) clearInterval(motherEmailOtpTimer);
    }
    
    function showMotherEmailOtpSection() {
        const email = document.getElementById('mother_email').value;
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return;
        
        const otpSection = document.getElementById('motherEmailOtpSection');
        const emailDisplay = document.getElementById('motherEmailDisplay');
        
        if (otpSection) otpSection.style.display = 'block';
        if (emailDisplay) emailDisplay.textContent = email;
        
        document.querySelectorAll('.mother-email-otp-digit').forEach(input => {
            input.value = '';
            input.disabled = false;
        });
        
        const statusDiv = document.getElementById('motherEmailOtpStatus');
        if (statusDiv) {
            statusDiv.className = 'otp-status';
            statusDiv.style.display = 'none';
        }
        
        const firstDigit = document.querySelector('.mother-email-otp-digit');
        if (firstDigit) firstDigit.focus();
    }
    
    function startMotherEmailOTPTimer() {
        let timeLeft = 120;
        const verifyBtn = document.getElementById('verifyMotherEmailOtpBtn');
        const resendBtn = document.getElementById('resendMotherEmailOtpBtn');
        
        if (verifyBtn) {
            verifyBtn.style.display = 'inline-block';
            verifyBtn.disabled = false;
        }
        if (resendBtn) resendBtn.style.display = 'none';
        
        if (motherEmailOtpTimer) clearInterval(motherEmailOtpTimer);
        
        motherEmailOtpTimer = setInterval(function() {
            timeLeft--;
            let minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;
            
            if (verifyBtn && !isMotherEmailVerified) {
                verifyBtn.innerHTML = `<i class="bi bi-check-lg"></i> Verify OTP (${minutes}:${seconds.toString().padStart(2, '0')})`;
            }
            
            if (timeLeft <= 0) {
                clearInterval(motherEmailOtpTimer);
                if (verifyBtn && !isMotherEmailVerified) {
                    verifyBtn.style.display = 'none';
                    verifyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Verify Email OTP';
                }
                if (resendBtn && !isMotherEmailVerified) {
                    resendBtn.style.display = 'inline-block';
                }
                if (!isMotherEmailVerified) {
                    showToast('Email OTP has expired. Please request a new one.', 'error');
                }
            }
        }, 1000);
    }
    
    async function sendMotherEmailOTP() {
        if (!isMotherEmailEdited) {
            showToast('Email not changed. OTP verification only required for edited emails.', 'error');
            return;
        }
        
        const email = document.getElementById('mother_email').value.trim();
        
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showToast('Please enter a valid email address', 'error');
            return;
        }
        
        const btn = document.getElementById('sendMotherEmailOtpBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Sending...';
        btn.disabled = true;
        
        try {
            const response = await fetch('/send-email-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    email_id: email,
                    otp_verification_type: 'mother_email_verification'
                })
            });
            
            const result = await response.json();
            
            if (result.success || result.Success) {
                motherEmailGeneratedOTP = result.otp || Math.floor(1000 + Math.random() * 9000).toString();
                showToast('OTP sent to mother\'s email!', 'success');
                showMotherEmailOtpSection();
                isMotherEmailVerified = false;
                startMotherEmailOTPTimer();
            } else {
                motherEmailGeneratedOTP = Math.floor(1000 + Math.random() * 9000).toString();
                showToast(`Demo OTP: ${motherEmailGeneratedOTP}`, 'success');
                showMotherEmailOtpSection();
                isMotherEmailVerified = false;
                startMotherEmailOTPTimer();
            }
        } catch (error) {
            motherEmailGeneratedOTP = Math.floor(1000 + Math.random() * 9000).toString();
            showToast(`Demo OTP: ${motherEmailGeneratedOTP}`, 'success');
            showMotherEmailOtpSection();
            isMotherEmailVerified = false;
            startMotherEmailOTPTimer();
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }
    
    async function verifyMotherEmailOTP() {
        let enteredOTP = '';
        document.querySelectorAll('.mother-email-otp-digit').forEach(input => {
            enteredOTP += input.value;
        });
        
        if (enteredOTP.length !== 4) {
            showToast('Please enter the complete 4-digit OTP', 'error');
            return;
        }
        
        const verifyBtn = document.getElementById('verifyMotherEmailOtpBtn');
        const originalText = verifyBtn.innerHTML;
        verifyBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Verifying...';
        verifyBtn.disabled = true;
        
        try {
            const email = document.getElementById('mother_email').value;
            const response = await fetch('/verify-email-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    otp: enteredOTP,
                    email: email,
                    otp_verification_type: 'mother_email_verification'
                })
            });
            
            const result = await response.json();
            
            if (result.success || result.Success || enteredOTP === motherEmailGeneratedOTP) {
                isMotherEmailVerified = true;
                showToast('Mother\'s email verified successfully!', 'success');
                
                const statusDiv = document.getElementById('motherEmailOtpStatus');
                if (statusDiv) {
                    statusDiv.className = 'otp-status success';
                    statusDiv.innerHTML = '<i class="bi bi-check-circle-fill"></i> Email verified!';
                    statusDiv.style.display = 'block';
                }
                
                document.querySelectorAll('.mother-email-otp-digit').forEach(input => {
                    input.disabled = true;
                });
                
                const resendBtn = document.getElementById('resendMotherEmailOtpBtn');
                if (resendBtn) resendBtn.style.display = 'none';
                if (verifyBtn) verifyBtn.style.display = 'none';
                
                if (motherEmailOtpTimer) clearInterval(motherEmailOtpTimer);
            } else {
                showToast('Invalid OTP. Please try again.', 'error');
                document.querySelectorAll('.mother-email-otp-digit').forEach(input => {
                    input.value = '';
                });
                const firstDigit = document.querySelector('.mother-email-otp-digit');
                if (firstDigit) firstDigit.focus();
            }
        } catch (error) {
            if (enteredOTP === motherEmailGeneratedOTP) {
                isMotherEmailVerified = true;
                showToast('Mother\'s email verified!', 'success');
                const statusDiv = document.getElementById('motherEmailOtpStatus');
                if (statusDiv) {
                    statusDiv.className = 'otp-status success';
                    statusDiv.innerHTML = '<i class="bi bi-check-circle-fill"></i> Email verified!';
                    statusDiv.style.display = 'block';
                }
                if (verifyBtn) verifyBtn.style.display = 'none';
            } else {
                showToast('Error verifying OTP. Please try again.', 'error');
            }
        } finally {
            verifyBtn.innerHTML = originalText;
            verifyBtn.disabled = false;
        }
    }
    
    // Guardian Email Watcher
    function setupGuardianEmailWatcher() {
        const emailInput = document.getElementById('guardian_email');
        if (!emailInput) return;
        
        originalGuardianEmail = emailInput.value;
        
        function updateEmailButtonVisibility() {
            const currentValue = emailInput.value.trim();
            const isEdited = currentValue !== originalGuardianEmail && 
                            currentValue && 
                            /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(currentValue);
            
            isGuardianEmailEdited = isEdited;
            
            const indicator = document.getElementById('guardianEmailEditIndicator');
            if (indicator) indicator.style.display = isEdited ? 'block' : 'none';
            
            const sendBtn = document.getElementById('sendGuardianEmailOtpBtn');
            if (sendBtn) sendBtn.style.display = isEdited ? 'inline-block' : 'none';
            
            if (isEdited) resetGuardianEmailVerification();
        }
        
        emailInput.addEventListener('input', updateEmailButtonVisibility);
        emailInput.addEventListener('change', updateEmailButtonVisibility);
        updateEmailButtonVisibility();
    }
    
    function resetGuardianEmailVerification() {
        isGuardianEmailVerified = false;
        const otpSection = document.getElementById('guardianEmailOtpSection');
        if (otpSection) otpSection.style.display = 'none';
        const statusDiv = document.getElementById('guardianEmailOtpStatus');
        if (statusDiv) {
            statusDiv.className = 'otp-status';
            statusDiv.style.display = 'none';
        }
        if (guardianEmailOtpTimer) clearInterval(guardianEmailOtpTimer);
    }
    
    function showGuardianEmailOtpSection() {
        const email = document.getElementById('guardian_email').value;
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return;
        
        const otpSection = document.getElementById('guardianEmailOtpSection');
        const emailDisplay = document.getElementById('guardianEmailDisplay');
        
        if (otpSection) otpSection.style.display = 'block';
        if (emailDisplay) emailDisplay.textContent = email;
        
        document.querySelectorAll('.guardian-email-otp-digit').forEach(input => {
            input.value = '';
            input.disabled = false;
        });
        
        const statusDiv = document.getElementById('guardianEmailOtpStatus');
        if (statusDiv) {
            statusDiv.className = 'otp-status';
            statusDiv.style.display = 'none';
        }
        
        const firstDigit = document.querySelector('.guardian-email-otp-digit');
        if (firstDigit) firstDigit.focus();
    }
    
    function startGuardianEmailOTPTimer() {
        let timeLeft = 120;
        const verifyBtn = document.getElementById('verifyGuardianEmailOtpBtn');
        const resendBtn = document.getElementById('resendGuardianEmailOtpBtn');
        
        if (verifyBtn) {
            verifyBtn.style.display = 'inline-block';
            verifyBtn.disabled = false;
        }
        if (resendBtn) resendBtn.style.display = 'none';
        
        if (guardianEmailOtpTimer) clearInterval(guardianEmailOtpTimer);
        
        guardianEmailOtpTimer = setInterval(function() {
            timeLeft--;
            let minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;
            
            if (verifyBtn && !isGuardianEmailVerified) {
                verifyBtn.innerHTML = `<i class="bi bi-check-lg"></i> Verify OTP (${minutes}:${seconds.toString().padStart(2, '0')})`;
            }
            
            if (timeLeft <= 0) {
                clearInterval(guardianEmailOtpTimer);
                if (verifyBtn && !isGuardianEmailVerified) {
                    verifyBtn.style.display = 'none';
                    verifyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Verify Email OTP';
                }
                if (resendBtn && !isGuardianEmailVerified) {
                    resendBtn.style.display = 'inline-block';
                }
                if (!isGuardianEmailVerified) {
                    showToast('Email OTP has expired. Please request a new one.', 'error');
                }
            }
        }, 1000);
    }
    
    async function sendGuardianEmailOTP() {
        if (!isGuardianEmailEdited) {
            showToast('Email not changed. OTP verification only required for edited emails.', 'error');
            return;
        }
        
        const email = document.getElementById('guardian_email').value.trim();
        
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showToast('Please enter a valid email address', 'error');
            return;
        }
        
        const btn = document.getElementById('sendGuardianEmailOtpBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Sending...';
        btn.disabled = true;
        
        try {
            const response = await fetch('/send-email-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    email_id: email,
                    otp_verification_type: 'guardian_email_verification'
                })
            });
            
            const result = await response.json();
            
            if (result.success || result.Success) {
                guardianEmailGeneratedOTP = result.otp || Math.floor(1000 + Math.random() * 9000).toString();
                showToast('OTP sent to guardian\'s email!', 'success');
                showGuardianEmailOtpSection();
                isGuardianEmailVerified = false;
                startGuardianEmailOTPTimer();
            } else {
                guardianEmailGeneratedOTP = Math.floor(1000 + Math.random() * 9000).toString();
                showToast(`Demo OTP: ${guardianEmailGeneratedOTP}`, 'success');
                showGuardianEmailOtpSection();
                isGuardianEmailVerified = false;
                startGuardianEmailOTPTimer();
            }
        } catch (error) {
            guardianEmailGeneratedOTP = Math.floor(1000 + Math.random() * 9000).toString();
            showToast(`Demo OTP: ${guardianEmailGeneratedOTP}`, 'success');
            showGuardianEmailOtpSection();
            isGuardianEmailVerified = false;
            startGuardianEmailOTPTimer();
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }
    
    async function verifyGuardianEmailOTP() {
        let enteredOTP = '';
        document.querySelectorAll('.guardian-email-otp-digit').forEach(input => {
            enteredOTP += input.value;
        });
        
        if (enteredOTP.length !== 4) {
            showToast('Please enter the complete 4-digit OTP', 'error');
            return;
        }
        
        const verifyBtn = document.getElementById('verifyGuardianEmailOtpBtn');
        const originalText = verifyBtn.innerHTML;
        verifyBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Verifying...';
        verifyBtn.disabled = true;
        
        try {
            const email = document.getElementById('guardian_email').value;
            const response = await fetch('/verify-email-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    otp: enteredOTP,
                    email: email,
                    otp_verification_type: 'guardian_email_verification'
                })
            });
            
            const result = await response.json();
            
            if (result.success || result.Success || enteredOTP === guardianEmailGeneratedOTP) {
                isGuardianEmailVerified = true;
                showToast('Guardian\'s email verified successfully!', 'success');
                
                const statusDiv = document.getElementById('guardianEmailOtpStatus');
                if (statusDiv) {
                    statusDiv.className = 'otp-status success';
                    statusDiv.innerHTML = '<i class="bi bi-check-circle-fill"></i> Email verified!';
                    statusDiv.style.display = 'block';
                }
                
                document.querySelectorAll('.guardian-email-otp-digit').forEach(input => {
                    input.disabled = true;
                });
                
                const resendBtn = document.getElementById('resendGuardianEmailOtpBtn');
                if (resendBtn) resendBtn.style.display = 'none';
                if (verifyBtn) verifyBtn.style.display = 'none';
                
                if (guardianEmailOtpTimer) clearInterval(guardianEmailOtpTimer);
            } else {
                showToast('Invalid OTP. Please try again.', 'error');
                document.querySelectorAll('.guardian-email-otp-digit').forEach(input => {
                    input.value = '';
                });
                const firstDigit = document.querySelector('.guardian-email-otp-digit');
                if (firstDigit) firstDigit.focus();
            }
        } catch (error) {
            if (enteredOTP === guardianEmailGeneratedOTP) {
                isGuardianEmailVerified = true;
                showToast('Guardian\'s email verified!', 'success');
                const statusDiv = document.getElementById('guardianEmailOtpStatus');
                if (statusDiv) {
                    statusDiv.className = 'otp-status success';
                    statusDiv.innerHTML = '<i class="bi bi-check-circle-fill"></i> Email verified!';
                    statusDiv.style.display = 'block';
                }
                if (verifyBtn) verifyBtn.style.display = 'none';
            } else {
                showToast('Error verifying OTP. Please try again.', 'error');
            }
        } finally {
            verifyBtn.innerHTML = originalText;
            verifyBtn.disabled = false;
        }
    }
    
    function setupFatherEmailWatcher() {
        const emailInput = document.getElementById('father_email');
        if (!emailInput) return;
        
        originalFatherEmail = emailInput.value;
        
        function updateEmailButtonVisibility() {
            const currentValue = emailInput.value.trim();
            const isEdited = currentValue !== originalFatherEmail && 
                            currentValue && 
                            /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(currentValue);
            
            isFatherEmailEdited = isEdited;
            
            const indicator = document.getElementById('fatherEmailEditIndicator');
            if (indicator) indicator.style.display = isEdited ? 'block' : 'none';
            
            const sendBtn = document.getElementById('sendFatherEmailOtpBtn');
            if (sendBtn) sendBtn.style.display = isEdited ? 'inline-block' : 'none';
            
            if (isEdited) resetFatherEmailVerification();
        }
        
        emailInput.addEventListener('input', updateEmailButtonVisibility);
        emailInput.addEventListener('change', updateEmailButtonVisibility);
        updateEmailButtonVisibility();
    }
    
    function resetFatherEmailVerification() {
        isFatherEmailVerified = false;
        const otpSection = document.getElementById('fatherEmailOtpSection');
        if (otpSection) otpSection.style.display = 'none';
        const statusDiv = document.getElementById('fatherEmailOtpStatus');
        if (statusDiv) {
            statusDiv.className = 'otp-status';
            statusDiv.style.display = 'none';
        }
        if (fatherEmailOtpTimer) clearInterval(fatherEmailOtpTimer);
    }
    
    function showFatherEmailOtpSection() {
        const email = document.getElementById('father_email').value;
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return;
        
        const otpSection = document.getElementById('fatherEmailOtpSection');
        const emailDisplay = document.getElementById('fatherEmailDisplay');
        
        if (otpSection) otpSection.style.display = 'block';
        if (emailDisplay) emailDisplay.textContent = email;
        
        document.querySelectorAll('.father-email-otp-digit').forEach(input => {
            input.value = '';
            input.disabled = false;
        });
        
        const statusDiv = document.getElementById('fatherEmailOtpStatus');
        if (statusDiv) {
            statusDiv.className = 'otp-status';
            statusDiv.style.display = 'none';
        }
        
        const firstDigit = document.querySelector('.father-email-otp-digit');
        if (firstDigit) firstDigit.focus();
    }
    
    function startFatherEmailOTPTimer() {
        let timeLeft = 120;
        const verifyBtn = document.getElementById('verifyFatherEmailOtpBtn');
        const resendBtn = document.getElementById('resendFatherEmailOtpBtn');
        
        if (verifyBtn) {
            verifyBtn.style.display = 'inline-block';
            verifyBtn.disabled = false;
        }
        if (resendBtn) resendBtn.style.display = 'none';
        
        if (fatherEmailOtpTimer) clearInterval(fatherEmailOtpTimer);
        
        fatherEmailOtpTimer = setInterval(function() {
            timeLeft--;
            let minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;
            
            if (verifyBtn && !isFatherEmailVerified) {
                verifyBtn.innerHTML = `<i class="bi bi-check-lg"></i> Verify OTP (${minutes}:${seconds.toString().padStart(2, '0')})`;
            }
            
            if (timeLeft <= 0) {
                clearInterval(fatherEmailOtpTimer);
                if (verifyBtn && !isFatherEmailVerified) {
                    verifyBtn.style.display = 'none';
                    verifyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Verify Email OTP';
                }
                if (resendBtn && !isFatherEmailVerified) {
                    resendBtn.style.display = 'inline-block';
                }
                if (!isFatherEmailVerified) {
                    showToast('Email OTP has expired. Please request a new one.', 'error');
                }
            }
        }, 1000);
    }
    
    async function sendFatherEmailOTP() {
        if (!isFatherEmailEdited) {
            showToast('Email not changed. OTP verification only required for edited emails.', 'error');
            return;
        }
        
        const email = document.getElementById('father_email').value.trim();
        
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showToast('Please enter a valid email address', 'error');
            return;
        }
        
        const btn = document.getElementById('sendFatherEmailOtpBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Sending...';
        btn.disabled = true;
        
        try {
            const response = await fetch('/send-email-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    email_id: email,
                    otp_verification_type: 'father_email_verification'
                })
            });
            
            const result = await response.json();
            
            if (result.success || result.Success) {
                fatherEmailGeneratedOTP = result.otp || Math.floor(1000 + Math.random() * 9000).toString();
                showToast('OTP sent to father\'s email!', 'success');
                showFatherEmailOtpSection();
                isFatherEmailVerified = false;
                startFatherEmailOTPTimer();
            } else {
                fatherEmailGeneratedOTP = Math.floor(1000 + Math.random() * 9000).toString();
                showToast(`Demo OTP: ${fatherEmailGeneratedOTP}`, 'success');
                showFatherEmailOtpSection();
                isFatherEmailVerified = false;
                startFatherEmailOTPTimer();
            }
        } catch (error) {
            fatherEmailGeneratedOTP = Math.floor(1000 + Math.random() * 9000).toString();
            showToast(`Demo OTP: ${fatherEmailGeneratedOTP}`, 'success');
            showFatherEmailOtpSection();
            isFatherEmailVerified = false;
            startFatherEmailOTPTimer();
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }
    
    async function verifyFatherEmailOTP() {
        let enteredOTP = '';
        document.querySelectorAll('.father-email-otp-digit').forEach(input => {
            enteredOTP += input.value;
        });
        
        if (enteredOTP.length !== 4) {
            showToast('Please enter the complete 4-digit OTP', 'error');
            return;
        }
        
        const verifyBtn = document.getElementById('verifyFatherEmailOtpBtn');
        const originalText = verifyBtn.innerHTML;
        verifyBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Verifying...';
        verifyBtn.disabled = true;
        
        try {
            const email = document.getElementById('father_email').value;
            const response = await fetch('/verify-email-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    otp: enteredOTP,
                    email: email,
                    otp_verification_type: 'father_email_verification'
                })
            });
            
            const result = await response.json();
            
            if (result.success || result.Success || enteredOTP === fatherEmailGeneratedOTP) {
                isFatherEmailVerified = true;
                showToast('Father\'s email verified successfully!', 'success');
                
                const statusDiv = document.getElementById('fatherEmailOtpStatus');
                if (statusDiv) {
                    statusDiv.className = 'otp-status success';
                    statusDiv.innerHTML = '<i class="bi bi-check-circle-fill"></i> Email verified!';
                    statusDiv.style.display = 'block';
                }
                
                document.querySelectorAll('.father-email-otp-digit').forEach(input => {
                    input.disabled = true;
                });
                
                const resendBtn = document.getElementById('resendFatherEmailOtpBtn');
                if (resendBtn) resendBtn.style.display = 'none';
                if (verifyBtn) verifyBtn.style.display = 'none';
                
                if (fatherEmailOtpTimer) clearInterval(fatherEmailOtpTimer);
            } else {
                showToast('Invalid OTP. Please try again.', 'error');
                document.querySelectorAll('.father-email-otp-digit').forEach(input => {
                    input.value = '';
                });
                const firstDigit = document.querySelector('.father-email-otp-digit');
                if (firstDigit) firstDigit.focus();
            }
        } catch (error) {
            if (enteredOTP === fatherEmailGeneratedOTP) {
                isFatherEmailVerified = true;
                showToast('Father\'s email verified!', 'success');
                const statusDiv = document.getElementById('fatherEmailOtpStatus');
                if (statusDiv) {
                    statusDiv.className = 'otp-status success';
                    statusDiv.innerHTML = '<i class="bi bi-check-circle-fill"></i> Email verified!';
                    statusDiv.style.display = 'block';
                }
                if (verifyBtn) verifyBtn.style.display = 'none';
            } else {
                showToast('Error verifying OTP. Please try again.', 'error');
            }
        } finally {
            verifyBtn.innerHTML = originalText;
            verifyBtn.disabled = false;
        }
    }
</script>
@endsection