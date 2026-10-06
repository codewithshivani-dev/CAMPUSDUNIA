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

* {
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.container {
    margin-top: 30px;
    margin-bottom: 30px;
    max-width: 100%;
    overflow-x: hidden;
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
    flex-wrap: wrap;
    align-items: center;
    /*font-size: 24px;*/
}

.page-header h4 .header-icon {
    background: rgba(255,255,255,0.2);
    padding: 12px;
    border-radius: 12px;
    margin-right: 15px;
}

.page-header .badge {
    background: rgba(255,255,255,0.2) !important;
    color: white !important;
    /*padding: 8px 15px;*/
    border-radius: 30px;
    font-weight: 500;
    font-size: 12px;
    border: 1px solid rgba(255,255,255,0.1);
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
    overflow-x: hidden;
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

hr {
    border-top: 2px solid #e0e0e0;
    margin: 20px 0 30px;
}

/* Form Labels */
.form-label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    font-size: 1rem;
}

.form-label i {
    margin-right: 8px;
    color: #4361ee;
    font-size: 1.1rem;
}

.form-label .text-danger {
    font-size: 1.2rem;
    margin-left: 4px;
}

/* Form Controls */
.form-control {
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    font-size: 1rem;
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

select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%234361ee' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 16px;
    padding-right: 40px;
}

/* Row and Column System */
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -10px;
    margin-left: -10px;
    width: 100%;
}

.col-sm-6 {
    flex: 0 0 50%;
    max-width: 50%;
    padding-right: 10px;
    padding-left: 10px;
    margin-bottom: 20px;
}

.col-sm-12 {
    flex: 0 0 100%;
    max-width: 100%;
    padding-right: 10px;
    padding-left: 10px;
    margin-bottom: 20px;
}

/* Checkbox Styling */
.form-check {
    background: #f8f9fa;
    padding: 15px 20px;
    border-radius: 12px;
    border: 2px solid #e0e0e0;
    margin-bottom: 20px;
    width: 100%;
}

.form-check-input {
    width: 20px;
    height: 20px;
    cursor: pointer;
    border: 2px solid #cbd5e1;
    margin-right: 10px;
}

.form-check-input:checked {
    background-color: #4361ee;
    border-color: #4361ee;
}

.form-check-label {
    font-weight: 500;
    color: #2c3e50;
    cursor: pointer;
    font-size: 1rem;
}

/* Instructions */
.instructions {
    font-size: 0.9rem;
    color: #64748b;
    margin-top: 5px;
    margin-bottom: 10px;
    padding: 8px 12px;
    background: #f8fafc;
    border-radius: 8px;
    border-left: 4px solid #4361ee;
    width: 100%;
    word-wrap: break-word;
}

/* File Upload */
.fileDiv {
    padding-left: 0px !important;
    padding-right: 0px !important;
    width: 100%;
    overflow: hidden;
}

.file-input {
    display: block;
    clear: both;
    margin: 0 auto;
    width: 100%;
}

.file-input label {
    float: left;
    clear: both;
    width: 100%;
    padding: 2rem 1.5rem;
    text-align: center;
    background: #f8fafc;
    border-radius: 15px;
    border: 3px dashed #e0e0e0;
    transition: all 0.3s ease;
    user-select: none;
    cursor: pointer;
}

.file-input label:hover {
    border-color: #4361ee;
    background: #f0f4ff;
    transform: scale(1.02);
}

.file-input label.hover {
    border: 3px solid #4361ee;
    box-shadow: inset 0 0 0 6px rgba(67, 97, 238, 0.05);
}

.file-input #start-logo,
.file-input #start-image {
    float: left;
    clear: both;
    width: 100%;
}

.file-input #start-logo.hidden,
.file-input #start-image.hidden {
    display: none;
}

.file-input i.fa {
    font-size: 50px;
    margin-bottom: 1rem;
    transition: all .2s ease-in-out;
    color: #4361ee;
}

.file-input .response {
    float: left;
    clear: both;
    width: 100%;
}

.file-input .response.hidden {
    display: none;
}

