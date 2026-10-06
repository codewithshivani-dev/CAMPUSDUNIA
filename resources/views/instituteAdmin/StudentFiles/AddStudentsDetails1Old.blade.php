@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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
        flex-wrap: wrap;
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
        top: 20px;
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
        top: 20px;
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
        width: 44px;
        height: 44px;
        background: #e2e8f0;
        color: #64748b;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        font-weight: 700;
        font-size: 16px;
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

    .timeline-step.locked .timeline-bullet {
        background: #cbd5e1;
        color: #94a3b8;
        cursor: not-allowed;
        opacity: 0.7;
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
        margin-bottom: 25px;
    }

    .card-header-custom {
        background: var(--primary-gradient);
        color: white;
        padding: 20px 30px;
        border: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header-custom h5 {
        margin: 0;
        font-weight: 700;
        font-size: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header-custom h5 i {
        font-size: 24px;
        background: rgba(255, 255, 255, 0.2);
        padding: 8px;
        border-radius: 12px;
    }

    .card-header-custom .btn-light {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        border-radius: 10px;
        padding: 6px 12px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
    }

    .card-header-custom .btn-light:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    .card-body-custom {
        padding: 15px;
    }

    /* Step Cards */
    .step-card {
        border: 2px solid #e2e8f0;
        border-radius: 20px;
        margin-bottom: 25px;
        overflow: hidden;
        transition: all 0.3s;
        background: white;
    }

    .step-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.1);
    }

    .step-card-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 15px 20px;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .step-card-header h6 {
        margin: 0;
        font-weight: 700;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .step-card-header h6 i {
        font-size: 18px;
    }

    .step-card-body {
        padding: 15px;
        background: white;
    }

    /* Sibling Rows */
    .sibling-row {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px !important;
        margin-bottom: 15px;
        transition: all 0.3s;
    }

    .sibling-row:hover {
        border-color: var(--primary-color);
        box-shadow: 0 5px 20px rgba(67, 97, 238, 0.1);
    }

    .sibling-details-card {
        border: 2px solid var(--success-color) !important;
        border-radius: 12px;
        overflow: hidden;
        margin-top: 15px;
    }

    .sibling-details-card .card-header {
        background: var(--success-gradient);
        color: white;
        padding: 10px 15px;
        font-weight: 600;
    }

    .sibling-details-card .card-body {
        padding: 15px;
        background: white;
    }

    .sibling-not-found {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border: 2px solid #fca5a5;
        color: #991b1b;
        border-radius: 12px;
        padding: 12px 15px;
        margin-top: 15px;
    }

    .sibling-spinner {
        padding: 15px;
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

    .text-danger {
        color: #ef4444 !important;
        font-size: 12px;
        margin-left: 3px;
    }

    /* Form Controls */
    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 15px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
        height: auto;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
    }

    .form-control:hover, .form-select:hover {
        border-color: var(--secondary-color);
    }

    .form-control:disabled, .form-control[readonly] {
        background-color: #f1f5f9;
        cursor: not-allowed;
        border-color: #e2e8f0;
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
        /*border-left: none;*/
        /*border-radius: 0 12px 12px 0;*/
    }

    /* Radio Buttons */
    .form-check-inline {
        margin-right: 20px;
    }

    .form-check-input {
        width: 18px;
        height: 18px;
        cursor: pointer;
        border: 2px solid #cbd5e1;
        border-radius: 50%;
        transition: all 0.2s;
        margin-right: 5px !important;
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
    }

    /* Button Group for Parent Selection */
    .btn-group-custom {
        display: flex;
        gap: 20px;
        padding: 10px 0;
    }

    .btn-group-custom .form-check-input {
        position: relative;
        margin-right: 5px !important;
    }

    /* Marital Status Boxes */
    #father_married_mother_box,
    #mother_married_father_box {
        background: linear-gradient(135deg, #f8fafc, #ffffff) !important;
        border: 2px solid var(--success-color) !important;
        border-radius: 16px !important;
        margin-top: 20px;
        padding: 12px;
    }

    #father_married_mother_box h5,
    #mother_married_father_box h5 {
        color: var(--success-color);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
    }

    #father_married_mother_box h5 i,
    #mother_married_father_box h5 i {
        font-size: 20px;
    }

    /* Guardian Select Container */
    .guardianSelectContainer {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        margin: 20px 20px 0px;
    }

    .guardianSelectContainer label {
        font-weight: 700;
        color: var(--primary-color);
    }

    /* Invalid Fields */
    .is-invalid {
        border-color: #ef4444 !important;
    }

    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #ef4444;
    }

    /* Textarea */
    textarea.form-control {
        min-height: 80px;
        resize: vertical;
    }

    /* File Input */
    input[type="file"].form-control {
        padding: 8px 12px;
    }

    .text-muted {
        color: #94a3b8 !important;
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }

    .text-muted a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
    }

    .text-muted a:hover {
        text-decoration: underline;
    }

    /* Other Documents Section */
    .other-doc-group {
        margin-bottom: 15px;
    }

    .other-doc-group .btn-danger {
        background: var(--danger-gradient);
        border: none;
        border-radius: 8px;
        padding: 4px 8px;
        font-size: 12px;
    }

    .other-doc-group .btn-danger:hover {
        transform: scale(1.05);
        box-shadow: 0 3px 10px rgba(239, 68, 68, 0.3);
    }

    /* Add Other Doc Button */
    #addOtherDocBtn {
        background: var(--success-gradient);
        border: none;
        color: white;
        border-radius: 10px;
        padding: 6px 15px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
        margin-left: 20px !important;
    }

    #addOtherDocBtn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
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
        cursor: pointer;
    }

    .form-switch .form-check-input:checked {
        background-color: var(--success-color);
        border-color: var(--success-color);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
    }

    .form-switch .form-check-label {
        font-weight: 600;
        color: var(--primary-color);
        margin-left: 10px;
    }

    .studentBank {
        border-top: 2px solid #e2e8f0;
        margin-top: 20px;
    }

    /* Navigation Buttons */
    .form-navigation {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        padding: 20px 30px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.05);
    }

    .btn-primary {
        background: var(--primary-gradient);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
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

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #94a3b8, #64748b);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-secondary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(100, 116, 139, 0.3);
    }

    .btn-success {
        background: var(--success-gradient);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    }

    .btn-outline-secondary {
        background: transparent;
        border: 2px solid #e2e8f0;
        color: #475569;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-outline-secondary:hover {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-color: #cbd5e1;
        color: #1e293b;
        transform: translateY(-2px);
    }

    /* Success Message */
    #successMessage {
        background: var(--success-gradient);
        color: white;
        border: none;
        border-radius: 16px;
        padding: 20px 30px;
        margin-top: 20px;
        box-shadow: 0 15px 35px rgba(16, 185, 129, 0.3);
    }

    #addAnotherBtn {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        border-radius: 10px;
        padding: 8px 20px;
        font-weight: 600;
        transition: all 0.3s;
    }

    #addAnotherBtn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    /* Disabled Fields */
    .disabled-field {
        opacity: 0.7;
        pointer-events: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            gap: 15px;
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
        }

        .timeline-wrapper {
            padding: 20px;
        }

        .timeline-step span {
            font-size: 11px;
        }

        .card-header-custom {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .step-card-body {
            padding: 15px;
        }

        .btn-group-custom {
            flex-direction: column;
            gap: 10px;
        }

        .form-navigation {
            /*flex-direction: column;*/
            gap: 10px;
        }

        .form-navigation .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

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
                <i class="bi bi-person-plus-fill"></i>
                Add Student Details
            </h1>
        </div>

        <!-- Timeline -->
        <div class="timeline-wrapper">
            <div class="timeline position-relative">
                <div class="timeline-line" id="timelineProgress"></div>
                <div class="timeline-step active" id="step1">
                    <div class="timeline-bullet">01</div>
                    <span>Basic</span>
                </div>
                <div class="timeline-step" id="step2">
                    <div class="timeline-bullet">02</div>
                    <span>Address</span>
                </div>
                <div class="timeline-step" id="step3">
                    <div class="timeline-bullet">03</div>
                    <span>Academic</span>
                </div>
                <div class="timeline-step" id="step4">
                    <div class="timeline-bullet">04</div>
                    <span>Documents</span>
                </div>
                <div class="timeline-step" id="step5">
                    <div class="timeline-bullet">05</div>
                    <span>Bank</span>
                </div>
            </div>
        </div>

        <!-- Form 1 -->
        <form id="form1">
            {{-- ===================== STUDENT DETAILS ===================== --}}
            <div class="form-card">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-person-fill"></i>
                        Student Info
                    </h5>
                </div>
                <div class="card-body-custom">
                    <div class="step-card">
                        <div class="step-card-header">
                            <h6><i class="bi bi-info-circle-fill"></i>Personal Information</h6>
                        </div>
                        <div class="step-card-body">
                            <input type="hidden" name="student_hash_id" id="student_hash_id" value="">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-upc-scan"></i> Registration Number</label>
                                    <div class="input-group">
                                        <input type="text" name="registration_number" id="registration_number"
                                            class="form-control" placeholder="Enter or Generate">
                                        <button class="btn btn-outline-secondary" type="button"
                                            id="btnGenerateReg">Generate</button>
                                    </div>
                                </div>
                                <div class="col-md-4 d-none">
                                    <label class="form-label">Roll No.</label>
                                    <input type="text" class="form-control" name="roll_number" placeholder="21">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-person-fill"></i> First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="first_name" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-person"></i> Middle Name</label>
                                    <input type="text" class="form-control" name="middle_name">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-person"></i> Last Name</label>
                                    <input type="text" class="form-control" name="last_name">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-calendar-fill"></i> Date of Birth <span class="text-danger">*</span></label>
                                    <input type="date" name="dob" id="student_dob" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-123"></i> Age</label>
                                    <input type="text" id="student_age" name="student_age" class="form-control" readonly
                                        placeholder="Auto-calculated">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-gender-ambiguous"></i> Gender <span class="text-danger">*</span></label>
                                    <select name="gender" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-phone-fill"></i> Mobile Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+91</span>
                                        <input type="tel" name="mobile" class="form-control" pattern="[0-9]{10}"
                                            title="Please enter exactly 10 digits" required>
                                    </div>
                                    <div class="invalid-feedback">Please enter a valid 10-digit mobile number</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-envelope-fill"></i> Email ID <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" required>
                                    <div class="invalid-feedback">Please enter a valid email address</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-flag-fill"></i> Nationality</label>
                                    <select name="nationality" class="form-control">
                                        <option value="">Select Nationality</option>
                                        <option value="Indian" {{ old('nationality') == 'Indian' ? 'selected' : '' }}>Indian</option>
                                        <option value="American" {{ old('nationality') == 'American' ? 'selected' : '' }}>American</option>
                                        <option value="British" {{ old('nationality') == 'British' ? 'selected' : '' }}>British</option>
                                        <option value="Canadian" {{ old('nationality') == 'Canadian' ? 'selected' : '' }}>Canadian</option>
                                        <option value="Australian" {{ old('nationality') == 'Australian' ? 'selected' : '' }}>Australian</option>
                                        <option value="Others" {{ old('nationality') == 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                    @error('nationality')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-building-fill"></i> Religion</label>
                                    <select name="religion" class="form-control">
                                        <option value="">Select Religion</option>
                                        <option value="Hindu" {{ old('religion') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                        <option value="Muslim" {{ old('religion') == 'Muslim' ? 'selected' : '' }}>Muslim</option>
                                        <option value="Christian" {{ old('religion') == 'Christian' ? 'selected' : '' }}>Christian</option>
                                        <option value="Sikh" {{ old('religion') == 'Sikh' ? 'selected' : '' }}>Sikh</option>
                                        <option value="Buddhist" {{ old('religion') == 'Buddhist' ? 'selected' : '' }}>Buddhist</option>
                                        <option value="Jain" {{ old('religion') == 'Jain' ? 'selected' : '' }}>Jain</option>
                                        <option value="Jewish" {{ old('religion') == 'Jewish' ? 'selected' : '' }}>Jewish</option>
                                        <option value="Others" {{ old('religion') == 'Others' ? 'selected' : '' }}>Others</option>
                                        <option value="Not Specified" {{ old('religion') == 'Not Specified' ? 'selected' : '' }}>Not Specified</option>
                                    </select>
                                    @error('religion')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-droplet-fill"></i> Blood Group</label>
                                    <select name="blood_group" class="form-control">
                                        <option value="">Select Blood Group</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                        <option value="Unknown">Unknown</option>
                                        <option value="Not Disclosed">Not Disclosed</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-people-fill"></i> Social Category</label>
                                    <select name="category" class="form-control">
                                        <option value="">Select Category</option>
                                        <option value="General">General</option>
                                        <option value="OBC">OBC (Other Backward Class)</option>
                                        <option value="SC">SC (Scheduled Caste)</option>
                                        <option value="ST">ST (Scheduled Tribe)</option>
                                        <option value="Others">Others</option>
                                        <option value="Not Specified">Not Specified</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
            {{-- ===================== SIBLING DETAILS ===================== --}}
            <div class="form-card">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-people-fill"></i>
                        Sibling Details
                    </h5>
                    <button type="button" class="btn btn-light btn-sm" id="addSiblingBtn">
                        <i class="bi bi-plus-lg"></i> Add Sibling
                    </button>
                </div>

                <div class="card-body-custom" id="siblingContainer">
                    <!-- Dynamic sibling rows will appear here -->
                </div>
            </div>

            <template id="siblingTemplate">
                <div class="sibling-row">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-9">
                            <label class="form-label"><i class="bi bi-search"></i> Registration Number or Email</label>
                            <input type="text" 
                                name="siblings[__INDEX__][search_value]" 
                                class="form-control sibling-reg" 
                                placeholder="Enter Reg No or Email">
                           
                            <!-- Hidden fields for form submission -->
                            <input type="hidden" name="siblings[__INDEX__][student_hash_id]" class="sibling-hash-id">
                            <input type="hidden" name="siblings[__INDEX__][registration_number]" class="sibling-reg-number">
                            <input type="hidden" name="siblings[__INDEX__][class_id]" class="sibling-class-id">
                            <input type="hidden" name="siblings[__INDEX__][section_id]" class="sibling-section-id">
                        </div>

                        <div class="col-md-3 d-none">
                            <label class="form-label">Sibling Name</label>
                            <input type="text" name="siblings[__INDEX__][name]" class="form-control sibling-name" readonly>
                        </div>

                        <div class="col-md-3 d-none">
                            <label class="form-label">Date of Birth</label>
                            <input type="text" name="siblings[__INDEX__][dob]" class="form-control sibling-dob" readonly>
                        </div>

                        <div class="col-md-2 d-none">
                            <label class="form-label">Class</label>
                            <input type="text" name="siblings[__INDEX__][class]" class="form-control sibling-class" readonly>
                        </div>

                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger removeSibling">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>

                        <!-- Sibling Details Card - Hidden by default -->
                        <div class="col-12 sibling-details-card" style="display: none;">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-info-circle-fill me-2"></i>Sibling Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label small">Registration No.</label>
                                            <div class="fw-bold sibling-reg-display">-</div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">Email</label>
                                            <div class="fw-bold sibling-email-display">-</div>
                                            <input type="hidden" name="siblings[__INDEX__][email]" class="sibling-email">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">Mobile</label>
                                            <div class="fw-bold sibling-mobile-display">-</div>
                                            <input type="hidden" name="siblings[__INDEX__][mobile]" class="sibling-mobile">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">Class</label>
                                            <div class="fw-bold sibling-class-display">-</div>
                                            <input type="hidden" name="siblings[__INDEX__][class]" class="sibling-class">
                                        </div>
                                        <div class="col-md-3 d-none">
                                            <label class="form-label small">Section</label>
                                            <div class="fw-bold sibling-section-display">-</div>
                                            <input type="hidden" name="siblings[__INDEX__][section]" class="sibling-section">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sibling Not Found Message -->
                        <div class="col-12 sibling-not-found" style="display: none;">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            No student found with the provided information.
                        </div>

                        <!-- Sibling Loading Spinner -->
                        <div class="col-12 sibling-spinner text-center" style="display: none;">
                            <div class="spinner-border text-success spinner-border-sm" role="status">
                                <span class="visually-hidden"></span>
                            </div>
                            <span class="ms-2 small">Searching for sibling...</span>
                        </div>
                    </div>
                </div>
            </template>

            {{-- ===================== PARENT DETAILS ===================== --}}
            <div class="form-card">
                <div class="card-header-custom bg-success">
                    <h5 class="mb-0">
                        <i class="bi bi-people-fill"></i>
                        Parent Details (At Least One Parent Information Required)
                    </h5>
                </div>

                <div class="card-body-custom">
                    <!-- Parent Selection Toggle - Only Father/Mother options -->
                    <div class="step-card">
                        <div class="step-card-header">
                            <h6><i class="bi bi-person-check-fill"></i>Select Parent</h6>
                        </div>
                        <div class="step-card-body">
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="btn-group-custom">
                                        <div class="form-check-inline">
                                            <input type="radio" class="form-check-input" name="parent_selection" id="parent_father"
                                                value="father" autocomplete="off" checked>
                                            <label class="form-check-label" for="parent_father">Father's Details</label>
                                        </div>
                                        <div class="form-check-inline">
                                            <input type="radio" class="form-check-input" name="parent_selection" id="parent_mother"
                                                value="mother" autocomplete="off">
                                            <label class="form-check-label" for="parent_mother">Mother's Details</label>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-2">*Select the parent whose details you want to provide.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Father's Details Section -->
                    <div id="fatherSection">
                        <div class="step-card">
                            <div class="step-card-header">
                                <h6><i class="bi bi-person-standing"></i>Father's Details</h6>
                            </div>
                            <div class="step-card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-person-fill"></i> First Name <span class="text-danger father-required">*</span></label>
                                        <input type="text" name="father_first_name" class="form-control father-field" data-required="true">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-person"></i> Middle Name</label>
                                        <input type="text" name="father_middle_name" class="form-control father-field">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-person"></i> Last Name</label>
                                        <input type="text" name="father_last_name" class="form-control father-field">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-calendar-fill"></i> Date of Birth</label>
                                        <input type="date" name="father_dob" id="father_dob" class="form-control father-field">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-123"></i> Age</label>
                                        <input type="text" id="father_age" name="father_age" class="form-control" readonly placeholder="Auto-calculated">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-envelope-fill"></i> Email ID <span class="text-danger father-required">*</span></label>
                                        <input type="email" name="father_email" class="form-control father-field" data-required="true">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-phone-fill"></i> Phone No. <span class="text-danger father-required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">+91</span>
                                            <input type="tel" name="father_phone" class="form-control father-field" placeholder="Enter 10-digit number" pattern="[0-9]{10}" title="Please enter exactly 10 digits" data-required="true">
                                        </div>
                                        <div class="invalid-feedback">Please enter a valid 10-digit mobile number</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-briefcase-fill"></i>Profession</label>
                                        <select name="father_occupation" class="form-control father-field">
                                            <option value="">-- Select --</option>
                                            <option>Government Employee</option>
                                            <option>Private Sector Employee</option>
                                            <option>Self-Employed</option>
                                            <option>Business Owner</option>
                                            <option>Farmer</option>
                                            <option>Teacher</option>
                                            <option>Doctor</option>
                                            <option>Engineer</option>
                                            <option>Driver</option>
                                            <option>Housewife</option>
                                            <option>Retired</option>
                                            <option>Unemployed</option>
                                            <option>Other</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-heart-fill"></i> Marital Status</label>
                                        <select class="form-control father-field" name="father_marital_status" id="father_marital_status">
                                            <option value="">Select Marital Status</option>
                                            <option value="single">Single</option>
                                            <option value="married" selected>Married</option>
                                            <option value="divorced">Divorced</option>
                                            <option value="widowed">Widowed</option>
                                        </select>
                                    </div>

                                    <!-- Number of Dependents - Shown for all marital statuses -->
                                    <div class="col-md-4 father-dependent-group">
                                        <label class="form-label"><i class="bi bi-people-fill"></i> Number of Dependents</label>
                                        <input type="number" class="form-control father-field" name="father_number_of_dependents" value="0" min="0" max="20" step="1">
                                    </div>

                                    <!-- Spouse Name - Shown for married, widowed, divorced -->
                                    <div class="col-md-4 father-spouse-group" id="father_spouse_group" style="display: none;">
                                        <label class="form-label"><i class="bi bi-person-heart"></i> Spouse Name</label>
                                        <input type="text" class="form-control father-field" name="father_spouse_name" id="father_spouse_name">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-cash-stack"></i> Annual Income</label>
                                        <input type="number" name="father_income" class="form-control father-field" placeholder="In INR">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-flag-fill"></i> Nationality</label>
                                        <select name="father_nationality" class="form-control father-field">
                                            <option value="">Select Nationality</option>
                                            <option value="Indian">Indian</option>
                                            <option value="American">American</option>
                                            <option value="British">British</option>
                                            <option value="Canadian">Canadian</option>
                                            <option value="Australian">Australian</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-building-fill"></i> Religion</label>
                                        <select name="father_religion" class="form-control father-field">
                                            <option value="">Select Religion</option>
                                            <option value="Hindu">Hindu</option>
                                            <option value="Muslim">Muslim</option>
                                            <option value="Christian">Christian</option>
                                            <option value="Sikh">Sikh</option>
                                            <option value="Buddhist">Buddhist</option>
                                            <option value="Jain">Jain</option>
                                            <option value="Jewish">Jewish</option>
                                            <option value="Others">Others</option>
                                            <option value="Not Specified">Not Specified</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-droplet-fill"></i> Blood Group</label>
                                        <select name="father_blood_group" class="form-control father-field">
                                            <option value="">Select Blood Group</option>
                                            <option value="A+">A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                            <option value="O+">O+</option>
                                            <option value="O-">O-</option>
                                            <option value="Unknown">Unknown</option>
                                            <option value="Not Disclosed">Not Disclosed</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-people-fill"></i> Social Category</label>
                                        <select name="father_category" class="form-control father-field">
                                            <option value="">Select Category</option>
                                            <option value="General">General</option>
                                            <option value="OBC">OBC (Other Backward Class)</option>
                                            <option value="SC">SC (Scheduled Caste)</option>
                                            <option value="ST">ST (Scheduled Tribe)</option>
                                            <option value="Others">Others</option>
                                            <option value="Not Specified">Not Specified</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Mother's Details Box (Appears when Father is Married) -->
                                <div id="father_married_mother_box" style="display: none;">
                                    <h5><i class="bi bi-person-standing-dress"></i> Mother's Details (Optional)</h5>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label"><i class="bi bi-person-fill"></i> First Name</label>
                                            <input type="text" name="father_married_mother_first_name" class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label"><i class="bi bi-person"></i> Middle Name</label>
                                            <input type="text" name="father_married_mother_middle_name" class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label"><i class="bi bi-person"></i> Last Name</label>
                                            <input type="text" name="father_married_mother_last_name" class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label"><i class="bi bi-calendar-fill"></i> Date of Birth</label>
                                            <input type="date" name="father_married_mother_dob" id="father_married_mother_dob" class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label"><i class="bi bi-123"></i> Age</label>
                                            <input type="text" id="father_married_mother_age" name="father_married_mother_age" class="form-control" readonly placeholder="Auto-calculated">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label"><i class="bi bi-envelope-fill"></i> Email ID</label>
                                            <input type="email" name="father_married_mother_email" class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label"><i class="bi bi-phone-fill"></i> Phone Number</label>
                                            <div class="input-group">
                                                <span class="input-group-text">+91</span>
                                                <input type="tel" name="father_married_mother_phone" class="form-control" placeholder="Enter 10-digit number" pattern="[0-9]{10}" title="Please enter exactly 10 digits">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label"><i class="bi bi-briefcase-fill"></i>Profession</label>
                                            <select name="father_married_mother_occupation" class="form-control">
                                                <option value="">-- Select --</option>
                                                <option>Government Employee</option>
                                                <option>Private Sector Employee</option>
                                                <option>Self-Employed</option>
                                                <option>Business Owner</option>
                                                <option>Farmer</option>
                                                <option>Teacher</option>
                                                <option>Doctor</option>
                                                <option>Engineer</option>
                                                <option>Driver</option>
                                                <option>Housewife</option>
                                                <option>Retired</option>
                                                <option>Unemployed</option>
                                                <option>Other</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label"><i class="bi bi-cash-stack"></i> Annual Income</label>
                                            <input type="number" name="father_married_mother_income" class="form-control" placeholder="In INR">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label"><i class="bi bi-droplet-fill"></i> Blood Group</label>
                                            <select name="father_married_mother_blood_group" class="form-control">
                                                <option value="">Select Blood Group</option>
                                                <option value="A+">A+</option>
                                                <option value="A-">A-</option>
                                                <option value="B+">B+</option>
                                                <option value="B-">B-</option>
                                                <option value="AB+">AB+</option>
                                                <option value="AB-">AB-</option>
                                                <option value="O+">O+</option>
                                                <option value="O-">O-</option>
                                                <option value="Unknown">Unknown</option>
                                                <option value="Not Disclosed">Not Disclosed</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mother's Details Section -->
                    <div id="motherSection" style="display: none;">
                        <div class="step-card">
                            <div class="step-card-header">
                                <h6><i class="bi bi-person-standing-dress"></i>Mother's Details</h6>
                            </div>
                            <div class="step-card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-person-fill"></i> First Name <span class="text-danger mother-required">*</span></label>
                                        <input type="text" name="mother_first_name" class="form-control mother-field" data-required="true">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-person"></i> Middle Name</label>
                                        <input type="text" name="mother_middle_name" class="form-control mother-field">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-person"></i> Last Name</label>
                                        <input type="text" name="mother_last_name" class="form-control mother-field">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-calendar-fill"></i> Date of Birth</label>
                                        <input type="date" name="mother_dob" id="mother_dob" class="form-control mother-field">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-123"></i> Age</label>
                                        <input type="text" id="mother_age" name="mother_age" class="form-control" readonly placeholder="Auto-calculated">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-envelope-fill"></i> Email ID <span class="text-danger mother-required">*</span></label>
                                        <input type="email" name="mother_email" class="form-control mother-field" data-required="true">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-phone-fill"></i> Phone Number <span class="text-danger mother-required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">+91</span>
                                            <input type="tel" name="mother_phone" class="form-control mother-field" placeholder="Enter 10-digit number" pattern="[0-9]{10}" title="Please enter exactly 10 digits" data-required="true">
                                        </div>
                                        <div class="invalid-feedback">Please enter a valid 10-digit mobile number</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-heart-fill"></i> Marital Status</label>
                                        <select class="form-control mother-field" name="mother_marital_status" id="mother_marital_status">
                                            <option value="">Select Marital Status</option>
                                            <option value="single">Single</option>
                                            <option value="married" selected>Married</option>
                                            <option value="divorced">Divorced</option>
                                            <option value="widowed">Widowed</option>
                                        </select>
                                    </div>

                                    <!-- Number of Dependents - Shown for all marital statuses -->
                                    <div class="col-md-4 mother-dependent-group">
                                        <label class="form-label"><i class="bi bi-people-fill"></i> Number of Dependents</label>
                                        <input type="number" class="form-control mother-field" name="mother_number_of_dependents" value="0" min="0" max="20" step="1">
                                    </div>

                                    <!-- Spouse Name - Shown for married, widowed, divorced -->
                                    <div class="col-md-4 mother-spouse-group" id="mother_spouse_group" style="display: none;">
                                        <label class="form-label"><i class="bi bi-person-heart"></i> Spouse Name</label>
                                        <input type="text" class="form-control mother-field" name="mother_spouse_name" id="mother_spouse_name">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-briefcase-fill"></i>Profession</label>
                                        <select name="mother_occupation" class="form-control mother-field">
                                            <option value="">-- Select --</option>
                                            <option>Government Employee</option>
                                            <option>Private Sector Employee</option>
                                            <option>Self-Employed</option>
                                            <option>Business Owner</option>
                                            <option>Farmer</option>
                                            <option>Teacher</option>
                                            <option>Doctor</option>
                                            <option>Engineer</option>
                                            <option>Driver</option>
                                            <option>Housewife</option>
                                            <option>Retired</option>
                                            <option>Unemployed</option>
                                            <option>Other</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-cash-stack"></i> Annual Income</label>
                                        <input type="number" name="mother_income" class="form-control mother-field" placeholder="In INR">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-flag-fill"></i> Nationality</label>
                                        <select name="mother_nationality" class="form-control mother-field">
                                            <option value="">Select Nationality</option>
                                            <option value="Indian">Indian</option>
                                            <option value="American">American</option>
                                            <option value="British">British</option>
                                            <option value="Canadian">Canadian</option>
                                            <option value="Australian">Australian</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-building-fill"></i> Religion</label>
                                        <select name="mother_religion" class="form-control mother-field">
                                            <option value="">Select Religion</option>
                                            <option value="Hindu">Hindu</option>
                                            <option value="Muslim">Muslim</option>
                                            <option value="Christian">Christian</option>
                                            <option value="Sikh">Sikh</option>
                                            <option value="Buddhist">Buddhist</option>
                                            <option value="Jain">Jain</option>
                                            <option value="Jewish">Jewish</option>
                                            <option value="Others">Others</option>
                                            <option value="Not Specified">Not Specified</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-droplet-fill"></i> Blood Group</label>
                                        <select name="mother_blood_group" class="form-control mother-field">
                                            <option value="">Select Blood Group</option>
                                            <option value="A+">A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                            <option value="O+">O+</option>
                                            <option value="O-">O-</option>
                                            <option value="Unknown">Unknown</option>
                                            <option value="Not Disclosed">Not Disclosed</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label"><i class="bi bi-people-fill"></i> Social Category</label>
                                        <select name="mother_category" class="form-control mother-field">
                                            <option value="">Select Category</option>
                                            <option value="General">General</option>
                                            <option value="OBC">OBC (Other Backward Class)</option>
                                            <option value="SC">SC (Scheduled Caste)</option>
                                            <option value="ST">ST (Scheduled Tribe)</option>
                                            <option value="Others">Others</option>
                                            <option value="Not Specified">Not Specified</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Father's Details Box (Appears when Mother is Married) -->
                                <div id="mother_married_father_box" style="display: none;">
                                    <h5><i class="bi bi-person-standing"></i> Father's Details (Optional)</h5>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label"><i class="bi bi-person-fill"></i> First Name</label>
                                            <input type="text" name="mother_married_father_first_name" class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label"><i class="bi bi-person"></i> Middle Name</label>
                                            <input type="text" name="mother_married_father_middle_name" class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label"><i class="bi bi-person"></i> Last Name</label>
                                            <input type="text" name="mother_married_father_last_name" class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label"><i class="bi bi-calendar-fill"></i> Date of Birth</label>
                                            <input type="date" name="mother_married_father_dob" id="mother_married_father_dob" class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label"><i class="bi bi-123"></i> Age</label>
                                            <input type="text" id="mother_married_father_age" name="mother_married_father_age" class="form-control" readonly placeholder="Auto-calculated">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label"><i class="bi bi-envelope-fill"></i> Email ID</label>
                                            <input type="email" name="mother_married_father_email" class="form-control">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label"><i class="bi bi-phone-fill"></i> Phone Number</label>
                                            <div class="input-group">
                                                <span class="input-group-text">+91</span>
                                                <input type="tel" name="mother_married_father_phone" class="form-control" placeholder="Enter 10-digit number" pattern="[0-9]{10}" title="Please enter exactly 10 digits">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label"><i class="bi bi-briefcase-fill"></i>Profession</label>
                                            <select name="mother_married_father_occupation" class="form-control">
                                                <option value="">-- Select --</option>
                                                <option>Government Employee</option>
                                                <option>Private Sector Employee</option>
                                                <option>Self-Employed</option>
                                                <option>Business Owner</option>
                                                <option>Farmer</option>
                                                <option>Teacher</option>
                                                <option>Doctor</option>
                                                <option>Engineer</option>
                                                <option>Driver</option>
                                                <option>Housewife</option>
                                                <option>Retired</option>
                                                <option>Unemployed</option>
                                                <option>Other</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label"><i class="bi bi-cash-stack"></i> Annual Income</label>
                                            <input type="number" name="mother_married_father_income" class="form-control" placeholder="In INR">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label"><i class="bi bi-droplet-fill"></i> Blood Group</label>
                                            <select name="mother_married_father_blood_group" class="form-control">
                                                <option value="">Select Blood Group</option>
                                                <option value="A+">A+</option>
                                                <option value="A-">A-</option>
                                                <option value="B+">B+</option>
                                                <option value="B-">B-</option>
                                                <option value="AB+">AB+</option>
                                                <option value="AB-">AB-</option>
                                                <option value="O+">O+</option>
                                                <option value="O-">O-</option>
                                                <option value="Unknown">Unknown</option>
                                                <option value="Not Disclosed">Not Disclosed</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== GUARDIAN DETAILS ===================== --}}
            <div class="form-card">
                <div class="card-header-custom bg-success">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-fill-check"></i>
                        Guardian Details
                    </h5>
                </div>

                <div class="guardianSelectContainer">
                    <label class="form-label d-block"><b>Select Guardian</b></label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="guardian_type" id="guardian_father"
                            value="father" checked>
                        <label class="form-check-label" for="guardian_father">Father</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="guardian_type" id="guardian_mother"
                            value="mother">
                        <label class="form-check-label" for="guardian_mother">Mother</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="guardian_type" id="guardian_other"
                            value="other">
                        <label class="form-check-label" for="guardian_other">Different / Other Person</label>
                    </div>
                </div>

                <div class="card-body-custom">
                    <div class="step-card guardianContainer d-none">
                        <div class="step-card-header">
                            <h6><i class="bi bi-person-badge-fill"></i>Guardian Information</h6>
                        </div>
                        <div class="step-card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-people-fill"></i> Relation with Student</label>
                                    <input type="text" name="guardian_relation" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-person-fill"></i> First Name</label>
                                    <input type="text" name="guardian_first_name" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-person"></i> Middle Name</label>
                                    <input type="text" name="guardian_middle_name" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-person"></i> Last Name</label>
                                    <input type="text" name="guardian_last_name" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-gender-ambiguous"></i> Gender</label>
                                    <select name="guardian_gender" class="form-control">
                                        <option value="">Select</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-calendar-fill"></i> Date of Birth</label>
                                    <input type="date" name="guardian_dob" id="guardian_dob" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-123"></i> Age</label>
                                    <input type="text" id="guardian_age" name="guardian_age" class="form-control" readonly placeholder="Auto-calculated">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-envelope-fill"></i> Email</label>
                                    <input type="email" name="guardian_email" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-phone-fill"></i> Phone</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+91</span>
                                        <input type="tel" name="guardian_phone" class="form-control"
                                            placeholder="Enter 10-digit number" pattern="[0-9]{10}"
                                            title="Please enter exactly 10 digits">
                                    </div>
                                    <div class="invalid-feedback">Please enter a valid 10-digit mobile number</div>
                                </div>

                                 <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-people-fill"></i> No. of Dependents</label>
                                    <input type="number" name="guardian_number_of_dependents" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-telephone-fill"></i> Alternate Phone</label>
                                    <input type="tel" name="guardian_alternate_phone_number" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-briefcase-fill"></i>Profession</label>
                                    <select name="guardian_occupation" class="form-control">
                                        <option value="">-- Select --</option>
                                        <option>Government Employee</option>
                                        <option>Private Sector Employee</option>
                                        <option>Self-Employed</option>
                                        <option>Business Owner</option>
                                        <option>Farmer</option>
                                        <option>Teacher</option>
                                        <option>Doctor</option>
                                        <option>Engineer</option>
                                        <option>Driver</option>
                                        <option>Housewife</option>
                                        <option>Retired</option>
                                        <option>Unemployed</option>
                                        <option>Other</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-cash-stack"></i> Annual Income</label>
                                    <input type="number" name="guardian_income" class="form-control" placeholder="In INR">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-flag-fill"></i> Nationality</label>
                                    <select name="guardian_nationality" class="form-control">
                                        <option value="">Select Nationality</option>
                                        <option value="Indian" {{ old('guardian_nationality') == 'Indian' ? 'selected' : '' }}>Indian</option>
                                        <option value="American" {{ old('guardian_nationality') == 'American' ? 'selected' : '' }}>American</option>
                                        <option value="British" {{ old('guardian_nationality') == 'British' ? 'selected' : '' }}>British</option>
                                        <option value="Canadian" {{ old('guardian_nationality') == 'Canadian' ? 'selected' : '' }}>Canadian</option>
                                        <option value="Australian" {{ old('guardian_nationality') == 'Australian' ? 'selected' : '' }}>Australian</option>
                                        <option value="Others" {{ old('guardian_nationality') == 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                    @error('guardian_nationality')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-building-fill"></i> Religion</label>
                                    <select name="guardian_religion" class="form-control">
                                        <option value="">Select Religion</option>
                                        <option value="Hindu" {{ old('guardian_religion') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                        <option value="Muslim" {{ old('guardian_religion') == 'Muslim' ? 'selected' : '' }}>Muslim</option>
                                        <option value="Christian" {{ old('guardian_religion') == 'Christian' ? 'selected' : '' }}>Christian</option>
                                        <option value="Sikh" {{ old('guardian_religion') == 'Sikh' ? 'selected' : '' }}>Sikh</option>
                                        <option value="Buddhist" {{ old('guardian_religion') == 'Buddhist' ? 'selected' : '' }}>Buddhist</option>
                                        <option value="Jain" {{ old('guardian_religion') == 'Jain' ? 'selected' : '' }}>Jain</option>
                                        <option value="Jewish" {{ old('guardian_religion') == 'Jewish' ? 'selected' : '' }}>Jewish</option>
                                        <option value="Others" {{ old('guardian_religion') == 'Others' ? 'selected' : '' }}>Others</option>
                                        <option value="Not Specified" {{ old('guardian_religion') == 'Not Specified' ? 'selected' : '' }}>Not Specified</option>
                                    </select>
                                    @error('guardian_religion')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-droplet-fill"></i> Blood Group</label>
                                    <select name="guardian_blood_group" class="form-control">
                                        <option value="">Select Blood Group</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                        <option value="Unknown">Unknown</option>
                                        <option value="Not Disclosed">Not Disclosed</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label"><i class="bi bi-people-fill"></i> Social Category</label>
                                    <select name="guardian_category" class="form-control">
                                        <option value="">Select Category</option>
                                        <option value="General">General</option>
                                        <option value="OBC">OBC (Other Backward Class)</option>
                                        <option value="SC">SC (Scheduled Caste)</option>
                                        <option value="ST">ST (Scheduled Tribe)</option>
                                        <option value="Others">Others</option>
                                        <option value="Not Specified">Not Specified</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-navigation">
                <span></span>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-arrow-right"></i> Next
                </button>
            </div>
        </form>

        <!-- Form 2 -->
        <form id="form2" style="display:none;">
            {{-- ===================== STUDENT ADDRESSES ===================== --}}
            <div class="form-card">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-geo-alt-fill"></i>
                        Student Address
                    </h5>
                </div>
                <div class="card-body-custom">
                    <div class="step-card">
                        <div class="step-card-header">
                            <h6><i class="bi bi-house-door-fill"></i>Permanent Address</h6>
                        </div>
                        <div class="step-card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                    <input type="text" name="student_perm_address_line1" class="form-control"
                                        placeholder="Address Line 1" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Address Line 2</label>
                                    <input type="text" name="student_perm_address_line2" class="form-control"
                                        placeholder="Address Line 2">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text" name="student_perm_city" class="form-control"
                                        placeholder="City" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">State <span class="text-danger">*</span></label>
                                    <input type="text" name="student_perm_state" class="form-control"
                                        placeholder="State" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Pincode <span class="text-danger">*</span></label>
                                    <input type="text" name="student_perm_pincode" class="form-control"
                                        placeholder="Pincode" pattern="[0-9]{6}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-card">
                        <div class="step-card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <h6><i class="bi bi-house-heart-fill"></i>Communication Address</h6>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="copyStudentAddress">
                                    <label class="form-check-label mt-0" for="copyStudentAddress">Same as Permanent</label>
                                </div>
                            </div>
                        </div>
                        <div class="step-card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <input type="text" name="student_comm_address_line1" class="form-control"
                                        placeholder="Address Line 1">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="text" name="student_comm_address_line2" class="form-control"
                                        placeholder="Address Line 2">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" name="student_comm_city" class="form-control"
                                        placeholder="City">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" name="student_comm_state" class="form-control"
                                        placeholder="State">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" name="student_comm_pincode" class="form-control"
                                        placeholder="Pincode">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="copyStudentToParent">
                        <label class="form-check-label fw-semibold mt-0" for="copyStudentToParent">
                            <i class="bi bi-files me-1"></i> Use Same Address as Student
                        </label>
                    </div>
                </div>
            </div>

            {{-- ===================== FATHERS ADDRESSES ===================== --}}
            <div class="form-card">
                <div class="card-header-custom bg-success">
                    <h5 class="mb-0">
                        <i class="bi bi-person-standing"></i>
                        Father's Address
                    </h5>
                </div>
                <div class="card-body-custom">
                    <div class="step-card">
                        <div class="step-card-header">
                            <h6><i class="bi bi-house-door-fill"></i>Permanent Address</h6>
                        </div>
                        <div class="step-card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                    <input type="text" name="parent_perm_address_line1" class="form-control"
                                        placeholder="Address Line 1" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Address Line 2</label>
                                    <input type="text" name="parent_perm_address_line2" class="form-control"
                                        placeholder="Address Line 2">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" name="parent_perm_city" class="form-control"
                                        placeholder="City">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">State</label>
                                    <input type="text" name="parent_perm_state" class="form-control"
                                        placeholder="State">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Pincode <span class="text-danger">*</span></label>
                                    <input type="text" name="parent_perm_pincode" class="form-control"
                                        placeholder="Pincode" pattern="[0-9]{6}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-card">
                        <div class="step-card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <h6><i class="bi bi-house-heart-fill"></i>Communication Address</h6>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="copyParentAddress">
                                    <label class="form-check-label mt-0" for="copyParentAddress">Same as Permanent</label>
                                </div>
                            </div>
                        </div>
                        <div class="step-card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <input type="text" name="parent_comm_address_line1" class="form-control"
                                        placeholder="Address Line 1">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="text" name="parent_comm_address_line2" class="form-control"
                                        placeholder="Address Line 2">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" name="parent_comm_city" class="form-control"
                                        placeholder="City">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" name="parent_comm_state" class="form-control"
                                        placeholder="State">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" name="parent_comm_pincode" class="form-control"
                                        placeholder="Pincode">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== GUARDIAN ADDRESSES ===================== --}}
            <div class="form-card guardian-address-container d-none">
                <div class="card-header-custom bg-success">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-fill-check"></i>
                        Guardian Address
                    </h5>
                </div>
                <div class="card-body-custom">
                    <div class="step-card">
                        <div class="step-card-header">
                            <h6><i class="bi bi-house-door-fill"></i>Permanent Address</h6>
                        </div>
                        <div class="step-card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Address Line 1</label>
                                    <input type="text" name="guardian_perm_address_line1" class="form-control"
                                        placeholder="Address Line 1">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Address Line 2</label>
                                    <input type="text" name="guardian_perm_address_line2" class="form-control"
                                        placeholder="Address Line 2">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" name="guardian_perm_city" class="form-control"
                                        placeholder="City">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">State</label>
                                    <input type="text" name="guardian_perm_state" class="form-control"
                                        placeholder="State">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Pincode</label>
                                    <input type="text" name="guardian_perm_pincode" class="form-control"
                                        placeholder="Pincode" pattern="[0-9]{6}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-card">
                        <div class="step-card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <h6><i class="bi bi-house-heart-fill"></i>Communication Address</h6>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="copyParentAddress">
                                    <label class="form-check-label mt-0" for="copyParentAddress">Same as Permanent</label>
                                </div>
                            </div>
                        </div>
                        <div class="step-card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <input type="text" name="guardian_comm_address_line1" class="form-control"
                                        placeholder="Address Line 1">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="text" name="guardian_comm_address_line2" class="form-control"
                                        placeholder="Address Line 2">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" name="guardian_comm_city" class="form-control"
                                        placeholder="City">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" name="guardian_comm_state" class="form-control"
                                        placeholder="State">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input type="text" name="guardian_comm_pincode" class="form-control"
                                        placeholder="Pincode">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-navigation">
                <button type="button" class="btn btn-outline-secondary" id="backToForm1">
                    <i class="bi bi-arrow-left"></i> Back
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-arrow-right"></i> Next
                </button>
            </div>
        </form>

        <!-- Form 3 -->
        <form id="form3" style="display:none;">
            <div class="form-card">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-building-fill"></i>
                        Institute Details
                    </h5>
                </div>
                <div class="card-body-custom">
                    <div class="step-card">
                        <div class="step-card-header">
                            <h6><i class="bi bi-info-circle-fill"></i>Academic Information</h6>
                        </div>
                        <div class="step-card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Department Category</label>
                                    <select name="department_category_id" id="department_category_id" class="form-control"
                                        required>
                                        <option value="">-- Select Department Category --</option>
                                        @foreach($departmentCategories as $category)
                                        <option value="{{ $category->department_category_id }}">
                                            {{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Department</label>
                                    <select name="department_id" id="department_id" class="form-control" required disabled>
                                        <option value="">-- Select Department --</option>
                                    </select>
                                    <input type="hidden" name="department" id="department">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{$courseLabel}} Type</label>
                                    <select name="course_type" id="course_type" class="form-control" required disabled>
                                        <option value="">-- Select {{$courseLabel}} Type --</option>
                                    </select>
                                    <input type="hidden" name="course_type_id" id="course_type_id">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{$courseLabel}} Sub Type</label>
                                    <select name="course_subtype_id" id="course_subtype_id" class="form-control" required
                                        disabled>
                                        <option value="">-- Select Sub Type --</option>
                                    </select>
                                    <input type="hidden" name="course_subtype" id="course_subtype">
                                </div>

                                <input type="hidden" name="course_detail_id" id="course_detail_id">

                                <div class="col-md-6">
                                    <label class="form-label">Batch</label>
                                    <input type="text" name="batch_name" id="batch_name" class="form-control" readonly>
                                    <input type="hidden" name="batch_id" id="batch_id">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Academic Year</label>
                                    <select name="academic_year_id" id="academic_year_id" class="form-control" required>
                                        <option value="">-- Select Academic Year --</option>
                                    </select>
                                    <input type="hidden" name="academic_year_name" id="academic_year_name">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{$courseLabel}} Mode</label>
                                    <select name="mode_of_course" id="mode_of_course" class="form-control">
                                        <option value="">-- Select Course Mode --</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Mode Type</label>
                                    <select name="mode_type" id="mode_type" class="form-control">
                                        <option value="">-- Select Mode Type --</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Semester/Terms</label>
                                    <select name="semester_id" id="semester_id" class="form-control">
                                        <option value="">-- Select Semester--</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Section</label>
                                    <select name="section_id" id="section_id" class="form-control">
                                        <option value="">-- Select Section --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-navigation">
                <button type="button" class="btn btn-outline-secondary" id="back2">
                    <i class="bi bi-arrow-left"></i> Back
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-arrow-right"></i> Next
                </button>
            </div>
        </form>

        <!-- Form 4 -->
        <form id="form4" style="display:none;" enctype="multipart/form-data">
            <!-- STUDENT DOCUMENTS -->
            <div class="form-card">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-person-fill"></i>
                        Student Documents
                    </h5>
                </div>
                <div class="card-body-custom" id="student-documents-container">
                    <div class="step-card">
                        <div class="step-card-header">
                            <h6><i class="bi bi-person-badge-fill"></i>Identity Documents</h6>
                        </div>
                        <div class="step-card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-qr-code"></i> Aadhaar Number</label>
                                    <input type="text" name="student_aadhaar_number" class="form-control" maxlength="12"
                                        pattern="\d{12}" placeholder="Enter 12-digit Aadhaar number">
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
                                        placeholder="ABCDE1234F">
                                    <div class="invalid-feedback">PAN must be in format ABCDE1234F</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-file-earmark-fill"></i> Upload PAN Card</label>
                                    <input type="file" name="student_pan_file" class="form-control"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-camera-fill"></i> Student Photo <span class="text-danger">*</span></label>
                                    <input type="file" name="student_photo" class="form-control" accept="image/*">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-file-earmark-fill"></i> Upload Student ID Card <small class="text-muted">(optional)</small></label>
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

                                <!-- Academic Documents will be injected here by JS -->
                                <div id="academic-documents-container" class="row g-3"></div>

                                <!-- Other Documents Section -->
                                <div class="col-12 d-flex align-items-center">
                                    <label class="form-label mb-0"><i class="bi bi-files-fill"></i> Other Documents</label>
                                    <button type="button" class="btn btn-sm" id="addOtherDocBtn">
                                        <i class="bi bi-plus-lg"></i> Add
                                    </button>
                                </div>

                                <div id="other-documents-wrapper" class="row g-3">
                                    <!-- Initial Other Document Field -->
                                    <div class="col-md-6 other-doc-group">
                                        <label class="form-label">Other Document Name</label>
                                        <input type="text" name="student_other_doc_label_1" class="form-control"
                                            placeholder="Enter document name">
                                    </div>
                                    <div class="col-md-6 other-doc-group d-flex align-items-end">
                                        <div class="w-100">
                                            <label class="form-label">Upload Other Document <small class="text-muted">(optional)</small></label>
                                            <input type="file" name="student_other_doc_file_1" class="form-control"
                                                accept=".pdf,.jpg,.jpeg,.png">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PARENT DOCUMENTS -->
            <div class="form-card">
                <div class="card-header-custom bg-success">
                    <h5 class="mb-0">
                        <i class="bi bi-people-fill"></i>
                        Parent Documents
                    </h5>
                </div>
                <div class="card-body-custom">
                    <div class="step-card">
                        <div class="step-card-header">
                            <h6><i class="bi bi-file-earmark-fill"></i>Parent Documents</h6>
                        </div>
                        <div class="step-card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-qr-code"></i> Aadhaar Number</label>
                                    <input type="text" name="parent_aadhaar_number" class="form-control" maxlength="12"
                                        pattern="\d{12}" placeholder="Enter 12-digit Aadhaar number">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-file-earmark-fill"></i> Upload Aadhaar Card</label>
                                    <input type="file" name="parent_aadhaar_file" class="form-control"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-credit-card-fill"></i> PAN Number</label>
                                    <input type="text" name="parent_pan_number" class="form-control" maxlength="10"
                                        placeholder="ABCDE1234F">
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
                    </div>

                    <!-- GUARDIAN DOCUMENTS -->
                    <div class="otherDocumentsContainer d-none">
                        <div class="step-card">
                            <div class="step-card-header">
                                <h6><i class="bi bi-shield-fill-check"></i>Guardian Documents</h6>
                            </div>
                            <div class="step-card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="bi bi-qr-code"></i> Aadhaar Number</label>
                                        <input type="text" name="guardian_aadhaar_number" class="form-control"
                                            maxlength="12" pattern="\d{12}" placeholder="Enter 12-digit Aadhaar number">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label"><i class="bi bi-file-earmark-fill"></i> Upload Aadhaar Card</label>
                                        <input type="file" name="guardian_aadhaar_file" class="form-control"
                                            accept=".pdf,.jpg,.jpeg,.png">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label"><i class="bi bi-credit-card-fill"></i> PAN Number</label>
                                        <input type="text" name="guardian_pan_number" class="form-control" maxlength="10"
                                            placeholder="ABCDE1234F">
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
            </div>

            <div class="form-navigation">
                <button type="button" class="btn btn-outline-secondary" id="back3">
                    <i class="bi bi-arrow-left"></i> Back
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-arrow-right"></i> Next
                </button>
            </div>
        </form>

        <!-- Form 5 -->
        <form id="form5" style="display:none;">
            <div class="form-card">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-bank2"></i>
                        Parent Bank Details
                    </h5>
                </div>
                <div class="card-body-custom">
                    <div class="step-card">
                        <div class="step-card-header">
                            <h6><i class="bi bi-bank2"></i>Bank Information</h6>
                        </div>
                        <div class="step-card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-person-fill"></i> Beneficiary Name</label>
                                    <input type="text" name="benificiary_name" class="form-control" />
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-credit-card-fill"></i> Bank Account Number</label>
                                    <input type="text" name="bank_account_number" pattern="[0-9]{9,18}"
                                        class="form-control" />
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-building-fill"></i> Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control" />
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-upc-scan"></i> IFSC Code</label>
                                    <input type="text" name="ifsc_code" class="form-control"
                                        pattern="[A-Z]{4}0[A-Z0-9]{6}" title="IFSC format: ABCD0123456" />
                                    <div class="invalid-feedback">IFSC must be in format ABCD0123456</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-list-ul"></i> Account Type</label>
                                    <select name="account_type" class="form-control">
                                        <option value="">Select Account Type</option>
                                        <option value="saving">Savings Account</option>
                                        <option value="current">Current Account</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><i class="bi bi-file-earmark-fill"></i> Upload Cancelled Cheque</label>
                                    <input name="upload_cancelled_cheque" type="file" class="form-control" />
                                </div>
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
                        <div class="step-card">
                            <div class="step-card-header">
                                <h6><i class="bi bi-person-fill"></i>Student Bank Details</h6>
                            </div>
                            <div class="step-card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="bi bi-person-fill"></i> Beneficiary Name</label>
                                        <input type="text" name="student_benificiary_name" class="form-control" />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label"><i class="bi bi-credit-card-fill"></i> Bank Account Number</label>
                                        <input type="text" name="student_bank_account_number" pattern="[0-9]{9,18}"
                                            class="form-control" />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label"><i class="bi bi-building-fill"></i> Bank Name</label>
                                        <input type="text" name="student_bank_name" class="form-control" />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label"><i class="bi bi-upc-scan"></i> IFSC Code</label>
                                        <input type="text" name="student_ifsc_code" class="form-control"
                                            pattern="[A-Z]{4}0[A-Z0-9]{6}" title="IFSC format: ABCD0123456" />
                                        <div class="invalid-feedback">IFSC must be in format ABCD0123456</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label"><i class="bi bi-list-ul"></i> Account Type</label>
                                        <select name="student_account_type" class="form-control">
                                            <option value="">Select Account Type</option>
                                            <option value="saving">Savings Account</option>
                                            <option value="current">Current Account</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label"><i class="bi bi-file-earmark-fill"></i> Upload Cancelled Cheque</label>
                                        <input name="student_upload_cancelled_cheque" type="file"
                                            class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-navigation">
                <button type="button" class="btn btn-outline-secondary" id="back4">
                    <i class="bi bi-arrow-left"></i> Back
                </button>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle-fill"></i> Submit
                </button>
            </div>
        </form>

        <!-- Success Message -->
        <div id="successMessage" class="alert alert-success mt-3" style="display:none;">
            <i class="bi bi-check-circle-fill me-2"></i>
            ✅ Student data successfully added!
            <div class="mt-2">
                <button id="addAnotherBtn" class="btn btn-sm">Add Another Student</button>
            </div>
        </div>
    </div>
</section>

<!-- JavaScript (unchanged) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// ALL JAVASCRIPT REMAINS EXACTLY THE SAME - NOT CHANGED
let siblingIndex = 0;

/* =============================
   SIBLING SEARCH FUNCTIONALITY
============================= */

// Debounce function to limit API calls
function debounce(func, wait) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func(...args), wait);
    };
}

// Fetch sibling details by registration number or email
async function fetchSiblingDetails(searchValue, inputElement) {
    if (!searchValue || searchValue.length < 3) return;

    const row = inputElement.closest('.sibling-row');
    if (!row) return;

    // Show loading state
    showSiblingLoading(row, true);
    clearSiblingValidation(row);

    try {
        const response = await fetch('/get-student-by-reg-or-email', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                search_term: searchValue
            })
        });

        const result = await response.json();

        // Hide loading state
        showSiblingLoading(row, false);

        if (!result.success) {
            showSiblingError(row, 'No student found with the provided information');
            clearSiblingFields(row);
            return;
        }

        const student = result.data;

        /* ===== PREVENT MAIN STUDENT AS SIBLING ===== */
        const mainHash = document.getElementById("student_hash_id")?.value;
        const mainReg = document.getElementById("registration_number")?.value;

        if (student.student_hash_id === mainHash || student.registration_number === mainReg) {
            showSiblingError(row, 'Main student cannot be added as sibling');
            clearSiblingFields(row);
            return;
        }

        /* ===== PREVENT DUPLICATE SIBLINGS ===== */
        if (isDuplicateSibling(student.student_hash_id, row)) {
            showSiblingError(row, 'This sibling has already been added');
            clearSiblingFields(row);
            return;
        }

        // Populate sibling fields
        populateSiblingFields(row, student);
        
        // Show success state
        inputElement.classList.add('is-valid');
        hideSiblingError(row);

    } catch (error) {
        console.error('Error fetching sibling:', error);
        showSiblingLoading(row, false);
        showSiblingError(row, 'Error fetching student details. Please try again.');
    }
}

