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

.stop-item {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 12px 15px;
    margin-bottom: 8px;
    cursor: pointer;
    transition: all 0.2s;
}

.stop-item:hover {
    background: #f8f9fa;
    border-color: #0d6efd;
}

.stop-item.selected {
    background: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

.stop-item.selected .text-muted {
    color: rgba(255, 255, 255, 0.8) !important;
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

.academic-year-info .alert-heading {
    color: #0a58ca;
    font-size: 16px;
}

.academic-year-info hr {
    border-top: 1px dashed #dee2e6;
    margin: 8px 0;
}

.academic-year-info i.fa-graduation-cap {
    color: #6f42c1;
}

.academic-year-info i.fa-calendar-day {
    color: #0d6efd;
}

.academic-year-info i.fa-bus {
    color: #198754;
}

.academic-year-info i.fa-info-circle {
    color: #6c757d;
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
    align-items: center;
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
/* Add these styles to your existing CSS */
.auto-selected {
    border: 2px solid #0d6efd !important;
    background: linear-gradient(135deg, #e3f2fd 0%, #f0f8ff 100%) !important;
}

.fee-preview {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stop-item:hover .text-success {
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

.selected .fa-check-circle {
    animation: bounce 0.5s ease;
}

@keyframes bounce {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.2); }
}
</style>

<div class="container-fluid mt-4">
    <div class="card shadow-sm p-4">
        <h3><i class="fas fa-bus-alt me-2"></i>Assign Transport Fee</h3>
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

        <form method="POST" action="{{ route('admin.transport.assign-fee.store') }}" id="assignFeeForm">
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

            <!-- Step 3: Select Transport Route -->
            <div class="section-box">
                <div class="section-header">
                    <div class="section-icon">🗺️</div>
                    <h5 class="section-title">Step 3: Select Transport Route</h5>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Select Route *</label>
                        <select name="transport_reference_id" id="transport_reference_id" class="form-control" required>
                            <option value="">Select Route</option>
                            @foreach($transports as $transport)
                            @php
                            $latestFee = $transport->fees()->where('status', 'active')->orderBy('created_at',
                            'desc')->first();
                            $monthlyFee = $latestFee ? $latestFee->monthly_fee : 0;
                            @endphp
                            <option value="{{ $transport->transport_reference_id }}"
                                data-type="{{ $transport->route_type }}" data-fee="{{ $monthlyFee }}">
                                {{ $transport->bus_number }} - {{ $transport->route_name }}
                                ({{ ucfirst($transport->route_type) }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Monthly Fee</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="text" id="route_monthly_fee" class="form-control" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add this section after Step 3 (Route Selection) -->
            <div class="section-box" id="routeFeePreview" style="display: none;">
                <div class="section-header">
                    <div class="section-icon">💰</div>
                    <h5 class="section-title">Route Fee Preview</h5>
                </div>
                
                <div class="alert alert-info">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <strong>Route:</strong> <span id="previewRouteName">-</span>
                            </div>
                            <div class="mb-2">
                                <strong>Monthly Fee:</strong> ₹<span id="previewMonthlyFee">0.00</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <strong>Selected Duration:</strong> 
                                <span  id="previewDuration">Monthly</span>
                            </div>
                            <div class="mb-2">
                                <strong>Total Annual Fee:</strong> ₹<span id="previewAnnualFee">0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4: Select Bus Stop -->
            <div class="section-box">
                <div class="section-header">
                    <div class="section-icon">🚏</div>
                    <h5 class="section-title">Step 4: Select Bus Stop (Optional) 
                        <span id="stopsCount" class="assignee-counter" style="display: none;">0</span>
                    </h5>
                </div>
                 <div id="stopsInfo" class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Optional:</strong> Select a specific stop.
                </div>

                <div id="stopsLoading" class="text-center py-3" style="display: none;">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span class="ms-2">Loading stops...</span>
                </div>

                <div id="stopsContainer" class="mb-3" style="display: none;">
                    <p class="text-muted mb-3">Select the bus stop for this assignee:</p>
                    <div id="stopsList">
                        <!-- Stops will be loaded here -->
                    </div>
                </div>

                <div id="noStopsMessage" class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Please select a route to view available stops
                </div>

                <!-- Selected Stop Info -->
                <div id="selectedStopInfo" class="alert alert-success mt-3" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Selected Stop: <span id="selectedStopName"></span></strong>
                            <div class="small mt-1">Monthly Fee: ₹<span id="selectedStopFee"></span></div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="clearStopSelection">
                            <i class="fas fa-times me-1"></i>Clear
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 5: Fee Details & Discount -->
            <div class="section-box">
                <div class="section-header">
                    <div class="section-icon">💰</div>
                    <h5 class="section-title">Step 5: Fee Details & Discount</h5>
                </div>

                <div class="row">
                    <!-- Fee Duration -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fee Duration *</label>
                        <select name="fee_duration" id="fee_duration" class="form-control" required>
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

            <!-- Step 6: Installment Details -->
            <div class="section-box" id="installmentsSection" style="display: none;">
                <div class="section-header">
                    <div class="section-icon">📅</div>
                    <h5 class="section-title">Step 6: Installment Details
                        <span id="installmentsCount" class="installments-count" style="display: none;">0</span>
                    </h5>
                </div>

                <div id="installmentsContainer">
                    <!-- Installment inputs will be loaded here -->
                </div>

                <div id="noInstallmentsMessage" class="text-center text-muted py-4">
                    <i class="fas fa-calendar-alt fa-2x mb-3"></i>
                    <p>Select a student, transport route, stop, and fee duration to configure installments</p>
                </div>

                <!-- Installment Total -->
                <div id="installmentsTotal" class="installment-total" style="display: none;">
                    Total Installment Amount: ₹<span id="totalInstallmentAmount">0.00</span>
                </div>
            </div>

            <!-- Step 7: Fee Summary -->
            <div class="section-box">
                <div class="section-header">
                    <div class="section-icon">📋</div>
                    <h5 class="section-title">Fee Summary</h5>
                </div>

                <div id="feeSummary" class="fee-summary-card" style="display: none;">
                    <div class="text-center mb-4">
                        <span class="duration-badge" id="durationBadge">Monthly</span>
                    </div>

                    <div class="fee-row">
                        <span>Monthly Fee:</span>
                        <span id="summary_monthly_fee">₹0.00</span>
                    </div>

                    <div class="fee-row">
                        <span>Duration:</span>
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

                    <div class="fee-row">
                        <span>Discount:</span>
                        <span id="summary_discount">₹0.00</span>
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
                    <p>Complete all steps above to see fee summary</p>
                </div>
            </div>

            <!-- Hidden Fields -->
            <input type="hidden" name="assignee_id" id="assignee_id">
            <input type="hidden" name="assignee_name" id="assignee_name">
            <input type="hidden" name="assignee_type_display" id="assignee_type_display">
            <input type="hidden" name="stop_id" id="stop_id">
            <input type="hidden" name="stop_name" id="stop_name">
            <input type="hidden" name="monthly_fee" id="monthly_fee_hidden">
            <input type="hidden" name="academic_year_id" id="academic_year_id">

            <!-- Submit Button -->
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success btn-lg px-5" id="submitBtn" disabled>
                    <i class="fas fa-check-circle me-2"></i>Assign Transport Fee
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let selectedAssignee = null;
    let selectedStop = null;
    let monthlyFee = 0;
    let routeType = '';
    let allAssignees = [];
    let filteredAssignees = [];
    let academicYearData = null;

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

    // Route selection handler
    $('#transport_reference_id').on('change', function() {
        const transportId = $(this).val();
        const selectedOption = $(this).find('option:selected');

        if (!transportId) {
            $('#route_monthly_fee').val('');
            $('#stopsContainer').hide();
            $('#selectedStopInfo').hide();
            $('#noStopsMessage').show();
            monthlyFee = 0;
            routeType = '';
            selectedStop = null;
            updateFeeSummary();
            return;
        }

        // Get monthly fee from data attribute
        monthlyFee = parseFloat(selectedOption.data('fee')) || 0;
        routeType = selectedOption.data('type') || '';

        $('#route_monthly_fee').val('₹' + monthlyFee.toFixed(2));
        $('#monthly_fee_hidden').val(monthlyFee);
        
          // Show route fee preview
        showRouteFeePreview();
        // Show fee summary immediately when route is selected
        updateFeeSummaryForRouteOnly();
        
        // Load stops for this route (Whole Route will be auto-selected)
        loadStops(transportId);
    });

    // Fee duration change handler
    $('#fee_duration').on('change', function() {
        if (selectedAssignee && monthlyFee > 0) {
            showInstallmentInputs();
        }
          // Update route fee preview
        if (monthlyFee > 0) {
            showRouteFeePreview();
        }
        updateFeeSummary();
        
        // If no assignee is selected yet but route is selected, still show fee summary
        if (!selectedAssignee && monthlyFee > 0) {
            updateFeeSummaryForRouteOnly();
        }
    });

    // Discount type change handler
    $('#discount_type').on('change', function() {
        const discountType = $(this).val();
        const discountInput = $('#discount_value');

        if (discountType) {
            discountInput.prop('disabled', false).prop('required', true);
            $('#discount_suffix').text(discountType === 'percentage' ? '%' : '₹');
        } else {
            discountInput.prop('disabled', true).prop('required', false).val('');
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

    $('#clearStopSelection').on('click', function() {
        clearStopSelection();
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
                        $('#searchAssignee').val(''); // Clear search box
                    } else {
                        showNoResults('No ' + assigneeType + 's found in this department');
                    }
                } else {
                    showNoResults('No ' + assigneeType + 's found in this department');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
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
                const fullName = assignee.name;

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
                loadAcademicYear(selectedAssignee.id);
            } else {
                // For employees, get academic year from transport_fees
                loadAcademicYearForEmployee();
            }

            updateSubmitButton();
        });
    }

    // Function to load academic year for employee
    function loadAcademicYearForEmployee() {
        $.ajax({
            url: '{{ route("ajax.employee.academic-year.transport") }}', // You'll need to add this route
            method: 'GET',
            success: function(response) {
                console.log('Employee Academic Year Response:', response);

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
                                            <i class="fas fa-bus me-2"></i>
                                            <strong>Transport Fee:</strong> From transport fee structure
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="small text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Employee transport fee based on academic year from transport fee structure
                                </div>
                            </div>
                        </div>
                    </div>
                `).show();

                    // Show installment inputs if all conditions are met
                    if (selectedStop && monthlyFee > 0) {
                        showInstallmentInputs();
                    }
                } else {
                    $('#academicYearInfo').html(`
                    <div class="alert alert-warning">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle fa-lg me-3"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Academic Year Information</h6>
                                <p class="mb-0">${response.message || 'Academic year not found in transport fee structure'}</p>
                                <small class="text-muted">Please ensure transport fee structure has academic year configured</small>
                            </div>
                        </div>
                    </div>
                `).show();
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
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
    // Function to load academic year for student
    function loadAcademicYear(studentHashId) {
        $.ajax({
            url: '{{ route("ajax.student.academic-year") }}',
            method: 'GET',
            data: {
                student_hash_id: studentHashId
            },
            success: function(response) {
                console.log('Academic Year Response:', response); // Debug log

                if (response.status) {
                    academicYearData = response.academic_year;
                    $('#academic_year_id').val(academicYearData.id);

                    // Create academic year display without showing months
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
                                                <i class="fas fa-bus me-2"></i>
                                                <strong>Transport Fee:</strong> Will be assigned for this academic year
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="small text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Transport fee will be configured based on selected duration (Monthly/Quarterly/Half-Yearly/Yearly)
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).show();

                    // Show installment inputs if all conditions are met
                    if (selectedStop && monthlyFee > 0) {
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
                console.error('AJAX Error:', error); // Debug log
                $('#academicYearInfo').html(`
                    <div class="alert alert-danger">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle fa-lg me-3"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Error Loading Information</h6>
                                <p class="mb-0">Unable to fetch academic year details. Please try again.</p>
                                <small class="text-muted">Check your internet connection and try refreshing the page</small>
                            </div>
                        </div>
                    </div>
                `).show();
            }
        });
    }

    // Function to show installment inputs
    function showInstallmentInputs() {
        if (!selectedAssignee || monthlyFee <= 0) {
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
            case 'monthly':
                installmentCount = 12;
                installmentName = 'Month';
                defaultMultiplier = 1;
                break;
            case 'quarterly':
                installmentCount = 4;
                installmentName = 'Quarter';
                defaultMultiplier = 3;
                break;
            case 'half_yearly':
                installmentCount = 2;
                installmentName = 'Half Year';
                defaultMultiplier = 6;
                break;
            case 'yearly':
                installmentCount = 1;
                installmentName = 'Year';
                defaultMultiplier = 12;
                break;
        }

        // Calculate default amount
        const defaultAmount = monthlyFee * defaultMultiplier;

        // Create installment inputs
        for (let i = 1; i <= installmentCount; i++) {
            const installmentHtml = `
                <div class="card mb-3 installment-card" data-index="${i}">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">${installmentName} ${i}</h6>
                        <span>
                            ${defaultMultiplier} month(s) × ₹${monthlyFee.toFixed(2)}
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
                                <small class="text-muted">Calculated based on ${duration} fee</small>
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
                        <input type="hidden" name="installments[${i}][months_covered]" value="${defaultMultiplier}">
                    </div>
                </div>
            `;

            $('#installmentsContainer').append(installmentHtml);
        }

        // Update installment count
        $('#installmentsCount').text(`${installmentCount} Installments`).show();

        // Auto-fill dates if academic year data exists
        if (academicYearData) {
            autoFillInstallmentDates(duration, academicYearData);
        }

        // Update total and summary
        updateInstallmentsTotal();
        updateFeeSummary();
    }
    
    function updateFeeSummaryForRouteOnly() {
        if (monthlyFee <= 0) {
            $('#feeSummary').hide();
            $('#noSummaryMessage').show();
            return;
        }

        const duration = $('#fee_duration').val();
        const discountType = $('#discount_type').val();
        const discountValue = parseFloat($('#discount_value').val()) || 0;

        let installmentCount = 0;
        let defaultMultiplier = 1;
        let totalAmount = 0;

        switch (duration) {
            case 'monthly':
                installmentCount = 12;
                defaultMultiplier = 1;
                totalAmount = monthlyFee * 12;
                break;
            case 'quarterly':
                installmentCount = 4;
                defaultMultiplier = 3;
                totalAmount = monthlyFee * 12; // Still 12 months total
                break;
            case 'half_yearly':
                installmentCount = 2;
                defaultMultiplier = 6;
                totalAmount = monthlyFee * 12; // Still 12 months total
                break;
            case 'yearly':
                installmentCount = 1;
                defaultMultiplier = 12;
                totalAmount = monthlyFee * 12;
                break;
        }

        // Calculate discount
        let discountAmount = 0;
        if (discountType === 'percentage') {
            discountAmount = (totalAmount * discountValue) / 100;
        } else if (discountType === 'fixed') {
            discountAmount = discountValue;
        }

        // Calculate total payable after discount
        const totalPayable = totalAmount - discountAmount;

        // Update summary display
        $('#summary_monthly_fee').text('₹' + monthlyFee.toFixed(2));
        $('#summary_duration').text(duration.charAt(0).toUpperCase() + duration.slice(1));
        $('#summary_installments').text(installmentCount);
        $('#summary_installment_total').text('₹' + totalAmount.toFixed(2));
        $('#summary_discount').text('₹' + discountAmount.toFixed(2));
        $('#summary_total').text('₹' + totalPayable.toFixed(2));

        // Update duration badge
        $('#durationBadge')
            .removeClass('badge-monthly badge-quarterly badge-halfyearly badge-yearly')
            .addClass('badge-' + duration)
            .text(duration.charAt(0).toUpperCase() + duration.slice(1));

        $('#feeSummary').show();
        $('#noSummaryMessage').hide();
    }
    // Function to auto-fill installment dates
    function autoFillInstallmentDates(duration, academicYearData) {
        const startDate = new Date(academicYearData.start_date);
        const endDate = new Date(academicYearData.end_date);

        $('.installment-card').each(function(index) {
            const cardIndex = $(this).data('index');
            let installmentStartDate, installmentDueDate;

            switch (duration) {
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
                    const fullName = (firstName + ' ' + middleName + ' ' + lastName).toLowerCase()
                        .trim();
                    const rollNo = assignee.registration_number || '';

                    return fullName.includes(searchTerm) ||
                        rollNo.toLowerCase().includes(searchTerm);
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
    
    // Function to show route fee preview

function showRouteFeePreview() {
    const selectedOption = $('#transport_reference_id').find('option:selected');
    const routeName = selectedOption.text().split(' - ')[1] || selectedOption.text();
    const duration = $('#fee_duration').val();
    
    $('#previewRouteName').text(routeName);
    $('#previewMonthlyFee').text(monthlyFee.toFixed(2));
    $('#previewDuration').text(duration.charAt(0).toUpperCase() + duration.slice(1));
    
    // Calculate annual fee based on current monthly fee
    let annualFee = monthlyFee * 12;
    $('#previewAnnualFee').text(annualFee.toFixed(2));
    
    $('#routeFeePreview').show();
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
    
    // Add this function to handle "Whole Route" option
    function addWholeRouteOption() {
        const wholeRouteHtml = `
            <div class="stop-item selected" 
                id="wholeRouteOption"
                data-id="route_default" 
                data-name="Whole Route" 
                data-fee="${monthlyFee}"
                data-order="0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div>
                            <i class="fas fa-route me-2"></i>Whole Route
                        </div>
                        <div class="small text-muted">Full route fee (Auto-selected)</div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold">₹${parseFloat(monthlyFee).toFixed(2)}</div>
                        <div class="small text-muted">per month</div>
                        <div class="small text-success mt-1">
                            <i class="fas fa-check-circle me-1"></i>Selected
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Add whole route option at the beginning of stops list
        $('#stopsList').prepend(wholeRouteHtml);
    }

   // Update the loadStops function
    function loadStops(transportId) {
        $('#stopsLoading').show();
        $('#stopsContainer').hide();
        $('#selectedStopInfo').hide();
        $('#noStopsMessage').hide();

        $.ajax({
            url: '{{ route("ajax.transport.stops") }}',
            method: 'GET',
            data: {
                transport_reference_id: transportId
            },
            success: function(response) {
                $('#stopsLoading').hide();

                if (response.status) {
                    let html = '';
                    let stopCount = 0;
                    
                    // Store the route's default monthly fee
                    const routeMonthlyFee = monthlyFee;
                    
                    // Add Whole Route option first (auto-selected)
                    const wholeRouteHtml = `
                        <div class="stop-item selected" 
                            id="wholeRouteOption"
                            data-id="route_default" 
                            data-name="Whole Route" 
                            data-fee="${routeMonthlyFee}"
                            data-order="0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div>
                                        <i class="fas fa-route me-2"></i>Whole Route
                                    </div>
                                    <div class="small text-muted">Full route fee (Auto-selected)</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">₹${parseFloat(routeMonthlyFee).toFixed(2)}</div>
                                    <div class="small text-muted">per month</div>
                                    <div class="small text-success mt-1">
                                        <i class="fas fa-check-circle me-1"></i>Selected
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Auto-select Whole Route initially
                    selectedStop = {
                        id: 'route_default',
                        name: 'Whole Route',
                        fee: routeMonthlyFee,
                        order: 0
                    };
                    
                    // Update hidden fields (empty for whole route)
                    $('#stop_id').val('');
                    $('#stop_name').val('');
                    
                    // Update selected stop info
                    $('#selectedStopName').text(selectedStop.name);
                    $('#selectedStopFee').text(selectedStop.fee.toFixed(2));
                    $('#selectedStopInfo').removeClass('alert-success').addClass('alert-primary').show();
                    
                    // If there are specific stops available, show them
                    if (response.stops && response.stops.length > 0) {
                        stopCount = response.stops.length + 1; // +1 for Whole Route
                        
                        // First add Whole Route
                        $('#stopsList').html(wholeRouteHtml);
                        
                        // Then add actual stops
                        response.stops.forEach(function(stop, index) {
                            const stopFee = stop.monthly_fee || routeMonthlyFee;
                            
                            html += `
                                <div class="stop-item" 
                                    data-id="${stop.stop_id}" 
                                    data-name="${stop.stop_name}" 
                                    data-fee="${stopFee}"
                                    data-order="${index + 1}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold">${stop.stop_name}</div>
                                            <div class="small text-muted">Stop ${index + 1}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold ${stopFee < routeMonthlyFee ? 'text-success' : ''}">₹${parseFloat(stopFee).toFixed(2)}</div>
                                            <div class="small text-muted">per month</div>
                                            ${stopFee < routeMonthlyFee ? 
                                                `<div class="small text-success">
                                                    Save: ₹${(routeMonthlyFee - stopFee).toFixed(2)}
                                                </div>` : 
                                                ''}
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        
                        $('#stopsList').append(html);
                    } else {
                        stopCount = 1; // Only Whole Route
                        $('#stopsList').html(`
                            ${wholeRouteHtml}
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                No specific stops configured for this route. Full route fee will be applied.
                            </div>
                        `);
                    }
                    
                    $('#stopsCount').text(stopCount).show();
                    $('#stopsContainer').show();
                    
                    // Show installment inputs immediately since Whole Route is auto-selected
                    if (selectedAssignee && routeMonthlyFee > 0) {
                        showInstallmentInputs();
                    }
                    
                    // Update fee summary immediately
                    updateFeeSummary();
                    updateSubmitButton();

                    // Add click handler for stop items (for manual selection)
                    $('.stop-item').on('click', function() {
                        $('.stop-item').removeClass('selected');
                        $(this).addClass('selected');

                        selectedStop = {
                            id: $(this).data('id'),
                            name: $(this).data('name'),
                            fee: parseFloat($(this).data('fee')),
                            order: $(this).data('order')
                        };

                        // Update the monthlyFee variable with the selected stop's fee
                        monthlyFee = selectedStop.fee;
                        
                        // Update the route monthly fee display
                        $('#route_monthly_fee').val('₹' + monthlyFee.toFixed(2));
                        $('#monthly_fee_hidden').val(monthlyFee);

                        // Handle Whole Route vs specific stop
                        if (selectedStop.id === 'route_default') {
                            $('#stop_id').val('');
                            $('#stop_name').val('');
                            $('#selectedStopInfo').removeClass('alert-success').addClass('alert-primary');
                        } else {
                            $('#stop_id').val(selectedStop.id);
                            $('#stop_name').val(selectedStop.name);
                            $('#selectedStopInfo').removeClass('alert-primary').addClass('alert-success');
                        }

                        // Update selected stop info
                        $('#selectedStopName').text(selectedStop.name);
                        $('#selectedStopFee').text(selectedStop.fee.toFixed(2));
                        $('#selectedStopInfo').show();

                        // Update route fee preview
                        showRouteFeePreview();
                        
                        // Re-show installment inputs with updated fee
                        if (selectedAssignee) {
                            showInstallmentInputs();
                        }

                        updateFeeSummary();
                        updateSubmitButton();
                    });
                } else {
                    $('#stopsContainer').hide();
                    $('#noStopsMessage').html(`
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            ${response.message || 'Unable to load route information'}
                        </div>
                    `).show();
                    $('#stopsCount').hide();
                }
            },
            error: function(xhr) {
                console.error('Stops AJAX Error:', xhr);
                $('#stopsLoading').hide();
                $('#stopsContainer').hide();
                $('#noStopsMessage').html(`
                    <div class="alert alert-danger mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error loading route information. Please try again.
                    </div>
                `).show();
                $('#stopsCount').hide();
            }
        });
    }

    // Function to calculate and update fee summary
    function updateFeeSummary() {
        // If no assignee selected yet, show route-only summary
        if (!selectedAssignee) {
            updateFeeSummaryForRouteOnly();
            return;
        }

        if (!monthlyFee || monthlyFee <= 0 || !selectedStop) {
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
        $('#summary_monthly_fee').text('₹' + monthlyFee.toFixed(2));
        $('#summary_duration').text(duration.charAt(0).toUpperCase() + duration.slice(1));
        $('#summary_installments').text(installmentCount);
        $('#summary_installment_total').text('₹' + installmentTotal.toFixed(2));
        $('#summary_discount').text('₹' + discountAmount.toFixed(2));
        $('#summary_total').text('₹' + totalPayable.toFixed(2));

        // Update duration badge
        $('#durationBadge')
            .removeClass('badge-monthly badge-quarterly badge-halfyearly badge-yearly')
            .addClass('badge-' + duration)
            .text(duration.charAt(0).toUpperCase() + duration.slice(1));

        $('#feeSummary').show();
        $('#noSummaryMessage').hide();
    }
    // Function to update submit button state
    function updateSubmitButton() {
        const hasAssignee = selectedAssignee !== null;
        const hasRoute = $('#transport_reference_id').val() !== '';
        const hasStop = selectedStop !== null;
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

        const allValid = hasAssignee && hasRoute && hasStop && hasInstallments && allInstallmentsValid;

        $('#submitBtn').prop('disabled', !allValid);
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

        // Submit form
        this.submit();
    });

    // Initial update
    updateFeeSummary();
    updateSubmitButton();
});
</script>
@endsection