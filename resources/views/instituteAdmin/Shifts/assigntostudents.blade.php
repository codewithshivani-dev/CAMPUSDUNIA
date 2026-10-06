@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet" />

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

    /* Main Card - Enhanced */
    .card {
        border: none;
        border-radius: 20px !important;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15) !important;
        overflow: hidden;
    }

    .card-header {
        background: var(--primary-gradient) !important;
        border-bottom: none;
        padding: 20px 30px !important;
    }

    .card-header h4 {
        font-weight: 700;
        font-size: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header h4 i {
        font-size: 24px;
        background: rgba(255,255,255,0.2);
        padding: 8px;
        border-radius: 12px;
    }

    .card-header .btn-light {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        border-radius: 10px;
        padding: 8px 16px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .card-header .btn-light:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        color: white;
    }

    .card-body {
        padding: 30px !important;
        background: white;
    }

    /* Step Sections */
    .step-section {
        border-left: 4px solid var(--primary-color);
        padding-left: 20px;
        margin-bottom: 30px;
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border-radius: 0 12px 12px 0;
        padding: 20px;
    }

    .step-section h5 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .step-section h5 i {
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 24px;
    }

    /* Assignment Options - Enhanced */
    .assignment-option {
        cursor: pointer;
        transition: all 0.4s ease;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        background: white;
        height: 100%;
    }

    .assignment-option:hover {
        border-color: var(--primary-color);
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.15);
    }

    .assignment-option.selected {
        border-color: var(--primary-color);
        background: linear-gradient(135deg, #f8fafc, #e7f1ff);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .assignment-option .card-body {
        padding: 25px !important;
        text-align: center;
    }

    .assignment-option i {
        font-size: 48px;
        margin-bottom: 15px;
    }

    .assignment-option i.bi-building {
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .assignment-option i.bi-people {
        background: var(--success-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .assignment-option h6 {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .assignment-option small {
        color: #64748b;
    }

    .cursor-pointer {
        cursor: pointer;
        width: 100%;
        display: block;
    }

    /* Form Labels */
    .form-label {
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .form-label.fw-bold {
        color: var(--primary-color);
    }

    /* Select2 Customization */
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 45px;
        border: 2px solid #e2e8f0 !important;
        border-radius: 10px !important;
        padding: 5px;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        line-height: 45px;
        padding-left: 15px;
        color: #334155;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered {
        padding: 5px;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
        background: var(--primary-gradient);
        border: none;
        color: white;
        border-radius: 30px;
        padding: 5px 15px;
        font-size: 13px;
        font-weight: 500;
        margin: 3px;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        margin-right: 5px;
        font-weight: bold;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #fee2e2;
        background: transparent;
    }

    .select2-container--bootstrap-5 .select2-dropdown {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .select2-container--bootstrap-5 .select2-results__option--selected {
        background: var(--primary-gradient) !important;
        color: white !important;
    }

    .select2-container--bootstrap-5 .select2-results__option--highlighted {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0) !important;
        color: #1e293b !important;
    }

    /* Student List Container */
    #studentListContainer {
        border: 2px solid #e2e8f0 !important;
        border-radius: 12px !important;
        background: linear-gradient(135deg, #f8fafc, #ffffff) !important;
        padding: 20px !important;
        max-height: 350px;
        overflow-y: auto;
    }

    #studentListContainer::-webkit-scrollbar {
        width: 8px;
    }

    #studentListContainer::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    #studentListContainer::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        border-radius: 4px;
    }

    #studentListContainer::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #3a0ca3, #4361ee);
    }

    /* Student Checkbox Items */
    .student-item {
        padding: 12px;
        margin-bottom: 8px;
        border-radius: 10px;
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        transition: all 0.3s;
        border: 1px solid #e2e8f0;
    }

    .student-item:hover {
        transform: translateX(5px);
        border-color: var(--primary-color);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.1);
    }

    .student-item .form-check {
        padding-left: 0;
        margin-bottom: 0;
    }

    .form-check-input {
        width: 20px;
        height: 20px;
        margin-right: 12px;
        cursor: pointer;
        border: 2px solid #cbd5e1;
        border-radius: 6px;
        transition: all 0.2s;
        float: none;
    }

    .form-check-input:hover {
        border-color: var(--primary-color);
        transform: scale(1.1);
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
    }

    .form-check-label {
        cursor: pointer;
        color: #334155;
        font-weight: 500;
        margin-left: 5px;
        display: inline-block;
        width: calc(100% - 30px);
    }

    .form-check-label strong {
        color: #1e293b;
        font-weight: 600;
    }

    .form-check-label .text-muted {
        color: #64748b !important;
    }

    .form-check-label small {
        display: block;
        margin-top: 2px;
    }

    /* Select All Checkbox */
    #selectAllStudents {
        width: 20px;
        height: 20px;
        margin-right: 10px;
    }

    /* Alert Messages - Enhanced */
    .alert {
        border: none;
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideIn 0.3s ease;
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

    .alert-info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border: 1px solid #93c5fd;
        color: #1e40af;
    }

    .alert-warning {
        background: linear-gradient(135deg, #fed7aa, #fdba74);
        border: 1px solid #fcd34d;
        color: #92400e;
    }

    .alert .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* Submit Button - Enhanced */
    .btn-success {
        background: var(--success-gradient);
        border: none;
        border-radius: 50px !important;
        padding: 12px 40px !important;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s;
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
    }

    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
    }

    .btn-success i {
        font-size: 18px;
    }

    /* Loading Spinner */
    .spinner-border-sm {
        width: 1.5rem;
        height: 1.5rem;
        border-width: 0.2rem;
    }

    /* Section Headers */
    .text-primary {
        color: var(--primary-color) !important;
    }

    .text-muted {
        color: #94a3b8 !important;
    }

    .text-danger {
        color: #ef4444 !important;
        font-weight: 600;
    }

    .text-success {
        color: #10b981 !important;
    }

    .text-warning {
        color: #f59e0b !important;
    }

    .text-info {
        color: #3b82f6 !important;
    }

    /* Small Text */
    small.text-muted {
        display: block;
        margin-top: 5px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 20px !important;
        }

        .step-section {
            padding: 15px;
        }

        .assignment-option .card-body {
            padding: 20px !important;
        }

        .assignment-option i {
            font-size: 36px;
        }

        .btn-success {
            width: 100%;
        }

        .student-item .d-flex {
            flex-direction: column;
        }
    }

    /* Animation for sections */
    #categorySection, #departmentSection, #assignmentTypeSection, #studentSelectionSection, #submitSection {
        animation: fadeInUp 0.5s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Loading indicator */
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 10px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Max height utility */
    .max-height-300 {
        max-height: 350px;
        overflow-y: auto;
    }

    /* Border radius utilities */
    .rounded-top-4 {
        border-top-left-radius: 20px !important;
        border-top-right-radius: 20px !important;
    }

    /* Cursor */
    .cursor-pointer {
        cursor: pointer;
    }

    /* Info badge in student list */
    .alert-info.mb-3 {
        margin-bottom: 15px !important;
        padding: 10px 15px;
    }
    .select2-selection__choice__display
    {
        color: #fff !important;
    }
    #shiftSelect option,
    #department_ids option{
        margin-bottom: .5em;
        padding: .6rem;
        border: 1px solid #e2e2e2;
    }
