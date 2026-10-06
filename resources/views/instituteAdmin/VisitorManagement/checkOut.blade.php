@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Visitor Checkout System</title>
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

        /*body {*/
        /*    background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);*/
        /*    min-height: 100vh;*/
        /*    padding: 20px 0;*/
        /*}*/
        
        .main-container {
            margin: 0 auto;
        }
        
        .header-section {
            background: var(--gradient-success);
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
            background: var(--gradient-success);
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
        
        .badge-checkedin {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .badge-checkedout {
            background-color: #d4edda;
            color: #155724;
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
        
        .checkout-visitor-info {
            background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
            border-radius: var(--radius);
            padding: 25px;
            margin-bottom: 25px;
            border-left: 4px solid var(--success-color);
        }
        
        .checkout-summary {
            background: white;
            border: 2px solid #dee2e6;
            border-radius: var(--radius);
            padding: 25px;
            margin-bottom: 25px;
        }
        
        .checkout-timestamp {
            font-size: 14px;
            color: #6c757d;
            margin-top: 5px;
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
                <i class="fas fa-sign-out-alt"></i>
                <div>
                    Visitor Checkout System
                    <div class="fs-6 fw-normal">Complete visitor checkout process</div>
                </div>
            </div>
            <p class="mb-0 mt-2">Enter visitor code to complete checkout process</p>
        </div>
        
        <!-- Main Card -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-user-check me-2"></i>Visitor Checkout</h4>
            </div>
            <div class="card-body">
                <!-- Visitor Code Entry Section -->
                <div class="section-title">
                    <i class="fas fa-search"></i>
                    <h5 class="mb-0">Find Visitor by Code</h5>
                </div>
                
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>Enter the visitor code to fetch visitor details and proceed with checkout process.</p>
                </div>
                
                <div class="mb-4">
                    <label for="checkoutCodeInput" class="form-label required">Visitor Code</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="checkoutCodeInput" placeholder="Enter VR-XXXXXX code" required>
                        <button class="btn btn-primary" type="button" id="checkoutFetchBtn">
                            <i class="fas fa-search me-2"></i>Fetch Visitor Details
                        </button>
                    </div>
                    <small class="text-muted">Enter the visitor code (e.g., VR-A1B2C3)</small>
                </div>
                
                <!-- Test Buttons -->
                <div class="alert alert-info mb-4">
                    <i class="fas fa-vial me-2"></i>
                    <strong>System Tools:</strong>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        <button class="btn btn-sm btn-outline-info" id="showCheckoutCodesBtn">
                            <i class="fas fa-eye me-1"></i>Show Available Codes
                        </button>
                        <button class="btn btn-sm btn-outline-warning" id="openFrontDeskBtn">
                            <i class="fas fa-concierge-bell me-1"></i>Open Front Desk
                        </button>
                        <button class="btn btn-sm btn-outline-success" id="openAttendanceSystemBtn">
                            <i class="fas fa-handshake me-1"></i>Open Attendance System
                        </button>
                    </div>
                </div>
                
                <!-- Visitor Details Display -->
                <div id="checkoutVisitorDetails" style="display: none;">
                    <!-- Visitor Code Display -->
                    <div class="visitor-code-container">
                        <h5><i class="fas fa-id-card me-2"></i>Check Out Pass Code</h5>
                        <div class="visitor-code-display" id="checkoutVisitorCodeDisplay">OP-000000</div>
                        <div id="checkoutRegistrationTypeBadge" class="mt-2" style="position: relative; z-index: 2;">
                            <!-- Registration type badge will appear here -->
                        </div>
                    </div>
                    
                    <!-- Visitor Information -->
                    <div class="checkout-visitor-info mt-4">
                        <div class="pass_id" id="out_pass_id"></div>
                        <h5><i class="fas fa-user me-2"></i>Visitor Details</h5>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> <span id="checkoutDetailName"></span></p>
                                <p><strong>Contact:</strong> <span id="checkoutDetailContact"></span></p>
                                <p><strong>Email:</strong> <span id="checkoutDetailEmail"></span></p>
                                <p><strong>Purpose:</strong> <span id="checkoutDetailPurpose"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Vehicle:</strong> <span id="checkoutDetailVehicle"></span></p>
                                <p><strong>Check-in Time:</strong> <span id="checkoutDetailCheckinTime"></span></p>
                                <p><strong>Registration Type:</strong> <span id="checkoutDetailRegistrationType"></span></p>
                                <p><strong>Current Status:</strong> 
                                    <span id="checkoutDetailStatus" class="check-in-badge badge-checkedin">Checked In</span>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Visitor Photo -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="info-label mb-2">Visitor Photo</div>
                                <div class="text-center">
                                    <img id="checkoutDetailPhoto" class="visitor-photo-large" src="" alt="Visitor Photo" style="display: none;">
                                    <div id="checkoutNoPhotoMessage" class="text-muted">No photo uploaded</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label mb-2">Vehicle Photos</div>
                                <div id="checkoutDetailVehiclePhotos" class="vehicle-photo-grid">
                                    <!-- Vehicle photos will appear here -->
                                </div>
                                <div id="checkoutNoVehiclePhotosMessage" class="text-muted">No vehicle photos uploaded</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Meeting Information (if any) -->
                    <div id="checkoutMeetingInfo" style="display: none;">
                        <div class="section-title">
                            <i class="fas fa-handshake"></i>
                            <h5 class="mb-0">Meeting Information</h5>
                        </div>
                        <div id="meetingInfoContainer">
                            <!-- Meeting info will be dynamically loaded here -->
                        </div>
                    </div>
                    
                    <!-- Meeting History Section -->
                    <div id="checkoutMeetingHistorySection" style="display: none;">
                        <div class="section-title">
                            <i class="fas fa-history"></i>
                            <h5 class="mb-0">Meeting History</h5>
                        </div>
                        <div id="checkoutMeetingHistoryContainer">
                            <!-- Meeting history will be dynamically loaded here -->
                        </div>
                    </div>
                    
                    <!-- Checkout Form -->
                    <div class="checkout-summary">
                        <h5><i class="fas fa-clipboard-check me-2"></i>Checkout Details</h5>
                        
                        <div class="mb-4">
                            <label for="checkoutRemarks" class="form-label">Checkout Remarks</label>
                            <textarea class="form-control form-textarea" id="checkoutRemarks" placeholder="Add remarks about the visit, meeting outcomes, or any feedback..." rows="4"></textarea>
                            <small class="text-muted">Optional: Add notes about the visitor's experience or meeting outcomes.</small>
                        </div>
                        
                        <div class="mb-4">
                            <label for="checkoutFeedback" class="form-label">Visitor Feedback</label>
                            <select class="form-select" id="checkoutFeedback">
                                <option value="" selected>Select Feedback (Optional)</option>
                                <option value="excellent">Excellent - Very satisfied</option>
                                <option value="good">Good - Satisfied</option>
                                <option value="average">Average - Neutral</option>
                                <option value="poor">Poor - Needs improvement</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label required">Checkout Time</label>
                            <div class="time-input-container">
                                <input type="date" class="form-control" id="checkoutDate" required>
                                <input type="time" class="form-control" id="checkoutTime" required>
                            </div>
                            <small class="text-muted">Set the checkout date and time (default is current time)</small>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Important:</strong> Once checked out, the visitor will be marked as "Checked-out" in the system and cannot be modified.
                        </div>
                        
                        <div class="action-buttons">
                            <button type="button" class="btn btn-success" id="completeCheckoutBtn">
                                <i class="fas fa-check-circle me-2"></i>Complete Checkout
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="cancelCheckoutBtn">
                                <i class="fas fa-times me-2"></i>Cancel
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Checkout Success Message -->
                <div id="checkoutSuccess" class="alert alert-success mt-4" style="display: none;">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Visitor checked out successfully!</strong>
                    <p class="mb-0 mt-2" id="checkoutSuccessDetails"></p>
                    <div class="mt-3">
                        <button class="btn btn-outline-success" id="newCheckoutBtn">
                            <i class="fas fa-plus me-2"></i>Checkout Another Visitor
                        </button>
                        <button class="btn btn-outline-primary ms-2" id="openFrontDeskAfterCheckoutBtn">
                            <i class="fas fa-concierge-bell me-2"></i>Return to Front Desk
                        </button>
                    </div>
                </div>
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
            
            // Initialize if no data exists
            function initializeSampleData() {
                if (allVisitors.length === 0) {
                    console.log('No visitor data found in checkout system.');
                    alert('No visitor data found. Please use the Front Desk System first.');
                }
            }
            
            // Call initialization
            initializeSampleData();
            
            // Check if there's a visitor code passed from front desk
            const passedVisitorCode = sessionStorage.getItem('checkoutVisitorCode');
            if (passedVisitorCode) {
                document.getElementById('checkoutCodeInput').value = passedVisitorCode;
                fetchVisitorDetails();
                // Clear the session storage
                sessionStorage.removeItem('checkoutVisitorCode');
            }
            
            // Event Listeners
            document.getElementById('checkoutFetchBtn').addEventListener('click', fetchVisitorDetails);
            
            // Enter key support for visitor code input
            document.getElementById('checkoutCodeInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    fetchVisitorDetails();
                }
            });
            
            // Test buttons
            document.getElementById('showCheckoutCodesBtn').addEventListener('click', function() {
                const codes = allVisitors.map(v => v.code).join(', ');
                const checkedInVisitors = allVisitors.filter(v => v.status === 'Checked-in');
                alert(`Available Visitor Codes:\n\n${codes}\n\nTotal Visitors: ${allVisitors.length}\nChecked-in Visitors: ${checkedInVisitors.length}`);
            });
            
            document.getElementById('openFrontDeskBtn').addEventListener('click', function() {
                // Open Front Desk System in new tab
                const frontDeskURL = '/institute/admin/visitor/front-desk';
                window.open(frontDeskURL, '_blank');
            });
            
            document.getElementById('openAttendanceSystemBtn').addEventListener('click', function() {
                // Open Employee Attendance System in new tab
                const attendanceURL = '/institute/admin/visitor/attendant';
                window.open(attendanceURL, '_blank');
            });
            
            // Checkout Page Actions
            document.getElementById('completeCheckoutBtn').addEventListener('click', completeCheckout);
            document.getElementById('cancelCheckoutBtn').addEventListener('click', resetCheckoutForm);
            document.getElementById('newCheckoutBtn').addEventListener('click', resetCheckoutForm);
            document.getElementById('openFrontDeskAfterCheckoutBtn').addEventListener('click', function() {
                // Open Front Desk System in new tab
                const frontDeskURL = '/institute/admin/visitor/front-desk';
                window.open(frontDeskURL, '_blank');
            });
            
            // Functions
            async function fetchVisitorDetails() {
                const codeInput = document.getElementById('checkoutCodeInput').value.trim().toUpperCase();
                
                if (!codeInput) {
                    alert('Please enter a visitor code');
                    return;
                }
                
                console.log('Checkout: Looking for visitor code:', codeInput);
                
                // Reload data from localStorage
                const storedData = await makeAjaxRequest('/visitors/fetch-by-code', 'POST', {
                    visitor_code: codeInput
                });
                console.log(storedData);
                currentVisitor = storedData.visitor;
                // if (storedData) {
                //     try {
                //         allVisitors = JSON.parse(storedData);
                //         console.log('Reloaded data. Total visitors:', allVisitors.length);
                //     } catch (e) {
                //         console.error('Error parsing data:', e);
                //         alert('Error loading visitor data. Please try again.');
                //         return;
                //     }
                // }
                
                // // Find visitor with matching code
                // const visitor = allVisitors.find(v => {
                //     if (v && v.code) {
                //         return v.code.toUpperCase() === codeInput;
                //     }
                //     return false;
                // });
                
                if (!currentVisitor) {
                    const availableCodes = allVisitors.map(v => v.code).join(', ');
                    alert(`❌ Visitor with code "${codeInput}" was not found.\n\nAvailable codes: ${availableCodes || 'None'}\n\nTry these sample codes: VR-A1B2C3, VR-X9Y8Z7, VR-M5N6O7`);
                    return;
                }
                
                // Check if visitor is already checked out
                if (currentVisitor.status === 'Checked-out') {
                    alert(`⚠️ Visitor "${visitor.name}" has already been checked out.\nCheckout time: ${visitor.checkoutTime ? new Date(visitor.checkoutTime).toLocaleString() : 'Unknown'}`);
                    return;
                }
                
                // currentVisitor = visitor;
                
                // Display visitor code
                document.getElementById('checkoutVisitorCodeDisplay').textContent = currentVisitor.out_pass_id
                
                // Display registration type with badge
                const registrationType = currentVisitor.registrationType || 'Unknown';
                const badgeClass = registrationType === 'Walk-in' ? 'badge-walkin' : 'badge-self';
                const badgeText = registrationType === 'Walk-in' ? 'Walk-in Visitor' : 'Self Registration';
                
                document.getElementById('checkoutRegistrationTypeBadge').innerHTML = `
                    <span class="check-in-badge ${badgeClass}">
                        <i class="fas ${registrationType === 'Walk-in' ? 'fa-walking' : 'fa-mobile-alt'} me-1"></i>
                        ${badgeText}
                    </span>
                `;
                
                // Display visitor details
                document.getElementById('checkoutDetailName').textContent = currentVisitor.name || 'Not provided';
                document.getElementById('checkoutDetailContact').textContent = currentVisitor.contact || 'Not provided';
                document.getElementById('checkoutDetailEmail').textContent = currentVisitor.email || 'Not provided';
                document.getElementById('checkoutDetailPurpose').textContent = currentVisitor.purpose || 'Not provided';
                document.getElementById('checkoutDetailRegistrationType').textContent = registrationType;
                
                // Format vehicle info
                const vehicleInfo = `${currentVisitor.vehicleType || 'N/A'} - ${currentVisitor.vehicleNumber || 'N/A'} (${currentVisitor.vehicleColor || 'N/A'})`;
                document.getElementById('checkoutDetailVehicle').textContent = vehicleInfo;
                
                // Format check-in time
                const checkinTime = currentVisitor.registrationTime ? 
                    new Date(currentVisitor.registrationTime).toLocaleString() : 'Not recorded';
                document.getElementById('checkoutDetailCheckinTime').textContent = checkinTime;
                
                // Display status
                document.getElementById('checkoutDetailStatus').textContent = currentVisitor.status || 'Unknown';
                if (currentVisitor.status === 'Checked-out') {
                    document.getElementById('checkoutDetailStatus').className = 'check-in-badge badge-checkedout';
                } else {
                    document.getElementById('checkoutDetailStatus').className = 'check-in-badge badge-checkedin';
                }

                // Display visitor photo if available
                if (currentVisitor.visitorPhoto) {
                    document.getElementById('checkoutDetailPhoto').src = currentVisitor.visitorPhoto;
                    document.getElementById('checkoutDetailPhoto').style.display = 'block';
                    document.getElementById('checkoutNoPhotoMessage').style.display = 'none';
                } else {
                    document.getElementById('checkoutDetailPhoto').style.display = 'none';
                    document.getElementById('checkoutNoPhotoMessage').style.display = 'block';
                }
                
                // Display vehicle photos if available
                const vehiclePhotosContainer = document.getElementById('checkoutDetailVehiclePhotos');
                const noVehiclePhotosMessage = document.getElementById('checkoutNoVehiclePhotosMessage');
                
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
                
                // Load meeting information
                loadMeetingInfo();
                
                // Load meeting history
                loadMeetingHistory();
                
                // Set default checkout time to now
                const now = new Date();
                document.getElementById('checkoutDate').value = now.toISOString().split('T')[0];
                document.getElementById('checkoutTime').value = now.toTimeString().substring(0, 5);
                
                // Show visitor details section
                document.getElementById('checkoutVisitorDetails').style.display = 'block';
                
                // Scroll to details
                document.getElementById('checkoutVisitorDetails').scrollIntoView({ behavior: 'smooth' });
                
                console.log('✅ Checkout visitor details loaded:', currentVisitor.name);
            }
            
            function loadMeetingInfo() {
                if (!currentVisitor || !currentVisitor.frontDeskActions) {
                    document.getElementById('checkoutMeetingInfo').style.display = 'none';
                    return;
                }
                
                const meetings = currentVisitor.frontDeskActions.filter(action => 
                    action.type === 'assigned_to_employee' || 
                    action.type === 'employee_attendance_confirmed'
                );
                
                if (meetings.length === 0) {
                    document.getElementById('checkoutMeetingInfo').style.display = 'none';
                    return;
                }
                
                // Get the latest meeting
                const latestMeeting = meetings[meetings.length - 1];
                const meetingContainer = document.getElementById('meetingInfoContainer');
                meetingContainer.innerHTML = '';
                
                // Check if meeting was completed
                const completedMeeting = meetings.find(m => m.type === 'employee_attendance_confirmed' && m.attendance === 'completed');
                const cancelledMeeting = meetings.find(m => m.type === 'employee_attendance_confirmed' && m.attendance === 'cancelled');
                
                let statusText = 'Pending';
                let statusClass = 'badge-pending';
                
                if (completedMeeting) {
                    statusText = 'Completed';
                    statusClass = 'badge-completed';
                } else if (cancelledMeeting) {
                    statusText = 'Cancelled';
                    statusClass = 'badge-cancelled';
                }
                
                const meetingCard = document.createElement('div');
                meetingCard.className = 'meeting-history-card';
                meetingCard.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">
                                <i class="fas fa-calendar-alt me-2"></i>${latestMeeting.meetingTitle || 'Meeting'}
                                <span class="meeting-status-badge ${statusClass}">${statusText}</span>
                            </h6>
                            <p class="text-muted mb-1">
                                <i class="fas fa-user-tie me-1"></i>
                                ${latestMeeting.employee?.name || 'Unknown Employee'} - ${latestMeeting.employee?.position || ''}
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-clock me-1"></i>
                                ${latestMeeting.meetingDate} at ${latestMeeting.meetingTime} • ${latestMeeting.duration || 'N/A'} mins
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                ${latestMeeting.location || 'Not specified'}
                            </p>
                            ${latestMeeting.notes ? `
                                <div class="mt-2 p-2 bg-light rounded">
                                    <small>
                                        <i class="fas fa-sticky-note me-1"></i>
                                        <strong>Notes:</strong> ${latestMeeting.notes}
                                    </small>
                                </div>
                            ` : ''}
                            ${completedMeeting ? `
                                <div class="mt-2 p-2 bg-success bg-opacity-10 rounded">
                                    <small>
                                        <i class="fas fa-check-circle me-1"></i>
                                        <strong>Meeting Completed</strong><br>
                                        <i class="fas fa-clock me-1"></i>
                                        <strong>Time:</strong> ${new Date(completedMeeting.timestamp).toLocaleString()}
                                        ${completedMeeting.notes ? `<br><i class="fas fa-sticky-note me-1"></i><strong>Completion Notes:</strong> ${completedMeeting.notes}` : ''}
                                    </small>
                                </div>
                            ` : ''}
                            ${cancelledMeeting ? `
                                <div class="mt-2 p-2 bg-danger bg-opacity-10 rounded">
                                    <small>
                                        <i class="fas fa-times-circle me-1"></i>
                                        <strong>Meeting Cancelled</strong><br>
                                        <i class="fas fa-clock me-1"></i>
                                        <strong>Time:</strong> ${new Date(cancelledMeeting.timestamp).toLocaleString()}
                                        ${cancelledMeeting.notes ? `<br><i class="fas fa-sticky-note me-1"></i><strong>Cancellation Notes:</strong> ${cancelledMeeting.notes}` : ''}
                                    </small>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                `;
                
                meetingContainer.appendChild(meetingCard);
                document.getElementById('checkoutMeetingInfo').style.display = 'block';
            }
            
            function loadMeetingHistory() {
                if (!currentVisitor || !currentVisitor.frontDeskActions) {
                    document.getElementById('checkoutMeetingHistorySection').style.display = 'none';
                    return;
                }
                
                const meetings = currentVisitor.frontDeskActions.filter(action => 
                    action.type === 'assigned_to_employee'
                );
                
                if (meetings.length <= 1) {
                    document.getElementById('checkoutMeetingHistorySection').style.display = 'none';
                    return;
                }
                
                const historyContainer = document.getElementById('checkoutMeetingHistoryContainer');
                historyContainer.innerHTML = '';
                
                // Display all meetings except the latest one (already shown in meeting info)
                meetings.slice(0, -1).forEach((meeting, index) => {
                    const meetingCard = document.createElement('div');
                    meetingCard.className = 'meeting-history-card';
                    meetingCard.innerHTML = `
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">
                                    <i class="fas fa-calendar-alt me-2"></i>${meeting.meetingTitle || 'Meeting'}
                                    <span class="meeting-status-badge badge-completed">Completed</span>
                                </h6>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-user-tie me-1"></i>
                                    ${meeting.employee?.name || 'Unknown Employee'} - ${meeting.employee?.position || ''}
                                </p>
                                <p class="mb-1">
                                    <i class="fas fa-clock me-1"></i>
                                    ${meeting.meetingDate} at ${meeting.meetingTime} • ${meeting.duration || 'N/A'} mins
                                </p>
                                <p class="mb-0">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    ${meeting.location || 'Not specified'}
                                </p>
                                ${meeting.notes ? `
                                    <div class="mt-2 p-2 bg-light rounded">
                                        <small>
                                            <i class="fas fa-sticky-note me-1"></i>
                                            <strong>Notes:</strong> ${meeting.notes}
                                        </small>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                    
                    historyContainer.appendChild(meetingCard);
                });
                
                document.getElementById('checkoutMeetingHistorySection').style.display = 'block';
            }
            
            async function completeCheckout() {
                if (!currentVisitor) {
                    alert('No visitor selected');
                    return;
                }
                
                // Get checkout details
                const checkoutDate = document.getElementById('checkoutDate').value;
                const checkoutTime = document.getElementById('checkoutTime').value;
                const checkoutRemarks = document.getElementById('checkoutRemarks').value;
                const checkoutFeedback = document.getElementById('checkoutFeedback').value;
                
                if (!checkoutDate || !checkoutTime) {
                    alert('Please enter checkout date and time');
                    return;
                }
                
                if (confirm(`Complete checkout for ${currentVisitor.name}? This will mark the visitor as checked out.`)) {
                    // Update visitor status
                    currentVisitor.status = 'Checked-out';
                    currentVisitor.checkoutTime = new Date(`${checkoutDate}T${checkoutTime}`).toISOString();
                    
                    // Add front desk action record
                    if (!currentVisitor.frontDeskActions) {
                        currentVisitor.frontDeskActions = [];
                    }
                    
                    currentVisitor.frontDeskActions.push({
                        type: 'checked_out',
                        timestamp: new Date().toISOString(),
                        checkoutDate: checkoutDate,
                        checkoutTime: checkoutTime,
                        remarks: checkoutRemarks,
                        feedback: checkoutFeedback,
                        checkoutProcessedBy: 'Checkout System'
                    });
                    console.log(currentVisitor);
                    const check_out = await makeAjaxRequest('/visitors/checkout', 'POST', {
                        visitor_code: currentVisitor.code,
                        out_pass_id: currentVisitor.out_pass_id,
                        data:currentVisitor,
                    });
                    // Update localStorage
                    updateVisitorInStorage();
                    
                    // Show success message
                    document.getElementById('checkoutVisitorDetails').style.display = 'none';
                    document.getElementById('checkoutSuccess').style.display = 'block';
                    document.getElementById('checkoutSuccessDetails').innerHTML = `
                        ✅ <strong>${currentVisitor.name}</strong> has been checked out successfully.<br><br>
                        <div class="visitor-code-display" style="font-size: 28px; margin: 15px 0;">${currentVisitor.code}</div>
                        <strong>Checkout Details:</strong><br>
                        👤 Name: ${currentVisitor.name}<br>
                        📞 Contact: ${currentVisitor.contact}<br>
                        📅 Purpose: ${currentVisitor.purpose}<br>
                        🕒 Check-out Time: <strong>${checkoutDate} ${checkoutTime}</strong><br>
                        ${checkoutRemarks ? `📝 Remarks: ${checkoutRemarks}<br>` : ''}
                        ${checkoutFeedback ? `⭐ Feedback: ${checkoutFeedback}<br>` : ''}
                        <div class="mt-3 alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Visitor status updated to <strong>"Checked-out"</strong> in the system.
                        </div>
                    `;
                    
                    console.log(`✅ Visitor checked out: ${currentVisitor.name}`);
                }
            }
            
            function updateVisitorInStorage() {
                const index = allVisitors.findIndex(v => v.code === currentVisitor.code);
                if (index !== -1) {
                    allVisitors[index] = currentVisitor;
                    localStorage.setItem('visitorRegistrationData', JSON.stringify(allVisitors));
                    console.log('✅ Visitor updated in storage:', currentVisitor.name);
                }
            }
            
            function resetCheckoutForm() {
                document.getElementById('checkoutCodeInput').value = '';
                document.getElementById('checkoutVisitorDetails').style.display = 'none';
                document.getElementById('checkoutSuccess').style.display = 'none';
                document.getElementById('checkoutRemarks').value = '';
                document.getElementById('checkoutFeedback').value = '';
                
                // Reset checkout time to current time
                const now = new Date();
                document.getElementById('checkoutDate').value = now.toISOString().split('T')[0];
                document.getElementById('checkoutTime').value = now.toTimeString().substring(0, 5);
                
                currentVisitor = null;
                
                // Focus on code input
                document.getElementById('checkoutCodeInput').focus();
            }
            
            // Initialize the form
            console.log('Checkout System initialized');
            console.log('Shared storage with visitor check-in system');
            console.log('Available visitor codes:', allVisitors.map(v => v.code));
        });
    </script>
@endsection