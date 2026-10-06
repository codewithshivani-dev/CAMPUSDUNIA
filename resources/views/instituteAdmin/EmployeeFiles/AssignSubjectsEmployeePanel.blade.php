@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<!-- Bootstrap Icons CDN - Latest version -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --primary-color: #4361ee;
    --secondary-color: #3a0ca3;
    --success-gradient: linear-gradient(135deg, #10b981, #059669);
    --success-color: #10b981;
    --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
    --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
    --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
    --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
}

body {
    background-color: #f8fafc;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.container {
    max-width: 1200px;
    margin: 30px auto;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px 30px;
    background: var(--primary-gradient);
    border-radius: 16px;
    box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
}

.page-header h3 {
    font-size: 24px;
    font-weight: 700;
    color: white;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

.page-header h3 i {
    font-size: 28px;
    background: rgba(255, 255, 255, 0.2);
    padding: 8px;
    border-radius: 12px;
}

.page-header .btn-secondary {
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    border-radius: 12px;
    padding: 10px 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    backdrop-filter: blur(5px);
    transition: all 0.3s;
    text-decoration: none;
}

.page-header .btn-secondary:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    color: white;
}

/* Alert Messages */
.alert {
    border: none;
    border-radius: 12px;
    padding: 15px 20px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 12px;
    animation: slideIn 0.3s ease;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.alert-success {
    background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    border: 1px solid #86efac;
    color: #166534;
}

.alert-danger {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border: 1px solid #fca5a5;
    color: #991b1b;
}

.alert .close {
    filter: invert(1) grayscale(100%) brightness(200%);
    opacity: 0.8;
    color: white;
    text-shadow: none;
}

/* Main Card */
.main-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    border: none;
    overflow: hidden;
}

.card-header-custom {
    background: var(--primary-gradient);
    color: white;
    padding: 20px 30px;
    border: none;
}