</style>

<div class="container-fluid">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
            <h4 class="mb-0"><i class="bi bi-clock-history"></i> Assign Shift to Students</h4>
            <a href="{{ route('manage.shifts.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Back to Shifts
            </a>
        </div>

        <div class="card-body p-4">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <form action="{{ route('student.shifts.assign') }}" method="POST" id="assignShiftForm">
                @csrf

                <!-- Step 1: Select Multiple Shifts -->
                <div class="step-section">
                    <h5 class="text-primary mb-3"><i class="bi bi-1-circle-fill"></i> Step 1: Select Shifts <span class="text-danger">*</span></h5>
                    <label class="form-label fw-bold">Select Shifts <span class="text-danger">*</span></label>
                    <select name="shift_ids[]" class="form-control select2-multiple" multiple="multiple" required
                        id="shiftSelect">
                        <!--<option value="">-- Select Shifts --</option>-->
                        @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}" data-priority="{{ $shift->priority }}">
                            {{ $shift->shift_name }}
                            ({{ $shift->formatted_start_time }} - {{ $shift->formatted_end_time }})
                            @if($shift->start_date || $shift->end_date)
                            [
                            {{ $shift->start_date ? \Carbon\Carbon::parse($shift->start_date)->format('j M Y') : 'Always' }}
                            @if($shift->end_date)
                            to {{ \Carbon\Carbon::parse($shift->end_date)->format('j M Y') }}
                            @endif
                            ]
                            @endif
                        </option>
                        @endforeach
                    </select>
                    <small class="text-muted">You can select multiple shifts</small>
                </div>

                <!-- Step 2: Select Single Category -->
                <div class="step-section" id="categorySection" style="display: none;">
                    <h5 class="text-primary mb-3"><i class="bi bi-2-circle-fill"></i> Step 2: Select Department Category <span
                                class="text-danger">*</span></h5>
                    <label class="form-label fw-bold">Select Department Category <span
                                class="text-danger">*</span></label>
                    <select id="department_category_id" name="department_category_id"
                        class="form-control select2-single" required>
                        <option value="">-- Select Category --</option>
                        @foreach($departmentCategories as $category)
                        <option value="{{ $category->department_category_id }}">
                            {{ $category->category_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Step 3: Select Multiple Departments -->
                <div class="step-section" id="departmentSection" style="display: none;">
                    <h5 class="text-primary mb-3"><i class="bi bi-3-circle-fill"></i> Step 3: Select Departments <span class="text-danger">*</span></h5>
                    <label class="form-label fw-bold">Select Departments <span class="text-danger">*</span></label>
                    <select id="department_ids" name="department_ids[]" class="form-control select2-multiple"
                        multiple="multiple"></select>
                    <small class="text-muted">You can select multiple departments</small>
                </div>

                <!-- Step 4: Assignment Type -->
                <div class="step-section" id="assignmentTypeSection" style="display: none;">
                    <h5 class="text-primary mb-3"><i class="bi bi-4-circle-fill"></i> Step 4: Choose Assignment Type <span class="text-danger">*</span>
                        </h5>
                    <label class="form-label fw-bold">Assign To <span class="text-danger">*</span></label>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="assignment-option">
                                <div class="card-body text-center">
                                    <input type="radio" name="assign_to_type" value="whole_department"
                                        id="whole_department" class="d-none">
                                    <label for="whole_department" class="cursor-pointer">
                                        <i class="bi bi-building display-4"></i>
                                        <h6 class="mt-2">Whole Department</h6>
                                        <small class="text-muted">Assign to all students in selected departments</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="assignment-option">
                                <div class="card-body text-center">
                                    <input type="radio" name="assign_to_type" value="selected_students"
                                        id="selected_students" class="d-none">
                                    <label for="selected_students" class="cursor-pointer">
                                        <i class="bi bi-people display-4"></i>
                                        <h6 class="mt-2">Select Students</h6>
                                        <small class="text-muted">Choose specific students</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Student Selection -->
                <div class="step-section" id="studentSelectionSection" style="display: none;">
                    <h5 class="text-primary mb-3"><i class="bi bi-5-circle-fill"></i> Step 5: Select Students <span class="text-danger">*</span></h5>
                    <label class="form-label fw-bold">Select Students <span class="text-danger">*</span></label>
                    <div class="border rounded p-3 bg-light max-height-300" id="studentListContainer">
                        <div id="studentList">
                            <p class="text-muted mb-0">Select departments first to see students...</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row mt-4" id="submitSection" style="display: none;">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success px-5 py-2 shadow-sm rounded-pill">
                            <i class="bi bi-check2-circle me-1"></i> Assign Shift to Students
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 with enhanced styling
    // $('.select2-single').select2({
    //     theme: 'bootstrap-5',
    //     placeholder: 'Select an option...',
    //     allowClear: true,
    //     width: '100%'
    // });

    // $('.select2-multiple').select2({
    //     theme: 'bootstrap-5',
    //     placeholder: 'Select options...',
    //     allowClear: true,
    //     multiple: true,
    //     width: '100%'
    // });

    const categorySection = $('#categorySection');
    const departmentSection = $('#departmentSection');
    const assignmentTypeSection = $('#assignmentTypeSection');
    const studentSelectionSection = $('#studentSelectionSection');
    const submitSection = $('#submitSection');
    const shiftSelect = $('#shiftSelect');
    const departmentCategorySelect = $('#department_category_id');
    const departmentSelect = $('#department_ids');
    const studentList = $('#studentList');

    // Step 1: Shift Selection
    shiftSelect.on('change', function() {
        const selectedShifts = $(this).val();
        if (selectedShifts && selectedShifts.length > 0) {
            categorySection.fadeIn(400);
        } else {
            resetSections();
        }
    });

    // Step 2: Category Selection (Single)
    departmentCategorySelect.on('change', function() {
        const categoryId = $(this).val();
        if (categoryId) {
            loadDepartmentsByCategory(categoryId);
            departmentSection.fadeIn(400);
        } else {
            resetSections([departmentSection, assignmentTypeSection, studentSelectionSection,
                submitSection
            ]);
        }
    });

    // Step 3: Department Selection
    departmentSelect.on('change', function() {
        const selectedDepartments = $(this).val();
        if (selectedDepartments && selectedDepartments.length > 0) {
            assignmentTypeSection.fadeIn(400);
        } else {
            resetSections([assignmentTypeSection, studentSelectionSection, submitSection]);
        }
    });

    // Step 4: Assignment Type Selection
    $('input[name="assign_to_type"]').on('change', function() {
        if (this.value === 'selected_students') {
            loadStudentsByDepartments(departmentSelect.val());
            studentSelectionSection.fadeIn(400);
        } else {
            studentSelectionSection.fadeOut(400);
        }
        submitSection.fadeIn(400);
    });

    // Load departments by single category - Use existing route
    function loadDepartmentsByCategory(categoryId) {
        departmentSelect.html('<option value="">Loading departments...</option>');
        departmentSelect.prop('disabled', true).trigger('change');

        $.ajax({
            url: '{{ route("ajax.departments.by.category") }}',
            type: 'GET',
            data: {
                category_id: categoryId
            },
            beforeSend: function() {
                departmentSelect.html('<option value="">Loading departments...</option>');
            },
            success: function(data) {
                if (!data.success) {
                    departmentSelect.html('<option value="">Error loading departments</option>');
                    return;
                }

                let options = '';
                data.departments.forEach(dept => {
                    options +=
                        `<option value="${dept.department_id}">${dept.department}</option>`;
                });

                departmentSelect.html(options);
                departmentSelect.prop('disabled', false).trigger('change');
            },
            error: function() {
                departmentSelect.html('<option value="">Error loading departments</option>');
                departmentSelect.prop('disabled', true).trigger('change');
            }
        });
    }

    // Load students by departments - New route needed
    function loadStudentsByDepartments(departmentIds) {
        studentList.html(
            '<div class="text-center py-3"><span class="loading-spinner"></span> Loading students...</div>'
        );

        console.log('Loading students for departments:', departmentIds); // Debug log

        $.ajax({
            url: '{{ route("student.shifts.ajax.students.by.departments") }}',
            type: 'GET',
            data: {
                department_ids: departmentIds,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                console.log('Response:', data); // Debug log

                if (!data.success) {
                    studentList.html(`<div class="alert alert-danger">${data.message}</div>`);
                    return;
                }

                const students = data.students || [];
                if (students.length > 0) {
                    let html = `
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle-fill"></i> Found ${data.count} students
                        </div>
                        <div class="form-check mb-3 student-item">
                            <input type="checkbox" class="form-check-input" id="selectAllStudents">
                            <label class="form-check-label fw-bold" for="selectAllStudents">
                                <strong>Select All Students</strong>
                            </label>
                        </div>
                    `;

                    students.forEach(student => {
                        html += `
                        <div class="student-item">
                            <div class="form-check">
                                <input type="checkbox" name="student_ids[]" 
                                    class="form-check-input student-checkbox" 
                                    value="${student.student_hash_id}" 
                                    id="student_${student.student_hash_id}">
                                <label class="form-check-label w-100" for="student_${student.student_hash_id}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>${student.full_name}</strong>
                                            <small class="text-muted d-block">
                                                ID: ${student.student_hash_id}
                                                ${student.registration_number ? ' | Reg.No.: ' + student.registration_number : ''}
                                            </small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    `;
                    });

                    studentList.html(html);

                    // Add select all functionality
                    $('#selectAllStudents').on('change', function() {
                        const isChecked = $(this).prop('checked');
                        $('.student-checkbox').prop('checked', isChecked);
                    });
                } else {
                    studentList.html(`
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            No students found in the selected departments.
                            <div class="small mt-1">Make sure students have department assignments in Academic/Transport Details.</div>
                        </div>
                    `);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error); // Debug log
                studentList.html(`
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        Error loading students. Please try again.
                        <div class="small mt-1">${error}</div>
                    </div>
                `);
            }
        });
    }

    // Form submission cleanup
    $('#assignShiftForm').on('submit', function(e) {
        // Filter out empty values from department_ids
        const selectedDepartments = departmentSelect.val();
        if (selectedDepartments) {
            const filteredDepartments = selectedDepartments.filter(dept => dept && dept.trim() !== '');
            departmentSelect.val(filteredDepartments).trigger('change');
        }

        // Filter out empty values from shift_ids
        const selectedShifts = shiftSelect.val();
        if (selectedShifts) {
            const filteredShifts = selectedShifts.filter(shift => shift && shift.trim() !== '');
            shiftSelect.val(filteredShifts).trigger('change');
        }

        // Show loading state on submit button
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.html('<span class="loading-spinner"></span> Assigning...').prop('disabled', true);

        return true;
    });

    // Reset sections
    function resetSections(sections) {
        if (!sections) {
            sections = [categorySection, departmentSection, assignmentTypeSection,
                studentSelectionSection, submitSection
            ];
        }
        sections.forEach(section => section.fadeOut(400));
    }

    // Add styling to assignment options when selected
    $('.assignment-option input').on('change', function() {
        $('.assignment-option').removeClass('selected');
        $(this).closest('.assignment-option').addClass('selected');
    });

    // Initialize any preselected values from URL params
    const urlParams = new URLSearchParams(window.location.search);
    const shiftId = urlParams.get('shift_id');
    if (shiftId) {
        $('#shiftSelect').val([shiftId]).trigger('change');
    }
    
    const shiftIds = urlParams.get('shift_ids');
    if (shiftIds) {
        $('#shiftSelect').val(shiftIds.split(',')).trigger('change');
    }
});
</script>
@endsection