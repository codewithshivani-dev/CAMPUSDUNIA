@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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
        --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding: 20px 30px;
        background: var(--primary-gradient);
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .page-title i {
        font-size: 32px;
        filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
    }

    /* Back Button */
    .btn-back {
        padding: 10px 20px;
        background: var(--primary-gradient);
        border: none;
        color: #fff!important;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }

    .btn-back:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        text-decoration: none;
    }

    .card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        width:1070px;
    }

    .card-header {
        background: var(--primary-gradient) !important;
        border-bottom: none;
        padding: 20px 30px;
    }

    .card-header h4 {
        font-weight: 700;
        font-size: 24px;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-header h4 i {
        font-size: 28px;
        background: rgba(255, 255, 255, 0.2);
        padding: 8px;
        border-radius: 12px;
    }

    .card-body {
        padding: 30px;
        background: white;
    }

    /* Alert Info */
    .alert-info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border: 1px solid #93c5fd;
        color: #1e40af;
        border-radius: 16px;
        padding: 18px 25px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-info.bg-light-info {
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe) !important;
        border-left: 4px solid var(--primary-color);
    }

    .alert-info i {
        font-size: 20px;
    }

    /* Form Labels */
    .form-label {
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .form-label.fw-semibold {
        color: var(--primary-color);
    }

    .form-label i {
        color: var(--primary-color);
        margin-right: 5px;
    }

    /* Form Controls */
    .form-control,
    .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
        height: auto;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
    }

    .form-control:hover,
    .form-select:hover {
        border-color: var(--secondary-color);
    }

    .input-group-text {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px solid #e2e8f0;
        border-right: none;
        border-radius: 12px 0 0 12px;
        color: var(--primary-color);
        font-weight: 500;
        padding: 0 16px;
    }

    .input-group .form-control {
        border-left: none;
        border-radius: 0 12px 12px 0;
    }

    .input-group .form-control:focus {
        border-left: none;
    }

    /* Buttons */
    .btn {
        border-radius: 12px;
        font-weight: 600;
        padding: 12px 30px;
        transition: all 0.3s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .btn:active {
        transform: translateY(-1px);
    }

    .btn-primary {
        background: var(--primary-gradient);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-primary:hover::before {
        left: 100%;
    }

    .btn-outline-secondary {
        background: transparent;
        border: 2px solid #e2e8f0;
        color: #475569;
    }

    .btn-outline-secondary:hover {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-color: #cbd5e1;
        color: #1e293b;
        transform: translateY(-2px);
    }

    .btn-light {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        border-radius: 10px;
        padding: 8px 16px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-light:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        color: white;
    }

    /* Info Card */
    .card.mt-4 {
        border: 2px solid #e2e8f0 !important;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
    }

    .card.mt-4 .card-body {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9) !important;
        border-radius: 20px;
    }

    .card.mt-4 h6 {
        color: var(--primary-color);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .badge.bg-primary {
        background: var(--primary-gradient) !important;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 14px;
        font-weight: 700;
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
    }

    .d-flex .me-3 {
        margin-right: 15px !important;
    }

    .fw-semibold {
        font-weight: 600;
        color: #1e293b;
    }

    .text-muted.small {
        color: #64748b !important;
        font-size: 12px;
    }

    /* Form Text */
    .form-text {
        margin-top: 8px;
        font-size: 12px;
        color: #64748b;
    }

    .form-text i {
        color: var(--warning-color);
    }

    .text-warning {
        color: #f59e0b !important;
    }

    /* Border Top */
    .border-top {
        border-top: 2px solid #e2e8f0 !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
        }

        .card-body {
            padding: 20px;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .d-md-flex {
            flex-direction: column;
            gap: 10px;
        }

        .col-md-4.mb-3 {
            margin-bottom: 20px !important;
        }
    }

    /* Value Type Label */
    #valueTypeLabel {
        font-weight: 600;
        color: var(--primary-color);
    }

    /* Custom Fee Section */
    #customFeeSection {
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Fee Type Badge */
    .fee-type-badge {
        font-size: 0.75rem;
        padding: 0.2rem 0.5rem;
        border-radius: 30px;
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        color: var(--primary-color);
        font-weight: 600;
    }

    /* Loading Spinner */
    .fa-spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* SweetAlert Customization */
    .swal2-confirm {
        background: var(--primary-gradient) !important;
        border: none !important;
        border-radius: 10px !important;
    }
</style>

<div class="container-fluid">
    @php
        $instituteType = $instituteType ?? '';
        $courseLabel = ($instituteType === 'School') ? 'Class' : 'Course';
    @endphp
    
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-tag-fill"></i>
            Create New Discount
        </h1>
        <a href="{{ route('discounts.list') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i>
            Back to List
        </a>
    </div>

    <div class="row">
        <div class="col-12 col-lg-10 col-xl-9">
            <!-- Main Card -->
            <div class="card shadow border-0">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="bi bi-tag-fill"></i>
                            Discount Configuration
                        </h4>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info border-0 bg-light-info">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Create a new discount that can be assigned later to categories, departments, or individuals.
                    </div>
                    
                    <form id="createDiscountForm" class="mt-3">
                        @csrf
                        
                        <!-- Discount Name & Type -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-tag"></i> Discount Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-type text-primary"></i>
                                    </span>
                                    <input type="text" name="name" class="form-control" 
                                           placeholder="e.g., Summer Special, Early Bird Discount" required>
                                </div>
                                <small class="text-muted">Enter a descriptive name for the discount</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-percent"></i> Discount Type <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-arrow-repeat text-primary"></i>
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
                                <label class="form-label fw-semibold"><i class="bi bi-cash-stack"></i> Discount Value <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-currency-rupee text-primary"></i>
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
                                <label class="form-label fw-semibold"><i class="bi bi-building-fill"></i> Applicable Fee Type <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-cash-stack text-primary"></i>
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
                                    <i class="bi bi-lightbulb-fill text-warning me-1"></i>
                                    Select the type of fee this discount applies to
                                </div>
                            </div>
                        </div>
                        
                        <!-- Custom Fee Selection (Conditional) -->
                        <div class="row mb-4" id="customFeeSection" style="display: none;">
                            <div class="col-12">
                                <label class="form-label fw-semibold"><i class="bi bi-list-ul"></i> Select Custom Fee</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-tags text-primary"></i>
                                    </span>
                                    <select name="custom_fee_id" class="form-control" id="customFeeSelect">
                                        <option value="">Select Custom Fee</option>
                                        <!-- This will be populated dynamically via AJAX or from controller -->
                                    </select>
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-lightbulb-fill text-warning me-1"></i>
                                    Select a specific custom fee from your fee structure
                                </div>
                            </div>
                        </div>
                        
                        <!-- Validity Period -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-calendar-fill"></i> Valid From <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-calendar text-primary"></i>
                                    </span>
                                    <input type="date" name="valid_from" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold"><i class="bi bi-calendar-fill"></i> Valid To <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-calendar-check text-primary"></i>
                                    </span>
                                    <input type="date" name="valid_to" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Usage Limit -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label fw-semibold"><i class="bi bi-hash"></i> Maximum Usage Limit</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-123 text-primary"></i>
                                    </span>
                                    <input type="number" name="max_usage" class="form-control" 
                                           placeholder="Enter maximum usage count">
                                    <span class="input-group-text bg-light">times</span>
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-lightbulb-fill text-warning me-1"></i>
                                    Leave empty for unlimited usage
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold"><i class="bi bi-pencil-fill"></i> Description</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light align-items-start pt-2">
                                    <i class="bi bi-align-start text-primary"></i>
                                </span>
                                <textarea name="description" class="form-control" rows="3" 
                                          placeholder="Enter discount description (optional)"></textarea>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-repeat me-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-plus-circle-fill me-1"></i> Create Discount
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="card mt-4 border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3"><i class="bi bi-lightbulb-fill text-warning me-2"></i>How it works?</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <span class="badge bg-primary rounded-circle p-2">1</span>
                                </div>
                                <div>
                                    <span class="fw-semibold">Create Discount</span>
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
                                    <span class="fw-semibold">Assign Later</span>
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
                                    <span class="fw-semibold">Track Usage</span>
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
            $customFeeSection.slideDown(300);
            $customFeeSelect.prop('required', true);
            
            // Load custom fees via AJAX if needed
            loadCustomFees();
        } else {
            $customFeeSection.slideUp(300);
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
                confirmButtonColor: '#4361ee'
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
                    confirmButtonColor: '#4361ee'
                });
                return;
            }
        }
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="bi bi-arrow-repeat fa-spin me-1"></i> Creating...');
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
                        confirmButtonColor: '#4361ee'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred. Please try again.',
                        confirmButtonColor: '#4361ee'
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
        $('#customFeeSection').slideUp(300);
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