.file-input #file-image-logo,
.file-input #file-image-image {
    display: inline;
    margin: 0 auto .5rem auto;
    width: auto;
    height: 120px;
    max-width: 150px;
    border-radius: 10px;
    border: 3px solid #4361ee;
    box-shadow: 0 5px 15px rgba(67, 97, 238, 0.2);
}

.file-input #file-image-logo.hidden,
.file-input #file-image-image.hidden {
    display: none;
}

.file-input #notimage-logo,
.file-input #notimage-image {
    display: block;
    float: left;
    clear: both;
    width: 100%;
    color: #dc3545;
    font-weight: 500;
}

.file-input #notimage-logo.hidden,
.file-input #notimage-image.hidden {
    display: none;
}

.file-input progress,
.file-input .progress {
    display: inline;
    clear: both;
    margin: 0 auto;
    width: 100%;
    max-width: 180px;
    height: 8px;
    border: 0;
    border-radius: 4px;
    background-color: #eee;
    overflow: hidden;
}

.file-input .progress[value]::-webkit-progress-bar {
    border-radius: 4px;
    background-color: #eee;
}

.file-input .progress[value]::-webkit-progress-value {
    background: var(--primary-gradient);
    border-radius: 4px;
}

.file-input .progress[value]::-moz-progress-bar {
    background: var(--primary-gradient);
    border-radius: 4px;
}

.file-input input[type="file"] {
    display: none;
}

.file-input .btn {
    display: inline-block;
    margin: .5rem .5rem 1rem .5rem;
    clear: both;
    font-family: inherit;
    font-weight: 700;
    font-size: 14px;
    text-decoration: none;
    text-transform: initial;
    border: none;
    border-radius: 30px;
    outline: none;
    padding: 8px 20px;
    color: #fff;
    transition: all 0.3s ease;
    box-sizing: border-box;
    background: var(--primary-gradient);
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
}

.file-input .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
}

/* Branch Items */
.branch-item {
    margin-bottom: 10px;
    animation: slideInRight 0.3s ease;
    width: 100%;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.input-group {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
}

.input-group .form-control {
    flex: 1;
    border-radius: 10px;
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #1e7e34);
    border: none;
    border-radius: 10px;
    padding: 8px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545, #b02a37);
    border: none;
    border-radius: 10px;
    padding: 8px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(220, 53, 69, 0.4);
}

.remove-branch-btn {
    margin-left: 10px;
    min-width: 80px;
    white-space: nowrap;
}

/* Submit Button */
.btn-container {
    text-align: center;
    margin-top: 35px;
    width: 100%;
}

.btn-primary {
    background: var(--primary-gradient);
    border: none;
    border-radius: 50px;
    padding: 14px 40px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(67, 97, 238, 0.4);
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

/* Error Styling */
.error {
    color: #dc3545;
    font-size: 0.875em;
    margin-top: 5px;
}

/* Select2 Customization */
.select2-container--default .select2-selection--multiple {
    padding: 8px;
    border-radius: 12px;
    border: 2px solid #e0e0e0;
    min-height: 50px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
    border: none;
    border-radius: 20px;
    padding: 5px 15px;
    color: #4361ee;
    font-weight: 500;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #4361ee;
    margin-right: 5px;
}

/* Preview Image */
.preview-container {
    text-align: center;
    margin-top: 15px;
}

.logo-preview {
    max-width: 120px;
    max-height: 120px;
    border-radius: 12px;
    border: 3px solid #4361ee;
    padding: 4px;
    background: #f8f9fa;
    box-shadow: 0 5px 15px rgba(67, 97, 238, 0.2);
}

/* Branches Container */
#new-branches-container,
#existing-branches-container {
    width: 100%;
    overflow: visible;
}

#new-branches-wrapper,
#existing-branches-wrapper {
    width: 100%;
}

