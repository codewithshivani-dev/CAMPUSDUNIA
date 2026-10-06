{{-- resources/views/instituteAdmin/EmployeePromotion/assign_salary_structure.blade.php --}}

@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Assign Salary Structure - {{ $employee->name }}</title>

<style>
    .assign-container {
        background: #fff;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        max-width: 800px;
        margin: 0 auto;
    }

    .employee-header {
        display: flex;
        align-items: center;
        gap: 20px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f1f5f9;
        margin-bottom: 25px;
    }

    .employee-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #0f172a;
        font-size: 14px;
        margin-bottom: 5px;
        display: block;
    }

    .form-control {
        padding: 10px 14px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
        width: 100%;
    }

    .form-control:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .input-group-text {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 8px 0 0 8px;
        font-weight: 600;
        color: #475569;
        padding: 10px 14px;
    }

    .alert-warning-custom {
        background: #fef3c7;
        border: 1px solid #fde68a;
        border-radius: 8px;
        padding: 15px;
        color: #92400e;
        margin-bottom: 20px;
    }

    .btn-assign {
        padding: 12px 40px;
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        width: 100%;
        font-size: 16px;
    }

    .btn-assign:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        color: white;
    }

    .btn-assign:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    .btn-back {
        padding: 10px 25px;
        background: #e2e8f0;
        color: #475569;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .btn-back:hover {
        background: #cbd5e1;
        color: #1e293b;
        text-decoration: none;
    }

    .structure-preview {
        background: #f8fafc;
        border-radius: 8px;
        padding: 15px;
        margin-top: 10px;
        border: 1px solid #e2e8f0;
    }

    .structure-preview .row {
        margin-bottom: 8px;
    }

    .structure-preview .label {
        color: #64748b;
        font-size: 13px;
    }

    .structure-preview .value {
        font-weight: 600;
        color: #0f172a;
    }

    .inactive-structures {
        background: #fff8f8;
        border: 1px solid #fecaca;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
    }

    .inactive-structures .structure-item {
        padding: 10px;
        border-bottom: 1px solid #fee2e2;
        font-size: 13px;
    }

    .inactive-structures .structure-item:last-child {
        border-bottom: none;
    }

    @media (max-width: 768px) {
        .employee-header {
            flex-direction: column;
            text-align: center;
        }
        .assign-container {
            padding: 20px;
        }
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="assign-container">
                <!-- Header -->
                <div class="employee-header">
                    <div class="employee-avatar">
                        {{ strtoupper(substr($employee->name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $employee->name }}</h3>
                        <div class="text-muted">
                            <i class="fas fa-id-badge me-1"></i> {{ $employee->employee_code }} &nbsp;|&nbsp;
                            <i class="fas fa-briefcase me-1"></i> {{ $employee->designation ?? 'N/A' }} &nbsp;|&nbsp;
                            <i class="fas fa-user-tag me-1"></i> {{ $employee->employment_type ?? 'N/A' }}
                        </div>
                    </div>
                    <div style="margin-left: auto;">
                        <a href="{{ route('employee.promotion.index') }}" class="btn-back">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>

                <!-- Warning if already has active structure -->
                @if($hasActiveSalaryStructure)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Note:</strong> This employee already has an active salary structure. 
                    Please inactivate it first if you want to assign a new one.
                </div>
                @endif

                <!-- Main Form -->
                <form id="salaryStructureForm">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                    <div class="alert-warning-custom">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Important:</strong> Assigning a new salary structure will create a new record and log this action in the employee's promotion history.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Basic Salary (Monthly) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control" name="basic_salary_monthly" id="basic_salary_monthly" 
                                        step="0.01" min="0" required placeholder="0.00" 
                                        oninput="calculateTotals()">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Basic Salary (Annual) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control" name="basic_salary_annual" id="basic_salary_annual" 
                                        step="0.01" min="0" required placeholder="0.00"
                                        oninput="calculateTotals()">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Fixed CTC (Annual) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control" name="fixed_ctc_annual" id="fixed_ctc_annual" 
                                        step="0.01" min="0" required placeholder="0.00"
                                        oninput="calculateTotals()">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Variable CTC (Annual) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control" name="variable_ctc_annual" id="variable_ctc_annual" 
                                        step="0.01" min="0" required placeholder="0.00"
                                        oninput="calculateTotals()">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Total CTC (Annual) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" class="form-control" name="total_ctc_annual" id="total_ctc_annual" 
                                step="0.01" min="0" required placeholder="0.00" readonly>
                        </div>
                        <small class="text-muted">Auto-calculated as Fixed CTC + Variable CTC</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Effective From <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="effective_from" id="effective_from" required>
                        <small class="text-muted">Date from which this salary structure will be effective</small>
                    </div>

                    <!-- Preview -->
                    <div class="structure-preview" id="structurePreview" style="display: none;">
                        <p class="mb-2"><strong>Salary Structure Preview</strong></p>
                        <div class="row">
                            <div class="col-md-6">
                                <span class="label">Basic Monthly:</span>
                                <span class="value" id="previewBasicMonthly">₹0.00</span>
                            </div>
                            <div class="col-md-6">
                                <span class="label">Basic Annual:</span>
                                <span class="value" id="previewBasicAnnual">₹0.00</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <span class="label">Fixed CTC:</span>
                                <span class="value" id="previewFixedCTC">₹0.00</span>
                            </div>
                            <div class="col-md-6">
                                <span class="label">Variable CTC:</span>
                                <span class="value" id="previewVariableCTC">₹0.00</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <span class="label">Total CTC:</span>
                                <span class="value" id="previewTotalCTC">₹0.00</span>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn-assign mt-3" onclick="assignSalaryStructure()">
                        <i class="fas fa-check-circle me-1"></i> Assign Salary Structure
                    </button>
                </form>

                <!-- Inactive Structures History -->
                @if($inactiveStructures->count() > 0)
                <div class="inactive-structures mt-4">
                    <p class="mb-2"><strong>Previous Inactive Structures</strong></p>
                    @foreach($inactiveStructures as $structure)
                    <div class="structure-item">
                        <strong>#{{ $structure->salary_structure_id }}</strong>
                        <span class="text-muted">|</span>
                        Basic: ₹{{ number_format($structure->basic_salary_monthly, 2) }}/month
                        <span class="text-muted">|</span>
                        Total CTC: ₹{{ number_format($structure->total_ctc_annual, 2) }}/year
                        <span class="text-muted float-end">
                            Inactivated: {{ \Carbon\Carbon::parse($structure->updated_at)->format('d-m-Y H:i') }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default effective date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('effective_from').value = today;
});

function calculateTotals() {
    const fixedCTC = parseFloat(document.getElementById('fixed_ctc_annual').value) || 0;
    const variableCTC = parseFloat(document.getElementById('variable_ctc_annual').value) || 0;
    const totalCTC = fixedCTC + variableCTC;
    
    document.getElementById('total_ctc_annual').value = totalCTC.toFixed(2);

    // Update preview
    const basicMonthly = parseFloat(document.getElementById('basic_salary_monthly').value) || 0;
    const basicAnnual = parseFloat(document.getElementById('basic_salary_annual').value) || 0;

    document.getElementById('previewBasicMonthly').textContent = '₹' + basicMonthly.toFixed(2);
    document.getElementById('previewBasicAnnual').textContent = '₹' + basicAnnual.toFixed(2);
    document.getElementById('previewFixedCTC').textContent = '₹' + fixedCTC.toFixed(2);
    document.getElementById('previewVariableCTC').textContent = '₹' + variableCTC.toFixed(2);
    document.getElementById('previewTotalCTC').textContent = '₹' + totalCTC.toFixed(2);

    // Show preview if any value is entered
    const hasValues = basicMonthly > 0 || basicAnnual > 0 || fixedCTC > 0 || variableCTC > 0;
    document.getElementById('structurePreview').style.display = hasValues ? 'block' : 'none';
}

function getConfirmationToken() {
    return fetch('{{ route("employee.promotion.confirmation-token") }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            return data.confirmation_token;
        }
        throw new Error('Failed to get confirmation token');
    });
}

