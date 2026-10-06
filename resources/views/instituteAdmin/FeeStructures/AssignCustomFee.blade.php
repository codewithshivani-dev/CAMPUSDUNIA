@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
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
    }

    .section-box {
        background: white;
        border: 2px solid var(--border-color);
        border-radius: 20px;
        padding: 24px;
        margin-bottom: 25px;
        border-left: 5px solid var(--primary-color);
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        transition: all 0.3s;
    }

    .section-box:hover {
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.08);
    }

    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--border-color);
    }

    .section-icon {
        font-size: 24px;
        margin-right: 10px;
        color: var(--primary-color);
        background: rgba(67, 97, 238, 0.1);
        padding: 8px;
        border-radius: 12px;
    }

    .section-title {
        margin: 0;
        color: var(--text-dark);
        font-weight: 700;
    }

    .assignee-card {
        background: white;
        border: 2px solid var(--border-color);
        border-radius: 14px;
        padding: 12px 15px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .assignee-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.1);
    }

    .assignee-card.selected {
        background: rgba(67, 97, 238, 0.05);
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .assignee-avatar {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: var(--primary-gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    }

    .fee-summary-card {
        background: white;
        border: 2px solid var(--success-color);
        border-radius: 16px;
        padding: 20px;
        margin-top: 20px;
    }

    .fee-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid var(--border-color);
    }

    .fee-row.total {
        font-weight: bold;
        font-size: 18px;
        color: var(--success-color);
        border-top: 2px solid var(--success-color);
        margin-top: 10px;
        padding-top: 15px;
    }

    .duration-badge {
        padding: 6px 14px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 14px;
    }

    .badge-onetime { background: rgba(16, 185, 129, 0.1); color: #166534; }
    .badge-monthly { background: rgba(67, 97, 238, 0.1); color: #1d4ed8; }
    .badge-quarterly { background: rgba(245, 158, 11, 0.1); color: #92400e; }
    .badge-halfyearly { background: rgba(239, 68, 68, 0.1); color: #991b1b; }
    .badge-yearly { background: rgba(139, 92, 246, 0.1); color: #6d28d9; }

    .search-box { position: relative; margin-bottom: 15px; }
    .search-box .form-control { padding-left: 40px; border-radius: 12px; }
    .search-box i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--primary-color); }

    .assignee-counter {
        display: inline-block;
        background: var(--primary-gradient);
        color: white;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 12px;
        margin-left: 10px;
    }

    .installment-card {
        border: 2px solid var(--border-color);
        border-radius: 14px;
        margin-bottom: 15px;
        overflow: hidden;
    }

    .installment-card .card-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-bottom: 2px solid var(--border-color);
        padding: 10px 15px;
    }

    .installments-count {
        background: var(--primary-gradient);
        color: white;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .installment-total {
        text-align: right;
        font-weight: 600;
        color: var(--success-color);
        font-size: 16px;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 2px solid var(--border-color);
    }

    .fee-duration-info {
        background: rgba(16, 185, 129, 0.05);
        border: 2px solid var(--success-color);
        border-radius: 14px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .selection-type-card {
        background: white;
        border: 2px solid var(--border-color);
        border-radius: 14px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        margin-bottom: 10px;
    }

    .selection-type-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.1);
    }

    .selection-type-card.selected {
        border-color: var(--primary-color);
        background: rgba(67, 97, 238, 0.05);
    }

    .discount-card {
        transition: all 0.3s ease;
        border: 2px solid var(--border-color);
        border-radius: 14px;
    }

    .discount-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.1);
    }

    .discount-card.selected {
        border-color: var(--primary-color);
        background: rgba(67, 97, 238, 0.03);
    }

    .btn-outline-primary {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-outline-primary:hover {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
    }

    .btn-success {
        background: var(--success-gradient);
        border: none;
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .btn-success:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    }

    .form-control, .form-select {
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 10px 14px;
        transition: all 0.3s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
    }

    label {
        font-weight: 600;
        color: var(--text-dark);
    }

    .alert {
        border-radius: 14px;
        border: none;
    }

    .alert-success { background: var(--success-gradient); color: white; }
    .alert-danger { background: var(--danger-gradient); color: white; }
    .alert-info { background: var(--info-gradient); color: white; }
    .alert-warning { background: var(--warning-gradient); color: white; }

    .recurring-duration-display {
        background: rgba(67, 97, 238, 0.05);
        border: 2px dashed var(--primary-color);
        border-radius: 12px;
        padding: 15px;
        margin-top: 10px;
    }

    #assigneeContainer {
        height: 400px !important;
        overflow-y: scroll !important;
        padding: 10px !important;
    }

    @media (max-width: 768px) {
        .section-box { padding: 15px; }
    }
</style>

<div class="container-fluid">
    <div class="card shadow-sm p-4" style="border-radius: 20px; border: 2px solid var(--border-color);">
        <!-- Page Header -->
        <div style="background: var(--primary-gradient); color: white; padding: 1.5rem 2rem; border-radius: 16px; margin-bottom: 2rem; box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <div>
                <h3 style="margin: 0; font-weight: 700; display: flex; align-items: center; gap: 12px;">
                    <span style="background: rgba(255,255,255,0.2); padding: 10px; border-radius: 12px;">
                        <i class="fas fa-money-bill-wave"></i>
                    </span>
                    Assign Custom Fee
                </h3>
                @if(isset($fee) && $fee)
                    <p style="opacity: 0.9; margin: 8px 0 0 0;">Assign custom fee: <strong>{{ $fee->custom_fee_key }}</strong></p>
                @else
                    <p style="opacity: 0.9; margin: 8px 0 0 0;">Select a custom fee to assign</p>
                @endif
            </div>
            <a href="{{ route('admin.custom-fees.index') }}" style="background: rgba(255,255,255,0.2); color: white; border: 2px solid rgba(255,255,255,0.3); padding: 10px 20px; border-radius: 12px; font-weight: 600; transition: all 0.3s; text-decoration: none;">
                <i class="fas fa-arrow-left me-1"></i>Back to List
            </a>
        </div>
        <hr style="border-color: var(--border-color);">

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

        <form method="POST" action="{{ route('admin.custom-fees.assign.store') }}" id="assignFeeForm">
            @csrf
            
            @if(isset($fee) && $fee)
            <input type="hidden" name="custom_reference_id" value="{{ $fee->custom_reference_id }}">
            <input type="hidden" name="academic_year" value="{{ $fee->academic_year }}">
            
            <div class="fee-duration-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1"><i class="fas fa-calendar-alt me-2"></i>Fee Duration Settings</h6>
                        <p class="mb-0 text-muted">Configured during fee creation</p>
                        @if($fee->fee_type == 'recurring')
                        <div class="recurring-duration-display mt-3">
                            <div class="d-flex align-items-center">
                                <div class="recurring-info-icon">
                                    <i class="fas fa-sync-alt"></i>
                                </div>
                                <div>
                                    <div class="recurring-duration-text">
                                        <i class="fas fa-calendar-check me-2"></i>
                                        Recurring Fee: {{ ucfirst($fee->fee_duration_type) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <span class="badge" style="background: {{ $fee->fee_type == 'one_time' ? 'var(--success-gradient)' : 'var(--info-gradient)' }}; color: white; padding: 6px 14px; border-radius: 30px; font-weight: 600;">
                        @if($fee->fee_type == 'one_time') One Time Fee @else Recurring ({{ ucfirst($fee->fee_duration_type) }}) @endif
                    </span>
                </div>
            </div>
            @else
            <div class="section-box">
                <div class="section-header">
                    <div class="section-icon">💰</div>
                    <h5 class="section-title">Step 0: Select Custom Fee</h5>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Select Custom Fee *</label>
                        <select name="custom_reference_id" id="custom_reference_id" class="form-control" required>
                            <option value="">Select Custom Fee</option>
                            @foreach($customFees as $customFee)
                                <option value="{{ $customFee->custom_reference_id }}"
                                    data-amount="{{ $customFee->custom_fee_value }}"
                                    data-name="{{ $customFee->custom_fee_key }}"
                                    data-type="{{ $customFee->fee_type }}"
                                    data-duration="{{ $customFee->fee_duration_type }}"
                                    data-academic-year="{{ $customFee->academic_year }}"
                                    data-late-fee-type="{{ $customFee->late_fee_type }}"
                                    data-late-fee-value="{{ $customFee->late_fee_value }}"
                                    data-partial-fee-type="{{ $customFee->partially_fee_type }}"
                                    data-partial-fee-value="{{ $customFee->partially_fee_value }}">
                                    {{ $customFee->custom_fee_key }} (₹{{ $customFee->custom_fee_value }}) - {{ ucfirst($customFee->fee_type) }}
                                </option>
                            @endforeach
                        </select>
                        <small style="color: var(--text-muted);">Select the custom fee to assign</small>
                    </div>
                </div>
                <div id="feePreviewSection" style="display: none; margin-top: 20px;">
                    <div class="fee-duration-info">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1"><i class="fas fa-calendar-alt me-2"></i>Selected Fee Details</h6>
                                <p class="mb-0 text-muted" id="selectedFeeName"></p>
                                <div class="recurring-duration-display mt-3" id="recurringInfoDisplay" style="display: none;">
                                    <div class="d-flex align-items-center">
                                        <div class="recurring-info-icon"><i class="fas fa-sync-alt"></i></div>
                                        <div>
                                            <div class="recurring-duration-text" id="recurringDurationText"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span class="badge" id="feeTypeBadge" style="padding: 6px 14px; border-radius: 30px; font-weight: 600; color: white;"></span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Step 1: Select Assignee Type -->
            <div class="section-box">
                <div class="section-header">
                    <div class="section-icon">👥</div>
                    <h5 class="section-title">Step 1: Select Assignee Type</h5>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="assignee_type" id="assignee_student" value="student" checked>
                            <label class="form-check-label" for="assignee_student"><i class="fas fa-user-graduate me-1"></i>Student</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="assignee_type" id="assignee_employee" value="employee">
                            <label class="form-check-label" for="assignee_employee"><i class="fas fa-briefcase me-1"></i>Employee</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="assignee_type" id="assignee_department" value="department">
                            <label class="form-check-label" for="assignee_department"><i class="fas fa-building me-1"></i>Department</label>
                        </div>
                    </div>
                </div>

                <div class="row mt-3" id="departmentSection">
                    <div class="col-md-6">
                        <label class="form-label">Select Department *</label>
                        <select name="department_id" id="department_id" class="form-control" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->department_id }}">{{ $department->department }}</option>
                            @endforeach
                        </select>
                        <small style="color: var(--text-muted);">Select the department for fee assignment</small>
                    </div>
                </div>

                <div class="row mt-3" id="classSelectionSection" style="display: none;">
                    <div class="col-md-12">
                        <div class="assignee-selection-type" style="background: #f8fafc; border: 2px solid var(--border-color); border-radius: 14px; padding: 15px; margin-bottom: 15px;">
                            <div class="class-selection-header">
                                <i class="fas fa-info-circle"></i>
                                <h6 class="mb-0">Select Assignment Scope</h6>
                            </div>
                            <p class="small text-muted mb-3">Choose whether to assign fee to the entire department or specific classes</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="selection-type-card" id="wholeDepartmentCard" data-type="whole_department">
                                        <div class="selection-type-icon"><i class="fas fa-building"></i></div>
                                        <div class="selection-type-title">Whole Department</div>
                                        <div class="selection-type-description">Assign fee to all students in the entire department</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="selection-type-card" id="classWiseCard" data-type="class_wise">
                                        <div class="selection-type-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                                        <div class="selection-type-title">Class Wise</div>
                                        <div class="selection-type-description">Select specific classes within the department</div>
                                    </div>
                                </div>
                            </div>
                            <div class="class-selection" id="classSelection" style="display: none; background: #fff3cd; border: 2px solid #ffc107; border-radius: 14px; padding: 15px; margin-top: 15px;">
                                <div class="class-selection-header"><i class="fas fa-chalkboard"></i><h6 class="mb-0">Select Classes</h6></div>
                                <div id="classesLoading" class="text-center py-3" style="display: none;"><div class="spinner-border spinner-border-sm" style="color: var(--primary-color);"></div><span class="ms-2">Loading classes...</span></div>
                                <div id="classesContainer"><div class="text-center text-muted py-3"><i class="fas fa-chalkboard-teacher fa-2x mb-3"></i><p>Select "Class Wise" option and choose a department to view classes</p></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Select Assignee (Individual) -->
            <div class="section-box" id="individualAssigneeSection">
                <div class="section-header">
                    <div class="section-icon">👤</div>
                    <h5 class="section-title">Step 2: Select Assignee <span id="assigneeCount" class="assignee-counter" style="display: none;">0</span></h5>
                </div>
                <div id="assigneeLoading" class="text-center py-5" style="display: none;"><div class="spinner-border" style="color: var(--primary-color);"></div><p class="mt-2">Loading assignees...</p></div>
                <div id="searchContainer" class="search-box" style="display: none;"><i class="fas fa-search"></i><input type="text" id="searchAssignee" class="form-control" placeholder="Search by name, ID, or registration number..."></div>
                <div id="assigneeContainer" class="mb-3"><div class="text-center text-muted py-4"><i class="fas fa-users fa-2x mb-3"></i><p>Please select a department to view assignees</p></div></div>
                <div id="selectedAssigneeInfo" class="alert alert-info mt-3" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><strong><span id="selectedName"></span></strong><div class="small mt-1">ID: <span id="selectedId"></span> • Type: <span id="selectedType"></span></div></div>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="clearSelection"><i class="fas fa-times me-1"></i>Clear</button>
                    </div>
                </div>
                <div id="academicYearInfo" class="academic-year-info mt-3" style="display: none;"></div>
            </div>

            <!-- Step 3: Custom Fee Details -->
            @if(isset($fee) && $fee)
            <div class="section-box">
                <div class="section-header"><div class="section-icon">💰</div><h5 class="section-title">Step 3: Custom Fee Details</h5></div>
                <div class="row">
                    <div class="col-md-6"><label class="form-label">Custom Fee Name</label><input type="text" class="form-control" value="{{ $fee->custom_fee_key }}" readonly></div>
                    <div class="col-md-6"><label class="form-label">Fee Amount</label><div class="input-group"><span class="input-group-text">₹</span><input type="text" class="form-control" value="{{ number_format($fee->custom_fee_value, 2) }}" readonly></div></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4"><label class="form-label">Fee Type</label><input type="text" class="form-control" value="{{ ucfirst($fee->fee_type) }}" readonly></div>
                    <div class="col-md-4"><label class="form-label">Payment Duration</label><input type="text" class="form-control" value="{{ $fee->fee_type == 'one_time' ? 'One Time' : ucfirst($fee->fee_duration_type) }}" readonly></div>
                    <div class="col-md-4"><label class="form-label">Academic Year</label><input type="text" class="form-control" value="{{ $fee->academic_year }}" readonly></div>
                </div>
            </div>
            @endif

            <!-- Step 4: Installment Details -->
            <div class="section-box" id="installmentsSection">
                <div class="section-header"><div class="section-icon">📅</div><h5 class="section-title">Step 4: Installment Details <span id="installmentsCount" class="installments-count">0</span></h5></div>
                <div id="installmentDurationInfo" class="alert alert-info mb-3" style="display: none;"><i class="fas fa-info-circle me-2"></i><span id="durationInfoText"></span></div>
                <div class="installment-container" id="installmentsContainer"><div id="noInstallmentsMessage" class="text-center text-muted py-4"><i class="fas fa-calendar-alt fa-2x mb-3"></i><p>Please select assignees to configure installments</p></div></div>
                <div id="installmentsTotal" class="installment-total" style="display: none;">Total Installment Amount: ₹<span id="totalInstallmentAmount">0.00</span></div>
            </div>

            <!-- Step 5: Available Discounts -->
            <div class="section-box discount-section">
                <div class="section-header"><div class="section-icon">🎫</div><h5 class="section-title">Step 5: Apply Discount (Optional)</h5></div>
                <div id="discountsInfo" class="alert alert-info mb-3"><i class="fas fa-info-circle me-2"></i>Select from available discounts for custom fees. Discounts will be recorded but not deducted from the fee.</div>
                <div id="discountsLoading" class="text-center py-5" style="display: none;"><div class="spinner-border" style="color: var(--primary-color);"></div><p class="mt-2">Loading available discounts...</p></div>
                <div id="discountsContainer" class="mb-3"><div class="text-center text-muted py-4"><i class="fas fa-tag fa-2x mb-3"></i><p>Complete steps 1-4 to view available discounts</p></div></div>
                <div id="selectedDiscountInfo" class="alert alert-success mt-3" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><strong>Selected Discount: <span id="selectedDiscountName"></span></strong><div class="small mt-1">Code: <span id="selectedDiscountCode" class="badge" style="background: var(--primary-gradient); color: white;"></span> • Type: <span id="selectedDiscountType" class="badge" style="background: var(--info-gradient); color: white;"></span> • Value: <span id="selectedDiscountValue" class="fw-bold"></span></div></div>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="clearDiscountSelection"><i class="fas fa-times me-1"></i>Remove</button>
                    </div>
                </div>
            </div>

            <!-- Step 6: Discount Applicability -->
            <div class="section-box" id="discountApplicabilitySection" style="display: none;">
                <div class="section-header"><div class="section-icon">⚙️</div><h5 class="section-title">Step 6: Discount Applicability</h5></div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">How should the discount be applied? *</label>
                        <div class="mb-3">
                            <div class="form-check"><input class="form-check-input" type="radio" name="discount_applicability" id="discount_total" value="annual" checked><label class="form-check-label" for="discount_total"><i class="fas fa-calendar-alt me-2"></i><strong>Apply to Total Fee</strong></label></div>
                            <div class="form-check mt-2"><input class="form-check-input" type="radio" name="discount_applicability" id="discount_per_installment" value="per_installment"><label class="form-check-label" for="discount_per_installment"><i class="fas fa-calendar-check me-2"></i><strong>Apply Per Installment</strong></label></div>
                        </div>
                    </div>
                    <div class="col-md-6"><div id="discountDurationInfo" class="alert alert-info"><i class="fas fa-info-circle me-2"></i><strong>Selected:</strong> Total Fee</div></div>
                </div>
            </div>

            <!-- Step 7: Fee Summary -->
            <div class="section-box">
                <div class="section-header"><div class="section-icon">📋</div><h5 class="section-title">Fee Summary</h5></div>
                <div id="feeSummary" class="fee-summary-card" style="display: none;">
                    <div class="text-center mb-4"><span class="duration-badge" id="durationBadge">-</span></div>
                    <div class="fee-row"><span>Fee Name:</span><span id="summary_fee_name">-</span></div>
                    <div class="fee-row"><span>Base Amount:</span><span id="summary_base_amount">₹0.00</span></div>
                    <div class="fee-row"><span>Fee Type:</span><span id="summary_fee_type">-</span></div>
                    <div class="fee-row"><span>Payment Duration:</span><span id="summary_duration">-</span></div>
                    <div class="fee-row"><span>Total Installments:</span><span id="summary_installments">0</span></div>
                    <div class="fee-row" id="discountRow" style="display: none;"><span>Discount (Recorded):</span><span id="summary_discount">₹0.00</span></div>
                    <div class="fee-row total"><span>Total Payable:</span><span id="summary_total">₹0.00</span></div>
                </div>
                <div id="noSummaryMessage" class="text-center text-muted py-4"><i class="fas fa-calculator fa-2x mb-3"></i><p>Select a custom fee and assignees to see fee summary</p></div>
            </div>

            <!-- Hidden Fields -->
            <input type="hidden" name="assignee_id" id="assignee_id">
            <input type="hidden" name="assignee_name" id="assignee_name">
            <input type="hidden" name="assignee_type_display" id="assignee_type_display" style="display:none;">
            <input type="hidden" name="academic_year_id" id="academic_year_id">
            <input type="hidden" name="discount_id" id="discount_id">
            <input type="hidden" name="discount_coupon_code" id="discount_coupon_code">
            <input type="hidden" name="discount_applicability" id="discount_applicability_hidden" value="annual">
            <input type="hidden" name="discount_duration_type" id="discount_duration_type_hidden">
            <input type="hidden" name="fee_duration" id="fee_duration" value="{{ isset($fee) ? $fee->fee_duration_type : '' }}">
            <input type="hidden" name="assignment_type" id="assignment_type" value="individual">
            <input type="hidden" name="selected_classes" id="selected_classes">
            <input type="hidden" name="selection_scope" id="selection_scope" value="whole_department">

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success btn-lg px-5" id="submitBtn" disabled>
                    <i class="fas fa-check-circle me-2"></i>Assign Custom Fee
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// ALL JAVASCRIPT REMAINS EXACTLY THE SAME - NOT CHANGED
$(document).ready(function() {
    let selectedAssignee = null;
    let selectedDiscount = null;
    let baseFee = 0;
    let allAssignees = [];
    let filteredAssignees = [];
    let academicYearData = null;
    let customFeeData = null;
    let discountApplicability = 'annual';
    let selectedClasses = [];
    let assignmentType = 'individual';
    let selectionScope = 'whole_department';

    @if(isset($fee) && $fee)
        customFeeData = {
            id: "{{ $fee->custom_reference_id }}",
            name: "{{ $fee->custom_fee_key }}",
            type: "{{ $fee->fee_type }}",
            baseAmount: {{ $fee->custom_fee_value }},
            lateFee: {{ $fee->late_fee_amount }},
            partialFee: {{ $fee->partially_fee_amount ?? 0 }},
            totalOneTime: {{ $fee->custom_fee_value }},
            feeDuration: "{{ $fee->fee_duration_type }}",
            isRecurring: {{ $fee->fee_type == 'recurring' ? 'true' : 'false' }},
            academicYear: "{{ $fee->academic_year }}"
        };
        baseFee = {{ $fee->custom_fee_value }};
        $('#discount_duration_type_hidden').val("{{ $fee->fee_duration_type }}");
        $('#selection_scope').val(selectionScope);
        updateFeeSummaryForBaseFee();
        setTimeout(function() {
            const assigneeType = $('input[name="assignee_type"]:checked').val();
            const departmentId = $('#department_id').val();
            if (assigneeType === 'department' && departmentId && customFeeData) {
                showInstallmentInputs();
            }
        }, 300);
    @else
        $('#custom_reference_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            if (!$(this).val()) { $('#feePreviewSection').hide(); customFeeData = null; baseFee = 0; updateSubmitButton(); hideInstallmentsAndDiscounts(); return; }
            baseFee = parseFloat(selectedOption.data('amount')) || 0;
            const customReferenceId = $(this).val();
            const feeName = selectedOption.data('name') || '';
            const feeType = selectedOption.data('type') || '';
            const feeDuration = selectedOption.data('duration') || 'one_time';
            const academicYear = selectedOption.data('academic-year') || '';
            const lateFeeType = selectedOption.data('late-fee-type') || 'none';
            const lateFeeValue = parseFloat(selectedOption.data('late-fee-value')) || 0;
            const partialFeeType = selectedOption.data('partial-fee-type') || 'none';
            const partialFeeValue = parseFloat(selectedOption.data('partial-fee-value')) || 0;
            $('#fee_duration').val(feeDuration);
            let lateFee = 0;
            if (lateFeeType === 'fixed') { lateFee = lateFeeValue; }
            else if (lateFeeType === 'percentage') { lateFee = (baseFee * lateFeeValue) / 100; }
            let partialFee = 0;
            if (partialFeeType === 'fixed') { partialFee = partialFeeValue; }
            else if (partialFeeType === 'percentage') { partialFee = (baseFee * partialFeeValue) / 100; }
            customFeeData = { id: customReferenceId, name: feeName, type: feeType, baseAmount: baseFee, lateFee: lateFee, partialFee: partialFee, totalOneTime: baseFee, feeDuration: feeDuration, isRecurring: feeType === 'recurring', academicYear: academicYear };
            $('#selectedFeeName').text(feeName);
            $('#feeTypeBadge').text(feeType === 'one_time' ? 'One Time Fee' : 'Recurring (' + feeDuration + ')').removeClass('bg-success bg-info').css('background', feeType === 'one_time' ? 'var(--success-gradient)' : 'var(--info-gradient)');
            if (feeType === 'recurring') { $('#recurringDurationText').html('<i class="fas fa-calendar-check me-2"></i>Recurring Fee: ' + feeDuration.charAt(0).toUpperCase() + feeDuration.slice(1)); $('#recurringInfoDisplay').show(); }
            else { $('#recurringInfoDisplay').hide(); }
            $('#feePreviewSection').show();
            $('#discount_duration_type_hidden').val(feeDuration);
            updateFeeSummaryForBaseFee();
            const assigneeType = $('input[name="assignee_type"]:checked').val();
            const departmentId = $('#department_id').val();
            if (assigneeType === 'department') {
                if (departmentId) { showInstallmentInputs(); }
                else { showDepartmentInstallmentMessage('Please select a department to configure installments'); }
            } else { if (selectedAssignee) { showInstallmentInputs(); } }
            updateSubmitButton();
        });
    @endif

    $('input[name="assignee_type"]').on('change', function() {
        const assigneeType = $(this).val();
        const departmentId = $('#department_id').val();
        if (assigneeType === 'department') {
            $('#individualAssigneeSection').hide(); $('#classSelectionSection').show(); $('#assignment_type').val('department'); assignmentType = 'department'; clearAssigneeSelection();
            $('#wholeDepartmentCard').addClass('selected'); $('#classWiseCard').removeClass('selected'); selectionScope = 'whole_department'; $('#selection_scope').val(selectionScope); $('#classSelection').hide();
            if (customFeeData) { if (departmentId) { showInstallmentInputs(); } else { showDepartmentInstallmentMessage('Please select a department to configure installments'); } }
            else { showDepartmentInstallmentMessage('Please select a custom fee first to configure installments'); }
        } else {
            $('#individualAssigneeSection').show(); $('#classSelectionSection').hide(); $('#assignment_type').val('individual'); assignmentType = 'individual'; selectedClasses = []; $('#selected_classes').val('');
            if (departmentId) { loadAssignees(departmentId, assigneeType); } else { resetAssigneeSection(); }
        }
        updateSubmitButton();
    });

    $('#department_id').on('change', function() {
        const departmentId = $(this).val();
        const assigneeType = $('input[name="assignee_type"]:checked').val();
        if (!departmentId) {
            if (assigneeType === 'department') { $('#classSelectionSection').hide(); $('#classesContainer').empty(); showDepartmentInstallmentMessage('Please select a department to configure installments'); hideDiscounts(); updateFeeSummary(); }
            else { resetAssigneeSection(); }
            updateSubmitButton(); return;
        }
        if (assigneeType === 'department') {
            $('#classSelectionSection').show();
            if (selectionScope === 'class_wise') { loadClassesForDepartment(departmentId); }
            if (customFeeData) { showInstallmentInputs(); loadAvailableDiscounts(customFeeData.id); }
            else { showDepartmentInstallmentMessage('Please select a custom fee first to configure installments'); }
        } else { loadAssignees(departmentId, assigneeType); }
        updateSubmitButton();
    });

    $(document).on('click', '.selection-type-card', function() {
        $('.selection-type-card').removeClass('selected'); $(this).addClass('selected');
        selectionScope = $(this).data('type'); $('#selection_scope').val(selectionScope);
        if (selectionScope === 'class_wise') { $('#classSelection').show(); const departmentId = $('#department_id').val(); if (departmentId) { loadClassesForDepartment(departmentId); } }
        else { $('#classSelection').hide(); selectedClasses = []; $('#selected_classes').val(''); }
        if (customFeeData && $('#department_id').val()) { showInstallmentInputs(); }
        updateSubmitButton(); updateFeeSummary();
    });

    function loadClassesForDepartment(departmentId) {
        if (selectionScope !== 'class_wise') return;
        $('#classesLoading').show(); $('#classesContainer').empty();
        $.ajax({
            url: '{{ route("ajax.course.types.by.department") }}', method: 'GET', data: { department_id: departmentId },
            success: function(response) {
                $('#classesLoading').hide();
                if (response.status === 'success' && response.courses && response.courses.length > 0) {
                    let html = '';
                    response.courses.forEach(function(course) {
                        const isSelected = selectedClasses.includes(course.finacp_merchant_sub_category_id.toString());
                        html += `<div class="class-card ${isSelected ? 'selected' : ''}" data-id="${course.finacp_merchant_sub_category_id}" data-name="${course.finacp_merchant_sub_category_type}" style="background: white; border: 2px solid var(--border-color); border-radius: 12px; padding: 10px 15px; margin-bottom: 8px; cursor: pointer;"><div class="d-flex justify-content-between align-items-center"><h6 class="mb-1">${course.finacp_merchant_sub_category_type}</h6>${isSelected ? '<i class="fas fa-check text-success"></i>' : ''}</div></div>`;
                    });
                    $('#classesContainer').html(html);
                    $('.class-card').on('click', function() {
                        const classId = $(this).data('id').toString();
                        if ($(this).hasClass('selected')) { $(this).removeClass('selected'); selectedClasses = selectedClasses.filter(id => id !== classId); }
                        else { $(this).addClass('selected'); selectedClasses.push(classId); }
                        $('#selected_classes').val(JSON.stringify(selectedClasses)); updateSubmitButton(); if (customFeeData) { updateFeeSummary(); }
                    });
                }
            }
        });
    }

    function loadAssignees(departmentId, assigneeType) {
        $('#assigneeLoading').show(); $('#assigneeContainer').hide(); $('#searchContainer').hide(); $('#selectedAssigneeInfo').hide(); $('#academicYearInfo').hide();
        const url = assigneeType === 'student' ? '{{ route("ajax.students.by.department") }}' : '{{ route("ajax.employees.by.department") }}';
        $.ajax({
            url: url, method: 'GET', data: { department_id: departmentId },
            success: function(response) {
                $('#assigneeLoading').hide();
                if (response.success === true) {
                    let assignees = [];
                    if (assigneeType === 'student' && response.students && response.students.length > 0) { assignees = response.students; }
                    else if (assigneeType === 'employee' && response.employees && response.employees.length > 0) { assignees = response.employees; }
                    if (assignees.length > 0) { allAssignees = assignees; filteredAssignees = [...allAssignees]; renderAssignees(); $('#assigneeCount').text(assignees.length).show(); $('#searchContainer').show(); $('#searchAssignee').val(''); }
                    else { showNoResults('No ' + assigneeType + 's found in this department'); }
                } else { showNoResults('No ' + assigneeType + 's found in this department'); }
            },
            error: function() { $('#assigneeLoading').hide(); showError('Error loading ' + assigneeType + 's. Please try again.'); }
        });
    }

    function renderAssignees() {
        const assigneeType = $('input[name="assignee_type"]:checked').val();
        let html = '';
        if (filteredAssignees.length === 0) { html = '<div class="no-results"><i class="fas fa-user-slash"></i><p class="mt-3">No ' + assigneeType + 's found</p></div>'; }
        else {
            filteredAssignees.forEach(function(assignee) {
                let assigneeId, assigneeName, assigneeIdentifier;
                if (assigneeType === 'student') { assigneeId = assignee.student_hash_id; assigneeName = `${assignee.first_name || ''} ${assignee.middle_name || ''} ${assignee.last_name || ''}`.trim(); assigneeIdentifier = assignee.registration_number || 'N/A'; }
                else { assigneeId = assignee.employee_id; assigneeName = assignee.name || 'Unknown'; assigneeIdentifier = assignee.employee_id || 'N/A'; }
                const initials = assigneeName.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
                const isSelected = selectedAssignee && selectedAssignee.id === assigneeId;
                html += `<div class="assignee-card ${isSelected ? 'selected' : ''}" data-id="${assigneeId}" data-name="${assigneeName}" data-identifier="${assigneeIdentifier}" data-type="${assigneeType}"><div class="d-flex align-items-center"><div class="assignee-avatar me-3">${initials}</div><div class="flex-grow-1"><h6 class="mb-1">${assigneeName}</h6><div class="small text-muted">${assigneeType === 'student' ? 'Reg No.' : 'Employee ID'}: ${assigneeIdentifier}</div></div>${isSelected ? '<i class="fas fa-check text-success ms-2"></i>' : ''}</div></div>`;
            });
        }
        $('#assigneeContainer').html(html).show();
        $('.assignee-card').off('click').on('click', function() {
            const card = $(this);
            if (card.hasClass('selected')) { clearAssigneeSelection(); }
            else { selectAssignee(card.data('id'), card.data('name'), card.data('identifier'), card.data('type'), card); }
        });
    }

    function selectAssignee(assigneeId, assigneeName, assigneeIdentifier, assigneeType, card) {
        $('.assignee-card').removeClass('selected'); card.addClass('selected');
        selectedAssignee = { id: assigneeId, name: assigneeName, identifier: assigneeIdentifier, type: assigneeType };
        $('#assignee_id').val(assigneeId); $('#assignee_name').val(assigneeName); $('#assignee_type_display').val(assigneeType);
        $('#selectedName').text(assigneeName); $('#selectedId').text(assigneeIdentifier); $('#selectedType').text(assigneeType.charAt(0).toUpperCase() + assigneeType.slice(1));
        $('#selectedAssigneeInfo').show(); loadAcademicYear(assigneeType, assigneeId); showInstallmentInputs();
    }

    function loadAcademicYear(assigneeType, assigneeId) {
        let url = ''; let data = {};
        if (assigneeType === 'student') { url = '{{ route("ajax.student.academic-year") }}'; data = { student_hash_id: assigneeId }; }
        else { url = '{{ route("ajax.employee.academic-year.transport") }}'; data = {}; }
        $.ajax({
            url: url, method: 'GET', data: data,
            success: function(response) {
                if (response.status === true && response.academic_year) { academicYearData = response.academic_year; $('#academic_year_id').val(academicYearData.id); $('#academicYearInfo').html('<div class="alert alert-success"><i class="fas fa-graduation-cap me-2"></i>Academic Year: ' + (academicYearData.name || academicYearData.id) + '</div>').show(); }
                else { $('#academicYearInfo').html('<div class="alert alert-warning"><i class="fas fa-info-circle me-2"></i>No Academic Year Found</div>').show(); const today = new Date(); academicYearData = { start_date: today.toISOString().split('T')[0], end_date: new Date(today.getFullYear() + 1, today.getMonth(), today.getDate()).toISOString().split('T')[0], total_months: 12 }; }
            }
        });
    }

    function showInstallmentInputs() {
        if (!customFeeData) { if (assignmentType === 'department') { showDepartmentInstallmentMessage('Please select a custom fee first to configure installments'); } else { $('#installmentsContainer').html('<div id="noInstallmentsMessage" class="text-center text-muted py-4"><i class="fas fa-calendar-alt fa-2x mb-3"></i><p>Please select a custom fee first to configure installments</p></div>'); } $('#noInstallmentsMessage').show(); return; }
        if (assignmentType === 'individual' && !selectedAssignee) return;
        if (assignmentType === 'department') { const departmentId = $('#department_id').val(); if (!departmentId) { showDepartmentInstallmentMessage('Please select a department to configure installments'); return; } }
        $('#installmentsContainer').empty(); $('#noInstallmentsMessage').hide(); $('#installmentsSection').show();
        const duration = customFeeData.feeDuration || $('#fee_duration').val();
        let installmentCount = 0; let installmentName = ''; let totalAmount = customFeeData.baseAmount; let installmentAmount = 0; let durationText = '';
        switch (duration) {
            case 'one_time': installmentCount = 1; installmentName = 'Full Payment'; installmentAmount = totalAmount; durationText = 'One Time Payment'; break;
            case 'monthly': installmentCount = 12; installmentName = 'Month'; installmentAmount = totalAmount; durationText = 'Monthly (12 installments - Full amount each)'; break;
            case 'quarterly': installmentCount = 4; installmentName = 'Quarter'; installmentAmount = totalAmount; durationText = 'Quarterly (4 installments - Full amount each)'; break;
            case 'half_yearly': installmentCount = 2; installmentName = 'Half Year'; installmentAmount = totalAmount; durationText = 'Half Yearly (2 installments - Full amount each)'; break;
            case 'yearly': installmentCount = 1; installmentName = 'Year'; installmentAmount = totalAmount; durationText = 'Yearly (1 installment)'; break;
        }
        installmentAmount = Math.round(installmentAmount * 100) / 100;
        let startDate = ''; let dueDate = '';
        if (assignmentType === 'individual' && academicYearData) { startDate = academicYearData.start_date; dueDate = academicYearData.end_date; }
        else { const today = new Date(); startDate = today.toISOString().split('T')[0]; const due = new Date(today); due.setMonth(due.getMonth() + 1); dueDate = due.toISOString().split('T')[0]; }
        $('#durationInfoText').text(durationText); $('#installmentDurationInfo').show();
        for (let i = 1; i <= installmentCount; i++) {
            const installmentHtml = `<div class="card mb-3 installment-card" data-index="${i}"><div class="card-header bg-light d-flex justify-content-between align-items-center"><h6 class="mb-0">${installmentName} ${i}</h6><div class="d-flex align-items-center"><span class="badge" style="background: var(--primary-gradient); color: white; margin-right: 8px;">${duration === 'one_time' ? 'Full Amount' : duration === 'monthly' ? 'Monthly' : duration === 'quarterly' ? 'Quarterly' : duration === 'half_yearly' ? 'Half-Yearly' : 'Yearly'}</span><span class="badge" style="background: var(--success-gradient); color: white;">₹${installmentAmount.toFixed(2)}</span></div></div><div class="card-body"><div class="row"><div class="col-md-4 mb-3"><label class="form-label">Amount (₹) *</label><input type="number" name="installments[${i}][amount]" class="form-control installment-amount" value="${installmentAmount.toFixed(2)}" step="0.01" min="0" required readonly></div><div class="col-md-4 mb-3"><label class="form-label">Start Date *</label><input type="date" name="installments[${i}][start_date]" class="form-control installment-start-date" value="${startDate}" required></div><div class="col-md-4 mb-3"><label class="form-label">Due Date *</label><input type="date" name="installments[${i}][due_date]" class="form-control installment-due-date" value="${dueDate}" required></div></div></div></div>`;
            $('#installmentsContainer').append(installmentHtml);
        }
        $('#installmentsCount').text(`${installmentCount} Installment${installmentCount > 1 ? 's' : ''}`).show();
        if (installmentCount > 1 && duration !== 'one_time') { autoFillInstallmentDatesForDepartment(duration, startDate, dueDate, installmentAmount); }
        updateInstallmentsTotal(); updateFeeSummary(); updateSubmitButton(); loadAvailableDiscounts(customFeeData.id);
    }

    function autoFillInstallmentDatesForDepartment(duration, startDateStr, endDateStr, installmentAmount) {
        const startDate = new Date(startDateStr); const endDate = new Date(endDateStr);
        $('.installment-card').each(function(index) {
            const cardIndex = $(this).data('index'); let installmentStartDate, installmentDueDate;
            switch (duration) {
                case 'one_time': installmentStartDate = new Date(startDate); installmentDueDate = new Date(endDate); break;
                case 'monthly': installmentStartDate = new Date(startDate); installmentStartDate.setMonth(startDate.getMonth() + (cardIndex - 1)); installmentDueDate = new Date(installmentStartDate); installmentDueDate.setMonth(installmentStartDate.getMonth() + 1); break;
                case 'quarterly': installmentStartDate = new Date(startDate); installmentStartDate.setMonth(startDate.getMonth() + ((cardIndex - 1) * 3)); installmentDueDate = new Date(installmentStartDate); installmentDueDate.setMonth(installmentStartDate.getMonth() + 3); break;
                case 'half_yearly': installmentStartDate = new Date(startDate); installmentStartDate.setMonth(startDate.getMonth() + ((cardIndex - 1) * 6)); installmentDueDate = new Date(installmentStartDate); installmentDueDate.setMonth(installmentStartDate.getMonth() + 6); break;
                case 'yearly': installmentStartDate = new Date(startDate); installmentDueDate = new Date(endDate); break;
            }
            if (installmentDueDate > endDate) { installmentDueDate = new Date(endDate); }
            $(this).find('.installment-start-date').val(installmentStartDate.toISOString().split('T')[0]);
            $(this).find('.installment-due-date').val(installmentDueDate.toISOString().split('T')[0]);
            $(this).find('.installment-amount').val(installmentAmount.toFixed(2));
        });
    }

    function updateInstallmentsTotal() { let total = 0; $('.installment-amount').each(function() { total += parseFloat($(this).val()) || 0; }); $('#totalInstallmentAmount').text(total.toFixed(2)); $('#installmentsTotal').show(); return total; }

    function loadAvailableDiscounts(customReferenceId) {
        if (!customReferenceId) return;
        $('#discountsLoading').show(); $('#discountsContainer').hide(); $('#noDiscountsMessage').hide(); $('#selectedDiscountInfo').hide();
        $.ajax({
            url: '{{ route("ajax.custom.available.discounts") }}', method: 'GET', data: { custom_reference_id: customReferenceId, assignee_type: assignmentType === 'department' ? 'department' : (selectedAssignee ? selectedAssignee.type : null), department_id: $('#department_id').val(), assignee_id: selectedAssignee ? selectedAssignee.id : null },
            success: function(response) { $('#discountsLoading').hide(); if (response.success && response.discounts && response.discounts.length > 0) { renderDiscounts(response.discounts); } else { showNoDiscountsAvailable(); } },
            error: function() { $('#discountsLoading').hide(); showNoDiscountsAvailable(); }
        });
    }

    function renderDiscounts(discounts) {
        let html = '';
        discounts.forEach(function(discount) {
            const discountValue = parseFloat(discount.value) || 0;
            const currentTotal = baseFee;
            let discountAmount = 0;
            if ((discount.type || 'flat') === 'percentage') { discountAmount = (currentTotal * discountValue) / 100; }
            else { discountAmount = discountValue; }
            if (discountAmount > currentTotal) { discountAmount = currentTotal; }
            const isSelected = selectedDiscount && selectedDiscount.hashId === discount.discount_hash_id;
            html += `<div class="card mb-3 discount-card ${isSelected ? 'selected' : ''}" data-id="${discount.id || ''}" data-hash-id="${discount.discount_hash_id || ''}" data-name="${discount.name || 'Unnamed Discount'}" data-code="${discount.coupon_code || 'N/A'}" data-type="${discount.type || 'flat'}" data-value="${discountValue}" data-description="${discount.description || ''}"><div class="card-body"><div class="d-flex justify-content-between align-items-start"><div class="flex-grow-1 me-3"><h6 class="mb-2">${discount.name || 'Unnamed Discount'}</h6><span class="badge" style="background: var(--primary-gradient); color: white;">${discount.coupon_code || 'N/A'}</span><span class="badge ms-2" style="background: var(--info-gradient); color: white;">${(discount.type || 'flat').toUpperCase()}</span></div><div class="text-end"><div class="discount-savings text-success mb-2">₹${discountAmount.toFixed(2)}</div><button type="button" class="btn btn-sm ${isSelected ? 'btn-outline-danger' : 'btn-primary'} select-discount-btn">${isSelected ? '<i class="fas fa-times me-1"></i>Remove' : '<i class="fas fa-check me-1"></i>Apply'}</button></div></div></div></div>`;
        });
        $('#discountsContainer').html(html).show();
        $(document).off('click', '.discount-card').on('click', '.discount-card', function(e) { if ($(e.target).closest('.select-discount-btn').length) return; if ($(this).hasClass('selected')) { clearDiscountSelection(); } else { selectDiscount($(this)); } });
        $(document).off('click', '.select-discount-btn').on('click', '.select-discount-btn', function(e) { e.stopPropagation(); if ($(this).hasClass('btn-primary')) { selectDiscount($(this).closest('.discount-card')); } else { clearDiscountSelection(); } });
    }

    function selectDiscount(card) {
        const discountData = { id: card.attr('data-id') || card.data('id') || '', hashId: card.attr('data-hash-id') || card.data('hash-id') || '', name: card.attr('data-name') || card.data('name') || 'Unnamed Discount', code: card.attr('data-code') || card.data('code') || 'N/A', type: card.attr('data-type') || card.data('type') || 'flat', value: parseFloat(card.attr('data-value') || card.data('value') || 0), description: card.attr('data-description') || card.data('description') || '' };
        $('.discount-card').removeClass('selected'); card.addClass('selected'); selectedDiscount = discountData;
        $('#discountApplicabilitySection').show(); $('#selectedDiscountName').text(selectedDiscount.name); $('#selectedDiscountCode').text(selectedDiscount.code); $('#selectedDiscountType').text(selectedDiscount.type.toUpperCase()); $('#selectedDiscountValue').text(selectedDiscount.type === 'percentage' ? `${selectedDiscount.value}%` : `₹${parseFloat(selectedDiscount.value).toFixed(2)}`); $('#selectedDiscountInfo').show();
        $('#discount_id').val(selectedDiscount.hashId || ''); $('#discount_coupon_code').val(selectedDiscount.code || '');
        $('.select-discount-btn').removeClass('btn-outline-danger').addClass('btn-primary').html('<i class="fas fa-check me-1"></i>Apply');
        card.find('.select-discount-btn').removeClass('btn-primary').addClass('btn-outline-danger').html('<i class="fas fa-times me-1"></i>Remove');
        updateDiscountApplicability(); updateFeeSummary();
    }

    function updateDiscountApplicability() {
        discountApplicability = $('input[name="discount_applicability"]:checked').val(); $('#discount_applicability_hidden').val(discountApplicability);
        if (discountApplicability === 'annual') { $('#discountDurationInfo').html('<i class="fas fa-info-circle me-2"></i><strong>Selected:</strong> Total Fee'); }
        else { const duration = customFeeData ? customFeeData.feeDuration : 'one_time'; $('#discountDurationInfo').html('<i class="fas fa-info-circle me-2"></i><strong>Selected:</strong> Per Installment (' + duration.charAt(0).toUpperCase() + duration.slice(1) + ')'); }
        updateFeeSummary();
    }

    function clearDiscountSelection() {
        selectedDiscount = null; $('.discount-card').removeClass('selected'); $('.select-discount-btn').removeClass('btn-outline-danger').addClass('btn-primary').html('<i class="fas fa-check me-1"></i>Apply');
        $('#selectedDiscountInfo').hide(); $('#discountApplicabilitySection').hide(); $('#discount_id').val(''); $('#discount_coupon_code').val(''); $('#discount_applicability_hidden').val('annual');
        updateFeeSummary();
    }

    function updateFeeSummaryForBaseFee() {
        if (!customFeeData) { $('#feeSummary').hide(); $('#noSummaryMessage').show(); return; }
        const duration = customFeeData.feeDuration; let installmentCount = 0;
        switch (duration) { case 'one_time': installmentCount = 1; break; case 'monthly': installmentCount = 12; break; case 'quarterly': installmentCount = 4; break; case 'half_yearly': installmentCount = 2; break; case 'yearly': installmentCount = 1; break; }
        $('#summary_fee_name').text(customFeeData.name); $('#summary_base_amount').text('₹' + customFeeData.baseAmount.toFixed(2)); $('#summary_fee_type').text(customFeeData.type.charAt(0).toUpperCase() + customFeeData.type.slice(1)); $('#summary_duration').text(duration.charAt(0).toUpperCase() + duration.slice(1)); $('#summary_installments').text(installmentCount); $('#summary_total').text('₹' + customFeeData.totalOneTime.toFixed(2));
        const badgeClass = 'badge-' + duration.replace('_', ''); $('#durationBadge').removeClass('badge-onetime badge-monthly badge-quarterly badge-halfyearly badge-yearly').addClass(badgeClass).text(duration.charAt(0).toUpperCase() + duration.slice(1));
        $('#feeSummary').show(); $('#noSummaryMessage').hide();
    }

    function updateFeeSummary() {
        if (assignmentType === 'department') { updateDepartmentFeeSummary(); return; }
        if (!customFeeData) { $('#feeSummary').hide(); $('#noSummaryMessage').show(); return; }
        if (!selectedAssignee && assignmentType === 'individual') { updateFeeSummaryForBaseFee(); return; }
        const installmentTotal = updateInstallmentsTotal();
        $('#summary_installments').text($('.installment-card').length);
        if (selectedDiscount) { $('#discountRow').show(); $('#summary_total').text('₹' + installmentTotal.toFixed(2)); $('#discountAppliedBadge').show(); }
        else { $('#discountRow').hide(); $('#summary_total').text('₹' + installmentTotal.toFixed(2)); }
        $('#feeSummary').show(); $('#noSummaryMessage').hide();
    }

    function updateDepartmentFeeSummary() {
        const departmentId = $('#department_id').val(); if (!departmentId || !customFeeData) { $('#feeSummary').hide(); $('#noSummaryMessage').show(); return; }
        const installmentTotal = updateInstallmentsTotal();
        $('#summary_installments').text($('.installment-card').length);
        if (selectedDiscount) { $('#discountRow').show(); $('#summary_total').text('₹' + installmentTotal.toFixed(2)); }
        else { $('#discountRow').hide(); $('#summary_total').text('₹' + installmentTotal.toFixed(2)); }
        $('#feeSummary').show(); $('#noSummaryMessage').hide();
    }

    function updateSubmitButton() { const assigneeType = $('input[name="assignee_type"]:checked').val(); let isValid = !customFeeData ? false : (assigneeType === 'department' ? ($('#department_id').val() && $('.installment-card').length > 0) : (selectedAssignee !== null && $('.installment-card').length > 0)); $('#submitBtn').prop('disabled', !isValid); }

    function resetAssigneeSection() { $('#assigneeContainer').html('<div class="text-center text-muted py-4"><i class="fas fa-users fa-2x mb-3"></i><p>Please select a department to view assignees</p></div>'); $('#searchContainer').hide(); $('#selectedAssigneeInfo').hide(); $('#academicYearInfo').hide(); $('#assigneeCount').hide(); clearAssigneeSelection(); }

    function clearAssigneeSelection() { $('.assignee-card').removeClass('selected'); selectedAssignee = null; $('#assignee_id').val(''); $('#assignee_name').val(''); $('#assignee_type_display').val(''); $('#selectedAssigneeInfo').hide(); $('#academicYearInfo').hide(); $('#installmentsContainer').empty(); $('#installmentsTotal').hide(); $('#noInstallmentsMessage').show(); $('#academic_year_id').val(''); academicYearData = null; hideDiscounts(); updateSubmitButton(); }

    function filterAssignees(searchTerm) { filteredAssignees = !searchTerm ? [...allAssignees] : allAssignees.filter(a => { const type = $('input[name="assignee_type"]:checked').val(); return type === 'student' ? ((a.first_name + ' ' + a.middle_name + ' ' + a.last_name).toLowerCase().includes(searchTerm) || (a.registration_number || '').toLowerCase().includes(searchTerm)) : ((a.name || '').toLowerCase().includes(searchTerm) || (a.employee_id || '').toLowerCase().includes(searchTerm)); }); renderAssignees(); }

    function showNoResults(message) { $('#assigneeContainer').html('<div class="no-results"><i class="fas fa-user-slash"></i><p class="mt-3">' + message + '</p></div>').show(); $('#searchContainer').hide(); $('#assigneeCount').hide(); clearAssigneeSelection(); }
    function showError(message) { $('#assigneeContainer').html('<div class="no-results"><i class="fas fa-exclamation-triangle text-danger"></i><p class="mt-3">' + message + '</p></div>').show(); clearAssigneeSelection(); }
    function showDepartmentInstallmentMessage(message) { $('#installmentsContainer').html('<div id="noInstallmentsMessage" class="text-center text-muted py-4"><i class="fas fa-calendar-alt fa-2x mb-3"></i><p>' + message + '</p></div>'); $('#installmentDurationInfo').hide(); $('#installmentsTotal').hide(); $('#installmentsCount').hide(); hideDiscounts(); }
    function showNoDiscountsAvailable() { $('#discountsContainer').hide(); $('#noDiscountsMessage').show(); $('#selectedDiscountInfo').hide(); $('#discountApplicabilitySection').hide(); }
    function hideDiscounts() { $('#discountsContainer').html('<div class="text-center text-muted py-4"><i class="fas fa-tag fa-2x mb-3"></i><p>Complete steps 1-4 to view available discounts</p></div>'); $('#selectedDiscountInfo').hide(); $('#noDiscountsMessage').hide(); $('#discountApplicabilitySection').hide(); }
    function hideInstallmentsAndDiscounts() { $('#installmentsContainer').empty(); $('#noInstallmentsMessage').show(); $('#installmentDurationInfo').hide(); $('#installmentsTotal').hide(); $('#installmentsCount').text('0').hide(); hideDiscounts(); $('#feeSummary').hide(); $('#noSummaryMessage').show(); updateSubmitButton(); }

    $(document).on('input', '#searchAssignee', function() { filterAssignees($(this).val().toLowerCase()); });
    $('#clearSelection').on('click', function() { clearAssigneeSelection(); });
    $('#clearDiscountSelection').on('click', function() { clearDiscountSelection(); });
    $(document).on('change', 'input[name="discount_applicability"]', function() { updateDiscountApplicability(); });
    $(document).on('input', '.installment-amount', function() { updateInstallmentsTotal(); updateFeeSummary(); });
    $(document).on('change', '.installment-start-date, .installment-due-date', function() { updateSubmitButton(); });
    $('#assignFeeForm').on('submit', function(e) { e.preventDefault(); $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...'); this.submit(); });

    @if(isset($fee) && $fee)
        updateFeeSummaryForBaseFee(); updateSubmitButton();
        const initialAssigneeType = $('input[name="assignee_type"]:checked').val();
        if (initialAssigneeType === 'department') { const departmentId = $('#department_id').val(); if (departmentId) { setTimeout(() => { showInstallmentInputs(); }, 500); } }
    @endif
});
</script>
@endsection