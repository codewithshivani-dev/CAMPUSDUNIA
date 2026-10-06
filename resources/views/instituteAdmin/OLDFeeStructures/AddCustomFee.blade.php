@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3><i class="fas fa-money-bill-wave me-2"></i>Add Custom Fee Structure</h3>
            <a href="{{ route('admin.custom-fees.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Back to List
            </a>
        </div>
        
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

        <form method="POST" action="{{ route('admin.custom-fees.store') }}" id="customFeeForm">
            @csrf
            
            <!-- Basic Information -->
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Academic Year *</label>
                        <select name="academic_year" class="form-control" required>
                            <option value="">Select Academic Year</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year['value'] }}" {{ old('academic_year') == $year['value'] ? 'selected' : '' }}>
                                    {{ $year['label'] }}
                                </option>
                            @endforeach
                        </select>
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
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="mb-3"><i class="fas fa-edit me-2"></i>Custom Fee Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Custom Fee Name *</label>
                        <input type="text" name="custom_fee_key" class="form-control" 
                               placeholder="e.g., ID Card Fee, Lab Fee, Sports Fee, etc." 
                               value="{{ old('custom_fee_key') }}" required>
                        <small class="text-muted">Enter a descriptive name for this custom fee</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">One-Time Fee Amount *</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" name="custom_fee_value" id="custom_fee_value" class="form-control" 
                                   min="0" step="0.01" placeholder="Enter one-time fee amount" 
                                   value="{{ old('custom_fee_value') }}" required>
                        </div>
                        <small class="text-muted">This is the base one-time fee amount</small>
                    </div>
                </div>
            </div>

            <!-- Late Fee Configuration -->
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="mb-3"><i class="fas fa-clock me-2"></i>Late Fee Configuration (Optional)</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Late Fee Type</label>
                        <select name="late_fee_type" id="late_fee_type" class="form-control">
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
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h5 class="mb-3"><i class="fas fa-percentage me-2"></i>Partial Fee Configuration (Optional)</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Partial Fee Type</label>
                        <select name="partially_fee_type" id="partially_fee_type" class="form-control">
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
            <div class="card border-light shadow-sm p-4 mb-4">
                <h5 class="mb-3"><i class="fas fa-eye me-2"></i>Fee Preview</h5>
                <div class="fee-preview">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-label">Fee Name:</div>
                            <div id="preview_fee_name" class="fw-semibold">-</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label">Fee Type:</div>
                            <div id="preview_fee_type" class="fw-semibold">Custom Fee</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-label">Base Amount:</div>
                            <div id="preview_base_amount" class="fw-semibold">₹0.00</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label">Late Fee:</div>
                            <div id="preview_late_fee" class="fw-semibold">₹0.00</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-label">Partial Fee:</div>
                            <div id="preview_partial_fee" class="fw-semibold">₹0.00</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label">Total One-Time Fee:</div>
                            <div id="preview_total">₹0.00</div>
                        </div>
                    </div>
                    <div class="small text-white">
                        <i class="fas fa-info-circle me-1"></i>
                        Note: Discount will be applied when assigning to students/employees
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="button" class="btn btn-outline-secondary me-2" onclick="resetForm()">
                    <i class="fas fa-redo me-1"></i>Reset
                </button>
                <button type="submit" class="btn btn-success px-4" id="submitBtn">
                    <i class="fas fa-save me-2"></i>Create Custom Fee
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .fee-preview {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 25px;
    }
    
    .fee-preview .form-label {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.9rem;
        margin-bottom: 5px;
    }
    
    #preview_fee_name, #preview_fee_type, #preview_base_amount,
    #preview_late_fee, #preview_partial_fee, #preview_total {
        color: white;
    }
    
    #preview_total {
        font-size: 1.8rem;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize all fields
        toggleLateFeeField();
        togglePartialFeeField();
        updatePreview();
        
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
    });
    
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
            {id: 'custom_fee_key', name: 'Custom Fee Name'},
            {id: 'custom_fee_value', name: 'Fee Amount'},
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