@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Add Designation</title>
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
        /*margin-bottom: 30px;*/
        animation: fadeInUp 0.6s ease;
    }

    .card-header {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        padding: 15px 15px;
        border: none;
        align-items: center;
    }

    .card-header h4 {
        color: white;
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .card-header h4 i {
        background: rgba(255,255,255,0.2);
        padding: 10px;
        border-radius: 12px;
        margin-right: 15px;
    }

    .card-body {
        padding: 40px;
        background: #f8faff;
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

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .roles-container {
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 15px;
        max-height: 250px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #4361ee #e0e0e0;
    }

    .roles-container::-webkit-scrollbar {
        width: 8px;
    }

    .roles-container::-webkit-scrollbar-track {
        background: #e0e0e0;
        border-radius: 10px;
    }

    .roles-container::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        border-radius: 10px;
    }

    .role-option {
        margin: 8px 0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .role-option label {
        padding: 12px 20px;
        margin: 0;
        border-radius: 12px;
        background: #f8f9ff;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        font-weight: 500;
        color: #2c3e50;
    }

    .role-option:hover label {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        transform: translateX(5px);
        box-shadow: var(--shadow-md);
    }

    .role-option.selected label {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        box-shadow: var(--shadow-md);
        border: 2px solid white;
    }

    .role-option input[type="radio"] {
        display: none;
    }

    .role-option i {
        font-size: 10px;
        margin-right: 15px;
        opacity: 0.6;
    }

    .role-option.selected i {
        color: white;
        opacity: 1;
    }

    .text-muted {
        color: #7f8c8d !important;
        margin-top: 10px;
        display: block;
        font-size: 0.9rem;
    }

    .btn-group-custom {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        /*display: inline-flex;*/
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.9rem;
    }

    .btn i {
        margin-right: 8px;
        font-size: 1rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.4);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.5);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(149, 165, 166, 0.3);
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(149, 165, 166, 0.4);
    }

    .btn-reset {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
    }

    .btn-reset:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(243, 156, 18, 0.4);
    }

    .required-field::after {
        content: '*';
        color: #e74c3c;
        margin-left: 4px;
        font-size: 1.2rem;
    }

    .floating-effect {
        animation: float 3s ease-in-out infinite;
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

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .invalid-feedback {
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 5px;
        padding-left: 15px;
        animation: shake 0.5s ease;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    .is-invalid {
        border-color: #e74c3c !important;
    }

    .counter-badge {
        background: rgba(255,255,255,0.2);
        height: auto;
        padding: 10px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        margin-left: 10px;
        place-content: center;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .card-body {
            padding: 20px;
        }
        
        .btn-group-custom {
            flex-direction: column;
        }
        
        .btn {
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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex">
                    <h4>
                        <i class="fas fa-pen-fancy"></i>
                        Add Designation
                    </h4>
                    <span class="counter-badge badge">New Entry</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('designations.store') }}" method="POST" id="addDesignationForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-layer-group"></i>
                                        <span class="required-field">Department Category</span>
                                    </label>
                                    <select name="department_category_id" class="form-select @error('department_category_id') is-invalid @enderror" required id="categorySelect">
                                        <option value="" disabled selected>Select a department category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->department_category_id }}" {{ old('department_category_id') == $category->department_category_id ? 'selected' : '' }}>
                                                {{ $category->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-tag"></i>
                                        <span class="required-field">Designation Name</span>
                                    </label>
                                    <input type="text" name="designations" class="form-control @error('designations') is-invalid @enderror" 
                                           value="{{ old('designations') }}" placeholder="e.g., Senior Teacher, Principal" required>
                                    @error('designations')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-align-left"></i>
                                Description
                            </label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="4" placeholder="Enter a detailed description of this designation...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Describe the responsibilities and scope of this designation
                            </small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user-tag"></i>
                                <span class="required-field">Assign Role</span>
                            </label>
                            <div class="roles-container @error('role') is-invalid @enderror" id="rolesContainer">
                                @foreach($roles as $role)
                                    <div class="role-option" onclick="selectRole(this)">
                                        <input type="radio" name="roles" value="{{ $role->name }}"
                                            id="role_{{ $role->id }}"
                                            {{ old('role') == $role->name ? 'checked' : '' }}>
                                        <label class="w-100" for="role_{{ $role->id }}">
                                            <i class="fas fa-circle"></i>
                                            {{ ucfirst($role->name) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-key me-1"></i>
                                Select one role to define the permissions for this designation
                            </small>
                        </div>

                        <div class="btn-group-custom">
                            <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                <i class="fas fa-undo-alt"></i>
                                Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Create Designation
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
                                <li class="mb-2"><i class="fas fa-check-circle me-2" style="color: #27ae60;"></i>Choose a descriptive designation name that reflects the position</li>
                                <li class="mb-2"><i class="fas fa-check-circle me-2" style="color: #27ae60;"></i>Select the appropriate department category for better organization</li>
                                <li class="mb-2"><i class="fas fa-check-circle me-2" style="color: #27ae60;"></i>Assign the correct role to ensure proper access permissions</li>
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
// Function to select a role with enhanced animation
function selectRole(element) {
    // Remove selected class from all role options with fade effect
    const allRoleOptions = document.querySelectorAll('.role-option');
    allRoleOptions.forEach(option => {
        option.classList.remove('selected');
        option.style.transform = 'scale(1)';
    });
    
    // Add selected class to clicked option with scale animation
    element.classList.add('selected');
    element.style.transform = 'scale(1.02)';
    setTimeout(() => {
        element.style.transform = 'scale(1)';
    }, 200);
    
    // Find and check the radio input
    const radioInput = element.querySelector('input[type="radio"]');
    if (radioInput) {
        radioInput.checked = true;
    }
}

// Function to reset form including role selection with animation
function resetForm() {
    // Remove selected class from all role options
    const allRoleOptions = document.querySelectorAll('.role-option');
    allRoleOptions.forEach(option => {
        option.classList.remove('selected');
        option.style.animation = 'shake 0.5s ease';
        setTimeout(() => {
            option.style.animation = '';
        }, 500);
    });
    
    // Uncheck all radio buttons
    const radioInputs = document.querySelectorAll('input[name="roles"]');
    radioInputs.forEach(radio => {
        radio.checked = false;
    });
    
    // Reset other form fields
    document.getElementById('addDesignationForm').reset();
    
    // Show a small toast or notification (you can implement a proper toast)
    alert('Form has been reset successfully!');
}

// Form Validation with enhanced feedback
document.getElementById('addDesignationForm').addEventListener('submit', function(e) {
    const selectedRole = document.querySelector('input[name="roles"]:checked');
    
    if (!selectedRole) {
        e.preventDefault();
        
        // Highlight the roles container
        const rolesContainer = document.getElementById('rolesContainer');
        rolesContainer.style.animation = 'shake 0.5s ease';
        rolesContainer.style.borderColor = '#e74c3c';
        
        setTimeout(() => {
            rolesContainer.style.animation = '';
        }, 500);
        
        // Show custom error message
        alert('⚠️ Please select a role for the designation before submitting.');
    }
});

// Initialize selected roles on page load with highlight
document.addEventListener('DOMContentLoaded', function() {
    console.log('Add designation page loaded with enhanced UI');
    
    // Initialize any pre-selected roles (from old input)
    const selectedRadio = document.querySelector('input[name="roles"]:checked');
    if (selectedRadio) {
        const roleOption = selectedRadio.closest('.role-option');
        if (roleOption) {
            roleOption.classList.add('selected');
        }
    }
    
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

// Character counter for description
const descriptionField = document.querySelector('textarea[name="description"]');
if (descriptionField) {
    descriptionField.addEventListener('input', function() {
        const charCount = this.value.length;
        const maxChars = 500;
        
        // Create or update counter
        let counter = this.parentElement.querySelector('.char-counter');
        if (!counter) {
            counter = document.createElement('small');
            counter.className = 'char-counter text-muted mt-2 d-block';
            this.parentElement.appendChild(counter);
        }
        
        counter.innerHTML = `<i class="fas fa-text-height me-1"></i>${charCount}/${maxChars} characters`;
        
        if (charCount > maxChars) {
            counter.style.color = '#e74c3c';
        } else {
            counter.style.color = '#7f8c8d';
        }
    });
}
</script>
@endsection