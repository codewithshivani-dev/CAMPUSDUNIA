@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<style>
body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.container-fluid {
    padding: 20px;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 0 !important;
    padding: 20px 25px;
    border: none;
}

.section-box {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 25px;
}

.section-header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #0d6efd;
}

.section-icon {
    font-size: 24px;
    margin-right: 10px;
    color: #0d6efd;
}

.section-title {
    margin: 0;
    color: #495057;
    font-weight: 600;
}

.assignee-card {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 12px 15px;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all 0.2s;
}

.assignee-card:hover {
    background: #f8f9fa;
    border-color: #0d6efd;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.assignee-card.selected {
    background: #e7f1ff;
    border-color: #0d6efd;
    box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.1);
}

.assignee-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #0d6efd;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}

.hostel-card {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all 0.2s;
}

.hostel-card:hover {
    background: #f8f9fa;
    border-color: #0d6efd;
}

.hostel-card.selected {
    background: #e7f1ff;
    border-color: #0d6efd;
    border-width: 2px;
}

.search-box {
    position: relative;
    margin-bottom: 15px;
}

.search-box .form-control {
    padding-left: 40px;
}

.search-box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

.assignee-counter {
    display: inline-block;
    background: #6c757d;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 12px;
    margin-left: 10px;
}

.no-results {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.no-results i {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.5;
}

.installment-card {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 15px;
    overflow: hidden;
}

.installment-card .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 10px 15px;
}

.installment-card .card-body {
    padding: 15px;
}

.academic-year-info {
    animation: fadeIn 0.5s ease-in-out;
}

.academic-year-info .alert {
    border-left: 4px solid #0d6efd;
    border-radius: 8px;
}

.fee-summary-card {
    background: white;
    border: 2px solid #198754;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
}

.fee-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #dee2e6;
}

.fee-row.total {
    font-weight: bold;
    font-size: 18px;
    color: #198754;
    border-top: 2px solid #198754;
    margin-top: 10px;
    padding-top: 15px;
}

.duration-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 14px;
}

.badge-monthly {
    background: #d1e7dd;
    color: #0f5132;
}

.badge-quarterly {
    background: #cfe2ff;
    color: #084298;
}

.badge-halfyearly {
    background: #fff3cd;
    color: #664d03;
}

.badge-yearly {
    background: #f8d7da;
    color: #842029;
}

.installment-total {
    text-align: right;
    font-weight: 600;
    color: #198754;
    font-size: 16px;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 2px solid #dee2e6;
}

