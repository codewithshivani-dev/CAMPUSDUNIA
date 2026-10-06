@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<style>
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

.badge-onetime {
    background: #d1e7dd;
    color: #0f5132;
}

.badge-monthly {
    background: #cfe2ff;
    color: #084298;
}

.badge-quarterly {
    background: #fff3cd;
    color: #664d03;
}

.badge-halfyearly {
    background: #f8d7da;
    color: #842029;
}

.badge-yearly {
    background: #e7c6ff;
    color: #5a189a;
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

.installment-card label {
    font-weight: 500;
    font-size: 14px;
    margin-bottom: 5px;
}

.academic-year-info {
    animation: fadeIn 0.5s ease-in-out;
}

.academic-year-info .alert {
    border-left: 4px solid #0d6efd;
    border-radius: 8px;
}

.academic-year-dates {
    background: rgba(13, 110, 253, 0.1);
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 14px;
}

.academic-year-status {
    background: rgba(25, 135, 84, 0.1);
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 14px;
}

.installment-amount {
    font-weight: 600;
    color: #198754;
}

.installment-date {
    font-size: 14px;
    color: #6c757d;
}

.installments-header {
    display: flex;
    justify-content: space-between;
    align-items-center;
    margin-bottom: 15px;
}

.installments-count {
    background: #6c757d;
    color: white;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
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

.installment-container {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 10px;
}

.fee-preview-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.fee-preview-card .form-label {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.9rem;
    margin-bottom: 5px;
}

.fee-preview-card .preview-value {
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
}

.fee-type-badge {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.alert-primary {
    border-left: 4px solid #0d6efd;
}

.alert-success {
    border-left: 4px solid #198754;
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
</style>

<div class="container-fluid mt-4">
    <div class="card shadow-sm p-4">
        <h3><i class="fas fa-money-bill-wave me-2"></i>Assign Custom Fee</h3>
        <hr>

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

        <form method="POST" action="{{ route('admin.custom-fees.assign.store') }}" id="assignFeeForm">
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
                    <h5 class="section-title">Step 2: Select Assignee <span id="assigneeCount" class="assignee-counter"
                            style="display: none;">0</span></h5>
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
                        placeholder="Search by name, ID, or roll number...">
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

            <!-- Step 3: Select Custom Fee -->
            <div class="section-box">
                <div class="section-header">
                    <div class="section-icon">💰</div>
                    <h5 class="section-title">Step 3: Select Custom Fee</h5>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Select Custom Fee *</label>
                        <select name="custom_reference_id" id="custom_reference_id" class="form-control" required>
                            <option value="">Select Custom Fee</option>
                            @foreach($customFees as $fee)
                                <option value="{{ $fee->custom_reference_id }}"
                                    data-amount="{{ $fee->custom_fee_value }}"
                                    data-name="{{ $fee->custom_fee_key }}"
                                    data-type="{{ $fee->fee_type }}"
                                    data-late-fee-type="{{ $fee->late_fee_type }}"
                                    data-late-fee-value="{{ $fee->late_fee_value }}"
                                    data-partial-fee-type="{{ $fee->partially_fee_type }}"
                                    data-partial-fee-value="{{ $fee->partially_fee_value }}">
                                    {{ $fee->custom_fee_key }} (₹{{ $fee->custom_fee_value }}) - {{ ucfirst($fee->fee_type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Custom Fee Preview -->
            <div class="section-box" id="feePreview" style="display: none;">
                <div class="section-header">
                    <div class="section-icon">👁️</div>
                    <h5 class="section-title">Fee Preview</h5>
                </div>

                <div class="fee-preview-card">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-label">Fee Name:</div>
                            <div id="previewFeeName" class="preview-value">-</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label">Fee Type:</div>
                            <div id="previewFeeType" class="preview-value">
                                <span class="fee-type-badge" id="previewFeeTypeBadge">-</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-label">Base Amount:</div>
                            <div id="previewBaseAmount" class="preview-value">₹0.00</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label">Late Fee:</div>
                            <div id="previewLateFee" class="preview-value">₹0.00</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-label">Partial Fee:</div>
                            <div id="previewPartialFee" class="preview-value">₹0.00</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label">Total One-Time Fee:</div>
                            <div id="previewTotalFee" class="preview-value" style="font-size: 1.5rem; font-weight: bold;">₹0.00</div>
                        </div>
                    </div>
                    <div class="small mt-3" style="opacity: 0.9;">
                        <i class="fas fa-info-circle me-1"></i>
                        Discount can be applied when configuring payment schedule
                    </div>
                </div>
            </div>

            <!-- Step 4: Fee Configuration -->
            <div class="section-box">
                <div class="section-header">
                    <div class="section-icon">⚙️</div>
                    <h5 class="section-title">Step 4: Configure Payment Schedule</h5>
                </div>

                <div class="row">
                    <!-- Payment Duration -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Payment Duration *</label>
                        <select name="fee_duration" id="fee_duration" class="form-control" required>
                            <option value="one_time">One Time (Single payment)</option>
                            <option value="monthly">Monthly (12 installments)</option>
                            <option value="quarterly">Quarterly (4 installments)</option>
                            <option value="half_yearly">Half Yearly (2 installments)</option>
                            <option value="yearly">Yearly (1 installment)</option>
                        </select>
                    </div>
                </div>

                <!-- Discount Section -->
                <div class="card border-primary mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-percentage me-2"></i>Discount (Optional)
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount Type</label>
                                <select name="discount_type" id="discount_type" class="form-control">
                                    <option value="">No Discount</option>
                                    <option value="percentage">Percentage (%)</option>
                                    <option value="fixed">Fixed Amount (₹)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount Value</label>
                                <div class="input-group">
                                    <input type="number" name="discount_value" id="discount_value" class="form-control"
                                        min="0" step="0.01" placeholder="Enter discount" disabled>
                                    <span class="input-group-text" id="discount_suffix">₹</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Discount Reason (Optional)</label>
                                <textarea name="discount_reason" id="discount_reason" class="form-control" rows="2"
                                    placeholder="Reason for discount (e.g., Sibling discount, Early bird discount)"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 5: Installment Details -->
            <div class="section-box" id="installmentsSection" style="display: none;">
                <div class="section-header">
                    <div class="section-icon">📅</div>
                    <h5 class="section-title">Step 5: Installment Details
                        <span id="installmentsCount" class="installments-count" style="display: none;">0</span>
                    </h5>
                </div>

                <div class="installment-container" id="installmentsContainer">
                    <!-- Installment inputs will be loaded here -->
                </div>

                <div id="noInstallmentsMessage" class="text-center text-muted py-4">
                    <i class="fas fa-calendar-alt fa-2x mb-3"></i>
                    <p>Please select a custom fee to configure installments</p>
                </div>

                <!-- Installment Total -->
                <div id="installmentsTotal" class="installment-total" style="display: none;">
                    Total Installment Amount: ₹<span id="totalInstallmentAmount">0.00</span>
                </div>
            </div>

            <!-- Step 6: Fee Summary -->
            <div class="section-box">
                <div class="section-header">
                    <div class="section-icon">📋</div>
                    <h5 class="section-title">Fee Summary</h5>
                </div>

                <div id="feeSummary" class="fee-summary-card" style="display: none;">
                    <div class="text-center mb-4">
                        <span class="duration-badge" id="durationBadge">One Time</span>
                    </div>

                    <div class="fee-row">
                        <span>Fee Name:</span>
                        <span id="summary_fee_name">-</span>
                    </div>

                    <div class="fee-row">
                        <span>Base Amount:</span>
                        <span id="summary_base_amount">₹0.00</span>
                    </div>

                    <div class="fee-row">
                        <span>Late Fee:</span>
                        <span id="summary_late_fee">₹0.00</span>
                    </div>

                    <div class="fee-row">
                        <span>Partial Fee:</span>
                        <span id="summary_partial_fee">₹0.00</span>
                    </div>

                    <div class="fee-row">
                        <span>Payment Duration:</span>
                        <span id="summary_duration">One Time</span>
                    </div>

                    <div class="fee-row">
                        <span>Total Installments:</span>
                        <span id="summary_installments">1</span>
                    </div>

                    <div class="fee-row">
                        <span>Discount:</span>
                        <span id="summary_discount" class="text-success">₹0.00</span>
                    </div>

                    <div class="fee-row total">
                        <span>Total Payable:</span>
                        <span id="summary_total">₹0.00</span>
                    </div>

                    <div class="mt-3 text-muted small">
                        <i class="fas fa-info-circle me-1"></i>
                        Note: Installment dates and amounts can be customized below
                    </div>
                </div>

                <div id="noSummaryMessage" class="text-center text-muted py-4">
                    <i class="fas fa-calculator fa-2x mb-3"></i>
                    <p>Select a custom fee to see fee summary</p>
                </div>
            </div>

            <!-- Hidden Fields -->
            <input type="hidden" name="assignee_id" id="assignee_id">
            <input type="hidden" name="assignee_name" id="assignee_name">
            <input type="hidden" name="assignee_type_display" id="assignee_type_display">
            <input type="hidden" name="academic_year_id" id="academic_year_id">

            <!-- Submit Button -->
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success btn-lg px-5" id="submitBtn">
                    <i class="fas fa-check-circle me-2"></i>Assign Custom Fee
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let selectedAssignee = null;
    let baseFee = 0;
    let lateFee = 0;
    let partialFee = 0;
    let allAssignees = [];
    let filteredAssignees = [];
    let academicYearData = null;
    let customFeeData = null;

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

    // Custom fee selection handler
    $('#custom_reference_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        
        if (!$(this).val()) {
            $('#feePreview').hide();
            hideInstallments();
            return;
        }

        // Get custom fee data from data attributes
        baseFee = parseFloat(selectedOption.data('amount')) || 0;
        const feeName = selectedOption.data('name') || '';
        const feeType = selectedOption.data('type') || '';
        const lateFeeType = selectedOption.data('late-fee-type') || 'none';
        const lateFeeValue = parseFloat(selectedOption.data('late-fee-value')) || 0;
        const partialFeeType = selectedOption.data('partial-fee-type') || 'none';
        const partialFeeValue = parseFloat(selectedOption.data('partial-fee-value')) || 0;

        // Calculate late fee
        if (lateFeeType === 'fixed') {
            lateFee = lateFeeValue;
        } else if (lateFeeType === 'percentage') {
            lateFee = (baseFee * lateFeeValue) / 100;
        } else {
            lateFee = 0;
        }

        // Calculate partial fee
        if (partialFeeType === 'fixed') {
            partialFee = partialFeeValue;
        } else if (partialFeeType === 'percentage') {
            partialFee = (baseFee * partialFeeValue) / 100;
        } else {
            partialFee = 0;
        }

        // Store custom fee data
        customFeeData = {
            name: feeName,
            type: feeType,
            baseAmount: baseFee,
            lateFee: lateFee,
            partialFee: partialFee,
            totalOneTime: baseFee
        };
        // Update preview
        updateFeePreview();
        $('#feePreview').show();
        
        // Show installment inputs based on selected duration
        showInstallmentInputs();
        
        // Update fee summary
        updateFeeSummary();
    });

    // Fee duration change handler
    $('#fee_duration').on('change', function() {
        if (baseFee > 0) {
            showInstallmentInputs();
        }
        updateFeeSummary();
    });

    // Discount type change handler
    $('#discount_type').on('change', function() {
        const discountType = $(this).val();
        const discountInput = $('#discount_value');

        if (discountType) {
            discountInput.prop('disabled', false);
            discountInput.prop('required', true);
            $('#discount_suffix').text(discountType === 'percentage' ? '%' : '₹');
        } else {
            discountInput.prop('disabled', true);
            discountInput.prop('required', false);
            discountInput.val('');
            $('#discount_suffix').text('₹');
        }
        updateFeeSummary();
    });

    // Discount value change handler
    $('#discount_value').on('input', function() {
        updateFeeSummary();
    });

    // Clear selection handler
    $('#clearSelection').on('click', function() {
        clearAssigneeSelection();
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

                    if (assigneeType === 'student' && response.students && response.students.length > 0) {
                        assignees = response.students;
                        count = response.count || assignees.length;
                    } else if (assigneeType === 'employee' && response.employees && response.employees.length > 0) {
                        assignees = response.employees;
                        count = response.count || assignees.length;
                    }

                    if (assignees.length > 0) {
                        allAssignees = assignees;
                        filteredAssignees = [...allAssignees];
                        renderAssignees();
                        $('#assigneeCount').text(count).show();
                        $('#searchContainer').show();
                        $('#searchAssignee').val(''); // Clear search box
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
                // Student data structure
                const firstName = assignee.first_name || '';
                const middleName = assignee.middle_name || '';
                const lastName = assignee.last_name || '';

                // Build full name
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
                // Employee data structure
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
            $('#assignee_type_display').val(selectedAssignee.type === 'student' ? 'Student' : 'Employee');

            // Update selected info display
            $('#selectedName').text(selectedAssignee.name);
            $('#selectedId').text(selectedAssignee.identifier || 'N/A');
            $('#selectedType').text(selectedAssignee.type === 'student' ? 'Student' : 'Employee');
            $('#selectedAssigneeInfo').show();

            // Load academic year based on assignee type
            if (selectedAssignee.type === 'student') {
                loadAcademicYear(selectedAssignee.id);
            } else {
                loadAcademicYearForEmployee();
            }

            updateSubmitButton();
        });
    }

    // Function to load academic year for student
    function loadAcademicYear(studentHashId) {
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
                                                <i class="fas fa-money-bill-wave me-2"></i>
                                                <strong>Custom Fee:</strong> Will be assigned for this academic year
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="small text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Custom fee will be configured based on selected payment duration
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).show();

                    // Show installment inputs if all conditions are met
                    if (customFeeData && baseFee > 0) {
                        showInstallmentInputs();
                    }
                } else {
                    $('#academicYearInfo').html(`
                        <div class="alert alert-warning">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle fa-lg me-3"></i>
                                <div>
                                    <h6 class="alert-heading mb-1">Academic Year Information</h6>
                                    <p class="mb-0">${response.message || 'Academic year not found for this student'}</p>
                                    <small class="text-muted">Please ensure student has an assigned academic year</small>
                                </div>
                            </div>
                        </div>
                    `).show();
                }
            },
            error: function(xhr, status, error) {
                $('#academicYearInfo').html(`
                    <div class="alert alert-danger">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle fa-lg me-3"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Error Loading Information</h6>
                                <p class="mb-0">Unable to fetch academic year details. Please try again.</p>
                            </div>
                        </div>
                    </div>
                `).show();
            }
        });
    }

    // Function to load academic year for employee
    function loadAcademicYearForEmployee() {
        $.ajax({
            url: '{{ route("ajax.employee.academic-year.custom") }}', 
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
                                            <i class="fas fa-money-bill-wave me-2"></i>
                                            <strong>Custom Fee:</strong> From custom fee structure
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="small text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Employee custom fee based on academic year from custom fee structure
                                </div>
                            </div>
                        </div>
                    </div>
                `).show();

                    // Show installment inputs if all conditions are met
                    if (customFeeData && baseFee > 0) {
                        showInstallmentInputs();
                    }
                } else {
                    $('#academicYearInfo').html(`
                    <div class="alert alert-warning">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle fa-lg me-3"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Academic Year Information</h6>
                                <p class="mb-0">${response.message || 'Academic year not found in custom fee structure'}</p>
                                <small class="text-muted">Please ensure custom fee structure has academic year configured</small>
                            </div>
                        </div>
                    </div>
                `).show();
                }
            },
            error: function(xhr, status, error) {
                $('#academicYearInfo').html(`
                <div class="alert alert-danger">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle fa-lg me-3"></i>
                        <div>
                            <h6 class="alert-heading mb-1">Error Loading Information</h6>
                            <p class="mb-0">Unable to fetch academic year details. Please try again.</p>
                        </div>
                    </div>
                </div>
            `).show();
            }
        });
    }

    // Function to update fee preview
    function updateFeePreview() {
        if (!customFeeData) return;

        $('#previewFeeName').text(customFeeData.name);
        $('#previewFeeType').text(customFeeData.type);
        $('#previewFeeTypeBadge').text(customFeeData.type.charAt(0).toUpperCase() + customFeeData.type.slice(1));
        $('#previewBaseAmount').text('₹' + customFeeData.baseAmount.toFixed(2));
        $('#previewLateFee').text('₹' + customFeeData.lateFee.toFixed(2));
        $('#previewPartialFee').text('₹' + customFeeData.partialFee.toFixed(2));
        $('#previewTotalFee').text('₹' + customFeeData.totalOneTime.toFixed(2));
    }

    // Function to show installment inputs
    function showInstallmentInputs() {
        if (!baseFee || baseFee <= 0) {
            return;
        }

        const duration = $('#fee_duration').val();

        $('#installmentsContainer').empty();
        $('#noInstallmentsMessage').hide();
        $('#installmentsSection').show();

        let installmentCount = 0;
        let installmentName = '';
        let defaultMultiplier = 1;

        switch (duration) {
            case 'one_time':
                installmentCount = 1;
                installmentName = 'Full Payment';
                defaultMultiplier = 1; // Full amount
                break;
            case 'monthly':
                installmentCount = 12;
                installmentName = 'Month';
                defaultMultiplier = 1/12; // Divide by 12 for monthly
                break;
            case 'quarterly':
                installmentCount = 4;
                installmentName = 'Quarter';
                defaultMultiplier = 1/4; // Divide by 4 for quarterly
                break;
            case 'half_yearly':
                installmentCount = 2;
                installmentName = 'Half Year';
                defaultMultiplier = 1/2; // Divide by 2 for half-yearly
                break;
            case 'yearly':
                installmentCount = 1;
                installmentName = 'Year';
                defaultMultiplier = 1; // Full amount
                break;
        }

        // Calculate total one-time fee
        const totalOneTimeFee = customFeeData ? customFeeData.totalOneTime : baseFee;
        
        // Calculate default amount per installment
        const defaultAmount = totalOneTimeFee * defaultMultiplier;

        // Create installment inputs
        for (let i = 1; i <= installmentCount; i++) {
            const installmentHtml = `
                <div class="card mb-3 installment-card" data-index="${i}">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">${installmentName} ${i}</h6>
                        <span class="badge bg-primary">
                            ${duration === 'one_time' ? 'Full Amount' : 
                              duration === 'monthly' ? 'Monthly' :
                              duration === 'quarterly' ? 'Quarterly' :
                              duration === 'half_yearly' ? 'Half-Yearly' : 'Yearly'}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Amount (₹) *</label>
                                <input type="number" 
                                       name="installments[${i}][amount]" 
                                       class="form-control installment-amount"
                                       value="${defaultAmount.toFixed(2)}"
                                       step="0.01"
                                       min="0"
                                       required>
                                <small class="text-muted">${duration === 'one_time' ? 'Full custom fee amount' : 'Calculated based on ' + duration + ' payment'}</small>
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
                        <input type="hidden" name="installments[${i}][months_covered]" value="${defaultMultiplier === 1/12 ? 1 : defaultMultiplier === 1/4 ? 3 : defaultMultiplier === 1/2 ? 6 : 12}">
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
    }

    // Function to auto-fill installment dates
    function autoFillInstallmentDates(duration, academicYearData) {
        const startDate = new Date(academicYearData.start_date);
        const endDate = new Date(academicYearData.end_date);

        $('.installment-card').each(function(index) {
            const cardIndex = $(this).data('index');
            let installmentStartDate, installmentDueDate;

            switch (duration) {
                case 'one_time':
                    // One-time payment - due at end of academic year
                    installmentStartDate = new Date(startDate);
                    installmentDueDate = new Date(endDate);
                    break;

                case 'monthly':
                    // Monthly installments
                    installmentStartDate = new Date(startDate);
                    installmentStartDate.setMonth(startDate.getMonth() + (cardIndex - 1));

                    installmentDueDate = new Date(installmentStartDate);
                    installmentDueDate.setMonth(installmentStartDate.getMonth() + 1);
                    break;

                case 'quarterly':
                    // Quarterly installments (3 months each)
                    installmentStartDate = new Date(startDate);
                    installmentStartDate.setMonth(startDate.getMonth() + ((cardIndex - 1) * 3));

                    installmentDueDate = new Date(installmentStartDate);
                    installmentDueDate.setMonth(installmentStartDate.getMonth() + 3);
                    break;

                case 'half_yearly':
                    // Half-yearly installments (6 months each)
                    installmentStartDate = new Date(startDate);
                    installmentStartDate.setMonth(startDate.getMonth() + ((cardIndex - 1) * 6));

                    installmentDueDate = new Date(installmentStartDate);
                    installmentDueDate.setMonth(installmentStartDate.getMonth() + 6);
                    break;

                case 'yearly':
                    // Yearly - single installment
                    installmentStartDate = new Date(startDate);
                    installmentDueDate = new Date(endDate);
                    break;
            }

            // Adjust due date if it exceeds academic year end
            if (installmentDueDate > endDate) {
                installmentDueDate = new Date(endDate);
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
                    const middleName = assignee.middle_name || '';
                    const lastName = assignee.last_name || '';
                    const fullName = (firstName + ' ' + middleName + ' ' + lastName).toLowerCase().trim();
                    const rollNo = assignee.registration_number || '';

                    return fullName.includes(searchTerm) ||
                        rollNo.toLowerCase().includes(searchTerm);
                } else if (assigneeType === 'employee') {
                    const fullName = (assignee.name || '').toLowerCase();
                    const employeeId = (assignee.employee_id || '').toLowerCase();
                    
                    return fullName.includes(searchTerm) ||
                        employeeId.includes(searchTerm);
                }
                return false;
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
        if (!baseFee || baseFee <= 0 || !customFeeData) {
            $('#feeSummary').hide();
            $('#noSummaryMessage').show();
            return;
        }

        const duration = $('#fee_duration').val();
        const discountType = $('#discount_type').val();
        const discountValue = parseFloat($('#discount_value').val()) || 0;

        // Calculate installment total
        const installmentTotal = updateInstallmentsTotal();
        const installmentCount = $('.installment-card').length;

        // Calculate discount
        let discountAmount = 0;
        if (discountType === 'percentage') {
            discountAmount = (installmentTotal * discountValue) / 100;
        } else if (discountType === 'fixed') {
            discountAmount = discountValue;
        }

        // Calculate total payable after discount
        const totalPayable = installmentTotal - discountAmount;

        // Update summary display
        $('#summary_fee_name').text(customFeeData.name);
        $('#summary_base_amount').text('₹' + customFeeData.baseAmount.toFixed(2));
        $('#summary_late_fee').text('₹' + customFeeData.lateFee.toFixed(2));
        $('#summary_partial_fee').text('₹' + customFeeData.partialFee.toFixed(2));
        $('#summary_duration').text(duration.charAt(0).toUpperCase() + duration.slice(1).replace('_', ' '));
        $('#summary_installments').text(installmentCount);
        $('#summary_discount').text(discountAmount > 0 ? '-₹' + discountAmount.toFixed(2) : '₹0.00');
        $('#summary_total').text('₹' + totalPayable.toFixed(2));

        // Update duration badge
        const badgeClass = 'badge-' + duration.replace('_', '');
        $('#durationBadge')
            .removeClass('badge-onetime badge-monthly badge-quarterly badge-halfyearly badge-yearly')
            .addClass(badgeClass)
            .text(duration.charAt(0).toUpperCase() + duration.slice(1).replace('_', ' '));

        $('#feeSummary').show();
        $('#noSummaryMessage').hide();
    }

    // Function to update submit button state
    function updateSubmitButton() {
        const hasAssignee = selectedAssignee !== null;
        const hasCustomFee = $('#custom_reference_id').val() !== '';
        const hasInstallments = $('.installment-card').length > 0;
        // Validate all installment inputs
        let allInstallmentsValid = true;
        if (hasInstallments) {
            $('.installment-amount, .installment-start-date, .installment-due-date').each(function(index) {
                if (!$(this).val()) {
                    allInstallmentsValid = false;
                }
            });
        }
        const allValid = hasAssignee && hasCustomFee && hasInstallments && allInstallmentsValid; 
    }

    // Installment amount change handler
    $(document).on('input', '.installment-amount', function() {
        updateInstallmentsTotal();
        updateFeeSummary();
    });

    // Installment date change handler
    $(document).on('change', '.installment-start-date, .installment-due-date', function() {
        updateSubmitButton();
    });

    // Form submission handler
    $('#assignFeeForm').on('submit', function(e) {
        e.preventDefault();

        // Validate discount if type selected
        const discountType = $('#discount_type').val();
        const discountValue = $('#discount_value').val();

        if (discountType && (!discountValue || parseFloat(discountValue) <= 0)) {
            alert('Please enter a valid discount value');
            $('#discount_value').focus();
            return;
        }

        if (discountType === 'percentage' && parseFloat(discountValue) > 100) {
            alert('Percentage discount cannot exceed 100%');
            $('#discount_value').focus();
            return;
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

        // Validate dates are within academic year if available
        if (academicYearData) {
            const academicStart = new Date(academicYearData.start_date);
            const academicEnd = new Date(academicYearData.end_date);

            $('.installment-card').each(function() {
                const startDate = new Date($(this).find('.installment-start-date').val());
                const dueDate = new Date($(this).find('.installment-due-date').val());

                if (startDate < academicStart || dueDate > academicEnd) {
                    alert('All installment dates must be within the academic year: ' +
                        academicYearData.start_date + ' to ' + academicYearData.end_date);
                    dateError = true;
                    return false;
                }
            });

            if (dateError) return;
        }

        // Show loading
        $('#submitBtn').prop('disabled', true).html(
            '<i class="fas fa-spinner fa-spin me-2"></i>Processing...');

        // Submit formf
        this.submit();
    });

    // Initial update
    updateSubmitButton();
});
</script>
@endsection