// Debounced version of fetch function
const debouncedFetchSibling = debounce(fetchSiblingDetails, 500);

// Show/hide loading spinner
function showSiblingLoading(row, show) {
    const spinner = row.querySelector('.sibling-spinner');
    if (spinner) spinner.style.display = show ? 'block' : 'none';
}

// Show error message
function showSiblingError(row, message) {
    const notFound = row.querySelector('.sibling-not-found');
    const detailsCard = row.querySelector('.sibling-details-card');
    
    if (notFound) {
        notFound.style.display = 'block';
        notFound.innerHTML = `<i class="fas fa-exclamation-triangle mr-2"></i>${message}`;
    }
    if (detailsCard) detailsCard.style.display = 'none';
}

// Hide error message
function hideSiblingError(row) {
    const notFound = row.querySelector('.sibling-not-found');
    if (notFound) notFound.style.display = 'none';
}

// Clear validation states
function clearSiblingValidation(row) {
    const searchInput = row.querySelector('.sibling-reg');
    if (searchInput) {
        searchInput.classList.remove('is-valid', 'is-invalid');
    }
}

  // Populate sibling fields with student data
function populateSiblingFields(row, student) {
    // Check if elements exist before setting properties
    const regDisplay = row.querySelector('.sibling-reg-display');
    if (regDisplay) regDisplay.textContent = student.registration_number || '-';
    
    const emailDisplay = row.querySelector('.sibling-email-display');
    if (emailDisplay) emailDisplay.textContent = student.email || student.father_email || student.mother_email || '-';
    
    const nameDisplay = row.querySelector('.sibling-name-display');
    if (nameDisplay) nameDisplay.textContent = student.full_name || '-';
    
    const dobDisplay = row.querySelector('.sibling-dob-display');
    if (dobDisplay) dobDisplay.textContent = student.dob || '-';
    
    const classDisplay = row.querySelector('.sibling-class-display');
    if (classDisplay) classDisplay.textContent = student.class || '-';
    
    const sectionDisplay = row.querySelector('.sibling-section-display');
    if (sectionDisplay) sectionDisplay.textContent = student.section || '-';
    
    const mobileDisplay = row.querySelector('.sibling-mobile-display');
    if (mobileDisplay) mobileDisplay.textContent = student.mobile || '-';

    // Hidden fields for form submission
    const hashId = row.querySelector('.sibling-hash-id');
    if (hashId) hashId.value = student.student_hash_id || '';
    
    const regNumber = row.querySelector('.sibling-reg-number');
    if (regNumber) regNumber.value = student.registration_number || '';
    
    const name = row.querySelector('.sibling-name');
    if (name) name.value = student.full_name || '';
    
    const email = row.querySelector('.sibling-email');
    if (email) email.value = student.email || student.father_email || student.mother_email || '';
    
    const dob = row.querySelector('.sibling-dob');
    if (dob) dob.value = student.dob || '';
    
    const className = row.querySelector('.sibling-class');
    if (className) className.value = student.class || '';
    
    const classId = row.querySelector('.sibling-class-id');
    if (classId) classId.value = student.classID || '';
    
    const section = row.querySelector('.sibling-section');
    if (section) section.value = student.section || '';
    
    const sectionId = row.querySelector('.sibling-section-id');
    if (sectionId) sectionId.value = student.sectionID || '';
    
    const mobile = row.querySelector('.sibling-mobile');
    if (mobile) mobile.value = student.mobile || '';

    // Show details card
    const detailsCard = row.querySelector('.sibling-details-card');
    if (detailsCard) detailsCard.style.display = 'block';
}

