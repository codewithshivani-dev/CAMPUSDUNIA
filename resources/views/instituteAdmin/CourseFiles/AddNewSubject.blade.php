@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
        padding: 0 15px;
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

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .page-title i {
        font-size: 32px;
        filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
    }

    .page-header .btn {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(5px);
        transition: all 0.3s;
        text-decoration: none;
    }

    .page-header .btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
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

    .card-header-custom h5 {
        margin: 0;
        font-weight: 700;
        font-size: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header-custom h5 i {
        font-size: 24px;
        background: rgba(255, 255, 255, 0.2);
        padding: 8px;
        border-radius: 12px;
    }

    .card-body-custom {
        padding: 30px;
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
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 25px;
    }

    /* Form Labels */
    label {
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: block;
    }

    label i {
        color: var(--primary-color);
        margin-right: 5px;
    }

    .required:after {
        content: " *";
        color: #ef4444;
        font-size: 14px;
    }

    /* Form Controls */
    .form-control, select.form-control {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 15px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
        height: auto;
        width: 100%;
    }

    .form-control:focus, select.form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
    }

    .form-control:hover, select.form-control:hover {
        border-color: var(--secondary-color);
    }

    .form-control:disabled, select.form-control:disabled {
        background-color: #f1f5f9;
        cursor: not-allowed;
        border-color: #e2e8f0;
    }

    /* Textarea */
    textarea.form-control {
        min-height: 80px;
        resize: vertical;
    }

    /* Semester Options */
    .semester-options {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 25px;
        transition: all 0.3s;
    }

    .semester-options:hover {
        border-color: var(--primary-color);
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.1);
    }

    .semester-options h6 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 16px;
    }

    .semester-options h6 i {
        font-size: 18px;
    }

    .semester-option {
        margin-bottom: 12px;
        padding: 10px 15px;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        transition: all 0.3s;
    }

    .semester-option:hover {
        border-color: var(--primary-color);
        background: linear-gradient(135deg, #f8fafc, #ffffff);
    }

    .semester-option input[type="radio"] {
        width: 18px;
        height: 18px;
        margin-right: 12px;
        cursor: pointer;
        border: 2px solid #cbd5e1;
        border-radius: 50%;
        transition: all 0.2s;
    }

    .semester-option input[type="radio"]:hover {
        border-color: var(--primary-color);
        transform: scale(1.1);
    }

    .semester-option input[type="radio"]:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .semester-option label {
        margin: 0;
        font-weight: 600;
        color: #1e293b;
        cursor: pointer;
        font-size: 14px;
        text-transform: none;
        display: inline-block;
    }

    .semester-option-desc {
        font-size: 12px;
        color: #64748b;
        margin-left: 30px;
        margin-top: 4px;
    }

    /* Semester Selection Container */
    #specific_semesters_container {
        margin-top: 15px;
        padding: 20px;
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        border: 2px solid var(--primary-color);
        border-radius: 12px;
        animation: fadeIn 0.3s ease;
    }

    #specific_semesters_container label {
        margin-bottom: 8px;
    }

    #semester_select {
        min-height: 45px;
        font-size: 14px;
        margin-bottom: 5px;
    }

    /* Subject Boxes */
    .subject-box {
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 20px;
        position: relative;
        transition: all 0.3s;
    }

    .subject-box:hover {
        border-color: var(--primary-color);
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.1);
    }

    .subject-box-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px dashed #e2e8f0;
    }

    .subject-box-title {
        font-weight: 700;
        color: var(--primary-color);
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .subject-box-title i {
        font-size: 18px;
    }

    .remove-subject-box {
        color: #ef4444;
        background: rgba(239, 68, 68, 0.1);
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        font-size: 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .remove-subject-box:hover {
        background: var(--danger-gradient);
        color: white;
        transform: scale(1.1);
    }

    /* Subject Name Input */
    .subject-name {
        margin-bottom: 5px;
    }

    /* ID Preview */
    .id-preview {
        font-size: 11px;
        color: #64748b;
        margin-top: 5px;
        padding-left: 5px;
        font-family: monospace;
        background: #f1f5f9;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    /* Sub Subject Section */
    .sub-subject-input-group {
        margin-top: 15px;
        padding-left: 20px;
        border-left: 3px solid var(--success-color);
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border-radius: 0 12px 12px 0;
        padding: 15px;
    }

    .sub-subject-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        gap: 8px;
        flex-wrap: wrap;
    }

    .sub-subject-item input {
        flex: 1;
        min-width: 200px;
    }

    .remove-sub-subject-btn {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .remove-sub-subject-btn:hover {
        background: var(--danger-gradient);
        color: white;
        transform: scale(1.1);
    }

    .add-sub-subject-btn {
        background: var(--success-gradient);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        margin-top: 10px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
    }

    .add-sub-subject-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
    }

    .add-sub-subject-btn i {
        font-size: 14px;
    }

    /* Add Subject Box Button */
    .add-subject-box-btn {
        background: var(--primary-gradient);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 12px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        margin-top: 10px;
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
    }

    .add-subject-box-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.4);
    }

    .add-subject-box-btn i {
        font-size: 16px;
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

    /* Error styling */
    .error {
        color: #ef4444;
        font-size: 12px;
        margin-top: 4px;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 30px;
        flex-wrap: wrap;
    }

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
        flex: 1;
        justify-content: center;
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

    /* Toast Container */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .toast {
        background: white;
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        padding: 16px 20px;
        margin-bottom: 10px;
        min-width: 300px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        animation: slideInRight 0.3s ease;
        border-left: 4px solid transparent;
    }

    .toast-success {
        border-left-color: #10b981;
    }

    .toast-success strong {
        color: #10b981;
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
        background: none;
        font-size: 20px;
        cursor: pointer;
        color: #94a3b8;
        transition: all 0.3s;
    }

    .toast button:hover {
        color: #ef4444;
        transform: scale(1.1);
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Spinner */
    .spinner-border {
        width: 1rem;
        height: 1rem;
        border-width: 0.2em;
        border-color: white transparent white transparent;
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
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
        }

        .card-body-custom {
            padding: 20px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-primary, .btn-secondary, .btn-outline-secondary {
            width: 100%;
        }

        .sub-subject-item {
            flex-direction: column;
            align-items: stretch;
        }

        .remove-sub-subject-btn {
            width: 100%;
        }

        .toast {
            min-width: calc(100vw - 40px);
        }
    }
</style>

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-journal-bookmark-fill"></i>
            Add New Subjects
        </h1>
        <a href="{{ route('subject-coursewise.index') }}" class="btn">
            <i class="bi bi-arrow-left"></i> Back to Subjects List
        </a>
    </div>

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

    <div class="main-card">
        <div class="card-header-custom">
            <h5 class="mb-0">
                <i class="bi bi-pencil-square"></i>
                Subject Information
            </h5>
        </div>
        <div class="card-body-custom">
            <form id="subjectForm">
                @csrf

                <!-- Category Selection -->
                <div class="form-group">
                    <label class="required"><i class="bi bi-building-fill"></i> Category</label>
                    <select name="department_category_id" id="department_category_id" class="form-control" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->department_category_id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="categoryError"></div>
                </div>

                <!-- Department Selection -->
                <div class="form-group">
                    <label class="required"><i class="bi bi-diagram-3-fill"></i> Department</label>
                    <select name="department_id" id="department_id" class="form-control" required disabled>
                        <option value="">-- Select Department --</option>
                    </select>
                    <div class="invalid-feedback" id="departmentError"></div>
                </div>

                <!-- Course Type Selection -->
                <div class="form-group">
                    <label class="required"><i class="bi bi-tag-fill"></i> {{$courseLabel ?? 'Course'}} Type</label>
                    <select name="course_type" id="course_type" class="form-control" required disabled>
                        <option value="">-- Select {{$courseLabel ?? 'Course'}} Type --</option>
                    </select>
                    <div class="invalid-feedback" id="courseTypeError"></div>
                </div>

                <!-- Sub Type Selection -->
                <div class="form-group">
                    <label class="required"><i class="bi bi-layers-fill"></i> {{$courseLabel ?? 'Course'}} Sub Type</label>
                    <select name="course_subtype_id" id="course_subtype_id" class="form-control" required disabled>
                        <option value="">-- Select Sub Type --</option>
                    </select>
                    <div class="invalid-feedback" id="subtypeError"></div>
                </div>

                <!-- Hidden field for course_detail_id -->
                <input type="hidden" name="course_detail_id" id="course_detail_id">

                <!-- Semester/Term Assignment Options -->
                <div class="semester-options">
                    <h6><i class="bi bi-calendar-range-fill"></i> Semester/Term Assignment</h6>
                    
                    <!-- Option 1: All Semesters/Terms -->
                    <div class="semester-option">
                        <input type="radio" id="semester_option_all" name="semester_option" value="all" checked>
                        <label for="semester_option_all">
                            All Semesters/Terms
                        </label>
                        <div class="semester-option-desc">Subject will be assigned to all semesters/terms of this course</div>
                    </div>
                    
                    <!-- Option 2: Specific Semester/Term -->
                    <div class="semester-option">
                        <input type="radio" id="semester_option_specific" name="semester_option" value="specific">
                        <label for="semester_option_specific">
                            Specific Semester/Term
                        </label>
                        <div class="semester-option-desc">Select one specific semester/term for this subject</div>
                    </div>
                    
                    <!-- Semester Selection Container -->
                    <div id="specific_semesters_container" style="display: none;">
                        <label class="required"><i class="bi bi-calendar-check-fill"></i> Select Semester/Term</label>
                        <select name="semester_id" id="semester_select" class="form-control">
                            <option value="">-- Select Semester/Term --</option>
                            <!-- Options will be loaded dynamically -->
                        </select>
                        <small class="text-muted"><i class="bi bi-info-circle-fill me-1"></i> Only one semester/term can be selected</small>
                        <div class="invalid-feedback" id="semesterError"></div>
                    </div>
                </div>

                <!-- Assigned Date -->
                <div class="form-group">
                    <label class="required"><i class="bi bi-calendar-plus-fill"></i> Assigned Date</label>
                    <input type="date" name="assigned_date" id="assigned_date" class="form-control" 
                           value="{{ date('Y-m-d') }}" required>
                    <div class="invalid-feedback" id="dateError"></div>
                </div>

                <!-- Remarks -->
                <div class="form-group">
                    <label><i class="bi bi-chat-text-fill"></i> Remarks</label>
                    <textarea name="remarks" id="remarks" class="form-control" 
                              placeholder="Enter any remarks (optional)" rows="2" maxlength="255"></textarea>
                </div>

                <!-- Subjects Section -->
                <div class="form-group">
                    <label class="required"><i class="bi bi-journal-text"></i> Subjects</label>
                    <div id="subjectBoxesContainer">
                        <!-- Subject boxes will be added by JavaScript -->
                    </div>
                    <button type="button" class="add-subject-box-btn" id="addSubjectBoxBtn">
                        <i class="bi bi-plus-lg"></i> Add Another Subject
                    </button>
                    <small class="text-muted"><i class="bi bi-info-circle-fill me-1"></i> Add multiple subjects with optional sub-subjects</small>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button type="submit" class="btn-primary" id="submitBtn">
                        <i class="bi bi-check2-circle"></i> Save All Subjects
                    </button>
                    <button type="reset" class="btn-secondary" onclick="resetSubjectForm()">
                        <i class="bi bi-arrow-repeat"></i> Reset
                    </button>
                    <a href="{{ route('subject-coursewise.index') }}" class="btn-outline-secondary">
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
<script>
// All JavaScript remains EXACTLY the same
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
                <button onclick="$(this).parent().remove()">×</button>
            </div>
        `);
        
        $('#toastContainer').append(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    // Subject counter for unique IDs
    let subjectBoxCounter = 0;
    let subSubjectCounter = {};

    // Function to generate subject ID preview
    function generateSubjectIdPreview(subjectName) {
        if (!subjectName || subjectName.length < 3) return '';
        
        let prefix = subjectName.replace(/\s+/g, '').substring(0, 5).toUpperCase();
        let randomDigits = Math.floor(Math.random() * 100000).toString().padStart(5, '0');
        
        return prefix + randomDigits;
    }

    // Function to generate sub-subject ID preview
    function generateSubSubjectIdPreview(subjectId, index) {
        if (!subjectId) return '';
        return subjectId + '-' + (index + 1).toString().padStart(3, '0');
    }

    // Function to create a new subject box
    function createSubjectBox() {
        subjectBoxCounter++;
        const boxId = `subjectBox_${subjectBoxCounter}`;
        subSubjectCounter[boxId] = 0;
        
        return `
            <div class="subject-box" id="${boxId}">
                <div class="subject-box-header">
                    <span class="subject-box-title">
                        <i class="bi bi-journal-bookmark-fill"></i> Subject ${subjectBoxCounter}
                    </span>
                    <button type="button" class="remove-subject-box" onclick="removeSubjectBox('${boxId}')">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="form-group mb-2">
                    <label class="required"><i class="bi bi-pencil-fill"></i> Subject Name</label>
                    <input type="text" name="subjects[${subjectBoxCounter}][name]" 
                           class="form-control subject-name" 
                           placeholder="Enter subject name" required
                           oninput="updateSubjectIdPreview('${boxId}', this.value)">
                    <div class="id-preview" id="subjectIdPreview_${boxId}">
                        <i class="bi bi-upc-scan me-1"></i> Subject ID: <span id="subjectIdValue_${boxId}">-</span>
                    </div>
                </div>
                <div class="form-group mb-2">
                    <label><i class="bi bi-diagram-2-fill"></i> Sub Subjects (Optional)</label>
                    <div class="sub-subject-input-group" id="subSubjects_${boxId}">
                        <!-- Sub-subjects will be added here -->
                    </div>
                    <button type="button" class="add-sub-subject-btn" 
                            onclick="addSubSubject('${boxId}')">
                        <i class="bi bi-plus-lg"></i> Add Sub Subject
                    </button>
                    <small class="text-muted"><i class="bi bi-info-circle-fill me-1"></i> Add sub-topics for this subject</small>
                </div>
            </div>
        `;
    }

    // Function to update subject ID preview
    window.updateSubjectIdPreview = function(boxId, subjectName) {
        const previewElement = $(`#subjectIdValue_${boxId}`);
        const subjectId = generateSubjectIdPreview(subjectName);
        previewElement.text(subjectId || '-');
        
        // Also update any existing sub-subject ID previews
        updateSubSubjectIdPreviews(boxId, subjectId);
    }

    // Function to update sub-subject ID previews
    function updateSubSubjectIdPreviews(boxId, subjectId) {
        const subSubjectInputs = $(`#${boxId} .sub-subject-item input`);
        subSubjectInputs.each(function(index) {
            const subSubId = generateSubSubjectIdPreview(subjectId, index);
            $(this).attr('data-preview-id', subSubId);
            
            // Update preview if it exists
            const previewSpan = $(this).nextAll('.sub-subject-preview');
            if (previewSpan.length) {
                previewSpan.html(`<i class="bi bi-upc-scan me-1"></i> ID: ${subSubId}`);
            }
        });
    }

    // Function to add sub subject
    window.addSubSubject = function(boxId) {
        const container = $(`#subSubjects_${boxId}`);
        subSubjectCounter[boxId]++;
        const subSubIndex = subSubjectCounter[boxId];
        const subjectId = $(`#subjectIdValue_${boxId}`).text();
        
        // Generate sub-subject ID preview
        const subSubIdPreview = generateSubSubjectIdPreview(subjectId === '-' ? '' : subjectId, subSubIndex - 1);
        
        const html = `
            <div class="sub-subject-item" id="sub_subject_${boxId}_${subSubIndex}">
                <input type="text" name="subjects[${boxId.split('_')[1]}][sub_subjects][]" 
                       class="form-control" 
                       placeholder="Enter sub subject name"
                       data-preview-id="${subSubIdPreview}">
                <button type="button" class="remove-sub-subject-btn" onclick="removeSubSubject('sub_subject_${boxId}_${subSubIndex}')">
                    <i class="bi bi-trash-fill"></i>
                </button>
                <div class="id-preview sub-subject-preview" style="font-size: 10px; color: #6c757d; width: 100%; margin-top: 5px;">
                    <i class="bi bi-upc-scan me-1"></i> ID: ${subSubIdPreview || 'Will be generated'}
                </div>
            </div>
        `;
        
        container.append(html);
    }

    // Function to remove sub subject
    window.removeSubSubject = function(subSubId) {
        $(`#${subSubId}`).remove();
        // Update counters and re-index
        const boxId = subSubId.split('_').slice(0, 3).join('_');
        reindexSubSubjects(boxId);
    }

    // Function to reindex sub subjects after removal
    function reindexSubSubjects(boxId) {
        const container = $(`#${boxId} .sub-subject-input-group`);
        const subSubjectItems = container.find('.sub-subject-item');
        
        subSubjectCounter[boxId] = subSubjectItems.length;
        
        // Update IDs and previews
        const subjectId = $(`#subjectIdValue_${boxId}`).text();
        subSubjectItems.each(function(index) {
            const newIndex = index + 1;
            const newId = `sub_subject_${boxId}_${newIndex}`;
            $(this).attr('id', newId);
            
            // Update remove button onclick
            $(this).find('.remove-sub-subject-btn').attr('onclick', `removeSubSubject('${newId}')`);
            
            // Update ID preview
            const subSubIdPreview = generateSubSubjectIdPreview(subjectId === '-' ? '' : subjectId, index);
            $(this).find('input').attr('data-preview-id', subSubIdPreview);
            $(this).find('.sub-subject-preview').html(`<i class="bi bi-upc-scan me-1"></i> ID: ${subSubIdPreview}`);
        });
    }

    // Function to remove subject box
    window.removeSubjectBox = function(boxId) {
        if ($('.subject-box').length > 1) {
            $(`#${boxId}`).remove();
            // Renumber remaining boxes
            renumberSubjectBoxes();
        } else {
            showToast('warning', 'At least one subject box is required');
        }
    }

    // Function to renumber subject boxes
    function renumberSubjectBoxes() {
        const boxes = $('.subject-box');
        
        // Reset counter to 0 before renumbering
        subjectBoxCounter = 0;
        
        boxes.each(function(index) {
            subjectBoxCounter++;
            const box = $(this);
            const newIndex = subjectBoxCounter;
            const newBoxId = `subjectBox_${newIndex}`;
            
            // Update the box ID
            box.attr('id', newBoxId);
            
            // Update title
            box.find('.subject-box-title').html(`<i class="bi bi-journal-bookmark-fill"></i> Subject ${newIndex}`);
            
            // Update subject name input
            box.find('.subject-name').attr('name', `subjects[${newIndex}][name]`);
            box.find('.subject-name').attr('oninput', `updateSubjectIdPreview('${newBoxId}', this.value)`);
            
            // Update preview elements
            box.find('[id^="subjectIdPreview_"]').attr('id', `subjectIdPreview_${newBoxId}`);
            box.find('[id^="subjectIdValue_"]').attr('id', `subjectIdValue_${newBoxId}`);
            
            // Update sub-subjects container ID
            box.find('.sub-subject-input-group').attr('id', `subSubjects_${newBoxId}`);
            
            // Update add button onclick
            box.find('.add-sub-subject-btn').attr('onclick', `addSubSubject('${newBoxId}')`);
            
            // Update remove button onclick
            box.find('.remove-subject-box').attr('onclick', `removeSubjectBox('${newBoxId}')`);
            
            // Update sub-subject inputs
            box.find('.sub-subject-input-group input').each(function() {
                $(this).attr('name', `subjects[${newIndex}][sub_subjects][]`);
            });
            
            // Update sub-subject IDs
            reindexSubSubjects(newBoxId);
        });
    }

    // Add first subject box on page load
    function initializeSubjectBox() {
        subjectBoxCounter = 0;
        $('#subjectBoxesContainer').html(createSubjectBox());
    }

    // Initialize on page load
    initializeSubjectBox();

    // Add subject box button click
    $('#addSubjectBoxBtn').click(function() {
        $('#subjectBoxesContainer').append(createSubjectBox());
    });

    // ==================== SEMESTER/TERM HANDLING ====================
    
    // Semester option change handler
    $('input[name="semester_option"]').change(function() {
        const selectedOption = $(this).val();
        const specificContainer = $('#specific_semesters_container');
        
        if (selectedOption === 'specific') {
            specificContainer.show();
            $('#semester_select').html('<option value="">-- Select Semester/Term --</option>');
            
            // Load semesters if course is already selected
            const productId = $('#course_subtype_id').val();
            if (productId) {
                loadSemesters(productId);
            }
        } else {
            specificContainer.hide();
            $('#semester_select').val('');
        }
    });

    // Load semesters function
    window.loadSemesters = function(productId) {
        if (!productId) {
            console.error('No product ID provided');
            $('#semester_select').html('<option value="">-- Select Semester/Term --</option>');
            return;
        }
        
        $('#semester_select').html('<option value="">Loading semesters...</option>');
        
        $.get('/ajax/subjects/semesters/' + productId)
        .done(function(response) {
            if (response.success) {
                $('#semester_select').html('<option value="">-- Select Semester/Term --</option>');
                
                if (response.semesters && response.semesters.length > 0) {
                    response.semesters.forEach(semester => {
                        if (semester && semester.trim() !== '') {
                            const semesterText = semester.trim();
                            $('#semester_select').append(`<option value="${semesterText}">${semesterText}</option>`);
                        }
                    });
                    showToast('success', `Loaded ${response.semesters.length} semester(s)/term(s)`);
                } else {
                    $('#semester_select').html('<option value="">No semesters/terms configured</option>');
                    showToast('warning', 'No semesters/terms configured for this course.');
                }
            } else {
                $('#semester_select').html(`<option value="">Error: ${response.message || 'Unknown error'}</option>`);
                showToast('error', response.message || 'Failed to load semesters');
            }
        })
        .fail(function(xhr, status, error) {
            console.error('AJAX Error loading semesters:', error);
            $('#semester_select').html('<option value="">Error loading semesters</option>');
            showToast('error', 'Failed to load semesters. Please try again.');
        });
    }

    // Reset form function
    window.resetSubjectForm = function() {
        $('#subjectForm')[0].reset();
        
        // Reset counters FIRST
        subjectBoxCounter = 0;
        subSubjectCounter = {};
        
        // Then create new box
        $('#subjectBoxesContainer').html(createSubjectBox());
        
        resetDownstreamSelects();
        $('#department_category_id').val('');
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('#assigned_date').val('{{ date("Y-m-d") }}');
        $('#semester_option_all').prop('checked', true);
        $('#specific_semesters_container').hide();
    }

    function resetDownstreamSelects() {
        $('#department_id').prop('disabled', true).html('<option value="">-- Select Department --</option>');
        $('#course_type').prop('disabled', true).html('<option value="">-- Select Course Type --</option>');
        $('#course_subtype_id').prop('disabled', true).html('<option value="">-- Select Sub Type --</option>');
        $('#semester_select').html('<option value="">-- Select Semester/Term --</option>');
        $('#course_detail_id').val('');
    }

    // Category change event
    $('#department_category_id').change(function() {
        let categoryId = $(this).val();
        
        resetDownstreamSelects();
        
        if (categoryId) {
            $('#department_id').html('<option value="">Loading departments...</option>');
            
            $.get('/ajax/departments-by-category', { category_id: categoryId })
                .done(function(response) {
                    if (response.success) {
                        $('#department_id').html('<option value="">-- Select Department --</option>');
                        if (response.departments && response.departments.length > 0) {
                            response.departments.forEach(dept => {
                                $('#department_id').append(
                                    `<option value="${dept.department_id}">${dept.department}</option>`
                                );
                            });
                            $('#department_id').prop('disabled', false);
                        } else {
                            $('#department_id').html('<option value="">No departments found</option>');
                            showToast('warning', 'No departments found for this category.');
                        }
                    } else {
                        $('#department_id').html('<option value="">Error loading departments</option>');
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
        
        $('#course_type').prop('disabled', true).html('<option value="">-- Select Course Type --</option>');
        $('#course_subtype_id').prop('disabled', true).html('<option value="">-- Select Sub Type --</option>');
        $('#semester_select').html('<option value="">-- Select Semester/Term --</option>');
        
        if (deptId) {
            $('#course_type').html('<option value="">Loading courses...</option>');
            
            $.get('/ajax/course-types-by-department', { department_id: deptId })
                .done(function(response) {
                    if (response.status === 'success') {
                        $('#course_type').html('<option value="">-- Select Course Type --</option>');
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
        
        $('#course_subtype_id').prop('disabled', true).html('<option value="">-- Select Sub Type --</option>');
        $('#semester_select').html('<option value="">-- Select Semester/Term --</option>');
        
        if (deptId && courseType) {
            $('#course_subtype_id').html('<option value="">Loading sub types...</option>');
            
            $.get('/ajax/get-branches-by-course', { 
                department_id: deptId, 
                course_type: courseType 
            })
            .done(function(response) {
                if (response.status === 'success') {
                    $('#course_subtype_id').html('<option value="">-- Select Sub Type --</option>');
                    if (response.branches && response.branches.length > 0) {
                        response.branches.forEach(branch => {
                            $('#course_subtype_id').append(
                                `<option value="${branch.product_id}" data-sub-type="${branch.sub_type}">${branch.sub_type}</option>`
                            );
                        });
                        $('#course_subtype_id').prop('disabled', false);
                    } else {
                        $('#course_subtype_id').html('<option value="">No sub types found</option>');
                        showToast('warning', 'No sub types found for this course.');
                    }
                } else {
                    $('#course_subtype_id').html('<option value="">Error loading sub types</option>');
                    showToast('error', response.message || 'Error loading sub types');
                }
            })
            .fail(function() {
                $('#course_subtype_id').html('<option value="">Error loading sub types</option>');
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
            // Only load semesters if specific semester option is currently selected
            if ($('input[name="semester_option"]:checked').val() === 'specific') {
                loadSemesters(productId);
            }
        } else {
            $('#semester_select').html('<option value="">-- Select Semester/Term --</option>');
        }
    });

    // ==================== FORM SUBMISSION ====================
    
    $('#subjectForm').submit(function(e) {
        e.preventDefault();
        
        // Validate form
        if (!validateForm()) {
            return;
        }

        // Check if at least one subject is added
        const subjectBoxes = $('.subject-box');
        if (subjectBoxes.length === 0) {
            showToast('error', 'Please add at least one subject');
            return;
        }

        // Validate each subject has a name
        let validSubjects = true;
        subjectBoxes.each(function() {
            const subjectName = $(this).find('.subject-name').val().trim();
            if (!subjectName) {
                $(this).find('.subject-name').addClass('is-invalid');
                validSubjects = false;
            } else {
                $(this).find('.subject-name').removeClass('is-invalid');
            }
        });

        if (!validSubjects) {
            showToast('error', 'Please enter a name for all subjects');
            return;
        }

        // Prepare form data
        let formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('department_category_id', $('#department_category_id').val());
        formData.append('department_id', $('#department_id').val());
        formData.append('course_type', $('#course_type').val());
        formData.append('course_subtype_id', $('#course_subtype_id').val());
        formData.append('course_detail_id', $('#course_detail_id').val());
        formData.append('semester_option', $('input[name="semester_option"]:checked').val());
        formData.append('assigned_date', $('#assigned_date').val());
        formData.append('remarks', $('#remarks').val());

        // Add semester data based on option
        const semesterOption = $('input[name="semester_option"]:checked').val();
        if (semesterOption === 'specific') {
            const selectedSemester = $('#semester_select').val();
            if (!selectedSemester) {
                $('#semester_select').addClass('is-invalid');
                $('#semesterError').text('Please select a semester/term');
                return false;
            }
            // Send as single value, not array
            formData.append('semester_id', selectedSemester);
        }

        // Add subjects and sub-subjects
        let subjectIndex = 0;
        subjectBoxes.each(function() {
            subjectIndex++;
            const boxId = $(this).attr('id');
            const subjectName = $(this).find('.subject-name').val().trim();
            
            // Add subject name
            formData.append(`subjects[${subjectIndex}][name]`, subjectName);
            
            // Add sub-subjects
            const subSubjects = $(this).find('.sub-subject-input-group input');
            subSubjects.each(function(subIndex) {
                const subSubjectName = $(this).val().trim();
                if (subSubjectName) {
                    formData.append(`subjects[${subjectIndex}][sub_subjects][]`, subSubjectName);
                }
            });
        });

        // Show loading
        $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Saving...');

        // Send AJAX request
        $.ajax({
            url: "{{ route('subject-coursewise.store') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    
                    // Redirect back to subjects list after successful save
                    setTimeout(function() {
                        window.location.href = "{{ route('subject-coursewise.index') }}";
                    }, 1500);
                } else {
                    showToast('error', response.message || 'Failed to save subjects');
                    $('#submitBtn').prop('disabled', false).html('<i class="bi bi-check2-circle"></i> Save All Subjects');
                }
            },
            error: function(xhr) {
                console.error('Error response:', xhr);
                let errors = xhr.responseJSON?.errors || {};
                let errorMessage = xhr.responseJSON?.message || 'An error occurred while saving subjects';
                
                // Display validation errors
                Object.keys(errors).forEach(function(key) {
                    let errorElement = $('#' + key + 'Error');
                    if (errorElement.length) {
                        errorElement.text(errors[key][0]);
                        $('#' + key).addClass('is-invalid');
                    }
                });
                
                showToast('error', errorMessage);
                $('#submitBtn').prop('disabled', false).html('<i class="bi bi-check2-circle"></i> Save All Subjects');
            }
        });
    });

    function validateForm() {
        let isValid = true;
        let requiredFields = ['department_category_id', 'department_id', 'course_type', 'course_subtype_id', 'assigned_date'];
        
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
            showToast('error', 'Please select a valid sub type');
            isValid = false;
        }
        
        // Check if specific semester is selected when that option is chosen
        const semesterOption = $('input[name="semester_option"]:checked').val();
        if (semesterOption === 'specific') {
            const selectedSemester = $('#semester_select').val();
            if (!selectedSemester) {
                $('#semester_select').addClass('is-invalid');
                $('#semesterError').text('Please select a semester/term');
                isValid = false;
            }
        }
        
        return isValid;
    }
});
</script>
@endsection