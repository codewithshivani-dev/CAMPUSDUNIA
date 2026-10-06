@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
:root {
    --primary: #4361ee;
    --success: #06d6a0;
    --warning: #ffb703;
    --danger: #ef476f;
    --light: #f8f9fa;
    --dark: #212529;
    --border: #e9ecef;
}

/* Simple, clean styling */
.page-title-box {
    background: white;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    border-left: 5px solid var(--primary);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.page-title-box h4 {
    font-size: 22px;
    font-weight: 600;
    color: var(--dark);
    margin: 0;
}

/* Step Wizard - Bootstrap 5 compatible */
.step-wizard {
    display: flex;
    margin-bottom: 30px;
    background: white;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid var(--border);
}

.step-item {
    flex: 1;
    text-align: center;
    position: relative;
}

.step-item:not(:last-child):after {
    content: '';
    position: absolute;
    top: 25px;
    right: -30px;
    width: 60px;
    height: 2px;
    background: var(--border);
    z-index: 1;
}

.step-number {
    width: 50px;
    height: 50px;
    background: white;
    border: 2px solid var(--border);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-weight: 600;
    color: #6c757d;
    position: relative;
    z-index: 2;
    background: white;
}

.step-item.active .step-number {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.step-item.completed .step-number {
    background: var(--success);
    border-color: var(--success);
    color: white;
}

.step-label {
    font-size: 14px;
    font-weight: 500;
    color: #6c757d;
}

.step-item.active .step-label {
    color: var(--primary);
    font-weight: 600;
}

.step-item.completed .step-label {
    color: var(--success);
}

/* Cards */
.card {
    border: 1px solid var(--border);
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    margin-bottom: 20px;
}

.card-header {
    background: white;
    border-bottom: 1px solid var(--border);
    padding: 15px 20px;
    font-weight: 600;
    border-radius: 12px 12px 0 0 !important;
}

.card-body {
    padding: 25px;
}

/* Form elements */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-weight: 500;
    color: var(--dark);
    margin-bottom: 8px;
    font-size: 14px;
}

.form-control,
.form-select {
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 10px 15px;
    height: auto;
    font-size: 14px;
    transition: all 0.2s;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    outline: none;
}

.form-control:disabled,
.form-select:disabled {
    background-color: #f8f9fa;
    border-color: var(--border);
    opacity: 0.7;
}

/* Buttons - Bootstrap 5 compatible */
.btn {
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.2s;
    cursor: pointer;
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-primary {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background: #3651d4;
    border-color: #3651d4;
}

.btn-success {
    background: var(--success);
    border-color: var(--success);
    color: white;
}

.btn-success:hover:not(:disabled) {
    background: #05b586;
    border-color: #05b586;
}

.btn-secondary {
    background: #6c757d;
    border-color: #6c757d;
    color: white;
}

.btn-outline-primary {
    background: white;
    border: 1px solid var(--primary);
    color: var(--primary);
}

.btn-outline-primary:hover:not(:disabled) {
    background: var(--primary);
    color: white;
}

/* Table */
.table-container {
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
}

.table {
    width: 100%;
    margin-bottom: 0;
}

.table thead th {
    background: #f8f9fa;
    color: var(--dark);
    font-weight: 600;
    font-size: 13px;
    padding: 12px 10px;
    border-bottom: 1px solid var(--border);
}

.table tbody td {
    padding: 12px 10px;
    border-bottom: 1px solid var(--border);
    font-size: 13px;
}

.table tbody tr:hover {
    background: #f8f9fa;
}

/* Stats cards */
.stats-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 15px;
    text-align: center;
}

.stats-number {
    font-size: 24px;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 5px;
}

.stats-label {
    font-size: 13px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

/* Alerts */
.alert {
    border-radius: 8px;
    padding: 15px 20px;
    margin-bottom: 20px;
}

.alert-warning {
    background: #fff3cd;
    border-color: #ffc107;
    color: #856404;
}

.alert-success {
    background: #d4edda;
    border-color: #28a745;
    color: #155724;
}

.alert-info {
    background: #d1ecf1;
    border-color: #17a2b8;
    color: #0c5460;
}

/* Badges */
.badge {
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.badge-primary {
    background: var(--primary);
    color: white;
}

.badge-success {
    background: var(--success);
    color: white;
}

.badge-warning {
    background: var(--warning);
    color: #212529;
}

/* Modals - Bootstrap 5 compatible */
.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid var(--border);
    background: white;
}

.modal-body {
    padding: 25px;
}

.modal-footer {
    padding: 20px;
    border-top: 1px solid var(--border);
}

/* Loading spinner */
.loading-spinner {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

/* Student checkbox */
.student-checkbox {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

/* Split layout for combined steps */
.split-layout {
    display: flex;
    gap: 25px;
}

.split-left, .split-right {
    flex: 1;
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-0">
                    <i class="fas fa-graduation-cap me-2"></i>
                    Promote Students
                </h4>
            </div>
        </div>
    </div>

    <!-- Step Wizard -->
    <div class="step-wizard">
        <div class="step-item active" id="step1">
            <div class="step-number">1</div>
            <div class="step-label">Select Source & Target</div>
        </div>
        <div class="step-item" id="step2">
            <div class="step-number">2</div>
            <div class="step-label">Select Students</div>
        </div>
        <div class="step-item" id="step3">
            <div class="step-number">3</div>
            <div class="step-label">Confirm & Promote</div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="promotionForm">
                        @csrf

                        <!-- Step 1: Select Source & Target (Combined) -->
                        <div id="step1-content">
                            <div class="split-layout">
                                <!-- Left Side: Source -->
                                <div class="split-left">
                                    <div class="card">
                                        <div class="card-header">
                                            <i class="fas fa-arrow-right-from-bracket me-2"></i>
                                            From
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>Academic Year <span class="text-danger">*</span></label>
                                                <select class="form-select" id="from_academic_year"
                                                    name="from_academic_year" required>
                                                    <option value="">Select Academic Year</option>
                                                    @foreach($academicYears as $year)
                                                    <option value="{{ $year['id'] }}"
                                                        {{ $year['selected'] ? 'selected' : '' }}>
                                                        {{ $year['label'] }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted">Students from this academic year will be shown</small>
                                            </div>

                                            <div class="form-group">
                                                <label>Department <span class="text-danger">*</span></label>
                                                <select class="form-select" id="from_department_id"
                                                    name="from_department_id" required>
                                                    <option value="">Select Department</option>
                                                    @foreach($departments as $dept)
                                                    <option value="{{ $dept->department_id }}">{{ $dept->department }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Class <span class="text-danger">*</span></label>
                                                <select class="form-select" id="from_class_id" name="from_class_id"
                                                    required disabled>
                                                    <option value="">Select Class</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Section</label>
                                                <select class="form-select" id="from_section_id" name="from_section_id"
                                                    disabled>
                                                    <option value="">All Sections</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Side: Target -->
                                <div class="split-right">
                                    <div class="card">
                                        <div class="card-header">
                                            <i class="fas fa-arrow-right-to-bracket me-2"></i>
                                            To
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>Academic Year <span class="text-danger">*</span></label>
                                                <select class="form-select" id="to_academic_year"
                                                    name="to_academic_year" required>
                                                    <option value="">Select Academic Year</option>
                                                    @foreach($academicYears as $year)
                                                    <option value="{{ $year['id'] }}"
                                                        {{ $year['next_selected'] ? 'selected' : '' }}>
                                                        {{ $year['label'] }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted">Students will be promoted to this academic year</small>
                                            </div>

                                            <div class="form-group">
                                                <label>Department <span class="text-danger">*</span></label>
                                                <select class="form-select" id="to_department_id"
                                                    name="to_department_id" required>
                                                    <option value="">Select Department</option>
                                                    @foreach($departments as $dept)
                                                    <option value="{{ $dept->department_id }}">{{ $dept->department }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Class <span class="text-danger">*</span></label>
                                                <select class="form-select" id="to_class_id" name="to_class_id"
                                                    required disabled>
                                                    <option value="">Select Class</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Section</label>
                                                <select class="form-select" id="to_section_id" name="to_section_id"
                                                    disabled>
                                                    <option value="">Select Section</option>
                                                </select>
                                            </div>

                                            <!-- Department Change Warning -->
                                            <div id="departmentChangeWarning" class="alert alert-warning mt-3" style="display: none;">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <span id="departmentChangeMessage"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3 d-none">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Selected Source Summary
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th width="120">Academic Year:</th>
                                                    <td><span id="selectedFromYearDisplay">-</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Department:</th>
                                                    <td><span id="selectedFromDeptDisplay">-</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Class:</th>
                                                    <td><span id="selectedFromClassDisplay">-</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Section:</th>
                                                    <td><span id="selectedFromSectionDisplay">All Sections</span></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Selected Target Summary
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th width="120">Academic Year:</th>
                                                    <td><span id="selectedToYearDisplay">-</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Department:</th>
                                                    <td><span id="selectedToDeptDisplay">-</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Class:</th>
                                                    <td><span id="selectedToClassDisplay">-</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Section:</th>
                                                    <td><span id="selectedToSectionDisplay">-</span></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <button type="button" class="btn btn-primary" id="nextToStep2" disabled>
                                    Next <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Select Students -->
                        <div id="step2-content" style="display: none;">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="stats-card">
                                        <div class="stats-number" id="statsTotalStudents">0</div>
                                        <div class="stats-label">Total Students</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stats-card">
                                        <div class="stats-number" id="statsSelectedStudents">0</div>
                                        <div class="stats-label">Selected</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stats-card">
                                        <div class="stats-number" id="statsFromYear">-</div>
                                        <div class="stats-label">From Year</div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="stats-card">
                                        <div class="stats-number" id="statsToYear">-</div>
                                        <div class="stats-label">To Year</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Active Students Info -->
                            <div id="activeStudentsInfo" class="alert alert-success" style="display: none;">
                                <i class="fas fa-check-circle me-2"></i>
                                <span id="activeStudentsMessage"></span>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-users me-2"></i>
                                        Students List
                                        <span id="studentCount" class="badge bg-primary ms-2"></span>
                                    </span>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-primary me-2" id="selectAllBtn">
                                            <i class="fas fa-check-double me-1"></i> Select All
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllBtn">
                                            <i class="fas fa-times me-1"></i> Deselect All
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-container">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th width="40">
                                                        <input type="checkbox" id="selectAllCheckbox" class="form-check-input select-all-checkbox">
                                                    </th>
                                                    <th>Reg. No.</th>
                                                    <th>Student Name</th>
                                                    <th>Status</th>
                                                    <th>Department</th>
                                                    <th>Class</th>
                                                    <th>Section</th>
                                                    <th>Academic Year</th>
                                                </tr>
                                            </thead>
                                            <tbody id="studentsTableBody">
                                                <!-- Student rows will be inserted here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-secondary" id="backToStep1">
                                    <i class="fas fa-arrow-left me-2"></i> Back
                                </button>
                                <button type="button" class="btn btn-primary" id="nextToStep3" disabled>
                                    Next <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Confirm & Promote -->
                        <div id="step3-content" style="display: none;">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Promotion Summary
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="fw-bold mb-3">From</h6>
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th width="120">Academic Year:</th>
                                                    <td><span id="summaryFromYear"></span></td>
                                                </tr>
                                                <tr>
                                                    <th>Department:</th>
                                                    <td><span id="summaryFromDept"></span></td>
                                                </tr>
                                                <tr>
                                                    <th>Class:</th>
                                                    <td><span id="summaryFromClass"></span></td>
                                                </tr>
                                                <tr>
                                                    <th>Section:</th>
                                                    <td><span id="summaryFromSection"></span></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="fw-bold mb-3">To</h6>
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th width="120">Academic Year:</th>
                                                    <td><span id="summaryToYear"></span></td>
                                                </tr>
                                                <tr>
                                                    <th>Department:</th>
                                                    <td><span id="summaryToDept"></span></td>
                                                </tr>
                                                <tr>
                                                    <th>Class:</th>
                                                    <td><span id="summaryToClass"></span></td>
                                                </tr>
                                                <tr>
                                                    <th>Section:</th>
                                                    <td><span id="summaryToSection"></span></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row text-center">
                                        <div class="col-md-4">
                                            <div class="stats-card">
                                                <div class="stats-number" id="summaryTotalStudents">0</div>
                                                <div class="stats-label">Total Students</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="stats-card">
                                                <div class="stats-number" id="summarySelectedStudents">0</div>
                                                <div class="stats-label">Selected for Promotion</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="stats-card">
                                                <div class="stats-number" id="summaryPromotionType">-</div>
                                                <div class="stats-label">Promotion Type</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-secondary" id="backToStep2">
                                    <i class="fas fa-arrow-left me-2"></i> Back
                                </button>
                                <div>
                                    <button type="button" class="btn btn-success btn-lg me-3" id="promoteSelectedBtn" disabled>
                                        <i class="fas fa-arrow-up me-2"></i>
                                        Promote Selected (<span id="selectedCount">0</span>)
                                    </button>
                                    <button type="button" class="btn btn-primary btn-lg" id="promoteAllBtn" disabled>
                                        <i class="fas fa-forward me-2"></i>
                                        Promote All
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Spinner -->
<div class="loading-spinner" id="loadingSpinner" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<!-- Success/Error Modal -->
<div class="modal fade" id="resultModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resultModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="resultModalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Promotion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="confirmModalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmPromotionBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let selectedStudents = [];
    let pendingPromotionData = null;
    let currentStep = 1;

    // Initialize
    updateStepUI();

    // Step navigation functions
    function goToStep(step) {
        // Hide all step contents
        $('#step1-content, #step2-content, #step3-content').hide();

        // Show current step content
        $(`#step${step}-content`).show();

        // Update step wizard
        $('.step-item').removeClass('active completed');
        for (let i = 1; i <= 3; i++) {
            if (i < step) {
                $(`#step${i}`).addClass('completed');
            } else if (i === step) {
                $(`#step${i}`).addClass('active');
            }
        }

        currentStep = step;

        // Load data if needed
        if (step === 2) {
            loadStudents();
            updateSummaryDisplays();
        } else if (step === 3) {
            updateFinalSummary();
        }
    }

    function updateStepUI() {
        // Check step 1 completion (source and target both selected)
        let step1Complete = $('#from_academic_year').val() &&
            $('#from_department_id').val() &&
            $('#from_class_id').val() &&
            $('#to_academic_year').val() &&
            $('#to_department_id').val() &&
            $('#to_class_id').val();
        $('#nextToStep2').prop('disabled', !step1Complete);

        // Check step 2 completion (at least one student selected or all)
        let step2Complete = selectedStudents.length > 0 || parseInt($('#statsTotalStudents').text()) > 0;
        $('#nextToStep3').prop('disabled', !step2Complete);
    }

    // Update source display
    function updateSourceDisplay() {
        $('#selectedFromYearDisplay').text($('#from_academic_year option:selected').text() || '-');
        $('#selectedFromDeptDisplay').text($('#from_department_id option:selected').text() || '-');
        
        // Get the selected class display text
        let selectedClassText = $('#from_class_id option:selected').text() || '-';
        $('#selectedFromClassDisplay').text(selectedClassText);
        $('#selectedFromSectionDisplay').text($('#from_section_id option:selected').text() || 'All Sections');

        $('#statsFromYear').text($('#from_academic_year option:selected').text() || '-');
    }

    // Update target display
    function updateTargetDisplay() {
        $('#selectedToYearDisplay').text($('#to_academic_year option:selected').text() || '-');
        $('#selectedToDeptDisplay').text($('#to_department_id option:selected').text() || '-');
        
        // Get the selected class display text
        let selectedClassText = $('#to_class_id option:selected').text() || '-';
        $('#selectedToClassDisplay').text(selectedClassText);
        $('#selectedToSectionDisplay').text($('#to_section_id option:selected').text() || '-');

        $('#statsToYear').text($('#to_academic_year option:selected').text() || '-');
    }

    // Update final summary
    function updateFinalSummary() {
        $('#summaryFromYear').text($('#from_academic_year option:selected').text());
        $('#summaryFromDept').text($('#from_department_id option:selected').text());
        $('#summaryFromClass').text($('#from_class_id option:selected').text());
        $('#summaryFromSection').text($('#from_section_id option:selected').text() || 'All Sections');

        $('#summaryToYear').text($('#to_academic_year option:selected').text());
        $('#summaryToDept').text($('#to_department_id option:selected').text());
        $('#summaryToClass').text($('#to_class_id option:selected').text());
        $('#summaryToSection').text($('#to_section_id option:selected').text() || '-');

        $('#summaryTotalStudents').text($('#statsTotalStudents').text());
        $('#summarySelectedStudents').text(selectedStudents.length);
        $('#summaryPromotionType').text($('#departmentChangeWarning').is(':visible') ? 'Department Change' : 'Same Department');
    }

    // Navigation buttons
    $('#nextToStep2').click(function() {
        if (validatePromotionInputs()) {
            goToStep(2);
        }
    });

    $('#backToStep1').click(function() {
        goToStep(1);
    });

    $('#nextToStep3').click(function() {
        goToStep(3);
    });

    $('#backToStep2').click(function() {
        goToStep(2);
    });

    // Academic year changes
    $('#from_academic_year').change(function() {
        updateSourceDisplay();
        updateStepUI();
        validatePromotion(); // Re-validate when academic year changes
    });

    $('#to_academic_year').change(function() {
        updateTargetDisplay();
        updateStepUI();
        validatePromotion(); // Re-validate when academic year changes
    });

    // Department/Class changes
    $('#from_department_id').change(function() {
        let departmentId = $(this).val();
        updateSourceDisplay();

        if (departmentId) {
            $.ajax({
                url: '{{ route("ajax.classes.by.department") }}',
                type: 'GET',
                data: {
                    department_id: departmentId
                },
                success: function(response) {
                    let fromClassSelect = $('#from_class_id');
                    fromClassSelect.empty().append('<option value="">Select Class</option>');

                    if (response.success && response.classes.length > 0) {
                        $.each(response.classes, function(index, cls) {
                            let displayText = cls.display_name || cls.sub_type;
                            fromClassSelect.append('<option value="' + cls.product_id + '">' + displayText + '</option>');
                        });
                        fromClassSelect.prop('disabled', false);
                    }
                }
            });
        } else {
            $('#from_class_id').empty().append('<option value="">Select Class</option>').prop('disabled', true);
            $('#from_section_id').empty().append('<option value="">All Sections</option>').prop('disabled', true);
        }
        updateStepUI();
    });

    $('#to_department_id').change(function() {
        let departmentId = $(this).val();
        updateTargetDisplay();

        if (departmentId) {
            $.ajax({
                url: '{{ route("ajax.classes.by.department") }}',
                type: 'GET',
                data: {
                    department_id: departmentId
                },
                success: function(response) {
                    let toClassSelect = $('#to_class_id');
                    toClassSelect.empty().append('<option value="">Select Class</option>');

                    if (response.success && response.classes.length > 0) {
                        $.each(response.classes, function(index, cls) {
                            let displayText = cls.display_name || cls.sub_type;
                            toClassSelect.append('<option value="' + cls.product_id + '">' + displayText + '</option>');
                        });
                        toClassSelect.prop('disabled', false);
                    }
                }
            });
        } else {
            $('#to_class_id').empty().append('<option value="">Select Class</option>').prop('disabled', true);
            $('#to_section_id').empty().append('<option value="">Select Section</option>').prop('disabled', true);
        }
        updateStepUI();
    });

    $('#from_class_id').change(function() {
        let classId = $(this).val();
        updateSourceDisplay();

        if (classId) {
            $('#loadingSpinner').show();

            $.ajax({
                url: '{{ route("ajax.sections.by.class") }}',
                type: 'GET',
                data: {
                    class_id: classId
                },
                success: function(response) {
                    let sectionSelect = $('#from_section_id');
                    sectionSelect.empty().append('<option value="">All Sections</option>');

                    if (response.success && response.sections.length > 0) {
                        $.each(response.sections, function(index, section) {
                            sectionSelect.append('<option value="' + section.id + '">' + section.name + '</option>');
                        });
                    }
                    sectionSelect.prop('disabled', false);
                    validatePromotion();
                },
                complete: function() {
                    $('#loadingSpinner').hide();
                }
            });
        }
        updateStepUI();
    });

    $('#to_class_id').change(function() {
        let classId = $(this).val();
        updateTargetDisplay();

        if (classId) {
            $.ajax({
                url: '{{ route("ajax.sections.by.class") }}',
                type: 'GET',
                data: {
                    class_id: classId
                },
                success: function(response) {
                    let sectionSelect = $('#to_section_id');
                    sectionSelect.empty().append('<option value="">Select Section</option>');

                    if (response.success && response.sections.length > 0) {
                        $.each(response.sections, function(index, section) {
                            sectionSelect.append('<option value="' + section.id + '">' + section.name + '</option>');
                        });
                    }
                    sectionSelect.prop('disabled', false);
                    validatePromotion();
                }
            });
        }
        updateStepUI();
    });

    $('#from_section_id, #to_section_id').change(function() {
        updateStepUI();
    });

    function validatePromotion() {
        let fromClass = $('#from_class_id').val();
        let toClass = $('#to_class_id').val();
        let fromAcademicYear = $('#from_academic_year').val();
        let toAcademicYear = $('#to_academic_year').val();

        if (fromClass && toClass && fromAcademicYear && toAcademicYear) {
            $.ajax({
                url: '{{ route("ajax.validate.promotion") }}',
                type: 'GET',
                data: {
                    from_class_id: fromClass,
                    to_class_id: toClass,
                    from_academic_year: fromAcademicYear,
                    to_academic_year: toAcademicYear
                },
                success: function(response) {
                    if (response.success === false) {
                        $('#departmentChangeWarning').show();
                        $('#departmentChangeWarning').removeClass('alert-warning alert-info').addClass('alert-danger');
                        $('#departmentChangeMessage').html('<i class="fas fa-times-circle me-2"></i>' + response.message);
                        $('#nextToStep2').prop('disabled', true);
                    } else if (response.success === true) {
                        $('#departmentChangeWarning').show();
                        
                        if (response.promotion_type === 'vertical_progression') {
                            $('#departmentChangeWarning').removeClass('alert-warning alert-danger').addClass('alert-success');
                            $('#departmentChangeMessage').html('<i class="fas fa-arrow-right me-2"></i>' + response.message);
                        } else if (response.department_change) {
                            $('#departmentChangeWarning').removeClass('alert-success alert-danger').addClass('alert-warning');
                            $('#departmentChangeMessage').html('<i class="fas fa-exclamation-triangle me-2"></i>' + response.message);
                        } else {
                            $('#departmentChangeWarning').removeClass('alert-warning alert-danger').addClass('alert-info');
                            $('#departmentChangeMessage').html('<i class="fas fa-info-circle me-2"></i>' + response.message);
                        }
                        
                        $('#nextToStep2').prop('disabled', false);
                    }
                },
                error: function() {
                    $('#departmentChangeWarning').hide();
                    $('#nextToStep2').prop('disabled', true);
                }
            });
        }
    }

    function loadStudents() {
        let classId = $('#from_class_id').val();
        let sectionId = $('#from_section_id').val();
        let academicYear = $('#from_academic_year').val();

        if (classId && academicYear) {
            $('#loadingSpinner').show();

            $.ajax({
                url: '{{ route("ajax.students.for.promotion") }}',
                type: 'GET',
                data: {
                    class_id: classId,
                    section_id: sectionId,
                    academic_year: academicYear
                },
                success: function(response) {
                    if (response.success) {
                        displayStudents(response.students);
                        $('#studentCount').text(response.total_count + ' students');
                        $('#statsTotalStudents').text(response.total_count);
                        $('#activeStudentsInfo').show();
                        $('#activeStudentsMessage').html(
                            '<i class="fas fa-check-circle me-2"></i>' +
                            'Showing students from ' + response.academic_year + '. Total: ' +
                            response.total_count + ' students ready for promotion.'
                        );

                        if (response.total_count > 0) {
                            $('#promoteAllBtn').prop('disabled', false);
                        } else {
                            $('#promoteSelectedBtn, #promoteAllBtn').prop('disabled', true);
                        }

                        selectedStudents = [];
                        $('#selectedCount').text('0');
                        $('#statsSelectedStudents').text('0');
                        $('#selectAllCheckbox').prop('checked', false);
                        updateStepUI();
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.course_not_completed) {
                        $('#activeStudentsInfo').removeClass('alert-success').addClass('alert-warning').show();
                        $('#activeStudentsMessage').html(
                            '<i class="fas fa-exclamation-triangle me-2"></i>' +
                            (xhr.responseJSON.message || 'Course not completed yet')
                        );
                        $('#studentsTableBody').html('<tr><td colspan="9" class="text-center py-5"><i class="fas fa-calendar-times fa-2x mb-2 d-block"></i>Course end date: ' + 
                            (xhr.responseJSON.end_date ? new Date(xhr.responseJSON.end_date).toLocaleDateString() : 'Not set') + 
                            '<br>Promotion is only allowed after course completion.</td></tr>');
                        $('#studentCount').text('0 students');
                        $('#statsTotalStudents').text('0');
                        $('#promoteAllBtn, #promoteSelectedBtn').prop('disabled', true);
                    } else {
                        $('#activeStudentsInfo').removeClass('alert-success').addClass('alert-danger').show();
                        $('#activeStudentsMessage').html(
                            '<i class="fas fa-exclamation-circle me-2"></i>' +
                            (xhr.responseJSON?.message || 'Error loading students')
                        );
                    }
                },
                complete: function() {
                    $('#loadingSpinner').hide();
                }
            });
        }
    }

    function displayStudents(students) {
        let tbody = $('#studentsTableBody');
        tbody.empty();

        if (students.length === 0) {
            tbody.append(
                '<tr><td colspan="9" class="text-center py-5 text-muted">' +
                'No students found for the selected criteria</td></tr>'
            );
            return;
        }

        $.each(students, function(index, student) {
            let row = '<tr>' +
                '<td><input type="checkbox" class="form-check-input student-checkbox" value="' +
                student.student_hash_id + '" data-name="' + student.full_name + '" data-regno="' + (student.registration_number || '') + '"></td>' +
                '<td>' + (student.registration_number || '-') + '</td>' +
                '<td>' + student.full_name + '</td>' +
                '<td><span class="badge bg-success">Active</span></td>' +
                '<td>' + (student.current_department || '-') + '</td>' +
                '<td>' + (student.current_class || '-') + '</td>' +
                '<td>' + (student.current_section_name || '-') + '</td>' +
                '<td>' + (student.current_academic_year || $('#from_academic_year option:selected').text()) + '</td>' +
                '</tr>';
            tbody.append(row);
        });

        bindCheckboxEvents();
    }

    function bindCheckboxEvents() {
        $('#selectAllCheckbox').off('click');
        $('tbody .student-checkbox').off('click');
        $('#selectAllBtn').off('click');
        $('#deselectAllBtn').off('click');

        $('#selectAllCheckbox').on('click', function() {
            let isChecked = $(this).prop('checked');
            $('tbody .student-checkbox').prop('checked', isChecked);
            updateSelectedStudents();
        });

        $('tbody .student-checkbox').on('click', function() {
            let allChecked = $('tbody .student-checkbox:checked').length === $('tbody .student-checkbox').length;
            $('#selectAllCheckbox').prop('checked', allChecked);
            updateSelectedStudents();
        });

        $('#selectAllBtn').on('click', function() {
            $('tbody .student-checkbox').prop('checked', true);
            $('#selectAllCheckbox').prop('checked', true);
            updateSelectedStudents();
        });

        $('#deselectAllBtn').on('click', function() {
            $('tbody .student-checkbox').prop('checked', false);
            $('#selectAllCheckbox').prop('checked', false);
            selectedStudents = [];
            updateSelectedStudents();
        });
    }

    function updateSelectedStudents() {
        selectedStudents = [];
        $('tbody .student-checkbox:checked').each(function() {
            selectedStudents.push($(this).val());
        });

        $('#selectedCount').text(selectedStudents.length);
        $('#statsSelectedStudents').text(selectedStudents.length);

        $('#promoteSelectedBtn').prop('disabled', selectedStudents.length === 0);
        $('#nextToStep3').prop('disabled', selectedStudents.length === 0 && parseInt($('#statsTotalStudents').text()) === 0);
        updateStepUI();
    }

    function generatePromotionSummary(response) {
        let summary = '<div class="promotion-summary">';

        summary += '<div class="alert alert-success">';
        summary += '<h5><i class="fas fa-check-circle me-2"></i>' + response.message + '</h5>';
        summary += '</div>';

        summary += '<div class="row mt-4">';
        summary += '<div class="col-md-3">';
        summary += '<div class="stats-card">';
        summary += '<div class="stats-number">' + (response.promoted_count || 0) + '</div>';
        summary += '<div class="stats-label">Promoted</div>';
        summary += '</div></div>';

        summary += '<div class="col-md-3">';
        summary += '<div class="stats-card">';
        summary += '<div class="stats-number">' + (response.fee_pending_count || 0) + '</div>';
        summary += '<div class="stats-label">Fee Pending</div>';
        summary += '</div></div>';

        summary += '<div class="col-md-3">';
        summary += '<div class="stats-card">';
        summary += '<div class="stats-number">' + (response.failed_count || 0) + '</div>';
        summary += '<div class="stats-label">Failed</div>';
        summary += '</div></div>';

        summary += '<div class="col-md-3">';
        summary += '<div class="stats-card">';
        summary += '<div class="stats-number">' + response.from_academic_year + '</div>';
        summary += '<div class="stats-label">From Year</div>';
        summary += '</div></div>';
        summary += '</div>';

        summary += '<div class="alert alert-info mt-4">';
        summary += '<i class="fas fa-route me-2"></i>';
        summary += '<strong>Promotion Path:</strong> ';
        summary += $('#from_department_id option:selected').text() + ' → ' + $('#to_department_id option:selected').text() + ' | ';
        summary += $('#from_class_id option:selected').text() + ' → ' + $('#to_class_id option:selected').text();
        if (response.department_changed) {
            summary += ' <span class="badge bg-warning text-dark">Department Changed</span>';
        }
        summary += '</div>';

        if (response.fee_pending_count > 0 && response.fee_pending_students) {
            summary += '<div class="mt-4">';
            summary += '<h5 class="mb-3"><i class="fas fa-rupee-sign text-warning me-2"></i>Students Skipped Due to Pending Fees</h5>';
            summary += '<div class="table-container">';
            summary += '<table class="table table-bordered">';
            summary += '<thead class="bg-light">';
            summary += '<tr><th>#</th><th>Registration No.</th><th>Student Name</th><th>Pending Fees</th></tr>';
            summary += '</thead><tbody>';

            $.each(response.fee_pending_students, function(index, student) {
                summary += '<tr>';
                summary += '<td>' + (index + 1) + '</td>';
                summary += '<td>' + (student.registration_number || '-') + '</td>';
                summary += '<td><strong>' + student.name + '</strong></td>';
                summary += '<td><span class="badge bg-warning text-dark">' + student.pending_fees + '</span></td>';
                summary += '</tr>';
            });

            summary += '</tbody></table></div></div>';
        }

        if (response.failed_count > 0 && response.failed_students) {
            summary += '<div class="mt-4">';
            summary += '<h5 class="mb-3"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Failed Students</h5>';
            summary += '<div class="table-container">';
            summary += '<table class="table table-bordered">';
            summary += '<thead class="bg-light">';
            summary += '<tr><th>#</th><th>Student Name</th><th>Error Message</th></tr>';
            summary += '</thead><tbody>';

            $.each(response.failed_students, function(index, failed) {
                summary += '<tr>';
                summary += '<td>' + (index + 1) + '</td>';
                summary += '<td>' + (failed.name || failed.id || 'Unknown') + '</td>';
                summary += '<td class="text-danger">' + (failed.reason || 'Unknown error') + '</td>';
                summary += '</tr>';
            });

            summary += '</tbody></table></div></div>';
        }

        summary += '<div class="text-muted text-end mt-4">';
        summary += '<small><i class="far fa-clock me-1"></i>Promoted on: ' + new Date().toLocaleString() + '</small>';
        summary += '</div>';

        summary += '</div>';
        return summary;
    }
    
    function updateSummaryDisplays() {
        $('#statsFromYear').text($('#from_academic_year option:selected').text() || '-');
        $('#statsToYear').text($('#to_academic_year option:selected').text() || '-');
        updateSourceDisplay();
        updateTargetDisplay();
    }

    $('#promoteSelectedBtn, #promoteAllBtn').click(function() {
        let isAll = $(this).attr('id') === 'promoteAllBtn';

        if (isAll && !validatePromotionInputs()) return;
        if (!isAll && selectedStudents.length === 0) {
            alert('Please select at least one student');
            return;
        }

        let fromYear = $('#from_academic_year option:selected').text();
        let toYear = $('#to_academic_year option:selected').text();
        let fromClass = $('#from_class_id option:selected').text();
        let toClass = $('#to_class_id option:selected').text();

        let message = '<div class="text-center">';
        message += '<p>Are you sure you want to promote ';
        message += isAll ? 'ALL <strong>' + $('#statsTotalStudents').text() + '</strong> students' :
            '<strong>' + selectedStudents.length + '</strong> selected student(s)';
        message += '?</p>';
        message += '<div class="bg-light p-3 rounded text-start">';
        message += '<p><strong>From:</strong> ' + fromClass + ' (' + fromYear + ')</p>';
        message += '<p><strong>To:</strong> ' + toClass + ' (' + toYear + ')</p>';

        if (!isAll && selectedStudents.length > 0) {
            message += '<hr>';
            message += '<p><strong>Selected Students:</strong></p>';
            message += '<ul class="list-unstyled">';
            let count = 0;
            $('.student-checkbox:checked').each(function() {
                if (count < 5) {
                    let name = $(this).data('name');
                    let regno = $(this).data('regno');
                    message += '<li><i class="fas fa-user text-primary me-2"></i>' + name + (regno ? ' (' + regno + ')' : '') + '</li>';
                }
                count++;
            });
            if (count > 5) {
                message += '<li class="text-muted">...and ' + (count - 5) + ' more</li>';
            }
            message += '</ul>';
        }

        message += '</div></div>';

        pendingPromotionData = {
            type: isAll ? 'all' : 'selected',
            studentIds: isAll ? null : selectedStudents
        };

        $('#confirmModalBody').html(message);
        $('#confirmModal').modal('show');
    });

    $('#confirmPromotionBtn').click(function() {
        $('#confirmModal').modal('hide');

        if (!pendingPromotionData) return;

        let data = {
            from_class_id: $('#from_class_id').val(),
            to_class_id: $('#to_class_id').val(),
            from_section_id: $('#from_section_id').val(),
            to_section_id: $('#to_section_id').val(),
            from_academic_year: $('#from_academic_year').val(),
            to_academic_year: $('#to_academic_year').val(),
            _token: '{{ csrf_token() }}'
        };

        let url = pendingPromotionData.type === 'selected' ?
            '{{ route("ajax.promote.students") }}' :
            '{{ route("ajax.promote.all.students") }}';

        if (pendingPromotionData.type === 'selected') {
            data.student_ids = pendingPromotionData.studentIds;
        }

        $('#loadingSpinner').show();

        $.ajax({
            url: url,
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function(response) {
                let summary = generatePromotionSummary(response);

                $('#resultModalTitle').html('<i class="fas ' + (response.success ? 'fa-check-circle' : 'fa-exclamation-circle') + ' me-2"></i>' +
                    (response.success ? 'Promotion Successful' : 'Promotion Completed'));
                $('#resultModalBody').html(summary);
                $('#resultModal').modal('show');

                loadStudents();
                goToStep(2);

                selectedStudents = [];
                $('#selectAllCheckbox').prop('checked', false);
                $('#selectedCount').text('0');
                $('#statsSelectedStudents').text('0');
                $('#promoteSelectedBtn').prop('disabled', true);
                updateStepUI();
            },
            error: function(xhr) {
                let message = 'An error occurred';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                $('#resultModalTitle').html('<i class="fas fa-exclamation-circle me-2 text-danger"></i>Error');
                $('#resultModalBody').html('<div class="alert alert-danger">' + message + '</div>');
                $('#resultModal').modal('show');
            },
            complete: function() {
                $('#loadingSpinner').hide();
                pendingPromotionData = null;
            }
        });
    });

    function validatePromotionInputs() {
        if (!$('#from_academic_year').val() || !$('#to_academic_year').val()) {
            alert('Please select academic years');
            return false;
        }
        if (!$('#from_department_id').val() || !$('#to_department_id').val()) {
            alert('Please select departments');
            return false;
        }
        if (!$('#from_class_id').val() || !$('#to_class_id').val()) {
            alert('Please select classes');
            return false;
        }
        
        return true;
    }

    // Initial bindings
    bindCheckboxEvents();
});
</script>
@endsection