// Clear sibling fields
function clearSiblingFields(row) {
    // Display fields - check if elements exist
    const regDisplay = row.querySelector('.sibling-reg-display');
    if (regDisplay) regDisplay.textContent = '-';
    
    const emailDisplay = row.querySelector('.sibling-email-display');
    if (emailDisplay) emailDisplay.textContent = '-';
    
    const nameDisplay = row.querySelector('.sibling-name-display');
    if (nameDisplay) nameDisplay.textContent = '-';
    
    const dobDisplay = row.querySelector('.sibling-dob-display');
    if (dobDisplay) dobDisplay.textContent = '-';
    
    const classDisplay = row.querySelector('.sibling-class-display');
    if (classDisplay) classDisplay.textContent = '-';
    
    const sectionDisplay = row.querySelector('.sibling-section-display');
    if (sectionDisplay) sectionDisplay.textContent = '-';
    
    const mobileDisplay = row.querySelector('.sibling-mobile-display');
    if (mobileDisplay) mobileDisplay.textContent = '-';

    // Hidden fields
    const hashId = row.querySelector('.sibling-hash-id');
    if (hashId) hashId.value = '';
    
    const regNumber = row.querySelector('.sibling-reg-number');
    if (regNumber) regNumber.value = '';
    
    const name = row.querySelector('.sibling-name');
    if (name) name.value = '';
    
    const email = row.querySelector('.sibling-email');
    if (email) email.value = '';
    
    const dob = row.querySelector('.sibling-dob');
    if (dob) dob.value = '';
    
    const className = row.querySelector('.sibling-class');
    if (className) className.value = '';
    
    const classId = row.querySelector('.sibling-class-id');
    if (classId) classId.value = '';
    
    const section = row.querySelector('.sibling-section');
    if (section) section.value = '';
    
    const sectionId = row.querySelector('.sibling-section-id');
    if (sectionId) sectionId.value = '';
    
    const mobile = row.querySelector('.sibling-mobile');
    if (mobile) mobile.value = '';

    // Hide details card
    const detailsCard = row.querySelector('.sibling-details-card');
    if (detailsCard) detailsCard.style.display = 'none';
    
    // Hide error message
    const notFound = row.querySelector('.sibling-not-found');
    if (notFound) notFound.style.display = 'none';
}

