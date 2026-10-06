@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<title>Add Custom Fee Structure</title>
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --success-gradient: linear-gradient(135deg, #10b981, #059669);
        --success-color: #10b981;
        --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --card-shadow: 0 6px 20px rgba(0,0,0,0.05);
        --hover-shadow: 0 10px 30px rgba(67, 97, 238, 0.1);
    }

    /* Page Header */
    .page-header {
        background: var(--primary-gradient);
        padding: 25px;
        border-radius: 16px;
        margin-bottom: 24px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: white;
        margin: 0;
    }

    .back-btn {
        padding: 12px 24px;
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
        cursor: pointer;
        border-radius: 12px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        text-decoration: none;
    }

    .back-btn:hover {
        background: white;
        color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        text-decoration: none;
    }

    /* Form Card */
    .form-card {
        background: #fff;
        border-radius: 16px;
        padding: 30px;
        border: 2px solid var(--border-color);
        box-shadow: var(--card-shadow);
        margin-bottom: 24px;
        transition: all 0.3s;
    }

    .form-card:hover {
        box-shadow: var(--hover-shadow);
        border-color: var(--primary-color);
    }

    .card-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border-color);
    }

    .card-title i {
        color: var(--primary-color);
        margin-right: 8px;
    }

    /* Form Elements */
    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 14px;
        background: #fff;
        transition: all 0.3s;
        height: 48px;
        box-sizing: border-box;
        color: var(--text-dark);
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        transform: translateY(-1px);
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    textarea.form-control {
        height: auto;
        min-height: 100px;
        resize: vertical;
    }

    .input-group {
        display: flex;
        align-items: stretch;
    }

    .input-group-text {
        padding: 12px 16px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px solid var(--border-color);
        border-right: none;
        border-radius: 12px 0 0 12px;
        font-weight: 600;
        color: var(--primary-color);
        font-size: 14px;
        display: flex;
        align-items: center;
    }

    .input-group .form-control {
        border-radius: 0 12px 12px 0;
    }

    .text-muted {
        color: var(--text-muted) !important;
        font-size: 12px;
        margin-top: 6px;
    }

    /* Fee Preview */
    .fee-preview-card {
        background: var(--primary-gradient);
        color: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
        margin-bottom: 24px;
    }

    .fee-preview-card .preview-label {
        color: rgba(255, 255, 255, 0.9);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .fee-preview-card .preview-value {
        color: white;
        font-size: 16px;
        font-weight: 600;
    }

    .fee-preview-card .preview-total {
        font-size: 28px;
        font-weight: 700;
        color: white;
    }

    .preview-note {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 10px;
        padding: 12px 16px;
        margin-top: 20px;
        font-size: 13px;
        backdrop-filter: blur(10px);
    }

    /* Alert Styles */
    .alert {
        border-radius: 14px;
        border: none;
        padding: 14px 18px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background: var(--success-gradient);
        color: white;
    }

    .alert-danger {
        background: var(--danger-gradient);
        color: white;
    }

    .alert ul {
        margin-bottom: 0;
        padding-left: 20px;
    }

    .btn-close-white {
        filter: brightness(0) invert(1);
    }

    /* Buttons */
    .btn {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        height: 48px;
        box-sizing: border-box;
        text-decoration: none;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .btn-success {
        background: var(--success-gradient);
        color: white;
        border-color: transparent;
    }

    .btn-success:hover {
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    }

    .btn-outline-secondary {
        background: #f1f5f9;
        color: #475569;
        border-color: var(--border-color);
    }

    .btn-outline-secondary:hover {
        background: #e2e8f0;
        color: #475569;
    }

    .btn-outline-primary {
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-outline-primary:hover {
        background: var(--primary-color);
        color: white;
    }

    /* Invalid Feedback */
    .is-invalid {
        border-color: #dc2626 !important;
    }

    .invalid-feedback {
        color: #dc2626;
        font-size: 12px;
        margin-top: 5px;
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-card {
            padding: 20px;
        }
        
        .page-header {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title"><i class="fas fa-money-bill-wave me-2"></i>Add Custom Fee Structure</h1>
        <a href="{{ route('admin.custom-fees.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to List
        </a>
    </div>

    {{-- Messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
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
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.custom-fees.store') }}" id="customFeeForm">
        @csrf
        
        <!-- Basic Information -->
        <div class="form-card">
            <h5 class="card-title"><i class="fas fa-info-circle"></i>Basic Information</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Academic Year *</label>
                    <select name="academic_year" class="form-select" required>
                        <option value="">Select Academic Year</option>
                        @foreach ($academicYears as $year)
                            <option value="{{ $year['value'] }}" {{ old('academic_year') == $year['value'] ? 'selected' : '' }}>
                                {{ $year['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Fee Frequency *</label>
                    <select name="fee_type" class="form-select" required>
                        <option value="">Select Fee Frequency</option>
                        <option value="one_time" {{ old('fee_type') == 'one_time' ? 'selected' : '' }}>One Time Fee</option>
                        <option value="recurring" {{ old('fee_type') == 'recurring' ? 'selected' : '' }}>Recurring Fee</option>
                    </select>
                </div>
            </div> 
            
            <!-- Recurring Options (Conditional) -->
            <div class="row" id="recurringOptions" style="display: none;">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Recurrence Period</label>
                    <select name="recurrence_period" id="recurrence_period" class="form-select">
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="half_yearly">Half Yearly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>
            </div>
            
            <!-- Date Fields -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Start Date *</label>
                    <input type="date" name="start_date" class="form-control" 
                           value="{{ old('start_date', date('Y-m-d')) }}" required>
                    <small class="text-muted">When this fee becomes applicable</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Due Date *</label>
                    <input type="date" name="due_date" class="form-control" 
                           value="{{ old('due_date') }}" required>
                    <small class="text-muted">Last date for payment without late fee</small>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" 
                              placeholder="Enter description for this fee structure">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Custom Fee Information -->
        <div class="form-card">
            <h5 class="card-title"><i class="fas fa-edit"></i>Custom Fee Information</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Custom Fee Name *</label>
                    <input type="text" name="custom_fee_key" class="form-control" 
                           placeholder="e.g., ID Card Fee, Lab Fee, Sports Fee, etc." 
                           value="{{ old('custom_fee_key') }}" required>
                    <small class="text-muted">Enter a descriptive name for this custom fee</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Fee Amount *</label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="number" name="custom_fee_value" id="custom_fee_value" class="form-control" 
                               min="0" step="0.01" placeholder="Enter fee amount" 
                               value="{{ old('custom_fee_value') }}" required>
                    </div>
                    <small class="text-muted" id="feeTypeHint">This is the one-time fee amount</small>
                </div>
            </div>
        </div>

        <!-- Late Fee Configuration -->
        <div class="form-card">
            <h5 class="card-title"><i class="fas fa-clock"></i>Late Fee Configuration (Optional)</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Late Fee Type</label>
                    <select name="late_fee_type" id="late_fee_type" class="form-select">
                        <option value="none" {{ old('late_fee_type', 'none') == 'none' ? 'selected' : '' }}>No Late Fee</option>
                        <option value="fixed" {{ old('late_fee_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                        <option value="percentage" {{ old('late_fee_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Late Fee Value</label>
                    <div class="input-group">
                        <input type="number" name="late_fee_value" id="late_fee_value" 
                               class="form-control" min="0" step="0.01" 
                               placeholder="Enter amount/percentage" 
                               value="{{ old('late_fee_value') }}">
                        <span class="input-group-text" id="late_fee_suffix">₹</span>
                    </div>
                    <small class="text-muted" id="late_fee_note">
                        Enter fixed amount or percentage for late payments
                    </small>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Late Fee Description (Optional)</label>
                    <input type="text" name="late_fee_description" class="form-control" 
                           placeholder="e.g., Late fee after due date" 
                           value="{{ old('late_fee_description') }}">
                </div>
            </div>
        </div>

        <!-- Partial Fee Configuration -->
        <div class="form-card">
            <h5 class="card-title"><i class="fas fa-percentage"></i>Partial Fee Configuration (Optional)</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Partial Fee Type</label>
                    <select name="partially_fee_type" id="partially_fee_type" class="form-select">
                        <option value="none" {{ old('partially_fee_type', 'none') == 'none' ? 'selected' : '' }}>No Partial Fee</option>
                        <option value="fixed" {{ old('partially_fee_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                        <option value="percentage" {{ old('partially_fee_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Partial Fee Value</label>
                    <div class="input-group">
                        <input type="number" name="partially_fee_value" id="partially_fee_value" 
                            class="form-control" min="0" step="0.01" 
                            placeholder="Enter amount/percentage" 
                            value="{{ old('partially_fee_value') }}">
                        <span class="input-group-text" id="partially_fee_suffix">₹</span>
                    </div>
                    <small class="text-muted" id="partially_fee_note">
                        Enter fixed amount or percentage for partial payments
                    </small>
                </div>
            </div>
        </div>

        <!-- Preview Section -->
        <div class="fee-preview-card">
            <h5 class="mb-4 text-white"><i class="fas fa-eye me-2"></i>Fee Preview</h5>
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <div class="preview-label">Fee Name</div>
                    <div id="preview_fee_name" class="preview-value">-</div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="preview-label">Fee Type</div>
                    <div id="preview_fee_type" class="preview-value">Custom Fee</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <div class="preview-label">Base Amount</div>
                    <div id="preview_base_amount" class="preview-value">₹0.00</div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="preview-label">Late Fee</div>
                    <div id="preview_late_fee" class="preview-value">₹0.00</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <div class="preview-label">Partial Fee</div>
                    <div id="preview_partial_fee" class="preview-value">₹0.00</div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="preview-label">Total One-Time Fee</div>
                    <div id="preview_total" class="preview-total">₹0.00</div>
                </div>
            </div>
            <div class="preview-note">
                <i class="fas fa-info-circle me-1"></i>
                Note: Discount will be applied when assigning to students/employees
            </div>
        </div>

        <div class="text-end mt-4">
            <button type="button" class="btn btn-outline-secondary me-2" onclick="resetForm()">
                <i class="fas fa-redo me-1"></i>Reset
            </button>
            <button type="submit" class="btn btn-success" id="submitBtn">
                <i class="fas fa-save me-2"></i>Create Custom Fee
            </button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize all fields
        toggleLateFeeField();
        togglePartialFeeField();
        toggleRecurringOptions();
        updatePreview();
            // Set default due date (30 days from now)
        const today = new Date();
        const dueDate = new Date();
        dueDate.setDate(today.getDate() + 30);

        $('[name="start_date"]').val(today.toISOString().split('T')[0]);
        $('[name="due_date"]').val(dueDate.toISOString().split('T')[0]);

        // Event listeners
        $('[name="fee_type"]').on('change', function() {
            toggleRecurringOptions();
            updatePreview();
        });
        
        // Event listeners for preview updates
        $('[name="custom_fee_key"], #custom_fee_value').on('input change', updatePreview);
        
        // Late fee listeners
        $('#late_fee_type').on('change', function() {
            toggleLateFeeField();
            updatePreview();
        });
        
        $('#late_fee_value').on('input', updatePreview);
        
        // Partial fee listeners
        $('#partially_fee_type').on('change', function() {
            togglePartialFeeField();
            updatePreview();
        });
        
        $('#partially_fee_value').on('input', updatePreview);

            // Date validation
        $('[name="start_date"], [name="due_date"]').on('change', function() {
            validateDates();
            updatePreview();
        });
    });
        function toggleRecurringOptions() {
            const feeType = $('[name="fee_type"]').val();
            const recurringSection = $('#recurringOptions');
            const feeHint = $('#feeTypeHint');
            
            if (feeType === 'recurring') {
                recurringSection.show();
                $('[name="recurrence_period"]').prop('required', true);
                $('[name="num_installments"]').prop('required', true);
                feeHint.text('This is the amount per installment');
            } else {
                recurringSection.hide();
                $('[name="recurrence_period"]').prop('required', false);
                $('[name="num_installments"]').prop('required', false);
                feeHint.text('This is the one-time fee amount');
            }
        }
        function toggleLateFeeField() {
            const lateFeeType = $('#late_fee_type').val();
            const input = $('#late_fee_value');
            const suffix = $('#late_fee_suffix');
            const note = $('#late_fee_note');
            
            if (lateFeeType === 'fixed') {
                input.prop('disabled', false);
                input.prop('required', true);
                suffix.text('₹');
                note.text('Enter fixed amount charged for late payments');
            } else if (lateFeeType === 'percentage') {
                input.prop('disabled', false);
                input.prop('required', true);
                suffix.text('%');
                note.text('Enter percentage of base fee charged for late payments');
            } else {
                input.prop('disabled', true);
                input.prop('required', false);
                input.val('');
                suffix.text('₹');
                note.text('Enter fixed amount or percentage for late payments');
            }
        }

        function validateDates() {
            const startDate = new Date($('[name="start_date"]').val());
            const dueDate = new Date($('[name="due_date"]').val());
            
            if (dueDate < startDate) {
                alert('Due date cannot be before start date');
                $('[name="due_date"]').val('');
                $('[name="due_date"]').focus();
                return false;
            }
            
            return true;
        }
    
    function togglePartialFeeField() {
        const partialFeeType = $('#partially_fee_type').val();
        const input = $('#partially_fee_value');
        const suffix = $('#partially_fee_suffix');
        const note = $('#partially_fee_note');
        
        if (partialFeeType === 'fixed') {
            input.prop('disabled', false);
            input.prop('required', true);
            suffix.text('₹');
            note.text('Enter fixed amount charged for partial payments');
        } else if (partialFeeType === 'percentage') {
            input.prop('disabled', false);
            input.prop('required', true);
            suffix.text('%');
            note.text('Enter percentage of base fee charged for partial payments');
        } else {
            input.prop('disabled', true);
            input.prop('required', false);
            input.val('');
            suffix.text('₹');
            note.text('Enter fixed amount or percentage for partial payments');
        }
    }
    
    function updatePreview() {
        // Get all values
        const feeName = $('[name="custom_fee_key"]').val();
        const baseAmount = parseFloat($('#custom_fee_value').val()) || 0;
        
        // Calculate late fee
        let lateFee = 0;
        const lateFeeType = $('#late_fee_type').val();
        const lateFeeValue = parseFloat($('#late_fee_value').val()) || 0;
        
        if (lateFeeType === 'fixed') {
            lateFee = lateFeeValue;
        } else if (lateFeeType === 'percentage') {
            lateFee = (baseAmount * lateFeeValue) / 100;
        }
        
        // Calculate partial fee
        let partialFee = 0;
        const partialFeeType = $('#partially_fee_type').val();
        const partialFeeValue = parseFloat($('#partially_fee_value').val()) || 0;
        
        if (partialFeeType === 'fixed') {
            partialFee = partialFeeValue;
        } else if (partialFeeType === 'percentage') {
            partialFee = (baseAmount * partialFeeValue) / 100;
        }
        
        // Calculate total (including all fees)
        const total = baseAmount;
        
        // Update preview display
        $('#preview_fee_name').text(feeName || '-');
        $('#preview_fee_type').text('Custom Fee');
        $('#preview_base_amount').text('₹' + baseAmount.toFixed(2));
        $('#preview_late_fee').text('₹' + lateFee.toFixed(2));
        $('#preview_partial_fee').text('₹' + partialFee.toFixed(2));
        $('#preview_total').text('₹' + total.toFixed(2));
        
        // Add warnings for high fees
        if (partialFee > (baseAmount * 0.5)) {
            $('#preview_partial_fee').addClass('text-warning').removeClass('text-white');
        } else {
            $('#preview_partial_fee').addClass('text-white').removeClass('text-warning');
        }
        
        if (lateFee > (baseAmount * 0.3)) {
            $('#preview_late_fee').addClass('text-danger').removeClass('text-white');
        } else {
            $('#preview_late_fee').addClass('text-white').removeClass('text-danger');
        }
    }
    
    function resetForm() {
        if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
            $('#customFeeForm')[0].reset();
            toggleLateFeeField();
            togglePartialFeeField();
            updatePreview();
        }
    }
    
    // Form validation
    $('#customFeeForm').on('submit', function(e) {
        e.preventDefault();
        
        // Clear previous validation highlights
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        let hasError = false;
        
        // Validate required fields
          const requiredFields = [
                {id: 'academic_year', name: 'Academic Year'},
                {id: 'fee_type', name: 'Fee Type'},
                {id: 'custom_fee_key', name: 'Custom Fee Name'},
                {id: 'custom_fee_value', name: 'Fee Amount'},
                {id: 'start_date', name: 'Start Date'},
                {id: 'due_date', name: 'Due Date'},
            ];
        
        requiredFields.forEach(field => {
            const element = $(`[name="${field.id}"]`);
            const value = element.val();
            
            if (!value || value.trim() === '') {
                showFieldError(element, `${field.name} is required`);
                hasError = true;
            }
        });
        
        // Validate fee amount
        const feeAmount = parseFloat($('#custom_fee_value').val()) || 0;
        if (feeAmount <= 0) {
            showFieldError($('#custom_fee_value'), 'Please enter a valid fee amount (greater than 0)');
            hasError = true;
        }
        
        // Validate late fee if enabled
        if ($('#late_fee_type').val() !== 'none') {
            const lateFeeValue = parseFloat($('#late_fee_value').val()) || 0;
            if (lateFeeValue <= 0) {
                showFieldError($('#late_fee_value'), 'Please enter a valid late fee value');
                hasError = true;
            }
            
            if ($('#late_fee_type').val() === 'percentage' && lateFeeValue > 100) {
                if (!confirm('Late fee percentage is unusually high (' + lateFeeValue + '%). Continue anyway?')) {
                    showFieldError($('#late_fee_value'), 'Please adjust the late fee percentage');
                    hasError = true;
                }
            }
        }
        
        // Validate partial fee if enabled
        if ($('#partially_fee_type').val() !== 'none') {
            const partialFeeValue = parseFloat($('#partially_fee_value').val()) || 0;
            if (partialFeeValue <= 0) {
                showFieldError($('#partially_fee_value'), 'Please enter a valid partial fee value');
                hasError = true;
            }
            
            if ($('#partially_fee_type').val() === 'percentage' && partialFeeValue > 100) {
                if (!confirm('Partial fee percentage is unusually high (' + partialFeeValue + '%). Continue anyway?')) {
                    showFieldError($('#partially_fee_value'), 'Please adjust the partial fee percentage');
                    hasError = true;
                }
            }
        }
        
        if (hasError) {
            return false;
        }
        
        // Show loading
        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html(
            '<i class="fas fa-spinner fa-spin me-2"></i>Saving...'
        );
        
        // Submit form after 500ms to show loading state
        setTimeout(() => {
            this.submit();
        }, 500);
    });
    
    function showFieldError(element, message) {
        element.addClass('is-invalid');
        element.after('<div class="invalid-feedback">' + message + '</div>');
        element.focus();
    }
</script>
@endsection