@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
body {
    background-color: #f8f9fa;
}

.container {
    margin-top: 50px;
    margin-bottom: 50px;
}

.form1 {
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: #f9f9f9;
}

.form-label {
    font-weight: bold;
}

.error {
    color: red;
    font-size: 0.875em;
}

.btn-container {
    width: 100%;
    text-align: center;
    margin-top: 20px;
}

.branch-list {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 10px;
    background: white;
    margin-top: 10px;
}

.branch-item {
    padding: 10px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    transition: all 0.2s;
    border-left: 4px solid transparent;
    position: relative;
}

.branch-item:hover {
    background-color: #f8f9fa;
}

.branch-item.selected {
    background-color: #e3f2fd;
    border-left: 4px solid #007bff;
}

.branch-item.auto-selected {
    background-color: #d4edda;
    border-left: 4px solid #28a745;
}

.branch-info {
    font-size: 0.9em;
    color: #666;
}

.loading {
    opacity: 0.6;
    pointer-events: none;
}

.branch-info-message {
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
    font-size: 0.9em;
}

.branch-info-message.info {
    background-color: #e7f3ff;
    border-left: 4px solid #17a2b8;
}

.branch-info-message.success {
    background-color: #d4edda;
    border-left: 4px solid #28a745;
}

.branch-info-message.warning {
    background-color: #fff3cd;
    border-left: 4px solid #ffc107;
}

.validation-message {
    font-size: 0.875em;
    margin-top: 5px;
}

.validation-message.error {
    color: #dc3545;
}

.validation-message.success {
    color: #28a745;
}

.form-control:invalid {
    border-color: #dc3545;
}

/* Toast notification */
.branch-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1050;
    min-width: 250px;
    display: none;
}

/* Auto-select indicator */
.auto-select-indicator {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: #28a745;
    color: white;
    padding: 2px 8px;
    border-radius: 3px;
    font-size: 0.8em;
}

/* Selected branch display */
.selected-branch-display {
    padding: 10px;
    margin-top: 10px;
    background-color: #f8f9fa;
    border-radius: 5px;
    border-left: 4px solid #007bff;
}
</style>

