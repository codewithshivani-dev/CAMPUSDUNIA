@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<title>Add Multiple Departments</title>

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
    }

    .alert {
        border: none;
        border-radius: 15px;
        padding: 15px 20px;
        margin-bottom: 20px;
        animation: slideInDown 0.5s ease;
        box-shadow: var(--shadow-md);
    }

    .alert-success {
        background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
        color: #1e7e34;
    }

    .alert-danger {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: #721c24;
    }

    .card {
        border: none;
        border-radius: 25px;
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        margin-bottom: 30px;
        animation: fadeInUp 0.6s ease;
    }

    .card-header {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        padding: 20px 30px;
        border: none;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h4 {
        color: white;
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        font-size: 1.25rem;
    }

    .card-header h4 i {
        margin-right: 15px;
    }

    .card-body {
        padding: 40px;
        background: #f8faff;
    }

    .mandatory-note {
        color: #e74c3c;
        font-size: 0.9rem;
        padding-right: 1.5rem;
        margin: 0.5rem 0;
        /*text-align: right;*/
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }

    .form-label i {
        margin-right: 8px;
        color: #4361ee;
        font-size: 1.1rem;
    }

    .form-control, .form-select {
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 12px 20px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
    }

    .form-control:hover, .form-select:hover {
        border-color: #3a0ca3;
    }

    .category-info {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-left: 4px solid #4361ee;
        padding: 20px;
        border-radius: 15px;
        animation: slideInRight 0.5s ease;
        box-shadow: var(--shadow-sm);
    }

    .category-info strong {
        color: #2c3e50;
    }

    .category-info span {
        color: #4361ee;
        font-weight: 600;
    }

    #departmentsContainer {
        max-height: 500px;
        overflow-y: auto;
        padding: 15px;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        background: white;
        scrollbar-width: thin;
        scrollbar-color: #4361ee #e0e0e0;
    }

    #departmentsContainer::-webkit-scrollbar {
        width: 8px;
    }

    #departmentsContainer::-webkit-scrollbar-track {
        background: #e0e0e0;
        border-radius: 10px;
    }

    #departmentsContainer::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        border-radius: 10px;
    }

    .department-row {
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        background: white;
        transition: all 0.3s ease;
        position: relative;
        animation: slideInUp 0.5s ease;
    }

    .department-row:hover {
        border-color: #4361ee;
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .department-row.valid {
        border-color: #28a745;
        background: linear-gradient(135deg, #f8f9fa 0%, #e8f5e9 100%);
    }

    .remove-btn {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border: none;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1.3rem;
        font-weight: bold;
        position: absolute;
        top: 15px;
        right: 15px;
        box-shadow: var(--shadow-sm);
        z-index: 10;
    }

    .remove-btn:hover {
        transform: scale(1.1) rotate(90deg);
        box-shadow: var(--shadow-md);
    }

    .row-number {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: bold;
        margin-right: 15px;
        box-shadow: var(--shadow-sm);
    }

    .input-group-department {
        display: flex;
        align-items: flex-start;
        gap: 15px;
    }

    .department-inputs {
        flex: 1;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .btn-add-row {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white !important;
        border: none;
        border-radius: 50px;
        /*padding: 10px 25px;*/
        font-weight: 600;
        /*display: inline-flex;*/
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .btn-add-row:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
    }

    .btn-add-row i {
        font-size: 1.1rem;
    }

    .btn-save {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white !important;
        border: none;
        border-radius: 50px;
        /*padding: 12px 35px;*/
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.4);
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-save:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.5);
    }

    .btn-save:disabled {
        background: linear-gradient(135deg, #95a5a6, #7f8c8d);
        cursor: not-allowed;
        opacity: 0.7;
    }

    .empty-state {
        text-align: center;
        padding: 50px 20px;
        background: #f8f9fa;
        border-radius: 15px;
        border: 2px dashed #e0e0e0;
    }

    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 15px;
    }

    .empty-state p {
        color: #7f8c8d;
        font-size: 1.1rem;
        margin: 0;
    }

    .required-field::after {
        content: '*';
        color: #e74c3c;
        margin-left: 4px;
        font-size: 1.2rem;
    }

    .text-danger {
        color: #e74c3c !important;
    }

    .department-row-removing {
        animation: slideOut 0.3s ease-out forwards;
    }

    @keyframes slideInDown {
        from {
            transform: translateY(-100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes fadeInUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes slideInRight {
        from {
            transform: translateX(30px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideInUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    .invalid-feedback {
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 5px;
        padding-left: 15px;
        animation: shake 0.5s ease;
    }

    .is-invalid {
        border-color: #e74c3c !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .card-body {
            padding: 20px;
        }
        
        .department-inputs {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .input-group-department {
            flex-direction: column;
        }
        
        .row-number {
            margin-bottom: 10px;
        }
        
        .btn-save {
            width: 100%;
        }
    }
</style>

<div class="container-fluid">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>
                        <i class="fas fa-building"></i>
                        Add Departments
                        <span class="counter-badge badge" style="background: rgba(255,255,255,0.2); border-radius: 20px; font-size: 0.8rem; margin-left: 10px;">
                            Bulk Entry
                        </span>
                    </h4>
                    
                    <div class="mandatory-note">
                        <i class="fas fa-asterisk text-danger me-1" style="font-size: 8px;"></i>
                        Fields marked with an asterisk (*) are mandatory.
                    </div>
                </div>
                <div class="card-body">
                    <form id="departmentForm" action="{{ route('departments.store') }}" method="POST">
                        @csrf
                        
                        <!-- Category Selection -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-layer-group"></i>
                                        <span class="required-field">Select Category</span>
                                    </label>
                                    <select class="form-select" id="department_category_id" name="department_category_id" required>
                                        <option value="" disabled selected>-- Select a Category --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->department_category_id }}"
                                                data-description="{{ $category->description }}"
                                                data-name="{{ $category->category_name }}">
                                                {{ $category->category_name }} ({{ $category->departments_count }} departments)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="categoryDescription" class="category-info" style="display: none;">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-info-circle me-2" style="color: #4361ee;"></i>
                                        <strong>Selected Category:</strong>
                                        <span id="categoryName" class="ms-2 fw-bold"></span>
                                    </div>
                                    <div class="d-flex">
                                        <i class="fas fa-align-left me-2" style="color: #4361ee;"></i>
                                        <strong>Description:</strong>
                                        <span id="descriptionText" class="ms-2"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Departments Container -->
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label mb-0">
                                    <i class="fas fa-building"></i>
                                    Add Departments:
                                </label>
                                <button type="button" class="btn btn-add-row" onclick="addDepartment()">
                                    <i class="fas fa-plus-circle"></i> Add Department
                                </button>
                            </div>

                            <div id="departmentsContainer">
                                <!-- Empty state -->
                                <div id="emptyState" class="empty-state">
                                    <i class="fas fa-building"></i>
                                    <p>No departments added yet. Click "Add Department" to start.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-save" id="submitDepartments" disabled>
                                <i class="fas fa-save"></i> Save All Departments
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Tips Card -->
            <div class="card mt-4" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 style="color: #2c3e50;"><i class="fas fa-lightbulb me-2" style="color: #f1c40f;"></i>Quick Tips</h5>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i class="fas fa-check-circle me-2" style="color: #27ae60;"></i>Select a category first before adding departments</li>
                                <li class="mb-2"><i class="fas fa-check-circle me-2" style="color: #27ae60;"></i>You can add multiple departments at once using the "Add Department Row" button</li>
                                <li class="mb-2"><i class="fas fa-check-circle me-2" style="color: #27ae60;"></i>Department names are required, descriptions are optional</li>
                            </ul>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-rocket fa-4x" style="color: #4361ee; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let departmentCount = 0;

// Category selection change
document.getElementById('department_category_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const categoryName = selectedOption.getAttribute('data-name');
    const description = selectedOption.getAttribute('data-description');
    const categoryDescription = document.getElementById('categoryDescription');
    const categoryNameSpan = document.getElementById('categoryName');
    const descriptionText = document.getElementById('descriptionText');
    const submitBtn = document.getElementById('submitDepartments');

    if (this.value) {
        categoryDescription.style.display = 'block';
        categoryNameSpan.textContent = categoryName;
        descriptionText.textContent = description || 'No description available';
        submitBtn.disabled = departmentCount === 0;
        
        // Add animation
        categoryDescription.style.animation = 'slideInRight 0.5s ease';
    } else {
        categoryDescription.style.display = 'none';
        submitBtn.disabled = true;
    }
});

// Add new department row
function addDepartment() {
    const container = document.getElementById('departmentsContainer');
    const emptyState = document.getElementById('emptyState');

    // Hide empty state when adding first department
    if (departmentCount === 0) {
        emptyState.style.display = 'none';
    }

    const newRow = document.createElement('div');
    newRow.className = 'department-row';
    newRow.setAttribute('data-index', departmentCount);

    newRow.innerHTML = `
        <button type="button" class="remove-btn" onclick="removeDepartment(${departmentCount})" title="Remove this department">
            <i class="fas fa-times"></i>
        </button>
        <div class="input-group-department">
            <div class="row-number">${departmentCount + 1}</div>
            <div class="department-inputs">
                <div>
                    <label class="form-label">
                        <i class="fas fa-tag"></i>
                        <span class="required-field">Department Name</span>
                    </label>
                    <input type="text" class="form-control department-name" name="departments[${departmentCount}][name]" 
                           placeholder="e.g. Computer Science" required>
                </div>
                <div>
                    <label class="form-label">
                        <i class="fas fa-align-left"></i>
                        Description
                    </label>
                    <input type="text" class="form-control department-desc" name="departments[${departmentCount}][description]" 
                           placeholder="Short description...">
                </div>
            </div>
        </div>
    `;

    container.appendChild(newRow);
    departmentCount++;

    // Enable submit button if category is selected
    const categorySelect = document.getElementById('department_category_id');
    if (categorySelect.value) {
        document.getElementById('submitDepartments').disabled = false;
    }

    // Add input validation
    const inputs = newRow.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('input', validateForm);
    });
    
    // Focus on first input
    newRow.querySelector('.department-name').focus();
}

// Remove department row
function removeDepartment(index) {
    const row = document.querySelector(`.department-row[data-index="${index}"]`);
    if (row) {
        // Add removal animation
        row.classList.add('department-row-removing');

        // Remove after animation completes
        setTimeout(() => {
            row.remove();
            departmentCount--;

            // Update indices for remaining rows
            updateRowIndices();

            // Show empty state if no departments left
            if (departmentCount === 0) {
                document.getElementById('emptyState').style.display = 'block';
                document.getElementById('submitDepartments').disabled = true;
            }

            validateForm();
        }, 300);
    }
}

// Update row indices after removal
function updateRowIndices() {
    const remainingRows = document.querySelectorAll('.department-row');
    remainingRows.forEach((row, newIndex) => {
        row.setAttribute('data-index', newIndex);
        row.style.animation = 'slideInUp 0.5s ease';

        const rowNumber = row.querySelector('.row-number');
        const nameInput = row.querySelector('.department-name');
        const descInput = row.querySelector('.department-desc');
        const removeBtn = row.querySelector('.remove-btn');

        if (rowNumber) rowNumber.textContent = newIndex + 1;
        if (nameInput) nameInput.name = `departments[${newIndex}][name]`;
        if (descInput) descInput.name = `departments[${newIndex}][description]`;
        if (removeBtn) removeBtn.setAttribute('onclick', `removeDepartment(${newIndex})`);
    });
}

// Validate form
function validateForm() {
    const categorySelect = document.getElementById('department_category_id');
    const departmentRows = document.querySelectorAll('.department-row');
    let hasValidDepartments = false;

    departmentRows.forEach(row => {
        const nameInput = row.querySelector('.department-name');
        const nameValid = nameInput.value.trim() !== '';

        // Visual feedback
        if (nameValid) {
            row.classList.add('valid');
            hasValidDepartments = true;
        } else {
            row.classList.remove('valid');
        }
    });

    // Enable/disable submit button
    const submitBtn = document.getElementById('submitDepartments');
    submitBtn.disabled = !(categorySelect.value && hasValidDepartments);
}

// Form submission validation
document.getElementById('departmentForm').addEventListener('submit', function(e) {
    const categorySelect = document.getElementById('department_category_id');
    const departmentRows = document.querySelectorAll('.department-row');
    let hasValidDepartments = false;

    // Check if at least one department has name
    departmentRows.forEach(row => {
        const nameInput = row.querySelector('.department-name');
        if (nameInput.value.trim() !== '') {
            hasValidDepartments = true;
        }
    });

    if (!categorySelect.value) {
        e.preventDefault();
        categorySelect.style.animation = 'shake 0.5s ease';
        setTimeout(() => {
            categorySelect.style.animation = '';
        }, 500);
        alert('⚠️ Please select a category first.');
        categorySelect.focus();
        return;
    }

    if (!hasValidDepartments) {
        e.preventDefault();
        alert('⚠️ Please add at least one department with a name.');
        return;
    }
});

// Real-time validation
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('department-name') || e.target.classList.contains('department-desc')) {
        validateForm();
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Add multiple departments page loaded');
    
    // Add floating labels effect
    const formControls = document.querySelectorAll('.form-control, .form-select');
    formControls.forEach(control => {
        control.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        control.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
    });
});
</script>
@endsection