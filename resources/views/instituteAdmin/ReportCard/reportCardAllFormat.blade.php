@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- jsPDF and html2canvas -->
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
}

/* Board-specific colors */
@if($boardFormat=='cbse') :root {
    --primary-color: #4361ee;
    --secondary-color: #3a0ca3;
}

@elseif($boardFormat=='icse') :root {
    --primary-color: #e63946;
    --secondary-color: #9d0208;
}

@elseif($boardFormat=='state') :root {
    --primary-color: #2a9d8f;
    --secondary-color: #264653;
}

@elseif($boardFormat=='open') :root {
    --primary-color: #7209b7;
    --secondary-color: #3a0ca3;
}

@endif body {
    background-color: #f5f7fb;
}

.report-card-container {
    /* max-width: 1200px;
    margin: 20px auto; */
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    border: 1px solid #e0e0e0;
}

.header-section {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
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
    font-size: 0.9rem;
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
    padding: 5px 10px;
    background-color: white;
}

.student-info h3 {
    font-weight: 700;
    border-bottom: 2px solid var(--primary-color);
    padding-bottom: 8px;
    margin-bottom: 15px;
    font-size: 1.3rem;
    color: var(--secondary-color);
}

.info-row {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.info-label {
    font-weight: 600;
    width: 140px;
    min-width: 140px;
    color: var(--secondary-color);
}

.info-value {
    color: #555;
}

.table-section {
    padding: 0 15px 20px 15px;
}

.table-section h4 {
    font-weight: 700;
    margin-bottom: 12px;
    padding-bottom: 6px;
    border-bottom: 1px solid #eaeaea;
    font-size: 1.2rem;
    color: var(--secondary-color);
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
    min-width: 800px;
    font-size: 0.85rem;
}

.scholastic-table th {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
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

.subject-cell {
    font-weight: 600;
    text-align: left !important;
    min-width: 150px;
    padding-left: 10px !important;
    background-color: rgba(var(--primary-color), 0.05);
}

.grade-box {
    display: inline-block;
    padding: 4px 8px;
    margin: 2px;
    border-radius: 4px;
    font-weight: 600;
    color: white;
    text-align: center;
    min-width: 50px;
    font-size: 0.8rem;
}

.grade-a1 {
    background: linear-gradient(135deg, #FFD700, #FFA500);
}

.grade-a2 {
    background: linear-gradient(135deg, #8A2BE2, #4B0082);
}

.grade-b1 {
    background: linear-gradient(135deg, #4361ee, #3a0ca3);
}

.grade-b2 {
    background: linear-gradient(135deg, #5a67d8, #4c51bf);
}

.grade-c1 {
    background: linear-gradient(135deg, #4bb543, #2a9d40);
}

.grade-c2 {
    background: linear-gradient(135deg, #ff9e00, #ff7b00);
}

.grade-d {
    background: #aaa;
}

.grade-e {
    background: linear-gradient(135deg, #e63946, #d00000);
}

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

.grading-scale {
    background-color: #f8f9ff;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 20px;
    border-left: 4px solid var(--primary-color);
}

.grading-scale h5 {
    font-weight: 700;
    margin-bottom: 8px;
    font-size: 1rem;
}

.controls-section {
    padding: 15px 25px;
    background-color: #f0f5ff;
    border-top: 1px solid #ddd;
    margin-top: 20px;
    border-radius: 8px;
}

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
    flex-direction: column;
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
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

@media print {

    .controls-section,
    .no-print,
    .feedback-message,
    .loading-spinner {
        display: none !important;
    }

    .report-card-container {
        box-shadow: none;
        margin: 0;
        border-radius: 0;
    }
}

@media (max-width: 768px) {
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
}

/* Search and Select Styles */
.student-search-container {
    margin-top: 15px;
}

.search-box {
    position: relative;
    margin-bottom: 10px;
}

.search-box .form-control {
    padding-right: 40px;
}

.search-box .search-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
    pointer-events: none;
}

.search-box .clear-search {
    position: absolute;
    right: 35px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
    cursor: pointer;
    display: none;
}

.search-box .clear-search:hover {
    color: #dc3545;
}

.student-list-container {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    background: white;
}

.student-list-item {
    padding: 12px 15px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.student-list-item:last-child {
    border-bottom: none;
}

.student-list-item:hover {
    background-color: #f0f5ff;
}

.student-list-item.selected {
    background-color: #e3f2fd;
    border-left: 4px solid var(--primary-color);
}

.student-list-item .student-info {
    flex: 1;
}

.student-list-item .student-name {
    font-weight: 600;
    color: #333;
    font-size: 0.95rem;
}

.student-list-item .student-reg {
    font-size: 0.8rem;
    color: #666;
    margin-left: 10px;
    background: #f0f0f0;
    padding: 2px 8px;
    border-radius: 12px;
}

.student-list-item .student-details {
    font-size: 0.8rem;
    color: #888;
    margin-top: 4px;
}

.student-list-item .select-indicator {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 2px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.8rem;
}

.student-list-item.selected .select-indicator {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.student-list-item.selected .select-indicator i {
    font-size: 0.7rem;
}

.student-count-badge {
    background-color: #e9ecef;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    color: #495057;
    display: inline-block;
    margin-bottom: 10px;
}

.selected-student-badge {
    background-color: #e8f5e9;
    border-left: 4px solid #4caf50;
    padding: 12px 15px;
    margin-top: 15px;
    border-radius: 6px;
    display: none;
}

.selected-student-badge .student-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.selected-student-badge .student-details {
    font-weight: 600;
    color: #2e7d32;
}

.selected-student-badge .remove-student {
    color: #dc3545;
    cursor: pointer;
    font-size: 1.2rem;
    transition: color 0.2s;
}

.selected-student-badge .remove-student:hover {
    color: #bd2130;
}

.search-loading {
    display: none;
    text-align: center;
    padding: 20px;
    color: #999;
}

.no-students-found {
    padding: 30px;
    text-align: center;
    color: #999;
    background: #f9f9f9;
    border-radius: 6px;
}

.highlight {
    background-color: #fff3cd;
    font-weight: 600;
}
</style>

<!-- Loading Spinner -->
<div class="loading-spinner" id="loadingSpinner">
    <div class="spinner"></div>
</div>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="fas fa-file-alt me-2"></i> Report Card </h2>
        </div>
    </div>

    <!-- Selection Section -->
    <div class="card mb-4">
        <div class="card-header"
            style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white;">
            <h5 class="mb-0"><i class="fas fa-search me-2"></i>Select Student</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="branch_id" class="form-label">Select Class *</label>
                    <select class="form-select" id="branch_id" required>
                        <option value="">-- Select Class --</option>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->product_id }}">{{ $branch->sub_type ?? $branch->course_type }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="academic_year" class="form-label">Academic Year</label>
                    <select class="form-select" id="academic_year">
                        <option value="">-- Select Academic Year --</option>
                        @foreach($academicYears as $year)
                        <option value="{{ $year }}" {{ $year == $session ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="exam_name_ids" class="form-label">Select Exams *</label>
                    <select class="form-select" id="exam_name_ids" multiple disabled required>
                        <option value="">-- Select Exams --</option>
                    </select>
                    <small class="text-muted">Hold Ctrl/Cmd to select multiple exams</small>
                </div>
            </div>

            <!-- Student Selection Area with Searchable List -->
            <div class="student-search-container mt-3">
                <div class="row">
                    <div class="col-12">
                        <label class="form-label fw-bold">Select Student from List *</label>
                        
                        <!-- Search Box -->
                        <div class="search-box mb-2">
                            <input type="text" 
                                   class="form-control" 
                                   id="studentSearch" 
                                   placeholder="Type to filter students by name or registration number..." 
                                   disabled>
                            <span class="search-icon">
                                <i class="fas fa-search"></i>
                            </span>
                            <span class="clear-search" id="clearSearch" style="display: none;">
                                <i class="fas fa-times-circle"></i>
                            </span>
                        </div>
                        
                        <!-- Student Count -->
                        <div class="student-count-badge" id="studentCount">
                            <i class="fas fa-users me-1"></i>
                            <span id="totalStudents">0</span> students found
                        </div>
                        
                        <!-- Student List Container (Scrollable) -->
                        <div class="student-list-container" id="studentListContainer">
                            <div class="student-list" id="studentList">
                                <!-- Students will be populated here -->
                                <div class="no-students-found">
                                    <i class="fas fa-user-graduate fa-3x mb-3 text-muted"></i>
                                    <p>Please select a class to view students</p>
                                </div>
                            </div>
                            
                            <!-- Loading Indicator -->
                            <div class="search-loading" id="searchLoading">
                                <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                <p>Loading students...</p>
                            </div>
                        </div>
                        
                        <!-- Selected Student Display -->
                        <div class="selected-student-badge" id="selectedStudentDisplay" style="display: none;">
                            <div class="student-info">
                                <div>
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span class="student-details" id="selectedStudentName"></span>
                                    <span class="badge bg-success ms-2" id="selectedStudentRegNo"></span>
                                </div>
                                <div class="remove-student" id="removeStudent">
                                    <i class="fas fa-times-circle" title="Remove selection"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Generate Button -->
            <div class="row mt-3">
                <div class="col-12">
                    <button class="btn btn-success" id="loadReportBtn" disabled>
                        <i class="fas fa-file-alt me-2"></i>Generate Report Card
                    </button>
                    <button class="btn btn-secondary ms-2" id="resetBtn">
                        <i class="fas fa-redo me-2"></i>Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Card Container -->
    <div class="report-card-container {{ $boardFormat }}-format" id="reportCard">
        <div class="header-section">
            <h1 id="school-name">{{ $institute->name ?? ($institute->institute_name ?? '') }}</h1>
            @if(!empty($institute->affiliation))
            <div class="school-info" id="school-affiliation">{{ $institute->affiliation }}</div>
            @endif
            @if(!empty($institute->address_line_1) || !empty($institute->institute_address))
            <div class="school-info" id="school-address">
                {{ $institute->address_line_1 ?? $institute->institute_address ?? '' }}
            </div>
            @endif
            @php
            $contactParts = [];
            if (!empty($institute->contact_number)) {
            $contactParts[] = 'PH: ' . $institute->contact_number;
            }
            if (!empty($institute->institute_phone)) {
            $contactParts[] = 'PH: ' . $institute->institute_phone;
            }
            if (!empty($institute->email)) {
            $contactParts[] = 'E-MAIL: ' . $institute->email;
            }
            if (!empty($institute->institute_email)) {
            $contactParts[] = 'E-MAIL: ' . $institute->institute_email;
            }
            $contactString = implode(' | ', array_unique($contactParts));
            @endphp
            @if($contactString)
            <div class="school-info" id="school-contact">{{ $contactString }}</div>
            @endif
            <div class="session">Session: <span id="session-year">{{ $session ?? '' }}</span></div>
            <h3 class="mt-3 mb-0" id="report-title">
                @if($boardFormat == 'cbse') Achievement Record - Annual Report Card
                @elseif($boardFormat == 'icse') Statement of Marks - ICSE Examination
                @elseif($boardFormat == 'state') Annual Progress Report
                @elseif($boardFormat == 'open') Mark Statement - Secondary Examination
                @endif
            </h3>
        </div>

        <div class="student-info">
            <h3>
                @if($boardFormat == 'cbse') Student Information
                @elseif($boardFormat == 'icse') Candidate Details
                @elseif($boardFormat == 'state') Student Particulars
                @elseif($boardFormat == 'open') Learner Details
                @endif
            </h3>
            <div class="row">
                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-label">
                            @if($boardFormat == 'icse') Candidate Name:
                            @elseif($boardFormat == 'open') Learner's Name:
                            @else Name:
                            @endif
                        </div>
                        <div class="info-value" id="student-name">--</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">
                            @if($boardFormat == 'open') Father's/Husband's Name:
                            @else Father's Name:
                            @endif
                        </div>
                        <div class="info-value" id="father-name">--</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Mother's Name:</div>
                        <div class="info-value" id="mother-name">--</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Address:</div>
                        <div class="info-value" id="address">--</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-label">
                            @if($boardFormat == 'icse') Class-Section:
                            @elseif($boardFormat == 'state') Class & Section:
                            @else Class-Section:
                            @endif
                        </div>
                        <div class="info-value" id="class-section">--</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">
                            @if($boardFormat == 'icse') ICSE No.:
                            @elseif($boardFormat == 'open') Enrollment No.:
                            @elseif($boardFormat == 'state') Registration No.:
                            @else Registration No.:
                            @endif
                        </div>
                        <div class="info-value" id="reg-number">--</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Date of Birth:</div>
                        <div class="info-value" id="dob">--</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Blood Group:</div>
                        <div class="info-value" id="blood_group">--</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-section">
            <h4>
                @if($boardFormat == 'cbse') Scholastic Area
                @elseif($boardFormat == 'icse') Performance in Subjects
                @elseif($boardFormat == 'state') Annual Examination Results
                @elseif($boardFormat == 'open') Subject-wise Performance
                @endif
            </h4>
            <div class="table-responsive">
                <table class="scholastic-table" id="marks-table">
                    <thead id="table-header">
                        @include('instituteAdmin.ReportCard.partials.' . $boardFormat . '_header')
                    </thead>
                    <tbody id="table-body">
                        <tr>
                            <td colspan="10" class="text-center py-4">No data available</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Grading Scale -->
            <h4 class="mt-4">
                @if($boardFormat == 'cbse') Scholastic Area Grading Scale
                @elseif($boardFormat == 'icse') ICSE Grading System
                @elseif($boardFormat == 'state') State Board Grading System
                @elseif($boardFormat == 'open') NIOS Grading System
                @endif
            </h4>
            <div class="grading-scale">
                <h5>Marks Range & Grades</h5>
                <div class="d-flex flex-wrap" id="grading-scale-container">
                    <!-- Dynamic grading scale will be inserted here -->
                </div>
            </div>
        </div>

        <div class="footer-section">
            <div class="summary-box">
                <div class="summary-item">
                    <div class="summary-label">Total Marks:</div>
                    <div class="summary-value" id="total-marks">--</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Marks Obtained:</div>
                    <div class="summary-value" id="obtained-marks">--</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Percentage:</div>
                    <div class="summary-value" id="percentage">--</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Overall Grade:</div>
                    <div class="summary-value" id="overall-grade">--</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Result:</div>
                    <div class="summary-value" id="result">--</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Remark:</div>
                    <div class="summary-value" id="remark">--</div>
                </div>
            </div>

            <div class="signature-section">
                <div class="signature-box">
                    <div>Date: <span id="current-date">{{ date('d/m/Y') }}</span></div>
                    <div class="signature-line"></div>
                    <div>Class Teacher</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div>Principal</div>
                </div>
                @if($boardFormat == 'icse')
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div>Council Secretary</div>
                </div>
                @elseif($boardFormat == 'state')
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div>District Education Officer</div>
                </div>
                @elseif($boardFormat == 'open')
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div>Regional Director</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Controls -->
    <div class="controls-section no-print">
        <h5>Actions</h5>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-primary" onclick="generatePDF()">
                <i class="fas fa-file-pdf me-2"></i>Download PDF
            </button>
            <button class="btn btn-warning" onclick="printReport()">
                <i class="fas fa-print me-2"></i>Print Report
            </button>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
const {
    jsPDF
} = window.jspdf;
let currentReportData = null;
let boardFormat = '{{ $boardFormat }}';

// Search variables
let searchTimeout = null;
let allStudents = [];
let filteredStudents = [];
let selectedStudent = null;

document.addEventListener('DOMContentLoaded', function() {
    setupDropdowns();
    setupSearchableList();
    setupEventListeners();
});

function setupDropdowns() {
    $('#branch_id').on('change', function() {
        const branchId = $(this).val();
        const academicYear = $('#academic_year').val();

        // Reset everything
        $('#studentSearch').val('').prop('disabled', true);
        $('#clearSearch').hide();
        $('#studentList').html('<div class="no-students-found"><i class="fas fa-user-graduate fa-3x mb-3 text-muted"></i><p>Please select a class to view students</p></div>');
        $('#exam_name_ids').html('<option value="">-- Select Exams --</option>').prop('disabled', true);
        $('#loadReportBtn').prop('disabled', true);
        $('#selectedStudentDisplay').hide();
        $('#totalStudents').text('0');
        selectedStudent = null;
        allStudents = [];
        filteredStudents = [];

        if (branchId) {
            loadStudentsForList(branchId, academicYear);
            $('#studentSearch').prop('disabled', false);
            if (academicYear) loadExamNames(academicYear);
        }
    });

    $('#academic_year').on('change', function() {
        const academicYear = $(this).val();
        const branchId = $('#branch_id').val();

        if (branchId) {
            loadStudentsForList(branchId, academicYear);
            loadExamNames(academicYear);
        }
    });
}

function loadStudentsForList(branchId, academicYear) {
    showLoading(true);
    $('#searchLoading').show();
    
    $.ajax({
        url: '{{ route("report-cards.get-students") }}',
        method: 'POST',
        data: {
            branch_id: branchId,
            academic_year: academicYear,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                allStudents = response.students;
                filteredStudents = [...allStudents];
                renderStudentList(filteredStudents);
                $('#totalStudents').text(allStudents.length);
                showFeedback(`${response.count} students loaded`, 'success');
            }
            $('#searchLoading').hide();
            showLoading(false);
        },
        error: function() {
            showFeedback('Error loading students', 'danger');
            $('#searchLoading').hide();
            showLoading(false);
        }
    });
}

function renderStudentList(students) {
    const listContainer = $('#studentList');
    listContainer.empty();
    
    if (!students || students.length === 0) {
        listContainer.html(`
            <div class="no-students-found">
                <i class="fas fa-user-slash fa-3x mb-3 text-muted"></i>
                <p>No students found</p>
            </div>
        `);
        return;
    }
    
    students.forEach(student => {
        const regNo = student.registration_number || 'N/A';
        const isSelected = selectedStudent && selectedStudent.student_hash_id === student.student_hash_id;
        const selectedClass = isSelected ? 'selected' : '';
        
        const studentItem = $(`
            <div class="student-list-item ${selectedClass}" data-student-id="${student.student_hash_id}">
                <div class="student-info">
                    <div>
                        <span class="student-name">${escapeHtml(student.full_name)}</span>
                        <span class="student-reg">${escapeHtml(regNo)}</span>
                    </div>
                   
                </div>
                <div class="select-indicator">
                    ${isSelected ? '<i class="fas fa-check"></i>' : ''}
                </div>
            </div>
        `);
        
        listContainer.append(studentItem);
    });
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function setupSearchableList() {
    const searchInput = $('#studentSearch');
    const clearSearch = $('#clearSearch');
    const listContainer = $('#studentListContainer');

    // Search input handler with debounce
    searchInput.on('input', function() {
        const searchTerm = $(this).val().toLowerCase().trim();
        
        // Show/hide clear button
        if (searchTerm.length > 0) {
            clearSearch.show();
        } else {
            clearSearch.hide();
        }
        
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterStudentList(searchTerm);
        }, 300);
    });

    // Clear search
    clearSearch.on('click', function() {
        searchInput.val('').focus();
        clearSearch.hide();
        filterStudentList('');
    });

    // Handle student selection via event delegation
    listContainer.on('click', '.student-list-item', function() {
        const studentId = $(this).data('student-id');
        const student = allStudents.find(s => s.student_hash_id === studentId);
        
        if (student) {
            selectStudent(student);
        }
    });
}

function filterStudentList(searchTerm) {
    if (!allStudents.length) return;
    
    if (searchTerm === '') {
        filteredStudents = [...allStudents];
    } else {
        filteredStudents = allStudents.filter(student => {
            const name = (student.full_name || '').toLowerCase();
            const regNo = (student.registration_number || '').toLowerCase();
            const fatherName = (student.father_name || '').toLowerCase();
            
            return name.includes(searchTerm) || 
                   regNo.includes(searchTerm) || 
                   fatherName.includes(searchTerm);
        });
    }
    
    renderStudentList(filteredStudents);
    $('#totalStudents').text(filteredStudents.length);
}

function selectStudent(student) {
    selectedStudent = student;
    
    // Update UI
    $('#selectedStudentName').text(student.full_name);
    $('#selectedStudentRegNo').text(student.registration_number || 'No Reg No');
    $('#selectedStudentDisplay').show();
    $('#loadReportBtn').prop('disabled', false);
    
    // Highlight selected item in list
    $('.student-list-item').removeClass('selected');
    $(`.student-list-item[data-student-id="${student.student_hash_id}"]`).addClass('selected');
    
    // Update select indicators
    $('.select-indicator').html('');
    $(`.student-list-item[data-student-id="${student.student_hash_id}"] .select-indicator`)
        .html('<i class="fas fa-check"></i>');
    
    showFeedback(`Selected: ${student.full_name}`, 'info');
}

function setupEventListeners() {
    // Remove student selection
    $('#removeStudent').on('click', function() {
        selectedStudent = null;
        $('#selectedStudentDisplay').hide();
        $('#loadReportBtn').prop('disabled', true);
        
        // Remove highlight from list
        $('.student-list-item').removeClass('selected');
        $('.select-indicator').html('');
        
        showFeedback('Student selection removed', 'info');
    });

    // Reset button
    $('#resetBtn').on('click', function() {
        $('#branch_id').val('').trigger('change');
        $('#academic_year').val('');
        $('#studentSearch').val('').prop('disabled', true);
        $('#clearSearch').hide();
        $('#studentList').html('<div class="no-students-found"><i class="fas fa-user-graduate fa-3x mb-3 text-muted"></i><p>Please select a class to view students</p></div>');
        $('#exam_name_ids').html('<option value="">-- Select Exams --</option>').prop('disabled', true);
        $('#loadReportBtn').prop('disabled', true);
        $('#selectedStudentDisplay').hide();
        $('#totalStudents').text('0');
        selectedStudent = null;
        allStudents = [];
        filteredStudents = [];
        showFeedback('Selections reset', 'info');
    });

    // Load report button
    $('#loadReportBtn').on('click', function() {
        loadReportCard();
    });
}

function loadExamNames(academicYear) {
    const branchId = $('#branch_id').val();
    if (!branchId) return;

    $.ajax({
        url: '{{ route("report-cards.get-exam-names") }}',
        method: 'POST',
        data: {
            branch_id: branchId,
            academic_year: academicYear,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                let options = '';
                response.exam_names.forEach(exam => {
                    options += `<option value="${exam.exam_name_id}">${exam.exam_name}</option>`;
                });
                $('#exam_name_ids').html(options).prop('disabled', false);
            }
        },
        error: function() {
            showFeedback('Error loading exam names', 'danger');
        }
    });
}

function loadReportCard() {
    if (!selectedStudent) {
        showFeedback('Please select a student', 'warning');
        return;
    }
    
    const examNameIds = $('#exam_name_ids').val();
    const academicYear = $('#academic_year').val();

    if (!examNameIds || examNameIds.length === 0) {
        showFeedback('Please select at least one exam', 'warning');
        return;
    }

    showLoading(true);

    $.ajax({
        url: '{{ route("report-cards.get-student-marks") }}',
        method: 'POST',
        data: {
            student_hash_id: selectedStudent.student_hash_id,
            exam_name_ids: examNameIds,
            academic_year: academicYear,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                populateReport(response);
                showFeedback('Report card generated successfully!', 'success');
            } else {
                showFeedback(response.message || 'No exam data found', 'warning');
            }
            showLoading(false);
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            showFeedback(response?.message || 'Error loading report card', 'danger');
            showLoading(false);
        }
    });
}

function populateReport(data) {
    currentReportData = data;
    const student = data.student;
    const academic = data.academic_details;
    const gradeSystem = data.grade_systems && data.grade_systems.length > 0 ? data.grade_systems[0] : null;
    const examNames = data.exam_names || [];

    // Student Info
    $('#student-name').text(student.full_name);
    $('#father-name').text(student.father_name);
    $('#mother-name').text(student.mother_name);
    
    // Show section name properly
    let classSection = '--';
    if (academic) {
        const courseType = academic.course_type || '';
        const sectionDisplay = academic.section_display || academic.section_id || '';
        classSection = courseType + (sectionDisplay ? ' - ' + sectionDisplay : '');
    }
    $('#class-section').text(classSection);
    
    $('#reg-number').text(student.registration_number || '--');
    $('#dob').text(student.dob ? new Date(student.dob).toLocaleDateString('en-GB') : '--');
    $('#blood_group').text(student.blood_group || '--');
    $('#address').text(student.address || '--');
      
    // Dynamically create table header based on selected exams
    createDynamicTableHeader(examNames);

    // Marks Table
    populateDynamicMarksTable(data.subjects_data, examNames);

    // Grading Scale
    updateGradingScale(gradeSystem);

    // Summary
    const stats = data.statistics;
    $('#total-marks').text(stats.total_marks);
    $('#obtained-marks').text(stats.obtained_marks);
    $('#percentage').text(stats.overall_percentage + '%');
    $('#overall-grade').text(calculateOverallGrade(stats.overall_percentage, gradeSystem));
    $('#result').text(stats.passed_subjects === stats.total_subjects ? 'PASS' : 'FAIL');
    $('#remark').text(getRemark(stats.overall_percentage));
}

function createDynamicTableHeader(examNames) {
    const thead = $('#table-header');
    thead.empty();
    
    // Create header row
    let headerRow = '<tr>';
    headerRow += '<th rowspan="2">Subject</th>';
    
    // Add column for each selected exam
    examNames.forEach(exam => {
        headerRow += `<th colspan="4" class="text-center">${exam.name}${exam.date ? ' (' + exam.date + ')' : ''}</th>`;
    });
    
    headerRow += '<th rowspan="2">Total</th>';
    headerRow += '<th rowspan="2">Final Grade</th>';
    headerRow += '</tr>';
    
    // Create sub-header row (Marks, Obtained, % , Grade)
    headerRow += '<tr>';
    examNames.forEach(() => {
        headerRow += '<th>Max</th>';
        headerRow += '<th>Obtained</th>';
        headerRow += '<th>%</th>';
        headerRow += '<th>Grade</th>';
    });
    headerRow += '</tr>';
    
    thead.html(headerRow);
}

function populateDynamicMarksTable(subjectsData, examNames) {
    const tbody = $('#table-body');
    tbody.empty();

    if (!subjectsData || subjectsData.length === 0) {
        tbody.html('<tr><td colspan="' + (examNames.length * 4 + 2) + '" class="text-center py-4">No data available</td></tr>');
        return;
    }

    subjectsData.forEach(subject => {

        let row = '<tr>';
        row += `<td class="subject-cell">${subject.subject_name}</td>`;

        let subjectTotalObtained = 0;
        let subjectTotalMax = 0;

        let examMarks = subject.exam_marks || {};

        examNames.forEach(exam => {

            const marks = examMarks[exam.id] || {
                total_marks: 0,
                obtained_marks: 0,
                percentage: 0,
                grade: '--'
            };

            subjectTotalObtained += parseFloat(marks.obtained_marks) || 0;
            subjectTotalMax += parseFloat(marks.total_marks) || 0;

            row += `<td>${marks.total_marks || '--'}</td>`;
            row += `<td>${marks.obtained_marks || '--'}</td>`;
            row += `<td>${marks.percentage ? marks.percentage + '%' : '--'}</td>`;
            row += `<td><span class="grade-box ${getGradeClass(marks.grade)}">${marks.grade || '--'}</span></td>`;
        });

        // ✅ Correct percentage calculation
        let finalPercentage = subjectTotalMax > 0 
            ? ((subjectTotalObtained / subjectTotalMax) * 100).toFixed(2)
            : 0;

        let finalGrade = calculateOverallGrade(parseFloat(finalPercentage), null);

        row += `<td>${subjectTotalObtained} / ${subjectTotalMax}</td>`;
        row += `<td><span class="grade-box ${getGradeClass(finalGrade)}">${finalGrade}</span></td>`;
        row += '</tr>';

        tbody.append(row);
    });
}

function updateGradingScale(gradeSystem) {
    const container = $('#grading-scale-container');
    container.empty();

    if (gradeSystem && gradeSystem.grade_ranges) {
        gradeSystem.grade_ranges.forEach(range => {
            const gradeClass = getGradeClassFromGrade(range.grade);
            container.append(`
                    <div class="grade-box ${gradeClass}">
                        ${range.grade} (${range.min_percentage}-${range.max_percentage}%)
                        ${range.grade_point ? ` - ${range.grade_point} GP` : ''}
                    </div>
                `);
        });
    } else {
        // Default grading scale
        const defaultScales = {
            cbse: [{
                    grade: 'A1',
                    range: '91-100%',
                    class: 'grade-a1'
                },
                {
                    grade: 'A2',
                    range: '81-90%',
                    class: 'grade-a2'
                },
                {
                    grade: 'B1',
                    range: '71-80%',
                    class: 'grade-b1'
                },
                {
                    grade: 'B2',
                    range: '61-70%',
                    class: 'grade-b2'
                },
                {
                    grade: 'C1',
                    range: '51-60%',
                    class: 'grade-c1'
                },
                {
                    grade: 'C2',
                    range: '41-50%',
                    class: 'grade-c2'
                },
                {
                    grade: 'D',
                    range: '33-40%',
                    class: 'grade-d'
                },
                {
                    grade: 'E',
                    range: 'Below 33%',
                    class: 'grade-e'
                }
            ],
            icse: [{
                    grade: '1',
                    range: '90-100%',
                    class: 'grade-a1'
                },
                {
                    grade: '2',
                    range: '80-89%',
                    class: 'grade-a2'
                },
                {
                    grade: '3',
                    range: '70-79%',
                    class: 'grade-b1'
                },
                {
                    grade: '4',
                    range: '60-69%',
                    class: 'grade-b2'
                },
                {
                    grade: '5',
                    range: '50-59%',
                    class: 'grade-c1'
                },
                {
                    grade: '6',
                    range: '40-49%',
                    class: 'grade-c2'
                },
                {
                    grade: '7',
                    range: '35-39%',
                    class: 'grade-d'
                },
                {
                    grade: '8',
                    range: 'Below 35%',
                    class: 'grade-e'
                }
            ],
            state: [{
                    grade: 'A+',
                    range: '91-100%',
                    class: 'grade-a1'
                },
                {
                    grade: 'A',
                    range: '81-90%',
                    class: 'grade-a2'
                },
                {
                    grade: 'B+',
                    range: '71-80%',
                    class: 'grade-b1'
                },
                {
                    grade: 'B',
                    range: '61-70%',
                    class: 'grade-b2'
                },
                {
                    grade: 'C',
                    range: '51-60%',
                    class: 'grade-c1'
                },
                {
                    grade: 'D',
                    range: '41-50%',
                    class: 'grade-c2'
                },
                {
                    grade: 'E',
                    range: '33-40%',
                    class: 'grade-d'
                },
                {
                    grade: 'F',
                    range: 'Below 33%',
                    class: 'grade-e'
                }
            ],
            open: [{
                    grade: 'A*',
                    range: '91-100%',
                    class: 'grade-a1'
                },
                {
                    grade: 'A',
                    range: '81-90%',
                    class: 'grade-a2'
                },
                {
                    grade: 'B',
                    range: '71-80%',
                    class: 'grade-b1'
                },
                {
                    grade: 'C',
                    range: '61-70%',
                    class: 'grade-b2'
                },
                {
                    grade: 'D',
                    range: '51-60%',
                    class: 'grade-c1'
                },
                {
                    grade: 'E',
                    range: '41-50%',
                    class: 'grade-c2'
                },
                {
                    grade: 'F',
                    range: '33-40%',
                    class: 'grade-d'
                },
                {
                    grade: 'G',
                    range: 'Below 33%',
                    class: 'grade-e'
                }
            ]
        };

        const scale = defaultScales[boardFormat] || defaultScales.cbse;
        scale.forEach(item => {
            container.append(`<div class="grade-box ${item.class}">${item.grade} (${item.range})</div>`);
        });
    }
}

function getGradeClass(grade) {
    const map = {
        'A1': 'grade-a1',
        'A2': 'grade-a2',
        'A+': 'grade-a1',
        'A': 'grade-a2',
        'B1': 'grade-b1',
        'B2': 'grade-b2',
        'B+': 'grade-b1',
        'B': 'grade-b2',
        'C1': 'grade-c1',
        'C2': 'grade-c2',
        'C': 'grade-c1',
        'D': 'grade-d',
        'E': 'grade-e',
        'F': 'grade-e',
        'G': 'grade-e',
        '1': 'grade-a1',
        '2': 'grade-a2',
        '3': 'grade-b1',
        '4': 'grade-b2',
        '5': 'grade-c1',
        '6': 'grade-c2',
        '7': 'grade-d',
        '8': 'grade-e'
    };
    return map[grade] || '';
}

function getGradeClassFromGrade(grade) {
    return getGradeClass(grade);
}

function calculateOverallGrade(percentage, gradeSystem) {
    if (gradeSystem && gradeSystem.grade_ranges) {
        for (let range of gradeSystem.grade_ranges) {
            if (percentage >= range.min_percentage && percentage <= range.max_percentage) {
                return range.grade;
            }
        }
    }
    // Default calculation
    if (percentage >= 91) return 'A1';
    if (percentage >= 81) return 'A2';
    if (percentage >= 71) return 'B1';
    if (percentage >= 61) return 'B2';
    if (percentage >= 51) return 'C1';
    if (percentage >= 41) return 'C2';
    if (percentage >= 33) return 'D';
    return 'E';
}

function getRemark(percentage) {
    if (percentage >= 90) return 'Excellent Performance';
    if (percentage >= 80) return 'Very Good Performance';
    if (percentage >= 70) return 'Good Performance';
    if (percentage >= 60) return 'Satisfactory Performance';
    if (percentage >= 50) return 'Average Performance';
    if (percentage >= 40) return 'Below Average';
    if (percentage >= 33) return 'Just Passed';
    return 'Failed';
}

function showLoading(show) {
    if (show) {
        $('#loadingSpinner').css('display', 'flex');
    } else {
        $('#loadingSpinner').hide();
    }
}

function showFeedback(message, type) {
    $('.feedback-message').remove();
    const feedback = $(`
            <div class="feedback-message alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1000;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
    $('body').append(feedback);
    setTimeout(() => feedback.fadeOut('slow', function() {
        $(this).remove();
    }), 3000);
}

function generatePDF() {
    if (!currentReportData) {
        showFeedback('Please load a report card first', 'warning');
        return;
    }

    fetch('{{ route("report-cards.download-pdf") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(currentReportData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Server error");
        }
        return response.blob();
    })
    .then(blob => {
        // Create a download link
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'Report_Card.pdf';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        
        showFeedback('PDF downloaded successfully!', 'success');
    })
    .catch(error => {
        console.error('Error:', error);
        showFeedback('Error generating PDF', 'danger');
    });
}

function printReport() {
    if (!currentReportData) {
        showFeedback('Please load a report card first', 'warning');
        return;
    }

    const printContent = document.getElementById('reportCard').innerHTML;

    const printWindow = window.open('', '', 'width=900,height=650');

    printWindow.document.write(`
        <html>
            <head>
                <title>Report Card</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        padding: 20px;
                        font-size: 12px;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }

                    th, td {
                        border: 1px solid #000;
                        padding: 6px;
                        text-align: center;
                    }

                    h2, h3 {
                        margin: 5px 0;
                    }
                </style>
            </head>
            <body>
                ${printContent}
            </body>
        </html>
    `);

    printWindow.document.close();
    printWindow.focus();

    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 500);
}

</script>
@endsection