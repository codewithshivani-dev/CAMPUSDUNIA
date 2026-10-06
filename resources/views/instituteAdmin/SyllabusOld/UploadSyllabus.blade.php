@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<!-- Bootstrap Icons CDN -->
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

    .page-title small {
        font-size: 16px;
        color: rgba(255, 255, 255, 0.9);
    }

    /* View All Button */
    .btn-outline-primary {
        padding: 10px 20px;
        background: var(--primary-gradient);
        border: none;
        color: #fff!important;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }

    .btn-outline-primary:hover {
        transform: translateY(-3px);
        text-decoration: none;
    }

    /* Main Card */
    .main-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        background: white;
    }

    .card-body {
        padding: 30px;
    }

    .card-body.bg-light {
        background: linear-gradient(135deg, #ffffff, #f8fafc) !important;
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

    .form-label.fw-semibold {
        color: var(--primary-color);
    }

    .form-label i {
        color: var(--primary-color);
        margin-right: 5px;
    }

    /* Form Controls */
    .form-control,
    .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
        height: auto;
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

    .shadow-sm {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05) !important;
    }

    /* Alert Messages */
    .alert {
        border: none;
        border-radius: 16px;
        padding: 18px 25px;
        margin-bottom: 25px;
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

    .alert-warning {
        background: linear-gradient(135deg, #fed7aa, #fdba74);
        border: 1px solid #fcd34d;
        color: #92400e;
    }

    .alert .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* Topic Item Card */
    .topic-item {
        border: 2px solid #e2e8f0 !important;
        border-radius: 20px !important;
        overflow: hidden;
        transition: all 0.3s;
        background: white;
    }

    .topic-item:hover {
        border-color: var(--primary-color) !important;
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.1);
    }

    .topic-item .card-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-bottom: 1px solid #e2e8f0;
        padding: 15px 20px;
    }

    .topic-item .card-body {
        padding: 20px;
    }

    .topic-item h6 {
        color: var(--primary-color);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .topic-item h6 i {
        font-size: 18px;
    }

    /* Buttons */
    .btn {
        border-radius: 10px;
        font-weight: 600;
        padding: 10px 20px;
        transition: all 0.3s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .btn:active {
        transform: translateY(-1px);
    }

    .btn-primary {
        background: var(--primary-gradient);
        color: white;
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

   

    .btn-outline-primary:hover {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white;
    }

    .btn-outline-danger {
        background: transparent;
        border: 2px solid #ef4444;
        color: #ef4444;
    }

    .btn-outline-danger:hover {
        background: var(--danger-gradient);
        border-color: transparent;
        color: white;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }

    /* Info and Success Messages */
    .text-success {
        color: var(--success-color) !important;
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .text-info {
        color: var(--info-gradient) !important;
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .text-muted {
        color: #64748b !important;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .text-muted small {
        font-size: 13px;
    }

    .text-muted i {
        color: var(--primary-color);
    }

    /* File Input */
    input[type="file"] {
        padding: 8px;
        border: 2px dashed #e2e8f0;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        cursor: pointer;
    }

    input[type="file"]:hover {
        border-color: var(--primary-color);
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    }

    input[type="file"]::-webkit-file-upload-button {
        background: var(--primary-gradient);
        border: none;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 500;
        margin-right: 12px;
        cursor: pointer;
        transition: all 0.3s;
    }

    input[type="file"]::-webkit-file-upload-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    }

    /* Submit Button Container */
    .text-center {
        margin-top: 20px;
    }

    .btn.px-4 {
        padding: 12px 30px;
        font-size: 16px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
        }

        .page-title small {
            font-size: 14px;
            display: block;
            margin-top: 5px;
        }

        .btn-outline-primary {
            width: 100%;
            justify-content: center;
        }

        .card-body {
            padding: 20px;
        }

        .row > [class*="col-"] {
            margin-bottom: 15px;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 10px;
        }
    }

    /* Grid spacing */
    .row.g-3 {
        --bs-gutter-y: 1rem;
    }

    /* Info icons */
    .bi-info-circle,
    .bi-check-circle {
        font-size: 14px;
    }

    /* Add Topic button */
    .btn-outline-primary.mt-2 {
        margin-top: 15px !important;
    }
</style>

@php
    $user = Auth::user();
    $isEmployee = $user->hasRole('employee') || $user->hasRole('Teacher');
    $instituteType = $instituteType ?? '';
    $courseLabel = ($instituteType === 'School') ? 'Class' : 'Course';
    
    // Get employee details if logged in as employee
    $employee = $employee ?? null;
@endphp

<div class="container-fluid">
    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-cloud-arrow-up-fill"></i>
            Upload Syllabus
            @if($isEmployee && $employee)
                <small>(Uploading as: {{ $employee->name }})</small>
            @endif
        </h1>
        <a href="{{ route('instituteAdmin.syllabus.viewAll') }}" class="btn-outline-primary">
            <i class="bi bi-list-ul"></i> View All Syllabuses
        </a>
    </div>

    {{-- Upload Card --}}
    <div class="main-card">
        <div class="card-body p-4 bg-light">
            <form method="POST" action="{{ route('instituteAdmin.syllabus.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf

                <div class="row g-4">
                    {{-- Department Category --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="bi bi-building-fill"></i> Category *</label>
                        <select id="category" class="form-control shadow-sm" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->department_category_id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Department --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="bi bi-building-fill"></i> Department *</label>
                        <select id="department" name="department_id" class="form-control shadow-sm" required>
                            @if($isEmployee && $employeeDepartments->count() > 0)
                                @foreach($employeeDepartments as $dept)
                                    <option value="{{ $dept->department_id }}" selected>{{ $dept->department }}</option>
                                @endforeach
                            @else
                                <option value="">Select Department</option>
                            @endif
                        </select>
                    </div>

                    {{-- Course Type --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="bi bi-book-fill"></i> {{ $courseLabel }} Type *</label>
                        <select id="courseType" class="form-control shadow-sm" required>
                            <option value="">Select {{ $courseLabel }} Type</option>
                        </select>
                    </div>

                    {{-- Sub Type --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="bi bi-bookmark-fill"></i> {{ $courseLabel }} Sub Type *</label>
                        <select id="subType" name="course_detail_id" class="form-control shadow-sm" required>
                            <option value="">Select {{ $courseLabel }} Sub Type</option>
                        </select>
                        <div id="singleSubtypeMessage" class="text-success mt-2" style="display: none;">
                            <i class="bi bi-check-circle-fill"></i> Only one sub-type available, auto-selected
                        </div>
                    </div>

                    {{-- Semester/Term Selector (shown only if needed) --}}
                    <div class="col-md-6" id="semester_container" style="display: none;">
                        <label class="form-label fw-semibold"><i class="bi bi-calendar-fill"></i> Semester/Term *</label>
                        <select name="semester_id" id="semester_id" class="form-control shadow-sm">
                            <option value="">-- Select Semester --</option>
                        </select>
                        <div id="semester_info" class="text-info mt-2" style="display: none;">
                            <i class="bi bi-info-circle-fill"></i> <span id="semester_info_text"></span>
                        </div>
                    </div>

                    {{-- Subject --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="bi bi-journal-bookmark-fill"></i> Subject *</label>
                        <select name="subject_id" id="subject" class="form-control shadow-sm" required>
                            <option value="">Select Subject</option>
                        </select>
                        <div id="subject_info" class="text-muted mt-2" style="display: none;">
                            <i class="bi bi-info-circle-fill"></i> <small id="subject_info_text"></small>
                        </div>
                    </div>

                    {{-- Title --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="bi bi-pencil-fill"></i> Syllabus Title *</label>
                        <input type="text" name="title" id="syllabus_title" class="form-control shadow-sm" 
                               placeholder="e.g., Mathematics Syllabus" required>
                    </div>

                    {{-- Topics Section --}}
                    <div class="col-12 mb-4">
                        <label class="form-label fw-semibold"><i class="bi bi-list-ul"></i> Syllabus Topics</label>

                        <div id="topicsContainer">
                            <!-- Topic Item -->
                            <div class="topic-item card border-0 shadow-sm mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0 fw-semibold text-primary">
                                            <i class="bi bi-journal-text"></i> Topic 1
                                        </h6>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label"><i class="bi bi-pencil"></i> Topic Name *</label>
                                            <input type="text" name="topics[0][topic_name]" class="form-control" placeholder="Enter topic name">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label"><i class="bi bi-chat-text"></i> Topic Description</label>
                                            <input type="text" name="topics[0][description]" class="form-control" placeholder="Short description">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label"><i class="bi bi-calendar-plus"></i> Start Date *</label>
                                            <input type="date" name="topics[0][start_date]" class="form-control">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label"><i class="bi bi-calendar-x"></i> End Date *</label>
                                            <input type="date" name="topics[0][end_date]" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-sm mt-3" onclick="addTopic()">
                            <i class="bi bi-plus-circle-fill"></i> Add Topic
                        </button>
                    </div>

                    {{-- File Upload --}}
                    <div class="col-md-12 mb-4">
                        <label class="form-label fw-semibold"><i class="bi bi-file-earmark-arrow-up-fill"></i> Upload Syllabus (PDF/DOC)</label>
                        <input type="file" name="file" class="form-control shadow-sm p-2" accept=".pdf,.doc,.docx,.jpg,.png">
                        <small class="text-muted d-block mt-2">
                            <i class="bi bi-info-circle-fill"></i> Max file size: 5MB. Supported formats: PDF, DOC, DOCX, JPG, PNG
                        </small>
                    </div>

                    {{-- Hidden field for admin uploads --}}
                    @if(!$isEmployee)
                        <input type="hidden" name="uploaded_by_admin" value="1">
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show">
                            {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Hidden field for syllabus_for --}}
                    <input type="hidden" name="syllabus_for" id="syllabus_for_hidden" value="subject_level">

                    {{-- Submit Button --}}
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary px-5 py-3">
                            <i class="bi bi-cloud-arrow-up-fill"></i> Upload Syllabus
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script Section --}}
<script>
const instituteType = "{{ $instituteType }}";
const isEmployee = {{ $isEmployee ? 'true' : 'false' }};
const courseLabel = instituteType === 'School' ? 'Class' : 'Course';

// Store subject data
let availableSemesters = [];
let subjectDistributionType = null;

let topicIndex = 1;
function addTopic() {
    const container = document.getElementById('topicsContainer');
    const div = document.createElement('div');
    div.className = 'topic-item card border-0 shadow-sm mb-3';
    div.innerHTML = `
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0 fw-semibold text-primary">
                    <i class="bi bi-journal-text"></i> Topic ${topicIndex + 1}
                </h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeTopic(this)">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-pencil"></i> Topic Name *</label>
                    <input type="text" name="topics[${topicIndex}][topic_name]" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-chat-text"></i> Topic Description</label>
                    <input type="text" name="topics[${topicIndex}][description]" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-calendar-plus"></i> Start Date *</label>
                    <input type="date" name="topics[${topicIndex}][start_date]" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-calendar-x"></i> End Date *</label>
                    <input type="date" name="topics[${topicIndex}][end_date]" class="form-control">
                </div>
            </div>
        </div>
    `;
    container.appendChild(div);
    topicIndex++;
}

function removeTopic(button) {
    button.closest('.topic-item').remove();
}

// Load departments when category changes
document.getElementById('category').addEventListener('change', function() {
    const categoryId = this.value;
    const deptSelect = document.getElementById('department');
    
    if (categoryId) {
        fetch(`/ajax/departments-by-category?category_id=${categoryId}`)
            .then(res => res.json())
            .then(data => {
                deptSelect.innerHTML = '<option value="">Select Department</option>';
                if (data.success && data.departments) {
                    data.departments.forEach(dept => {
                        deptSelect.innerHTML += `<option value="${dept.department_id}">${dept.department}</option>`;
                    });
                } else {
                    deptSelect.innerHTML = '<option value="">No departments found</option>';
                }
            })
            .catch(error => {
                console.error('Error loading departments:', error);
                deptSelect.innerHTML = '<option value="">Error loading departments</option>';
            });
    }
});

// When Department changes
document.getElementById('department').addEventListener('change', function() {
    const deptId = this.value;
    resetFormAfterDepartment();
    loadCourseTypes(deptId);
});

// Reset form when department changes
function resetFormAfterDepartment() {
    document.getElementById('subType').innerHTML = '<option value="">Select ' + courseLabel + ' Sub Type</option>';
    document.getElementById('subject').innerHTML = '<option value="">Select Subject</option>';
    document.getElementById('semester_container').style.display = 'none';
    document.getElementById('semester_id').innerHTML = '<option value="">-- Select Semester --</option>';
    document.getElementById('subject_info').style.display = 'none';
    document.getElementById('syllabus_title').value = '';
    availableSemesters = [];
    subjectDistributionType = null;
}

// Load course types
function loadCourseTypes(deptId) {
    const courseTypeSelect = document.getElementById('courseType');
    
    if (!deptId) return;
    
    fetch(`/ajax/course-types-by-department?department_id=${deptId}`)
        .then(res => res.json())
        .then(data => {
            courseTypeSelect.innerHTML = '<option value="">Select ' + courseLabel + ' Type</option>';
            if (data.status === 'success' && data.courses) {
                data.courses.forEach(course => {
                    courseTypeSelect.innerHTML += `<option value="${course.finacp_merchant_sub_category_type}">${course.finacp_merchant_sub_category_type}</option>`;
                });
            } else if (data.courseTypes) {
                data.courseTypes.forEach(course => {
                    courseTypeSelect.innerHTML += `<option value="${course.course_type}">${course.course_type}</option>`;
                });
            } else {
                courseTypeSelect.innerHTML = '<option value="">No course types found</option>';
            }
        })
        .catch(error => {
            console.error('Error loading course types:', error);
            courseTypeSelect.innerHTML = '<option value="">Error loading course types</option>';
        });
}

// When Course Type changes
document.getElementById('courseType').addEventListener('change', function() {
    const courseType = this.value;
    const departmentId = document.getElementById('department').value;
    const subtypeSelect = document.getElementById('subType');
    const messageDiv = document.getElementById('singleSubtypeMessage');
    
    // Reset
    subtypeSelect.innerHTML = '<option value="">Select ' + courseLabel + ' Sub Type</option>';
    messageDiv.style.display = 'none';
    resetFormAfterCourseType();
    
    if (courseType && departmentId) {
        fetch(`/ajax/get-branches-by-course?department_id=${departmentId}&course_type=${encodeURIComponent(courseType)}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success' && data.branches) {
                    subtypeSelect.innerHTML = '<option value="">Select ' + courseLabel + ' Sub Type</option>';
                    
                    if (data.branches.length > 0) {
                        data.branches.forEach(item => {
                            subtypeSelect.innerHTML += `<option value="${item.product_id}">${item.sub_type}</option>`;
                        });
                        
                        // Auto-select if only one sub-type exists
                        if (data.branches.length === 1) {
                            const singleItem = data.branches[0];
                            subtypeSelect.value = singleItem.product_id;
                            messageDiv.style.display = 'block';
                            messageDiv.innerHTML = `<i class="bi bi-check-circle-fill"></i> Only one sub-type available: ${singleItem.sub_type}`;
                            
                            // Check subject distribution and load accordingly
                            checkSubjectDistribution(singleItem.product_id);
                        }
                    } else {
                        subtypeSelect.innerHTML = '<option value="">No sub types found</option>';
                    }
                } else {
                    console.error('API Error:', data.message);
                    subtypeSelect.innerHTML = `<option value="">Error: ${data.message || 'Failed to load sub types'}</option>`;
                }
            })
            .catch(error => {
                console.error('Error loading sub types:', error);
                subtypeSelect.innerHTML = '<option value="">Error loading sub types</option>';
            });
    } else {
        subtypeSelect.innerHTML = '<option value="">Please select department first</option>';
    }
});

// Reset form when course type changes
function resetFormAfterCourseType() {
    document.getElementById('subject').innerHTML = '<option value="">Select Subject</option>';
    document.getElementById('semester_container').style.display = 'none';
    document.getElementById('semester_id').innerHTML = '<option value="">-- Select Semester --</option>';
    document.getElementById('subject_info').style.display = 'none';
    document.getElementById('syllabus_title').value = '';
    availableSemesters = [];
    subjectDistributionType = null;
}

// When Sub Type changes
document.getElementById('subType').addEventListener('change', function() {
    const productId = this.value;
    if (productId) {
        // Check how subjects are distributed for this course
        checkSubjectDistribution(productId);
    } else {
        resetFormAfterCourseType();
    }
});

// Check how subjects are distributed for a course
function checkSubjectDistribution(productId) {
    // First, load available semesters from product_details
    loadAvailableSemesters(productId)
        .then(() => {
            // Now check subjects in subjects_coursewise table
            // FIXED: Use the correct route
            return fetch(`/instituteAdmin/syllabus/get-subjects/${productId}`)
                .then(res => res.json())
                .then(data => {
                    console.log('Subjects data received:', data); // Add for debugging
                    if (Array.isArray(data) && data.length > 0) {
                        // Transform data to match expected format
                        const transformedData = data.map(item => ({
                            subject_id: item.subject_id,
                            subject_name: item.subject_name,
                            semester_id: item.semester_id || 'all_semesters'
                        }));
                        analyzeSubjectDistribution(transformedData);
                    } else {
                        document.getElementById('subject_info').style.display = 'block';
                        document.getElementById('subject_info_text').innerHTML = 'No subjects found for this course';
                        document.getElementById('subject').innerHTML = '<option value="">No subjects available</option>';
                    }
                });
        })
        .catch(error => {
            console.error('Error loading semesters:', error);
            // Fallback: load subjects directly
            loadSubjectsDirectly(productId);
        });
}


// Load available semesters from product_details
function loadAvailableSemesters(productId) {
    return fetch(`/ajax/get-semesters/${productId}`)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.semesters && data.semesters.length > 0) {
                availableSemesters = data.semesters;
                console.log('Loaded semesters:', availableSemesters);
            } else {
                availableSemesters = [];
                console.log('No semesters found for product:', productId);
            }
        })
        .catch(error => {
            console.error('Error loading semesters:', error);
            availableSemesters = [];
            throw error;
        });
}

// Analyze how subjects are distributed
function analyzeSubjectDistribution(subjects) {
    const semesterContainer = document.getElementById('semester_container');
    const semesterInfoText = document.getElementById('semester_info_text');

    const allAll = subjects.every(s => s.semester_id === 'all_semesters');
    const allSpecific = subjects.every(s => s.semester_id && s.semester_id !== 'all_semesters');
    const mixed = !allAll && !allSpecific;

    if (allAll) {
        semesterContainer.style.display = 'none';
        semesterInfoText.textContent = 'Subjects are available for all semesters';
        document.getElementById('syllabus_for_hidden').value = 'class_level';
        populateSubjects(subjects);
    }

    else if (allSpecific || mixed) {
        semesterContainer.style.display = 'block';
        semesterInfoText.textContent = 'Please select a semester';
        document.getElementById('syllabus_for_hidden').value = 'subject_level';
        populateSemesterSelect();
        document.getElementById('subject').innerHTML =
            '<option value="">Select semester first</option>';
    }
}


// In the loadSubjectsDirectly function:
function loadSubjectsDirectly(productId) {
    // FIXED: Use the correct route
    fetch(`/instituteAdmin/syllabus/get-subjects/${productId}`)
        .then(res => res.json())
        .then(data => {
            console.log('Direct subjects data:', data); // Add for debugging
            const subjectSelect = document.getElementById('subject');
            subjectSelect.innerHTML = '<option value="">Select Subject</option>';
            
            if (Array.isArray(data) && data.length > 0) {
                // Transform data to match expected format
                const transformedData = data.map(item => ({
                    subject_id: item.subject_id,
                    subject_name: item.subject_name,
                    semester_id: item.semester_id || 'all_semesters'
                }));
                
                populateSubjects(transformedData);
                
                // Hide semester container since we don't have semester info
                document.getElementById('semester_container').style.display = 'none';
                document.getElementById('semester_info').style.display = 'none';
                
                // For schools, set to class_level
                if (instituteType === 'School') {
                    document.getElementById('syllabus_for_hidden').value = 'class_level';
                }
            } else {
                subjectSelect.innerHTML = '<option value="">No subjects found</option>';
            }
        })
        .catch(error => {
            console.error('Error loading subjects:', error);
            document.getElementById('subject').innerHTML = '<option value="">Error loading subjects</option>';
        });
}

// Populate semester select dropdown
function populateSemesterSelect() {
    const semesterSelect = document.getElementById('semester_id');
    semesterSelect.innerHTML = '<option value="">-- Select Semester --</option>';
    
    if (availableSemesters.length > 0) {
        availableSemesters.forEach(semester => {
            semesterSelect.innerHTML += `<option value="${semester}">${semester}</option>`;
        });
    } else {
        semesterSelect.innerHTML = '<option value="">No semesters defined for this course</option>';
    }
}

// When semester is selected
document.getElementById('semester_id').addEventListener('change', function () {
    const semesterId = this.value;
    const productId = document.getElementById('subType').value;

    if (!semesterId || !productId) return;

    fetch(`/ajax/get-subjects-by-course-semester?course_detail_id=${productId}&semester_id=${semesterId}`)
        .then(res => res.json())
        .then(data => {
            populateSubjects(data.subjects || []);
        });
});

// Populate subjects dropdown
function populateSubjects(subjects) {
    const subjectSelect = document.getElementById('subject');
    subjectSelect.innerHTML = '<option value="">Select Subject</option>';
    
    if (subjects && subjects.length > 0) {
        subjects.forEach(subject => {
            subjectSelect.innerHTML += `
                <option value="${subject.subject_id}" 
                        data-name="${subject.subject_name}"
                        data-semester="${subject.semester_id || ''}">
                    ${subject.subject_name}
                    ${subject.semester_id && subject.semester_id !== 'all_semesters' ? `(${subject.semester_id})` : ''}
                </option>`;
        });
        
        document.getElementById('subject_info').style.display = 'block';
        document.getElementById('subject_info_text').innerHTML = `${subjects.length} subject(s) available`;
    }
}

// When subject changes, update title
document.getElementById('subject').addEventListener('change', function() {
    updateSyllabusTitle();
});

// Update syllabus title
function updateSyllabusTitle() {
    const subjectSelect = document.getElementById('subject');
    const subjectName = subjectSelect.options[subjectSelect.selectedIndex]?.dataset.name;
    const semester = document.getElementById('semester_id').value;

    let title = subjectName ? `${subjectName} Syllabus` : '';

    if (semester && semester !== 'all_semesters') {
        title = `${subjectName} - ${semester} Syllabus`;
    }

    document.getElementById('syllabus_title').value = title;
}

// Form validation
(function() {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            // Custom validation for semester field if needed
            const subjectDistribution = subjectDistributionType;
            const semesterId = document.getElementById('semester_id');
            const subjectId = document.getElementById('subject').value;
            
            if (subjectDistribution === 'specific_semesters' && !subjectId) {
                // If subjects are semester-specific but no semester selected
                if (!semesterId.value) {
                    semesterId.classList.add('is-invalid');
                    event.preventDefault();
                    event.stopPropagation();
                    
                    if (!document.getElementById('semester-error')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.id = 'semester-error';
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Please select a semester first';
                        semesterId.parentNode.appendChild(errorDiv);
                    }
                } else {
                    semesterId.classList.remove('is-invalid');
                    const errorDiv = document.getElementById('semester-error');
                    if (errorDiv) errorDiv.remove();
                }
            }
            
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>
@endsection