// Check for duplicate siblings
function isDuplicateSibling(hashId, currentRow) {
    const allHashes = document.querySelectorAll('.sibling-hash-id');
    for (let el of allHashes) {
        // Skip the current row's hidden field
        if (el.closest('.sibling-row') === currentRow) continue;
        if (el.value === hashId) return true;
    }
    return false;
}

// Add new sibling row
document.getElementById('addSiblingBtn').addEventListener('click', function() {
    let template = document.getElementById('siblingTemplate').innerHTML;
    
    // Replace index placeholder
    template = template.replace(/__INDEX__/g, siblingIndex);

    document.getElementById('siblingContainer')
        .insertAdjacentHTML('beforeend', template);

    const newRow = document.querySelector('#siblingContainer .sibling-row:last-child');
    
    // Add search input event listener
    const searchInput = newRow.querySelector('.sibling-reg');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const value = this.value.trim();
            if (value.length >= 3) {
                debouncedFetchSibling(value, this);
            } else {
                // Clear fields if input is too short
                const row = this.closest('.sibling-row');
                clearSiblingFields(row);
                showSiblingLoading(row, false);
                this.classList.remove('is-valid', 'is-invalid');
            }
        });
    }

    siblingIndex++;
});

// Remove sibling row
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('removeSibling')) {
        e.target.closest('.sibling-row').remove();
    }
});

