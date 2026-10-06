@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
@php
    $user = Auth::user();
    $isEmployee = $user->hasRole('employee') || $user->hasRole('Teacher');
@endphp

@if(!$employee)
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Employee profile not found. Please contact administrator to set up your employee profile.
    </div>
    <div class="text-center mt-4">
        <a href="{{ route('assignments.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-1"></i> Back to Assignments
        </a>
    </div>
@else
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
    }

    body {
        background-color: #f8fafc;
    }

    .container-fluid {
        background-color: #f8fafc;
    }

    /* Page Header */
    .page-header {
        background: var(--primary-gradient);
        padding: 20px 25px;
        border-radius: 16px;
        margin-bottom: 25px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
    }

    .page-header h4 {
        color: white;
        margin: 0;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.35rem;
    }

    .page-header h4 i {
        background: rgba(255,255,255,0.2);
        padding: 10px;
        border-radius: 12px;
        font-size: 1.1rem;
    }

    .breadcrumb {
        margin-bottom: 0;
        background: transparent;
        padding: 0;
    }

    .breadcrumb-item a {
        color: rgba(255,255,255,0.8);
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        color: white;
    }

    .breadcrumb-item.active {
        color: white;
    }

    .btn-back {
        background: rgba(255,255,255,0.15);
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
        padding: 8px 20px;
        border-radius: 12px;
        font-weight: 500;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-2px);
        color: white;
    }

    /* Cards */
    .card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 25px;
    }

    .card-body {
        padding: 25px;
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title i {
        color: var(--primary-color);
        font-size: 1.2rem;
    }

    /* Form Controls */
    .form-label {
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
        font-size: 13px;
        letter-spacing: 0.3px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-label i {
        color: var(--primary-color);
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 15px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
    }

    .form-control[readonly] {
        background-color: #f8fafc;
        cursor: default;
    }

    .form-text {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 5px;
    }

    .text-danger {
        color: #ef4444 !important;
    }

    /* Buttons */
    .btn-primary {
        background: var(--primary-gradient);
        border: none;
        padding: 10px 28px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
    }

    .btn-outline-secondary {
        border: 2px solid #e2e8f0;
        color: #475569;
        background: transparent;
        padding: 10px 28px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-outline-secondary:hover {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-color: #cbd5e1;
        transform: translateY(-2px);
    }

    .btn-link {
        color: #64748b;
        text-decoration: none;
    }

    .btn-link:hover {
        color: #ef4444;
    }

    /* Alert Styles */
    .alert {
        border: none;
        border-radius: 16px;
        padding: 15px 20px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: var(--success-gradient);
        color: white;
    }

    .alert-danger {
        background: var(--danger-gradient);
        color: white;
    }

    .alert-success .btn-close,
    .alert-danger .btn-close {
        filter: brightness(0) invert(1);
    }

    /* Sticky Sidebar */
    .sticky-top {
        position: sticky;
        top: 20px;
        z-index: 0 !important;
    }

    /* Avatar Circle */
    .avatar-circle {
        background: var(--primary-gradient);
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Badges */
    .badge {
        padding: 5px 12px;
        border-radius: 30px;
        font-weight: 500;
        font-size: 11px;
    }

    .badge.bg-light {
        background: #f1f5f9 !important;
        color: #475569;
    }

    /* File Preview */
    .border.rounded {
        border-radius: 12px !important;
        border: 2px solid #e2e8f0 !important;
    }

    .bg-light {
        background-color: #f8fafc !important;
    }

    .file-preview-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 15px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s;
    }

    .file-preview-item:hover {
        border-color: var(--primary-color);
        box-shadow: 0 2px 8px rgba(67, 97, 238, 0.1);
    }

    .file-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .file-icon {
        width: 36px;
        height: 36px;
        background: var(--primary-gradient);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .file-name {
        font-weight: 600;
        font-size: 13px;
        color: #1e293b;
    }

    .file-size {
        font-size: 11px;
        color: #94a3b8;
    }

    .file-remove {
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 5px;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .file-remove:hover {
        background: #fee2e2;
        color: #ef4444;
    }

    /* List Styles */
    .list-unstyled li {
        margin-bottom: 10px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .list-unstyled li i {
        margin-top: 2px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 15px;
        }

        .page-header {
            padding: 15px 20px;
        }

        .page-header h4 {
            font-size: 1.1rem;
        }

        .btn-back {
            padding: 6px 15px;
            font-size: 13px;
        }

        .card-body {
            padding: 20px;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 10px;
        }

        .sticky-top {
            position: relative;
            top: 0;
            margin-top: 20px;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4>
                    <i class="fas fa-plus-circle"></i>
                    Create New Assignment
                </h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('assignments.index') }}">Assignments</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('assignments.index') }}" class="btn-back">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('assignments.store') }}" enctype="multipart/form-data" id="assignmentForm" class="needs-validation" novalidate>
                @csrf
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Employee Information -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-user-tie"></i> Your Information
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user"></i> Employee Name
                                </label>
                                <input type="text" class="form-control" value="{{ $employee->name }}" readonly>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-id-card"></i> Employee ID
                                </label>
                                <input type="text" class="form-control" value="{{ $employee->employee_id }}" readonly>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-briefcase"></i> Designation
                                </label>
                                <input type="text" class="form-control" value="{{ $employee->designation }}" readonly>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-building"></i> Department
                                </label>
                                @if($employeeDepartment)
                                    <input type="text" class="form-control" value="{{ $employeeDepartment->department }}" readonly>
                                    <input type="hidden" name="department_id" value="{{ $employee->department_id }}">
                                @else
                                    <input type="text" class="form-control" value="Department not assigned" readonly style="color: #dc3545;">
                                    <small class="text-danger">Please contact administrator to assign you a department</small>
                                @endif
                            </div>
                            
                            @if($employeeDepartment && $employeeDepartmentCategory)
                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-layer-group"></i> Department Category
                                </label>
                                <input type="text" class="form-control" value="{{ $employeeDepartmentCategory->category_name }}" readonly>
                                <input type="hidden" name="department_category_id" value="{{ $employeeDepartmentCategory->department_category_id }}">
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Assignment Information -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-clipboard-list"></i> Assignment Information
                        </h5>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="title" class="form-label">
                                    <i class="fas fa-heading"></i> Assignment Title <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="title" id="title" class="form-control" placeholder="Enter assignment title" required>
                                <small class="form-text">Give your assignment a clear and descriptive title</small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-file-alt"></i> Description
                                </label>
                                <textarea name="description" id="description" class="form-control" rows="4" placeholder="Provide detailed instructions for the assignment..."></textarea>
                                <small class="form-text">Include any specific requirements, guidelines, or instructions</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="due_date" class="form-label">
                                    <i class="fas fa-calendar-alt"></i> Due Date & Time <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local" name="due_date" id="due_date" class="form-control" required>
                                <small class="form-text">Set a realistic deadline for submission</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course & Subject Selection -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-graduation-cap"></i> Course & Subject Details
                        </h5>

                        <div class="row">
                            <!-- Course Type -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-book-open"></i> {{ $courseLabel ?? 'Course' }} Type <span class="text-danger">*</span>
                                </label>
                                <select id="course_type_id" class="form-control" required {{ !$employeeDepartment ? 'disabled' : '' }}>
                                    <option value="">Select {{ $courseLabel ?? 'Course' }} Type</option>
                                </select>
                                @if(!$employeeDepartment)
                                    <small class="text-danger">Please complete your department assignment first</small>
                                @endif
                            </div>

                            <!-- Branch/Program -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-code-branch"></i> {{ $courseLabel ?? 'Course' }} Sub Type <span class="text-danger">*</span>
                                </label>
                                <select id="branch_id" name="branch_id" class="form-control" required {{ !$employeeDepartment ? 'disabled' : '' }}>
                                    <option value="">Select {{ $courseLabel ?? 'Course' }} Sub Type</option>
                                </select>
                                <div id="singleSubtypeMessage" class="text-success mt-1" style="display: none;">
                                    <i class="fas fa-check-circle"></i> Only one sub-type available, auto-selected
                                </div>
                            </div>

                            <!-- Semester/Term Selector (shown only if needed) -->
                            <div class="col-md-6 mb-3" id="semester_container" style="display: none;">
                                <label class="form-label">
                                    <i class="fas fa-layer-group"></i> Semester/Term
                                </label>
                                <select name="semester_id" id="semester_id" class="form-control">
                                    <option value="">-- Select Semester --</option>
                                </select>
                                <div id="semester_info" class="text-info mt-1" style="display: none;">
                                     <span id="semester_info_text"></span>
                                </div>
                            </div>

                            <!-- Subject -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-book"></i> Subject <span class="text-danger">*</span>
                                </label>
                                <select name="subject_id" id="subject_id" class="form-control" required disabled>
                                    <option value="">Select Subject</option>
                                </select>
                                <div id="subject_info" class="text-muted mt-1" style="display: none;">
                                    <small id="subject_info_text"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Attachments -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-paperclip"></i> File Attachments
                        </h5>

                        <div class="mb-3">
                            <label for="files" class="form-label">
                                <i class="fas fa-upload"></i> Upload Files
                            </label>
                            <div class="border rounded p-3 bg-light">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <input type="file" name="files[]" id="files" class="form-control" multiple 
                                               accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png,.zip">
                                    </div>
                                    <button type="button" class="btn btn-link text-danger ms-2" id="clearFiles" title="Clear all files">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <small class="form-text d-block mt-2">
                                    <i class="fas fa-info-circle me-1"></i> Max size: 10MB per file. Supported: PDF, DOC, PPT, Images, ZIP
                                </small>
                            </div>

                            <div id="filePreview" class="mt-3"></div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary" id="saveDraft">
                        <i class="fas fa-save me-1"></i> Save as Draft
                    </button>
                    <button type="submit" class="btn btn-primary px-4" id="submitBtn" {{ !$employeeDepartment ? 'disabled' : '' }}>
                        <i class="fas fa-plus-circle me-1"></i> Create Assignment
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Sidebar - Progress & Tips -->
        <div class="col-lg-4">
            <div class="card sticky-top">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-user-check"></i> Assignment Progress
                    </h5>

                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <div class="avatar-circle d-inline-flex align-items-center justify-content-center mb-3">
                                <i class="fas fa-user-tie fa-2x text-white"></i>
                            </div>
                            <h5 class="mb-1">{{ $employee->name }}</h5>
                            <p class="text-muted mb-2">{{ $employee->designation }}</p>
                            <div class="badge bg-light text-dark border">
                                <i class="fas fa-id-card me-1"></i> {{ $employee->employee_id }}
                            </div>
                        </div>
                    </div>

                    <div class="border-top pt-3 mt-3">
                        <h6 class="fw-semibold mb-3">Progress Status</h6>
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-grow-1">
                                <small class="text-muted">Department Assignment</small>
                            </div>
                            <div class="badge {{ $employeeDepartment ? 'bg-success text-white' : 'bg-danger text-white' }}" id="deptStatus">
                                {{ $employeeDepartment ? 'Completed' : 'Pending' }}
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-grow-1">
                                <small class="text-muted">Course Selection</small>
                            </div>
                            <div class="badge bg-light text-dark" id="courseStatus">Pending</div>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-grow-1">
                                <small class="text-muted">Subject Selection</small>
                            </div>
                            <div class="badge bg-light text-dark" id="subjectStatus">Pending</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <small class="text-muted">Files Attachment</small>
                            </div>
                            <div class="badge bg-light text-dark" id="fileStatus">Pending</div>
                        </div>
                    </div>

                    <div class="border-top pt-3 mt-3">
                        <h6 class="fw-semibold mb-2">Quick Tips</h6>
                        <ul class="list-unstyled small text-muted">
                            <li class="mb-2">
                                <i class="fas fa-check-circle {{ $employeeDepartment ? 'text-success' : 'text-danger' }} me-2"></i>
                                @if($employeeDepartment)
                                    Your department is assigned: {{ $employeeDepartment->department }}
                                @else
                                    Please contact administrator to assign you a department
                                @endif
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Select course type and sub-type to load subjects
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Set a realistic due date for submission
                            </li>
                            <li>
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Attach relevant files if needed
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
const instituteType = "{{ $instituteType ?? '' }}";
const isEmployee = {{ $isEmployee ? 'true' : 'false' }};
const courseLabel = instituteType === 'School' ? 'Class' : 'Course';
const employeeDepartmentId = "{{ $employee->department_id ?? '' }}";
const hasDepartment = {{ $employeeDepartment ? 'true' : 'false' }};

// Store subject data
let availableSemesters = [];
let subjectDistributionType = null;

$(document).ready(function() {
    if (!hasDepartment) {
        $('#course_type_id, #branch_id').prop('disabled', true);
        $('#submitBtn').prop('disabled', true);
        alert('Please complete your department assignment before creating assignments.');
        return;
    }
    
    // Initialize the form - load course types for employee's department
    if (employeeDepartmentId) {
        loadCourseTypes(employeeDepartmentId);
    }
    
    // ... rest of your JavaScript code remains the same ...
    // File preview and management functions
    function getFileIcon(filename) {
        const ext = filename.split('.').pop().toLowerCase();
        const icons = {
            pdf: 'file-pdf',
            doc: 'file-word',
            docx: 'file-word',
            ppt: 'file-powerpoint',
            pptx: 'file-powerpoint',
            jpg: 'file-image',
            jpeg: 'file-image',
            png: 'file-image',
            zip: 'file-archive',
            default: 'file'
        };
        return icons[ext] || icons.default;
    }

    $('#files').on('change', function() {
        const files = $(this)[0].files;
        const preview = $('#filePreview');
        preview.empty();

        if (files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                const icon = getFileIcon(file.name);

                const fileItem = `
                    <div class="file-preview-item">
                        <div class="file-info">
                            <div class="file-icon">
                                <i class="fas fa-${icon}"></i>
                            </div>
                            <div>
                                <div class="file-name">${file.name}</div>
                                <div class="file-size">${fileSize} MB</div>
                            </div>
                        </div>
                        <button type="button" class="file-remove" data-index="${i}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                preview.append(fileItem);
            }
            $('#fileStatus').removeClass('bg-light text-dark').addClass('bg-success text-white').text('Completed');
        } else {
            $('#fileStatus').removeClass('bg-success text-white').addClass('bg-light text-dark').text('Pending');
        }
    });

    // Remove file from preview
    $(document).on('click', '.file-remove', function(e) {
        e.preventDefault();
        const index = $(this).data('index');
        const input = $('#files')[0];
        const dt = new DataTransfer();

        for (let i = 0; i < input.files.length; i++) {
            if (i !== index) {
                dt.items.add(input.files[i]);
            }
        }

        input.files = dt.files;
        $(this).closest('.file-preview-item').remove();
        $('#files').trigger('change');
    });

    // Clear all files
    $('#clearFiles').on('click', function(e) {
        e.preventDefault();
        $('#files').val('');
        $('#filePreview').empty();
        $('#fileStatus').removeClass('bg-success text-white').addClass('bg-light text-dark').text('Pending');
    });
});

// Load course types for employee's department
function loadCourseTypes(deptId) {
    const courseTypeSelect = $('#course_type_id');
    
    if (!deptId) return;
    
    fetch(`/ajax/course-types-by-department?department_id=${deptId}`)
        .then(res => res.json())
        .then(data => {
            courseTypeSelect.html('<option value="">Select ' + courseLabel + ' Type</option>');
            if (data.status === 'success' && data.courses) {
                data.courses.forEach(course => {
                    courseTypeSelect.append(`<option value="${course.finacp_merchant_sub_category_type}">${course.finacp_merchant_sub_category_type}</option>`);
                });
                courseTypeSelect.prop('disabled', false);
                $('#courseStatus').removeClass('bg-light text-dark').addClass('bg-success text-white').text('Ready');
            } else if (data.courseTypes) {
                data.courseTypes.forEach(course => {
                    courseTypeSelect.append(`<option value="${course.course_type}">${course.course_type}</option>`);
                });
                courseTypeSelect.prop('disabled', false);
                $('#courseStatus').removeClass('bg-light text-dark').addClass('bg-success text-white').text('Ready');
            } else {
                courseTypeSelect.html('<option value="">No course types found</option>');
            }
        })
        .catch(error => {
            courseTypeSelect.html('<option value="">Error loading course types</option>');
        });
}

// When Course Type changes
$('#course_type_id').on('change', function() {
    const courseType = this.value;
    const departmentId = employeeDepartmentId;
    const subtypeSelect = $('#branch_id');
    const messageDiv = $('#singleSubtypeMessage');
    
    // Reset
    subtypeSelect.html('<option value="">Select ' + courseLabel + ' Sub Type</option>');
    messageDiv.hide();
    resetFormAfterCourseType();  // Now this function exists
    
    if (courseType && departmentId) {
        fetch(`/ajax/get-branches-by-course?department_id=${departmentId}&course_type=${encodeURIComponent(courseType)}`)
            .then(res => res.json())
            .then(data => {
                console.log('Branches response:', data); // Debug log
                
                if (data.status === 'success' && data.branches) {
                    subtypeSelect.html('<option value="">Select ' + courseLabel + ' Sub Type</option>');
                    
                    if (data.branches.length > 0) {
                        data.branches.forEach(item => {
                            subtypeSelect.append(`<option value="${item.product_id}">${item.sub_type}</option>`);
                        });
                        subtypeSelect.prop('disabled', false);
                        
                        // Auto-select if only one sub-type exists
                        if (data.branches.length === 1) {
                            const singleItem = data.branches[0];
                            subtypeSelect.val(singleItem.product_id);
                            messageDiv.show();
                            messageDiv.html(`<i class="fas fa-check-circle"></i> Only one sub-type available: ${singleItem.sub_type}`);
                            
                            // Check subject distribution and load accordingly
                            checkSubjectDistribution(singleItem.product_id);
                        }
                    } else {
                        subtypeSelect.html('<option value="">No sub types found</option>');
                        console.warn('No branches returned from API');
                    }
                } else {
                    console.error('API Error response:', data);
                    subtypeSelect.html(`<option value="">Error: ${data.message || 'Failed to load sub types'}</option>`);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                subtypeSelect.html('<option value="">Error loading sub types</option>');
            });
    } else {
        subtypeSelect.html('<option value="">Please select course type first</option>');
    }
});

$('#branch_id').on('change', function() {
    const productId = this.value;
    if (productId) {
        // Check how subjects are distributed for this course
        checkSubjectDistribution(productId);
    } else {
        resetFormAfterCourseType();
    }
});

    // When semester is selected
    $('#semester_id').on('change', function() {
        const semesterId = $(this).val();
        const productId = $('#branch_id').val();

        if (!semesterId || !productId) return;

        loadSubjectsBySemester(productId, semesterId);
    });


    function checkSubjectDistribution(productId) {
    console.log('Checking subject distribution for product:', productId);
    
    // Reset UI
    $('#semester_container').hide();
    $('#semester_info').hide();
    $('#subject_info').hide();
    $('#subject_id').prop('disabled', true).html('<option value="">Loading...</option>');
    $('#subjectStatus').removeClass('bg-success text-white').addClass('bg-light text-dark').text('Loading...');

    // First, load available semesters from product_details
    loadAvailableSemesters(productId)
        .then(() => {
            console.log('Available semesters loaded:', availableSemesters);
            
            // Now check subjects in subjects_coursewise table
            // Use the existing subjects API endpoint
            return fetch(`/ajax/get-subjects-by-course?course_detail_id=${productId}`)
                .then(res => res.json())
                .then(data => {
                    console.log('Subjects API response:', data);
                    
                    if (data.status === 'success' && data.subjects && data.subjects.length > 0) {
                        analyzeSubjectDistribution(data.subjects);
                    } else {
                        $('#subject_info').show();
                        $('#subject_info_text').text('No subjects found for this course');
                        $('#subject_id').html('<option value="">No subjects available</option>');
                        $('#subjectStatus').removeClass('bg-light text-dark').addClass('bg-danger text-white').text('No Subjects');
                    }
                });
        })
        .catch(error => {
            console.error('Error in checkSubjectDistribution:', error);
            // Fallback: load subjects directly
            loadSubjectsDirectly(productId);
        });
    }

    // Load available semesters from product_details
    function loadAvailableSemesters(productId) {
    return fetch(`/ajax/get-semesters/${productId}`)
        .then(res => res.json())
        .then(data => {
            console.log('Semesters API response:', data);
            
            if (data.success && data.semesters && data.semesters.length > 0) {
                availableSemesters = data.semesters;
                return availableSemesters;
            } else {
                availableSemesters = [];
                console.log('No semesters found for product:', productId);
                return [];
            }
        })
        .catch(error => {
            console.error('Error loading semesters:', error);
            availableSemesters = [];
            return [];
        });
    }
    // Analyze how subjects are distributed
    function analyzeSubjectDistribution(subjects) {
        if (!subjects || subjects.length === 0) {
            $('#subject_info').show();
            $('#subject_info_text').text('No subjects found for this course');
            $('#subject_id').html('<option value="">No subjects available</option>');
            $('#subjectHelpText').text('No subjects available');
            return;
        }

        // Check if all subjects have 'all_semesters'
        const allAllSemesters = subjects.every(subject => subject.semester_id === 'all_semesters');

        // Check if all subjects have specific semesters
        const allSpecificSemesters = subjects.every(subject =>
            subject.semester_id && subject.semester_id !== 'all_semesters');

        // Check if mixed distribution
        const hasAllSemesters = subjects.some(subject => subject.semester_id === 'all_semesters');
        const hasSpecificSemesters = subjects.some(subject =>
            subject.semester_id && subject.semester_id !== 'all_semesters');

        const semesterContainer = $('#semester_container');
        const semesterInfo = $('#semester_info');
        const semesterInfoText = $('#semester_info_text');
        const semesterHelpText = $('#semesterHelpText');
        const subjectSelect = $('#subject_id');

        if (allAllSemesters) {
            // Case 1: All subjects are for all semesters
            subjectDistributionType = 'all_semesters';
            semesterContainer.hide();
            semesterInfo.show();
            semesterInfoText.text('Subjects are available for all semesters');
            semesterHelpText.text('No semester selection needed');

            // Show all subjects
            populateSubjects(subjects);
            $('#subjectHelpText').text('Subjects loaded successfully');

        } else if (allSpecificSemesters) {
            // Case 2: All subjects have specific semesters
            subjectDistributionType = 'specific_semesters';

            // Show semester selector
            semesterContainer.show();
            semesterInfo.show();
            semesterInfoText.text('Please select a semester to view subjects');
            semesterHelpText.text('Required for subject selection');

            // Populate semester dropdown
            populateSemesterSelect();

            // Clear subjects until semester is selected
            subjectSelect.html('<option value="">Select semester first</option>');
            subjectSelect.prop('disabled', true);
            $('#subjectHelpText').text('Select a semester to load subjects');

        } else if (hasAllSemesters && hasSpecificSemesters) {
            // Case 3: Mixed distribution (some all_semesters, some specific)
            subjectDistributionType = 'mixed';

            // Show semester selector
            semesterContainer.show();
            semesterInfo.show();
            semesterInfoText.html(
                '<i class="fas fa-info-circle me-1"></i> This course has mixed subject types. Select a semester or choose "All Semesters"'
                );
            semesterHelpText.text('Optional: Select to filter semester-specific subjects');

            // Add "All Semesters" option to semester dropdown
            populateSemesterSelect();
            const semesterSelect = $('#semester_id');
            if (!semesterSelect.find('option[value="all_semesters"]').length) {
                semesterSelect.prepend('<option value="all_semesters">All Semesters</option>');
            }

            // Show all subjects initially
            populateSubjects(subjects);
            $('#subjectHelpText').text('Subjects loaded. You can filter by semester if needed.');

        } else {
            // Case 4: Some subjects have no semester info
            subjectDistributionType = 'unknown';
            semesterContainer.hide();
            populateSubjects(subjects);
            $('#subjectHelpText').text('Subjects loaded');
        }
    }

    // In the loadSubjectsDirectly function:
    function loadSubjectsDirectly(productId) {
    console.log('Loading subjects directly for product:', productId);
    
    fetch(`/ajax/get-subjects-by-course?course_detail_id=${productId}`)
        .then(res => res.json())
        .then(data => {
            console.log('Direct subjects data:', data);
            const subjectSelect = $('#subject_id');

            if (data.status === 'success' && data.subjects && data.subjects.length > 0) {
                populateSubjects(data.subjects);
                
                // Hide semester container since we don't have semester info
                $('#semester_container').hide();
                $('#semester_info').hide();
                
                $('#subjectStatus').removeClass('bg-light text-dark').addClass('bg-success text-white').text('Completed');
            } else {
                subjectSelect.html('<option value="">No subjects found</option>');
                $('#subjectStatus').removeClass('bg-light text-dark').addClass('bg-danger text-white').text('No Subjects');
            }
        })
        .catch(error => {
            console.error('Error loading subjects directly:', error);
            $('#subject_id').html('<option value="">Error loading subjects</option>');
            $('#subjectStatus').removeClass('bg-light text-dark').addClass('bg-danger text-white').text('Error');
        });
}


    // Load subjects by semester
    function loadSubjectsBySemester(productId, semesterId) {
        const subjectSelect = $('#subject_id');
        subjectSelect.prop('disabled', true).html('<option value="">Loading subjects...</option>');
        $('#subjectHelpText').text('Loading subjects...');

        fetch(
                `/ajax/get-subjects-by-course-semester?course_detail_id=${productId}&semester_id=${encodeURIComponent(semesterId)}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success' && data.subjects && data.subjects.length > 0) {
                    populateSubjects(data.subjects);

                    // Update info text
                    $('#subject_info').show();
                    if (semesterId === 'all_semesters') {
                        $('#subject_info_text').text(`${data.subjects.length} subject(s) available for all semesters`);
                        $('#subjectHelpText').text('Subjects loaded for all semesters');
                    } else {
                        $('#subject_info_text').text(`${data.subjects.length} subject(s) available for ${semesterId}`);
                        $('#subjectHelpText').text(`Subjects loaded for ${semesterId}`);
                    }
                } else {
                    subjectSelect.html('<option value="">No subjects found for this semester</option>');
                    $('#subject_info').show();
                    $('#subject_info_text').text(`No subjects available for ${semesterId}`);
                    $('#subjectHelpText').text('No subjects found');
                }
            })
            .catch(error => {
                // Fallback: try to load all subjects
                fetch(`/ajax/get-subjects-by-course?course_detail_id=${productId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success' && data.subjects) {
                            // Filter subjects client-side by semester
                            const filteredSubjects = data.subjects.filter(subject =>
                                subject.semester_id === semesterId ||
                                subject.semester_id === 'all_semesters'
                            );
                            if (filteredSubjects.length > 0) {
                                populateSubjects(filteredSubjects);
                                $('#subjectHelpText').text(`Subjects filtered for ${semesterId}`);
                            } else {
                                subjectSelect.html('<option value="">No subjects found for this semester</option>');
                                $('#subjectHelpText').text('No subjects match this semester');
                            }
                        } else {
                            subjectSelect.html('<option value="">No subjects found</option>');
                            $('#subjectHelpText').text('No subjects available');
                        }
                    })
                    .catch(fallbackError => {
                        subjectSelect.html('<option value="">Error loading subjects</option>');
                        $('#subjectHelpText').text('Error loading subjects');
                    });
            });
    }

    // Populate semester select dropdown
    function populateSemesterSelect() {
        const semesterSelect = $('#semester_id');
        semesterSelect.html('<option value="">-- Select Semester --</option>');

        if (availableSemesters.length > 0) {
            availableSemesters.forEach(semester => {
                semesterSelect.append(`<option value="${semester}">${semester}</option>`);
            });
            semesterSelect.prop('disabled', false);
        } else {
            semesterSelect.html('<option value="">No semesters defined for this course</option>');
            semesterSelect.prop('disabled', true);
        }
    }

    // Populate subjects dropdown
    function populateSubjects(subjects) {
    console.log('Populating subjects:', subjects);
    
    const subjectSelect = $('#subject_id');
    subjectSelect.html('<option value="">Select Subject</option>');
    
    if (subjects && subjects.length > 0) {
        subjects.forEach(subject => {
            const optionText = subject.semester_id && subject.semester_id !== 'all_semesters' 
                ? `${subject.subject_name} (${subject.semester_id})`
                : subject.subject_name;
            
            subjectSelect.append(`
                <option value="${subject.subject_id}">
                    ${optionText}
                </option>`);
        });
        
        $('#subject_info').show();
        $('#subject_info_text').text(`${subjects.length} subject(s) available`);
        subjectSelect.prop('disabled', false);
        
        // Update progress status
        $('#subjectStatus').removeClass('bg-light text-dark').addClass('bg-success text-white').text('Completed');
    } else {
        subjectSelect.html('<option value="">No subjects found</option>');
        subjectSelect.prop('disabled', true);
    }
}
    // Reset form when branch is cleared
    function resetFormAfterBranch() {
        $('#semester_container').hide();
        $('#semester_info').hide();
        $('#subject_info').hide();
        $('#subject_id').prop('disabled', true).html('<option value="">Select Subject</option>');
        $('#semester_id').prop('disabled', true).html('<option value="">Select Semester</option>');
        $('#subjectStatus').removeClass('bg-success text-white').addClass('bg-light text-dark').text('Pending');
    }

    // Reset form when course type changes
    function resetFormAfterCourseType() {
        $('#subject_id').html('<option value="">Select Subject</option>');
        $('#semester_container').hide();
        $('#semester_id').html('<option value="">-- Select Semester --</option>');
        $('#subject_info').hide();
        $('#subjectStatus').removeClass('bg-success text-white').addClass('bg-light text-dark').text('Pending');
        availableSemesters = [];
        subjectDistributionType = null;
    }
    // When branch is selected
    $('#branch_id').on('change', function() {
        const branchId = $(this).val();
        const semesterSelect = $('#semester_id');

        if (branchId) {
            // Load semesters for this branch
            $.ajax({
                url: '/ajax/get-semesters/' + branchId,
                type: 'GET',
                beforeSend: function() {
                    semesterSelect.prop('disabled', true).html(
                        '<option value="">Loading semesters...</option>');
                },
                success: function(response) {
                    semesterSelect.html('<option value="">Select Semester</option>');

                    if (response.success && response.semesters && response.semesters
                        .length > 0) {
                        // This branch has semesters
                        $.each(response.semesters, function(key, semester) {
                            semesterSelect.append(
                                `<option value="${semester}">${semester}</option>`
                            );
                        });
                        semesterSelect.prop('disabled', false);

                        // Add an option for "All Semesters" if needed
                        semesterSelect.append(
                            '<option value="all_semesters">All Semesters</option>');
                    } else {
                        // This branch doesn't have specific semesters
                        semesterSelect.append(
                            '<option value="">No specific semesters</option>');
                        semesterSelect.prop('disabled', true);

                        // Load subjects immediately since no semester selection needed
                        loadSubjects();
                    }
                },
                error: function() {
                    semesterSelect.html(
                        '<option value="">Error loading semesters</option>');
                }
            });
        } else {
            semesterSelect.prop('disabled', true).html('<option value="">Select Branch first</option>');
            $('#subject_id').prop('disabled', true).html(
                '<option value="">Select Branch first</option>');
        }
    });

     // Update progress status based on form changes
    $('input, select, textarea').on('change', function() {
        if ($('#title').val() && $('#due_date').val()) {
            $('#submitBtn').prop('disabled', false);
        }
    });

    // Form submission
    $('#assignmentForm').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();

        // Disable submit button
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i> Creating...');

        // Submit form
        setTimeout(() => {
            form[0].submit();
        }, 1000);
    });

    // Save draft functionality
    $('#saveDraft').on('click', function() {
        alert('Draft save functionality to be implemented');
    });

    // Set minimum date to today
    const today = new Date().toISOString().slice(0, 16);
    $('#due_date').attr('min', today);

    // Auto-focus title field
    $('#title').focus();
  

</script>
@endif
@endsection