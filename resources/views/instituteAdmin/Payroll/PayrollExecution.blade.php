@extends('instituteAdmin.Payroll.PayrollManagement')

@section('payroll-content')
<title>Payroll Execution Configuration</title>

<style>
    /* Modern typography and base variables */
    :root {
        --primary-color: #3b82f6;
        --primary-hover: #2563eb;
        --bg-light: #f8fafc;
        --text-main: #0f172a;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }

    /* Premium Card styling */
    .card {
        border: none;
        border-radius: 24px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.04), 0 4px 6px rgba(0, 0, 0, 0.02);
        overflow: hidden;
        margin: 0 auto;
        background: #ffffff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    /* Elegant Header with Gradient */
    .card-header {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-bottom: 1px solid #e2e8f0;
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .form-title {
        font-weight: 700;
        font-size: 1.45rem;
        color: var(--text-main);
        margin: 0;
        letter-spacing: -0.4px;
        display: flex;
        align-items: center;
    }

    /* Subtle button */
    .btn-outline-primary {
        border-radius: 50px;
        padding: 8px 22px;
        font-weight: 500;
        font-size: 0.85rem;
        border: 1px solid #cbd5e1;
        color: #334155;
        background: white;
        transition: all 0.25s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .btn-outline-primary:hover {
        background: var(--bg-light);
        border-color: #94a3b8;
        color: var(--text-main);
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
    }

    /* Form body spacing */
    .card-body {
        padding: 2rem 2.5rem;
    }

    /* Clear Form labels */
    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--text-main);
        margin-bottom: 0.6rem;
        letter-spacing: 0.2px;
    }

    /* Premium solid inputs */
    .form-select, .form-control {
        border-radius: 12px;
        border: 1px solid var(--border-color);
        background-color: var(--bg-light);
        padding: 0.7rem 1.2rem;
        font-size: 0.95rem;
        color: #1e293b;
        transition: all 0.2s;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.01);
    }

    .form-select:focus, .form-control:focus {
        background-color: #ffffff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        outline: none;
    }

    /* Hint styling */
    .hint {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .hint i {
        font-size: 0.85rem;
        color: var(--primary-color);
    }

    /* Primary Action button */
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, #2563eb 100%);
        border: none;
        border-radius: 50px;
        padding: 10px 28px;
        font-weight: 600;
        font-size: 0.9rem;
        letter-spacing: 0.3px;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        transition: all 0.25s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    }

    .btn-outline-secondary {
        border-radius: 50px;
        padding: 10px 24px;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .hidden {
        display: none;
    }

    /* Loader */
    .loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loader-overlay.active {
        display: flex;
    }

    .loader-content {
        text-align: center;
        background: white;
        padding: 30px 40px;
        border-radius: 15px;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 4px solid #e2e8f0;
        border-top: 4px solid #4361ee;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 15px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Success message */
    .alert-success {
        background: #d1fae5;
        border: 1px solid #10b981;
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 20px;
        display: none;
    }

    .alert-success.show {
        display: block;
    }

    /* Configurations table */
    .config-table-container {
        max-height: 500px;
        overflow-y: auto;
    }

    .config-table {
        width: 100%;
        border-collapse: collapse;
    }

    .config-table th,
    .config-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .config-table th {
        background: #f8fafc;
        font-weight: 600;
        position: sticky;
        top: 0;
    }

    .config-table tr:hover {
        background: #f8fafc;
    }

    .btn-sm {
        padding: 4px 10px;
        font-size: 12px;
        margin: 0 2px;
    }
</style>

<div class="container">
    <div class="card">
        <!-- Simple header with title and view button -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="form-title">
                <i class="fas fa-calendar-alt me-2" style="color: #3b82f6; font-size: 1.3rem;"></i>
                Payroll Execution
            </h3>
            <a href="{{ route('payroll.viewPage') }}" class="btn btn-outline-primary">
                <i class="fas fa-eye me-1"></i> View Execution
            </a>
        </div>

        <div class="card-body">
            <!-- Success Message -->
            <div id="successMessage" class="alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <span id="successText">Execution saved successfully!</span>
            </div>

            <form id="payrollConfigForm">
                @csrf

                <!-- Financial Year -->
                <div class="mb-3">
                    <label class="form-label">Financial Year <span class="text-danger">*</span></label>
                    <select name="financial_year" class="form-select" required>
                        <option selected disabled>Select Financial Year</option>
                        @php
                        $currentYear = date('Y');
                        @endphp
                        @for($i = $currentYear - 1; $i <= $currentYear + 2; $i++) <option value="{{ $i }}-{{ $i + 1 }}">
                            {{ $i }}-{{ $i + 1 }}</option>
                            @endfor
                    </select>
                </div>

                <!-- Apply Payroll To -->
                <div class="mb-3">
                    <label class="form-label">Apply Payroll To <span class="text-danger">*</span></label>
                    <select name="apply_to" class="form-select" id="assignScope" required>
                        <option selected disabled>Select Option</option>
                        <option value="all">All Departments</option>
                        <option value="specific">Specific Department</option>
                    </select>
                </div>

                <!-- Department (conditional) -->
                <div class="mb-3 hidden" id="departmentDiv">
                    <label class="form-label">Select Department <span class="text-danger">*</span></label>
                    <select name="department_id" class="form-select">
                        <option selected disabled>Select Department</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->department_id }}">{{ $dept->department }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Payroll Cycle -->
                <div class="mb-3">
                    <label class="form-label">Payroll Cycle <span class="text-danger">*</span></label>
                    <select name="payroll_cycle" class="form-select" id="payrollCycle" required>
                        <option selected disabled>Select Cycle</option>
                        <option value="monthly">Monthly</option>
                        <option value="days">Day Count</option>
                    </select>
                </div>

                <!-- Execution Date (ALWAYS DISPLAYED) -->
                <div class="mb-3" id="monthlyCycleDiv">
                    <label class="form-label">Execution Date <span class="text-danger">*</span></label>
                    <select name="execution_day" class="form-select" required>
                        <option selected disabled>Select Date</option>
                        @for($i = 1; $i <= 31; $i++) <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    <div class="hint">
                        <i class="fas fa-info-circle"></i>
                        If selected day exceeds month length (e.g., Feb), it will auto-adjust to last day.
                    </div>
                </div>

                <!-- Day Count (Days) -->
                <div class="mb-3 hidden" id="daysCycleDiv">
                    <label class="form-label">Day Count <span class="text-danger">*</span></label>
                    <input type="number" name="cycle_days" class="form-control" min="1" placeholder="Enter number of days (e.g., 15)">
                    <div class="hint">
                        <i class="fas fa-info-circle"></i>
                        Number of days for the payroll cycle.
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label">Description (Optional)</label>
                    <textarea name="description" class="form-control" rows="2"
                        placeholder="Add any notes about this configuration..."></textarea>
                </div>

                <div class="text-end mt-4">
                    <button type="reset" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Loader Overlay -->
