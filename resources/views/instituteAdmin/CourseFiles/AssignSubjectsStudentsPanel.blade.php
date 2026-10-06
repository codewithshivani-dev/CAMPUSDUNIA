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

    /* Form Labels */
    .form-label {
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .required:after {
        content: " *";
        color: #ef4444;
        font-weight: 700;
    }

    /* Form Controls */
    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 15px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
        height: auto;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
    }

    .form-control:hover, .form-select:hover {
        border-color: var(--secondary-color);
    }

    .form-control:disabled, .form-select:disabled {
        background-color: #f1f5f9;
        cursor: not-allowed;
        border-color: #e2e8f0;
    }

    /* Student Selection Box */
    .student-selection-box {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 15px;
        max-height: 350px;
        overflow-y: auto;
    }

    .student-selection-box::-webkit-scrollbar {
        width: 8px;
    }

    .student-selection-box::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    .student-selection-box::-webkit-scrollbar-thumb {
        background: var(--primary-gradient);
        border-radius: 4px;
    }

    .student-option {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        padding: 12px;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        transition: all 0.3s;
    }

    .student-option:hover {
        border-color: var(--primary-color);
        box-shadow: 0 3px 10px rgba(67, 97, 238, 0.1);
        transform: translateX(3px);
    }

    .student-option input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        border: 2px solid #cbd5e1;
        border-radius: 4px;
        transition: all 0.2s;
        margin-right: 12px;
        accent-color: var(--primary-color);
    }

    .student-option input[type="checkbox"]:hover {
        border-color: var(--primary-color);
        transform: scale(1.1);
    }

    .student-info {
        flex: 1;
    }

    .student-name {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .student-name i {
        font-size: 14px;
        background: rgba(67, 97, 238, 0.1);
        padding: 3px;
        border-radius: 4px;
    }

    .student-id, .student-registration {
        font-size: 11px;
        color: #64748b;
        margin-top: 2px;
    }

    /* Subject Selection Box */
    .subject-selection-box {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 15px;
        max-height: 500px;
        overflow-y: auto;
    }

    .subject-selection-box::-webkit-scrollbar {
        width: 8px;
    }

    .subject-selection-box::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    .subject-selection-box::-webkit-scrollbar-thumb {
        background: var(--primary-gradient);
        border-radius: 4px;
    }

    .subject-card {
        border: 2px solid #e2e8f0 !important;
        border-radius: 12px !important;
        margin-bottom: 15px;
        overflow: hidden;
        transition: all 0.3s;
    }

    .subject-card:hover {
        border-color: var(--primary-color) !important;
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.1);
    }

    .subject-card .card-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 12px 15px;
        border-bottom: 2px solid #e2e8f0;
    }

    .subject-card .card-header .form-check-input {
        width: 18px;
        height: 18px;
        cursor: pointer;
        border: 2px solid #cbd5e1;
        border-radius: 4px;
        transition: all 0.2s;
        margin-right: 10px;
        accent-color: var(--primary-color);
    }

    .subject-card .card-header .form-check-input:hover {
        border-color: var(--primary-color);
        transform: scale(1.1);
    }

    .subject-card .card-header .form-check-label {
        font-weight: 600;
        color: var(--primary-color);
        cursor: pointer;
    }

    .subject-card .card-body {
        padding: 15px;
        background: white;
    }

    /* Sub-subject checkboxes */
    .sub-subject-checkbox {
        width: 16px;
        height: 16px;
        cursor: pointer;
        border: 2px solid #cbd5e1;
        border-radius: 4px;
        transition: all 0.2s;
        margin-right: 8px;
        accent-color: var(--primary-color);
    }

    .sub-subject-checkbox:hover {
        border-color: var(--primary-color);
        transform: scale(1.1);
    }

    .sub-subject-checkbox + label {
        font-size: 13px;
        color: #475569;
        cursor: pointer;
    }

    .sub-subject-checkbox + label small {
        color: #94a3b8;
    }

    /* Select All Groups */
    .select-group {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 15px;
        margin-bottom: 15px;
    }

    .select-group .form-check-inline {
        margin-right: 20px;
    }

    .select-group .form-check-input {
        width: 18px;
        height: 18px;
        cursor: pointer;
        border: 2px solid #cbd5e1;
        border-radius: 4px;
        transition: all 0.2s;
        margin-right: 6px;
        accent-color: var(--primary-color);
    }

    .select-group .form-check-input:hover {
        border-color: var(--primary-color);
        transform: scale(1.1);
    }

    .select-group .form-check-label {
        font-weight: 500;
        color: #475569;
        cursor: pointer;
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

    /* Toast Container */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .toast {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        padding: 16px 20px;
        margin-bottom: 10px;
        min-width: 320px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        animation: slideInToast 0.3s ease;
        border-left: 4px solid;
    }

    @keyframes slideInToast {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .toast-success {
        border-left-color: var(--success-color);
    }

    .toast-success strong {
        color: var(--success-color);
    }

    .toast-error {
        border-left-color: #ef4444;
    }

    .toast-error strong {
        color: #ef4444;
    }

    .toast-warning {
        border-left-color: #f59e0b;
    }

    .toast-warning strong {
        color: #f59e0b;
    }

    .toast button {
        border: none;
        background: transparent;
        font-size: 20px;
        cursor: pointer;
        color: #94a3b8;
        transition: all 0.2s;
    }

    .toast button:hover {
        color: #475569;
        transform: scale(1.1);
    }

    /* Loading Spinner */
    .spinner-border {
        width: 1rem;
        height: 1rem;
        border-width: 0.2em;
        border-color: white;
        border-right-color: transparent;
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

        .btn-primary, .btn-secondary, .btn-outline-secondary {
            width: 100%;
            justify-content: center;
        }

        .select-group .form-check-inline {
            display: block;
            margin-bottom: 10px;
        }

        .student-option {
            flex-direction: column;
            align-items: flex-start;
        }

        .student-option input[type="checkbox"] {
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
            Assign Subjects to Students
        </h3>
        <a href="{{ route('student-subject-assignments.index') }}" class="btn btn-secondary">
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
            <form id="assignmentForm">
                @csrf

                <!-- Category Selection -->
                <div class="form-section">
                    <h5><i class="bi bi-diagram-3-fill"></i> Course Information</h5>
                    
                    <div class="form-group mb-3">
                        <label class="form-label required">Category</label>
                        <select name="department_category_id" id="department_category_id" class="form-control" required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->department_category_id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="categoryError"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label required">Department</label>
                        <select name="department_id" id="department_id" class="form-control" required disabled>
                            <option value="">-- Select Department --</option>
                        </select>
                        <div class="invalid-feedback" id="departmentError"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label required">{{$courseLabel}} Type</label>
                        <select name="course_type" id="course_type" class="form-control" required disabled>
                            <option value="">-- Select {{$courseLabel}} Type --</option>
                        </select>
                        <div class="invalid-feedback" id="courseTypeError"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label required">{{$courseLabel}} Sub Type</label>
                        <select name="course_subtype_id" id="course_subtype_id" class="form-control" required disabled>
                            <option value="">-- Select {{$courseLabel}} Sub Type --</option>
                        </select>
                        <div class="invalid-feedback" id="subtypeError"></div>
                    </div>

                    <!-- Hidden field for course_detail_id -->
                    <input type="hidden" name="course_detail_id" id="course_detail_id">
                </div>

                <!-- Student Selection -->
                <div class="form-section">
                    <h5><i class="bi bi-people-fill"></i> Student Selection</h5>
                    
                    <div class="form-group mb-3">
                        <label class="form-label required">Select Students</label>
                        <div id="studentsContainer" class="student-selection-box">
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <small>Please select department and course first to load students</small>
                            </div>
                        </div>
                        <div class="form-check mt-3">
                            <input type="checkbox" id="selectAllStudents" class="form-check-input">
                            <label for="selectAllStudents" class="form-check-label fw-medium">
                                <i class="bi bi-check-all me-1"></i> Select All Students
                            </label>
                        </div>
                        <div class="invalid-feedback" id="studentsError"></div>
                    </div>
                </div>

                <!-- Subject Selection -->
                <div class="form-section">
                    <h5><i class="bi bi-book-fill"></i> Subject Selection</h5>
                    
                    <div class="form-group mb-3">
                        <label class="form-label required">Select Subjects</label>
                        <div id="subjectsContainer" class="subject-selection-box">
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <small>Please select course sub-type first to load subjects</small>
                            </div>
                        </div>
                        <div class="form-check mt-3">
                            <input type="checkbox" id="selectAllSubjects" class="form-check-input">
                            <label for="selectAllSubjects" class="form-check-label fw-medium">
                                <i class="bi bi-check-all me-1"></i> Select All Subjects
                            </label>
                        </div>
                        <div class="invalid-feedback" id="subjectsError"></div>
                    </div>
                </div>

                <!-- Assignment Details -->
                <div class="form-section">
                    <h5><i class="bi bi-calendar-check-fill"></i> Assignment Details</h5>
                    
                    <div class="form-group mb-3">
                        <label class="form-label required">Assigned Date</label>
                        <input type="date" name="assigned_date" id="assigned_date" class="form-control"
                            value="{{ date('Y-m-d') }}" required>
                        <div class="invalid-feedback" id="dateError"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" id="remarks" class="form-control" 
                            placeholder="Enter any remarks (optional)" rows="2" maxlength="255"></textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn btn-primary flex-grow-1" id="submitBtn">
                        <i class="bi bi-save"></i> Assign Subjects
                    </button>
                    <button type="reset" class="btn btn-secondary" onclick="resetAssignmentForm()">
                        <i class="bi bi-arrow-repeat"></i> Reset
                    </button>
                    <a href="{{ route('student-subject-assignments.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Toast Container --}}
<div class="toast-container" id="toastContainer"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS for alerts/dismissals -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ALL JAVASCRIPT REMAINS EXACTLY THE SAME - NOT CHANGED
$(document).ready(function() {
    const instituteType = "{{ $serviceInstitutedetails->type ?? '' }}";
    const courseLabel = instituteType === 'School' ? 'Class' : 'Course';    
    
    // Toast notification function
    window.showToast = function(type, message) {
        const toast = $(`
            <div class="toast toast-${type}">
                <div>
                    <strong>${type === 'success' ? '✓' : type === 'error' ? '✗' : type === 'warning' ? '⚠' : 'ℹ'}</strong>
                    <span style="margin-left: 10px;">${message}</span>
                </div>
                <button onclick="$(this).parent().remove()" style="border:none; background:none; cursor:pointer;">×</button>
            </div>
        `);
        
        $('#toastContainer').append(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    function resetDownstreamSelects() {
        $('#department_id').prop('disabled', true).html('<option value="">-- Select Department --</option>');
        $('#course_type').prop('disabled', true).html('<option value="">-- Select Course Type --</option>');
        $('#course_subtype_id').prop('disabled', true).html('<option value="">-- Select Sub Type --</option>');
        $('#course_detail_id').val('');
    }

    window.resetAssignmentForm = function() {
        $('#assignmentForm')[0].reset();
        $('#studentsContainer').html(
            '<div class="text-center py-4 text-muted"><i class="bi bi-info-circle-fill me-2"></i><small>Please select department and course first to load students</small></div>'
        );
        $('#subjectsContainer').html(
            '<div class="text-center py-4 text-muted"><i class="bi bi-info-circle-fill me-2"></i><small>Please select course sub-type first to load subjects</small></div>'
        );
        $('#department_category_id').val('');
        resetDownstreamSelects();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('#assigned_date').val('{{ date("Y-m-d") }}');
        $('#selectAllStudents').prop('checked', false);
        $('#selectAllSubjects').prop('checked', false);
    };

    window.viewStudentSubjects = function(studentId) {
        $.ajax({
            url: '/student-subject-assignments/' + studentId,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let data = response.data;

                    let html = `
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Student Information</h5>
                                <p><strong>Name:</strong> ${data.student.name || data.student.full_name}</p>
                                <p><strong>ID:</strong> <span>${data.student.student_hash_id || data.student.id}</span></p>
                                <p><strong> ${courseLabel} :</strong> ${data.course.course_type} - ${data.course.sub_type}</p>
                                <hr>
                                <h5 class="card-title">Assigned Subjects</h5>
                    `;

                    if (data.main_subjects && data.main_subjects.length > 0) {
                        html += '<div class="mb-3"><h6>Main Subjects</h6><div class="list-group">';
                        data.main_subjects.forEach((subject, index) => {
                            html += `
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">${subject.subject_name}</h6>
                                        <small class="text-muted">${subject.subject_id}</small>
                                    </div>
                                    <p class="mb-1">Semester: ${subject.semester_id === 'all_semesters' ? 'All Semesters' : subject.semester_id}</p>
                                    <small>Assigned on: ${new Date(subject.assigned_date).toLocaleDateString('en-GB')}</small>
                                </div>
                            `;
                        });
                        html += '</div></div>';
                    }

                    if (data.sub_subjects && data.sub_subjects.length > 0) {
                        html += '<div class="mb-3"><h6>Sub Subjects</h6><div class="list-group">';
                        data.sub_subjects.forEach((subSubject, index) => {
                            html += `
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">${subSubject.subject_name} > ${subSubject.sub_subject_name}</h6>
                                        <small class="text-muted">${subSubject.sub_subject_id}</small>
                                    </div>
                                    <p class="mb-1">Parent Subject: ${subSubject.subject_id}</p>
                                    <small>Assigned on: ${new Date(subSubject.assigned_date).toLocaleDateString('en-GB')}</small>
                                </div>
                            `;
                        });
                        html += '</div></div>';
                    }

                    if ((!data.main_subjects || data.main_subjects.length === 0) &&
                        (!data.sub_subjects || data.sub_subjects.length === 0)) {
                        html += '<p class="text-muted">No subjects assigned.</p>';
                    }

                    html += `
                                <div class="mt-3">
                                    <small class="text-muted">Total Assignments: ${data.total_assignments || 0}</small>
                                </div>
                            </div>
                        </div>
                    `;

                    $('#studentSubjectsDetails').html(html);

                    // Open the panel
                    document.getElementById("viewStudentSubjectsPanel").style.right = "0";
                } else {
                    showToast('error', response.message || 'Failed to load student subject details');
                }
            },
            error: function() {
                showToast('error', 'Failed to load student subject details');
            }
        });
    };

    // Category change event
    $('#department_category_id').change(function() {
        let categoryId = $(this).val();

        resetDownstreamSelects();
        $('#studentsContainer').html(
            '<div class="text-center py-4 text-muted"><i class="bi bi-info-circle-fill me-2"></i><small>Please select department and course first to load students</small></div>'
        );
        $('#subjectsContainer').html(
            '<div class="text-center py-4 text-muted"><i class="bi bi-info-circle-fill me-2"></i><small>Please select course sub-type first to load subjects</small></div>'
        );

        if (categoryId) {
            $('#department_id').html('<option value="">Loading departments...</option>');

            $.get('/ajax/departments-by-category', {
                    category_id: categoryId
                })
                .done(function(response) {
                    if (response.success) {
                        $('#department_id').html(
                            '<option value="">-- Select Department --</option>');
                        if (response.departments && response.departments.length > 0) {
                            response.departments.forEach(dept => {
                                $('#department_id').append(
                                    `<option value="${dept.department_id}">${dept.department}</option>`
                                );
                            });
                            $('#department_id').prop('disabled', false);
                        } else {
                            $('#department_id').html(
                                '<option value="">No departments found</option>');
                            showToast('warning', 'No departments found for this category.');
                        }
                    } else {
                        $('#department_id').html(
                            '<option value="">Error loading departments</option>');
                        showToast('error', response.message || 'Error loading departments');
                    }
                })
                .fail(function() {
                    $('#department_id').html('<option value="">Error loading departments</option>');
                    showToast('error', 'Failed to load departments. Please try again.');
                });
        }
    });

    // Department change event
    $('#department_id').change(function() {
        let deptId = $(this).val();

        $('#course_type').prop('disabled', true).html(
            '<option value="">-- Select Course Type --</option>');
        $('#course_subtype_id').prop('disabled', true).html(
            '<option value="">-- Select Sub Type --</option>');
        $('#studentsContainer').html(
            '<div class="text-center py-4 text-muted"><i class="bi bi-info-circle-fill me-2"></i><small>Please select course first to load students</small></div>'
        );
        $('#subjectsContainer').html(
            '<div class="text-center py-4 text-muted"><i class="bi bi-info-circle-fill me-2"></i><small>Please select course sub-type first to load subjects</small></div>'
        );

        if (deptId) {
            // Load course types
            $('#course_type').html('<option value="">Loading courses...</option>');

            $.get('/ajax/course-types-by-department', {
                    department_id: deptId
                })
                .done(function(response) {
                    if (response.status === 'success') {
                        $('#course_type').html(
                            '<option value="">-- Select Course Type --</option>');
                        if (response.courses && response.courses.length > 0) {
                            response.courses.forEach(course => {
                                $('#course_type').append(
                                    `<option value="${course.finacp_merchant_sub_category_type}" data-finacp-id="${course.finacp_merchant_sub_category_id}">${course.finacp_merchant_sub_category_type}</option>`
                                );
                            });
                            $('#course_type').prop('disabled', false);
                        } else {
                            $('#course_type').html('<option value="">No courses found</option>');
                            showToast('warning', 'No courses found for this department.');
                        }
                    } else {
                        $('#course_type').html('<option value="">Error loading courses</option>');
                        showToast('error', response.message || 'Error loading courses');
                    }
                })
                .fail(function() {
                    $('#course_type').html('<option value="">Error loading courses</option>');
                    showToast('error', 'Failed to load courses. Please try again.');
                });
        }
    });

    // Course Type change event
    $('#course_type').change(function() {
        let deptId = $('#department_id').val();
        let courseType = $(this).val();

        $('#course_subtype_id').prop('disabled', true).html(
            '<option value="">-- Select Sub Type --</option>');
        $('#studentsContainer').html(
            '<div class="text-center py-4 text-muted"><i class="bi bi-info-circle-fill me-2"></i><small>Please select course sub-type first to load students</small></div>'
        );
        $('#subjectsContainer').html(
            '<div class="text-center py-4 text-muted"><i class="bi bi-info-circle-fill me-2"></i><small>Please select course sub-type first to load subjects</small></div>'
        );

        if (deptId && courseType) {
            // Load sub types/branches
            $('#course_subtype_id').html('<option value="">Loading sub types...</option>');

            $.get('/ajax/get-branches-by-course', {
                    department_id: deptId,
                    course_type: courseType
                })
                .done(function(response) {
                    if (response.status === 'success') {
                        $('#course_subtype_id').html(
                            '<option value="">-- Select Sub Type --</option>');
                        if (response.branches && response.branches.length > 0) {
                            response.branches.forEach(branch => {
                                $('#course_subtype_id').append(
                                    `<option value="${branch.product_id}" data-sub-type="${branch.sub_type}">${branch.sub_type}</option>`
                                );
                            });
                            $('#course_subtype_id').prop('disabled', false);
                        } else {
                            $('#course_subtype_id').html(
                                '<option value="">No sub types found</option>');
                            showToast('warning', 'No sub types found for this course.');
                        }
                    } else {
                        $('#course_subtype_id').html(
                            '<option value="">Error loading sub types</option>');
                        showToast('error', response.message || 'Error loading sub types');
                    }
                })
                .fail(function() {
                    $('#course_subtype_id').html(
                        '<option value="">Error loading sub types</option>');
                    showToast('error', 'Failed to load sub types. Please try again.');
                });
        }
    });

    // Sub Type change event
    $('#course_subtype_id').change(function() {
        let productId = $(this).val();
        let subTypeText = $(this).find('option:selected').data('sub-type');

        // Set hidden field for course_detail_id
        $('#course_detail_id').val(productId);

        if (productId) {
            // Load students
            loadStudents(productId);

            // Load subjects
            loadSubjects(productId);
        } else {
            $('#studentsContainer').html(
                '<div class="text-center py-4 text-muted"><i class="bi bi-info-circle-fill me-2"></i><small>Please select a valid course sub-type</small></div>'
            );
            $('#subjectsContainer').html(
                '<div class="text-center py-4 text-muted"><i class="bi bi-info-circle-fill me-2"></i><small>Please select a valid course sub-type</small></div>'
            );
        }
    });

    // Load students function
    function loadStudents(productId) {
        $('#studentsContainer').html(
            '<div class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary"></div> Loading students...</div>'
        );

        // Use the endpoint with product_id
        $.get('/ajax/students-by-product', {
                product_id: productId
            })
            .done(function(response) {
                if (response.success && response.students && response.students.length > 0) {
                    let html = '';
                    response.students.forEach(student => {
                        // Get the full name (handle null middle name)
                        let fullName = student.full_name ||
                            student.first_name + ' ' +
                            (student.middle_name ? student.middle_name + ' ' : '') +
                            student.last_name;

                        html += `
                        <div class="student-option">
                            <input type="checkbox" name="student_ids[]" value="${student.student_hash_id}" 
                                id="student_${student.student_hash_id}" class="student-checkbox">
                            <div class="student-info">
                                <div class="student-name"><i class="bi bi-person-badge-fill"></i> ${fullName}</div>
                                <div class="student-id">ID: ${student.student_hash_id}</div>
                                <div class="student-registration">Reg: ${student.registration_number || 'N/A'}</div>
                            </div>
                        </div>
                    `;
                    });
                    $('#studentsContainer').html(html);
                } else {
                    $('#studentsContainer').html(
                        '<div class="text-center py-4 text-muted"><i class="bi bi-exclamation-circle-fill me-2"></i><small>No students found for this course</small></div>'
                    );
                    if (response.message) {
                        showToast('warning', response.message);
                    }
                }
            })
            .fail(function(xhr) {
                let errorMessage = 'Failed to load students. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                $('#studentsContainer').html(
                    `<div class="text-center py-4 text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i><small>${errorMessage}</small></div>`);
                showToast('error', errorMessage);
            });
    }

    // Load subjects function
    function loadSubjects(productId) {
        $('#subjectsContainer').html(
            '<div class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary"></div> Loading subjects...</div>'
        );

        $.get('/ajax/subjects-by-course', {
                course_detail_id: productId
            })
            .done(function(response) {
                if (response.success && response.subjects && response.subjects.length > 0) {
                    let html = '';

                    // Group options
                    html += `
                <div class="select-group">
                    <div class="form-check form-check-inline">
                        <input type="checkbox" id="selectAllMain" class="form-check-input">
                        <label for="selectAllMain" class="form-check-label">Select All Main Subjects</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="checkbox" id="selectAllSub" class="form-check-input">
                        <label for="selectAllSub" class="form-check-label">Select All Sub-Subjects</label>
                    </div>
                </div>
            `;

                    response.subjects.forEach((subject, index) => {
                        html += `
                    <div class="card subject-card">
                        <div class="card-header">
                            <div class="form-check">
                                <input type="checkbox" 
                                    name="subject_ids[]" 
                                    value="${subject.subject_id}" 
                                    id="subject_${subject.id}" 
                                    class="form-check-input subject-checkbox parent-subject"
                                    data-subject-id="${subject.subject_id}"
                                    data-subject-name="${subject.subject_name}">
                                <label for="subject_${subject.id}" class="form-check-label fw-bold">
                                    <span class="subject-name">${subject.subject_name}</span>
                                    <small class="text-muted ms-2">(ID: ${subject.subject_id})</small>
                                </label>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="ps-4">
                                <small class="text-muted">Semester: ${subject.semester_id === 'all_semesters' ? 'All Semesters' : 'Semester ' + subject.semester_id}</small>
                `;

                        if (subject.sub_subjects && subject.sub_subjects.length > 0) {
                            html +=
                                '<div class="mt-3"><small class="text-muted mb-2 d-block">Sub-Subjects:</small>';

                            subject.sub_subjects.forEach((subSub, subIndex) => {
                                html += `
                            <div class="form-check mb-2">
                                <input type="checkbox" 
                                    name="sub_subject_ids[]" 
                                    value="${subSub.sub_subject_id}" 
                                    id="sub_subject_${subSub.id}" 
                                    class="form-check-input sub-subject-checkbox"
                                    data-parent-subject-id="${subject.subject_id}"
                                    data-sub-subject-id="${subSub.sub_subject_id}"
                                    data-sub-subject-name="${subSub.sub_subject_name}">
                                <label for="sub_subject_${subSub.id}" class="form-check-label">
                                    <span>${subSub.sub_subject_name}</span>
                                    <small class="text-muted ms-2">(${subSub.sub_subject_id})</small>
                                </label>
                            </div>
                        `;
                            });

                            html += '</div>';
                        }

                        html += `
                        </div>
                    </div>
                </div>`;
                    });

                    $('#subjectsContainer').html(html);

                    // Add checkbox event listeners
                    setupCheckboxLogic();

                } else {
                    $('#subjectsContainer').html(
                        '<div class="text-center py-4 text-muted"><i class="bi bi-exclamation-circle-fill me-2"></i><small>No subjects found for this course</small></div>'
                    );
                    showToast('warning', 'No subjects found for the selected course.');
                }
            })
            .fail(function() {
                $('#subjectsContainer').html(
                    '<div class="text-center py-4 text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i><small>Failed to load subjects</small></div>'
                );
                showToast('error', 'Failed to load subjects. Please try again.');
            });
    }

    // New function to handle checkbox logic
    function setupCheckboxLogic() {
        // When selecting a main subject, also select all its sub-subjects
        $('.parent-subject').change(function() {
            const subjectId = $(this).data('subject-id');
            const isChecked = $(this).prop('checked');

            // Find and check/uncheck all sub-subjects under this parent
            $(`.sub-subject-checkbox[data-parent-subject-id="${subjectId}"]`)
                .prop('checked', isChecked)
                .trigger('change');
        });

        // When selecting a sub-subject, ensure its parent subject is selected
        $('.sub-subject-checkbox').change(function() {
            const parentSubjectId = $(this).data('parent-subject-id');
            const subSubjectCheckboxes = $(
                `.sub-subject-checkbox[data-parent-subject-id="${parentSubjectId}"]`);
            const checkedCount = subSubjectCheckboxes.filter(':checked').length;
            const totalCount = subSubjectCheckboxes.length;

            // If any sub-subject is checked, check the parent
            if (checkedCount > 0) {
                $(`.parent-subject[data-subject-id="${parentSubjectId}"]`).prop('checked', true);
            }
            // If all sub-subjects are unchecked, uncheck the parent
            else if (checkedCount === 0) {
                $(`.parent-subject[data-subject-id="${parentSubjectId}"]`).prop('checked', false);
            }
        });

        // Select All Main Subjects
        $('#selectAllMain').change(function() {
            const isChecked = $(this).prop('checked');
            $('.parent-subject').prop('checked', isChecked).trigger('change');
        });

        // Select All Sub-Subjects
        $('#selectAllSub').change(function() {
            const isChecked = $(this).prop('checked');
            $('.sub-subject-checkbox').prop('checked', isChecked).trigger('change');
        });
    }

    // Select all students
    $('#selectAllStudents').change(function() {
        $('.student-checkbox').prop('checked', $(this).prop('checked'));
    });

       // Select all subjects (general)
    $('#selectAllSubjects').change(function() {
        $('.main-subject-checkbox').prop('checked', $(this).prop('checked'));
        $('.sub-subject-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Form submission
    $('#assignmentForm').submit(function(e) {
        e.preventDefault();

        if (!validateAssignmentForm()) {
            return;
        }

        // Get selected students
        const selectedStudents = $('.student-checkbox:checked');
        if (selectedStudents.length === 0) {
            showToast('error', 'Please select at least one student');
            return;
        }

        // Get selected subjects and sub-subjects
        const selectedMainSubjects = $('.parent-subject:checked');
        const selectedSubSubjects = $('.sub-subject-checkbox:checked');

        if (selectedMainSubjects.length === 0 && selectedSubSubjects.length === 0) {
            showToast('error', 'Please select at least one subject or sub-subject');
            return;
        }

        // Prepare data
        const data = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            department_id: $('#department_id').val(),
            course_detail_id: $('#course_detail_id').val(),
            assigned_date: $('#assigned_date').val(),
            remarks: $('#remarks').val(),
            student_ids: [],
            subject_ids: [],
            sub_subject_ids: []
        };

        // Collect student IDs
        selectedStudents.each(function() {
            data.student_ids.push($(this).val());
        });

        // Collect main subject IDs
        selectedMainSubjects.each(function() {
            data.subject_ids.push($(this).val());
        });

        // Collect sub-subject IDs
        selectedSubSubjects.each(function() {
            data.sub_subject_ids.push($(this).val());
        });

        // Show loading
        $('#submitBtn').prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm"></span> Assigning...');

        $.ajax({
            url: "{{ route('student-subject-assignments.store') }}",
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                console.log('Success response:', response);
                if (response.success) {
                    showToast('success', response.message);
                    
                    // Redirect back to assignments list after successful save
                    setTimeout(function() {
                        window.location.href = "{{ route('student-subject-assignments.index') }}";
                    }, 1500);
                } else {
                    showToast('error', response.message || 'Failed to assign subjects');
                    $('#submitBtn').prop('disabled', false).html(
                        '<i class="bi bi-save"></i> Assign Subjects');
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
                
                let errorMessage = 'An error occurred. Please check console for details.';
                
                if (xhr.responseJSON) {
                    console.log('Response JSON:', xhr.responseJSON);
                    errorMessage = xhr.responseJSON.message || errorMessage;
                    
                    // Display validation errors
                    if (xhr.responseJSON.errors) {
                        Object.keys(xhr.responseJSON.errors).forEach(function(key) {
                            let errorElement = $('#' + key + 'Error');
                            if (errorElement.length) {
                                errorElement.text(xhr.responseJSON.errors[key][0]);
                                $('#' + key).addClass('is-invalid');
                            } else {
                                // Create a general error display
                                showToast('error', xhr.responseJSON.errors[key][0]);
                            }
                        });
                    }
                }
                
                showToast('error', errorMessage);
                $('#submitBtn').prop('disabled', false).html(
                    '<i class="bi bi-save"></i> Assign Subjects');
            }
        });
    });

    function validateAssignmentForm() {
        let isValid = true;
        let requiredFields = ['department_category_id', 'department_id', 'course_type', 'course_subtype_id',
            'assigned_date'
        ];

        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        // Check required fields
        requiredFields.forEach(function(field) {
            let value = $('#' + field).val();
            if (!value) {
                $('#' + field).addClass('is-invalid');
                $('#' + field + 'Error').text('This field is required');
                isValid = false;
            }
        });

        // Check course_detail_id
        if (!$('#course_detail_id').val()) {
            showToast('error', 'Please select a valid course sub-type');
            isValid = false;
        }

        return isValid;
    }
});
</script>
@endsection