<div class="container-fluid">
    <div class="form1">
        <form id="course-basic-form" action="{{ route('save.course.basic.details') }}" method="POST">
            @csrf
            <h3 class="mb-4">Add
                @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                    Class
                                    @else
                                  Course
                                    @endif
                  Basic Details</h3>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Department Category Selection -->
                <div class="col-sm-6 mb-3">
                    <label for="department_category_id" class="form-label">Select Department Category</label>
                    <select id="department_category_id" name="department_category_id" class="form-control" required>
                        <option value="">-- Select Department Category --</option>
                        @foreach($departmentCategories as $category)
                            <option value="{{ $category->department_category_id }}" {{ old('department_category_id') == $category->department_category_id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Department Selection -->
                <div class="col-sm-6 mb-3">
                    <label class="form-label fw-bold">Select Department <span class="text-danger">*</span></label>
                    <select id="department_id" name="department_id" class="form-control" required>
                        <option value="">-- Choose Department --</option>
                    </select>
                    @if($errors->has('department_id'))
                    <div class="alert alert-danger" style="color:red">
                        {{ $errors->first('department_id')}}
                    </div>
                    @endif
                </div>

                <!-- Course Type Selection -->
                <div class="col-sm-6 mb-3">
                    <label for="course_type" class="form-label">Select
                         @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                    Class
                                    @else
                                    Course
                                    @endif
                        </label>
                    <select id="course_type" name="course_type" class="form-control" required disabled>
                        <option value="">-- Select
                             @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                    Class
                                    @else
                                    Course
                                    @endif
                             Type --</option>
                    </select>
                </div>

                <!-- Branch Selection Section -->
                <div class="col-sm-12 mb-3 d-none" id="branches-container">
                    <label class="form-label">Select
                         @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                    Class
                                    @else
                                    Course
                                    @endif
                        </label>
                    
                    <!-- Branch Information Message -->
                    <div id="branch-info-message" class="branch-info-message">
                        <!-- Dynamic message will appear here -->
                    </div>
                    
                    <!-- Branches List - ALWAYS VISIBLE -->
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

                <!-- Mode of Course -->
                <div class="col-sm-6 mb-3">
                    <label for="mode_of_course">Mode of
                         @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                    Class
                                    @else
                                    Course
                                    @endif
                        </label>
                    <select name="mode_of_course" class="form-control" required>
                        <option value="">-- Select --</option>
                        <option value="Online" {{ old('mode_of_course') == 'Online' ? 'selected' : '' }}>Online</option>
                        <option value="Offline" {{ old('mode_of_course') == 'Offline' ? 'selected' : '' }}>Offline</option>
                        <option value="Hybrid" {{ old('mode_of_course') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                </div>

                <!-- Course Mode Type -->
                <div class="col-sm-6 mb-3">
                    <label for="mode_type">
                        @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                    Class
                                    @else
                                    Course
                                    @endif
                         Mode Type</label>
                    <select name="mode_type" class="form-control" required>
                        <option value="">-- Select --</option>
                        <option value="part_time" {{ old('mode_type') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                        <option value="full_time" {{ old('mode_type') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                        <option value="hybrid" {{ old('mode_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                </div>

                <!-- Course Duration -->
                <div class="col-sm-6 mb-3">
                    <label>
                        @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                    Class
                                    @else
                                    Course
                                    @endif
                        Duration</label>
                    <select id="course_duration_type" name="course_duration" class="form-control" required>
                        <option value="">-- Select --</option>
                        <option value="Hourly" {{ old('course_duration') == 'Hourly' ? 'selected' : '' }}>Hourly</option>
                        <option value="Weekly" {{ old('course_duration') == 'Weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="Monthly" {{ old('course_duration') == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="Quarterly" {{ old('course_duration') == 'Quarterly' ? 'selected' : '' }}>Quarterly</option>
                        <option value="Half_yearly" {{ old('course_duration') == 'Half_yearly' ? 'selected' : '' }}>Half yearly</option>
                        <option value="Yearly" {{ old('course_duration') == 'Yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                </div>

                <!-- Course Length -->
                <div class="col-sm-6">
                    <label id="courseLengthLabel">
                        @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                    Class
                                    @else
                                    Course
                                    @endif
                         Length</label>
                    <input type="number" id="course_duration_term" name="course_length" class="form-control" 
                           placeholder="e.g. 3" value="{{ old('course_length') }}" required />
                </div>

                <!-- Number of Semesters/Terms -->
                <div class="col-sm-6" style="visibility: hidden;">
                    <label for="semester_count">Number of Semesters/Terms</label>
                    <input type="number" id="semester_count" name="semester_count" class="form-control mb-2" 
                           placeholder="e.g. 2" min="1" max="12" value="{{ old('semester_count') }}" required>
                    <input type="hidden" id="semester_list" name="semester_list" value="{{ old('semester_list') }}">
                    <small class="text-muted" id="semester-preview"></small>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="col-sm-12 mt-4">
                <button type="submit" class="btn btn-primary btn-lg" id="submit-btn">
                    <i class="fas fa-save me-2"></i>Submit 
                </button>
                <div id="form-validation" class="validation-message mt-2">
                    <!-- Form validation messages will appear here -->
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
            Selection</strong>
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
        $('#course_type').prop('disabled', true).html('<option value="">-- Select Course Type --</option>');
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
                        Course: ${branch.course_type}
                    </div>
                </div>
            `;
        });
        
        $('#branches-list').html(branchesHtml);
        
        // Show appropriate message
        if (branches.length === 1) {
          
        } else {
            $('#branch-info-message').html(`
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Found ${branches.length}. Please select one:
                </div>
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
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <strong>"${branch.sub_type}"</strong> has been auto-selected.
            </div>
        `).addClass('success').show();
        
        showToast('Single record found and auto-selected', 'success');
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
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                Branch <strong>"${subType}"</strong> selected.
            </div>
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
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Please select a branch to continue
                </div>
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
            $('#semester-preview').text('');
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
            preview.text('Terms: ' + terms.join(', '));
        } else {
            semesterInput.val('');
            preview.text('');
        }
    }

    // Course duration label update
    $('#course_duration_type').change(function() {
        const selectedText = this.options[this.selectedIndex].text;
        const label = $('#courseLengthLabel');

        if (selectedText && selectedText !== "-- Select --") {
            label.text(`Course Length (${selectedText})`);
        } else {
            label.text('Course Length');
        }
        
        updateSemesterList();
    });
});
</script>
@endsection