/* ===================== AGE CALCULATION ===================== */
function calculateAge(dobInputId, ageOutputId) {
    const dobInput = document.getElementById(dobInputId);
    const ageOutput = document.getElementById(ageOutputId);
    if (!dobInput || !ageOutput) return;

    dobInput.addEventListener("change", function() {
        const dob = new Date(this.value);
        if (isNaN(dob)) {
            ageOutput.value = "";
            return;
        }

        const today = new Date();
        let years = today.getFullYear() - dob.getFullYear();
        let months = today.getMonth() - dob.getMonth();
        const days = today.getDate() - dob.getDate();

        if (days < 0) months--;
        if (months < 0) {
            years--;
            months += 12;
        }

        ageOutput.value = `${years} years${months > 0 ? `, ${months} months` : ""}`;
    });
}

// Initialize Student & Parent DOB (safe if elements don't exist)
calculateAge("student_dob", "student_age");
calculateAge("father_dob", "father_age");
calculateAge("mother_dob", "mother_age");
calculateAge("guardian_dob", "guardian_age");

/* ===================== GLOBALS ===================== */
const steps = ["step1", "step2", "step3", "step4", "step5"];
const forms = ["form1", "form2", "form3", "form4", "form5"];
const progressBar = document.getElementById("timelineProgress");
const successMessage = document.getElementById("successMessage");

let currentStep = 0;
let unlockedStepIndex = 0;
let otherDocCount = 1;
const maxOtherDocs = 3;
const instituteType = "{{ $instituteType }}";
console.log('Institute Type from server:', instituteType);

/* ===================== ACADEMIC DOCS DEFINITION ===================== */
const academicDocs = {
    school: [{
        label: "Previous Class Certificate",
        name: "prev_class_certificate"
    }],
    college: [{
            label: "10th Marksheet",
            name: "marksheet_10"
        },
        {
            label: "12th Marksheet",
            name: "marksheet_12"
        },
        {
            label: "Bachelor's Marksheet (if applicable)",
            name: "bachelor_marksheet",
            optional: true
        }
    ],
    university: [{
            label: "10th Marksheet",
            name: "marksheet_10"
        },
        {
            label: "12th Marksheet",
            name: "marksheet_12"
        },
        {
            label: "Bachelor's Marksheet",
            name: "bachelor_marksheet"
        }
    ],
    coaching: [{
            label: "10th Marksheet",
            name: "marksheet_10"
        },
        {
            label: "12th Marksheet",
            name: "marksheet_12"
        }
    ]
};

function initInstituteDocs() {
    let type = "school"; // default
    
    // Use the global instituteType variable from PHP
    if (instituteType && instituteType !== '') {
        const lowerType = instituteType.toLowerCase();
        
        if (lowerType === "school") {
            type = "school";
        } else if (lowerType === "college") {
            type = "college";
        } else if (lowerType === "university") {
            type = "university";
        } else if (lowerType === "coaching" || lowerType === "institute") {
            type = "coaching";
        }
    }
    
    renderAcademicDocs(type);
}


function renderAcademicDocs(type) {
    const container = document.getElementById("academic-documents-container");
    if (!container) {
        console.log('Academic documents container not found');
        return;
    }

    container.innerHTML = "";
    
    if (!academicDocs[type]) {
        console.log('No academic docs defined for type:', type);
        return;
    }

    console.log('Rendering academic docs for:', type, academicDocs[type]);
    
    academicDocs[type].forEach(doc => {
        const col = document.createElement("div");
        col.className = "col-md-6 academic-field";

        // Make all documents optional - remove required attribute
        col.innerHTML = `
            <label class="form-label">${doc.label} 
                <small class="text-muted">(optional)</small>
            </label>
            <input type="file" 
                   name="${doc.name}" 
                   class="form-control" 
                   accept=".pdf,.jpg,.jpeg,.png">
        `;

        container.appendChild(col);
    });
}

/* ===================== DOM READY (single init) ===================== */
document.addEventListener('DOMContentLoaded', function() {
    showForm(currentStep);
    initFormStates();
    bindForm2Events();
    initGuardianLogic();
    initStudentBank();
    initInstituteDocs();
    initCourseSelection();
    initValidationListeners();
    initFormSubmissions();
    initBackAndResetButtons();
    updateGuardianValue();
});

// Function to populate course modes with hybrid logic
function populateCourseModes(courseModes) {
    const modeSelect = document.getElementById('mode_of_course');
    modeSelect.innerHTML = '<option value="">-- Select Course Mode --</option>';

    if (courseModes && courseModes.length > 0) {
        courseModes.forEach(mode => {
            // Use lowercase values that match database CHECK constraint
            let value = mode.toLowerCase();
            let display = mode; // Keep display as is

            const option = document.createElement('option');
            option.value = value; // Use lowercase
            option.textContent = display; // Display with proper case
            option.setAttribute('data-display', display); // Store display text
            modeSelect.appendChild(option);
        });
    }
}

// Function to populate mode types with hybrid logic
function populateModeTypes(modeTypes) {
    const modeTypeSelect = document.getElementById('mode_type');
    modeTypeSelect.innerHTML = '<option value="">-- Select Mode Type --</option>';

    if (modeTypes && modeTypes.length > 0) {
        modeTypes.forEach(type => {
            // Use lowercase with underscore format
            let value = type.toLowerCase().replace('-', '_');
            let display = type.replace('_', ' '); // Display with space

            const option = document.createElement('option');
            option.value = value;
            option.textContent = display;
            modeTypeSelect.appendChild(option);
        });
    }
}

// Helper function for jQuery version
function populateModeTypeDropdown(modeTypes) {
    const modeTypeSelect = $('#mode_type');
    modeTypeSelect.html('<option value="">-- Select Mode Type --</option>');

    if (modeTypes && modeTypes.length > 0) {
        modeTypes.forEach(type => {
            if (type === 'hybrid') {
                const allOptions = ['part_time', 'full_time'];
                allOptions.forEach(optionValue => {
                    modeTypeSelect.append(`<option value="${optionValue}">${optionValue}</option>`);
                });
                return false;
            } else {
                modeTypeSelect.append(`<option value="${type}">${type}</option>`);
            }
        });
    }
}

// Event listener for course mode change
document.getElementById('mode_of_course').addEventListener('change', function() {
    const selectedMode = this.value;
    console.log('Course mode changed to:', selectedMode);
    // Mode type doesn't depend on course mode selection in this logic
});

// Function to set latest batch and academic year
function setLatestBatch(latestBatch) {
    if (latestBatch) {
        document.getElementById('batch_name').value = latestBatch.batch_name;
        document.getElementById('batch_id').value = latestBatch.batch_id;
        document.getElementById('academic_year_name').value = latestBatch.academic_year_name;
        document.getElementById('academic_year_id').value = latestBatch.academic_year_id;
    }
}

$('#department_category_id').change(function() {
    let categoryId = $(this).val();

    // Reset downstream selects
    $('#department_id').prop('disabled', true).html('<option value="">-- Select Department --</option>');
    $('#course_type').prop('disabled', true).html('<option value="">-- Select Course Type --</option>');
    $('#course_subtype_id').prop('disabled', true).html('<option value="">-- Select Sub Type --</option>');
    $('#additionalAcademicFields').hide();

    if (categoryId) {
        $('#department_id').html('<option value="">Loading departments...</option>');

        // Load departments by category
        fetch(`/ajax/departments-by-category?category_id=${categoryId}`)
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {

                $('#department_id').html('<option value="">-- Select Department --</option>');
                if (data.departments && data.departments.length > 0) {
                    data.departments.forEach(dept => {
                        $('#department_id').append(
                            `<option value="${dept.department_id}">${dept.department}</option>`
                        );
                    });
                    $('#department_id').prop('disabled', false);
                } else {
                    $('#department_id').html('<option value="">No departments found</option>');
                }
            })
            .catch(error => {
                console.error('Error loading departments:', error);
                $('#department_id').html('<option value="">Error loading departments</option>');
            });
    }
});

// Department change event
$('#department_id').change(function() {
    let deptId = $(this).val();
    let deptName = $(this).find('option:selected').text();

    // Store department name
    $('#department').val(deptName);

    // Reset downstream selects
    $('#course_type').prop('disabled', true).html('<option value="">-- Select Course Type --</option>');
    $('#course_subtype_id').prop('disabled', true).html('<option value="">-- Select Sub Type --</option>');
    $('#additionalAcademicFields').hide();

    if (deptId) {
        $('#course_type').html('<option value="">Loading courses...</option>');

        // Load course types by department
        fetch(`/ajax/course-types-by-department?department_id=${deptId}`)
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                console.log('Courses data:', data);

                $('#course_type').html('<option value="">-- Select Course Type --</option>');
                if (data.courses && data.courses.length > 0) {
                    data.courses.forEach(course => {
                        $('#course_type').append(
                            `<option value="${course.finacp_merchant_sub_category_type}" data-finacp-id="${course.finacp_merchant_sub_category_id}">${course.finacp_merchant_sub_category_type}</option>`
                        );
                    });
                    $('#course_type').prop('disabled', false);
                } else {
                    $('#course_type').html('<option value="">No courses found</option>');
                }
            })
            .catch(error => {
                console.error('Error loading courses:', error);
                $('#course_type').html('<option value="">Error loading courses</option>');
            });
    }
});

