@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Check-in System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #4bb543;
            --warning-color: #ff9e00;
            --danger-color: #e63946;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gradient-primary: linear-gradient(135deg, #4361ee, #3a0ca3);
            --gradient-success: linear-gradient(135deg, #4bb543, #2a9d40);
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --radius: 12px;
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        /* body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            min-height: 100vh;
            padding: 20px 0;
        } */
        
        .main-container {
            
            margin: 0 auto;
        }
        
        .header-section {
            background: var(--gradient-primary);
            color: white;
            border-radius: var(--radius);
            padding: 25px 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }
        
        .header-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg);
        }
        
        .system-logo {
            font-size: 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .system-logo i {
            margin-right: 15px;
            font-size: 32px;
        }
        
        .card {
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: none;
            margin-bottom: 25px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background: var(--gradient-primary);
            color: white;
            padding: 20px 25px;
            border-bottom: none;
        }
        
        .card-header h4 {
            margin: 0;
            font-weight: 600;
        }
        
        .form-section {
            display: none;
            padding: 30px;
        }
        
        .form-section.active {
            display: block;
            animation: fadeIn 0.5s;
        }
        
        .step-section {
            display: none;
            padding: 30px;
        }
        
        .step-section.active {
            display: block;
            animation: fadeIn 0.5s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin: 0 20px 40px;
        }
        
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 4px;
            background-color: #e9ecef;
            z-index: 1;
        }
        
        .step {
            text-align: center;
            flex: 1;
            position: relative;
            z-index: 2;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: 600;
            color: #6c757d;
            transition: all 0.3s ease;
            border: 3px solid white;
            box-shadow: 0 0 0 3px #e9ecef;
        }
        
        .step.active .step-number {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 0 0 3px var(--primary-color);
        }
        
        .step.completed .step-number {
            background: var(--gradient-success);
            color: white;
            box-shadow: 0 0 0 3px var(--success-color);
        }
        
        .step.completed .step-number::after {
            content: '\f00c';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
        }
        
        .step-label {
            font-size: 14px;
            color: #6c757d;
            font-weight: 500;
        }
        
        .step.active .step-label {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
        }
        
        .btn-success {
            background: var(--gradient-success);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(75, 181, 67, 0.4);
        }
        
        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        .mode-selection {
            text-align: center;
            padding: 40px 20px;
        }
        
        .mode-card {
            border: 2px solid #dee2e6;
            border-radius: var(--radius);
            padding: 40px 20px;
            margin: 0 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            height: 100%;
        }
        
        .mode-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-5px);
            box-shadow: var(--shadow);
        }
        
        .mode-card.active {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(58, 12, 163, 0.05));
        }
        
        .mode-icon {
            font-size: 48px;
            margin-bottom: 20px;
            color: var(--primary-color);
        }
        
        .mode-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark-color);
        }
        
        .mode-description {
            color: #6c757d;
            margin-bottom: 0;
            line-height: 1.6;
        }
        
        .required:after {
            content: " *";
            color: var(--danger-color);
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .otp-container {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            text-align: center;
            margin-top: 20px;
            border: 2px solid #e9ecef;
        }
        
        .otp-input-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }
        
        .otp-input {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .otp-input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        .otp-timer {
            font-size: 14px;
            color: #6c757d;
            margin: 10px 0;
        }
        
        .vehicle-details-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            border: 2px solid #e9ecef;
        }
        
        .preview-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }
        
        .preview-container {
            text-align: center;
            margin-bottom: 15px;
            position: relative;
        }
        
        .photo-preview {
            width: 120px;
            height: 120px;
            border-radius: 8px;
            object-fit: cover;
            border: 3px solid #dee2e6;
        }
        
        .remove-photo {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--danger-color);
            color: white;
            border: none;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .camera-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            display: none;
        }
        
        .camera-video {
            width: 90%;
            max-width: 640px;
            border-radius: 8px;
            border: 3px solid white;
        }
        
        .camera-controls {
            margin-top: 20px;
            display: flex;
            gap: 15px;
        }
        
        .captured-photos {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        
        .captured-photo {
            width: 80px;
            height: 80px;
            border-radius: 6px;
            object-fit: cover;
            border: 2px solid white;
        }
        
        .visitor-code-container {
            background: var(--gradient-primary);
            color: white;
            border-radius: var(--radius);
            padding: 30px;
            margin-top: 25px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .visitor-code-container::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg);
        }
        
        .visitor-code-display {
            font-size: 36px;
            font-weight: 700;
            letter-spacing: 2px;
            margin: 20px 0;
            background: rgba(255, 255, 255, 0.2);
            padding: 15px 30px;
            border-radius: 8px;
            display: inline-block;
            position: relative;
            z-index: 2;
        }
        
        .section-title {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            color: var(--secondary-color);
        }
        
        .section-title i {
            margin-right: 12px;
            font-size: 24px;
        }
        
        .info-box {
            background: linear-gradient(135deg, #e3f2fd, #f0f4ff);
            border-left: 4px solid var(--primary-color);
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 0 8px 8px 0;
            display: flex;
            align-items: flex-start;
        }
        
        .info-box i {
            color: var(--primary-color);
            font-size: 20px;
            margin-right: 15px;
            margin-top: 2px;
        }
        
        .info-box p {
            margin: 0;
            color: #495057;
        }
        
        .photo-upload-container {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }
        
        .photo-upload-container:hover {
            border-color: var(--primary-color);
            background-color: #f8f9fa;
        }
        
        .self-registration-form {
            padding: 20px;
        }
        
        .visitor-details-card {
            background: white;
            border-radius: var(--radius);
            padding: 30px;
            margin-top: 20px;
            box-shadow: var(--shadow);
            border: 2px solid #dee2e6;
        }
        
        .visitor-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
        }
        
        .info-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 16px;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .vehicle-photo-grid {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 10px;
        }
        
        .vehicle-photo {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #dee2e6;
        }
        
        .visitor-photo-large {
            width: 150px;
            height: 150px;
            border-radius: 8px;
            object-fit: cover;
            border: 3px solid #dee2e6;
            margin: 10px 0;
        }
        
        .test-buttons {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            border: 2px solid #dee2e6;
        }
        
        @media (max-width: 768px) {
            .step-indicator {
                margin: 0 10px 30px;
            }
            
            .step-label {
                font-size: 12px;
            }
            
            .header-section {
                padding: 20px;
            }
            
            .system-logo {
                font-size: 22px;
            }
            
            .mode-card {
                margin: 10px 0;
                padding: 30px 15px;
            }
            
            .otp-input {
                width: 40px;
                height: 50px;
                font-size: 20px;
            }
            
            .visitor-code-display {
                font-size: 28px;
                padding: 12px 20px;
            }
            
            .camera-video {
                width: 95%;
            }
            
            .mode-card {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container main-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="system-logo">
                <i class="fas fa-check-circle"></i>
                <div>
                    Visitor Check-in System
                    <div class="fs-6 fw-normal">Welcome to our facility</div>
                </div>
            </div>
            <p class="mb-0 mt-2">Select your check-in method to proceed</p>
        </div>
        
        <!-- Check-in Card -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-sign-in-alt me-2"></i>Visitor Check-in</h4>
            </div>
            <div class="card-body p-0">
                <!-- Mode Selection -->
                <div id="modeSelection" class="mode-selection active">
                    <h5 class="mb-4">Select your check-in mode</h5>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="mode-card" id="walkinCard" data-mode="walkin">
                                <div class="mode-icon">
                                    <i class="fas fa-walking"></i>
                                </div>
                                <div class="mode-title">Walk-in Visitor</div>
                                <div class="mode-description">
                                    New visitor registration with OTP verification, vehicle details, and camera photo capture. Complete all steps to get your visitor pass.
                                </div>
                                <div class="mt-3">
                                    <small class="text-primary"><i class="fas fa-info-circle me-1"></i>Includes OTP verification & vehicle photos</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="mode-card" id="selfRegCard" data-mode="self">
                                <div class="mode-icon">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <div class="mode-title">Self Registration</div>
                                <div class="mode-description">
                                    Already registered? Enter your visitor code to check-in. Your details will be fetched and displayed.
                                </div>
                                <div class="mt-3">
                                    <small class="text-primary"><i class="fas fa-info-circle me-1"></i>Quick check-in with existing code</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Test Buttons -->
                    <div class="test-buttons">
                        <h6><i class="fas fa-vial me-2"></i>Testing Tools</h6>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <button class="btn btn-sm btn-info" id="showSampleCodesBtn">
                                <i class="fas fa-eye me-1"></i>Show Sample Codes
                            </button>
                            <button class="btn btn-sm btn-warning" id="checkStorageBtn">
                                <i class="fas fa-database me-1"></i>Check Storage
                            </button>
                            <button class="btn btn-sm btn-danger" id="clearStorageBtn">
                                <i class="fas fa-trash me-1"></i>Clear Storage
                            </button>
                            <button class="btn btn-sm btn-success" id="loadSampleDataBtn">
                                <i class="fas fa-plus me-1"></i>Load Sample Data
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Walk-in Registration Form -->
                <div id="walkinForm" class="form-section">
                    <!-- Progress Steps for Walk-in -->
                    <div class="step-indicator">
                        <div class="step active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label">Personal Info</div>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-label">OTP Verification</div>
                        </div>
                        <div class="step" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label">Vehicle Details</div>
                        </div>
                        <div class="step" data-step="4">
                            <div class="step-number">4</div>
                            <div class="step-label">Visitor Code</div>
                        </div>
                    </div>
                    
                    <!-- Step 1: Personal Information -->
                    <div class="step-section active" id="walkinSection1">
                        <div class="section-title">
                            <i class="fas fa-user-circle"></i>
                            <h5 class="mb-0">Step 1: Personal Information</h5>
                        </div>
                        
                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            <p>Please provide your personal details for identification and security purposes.</p>
                        </div>
                        
                        <form id="personalInfoForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="walkinFullName" class="form-label required">Full Name</label>
                                    <input type="text" class="form-control" id="walkinFullName" placeholder="Enter your full name" required>
                                    <div class="invalid-feedback">Please provide your full name.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="walkinContactNumber" class="form-label required">Contact Number</label>
                                    <input type="tel" class="form-control" id="walkinContactNumber" placeholder="Enter 10-digit mobile number" required>
                                    <div class="invalid-feedback">Please provide a valid 10-digit contact number.</div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="walkinEmail" class="form-label required">Email Address</label>
                                    <input type="email" class="form-control" id="walkinEmail" placeholder="Enter your email address" required>
                                    <div class="invalid-feedback">Please provide a valid email address.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="walkinPurpose" class="form-label required">Purpose of Visit</label>
                                    <select class="form-select" id="walkinPurpose" required>
                                        <option value="" selected disabled>Select Purpose</option>
                                        <option value="Meeting">Meeting</option>
                                        <option value="Delivery">Delivery</option>
                                        <option value="Interview">Interview</option>
                                        <option value="Client Visit">Client Visit</option>
                                        <option value="Maintenance">Maintenance</option>
                                        <option value="Training">Training</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="invalid-feedback">Please select purpose of visit.</div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label required">Upload Visitor Photo</label>
                                <div class="photo-upload-container" id="walkinPhotoUploadArea">
                                    <i class="fas fa-camera fa-3x text-muted mb-3"></i>
                                    <h5 class="mb-2">Click to upload or take your photo</h5>
                                    <p class="text-muted mb-0">JPG, PNG - Max 5MB</p>
                                    <input type="file" id="walkinPhotoUpload" accept="image/*" class="d-none">
                                </div>
                                <div class="preview-container" id="walkinPhotoPreviewContainer" style="display: none;">
                                    <img id="walkinPhotoPreview" class="photo-preview" src="" alt="Visitor Photo Preview">
                                    <button type="button" class="remove-photo" id="removeWalkinPhoto">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <div class="preview-label mt-2">Your Photo Preview</div>
                                </div>
                                <div class="invalid-feedback" id="walkinPhotoError">Please upload your photo.</div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="openCameraBtn">
                                        <i class="fas fa-camera me-2"></i>Take Photo with Camera
                                    </button>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary" id="backToModeWalkin">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Mode Selection
                                </button>
                                <button type="button" class="btn btn-primary" id="walkinNext1">
                                    Next: OTP Verification <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Step 2: OTP Verification -->
                    <div class="step-section" id="walkinSection2">
                        <div class="section-title">
                            <i class="fas fa-shield-alt"></i>
                            <h5 class="mb-0">Step 2: OTP Verification</h5>
                        </div>
                        
                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            <p>For security verification, we need to verify your contact number via OTP.</p>
                        </div>
                        
                        <div class="otp-container">
                            <h6><i class="fas fa-mobile-alt me-2"></i>Mobile Number Verification</h6>
                            <p class="mb-3">An OTP will be sent to your mobile number: <strong id="walkinMobileNumberDisplay"></strong></p>
                            
                            <div class="otp-input-container" id="walkinOtpInputContainer" style="display: none;">
                                <input type="text" maxlength="1" class="otp-input" data-index="0">
                                <input type="text" maxlength="1" class="otp-input" data-index="1">
                                <input type="text" maxlength="1" class="otp-input" data-index="2">
                                <input type="text" maxlength="1" class="otp-input" data-index="3">
                                <input type="text" maxlength="1" class="otp-input" data-index="4">
                                <input type="text" maxlength="1" class="otp-input" data-index="5">
                            </div>
                            
                            <div class="otp-timer" id="walkinOtpTimer" style="display: none;">
                                OTP valid for: <span id="walkinTimer">05:00</span>
                            </div>
                            
                            <div id="walkinVerificationStatus" class="mt-3" style="display: none;">
                                <div class="alert alert-success" id="walkinSuccessMessage" style="display: none;">
                                    <i class="fas fa-check-circle me-2"></i>OTP verified successfully!
                                </div>
                                <div class="alert alert-danger" id="walkinErrorMessage" style="display: none;">
                                    <i class="fas fa-times-circle me-2"></i>Invalid OTP. Please try again.
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="button" class="btn btn-primary" id="walkinGenerateOtpBtn">
                                    <i class="fas fa-key me-2"></i>Generate OTP
                                </button>
                                <button type="button" class="btn btn-success" id="walkinVerifyOtpBtn" style="display: none;">
                                    <i class="fas fa-check-circle me-2"></i>Verify OTP
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="walkinResendOtpBtn" style="display: none;">
                                    <i class="fas fa-redo me-2"></i>Resend OTP
                                </button>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary" id="walkinPrev2">
                                <i class="fas fa-arrow-left me-2"></i>Previous
                            </button>
                            <button type="button" class="btn btn-primary" id="walkinNext2" style="display: none;">
                                Next: Vehicle Details <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 3: Vehicle Details -->
                    <div class="step-section" id="walkinSection3">
                        <div class="section-title">
                            <i class="fas fa-car"></i>
                            <h5 class="mb-0">Step 3: Vehicle Details</h5>
                        </div>
                        
                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            <p>Please provide your vehicle details and photos for parking and security purposes.</p>
                        </div>
                        
                        <div class="vehicle-details-section">
                            <h6 class="mb-3"><i class="fas fa-car me-2"></i>Vehicle Information</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="walkinVehicleType" class="form-label required">Vehicle Type</label>
                                    <select class="form-select" id="walkinVehicleType" required>
                                        <option value="" selected disabled>Select Vehicle Type</option>
                                        <option value="Car">Car</option>
                                        <option value="Motorcycle">Motorcycle</option>
                                        <option value="Scooter">Scooter</option>
                                        <option value="Bicycle">Bicycle</option>
                                        <option value="Truck">Truck</option>
                                        <option value="Van">Van</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="walkinVehicleNumber" class="form-label required">Vehicle Number</label>
                                    <input type="text" class="form-control" id="walkinVehicleNumber" placeholder="e.g. DL01AB1234" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="walkinVehicleColor" class="form-label">Vehicle Color</label>
                                    <input type="text" class="form-control" id="walkinVehicleColor" placeholder="e.g. Red, White, Black">
                                </div>
                            </div>
                            
                            <!-- Vehicle Photo Upload -->
                            <div class="mb-4">
                                <label class="form-label required">Vehicle Photos</label>
                                <p class="text-muted mb-3">Please upload at least 2 photos of your vehicle (front and back)</p>
                                
                                <div class="photo-upload-container" id="walkinVehiclePhotoUploadArea">
                                    <i class="fas fa-camera fa-3x text-muted mb-3"></i>
                                    <h5 class="mb-2">Click to upload or take vehicle photos</h5>
                                    <p class="text-muted mb-0">Upload multiple photos or use camera</p>
                                    <input type="file" id="walkinVehiclePhotoUpload" accept="image/*" multiple class="d-none">
                                </div>
                                
                                <div class="mt-2">
                                    <button type="button" class="btn btn-outline-primary" id="openWalkinVehicleCameraBtn">
                                        <i class="fas fa-camera me-2"></i>Take Vehicle Photos with Camera
                                    </button>
                                </div>
                                
                                <!-- Vehicle Photos Preview -->
                                <div id="walkinVehiclePhotosPreview" class="preview-row mt-3"></div>
                                <div class="invalid-feedback" id="vehiclePhotoError">Please upload at least 2 vehicle photos.</div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary" id="walkinPrev3">
                                <i class="fas fa-arrow-left me-2"></i>Previous
                            </button>
                            <button type="button" class="btn btn-primary" id="walkinNext3">
                                Generate Visitor Code <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 4: Visitor Code -->
                    <div class="step-section" id="walkinSection4">
                        <div class="section-title">
                            <i class="fas fa-qrcode"></i>
                            <h5 class="mb-0">Step 4: Your Visitor Code</h5>
                        </div>
                        
                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            <p>Your visitor code has been generated. Please save this code and show it at the reception.</p>
                        </div>
                        
                        <div class="visitor-code-container">
                            <h5><i class="fas fa-id-card me-2"></i>Your Visitor Code</h5>
                            <div class="visitor-code-display" id="walkinVisitorCode">VR-000000</div>
                            
                            <div class="visitor-details mt-4" style="position: relative; z-index: 2;">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <p class="mb-1"><strong>Name:</strong></p>
                                        <p id="walkinFinalVisitorName">Visitor Name</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <p class="mb-1"><strong>Purpose:</strong></p>
                                        <p id="walkinFinalVisitorPurpose">Purpose</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <p class="mb-1"><strong>Contact:</strong></p>
                                        <p id="walkinFinalVisitorContact">Contact</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <p class="mb-1"><strong>Vehicle:</strong></p>
                                        <p id="walkinFinalVehicleType">Vehicle Type</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <p class="mb-1"><strong>Vehicle No:</strong></p>
                                        <p id="walkinFinalVehicleNumber">Vehicle Number</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <p class="mb-1"><strong>Photos:</strong></p>
                                        <p id="walkinFinalVehiclePhotos">0 photos</p>
                                    </div>
                                </div>
                            </div>
                            
                            <small class="mt-3 d-block opacity-75" style="position: relative; z-index: 2;">
                                <i class="fas fa-clock me-1"></i> Valid for 24 hours from registration
                            </small>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary" id="walkinPrev4">
                                <i class="fas fa-arrow-left me-2"></i>Previous
                            </button>
                            <button type="button" class="btn btn-success" id="walkinSaveAndComplete">
                                <i class="fas fa-check-circle me-2"></i>Save & Complete Check-in
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Self Registration Form -->
                <div id="selfRegForm" class="form-section">
                    <div class="self-registration-form">
                        <div class="section-title">
                            <i class="fas fa-qrcode"></i>
                            <h5 class="mb-0">Self Registration Check-in</h5>
                        </div>
                        
                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            <p>Enter your visitor code to check-in. Your details will be fetched and displayed.</p>
                        </div>
                        
                        <div class="mb-4">
                            <label for="visitorCodeInput" class="form-label required">Enter Your Visitor Code</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="visitorCodeInput" placeholder="Enter VR-XXXXXX code" required>
                                <button class="btn btn-primary" type="button" id="fetchVisitorBtn">
                                    <i class="fas fa-search me-2"></i>Fetch Details
                                </button>
                            </div>
                            <small class="text-muted">Enter the visitor code you received during registration</small>
                        </div>
                        
                        <!-- Sample Codes Info -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Need a test code?</strong> Try: <code>VR-A1B2C3</code>, <code>VR-X9Y8Z7</code>, or <code>VR-M5N6O7</code>
                        </div>
                        
                        <!-- Visitor Details Display -->
                        <div id="visitorDetails" class="visitor-details-card" style="display: none;">
                            <h6 class="mb-3"><i class="fas fa-user-circle me-2"></i>Visitor Details</h6>
                            
                            <!-- Visitor Code Display -->
                            <div class="visitor-code-container mb-4" id="selfVisitorCodeContainer" style="margin-top: 20px; display: none;">
                                <h6><i class="fas fa-id-card me-2"></i>Your Visitor Code</h6>
                                <div class="visitor-code-display" id="selfVisitorCodeDisplay">VR-000000</div>
                                <small class="mt-2 d-block opacity-75">Valid for 24 hours from registration</small>
                            </div>
                            
                            <!-- Visitor Information -->
                            <div class="visitor-info-grid">
                                <div class="info-item">
                                    <div class="info-label">Full Name</div>
                                    <div class="info-value" id="detailName">-</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Contact Number</div>
                                    <div class="info-value" id="detailContact">-</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Email Address</div>
                                    <div class="info-value" id="detailEmail">-</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Purpose of Visit</div>
                                    <div class="info-value" id="detailPurpose">-</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Registration Date</div>
                                    <div class="info-value" id="detailDate">-</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Status</div>
                                    <div class="info-value" id="detailStatus">-</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Vehicle Type</div>
                                    <div class="info-value" id="detailVehicleType">-</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Vehicle Number</div>
                                    <div class="info-value" id="detailVehicleNumber">-</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Vehicle Color</div>
                                    <div class="info-value" id="detailVehicleColor">-</div>
                                </div>
                            </div>
                            
                            <!-- Visitor Photo -->
                            <div class="mt-4">
                                <div class="info-label mb-2">Visitor Photo</div>
                                <div class="text-center">
                                    <img id="detailPhoto" class="visitor-photo-large" src="" alt="Visitor Photo" style="display: none;">
                                    <div id="noPhotoMessage" class="text-muted">No photo uploaded</div>
                                </div>
                            </div>
                            
                            <!-- Vehicle Photos -->
                            <div class="mt-4">
                                <div class="info-label mb-2">Vehicle Photos</div>
                                <div id="detailVehiclePhotos" class="vehicle-photo-grid">
                                    <!-- Vehicle photos will appear here -->
                                </div>
                                <div id="noVehiclePhotosMessage" class="text-muted">No vehicle photos uploaded</div>
                            </div>
                            
                            <!-- Check-in Button -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary" id="backToModeSelf">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Mode Selection
                                </button>
                                <button type="button" class="btn btn-success" id="checkinVisitorBtn">
                                    <i class="fas fa-check-circle me-2"></i>Confirm Check-in
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Success Message -->
        <div id="successMessage" class="alert alert-success" style="display: none;">
            <h5><i class="fas fa-check-circle me-2"></i>Check-in Successful!</h5>
            <p class="mb-0" id="successDetails"></p>
            <div class="mt-3">
                <button class="btn btn-outline-success" id="newCheckinBtn">
                    <i class="fas fa-plus me-2"></i>New Check-in
                </button>
            </div>
        </div>
        
        <!-- Camera Modal -->
        <div class="camera-container" id="cameraModal">
            <video id="cameraVideo" class="camera-video" autoplay></video>
            <div class="camera-controls">
                <button type="button" class="btn btn-primary" id="capturePhotoBtn">
                    <i class="fas fa-camera me-2"></i>Capture Photo
                </button>
                <button type="button" class="btn btn-secondary" id="closeCameraBtn">
                    <i class="fas fa-times me-2"></i>Close Camera
                </button>
            </div>
            <div class="captured-photos" id="capturedPhotos"></div>
            <div class="mt-3">
                <button type="button" class="btn btn-success" id="useCapturedPhotosBtn" style="display: none;">
                    <i class="fas fa-check me-2"></i>Use Selected Photos
                </button>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get visitor data from localStorage
            let allVisitors = JSON.parse(localStorage.getItem('visitorRegistrationData') || '[]');
            let currentVisitor = null;
            let currentWalkinStep = 1;
            let walkinFormData = {};
            let generatedOTP = '';
            let otpTimer = null;
            let vehiclePhotos = [];
            
            // Initialize sample data if empty
            function initializeSampleData() {
                if (allVisitors.length === 0) {
                    console.log('Initializing sample data...');
                    allVisitors = [
                        {
                            id: 1,
                            name: "John Doe",
                            contact: "9876543210",
                            email: "john@example.com",
                            purpose: "Meeting",
                            vehicleType: "Car",
                            vehicleNumber: "DL01AB1234",
                            vehicleColor: "Red",
                            vehiclePhotos: [],
                            visitorPhoto: "",
                            code: "VR-A1B2C3",
                            registrationTime: new Date().toISOString(),
                            status: "Registered",
                            registrationType: "Self Registration",
                            otpVerified: true
                        },
                        {
                            id: 2,
                            name: "Jane Smith",
                            contact: "9123456789",
                            email: "jane@example.com",
                            purpose: "Interview",
                            vehicleType: "Motorcycle",
                            vehicleNumber: "MH02CD5678",
                            vehicleColor: "Black",
                            vehiclePhotos: [],
                            visitorPhoto: "",
                            code: "VR-X9Y8Z7",
                            registrationTime: new Date().toISOString(),
                            status: "Registered",
                            registrationType: "Walk-in",
                            otpVerified: true
                        },
                        {
                            id: 3,
                            name: "Robert Johnson",
                            contact: "9988776655",
                            email: "robert@example.com",
                            purpose: "Delivery",
                            vehicleType: "Truck",
                            vehicleNumber: "KA03EF9012",
                            vehicleColor: "White",
                            vehiclePhotos: [],
                            visitorPhoto: "",
                            code: "VR-M5N6O7",
                            registrationTime: new Date().toISOString(),
                            status: "Registered",
                            registrationType: "Self Registration",
                            otpVerified: true
                        }
                    ];
                    localStorage.setItem('visitorRegistrationData', JSON.stringify(allVisitors));
                    console.log('Sample data initialized with 3 visitors');
                }
                console.log('Total visitors in storage:', allVisitors.length);
                console.log('Visitor codes:', allVisitors.map(v => v.code));
            }
            
            // Call initialization
            initializeSampleData();
            
            // Mode selection
            document.getElementById('walkinCard').addEventListener('click', function() {
                selectMode('walkin');
            });
            
            document.getElementById('selfRegCard').addEventListener('click', function() {
                selectMode('self');
            });
            
            // Back buttons
            document.getElementById('backToModeWalkin').addEventListener('click', function() {
                showModeSelection();
            });
            
            document.getElementById('backToModeSelf').addEventListener('click', function() {
                showModeSelection();
            });
            
            // Fetch visitor details for self-registration
            document.getElementById('fetchVisitorBtn').addEventListener('click', fetchVisitorDetails);
            
            // Allow pressing Enter in visitor code input
            document.getElementById('visitorCodeInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    fetchVisitorDetails();
                }
            });
            
            // Check-in visitor for self-registration
            document.getElementById('checkinVisitorBtn').addEventListener('click', function() {
                checkinVisitor();
            });
            
            // New check-in button
            document.getElementById('newCheckinBtn').addEventListener('click', function() {
                resetForm();
            });
            
            // Test buttons
            document.getElementById('showSampleCodesBtn').addEventListener('click', function() {
                const codes = allVisitors.map(v => v.code).join(', ');
                alert(`Available Visitor Codes:\n\n${codes}\n\nTotal: ${allVisitors.length} visitor(s)`);
            });
            
            document.getElementById('checkStorageBtn').addEventListener('click', function() {
                console.log('=== STORAGE CHECK ===');
                console.log('Total visitors:', allVisitors.length);
                console.log('Visitor codes:', allVisitors.map(v => v.code));
                console.log('Full data:', allVisitors);
                alert(`Storage contains ${allVisitors.length} visitor(s)\n\nCodes: ${allVisitors.map(v => v.code).join(', ')}\n\nCheck browser console for full details.`);
            });
            
            document.getElementById('clearStorageBtn').addEventListener('click', function() {
                if (confirm('Are you sure you want to clear all visitor data?')) {
                    localStorage.removeItem('visitorRegistrationData');
                    allVisitors = [];
                    alert('All visitor data has been cleared from storage.');
                    console.log('Storage cleared');
                }
            });
            
            document.getElementById('loadSampleDataBtn').addEventListener('click', function() {
                initializeSampleData();
                alert('Sample data loaded! Try these codes:\n\nVR-A1B2C3\nVR-X9Y8Z7\nVR-M5N6O7');
            });
            
            // Function to select mode
            function selectMode(mode) {
                document.querySelectorAll('.mode-card').forEach(card => {
                    card.classList.remove('active');
                });
                
                if (mode === 'walkin') {
                    document.getElementById('walkinCard').classList.add('active');
                    showWalkinForm();
                } else {
                    document.getElementById('selfRegCard').classList.add('active');
                    showSelfRegForm();
                }
            }
            
            // Function to show mode selection
            function showModeSelection() {
                document.getElementById('modeSelection').classList.add('active');
                document.getElementById('modeSelection').style.display = 'block';
                document.getElementById('walkinForm').style.display = 'none';
                document.getElementById('selfRegForm').style.display = 'none';
                document.getElementById('successMessage').style.display = 'none';
                document.querySelectorAll('.mode-card').forEach(card => {
                    card.classList.remove('active');
                });
            }
            
            // Function to show self-registration form
            function showSelfRegForm() {
                document.getElementById('modeSelection').style.display = 'none';
                document.getElementById('modeSelection').classList.remove('active');
                document.getElementById('walkinForm').style.display = 'none';
                document.getElementById('selfRegForm').style.display = 'block';
                document.getElementById('visitorCodeInput').focus();
            }
            
            // Function to fetch visitor details
            function fetchVisitorDetails() {
                const codeInput = document.getElementById('visitorCodeInput').value.trim().toUpperCase();
                
                if (!codeInput) {
                    alert('Please enter a visitor code');
                    return;
                }
                
                console.log('=== FETCHING VISITOR ===');
                console.log('Looking for code:', codeInput);
                console.log('Total visitors in storage:', allVisitors.length);
                
                // Reload data from localStorage to ensure we have latest
                const storedData = localStorage.getItem('visitorRegistrationData');
                if (storedData) {
                    try {
                        allVisitors = JSON.parse(storedData);
                        console.log('Reloaded data from storage. Total visitors:', allVisitors.length);
                    } catch (e) {
                        console.error('Error parsing data:', e);
                        alert('Error loading visitor data. Please try again.');
                        return;
                    }
                }
                
                // Find visitor with matching code (case-insensitive)
                const visitor = allVisitors.find(v => {
                    if (v && v.code) {
                        return v.code.toUpperCase() === codeInput;
                    }
                    return false;
                });
                
                console.log('Found visitor:', visitor);
                
                if (!visitor) {
                    // Show available codes for help
                    const availableCodes = allVisitors.map(v => v.code).join(', ');
                    alert(`❌ Visitor with code "${codeInput}" was not found.\n\nAvailable codes: ${availableCodes || 'None'}\n\nTry one of these sample codes:\nVR-A1B2C3, VR-X9Y8Z7, VR-M5N6O7`);
                    return;
                }
                
                currentVisitor = visitor;
                
                // Display visitor code prominently
                document.getElementById('selfVisitorCodeContainer').style.display = 'block';
                document.getElementById('selfVisitorCodeDisplay').textContent = visitor.code;
                
                // Display visitor details
                document.getElementById('detailName').textContent = visitor.name || 'Not provided';
                document.getElementById('detailContact').textContent = visitor.contact || 'Not provided';
                document.getElementById('detailEmail').textContent = visitor.email || 'Not provided';
                document.getElementById('detailPurpose').textContent = visitor.purpose || 'Not provided';
                document.getElementById('detailVehicleType').textContent = visitor.vehicleType || 'Not provided';
                document.getElementById('detailVehicleNumber').textContent = visitor.vehicleNumber || 'Not provided';
                document.getElementById('detailVehicleColor').textContent = visitor.vehicleColor || 'Not provided';
                
                // Format date
                if (visitor.registrationTime) {
                    const regDate = new Date(visitor.registrationTime);
                    document.getElementById('detailDate').textContent = regDate.toLocaleDateString() + ' ' + regDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                } else {
                    document.getElementById('detailDate').textContent = 'Not available';
                }
                
                // Display status with color
                let statusText = visitor.status || 'Unknown';
                let statusClass = '';
                
                if (statusText === 'Checked-in') {
                    statusClass = 'text-success';
                    statusText += ' ✓';
                } else if (statusText === 'Registered') {
                    statusClass = 'text-primary';
                } else if (statusText === 'Unknown') {
                    statusClass = 'text-warning';
                }
                
                document.getElementById('detailStatus').textContent = statusText;
                document.getElementById('detailStatus').className = 'info-value ' + statusClass;
                
                // Display visitor photo if available
                if (visitor.visitorPhoto) {
                    document.getElementById('detailPhoto').src = visitor.visitorPhoto;
                    document.getElementById('detailPhoto').style.display = 'block';
                    document.getElementById('noPhotoMessage').style.display = 'none';
                } else {
                    document.getElementById('detailPhoto').style.display = 'none';
                    document.getElementById('noPhotoMessage').style.display = 'block';
                }
                
                // Display vehicle photos if available
                const vehiclePhotosContainer = document.getElementById('detailVehiclePhotos');
                const noVehiclePhotosMessage = document.getElementById('noVehiclePhotosMessage');
                
                if (visitor.vehiclePhotos && Array.isArray(visitor.vehiclePhotos) && visitor.vehiclePhotos.length > 0) {
                    vehiclePhotosContainer.innerHTML = '';
                    visitor.vehiclePhotos.forEach((photo, index) => {
                        const img = document.createElement('img');
                        img.className = 'vehicle-photo';
                        img.src = photo;
                        img.alt = `Vehicle Photo ${index + 1}`;
                        img.title = `Vehicle Photo ${index + 1}`;
                        vehiclePhotosContainer.appendChild(img);
                    });
                    vehiclePhotosContainer.style.display = 'flex';
                    noVehiclePhotosMessage.style.display = 'none';
                } else {
                    vehiclePhotosContainer.style.display = 'none';
                    noVehiclePhotosMessage.style.display = 'block';
                }
                
                // Show visitor details
                document.getElementById('visitorDetails').style.display = 'block';
                
                // Scroll to details for better UX
                document.getElementById('visitorDetails').scrollIntoView({ behavior: 'smooth' });
                
                console.log('✅ Visitor details displayed successfully');
            }
            
            function checkinVisitor() {
                if (!currentVisitor) {
                    alert('No visitor selected');
                    return;
                }
                
                if (currentVisitor.status === 'Checked-in') {
                    alert('This visitor is already checked in.');
                    return;
                }
                
                // Update visitor status
                currentVisitor.status = 'Checked-in';
                currentVisitor.checkinTime = new Date().toISOString();
                
                // Update in localStorage
                const index = allVisitors.findIndex(v => v.code === currentVisitor.code);
                if (index !== -1) {
                    allVisitors[index] = currentVisitor;
                    localStorage.setItem('visitorRegistrationData', JSON.stringify(allVisitors));
                }
                
                // Show success message with visitor code
                document.getElementById('selfRegForm').style.display = 'none';
                document.getElementById('successMessage').style.display = 'block';
                document.getElementById('successDetails').innerHTML = `
                    ✅ <strong>${currentVisitor.name}</strong> has been successfully checked in.<br><br>
                    <div class="visitor-code-display" style="font-size: 28px; margin: 15px 0;">${currentVisitor.code}</div>
                    <strong>Visitor Details:</strong><br>
                    👤 Name: ${currentVisitor.name}<br>
                    📞 Contact: ${currentVisitor.contact}<br>
                    📅 Purpose: ${currentVisitor.purpose}<br>
                    ${currentVisitor.vehicleType ? '🚗 Vehicle: ' + currentVisitor.vehicleType + ' (' + currentVisitor.vehicleNumber + ')' : ''}<br>
                    🕒 Check-in Time: <strong>${new Date().toLocaleTimeString()}</strong>
                `;
                
                currentVisitor = null;
            }
            
            function resetForm() {
                // Reset self-registration form
                document.getElementById('visitorCodeInput').value = '';
                document.getElementById('visitorDetails').style.display = 'none';
                document.getElementById('selfVisitorCodeContainer').style.display = 'none';
                
                // Show mode selection
                showModeSelection();
                currentVisitor = null;
            }
            
            // Walk-in form functionality
            function showWalkinForm() {
                document.getElementById('modeSelection').style.display = 'none';
                document.getElementById('modeSelection').classList.remove('active');
                document.getElementById('walkinForm').style.display = 'block';
                document.getElementById('selfRegForm').style.display = 'none';
                document.getElementById('successMessage').style.display = 'none';
                
                // Reset walk-in form
                resetWalkinForm();
                
                // Show first step
                showWalkinStep(1);
            }
            
            function resetWalkinForm() {
                currentWalkinStep = 1;
                walkinFormData = {};
                vehiclePhotos = [];
                
                // Reset form fields
                document.getElementById('walkinFullName').value = '';
                document.getElementById('walkinContactNumber').value = '';
                document.getElementById('walkinEmail').value = '';
                document.getElementById('walkinPurpose').value = '';
                document.getElementById('walkinVehicleType').value = '';
                document.getElementById('walkinVehicleNumber').value = '';
                document.getElementById('walkinVehicleColor').value = '';
                
                // Reset photo previews
                document.getElementById('walkinPhotoPreviewContainer').style.display = 'none';
                document.getElementById('walkinPhotoPreview').src = '';
                document.getElementById('walkinVehiclePhotosPreview').innerHTML = '';
                
                // Reset OTP section
                document.getElementById('walkinOtpInputContainer').style.display = 'none';
                document.getElementById('walkinOtpTimer').style.display = 'none';
                document.getElementById('walkinVerificationStatus').style.display = 'none';
                document.getElementById('walkinSuccessMessage').style.display = 'none';
                document.getElementById('walkinErrorMessage').style.display = 'none';
                document.getElementById('walkinGenerateOtpBtn').style.display = 'block';
                document.getElementById('walkinVerifyOtpBtn').style.display = 'none';
                document.getElementById('walkinResendOtpBtn').style.display = 'none';
                document.getElementById('walkinNext2').style.display = 'none';
                
                // Clear OTP inputs
                document.querySelectorAll('.otp-input').forEach(input => {
                    input.value = '';
                });
                
                // Clear timer
                if (otpTimer) {
                    clearInterval(otpTimer);
                    otpTimer = null;
                }
            }
            
            function showWalkinStep(stepNumber) {
                currentWalkinStep = stepNumber;
                
                // Hide all step sections
                document.querySelectorAll('.step-section').forEach(section => {
                    section.classList.remove('active');
                    section.style.display = 'none';
                });
                
                // Show current step section
                const currentSection = document.getElementById('walkinSection' + stepNumber);
                currentSection.classList.add('active');
                currentSection.style.display = 'block';
                
                // Update step indicators
                document.querySelectorAll('.step').forEach(step => {
                    const stepNum = parseInt(step.getAttribute('data-step'));
                    step.classList.remove('active', 'completed');
                    
                    if (stepNum < stepNumber) {
                        step.classList.add('completed');
                    } else if (stepNum === stepNumber) {
                        step.classList.add('active');
                    }
                });
                
                // Scroll to top of form
                currentSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            
            // Walk-in navigation buttons
            document.getElementById('walkinNext1').addEventListener('click', function() {
                // Validate step 1
                if (!validateStep1()) {
                    return;
                }
                
                // Save step 1 data
                walkinFormData.name = document.getElementById('walkinFullName').value;
                walkinFormData.contact = document.getElementById('walkinContactNumber').value;
                walkinFormData.email = document.getElementById('walkinEmail').value;
                walkinFormData.purpose = document.getElementById('walkinPurpose').value;
                walkinFormData.visitorPhoto = document.getElementById('walkinPhotoPreview').src || '';
                
                // Display mobile number for OTP
                document.getElementById('walkinMobileNumberDisplay').textContent = walkinFormData.contact;
                
                // Move to step 2
                showWalkinStep(2);
            });
            
            document.getElementById('walkinPrev2').addEventListener('click', function() {
                showWalkinStep(1);
            });
            
            document.getElementById('walkinPrev3').addEventListener('click', function() {
                showWalkinStep(2);
            });
            
            document.getElementById('walkinPrev4').addEventListener('click', function() {
                showWalkinStep(3);
            });
            
            // OTP functionality
            document.getElementById('walkinGenerateOtpBtn').addEventListener('click', function() {
                generateOTP();
            });
            
            document.getElementById('walkinVerifyOtpBtn').addEventListener('click', function() {
                verifyOTP();
            });
            
            document.getElementById('walkinResendOtpBtn').addEventListener('click', function() {
                generateOTP();
            });
            
            document.getElementById('walkinNext2').addEventListener('click', function() {
                showWalkinStep(3);
            });
            
            document.getElementById('walkinNext3').addEventListener('click', function() {
                // Validate step 3
                if (!validateStep3()) {
                    return;
                }
                
                // Save step 3 data
                walkinFormData.vehicleType = document.getElementById('walkinVehicleType').value;
                walkinFormData.vehicleNumber = document.getElementById('walkinVehicleNumber').value;
                walkinFormData.vehicleColor = document.getElementById('walkinVehicleColor').value;
                walkinFormData.vehiclePhotos = vehiclePhotos;
                
                // Generate visitor code
                const walkinCode = generateVisitorCode();
                walkinFormData.code = walkinCode;
                
                // Update display in step 4
                document.getElementById('walkinVisitorCode').textContent = walkinCode;
                document.getElementById('walkinFinalVisitorName').textContent = walkinFormData.name;
                document.getElementById('walkinFinalVisitorPurpose').textContent = walkinFormData.purpose;
                document.getElementById('walkinFinalVisitorContact').textContent = walkinFormData.contact;
                document.getElementById('walkinFinalVehicleType').textContent = walkinFormData.vehicleType;
                document.getElementById('walkinFinalVehicleNumber').textContent = walkinFormData.vehicleNumber;
                document.getElementById('walkinFinalVehiclePhotos').textContent = vehiclePhotos.length + ' photos';
                
                // Move to step 4
                showWalkinStep(4);
            });
            
            document.getElementById('walkinSaveAndComplete').addEventListener('click', function() {
                saveWalkinVisitor();
            });
            
            // Photo upload functionality
            document.getElementById('walkinPhotoUploadArea').addEventListener('click', function() {
                document.getElementById('walkinPhotoUpload').click();
            });
            
            document.getElementById('walkinPhotoUpload').addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        document.getElementById('walkinPhotoPreview').src = event.target.result;
                        document.getElementById('walkinPhotoPreviewContainer').style.display = 'block';
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
            
            document.getElementById('removeWalkinPhoto').addEventListener('click', function() {
                document.getElementById('walkinPhotoPreview').src = '';
                document.getElementById('walkinPhotoPreviewContainer').style.display = 'none';
                document.getElementById('walkinPhotoUpload').value = '';
            });
            
            document.getElementById('walkinVehiclePhotoUploadArea').addEventListener('click', function() {
                document.getElementById('walkinVehiclePhotoUpload').click();
            });
            
            document.getElementById('walkinVehiclePhotoUpload').addEventListener('change', function(e) {
                if (e.target.files) {
                    for (let i = 0; i < e.target.files.length; i++) {
                        const file = e.target.files[i];
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            addVehiclePhoto(event.target.result);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
            
            // Helper functions
            function validateStep1() {
                const name = document.getElementById('walkinFullName').value;
                const contact = document.getElementById('walkinContactNumber').value;
                const email = document.getElementById('walkinEmail').value;
                const purpose = document.getElementById('walkinPurpose').value;
                const hasPhoto = document.getElementById('walkinPhotoPreview').src !== '';
                
                let isValid = true;
                
                // Reset all validation states
                document.getElementById('walkinFullName').classList.remove('is-invalid');
                document.getElementById('walkinContactNumber').classList.remove('is-invalid');
                document.getElementById('walkinEmail').classList.remove('is-invalid');
                document.getElementById('walkinPurpose').classList.remove('is-invalid');
                document.getElementById('walkinPhotoError').style.display = 'none';
                
                if (!name.trim()) {
                    document.getElementById('walkinFullName').classList.add('is-invalid');
                    isValid = false;
                }
                
                if (!contact.match(/^\d{10}$/)) {
                    document.getElementById('walkinContactNumber').classList.add('is-invalid');
                    isValid = false;
                }
                
                if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                    document.getElementById('walkinEmail').classList.add('is-invalid');
                    isValid = false;
                }
                
                if (!purpose) {
                    document.getElementById('walkinPurpose').classList.add('is-invalid');
                    isValid = false;
                }
                
                if (!hasPhoto) {
                    document.getElementById('walkinPhotoError').style.display = 'block';
                    isValid = false;
                }
                
                if (!isValid) {
                    // Scroll to first error
                    document.getElementById('walkinSection1').scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                
                return isValid;
            }
            
            function validateStep3() {
                const vehicleType = document.getElementById('walkinVehicleType').value;
                const vehicleNumber = document.getElementById('walkinVehicleNumber').value;
                
                let isValid = true;
                
                // Reset all validation states
                document.getElementById('walkinVehicleType').classList.remove('is-invalid');
                document.getElementById('walkinVehicleNumber').classList.remove('is-invalid');
                document.getElementById('vehiclePhotoError').style.display = 'none';
                
                if (!vehicleType) {
                    document.getElementById('walkinVehicleType').classList.add('is-invalid');
                    isValid = false;
                }
                
                if (!vehicleNumber.trim()) {
                    document.getElementById('walkinVehicleNumber').classList.add('is-invalid');
                    isValid = false;
                }
                
                if (vehiclePhotos.length < 2) {
                    document.getElementById('vehiclePhotoError').style.display = 'block';
                    isValid = false;
                }
                
                if (!isValid) {
                    // Scroll to first error
                    document.getElementById('walkinSection3').scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                
                return isValid;
            }
            
            function generateOTP() {
                // Generate a 6-digit OTP
                generatedOTP = Math.floor(100000 + Math.random() * 900000).toString();
                console.log('Generated OTP:', generatedOTP); // For testing purposes
                
                // Show OTP input fields
                document.getElementById('walkinOtpInputContainer').style.display = 'flex';
                document.getElementById('walkinOtpTimer').style.display = 'block';
                document.getElementById('walkinVerificationStatus').style.display = 'none';
                document.getElementById('walkinSuccessMessage').style.display = 'none';
                document.getElementById('walkinErrorMessage').style.display = 'none';
                
                // Show verify button and resend option
                document.getElementById('walkinGenerateOtpBtn').style.display = 'none';
                document.getElementById('walkinVerifyOtpBtn').style.display = 'inline-block';
                document.getElementById('walkinResendOtpBtn').style.display = 'inline-block';
                
                // Focus on first OTP input
                document.querySelector('.otp-input[data-index="0"]').focus();
                
                // Start timer
                startOTPTimer();
                
                // In a real application, you would send this OTP via SMS
                alert(`OTP sent to ${walkinFormData.contact}: ${generatedOTP}\n\n(In a real system, this would be sent via SMS)`);
            }
            
            function startOTPTimer() {
                let timeLeft = 300; // 5 minutes in seconds
                
                if (otpTimer) {
                    clearInterval(otpTimer);
                }
                
                // Update timer immediately
                updateTimerDisplay(timeLeft);
                
                otpTimer = setInterval(function() {
                    timeLeft--;
                    updateTimerDisplay(timeLeft);
                    
                    if (timeLeft <= 0) {
                        clearInterval(otpTimer);
                        document.getElementById('walkinTimer').textContent = '00:00';
                        alert('OTP has expired. Please generate a new OTP.');
                    }
                }, 1000);
            }
            
            function updateTimerDisplay(seconds) {
                const minutes = Math.floor(seconds / 60);
                const secs = seconds % 60;
                document.getElementById('walkinTimer').textContent = 
                    minutes.toString().padStart(2, '0') + ':' + 
                    secs.toString().padStart(2, '0');
            }
            
            function verifyOTP() {
                // Get entered OTP
                let enteredOTP = '';
                document.querySelectorAll('.otp-input').forEach(input => {
                    enteredOTP += input.value;
                });
                
                if (enteredOTP === generatedOTP) {
                    // OTP verified successfully
                    document.getElementById('walkinSuccessMessage').style.display = 'block';
                    document.getElementById('walkinErrorMessage').style.display = 'none';
                    document.getElementById('walkinNext2').style.display = 'inline-block';
                    document.getElementById('walkinVerifyOtpBtn').style.display = 'none';
                    document.getElementById('walkinResendOtpBtn').style.display = 'none';
                    
                    // Clear timer
                    if (otpTimer) {
                        clearInterval(otpTimer);
                    }
                    
                    walkinFormData.otpVerified = true;
                } else {
                    // Invalid OTP
                    document.getElementById('walkinSuccessMessage').style.display = 'none';
                    document.getElementById('walkinErrorMessage').style.display = 'block';
                    
                    // Clear OTP inputs
                    document.querySelectorAll('.otp-input').forEach(input => {
                        input.value = '';
                    });
                    
                    // Focus on first input
                    document.querySelector('.otp-input[data-index="0"]').focus();
                }
                
                document.getElementById('walkinVerificationStatus').style.display = 'block';
            }
            
            function addVehiclePhoto(photoData) {
                vehiclePhotos.push(photoData);
                updateVehiclePhotosPreview();
            }
            
            function updateVehiclePhotosPreview() {
                const previewContainer = document.getElementById('walkinVehiclePhotosPreview');
                previewContainer.innerHTML = '';
                
                vehiclePhotos.forEach((photo, index) => {
                    const div = document.createElement('div');
                    div.className = 'preview-container';
                    div.innerHTML = `
                        <img src="${photo}" class="photo-preview">
                        <button type="button" class="remove-photo" data-index="${index}">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="preview-label mt-1">Photo ${index + 1}</div>
                    `;
                    previewContainer.appendChild(div);
                    
                    // Add event listener to remove button
                    div.querySelector('.remove-photo').addEventListener('click', function() {
                        const idx = parseInt(this.getAttribute('data-index'));
                        vehiclePhotos.splice(idx, 1);
                        updateVehiclePhotosPreview();
                    });
                });
            }
            
            function generateVisitorCode() {
                const prefix = 'VR-';
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                let randomPart = '';
                for (let i = 0; i < 6; i++) {
                    randomPart += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                return prefix + randomPart;
            }
            
            function saveWalkinVisitor() {
                // Create visitor object
                const visitor = {
                    id: Date.now(),
                    name: walkinFormData.name,
                    contact: walkinFormData.contact,
                    email: walkinFormData.email,
                    purpose: walkinFormData.purpose,
                    vehicleType: walkinFormData.vehicleType,
                    vehicleNumber: walkinFormData.vehicleNumber,
                    vehicleColor: walkinFormData.vehicleColor,
                    vehiclePhotos: walkinFormData.vehiclePhotos,
                    visitorPhoto: walkinFormData.visitorPhoto,
                    code: walkinFormData.code,
                    registrationTime: new Date().toISOString(),
                    status: 'Checked-in',
                    registrationType: 'Walk-in',
                    otpVerified: true
                };
                
                // Add to localStorage
                allVisitors.push(visitor);
                localStorage.setItem('visitorRegistrationData', JSON.stringify(allVisitors));
                
                // Show success message
                document.getElementById('walkinForm').style.display = 'none';
                document.getElementById('successMessage').style.display = 'block';
                document.getElementById('successDetails').innerHTML = `
                    ✅ <strong>${visitor.name}</strong> has been successfully registered and checked in.<br><br>
                    <div class="visitor-code-display" style="font-size: 28px; margin: 15px 0;">${visitor.code}</div>
                    <strong>Visitor Details:</strong><br>
                    👤 Name: ${visitor.name}<br>
                    📞 Contact: ${visitor.contact}<br>
                    📅 Purpose: ${visitor.purpose}<br>
                    🚗 Vehicle: ${visitor.vehicleType} (${visitor.vehicleNumber})<br>
                    🕒 Registration Time: <strong>${new Date().toLocaleTimeString()}</strong><br><br>
                    <span class="badge bg-info">Walk-in Registration</span>
                `;
                
                console.log('Walk-in visitor saved:', visitor);
            }
            
            // OTP input navigation
            document.querySelectorAll('.otp-input').forEach((input, index) => {
                input.addEventListener('input', function() {
                    if (this.value.length === 1 && index < 5) {
                        document.querySelector(`.otp-input[data-index="${index + 1}"]`).focus();
                    }
                });
                
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && this.value === '' && index > 0) {
                        document.querySelector(`.otp-input[data-index="${index - 1}"]`).focus();
                    }
                });
            });
            
            // Initialize on page load
            console.log('Visitor Check-in System initialized');
            console.log('Available codes:', allVisitors.map(v => v.code));
        });
    </script>
</body>
</html>
@endsection