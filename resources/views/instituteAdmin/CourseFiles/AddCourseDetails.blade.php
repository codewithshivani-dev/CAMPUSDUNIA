@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --success-gradient: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
    --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 25px rgba(0,0,0,0.1);
    --shadow-hover: 0 20px 40px rgba(0,0,0,0.15);
}

/* Page Header */
.page-header {
    background: var(--primary-gradient);
    padding: 15px 15px;
    border-radius: 15px;
    margin-bottom: 30px;
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
}

.page-header h4 {
    color: white;
    font-weight: 600;
    margin: 0;
    display: flex;
    /*flex-wrap: wrap;*/
    align-items: center;
}

.page-header h4 .header-icon {
    background: rgba(255,255,255,0.2);
    padding: 12px;
    border-radius: 12px;
    margin-right: 10px;
}

.page-header .badge {
    background: rgba(255,255,255,0.2) !important;
    color: white !important;
    /*padding: 8px 15px;*/
    border-radius: 30px;
    font-weight: 500;
    font-size: 12px;
    border: 1px solid rgba(255,255,255,0.1);
    margin-left: 10px;
}

/* Back Button */
.btn-back {
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    border-radius: 12px;
    padding: 10px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-back:hover {
    background: rgba(255,255,255,0.3);
    color: white;
    transform: translateY(-2px);
}

/* Form Container */
.form1 {
    padding: 30px;
    border: 2px solid #e0e0e0;
    border-radius: 20px;
    background-color: #fff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    animation: fadeInUp 0.6s ease;
    width: 100%;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Section Title */
.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e0e0e0;
    display: flex;
    align-items: center;
}

.section-title i {
    color: #4361ee;
    margin-right: 10px;
    font-size: 1.3rem;
}

/* Form Labels */
.form-label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    font-size: 0.95rem;
}

.form-label i {
    margin-right: 8px;
    color: #4361ee;
    font-size: 1rem;
}

.form-label .text-danger {
    font-size: 1.1rem;
    margin-left: 4px;
}

/* Form Controls */
.form-control {
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: white;
    width: 100%;
}

.form-control:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    outline: none;
}

.form-control:hover {
    border-color: #3a0ca3;
}

/*.form-control:invalid {*/
/*    border-color: #dc3545;*/
/*}*/

select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%234361ee' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 16px;
    padding-right: 40px;
}

/* Alert Messages */
.alert {
    border: none;
    border-radius: 12px;
    padding: 15px 20px;
    margin-bottom: 20px;
    animation: slideInDown 0.5s ease;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    width: 100%;
}

.alert-success {
    background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    color: #1e7e34;
}

.alert-danger {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: #721c24;
}

.alert ul {
    margin-bottom: 0;
    padding-left: 20px;
}

