@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <title>Gate Pass Generator</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- QR Code Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <!-- CSRF Token Meta Tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-color: #4bb543;
            --warning-color: #ff9e00;
            --danger-color: #e63946;
            --info-color: #00b4d8;
            --gradient-primary: linear-gradient(135deg, #4361ee, #3a0ca3);
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --radius: 12px;
        }
        
        .search-container {
            margin: 0 auto 30px;
        }
        
        .search-card {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .search-header {
            background: var(--gradient-primary);
            color: white;
            border-radius: var(--radius);
            padding: 25px;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .search-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .gate-pass-container {
            max-width: 800px;
            margin: 30px auto;
            display: none;
        }
        
        .gate-pass-header {
            background: var(--gradient-primary);
            color: white;
            border-radius: var(--radius);
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            text-align: center;
        }
        
        .gate-pass-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .visitor-code-large {
            font-size: 48px;
            font-weight: 800;
            color: var(--primary-color);
            text-align: center;
            margin: 20px 0;
            letter-spacing: 3px;
        }
        
        .qr-code-container {
            text-align: center;
            margin: 30px 0;
        }
        
        .qr-code-placeholder {
            width: 200px;
            height: 200px;
            background: #f0f2f5;
            border-radius: 10px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #dee2e6;
            overflow: hidden;
        }
        
        .visitor-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin: 25px 0;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #495057;
            min-width: 150px;
        }
        
        .detail-value {
            color: #212529;
            text-align: right;
            flex: 1;
        }
        
        .badge-status {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .loading-spinner {
            display: none;
            text-align: center;
            padding: 20px;
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #4361ee;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .cursor-pointer {
            cursor: pointer;
        }
        
        .test-code-badge {
            transition: all 0.3s ease;
        }
        
        .test-code-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .toast {
            min-width: 300px;
            box-shadow: var(--shadow);
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .search-container {
                display: none;
            }
            
            .no-print {
                display: none;
            }
            
            .gate-pass-container {
                display: block !important;
                margin: 0;
                max-width: none;
            }
        }
    </style>

    <!-- Toast Container -->
    <div class="toast-container"></div>
    
    <!-- Watermark -->
    <div class="watermark" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 100px; font-weight: 800; color: rgba(67, 97, 238, 0.05); pointer-events: none; z-index: -1;">
        GATE PASS
    </div>
    
    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="loading-spinner">
        <div class="spinner"></div>
        <p>Generating Gate Pass...</p>
    </div>
    
    <!-- Search Container -->
    <div class="search-container" id="searchContainer">
        <div class="search-header">
            <h1 class="search-title">GATE PASS GENERATOR</h1>
            <p>Search visitor by code to generate gate pass</p>
        </div>
        
        <div class="search-card">
            <div class="mb-4">
                <h5><i class="fas fa-search me-2"></i>Search Visitor</h5>
                <p class="text-muted">Enter visitor code to fetch details and generate gate pass</p>
            </div>
            
            <div class="mb-4">
                <label for="visitorCodeSearch" class="form-label">Visitor Code</label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-primary text-white">
                        <i class="fas fa-id-card"></i>
                    </span>
                    <input type="text" class="form-control" id="visitorCodeSearch" 
                           placeholder="Enter VR-XXXXXX code (e.g., VR-A1B2C3)" 
                           autocomplete="off" autofocus>
                    <button class="btn btn-primary" type="button" id="searchVisitorBtn">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
                <div class="form-text">Enter the visitor code from check-in system</div>
            </div>
            
            <!-- Test Codes Section -->
            <!-- <div class="alert alert-info" id="testCodesContainer">
                <h6><i class="fas fa-vial me-2"></i>Available Test Codes</h6>
                <p class="mb-2">Use these codes for testing:</p>
                <div id="testCodesList" class="d-flex flex-wrap gap-2">
                    <div class="text-center">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="ms-2">Loading test codes...</span>
                    </div>
                </div>
            </div> -->
            
            <!-- Search Results -->
            <div id="searchResults" style="display: none;">
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Visitor found!</strong> Click below to generate gate pass.
                </div>
                
                <div class="visitor-details mt-3" id="visitorPreview">
                    <!-- Visitor details will be populated here -->
                </div>
                
                <div class="text-center mt-4">
                    <button class="btn btn-primary btn-lg" id="generateGatePassBtn">
                        <i class="fas fa-id-card-alt me-2"></i>Generate Gate Pass
                    </button>
                    <button class="btn btn-outline-secondary" id="newSearchBtn">
                        <i class="fas fa-redo me-2"></i>New Search
                    </button>
                </div>
            </div>
            
            <!-- Error Message -->
            <div id="searchError" class="alert alert-danger mt-3" style="display: none;">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <span id="errorMessage">Visitor not found</span>
            </div>
            
            <!-- Recent Visitors -->
            <div class="mt-4" id="recentVisitorsContainer" style="display: none;">
                <h6><i class="fas fa-history me-2"></i>Recent Visitors</h6>
                <div id="recentVisitorsList" class="row mt-2">
                    <!-- Recent visitors will be loaded here -->
                </div>
            </div>
        </div>
    </div>
    
    <!-- Gate Pass Container (Hidden initially) -->
    <div class="gate-pass-container" id="gatePassDisplay">
        <!-- Gate Pass Header -->
        <div class="gate-pass-header">
            <h1 class="gate-pass-title">OFFICIAL GATE PASS</h1>
            <p>Valid for single entry/exit | Please present at security gate</p>
        </div>
        
        <!-- Gate Pass Body -->
        <div class="search-card">
            <!-- Visitor Code -->
            <div class="visitor-code-large" id="gatePassCode">
                VR-000000
            </div>
            
            <!-- QR Code -->
            <div class="qr-code-container">
                <div class="qr-code-placeholder" id="qrCodeContainer">
                    <i class="fas fa-qrcode fa-3x text-muted"></i>
                </div>
                <p class="text-muted">Scan QR code at security gate</p>
            </div>
            
            <!-- Visitor Details -->
            <div class="visitor-details">
                <h5 class="text-center mb-4"><i class="fas fa-user-check me-2"></i>Visitor Information</h5>
                
                <div class="detail-row">
                    <span class="detail-label">Visitor Name:</span>
                    <span class="detail-value" id="visitorName">Not Provided</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Contact Number:</span>
                    <span class="detail-value" id="visitorContact">Not Provided</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Email Address:</span>
                    <span class="detail-value" id="visitorEmail">Not Provided</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Purpose of Visit:</span>
                    <span class="detail-value" id="visitorPurpose">Not Provided</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Vehicle Type:</span>
                    <span class="detail-value" id="vehicleType">Not Provided</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Vehicle Number:</span>
                    <span class="detail-value" id="vehicleNumber">Not Provided</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Check-in Time:</span>
                    <span class="detail-value" id="checkinTime">Not Provided</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Pass Generated:</span>
                    <span class="detail-value" id="passGeneratedTime">Not Provided</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        <span class="badge-success badge-status" id="visitorStatus">Active</span>
                    </span>
                </div>
            </div>
            
            <!-- Security Instructions -->
            <div class="alert alert-warning mt-4">
                <h6><i class="fas fa-shield-alt me-2"></i>Security Instructions</h6>
                <ul class="mb-0">
                    <li>This pass is valid for single entry/exit only</li>
                    <li>Visitor must present ID proof along with this gate pass</li>
                    <li>All visitors must check out before leaving premises</li>
                    <li>Gate pass expires at the end of the day</li>
                </ul>
            </div>
            
            <!-- Action Buttons -->
            <div class="button-container mt-4 text-center no-print">
                <button class="btn btn-primary" onclick="printGatePass()">
                    <i class="fas fa-print me-2"></i>Print Gate Pass
                </button>
                <button class="btn btn-success" id="sendEmailBtn">
                    <i class="fas fa-envelope me-2"></i>Send via Email
                </button>
                <button class="btn btn-outline-primary" id="newGatePassBtn">
                    <i class="fas fa-plus me-2"></i>Generate Another
                </button>
                <button class="btn btn-outline-secondary" onclick="goToFrontDesk()">
                    <i class="fas fa-arrow-left me-2"></i>Back to Front Desk
                </button>
            </div>
        </div>
    </div>

    <script>
        // CSRF Token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Global variables
        let currentVisitor = null;
        let qrCodeInstance = null;
        
        // Initialize on page load
        $(document).ready(function() {
            console.log('Gate Pass Page Initialized');
            
            // Load test codes
            // loadTestCodes();
            
            // Load recent visitors
            // loadRecentVisitors();
            
            // Setup event listeners
            setupEventListeners();
            
            // Check URL for code parameter
            const urlParams = new URLSearchParams(window.location.search);
            const visitorCode = urlParams.get('code');
            if (visitorCode) {
                $('#visitorCodeSearch').val(visitorCode);
                setTimeout(() => {
                    searchVisitor();
                }, 500);
            }
        });
        
        // Setup event listeners
        function setupEventListeners() {
            // // Search button click
            // $('#searchVisitorBtn').click(searchVisitor);
            
            // // Enter key in search input
            // $('#visitorCodeSearch').keypress(function(e) {
            //     if (e.key === 'Enter') {
            //         e.preventDefault();
            //         searchVisitor();
            //     }
            // });
            
            // Generate Gate Pass button
            $('#generateGatePassBtn').click(generateGatePass);
            
            // New Search button
            $('#newSearchBtn').click(resetSearch);
            
            // New Gate Pass button
            $('#newGatePassBtn').click(resetToSearch);
            
            // Send Email button
            $('#sendEmailBtn').click(sendGatePassEmail);
        }
        
        $(document).ready(function () {
            $('#searchVisitorBtn').on('click', function (e) {
                e.preventDefault();
                searchVisitor();
            });
        });
                
        // Load recent visitors
        // function loadRecentVisitors() {
        //     const codeInput = $('#visitorCodeSearch').val().trim().toUpperCase();
        //     const csrfToken = $('meta[name="csrf-token"]').attr('content');
        //     $.ajax({
        //         url: '/visitor/fetch-by-code',
        //         type: 'POST',
        //         data: {
        //             visitor_code: codeInput,
        //             _token: csrfToken
        //         },
        //         dataType: 'json',
        //         success: function(response) {
        //             console.log(response);
        //             if (response.success && response.data.length > 0) {
        //                 displayRecentVisitors(response.data);
        //             }
        //         },
        //         error: function(xhr) {
        //             console.error('Error loading recent visitors:', xhr.responseText);
        //         }
        //     });
        // }
        
        // Display recent visitors
        // function displayRecentVisitors(visitors) {
        //     const container = $('#recentVisitorsList');
        //     container.empty();
            
        //     visitors.forEach(visitor => {
        //         const card = $(`
        //             <div class="col-md-6 mb-3">
        //                 <div class="card cursor-pointer" style="cursor: pointer;">
        //                     <div class="card-body">
        //                         <h6 class="card-title mb-1">${visitor.name}</h6>
        //                         <p class="card-text mb-1">
        //                             <small class="text-muted">
        //                                 <i class="fas fa-id-card me-1"></i>
        //                                 ${visitor.code}
        //                             </small>
        //                         </p>
        //                         <p class="card-text mb-1">
        //                             <small>
        //                                 <i class="fas fa-clock me-1"></i>
        //                                 ${visitor.checkin_time}
        //                             </small>
        //                         </p>
        //                     </div>
        //                 </div>
        //             </div>
        //         `);
                
        //         card.click(function() {
        //             $('#visitorCodeSearch').val(visitor.code);
        //             searchVisitor();
        //         });
                
        //         container.append(card);
        //     });
            
        //     $('#recentVisitorsContainer').show();
        // }
        
        // Search for visitor
        function searchVisitor() {
            const codeInput = $('#visitorCodeSearch').val().trim().toUpperCase();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (!codeInput) {
                showError('Please enter a visitor code');
                return;
            }
            
            // Hide previous results/errors
            hideAllMessages();
            
            // Show loading state on search button
            const searchBtn = $('#searchVisitorBtn');
            const originalText = searchBtn.html();
            searchBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Searching...');
            searchBtn.prop('disabled', true);
            
            // AJAX call to search visitor
            $.ajax({
                url: '/visitors/fetch-by-code',
                type: 'POST',
                data: {
                    visitor_code: codeInput,
                    _token: csrfToken
                },
                dataType: 'json',
                success: function(response) {
                    // Reset button
                    searchBtn.html(originalText);
                    searchBtn.prop('disabled', false);
                    console.log(response);
                    if (response.success) {
                        currentVisitor = response.visitor;
                        displayVisitorPreview();
                    } else {
                        showError(response.message || 'Visitor not found');
                    }
                },
                error: function(xhr) {
                    // Reset button
                    searchBtn.html(originalText);
                    searchBtn.prop('disabled', false);
                    
                    let errorMessage = 'Error searching for visitor';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showError(errorMessage);
                }
            });
        }
        
        // Display visitor preview
        function displayVisitorPreview() {
            if (!currentVisitor) return;
            
            const previewContainer = $('#visitorPreview');
            const statusBadge = getStatusBadge(currentVisitor.status);
            
            previewContainer.html(`
                <h6>Visitor Details Preview:</h6>
                <div class="detail-row">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">${currentVisitor.name || 'Not Provided'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Contact:</span>
                    <span class="detail-value">${currentVisitor.contact || 'Not Provided'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Purpose:</span>
                    <span class="detail-value">${currentVisitor.purpose || 'Not Provided'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Check-in Time:</span>
                    <span class="detail-value">${currentVisitor.registration_time || 'Not Provided'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">${statusBadge}</span>
                </div>
            `);
            
            $('#searchResults').show();
            
            // Scroll to results
            $('#searchResults')[0].scrollIntoView({ behavior: 'smooth' });
        }
        
        // Get status badge HTML
        function getStatusBadge(status) {
            let badgeClass = 'badge-warning';
            let badgeText = status || 'Unknown';
            
            switch(status?.toLowerCase()) {
                case 'checked-in':
                case 'active':
                    badgeClass = 'badge-success';
                    break;
                case 'checked-out':
                case 'completed':
                    badgeClass = 'badge-danger';
                    break;
                case 'pending':
                    badgeClass = 'badge-warning';
                    break;
            }
            
            return `<span class="badge-status ${badgeClass}">${badgeText}</span>`;
        }
        
        // Generate gate pass
        function generateGatePass() {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (!currentVisitor) {
                showError('No visitor selected. Please search for a visitor first.');
                return;
            }
            
            // Show loading spinner
            $('#loadingSpinner').show();
            
            // AJAX call to generate gate pass
            $.ajax({
                url: '/visitors/generate-out-pass',
                type: 'POST',
                data: {
                    visitor_id: currentVisitor.id,
                    visitor_code: currentVisitor.code,
                    _token: csrfToken,
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Update current visitor with gate pass data
                        currentVisitor.gate_pass_data = response.data.gate_pass_data;
                        
                        // Display gate pass
                        displayGatePass();
                    } else {
                        showError(response.message || 'Error generating gate pass');
                    }
                    $('#loadingSpinner').hide();
                },
                error: function(xhr) {
                    $('#loadingSpinner').hide();
                    let errorMessage = 'Error generating gate pass';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showError(errorMessage);
                }
            });
        }
        
        // Display gate pass
        function displayGatePass() {
            // Update visitor details in gate pass display
            const now = new Date();
            const dateStr = now.toLocaleDateString('en-GB');
            const timeStr = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            $('#gatePassCode').text(currentVisitor.code);
            $('#visitorName').text(currentVisitor.name || 'Not Provided');
            $('#visitorContact').text(currentVisitor.contact_number || 'Not Provided');
            $('#visitorEmail').text(currentVisitor.email || 'Not Provided');
            $('#visitorPurpose').text(currentVisitor.purpose || 'Not Provided');
            $('#vehicleType').text(currentVisitor.vehicle_type || 'Not Provided');
            $('#vehicleNumber').text(currentVisitor.vehicle_number || 'Not Provided');
            $('#checkinTime').text(currentVisitor.checkin_time || 'Not Provided');
            $('#passGeneratedTime').text(`${dateStr} ${timeStr}`);
            
            // Update status badge
            const statusBadge = getStatusBadge(currentVisitor.status);
            $('#visitorStatus').replaceWith(statusBadge);
            
            // Generate QR code
            generateQRCode(currentVisitor.code);
            
            // Hide search container and show gate pass
            $('#searchContainer').hide();
            $('#gatePassDisplay').show();
            
            // Scroll to top
            window.scrollTo(0, 0);
            
            // Show success toast
            showToast('Gate Pass Generated', `Gate pass generated successfully for ${currentVisitor.name}`, 'success');
            
            // Refresh recent visitors
            loadRecentVisitors();
        }
        
        // Generate QR code
        function generateQRCode(code) {
            const qrContainer = $('#qrCodeContainer');
            qrContainer.empty();
            
            // Clear previous QR code
            if (qrCodeInstance) {
                qrCodeInstance.clear();
            }
            
            // Create QR code
            qrCodeInstance = new QRCode(qrContainer[0], {
                text: JSON.stringify({
                    code: code,
                    timestamp: new Date().toISOString(),
                    type: 'gate_pass'
                }),
                width: 180,
                height: 180,
                colorDark: "#4361ee",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        }
        
        // Send gate pass via email
        function sendGatePassEmail() {
            if (!currentVisitor) return;
            
            // Disable button and show loading
            const emailBtn = $('#sendEmailBtn');
            const originalText = emailBtn.html();
            emailBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Sending...');
            emailBtn.prop('disabled', true);
            
            // AJAX call to send email
            $.ajax({
                url: '',
                type: 'POST',
                data: {
                    visitor_id: currentVisitor.id,
                    email: currentVisitor.email
                },
                dataType: 'json',
                success: function(response) {
                    emailBtn.html(originalText);
                    emailBtn.prop('disabled', false);
                    
                    if (response.success) {
                        showToast('Email Sent', 'Gate pass sent successfully via email', 'success');
                    } else {
                        showToast('Error', response.message || 'Failed to send email', 'danger');
                    }
                },
                error: function(xhr) {
                    emailBtn.html(originalText);
                    emailBtn.prop('disabled', false);
                    
                    let errorMessage = 'Error sending email';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showToast('Error', errorMessage, 'danger');
                }
            });
        }
        
        // Reset search
        function resetSearch() {
            hideAllMessages();
            $('#visitorCodeSearch').val('');
            $('#visitorCodeSearch').focus();
            currentVisitor = null;
        }
        
        // Reset to search view
        function resetToSearch() {
            $('#gatePassDisplay').hide();
            $('#searchContainer').show();
            resetSearch();
        }
        
        // Show error message
        function showError(message) {
            $('#errorMessage').text(message);
            $('#searchError').show();
            $('#searchResults').hide();
        }
        
        // Hide all messages
        function hideAllMessages() {
            $('#searchError').hide();
            $('#searchResults').hide();
        }
        
        // Show toast notification
        function showToast(title, message, type = 'info') {
            const toastId = 'toast-' + Date.now();
            const toastHtml = `
                <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header bg-${type} text-white">
                        <strong class="me-auto">${title}</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">
                        ${message}
                    </div>
                </div>
            `;
            
            $('.toast-container').append(toastHtml);
            const toast = new bootstrap.Toast(document.getElementById(toastId));
            toast.show();
            
            // Remove toast after it hides
            $(`#${toastId}`).on('hidden.bs.toast', function () {
                $(this).remove();
            });
        }
        
        // Print gate pass
        function printGatePass() {
            window.print();
        }
        
        // Go to front desk
        function goToFrontDesk() {
            window.location.href = '';
        }
    </script>
@endsection