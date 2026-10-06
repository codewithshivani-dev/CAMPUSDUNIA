@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Front Desk Management System</title>
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
            --info-color: #00b4d8;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gradient-primary: linear-gradient(135deg, #4361ee, #3a0ca3);
            --gradient-success: linear-gradient(135deg, #4bb543, #2a9d40);
            --gradient-info: linear-gradient(135deg, #00b4d8, #0077b6);
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --radius: 12px;
        }
        
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
        
        .visitor-code-container {
            background: var(--gradient-info);
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
        
        .tab-container {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 25px;
        }
        
        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
        }
        
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 8px 8px 0 0;
            margin-right: 5px;
            transition: all 0.3s ease;
        }
        
        .nav-tabs .nav-link:hover {
            color: var(--primary-color);
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            background-color: white;
            border-bottom: 3px solid var(--primary-color);
            font-weight: 600;
        }
        
        .tab-content {
            padding: 25px 0;
        }
        
        .visitor-photo-large {
            width: 150px;
            height: 150px;
            border-radius: 8px;
            object-fit: cover;
            border: 3px solid #dee2e6;
            margin: 10px 0;
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
        
        .form-control, .form-select, .form-textarea {
            border-radius: 8px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .form-textarea {
            min-height: 120px;
            resize: vertical;
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
        
        .btn-info {
            background: var(--gradient-info);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 180, 216, 0.4);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #ff9e00, #ff7b00);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 158, 0, 0.4);
        }
        
        .gate-pass-container {
            background: white;
            border: 2px dashed var(--primary-color);
            border-radius: var(--radius);
            padding: 30px;
            margin-top: 25px;
            text-align: center;
            box-shadow: var(--shadow);
        }
        
        .gate-pass-title {
            color: var(--primary-color);
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .gate-pass-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        
        .gate-pass-qr {
            margin: 20px auto;
            width: 150px;
            height: 150px;
            background: #e9ecef;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .check-in-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-left: 10px;
        }
        
        .badge-self {
            background-color: #d4edda;
            color: #155724;
        }
        
        .badge-walkin {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .badge-notification {
            background-color: #fff3cd;
            color: #856404;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .time-input-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .time-input-container .form-control {
            flex: 1;
        }
        
        .meeting-history-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: var(--radius);
            padding: 20px;
            margin-top: 15px;
            transition: all 0.3s ease;
        }
        
        .meeting-history-card:hover {
            border-color: var(--info-color);
            box-shadow: var(--shadow);
        }
        
        .meeting-status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 8px;
        }
        
        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .badge-completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .badge-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .notification-alert {
            animation: slideIn 0.5s ease-out;
            border-left: 4px solid var(--warning-color);
        }
        
        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        .notification-bell {
            position: relative;
            display: inline-block;
        }
        
        .notification-bell .badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        @media (max-width: 768px) {
            .header-section {
                padding: 20px;
            }
            
            .system-logo {
                font-size: 22px;
            }
            
            .visitor-code-display {
                font-size: 28px;
                padding: 12px 20px;
            }
            
            .nav-tabs .nav-link {
                padding: 10px 15px;
                font-size: 14px;
                margin-bottom: 5px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons .btn {
                width: 100%;
            }
            
            .time-input-container {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>

    <div class="container-fluid main-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="system-logo">
                <div class="notification-bell">
                    <i class="fas fa-concierge-bell"></i>
                    <span class="badge" id="notificationBadge" style="display: none;">!</span>
                </div>
                <div>
                    Front Desk Management System
                    <div class="fs-6 fw-normal">Visitor Management & Gate Pass</div>
                </div>
            </div>
            <p class="mb-0 mt-2">Enter visitor code to manage check-in and generate gate pass</p>
        </div>
        
        <!-- Main Card -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-users me-2"></i>Visitor Management</h4>
            </div>
            <div class="card-body">
                <!-- Visitor Code Entry Section -->
                <div class="section-title">
                    <i class="fas fa-search"></i>
                    <h5 class="mb-0">Find Visitor by Code</h5>
                </div>
                
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>Enter the visitor code from check-in system to fetch visitor details and proceed with front desk operations.</p>
                </div>
                
                <div class="mb-4">
                    <label for="frontDeskCodeInput" class="form-label required">Visitor Code</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="frontDeskCodeInput" placeholder="Enter VR-XXXXXX code" required>
                        <button class="btn btn-primary" type="button" id="frontDeskFetchBtn">
                            <i class="fas fa-search me-2"></i>Fetch Visitor Details
                        </button>
                    </div>
                    <small class="text-muted">Enter the visitor code from check-in system (e.g., VR-A1B2C3)</small>
                </div>
                
                <!-- Test Buttons -->
                <div class="alert alert-info mb-4">
                    <i class="fas fa-vial me-2"></i>
                    <strong>Testing Tools:</strong>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        <button class="btn btn-sm btn-outline-info" id="showAvailableCodesBtn">
                            <i class="fas fa-eye me-1"></i>Show Available Codes
                        </button>
                        <button class="btn btn-sm btn-outline-warning" id="checkStorageBtn">
                            <i class="fas fa-database me-1"></i>Check Storage
                        </button>
                        <button class="btn btn-sm btn-outline-danger" id="clearAllDataBtn">
                            <i class="fas fa-trash me-1"></i>Clear All Data
                        </button>
                        <button class="btn btn-sm btn-outline-success" id="openAttendanceSystemBtn">
                            <i class="fas fa-handshake me-1"></i>Open Attendance System
                        </button>
                        <button class="btn btn-sm btn-outline-primary" id="openCheckoutPageBtn">
                            <i class="fas fa-sign-out-alt me-1"></i>Open Checkout Page
                        </button>
                    </div>
                </div>
                
                <!-- Visitor Details Display -->
                <div id="frontDeskVisitorDetails" style="display: none;">
                    <div class="visitor-code-container">
                        <h5><i class="fas fa-id-card me-2"></i>Visitor Code</h5>
                        <div class="visitor-code-display" id="frontDeskVisitorCodeDisplay">VR-000000</div>
                        <div id="registrationTypeBadge" class="mt-2" style="position: relative; z-index: 2;">
                            <!-- Registration type badge will appear here -->
                        </div>
                    </div>
                    
                    <!-- Meeting History Section -->
                    <div id="meetingHistorySection" style="display: none;">
                        <div class="section-title">
                            <i class="fas fa-history"></i>
                            <h5 class="mb-0">Meeting History</h5>
                        </div>
                        <div id="meetingHistoryContainer">
                            <!-- Meeting history will be dynamically loaded here -->
                        </div>
                    </div>
                    
                    <!-- Visitor Information -->
                    <div class="visitor-info-grid mt-4">
                        <div class="info-item">
                            <div class="info-label">Full Name</div>
                            <div class="info-value" id="fdDetailName">-</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Contact Number</div>
                            <div class="info-value" id="fdDetailContact">-</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Registration Type</div>
                            <div class="info-value" id="fdDetailRegistrationType">-</div>
                        </div>
                    </div>
                    <div class="visitor-info-grid">
                        <div class="info-item">
                            <div class="info-label">Email Address</div>
                            <div class="info-value" id="fdDetailEmail">-</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Purpose of Visit</div>
                            <div class="info-value" id="fdDetailPurpose">-</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Status</div>
                            <div class="info-value" id="fdDetailStatus">-</div>
                        </div>
                    </div>
                    <div class="visitor-info-grid">
                        <div class="info-item">
                            <div class="info-label">Vehicle Type</div>
                            <div class="info-value" id="fdDetailVehicleType">-</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Vehicle Number</div>
                            <div class="info-value" id="fdDetailVehicleNumber">-</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Vehicle Color</div>
                            <div class="info-value" id="fdDetailVehicleColor">-</div>
                        </div>
                    </div>
                    
                    <!-- Visitor Photo -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="info-label mb-2">Visitor Photo</div>
                            <div class="text-center">
                                <img id="fdDetailPhoto" class="visitor-photo-large" src="" alt="Visitor Photo" style="display: none;">
                                <div id="fdNoPhotoMessage" class="text-muted">No photo uploaded</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label mb-2">Vehicle Photos</div>
                            <div id="fdDetailVehiclePhotos" class="vehicle-photo-grid">
                                <!-- Vehicle photos will appear here -->
                            </div>
                            <div id="fdNoVehiclePhotosMessage" class="text-muted">No vehicle photos uploaded</div>
                        </div>
                    </div>
                    
                    <!-- Front Desk Action Tabs -->
                    <div class="tab-container mt-5">
                        <ul class="nav nav-tabs" id="frontDeskTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="self-attendant-tab" data-bs-toggle="tab" data-bs-target="#self-attendant" type="button" role="tab">
                                    <i class="fas fa-user-check me-2"></i>Self Attendant
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="assigned-employee-tab" data-bs-toggle="tab" data-bs-target="#assigned-employee" type="button" role="tab">
                                    <i class="fas fa-user-tie me-2"></i>Assign & Schedule Meeting
                                </button>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="frontDeskTabContent">
                            <!-- Self Attendant Tab -->
                            <div class="tab-pane fade show active" id="self-attendant" role="tabpanel">
                                <div class="section-title">
                                    <i class="fas fa-user-check"></i>
                                    <h5 class="mb-0">Self Attendant Process</h5>
                                </div>
                                
                                <div class="info-box">
                                    <i class="fas fa-info-circle"></i>
                                    <p>Visitor will attend to themselves. Add remarks and generate gate pass for entry.</p>
                                </div>
                                
                                <!-- Notification Area -->
                                <div id="gatePassNotification" style="display: none;">
                                    <!-- Notification will appear here -->
                                </div>
                                
                                <form id="selfAttendantForm">
                                    <div class="mb-4">
                                        <label for="selfRemarks" class="form-label">Remarks / Notes</label>
                                        <textarea class="form-control form-textarea" id="selfRemarks" placeholder="Add any remarks or instructions for the visitor..." rows="4"></textarea>
                                        <small class="text-muted">Optional: Add any specific instructions or notes for the visitor.</small>
                                    </div>
                                    
                                    <div class="gate-pass-container" id="selfGatePassContainer" style="display: none;">
                                        <div class="gate-pass-title">
                                            <i class="fas fa-id-card me-2"></i>Out Pass Code : <span id="gatePassOutCode"></span> 
                                        </div>
                                        <div class="gate-pass-details">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Visitor Name:</strong> <span id="gatePassName"></span></p>
                                                    <p><strong>Visitor Code:</strong> <span id="gatePassCode"></span></p>
                                                    <p><strong>Purpose:</strong> <span id="gatePassPurpose"></span></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Valid Date:</strong> <span id="gatePassDate"></span></p>
                                                    <p><strong>Valid Time:</strong> <span id="gatePassTime"></span></p>
                                                    <p><strong>Gate:</strong> Main Entrance</p>
                                                </div>
                                            </div>
                                            <div id="meetingInfoSection" style="display: none; margin-top: 15px; padding-top: 15px; border-top: 1px dashed #dee2e6;">
                                                <p class="mb-1"><strong><i class="fas fa-handshake me-1"></i>Meeting Details:</strong></p>
                                                <p class="mb-1" id="gatePassMeetingTitle"></p>
                                                <p class="mb-0" id="gatePassMeetingEmployee"></p>
                                            </div>
                                        </div>
                                        <!-- <div class="gate-pass-qr">
                                            <i class="fas fa-qrcode fa-4x"></i>
                                        </div>
                                        <p class="text-muted">Show this pass at the gate for entry</p> -->
                                    </div>
                                    
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-primary" id="generateGatePassBtn">
                                            <i class="fas fa-id-card me-2"></i>Generate Gate Pass
                                        </button>
                                        <button type="button" class="btn btn-success" id="proceedToCheckoutBtn" style="display: none;">
                                            <i class="fas fa-sign-out-alt me-2"></i>Proceed to Checkout
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="printGatePassBtn" style="display: none;">
                                            <i class="fas fa-print me-2"></i>Print Gate Pass
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Assigned to Employee Tab (Now with Meeting Scheduling) -->
                            <div class="tab-pane fade" id="assigned-employee" role="tabpanel">
                                <div class="section-title">
                                    <i class="fas fa-user-tie"></i>
                                    <h5 class="mb-0">Assign to Employee & Schedule Meeting</h5>
                                </div>
                                
                                <div class="info-box">
                                    <i class="fas fa-info-circle"></i>
                                    <p>Assign this visitor to an employee and schedule a meeting. Generate meeting-specific gate passes.</p>
                                </div>
                                
                                <form id="assignEmployeeForm">
                                    <div class="row">
                                        <!-- <div class="col-md-6 mb-3">
                                            <label for="departmentSelect" class="form-label required">Select Department</label>
                                            <select class="form-select" id="departmentSelect" required>
                                                <option value="" selected disabled>Choose Department</option>
                                                <option value="hr">Human Resources</option>
                                                <option value="it">Information Technology</option>
                                                <option value="sales">Sales & Marketing</option>
                                                <option value="finance">Finance</option>
                                                <option value="operations">Operations</option>
                                                <option value="rd">Research & Development</option>
                                                <option value="support">Customer Support</option>
                                            </select>
                                            <div class="invalid-feedback">Please select a department.</div>
                                        </div> -->
                                        <div class="col-md-6 mb-3">
                                            <label for="departmentSelect" class="form-label required">Select Department</label>
                                            <select class="form-select" id="departmentSelect" required>
                                                <option value="" selected disabled>Choose Department</option>
                                            </select>
                                            <div class="invalid-feedback">Please select a department.</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="employeeSelect" class="form-label required">Select Employee</label>
                                            <select class="form-select" id="employeeSelect" required>
                                                <option value="" selected disabled>Choose Employee</option>
                                                <!-- Employees will be dynamically loaded based on department -->
                                            </select>
                                            <div class="invalid-feedback">Please select an employee.</div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="meetingTitle" class="form-label required">Meeting Title</label>
                                        <input type="text" class="form-control" id="meetingTitle" placeholder="Enter meeting title" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="meetingLocation" class="form-label required">Meeting Location</label>
                                        <input type="text" class="form-control" id="meetingLocation" placeholder="e.g., Conference Room A" required>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="meetingDuration" class="form-label required">Meeting Duration</label>
                                            <select class="form-select" id="meetingDuration" required>
                                                <option value="" selected disabled>Select Duration</option>
                                                <option value="30">30 minutes</option>
                                                <option value="60">1 hour</option>
                                                <option value="90">1.5 hours</option>
                                                <option value="120">2 hours</option>
                                                <option value="180">3 hours</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label required">Meeting Time</label>
                                            <div class="time-input-container">
                                                <input type="date" class="form-control" id="employeeMeetingDate" required>
                                                <input type="time" class="form-control" id="employeeMeetingTime" required>
                                            </div>
                                            <small class="text-muted">Set the date and time for the meeting</small>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="employeeMeetingNotes" class="form-label">Meeting Notes / Purpose</label>
                                        <textarea class="form-control form-textarea" id="employeeMeetingNotes" placeholder="Brief description of meeting purpose..." rows="3"></textarea>
                                    </div>
                                    
                                    <div class="gate-pass-container" id="meetingGatePassContainer" style="display: none;">
                                        <div class="gate-pass-title">
                                            <i class="fas fa-calendar-check me-2"></i>Meeting Gate Pass
                                        </div>
                                        <div class="gate-pass-details">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Meeting:</strong> <span id="meetingGatePassTitle"></span></p>
                                                    <p><strong>Visitor:</strong> <span id="meetingGatePassVisitor"></span></p>
                                                    <p><strong>Time:</strong> <span id="meetingGatePassTime"></span></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Location:</strong> <span id="meetingGatePassLocation"></span></p>
                                                    <p><strong>Duration:</strong> <span id="meetingGatePassDuration"></span></p>
                                                    <p><strong>Code:</strong> <span id="meetingGatePassCode"></span></p>
                                                </div>
                                            </div>
                                            <div class="mt-3 p-2 bg-light rounded">
                                                <p class="mb-1"><strong><i class="fas fa-user-tie me-1"></i>Meeting With:</strong></p>
                                                <p class="mb-0" id="meetingGatePassEmployee"></p>
                                            </div>
                                        </div>
                                        <div class="gate-pass-qr">
                                            <i class="fas fa-calendar-alt fa-4x"></i>
                                        </div>
                                        <p class="text-muted">Show this pass for meeting entry</p>
                                    </div>
                                    
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-primary" id="generateMeetingPassBtn">
                                            <i class="fas fa-id-card me-2"></i>Generate Meeting Pass
                                        </button>
                                        <button type="button" class="btn btn-success" id="assignEmployeeBtn">
                                            <i class="fas fa-user-check me-2"></i>Assign & Schedule
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" id="clearAssignmentBtn">
                                            <i class="fas fa-times me-2"></i>Clear
                                        </button>
                                    </div>
                                </form>
                                
                                <!-- Assignment Success Message -->
                                <div id="assignmentSuccess" class="alert alert-success mt-4" style="display: none;">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Visitor assigned and meeting scheduled successfully!</strong>
                                    <p class="mb-0 mt-2" id="assignmentDetails"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Success Message -->
        <div id="frontDeskSuccessMessage" class="alert alert-success" style="display: none;">
            <h5><i class="fas fa-check-circle me-2"></i>Operation Completed Successfully!</h5>
            <p class="mb-0" id="frontDeskSuccessDetails"></p>
            <div class="mt-3">
                <button class="btn btn-outline-success" id="newVisitorBtn">
                    <i class="fas fa-plus me-2"></i>Process New Visitor
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get CSRF token for AJAX requests
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            // AJAX helper function
            async function makeAjaxRequest(url, method, data) {
                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });
                    
                    return await response.json();
                } catch (error) {
                    console.error('AJAX request failed:', error);
                    return {
                        success: false,
                        message: 'Network error: ' + error.message
                    };
                }
            }
            // Get visitor data from localStorage (shared with check-in system)
            let allVisitors = JSON.parse(localStorage.getItem('visitorRegistrationData') || '[]');
            let currentVisitor = null;
            let currentTab = 'self-attendant';
            
            // Sample employee data by department
            // const employeesByDepartment = {
            //     'hr': [
            //         { id: 1, name: 'Sarah Johnson', position: 'HR Manager', employeeId: 'EMP001' },
            //         { id: 2, name: 'Michael Chen', position: 'Recruitment Specialist', employeeId: 'EMP002' },
            //         { id: 3, name: 'Emily Davis', position: 'Training Coordinator', employeeId: 'EMP003' }
            //     ],
            //     'it': [
            //         { id: 4, name: 'Robert Wilson', position: 'IT Director', employeeId: 'EMP004' },
            //         { id: 5, name: 'David Miller', position: 'Software Developer', employeeId: 'EMP005' },
            //         { id: 6, name: 'Lisa Thompson', position: 'System Administrator', employeeId: 'EMP006' }
            //     ],
            //     'sales': [
            //         { id: 7, name: 'James Anderson', position: 'Sales Manager', employeeId: 'EMP007' },
            //         { id: 8, name: 'Maria Garcia', position: 'Account Executive', employeeId: 'EMP008' },
            //         { id: 9, name: 'Thomas Lee', position: 'Marketing Specialist', employeeId: 'EMP009' }
            //     ],
            //     'finance': [
            //         { id: 10, name: 'Patricia Brown', position: 'Finance Director', employeeId: 'EMP010' },
            //         { id: 11, name: 'Christopher Taylor', position: 'Accountant', employeeId: 'EMP011' }
            //     ],
            //     'operations': [
            //         { id: 12, name: 'Jennifer White', position: 'Operations Manager', employeeId: 'EMP012' },
            //         { id: 13, name: 'Daniel Moore', position: 'Logistics Coordinator', employeeId: 'EMP013' }
            //     ],
            //     'rd': [
            //         { id: 14, name: 'Kevin Martin', position: 'Research Lead', employeeId: 'EMP014' },
            //         { id: 15, name: 'Amanda Clark', position: 'Product Developer', employeeId: 'EMP015' }
            //     ],
            //     'support': [
            //         { id: 16, name: 'Brian Lewis', position: 'Support Manager', employeeId: 'EMP016' },
            //         { id: 17, name: 'Nancy Hall', position: 'Customer Service Rep', employeeId: 'EMP017' }
            //     ]
            // };
            
            // Initialize if no data exists
            function initializeSampleData() {
                if (allVisitors.length === 0) {
                    console.log('No visitor data found. Creating sample data...');
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
                            status: "Checked-in",
                            registrationType: "Self Registration",
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
                            status: "Checked-in",
                            registrationType: "Walk-in",
                            frontDeskActions: []
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
                            status: "Checked-in",
                            registrationType: "Self Registration",
                            frontDeskActions: []
                        }
                    ];
                    localStorage.setItem('visitorRegistrationData', JSON.stringify(allVisitors));
                    console.log('Sample visitor data created with 3 visitors');
                }
            }
            
            // Call initialization
            initializeSampleData();
            
            // Event Listeners
            document.getElementById('frontDeskFetchBtn').addEventListener('click', fetchVisitorDetails);
            
            // Enter key support for visitor code input
            document.getElementById('frontDeskCodeInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    fetchVisitorDetails();
                }
            });
            
            // Test buttons
            document.getElementById('showAvailableCodesBtn').addEventListener('click', function() {
                const codes = allVisitors.map(v => v.code).join(', ');
                alert(`Available Visitor Codes:\n\n${codes}\n\nTotal: ${allVisitors.length} visitor(s)`);
            });
            
            document.getElementById('checkStorageBtn').addEventListener('click', function() {
                console.log('=== FRONT DESK STORAGE CHECK ===');
                console.log('Total visitors:', allVisitors.length);
                console.log('Visitor codes:', allVisitors.map(v => v.code));
                alert(`Front Desk Storage: ${allVisitors.length} visitor(s)\n\nCodes: ${allVisitors.map(v => v.code).join(', ')}\n\nCheck browser console for full details.`);
            });
            
            document.getElementById('clearAllDataBtn').addEventListener('click', function() {
                if (confirm('Are you sure you want to clear ALL visitor data? This cannot be undone.')) {
                    localStorage.removeItem('visitorRegistrationData');
                    allVisitors = [];
                    alert('All visitor data has been cleared from storage.');
                    console.log('Front desk storage cleared');
                    location.reload();
                }
            });
            
            document.getElementById('openAttendanceSystemBtn').addEventListener('click', function() {
                // Open Employee Attendance System in new tab
                const attendanceURL = '/institute/admin/visitor/attendant';
                window.open(attendanceURL, '_blank');
            });
            
            document.getElementById('openCheckoutPageBtn').addEventListener('click', function() {
                // Open Checkout Page in new tab
                const checkoutURL = '/institute/admin/visitor/check-out';
                window.open(checkoutURL, '_blank');
            });
            
            // Self Attendant Tab Actions
            document.getElementById('generateGatePassBtn').addEventListener('click', generateGatePassForSelfAttendent);
            document.getElementById('printGatePassBtn').addEventListener('click', function() {
                alert('Printing gate pass... (In a real system, this would open print dialog)');
                window.print();
            });
            
            // Proceed to Checkout button
            document.getElementById('proceedToCheckoutBtn').addEventListener('click', function() {
                if (currentVisitor) {
                    // Store current visitor code in sessionStorage to pass to checkout page
                    sessionStorage.setItem('checkoutVisitorCode', currentVisitor.code);
                    
                    // Open checkout page in new tab
                    const checkoutURL = '/institute/admin/visitor/check-out';
                    window.open(checkoutURL, '_blank');
                    
                    // Optionally reset the form
                    resetFrontDeskForm();
                } else {
                    alert('No visitor selected. Please fetch visitor details first.');
                }
            });
            
            // Assigned to Employee Tab Actions (Now with Meeting Scheduling)
            document.getElementById('departmentSelect').addEventListener('change', loadEmployeesByDepartment);
            document.getElementById('generateMeetingPassBtn').addEventListener('click', generateMeetingPass);
            document.getElementById('assignEmployeeBtn').addEventListener('click', assignToEmployeeAndScheduleMeeting);
            document.getElementById('clearAssignmentBtn').addEventListener('click', clearAssignment);
            
            // New visitor button
            document.getElementById('newVisitorBtn').addEventListener('click', function() {
                resetFrontDeskForm();
            });
            
            // Tab change handler
            document.querySelectorAll('#frontDeskTabs button').forEach(tab => {
                tab.addEventListener('click', function() {
                    currentTab = this.getAttribute('data-bs-target').replace('#', '');
                });
            });
            
            // Functions
            async function fetchVisitorDetails() {
                const codeInput = document.getElementById('frontDeskCodeInput').value.trim().toUpperCase();
                if (!codeInput) {
                    alert('Please enter a visitor code');
                    return;
                }
                
                console.log('Front Desk: Looking for visitor code:', codeInput);
                const fetchBtn = document.getElementById('frontDeskFetchBtn');
                const originalText = fetchBtn.innerHTML;
                fetchBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
                fetchBtn.disabled = true;
                // Reload data from localStorage
                const storedData = await makeAjaxRequest('/visitors/fetch-by-code', 'POST', {
                    visitor_code: codeInput
                });
                console.log(storedData);
                // const storedData = localStorage.getItem('visitorRegistrationData');
                currentVisitor = storedData.visitor;
                if (currentVisitor) {
                    try {
                        console.log('Reloaded data. Total visitors:', allVisitors.length);
                    } catch (e) {
                        console.error('Error parsing data:', e);
                        alert('Error loading visitor data. Please try again.');
                        return;
                    }
                }
                
                // Find visitor with matching code
                // const visitor = allVisitors.find(v => {
                //     if (v && v.code) {
                //         return v.code.toUpperCase() === codeInput;
                //     }
                //     return false;
                // });
                
                // if (!visitor) {
                //     const availableCodes = allVisitors.map(v => v.code).join(', ');
                //     alert(`❌ Visitor with code "${codeInput}" was not found.\n\nAvailable codes: ${availableCodes || 'None'}\n\nTry these sample codes: VR-A1B2C3, VR-X9Y8Z7, VR-M5N6O7`);
                //     return;
                // }
                
                
                console.log(currentVisitor)
                // Display visitor code
                document.getElementById('frontDeskVisitorCodeDisplay').textContent = currentVisitor.code;
                
                // Display registration type with badge
                const registrationType = currentVisitor.registrationType || 'Unknown';
                const badgeClass = registrationType === 'Walk-in' ? 'badge-walkin' : 'badge-self';
                const badgeText = registrationType === 'Walk-in' ? 'Walk-in Visitor' : 'Self Registration';
                
                document.getElementById('registrationTypeBadge').innerHTML = `
                    <span class="check-in-badge ${badgeClass}">
                        <i class="fas ${registrationType === 'Walk-in' ? 'fa-walking' : 'fa-mobile-alt'} me-1"></i>
                        ${badgeText}
                    </span>
                `;
                
                // Display visitor details
                document.getElementById('fdDetailName').textContent = currentVisitor.name || 'Not provided';
                document.getElementById('fdDetailContact').textContent = currentVisitor.contact || 'Not provided';
                document.getElementById('fdDetailEmail').textContent = currentVisitor.email || 'Not provided';
                document.getElementById('fdDetailPurpose').textContent = currentVisitor.purpose || 'Not provided';
                document.getElementById('fdDetailVehicleType').textContent = currentVisitor.vehicle_type || 'Not provided';
                document.getElementById('fdDetailVehicleNumber').textContent = currentVisitor.vehicle_number || 'Not provided';
                document.getElementById('fdDetailVehicleColor').textContent = currentVisitor.vehicle_color || 'Not provided';
                document.getElementById('fdDetailRegistrationType').textContent = registrationType;
                
                // Display status with color
                let statusText = currentVisitor.status || 'Unknown';
                let statusClass = '';
                
                if (statusText === 'Checked-in') {
                    statusClass = 'text-success';
                    statusText += ' ✓';
                } else if (statusText === 'Registered') {
                    statusClass = 'text-primary';
                } else {
                    statusClass = 'text-warning';
                }
                
                document.getElementById('fdDetailStatus').textContent = statusText;
                document.getElementById('fdDetailStatus').className = 'info-value ' + statusClass;
                
                // Display currentVisitor photo if available
                if (currentVisitor.visitor_photo) {
                    document.getElementById('fdDetailPhoto').src = currentVisitor.visitor_photo;
                    document.getElementById('fdDetailPhoto').style.display = 'block';
                    document.getElementById('fdNoPhotoMessage').style.display = 'none';
                } else {
                    document.getElementById('fdDetailPhoto').style.display = 'none';
                    document.getElementById('fdNoPhotoMessage').style.display = 'block';
                }
                
                // Display vehicle photos if available
                const vehiclePhotosContainer = document.getElementById('fdDetailVehiclePhotos');
                const noVehiclePhotosMessage = document.getElementById('fdNoVehiclePhotosMessage');
                
                if (currentVisitor.vehiclePhotos && Array.isArray(currentVisitor.vehiclePhotos) && currentVisitor.vehiclePhotos.length > 0) {
                    vehiclePhotosContainer.innerHTML = '';
                    currentVisitor.vehiclePhotos.forEach((photo, index) => {
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
                
                // Load meeting history
                loadMeetingHistory();
                
                // Show visitor details section
                document.getElementById('frontDeskVisitorDetails').style.display = 'block';
                
                // Check for pending gate pass notifications
                setTimeout(() => {
                    checkForPendingGatePasses();
                }, 500);
                
                // Scroll to details
                document.getElementById('frontDeskVisitorDetails').scrollIntoView({ behavior: 'smooth' });
                
                console.log('✅ Front desk visitor details loaded:', currentVisitor.name);
            }
            
            function loadMeetingHistory() {
                if (!currentVisitor || !currentVisitor.frontDeskActions) {
                    document.getElementById('meetingHistorySection').style.display = 'none';
                    return;
                }
                
                const meetings = currentVisitor.frontDeskActions.filter(action => 
                    action.type === 'assigned_to_employee' || 
                    action.type === 'employee_attendance_confirmed'
                );
                
                if (meetings.length === 0) {
                    document.getElementById('meetingHistorySection').style.display = 'none';
                    return;
                }
                
                const historyContainer = document.getElementById('meetingHistoryContainer');
                historyContainer.innerHTML = '';
                
                // Group meetings by meeting ID or title
                const groupedMeetings = {};
                
                meetings.forEach(action => {
                    const meetingKey = action.meetingId || action.meetingTitle;
                    if (!groupedMeetings[meetingKey]) {
                        groupedMeetings[meetingKey] = {
                            title: action.meetingTitle,
                            employee: action.employee,
                            date: action.meetingDate,
                            time: action.meetingTime,
                            location: action.location,
                            duration: action.duration,
                            notes: action.notes,
                            status: action.meetingStatus || 'pending',
                            attendanceAction: action.type === 'employee_attendance_confirmed' ? action : null
                        };
                    } else if (action.type === 'employee_attendance_confirmed') {
                        groupedMeetings[meetingKey].attendanceAction = action;
                        groupedMeetings[meetingKey].status = action.attendance === 'completed' ? 'completed' : 'cancelled';
                    }
                });
                
                // Display each meeting
                Object.values(groupedMeetings).forEach(meeting => {
                    const statusBadge = meeting.status === 'completed' ? 'badge-completed' : 
                                      meeting.status === 'cancelled' ? 'badge-cancelled' : 'badge-pending';
                    const statusText = meeting.status === 'completed' ? 'Completed' : 
                                     meeting.status === 'cancelled' ? 'Cancelled' : 'Pending';
                    
                    const meetingCard = document.createElement('div');
                    meetingCard.className = 'meeting-history-card';
                    meetingCard.innerHTML = `
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">
                                    <i class="fas fa-calendar-alt me-2"></i>${meeting.title}
                                    <span class="meeting-status-badge ${statusBadge}">${statusText}</span>
                                </h6>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-user-tie me-1"></i>
                                    ${meeting.employee?.name || 'Unknown Employee'} - ${meeting.employee?.position || ''}
                                </p>
                                <p class="mb-1">
                                    <i class="fas fa-clock me-1"></i>
                                    ${meeting.date} at ${meeting.time} • ${meeting.duration || 'N/A'} mins
                                </p>
                                <p class="mb-0">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    ${meeting.location || 'Not specified'}
                                </p>
                                ${meeting.attendanceAction ? `
                                    <div class="mt-2 p-2 bg-light rounded">
                                        <small>
                                            <i class="fas fa-user-check me-1"></i>
                                            <strong>Attendance:</strong> ${meeting.attendanceAction.attendance === 'completed' ? 'Meeting Completed' : 'Meeting Cancelled'}
                                            <br>
                                            <i class="fas fa-clock me-1"></i>
                                            <strong>Confirmed:</strong> ${new Date(meeting.attendanceAction.timestamp).toLocaleString()}
                                            ${meeting.attendanceAction.notes ? `<br><i class="fas fa-sticky-note me-1"></i><strong>Notes:</strong> ${meeting.attendanceAction.notes}` : ''}
                                        </small>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                    
                    historyContainer.appendChild(meetingCard);
                });
                
                document.getElementById('meetingHistorySection').style.display = 'block';
            }
            
            function checkForPendingGatePasses() {
                if (!currentVisitor || !currentVisitor.frontDeskActions) {
                    return false;
                }
                
                // Check if there's a pending gate pass request from employee attendance
                const pendingAction = currentVisitor.frontDeskActions.find(action => 
                    action.type === 'employee_attendance_confirmed' && 
                    action.status === 'pending_gate_pass' &&
                    action.requiresGatePass === true
                );
                
                if (pendingAction) {
                    // Update notification badge
                    document.getElementById('notificationBadge').style.display = 'flex';
                    
                    // Automatically switch to Self Attendant tab
                    document.getElementById('self-attendant-tab').click();
                    
                    // Show notification
                    const notificationDiv = document.createElement('div');
                    notificationDiv.className = 'alert alert-warning notification-alert';
                    notificationDiv.innerHTML = `
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-bell fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="alert-heading mb-2">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Gate Pass Required!
                                </h6>
                                <p class="mb-2">
                                    Employee <strong>${pendingAction.employee.name}</strong> has confirmed meeting completion with <strong>${currentVisitor.name}</strong>.
                                    Please generate a gate pass for the visitor to exit.
                                </p>
                                <div class="bg-light p-2 rounded mb-2">
                                    <small>
                                        <strong>Meeting Details:</strong><br>
                                        📅 <strong>Meeting:</strong> ${pendingAction.meetingTitle}<br>
                                        🕒 <strong>Time:</strong> ${new Date(pendingAction.timestamp).toLocaleTimeString()}<br>
                                        ${pendingAction.notes ? `📝 <strong>Notes:</strong> ${pendingAction.notes}<br>` : ''}
                                        👤 <strong>Confirmed by:</strong> ${pendingAction.employee.name} (${pendingAction.employee.position})
                                    </small>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-primary" id="autoGenerateGatePassBtn">
                                        <i class="fas fa-id-card me-1"></i>Generate Gate Pass Now
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="dismissNotificationBtn">
                                        <i class="fas fa-times me-1"></i>Dismiss
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Insert notification
                    const notificationContainer = document.getElementById('gatePassNotification');
                    notificationContainer.innerHTML = '';
                    notificationContainer.appendChild(notificationDiv);
                    notificationContainer.style.display = 'block';
                    
                    // Add event listeners
                    setTimeout(() => {
                        const autoBtn = document.getElementById('autoGenerateGatePassBtn');
                        const dismissBtn = document.getElementById('dismissNotificationBtn');
                        
                        if (autoBtn) {
                            autoBtn.addEventListener('click', function() {
                                // Auto-fill remarks with meeting info
                                document.getElementById('selfRemarks').value = 
                                    `Meeting completed with ${pendingAction.employee.name} (${pendingAction.employee.position}).\n` +
                                    `Meeting: ${pendingAction.meetingTitle}\n` +
                                    `Time: ${new Date(pendingAction.timestamp).toLocaleString()}\n` +
                                    `${pendingAction.notes ? `Notes: ${pendingAction.notes}` : ''}`;
                                
                                // Set meeting info for gate pass
                                window.pendingMeetingInfo = {
                                    title: pendingAction.meetingTitle,
                                    employee: pendingAction.employee.name,
                                    position: pendingAction.employee.position
                                };
                                
                                // Generate gate pass
                                generateGatePass();
                                
                                // Update the action status
                                pendingAction.status = 'gate_pass_generated';
                                pendingAction.gatePassGeneratedAt = new Date().toISOString();
                                
                                // Update localStorage
                                updateVisitorInStorage();
                                
                                // Remove notification
                                notificationContainer.style.display = 'none';
                                document.getElementById('notificationBadge').style.display = 'none';
                                
                                // Refresh meeting history
                                loadMeetingHistory();
                            });
                        }
                        
                        if (dismissBtn) {
                            dismissBtn.addEventListener('click', function() {
                                notificationContainer.style.display = 'none';
                                document.getElementById('notificationBadge').style.display = 'none';
                            });
                        }
                    }, 100);
                    
                    return true;
                }
                
                return false;
            }
            
            function generateGatePass() {
                if (!currentVisitor) {
                    alert('No visitor selected');
                    return;
                }
                
                const now = new Date();
                const dateStr = now.toLocaleDateString();
                const timeStr = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                
                // Update gate pass display
                document.getElementById('gatePassName').textContent = currentVisitor.name;
                document.getElementById('gatePassCode').textContent = currentVisitor.code;
                document.getElementById('gatePassPurpose').textContent = currentVisitor.purpose;
                document.getElementById('gatePassDate').textContent = dateStr;
                document.getElementById('gatePassTime').textContent = timeStr;
                
                // Check if there's meeting info to display
                if (window.pendingMeetingInfo) {
                    document.getElementById('gatePassMeetingTitle').textContent = window.pendingMeetingInfo.title;
                    document.getElementById('gatePassMeetingEmployee').textContent = 
                        `With: ${window.pendingMeetingInfo.employee} (${window.pendingMeetingInfo.position})`;
                    document.getElementById('meetingInfoSection').style.display = 'block';
                } else {
                    document.getElementById('meetingInfoSection').style.display = 'none';
                }
                
                // Show gate pass container
                document.getElementById('selfGatePassContainer').style.display = 'block';
                document.getElementById('printGatePassBtn').style.display = 'inline-block';
                document.getElementById('proceedToCheckoutBtn').style.display = 'inline-block';
                
                // Add front desk action record
                if (!currentVisitor.frontDeskActions) {
                    currentVisitor.frontDeskActions = [];
                }
                
                currentVisitor.frontDeskActions.push({
                    type: 'gate_pass_generated',
                    timestamp: new Date().toISOString(),
                    tab: 'self-attendant',
                    remarks: document.getElementById('selfRemarks').value,
                    meetingInfo: window.pendingMeetingInfo || null
                });
                
                // Update localStorage
                updateVisitorInStorage();
                
                // Clear pending meeting info
                window.pendingMeetingInfo = null;
                
                // Show success message
                showSuccessMessage(`Gate pass generated for ${currentVisitor.name} (${currentVisitor.code})`);
            }

            async function generateGatePassForSelfAttendent() {
                if (!currentVisitor) {
                    alert('No visitor selected');
                    return;
                }
                console.log(currentVisitor);
                const now = new Date();
                const dateStr = now.toLocaleDateString();
                const timeStr = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                
                // Update gate pass display
                document.getElementById('gatePassName').textContent = currentVisitor.name;
                document.getElementById('gatePassCode').textContent = currentVisitor.code;
                document.getElementById('gatePassPurpose').textContent = currentVisitor.purpose;
                document.getElementById('gatePassDate').textContent = dateStr;
                document.getElementById('gatePassTime').textContent = timeStr;
                
                // Check if there's meeting info to display
                // if (window.pendingMeetingInfo) {
                //     document.getElementById('gatePassMeetingTitle').textContent = window.pendingMeetingInfo.title;
                //     document.getElementById('gatePassMeetingEmployee').textContent = 
                //         `With: ${window.pendingMeetingInfo.employee} (${window.pendingMeetingInfo.position})`;
                //     document.getElementById('meetingInfoSection').style.display = 'block';
                // } else {
                //     document.getElementById('meetingInfoSection').style.display = 'none';
                // }
                
                // Show gate pass container
                document.getElementById('selfGatePassContainer').style.display = 'block';
                document.getElementById('printGatePassBtn').style.display = 'inline-block';
                document.getElementById('proceedToCheckoutBtn').style.display = 'inline-block';
                
                // Add front desk action record
                if (!currentVisitor.frontDeskActions) {
                    currentVisitor.frontDeskActions = [];
                }
                
                currentVisitor.frontDeskActions.push({
                    type: 'gate_pass_generated',
                    timestamp: new Date().toISOString(),
                    tab: 'self-attendant',
                    remarks: document.getElementById('selfRemarks').value,
                    // meetingInfo: window.pendingMeetingInfo || null
                });
                const self_attendent = await makeAjaxRequest('/visitors/visitor-attendent-log', 'POST', {
                    visitor_code: currentVisitor.code,
                    meeting_purpose: currentVisitor.purpose,
                    self_attendent_remarks: document.getElementById('selfRemarks').value,
                    meeting_attendent_type: 'self',
                    timestamp: new Date().toISOString()
                    
                });
                // Update localStorage
                updateVisitorInStorage();
                
                // Clear pending meeting info
                window.pendingMeetingInfo = null;
                document.getElementById('gatePassOutCode').textContent = self_attendent.out_pass_id;
                // Show success message
                showSuccessMessage(`Gate pass generated for ${currentVisitor.name} (${currentVisitor.code})`);
            }
            
            // function loadEmployeesByDepartment() {
            //     const department = document.getElementById('departmentSelect').value;
            //     const employeeSelect = document.getElementById('employeeSelect');
                
            //     // Clear existing options
            //     employeeSelect.innerHTML = '<option value="" selected disabled>Choose Employee</option>';
                
            //     if (department && employeesByDepartment[department]) {
            //         employeesByDepartment[department].forEach(employee => {
            //             const option = document.createElement('option');
            //             option.value = employee.employeeId;
            //             option.textContent = `${employee.name} - ${employee.position}`;
            //             employeeSelect.appendChild(option);
            //         });
            //     }
            // }
            async function loadEmployeesByDepartment() {
                const deptId = document.getElementById('departmentSelect').value;
                const employeeSelect = document.getElementById('employeeSelect');

                // Reset dropdown
                employeeSelect.innerHTML = '<option value="" selected disabled>Choose Employee</option>';

                if (!deptId) return;

                try {
                    const response = await fetch(`/ajax/employees-by-department?department_id=${deptId}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    const data = await response.json();

                    if (data.employees && data.employees.length > 0) {
                        data.employees.forEach(employee => {
                            const option = document.createElement('option');
                            option.value = employee.employee_id; // backend employee id
                            option.textContent = `${employee.name}`;
                            employeeSelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.disabled = true;
                        option.textContent = 'No employees found';
                        employeeSelect.appendChild(option);
                    }

                } catch (error) {
                    console.error('Error loading employees:', error);

                    const option = document.createElement('option');
                    option.disabled = true;
                    option.textContent = 'Failed to load employees';
                    employeeSelect.appendChild(option);
                }
            }
            function generateMeetingPass() {
                if (!currentVisitor) {
                    alert('No visitor selected');
                    return;
                }
                
                const department = document.getElementById('departmentSelect').value;
                const employeeId = document.getElementById('employeeSelect').value;
                const meetingTitle = document.getElementById('meetingTitle').value;
                const meetingLocation = document.getElementById('meetingLocation').value;
                const meetingDate = document.getElementById('employeeMeetingDate').value;
                const meetingTime = document.getElementById('employeeMeetingTime').value;
                const meetingDuration = document.getElementById('meetingDuration').value;
                
                if (!department || !employeeId || !meetingTitle || !meetingLocation || !meetingDate || !meetingTime || !meetingDuration) {
                    alert('Please fill all required fields for the meeting');
                    return;
                }
                
                // Get selected employee
                const selectedEmployee = employeesByDepartment[department].find(e => e.employeeId === employeeId);
                
                // Update meeting gate pass display
                document.getElementById('meetingGatePassTitle').textContent = meetingTitle;
                document.getElementById('meetingGatePassVisitor').textContent = currentVisitor.name;
                document.getElementById('meetingGatePassTime').textContent = `${meetingDate} at ${meetingTime}`;
                document.getElementById('meetingGatePassLocation').textContent = meetingLocation;
                document.getElementById('meetingGatePassDuration').textContent = meetingDuration + ' minutes';
                document.getElementById('meetingGatePassCode').textContent = currentVisitor.code;
                document.getElementById('meetingGatePassEmployee').textContent = 
                    `${selectedEmployee.name} - ${selectedEmployee.position}`;
                
                // Show gate pass container
                document.getElementById('meetingGatePassContainer').style.display = 'block';
                
                // Add front desk action record
                if (!currentVisitor.frontDeskActions) {
                    currentVisitor.frontDeskActions = [];
                }
                
                currentVisitor.frontDeskActions.push({
                    type: 'meeting_pass_generated',
                    timestamp: new Date().toISOString(),
                    meetingTitle: meetingTitle,
                    location: meetingLocation,
                    date: meetingDate,
                    time: meetingTime,
                    duration: meetingDuration,
                    employee: selectedEmployee
                });
                
                // Update localStorage
                updateVisitorInStorage();
                
                showSuccessMessage(`Meeting pass generated for "${meetingTitle}"`);
            }
            
            async function assignToEmployeeAndScheduleMeeting() {
                if (!currentVisitor) {
                    alert('No visitor selected');
                    return;
                }
                
                const department = document.getElementById('departmentSelect').value;
                const departmentName = document.getElementById('employeeSelect').text;
                const employeeId = document.getElementById('employeeSelect').value;
                const employeeName = document.getElementById('employeeSelect').text;
                const meetingTitle = document.getElementById('meetingTitle').value;
                const meetingLocation = document.getElementById('meetingLocation').value;
                const meetingDate = document.getElementById('employeeMeetingDate').value;
                const meetingTime = document.getElementById('employeeMeetingTime').value;
                const meetingDuration = document.getElementById('meetingDuration').value;
                const meetingNotes = document.getElementById('employeeMeetingNotes').value;
                
                if (!department || !employeeId || !meetingTitle || !meetingLocation || !meetingDate || !meetingTime || !meetingDuration) {
                    alert('Please fill all required fields');
                    return;
                }
                
                // Get selected employee
                // const selectedEmployee = employeesByDepartment[department].find(e => e.employeeId === employeeId);
                
                // Generate meeting ID
                const meetingId = 'MTG-' + Date.now().toString().substr(-6);
                
                // Add front desk action record
                if (!currentVisitor.frontDeskActions) {
                    currentVisitor.frontDeskActions = [];
                }
                
                currentVisitor.frontDeskActions.push({
                    type: 'assigned_to_employee',
                    timestamp: new Date().toISOString(),
                    department: department,
                    department_name: departmentName,
                    employee: employeeId,
                    employee_name: employeeName,
                    meetingDate: meetingDate,
                    meetingTime: meetingTime,
                    notes: meetingNotes,
                    meetingTitle: meetingTitle,
                    location: meetingLocation,
                    duration: meetingDuration,
                    status: 'pending',
                    meetingId: meetingId
                });
                const check_out = await makeAjaxRequest('/visitors/visitor-attendent-log', 'POST', {
                    visitor_code: currentVisitor.code,
                    meeting_purpose:currentVisitor.purpose,
                    meeting_attendent_type: 'transfer',
                    type: 'assigned_to_employee',
                    timestamp: new Date().toISOString(),
                    department: department,
                    department_name: departmentName,
                    employee: employeeId,
                    employee_name: employeeName,
                    meetingDate: meetingDate,
                    meetingTime: meetingTime,
                    notes: meetingNotes,
                    meetingTitle: meetingTitle,
                    location: meetingLocation,
                    duration: meetingDuration,
                    status: 'pending',
                    meetingId: meetingId
                });
                // Update localStorage
                updateVisitorInStorage();
                
                // Show success message
                document.getElementById('assignmentSuccess').style.display = 'block';
                document.getElementById('assignmentDetails').innerHTML = `
                    <strong>${currentVisitor.name}</strong> has been assigned to <strong>${selectedEmployee.name}</strong><br>
                    <strong>Meeting:</strong> ${meetingTitle}<br>
                    <strong>Department:</strong> ${document.getElementById('departmentSelect').options[document.getElementById('departmentSelect').selectedIndex].text}<br>
                    <strong>Meeting Time:</strong> ${meetingDate} at ${meetingTime} (${meetingDuration} mins)<br>
                    <strong>Location:</strong> ${meetingLocation}<br>
                    <strong>Notes:</strong> ${meetingNotes || 'None'}<br><br>
                    <small class="text-muted">Employee can confirm meeting completion in the Attendance System</small>
                `;
                
                // Refresh meeting history
                loadMeetingHistory();
                
                // Clear form
                clearAssignment();
                
                console.log(`✅ Visitor assigned to employee and meeting scheduled: ${meetingTitle} with ${selectedEmployee.name}`);
            }
            
            function clearAssignment() {
                document.getElementById('departmentSelect').value = '';
                document.getElementById('employeeSelect').innerHTML = '<option value="" selected disabled>Choose Employee</option>';
                document.getElementById('employeeSelect').value = '';
                document.getElementById('meetingTitle').value = '';
                document.getElementById('meetingLocation').value = '';
                document.getElementById('employeeMeetingNotes').value = '';
                document.getElementById('employeeMeetingDate').value = '';
                document.getElementById('employeeMeetingTime').value = '';
                document.getElementById('meetingDuration').value = '';
                document.getElementById('meetingGatePassContainer').style.display = 'none';
            }
            
            function updateVisitorInStorage() {
                const index = allVisitors.findIndex(v => v.code === currentVisitor.code);
                if (index !== -1) {
                    allVisitors[index] = currentVisitor;
                    localStorage.setItem('visitorRegistrationData', JSON.stringify(allVisitors));
                    console.log('✅ Visitor updated in storage:', currentVisitor.name);
                }
            }
            
            function showSuccessMessage(message) {
                // Create a temporary alert
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                alertDiv.innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                // Insert after the card
                const card = document.querySelector('.card');
                card.parentNode.insertBefore(alertDiv, card.nextSibling);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            }
            
            function resetFrontDeskForm() {
                document.getElementById('frontDeskCodeInput').value = '';
                document.getElementById('frontDeskVisitorDetails').style.display = 'none';
                document.getElementById('frontDeskSuccessMessage').style.display = 'none';
                document.getElementById('selfGatePassContainer').style.display = 'none';
                document.getElementById('meetingGatePassContainer').style.display = 'none';
                document.getElementById('assignmentSuccess').style.display = 'none';
                document.getElementById('gatePassNotification').style.display = 'none';
                document.getElementById('notificationBadge').style.display = 'none';
                currentVisitor = null;
                
                // Clear forms
                document.getElementById('selfRemarks').value = '';
                clearAssignment();
                
                // Clear window variable
                window.pendingMeetingInfo = null;
                
                // Focus on code input
                document.getElementById('frontDeskCodeInput').focus();
            }
            
            // Initialize the form
            console.log('Front Desk Management System initialized');
            console.log('Shared storage with visitor check-in system');
            console.log('Available visitor codes:', allVisitors.map(v => v.code));
            
            // Set default date to today and time to next hour
            const now = new Date();
            const nextHour = new Date(now.getTime() + 60 * 60 * 1000);
            
            const today = now.toISOString().split('T')[0];
            const nextHourTime = nextHour.toTimeString().substring(0, 5);
            
            // Set default values for date and time inputs
            document.getElementById('employeeMeetingDate').value = today;
            document.getElementById('employeeMeetingTime').value = nextHourTime;
        });

        document.addEventListener('DOMContentLoaded', async function () {
            // try {
                const response = await fetch('/get-departments');
                const result = await response.json();
        
                if (result.status) {
                    const departmentSelect = document.getElementById('departmentSelect');
        
                    result.data.forEach(dept => {
                        const option = document.createElement('option');
                        option.value = dept.department_id;          // send ID
                        option.textContent = dept.department; // show name
                        departmentSelect.appendChild(option);
                    });
                }
            // } catch (error) {
            //     console.error('Failed to load departments', error);
            // }
        });
    </script>
@endsection