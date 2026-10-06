@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <title>Report Card - Achievement Record</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jsPDF and html2canvas libraries -->
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
            --gradient-warning: linear-gradient(135deg, #ff9e00, #ff7b00);
            --gradient-danger: linear-gradient(135deg, #e63946, #d00000);
            --gradient-gold: linear-gradient(135deg, #FFD700, #FFA500);
            --gradient-purple: linear-gradient(135deg, #8A2BE2, #4B0082);
        }
        
        
        .report-card-container {
            /*margin: 30px auto;*/
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid #e0e0e0;
        }
        
        .header-section {
            background: var(--gradient-primary);
            color: white;
            padding: 20px 25px;
            text-align: center;
            position: relative;
        }
        
        .header-section h1 {
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }
        
        .header-section .school-info {
            font-size: 0.85rem;
            opacity: 0.9;
            margin-bottom: 3px;
            line-height: 1.2;
        }
        
        .header-section .session {
            background-color: rgba(255, 255, 255, 0.15);
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 600;
            margin-top: 8px;
            font-size: 0.95rem;
        }
        
        .student-info {
            padding: 20px 25px;
            background-color: white;
        }
        
        .student-info h3 {
            color: var(--secondary-color);
            font-weight: 700;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 8px;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        
        .info-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--secondary-color);
            width: 140px;
            min-width: 140px;
        }
        
        .info-value {
            color: #555;
        }
        
        .table-section {
            padding: 0 15px 20px 15px;
        }
        
        .table-section h4 {
            color: var(--secondary-color);
            font-weight: 700;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 1px solid #eaeaea;
            font-size: 1.2rem;
        }
        
        .table-responsive {
            overflow-x: auto;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }
        
        .scholastic-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1100px;
            font-size: 0.85rem;
        }
        
        .scholastic-table th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            text-align: center;
            padding: 8px 5px;
            border: 1px solid #ddd;
            white-space: nowrap;
            vertical-align: middle;
        }
        
        .scholastic-table td {
            text-align: center;
            padding: 8px 5px;
            border: 1px solid #ddd;
            vertical-align: middle;
        }
        
        .scholastic-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .scholastic-table tr:hover {
            background-color: #f0f5ff;
        }
        
        .subject-cell {
            font-weight: 600;
            text-align: left !important;
            color: var(--secondary-color);
            background-color: #f8f9ff;
            min-width: 100px;
            padding-left: 10px !important;
        }
        
        .total-row {
            background-color: #e8f4ff !important;
            font-weight: 600;
        }
        
        .compact-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }
        
        .compact-table th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            text-align: center;
            padding: 8px 5px;
            border: 1px solid #ddd;
            vertical-align: middle;
        }
        
        .compact-table td {
            text-align: center;
            padding: 8px 5px;
            border: 1px solid #ddd;
            vertical-align: middle;
        }
        
        .compact-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .compact-table tr:hover {
            background-color: #f0f5ff;
        }
        
        .grading-scale {
            background-color: #f8f9ff;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            border-left: 4px solid var(--primary-color);
        }
        
        .grading-scale h5 {
            color: var(--secondary-color);
            font-weight: 700;
            margin-bottom: 8px;
            font-size: 1rem;
        }
        
        .grade-box {
            display: inline-block;
            padding: 4px 8px;
            margin: 3px;
            border-radius: 4px;
            font-weight: 600;
            color: white;
            text-align: center;
            min-width: 60px;
            font-size: 0.8rem;
        }
        
        .grade-a1 { background: var(--gradient-gold); }
        .grade-a2 { background: var(--gradient-purple); }
        .grade-b1 { background: var(--gradient-primary); }
        .grade-b2 { background: linear-gradient(135deg, #5a67d8, #4c51bf); }
        .grade-c1 { background: var(--gradient-success); }
        .grade-c2 { background: var(--gradient-warning); }
        .grade-d { background: #aaa; }
        .grade-e { background: var(--gradient-danger); }
        
        .footer-section {
            padding: 20px 25px;
            background-color: #f8f9ff;
            border-top: 1px solid #e0e0e0;
        }
        
        .summary-box {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #eaeaea;
            font-size: 0.9rem;
        }
        
        .summary-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .summary-label {
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .summary-value {
            font-weight: 700;
            font-size: 1rem;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
        }
        
        .signature-box {
            text-align: center;
            padding-top: 15px;
            width: 180px;
            font-size: 0.9rem;
        }
        
        .signature-line {
            border-top: 1px solid #555;
            width: 130px;
            margin: 0 auto 5px auto;
            padding-top: 8px;
        }
        
        .controls-section {
            padding: 15px 25px;
            background-color: #f0f5ff;
            border-top: 1px solid #ddd;
        }
        
        .color-picker {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }
        
        .color-option {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .color-option.active {
            border: 2px solid #333;
            transform: scale(1.1);
        }
        
        .table-caption {
            font-size: 0.8rem;
            color: #666;
            text-align: center;
            margin-top: 5px;
            font-style: italic;
        }
        
        .term-header {
            background-color: rgba(58, 12, 163, 0.1) !important;
            color: var(--secondary-color) !important;
            font-weight: 700;
        }
        
        /* Loading Spinner */
        .loading-spinner {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* PDF specific styles */
        @media print {
            .controls-section, .no-print, .feedback-message, .loading-spinner {
                display: none !important;
            }
            
            .report-card-container {
                box-shadow: none;
                margin: 0;
                border-radius: 0;
                width: 100%;
                max-width: 100%;
            }
            
            .table-responsive {
                overflow-x: visible !important;
            }
            
            body {
                background-color: white;
                padding: 0;
                margin: 0;
            }
        }
        
        /* PDF Preview Styles */
        .pdf-preview-mode {
            width: 794px !important; /* A4 width in pixels at 96 DPI */
            height: 1123px !important; /* A4 height in pixels at 96 DPI */
            margin: 20px auto !important;
            padding: 40px !important;
            box-shadow: 0 0 20px rgba(0,0,0,0.1) !important;
            border-radius: 0 !important;
            transform-origin: top center !important;
            transform: scale(0.8) !important;
            overflow: visible !important;
            background: white !important;
            position: relative !important;
        }
        
        .pdf-preview-mode .header-section {
            padding: 15px 20px !important;
        }
        
        .pdf-preview-mode .student-info,
        .pdf-preview-mode .table-section,
        .pdf-preview-mode .footer-section {
            padding: 15px 20px !important;
        }
        
        .pdf-preview-mode .scholastic-table {
            font-size: 0.75rem !important;
            min-width: 900px !important;
        }
        
        .pdf-preview-mode .compact-table {
            font-size: 0.75rem !important;
        }
        
        .pdf-preview-mode .grade-box {
            font-size: 0.7rem !important;
            padding: 2px 4px !important;
            min-width: 45px !important;
            margin: 1px !important;
        }
        
        .pdf-preview-mode .info-row {
            font-size: 0.8rem !important;
            margin-bottom: 4px !important;
        }
        
        .pdf-preview-mode h3 {
            font-size: 1.1rem !important;
        }
        
        .pdf-preview-mode h4 {
            font-size: 1rem !important;
        }
        
        .pdf-preview-mode .grading-scale {
            padding: 8px !important;
            margin-bottom: 15px !important;
        }
        
        .pdf-preview-mode .summary-box {
            padding: 10px !important;
        }
        
        .pdf-preview-mode .signature-box {
            width: 150px !important;
        }
        
        @media (max-width: 768px) {
            .report-card-container {
                margin: 10px;
                border-radius: 8px;
            }
            
            .header-section, .student-info, .footer-section {
                padding: 15px;
            }
            
            .header-section h1 {
                font-size: 1.5rem;
            }
            
            .info-label {
                width: 120px;
                min-width: 120px;
            }
            
            .signature-section {
                flex-direction: column;
                gap: 15px;
            }
            
            .signature-box {
                width: 100%;
            }
            
            .table-section {
                padding: 0 10px 15px 10px;
            }
        }
        
        @media (max-width: 480px) {
            body {
                font-size: 0.85rem;
            }
            
            .header-section h1 {
                font-size: 1.3rem;
            }
            
            .info-label {
                width: 110px;
                min-width: 110px;
            }
            
            .grade-box {
                min-width: 50px;
                font-size: 0.75rem;
                padding: 3px 6px;
            }
        }
    </style>

    <!-- Loading Spinner -->
    <div class="loading-spinner" id="loadingSpinner">
        <div class="spinner"></div>
    </div>

    <div class="container-fluid">
        <!-- Report Card Selector -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-search me-2"></i>Select Student Report Card</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="studentSelect" class="form-label">Select Student</label>
                        <select class="form-select" id="studentSelect">
                            <option value="">-- Select a Student --</option>
                            <!-- Will be populated dynamically -->
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="academicYearSelect" class="form-label">Academic Year</label>
                        <select class="form-select" id="academicYearSelect">
                            <option value="">-- Select Academic Year --</option>
                            <!-- Will be populated dynamically -->
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="examTypeSelect" class="form-label">Exam Type</label>
                        <select class="form-select" id="examTypeSelect">
                            <option value="">-- Select Exam Type --</option>
                            <option value="annual">Annual Examination</option>
                            <option value="mid_term">Mid Term</option>
                            <option value="final">Final Term</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-success" id="loadReportBtn">
                        <i class="fas fa-file-alt me-2"></i>Load Report Card
                    </button>
                    <button class="btn btn-secondary" id="resetBtn">
                        <i class="fas fa-redo me-2"></i>Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Report Card Container -->
        <div class="container- fluid report-card-container" id="report-card-content" style="display: none;">
            <!-- Header Section -->
            <div class="header-section">
                <h1 id="school-name">ANEE'S SCHOOL</h1>
                <div class="school-info" id="school-affiliation">(AFFILIATED TO CBSE)</div>
                <div class="school-info" id="school-address">SHIVJOT ENCLAVE, KHARAR, DISTT. MOHALI</div>
                <div class="school-info" id="school-contact">PH: 75270-66620, 75270-66621 | E-MAIL: anees.school@yahoo.com</div>
                <div class="session">Session: <span id="session-year">2024-2025</span></div>
                <h3 class="mt-3 mb-0">Achievement Record - Annual Report Card</h3>
            </div>
            
            <!-- Student Information -->
            <div class="student-info">
                <h3>Student Information</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Name:</div>
                            <div class="info-value" id="student-name">--</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Father's Name:</div>
                            <div class="info-value" id="father-name">--</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Mother's Name:</div>
                            <div class="info-value" id="mother-name">--</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Class-Section:</div>
                            <div class="info-value" id="class-section">--</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Admission No.:</div>
                            <div class="info-value" id="admn-no">--</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Date of Birth:</div>
                            <div class="info-value" id="dob">--</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Roll No.:</div>
                            <div class="info-value" id="roll-no">--</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Mobile No.:</div>
                            <div class="info-value" id="mobile-no">--</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Scholastic Area Table -->
            <div class="table-section">
                <h4>Scholastic Area</h4>
                <div class="table-responsive">
                    <table class="scholastic-table">
                        <thead>
                            <tr>
                                <th rowspan="2">Subject</th>
                                <th colspan="6" class="term-header">Term I</th>
                                <th colspan="6" class="term-header">Term II</th>
                                <th rowspan="2">Marks (200)</th>
                                <th rowspan="2">Grade</th>
                            </tr>
                            <tr>
                                <th>PT (10)</th>
                                <th>NB (5)</th>
                                <th>SE (5)</th>
                                <th>Term I (80)</th>
                                <th>Total (100)</th>
                                <th>Grade</th>
                                <th>PT (10)</th>
                                <th>NB (5)</th>
                                <th>SE (5)</th>
                                <th>Term II (80)</th>
                                <th>Total (100)</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody id="scholastic-table-body">
                            <!-- Will be populated dynamically -->
                            <tr>
                                <td colspan="16" class="text-center py-4">No scholastic data available</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-caption">PT: Periodic Test, NB: Notebook, SE: Subject Enrichment</div>
                
                <!-- Grading Subjects -->
                <h4 class="mt-4">Grading Subjects</h4>
                <div class="table-responsive">
                    <table class="compact-table">
                        <thead>
                            <tr>
                                <th rowspan="2">Subject</th>
                                <th colspan="2">Term 1</th>
                                <th colspan="2">Term 2</th>
                            </tr>
                            <tr>
                                <th>Grade</th>
                                <th>Grade</th>
                                <th>Grade</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody id="grading-table-body">
                            <!-- Will be populated dynamically -->
                            <tr>
                                <td colspan="5" class="text-center py-3">No grading subjects data available</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Co-Scholastic Areas -->
                <h4 class="mt-4">Co-Scholastic Areas</h4>
                <div class="grading-scale">
                    <h5>3-Point Grading Scale (A-C)</h5>
                    <div class="d-flex flex-wrap">
                        <div class="grade-box" style="background: var(--gradient-success);">A (Outstanding)</div>
                        <div class="grade-box" style="background: var(--gradient-warning);">B (Good)</div>
                        <div class="grade-box" style="background: #aaa;">C (Satisfactory)</div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="compact-table">
                        <thead>
                            <tr>
                                <th rowspan="2">Area</th>
                                <th colspan="2">Term 1</th>
                                <th colspan="2">Term 2</th>
                            </tr>
                            <tr>
                                <th>Grade</th>
                                <th>Grade</th>
                                <th>Grade</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody id="co-scholastic-table-body">
                            <!-- Will be populated dynamically -->
                            <tr>
                                <td colspan="5" class="text-center py-3">No co-scholastic data available</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Grading Scale -->
                <h4 class="mt-4">Scholastic Area Grading Scale</h4>
                <div class="grading-scale">
                    <h5>Percentage Range & Grades</h5>
                    <div class="d-flex flex-wrap">
                        <div class="grade-box grade-a1">91-100: A1</div>
                        <div class="grade-box grade-a2">81-90: A2</div>
                        <div class="grade-box grade-b1">71-80: B1</div>
                        <div class="grade-box grade-b2">61-70: B2</div>
                        <div class="grade-box grade-c1">51-60: C1</div>
                        <div class="grade-box grade-c2">41-50: C2</div>
                        <div class="grade-box grade-d">33-40: D</div>
                        <div class="grade-box grade-e">32 & Below: E</div>
                    </div>
                </div>
            </div>
            
            <!-- Footer Section -->
            <div class="footer-section">
                <div class="summary-box">
                    <div class="summary-item">
                        <div class="summary-label">Total Marks:</div>
                        <div class="summary-value" id="total-marks">--</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Marks Obtained:</div>
                        <div class="summary-value" id="marks-obtained">--</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Percentage:</div>
                        <div class="summary-value" id="percentage">--</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Grade:</div>
                        <div class="summary-value" id="overall-grade">--</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Attendance:</div>
                        <div class="summary-value" id="attendance">--</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Remark:</div>
                        <div class="summary-value" id="remark">--</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Promoted to:</div>
                        <div class="summary-value" id="promoted-to">--</div>
                    </div>
                </div>
                
                <div class="signature-section">
                    <div class="signature-box">
                        <div>Date: <span id="current-date">--</span></div>
                        <div class="signature-line"></div>
                        <div>Class Teacher</div>
                    </div>
                    <div class="signature-box">
                        <div style="visibility: hidden;">Signature</div>
                        <div class="signature-line"></div>
                        <div>Principal</div>
                    </div>
                </div>
            </div>
        </div>
            
        <!-- Controls Section -->
        <div class="controls-section no-print" style="display: none;">
            <h5>Customize Report Card</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Change Theme Color:</label>
                        <div class="color-picker">
                            <div class="color-option active" style="background: var(--primary-color);" data-color="primary"></div>
                            <div class="color-option" style="background: var(--success-color);" data-color="success"></div>
                            <div class="color-option" style="background: var(--warning-color);" data-color="warning"></div>
                            <div class="color-option" style="background: var(--danger-color);" data-color="danger"></div>
                            <div class="color-option" style="background: #8A2BE2;" data-color="purple"></div>
                            <div class="color-option" style="background: #2E8B57;" data-color="green"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="studentNameInput" class="form-label">Customize Student Name:</label>
                        <input type="text" class="form-control form-control-sm" id="studentNameInput" placeholder="Enter custom name">
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-primary btn-sm" id="update-btn">
                    <i class="fas fa-sync-alt me-2"></i>Update Report Card
                </button>
                <button class="btn btn-warning btn-sm" id="print-btn">
                    <i class="fas fa-print me-2"></i>Print Report Card
                </button>
                <button class="btn btn-danger btn-sm" id="pdf-btn">
                    <i class="fas fa-file-pdf me-2"></i>Download as PDF
                </button>
                <button class="btn btn-info btn-sm" id="single-page-pdf-btn">
                    <i class="fas fa-file-alt me-2"></i>Single Page PDF
                </button>
                <button class="btn btn-secondary btn-sm" id="preview-btn">
                    <i class="fas fa-eye me-2"></i>Preview PDF
                </button>
            </div>
            <div class="mt-3">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="pdf-watermark" checked>
                    <label class="form-check-label" for="pdf-watermark">Add "OFFICIAL COPY" watermark</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="pdf-stamp" checked>
                    <label class="form-check-label" for="pdf-stamp">Add "SCHOOL SEAL" stamp</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="pdf-compact" checked>
                    <label class="form-check-label" for="pdf-compact">Compact PDF Mode</label>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize jsPDF
        const { jsPDF } = window.jspdf;
        
        // Store current report data
        let currentReportData = null;
        
        // Initialize the report card when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Set current date
            const today = new Date();
            const formattedDate = today.toLocaleDateString('en-GB');
            document.getElementById('current-date').textContent = formattedDate;
            
            // Load initial data
            loadInitialData();
            
            // Set up event listeners
            setupEventListeners();
        });

        // Load initial data (students and academic years)
        function loadInitialData() {
            showLoading(true);
            
            // Fetch students list from backend
            fetchStudents();
            
            // Fetch academic years from backend
            fetchAcademicYears();
        }

        // Fetch students list from backend
        function fetchStudents() {
            // This should be replaced with actual API endpoint
            // Example: fetch('/api/students')
            // For now, using mock data
            
            // Simulate API call delay
            setTimeout(() => {
                const mockStudents = [
                    { id: 1, name: "Aarav Sharma", roll_no: "12", class: "X-A" },
                    { id: 2, name: "Anaya Singh", roll_no: "15", class: "X-B" },
                    { id: 3, name: "Rohan Verma", roll_no: "8", class: "IX-A" },
                    { id: 4, name: "Priya Patel", roll_no: "22", class: "XI-Science" },
                    { id: 5, name: "Karan Mehta", roll_no: "5", class: "XII-Commerce" }
                ];
                
                const studentSelect = document.getElementById('studentSelect');
                mockStudents.forEach(student => {
                    const option = document.createElement('option');
                    option.value = student.id;
                    option.textContent = `${student.name} (Roll No: ${student.roll_no}, Class: ${student.class})`;
                    studentSelect.appendChild(option);
                });
                
                showLoading(false);
            }, 1000);
        }

        // Fetch academic years from backend
        function fetchAcademicYears() {
            // This should be replaced with actual API endpoint
            // Example: fetch('/api/academic-years')
            
            // Simulate API call delay
            setTimeout(() => {
                const mockYears = [
                    { id: 1, year: "2024-2025" },
                    { id: 2, year: "2023-2024" },
                    { id: 3, year: "2022-2023" },
                    { id: 4, year: "2021-2022" }
                ];
                
                const yearSelect = document.getElementById('academicYearSelect');
                mockYears.forEach(year => {
                    const option = document.createElement('option');
                    option.value = year.id;
                    option.textContent = year.year;
                    yearSelect.appendChild(option);
                });
            }, 500);
        }

        // Load report card data from backend
        function loadReportCard() {
            const studentId = document.getElementById('studentSelect').value;
            const academicYearId = document.getElementById('academicYearSelect').value;
            const examType = document.getElementById('examTypeSelect').value;
            
            if (!studentId || !academicYearId || !examType) {
                showFeedback('Please select all options before loading report card.', 'warning');
                return;
            }
            
            showLoading(true);
            
            // This should be replaced with actual API endpoint
            // Example: fetch(`/api/report-card/${studentId}/${academicYearId}/${examType}`)
            
            // Simulate API call delay with mock data
            setTimeout(() => {
                // Mock data structure matching backend response
                const mockReportData = {
                    school: {
                        name: "ANEE'S SCHOOL",
                        affiliation: "(AFFILIATED TO CBSE)",
                        address: "SHIVJOT ENCLAVE, KHARAR, DISTT. MOHALI",
                        contact: "PH: 75270-66620, 75270-66621 | E-MAIL: anees.school@yahoo.com"
                    },
                    student: {
                        name: "Aarav Sharma",
                        fatherName: "Rajesh Sharma",
                        motherName: "Priya Sharma",
                        classSection: "X-A",
                        admnNo: "2020-5678",
                        dob: "15/08/2009",
                        rollNo: "12",
                        mobileNo: "98765-43210"
                    },
                    scholastic: [
                        { subject: "English", term1: { test: 9, notebook: 5, enrichment: 4, term: 72, total: 90, grade: "A1" }, 
                          term2: { test: 8, notebook: 4, enrichment: 5, term: 75, total: 92, grade: "A1" }, 
                          totalMarks: 182, finalGrade: "A1" },
                        { subject: "Hindi", term1: { test: 8, notebook: 4, enrichment: 4, term: 68, total: 84, grade: "A2" }, 
                          term2: { test: 9, notebook: 5, enrichment: 4, term: 70, total: 88, grade: "A2" }, 
                          totalMarks: 172, finalGrade: "A2" },
                        { subject: "Punjabi", term1: { test: 7, notebook: 3, enrichment: 4, term: 65, total: 79, grade: "B1" }, 
                          term2: { test: 8, notebook: 4, enrichment: 4, term: 68, total: 84, grade: "A2" }, 
                          totalMarks: 163, finalGrade: "B1" },
                        { subject: "Maths", term1: { test: 10, notebook: 5, enrichment: 5, term: 78, total: 98, grade: "A1" }, 
                          term2: { test: 9, notebook: 5, enrichment: 5, term: 76, total: 95, grade: "A1" }, 
                          totalMarks: 193, finalGrade: "A1" },
                        { subject: "Science", term1: { test: 9, notebook: 4, enrichment: 4, term: 70, total: 87, grade: "A2" }, 
                          term2: { test: 8, notebook: 5, enrichment: 4, term: 72, total: 89, grade: "A2" }, 
                          totalMarks: 176, finalGrade: "A2" },
                        { subject: "Social Studies", term1: { test: 8, notebook: 4, enrichment: 3, term: 65, total: 80, grade: "B1" }, 
                          term2: { test: 7, notebook: 4, enrichment: 4, term: 68, total: 83, grade: "A2" }, 
                          totalMarks: 163, finalGrade: "B1" },
                        { subject: "Computer", term1: { test: 10, notebook: 5, enrichment: 5, term: 75, total: 95, grade: "A1" }, 
                          term2: { test: 10, notebook: 5, enrichment: 5, term: 78, total: 98, grade: "A1" }, 
                          totalMarks: 193, finalGrade: "A1" }
                    ],
                    gradingSubjects: [
                        { subject: "Art", term1Grade: "A", term2Grade: "A" },
                        { subject: "GK", term1Grade: "B", term2Grade: "A" }
                    ],
                    coScholastic: [
                        { area: "Work Education", term1Grade: "A", term2Grade: "A" },
                        { area: "Art Education", term1Grade: "A", term2Grade: "B" },
                        { area: "Health & Physical Education", term1Grade: "B", term2Grade: "A" },
                        { area: "Sports", term1Grade: "A", term2Grade: "A" },
                        { area: "Music & Dance", term1Grade: "B", term2Grade: "B" },
                        { area: "Discipline", term1Grade: "A", term2Grade: "A" }
                    ],
                    summary: {
                        totalMarks: 800,
                        marksObtained: 685,
                        percentage: "85.63%",
                        overallGrade: "A2",
                        attendance: "94%",
                        remark: "Excellent Performance",
                        promotedTo: "XI-A (Science)"
                    },
                    sessionYear: "2024-2025"
                };
                
                currentReportData = mockReportData;
                
                // Populate the report card with data
                populateReportCard(mockReportData);
                
                // Show report card and controls
                document.getElementById('report-card-content').style.display = 'block';
                document.querySelector('.controls-section').style.display = 'block';
                
                showLoading(false);
                showFeedback('Report card loaded successfully!', 'success');
                
                // Scroll to report card
                document.getElementById('report-card-content').scrollIntoView({ behavior: 'smooth' });
                
            }, 1500);
        }

        // Populate report card with data
        function populateReportCard(data) {
            // Populate school information
            document.getElementById('school-name').textContent = data.school.name;
            document.getElementById('school-affiliation').textContent = data.school.affiliation;
            document.getElementById('school-address').textContent = data.school.address;
            document.getElementById('school-contact').textContent = data.school.contact;
            document.getElementById('session-year').textContent = data.sessionYear;
            
            // Populate student information
            populateStudentInfo(data.student);
            
            // Populate tables
            populateScholasticTable(data.scholastic);
            populateGradingTable(data.gradingSubjects);
            populateCoScholasticTable(data.coScholastic);
            
            // Populate summary
            populateSummary(data.summary);
            
            // Set custom name input
            document.getElementById('studentNameInput').value = data.student.name;
        }

        // Populate student information
        function populateStudentInfo(student) {
            document.getElementById('student-name').textContent = student.name;
            document.getElementById('father-name').textContent = student.fatherName;
            document.getElementById('mother-name').textContent = student.motherName;
            document.getElementById('class-section').textContent = student.classSection;
            document.getElementById('admn-no').textContent = student.admnNo;
            document.getElementById('dob').textContent = student.dob;
            document.getElementById('roll-no').textContent = student.rollNo;
            document.getElementById('mobile-no').textContent = student.mobileNo;
        }

        // Populate scholastic table
        function populateScholasticTable(scholasticData) {
            const tableBody = document.getElementById('scholastic-table-body');
            tableBody.innerHTML = '';
            
            if (!scholasticData || scholasticData.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="16" class="text-center py-4">No scholastic data available</td>
                    </tr>
                `;
                return;
            }
            
            let totalMarks = 0;
            
            scholasticData.forEach(item => {
                const row = document.createElement('tr');
                
                // Calculate row total
                const rowTotal = item.term1.total + item.term2.total;
                totalMarks += rowTotal;
                
                // Add grade class for final grade
                const finalGradeClass = getGradeClass(item.finalGrade);
                
                row.innerHTML = `
                    <td class="subject-cell">${item.subject}</td>
                    <td>${item.term1.test}</td>
                    <td>${item.term1.notebook}</td>
                    <td>${item.term1.enrichment}</td>
                    <td>${item.term1.term}</td>
                    <td>${item.term1.total}</td>
                    <td><span class="grade-box ${getGradeClass(item.term1.grade)}">${item.term1.grade}</span></td>
                    <td>${item.term2.test}</td>
                    <td>${item.term2.notebook}</td>
                    <td>${item.term2.enrichment}</td>
                    <td>${item.term2.term}</td>
                    <td>${item.term2.total}</td>
                    <td><span class="grade-box ${getGradeClass(item.term2.grade)}">${item.term2.grade}</span></td>
                    <td>${item.totalMarks}</td>
                    <td><span class="grade-box ${finalGradeClass}">${item.finalGrade}</span></td>
                `;
                
                tableBody.appendChild(row);
            });
            
            // Add total row
            const totalRow = document.createElement('tr');
            totalRow.className = 'total-row';
            totalRow.innerHTML = `
                <td class="subject-cell">Total</td>
                <td colspan="5"></td>
                <td></td>
                <td colspan="5"></td>
                <td></td>
                <td>${totalMarks}</td>
                <td></td>
            `;
            tableBody.appendChild(totalRow);
        }

        // Populate grading subjects table
        function populateGradingTable(gradingSubjects) {
            const tableBody = document.getElementById('grading-table-body');
            tableBody.innerHTML = '';
            
            if (!gradingSubjects || gradingSubjects.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-3">No grading subjects data available</td>
                    </tr>
                `;
                return;
            }
            
            gradingSubjects.forEach(item => {
                const row = document.createElement('tr');
                
                row.innerHTML = `
                    <td class="subject-cell">${item.subject}</td>
                    <td><span class="grade-box ${getCoGradeClass(item.term1Grade)}">${item.term1Grade}</span></td>
                    <td><span class="grade-box ${getCoGradeClass(item.term1Grade)}">${item.term1Grade}</span></td>
                    <td><span class="grade-box ${getCoGradeClass(item.term2Grade)}">${item.term2Grade}</span></td>
                    <td><span class="grade-box ${getCoGradeClass(item.term2Grade)}">${item.term2Grade}</span></td>
                `;
                
                tableBody.appendChild(row);
            });
        }

        // Populate co-scholastic table
        function populateCoScholasticTable(coScholastic) {
            const tableBody = document.getElementById('co-scholastic-table-body');
            tableBody.innerHTML = '';
            
            if (!coScholastic || coScholastic.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-3">No co-scholastic data available</td>
                    </tr>
                `;
                return;
            }
            
            coScholastic.forEach(item => {
                const row = document.createElement('tr');
                
                row.innerHTML = `
                    <td class="subject-cell">${item.area}</td>
                    <td><span class="grade-box ${getCoGradeClass(item.term1Grade)}">${item.term1Grade}</span></td>
                    <td><span class="grade-box ${getCoGradeClass(item.term1Grade)}">${item.term1Grade}</span></td>
                    <td><span class="grade-box ${getCoGradeClass(item.term2Grade)}">${item.term2Grade}</span></td>
                    <td><span class="grade-box ${getCoGradeClass(item.term2Grade)}">${item.term2Grade}</span></td>
                `;
                
                tableBody.appendChild(row);
            });
        }

        // Populate summary section
        function populateSummary(summary) {
            document.getElementById('total-marks').textContent = summary.totalMarks;
            document.getElementById('marks-obtained').textContent = summary.marksObtained;
            document.getElementById('percentage').textContent = summary.percentage;
            document.getElementById('overall-grade').textContent = summary.overallGrade;
            document.getElementById('attendance').textContent = summary.attendance;
            document.getElementById('remark').textContent = summary.remark;
            document.getElementById('promoted-to').textContent = summary.promotedTo;
        }

        // Get grade class for styling
        function getGradeClass(grade) {
            switch(grade) {
                case 'A1': return 'grade-a1';
                case 'A2': return 'grade-a2';
                case 'B1': return 'grade-b1';
                case 'B2': return 'grade-b2';
                case 'C1': return 'grade-c1';
                case 'C2': return 'grade-c2';
                case 'D': return 'grade-d';
                case 'E': return 'grade-e';
                default: return '';
            }
        }

        // Get co-scholastic grade class
        function getCoGradeClass(grade) {
            switch(grade) {
                case 'A': return 'grade-a1';
                case 'B': return 'grade-c1';
                case 'C': return 'grade-d';
                default: return '';
            }
        }

        // Set up event listeners   
        function setupEventListeners() {
            // Load Report Button
            document.getElementById('loadReportBtn').addEventListener('click', loadReportCard);
            
            // Reset Button
            document.getElementById('resetBtn').addEventListener('click', function() {
                document.getElementById('studentSelect').value = '';
                document.getElementById('academicYearSelect').value = '';
                document.getElementById('examTypeSelect').value = '';
                document.getElementById('report-card-content').style.display = 'none';
                document.querySelector('.controls-section').style.display = 'none';
                showFeedback('Selections reset. Please select new criteria.', 'info');
            });
            
            // Color picker
            const colorOptions = document.querySelectorAll('.color-option');
            colorOptions.forEach(option => {
                option.addEventListener('click', function() {
                    colorOptions.forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');
                    
                    const color = this.getAttribute('data-color');
                    changeThemeColor(color);
                });
            });
            
            // Update button
            document.getElementById('update-btn').addEventListener('click', function() {
                const newName = document.getElementById('studentNameInput').value;
                if (newName.trim() !== '' && currentReportData) {
                    currentReportData.student.name = newName;
                    document.getElementById('student-name').textContent = newName;
                }
                
                showFeedback('Report card updated successfully!', 'success');
            });
            
            // Print button
            document.getElementById('print-btn').addEventListener('click', function() {
                window.print();
            });
            
            // PDF Download button
            document.getElementById('pdf-btn').addEventListener('click', generatePDF);
            
            // Single Page PDF button
            document.getElementById('single-page-pdf-btn').addEventListener('click', generateSinglePagePDF);
            
            // Preview button
            document.getElementById('preview-btn').addEventListener('click', togglePDFPreview);
        }

        // Change theme color 
        function changeThemeColor(color) {
            let primaryColor, secondaryColor;
            
            switch(color) {
                case 'success':
                    primaryColor = '#4bb543';
                    secondaryColor = '#2a9d40';
                    break;
                case 'warning':
                    primaryColor = '#ff9e00';
                    secondaryColor = '#ff7b00';
                    break;
                case 'danger':
                    primaryColor = '#e63946';
                    secondaryColor = '#d00000';
                    break;
                case 'purple':
                    primaryColor = '#8A2BE2';
                    secondaryColor = '#4B0082';
                    break;
                case 'green':
                    primaryColor = '#2E8B57';
                    secondaryColor = '#1B5E20';
                    break;
                default: // primary
                    primaryColor = '#4361ee';
                    secondaryColor = '#3a0ca3';
            }
            
            // Update CSS custom properties
            document.documentElement.style.setProperty('--primary-color', primaryColor);
            document.documentElement.style.setProperty('--secondary-color', secondaryColor);
            document.documentElement.style.setProperty('--gradient-primary', `linear-gradient(135deg, ${primaryColor}, ${secondaryColor})`);
            
            // Update header gradient
            document.querySelector('.header-section').style.background = `linear-gradient(135deg, ${primaryColor}, ${secondaryColor})`;
            
            showFeedback(`Theme changed to ${color} color`, 'info');
        }

        // Calculate grade based on percentage
        function calculateGrade(percentage) {
            if (percentage >= 91) return 'A1';
            if (percentage >= 81) return 'A2';
            if (percentage >= 71) return 'B1';
            if (percentage >= 61) return 'B2';
            if (percentage >= 51) return 'C1';
            if (percentage >= 41) return 'C2';
            if (percentage >= 33) return 'D';
            return 'E';
        }

        // Show/hide loading spinner
        function showLoading(show) {
            document.getElementById('loadingSpinner').style.display = show ? 'flex' : 'none';
        }

        // Show feedback message
        function showFeedback(message, type) {
            // Remove existing feedback
            const existingFeedback = document.querySelector('.feedback-message');
            if (existingFeedback) {
                existingFeedback.remove();
            }
            
            // Create new feedback element
            const feedback = document.createElement('div');
            feedback.className = `feedback-message alert alert-${type} alert-dismissible fade show`;
            feedback.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            // Add to controls section
            const controlsSection = document.querySelector('.controls-section');
            if (controlsSection.style.display !== 'none') {
                controlsSection.prepend(feedback);
            } else {
                // Add to the card body if controls are not visible
                document.querySelector('.card-body').appendChild(feedback);
            }
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                if (feedback.parentNode) {
                    feedback.remove();
                }
            }, 3000);
        }

        // Toggle PDF preview mode
        function togglePDFPreview() {
            const reportCardElement = document.getElementById('report-card-content');
            
            if (reportCardElement.classList.contains('pdf-preview-mode')) {
                // Exit preview mode
                reportCardElement.classList.remove('pdf-preview-mode');
                document.getElementById('preview-btn').innerHTML = '<i class="fas fa-eye me-2"></i>Preview PDF';
                showFeedback('Exited PDF preview mode', 'info');
            } else {
                // Enter preview mode
                reportCardElement.classList.add('pdf-preview-mode');
                document.getElementById('preview-btn').innerHTML = '<i class="fas fa-times me-2"></i>Exit Preview';
                showFeedback('Entered PDF preview mode. Scroll to see all content.', 'info');
                
                // Scroll to top
                window.scrollTo(0, 0);
            }
        }

        // Generate PDF from report card (with proper pagination)
        function generatePDF() {
            if (!currentReportData) {
                showFeedback('Please load a report card first.', 'warning');
                return;
            }
            
            showFeedback('Generating PDF... Please wait.', 'info');
            
            const reportCardElement = document.getElementById('report-card-content');
            const originalClass = reportCardElement.className;
            const originalControls = document.querySelector('.controls-section');
            const originalControlsDisplay = originalControls.style.display;
            
            // Apply PDF preview mode temporarily
            reportCardElement.classList.add('pdf-preview-mode');
            originalControls.style.display = 'none';
            
            // Wait for DOM to update
            setTimeout(() => {
                const pdf = new jsPDF('p', 'mm', 'a4');
                const pageWidth = pdf.internal.pageSize.getWidth();
                const pageHeight = pdf.internal.pageSize.getHeight();
                
                const addWatermark = document.getElementById('pdf-watermark').checked;
                const addStamp = document.getElementById('pdf-stamp').checked;
                
                html2canvas(reportCardElement, {
                    scale: 2,
                    useCORS: true,
                    logging: false,
                    backgroundColor: '#ffffff',
                    width: 794, // A4 width in pixels
                    height: reportCardElement.scrollHeight,
                    windowWidth: 794
                }).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const imgWidth = pageWidth - 20; // 10mm margin on each side
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;
                    
                    let heightLeft = imgHeight;
                    let position = 10; // Start 10mm from top
                    let page = 1;
                    
                    // Add first page
                    pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                    
                    // Add additional pages if content is too long
                    while (heightLeft > pageHeight - 20) {
                        position = position - pageHeight + 20;
                        pdf.addPage();
                        page++;
                        // Add remaining content to new page
                        pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight - 20;
                    }
                    
                    // Add watermark if enabled
                    if (addWatermark) {
                        pdf.setFontSize(40);
                        pdf.setTextColor(200, 200, 200);
                        pdf.setGState(new pdf.GState({opacity: 0.3}));
                        
                        // Add watermark to each page
                        for (let i = 1; i <= page; i++) {
                            pdf.setPage(i);
                            pdf.text('OFFICIAL COPY', pageWidth / 2, pageHeight / 2, {angle: 45, align: 'center'});
                        }
                        pdf.setGState(new pdf.GState({opacity: 1}));
                    }
                    
                    // Add school seal/stamp if enabled
                    if (addStamp) {
                        pdf.setFontSize(12);
                        pdf.setTextColor(100, 100, 100);
                        
                        // Add stamp to last page
                        pdf.setPage(page);
                        pdf.text('SCHOOL SEAL', pageWidth - 30, pageHeight - 15);
                        // Draw a simple circle as seal
                        pdf.setDrawColor(100, 100, 100);
                        pdf.setLineWidth(0.5);
                        pdf.circle(pageWidth - 30, pageHeight - 25, 10, 'D');
                    }
                    
                    // Save the PDF
                    const fileName = `Report_Card_${currentReportData.student.name.replace(/\s+/g, '_')}_${currentReportData.student.rollNo}.pdf`;
                    pdf.save(fileName);
                    
                    // Restore original state
                    reportCardElement.className = originalClass;
                    originalControls.style.display = originalControlsDisplay;
                    
                    showFeedback('PDF downloaded successfully!', 'success');
                }).catch(error => {
                    console.error('Error generating PDF:', error);
                    // Restore original state
                    reportCardElement.className = originalClass;
                    originalControls.style.display = originalControlsDisplay;
                    showFeedback('Error generating PDF. Please try again.', 'danger');
                });
            }, 500);
        }

        // Generate optimized single page PDF
        function generateSinglePagePDF() {
            if (!currentReportData) {
                showFeedback('Please load a report card first.', 'warning');
                return;
            }
            
            showFeedback('Generating optimized single-page PDF...', 'info');
            
            // Store original state
            const reportCardElement = document.getElementById('report-card-content');
            const originalClass = reportCardElement.className;
            const originalBodyFontSize = document.body.style.fontSize;
            const originalControls = document.querySelector('.controls-section');
            const originalControlsDisplay = originalControls.style.display;
            const compactMode = document.getElementById('pdf-compact').checked;
            
            // Apply PDF optimized styles
            if (compactMode) {
                // Create a compact version for single page
                reportCardElement.classList.add('pdf-preview-mode');
            } else {
                // Use standard preview mode
                reportCardElement.classList.add('pdf-preview-mode');
            }
            
            originalControls.style.display = 'none';
            
            // Wait for DOM to update
            setTimeout(() => {
                const pdf = new jsPDF('p', 'mm', 'a4');
                const pageWidth = pdf.internal.pageSize.getWidth();
                const pageHeight = pdf.internal.pageSize.getHeight();
                
                const addWatermark = document.getElementById('pdf-watermark').checked;
                const addStamp = document.getElementById('pdf-stamp').checked;
                
                // Calculate the scale to fit content on one page
                const containerWidth = reportCardElement.offsetWidth;
                const containerHeight = reportCardElement.scrollHeight;
                const scaleFactor = Math.min(
                    (pageWidth - 20) / containerWidth * 96 / 25.4, // Convert px to mm
                    (pageHeight - 20) / containerHeight * 96 / 25.4
                );
                
                html2canvas(reportCardElement, {
                    scale: 2 * scaleFactor, // Scale down to fit
                    useCORS: true,
                    logging: false,
                    backgroundColor: '#ffffff',
                    width: containerWidth,
                    height: containerHeight
                }).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const imgWidth = pageWidth - 20; // 10mm margins
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;
                    
                    // Center vertically if content is shorter than page
                    const yPosition = imgHeight < pageHeight - 20 ? 
                        (pageHeight - imgHeight) / 2 : 10;
                    
                    // Add image to PDF
                    pdf.addImage(imgData, 'PNG', 10, yPosition, imgWidth, imgHeight);
                    
                    // Add watermark if enabled
                    if (addWatermark) {
                        pdf.setFontSize(40);
                        pdf.setTextColor(200, 200, 200);
                        pdf.setGState(new pdf.GState({opacity: 0.3}));
                        pdf.text('OFFICIAL COPY', pageWidth / 2, pageHeight / 2, {angle: 45, align: 'center'});
                        pdf.setGState(new pdf.GState({opacity: 1}));
                    }
                    
                    // Add school seal/stamp if enabled
                    if (addStamp) {
                        pdf.setFontSize(10);
                        pdf.setTextColor(100, 100, 100);
                        pdf.text('SCHOOL SEAL', pageWidth - 25, pageHeight - 10);
                        // Draw a simple circle as seal
                        pdf.setDrawColor(100, 100, 100);
                        pdf.setLineWidth(0.5);
                        pdf.circle(pageWidth - 25, pageHeight - 20, 8, 'D');
                    }
                    
                    // Save the PDF
                    const fileName = `Report_Card_${currentReportData.student.name.replace(/\s+/g, '_')}_SinglePage.pdf`;
                    pdf.save(fileName);
                    
                    // Restore original state
                    reportCardElement.className = originalClass;
                    document.body.style.fontSize = originalBodyFontSize;
                    originalControls.style.display = originalControlsDisplay;
                    
                    showFeedback('Single-page PDF downloaded successfully!', 'success');
                }).catch(error => {
                    console.error('Error generating single-page PDF:', error);
                    // Restore original state
                    reportCardElement.className = originalClass;
                    document.body.style.fontSize = originalBodyFontSize;
                    originalControls.style.display = originalControlsDisplay;
                    showFeedback('Error generating PDF. Please try again.', 'danger');
                });
            }, 500);
        }
    </script>
@endsection