<div id="loaderOverlay" class="loader-overlay">
    <div class="loader-content">
        <div class="spinner"></div>
        <p>Saving configuration...</p>
        <p class="loader-text">Please wait</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Show/hide department dropdown
const assignScope = document.getElementById("assignScope");
const departmentDiv = document.getElementById("departmentDiv");

if (assignScope) {
    assignScope.addEventListener("change", function() {
        if (this.value === "specific") {
            departmentDiv.classList.remove("hidden");
        } else {
            departmentDiv.classList.add("hidden");
        }
    });
}

// Show/hide cycle inputs
const payrollCycle = document.getElementById("payrollCycle");
const cyclesContainerHtml = document.getElementById("monthlyCycleDiv"); // we will rename logic but keep id for now
const daysCycleDiv = document.getElementById("daysCycleDiv");

if (payrollCycle) {
    payrollCycle.addEventListener("change", function() {
        if (this.value === "monthly") {
            daysCycleDiv.classList.add("hidden");
            document.querySelector('[name="cycle_days"]').required = false;
        } else if (this.value === "days") {
            daysCycleDiv.classList.remove("hidden");
            document.querySelector('[name="cycle_days"]').required = true;
        } else {
            daysCycleDiv.classList.add("hidden");
            document.querySelector('[name="cycle_days"]').required = false;
        }
    });
}

// Show loader
function showLoader() {
    const loader = document.getElementById('loaderOverlay');
    if (loader) loader.classList.add('active');
}

// Hide loader
function hideLoader() {
    const loader = document.getElementById('loaderOverlay');
    if (loader) loader.classList.remove('active');
}

// Show success message
function showSuccessMessage(message) {
    const successDiv = document.getElementById('successMessage');
    const successText = document.getElementById('successText');
    successText.textContent = message;
    successDiv.classList.add('show');
    setTimeout(() => {
        successDiv.classList.remove('show');
    }, 3000);
}

// Form submission
document.getElementById('payrollConfigForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    // Validate required fields
    if (!data.financial_year || !data.apply_to || !data.payroll_cycle) {
        Swal.fire({
            icon: 'warning',
            title: 'Incomplete Form',
            text: 'Please fill all required fields.',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }

    if (data.payroll_cycle === 'monthly' && !data.execution_day) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Execution Date',
            text: 'Please select an execution date.',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }

    if (data.payroll_cycle === 'days' && !data.cycle_days) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Day Count',
            text: 'Please enter a day count.',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }

    // Validate department if specific is selected
    if (data.apply_to === 'specific' && !data.department_id) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Department',
            text: 'Please select a department.',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }

    showLoader();
    executeSave(data);
});

