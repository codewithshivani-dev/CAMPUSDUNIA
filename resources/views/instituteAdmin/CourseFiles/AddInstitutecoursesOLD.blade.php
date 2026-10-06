@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
/* Your existing CSS styles here */
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
    background: #fff;
    border-radius: 7px;
    border: 3px solid #eee;
    transition: all .2s ease;
    user-select: none;
    cursor: pointer;
}

.file-input label:hover {
    border-color: #007bff;
}

.file-input label.hover {
    border: 3px solid #007bff;
    box-shadow: inset 0 0 0 6px #eee;
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
    color: #6c757d;
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
    height: 150px;
    max-width: 180px;
    border-radius: 5px;
    border: 1px solid #ddd;
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
    background: linear-gradient(to right, darken(#007bff, 8%) 0%, #007bff 50%);
    border-radius: 4px;
}

.file-input .progress[value]::-moz-progress-bar {
    background: linear-gradient(to right, darken(#007bff, 8%) 0%, #007bff 50%);
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
    border-radius: .2rem;
    outline: none;
    padding: 0 1rem;
    height: 36px;
    line-height: 36px;
    color: #fff;
    transition: all 0.2s ease-in-out;
    box-sizing: border-box;
    background: #007bff;
    border-color: #007bff;
    cursor: pointer;
}

/* Additional Styles */
.container {
    margin-top: 30px;
    margin-bottom: 30px;
}

.form1 {
    padding: 25px;
    border: 1px solid #ddd;
    border-radius: 10px;
    background-color: #fff;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
}

.form-label {
    font-weight: bold;
    font-size: larger;
    color: #2c3e50;
}

.btn-container {
    text-align: center;
    margin-top: 25px;
}

.error {
    color: red;
    font-size: 0.875em;
}

.preview-image {
    max-width: 100px;
    margin-bottom: 10px;
    border-radius: 5px;
}

.fileDiv {
    padding-left: 0px !important;
    padding-right: 0px !important;
}

.select2-container--default .select2-selection--multiple {
    padding: 5px;
    border-radius: 4px;
    border: 1px solid #ced4da;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #e4e4e4;
    border: 1px solid #aaa;
    border-radius: 4px;
    box-sizing: border-box;
    display: inline-block;
    margin-left: 3px;
    margin-top: -3px;
    padding: 0;
    padding-left: 35px;
    position: relative;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    vertical-align: bottom;
    white-space: nowrap;
    color: royalblue;
}

.instructions {
    font-size: 0.9rem;
    color: #6c757d;
    margin-top: 5px;
}

.preview-container {
    text-align: center;
    margin-top: 15px;
}

.logo-preview {
    max-width: 120px;
    max-height: 120px;
    border-radius: 8px;
    border: 1px solid #ddd;
    padding: 4px;
    background: #f8f9fa;
}

.branch-item {
    margin-bottom: 10px;
}

.remove-branch-btn {
    margin-left: 10px;
}
</style>

<div class="container my-5">
    <div class="form1">
        <form id="file-upload-form" action="{{ route('AddInstitutecourses') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
            <h3 class="mb-4">Add Class</h3>
            @else
            <h3 class="mb-4">Add Course</h3>
            @endif
            
            <hr>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
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

            <!-- Department Category Field -->
            <div class="mb-4">
                <label for="department_category_id" class="form-label">Department Category</label>
                <select name="department_category_id" id="department_category_id" class="form-control" required>
                    <option value="">Select Department Category</option>
                    @foreach($departmentCategories as $category)
                    <option value="{{ $category->department_category_id }}"
                        {{ old('department_category_id') == $category->department_category_id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                    @endforeach
                </select>
                @if($errors->has('department_category_id'))
                <div class="alert alert-danger" style="color:red">
                    {{ $errors->first('department_category_id')}}
                </div>
                @endif
            </div>

            <!-- Department Field - Loaded based on category -->
            <div class="mb-4">
                <label class="form-label fw-bold">Select Department <span class="text-danger">*</span></label>
                <select id="department_id" name="department_id" class="form-control">
                    <option value="">-- Choose Department --</option>
                </select>
                @if($errors->has('department_id'))
                <div class="alert alert-danger" style="color:red">
                    {{ $errors->first('department_id')}}
                </div>
                @endif
            </div>

            <!-- Checkbox for selecting existing course -->
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="select-existing-course-toggle" name="select_existing_course">
                <label class="form-check-label" for="select-existing-course-toggle">
                    @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                    Select Existing Class
                    @else
                     Select Existing Course
                    @endif
                   
                </label>
            </div>

            <!-- New Course Fields (visible by default) -->
            <div class="mb-4" id="new-course-container">
                <label for="new_course" class="form-label">
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
                <div class="alert alert-danger" style="color:red">
                    {{ $errors->first('new_course')}}
                </div>
                @endif
            </div>

            <div class="choose col-sm-12 fileDiv" id="new-courselogo-container" style="flex-wrap: wrap;">
                <div class="mb-4 w-100 fileDiv">
                    @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                       <label class="form-label">Class Logo <span class="text-danger">(optional)</span></label>
                    @else
                       <label class="form-label">Course Logo <span class="text-danger">(optional)</span></label>
                    @endif
                    
                   @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                       <div class="instructions">Upload a logo for your new class (JPEG, PNG, JPG, max 5MB)</div>
                    @else
                       <div class="instructions">Upload a logo for your new course (JPEG, PNG, JPG, max 5MB)</div>
                    @endif
                    

                    <div class="file-input">
                        <label for="file-upload-logo" id="file-drag-logo">
                            <div id="start-logo">
                                <i class="fa fa-cloud-upload-alt" aria-hidden="true"></i>
                                <div>Select a file or drag here</div>
                                <div id="notimage-logo" class="hidden">Please select an image</div>
                                <span id="file-upload-btn-logo" class="btn btn-primary mt-2">Choose File</span>
                            </div>
                            <img id="file-image-logo" src="#" alt="Preview" class="hidden mt-3">
                        </label>
                        <input id="file-upload-logo" type="file" name="finacp_merchant_sub_category_logo"
                               accept="image/*">
                    </div>

                    @if($errors->has('finacp_merchant_sub_category_logo'))
                    <div class="error mt-2">{{ $errors->first('finacp_merchant_sub_category_logo')}}</div>
                    @endif
                </div>
            </div>

            <!-- Branches for New Course (visible by default) -->
            <div class="mb-4" id="new-branches-container">
                <label class="form-label">Add Branches (Optional)</label>
                <div class="instructions">If no branches are added, the @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                   Class
                @else
                   Course
                @endif name will be used as the branch. Add
                    branches to create multiple specialization options.</div>
                <div id="new-branches-wrapper">
                    <div class="input-group mb-2 branch-item">
                        <input type="text" name="new_branches[]" class="form-control branch-input"
                               placeholder="Enter branch name (e.g., CSE, IT, AI)">
                        <button type="button" class="btn btn-danger remove-new-branch-btn"
                                style="display:none;">Remove</button>
                    </div>
                </div>
                <button type="button" id="add-new-branch-btn" class="btn btn-success btn-sm mt-2">+ Add Branch</button>
            </div>

            <!-- Existing Courses (hidden initially) -->
            <div class="mb-4" id="existing-courses-container" style="display:none;">
                    @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                       <label for="course" class="form-label">Select Class</label>
                    @else
                       <label for="course" class="form-label">Select Course</label>
                    @endif
                <div class="instructions">Hold down the Ctrl (Windows) or Command (Mac) button to select multiple
                    options</div>
                <select name="finacp_merchant_sub_category_type[]" id="course" multiple style="width: 100%;">
                    @foreach($allsubCategories as $category)
                    <option value="{{ $category->finacp_merchant_sub_category_type }}">
                        {{ $category->finacp_merchant_sub_category_type }}
                    </option>
                    @endforeach
                </select>
                @if($errors->has('finacp_merchant_sub_category_type'))
                <div class="alert alert-danger" style="color:red">
                    {{ $errors->first('finacp_merchant_sub_category_type')}}
                </div>
                @endif
            </div>

            <!-- Branches for Existing Courses (hidden initially) -->
            <div class="mb-4" id="existing-branches-container" style="display:none;">
                <label class="form-label">Add Branches (Optional)</label>
                <div class="instructions">If no branches are added, the
                @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                   Class
                @else
                   Course
                @endif 
                    name will be used as the branch. Add
                    branches to create multiple specialization options.</div>
                <div id="existing-branches-wrapper">
                    <div class="input-group mb-2 branch-item">
                        <input type="text" name="existing_branches[]" class="form-control branch-input"
                               placeholder="Enter branch name (e.g., CSE, IT, AI)">
                        <button type="button" class="btn btn-danger remove-existing-branch-btn"
                                style="display:none;">Remove</button>
                    </div>
                </div>
                <button type="button" id="add-existing-branch-btn" class="btn btn-success btn-sm mt-2">+ Add
                    Branch</button>
            </div>

            <div class="btn-container">
                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">Submit</button>
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
    existingCourseSelect.required = false;

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
            existingCourseSelect.required = true;
            
        } else {
            // Show new course fields
            newCourseContainer.style.display = 'block';
            newCourselogoContainer.style.display = 'flex';
            newBranchesContainer.style.display = 'block';
            
            // Hide existing course fields
            existingCoursesContainer.style.display = 'none';
            existingBranchesContainer.style.display = 'none';
            
            // Update form type
            formType.value = 'new';
            
            // Update required attributes
            newCourseInput.required = true;
            newCourseLogoInput.required = true;
            existingCourseSelect.required = false;
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
            <button type="button" class="btn btn-danger remove-new-branch-btn">Remove</button>
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
            <button type="button" class="btn btn-danger remove-existing-branch-btn">Remove</button>
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

    // Drag and drop functionality
    const fileDragLogo = document.getElementById('file-drag-logo');

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
        if (files.length) {
            fileUploadLogo.files = files;
            const event = new Event('change');
            fileUploadLogo.dispatchEvent(event);
        }
    });
});
</script>

@endsection