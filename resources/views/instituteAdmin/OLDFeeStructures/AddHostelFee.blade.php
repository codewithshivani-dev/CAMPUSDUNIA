@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container {
        margin-top: 30px;
        margin-bottom: 30px;
    }

    .form-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        border: none;
        margin-bottom: 20px;
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 20px 25px;
        border: none;
    }

    .card-header h5 {
        margin: 0;
        font-weight: 600;
    }

    .card-body {
        padding: 25px;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .form-control,
    .form-select {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 6px 15px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.1);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 8px;
        padding: 12px 30px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-outline-secondary {
        border: 2px solid #6c757d;
        border-radius: 8px;
        padding: 12px 25px;
        font-weight: 500;
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        border-radius: 8px;
        padding: 12px 30px;
        font-weight: 600;
    }

    /* Fee Preview Styling */
    .fee-preview {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
    }
    
    .fee-preview .form-label {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.9rem;
        margin-bottom: 5px;
    }
    
    .fee-preview .preview-value {
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .fee-preview #preview_total {
        font-size: 1.8rem;
        font-weight: bold;
    }
    
    .text-warning {
        color: #ffc107 !important;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }
    
    .card-section {
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .card-section h5 {
        color: #0d6efd;
        border-bottom: 2px solid #0d6efd;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
</style>

<div class="container">
    <div class="form-card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-bed me-2"></i>Create Hostel Fee Structure</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('hostel.fees.store') }}" method="POST" id="hostelFeeForm">
                @csrf
                
                <!-- Basic Information -->
                <div class="card-section">
                    <h5><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hostel Name *</label>
                            <input type="text" name="hostel_name" class="form-control" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hostel Type *</label>
                            <select name="hostel_type" class="form-control" required>
                                <option value="">Select Type</option>
                                <option value="boys">Boys Hostel</option>
                                <option value="girls">Girls Hostel</option>
                                <option value="co-ed">Co-Educational Hostel</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Room Type and Monthly Fee -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Room Type *</label>
                            <select name="room_type" class="form-control" required>
                                <option value="">Select Room Type</option>
                                <option value="single">Single Room</option>
                                <option value="double">Double Sharing</option>
                                <option value="triple">Triple Sharing</option>
                                <option value="dormitory">Dormitory (4+ beds)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Monthly Fee (₹) *</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="monthly_fee" id="monthly_fee" class="form-control" 
                                       min="0" step="0.01" placeholder="e.g., 5000" required>
                            </div>
                            <small class="text-muted">Basic monthly accommodation fee</small>
                        </div>
                    </div>
                </div>

                <!-- Additional Charges -->
                <div class="card-section">
                    <h5><i class="fas fa-file-invoice-dollar me-2"></i>Additional Charges (Optional)</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Security Deposit (₹)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="security_deposit" id="security_deposit" class="form-control" 
                                       min="0" step="0.01" placeholder="One-time deposit" value="0">
                            </div>
                            <small class="text-muted">Refundable security deposit (one-time)</small>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Maintenance Fee (₹)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="maintenance_fee" id="maintenance_fee" class="form-control" 
                                       min="0" step="0.01" placeholder="Annual maintenance" value="0">
                            </div>
                            <small class="text-muted">Annual maintenance fee</small>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Utility Charges (₹)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" name="utility_charges" id="utility_charges" class="form-control" 
                                       min="0" step="0.01" placeholder="Annual utilities" value="0">
                            </div>
                            <small class="text-muted">Annual utility charges</small>
                        </div>
                    </div>
                </div>

                <!-- Late Fee Configuration -->
                <div class="card-section">
                    <h5><i class="fas fa-clock me-2"></i>Late Fee Configuration (Optional)</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Late Fee Type</label>
                            <select name="late_fee_type" id="late_fee_type" class="form-control">
                                <option value="none">No Late Fee</option>
                                <option value="fixed">Fixed Amount (per month)</option>
                                <option value="percentage">Percentage (of monthly fee)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Late Fee Value</label>
                            <div class="input-group">
                                <input type="number" name="late_fee_value" id="late_fee_value" 
                                       class="form-control" min="0" step="0.01" 
                                       placeholder="Enter amount/percentage" value="0">
                                <span class="input-group-text" id="late_fee_suffix">₹</span>
                            </div>
                            <small class="text-muted" id="late_fee_note">
                                Enter fixed amount or percentage for late payments
                            </small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Grace Period (Days)</label>
                            <input type="number" name="grace_period" class="form-control" 
                                   min="0" value="5" placeholder="Number of grace days">
                            <small class="text-muted">Number of days before late fee is applied</small>
                        </div>
                    </div>
                </div>

                <!-- Partial Fee Configuration -->
                <div class="card-section">
                    <h5><i class="fas fa-percentage me-2"></i>Partial Fee Configuration (Optional)</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Partial Fee Type</label>
                            <select name="partially_fee_type" id="partially_fee_type" class="form-control">
                                <option value="none">No Partial Fee</option>
                                <option value="fixed">Fixed Amount (per payment)</option>
                                <option value="percentage">Percentage (of installment)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Partial Fee Value</label>
                            <div class="input-group">
                                <input type="number" name="partially_fee_value" id="partially_fee_value" 
                                    class="form-control" min="0" step="0.01" 
                                    placeholder="Enter amount/percentage" value="0">
                                <span class="input-group-text" id="partially_fee_suffix">₹</span>
                            </div>
                            <small class="text-muted" id="partially_fee_note">
                                Enter fixed amount or percentage for partial payments
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Academic Year and Capacity -->
                <div class="card-section">
                    <h5><i class="fas fa-calendar-alt me-2"></i>Academic Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Academic Year *</label>
                            <select name="academic_year" class="form-control" required>
                                <option value="">Select Academic Year</option>
                                @foreach($academicYears as $year)
                                <option value="{{ $year['value'] }}">{{ $year['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total Capacity (Seats) *</label>
                            <input type="number" name="total_capacity" class="form-control" 
                                   min="1" placeholder="e.g., 50" required>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description (Optional)</label>
                            <textarea name="description" class="form-control" rows="3"
                                      placeholder="Optional description about hostel facilities, rules, etc."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Fee Preview -->
                <div class="fee-preview">
                    <h5 class="text-white mb-4"><i class="fas fa-eye me-2"></i>Annual Fee Preview</h5>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-label">Monthly Fee:</div>
                            <div id="preview_monthly_fee" class="preview-value">₹0.00</div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-label">Security Deposit:</div>
                            <div id="preview_security_deposit" class="preview-value">₹0.00</div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-label">Maintenance Fee:</div>
                            <div id="preview_maintenance_fee" class="preview-value">₹0.00</div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-label">Utility Charges:</div>
                            <div id="preview_utility_charges" class="preview-value">₹0.00</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-label">Late Fee (per month):</div>
                            <div id="preview_late_fee" class="preview-value">₹0.00</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label">Partial Fee (per installment):</div>
                            <div id="preview_partial_fee" class="preview-value">₹0.00</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-label">Annual Base Fee:</div>
                            <div id="preview_annual_base" class="preview-value">₹0.00</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label">Total Annual Fee:</div>
                            <div id="preview_total_annual" class="preview-value">₹0.00</div>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top border-white-50">
                        <small class="text-white-75">
                            <i class="fas fa-info-circle me-1"></i>
                            Note: Late and Partial fees will be applied when assigning to students/employees
                        </small>
                    </div>
                </div>

                <!-- Status -->
                <div class="card-section">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">
                            <strong>Make this hostel fee structure active</strong>
                        </label>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('hostel.fees.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                    <button type="button" class="btn btn-warning me-2" onclick="resetForm()">
                        <i class="fas fa-redo me-2"></i>Reset
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save me-2"></i>Create Hostel Structure
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize all fields
    toggleLateFeeField();
    togglePartialFeeField();
    updatePreview();
    
    // Event listeners for preview updates
    $('#monthly_fee, #security_deposit, #maintenance_fee, #utility_charges').on('input', updatePreview);
    
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
    
    // Form validation
    $('#hostelFeeForm').on('submit', function(e) {
        e.preventDefault();
        
        // Clear previous validation
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        let hasError = false;
        
        // Validate required fields
        const requiredFields = [
            {name: 'hostel_name', label: 'Hostel Name'},
            {name: 'hostel_type', label: 'Hostel Type'},
            {name: 'room_type', label: 'Room Type'},
            {name: 'monthly_fee', label: 'Monthly Fee'},
            {name: 'academic_year', label: 'Academic Year'},
            {name: 'total_capacity', label: 'Total Capacity'},
        ];
        
        requiredFields.forEach(field => {
            const element = $(`[name="${field.name}"]`);
            const value = element.val();
            
            if (!value || value.trim() === '') {
                showFieldError(element, `${field.label} is required`);
                hasError = true;
            }
        });
        
        // Validate monthly fee
        const monthlyFee = parseFloat($('#monthly_fee').val()) || 0;
        if (monthlyFee <= 0) {
            showFieldError($('#monthly_fee'), 'Please enter a valid monthly fee (greater than 0)');
            hasError = true;
        }
        
        // Validate capacity
        const capacity = parseFloat($('input[name="total_capacity"]').val()) || 0;
        if (capacity <= 0) {
            showFieldError($('input[name="total_capacity"]'), 'Please enter a valid capacity (greater than 0)');
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
        
        // Submit form
        this.submit();
    });
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
        note.text('Fixed amount charged per month for late payments');
    } else if (lateFeeType === 'percentage') {
        input.prop('disabled', false);
        input.prop('required', true);
        suffix.text('%');
        note.text('Percentage of monthly fee charged for late payments');
    } else {
        input.prop('disabled', true);
        input.prop('required', false);
        input.val('0');
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
        note.text('Fixed amount charged per installment for partial payments');
    } else if (partialFeeType === 'percentage') {
        input.prop('disabled', false);
        input.prop('required', true);
        suffix.text('%');
        note.text('Percentage of installment charged for partial payments');
    } else {
        input.prop('disabled', true);
        input.prop('required', false);
        input.val('0');
        suffix.text('₹');
        note.text('Enter fixed amount or percentage for partial payments');
    }
}

function updatePreview() {
    // Get all values
    const monthlyFee = parseFloat($('#monthly_fee').val()) || 0;
    const securityDeposit = parseFloat($('#security_deposit').val()) || 0;
    const maintenanceFee = parseFloat($('#maintenance_fee').val()) || 0;
    const utilityCharges = parseFloat($('#utility_charges').val()) || 0;
    
    // Calculate annual base fee
    const annualBaseFee = monthlyFee * 12;
    
    // Calculate late fee
    let lateFee = 0;
    const lateFeeType = $('#late_fee_type').val();
    const lateFeeValue = parseFloat($('#late_fee_value').val()) || 0;
    
    if (lateFeeType === 'fixed') {
        lateFee = lateFeeValue;
    } else if (lateFeeType === 'percentage') {
        lateFee = (monthlyFee * lateFeeValue) / 100;
    }
    
    // Calculate partial fee
    let partialFee = 0;
    const partialFeeType = $('#partially_fee_type').val();
    const partialFeeValue = parseFloat($('#partially_fee_value').val()) || 0;
    
    if (partialFeeType === 'fixed') {
        partialFee = partialFeeValue;
    } else if (partialFeeType === 'percentage') {
        // Base on monthly fee for preview
        partialFee = (monthlyFee * partialFeeValue) / 100;
    }
    
    // Calculate total annual fee (excluding late/partial as they're per payment)
    const totalAnnualFee = annualBaseFee + securityDeposit + maintenanceFee + utilityCharges;
    
    // Update preview display
    $('#preview_monthly_fee').text('₹' + monthlyFee.toFixed(2));
    $('#preview_security_deposit').text('₹' + securityDeposit.toFixed(2));
    $('#preview_maintenance_fee').text('₹' + maintenanceFee.toFixed(2));
    $('#preview_utility_charges').text('₹' + utilityCharges.toFixed(2));
    $('#preview_late_fee').text('₹' + lateFee.toFixed(2));
    $('#preview_partial_fee').text('₹' + partialFee.toFixed(2));
    $('#preview_annual_base').text('₹' + annualBaseFee.toFixed(2));
    $('#preview_total_annual').text('₹' + totalAnnualFee.toFixed(2));
    
    // Add warnings for high fees
    if (partialFeeType !== 'none' && partialFeeValue > 0) {
        if (partialFeeType === 'percentage' && partialFeeValue > 50) {
            $('#preview_partial_fee').addClass('text-warning').removeClass('text-white');
        } else if (partialFeeType === 'fixed' && partialFee > (monthlyFee * 0.5)) {
            $('#preview_partial_fee').addClass('text-warning').removeClass('text-white');
        } else {
            $('#preview_partial_fee').addClass('text-white').removeClass('text-warning');
        }
    } else {
        $('#preview_partial_fee').addClass('text-white').removeClass('text-warning');
    }
    
    if (lateFeeType !== 'none' && lateFeeValue > 0) {
        if (lateFeeType === 'percentage' && lateFeeValue > 30) {
            $('#preview_late_fee').addClass('text-danger').removeClass('text-white');
        } else if (lateFeeType === 'fixed' && lateFee > (monthlyFee * 0.3)) {
            $('#preview_late_fee').addClass('text-danger').removeClass('text-white');
        } else {
            $('#preview_late_fee').addClass('text-white').removeClass('text-danger');
        }
    } else {
        $('#preview_late_fee').addClass('text-white').removeClass('text-danger');
    }
}

function resetForm() {
    if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
        $('#hostelFeeForm')[0].reset();
        toggleLateFeeField();
        togglePartialFeeField();
        updatePreview();
    }
}

function showFieldError(element, message) {
    element.addClass('is-invalid');
    element.after('<div class="invalid-feedback">' + message + '</div>');
    element.focus();
}
</script>
@endsection