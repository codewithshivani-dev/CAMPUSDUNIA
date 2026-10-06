@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
.card-body {
    padding: 2rem 2.5rem;
}

.card {
    border-radius: 12px;
    border: 1px solid #e0e0e0;
}
.card-header {
    border-radius: 12px 12px 0 0 !important;
}
.input-group-text {
    border-right: none;
    background-color: #f8f9fa;
}
.form-control:focus, .form-select:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    border-color: #86b7fe;
}
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 10px 24px;
    border-radius: 8px;
    font-weight: 500;
}
.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4090 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}
.form-label {
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 8px;
}
.alert-info {
    background-color: #f0f9ff;
    border: 1px solid #b6e0fe;
    border-radius: 8px;
}
.badge.bg-primary {
    background-color: #667eea !important;
}
.fee-type-option {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.fee-type-badge {
    font-size: 0.75rem;
    padding: 0.2rem 0.5rem;
}
</style>
<div class="container-fluid">
      @php
        $instituteType = $instituteType ?? '';
        $courseLabel = ($instituteType === 'School') ? 'Class' : 'Course';
    @endphp
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-tag me-2"></i>Create New Discount</h4>
                        <a href="{{ route('discounts.list') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info border-0 bg-light-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Create a new discount that can be assigned later.
                    </div>
                    
                    <form id="createDiscountForm" class="mt-3">
                        @csrf
                        
                        <!-- Discount Name & Type -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Discount Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-heading text-primary"></i>
                                    </span>
                                    <input type="text" name="name" class="form-control" 
                                           placeholder="Enter discount name" required>
                                </div>
                                <small class="text-muted">e.g., Summer Special, Early Bird Discount</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Discount Type <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-percentage text-primary"></i>
                                    </span>
                                    <select name="type" class="form-control" required>
                                        <option value="">Select Type</option>
                                        <option value="flat">Flat Amount</option>
                                        <option value="percentage">Percentage</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Discount Value -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Discount Value <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-rupee-sign text-primary"></i>
                                    </span>
                                    <input type="number" step="0.01" name="value" class="form-control" 
                                           placeholder="Enter discount value" required>
                                    <span class="input-group-text bg-light">
                                        <span id="valueTypeLabel">Amount/Percentage</span>
                                    </span>
                                </div>
                                <small class="text-muted" id="valueHelpText">
                                    Enter the discount amount or percentage value
                                </small>
                            </div>
                        </div>
                        
                        <!-- Fee Type Selection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Applicable Fee Type <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-money-bill-wave text-primary"></i>
                                    </span>
                                    <select name="fee_type" class="form-control" required>
                                        <option value="">Select Fee Type</option>
                                        <option value="course">Class Fee</option>
                                        <option value="hostel">Hostel Fee</option>
                                        <option value="transportation">Transportation Fee</option>
                                        <option value="registration">Registration Fee</option>
                                        <option value="custom">Custom Fee (Select later)</option>
                                    </select>
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-lightbulb text-warning me-1"></i>
                                    Select the type of fee this discount applies to
                                </div>
                            </div>
                        </div>
                        
                        <!-- Custom Fee Selection (Conditional) -->
                        <div class="row mb-4" id="customFeeSection" style="display: none;">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Select Custom Fee</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-list text-primary"></i>
                                    </span>
                                    <select name="custom_fee_id" class="form-control" id="customFeeSelect">
                                        <option value="">Select Custom Fee</option>
                                        <!-- This will be populated dynamically via AJAX or from controller -->
                                    </select>
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-lightbulb text-warning me-1"></i>
                                    Select a specific custom fee from your fee structure
                                </div>
                            </div>
                        </div>
                        
                        <!-- Validity Period -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Valid From <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-calendar-alt text-primary"></i>
                                    </span>
                                    <input type="date" name="valid_from" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Valid To <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-calendar-check text-primary"></i>
                                    </span>
                                    <input type="date" name="valid_to" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Usage Limit -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Maximum Usage Limit</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-hashtag text-primary"></i>
                                    </span>
                                    <input type="number" name="max_usage" class="form-control" 
                                           placeholder="Enter maximum usage count">
                                    <span class="input-group-text bg-light">times</span>
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-lightbulb text-warning me-1"></i>
                                    Leave empty for unlimited usage
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light align-items-start pt-2">
                                    <i class="fas fa-align-left text-primary"></i>
                                </span>
                                <textarea name="description" class="form-control" rows="3" 
                                          placeholder="Enter discount description (optional)"></textarea>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-redo me-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-plus-circle me-1"></i> Create Discount
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="card mt-4 border-0 shadow-sm">
                <div class="card-body bg-light">
                    <h6 class="mb-3"><i class="fas fa-lightbulb text-warning me-2"></i>How it works?</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <span class="badge bg-primary rounded-circle p-2">1</span>
                                </div>
                                <div>
                                    <small class="fw-semibold">Create Discount</small>
                                    <p class="text-muted small mb-0">First create discount with basic details</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <span class="badge bg-primary rounded-circle p-2">2</span>
                                </div>
                                <div>
                                    <small class="fw-semibold">Assign Later</small>
                                    <p class="text-muted small mb-0">Assign to categories, departments, or students later</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <span class="badge bg-primary rounded-circle p-2">3</span>
                                </div>
                                <div>
                                    <small class="fw-semibold">Track Usage</small>
                                    <p class="text-muted small mb-0">Monitor discount usage and effectiveness</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Update input label based on discount type
    $('select[name="type"]').change(function() {
        const type = $(this).val();
        const $valueLabel = $('#valueTypeLabel');
        const $helpText = $('#valueHelpText');
        
        if (type === 'percentage') {
            $valueLabel.text('%');
            $helpText.text('Enter percentage value (e.g., 10 for 10% discount)');
        } else if (type === 'flat') {
            $valueLabel.text('₹');
            $helpText.text('Enter flat amount (e.g., 500 for ₹500 discount)');
        } else {
            $valueLabel.text('Amount/Percentage');
            $helpText.text('Enter the discount amount or percentage value');
        }
    });
    
    // Show/hide custom fee section based on fee type selection
    $('select[name="fee_type"]').change(function() {
        const feeType = $(this).val();
        const $customFeeSection = $('#customFeeSection');
        const $customFeeSelect = $('#customFeeSelect');
        
        if (feeType === 'custom') {
            $customFeeSection.show();
            $customFeeSelect.prop('required', true);
            
            // Load custom fees via AJAX if needed
            loadCustomFees();
        } else {
            $customFeeSection.hide();
            $customFeeSelect.prop('required', false);
        }
    });
    

  // Function to load custom fees
function loadCustomFees() {
    const $select = $('#customFeeSelect');
    
    // Clear existing options except the first one
    $select.empty();
    $select.append('<option value="">Select Custom Fee</option>');
    
    // Show loading state
    $select.prop('disabled', true);
    $select.append('<option value="" disabled>Loading custom fees...</option>');
    
    // Load custom fees from controller via AJAX
    $.ajax({
        url: "{{ route('get.custom.fees') }}",
        method: 'GET',
        success: function(response) {
            $select.empty();
            $select.append('<option value="">Select Custom Fee</option>');
            
            if (response.success && response.fees && response.fees.length > 0) {
                response.fees.forEach(function(fee) {
                    $select.append(
                        `<option value="${fee.custom_reference_id}">${fee.custom_fee_key} (₹${fee.custom_fee_value})</option>`
                    );
                });
                $select.prop('disabled', false);
            } else {
                // No custom fees found
                $select.append('<option value="" disabled>No custom fees available</option>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading custom fees:', error);
            $select.empty();
            $select.append('<option value="">Select Custom Fee</option>');
            $select.append('<option value="" disabled>Failed to load custom fees. Please try again.</option>');
        },
        complete: function() {
            $select.prop('disabled', false);
        }
    });
}
    
    // Set default dates
    const today = new Date().toISOString().split('T')[0];
    const nextMonth = new Date();
    nextMonth.setMonth(nextMonth.getMonth() + 1);
    const nextMonthStr = nextMonth.toISOString().split('T')[0];
    
    $('input[name="valid_from"]').val(today);
    $('input[name="valid_to"]').val(nextMonthStr);
    
    // Form submission
    $('#createDiscountForm').submit(function(e) {
        e.preventDefault();
        
        // Validate fee type
        const feeType = $('select[name="fee_type"]').val();
        if (!feeType) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please select a fee type',
                confirmButtonColor: '#667eea'
            });
            return;
        }
        
        // Validate custom fee if selected
        if (feeType === 'custom') {
            const customFeeId = $('select[name="custom_fee_id"]').val();
            if (!customFeeId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please select a custom fee',
                    confirmButtonColor: '#667eea'
                });
                return;
            }
        }
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i> Creating...');
        submitBtn.prop('disabled', true);
        
        $.ajax({
            url: "{{ route('discounts.store') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = response.redirect;
                    });
                }
            },
            error: function(xhr) {
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
                
                if (xhr.status === 422) {
                    let errorMessages = '';
                    const errors = xhr.responseJSON.errors;
                    Object.values(errors).forEach(error => {
                        errorMessages += error + '\n';
                    });
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: errorMessages,
                        confirmButtonColor: '#667eea'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred. Please try again.',
                        confirmButtonColor: '#667eea'
                    });
                }
            }
        });
    });
    
    // Reset button
    $('button[type="reset"]').click(function() {
        // Reset dates to defaults
        $('input[name="valid_from"]').val(today);
        $('input[name="valid_to"]').val(nextMonthStr);
        
        // Reset type selector
        $('select[name="type"]').val('');
        $('#valueTypeLabel').text('Amount/Percentage');
        $('#valueHelpText').text('Enter the discount amount or percentage value');
        
        // Reset fee type
        $('select[name="fee_type"]').val('');
        $('#customFeeSection').hide();
        $('#customFeeSelect').prop('required', false);
    });
});
</script>

<!-- Add SweetAlert if not already included -->
@if(!isset($sweetalert))
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endif
@endsection