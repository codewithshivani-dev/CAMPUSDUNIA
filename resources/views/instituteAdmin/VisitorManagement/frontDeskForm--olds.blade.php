@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Front Desk Management System</title>
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
            --info-color: #00b4d8;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gradient-primary: linear-gradient(135deg, #4361ee, #3a0ca3);
            --gradient-success: linear-gradient(135deg, #4bb543, #2a9d40);
            --gradient-info: linear-gradient(135deg, #00b4d8, #0077b6);
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --radius: 12px;
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            min-height: 100vh;
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
        
        .nav-tabs .nav-link.disabled {
            color: #adb5bd;
            cursor: not-allowed;
            background-color: #f8f9fa;
        }
        
        .nav-tabs .nav-link.disabled:hover {
            color: #adb5bd;
            background-color: #f8f9fa;
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
        
        /* Admission Form Styles */
        .enquiry-id-container {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border-radius: var(--radius);
            padding: 20px;
            text-align: center;
            margin-bottom: 25px;
        }
        
        .enquiry-id-display {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 2px;
            margin: 15px 0;
            background: rgba(255, 255, 255, 0.2);
            padding: 12px 25px;
            border-radius: 8px;
            display: inline-block;
        }
        
        .lead-id-container {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-radius: var(--radius);
            padding: 20px;
            text-align: center;
            margin-bottom: 25px;
        }
        
        .lead-id-display {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 2px;
            margin: 15px 0;
            background: rgba(255, 255, 255, 0.2);
            padding: 12px 25px;
            border-radius: 8px;
            display: inline-block;
        }
        
        .payment-options {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin: 25px 0;
            flex-wrap: wrap;
        }
        
        .payment-option-card {
            width: 200px;
            padding: 25px;
            border-radius: var(--radius);
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 3px solid transparent;
            background: white;
            box-shadow: var(--shadow);
        }
        
        .payment-option-card:hover {
            transform: translateY(-5px);
        }
        
        .payment-option-card.selected {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        }
        
        .payment-option-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }
        
        .online-payment {
            color: #4361ee;
        }
        
        .offline-payment {
            color: #ff9e00;
        }
        
        .qr-code-container {
            background: white;
            border: 2px dashed #4361ee;
            border-radius: var(--radius);
            padding: 30px;
            margin-top: 25px;
            text-align: center;
        }
        
        .payment-qr {
            width: 200px;
            height: 200px;
            background: #e9ecef;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            margin: 0 auto 20px;
        }
        
        .process-status {
            background: #f8f9fa;
            border-radius: var(--radius);
            padding: 20px;
            margin-top: 25px;
            text-align: center;
            border-left: 5px solid var(--info-color);
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin: 10px 0;
        }
        
        .status-new {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .status-inprocess {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-followup {
            background-color: #cce5ff;
            color: #004085;
        }
        
        .form-section {
            background: #f8f9fa;
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid var(--info-color);
        }
        
        .form-section-title {
            color: var(--info-color);
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .form-section-title i {
            margin-right: 10px;
            font-size: 20px;
        }
        
        .followup-section {
            background: linear-gradient(135deg, #e3f2fd, #f0f4ff);
            border-radius: var(--radius);
            padding: 20px;
            margin-top: 25px;
            border-left: 5px solid var(--warning-color);
        }
        
        /* Admission Purpose Badge */
        .admission-purpose-badge {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-left: 10px;
            display: inline-block;
        }
        
        /* Employee Assignment Section */
        .employee-assignment-section {
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            border-radius: var(--radius);
            padding: 20px;
            margin-top: 25px;
            border-left: 5px solid var(--success-color);
        }
        
        .employee-select-card {
            background: white;
            border-radius: var(--radius);
            padding: 15px;
            margin-bottom: 15px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        
        .employee-select-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }
        
        .employee-select-card.selected {
            border-color: var(--primary-color);
            background-color: #e3f2fd;
        }
        
        .employee-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 15px;
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
            
            .payment-options {
                flex-direction: column;
                align-items: center;
            }
            
            .payment-option-card {
                width: 100%;
                max-width: 300px;
            }
            
            .enquiry-id-display, .lead-id-display {
                font-size: 22px;
                padding: 10px 15px;
            }
            
            .employee-select-card {
                flex-direction: column;
                text-align: center;
            }
            
            .employee-avatar {
                margin-right: 0;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container main-container">
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
                        <button class="btn btn-sm btn-outline-dark" id="openLeadDashboardBtn">
                            <i class="fas fa-bullseye me-1"></i>Open Lead Dashboard
                        </button>
                    </div>
                </div>
                
                <!-- Visitor Details Display -->
                <div id="frontDeskVisitorDetails" style="display: none;">
                    <!-- Visitor Code Display -->
                    <div class="visitor-code-container">
                        <h5><i class="fas fa-id-card me-2"></i>Visitor Code</h5>
                        <div class="visitor-code-display" id="frontDeskVisitorCodeDisplay">VR-000000</div>
                        <div id="registrationTypeBadge"></div>
                    </div>
                    
                    <!-- Notification Area -->
                    <div id="gatePassNotification" style="display: none; margin-top: 20px;"></div>
                    
                    <!-- Visitor Details Grid -->
                    <div class="visitor-info-grid">
                        <div class="info-item">
                            <div class="info-label">Name</div>
                            <div class="info-value" id="fdDetailName">Not provided</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Contact Number</div>
                            <div class="info-value" id="fdDetailContact">Not provided</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email Address</div>
                            <div class="info-value" id="fdDetailEmail">Not provided</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Purpose of Visit</div>
                            <div class="info-value" id="fdDetailPurpose">Not provided</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Vehicle Type</div>
                            <div class="info-value" id="fdDetailVehicleType">Not provided</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Vehicle Number</div>
                            <div class="info-value" id="fdDetailVehicleNumber">Not provided</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Vehicle Color</div>
                            <div class="info-value" id="fdDetailVehicleColor">Not provided</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Registration Type</div>
                            <div class="info-value" id="fdDetailRegistrationType">Unknown</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Status</div>
                            <div class="info-value" id="fdDetailStatus">Unknown</div>
                        </div>
                    </div>
                    
                    <!-- Visitor Photo -->
                    <div class="mt-4">
                        <h6><i class="fas fa-camera me-2"></i>Visitor Photo</h6>
                        <div class="text-center">
                            <img id="fdDetailPhoto" class="visitor-photo-large" src="" alt="Visitor Photo" style="display: none;">
                            <div id="fdNoPhotoMessage" class="text-muted">
                                <i class="fas fa-user-slash fa-3x mb-2"></i><br>
                                No photo uploaded
                            </div>
                        </div>
                    </div>
                    
                    <!-- Vehicle Photos -->
                    <div class="mt-4">
                        <h6><i class="fas fa-car me-2"></i>Vehicle Photos</h6>
                        <div id="fdDetailVehiclePhotos" class="vehicle-photo-grid" style="display: none;"></div>
                        <div id="fdNoVehiclePhotosMessage" class="text-muted">
                            <i class="fas fa-car fa-3x mb-2"></i><br>
                            No vehicle photos uploaded
                        </div>
                    </div>
                    
                    <!-- Meeting History -->
                    <div id="meetingHistorySection" style="display: none;" class="mt-4">
                        <h6><i class="fas fa-history me-2"></i>Meeting History</h6>
                        <div id="meetingHistoryContainer"></div>
                    </div>
                    
                    <!-- Admission Purpose Notice -->
                    <div id="admissionPurposeNotice" class="alert alert-info mt-4" style="display: none;">
                        <i class="fas fa-graduation-cap me-2"></i>
                        <strong>Admission Purpose Detected!</strong> Please proceed to the "Admission Form" tab for admission processing.
                    </div>
                    
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs mt-4" id="frontDeskTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="self-attendant-tab" data-bs-toggle="tab" data-bs-target="#self-attendant" type="button" role="tab">
                                <i class="fas fa-user-clock me-2"></i>Self Attendant
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="assign-employee-tab" data-bs-toggle="tab" data-bs-target="#assign-employee" type="button" role="tab">
                                <i class="fas fa-user-tie me-2"></i>Assign to Employee
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="admission-form-tab" data-bs-toggle="tab" data-bs-target="#admission-form" type="button" role="tab" disabled>
                                <i class="fas fa-graduation-cap me-2"></i>Admission Form
                            </button>
                        </li>
                    </ul>
                    
                    <!-- Tab Content -->
                    <div class="tab-content" id="frontDeskTabContent">
                        <!-- Self Attendant Tab -->
                        <div class="tab-pane fade show active" id="self-attendant" role="tabpanel">
                            <div class="mt-4">
                                <h6><i class="fas fa-clipboard-check me-2"></i>Generate Gate Pass</h6>
                                <div class="mb-3">
                                    <label for="selfRemarks" class="form-label">Remarks (Optional)</label>
                                    <textarea class="form-control form-textarea" id="selfRemarks" placeholder="Enter any remarks or notes for gate pass"></textarea>
                                </div>
                                <div class="action-buttons">
                                    <button type="button" class="btn btn-primary" id="generateGatePassBtn">
                                        <i class="fas fa-id-card me-2"></i>Generate Gate Pass
                                    </button>
                                    <button type="button" class="btn btn-warning" id="proceedToCheckoutBtn" style="display: none;">
                                        <i class="fas fa-sign-out-alt me-2"></i>Proceed to Checkout
                                    </button>
                                </div>
                                
                                <!-- Gate Pass Preview -->
                                <div id="selfGatePassContainer" class="gate-pass-container mt-4" style="display: none;">
                                    <div class="gate-pass-title">
                                        <i class="fas fa-id-card-alt me-2"></i>Gate Pass
                                    </div>
                                    <div class="visitor-code-display" id="gatePassCode">VR-000000</div>
                                    <div class="gate-pass-details">
                                        <p><strong>Name:</strong> <span id="gatePassName">Visitor Name</span></p>
                                        <p><strong>Purpose:</strong> <span id="gatePassPurpose">Purpose</span></p>
                                        <p><strong>Date:</strong> <span id="gatePassDate">DD/MM/YYYY</span></p>
                                        <p><strong>Time:</strong> <span id="gatePassTime">HH:MM</span></p>
                                        <div id="meetingInfoSection" style="display: none;">
                                            <hr>
                                            <p><strong>Meeting:</strong> <span id="gatePassMeetingTitle">Meeting Title</span></p>
                                            <p id="gatePassMeetingEmployee">With: Employee Name</p>
                                        </div>
                                    </div>
                                    <div class="gate-pass-qr">
                                        <i class="fas fa-qrcode fa-4x"></i>
                                    </div>
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-success" id="printGatePassBtn">
                                            <i class="fas fa-print me-2"></i>Print Gate Pass
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Assign to Employee Tab -->
                        <div class="tab-pane fade" id="assign-employee" role="tabpanel">
                            <div class="mt-4">
                                <h6><i class="fas fa-user-tie me-2"></i>Assign Visitor to Employee</h6>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="departmentSelect" class="form-label required">Department</label>
                                        <select class="form-select" id="departmentSelect" required>
                                            <option value="" selected disabled>Choose Department</option>
                                            <option value="hr">Human Resources</option>
                                            <option value="it">Information Technology</option>
                                            <option value="sales">Sales & Marketing</option>
                                            <option value="finance">Finance & Accounts</option>
                                            <option value="operations">Operations</option>
                                            <option value="rd">Research & Development</option>
                                            <option value="support">Customer Support</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="employeeSelect" class="form-label required">Employee</label>
                                        <select class="form-select" id="employeeSelect" required>
                                            <option value="" selected disabled>Choose Employee</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="meetingTitle" class="form-label required">Meeting Title</label>
                                    <input type="text" class="form-control" id="meetingTitle" placeholder="e.g., Job Interview, Client Meeting, Project Discussion" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="meetingLocation" class="form-label required">Meeting Location</label>
                                    <input type="text" class="form-control" id="meetingLocation" placeholder="e.g., Conference Room A, Office 201, Main Building" required>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="employeeMeetingDate" class="form-label required">Date</label>
                                        <input type="date" class="form-control" id="employeeMeetingDate" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="employeeMeetingTime" class="form-label required">Time</label>
                                        <input type="time" class="form-control" id="employeeMeetingTime" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="meetingDuration" class="form-label required">Duration (mins)</label>
                                        <input type="number" class="form-control" id="meetingDuration" placeholder="e.g., 30, 60" min="15" max="240" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="employeeMeetingNotes" class="form-label">Notes (Optional)</label>
                                    <textarea class="form-control form-textarea" id="employeeMeetingNotes" placeholder="Any additional notes for the meeting"></textarea>
                                </div>
                                
                                <div class="action-buttons">
                                    <button type="button" class="btn btn-primary" id="generateMeetingPassBtn">
                                        <i class="fas fa-id-card me-2"></i>Generate Meeting Pass
                                    </button>
                                    <button type="button" class="btn btn-success" id="assignEmployeeBtn">
                                        <i class="fas fa-user-check me-2"></i>Assign & Schedule Meeting
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="clearAssignmentBtn">
                                        <i class="fas fa-redo me-2"></i>Clear
                                    </button>
                                </div>
                                
                                <!-- Meeting Pass Preview -->
                                <div id="meetingGatePassContainer" class="gate-pass-container mt-4" style="display: none;">
                                    <div class="gate-pass-title">
                                        <i class="fas fa-handshake me-2"></i>Meeting Pass
                                    </div>
                                    <div class="visitor-code-display" id="meetingGatePassCode">VR-000000</div>
                                    <div class="gate-pass-details">
                                        <p><strong>Meeting:</strong> <span id="meetingGatePassTitle">Meeting Title</span></p>
                                        <p><strong>Visitor:</strong> <span id="meetingGatePassVisitor">Visitor Name</span></p>
                                        <p><strong>Time:</strong> <span id="meetingGatePassTime">DD/MM/YYYY at HH:MM</span></p>
                                        <p><strong>Location:</strong> <span id="meetingGatePassLocation">Location</span></p>
                                        <p><strong>Duration:</strong> <span id="meetingGatePassDuration">60 minutes</span></p>
                                        <p><strong>Employee:</strong> <span id="meetingGatePassEmployee">Employee Name</span></p>
                                    </div>
                                </div>
                                
                                <!-- Assignment Success Message -->
                                <div id="assignmentSuccess" class="alert alert-success mt-4" style="display: none;">
                                    <h6><i class="fas fa-check-circle me-2"></i>Successfully Assigned!</h6>
                                    <div id="assignmentDetails"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Admission Form Tab -->
                        <div class="tab-pane fade" id="admission-form" role="tabpanel">
                            <div class="mt-4">
                                <h6><i class="fas fa-graduation-cap me-2"></i>Admission Process</h6>
                                <p class="text-muted">Complete the admission process for educational institutions.</p>
                                
                                <!-- Enquiry ID Section -->
                                <div class="enquiry-id-container">
                                    <h5><i class="fas fa-file-alt me-2"></i>Enquiry ID</h5>
                                    <div class="enquiry-id-display" id="enquiryIdDisplay">ENQ-000000</div>
                                    <button type="button" class="btn btn-light mt-3" id="generateEnquiryIdBtn">
                                        <i class="fas fa-cog me-2"></i>Generate Enquiry ID
                                    </button>
                                </div>
                                
                                <!-- Parent Information Form -->
                                <div class="form-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-user-friends"></i>
                                        Parent/Guardian Information
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="parentFirstName" class="form-label required">First Name</label>
                                            <input type="text" class="form-control" id="parentFirstName" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="parentMiddleName" class="form-label">Middle Name</label>
                                            <input type="text" class="form-control" id="parentMiddleName">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="parentLastName" class="form-label required">Last Name</label>
                                            <input type="text" class="form-control" id="parentLastName" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="parentContact" class="form-label required">Contact Number</label>
                                            <input type="tel" class="form-control" id="parentContact" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="parentEmail" class="form-label required">Email Address</label>
                                            <input type="email" class="form-control" id="parentEmail" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="parentAddress" class="form-label required">Address</label>
                                            <textarea class="form-control form-textarea" id="parentAddress" required></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="parentState" class="form-label required">State</label>
                                            <input type="text" class="form-control" id="parentState" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="parentCity" class="form-label required">City</label>
                                            <input type="text" class="form-control" id="parentCity" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="parentPincode" class="form-label required">Pincode</label>
                                            <input type="text" class="form-control" id="parentPincode" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Student Information Form -->
                                <div class="form-section">
                                    <div class="form-section-title">
                                        <i class="fas fa-user-graduate"></i>
                                        Student Information
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="studentFirstName" class="form-label required">First Name</label>
                                            <input type="text" class="form-control" id="studentFirstName" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="studentMiddleName" class="form-label">Middle Name</label>
                                            <input type="text" class="form-control" id="studentMiddleName">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="studentLastName" class="form-label required">Last Name</label>
                                            <input type="text" class="form-control" id="studentLastName" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="studentRelation" class="form-label required">Relation to Parent</label>
                                            <select class="form-select" id="studentRelation" required>
                                                <option value="" selected disabled>Select Relation</option>
                                                <option value="son">Son</option>
                                                <option value="daughter">Daughter</option>
                                                <option value="ward">Ward</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="studentDepartment" class="form-label required">Department/Course</label>
                                            <input type="text" class="form-control" id="studentDepartment" placeholder="e.g., Computer Science, MBA" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="studentClass" class="form-label required">Class/Grade</label>
                                            <input type="text" class="form-control" id="studentClass" placeholder="e.g., Grade 10, 1st Year" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="admissionForm" class="form-label required">Admission Required?</label>
                                            <select class="form-select" id="admissionForm" required>
                                                <option value="" selected disabled>Select Option</option>
                                                <option value="yes">Yes, proceed with admission</option>
                                                <option value="no">No, only enquiry</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Generate Lead Button -->
                                <div class="text-center mb-4">
                                    <button type="button" class="btn btn-primary btn-lg" id="generateLeadIdBtn">
                                        <i class="fas fa-file-contract me-2"></i>Generate Lead ID
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="clearAdmissionFormBtn">
                                        <i class="fas fa-redo me-2"></i>Clear Form
                                    </button>
                                </div>
                                
                                <!-- Lead ID Section (Hidden by default) -->
                                <div id="leadSection" style="display: none;">
                                    <div class="lead-id-container">
                                        <h5><i class="fas fa-bullseye me-2"></i>Lead ID</h5>
                                        <div class="lead-id-display" id="leadIdDisplay">LEAD-000000</div>
                                        <div class="mt-3">
                                            <span class="status-badge status-new" id="leadStatusBadge">New Lead</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Admission Decision Section -->
                                    <div id="admissionDecisionSection" style="display: none;">
                                        <!-- Yes - Admission Required -->
                                        <div id="admissionYesSection" style="display: none;">
                                            <div class="alert alert-success">
                                                <i class="fas fa-check-circle me-2"></i>
                                                <strong>Great! Let's proceed with the admission process.</strong>
                                            </div>
                                            
                                            <!-- Payment Options -->
                                            <div class="payment-options">
                                                <div class="payment-option-card" id="onlinePaymentOption">
                                                    <div class="payment-option-icon online-payment">
                                                        <i class="fas fa-credit-card"></i>
                                                    </div>
                                                    <h6>Online Payment</h6>
                                                    <p class="text-muted small">Pay securely online via UPI/Bank transfer</p>
                                                </div>
                                                <div class="payment-option-card" id="offlinePaymentOption">
                                                    <div class="payment-option-icon offline-payment">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </div>
                                                    <h6>Offline Payment</h6>
                                                    <p class="text-muted small">Pay at accounts counter with cash/cheque</p>
                                                </div>
                                            </div>
                                            
                                            <!-- Online Payment Section -->
                                            <div id="onlinePaymentSection" class="qr-code-container" style="display: none;">
                                                <h6><i class="fas fa-qrcode me-2"></i>Online Payment</h6>
                                                <div class="payment-qr">
                                                    <i class="fas fa-qrcode fa-4x"></i>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="paymentLink" class="form-label">Payment Link</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="paymentLink" readonly>
                                                        <button class="btn btn-outline-primary" type="button" id="copyPaymentLinkBtn">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="action-buttons">
                                                    <button type="button" class="btn btn-success" id="markPaymentCompleteBtn">
                                                        <i class="fas fa-check-circle me-2"></i>Mark Payment Complete
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- Offline Payment Section -->
                                            <div id="offlinePaymentSection" style="display: none;">
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    <strong>Proceed to Accounts Counter</strong><br>
                                                    Please guide the visitor to the accounts counter for offline payment.
                                                </div>
                                                <div class="action-buttons">
                                                    <button type="button" class="btn btn-warning" id="markInProcessBtn">
                                                        <i class="fas fa-cog me-2"></i>Mark as In Process
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- No - Only Enquiry -->
                                        <div id="admissionNoSection" style="display: none;">
                                            <div class="followup-section">
                                                <h6><i class="fas fa-calendar-alt me-2"></i>Schedule Follow-up</h6>
                                                <div class="mb-3">
                                                    <label for="leadRemarks" class="form-label required">Remarks</label>
                                                    <textarea class="form-control form-textarea" id="leadRemarks" placeholder="Enter remarks for follow-up" required></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="nextFollowDate" class="form-label required">Next Follow-up Date</label>
                                                    <input type="date" class="form-control" id="nextFollowDate" required>
                                                </div>
                                                <div class="action-buttons">
                                                    <button type="button" class="btn btn-primary" id="saveFollowupBtn">
                                                        <i class="fas fa-save me-2"></i>Save Follow-up
                                                    </button>
                                                    <button type="button" class="btn btn-success" id="proceedToGatePassBtn">
                                                        <i class="fas fa-forward me-2"></i>Proceed to Gate Pass
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Process Status Section -->
                                        <div class="process-status mt-4">
                                            <h6><i class="fas fa-tasks me-2"></i>Process Status</h6>
                                            <p id="processStatusMessage">Select admission decision to proceed.</p>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-info btn-sm" id="updateLeadStatusBtn">
                                                    <i class="fas fa-sync me-1"></i>Update Status
                                                </button>
                                                <button type="button" class="btn btn-success btn-sm" id="completeAdmissionBtn">
                                                    <i class="fas fa-graduation-cap me-1"></i>Complete Admission
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Employee Assignment Section (For Offline Payment) -->
                                    <div id="employeeAssignmentSection" class="employee-assignment-section" style="display: none;">
                                        <h6><i class="fas fa-user-tie me-2"></i>Assign Admission Officer</h6>
                                        <p class="text-muted">Select an admission officer to guide the visitor through the process.</p>
                                        
                                        <div class="mb-3">
                                            <label for="admissionNotes" class="form-label">Notes for Admission Officer</label>
                                            <textarea class="form-control form-textarea" id="admissionNotes" placeholder="Enter any specific notes or instructions"></textarea>
                                        </div>
                                        
                                        <h6>Available Admission Officers:</h6>
                                        <div id="admissionOfficersList"></div>
                                        
                                        <div class="action-buttons mt-3">
                                            <button type="button" class="btn btn-success" id="assignAdmissionOfficerBtn">
                                                <i class="fas fa-user-check me-2"></i>Assign Officer
                                            </button>
                                            <button type="button" class="btn btn-secondary" id="skipAssignmentBtn">
                                                <i class="fas fa-forward me-2"></i>Skip Assignment
                                            </button>
                                        </div>
                                    </div>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
            let currentEnquiryId = '';
            let currentLeadId = '';
            let selectedPaymentMethod = '';
            let selectedAdmissionOfficer = null;
            
            // Sample employee data by department
            const employeesByDepartment = {
                'hr': [
                    { id: 1, name: 'Sarah Johnson', position: 'HR Manager', employeeId: 'EMP001' },
                    { id: 2, name: 'Michael Chen', position: 'Recruitment Specialist', employeeId: 'EMP002' },
                    { id: 3, name: 'Emily Davis', position: 'Training Coordinator', employeeId: 'EMP003' }
                ],
                'it': [
                    { id: 4, name: 'Robert Wilson', position: 'IT Director', employeeId: 'EMP004' },
                    { id: 5, name: 'David Miller', position: 'Software Developer', employeeId: 'EMP005' },
                    { id: 6, name: 'Lisa Thompson', position: 'System Administrator', employeeId: 'EMP006' }
                ],
                'sales': [
                    { id: 7, name: 'James Anderson', position: 'Sales Manager', employeeId: 'EMP007' },
                    { id: 8, name: 'Maria Garcia', position: 'Account Executive', employeeId: 'EMP008' },
                    { id: 9, name: 'Thomas Lee', position: 'Marketing Specialist', employeeId: 'EMP009' }
                ],
                'finance': [
                    { id: 10, name: 'Patricia Brown', position: 'Finance Director', employeeId: 'EMP010' },
                    { id: 11, name: 'Christopher Taylor', position: 'Accountant', employeeId: 'EMP011' }
                ],
                'operations': [
                    { id: 12, name: 'Jennifer White', position: 'Operations Manager', employeeId: 'EMP012' },
                    { id: 13, name: 'Daniel Moore', position: 'Logistics Coordinator', employeeId: 'EMP013' }
                ],
                'rd': [
                    { id: 14, name: 'Kevin Martin', position: 'Research Lead', employeeId: 'EMP014' },
                    { id: 15, name: 'Amanda Clark', position: 'Product Developer', employeeId: 'EMP015' }
                ],
                'support': [
                    { id: 16, name: 'Brian Lewis', position: 'Support Manager', employeeId: 'EMP016' },
                    { id: 17, name: 'Nancy Hall', position: 'Customer Service Rep', employeeId: 'EMP017' }
                ]
            };
            
            // Admission Officers (Specialized for admissions)
            const admissionOfficers = [
                { id: 101, name: 'Dr. Ravi Sharma', position: 'Head of Admissions', department: 'academic', employeeId: 'ADM001' },
                { id: 102, name: 'Ms. Priya Patel', position: 'Admission Counselor', department: 'academic', employeeId: 'ADM002' },
                { id: 103, name: 'Mr. Rajesh Kumar', position: 'Admission Officer', department: 'non-academic', employeeId: 'ADM003' },
                { id: 104, name: 'Mrs. Anjali Singh', position: 'Admission Coordinator', department: 'academic', employeeId: 'ADM004' },
                { id: 105, name: 'Mr. Vikram Mehta', position: 'Admission Executive', department: 'non-academic', employeeId: 'ADM005' }
            ];
            
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
                            purpose: "Admission",
                            vehicleType: "Car",
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
                    console.log('Sample visitor data created with 3 visitors (including Admission)');
                }
            }
            
            // Call initialization
            initializeSampleData();
            
            // Event Listeners for existing functionality
            document.getElementById('frontDeskFetchBtn').addEventListener('click', fetchVisitorDetails);
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
                // Check for leads
                const allLeads = getAllLeadsFromStorage();
                console.log('Total leads found:', allLeads.length);
                console.log('Lead IDs:', allLeads.map(l => l.leadId));
                
                alert(`Front Desk Storage: ${allVisitors.length} visitor(s)\nLeads: ${allLeads.length}\n\nCheck browser console for full details.`);
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
                const attendanceURL = '/employee_attendant_form';
                window.open(attendanceURL, '_blank');
            });
            
            document.getElementById('openCheckoutPageBtn').addEventListener('click', function() {
                const checkoutURL = '/checkout_gate_pass';
                window.open(checkoutURL, '_blank');
            });
            
            // NEW: Open Lead Dashboard button
            document.getElementById('openLeadDashboardBtn').addEventListener('click', function() {
                const leadDashboardURL = '/lead_management_dashboard';
                window.open(leadDashboardURL, '_blank');
            });
            
            // Self Attendant Tab Actions
            document.getElementById('generateGatePassBtn').addEventListener('click', generateGatePass);
            document.getElementById('printGatePassBtn').addEventListener('click', function() {
                alert('Printing gate pass... (In a real system, this would open print dialog)');
                window.print();
            });
            
            // Proceed to Checkout button
            document.getElementById('proceedToCheckoutBtn').addEventListener('click', function() {
                if (currentVisitor) {
                    sessionStorage.setItem('checkoutVisitorCode', currentVisitor.code);
                    const checkoutURL = '/checkout_gate_pass';
                    window.open(checkoutURL, '_blank');
                    resetFrontDeskForm();
                } else {
                    alert('No visitor selected. Please fetch visitor details first.');
                }
            });
            
            // Assigned to Employee Tab Actions
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
            
            // Admission Form Tab Event Listeners
            document.getElementById('generateEnquiryIdBtn').addEventListener('click', generateEnquiryId);
            document.getElementById('generateLeadIdBtn').addEventListener('click', generateLeadId);
            document.getElementById('clearAdmissionFormBtn').addEventListener('click', clearAdmissionForm);
            document.getElementById('admissionForm').addEventListener('change', handleAdmissionDecision);
            
            // Payment Options
            document.getElementById('onlinePaymentOption').addEventListener('click', function() {
                selectPaymentMethod('online');
            });
            
            document.getElementById('offlinePaymentOption').addEventListener('click', function() {
                selectPaymentMethod('offline');
            });
            
            // Payment Actions
            document.getElementById('copyPaymentLinkBtn').addEventListener('click', function() {
                const paymentLink = document.getElementById('paymentLink');
                paymentLink.select();
                document.execCommand('copy');
                showSuccessMessage('Payment link copied to clipboard!');
            });
            
            document.getElementById('markPaymentCompleteBtn').addEventListener('click', function() {
                updateLeadStatus('payment_completed');
                showSuccessMessage('Payment marked as completed! Proceeding to gate pass generation.');
                setTimeout(() => {
                    document.getElementById('self-attendant-tab').click();
                }, 2000);
            });
            
            // Offline Payment Actions
            document.getElementById('markInProcessBtn').addEventListener('click', function() {
                // For offline payment, show employee assignment section
                document.getElementById('employeeAssignmentSection').style.display = 'block';
                document.getElementById('processStatusMessage').textContent = 'Please assign an admission officer to guide the visitor.';
                document.getElementById('markInProcessBtn').style.display = 'none';
                
                // Load admission officers
                loadAdmissionOfficers();
            });
            
            // Admission Officer Assignment Actions
            document.getElementById('assignAdmissionOfficerBtn').addEventListener('click', assignAdmissionOfficer);
            document.getElementById('skipAssignmentBtn').addEventListener('click', function() {
                showSuccessMessage('Skipping assignment. Proceeding to gate pass generation.');
                setTimeout(() => {
                    document.getElementById('self-attendant-tab').click();
                }, 2000);
            });
            
            // Follow-up Actions
            document.getElementById('saveFollowupBtn').addEventListener('click', function() {
                const remarks = document.getElementById('leadRemarks').value;
                const nextFollowDate = document.getElementById('nextFollowDate').value;
                
                if (!remarks || !nextFollowDate) {
                    alert('Please fill all required fields for follow-up.');
                    return;
                }
                
                updateLeadStatus('follow_up_scheduled', remarks, nextFollowDate);
                showSuccessMessage('Follow-up scheduled successfully! Proceeding to gate pass generation.');
                setTimeout(() => {
                    document.getElementById('self-attendant-tab').click();
                }, 2000);
            });
            
            document.getElementById('proceedToGatePassBtn').addEventListener('click', function() {
                showSuccessMessage('Proceeding to gate pass generation.');
                setTimeout(() => {
                    document.getElementById('self-attendant-tab').click();
                }, 1000);
            });
            
            document.getElementById('updateLeadStatusBtn').addEventListener('click', function() {
                const newStatus = prompt('Enter new lead status:');
                if (newStatus) {
                    updateLeadStatus('updated', newStatus);
                }
            });
            
            document.getElementById('completeAdmissionBtn').addEventListener('click', function() {
                updateLeadStatus('admission_completed');
                showSuccessMessage('Admission process completed successfully!');
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
                alert('1');
                // Display visitor code
                document.getElementById('frontDeskVisitorCodeDisplay').textContent = currentVisitor.code;
                
                // Display registration type with badge
                const registrationType = currentVisitor.registration_type || 'Unknown';
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
                }
                
                // Check if there's admission officer info to display
                if (selectedAdmissionOfficer) {
                    if (!document.getElementById('meetingInfoSection').style.display || document.getElementById('meetingInfoSection').style.display === 'none') {
                        document.getElementById('meetingInfoSection').style.display = 'block';
                    }
                    document.getElementById('gatePassMeetingTitle').textContent = 'Admission Process';
                    document.getElementById('gatePassMeetingEmployee').textContent = 
                        `With: ${selectedAdmissionOfficer.name} (${selectedAdmissionOfficer.position})`;
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
                    meetingInfo: window.pendingMeetingInfo || null,
                    admissionOfficer: selectedAdmissionOfficer || null
                });
                
                // Update localStorage
                updateVisitorInStorage();
                
                // Clear pending meeting info
                window.pendingMeetingInfo = null;
                
                // Show success message
                showSuccessMessage(`Gate pass generated for ${currentVisitor.name} (${currentVisitor.code})`);
            }
            
            function loadEmployeesByDepartment() {
                const department = document.getElementById('departmentSelect').value;
                const employeeSelect = document.getElementById('employeeSelect');
                
                // Clear existing options
                employeeSelect.innerHTML = '<option value="" selected disabled>Choose Employee</option>';
                
                if (department && employeesByDepartment[department]) {
                    employeesByDepartment[department].forEach(employee => {
                        const option = document.createElement('option');
                        option.value = employee.employeeId;
                        option.textContent = `${employee.name} - ${employee.position}`;
                        employeeSelect.appendChild(option);
                    });
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
            
            function assignToEmployeeAndScheduleMeeting() {
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
                const meetingNotes = document.getElementById('employeeMeetingNotes').value;
                
                if (!department || !employeeId || !meetingTitle || !meetingLocation || !meetingDate || !meetingTime || !meetingDuration) {
                    alert('Please fill all required fields');
                    return;
                }
                
                // Get selected employee
                const selectedEmployee = employeesByDepartment[department].find(e => e.employeeId === employeeId);
                
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
                    employee: selectedEmployee,
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
            
            // NEW: Function to get all leads from storage
            function getAllLeadsFromStorage() {
                const storedData = localStorage.getItem('visitorRegistrationData');
                if (!storedData) return [];
                
                const allVisitors = JSON.parse(storedData);
                const allLeads = [];
                
                allVisitors.forEach(visitor => {
                    if (visitor.frontDeskActions && Array.isArray(visitor.frontDeskActions)) {
                        visitor.frontDeskActions.forEach(action => {
                            if (action.type === 'lead_generated') {
                                allLeads.push({
                                    leadId: action.leadId,
                                    enquiryId: action.enquiryId,
                                    visitorCode: visitor.code,
                                    visitorName: visitor.name,
                                    visitorContact: visitor.contact,
                                    visitorEmail: visitor.email,
                                    parentInfo: action.parentInfo,
                                    studentInfo: action.studentInfo,
                                    admissionRequired: action.admissionRequired,
                                    status: action.status || 'new_lead',
                                    createdAt: action.timestamp,
                                    lastUpdated: action.updatedAt || action.timestamp,
                                    remarks: action.remarks || '',
                                    nextFollowUpDate: action.nextFollowUpDate || '',
                                    paymentMethod: action.paymentMethod || '',
                                    admissionOfficer: action.admissionOfficer || null,
                                    visitorId: visitor.id
                                });
                            }
                        });
                    }
                });
                
                return allLeads;
            }
            
            // Admission Form Functions
            function generateEnquiryId() {
                const enquiryId = 'ENQ-' + Date.now().toString().substr(-6);
                currentEnquiryId = enquiryId;
                
                document.getElementById('enquiryIdDisplay').textContent = enquiryId;
                document.getElementById('generateEnquiryIdBtn').disabled = true;
                document.getElementById('generateEnquiryIdBtn').innerHTML = '<i class="fas fa-check me-2"></i>Enquiry ID Generated';
                document.getElementById('generateEnquiryIdBtn').classList.remove('btn-light');
                document.getElementById('generateEnquiryIdBtn').classList.add('btn-success');
                
                showSuccessMessage(`Enquiry ID ${enquiryId} generated successfully!`);
                
                // Auto-fill parent info from visitor data if available
                if (currentVisitor) {
                    document.getElementById('parentFirstName').value = currentVisitor.name.split(' ')[0] || '';
                    document.getElementById('parentLastName').value = currentVisitor.name.split(' ').slice(-1)[0] || '';
                    document.getElementById('parentContact').value = currentVisitor.contact || '';
                    document.getElementById('parentEmail').value = currentVisitor.email || '';
                }
            }
            
            function generateLeadId() {
                // Validate required fields
                const requiredFields = [
                    'parentFirstName', 'parentLastName', 'parentContact', 'parentEmail',
                    'parentAddress', 'parentState', 'parentCity', 'parentPincode',
                    'studentFirstName', 'studentLastName', 'studentRelation', 'studentDepartment', 'studentClass', 'admissionForm'
                ];
                
                for (const fieldId of requiredFields) {
                    const field = document.getElementById(fieldId);
                    if (!field.value) {
                        alert(`Please fill in ${field.previousElementSibling.textContent}`);
                        field.focus();
                        return;
                    }
                }
                
                if (!currentEnquiryId) {
                    alert('Please generate Enquiry ID first');
                    return;
                }
                
                // Generate Lead ID
                const leadId = 'LEAD-' + Date.now().toString().substr(-6);
                currentLeadId = leadId;
                
                // Display lead ID
                document.getElementById('leadIdDisplay').textContent = leadId;
                document.getElementById('leadStatusBadge').textContent = 'New Lead';
                document.getElementById('leadStatusBadge').className = 'status-badge status-new';
                
                // Show lead section
                document.getElementById('leadSection').style.display = 'block';
                document.getElementById('admissionDecisionSection').style.display = 'block';
                
                // Handle admission decision
                handleAdmissionDecision();
                
                // Create lead data object
                const leadData = {
                    type: 'lead_generated',
                    timestamp: new Date().toISOString(),
                    enquiryId: currentEnquiryId,
                    leadId: currentLeadId,
                    parentInfo: {
                        firstName: document.getElementById('parentFirstName').value,
                        middleName: document.getElementById('parentMiddleName').value,
                        lastName: document.getElementById('parentLastName').value,
                        contact: document.getElementById('parentContact').value,
                        email: document.getElementById('parentEmail').value,
                        address: document.getElementById('parentAddress').value,
                        state: document.getElementById('parentState').value,
                        city: document.getElementById('parentCity').value,
                        pincode: document.getElementById('parentPincode').value
                    },
                    studentInfo: {
                        firstName: document.getElementById('studentFirstName').value,
                        middleName: document.getElementById('studentMiddleName').value,
                        lastName: document.getElementById('studentLastName').value,
                        relation: document.getElementById('studentRelation').value,
                        department: document.getElementById('studentDepartment').value,
                        class: document.getElementById('studentClass').value
                    },
                    admissionRequired: document.getElementById('admissionForm').value,
                    status: 'new_lead'
                };
                
                // Add front desk action record
                if (!currentVisitor.frontDeskActions) {
                    currentVisitor.frontDeskActions = [];
                }
                
                currentVisitor.frontDeskActions.push(leadData);
                
                // Update localStorage
                updateVisitorInStorage();
                
                // Trigger localStorage update event for Lead Dashboard
                triggerLeadUpdateEvent(leadData);
                
                showSuccessMessage(`Lead ${leadId} generated successfully!`);
                
                // Scroll to lead section
                document.getElementById('leadSection').scrollIntoView({ behavior: 'smooth' });
            }
            
            // NEW: Trigger event for Lead Dashboard
            function triggerLeadUpdateEvent(leadData) {
                // Create a custom event to notify Lead Dashboard about new lead
                const leadUpdateEvent = new CustomEvent('leadUpdated', {
                    detail: {
                        leadId: leadData.leadId,
                        visitorName: currentVisitor.name,
                        timestamp: new Date().toISOString()
                    }
                });
                window.dispatchEvent(leadUpdateEvent);
                
                // Also store in sessionStorage for cross-tab communication
                sessionStorage.setItem('latestLeadUpdate', JSON.stringify({
                    leadId: leadData.leadId,
                    timestamp: new Date().toISOString()
                }));
            }
            
            function handleAdmissionDecision() {
                const admissionDecision = document.getElementById('admissionForm').value;
                
                if (admissionDecision === 'yes') {
                    document.getElementById('admissionYesSection').style.display = 'block';
                    document.getElementById('admissionNoSection').style.display = 'none';
                } else if (admissionDecision === 'no') {
                    document.getElementById('admissionYesSection').style.display = 'none';
                    document.getElementById('admissionNoSection').style.display = 'block';
                    
                    // Set default follow-up date to tomorrow
                    const tomorrow = new Date();
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    document.getElementById('nextFollowDate').value = tomorrow.toISOString().split('T')[0];
                } else {
                    document.getElementById('admissionYesSection').style.display = 'none';
                    document.getElementById('admissionNoSection').style.display = 'none';
                }
            }
            
            function selectPaymentMethod(method) {
                selectedPaymentMethod = method;
                
                // Remove selected class from all options
                document.querySelectorAll('.payment-option-card').forEach(card => {
                    card.classList.remove('selected');
                });
                
                // Add selected class to chosen option
                if (method === 'online') {
                    document.getElementById('onlinePaymentOption').classList.add('selected');
                    document.getElementById('offlinePaymentOption').classList.remove('selected');
                    document.getElementById('onlinePaymentSection').style.display = 'block';
                    document.getElementById('offlinePaymentSection').style.display = 'none';
                    
                    // Update payment link with lead ID
                    document.getElementById('paymentLink').value = `https://payment.example.com/pay/${currentLeadId}`;
                } else {
                    document.getElementById('offlinePaymentOption').classList.add('selected');
                    document.getElementById('onlinePaymentOption').classList.remove('selected');
                    document.getElementById('offlinePaymentSection').style.display = 'block';
                    document.getElementById('onlinePaymentSection').style.display = 'none';
                    
                    // Reset employee assignment section
                    document.getElementById('employeeAssignmentSection').style.display = 'none';
                    document.getElementById('processStatusMessage').textContent = 'Please proceed to accounts counter for payment and form submission.';
                    document.getElementById('markInProcessBtn').style.display = 'inline-block';
                    selectedAdmissionOfficer = null;
                }
            }
            
            function loadAdmissionOfficers() {
                const studentDept = document.getElementById('studentDepartment').value;
                const officersList = document.getElementById('admissionOfficersList');
                officersList.innerHTML = '';
                
                // Filter admission officers by department if needed
                const filteredOfficers = admissionOfficers.filter(officer => 
                    studentDept === '' || officer.department === studentDept
                );
                
                if (filteredOfficers.length === 0) {
                    officersList.innerHTML = '<p class="text-muted">No admission officers available for this department.</p>';
                    return;
                }
                
                // Create officer cards
                filteredOfficers.forEach(officer => {
                    const card = document.createElement('div');
                    card.className = 'employee-select-card d-flex align-items-center';
                    card.dataset.employeeId = officer.employeeId;
                    
                    // Create avatar with initials
                    const initials = officer.name.split(' ').map(n => n[0]).join('').toUpperCase();
                    
                    card.innerHTML = `
                        <div class="employee-avatar">${initials}</div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${officer.name}</h6>
                            <p class="mb-0 text-muted">${officer.position}</p>
                            <small class="text-primary">ID: ${officer.employeeId}</small>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="admissionOfficer" id="officer_${officer.employeeId}" value="${officer.employeeId}">
                            <label class="form-check-label" for="officer_${officer.employeeId}"></label>
                        </div>
                    `;
                    
                    // Add click event to select officer
                    card.addEventListener('click', function(e) {
                        if (!e.target.classList.contains('form-check-input')) {
                            const radio = this.querySelector('.form-check-input');
                            radio.checked = !radio.checked;
                            radio.dispatchEvent(new Event('change'));
                        }
                    });
                    
                    // Add change event to radio button
                    const radioBtn = card.querySelector('.form-check-input');
                    radioBtn.addEventListener('change', function() {
                        if (this.checked) {
                            // Remove selected class from all cards
                            document.querySelectorAll('.employee-select-card').forEach(c => {
                                c.classList.remove('selected');
                            });
                            
                            // Add selected class to current card
                            card.classList.add('selected');
                            selectedAdmissionOfficer = officer;
                        }
                    });
                    
                    officersList.appendChild(card);
                });
            }
            
            function assignAdmissionOfficer() {
                if (!selectedAdmissionOfficer) {
                    alert('Please select an admission officer first.');
                    return;
                }
                
                const admissionNotes = document.getElementById('admissionNotes').value;
                
                // Create admission officer assignment data
                const officerAssignment = {
                    type: 'admission_officer_assigned',
                    timestamp: new Date().toISOString(),
                    officer: selectedAdmissionOfficer,
                    notes: admissionNotes,
                    leadId: currentLeadId
                };
                
                // Update lead status with admission officer assignment
                updateLeadStatus('admission_officer_assigned', admissionNotes);
                
                // Add front desk action record
                if (!currentVisitor.frontDeskActions) {
                    currentVisitor.frontDeskActions = [];
                }
                
                currentVisitor.frontDeskActions.push(officerAssignment);
                
                // Update localStorage
                updateVisitorInStorage();
                
                // Trigger update event
                triggerLeadUpdateEvent(officerAssignment);
                
                // Show success and proceed to gate pass
                showSuccessMessage(`Admission officer ${selectedAdmissionOfficer.name} assigned successfully!`);
                
                // Update process status
                document.getElementById('processStatusMessage').innerHTML = `
                    <strong>Admission Officer Assigned:</strong><br>
                    ${selectedAdmissionOfficer.name} (${selectedAdmissionOfficer.position})<br>
                    <small class="text-muted">ID: ${selectedAdmissionOfficer.employeeId}</small>
                `;
                
                // Change button to proceed to gate pass
                const proceedBtn = document.createElement('button');
                proceedBtn.type = 'button';
                proceedBtn.className = 'btn btn-success mt-2';
                proceedBtn.innerHTML = '<i class="fas fa-forward me-2"></i>Proceed to Gate Pass';
                proceedBtn.addEventListener('click', function() {
                    showSuccessMessage('Proceeding to gate pass generation.');
                    setTimeout(() => {
                        document.getElementById('self-attendant-tab').click();
                    }, 1000);
                });
                
                // Replace the assignment section with proceed button
                const processStatusDiv = document.querySelector('.process-status .d-flex');
                if (processStatusDiv) {
                    processStatusDiv.innerHTML = '';
                    processStatusDiv.appendChild(proceedBtn);
                }
            }
            
            function updateLeadStatus(status, remarks = '', followUpDate = '') {
                if (!currentVisitor || !currentLeadId) return;
                
                // Find the lead action
                const leadAction = currentVisitor.frontDeskActions.find(action => 
                    action.type === 'lead_generated' && action.leadId === currentLeadId
                );
                
                if (leadAction) {
                    leadAction.status = status;
                    leadAction.updatedAt = new Date().toISOString();
                    
                    if (remarks) {
                        leadAction.remarks = remarks;
                    }
                    
                    if (followUpDate) {
                        leadAction.nextFollowUpDate = followUpDate;
                    }
                    
                    if (selectedPaymentMethod) {
                        leadAction.paymentMethod = selectedPaymentMethod;
                    }
                    
                    if (selectedAdmissionOfficer) {
                        leadAction.admissionOfficer = selectedAdmissionOfficer;
                    }
                    
                    // Update status display
                    let statusText = '';
                    let statusClass = '';
                    
                    switch(status) {
                        case 'payment_completed':
                            statusText = 'Payment Completed';
                            statusClass = 'status-completed';
                            break;
                        case 'admission_in_process':
                            statusText = 'Admission In Process';
                            statusClass = 'status-inprocess';
                            break;
                        case 'admission_officer_assigned':
                            statusText = 'Officer Assigned';
                            statusClass = 'status-inprocess';
                            break;
                        case 'follow_up_scheduled':
                            statusText = 'Follow-up Scheduled';
                            statusClass = 'status-followup';
                            break;
                        case 'admission_completed':
                            statusText = 'Admission Completed';
                            statusClass = 'status-completed';
                            break;
                        default:
                            statusText = status;
                            statusClass = 'status-new';
                    }
                    
                    document.getElementById('leadStatusBadge').textContent = statusText;
                    document.getElementById('leadStatusBadge').className = 'status-badge ' + statusClass;
                    
                    // Update localStorage
                    updateVisitorInStorage();
                    
                    // Trigger update event
                    triggerLeadUpdateEvent(leadAction);
                    
                    console.log(`✅ Lead status updated: ${currentLeadId} -> ${status}`);
                }
            }
            
            function clearAdmissionForm() {
                // Clear all form fields
                document.getElementById('parentFirstName').value = '';
                document.getElementById('parentMiddleName').value = '';
                document.getElementById('parentLastName').value = '';
                document.getElementById('parentContact').value = '';
                document.getElementById('parentEmail').value = '';
                document.getElementById('parentAddress').value = '';
                document.getElementById('parentState').value = '';
                document.getElementById('parentCity').value = '';
                document.getElementById('parentPincode').value = '';
                
                document.getElementById('studentFirstName').value = '';
                document.getElementById('studentMiddleName').value = '';
                document.getElementById('studentLastName').value = '';
                document.getElementById('studentRelation').value = '';
                document.getElementById('studentDepartment').value = '';
                document.getElementById('studentClass').value = '';
                document.getElementById('admissionForm').value = '';
                
                // Reset enquiry ID
                currentEnquiryId = '';
                document.getElementById('enquiryIdDisplay').textContent = 'ENQ-000000';
                document.getElementById('generateEnquiryIdBtn').disabled = false;
                document.getElementById('generateEnquiryIdBtn').innerHTML = '<i class="fas fa-cog me-2"></i>Generate Enquiry ID';
                document.getElementById('generateEnquiryIdBtn').classList.remove('btn-success');
                document.getElementById('generateEnquiryIdBtn').classList.add('btn-light');
                
                // Hide lead section
                document.getElementById('leadSection').style.display = 'none';
                document.getElementById('admissionDecisionSection').style.display = 'none';
                document.getElementById('admissionYesSection').style.display = 'none';
                document.getElementById('admissionNoSection').style.display = 'none';
                
                // Reset payment options
                document.querySelectorAll('.payment-option-card').forEach(card => {
                    card.classList.remove('selected');
                });
                document.getElementById('onlinePaymentSection').style.display = 'none';
                document.getElementById('offlinePaymentSection').style.display = 'none';
                
                // Reset employee assignment
                document.getElementById('employeeAssignmentSection').style.display = 'none';
                selectedPaymentMethod = '';
                selectedAdmissionOfficer = null;
                
                showSuccessMessage('Admission form cleared successfully.');
            }
            
            function updateVisitorInStorage() {
                if (!currentVisitor) return;
                
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
                clearAdmissionForm();
                
                // Reset Admission tab to disabled state
                const admissionTab = document.getElementById('admission-form-tab');
                admissionTab.disabled = true;
                admissionTab.classList.add('disabled');
                document.getElementById('admissionPurposeNotice').style.display = 'none';
                
                // Reset to Self Attendant tab
                document.getElementById('self-attendant-tab').click();
                
                // Clear window variable
                window.pendingMeetingInfo = null;
                
                // Focus on code input
                document.getElementById('frontDeskCodeInput').focus();
            }
            
            // Initialize the form
            console.log('Front Desk Management System initialized');
            console.log('Shared storage with visitor check-in system');
            console.log('Available visitor codes:', allVisitors.map(v => v.code));
            
            // Check for existing leads
            const allLeads = getAllLeadsFromStorage();
            console.log(`Total leads in system: ${allLeads.length}`);
            
            // Set default date to today and time to next hour
            const now = new Date();
            const nextHour = new Date(now.getTime() + 60 * 60 * 1000);
            
            const today = now.toISOString().split('T')[0];
            const nextHourTime = nextHour.toTimeString().substring(0, 5);
            
            // Set default values for date and time inputs
            document.getElementById('employeeMeetingDate').value = today;
            document.getElementById('employeeMeetingTime').value = nextHourTime;
            
            // Set default follow-up date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('nextFollowDate').value = tomorrow.toISOString().split('T')[0];
            
            // Listen for storage events from other tabs (like Lead Dashboard)
            window.addEventListener('storage', function(event) {
                if (event.key === 'visitorRegistrationData') {
                    console.log('Storage updated from another tab, reloading data...');
                    const storedData = localStorage.getItem('visitorRegistrationData');
                    if (storedData) {
                        allVisitors = JSON.parse(storedData);
                        console.log('Data reloaded from storage event');
                    }
                }
            });
        });
    </script>
</body>
</html>
@endsection