function executeSave(data) {
    // Make API call to save configuration
    fetch('{{ route("payroll.config.save") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(responseData => {
            hideLoader();
            
            if (responseData.require_confirmation) {
                Swal.fire({
                    title: 'Override Existing?',
                    text: responseData.message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ea580c',  // Orange for overwrite action
                    cancelButtonColor: '#3b82f6',   // Blue for view
                    confirmButtonText: '<i class="fas fa-arrow-right me-1"></i> Continue',
                    cancelButtonText: '<i class="fas fa-eye me-1"></i> View',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        data.force_override = true;
                        showLoader();
                        executeSave(data);
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        window.location.href = "{{ route('payroll.viewPage') }}";
                    }
                });
                return;
            }
            
            if (responseData.success) {
                showSuccessMessage(responseData.message);
                setTimeout(() => {
                    window.location.href = "{{ route('payroll.viewPage') }}";
                }, 1500);
                document.getElementById('payrollConfigForm').reset();
                departmentDiv.classList.add('hidden');
                daysCycleDiv.classList.add('hidden');
                document.querySelector('[name="apply_to"]').value = '';
                document.querySelector('[name="payroll_cycle"]').value = '';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: responseData.message || 'Error saving configuration',
                    confirmButtonColor: '#3b82f6'
                });
            }
        })
        .catch(error => {
            hideLoader();
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Error saving configuration',
                confirmButtonColor: '#3b82f6'
            });
        });
}

document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const editId = params.get('edit_id');
    if (editId) {
        editConfig(editId);
    }
});

// Edit configuration
function editConfig(id) {
    // Fetch configuration details
    fetch(`{{ url("payroll/executions") }}/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const config = data.data;

                // Fill the form with config data
                document.querySelector('[name="financial_year"]').value = config.financial_year;
                
                // When editing a specific row, it acts as a 'specific' department override.
                document.querySelector('[name="apply_to"]').value = 'specific';

                // Trigger department visibility
                document.getElementById('departmentDiv').classList.remove('hidden');
                // Wait for the select to be populated
                setTimeout(() => {
                    document.querySelector('[name="department_id"]').value = config.department_id;
                }, 100);

                document.querySelector('[name="payroll_cycle"]').value = config.payroll_cycle;
                
                if (config.payroll_cycle === 'monthly') {
                    daysCycleDiv.classList.add("hidden");
                    document.querySelector('[name="cycle_days"]').required = false;
                    document.querySelector('[name="execution_day"]').value = config.execution_day;
                } else if (config.payroll_cycle === 'days') {
                    daysCycleDiv.classList.remove("hidden");
                    document.querySelector('[name="execution_day"]').required = true;
                    document.querySelector('[name="cycle_days"]').required = true;
                    document.querySelector('[name="execution_day"]').value = config.execution_day;
                    document.querySelector('[name="cycle_days"]').value = config.cycle_days;
                }

                document.querySelector('[name="description"]').value = config.description || '';

                // Store config ID for update
                document.getElementById('payrollConfigForm').setAttribute('data-edit-id', config.id);

                // Add to body params next time we submit
                const form = document.getElementById('payrollConfigForm');
                if(!document.getElementById('hidden_edit_id')) {
                    const hInput = document.createElement('input');
                    hInput.type = 'hidden';
                    hInput.name = 'edit_id';
                    hInput.id = 'hidden_edit_id';
                    hInput.value = config.id;
                    form.appendChild(hInput);
                } else {
                    document.getElementById('hidden_edit_id').value = config.id;
                }

                // Scroll to form
                document.querySelector('.card-body').scrollIntoView({
                    behavior: 'smooth'
                });

                // Change button text to Update
                const submitBtn = document.querySelector('#payrollConfigForm button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-save me-1"></i> Update Configuration';

                Swal.fire({
                    icon: 'info',
                    title: 'Edit Mode',
                    text: 'You can now edit the configuration. Click Update to save changes.',
                    confirmButtonColor: '#3b82f6'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Error loading configuration details',
                confirmButtonColor: '#3b82f6'
            });
        });
}

// Delete configuration
function deleteConfig(id) {
    Swal.fire({
        title: 'Delete Configuration?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#3b82f6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoader();

            // Delete API call
            fetch(`{{ url("payroll/executions") }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoader();
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Configuration has been deleted.',
                            confirmButtonColor: '#10b981'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Error deleting configuration',
                            confirmButtonColor: '#3b82f6'
                        });
                    }
                })
                .catch(error => {
                    hideLoader();
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Error deleting configuration',
                        confirmButtonColor: '#3b82f6'
                    });
                });
        }
    });
}

// Reset button functionality
document.querySelector('button[type="reset"]').addEventListener('click', function() {
    departmentDiv.classList.add('hidden');
    daysCycleDiv.classList.add('hidden');
    document.querySelector('[name="apply_to"]').value = '';
    document.querySelector('[name="payroll_cycle"]').value = '';

    // Reset edit mode
    const submitBtn = document.querySelector('#payrollConfigForm button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-save me-1"></i> Save Configuration';
    document.getElementById('payrollConfigForm').removeAttribute('data-edit-id');
});
</script>
@endsection