// Course Type change event
$('#course_type').change(function() {
    let deptId = $('#department_id').val();
    let courseType = $(this).val();
    let finacpId = $(this).find('option:selected').data('finacp-id');

    // Set hidden fields for course type
    $('#course_type_id').val(finacpId);

    // Reset downstream selects
    $('#course_subtype_id').prop('disabled', true).html('<option value="">-- Select Sub Type --</option>');
    $('#additionalAcademicFields').hide();

    if (deptId && courseType) {
        $('#course_subtype_id').html('<option value="">Loading branches...</option>');

        // Load branches by course
        fetch(
                `/ajax/get-branches-by-course?department_id=${deptId}&course_type=${encodeURIComponent(courseType)}`
                )
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                $('#course_subtype_id').html('<option value="">-- Select Sub Type --</option>');
                if (data.branches && data.branches.length > 0) {
                    data.branches.forEach(branch => {
                        $('#course_subtype_id').append(
                            `<option value="${branch.product_id}" data-sub-type="${branch.sub_type}">${branch.sub_type}</option>`
                        );
                    });
                    $('#course_subtype_id').prop('disabled', false);
                } else {
                    $('#course_subtype_id').html('<option value="">No branches found</option>');
                }
            })
            .catch(error => {
                console.error('Error loading branches:', error);
                $('#course_subtype_id').html('<option value="">Error loading branches</option>');
            });
    }
});

// Sub Type change event
$('#course_subtype_id').change(function() {
    let productId = $(this).val();
    let subTypeText = $(this).find('option:selected').data('sub-type');

    // Set hidden fields
    $('#course_subtype').val(subTypeText);
    $('#course_detail_id').val(productId);

    if (productId) {

        // Fetch additional academic details
        $.get('/get-academic-details/' + productId, function(data) {

            // Set latest batch in readonly input field
            if (data.latest_batch) {
                $('#batch_name').val(data.latest_batch.batch_name);
                $('#batch_id').val(data.latest_batch.batch_id);

            }

            // Populate Academic Year dropdown with all academic years
            $('#academic_year_id').html('<option value="">-- Select Academic Year --</option>');
            if (data.academic_years && data.academic_years.length > 0) {
                $.each(data.academic_years, function(key, value) {
                    $('#academic_year_id').append('<option value="' + value.academic_year_id +
                        '">' + value.academic_year_name + '</option>');
                });

                // Auto-select the latest academic year if available
                if (data.latest_batch) {
                    $('#academic_year_id').val(data.latest_batch.academic_year_id);
                    $('#academic_year_name').val(data.latest_batch.academic_year_name);
                }
            }

            // Populate Course Mode dropdown with hybrid logic
            $('#mode_of_course').html('<option value="">-- Select Course Mode --</option>');
            if (data.course_modes && data.course_modes.length > 0) {
                let hasHybrid = data.course_modes.includes('Hybrid');

                if (hasHybrid) {
                    $('#mode_of_course').append('<option value="Online">Online</option>');
                    $('#mode_of_course').append('<option value="Offline">Offline</option>');
                } else {
                    data.course_modes.forEach(mode => {
                        $('#mode_of_course').append('<option value="' + mode + '">' + mode +
                            '</option>');
                    });
                }
            }

            // Populate Mode Type dropdown with hybrid logic
            $('#mode_type').html('<option value="">-- Select Mode Type --</option>');
            if (data.mode_types && data.mode_types.length > 0) {
                let hasHybrid = data.mode_types.includes('hybrid');

                if (hasHybrid) {
                    $('#mode_type').append('<option value="part_time">Part Time</option>');
                    $('#mode_type').append('<option value="full_time">Full Time</option>');
                } else {
                    data.mode_types.forEach(type => {
                        $('#mode_type').append('<option value="' + type + '">' + type +
                            '</option>');
                    });
                }
            }

            // Populate Semester dropdown
            $('#semester_id').html('<option value="">-- Select Semester --</option>');
            if (data.semesters && data.semesters.length > 0) {
                $.each(data.semesters, function(key, value) {
                    let cleanValue = String(value).trim();
                    cleanValue = cleanValue.replace(/^\[|\]|"|'/g, '');
                    if (cleanValue && cleanValue !== '""' && cleanValue !== "''") {
                        $('#semester_id').append('<option value="' + cleanValue + '">' +
                            cleanValue + '</option>');
                    }
                });
            }

            // Populate Section dropdown
            $('#section_id').html('<option value="">-- Select Section --</option>');
            if (data.sections && data.sections.length > 0) {
                $.each(data.sections, function(key, section) {
                    // section is an object like: {id: "section_1", name: "Section-A", seats: 30}
                    if (section && section.id && section.name) {
                        $('#section_id').append('<option value="' + section.id + '">' + section
                            .name + '</option>');
                    }
                });
            }

            // Show additional academic fields
            $('#additionalAcademicFields').show();

        }).fail(function(xhr, status, error) {
            console.error('Error loading academic details:', error);
            alert('Error loading academic details. Please try again.');
            $('#additionalAcademicFields').hide();
        });
    } else {
        $('#additionalAcademicFields').hide();
        $('#semester_id').html('<option value="">-- Select Semester --</option>');
        $('#section_id').html('<option value="">-- Select Section --</option>');
        $('#batch_name').val('');
        $('#batch_id').val('');
        $('#academic_year_id').html('<option value="">-- Select Academic Year --</option>');
        $('#academic_year_name').val('');
    }
});

// Add change event for sub_type to set the hidden fields
$('#sub_type').change(function() {
    let subTypeText = $(this).find('option:selected').data('sub-type');
    let productId = $(this).val();

    $('#course_subtype_name').val(subTypeText);
    $('#course_subtype_id').val(productId);
});

// Event listener for academic year change to update the hidden academic_year_name
$('#academic_year_id').change(function() {
    const selectedText = $(this).find('option:selected').text();
    $('#academic_year_name').val(selectedText);
});

// Auto Generate Registration Number
document.getElementById("btnGenerateReg").addEventListener("click", function() {

    // FORMAT: REG-YEAR-RANDOM6DIGITS
    let year = new Date().getFullYear();
    let randomNum = Math.floor(100000 + Math.random() * 900000);

    let regNo = `REG-${year}-${randomNum}`;

    document.getElementById("registration_number").value = regNo;
});

/* ===================== PARENT SELECTION AND MARITAL STATUS LOGIC ===================== */
function initParentSelection() {
    const parentRadios = document.querySelectorAll('input[name="parent_selection"]');
    const fatherSection = document.getElementById('fatherSection');
    const motherSection = document.getElementById('motherSection');
    
    if (!parentRadios.length || !fatherSection || !motherSection) return;

    function updateParentSections() {
        const selectedValue = document.querySelector('input[name="parent_selection"]:checked')?.value;

        if (selectedValue === 'father') {
            fatherSection.style.display = 'block';
            motherSection.style.display = 'none';
            
            // Set required attributes for father fields only
            document.querySelectorAll('.father-field[data-required="true"]').forEach(f => f.required = true);
            document.querySelectorAll('.mother-field').forEach(f => f.required = false);
            
            // Trigger father marital status update
            const fatherMarital = document.getElementById('father_marital_status');
            if (fatherMarital) {
                updateFatherMaritalFields(fatherMarital.value);
            }
            
        } else if (selectedValue === 'mother') {
            fatherSection.style.display = 'none';
            motherSection.style.display = 'block';
            
            // Set required attributes for mother fields only
            document.querySelectorAll('.father-field').forEach(f => f.required = false);
            document.querySelectorAll('.mother-field[data-required="true"]').forEach(f => f.required = true);
            
            // Trigger mother marital status update
            const motherMarital = document.getElementById('mother_marital_status');
            if (motherMarital) {
                updateMotherMaritalFields(motherMarital.value);
            }
        }
    }

    parentRadios.forEach(radio => radio.addEventListener('change', updateParentSections));
    updateParentSections(); // Initialize on page load
}

/* ===================== FATHER MARITAL STATUS HANDLING ===================== */
function updateFatherMaritalFields(status) {
    const spouseGroup = document.getElementById('father_spouse_group');
    const dependentGroup = document.querySelector('.father-dependent-group');
    const motherBox = document.getElementById('father_married_mother_box');
    const spouseInput = document.getElementById('father_spouse_name');
    
    // Always show dependents
    if (dependentGroup) dependentGroup.style.display = 'block';
    
    // Handle different marital statuses
    switch(status) {
        case 'married':
            // Show spouse field
            if (spouseGroup) {
                spouseGroup.style.display = 'block';
                spouseInput.required = false; // Optional
            }
            // Show mother's details box (optional)
            if (motherBox) motherBox.style.display = 'block';
            break;
            
        case 'divorced':
        case 'widowed':
            // Show spouse field
            if (spouseGroup) {
                spouseGroup.style.display = 'block';
                spouseInput.required = false; // Optional
            }
            // Hide mother's details box
            if (motherBox) motherBox.style.display = 'none';
            break;
            
        case 'single':
        default:
            // Hide spouse field and clear value
            if (spouseGroup) {
                spouseGroup.style.display = 'none';
                spouseInput.value = '';
                spouseInput.required = false;
            }
            // Hide mother's details box
            if (motherBox) motherBox.style.display = 'none';
            break;
    }
}

/* ===================== MOTHER MARITAL STATUS HANDLING ===================== */
function updateMotherMaritalFields(status) {
    const spouseGroup = document.getElementById('mother_spouse_group');
    const dependentGroup = document.querySelector('.mother-dependent-group');
    const fatherBox = document.getElementById('mother_married_father_box');
    const spouseInput = document.getElementById('mother_spouse_name');
    
    // Always show dependents
    if (dependentGroup) dependentGroup.style.display = 'block';
    
    // Handle different marital statuses
    switch(status) {
        case 'married':
            // Show spouse field
            if (spouseGroup) {
                spouseGroup.style.display = 'block';
                spouseInput.required = false; // Optional
            }
            // Show father's details box (optional)
            if (fatherBox) fatherBox.style.display = 'block';
            break;
            
        case 'divorced':
        case 'widowed':
            // Show spouse field
            if (spouseGroup) {
                spouseGroup.style.display = 'block';
                spouseInput.required = false; // Optional
            }
            // Hide father's details box
            if (fatherBox) fatherBox.style.display = 'none';
            break;
            
        case 'single':
        default:
            // Hide spouse field and clear value
            if (spouseGroup) {
                spouseGroup.style.display = 'none';
                spouseInput.value = '';
                spouseInput.required = false;
            }
            // Hide father's details box
            if (fatherBox) fatherBox.style.display = 'none';
            break;
    }
}

/* ===================== INITIALIZE AGE CALCULATION FOR ALL DOB FIELDS ===================== */
function calculateAge(dobInputId, ageOutputId) {
    const dobInput = document.getElementById(dobInputId);
    const ageOutput = document.getElementById(ageOutputId);
    if (!dobInput || !ageOutput) return;

    dobInput.addEventListener("change", function() {
        const dob = new Date(this.value);
        if (isNaN(dob)) {
            ageOutput.value = "";
            return;
        }

        const today = new Date();
        let years = today.getFullYear() - dob.getFullYear();
        let months = today.getMonth() - dob.getMonth();
        const days = today.getDate() - dob.getDate();

        if (days < 0) months--;
        if (months < 0) {
            years--;
            months += 12;
        }

        ageOutput.value = `${years} years${months > 0 ? `, ${months} months` : ""}`;
    });
}

/* ===================== UPDATE DOMContentLoaded ===================== */
document.addEventListener('DOMContentLoaded', function() {
    initParentSelection();
    
    // Initialize marital status event listeners
    const fatherMarital = document.getElementById('father_marital_status');
    const motherMarital = document.getElementById('mother_marital_status');
    
    if (fatherMarital) {
        fatherMarital.addEventListener('change', function() {
            updateFatherMaritalFields(this.value);
        });
        // Initialize with default value
        updateFatherMaritalFields(fatherMarital.value);
    }
    
    if (motherMarital) {
        motherMarital.addEventListener('change', function() {
            updateMotherMaritalFields(this.value);
        });
        // Initialize with default value
        updateMotherMaritalFields(motherMarital.value);
    }
    
    // Initialize age calculations for all DOB fields
    calculateAge("father_dob", "father_age");
    calculateAge("mother_dob", "mother_age");
    calculateAge("father_married_mother_dob", "father_married_mother_age");
    calculateAge("mother_married_father_dob", "mother_married_father_age");
    calculateAge("guardian_dob", "guardian_age");
    
    // Set default marital status to married
    if (fatherMarital) fatherMarital.value = 'married';
    if (motherMarital) motherMarital.value = 'married';
    
    // Set father as default parent
    const fatherRadio = document.getElementById('parent_father');
    if (fatherRadio) fatherRadio.checked = true;
});

// Update the initMaritalStatusLogic function to match the same behavior
function initMaritalStatusLogic() {
    const fatherMarital = document.getElementById('father_marital_status');
    const motherMarital = document.getElementById('mother_marital_status');
    const fatherSpouse = document.getElementById('father_spouse_name');
    const motherSpouse = document.getElementById('mother_spouse_name');
    const fatherSpouseGroup = document.getElementById('father_spouse_name_group');
    const motherSpouseGroup = document.getElementById('mother_spouse_name_group');

    // Helper: Get full name from parent fields
    function getParentFullName(prefix) {
        const first = document.querySelector(`[name="${prefix}_first_name"]`)?.value || '';
        const middle = document.querySelector(`[name="${prefix}_middle_name"]`)?.value || '';
        const last = document.querySelector(`[name="${prefix}_last_name"]`)?.value || '';
        return [first, middle, last].filter(Boolean).join(' ');
    }

    // Helper: Split full name into parts
    function splitFullName(fullName) {
        const parts = (fullName || '').trim().split(/\s+/);
        if (parts.length === 0) return { first: '', middle: '', last: '' };
        if (parts.length === 1) return { first: parts[0], middle: '', last: '' };
        if (parts.length === 2) return { first: parts[0], middle: '', last: parts[1] };
        return { first: parts[0], middle: parts.slice(1, -1).join(' '), last: parts[parts.length - 1] };
    }

    // Helper: Set parent name fields from full name
    function setParentNameFields(prefix, fullName) {
        const parts = splitFullName(fullName);
        const firstNameEl = document.querySelector(`[name="${prefix}_first_name"]`);
        const middleNameEl = document.querySelector(`[name="${prefix}_middle_name"]`);
        const lastNameEl = document.querySelector(`[name="${prefix}_last_name"]`);

        if (firstNameEl && parts.first) firstNameEl.value = parts.first;
        if (middleNameEl && parts.middle) middleNameEl.value = parts.middle;
        if (lastNameEl && parts.last) lastNameEl.value = parts.last;
    }

    // Check if spouse should be shown based on marital status
    function shouldShowSpouse(maritalStatus) {
        return maritalStatus === 'married' || maritalStatus === 'widowed' || maritalStatus === 'divorced';
    }

    // Update spouse field visibility and content
    function updateSpouseFields() {
        const parentSelection = document.querySelector('input[name="parent_selection"]:checked')?.value;
        
        // If in both mode, hide all marital status and family fields
        if (parentSelection === 'both') {
            // Hide marital status fields
            if (fatherMarital) {
                fatherMarital.closest('.col-md-4').style.display = 'none';
            }
            if (motherMarital) {
                motherMarital.closest('.col-md-4').style.display = 'none';
            }
            
            // Hide spouse groups
            if (fatherSpouseGroup) fatherSpouseGroup.style.display = 'none';
            if (motherSpouseGroup) motherSpouseGroup.style.display = 'none';
            
            // Clear spouse fields
            if (fatherSpouse) fatherSpouse.value = '';
            if (motherSpouse) motherSpouse.value = '';
            return;
        }

        // Show marital status fields when not in both mode
        if (fatherMarital) {
            fatherMarital.closest('.col-md-4').style.display = 'block';
        }
        if (motherMarital) {
            motherMarital.closest('.col-md-4').style.display = 'block';
        }

        // Father's spouse field
        if (fatherMarital && fatherSpouseGroup && fatherSpouse) {
            const fatherStatus = fatherMarital.value;
            const showFatherSpouse = shouldShowSpouse(fatherStatus);
            
            // Show spouse field for married, widowed, divorced
            if (showFatherSpouse) {
                fatherSpouseGroup.style.display = 'block';
                fatherSpouse.readOnly = false;
            } else {
                fatherSpouseGroup.style.display = 'none';
                fatherSpouse.value = '';
            }
        }

        // Mother's spouse field
        if (motherMarital && motherSpouseGroup && motherSpouse) {
            const motherStatus = motherMarital.value;
            const showMotherSpouse = shouldShowSpouse(motherStatus);
            
            // Show spouse field for married, widowed, divorced
            if (showMotherSpouse) {
                motherSpouseGroup.style.display = 'block';
                motherSpouse.readOnly = false;
            } else {
                motherSpouseGroup.style.display = 'none';
                motherSpouse.value = '';
            }
        }
    }

    // When spouse name is entered, update the other parent's name (for "both" mode)
    function setupSpouseToParentSync() {
        // Father's spouse -> Mother's name
        if (fatherSpouse) {
            fatherSpouse.addEventListener('input', function() {
                const parentSelection = document.querySelector('input[name="parent_selection"]:checked')?.value;
                if (parentSelection === 'both' && fatherMarital?.value === 'married' && this.value) {
                    setParentNameFields('mother', this.value);
                }
            });
        }

        // Mother's spouse -> Father's name
        if (motherSpouse) {
            motherSpouse.addEventListener('input', function() {
                const parentSelection = document.querySelector('input[name="parent_selection"]:checked')?.value;
                if (parentSelection === 'both' && motherMarital?.value === 'married' && this.value) {
                    setParentNameFields('father', this.value);
                }
            });
        }
    }

    // When parent name changes, update spouse field (for "both" mode)
    function setupParentToSpouseSync() {
        // Father's name changes -> Update mother's spouse field
        ['father_first_name', 'father_middle_name', 'father_last_name'].forEach(field => {
            const el = document.querySelector(`[name="${field}"]`);
            if (el) {
                el.addEventListener('input', function() {
                    const parentSelection = document.querySelector('input[name="parent_selection"]:checked')?.value;
                    if (parentSelection === 'both' && motherMarital?.value === 'married' && motherSpouse) {
                        const fatherName = getParentFullName('father');
                        if (fatherName) motherSpouse.value = fatherName;
                    }
                });
            }
        });

        // Mother's name changes -> Update father's spouse field
        ['mother_first_name', 'mother_middle_name', 'mother_last_name'].forEach(field => {
            const el = document.querySelector(`[name="${field}"]`);
            if (el) {
                el.addEventListener('input', function() {
                    const parentSelection = document.querySelector('input[name="parent_selection"]:checked')?.value;
                    if (parentSelection === 'both' && fatherMarital?.value === 'married' && fatherSpouse) {
                        const motherName = getParentFullName('mother');
                        if (motherName) fatherSpouse.value = motherName;
                    }
                });
            }
        });
    }

    // Event listeners
    if (fatherMarital) fatherMarital.addEventListener('change', updateSpouseFields);
    if (motherMarital) motherMarital.addEventListener('change', updateSpouseFields);

    // Parent selection change
    document.querySelectorAll('input[name="parent_selection"]').forEach(radio => {
        radio.addEventListener('change', updateSpouseFields);
    });

    // Initial setup
    updateSpouseFields();
    setupSpouseToParentSync();
    setupParentToSpouseSync();
}

// Update the DOMContentLoaded event listener
document.addEventListener('DOMContentLoaded', function() {
    initParentSelection();
    initMaritalStatusLogic();

    // Set default marital status to married
    const fatherMarital = document.getElementById('father_marital_status');
    const motherMarital = document.getElementById('mother_marital_status');
    if (fatherMarital) fatherMarital.value = 'married';
    if (motherMarital) motherMarital.value = 'married';
});

/* ===================== GUARDIAN HANDLING ===================== */
function updateGuardianValue() {
    const guardianRadios = document.querySelectorAll('input[name="guardian_type"]');
    const guardianContainer = document.querySelector('.guardianContainer');
    if (!guardianRadios.length || !guardianContainer) return;
    const selected = Array.from(guardianRadios).find(radio => radio.checked)?.value;
    if (!selected) return;
    console.log(selected);
    if (selected === 'other') {
        guardianContainer.classList.remove('d-none');
        guardianContainer.querySelectorAll('input, select').forEach(el => el.value = '');
    } else {
        guardianContainer.classList.add('d-none');
        autoFillGuardian(selected);
    }

}

function initGuardianLogic() {
    const guardianRadios = document.querySelectorAll('input[name="guardian_type"]');
    const guardianContainer = document.querySelector('.guardianContainer');
    if (!guardianRadios.length || !guardianContainer) return;
    // attach listeners
    guardianRadios.forEach(radio => {
        radio.addEventListener('change', updateGuardianValue);
    });
    // set default to father on page load
    const defaultGuardian = document.querySelector('input[value="father"][name="guardian_type"]');
    if (defaultGuardian) {
        defaultGuardian.checked = true;
        guardianContainer.classList.add('d-none');
        autoFillGuardian('father');
    }
}

/* ===================== FORM STEP MANAGEMENT ===================== */
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

/* ===================== INITIAL FORM STATES ===================== */
function initFormStates() {
    // Disable all forms initially
    forms.forEach(disableFormInputs);

    // Enable first form only at start
    enableFormInputs(forms[0]);

    // Step click logic
    steps.forEach((stepId, i) => {
        const el = document.getElementById(stepId);
        if (!el) return;

        el.addEventListener("click", () => {
            showForm(i);

            // Form 1 is always locked after submission
            // if (i === 0 && unlockedStepIndex > 0) {
            //     disableFormInputs(forms[0]);
            //     return;
            // }

            // Allow editing only for current or previously unlocked ones
            if (i <= unlockedStepIndex) {
                enableFormInputs(forms[i]);
            } else {
                disableFormInputs(forms[i]);
            }
        });
    });
}

/* ===================== VALIDATION ===================== */
function validateInput(input) {
    const value = (input.value || "").trim();
    const parent = input.closest('.mb-3') || input.parentElement || document;
    let msg = '';

    input.classList.remove('is-invalid');
    parent.querySelector('.invalid-feedback')?.remove();
    if (!input.required && !value) return true;

    if (input.required && !value) msg = 'This field is required';
    else if (input.pattern && !new RegExp(input.pattern).test(value)) {
        const map = {
            mobile: 'Please enter exactly 10 digits',
            student_perm_pincode: 'Pincode must be 6 digits',
            parent_perm_pincode: 'Pincode must be 6 digits',
            student_pan_number: 'PAN must be in format ABCDE1234F',
            parent_pan_number: 'PAN must be in format ABCDE1234F',
            ifsc_code: 'IFSC must be in format ABCD0123456'
        };
        msg = map[input.name] || 'Invalid format';
    } else if (input.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value))
        msg = 'Please enter a valid email address';
    else if (input.type === 'date' && new Date(value) > new Date())
        msg = 'Date cannot be in the future';
    else if (input.name === 'dob') {
        const dob = new Date(value);
        const minAge = new Date();
        minAge.setFullYear(minAge.getFullYear() - 3);
        if (dob > minAge) msg = 'Student must be at least 3 years old';
    }

    if (msg) {
        input.classList.add('is-invalid');
        const err = document.createElement('div');
        err.className = 'invalid-feedback';
        err.textContent = msg;
        parent.appendChild(err);
        return false;
    }
    return true;
}