function assignSalaryStructure() {
    const form = document.getElementById('salaryStructureForm');
    const formData = new FormData(form);
    const employeeId = formData.get('employee_id');

    const basicMonthly = formData.get('basic_salary_monthly');
    const basicAnnual = formData.get('basic_salary_annual');
    const fixedCTC = formData.get('fixed_ctc_annual');
    const variableCTC = formData.get('variable_ctc_annual');
    const totalCTC = formData.get('total_ctc_annual');
    const effectiveFrom = formData.get('effective_from');

    if (!basicMonthly || !basicAnnual || !fixedCTC || !variableCTC || !totalCTC || !effectiveFrom) {
        Swal.fire({
            title: 'Error!',
            text: 'Please fill in all required fields.',
            icon: 'error',
            confirmButtonColor: '#dc2626'
        });
        return;
    }

    // Double confirmation
    Swal.fire({
        title: '⚠️ Confirm Assignment',
        html: `
            <p>You are about to assign a new salary structure to <strong>{{ $employee->name }}</strong></p>
            <p><strong>Details:</strong></p>
            <div style="text-align: left; background: #f8fafc; padding: 10px; border-radius: 8px;">
                <p><strong>Basic Monthly:</strong> ₹${parseFloat(basicMonthly).toFixed(2)}</p>
                <p><strong>Basic Annual:</strong> ₹${parseFloat(basicAnnual).toFixed(2)}</p>
                <p><strong>Fixed CTC:</strong> ₹${parseFloat(fixedCTC).toFixed(2)}</p>
                <p><strong>Variable CTC:</strong> ₹${parseFloat(variableCTC).toFixed(2)}</p>
                <p><strong>Total CTC:</strong> ₹${parseFloat(totalCTC).toFixed(2)}</p>
                <p><strong>Effective From:</strong> ${effectiveFrom}</p>
            </div>
            <p class="text-danger mt-2"><small>This action will be logged in the employee's promotion history.</small></p>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4361ee',
        cancelButtonColor: '#dc2626',
        confirmButtonText: 'Yes, Assign',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            processSalaryStructureAssignment(formData, employeeId);
        }
    });
}

function processSalaryStructureAssignment(formData, employeeId) {
    Swal.fire({
        title: 'Processing...',
        text: 'Please wait while we assign the salary structure.',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => { Swal.showLoading(); }
    });

    getConfirmationToken().then(token => {
        formData.append('confirmation_token', token);

        fetch(`/employee-promotion/${employeeId}/assign-salary-structure`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    html: `
                        ${data.message}<br><br>
                        <strong>Structure ID:</strong> #${data.salary_structure_id}<br>
                        <strong>Total CTC:</strong> ₹${parseFloat(data.salary_structure.total_ctc_annual).toFixed(2)}
                    `,
                    icon: 'success',
                    confirmButtonColor: '#4361ee',
                    timer: 4000,
                    timerProgressBar: true
                }).then(() => {
                    window.location.href = data.redirect_url || '{{ route("employee.promotion.index") }}';
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message || 'Failed to assign salary structure.',
                    icon: 'error',
                    confirmButtonColor: '#dc2626'
                });
            }
        })
        .catch(error => {
            Swal.close();
            Swal.fire({
                title: 'Error!',
                text: 'Something went wrong. Please try again.',
                icon: 'error',
                confirmButtonColor: '#dc2626'
            });
        });
    }).catch(error => {
        Swal.close();
        Swal.fire({
            title: 'Error!',
            text: 'Failed to generate confirmation token. Please try again.',
            icon: 'error',
            confirmButtonColor: '#dc2626'
        });
    });
}
</script>

@endsection