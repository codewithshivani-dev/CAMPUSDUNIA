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
    }

    /* Page Header */
    .page-header {
        background: var(--primary-gradient);
        padding: 20px 25px;
        border-radius: 16px;
        margin-bottom: 25px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
    }

    .page-header h5 {
        color: white;
        margin: 0;
        font-weight: 700;
        display: flex;
        align-items: center;
    }

    .page-header h5 i {
        border-radius: 12px;
        margin-right: 15px;
        font-size: 32px;
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
        margin: 25px 0 30px;
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

    .timeline-step span {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #475569;
        transition: all 0.3s;
        margin-top: 5px;
    }

    .timeline-step.active span {
        color: var(--primary-color);
        font-weight: 700;
    }

    .timeline-step.completed span {
        color: var(--success-color);
    }

    /* Main Card */
    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        border: none;
        overflow: hidden;
    }

    .main-card-header {
        background: var(--primary-gradient) !important;
        padding: 20px 30px !important;
        border: none !important;
    }

    .main-card-header h5 {
        color: white;
        margin: 0;
        font-weight: 700;
        display: flex;
        align-items: center;
    }

    .main-card-header h5 i {
        background: rgba(255,255,255,0.2);
        padding: 10px;
        border-radius: 12px;
        margin-right: 15px;
        font-size: 1.1rem;
    }

    .card-body {
        padding: 30px;
        background: white;
    }

    /* Step Cards */
    .step-card {
        border: 2px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s;
        background: white;
    }

    .step-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.1);
    }

    .step-card-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9) !important;
        padding: 15px 25px !important;
        border-bottom: 2px solid #e2e8f0 !important;
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
        gap: 10px;
        font-size: 1rem;
    }

    .step-card-header h6 i {
        font-size: 1.1rem;
        color: var(--primary-color);
    }

    .step-card-body {
        padding: 25px;
        background: white;
    }

    /* Form Labels */
    label {
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
        font-size: 13px;
        letter-spacing: 0.3px;
        display: block;
    }

    label i {
        color: var(--primary-color);
        margin-right: 6px;
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
        width: 100%;
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

    /* Input Group */
    .input-group {
        display: flex;
        align-items: stretch;
    }

    .input-group-append .btn-outline-primary {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
        border: 2px solid #e2e8f0;
        border-left: none;
        padding: 10px 15px;
        background: white;
        color: var(--primary-color);
        font-weight: 500;
    }

    .input-group-append .btn-outline-primary:hover {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
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
        margin-right: 6px;
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

    /* Textarea */
    textarea.form-control {
        min-height: 80px;
        resize: vertical;
    }

    /* File Input */
    input[type="file"].form-control {
        padding: 8px 12px;
    }

    /* Alert Styles */
    .alert {
        border: none;
        border-radius: 16px;
        padding: 18px 25px;
        margin-bottom: 25px;
    }

    .alert-success {
        background: var(--success-gradient);
        color: white;
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.2);
    }

    .alert-danger {
        background: var(--danger-gradient);
        color: white;
        box-shadow: 0 10px 25px rgba(239, 68, 68, 0.2);
    }

    .alert-success .btn-close,
    .alert-danger .btn-close {
        filter: brightness(0) invert(1);
    }

    /* Button Styles */
    .btn-primary {
        display: flex;
        background: var(--primary-gradient);
        border: none;
        padding: 10px 28px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        color: white;
        align-items: center;
        gap: 8px;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #94a3b8, #64748b);
        color: white;
        border: none;
        padding: 10px 28px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(100, 116, 139, 0.3);
    }

    .btn-success {
        background: var(--success-gradient);
        border: none;
        padding: 10px 28px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        color: white;
        align-items: center;
        gap: 8px;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
    }

    .btn-outline-primary {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        background: transparent;
        padding: 8px 20px;
        /*border-radius: 10px;*/
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
    }

    /* Add Document Button */
    .add-document-btn {
        border: 2px dashed var(--primary-color);
        color: var(--primary-color);
        background: transparent;
        padding: 8px 20px;
        border-radius: 10px;
        transition: all 0.3s ease;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .add-document-btn:hover {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
    }

    /* Custom Documents Container */
    #customDocumentsContainer .row {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 15px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    #customDocumentsContainer .row:hover {
        border-color: var(--primary-color);
        box-shadow: 0 5px 20px rgba(67, 97, 238, 0.1);
    }

    /* Navigation Buttons */
    .form-navigation {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
        padding-top: 20px;
        border-top: 2px solid #e2e8f0;
    }

    .text-right {
        text-align: right;
    }

    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .justify-content-end {
        justify-content: flex-end;
    }

    /* Validation Styles */
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

    /* Form Step Animation */
    .form-step {
        display: none;
        animation: fadeStep 0.3s ease-in-out;
    }

    .form-step.active {
        display: block;
    }

    @keyframes fadeStep {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Small Text */
    .small, small {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    /* HR */
    hr {
        border: none;
        border-top: 2px solid #e2e8f0;
        margin: 20px 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 20px;
        }
        
        .step-card-body {
            padding: 20px;
        }
        
        .step-card-header {
            padding: 12px 20px !important;
        }
        
        .timeline-step span {
            font-size: 11px;
        }
        
        .form-navigation {
            gap: 10px;
        }
        
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 10px;
        }
    }
    
    @media (max-width: 768px) {
        .timeline-wrapper {
            padding: 20px;
        }
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Page Header -->
            <div class="page-header">
                <h5>
                    <i class="bi bi-person-plus-fill"></i>
                    Add Employee Details
                </h5>
            </div>

            <!-- ============================
            STEPPER
            ============================ -->
            <div class="timeline-wrapper">
                <div class="timeline position-relative">
                <div class="timeline-line" id="timelineProgress"></div>
                <div class="timeline-step active" id="step1">
                    <div class="timeline-bullet">01</div>
                    <span>Basic</span>
                </div>
                <div class="timeline-step viewable" id="step2">
                    <div class="timeline-bullet">02</div>
                    <span>Professional</span>
                </div>
                <div class="timeline-step viewable" id="step3">
                    <div class="timeline-bullet">03</div>
                    <span>Contact</span>
                </div>
                <div class="timeline-step viewable" id="step4">
                    <div class="timeline-bullet">04</div>
                    <span>Documents</span>
                </div>
                <div class="timeline-step viewable" id="step5">
                    <div class="timeline-bullet">05</div>
                    <span>Bank</span>
                </div>
            </div>
            </div>
            
            <div class="main-card">
                <div class="main-card-header">
                    <h5>
                        <i class="bi bi-person-badge-fill"></i>
                        Employee Info
                    </h5>
                </div>

                <div class="card-body">
                    <form id="employeeForm" action="{{ route('employees.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <!-- Success Message -->
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <!-- Validation Errors -->
                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <h6 class="mb-2">Please fix the following errors:</h6>
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
                            <div class="step-card">
                                <div class="step-card-header">
                                    <h6><i class="bi bi-person-fill"></i> Basic Details</h6>
                                </div>
                                <div class="step-card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-building-fill"></i> Department Category<span class="text-danger">*</span></label>
                                            <select
                                                class="form-control {{ $errors->has('department_category_id') ? 'is-invalid' : '' }}"
                                                name="department_category_id" id="department_category_id"
                                                onchange="loadDepartments(this.value)" required>
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                <option value="{{ $category->department_category_id }}"
                                                    {{ old('department_category_id') == $category->department_category_id ? 'selected' : '' }}>
                                                    {{ $category->category_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('department_category_id'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('department_category_id') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-building"></i> Department<span class="text-danger">*</span></label>
                                            <select
                                                class="form-control {{ $errors->has('department_id') ? 'is-invalid' : '' }}"
                                                name="department_id" id="department_id" required>
                                                <option value="">Select Department</option>
                                            </select>
                                            @if($errors->has('department_id'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('department_id') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-upc-scan"></i> Employee Code<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text"
                                                    class="form-control {{ $errors->has('employee_code') ? 'is-invalid' : '' }}"
                                                    name="employee_code" id="employee_code"
                                                    value="{{ old('employee_code') }}" required>
                                                <div class="input-group-append">
                                                   <button type="button" class="btn btn-outline-primary"
                                                   id="btnGenerateEmployeeCode">
                                                        <i class="bi bi-sync-alt"></i> Generate
                                                    </button>
                                                </div>
                                            </div>
                                            @if($errors->has('employee_code'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('employee_code') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-person-fill"></i> Name<span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                                name="name" value="{{ old('name') }}" required
                                                placeholder="Enter full name">
                                            @if($errors->has('name'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('name') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-briefcase-fill"></i> Designation<span class="text-danger">*</span></label>
                                            <select
                                                class="form-control {{ $errors->has('designation_id') ? 'is-invalid' : '' }}"
                                                id="designationSelect" name="designation_id"
                                                value="{{ old('designation_id') }}" required>
                                                <option value="">Select Designation</option>
                                                @foreach($designations as $desig)
                                                <option value="{{ $desig->designation_id }}"
                                                    {{ old('designation_id') == $desig->designation_id ? 'selected' : '' }}
                                                    data-name="{{ $desig->designations }}"
                                                    data-roles="{{ $desig->roles }}">
                                                    {{ $desig->designations }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('designation_id'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('designation_id') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-phone-fill"></i> Mobile<span class="text-danger">*</span></label>
                                            <input type="tel"
                                                class="form-control {{ $errors->has('mobile_number') ? 'is-invalid' : '' }}"
                                                name="mobile_number" value="{{ old('mobile_number') }}" required
                                                placeholder="10-digit number">
                                            @if($errors->has('mobile_number'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('mobile_number') }}
                                            </div>
                                            @endif
                                        </div>

                                        <input type="hidden" name="designation" id="designationName"
                                            value="{{ old('designation') }}">
                                        <input type="hidden" name="assigned_role" id="assignedRole"
                                            value="{{ old('assigned_role') }}">

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-envelope-fill"></i> Email<span class="text-danger">*</span></label>
                                            <input type="email"
                                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                                name="email" value="{{ old('email') }}" required
                                                placeholder="example@company.com">
                                            @if($errors->has('email'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('email') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-gender-ambiguous"></i> Gender<span class="text-danger">*</span></label><br>
                                            <div class="form-check form-check-inline">
                                                <input
                                                    class="form-check-input {{ $errors->has('gender') ? 'is-invalid' : '' }}"
                                                    type="radio" name="gender" value="male"
                                                    {{ old('gender') == 'male' ? 'checked' : '' }} required>
                                                <label class="form-check-label">Male</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender"
                                                    value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                                                <label class="form-check-label">Female</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" value="other"
                                                    {{ old('gender') == 'other' ? 'checked' : '' }}>
                                                <label class="form-check-label">Other</label>
                                            </div>
                                            @if($errors->has('gender'))
                                            <div class="invalid-feedback d-block">
                                                {{ $errors->first('gender') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-calendar-fill"></i> Date of Birth<span class="text-danger">*</span></label>
                                            <input type="date"
                                                class="form-control {{ $errors->has('dob') ? 'is-invalid' : '' }}"
                                                name="dob" value="{{ old('dob') }}" required max="{{ date('Y-m-d') }}">
                                            @if($errors->has('dob'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('dob') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-droplet-fill"></i> Blood Group<span class="text-danger">*</span></label>
                                            <select
                                                class="form-control {{ $errors->has('blood_group') ? 'is-invalid' : '' }}"
                                                name="blood_group" required>
                                                <option value="">Select Blood Group</option>
                                                <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+
                                                </option>
                                                <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-
                                                </option>
                                                <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+
                                                </option>
                                                <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-
                                                </option>
                                                <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+
                                                </option>
                                                <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-
                                                </option>
                                                <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>
                                                    AB+</option>
                                                <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>
                                                    AB-</option>
                                            </select>
                                            @if($errors->has('blood_group'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('blood_group') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-flag-fill"></i> Nationality<span class="text-danger">*</span></label>
                                            <select
                                                class="form-control {{ $errors->has('nationality') ? 'is-invalid' : '' }}"
                                                name="nationality" required>
                                                <option value="">Select Nationality</option>
                                                <option value="Indian"
                                                    {{ old('nationality') == 'Indian' ? 'selected' : '' }}>Indian
                                                </option>
                                                <option value="American"
                                                    {{ old('nationality') == 'American' ? 'selected' : '' }}>American
                                                </option>
                                                <option value="British"
                                                    {{ old('nationality') == 'British' ? 'selected' : '' }}>British
                                                </option>
                                                <option value="Canadian"
                                                    {{ old('nationality') == 'Canadian' ? 'selected' : '' }}>Canadian
                                                </option>
                                                <option value="Australian"
                                                    {{ old('nationality') == 'Australian' ? 'selected' : '' }}>
                                                    Australian</option>
                                                <option value="Others"
                                                    {{ old('nationality') == 'Others' ? 'selected' : '' }}>Others
                                                </option>
                                            </select>
                                            @if($errors->has('nationality'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('nationality') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-building-fill"></i> Religion<span class="text-danger">*</span></label>
                                            <select
                                                class="form-control {{ $errors->has('religion') ? 'is-invalid' : '' }}"
                                                name="religion" required>
                                                <option value="">Select Religion</option>
                                                <option value="Hindu"
                                                    {{ old('religion') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                                <option value="Muslim"
                                                    {{ old('religion') == 'Muslim' ? 'selected' : '' }}>Muslim</option>
                                                <option value="Christian"
                                                    {{ old('religion') == 'Christian' ? 'selected' : '' }}>Christian
                                                </option>
                                                <option value="Sikh" {{ old('religion') == 'Sikh' ? 'selected' : '' }}>
                                                    Sikh</option>
                                                <option value="Buddhist"
                                                    {{ old('religion') == 'Buddhist' ? 'selected' : '' }}>Buddhist
                                                </option>
                                                <option value="Jain" {{ old('religion') == 'Jain' ? 'selected' : '' }}>
                                                    Jain</option>
                                                <option value="Jewish"
                                                    {{ old('religion') == 'Jewish' ? 'selected' : '' }}>Jewish</option>
                                                <option value="Others"
                                                    {{ old('religion') == 'Others' ? 'selected' : '' }}>Others</option>
                                                <option value="Not Specified"
                                                    {{ old('religion') == 'Not Specified' ? 'selected' : '' }}>Not
                                                    Specified</option>
                                            </select>
                                            @if($errors->has('religion'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('religion') }}
                                            </div>9000
                                            @endif
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-heart-fill"></i> Marital Status <span class="text-danger">*</span></label>
                                            <select
                                                class="form-control {{ $errors->has('marital_status') ? 'is-invalid' : '' }}"
                                                name="marital_status" id="marital_status" required>
                                                <option value="">Select Marital Status</option>
                                                <option value="single"
                                                    {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single
                                                </option>
                                                <option value="married"
                                                    {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married
                                                </option>
                                                <option value="divorced"
                                                    {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced
                                                </option>
                                                <option value="widowed"
                                                    {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed
                                                </option>
                                            </select>
                                            @if($errors->has('marital_status'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('marital_status') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-people-fill"></i> Number of Dependents</label>
                                            <input type="number"
                                                class="form-control {{ $errors->has('number_of_dependents') ? 'is-invalid' : '' }}"
                                                name="number_of_dependents" id="number_of_dependents"
                                                value="{{ old('number_of_dependents', 0) }}" min="0" max="20" step="1">
                                            @if($errors->has('number_of_dependents'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('number_of_dependents') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3" id="spouse_name_group" style="display: none;">
                                            <label><i class="bi bi-person-heart"></i> Spouse Name</label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('spouse_name') ? 'is-invalid' : '' }}"
                                                name="spouse_name" id="spouse_name" value="{{ old('spouse_name') }}"
                                                maxlength="255">
                                            @if($errors->has('spouse_name'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('spouse_name') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-geo-alt-fill"></i> Address Line 1<span class="text-danger">*</span></label>
                                            <textarea
                                                class="form-control {{ $errors->has('addressline1') ? 'is-invalid' : '' }}"
                                                name="addressline1" required
                                                placeholder="House no, Street, Area">{{ old('addressline1') }}</textarea>
                                            @if($errors->has('addressline1'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('addressline1') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-geo-alt"></i> Address Line 2 <span class="small">(Optional)</span></label>
                                            <textarea class="form-control" name="addressline2"
                                                placeholder="Landmark, Locality">{{ old('addressline2') }}</textarea>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-building"></i> State<span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}"
                                                name="state" value="{{ old('state') }}" required
                                                placeholder="State name">
                                            @if($errors->has('state'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('state') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-building"></i> City<span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}"
                                                name="city" value="{{ old('city') }}" required placeholder="City name">
                                            @if($errors->has('city'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('city') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-upc-scan"></i> Pincode<span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('pincode') ? 'is-invalid' : '' }}"
                                                name="pincode" value="{{ old('pincode') }}" required maxlength="6"
                                                placeholder="6-digit pincode">
                                            @if($errors->has('pincode'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('pincode') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-navigation justify-content-end">
                                        <button type="button" class="btn btn-primary" onclick="nextStep()">
                                            <i class="bi bi-arrow-right"></i> Next
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ============================
                        STEP 2 : PROFESSIONAL
                        ============================ -->
                        <div class="form-step" data-step="2">
                            <div class="step-card">
                                <div class="step-card-header">
                                    <h6><i class="bi bi-briefcase-fill"></i> Professional Details</h6>
                                </div>
                                <div class="step-card-body">
                                    <div class="row">
                                        <!-- Add this after the employment_type field in STEP 2 -->
                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-briefcase-fill"></i> Employment Type<span class="text-danger">*</span></label>
                                            <select
                                                class="form-control {{ $errors->has('employment_type') ? 'is-invalid' : '' }}"
                                                name="employment_type" id="employment_type" required>
                                                <option value="">Select</option>
                                                <option value="Full-time"
                                                    {{ old('employment_type') == 'Full-time' ? 'selected' : '' }}>
                                                    Full-time</option>
                                                <option value="Part-time"
                                                    {{ old('employment_type') == 'Part-time' ? 'selected' : '' }}>
                                                    Part-time</option>
                                                <option value="Contract-based"
                                                    {{ old('employment_type') == 'Contract-based' ? 'selected' : '' }}>
                                                    Contract-based</option>
                                                <option value="Probation-Period"
                                                    {{ old('employment_type') == 'Probation-Period' ? 'selected' : '' }}>
                                                    Probation Period</option>
                                            </select>
                                            @if($errors->has('employment_type'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('employment_type') }}
                                            </div>
                                            @endif
                                        </div>

                                        <!-- Add this new field for probation days -->
                                        <div class="col-md-6 mb-3" id="probation_days_wrapper" style="display: {{ old('employment_type') == 'Probation-Period' ? 'block' : 'none' }};">
                                            <label><i class="bi bi-calendar-week-fill"></i> Probation Period (Days)<span class="text-danger">*</span></label>
                                            <input type="number"
                                                class="form-control {{ $errors->has('probation_days') ? 'is-invalid' : '' }}"
                                                name="probation_days" id="probation_days"
                                                value="{{ old('probation_days', 90) }}" min="1" max="365" step="1"
                                                placeholder="Enter number of days (e.g., 90)">
                                            <small class="text-muted">60 days (2 months), 90 days (3 months)</small>
                                            @if($errors->has('probation_days'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('probation_days') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-cash-stack"></i> Salary Type<span class="text-danger">*</span></label>
                                            <select
                                                class="form-control {{ $errors->has('salary_type') ? 'is-invalid' : '' }}"
                                                name="salary_type" required>
                                                <option value="">Select</option>
                                                <option value="Monthly"
                                                    {{ old('salary_type') == 'Monthly' ? 'selected' : '' }}>Monthly
                                                </option>
                                                <option value="Hourly"
                                                    {{ old('salary_type') == 'Hourly' ? 'selected' : '' }}>Hourly
                                                </option>
                                            </select>
                                            @if($errors->has('salary_type'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('salary_type') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-calendar-fill"></i> Date of Joining<span class="text-danger">*</span></label>
                                            <input type="date"
                                                class="form-control {{ $errors->has('doj') ? 'is-invalid' : '' }}"
                                                name="doj" value="{{ old('doj') }}" required>
                                            @if($errors->has('doj'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('doj') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-upc-scan"></i> PF Number (UAN) <span class="small">(Optional)</span></label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('previous_pf_number') ? 'is-invalid' : '' }}"
                                                name="previous_pf_number" value="{{ old('previous_pf_number') }}">
                                            @if($errors->has('previous_pf_number'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('previous_pf_number') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-upc-scan"></i> ESI Number <span class="small">(Optional)</span></label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('esi_number') ? 'is-invalid' : '' }}"
                                                name="esi_number" value="{{ old('esi_number') }}">
                                            @if($errors->has('esi_number'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('esi_number') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-building"></i> Previous Employer Name <span class="small">(Optional)</span></label>
                                            <input type="text" class="form-control" name="previous_employer_name"
                                                value="{{ old('previous_employer_name') }}">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-calendar"></i> Date of Exit <span class="small">(Last Organization) (Optional)</span></label>
                                            <input type="date" class="form-control" name="previous_exit_date"
                                                value="{{ old('previous_exit_date') }}">
                                        </div>
                                    </div>

                                    <div class="form-navigation">
                                        <button type="button" class="btn btn-secondary" onclick="prevStep()">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                        <button type="button" class="btn btn-primary" onclick="nextStep()">
                                            <i class="bi bi-arrow-right"></i> Next
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ============================
                        STEP 3 : CONTACT
                        ============================ -->
                        <div class="form-step" data-step="3">
                            <div class="step-card">
                                <div class="step-card-header">
                                    <h6><i class="bi bi-telephone-fill"></i> Contact Details</h6>
                                </div>
                                <div class="step-card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label><i class="bi bi-phone-fill"></i> Emergency Contact Number<span class="text-danger">*</span></label>
                                            <input type="tel"
                                                class="form-control {{ $errors->has('emergency_contact_number') ? 'is-invalid' : '' }}"
                                                name="emergency_contact_number"
                                                value="{{ old('emergency_contact_number') }}" required
                                                placeholder="10-digit number">
                                            @if($errors->has('emergency_contact_number'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('emergency_contact_number') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label><i class="bi bi-person-fill"></i> Contact Person Name<span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('contact_person_name') ? 'is-invalid' : '' }}"
                                                name="contact_person_name" value="{{ old('contact_person_name') }}"
                                                required>
                                            @if($errors->has('contact_person_name'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('contact_person_name') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label><i class="bi bi-people-fill"></i> Relation<span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('relation_with_contact') ? 'is-invalid' : '' }}"
                                                name="relation_with_contact" value="{{ old('relation_with_contact') }}"
                                                required>
                                            @if($errors->has('relation_with_contact'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('relation_with_contact') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-person"></i> Reference Name</label>
                                            <input type="text" class="form-control" name="reference_name"
                                                value="{{ old('reference_name') }}">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-phone"></i> Reference Contact Number</label>
                                            <input type="tel" class="form-control" name="reference_contact_number"
                                                value="{{ old('reference_contact_number') }}">
                                        </div>
                                    </div>

                                    <div class="form-navigation">
                                        <button type="button" class="btn btn-secondary" onclick="prevStep()">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                        <button type="button" class="btn btn-primary" onclick="nextStep()">
                                            <i class="bi bi-arrow-right"></i> Next
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ============================
                        STEP 4 : DOCUMENTS
                        ============================ -->
                        <div class="form-step" data-step="4">
                            <div class="step-card">
                                <div class="step-card-header">
                                    <h6><i class="bi bi-file-earmark-person-fill"></i> Document Details</h6>
                                </div>
                                <div class="step-card-body">
                                    <!-- Aadhaar & PAN -->
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-qr-code"></i> Aadhaar Card <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" name="aadhaar_card">
                                            @if($errors->has('aadhaar_card'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('aadhaar_card') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-upc-scan"></i> Aadhaar Number <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('aadhaar_number') ? 'is-invalid' : '' }}"
                                                name="aadhaar_number" value="{{ old('aadhaar_number') }}" 
                                                placeholder="12-digit number">
                                            @if($errors->has('aadhaar_number'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('aadhaar_number') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-credit-card-fill"></i> PAN Card <span class="text-danger">*</span></label>
                                            <input type="file"
                                                class="form-control {{ $errors->has('pan_card') ? 'is-invalid' : '' }}"
                                                name="pan_card" >
                                            @if($errors->has('pan_card'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('pan_card') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-upc-scan"></i> PAN Number <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('pan_number') ? 'is-invalid' : '' }}"
                                                name="pan_number" value="{{ old('pan_number') }}" 
                                                placeholder="ABCDE1234F">
                                            @if($errors->has('pan_number'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('pan_number') }}
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-camera-fill"></i> Upload Profile Photo<span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" name="profile_photo">
                                            @if($errors->has('profile_photo'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('profile_photo') }}
                                            </div>
                                            @endif
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-camera-fill"></i> Upload Signature<span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" name="upload_signature">
                                            @if($errors->has('upload_signature'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('upload_signature') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Communication Address Same -->
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-check-circle"></i> Is Communication Address Same? <span class="text-danger">*</span></label>
                                            <select
                                                class="form-control {{ $errors->has('is_address_same') ? 'is-invalid' : '' }}"
                                                name="is_address_same" id="is_address_same" required>
                                                <option value="">Select</option>
                                                <option value="yes"
                                                    {{ old('is_address_same') == 'yes' ? 'selected' : '' }}>Yes</option>
                                                <option value="no"
                                                    {{ old('is_address_same') == 'no' ? 'selected' : '' }}>No</option>
                                            </select>
                                            @if($errors->has('is_address_same'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('is_address_same') }}
                                            </div>
                                            @endif
                                        </div>

                                        <!-- Address Proof Type -->
                                        <div class="col-md-6 d-none" id="addressProofTypeWrapper">
                                            <div class="mb-3">
                                                <label><i class="bi bi-file-earmark"></i> Address Proof Type</label>
                                                <select
                                                    class="form-control {{ $errors->has('address_proof_type') ? 'is-invalid' : '' }}"
                                                    id="address_proof_type" name="address_proof_type">
                                                    <option value="">Select Document Type</option>
                                                    <option value="driving_license"
                                                        {{ old('address_proof_type') == 'driving_license' ? 'selected' : '' }}>
                                                        Driving License</option>
                                                    <option value="passport"
                                                        {{ old('address_proof_type') == 'passport' ? 'selected' : '' }}>
                                                        Passport</option>
                                                    <option value="voter_id"
                                                        {{ old('address_proof_type') == 'voter_id' ? 'selected' : '' }}>
                                                        Voter ID</option>
                                                </select>
                                                @if($errors->has('address_proof_type'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('address_proof_type') }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Address Proof Upload -->
                                    <div class="row d-none" id="addressProofFileWrapper">
                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-file-earmark-arrow-up"></i> Upload Address Proof</label>
                                            <input type="file"
                                                class="form-control {{ $errors->has('address_proof_file') ? 'is-invalid' : '' }}"
                                                id="address_proof_file" name="address_proof_file">
                                            @if($errors->has('address_proof_file'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('address_proof_file') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-upc-scan"></i> Address Proof Number</label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('address_proof_number') ? 'is-invalid' : '' }}"
                                                name="address_proof_number" value="{{ old('address_proof_number') }}">
                                            @if($errors->has('address_proof_number'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('address_proof_number') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="my-2">
                                        <div class="d-flex w-100 justify-content-between mb-3">
                                            <h6 class="fw-bold"><i class="bi bi-files-fill"></i> Additional Documents</h6>
                                            <button type="button" class="btn btn-outline-primary add-document-btn"
                                                onclick="addDocumentRow()">
                                                <i class="bi bi-plus-lg"></i> Add Document
                                            </button>
                                        </div>
                                        <div id="customDocumentsContainer"></div>
                                    </div>

                                    <!-- Navigation -->
                                    <div class="form-navigation">
                                        <button type="button" class="btn btn-secondary" onclick="prevStep()">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                        <button type="button" class="btn btn-primary" onclick="nextStep()">
                                            <i class="bi bi-arrow-right"></i> Next
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ============================
                        STEP 5 : BANK
                        ============================ -->
                        <div class="form-step" data-step="5">
                            <div class="step-card">
                                <div class="step-card-header">
                                    <h6><i class="bi bi-bank2"></i> Bank Details</h6>
                                </div>
                                <div class="step-card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-building-fill"></i> Bank Name</label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('bank_name') ? 'is-invalid' : '' }}"
                                                name="bank_name" value="{{ old('bank_name') }}">
                                            @if($errors->has('bank_name'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('bank_name') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-building"></i> Branch Name</label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('branch_name') ? 'is-invalid' : '' }}"
                                                name="branch_name" value="{{ old('branch_name') }}">
                                            @if($errors->has('branch_name'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('branch_name') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-credit-card-fill"></i> Account Number</label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('account_number') ? 'is-invalid' : '' }}"
                                                name="account_number" value="{{ old('account_number') }}">
                                            @if($errors->has('account_number'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('account_number') }}
                                            </div>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label><i class="bi bi-upc-scan"></i> IFSC Code</label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('ifsc_code') ? 'is-invalid' : '' }}"
                                                name="ifsc_code" value="{{ old('ifsc_code') }}">
                                            @if($errors->has('ifsc_code'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('ifsc_code') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-navigation">
                                        <button type="button" class="btn btn-secondary" onclick="prevStep()">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-check-circle-fill"></i> Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // ALL JAVASCRIPT REMAINS EXACTLY THE SAME - NOT CHANGED
    let currentStep = 0;
    let maxCompletedStep = 0;
    let documentIndex = 0;
    let editableStep = 0;
    const steps = document.querySelectorAll('.form-step');
    const timelineSteps = document.querySelectorAll('.timeline-step');
    const progressLine = document.getElementById('timelineProgress');

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
        sessionStorage.setItem(STEP_KEY, index);

        requestAnimationFrame(updateStepAccessibility);
    }

    // Toggle spouse name field based on marital status
    document.addEventListener('DOMContentLoaded', function() {
        const maritalStatus = document.getElementById('marital_status');
        const spouseGroup = document.getElementById('spouse_name_group');
        const spouseInput = document.getElementById('spouse_name');
        
        function toggleSpouseField() {
            if (maritalStatus.value === 'married') {
                spouseGroup.style.display = 'block';
                spouseInput.setAttribute('data-required', 'true');
            } else {
                spouseGroup.style.display = 'none';
                spouseInput.removeAttribute('data-required');
                spouseInput.value = '';
            }
            
            if (typeof saveFormData === 'function') {
                saveFormData();
            }
        }
        
        if (maritalStatus) {
            toggleSpouseField();
            maritalStatus.addEventListener('change', toggleSpouseField);
        }
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        const employmentTypeSelect = document.getElementById('employment_type');
        const probationDaysWrapper = document.getElementById('probation_days_wrapper');
        const probationDaysInput = document.getElementById('probation_days');
    
        function toggleProbationDaysField() {
            if (employmentTypeSelect.value === 'Probation-Period') {
                probationDaysWrapper.style.display = 'block';
                probationDaysInput.setAttribute('required', 'required');
                probationDaysInput.setAttribute('data-required', 'true');
    
                // Set default value if empty
                if (!probationDaysInput.value) {
                    probationDaysInput.value = 90;
                }
            } else {
                probationDaysWrapper.style.display = 'none';
                probationDaysInput.removeAttribute('required');
                probationDaysInput.removeAttribute('data-required');
                // Optionally clear the value when not in probation
                // probationDaysInput.value = '';
            }
    
            // Save form data
            if (typeof saveFormData === 'function') {
                saveFormData();
            }
        }
    
        if (employmentTypeSelect) {
            toggleProbationDaysField();
            employmentTypeSelect.addEventListener('change', toggleProbationDaysField);
        }
    });

    /* ============================
    VALIDATION FUNCTION FOR NEXT BUTTON
    ============================ */
    function validateCurrentStepFields() {
        const currentStepElement = steps[currentStep];
        const inputs = currentStepElement.querySelectorAll('input, select, textarea');
        let isValid = true;

        inputs.forEach(input => {
            input.classList.remove('is-invalid');
            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.remove();
            }
        });

        inputs.forEach(input => {
            if (input.disabled) return;
            if (!input.required) return;
            
            if (input.required && !input.value.trim()) {
                showFieldError(input, 'This field is required');
                isValid = false;
                return;
            }

            if (input.type === 'email' && input.value.trim()) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(input.value.trim())) {
                    showFieldError(input, 'Please enter a valid email address');
                    isValid = false;
                }
            }

            if (input.name === 'mobile_number' || input.name === 'emergency_contact_number') {
                const mobileRegex = /^[0-9]{10}$/;
                if (input.value.trim() && !mobileRegex.test(input.value.trim())) {
                    showFieldError(input, 'Please enter a valid 10-digit mobile number');
                    isValid = false;
                }
            }

            if (input.name === 'pincode' && input.value.trim()) {
                const pincodeRegex = /^[0-9]{6}$/;
                if (!pincodeRegex.test(input.value.trim())) {
                    showFieldError(input, 'Please enter a valid 6-digit pincode');
                    isValid = false;
                }
            }

            if (input.type === 'date' && input.name === 'dob' && input.value) {
                const selectedDate = new Date(input.value);
                const today = new Date();
                if (selectedDate > today) {
                    showFieldError(input, 'Date of birth cannot be in the future');
                    isValid = false;
                }
            }
            
            if (currentStep === 0) {
                const maritalStatus = document.querySelector('[name="marital_status"]');
                if (maritalStatus && maritalStatus.required && !maritalStatus.value) {
                    showFieldError(maritalStatus, 'Please select marital status');
                    isValid = false;
                }
                
                const dependents = document.querySelector('[name="number_of_dependents"]');
                if (dependents && dependents.value) {
                    const depValue = parseInt(dependents.value);
                    if (depValue < 0 || depValue > 20) {
                        showFieldError(dependents, 'Number of dependents must be between 0 and 20');
                        isValid = false;
                    }
                }
            }
            
            if (currentStep === 1) {
                const employmentType = document.querySelector('[name="employment_type"]');
                const probationDays = document.querySelector('[name="probation_days"]');
    
                if (employmentType && employmentType.value === 'Probation-Period') {
                    if (probationDays && (!probationDays.value || probationDays.value < 1 || probationDays.value > 365)) {
                        showFieldError(probationDays, 'Please enter a valid probation period between 1 and 365 days');
                        isValid = false;
                    }
                }
            }

            if (input.tagName === 'SELECT' && input.required) {
                if (!input.value || input.value === "") {
                    showFieldError(input, 'Please select an option');
                    isValid = false;
                }
            }

            if (input.type === 'radio' && input.required) {
                const radioGroup = document.querySelectorAll(`input[name="${input.name}"]`);
                const isRadioSelected = Array.from(radioGroup).some(radio => radio.checked);
                const radioContainer = radioGroup[0].closest('.mb-3');

                if (!isRadioSelected) {
                    if (radioContainer && !radioContainer.querySelector('.invalid-feedback')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback d-block';
                        errorDiv.textContent = 'Please select an option';
                        radioContainer.appendChild(errorDiv);
                    }
                    isValid = false;
                } else if (radioContainer) {
                    const existingError = radioContainer.querySelector('.invalid-feedback');
                    if (existingError) existingError.remove();
                }
            }
        });

        if (!isValid) {
            const firstError = currentStepElement.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                firstError.focus();
            }
        }

        return isValid;
    }

    function showFieldError(input, message) {
        input.classList.add('is-invalid');

        let errorDiv = input.nextElementSibling;
        if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            input.parentNode.insertBefore(errorDiv, input.nextSibling);
        }

        errorDiv.textContent = message;
    }

    function nextStep() {
        if (!validateCurrentStepFields()) {
            return;
        }

        if (editableStep < steps.length - 1) {
            editableStep++;
            maxCompletedStep = Math.max(maxCompletedStep, editableStep);

            sessionStorage.setItem('employee_max_step', maxCompletedStep);
            sessionStorage.setItem('employee_editable_step', editableStep);

            showStep(editableStep);
        }
    }

    function prevStep() {
        if (currentStep > 0) {
            showStep(currentStep - 1);
        }
    }

    timelineSteps.forEach((step, index) => {
        step.addEventListener('click', () => {
            showStep(index);
            updateStepAccessibility();
        });
    });

    document.getElementById("btnGenerateEmployeeCode").addEventListener("click", function() {
        const year = new Date().getFullYear();
        const random = Math.floor(1000 + Math.random() * 9000);
        const code = `EMP-${year}-${random}`;
        document.getElementById("employee_code").value = code;
    });

    const designationSelect = document.getElementById('designationSelect');
    const designationNameInput = document.getElementById('designationName');
    const assignedRoleInput = document.getElementById('assignedRole');

    designationSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        designationNameInput.value = selectedOption.getAttribute('data-name') || '';
        const role = selectedOption.getAttribute('data-roles');

        assignedRoleInput.value = role && role !== 'null' ? role : 'employee';
    });

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

                data.departments.forEach(dept => {
                    const selected = selectedDepartmentId == dept.department_id ? 'selected' : '';
                    options += `<option value="${dept.department_id}" ${selected}>${dept.department}</option>`;
                });

                departmentSelect.innerHTML = options;

                requestAnimationFrame(updateStepAccessibility);
            })
            .catch(() => {
                departmentSelect.innerHTML = '<option value="">Error loading</option>';
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const selectedCategory = document.getElementById('department_category_id').value;
        if (selectedCategory) {
            loadDepartments(selectedCategory);
        }
    });

    document.getElementById('is_address_same').addEventListener('change', function() {
        const proofTypeWrapper = document.getElementById('addressProofTypeWrapper');
        const proofFileWrapper = document.getElementById('addressProofFileWrapper');
        const proofType = document.getElementById('address_proof_type');
        const proofFile = document.getElementById('address_proof_file');

        if (this.value === 'no') {
            proofTypeWrapper.classList.remove('d-none');
            proofFileWrapper.classList.remove('d-none');

            proofType.setAttribute('required', 'required');
            proofFile.setAttribute('required', 'required');
        } else {
            proofTypeWrapper.classList.add('d-none');
            proofFileWrapper.classList.add('d-none');

            proofType.removeAttribute('required');
            proofFile.removeAttribute('required');

            proofType.value = '';
            proofFile.value = '';
        }
    });

    const STORAGE_KEY = 'employee_form_data';
    const STEP_KEY = 'employee_current_step';

    function saveFormData() {
        const form = document.getElementById('employeeForm');
        const data = {};

        form.querySelectorAll('input, select, textarea').forEach(el => {
            if (el.type === 'file') return;

            if (el.type === 'radio') {
                if (el.checked) {
                    data[el.name] = el.value;
                }
            } else {
                data[el.name] = el.value;
            }
        });

        sessionStorage.setItem(STORAGE_KEY, JSON.stringify(data));
        sessionStorage.setItem(STEP_KEY, currentStep);
    }

    function restoreFormData() {
        if (document.querySelector('.alert-danger')) {
            return;
        }

        const savedData = sessionStorage.getItem(STORAGE_KEY);
        if (!savedData) return;
        const savedStep = sessionStorage.getItem(STEP_KEY);
        const savedMaxStep = sessionStorage.getItem('employee_max_step');

        if (savedMaxStep !== null) {
            maxCompletedStep = parseInt(savedMaxStep);
        }

        if (!savedData) return;

        const data = JSON.parse(savedData);
        const form = document.getElementById('employeeForm');

        form.querySelectorAll('input, select, textarea').forEach(el => {
            if (el.type === 'file') return;

            if (el.type === 'radio') {
                el.checked = data[el.name] === el.value;
            } else if (data[el.name] !== undefined) {
                el.value = data[el.name];
            }
        });

        if (data.department_category_id) {
            document.getElementById('department_category_id').value = data.department_category_id;

            loadDepartments(
                data.department_category_id,
                data.department_id
            );
        }

        if (data.is_address_same === 'no') {
            document.getElementById('addressProofTypeWrapper')?.classList.remove('d-none');
            document.getElementById('addressProofFileWrapper')?.classList.remove('d-none');

            document.getElementById('address_proof_type')?.setAttribute('required', 'required');
            document.getElementById('address_proof_file')?.setAttribute('required', 'required');
        }

        if (savedStep !== null) {
            showStep(parseInt(savedStep));
        } else {
            showStep(0);
        }
    }

    setTimeout(() => {
        updateStepAccessibility();
    }, 0);

    document.getElementById('employeeForm').addEventListener('input', saveFormData);
    document.getElementById('employeeForm').addEventListener('change', saveFormData);

    document.getElementById('employeeForm').addEventListener('submit', function() {
        sessionStorage.removeItem(STORAGE_KEY);
        sessionStorage.removeItem(STEP_KEY);
    });

    document.addEventListener('DOMContentLoaded', restoreFormData);

    function updateStepAccessibility() {
        steps.forEach((step, index) => {
            const inputs = step.querySelectorAll('input, select, textarea');

            if (index <= maxCompletedStep) {
                inputs.forEach(el => el.disabled = false);
            } else {
                inputs.forEach(el => el.disabled = true);
            }
        });
    }

    function addDocumentRow() {
        const container = document.getElementById('customDocumentsContainer');

        const row = document.createElement('div');
        row.classList.add('row', 'mb-3');

        row.innerHTML = `
                <div class="col-md-4">
                    <label>Document Name</label>
                    <input type="text"
                        class="form-control"
                        name="documents[${documentIndex}][name]"
                        placeholder="e.g. Experience Letter">
                </div>

                <div class="col-md-4">
                    <label>Document Number</label>
                    <input type="text"
                        class="form-control"
                        name="documents[${documentIndex}][number]"
                        placeholder="Document Number">
                </div>

                <div class="col-md-3">
                    <label>Upload File</label>
                    <input type="file"
                        class="form-control"
                        name="documents[${documentIndex}][file]">
                </div>

                <div class="col-md-1 text-right" style="place-content: end;">
                    <button type="button"
                            class="btn btn-danger btn-sm"
                            onclick="this.closest('.row').remove()">
                        ✕
                    </button>
                </div>
            `;

        container.appendChild(row);

        if (currentStep !== steps.length - 1) {
            row.querySelectorAll('input').forEach(el => el.disabled = true);
        }

        documentIndex++;

        updateStepAccessibility();
    }
</script>
@endsection