.hostel-fee-preview {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.hostel-fee-preview .preview-value {
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
}

.seat-availability {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.seat-available {
    background: #d1e7dd;
    color: #0f5132;
}

.seat-low {
    background: #fff3cd;
    color: #664d03;
}

.seat-unavailable {
    background: #f8d7da;
    color: #842029;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Discount card styles */
.discount-card {
    transition: all 0.3s ease;
    border: 1px solid #dee2e6;
}

.discount-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.discount-card.selected {
    border-color: #0d6efd;
    background: linear-gradient(135deg, #f8f9fa 0%, #e7f1ff 100%);
}

.discount-card .badge {
    font-size: 11px;
    padding: 4px 8px;
}

.discount-savings {
    font-size: 16px;
    font-weight: 600;
}

.discount-usage {
    background: #f8f9fa;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
    color: #6c757d;
}

.discount-validity {
    background: #e7f8f0;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
    color: #0f5132;
}

.discount-section {
    border-left: 4px solid #ffc107;
}

.discount-section .card-header {
    background: linear-gradient(135deg, #ffc107 0%, #ffcd39 100%);
    color: #000;
}

/* Discount applicability styles */
.applicability-card {
    border: 2px solid transparent;
    border-radius: 10px;
    padding: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 10px;
}

.applicability-card:hover {
    border-color: #0d6efd;
    background: #f8f9fa;
}

.applicability-card.selected {
    border-color: #0d6efd;
    background: #e7f1ff;
}

.applicability-icon {
    font-size: 24px;
    margin-right: 10px;
    color: #0d6efd;
}

.applicability-details {
    flex: 1;
}

.installment-container {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 10px;
}

.installments-count {
    background: #6c757d;
    color: white;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}
</style>

<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="mb-0"><i class="fas fa-bed me-2"></i>Assign Hostel Fee</h3>
        </div>
        <div class="card-body p-4">

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

            <form method="POST" action="{{ route('admin.hostel-fees.assign.store') }}" id="assignFeeForm">
                @csrf

                <!-- Step 1: Select Assignee Type -->
                <div class="section-box">
                    <div class="section-header">
                        <div class="section-icon">👥</div>
                        <h5 class="section-title">Step 1: Select Assignee Type</h5>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="assignee_type" id="assignee_student"
                                    value="student" checked>
                                <label class="form-check-label" for="assignee_student">
                                    <i class="fas fa-user-graduate me-1"></i>Student
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="assignee_type" id="assignee_employee"
                                    value="employee">
                                <label class="form-check-label" for="assignee_employee">
                                    <i class="fas fa-briefcase me-1"></i>Employee
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Department Selection -->
                    <div class="row mt-3" id="departmentSection">
                        <div class="col-md-6">
                            <label class="form-label">Select Department *</label>
                            <select name="department_id" id="department_id" class="form-control" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                <option value="{{ $department->department_id }}">{{ $department->department }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Select Assignee -->
                <div class="section-box">
                    <div class="section-header">
                        <div class="section-icon">👤</div>
                        <h5 class="section-title">Step 2: Select Assignee
                            <span id="assigneeCount" class="assignee-counter" style="display: none;">0</span>
                        </h5>
                    </div>

                    <div id="assigneeLoading" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading assignees...</p>
                    </div>

                    <!-- Search Box -->
                    <div id="searchContainer" class="search-box" style="display: none;">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchAssignee" class="form-control"
                            placeholder="Search by name or ID...">
                    </div>

                    <div id="assigneeContainer" class="mb-3">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-users fa-2x mb-3"></i>
                            <p>Please select a department to view assignees</p>
                        </div>
                    </div>

                    <!-- Selected Assignee Info -->
                    <div id="selectedAssigneeInfo" class="alert alert-info mt-3" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong><span id="selectedName"></span></strong>
                                <div class="small mt-1">ID: <span id="selectedId"></span> • Type: <span
                                        id="selectedType"></span></div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="clearSelection">
                                <i class="fas fa-times me-1"></i>Clear
                            </button>
                        </div>
                    </div>

                    <!-- Academic Year Info -->
                    <div id="academicYearInfo" class="academic-year-info mt-3" style="display: none;">
                        <!-- Academic year info will be loaded here -->
                    </div>
                </div>

                <!-- Step 3: Select Hostel -->
                <div class="section-box">
                    <div class="section-header">
                        <div class="section-icon">🏨</div>
                        <h5 class="section-title">Step 3: Select Hostel</h5>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Select Hostel *</label>
                            <select name="hostel_reference_id" id="hostel_reference_id" class="form-control" required>
                                <option value="">Select Hostel</option>
                                @foreach($hostels as $hostel)
                                @php
                                $seatClass = 'seat-available';
                                if ($hostel->available_seats <= 0) { $seatClass='seat-unavailable' ; } elseif ($hostel->
                                    available_seats < 5) { $seatClass='seat-low' ; } @endphp <option
                                        value="{{ $hostel->hostel_fee_reference_id }}"
                                        data-monthly-fee="{{ $hostel->monthly_fee ?? 0 }}"
                                        data-room-type="{{ $hostel->room_type ?? '' }}"
                                        data-security-deposit="{{ $hostel->security_deposit ?? 0 }}"
                                        data-maintenance-fee="{{ $hostel->maintenance_fee ?? 0 }}"
                                        data-utility-charges="{{ $hostel->utility_charges ?? 0 }}"
                                        data-late-fee-type="{{ $hostel->late_fee_type ?? 'none' }}"
                                        data-late-fee-value="{{ $hostel->late_fee_value ?? 0 }}"
                                        data-partial-fee-type="{{ $hostel->partially_fee_type ?? 'none' }}"
                                        data-partial-fee-value="{{ $hostel->partially_fee_value ?? 0 }}"
                                        data-available-seats="{{ $hostel->available_seats ?? 0 }}">
                                        {{ $hostel->hostel_name }} ({{ ucfirst($hostel->hostel_type) }})
                                        • {{ ucfirst($hostel->room_type) }}
                                        • ₹{{ $hostel->monthly_fee ?? 0 }}/month
                                        • <span class="{{ $seatClass }}">{{ $hostel->available_seats ?? 0 }} seats</span>
                                        </option>
                                        @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Room Type Display -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Room Type</label>
                            <input type="text" id="room_type_display" class="form-control" readonly>
                            <input type="hidden" name="room_type" id="room_type">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Monthly Fee</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="text" id="monthly_fee_display" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hostel Fee Preview -->
                <div class="section-box" id="hostelFeePreview" style="display: none;">
                    <div class="section-header">
                        <div class="section-icon">💰</div>
                        <h5 class="section-title">Hostel Fee Preview</h5>
                    </div>

                    <div class="hostel-fee-preview">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-label">Monthly Accommodation:</div>
                                <div id="preview_monthly_fee" class="preview-value">₹0.00</div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label">Security Deposit:</div>
                                <div id="preview_security_deposit" class="preview-value">₹0.00</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-label">Maintenance Fee:</div>
                                <div id="preview_maintenance_fee" class="preview-value">₹0.00</div>
                            </div>
                            <div class="col-md-6">
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
                                <div class="form-label">Partial Fee (per payment):</div>
                                <div id="preview_partial_fee" class="preview-value">₹0.00</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-label">Annual Fee:</div>
                                <div id="preview_annual_fee" class="preview-value" style="font-size: 1.3rem;">₹0.00
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-label">Available Seats:</div>
                                <div id="preview_available_seats" class="preview-value">0</div>
                            </div>
                        </div>
                        <div class="small mt-3" style="opacity: 0.9;">
                            <i class="fas fa-info-circle me-1"></i>
                            Additional fees will be applied based on payment duration and discount settings
                        </div>
                    </div>
                </div>

                <!-- Step 4: Fee Duration -->
                <div class="section-box">
                    <div class="section-header">
                        <div class="section-icon">📅</div>
                        <h5 class="section-title">Step 4: Select Payment Duration</h5>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Payment Duration *</label>
                            <select name="fee_duration" id="fee_duration" class="form-control" required>
                                <option value="monthly">Monthly (12 installments)</option>
                                <option value="quarterly">Quarterly (4 installments)</option>
                                <option value="half_yearly">Half Yearly (2 installments)</option>
                                <option value="yearly">Yearly (1 installment)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Available Discounts -->
                <div class="section-box discount-section">
                    <div class="section-header">
                        <div class="section-icon">🎫</div>
                        <h5 class="section-title">Step 5: Apply Discount (Optional)</h5>
                    </div>

                    <div id="discountsInfo" class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Select from available discounts for hostel fees. Discounts will be recorded but not deducted from the fee.
                    </div>

                    <div id="discountsLoading" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading available discounts...</p>
                    </div>

                    <div id="discountsContainer" class="mb-3">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-tag fa-2x mb-3"></i>
                            <p>Complete steps 1-4 to view available discounts</p>
                            <small class="text-muted">Discounts are loaded based on selected assignee and hostel</small>
                        </div>
                    </div>

                    <!-- Selected Discount Info -->
                    <div id="selectedDiscountInfo" class="alert alert-success mt-3" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Selected Discount: <span id="selectedDiscountName"></span></strong>
                                <div class="small mt-1">
                                    Code: <span id="selectedDiscountCode" class="badge text-white bg-primary"></span> • 
                                    Type: <span id="selectedDiscountType" class="badge text-white bg-info"></span> • 
                                    Value: <span id="selectedDiscountValue" class="fw-bold"></span>
                                </div>
                                <div class="small mt-1">
                                    <span id="selectedDiscountDescription"></span>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="clearDiscountSelection">
                                <i class="fas fa-times me-1"></i>Remove
                            </button>
                        </div>
                    </div>

                    <!-- No Discounts Message -->
                    <div id="noDiscountsMessage" class="alert alert-warning mt-3" style="display: none;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>No discounts available</strong>
                        <div class="small mt-1">No active discounts are available for hostel fees at the moment.</div>
                    </div>
                </div>

                <!-- Step 6: Discount Applicability -->
                <div class="section-box" id="discountApplicabilitySection" style="display: none;">
                    <div class="section-header">
                        <div class="section-icon">⚙️</div>
                        <h5 class="section-title">Step 6: Discount Applicability</h5>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">How should the discount be applied? *</label>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="discount_applicability" 
                                           id="discount_annual" value="annual" checked>
                                    <label class="form-check-label" for="discount_annual">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        <strong>Apply to Whole Academic Year</strong>
                                        <div class="small text-muted">
                                            Discount will be recorded for the total annual fee
                                        </div>
                                    </label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="radio" name="discount_applicability" 
                                           id="discount_per_installment" value="per_installment">
                                    <label class="form-check-label" for="discount_per_installment">
                                        <i class="fas fa-calendar-check me-2"></i>
                                        <strong>Apply Per Installment</strong>
                                        <div class="small text-muted">
                                            Discount will be recorded for each installment separately
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="discountDurationInfo" class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Selected:</strong> Whole Academic Year
                                <div class="small mt-1">
                                    The discount will be recorded against the total annual hostel fee
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 7: Installment Details -->
                <div class="section-box" id="installmentsSection" style="display: none;">
                    <div class="section-header">
                        <div class="section-icon">📋</div>
                        <h5 class="section-title">Step 7: Installment Details
                            <span id="installmentsCount" class="installments-count" style="display: none;">0</span>
                        </h5>
                    </div>

                    <div class="installment-container" id="installmentsContainer">
                        <!-- Installment inputs will be loaded here -->
                    </div>

                    <div id="noInstallmentsMessage" class="text-center text-muted py-4">
                        <i class="fas fa-calendar-alt fa-2x mb-3"></i>
                        <p>Complete all previous steps to configure installments</p>
                    </div>

                    <!-- Installment Total -->
                    <div id="installmentsTotal" class="installment-total" style="display: none;">
                        Total Installment Amount: ₹<span id="totalInstallmentAmount">0.00</span>
                    </div>
                </div>

                <!-- Step 8: Fee Summary -->
                <div class="section-box">
                    <div class="section-header">
                        <div class="section-icon">💰</div>
                        <h5 class="section-title">Fee Summary</h5>
                    </div>

                    <div id="feeSummary" class="fee-summary-card" style="display: none;">
                        <div class="text-center mb-4">
                            <span class="duration-badge" id="durationBadge">Monthly</span>
                            <span id="discountAppliedBadge" class="badge text-white bg-success ms-2" style="display: none;">
                                <i class="fas fa-tag me-1"></i>Discount Applied
                            </span>
                        </div>

                        <div class="fee-row">
                            <span>Monthly Accommodation:</span>
                            <span id="summary_monthly_fee">₹0.00</span>
                        </div>

                        <div class="fee-row">
                            <span>Security Deposit:</span>
                            <span id="summary_security_deposit">₹0.00</span>
                        </div>

                        <div class="fee-row">
                            <span>Maintenance Fee:</span>
                            <span id="summary_maintenance_fee">₹0.00</span>
                        </div>

                        <div class="fee-row">
                            <span>Utility Charges:</span>
                            <span id="summary_utility_charges">₹0.00</span>
                        </div>

                        <div class="fee-row">
                            <span>Late Fee (per month):</span>
                            <span id="summary_late_fee">₹0.00</span>
                        </div>

                        <div class="fee-row">
                            <span>Partial Fee (per payment):</span>
                            <span id="summary_partial_fee">₹0.00</span>
                        </div>

                        <div class="fee-row">
                            <span>Payment Duration:</span>
                            <span id="summary_duration">Monthly</span>
                        </div>

                        <div class="fee-row">
                            <span>Total Installments:</span>
                            <span id="summary_installments">0</span>
                        </div>

                        <div class="fee-row">
                            <span>Total Installment Amount:</span>
                            <span id="summary_installment_total">₹0.00</span>
                        </div>

                        <div class="fee-row" id="discountRow" style="display: none;">
                            <span>Discount (Recorded):</span>
                            <span id="summary_discount">₹0.00</span>
                        </div>

                        <div class="fee-row total">
                            <span>Total Payable:</span>
                            <span id="summary_total">₹0.00</span>
                        </div>

                        <div id="discountDetails" class="mt-3 text-muted small" style="display: none;">
                            <i class="fas fa-info-circle me-1"></i>
                            <span id="discountDetailText"></span>
                        </div>
                    </div>

                    <div id="noSummaryMessage" class="text-center text-muted py-4">
                        <i class="fas fa-calculator fa-2x mb-3"></i>
                        <p>Complete all steps above to see fee summary</p>
                    </div>
                </div>

                <!-- Hidden Fields -->
                <input type="hidden" name="assignee_id" id="assignee_id">
                <input type="hidden" name="assignee_name" id="assignee_name">
                <input type="hidden" name="assignee_type_display" id="assignee_type_display">
                <input type="hidden" name="academic_year_id" id="academic_year_id">
                <!-- Discount hidden fields -->
                <input type="hidden" name="discount_id" id="discount_id">
                <input type="hidden" name="discount_applicability" id="discount_applicability_hidden" value="annual">
                <input type="hidden" name="discount_duration_type" id="discount_duration_type_hidden">

                <!-- Submit Button -->
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-5" id="submitBtn" disabled>
                        <i class="fas fa-check-circle me-2"></i>Assign Hostel Fee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let selectedAssignee = null;
    let hostelData = null;
    let monthlyFee = 0;
    let allAssignees = [];
    let filteredAssignees = [];
    let academicYearData = null;
    let selectedDiscount = null;
    let discountApplicability = 'annual';

    // Department change handler
    $('#department_id').on('change', function() {
        const departmentId = $(this).val();
        const assigneeType = $('input[name="assignee_type"]:checked').val();

        if (!departmentId) {
            resetAssigneeSection();
            return;
        }

        loadAssignees(departmentId, assigneeType);
    });

    // Assignee type change handler
    $('input[name="assignee_type"]').on('change', function() {
        const departmentId = $('#department_id').val();
        const assigneeType = $(this).val();

        if (departmentId) {
            loadAssignees(departmentId, assigneeType);
        } else {
            resetAssigneeSection();
        }
    });

    // Hostel selection handler
    $('#hostel_reference_id').on('change', function() {
        const hostelId = $(this).val();
        const selectedOption = $(this).find('option:selected');
        
        if (!hostelId || hostelId === '') {
            $('#hostelFeePreview').hide();
            $('#room_type_display').val('');
            $('#monthly_fee_display').val('');
            $('#room_type').val('');
            hostelData = null;
            monthlyFee = 0;
            updateFeeSummary();
            hideInstallments();
            hideDiscounts();
            return;
        }

        // Get hostel data from data attributes
        hostelData = {
            monthly_fee: parseFloat(selectedOption.data('monthly-fee')) || 0,
            room_type: selectedOption.data('room-type') || '',
            security_deposit: parseFloat(selectedOption.data('security-deposit')) || 0,
            maintenance_fee: parseFloat(selectedOption.data('maintenance-fee')) || 0,
            utility_charges: parseFloat(selectedOption.data('utility-charges')) || 0,
            late_fee_type: selectedOption.data('late-fee-type') || 'none',
            late_fee_value: parseFloat(selectedOption.data('late-fee-value')) || 0,
            partial_fee_type: selectedOption.data('partial-fee-type') || 'none',
            partial_fee_value: parseFloat(selectedOption.data('partial-fee-value')) || 0,
            available_seats: parseInt(selectedOption.data('available-seats')) || 0
        };

        monthlyFee = hostelData.monthly_fee;

        // Update display fields
        $('#room_type_display').val(hostelData.room_type.charAt(0).toUpperCase() + hostelData.room_type.slice(1));
        $('#monthly_fee_display').val('₹' + hostelData.monthly_fee.toFixed(2));
        $('#room_type').val(hostelData.room_type);

        // Update fee preview
        updateHostelFeePreview();
        $('#hostelFeePreview').show();

        // Show installment inputs if assignee is selected
        if (selectedAssignee) {
            showInstallmentInputs();
        }

        // Load discounts if assignee is selected
        if (selectedAssignee) {
            loadAvailableDiscounts();
        }

        updateFeeSummary();
    });

    // Fee duration change handler
    $('#fee_duration').on('change', function() {
        $('#discount_duration_type_hidden').val($(this).val());
        
        if (selectedAssignee && hostelData) {
            showInstallmentInputs();
        }
        
        // Update discount preview if discount is selected
        if (selectedDiscount) {
            updateDiscountPreview();
        }
        
        updateFeeSummary();
    });

    // Discount applicability change handler
    $(document).on('change', 'input[name="discount_applicability"]', function() {
        updateDiscountApplicability();
    });

    // Clear selection handlers
    $('#clearSelection').on('click', function() {
        clearAssigneeSelection();
    });

    $('#clearDiscountSelection').on('click', function() {
        clearDiscountSelection();
    });

    // Search functionality
    $(document).on('input', '#searchAssignee', function() {
        const searchTerm = $(this).val().toLowerCase();
        filterAssignees(searchTerm);
    });

    // Function to reset assignee section
    function resetAssigneeSection() {
        $('#assigneeContainer').html(`
            <div class="text-center text-muted py-4">
                <i class="fas fa-users fa-2x mb-3"></i>
                <p>Please select a department to view assignees</p>
            </div>
        `);
        $('#searchContainer').hide();
        $('#selectedAssigneeInfo').hide();
        $('#academicYearInfo').hide();
        $('#assigneeCount').hide();
        clearAssigneeSelection();
    }

    // Function to load assignees
    function loadAssignees(departmentId, assigneeType) {
        $('#assigneeLoading').show();
        $('#assigneeContainer').hide();
        $('#searchContainer').hide();
        $('#selectedAssigneeInfo').hide();
        $('#academicYearInfo').hide();

        const url = assigneeType === 'student' ?
            '{{ route("ajax.students.by.department") }}' :
            '{{ route("ajax.employees.by.department") }}';

        $.ajax({
            url: url,
            method: 'GET',
            data: {
                department_id: departmentId
            },
            success: function(response) {
                $('#assigneeLoading').hide();

                if (response.success === true) {
                    let assignees = [];
                    let count = 0;

                    if (assigneeType === 'student' && response.students && response.students
                        .length > 0) {
                        assignees = response.students;
                        count = response.count || assignees.length;
                    } else if (assigneeType === 'employee' && response.employees && response
                        .employees.length > 0) {
                        assignees = response.employees;
                        count = response.count || assignees.length;
                    }

                    if (assignees.length > 0) {
                        allAssignees = assignees;
                        filteredAssignees = [...allAssignees];
                        renderAssignees();
                        $('#assigneeCount').text(count).show();
                        $('#searchContainer').show();
                        $('#searchAssignee').val('');
                    } else {
                        showNoResults('No ' + assigneeType + 's found in this department');
                    }
                } else {
                    showNoResults('No ' + assigneeType + 's found in this department');
                }
            },
            error: function(xhr, status, error) {
                $('#assigneeLoading').hide();
                showError('Error loading ' + assigneeType + 's. Please try again.');
            }
        });
    }

    // Function to render assignees
    function renderAssignees() {
        if (filteredAssignees.length === 0) {
            showNoResults('No assignees match your search');
            return;
        }

        let html = '';
        const assigneeType = $('input[name="assignee_type"]:checked').val();

        filteredAssignees.forEach(function(assignee, index) {
            if (assigneeType === 'student') {
                const firstName = assignee.first_name || '';
                const middleName = assignee.middle_name || '';
                const lastName = assignee.last_name || '';
                let fullNameParts = [];
                if (firstName) fullNameParts.push(firstName);
                if (middleName) fullNameParts.push(middleName);
                if (lastName) fullNameParts.push(lastName);
                const fullName = fullNameParts.join(' ');

                const identifier = assignee.registration_number || '';
                const assigneeId = assignee.student_hash_id || assignee.id || '';
                const avatarText = (firstName.charAt(0) || '').toUpperCase();

                const isSelected = selectedAssignee && selectedAssignee.id === assigneeId;

                html += `
                    <div class="assignee-card ${isSelected ? 'selected' : ''}" 
                        data-id="${assigneeId}" 
                        data-name="${fullName}" 
                        data-identifier="${identifier}"
                        data-type="${assigneeType}">
                        <div class="d-flex align-items-center">
                            <div class="assignee-avatar me-3">
                                ${avatarText}
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">${fullName}</h6>
                                <p class="mb-0 text-muted small">
                                    ${identifier ? 'ID: ' + identifier + ' • ' : ''}
                                    Student
                                </p>
                            </div>
                            ${isSelected ? '<i class="fas fa-check text-success"></i>' : ''}
                        </div>
                    </div>
                `;
            } else if (assigneeType === 'employee') {
                const fullName = assignee.name || '';
                const identifier = assignee.employee_id || '';
                const assigneeId = assignee.employee_id || assignee.id || '';
                const avatarText = (fullName.charAt(0) || '').toUpperCase();

                const isSelected = selectedAssignee && selectedAssignee.id === assigneeId;

                html += `
                    <div class="assignee-card ${isSelected ? 'selected' : ''}" 
                        data-id="${assigneeId}" 
                        data-name="${fullName}" 
                        data-identifier="${identifier}"
                        data-type="${assigneeType}">
                        <div class="d-flex align-items-center">
                            <div class="assignee-avatar me-3" style="background-color: #198754;">
                                ${avatarText}
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">${fullName}</h6>
                                <p class="mb-0 text-muted small">
                                    ${identifier ? 'ID: ' + identifier + ' • ' : ''}
                                    ${assignee.designation ? assignee.designation + ' • ' : ''}
                                    Employee
                                </p>
                            </div>
                            ${isSelected ? '<i class="fas fa-check text-success"></i>' : ''}
                        </div>
                    </div>
                `;
            }
        });

        $('#assigneeContainer').html(html).show();

        // Add click handler for assignee cards
        $('.assignee-card').on('click', function() {
            const assigneeId = $(this).data('id');
            const assigneeName = $(this).data('name');
            const assigneeIdentifier = $(this).data('identifier');
            const assigneeType = $(this).data('type');

            $('.assignee-card').removeClass('selected');
            $(this).addClass('selected');

            selectedAssignee = {
                id: assigneeId,
                name: assigneeName,
                identifier: assigneeIdentifier,
                type: assigneeType
            };

            // Update hidden fields
            $('#assignee_id').val(selectedAssignee.id);
            $('#assignee_name').val(selectedAssignee.name);
            $('#assignee_type_display').val(selectedAssignee.type === 'student' ? 'Student' :
                'Employee');

            // Update selected info display
            $('#selectedName').text(selectedAssignee.name);
            $('#selectedId').text(selectedAssignee.identifier || 'N/A');
            $('#selectedType').text(selectedAssignee.type === 'student' ? 'Student' : 'Employee');
            $('#selectedAssigneeInfo').show();

            // Load academic year based on assignee type
            if (selectedAssignee.type === 'student') {
                loadStudentAcademicYear(selectedAssignee.id);
            } else {
                loadEmployeeAcademicYear();
            }

            // Show installment inputs if hostel is selected
            if (hostelData) {
                showInstallmentInputs();
            }

            // Load discounts if hostel is selected
            if (hostelData) {
                loadAvailableDiscounts();
            }

            updateSubmitButton();
        });
    }

    // Function to load student academic year
    function loadStudentAcademicYear(studentHashId) {
        $.ajax({
            url: '{{ route("ajax.student.academic-year") }}',
            method: 'GET',
            data: {
                student_hash_id: studentHashId
            },
            success: function(response) {
                if (response.status) {
                    academicYearData = response.academic_year;
                    $('#academic_year_id').val(academicYearData.id);

                    $('#academicYearInfo').html(`
                        <div class="alert alert-primary border-primary">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="academic-year-dates">
                                                <i class="fas fa-calendar-day me-2"></i>
                                                <strong>Academic Year:</strong> 
                                                <strong>${academicYearData.name}</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="academic-year-status">
                                                <i class="fas fa-bed me-2"></i>
                                                <strong>Hostel Fee:</strong> Will be assigned for this academic year
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="small text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Hostel fee will be configured based on selected payment duration
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).show();
                } else {
                    $('#academicYearInfo').html(`
                        <div class="alert alert-warning">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle fa-lg me-3"></i>
                                <div>
                                    <h6 class="alert-heading mb-1">Academic Year Information</h6>
                                    <p class="mb-0">${response.message || 'Academic year not found for this student'}</p>
                                </div>
                            </div>
                        </div>
                    `).show();
                }
            },
            error: function() {
                $('#academicYearInfo').html(`
                    <div class="alert alert-danger">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle fa-lg me-3"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Error Loading Information</h6>
                                <p class="mb-0">Unable to fetch academic year details</p>
                            </div>
                        </div>
                    </div>
                `).show();
            }
        });
    }

    // Function to load employee academic year
    function loadEmployeeAcademicYear() {
        $.ajax({
            url: '{{ route("ajax.employee.academic-year.hostel") }}',
            method: 'GET',
            success: function(response) {
                if (response.status) {
                    academicYearData = response.academic_year;
                    $('#academic_year_id').val(academicYearData.id);

                    $('#academicYearInfo').html(`
                        <div class="alert alert-primary border-primary">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-calendar-check fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="academic-year-dates">
                                                <i class="fas fa-calendar-day me-2"></i>
                                                <strong>Academic Year:</strong> 
                                                <strong>${academicYearData.name}</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="academic-year-status">
                                                <i class="fas fa-bed me-2"></i>
                                                <strong>Hostel Fee:</strong> From hostel fee structure
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).show();
                } else {
                    $('#academicYearInfo').html(`
                        <div class="alert alert-warning">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle fa-lg me-3"></i>
                                <div>
                                    <h6 class="alert-heading mb-1">Academic Year Information</h6>
                                    <p class="mb-0">${response.message || 'Academic year not found'}</p>
                                </div>
                            </div>
                        </div>
                    `).show();
                }
            },
            error: function() {
                $('#academicYearInfo').html(`
                    <div class="alert alert-danger">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle fa-lg me-3"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Error Loading Information</h6>
                                <p class="mb-0">Unable to fetch academic year details</p>
                            </div>
                        </div>
                    </div>
                `).show();
            }
        });
    }

    // Function to load available discounts
    function loadAvailableDiscounts() {
        if (!selectedAssignee || !monthlyFee || monthlyFee <= 0) {
            return;
        }

        $('#discountsLoading').show();
        $('#discountsContainer').hide();
        $('#noDiscountsMessage').hide();
        $('#selectedDiscountInfo').hide();

        $.ajax({
            url: '{{ route("ajax.hostel.available.discounts") }}',
            method: 'GET',
            success: function(response) {
                $('#discountsLoading').hide();

                if (response.success && response.discounts && response.discounts.length > 0) {
                    renderDiscounts(response.discounts);
                } else {
                    showNoDiscountsAvailable();
                }
            },
            error: function() {
                $('#discountsLoading').hide();
                showNoDiscountsAvailable();
            }
        });
    }

    // Function to hide discounts
    function hideDiscounts() {
        $('#discountsContainer').html(`
            <div class="text-center text-muted py-4">
                <i class="fas fa-tag fa-2x mb-3"></i>
                <p>Complete steps 1-4 to view available discounts</p>
                <small class="text-muted">Discounts are loaded based on selected assignee and hostel</small>
            </div>
        `);
        $('#selectedDiscountInfo').hide();
        $('#noDiscountsMessage').hide();
        $('#discountApplicabilitySection').hide();
    }

    // Function to render discounts
    function renderDiscounts(discounts) {
        let html = '';
        
        // Calculate current total for discount preview
        const currentTotal = calculateTotalForDiscountPreview();
        
        discounts.forEach(function(discount, index) {
            const discountId = discount.discount_hash_id || discount.id;
            const discountName = discount.name || 'Unnamed Discount';
            const couponCode = discount.coupon_code || 'N/A';
            const discountType = discount.type || 'flat';
            const discountValue = parseFloat(discount.value) || 0;
            const description = discount.description || '';
            
            // Calculate discount amount for this discount
            let discountAmount = 0;
            let discountText = '';
            let discountTypeBadge = '';
            let usageText = '';
            
            if (discountType === 'percentage') {
                discountAmount = (currentTotal * discountValue) / 100;
                discountText = `${discountValue}%`;
                discountTypeBadge = 'bg-success';
            } else if (discountType === 'flat') {
                discountAmount = discountValue;
                discountText = `₹${discountValue.toFixed(2)}`;
                discountTypeBadge = 'bg-info';
            }
            
            // Check if discount exceeds total
            if (discountAmount > currentTotal) {
                discountAmount = currentTotal;
            }
            
            // Get usage from the response
            const currentUsage = discount.current_usage || 0;
            const maxUsage = discount.max_usage || null;
            const remaining = discount.remaining_usage;
            
            // Format usage text
            if (maxUsage && remaining !== null) {
                usageText = `${currentUsage}/${maxUsage} used • ${remaining} remaining`;
            } else {
                usageText = 'Unlimited uses';
            }
            
            // Get validity text
            const validityText = discount.validity_text || 'No expiry';
            
            const isSelected = selectedDiscount && selectedDiscount.id === discountId;
            
            html += `
                <div class="card mb-3 discount-card ${isSelected ? 'selected' : ''}" 
                    data-id="${discountId}"
                    data-name="${discountName}"
                    data-code="${couponCode}"
                    data-type="${discountType}"
                    data-value="${discountValue}"
                    data-description="${description}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1 me-3">
                                <div class="d-flex align-items-center mb-2">
                                    <h6 class="mb-0 me-2">${discountName}</h6>
                                    <span class="badge text-white ${discountTypeBadge}">${discountType.toUpperCase()}</span>
                                </div>
                                
                                <div class="mb-2">
                                    <span class="badge text-white bg-primary">${couponCode}</span>
                                    <span class="badge text-white bg-secondary ms-2">${discountText}</span>
                                </div>
                                
                                ${description ? `<p class="small text-muted mb-2">${description}</p>` : ''}
                                
                                <div class="small">
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="discount-usage">
                                            <i class="fas fa-users me-1"></i>${usageText}
                                        </span>
                                        <span class="discount-validity">
                                            <i class="fas fa-calendar-check me-1"></i>${validityText}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="discount-savings text-success mb-2">
                                    <i class="fas fa-rupee-sign"></i>${discountAmount.toFixed(2)}
                                    <div class="small text-muted">Annual Savings</div>
                                </div>
                                <button type="button" class="btn btn-sm ${isSelected ? 'btn-outline-danger' : 'btn-primary'} select-discount-btn">
                                    ${isSelected ? '<i class="fas fa-times me-1"></i>Remove' : '<i class="fas fa-check me-1"></i>Apply'}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        $('#discountsContainer').html(html).show();
        
        $('.select-discount-btn').on('click', function(e) {
            e.stopPropagation();
            const card = $(this).closest('.discount-card');
            
            if ($(this).hasClass('btn-primary')) {
                selectDiscount(card);
            } else {
                clearDiscountSelection();
            }
        });
        
        $('.discount-card').on('click', function(e) {
            if (!$(e.target).closest('.select-discount-btn').length) {
                const card = $(this);
                if (!card.hasClass('selected')) {
                    selectDiscount(card);
                }
            }
        });
    }

    // Function to select a discount
    function selectDiscount(card) {
        $('.discount-card').removeClass('selected');
        card.addClass('selected');
        
        selectedDiscount = {
            id: card.data('id'),
            name: card.data('name'),
            code: card.data('code'),
            type: card.data('type'),
            value: card.data('value'),
            description: card.data('description')
        };
        
        // Show discount applicability section
        $('#discountApplicabilitySection').show();
        
        // Update UI
        $('#selectedDiscountName').text(selectedDiscount.name);
        $('#selectedDiscountCode').text(selectedDiscount.code);
        $('#selectedDiscountType').text(selectedDiscount.type.toUpperCase());
        $('#selectedDiscountValue').text(
            selectedDiscount.type === 'percentage' ? 
            `${selectedDiscount.value}%` : 
            `₹${selectedDiscount.value}`
        );
        $('#selectedDiscountDescription').text(selectedDiscount.description || 'No description provided');
        $('#selectedDiscountInfo').show();
        
        // Update hidden fields
        $('#discount_id').val(selectedDiscount.id);
        
        // Update button text
        $('.select-discount-btn')
            .removeClass('btn-outline-danger')
            .addClass('btn-primary')
            .html('<i class="fas fa-check me-1"></i>Apply');
        card.find('.select-discount-btn')
            .removeClass('btn-primary')
            .addClass('btn-outline-danger')
            .html('<i class="fas fa-times me-1"></i>Remove');
        
        updateDiscountApplicability();
        updateFeeSummary();
    }

    // Function to update discount applicability
    function updateDiscountApplicability() {
        const applicability = $('input[name="discount_applicability"]:checked').val();
        discountApplicability = applicability;
        $('#discount_applicability_hidden').val(applicability);
        
        // Update info display
        if (applicability === 'annual') {
            $('#discountDurationInfo').html(`
                <i class="fas fa-info-circle me-2"></i>
                <strong>Selected:</strong> Whole Academic Year
                <div class="small mt-1">
                    The discount will be recorded against the total annual hostel fee
                </div>
            `);
        } else {
            const duration = $('#fee_duration').val();
            const durationText = duration.charAt(0).toUpperCase() + duration.slice(1);
            $('#discountDurationInfo').html(`
                <i class="fas fa-info-circle me-2"></i>
                <strong>Selected:</strong> Per Installment (${durationText})
                <div class="small mt-1">
                    Discount will be recorded for each ${duration} installment separately
                </div>
            `);
        }
        
        updateFeeSummary();
    }

    // Function to show no discounts available
    function showNoDiscountsAvailable() {
        $('#discountsContainer').hide();
        $('#noDiscountsMessage').show();
        $('#selectedDiscountInfo').hide();
        $('#discountApplicabilitySection').hide();
    }

    // Function to clear discount selection
    function clearDiscountSelection() {
        selectedDiscount = null;
        $('.discount-card').removeClass('selected');
        $('.select-discount-btn')
            .removeClass('btn-outline-danger')
            .addClass('btn-primary')
            .html('<i class="fas fa-check me-1"></i>Apply');
        $('#selectedDiscountInfo').hide();
        $('#discountApplicabilitySection').hide();
        $('#discount_id').val('');
        $('#discount_applicability_hidden').val('annual');
        $('#discount_duration_type_hidden').val('');
        updateFeeSummary();
    }

    // Function to calculate total for discount preview
    function calculateTotalForDiscountPreview() {
        if (!monthlyFee || monthlyFee <= 0) return 0;
        
        const duration = $('#fee_duration').val();
        let total = 0;
        
        switch (duration) {
            case 'monthly':
                total = monthlyFee * 12;
                break;
            case 'quarterly':
                total = monthlyFee * 12;
                break;
            case 'half_yearly':
                total = monthlyFee * 12;
                break;
            case 'yearly':
                total = monthlyFee * 12;
                break;
        }
        
        return total;
    }

    // Function to update discount preview
    function updateDiscountPreview() {
        if (!selectedDiscount) return;
        
        const currentTotal = calculateTotalForDiscountPreview();
        let discountAmount = 0;
        
        if (selectedDiscount.type === 'percentage') {
            discountAmount = (currentTotal * selectedDiscount.value) / 100;
        } else if (selectedDiscount.type === 'flat') {
            discountAmount = selectedDiscount.value;
        }
        
        if (discountAmount > currentTotal) {
            discountAmount = currentTotal;
        }
        
        $('.discount-card.selected').find('.discount-savings').html(`
            <i class="fas fa-rupee-sign"></i>${discountAmount.toFixed(2)}
            <div class="small text-muted">Annual Savings</div>
        `);
    }

    // Function to update hostel fee preview
    function updateHostelFeePreview() {
        if (!hostelData) return;

        // Calculate late fee
        let lateFee = 0;
        if (hostelData.late_fee_type === 'fixed') {
            lateFee = hostelData.late_fee_value;
        } else if (hostelData.late_fee_type === 'percentage') {
            lateFee = (hostelData.monthly_fee * hostelData.late_fee_value) / 100;
        }

        // Calculate partial fee
        let partialFee = 0;
        if (hostelData.partial_fee_type === 'fixed') {
            partialFee = hostelData.partial_fee_value;
        } else if (hostelData.partial_fee_type === 'percentage') {
            partialFee = (hostelData.monthly_fee * hostelData.partial_fee_value) / 100;
        }

        // Calculate annual fee
        const annualFee = (hostelData.monthly_fee * 12) +
            hostelData.security_deposit +
            hostelData.maintenance_fee +
            hostelData.utility_charges;

        $('#preview_monthly_fee').text('₹' + hostelData.monthly_fee.toFixed(2));
        $('#preview_security_deposit').text('₹' + hostelData.security_deposit.toFixed(2));
        $('#preview_maintenance_fee').text('₹' + hostelData.maintenance_fee.toFixed(2));
        $('#preview_utility_charges').text('₹' + hostelData.utility_charges.toFixed(2));
        $('#preview_late_fee').text('₹' + lateFee.toFixed(2));
        $('#preview_partial_fee').text('₹' + partialFee.toFixed(2));
        $('#preview_annual_fee').text('₹' + annualFee.toFixed(2));
        $('#preview_available_seats').text(hostelData.available_seats);
    }

    // Function to show installment inputs
    function showInstallmentInputs() {
        if (!selectedAssignee || !hostelData) {
            return;
        }

        const duration = $('#fee_duration').val();

        $('#installmentsContainer').empty();
        $('#noInstallmentsMessage').hide();
        $('#installmentsSection').show();

        let installmentCount = 0;
        let installmentName = '';
        let defaultMultiplier = 1;
        let monthsCovered = 1;

        switch (duration) {
            case 'monthly':
                installmentCount = 12;
                installmentName = 'Month';
                defaultMultiplier = 1;
                monthsCovered = 1;
                break;
            case 'quarterly':
                installmentCount = 4;
                installmentName = 'Quarter';
                defaultMultiplier = 3;
                monthsCovered = 3;
                break;
            case 'half_yearly':
                installmentCount = 2;
                installmentName = 'Half Year';
                defaultMultiplier = 6;
                monthsCovered = 6;
                break;
            case 'yearly':
                installmentCount = 1;
                installmentName = 'Year';
                defaultMultiplier = 12;
                monthsCovered = 12;
                break;
        }

        // Calculate base amount (monthly fee × multiplier)
        const baseAmount = hostelData.monthly_fee * defaultMultiplier;

        // Add additional charges to first installment
        const additionalCharges = hostelData.security_deposit + hostelData.maintenance_fee + hostelData.utility_charges;
        const firstInstallmentAmount = baseAmount + additionalCharges;
        const otherInstallmentAmount = baseAmount;

        // Create installment inputs
        for (let i = 1; i <= installmentCount; i++) {
            const isFirst = i === 1;
            const installmentAmount = isFirst ? firstInstallmentAmount : otherInstallmentAmount;
            const note = isFirst ?
                `Base: ₹${baseAmount.toFixed(2)} + Additional: ₹${additionalCharges.toFixed(2)}` :
                `${defaultMultiplier} month(s) × ₹${hostelData.monthly_fee.toFixed(2)}`;

            const installmentHtml = `
                <div class="card mb-3 installment-card" data-index="${i}">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">${installmentName} ${i}</h6>
                        <span class="badge bg-primary">
                            ${duration.charAt(0).toUpperCase() + duration.slice(1)}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Amount (₹) *</label>
                                <input type="number" 
                                       name="installments[${i}][amount]" 
                                       class="form-control installment-amount"
                                       value="${installmentAmount.toFixed(2)}"
                                       step="0.01"
                                       min="0"
                                       required>
                                <small class="text-muted">${note}</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Start Date *</label>
                                <input type="date" 
                                       name="installments[${i}][start_date]" 
                                       class="form-control installment-start-date"
                                       required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Due Date *</label>
                                <input type="date" 
                                       name="installments[${i}][due_date]" 
                                       class="form-control installment-due-date"
                                       required>
                            </div>
                        </div>
                        <input type="hidden" name="installments[${i}][installment_name]" value="${installmentName} ${i}">
                        <input type="hidden" name="installments[${i}][installment_number]" value="${i}">
                        <input type="hidden" name="installments[${i}][months_covered]" value="${monthsCovered}">
                    </div>
                </div>
            `;

            $('#installmentsContainer').append(installmentHtml);
        }

        // Update installment count
        $('#installmentsCount').text(`${installmentCount} Installment${installmentCount > 1 ? 's' : ''}`).show();

        // Auto-fill dates if academic year data exists
        if (academicYearData) {
            autoFillInstallmentDates(duration, academicYearData);
        }

        // Update total and summary
        updateInstallmentsTotal();
        updateFeeSummary();
        updateSubmitButton();
    }

    // Function to auto-fill installment dates
    function autoFillInstallmentDates(duration, academicYearData) {
        const startDate = academicYearData.start_date ? new Date(academicYearData.start_date) : new Date();
        const endDate = academicYearData.end_date ? new Date(academicYearData.end_date) : new Date();

        // If no end date, set it to 12 months from start
        if (!academicYearData.end_date) {
            endDate.setFullYear(startDate.getFullYear() + 1);
        }

        $('.installment-card').each(function(index) {
            const cardIndex = $(this).data('index');
            let installmentStartDate, installmentDueDate;

            switch (duration) {
                case 'monthly':
                    installmentStartDate = new Date(startDate);
                    installmentStartDate.setMonth(startDate.getMonth() + (cardIndex - 1));
                    installmentDueDate = new Date(installmentStartDate);
                    installmentDueDate.setMonth(installmentStartDate.getMonth() + 1);
                    break;

                case 'quarterly':
                    installmentStartDate = new Date(startDate);
                    installmentStartDate.setMonth(startDate.getMonth() + ((cardIndex - 1) * 3));
                    installmentDueDate = new Date(installmentStartDate);
                    installmentDueDate.setMonth(installmentStartDate.getMonth() + 3);
                    break;

                case 'half_yearly':
                    installmentStartDate = new Date(startDate);
                    installmentStartDate.setMonth(startDate.getMonth() + ((cardIndex - 1) * 6));
                    installmentDueDate = new Date(installmentStartDate);
                    installmentDueDate.setMonth(installmentStartDate.getMonth() + 6);
                    break;

                case 'yearly':
                    installmentStartDate = new Date(startDate);
                    installmentDueDate = new Date(endDate);
                    break;
            }

            // Format dates
            const startDateStr = installmentStartDate.toISOString().split('T')[0];
            const dueDateStr = installmentDueDate.toISOString().split('T')[0];

            $(this).find('.installment-start-date').val(startDateStr);
            $(this).find('.installment-due-date').val(dueDateStr);
        });
    }

    // Function to update installments total
    function updateInstallmentsTotal() {
        let total = 0;
        $('.installment-amount').each(function() {
            total += parseFloat($(this).val()) || 0;
        });

        $('#totalInstallmentAmount').text(total.toFixed(2));
        $('#installmentsTotal').show();

        return total;
    }

    // Function to filter assignees
    function filterAssignees(searchTerm) {
        if (!searchTerm) {
            filteredAssignees = [...allAssignees];
        } else {
            const assigneeType = $('input[name="assignee_type"]:checked').val();
            filteredAssignees = allAssignees.filter(assignee => {
                if (assigneeType === 'student') {
                    const firstName = assignee.first_name || '';
                    const lastName = assignee.last_name || '';
                    const fullName = (firstName + ' ' + lastName).toLowerCase();
                    const rollNo = (assignee.registration_number || '').toLowerCase();
                    return fullName.includes(searchTerm) || rollNo.includes(searchTerm);
                } else {
                    const fullName = (assignee.name || '').toLowerCase();
                    const empId = (assignee.employee_id || '').toLowerCase();
                    return fullName.includes(searchTerm) || empId.includes(searchTerm);
                }
            });
        }
        renderAssignees();
    }

    // Function to clear assignee selection
    function clearAssigneeSelection() {
        $('.assignee-card').removeClass('selected');
        selectedAssignee = null;
        $('#assignee_id').val('');
        $('#assignee_name').val('');
        $('#assignee_type_display').val('');
        $('#selectedAssigneeInfo').hide();
        $('#academicYearInfo').hide();
        $('#installmentsSection').hide();
        $('#academic_year_id').val('');
        academicYearData = null;
        hideDiscounts();
        updateSubmitButton();
    }

    // Function to show no results
    function showNoResults(message) {
        $('#assigneeContainer').html(`
            <div class="no-results">
                <i class="fas fa-user-slash"></i>
                <p class="mt-3">${message}</p>
            </div>
        `).show();
        $('#searchContainer').hide();
        $('#assigneeCount').hide();
        clearAssigneeSelection();
    }

    // Function to show error
    function showError(message) {
        $('#assigneeContainer').html(`
            <div class="no-results">
                <i class="fas fa-exclamation-triangle text-danger"></i>
                <p class="mt-3">${message}</p>
            </div>
        `).show();
        $('#searchContainer').hide();
        $('#assigneeCount').hide();
        clearAssigneeSelection();
    }

    // Function to hide installments
    function hideInstallments() {
        $('#installmentsSection').hide();
        $('#installmentsContainer').empty();
        $('#noInstallmentsMessage').show();
        updateFeeSummary();
    }

    // Function to calculate and update fee summary
    function updateFeeSummary() {
        if (!hostelData || !selectedAssignee) {
            $('#feeSummary').hide();
            $('#noSummaryMessage').show();
            return;
        }
        
        const duration = $('#fee_duration').val();
        const installmentTotal = updateInstallmentsTotal();
        const installmentCount = $('.installment-card').length;
        
        // Calculate discount (for display only)
        let discountAmount = 0;
        let discountText = '';
        let discountPerInstallment = 0;
        
        if (selectedDiscount) {
            if (discountApplicability === 'annual') {
                const annualTotal = monthlyFee * 12 + hostelData.security_deposit + 
                                  hostelData.maintenance_fee + hostelData.utility_charges;
                
                if (selectedDiscount.type === 'percentage') {
                    discountAmount = (annualTotal * selectedDiscount.value) / 100;
                    discountText = `${selectedDiscount.value}% of annual total`;
                } else if (selectedDiscount.type === 'flat') {
                    discountAmount = selectedDiscount.value;
                    discountText = `₹${selectedDiscount.value} flat discount`;
                }
                
                if (discountAmount > annualTotal) {
                    discountAmount = annualTotal;
                }
            } else {
                const installmentMultiplier = getMultiplierForDuration(duration);
                const installmentAmount = monthlyFee * installmentMultiplier;
                
                if (selectedDiscount.type === 'percentage') {
                    discountPerInstallment = (installmentAmount * selectedDiscount.value) / 100;
                } else if (selectedDiscount.type === 'flat') {
                    discountPerInstallment = selectedDiscount.value;
                }
                
                discountAmount = discountPerInstallment * installmentCount;
                discountText = `₹${discountPerInstallment.toFixed(2)} per ${duration} installment`;
            }
        }
        
        // Calculate late fee per month
        let lateFeePerMonth = 0;
        if (hostelData.late_fee_type === 'fixed') {
            lateFeePerMonth = hostelData.late_fee_value;
        } else if (hostelData.late_fee_type === 'percentage') {
            lateFeePerMonth = (hostelData.monthly_fee * hostelData.late_fee_value) / 100;
        }
        
        // Calculate partial fee per payment
        let partialFeePerPayment = 0;
        if (hostelData.partial_fee_type === 'fixed') {
            partialFeePerPayment = hostelData.partial_fee_value;
        } else if (hostelData.partial_fee_type === 'percentage') {
            const avgInstallment = installmentTotal / (installmentCount || 1);
            partialFeePerPayment = (avgInstallment * hostelData.partial_fee_value) / 100;
        }
        
        // Update summary display
        $('#summary_monthly_fee').text('₹' + hostelData.monthly_fee.toFixed(2));
        $('#summary_security_deposit').text('₹' + hostelData.security_deposit.toFixed(2));
        $('#summary_maintenance_fee').text('₹' + hostelData.maintenance_fee.toFixed(2));
        $('#summary_utility_charges').text('₹' + hostelData.utility_charges.toFixed(2));
        $('#summary_late_fee').text('₹' + lateFeePerMonth.toFixed(2));
        $('#summary_partial_fee').text('₹' + partialFeePerPayment.toFixed(2));
        $('#summary_duration').text(duration.charAt(0).toUpperCase() + duration.slice(1));
        $('#summary_installments').text(installmentCount);
        $('#summary_installment_total').text('₹' + installmentTotal.toFixed(2));
        
        if (selectedDiscount) {
            $('#discountRow').show();
            $('#summary_discount').text('₹' + discountAmount.toFixed(2));
            $('#summary_total').text('₹' + installmentTotal.toFixed(2)); // Don't subtract discount
            $('#discountDetailText').text(`${selectedDiscount.name} (${selectedDiscount.code}): ${discountText} - Recorded ${discountApplicability === 'annual' ? 'for whole academic year' : `per ${duration} installment`}`);
            $('#discountAppliedBadge').show();
            $('#discountDetails').show();
        } else {
            $('#discountRow').hide();
            $('#summary_discount').text('₹0.00');
            $('#summary_total').text('₹' + installmentTotal.toFixed(2));
            $('#discountAppliedBadge').hide();
            $('#discountDetails').hide();
        }
        
        // Update duration badge
        $('#durationBadge')
            .removeClass('badge-monthly badge-quarterly badge-halfyearly badge-yearly')
            .addClass('badge-' + duration)
            .text(duration.charAt(0).toUpperCase() + duration.slice(1));
        
        $('#feeSummary').show();
        $('#noSummaryMessage').hide();
    }

    // Helper function for multiplier
    function getMultiplierForDuration(duration) {
        switch (duration) {
            case 'monthly': return 1;
            case 'quarterly': return 3;
            case 'half_yearly': return 6;
            case 'yearly': return 12;
            default: return 1;
        }
    }

    // Function to update submit button state
    function updateSubmitButton() {
        const hasAssignee = selectedAssignee !== null;
        const hasHostel = $('#hostel_reference_id').val() !== '';
        const hasInstallments = $('.installment-card').length > 0;

        // Validate all installment inputs
        let allInstallmentsValid = true;
        if (hasInstallments) {
            $('.installment-amount, .installment-start-date, .installment-due-date').each(function() {
                if (!$(this).val()) {
                    allInstallmentsValid = false;
                }
            });
        }

        const allValid = hasAssignee && hasHostel && hasInstallments && allInstallmentsValid;
        $('#submitBtn').prop('disabled', !allValid);
    }

    // Installment amount change handler
    $(document).on('input', '.installment-amount', function() {
        updateInstallmentsTotal();
        updateFeeSummary();
        if (selectedDiscount) {
            updateDiscountPreview();
        }
    });

    // Installment date change handler
    $(document).on('change', '.installment-start-date, .installment-due-date', function() {
        updateSubmitButton();
    });

    // Form submission handler
    $('#assignFeeForm').on('submit', function(e) {
        e.preventDefault();

        // Validate discount if selected
        if (selectedDiscount) {
            const selectedCard = $('.discount-card.selected');
            if (!selectedCard.length) {
                alert('Selected discount is no longer available. Please select a different discount or remove it.');
                return;
            }
        }

        // Validate installment dates
        let dateError = false;
        $('.installment-card').each(function() {
            const startDate = $(this).find('.installment-start-date').val();
            const dueDate = $(this).find('.installment-due-date').val();

            if (startDate && dueDate && new Date(startDate) > new Date(dueDate)) {
                alert('Start date cannot be after due date in any installment');
                dateError = true;
                return false;
            }
        });

        if (dateError) return;

        // Show loading
        $('#submitBtn').prop('disabled', true).html(
            '<i class="fas fa-spinner fa-spin me-2"></i>Processing...');

        // Submit form
        this.submit();
    });

    // Set discount duration type on page load
    $('#discount_duration_type_hidden').val($('#fee_duration').val());

    // Initial update
    updateSubmitButton();

    // Force initial update based on current selections
    setTimeout(function() {
        const hostelId = $('#hostel_reference_id').val();
        if (hostelId) {
            $('#hostel_reference_id').trigger('change');
        }
    }, 100);
    
    // Also update fee duration if already selected
    setTimeout(function() {
        if ($('#fee_duration').val()) {
            $('#fee_duration').trigger('change');
        }
    }, 150);
});
</script>
@endsection