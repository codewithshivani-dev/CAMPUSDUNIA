<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employee Self-Onboarding Portal | Join Our Team</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5, #3730a3);
            --primary-color: #4f46e5;
            --secondary-color: #3730a3;
            --accent-color: #06b6d4;
            --success-gradient: linear-gradient(135deg, #10b981, #047857);
            --success-color: #10b981;
            --danger-gradient: linear-gradient(135deg, #ef4444, #b91c1c);
            --bg-body: #f8fafc;
            --card-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.07);
        }

        body {
            background-color: var(--bg-body);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #1e293b;
            min-height: 100vh;
            padding-bottom: 50px;
        }

        /* Top Hero Header */
        .portal-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
            padding: 45px 0 65px;
            color: white;
            position: relative;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.2);
        }

        .portal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 80% 20%, rgba(99, 102, 241, 0.15), transparent 40%);
            pointer-events: none;
        }

        .portal-header .portal-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.3px;
            margin-bottom: 12px;
        }

        .portal-header .portal-title {
            font-size: 32px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .portal-header .portal-subtitle {
            font-size: 15px;
            color: #cbd5e1;
            margin-top: 6px;
            max-width: 600px;
        }

        /* Container adjustment */
        .main-wrapper {
            margin-top: -35px;
            z-index: 10;
            position: relative;
        }

        /* Stepper Navigation */
        .timeline-wrapper {
            background: white;
            border-radius: 20px;
            padding: 24px 30px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
            border: 1px solid #f1f5f9;
        }

        .timeline {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin: 10px 0 15px;
        }

        .timeline-line {
            position: absolute;
            top: 20px;
            left: 8%;
            height: 4px;
            background: var(--primary-gradient);
            z-index: 0;
            transition: width 0.4s ease;
            width: 0%;
            border-radius: 4px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 8%;
            width: 84%;
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
            background: #f1f5f9;
            color: #64748b;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.3s;
            border: 3px solid white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .timeline-step.active .timeline-bullet {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.35);
            transform: scale(1.12);
        }

        .timeline-step.completed .timeline-bullet {
            background: var(--success-gradient);
            color: white;
        }

        .timeline-step span {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            transition: all 0.3s;
        }

        .timeline-step.active span {
            color: var(--primary-color);
            font-weight: 700;
        }

        .timeline-step.completed span {
            color: var(--success-color);
        }

        /* Main Form Card */
        .main-card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .main-card-header {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            padding: 20px 30px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .main-card-header h5 {
            color: #0f172a;
            margin: 0;
            font-weight: 700;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .main-card-header h5 i {
            color: var(--primary-color);
            font-size: 1.25rem;
        }

        .card-body {
            padding: 32px;
        }

        /* Sub Card Sections */
        .section-box {
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            padding: 22px;
            background: #ffffff;
            margin-bottom: 20px;
            transition: all 0.2s ease;
        }

        .section-box:hover {
            border-color: #cbd5e1;
        }

        .section-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 10px;
        }

        .section-title i {
            color: var(--primary-color);
        }

        /* Form Labels & Controls */
        label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 7px;
            font-size: 13px;
            display: block;
        }

        label i {
            color: var(--primary-color);
            margin-right: 4px;
        }

        .form-control,
        .form-select {
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            transition: all 0.2s ease;
            background-color: #fff;
            color: #0f172a;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
            outline: none;
        }

        .form-control::placeholder {
            color: #94a3b8;
            font-size: 13.5px;
        }

        .text-danger {
            color: #ef4444 !important;
        }

        /* Radio Buttons */
        .form-check-inline {
            margin-right: 20px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            border: 1.5px solid #cbd5e1;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* OTP Box Styles */
        .otp-verification-box {
            background: #f8fafc;
            border: 1.5px dashed #cbd5e1;
            border-radius: 12px;
            padding: 16px;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .otp-verification-box.active {
            border-color: var(--primary-color);
            background: #f0f3ff;
        }

        .verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
            font-weight: 700;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 50px;
        }

        /* Buttons */
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            padding: 11px 28px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(79, 70, 229, 0.35);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 11px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        .btn-success {
            background: var(--success-gradient);
            border: none;
            padding: 11px 32px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14.5px;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
            transition: all 0.2s ease;
        }

        .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-verify-trigger {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            font-weight: 600;
            font-size: 13px;
            white-space: nowrap;
        }

        /* Camera Modal */
        .camera-option {
            margin-top: 8px;
        }

        .camera-btn {
            background: linear-gradient(135deg, #0284c7, #0369a1);
            border: none;
            border-radius: 8px;
            padding: 7px 14px;
            font-size: 12.5px;
            font-weight: 600;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .camera-btn:hover {
            background: linear-gradient(135deg, #0369a1, #075985);
        }

        .camera-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.85);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
            margin: 0px;
        }

        .camera-modal.active {
            display: flex;
        }

        .camera-container {
            background: #1a1a2e;
            border-radius: 16px;
            width: min(95vw, 720px);
            padding: 20px;
            text-align: center;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }

        .camera-preview {
            width: 100%;
            max-height: 70vh;
            border-radius: 12px;
            background: #000;
            border: 2px solid #4361ee;
        }

        .camera-stage {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            background: #0f172a;
        }

        .face-guide {
            position: absolute;
            inset: 12% 24%;
            border: 4px solid rgba(255, 255, 255, 0.75);
            border-radius: 50%;
            pointer-events: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .face-guide.ready {
            border-color: #22c55e;
            box-shadow: 0 0 0 999px rgba(34, 197, 94, 0.08), 0 0 24px rgba(34, 197, 94, 0.75);
        }

        .landmark-overlay {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 2;
        }

        .face-guide {
            z-index: 3;
        }

        .face-status {
            min-height: 20px;
            margin-top: 10px;
            color: #ffffff;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
        }

        .face-status.countdown {
            color: #fcfcfc;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .camera-controls {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 15px;
        }

        .capture-btn {
            background: var(--success-gradient);
            color: white;
            padding: 8px 22px;
            border-radius: 30px;
            border: none;
            font-weight: 600;
        }

        .capture-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .close-camera {
            background: #e2e8f0;
            color: #475569;
            padding: 8px 20px;
            border-radius: 30px;
            border: none;
            font-weight: 600;
        }

        .image-preview {
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .image-preview img {
            width: 54px;
            height: 54px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #e2e8f0;
        }

        .gps-status {
            display: none;
            margin-top: 8px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            align-items: center;
            gap: 6px;
        }

        .gps-status.success {
            display: inline-flex;
            background: #dcfce7;
            color: #15803d;
        }

        .gps-status.error {
            display: inline-flex;
            background: #fee2e2;
            color: #b91c1c;
        }

        .form-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .form-step {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }

        .form-step.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .is-invalid {
            border-color: #ef4444 !important;
        }

        .invalid-feedback {
            font-size: 12px;
            color: #ef4444;
            margin-top: 4px;
        }
    </style>
</head>

<body>

    <!-- Portal Top Header -->
    <div class="portal-header text-center">
        <div class="container position-relative">
            <div class="portal-badge">
                <i class="bi bi-shield-lock-fill"></i> Self-Service Registration Portal
            </div>
            <h1 class="portal-title">Employee Self-Onboarding</h1>
            <p class="portal-subtitle mx-auto">
                Welcome! Please complete your details below to register your profile and complete your employment
                onboarding process.
            </p>
        </div>
    </div>

    <!-- Main Form Wrapper -->
    <div class="container main-wrapper">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">

                <!-- Timeline / Stepper Header -->
                <div class="timeline-wrapper">
                    <div class="timeline">
                        <div class="timeline-line" id="timelineProgress"></div>
                        <div class="timeline-step active" id="step1">
                            <div class="timeline-bullet">01</div>
                            <span>Personal Details</span>
                        </div>
                        <div class="timeline-step" id="step2">
                            <div class="timeline-bullet">02</div>
                            <span>Professional</span>
                        </div>
                        <div class="timeline-step" id="step3">
                            <div class="timeline-bullet">03</div>
                            <span>Contact & Address</span>
                        </div>
                        <div class="timeline-step" id="step4">
                            <div class="timeline-bullet">04</div>
                            <span>Verification & Docs</span>
                        </div>
                        <div class="timeline-step" id="step5">
                            <div class="timeline-bullet">05</div>
                            <span>Bank Account</span>
                        </div>
                    </div>
                </div>

                <!-- Main Card -->
                <div class="main-card mb-5">
                    <div class="main-card-header">
                        <h5>
                            <i class="bi bi-person-lines-fill"></i>
                            <span id="stepTitleHeader">Step 1: Personal & General Details</span>
                        </h5>
                        <span class="badge bg-primary rounded-pill px-3 py-2" id="stepCounterBadge">Step 1 of 5</span>
                    </div>

                    <div class="card-body">
                        <form id="employeeForm" action="{{ route('external.onboarding.store') }}" method="POST"
                            enctype="multipart/form-data" novalidate>
                            @csrf
                            <input type="hidden" name="institute_id" value="{{ $instituteId ?? 'ABC1234' }}">
                            @if(!empty($leadPrefill))
                                <input type="hidden" name="department_category_id"
                                    value="{{ $leadPrefill['department_category_id'] ?? '' }}">
                                <input type="hidden" name="department_id" value="{{ $leadPrefill['department_id'] ?? '' }}">
                                <input type="hidden" name="designation_id"
                                    value="{{ $leadPrefill['designation_id'] ?? '' }}">
                                <input type="hidden" name="designation" value="{{ $leadPrefill['designation'] ?? '' }}">
                                <div class="alert alert-info border-0 mb-4">
                                    <i class="bi bi-shield-check me-2"></i>
                                    Your verified lead information has been loaded. These fields cannot be changed here.
                                </div>
                            @endif

                            <!-- Success Alert -->
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-check-circle-fill fs-5"></i>
                                        <div>{{ session('success') }}</div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Error Alert -->
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                                        <div>{{ session('error') }}</div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Error Summary -->
                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
                                    <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        Submission Errors:</div>
                                    <ul class="mb-0 ps-3">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- ====================================================
                            STEP 1 : PERSONAL DETAILS
                            ==================================================== -->
                            <div class="form-step active" data-step="1">
                                <div class="section-box">
                                    <div class="section-title">
                                        <i class="bi bi-person-vcard-fill"></i> Basic Identity Information
                                    </div>
                                    <div class="row g-3">
                                        <!-- Full Name -->
                                        <div class="col-md-12">
                                            <label><i class="bi bi-person-fill"></i> Full Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                                name="name" id="name"
                                                value="{{ old('name', $leadPrefill['name'] ?? '') }}" required
                                                @if(!empty($leadPrefill['name'])) readonly @endif
                                                placeholder="Enter full legal name">
                                            @if($errors->has('name'))
                                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>

                                        <!-- Mobile Number (verified during lead process) -->
                                        <div class="col-md-6">
                                            <label><i class="bi bi-telephone-fill"></i> Mobile Phone Number <span
                                                    class="text-danger">*</span></label>
                                            <input type="tel"
                                                class="form-control {{ $errors->has('mobile_number') ? 'is-invalid' : '' }}"
                                                name="mobile_number" id="mobile_number_input"
                                                value="{{ old('mobile_number', $leadPrefill['mobile_number'] ?? '') }}"
                                                required maxlength="10" @if(!empty($leadPrefill['mobile_number']))
                                                readonly @endif placeholder="10-digit mobile number">
                                            @if($errors->has('mobile_number'))
                                                <div class="invalid-feedback d-block">{{ $errors->first('mobile_number') }}
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Email Address (verified during lead process) -->
                                        <div class="col-md-6">
                                            <label><i class="bi bi-envelope-fill"></i> Email Address <span
                                                    class="text-danger">*</span></label>
                                            <input type="email"
                                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                                name="email" id="email_input"
                                                value="{{ old('email', $leadPrefill['email'] ?? '') }}" required
                                                @if(!empty($leadPrefill['email'])) readonly @endif
                                                placeholder="name@example.com">
                                            @if($errors->has('email'))
                                                <div class="invalid-feedback d-block">{{ $errors->first('email') }}</div>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <label><i class="bi bi-gender-ambiguous"></i> Gender <span
                                                    class="text-danger">*</span></label>
                                            <div class="pt-2">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="gender"
                                                        value="male" {{ old('gender') == 'male' ? 'checked' : '' }}
                                                        required>
                                                    <label class="form-check-label mb-0">Male</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="gender"
                                                        value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                                                    <label class="form-check-label mb-0">Female</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="gender"
                                                        value="other" {{ old('gender') == 'other' ? 'checked' : '' }}>
                                                    <label class="form-check-label mb-0">Other</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label><i class="bi bi-calendar-event-fill"></i> Date of Birth <span
                                                    class="text-danger">*</span></label>
                                            <input type="date"
                                                class="form-control {{ $errors->has('dob') ? 'is-invalid' : '' }}"
                                                name="dob" id="dob" value="{{ old('dob', $leadPrefill['dob'] ?? '') }}"
                                                required @if(!empty($leadPrefill['dob'])) readonly @endif
                                                max="{{ date('Y-m-d') }}">
                                            @if($errors->has('dob'))
                                                <div class="invalid-feedback">{{ $errors->first('dob') }}</div>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <label><i class="bi bi-flag-fill"></i> Nationality <span
                                                    class="text-danger">*</span></label>
                                            <select
                                                class="form-select {{ $errors->has('nationality') ? 'is-invalid' : '' }}"
                                                name="nationality" required>
                                                <option value="">Select Nationality</option>
                                                <option value="Indian" {{ old('nationality', 'Indian') == 'Indian' ? 'selected' : '' }}>Indian</option>
                                                <option value="American" {{ old('nationality') == 'American' ? 'selected' : '' }}>American</option>
                                                <option value="British" {{ old('nationality') == 'British' ? 'selected' : '' }}>British</option>
                                                <option value="Canadian" {{ old('nationality') == 'Canadian' ? 'selected' : '' }}>Canadian</option>
                                                <option value="Australian" {{ old('nationality') == 'Australian' ? 'selected' : '' }}>Australian</option>
                                                <option value="Others" {{ old('nationality') == 'Others' ? 'selected' : '' }}>Others</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label><i class="bi bi-person-badge"></i> Religion <span
                                                    class="text-danger">*</span></label>
                                            <select
                                                class="form-select {{ $errors->has('religion') ? 'is-invalid' : '' }}"
                                                name="religion" required>
                                                <option value="">Select Religion</option>
                                                <option value="Hindu" {{ old('religion') == 'Hindu' ? 'selected' : '' }}>
                                                    Hindu</option>
                                                <option value="Muslim" {{ old('religion') == 'Muslim' ? 'selected' : '' }}>Muslim</option>
                                                <option value="Christian" {{ old('religion') == 'Christian' ? 'selected' : '' }}>Christian</option>
                                                <option value="Sikh" {{ old('religion') == 'Sikh' ? 'selected' : '' }}>
                                                    Sikh</option>
                                                <option value="Buddhist" {{ old('religion') == 'Buddhist' ? 'selected' : '' }}>Buddhist</option>
                                                <option value="Jain" {{ old('religion') == 'Jain' ? 'selected' : '' }}>
                                                    Jain</option>
                                                <option value="Others" {{ old('religion') == 'Others' ? 'selected' : '' }}>Others</option>
                                                <option value="Not Specified" {{ old('religion') == 'Not Specified' ? 'selected' : '' }}>Not Specified</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label><i class="bi bi-heart-fill"></i> Marital Status <span
                                                    class="text-danger">*</span></label>
                                            <select
                                                class="form-select {{ $errors->has('marital_status') ? 'is-invalid' : '' }}"
                                                name="marital_status" id="marital_status" required>
                                                <option value="">Select Marital Status</option>
                                                <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                                                <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                                                <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                                <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label><i class="bi bi-people-fill"></i> Number of Dependents</label>
                                            <input type="number" class="form-control" name="number_of_dependents"
                                                id="number_of_dependents" value="{{ old('number_of_dependents', 0) }}"
                                                min="0" max="20">
                                        </div>

                                        <div class="col-md-6" id="spouse_name_group" style="display: none;">
                                            <label><i class="bi bi-person-heart"></i> Spouse Name</label>
                                            <input type="text" class="form-control" name="spouse_name" id="spouse_name"
                                                value="{{ old('spouse_name') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Health Record (Optional Medical Notes) -->
                                <div class="section-box">
                                    <div class="section-title">
                                        <i class="bi bi-heart-pulse-fill"></i> Health & Medical Information
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label><i class="bi bi-droplet-fill"></i> Blood Group <span
                                                    class="text-danger">*</span></label>
                                            <select
                                                class="form-select {{ $errors->has('blood_group') ? 'is-invalid' : '' }}"
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
                                        </div>

                                        <div class="col-md-4">
                                            <label>Height</label>
                                            <div class="input-group">
                                                <input type="number" name="height_cm" class="form-control" min="0"
                                                    step="0.1" placeholder="cm" value="{{ old('height_cm') }}">
                                                <select name="height_unit" class="form-select" style="max-width: 90px;">
                                                    <option value="cm" {{ old('height_unit', 'cm') == 'cm' ? 'selected' : '' }}>cm</option>
                                                    <option value="ft_in" {{ old('height_unit') == 'ft_in' ? 'selected' : '' }}>ft/in</option>
                                                </select>
                                            </div>
                                            <div class="height-ft-in d-none mt-2">
                                                <div class="input-group">
                                                    <input type="number" name="height_feet" class="form-control" min="0"
                                                        step="1" placeholder="Feet" value="{{ old('height_feet') }}">
                                                    <input type="number" name="height_inches" class="form-control"
                                                        min="0" max="11.9" step="0.1" placeholder="Inches"
                                                        value="{{ old('height_inches') }}">
                                                </div>
                                            </div>
                                            <input type="hidden" name="height" value="{{ old('height') }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Weight</label>
                                            <div class="input-group">
                                                <input type="number" name="weight_input" class="form-control" min="0"
                                                    step="0.1" placeholder="Weight" value="{{ old('weight_input') }}">
                                                <select name="weight_unit" class="form-select" style="max-width: 90px;">
                                                    <option value="kg" {{ old('weight_unit', 'kg') == 'kg' ? 'selected' : '' }}>kg</option>
                                                    <option value="lbs" {{ old('weight_unit') == 'lbs' ? 'selected' : '' }}>lbs</option>
                                                </select>
                                            </div>
                                            <input type="hidden" name="weight" value="{{ old('weight') }}">
                                        </div>

                                        <div class="col-md-6">
                                            <label>Health Condition (Optional)</label>
                                            <input type="text" name="health_condition" class="form-control"
                                                placeholder="Any existing health condition"
                                                value="{{ old('health_condition') }}">
                                        </div>

                                        <div class="col-md-6">
                                            <label>Known Allergies (Optional)</label>
                                            <input type="text" name="allergies" class="form-control"
                                                placeholder="e.g. Penicillin, Dust, Food allergies"
                                                value="{{ old('allergies') }}">
                                        </div>

                                        <!-- Optional Medical Notes -->
                                        <div class="col-md-12">
                                            <label>Medical Notes (Optional)</label>
                                            <textarea name="medical_notes" class="form-control" rows="2"
                                                placeholder="Additional medical information or special requirements">{{ old('medical_notes') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-navigation justify-content-end">
                                    <button type="button" class="btn btn-primary" onclick="nextStep()">
                                        Next Step <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- ====================================================
                            STEP 2 : PROFESSIONAL DETAILS
                            ==================================================== -->
                            <div class="form-step" data-step="2">
                                <div class="section-box">
                                    <div class="section-title">
                                        <i class="bi bi-briefcase-fill"></i> Professional Information
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <div class="alert alert-info border-0">
                                                <i class="bi bi-info-circle-fill me-2"></i>
                                                Your employment type, joining date, and salary structure will be
                                                assigned by the HR team after your onboarding is complete.
                                            </div>
                                        </div>

                                        <!-- Previous Employment Details -->
                                        <div class="col-md-6">
                                            <label>Previous Employer Name (Optional)</label>
                                            <input type="text" class="form-control" name="previous_employer_name"
                                                placeholder="Last company name"
                                                value="{{ old('previous_employer_name', $leadPrefill['previous_employer_name'] ?? '') }}"
                                                @if(!empty($leadPrefill['previous_employer_name'])) readonly @endif>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Last Working Date (Optional)</label>
                                            <input type="date" class="form-control" name="previous_exit_date"
                                                value="{{ old('previous_exit_date') }}">
                                        </div>

                                        <div class="col-md-6">
                                            <label>PF UAN Number (Optional)</label>
                                            <input type="text" class="form-control" name="previous_pf_number"
                                                placeholder="12-digit UAN" value="{{ old('previous_pf_number') }}">
                                        </div>

                                        <div class="col-md-6">
                                            <label>ESI Number (Optional)</label>
                                            <input type="text" class="form-control" name="esi_number"
                                                placeholder="17-digit ESI" value="{{ old('esi_number') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-navigation">
                                    <button type="button" class="btn btn-secondary" onclick="prevStep()">
                                        <i class="bi bi-arrow-left"></i> Previous
                                    </button>
                                    <button type="button" class="btn btn-primary" onclick="nextStep()">
                                        Next Step <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- ====================================================
                            STEP 3 : CONTACT & ADDRESS
                            ==================================================== -->
                            <div class="form-step" data-step="3">
                                <div class="section-box">
                                    <div class="section-title">
                                        <i class="bi bi-telephone-exclamation-fill"></i> Emergency Contact Information
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label><i class="bi bi-phone-vibrate-fill"></i> Emergency Contact Phone
                                                <span class="text-danger">*</span></label>
                                            <input type="tel"
                                                class="form-control {{ $errors->has('emergency_contact_number') ? 'is-invalid' : '' }}"
                                                name="emergency_contact_number"
                                                value="{{ old('emergency_contact_number') }}" required
                                                placeholder="10-digit phone number">
                                        </div>

                                        <div class="col-md-4">
                                            <label><i class="bi bi-person-fill"></i> Contact Person Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('contact_person_name') ? 'is-invalid' : '' }}"
                                                name="contact_person_name" value="{{ old('contact_person_name') }}"
                                                required placeholder="Full name of contact">
                                        </div>

                                        <div class="col-md-4">
                                            <label><i class="bi bi-people-fill"></i> Relationship <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('relation_with_contact') ? 'is-invalid' : '' }}"
                                                name="relation_with_contact" value="{{ old('relation_with_contact') }}"
                                                required placeholder="Parent / Spouse / Sibling">
                                        </div>
                                    </div>
                                </div>

                                <div class="section-box">
                                    <div class="section-title">
                                        <i class="bi bi-geo-alt-fill"></i> Residential Address
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label>Address Line 1 <span class="text-danger">*</span></label>
                                            <textarea
                                                class="form-control {{ $errors->has('addressline1') ? 'is-invalid' : '' }}"
                                                name="addressline1" required rows="2"
                                                placeholder="Flat, House no., Building, Street">{{ old('addressline1') }}</textarea>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Address Line 2 (Optional)</label>
                                            <textarea class="form-control" name="addressline2" rows="2"
                                                placeholder="Locality, Area, Landmark">{{ old('addressline2') }}</textarea>
                                        </div>

                                        <div class="col-md-4">
                                            <label>City <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}"
                                                name="city" value="{{ old('city') }}" required placeholder="City">
                                        </div>

                                        <div class="col-md-4">
                                            <label>State <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}"
                                                name="state" value="{{ old('state') }}" required placeholder="State">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Pincode <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control {{ $errors->has('pincode') ? 'is-invalid' : '' }}"
                                                name="pincode" value="{{ old('pincode') }}" required maxlength="6"
                                                placeholder="6-digit Pincode">
                                        </div>
                                    </div>
                                </div>

                                <div class="section-box">
                                    <div class="section-title">
                                        <i class="bi bi-person-bounding-box"></i> Personal Reference (Optional)
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label>Reference Name</label>
                                            <input type="text" class="form-control" name="reference_name"
                                                value="{{ old('reference_name') }}" placeholder="Reference full name">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Reference Phone Number</label>
                                            <input type="tel" class="form-control" name="reference_contact_number"
                                                value="{{ old('reference_contact_number') }}"
                                                placeholder="10-digit number">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-navigation">
                                    <button type="button" class="btn btn-secondary" onclick="prevStep()">
                                        <i class="bi bi-arrow-left"></i> Previous
                                    </button>
                                    <button type="button" class="btn btn-primary" onclick="nextStep()">
                                        Next Step <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- ====================================================
                            STEP 4 : VERIFICATION & DOCUMENTS
                            ==================================================== -->
                            <div class="form-step" data-step="4">
                                <div class="section-box">
                                    <div class="section-title">
                                        <i class="bi bi-file-earmark-person-fill"></i> Identity & Government Documents
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label><i class="bi bi-card-heading"></i> Aadhaar Number</label>
                                            <input type="text" class="form-control" name="aadhaar_number"
                                                value="{{ old('aadhaar_number') }}"
                                                placeholder="12-digit Aadhaar number">
                                        </div>

                                        <div class="col-md-6">
                                            <label><i class="bi bi-file-earmark-arrow-up"></i> Upload Aadhaar
                                                Copy</label>
                                            <input type="file" class="form-control" name="aadhaar_card"
                                                accept=".jpg,.jpeg,.png,.pdf">
                                        </div>

                                        <div class="col-md-6">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <label class="mb-0">
                                                    <i class="bi bi-credit-card-2-front-fill"></i>
                                                    PAN Number
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <span id="panVerifiedBadge" class="verified-badge d-none">
                                                    <i class="bi bi-check-circle-fill"></i>
                                                    Verified
                                                </span>
                                            </div>

                                            <div class="input-group">
                                                <input type="hidden" name="pan_card_status" id="pan_card_status_input"
                                                    value="{{ old('pan_card_status') }}">
                                                <input type="text"
                                                    class="form-control {{ $errors->has('pan_number') ? 'is-invalid' : '' }}"
                                                    name="pan_number" id="pan_number_input"
                                                    value="{{ old('pan_number') }}"
                                                    placeholder="10-character PAN (e.g. ABCDE1234F)" maxlength="10"
                                                    required style="text-transform: uppercase;">

                                                <button type="button" class="btn btn-outline-primary" id="verifyPanBtn"
                                                    onclick="verifyPan()">

                                                    <i class="bi bi-shield-check me-1"></i>
                                                    Verify PAN
                                                </button>
                                            </div>

                                            @if($errors->has('pan_number'))
                                                <div class="invalid-feedback d-block">
                                                    {{ $errors->first('pan_number') }}
                                                </div>
                                            @endif

                                            <div id="panVerifyMsg" class="mt-2 small"></div>
                                        </div>

                                        <div class="col-md-6">
                                            <label><i class="bi bi-file-earmark-arrow-up"></i> Upload PAN Copy</label>
                                            <input type="file" class="form-control" name="pan_card"
                                                accept=".jpg,.jpeg,.png,.pdf">
                                        </div>
                                    </div>
                                </div>

                                <div class="section-box">
                                    <div class="section-title">
                                        <i class="bi bi-camera-fill"></i> Profile Photo Capture & Digital Signature
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label><i class="bi bi-person-square"></i> Profile Photo <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" class="form-control" name="profile_photo"
                                                id="profile_photo_input" accept="image/*" required>
                                            <div class="camera-option">
                                                <button type="button" class="camera-btn" id="openCameraBtn">
                                                    <i class="bi bi-camera"></i> Capture via WebCam
                                                </button>
                                                <button type="button" class="camera-btn d-none" id="retryCameraBtn">
                                                    <i class="bi bi-arrow-clockwise"></i> Retry Selfie
                                                </button>
                                            </div>
                                            <div id="profile_photo_preview" class="image-preview"></div>
                                            <div id="gpsStatus" class="gps-status">
                                                <i class="bi bi-geo-alt-fill"></i>
                                                <span id="gpsStatusText"></span>
                                                <span id="gpsCoordsDisplay" class="ms-1"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label><i class="bi bi-pen-fill"></i> Digital Signature Image</label>
                                            <input type="file" class="form-control" name="upload_signature"
                                                id="upload_signature_input" accept="image/*">
                                            <div id="upload_signature_preview" class="image-preview"></div>
                                        </div>
                                    </div>

                                    <!-- Camera Modal -->
                                    <div id="cameraModal" class="camera-modal">
                                        <div class="camera-container">
                                            <h6 class="fw-bold mb-3"><i class="bi bi-camera-fill me-1"></i> Capture
                                                Photo</h6>
                                            <div id="captureTimerStatus" class="face-status countdown"
                                                aria-live="polite"></div>
                                            <div class="camera-stage">
                                                <video id="video" class="camera-preview" autoplay playsinline></video>
                                                <canvas id="landmarkCanvas" class="landmark-overlay"></canvas>
                                                <div id="faceGuide" class="face-guide"></div>
                                            </div>
                                            <div id="faceStatus" class="face-status">Loading face detection...</div>
                                            <canvas id="canvas" style="display: none;"></canvas>
                                            <div class="camera-controls">
                                                <button type="button" id="closeCameraBtn" class="close-camera"><i
                                                        class="bi bi-x-lg me-1"></i> Cancel</button>
                                                <button type="button" id="retryCameraModalBtn"
                                                    class="close-camera d-none"><i class="bi bi-arrow-clockwise"></i>
                                                    Retry</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="latitude" id="latitude" value="">
                                <input type="hidden" name="longitude" id="longitude" value="">
                                <input type="hidden" name="timestamp" id="timestamp" value="">

                                <div class="section-box">
                                    <div class="section-title">
                                        <i class="bi bi-house-check-fill"></i> Address Proof & Additional Documents
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label>Is Communication Address same as Permanent? <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" name="is_address_same" id="is_address_same"
                                                required>
                                                <option value="">Select Option</option>
                                                <option value="yes" {{ old('is_address_same', 'yes') == 'yes' ? 'selected' : '' }}>Yes</option>
                                                <option value="no" {{ old('is_address_same') == 'no' ? 'selected' : '' }}>
                                                    No</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 d-none" id="addressProofTypeWrapper">
                                            <label>Address Proof Document Type</label>
                                            <select class="form-select" id="address_proof_type"
                                                name="address_proof_type">
                                                <option value="">Select Document Type</option>
                                                <option value="driving_license">Driving License</option>
                                                <option value="passport">Passport</option>
                                                <option value="voter_id">Voter ID</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 d-none" id="addressProofFileWrapper">
                                            <label>Upload Address Proof File</label>
                                            <input type="file" class="form-control" id="address_proof_file"
                                                name="address_proof_file" accept=".jpg,.jpeg,.png,.pdf">
                                        </div>

                                        <div class="col-md-6 d-none" id="addressProofNumberWrapper">
                                            <label>Address Proof Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="address_proof_number"
                                                name="address_proof_number"
                                                value="{{ old('address_proof_number', $leadPrefill['address_proof_number'] ?? '') }}"
                                                maxlength="50" placeholder="Enter the number shown on the document">
                                            @if($errors->has('address_proof_number'))
                                                <div class="invalid-feedback d-block">
                                                    {{ $errors->first('address_proof_number') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="fw-bold mb-0"><i class="bi bi-folder-plus"></i> Additional
                                                Qualification / Experience Certificates</h6>
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                onclick="addDocumentRow()">
                                                <i class="bi bi-plus-lg me-1"></i> Add Document
                                            </button>
                                        </div>
                                        <div id="customDocumentsContainer"></div>
                                    </div>
                                </div>

                                <div class="form-navigation">
                                    <button type="button" class="btn btn-secondary" onclick="prevStep()">
                                        <i class="bi bi-arrow-left"></i> Previous
                                    </button>
                                    <button type="button" class="btn btn-primary" onclick="nextStep()">
                                        Next Step <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- ====================================================
                            STEP 5 : BANKING INFORMATION
                            ==================================================== -->
                            <div class="form-step" data-step="5">
                                <div class="section-box">
                                    <div class="section-title">
                                        <i class="bi bi-bank2"></i> Salary Disbursement Bank Details
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label><i class="bi bi-building"></i> Bank Name</label>
                                            <input type="text" class="form-control" name="bank_name"
                                                value="{{ old('bank_name') }}"
                                                placeholder="e.g. State Bank of India, HDFC Bank">
                                        </div>

                                        <div class="col-md-6">
                                            <label><i class="bi bi-geo-alt"></i> Branch Name</label>
                                            <input type="text" class="form-control" name="branch_name"
                                                value="{{ old('branch_name') }}" placeholder="Branch locality">
                                        </div>

                                        <div class="col-md-6">
                                            <label><i class="bi bi-credit-card-fill"></i> Bank Account Number</label>
                                            <input type="text" class="form-control" name="account_number"
                                                value="{{ old('account_number') }}" placeholder="Account number">
                                        </div>

                                        <div class="col-md-6">
                                            <label><i class="bi bi-upc-scan"></i> IFSC Code</label>
                                            <input type="text" class="form-control" name="ifsc_code"
                                                value="{{ old('ifsc_code') }}" placeholder="e.g. SBIN0001234">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-navigation">
                                    <button type="button" class="btn btn-secondary" onclick="prevStep()">
                                        <i class="bi bi-arrow-left"></i> Previous
                                    </button>
                                    <button type="submit" class="btn btn-success" id="submitBtn">
                                        <i class="bi bi-check-circle-fill me-1"></i> Submit Registration
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Footer Security Notice -->
                <div class="text-center text-muted small mt-4">
                    <i class="bi bi-shield-check text-success me-1"></i> Your submitted information is securely
                    encrypted and stored for administrative onboarding purposes only.
                </div>
            </div>
        </div>
    </div>

    <!-- Load MediaPipe Face Mesh from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/face_mesh.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Setup CSRF header for Ajax requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const leadPrefill = @json($leadPrefill ?? []);

        function applyLeadPrefill() {
            Object.entries(leadPrefill).forEach(([name, value]) => {
                document.querySelectorAll(`[name="${name}"]`).forEach((field) => {
                    if (field.type === 'radio') {
                        field.checked = field.value == value;
                    } else if (field.type !== 'file') {
                        field.value = value;
                    }

                    if (field.type === 'select-one' || field.type === 'select-multiple') {
                        field.disabled = true;
                    } else if (field.type !== 'hidden') {
                        field.readOnly = true;
                    }
                });
            });
        }

        applyLeadPrefill();

        // Verification States
        let isMobileVerified = false;
        let isEmailVerified = false;

        // Timers
        let mobileTimerInterval = null;
        let emailTimerInterval = null;
        // =============================================
        // PAN VERIFICATION STATE
        // =============================================
        let isPanVerified = false;
        let panVerificationCompulsory = 1; // default; updated after first verify attempt or config load

        // =============================================
        // VERIFY PAN (Only on Button Click)
        // =============================================
        function verifyPan() {

            const panInput = document.getElementById('pan_number_input');
            const nameInput = document.getElementById('name');
            const dobInput = document.getElementById('dob');
            const verifyBtn = document.getElementById('verifyPanBtn');
            const verifiedBadge = document.getElementById('panVerifiedBadge');
            const messageBox = document.getElementById('panVerifyMsg');

            if (!panInput || !verifyBtn || !messageBox) {
                console.error('PAN verification elements not found.');
                return;
            }

            const pan = panInput.value.trim().toUpperCase();
            const name = nameInput ? nameInput.value.trim() : '';
            const dob = dobInput ? dobInput.value.trim() : '';

            // ----- Frontend validation -----
            if (!pan) {
                messageBox.innerHTML = '<span class="text-danger">Please enter your PAN number.</span>';
                panInput.focus();
                return;
            }

            const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
            if (!panRegex.test(pan)) {
                messageBox.innerHTML = '<span class="text-danger">Please enter a valid PAN format (e.g., ABCDE1234F).</span>';
                panInput.focus();
                return;
            }

            if (!name) {
                messageBox.innerHTML = '<span class="text-danger">Please enter your name before verifying PAN.</span>';
                nameInput.focus();
                return;
            }

            if (!dob) {
                messageBox.innerHTML = '<span class="text-danger">Please enter your date of birth before verifying PAN.</span>';
                dobInput.focus();
                return;
            }

            // ----- Loading state -----
            verifyBtn.disabled = true;
            verifyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Verifying...';
            messageBox.innerHTML = '<span class="text-muted">Verifying PAN, please wait...</span>';

            // ----- AJAX call -----
            fetch('{{ route("external.onboarding.verify-pan") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    pan_number: pan,
                    name: name,
                    dob: dob
                })
            })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'PAN verification failed.');
                    }
                    return data;
                })
                .then(data => {
                    console.log('PAN Verification Response:', data);
                    // ✅ Config = 0 case → backend says "skipped"
                    if (data.success && data.skipped) {

                        panVerificationCompulsory = 0;
                        applyPanConfig();
                        messageBox.innerHTML = '<span class="text-muted">PAN verification is disabled.</span>';
                        return;
                    }

                    if (data.success) {
                        // ✅ PAN verified
                        isPanVerified = true;
                        panVerificationCompulsory = 1;

                        const panStatusEl = document.getElementById('pan_card_status_input');
                        if (panStatusEl) panStatusEl.value = 'verified';

                        panInput.value = pan;
                        panInput.readOnly = true;
                        verifiedBadge.classList.remove('d-none');

                        verifyBtn.disabled = true;
                        verifyBtn.classList.remove('btn-outline-primary');
                        verifyBtn.classList.add('btn-success');
                        verifyBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Verified';

                        messageBox.innerHTML = '<span class="text-success">' +
                            '<i class="bi bi-check-circle-fill me-1"></i>' +
                            data.message + '</span>';
                    } else {
                        // ❌ Verification failed
                        isPanVerified = false;
                        const panStatusEl = document.getElementById('pan_card_status_input');
                        if (panStatusEl) panStatusEl.value = 'unverified';
                        messageBox.innerHTML = '<span class="text-danger">' + data.message + '</span>';
                        verifyBtn.disabled = false;
                        verifyBtn.innerHTML = '<i class="bi bi-shield-check me-1"></i> Verify PAN';
                    }
                })
                .catch(error => {
                    isPanVerified = false;
                    messageBox.innerHTML = '<span class="text-danger">' + error.message + '</span>';
                    verifyBtn.disabled = false;
                    verifyBtn.innerHTML = '<i class="bi bi-shield-check me-1"></i> Verify PAN';
                });
        }

        // =============================================
        // APPLY PAN CONFIG TO UI
        // =============================================
        function applyPanConfig() {
            const panInput = document.getElementById('pan_number_input');
            const verifyBtn = document.getElementById('verifyPanBtn');
            const requiredStar = document.getElementById('panRequiredStar');

            if (panVerificationCompulsory === 1) {
                panInput?.setAttribute('required', 'required');
                if (verifyBtn) verifyBtn.style.display = 'inline-block';
                if (requiredStar) requiredStar.style.display = 'inline';
            } else {
                panInput?.removeAttribute('required');
                if (verifyBtn) verifyBtn.style.display = 'none';
                if (requiredStar) requiredStar.style.display = 'none';

                // Clear previous verification state
                isPanVerified = false;
                document.getElementById('panVerifiedBadge')?.classList.add('d-none');
            }
        }

        // =============================================
        // OTP AJAX HANDLERS & TIMERS
        // =============================================

        // Send Mobile OTP
        function sendMobileOtp() {
            const mobileInput = document.getElementById('mobile_number_input');
            const mobile = mobileInput ? mobileInput.value.trim() : '';

            if (!mobile || !/^[6-9]\d{9}$/.test(mobile)) {
                showFieldError(mobileInput, 'Please enter a valid 10-digit mobile number');
                return;
            }

            const sendBtn = document.getElementById('sendMobileOtpBtn');
            sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Sending...';
            sendBtn.disabled = true;

            fetch('{{ route("external.onboarding.send-mobile-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ mobile_number: mobile })
            })
                .then(res => res.json())
                .then(data => {
                    sendBtn.innerHTML = '<i class="bi bi-send-fill me-1"></i> Resend OTP';
                    if (data.success) {
                        document.getElementById('mobileOtpBox').style.display = 'block';
                        document.getElementById('mobileOtpMsg').className = 'mt-2 small text-success font-weight-bold';
                        document.getElementById('mobileOtpMsg').textContent = data.message;
                        startMobileTimer(120); // 2 minutes timer
                    } else {
                        sendBtn.disabled = false;
                        document.getElementById('mobileOtpMsg').className = 'mt-2 small text-danger';
                        document.getElementById('mobileOtpMsg').textContent = data.message;
                    }
                })
                .catch(err => {
                    sendBtn.innerHTML = '<i class="bi bi-send-fill me-1"></i> Verify Mobile';
                    sendBtn.disabled = false;
                    alert('Error sending mobile OTP. Please try again.');
                });
        }

        // Verify Mobile OTP
        function verifyMobileOtp() {
            const mobile = document.getElementById('mobile_number_input')?.value.trim();
            const otpInput = document.getElementById('mobile_otp_input');
            const otp = otpInput ? otpInput.value.trim() : '';

            if (!otp || otp.length !== 4) {
                document.getElementById('mobileOtpMsg').className = 'mt-2 small text-danger';
                document.getElementById('mobileOtpMsg').textContent = 'Please enter valid 4-digit OTP code.';
                return;
            }

            const verifyBtn = document.getElementById('verifyMobileOtpBtn');
            verifyBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Verifying...';
            verifyBtn.disabled = true;

            fetch('{{ route("external.onboarding.verify-mobile-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ mobile_number: mobile, otp: otp })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        isMobileVerified = true;
                        clearInterval(mobileTimerInterval);
                        document.getElementById('mobileOtpBox').style.display = 'none';
                        document.getElementById('mobileVerifiedBadge').classList.remove('d-none');
                        document.getElementById('sendMobileOtpBtn').style.display = 'none';
                        document.getElementById('mobile_number_input').readOnly = true;
                        sessionStorage.setItem('external_mobile_verified', 'true');
                    } else {
                        verifyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Verify OTP';
                        verifyBtn.disabled = false;
                        document.getElementById('mobileOtpMsg').className = 'mt-2 small text-danger';
                        document.getElementById('mobileOtpMsg').textContent = data.message;
                    }
                })
                .catch(err => {
                    verifyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Verify OTP';
                    verifyBtn.disabled = false;
                    alert('Error verifying OTP.');
                });
        }

        // Mobile Timer (2 Minutes = 120 seconds)
        function startMobileTimer(seconds) {
            clearInterval(mobileTimerInterval);
            const resendBtn = document.getElementById('resendMobileBtn');
            const timerSpan = document.getElementById('mobileTimer');
            resendBtn.disabled = true;
            let remaining = seconds;

            mobileTimerInterval = setInterval(() => {
                remaining--;
                const mins = Math.floor(remaining / 60);
                const secs = remaining % 60;
                timerSpan.textContent = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;

                if (remaining <= 0) {
                    clearInterval(mobileTimerInterval);
                    timerSpan.textContent = '00:00';
                    resendBtn.disabled = false;
                    document.getElementById('mobileTimerContainer').innerHTML = '<span class="text-danger fw-bold"><i class="bi bi-exclamation-circle me-1"></i> OTP Expired. Click Resend OTP.</span>';
                }
            }, 1000);
        }

        // Send Email OTP
        function sendEmailOtp() {
            const emailInput = document.getElementById('email_input');
            const email = emailInput ? emailInput.value.trim() : '';

            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showFieldError(emailInput, 'Please enter a valid email address');
                return;
            }

            const sendBtn = document.getElementById('sendEmailOtpBtn');
            sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Sending...';
            sendBtn.disabled = true;

            fetch('{{ route("external.onboarding.send-email-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ email: email })
            })
                .then(res => res.json())
                .then(data => {
                    sendBtn.innerHTML = '<i class="bi bi-send-fill me-1"></i> Resend OTP';
                    if (data.success) {
                        document.getElementById('emailOtpBox').style.display = 'block';
                        document.getElementById('emailOtpMsg').className = 'mt-2 small text-success font-weight-bold';
                        document.getElementById('emailOtpMsg').textContent = data.message;
                        startEmailTimer(120); // 2 minutes timer
                    } else {
                        sendBtn.disabled = false;
                        document.getElementById('emailOtpMsg').className = 'mt-2 small text-danger';
                        document.getElementById('emailOtpMsg').textContent = data.message;
                    }
                })
                .catch(err => {
                    sendBtn.innerHTML = '<i class="bi bi-send-fill me-1"></i> Verify Email';
                    sendBtn.disabled = false;
                    alert('Error sending email OTP. Please try again.');
                });
        }

        // Verify Email OTP
        function verifyEmailOtp() {
            const email = document.getElementById('email_input')?.value.trim();
            const otpInput = document.getElementById('email_otp_input');
            const otp = otpInput ? otpInput.value.trim() : '';

            if (!otp || otp.length !== 4) {
                document.getElementById('emailOtpMsg').className = 'mt-2 small text-danger';
                document.getElementById('emailOtpMsg').textContent = 'Please enter valid 4-digit OTP code.';
                return;
            }

            const verifyBtn = document.getElementById('verifyEmailOtpBtn');
            verifyBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Verifying...';
            verifyBtn.disabled = true;

            fetch('{{ route("external.onboarding.verify-email-otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ email: email, otp: otp })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        isEmailVerified = true;
                        clearInterval(emailTimerInterval);
                        document.getElementById('emailOtpBox').style.display = 'none';
                        document.getElementById('emailVerifiedBadge').classList.remove('d-none');
                        document.getElementById('sendEmailOtpBtn').style.display = 'none';
                        document.getElementById('email_input').readOnly = true;
                        sessionStorage.setItem('external_email_verified', 'true');
                    } else {
                        verifyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Verify OTP';
                        verifyBtn.disabled = false;
                        document.getElementById('emailOtpMsg').className = 'mt-2 small text-danger';
                        document.getElementById('emailOtpMsg').textContent = data.message;
                    }
                })
                .catch(err => {
                    verifyBtn.innerHTML = '<i class="bi bi-check-lg"></i> Verify OTP';
                    verifyBtn.disabled = false;
                    alert('Error verifying OTP.');
                });
        }

        // Email Timer (2 Minutes = 120 seconds)
        function startEmailTimer(seconds) {
            clearInterval(emailTimerInterval);
            const resendBtn = document.getElementById('resendEmailBtn');
            const timerSpan = document.getElementById('emailTimer');
            resendBtn.disabled = true;
            let remaining = seconds;

            emailTimerInterval = setInterval(() => {
                remaining--;
                const mins = Math.floor(remaining / 60);
                const secs = remaining % 60;
                timerSpan.textContent = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;

                if (remaining <= 0) {
                    clearInterval(emailTimerInterval);
                    timerSpan.textContent = '00:00';
                    resendBtn.disabled = false;
                    document.getElementById('emailTimerContainer').innerHTML = '<span class="text-danger fw-bold"><i class="bi bi-exclamation-circle me-1"></i> OTP Expired. Click Resend OTP.</span>';
                }
            }, 1000);
        }

        // =============================================
        // STEPPER ENGINE — FIXED
        // =============================================
        let currentStep = 0;
        let maxCompletedStep = 0;
        let editableStep = 0;
        let documentIndex = 0;

        const steps = document.querySelectorAll('.form-step');
        const timelineSteps = document.querySelectorAll('.timeline-step');
        const progressLine = document.getElementById('timelineProgress');
        const stepCounterBadge = document.getElementById('stepCounterBadge');
        const stepTitleHeader = document.getElementById('stepTitleHeader');

        const stepTitles = [
            "Step 1: Personal & General Details",
            "Step 2: Professional Background",
            "Step 3: Contact & Address Information",
            "Step 4: Verification & Identity Documents",
            "Step 5: Salary Disbursement Bank Account"
        ];

        function showStep(index) {
            if (index < 0 || index >= steps.length) return;

            steps.forEach((step, i) => step.classList.toggle('active', i === index));

            timelineSteps.forEach((step, i) => {
                step.classList.remove('active', 'completed');
                if (i < index) step.classList.add('completed');
                else if (i === index) step.classList.add('active');
            });

            const progressPercent = (index / (timelineSteps.length - 1)) * 84;
            if (progressLine) progressLine.style.width = progressPercent + '%';
            if (stepCounterBadge) stepCounterBadge.textContent = `Step ${index + 1} of ${steps.length}`;
            if (stepTitleHeader && stepTitles[index]) stepTitleHeader.textContent = stepTitles[index];

            currentStep = index;
            sessionStorage.setItem('external_onboarding_step', index);
            updateStepAccessibility();
        }

        // Next button — only advances AFTER validation
        function nextStep() {
            if (!validateCurrentStepFields(currentStep)) return;

            saveFormData();

            if (currentStep < steps.length - 1) {
                const next = currentStep + 1;
                // Mark the CURRENT step as completed before moving
                maxCompletedStep = Math.max(maxCompletedStep, next);
                editableStep = next;

                sessionStorage.setItem('external_onboarding_max_step', maxCompletedStep);
                sessionStorage.setItem('external_onboarding_editable_step', editableStep);

                showStep(next);
                document.querySelector('.main-card')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function prevStep() {
            if (currentStep > 0) {
                saveFormData();
                showStep(currentStep - 1);
                document.querySelector('.main-card')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // Timeline navigation: ONLY allow going back to completed steps
        timelineSteps.forEach((step, index) => {
            step.addEventListener('click', () => {
                // User can only navigate to steps they've already completed (not future ones)
                if (index <= maxCompletedStep) {
                    saveFormData();
                    showStep(index);
                }
            });
        });

        // =============================================
        // VALIDATION — FIXED (returns boolean properly)
        // =============================================
        function validateCurrentStepFields(stepIndex) {
            const stepEl = steps[stepIndex];
            if (!stepEl) return true;

            const inputs = stepEl.querySelectorAll('input, select, textarea');
            let isValid = true;

            // Clear old errors
            inputs.forEach(input => {
                input.classList.remove('is-invalid');
                const sib = input.nextElementSibling;
                if (sib && sib.classList.contains('invalid-feedback')) sib.remove();
            });

            inputs.forEach(input => {
                if (input.disabled) return;
                // Skip hidden inputs (like lat/lng/timestamp)
                if (input.type === 'hidden') return;

                // Skip conditionally hidden fields (parent has d-none)
                if (input.closest('.d-none')) return;

                if (input.required && !input.value.trim()) {
                    // Special-case: checkbox / radio
                    if (input.type === 'radio') {
                        const group = stepEl.querySelectorAll(`input[name="${input.name}"]`);
                        const checked = Array.from(group).some(r => r.checked);
                        if (!checked) {
                            showFieldError(input, 'Please select an option');
                            isValid = false;
                        }
                    } else {
                        showFieldError(input, 'This field is required');
                        isValid = false;
                    }
                    return;
                }

                if (input.type === 'email' && input.value.trim()) {
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value.trim())) {
                        showFieldError(input, 'Please enter a valid email address');
                        isValid = false;
                    }
                }

                if ((input.name === 'mobile_number' || input.name === 'emergency_contact_number') && input.value.trim()) {
                    if (!/^[6-9]\d{9}$/.test(input.value.trim())) {
                        showFieldError(input, 'Enter a valid 10-digit mobile number');
                        isValid = false;
                    }
                }

                if (input.name === 'pincode' && input.value.trim()) {
                    if (!/^\d{6}$/.test(input.value.trim())) {
                        showFieldError(input, 'Enter a valid 6-digit pincode');
                        isValid = false;
                    }
                }
            });

            // ---- Conditional validation per step ----
            // Step 4 (index 3): address proof required if is_address_same === 'no'
            if (stepIndex === 3) {
                const isAddrSame = document.getElementById('is_address_same')?.value;
                if (isAddrSame === 'no') {
                    const proofType = document.getElementById('address_proof_type');
                    const proofFile = document.getElementById('address_proof_file');
                    if (proofType && !proofType.value) {
                        showFieldError(proofType, 'Please select a document type');
                        isValid = false;
                    }
                    if (proofFile && (!proofFile.files || proofFile.files.length === 0)) {
                        showFieldError(proofFile, 'Please upload the address proof');
                        isValid = false;
                    }
                }
            }

            // Step 1 (index 0): mobile & email format check
            if (stepIndex === 0) {
                const mobInput = document.getElementById('mobile_number_input');
                const mobVal = mobInput?.value.trim();
                if (mobVal && /^[6-9]\d{9}$/.test(mobVal)) {
                    isMobileVerified = true;
                } else if (!isMobileVerified) {
                    showFieldError(mobInput, 'Please enter a valid 10-digit mobile number');
                    isValid = false;
                }

                const emailInput = document.getElementById('email_input');
                const emailVal = emailInput?.value.trim();
                if (emailVal && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
                    isEmailVerified = true;
                } else if (!isEmailVerified && emailInput?.required) {
                    showFieldError(emailInput, 'Please enter a valid email address');
                    isValid = false;
                }
            }

            if (!isValid) {
                const firstError = stepEl.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }

            return isValid;
        }

        function showFieldError(input, message) {
            if (!input) return;
            input.classList.add('is-invalid');
            let errorDiv = input.nextElementSibling;
            if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                input.parentNode.insertBefore(errorDiv, input.nextSibling);
            }
            errorDiv.textContent = message;
        }

        // =============================================
        // FORM PERSISTENCE (SESSION STORAGE)
        // =============================================
        const STORAGE_KEY = 'external_onboarding_data';

        function saveFormData() {
            const form = document.getElementById('employeeForm');
            if (!form) return;
            syncMeasurementFields();
            const data = {};

            form.querySelectorAll('input, select, textarea').forEach(el => {
                if (el.type === 'file') return;
                if (el.type === 'radio') {
                    if (el.checked) data[el.name] = el.value;
                } else {
                    data[el.name] = el.value;
                }
            });

            sessionStorage.setItem(STORAGE_KEY, JSON.stringify(data));
        }

        function syncMeasurementFields() {
            const heightUnit = document.querySelector('select[name="height_unit"]');
            const heightCm = document.querySelector('input[name="height_cm"]');
            const heightWrapper = document.querySelector('.height-ft-in');
            const heightFeet = document.querySelector('input[name="height_feet"]');
            const heightInches = document.querySelector('input[name="height_inches"]');
            const height = document.querySelector('input[name="height"]');
            const weightInput = document.querySelector('input[name="weight_input"]');
            const weightUnit = document.querySelector('select[name="weight_unit"]');
            const weight = document.querySelector('input[name="weight"]');

            const feetAndInches = heightUnit?.value === 'ft_in';
            if (heightCm) heightCm.classList.toggle('d-none', feetAndInches);
            if (heightWrapper) heightWrapper.classList.toggle('d-none', !feetAndInches);

            if (height) {
                height.value = feetAndInches
                    ? (heightFeet?.value || heightInches?.value
                        ? (((parseFloat(heightFeet?.value) || 0) * 30.48) + ((parseFloat(heightInches?.value) || 0) * 2.54)).toFixed(2)
                        : '')
                    : (heightCm?.value || '');
            }

            if (weight) {
                weight.value = weightUnit?.value === 'lbs' && weightInput?.value
                    ? (parseFloat(weightInput.value) * 0.45359237).toFixed(2)
                    : (weightInput?.value || '');
            }
        }

        function restoreFormData() {
            if (document.querySelector('.alert-danger')) return;

            const savedData = sessionStorage.getItem(STORAGE_KEY);
            const savedStep = sessionStorage.getItem('external_onboarding_step');
            const savedMaxStep = sessionStorage.getItem('external_onboarding_max_step');
            const savedEditableStep = sessionStorage.getItem('external_onboarding_editable_step');

            if (savedMaxStep !== null) maxCompletedStep = parseInt(savedMaxStep);
            if (savedEditableStep !== null) editableStep = parseInt(savedEditableStep);

            if (savedData) {
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

                if (data.is_address_same === 'no') {
                    document.getElementById('addressProofTypeWrapper')?.classList.remove('d-none');
                    document.getElementById('addressProofFileWrapper')?.classList.remove('d-none');
                }

                if (data.marital_status === 'married') {
                    const spouseGroup = document.getElementById('spouse_name_group');
                    if (spouseGroup) spouseGroup.style.display = 'block';
                }
            }


            if (sessionStorage.getItem('external_mobile_verified') === 'true') {
                isMobileVerified = true;
                document.getElementById('mobileVerifiedBadge')?.classList.remove('d-none');
                const sendMobileOtpBtn = document.getElementById('sendMobileOtpBtn');
                if (sendMobileOtpBtn) sendMobileOtpBtn.style.display = 'none';
                const mobInput = document.getElementById('mobile_number_input');
                if (mobInput) mobInput.readOnly = true;
            }

            if (sessionStorage.getItem('external_email_verified') === 'true') {
                isEmailVerified = true;
                document.getElementById('emailVerifiedBadge')?.classList.remove('d-none');
                const sendEmailOtpBtn = document.getElementById('sendEmailOtpBtn');
                if (sendEmailOtpBtn) sendEmailOtpBtn.style.display = 'none';
                const emInput = document.getElementById('email_input');
                if (emInput) emInput.readOnly = true;
            }

            showStep(savedStep !== null ? parseInt(savedStep) : 0);
        }

        document.getElementById('employeeForm')?.addEventListener('input', saveFormData);
        document.getElementById('employeeForm')?.addEventListener('change', saveFormData);

        document.getElementById('employeeForm')?.addEventListener('submit', function (e) {

            const form = this;

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            | Do NOT validate all hidden/previous steps again here.
            | Each step was already validated through nextStep().
            |--------------------------------------------------------------------------
            */

            // Make sure required values are present before final submission
            const panInput = document.getElementById('pan_number_input');
            const panValue = panInput ? panInput.value.trim().toUpperCase() : '';

            if (!panValue) {
                e.preventDefault();

                alert('Please enter your PAN number.');

                showStep(3);

                panInput?.focus();

                return false;
            }

            /*
            |--------------------------------------------------------------------------
            | PAN must have been verified through Verify PAN button
            |--------------------------------------------------------------------------
            */

            if (typeof isPanVerified !== 'undefined' && !isPanVerified) {

                e.preventDefault();

                alert('Please verify your PAN number before submitting.');

                showStep(3);

                panInput?.focus();

                return false;
            }

            /*
            |--------------------------------------------------------------------------
            | Profile photo
            |--------------------------------------------------------------------------
            */

            const photoInput = document.getElementById('profile_photo_input');

            if (!photoInput || !photoInput.files || photoInput.files.length === 0) {

                e.preventDefault();

                alert('Please capture or upload your profile photo.');

                showStep(3);

                return false;
            }

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT:
            | Re-enable disabled form fields before native POST.
            |
            | HTML disabled inputs are NOT submitted by the browser.
            |--------------------------------------------------------------------------
            */

            form.querySelectorAll('input:disabled, select:disabled, textarea:disabled')
                .forEach(function (element) {
                    element.disabled = false;
                });

            /*
            |--------------------------------------------------------------------------
            | Keep calculated measurement values synchronized
            |--------------------------------------------------------------------------
            */

            syncMeasurementFields();

            /*
            |--------------------------------------------------------------------------
            | Save submit state
            |--------------------------------------------------------------------------
            */

            const submitBtn = document.getElementById('submitBtn');

            if (submitBtn) {
                submitBtn.disabled = true;

                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>' +
                    'Submitting...';
            }

            /*
            |--------------------------------------------------------------------------
            | Save current step
            |--------------------------------------------------------------------------
            */

            sessionStorage.setItem(
                'external_onboarding_step',
                currentStep
            );

            /*
            |--------------------------------------------------------------------------
            | DO NOT call preventDefault()
            |--------------------------------------------------------------------------
            | Browser now performs the normal multipart POST to Laravel.
            |--------------------------------------------------------------------------
            */

            return true;
        });

        // =============================================
        // CONDITIONAL FIELD TOGGLES
        // =============================================
        document.addEventListener('DOMContentLoaded', function () {
            const maritalStatus = document.getElementById('marital_status');
            const spouseGroup = document.getElementById('spouse_name_group');

            if (maritalStatus && spouseGroup) {
                maritalStatus.addEventListener('change', function () {
                    spouseGroup.style.display = this.value === 'married' ? 'block' : 'none';
                    saveFormData();
                });
            }

            // PAN input: force uppercase as user types
            const panInput = document.getElementById('pan_number_input');
            if (panInput) {
                panInput.addEventListener('input', function () {
                    this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
                });

            }

            const addressSameSelect = document.getElementById('is_address_same');
            if (addressSameSelect) {
                const toggleAddressProofFields = () => {
                    const isDifferent = addressSameSelect.value === 'no';
                    const proofTypeWrapper = document.getElementById('addressProofTypeWrapper');
                    const proofFileWrapper = document.getElementById('addressProofFileWrapper');
                    const proofNumberWrapper = document.getElementById('addressProofNumberWrapper');
                    const proofType = document.getElementById('address_proof_type');
                    const proofFile = document.getElementById('address_proof_file');
                    const proofNumber = document.getElementById('address_proof_number');

                    [proofTypeWrapper, proofFileWrapper, proofNumberWrapper].forEach((element) => {
                        element?.classList.toggle('d-none', !isDifferent);
                    });
                    [proofType, proofFile, proofNumber].forEach((element) => {
                        if (element) element.required = isDifferent;
                    });
                };

                addressSameSelect.addEventListener('change', function () {
                    toggleAddressProofFields();
                    saveFormData();
                });
                toggleAddressProofFields();
            }

            restoreFormData();
            applyLeadPrefill();
        });

        // Dynamic Document Rows
        function addDocumentRow() {
            const container = document.getElementById('customDocumentsContainer');
            if (!container) return;

            const row = document.createElement('div');
            row.className = 'row g-2 align-items-end mb-2 p-2 bg-light rounded-3';

            row.innerHTML = `
                <div class="col-md-4">
                    <label class="small text-muted mb-1">Document Name</label>
                    <input type="text" class="form-control form-control-sm" name="documents[${documentIndex}][name]" placeholder="e.g. Experience Letter">
                </div>
                <div class="col-md-4">
                    <label class="small text-muted mb-1">Document Number</label>
                    <input type="text" class="form-control form-control-sm" name="documents[${documentIndex}][number]" placeholder="Document / Certificate ID">
                </div>
                <div class="col-md-3">
                    <label class="small text-muted mb-1">File</label>
                    <input type="file" class="form-control form-control-sm" name="documents[${documentIndex}][file]" accept=".jpg,.jpeg,.png,.pdf">
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="this.closest('.row').remove()"><i class="bi bi-trash"></i></button>
                </div>
            `;

            container.appendChild(row);
            documentIndex++;
            updateStepAccessibility();
            saveFormData();
        }

        function updateStepAccessibility() {
            steps.forEach((step, index) => {
                const inputs = step.querySelectorAll('input, select, textarea');
                inputs.forEach(el => {
                    el.disabled = index > maxCompletedStep;
                });
            });
        }

        // =============================================
        // CAMERA & FACE DETECTION
        // =============================================
        let activeStream = null;
        let faceDetectionTimer = null;
        let faceModelsReady = false;
        let faceReady = false;
        let faceStableFrames = 0;
        let faceMissingFrames = 0;
        let detectionInProgress = false;
        const faceModelUrl = '{{ asset('storage/face-api') }}';

        function setFaceStatus(message, ready = false) {
            const status = document.getElementById('faceStatus');
            const guide = document.getElementById('faceGuide');
            if (status) status.textContent = message;
            if (guide) guide.classList.toggle('ready', ready);
        }

        function updateCaptureAvailability() {
            const captureButton = document.getElementById('captureBtn');
            if (captureButton) captureButton.disabled = !faceReady;
        }

        async function loadFaceModels() {
            if (faceModelsReady) return true;
            if (typeof faceapi === 'undefined') {
                console.error('face-api.js not loaded');
                return false;
            }

            try {
                await Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri(faceModelUrl),
                ]);
                faceModelsReady = true;
                return true;
            } catch (error) {
                console.error('Error loading face models:', error);
                return false;
            }
        }

        async function detectFace() {
            const video = document.getElementById('video');
            if (!video || !faceModelsReady || video.readyState < 2 || detectionInProgress) {
                return;
            }

            detectionInProgress = true;

            try {
                const result = await faceapi
                    .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 320, scoreThreshold: 0.55 }));

                if (!result) {
                    faceMissingFrames++;
                    if (faceMissingFrames >= 4) {
                        faceReady = false;
                        faceStableFrames = 0;
                    }
                    setFaceStatus('Position your face inside the circle.');
                    updateCaptureAvailability();
                    return;
                }

                faceMissingFrames = 0;

                const box = result.box;
                const videoWidth = video.videoWidth || 1;
                const videoHeight = video.videoHeight || 1;
                const faceCenterX = (box.x + box.width / 2) / videoWidth;
                const faceCenterY = (box.y + box.height / 2) / videoHeight;
                const centered = faceCenterX > 0.3 && faceCenterX < 0.7 && faceCenterY > 0.25 && faceCenterY < 0.75;
                const largeEnough = box.width / videoWidth > 0.2;

                if (centered && largeEnough) {
                    faceStableFrames = Math.min(faceStableFrames + 1, 6);
                } else {
                    faceStableFrames = 0;
                }

                faceReady = faceStableFrames >= 2;
                if (!faceReady) {
                    setFaceStatus('Move closer and center your face in the circle.');
                    updateCaptureAvailability();
                    return;
                }

                setFaceStatus('✅ Face detected. You can capture your selfie.', true);
                updateCaptureAvailability();
            } catch (error) {
                console.error('Face detection error:', error);
                setFaceStatus('Face detection error. Please try again.');
            } finally {
                detectionInProgress = false;
            }
        }

        function startFaceDetection() {
            clearInterval(faceDetectionTimer);
            faceDetectionTimer = setInterval(() => {
                detectFace().catch(() => setFaceStatus('Face detection is unavailable. Please try again.'));
            }, 150);
        }

        function stopCamera() {
            clearInterval(faceDetectionTimer);
            faceDetectionTimer = null;
            if (activeStream) {
                activeStream.getTracks().forEach(track => track.stop());
                activeStream = null;
            }
            const video = document.getElementById('video');
            if (video) video.srcObject = null;
        }

        // MediaPipe override for the employee selfie flow.
        let employeeFaceMesh = null;
        let employeeFaceVerified = false;
        let employeeFaceBaselineCenter = null;
        let employeeFaceOpenEyeBaseline = 0;
        let employeeFaceEyeSamples = 0;
        let employeeFaceEyesClosed = false;
        let employeeAutoCaptureStarted = false;
        let employeeCaptureCountdownTimer = null;

        function employeeDistance(first, second) { return Math.hypot(first.x - second.x, first.y - second.y); }
        function employeeEar(eye) {
            return (employeeDistance(eye[1], eye[5]) + employeeDistance(eye[2], eye[4])) / (2 * employeeDistance(eye[0], eye[3]));
        }
        function startEmployeeCaptureCountdown() {
            if (employeeCaptureCountdownTimer) return;
            let remaining = 3;
            const timerStatus = document.getElementById('captureTimerStatus');
            if (timerStatus) timerStatus.textContent = `📸 Capturing selfie in ${remaining}...`;
            employeeCaptureCountdownTimer = setInterval(() => {
                remaining--;
                if (remaining > 0) {
                    if (timerStatus) timerStatus.textContent = `📸 Capturing selfie in ${remaining}...`;
                    return;
                }
                clearInterval(employeeCaptureCountdownTimer);
                employeeCaptureCountdownTimer = null;
                captureEmployeePhoto();
            }, 1000);
        }
        function cancelEmployeeCaptureCountdown(message) {
            if (!employeeCaptureCountdownTimer) return;
            clearInterval(employeeCaptureCountdownTimer);
            employeeCaptureCountdownTimer = null;
            employeeAutoCaptureStarted = false;
            faceReady = false;
            setFaceStatus(message || 'Face moved. Please return to the circle and try again.');
            updateCaptureAvailability();
        }
        function drawEmployeeLandmarks(landmarks, video) {
            const canvas = document.getElementById('landmarkCanvas');
            if (!canvas) return;
            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;
            const context = canvas.getContext('2d');
            context.clearRect(0, 0, canvas.width, canvas.height);
            context.fillStyle = employeeFaceVerified ? '#22c55e' : '#38bdf8';
            landmarks.forEach(point => {
                context.beginPath();
                context.arc(point.x * canvas.width, point.y * canvas.height, 1.6, 0, Math.PI * 2);
                context.fill();
            });
        }
        async function loadFaceModels() {
            if (faceModelsReady) return true;
            if (typeof FaceMesh === 'undefined') return false;
            employeeFaceMesh = new FaceMesh({ locateFile: file => `https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/${file}` });
            employeeFaceMesh.setOptions({ maxNumFaces: 1, refineLandmarks: true, minDetectionConfidence: 0.6, minTrackingConfidence: 0.6 });
            employeeFaceMesh.onResults(employeeFaceResults);
            faceModelsReady = true;
            return true;
        }
        function employeeFaceResults(results) {
            const video = document.getElementById('video');
            const landmarks = results.multiFaceLandmarks?.[0];
            if (!landmarks || !video) {
                cancelEmployeeCaptureCountdown('Face lost. Selfie cancelled. Please return to the circle.');
                faceReady = false;
                setFaceStatus('Position your face inside the circle.');
                updateCaptureAvailability();
                return;
            }
            drawEmployeeLandmarks(landmarks, video);
            const xs = landmarks.map(point => point.x), ys = landmarks.map(point => point.y);
            const minX = Math.min(...xs), maxX = Math.max(...xs), minY = Math.min(...ys), maxY = Math.max(...ys);
            const centerX = (minX + maxX) / 2, centerY = (minY + maxY) / 2;
            const centered = centerX > 0.3 && centerX < 0.7 && centerY > 0.25 && centerY < 0.75;
            faceStableFrames = centered && maxX - minX > 0.2 ? Math.min(faceStableFrames + 1, 6) : 0;
            faceReady = faceStableFrames >= 2;
            if (!faceReady) {
                cancelEmployeeCaptureCountdown('Face moved. Selfie cancelled. Please return to the circle.');
                setFaceStatus('Move closer and center your face in the circle.');
                updateCaptureAvailability();
                return;
            }
            if (!employeeFaceBaselineCenter) employeeFaceBaselineCenter = { x: centerX, y: centerY };
            const leftEye = [33, 160, 158, 133, 153, 144].map(index => landmarks[index]);
            const rightEye = [362, 385, 387, 263, 373, 380].map(index => landmarks[index]);
            const eyeRatio = (employeeEar(leftEye) + employeeEar(rightEye)) / 2;
            if (!employeeFaceEyesClosed && eyeRatio > 0.24 && employeeFaceEyeSamples < 20) {
                employeeFaceOpenEyeBaseline = employeeFaceEyeSamples === 0 ? eyeRatio : (employeeFaceOpenEyeBaseline * 0.8) + (eyeRatio * 0.2);
                employeeFaceEyeSamples++;
            }
            const baseline = employeeFaceOpenEyeBaseline || eyeRatio;
            const closed = employeeFaceEyeSamples >= 3 ? eyeRatio < baseline * 0.75 : eyeRatio < 0.20;
            const open = employeeFaceEyeSamples >= 3 ? eyeRatio > baseline * 0.88 : eyeRatio > 0.24;
            if (closed) employeeFaceEyesClosed = true;
            if (employeeFaceEyesClosed && open) { employeeFaceVerified = true; employeeFaceEyesClosed = false; }
            if (!employeeFaceVerified && Math.hypot(centerX - employeeFaceBaselineCenter.x, centerY - employeeFaceBaselineCenter.y) > 0.06) employeeFaceVerified = true;
            setFaceStatus(employeeFaceVerified ? '✅ Face verified. Capturing selfie...' : 'Please blink or gently move your face to verify.', true);
            updateCaptureAvailability();
            if (employeeFaceVerified && !employeeAutoCaptureStarted) {
                employeeAutoCaptureStarted = true;
                startEmployeeCaptureCountdown();
            }
        }
        async function detectFace() {
            const video = document.getElementById('video');
            if (!video || !employeeFaceMesh || !faceModelsReady || video.readyState < 2 || detectionInProgress) return;
            detectionInProgress = true;
            try { await employeeFaceMesh.send({ image: video }); } finally { detectionInProgress = false; }
        }

        function captureEmployeePhoto() {
            if (!faceReady || !employeeFaceVerified) {
                cancelEmployeeCaptureCountdown('Face moved. Selfie cancelled. Please try again.');
                return;
            }
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            if (!video || !canvas) return;

            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
                    document.getElementById('latitude').value = pos.coords.latitude;
                    document.getElementById('longitude').value = pos.coords.longitude;
                    document.getElementById('timestamp').value = new Date().toISOString();
                    showGPSStatus('success', '', { lat: pos.coords.latitude, lng: pos.coords.longitude });
                }, () => showGPSStatus('error', 'GPS location not available'));
            }

            canvas.toBlob(blob => {
                if (!blob) return;
                const fileInput = document.getElementById('profile_photo_input');
                if (!fileInput) return;
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(new File([blob], 'camera_capture.jpg', { type: 'image/jpeg' }));
                fileInput.files = dataTransfer.files;
                document.getElementById('profile_photo_preview').innerHTML = `<img src="${URL.createObjectURL(blob)}"><span class="small text-success fw-bold ms-2">Photo Captured</span>`;
                document.getElementById('retryCameraBtn')?.classList.remove('d-none');
                document.getElementById('closeCameraBtn')?.click();
            }, 'image/jpeg', 0.9);
        }

        function showGPSStatus(type, message, coords = null) {
            const gpsStatus = document.getElementById('gpsStatus');
            const gpsText = document.getElementById('gpsStatusText');
            const gpsCoords = document.getElementById('gpsCoordsDisplay');
            if (!gpsStatus || !gpsText || !gpsCoords) return;

            if (type === 'success' && coords) {
                gpsStatus.className = 'gps-status success';
                gpsText.textContent = '📍 Location captured';
                gpsCoords.textContent = `${coords.lat.toFixed(5)}, ${coords.lng.toFixed(5)}`;
                gpsStatus.style.display = 'inline-flex';
            } else if (message) {
                gpsStatus.className = 'gps-status error';
                gpsText.textContent = message;
                gpsStatus.style.display = 'inline-flex';
            }
        }

        // =============================================
        // CAMERA EVENT LISTENERS
        // =============================================

        // Open Camera Button
        document.addEventListener('DOMContentLoaded', function () {
            const openCameraBtn = document.getElementById('openCameraBtn');
            if (openCameraBtn) {
                openCameraBtn.addEventListener('click', function () {
                    const modal = document.getElementById('cameraModal');
                    if (!modal) return;
                    modal.classList.add('active');
                    document.getElementById('retryCameraModalBtn')?.classList.add('d-none');
                    faceReady = false;
                    employeeFaceVerified = false;
                    employeeFaceBaselineCenter = null;
                    employeeFaceOpenEyeBaseline = 0;
                    employeeFaceEyeSamples = 0;
                    employeeFaceEyesClosed = false;
                    employeeAutoCaptureStarted = false;
                    clearInterval(employeeCaptureCountdownTimer);
                    employeeCaptureCountdownTimer = null;
                    faceStableFrames = 0;
                    faceMissingFrames = 0;
                    detectionInProgress = false;
                    updateCaptureAvailability();
                    setFaceStatus('📷 Loading camera...');

                    (async function startCamera() {
                        try {
                            if (!navigator.mediaDevices?.getUserMedia) {
                                throw new Error('Camera requires HTTPS or localhost.');
                            }

                            let stream;
                            try {
                                stream = await navigator.mediaDevices.getUserMedia({
                                    video: { facingMode: 'user', width: 640, height: 480 }
                                });
                            } catch (error) {
                                if (error.name !== 'OverconstrainedError' && error.name !== 'ConstraintNotSatisfiedError') {
                                    throw error;
                                }
                                stream = await navigator.mediaDevices.getUserMedia({ video: true });
                            }

                            const video = document.getElementById('video');
                            if (!video) throw new Error('Video element not found');

                            video.srcObject = stream;
                            activeStream = stream;
                            await video.play();

                            setFaceStatus('⏳ Loading face detection models...');
                            const loaded = await loadFaceModels();
                            if (!loaded) throw new Error('Failed to load face detection models');

                            setFaceStatus('👤 Position your face inside the circle.');
                            startFaceDetection();
                        } catch (error) {
                            stopCamera();
                            console.error('Camera error:', error);
                            setFaceStatus('❌ ' + (error.message || 'Unable to start camera. Please check permissions.'));
                            document.getElementById('retryCameraModalBtn')?.classList.remove('d-none');
                            updateCaptureAvailability();
                        }
                    })();
                });
            }

            document.getElementById('retryCameraBtn')?.addEventListener('click', function () {
                this.classList.add('d-none');
                document.getElementById('openCameraBtn')?.click();
            });
            document.getElementById('retryCameraModalBtn')?.addEventListener('click', function () {
                this.classList.add('d-none');
                document.getElementById('closeCameraBtn')?.click();
                document.getElementById('openCameraBtn')?.click();
            });

            // Capture Button
            const captureBtn = document.getElementById('captureBtn');
            if (captureBtn) {
                captureBtn.addEventListener('click', function () {
                    const video = document.getElementById('video');
                    const canvas = document.getElementById('canvas');
                    if (!video || !canvas) return;

                    const context = canvas.getContext('2d');
                    canvas.width = video.videoWidth || 640;
                    canvas.height = video.videoHeight || 480;
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);

                    // Get GPS location
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            pos => {
                                document.getElementById('latitude').value = pos.coords.latitude;
                                document.getElementById('longitude').value = pos.coords.longitude;
                                document.getElementById('timestamp').value = new Date().toISOString();
                                showGPSStatus('success', '', {
                                    lat: pos.coords.latitude,
                                    lng: pos.coords.longitude
                                });
                            },
                            err => {
                                showGPSStatus('error', '⚠️ GPS location not available');
                            }
                        );
                    }

                    canvas.toBlob(blob => {
                        if (!blob) return;
                        const file = new File([blob], "camera_capture.jpg", { type: "image/jpeg" });
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);

                        const fileInput = document.getElementById('profile_photo_input');
                        if (fileInput) {
                            fileInput.files = dataTransfer.files;
                            const previewDiv = document.getElementById('profile_photo_preview');
                            if (previewDiv) {
                                previewDiv.innerHTML = `<img src="${URL.createObjectURL(blob)}"><span class="small text-success fw-bold ms-2">✅ Photo Captured</span>`;
                            }
                        }
                        document.getElementById('retryCameraBtn')?.classList.remove('d-none');
                        document.getElementById('closeCameraBtn')?.click();
                    }, 'image/jpeg', 0.9);
                });
            }

            // Close Camera Button
            const closeCameraBtn = document.getElementById('closeCameraBtn');
            if (closeCameraBtn) {
                closeCameraBtn.addEventListener('click', function () {
                    document.getElementById('cameraModal')?.classList.remove('active');
                    stopCamera();
                    setFaceStatus('Camera closed');
                });
            }
        });

        // Preview for file uploads
        document.addEventListener('DOMContentLoaded', function () {
            // Profile photo preview
            const profileInput = document.getElementById('profile_photo_input');
            if (profileInput) {
                profileInput.addEventListener('change', function (e) {
                    const preview = document.getElementById('profile_photo_preview');
                    if (preview && this.files && this.files[0]) {
                        preview.innerHTML = `<img src="${URL.createObjectURL(this.files[0])}"><span class="small text-success fw-bold ms-2">📷 File selected</span>`;
                    }
                });
            }

            // Signature preview
            const sigInput = document.getElementById('upload_signature_input');
            if (sigInput) {
                sigInput.addEventListener('change', function (e) {
                    const preview = document.getElementById('upload_signature_preview');
                    if (preview && this.files && this.files[0]) {
                        preview.innerHTML = `<img src="${URL.createObjectURL(this.files[0])}"><span class="small text-success fw-bold ms-2">✅ Signature uploaded</span>`;
                    }
                });
            }
        });
    </script>
</body>

</html>