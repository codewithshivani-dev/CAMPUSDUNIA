@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Registration System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- jsPDF Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
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
            max-width: 1200px;
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
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .progress-container {
            background-color: white;
            border-radius: var(--radius);
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin: 0 20px;
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
        
        .btn-pdf {
            background: linear-gradient(135deg, #e63946, #d00000);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
            color: white;
        }
        
        .btn-pdf:hover {
            background: linear-gradient(135deg, #d32f2f, #b71c1c);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(230, 57, 70, 0.4);
            color: white;
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
        
        .photo-preview {
            width: 150px;
            height: 150px;
            border-radius: 8px;
            object-fit: cover;
            display: none;
            margin: 0 auto 10px;
            border: 3px solid #dee2e6;
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
        }
        
        .preview-label {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
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
        
        .pdf-download-container {
            background: linear-gradient(135deg, #ff9e00, #ff7b00);
            color: white;
            border-radius: var(--radius);
            padding: 25px;
            margin-top: 25px;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .step-indicator {
                margin: 0 10px;
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
            
            .otp-input {
                width: 40px;
                height: 50px;
                font-size: 20px;
            }
            
            .visitor-code-display {
                font-size: 28px;
                padding: 12px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container main-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="system-logo">
                <i class="fas fa-user-plus"></i>
                <div>
                    Visitor Registration System
                    <div class="fs-6 fw-normal">Secure and Efficient Registration</div>
                </div>
            </div>
            <p class="mb-0 mt-2">Complete all steps to register and receive your visitor code</p>
        </div>
        
        <!-- Progress Section -->
        <div class="progress-container">
            <div class="step-indicator">
                <div class="step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-label">Personal Info</div>
                </div>
                <div class="step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-label">Visit Details</div>
                </div>
                <div class="step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-label">OTP Verification</div>
                </div>
                <div class="step" data-step="4">
                    <div class="step-number">4</div>
                    <div class="step-label">Visitor Code</div>
                </div>
            </div>
        </div>
        
        <!-- Form Container -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-user-plus me-2"></i>Visitor Registration Form</h4>
            </div>
            <div class="card-body p-0">
                <form id="visitorForm">
                    <!-- Step 1: Personal Information -->
                    <div class="form-section active" id="section1">
                        <div class="section-title">
                            <i class="fas fa-user-circle"></i>
                            <h5 class="mb-0">Step 1: Personal Information</h5>
                        </div>
                        
                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            <p>Please provide your personal details for identification and security purposes.</p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fullName" class="form-label required">Full Name</label>
                                <input type="text" class="form-control" id="fullName" placeholder="Enter your full name" required>
                                <div class="invalid-feedback">Please provide your full name.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contactNumber" class="form-label required">Contact Number</label>
                                <input type="tel" class="form-control" id="contactNumber" placeholder="Enter 10-digit mobile number" required>
                                <div class="invalid-feedback">Please provide a valid 10-digit contact number.</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="email" class="form-label required">Email Address</label>
                                <input type="email" class="form-control" id="email" placeholder="Enter your email address" required>
                                <div class="invalid-feedback">Please provide a valid email address.</div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label required">Upload Photo</label>
                            <div class="photo-upload-container" id="visitorPhotoUploadArea">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <h5 class="mb-2">Click to upload your photo</h5>
                                <p class="text-muted mb-0">JPG, PNG - Max 5MB</p>
                                <input type="file" id="visitorPhotoUpload" accept="image/*" class="d-none" required>
                            </div>
                            <div class="preview-container">
                                <img id="visitorPhotoPreview" class="photo-preview" src="" alt="Visitor Photo Preview">
                                <div class="preview-label">Your Photo Preview</div>
                            </div>
                            <div class="invalid-feedback">Please upload your photo.</div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-primary" id="next1">
                                Next Step <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 2: Visit Details -->
                    <div class="form-section" id="section2">
                        <div class="section-title">
                            <i class="fas fa-clipboard-list"></i>
                            <h5 class="mb-0">Step 2: Visit Details</h5>
                        </div>
                        
                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            <p>Provide details about your visit and vehicle information (if applicable).</p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="purpose" class="form-label required">Purpose of Visit</label>
                                <select class="form-select" id="purpose" required>
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
                        
                        <div class="vehicle-details-section">
                            <h6 class="mb-3"><i class="fas fa-car me-2"></i>Vehicle Details (Optional)</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="vehicleType" class="form-label">Vehicle Type</label>
                                    <select class="form-select" id="vehicleType">
                                        <option value="" selected>Select Vehicle Type</option>
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
                                    <label for="vehicleNumber" class="form-label">Vehicle Number</label>
                                    <input type="text" class="form-control" id="vehicleNumber" placeholder="e.g. DL01AB1234">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="vehicleColor" class="form-label">Vehicle Color</label>
                                    <input type="text" class="form-control" id="vehicleColor" placeholder="e.g. Red, White, Black">
                                </div>
                            </div>
                            
                            <!-- Vehicle Photo Upload -->
                            <div class="mb-3">
                                <label class="form-label">Upload Vehicle Photos (Optional)</label>
                                <div class="photo-upload-container" id="vehiclePhotoUploadArea">
                                    <i class="fas fa-car fa-3x text-muted mb-3"></i>
                                    <h5 class="mb-2">Click to upload vehicle photos</h5>
                                    <p class="text-muted mb-0">Upload multiple photos (max 3)</p>
                                    <input type="file" id="vehiclePhotoUpload" accept="image/*" multiple class="d-none">
                                </div>
                                <div id="vehiclePhotosPreview" class="preview-row"></div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary" id="prev2">
                                <i class="fas fa-arrow-left me-2"></i>Previous
                            </button>
                            <button type="button" class="btn btn-primary" id="next2">
                                Next Step <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 3: OTP Verification -->
                    <div class="form-section" id="section3">
                        <div class="section-title">
                            <i class="fas fa-shield-alt"></i>
                            <h5 class="mb-0">Step 3: OTP Verification</h5>
                        </div>
                        
                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            <p>For security verification, we need to verify your contact number via OTP.</p>
                        </div>
                        
                        <div class="otp-container">
                            <h6><i class="fas fa-mobile-alt me-2"></i>Mobile Number Verification</h6>
                            <p class="mb-3">An OTP will be sent to your mobile number: <strong id="mobileNumberDisplay"></strong></p>
                            
                            <div class="otp-input-container" id="otpInputContainer" style="display: none;">
                                <input type="text" maxlength="1" class="otp-input" data-index="0">
                                <input type="text" maxlength="1" class="otp-input" data-index="1">
                                <input type="text" maxlength="1" class="otp-input" data-index="2">
                                <input type="text" maxlength="1" class="otp-input" data-index="3">
                                <input type="text" maxlength="1" class="otp-input" data-index="4">
                                <input type="text" maxlength="1" class="otp-input" data-index="5">
                            </div>
                            
                            <div class="otp-timer" id="otpTimer" style="display: none;">
                                OTP valid for: <span id="timer">05:00</span>
                            </div>
                            
                            <div id="verificationStatus" class="mt-3" style="display: none;">
                                <div class="alert alert-success" id="successMessage" style="display: none;">
                                    <i class="fas fa-check-circle me-2"></i>OTP verified successfully!
                                </div>
                                <div class="alert alert-danger" id="errorMessage" style="display: none;">
                                    <i class="fas fa-times-circle me-2"></i>Invalid OTP. Please try again.
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="button" class="btn btn-primary" id="generateOtpBtn">
                                    <i class="fas fa-key me-2"></i>Generate OTP
                                </button>
                                <button type="button" class="btn btn-success" id="verifyOtpBtn" style="display: none;">
                                    <i class="fas fa-check-circle me-2"></i>Verify OTP
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="resendOtpBtn" style="display: none;">
                                    <i class="fas fa-redo me-2"></i>Resend OTP
                                </button>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary" id="prev3">
                                <i class="fas fa-arrow-left me-2"></i>Previous
                            </button>
                            <button type="button" class="btn btn-primary" id="proceedToCodeBtn" style="display: none;">
                                Generate Visitor Code <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 4: Generate Visitor Code -->
                    <div class="form-section" id="section4">
                        <div class="section-title">
                            <i class="fas fa-qrcode"></i>
                            <h5 class="mb-0">Step 4: Generate Visitor Code</h5>
                        </div>
                        
                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            <p>Your visitor code has been generated. Please save this code and show it at the reception.</p>
                        </div>
                        
                        <div class="visitor-code-container">
                            <h5><i class="fas fa-id-card me-2"></i>Your Visitor Code</h5>
                            <div class="visitor-code-display" id="visitorCode">VR-000000</div>
                            <div class="visitor-details mt-4" style="position: relative; z-index: 2;">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <p class="mb-1"><strong>Name:</strong></p>
                                        <p id="finalVisitorName">Visitor Name</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <p class="mb-1"><strong>Purpose:</strong></p>
                                        <p id="finalVisitorPurpose">Purpose</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <p class="mb-1"><strong>Contact:</strong></p>
                                        <p id="finalVisitorContact">Contact</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <p class="mb-1"><strong>Vehicle Type:</strong></p>
                                        <p id="finalVehicleType">-</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <p class="mb-1"><strong>Vehicle Number:</strong></p>
                                        <p id="finalVehicleNumber">-</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <p class="mb-1"><strong>Vehicle Color:</strong></p>
                                        <p id="finalVehicleColor">-</p>
                                    </div>
                                </div>
                            </div>
                            <small class="mt-3 d-block opacity-75" style="position: relative; z-index: 2;">Valid for 24 hours from registration</small>
                        </div>
                        
                        <!-- PDF Download Section -->
                        <div class="pdf-download-container mt-4">
                            <h5><i class="fas fa-file-pdf me-2"></i>Download Visitor Pass</h5>
                            <p class="mb-3">Download your visitor details as a PDF for your records</p>
                            <button type="button" class="btn btn-pdf" id="downloadPdfBtn">
                                <i class="fas fa-download me-2"></i>Download PDF
                            </button>
                            <div class="mt-3">
                                <small class="opacity-75">Save this PDF and show it at the reception</small>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary" id="prev4">
                                <i class="fas fa-arrow-left me-2"></i>Previous
                            </button>
                            <button type="button" class="btn btn-success" id="saveAndSubmit">
                                <i class="fas fa-save me-2"></i>Save & Complete Registration
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!--<div class="footer-note text-center mt-4">-->
        <!--    <p class="text-muted">© 2023 Visitor Registration System | All rights reserved</p>-->
        <!--</div>-->
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Step navigation
            const steps = document.querySelectorAll('.step');
            const sections = document.querySelectorAll('.form-section');
            let timerInterval;
            let visitorData = {};
            let vehiclePhotos = [];
            let generatedOtp = '';
            let isOtpVerified = false;
            let visitorCode = '';
            
            // Next button handlers
            document.getElementById('next1').addEventListener('click', function() {
                if (validateSection(1)) {
                    navigateToStep(2);
                }
            });
            
            document.getElementById('next2').addEventListener('click', function() {
                if (validateSection(2)) {
                    navigateToStep(3);
                    updateMobileNumberDisplay();
                }
            });
            
            // Previous button handlers
            document.getElementById('prev2').addEventListener('click', function() {
                navigateToStep(1);
            });
            
            document.getElementById('prev3').addEventListener('click', function() {
                navigateToStep(2);
            });
            
            document.getElementById('prev4').addEventListener('click', function() {
                navigateToStep(3);
            });
            
            // Visitor Photo upload functionality
            document.getElementById('visitorPhotoUploadArea').addEventListener('click', function() {
                document.getElementById('visitorPhotoUpload').click();
            });
            
            document.getElementById('visitorPhotoUpload').addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        document.getElementById('visitorPhotoPreview').src = e.target.result;
                        document.getElementById('visitorPhotoPreview').style.display = 'block';
                    }
                    
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
            
            // Vehicle Photos upload functionality
            document.getElementById('vehiclePhotoUploadArea').addEventListener('click', function() {
                document.getElementById('vehiclePhotoUpload').click();
            });
            
            document.getElementById('vehiclePhotoUpload').addEventListener('change', function(e) {
                if (e.target.files && e.target.files.length > 0) {
                    const files = Array.from(e.target.files);
                    vehiclePhotos = [];
                    const previewContainer = document.getElementById('vehiclePhotosPreview');
                    previewContainer.innerHTML = '';
                    
                    // Limit to 3 photos
                    const limitedFiles = files.slice(0, 3);
                    
                    limitedFiles.forEach((file, index) => {
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            // Create preview container
                            const previewDiv = document.createElement('div');
                            previewDiv.className = 'preview-container';
                            
                            // Create image element
                            const img = document.createElement('img');
                            img.className = 'photo-preview';
                            img.src = e.target.result;
                            img.style.display = 'block';
                            img.alt = `Vehicle Photo ${index + 1}`;
                            img.style.width = '120px';
                            img.style.height = '120px';
                            
                            // Create label
                            const label = document.createElement('div');
                            label.className = 'preview-label';
                            label.textContent = `Photo ${index + 1}`;
                            
                            // Append to container
                            previewDiv.appendChild(img);
                            previewDiv.appendChild(label);
                            previewContainer.appendChild(previewDiv);
                            
                            // Store photo data
                            vehiclePhotos.push(e.target.result);
                        }
                        
                        reader.readAsDataURL(file);
                    });
                }
            });
            
            // Generate OTP
            document.getElementById('generateOtpBtn').addEventListener('click', function() {
                const contactNumber = document.getElementById('contactNumber').value;
                
                // Generate random 6-digit OTP
                generatedOtp = Math.floor(100000 + Math.random() * 900000).toString();
                
                // Display OTP input fields
                document.getElementById('otpInputContainer').style.display = 'flex';
                document.getElementById('otpTimer').style.display = 'block';
                document.getElementById('verifyOtpBtn').style.display = 'inline-block';
                document.getElementById('resendOtpBtn').style.display = 'inline-block';
                document.getElementById('generateOtpBtn').style.display = 'none';
                
                // Clear any previous OTP inputs
                clearOtpInputs();
                
                // Show success message
                alert(`OTP ${generatedOtp} has been sent to your mobile number. Please enter the 6-digit code to verify.`);
                
                // For demo purposes, you can log the OTP to console
                console.log('Generated OTP:', generatedOtp);
                
                // Start OTP timer (5 minutes)
                startOtpTimer();
            });
            
            // Verify OTP
            document.getElementById('verifyOtpBtn').addEventListener('click', function() {
                const enteredOtp = getEnteredOtp();
                
                if (enteredOtp === generatedOtp) {
                    // OTP verified successfully
                    isOtpVerified = true;
                    
                    // Show success message
                    document.getElementById('successMessage').style.display = 'block';
                    document.getElementById('errorMessage').style.display = 'none';
                    document.getElementById('verificationStatus').style.display = 'block';
                    
                    // Enable proceed button
                    document.getElementById('proceedToCodeBtn').style.display = 'inline-block';
                    document.getElementById('verifyOtpBtn').style.display = 'none';
                    document.getElementById('resendOtpBtn').style.display = 'none';
                    
                    // Clear intervals
                    clearInterval(timerInterval);
                } else {
                    // Invalid OTP
                    document.getElementById('successMessage').style.display = 'none';
                    document.getElementById('errorMessage').style.display = 'block';
                    document.getElementById('verificationStatus').style.display = 'block';
                    
                    // Shake OTP inputs for error effect
                    const otpInputs = document.querySelectorAll('.otp-input');
                    otpInputs.forEach(input => {
                        input.classList.add('border-danger');
                        setTimeout(() => input.classList.remove('border-danger'), 500);
                    });
                }
            });
            
            // Resend OTP
            document.getElementById('resendOtpBtn').addEventListener('click', function() {
                // Generate new OTP
                generatedOtp = Math.floor(100000 + Math.random() * 900000).toString();
                
                // Clear previous OTP inputs
                clearOtpInputs();
                
                // Reset timer
                startOtpTimer();
                
                // Show message
                alert(`New OTP ${generatedOtp} has been sent to your mobile number.`);
            });
            
            // Proceed to Generate Visitor Code
            document.getElementById('proceedToCodeBtn').addEventListener('click', function() {
                if (isOtpVerified) {
                    generateVisitorCode();
                    updateFinalDetails();
                    navigateToStep(4);
                } else {
                    alert('Please verify your OTP first.');
                }
            });
            
            // Save and Submit
            document.getElementById('saveAndSubmit').addEventListener('click', function() {
                // Make sure all data is collected before saving
                collectVisitorData();
                
                if (visitorData && visitorData.code) {
                    saveVisitorData();
                } else {
                    alert('Please complete all steps and generate a visitor code first.');
                }
            });
            
            // Download PDF button
            document.getElementById('downloadPdfBtn').addEventListener('click', function() {
                downloadVisitorPassPDF();
            });
            
            // OTP Input handling
            document.querySelectorAll('.otp-input').forEach(input => {
                input.addEventListener('input', function(e) {
                    const value = e.target.value;
                    const index = parseInt(this.getAttribute('data-index'));
                    
                    // Only allow numbers
                    if (value && !/^\d$/.test(value)) {
                        this.value = '';
                        return;
                    }
                    
                    // Auto-focus next input
                    if (value && index < 5) {
                        document.querySelector(`.otp-input[data-index="${index + 1}"]`).focus();
                    }
                    
                    // Auto-verify when all 6 digits are entered
                    if (index === 5 && value && getEnteredOtp().length === 6) {
                        // Auto-verify after a short delay
                        setTimeout(() => {
                            document.getElementById('verifyOtpBtn').click();
                        }, 300);
                    }
                });
                
                // Handle backspace
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value && parseInt(this.getAttribute('data-index')) > 0) {
                        document.querySelector(`.otp-input[data-index="${parseInt(this.getAttribute('data-index')) - 1}"]`).focus();
                    }
                });
                
                // Paste OTP
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text').trim();
                    
                    if (/^\d{6}$/.test(pastedData)) {
                        const digits = pastedData.split('');
                        digits.forEach((digit, index) => {
                            if (index <= 5) {
                                const otpInput = document.querySelector(`.otp-input[data-index="${index}"]`);
                                if (otpInput) {
                                    otpInput.value = digit;
                                }
                            }
                        });
                        
                        // Auto-verify after paste
                        setTimeout(() => {
                            if (getEnteredOtp().length === 6) {
                                document.getElementById('verifyOtpBtn').click();
                            }
                        }, 100);
                    }
                });
            });
            
            // Function to navigate between steps
            function navigateToStep(stepNumber) {
                // Update step indicators
                steps.forEach(step => {
                    const stepNum = parseInt(step.getAttribute('data-step'));
                    if (stepNum < stepNumber) {
                        step.classList.add('completed');
                        step.classList.remove('active');
                    } else if (stepNum === stepNumber) {
                        step.classList.add('active');
                        step.classList.remove('completed');
                    } else {
                        step.classList.remove('active', 'completed');
                    }
                });
                
                // Show/hide sections
                sections.forEach(section => {
                    section.classList.remove('active');
                });
                document.getElementById('section' + stepNumber).classList.add('active');
            }
            
            // Function to validate form sections
            function validateSection(sectionNumber) {
                let isValid = true;
                const section = document.getElementById('section' + sectionNumber);
                const inputs = section.querySelectorAll('input, select, textarea');
                
                inputs.forEach(input => {
                    // Check if required and empty
                    if (input.hasAttribute('required') && !input.value.trim()) {
                        // Special handling for file input
                        if (input.type === 'file') {
                            const preview = document.getElementById('visitorPhotoPreview');
                            if (!preview || !preview.src || preview.src === '') {
                                input.classList.add('is-invalid');
                                isValid = false;
                            }
                        } else {
                            input.classList.add('is-invalid');
                            isValid = false;
                        }
                    } else {
                        input.classList.remove('is-invalid');
                    }
                    
                    // Email validation
                    if (input.type === 'email' && input.value) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(input.value)) {
                            input.classList.add('is-invalid');
                            isValid = false;
                        }
                    }
                    
                    // Phone validation
                    if (input.id === 'contactNumber' && input.value) {
                        const phoneRegex = /^[0-9]{10}$/;
                        if (!phoneRegex.test(input.value.replace(/\D/g, ''))) {
                            input.classList.add('is-invalid');
                            isValid = false;
                        }
                    }
                });
                
                return isValid;
            }
            
            // Function to update mobile number display
            function updateMobileNumberDisplay() {
                const contactNumber = document.getElementById('contactNumber').value;
                document.getElementById('mobileNumberDisplay').textContent = contactNumber;
            }
            
            // Function to generate visitor code
            function generateVisitorCode() {
                // Generate random alphanumeric code
                const prefix = 'VR-';
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                let randomPart = '';
                
                for (let i = 0; i < 6; i++) {
                    randomPart += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                
                visitorCode = prefix + randomPart;
                
                // Display the code
                document.getElementById('visitorCode').textContent = visitorCode;
                
                return visitorCode;
            }
            
            // Function to collect all visitor data
            function collectVisitorData() {
                try {
                    // Get visitor photo preview
                    const visitorPhotoPreview = document.getElementById('visitorPhotoPreview');
                    const visitorPhoto = visitorPhotoPreview && visitorPhotoPreview.src.includes('data:image') ? visitorPhotoPreview.src : '';
                    
                    // Get vehicle photos
                    const vehiclePhotoElements = document.querySelectorAll('#vehiclePhotosPreview img');
                    const vehiclePhotoData = [];
                    vehiclePhotoElements.forEach(img => {
                        if (img.src && img.src.includes('data:image')) {
                            vehiclePhotoData.push(img.src);
                        }
                    });
                    
                    // Store visitor data
                    visitorData = {
                        id: Date.now(),
                        name: document.getElementById('fullName').value || '',
                        contact: document.getElementById('contactNumber').value || '',
                        email: document.getElementById('email').value || '',
                        purpose: document.getElementById('purpose').value || '',
                        vehicleType: document.getElementById('vehicleType').value || '',
                        vehicleNumber: document.getElementById('vehicleNumber').value || '',
                        vehicleColor: document.getElementById('vehicleColor').value || '',
                        vehiclePhotos: vehiclePhotoData,
                        visitorPhoto: visitorPhoto,
                        code: visitorCode || '',
                        registrationTime: new Date().toISOString(),
                        status: 'Registered',
                        otpVerified: isOtpVerified,
                        frontDeskActions: [] // Initialize empty array for front desk actions
                    };
                    
                    console.log('Visitor data collected successfully');
                    return visitorData;
                } catch (error) {
                    console.error('Error collecting visitor data:', error);
                    return null;
                }
            }
            
            // Function to update final details
            function updateFinalDetails() {
                try {
                    // First collect the data
                    collectVisitorData();
                    
                    // Then update the display
                    if (visitorData) {
                        document.getElementById('finalVisitorName').textContent = visitorData.name || 'Not provided';
                        document.getElementById('finalVisitorPurpose').textContent = visitorData.purpose || 'Not provided';
                        document.getElementById('finalVisitorContact').textContent = visitorData.contact || 'Not provided';
                        document.getElementById('finalVehicleType').textContent = visitorData.vehicleType || '-';
                        document.getElementById('finalVehicleNumber').textContent = visitorData.vehicleNumber || '-';
                        document.getElementById('finalVehicleColor').textContent = visitorData.vehicleColor || '-';
                    }
                } catch (error) {
                    console.error('Error updating final details:', error);
                }
            }
            
            // Function to start OTP timer
            function startOtpTimer() {
                let timeLeft = 300; // 5 minutes in seconds
                
                // Clear any existing timer
                clearInterval(timerInterval);
                
                // Update timer every second
                timerInterval = setInterval(function() {
                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;
                    
                    document.getElementById('timer').textContent = 
                        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    
                    timeLeft--;
                    
                    if (timeLeft < 0) {
                        clearInterval(timerInterval);
                        document.getElementById('otpTimer').innerHTML = 
                            '<span class="text-danger">OTP expired. Please generate a new one.</span>';
                        document.getElementById('verifyOtpBtn').disabled = true;
                    }
                }, 1000);
            }
            
            // Function to save visitor data to localStorage
            function saveVisitorData() {
                try {
                    // Make sure we have the latest data
                    if (!visitorData || !visitorData.code) {
                        collectVisitorData();
                    }
                    
                    // Check if we have valid data
                    if (!visitorData || !visitorData.code) {
                        alert('Please generate a visitor code first by completing all steps.');
                        return;
                    }
                    
                    // Get existing visitors or initialize empty array
                    const existingVisitors = JSON.parse(localStorage.getItem('visitorRegistrationData') || '[]');
                    
                    // Check if visitor with this code already exists
                    const existingIndex = existingVisitors.findIndex(v => v.code === visitorData.code);
                    
                    if (existingIndex !== -1) {
                        // Update existing visitor
                        existingVisitors[existingIndex] = visitorData;
                    } else {
                        // Add new visitor
                        existingVisitors.push(visitorData);
                    }
                    
                    // Save back to localStorage
                    localStorage.setItem('visitorRegistrationData', JSON.stringify(existingVisitors));
                    
                    // Show success message with code
                    alert(`✅ Registration completed successfully!\n\n📋 Your Visitor Code: ${visitorData.code}\n👤 Name: ${visitorData.name}\n📞 Contact: ${visitorData.contact}\n\n⚠️ IMPORTANT: Save this code for check-in!\n🔑 Code: ${visitorData.code}\n\n📄 You can now download your visitor pass as PDF.`);
                    
                    console.log('=== REGISTRATION SUCCESSFUL ===');
                    console.log('Visitor Code:', visitorData.code);
                    console.log('Name:', visitorData.name);
                    console.log('Contact:', visitorData.contact);
                    console.log('Total Visitors:', existingVisitors.length);
                    console.log('==============================');
                    
                } catch (error) {
                    console.error('Error saving visitor data:', error);
                    alert('Error saving registration. Please try again.');
                }
            }
            
            // Function to download PDF
            function downloadVisitorPassPDF() {
                try {
                    if (!visitorData || !visitorData.code) {
                        alert('Please complete the registration first.');
                        return;
                    }
                    
                    // Show loading message
                    alert('Generating PDF... Please wait.');
                    
                    // Create PDF using jsPDF
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF('p', 'mm', 'a4');
                    
                    // Set up PDF content
                    const pageWidth = doc.internal.pageSize.getWidth();
                    const pageHeight = doc.internal.pageSize.getHeight();
                    
                    // Add header with gradient effect
                    doc.setFillColor(67, 97, 238); // Primary color
                    doc.rect(0, 0, pageWidth, 40, 'F');
                    
                    // Add header text
                    doc.setTextColor(255, 255, 255);
                    doc.setFontSize(20);
                    doc.setFont('helvetica', 'bold');
                    doc.text('VISITOR PASS', pageWidth / 2, 20, { align: 'center' });
                    
                    doc.setFontSize(12);
                    doc.text('Visitor Registration System', pageWidth / 2, 28, { align: 'center' });
                    
                    // Add visitor code in a box
                    doc.setFillColor(255, 255, 255);
                    doc.rect(50, 45, pageWidth - 100, 20, 'F');
                    doc.setTextColor(67, 97, 238);
                    doc.setFontSize(24);
                    doc.setFont('helvetica', 'bold');
                    doc.text(visitorData.code, pageWidth / 2, 58, { align: 'center' });
                    
                    // Add visitor details
                    doc.setTextColor(0, 0, 0);
                    doc.setFontSize(16);
                    doc.setFont('helvetica', 'bold');
                    doc.text('Visitor Details', 20, 85);
                    
                    doc.setFontSize(12);
                    doc.setFont('helvetica', 'normal');
                    
                    const details = [
                        [`Name: ${visitorData.name}`],
                        [`Contact: ${visitorData.contact}`],
                        [`Email: ${visitorData.email}`],
                        [`Purpose: ${visitorData.purpose}`],
                        [`Registration Date: ${new Date(visitorData.registrationTime).toLocaleDateString()}`],
                        [`Registration Time: ${new Date(visitorData.registrationTime).toLocaleTimeString()}`]
                    ];
                    
                    if (visitorData.vehicleType) {
                        details.push([`Vehicle Type: ${visitorData.vehicleType}`]);
                    }
                    if (visitorData.vehicleNumber) {
                        details.push([`Vehicle Number: ${visitorData.vehicleNumber}`]);
                    }
                    if (visitorData.vehicleColor) {
                        details.push([`Vehicle Color: ${visitorData.vehicleColor}`]);
                    }
                    
                    let yPos = 100;
                    details.forEach(detail => {
                        doc.text(detail, 20, yPos);
                        yPos += 8;
                    });
                    
                    // Add important notes
                    doc.setFont('helvetica', 'bold');
                    doc.text('Important Instructions:', 20, yPos + 10);
                    doc.setFont('helvetica', 'normal');
                    
                    const instructions = [
                        '1. Show this pass at the reception desk',
                        '2. Keep this pass with you at all times',
                        '3. Valid for 24 hours from registration',
                        '4. Do not share your visitor code with others',
                        '5. Follow all security protocols within the premises'
                    ];
                    
                    yPos += 20;
                    instructions.forEach(instruction => {
                        doc.text(instruction, 20, yPos);
                        yPos += 7;
                    });
                    
                    // Add footer
                    doc.setFontSize(10);
                    doc.setTextColor(100, 100, 100);
                    doc.text('© 2023 Visitor Registration System. All rights reserved.', pageWidth / 2, pageHeight - 10, { align: 'center' });
                    
                    // Add border
                    doc.setDrawColor(67, 97, 238);
                    doc.setLineWidth(1);
                    doc.rect(10, 10, pageWidth - 20, pageHeight - 20);
                    
                    // Save the PDF
                    const fileName = `Visitor_Pass_${visitorData.code}_${visitorData.name.replace(/\s+/g, '_')}.pdf`;
                    doc.save(fileName);
                    
                    // Show success message
                    alert(`✅ Visitor pass downloaded successfully!\n\nFile: ${fileName}\n\nPlease save this PDF for your records.`);
                    
                } catch (error) {
                    console.error('Error generating PDF:', error);
                    alert('Error generating PDF. Please try again.');
                }
            }
            
            // Function to get entered OTP
            function getEnteredOtp() {
                let otp = '';
                document.querySelectorAll('.otp-input').forEach(input => {
                    otp += input.value;
                });
                return otp;
            }
            
            // Function to clear OTP inputs
            function clearOtpInputs() {
                document.querySelectorAll('.otp-input').forEach(input => {
                    input.value = '';
                    input.classList.remove('border-danger');
                });
                document.getElementById('verificationStatus').style.display = 'none';
            }
            
            // Function to reset form
            function resetForm() {
                try {
                    // Reset form inputs
                    document.getElementById('visitorForm').reset();
                    
                    // Reset photo previews
                    document.getElementById('visitorPhotoPreview').src = '';
                    document.getElementById('visitorPhotoPreview').style.display = 'none';
                    document.getElementById('vehiclePhotosPreview').innerHTML = '';
                    
                    // Reset variables
                    vehiclePhotos = [];
                    generatedOtp = '';
                    isOtpVerified = false;
                    visitorCode = '';
                    visitorData = {};
                    
                    // Reset OTP UI
                    clearOtpInputs();
                    document.getElementById('otpInputContainer').style.display = 'none';
                    document.getElementById('otpTimer').style.display = 'none';
                    document.getElementById('verifyOtpBtn').style.display = 'none';
                    document.getElementById('resendOtpBtn').style.display = 'none';
                    document.getElementById('generateOtpBtn').style.display = 'inline-block';
                    document.getElementById('proceedToCodeBtn').style.display = 'none';
                    document.getElementById('verificationStatus').style.display = 'none';
                    
                    // Clear timer
                    clearInterval(timerInterval);
                    
                    // Reset steps
                    steps.forEach(step => {
                        step.classList.remove('active', 'completed');
                    });
                    steps[0].classList.add('active');
                    
                    // Navigate to first section
                    sections.forEach(section => {
                        section.classList.remove('active');
                    });
                    document.getElementById('section1').classList.add('active');
                    
                    console.log('Form reset successfully');
                } catch (error) {
                    console.error('Error resetting form:', error);
                }
            }
            
            // Initialize with sample data if empty
            function initializeSampleData() {
                try {
                    const existingVisitors = JSON.parse(localStorage.getItem('visitorRegistrationData') || '[]');
                    if (existingVisitors.length === 0) {
                        const sampleVisitors = [
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
                                otpVerified: true,
                                frontDeskActions: []
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
                                otpVerified: true,
                                frontDeskActions: []
                            }
                        ];
                        localStorage.setItem('visitorRegistrationData', JSON.stringify(sampleVisitors));
                        console.log('Sample data initialized with 2 visitors');
                    }
                } catch (error) {
                    console.error('Error initializing sample data:', error);
                }
            }
            
            // Initialize sample data on page load
            initializeSampleData();
        });
    </script>
</body>
</html>
@endsection