@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <title>Out Pass System</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jsPDF Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --approved-color: #2ecc71;
            --pending-color: #f39c12;
            --rejected-color: #e74c3c;
            --out-pass-bg: #f8f9ff;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .header h1 {
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .header p {
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-3px);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 15px 20px;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 1.5px solid #e0e0e0;
            padding: 10px 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .status-approved {
            background-color: rgba(46, 204, 113, 0.15);
            color: var(--approved-color);
        }
        
        .status-pending {
            background-color: rgba(243, 156, 18, 0.15);
            color: var(--pending-color);
        }
        
        .status-rejected {
            background-color: rgba(231, 76, 60, 0.15);
            color: var(--rejected-color);
        }
        
        .out-pass-container {
            border: 2px solid var(--primary-color);
            border-radius: 12px;
            padding: 25px;
            background-color: var(--out-pass-bg);
            min-height: 400px;
            position: relative;
            overflow: hidden;
        }
        
        .out-pass-container::before {
            content: "OUT PASS";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 4rem;
            font-weight: 900;
            color: rgba(67, 97, 238, 0.05);
            z-index: 0;
            white-space: nowrap;
        }
        
        .out-pass-content {
            position: relative;
            z-index: 1;
        }
        
        .out-pass-header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(67, 97, 238, 0.3);
        }
        
        .out-pass-title {
            color: var(--secondary-color);
            font-weight: 800;
            font-size: 1.8rem;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
        
        .out-pass-subtitle {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1rem;
        }
        
        .out-pass-details {
            margin-bottom: 25px;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ddd;
        }
        
        .detail-label {
            flex: 0 0 40%;
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .detail-value {
            flex: 0 0 60%;
            font-weight: 500;
        }
        
        .out-pass-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid rgba(67, 97, 238, 0.3);
        }
        
        .signature-area {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .signature-box {
            text-align: center;
            width: 45%;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
            font-size: 0.9rem;
        }
        
        .pass-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 25px;
        }
        
        /*.table th {*/
        /*    color: var(--secondary-color);*/
        /*    font-weight: 600;*/
        /*    border-top: none;*/
        /*}*/
        
        .table td {
            vertical-align: middle;
        }
        
        .action-buttons .btn {
            margin-right: 5px;
            margin-bottom: 5px;
        }
        
        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 20px;
        }
        
        .nav-tabs .nav-link {
            color: var(--secondary-color);
            font-weight: 600;
            border-radius: 8px 8px 0 0;
            border: 1px solid transparent;
            padding: 12px 25px;
            margin-right: 5px;
        }
        
        .nav-tabs .nav-link.active {
            color: white;
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .nav-tabs .nav-link:not(.active):hover {
            border-color: var(--primary-color);
        }
        
        .tab-content {
            padding: 10px 0;
        }
        
        .stats-row {
            margin-bottom: 20px;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .passenger-item {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid var(--primary-color);
        }
        
        .passenger-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .remove-passenger {
            color: #dc3545;
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
        }
        
        .auto-code-display {
            background-color: #eef2ff;
            border-radius: 8px;
            padding: 12px;
            margin: 10px;
            border-left: 4px solid var(--primary-color);
        }
        
        .code-label {
            font-weight: 600;
            color: var(--secondary-color);
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        
        .generated-code {
            font-family: monospace;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            letter-spacing: 1px;
        }
        
        .qr-container {
            text-align: center;
            margin: 20px 0;
        }
        
        .qr-placeholder {
            width: 150px;
            height: 150px;
            background: linear-gradient(45deg, #f0f2ff, #e6e9ff);
            border-radius: 10px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px dashed var(--primary-color);
        }
        
        .qr-placeholder i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .pdf-options {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            border-left: 4px solid var(--secondary-color);
        }
        
        .pdf-option-label {
            font-weight: 600;
            color: var(--secondary-color);
            font-size: 0.9rem;
            margin-bottom: 8px;
        }
        
        .pdf-checkboxes {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .form-check {
            margin-bottom: 5px;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            
            .out-pass-container {
                border: 2px solid #000;
            }
            
            .out-pass-container::before {
                color: rgba(0, 0, 0, 0.05);
            }
        }
        
        @media (max-width: 768px) {
            .out-pass-container {
                padding: 15px;
            }
            
            .out-pass-title {
                font-size: 1.5rem;
            }
            
            .signature-area {
                flex-direction: column;
            }
            
            .signature-box {
                width: 100%;
                margin-bottom: 20px;
            }
            
            .pass-actions {
                flex-wrap: wrap;
            }
            
            .pdf-checkboxes {
                flex-direction: column;
                gap: 10px;
            }
        }

        .id-fetch-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid var(--primary-color);
        }

        .accompany-section {
            background-color: #f0f7ff;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            border: 1px solid #cfe2ff;
        }

        .accompany-section h6 {
            color: var(--primary-color);
            border-bottom: 2px solid #cfe2ff;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .fetch-btn {
            margin-top: 8px;
        }
        
        .sample-data-btn {
            margin-top: 10px;
            background-color: #6c757d;
            border-color: #6c757d;
        }
        
        .sample-data-btn:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        
        .accompany-item {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid var(--secondary-color);
        }
        
        .accompany-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .remove-accompany {
            color: #dc3545;
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
        }
        
        .accompany-controls {
            margin-top: 15px;
        }

        .optional-fields-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            border: 1px solid #dee2e6;
        }
        
        .optional-fields-section h6 {
            color: var(--secondary-color);
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .btn-group button{
            color: white !important ;
        }
        
        /* ERP Table Styles */
        .erp-table {
            width: 100%;
            background: #fff;
            border-collapse: collapse;
        }
        
        .erp-table thead {
            background: var(--primary-gradient);
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .erp-table th {
            padding: 15px 8px;
            font-weight: 600;
            color: white;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            cursor: pointer;
            user-select: none;
            transition: background-color 0.2s;
            position: relative;
        }

        .erp-table th h6 {
            color: rgba(255,255,255,0.9);
            font-size: 11px;
            margin: 2px 0 0;
        }

        .erp-table th:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .erp-table th.sortable {
            padding-right: 30px;
        }

        .sort-icons {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sort-icon {
            color: rgba(255,255,255,0.5);
            font-size: 12px;
            line-height: 1;
        }

        .sort-icon.active {
            color: white;
        }

        .erp-table td {
            padding: 15px 8px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 14px;
            vertical-align: middle;
        }

        .erp-table tbody tr {
            transition: background-color 0.2s, transform 0.2s;
        }

        .erp-table tbody tr:hover {
            background-color: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .erp-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .table-responsive{
            overflow-x: auto;
        }
    </style>

    <div class="container-fluid">
    <!-- Header -->
    <div class="header">
        <h1><i class="fas fa-door-open me-2"></i>Out Pass System</h1>
        <p>Generate and manage exit passes for employees, students, vehicles and visitors</p>
    </div>

        <!-- Quick Stats Row -->
        <div class="row stats-row">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-number" id="totalPasses">0</div>
                    <div class="stat-label">Total Passes</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-number" id="approvedPasses">0</div>
                    <div class="stat-label">Approved</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-number" id="pendingPasses">0</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card">
                    <div class="stat-number" id="todayCount">0</div>
                    <div class="stat-label">Today's Requests</div>
                </div>
            </div>
        </div>
        
        <!-- Sample Data Button -->
        <div class="row mb-3 no-print d-none">
            <div class="col-12">
                <div class="alert alert-info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-database me-2"></i>
                            <strong>Demo Data Loaded:</strong> The system contains sample out pass records for demonstration.
                        </div>
                        <button class="btn btn-sm btn-primary" id="loadSampleData">
                            <i class="fas fa-redo me-1"></i> Reload Sample Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs no-print" id="outPassTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="generate-tab" data-bs-toggle="tab" data-bs-target="#generate" type="button">
                    <i class="fas fa-plus-circle me-2"></i>Generate Out Pass
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="requests-tab" data-bs-toggle="tab" data-bs-target="#requests" type="button">
                    <i class="fas fa-list-alt me-2"></i>All Requests
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button">
                    <i class="fas fa-history me-2"></i>History
                </button>
            </li>
        </ul>
        
        <!-- Tab Content -->
        <div class="tab-content" id="outPassTabContent">
            <!-- Generate Tab -->
            <div class="tab-pane fade show active" id="generate" role="tabpanel">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-file-alt me-2"></i> Out Pass Request Form
                            </div>
                            <div class="card-body">
                                <form id="outPassForm" novalidate>
                                    <div class="mb-3">
                                        <label for="passType" class="form-label">Pass Type</label>
                                        <select class="form-select" id="passType" required>
                                            <option value="">Select Type</option>
                                            <option value="student">Student Out Pass</option>
                                            <option value="employee">Employee Out Pass</option>
                                            <option value="vehicle">Vehicle Out Pass</option>
                                            <option value="visitor">Visitor Out Pass</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Auto Generated Code Display -->
                                    <div class="auto-code-display" id="autoCodeDisplay" style="display: none;">
                                        <div class="code-label">Auto Generated Pass Code:</div>
                                        <div class="generated-code" id="generatedCode">OUT-XXXX-XXXX</div>
                                        <small class="text-muted">This code will be automatically assigned to your out pass</small>
                                    </div>
                                    
                                    <!-- Student Fields -->
                                    <div id="studentFields" style="display: none;">
                                        <div class="id-fetch-section">
                                            <h5 class="mb-3" style="color: var(--secondary-color);">
                                                <i class="fas fa-graduation-cap me-2"></i>Student Details
                                            </h5>
                                            <div class="mb-3">
                                                <label for="studentId" class="form-label">Student ID *</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="studentId" name="student_id" placeholder="Enter Student ID">
                                                    <button type="button" class="btn btn-primary fetch-btn" id="fetchStudentBtn">
                                                        <i class="fas fa-search me-1"></i> Fetch Details
                                                    </button>
                                                </div>
                                                <small class="text-muted">Try: STU001, STU002, STU003, STU004, STU005</small>
                                            </div>
                                        </div>
                                        
                                        <div class="student-details-section" id="studentDetailsSection" style="display: none;">
                                            <div class="mb-3">
                                                <label for="studentName" class="form-label">Full Name</label>
                                                <input type="text" class="form-control" id="studentName" name="full_name" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="studentContact" class="form-label">Contact Number</label>
                                                <input type="tel" class="form-control" id="studentContact" name="contact_number" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="studentAddress" class="form-label">Address</label>
                                                <textarea class="form-control" id="studentAddress" rows="2" name="address" readonly></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="studentClass" class="form-label">Class</label>
                                                    <input type="text" class="form-control" id="studentClass" name="class" readonly>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="studentSection" class="form-label">Section</label>
                                                    <input type="text" class="form-control" id="studentSection" name="section" readonly>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="studentRollNo" class="form-label">Roll Number</label>
                                                <input type="text" class="form-control" id="studentRollNo" name="roll_number" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="studentEmail" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="studentEmail" name="email" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="studentParentName" class="form-label">Parent Name</label>
                                                <input type="text" class="form-control" id="studentParentName" name="parent_name" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="studentParentContact" class="form-label">Parent Contact</label>
                                                <input type="tel" class="form-control" id="studentParentContact" name="parent_contact" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="studentParentEmail" class="form-label">Parent Email (Optional)</label>
                                                <input type="email" class="form-control" id="studentParentEmail" name="parent_email" placeholder="Enter parent email">
                                            </div>
                                        </div>

                                        <!-- Optional Student Fields -->
                                        <div class="optional-fields-section" id="studentOptionalFields" style="display: none;">
                                            <h6><i class="fas fa-address-card me-2"></i>Additional Information (Optional)</h6>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="studentDOB" class="form-label">Date of Birth</label>
                                                    <input type="date" class="form-control" name="date_of_birth" id="studentDOB">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="studentBloodGroup" class="form-label">Blood Group</label>
                                                    <select class="form-select" id="studentBloodGroup" name="blood_group">
                                                        <option value="">Select Blood Group</option>
                                                        <option value="A+">A+</option>
                                                        <option value="A-">A-</option>
                                                        <option value="B+">B+</option>
                                                        <option value="B-">B-</option>
                                                        <option value="O+">O+</option>
                                                        <option value="O-">O-</option>
                                                        <option value="AB+">AB+</option>
                                                        <option value="AB-">AB-</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="studentMedicalConditions" class="form-label">Medical Conditions</label>
                                                <textarea class="form-control" id="studentMedicalConditions" rows="2" name="medical_conditions" placeholder="Any medical conditions to note"></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="is_hosteler" type="checkbox" id="studentIsHosteler">
                                                        <label class="form-check-label" for="studentIsHosteler">
                                                            Is Hosteler
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="hostelFields" style="display: none;">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="studentHostelName" class="form-label">Hostel Name</label>
                                                        <input type="text" class="form-control" id="studentHostelName" name="hostel_name" placeholder="Enter hostel name">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="studentRoomNumber" class="form-label">Room Number</label>
                                                        <input type="text" name="room_number" class="form-control" id="studentRoomNumber" placeholder="Enter room number">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Accompany By Section for Student -->
                                        <div class="accompany-section" id="studentAccompanySection" style="display: none;">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0"><i class="fas fa-user-friends me-2"></i>Accompany By (Optional)</h6>
                                                <button type="button" class="btn btn-sm btn-outline-primary" id="addStudentAccompany">
                                                    <i class="fas fa-plus me-1"></i> Add Person
                                                </button>
                                            </div>
                                            <div id="studentAccompanyContainer">
                                                <!-- Accompany items will be added here dynamically -->
                                            </div>
                                            <div class="form-check mt-3">
                                                <input class="form-check-input" type="checkbox" id="studentNoAccompany">
                                                <label class="form-check-label" for="studentNoAccompany">
                                                    No one accompanying
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Employee Fields -->
                                    <div id="employeeFields" style="display: none;">
                                        <div class="id-fetch-section">
                                            <h5 class="mb-3" style="color: var(--secondary-color);">
                                                <i class="fas fa-briefcase me-2"></i>Employee Details
                                            </h5>
                                            <div class="mb-3">
                                                <label for="employeeIdInput" class="form-label">Employee ID *</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="employeeIdInput" name="employee_id" placeholder="Enter Employee ID">
                                                    <button type="button" class="btn btn-primary fetch-btn" id="fetchEmployeeBtn">
                                                        <i class="fas fa-search me-1"></i> Fetch Details
                                                    </button>
                                                </div>
                                                <small class="text-muted">Try: EMP001, EMP002, EMP003, EMP004, EMP005</small>
                                            </div>
                                        </div>
                                        
                                        <div class="employee-details-section" id="employeeDetailsSection" style="display: none;">
                                            <div class="mb-3">
                                                <label for="employeeName" class="form-label">Employee Name</label>
                                                <input type="text" class="form-control" id="employeeName" name="full_name" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="employeeContact" class="form-label">Contact Number</label>
                                                <input type="tel" class="form-control" id="employeeContact" name="contact_number" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="employeeAddress" class="form-label">Address</label>
                                                <textarea class="form-control" id="employeeAddress" name="address" rows="2" readonly></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="employeeDepartment" class="form-label">Department</label>
                                                <input type="text" class="form-control" id="employeeDepartment" name="department" placeholder="Department" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="employeeDesignation" class="form-label">Designation</label>
                                                <input type="text" class="form-control" id="employeeDesignation" name="designation" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="employeeEmail" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="employeeEmail" name="email" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="employeeManager" class="form-label">Reporting Manager</label>
                                                <input type="text" class="form-control" id="employeeManager" name="reporting_manager" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="employeeEmergencyContact" class="form-label">Emergency Contact</label>
                                                <input type="tel" class="form-control" id="employeeEmergencyContact" name="emergency_contact" readonly>
                                            </div>
                                        </div>
                                        
                                        <!-- Accompany By Section for Employee -->
                                        <div class="accompany-section" id="employeeAccompanySection" style="display: none;">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0"><i class="fas fa-user-friends me-2"></i>Accompany By (Optional)</h6>
                                                <button type="button" class="btn btn-sm btn-outline-primary" id="addEmployeeAccompany">
                                                    <i class="fas fa-plus me-1"></i> Add Person
                                                </button>
                                            </div>
                                            <div id="employeeAccompanyContainer">
                                                <!-- Accompany items will be added here dynamically -->
                                            </div>
                                            <div class="form-check mt-3">
                                                <input class="form-check-input" type="checkbox" id="employeeNoAccompany">
                                                <label class="form-check-label" for="employeeNoAccompany">
                                                    No one accompanying
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Vehicle Fields -->
                                    <div id="vehicleFields" style="display: none;">
                                        <h5 class="mb-3" style="color: var(--secondary-color); border-bottom: 2px solid var(--primary-color); padding-bottom: 8px;">
                                            <i class="fas fa-car me-2"></i>Vehicle Details
                                        </h5>
                                        <div class="mb-3">
                                            <label for="vehicleNumber" class="form-label">Vehicle Number *</label>
                                            <input type="text" class="form-control" id="vehicleNumber" placeholder="Enter vehicle registration number" name="vehicle_number">
                                        </div>
                                        <div class="mb-3">
                                            <label for="vehicleType" class="form-label">Vehicle Type *</label>
                                            <input type="text" class="form-control" id="vehicleType" name="vehicle_type" placeholder="Enter Vehicle Type (e.g., Car, Bus, Truck)">
                                        </div>
                                        <div class="mb-3">
                                            <label for="vehicleModel" class="form-label">Vehicle Model</label>
                                            <input type="text" class="form-control" id="vehicleModel" name="model" placeholder="Enter vehicle model">
                                        </div>
                                        <h6 class="mb-3" style="color: var(--primary-color);">
                                            <i class="fas fa-user me-2"></i>Driver Details
                                        </h6>
                                        <div class="mb-3">
                                            <label for="driverName" class="form-label">Driver Name *</label>
                                            <input type="text" class="form-control" id="driverName" placeholder="Enter driver name" name="driver_name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="driverContact" class="form-label">Driver Contact Number *</label>
                                            <input type="tel" class="form-control" id="driverContact" placeholder="Enter driver contact number" name="driver_contact">
                                        </div>
                                        <div class="mb-3">
                                            <label for="driverAddress" class="form-label">Driver Address *</label>
                                            <textarea class="form-control" id="driverAddress" rows="2" placeholder="Enter driver address" name="driver_address"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="driverLicense" class="form-label">Driver License Number</label>
                                            <input type="text" class="form-control" id="driverLicense" name="driver_license" placeholder="Enter driver license number">
                                        </div>
                                        <h6 class="mb-3" style="color: var(--primary-color);">
                                            <i class="fas fa-users me-2"></i>Passenger Details
                                        </h6>
                                        <div id="passengersContainer">
                                            <div class="passenger-item" id="passengerTemplate" style="display: none;">
                                                <div class="passenger-header">
                                                    <h6 class="mb-0">Passenger <span class="passenger-number">1</span></h6>
                                                    <button type="button" class="remove-passenger">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <input type="text" class="form-control passenger-name" placeholder="Passenger Name *">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <input type="text" class="form-control passenger-phone" placeholder="Phone Number *">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 mb-2">
                                                        <input type="text" class="form-control passenger-relation" placeholder="Relation *">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <button type="button" class="btn btn-outline-primary btn-sm" id="addPassenger">
                                                <i class="fas fa-plus me-1"></i> Add Passenger
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Visitor Fields -->
                                    <div id="visitorFields" style="display: none;">
                                        <h5 class="mb-3" style="color: var(--secondary-color); border-bottom: 2px solid var(--primary-color); padding-bottom: 8px;">
                                            <i class="fas fa-user-friends me-2"></i>Visitor Details
                                        </h5>
                                        <div class="mb-3">
                                            <label for="visitorName" class="form-label">Visitor Name *</label>
                                            <input type="text" class="form-control" id="visitorName" name="visitor_name" placeholder="Enter visitor name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="visitorContact" class="form-label">Contact Number *</label>
                                            <input type="tel" class="form-control" id="visitorContact" name="visitor_contact" placeholder="Enter contact number">
                                        </div>
                                        <div class="mb-3">
                                            <label for="visitorAddress" class="form-label">Address *</label>
                                            <textarea class="form-control" id="visitorAddress" rows="2" name="visitor_address" placeholder="Enter visitor address"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="visitorIdProof" class="form-label">ID Proof Details *</label>
                                            <input type="text" class="form-control" id="visitorIdProof" name="visitor_id_proof" placeholder="Enter ID proof details (Aadhar, DL, etc.)">
                                        </div>
                                        <div class="mb-3">
                                            <label for="visitorCompany" class="form-label">Company/Organization</label>
                                            <input type="text" class="form-control" id="visitorCompany" name="visitor_company" placeholder="Enter company/organization name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="personToMeet" class="form-label">Person to Meet *</label>
                                            <input type="text" class="form-control" id="personToMeet" name="person_to_meet" placeholder="Enter person name to meet">
                                        </div>
                                    </div>
                                    
                                    <!-- Common Fields for all passes -->
                                    <h5 class="mb-3" style="color: var(--secondary-color); border-bottom: 2px solid var(--primary-color); padding-bottom: 8px;">
                                        <i class="fas fa-calendar-alt me-2"></i>Exit Details
                                    </h5>
                                    <div class="mb-3">
                                        <label for="outDate" class="form-label">Out Date *</label>
                                        <input type="date" class="form-control" id="outDate" name="out_date">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="outTime" class="form-label">Out Time *</label>
                                        <input type="time" class="form-control" id="outTime" name="out_time">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="purpose" class="form-label">Purpose of Exit *</label>
                                        <textarea class="form-control" id="purpose" rows="3" name="purpose" placeholder="Enter purpose of going out"></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="destination" class="form-label">Destination *</label>
                                        <input type="text" class="form-control" id="destination" name="destination" placeholder="Where are you going?">
                                    </div>
                                    
                                    <!-- PDF Options -->
                                    <div class="pdf-options" id="pdfOptions" style="display: none;">
                                        <div class="pdf-option-label">PDF Generation Options:</div>
                                        <div class="pdf-checkboxes">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="includeSignature" checked>
                                                <label class="form-check-label" for="includeSignature">
                                                    Include Signature Lines
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="includeQR" checked>
                                                <label class="form-check-label" for="includeQR">
                                                    Include QR Code Placeholder
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="includeWatermark" checked>
                                                <label class="form-check-label" for="includeWatermark">
                                                    Include "OUT PASS" Watermark
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex gap-2 justify-content-between mt-3">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-paper-plane me-2"></i> Submit
                                        </button>
                                        <button type="button" class="d-none btn btn-success" id="generatePDF">
                                            <i class="fas fa-file-pdf me-2"></i> Generate PDF
                                        </button>
                                        <button type="reset" class="btn btn-outline-secondary">
                                            <i class="fas fa-redo me-2"></i> Reset
                                        </button>
                                        <button type="button" class="d-none btn btn-secondary sample-data-btn" id="loadSampleFormData">
                                            <i class="fas fa-magic me-2"></i> Load Sample Form Data
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-eye me-2"></i> Out Pass Preview
                            </div>
                            <div class="card-body">
                                <div id="passPreview" class="out-pass-container">
                                    <div class="out-pass-content">
                                        <div class="text-center py-5">
                                            <i class="fas fa-door-open fa-4x mb-3" style="color: #e0e0e0;"></i>
                                            <h4 style="color: #adb5bd;">Out Pass Preview</h4>
                                            <p class="text-muted">Fill out the form to see the preview</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="pass-actions no-print" id="passActions" style="display: none;">
                                    <button class="btn btn-success" id="approvePass">
                                        <i class="fas fa-check me-2"></i> Approve Pass
                                    </button>
                                    <button class="btn btn-warning" id="printPass">
                                        <i class="fas fa-print me-2"></i> Print Pass
                                    </button>
                                    <button class="btn btn-info" id="downloadPDF">
                                        <i class="fas fa-file-pdf me-2"></i> Download PDF
                                    </button>
                                    <button class="btn btn-danger" id="rejectPass">
                                        <i class="fas fa-times me-2"></i> Reject
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Requests Tab -->
            <div class="tab-pane fade" id="requests" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-list me-2"></i> All Out Pass Requests
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-outline-primary btn-sm active filter-btn" data-filter="all">All</button>
                                <button class="btn btn-outline-warning btn-sm filter-btn" data-filter="pending">Pending</button>
                                <button class="btn btn-outline-success btn-sm filter-btn" data-filter="approved">Approved</button>
                                <button class="btn btn-outline-danger btn-sm filter-btn" data-filter="rejected">Rejected</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                            <table class="erp-table table-hover" id="requestsTable">
                                <thead>
                                    <tr>
                                        <th class="sticky-main-2 sortable">Pass Code</th>
                                        <th class="sortable">Name</th>
                                        <th class="sortable">Type</th>
                                        <th class="sortable">Out Date/Time</th>
                                        <th class="sortable">Destination</th>
                                        <th class="sortable">Purpose</th>
                                        <th class="sortable">Status</th>
                                        <th class="sortable">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="requestsBody">
                                    <!-- Will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Floating Horizontal Scrollbar -->
                        <div class="d-none table-scroll-top" id="tableScrollTop">
                            <div class="table-scroll-inner"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- History Tab -->
            <div class="tab-pane fade" id="history" role="tabpanel">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchHistory" placeholder="Search by name, code, or purpose...">
                            <button class="btn btn-primary" type="button" id="searchButton">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="date" class="form-control" id="filterDate">
                            <button class="btn btn-outline-primary" type="button" id="filterButton">
                                <i class="fas fa-filter"></i> Filter by Date
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-history me-2"></i> Out Pass History
                    </div>
                    <div class="card-body">
                        <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                            <table class="erp-table table-hover" id="historyTable">
                                <thead>
                                    <tr>
                                        <th class="sticky-main-2 sortable">Pass Code</th>
                                        <th class="sortable">Name</th>
                                        <th class="sortable">Type</th>
                                        <th class="sortable">Out Date/Time</th>
                                        <th class="sortable">Destination</th>
                                        <th class="sortable">Purpose</th>
                                        <th class="sortable">Status</th>
                                        <th class="sortable">PDF</th>
                                    </tr>
                                </thead>
                                <tbody id="historyBody">
                                    <!-- Will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Floating Horizontal Scrollbar -->
                        <div class="d-none table-scroll-top" id="tableScrollTop">
                            <div class="table-scroll-inner"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize jsPDF
        const { jsPDF } = window.jspdf;
        
        // Initialize the application with sample data
        document.addEventListener('DOMContentLoaded', function() {
            // Set default date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('outDate').value = today;
            
            // Generate initial pass code
            updateAutoCode();
            
            // Initialize mock data and load sample data (for demo purposes)
            initializeMockData();
            loadSampleOutPasses();
            
            // Load initial data
            updateStats();
            loadAllRequests();
            loadHistory();
            
            // Set up event listeners
            setupEventListeners();
            
            console.log('Application initialized!');
        });

        // Initialize mock data in localStorage (for demo purposes)
        function initializeMockData() {
            // Clear existing data
            localStorage.removeItem('students');
            localStorage.removeItem('employees');
            
            // Create mock student database with column names matching migration
            const mockStudentDatabase = {
                'STU001': {
                    student_id: 'STU001',
                    full_name: 'John Doe',
                    contact_number: '9876543210',
                    address: '123 Main Street, City, State 12345',
                    class: '10th',
                    section: 'A',
                    roll_number: '101',
                    email: 'john.doe@school.edu',
                    parent_name: 'Robert Doe',
                    parent_contact: '9876543211',
                    parent_email: 'robert.doe@email.com',
                    date_of_birth: '2010-05-15',
                    blood_group: 'O+',
                    medical_conditions: 'None',
                    admission_year: '2024',
                    is_hosteler: false,
                    hostel_name: null,
                    room_number: null,
                    is_active: true
                },
                'STU002': {
                    student_id: 'STU002',
                    full_name: 'Jane Smith',
                    contact_number: '9876543220',
                    address: '456 Oak Avenue, City, State 12345',
                    class: '12th',
                    section: 'B',
                    roll_number: '205',
                    email: 'jane.smith@school.edu',
                    parent_name: 'Mary Smith',
                    parent_contact: '9876543221',
                    parent_email: 'mary.smith@email.com',
                    date_of_birth: '2008-08-22',
                    blood_group: 'A+',
                    medical_conditions: 'Asthma',
                    admission_year: '2023',
                    is_hosteler: true,
                    hostel_name: 'Girls Hostel',
                    room_number: '205',
                    is_active: true
                },
                'STU003': {
                    student_id: 'STU003',
                    full_name: 'Michael Johnson',
                    contact_number: '9876543230',
                    address: '789 Pine Road, City, State 12345',
                    class: 'UG',
                    section: 'C',
                    roll_number: '301',
                    email: 'michael.j@college.edu',
                    parent_name: 'David Johnson',
                    parent_contact: '9876543231',
                    parent_email: 'david.j@email.com',
                    date_of_birth: '2005-03-10',
                    blood_group: 'B+',
                    medical_conditions: 'None',
                    admission_year: '2023',
                    is_hosteler: false,
                    hostel_name: null,
                    room_number: null,
                    is_active: true
                },
                'STU004': {
                    student_id: 'STU004',
                    full_name: 'Emily Brown',
                    contact_number: '9876543240',
                    address: '321 Maple Drive, City, State 12345',
                    class: '11th',
                    section: 'D',
                    roll_number: '402',
                    email: 'emily.b@school.edu',
                    parent_name: 'Richard Brown',
                    parent_contact: '9876543241',
                    parent_email: 'richard.b@email.com',
                    date_of_birth: '2009-11-18',
                    blood_group: 'AB+',
                    medical_conditions: 'Allergies',
                    admission_year: '2024',
                    is_hosteler: false,
                    hostel_name: null,
                    room_number: null,
                    is_active: true
                },
                'STU005': {
                    student_id: 'STU005',
                    full_name: 'David Wilson',
                    contact_number: '9876543250',
                    address: '654 Elm Street, City, State 12345',
                    class: '9th',
                    section: 'E',
                    roll_number: '503',
                    email: 'david.w@school.edu',
                    parent_name: 'Susan Wilson',
                    parent_contact: '9876543251',
                    parent_email: 'susan.w@email.com',
                    date_of_birth: '2011-07-25',
                    blood_group: 'O-',
                    medical_conditions: 'None',
                    admission_year: '2024',
                    is_hosteler: false,
                    hostel_name: null,
                    room_number: null,
                    is_active: true
                }
            };

            // Create mock employee database
            const mockEmployeeDatabase = {
                'EMP001': {
                    name: 'Sarah Williams',
                    contact: '9876543310',
                    address: '321 Maple Drive, City, State 12345',
                    department: 'Human Resources',
                    designation: 'HR Manager',
                    employeeId: 'EMP001',
                    email: 'sarah.williams@company.com',
                    manager: 'David Brown',
                    emergencyContact: '9876543311'
                },
                'EMP002': {
                    name: 'Robert Chen',
                    contact: '9876543320',
                    address: '654 Birch Lane, City, State 12345',
                    department: 'Information Technology',
                    designation: 'Software Engineer',
                    employeeId: 'EMP002',
                    email: 'robert.chen@company.com',
                    manager: 'Lisa Wang',
                    emergencyContact: '9876543321'
                },
                'EMP003': {
                    name: 'Emily Davis',
                    contact: '9876543330',
                    address: '987 Cedar Street, City, State 12345',
                    department: 'Marketing',
                    designation: 'Marketing Manager',
                    employeeId: 'EMP003',
                    email: 'emily.davis@company.com',
                    manager: 'James Wilson',
                    emergencyContact: '9876543331'
                },
                'EMP004': {
                    name: 'Michael Thompson',
                    contact: '9876543340',
                    address: '147 Oak Avenue, City, State 12345',
                    department: 'Finance',
                    designation: 'Financial Analyst',
                    employeeId: 'EMP004',
                    email: 'michael.t@company.com',
                    manager: 'Jennifer Lee',
                    emergencyContact: '9876543341'
                },
                'EMP005': {
                    name: 'Lisa Martinez',
                    contact: '9876543350',
                    address: '258 Pine Road, City, State 12345',
                    department: 'Operations',
                    designation: 'Operations Manager',
                    employeeId: 'EMP005',
                    email: 'lisa.m@company.com',
                    manager: 'Kevin Scott',
                    emergencyContact: '9876543351'
                }
            };
            
            // Store in localStorage
            localStorage.setItem('students', JSON.stringify(mockStudentDatabase));
            localStorage.setItem('employees', JSON.stringify(mockEmployeeDatabase));
            
            console.log('Mock databases initialized successfully!');
        }

        // Load sample out passes (for demo purposes)
        function loadSampleOutPasses() {
            const existingPasses = JSON.parse(localStorage.getItem('outPasses')) || [];
            
            if (existingPasses.length === 0) {
                const sampleOutPasses = [
                    {
                        passCode: 'STU-250210-001-ABC',
                        type: 'student',
                        student_id: 'STU001',
                        full_name: 'John Doe',
                        contact_number: '9876543210',
                        address: '123 Main Street, City, State 12345',
                        class: '10th',
                        section: 'A',
                        roll_number: '101',
                        email: 'john.doe@school.edu',
                        parent_name: 'Robert Doe',
                        parent_contact: '9876543211',
                        parent_email: 'robert.doe@email.com',
                        admission_year: '2024',
                        date_of_birth: '2010-05-15',
                        blood_group: 'O+',
                        medical_conditions: 'None',
                        is_hosteler: false,
                        accompany: [{
                            name: 'Robert Doe',
                            mobile: '9876543211',
                            email: 'robert.doe@email.com',
                            relationship: 'Father'
                        }],
                        outDate: '2024-02-20',
                        outTime: '14:30',
                        purpose: 'Medical appointment',
                        destination: 'City Hospital',
                        status: 'approved',
                        submittedAt: '2024-02-20T10:15:30Z',
                        approvedBy: 'Admin',
                        approvedAt: '2024-02-20T11:00:00Z'
                    },
                    {
                        passCode: 'STU-250210-002-DEF',
                        type: 'student',
                        student_id: 'STU002',
                        full_name: 'Jane Smith',
                        contact_number: '9876543220',
                        address: '456 Oak Avenue, City, State 12345',
                        class: '12th',
                        section: 'B',
                        roll_number: '205',
                        email: 'jane.smith@school.edu',
                        parent_name: 'Mary Smith',
                        parent_contact: '9876543221',
                        parent_email: 'mary.smith@email.com',
                        admission_year: '2023',
                        date_of_birth: '2008-08-22',
                        blood_group: 'A+',
                        medical_conditions: 'Asthma',
                        is_hosteler: true,
                        hostel_name: 'Girls Hostel',
                        room_number: '205',
                        accompany: [{
                            name: 'Mary Smith',
                            mobile: '9876543221',
                            email: 'mary.smith@email.com',
                            relationship: 'Mother'
                        }],
                        outDate: '2024-02-21',
                        outTime: '16:00',
                        purpose: 'Family function',
                        destination: 'Grand Hotel',
                        status: 'pending',
                        submittedAt: '2024-02-21T09:20:45Z'
                    }
                ];
                
                localStorage.setItem('outPasses', JSON.stringify(sampleOutPasses));
                console.log('Sample out passes loaded successfully!');
            }
        }

        // Fetch student details from server
        function fetchStudentDetails(studentId) {
            // Show loading state
            const fetchBtn = document.getElementById('fetchStudentBtn');
            const originalText = fetchBtn.innerHTML;
            fetchBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Fetching...';
            fetchBtn.disabled = true;
            
            const formData = new FormData();
            formData.append('student_id', studentId);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            
            fetch('/students/fetch', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Map the response data to form fields using actual column names
                    document.getElementById('studentName').value = data.data.full_name || data.data.name || '';
                    document.getElementById('studentContact').value = data.data.contact_number || data.data.contact || '';
                    document.getElementById('studentAddress').value = data.data.address || '';
                    document.getElementById('studentClass').value = data.data.class || '';
                    document.getElementById('studentSection').value = data.data.section || '';
                    document.getElementById('studentRollNo').value = data.data.roll_number || data.data.rollNo || '';
                    document.getElementById('studentEmail').value = data.data.email || '';
                    document.getElementById('studentParentName').value = data.data.parent_name || data.data.parentName || '';
                    document.getElementById('studentParentContact').value = data.data.parent_contact || data.data.parentContact || '';
                    
                    // Optional fields
                    if (document.getElementById('studentParentEmail')) {
                        document.getElementById('studentParentEmail').value = data.data.parent_email || '';
                    }
                    if (document.getElementById('studentDOB')) {
                        document.getElementById('studentDOB').value = data.data.date_of_birth || '';
                    }
                    if (document.getElementById('studentBloodGroup')) {
                        document.getElementById('studentBloodGroup').value = data.data.blood_group || '';
                    }
                    if (document.getElementById('studentMedicalConditions')) {
                        document.getElementById('studentMedicalConditions').value = data.data.medical_conditions || '';
                    }
                    if (document.getElementById('studentIsHosteler')) {
                        document.getElementById('studentIsHosteler').checked = data.data.is_hosteler || false;
                        if (data.data.is_hosteler) {
                            document.getElementById('hostelFields').style.display = 'block';
                            document.getElementById('studentHostelName').value = data.data.hostel_name || '';
                            document.getElementById('studentRoomNumber').value = data.data.room_number || '';
                        }
                    }
                    
                    document.getElementById('studentDetailsSection').style.display = 'block';
                    document.getElementById('studentOptionalFields').style.display = 'block';
                    document.getElementById('studentAccompanySection').style.display = 'block';
                    
                    updatePassPreview();
                } else {
                    alert(data.message || `Student ID "${studentId}" not found!`);
                    document.getElementById('studentDetailsSection').style.display = 'none';
                    document.getElementById('studentOptionalFields').style.display = 'none';
                    document.getElementById('studentAccompanySection').style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Fallback to mock data for demo
                const students = JSON.parse(localStorage.getItem('students')) || {};
                const student = students[studentId];
                
                if (student) {
                    document.getElementById('studentName').value = student.full_name || student.name || '';
                    document.getElementById('studentContact').value = student.contact_number || student.contact || '';
                    document.getElementById('studentAddress').value = student.address || '';
                    document.getElementById('studentClass').value = student.class || '';
                    document.getElementById('studentSection').value = student.section || '';
                    document.getElementById('studentRollNo').value = student.roll_number || student.rollNo || '';
                    document.getElementById('studentEmail').value = student.email || '';
                    document.getElementById('studentParentName').value = student.parent_name || student.parentName || '';
                    document.getElementById('studentParentContact').value = student.parent_contact || student.parentContact || '';
                    
                    // Optional fields
                    if (document.getElementById('studentParentEmail')) {
                        document.getElementById('studentParentEmail').value = student.parent_email || '';
                    }
                    if (document.getElementById('studentDOB')) {
                        document.getElementById('studentDOB').value = student.date_of_birth || '';
                    }
                    if (document.getElementById('studentBloodGroup')) {
                        document.getElementById('studentBloodGroup').value = student.blood_group || '';
                    }
                    if (document.getElementById('studentMedicalConditions')) {
                        document.getElementById('studentMedicalConditions').value = student.medical_conditions || '';
                    }
                    if (document.getElementById('studentIsHosteler')) {
                        document.getElementById('studentIsHosteler').checked = student.is_hosteler || false;
                        if (student.is_hosteler) {
                            document.getElementById('hostelFields').style.display = 'block';
                            document.getElementById('studentHostelName').value = student.hostel_name || '';
                            document.getElementById('studentRoomNumber').value = student.room_number || '';
                        }
                    }
                    
                    document.getElementById('studentDetailsSection').style.display = 'block';
                    document.getElementById('studentOptionalFields').style.display = 'block';
                    document.getElementById('studentAccompanySection').style.display = 'block';
                    
                    updatePassPreview();
                } else {
                    alert(`Student ID "${studentId}" not found in demo data!`);
                    document.getElementById('studentDetailsSection').style.display = 'none';
                    document.getElementById('studentOptionalFields').style.display = 'none';
                    document.getElementById('studentAccompanySection').style.display = 'none';
                }
            })
            .finally(() => {
                fetchBtn.innerHTML = originalText;
                fetchBtn.disabled = false;
            });
        }

        // Fetch employee details from server
        function fetchEmployeeDetails(employeeId) {
            // Show loading state
            const fetchBtn = document.getElementById('fetchEmployeeBtn');
            const originalText = fetchBtn.innerHTML;
            fetchBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Fetching...';
            fetchBtn.disabled = true;
            
            const formData = new FormData();
            formData.append('employee_id', employeeId);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            
            fetch('/employees/out-pass/fetch', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }) 
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('employeeName').value = data.data.name || '';
                    document.getElementById('employeeContact').value = data.data.contact || '';
                    document.getElementById('employeeAddress').value = data.data.address || '';
                    document.getElementById('employeeDepartment').value = data.data.department || '';
                    document.getElementById('employeeDesignation').value = data.data.designation || '';
                    document.getElementById('employeeEmail').value = data.data.email || '';
                    document.getElementById('employeeManager').value = data.data.manager || '';
                    document.getElementById('employeeEmergencyContact').value = data.data.emergencyContact || '';
                    
                    document.getElementById('employeeDetailsSection').style.display = 'block';
                    document.getElementById('employeeAccompanySection').style.display = 'block';
                    
                    updatePassPreview();
                } else {
                    alert(data.message || `Employee ID "${employeeId}" not found!`);
                    document.getElementById('employeeDetailsSection').style.display = 'none';
                    document.getElementById('employeeAccompanySection').style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Fallback to mock data for demo
                const employees = JSON.parse(localStorage.getItem('employees')) || {};
                const employee = employees[employeeId];
                
                if (employee) {
                    document.getElementById('employeeName').value = employee.name || '';
                    document.getElementById('employeeContact').value = employee.contact || '';
                    document.getElementById('employeeAddress').value = employee.address || '';
                    document.getElementById('employeeDepartment').value = employee.department || '';
                    document.getElementById('employeeDesignation').value = employee.designation || '';
                    document.getElementById('employeeEmail').value = employee.email || '';
                    document.getElementById('employeeManager').value = employee.manager || '';
                    document.getElementById('employeeEmergencyContact').value = employee.emergencyContact || '';
                    
                    document.getElementById('employeeDetailsSection').style.display = 'block';
                    document.getElementById('employeeAccompanySection').style.display = 'block';
                    
                    updatePassPreview();
                } else {
                    alert(`Employee ID "${employeeId}" not found in demo data!`);
                    document.getElementById('employeeDetailsSection').style.display = 'none';
                    document.getElementById('employeeAccompanySection').style.display = 'none';
                }
            })
            .finally(() => {
                fetchBtn.innerHTML = originalText;
                fetchBtn.disabled = false;
            });
        }

        // Submit out pass request to server
        function submitOutPassRequest() {
            const passType = document.getElementById('passType').value;
            
            if (!passType) {
                alert('Please select a pass type');
                return;
            }
            
            // Temporarily remove required attributes from all fields to prevent validation errors
            removeRequiredAttributes();
            
            // Validate based on selected pass type and add required attributes only for visible fields
            let isValid = true;
            let errorMessage = '';
            
            if (passType === 'student') {
                const studentId = document.getElementById('studentId').value;
                if (!studentId) {
                    isValid = false;
                    errorMessage = 'Please enter Student ID';
                } else if (document.getElementById('studentDetailsSection').style.display === 'none') {
                    isValid = false;
                    errorMessage = 'Please fetch student details first';
                }
                
                // Add required attributes back for student fields
                if (isValid) {
                    document.getElementById('studentId').setAttribute('required', 'required');
                    document.getElementById('studentName').setAttribute('required', 'required');
                    document.getElementById('studentContact').setAttribute('required', 'required');
                    document.getElementById('studentAddress').setAttribute('required', 'required');
                    document.getElementById('studentClass').setAttribute('required', 'required');
                    document.getElementById('studentSection').setAttribute('required', 'required');
                    document.getElementById('studentRollNo').setAttribute('required', 'required');
                    document.getElementById('studentEmail').setAttribute('required', 'required');
                    document.getElementById('studentParentName').setAttribute('required', 'required');
                    document.getElementById('studentParentContact').setAttribute('required', 'required');
                }
                
            } else if (passType === 'employee') {
                const employeeId = document.getElementById('employeeIdInput').value;
                if (!employeeId) {
                    isValid = false;
                    errorMessage = 'Please enter Employee ID';
                } else if (document.getElementById('employeeDetailsSection').style.display === 'none') {
                    isValid = false;
                    errorMessage = 'Please fetch employee details first';
                }
                
                // Add required attributes back for employee fields
                if (isValid) {
                    document.getElementById('employeeIdInput').setAttribute('required', 'required');
                    document.getElementById('employeeName').setAttribute('required', 'required');
                    document.getElementById('employeeContact').setAttribute('required', 'required');
                    document.getElementById('employeeAddress').setAttribute('required', 'required');
                    document.getElementById('employeeDepartment').setAttribute('required', 'required');
                    document.getElementById('employeeDesignation').setAttribute('required', 'required');
                    document.getElementById('employeeEmail').setAttribute('required', 'required');
                    document.getElementById('employeeManager').setAttribute('required', 'required');
                    document.getElementById('employeeEmergencyContact').setAttribute('required', 'required');
                }
                
            } else if (passType === 'vehicle') {
                const vehicleNumber = document.getElementById('vehicleNumber').value;
                const driverName = document.getElementById('driverName').value;
                const driverContact = document.getElementById('driverContact').value;
                const driverAddress = document.getElementById('driverAddress').value;
                
                if (!vehicleNumber) {
                    isValid = false;
                    errorMessage = 'Please enter Vehicle Number';
                } else if (!driverName) {
                    isValid = false;
                    errorMessage = 'Please enter Driver Name';
                } else if (!driverContact) {
                    isValid = false;
                    errorMessage = 'Please enter Driver Contact Number';
                } else if (!driverAddress) {
                    isValid = false;
                    errorMessage = 'Please enter Driver Address';
                }
                
                // Add required attributes back for vehicle fields
                if (isValid) {
                    document.getElementById('vehicleNumber').setAttribute('required', 'required');
                    document.getElementById('vehicleType').setAttribute('required', 'required');
                    document.getElementById('driverName').setAttribute('required', 'required');
                    document.getElementById('driverContact').setAttribute('required', 'required');
                    document.getElementById('driverAddress').setAttribute('required', 'required');
                    
                    // Validate passengers if any
                    const passengers = document.querySelectorAll('#passengersContainer .passenger-item:not(#passengerTemplate)');
                    passengers.forEach((passenger, index) => {
                        const nameInput = passenger.querySelector('.passenger-name');
                        const phoneInput = passenger.querySelector('.passenger-phone');
                        const relationInput = passenger.querySelector('.passenger-relation');
                        
                        if (nameInput) nameInput.setAttribute('required', 'required');
                        if (phoneInput) phoneInput.setAttribute('required', 'required');
                        if (relationInput) relationInput.setAttribute('required', 'required');
                    });
                }
                
            } else if (passType === 'visitor') {
                const visitorName = document.getElementById('visitorName').value;
                const visitorContact = document.getElementById('visitorContact').value;
                const visitorAddress = document.getElementById('visitorAddress').value;
                const visitorIdProof = document.getElementById('visitorIdProof').value;
                const personToMeet = document.getElementById('personToMeet').value;
                
                if (!visitorName) {
                    isValid = false;
                    errorMessage = 'Please enter Visitor Name';
                } else if (!visitorContact) {
                    isValid = false;
                    errorMessage = 'Please enter Contact Number';
                } else if (!visitorAddress) {
                    isValid = false;
                    errorMessage = 'Please enter Address';
                } else if (!visitorIdProof) {
                    isValid = false;
                    errorMessage = 'Please enter ID Proof Details';
                } else if (!personToMeet) {
                    isValid = false;
                    errorMessage = 'Please enter Person to Meet';
                }
                
                // Add required attributes back for visitor fields
                if (isValid) {
                    document.getElementById('visitorName').setAttribute('required', 'required');
                    document.getElementById('visitorContact').setAttribute('required', 'required');
                    document.getElementById('visitorAddress').setAttribute('required', 'required');
                    document.getElementById('visitorIdProof').setAttribute('required', 'required');
                    document.getElementById('personToMeet').setAttribute('required', 'required');
                }
            }
            
            // Validate common fields
            const outDate = document.getElementById('outDate').value;
            const outTime = document.getElementById('outTime').value;
            const purpose = document.getElementById('purpose').value;
            const destination = document.getElementById('destination').value;
            
            if (!outDate) {
                isValid = false;
                errorMessage = 'Please select Out Date';
            } else if (!outTime) {
                isValid = false;
                errorMessage = 'Please select Out Time';
            } else if (!purpose) {
                isValid = false;
                errorMessage = 'Please enter Purpose of Exit';
            } else if (!destination) {
                isValid = false;
                errorMessage = 'Please enter Destination';
            }
            
            // Add required attributes for common fields
            if (isValid) {
                document.getElementById('outDate').setAttribute('required', 'required');
                document.getElementById('outTime').setAttribute('required', 'required');
                document.getElementById('purpose').setAttribute('required', 'required');
                document.getElementById('destination').setAttribute('required', 'required');
            }
            
            if (!isValid) {
                alert(errorMessage);
                return;
            }
            
            // Collect form data with actual column names
            const formData = new FormData();
            formData.append('pass_type', passType);
            formData.append('out_date', outDate);
            formData.append('out_time', outTime);
            formData.append('purpose', purpose);
            formData.append('destination', destination);
            
            // Add type-specific data with actual column names
            if (passType === 'student') {
                // Student personal details - matching migration column names
                formData.append('student_id', document.getElementById('studentId').value);
                formData.append('full_name', document.getElementById('studentName').value);
                formData.append('contact_number', document.getElementById('studentContact').value);
                formData.append('address', document.getElementById('studentAddress').value);
                formData.append('class', document.getElementById('studentClass').value);
                formData.append('section', document.getElementById('studentSection').value);
                formData.append('roll_number', document.getElementById('studentRollNo').value);
                formData.append('email', document.getElementById('studentEmail').value);
                formData.append('parent_name', document.getElementById('studentParentName').value);
                formData.append('parent_contact', document.getElementById('studentParentContact').value);
                
                // Additional fields from migration
                const currentYear = new Date().getFullYear().toString();
                formData.append('admission_year', currentYear);
                
                // Get institute_id, branch_id, user_id from session/meta if available
                formData.append('institute_id', document.querySelector('meta[name="institute-id"]')?.content || '');
                formData.append('branch_id', document.querySelector('meta[name="branch-id"]')?.content || '');
                formData.append('user_id', document.querySelector('meta[name="user-id"]')?.content || '');
                
                formData.append('is_active', '1'); // Default to active
                
                // Optional fields
                const parentEmail = document.getElementById('studentParentEmail')?.value || '';
                formData.append('parent_email', parentEmail);
                
                const dateOfBirth = document.getElementById('studentDOB')?.value || '';
                formData.append('date_of_birth', dateOfBirth);
                
                const bloodGroup = document.getElementById('studentBloodGroup')?.value || '';
                formData.append('blood_group', bloodGroup);
                
                const medicalConditions = document.getElementById('studentMedicalConditions')?.value || '';
                formData.append('medical_conditions', medicalConditions);
                
                const isHosteler = document.getElementById('studentIsHosteler')?.checked || false;
                formData.append('is_hosteler', isHosteler ? '1' : '0');
                
                if (isHosteler) {
                    const hostelName = document.getElementById('studentHostelName')?.value || '';
                    formData.append('hostel_name', hostelName);
                    
                    const roomNumber = document.getElementById('studentRoomNumber')?.value || '';
                    formData.append('room_number', roomNumber);
                } else {
                    formData.append('hostel_name', '');
                    formData.append('room_number', '');
                }
                
                // Add accompany data (store as JSON)
                const accompanyData = getStudentAccompanyData();
                formData.append('accompany_data', JSON.stringify(accompanyData));
                
            } else if (passType === 'employee') {
                formData.append('employee_id', document.getElementById('employeeIdInput').value);
                formData.append('full_name', document.getElementById('employeeName').value);
                formData.append('contact_number', document.getElementById('employeeContact').value);
                formData.append('address', document.getElementById('employeeAddress').value);
                formData.append('department', document.getElementById('employeeDepartment').value);
                formData.append('designation', document.getElementById('employeeDesignation').value);
                formData.append('email', document.getElementById('employeeEmail').value);
                formData.append('reporting_manager', document.getElementById('employeeManager').value);
                formData.append('emergency_contact', document.getElementById('employeeEmergencyContact').value);
                
                // Add accompany data
                const accompanyData = getEmployeeAccompanyData();
                formData.append('accompany_data', JSON.stringify(accompanyData));
                
            } else if (passType === 'vehicle') {
                formData.append('vehicle_number', document.getElementById('vehicleNumber').value);
                formData.append('vehicle_type', document.getElementById('vehicleType').value);
                formData.append('model', document.getElementById('vehicleModel').value);
                formData.append('driver_name', document.getElementById('driverName').value);
                formData.append('driver_contact', document.getElementById('driverContact').value);
                formData.append('driver_address', document.getElementById('driverAddress').value);
                formData.append('driver_license', document.getElementById('driverLicense').value);
                
                // Add passenger data
                const passengers = getPassengerData();
                formData.append('passenger_data', JSON.stringify(passengers));
                
            } else if (passType === 'visitor') {
                formData.append('visitor_name', document.getElementById('visitorName').value);
                formData.append('visitor_contact', document.getElementById('visitorContact').value);
                formData.append('visitor_address', document.getElementById('visitorAddress').value);
                formData.append('visitor_id_proof', document.getElementById('visitorIdProof').value);
                formData.append('visitor_company', document.getElementById('visitorCompany').value);
                formData.append('person_to_meet', document.getElementById('personToMeet').value);
            }
            
            // Add CSRF token
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            
            // Show loading state
            const submitBtn = document.querySelector('#outPassForm button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Submitting...';
            submitBtn.disabled = true;
            
            // Determine the correct endpoint based on pass type
            let endpoint = '';
            switch(passType) {
                case 'student':
                    endpoint = '/students/store';
                    break;
                case 'employee':
                    endpoint = '/employees/store';
                    break;
                case 'vehicle':
                    endpoint = '/vehicles/out-pass-vehicles';
                    break;
                case 'visitor':
                    endpoint = '/out-pass-visitors';
                    break;
                default:
                    endpoint = '/out-pass/submit';
            }
            
            // Send to server
            fetch(endpoint, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`${passType.charAt(0).toUpperCase() + passType.slice(1)} Out Pass created successfully!\nPass Code: ${data.data?.pass_code || document.getElementById('generatedCode').textContent}`);
                    
                    // Save to localStorage for demo/backup
                    const outPass = {
                        passCode: data.data?.pass_code || document.getElementById('generatedCode').textContent,
                        type: passType,
                        ...getFormDataByType(passType),
                        outDate: outDate,
                        outTime: outTime,
                        purpose: purpose,
                        destination: destination,
                        status: 'pending',
                        submittedAt: new Date().toISOString()
                    };
                    
                    const outPasses = JSON.parse(localStorage.getItem('outPasses')) || [];
                    outPasses.push(outPass);
                    localStorage.setItem('outPasses', JSON.stringify(outPasses));
                    
                    // Reset form
                    resetForm();
                    
                    // Update UI
                    updateStats();
                    loadAllRequests();
                    loadHistory();
                    
                    // Switch to requests tab
                    document.getElementById('requests-tab').click();
                } else {
                    alert('Error: ' + (data.message || 'Failed to create out pass'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Fallback to localStorage for demo
                const outPass = {
                    passCode: document.getElementById('generatedCode').textContent,
                    type: passType,
                    ...getFormDataByType(passType),
                    outDate: outDate,
                    outTime: outTime,
                    purpose: purpose,
                    destination: destination,
                    status: 'pending',
                    submittedAt: new Date().toISOString()
                };
                
                const outPasses = JSON.parse(localStorage.getItem('outPasses')) || [];
                outPasses.push(outPass);
                localStorage.setItem('outPasses', JSON.stringify(outPasses));
                
                alert(`${passType.charAt(0).toUpperCase() + passType.slice(1)} Out Pass created successfully (Demo Mode)!\nPass Code: ${outPass.passCode}`);
                
                // Reset form
                resetForm();
                
                // Update UI
                updateStats();
                loadAllRequests();
                loadHistory();
                
                // Switch to requests tab
                document.getElementById('requests-tab').click();
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }

        // Helper function to remove required attributes from all fields
        function removeRequiredAttributes() {
            const requiredFields = [
                'studentId', 'studentName', 'studentContact', 'studentAddress', 
                'studentClass', 'studentSection', 'studentRollNo', 'studentEmail',
                'studentParentName', 'studentParentContact',
                'employeeIdInput', 'employeeName', 'employeeContact', 'employeeAddress',
                'employeeDepartment', 'employeeDesignation', 'employeeEmail',
                'employeeManager', 'employeeEmergencyContact',
                'vehicleNumber', 'vehicleType', 'driverName', 'driverContact', 'driverAddress',
                'visitorName', 'visitorContact', 'visitorAddress', 'visitorIdProof', 'personToMeet',
                'outDate', 'outTime', 'purpose', 'destination'
            ];
            
            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.removeAttribute('required');
                }
            });
            
            // Remove required from passenger fields
            document.querySelectorAll('.passenger-name, .passenger-phone, .passenger-relation').forEach(field => {
                field.removeAttribute('required');
            });
        }

        // Helper function to get form data by type
        function getFormDataByType(passType) {
            const data = {};
            
            if (passType === 'student') {
                data.student_id = document.getElementById('studentId').value;
                data.full_name = document.getElementById('studentName').value;
                data.contact_number = document.getElementById('studentContact').value;
                data.address = document.getElementById('studentAddress').value;
                data.class = document.getElementById('studentClass').value;
                data.section = document.getElementById('studentSection').value;
                data.roll_number = document.getElementById('studentRollNo').value;
                data.email = document.getElementById('studentEmail').value;
                data.parent_name = document.getElementById('studentParentName').value;
                data.parent_contact = document.getElementById('studentParentContact').value;
                data.parent_email = document.getElementById('studentParentEmail')?.value || '';
                data.date_of_birth = document.getElementById('studentDOB')?.value || '';
                data.blood_group = document.getElementById('studentBloodGroup')?.value || '';
                data.medical_conditions = document.getElementById('studentMedicalConditions')?.value || '';
                data.is_hosteler = document.getElementById('studentIsHosteler')?.checked || false;
                data.hostel_name = document.getElementById('studentHostelName')?.value || '';
                data.room_number = document.getElementById('studentRoomNumber')?.value || '';
                data.accompany = getStudentAccompanyData();
                
            } else if (passType === 'employee') {
                data.employee_id = document.getElementById('employeeIdInput').value;
                data.full_name = document.getElementById('employeeName').value;
                data.contact_number = document.getElementById('employeeContact').value;
                data.address = document.getElementById('employeeAddress').value;
                data.department = document.getElementById('employeeDepartment').value;
                data.designation = document.getElementById('employeeDesignation').value;
                data.email = document.getElementById('employeeEmail').value;
                data.reporting_manager = document.getElementById('employeeManager').value;
                data.emergency_contact = document.getElementById('employeeEmergencyContact').value;
                data.accompany = getEmployeeAccompanyData();
                
            } else if (passType === 'vehicle') {
                data.vehicle_number = document.getElementById('vehicleNumber').value;
                data.vehicle_type = document.getElementById('vehicleType').value;
                data.model = document.getElementById('vehicleModel').value;
                data.driver_name = document.getElementById('driverName').value;
                data.driver_contact = document.getElementById('driverContact').value;
                data.driver_address = document.getElementById('driverAddress').value;
                data.driver_license = document.getElementById('driverLicense').value;
                data.passengers = getPassengerData();
                
            } else if (passType === 'visitor') {
                data.visitor_name = document.getElementById('visitorName').value;
                data.visitor_contact = document.getElementById('visitorContact').value;
                data.visitor_address = document.getElementById('visitorAddress').value;
                data.visitor_id_proof = document.getElementById('visitorIdProof').value;
                data.visitor_company = document.getElementById('visitorCompany').value;
                data.person_to_meet = document.getElementById('personToMeet').value;
            }
            
            return data;
        }

        // Helper function to reset form
        function resetForm() {
            document.getElementById('outPassForm').reset();
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('outDate').value = today;
            
            // Reset fields visibility
            document.getElementById('studentFields').style.display = 'none';
            document.getElementById('employeeFields').style.display = 'none';
            document.getElementById('vehicleFields').style.display = 'none';
            document.getElementById('visitorFields').style.display = 'none';
            document.getElementById('hostelFields').style.display = 'none';
            
            // Clear containers
            clearPassengers();
            clearStudentAccompany();
            clearEmployeeAccompany();
            
            // Update auto code
            updateAutoCode();
            
            // Update preview
            updatePassPreview();
            
            // Remove all required attributes
            removeRequiredAttributes();
        }

        // Add student accompany person
        function addStudentAccompany() {
            const container = document.getElementById('studentAccompanyContainer');
            const count = container.querySelectorAll('.accompany-item').length;
            
            const accompanyHTML = `
                <div class="accompany-item">
                    <div class="accompany-header">
                        <h6 class="mb-0">Accompany Person ${count + 1}</h6>
                        <button type="button" class="remove-accompany">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Name</label>
                            <input type="text" class="form-control accompany-name" placeholder="Enter name">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Mobile No.</label>
                            <input type="tel" class="form-control accompany-mobile" placeholder="Enter mobile number">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Email</label>
                            <input type="email" class="form-control accompany-email" placeholder="Enter email address">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Relationship</label>
                            <input type="text" class="form-control accompany-relationship" placeholder="e.g., Parent, Guardian">
                        </div>
                    </div>
                </div>
            `;
            
            const div = document.createElement('div');
            div.innerHTML = accompanyHTML;
            container.appendChild(div.firstElementChild);
            
            const removeBtn = container.querySelector('.accompany-item:last-child .remove-accompany');
            removeBtn.addEventListener('click', function() {
                this.closest('.accompany-item').remove();
                updatePassPreview();
            });
            
            const inputs = container.querySelectorAll('.accompany-item:last-child input');
            inputs.forEach(input => {
                input.addEventListener('input', updatePassPreview);
            });
            
            const noAccompany = document.getElementById('studentNoAccompany').checked;
            if (noAccompany) {
                inputs.forEach(input => {
                    input.disabled = true;
                });
            }
            
            updatePassPreview();
        }

        // Add employee accompany person
        function addEmployeeAccompany() {
            const container = document.getElementById('employeeAccompanyContainer');
            const count = container.querySelectorAll('.accompany-item').length;
            
            const accompanyHTML = `
                <div class="accompany-item">
                    <div class="accompany-header">
                        <h6 class="mb-0">Accompany Person ${count + 1}</h6>
                        <button type="button" class="remove-accompany">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Name</label>
                            <input type="text" class="form-control accompany-name" placeholder="Enter name">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Mobile No.</label>
                            <input type="tel" class="form-control accompany-mobile" placeholder="Enter mobile number">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Email</label>
                            <input type="email" class="form-control accompany-email" placeholder="Enter email address">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small">Relationship</label>
                            <input type="text" class="form-control accompany-relationship" placeholder="e.g., Spouse, Family">
                        </div>
                    </div>
                </div>
            `;
            
            const div = document.createElement('div');
            div.innerHTML = accompanyHTML;
            container.appendChild(div.firstElementChild);
            
            const removeBtn = container.querySelector('.accompany-item:last-child .remove-accompany');
            removeBtn.addEventListener('click', function() {
                this.closest('.accompany-item').remove();
                updatePassPreview();
            });
            
            const inputs = container.querySelectorAll('.accompany-item:last-child input');
            inputs.forEach(input => {
                input.addEventListener('input', updatePassPreview);
            });
            
            const noAccompany = document.getElementById('employeeNoAccompany').checked;
            if (noAccompany) {
                inputs.forEach(input => {
                    input.disabled = true;
                });
            }
            
            updatePassPreview();
        }

        // Clear student accompany
        function clearStudentAccompany() {
            document.getElementById('studentAccompanyContainer').innerHTML = '';
        }

        // Clear employee accompany
        function clearEmployeeAccompany() {
            document.getElementById('employeeAccompanyContainer').innerHTML = '';
        }

        // Get student accompany data
        function getStudentAccompanyData() {
            const noAccompany = document.getElementById('studentNoAccompany').checked;
            if (noAccompany) {
                return [];
            }
            
            const accompanyData = [];
            document.querySelectorAll('#studentAccompanyContainer .accompany-item').forEach(item => {
                const name = item.querySelector('.accompany-name').value;
                const mobile = item.querySelector('.accompany-mobile').value;
                const email = item.querySelector('.accompany-email').value;
                const relationship = item.querySelector('.accompany-relationship').value;
                
                if (name || mobile || email || relationship) {
                    accompanyData.push({
                        name: name,
                        mobile: mobile,
                        email: email,
                        relationship: relationship
                    });
                }
            });
            return accompanyData;
        }

        // Get employee accompany data
        function getEmployeeAccompanyData() {
            const noAccompany = document.getElementById('employeeNoAccompany').checked;
            if (noAccompany) {
                return [];
            }
            
            const accompanyData = [];
            document.querySelectorAll('#employeeAccompanyContainer .accompany-item').forEach(item => {
                const name = item.querySelector('.accompany-name').value;
                const mobile = item.querySelector('.accompany-mobile').value;
                const email = item.querySelector('.accompany-email').value;
                const relationship = item.querySelector('.accompany-relationship').value;
                
                if (name || mobile || email || relationship) {
                    accompanyData.push({
                        name: name,
                        mobile: mobile,
                        email: email,
                        relationship: relationship
                    });
                }
            });
            return accompanyData;
        }

        // Add passenger for vehicle out pass
        function addPassenger() {
            const container = document.getElementById('passengersContainer');
            const template = document.getElementById('passengerTemplate').cloneNode(true);
            
            template.removeAttribute('id');
            template.style.display = 'block';
            
            const passengerNumber = container.children.length;
            template.querySelector('.passenger-number').textContent = passengerNumber + 1;
            
            template.querySelector('.remove-passenger').addEventListener('click', function() {
                if (container.children.length > 1) {
                    this.closest('.passenger-item').remove();
                    updatePassengerNumbers();
                    updatePassPreview();
                }
            });
            
            template.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', updatePassPreview);
            });
            
            container.appendChild(template);
            updatePassPreview();
        }

        // Clear all passengers
        function clearPassengers() {
            const container = document.getElementById('passengersContainer');
            const template = container.querySelector('#passengerTemplate');
            
            while (container.firstChild) {
                container.removeChild(container.firstChild);
            }
            
            container.appendChild(template);
        }

        // Update passenger numbers
        function updatePassengerNumbers() {
            const passengers = document.querySelectorAll('#passengersContainer .passenger-item:not(#passengerTemplate)');
            passengers.forEach((passenger, index) => {
                passenger.querySelector('.passenger-number').textContent = index + 1;
            });
        }

        // Get passenger data
        function getPassengerData() {
            const passengers = [];
            document.querySelectorAll('#passengersContainer .passenger-item:not(#passengerTemplate)').forEach(passenger => {
                const name = passenger.querySelector('.passenger-name').value;
                const phone = passenger.querySelector('.passenger-phone').value;
                const relation = passenger.querySelector('.passenger-relation').value;
                
                if (name || phone || relation) {
                    passengers.push({
                        name: name,
                        phone: phone,
                        relation: relation
                    });
                }
            });
            return passengers;
        }

        // Approve a request
        function approveRequest(passCode) {
            if (!confirm(`Are you sure you want to approve pass ${passCode}?`)) {
                return;
            }
            
            fetch(`/out-pass/approve/${passCode}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Out Pass ${passCode} has been approved.`);
                    
                    // Update local storage for demo
                    const outPasses = JSON.parse(localStorage.getItem('outPasses')) || [];
                    const passIndex = outPasses.findIndex(p => p.passCode === passCode);
                    if (passIndex !== -1) {
                        outPasses[passIndex].status = 'approved';
                        outPasses[passIndex].approvedBy = 'Admin';
                        outPasses[passIndex].approvedAt = new Date().toISOString();
                        localStorage.setItem('outPasses', JSON.stringify(outPasses));
                    }
                    
                    // Update UI
                    updateStats();
                    loadAllRequests();
                    loadHistory();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Fallback for demo
                const outPasses = JSON.parse(localStorage.getItem('outPasses')) || [];
                const passIndex = outPasses.findIndex(p => p.passCode === passCode);
                if (passIndex !== -1) {
                    outPasses[passIndex].status = 'approved';
                    outPasses[passIndex].approvedBy = 'Admin';
                    outPasses[passIndex].approvedAt = new Date().toISOString();
                    localStorage.setItem('outPasses', JSON.stringify(outPasses));
                    
                    alert(`Out Pass ${passCode} has been approved (Demo Mode).`);
                    
                    // Update UI
                    updateStats();
                    loadAllRequests();
                    loadHistory();
                }
            });
        }

        // Reject a request
        function rejectRequest(passCode) {
            const reason = prompt('Please enter rejection reason:');
            if (reason === null) return;
            
            fetch(`/out-pass/reject/${passCode}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ rejection_reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Out Pass ${passCode} has been rejected.`);
                    
                    // Update local storage for demo
                    const outPasses = JSON.parse(localStorage.getItem('outPasses')) || [];
                    const passIndex = outPasses.findIndex(p => p.passCode === passCode);
                    if (passIndex !== -1) {
                        outPasses[passIndex].status = 'rejected';
                        outPasses[passIndex].rejectedBy = 'Admin';
                        outPasses[passIndex].rejectedAt = new Date().toISOString();
                        outPasses[passIndex].rejectionReason = reason;
                        localStorage.setItem('outPasses', JSON.stringify(outPasses));
                    }
                    
                    // Update UI
                    updateStats();
                    loadAllRequests();
                    loadHistory();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Fallback for demo
                const outPasses = JSON.parse(localStorage.getItem('outPasses')) || [];
                const passIndex = outPasses.findIndex(p => p.passCode === passCode);
                if (passIndex !== -1) {
                    outPasses[passIndex].status = 'rejected';
                    outPasses[passIndex].rejectedBy = 'Admin';
                    outPasses[passIndex].rejectedAt = new Date().toISOString();
                    outPasses[passIndex].rejectionReason = reason;
                    localStorage.setItem('outPasses', JSON.stringify(outPasses));
                    
                    alert(`Out Pass ${passCode} has been rejected (Demo Mode).`);
                    
                    // Update UI
                    updateStats();
                    loadAllRequests();
                    loadHistory();
                }
            });
        }

        // Generate auto code for each pass type
        function generateAutoCode(type) {
            const prefixes = {
                'student': 'STU',
                'employee': 'EMP',
                'vehicle': 'VEH',
                'visitor': 'VIS'
            };
            
            const prefix = prefixes[type] || 'OUT';
            const date = new Date();
            const dateStr = date.getFullYear().toString().substr(-2) + 
                          (date.getMonth() + 1).toString().padStart(2, '0') + 
                          date.getDate().toString().padStart(2, '0');
            
            const outPasses = JSON.parse(localStorage.getItem('outPasses')) || [];
            const todayPasses = outPasses.filter(pass => {
                const passDate = new Date(pass.submittedAt);
                return passDate.toISOString().split('T')[0] === new Date().toISOString().split('T')[0];
            });
            
            const sequenceNumber = (todayPasses.length + 1).toString().padStart(3, '0');
            
            const randomChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let randomSuffix = '';
            for (let i = 0; i < 3; i++) {
                randomSuffix += randomChars.charAt(Math.floor(Math.random() * randomChars.length));
            }
            
            return `${prefix}-${dateStr}-${sequenceNumber}-${randomSuffix}`;
        }

        // Update auto code display
        function updateAutoCode() {
            const passType = document.getElementById('passType').value;
            if (passType) {
                const autoCode = generateAutoCode(passType);
                document.getElementById('generatedCode').textContent = autoCode;
                document.getElementById('autoCodeDisplay').style.display = 'block';
                document.getElementById('pdfOptions').style.display = 'block';
            } else {
                document.getElementById('autoCodeDisplay').style.display = 'none';
                document.getElementById('pdfOptions').style.display = 'none';
            }
        }

        // Format date for display
        function formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        // Format time for display
        function formatTime(timeString) {
            if (!timeString) return '';
            const [hours, minutes] = timeString.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const displayHour = hour % 12 || 12;
            return `${displayHour}:${minutes} ${ampm}`;
        }

        // Setup all event listeners
        function setupEventListeners() {
            // Pass type change
            document.getElementById('passType').addEventListener('change', function() {
                const type = this.value;
                
                // First remove all required attributes
                removeRequiredAttributes();
                
                // Hide all sections
                document.getElementById('studentFields').style.display = 'none';
                document.getElementById('employeeFields').style.display = 'none';
                document.getElementById('vehicleFields').style.display = 'none';
                document.getElementById('visitorFields').style.display = 'none';
                document.getElementById('hostelFields').style.display = 'none';
                
                if (type !== 'vehicle') {
                    clearPassengers();
                }
                clearStudentAccompany();
                clearEmployeeAccompany();
                
                // Show selected section
                if (type === 'student') {
                    document.getElementById('studentFields').style.display = 'block';
                    document.getElementById('studentId').value = '';
                    document.getElementById('studentDetailsSection').style.display = 'none';
                    document.getElementById('studentOptionalFields').style.display = 'none';
                    document.getElementById('studentAccompanySection').style.display = 'none';
                } else if (type === 'employee') {
                    document.getElementById('employeeFields').style.display = 'block';
                    document.getElementById('employeeIdInput').value = '';
                    document.getElementById('employeeDetailsSection').style.display = 'none';
                    document.getElementById('employeeAccompanySection').style.display = 'none';
                } else if (type === 'vehicle') {
                    document.getElementById('vehicleFields').style.display = 'block';
                    addPassenger();
                } else if (type === 'visitor') {
                    document.getElementById('visitorFields').style.display = 'block';
                }
                
                updateAutoCode();
                updatePassPreview();
            });

            // Fetch student details button
            document.getElementById('fetchStudentBtn').addEventListener('click', function() {
                const studentId = document.getElementById('studentId').value.trim();
                if (!studentId) {
                    alert('Please enter Student ID');
                    return;
                }
                fetchStudentDetails(studentId);
            });

            // Fetch employee details button
            document.getElementById('fetchEmployeeBtn').addEventListener('click', function() {
                const employeeId = document.getElementById('employeeIdInput').value.trim();
                if (!employeeId) {
                    alert('Please enter Employee ID');
                    return;
                }
                fetchEmployeeDetails(employeeId);
            });

            // Add student accompany button
            document.getElementById('addStudentAccompany').addEventListener('click', function() {
                addStudentAccompany();
            });

            // Add employee accompany button
            document.getElementById('addEmployeeAccompany').addEventListener('click', function() {
                addEmployeeAccompany();
            });

            // No accompany checkboxes
            document.getElementById('studentNoAccompany').addEventListener('change', function() {
                const disabled = this.checked;
                const inputs = document.querySelectorAll('#studentAccompanyContainer input');
                inputs.forEach(input => {
                    input.disabled = disabled;
                });
                
                if (disabled) {
                    clearStudentAccompany();
                }
                updatePassPreview();
            });

            document.getElementById('employeeNoAccompany').addEventListener('change', function() {
                const disabled = this.checked;
                const inputs = document.querySelectorAll('#employeeAccompanyContainer input');
                inputs.forEach(input => {
                    input.disabled = disabled;
                });
                
                if (disabled) {
                    clearEmployeeAccompany();
                }
                updatePassPreview();
            });

            // Hosteler checkbox
            if (document.getElementById('studentIsHosteler')) {
                document.getElementById('studentIsHosteler').addEventListener('change', function() {
                    document.getElementById('hostelFields').style.display = this.checked ? 'block' : 'none';
                });
            }

            // Add passenger button
            document.getElementById('addPassenger').addEventListener('click', function() {
                addPassenger();
            });

            // Form input changes for live preview
            document.querySelectorAll('#outPassForm input, #outPassForm select, #outPassForm textarea').forEach(element => {
                element.addEventListener('input', updatePassPreview);
                element.addEventListener('change', updatePassPreview);
            });

            // Out pass form submission
            document.getElementById('outPassForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitOutPassRequest();
            });

            // Generate PDF button
            document.getElementById('generatePDF').addEventListener('click', function() {
                generateAndDownloadPDF();
            });

            // Download PDF from preview
            document.getElementById('downloadPDF').addEventListener('click', function() {
                generateAndDownloadPDF();
            });

            // Filter buttons for requests
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.filter-btn').forEach(b => {
                        b.classList.remove('active');
                    });
                    this.classList.add('active');
                    
                    const filter = this.getAttribute('data-filter');
                    filterRequests(filter);
                });
            });

            // Pass action buttons
            document.getElementById('approvePass').addEventListener('click', function() {
                approveCurrentPass();
            });

            document.getElementById('rejectPass').addEventListener('click', function() {
                rejectCurrentPass();
            });

            document.getElementById('printPass').addEventListener('click', function() {
                window.print();
            });

            // Search and filter for history
            document.getElementById('searchButton').addEventListener('click', searchHistory);
            document.getElementById('filterButton').addEventListener('click', filterHistoryByDate);
            
            // Load sample data button
            document.getElementById('loadSampleData').addEventListener('click', function() {
                if (confirm('This will reload all sample data. Existing data will be replaced. Continue?')) {
                    localStorage.removeItem('outPasses');
                    loadSampleOutPasses();
                    updateStats();
                    loadAllRequests();
                    loadHistory();
                    alert('Sample data reloaded successfully!');
                }
            });
            
            // Load sample form data button
            document.getElementById('loadSampleFormData').addEventListener('click', function() {
                loadSampleFormData();
            });
            
            // Form reset
            document.querySelector('#outPassForm button[type="reset"]').addEventListener('click', function() {
                resetForm();
            });
        }

        // Load sample form data for demonstration
        function loadSampleFormData() {
            const passTypes = ['student', 'employee', 'vehicle', 'visitor'];
            const randomType = passTypes[Math.floor(Math.random() * passTypes.length)];
            
            document.getElementById('passType').value = randomType;
            document.getElementById('passType').dispatchEvent(new Event('change'));
            
            updateAutoCode();
            
            if (randomType === 'student') {
                document.getElementById('studentId').value = 'STU001';
                setTimeout(() => {
                    fetchStudentDetails('STU001');
                    
                    clearStudentAccompany();
                    addStudentAccompany();
                    
                    const accompanyItem = document.querySelector('#studentAccompanyContainer .accompany-item');
                    if (accompanyItem) {
                        accompanyItem.querySelector('.accompany-name').value = 'Robert Doe';
                        accompanyItem.querySelector('.accompany-mobile').value = '9876543211';
                        accompanyItem.querySelector('.accompany-email').value = 'robert.doe@email.com';
                        accompanyItem.querySelector('.accompany-relationship').value = 'Father';
                    }
                    
                    document.getElementById('studentNoAccompany').checked = false;
                    
                    setSampleExitDetails();
                    updatePassPreview();
                }, 100);
                
            } else if (randomType === 'employee') {
                document.getElementById('employeeIdInput').value = 'EMP001';
                setTimeout(() => {
                    fetchEmployeeDetails('EMP001');
                    
                    clearEmployeeAccompany();
                    addEmployeeAccompany();
                    
                    const accompanyItem = document.querySelector('#employeeAccompanyContainer .accompany-item');
                    if (accompanyItem) {
                        accompanyItem.querySelector('.accompany-name').value = 'James Williams';
                        accompanyItem.querySelector('.accompany-mobile').value = '9876543312';
                        accompanyItem.querySelector('.accompany-email').value = 'james.w@email.com';
                        accompanyItem.querySelector('.accompany-relationship').value = 'Spouse';
                    }
                    
                    document.getElementById('employeeNoAccompany').checked = false;
                    
                    setSampleExitDetails();
                    updatePassPreview();
                }, 100);
                
            } else if (randomType === 'vehicle') {
                document.getElementById('vehicleNumber').value = 'MH01-AB-1234';
                document.getElementById('vehicleType').value = 'Car';
                document.getElementById('vehicleModel').value = 'Toyota Innova';
                document.getElementById('driverName').value = 'Rajesh Kumar';
                document.getElementById('driverContact').value = '9876543410';
                document.getElementById('driverAddress').value = '101 Palm Street, City, State 12345';
                document.getElementById('driverLicense').value = 'DL1234567890';
                
                clearPassengers();
                setTimeout(() => {
                    addPassenger();
                    addPassenger();
                    
                    const passengers = document.querySelectorAll('#passengersContainer .passenger-item:not(#passengerTemplate)');
                    if (passengers.length >= 2) {
                        passengers[0].querySelector('.passenger-name').value = 'Amit Sharma';
                        passengers[0].querySelector('.passenger-phone').value = '9876543411';
                        passengers[0].querySelector('.passenger-relation').value = 'Colleague';
                        passengers[1].querySelector('.passenger-name').value = 'Priya Singh';
                        passengers[1].querySelector('.passenger-phone').value = '9876543412';
                        passengers[1].querySelector('.passenger-relation').value = 'Client';
                    }
                    
                    setSampleExitDetails();
                    updatePassPreview();
                }, 100);
                
            } else if (randomType === 'visitor') {
                document.getElementById('visitorName').value = 'David Wilson';
                document.getElementById('visitorContact').value = '9876543510';
                document.getElementById('visitorAddress').value = '404 Rose Street, City, State 12345';
                document.getElementById('visitorIdProof').value = 'Aadhar: 1234-5678-9012';
                document.getElementById('visitorCompany').value = 'Global Solutions Inc.';
                document.getElementById('personToMeet').value = 'Sarah Williams';
                
                setSampleExitDetails();
                updatePassPreview();
            }
            
            setTimeout(() => {
                alert('Sample form data loaded successfully!');
            }, 500);
        }

        // Set sample exit details
        function setSampleExitDetails() {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowStr = tomorrow.toISOString().split('T')[0];
            
            document.getElementById('outDate').value = tomorrowStr;
            document.getElementById('outTime').value = '14:30';
            document.getElementById('purpose').value = 'Sample purpose for demonstration';
            document.getElementById('destination').value = 'Sample Destination';
        }

        // Update the out pass preview
        function updatePassPreview() {
            const passType = document.getElementById('passType').value;
            const outDate = document.getElementById('outDate').value;
            const outTime = document.getElementById('outTime').value;
            const purpose = document.getElementById('purpose').value;
            const destination = document.getElementById('destination').value;
            const autoCode = document.getElementById('generatedCode').textContent;
            
            if (!passType) {
                document.getElementById('passPreview').innerHTML = `
                    <div class="out-pass-content">
                        <div class="text-center py-5">
                            <i class="fas fa-door-open fa-4x mb-3" style="color: #e0e0e0;"></i>
                            <h4 style="color: #adb5bd;">Out Pass Preview</h4>
                            <p class="text-muted">Fill out the form to see the preview</p>
                        </div>
                    </div>
                `;
                document.getElementById('passActions').style.display = 'none';
                return;
            }
            
            let passHTML = '';
            
            if (passType === 'student') {
                const name = document.getElementById('studentName').value || 'Student Name';
                const contact = document.getElementById('studentContact').value || 'Not provided';
                const address = document.getElementById('studentAddress').value || 'Not provided';
                const studentClass = document.getElementById('studentClass').value || 'Not specified';
                const section = document.getElementById('studentSection').value || 'Not specified';
                const rollNo = document.getElementById('studentRollNo').value || 'Not specified';
                const studentId = document.getElementById('studentId').value || 'Not specified';
                const email = document.getElementById('studentEmail').value || 'Not provided';
                const parentName = document.getElementById('studentParentName').value || 'Not provided';
                const parentContact = document.getElementById('studentParentContact').value || 'Not provided';
                const parentEmail = document.getElementById('studentParentEmail')?.value || '';
                const bloodGroup = document.getElementById('studentBloodGroup')?.value || '';
                const medicalConditions = document.getElementById('studentMedicalConditions')?.value || '';
                const isHosteler = document.getElementById('studentIsHosteler')?.checked || false;
                const hostelName = document.getElementById('studentHostelName')?.value || '';
                const roomNumber = document.getElementById('studentRoomNumber')?.value || '';
                
                const accompanyData = getStudentAccompanyData();
                const noAccompany = document.getElementById('studentNoAccompany').checked;
                
                passHTML = `
                    <div class="out-pass-content">
                        <div class="out-pass-header">
                            <div class="out-pass-title">STUDENT OUT PASS</div>
                            <div class="out-pass-subtitle">SCHOOL/COLLEGE EXIT PERMIT</div>
                        </div>
                        
                        <div class="qr-container">
                            <div class="qr-placeholder">
                                <i class="fas fa-qrcode"></i>
                                <small>Scan to Verify</small>
                            </div>
                        </div>
                        
                        <div class="out-pass-details">
                            <div class="detail-row">
                                <div class="detail-label">Pass Code:</div>
                                <div class="detail-value"><strong>${autoCode}</strong></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Student ID:</div>
                                <div class="detail-value">${studentId}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Full Name:</div>
                                <div class="detail-value">${name}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Contact Number:</div>
                                <div class="detail-value">${contact}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Email:</div>
                                <div class="detail-value">${email}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Address:</div>
                                <div class="detail-value">${address}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Class:</div>
                                <div class="detail-value">${studentClass}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Section:</div>
                                <div class="detail-value">${section}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Roll Number:</div>
                                <div class="detail-value">${rollNo}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Parent Name:</div>
                                <div class="detail-value">${parentName}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Parent Contact:</div>
                                <div class="detail-value">${parentContact}</div>
                            </div>`;
                
                if (parentEmail) {
                    passHTML += `
                            <div class="detail-row">
                                <div class="detail-label">Parent Email:</div>
                                <div class="detail-value">${parentEmail}</div>
                            </div>`;
                }
                
                if (bloodGroup) {
                    passHTML += `
                            <div class="detail-row">
                                <div class="detail-label">Blood Group:</div>
                                <div class="detail-value">${bloodGroup}</div>
                            </div>`;
                }
                
                if (medicalConditions) {
                    passHTML += `
                            <div class="detail-row">
                                <div class="detail-label">Medical Conditions:</div>
                                <div class="detail-value">${medicalConditions}</div>
                            </div>`;
                }
                
                if (isHosteler) {
                    passHTML += `
                            <div class="detail-row">
                                <div class="detail-label">Hostel Name:</div>
                                <div class="detail-value">${hostelName}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Room Number:</div>
                                <div class="detail-value">${roomNumber}</div>
                            </div>`;
                }
                
                if (noAccompany) {
                    passHTML += `
                            <div class="detail-row">
                                <div class="detail-label">Accompany By:</div>
                                <div class="detail-value">None</div>
                            </div>`;
                } else if (accompanyData.length > 0) {
                    accompanyData.forEach((person, index) => {
                        if (person.name || person.mobile || person.email || person.relationship) {
                            passHTML += `
                                <div class="detail-row">
                                    <div class="detail-label">Accompany ${index + 1}:</div>
                                    <div class="detail-value">${person.name || 'Not specified'}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Mobile ${index + 1}:</div>
                                    <div class="detail-value">${person.mobile || 'Not specified'}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Email ${index + 1}:</div>
                                    <div class="detail-value">${person.email || 'Not specified'}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Relationship ${index + 1}:</div>
                                    <div class="detail-value">${person.relationship || 'Not specified'}</div>
                                </div>`;
                        }
                    });
                }
                
                passHTML += `
                            <div class="detail-row">
                                <div class="detail-label">Out Date:</div>
                                <div class="detail-value">${outDate ? formatDate(outDate) : 'Select date'}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Out Time:</div>
                                <div class="detail-value">${outTime ? formatTime(outTime) : 'Select time'}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Destination:</div>
                                <div class="detail-value">${destination || 'Not specified'}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Purpose:</div>
                                <div class="detail-value">${purpose || 'Not specified'}</div>
                            </div>
                        </div>
                        
                        <div class="out-pass-footer">
                            <div class="signature-area">
                                <div class="signature-box">
                                    <div>Student/Parent Signature</div>
                                    <div class="signature-line">${name}</div>
                                </div>
                                <div class="signature-box">
                                    <div>Authorized By</div>
                                    <div class="signature-line">School/College Authority</div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small><i class="fas fa-exclamation-circle me-1"></i> This pass must be returned to security upon return</small>
                            </div>
                        </div>
                    </div>
                `;
            } else if (passType === 'employee') {
                const name = document.getElementById('employeeName').value || 'Employee Name';
                const contact = document.getElementById('employeeContact').value || 'Not provided';
                const address = document.getElementById('employeeAddress').value || 'Not provided';
                const department = document.getElementById('employeeDepartment').value || 'Not specified';
                const designation = document.getElementById('employeeDesignation').value || 'Not specified';
                const employeeId = document.getElementById('employeeIdInput').value || 'Not specified';
                const email = document.getElementById('employeeEmail').value || 'Not provided';
                const manager = document.getElementById('employeeManager').value || 'Not specified';
                const emergencyContact = document.getElementById('employeeEmergencyContact').value || 'Not provided';
                
                const accompanyData = getEmployeeAccompanyData();
                const noAccompany = document.getElementById('employeeNoAccompany').checked;
                
                passHTML = `
                    <div class="out-pass-content">
                        <div class="out-pass-header">
                            <div class="out-pass-title">EMPLOYEE OUT PASS</div>
                            <div class="out-pass-subtitle">OFFICE EXIT PERMIT</div>
                        </div>
                        
                        <div class="qr-container">
                            <div class="qr-placeholder">
                                <i class="fas fa-qrcode"></i>
                                <small>Scan to Verify</small>
                            </div>
                        </div>
                        
                        <div class="out-pass-details">
                            <div class="detail-row">
                                <div class="detail-label">Pass Code:</div>
                                <div class="detail-value"><strong>${autoCode}</strong></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Employee ID:</div>
                                <div class="detail-value">${employeeId}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Employee Name:</div>
                                <div class="detail-value">${name}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Contact Number:</div>
                                <div class="detail-value">${contact}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Email:</div>
                                <div class="detail-value">${email}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Address:</div>
                                <div class="detail-value">${address}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Department:</div>
                                <div class="detail-value">${department}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Designation:</div>
                                <div class="detail-value">${designation}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Reporting Manager:</div>
                                <div class="detail-value">${manager}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Emergency Contact:</div>
                                <div class="detail-value">${emergencyContact}</div>
                            </div>`;
                
                if (noAccompany) {
                    passHTML += `
                            <div class="detail-row">
                                <div class="detail-label">Accompany By:</div>
                                <div class="detail-value">None</div>
                            </div>`;
                } else if (accompanyData.length > 0) {
                    accompanyData.forEach((person, index) => {
                        if (person.name || person.mobile || person.email || person.relationship) {
                            passHTML += `
                                <div class="detail-row">
                                    <div class="detail-label">Accompany ${index + 1}:</div>
                                    <div class="detail-value">${person.name || 'Not specified'}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Mobile ${index + 1}:</div>
                                    <div class="detail-value">${person.mobile || 'Not specified'}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Email ${index + 1}:</div>
                                    <div class="detail-value">${person.email || 'Not specified'}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Relationship ${index + 1}:</div>
                                    <div class="detail-value">${person.relationship || 'Not specified'}</div>
                                </div>`;
                        }
                    });
                }
                
                passHTML += `
                            <div class="detail-row">
                                <div class="detail-label">Out Date:</div>
                                <div class="detail-value">${outDate ? formatDate(outDate) : 'Select date'}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Out Time:</div>
                                <div class="detail-value">${outTime ? formatTime(outTime) : 'Select time'}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Destination:</div>
                                <div class="detail-value">${destination || 'Not specified'}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Purpose:</div>
                                <div class="detail-value">${purpose || 'Not specified'}</div>
                            </div>
                        </div>
                        
                        <div class="out-pass-footer">
                            <div class="signature-area">
                                <div class="signature-box">
                                    <div>Employee Signature</div>
                                    <div class="signature-line">${name}</div>
                                </div>
                                <div class="signature-box">
                                    <div>Authorized By</div>
                                    <div class="signature-line">Management/HOD</div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small><i class="fas fa-exclamation-circle me-1"></i> This pass must be returned to security upon return</small>
                            </div>
                        </div>
                    </div>
                `;
            } else if (passType === 'vehicle') {
                const vehicleNumber = document.getElementById('vehicleNumber').value || 'Not specified';
                const vehicleType = document.getElementById('vehicleType').value || 'Not specified';
                const vehicleModel = document.getElementById('vehicleModel').value || 'Not specified';
                const driverName = document.getElementById('driverName').value || 'Driver Name';
                const driverContact = document.getElementById('driverContact').value || 'Not provided';
                const driverAddress = document.getElementById('driverAddress').value || 'Not provided';
                const driverLicense = document.getElementById('driverLicense').value || 'Not provided';
                const passengers = getPassengerData();
                
                let passengersHTML = '';
                if (passengers.length > 0) {
                    passengers.forEach((passenger, index) => {
                        passengersHTML += `
                            <div class="detail-row">
                                <div class="detail-label">Passenger ${index + 1}:</div>
                                <div class="detail-value">${passenger.name || 'Not specified'}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Phone ${index + 1}:</div>
                                <div class="detail-value">${passenger.phone || 'Not specified'}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Relation ${index + 1}:</div>
                                <div class="detail-value">${passenger.relation || 'Not specified'}</div>
                            </div>
                        `;
                    });
                }
                
                passHTML = `
                    <div class="out-pass-content">
                        <div class="out-pass-header">
                            <div class="out-pass-title">VEHICLE OUT PASS</div>
                            <div class="out-pass-subtitle">VEHICLE EXIT PERMIT</div>
                        </div>
                        
                        <div class="qr-container">
                            <div class="qr-placeholder">
                                <i class="fas fa-qrcode"></i>
                                <small>Scan to Verify</small>
                            </div>
                        </div>
                        
                        <div class="out-pass-details">
                            <div class="detail-row">
                                <div class="detail-label">Pass Code:</div>
                                <div class="detail-value"><strong>${autoCode}</strong></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Vehicle Number:</div>
                                <div class="detail-value">${vehicleNumber}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Vehicle Type:</div>
                                <div class="detail-value">${vehicleType}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Vehicle Model:</div>
                                <div class="detail-value">${vehicleModel}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Driver Name:</div>
                                <div class="detail-value">${driverName}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Driver Contact:</div>
                                <div class="detail-value">${driverContact}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Driver Address:</div>
                                <div class="detail-value">${driverAddress}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Driver License:</div>
                                <div class="detail-value">${driverLicense}</div>
                            </div>
                            ${passengersHTML}
                            <div class="detail-row">
                                <div class="detail-label">Out Date:</div>
                                <div class="detail-value">${outDate ? formatDate(outDate) : 'Select date'}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Out Time:</div>
                                <div class="detail-value">${outTime ? formatTime(outTime) : 'Select time'}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Destination:</div>
                                <div class="detail-value">${destination || 'Not specified'}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Purpose:</div>
                                <div class="detail-value">${purpose || 'Not specified'}</div>
                            </div>
                        </div>
                        
                        <div class="out-pass-footer">
                            <div class="signature-area">
                                <div class="signature-box">
                                    <div>Driver Signature</div>
                                    <div class="signature-line">${driverName}</div>
                                </div>
                                <div class="signature-box">
                                    <div>Authorized By</div>
                                    <div class="signature-line">Security Officer</div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small><i class="fas fa-exclamation-circle me-1"></i> This pass must be returned to security upon return</small>
                            </div>
                        </div>
                    </div>
                `;
            } else if (passType === 'visitor') {
                const name = document.getElementById('visitorName').value || 'Visitor Name';
                const contact = document.getElementById('visitorContact').value || 'Not provided';
                const address = document.getElementById('visitorAddress').value || 'Not provided';
                const idProof = document.getElementById('visitorIdProof').value || 'Not provided';
                const company = document.getElementById('visitorCompany').value || 'Not provided';
                const personToMeet = document.getElementById('personToMeet').value || 'Not specified';
                
                passHTML = `
                    <div class="out-pass-content">
                        <div class="out-pass-header">
                            <div class="out-pass-title">VISITOR OUT PASS</div>
                            <div class="out-pass-subtitle">VISITOR EXIT PERMIT</div>
                        </div>
                        
                        <div class="qr-container">
                            <div class="qr-placeholder">
                                <i class="fas fa-qrcode"></i>
                                <small>Scan to Verify</small>
                            </div>
                        </div>
                    
                        <div class="out-pass-details">
                            <div class="detail-row">
                                <div class="detail-label">Pass Code:</div>
                                <div class="detail-value"><strong>${autoCode}</strong></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Visitor Name:</div>
                                <div class="detail-value">${name}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Contact Number:</div>
                                <div class="detail-value">${contact}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Address:</div>
                                <div class="detail-value">${address}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">ID Proof:</div>
                                <div class="detail-value">${idProof}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Company/Organization:</div>
                                <div class="detail-value">${company}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Person to Meet:</div>
                                <div class="detail-value">${personToMeet}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Out Date:</div>
                                <div class="detail-value">${outDate ? formatDate(outDate) : 'Select date'}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Out Time:</div>
                                <div class="detail-value">${outTime ? formatTime(outTime) : 'Select time'}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Destination:</div>
                                <div class="detail-value">${destination || 'Not specified'}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Purpose:</div>
                                <div class="detail-value">${purpose || 'Not specified'}</div>
                            </div>
                        </div>
                        
                        <div class="out-pass-footer">
                            <div class="signature-area">
                                <div class="signature-box">
                                    <div>Visitor Signature</div>
                                    <div class="signature-line">${name}</div>
                                </div>
                                <div class="signature-box">
                                    <div>Authorized By</div>
                                    <div class="signature-line">Security Officer</div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <small><i class="fas fa-exclamation-circle me-1"></i> This pass must be returned to security upon return</small>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            document.getElementById('passPreview').innerHTML = passHTML;
            document.getElementById('passActions').style.display = 'flex';
        }

        // Update statistics
        function updateStats() {
            const outPasses = JSON.parse(localStorage.getItem('outPasses')) || [];
            
            document.getElementById('totalPasses').textContent = outPasses.length;
            
            const approved = outPasses.filter(pass => pass.status === 'approved').length;
            document.getElementById('approvedPasses').textContent = approved;
            
            const pending = outPasses.filter(pass => pass.status === 'pending').length;
            document.getElementById('pendingPasses').textContent = pending;
            
            const today = new Date().toISOString().split('T')[0];
            const todayCount = outPasses.filter(pass => pass.outDate === today).length;
            document.getElementById('todayCount').textContent = todayCount;
        }

        // Load all requests for requests tab
        function loadAllRequests() {
            const outPasses = JSON.parse(localStorage.getItem('outPasses')) || [];
            const tbody = document.getElementById('requestsBody');
            tbody.innerHTML = '';
            
            if (outPasses.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            No out pass requests found. Create your first out pass!
                        </td>
                    </tr>
                `;
                return;
            }
            
            outPasses.slice().reverse().forEach(pass => {
                const statusBadge = getStatusBadge(pass.status);
                let typeBadge = '';
                let displayName = pass.full_name || pass.driver_name || pass.name || 'N/A';
                
                switch(pass.type) {
                    case 'student':
                        typeBadge = '<span class="badge bg-success">Student</span>';
                        break;
                    case 'employee':
                        typeBadge = '<span class="badge bg-primary">Employee</span>';
                        break;
                    case 'vehicle':
                        typeBadge = '<span class="badge bg-warning text-dark">Vehicle</span>';
                        // displayName = pass.driverName || 'N/A';
                        break;
                    case 'visitor':
                        typeBadge = '<span class="badge bg-secondary">Visitor</span>';
                        break;
                }
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="sticky-main-2"><strong>${pass.passCode}</strong></td>
                    <td>${displayName}</td>
                    <td>${typeBadge}</td>
                    <td>${formatDate(pass.outDate)}<br><small>${formatTime(pass.outTime)}</small></td>
                    <td>${pass.destination ? pass.destination.substring(0, 20) + (pass.destination.length > 20 ? '...' : '') : 'N/A'}</td>
                    <td>${pass.purpose ? pass.purpose.substring(0, 20) + (pass.purpose.length > 20 ? '...' : '') : 'N/A'}</td>
                    <td>${statusBadge}</td>
                    <td class="action-buttons">
                        <button class="btn btn-sm btn-outline-primary view-request" data-code="${pass.passCode}" title="View">
                            <i class="fas fa-eye"></i>
                        </button>
                        ${pass.status === 'pending' ? `
                            <button class="btn btn-sm btn-outline-success approve-request" data-code="${pass.passCode}" title="Approve">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger reject-request" data-code="${pass.passCode}" title="Reject">
                                <i class="fas fa-times"></i>
                            </button>
                        ` : ''}
                        <button class="btn btn-sm btn-outline-info print-request" data-code="${pass.passCode}" title="Print">
                            <i class="fas fa-print"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-warning pdf-request" data-code="${pass.passCode}" title="Download PDF">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
            
            setupRequestActionListeners();
        }

        // Get status badge HTML
        function getStatusBadge(status) {
            switch(status) {
                case 'approved':
                    return '<span class="status-badge status-approved">Approved</span>';
                case 'pending':
                    return '<span class="status-badge status-pending">Pending</span>';
                case 'rejected':
                    return '<span class="status-badge status-rejected">Rejected</span>';
                default:
                    return '<span class="status-badge">Unknown</span>';
            }
        }

        // Setup action listeners for request rows
        function setupRequestActionListeners() {
            document.querySelectorAll('.view-request').forEach(btn => {
                btn.addEventListener('click', function() {
                    viewRequest(this.getAttribute('data-code'));
                });
            });
            
            document.querySelectorAll('.approve-request').forEach(btn => {
                btn.addEventListener('click', function() {
                    approveRequest(this.getAttribute('data-code'));
                });
            });
            
            document.querySelectorAll('.reject-request').forEach(btn => {
                btn.addEventListener('click', function() {
                    rejectRequest(this.getAttribute('data-code'));
                });
            });
            
            document.querySelectorAll('.print-request').forEach(btn => {
                btn.addEventListener('click', function() {
                    printRequest(this.getAttribute('data-code'));
                });
            });
            
            document.querySelectorAll('.pdf-request').forEach(btn => {
                btn.addEventListener('click', function() {
                    const passCode = this.getAttribute('data-code');
                    generatePDFForPass(passCode);
                });
            });
        }

        // View a specific request
        function viewRequest(passCode) {
            const outPasses = JSON.parse(localStorage.getItem('outPasses')) || [];
            const pass = outPasses.find(p => p.passCode === passCode);
            
            if (pass) {
                document.getElementById('generate-tab').click();
                
                document.getElementById('passType').value = pass.type;
                document.getElementById('passType').dispatchEvent(new Event('change'));
                
                document.getElementById('generatedCode').textContent = pass.passCode;
                document.getElementById('autoCodeDisplay').style.display = 'block';
                
                if (pass.type === 'student') {
                    document.getElementById('studentId').value = pass.student_id || '';
                    
                    if (pass.student_id) {
                        document.getElementById('studentName').value = pass.full_name || '';
                        document.getElementById('studentContact').value = pass.contact_number || '';
                        document.getElementById('studentAddress').value = pass.address || '';
                        document.getElementById('studentClass').value = pass.class || '';
                        document.getElementById('studentSection').value = pass.section || '';
                        document.getElementById('studentRollNo').value = pass.roll_number || '';
                        document.getElementById('studentEmail').value = pass.email || '';
                        document.getElementById('studentParentName').value = pass.parent_name || '';
                        document.getElementById('studentParentContact').value = pass.parent_contact || '';
                        
                        if (document.getElementById('studentParentEmail')) {
                            document.getElementById('studentParentEmail').value = pass.parent_email || '';
                        }
                        if (document.getElementById('studentDOB')) {
                            document.getElementById('studentDOB').value = pass.date_of_birth || '';
                        }
                        if (document.getElementById('studentBloodGroup')) {
                            document.getElementById('studentBloodGroup').value = pass.blood_group || '';
                        }
                        if (document.getElementById('studentMedicalConditions')) {
                            document.getElementById('studentMedicalConditions').value = pass.medical_conditions || '';
                        }
                        if (document.getElementById('studentIsHosteler')) {
                            document.getElementById('studentIsHosteler').checked = pass.is_hosteler || false;
                            if (pass.is_hosteler) {
                                document.getElementById('hostelFields').style.display = 'block';
                                document.getElementById('studentHostelName').value = pass.hostel_name || '';
                                document.getElementById('studentRoomNumber').value = pass.room_number || '';
                            }
                        }
                        
                        document.getElementById('studentDetailsSection').style.display = 'block';
                        document.getElementById('studentOptionalFields').style.display = 'block';
                        document.getElementById('studentAccompanySection').style.display = 'block';
                        
                        clearStudentAccompany();
                        if (pass.accompany && Array.isArray(pass.accompany)) {
                            pass.accompany.forEach(person => {
                                addStudentAccompany();
                                const lastItem = document.querySelector('#studentAccompanyContainer .accompany-item:last-child');
                                if (lastItem && person.name) {
                                    lastItem.querySelector('.accompany-name').value = person.name || '';
                                    lastItem.querySelector('.accompany-mobile').value = person.mobile || '';
                                    lastItem.querySelector('.accompany-email').value = person.email || '';
                                    lastItem.querySelector('.accompany-relationship').value = person.relationship || '';
                                }
                            });
                        }
                        document.getElementById('studentNoAccompany').checked = pass.accompany && pass.accompany.length === 0;
                    }
                } else if (pass.type === 'employee') {
                    document.getElementById('employeeIdInput').value = pass.employee_id || '';
                    
                    if (pass.employee_id) {
                        document.getElementById('employeeName').value = pass.full_name || '';
                        document.getElementById('employeeContact').value = pass.contact_number || '';
                        document.getElementById('employeeAddress').value = pass.address || '';
                        document.getElementById('employeeDepartment').value = pass.department || '';
                        document.getElementById('employeeDesignation').value = pass.designation || '';
                        document.getElementById('employeeEmail').value = pass.email || '';
                        document.getElementById('employeeManager').value = pass.reporting_manager || '';
                        document.getElementById('employeeEmergencyContact').value = pass.emergency_contact || '';
                        
                        document.getElementById('employeeDetailsSection').style.display = 'block';
                        document.getElementById('employeeAccompanySection').style.display = 'block';
                        
                        clearEmployeeAccompany();
                        if (pass.accompany && Array.isArray(pass.accompany)) {
                            pass.accompany.forEach(person => {
                                addEmployeeAccompany();
                                const lastItem = document.querySelector('#employeeAccompanyContainer .accompany-item:last-child');
                                if (lastItem && person.name) {
                                    lastItem.querySelector('.accompany-name').value = person.name || '';
                                    lastItem.querySelector('.accompany-mobile').value = person.mobile || '';
                                    lastItem.querySelector('.accompany-email').value = person.email || '';
                                    lastItem.querySelector('.accompany-relationship').value = person.relationship || '';
                                }
                            });
                        }
                        document.getElementById('employeeNoAccompany').checked = pass.accompany && pass.accompany.length === 0;
                    }
                } else if (pass.type === 'vehicle') {
                    document.getElementById('vehicleNumber').value = pass.vehicle_number || '';
                    document.getElementById('vehicleType').value = pass.vehicle_type || '';
                    document.getElementById('vehicleModel').value = pass.model || '';
                    document.getElementById('driverName').value = pass.driver_name || '';
                    document.getElementById('driverContact').value = pass.driver_contact || '';
                    document.getElementById('driverAddress').value = pass.driver_address || '';
                    document.getElementById('driverLicense').value = pass.driver_license || '';
                    
                    clearPassengers();
                    if (pass.passengers && Array.isArray(pass.passengers)) {
                        pass.passengers.forEach((passenger, index) => {
                            if (index === 0) {
                                const firstPassenger = document.querySelector('#passengersContainer .passenger-item:not(#passengerTemplate)');
                                if (firstPassenger) {
                                    firstPassenger.querySelector('.passenger-name').value = passenger.name || '';
                                    firstPassenger.querySelector('.passenger-phone').value = passenger.phone || '';
                                    firstPassenger.querySelector('.passenger-relation').value = passenger.relation || '';
                                }
                            } else {
                                addPassenger();
                                const newPassenger = document.querySelectorAll('#passengersContainer .passenger-item:not(#passengerTemplate)')[index];
                                if (newPassenger) {
                                    newPassenger.querySelector('.passenger-name').value = passenger.name || '';
                                    newPassenger.querySelector('.passenger-phone').value = passenger.phone || '';
                                    newPassenger.querySelector('.passenger-relation').value = passenger.relation || '';
                                }
                            }
                        });
                    }
                } else if (pass.type === 'visitor') {
                    document.getElementById('visitorName').value = pass.visitor_name || '';
                    document.getElementById('visitorContact').value = pass.visitor_contact || '';
                    document.getElementById('visitorAddress').value = pass.visitor_address || '';
                    document.getElementById('visitorIdProof').value = pass.visitor_id_proof || '';
                    document.getElementById('visitorCompany').value = pass.visitor_company || '';
                    document.getElementById('personToMeet').value = pass.person_to_meet || '';
                }
                
                document.getElementById('outDate').value = pass.outDate || '';
                document.getElementById('outTime').value = pass.outTime || '';
                document.getElementById('purpose').value = pass.purpose || '';
                document.getElementById('destination').value = pass.destination || '';
                
                updatePassPreview();
                
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }

        // Print a request
        function printRequest(passCode) {
            viewRequest(passCode);
            setTimeout(() => {
                window.print();
            }, 500);
        }

        // Approve current pass in preview
        function approveCurrentPass() {
            const passIdElement = document.querySelector('#passPreview .detail-row .detail-value strong');
            if (passIdElement) {
                const passCode = passIdElement.textContent.trim();
                approveRequest(passCode);
            }
        }

        // Reject current pass in preview
        function rejectCurrentPass() {
            const passIdElement = document.querySelector('#passPreview .detail-row .detail-value strong');
            if (passIdElement) {
                const passCode = passIdElement.textContent.trim();
                rejectRequest(passCode);
            }
        }

        // Generate PDF for existing pass
        function generatePDFForPass(passCode) {
            const outPasses = JSON.parse(localStorage.getItem('outPasses')) || [];
            const pass = outPasses.find(p => p.passCode === passCode);
            
            if (!pass) {
                alert('Pass not found');
                return;
            }
            
            const pdf = new jsPDF('p', 'mm', 'a4');
            
            pdf.setFont("helvetica");
            
            pdf.setTextColor(200, 200, 200);
            pdf.setFontSize(60);
            pdf.text('OUT PASS', 105, 150, { angle: 45, align: 'center' });
            pdf.setTextColor(0, 0, 0);
            
            pdf.setFillColor(67, 97, 238);
            pdf.rect(0, 0, 210, 30, 'F');
            
            pdf.setTextColor(255, 255, 255);
            pdf.setFontSize(24);
            pdf.setFont("helvetica", "bold");
            pdf.text('OUT PASS SYSTEM', 105, 18, { align: 'center' });
            
            pdf.setFontSize(12);
            pdf.text(`${pass.type.toUpperCase()} EXIT PERMIT`, 105, 25, { align: 'center' });
            
            pdf.setTextColor(0, 0, 0);
            
            pdf.setFontSize(16);
            pdf.setFont("helvetica", "bold");
            pdf.text(`Pass Code: ${pass.passCode}`, 20, 45);
            
            pdf.setDrawColor(67, 97, 238);
            pdf.setLineWidth(0.5);
            pdf.line(20, 50, 190, 50);
            
            pdf.setFontSize(11);
            pdf.setFont("helvetica", "normal");
            
            let yPosition = 60;
            
            if (pass.type === 'student') {
                pdf.text(`Student ID: ${pass.student_id || 'Not provided'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Full Name: ${pass.full_name || 'Not provided'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Contact Number: ${pass.contact_number || 'Not provided'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Email: ${pass.email || 'Not provided'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Address: ${pass.address || 'Not provided'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Class: ${pass.class || 'Not specified'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Section: ${pass.section || 'Not specified'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Roll Number: ${pass.roll_number || 'Not specified'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Parent Name: ${pass.parent_name || 'Not provided'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Parent Contact: ${pass.parent_contact || 'Not provided'}`, 20, yPosition);
                
                if (pass.parent_email) {
                    yPosition += 8;
                    pdf.text(`Parent Email: ${pass.parent_email}`, 20, yPosition);
                }
                
                if (pass.blood_group) {
                    yPosition += 8;
                    pdf.text(`Blood Group: ${pass.blood_group}`, 20, yPosition);
                }
                
                if (pass.medical_conditions) {
                    yPosition += 8;
                    pdf.text(`Medical Conditions: ${pass.medical_conditions}`, 20, yPosition);
                }
                
                if (pass.is_hosteler) {
                    yPosition += 8;
                    pdf.text(`Hostel Name: ${pass.hostel_name || 'Not specified'}`, 20, yPosition);
                    yPosition += 8;
                    pdf.text(`Room Number: ${pass.room_number || 'Not specified'}`, 20, yPosition);
                }
                
                if (pass.accompany && Array.isArray(pass.accompany) && pass.accompany.length > 0) {
                    yPosition += 8;
                    pass.accompany.forEach((person, index) => {
                        if (person.name) {
                            pdf.text(`Accompany ${index + 1}: ${person.name}`, 20, yPosition);
                            yPosition += 8;
                            pdf.text(`  Mobile: ${person.mobile || 'Not specified'}`, 25, yPosition);
                            yPosition += 8;
                            pdf.text(`  Email: ${person.email || 'Not specified'}`, 25, yPosition);
                            yPosition += 8;
                            pdf.text(`  Relationship: ${person.relationship || 'Not specified'}`, 25, yPosition);
                            yPosition += 8;
                        }
                    });
                } else {
                    yPosition += 8;
                    pdf.text(`Accompany By: None`, 20, yPosition);
                }
                
            } else if (pass.type === 'employee') {
                pdf.text(`Employee ID: ${pass.employee_id || 'Not specified'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Employee Name: ${pass.full_name || 'Not provided'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Contact Number: ${pass.contact_number || 'Not provided'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Email: ${pass.email || 'Not provided'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Address: ${pass.address || 'Not provided'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Department: ${pass.department || 'Not specified'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Designation: ${pass.designation || 'Not specified'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Reporting Manager: ${pass.reporting_manager || 'Not specified'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Emergency Contact: ${pass.emergency_contact || 'Not provided'}`, 20, yPosition);
                
                if (pass.accompany && Array.isArray(pass.accompany) && pass.accompany.length > 0) {
                    yPosition += 8;
                    pass.accompany.forEach((person, index) => {
                        if (person.name) {
                            pdf.text(`Accompany ${index + 1}: ${person.name}`, 20, yPosition);
                            yPosition += 8;
                            pdf.text(`  Mobile: ${person.mobile || 'Not specified'}`, 25, yPosition);
                            yPosition += 8;
                            pdf.text(`  Email: ${person.email || 'Not specified'}`, 25, yPosition);
                            yPosition += 8;
                            pdf.text(`  Relationship: ${person.relationship || 'Not specified'}`, 25, yPosition);
                            yPosition += 8;
                        }
                    });
                } else {
                    yPosition += 8;
                    pdf.text(`Accompany By: None`, 20, yPosition);
                }
                
            } else if (pass.type === 'vehicle') {
                pdf.text(`Vehicle Number: ${pass.vehicle_number || 'Not specified'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Vehicle Type: ${pass.vehicle_type || 'Not specified'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Vehicle Model: ${pass.model || 'Not specified'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Driver Name: ${pass.driver_name || 'Not provided'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Driver Contact: ${pass.driver_contact || 'Not provided'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Driver Address: ${pass.driver_address || 'Not provided'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Driver License: ${pass.driver_license || 'Not provided'}`, 20, yPosition);
                yPosition += 8;
                
                if (pass.passengers && pass.passengers.length > 0) {
                    pdf.text('Passengers:', 20, yPosition);
                    yPosition += 8;
                    pass.passengers.forEach((passenger, index) => {
                        pdf.text(`  ${index + 1}. ${passenger.name || 'N/A'}`, 25, yPosition);
                        yPosition += 8;
                        pdf.text(`     Phone: ${passenger.phone || 'N/A'}`, 25, yPosition);
                        yPosition += 8;
                        pdf.text(`     Relation: ${passenger.relation || 'N/A'}`, 25, yPosition);
                        yPosition += 8;
                    });
                }
                
            } else if (pass.type === 'visitor') {
                pdf.text(`Visitor Name: ${pass.visitor_name || 'Not provided'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Contact Number: ${pass.visitor_contact || 'Not provided'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Address: ${pass.visitor_address || 'Not provided'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`ID Proof: ${pass.visitor_id_proof || 'Not provided'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Company/Organization: ${pass.visitor_company || 'Not provided'}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Person to Meet: ${pass.person_to_meet || 'Not specified'}`, 20, yPosition);
            }
            
            yPosition += 8;
            
            pdf.setFont("helvetica", "bold");
            pdf.text('Exit Details:', 20, yPosition);
            yPosition += 8;
            
            pdf.setFont("helvetica", "normal");
            pdf.text(`Out Date: ${pass.outDate ? formatDate(pass.outDate) : 'Not specified'}`, 20, yPosition);
            yPosition += 8;
            pdf.text(`Out Time: ${pass.outTime ? formatTime(pass.outTime) : 'Not specified'}`, 20, yPosition);
            yPosition += 8;
            pdf.text(`Destination: ${pass.destination || 'Not specified'}`, 20, yPosition);
            yPosition += 8;
            pdf.text(`Purpose: ${pass.purpose || 'Not specified'}`, 20, yPosition);
            
            yPosition += 15;
            
            pdf.setFont("helvetica", "bold");
            pdf.text(`Status: ${pass.status.toUpperCase()}`, 20, yPosition);
            
            if (pass.status === 'approved' && pass.approvedBy) {
                yPosition += 8;
                pdf.setFont("helvetica", "normal");
                pdf.text(`Approved By: ${pass.approvedBy}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Approved At: ${pass.approvedAt ? new Date(pass.approvedAt).toLocaleString() : ''}`, 20, yPosition);
            }
            
            if (pass.status === 'rejected' && pass.rejectionReason) {
                yPosition += 8;
                pdf.setFont("helvetica", "normal");
                pdf.text(`Rejection Reason: ${pass.rejectionReason}`, 20, yPosition);
            }
            
            pdf.setFillColor(240, 242, 255);
            pdf.rect(150, 60, 40, 40, 'F');
            pdf.setDrawColor(67, 97, 238);
            pdf.setLineWidth(0.5);
            pdf.rect(150, 60, 40, 40, 'S');
            
            pdf.setFontSize(8);
            pdf.text('Scan to Verify', 170, 107, { align: 'center' });
            
            pdf.setFontSize(11);
            pdf.setDrawColor(0, 0, 0);
            pdf.setLineWidth(0.2);
            
            pdf.line(30, 220, 80, 220);
            pdf.setFontSize(10);
            pdf.text('Requestor Signature', 55, 227, { align: 'center' });
            
            pdf.line(130, 220, 180, 220);
            pdf.text('Authorized Signature', 155, 227, { align: 'center' });
            
            pdf.line(80, 235, 130, 235);
            pdf.text('Date', 105, 242, { align: 'center' });
            
            pdf.setFontSize(9);
            pdf.setTextColor(100, 100, 100);
            pdf.text('This pass must be returned to security upon return', 105, 280, { align: 'center' });
            pdf.text(`Generated on: ${new Date().toLocaleString()}`, 105, 285, { align: 'center' });
            
            const fileName = `${pass.passCode}_OutPass.pdf`;
            pdf.save(fileName);
        }

        // Filter requests by status
        function filterRequests(filter) {
            const rows = document.querySelectorAll('#requestsBody tr');
            
            rows.forEach(row => {
                const statusCell = row.querySelector('td:nth-child(7)');
                if (!statusCell) return;
                
                const statusBadge = statusCell.querySelector('.status-badge');
                if (!statusBadge) return;
                
                const status = statusBadge.textContent.toLowerCase().trim();
                
                if (filter === 'all' || status === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Load history for history tab
        function loadHistory() {
            const outPasses = JSON.parse(localStorage.getItem('outPasses')) || [];
            const tbody = document.getElementById('historyBody');
            tbody.innerHTML = '';
            
            if (outPasses.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            No out pass history found
                        </td>
                    </tr>
                `;
                return;
            }
            
            outPasses.slice().reverse().forEach(pass => {
                const statusBadge = getStatusBadge(pass.status);
                let typeBadge = '';
                let displayName = pass.full_name || pass.name || pass.driver_name || 'N/A';
                switch(pass.type) {
                    case 'student':
                        typeBadge = '<span class="badge bg-success">Student</span>';
                        break;
                    case 'employee':
                        typeBadge = '<span class="badge bg-primary">Employee</span>';
                        break;
                    case 'vehicle':
                        typeBadge = '<span class="badge bg-warning text-dark">Vehicle</span>';
                        // displayName = pass.driver_name || 'N/A;
                        break;
                    case 'visitor':
                        typeBadge = '<span class="badge bg-secondary">Visitor</span>';
                        break;
                }
                console.log(displayName);
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="sticky-main-2"><strong>${pass.passCode}</strong></td>
                    <td>${displayName}</td>
                    <td>${typeBadge}</td>
                    <td>${formatDate(pass.outDate)} ${formatTime(pass.outTime)}</td>
                    <td>${pass.destination ? pass.destination.substring(0, 20) + (pass.destination.length > 20 ? '...' : '') : 'N/A'}</td>
                    <td>${pass.purpose ? pass.purpose.substring(0, 20) + (pass.purpose.length > 20 ? '...' : '') : 'N/A'}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-warning pdf-history" data-code="${pass.passCode}" title="Download PDF">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
            
            document.querySelectorAll('.pdf-history').forEach(btn => {
                btn.addEventListener('click', function() {
                    const passCode = this.getAttribute('data-code');
                    generatePDFForPass(passCode);
                });
            });
        }

        // Search history
        function searchHistory() {
            const query = document.getElementById('searchHistory').value.toLowerCase();
            const rows = document.querySelectorAll('#historyBody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Filter history by date
        function filterHistoryByDate() {
            const date = document.getElementById('filterDate').value;
            const rows = document.querySelectorAll('#historyBody tr');
            
            if (!date) {
                rows.forEach(row => row.style.display = '');
                return;
            }
            
            const filterDateStr = formatDate(date);
            
            rows.forEach(row => {
                const dateCell = row.querySelector('td:nth-child(4)');
                if (dateCell && dateCell.textContent.includes(filterDateStr)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Generate and download PDF
        function generateAndDownloadPDF() {
            const passType = document.getElementById('passType').value;
            const passCode = document.getElementById('generatedCode').textContent;
            
            if (!passType || passCode === 'OUT-XXXX-XXXX') {
                alert('Please select a pass type and fill the form first');
                return;
            }
            
            const includeSignature = document.getElementById('includeSignature').checked;
            const includeQR = document.getElementById('includeQR').checked;
            const includeWatermark = document.getElementById('includeWatermark').checked;
            
            const pdf = new jsPDF('p', 'mm', 'a4');
            
            pdf.setFont("helvetica");
            
            if (includeWatermark) {
                pdf.setTextColor(200, 200, 200);
                pdf.setFontSize(60);
                pdf.text('OUT PASS', 105, 150, { angle: 45, align: 'center' });
                pdf.setTextColor(0, 0, 0);
            }
            
            pdf.setFillColor(67, 97, 238);
            pdf.rect(0, 0, 210, 30, 'F');
            
            pdf.setTextColor(255, 255, 255);
            pdf.setFontSize(24);
            pdf.setFont("helvetica", "bold");
            pdf.text('OUT PASS SYSTEM', 105, 18, { align: 'center' });
            
            pdf.setFontSize(12);
            pdf.text(`${passType.toUpperCase()} EXIT PERMIT`, 105, 25, { align: 'center' });
            
            pdf.setTextColor(0, 0, 0);
            
            pdf.setFontSize(16);
            pdf.setFont("helvetica", "bold");
            pdf.text(`Pass Code: ${passCode}`, 20, 45);
            
            pdf.setDrawColor(67, 97, 238);
            pdf.setLineWidth(0.5);
            pdf.line(20, 50, 190, 50);
            
            pdf.setFontSize(11);
            pdf.setFont("helvetica", "normal");
            
            let yPosition = 60;
            
            if (passType === 'student') {
                const studentId = document.getElementById('studentId').value || 'Not provided';
                const name = document.getElementById('studentName').value || 'Not provided';
                const contact = document.getElementById('studentContact').value || 'Not provided';
                const address = document.getElementById('studentAddress').value || 'Not provided';
                const studentClass = document.getElementById('studentClass').value || 'Not specified';
                const section = document.getElementById('studentSection').value || 'Not specified';
                const rollNo = document.getElementById('studentRollNo').value || 'Not specified';
                const email = document.getElementById('studentEmail').value || 'Not provided';
                const parentName = document.getElementById('studentParentName').value || 'Not provided';
                const parentContact = document.getElementById('studentParentContact').value || 'Not provided';
                const parentEmail = document.getElementById('studentParentEmail')?.value || '';
                const bloodGroup = document.getElementById('studentBloodGroup')?.value || '';
                const medicalConditions = document.getElementById('studentMedicalConditions')?.value || '';
                const isHosteler = document.getElementById('studentIsHosteler')?.checked || false;
                const hostelName = document.getElementById('studentHostelName')?.value || '';
                const roomNumber = document.getElementById('studentRoomNumber')?.value || '';
                const accompanyData = getStudentAccompanyData();
                const noAccompany = document.getElementById('studentNoAccompany').checked;
                
                pdf.text(`Student ID: ${studentId}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Full Name: ${name}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Contact Number: ${contact}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Email: ${email}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Address: ${address}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Class: ${studentClass}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Section: ${section}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Roll Number: ${rollNo}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Parent Name: ${parentName}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Parent Contact: ${parentContact}`, 20, yPosition);
                
                if (parentEmail) {
                    yPosition += 8;
                    pdf.text(`Parent Email: ${parentEmail}`, 20, yPosition);
                }
                
                if (bloodGroup) {
                    yPosition += 8;
                    pdf.text(`Blood Group: ${bloodGroup}`, 20, yPosition);
                }
                
                if (medicalConditions) {
                    yPosition += 8;
                    pdf.text(`Medical Conditions: ${medicalConditions}`, 20, yPosition);
                }
                
                if (isHosteler) {
                    yPosition += 8;
                    pdf.text(`Hostel Name: ${hostelName}`, 20, yPosition);
                    yPosition += 8;
                    pdf.text(`Room Number: ${roomNumber}`, 20, yPosition);
                }
                
                if (noAccompany) {
                    yPosition += 8;
                    pdf.text(`Accompany By: None`, 20, yPosition);
                } else if (accompanyData.length > 0) {
                    yPosition += 8;
                    accompanyData.forEach((person, index) => {
                        if (person.name) {
                            pdf.text(`Accompany ${index + 1}: ${person.name}`, 20, yPosition);
                            yPosition += 8;
                            pdf.text(`  Mobile: ${person.mobile || 'Not specified'}`, 25, yPosition);
                            yPosition += 8;
                            pdf.text(`  Email: ${person.email || 'Not specified'}`, 25, yPosition);
                            yPosition += 8;
                            pdf.text(`  Relationship: ${person.relationship || 'Not specified'}`, 25, yPosition);
                            yPosition += 8;
                        }
                    });
                }
                
            } else if (passType === 'employee') {
                const employeeId = document.getElementById('employeeIdInput').value || 'Not specified';
                const name = document.getElementById('employeeName').value || 'Not provided';
                const contact = document.getElementById('employeeContact').value || 'Not provided';
                const address = document.getElementById('employeeAddress').value || 'Not provided';
                const department = document.getElementById('employeeDepartment').value || 'Not specified';
                const designation = document.getElementById('employeeDesignation').value || 'Not specified';
                const email = document.getElementById('employeeEmail').value || 'Not provided';
                const manager = document.getElementById('employeeManager').value || 'Not specified';
                const emergencyContact = document.getElementById('employeeEmergencyContact').value || 'Not provided';
                const accompanyData = getEmployeeAccompanyData();
                const noAccompany = document.getElementById('employeeNoAccompany').checked;
                
                pdf.text(`Employee ID: ${employeeId}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Employee Name: ${name}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Contact Number: ${contact}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Email: ${email}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Address: ${address}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Department: ${department}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Designation: ${designation}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Reporting Manager: ${manager}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Emergency Contact: ${emergencyContact}`, 20, yPosition);
                
                if (noAccompany) {
                    yPosition += 8;
                    pdf.text(`Accompany By: None`, 20, yPosition);
                } else if (accompanyData.length > 0) {
                    yPosition += 8;
                    accompanyData.forEach((person, index) => {
                        if (person.name) {
                            pdf.text(`Accompany ${index + 1}: ${person.name}`, 20, yPosition);
                            yPosition += 8;
                            pdf.text(`  Mobile: ${person.mobile || 'Not specified'}`, 25, yPosition);
                            yPosition += 8;
                            pdf.text(`  Email: ${person.email || 'Not specified'}`, 25, yPosition);
                            yPosition += 8;
                            pdf.text(`  Relationship: ${person.relationship || 'Not specified'}`, 25, yPosition);
                            yPosition += 8;
                        }
                    });
                }
                
            } else if (passType === 'vehicle') {
                const vehicleNumber = document.getElementById('vehicleNumber').value || 'Not specified';
                const vehicleType = document.getElementById('vehicleType').value || 'Not specified';
                const vehicleModel = document.getElementById('vehicleModel').value || 'Not specified';
                const driverName = document.getElementById('driverName').value || 'Not provided';
                const driverContact = document.getElementById('driverContact').value || 'Not provided';
                const driverAddress = document.getElementById('driverAddress').value || 'Not provided';
                const driverLicense = document.getElementById('driverLicense').value || 'Not provided';
                const passengers = getPassengerData();
                
                pdf.text(`Vehicle Number: ${vehicleNumber}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Vehicle Type: ${vehicleType}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Vehicle Model: ${vehicleModel}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Driver Name: ${driverName}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Driver Contact: ${driverContact}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Driver Address: ${driverAddress}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Driver License: ${driverLicense}`, 20, yPosition);
                yPosition += 8;
                
                if (passengers.length > 0) {
                    pdf.text('Passengers:', 20, yPosition);
                    yPosition += 8;
                    passengers.forEach((passenger, index) => {
                        pdf.text(`  ${index + 1}. ${passenger.name || 'N/A'}`, 25, yPosition);
                        yPosition += 8;
                        pdf.text(`     Phone: ${passenger.phone || 'N/A'}`, 25, yPosition);
                        yPosition += 8;
                        pdf.text(`     Relation: ${passenger.relation || 'N/A'}`, 25, yPosition);
                        yPosition += 8;
                    });
                }
                
            } else if (passType === 'visitor') {
                const name = document.getElementById('visitorName').value || 'Not provided';
                const contact = document.getElementById('visitorContact').value || 'Not provided';
                const address = document.getElementById('visitorAddress').value || 'Not provided';
                const idProof = document.getElementById('visitorIdProof').value || 'Not provided';
                const company = document.getElementById('visitorCompany').value || 'Not provided';
                const personToMeet = document.getElementById('personToMeet').value || 'Not specified';
                
                pdf.text(`Visitor Name: ${name}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Contact Number: ${contact}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Address: ${address}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`ID Proof: ${idProof}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Company/Organization: ${company}`, 20, yPosition);
                yPosition += 8;
                pdf.text(`Person to Meet: ${personToMeet}`, 20, yPosition);
            }
            
            yPosition += 8;
            
            const outDate = document.getElementById('outDate').value;
            const outTime = document.getElementById('outTime').value;
            const purpose = document.getElementById('purpose').value || 'Not specified';
            const destination = document.getElementById('destination').value || 'Not specified';
            
            pdf.setFont("helvetica", "bold");
            pdf.text('Exit Details:', 20, yPosition);
            yPosition += 8;
            
            pdf.setFont("helvetica", "normal");
            pdf.text(`Out Date: ${outDate ? formatDate(outDate) : 'Not specified'}`, 20, yPosition);
            yPosition += 8;
            pdf.text(`Out Time: ${outTime ? formatTime(outTime) : 'Not specified'}`, 20, yPosition);
            yPosition += 8;
            pdf.text(`Destination: ${destination}`, 20, yPosition);
            yPosition += 8;
            pdf.text(`Purpose: ${purpose}`, 20, yPosition);
            
            yPosition += 15;
            
            if (includeQR) {
                pdf.setFillColor(240, 242, 255);
                pdf.rect(150, 60, 40, 40, 'F');
                pdf.setDrawColor(67, 97, 238);
                pdf.setLineWidth(0.5);
                pdf.rect(150, 60, 40, 40, 'S');
                
                pdf.setFontSize(8);
                pdf.text('Scan to Verify', 170, 107, { align: 'center' });
                pdf.setFontSize(11);
            }
            
            if (includeSignature) {
                pdf.setDrawColor(0, 0, 0);
                pdf.setLineWidth(0.2);
                
                pdf.line(30, 220, 80, 220);
                pdf.setFontSize(10);
                pdf.text('Requestor Signature', 55, 227, { align: 'center' });
                
                pdf.line(130, 220, 180, 220);
                pdf.text('Authorized Signature', 155, 227, { align: 'center' });
                
                pdf.line(80, 235, 130, 235);
                pdf.text('Date', 105, 242, { align: 'center' });
            } 
            
            pdf.setFontSize(9);
            pdf.setTextColor(100, 100, 100);
            pdf.text('This pass must be returned to security upon return', 105, 280, { align: 'center' });
            pdf.text(`Generated on: ${new Date().toLocaleString()}`, 105, 285, { align: 'center' });
            
            const fileName = `${passCode}_OutPass.pdf`;
            pdf.save(fileName);
            
            alert(`PDF downloaded successfully as: ${fileName}`);
        }
    </script>
@endsection