.card-header-custom h4 {
    margin: 0;
    font-weight: 700;
    font-size: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header-custom h4 i {
    font-size: 24px;
    background: rgba(255, 255, 255, 0.2);
    padding: 8px;
    border-radius: 12px;
}

.card-body-custom {
    padding: 30px;
}

/* Form Sections */
.form-section {
    background: linear-gradient(135deg, #ffffff, #f8fafc);
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    padding: 25px;
    margin-bottom: 25px;
    transition: all 0.3s;
}

.form-section:hover {
    border-color: var(--primary-color);
    box-shadow: 0 10px 30px rgba(67, 97, 238, 0.1);
}

.form-section h5 {
    color: var(--primary-color);
    border-bottom: 2px solid var(--primary-color);
    padding-bottom: 12px;
    margin-bottom: 20px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-section h5 i {
    font-size: 20px;
    background: white;
    padding: 6px;
    border-radius: 8px;
    color: var(--primary-color);
}

.form-section h6 {
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-section h6 i {
    font-size: 18px;
}

/* Form Labels */
.form-label {
    font-weight: 600;
    color: #475569;
    margin-bottom: 8px;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    display: block;
}

.required:after {
    content: " *";
    color: #ef4444;
    font-weight: 700;
}

/* Form Controls */
.form-control,
.form-select {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 15px;
    font-size: 14px;
    transition: all 0.3s;
    background: white;
    height: auto;
    width: 100%;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    outline: none;
}

.form-control:hover,
.form-select:hover {
    border-color: var(--secondary-color);
}

.form-control:disabled,
.form-select:disabled {
    background-color: #f1f5f9;
    cursor: not-allowed;
    border-color: #e2e8f0;
}

select[multiple] {
    min-height: 120px;
    padding: 8px;
}

select[multiple] option {
    padding: 8px 12px;
    border-radius: 6px;
    margin: 2px 0;
}

select[multiple] option:hover {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
}

select[multiple] option:checked {
    background: var(--primary-gradient);
    color: white;
}

/* Semester Options */
.semester-options-container {
    background: linear-gradient(135deg, #f8fafc, #ffffff);
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 15px;
}

.semester-option {
    display: flex;
    align-items: flex-start;
    margin-bottom: 15px;
    padding: 12px;
    border-radius: 10px;
    transition: all 0.3s;
}

.semester-option:hover {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
}

.semester-option input[type="radio"] {
    width: 18px;
    height: 18px;
    margin-right: 12px;
    margin-top: 2px;
    cursor: pointer;
    border: 2px solid #cbd5e1;
    border-radius: 50%;
    transition: all 0.2s;
    accent-color: var(--primary-color);
}

.semester-option input[type="radio"]:hover {
    border-color: var(--primary-color);
    transform: scale(1.1);
}

.semester-option label {
    margin: 0;
    font-weight: 500;
    cursor: pointer;
    flex: 1;
}

.semester-option-desc {
    font-size: 12px;
    color: #64748b;
    margin-top: 4px;
}

#specific_semester_container {
    margin-top: 15px;
    padding: 20px;
    background: linear-gradient(135deg, #f8fafc, #ffffff);
    border: 2px solid #e2e8f0;
    border-radius: 12px;
}

/* Lecture Timing Box */
.lecture-box {
    background: linear-gradient(135deg, #ffffff, #f8fafc);
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s;
}

.lecture-box:hover {
    border-color: var(--primary-color);
    box-shadow: 0 5px 20px rgba(67, 97, 238, 0.1);
}

.lecture-box h6 {
    color: var(--primary-color);
    border-bottom: 2px dashed #e2e8f0;
    padding-bottom: 12px;
    margin-bottom: 20px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
}

.lecture-box h6 i {
    font-size: 18px;
}

/* Section Warning */
.alert-warning {
    background: linear-gradient(135deg, #fff3cd, #ffe69c);
    border: 2px solid #ffc107;
    color: #856404;
    border-radius: 10px;
    padding: 12px 15px;
    margin-top: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-warning i {
    font-size: 18px;
}

/* Checkbox Groups */
.weekly-wrap,
.monthly-wrap {
    background: linear-gradient(135deg, #f8fafc, #ffffff);
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px;
    margin-top: 10px;
}

.weekly-wrap label {
    display: inline-flex;
    align-items: center;
    margin-right: 12px;
    margin-bottom: 8px;
    font-size: 13px;
    cursor: pointer;
}

.weekly-wrap input[type="checkbox"] {
    width: 16px;
    height: 16px;
    margin-right: 4px;
    cursor: pointer;
    border: 2px solid #cbd5e1;
    border-radius: 3px;
    accent-color: var(--primary-color);
}

/* Action Buttons */
.btn-primary {
    background: var(--primary-gradient);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 12px 24px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}

.btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn-primary:hover::before {
    left: 100%;
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
}

.btn-secondary {
    background: linear-gradient(135deg, #94a3b8, #64748b);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 12px 24px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #94a3b8, #64748b) !important;
    color: white !important;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(100, 116, 139, 0.3);
}

.btn-outline-secondary {
    background: transparent;
    border: 2px solid #e2e8f0;
    color: #475569;
    border-radius: 12px;
    padding: 12px 24px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
    text-decoration: none;
}

.btn-outline-secondary:hover {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    border-color: #cbd5e1;
    color: #1e293b;
    transform: translateY(-2px);
}

/* Invalid Feedback */
.is-invalid {
    border-color: #ef4444 !important;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #ef4444;
}

/* Error Styling */
.error {
    color: #ef4444;
    font-size: 12px;
    margin-top: 4px;
    display: block;
}

/* Small Text */
.text-muted {
    color: #94a3b8 !important;
    font-size: 12px;
    margin-top: 5px;
    display: block;
}

/* Responsive */
@media (max-width: 768px) {
    .container {
        padding: 15px;
        margin: 15px auto;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
        padding: 20px;
    }

    .page-header h3 {
        font-size: 20px;
    }

    .card-body-custom {
        padding: 20px;
    }

    .form-section {
        padding: 20px;
    }

    .d-flex.gap-2 {
        flex-direction: column;
    }

    .btn-primary,
    .btn-secondary,
    .btn-outline-secondary {
        width: 100%;
        justify-content: center;
    }

    .weekly-wrap label {
        display: block;
        margin-bottom: 8px;
    }
}
</style>

@php
$courseLabel = (isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type === 'School')
? 'Class'
: 'Course';
@endphp

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h3>
            <i class="bi bi-journal-bookmark-fill"></i>
            Assign Subjects to Employees
        </h3>
        <a href="{{ route('assign-subjects.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Assignments List
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Main Card -->
    <div class="main-card">
        <div class="card-header-custom">
            <h4>
                <i class="bi bi-person-plus-fill"></i>
                Assignment Form
            </h4>
        </div>
        <div class="card-body-custom">
            <form id="assignForm">
                @csrf

                <!-- Hidden field for all sections data -->
                <input type="hidden" id="all_sections_data" name="all_sections_data" value="">

                <!-- Course Information Section -->
                <div class="form-section">
                    <h5><i class="bi bi-diagram-3-fill"></i> Course Information</h5>

                    <!-- Category Selection -->
                    <div class="form-group mb-3">
                        <label class="form-label required">Category</label>
                        <select name="department_category_id" id="department_category_id" class="form-control" required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->department_category_id }}">{{ $category->category_name }}
                            </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="categoryError"></div>
                    </div>

                    <!-- Department Selection -->
                    <div class="form-group mb-3">
                        <label class="form-label required">Department</label>
                        <select name="department_id" id="department_id" class="form-control" required disabled>
                            <option value="">-- Select Department --</option>
                        </select>
                        <div class="invalid-feedback" id="departmentError"></div>
                    </div>

                    <!-- Employee Selection -->
                    <div class="form-group mb-3">
                        <label class="form-label required">Employee</label>
                        <select name="employee_id" id="employee_id" class="form-control" required disabled>
                            <option value="">-- Select Employee --</option>
                        </select>
                        <div class="invalid-feedback" id="employeeError"></div>
                    </div>

                    <!-- Course Type Selection -->
                    <div class="form-group mb-3">
                        <label class="form-label required">{{$courseLabel}} Type</label>
                        <select name="course_type" id="course_type" class="form-control" required disabled>
                            <option value="">-- Select {{$courseLabel}} Type --</option>
                        </select>
                        <div class="invalid-feedback" id="courseTypeError"></div>
                    </div>

                    <!-- Sub Type Selection -->
                    <div class="form-group mb-3">
                        <label class="form-label required">{{$courseLabel}} Sub Type</label>
                        <select name="course_detail_id" id="sub_type" class="form-control" required disabled>
                            <option value="">-- Select Sub Type --</option>
                        </select>
                        <div class="invalid-feedback" id="subtypeError"></div>
                    </div>

                    <!-- Section Selection -->
                    <div class="form-group mb-3">
                        <label class="form-label required">Section</label>
                        <select name="section_id" id="section_id" class="form-control" required disabled>
                            <option value="">-- Select Section --</option>
                            <option value="all">All Sections</option>
                        </select>
                        <div class="invalid-feedback" id="sectionError"></div>
                    </div>
                </div>

                <!-- Semester/Term Assignment Section -->
                <div class="form-section">
                    <h5><i class="bi bi-calendar-range-fill"></i> Semester/Term Assignment</h5>

                    <div class="semester-options-container">
                        <!-- Option 1: All Semesters/Terms -->
                        <div class="semester-option">
                            <input type="radio" id="semester_option_all" name="semester_option" value="all" checked>
                            <label for="semester_option_all">
                                All Semesters/Terms
                                <div class="semester-option-desc">Subject will be assigned to all semesters/terms of
                                    this course</div>
                            </label>
                        </div>

                        <!-- Option 2: Specific Semester/Term -->
                        <div class="semester-option">
                            <input type="radio" id="semester_option_specific" name="semester_option" value="specific">
                            <label for="semester_option_specific">
                                Specific Semester/Term
                                <div class="semester-option-desc">Select one specific semester/term for this subject
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Semester Selection Container -->
                    <div id="specific_semester_container" style="display: none;">
                        <label class="form-label required">Select Semester/Term</label>
                        <select name="semester_id" id="semester_id" class="form-control">
                            <option value="">-- Select Semester/Term --</option>
                            <!-- Options will be loaded dynamically -->
                        </select>
                        <small class="text-muted">Only one semester/term can be selected</small>
                        <div class="invalid-feedback" id="semesterError"></div>
                    </div>
                </div>

                <!-- Subject Selection Section -->
                <div class="form-section">
                    <h5><i class="bi bi-book-fill"></i> Subject Selection</h5>

                    <div class="form-group mb-3">
                        <label class="form-label required">Select Subject(s)</label>
                        <select name="subject_id[]" id="subject_id" class="form-control" multiple required>
                            <option value="">-- First complete above selections --</option>
                        </select>
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple subjects or sub-subjects</small>
                        <div class="invalid-feedback" id="subjectError"></div>
                    </div>
                </div>

                <!-- Lecture Timing Section -->
                <div class="form-section">
                    <h5><i class="bi bi-clock-fill"></i> Lecture Timing Details</h5>
                    <div id="lecture-timing-container">
                        <!-- Lecture timing boxes will be added dynamically here -->
                    </div>
                </div>

                <!-- Assignment Details Section -->
                <div class="form-section">
                    <h5><i class="bi bi-calendar-check-fill"></i> Assignment Details</h5>

                    <!-- Assigned Date -->
                    <div class="form-group mb-3">
                        <label class="form-label required">Assigned Date</label>
                        <input type="date" name="assigned_date" id="assigned_date" class="form-control"
                            value="{{ date('Y-m-d') }}" required>
                        <div class="invalid-feedback" id="dateError"></div>
                    </div>

                    <!-- Remarks -->
                    <div class="form-group mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" id="remarks" class="form-control"
                            placeholder="Enter any remarks (optional)" rows="2" maxlength="255"></textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between gap-3 mt-4">
                    <div>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-save"></i> Assign Subjects
                        </button>
                    </div>
                    <div>
                        <button type="reset" class="btn btn-secondary" onclick="resetForm()">
                            <i class="bi bi-arrow-repeat"></i> Reset
                        </button>
                        <a href="{{ route('assign-subjects.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i> Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS for alerts/dismissals -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<script>

// ALL JAVASCRIPT REMAINS EXACTLY THE SAME - NOT CHANGED
const instituteType = "{{ $serviceInstitutedetails->type ?? '' }}";
const courseLabel = instituteType === 'School' ? 'Class' : 'Course';
const courseModes = @json($courseModes ?? []);
$(document).ready(function() {
    let subjectMap = {}; // {subject_id: {semester, name}}
    let currentProductId = null;
    let hasAllSemestersSubjects = false;
    let hasSpecificSemesterSubjects = false;
    let allSectionsData = [];

    // ---------------- Reset Form ----------------
    window.resetForm = function() {
        $('#assignForm')[0].reset();
        $('#department_id').prop('disabled', true).html(
            '<option value="">-- Select Department --</option>');
        $('#employee_id').prop('disabled', true).html('<option value="">-- Select Employee --</option>');
        $('#course_type').prop('disabled', true).html('<option value="">-- Select Course Type --</option>');
        $('#sub_type').prop('disabled', true).html('<option value="">-- Select Sub Type --</option>');
        $('#semester_id').html('<option value="">-- Select Semester --</option>');
        $('#subject_id').html('<option value="">-- First complete above selections --</option>');
        $('#lecture-timing-container').empty();
        $('#specific_semester_container').hide();
        $('#semester_option_all').prop('checked', true);
        subjectMap = {};
        currentProductId = null;
        hasAllSemestersSubjects = false;
        hasSpecificSemesterSubjects = false;
        $('#assigned_date').val('{{ date("Y-m-d") }}');
        $('#all_sections_data').val('');
        hideSectionWarning();
    }

    // ---------------- Step 1: Category Selection ----------------
    $('#department_category_id').change(function() {
        let categoryId = $(this).val();

        // Reset downstream selects
        $('#department_id').prop('disabled', true).html(
            '<option value="">-- Select Department --</option>');
        $('#employee_id').prop('disabled', true).html(
            '<option value="">-- Select Employee --</option>');
        $('#course_type').prop('disabled', true).html(
            '<option value="">-- Select Course Type --</option>');
        $('#sub_type').prop('disabled', true).html('<option value="">-- Select Sub Type --</option>');
        $('#semester_id').html('<option value="">-- Select Semester --</option>');
        $('#subject_id').html('<option value="">-- First complete above selections --</option>');
        $('#lecture-timing-container').empty();

        if (categoryId) {
            $('#department_id').html('<option value="">Loading departments...</option>');

            // Use AjaxFunctionsCallController route
            $.get('/ajax/departments-by-category', {
                    category_id: categoryId
                })
                .done(function(response) {

                    $('#department_id').html('<option value="">-- Select Department --</option>');

                    // Handle both response formats
                    let departments = [];
                    if (response.status === 'success' && response.departments) {
                        departments = response.departments;
                    } else if (response.departments) {
                        // Direct departments array
                        departments = response.departments;
                    } else if (Array.isArray(response)) {
                        // Response is directly an array
                        departments = response;
                    }

                    if (departments && departments.length > 0) {
                        departments.forEach(dept => {
                            $('#department_id').append(
                                `<option value="${dept.department_id}">${dept.department}</option>`
                            );
                        });
                        $('#department_id').prop('disabled', false);
                    } else {
                        $('#department_id').html('<option value="">No departments found</option>');
                    }
                })
                .fail(function(xhr, status, error) {
                    console.error('Error loading departments:', error);
                    console.error('Response text:', xhr.responseText);
                    $('#department_id').html('<option value="">Error loading departments</option>');
                });
        }
    });

    // ---------------- Step 2: Department Selection ----------------
    $('#department_id').change(function() {
        let deptId = $(this).val();
        console.log('Department selected:', deptId); // Debug

        // Reset downstream selects
        $('#employee_id').prop('disabled', true).html(
            '<option value="">-- Select Employee --</option>');
        $('#course_type').prop('disabled', true).html(
            '<option value="">-- Select Course Type --</option>');
        $('#sub_type').prop('disabled', true).html('<option value="">-- Select Sub Type --</option>');
        $('#semester_id').html('<option value="">-- Select Semester --</option>');
        $('#subject_id').html('<option value="">-- First complete above selections --</option>');
        $('#lecture-timing-container').empty();

        if (deptId) {

            // Load employees
            $('#employee_id').html('<option value="">Loading employees...</option>');
            $.get('/ajax/employees-by-department', {
                    department_id: deptId
                })
                .done(function(response) {
                    // Check if response is valid
                    if (!response) {
                        console.error('Empty response from employees API');
                        $('#employee_id').html('<option value="">Error: Empty response</option>');
                        return;
                    }

                    $('#employee_id').html('<option value="">-- Select Employee --</option>');

                    let employees = [];
                    if (Array.isArray(response)) {
                        // Direct array response
                        employees = response;
                    } else if (response.data && Array.isArray(response.data)) {
                        // {data: [...]} format
                        employees = response.data;
                    } else if (response.employees && Array.isArray(response.employees)) {
                        // {employees: [...]} format
                        employees = response.employees;
                    } else if (response.status === 'success' && response.employees) {
                        // {status: 'success', employees: [...]} format
                        employees = response.employees;
                    }

                    if (employees.length > 0) {
                        employees.forEach(emp => {
                            $('#employee_id').append(
                                `<option value="${emp.employee_id}">${emp.name}</option>`
                            );
                        });
                        $('#employee_id').prop('disabled', false);
                    } else {
                        $('#employee_id').html('<option value="">No employees found</option>');
                    }
                })
                .fail(function(xhr, status, error) {
                    console.error('AJAX Error loading employees:', {
                        status: xhr.status,
                        error: error,
                        responseText: xhr.responseText
                    });
                    $('#employee_id').html('<option value="">Error loading employees</option>');
                });

            // Load course types
            $('#course_type').html('<option value="">Loading courses...</option>');
            $.get('/ajax/course-types-by-department', {
                    department_id: deptId
                })
                .done(function(response) {

                    // Check if response is valid
                    if (!response) {
                        console.error('Empty response from courses API');
                        $('#course_type').html('<option value="">Error: Empty response</option>');
                        return;
                    }

                    $('#course_type').html('<option value="">-- Select Course Type --</option>');

                    let courses = [];
                    if (Array.isArray(response)) {
                        // Direct array response
                        courses = response;
                    } else if (response.data && Array.isArray(response.data)) {
                        // {data: [...]} format
                        courses = response.data;
                    } else if (response.courses && Array.isArray(response.courses)) {
                        // {courses: [...]} format
                        courses = response.courses;
                    } else if (response.status === 'success' && response.courses) {
                        // {status: 'success', courses: [...]} format
                        courses = response.courses;
                    }

                    if (courses.length > 0) {
                        courses.forEach(course => {

                            // Get the actual course type (text value)
                            let courseTypeValue = course
                                .finacp_merchant_sub_category_type || course.course_type;
                            let courseTypeText = course.finacp_merchant_sub_category_type ||
                                course.course_type || 'Unknown';

                            // Store finacp ID in data attribute if available
                            let finacpId = course.finacp_merchant_sub_category_id || '';

                            $('#course_type').append(
                                `<option value="${courseTypeValue}" data-finacp-id="${finacpId}">${courseTypeText}</option>`
                            );
                        });
                        $('#course_type').prop('disabled', false);
                    } else {
                        $('#course_type').html('<option value="">No courses found</option>');
                    }
                })
                .fail(function(xhr, status, error) {
                    console.error('AJAX Error loading courses:', {
                        status: xhr.status,
                        error: error,
                        responseText: xhr.responseText
                    });
                    $('#course_type').html('<option value="">Error loading courses</option>');
                });
        }
    });


    // ---------------- Step 3: Course Type Selection ----------------
    $('#course_type').change(function() {
        let selectedOption = $(this).find('option:selected');
        let courseType = selectedOption.val();
        let finacpId = selectedOption.data('finacp-id');
        let deptId = $('#department_id').val();

        // Reset downstream selects
        $('#sub_type').prop('disabled', true).html('<option value="">-- Select Sub Type --</option>');
        $('#semester_id').html('<option value="">-- Select Semester --</option>');
        $('#subject_id').html('<option value="">-- First complete above selections --</option>');
        $('#lecture-timing-container').empty();

        if (courseType && deptId) {
            $('#sub_type').html('<option value="">Loading sub types...</option>');

            // Send BOTH course_type (name) and finacp_id to controller
            $.ajax({
                url: '/ajax/get-branches-by-course',
                method: 'GET',
                data: {
                    department_id: deptId,
                    course_type: courseType, // Send course name
                    finacp_merchant_sub_category_id: finacpId // Also send ID as backup
                },
                success: function(response) {

                    $('#sub_type').html('<option value="">-- Select Sub Type --</option>');

                    // Check for error response first
                    if (response.status === 'error') {
                        console.error('API Error:', response.message);
                        $('#sub_type').html(
                            `<option value="">Error: ${response.message}</option>`);
                        return;
                    }

                    // Handle response formats
                    let branches = [];
                    if (response.status === 'success' && response.branches) {
                        branches = response.branches;
                    } else if (response.branches && Array.isArray(response.branches)) {
                        branches = response.branches;
                    } else if (Array.isArray(response)) {
                        branches = response;
                    } else if (response.data && Array.isArray(response.data)) {
                        branches = response.data;
                    }

                    if (branches && branches.length > 0) {
                        branches.forEach(item => {
                            $('#sub_type').append(
                                `<option value="${item.product_id}">${item.sub_type}</option>`
                            );
                        });
                        $('#sub_type').prop('disabled', false);
                    } else {
                        $('#sub_type').html('<option value="">No sub types found</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error loading sub types:', {
                        status: xhr.status,
                        error: error,
                        responseText: xhr.responseText
                    });
                    $('#sub_type').html(
                        '<option value="">Error loading sub types</option>');
                }
            });
        }
    });
    let currentCourseMode = 'offline';

    // ---------------- Step 4: Sub Type Selection ----------------
    $('#sub_type').change(function() {
        currentProductId = $(this).val();
        currentCourseMode = courseModes[currentProductId] || 'offline';
        // Reset downstream selects
        $('#section_id').prop('disabled', true).html(
            '<option value="">-- Select Section --</option><option value="all">All Sections</option>'
        );
        $('#semester_id').html('<option value="">-- Select Semester --</option>');
        $('#subject_id').html('<option value="">-- First complete above selections --</option>');
        $('#lecture-timing-container').empty();

        if (currentProductId) {
            // Load sections first
            loadSections(currentProductId);

            // Load available semesters for this product
            $('#semester_id').html('<option value="">Loading semesters...</option>');
            $.get('/get-semesters-by-subtype/' + currentProductId)
                .done(function(semesters) {

                    // Check if we have 'all_semesters' subjects
                    hasAllSemestersSubjects = semesters.some(s => s.semester_id ===
                        'all_semesters');
                    hasSpecificSemesterSubjects = semesters.some(s => s.semester_id !==
                        'all_semesters');

                    // Update UI based on what's available
                    updateSemesterOptionsUI();

                    // Load semester dropdown for specific option
                    $('#semester_id').html('<option value="">-- Select Semester --</option>');
                    semesters.forEach(semester => {
                        if (semester.semester_id !== 'all_semesters') {
                            $('#semester_id').append(
                                `<option value="${semester.semester_id}">${semester.semester_id}</option>`
                            );
                        }
                    });

                    // Auto-load subjects if "All Semesters" is selected and available
                    if ($('#semester_option_all').is(':checked') && hasAllSemestersSubjects) {
                        loadSubjects(currentProductId, 'all_semesters');
                    }
                })
                .fail(function(xhr, status, error) {
                    console.error('Error loading semesters:', error);
                    $('#semester_id').html('<option value="">Error loading semesters</option>');
                });
        }
    });

    // ---------------- Helper: Load Sections ----------------
    function loadSections(productId) {
        $('#section_id').html(
            '<option value="">Loading sections...</option><option value="all">All Sections</option>');

        $.ajax({
            url: '/get-sections-by-product/' + productId,
            method: 'GET',
            success: function(response) {

                if (response.success) {
                    if (response.sections && response.sections.length > 0) {
                        // Store all sections data globally
                        allSectionsData = response.sections;

                        $('#section_id').html('<option value="">-- Select Section --</option>');

                        // Add "All Sections" option with count
                        $('#section_id').append(
                            `<option value="all">All Sections (${response.sections.length} sections)</option>`
                        );

                        // Add individual sections
                        response.sections.forEach(section => {
                            $('#section_id').append(
                                `<option value="${section.section_id}">${section.section_name}</option>`
                            );
                        });

                        $('#section_id').prop('disabled', false);

                        // Show warning if selecting "All Sections"
                        $('#section_id').change(function() {
                            if ($(this).val() === 'all') {
                                // Store all section IDs in hidden field
                                const sectionIds = response.sections.map(s => s.section_id);
                                $('#all_sections_data').val(JSON.stringify(sectionIds));
                                showSectionWarning(response.sections.length);
                            } else {
                                $('#all_sections_data').val('');
                                hideSectionWarning();
                            }
                        });

                    } else {
                        // No sections found
                        console.warn('No sections found in response:', response);
                        $('#section_id').html(
                            '<option value="all">All Sections (No specific sections defined)</option>'
                        );
                        $('#section_id').prop('disabled', false);
                    }
                } else {
                    // API returned success: false
                    console.error('API Error:', response.message || 'Unknown error');
                    $('#section_id').html(
                        '<option value="all">All Sections (Error loading sections)</option>');
                    $('#section_id').prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error loading sections:', {
                    status: xhr.status,
                    error: error,
                    responseText: xhr.responseText
                });
                $('#section_id').html('<option value="all">All Sections (Network error)</option>');
                $('#section_id').prop('disabled', false);
            }
        });
    }

    // ---------------- Show warning for "All Sections" selection ----------------
    function showSectionWarning(sectionCount) {
        // Remove existing warning if any
        hideSectionWarning();

        // Create warning message
        const warningHtml = `
            <div class="alert-warning" id="sectionWarning">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span><strong>Note:</strong> Selecting "All Sections" will create separate assignments for each of the ${sectionCount} sections. Each section will have its own assignment record.</span>
            </div>
        `;

        $('#section_id').after(warningHtml);
    }

    function hideSectionWarning() {
        $('#sectionWarning').remove();
    }

    // ---------------- Step 5: Semester Option Selection ----------------
    $('input[name="semester_option"]').change(function() {
        const selectedOption = $(this).val();

        if (selectedOption === 'specific') {
            $('#specific_semester_container').show();

            // If semester is already selected, load subjects
            const selectedSemester = $('#semester_id').val();
            if (selectedSemester && currentProductId) {
                loadSubjects(currentProductId, selectedSemester);
            } else {
                $('#subject_id').html('<option value="">-- Please select a semester --</option>');
                $('#lecture-timing-container').empty();
            }
        } else {
            $('#specific_semester_container').hide();
            // Load "All Semesters" subjects if available
            if (currentProductId && hasAllSemestersSubjects) {
                loadSubjects(currentProductId, 'all_semesters');
            } else if (currentProductId && !hasAllSemestersSubjects) {
                $('#subject_id').html(
                    '<option value="">No subjects available for all semesters</option>');
                $('#lecture-timing-container').empty();
            }
        }
    });

    // ---------------- Step 6: Specific Semester Selection ----------------
    $('#semester_id').change(function() {
        const semesterId = $(this).val();

        if (semesterId && currentProductId && $('#semester_option_specific').is(':checked')) {
            loadSubjects(currentProductId, semesterId);
        }
    });

    // ---------------- Helper: Update Semester Options UI ----------------
    function updateSemesterOptionsUI() {
        const allOption = $('#semester_option_all');
        const specificOption = $('#semester_option_specific');

        if (hasAllSemestersSubjects && !hasSpecificSemesterSubjects) {
            // Only "All Semesters" subjects exist
            allOption.prop('checked', true).prop('disabled', false);
            specificOption.prop('disabled', true);
            $('#specific_semester_container').hide();
        } else if (!hasAllSemestersSubjects && hasSpecificSemesterSubjects) {
            // Only specific semester subjects exist
            allOption.prop('disabled', true);
            specificOption.prop('checked', true).prop('disabled', false);
            $('#specific_semester_container').show();
        } else if (hasAllSemestersSubjects && hasSpecificSemesterSubjects) {
            // Both types exist
            allOption.prop('checked', true).prop('disabled', false);
            specificOption.prop('disabled', false);
            $('#specific_semester_container').hide();
        } else {
            // No subjects exist
            allOption.prop('disabled', true);
            specificOption.prop('disabled', true);
            $('#subject_id').html('<option value="">No subjects available</option>');
        }
    }

    // ---------------- Helper: Load Subjects ----------------
    function loadSubjects(productId, semesterId) {
        $('#subject_id').html('<option value="">Loading subjects...</option>');

        $.ajax({
            url: '/get-subjectsforassignment',
            method: 'POST',
            data: {
                product_id: productId,
                semester_id: semesterId,
                _token: '{{ csrf_token() }}'
            },
            success: function(subjects) {
                $('#subject_id').html('');
                subjectMap = {};

                if (subjects && subjects.length > 0) {
                    subjects.forEach(subject => {
                        // Add main subject to map
                        subjectMap[subject.subject_id] = {
                            semester: subject.semester_id,
                            name: subject.subject_name,
                            has_sub_subjects: subject.has_sub_subjects,
                            sub_subjects: subject.sub_subjects || []
                        };

                        // Create option group for main subject
                        if (subject.has_sub_subjects && subject.sub_subjects.length > 0) {
                            // Create option group for subject with sub-subjects
                            let optionGroup = $('<optgroup>', {
                                label: subject.subject_name + ' (has ' + subject
                                    .sub_subjects.length + ' sub-subjects)',
                                'data-subject-id': subject.subject_id
                            });

                            // Add main subject option
                            $('<option>', {
                                value: subject.subject_id,
                                text: subject.subject_name + ' (Main Subject)',
                                'data-is-main': true,
                                'data-has-sub-subjects': true,
                                'data-subject-type': 'main_subject'
                            }).appendTo(optionGroup);

                            // Add each sub-subject as separate option
                            subject.sub_subjects.forEach(subSub => {
                                $('<option>', {
                                    value: subSub.sub_subject_id,
                                    text: '  └── ' + subSub
                                        .sub_subject_name,
                                    'data-is-sub-subject': true,
                                    'data-parent-subject-id': subject
                                        .subject_id,
                                    'data-subject-type': 'sub_subject',
                                    'data-parent-name': subject
                                        .subject_name,
                                    'data-sub-subject-name': subSub
                                        .sub_subject_name
                                }).appendTo(optionGroup);
                            });

                            optionGroup.appendTo('#subject_id');
                        } else {
                            // Regular subject without sub-subjects
                            $('#subject_id').append(
                                `<option value="${subject.subject_id}" data-subject-type="main_subject">${subject.subject_name}</option>`
                            );
                        }
                    });
                } else {
                    $('#subject_id').html('<option value="">No subjects found</option>');
                }

                $('#lecture-timing-container').empty();
            },
            error: function(xhr, status, error) {
                console.error('Error loading subjects:', error);
                $('#subject_id').html('<option value="">Error loading subjects</option>');
            }
        });
    }

    // ---------------- Step 7: Subject Selection -> Timing Boxes ----------------
    $('#subject_id').change(function() {
        let selected = $(this).val() || [];
        let container = $('#lecture-timing-container');
        container.empty();

        // Generate timing boxes for each selected subject/sub-subject
        selected.forEach((selectedId, index) => {
            let selectedOption = $(this).find('option[value="' + selectedId + '"]');
            let subjectType = selectedOption.data('subject-type') || 'main_subject';
            let isSubSubject = selectedOption.data('is-sub-subject');
            let parentSubjectId = selectedOption.data('parent-subject-id');
            let parentSubjectName = selectedOption.data('parent-name');
            let subSubjectName = selectedOption.data('sub-subject-name');

            let subjectInfo = null;
            let displayName = '';

            // Use a unique key for each selected item
            let uniqueKey = selectedId + '_' + Date.now() + '_' + index;

            if (subjectType === 'sub_subject' && parentSubjectId) {
                // This is a sub-subject
                let parentSubject = subjectMap[parentSubjectId];
                if (parentSubject) {
                    subjectInfo = {
                        id: selectedId,
                        name: subSubjectName || 'Unknown Sub-subject',
                        parent_id: parentSubjectId,
                        parent_name: parentSubjectName || parentSubject.name,
                        semester: parentSubject.semester,
                        subject_type: 'sub_subject',
                        display_name: (parentSubjectName || parentSubject.name) + ' > ' + (
                            subSubjectName || 'Unknown'),
                        unique_key: uniqueKey
                    };
                    displayName = subjectInfo.display_name;
                }
            } else {
                // This is a main subject
                let mainSubject = subjectMap[selectedId];
                if (mainSubject) {
                    subjectInfo = {
                        id: selectedId,
                        name: mainSubject.name,
                        semester: mainSubject.semester,
                        subject_type: 'main_subject',
                        display_name: mainSubject.name,
                        unique_key: uniqueKey
                    };
                    displayName = mainSubject.name + ' (Main Subject)';
                }
            }

            if (subjectInfo) {
                // FIXED: Use currentCourseMode instead of courseMode
                const isOnlineDefault = (currentCourseMode === 'online');
                
                container.append(`
                    <div class="lecture-box" data-subject-id="${subjectInfo.id}" data-subject-type="${subjectInfo.subject_type}" data-unique-key="${subjectInfo.unique_key}">
                        <h6><i class="bi bi-clock-fill"></i> Lecture Timing for: ${displayName}</h6>
                        
                        <input type="hidden" name="subjects[${subjectInfo.unique_key}][subject_type]" value="${subjectInfo.subject_type}">
                        <input type="hidden" name="subjects[${subjectInfo.unique_key}][subject_id]" value="${subjectInfo.id}">
                        ${subjectInfo.subject_type === 'sub_subject' ? 
                            `<input type="hidden" name="subjects[${subjectInfo.unique_key}][parent_subject_id]" value="${subjectInfo.parent_id}">` : 
                            ''}
                        <input type="hidden" name="subjects[${subjectInfo.unique_key}][semester_id]" value="${subjectInfo.semester}">
                        <input type="hidden" name="subjects[${subjectInfo.unique_key}][display_name]" value="${subjectInfo.display_name}">
                        
                        <!-- Lecture Mode Selection -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label required">Class Mode</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" 
                                                name="subjects[${subjectInfo.unique_key}][lecture_mode]" 
                                                value="offline" 
                                                data-subject-key="${subjectInfo.unique_key}"
                                                id="offline_${subjectInfo.unique_key}" 
                                                ${!isOnlineDefault ? 'checked' : ''}>
                                            <label class="form-check-label" for="offline_${subjectInfo.unique_key}">
                                                <i class="bi bi-building"></i> Offline (Physical Classroom)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" 
                                                name="subjects[${subjectInfo.unique_key}][lecture_mode]" 
                                                value="online" 
                                                data-subject-key="${subjectInfo.unique_key}"
                                                id="online_${subjectInfo.unique_key}"
                                                ${isOnlineDefault ? 'checked' : ''}>
                                            <label class="form-check-label" for="online_${subjectInfo.unique_key}">
                                                <i class="bi bi-camera-video-fill"></i> Online (Virtual Class)
                                            </label>
                                        </div>
                                    </div>
                                    <small class="text-muted">Course default mode: ${currentCourseMode.toUpperCase()}</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Online Meeting Details (hidden by default unless course mode is online) -->
                        <div class="online-fields" id="online_fields_${subjectInfo.unique_key}" style="display: ${isOnlineDefault ? 'block' : 'none'};">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-2">
                                        <label class="form-label">Meeting Link </label>
                                        <input type="url" name="subjects[${subjectInfo.unique_key}][meeting_link]" 
                                            class="form-control meeting-link" 
                                            placeholder="https://meet.google.com/xxx-xxxx-xxx">
                                        <small class="text-muted">Google Meet, Zoom, Microsoft Teams, etc.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label">Meeting ID (Optional)</label>
                                        <input type="text" name="subjects[${subjectInfo.unique_key}][meeting_id]" 
                                            class="form-control" placeholder="Enter meeting ID or code">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label">Meeting Password (Optional)</label>
                                        <input type="text" name="subjects[${subjectInfo.unique_key}][meeting_password]" 
                                            class="form-control" placeholder="Enter meeting password">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-2">
                                        <label class="form-label">Meeting Instructions (Optional)</label>
                                        <input type="text" name="subjects[${subjectInfo.unique_key}][meeting_instructions]" 
                                            class="form-control" placeholder="e.g., Join 5 minutes early">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Rest of your existing fields (Frequency, Time, Location, etc.) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label class="form-label required">Frequency</label>
                                    <select name="subjects[${subjectInfo.unique_key}][frequency]" class="form-control freq-select" required>
                                        <option value="">-- Select --</option>
                                        <option value="one_time">One time</option>
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2 weekly-wrap" style="display:none;">
                                    <label class="form-label required">Days of Week</label><br>
                                    ${['Sun','Mon','Tue','Wed','Thu','Fri','Sat'].map(d => `
                                        <label class="me-2"><input type="checkbox" name="subjects[${subjectInfo.unique_key}][days_of_week][]" value="${d}"> ${d}</label>
                                    `).join('')}
                                </div>
                                
                                <div class="form-group mb-2 monthly-wrap" style="display:none;">
                                    <label class="form-label required">Day of Month (1-31)</label>
                                    <input type="number" class="form-control" name="subjects[${subjectInfo.unique_key}][day_of_month]" min="1" max="31">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label class="form-label required">Start Time</label>
                                    <input type="time" name="subjects[${subjectInfo.unique_key}][start_time]" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label class="form-label required">End Time</label>
                                    <input type="time" name="subjects[${subjectInfo.unique_key}][end_time]" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label class="form-label required">Valid From</label>
                                    <input type="date" name="subjects[${subjectInfo.unique_key}][valid_from]" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label class="form-label">Valid To (optional)</label>
                                    <input type="date" name="subjects[${subjectInfo.unique_key}][valid_to]" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-2">
                                    <label class="form-label">Building</label>
                                    <select name="subjects[${subjectInfo.unique_key}][building_id]" class="form-control building-select" data-subject-key="${subjectInfo.unique_key}">
                                        <option value="">Select Building</option>
                                        @if(isset($buildings))
                                            @foreach($buildings as $building)
                                                <option value="{{ $building->id }}">{{ $building->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-2">
                                    <label class="form-label">Block</label>
                                    <select name="subjects[${subjectInfo.unique_key}][block_id]" class="form-control block-select" data-subject-key="${subjectInfo.unique_key}" disabled>
                                        <option value="">Select Building First</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-2">
                                    <label class="form-label">Floor</label>
                                    <select name="subjects[${subjectInfo.unique_key}][floor_id]" class="form-control floor-select" data-subject-key="${subjectInfo.unique_key}" disabled>
                                        <option value="">Select Block First</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-2">
                                    <label class="form-label">Room</label>
                                    <select name="subjects[${subjectInfo.unique_key}][room_id]" class="form-control room-select" data-subject-key="${subjectInfo.unique_key}" disabled>
                                        <option value="">Select Floor First</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
        });

        // Frequency toggle inside each box
        container.find('.freq-select').change(function() {
            let box = $(this).closest('.lecture-box');
            box.find('.weekly-wrap').hide();
            box.find('.monthly-wrap').hide();
            if (this.value === 'weekly') box.find('.weekly-wrap').show();
            if (this.value === 'monthly') box.find('.monthly-wrap').show();
        });
    });

    // ---------------- Form Submission ----------------
    $('#assignForm').submit(function(e) {
        e.preventDefault();

        // Basic validation
        if ($('#subject_id').val().length === 0) {
            alert('Please select at least one subject.');
            return;
        }

        // Warn if "All Sections" is selected
        const selectedSection = $('#section_id').val();
        if (selectedSection === 'all') {
            if (!confirm(
                    'You have selected "All Sections". This will create separate assignments for each section. Do you want to continue?'
                )) {
                return;
            }
        }

        // Show loading
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin"></i> Processing...');

        // Create FormData
        let formData = new FormData(this);

        // If "all" is selected, send all section IDs
        if (selectedSection === 'all') {
            // Send all section IDs as array
            const allSectionsIds = allSectionsData.map(s => s.section_id);
            allSectionsIds.forEach(sectionId => {
                formData.append('section_ids[]', sectionId);
            });
            formData.append('section_selection', 'all');
        } else {
            // Send single section ID
            formData.append('section_ids[]', selectedSection);
            formData.append('section_selection', 'single');
        }

        $.ajax({
            url: "{{ route('assign-subjects.store') }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response.success ? response.message :
                    'Subjects assigned successfully!');
                location.reload();
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    errorMessage = xhr.responseText;
                }
                alert('Error: ' + errorMessage);
                console.error('Error response:', xhr.responseJSON);
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // ---------------- Location Selection ----------------
    // Building change event
    $(document).on('change', '.building-select', function() {
        const buildingId = $(this).val();
        const subjectKey = $(this).data('subject-key');
        const blockSelect = $(`.block-select[data-subject-key="${subjectKey}"]`);
        const floorSelect = $(`.floor-select[data-subject-key="${subjectKey}"]`);
        const roomSelect = $(`.room-select[data-subject-key="${subjectKey}"]`);

        // Reset dependent dropdowns
        blockSelect.html('<option value="">Select Building First</option>').prop('disabled', true);
        floorSelect.html('<option value="">Select Block First</option>').prop('disabled', true);
        roomSelect.html('<option value="">Select Floor First</option>').prop('disabled', true);

        if (buildingId) {
            $.ajax({
                url: "{{ route('ajax.get.blocks.by.building') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    building_id: buildingId
                },
                success: function(response) {
                    if (response.success) {
                        blockSelect.prop('disabled', false);
                        response.blocks.forEach(function(block) {
                            // Handle both 'name' and 'block_name' fields
                            const blockName = block.name || block.block_name ||
                                'Unknown Block';
                            blockSelect.append(
                                `<option value="${block.id}">${blockName}</option>`
                            );
                        });
                    } else {
                        alert('Failed to load blocks: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Error loading blocks: ' + (xhr.responseJSON?.message || xhr
                        .statusText));
                }
            });
        }
    });

    // Block change event
    $(document).on('change', '.block-select', function() {
        const blockId = $(this).val();
        const subjectKey = $(this).data('subject-key');
        const floorSelect = $(`.floor-select[data-subject-key="${subjectKey}"]`);
        const roomSelect = $(`.room-select[data-subject-key="${subjectKey}"]`);

        // Reset dependent dropdowns
        floorSelect.html('<option value="">Select Block First</option>').prop('disabled', true);
        roomSelect.html('<option value="">Select Floor First</option>').prop('disabled', true);

        if (blockId) {
            $.ajax({
                url: "{{ route('ajax.get.floors.by.block') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    block_id: blockId
                },
                success: function(response) {
                    if (response.success) {
                        floorSelect.prop('disabled', false);
                        response.floors.forEach(function(floor) {
                            floorSelect.append(
                                `<option value="${floor.id}">${floor.floor_number}</option>`
                            );
                        });
                    } else {
                        alert('Failed to load floors: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Error loading floors: ' + (xhr.responseJSON?.message || xhr
                        .statusText));
                }
            });
        }
    });

    // Floor change event
    $(document).on('change', '.floor-select', function() {
        const floorId = $(this).val();
        const subjectKey = $(this).data('subject-key');
        const blockSelect = $(`.block-select[data-subject-key="${subjectKey}"]`);
        const roomSelect = $(`.room-select[data-subject-key="${subjectKey}"]`);
        const blockId = blockSelect.val();

        // Reset room dropdown
        roomSelect.html('<option value="">Select Floor First</option>').prop('disabled', true);

        if (floorId) {
            $.ajax({
                url: "{{ route('ajax.get.rooms.by.floor') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    block_id: blockId,
                    floor_id: floorId
                },
                success: function(response) {
                    if (response.success) {
                        roomSelect.prop('disabled', false);
                        response.rooms.forEach(function(room) {
                            roomSelect.append(
                                `<option value="${room.id}">${room.room_number}</option>`
                            );
                        });
                    } else {
                        alert('Failed to load rooms: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Error loading rooms: ' + (xhr.responseJSON?.message || xhr
                        .statusText));
                }
            });
        }
    });

    // Handle lecture mode toggle to show/hide online meeting fields
    $(document).on('change', 'input[name$="[lecture_mode]"]', function() {
        const subjectKey = $(this).data('subject-key');
        const isOnline = $(this).val() === 'online';
        const onlineFields = $('#online_fields_' + subjectKey);

        if (isOnline) {
            onlineFields.slideDown();
            // Make meeting link required
            onlineFields.find('.meeting-link').prop('required', false);
        } else {
            onlineFields.slideUp();
            // Remove required attribute and clear values
            onlineFields.find('.meeting-link').prop('required', false).val('');
            onlineFields.find('input[type="text"]').val('');
        }
    });
});
</script>
@endsection