function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    let valid = true;
    // Clear previous validation errors
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

    // For Form 4 (documents), make all file inputs optional
    if (formId === 'form4') {
        // Skip required validation for file inputs in documents form
        form.querySelectorAll('input, select, textarea').forEach(input => {
            // Skip file inputs - they're all optional
            if (input.type === 'file') {
                return;
            }
            
            // Validate other fields normally
            if (input.type !== 'hidden' && input.required) {
                if (!validateInput(input)) valid = false;
            }
        });
        return valid;
    }
    // Additional per-step checks: Basic Details (step 0)
    try {
        if (typeof currentStep !== 'undefined' && currentStep === 0) {
            const maritalStatus = document.querySelector('[name="marital_status"]') || document.getElementById(
                'marital_status');
            if (maritalStatus && (maritalStatus.required || maritalStatus.getAttribute('data-required') === 'true') && !
                maritalStatus.value) {
                maritalStatus.classList.add('is-invalid');
                (maritalStatus.closest('.mb-3') || maritalStatus.parentElement).querySelector('.invalid-feedback')
                    ?.remove();
                const err = document.createElement('div');
                err.className = 'invalid-feedback';
                err.textContent = 'Please select marital status';
                (maritalStatus.closest('.mb-3') || maritalStatus.parentElement).appendChild(err);
                valid = false;
            }

            const dependents = document.querySelector('[name="number_of_dependents"]');
            if (dependents && dependents.value) {
                const depValue = parseInt(dependents.value, 10);
                if (isNaN(depValue) || depValue < 0 || depValue > 20) {
                    dependents.classList.add('is-invalid');
                    (dependents.closest('.mb-3') || dependents.parentElement).querySelector('.invalid-feedback')
                        ?.remove();
                    const err2 = document.createElement('div');
                    err2.className = 'invalid-feedback';
                    err2.textContent = 'Number of dependents must be between 0 and 20';
                    (dependents.closest('.mb-3') || dependents.parentElement).appendChild(err2);
                    valid = false;
                }
            }
        }
    } catch (e) {
        console.error('Additional validation error:', e);
    }

    return valid;
}

// Validate siblings before form submission
function validateSiblingsBeforeSubmit() {
    const siblingRows = document.querySelectorAll('.sibling-row');
    let isValid = true;
    let errorMessage = '';
    
    for (let row of siblingRows) {
        const searchInput = row.querySelector('.sibling-reg');
        const searchValue = searchInput?.value.trim();
        const hashId = row.querySelector('.sibling-hash-id')?.value;
        
        // If search field has value but no valid sibling selected
        if (searchValue && searchValue.length >= 3 && !hashId) {
            // Check if there's an error message already showing
            const notFound = row.querySelector('.sibling-not-found');
            if (notFound && notFound.style.display === 'block') {
                // Error already showing, just mark as invalid
                isValid = false;
                searchInput.classList.add('is-invalid');
            } else {
                // Show error message
                showSiblingError(row, 'Please select a valid sibling or remove this entry');
                isValid = false;
            }
        } else if (searchValue && searchValue.length < 3 && searchValue.length > 0) {
            // Input too short
            showSiblingError(row, 'Please enter at least 3 characters to search');
            isValid = false;
        }
    }
    
    return isValid;
}

// Override the form submission validation
const originalValidateForm = validateForm;
validateForm = function(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;

    let valid = true;

    // Clear previous validation errors
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

    // For Form 1 (step 0), validate siblings first
    if (formId === 'form1') {
        if (!validateSiblingsBeforeSubmit()) {
            valid = false;
        }
        
        const selectedParent = document.querySelector('input[name="parent_selection"]:checked')?.value;

        if (selectedParent === 'father') {
            // Validate only father fields
            form.querySelectorAll('.father-field[required]').forEach(input => {
                if (!validateInput(input)) valid = false;
            });

            // Additional validation for spouse name and dependents only when marital status qualifies
            const fatherMarital = document.getElementById('father_marital_status');
            if (fatherMarital) {
                const showFamily = fatherMarital.value === 'married';
                if (showFamily) {
                    const fatherSpouse = document.getElementById('father_spouse_name');
                    if (fatherSpouse && fatherSpouse.required && !fatherSpouse.value.trim()) {
                        fatherSpouse.classList.add('is-invalid');
                        const err = document.createElement('div');
                        err.className = 'invalid-feedback';
                        err.textContent = 'Spouse name is required';
                        fatherSpouse.parentElement.appendChild(err);
                        valid = false;
                    }
                }
            }

        } else if (selectedParent === 'mother') {
            // Validate only mother fields
            form.querySelectorAll('.mother-field[required]').forEach(input => {
                if (!validateInput(input)) valid = false;
            });

            // Additional validation for spouse name and dependents only when marital status qualifies
            const motherMarital = document.getElementById('mother_marital_status');
            if (motherMarital) {
                const showFamily = motherMarital.value === 'married';
                if (showFamily) {
                    const motherSpouse = document.getElementById('mother_spouse_name');
                    if (motherSpouse && motherSpouse.required && !motherSpouse.value.trim()) {
                        motherSpouse.classList.add('is-invalid');
                        const err = document.createElement('div');
                        err.className = 'invalid-feedback';
                        err.textContent = 'Spouse name is required';
                        motherSpouse.parentElement.appendChild(err);
                        valid = false;
                    }
                }
            }

        } else if (selectedParent === 'both') {
            // Validate both parents but NO marital status validation
            form.querySelectorAll('.father-field[required], .mother-field[required]').forEach(input => {
                if (!validateInput(input)) valid = false;
            });
            
            // No spouse validation in both mode
        }

        // Validate other non-parent fields in the form
        form.querySelectorAll('input, select, textarea').forEach(input => {
            // Skip parent fields as we already validated them
            if (input.classList.contains('father-field') || input.classList.contains('mother-field')) {
                return;
            }

            // Skip hidden fields
            if (input.type !== 'hidden' && input.required) {
                if (!validateInput(input)) valid = false;
            }
        });

        return valid;
    } else {
        // For other forms, use the original validation
        form.querySelectorAll('input, select, textarea').forEach(input => {
            if (input.type !== 'hidden' && input.required) {
                if (!validateInput(input)) valid = false;
            }
            if (input.type === 'file' && input.required && (!input.files || !input.files.length)) {
                input.classList.add('is-invalid');
                const err = document.createElement('div');
                err.className = 'invalid-feedback';
                err.textContent = 'This file is required';
                (input.closest('.mb-3') || input.parentElement).appendChild(err);
                valid = false;
            }
        });

        return valid;
    }
};

