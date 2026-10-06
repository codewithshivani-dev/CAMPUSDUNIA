@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')

<style>
.card-header {
    border-bottom: 2px solid rgba(0,0,0,0.1);
}
.badge {
    padding: 8px 12px;
    font-size: 0.9rem;
    display: block;
    margin-bottom: 10px;
    text-align: center;
}
.form-check-label {
    font-weight: 500;
    cursor: pointer;
}
.form-check-input {
    cursor: pointer;
}
#employeeList {
    max-height: 300px;
    overflow-y: auto;
}
</style>
<div class="container mt-4">
    <h4>📋 Assign Leave Policies</h4>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('policies.assign') }}" method="POST" id="policyAssignmentForm">
        @csrf

        <!-- Policy Selection -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Select Leave Policy</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="policy_id">Leave Policy *</label>
                    <select id="policy_id" name="policy_id" class="form-control" required>
                        <option value="">-- Select Policy --</option>
                        @foreach($policies as $policy)
                        <option value="{{ $policy->policy_id }}" data-rules='@json($policy->conversion_rules)'>
                            {{ $policy->policy_name }}
                            @if($policy->is_default) (Default) @endif
                            @if($policy->branch_id) - Branch Specific @else - Institute Wide @endif
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Policy Details Preview -->
                <div class="card mt-3 d-none" id="policyDetailsCard">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="bi bi-info-circle"></i> Policy Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Hours:</strong><br>
                                Full Day: <span id="fullDayHours">8</span> hrs<br>
                                Half Day: <span id="halfDayHours">4</span> hrs<br>
                                Short Leave: <span id="shortLeaveHours">2</span> hrs
                            </div>
                            <div class="col-md-8">
                                <strong>Conversion Rules:</strong><br>
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <div class="badge bg-warning">
                                            <span id="halfToFull">2</span> Half Days = 1 Full Day
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="badge bg-info">
                                            <span id="shortToHalf">2</span> Short Leaves = 1 Half Day
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="badge bg-success">
                                            <span id="shortToFull">4</span> Short Leaves = 1 Full Day
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignment Type -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-gear"></i> Assignment Type</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Assign To *</label>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input assignment-type" type="radio" 
                                       name="assignment_type" id="assignShift" value="shift" required>
                                <label class="form-check-label" for="assignShift">
                                    <i class="bi bi-clock"></i> Shifts
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input assignment-type" type="radio" 
                                       name="assignment_type" id="assignDepartment" value="department" required>
                                <label class="form-check-label" for="assignDepartment">
                                    <i class="bi bi-building"></i> Departments
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input assignment-type" type="radio" 
                                       name="assignment_type" id="assignEmployee" value="employee" required>
                                <label class="form-check-label" for="assignEmployee">
                                    <i class="bi bi-person"></i> Employees
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shifts Assignment (Initially Hidden) -->
        <div class="card mb-4 d-none" id="shiftAssignmentSection">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-clock"></i> Select Shifts</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="shift_ids">Select Shifts *</label>
                    <select id="shift_ids" name="shift_ids[]" class="form-control" multiple style="height: 150px;">
                        @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}">
                            {{ $shift->shift_name }} ({{ $shift->start_time }} - {{ $shift->end_time }})
                        </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hold Ctrl to select multiple shifts</small>
                </div>
                
                <div class="form-check mt-3">
                    <input type="checkbox" name="is_default" id="shiftIsDefault" class="form-check-input">
                    <label class="form-check-label" for="shiftIsDefault">
                        Set as default policy for selected shifts
                    </label>
                </div>
            </div>
        </div>

        <!-- Department Assignment (Initially Hidden) -->
        <div class="card mb-4 d-none" id="departmentAssignmentSection">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-building"></i> Select Departments</h5>
            </div>
            <div class="card-body">
                <!-- Department Category -->
                <div class="form-group">
                    <label for="department_category_id">Department Category *</label>
                    <select id="department_category_id" name="department_category_id" class="form-control">
                        <option value="">-- Select Category --</option>
                        @foreach($departmentCategories as $category)
                        <option value="{{ $category->department_category_id }}">
                            {{ $category->category_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Departments (Loaded via AJAX) -->
                <div class="form-group mt-3">
                    <label for="department_ids">Select Departments *</label>
                    <select id="department_ids" name="department_ids[]" class="form-control" multiple 
                            style="height: 150px;" disabled>
                        <option value="">-- Select category first --</option>
                    </select>
                    <small class="text-muted">Hold Ctrl to select multiple departments</small>
                </div>

                <!-- Assignment Type for Departments -->
                <div class="form-group mt-3">
                    <label>Assign To *</label>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input department-assign-type" type="radio" 
                                       name="assign_to_type" id="wholeDepartment" value="whole_department">
                                <label class="form-check-label" for="wholeDepartment">
                                    Whole Department (All employees)
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input department-assign-type" type="radio" 
                                       name="assign_to_type" id="selectedEmployees" value="selected_employees">
                                <label class="form-check-label" for="selectedEmployees">
                                    Selected Employees Only
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employee Selection (Initially Hidden) -->
                <div class="d-none" id="employeeSelectionSection">
                    <div class="form-group">
                        <label>Select Employees *</label>
                        <div id="employeeList" class="border rounded p-3 bg-light" style="min-height: 150px;">
                            <p class="text-muted mb-0">Select departments first to view employees.</p>
                        </div>
                        <div class="mt-2">
                            <input type="checkbox" id="selectAllEmployees">
                            <label for="selectAllEmployees">Select All Employees</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-check mt-3">
                    <input type="checkbox" name="is_default" id="deptIsDefault" class="form-check-input">
                    <label class="form-check-label" for="deptIsDefault">
                        Set as default policy for selected departments
                    </label>
                </div>
            </div>
        </div>

        <!-- Employee Assignment (Initially Hidden) -->
        <div class="card mb-4 d-none" id="employeeAssignmentSection">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-person"></i> Select Employees</h5>
            </div>
            <div class="card-body">
                <!-- Category -> Department -> Employee flow -->
                <div class="form-group">
                    <label for="emp_category_id">Department Category *</label>
                    <select id="emp_category_id" name="emp_category_id" class="form-control">
                        <option value="">-- Select Category --</option>
                        @foreach($departmentCategories as $category)
                        <option value="{{ $category->department_category_id }}">
                            {{ $category->category_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mt-3">
                    <label for="emp_department_id">Department *</label>
                    <select id="emp_department_id" name="emp_department_id" class="form-control" disabled>
                        <option value="">-- Select category first --</option>
                    </select>
                </div>

                <div class="form-group mt-3">
                    <label for="employee_ids">Select Employees *</label>
                    <select id="employee_ids" name="employee_ids[]" class="form-control" multiple 
                            style="height: 200px;" disabled>
                        <option value="">-- Select department first --</option>
                    </select>
                    <small class="text-muted">Hold Ctrl to select multiple employees</small>
                </div>
            </div>
        </div>

        <!-- Effective Dates -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-calendar"></i> Effective Dates (Optional)</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="effective_from">Effective From</label>
                            <input type="date" name="effective_from" id="effective_from" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="effective_to">Effective To</label>
                            <input type="date" name="effective_to" id="effective_to" class="form-control">
                        </div>
                    </div>
                </div>
                <small class="text-muted">Leave blank for permanent assignment</small>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Assign Policy
            </button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Policy details display
    const policySelect = document.getElementById('policy_id');
    const policyDetailsCard = document.getElementById('policyDetailsCard');
    
    policySelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            try {
                const policyRules = JSON.parse(selectedOption.getAttribute('data-rules'));
                updatePolicyDetails(policyRules);
                policyDetailsCard.classList.remove('d-none');
            } catch (error) {
                console.error('Error parsing policy rules:', error);
                policyDetailsCard.classList.add('d-none');
            }
        } else {
            policyDetailsCard.classList.add('d-none');
        }
    });
    
    function updatePolicyDetails(rules) {
        if (!rules) return;
        
        document.getElementById('fullDayHours').textContent = rules.full_day_hours || 8;
        document.getElementById('halfDayHours').textContent = rules.half_day_hours || 4;
        document.getElementById('shortLeaveHours').textContent = rules.short_leave_hours || 2;
        
        const conversion = rules.conversion || {};
        document.getElementById('halfToFull').textContent = conversion.half_day_to_full_day || 2;
        document.getElementById('shortToHalf').textContent = conversion.short_leave_to_half_day || 2;
        document.getElementById('shortToFull').textContent = conversion.short_leave_to_full_day || 4;
    }
    
    // Assignment type toggle
    const assignmentTypes = document.querySelectorAll('.assignment-type');
    const shiftSection = document.getElementById('shiftAssignmentSection');
    const deptSection = document.getElementById('departmentAssignmentSection');
    const empSection = document.getElementById('employeeAssignmentSection');
    
    assignmentTypes.forEach(type => {
        type.addEventListener('change', function() {
            shiftSection.classList.add('d-none');
            deptSection.classList.add('d-none');
            empSection.classList.add('d-none');
            
            switch(this.value) {
                case 'shift':
                    shiftSection.classList.remove('d-none');
                    break;
                case 'department':
                    deptSection.classList.remove('d-none');
                    break;
                case 'employee':
                    empSection.classList.remove('d-none');
                    break;
            }
        });
    });
    
    // Department category change (for department assignment)
    const deptCategorySelect = document.getElementById('department_category_id');
    const deptSelect = document.getElementById('department_ids');
    
    deptCategorySelect.addEventListener('change', function() {
        const categoryId = this.value;
        
        if (!categoryId) {
            deptSelect.innerHTML = '<option value="">-- Select category first --</option>';
            deptSelect.disabled = true;
            return;
        }
        
        deptSelect.innerHTML = '<option value="">Loading departments...</option>';
        
        fetch(`/ajax/departments-by-category?category_id=${categoryId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.departments.length > 0) {
                let options = '';
                data.departments.forEach(dept => {
                    const shiftInfo = dept.shift_name ? ` (${dept.shift_name})` : '';
                    options += `<option value="${dept.department_id}">${dept.department}${shiftInfo}</option>`;
                });
                deptSelect.innerHTML = options;
                deptSelect.disabled = false;
            } else {
                deptSelect.innerHTML = '<option value="">No departments found</option>';
            }
        })
        .catch(error => {
            console.error('Error loading departments:', error);
            deptSelect.innerHTML = '<option value="">Error loading departments</option>';
        });
    });
    
    // Load employees when department selection changes (for department assignment)
    deptSelect.addEventListener('change', function() {
        const selectedDepts = Array.from(this.selectedOptions).map(opt => opt.value);
        const employeeList = document.getElementById('employeeList');
        
        if (selectedDepts.length === 0) {
            employeeList.innerHTML = '<p class="text-muted mb-0">Select departments first to view employees.</p>';
            return;
        }
        
        employeeList.innerHTML = '<div class="text-center"><div class="spinner-border spinner-border-sm"></div> Loading employees...</div>';
        
        // Load employees from all selected departments
        const promises = selectedDepts.map(deptId => 
            fetch(`/ajax/employees-by-department?department_id=${deptId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            }).then(res => res.json())
        );
        
        Promise.all(promises)
            .then(results => {
                let allEmployees = [];
                results.forEach(data => {
                    if (data.employees && data.employees.length > 0) {
                        allEmployees = allEmployees.concat(data.employees);
                    }
                });
                
                if (allEmployees.length > 0) {
                    let html = '<div class="row">';
                    allEmployees.forEach(emp => {
                        html += `
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input type="checkbox" name="employee_ids[]" value="${emp.employee_id}" 
                                       class="form-check-input emp-checkbox" id="emp_${emp.employee_id}">
                                <label class="form-check-label" for="emp_${emp.employee_id}">
                                    ${emp.employee_id} - ${emp.name}
                                </label>
                            </div>
                        </div>`;
                    });
                    html += '</div>';
                    employeeList.innerHTML = html;
                } else {
                    employeeList.innerHTML = '<p class="text-danger">No employees found in selected departments.</p>';
                }
            })
            .catch(error => {
                console.error('Error loading employees:', error);
                employeeList.innerHTML = '<p class="text-danger">Error loading employees.</p>';
            });
    });
    
    // Toggle employee selection section for department assignment
    const deptAssignTypes = document.querySelectorAll('.department-assign-type');
    const empSelectionSection = document.getElementById('employeeSelectionSection');
    
    deptAssignTypes.forEach(type => {
        type.addEventListener('change', function() {
            if (this.value === 'selected_employees') {
                empSelectionSection.classList.remove('d-none');
            } else {
                empSelectionSection.classList.add('d-none');
            }
        });
    });
    
    // Select all employees
    document.addEventListener('click', function(e) {
        if (e.target.id === 'selectAllEmployees') {
            document.querySelectorAll('.emp-checkbox').forEach(chk => {
                chk.checked = e.target.checked;
            });
        }
    });
    
    // Employee assignment flow
    const empCategorySelect = document.getElementById('emp_category_id');
    const empDeptSelect = document.getElementById('emp_department_id');
    const employeeMultiSelect = document.getElementById('employee_ids');
    
    empCategorySelect.addEventListener('change', function() {
        const categoryId = this.value;
        
        if (!categoryId) {
            empDeptSelect.innerHTML = '<option value="">-- Select category first --</option>';
            empDeptSelect.disabled = true;
            employeeMultiSelect.disabled = true;
            return;
        }
        
        empDeptSelect.innerHTML = '<option value="">Loading departments...</option>';
        
        fetch(`/ajax/departments-by-category?category_id=${categoryId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.departments.length > 0) {
                let options = '<option value="">-- Select Department --</option>';
                data.departments.forEach(dept => {
                    options += `<option value="${dept.department_id}">${dept.department}</option>`;
                });
                empDeptSelect.innerHTML = options;
                empDeptSelect.disabled = false;
                employeeMultiSelect.disabled = true;
            } else {
                empDeptSelect.innerHTML = '<option value="">No departments found</option>';
            }
        });
    });
    
    empDeptSelect.addEventListener('change', function() {
        const deptId = this.value;
        
        if (!deptId) {
            employeeMultiSelect.innerHTML = '<option value="">-- Select department first --</option>';
            employeeMultiSelect.disabled = true;
            return;
        }
        
        employeeMultiSelect.innerHTML = '<option value="">Loading employees...</option>';
        
        fetch(`/ajax/employees-by-department?department_id=${deptId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.employees && data.employees.length > 0) {
                let options = '';
                data.employees.forEach(emp => {
                    options += `<option value="${emp.employee_id}">${emp.employee_id} - ${emp.name}</option>`;
                });
                employeeMultiSelect.innerHTML = options;
                employeeMultiSelect.disabled = false;
            } else {
                employeeMultiSelect.innerHTML = '<option value="">No employees found</option>';
            }
        });
    });
    
    // Form validation
    document.getElementById('policyAssignmentForm').addEventListener('submit', function(e) {
        const assignmentType = document.querySelector('input[name="assignment_type"]:checked');
        
        if (!assignmentType) {
            e.preventDefault();
            alert('Please select an assignment type');
            return;
        }
        
        if (!policySelect.value) {
            e.preventDefault();
            alert('Please select a policy');
            return;
        }
        
        // Validate based on assignment type
        switch(assignmentType.value) {
            case 'shift':
                const shiftSelect = document.getElementById('shift_ids');
                if (shiftSelect.selectedOptions.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one shift');
                }
                break;
                
            case 'department':
                const deptSelect = document.getElementById('department_ids');
                if (deptSelect.selectedOptions.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one department');
                    return;
                }
                
                const assignToType = document.querySelector('input[name="assign_to_type"]:checked');
                if (!assignToType) {
                    e.preventDefault();
                    alert('Please select assignment type for departments');
                    return;
                }
                
                if (assignToType.value === 'selected_employees') {
                    const selectedEmployees = document.querySelectorAll('.emp-checkbox:checked').length;
                    if (selectedEmployees === 0) {
                        e.preventDefault();
                        alert('Please select at least one employee');
                    }
                }
                break;
                
            case 'employee':
                const empSelect = document.getElementById('employee_ids');
                if (empSelect.selectedOptions.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one employee');
                }
                break;
        }
    });
});
</script>
@endsection