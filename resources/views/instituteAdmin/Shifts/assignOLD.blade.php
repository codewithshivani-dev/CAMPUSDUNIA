@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet" />
<style>
.step-section {
    border-left: 3px solid #007bff;
    padding-left: 15px;
    margin-bottom: 20px;
}

.assignment-option {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.assignment-option:hover {
    border-color: #007bff;
    transform: translateY(-2px);
}

.assignment-option.selected {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.select2-container--bootstrap-5 .select2-selection {
    min-height: 38px;
}
</style>

<div class="container-fluid mt-4">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
            <h4 class="mb-0"><i class="bi bi-clock-history"></i> Assign Shift</h4>
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

            <form action="{{ route('shifts.assign') }}" method="POST" id="assignShiftForm">
                @csrf

                <!-- Step 1: Select Multiple Shifts -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-primary mb-3">Step 1: Select Shifts <span class="text-danger">*</span></h5>
                        <label class="form-label fw-bold">Select Shifts <span class="text-danger">*</span></label>
                        <select name="shift_ids[]" class="form-control select2-multiple" multiple="multiple" required id="shiftSelect">
                            <option value="">-- Select Shifts --</option>
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
                </div>

                <!-- Step 2: Select Single Category -->
                <div class="row mb-4" id="categorySection" style="display: none;">
                    <div class="col-12">
                        <h5 class="text-primary mb-3">Step 2: Select Department Category <span class="text-danger">*</span></h5>
                        <label class="form-label fw-bold">Select Department Category <span class="text-danger">*</span></label>
                        <select id="department_category_id" name="department_category_id" class="form-control select2-single" required>
                            <option value="">-- Select Category --</option>
                            @foreach($departmentCategories as $category)
                            <option value="{{ $category->department_category_id }}">
                                {{ $category->category_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Step 3: Select Multiple Departments -->
                <div class="row mb-4" id="departmentSection" style="display: none;">
                    <div class="col-12">
                        <h5 class="text-primary mb-3">Step 3: Select Departments <span class="text-danger">*</span></h5>
                        <label class="form-label fw-bold">Select Departments <span class="text-danger">*</span></label>
                        <select id="department_ids" name="department_ids[]" class="form-control select2-multiple" multiple="multiple"></select>
                        <small class="text-muted">You can select multiple departments</small>
                    </div>
                </div>

                <!-- Step 4: Assignment Type -->
                <div class="row mb-4" id="assignmentTypeSection" style="display: none;">
                    <div class="col-12">
                        <h5 class="text-primary mb-3">Step 4: Choose Assignment Type <span class="text-danger">*</span>
                        </h5>
                        <label class="form-label fw-bold">Assign To <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card assignment-option">
                                    <div class="card-body text-center">
                                        <input type="radio" name="assign_to_type" value="whole_department"
                                            id="whole_department" class="d-none">
                                        <label for="whole_department" class="cursor-pointer">
                                            <i class="bi bi-building display-4 text-primary"></i>
                                            <h6 class="mt-2">Whole Department</h6>
                                            <small class="text-muted">Assign to department only</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card assignment-option">
                                    <div class="card-body text-center">
                                        <input type="radio" name="assign_to_type" value="selected_employees"
                                            id="selected_employees" class="d-none">
                                        <label for="selected_employees" class="cursor-pointer">
                                            <i class="bi bi-people display-4 text-success"></i>
                                            <h6 class="mt-2">Select Employees</h6>
                                            <small class="text-muted">Choose specific employees</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Employee Selection -->
                <div class="row mb-4" id="employeeSelectionSection" style="display: none;">
                    <div class="col-12">
                        <h5 class="text-primary mb-3">Step 5: Select Employees <span class="text-danger">*</span></h5>
                        <label class="form-label fw-bold">Select Employees <span class="text-danger">*</span></label>
                        <div class="border rounded p-3 bg-light max-height-300" id="employeeListContainer">
                            <div id="employeeList">
                                <p class="text-muted mb-0">Select departments first to see employees...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row mt-4" id="submitSection" style="display: none;">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success px-5 py-2 shadow-sm rounded-pill">
                            <i class="bi bi-check2-circle me-1"></i> Assign Shift
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
    // Initialize Select2
    $('.select2-single').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select an option...',
        allowClear: true
    });

    $('.select2-multiple').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select options...',
        allowClear: true,
        multiple: true
    });

    const categorySection = $('#categorySection');
    const departmentSection = $('#departmentSection');
    const assignmentTypeSection = $('#assignmentTypeSection');
    const employeeSelectionSection = $('#employeeSelectionSection');
    const submitSection = $('#submitSection');
    const shiftSelect = $('#shiftSelect');
    const departmentCategorySelect = $('#department_category_id');
    const departmentSelect = $('#department_ids');
    const employeeList = $('#employeeList');

    // Step 1: Shift Selection
    shiftSelect.on('change', function() {
        const selectedShifts = $(this).val();
        if (selectedShifts && selectedShifts.length > 0) {
            categorySection.show();
        } else {
            resetSections();
        }
    });

    // Step 2: Category Selection (Single)
    departmentCategorySelect.on('change', function() {
        const categoryId = $(this).val();
        if (categoryId) {
            loadDepartmentsByCategory(categoryId);
            departmentSection.show();
        } else {
            resetSections([departmentSection, assignmentTypeSection, employeeSelectionSection, submitSection]);
        }
    });

    // Step 3: Department Selection
    departmentSelect.on('change', function() {
        const selectedDepartments = $(this).val();
        if (selectedDepartments && selectedDepartments.length > 0) {
            assignmentTypeSection.show();
        } else {
            resetSections([assignmentTypeSection, employeeSelectionSection, submitSection]);
        }
    });

    // Step 4: Assignment Type Selection
    $('input[name="assign_to_type"]').on('change', function() {
        if (this.value === 'selected_employees') {
            loadEmployeesByDepartments(departmentSelect.val());
            employeeSelectionSection.show();
        } else {
            employeeSelectionSection.hide();
        }
        submitSection.show();
    });

    // Load departments by single category
    function loadDepartmentsByCategory(categoryId) {
        departmentSelect.html('<option value="">Loading departments...</option>');

        $.ajax({
            url: '{{ route("ajax.departments.by.category") }}', // Need to create this route
            type: 'GET',
            data: { category_id: categoryId },
            success: function(data) {
                if (!data.success) {
                    departmentSelect.html('<option value="">Error loading departments</option>');
                    return;
                }

                let options = '<option value=""></option>';
                data.departments.forEach(dept => {
                    options += `<option value="${dept.department_id}">${dept.department}</option>`;
                });

                departmentSelect.html(options);
                departmentSelect.val(null).trigger('change');
            },
            error: function() {
                departmentSelect.html('<option value="">Error loading departments</option>');
            }
        });
    }

    // Load employees by departments
    function loadEmployeesByDepartments(departmentIds) {
        employeeList.html('<p class="text-muted">Loading employees...</p>');

        $.ajax({
            url: '{{ route("ajax.multiemployees.by.departments") }}',
            type: 'GET',
            data: { department_ids: departmentIds },
            success: function(data) {
                if (!data.success) {
                    employeeList.html(`<p class="text-danger">${data.message}</p>`);
                    return;
                }

                const employees = data.employees || [];
                if (employees.length > 0) {
                    let html = '';
                    employees.forEach(emp => {
                        html += `
                            <div class="form-check mb-2">
                                <input type="checkbox" name="employee_ids[]" class="form-check-input" 
                                    value="${emp.employee_id}" id="emp_${emp.id}">
                                <label class="form-check-label" for="emp_${emp.id}">
                                    <strong>${emp.name}</strong> 
                                    <span class="text-muted">(${emp.employee_id})</span><br>
                                    <small class="text-muted">${emp.designation} - ${emp.employee_code}</small>
                                </label>
                            </div>
                        `;
                    });
                    employeeList.html(html);
                } else {
                    employeeList.html('<p class="text-danger">No employees found in selected departments.</p>');
                }
            },
            error: function() {
                employeeList.html('<p class="text-danger">Error fetching employees.</p>');
            }
        });
    }
    // Add this before form submission
$('#assignShiftForm').on('submit', function(e) {
    // Filter out empty values from department_ids
    const departmentSelect = $('#department_ids');
    const selectedDepartments = departmentSelect.val();
    
    if (selectedDepartments) {
        // Filter out empty/null values
        const filteredDepartments = selectedDepartments.filter(dept => dept && dept.trim() !== '');
        departmentSelect.val(filteredDepartments).trigger('change');
    }
    
    // Filter out empty values from shift_ids
    const shiftSelect = $('#shiftSelect');
    const selectedShifts = shiftSelect.val();
    
    if (selectedShifts) {
        const filteredShifts = selectedShifts.filter(shift => shift && shift.trim() !== '');
        shiftSelect.val(filteredShifts).trigger('change');
    }
    
    // Continue with form submission
    return true;
});
    // Reset sections
    function resetSections(sections) {
        if (!sections) {
            sections = [categorySection, departmentSection, assignmentTypeSection, 
                        employeeSelectionSection, submitSection];
        }
        sections.forEach(section => section.hide());
    }

    // Add styling to assignment options when selected
    $('.assignment-option input').on('change', function() {
        $('.assignment-option').removeClass('selected');
        $(this).closest('.assignment-option').addClass('selected');
    });
});
</script>
@endsection