/* ===================== ENABLE / DISABLE FORM INPUTS ===================== */
function disableFormInputs(formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.querySelectorAll("input, select, textarea").forEach(el => {
        if (el.type !== "hidden") {
            el.disabled = true;
            el.classList.add("disabled-field");
        }
    });

    form.querySelector('button[type="submit"]')?.setAttribute("disabled", true);

    const addOtherDocBtn = form.querySelector('#addOtherDocBtn') || document.getElementById('addOtherDocBtn');
    if (addOtherDocBtn) {
        addOtherDocBtn.disabled = true;
        addOtherDocBtn.classList.add("disabled-field");
    }
}

function enableFormInputs(formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    // Enable all form inputs, selects, and textareas
    form.querySelectorAll("input, select, textarea").forEach(el => {
        if (el.type !== "hidden") {
            el.disabled = false;
            el.classList.remove("disabled-field");
        }
    });

    // Enable the submit button
    form.querySelector('button[type="submit"]')?.removeAttribute("disabled");

    // Enable the "Add Other Documents" button if it exists inside or outside the form
    const addOtherDocBtn = form.querySelector('#addOtherDocBtn') || document.getElementById('addOtherDocBtn');
    if (addOtherDocBtn) {
        addOtherDocBtn.disabled = false;
        addOtherDocBtn.classList.remove("disabled-field");
    }
}

/* ===================== FORM SUBMISSION ===================== */
function submitFormData(form, step, cb) {
    step = step - 1;
    const fd = new FormData(form);
    fd.append('form_step', step + 1);

    const sid = sessionStorage.getItem("current_student_id");
    const hid = sessionStorage.getItem("current_student_hash_id");
    const gt = sessionStorage.getItem("guardian_type");

    if (step > 1) {
        if (sid) fd.append("student_id", sid);
        if (hid) fd.append("student_hash_id", hid);
    }

    // Validate siblings before submission (already done in validateForm, but double-check)
    if (step === 0) {
        if (!validateSiblingsBeforeSubmit()) {
            alert('Please fix sibling entries before submitting.');
            return;
        }
    }

    const btn = form.querySelector('button[type="submit"]');
    const original = btn.innerHTML;
    btn.innerHTML =
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
    btn.disabled = true;

    fetch("/save-student-onboarding-details", {
            method: "POST",
            body: fd,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || "",
                Accept: "application/json",
            },
        })
        .then(async (r) => {
            const json = await r.json().catch(() => ({}));
            if (!r.ok) throw new Error(json.message || "Server error");
            return json;
        })
        .then((d) => {
            if (!d.success) throw new Error(d.message || "Submission failed");

            // Save student IDs
            if (step === 1) {
                if (d.student_id) sessionStorage.setItem("current_student_id", d.student_id);
                if (d.student_hash_id) sessionStorage.setItem("current_student_hash_id", d.student_hash_id);
                if (d.guardian_type) sessionStorage.setItem("guardian_type", d.guardian_type);
            }

            const nextIndex = step + 1;

            // Disable all forms first
            forms.forEach(fid => disableFormInputs(fid));

            // Re-enable only:
            // - Already submitted forms (except Form 1, which stays locked)
            // - The next form (newly unlocked)
            forms.forEach((fid, i) => {
                if (i > 0 && i < nextIndex) enableFormInputs(fid); // past submitted forms (editable)
            });

            // Enable the immediate next form only (one step ahead)
            if (forms[nextIndex]) enableFormInputs(forms[nextIndex]);

            // Update unlocked index strictly to next step
            unlockedStepIndex = step + 1;

            console.log("unlockedStepIndex =", unlockedStepIndex, "nextIndex =", nextIndex);

            // Show next form automatically
            if (forms[nextIndex]) showForm(nextIndex);

            // Restore button
            btn.innerHTML = original;
            btn.disabled = false;

            if (typeof cb === "function") cb(d);
        })
        .catch((err) => {
            console.error(err);
            btn.innerHTML = original;
            btn.disabled = false;
            
            // Check if it's a sibling-related error
            if (err.message && err.message.includes('sibling')) {
                // Show error in the sibling cards instead of alert
                const siblingRows = document.querySelectorAll('.sibling-row');
                if (siblingRows.length > 0) {
                    // Show error in the first sibling row
                    showSiblingError(siblingRows[0], err.message);
                } else {
                    alert(err.message || "Something went wrong!");
                }
            } else {
                alert(err.message || "Something went wrong!");
            }
        });
}

/* ===================== HELPERS ===================== */
function clearValidationErrors(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
}

/* ===================== GUARDIAN LOGIC ===================== */
const guardianRadios = document.querySelectorAll('input[name="guardian_type"]');
const guardianContainer = document.querySelector('.guardianContainer');

guardianRadios.forEach(radio => {
    radio.addEventListener('change', function() {
        if (this.value === 'other') {
            guardianContainer.classList.remove('d-none');
        } else {
            guardianContainer.classList.add('d-none');
            autoFillGuardian(this.value);
        }
    });
});

/* ===================== GUARDIAN AUTO-FILL ===================== */
function autoFillGuardian(type) {
    const prefix = type === 'father' ? 'father' : 'mother';

    document.querySelector('input[name="guardian_first_name"]').value =
        document.querySelector(`input[name="${prefix}_first_name"]`)?.value || '';
    document.querySelector('input[name="guardian_middle_name"]').value =
        document.querySelector(`input[name="${prefix}_middle_name"]`)?.value || '';
    document.querySelector('input[name="guardian_last_name"]').value =
        document.querySelector(`input[name="${prefix}_last_name"]`)?.value || '';
    document.querySelector('input[name="guardian_email"]').value =
        document.querySelector(`input[name="${prefix}_email"]`)?.value || '';
    document.querySelector('input[name="guardian_phone"]').value =
        document.querySelector(`input[name="${prefix}_phone"]`)?.value || '';
    document.querySelector('select[name="guardian_occupation"]').value =
        document.querySelector(`select[name="${prefix}_occupation"]`)?.value || '';
    document.querySelector('input[name="guardian_income"]').value =
        document.querySelector(`input[name="${prefix}_income"]`)?.value || '';
    document.querySelector('select[name="guardian_blood_group"]').value =
        document.querySelector(`select[name="${prefix}_blood_group"]`)?.value || '';
    document.querySelector('input[name="guardian_relation"]').value =
        document.querySelector(`input[name="${prefix}_relation"]`)?.value || '';
}

// Set Father as default guardian
const defaultGuardian = document.querySelector('input[value="father"][name="guardian_type"]');
if (defaultGuardian) {
    defaultGuardian.checked = true;
    guardianContainer.classList.add('d-none');
    autoFillGuardian('father');
}

/* ===================== STUDENT BANK TOGGLE ===================== */
function initStudentBank() {
    const checkbox = document.querySelector('input[name="add_student_bank"]');
    const container = document.querySelector('.studentBank');
    if (!checkbox || !container) return;
    checkbox.addEventListener("change", () => {
        container.classList.toggle("d-none", !checkbox.checked);
        // Clear student bank fields when unchecked
        if (!checkbox.checked) {
            container.querySelectorAll('input, select').forEach(el => {
                if (el.type !== 'checkbox') {
                    el.value = '';
                }
            });
        }
    });
}

/* ===================== ADDRESS COPY (FORM 2) ===================== */
function bindForm2Events() {
    const addressPairs = [
        ['copyStudentAddress', 'student_perm', 'student_comm'],
        ['copyStudentToParent', 'student_perm', 'parent_perm'],
        ['copyStudentToParent', 'student_comm', 'parent_comm'],
        ['copyParentAddress', 'parent_perm', 'parent_comm']
    ];
    addressPairs.forEach(([id, from, to]) => {
        const chk = document.getElementById(id);
        if (!chk) return;
        chk.addEventListener('change', () => handleAddressSync(from, to, chk.checked));
        handleAddressSync(from, to, chk.checked);
    });

    document.querySelectorAll(
        'input[name$="_address_line1"], input[name$="_address_line2"], input[name$="_city"], input[name$="_state"], input[name$="_pincode"]'
    ).forEach(el => {
        el.addEventListener('input', () => {
            if (el.dataset.syncTo) {
                const target = document.getElementsByName(el.dataset.syncTo)[0];
                if (target) target.value = el.value;
            }
        });
    });
}

function handleAddressSync(from, to, sync) {
    ['address_line1', 'address_line2', 'city', 'state', 'pincode'].forEach(f => {
        const src = document.getElementsByName(`${from}_${f}`)[0];
        const dst = document.getElementsByName(`${to}_${f}`)[0];
        if (src && dst) {
            if (sync) {
                dst.value = src.value;
                src.dataset.syncTo = `${to}_${f}`;
            } else delete src.dataset.syncTo;
        }
    });
}

/* ===================== COURSE SELECTION (store name) ===================== */
function initCourseSelection() {
    const setText = (sel, targetId) => {
        const target = document.getElementById(targetId);
        if (!sel || !target) return;
        sel.addEventListener("change", () => target.value = sel.selectedOptions[0]?.text || '');
    };
    setText(document.querySelector("select[name='course_type_id']"), "course_type_name");
    setText(document.querySelector("select[name='course_subtype_id']"), "course_subtype_name");
}

/* ========== INSTITUTE SELECT INIT (fix for missing function) ============= */


/* ===================== OTHER DOCUMENTS (FORM 4) ===================== */
document.getElementById('addOtherDocBtn')?.addEventListener('click', () => {
    if (otherDocCount >= maxOtherDocs) return;
    otherDocCount++;
    const wrapper = document.getElementById('other-documents-wrapper');
    if (!wrapper) return;

    const groupHTML = `
                    <div class="col-md-6 other-doc-group">
                        <label class="form-label">Other Document Name ${otherDocCount}</label>
                        <input type="text" name="student_other_doc_label_${otherDocCount}" class="form-control" placeholder="Enter document name">
                    </div>
                    <div class="col-md-6 other-doc-group d-flex align-items-end">
                        <div class="w-100">
                            <label class="form-label d-flex justify-content-between align-items-center">
                                Upload Other Document ${otherDocCount}
                                <button type="button" class="btn btn-sm btn-danger ms-2 removeOtherDocBtn"><i class="fas fa-times"></i></button>
                            </label>
                            <input type="file" name="student_other_doc_file_${otherDocCount}" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>`;
    wrapper.insertAdjacentHTML('beforeend', groupHTML);
});

document.addEventListener('click', e => {
    const btn = e.target.closest('.removeOtherDocBtn');
    if (btn) {
        const group = btn.closest('.other-doc-group');
        group?.previousElementSibling?.remove();
        group?.remove();
        otherDocCount = Math.max(1, otherDocCount - 1);
    }
});

/* ===================== REAL-TIME VALIDATION LISTENERS ===================== */
function initValidationListeners() {
    document.querySelectorAll('input, select, textarea').forEach(input => {
        input.addEventListener('blur', () => validateInput(input));
        if (input.name && (input.name.includes('mobile') || /pincode|pan|ifsc/.test(input.name))) {
            input.addEventListener('input', () => {
                if (input.value.length >= (input.minLength || 0)) validateInput(input);
            });
        }
    });
}

/* ============= FORM SUBMISSIONS (ALL FORMS) =============== */
function initFormSubmissions() {
    forms.forEach((id, i) => {
        const form = document.getElementById(id);
        if (!form) return;

        form.addEventListener("submit", function(e) {
            e.preventDefault();
            const step = i + 1;

            clearValidationErrors(id);
            if (!validateForm(id)) return;

            submitFormData(this, step, (d) => {
                currentStep = step;
                showForm(step);

                // ✅ Control Guardian Sections visibility
                const guardianType = d.guardian_type || sessionStorage.getItem("guardian_type");
                console.log("Guardian Type:", guardianType);

                const addressContainer = document.querySelector(".guardian-address-container");
                const docsContainer = document.querySelector(".otherDocumentsContainer");

                if (guardianType && guardianType.toLowerCase() === "other") {
                    addressContainer?.classList.remove("d-none");
                    addressContainer?.classList.add("d-block");

                    docsContainer?.classList.remove("d-none");
                    docsContainer?.classList.add("d-block");
                } else {
                    addressContainer?.classList.add("d-none");
                    addressContainer?.classList.remove("d-block");

                    docsContainer?.classList.add("d-none");
                    docsContainer?.classList.remove("d-block");
                }

                // ✅ Handle final success case
                if (step === 5) {
                    form.style.display = "none";
                    if (successMessage) successMessage.style.display = "block";
                    clearAllForms();

                    setTimeout(() => {
                        currentStep = 0;
                        showForm(currentStep);
                        if (successMessage) successMessage.style.display = "none";

                        sessionStorage.removeItem("current_student_id");
                        sessionStorage.removeItem("current_student_hash_id");
                        sessionStorage.removeItem("guardian_type");
                    }, 3000);
                }
            });
        });
    });
}

/* =========== BACK / RESET BUTTONS ============= */
function initBackAndResetButtons() {
    const backs = [{
            id: 'backToForm1',
            step: 0
        },
        {
            id: 'back2',
            step: 1
        },
        {
            id: 'back3',
            step: 2
        },
        {
            id: 'back4',
            step: 3
        },
    ];
    backs.forEach(b => document.getElementById(b.id)?.addEventListener('click', () => {
        currentStep = b.step;
        showForm(b.step);
    }));

    document.getElementById("addAnotherBtn")?.addEventListener("click", () => {
        clearAllForms();
        currentStep = 0;
        showForm(0);
        if (successMessage) successMessage.style.display = "none";
        sessionStorage.removeItem('current_student_id');
        sessionStorage.removeItem('current_student_hash_id');
    });
}

/* =========== CLEAR ALL FORMS =========== */
function clearAllForms() {
    forms.forEach(id => {
        const form = document.getElementById(id);
        if (form) {
            form.reset();
            clearValidationErrors(id);
            form.querySelectorAll('input[type="file"]').forEach(f => {
                try {
                    f.value = '';
                } catch (e) {}
            });
        }
    });

    // Reset other documents wrapper (keep first two children if present)
    otherDocCount = 1;
    const otherDocsWrapper = document.getElementById("other-documents-wrapper");
    if (otherDocsWrapper) {
        const children = Array.from(otherDocsWrapper.children);
        const keep = children.slice(0, 2);
        otherDocsWrapper.innerHTML = "";
        keep.forEach(n => otherDocsWrapper.appendChild(n));
    }

    // Refresh academic docs based on institute select (if present)
    const instituteSelect = document.querySelector('select[name="institute_id"]');
    if (instituteSelect) instituteSelect.dispatchEvent(new Event('change'));
}
</script>
@endsection