@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ isset($role) ? 'Edit' : 'Create' }} Role for Employee</h3>
                </div>
                
                <form method="POST" action="{{ isset($role) ? route('roles.update', $role->id) : route('roles.store') }}" id="roleForm">
                    @csrf
                    @if(isset($role))
                        @method('PUT')
                    @endif

                    <div class="card-body">
                        <div class="row">

                            <!-- Employee -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Employee <span class="text-danger">*</span></label>
                                    <select name="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required>
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->employee_id }}" 
                                                {{ old('employee_id', $role->employee_id ?? '') == $employee->employee_id ? 'selected' : '' }}>
                                                {{ $employee->name }} ({{ $employee->employee_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Type -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Role Type <span class="text-danger">*</span></label>
                                    <select name="type" class="form-control @error('type') is-invalid @enderror" id="roleTypeSelect" required>
                                        <option value="">Select Type</option>
                                        <option value="counsellor" {{ old('type', $role->type ?? '') == 'counsellor' ? 'selected' : '' }}>Counsellor</option>
                                        <option value="agent" {{ old('type', $role->type ?? '') == 'agent' ? 'selected' : '' }}>Agent</option>
                                        <option value="interviewer" {{ old('type', $role->type ?? '') == 'interviewer' ? 'selected' : '' }}>Interviewer</option>
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Department -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Department <span class="text-danger">*</span></label>
                                    <select name="department_id" class="form-control @error('department_id') is-invalid @enderror" 
                                            id="departmentSelect" required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->department_id }}" 
                                                {{ old('department_id', $role->department_id ?? '') == $department->department_id ? 'selected' : '' }}>
                                                {{ $department->department }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="active" {{ old('status', $role->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="leave" {{ old('status', $role->status ?? '') == 'leave' ? 'selected' : '' }}>Leave</option>
                                        <option value="detained" {{ old('status', $role->status ?? '') == 'detained' ? 'selected' : '' }}>Detained</option>
                                        <option value="inactive" {{ old('status', $role->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="terminated" {{ old('status', $role->status ?? '') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Classes Selection - Only visible for Counsellor type -->
                        <div class="row mt-4" id="classesSection" style="{{ (old('type', $role->type ?? '') == 'counsellor') ? '' : 'display: none;' }}">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            <i class="fas fa-graduation-cap mr-2"></i>
                                            Assign Classes
                                            <small class="text-muted ml-2">(Only for Counsellor role)</small>
                                        </h5>
                                        <span id="selectedClassesBadge" class="badge badge-primary" style="display: none;">0 selected</span>
                                    </div>
                                    <div class="card-body">
                                        <!-- Department Warning if no department selected -->
                                        <div id="departmentWarning" class="alert alert-info" style="{{ old('department_id', $role->department_id ?? '') ? 'display: none;' : '' }}">
                                            <i class="fas fa-info-circle"></i> Please select a department first to load classes.
                                        </div>

                                        <!-- Loading Indicator -->
                                        <div id="loadingIndicator" class="text-center py-4" style="display: none;">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                            <p class="mt-2 text-muted">Loading classes...</p>
                                        </div>

                                        <!-- Classes Grid -->
                                        <div id="classesGrid" class="row">
                                            @if(isset($selectedClasses) && $selectedClasses->count() > 0)
                                                @foreach($selectedClasses as $class)
                                                    <div class="col-md-3 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" name="class_ids[]" 
                                                                   value="{{ $class->id }}" 
                                                                   class="custom-control-input class-checkbox"
                                                                   id="class_{{ $class->id }}" 
                                                                   checked>
                                                            <label class="custom-control-label" for="class_{{ $class->id }}">
                                                                {{ $class->name }} ({{ $class->code }})
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="col-12 text-center text-muted" id="noClassesMessage">
                                                    <i class="fas fa-graduation-cap fa-2x mb-2"></i>
                                                    <p>Select a department to load classes</p>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Select All Checkbox -->
                                        <div class="row mt-3" id="selectAllContainer" style="display: none;">
                                            <div class="col-12">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="selectAllCheckbox">
                                                    <label class="custom-control-label" for="selectAllCheckbox">
                                                        <strong>Select All Classes</strong>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ isset($role) ? 'Update' : 'Save' }}
                        </button>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


<script>
    // CSRF Token
    window.csrfToken = '{{ csrf_token() }}';

    // Selected classes (for edit mode)
    @if(isset($selectedClasses) && $selectedClasses->count() > 0)
        window.selectedClasses = {!! json_encode($selectedClasses->pluck('id')->map(function($id) { 
            return (string)$id; 
        })->toArray()) !!};
    @else
        window.selectedClasses = [];
    @endif

    document.addEventListener('DOMContentLoaded', function() {
        // DOM Elements
        const roleTypeSelect = document.getElementById('roleTypeSelect');
        const departmentSelect = document.getElementById('departmentSelect');
        const classesSection = document.getElementById('classesSection');
        const classesGrid = document.getElementById('classesGrid');
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const selectAllContainer = document.getElementById('selectAllContainer');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const departmentWarning = document.getElementById('departmentWarning');
        const selectedClassesBadge = document.getElementById('selectedClassesBadge');

        // Function to toggle classes section based on role type
        function toggleClassesSection() {
            if (roleTypeSelect.value === 'counsellor') {
                classesSection.style.display = 'block';
                // If department is selected, load classes
                if (departmentSelect.value) {
                    loadClassesForDepartment(departmentSelect.value);
                } else {
                    // Show department warning
                    if (departmentWarning) departmentWarning.style.display = 'block';
                    // Clear classes grid
                    classesGrid.innerHTML = `
                        <div class="col-12 text-center text-muted">
                            <i class="fas fa-graduation-cap fa-2x mb-2"></i>
                            <p>Select a department to load classes</p>
                        </div>
                    `;
                }
            } else {
                classesSection.style.display = 'none';
                // Clear any selected classes when not counsellor
                clearSelectedClasses();
            }
        }

        // Function to clear selected classes
        function clearSelectedClasses() {
            const checkboxes = document.querySelectorAll('.class-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            if (selectedClassesBadge) selectedClassesBadge.style.display = 'none';
        }

        // Toggle classes section when role type changes
        if (roleTypeSelect) {
            roleTypeSelect.addEventListener('change', toggleClassesSection);
        }

        // Load classes when department changes (only if counsellor is selected)
        if (departmentSelect) {
            departmentSelect.addEventListener('change', function() {
                const departmentId = this.value;
                
                // Hide department warning if department is selected
                if (departmentWarning) {
                    departmentWarning.style.display = departmentId ? 'none' : 'block';
                }
                
                // Only load classes if counsellor is selected
                if (roleTypeSelect.value === 'counsellor' && departmentId) {
                    loadClassesForDepartment(departmentId);
                } else if (!departmentId) {
                    // Clear classes grid if no department selected
                    classesGrid.innerHTML = `
                        <div class="col-12 text-center text-muted">
                            <i class="fas fa-graduation-cap fa-2x mb-2"></i>
                            <p>Select a department to load classes</p>
                        </div>
                    `;
                    if (selectAllContainer) selectAllContainer.style.display = 'none';
                    if (selectedClassesBadge) selectedClassesBadge.style.display = 'none';
                }
            });
        }

        // Select All functionality
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.class-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectedCount();
            });
        }

        // Load Classes for Department
        function loadClassesForDepartment(departmentId) {
            // Show loading indicator
            if (loadingIndicator) loadingIndicator.style.display = 'block';
            if (classesGrid) classesGrid.innerHTML = '';
            if (selectAllContainer) selectAllContainer.style.display = 'none';
            if (departmentWarning) departmentWarning.style.display = 'none';
            
            // Fetch classes from API
            fetch(`/ajax/course-types-by-department?department_id=${departmentId}`, {
                headers: {
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Hide loading indicator
                if (loadingIndicator) loadingIndicator.style.display = 'none';
                
                console.log('API Response:', data);
                
                // Process the response data
                let classes = [];
                
                if (data.status === 'success' && data.courses) {
                    classes = data.courses;
                } else if (data.status === 'success' && data.data) {
                    classes = data.data;
                } else if (Array.isArray(data)) {
                    classes = data;
                } else if (data.courses && Array.isArray(data.courses)) {
                    classes = data.courses;
                }
                
                if (classes.length > 0) {
                    renderClassesGrid(classes);
                    if (selectAllContainer) selectAllContainer.style.display = 'block';
                } else {
                    // No classes available
                    classesGrid.innerHTML = `
                        <div class="col-12 text-center py-4">
                            <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No classes available for this department</p>
                        </div>
                    `;
                    if (selectAllContainer) selectAllContainer.style.display = 'none';
                }
                
                updateSelectedCount();
            })
            .catch(error => {
                console.error('Error loading classes:', error);
                
                // Hide loading indicator
                if (loadingIndicator) loadingIndicator.style.display = 'none';
                
                // Show error message
                classesGrid.innerHTML = `
                    <div class="col-12 text-center py-4">
                        <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
                        <p class="text-danger">Failed to load classes. Please try again.</p>
                        <button class="btn btn-sm btn-outline-primary mt-2" onclick="loadClassesForDepartment(${departmentId})">
                            <i class="fas fa-sync-alt"></i> Retry
                        </button>
                    </div>
                `;
                
                if (selectAllContainer) selectAllContainer.style.display = 'none';
            });
        }

        // Render Classes Grid
        function renderClassesGrid(classes) {
            let html = '';
            
            classes.forEach((course, index) => {
                // Debug: Log each course to see its structure
                console.log(`Course ${index + 1}:`, course);
                
                // Get the correct field names based on your API response
                const courseId = course.finacp_merchant_sub_category_id || 
                                course.fincap_program_category_id || 
                                course.id || 
                                course.category_id ||
                                course.course_id ||
                                course.value;
                                
                const courseName = course.finacp_merchant_sub_category_type || 
                                  course.name || 
                                  course.category_name || 
                                  course.title || 
                                  course.course_name ||
                                  course.text ||
                                  'Unknown Class';
                
                // Check if this course is selected (for edit mode)
                const isSelected = window.selectedClasses && 
                                  window.selectedClasses.some(id => String(id) === String(courseId));
                
                if (courseId) {
                    html += `
                        <div class="col-md-3 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="class_ids[]" 
                                       value="${courseId}" 
                                       class="custom-control-input class-checkbox"
                                       id="class_${courseId}" ${isSelected ? 'checked' : ''}>
                                <label class="custom-control-label" for="class_${courseId}">
                                    ${courseName}
                                </label>
                            </div>
                        </div>
                    `;
                }
            });
            
            if (html) {
                classesGrid.innerHTML = html;
            } else {
                classesGrid.innerHTML = `
                    <div class="col-12 text-center py-4">
                        <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
                        <p class="text-warning">No valid class data found</p>
                    </div>
                `;
            }

            // Add event listeners to new checkboxes
            document.querySelectorAll('.class-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            updateSelectedCount();
        }

        // Update selected count and select all state
        function updateSelectedCount() {
            const checkboxes = document.querySelectorAll('.class-checkbox');
            const checkedCheckboxes = document.querySelectorAll('.class-checkbox:checked');
            const count = checkedCheckboxes.length;
            
            // Update select all checkbox state
            if (selectAllCheckbox) {
                if (checkboxes.length > 0) {
                    selectAllCheckbox.checked = checkboxes.length === count;
                    selectAllCheckbox.indeterminate = count > 0 && count < checkboxes.length;
                } else {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                }
            }
            
            // Update selected classes badge
            if (selectedClassesBadge) {
                if (count > 0) {
                    selectedClassesBadge.textContent = `${count} selected`;
                    selectedClassesBadge.style.display = 'inline-block';
                } else {
                    selectedClassesBadge.style.display = 'none';
                }
            }
        }

        // Form validation before submit
        document.getElementById('roleForm').addEventListener('submit', function(e) {
            const roleType = roleTypeSelect.value;
            const departmentId = departmentSelect.value;
            
            if (!roleType) {
                e.preventDefault();
                alert('Please select a role type');
                return false;
            }
            
            if (!departmentId) {
                e.preventDefault();
                alert('Please select a department');
                return false;
            }
            
            // Only validate classes if role type is counsellor
            if (roleType === 'counsellor') {
                const selectedClasses = document.querySelectorAll('.class-checkbox:checked');
                if (selectedClasses.length === 0) {
                    e.preventDefault();
                    if (confirm('No classes selected. Are you sure you want to continue?')) {
                        return true;
                    }
                    return false;
                }
            }
            
            return true;
        });

        // Expose loadClassesForDepartment to global scope for the retry button
        window.loadClassesForDepartment = loadClassesForDepartment;
    });
</script>

<style>
    /* Custom styles for the role form */
    .custom-checkbox {
        padding: 8px 12px;
        border-radius: 6px;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    
    .custom-checkbox:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    .class-checkbox:checked + label {
        color: #007bff;
        font-weight: 500;
    }
    
    .custom-checkbox:has(.class-checkbox:checked) {
        background-color: #f0f7ff;
        border-color: #007bff;
    }
    
    #selectedClassesBadge {
        padding: 5px 10px;
        font-size: 0.9rem;
    }
    
    .loading-state {
        text-align: center;
        padding: 40px;
    }
    
    .loading-state .spinner-border {
        width: 3rem;
        height: 3rem;
    }
    
    /* Animation for loading */
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    .loading-state p {
        animation: pulse 1.5s infinite;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .col-md-3 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
    
    @media (max-width: 576px) {
        .col-md-3 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
    
    /* Department warning styling */
    .alert-info {
        border-left: 4px solid #17a2b8;
    }
    
    /* Classes section transition */
    #classesSection {
        transition: all 0.3s ease;
    }
</style>o