/* Branch Container */
.branch-container {
    background: #f8fafc;
    border: 2px solid #e0e0e0;
    border-radius: 15px;
    padding: 20px;
    margin-top: 10px;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.branch-list {
    max-height: 300px;
    overflow-y: auto;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    padding: 10px;
    background: white;
    margin-top: 15px;
}

.branch-item {
    padding: 15px;
    border-bottom: 1px solid #e0e0e0;
    cursor: pointer;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
    position: relative;
    background: white;
    margin-bottom: 5px;
    border-radius: 8px;
}

.branch-item:last-child {
    border-bottom: none;
}

.branch-item:hover {
    background: linear-gradient(135deg, #f8faff 0%, #f0f4ff 100%);
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.branch-item.selected {
    background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
    border-left: 4px solid #4361ee;
}

.branch-item.auto-selected {
    background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    border-left: 4px solid #28a745;
}

.branch-info {
    font-size: 0.9em;
    color: #64748b;
    margin-top: 5px;
}

.branch-info-message {
    /*padding: 12px 15px;*/
    margin: 10px 0;
    border-radius: 10px;
    font-size: 0.95em;
    font-weight: 500;
}

.branch-info-message.info {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1e40af;
    border-left: 4px solid #3b82f6;
}

.branch-info-message.success {
    background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    color: #166534;
    border-left: 4px solid #28a745;
}

.branch-info-message.warning {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
    border-left: 4px solid #f59e0b;
}

/* Auto-select indicator */
.auto-select-indicator {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: #28a745;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8em;
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Selected branch display */
.selected-branch-display {
    padding: 12px 15px;
    margin-top: 15px;
    background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
    border-radius: 10px;
    border-left: 4px solid #4361ee;
    color: #1e40af;
    font-weight: 500;
}

/* Validation messages */
.validation-message {
    font-size: 0.875em;
    margin-top: 5px;
    padding: 8px 12px;
    border-radius: 8px;
}

.validation-message.error {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #991b1b;
}

.validation-message.success {
    background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    color: #166534;
}

/* Submit Button */
.btn-submit {
    background: var(--primary-gradient);
    border: none;
    border-radius: 12px;
    padding: 14px 30px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    color: white;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    min-width: 200px;
    justify-content: center;
}

.btn-submit:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(67, 97, 238, 0.4);
    color: white;
}

/* Toast notification */
.branch-toast {
    position: fixed;
    top: 50px;
    right: 20px;
    z-index: 999;
    min-width: 300px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    border: none;
}

.branch-toast .toast-header {
    background: var(--primary-gradient);
    color: white;
    border-bottom: none;
    padding: 12px 15px;
}

.branch-toast .toast-header strong {
    color: white;
}

.branch-toast .toast-header .btn-close {
    filter: invert(1);
}

.branch-toast .toast-body {
    padding: 15px;
    font-size: 0.95rem;
}

/* Semester preview */
.semester-preview {
    background: #f8fafc;
    padding: 10px 15px;
    border-radius: 8px;
    border-left: 4px solid #4361ee;
    font-size: 0.9rem;
    color: #2c3e50;
    margin-top: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        gap: 15px;
    }

    .form1 {
        padding: 20px;
    }

    .btn-submit {
        width: 100%;
    }

    .branch-item .auto-select-indicator {
        position: static;
        display: inline-block;
        margin-top: 8px;
        transform: none;
    }
}

@media (max-width: 480px) {
    .form1 {
        padding: 15px;
    }

    .branch-toast {
        min-width: 90%;
        right: 5%;
    }
}
</style>

<div class="container-fluid">
    <!-- Page Header with Gradient -->
    <div class="page-header">
        <h4>
            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                <i class="fas fa-school header-icon"></i>
                Add Class Details
            @else
                <i class="fas fa-book-open header-icon"></i>
                Add Course Details
            @endif
            <span class="badge">
                <i class="fas fa-plus-circle"></i>
                New Entry
            </span>
        </h4>
        <div class="d-flex gap-2">
            <button type="button" class="btn-back" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i>
                Back
            </button>
        </div>
    </div>

    <div class="form1">
        <form id="course-basic-form" action="{{ route('save.course.basic.details') }}" method="POST">
            @csrf
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mt-2">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Basic Information Section -->
            <div class="section-title">
                <i class="fas fa-info-circle"></i>
                Basic Information
            </div>

            <div class="row">
                <!-- Department Category Selection -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="department_category_id" class="form-label">
                            <i class="fas fa-layer-group"></i>
                            Department Category <span class="text-danger">*</span>
                        </label>
                        <select id="department_category_id" name="department_category_id" class="form-control" required>
                            <option value="">-- Select Department Category --</option>
                            @foreach($departmentCategories as $category)
                                <option value="{{ $category->department_category_id }}" {{ old('department_category_id') == $category->department_category_id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Department Selection -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-building"></i>
                            Select Department <span class="text-danger">*</span>
                        </label>
                        <select id="department_id" name="department_id" class="form-control" required>
                            <option value="">-- Choose Department --</option>
                        </select>
                        @if($errors->has('department_id'))
                        <div class="error">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $errors->first('department_id')}}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Course Type and Branch Section -->
            <div class="section-title mt-3">
                <i class="fas fa-tag"></i>
                Course Details
            </div>

            <div class="row">
                <!-- Course Type Selection -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="course_type" class="form-label">
                            <i class="fas fa-bookmark"></i>
                            Select
                            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                Class
                            @else
                                Course
                            @endif
                            Type <span class="text-danger">*</span>
                        </label>
                        <select id="course_type" name="course_type" class="form-control" required disabled>
                            <option value="">-- Select Type --</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Branch Selection Section -->
            <div class="col-md-12" id="branches-container" style="display: none;">
                <div class="branch-container">
                    <label class="form-label">
                        <i class="fas fa-code-branch"></i>
                        Select
                        @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                            Class
                        @else
                            Course
                        @endif
                        Branch <span class="text-danger">*</span>
                    </label>
                    
                    <!-- Branch Information Message -->
                    <div id="branch-info-message" class="branch-info-message">
                        <!-- Dynamic message will appear here -->
                    </div>
                    
                    <!-- Branches List -->
                    <div class="branch-list" id="branches-list">
                        <!-- Branches will be loaded here -->
                    </div>
                    
                    <input type="hidden" name="product_id" id="product_id">
                    <input type="hidden" name="sub_type" id="sub_type">
                    
                    <!-- Branch Validation Message -->
                    <div id="branch-validation" class="validation-message mt-2">
                        <!-- Validation messages will appear here -->
                    </div>
                </div>
            </div>

            <!-- Additional Details Section -->
            <div class="section-title mt-3">
                <i class="fas fa-cog"></i>
                Additional Details
            </div>

            <div class="row">
                <!-- Mode of Course -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="mode_of_course" class="form-label">
                            <i class="fas fa-clock"></i>
                            Mode of
                            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                Class
                            @else
                                Course
                            @endif
                            <span class="text-danger">*</span>
                        </label>
                        <select name="mode_of_course" class="form-control" required>
                            <option value="">-- Select --</option>
                            <option value="Online" {{ old('mode_of_course') == 'Online' ? 'selected' : '' }}>Online</option>
                            <option value="Offline" {{ old('mode_of_course') == 'Offline' ? 'selected' : '' }}>Offline</option>
                            <option value="Hybrid" {{ old('mode_of_course') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                    </div>
                </div>

                <!-- Course Mode Type -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="mode_type" class="form-label">
                            <i class="fas fa-tachometer-alt"></i>
                            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                Class
                            @else
                                Course
                            @endif
                            Mode Type <span class="text-danger">*</span>
                        </label>
                        <select name="mode_type" class="form-control" required>
                            <option value="">-- Select --</option>
                            <option value="part_time" {{ old('mode_type') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                            <option value="full_time" {{ old('mode_type') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                            <option value="hybrid" {{ old('mode_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                    </div>
                </div>

                <!-- Course Duration -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="course_duration_type" class="form-label">
                            <i class="fas fa-hourglass-half"></i>
                            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                Class
                            @else
                                Course
                            @endif
                            Duration <span class="text-danger">*</span>
                        </label>
                        <select id="course_duration_type" name="course_duration" class="form-control" required>
                            <option value="">-- Select --</option>
                            <option value="Hourly" {{ old('course_duration') == 'Hourly' ? 'selected' : '' }}>Hourly</option>
                            <option value="Weekly" {{ old('course_duration') == 'Weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="Monthly" {{ old('course_duration') == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="Quarterly" {{ old('course_duration') == 'Quarterly' ? 'selected' : '' }}>Quarterly</option>
                            <option value="Half_yearly" {{ old('course_duration') == 'Half_yearly' ? 'selected' : '' }}>Half Yearly</option>
                            <option value="Yearly" {{ old('course_duration') == 'Yearly' ? 'selected' : '' }}>Yearly</option>
                        </select>
                    </div>
                </div>

                <!-- Course Length -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label id="courseLengthLabel" class="form-label">
                            <i class="fas fa-ruler"></i>
                            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                Class
                            @else
                                Course
                            @endif
                            Length <span class="text-danger">*</span>
                        </label>
                        <input type="number" id="course_duration_term" name="course_length" class="form-control" 
                               placeholder="e.g. 3" value="{{ old('course_length') }}" required />
                    </div>
                </div>

                <!-- Number of Semesters/Terms (Hidden) -->
                <div class="col-md-12" style="display: none;">
                    <input type="number" id="semester_count" name="semester_count" class="form-control mb-2" 
                           placeholder="e.g. 2" min="1" max="12" value="{{ old('semester_count') }}" required>
                    <input type="hidden" id="semester_list" name="semester_list" value="{{ old('semester_list') }}">
                </div>
                
                <!-- Semester Preview (Visible) -->
                <div class="col-md-12" id="semester-preview-container">
                    <div class="semester-preview" id="semester-preview">
                        <!-- Semester preview will appear here -->
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="row mt-4">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn-submit" id="submit-btn">
                        <i class="fas fa-save"></i>
                        Submit 
                        @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                            Class
                        @else
                            Course
                        @endif
                        Details
                    </button>
                    <div id="form-validation" class="validation-message mt-2">
                        <!-- Form validation messages will appear here -->
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Toast Notification -->
<div class="toast branch-toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
    <div class="toast-header">
        <i class="fas fa-info-circle me-2"></i>
        <strong class="me-auto">
            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                Class
            @else
                Course
            @endif
            Selection
        </strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
    </div>
    <div class="toast-body" id="toast-message">
        <!-- Toast message content -->
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Bootstrap toast
    const branchToast = new bootstrap.Toast(document.querySelector('.branch-toast'));
    
    // Show toast message
    function showToast(message, type = 'info') {
        const toast = $('.branch-toast');
        const toastBody = $('#toast-message');
        
        toast.removeClass('bg-info bg-success bg-warning');
        if (type === 'success') {
            toast.addClass('bg-success text-white');
        } else if (type === 'warning') {
            toast.addClass('bg-warning');
        } else {
            toast.addClass('bg-info text-white');
        }
        
        toastBody.text(message);
        branchToast.show();
    }

    // Department Category change event
    $('#department_category_id').change(function() {
        let categoryId = this.value;
        loadDepartmentsByCategory(categoryId);
        
        $('#department_id').html('<option value="">-- Choose Department --</option>');
        $('#course_type').prop('disabled', true).html('<option value="">-- Select Class Type --</option>');
        $('#branches-container').hide();
        resetBranchSelection();
    });

    function loadDepartmentsByCategory(categoryId) {
        const departmentSelect = document.getElementById('department_id');

        if (!categoryId) {
            departmentSelect.innerHTML = '<option value="">-- Choose Department --</option>';
            return;
        }
        
        departmentSelect.innerHTML = '<option value="">Loading departments...</option>';
        
        fetch(`/ajax/departments-by-category?category_id=${categoryId}`)
            .then(res => res.json())
            .then(data => {
                departmentSelect.innerHTML = '<option value="">-- Choose Department --</option>';
                if (data.success && data.departments && data.departments.length > 0) {
                    data.departments.forEach(dept => {
                        const option = document.createElement('option');
                        option.value = dept.department_id;
                        option.textContent = dept.department;
                        departmentSelect.appendChild(option);
                    });
                } else {
                    departmentSelect.innerHTML = '<option value="">No departments found</option>';
                }
            })
            .catch((error) => {
                departmentSelect.innerHTML = '<option value="">Error loading departments</option>';
            });
    }

    // Department change event
    $('#department_id').change(function() {
        let deptId = this.value;
        let courseSelect = $('#course_type');
        
        resetBranchSelection();
        
        if (deptId) {
            courseSelect.prop('disabled', true).addClass('loading').html('<option value="">Loading courses...</option>');
            
            fetch(`/ajax/course-types-by-department?department_id=${deptId}`)
                .then(res => res.json())
                .then(data => {
                    courseSelect.html('<option value="">-- Select Course Type --</option>');
                    if (data.status === 'success' && data.courses && data.courses.length > 0) {
                        data.courses.forEach(course => {
                            courseSelect.append(`<option value="${course.finacp_merchant_sub_category_type}">${course.finacp_merchant_sub_category_type}</option>`);
                        });
                    } else {
                        courseSelect.append('<option value="">No Courses Found</option>');
                    }
                    courseSelect.prop('disabled', false).removeClass('loading');
                })
                .catch(error => {
                    courseSelect.html('<option value="">Error loading courses</option>');
                    courseSelect.prop('disabled', false).removeClass('loading');
                });
        } else {
            courseSelect.prop('disabled', true).html('<option value="">-- Select Course Type --</option>');
        }
    });

    // Course change event
    $('#course_type').change(function() {
        let courseType = this.value;
        let deptId = $('#department_id').val();
        
        if (courseType && deptId) {
            loadBranches(deptId, courseType);
        } else {
            $('#branches-container').hide();
            resetBranchSelection();
        }
    });

    // Reset branch selection
    function resetBranchSelection() {
        $('#branches-container').hide();
        $('#branches-list').empty();
        $('#product_id').val('');
        $('#sub_type').val('');
        $('#branch-info-message').empty().removeClass('info success warning');
        $('#branch-validation').empty().removeClass('error success').hide();
    }

    // Load branches function
    function loadBranches(departmentId, courseType) {
        $('#branches-container').show();
        $('#branches-list').html('<div class="text-center p-3">Loading branches...</div>');
        $('#branch-info-message').empty().removeClass('info success warning');
        $('#branch-validation').empty().removeClass('error success').hide();
        
        fetch(`/ajax/get-branches-by-course?department_id=${departmentId}&course_type=${encodeURIComponent(courseType)}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success' && data.branches && data.branches.length > 0) {
                    // Display branches in the list
                    displayBranchesInList(data.branches);
                    
                    // Auto-select if single branch
                    if (data.branches.length === 1) {
                        autoSelectSingleBranch(data.branches[0]);
                    } else {
                        showToast(`Found ${data.branches.length} branches. Please select one.`, 'info');
                    }
                } else {
                    let errorMessage = 'No branches found for this course.';
                    if (data.message) errorMessage = data.message;
                    
                    $('#branches-list').html(`
                        <div class="text-center p-3 text-muted">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            ${errorMessage}
                        </div>
                    `);
                    
                    showToast('No branches found for this course', 'warning');
                }
            })
            .catch(error => {
                $('#branches-list').html(`
                    <div class="text-center p-3 text-muted">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Error loading branches. Please try again.
                    </div>
                `);
            });
    }

    // Display branches in the list (ALWAYS SHOW THEM)
    function displayBranchesInList(branches) {
        let branchesHtml = '';
        
        branches.forEach(branch => {
            branchesHtml += `
                <div class="branch-item" 
                     data-branch-id="${branch.product_id}" 
                     data-sub-type="${branch.sub_type}">
                    <div class="fw-bold">${branch.sub_type}</div>
                    <div class="branch-info">
                        <i class="fas fa-book me-1"></i>
                        Course: ${branch.course_type}
                    </div>
                </div>
            `;
        });
        
        $('#branches-list').html(branchesHtml);
        
        // Show appropriate message
        if (branches.length === 1) {
            // Message will be shown in auto-select
        } else {
            $('#branch-info-message').html(`
                <i class="fas fa-info-circle me-2"></i>
                Found ${branches.length} branches. Please select one:
            `).addClass('info');
        }
    }

    // Auto-select single branch
    function autoSelectSingleBranch(branch) {
        // Select the branch in the list
        $('.branch-item').addClass('selected auto-selected');
        
        // Add auto-select indicator
        $('.branch-item').append('<span class="auto-select-indicator">✓ Auto-selected</span>');
        
        // Set hidden values
        $('#product_id').val(branch.product_id);
        $('#sub_type').val(branch.sub_type);
        
        // Show success message
        $('#branch-validation').html(`
            <i class="fas fa-check-circle me-2"></i>
            <strong>"${branch.sub_type}"</strong> has been auto-selected.
        `).addClass('success').show();
        
        showToast(`Single record found and auto-selected`, 'success');
    }

    // Handle branch selection (for multiple branches)
    $(document).on('click', '.branch-item', function() {
        // If it's already auto-selected, don't change
        if ($(this).hasClass('auto-selected')) {
            return;
        }
        
        $('.branch-item').removeClass('selected');
        $(this).addClass('selected');
        
        const branchId = $(this).data('branch-id');
        const subType = $(this).data('sub-type');
        
        $('#product_id').val(branchId);
        $('#sub_type').val(subType);
        
        // Show success message
        $('#branch-validation').html(`
            <i class="fas fa-check-circle me-2"></i>
            Branch <strong>"${subType}"</strong> selected.
        `).addClass('success').show();
        
        showToast(`Branch "${subType}" selected`, 'success');
    });

    // Form submission validation
    $('#course-basic-form').on('submit', function(e) {
        const branchSelected = $('#product_id').val() !== '';
        
        if (!branchSelected && $('#branches-container').is(':visible')) {
            e.preventDefault();
            
            // Show error message
            $('#branch-validation').html(`
                <i class="fas fa-exclamation-circle me-2"></i>
                Please select a branch to continue
            `).addClass('error').show();
            
            // Scroll to branch section
            $('html, body').animate({
                scrollTop: $('#branches-container').offset().top - 100
            }, 500);
            
            showToast('Please select a branch before submitting', 'warning');
            return false;
        }
        
        return true;
    });

    // Semester count handler
    $('#semester_count').on('input', function() {
        updateSemesterList();
    });

    // Auto-calculate semesters/terms
    $('#course_duration_type, #course_duration_term').on('change input', function() {
        let durationType = $('#course_duration_type').val();
        let courseLength = parseInt($('#course_duration_term').val());
        let semesterCount = 0;

        if (!durationType || isNaN(courseLength) || courseLength <= 0) {
            $('#semester_count').val('');
            $('#semester_list').val('');
            $('#semester-preview').html('');
            return;
        }

        switch (durationType) {
            case 'Hourly':
            case 'Weekly':
            case 'Monthly':
            case 'Quarterly':
                semesterCount = courseLength;
                break;
            case 'Half_yearly':
                semesterCount = courseLength;
                break;
            case 'Yearly':
                semesterCount = courseLength * 2;
                break;
            default:
                semesterCount = 1;
        }

        semesterCount = Math.max(1, Math.min(12, semesterCount));
        $('#semester_count').val(semesterCount);
        updateSemesterList();
    });

    // Update semester list
    function updateSemesterList() {
        let durationType = $('#course_duration_type').val();
        let count = parseInt($('#semester_count').val());
        let semesterInput = $('#semester_list');
        let preview = $('#semester-preview');

        if (!isNaN(count) && count > 0 && count <= 12) {
            let terms = [];
            
            switch (durationType) {
                case 'Hourly':
                    for (let i = 1; i <= count; i++) terms.push(`Hour ${i}`);
                    break;
                case 'Weekly':
                    for (let i = 1; i <= count; i++) terms.push(`Week ${i}`);
                    break;
                case 'Monthly':
                    for (let i = 1; i <= count; i++) terms.push(`Month ${i}`);
                    break;
                case 'Quarterly':
                    for (let i = 1; i <= count; i++) terms.push(`Quarter ${i}`);
                    break;
                case 'Half_yearly':
                    for (let i = 1; i <= count; i++) terms.push(`Semester ${i}`);
                    break;
                case 'Yearly':
                    for (let i = 1; i <= count; i++) terms.push(`Semester ${i}`);
                    break;
                default:
                    for (let i = 1; i <= count; i++) terms.push(`Term ${i}`);
            }
            
            semesterInput.val(JSON.stringify(terms));
            preview.html(`
                <i class="fas fa-list me-2"></i>
                <strong>Terms:</strong> ${terms.join(' · ')}
            `);
        } else {
            semesterInput.val('');
            preview.html('');
        }
    }

    // Course duration label update
    $('#course_duration_type').change(function() {
        const selectedText = this.options[this.selectedIndex].text;
        const label = $('#courseLengthLabel');

        if (selectedText && selectedText !== "-- Select --") {
            label.html(`
                <i class="fas fa-ruler me-2"></i>
                Class Length (${selectedText}) <span class="text-danger">*</span>
            `);
        } else {
            label.html(`
                <i class="fas fa-ruler me-2"></i>
                Class Length <span class="text-danger">*</span>
            `);
        }
        
        updateSemesterList();
    });
});
</script>
@endsection