/* Back Button */
.btn-secondary {
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    border-radius: 12px;
    padding: 10px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.btn-secondary:hover {
    background: rgba(255,255,255,0.3);
    color: white;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        /*flex-direction: column;*/
        gap: 15px;
        /*text-align: center;*/
        /*padding: 20px;*/
    }

    .form1 {
        padding: 20px;
    }

    .col-sm-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }

    .input-group {
        flex-direction: column;
        align-items: stretch;
    }

    .input-group .form-control {
        width: 100%;
    }

    .remove-branch-btn {
        margin-left: 0;
        width: 100%;
    }

    .file-input label {
        padding: 1.5rem;
    }
}

@media (max-width: 480px) {
    .form1 {
        padding: 15px;
    }

    .btn-primary {
        width: 100%;
        padding: 12px 20px;
    }
}
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h4>
            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                <i class="fas fa-school header-icon"></i>
                Add Class
            @else
                <i class="fas fa-book-open header-icon"></i>
                Add Course
            @endif
            <span class="badge ms-2">
                <i class="fas fa-plus-circle"></i>
                New Entry
            </span>
        </h4>
        <div class="d-none gap-2">
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i>
                Back
            </button>
        </div>
    </div>

    <div class="form1">
        <form id="file-upload-form" action="{{ route('AddInstitutecourses') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
            <h5 class="mb-3 text-primary">
                <i class="fas fa-info-circle me-2"></i>Class Information
            </h5>
            @else
            <h5 class="mb-3 text-primary">
                <i class="fas fa-info-circle me-2"></i>Course Information
            </h5>
            @endif
            
            <hr>

            @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
            @endif
            
            @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mt-2">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Hidden field to identify the form context -->
            <input type="hidden" name="form_type" id="form_type" value="new">
            <!-- Hidden field for branch_id -->
            <input type="hidden" name="branch_id" id="branch_id" value="{{ auth()->user()->branch_id ?? null }}">

            <!-- Department Category and Department Fields -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="department_category_id" class="form-label">
                            <i class="fas fa-layer-group"></i>
                            Department Category <span class="text-danger">*</span>
                        </label>
                        <select name="department_category_id" id="department_category_id" class="form-control" required>
                            <option value="">-- Select Department Category --</option>
                            @foreach($departmentCategories as $category)
                            <option value="{{ $category->department_category_id }}"
                                {{ old('department_category_id') == $category->department_category_id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                            @endforeach
                        </select>
                        @if($errors->has('department_category_id'))
                        <div class="error">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $errors->first('department_category_id')}}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-building"></i>
                            Select Department <span class="text-danger">*</span>
                        </label>
                        <select id="department_id" name="department_id" class="form-control">
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

            <!-- Checkbox for selecting existing course and New Course Name -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="select-existing-course-toggle" name="select_existing_course">
                        <label class="form-check-label" for="select-existing-course-toggle">
                            <i class="fas fa-check-circle me-2"></i>
                            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                            Select Existing Class
                            @else
                            Select Existing Course
                            @endif
                        </label>
                    </div>
                </div>

                <div class="col-sm-6" id="new-course-container">
                    <div class="mb-3">
                        <label for="new_course" class="form-label">
                            <i class="fas fa-tag"></i>
                            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                            New Class Name 
                            @else
                            New Course Name 
                            @endif
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="new_course" id="new_course" class="form-control" 
                               placeholder="Enter new course name" required>
                        @if($errors->has('new_course'))
                        <div class="error">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $errors->first('new_course')}}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Course Logo Upload -->
            <div class="row">
                <div class="col-sm-12" id="new-courselogo-container">
                    <div class="mb-3">
                        @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                        <label class="form-label">
                            <i class="fas fa-image"></i>
                            Class Logo <span class="text-muted">(optional)</span>
                        </label>
                        @else
                        <label class="form-label">
                            <i class="fas fa-image"></i>
                            Course Logo <span class="text-muted">(optional)</span>
                        </label>
                        @endif
                        
                        @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                        <div class="instructions">
                            <i class="fas fa-info-circle me-1"></i>
                            Upload a logo for your new class (JPEG, PNG, JPG, max 5MB)
                        </div>
                        @else
                        <div class="instructions">
                            <i class="fas fa-info-circle me-1"></i>
                            Upload a logo for your new course (JPEG, PNG, JPG, max 5MB)
                        </div>
                        @endif

                        <div class="file-input">
                            <label for="file-upload-logo" id="file-drag-logo">
                                <div id="start-logo">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <div>Select a file or drag here</div>
                                    <div id="notimage-logo" class="hidden">Please select an image</div>
                                    <span id="file-upload-btn-logo" class="btn mt-2">Choose File</span>
                                </div>
                                <img id="file-image-logo" src="#" alt="Preview" class="hidden mt-3">
                            </label>
                            <input id="file-upload-logo" type="file" name="finacp_merchant_sub_category_logo"
                                   accept="image/*">
                        </div>

                        @if($errors->has('finacp_merchant_sub_category_logo'))
                        <div class="error mt-2">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $errors->first('finacp_merchant_sub_category_logo')}}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Branches for New Course (visible by default) -->
            <div class="row">
                <div class="col-sm-12" id="new-branches-container">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-code-branch"></i>
                            Add Branches <span class="text-muted">(Optional)</span>
                        </label>
                        <div class="instructions">
                            <i class="fas fa-info-circle me-1"></i>
                            If no branches are added, the 
                            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                            class
                            @else
                            course
                            @endif 
                            name will be used as the branch. Add branches to create multiple specialization options.
                        </div>
                        <div id="new-branches-wrapper">
                            <div class="input-group mb-2 branch-item">
                                <input type="text" name="new_branches[]" class="form-control branch-input"
                                       placeholder="Enter branch name (e.g., CSE, IT, AI)">
                                <button type="button" class="btn btn-danger remove-new-branch-btn"
                                        style="display:none;">
                                    <i class="fas fa-trash-alt me-1"></i>Remove
                                </button>
                            </div>
                        </div>
                        <button type="button" id="add-new-branch-btn" class="btn btn-success btn-sm mt-2">
                            <i class="fas fa-plus-circle me-1"></i>+ Add Branch
                        </button>
                    </div>
                </div>
            </div>

            <!-- Existing Courses (hidden initially) -->
            <div class="row">
                <div class="col-sm-12" id="existing-courses-container" style="display:none;">
                    <div class="mb-3">
                        @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                        <label for="course" class="form-label">
                            <i class="fas fa-school"></i>
                            Select Class <span class="text-danger">*</span>
                        </label>
                        @else
                        <label for="course" class="form-label">
                            <i class="fas fa-book"></i>
                            Select Course <span class="text-danger">*</span>
                        </label>
                        @endif
                        <div class="instructions">
                            <i class="fas fa-info-circle me-1"></i>
                            Hold down the Ctrl (Windows) or Command (Mac) button to select multiple options
                        </div>
                        <select name="finacp_merchant_sub_category_type[]" id="course" multiple style="width: 100%;">
                            @foreach($allsubCategories as $category)
                            <option value="{{ $category->finacp_merchant_sub_category_type }}">
                                {{ $category->finacp_merchant_sub_category_type }}
                            </option>
                            @endforeach
                        </select>
                        @if($errors->has('finacp_merchant_sub_category_type'))
                        <div class="error">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $errors->first('finacp_merchant_sub_category_type')}}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Branches for Existing Courses (hidden initially) -->
            <div class="row">
                <div class="col-sm-12" id="existing-branches-container" style="display:none;">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-code-branch"></i>
                            Add Branches <span class="text-muted">(Optional)</span>
                        </label>
                        <div class="instructions">
                            <i class="fas fa-info-circle me-1"></i>
                            If no branches are added, the 
                            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                            class
                            @else
                            course
                            @endif 
                            name will be used as the branch. Add branches to create multiple specialization options.
                        </div>
                        <div id="existing-branches-wrapper">
                            <div class="input-group mb-2 branch-item">
                                <input type="text" name="existing_branches[]" class="form-control branch-input"
                                       placeholder="Enter branch name (e.g., CSE, IT, AI)">
                                <button type="button" class="btn btn-danger remove-existing-branch-btn"
                                        style="display:none;">
                                    <i class="fas fa-trash-alt me-1"></i>Remove
                                </button>
                            </div>
                        </div>
                        <button type="button" id="add-existing-branch-btn" class="btn btn-success btn-sm mt-2">
                            <i class="fas fa-plus-circle me-1"></i>+ Add Branch
                        </button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="btn-container">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Submit
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function loadScript(url) {
    return new Promise(function(resolve, reject) {
        const script = document.createElement('script');
        script.src = url;
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}

function loadCSS(url) {
    return new Promise(function(resolve, reject) {
        const link = document.createElement('link');
        link.href = url;
        link.rel = 'stylesheet';
        link.onload = resolve;
        link.onerror = reject;
        document.head.appendChild(link);
    });
}

async function initSelect2() {
    if (window.select2Initialized) return;
    window.select2Initialized = true;

    await loadScript('https://code.jquery.com/jquery-3.6.0.min.js');
    await loadCSS('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
    await loadScript('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js');

    // Initialize Select2 only for existing courses (will be initialized when shown)
    $(function() {
        $('#course').select2({
            placeholder: "Select or search courses",
            allowClear: true,
            width: '100%'
        });
    });
}

// Load departments based on selected category
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
            if (!data.success) {
                departmentSelect.innerHTML = '<option value="">Error loading departments</option>';
                return;
            }
            
            departmentSelect.innerHTML = '<option value="">-- Choose Department --</option>';
            
            if (data.departments && data.departments.length > 0) {
                data.departments.forEach(dept => {
                    const option = document.createElement('option');
                    option.value = dept.department_id;
                    option.textContent = `${dept.department}`;
                    departmentSelect.appendChild(option);
                });
            } else {
                departmentSelect.innerHTML = '<option value="">No departments found</option>';
            }
        })
        .catch((error) => {
            console.error('Error loading departments:', error);
            departmentSelect.innerHTML = '<option value="">Error loading departments</option>';
        });
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    initSelect2();

    // Department category change event
    const departmentCategorySelect = document.getElementById('department_category_id');
    departmentCategorySelect.addEventListener('change', function() {
        loadDepartmentsByCategory(this.value);
    });

    // Load departments if category is already selected
    const initialCategoryId = departmentCategorySelect.value;
    if (initialCategoryId) {
        loadDepartmentsByCategory(initialCategoryId);
    }

    // Toggle between new course (default) and existing course
    const checkbox = document.getElementById('select-existing-course-toggle');
    const newCourseContainer = document.getElementById('new-course-container');
    const newCourselogoContainer = document.getElementById('new-courselogo-container');
    const newBranchesContainer = document.getElementById('new-branches-container');
    const existingCoursesContainer = document.getElementById('existing-courses-container');
    const existingBranchesContainer = document.getElementById('existing-branches-container');
    const formType = document.getElementById('form_type');

    // Get form elements for required attribute manipulation
    const newCourseInput = document.getElementById('new_course');
    const newCourseLogoInput = document.getElementById('file-upload-logo');
    const existingCourseSelect = document.querySelector('#existing-courses-container select');

    // Set existing course select as optional initially
    if (existingCourseSelect) existingCourseSelect.required = false;

    checkbox.addEventListener('change', function() {
        if (this.checked) {
            // Show existing course fields
            existingCoursesContainer.style.display = 'block';
            existingBranchesContainer.style.display = 'block';
            
            // Hide new course fields
            newCourseContainer.style.display = 'none';
            newCourselogoContainer.style.display = 'none';
            newBranchesContainer.style.display = 'none';
            
            // Update form type
            formType.value = 'existing';
            
            // Update required attributes
            newCourseInput.required = false;
            newCourseLogoInput.required = false;
            if (existingCourseSelect) existingCourseSelect.required = true;
            
        } else {
            // Show new course fields
            newCourseContainer.style.display = 'block';
            newCourselogoContainer.style.display = 'block';
            newBranchesContainer.style.display = 'block';
            
            // Hide existing course fields
            existingCoursesContainer.style.display = 'none';
            existingBranchesContainer.style.display = 'none';
            
            // Update form type
            formType.value = 'new';
            
            // Update required attributes
            newCourseInput.required = true;
            newCourseLogoInput.required = false;
            if (existingCourseSelect) existingCourseSelect.required = false;
        }
    });

    // ✅ Dynamic branch add/remove logic for NEW COURSE
    const addNewBranchBtn = document.getElementById('add-new-branch-btn');
    const newBranchesWrapper = document.getElementById('new-branches-wrapper');

    addNewBranchBtn.addEventListener('click', function() {
        const newBranchDiv = document.createElement('div');
        newBranchDiv.classList.add('input-group', 'mb-2', 'branch-item');
        newBranchDiv.innerHTML = `
            <input type="text" name="new_branches[]" class="form-control branch-input" placeholder="Enter branch name">
            <button type="button" class="btn btn-danger remove-new-branch-btn">
                <i class="fas fa-trash-alt me-1"></i>Remove
            </button>
        `;
        newBranchesWrapper.appendChild(newBranchDiv);
        updateRemoveButtons('.remove-new-branch-btn');
    });

    // ✅ Dynamic branch add/remove logic for EXISTING COURSES
    const addExistingBranchBtn = document.getElementById('add-existing-branch-btn');
    const existingBranchesWrapper = document.getElementById('existing-branches-wrapper');

    addExistingBranchBtn.addEventListener('click', function() {
        const newBranchDiv = document.createElement('div');
        newBranchDiv.classList.add('input-group', 'mb-2', 'branch-item');
        newBranchDiv.innerHTML = `
            <input type="text" name="existing_branches[]" class="form-control branch-input" placeholder="Enter branch name">
            <button type="button" class="btn btn-danger remove-existing-branch-btn">
                <i class="fas fa-trash-alt me-1"></i>Remove
            </button>
        `;
        existingBranchesWrapper.appendChild(newBranchDiv);
        updateRemoveButtons('.remove-existing-branch-btn');
    });

    function updateRemoveButtons(selector) {
        const removeButtons = document.querySelectorAll(selector);
        removeButtons.forEach(btn => {
            const container = btn.closest('.branch-item').parentElement;
            btn.style.display = container.querySelectorAll('.branch-item').length > 1 ? 'inline-block' : 'none';
            btn.onclick = () => {
                btn.closest('.branch-item').remove();
                updateRemoveButtons(selector);
            };
        });
    }

    // Initialize remove buttons for both sections
    updateRemoveButtons('.remove-new-branch-btn');
    updateRemoveButtons('.remove-existing-branch-btn');

    // File upload handling
    const fileUploadLogo = document.getElementById('file-upload-logo');
    const fileImageLogo = document.getElementById('file-image-logo');
    const notImageLogo = document.getElementById('notimage-logo');
    const startLogo = document.getElementById('start-logo');

    if (fileUploadLogo) {
        fileUploadLogo.addEventListener('change', function() {
            const file = this.files[0];

            if (file) {
                if (!file.type.match('image.*')) {
                    notImageLogo.classList.remove('hidden');
                    startLogo.classList.add('hidden');
                    fileImageLogo.classList.add('hidden');
                    return;
                }

                notImageLogo.classList.add('hidden');

                const reader = new FileReader();

                reader.onload = function(e) {
                    fileImageLogo.src = e.target.result;
                    fileImageLogo.classList.remove('hidden');
                    startLogo.classList.add('hidden');
                };

                reader.readAsDataURL(file);
            }
        });
    }

    // Drag and drop functionality
    const fileDragLogo = document.getElementById('file-drag-logo');

    if (fileDragLogo) {
        fileDragLogo.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.add('hover');
        });

        fileDragLogo.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('hover');
        });

        fileDragLogo.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('hover');

            const files = e.dataTransfer.files;
            if (files.length && fileUploadLogo) {
                fileUploadLogo.files = files;
                const event = new Event('change');
                fileUploadLogo.dispatchEvent(event);
            }
        });
    }
});
</script>

@endsection